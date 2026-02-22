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
use Carbon\Carbon;
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
     * Update existing task/step
     */
    public function updateTask(Request $request, $id)
    {
        // 1. Validasi Input
        $request->validate([
            'title' => 'required', 
            'instruction' => 'required', 
            'initial_code' => 'required', 
            'validation_rules' => 'required', // String
            'points' => 'required|integer',
            'order_index' => 'required|integer'
        ]);

        // [DIHAPUS] Bagian konversi ke JSON Array dihapus
        // $rulesArray = array_map('trim', explode(',', $request->validation_rules));

        // 3. Update Database
        // Menggunakan updateOrInsert atau logika pengecekan agar jika data sama tidak error
        DB::table('lab_steps')
            ->where('id', $id)
            ->update([
                'title' => $request->title,
                'instruction' => $request->instruction,
                'initial_code' => $request->initial_code,
                
                // [PERUBAHAN] Simpan langsung string dari request tanpa json_encode
                'validation_rules' => $request->validation_rules, 
                
                'points' => $request->points,
                'order_index' => $request->order_index,
                'updated_at' => now(), 
            ]);

        // Return selalu success (karena DB::update return 0 jika tidak ada perubahan data, 
        // yang mana itu bukan error tapi 'no changes')
        return response()->json([
            'status' => 'success', 
            'message' => 'Step updated successfully'
        ]);
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

    // public function studentAnalytics($userId)
    // {
    //     $user = \App\Models\User::findOrFail($userId);

    //     $histories = DB::table('lab_histories')
    //         ->join('labs', 'lab_histories.lab_id', '=', 'labs.id')
    //         ->select('lab_histories.*', 'labs.title as lab_title')
    //         ->where('user_id', $userId)
    //         ->orderByDesc('created_at')
    //         ->get();

    //     $totalLabsAttempted = $histories->count();
    //     $passedLabs = $histories->where('status', 'passed')->count();
    //     $failedLabs = $histories->where('status', 'failed')->count();
    //     $globalAvgScore = $totalLabsAttempted > 0 ? round($histories->avg('final_score'), 1) : 0;
        
    //     $totalSeconds = $histories->sum('duration_seconds');
    //     $totalTimeSpent = gmdate("H:i:s", $totalSeconds);

    //     $chartData = $histories->sortBy('created_at')->take(-10);
    //     $chartLabels = $chartData->map(fn($item) => Str::limit($item->lab_title, 10))->values();
    //     $chartScores = $chartData->pluck('final_score')->values();

    //     return view('admin.student_lab_analytics', compact(
    //         'user', 'histories', 
    //         'totalLabsAttempted', 'passedLabs', 'failedLabs', 'globalAvgScore', 'totalTimeSpent',
    //         'chartLabels', 'chartScores'
    //     ));
    // }
public function studentAnalytics($userId)
    {
        $user = \App\Models\User::findOrFail($userId);

        // 1. Ambil History (Selesai Mengerjakan)
        $histories = DB::table('lab_histories')
            ->join('labs', 'lab_histories.lab_id', '=', 'labs.id')
            ->where('lab_histories.user_id', $userId)
            ->select(
                'lab_histories.*', 
                'labs.title as lab_title',
                DB::raw("'history' as type") // Penanda tipe data
            )
            ->orderByDesc('created_at')
            ->get();

        // 2. Ambil Sesi Aktif (Realtime / Sedang Mengerjakan)
        $activeSessions = DB::table('lab_sessions')
            ->join('labs', 'lab_sessions.lab_id', '=', 'labs.id')
            ->where('lab_sessions.user_id', $userId)
            ->select(
                'lab_sessions.id',
                'lab_sessions.lab_id',
                'lab_sessions.current_score as final_score', // Samakan nama kolom skor
                'lab_sessions.updated_at as created_at',     // Gunakan updated_at sebagai waktu aktivitas
                'lab_sessions.updated_at', 
                'labs.title as lab_title',
                DB::raw("'active' as status"), // Status khusus untuk UI
                DB::raw("'active' as type"),   // Penanda tipe data
                DB::raw("0 as duration_seconds") // Placeholder
            )
            ->get();

        // 3. Statistik (Hanya hitung dari yang SUDAH SELESAI)
        $totalLabsAttempted = $histories->count();
        $passedLabs = $histories->where('status', 'passed')->count();
        $failedLabs = $histories->where('status', 'failed')->count();
        $globalAvgScore = $totalLabsAttempted > 0 ? round($histories->avg('final_score'), 1) : 0;
        
        $totalSeconds = $histories->sum('duration_seconds');
        $hours = floor($totalSeconds / 3600);
        $minutes = floor(($totalSeconds % 3600) / 60);
        $totalTimeSpent = "{$hours}j {$minutes}m";

        // 4. Data Grafik (History Saja)
        $chartData = $histories->sortBy('created_at')->take(-10);
        $chartLabels = $chartData->map(fn($item) => \Illuminate\Support\Str::limit($item->lab_title, 10))->values();
        $chartScores = $chartData->pluck('final_score')->values();

        // 5. Gabungkan untuk Feed Aktivitas (Aktif + History)
        // Sesi aktif ditaruh paling atas, lalu diurutkan waktu terbaru
        $recentActivities = $activeSessions->merge($histories)->sortByDesc('created_at');

        return view('admin.student_lab_analytics', compact(
            'user', 
            'histories', 
            'recentActivities', // Variabel baru gabungan
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
     * 1. START: Logika Masuk (Remedial Reset & Smart Resume)
     */
    public function start($id)
    {
        $userId = Auth::id();
        
        // 1. Cek Sesi Aktif (Sedang Mengerjakan)
        // Jika masih ada sesi gantung, langsung lanjutkan tanpa reset
        $activeSession = LabSession::where('user_id', $userId)
            ->where('lab_id', $id)
            ->where('status', 'active')
            ->first();

        if ($activeSession) {
            return redirect()->route('lab.workspace', ['id' => $id]);
        }

        // 2. Cek History Terakhir
        $lastHistory = LabHistory::where('user_id', $userId)
            ->where('lab_id', $id)
            ->latest()
            ->first();

        // A. LULUS (PASSED) -> Arahkan ke Workspace (Mode Read-Only)
        if ($lastHistory && $lastHistory->status === 'passed') {
            return redirect()->route('lab.workspace', ['id' => $id]);
        }

        // 3. Logic Penentuan Initial Code
        $initialCode = '';
        $firstStep = DB::table('lab_steps')
            ->where('lab_id', $id)
            ->orderBy('order_index', 'asc')
            ->first();

        // B. GAGAL (REMEDIAL) -> "Pure Mengulang dari Awal"
        if ($lastHistory && $lastHistory->status === 'failed') {
            // Kita ambil kode template asli dari Task 1 (Reset Bersih)
            $initialCode = $firstStep ? $firstStep->initial_code : '';
        } 
        // C. BARU PERTAMA KALI -> Ambil Template dari Task Pertama
        else {
            $initialCode = $firstStep ? $firstStep->initial_code : '';
        }

        // Ambil durasi lab
        $labData = DB::table('labs')->where('id', $id)->first();
        $duration = $labData->duration_minutes ?? 60;

        // 4. Buat Sesi Baru (Fresh Session)
        LabSession::create([
            'user_id' => $userId,
            'lab_id' => $id,
            'status' => 'active',
            'started_at' => now(),
            'expires_at' => now()->addMinutes($duration),
            'current_code' => $initialCode, // Kode bersih (Template)
            'current_score' => 0, 
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('lab.workspace', ['id' => $id]);
    }

    /**
     * 2. WORKSPACE: Auto-Check Progress & Expired Check
     */
   public function workspace($id)
    {
        $userId = Auth::id();
        $lab = Lab::findOrFail($id);
        
        // Ambil steps untuk navigasi
        $steps = DB::table('lab_steps')->where('lab_id', $id)->orderBy('order_index')->get();
        $lab->steps = $steps;

        // Inisialisasi variabel penampung progress
        $completedStepIds = [];

        // 1. Cek Sesi Aktif
        $session = LabSession::where('user_id', $userId)
            ->where('lab_id', $id)
            ->where('status', 'active')
            ->first();

        // --- SKENARIO 1: TIDAK ADA SESI AKTIF (History / Mode Read Only) ---
        if (!$session) {
            $lastHistory = LabHistory::where('user_id', $userId)
                ->where('lab_id', $id)
                ->latest()
                ->first();

            // Jika status PASSED -> Tampilkan Mode Review
            if ($lastHistory && $lastHistory->status === 'passed') {
                $session = new LabSession();
                $session->id = 0; // Fake ID
                $session->user_id = $userId;
                $session->lab_id = $id;
                $session->status = 'completed'; // Trigger ReadOnly di View
                $session->current_code = $lastHistory->last_code_snapshot ?? $lastHistory->source_code;
                $session->current_score = $lastHistory->final_score;
                $session->started_at = $lastHistory->created_at;
                $session->expires_at = now(); 
                
                // [FIX] Ambil progress task dari tabel history
                if (!empty($lastHistory->completed_steps)) {
                    $completedStepIds = json_decode($lastHistory->completed_steps, true);
                } else {
                    // Fallback untuk data lama: anggap semua selesai
                    $completedStepIds = $steps->pluck('id')->toArray();
                }
            } 
            // Jika status FAILED atau belum pernah -> Lempar ke Start
            else {
                return redirect()->route('lab.start', ['id' => $id]);
            }
        } 
        
        // --- SKENARIO 2: ADA SESI AKTIF (Sedang Mengerjakan) ---
        else {
            // Cek Expired
            if (Carbon::now()->greaterThan($session->expires_at)) {
                return $this->handleExpiredSession($session);
            }

            // Logic force initial code jika editor kosong
            if (empty(trim($session->current_code ?? ''))) {
                $firstStep = $steps->first();
                if ($firstStep) {
                    $session->current_code = $firstStep->initial_code;
                    $session->save();
                }
            }

            // [FIX UTAMA] Ambil progress task dari tabel session (JSON)
            // Tidak lagi memvalidasi ulang kode source_code vs rules di sini
            if (!empty($session->completed_steps)) {
                $completedStepIds = json_decode($session->completed_steps, true);
            }
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

        // 1. Cek Expired
        if (Carbon::now()->greaterThan($session->expires_at)) {
            $this->handleExpiredSession($session);
            return response()->json(['status' => 'expired', 'message' => 'Waktu pengerjaan telah habis!', 'redirect' => route('dashboard')]);
        }

        // 2. Simpan Kode Terbaru
        if ($request->has('source_code')) {
            $session->update([
                'current_code' => $request->source_code, 
                'updated_at' => now()
            ]);
        }

        // Jika tidak ada step_id (hanya save), return success
        if (!$request->step_id) return response()->json(['status' => 'success', 'output' => "Auto-saved."]);

        // 3. Validasi Task YANG SEDANG DIKERJAKAN SAJA
        $currentStep = DB::table('lab_steps')->where('id', $request->step_id)->first();
        $userCode = $request->source_code ?? $session->current_code;
        $failedRule = '';

        // Cek validasi HANYA untuk step ini
        $isPassed = $this->validateStepRules($currentStep->validation_rules, $userCode, $failedRule);

        if ($isPassed) {
            
            // --- LOGIKA BARU: COMPLETED STEPS TRACKER ---
            
            // A. Ambil daftar ID task yang sudah selesai dari DB (Decode JSON)
            // Jika null, inisialisasi array kosong []
            $completedSteps = $session->completed_steps ? json_decode($session->completed_steps, true) : [];

            // B. Jika ID task ini belum ada di daftar, tambahkan!
            // (Agar poin tidak dihitung ganda jika tombol diklik berkali-kali)
            if (!in_array($currentStep->id, $completedSteps)) {
                $completedSteps[] = $currentStep->id;
                
                // Simpan update daftar completed_steps ke DB
                $session->completed_steps = json_encode($completedSteps);
                $session->save();
            }

            // C. Hitung Total Skor Berdasarkan DAFTAR YANG SELESAI
            // Kita jumlahkan poin dari step-step yang ID-nya ada di array $completedSteps
            // Ini membuat nilai "ABADI/LOCKED". Meskipun kode dihapus, ID tetap ada di array ini.
            $earnedPoints = DB::table('lab_steps')
                ->whereIn('id', $completedSteps)
                ->sum('points');

            // D. Hitung Total Poin Maksimal (Untuk Persentase)
            $totalPossiblePoints = DB::table('lab_steps')
                ->where('lab_id', $session->lab_id)
                ->sum('points');

            // E. Hitung Persentase Skor Akhir
            $finalScore = ($totalPossiblePoints > 0) 
                ? round(($earnedPoints / $totalPossiblePoints) * 100) 
                : 0;

            // Update skor di database
            $session->update(['current_score' => $finalScore]);

            return response()->json([
                'status' => 'success',
                'points' => $currentStep->points,
                'new_score' => $finalScore, // Sekarang pasti akumulatif (misal: 10, lalu 25)
                'output' => "Benar! Task selesai."
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => "Syarat belum terpenuhi: Kode wajib mengandung '{$failedRule}'"
        ]);
    }
    /**
     * 4. END: Submit & Clean Session
     */
    public function end(Request $request, $id)
    {
        $userId = Auth::id();

        // [SMART SEARCH]
        // Langkah 1: Coba cari asumsi $id adalah LAB ID
        $session = LabSession::where('lab_id', $id)
            ->where('user_id', $userId)
            ->where('status', 'active')
            ->first();

        // Langkah 2: Jika tidak ketemu, Coba cari asumsi $id adalah SESSION ID
        // (Ini menangani kasus jika Javascript mengirim ID Sesi, bukan ID Lab)
        if (!$session) {
            $session = LabSession::where('id', $id)
                ->where('user_id', $userId)
                ->where('status', 'active')
                ->first();
        }

        // Jika masih tidak ketemu juga, baru error
        if (!$session) {
            // [DEBUGGING] Cek apakah user punya sesi aktif lain?
            $anyActive = LabSession::where('user_id', $userId)->where('status', 'active')->first();
            $msg = $anyActive 
                ? "Sesi aktif ditemukan di Lab ID {$anyActive->lab_id}, bukan ID $id." 
                : "Tidak ada sesi aktif sama sekali.";

            return response()->json([
                'status' => 'error', 
                'message' => "Gagal menemukan sesi. $msg"
            ], 404);
        }
        
        // Cek Expired
        if (Carbon::now()->greaterThan($session->expires_at)) {
            $this->handleExpiredSession($session);
            return response()->json(['status' => 'expired', 'message' => 'Waktu Habis', 'redirect' => route('dashboard')]);
        }

        $finalCode = $request->source_code ?? $session->current_code;

        DB::beginTransaction();
        try {
            $lab = Lab::findOrFail($session->lab_id);
            
            // --- VALIDASI ULANG & HITUNG SKOR ---
            $allSteps = DB::table('lab_steps')->where('lab_id', $session->lab_id)->get();
            $earnedPoints = 0;
            $totalPossiblePoints = $allSteps->sum('points');

            foreach ($allSteps as $s) {
                if ($this->validateStepRules($s->validation_rules, $finalCode)) {
                    $earnedPoints += $s->points;
                }
            }
            
            $calculatedScore = ($totalPossiblePoints > 0) 
                ? round(($earnedPoints / $totalPossiblePoints) * 100) 
                : 0;

            // Ambil skor tertinggi (Safety Net)
            $finalScore = max($calculatedScore, $session->current_score);

            $passingGrade = $lab->passing_grade ?? 50;
            $status = ($finalScore >= $passingGrade) ? 'passed' : 'failed';
            $durationSeconds = abs(now()->diffInSeconds($session->started_at));

            // Ambil Completed Steps dari sesi saat ini
            $completedStepsData = $session->completed_steps;

            // Simpan ke History
            LabHistory::create([
                'user_id' => $userId,
                'lab_id' => $session->lab_id,
                'last_code_snapshot' => $finalCode,
                'source_code'        => $finalCode,
                'status' => $status,
                'final_score' => $finalScore,
                'duration_seconds' => $durationSeconds,
                'completed_steps' => $completedStepsData, // Simpan centang hijau
                'created_at' => now(),
                'updated_at' => now(),
                'completed_at' => now()
            ]);

            // HAPUS SESI TOTAL
            $session->delete();
            
            DB::commit();

            return response()->json([
                'status' => 'success',
                'score' => $finalScore,
                'message' => 'Lab berhasil dikumpulkan!',
                'redirect_url' => route('courses.htmldancss') 
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Gagal submit: ' . $e->getMessage()], 500);
        }
    }
    /**
     * Helper: Validasi Kode
     */
    /**
     * Helper: Validasi Kode Ketat (Anti-Bypass)
     * Mengecek apakah rule ada di dalam atribut class="..." HTML
     */
    private function validateStepRules($jsonRules, $code, &$failedRule = null)
    {
        if (empty($jsonRules)) return true;
        
        $rules = is_string($jsonRules) ? json_decode($jsonRules, true) : $jsonRules;
        if (!$rules || !is_array($rules)) return true; 

        // 1. Ekstrak semua konten di dalam class="..." atau class='...'
        // Regex ini menangkap semua string yang ada di dalam atribut class
        preg_match_all('/class\s*=\s*["\']([^"\']*)["\']/i', $code, $matches);
        
        // Gabungkan semua class yang ditemukan menjadi satu string besar untuk pencarian
        // Contoh: "w-full fixed flex justify-between bg-slate-900"
        $allClassesFound = strtolower(implode(' ', $matches[1] ?? []));
        
        // Bersihkan kode dari komentar HTML agar tidak bisa cheat via komentar
        $cleanCode = preg_replace('//s', '', $code);

        foreach ($rules as $rule) {
            $rule = strtolower($rule);
            $isValid = false;

            // STRATEGI VALIDASI:
            
            // A. Jika rule adalah tag HTML (misal: <nav>, <footer>, <script>)
            // Cek keberadaan tag di kode bersih (bukan di komentar)
            if (str_starts_with($rule, '<')) {
                // Hapus tanda < dan > untuk cek nama tag, misal <nav> jadi nav
                $tagName = str_replace(['<', '>', '/'], '', $rule);
                if (str_contains(strtolower($cleanCode), $rule) || str_contains(strtolower($cleanCode), "<$tagName")) {
                    $isValid = true;
                }
            } 
            // B. Jika rule adalah Utility Class (misal: flex, w-full, bg-slate-900)
            // Cek HANYA di dalam atribut class="..." yang sudah kita ekstrak tadi
            else {
                // Cek exact match word boundary (\b) agar "flex" tidak match dengan "flex-col"
                // Atau str_contains biasa jika ingin longgar. Di sini kita pakai str_contains pada kumpulan class.
                if (str_contains($allClassesFound, $rule)) {
                    $isValid = true;
                }
            }

            if (!$isValid) {
                $failedRule = $rule;
                return false;
            }
        }
        return true;
    }

    /**
     * [BARU] Helper: Handle Expired Session
     * Fungsi ini dipanggil otomatis jika user mencoba akses sesi yang sudah lewat waktunya
     */
    private function handleExpiredSession($session)
    {
        $lab = DB::table('labs')->where('id', $session->lab_id)->first();
        
        // Hitung skor terakhir apa adanya
        $allSteps = DB::table('lab_steps')->where('lab_id', $session->lab_id)->get();
        $earnedPoints = 0;
        $totalPossiblePoints = $allSteps->sum('points');
        $finalCode = $session->current_code;

        foreach ($allSteps as $s) {
            if ($this->validateStepRules($s->validation_rules, $finalCode)) {
                $earnedPoints += $s->points;
            }
        }

        $finalScore = ($totalPossiblePoints > 0) 
            ? round(($earnedPoints / $totalPossiblePoints) * 100) 
            : 0;

        $passingGrade = $lab->passing_grade ?? 50;
        $durationSeconds = abs(Carbon::now()->diffInSeconds($session->started_at));

        // Pindahkan ke History
        DB::table('lab_histories')->insert([
            'user_id' => $session->user_id,
            'lab_id' => $session->lab_id,
            'final_score' => $finalScore,
            'duration_seconds' => $durationSeconds,
            'last_code_snapshot' => $finalCode,
            'source_code' => $finalCode,
            'completed_at' => now(),
            'status' => ($finalScore >= $passingGrade) ? 'passed' : 'failed',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Hapus sesi aktif
        DB::table('lab_sessions')->where('id', $session->id)->delete();

        return redirect()->route('dashboard')->with('error', "Waktu Habis! Lab otomatis dikumpulkan. Nilai Anda: {$finalScore}");
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