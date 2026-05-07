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
        $labs = DB::table('labs')->orderByDesc('created_at')->get();
        $totalLabs = $labs->count();
        $totalActive = $labs->filter(fn($l) => $l->is_active ?? 0)->count();
        
        return view('admin.lab_configuration', compact('labs', 'totalLabs', 'totalActive'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'duration' => 'required|integer|min:1',
            'passing_grade' => 'required|integer|min:0|max:100',
        ]);

        DB::table('labs')->insert([
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . time(),
            'description' => $request->description,
            'duration_minutes' => $request->duration, 
            'passing_grade' => $request->passing_grade,
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['status' => 'success', 'message' => 'Lab created successfully']);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'duration' => 'required|integer|min:1',
            'passing_grade' => 'required|integer|min:0|max:100',
        ]);

        $exists = DB::table('labs')->where('id', $id)->exists();
        if(!$exists) {
            return response()->json(['status' => 'error', 'message' => 'Lab not found'], 404);
        }

        DB::table('labs')->where('id', $id)->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . $id, 
            'description' => $request->description,
            'duration_minutes' => $request->duration,
            'passing_grade' => $request->passing_grade,
            'updated_at' => now(),
        ]);

        return response()->json(['status' => 'success', 'message' => 'Lab updated successfully']);
    }

    public function destroy($id)
    {
        DB::table('labs')->where('id', $id)->delete();
        DB::table('lab_steps')->where('lab_id', $id)->delete();
        return response()->json(['status' => 'success', 'message' => 'Lab deleted']);
    }

    public function toggleStatus($id)
    {
        $lab = DB::table('labs')->where('id', $id)->first();
        if($lab) {
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

    public function updateTask(Request $request, $id)
    {
        $request->validate([
            'title' => 'required', 
            'instruction' => 'required', 
            'initial_code' => 'required', 
            'validation_rules' => 'required',
            'points' => 'required|integer',
            'order_index' => 'required|integer'
        ]);

        DB::table('lab_steps')
            ->where('id', $id)
            ->update([
                'title' => $request->title,
                'instruction' => $request->instruction,
                'initial_code' => $request->initial_code,
                'validation_rules' => $request->validation_rules, 
                'points' => $request->points,
                'order_index' => $request->order_index,
                'updated_at' => now(), 
            ]);

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

    public function studentAnalytics($userId)
    {
        $user = \App\Models\User::findOrFail($userId);
        
        // 1. Ambil History
        $histories = DB::table('lab_histories')
            ->join('labs', 'lab_histories.lab_id', '=', 'labs.id')
            ->where('lab_histories.user_id', $userId)
            ->select(
                'lab_histories.*', 
                'labs.title as lab_title',
                DB::raw("'history' as type")
            )
            ->orderByDesc('created_at')
            ->get();
            
        // 2. Ambil Sesi Aktif
        $activeSessions = DB::table('lab_sessions')
            ->join('labs', 'lab_sessions.lab_id', '=', 'labs.id')
            ->where('lab_sessions.user_id', $userId)
            ->select(
                'lab_sessions.id',
                'lab_sessions.lab_id',
                'lab_sessions.current_score as final_score',
                'lab_sessions.updated_at as created_at',
                'lab_sessions.updated_at', 
                'labs.title as lab_title',
                DB::raw("'active' as status"),
                DB::raw("'active' as type"),
                DB::raw("0 as duration_seconds")
            )
            ->get();
            
        // 3. Statistik
        $totalLabsAttempted = $histories->count();
        $passedLabs = $histories->where('status', 'passed')->count();
        $failedLabs = $histories->where('status', 'failed')->count();
        $globalAvgScore = $totalLabsAttempted > 0 ? round($histories->avg('final_score'), 1) : 0;
        
        $totalSeconds = $histories->sum('duration_seconds');
        $hours = floor($totalSeconds / 3600);
        $minutes = floor(($totalSeconds % 3600) / 60);
        $totalTimeSpent = "{$hours}j {$minutes}m";
        
        // 4. Data Grafik
        $chartData = $histories->sortBy('created_at')->take(-10);
        $chartLabels = $chartData->map(fn($item) => \Illuminate\Support\Str::limit($item->lab_title, 10))->values();
        $chartScores = $chartData->pluck('final_score')->values();

        // 5. Gabungkan untuk Feed Aktivitas
        $recentActivities = $activeSessions->merge($histories)->sortByDesc('created_at');
        
        return view('admin.student_lab_analytics', compact(
            'user', 
            'histories', 
            'recentActivities',
            'totalLabsAttempted', 'passedLabs', 'failedLabs', 'globalAvgScore', 'totalTimeSpent',
            'chartLabels', 'chartScores'
        ));
    }

    /**
     * =========================================================================
     * BAGIAN 4: SISWA - LOGIKA PENGERJAAN
     * =========================================================================
     */

    public function start($id)
    {
        $userId = Auth::id();
        
        // 1. Cek Sesi Aktif
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
            
        if ($lastHistory && $lastHistory->status === 'passed') {
            return redirect()->route('lab.workspace', ['id' => $id]);
        }

        // 3. Logic Penentuan Initial Code
        $firstStep = DB::table('lab_steps')
            ->where('lab_id', $id)
            ->orderBy('order_index', 'asc')
            ->first();
            
        $initialCode = $firstStep ? $firstStep->initial_code : '';

        // Ambil durasi lab
        $labData = DB::table('labs')->where('id', $id)->first();
        // FIX: Casting ke integer agar Carbon::addMinutes tidak error "string given"
        $duration = (int) ($labData->duration_minutes ?? 60);

        // 4. Buat Sesi Baru
        LabSession::create([
            'user_id' => $userId,
            'lab_id' => $id,
            'status' => 'active',
            'started_at' => now(),
            'expires_at' => now()->addMinutes($duration), // Aman karena $duration dipastikan integer
            'current_code' => $initialCode,
            'current_score' => 0, 
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        return redirect()->route('lab.workspace', ['id' => $id]);
    }

    public function workspace($id)
    {
        $userId = Auth::id();
        $lab = Lab::findOrFail($id);
        
        $steps = DB::table('lab_steps')->where('lab_id', $id)->orderBy('order_index')->get();
        $lab->steps = $steps;

        $completedStepIds = [];
        
        // 1. Cek Sesi Aktif
        $session = LabSession::where('user_id', $userId)
            ->where('lab_id', $id)
            ->where('status', 'active')
            ->first();
            
        // TIDAK ADA SESI AKTIF (History / Mode Read Only)
        if (!$session) {
            $lastHistory = LabHistory::where('user_id', $userId)
                ->where('lab_id', $id)
                ->latest()
                ->first();
                
            if ($lastHistory && $lastHistory->status === 'passed') {
                $session = new LabSession();
                $session->id = 0; 
                $session->user_id = $userId;
                $session->lab_id = $id;
                $session->status = 'completed'; 
                $session->current_code = $lastHistory->last_code_snapshot ?? $lastHistory->source_code;
                $session->current_score = $lastHistory->final_score;
                $session->started_at = $lastHistory->created_at;
                $session->expires_at = now();
                
                if (!empty($lastHistory->completed_steps)) {
                    $completedStepIds = json_decode($lastHistory->completed_steps, true);
                } else {
                    $completedStepIds = $steps->pluck('id')->toArray();
                }
            } else {
                return redirect()->route('lab.start', ['id' => $id]);
            }
        } 
        // ADA SESI AKTIF
        else {
            // FIX: Parsing $session->expires_at dengan Carbon untuk komparasi aman
            if (Carbon::now()->greaterThan(Carbon::parse($session->expires_at))) {
                return $this->handleExpiredSession($session);
            }

            if (empty(trim($session->current_code ?? ''))) {
                $firstStep = $steps->first();
                if ($firstStep) {
                    $session->current_code = $firstStep->initial_code;
                    $session->save();
                }
            }

            if (!empty($session->completed_steps)) {
                $completedStepIds = json_decode($session->completed_steps, true);
            }
        }

        return view('labs.workspace', compact('lab', 'session', 'completedStepIds'));
    }

    public function check(Request $request, $id)
    {
        $session = LabSession::find($id);
        if (!$session) return response()->json(['status' => 'error', 'message' => 'Sesi invalid'], 404);
        
        // FIX: Parsing $session->expires_at dengan Carbon
        if (Carbon::now()->greaterThan(Carbon::parse($session->expires_at))) {
            $this->handleExpiredSession($session);
            return response()->json(['status' => 'expired', 'message' => 'Waktu pengerjaan telah habis!', 'redirect' => route('dashboard')]);
        }

        if ($request->has('source_code')) {
            $session->update([
                'current_code' => $request->source_code, 
                'updated_at' => now()
            ]);
        }

        if (!$request->step_id) return response()->json(['status' => 'success', 'output' => "Auto-saved."]);
        
        $currentStep = DB::table('lab_steps')->where('id', $request->step_id)->first();
        $userCode = $request->source_code ?? $session->current_code;
        $failedRule = '';

        $isPassed = $this->validateStepRules($currentStep->validation_rules, $userCode, $failedRule);
        
        if ($isPassed) {
            $completedSteps = $session->completed_steps ? json_decode($session->completed_steps, true) : [];

            if (!in_array($currentStep->id, $completedSteps)) {
                $completedSteps[] = $currentStep->id;
                $session->completed_steps = json_encode($completedSteps);
                $session->save();
            }

            $earnedPoints = DB::table('lab_steps')->whereIn('id', $completedSteps)->sum('points');
            $totalPossiblePoints = DB::table('lab_steps')->where('lab_id', $session->lab_id)->sum('points');
            
            $finalScore = ($totalPossiblePoints > 0) ? round(($earnedPoints / $totalPossiblePoints) * 100) : 0;
            $session->update(['current_score' => $finalScore]);
            
            return response()->json([
                'status' => 'success',
                'points' => $currentStep->points,
                'new_score' => $finalScore, 
                'output' => "Benar! Task selesai."
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => "Syarat belum terpenuhi: Kode wajib mengandung '{$failedRule}'"
        ]);
    }

    public function end(Request $request, $id)
    {
        $userId = Auth::id();
        
        $session = LabSession::where('lab_id', $id)->where('user_id', $userId)->where('status', 'active')->first();
        if (!$session) {
            $session = LabSession::where('id', $id)->where('user_id', $userId)->where('status', 'active')->first();
        }

        if (!$session) {
            $anyActive = LabSession::where('user_id', $userId)->where('status', 'active')->first();
            $msg = $anyActive ? "Sesi aktif ditemukan di Lab ID {$anyActive->lab_id}, bukan ID $id." : "Tidak ada sesi aktif sama sekali.";
            return response()->json(['status' => 'error', 'message' => "Gagal menemukan sesi. $msg"], 404);
        }
        
        // FIX: Parsing $session->expires_at dengan Carbon
        if (Carbon::now()->greaterThan(Carbon::parse($session->expires_at))) {
            $this->handleExpiredSession($session);
            return response()->json(['status' => 'expired', 'message' => 'Waktu Habis', 'redirect' => route('dashboard')]);
        }

        $finalCode = $request->source_code ?? $session->current_code;

        DB::beginTransaction();
        try {
            $lab = Lab::findOrFail($session->lab_id);
            $allSteps = DB::table('lab_steps')->where('lab_id', $session->lab_id)->get();
            $earnedPoints = 0;
            $totalPossiblePoints = $allSteps->sum('points');

            foreach ($allSteps as $s) {
                if ($this->validateStepRules($s->validation_rules, $finalCode)) {
                    $earnedPoints += $s->points;
                }
            }
            
            $calculatedScore = ($totalPossiblePoints > 0) ? round(($earnedPoints / $totalPossiblePoints) * 100) : 0;
            $finalScore = max($calculatedScore, $session->current_score);
            
            $passingGrade = $lab->passing_grade ?? 50;
            $status = ($finalScore >= $passingGrade) ? 'passed' : 'failed';
            $durationSeconds = abs(now()->diffInSeconds(Carbon::parse($session->started_at)));
            $completedStepsData = $session->completed_steps;
            
            LabHistory::create([
                'user_id' => $userId,
                'lab_id' => $session->lab_id,
                'last_code_snapshot' => $finalCode,
                'source_code'        => $finalCode,
                'status' => $status,
                'final_score' => $finalScore,
                'duration_seconds' => $durationSeconds,
                'completed_steps' => $completedStepsData, 
                'created_at' => now(),
                'updated_at' => now(),
                'completed_at' => now()
            ]);
            
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

    private function validateStepRules($jsonRules, $code, &$failedRule = null)
    {
        if (empty($jsonRules)) return true;
        $rules = is_string($jsonRules) ? json_decode($jsonRules, true) : $jsonRules;
        if (!$rules || !is_array($rules)) return true;
        
        preg_match_all('/class\s*=\s*["\']([^"\']*)["\']/i', $code, $matches);
        $allClassesFound = strtolower(implode(' ', $matches[1] ?? []));
        $cleanCode = preg_replace('/\s+/', '', $code);

        foreach ($rules as $rule) {
            $rule = strtolower($rule);
            $isValid = false;

            if (str_starts_with($rule, '<')) {
                $tagName = str_replace(['<', '>', '/'], '', $rule);
                if (str_contains(strtolower($cleanCode), $rule) || str_contains(strtolower($cleanCode), "<$tagName")) {
                    $isValid = true;
                }
            } else {
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

    private function handleExpiredSession($session)
    {
        $lab = DB::table('labs')->where('id', $session->lab_id)->first();
        $allSteps = DB::table('lab_steps')->where('lab_id', $session->lab_id)->get();
        $earnedPoints = 0;
        $totalPossiblePoints = $allSteps->sum('points');
        $finalCode = $session->current_code;

        foreach ($allSteps as $s) {
            if ($this->validateStepRules($s->validation_rules, $finalCode)) {
                $earnedPoints += $s->points;
            }
        }

        $finalScore = ($totalPossiblePoints > 0) ? round(($earnedPoints / $totalPossiblePoints) * 100) : 0;
        $passingGrade = $lab->passing_grade ?? 50;
        
        // FIX: Parsing tanggal dengan Carbon
        $durationSeconds = abs(Carbon::now()->diffInSeconds(Carbon::parse($session->started_at)));

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
        
        DB::table('lab_sessions')->where('id', $session->id)->delete();
        
        return redirect()->route('dashboard')->with('error', "Waktu Habis! Lab otomatis dikumpulkan. Nilai Anda: {$finalScore}");
    }

    private function finalizeAndDestroy($session, $message)
    {
        $lab = DB::table('labs')->where('id', $session->lab_id)->first();
        $passingGrade = $lab->passing_grade ?? 50;
        
        DB::table('lab_histories')->insert([
            'user_id' => $session->user_id,
            'lab_id' => $session->lab_id,
            'final_score' => $session->current_score,
            // FIX: Parsing tanggal dengan Carbon
            'duration_seconds' => abs(now()->diffInSeconds(Carbon::parse($session->started_at))),
            'last_code_snapshot' => $session->current_code,
            'completed_at' => now(),
            'status' => ($session->current_score >= $passingGrade) ? 'passed' : 'failed',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        // FIX: $sessionId diganti menjadi $session->id
        DB::table('lab_sessions')->where('id', $session->id)->delete();

        return redirect()->route('dashboard')->with('success', "$message Nilai Anda: {$session->current_score}");
    }
}