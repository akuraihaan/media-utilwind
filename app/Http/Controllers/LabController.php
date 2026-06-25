<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use App\Models\Lab;
use App\Models\LabHistory;
use App\Support\ChapterSummary;
use App\Support\LabResultAnalyzer;
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

        return response()->json(['status' => 'success', 'message' => 'Lab berhasil dibuat']);
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
            return response()->json(['status' => 'error', 'message' => 'Lab tidak ditemukan'], 404);
        }

        DB::table('labs')->where('id', $id)->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . $id, 
            'description' => $request->description,
            'duration_minutes' => $request->duration,
            'passing_grade' => $request->passing_grade,
            'updated_at' => now(),
        ]);

        return response()->json(['status' => 'success', 'message' => 'Lab berhasil diperbarui']);
    }

    public function destroy($id)
    {
        DB::table('labs')->where('id', $id)->delete();
        DB::table('lab_steps')->where('lab_id', $id)->delete();
        return response()->json(['status' => 'success', 'message' => 'Lab berhasil dihapus']);
    }

    public function toggleStatus($id)
    {
        $lab = DB::table('labs')->where('id', $id)->first();
        if($lab) {
            DB::table('labs')->where('id', $id)->update(['is_active' => !($lab->is_active ?? 0)]);
        }
        return response()->json(['status' => 'success', 'message' => 'Status berhasil diperbarui']);
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
            'learning_objective_code' => 'nullable|string|max:40',
            'learning_objective_title' => 'nullable|string|max:255',
            'remediation_hint' => 'nullable|string|max:1000',
            'initial_code' => 'required', 
            'validation_rules' => 'required', 
            'points' => 'required|integer'
        ]);

        $rulesArray = array_map('trim', explode(',', $request->validation_rules));

        DB::table('lab_steps')->insert($this->filterColumns('lab_steps', [
            'lab_id' => $request->lab_id,
            'title' => $request->title,
            'learning_objective_code' => $request->learning_objective_code,
            'learning_objective_title' => $request->learning_objective_title,
            'remediation_hint' => $request->remediation_hint,
            'instruction' => $request->instruction,
            'initial_code' => $request->initial_code,
            'validation_rules' => json_encode($rulesArray),
            'points' => $request->points,
            'order_index' => $request->order_index ?? 1,
            'created_at' => now(),
        ]));
        return response()->json(['status' => 'success', 'message' => 'Langkah berhasil ditambahkan']);
    }

    public function destroyTask($id)
    {
        DB::table('lab_steps')->where('id', $id)->delete();
        return response()->json(['status' => 'success', 'message' => 'Langkah berhasil dihapus']);
    }

    public function updateTask(Request $request, $id)
    {
        $request->validate([
            'title' => 'required', 
            'learning_objective_code' => 'nullable|string|max:40',
            'learning_objective_title' => 'nullable|string|max:255',
            'remediation_hint' => 'nullable|string|max:1000',
            'instruction' => 'required', 
            'initial_code' => 'required', 
            'validation_rules' => 'required',
            'points' => 'required|integer',
            'order_index' => 'required|integer'
        ]);

        $rulesArray = array_map('trim', explode(',', $request->validation_rules));

        DB::table('lab_steps')
            ->where('id', $id)
            ->update($this->filterColumns('lab_steps', [
                'title' => $request->title,
                'learning_objective_code' => $request->learning_objective_code,
                'learning_objective_title' => $request->learning_objective_title,
                'remediation_hint' => $request->remediation_hint,
                'instruction' => $request->instruction,
                'initial_code' => $request->initial_code,
                'validation_rules' => json_encode($rulesArray),
                'points' => $request->points,
                'order_index' => $request->order_index,
                'updated_at' => now(), 
            ]));

        return response()->json([
            'status' => 'success', 
            'message' => 'Langkah berhasil diperbarui'
        ]);
    }

    /**
     * =========================================================================
     * BAGIAN 3: ANALYTICS (GLOBAL & STUDENT)
     * =========================================================================
     */

    public function analytics(Request $request, $labId = null)
    {
        $selectedClass = trim((string) $request->query('class_group', ''));
        $classGroups = DB::table('class_groups')
            ->whereNotNull('token')
            ->where('token', '<>', '')
            ->orderBy('name')
            ->pluck('name');

        if ($selectedClass !== '' && !$classGroups->contains($selectedClass)) {
            $selectedClass = '';
        }

        $baseQuery = DB::table('lab_histories')
            ->join('users', 'lab_histories.user_id', '=', 'users.id')
            ->join('class_groups', 'users.class_group', '=', 'class_groups.name')
            ->whereNotNull('class_groups.token')
            ->where('class_groups.token', '<>', '')
            ->when($labId, fn ($q) => $q->where('lab_histories.lab_id', $labId))
            ->when($selectedClass !== '', fn ($q) => $q->where('users.class_group', $selectedClass));

        $totalAttempts = (clone $baseQuery)->count('lab_histories.id');
        $passedCount = (clone $baseQuery)->where('lab_histories.status', 'passed')->count('lab_histories.id');
        $failedCount = (clone $baseQuery)->where('lab_histories.status', 'failed')->count('lab_histories.id');

        $completionRate = $totalAttempts > 0 ? round(($passedCount / $totalAttempts) * 100, 1) : 0;
        $avgScore = round((clone $baseQuery)->avg('lab_histories.final_score') ?? 0, 1);
        $avgDurationSeconds = (int) ((clone $baseQuery)->avg('lab_histories.duration_seconds') ?? 0);
        $avgDuration = gmdate("i:s", $avgDurationSeconds);

        $userPerformance = DB::table('lab_histories')
            ->join('users', 'lab_histories.user_id', '=', 'users.id')
            ->join('class_groups', 'users.class_group', '=', 'class_groups.name')
            ->select(
                'users.id as student_id',
                'users.name',
                'users.email',
                'users.class_group',
                DB::raw('COUNT(lab_histories.id) as total_tries'),
                DB::raw('MAX(lab_histories.final_score) as best_score'),
                DB::raw('MIN(lab_histories.final_score) as lowest_score'),
                DB::raw('AVG(lab_histories.final_score) as average_score'),
                DB::raw("SUM(CASE WHEN lab_histories.status = 'passed' THEN 1 ELSE 0 END) as passed_tries"),
                DB::raw("SUM(CASE WHEN lab_histories.status = 'failed' THEN 1 ELSE 0 END) as failed_tries"),
                DB::raw('AVG(lab_histories.duration_seconds) as avg_time'),
                DB::raw('MAX(lab_histories.created_at) as last_attempt'),
                DB::raw('MAX(lab_histories.id) as latest_history_id')
            )
            ->whereNotNull('class_groups.token')
            ->where('class_groups.token', '<>', '')
            ->when($labId, fn ($q) => $q->where('lab_histories.lab_id', $labId))
            ->when($selectedClass !== '', fn ($q) => $q->where('users.class_group', $selectedClass))
            ->groupBy('users.id', 'users.name', 'users.email', 'users.class_group')
            ->orderByDesc('best_score')
            ->get();

        $classPerformance = DB::table('lab_histories')
            ->join('users', 'lab_histories.user_id', '=', 'users.id')
            ->join('class_groups', 'users.class_group', '=', 'class_groups.name')
            ->select(
                'class_groups.name as class_group',
                'class_groups.major',
                'class_groups.token',
                'class_groups.is_active'
            )
            ->selectRaw('COUNT(DISTINCT users.id) as students_count')
            ->selectRaw('COUNT(lab_histories.id) as total_attempts')
            ->selectRaw('AVG(lab_histories.final_score) as avg_score')
            ->selectRaw("SUM(CASE WHEN lab_histories.status = 'passed' THEN 1 ELSE 0 END) as passed_attempts")
            ->selectRaw("SUM(CASE WHEN lab_histories.status = 'failed' THEN 1 ELSE 0 END) as failed_attempts")
            ->selectRaw('AVG(lab_histories.duration_seconds) as avg_time')
            ->selectRaw('MAX(lab_histories.created_at) as last_attempt')
            ->whereNotNull('class_groups.token')
            ->where('class_groups.token', '<>', '')
            ->when($labId, fn ($q) => $q->where('lab_histories.lab_id', $labId))
            ->when($selectedClass !== '', fn ($q) => $q->where('users.class_group', $selectedClass))
            ->groupBy('class_groups.name', 'class_groups.major', 'class_groups.token', 'class_groups.is_active')
            ->orderBy('class_group')
            ->get();

        $enrolledStudentsByClass = DB::table('users')
            ->join('class_groups', 'users.class_group', '=', 'class_groups.name')
            ->whereNotNull('class_groups.token')
            ->where('class_groups.token', '<>', '')
            ->select('class_groups.name', DB::raw('COUNT(users.id) as total_students'))
            ->groupBy('class_groups.name')
            ->pluck('total_students', 'class_groups.name');

        $classPerformance = $classPerformance->map(function ($row) use ($enrolledStudentsByClass) {
            $row->students_count = (int) ($row->students_count ?? 0);
            $row->enrolled_students = (int) ($enrolledStudentsByClass[$row->class_group] ?? $row->students_count);
            $row->total_attempts = (int) ($row->total_attempts ?? 0);
            $row->passed_attempts = (int) ($row->passed_attempts ?? 0);
            $row->failed_attempts = (int) ($row->failed_attempts ?? 0);
            $row->avg_score = round((float) ($row->avg_score ?? 0), 1);
            $row->avg_time = (int) ($row->avg_time ?? 0);
            $row->avg_time_label = gmdate('i:s', $row->avg_time);
            $row->pass_rate = $row->total_attempts > 0 ? round(($row->passed_attempts / $row->total_attempts) * 100, 1) : 0;
            $row->last_attempt_label = $row->last_attempt
                ? \Carbon\Carbon::parse($row->last_attempt)->diffForHumans()
                : 'Belum ada aktivitas';
            $row->status_label = (int) $row->is_active === 1 ? 'Aktif' : 'Nonaktif';

            return $row;
        });

        $labScoreRows = DB::table('lab_histories')
            ->join('labs', 'lab_histories.lab_id', '=', 'labs.id')
            ->join('users', 'lab_histories.user_id', '=', 'users.id')
            ->join('class_groups', 'users.class_group', '=', 'class_groups.name')
            ->select(
                'lab_histories.user_id',
                'lab_histories.lab_id',
                'lab_histories.final_score',
                'lab_histories.updated_at',
                'labs.title as lab_title',
                'users.class_group'
            )
            ->whereNotNull('lab_histories.final_score')
            ->whereNotNull('class_groups.token')
            ->where('class_groups.token', '<>', '')
            ->when($labId, fn ($q) => $q->where('lab_histories.lab_id', $labId))
            ->when($selectedClass !== '', fn ($q) => $q->where('users.class_group', $selectedClass))
            ->get();

        $bestLabScores = $labScoreRows
            ->groupBy(fn ($item) => $item->user_id . '-' . $item->lab_id)
            ->map(function ($items) {
                return $items->sortByDesc('final_score')->sortByDesc('updated_at')->first();
            })
            ->values();

        $labScoreTrend = $bestLabScores
            ->groupBy('lab_id')
            ->map(function ($items) {
                $first = $items->first();

                return [
                    'label' => Str::limit($first->lab_title ?? 'Lab', 18),
                    'score' => round((float) $items->avg('final_score'), 1),
                    'participants' => $items->count(),
                ];
            })
            ->values();

        $labChartLabels = $labScoreTrend->pluck('label')->values();
        $labChartScores = $labScoreTrend->pluck('score')->values();
        $labChartParticipants = $labScoreTrend->pluck('participants')->values();
        $validLabScores = $labChartScores->filter(fn ($score) => $score !== null);
        $labChartAverage = $validLabScores->count() ? round((float) $validLabScores->avg(), 1) : null;
        $labChartHighest = $validLabScores->count() ? round((float) $validLabScores->max(), 1) : null;
        $labChartLowest = $validLabScores->count() ? round((float) $validLabScores->min(), 1) : null;
        $hasLabChartData = $validLabScores->count() > 0;
        
        $labsList = DB::table('labs')->select('id', 'title')->get();
        
        return view('admin.lab_analytics', compact(
            'totalAttempts', 'passedCount', 'failedCount', 'completionRate',
            'avgScore', 'avgDuration', 'userPerformance', 'labChartLabels',
            'labChartScores', 'labChartParticipants', 'labChartAverage',
            'labChartHighest', 'labChartLowest', 'hasLabChartData', 'labsList', 'labId',
            'classGroups', 'classPerformance', 'selectedClass'
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

    public function workspaceHistory($historyId)
    {
        $history = LabHistory::with('lab.steps')
            ->where('id', $historyId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $lab = $history->lab;
        abort_if(!$lab, 404);

        $session = new LabSession();
        $session->id = 0;
        $session->user_id = $history->user_id;
        $session->lab_id = $history->lab_id;
        $session->status = 'completed';
        $session->current_code = $history->source_code ?: $history->last_code_snapshot;
        $session->current_score = $history->final_score ?? 0;
        $session->started_at = $history->created_at;
        $session->expires_at = $history->completed_at ?: $history->updated_at;
        $session->review_result_url = route('lab.result', $history->id);

        $completedStepIds = collect(json_decode($history->completed_steps ?? '[]', true) ?: [])
            ->map(fn ($id) => (int) $id)
            ->values()
            ->all();

        return view('labs.workspace', compact('lab', 'session', 'completedStepIds', 'history'));
    }

    public function result($historyId)
    {
        $history = LabHistory::with('lab.steps')
            ->where('id', $historyId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $analysis = LabResultAnalyzer::analyze($history);
        $lab = $analysis['lab'];
        $reviewItems = $analysis['review_items'];
        $score = $analysis['score'];
        $isPassed = $analysis['is_passed'];
        $metrics = $analysis['metrics'];
        $feedback = $analysis['feedback'];
        $sourceCode = $analysis['source_code'];
        $chapterSummary = ChapterSummary::forLab($lab);

        return view('labs.result', compact(
            'history',
            'lab',
            'reviewItems',
            'score',
            'isPassed',
            'metrics',
            'feedback',
            'chapterSummary',
            'sourceCode',
            'analysis'
        ));
    }

    public function adminResult($historyId)
    {
        $history = LabHistory::with(['lab.steps', 'user'])->findOrFail($historyId);
        $analysis = LabResultAnalyzer::analyze($history);
        $lab = $analysis['lab'];
        $student = $history->user;
        $chapterSummary = ChapterSummary::forLab($lab);

        $previousAttempts = LabHistory::with('lab')
            ->where('user_id', $history->user_id)
            ->where('lab_id', $history->lab_id)
            ->latest('created_at')
            ->limit(8)
            ->get();

        $labStats = [
            'average_score' => round((float) (LabHistory::where('lab_id', $history->lab_id)->avg('final_score') ?? 0), 1),
            'attempts' => LabHistory::where('lab_id', $history->lab_id)->count(),
            'passed' => LabHistory::where('lab_id', $history->lab_id)->where('status', 'passed')->count(),
        ];

        $labStats['pass_rate'] = $labStats['attempts'] > 0
            ? round(($labStats['passed'] / $labStats['attempts']) * 100, 1)
            : 0;

        return view('admin.lab_result_review', compact(
            'history',
            'analysis',
            'lab',
            'student',
            'chapterSummary',
            'previousAttempts',
            'labStats'
        ));
    }

    public function check(Request $request, $id)
    {
        $session = LabSession::find($id);
        if (!$session) return response()->json(['status' => 'error', 'message' => 'Sesi invalid'], 404);
        
        // FIX: Parsing $session->expires_at dengan Carbon
        if (Carbon::now()->greaterThan(Carbon::parse($session->expires_at))) {
            $redirect = $this->handleExpiredSession($session);
            return response()->json(['status' => 'expired', 'message' => 'Waktu pengerjaan telah habis!', 'redirect' => $redirect->getTargetUrl()]);
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
                'output' => "Benar! Tugas selesai."
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
            $redirect = $this->handleExpiredSession($session);
            return response()->json(['status' => 'expired', 'message' => 'Waktu Habis', 'redirect' => $redirect->getTargetUrl()]);
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
            
            $history = LabHistory::create([
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
                'redirect_url' => route('lab.result', $history->id)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Gagal submit: ' . $e->getMessage()], 500);
        }
    }

    private function validateStepRules($jsonRules, $code, &$failedRule = null)
    {
        return LabResultAnalyzer::validateStepRules($jsonRules, $code, $failedRule);
    }

    private function decodeValidationRules($jsonRules): array
    {
        return LabResultAnalyzer::decodeValidationRules($jsonRules);
    }

    private function buildLabFeedback(int $score, array $metrics, int $passingGrade): array
    {
        if ($score >= 90) {
            $level = 'Sangat Baik';
            $message = 'Implementasi lab sudah sangat kuat. Struktur kode dan pemenuhan task menunjukkan pemahaman praktik yang matang.';
        } elseif ($score >= $passingGrade) {
            $level = 'Lulus';
            $message = 'Lab sudah memenuhi nilai minimal. Tetap tinjau task yang belum sempurna agar pola implementasi lebih stabil.';
        } else {
            $level = 'Perlu Penguatan';
            $message = 'Nilai lab belum mencapai batas kelulusan. Fokus ulang pada instruksi task, class yang diwajibkan, dan hubungan kode dengan preview.';
        }

        $remainingSteps = max(0, ($metrics['total_steps'] ?? 0) - ($metrics['completed_steps'] ?? 0));

        if ($remainingSteps > 0) {
            $message .= ' Masih ada ' . $remainingSteps . ' tugas yang perlu dilengkapi atau diperbaiki.';
        }

        return compact('level', 'message');
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

        $historyId = DB::table('lab_histories')->insertGetId([
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
        
        return redirect()->route('lab.result', $historyId)->with('error', "Waktu Habis! Lab otomatis dikumpulkan. Nilai Anda: {$finalScore}");
    }

    private function finalizeAndDestroy($session, $message)
    {
        $lab = DB::table('labs')->where('id', $session->lab_id)->first();
        $passingGrade = $lab->passing_grade ?? 50;
        
        $historyId = DB::table('lab_histories')->insertGetId([
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

        return redirect()->route('lab.result', $historyId)->with('success', "$message Nilai Anda: {$session->current_score}");
    }

    private function filterColumns(string $table, array $attributes): array
    {
        return collect($attributes)
            ->filter(fn ($value, $column) => Schema::hasColumn($table, $column))
            ->all();
    }
}
