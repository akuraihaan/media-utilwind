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
        $selectedPeriod = (string) $request->query('period', 'all');
        $periodOptions = [
            'all' => 'Semua waktu',
            '7d' => '7 hari terakhir',
            '30d' => '30 hari terakhir',
            '6m' => '6 bulan terakhir',
        ];

        if (!array_key_exists($selectedPeriod, $periodOptions)) {
            $selectedPeriod = 'all';
        }

        $periodStart = match ($selectedPeriod) {
            '7d' => Carbon::now()->subDays(6)->startOfDay(),
            '30d' => Carbon::now()->subDays(29)->startOfDay(),
            '6m' => Carbon::now()->subMonths(6)->startOfDay(),
            default => null,
        };
        $periodLabel = $periodOptions[$selectedPeriod];

        $managedClassGroups = DB::table('class_groups')
            ->whereNotNull('token')
            ->where('token', '<>', '')
            ->orderBy('name')
            ->pluck('name');
        $activityClassGroups = DB::table('lab_histories')
            ->join('users', 'lab_histories.user_id', '=', 'users.id')
            ->where('users.role', 'student')
            ->whereNotNull('users.class_group')
            ->where('users.class_group', '<>', '')
            ->when($labId, fn ($q) => $q->where('lab_histories.lab_id', $labId))
            ->when($periodStart, fn ($q) => $q->where('lab_histories.created_at', '>=', $periodStart))
            ->distinct()
            ->pluck('users.class_group');
        $classGroups = $managedClassGroups
            ->merge($activityClassGroups)
            ->filter()
            ->unique()
            ->sort()
            ->values();

        if ($selectedClass !== '' && !$classGroups->contains($selectedClass)) {
            $selectedClass = '';
        }

        $baseQuery = DB::table('lab_histories')
            ->join('users', 'lab_histories.user_id', '=', 'users.id')
            ->leftJoin('class_groups', 'users.class_group', '=', 'class_groups.name')
            ->where('users.role', 'student')
            ->whereNotNull('users.class_group')
            ->where('users.class_group', '<>', '')
            ->when($labId, fn ($q) => $q->where('lab_histories.lab_id', $labId))
            ->when($periodStart, fn ($q) => $q->where('lab_histories.created_at', '>=', $periodStart))
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
            ->leftJoin('class_groups', 'users.class_group', '=', 'class_groups.name')
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
            ->where('users.role', 'student')
            ->whereNotNull('users.class_group')
            ->where('users.class_group', '<>', '')
            ->when($labId, fn ($q) => $q->where('lab_histories.lab_id', $labId))
            ->when($periodStart, fn ($q) => $q->where('lab_histories.created_at', '>=', $periodStart))
            ->when($selectedClass !== '', fn ($q) => $q->where('users.class_group', $selectedClass))
            ->groupBy('users.id', 'users.name', 'users.email', 'users.class_group')
            ->orderByDesc('best_score')
            ->get()
            ->map(function ($row) {
                $row->total_tries = (int) ($row->total_tries ?? 0);
                $row->passed_tries = (int) ($row->passed_tries ?? 0);
                $row->failed_tries = (int) ($row->failed_tries ?? 0);
                $row->best_score = round((float) ($row->best_score ?? 0), 1);
                $row->lowest_score = round((float) ($row->lowest_score ?? 0), 1);
                $row->average_score = round((float) ($row->average_score ?? 0), 1);
                $row->avg_time = (int) ($row->avg_time ?? 0);

                return $row;
            });

        $classPerformance = DB::table('users')
            ->leftJoin('class_groups', 'users.class_group', '=', 'class_groups.name')
            ->leftJoin('lab_histories', function ($join) use ($labId, $periodStart) {
                $join->on('lab_histories.user_id', '=', 'users.id');

                if ($labId) {
                    $join->where('lab_histories.lab_id', $labId);
                }

                if ($periodStart) {
                    $join->where('lab_histories.created_at', '>=', $periodStart);
                }
            })
            ->select(
                'users.class_group as class_group',
                'class_groups.major',
                'class_groups.token',
                'class_groups.is_active'
            )
            ->selectRaw('COUNT(DISTINCT users.id) as enrolled_students')
            ->selectRaw('COUNT(DISTINCT CASE WHEN lab_histories.id IS NOT NULL THEN users.id END) as students_count')
            ->selectRaw('COUNT(lab_histories.id) as total_attempts')
            ->selectRaw('AVG(lab_histories.final_score) as avg_score')
            ->selectRaw("SUM(CASE WHEN lab_histories.status = 'passed' THEN 1 ELSE 0 END) as passed_attempts")
            ->selectRaw("SUM(CASE WHEN lab_histories.status = 'failed' THEN 1 ELSE 0 END) as failed_attempts")
            ->selectRaw('AVG(lab_histories.duration_seconds) as avg_time')
            ->selectRaw('MAX(lab_histories.created_at) as last_attempt')
            ->where('users.role', 'student')
            ->whereNotNull('users.class_group')
            ->where('users.class_group', '<>', '')
            ->when($selectedClass !== '', fn ($q) => $q->where('users.class_group', $selectedClass))
            ->groupBy('users.class_group', 'class_groups.major', 'class_groups.token', 'class_groups.is_active')
            ->orderBy('class_group')
            ->get();

        $classPerformance = $classPerformance->map(function ($row) {
            $row->students_count = (int) ($row->students_count ?? 0);
            $row->enrolled_students = (int) ($row->enrolled_students ?? $row->students_count);
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
            $row->status_label = $row->is_active === null
                ? 'Tanpa Token'
                : ((int) $row->is_active === 1 ? 'Aktif' : 'Nonaktif');

            return $row;
        });

        $labScoreRows = DB::table('lab_histories')
            ->join('labs', 'lab_histories.lab_id', '=', 'labs.id')
            ->join('users', 'lab_histories.user_id', '=', 'users.id')
            ->leftJoin('class_groups', 'users.class_group', '=', 'class_groups.name')
            ->select(
                'lab_histories.user_id',
                'lab_histories.lab_id',
                'lab_histories.final_score',
                'lab_histories.updated_at',
                'labs.title as lab_title',
                'users.class_group'
            )
            ->whereNotNull('lab_histories.final_score')
            ->where('users.role', 'student')
            ->whereNotNull('users.class_group')
            ->where('users.class_group', '<>', '')
            ->when($labId, fn ($q) => $q->where('lab_histories.lab_id', $labId))
            ->when($periodStart, fn ($q) => $q->where('lab_histories.created_at', '>=', $periodStart))
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

        $historyRows = DB::table('lab_histories')
            ->join('labs', 'lab_histories.lab_id', '=', 'labs.id')
            ->join('users', 'lab_histories.user_id', '=', 'users.id')
            ->leftJoin('class_groups', 'users.class_group', '=', 'class_groups.name')
            ->select(
                'lab_histories.id',
                'lab_histories.user_id',
                'lab_histories.lab_id',
                'lab_histories.final_score',
                'lab_histories.status',
                'lab_histories.duration_seconds',
                'lab_histories.completed_steps',
                'lab_histories.created_at as attempted_at',
                'labs.title as lab_title',
                'users.name as student_name',
                'users.email as student_email',
                'users.class_group'
            )
            ->where('users.role', 'student')
            ->whereNotNull('users.class_group')
            ->where('users.class_group', '<>', '')
            ->when($labId, fn ($q) => $q->where('lab_histories.lab_id', $labId))
            ->when($periodStart, fn ($q) => $q->where('lab_histories.created_at', '>=', $periodStart))
            ->when($selectedClass !== '', fn ($q) => $q->where('users.class_group', $selectedClass))
            ->orderByDesc('lab_histories.created_at')
            ->get()
            ->map(function ($row) {
                $row->final_score = round((float) ($row->final_score ?? 0), 1);
                $row->duration_seconds = (int) ($row->duration_seconds ?? 0);
                $row->duration_label = $this->formatSecondsShort($row->duration_seconds);
                $row->status_label = ($row->status ?? '') === 'passed' ? 'Lulus' : 'Belum lulus';
                $row->attempted_sort = $row->attempted_at ? Carbon::parse($row->attempted_at)->timestamp : 0;
                $row->attempted_label = $row->attempted_at ? Carbon::parse($row->attempted_at)->diffForHumans() : '-';

                return $row;
            });

        $labStepRows = DB::table('lab_steps')
            ->join('labs', 'lab_steps.lab_id', '=', 'labs.id')
            ->select(
                'lab_steps.id',
                'lab_steps.lab_id',
                'lab_steps.title as step_title',
                'lab_steps.order_index',
                'labs.title as lab_title'
            )
            ->when($labId, fn ($q) => $q->where('lab_steps.lab_id', $labId))
            ->orderBy('labs.id')
            ->orderBy('lab_steps.order_index')
            ->get();

        $stepIdsByLab = $labStepRows
            ->groupBy('lab_id')
            ->map(fn ($steps) => $steps->pluck('id')->map(fn ($id) => (int) $id)->values());

        $completedIdsForAttempt = function ($row) use ($stepIdsByLab): array {
            $decoded = json_decode($row->completed_steps ?? '[]', true);
            $ids = collect(is_array($decoded) ? $decoded : [])
                ->map(fn ($id) => (int) $id)
                ->filter()
                ->values();

            if ($ids->isEmpty() && ($row->status ?? '') === 'passed') {
                return collect($stepIdsByLab->get((int) $row->lab_id, collect()))->all();
            }

            return $ids->all();
        };

        $latestAttemptRows = $historyRows
            ->sortByDesc('attempted_sort')
            ->unique(fn ($row) => $row->user_id . '-' . $row->lab_id)
            ->values();

        $stepObstacleRows = $labStepRows
            ->map(function ($step) use ($latestAttemptRows, $completedIdsForAttempt) {
                $attempts = $latestAttemptRows->where('lab_id', $step->lab_id)->values();
                $total = $attempts->count();

                if ($total === 0) {
                    return null;
                }

                $completedCount = $attempts
                    ->filter(fn ($row) => in_array((int) $step->id, $completedIdsForAttempt($row), true))
                    ->count();
                $unresolvedCount = max(0, $total - $completedCount);

                return [
                    'lab_id' => (int) $step->lab_id,
                    'lab_title' => $step->lab_title,
                    'step_title' => $step->step_title,
                    'order_index' => (int) ($step->order_index ?? 0),
                    'attempt_count' => $total,
                    'completed_count' => $completedCount,
                    'unresolved_count' => $unresolvedCount,
                    'completion_rate' => $total > 0 ? round(($completedCount / $total) * 100, 1) : 0,
                ];
            })
            ->filter(fn ($row) => $row && $row['unresolved_count'] > 0)
            ->sortByDesc('unresolved_count')
            ->take(3)
            ->values();

        $labObstacleRows = $latestAttemptRows
            ->groupBy('lab_id')
            ->map(function ($attempts, $currentLabId) use ($labStepRows, $completedIdsForAttempt, $stepObstacleRows) {
                $steps = $labStepRows->where('lab_id', (int) $currentLabId)->values();
                $stepCount = max(1, $steps->count());
                $incompleteAverage = $attempts->avg(function ($row) use ($steps, $completedIdsForAttempt, $stepCount) {
                    if ($steps->isEmpty()) {
                        return ($row->status ?? '') === 'passed' ? 0 : 1;
                    }

                    $completedIds = $completedIdsForAttempt($row);

                    return max(0, $stepCount - $steps->filter(fn ($step) => in_array((int) $step->id, $completedIds, true))->count());
                });
                $weakestStep = $stepObstacleRows->firstWhere('lab_id', (int) $currentLabId);

                return [
                    'lab_id' => (int) $currentLabId,
                    'lab_title' => $attempts->first()->lab_title ?? 'Lab',
                    'students_count' => $attempts->pluck('user_id')->unique()->count(),
                    'latest_attempts' => $attempts->count(),
                    'not_passed_count' => $attempts->filter(fn ($row) => ($row->status ?? '') !== 'passed')->count(),
                    'avg_score' => round((float) $attempts->avg('final_score'), 1),
                    'avg_incomplete_steps' => round((float) $incompleteAverage, 1),
                    'weakest_step' => $weakestStep['step_title'] ?? 'Belum ada langkah dominan',
                    'risk_percent' => $attempts->count() > 0
                        ? round(($attempts->filter(fn ($row) => ($row->status ?? '') !== 'passed')->count() / $attempts->count()) * 100, 1)
                        : 0,
                ];
            })
            ->sortByDesc(fn ($row) => ($row['not_passed_count'] * 1000) + ($row['avg_incomplete_steps'] * 10) + max(0, 100 - $row['avg_score']))
            ->take(3)
            ->values();

        $studentIdsInScope = DB::table('users')
            ->leftJoin('class_groups', 'users.class_group', '=', 'class_groups.name')
            ->where('users.role', 'student')
            ->whereNotNull('users.class_group')
            ->where('users.class_group', '<>', '')
            ->when($selectedClass !== '', fn ($q) => $q->where('users.class_group', $selectedClass))
            ->pluck('users.id');

        $lessonObstacleRows = collect();

        if (Schema::hasTable('course_lessons') && $studentIdsInScope->isNotEmpty()) {
            $lessonCompletionCounts = DB::table('user_lesson_progress')
                ->whereIn('user_id', $studentIdsInScope)
                ->where('completed', true)
                ->select('course_lesson_id', DB::raw('COUNT(DISTINCT user_id) as completed_students'))
                ->groupBy('course_lesson_id')
                ->pluck('completed_students', 'course_lesson_id');

            $studentTotalInScope = $studentIdsInScope->unique()->count();

            $lessonObstacleRows = DB::table('course_lessons')
                ->select('id', 'title', 'order')
                ->orderBy('order')
                ->get()
                ->map(function ($lesson) use ($lessonCompletionCounts, $studentTotalInScope) {
                    $completedStudents = (int) ($lessonCompletionCounts[$lesson->id] ?? 0);
                    $unfinishedStudents = max(0, $studentTotalInScope - $completedStudents);

                    return [
                        'lesson_id' => (int) $lesson->id,
                        'title' => $lesson->title,
                        'order' => (int) ($lesson->order ?? 0),
                        'completed_students' => $completedStudents,
                        'unfinished_students' => $unfinishedStudents,
                        'completion_rate' => $studentTotalInScope > 0 ? round(($completedStudents / $studentTotalInScope) * 100, 1) : 0,
                    ];
                })
                ->filter(fn ($row) => $row['unfinished_students'] > 0)
                ->sortByDesc('unfinished_students')
                ->take(3)
                ->values();
        }

        $followUpRecommendations = collect();
        $topStepObstacle = $stepObstacleRows->first();
        $topLabObstacle = $labObstacleRows->first();
        $topLessonObstacle = $lessonObstacleRows->first();

        if ($topStepObstacle) {
            $followUpRecommendations->push([
                'tone' => 'amber',
                'title' => 'Ulangi materi terkait ' . $topStepObstacle['step_title'],
                'metric' => $topStepObstacle['completion_rate'] . '% tuntas',
                'reason' => 'Langkah ini menjadi hambatan terbesar pada filter aktif.',
                'evidence' => $topStepObstacle['unresolved_count'] . ' siswa belum menuntaskan langkah ini di ' . $topStepObstacle['lab_title'] . '.',
                'next_section' => 'Catatan Praktik',
                'next_hint' => 'Mulai dari langkah yang sering tertunda, lalu beri contoh singkat sebelum siswa mengulang lab.',
            ]);
        }

        if ($topLabObstacle && $topLabObstacle['not_passed_count'] > 0) {
            $followUpRecommendations->push([
                'tone' => 'rose',
                'title' => 'Selesaikan ' . $topLabObstacle['lab_title'] . ' sebelum lanjut',
                'metric' => $topLabObstacle['risk_percent'] . '% belum lulus',
                'reason' => 'Lab ini memiliki rasio belum lulus paling tinggi.',
                'evidence' => $topLabObstacle['not_passed_count'] . ' siswa pada percobaan terakhir belum lulus.',
                'next_section' => 'Performa per Kelas',
                'next_hint' => 'Cek kelas dengan partisipasi rendah atau rasio lulus kecil, lalu jadwalkan remedial praktik.',
            ]);
        }

        $responsiveSignal = $stepObstacleRows->first(function ($row) {
            $text = Str::lower(($row['lab_title'] ?? '') . ' ' . ($row['step_title'] ?? ''));

            return Str::contains($text, ['responsive', 'responsif', 'breakpoint']);
        }) ?: $labObstacleRows->first(function ($row) {
            return Str::contains(Str::lower($row['lab_title'] ?? ''), ['responsive', 'responsif', 'breakpoint']);
        });

        if ($responsiveSignal) {
            $followUpRecommendations->push([
                'tone' => 'cyan',
                'title' => 'Indikator responsivitas perlu diperkuat',
                'metric' => ($responsiveSignal['lab_title'] ?? 'Responsif'),
                'reason' => 'Data hambatan menunjukkan sinyal terkait responsivitas atau breakpoint.',
                'evidence' => 'Sinyal muncul dari judul lab atau langkah yang sering belum tuntas.',
                'next_section' => 'Catatan Praktik',
                'next_hint' => 'Berikan latihan kecil tentang breakpoint, urutan class responsif, dan pengecekan live preview.',
            ]);
        }

        if ($topLessonObstacle) {
            $followUpRecommendations->push([
                'tone' => 'indigo',
                'title' => 'Tinjau ulang subbab ' . $topLessonObstacle['title'],
                'metric' => $topLessonObstacle['completion_rate'] . '% selesai',
                'reason' => 'Materi pendukung belum cukup kuat sebelum siswa mengulang praktik.',
                'evidence' => $topLessonObstacle['unfinished_students'] . ' siswa belum menandai subbab ini selesai.',
                'next_section' => 'Materi pendukung',
                'next_hint' => 'Arahkan siswa menuntaskan subbab ini, lalu hubungkan kembali dengan instruksi lab terkait.',
            ]);
        }

        if ($followUpRecommendations->isEmpty()) {
            $followUpRecommendations->push([
                'tone' => 'emerald',
                'title' => 'Tidak ada hambatan dominan',
                'metric' => 'Stabil',
                'reason' => 'Rasio lulus, skor, dan hambatan langkah belum menunjukkan masalah besar.',
                'evidence' => 'Belum ada lab, langkah, atau materi yang menonjol sebagai prioritas intervensi.',
                'next_section' => 'Pendampingan Siswa',
                'next_hint' => 'Pertahankan umpan balik rutin dan cek siswa yang mulai menurun pada percobaan berikutnya.',
            ]);
        }

        $followUpRecommendations = $followUpRecommendations->take(3)->values();
        
        $labsList = DB::table('labs')->select('id', 'title')->get();
        
        return view('admin.lab_analytics', compact(
            'totalAttempts', 'passedCount', 'failedCount', 'completionRate',
            'avgScore', 'avgDuration', 'userPerformance', 'labChartLabels',
            'labChartScores', 'labChartParticipants', 'labChartAverage',
            'labChartHighest', 'labChartLowest', 'hasLabChartData', 'labsList', 'labId',
            'classGroups', 'classPerformance', 'selectedClass',
            'selectedPeriod', 'periodOptions', 'periodLabel',
            'labObstacleRows', 'stepObstacleRows', 'lessonObstacleRows',
            'followUpRecommendations'
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
        LabSession::create($this->filterColumns('lab_sessions', [
            'user_id' => $userId,
            'lab_id' => $id,
            'status' => 'active',
            'started_at' => now(),
            'expires_at' => now()->addMinutes($duration), // Aman karena $duration dipastikan integer
            'current_code' => $initialCode,
            'current_score' => 0, 
            'save_count' => 0,
            'validation_attempt_count' => 0,
            'code_change_count' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]));
        
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

        $isValidationAttempt = $request->filled('step_id');
        $isManualSave = !$isValidationAttempt || $request->input('event_type') === 'manual_save';

        if ($request->has('source_code')) {
            $session->current_code = $request->source_code;
        }

        $this->applyLabInteractionCounters($session, $request, $isManualSave, $isValidationAttempt);
        $session->updated_at = now();
        $session->save();

        if (!$isValidationAttempt) {
            return response()->json([
                'status' => 'success',
                'output' => 'Auto-saved.',
            ] + $this->labInteractionPayload($session));
        }
        
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
            ] + $this->labInteractionPayload($session));
        }

        return response()->json([
            'status' => 'error',
            'message' => "Syarat belum terpenuhi: Kode wajib mengandung '{$failedRule}'"
        ] + $this->labInteractionPayload($session));
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
        $session->current_code = $finalCode;
        $this->applyLabInteractionCounters($session, $request, false, false);
        $session->save();

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
            
            $history = LabHistory::create($this->filterColumns('lab_histories', [
                'user_id' => $userId,
                'lab_id' => $session->lab_id,
                'last_code_snapshot' => $finalCode,
                'source_code'        => $finalCode,
                'status' => $status,
                'final_score' => $finalScore,
                'duration_seconds' => $durationSeconds,
                'completed_steps' => $completedStepsData, 
                'save_count' => (int) ($session->save_count ?? 0),
                'validation_attempt_count' => (int) ($session->validation_attempt_count ?? 0),
                'code_change_count' => (int) ($session->code_change_count ?? 0),
                'created_at' => now(),
                'updated_at' => now(),
                'completed_at' => now()
            ]));
            
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

        $historyId = DB::table('lab_histories')->insertGetId($this->filterColumns('lab_histories', [
            'user_id' => $session->user_id,
            'lab_id' => $session->lab_id,
            'final_score' => $finalScore,
            'duration_seconds' => $durationSeconds,
            'last_code_snapshot' => $finalCode,
            'source_code' => $finalCode,
            'completed_steps' => $session->completed_steps,
            'save_count' => (int) ($session->save_count ?? 0),
            'validation_attempt_count' => (int) ($session->validation_attempt_count ?? 0),
            'code_change_count' => (int) ($session->code_change_count ?? 0),
            'completed_at' => now(),
            'status' => ($finalScore >= $passingGrade) ? 'passed' : 'failed',
            'created_at' => now(),
            'updated_at' => now()
        ]));
        
        DB::table('lab_sessions')->where('id', $session->id)->delete();
        
        return redirect()->route('lab.result', $historyId)->with('error', "Waktu Habis! Lab otomatis dikumpulkan. Nilai Anda: {$finalScore}");
    }

    private function finalizeAndDestroy($session, $message)
    {
        $lab = DB::table('labs')->where('id', $session->lab_id)->first();
        $passingGrade = $lab->passing_grade ?? 50;
        
        $historyId = DB::table('lab_histories')->insertGetId($this->filterColumns('lab_histories', [
            'user_id' => $session->user_id,
            'lab_id' => $session->lab_id,
            'final_score' => $session->current_score,
            // FIX: Parsing tanggal dengan Carbon
            'duration_seconds' => abs(now()->diffInSeconds(Carbon::parse($session->started_at))),
            'last_code_snapshot' => $session->current_code,
            'source_code' => $session->current_code,
            'completed_steps' => $session->completed_steps,
            'save_count' => (int) ($session->save_count ?? 0),
            'validation_attempt_count' => (int) ($session->validation_attempt_count ?? 0),
            'code_change_count' => (int) ($session->code_change_count ?? 0),
            'completed_at' => now(),
            'status' => ($session->current_score >= $passingGrade) ? 'passed' : 'failed',
            'created_at' => now(),
            'updated_at' => now()
        ]));
        
        // FIX: $sessionId diganti menjadi $session->id
        DB::table('lab_sessions')->where('id', $session->id)->delete();

        return redirect()->route('lab.result', $historyId)->with('success', "$message Nilai Anda: {$session->current_score}");
    }

    private function applyLabInteractionCounters(LabSession $session, Request $request, bool $countSave, bool $countValidation): void
    {
        if ($countSave && Schema::hasColumn('lab_sessions', 'save_count')) {
            $session->save_count = (int) ($session->save_count ?? 0) + 1;
        }

        if ($countValidation && Schema::hasColumn('lab_sessions', 'validation_attempt_count')) {
            $session->validation_attempt_count = (int) ($session->validation_attempt_count ?? 0) + 1;
        }

        if (Schema::hasColumn('lab_sessions', 'code_change_count')) {
            $clientChangeCount = max(0, (int) $request->input('code_change_count', 0));
            $session->code_change_count = max((int) ($session->code_change_count ?? 0), $clientChangeCount);
        }
    }

    private function labInteractionPayload(LabSession $session): array
    {
        return [
            'save_count' => (int) ($session->save_count ?? 0),
            'validation_attempt_count' => (int) ($session->validation_attempt_count ?? 0),
            'code_change_count' => (int) ($session->code_change_count ?? 0),
        ];
    }

    private function formatSecondsShort($seconds): string
    {
        $seconds = max(0, (int) round((float) ($seconds ?? 0)));

        if ($seconds === 0) {
            return '0s';
        }

        $hours = intdiv($seconds, 3600);
        $minutes = intdiv($seconds % 3600, 60);
        $remainingSeconds = $seconds % 60;
        $parts = [];

        if ($hours > 0) {
            $parts[] = $hours . 'j';
        }

        if ($minutes > 0) {
            $parts[] = $minutes . 'm';
        }

        if ($remainingSeconds > 0 || empty($parts)) {
            $parts[] = $remainingSeconds . 's';
        }

        return implode(' ', array_slice($parts, 0, 2));
    }

    private function filterColumns(string $table, array $attributes): array
    {
        return collect($attributes)
            ->filter(fn ($value, $column) => Schema::hasColumn($table, $column))
            ->all();
    }
}
