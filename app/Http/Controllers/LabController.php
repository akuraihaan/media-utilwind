<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Lab;
use App\Models\LabHistory;
use Illuminate\Support\Str;
use App\Models\LabSession;
class LabController extends Controller
{
    /**
     * =========================================================================
     * BAGIAN 1: ADMIN - LAB CONFIGURATION (CRUD)
     * =========================================================================
     */
    
    public function index()
    {
        // Ambil data labs urutkan dari terbaru
        $labs = DB::table('labs')->orderByDesc('created_at')->get();

        $totalLabs = $labs->count();
        // Gunakan ?? 0 untuk menghindari error jika kolom is_active belum ada
        $totalActive = $labs->filter(fn($l) => $l->is_active ?? 0)->count(); 
        
        return view('admin.lab_configuration', compact('labs', 'totalLabs', 'totalActive'));
    }

    /**
     * SIMPAN DATA BARU
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'duration' => 'required|integer|min:1', // Input name di form adalah 'duration'
            'passing_grade' => 'required|integer|min:0|max:100',
        ]);

        DB::table('labs')->insert([
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . time(),
            'description' => $request->description,
            // MAPPING PENTING: Input 'duration' -> Kolom DB 'duration_minutes'
            'duration_minutes' => $request->duration, 
            'passing_grade' => $request->passing_grade,
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['status' => 'success', 'message' => 'Lab created successfully']);
    }

    /**
     * UPDATE DATA (EDIT)
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'duration' => 'required|integer|min:1',
            'passing_grade' => 'required|integer|min:0|max:100',
        ]);
        
        // Cek apakah data ada
        $exists = DB::table('labs')->where('id', $id)->exists();
        if(!$exists) {
            return response()->json(['status' => 'error', 'message' => 'Lab not found'], 404);
        }

        DB::table('labs')->where('id', $id)->update([
            'title' => $request->title,
            // Opsional: Update slug jika judul berubah (biar URL tetap relevan)
            'slug' => Str::slug($request->title) . '-' . $id, 
            'description' => $request->description,
            // MAPPING PENTING: Input 'duration' -> Kolom DB 'duration_minutes'
            'duration_minutes' => $request->duration,
            'passing_grade' => $request->passing_grade,
            'updated_at' => now(),
        ]);

        return response()->json(['status' => 'success', 'message' => 'Lab updated successfully']);
    }

    /**
     * HAPUS DATA
     */
    public function destroy($id)
    {
        DB::table('labs')->where('id', $id)->delete();
        // Bersihkan data terkait agar tidak jadi sampah
        DB::table('lab_steps')->where('lab_id', $id)->delete(); 
        
        return response()->json(['status' => 'success', 'message' => 'Lab deleted']);
    }

    /**
     * TOGGLE STATUS (AKTIF/NONAKTIF)
     */
    public function toggleStatus($id)
    {
        $lab = DB::table('labs')->where('id', $id)->first();
        if($lab) {
            // Toggle nilai boolean (1 jadi 0, 0 jadi 1)
            DB::table('labs')->where('id', $id)->update(['is_active' => !($lab->is_active ?? 0)]);
        }
        return response()->json(['status' => 'success', 'message' => 'Status updated']);
    }


    /**
     * =========================================================================
     * BAGIAN 2: ADMIN - TASK MANAGER (DB: lab_steps)
     * =========================================================================
     */

    public function getTasks($labId)
    {
        $tasks = DB::table('lab_steps')
            ->where('lab_id', $labId)
            ->orderBy('order_index', 'asc')
            ->get();
        return response()->json($tasks);
    }

    public function storeTask(Request $request)
    {
        $request->validate([
            'lab_id' => 'required|integer', 
            'title' => 'required', 
            'initial_code' => 'required', 
            'validation_rules' => 'required', 
            'points' => 'required|integer'
        ]);

        $rulesArray = array_map('trim', explode(',', $request->validation_rules));

        DB::table('lab_steps')->insert([
            'lab_id' => $request->lab_id,
            'title' => $request->title,
            'instruction' => $request->instruction,
            'initial_code' => $request->initial_code,
            'validation_rules' => json_encode($rulesArray),
            'points' => $request->points,
            'order_index' => $request->order_index ?? 1,
            'created_at' => now(),
        ]);

        return response()->json(['status' => 'success', 'message' => 'Step added']);
    }

    public function destroyTask($id)
    {
        DB::table('lab_steps')->where('id', $id)->delete();
        return response()->json(['status' => 'success', 'message' => 'Step deleted']);
    }


    /**
     * =========================================================================
     * BAGIAN 3: ANALYTICS (GLOBAL & STUDENT)
     * =========================================================================
     */

    public function analytics($labId = null)
    {
        $query = DB::table('lab_histories');
        if ($labId) $query->where('lab_id', $labId);

        $totalAttempts = $query->count();
        $passedCount = (clone $query)->where('status', 'passed')->count();
        $failedCount = (clone $query)->where('status', 'failed')->count();
        $completionRate = $totalAttempts > 0 ? round(($passedCount / $totalAttempts) * 100, 1) : 0;
        
        $avgScore = round((clone $query)->avg('final_score') ?? 0, 1);
        $avgDurationSeconds = (clone $query)->avg('duration_seconds') ?? 0;
        $avgDuration = gmdate("i:s", $avgDurationSeconds);

        $userPerformance = DB::table('lab_histories')
            ->join('users', 'lab_histories.user_id', '=', 'users.id')
            ->select(
                'users.id as student_id', 
                'users.name',
                'users.email', 
                DB::raw('COUNT(lab_histories.id) as total_tries'),
                DB::raw('MAX(lab_histories.final_score) as best_score'),
                DB::raw('AVG(lab_histories.duration_seconds) as avg_time'),
                DB::raw('MAX(lab_histories.created_at) as last_attempt')
            )
            ->when($labId, function($q) use ($labId) { return $q->where('lab_histories.lab_id', $labId); })
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderByDesc('best_score')
            ->limit(10)
            ->get();

        $weeklyTrend = DB::table('lab_histories')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as attempts'))
            ->where('created_at', '>=', now()->subDays(7))
            ->when($labId, function($q) use ($labId) { return $q->where('lab_id', $labId); })
            ->groupBy('date')->orderBy('date', 'asc')->get();

        $chartLabels = $weeklyTrend->pluck('date');
        $chartData = $weeklyTrend->pluck('attempts');
        
        $labsList = DB::table('labs')->select('id', 'title')->get();

        return view('admin.lab_analytics', compact(
            'totalAttempts', 'passedCount', 'failedCount', 'completionRate',
            'avgScore', 'avgDuration', 'userPerformance', 'chartLabels', 'chartData', 'labsList', 'labId'
        ));
    }

    public function studentAnalytics($userId)
    {
        $user = \App\Models\User::findOrFail($userId);

        $histories = DB::table('lab_histories')
            ->join('labs', 'lab_histories.lab_id', '=', 'labs.id')
            ->select('lab_histories.*', 'labs.title as lab_title')
            ->where('user_id', $userId)
            ->orderByDesc('created_at')
            ->get();

        $totalLabsAttempted = $histories->count();
        $passedLabs = $histories->where('status', 'passed')->count();
        $failedLabs = $histories->where('status', 'failed')->count();
        $globalAvgScore = $totalLabsAttempted > 0 ? round($histories->avg('final_score'), 1) : 0;
        
        $totalSeconds = $histories->sum('duration_seconds');
        $totalTimeSpent = gmdate("H:i:s", $totalSeconds);

        $chartData = $histories->sortBy('created_at')->take(-10);
        $chartLabels = $chartData->map(fn($item) => Str::limit($item->lab_title, 10))->values();
        $chartScores = $chartData->pluck('final_score')->values();

        return view('admin.student_lab_analytics', compact(
            'user', 'histories', 
            'totalLabsAttempted', 'passedLabs', 'failedLabs', 'globalAvgScore', 'totalTimeSpent',
            'chartLabels', 'chartScores'
        ));
    }


   /**
     * =========================================================================
     * BAGIAN 4: SISWA - LOGIKA PENGERJAAN
     * =========================================================================
     */

    /**
     * 1. START: Logika Masuk (Remedial & Smart Resume)
     */
    public function start($id)
    {
        $userId = Auth::id();
        
        // Cek Sesi Aktif (Sedang Mengerjakan)
        $activeSession = LabSession::where('user_id', $userId)
            ->where('lab_id', $id)
            ->where('status', 'active')
            ->first();

        if ($activeSession) {
            return redirect()->route('lab.workspace', ['id' => $id]);
        }

        // Cek History Terakhir
        $lastHistory = LabHistory::where('user_id', $userId)
            ->where('lab_id', $id)
            ->latest()
            ->first();

        // A. Jika SUDAH Lulus -> Masuk Mode Review (Workspace akan handle ini)
        if ($lastHistory && $lastHistory->status === 'passed') {
            return redirect()->route('lab.workspace', ['id' => $id]);
        }

        // Logic Penentuan Initial Code
        $initialCode = '';

        // B. Jika GAGAL (Remedial) -> Copy kodingan terakhir
        if ($lastHistory && $lastHistory->status === 'failed') {
            $initialCode = $lastHistory->last_code_snapshot ?? $lastHistory->source_code;
        } 
        // C. Jika BARU -> Ambil Template dari Task Pertama
        else {
            $firstStep = DB::table('lab_steps')
                ->where('lab_id', $id)
                ->orderBy('order_index', 'asc')
                ->first();
            $initialCode = $firstStep ? $firstStep->initial_code : '';
        }

        // Ambil durasi lab
        $labData = DB::table('labs')->where('id', $id)->first();
        $duration = $labData->duration_minutes ?? 60;

        // Buat Sesi Baru
        LabSession::create([
            'user_id' => $userId,
            'lab_id' => $id,
            'status' => 'active',
            'started_at' => now(),
            'expires_at' => now()->addMinutes($duration),
            'current_code' => $initialCode, // Kode terisi otomatis
            'current_score' => 0, // Score dihitung ulang di workspace
        ]);

        return redirect()->route('lab.workspace', ['id' => $id]);
    }

    /**
     * 2. WORKSPACE: Auto-Check Progress & Read-Only Mode
     */
    public function workspace($id)
    {
        $userId = Auth::id();
        $lab = Lab::findOrFail($id);
        
        // Inject steps manual
        $steps = DB::table('lab_steps')->where('lab_id', $id)->orderBy('order_index')->get();
        $lab->steps = $steps; // Assign ke object lab agar bisa diakses di view

        $session = LabSession::where('user_id', $userId)
            ->where('lab_id', $id)
            ->where('status', 'active')
            ->first();

        // --- FALLBACK (Review Mode) ---
        if (!$session) {
            $lastHistory = LabHistory::where('user_id', $userId)->where('lab_id', $id)->latest()->first();

            // Jika pernah lulus -> Buat Dummy Session untuk Read-Only
            if ($lastHistory && $lastHistory->status === 'passed') {
                $session = new LabSession();
                $session->id = 0; // ID Dummy
                $session->user_id = $userId;
                $session->lab_id = $id;
                $session->status = 'completed'; // Penanda Read-Only di View
                $session->current_code = $lastHistory->last_code_snapshot ?? $lastHistory->source_code;
                $session->current_score = $lastHistory->final_score;
                $session->started_at = $lastHistory->created_at;
                $session->expires_at = now();
                $session->exists = true; // Tipuan agar dianggap model valid
            } else {
                // Jika belum lulus dan tidak ada sesi -> Lempar ke Start
                return redirect()->route('lab.start', ['id' => $id]);
            }
        }

        // Force Initial Code jika kosong (Safety Check)
        if ($session->id !== 0 && $session->status === 'active' && empty(trim($session->current_code ?? ''))) {
            $firstStep = $steps->first();
            if ($firstStep) {
                $session->current_code = $firstStep->initial_code;
                DB::table('lab_sessions')->where('id', $session->id)->update(['current_code' => $firstStep->initial_code]);
            }
        }

        // --- LOGIC CALCULATE SCORE (REALTIME) ---
        $completedStepIds = [];
        $rawScore = 0; 
        $currentCode = $session->current_code ?? '';

        if (!empty($currentCode)) {
            foreach ($steps as $step) {
                // Gunakan helper validate yang sama
                if ($this->validateStepRules($step->validation_rules, $currentCode)) {
                    $completedStepIds[] = $step->id;
                    $rawScore += $step->points;
                }
            }
        }

        // Capping Score Max 100
        $finalScore = min($rawScore, 100);

        // Sync Score ke DB jika berubah (Hanya untuk Sesi Aktif)
        if ($session->id !== 0 && $session->status === 'active' && $session->current_score != $finalScore) {
            DB::table('lab_sessions')->where('id', $session->id)->update(['current_score' => $finalScore]);
            $session->current_score = $finalScore;
        }

        return view('labs.workspace', compact('lab', 'session', 'completedStepIds'));
    }

    /**
     * 3. CHECK: Validasi Task Spesifik (AJAX)
     */
    public function check(Request $request, $id)
    {
        $session = LabSession::find($id);
        if (!$session) return response()->json(['status' => 'error', 'message' => 'Sesi invalid'], 404);

        $request->validate(['source_code' => 'nullable|string', 'step_id' => 'nullable|integer']);

        // Auto Save Code
        if ($request->has('source_code')) {
            $session->update(['current_code' => $request->source_code, 'updated_at' => now()]);
        }

        // Jika hanya save (tanpa check spesifik)
        if (!$request->step_id) return response()->json(['status' => 'success', 'output' => "Saved."]);

        $step = DB::table('lab_steps')->where('id', $request->step_id)->first();
        $userCode = $request->source_code ?? $session->current_code;
        $failedRule = '';

        // Validasi Rule
        if ($this->validateStepRules($step->validation_rules, $userCode, $failedRule)) {
            
            // Hitung Ulang Total Score Semua Step
            $allSteps = DB::table('lab_steps')->where('lab_id', $session->lab_id)->get();
            $rawScore = 0;
            
            foreach ($allSteps as $s) {
                if ($this->validateStepRules($s->validation_rules, $userCode)) {
                    $rawScore += $s->points;
                }
            }
            
            $finalScore = min($rawScore, 100);
            $session->update(['current_score' => $finalScore]);

            return response()->json([
                'status' => 'success',
                'points' => $step->points,
                'new_score' => $finalScore, 
                'output' => "Passed: {$step->title}"
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => "Syarat tidak terpenuhi: Kode wajib mengandung '{$failedRule}'"
        ]);
    }

    /**
     * 4. END: Submit Lab & Generate History
     */
    public function end(Request $request, $id)
    {
        // Validasi ID Session
        $session = LabSession::find($id);
        if(!$session) {
            return response()->json(['status' => 'error', 'message' => 'Sesi tidak ditemukan.'], 404);
        }
        
        $request->validate(['source_code' => 'required|string']);

        DB::beginTransaction();
        try {
            $lab = Lab::findOrFail($session->lab_id);
            $userId = Auth::id();

            // 1. HITUNG SKOR & STATUS AKHIR
            // Kita hitung ulang skor berdasarkan kode terakhir untuk keamanan
            $allSteps = DB::table('lab_steps')->where('lab_id', $session->lab_id)->get();
            $rawScore = 0;
            $finalCode = $request->source_code;

            foreach ($allSteps as $s) {
                if ($this->validateStepRules($s->validation_rules, $finalCode)) {
                    $rawScore += $s->points;
                }
            }
            $finalScore = min($rawScore, 100);

            $passingGrade = $lab->passing_grade ?? 50;
            $status = ($finalScore >= $passingGrade) ? 'passed' : 'failed';

            // 2. HITUNG DURASI
            $durationSeconds = abs(now()->diffInSeconds($session->started_at));

            // 3. SIMPAN HISTORY
            LabHistory::create([
                'user_id' => $userId,
                'lab_id' => $session->lab_id,
                'last_code_snapshot' => $finalCode, // Simpan kode final
                'source_code'        => $finalCode, // Redudansi opsional
                'status' => $status,
                'final_score' => $finalScore,
                'duration_seconds' => $durationSeconds,
                'created_at' => now(),
                'updated_at' => now(),
                'completed_at' => now()
            ]);

            // 4. HAPUS SESI
            $session->delete();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'score' => $finalScore,
                'message' => 'Lab berhasil dikumpulkan!',
                'redirect_url' => route('courses.htmldancss') // Bisa disesuaikan
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal submit: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper: Validasi Kode berdasarkan Rules JSON
     */
    private function validateStepRules($jsonRules, $code, &$failedRule = null)
    {
        // Decode JSON rules (Format di DB: ["<div class", "flex"])
        if (empty($jsonRules)) return true;

        $rules = is_string($jsonRules) ? json_decode($jsonRules, true) : $jsonRules;
        
        // Jika rules kosong atau format salah, anggap lolos (Free pass)
        if (!$rules || !is_array($rules)) return true; 

        foreach ($rules as $rule) {
            // Case insensitive check (Huruf besar/kecil dianggap sama)
            // Pastikan $code tidak null
            if (!Str::contains(strtolower($code ?? ''), strtolower($rule))) {
                $failedRule = $rule; // Simpan rule yang gagal untuk pesan error
                return false;
            }
        }
        return true;
    }
    private function finalizeAndDestroy($session, $message)
    {
        // Ambil passing grade dari tabel labs
        $lab = DB::table('labs')->where('id', $session->lab_id)->first();
        $passingGrade = $lab->passing_grade ?? 50;
        
        DB::table('lab_histories')->insert([
            'user_id' => $session->user_id,
            'lab_id' => $session->lab_id,
            'final_score' => $session->current_score,
            'duration_seconds' => abs(now()->diffInSeconds($session->started_at)),
            'last_code_snapshot' => $session->current_code,
            'completed_at' => now(),
            'status' => ($session->current_score >= $passingGrade) ? 'passed' : 'failed',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('lab_sessions')->where('id', $sessionId)->delete();

        return redirect()->route('dashboard')->with('success', "$message Nilai Anda: {$session->current_score}");
    }

    
}