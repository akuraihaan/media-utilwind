<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\CourseLesson;
use App\Models\UserLessonProgress;
use App\Models\UserActivityProgress;
use App\Models\QuizAttempt;
use App\Models\LabHistory;
use App\Models\Lab;
use App\Models\LabSession;

class CourseController extends Controller
{
    /**
     * Mapping ini mengikuti isi tabel course_lessons.
     * Setiap subbab terdiri dari 5 lesson: 4 materi + 1 aktivitas.
     * Aktivitas tidak dianggap selesai hanya karena halaman discroll.
     */
    private array $lessonMap = [
        '1.1' => ['range' => [1, 5],   'activity' => 1,  'view' => 'courses.htmldancss',     'required' => null],
        '1.2' => ['range' => [6, 10],  'activity' => 2,  'view' => 'courses.tailwindcss',    'required' => '1.1'],
        '1.3' => ['range' => [11, 15], 'activity' => 3,  'view' => 'courses.latarbelakang',  'required' => '1.2'],
        '1.4' => ['range' => [16, 20], 'activity' => 4,  'view' => 'courses.implementasi',   'required' => '1.3'],
        '1.5' => ['range' => [21, 25], 'activity' => 5,  'view' => 'courses.keunggulan',     'required' => '1.4'],

        '2.1' => ['range' => [26, 30], 'activity' => 6,  'view' => 'courses.layout-mgmt',    'required' => 'quiz_1'],
        '2.2' => ['range' => [31, 35], 'activity' => 7,  'view' => 'courses.flexbox',        'required' => '2.1'],
        '2.3' => ['range' => [36, 40], 'activity' => 8,  'view' => 'courses.grid',           'required' => '2.2'],
        '2.4' => ['range' => [41, 45], 'activity' => 9,  'view' => 'courses.responsive',     'required' => '2.3'],

        '3.1' => ['range' => [46, 50], 'activity' => 10, 'view' => 'courses.typography',     'required' => 'quiz_2'],
        '3.2' => ['range' => [51, 55], 'activity' => 11, 'view' => 'courses.backgrounds',    'required' => '3.1'],
        '3.3' => ['range' => [56, 60], 'activity' => 12, 'view' => 'courses.borders',        'required' => '3.2'],
        '3.4' => ['range' => [61, 65], 'activity' => 13, 'view' => 'courses.effects',        'required' => '3.3'],
    ];

    /**
     * Helper: status subbab, kuis, dan unlock bab.
     * Subbab dinilai selesai dari UserActivityProgress, bukan dari scroll lesson terakhir.
     */
    private function getChapterStatus($userId): array
    {
        $completedActivities = UserActivityProgress::where('user_id', $userId)
            ->where('completed', true)
            ->pluck('course_activity_id')
            ->map(fn ($id) => (int) $id)
            ->toArray();

        $passedChapters = QuizAttempt::where('user_id', $userId)
            ->whereNotNull('completed_at')
            ->select('chapter_id', 'score')
            ->get()
            ->groupBy('chapter_id')
            ->map(fn ($rows) => $rows->max('score'))
            ->filter(fn ($score) => $score >= 70)
            ->keys()
            ->map(fn ($id) => (string) $id)
            ->toArray();

        $status = [];
        foreach ($this->lessonMap as $key => $meta) {
            $status[$key] = in_array((int) $meta['activity'], $completedActivities, true);
        }

        $status['quiz_1'] = in_array('1', $passedChapters, true);
        $status['quiz_2'] = in_array('2', $passedChapters, true);
        $status['quiz_3'] = in_array('3', $passedChapters, true);

        return $status;
    }

    /**
     * Loader utama setiap subbab.
     */
    private function loadView(string $view, string $currentKey, array $lessonRange, int $activityId, ?string $requiredKey = null)
    {
        $user = Auth::user();
        $userId = $user->id;
        $isAdmin = $user->role === 'admin';

        $course = Course::where('slug', 'tailwind-css')->first()
            ?? Course::where('slug', 'tailwind-foundation')->first()
            ?? Course::find(1)
            ?? Course::firstOrFail();

        $statusMap = $this->getChapterStatus($userId);

        if (!$isAdmin && $requiredKey && empty($statusMap[$requiredKey])) {
            return redirect()
                ->route('dashboard')
                ->with('error', 'Selesaikan materi/kuis sebelumnya untuk mengakses halaman ini!');
        }

        $targetIds = range($lessonRange[0], $lessonRange[1]);

        $completedIds = UserLessonProgress::where('user_id', $userId)
            ->where('completed', true)
            ->pluck('course_lesson_id')
            ->map(fn ($id) => (int) $id)
            ->toArray();

        $activityDone = UserActivityProgress::where('user_id', $userId)
            ->where('course_activity_id', $activityId)
            ->where('completed', true)
            ->exists();

        /**
         * Progress subbab:
         * - 4 lesson materi tersimpan lewat scroll.
         * - 1 lesson aktivitas hanya tersimpan setelah aktivitas valid.
         * - Jika aktivitas sudah valid, subbab langsung 100%.
         */
        $doneCount = count(array_intersect($targetIds, $completedIds));
        $progressPercent = count($targetIds) > 0 ? round(($doneCount / count($targetIds)) * 100) : 0;
        if ($activityDone) {
            $progressPercent = 100;
        }

        $lessons = CourseLesson::whereIn('id', $targetIds)
            ->orderBy('order')
            ->get();

        $allLabs = Lab::where('is_active', 1)->get();
        $labsByChapter = $allLabs->groupBy('chapter_id');

        $quizScores = QuizAttempt::where('user_id', $userId)
            ->whereNotNull('completed_at')
            ->select('chapter_id', 'score')
            ->get()
            ->groupBy('chapter_id')
            ->map(fn ($rows) => $rows->max('score'))
            ->toArray();

        $activeQuizSessions = QuizAttempt::where('user_id', $userId)
            ->whereNull('completed_at')
            ->pluck('id', 'chapter_id')
            ->toArray();

        $completedLabs = LabHistory::where('user_id', $userId)
            ->join('labs', 'lab_histories.lab_id', '=', 'labs.id')
            ->select('labs.slug', 'lab_histories.final_score')
            ->get()
            ->groupBy('slug')
            ->map(fn ($rows) => $rows->max('final_score'))
            ->toArray();

        $activeLabSessions = LabSession::where('user_id', $userId)
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->with('lab')
            ->get()
            ->mapWithKeys(fn ($s) => [$s->lab->slug => $s->id])
            ->toArray();

        return view($view, [
            'course' => $course,
            'lessons' => $lessons,
            'progressPercent' => $progressPercent,
            'isCurrentCompleted' => ($progressPercent === 100),
            'completedLessonIds' => $completedIds,
            'currentLessonIds' => $targetIds,
            'activityCompleted' => $activityDone,
            'completedLessonsMap' => $statusMap,
            'currentKey' => $currentKey,
            'currentActivityId' => $activityId,

            'labsByChapter' => $labsByChapter,
            'completedLabs' => $completedLabs,
            'activeSessions' => $activeLabSessions,

            'quizScores' => $quizScores,
            'activeQuizSessions' => $activeQuizSessions,
        ]);
    }

    /**
     * Shortcut pemanggil subbab berdasarkan mapping.
     */
    private function loadSubchapter(string $key)
    {
        $meta = $this->lessonMap[$key];
        return $this->loadView($meta['view'], $key, $meta['range'], $meta['activity'], $meta['required']);
    }

    // --- Route Handlers Bab 1 ---
    public function tailwind() { return $this->loadSubchapter('1.1'); }
    public function subbabTailwindCss() { return $this->loadSubchapter('1.2'); }
    public function background() { return $this->loadSubchapter('1.3'); }
    public function implementation() { return $this->loadSubchapter('1.4'); }
    public function advantages() { return $this->loadSubchapter('1.5'); }
    public function installation() { return redirect()->route('courses.implementation'); }

    // --- Route Handlers Bab 2 ---
    public function layoutBasics() { return $this->loadSubchapter('2.1'); }
    public function layoutSpacing() { return $this->loadSubchapter('2.1'); }
    public function flexbox() { return $this->loadSubchapter('2.2'); }
    public function grid() { return $this->loadSubchapter('2.3'); }
    public function layoutMgmt() { return redirect()->route('courses.grid'); }
    public function responsive() { return $this->loadSubchapter('2.4'); }

    // --- Route Handlers Bab 3 ---
    public function typography() { return $this->loadSubchapter('3.1'); }
    public function backgrounds() { return $this->loadSubchapter('3.2'); }
    public function borders() { return $this->loadSubchapter('3.3'); }
    public function effects() { return $this->loadSubchapter('3.4'); }

    private function activityIdForLesson(int $lessonId): ?int
    {
        return [
            5 => 1,
            10 => 2,
            15 => 3,
            20 => 4,
            25 => 5,
            30 => 6,
            35 => 7,
            40 => 8,
            45 => 9,
            50 => 10,
            55 => 11,
            60 => 12,
            65 => 13,
        ][$lessonId] ?? null;
    }

    /**
     * Menyimpan progress bacaan materi dan progress aktivitas.
     * Catatan: activity lesson ID hanya dikirim oleh frontend setelah aktivitas valid,
     * karena section aktivitas tidak disimpan oleh observer scroll.
     */
    public function completeLesson(Request $request)
    {
        $data = $request->validate([
            'lesson_id' => 'required|integer|exists:course_lessons,id',
        ]);

        $userId = Auth::id();
        $lessonId = (int) $data['lesson_id'];

        DB::table('user_lesson_progress')->updateOrInsert(
            [
                'user_id' => $userId,
                'course_lesson_id' => $lessonId,
            ],
            [
                'completed' => 1,
                'updated_at' => now(),
            ]
        );

        if ($activityId = $this->activityIdForLesson($lessonId)) {
            UserActivityProgress::updateOrCreate(
                [
                    'user_id' => $userId,
                    'course_activity_id' => $activityId,
                ],
                [
                    'completed' => true,
                    'score' => 100,
                    'completed_at' => now(),
                ]
            );
        }

        return response()->json([
            'status' => 'ok',
            'message' => 'Progress berhasil disimpan.',
            'lesson_id' => $lessonId,
            'activity_id' => $this->activityIdForLesson($lessonId),
        ]);
    }

    /**
     * Menampilkan halaman kurikulum.
     */
    public function showSyllabus()
    {
        $userId = Auth::id();
        $statusMap = $this->getChapterStatus($userId);

        $passedLabsMap = LabHistory::where('user_id', $userId)
            ->where('status', 'passed')
            ->pluck('lab_id')
            ->flip()
            ->toArray();

        $activeLabIds = Lab::where('is_active', 1)->pluck('id')->map(fn ($id) => (int) $id)->toArray();
        $totalSteps = count($this->lessonMap) + count($activeLabIds) + 3;

        $completedSubchapters = count(array_filter(
            $statusMap,
            fn ($value, $key) => $value === true && !str_starts_with($key, 'quiz_'),
            ARRAY_FILTER_USE_BOTH
        ));

        $completedLabs = count(array_intersect(array_keys($passedLabsMap), $activeLabIds));

        $completedQuizzes = count(array_filter(
            $statusMap,
            fn ($value, $key) => $value === true && str_starts_with($key, 'quiz_'),
            ARRAY_FILTER_USE_BOTH
        ));

        $totalCompleted = $completedSubchapters + $completedLabs + $completedQuizzes;
        $progressPercent = $totalSteps > 0 ? min(100, round(($totalCompleted / $totalSteps) * 100)) : 0;

        return view('courses.curriculum', [
            'completedLessonsMap' => $statusMap,
            'passedLabsMap' => $passedLabsMap,
            'progressPercent' => $progressPercent,
        ]);
    }
}
