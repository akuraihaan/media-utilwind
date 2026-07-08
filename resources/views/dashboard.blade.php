@extends('layouts.landing')

@section('title', 'Dasbor Akademik')

@section('content')

{{-- ==============================================================================
     LOGIKA QUERY (Data Presisi & Waktu Jelas)
     ============================================================================== --}}
@php
    $userId = Auth::id();

    /*
    |--------------------------------------------------------------------------
    | DATA VALID DASHBOARD AKADEMIK
    |--------------------------------------------------------------------------
    | Variabel tetap menerima data dari controller. Jika controller belum mengirimkan
    | data, Blade ini mengambil fallback langsung dari model/database yang digunakan
    | pada project Utilwind.
    */

    $totalLessons = $totalLessons ?? 0;
    $lessonsCompleted = $lessonsCompleted ?? 0;
    $totalLabs = $totalLabs ?? 0;
    $labsCompleted = $labsCompleted ?? 0;
    $quizzesCompleted = $quizzesCompleted ?? 0;
    $quizAverage = $quizAverage ?? 0;
    $chaptersPassed = $chaptersPassed ?? 0;
    $chartData = $chartData ?? ['labels' => [], 'scores' => []];
    $activeSession = $activeSession ?? null;

    $allQuizzes = collect();
    $allLabs = collect();
    $allLessons = collect();
    $courseLessonCatalog = collect();
    $lessonProgressRecords = collect();
    $lessonInsightItems = collect();
    $completedLessonIds = collect();

    // Katalog materi dari course_lessons agar materi yang belum dibaca tetap muncul sebagai placeholder.
    try {
        if (class_exists(\App\Models\CourseLesson::class) && \Illuminate\Support\Facades\Schema::hasTable('course_lessons')) {
            $courseLessonCatalog = \App\Models\CourseLesson::query()
                ->orderBy('order')
                ->orderBy('id')
                ->get();

            if ((!isset($totalLessons) || (int) $totalLessons === 0)) {
                $totalLessons = $courseLessonCatalog->count();
            }
        } elseif (class_exists(\App\Models\Lesson::class) && \Illuminate\Support\Facades\Schema::hasTable('lessons')) {
            $courseLessonCatalog = \App\Models\Lesson::query()
                ->orderBy('order')
                ->orderBy('id')
                ->get();

            if ((!isset($totalLessons) || (int) $totalLessons === 0)) {
                $totalLessons = $courseLessonCatalog->count();
            }
        }
    } catch (\Throwable $e) {
        $totalLessons = $totalLessons ?? 0;
        $courseLessonCatalog = collect();
    }

    // Total lab dari tabel labs jika controller belum mengirimkan.
    try {
        if ((!isset($totalLabs) || (int) $totalLabs === 0) && class_exists(\App\Models\Lab::class)) {
            $totalLabs = \App\Models\Lab::count();
        }
    } catch (\Throwable $e) {
        $totalLabs = $totalLabs ?? 0;
    }

    // Materi selesai dari user_lesson_progress.
    try {
        if (class_exists(\App\Models\UserLessonProgress::class)) {
            $lessonProgressQuery = \App\Models\UserLessonProgress::where('user_id', $userId)
                ->where('completed', true);

            if ((!isset($lessonsCompleted) || (int) $lessonsCompleted === 0)) {
                $lessonsCompleted = (clone $lessonProgressQuery)->count();
            }

            $lessonProgressRecords = (clone $lessonProgressQuery)
                ->with('lesson')
                ->get();

            $completedLessonIds = $lessonProgressRecords
                ->pluck('course_lesson_id')
                ->map(fn ($id) => (int) $id)
                ->unique()
                ->values();

            $allLessons = $lessonProgressRecords
                ->sortBy(fn($m) => $m->lesson->order ?? $m->course_lesson_id)
                ->values()
                ->map(function ($m) {
                    $urutan = $m->lesson->order ?? $m->course_lesson_id;

                    return [
                        'name' => 'Materi Bacaan: ' . ($m->lesson->title ?? 'Modul ' . $urutan),
                        'type' => 'materi',
                        'score' => null,
                        'date' => $m->updated_at,
                        'full_date' => \Carbon\Carbon::parse($m->updated_at)->format('d M Y, H:i'),
                        'time' => \Carbon\Carbon::parse($m->updated_at)->diffForHumans(),
                        'status' => 'Tuntas',
                        'badge_number' => str_pad($urutan, 2, '0', STR_PAD_LEFT),
                        'raw_order' => $urutan,
                        'lesson_id' => (int) $m->course_lesson_id,
                    ];
                });
        }
    } catch (\Throwable $e) {
        $allLessons = collect();
        $lessonProgressRecords = collect();
        $completedLessonIds = collect();
    }

    $lessonProgressById = $lessonProgressRecords->keyBy(fn ($row) => (int) ($row->course_lesson_id ?? 0));
    $lessonInsightItems = $courseLessonCatalog
        ->map(function ($lesson) use ($lessonProgressById) {
            $lessonId = (int) ($lesson->id ?? 0);
            $progress = $lessonProgressById->get($lessonId);
            $order = $lesson->order ?? $lessonId;
            $isCompleted = (bool) $progress;

            return [
                'id' => $lessonId,
                'title' => $lesson->title ?? 'Materi ' . $order,
                'badge_number' => str_pad((string) $order, 2, '0', STR_PAD_LEFT),
                'completed' => $isCompleted,
                'status' => $isCompleted ? 'Tuntas' : 'Belum dibaca',
                'time' => $progress?->updated_at ? \Carbon\Carbon::parse($progress->updated_at)->diffForHumans() : 'Belum ada waktu baca',
                'full_date' => $progress?->updated_at ? \Carbon\Carbon::parse($progress->updated_at)->format('d M Y, H:i') : '-',
            ];
        })
        ->values();

    if ($lessonInsightItems->isEmpty() && $allLessons->isNotEmpty()) {
        $lessonInsightItems = $allLessons
            ->map(fn ($lessonLog) => [
                'id' => $lessonLog['lesson_id'] ?? $lessonLog['raw_order'] ?? null,
                'title' => str_replace('Materi Bacaan: ', '', $lessonLog['name'] ?? 'Materi'),
                'badge_number' => $lessonLog['badge_number'] ?? '-',
                'completed' => true,
                'status' => 'Tuntas',
                'time' => $lessonLog['time'] ?? '-',
                'full_date' => $lessonLog['full_date'] ?? '-',
            ])
            ->values();
    }

    $unreadLessonsCount = $lessonInsightItems->where('completed', false)->count();

    // Data kuis valid.
    try {
        if (class_exists(\App\Models\QuizAttempt::class)) {
            $quizQuery = \App\Models\QuizAttempt::where('user_id', $userId)
                ->whereNotNull('completed_at');

            if ((!isset($quizzesCompleted) || (int) $quizzesCompleted === 0)) {
                $quizzesCompleted = (clone $quizQuery)->count();
            }

            if ((!isset($quizAverage) || (float) $quizAverage === 0.0)) {
                $quizAverage = round((clone $quizQuery)->avg('score') ?? 0, 1);
            }

            if ((!isset($chaptersPassed) || (int) $chaptersPassed === 0)) {
                $chaptersPassed = (clone $quizQuery)
                    ->where('score', '>=', 70)
                    ->distinct('chapter_id')
                    ->count('chapter_id');
            }

            $allQuizzes = (clone $quizQuery)
                ->latest('completed_at')
                ->get()
                ->map(fn($q) => [
                    'id' => $q->id,
                    'name' => $q->chapter_id == 99 ? 'Evaluasi Akhir' : 'Evaluasi Teori: Bab ' . $q->chapter_id,
                    'type' => 'kuis',
                    'score' => $q->score,
                    'chapter_id' => $q->chapter_id,
                    'date' => $q->completed_at,
                    'full_date' => \Carbon\Carbon::parse($q->completed_at)->format('d M Y, H:i'),
                    'time' => \Carbon\Carbon::parse($q->completed_at)->diffForHumans(),
                    'status' => $q->score >= 70 ? 'Lulus' : 'Belum Lulus',
                    'duration' => (int) ($q->time_spent_seconds ?? 0),
                    'duration_text' => gmdate(((int) ($q->time_spent_seconds ?? 0)) >= 3600 ? 'H:i:s' : 'i:s', max(0, (int) ($q->time_spent_seconds ?? 0))),
                    'answered_count' => (int) ($q->answered_count ?? 0),
                    'unanswered_count' => (int) ($q->unanswered_count ?? 0),
                    'flagged_count' => (int) ($q->flagged_count ?? 0),
                    'focus_lost_count' => (int) ($q->focus_lost_count ?? 0),
                    'feedback_level' => $q->score >= 70 ? 'Lulus' : 'Belum Lulus',
                    'feedback_message' => $q->score >= 70 ? 'Evaluasi telah diselesaikan. Lihat rincian jawaban untuk melihat hasil setiap soal.' : 'Evaluasi telah diselesaikan. Nilai belum mencapai KKM. Lihat rincian jawaban untuk melihat hasil setiap soal.',
                    'reflection_note' => $q->reflection_note ?? null,
                    'review_url' => route('quiz.result', $q->id),
                ]);

            // Chart mengambil nilai terbaik per bab agar data tidak bias karena percobaan ulang.
            if (empty($chartData['scores'])) {
                $bestByChapter = (clone $quizQuery)
                    ->select('chapter_id', \Illuminate\Support\Facades\DB::raw('MAX(score) as best_score'), \Illuminate\Support\Facades\DB::raw('MAX(completed_at) as last_completed_at'))
                    ->groupBy('chapter_id')
                    ->orderBy('chapter_id')
                    ->get();

                $chartData = [
                    'labels' => $bestByChapter
                        ->map(fn($row) => $row->chapter_id == 99 ? 'Evaluasi' : 'Bab ' . $row->chapter_id)
                        ->values()
                        ->toArray(),
                    'scores' => $bestByChapter
                        ->pluck('best_score')
                        ->map(fn($score) => round($score, 1))
                        ->values()
                        ->toArray(),
                    'dates' => $bestByChapter
                        ->map(fn($row) => $row->last_completed_at ? \Carbon\Carbon::parse($row->last_completed_at)->format('d M Y, H:i') : '-')
                        ->values()
                        ->toArray(),
                ];
            }
        }
    } catch (\Throwable $e) {
        $allQuizzes = collect();
        $chartData = $chartData ?? ['labels' => [], 'scores' => []];
    }

    // Data lab valid.
    try {
        if (class_exists(\App\Models\LabHistory::class)) {
            $labHistoryQuery = \App\Models\LabHistory::where('user_id', $userId)
                ->whereIn('status', ['passed', 'failed', 'completed']);

            if ((!isset($labsCompleted) || (int) $labsCompleted === 0)) {
                $labsCompleted = (clone $labHistoryQuery)
                    ->where(function ($query) {
                        $query->where('status', 'passed')
                              ->orWhere('final_score', '>=', 70);
                    })
                    ->distinct('lab_id')
                    ->count('lab_id');
            }

            $allLabs = (clone $labHistoryQuery)
                ->with(['lab.steps'])
                ->latest('updated_at')
                ->get()
                ->map(function ($l) {
                    $completedSteps = collect(json_decode($l->completed_steps ?? '[]', true) ?: [])->count();
                    $totalSteps = $l->lab?->steps?->count() ?? 0;
                    $passingGrade = (int) ($l->lab->passing_grade ?? 70);
                    $score = (int) ($l->final_score ?? 0);
                    $durationSeconds = (int) ($l->duration_seconds ?? 0);

                    return [
                        'id' => $l->id,
                        'name' => 'Praktik: ' . ($l->lab->title ?? 'Modul ' . $l->lab_id),
                        'type' => 'lab',
                        'score' => $score,
                        'lab_id' => $l->lab_id,
                        'chapter_id' => (int) ($l->lab->chapter_id ?? $l->lab_id ?? 1),
                        'date' => $l->updated_at,
                        'full_date' => \Carbon\Carbon::parse($l->updated_at)->format('d M Y, H:i'),
                        'time' => \Carbon\Carbon::parse($l->updated_at)->diffForHumans(),
                        'status' => ($l->status === 'passed' || $score >= $passingGrade) ? 'Lulus' : 'Belum Lulus',
                        'duration' => $durationSeconds,
                        'duration_text' => gmdate($durationSeconds >= 3600 ? 'H:i:s' : 'i:s', max(0, $durationSeconds)),
                        'completed_steps' => $completedSteps,
                        'total_steps' => $totalSteps,
                        'completion_percent' => $totalSteps > 0 ? round(($completedSteps / $totalSteps) * 100) : min(100, max(0, $score)),
                        'passing_grade' => $passingGrade,
                        'feedback_level' => $score >= $passingGrade ? 'Lulus' : 'Belum Lulus',
                        'feedback_message' => $score >= $passingGrade
                            ? 'Praktik telah diselesaikan. Lihat detail tugas dan kode akhir.'
                            : 'Praktik telah diselesaikan. Nilai belum mencapai batas lulus. Lihat detail tugas dan kode akhir.',
                        'review_url' => route('lab.result', $l->id),
                    ];
                });
        }
    } catch (\Throwable $e) {
        $allLabs = collect();
    }

    $labCatalog = collect();
    try {
        if (class_exists(\App\Models\Lab::class) && \Illuminate\Support\Facades\Schema::hasTable('labs')) {
            $labCatalog = \App\Models\Lab::query()
                ->withCount('steps')
                ->orderBy('chapter_id')
                ->orderBy('id')
                ->get();

            if ((!isset($totalLabs) || (int) $totalLabs === 0)) {
                $totalLabs = $labCatalog->count();
            }
        }
    } catch (\Throwable $e) {
        $labCatalog = collect();
    }

    $bestLabById = $allLabs
        ->groupBy('lab_id')
        ->map(fn ($rows) => $rows->sortByDesc('score')->sortByDesc('date')->first());

    $labInsightItems = $labCatalog
        ->map(function ($lab) use ($bestLabById) {
            $best = $bestLabById->get($lab->id);
            $passingGrade = (int) ($lab->passing_grade ?? 70);
            $score = $best['score'] ?? null;
            $isPassed = $score !== null && $score >= $passingGrade;

            return [
                'id' => (int) $lab->id,
                'title' => $lab->title ?? 'Praktik ' . $lab->id,
                'chapter' => $lab->chapter_id ? 'Bab ' . $lab->chapter_id : 'Praktik',
                'score' => $score,
                'passing_grade' => $passingGrade,
                'status' => $isPassed ? 'Lulus' : ($score !== null ? 'Belum Lulus' : 'Belum dikerjakan'),
                'completed' => $isPassed,
                'steps' => (int) ($lab->steps_count ?? 0),
                'duration' => $best['duration_text'] ?? '-',
                'time' => $best['time'] ?? 'Belum ada riwayat',
                'completion_percent' => $best['completion_percent'] ?? 0,
            ];
        })
        ->values();

    if ($labInsightItems->isEmpty() && $allLabs->isNotEmpty()) {
        $labInsightItems = $allLabs
            ->map(fn ($labLog) => [
                'id' => $labLog['lab_id'] ?? null,
                'title' => str_replace('Praktik: ', '', $labLog['name'] ?? 'Praktik'),
                'chapter' => isset($labLog['chapter_id']) ? 'Bab ' . $labLog['chapter_id'] : 'Praktik',
                'score' => $labLog['score'] ?? null,
                'passing_grade' => $labLog['passing_grade'] ?? 70,
                'status' => $labLog['status'] ?? 'Belum Lulus',
                'completed' => ($labLog['score'] ?? 0) >= ($labLog['passing_grade'] ?? 70),
                'steps' => $labLog['total_steps'] ?? 0,
                'duration' => $labLog['duration_text'] ?? '-',
                'time' => $labLog['time'] ?? '-',
                'completion_percent' => $labLog['completion_percent'] ?? 0,
            ])
            ->values();
    }

    $uncompletedLabsCount = $labInsightItems->where('completed', false)->count();

    $quizInsightItems = $allQuizzes
        ->groupBy('chapter_id')
        ->map(function ($attempts, $chapterId) {
            $best = $attempts->sortByDesc('score')->sortByDesc('date')->first();
            $latest = $attempts->sortByDesc('date')->first();
            $scoreValues = $attempts->pluck('score')->filter(fn ($score) => $score !== null);

            return [
                'chapter_id' => (int) $chapterId,
                'label' => ((int) $chapterId === 99) ? 'Evaluasi Akhir' : 'Bab ' . $chapterId,
                'best_score' => $best['score'] ?? null,
                'latest_score' => $latest['score'] ?? null,
                'average_score' => $scoreValues->isNotEmpty() ? round($scoreValues->avg(), 1) : null,
                'attempts' => $attempts->count(),
                'status' => (($best['score'] ?? 0) >= 70) ? 'Lulus' : 'Belum Lulus',
                'time' => $latest['time'] ?? '-',
                'duration' => $latest['duration_text'] ?? '-',
                'focus_lost_count' => (int) ($attempts->sum('focus_lost_count') ?? 0),
            ];
        })
        ->sortBy(fn ($item) => $item['chapter_id'] === 99 ? 999 : $item['chapter_id'])
        ->values();

    $chapterInsightKeys = $allQuizzes
        ->pluck('chapter_id')
        ->merge($allLabs->pluck('chapter_id'))
        ->filter(fn ($key) => $key !== null && $key !== '')
        ->unique()
        ->sortBy(fn ($key) => is_numeric($key) ? (int) $key : 999)
        ->values();

    $chapterInsightItems = $chapterInsightKeys
        ->map(function ($chapterId) use ($allQuizzes, $allLabs) {
            $chapterQuizzes = $allQuizzes->where('chapter_id', $chapterId);
            $chapterLabs = $allLabs->where('chapter_id', (int) $chapterId);
            $bestQuiz = $chapterQuizzes->pluck('score')->filter(fn ($score) => $score !== null)->max();
            $bestLab = $chapterLabs->pluck('score')->filter(fn ($score) => $score !== null)->max();
            $latestRows = collect($chapterQuizzes)->merge($chapterLabs)->sortByDesc('date');
            $isPassed = $bestQuiz !== null && $bestQuiz >= 70;

            return [
                'chapter_id' => (int) $chapterId,
                'label' => ((int) $chapterId === 99) ? 'Evaluasi Akhir' : 'Bab ' . $chapterId,
                'quiz_score' => $bestQuiz !== null ? round($bestQuiz, 1) : null,
                'lab_score' => $bestLab !== null ? round($bestLab, 1) : null,
                'quiz_attempts' => $chapterQuizzes->count(),
                'lab_attempts' => $chapterLabs->count(),
                'status' => $isPassed ? 'Lulus' : 'Belum Lulus',
                'completed' => $isPassed,
                'time' => $latestRows->first()['time'] ?? '-',
            ];
        })
        ->values();

    $chaptersNotPassed = $chapterInsightItems->where('completed', false)->count();

    $totalTasks = ($totalLessons ?? 0) + ($totalLabs ?? 0);
    $completedTasks = ($lessonsCompleted ?? 0) + ($labsCompleted ?? 0);
    $overallProgress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

    $pctLesson = ($totalLessons > 0) ? round(($lessonsCompleted / $totalLessons) * 100) : 0;
    $pctLab = ($totalLabs > 0) ? round(($labsCompleted / $totalLabs) * 100) : 0;

    $historyCombined = collect($allQuizzes)->merge($allLabs)->sortByDesc('date')->values();
    $liveLogData = collect($allLessons)->merge($allQuizzes)->merge($allLabs)->sortByDesc('date')->values()->take(30);

    $chartScores = collect($chartData['scores'] ?? []);
    $chartLabels = collect($chartData['labels'] ?? []);

    $chartSummary = [
        'attempts' => $allQuizzes->count(),
        'best' => $chartScores->count() ? $chartScores->max() : 0,
        'average' => $chartScores->count() ? round($chartScores->avg(), 1) : round($quizAverage ?? 0, 1),
        'passed' => $allQuizzes->where('score', '>=', 70)->count(),
        'below_passing' => $allQuizzes->where('score', '<', 70)->count(),
    ];

    /*
    |--------------------------------------------------------------------------
    | RINGKASAN AKTIVITAS
    |--------------------------------------------------------------------------
    | Dasbor menampilkan data materi, praktik, dan evaluasi secara ringkas.
    | Tidak ada rekomendasi otomatis pada halaman ini.
    */



    /*
    |--------------------------------------------------------------------------
    | DATA GRAFIK VALID: NILAI KUIS + NILAI PRAKTIK
    |--------------------------------------------------------------------------
    | - Kuis memakai nilai terbaik per chapter_id.
    | - Praktik memakai nilai terbaik per chapter_id jika tersedia; jika tidak, fallback ke lab_id.
    | - Data kosong memakai null, bukan 0, supaya grafik tidak menampilkan nilai palsu.
    */
    $quizRawForAssessmentChart = collect();
    $labRawForAssessmentChart = collect();

    try {
        if (class_exists(\App\Models\QuizAttempt::class)) {
            $quizRawForAssessmentChart = \App\Models\QuizAttempt::where('user_id', $userId)
                ->whereNotNull('score')
                ->whereNotNull('completed_at')
                ->get();
        }
    } catch (\Throwable $e) {
        $quizRawForAssessmentChart = collect();
    }

    try {
        if (class_exists(\App\Models\LabHistory::class)) {
            $labRawForAssessmentChart = \App\Models\LabHistory::where('user_id', $userId)
                ->whereNotNull('final_score')
                ->whereIn('status', ['passed', 'failed', 'completed'])
                ->with('lab')
                ->get();
        }
    } catch (\Throwable $e) {
        $labRawForAssessmentChart = collect();
    }

    $quizBestByChapter = $quizRawForAssessmentChart
        ->filter(fn ($item) => $item->chapter_id !== null)
        ->groupBy('chapter_id')
        ->map(function ($items) {
            $best = $items
                ->sortByDesc('score')
                ->sortByDesc('completed_at')
                ->first();

            return [
                'score' => $best ? round((float) $best->score, 1) : null,
                'date' => $best?->completed_at,
                'label' => ($best?->chapter_id == 99) ? 'Evaluasi' : 'Bab ' . $best?->chapter_id,
            ];
        });

    $labBestByChapter = $labRawForAssessmentChart
        ->filter(fn ($item) => $item->lab_id !== null)
        ->groupBy(function ($item) {
            return $item->lab->chapter_id ?? $item->lab_id;
        })
        ->map(function ($items) {
            $best = $items
                ->sortByDesc('final_score')
                ->sortByDesc('updated_at')
                ->first();

            $chapterKey = $best->lab->chapter_id ?? $best->lab_id;

            return [
                'score' => $best ? round((float) $best->final_score, 1) : null,
                'date' => $best?->updated_at,
                'label' => is_numeric($chapterKey) ? 'Bab ' . $chapterKey : 'Praktik',
                'title' => $best->lab->title ?? 'Praktik',
            ];
        });

    $assessmentKeys = collect($quizBestByChapter->keys())
        ->merge($labBestByChapter->keys())
        ->filter(fn ($key) => $key !== null && $key !== '')
        ->unique()
        ->sortBy(fn ($key) => is_numeric($key) ? (int) $key : 999)
        ->values();

    $performanceLabels = $assessmentKeys
        ->map(function ($key) use ($quizBestByChapter, $labBestByChapter) {
            if (isset($quizBestByChapter[$key]['label'])) {
                return $quizBestByChapter[$key]['label'];
            }

            if (isset($labBestByChapter[$key]['label'])) {
                return $labBestByChapter[$key]['label'];
            }

            return is_numeric($key) ? 'Bab ' . $key : (string) $key;
        })
        ->values();

    $quizScoreSeries = $assessmentKeys
        ->map(fn ($key) => $quizBestByChapter[$key]['score'] ?? null)
        ->values();

    $labScoreSeries = $assessmentKeys
        ->map(fn ($key) => $labBestByChapter[$key]['score'] ?? null)
        ->values();

    $validQuizScores = $quizScoreSeries->filter(fn ($score) => $score !== null);
    $validLabScores = $labScoreSeries->filter(fn ($score) => $score !== null);

    $quizChartAverage = $validQuizScores->count() > 0 ? round($validQuizScores->avg(), 1) : null;
    $labChartAverage = $validLabScores->count() > 0 ? round($validLabScores->avg(), 1) : null;
    $quizChartHighest = $validQuizScores->count() > 0 ? round($validQuizScores->max(), 1) : null;
    $labChartHighest = $validLabScores->count() > 0 ? round($validLabScores->max(), 1) : null;
    $quizChartLatest = $validQuizScores->count() > 0 ? $validQuizScores->last() : null;
    $labChartLatest = $validLabScores->count() > 0 ? $validLabScores->last() : null;

    $hasAssessmentChartData = $validQuizScores->count() > 0 || $validLabScores->count() > 0;

    if (!$hasAssessmentChartData) {
        $performanceLabels = collect(['Belum Ada Data']);
        $quizScoreSeries = collect([null]);
        $labScoreSeries = collect([null]);
    }

    $assessmentAveragePool = collect([$quizChartAverage, $labChartAverage])->filter(fn ($score) => $score !== null);
    $combinedAssessmentAverage = $assessmentAveragePool->isNotEmpty() ? round($assessmentAveragePool->avg(), 1) : null;
    $lowestAssessmentFocus = $assessmentKeys
        ->map(function ($key, $index) use ($quizBestByChapter, $labBestByChapter, $performanceLabels) {
            $chapterScores = collect([
                $quizBestByChapter[$key]['score'] ?? null,
                $labBestByChapter[$key]['score'] ?? null,
            ])->filter(fn ($score) => $score !== null);

            if ($chapterScores->isEmpty()) {
                return null;
            }

            return [
                'label' => $performanceLabels[$index] ?? (is_numeric($key) ? 'Bab ' . $key : (string) $key),
                'score' => round($chapterScores->avg(), 1),
            ];
        })
        ->filter()
        ->sortBy('score')
        ->first();
    $weakestLearningLabel = $lowestAssessmentFocus['label'] ?? 'Belum ada data';
    $weakestLearningScore = $lowestAssessmentFocus['score'] ?? null;
    $weakestLearningPercent = $weakestLearningScore !== null ? min(100, max(0, (int) round($weakestLearningScore))) : 0;
    $weakestLearningStatus = $weakestLearningScore === null
        ? 'Data belum tersedia'
        : ($weakestLearningScore >= 70 ? 'Terendah, tetap tuntas' : 'Perlu diulang');
    $analyticsAttemptCount = $allLabs->count() + $allQuizzes->count();
    $analyticsActivityCount = $allLessons->count() + $allLabs->count() + $allQuizzes->count();
    $totalFocusLost = (int) $allQuizzes->sum('focus_lost_count');
    $totalLearningDurationSeconds = (int) $allQuizzes->sum('duration') + (int) $allLabs->sum('duration');

    $formatLearningDuration = function (int $seconds): string {
        if ($seconds <= 0) {
            return '-';
        }

        $hours = intdiv($seconds, 3600);
        $minutes = intdiv($seconds % 3600, 60);
        $remainingSeconds = $seconds % 60;

        if ($hours > 0) {
            return $hours . ' jam' . ($minutes > 0 ? ' ' . $minutes . ' mnt' : '');
        }

        if ($minutes > 0) {
            return $minutes . ' mnt' . ($remainingSeconds > 0 ? ' ' . $remainingSeconds . ' dtk' : '');
        }

        return $remainingSeconds . ' dtk';
    };

    $totalLearningDurationText = $formatLearningDuration($totalLearningDurationSeconds);

    $analyticsSourceCount = collect([
        $allLessons->isNotEmpty(),
        $allLabs->isNotEmpty(),
        $allQuizzes->isNotEmpty(),
    ])->filter()->count();
    $analyticsCoveragePercent = round(($analyticsSourceCount / 3) * 100);

    if ($overallProgress >= 100) {
        $learningCompletionStatus = 'Selesai';
        $learningCompletionHint = 'Materi dan praktik yang tersedia telah diselesaikan.';
        $learningCompletionTone = 'emerald';
    } elseif ($analyticsActivityCount > 0) {
        $learningCompletionStatus = 'Berjalan';
        $learningCompletionHint = 'Data materi, praktik, atau evaluasi sudah tersedia.';
        $learningCompletionTone = 'cyan';
    } else {
        $learningCompletionStatus = 'Belum Ada Aktivitas';
        $learningCompletionHint = 'Data akan muncul setelah materi, praktik, atau evaluasi dikerjakan.';
        $learningCompletionTone = 'slate';
    }

    $latestActivity = $liveLogData->first();
    $latestActivityType = strtolower((string) ($latestActivity['type'] ?? ''));
    $latestActivityLabel = match ($latestActivityType) {
        'materi' => 'Materi terakhir',
        'lab' => 'Praktik terakhir',
        'kuis', 'quiz' => 'Evaluasi terakhir',
        default => 'Aktivitas terakhir',
    };
    $latestActivityName = str_replace(['Materi Bacaan: ', 'Praktik Lab: ', 'Praktik: ', 'Evaluasi Teori: '], '', $latestActivity['name'] ?? 'Belum ada aktivitas');
    $latestActivityTime = $latestActivity['time'] ?? 'Belum ada aktivitas';
    $latestActivityTone = match ($latestActivityType) {
        'materi' => 'fuchsia',
        'lab' => 'blue',
        'kuis', 'quiz' => 'cyan',
        default => 'slate',
    };
    $latestActivityStatus = $latestActivity['status'] ?? '-';
    $latestActivityScore = $latestActivity['score'] ?? null;
    $latestActivityDate = $latestActivity['full_date'] ?? $latestActivityTime;
    $latestActivityInsight = match ($latestActivityType) {
        'materi' => 'Aktivitas bacaan terakhir sudah tercatat.',
        'lab' => 'Praktik terakhir sudah tercatat.',
        'kuis', 'quiz' => 'Evaluasi terakhir sudah tercatat.',
        default => 'Belum ada aktivitas terbaru.',
    };

    $lessonRouteRanges = [
        ['from' => 1, 'to' => 5, 'routes' => ['courses.htmldancss']],
        ['from' => 6, 'to' => 10, 'routes' => ['courses.tailwindcss']],
        ['from' => 11, 'to' => 15, 'routes' => ['courses.latarbelakang']],
        ['from' => 16, 'to' => 20, 'routes' => ['courses.implementation', 'courses.implementasi']],
        ['from' => 21, 'to' => 25, 'routes' => ['courses.advantages', 'courses.keunggulan']],
        ['from' => 26, 'to' => 30, 'routes' => ['courses.layout-basics', 'courses.layout-spacing']],
        ['from' => 31, 'to' => 35, 'routes' => ['courses.flexbox']],
        ['from' => 36, 'to' => 40, 'routes' => ['courses.grid', 'courses.layout-mgmt']],
        ['from' => 41, 'to' => 45, 'routes' => ['courses.responsive']],
        ['from' => 46, 'to' => 50, 'routes' => ['courses.typography']],
        ['from' => 51, 'to' => 55, 'routes' => ['courses.background', 'courses.backgrounds']],
        ['from' => 56, 'to' => 60, 'routes' => ['courses.borders']],
        ['from' => 61, 'to' => 65, 'routes' => ['courses.effects']],
    ];

    $resolveRouteUrl = function ($routeNames) {
        foreach ((array) $routeNames as $routeName) {
            if (\Illuminate\Support\Facades\Route::has($routeName)) {
                return route($routeName);
            }
        }

        return route('courses.curriculum');
    };

    $nextLessonRouteForId = function (?int $lessonId) use ($lessonRouteRanges, $resolveRouteUrl) {
        if (!$lessonId) {
            return route('courses.curriculum');
        }

        foreach ($lessonRouteRanges as $routeRange) {
            if ($lessonId >= $routeRange['from'] && $lessonId <= $routeRange['to']) {
                return $resolveRouteUrl($routeRange['routes']);
            }
        }

        return route('courses.curriculum');
    };

    $nextUnreadLesson = $lessonInsightItems->firstWhere('completed', false);
    $nextUnreadLessonId = $nextUnreadLesson ? (int) ($nextUnreadLesson['id'] ?? 0) : null;
    $nextUnreadLessonTitle = $nextUnreadLesson['title'] ?? 'Tidak ada materi berikutnya';
    $nextUnreadLessonBadge = $nextUnreadLesson['badge_number'] ?? '-';
    $nextUnreadLessonUrl = $nextUnreadLesson ? $nextLessonRouteForId($nextUnreadLessonId) : route('courses.curriculum');
    $nextUnreadLessonAction = $nextUnreadLesson ? 'Buka Materi' : 'Buka Silabus';
    $nextUnreadLessonCardLabel = $nextUnreadLesson ? 'Lanjut: ' . $nextUnreadLessonTitle : 'Materi selesai';

    $activitySummaryItems = [
        [
            'label' => 'Materi',
            'value' => ($lessonsCompleted ?? 0) . '/' . ($totalLessons ?? 0),
            'detail' => 'selesai',
            'tone' => 'fuchsia',
        ],
        [
            'label' => 'Praktik',
            'value' => ($labsCompleted ?? 0) . '/' . ($totalLabs ?? 0),
            'detail' => 'selesai',
            'tone' => 'blue',
        ],
        [
            'label' => 'Evaluasi',
            'value' => $allQuizzes->count(),
            'detail' => 'selesai',
            'tone' => 'cyan',
        ],
    ];

    $analyticsOverviewItems = [
        [
            'label' => 'Progres Belajar',
            'value' => $overallProgress . '%',
            'hint' => $completedTasks . ' dari ' . $totalTasks . ' materi dan praktik selesai',
            'tone' => 'cyan',
        ],
        [
            'label' => 'Rata-rata Nilai',
            'value' => $combinedAssessmentAverage !== null ? $combinedAssessmentAverage . ' nilai' : '-',
            'hint' => 'Nilai terbaik kuis dan praktik',
            'tone' => $combinedAssessmentAverage === null ? 'slate' : ($combinedAssessmentAverage >= 70 ? 'emerald' : 'amber'),
        ],
        [
            'label' => 'Aktivitas Tercatat',
            'value' => $analyticsAttemptCount,
            'hint' => $allLabs->count() . ' praktik, ' . $allQuizzes->count() . ' evaluasi',
            'tone' => 'blue',
        ],
        [
            'label' => 'Waktu Belajar',
            'value' => $totalLearningDurationText,
            'hint' => 'Akumulasi praktik dan evaluasi',
            'tone' => 'indigo',
        ],
    ];

    $analyticsBreakdownItems = [
        [
            'label' => 'Materi',
            'value' => ($lessonsCompleted ?? 0) . '/' . ($totalLessons ?? 0),
            'percent' => $pctLesson,
            'hint' => 'Materi selesai',
            'tone' => 'fuchsia',
        ],
        [
            'label' => 'Praktik',
            'value' => ($labsCompleted ?? 0) . '/' . ($totalLabs ?? 0),
            'percent' => $pctLab,
            'hint' => 'Praktik selesai',
            'tone' => 'blue',
        ],
        [
            'label' => 'Kuis & Evaluasi',
            'value' => $combinedAssessmentAverage !== null ? $combinedAssessmentAverage . ' nilai' : '-',
            'percent' => $combinedAssessmentAverage !== null ? min(100, max(0, round($combinedAssessmentAverage))) : 0,
            'hint' => $allQuizzes->count() . ' percobaan, ' . $totalFocusLost . ' fokus terganggu',
            'tone' => 'cyan',
        ],
        [
            'label' => 'Sumber Data',
            'value' => $analyticsSourceCount . '/3 sumber',
            'percent' => $analyticsCoveragePercent,
            'hint' => 'Materi, praktik, evaluasi',
            'tone' => 'emerald',
        ],
    ];

    $learningInsightStats = [
        [
            'label' => 'Progres',
            'value' => $overallProgress . '%',
            'unit' => 'selesai',
            'detail' => number_format($completedTasks) . ' dari ' . number_format($totalTasks) . ' item',
            'tone' => 'cyan',
        ],
        [
            'label' => 'Nilai',
            'value' => $combinedAssessmentAverage !== null ? $combinedAssessmentAverage . ' nilai' : '-',
            'unit' => '',
            'detail' => number_format($analyticsAttemptCount) . ' kali praktik/evaluasi',
            'tone' => $combinedAssessmentAverage === null ? 'slate' : ($combinedAssessmentAverage >= 70 ? 'emerald' : 'amber'),
        ],
        [
            'label' => 'Aktivitas',
            'value' => number_format($analyticsActivityCount),
            'unit' => 'aktivitas',
            'detail' => number_format($allLessons->count()) . ' materi, ' . number_format($allLabs->count()) . ' praktik, ' . number_format($allQuizzes->count()) . ' evaluasi',
            'tone' => 'blue',
        ],
        [
            'label' => 'Durasi',
            'value' => $totalLearningDurationText,
            'unit' => 'tercatat',
            'detail' => 'akumulasi praktik dan evaluasi',
            'tone' => 'indigo',
        ],
    ];

    $learningInsightBars = [
        [
            'label' => 'Materi',
            'value' => ($lessonsCompleted ?? 0) . '/' . ($totalLessons ?? 0),
            'percent' => $pctLesson,
            'tone' => 'fuchsia',
        ],
        [
            'label' => 'Praktik',
            'value' => ($labsCompleted ?? 0) . '/' . ($totalLabs ?? 0),
            'percent' => $pctLab,
            'tone' => 'blue',
        ],
        [
            'label' => 'Kuis & Evaluasi',
            'value' => $combinedAssessmentAverage !== null ? $combinedAssessmentAverage . ' nilai' : '-',
            'percent' => $combinedAssessmentAverage !== null ? min(100, max(0, round($combinedAssessmentAverage))) : 0,
            'tone' => 'cyan',
        ],
        [
            'label' => 'Sumber Data',
            'value' => $analyticsSourceCount . '/3 sumber',
            'percent' => $analyticsCoveragePercent,
            'tone' => 'emerald',
        ],
    ];

    if ($analyticsActivityCount === 0) {
        $learningInsightTitle = 'Belum ada aktivitas belajar';
        $learningInsightBody = 'Belum ada aktivitas tercatat.';
        $learningInsightAction = 'Mulai dari materi pertama.';
        $learningInsightTone = 'slate';
    } elseif ($overallProgress >= 100 && ($combinedAssessmentAverage === null || $combinedAssessmentAverage >= 70)) {
        $learningInsightTitle = 'Progres belajar sudah tuntas';
        $learningInsightBody = 'Target belajar sudah selesai.';
        $learningInsightAction = 'Tinjau nilai per bab.';
        $learningInsightTone = 'emerald';
    } elseif ($combinedAssessmentAverage !== null && $combinedAssessmentAverage < 70) {
        $learningInsightTitle = 'Nilai masih perlu penguatan';
        $learningInsightBody = 'Nilai masih di bawah batas tuntas.';
        $learningInsightAction = 'Ulangi bab dengan nilai terendah.';
        $learningInsightTone = 'amber';
    } elseif ($pctLesson < 100 && $pctLesson <= $pctLab) {
        $learningInsightTitle = 'Materi bacaan perlu dilengkapi';
        $learningInsightBody = 'Materi belum lengkap.';
        $learningInsightAction = 'Lanjutkan materi tertinggal.';
        $learningInsightTone = 'fuchsia';
    } elseif ($pctLab < 100) {
        $learningInsightTitle = 'Praktik perlu dilanjutkan';
        $learningInsightBody = 'Praktik belum lengkap.';
        $learningInsightAction = 'Lanjutkan praktik berikutnya.';
        $learningInsightTone = 'blue';
    } else {
        $learningInsightTitle = 'Aktivitas belajar berjalan stabil';
        $learningInsightBody = 'Aktivitas belajar stabil.';
        $learningInsightAction = 'Pantau nilai dan riwayat.';
        $learningInsightTone = 'cyan';
    }

    $analyticsToneClasses = [
        'slate' => [
            'text' => 'text-slate-600 dark:text-slate-400',
            'soft' => 'bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-slate-400 border-slate-200 dark:border-white/10',
            'bar' => 'bg-slate-400',
            'dot' => 'bg-slate-400',
        ],
        'cyan' => [
            'text' => 'text-cyan-600 dark:text-cyan-400',
            'soft' => 'bg-cyan-50 dark:bg-cyan-500/10 text-cyan-700 dark:text-cyan-300 border-cyan-100 dark:border-cyan-500/20',
            'bar' => 'bg-cyan-500',
            'dot' => 'bg-cyan-500 shadow-[0_0_8px_#06b6d4]',
        ],
        'blue' => [
            'text' => 'text-blue-600 dark:text-blue-400',
            'soft' => 'bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-300 border-blue-100 dark:border-blue-500/20',
            'bar' => 'bg-blue-500',
            'dot' => 'bg-blue-500 shadow-[0_0_8px_#3b82f6]',
        ],
        'indigo' => [
            'text' => 'text-indigo-600 dark:text-indigo-400',
            'soft' => 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-700 dark:text-indigo-300 border-indigo-100 dark:border-indigo-500/20',
            'bar' => 'bg-indigo-500',
            'dot' => 'bg-indigo-500 shadow-[0_0_8px_#6366f1]',
        ],
        'fuchsia' => [
            'text' => 'text-fuchsia-600 dark:text-fuchsia-400',
            'soft' => 'bg-fuchsia-50 dark:bg-fuchsia-500/10 text-fuchsia-700 dark:text-fuchsia-300 border-fuchsia-100 dark:border-fuchsia-500/20',
            'bar' => 'bg-fuchsia-500',
            'dot' => 'bg-fuchsia-500 shadow-[0_0_8px_#d946ef]',
        ],
        'emerald' => [
            'text' => 'text-emerald-600 dark:text-emerald-400',
            'soft' => 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-300 border-emerald-100 dark:border-emerald-500/20',
            'bar' => 'bg-emerald-500',
            'dot' => 'bg-emerald-500 shadow-[0_0_8px_#10b981]',
        ],
        'amber' => [
            'text' => 'text-amber-600 dark:text-amber-400',
            'soft' => 'bg-amber-50 dark:bg-amber-500/10 text-amber-700 dark:text-amber-300 border-amber-100 dark:border-amber-500/20',
            'bar' => 'bg-amber-500',
            'dot' => 'bg-amber-500 shadow-[0_0_8px_#f59e0b]',
        ],
    ];

    $passingScoreTarget = 70;
    $completionTarget = 100;
    $scoreReferenceValue = $combinedAssessmentAverage !== null ? round((float) $combinedAssessmentAverage, 1) : null;
    $scoreGap = $scoreReferenceValue !== null ? round($scoreReferenceValue - $passingScoreTarget, 1) : null;

    $learnerReferenceCards = [
        [
            'label' => 'Progres belajar',
            'value' => number_format($overallProgress) . '%',
            'target' => 'Target ' . $completionTarget . '%',
            'caption' => number_format($completedTasks) . ' dari ' . number_format($totalTasks) . ' aktivitas selesai',
            'percent' => $overallProgress,
            'tone' => $overallProgress >= $completionTarget ? 'emerald' : ($overallProgress >= 70 ? 'cyan' : 'amber'),
        ],
        [
            'label' => 'Nilai evaluasi',
            'value' => $scoreReferenceValue !== null ? $scoreReferenceValue : '-',
            'target' => 'Batas tuntas ' . $passingScoreTarget,
            'caption' => $scoreGap === null ? 'Belum ada nilai kuis atau praktik' : ($scoreGap >= 0 ? 'Sudah di atas batas tuntas' : 'Kurang ' . abs($scoreGap) . ' poin dari batas tuntas'),
            'percent' => $scoreReferenceValue !== null ? min(100, max(0, round($scoreReferenceValue))) : 0,
            'tone' => $scoreReferenceValue === null ? 'slate' : ($scoreReferenceValue >= $passingScoreTarget ? 'emerald' : 'amber'),
        ],
        [
            'label' => 'Kelengkapan data',
            'value' => $analyticsSourceCount . '/3',
            'target' => 'Materi, praktik, kuis',
            'caption' => 'Sumber data yang sudah terbaca pada dasbor',
            'percent' => $analyticsCoveragePercent,
            'tone' => $analyticsCoveragePercent >= 100 ? 'emerald' : ($analyticsCoveragePercent >= 67 ? 'blue' : 'slate'),
        ],
    ];

    $learnerRecommendations = collect();

    if (empty(Auth::user()->class_group)) {
        $learnerRecommendations->push([
            'title' => 'Gabung kelas terlebih dahulu',
            'body' => 'Masukkan token kelas.',
            'tone' => 'amber',
        ]);
    }

    if (isset($activeSession) && $activeSession) {
        $learnerRecommendations->push([
            'title' => 'Lanjutkan praktik yang masih berjalan',
            'body' => ($activeSession->lab->title ?? 'Praktik') . ' belum selesai.',
            'tone' => 'indigo',
        ]);
    }

    if ($scoreReferenceValue !== null && $scoreReferenceValue < $passingScoreTarget) {
        $learnerRecommendations->push([
            'title' => 'Perkuat bab dengan nilai rendah',
            'body' => 'Ulangi bab dengan nilai terendah.',
            'tone' => 'amber',
        ]);
    }

    if ($pctLesson < $completionTarget) {
        $learnerRecommendations->push([
            'title' => 'Lengkapi materi yang belum selesai',
            'body' => 'Selesaikan materi tertinggal.',
            'tone' => 'fuchsia',
        ]);
    }

    if ($pctLab < $completionTarget && !empty(Auth::user()->class_group)) {
        $learnerRecommendations->push([
            'title' => 'Kerjakan praktik untuk menguji pemahaman',
            'body' => 'Lanjutkan praktik berikutnya.',
            'tone' => 'blue',
        ]);
    }

    if ($learnerRecommendations->isEmpty()) {
        $learnerRecommendations->push([
            'title' => 'Pertahankan ritme belajar',
            'body' => 'Pantau riwayat antar bab.',
            'tone' => 'emerald',
        ]);
    }

    $learnerRecommendations = $learnerRecommendations->take(3)->values();
    $primaryRecommendation = $learnerRecommendations->first();
    if ($nextUnreadLesson) {
        $nextStepTitle = 'Baca materi berikutnya';
        $nextStepBody = $nextUnreadLessonTitle;
        $nextStepUrl = $nextUnreadLessonUrl;
    } elseif (isset($activeSession) && $activeSession) {
        $nextStepTitle = 'Lanjutkan praktik';
        $nextStepBody = $activeSession->lab->title ?? 'Praktik berjalan';
        $nextStepUrl = \Illuminate\Support\Facades\Route::has('lab.workspace')
            ? route('lab.workspace', ['id' => $activeSession->lab_id])
            : route('courses.curriculum');
    } elseif ($weakestLearningScore !== null && $weakestLearningScore < $passingScoreTarget) {
        $nextStepTitle = 'Ulangi bagian terendah';
        $nextStepBody = $weakestLearningLabel . ' - nilai ' . $weakestLearningScore;
        $nextStepUrl = route('courses.curriculum');
    } else {
        $nextStepTitle = 'Lihat ringkasan belajar';
        $nextStepBody = 'Semua materi yang tercatat sudah dibaca';
        $nextStepUrl = route('courses.curriculum');
    }

    $progressTargetGap = max(0, $completionTarget - $overallProgress);
    $progressTargetLabel = $progressTargetGap > 0 ? 'Kurang ' . number_format($progressTargetGap) . '%' : 'Target tercapai';
@endphp

<div id="appRoot" class="relative h-screen bg-slate-50 dark:bg-[#020617] text-slate-800 dark:text-slate-200 font-sans overflow-hidden flex flex-col selection:bg-cyan-500/30 selection:text-cyan-900 dark:selection:text-white transition-colors duration-500 pt-[76px] md:pt-[88px]">

    {{-- ======================================================================
         1. BACKGROUND EFFECTS
         ====================================================================== --}}
    <div class="fixed inset-0 -z-50 pointer-events-none">
        <div id="animated-bg" class="absolute inset-0 opacity-40 transition-colors duration-500"></div>
        <div class="absolute top-[-10%] left-[-10%] w-[800px] h-[800px] bg-indigo-300/30 dark:bg-indigo-900/10 rounded-full blur-[120px] animate-pulse transition-colors duration-500"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[600px] h-[600px] bg-cyan-300/30 dark:bg-cyan-900/10 rounded-full blur-[100px] transition-colors duration-500"></div>
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.02] dark:opacity-[0.03] mix-blend-overlay transition-opacity duration-500"></div>
    </div>

    @include('layouts.partials.navbar')
    
    {{-- WRAPPER UTAMA DENGAN ALPINEJS --}}
    <div class="flex flex-1 overflow-hidden relative" 
         x-data="{ 
            sidebarOpen: false, showJoinModal: false, showLessonModal: false,
            showLabModal: false, showQuizModal: false, showChapterModal: false,
            showDashboardInfoModal: false,
            showLearningInsightModal: false,
            showLatestActivityModal: false,
            showQuizReviewModal: false,
            showLabReviewModal: false,
            selectedQuizReview: null,
            selectedLabReview: null
         }"
         @keydown.escape.window="sidebarOpen = false; showJoinModal = false; showLessonModal = false; showLabModal = false; showQuizModal = false; showChapterModal = false; showDashboardInfoModal = false; showLearningInsightModal = false; showLatestActivityModal = false; showQuizReviewModal = false; showLabReviewModal = false;">

        {{-- Overlay Mobile --}}
        <div x-show="sidebarOpen" class="fixed inset-0 bg-slate-900/60 dark:bg-[#020617]/80 backdrop-blur-sm z-[90] lg:hidden transition-colors" @click="sidebarOpen = false" x-transition.opacity style="display: none;" x-cloak></div>
        
        {{-- ======================================================================
             SIDEBAR MENU (SAAS REFINED)
             ====================================================================== --}}
        <aside class="w-[260px] bg-white/80 dark:bg-[#020617]/80 backdrop-blur-2xl border-r border-slate-200/80 dark:border-white/5 flex flex-col shrink-0 z-[100] absolute lg:relative inset-y-0 left-0 h-full transition-transform duration-300 transform lg:translate-x-0 shadow-2xl lg:shadow-none" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            
            {{-- Tombol Tutup Sidebar Mobile --}}
            <button @click="sidebarOpen = false" class="lg:hidden absolute top-4 right-4 p-2 rounded-lg text-slate-500 hover:bg-slate-100 dark:hover:bg-white/5 transition-colors focus:outline-none z-50">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            {{-- Navigasi Menu --}}
            <div class="p-5 pt-8 lg:pt-6 overflow-y-auto custom-scrollbar flex-1 relative z-10">
                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-3 pl-3 transition-colors">Menu Utama</p>
                <nav class="space-y-1">
                    <a href="{{ route('dashboard') }}" class="group flex items-center gap-3 px-3 py-2.5 rounded-lg bg-slate-100/80 dark:bg-white/5 text-slate-900 dark:text-white font-semibold transition-all">
                        <svg class="w-5 h-5 text-cyan-600 dark:text-cyan-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                        <span class="text-[14px]">Overview</span>
                    </a>
                    
                    @php $isUnlocked = Auth::user() && (Auth::user()->role === 'admin' || !empty(Auth::user()->class_group)); @endphp
                    @if($isUnlocked)
                        <a href="{{ route('courses.curriculum') }}" class="group flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-50 dark:hover:bg-white/[0.03] text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors">
                            <svg class="w-5 h-5 text-slate-400 group-hover:text-slate-700 dark:group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                            <span class="text-[14px] font-medium">Materi Belajar</span>
                        </a>
                    @else
                        <button type="button" @click="showJoinModal = true" class="relative overflow-hidden w-full group flex items-center justify-between px-3 py-2.5 rounded-lg border border-amber-200/70 dark:border-amber-500/20 bg-white/60 dark:bg-white/[0.025] text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:border-amber-300 dark:hover:border-amber-400/40 transition-all text-left" title="Gabung kelas untuk membuka materi">
                            <span class="absolute inset-0 bg-gradient-to-r from-transparent via-amber-300/20 dark:via-amber-400/10 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700"></span>
                            <span class="absolute left-0 top-2 bottom-2 w-1 rounded-r-full bg-amber-400 shadow-[0_0_14px_rgba(251,191,36,.55)]"></span>
                            <div class="flex items-center gap-3">
                                <span class="relative flex h-2 w-2 shrink-0">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                                </span>
                                <svg class="relative w-5 h-5 text-amber-600 dark:text-amber-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                                <span class="relative text-[14px] font-semibold">Materi Belajar</span>
                            </div>
                            <span class="relative px-2 py-0.5 rounded-full bg-amber-100 dark:bg-amber-400/10 text-[9px] font-black uppercase tracking-widest text-amber-700 dark:text-amber-300 border border-amber-200/70 dark:border-amber-400/20">
                                Perlu Kelas
                            </span>
                        </button>
                    @endif

                    <a href="{{ route('profile.edit') }}" class="group flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-50 dark:hover:bg-white/[0.03] text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors">
                        <svg class="w-5 h-5 text-slate-400 group-hover:text-slate-700 dark:group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        <span class="text-[14px] font-medium">Pengaturan</span>
                    </a>
                    
                    <a href="{{ route('developer.info') }}" class="group flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-50 dark:hover:bg-white/[0.03] text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors">
                        <svg class="w-5 h-5 text-slate-400 group-hover:text-slate-700 dark:group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span class="text-[14px] font-medium">Informasi Sistem</span>
                    </a>
                </nav>
            </div>

            
        </aside>

        {{-- MAIN CONTENT (Scrollable Area) --}}
        <main class="flex-1 h-full overflow-y-auto scroll-smooth relative custom-scrollbar p-5 md:p-8 lg:p-10">
            <div class="max-w-7xl mx-auto space-y-8 pb-20">
                
                {{-- TOMBOL HAMBURGER MOBILE (Dashboard Area) --}}
                <div class="flex items-center gap-3 mb-2 lg:hidden">
                    <button @click="sidebarOpen = true" class="p-2 bg-white/80 dark:bg-white/5 backdrop-blur-md rounded-lg text-slate-700 dark:text-white hover:bg-slate-100 dark:hover:bg-white/10 transition-colors border border-slate-200 dark:border-white/10 shadow-sm focus:outline-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest transition-colors">Menu Dasbor</span>
                </div>

                 {{-- =========================================================
                     1. HEADER DASHBOARD
                     ========================================================= --}}
                <div class="dashboard-hero-shell flex flex-col xl:flex-row justify-between items-start gap-6">
                    <div class="flex-1 w-full">
                        {{-- BREADCRUMB --}}
                        <nav class="flex items-center gap-2 mb-4 text-[11px] font-bold uppercase tracking-widest text-slate-500 dark:text-slate-500 transition-colors" aria-label="Breadcrumb">
                            <a href="/" class="hover:text-slate-900 dark:hover:text-white transition-colors flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                Beranda
                            </a>
                            <span class="text-slate-300 dark:text-slate-700 transition-colors">/</span>
                            <span class="text-cyan-600 dark:text-cyan-400 transition-colors">Dasbor Akademik</span>
                        </nav>

                        {{-- HEADLINE & PANDUAN BUTTON --}}
                        <div class="flex items-center gap-4 mb-3 reveal-up">
                            <h1 class="text-3xl md:text-4xl lg:text-5xl font-black text-slate-900 dark:text-white tracking-tight transition-colors">
                                <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-600 to-indigo-600 dark:from-cyan-400 dark:to-indigo-400">Dashboard Pengguna</span>
                            </h1>
                            <button @click="showDashboardInfoModal = true" class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-500 dark:text-slate-400 hover:text-cyan-600 dark:hover:text-cyan-400 hover:bg-white dark:hover:bg-white/10 hover:border-cyan-200 dark:hover:border-cyan-500/30 transition-all duration-300 shadow-sm hover:shadow-md focus:outline-none group" title="Panduan Dasbor">
                                <svg class="w-4 h-4 md:w-5 md:h-5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </button>
                        </div>
                        <p class="text-slate-600 dark:text-slate-400 text-sm md:text-base transition-colors max-w-2xl reveal-up delay-100">Pantau progres materi, praktik, evaluasi, dan riwayat aktivitas belajar.</p>
                        
                        <div class="mt-6 flex flex-wrap items-center gap-4 reveal-up delay-200">
                            {{-- Kelas Badge --}}
                            <div class="inline-flex items-center gap-4 px-4 py-3 rounded-2xl bg-white/50 dark:bg-white/[0.02] backdrop-blur-sm border border-slate-200/80 dark:border-white/[0.05] shadow-sm w-full md:w-auto transition-colors">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-cyan-500 flex items-center justify-center font-bold text-white shadow-sm text-lg shrink-0">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-[10px] text-slate-500 dark:text-slate-400 uppercase tracking-widest font-bold mb-0.5 transition-colors">Kelas</p>
                                    <div class="flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full shrink-0 {{ Auth::user()->class_group ? 'bg-emerald-500 shadow-[0_0_8px_#10b981]' : 'bg-yellow-500 shadow-[0_0_8px_#eab308] animate-pulse' }}"></span>
                                        <span class="text-[13px] md:text-sm font-bold text-slate-800 dark:text-white truncate transition-colors">{{ Auth::user()->class_group ?? 'Belum bergabung kelas' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ACTION BAR: GABUNG KELAS ATAU ALERT LAB --}}
                <div class="flex flex-col md:flex-row items-center gap-4 reveal-up delay-200">
                    @empty(Auth::user()->class_group)
                        <button @click="showJoinModal = true" class="w-full md:w-auto flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 dark:hover:bg-indigo-500 text-white text-[13px] font-bold shadow-md transition-all border border-transparent focus:outline-none group">
                            <svg class="w-4 h-4 transition-transform group-hover:rotate-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                            Gabung Kelas Sekarang
                        </button>
                    @endempty

                    @if(isset($activeSession) && $activeSession)
                        <div class="w-full rounded-2xl bg-indigo-50/80 dark:bg-indigo-500/10 border border-indigo-200/80 dark:border-indigo-500/20 p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-sm animate-pulse-slow transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-indigo-500 flex items-center justify-center animate-pulse shadow-sm shrink-0">
                                    <svg class="w-4.5 h-4.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-[13px] text-indigo-900 dark:text-indigo-100 transition-colors leading-tight">Praktik Sedang Berjalan: {{ $activeSession->lab->title ?? 'Praktik' }}</h3>
                                    <p class="text-indigo-600/80 dark:text-indigo-300 text-[11px] transition-colors mt-0.5 font-medium">Sesi praktik belum diselesaikan.</p>
                                </div>
                            </div>
                            <a href="{{ route('lab.workspace', $activeSession->lab_id) }}" class="w-full sm:w-auto px-5 py-2 bg-indigo-600 dark:bg-indigo-500 hover:bg-indigo-700 dark:hover:bg-indigo-400 text-white text-center font-bold rounded-lg text-[13px] transition shadow-sm flex items-center justify-center gap-2 shrink-0 focus:outline-none">
                                Lanjutkan Praktik <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        </div>
                    @endif
                </div>

                {{-- RINGKASAN DATA BELAJAR --}}
                <div class="hidden">
                    @php
                        $completionToneClasses = $analyticsToneClasses[$learningCompletionTone] ?? $analyticsToneClasses['slate'];
                        $latestToneClasses = $analyticsToneClasses[$latestActivityTone] ?? $analyticsToneClasses['slate'];
                        $learningInsightToneClasses = $analyticsToneClasses[$learningInsightTone] ?? $analyticsToneClasses['slate'];
                    @endphp

                    <div class="academic-card bento-card xl:col-span-8 rounded-[1.5rem] bg-white dark:bg-[#0f141e] border border-slate-200/80 dark:border-white/[0.05] p-5 md:p-6 shadow-sm dark:shadow-none transition-colors duration-500">
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between mb-5 border-b border-slate-100 dark:border-white/[0.05] pb-4">
                            <div>
                                <p class="text-[10px] uppercase tracking-[0.2em] font-black text-slate-400 dark:text-slate-500 mb-1">Analitik Belajar</p>
                                <h3 class="text-[15px] md:text-[16px] font-bold text-slate-900 dark:text-white">Detail Belajar</h3>
                            </div>
                            <span class="px-3 py-1.5 rounded-lg border text-[10px] font-black uppercase tracking-widest {{ $learningInsightToneClasses['soft'] }}">
                                {{ $learningInsightTitle }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($learningInsightBars as $insightBar)
                                @php
                                    $insightBarTone = $analyticsToneClasses[$insightBar['tone']] ?? $analyticsToneClasses['slate'];
                                    $insightBarPercent = min(100, max(0, (int) $insightBar['percent']));
                                @endphp
                                <div class="rounded-xl border border-slate-100 bg-slate-50/70 p-3 dark:border-white/[0.05] dark:bg-white/[0.03]">
                                    <div class="flex items-start justify-between gap-3 mb-2">
                                        <div>
                                            <p class="text-sm font-black text-slate-900 dark:text-white">{{ $insightBar['label'] }}</p>
                                        </div>
                                        <span class="text-xs font-black {{ $insightBarTone['text'] }}">{{ $insightBar['value'] }}</span>
                                    </div>
                                    <div class="h-2 rounded-full bg-slate-100 dark:bg-white/5 overflow-hidden border border-slate-200/60 dark:border-white/5">
                                        <div class="h-full {{ $insightBarTone['bar'] }} transition-all duration-1000" style="width: {{ $insightBarPercent }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="dashboard-progress-card academic-card bento-card xl:col-span-4 min-h-[260px] rounded-[1.5rem] bg-white dark:bg-[#0f141e] border border-slate-200/80 dark:border-white/[0.05] p-5 md:p-6 shadow-sm dark:shadow-none transition-colors duration-500">
                        <div class="flex items-start justify-between gap-4 border-b border-slate-100 dark:border-white/[0.05] pb-4">
                            <div>
                                <p class="text-[10px] uppercase tracking-[0.2em] font-black text-slate-400 dark:text-slate-500 mb-2">Progres Total</p>
                                <h2 class="text-lg md:text-xl font-black text-slate-900 dark:text-white leading-tight">Penyelesaian Belajar</h2>
                            </div>
                            <span class="px-3 py-1.5 rounded-lg border text-[10px] font-black uppercase tracking-widest {{ $completionToneClasses['soft'] }}">
                                {{ $learningCompletionStatus }}
                            </span>
                        </div>

                        <div class="mt-5">
                            <div class="flex items-end justify-between gap-3 mb-2">
                                <div>
                                    <p class="text-[10px] uppercase tracking-widest font-bold text-slate-400 dark:text-slate-500">Progres</p>
                                    <p class="text-5xl font-black text-slate-900 dark:text-white mt-1 leading-none"><span class="counter-value">{{ $overallProgress }}</span><span class="text-lg text-slate-400 dark:text-slate-500">%</span></p>
                                </div>
                                <p class="text-[11px] font-bold text-slate-500 dark:text-slate-400 text-right">{{ $completedTasks }} / {{ $totalTasks }} item</p>
                            </div>
                            <div class="h-2.5 rounded-full bg-slate-100 dark:bg-white/5 overflow-hidden border border-slate-200/60 dark:border-white/5">
                                <div class="h-full {{ $completionToneClasses['bar'] }} transition-all duration-1000" style="width: {{ min(100, max(0, $overallProgress)) }}%"></div>
                            </div>
                        </div>

                        <div class="mt-5 flex flex-wrap gap-2">
                            @foreach([
                                'Materi' => $allLessons->isNotEmpty(),
                                'Praktik' => $allLabs->isNotEmpty(),
                                'Kuis/Evaluasi' => $allQuizzes->isNotEmpty(),
                            ] as $sourceLabel => $sourceActive)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg border text-[10px] font-bold {{ $sourceActive ? 'text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10 border-emerald-100 dark:border-emerald-500/20' : 'text-slate-400 dark:text-slate-500 bg-slate-50 dark:bg-white/[0.02] border-slate-100 dark:border-white/5' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $sourceActive ? 'bg-emerald-500' : 'bg-slate-300 dark:bg-slate-600' }}"></span>
                                    {{ $sourceLabel }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <div class="academic-card bento-card xl:col-span-8 rounded-[1.5rem] bg-white dark:bg-[#0f141e] border border-slate-200/80 dark:border-white/[0.05] p-5 md:p-6 shadow-sm dark:shadow-none transition-colors duration-500">
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between mb-5 border-b border-slate-100 dark:border-white/[0.05] pb-4">
                            <div>
                                <p class="text-[10px] uppercase tracking-[0.2em] font-black text-slate-400 dark:text-slate-500 mb-1">Ringkasan Aktivitas</p>
                                <h3 class="text-[15px] md:text-[16px] font-bold text-slate-900 dark:text-white">Aktivitas dan Hasil</h3>
                            </div>
                            <span class="inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500">
                                <span class="w-1.5 h-1.5 rounded-full {{ $completionToneClasses['dot'] }}"></span>
                                {{ $analyticsActivityCount }} aktivitas
                            </span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-3">
                            @foreach($analyticsOverviewItems as $overview)
                                @php $overviewTone = $analyticsToneClasses[$overview['tone']] ?? $analyticsToneClasses['slate']; @endphp
                                <div class="rounded-xl border border-slate-100 bg-slate-50/70 p-3 dark:border-white/[0.05] dark:bg-white/[0.03]">
                                    <div class="flex items-center gap-2 mb-3">
                                        <span class="w-2 h-2 rounded-full {{ $overviewTone['dot'] }}"></span>
                                        <p class="text-[10px] uppercase tracking-widest font-bold text-slate-400 dark:text-slate-500">{{ $overview['label'] }}</p>
                                    </div>
                                    <p class="text-2xl font-black {{ $overviewTone['text'] }}">{{ $overview['value'] }}</p>
                                    <p class="mt-1 text-[11px] leading-relaxed text-slate-500 dark:text-slate-400">{{ $overview['hint'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="academic-card bento-card xl:col-span-8 rounded-[1.5rem] bg-white dark:bg-[#0f141e] border border-slate-200/80 dark:border-white/[0.05] p-5 md:p-6 shadow-sm dark:shadow-none transition-colors duration-500">
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between mb-5 border-b border-slate-100 dark:border-white/[0.05] pb-4">
                            <div>
                                <p class="text-[10px] uppercase tracking-[0.2em] font-black text-slate-400 dark:text-slate-500 mb-1">Rincian Progres</p>
                                <h3 class="text-[15px] md:text-[16px] font-bold text-slate-900 dark:text-white">Materi, Praktik, dan Evaluasi</h3>
                            </div>
                            <p class="text-[11px] font-bold text-slate-500 dark:text-slate-400">{{ $analyticsActivityCount }} aktivitas tercatat</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($analyticsBreakdownItems as $breakdown)
                                @php
                                    $breakdownTone = $analyticsToneClasses[$breakdown['tone']] ?? $analyticsToneClasses['slate'];
                                    $breakdownPercent = min(100, max(0, (int) $breakdown['percent']));
                                @endphp
                                <div class="rounded-xl border border-slate-100 bg-slate-50/70 p-3 dark:border-white/[0.05] dark:bg-white/[0.03]">
                                    <div class="flex items-start justify-between gap-3 mb-2">
                                        <div>
                                            <p class="text-sm font-black text-slate-900 dark:text-white">{{ $breakdown['label'] }}</p>
                                            <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">{{ $breakdown['hint'] }}</p>
                                        </div>
                                        <span class="text-xs font-black {{ $breakdownTone['text'] }}">{{ $breakdown['value'] }}</span>
                                    </div>
                                    <div class="h-2 rounded-full bg-slate-100 dark:bg-white/5 overflow-hidden border border-slate-200/60 dark:border-white/5">
                                        <div class="h-full {{ $breakdownTone['bar'] }} transition-all duration-1000" style="width: {{ $breakdownPercent }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="academic-card bento-card xl:col-span-4 rounded-[1.5rem] bg-white dark:bg-[#0f141e] border border-slate-200/80 dark:border-white/[0.05] p-5 md:p-6 shadow-sm dark:shadow-none transition-colors duration-500">
                        <div class="flex items-start justify-between gap-3 mb-5 border-b border-slate-100 dark:border-white/[0.05] pb-4">
                            <div>
                                <p class="text-[10px] uppercase tracking-[0.2em] font-black text-slate-400 dark:text-slate-500 mb-1">Aktivitas Terakhir</p>
                                <h3 class="text-[15px] md:text-[16px] font-bold text-slate-900 dark:text-white">Aktivitas Terbaru</h3>
                            </div>
                            <span class="px-2.5 py-1 rounded-lg border text-[10px] font-black {{ $latestToneClasses['soft'] }}">{{ $latestActivityLabel }}</span>
                        </div>

                        <div>
                            <p class="text-sm font-black text-slate-900 dark:text-white leading-snug">{{ $latestActivityName }}</p>
                            <p class="mt-1 text-[11px] font-medium text-slate-500 dark:text-slate-400">{{ $latestActivityTime }}</p>
                        </div>

                        <div class="pt-5 mt-5 border-t border-slate-100 dark:border-white/[0.05]">
                            <p class="text-[10px] uppercase tracking-[0.2em] font-black text-slate-400 dark:text-slate-500 mb-3">Ringkasan</p>
                            <div class="grid grid-cols-3 gap-2">
                                @foreach($activitySummaryItems as $summaryItem)
                                    @php $summaryTone = $analyticsToneClasses[$summaryItem['tone']] ?? $analyticsToneClasses['slate']; @endphp
                                    <div class="rounded-xl border border-slate-100 bg-slate-50/70 px-2.5 py-2.5 dark:border-white/[0.05] dark:bg-white/[0.03]">
                                        <p class="truncate text-[9px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">{{ $summaryItem['label'] }}</p>
                                        <p class="mt-1 text-sm font-black {{ $summaryTone['text'] }}">{{ $summaryItem['value'] }}</p>
                                        <p class="text-[9px] font-medium text-slate-500 dark:text-slate-400">{{ $summaryItem['detail'] }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- VISUAL SEPARATOR --}}
                <div class="hidden">
                    <div class="h-px bg-slate-200 dark:bg-white/[0.05] flex-1 transition-colors"></div>
                    <span class="text-[10px] font-black text-slate-400 dark:text-slate-600 uppercase tracking-[0.2em] px-3 py-1 transition-colors">Detail Belajar</span>
                    <div class="h-px bg-slate-200 dark:bg-white/[0.05] flex-1 transition-colors"></div>
                </div>

                {{-- =========================================================
                     2. GRID STATISTIK AKADEMIK (ULTIMATE CARDS)
                     ========================================================= --}}
                <div class="dashboard-metrics-bento grid grid-cols-1 gap-4 md:grid-cols-2 md:gap-5 xl:grid-cols-12 xl:gap-6 reveal-up delay-300">
                    
                    {{-- CARD 1: MATERI --}}
                    <div class="academic-card bento-card bento-metric bento-metric--lesson group bg-white dark:bg-[#0f141e] rounded-[1.5rem] p-5 relative flex flex-col justify-between border border-slate-200/80 dark:border-white/[0.05] hover:border-fuchsia-300 dark:hover:border-fuchsia-500/30 shadow-sm hover:shadow-md dark:shadow-none transition-all duration-300 cursor-pointer" @click="showLessonModal = true">
                        <div class="absolute inset-0 bg-gradient-to-br from-fuchsia-50/50 dark:from-fuchsia-500/5 to-transparent opacity-0 group-hover:opacity-100 transition duration-300 pointer-events-none rounded-[1.5rem]"></div>
                        <div class="relative z-10">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-lg bg-fuchsia-50 dark:bg-fuchsia-500/10 text-fuchsia-600 dark:text-fuchsia-400 flex items-center justify-center border border-fuchsia-100 dark:border-fuchsia-500/20 transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                                    </div>
                                    <p class="text-[10px] font-bold text-slate-500 dark:text-slate-500 uppercase tracking-widest group-hover:text-fuchsia-600 dark:group-hover:text-fuchsia-400 transition-colors">Materi Bacaan</p>
                                </div>
                                <div class="tooltip-container tooltip-fuchsia tooltip-down" @click.stop>
                                    <div class="tooltip-trigger"><svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></div>
                                    <div class="tooltip-content">Menghitung jumlah halaman/slide materi teori yang telah Anda baca hingga tuntas.</div>
                                </div>
                            </div>
                            <div class="flex items-baseline gap-1 mt-4">
                                <span class="text-3xl font-black text-slate-900 dark:text-white group-hover:text-fuchsia-600 dark:group-hover:text-fuchsia-400 transition-colors counter-value">{{ $lessonsCompleted ?? 0 }}</span>
                                <span class="text-slate-400 dark:text-slate-600 font-bold text-[13px] transition-colors">/ {{ $totalLessons ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="w-full h-1 bg-slate-100 dark:bg-white/5 rounded-full mt-4 overflow-hidden relative z-10 transition-colors">
                            <div class="h-full bg-fuchsia-500 transition-all duration-1000" style="width: {{ $pctLesson }}%"></div>
                        </div>
                    </div>

                    {{-- CARD 2: HANDS-ON LABS --}}
                    <div class="academic-card bento-card bento-metric bento-metric--lab group bg-white dark:bg-[#0f141e] rounded-[1.5rem] p-5 relative flex flex-col justify-between border border-slate-200/80 dark:border-white/[0.05] hover:border-blue-300 dark:hover:border-blue-500/30 shadow-sm hover:shadow-md dark:shadow-none transition-all duration-300 cursor-pointer" @click="showLabModal = true">
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-50/50 dark:from-blue-500/5 to-transparent opacity-0 group-hover:opacity-100 transition duration-300 pointer-events-none rounded-[1.5rem]"></div>
                        <div class="relative z-10">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center border border-blue-100 dark:border-blue-500/20 transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                                    </div>
                                    <p class="text-[10px] font-bold text-slate-500 dark:text-slate-500 uppercase tracking-widest group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">Praktik</p>
                                </div>
                                <div class="tooltip-container tooltip-blue tooltip-down" @click.stop>
                                    <div class="tooltip-trigger"><svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></div>
                                    <div class="tooltip-content">Praktik per bab yang berhasil diselesaikan dengan nilai kelulusan minimal 70.</div>
                                </div>
                            </div>
                            <div class="flex items-baseline gap-1 mt-4">
                                <span class="text-3xl font-black text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors counter-value">{{ $labsCompleted ?? 0 }}</span>
                                <span class="text-slate-400 dark:text-slate-600 font-bold text-[13px] transition-colors">/ {{ $totalLabs ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="w-full h-1 bg-slate-100 dark:bg-white/5 rounded-full mt-4 overflow-hidden relative z-10 transition-colors">
                            <div class="h-full bg-blue-500 transition-all duration-1000" style="width: {{ $pctLab }}%"></div>
                        </div>
                    </div>

                    {{-- CARD 3: RATA-RATA KUIS --}}
                    <div class="academic-card bento-card bento-metric bento-metric--quiz group bg-white dark:bg-[#0f141e] rounded-[1.5rem] p-5 relative flex flex-col justify-between border border-slate-200/80 dark:border-white/[0.05] hover:border-cyan-300 dark:hover:border-cyan-500/30 shadow-sm hover:shadow-md dark:shadow-none transition-all duration-300 cursor-pointer" @click="showQuizModal = true">
                        <div class="absolute inset-0 bg-gradient-to-br from-cyan-50/50 dark:from-cyan-500/5 to-transparent opacity-0 group-hover:opacity-100 transition duration-300 pointer-events-none rounded-[1.5rem]"></div>
                        <div class="relative z-10 h-full flex flex-col justify-between">
                            <div>
                                <div class="flex justify-between items-start mb-2">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-lg bg-cyan-50 dark:bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 flex items-center justify-center border border-cyan-100 dark:border-cyan-500/20 transition-colors">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                                        </div>
                                        <p class="text-[10px] font-bold text-slate-500 dark:text-slate-500 uppercase tracking-widest group-hover:text-cyan-600 dark:group-hover:text-cyan-400 transition-colors">Rata-rata Kuis</p>
                                    </div>
                                    <div class="tooltip-container tooltip-cyan tooltip-left" @click.stop>
                                        <div class="tooltip-trigger"><svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></div>
                                        <div class="tooltip-content">Rata-rata nilai dari evaluasi yang telah dikerjakan.</div>
                                    </div>
                                </div>
                                <div class="flex items-baseline gap-1 mt-4">
                                    <span class="text-3xl font-black text-slate-900 dark:text-white group-hover:text-cyan-600 dark:group-hover:text-cyan-400 transition-colors counter-value">{{ round($quizAverage ?? 0, 1) }}</span>
                                </div>
                            </div>
                            <p class="text-[10px] text-slate-400 dark:text-slate-600 mt-4 font-medium transition-colors border-t border-slate-100 dark:border-white/[0.05] pt-3">Dari <span class="counter-value">{{ $quizzesCompleted ?? 0 }}</span> evaluasi.</p>
                        </div>
                    </div>

                    {{-- CARD 4: BAB LULUS --}}
                    <div class="academic-card bento-card bento-metric bento-metric--chapter group bg-white dark:bg-[#0f141e] rounded-[1.5rem] p-5 relative flex flex-col justify-between border border-slate-200/80 dark:border-white/[0.05] hover:border-emerald-300 dark:hover:border-emerald-500/30 shadow-sm hover:shadow-md dark:shadow-none transition-all duration-300 cursor-pointer" @click="showChapterModal = true">
                        <div class="absolute inset-0 bg-gradient-to-br from-emerald-50/50 dark:from-emerald-500/5 to-transparent opacity-0 group-hover:opacity-100 transition duration-300 pointer-events-none rounded-[1.5rem]"></div>
                        <div class="relative z-10 h-full flex flex-col justify-between">
                            <div>
                                <div class="flex justify-between items-start mb-2">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center border border-emerald-100 dark:border-emerald-500/20 transition-colors">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                        </div>
                                        <p class="text-[10px] font-bold text-slate-500 dark:text-slate-500 uppercase tracking-widest group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">Bab Lulus</p>
                                    </div>
                                    <div class="tooltip-container tooltip-emerald tooltip-left" @click.stop>
                                        <div class="tooltip-trigger"><svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></div>
                                        <div class="tooltip-content">Jumlah bab teori yang berhasil diselesaikan dengan nilai kuis akhir minimal 70.</div>
                                    </div>
                                </div>
                                <div class="flex items-baseline gap-1 mt-4">
                                    <span class="text-3xl font-black text-slate-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors counter-value">{{ $chaptersPassed ?? 0 }}</span>
                                    <span class="text-slate-400 dark:text-slate-600 font-bold text-[13px] transition-colors">Bab</span>
                                </div>
                            </div>
                            <p class="text-[10px] text-emerald-600 dark:text-emerald-500 mt-4 font-bold uppercase tracking-wider transition-colors border-t border-slate-100 dark:border-white/[0.05] pt-3 group-hover:tracking-widest duration-300">Lihat Rincian</p>
                        </div>
                    </div>

                    {{-- CARD 5: TARGET BELAJAR --}}
                    <div class="academic-card bento-card bento-metric bento-metric--target group bg-white dark:bg-[#0f141e] rounded-[1.5rem] p-5 relative flex flex-col justify-between border border-slate-200/80 dark:border-white/[0.05] hover:border-indigo-300 dark:hover:border-indigo-500/30 shadow-sm hover:shadow-md dark:shadow-none transition-all duration-300 cursor-pointer" @click="showLearningInsightModal = true">
                        <div class="absolute inset-0 bg-gradient-to-br from-indigo-50/50 dark:from-indigo-500/5 to-transparent opacity-0 group-hover:opacity-100 transition duration-300 pointer-events-none rounded-[1.5rem]"></div>
                        <div class="relative z-10">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center border border-indigo-100 dark:border-indigo-500/20 transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <p class="text-[10px] font-bold text-slate-500 dark:text-slate-500 uppercase tracking-widest group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">Target Belajar</p>
                                </div>
                                <div class="tooltip-container tooltip-indigo tooltip-left" @click.stop>
                                    <div class="tooltip-trigger"><svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></div>
                                    <div class="tooltip-content">Perbandingan seluruh aktivitas selesai dengan target ketuntasan.</div>
                                </div>
                            </div>
                            <div class="flex items-baseline gap-1 mt-4">
                                <span class="text-3xl font-black text-slate-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors counter-value">{{ number_format($overallProgress) }}</span>
                                <span class="text-slate-400 dark:text-slate-600 font-bold text-[13px] transition-colors">%</span>
                            </div>
                        </div>
                        <div class="relative z-10">
                            <div class="w-full h-1 bg-slate-100 dark:bg-white/5 rounded-full mt-4 overflow-hidden transition-colors">
                                <div class="h-full bg-indigo-500 transition-all duration-1000" style="width: {{ $overallProgress }}%"></div>
                            </div>
                            <p class="text-[10px] text-slate-400 dark:text-slate-600 mt-3 font-medium transition-colors">{{ number_format($completedTasks) }}/{{ number_format($totalTasks) }} aktivitas. {{ $progressTargetLabel }}.</p>
                        </div>
                    </div>

                    {{-- CARD 6: FOKUS ULANG --}}
                    <div class="academic-card bento-card bento-metric bento-metric--focus group bg-white dark:bg-[#0f141e] rounded-[1.5rem] p-5 relative flex flex-col justify-between border border-slate-200/80 dark:border-white/[0.05] hover:border-amber-300 dark:hover:border-amber-500/30 shadow-sm hover:shadow-md dark:shadow-none transition-all duration-300 cursor-pointer" @click="showChapterModal = true">
                        <div class="absolute inset-0 bg-gradient-to-br from-amber-50/60 dark:from-amber-500/5 to-transparent opacity-0 group-hover:opacity-100 transition duration-300 pointer-events-none rounded-[1.5rem]"></div>
                        <div class="relative z-10">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center border border-amber-100 dark:border-amber-500/20 transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                                    </div>
                                    <p class="text-[10px] font-bold text-slate-500 dark:text-slate-500 uppercase tracking-widest group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors">Fokus Ulang</p>
                                </div>
                                <span class="px-2 py-1 rounded-lg bg-amber-50 dark:bg-amber-500/10 text-[10px] font-black text-amber-600 dark:text-amber-400 border border-amber-100 dark:border-amber-500/20">{{ $weakestLearningScore !== null ? $weakestLearningScore : '-' }}</span>
                            </div>
                            <h3 class="mt-4 text-xl font-black leading-tight text-slate-900 dark:text-white group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors line-clamp-2">{{ $weakestLearningLabel }}</h3>
                        </div>
                        <div class="relative z-10">
                            <div class="w-full h-1 bg-slate-100 dark:bg-white/5 rounded-full mt-4 overflow-hidden transition-colors">
                                <div class="h-full bg-amber-500 transition-all duration-1000" style="width: {{ $weakestLearningPercent }}%"></div>
                            </div>
                            <p class="text-[10px] text-slate-400 dark:text-slate-600 mt-3 font-medium transition-colors">{{ $weakestLearningStatus }}</p>
                        </div>
                    </div>

                    {{-- CARD 7: AKTIVITAS TERAKHIR --}}
                    <div class="academic-card bento-card bento-metric bento-metric--latest group bg-white dark:bg-[#0f141e] rounded-[1.5rem] p-5 relative flex flex-col justify-between border border-slate-200/80 dark:border-white/[0.05] hover:border-violet-300 dark:hover:border-violet-500/30 shadow-sm hover:shadow-md dark:shadow-none transition-all duration-300 cursor-pointer" @click="showLatestActivityModal = true">
                        <div class="absolute inset-0 bg-gradient-to-br from-violet-50/50 dark:from-violet-500/5 to-transparent opacity-0 group-hover:opacity-100 transition duration-300 pointer-events-none rounded-[1.5rem]"></div>
                        <div class="relative z-10">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-lg bg-violet-50 dark:bg-violet-500/10 text-violet-600 dark:text-violet-400 flex items-center justify-center border border-violet-100 dark:border-violet-500/20 transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z"/></svg>
                                    </div>
                                    <p class="text-[10px] font-bold text-slate-500 dark:text-slate-500 uppercase tracking-widest group-hover:text-violet-600 dark:group-hover:text-violet-400 transition-colors">Aktivitas Terakhir</p>
                                </div>
                                <span class="px-2 py-1 rounded-lg bg-violet-50 dark:bg-violet-500/10 text-[10px] font-black text-violet-600 dark:text-violet-400 border border-violet-100 dark:border-violet-500/20">{{ $latestActivityLabel }}</span>
                            </div>
                            <h3 class="mt-4 text-xl font-black leading-tight text-slate-900 dark:text-white group-hover:text-violet-600 dark:group-hover:text-violet-400 transition-colors line-clamp-2">{{ $latestActivityName }}</h3>
                        </div>
                        <div class="relative z-10 mt-4 border-t border-slate-100 pt-3 dark:border-white/[0.05]">
                            <p class="text-[10px] font-medium text-slate-400 transition-colors dark:text-slate-600">{{ $latestActivityTime }}</p>
                            <a href="{{ $nextUnreadLessonUrl }}" @click.stop class="mt-2 flex items-center justify-between gap-2 rounded-xl bg-violet-50/70 px-3 py-2 transition hover:bg-violet-100 dark:bg-violet-500/10 dark:hover:bg-violet-500/15">
                                <span class="truncate text-[10px] font-black text-violet-700 dark:text-violet-300">{{ $nextUnreadLessonCardLabel }}</span>
                                <svg class="h-3.5 w-3.5 shrink-0 text-violet-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>

                    {{-- CARD 8: LANGKAH BERIKUTNYA --}}
                    <a href="{{ $nextStepUrl }}" class="academic-card bento-card bento-metric bento-metric--next group bg-white dark:bg-[#0f141e] rounded-[1.5rem] p-5 relative flex flex-col justify-between border border-slate-200/80 dark:border-white/[0.05] hover:border-rose-300 dark:hover:border-rose-500/30 shadow-sm hover:shadow-md dark:shadow-none transition-all duration-300 cursor-pointer">
                        <div class="absolute inset-0 bg-gradient-to-br from-rose-50/50 dark:from-rose-500/5 to-transparent opacity-0 group-hover:opacity-100 transition duration-300 pointer-events-none rounded-[1.5rem]"></div>
                        <div class="relative z-10">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-lg bg-rose-50 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400 flex items-center justify-center border border-rose-100 dark:border-rose-500/20 transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                                    </div>
                                    <p class="text-[10px] font-bold text-slate-500 dark:text-slate-500 uppercase tracking-widest group-hover:text-rose-600 dark:group-hover:text-rose-400 transition-colors">Langkah Berikutnya</p>
                                </div>
                            </div>
                            <h3 class="mt-4 text-xl font-black leading-tight text-slate-900 dark:text-white group-hover:text-rose-600 dark:group-hover:text-rose-400 transition-colors line-clamp-2">{{ $nextStepTitle }}</h3>
                        </div>
                        <p class="relative z-10 text-[10px] text-slate-400 dark:text-slate-600 mt-4 font-medium transition-colors border-t border-slate-100 dark:border-white/[0.05] pt-3">{{ $nextStepBody }}</p>
                    </a>
                </div>

                {{-- =========================================================
                     3. CHART & LOGS PURE ACADEMIC
                     ========================================================= --}}
                <div class="academic-bento-grid grid grid-cols-1 gap-6 md:gap-8 lg:grid-cols-3 xl:grid-cols-12 reveal-up delay-400 mt-2">
                    
                    {{-- KIRI: GRAFIK & TABEL (2 Kolom) --}}
                    <div class="bento-main-column lg:col-span-2 space-y-6 md:space-y-8">
                        
                        {{-- GRAFIK PERKEMBANGAN NILAI KUIS DAN LAB - Data Valid + Adaptasi Chart ZIP --}}
                        <div id="student-assessment-chart" class="academic-card bento-card bento-chart-card rounded-[1.5rem] bg-white dark:bg-[#0f141e] border border-slate-200/80 dark:border-white/[0.05] overflow-hidden shadow-sm dark:shadow-none transition-colors duration-500 scroll-mt-28"
                             x-data="{ chartView: 'all', chartType: 'line' }">

                            {{-- Header chart mengikuti adaptasi template ZIP/Notus, warna tetap desain Utilwind --}}
                            <div class="relative px-5 md:px-6 py-5 border-b border-slate-100 dark:border-white/[0.05] bg-gradient-to-br from-slate-50 via-white to-cyan-50/60 dark:from-[#0f141e] dark:via-[#0a0e17] dark:to-cyan-950/20 overflow-hidden">
                                <div class="absolute -right-16 -top-16 w-52 h-52 bg-cyan-300/20 dark:bg-cyan-500/10 rounded-full blur-[70px] pointer-events-none"></div>
                                <div class="absolute -left-14 -bottom-20 w-48 h-48 bg-indigo-300/20 dark:bg-indigo-500/10 rounded-full blur-[70px] pointer-events-none"></div>

                                <div class="relative z-10 flex flex-col gap-4 xl:flex-row xl:items-start xl:justify-between">
                                    <div>
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-50 dark:bg-cyan-500/10 border border-cyan-100 dark:border-cyan-500/20 text-[9px] font-black uppercase tracking-[0.18em] text-cyan-700 dark:text-cyan-300">
                                                <span class="w-1.5 h-1.5 rounded-full bg-cyan-500 shadow-[0_0_8px_#06b6d4]"></span>
                                                Performa Akademik
                                            </span>

                                            <div class="tooltip-container tooltip-cyan tooltip-down">
                                                <div class="tooltip-trigger">
                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </div>
                                                <div class="tooltip-content">
                                                    Grafik membandingkan nilai terbaik kuis dan praktik pada setiap bab. Data kosong ditampilkan sebagai jeda, bukan angka 0.
                                                </div>
                                            </div>
                                        </div>

                                        <h3 class="text-[17px] md:text-xl font-black text-slate-900 dark:text-white tracking-tight transition-colors">
                                            Grafik Nilai Kuis dan Praktik
                                        </h3>

                                        <p class="text-[11px] md:text-xs text-slate-500 dark:text-slate-400 mt-1 transition-colors font-medium max-w-xl">
                                            Nilai diambil dari nilai terbaik tiap bab agar perkembangan belajar lebih valid.
                                        </p>
                                    </div>

                                    <div class="flex flex-col sm:flex-row gap-2 sm:items-center">
                                        {{-- Toggle seri data --}}
                                        <div class="flex p-1 bg-white/80 dark:bg-[#020617]/80 rounded-xl border border-slate-200/70 dark:border-white/5 shadow-sm transition-colors">
                                            <button
                                                type="button"
                                                @click="chartView = 'all'; updateAssessmentChartView('all')"
                                                :class="chartView === 'all'
                                                    ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20'
                                                    : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'"
                                                class="px-3.5 py-2 rounded-lg text-[10px] font-black transition focus:outline-none">
                                                Semua
                                            </button>

                                            <button
                                                type="button"
                                                @click="chartView = 'quiz'; updateAssessmentChartView('quiz')"
                                                :class="chartView === 'quiz'
                                                    ? 'bg-cyan-500 text-white shadow-md shadow-cyan-500/20'
                                                    : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'"
                                                class="px-3.5 py-2 rounded-lg text-[10px] font-black transition focus:outline-none">
                                                Kuis
                                            </button>

                                            <button
                                                type="button"
                                                @click="chartView = 'lab'; updateAssessmentChartView('lab')"
                                                :class="chartView === 'lab'
                                                    ? 'bg-blue-500 text-white shadow-md shadow-blue-500/20'
                                                    : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'"
                                                class="px-3.5 py-2 rounded-lg text-[10px] font-black transition focus:outline-none">
                                                Praktik
                                            </button>
                                        </div>

                                        {{-- Toggle tipe chart --}}
                                        <div class="flex p-1 bg-white/80 dark:bg-[#020617]/80 rounded-xl border border-slate-200/70 dark:border-white/5 shadow-sm transition-colors">
                                            <button
                                                type="button"
                                                @click="chartType = 'line'; updateAssessmentChartType('line')"
                                                :class="chartType === 'line'
                                                    ? 'bg-slate-900 dark:bg-white text-white dark:text-[#020617] shadow-md'
                                                    : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'"
                                                class="px-3.5 py-2 rounded-lg text-[10px] font-black transition focus:outline-none">
                                                Garis
                                            </button>

                                            <button
                                                type="button"
                                                @click="chartType = 'bar'; updateAssessmentChartType('bar')"
                                                :class="chartType === 'bar'
                                                    ? 'bg-slate-900 dark:bg-white text-white dark:text-[#020617] shadow-md'
                                                    : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'"
                                                class="px-3.5 py-2 rounded-lg text-[10px] font-black transition focus:outline-none">
                                                Batang
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="relative p-5 md:p-6">
                                <div class="absolute inset-0 pointer-events-none overflow-hidden">
                                    <div class="absolute top-8 right-10 w-44 h-44 bg-cyan-200/20 dark:bg-cyan-500/10 rounded-full blur-[70px]"></div>
                                    <div class="absolute bottom-0 left-6 w-48 h-48 bg-indigo-200/20 dark:bg-indigo-500/10 rounded-full blur-[70px]"></div>
                                </div>

                                {{-- Ringkasan valid: null tidak dihitung sebagai 0 --}}
                                <div class="relative z-10 grid grid-cols-2 md:grid-cols-4 gap-3 mb-5">
                                    <div class="rounded-2xl bg-slate-50/80 dark:bg-[#020617]/70 border border-slate-200/70 dark:border-white/5 px-4 py-3">
                                        <p class="text-[9px] uppercase tracking-widest font-bold leading-tight text-slate-400 dark:text-slate-500">Rata-rata Kuis</p>
                                        <p class="mt-1 text-lg font-black text-cyan-600 dark:text-cyan-400">{{ $quizChartAverage !== null ? $quizChartAverage : '-' }}</p>
                                    </div>

                                    <div class="rounded-2xl bg-slate-50/80 dark:bg-[#020617]/70 border border-slate-200/70 dark:border-white/5 px-4 py-3">
                                        <p class="text-[9px] uppercase tracking-widest font-bold leading-tight text-slate-400 dark:text-slate-500">Rata-rata Praktik</p>
                                        <p class="mt-1 text-lg font-black text-blue-600 dark:text-blue-400">{{ $labChartAverage !== null ? $labChartAverage : '-' }}</p>
                                    </div>

                                    <div class="rounded-2xl bg-slate-50/80 dark:bg-[#020617]/70 border border-slate-200/70 dark:border-white/5 px-4 py-3">
                                        <p class="text-[9px] uppercase tracking-widest font-bold leading-tight text-slate-400 dark:text-slate-500">Tertinggi Kuis</p>
                                        <p class="mt-1 text-lg font-black text-emerald-600 dark:text-emerald-400">{{ $quizChartHighest !== null ? $quizChartHighest : '-' }}</p>
                                    </div>

                                    <div class="rounded-2xl bg-slate-50/80 dark:bg-[#020617]/70 border border-slate-200/70 dark:border-white/5 px-4 py-3">
                                        <p class="text-[9px] uppercase tracking-widest font-bold leading-tight text-slate-400 dark:text-slate-500">Tertinggi Praktik</p>
                                        <p class="mt-1 text-lg font-black text-indigo-600 dark:text-indigo-400">{{ $labChartHighest !== null ? $labChartHighest : '-' }}</p>
                                    </div>
                                </div>

                                <div class="relative z-10 h-[260px] md:h-[320px] w-full">
                                    @if($hasAssessmentChartData)
                                        <canvas id="assessmentProgressChart"></canvas>
                                    @else
                                        <div class="absolute inset-0 flex flex-col items-center justify-center border border-dashed border-slate-200 dark:border-white/10 rounded-2xl bg-slate-50/80 dark:bg-[#020617]/70 transition-colors">
                                            <svg class="w-7 h-7 text-slate-300 dark:text-slate-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                                            </svg>
                                            <p class="text-[11px] font-bold text-slate-400 dark:text-slate-500">Belum ada data kuis atau praktik</p>
                                            <p class="text-[10px] text-slate-400 dark:text-slate-600 mt-1">Grafik muncul setelah evaluasi atau praktik diselesaikan.</p>
                                        </div>
                                    @endif
                                </div>

                                <div class="relative z-10 mt-5 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between text-[10px] font-bold text-slate-500 dark:text-slate-500">
                                    <div class="flex items-center gap-4">
                                        <span class="inline-flex items-center gap-1.5">
                                            <span class="w-2.5 h-2.5 rounded-full bg-cyan-500"></span>
                                            Nilai Kuis
                                        </span>

                                        <span class="inline-flex items-center gap-1.5">
                                            <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                                            Nilai Praktik
                                        </span>
                                    </div>

                                    <p>Data kosong tidak dianggap 0.</p>
                                </div>
                            </div>
                        </div>

                        {{-- TABEL HISTORY EVALUASI --}}
                        <div id="student-activity-history" class="academic-card bento-card bento-history-card rounded-[1.5rem] bg-white dark:bg-[#0f141e] border border-slate-200/80 dark:border-white/[0.05] p-5 md:p-6 flex flex-col h-[400px] shadow-sm dark:shadow-none transition-colors duration-500 scroll-mt-28" x-data="{ filterTable: 'all' }">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4 shrink-0 border-b border-slate-100 dark:border-white/[0.05] pb-4 transition-colors">
                                <h3 class="text-[15px] md:text-[16px] font-bold text-slate-900 dark:text-white flex items-center gap-2 transition-colors">
                                    Riwayat Evaluasi
                                </h3>
                                
                                {{-- Filter Interaktif AlpineJS --}}
                                <div class="flex items-center bg-slate-100/80 dark:bg-[#020617] p-1 rounded-lg border border-slate-200/50 dark:border-white/5 transition-colors">
                                    <button @click="filterTable = 'all'" :class="filterTable === 'all' ? 'bg-cyan-500 text-white shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white'" class="px-3 py-1.5 rounded-md text-[11px] font-semibold transition focus:outline-none">Semua</button>
                                    <button @click="filterTable = 'kuis'" :class="filterTable === 'kuis' ? 'bg-fuchsia-500 text-white shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white'" class="px-3 py-1.5 rounded-md text-[11px] font-semibold transition focus:outline-none">Kuis</button>
                                    <button @click="filterTable = 'lab'" :class="filterTable === 'lab' ? 'bg-blue-500 text-white shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white'" class="px-3 py-1.5 rounded-md text-[11px] font-semibold transition focus:outline-none">Praktik</button>
                                </div>
                            </div>
                            
                            <div class="overflow-x-auto custom-scrollbar -mx-5 md:mx-0 px-5 md:px-0 flex-1 relative">
                                <div class="absolute top-0 bottom-0 right-0 w-4 bg-gradient-to-l from-white dark:from-[#0f141e] to-transparent pointer-events-none md:hidden z-10 transition-colors duration-500"></div>
                                <div class="h-full overflow-y-auto custom-scrollbar pr-2 pb-4">
                                    <table class="w-full text-left border-collapse min-w-[400px]">
                                        <thead class="sticky top-0 z-20 bg-white dark:bg-[#0f141e] after:absolute after:bottom-0 after:left-0 after:right-0 after:h-px after:bg-slate-100 dark:after:bg-white/[0.05] transition-colors duration-500">
                                            <tr class="text-[10px] text-slate-400 dark:text-slate-500 uppercase tracking-widest transition-colors font-bold">
                                                <th class="py-3 pl-2">Aktivitas Evaluasi</th>
                                                <th class="py-3 hidden sm:table-cell">Waktu Pengumpulan</th>
                                                <th class="py-3 text-right pr-2">Nilai Akhir</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-[13px] text-slate-700 dark:text-slate-300 transition-colors">
                                            @forelse(collect($historyCombined)->whereIn('type', ['kuis', 'lab', 'quiz']) as $item)
                                                @php
                                                    $typeLower = strtolower($item['type']);
                                                    $typeLabel = 'Aktivitas';
                                                    $typeColor = 'text-slate-500 dark:text-slate-400';
                                                    $iconBg = 'bg-slate-100 dark:bg-white/5 text-slate-500 dark:text-slate-400 border-slate-200 dark:border-white/5';
                                                    $icon = '<svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>';

                                                    if ($typeLower == 'kuis' || $typeLower == 'quiz') {
                                                        $typeLabel = 'Evaluasi Teori';
                                                        $typeColor = 'text-fuchsia-600 dark:text-fuchsia-400';
                                                        $iconBg = 'bg-fuchsia-50 dark:bg-fuchsia-500/10 text-fuchsia-600 dark:text-fuchsia-400 border-fuchsia-100 dark:border-fuchsia-500/10';
                                                        $icon = '<svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>';
                                                    } elseif ($typeLower == 'lab') {
                                                        $typeLabel = 'Praktik';
                                                        $typeColor = 'text-blue-600 dark:text-blue-400';
                                                        $iconBg = 'bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 border-blue-100 dark:border-blue-500/10';
                                                        $icon = '<svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>';
                                                    }
                                                @endphp
                                                @php
                                                    $quizReviewPayload = null;
                                                    $labReviewPayload = null;
                                                    if ($typeLower == 'kuis' || $typeLower == 'quiz') {
                                                        $quizReviewPayload = [
                                                            'title' => $item['name'] ?? 'Evaluasi Teori',
                                                            'score' => $item['score'] ?? 0,
                                                            'status' => $item['status'] ?? (($item['score'] ?? 0) >= 70 ? 'Lulus' : 'Belum Lulus'),
                                                            'chapter' => $item['chapter_id'] ?? null,
                                                            'date' => $item['full_date'] ?? '-',
                                                            'time' => $item['time'] ?? '-',
                                                            'duration' => $item['duration_text'] ?? '-',
                                                            'answered' => $item['answered_count'] ?? 0,
                                                            'unanswered' => $item['unanswered_count'] ?? 0,
                                                            'flagged' => $item['flagged_count'] ?? 0,
                                                            'focusLost' => $item['focus_lost_count'] ?? 0,
                                                            'feedbackLevel' => $item['feedback_level'] ?? (($item['score'] ?? 0) >= 70 ? 'Lulus' : 'Belum Lulus'),
                                                            'feedbackMessage' => $item['feedback_message'] ?? '',
                                                            'reflectionNote' => $item['reflection_note'] ?? '',
                                                            'reviewUrl' => $item['review_url'] ?? null,
                                                        ];
                                                    }

                                                    if ($typeLower == 'lab') {
                                                        $labReviewPayload = [
                                                            'title' => $item['name'] ?? 'Praktik',
                                                            'score' => $item['score'] ?? 0,
                                                            'status' => $item['status'] ?? (($item['score'] ?? 0) >= 70 ? 'Lulus' : 'Belum Lulus'),
                                                            'date' => $item['full_date'] ?? '-',
                                                            'time' => $item['time'] ?? '-',
                                                            'duration' => $item['duration_text'] ?? '-',
                                                            'completedSteps' => $item['completed_steps'] ?? 0,
                                                            'totalSteps' => $item['total_steps'] ?? 0,
                                                            'completionPercent' => $item['completion_percent'] ?? 0,
                                                            'passingGrade' => $item['passing_grade'] ?? 70,
                                                            'feedbackLevel' => $item['feedback_level'] ?? (($item['score'] ?? 0) >= 70 ? 'Lulus' : 'Belum Lulus'),
                                                            'feedbackMessage' => $item['feedback_message'] ?? '',
                                                            'reviewUrl' => $item['review_url'] ?? null,
                                                        ];
                                                    }
                                                @endphp
                                                <tr x-show="filterTable === 'all' || filterTable === '{{ $typeLower === 'quiz' ? 'kuis' : $typeLower }}'" class="group hover:bg-slate-50 dark:hover:bg-white/[0.02] transition-colors border-b border-slate-100/50 dark:border-white/[0.02] last:border-0" x-transition>
                                                    <td class="py-3 pl-2 font-medium text-slate-800 dark:text-white flex items-center gap-3 transition-colors">
                                                        <div class="w-7 h-7 rounded-md flex items-center justify-center shrink-0 border {{ $iconBg }} transition-colors">
                                                            {!! $icon !!}
                                                        </div>
                                                        <div class="flex flex-col min-w-0">
                                                            <span class="truncate text-[13px] font-bold text-slate-900 dark:text-white group-hover:text-cyan-600 dark:group-hover:text-cyan-400 transition-colors" title="{{ $item['name'] }}">{{ $item['name'] }}</span>
                                                            <div class="flex items-center gap-1.5 mt-0.5">
                                                                <span class="text-[9px] uppercase font-bold tracking-wider {{ $typeColor }} transition-colors">{{ $typeLabel }}</span>
                                                                <span class="w-1 h-1 rounded-full bg-slate-300 dark:bg-white/20 sm:hidden transition-colors"></span>
                                                                <span class="text-[10px] text-slate-500 dark:text-slate-500 sm:hidden transition-colors">{{ $item['time'] ?? '' }}</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="py-3 hidden sm:table-cell transition-colors">
                                                        <p class="text-[12px] font-medium text-slate-700 dark:text-slate-300">{{ $item['full_date'] ?? '' }}</p>
                                                        <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5">{{ $item['time'] ?? '' }}</p>
                                                    </td>
                                                    <td class="py-3 text-right pr-2 shrink-0">
                                                        @if(isset($item['score']))
                                                            <span class="px-2.5 py-1 rounded-md text-[11px] font-bold border transition-colors {{ $item['score'] >= 70 ? 'text-emerald-700 bg-emerald-50 dark:text-emerald-400 dark:bg-emerald-500/10 border-emerald-200 dark:border-emerald-500/10' : 'text-red-700 bg-red-50 dark:text-red-400 dark:bg-red-500/10 border-red-200 dark:border-red-500/10' }}">Nilai {{ $item['score'] }}</span>
                                                        @endif
                                                        @if($quizReviewPayload)
                                                            <button type="button"
                                                                    @click.stop="selectedQuizReview = {{ \Illuminate\Support\Js::from($quizReviewPayload) }}; showQuizReviewModal = true"
                                                                    class="mt-1 inline-flex items-center justify-end gap-1 text-[10px] font-bold text-fuchsia-600 dark:text-fuchsia-400 hover:text-fuchsia-700 dark:hover:text-fuchsia-300 transition-colors focus:outline-none">
                                                                Tinjau
                                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                                            </button>
                                                        @elseif($labReviewPayload)
                                                            <button type="button"
                                                                    @click.stop="selectedLabReview = {{ \Illuminate\Support\Js::from($labReviewPayload) }}; showLabReviewModal = true"
                                                                    class="mt-1 inline-flex items-center justify-end gap-1 text-[10px] font-bold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition-colors focus:outline-none">
                                                                Tinjau
                                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                                            </button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="3" class="py-8 text-center text-slate-400 dark:text-slate-600 italic text-[13px] transition-colors">Belum ada riwayat pengerjaan kuis atau praktik.</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- KANAN: AKTIVITAS PIE CHART & LIVE LOG (1 Kolom) --}}
                    <div class="bento-side-column lg:col-span-1 space-y-6 md:space-y-8">

                        {{-- Komposisi Aktivitas --}}
                        <div class="academic-card bento-card bento-activity-card rounded-[1.5rem] bg-white dark:bg-[#0f141e] border border-slate-200/80 dark:border-white/[0.05] p-5 md:p-6 shadow-sm dark:shadow-none transition-colors duration-500 relative">
                            <div class="flex justify-between items-start mb-4">
                                <h3 class="text-[14px] md:text-[15px] font-bold text-slate-900 dark:text-white transition-colors">
                                    Komposisi Aktivitas
                                </h3>
                            </div>
                            <div class="relative h-[180px] w-full flex justify-center">
                                @if(($lessonsCompleted ?? 0) > 0 || ($labsCompleted ?? 0) > 0 || ($quizzesCompleted ?? 0) > 0)
                                    <canvas id="activityPieChart"></canvas>
                                @else
                                    <div class="absolute inset-0 flex flex-col items-center justify-center border border-dashed border-slate-200 dark:border-white/10 rounded-xl bg-slate-50 dark:bg-white/[0.02] transition-colors">
                                        <p class="text-[11px] font-medium text-slate-400 dark:text-slate-600">Mulai belajar untuk melihat data.</p>
                                    </div>
                                @endif
                            </div>
                            <div class="mt-4 grid grid-cols-3 gap-2 text-center border-t border-slate-100 dark:border-white/[0.05] pt-4">
                                <div>
                                    <p class="text-[9px] text-slate-500 dark:text-slate-500 uppercase tracking-widest font-bold">Materi</p>
                                    <p class="text-[15px] font-black text-fuchsia-500 counter-value">{{ $lessonsCompleted ?? 0 }}</p>
                                </div>
                                <div>
                                    <p class="text-[9px] text-slate-500 dark:text-slate-500 uppercase tracking-widest font-bold">Praktik</p>
                                    <p class="text-[15px] font-black text-blue-500 counter-value">{{ $labsCompleted ?? 0 }}</p>
                                </div>
                                <div>
                                    <p class="text-[9px] text-slate-500 dark:text-slate-500 uppercase tracking-widest font-bold">Kuis</p>
                                    <p class="text-[15px] font-black text-cyan-500 counter-value">{{ $quizzesCompleted ?? 0 }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Log Real-time Aktivitas --}}
                        <div class="academic-card bento-card bento-log-card rounded-[1.5rem] bg-white dark:bg-[#0f141e] border border-slate-200/80 dark:border-white/[0.05] p-5 md:p-6 h-[400px] flex flex-col relative overflow-hidden shadow-sm dark:shadow-none transition-colors duration-500" x-data="{ logFilter: 'all' }">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 border-b border-slate-100 dark:border-white/[0.05] pb-4 shrink-0 gap-3 transition-colors">
                                <h3 class="text-[14px] md:text-[15px] font-bold text-slate-900 dark:text-white transition-colors">
                                    Riwayat Aktivitas
                                </h3>
                                <div class="flex items-center bg-slate-100/80 dark:bg-[#020617] p-1 rounded-lg border border-slate-200/50 dark:border-white/5 transition-colors">
                                    <button @click="logFilter = 'all'" :class="logFilter === 'all' ? 'bg-cyan-500 text-white shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white'" class="px-2.5 py-1 rounded-md text-[10px] font-semibold transition focus:outline-none">Semua</button>
                                    <button @click="logFilter = 'materi'" :class="logFilter === 'materi' ? 'bg-indigo-500 text-white shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white'" class="px-2.5 py-1 rounded-md text-[10px] font-semibold transition focus:outline-none">Materi</button>
                                    <button @click="logFilter = 'kuis'" :class="logFilter === 'kuis' ? 'bg-fuchsia-500 text-white shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white'" class="px-2.5 py-1 rounded-md text-[10px] font-semibold transition focus:outline-none">Kuis</button>
                                </div>
                            </div>

                            <ul id="activityLogList" class="space-y-2 overflow-y-auto custom-scrollbar pr-2 flex-1 pb-4">
                                {{-- Render via JS --}}
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="border-t border-slate-200 dark:border-white/5 pt-6 mt-8 text-center transition-colors">
                    {{-- <p class="text-slate-500 dark:text-slate-500 text-[11px] font-medium">&copy; {{ date('Y') }} Utilwind CSS Academic Platform</p> --}}
                </div>
            </div>

            {{-- =========================================================================
                 MODAL INSIGHT ANALITIK & PANDUAN DASBOR
                 ========================================================================= --}}

            {{-- Hero Modal Insight Learning Analytics --}}
            <div x-show="showLearningInsightModal" style="display: none;" class="insight-modal-layer fixed inset-0 z-[99999] flex items-center justify-center p-4 sm:p-6" x-transition.opacity.duration.200ms x-cloak>
                <div class="absolute inset-0 bg-slate-900/60 dark:bg-[#020617]/85 backdrop-blur-md transition-colors" @click="showLearningInsightModal = false" x-transition.opacity></div>

                <div class="insight-modal-panel relative w-full max-w-5xl max-h-[92vh] overflow-hidden rounded-[2rem] bg-white/95 dark:bg-[#0f141e]/95 border border-cyan-200/80 dark:border-cyan-500/20 shadow-2xl transition-colors" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-5" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-95 translate-y-5">
                    <div class="insight-hero relative overflow-hidden bg-gradient-to-br from-cyan-600 via-indigo-600 to-fuchsia-600 px-6 py-7 text-white md:px-8 md:py-8">
                        <div class="relative z-10 flex items-start justify-between gap-5">
                            <div class="max-w-3xl">
                                <p class="mb-2 text-[10px] font-black uppercase tracking-[0.24em] text-cyan-50">Analitik Pembelajaran</p>
                                <h3 class="text-3xl font-black leading-tight md:text-5xl">{{ $learningInsightTitle }}</h3>
                                <p class="mt-3 text-sm font-semibold leading-relaxed text-white/85 md:text-base">{{ $learningInsightBody }}</p>
                            </div>
                            <button type="button" @click="showLearningInsightModal = false" class="insight-close-button" aria-label="Tutup insight" title="Tutup">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        <div class="insight-summary-grid relative z-10 mt-6 grid gap-3 sm:grid-cols-4">
                            <div class="insight-stat-card rounded-2xl border border-white/20 bg-white/10 p-4 backdrop-blur">
                                <p class="text-[10px] font-black uppercase tracking-[0.18em] text-cyan-50">Progres</p>
                                <b class="mt-2 block text-2xl font-black">{{ number_format($overallProgress) }}<span class="text-sm font-bold text-white/75">%</span></b>
                            </div>
                            <div class="insight-stat-card rounded-2xl border border-white/20 bg-white/10 p-4 backdrop-blur">
                                <p class="text-[10px] font-black uppercase tracking-[0.18em] text-cyan-50">Aktivitas</p>
                                <b class="mt-2 block text-2xl font-black">{{ number_format($analyticsActivityCount) }} <span class="text-sm font-bold text-white/75">aktivitas</span></b>
                            </div>
                            <div class="insight-stat-card rounded-2xl border border-white/20 bg-white/10 p-4 backdrop-blur">
                                <p class="text-[10px] font-black uppercase tracking-[0.18em] text-cyan-50">Nilai</p>
                                <b class="mt-2 block text-2xl font-black">
                                    {{ $combinedAssessmentAverage !== null ? $combinedAssessmentAverage : '-' }}
                                    @if($combinedAssessmentAverage !== null)
                                        <span class="text-sm font-bold text-white/75">nilai</span>
                                    @endif
                                </b>
                            </div>
                            <div class="insight-stat-card rounded-2xl border border-white/20 bg-white/10 p-4 backdrop-blur">
                                <p class="text-[10px] font-black uppercase tracking-[0.18em] text-cyan-50">Data</p>
                                <b class="mt-2 block text-2xl font-black">{{ number_format($analyticsSourceCount) }}/3 <span class="text-sm font-bold text-white/75">sumber</span></b>
                            </div>
                        </div>
                    </div>

                    <div class="insight-modal-body max-h-[56vh] overflow-y-auto bg-white p-6 dark:bg-[#0a0e17] md:p-8 custom-scrollbar">
                        <div class="grid gap-6 lg:grid-cols-[1.1fr_.9fr]">
                            <section class="rounded-2xl border border-slate-200 bg-slate-50/80 p-5 shadow-inner dark:border-white/10 dark:bg-[#020617]/70">
                                <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                                    <div>
                                        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-cyan-600 dark:text-cyan-300">Peta Progres</p>
                                        <h4 class="mt-1 text-lg font-black text-slate-900 dark:text-white">Materi, praktik, evaluasi, dan sumber data</h4>
                                    </div>
                                    <span class="rounded-xl border border-cyan-200 bg-cyan-50 px-3 py-2 text-[10px] font-black uppercase tracking-widest text-cyan-700 dark:border-cyan-400/20 dark:bg-cyan-400/10 dark:text-cyan-200">
                                        {{ number_format($analyticsAttemptCount) }} kali percobaan
                                    </span>
                                </div>

                                <div class="mt-6 space-y-4">
                                    @foreach($learningInsightBars as $insightBar)
                                        @php
                                            $insightBarTone = $analyticsToneClasses[$insightBar['tone']] ?? $analyticsToneClasses['slate'];
                                            $insightBarPercent = min(100, max(0, (int) $insightBar['percent']));
                                        @endphp
                                        <div>
                                            <div class="mb-2 flex items-start justify-between gap-3">
                                                <div>
                                                    <p class="text-sm font-black text-slate-900 dark:text-white">{{ $insightBar['label'] }}</p>
                                                    <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-400">{{ $insightBarPercent }}% tercatat</p>
                                                </div>
                                                <span class="text-xs font-black {{ $insightBarTone['text'] }}">{{ $insightBar['value'] }}</span>
                                            </div>
                                            <div class="h-2.5 overflow-hidden rounded-full border border-slate-200 bg-white dark:border-white/10 dark:bg-white/5">
                                                <div class="h-full rounded-full {{ $insightBarTone['bar'] }} transition-all duration-1000" style="width: {{ $insightBarPercent }}%"></div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </section>

                            <aside class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-[#0f141e]">
                                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-cyan-600 dark:text-cyan-300">Interpretasi</p>
                                <h4 class="mt-2 text-xl font-black leading-tight text-slate-900 dark:text-white">{{ $learningInsightTitle }}</h4>
                                <p class="mt-3 text-sm font-semibold leading-relaxed text-slate-500 dark:text-white/50">{{ $learningInsightAction }}</p>

                                <div class="mt-5 space-y-3">
                                    <div class="rounded-xl border border-fuchsia-200 bg-fuchsia-50 p-4 dark:border-fuchsia-400/20 dark:bg-fuchsia-400/10">
                                        <p class="text-[10px] font-black uppercase tracking-widest text-fuchsia-700 dark:text-fuchsia-200">Akses Materi</p>
                                        <p class="mt-1 text-xs font-semibold leading-relaxed text-fuchsia-800/80 dark:text-fuchsia-100/75">{{ number_format($lessonsCompleted ?? 0) }} dari {{ number_format($totalLessons ?? 0) }} materi selesai.</p>
                                    </div>
                                    <div class="rounded-xl border border-blue-200 bg-blue-50 p-4 dark:border-blue-400/20 dark:bg-blue-400/10">
                                        <p class="text-[10px] font-black uppercase tracking-widest text-blue-700 dark:text-blue-200">Praktik</p>
                                        <p class="mt-1 text-xs font-semibold leading-relaxed text-blue-800/80 dark:text-blue-100/75">{{ number_format($allLabs->count()) }} kali riwayat praktik, {{ number_format($labsCompleted ?? 0) }} dari {{ number_format($totalLabs ?? 0) }} praktik selesai.</p>
                                    </div>
                                    <div class="rounded-xl border border-cyan-200 bg-cyan-50 p-4 dark:border-cyan-400/20 dark:bg-cyan-400/10">
                                        <p class="text-[10px] font-black uppercase tracking-widest text-cyan-700 dark:text-cyan-200">Kuis dan Evaluasi</p>
                                        <p class="mt-1 text-xs font-semibold leading-relaxed text-cyan-800/80 dark:text-cyan-100/75">{{ number_format($allQuizzes->count()) }} kali percobaan, {{ number_format($totalFocusLost) }} kali fokus terganggu.</p>
                                    </div>
                                </div>

                                <div class="mt-5 grid gap-3 sm:grid-cols-2">
                                    <a href="{{ route('courses.curriculum') }}" @click="showLearningInsightModal = false" class="rounded-xl bg-cyan-600 px-4 py-3 text-center text-[10px] font-black uppercase tracking-widest text-white shadow-sm transition hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-cyan-400/40">Buka Silabus</a>
                                    <a href="#student-assessment-chart" @click="showLearningInsightModal = false" class="rounded-xl bg-slate-900 px-4 py-3 text-center text-[10px] font-black uppercase tracking-widest text-white shadow-sm transition hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-400/40 dark:bg-white dark:text-slate-950 dark:hover:bg-slate-200">Lihat Grafik</a>
                                </div>
                            </aside>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modal Insight Aktivitas Terakhir --}}
            <div x-show="showLatestActivityModal" style="display: none;" class="insight-modal-layer fixed inset-0 z-[99999] flex items-center justify-center p-4 sm:p-6" x-transition.opacity.duration.200ms x-cloak>
                <div class="absolute inset-0 bg-slate-900/55 dark:bg-[#020617]/85 backdrop-blur-md transition-colors" @click="showLatestActivityModal = false" x-transition.opacity></div>
                <div class="insight-modal-panel relative w-full max-w-3xl max-h-[90vh] overflow-hidden rounded-[2rem] bg-white dark:bg-[#0f141e] border border-violet-200/80 dark:border-violet-500/20 shadow-2xl transition-colors" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-5" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-95 translate-y-5">
                    <div class="insight-hero relative overflow-hidden px-6 py-6 md:px-7">
                        <button type="button" @click="showLatestActivityModal = false" class="insight-close-button" aria-label="Tutup insight" title="Tutup">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>

                        <p class="text-[10px] font-black uppercase tracking-[0.24em] text-violet-600 dark:text-violet-300">Aktivitas Terakhir</p>
                        <h3 class="mt-2 max-w-2xl text-2xl font-black leading-tight text-slate-900 dark:text-white">{{ $latestActivityName }}</h3>
                        <p class="mt-2 max-w-2xl text-sm font-semibold leading-relaxed text-slate-500 dark:text-slate-400">{{ $latestActivityInsight }}</p>

                        <div class="insight-summary-grid mt-5 grid gap-3 sm:grid-cols-3">
                            <div class="insight-stat-card rounded-2xl border p-4">
                                <p class="text-[10px] font-black uppercase tracking-widest">Jenis</p>
                                <b class="mt-2 block text-xl font-black">{{ $latestActivityLabel }}</b>
                            </div>
                            <div class="insight-stat-card rounded-2xl border p-4">
                                <p class="text-[10px] font-black uppercase tracking-widest">Status</p>
                                <b class="mt-2 block text-xl font-black">{{ $latestActivityStatus }}</b>
                            </div>
                            <div class="insight-stat-card rounded-2xl border p-4">
                                <p class="text-[10px] font-black uppercase tracking-widest">Nilai</p>
                                <b class="mt-2 block text-xl font-black">{{ $latestActivityScore !== null ? $latestActivityScore : '-' }}</b>
                            </div>
                        </div>
                    </div>

                    <div class="insight-modal-body max-h-[52vh] overflow-y-auto p-5 md:p-6 custom-scrollbar">
                        <div class="grid gap-4 md:grid-cols-[1fr_.95fr]">
                            <section class="insight-detail-card rounded-2xl border p-5">
                                <div class="flex items-start gap-3">
                                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-violet-200 bg-violet-50 text-violet-600 dark:border-violet-400/20 dark:bg-violet-400/10 dark:text-violet-300">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z"/></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-violet-600 dark:text-violet-300">Catatan Terbaru</p>
                                        <h4 class="mt-1 line-clamp-2 text-base font-black text-slate-900 dark:text-white">{{ $latestActivityName }}</h4>
                                        <p class="mt-2 text-xs font-semibold text-slate-500 dark:text-slate-400">{{ $latestActivityDate }}</p>
                                    </div>
                                </div>
                                <div class="insight-card-meta mt-4 grid grid-cols-2 gap-3 text-[11px] font-bold text-slate-500 dark:text-slate-400">
                                    <span>{{ $latestActivityLabel }}</span>
                                    <span class="text-right">{{ $latestActivityStatus }}</span>
                                </div>
                            </section>

                            <section class="rounded-2xl border border-violet-200 bg-violet-50/70 p-5 dark:border-violet-500/20 dark:bg-violet-500/10">
                                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-violet-700 dark:text-violet-300">Lanjutkan Materi</p>
                                <div class="mt-3 flex items-start gap-3">
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-violet-200 bg-white text-[11px] font-black text-violet-600 dark:border-violet-400/20 dark:bg-violet-400/10 dark:text-violet-200">
                                        {{ $nextUnreadLessonBadge }}
                                    </div>
                                    <div class="min-w-0">
                                        <h4 class="line-clamp-2 text-base font-black text-slate-900 dark:text-white">{{ $nextUnreadLessonTitle }}</h4>
                                        <p class="mt-1 text-xs font-semibold text-violet-700/70 dark:text-violet-100/70">{{ $nextUnreadLesson ? 'Belum dibaca' : 'Semua materi terbaca' }}</p>
                                    </div>
                                </div>

                                <a href="{{ $nextUnreadLessonUrl }}" class="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-violet-600 px-4 py-3 text-[10px] font-black uppercase tracking-widest text-white shadow-sm shadow-violet-500/20 transition hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-violet-400/40">
                                    {{ $nextUnreadLessonAction }}
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                                </a>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Modal Insight Materi --}}
            <div x-show="showLessonModal" style="display: none;" class="insight-modal-layer fixed inset-0 z-[99999] flex items-center justify-center p-4 sm:p-6" x-transition.opacity.duration.200ms x-cloak>
                <div class="absolute inset-0 bg-slate-900/55 dark:bg-[#020617]/85 backdrop-blur-md transition-colors" @click="showLessonModal = false" x-transition.opacity></div>
                <div class="insight-modal-panel relative w-full max-w-5xl max-h-[92vh] overflow-hidden rounded-[2rem] bg-white dark:bg-[#0f141e] border border-fuchsia-200/80 dark:border-fuchsia-500/20 shadow-2xl transition-colors" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-5" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-95 translate-y-5">
                    <div class="insight-hero relative overflow-hidden bg-gradient-to-br from-fuchsia-600 via-pink-600 to-rose-500 px-6 py-7 text-white md:px-8">
                        <button type="button" @click="showLessonModal = false" class="insight-close-button" aria-label="Tutup insight" title="Tutup">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                        <p class="text-[10px] font-black uppercase tracking-[0.24em] text-white/70">Insight Materi</p>
                        <h3 class="mt-2 max-w-2xl text-3xl font-black leading-tight md:text-5xl">Materi Bacaan</h3>
                        <div class="insight-summary-grid mt-6 grid gap-3 sm:grid-cols-3">
                            <div class="insight-stat-card rounded-2xl border border-white/20 bg-white/10 p-4 backdrop-blur">
                                <p class="text-[10px] font-black uppercase tracking-widest text-white/65">Tuntas</p>
                                <b class="mt-2 block text-3xl font-black">{{ number_format($lessonsCompleted ?? 0) }}/{{ number_format($totalLessons ?? 0) }}</b>
                            </div>
                            <div class="insight-stat-card rounded-2xl border border-white/20 bg-white/10 p-4 backdrop-blur">
                                <p class="text-[10px] font-black uppercase tracking-widest text-white/65">Progres</p>
                                <b class="mt-2 block text-3xl font-black">{{ number_format($pctLesson ?? 0) }}%</b>
                            </div>
                            <div class="insight-stat-card rounded-2xl border border-white/20 bg-white/10 p-4 backdrop-blur">
                                <p class="text-[10px] font-black uppercase tracking-widest text-white/65">Belum Dibaca</p>
                                <b class="mt-2 block text-3xl font-black">{{ number_format($unreadLessonsCount) }}</b>
                            </div>
                        </div>
                    </div>

                    <div class="insight-modal-body max-h-[58vh] overflow-y-auto p-5 md:p-6 custom-scrollbar">
                        <div class="mb-4 flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-fuchsia-600 dark:text-fuchsia-300">Rincian Materi</p>
                                <h4 class="text-base font-black text-slate-900 dark:text-white">Status bacaan, urut dari awal</h4>
                            </div>
                            <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400">{{ number_format($lessonsCompleted ?? 0) }} tuntas, {{ number_format($unreadLessonsCount) }} belum dibaca</span>
                        </div>
                        <div class="insight-lesson-list max-h-[44vh] space-y-2 overflow-y-auto pr-1 custom-scrollbar">
                            @forelse($lessonInsightItems as $lessonItem)
                                @php $lessonDone = (bool) $lessonItem['completed']; @endphp
                                <div class="insight-detail-card rounded-2xl border p-3.5 transition-colors {{ $lessonDone ? 'border-fuchsia-200 bg-fuchsia-50/70 dark:border-fuchsia-500/20 dark:bg-fuchsia-500/10' : 'insight-detail-card--muted border-slate-200 bg-slate-100/80 text-slate-500 dark:border-white/10 dark:bg-white/[0.03] dark:text-slate-500' }}">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border text-[11px] font-black {{ $lessonDone ? 'border-fuchsia-200 bg-white text-fuchsia-600 dark:border-fuchsia-400/20 dark:bg-fuchsia-400/10 dark:text-fuchsia-300' : 'border-slate-200 bg-slate-200 text-slate-500 dark:border-white/10 dark:bg-white/[0.04]' }}">
                                            {{ $lessonItem['badge_number'] }}
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-center justify-between gap-3">
                                                <h4 class="line-clamp-2 text-sm font-black {{ $lessonDone ? 'text-slate-900 dark:text-white' : 'text-slate-500 dark:text-slate-500' }}">{{ $lessonItem['title'] }}</h4>
                                                <span class="shrink-0 rounded-lg border px-2 py-1 text-[9px] font-black uppercase tracking-wider {{ $lessonDone ? 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-400/20 dark:bg-emerald-400/10 dark:text-emerald-300' : 'border-slate-200 bg-slate-200/80 text-slate-500 dark:border-white/10 dark:bg-white/[0.04]' }}">{{ $lessonItem['status'] }}</span>
                                            </div>
                                            <p class="mt-2 text-[11px] font-semibold {{ $lessonDone ? 'text-fuchsia-700/70 dark:text-fuchsia-100/60' : 'text-slate-400 dark:text-slate-600' }}">{{ $lessonItem['time'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="rounded-2xl border border-dashed border-slate-200 p-8 text-center text-xs font-bold text-slate-400 dark:border-white/10 dark:text-slate-600 md:col-span-2">Data materi belum tersedia.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modal Insight Praktik --}}
            <div x-show="showLabModal" style="display: none;" class="insight-modal-layer fixed inset-0 z-[99999] flex items-center justify-center p-4 sm:p-6" x-transition.opacity.duration.200ms x-cloak>
                <div class="absolute inset-0 bg-slate-900/55 dark:bg-[#020617]/85 backdrop-blur-md transition-colors" @click="showLabModal = false" x-transition.opacity></div>
                <div class="insight-modal-panel relative w-full max-w-5xl max-h-[92vh] overflow-hidden rounded-[2rem] bg-white dark:bg-[#0f141e] border border-blue-200/80 dark:border-blue-500/20 shadow-2xl transition-colors" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-5" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-95 translate-y-5">
                    <div class="insight-hero relative overflow-hidden bg-gradient-to-br from-blue-600 via-cyan-600 to-indigo-600 px-6 py-7 text-white md:px-8">
                        <button type="button" @click="showLabModal = false" class="insight-close-button" aria-label="Tutup insight" title="Tutup">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                        <p class="text-[10px] font-black uppercase tracking-[0.24em] text-white/70">Insight Praktik</p>
                        <h3 class="mt-2 max-w-2xl text-3xl font-black leading-tight md:text-5xl">Praktik</h3>
                        <div class="insight-summary-grid mt-6 grid gap-3 sm:grid-cols-3">
                            <div class="insight-stat-card rounded-2xl border border-white/20 bg-white/10 p-4 backdrop-blur">
                                <p class="text-[10px] font-black uppercase tracking-widest text-white/65">Lulus</p>
                                <b class="mt-2 block text-3xl font-black">{{ number_format($labsCompleted ?? 0) }}/{{ number_format($totalLabs ?? 0) }}</b>
                            </div>
                            <div class="insight-stat-card rounded-2xl border border-white/20 bg-white/10 p-4 backdrop-blur">
                                <p class="text-[10px] font-black uppercase tracking-widest text-white/65">Progres</p>
                                <b class="mt-2 block text-3xl font-black">{{ number_format($pctLab ?? 0) }}%</b>
                            </div>
                            <div class="insight-stat-card rounded-2xl border border-white/20 bg-white/10 p-4 backdrop-blur">
                                <p class="text-[10px] font-black uppercase tracking-widest text-white/65">Belum Lulus</p>
                                <b class="mt-2 block text-3xl font-black">{{ number_format($uncompletedLabsCount) }}</b>
                            </div>
                        </div>
                    </div>

                    <div class="insight-modal-body max-h-[58vh] overflow-y-auto p-5 md:p-6 custom-scrollbar">
                        <div class="mb-4 flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-blue-600 dark:text-blue-300">Rincian Praktik</p>
                                <h4 class="text-base font-black text-slate-900 dark:text-white">Nilai terbaik setiap praktik</h4>
                            </div>
                            <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400">{{ number_format($labsCompleted ?? 0) }} lulus, {{ number_format($uncompletedLabsCount) }} belum lulus</span>
                        </div>
                        <div class="insight-data-grid grid gap-3 md:grid-cols-2">
                            @forelse($labInsightItems as $labItem)
                                @php
                                    $labDone = (bool) $labItem['completed'];
                                    $labScorePercent = $labItem['score'] !== null ? min(100, max(0, (int) round($labItem['score']))) : 0;
                                @endphp
                                <div class="insight-detail-card rounded-2xl border p-4 transition-colors {{ $labDone ? 'border-blue-200 bg-blue-50/70 dark:border-blue-500/20 dark:bg-blue-500/10' : 'insight-detail-card--muted border-slate-200 bg-slate-100/80 text-slate-500 dark:border-white/10 dark:bg-white/[0.03] dark:text-slate-500' }}">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="min-w-0">
                                            <p class="text-[10px] font-black uppercase tracking-widest {{ $labDone ? 'text-blue-600 dark:text-blue-300' : 'text-slate-400 dark:text-slate-600' }}">{{ $labItem['chapter'] }}</p>
                                            <h4 class="mt-1 line-clamp-2 text-sm font-black {{ $labDone ? 'text-slate-900 dark:text-white' : 'text-slate-500 dark:text-slate-500' }}">{{ $labItem['title'] }}</h4>
                                        </div>
                                        <span class="shrink-0 rounded-xl px-3 py-2 text-sm font-black {{ $labDone ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-500 dark:bg-white/[0.05]' }}">{{ $labItem['score'] !== null ? $labItem['score'] : '-' }}</span>
                                    </div>
                                    <div class="mt-4 h-2 overflow-hidden rounded-full bg-slate-200 dark:bg-white/5">
                                        <div class="h-full rounded-full {{ $labDone ? 'bg-blue-500' : 'bg-slate-400' }}" style="width: {{ $labScorePercent }}%"></div>
                                    </div>
                                    <div class="insight-card-meta mt-3 flex items-center justify-between gap-3 text-[11px] font-bold">
                                        <span class="{{ $labDone ? 'text-emerald-600 dark:text-emerald-300' : 'text-slate-400 dark:text-slate-600' }}">{{ $labItem['status'] }}</span>
                                        <span class="text-slate-400 dark:text-slate-600">{{ $labItem['time'] }}</span>
                                    </div>
                                </div>
                            @empty
                                <div class="rounded-2xl border border-dashed border-slate-200 p-8 text-center text-xs font-bold text-slate-400 dark:border-white/10 dark:text-slate-600 md:col-span-2">Data praktik belum tersedia.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modal Insight Kuis --}}
            <div x-show="showQuizModal" style="display: none;" class="insight-modal-layer fixed inset-0 z-[99999] flex items-center justify-center p-4 sm:p-6" x-transition.opacity.duration.200ms x-cloak>
                <div class="absolute inset-0 bg-slate-900/55 dark:bg-[#020617]/85 backdrop-blur-md transition-colors" @click="showQuizModal = false" x-transition.opacity></div>
                <div class="insight-modal-panel relative w-full max-w-5xl max-h-[92vh] overflow-hidden rounded-[2rem] bg-white dark:bg-[#0f141e] border border-cyan-200/80 dark:border-cyan-500/20 shadow-2xl transition-colors" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-5" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-95 translate-y-5">
                    <div class="insight-hero relative overflow-hidden bg-gradient-to-br from-cyan-600 via-sky-600 to-indigo-600 px-6 py-7 text-white md:px-8">
                        <button type="button" @click="showQuizModal = false" class="insight-close-button" aria-label="Tutup insight" title="Tutup">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                        <p class="text-[10px] font-black uppercase tracking-[0.24em] text-white/70">Insight Kuis</p>
                        <h3 class="mt-2 max-w-2xl text-3xl font-black leading-tight md:text-5xl">Rata-rata Evaluasi</h3>
                        <div class="insight-summary-grid mt-6 grid gap-3 sm:grid-cols-3">
                            <div class="insight-stat-card rounded-2xl border border-white/20 bg-white/10 p-4 backdrop-blur">
                                <p class="text-[10px] font-black uppercase tracking-widest text-white/65">Rata-rata</p>
                                <b class="mt-2 block text-3xl font-black">{{ round($quizAverage ?? 0, 1) }}</b>
                            </div>
                            <div class="insight-stat-card rounded-2xl border border-white/20 bg-white/10 p-4 backdrop-blur">
                                <p class="text-[10px] font-black uppercase tracking-widest text-white/65">Percobaan</p>
                                <b class="mt-2 block text-3xl font-black">{{ number_format($quizzesCompleted ?? 0) }}</b>
                            </div>
                            <div class="insight-stat-card rounded-2xl border border-white/20 bg-white/10 p-4 backdrop-blur">
                                <p class="text-[10px] font-black uppercase tracking-widest text-white/65">Tuntas</p>
                                <b class="mt-2 block text-3xl font-black">{{ number_format($chartSummary['passed'] ?? 0) }}</b>
                            </div>
                        </div>
                    </div>

                    <div class="insight-modal-body max-h-[58vh] overflow-y-auto p-5 md:p-6 custom-scrollbar">
                        <div class="mb-4 flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-cyan-600 dark:text-cyan-300">Rincian Kuis</p>
                                <h4 class="text-base font-black text-slate-900 dark:text-white">Nilai terbaik per bab</h4>
                            </div>
                            <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400">{{ number_format($quizzesCompleted ?? 0) }} percobaan tercatat</span>
                        </div>
                        <div class="insight-data-grid grid gap-3 md:grid-cols-2">
                            @forelse($quizInsightItems as $quizItem)
                                @php
                                    $quizDone = $quizItem['status'] === 'Lulus';
                                    $quizScorePercent = $quizItem['best_score'] !== null ? min(100, max(0, (int) round($quizItem['best_score']))) : 0;
                                @endphp
                                <div class="insight-detail-card rounded-2xl border p-4 transition-colors {{ $quizDone ? 'border-cyan-200 bg-cyan-50/70 dark:border-cyan-500/20 dark:bg-cyan-500/10' : 'insight-detail-card--muted border-slate-200 bg-slate-100/80 text-slate-500 dark:border-white/10 dark:bg-white/[0.03] dark:text-slate-500' }}">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <p class="text-[10px] font-black uppercase tracking-widest {{ $quizDone ? 'text-cyan-600 dark:text-cyan-300' : 'text-slate-400 dark:text-slate-600' }}">{{ $quizItem['label'] }}</p>
                                            <h4 class="mt-1 text-sm font-black {{ $quizDone ? 'text-slate-900 dark:text-white' : 'text-slate-500 dark:text-slate-500' }}">Nilai terbaik {{ $quizItem['best_score'] !== null ? $quizItem['best_score'] : '-' }}</h4>
                                        </div>
                                        <span class="shrink-0 rounded-lg border px-2 py-1 text-[9px] font-black uppercase tracking-wider {{ $quizDone ? 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-400/20 dark:bg-emerald-400/10 dark:text-emerald-300' : 'border-slate-200 bg-slate-200/80 text-slate-500 dark:border-white/10 dark:bg-white/[0.04]' }}">{{ $quizItem['status'] }}</span>
                                    </div>
                                    <div class="mt-4 h-2 overflow-hidden rounded-full bg-slate-200 dark:bg-white/5">
                                        <div class="h-full rounded-full {{ $quizDone ? 'bg-cyan-500' : 'bg-slate-400' }}" style="width: {{ $quizScorePercent }}%"></div>
                                    </div>
                                    <div class="insight-card-meta mt-3 grid grid-cols-3 gap-2 text-[10px] font-bold text-slate-500 dark:text-slate-500">
                                        <span>{{ $quizItem['attempts'] }}x coba</span>
                                        <span>Rata {{ $quizItem['average_score'] !== null ? $quizItem['average_score'] : '-' }}</span>
                                        <span>{{ $quizItem['time'] }}</span>
                                    </div>
                                </div>
                            @empty
                                <div class="rounded-2xl border border-dashed border-slate-200 p-8 text-center text-xs font-bold text-slate-400 dark:border-white/10 dark:text-slate-600 md:col-span-2">Data kuis belum tersedia.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modal Insight Bab Lulus --}}
            <div x-show="showChapterModal" style="display: none;" class="insight-modal-layer fixed inset-0 z-[99999] flex items-center justify-center p-4 sm:p-6" x-transition.opacity.duration.200ms x-cloak>
                <div class="absolute inset-0 bg-slate-900/55 dark:bg-[#020617]/85 backdrop-blur-md transition-colors" @click="showChapterModal = false" x-transition.opacity></div>
                <div class="insight-modal-panel relative w-full max-w-5xl max-h-[92vh] overflow-hidden rounded-[2rem] bg-white dark:bg-[#0f141e] border border-emerald-200/80 dark:border-emerald-500/20 shadow-2xl transition-colors" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-5" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-95 translate-y-5">
                    <div class="insight-hero relative overflow-hidden bg-gradient-to-br from-emerald-600 via-teal-600 to-cyan-600 px-6 py-7 text-white md:px-8">
                        <button type="button" @click="showChapterModal = false" class="insight-close-button" aria-label="Tutup insight" title="Tutup">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                        <p class="text-[10px] font-black uppercase tracking-[0.24em] text-white/70">Insight Bab</p>
                        <h3 class="mt-2 max-w-2xl text-3xl font-black leading-tight md:text-5xl">Kelulusan Bab</h3>
                        <div class="insight-summary-grid mt-6 grid gap-3 sm:grid-cols-3">
                            <div class="insight-stat-card rounded-2xl border border-white/20 bg-white/10 p-4 backdrop-blur">
                                <p class="text-[10px] font-black uppercase tracking-widest text-white/65">Bab Lulus</p>
                                <b class="mt-2 block text-3xl font-black">{{ number_format($chaptersPassed ?? 0) }}</b>
                            </div>
                            <div class="insight-stat-card rounded-2xl border border-white/20 bg-white/10 p-4 backdrop-blur">
                                <p class="text-[10px] font-black uppercase tracking-widest text-white/65">Belum Lulus</p>
                                <b class="mt-2 block text-3xl font-black">{{ number_format($chaptersNotPassed) }}</b>
                            </div>
                            <div class="insight-stat-card rounded-2xl border border-white/20 bg-white/10 p-4 backdrop-blur">
                                <p class="text-[10px] font-black uppercase tracking-widest text-white/65">Batas Nilai</p>
                                <b class="mt-2 block text-3xl font-black">70</b>
                            </div>
                        </div>
                    </div>

                    <div class="insight-modal-body max-h-[58vh] overflow-y-auto p-5 md:p-6 custom-scrollbar">
                        <div class="mb-4 flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-emerald-600 dark:text-emerald-300">Rincian Bab</p>
                                <h4 class="text-base font-black text-slate-900 dark:text-white">Ketuntasan berdasarkan nilai kuis</h4>
                            </div>
                            <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400">{{ number_format($chaptersPassed ?? 0) }} lulus, {{ number_format($chaptersNotPassed) }} belum lulus</span>
                        </div>
                        <div class="insight-data-grid grid gap-3 md:grid-cols-2">
                            @forelse($chapterInsightItems as $chapterItem)
                                @php
                                    $chapterDone = (bool) $chapterItem['completed'];
                                    $chapterPercent = $chapterItem['quiz_score'] !== null ? min(100, max(0, (int) round($chapterItem['quiz_score']))) : 0;
                                @endphp
                                <div class="insight-detail-card rounded-2xl border p-4 transition-colors {{ $chapterDone ? 'border-emerald-200 bg-emerald-50/70 dark:border-emerald-500/20 dark:bg-emerald-500/10' : 'insight-detail-card--muted border-slate-200 bg-slate-100/80 text-slate-500 dark:border-white/10 dark:bg-white/[0.03] dark:text-slate-500' }}">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <p class="text-[10px] font-black uppercase tracking-widest {{ $chapterDone ? 'text-emerald-600 dark:text-emerald-300' : 'text-slate-400 dark:text-slate-600' }}">{{ $chapterItem['label'] }}</p>
                                            <h4 class="mt-1 text-sm font-black {{ $chapterDone ? 'text-slate-900 dark:text-white' : 'text-slate-500 dark:text-slate-500' }}">Kuis {{ $chapterItem['quiz_score'] !== null ? $chapterItem['quiz_score'] : '-' }} | Praktik {{ $chapterItem['lab_score'] !== null ? $chapterItem['lab_score'] : '-' }}</h4>
                                        </div>
                                        <span class="shrink-0 rounded-lg border px-2 py-1 text-[9px] font-black uppercase tracking-wider {{ $chapterDone ? 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-400/20 dark:bg-emerald-400/10 dark:text-emerald-300' : 'border-slate-200 bg-slate-200/80 text-slate-500 dark:border-white/10 dark:bg-white/[0.04]' }}">{{ $chapterItem['status'] }}</span>
                                    </div>
                                    <div class="mt-4 h-2 overflow-hidden rounded-full bg-slate-200 dark:bg-white/5">
                                        <div class="h-full rounded-full {{ $chapterDone ? 'bg-emerald-500' : 'bg-slate-400' }}" style="width: {{ $chapterPercent }}%"></div>
                                    </div>
                                    <div class="insight-card-meta mt-3 grid grid-cols-3 gap-2 text-[10px] font-bold text-slate-500 dark:text-slate-500">
                                        <span>{{ $chapterItem['quiz_attempts'] }} kuis</span>
                                        <span>{{ $chapterItem['lab_attempts'] }} praktik</span>
                                        <span>{{ $chapterItem['time'] }}</span>
                                    </div>
                                </div>
                            @empty
                                <div class="rounded-2xl border border-dashed border-slate-200 p-8 text-center text-xs font-bold text-slate-400 dark:border-white/10 dark:text-slate-600 md:col-span-2">Data bab belum tersedia.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modal Hero Tinjauan Kuis --}}
            <div x-show="showQuizReviewModal" style="display: none;" class="fixed inset-0 z-[99999] flex items-center justify-center p-4 sm:p-6" x-transition.opacity.duration.200ms x-cloak>
                <div class="absolute inset-0 bg-slate-900/55 dark:bg-[#020617]/85 backdrop-blur-md transition-colors" @click="showQuizReviewModal = false" x-transition.opacity></div>
                <div class="relative w-full max-w-3xl overflow-hidden rounded-[2rem] bg-white dark:bg-[#0f141e] border border-fuchsia-200 dark:border-fuchsia-500/20 shadow-2xl transition-colors" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0">
                    <div class="relative p-6 md:p-8 bg-gradient-to-br from-fuchsia-600 via-purple-600 to-cyan-600 text-white">
                        <div class="absolute inset-0 bg-black/10"></div>
                        <button @click="showQuizReviewModal = false" class="absolute top-5 right-5 z-10 p-2 rounded-full bg-white/10 hover:bg-white/20 transition focus:outline-none" title="Tutup">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                        <div class="relative z-10 max-w-xl">
                            <p class="text-[10px] uppercase tracking-[0.26em] font-black text-white/65 mb-2">Tinjauan Evaluasi</p>
                            <h3 class="text-2xl md:text-3xl font-black leading-tight" x-text="selectedQuizReview?.title || 'Evaluasi Teori'"></h3>
                            <p class="text-sm text-white/75 mt-3" x-text="selectedQuizReview?.feedbackMessage || 'Ringkasan pengerjaan tersedia untuk ditinjau ulang.'"></p>
                        </div>
                        <div class="absolute right-8 bottom-[-22px] z-10 hidden md:block text-right">
                            <div class="text-7xl font-black leading-none" x-text="selectedQuizReview?.score ?? 0"></div>
                            <div class="text-[10px] uppercase tracking-widest font-bold text-white/70">Nilai Akhir</div>
                        </div>
                    </div>

                    <div class="p-6 md:p-8">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
                            <div class="rounded-2xl bg-slate-50 dark:bg-white/[0.04] border border-slate-200 dark:border-white/10 p-4">
                                <p class="text-[10px] uppercase tracking-widest text-slate-400 font-bold">Status</p>
                                <p class="text-lg font-black mt-1" :class="(selectedQuizReview?.score ?? 0) >= 70 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400'" x-text="selectedQuizReview?.status || '-'"></p>
                            </div>
                            <div class="rounded-2xl bg-slate-50 dark:bg-white/[0.04] border border-slate-200 dark:border-white/10 p-4">
                                <p class="text-[10px] uppercase tracking-widest text-slate-400 font-bold">Durasi</p>
                                <p class="text-lg font-black mt-1 text-slate-900 dark:text-white font-mono" x-text="selectedQuizReview?.duration || '-'"></p>
                            </div>
                            <div class="rounded-2xl bg-slate-50 dark:bg-white/[0.04] border border-slate-200 dark:border-white/10 p-4">
                                <p class="text-[10px] uppercase tracking-widest text-slate-400 font-bold">Terjawab</p>
                                <p class="text-lg font-black mt-1 text-slate-900 dark:text-white"><span x-text="selectedQuizReview?.answered ?? 0"></span> Soal</p>
                            </div>
                            <div class="rounded-2xl bg-slate-50 dark:bg-white/[0.04] border border-slate-200 dark:border-white/10 p-4">
                                <p class="text-[10px] uppercase tracking-widest text-slate-400 font-bold">Ragu-ragu</p>
                                <p class="text-lg font-black mt-1 text-amber-600 dark:text-amber-400"><span x-text="selectedQuizReview?.flagged ?? 0"></span> Soal</p>
                            </div>
                        </div>

                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 rounded-2xl bg-slate-50 dark:bg-[#020617]/60 border border-slate-200 dark:border-white/10 p-4">
                            <div>
                                <p class="text-xs font-bold text-slate-900 dark:text-white" x-text="selectedQuizReview?.feedbackLevel || 'Ringkasan'"></p>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">Dikumpulkan: <span x-text="selectedQuizReview?.date || '-'"></span></p>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400">Soal kosong: <span x-text="selectedQuizReview?.unanswered ?? 0"></span> • Fokus terganggu: <span x-text="selectedQuizReview?.focusLost ?? 0"></span></p>
                                <template x-if="selectedQuizReview?.reflectionNote">
                                    <p class="text-[11px] text-slate-600 dark:text-slate-300 mt-2 italic">"<span x-text="selectedQuizReview.reflectionNote"></span>"</p>
                                </template>
                            </div>
                            <template x-if="selectedQuizReview?.reviewUrl">
                                <a :href="selectedQuizReview.reviewUrl" class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-xs font-black uppercase tracking-widest hover:opacity-90 transition">
                                    Buka Detail
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modal Hero Tinjauan Lab --}}
            <div x-show="showLabReviewModal" style="display: none;" class="fixed inset-0 z-[99999] flex items-center justify-center p-4 sm:p-6" x-transition.opacity.duration.200ms x-cloak>
                <div class="absolute inset-0 bg-slate-900/55 dark:bg-[#020617]/85 backdrop-blur-md transition-colors" @click="showLabReviewModal = false" x-transition.opacity></div>
                <div class="relative w-full max-w-3xl overflow-hidden rounded-[2rem] bg-white dark:bg-[#0f141e] border border-blue-200 dark:border-blue-500/20 shadow-2xl transition-colors" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0">
                    <div class="relative p-6 md:p-8 bg-gradient-to-br from-blue-600 via-cyan-600 to-indigo-600 text-white">
                        <div class="absolute inset-0 bg-black/10"></div>
                        <button @click="showLabReviewModal = false" class="absolute top-5 right-5 z-10 p-2 rounded-full bg-white/10 hover:bg-white/20 transition focus:outline-none" title="Tutup">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                        <div class="relative z-10 max-w-xl">
                            <p class="text-[10px] uppercase tracking-[0.26em] font-black text-white/65 mb-2">Tinjauan Praktik</p>
                            <h3 class="text-2xl md:text-3xl font-black leading-tight" x-text="selectedLabReview?.title || 'Praktik'"></h3>
                            <p class="text-sm text-white/75 mt-3" x-text="selectedLabReview?.feedbackMessage || 'Ringkasan praktik tersedia sebelum membuka detail hasil praktik.'"></p>
                        </div>
                        <div class="absolute right-8 bottom-[-22px] z-10 hidden md:block text-right">
                            <div class="text-7xl font-black leading-none" x-text="selectedLabReview?.score ?? 0"></div>
                            <div class="text-[10px] uppercase tracking-widest font-bold text-white/70">Nilai Praktik</div>
                        </div>
                    </div>

                    <div class="p-6 md:p-8">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
                            <div class="rounded-2xl bg-slate-50 dark:bg-white/[0.04] border border-slate-200 dark:border-white/10 p-4">
                                <p class="text-[10px] uppercase tracking-widest text-slate-400 font-bold">Status</p>
                                <p class="text-lg font-black mt-1" :class="(selectedLabReview?.score ?? 0) >= (selectedLabReview?.passingGrade ?? 70) ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400'" x-text="selectedLabReview?.status || '-'"></p>
                            </div>
                            <div class="rounded-2xl bg-slate-50 dark:bg-white/[0.04] border border-slate-200 dark:border-white/10 p-4">
                                <p class="text-[10px] uppercase tracking-widest text-slate-400 font-bold">Durasi</p>
                                <p class="text-lg font-black mt-1 text-slate-900 dark:text-white font-mono" x-text="selectedLabReview?.duration || '-'"></p>
                            </div>
                            <div class="rounded-2xl bg-slate-50 dark:bg-white/[0.04] border border-slate-200 dark:border-white/10 p-4">
                                <p class="text-[10px] uppercase tracking-widest text-slate-400 font-bold">Tugas</p>
                                <p class="text-lg font-black mt-1 text-slate-900 dark:text-white">
                                    <span x-text="selectedLabReview?.completedSteps ?? 0"></span>/<span x-text="selectedLabReview?.totalSteps ?? 0"></span>
                                </p>
                            </div>
                            <div class="rounded-2xl bg-slate-50 dark:bg-white/[0.04] border border-slate-200 dark:border-white/10 p-4">
                                <p class="text-[10px] uppercase tracking-widest text-slate-400 font-bold">Batas Lulus</p>
                                <p class="text-lg font-black mt-1 text-amber-600 dark:text-amber-400" x-text="selectedLabReview?.passingGrade ?? 70"></p>
                            </div>
                        </div>

                        <div class="rounded-2xl bg-slate-50 dark:bg-[#020617]/60 border border-slate-200 dark:border-white/10 p-4 mb-4">
                            <div class="flex items-center justify-between gap-3 mb-2">
                                <p class="text-xs font-bold text-slate-900 dark:text-white" x-text="selectedLabReview?.feedbackLevel || 'Ringkasan Praktik'"></p>
                                <p class="text-[11px] font-bold text-slate-500 dark:text-slate-400"><span x-text="selectedLabReview?.completionPercent ?? 0"></span>% selesai</p>
                            </div>
                            <div class="h-2 overflow-hidden rounded-full bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10">
                                <div class="h-full rounded-full bg-blue-500 transition-all" :style="'width: ' + Math.min(100, Math.max(0, selectedLabReview?.completionPercent ?? 0)) + '%'"></div>
                            </div>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-3">Dikumpulkan: <span x-text="selectedLabReview?.date || '-'"></span></p>
                        </div>

                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 rounded-2xl bg-white dark:bg-white/[0.03] border border-slate-200 dark:border-white/10 p-4">
                            <div>
                                <p class="text-sm font-black text-slate-900 dark:text-white">Buka detail untuk melihat validasi tugas dan cuplikan kode akhir.</p>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">Popup ini membantu membaca hasil utama terlebih dahulu sebelum masuk ke halaman detail.</p>
                            </div>
                            <template x-if="selectedLabReview?.reviewUrl">
                                <a :href="selectedLabReview.reviewUrl" class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-xs font-black uppercase tracking-widest hover:opacity-90 transition">
                                    Buka Detail Praktik
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            {{-- MODAL PANDUAN DASBOR --}}
            <div x-show="showDashboardInfoModal" style="display: none;" class="fixed inset-0 z-[99999] flex items-center justify-center p-4 sm:p-6" x-transition.opacity.duration.200ms x-cloak>
                <div class="absolute inset-0 bg-slate-900/40 dark:bg-[#020617]/70 backdrop-blur-sm cursor-pointer transition-opacity" @click="showDashboardInfoModal = false" x-transition.opacity></div>
                
                <div class="relative w-full max-w-xl bg-white/90 dark:bg-[#0f141e]/95 backdrop-blur-xl border border-slate-200 dark:border-white/10 rounded-2xl p-8 md:p-10 shadow-xl dark:shadow-2xl transition-all" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-95 translate-y-4">
                    
                    <button @click="showDashboardInfoModal = false" class="absolute top-5 right-5 p-2 rounded-full hover:bg-slate-100 dark:hover:bg-white/5 text-slate-400 hover:text-slate-600 dark:hover:text-white transition-all focus:outline-none">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>

                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 bg-slate-100 dark:bg-white/5 rounded-xl border border-slate-200 dark:border-white/10 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 text-slate-700 dark:text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2-2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-slate-900 dark:text-white leading-tight">Panduan Dasbor Akademik</h3>
                            <p class="text-xs font-medium text-slate-500 dark:text-slate-400 mt-1">Navigasi & Analitik Pembelajaran</p>
                        </div>
                    </div>
                    
                    <div class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed font-medium mb-8">
                        Halaman ini menampilkan progres materi, praktik, evaluasi, dan riwayat aktivitas Anda.
                    </div>
                    
                    <div class="space-y-3">
                        <div class="flex items-start gap-3 p-4 bg-slate-50/50 dark:bg-slate-800/30 rounded-xl border border-slate-100 dark:border-white/5">
                            <span class="text-slate-400 dark:text-slate-500 mt-0.5 font-mono text-xs">01</span>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200">Kartu Ringkasan</h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Kartu materi, praktik, kuis, dan bab menampilkan data ringkas. Pilih kartu untuk melihat rincian.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-4 bg-slate-50/50 dark:bg-slate-800/30 rounded-xl border border-slate-100 dark:border-white/5">
                            <span class="text-slate-400 dark:text-slate-500 mt-0.5 font-mono text-xs">02</span>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200">Grafik Nilai</h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Grafik menampilkan nilai kuis dan praktik pada setiap bab yang telah dikerjakan.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-4 bg-slate-50/50 dark:bg-slate-800/30 rounded-xl border border-slate-100 dark:border-white/5">
                            <span class="text-slate-400 dark:text-slate-500 mt-0.5 font-mono text-xs">03</span>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200">Riwayat Aktivitas</h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Daftar materi, praktik, dan evaluasi yang telah dikerjakan. Gunakan filter untuk melihat kategori aktivitas.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-4 bg-slate-50/50 dark:bg-slate-800/30 rounded-xl border border-slate-100 dark:border-white/5">
                            <span class="text-slate-400 dark:text-slate-500 mt-0.5 font-mono text-xs">04</span>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200">Analitik Pembelajaran</h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Card insight menampilkan interpretasi progres, nilai, aktivitas, durasi, sumber data, dan tindakan belajar berikutnya.</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8">
                        <button @click="showDashboardInfoModal = false" class="w-full py-2.5 bg-slate-900 hover:bg-slate-800 dark:bg-white dark:hover:bg-slate-200 text-white dark:text-slate-900 font-bold text-sm rounded-lg transition-colors focus:outline-none">
                            Tutup Panduan
                        </button>
                    </div>
                </div>
            </div>

            {{-- MODAL GABUNG KELAS --}}
            @empty(Auth::user()->class_group)
            <div x-show="showJoinModal" style="display: none;" class="fixed inset-0 z-[99999] flex items-center justify-center p-4 sm:p-6" x-transition.opacity.duration.200ms x-cloak>
                <div class="absolute inset-0 bg-slate-900/40 dark:bg-[#000000]/60 backdrop-blur-sm transition-colors" @click="showJoinModal = false"></div>
                <div class="relative w-full max-w-sm md:max-w-md bg-white dark:bg-[#0f141e] border border-slate-200/80 dark:border-white/10 rounded-[2rem] p-6 md:p-8 shadow-2xl transition-colors" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0">
                    
                    <div class="flex justify-between items-center mb-6">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-100 dark:border-indigo-500/20 flex items-center justify-center transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <button @click="showJoinModal = false" class="text-slate-400 hover:text-slate-800 dark:hover:text-white transition p-2 rounded-full bg-slate-50 dark:bg-white/5 hover:bg-slate-100 dark:hover:bg-white/10 focus:outline-none"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
                    </div>
                    
                    <h3 class="text-xl md:text-2xl font-bold text-slate-900 dark:text-white mb-2 transition-colors">Gabung Kelas</h3>
                    <p class="text-[13px] text-slate-500 dark:text-slate-400 mb-6 transition-colors">Masukkan kode token akses (6 karakter) yang diberikan oleh instruktur.</p>

                    <form action="{{ route('student.join_class') }}" method="POST" class="space-y-6" x-data="{ isSubmitting: false }" @submit="isSubmitting = true">
                        @csrf
                        <div>
                            <input type="text" name="token" required maxlength="6" style="text-transform: uppercase;" placeholder="KODE" class="w-full bg-slate-50 dark:bg-[#020617] border border-slate-200 dark:border-white/10 rounded-xl px-4 py-4 text-xl font-mono tracking-[0.3em] font-bold text-slate-900 dark:text-white focus:ring-2 ring-indigo-500/40 focus:border-indigo-500 outline-none transition-all placeholder:text-slate-300 dark:placeholder:text-slate-700 placeholder:tracking-normal placeholder:font-sans placeholder:font-normal text-center shadow-inner">
                        </div>

                        <div class="flex justify-end gap-3 pt-2">
                            <button type="button" @click="showJoinModal = false" class="px-5 py-2.5 rounded-xl text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white font-medium text-[13px] transition hover:bg-slate-100 dark:hover:bg-white/5 focus:outline-none">Batal</button>
                            <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 dark:bg-white hover:bg-indigo-700 dark:hover:bg-slate-200 text-white dark:text-slate-900 font-bold text-[13px] shadow-md transition flex items-center justify-center gap-2 focus:outline-none" :disabled="isSubmitting" :class="isSubmitting ? 'opacity-70 cursor-wait' : ''">
                                <svg x-show="isSubmitting" class="animate-spin h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" x-cloak><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                <span x-text="isSubmitting ? 'Memproses...' : 'Gabung Kelas'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endempty

        </main>
    </div>
</div>

{{-- ======================================================================
     STYLES (TOOLTIPS, SCROLLBAR, ANIMATIONS)
     ====================================================================== --}}
<style>
    /* Custom Scrollbar Dinamis */
    .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; } 
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; } 
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(150,150,150,0.3); border-radius: 10px; } 
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(150,150,150,0.5); }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); } 
    .dark .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.2); }

    /* Animasi Utama */
    .animate-spin-slow { animation: spin 8s linear infinite; } 
    @keyframes spin { 100% { transform: rotate(360deg); } }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } } 
    .animate-fade-in-up { animation: fadeInUp 0.4s ease-out forwards; opacity: 0; }
    [x-cloak] { display: none !important; }

    /* Mengembalikan Glass Card Premium */
    .glass-card { background: rgba(255, 255, 255, 0.7); border: 1px solid rgba(0, 0, 0, 0.05); backdrop-filter: blur(20px); }
    .dark .glass-card { background: rgba(10, 14, 23, 0.6); border: 1px solid rgba(255, 255, 255, 0.05); }
    
    /* Hover Z-index Fixer untuk Tooltip */
    .academic-card { z-index: 10; overflow: visible !important; }
    .academic-card:hover { z-index: 50; }

    .dashboard-hero-shell {
        position: relative;
        padding: .25rem 0 .5rem;
    }

    .analytics-summary-grid {
        align-items: stretch;
    }

    .dashboard-progress-card {
        position: relative;
        overflow: hidden !important;
        background:
            radial-gradient(circle at 92% 8%, rgba(6, 182, 212, .12), transparent 34%),
            rgba(255, 255, 255, .94) !important;
    }

    .dark .dashboard-progress-card {
        background:
            radial-gradient(circle at 92% 8%, rgba(6, 182, 212, .12), transparent 34%),
            rgba(15, 20, 30, .96) !important;
    }

    .learner-insight-card {
        --learner-accent: #06b6d4;
        --learner-surface: rgba(6, 182, 212, .10);
        --learner-border: rgba(6, 182, 212, .24);
        position: relative;
        width: 100%;
        overflow: hidden !important;
        border: 1px solid var(--learner-border) !important;
        border-radius: 1.5rem;
        background:
            linear-gradient(135deg, var(--learner-surface), transparent 58%),
            rgba(255, 255, 255, .94);
        color: inherit;
        font: inherit;
        box-shadow: 0 16px 36px -26px rgba(15, 23, 42, .32);
        transition: transform 220ms cubic-bezier(.22, 1, .36, 1), border-color 220ms ease, box-shadow 220ms ease;
    }

    .dark .learner-insight-card {
        background:
            linear-gradient(135deg, var(--learner-surface), transparent 58%),
            rgba(15, 20, 30, .96);
        box-shadow: 0 18px 46px -30px rgba(0, 0, 0, .88);
    }

    .learner-insight-card::before {
        position: absolute;
        top: 0;
        right: 0;
        left: 0;
        height: 3px;
        content: '';
        background: var(--learner-accent);
    }

    .learner-insight-card:hover {
        transform: translateY(-2px);
        border-color: color-mix(in srgb, var(--learner-accent) 42%, rgba(148, 163, 184, .32)) !important;
        box-shadow: 0 18px 42px -26px color-mix(in srgb, var(--learner-accent) 35%, rgba(15, 23, 42, .30));
    }

    .learner-tone-slate {
        --learner-accent: #64748b;
        --learner-surface: rgba(100, 116, 139, .10);
        --learner-border: rgba(100, 116, 139, .24);
    }

    .learner-tone-cyan {
        --learner-accent: #06b6d4;
        --learner-surface: rgba(6, 182, 212, .12);
        --learner-border: rgba(6, 182, 212, .28);
    }

    .learner-tone-blue {
        --learner-accent: #3b82f6;
        --learner-surface: rgba(59, 130, 246, .12);
        --learner-border: rgba(59, 130, 246, .28);
    }

    .learner-tone-fuchsia {
        --learner-accent: #d946ef;
        --learner-surface: rgba(217, 70, 239, .12);
        --learner-border: rgba(217, 70, 239, .28);
    }

    .learner-tone-emerald {
        --learner-accent: #10b981;
        --learner-surface: rgba(16, 185, 129, .12);
        --learner-border: rgba(16, 185, 129, .28);
    }

    .learner-tone-amber {
        --learner-accent: #f59e0b;
        --learner-surface: rgba(245, 158, 11, .14);
        --learner-border: rgba(245, 158, 11, .30);
    }

    .learner-insight-badge {
        display: inline-flex;
        align-items: center;
        border-width: 1px;
        border-radius: .7rem;
        padding: .45rem .65rem;
        font-size: .62rem;
        font-weight: 900;
        line-height: 1.15;
        text-transform: uppercase;
        max-width: 100%;
        white-space: normal;
        text-wrap: balance;
    }

    .learner-insight-progress {
        height: .6rem;
        overflow: hidden;
        border: 1px solid rgba(148, 163, 184, .25);
        border-radius: 999px;
        background: rgba(148, 163, 184, .16);
    }

    .learner-insight-progress > span {
        display: block;
        height: 100%;
        min-width: 2px;
        border-radius: inherit;
        transition: width 700ms cubic-bezier(.22, 1, .36, 1);
    }

    .learner-insight-stat-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: .65rem;
    }

    .learner-insight-stat {
        min-width: 0;
        border: 1px solid color-mix(in srgb, var(--learner-accent) 16%, rgba(148, 163, 184, .25));
        border-radius: 1rem;
        background: color-mix(in srgb, var(--learner-accent) 5%, rgba(248, 250, 252, .92));
        padding: .85rem;
    }

    .learner-insight-stat p {
        overflow-wrap: anywhere;
    }

    .dark .learner-insight-stat {
        background: color-mix(in srgb, var(--learner-accent) 8%, rgba(2, 6, 23, .68));
        border-color: color-mix(in srgb, var(--learner-accent) 24%, rgba(255, 255, 255, .08));
    }

    .insight-modal-layer {
        isolation: isolate;
        perspective: 1200px;
    }

    .insight-modal-panel {
        transform-origin: center;
        box-shadow: 0 32px 90px -48px rgba(15, 23, 42, .70);
    }

    .dark .insight-modal-panel {
        box-shadow: 0 34px 100px -48px rgba(0, 0, 0, .92);
    }

    .insight-hero {
        isolation: isolate;
        border-bottom: 1px solid rgba(226, 232, 240, .86);
        background:
            linear-gradient(135deg, rgba(248, 250, 252, .98), rgba(255, 255, 255, .96)) !important;
        color: #0f172a !important;
        padding: 1.5rem !important;
        padding-right: 4.8rem !important;
    }

    .insight-hero::before {
        display: none;
    }

    .insight-hero::after {
        content: '';
        position: absolute;
        inset: auto 0 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(148, 163, 184, .36), transparent);
        pointer-events: none;
    }

    .insight-hero > * {
        position: relative;
        z-index: 1;
    }

    .dark .insight-hero {
        border-bottom-color: rgba(255, 255, 255, .08);
        background:
            linear-gradient(135deg, rgba(15, 20, 30, .98), rgba(2, 6, 23, .92)) !important;
        color: #f8fafc !important;
    }

    .insight-hero h3 {
        max-width: 42rem;
        margin-top: .35rem !important;
        font-size: clamp(1.35rem, 2vw, 2rem) !important;
        line-height: 1.12 !important;
        letter-spacing: 0 !important;
        color: #0f172a !important;
    }

    .dark .insight-hero h3 {
        color: #ffffff !important;
    }

    .insight-hero p {
        color: #64748b !important;
    }

    .dark .insight-hero p {
        color: #94a3b8 !important;
    }

    .insight-close-button {
        position: absolute;
        top: 1rem;
        right: 1rem;
        z-index: 10;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2.25rem;
        height: 2.25rem;
        padding: 0 !important;
        border-radius: 999px !important;
        border-color: rgba(226, 232, 240, .9) !important;
        background: rgba(248, 250, 252, .95) !important;
        color: #64748b !important;
        box-shadow: 0 10px 28px -24px rgba(15, 23, 42, .38);
        transition: transform 180ms cubic-bezier(.22, 1, .36, 1), background-color 180ms ease, color 180ms ease, border-color 180ms ease, box-shadow 180ms ease;
    }

    .insight-close-button svg {
        width: 1rem;
        height: 1rem;
        stroke-width: 2.4;
    }

    .insight-close-button:hover {
        transform: translateY(-1px) scale(1.03);
        background: #fee2e2 !important;
        color: #dc2626 !important;
        border-color: #fecaca !important;
        box-shadow: 0 16px 30px -22px rgba(220, 38, 38, .55);
    }

    .insight-close-button:focus-visible {
        outline: none;
        box-shadow: 0 0 0 3px rgba(6, 182, 212, .22), 0 10px 28px -24px rgba(15, 23, 42, .38);
    }

    .dark .insight-close-button {
        border-color: rgba(255, 255, 255, .10) !important;
        background: rgba(255, 255, 255, .05) !important;
        color: #94a3b8 !important;
        box-shadow: none;
    }

    .dark .insight-close-button:hover {
        background: rgba(239, 68, 68, .16) !important;
        color: #fca5a5 !important;
        border-color: rgba(248, 113, 113, .28) !important;
        box-shadow: 0 16px 34px -24px rgba(0, 0, 0, .92);
    }

    .insight-modal-body {
        background:
            linear-gradient(180deg, rgba(248, 250, 252, .96), rgba(255, 255, 255, .96));
    }

    .dark .insight-modal-body {
        background:
            linear-gradient(180deg, rgba(2, 6, 23, .72), rgba(15, 20, 30, .96));
    }

    .insight-stat-card {
        min-height: 92px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        border-color: rgba(226, 232, 240, .90) !important;
        background: rgba(248, 250, 252, .92) !important;
        color: #0f172a !important;
        transition: transform 220ms cubic-bezier(.22, 1, .36, 1), background-color 220ms ease, border-color 220ms ease;
    }

    .dark .insight-stat-card {
        border-color: rgba(255, 255, 255, .08) !important;
        background: rgba(255, 255, 255, .04) !important;
        color: #ffffff !important;
    }

    .insight-stat-card p,
    .insight-stat-card span {
        color: #64748b !important;
    }

    .dark .insight-stat-card p,
    .dark .insight-stat-card span {
        color: #94a3b8 !important;
    }

    .insight-stat-card b {
        color: #0f172a !important;
    }

    .dark .insight-stat-card b {
        color: #ffffff !important;
    }

    .insight-stat-card:hover {
        transform: translateY(-2px);
        background-color: rgba(255, 255, 255, .98) !important;
        border-color: rgba(6, 182, 212, .28) !important;
    }

    .dark .insight-stat-card:hover {
        background-color: rgba(255, 255, 255, .07) !important;
        border-color: rgba(103, 232, 249, .20) !important;
    }

    .insight-detail-card {
        position: relative;
        overflow: hidden;
        transform: translateY(0);
        background: rgba(255, 255, 255, .88) !important;
        border-color: rgba(226, 232, 240, .94) !important;
        animation: insightItemIn 420ms cubic-bezier(.22, 1, .36, 1) both;
        transition: transform 220ms cubic-bezier(.22, 1, .36, 1), border-color 220ms ease, box-shadow 220ms ease, background-color 220ms ease, filter 220ms ease;
    }

    .dark .insight-detail-card {
        background: rgba(255, 255, 255, .035) !important;
        border-color: rgba(255, 255, 255, .08) !important;
    }

    .insight-detail-card::before {
        content: '';
        position: absolute;
        inset: 0 0 auto;
        height: 2px;
        opacity: .55;
        background: linear-gradient(90deg, rgba(6, 182, 212, .58), rgba(217, 70, 239, .34), transparent);
        pointer-events: none;
    }

    .insight-detail-card--muted {
        filter: saturate(.82);
        background: rgba(241, 245, 249, .78) !important;
        border-style: dashed;
    }

    .dark .insight-detail-card--muted {
        background: rgba(255, 255, 255, .025) !important;
    }

    .insight-detail-card:hover {
        transform: translateY(-2px);
        border-color: rgba(6, 182, 212, .30);
        box-shadow: 0 18px 38px -30px rgba(15, 23, 42, .48);
    }

    .dark .insight-detail-card:hover {
        border-color: rgba(103, 232, 249, .22);
        box-shadow: 0 20px 42px -32px rgba(0, 0, 0, .95);
    }

    .insight-summary-grid {
        align-items: stretch;
    }

    .insight-data-grid {
        align-items: start;
    }

    .insight-card-meta {
        border-top: 1px solid rgba(226, 232, 240, .78);
        padding-top: .75rem;
    }

    .dark .insight-card-meta {
        border-top-color: rgba(255, 255, 255, .07);
    }

    .insight-detail-card:nth-child(2) { animation-delay: 35ms; }
    .insight-detail-card:nth-child(3) { animation-delay: 70ms; }
    .insight-detail-card:nth-child(4) { animation-delay: 105ms; }
    .insight-detail-card:nth-child(5) { animation-delay: 140ms; }
    .insight-detail-card:nth-child(n + 6) { animation-delay: 175ms; }

    @keyframes insightHeroSheen {
        0%, 56% { transform: translateX(-105%); opacity: 0; }
        64% { opacity: 1; }
        82%, 100% { transform: translateX(105%); opacity: 0; }
    }

    @keyframes insightItemIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* =======================================
       TOOLTIP ULTIMATE CSS (ANTI POTONG)
       ======================================= */
    .tooltip-container { position: relative; display: inline-flex; align-items: center; justify-content: center; z-index: 60; }
    .tooltip-container:hover { z-index: 99999; }
    
    .tooltip-trigger { width: 16px; height: 16px; border-radius: 50%; color: white; font-size: 10px; font-weight: 900; display: flex; align-items: center; justify-content: center; cursor: help; transition: all 0.2s; border: 1px solid rgba(0,0,0,0.1); }
    .dark .tooltip-trigger { border: 1px solid rgba(255,255,255,0.2); }
    .tooltip-trigger:hover { transform: scale(1.15); }
    
    .tooltip-content { opacity: 0; visibility: hidden; position: absolute; pointer-events: none; width: max-content; min-width: 200px; max-width: 250px; white-space: normal; text-align: left; background-color: #ffffff; color: #0f141e; border: 1px solid #e2e8f0; font-size: 11px; padding: 12px 14px; line-height: 1.5; border-radius: 12px; box-shadow: 0 10px 40px -10px rgba(0,0,0,0.15); z-index: 99999; transition: all 0.2s ease-out; font-family: 'Inter', sans-serif;}
    .dark .tooltip-content { background-color: #0a0e17; color: #cbd5e1; border: 1px solid rgba(255,255,255,0.1); box-shadow: 0 10px 40px -10px rgba(0,0,0,0.8); }
    
    /* Arah Bawah */
    .tooltip-down .tooltip-content { top: calc(100% + 10px); left: 50%; transform: translateX(-50%) translateY(-5px); } 
    .tooltip-down:hover .tooltip-content, .tooltip-container:hover > .tooltip-content { transform: translateX(-50%) translateY(0); opacity: 1; visibility: visible; } 
    .tooltip-down .tooltip-content::after { content: ''; position: absolute; bottom: 100%; left: 50%; margin-left: -5px; border-width: 5px; border-style: solid; border-color: transparent transparent #ffffff transparent; } 
    .dark .tooltip-down .tooltip-content::after { border-color: transparent transparent #0a0e17 transparent; }
    
    /* Arah Kiri */
    .tooltip-left .tooltip-content { left: auto; right: calc(100% + 10px); top: 50%; transform: translateY(-50%) translateX(5px); } 
    .tooltip-left:hover .tooltip-content, .tooltip-container:hover > .tooltip-content { transform: translateY(-50%) translateX(0); opacity: 1; visibility: visible; } 
    .tooltip-left .tooltip-content::after { left: 100%; top: 50%; border-width: 5px; border-style: solid; border-color: transparent transparent transparent #ffffff; margin-top: -5px; }
    .dark .tooltip-left .tooltip-content::after { border-color: transparent transparent transparent #0a0e17; }
    
    /* Warna Variants Trigger */
    .tooltip-blue .tooltip-trigger { background-color: #3b82f6; box-shadow: 0 0 10px rgba(59,130,246,0.3); } .tooltip-blue .tooltip-trigger:hover { background-color: #60a5fa; box-shadow: 0 0 15px rgba(59,130,246,0.6); }
    .tooltip-fuchsia .tooltip-trigger { background-color: #d946ef; box-shadow: 0 0 10px rgba(217,70,239,0.3); } .tooltip-fuchsia .tooltip-trigger:hover { background-color: #e879f9; box-shadow: 0 0 15px rgba(217,70,239,0.6); } 
    .tooltip-cyan .tooltip-trigger { background-color: #06b6d4; box-shadow: 0 0 10px rgba(6,182,212,0.3); } .tooltip-cyan .tooltip-trigger:hover { background-color: #22d3ee; box-shadow: 0 0 15px rgba(6,182,212,0.6); } 
    .tooltip-emerald .tooltip-trigger { background-color: #10b981; box-shadow: 0 0 10px rgba(16,185,129,0.3); } .tooltip-emerald .tooltip-trigger:hover { background-color: #34d399; box-shadow: 0 0 15px rgba(16,185,129,0.6); } 
    .tooltip-indigo .tooltip-trigger { background-color: #6366f1; box-shadow: 0 0 10px rgba(99,102,241,0.3); } .tooltip-indigo .tooltip-trigger:hover { background-color: #818cf8; box-shadow: 0 0 15px rgba(99,102,241,0.6); }

    /* ======================================================
       BENTO GRID AKADEMIK
       Struktur data, modal, filter, grafik, dan log tetap sama.
       ====================================================== */
    .dashboard-metrics-bento,
    .academic-bento-grid {
        align-items: start;
    }

    .bento-card {
        min-width: 0;
        isolation: isolate;
        transform: translateZ(0);
        will-change: transform;
    }

    .line-clamp-2 {
        display: -webkit-box;
        overflow: hidden;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
    }

    .bento-metric {
        min-height: 172px;
    }

    .bento-chart-card,
    .bento-history-card,
    .bento-recommendation-card,
    .bento-activity-card,
    .bento-log-card {
        overflow: hidden !important;
    }

    .bento-chart-card {
        min-height: 540px;
    }

    .bento-recommendation-card {
        min-height: 100%;
    }

    .bento-activity-card {
        min-height: 318px;
    }

    @media (hover: hover) and (pointer: fine) {
        .bento-card {
            transition: transform 220ms cubic-bezier(.22, 1, .36, 1), border-color 220ms ease, box-shadow 220ms ease;
        }

        .bento-card:hover {
            transform: translateY(-2px);
            border-color: rgba(6, 182, 212, .28);
            box-shadow: 0 16px 36px -24px rgba(15, 23, 42, .30);
        }

        .dark .bento-card:hover {
            border-color: rgba(103, 232, 249, .22);
            box-shadow: 0 18px 44px -26px rgba(0, 0, 0, .78);
        }
    }

    @media (min-width: 1280px) {
        .dashboard-metrics-bento {
            grid-template-columns: repeat(12, minmax(0, 1fr));
            grid-auto-rows: minmax(172px, auto);
        }

        .bento-metric--lesson,
        .bento-metric--lab {
            grid-column: span 3 / span 3;
        }

        .bento-metric--quiz {
            grid-column: span 4 / span 4;
        }

        .bento-metric--chapter {
            grid-column: span 2 / span 2;
        }

        .bento-metric--target,
        .bento-metric--focus,
        .bento-metric--latest,
        .bento-metric--next {
            grid-column: span 3 / span 3;
        }

        .academic-bento-grid {
            grid-template-columns: repeat(12, minmax(0, 1fr));
            grid-auto-flow: row dense;
            align-items: start;
        }

        .bento-main-column,
        .bento-side-column {
            display: contents;
        }

        .bento-main-column > *,
        .bento-side-column > * {
            margin-top: 0 !important;
        }

        .bento-chart-card,
        .bento-history-card {
            grid-column: span 8 / span 8;
        }

        .bento-recommendation-card,
        .bento-activity-card {
            grid-column: span 4 / span 4;
        }

        .bento-log-card {
            grid-column: 1 / -1;
            min-height: 390px;
        }

        .bento-chart-card {
            min-height: 548px;
        }

        .bento-history-card {
            min-height: 400px;
        }

        .bento-recommendation-card {
            min-height: 548px;
        }

        .bento-activity-card {
            min-height: 400px;
        }
    }

    @media (max-width: 767px) {
        .bento-metric {
            min-height: 156px;
        }

        .bento-chart-card {
            min-height: 0;
        }

        .learner-insight-stat-grid {
            grid-template-columns: 1fr;
        }

        .learner-insight-badge {
            white-space: normal;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .bento-card,
        .bento-card:hover,
        .learner-insight-card,
        .learner-insight-card:hover,
        .insight-stat-card,
        .insight-stat-card:hover,
        .insight-detail-card,
        .insight-detail-card:hover,
        .insight-hero::before {
            animation: none !important;
            transform: none !important;
            transition: none !important;
        }
    }

</style>

{{-- ======================================================================
     SCRIPTS (ALPINE.JS, CHART.JS, JQUERY COUNTER)
     ====================================================================== --}}
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        
        // --- 1. JQUERY COUNTER-UP ANIMATION ---
        $('.counter-value').each(function () {
            $(this).prop('Counter', 0).animate({
                Counter: $(this).text()
            }, {
                duration: 1200,
                easing: 'swing',
                step: function (now) {
                    if ($(this).text().indexOf('.') > -1) {
                        $(this).text((Math.round(now * 10) / 10).toFixed(1));
                    } else {
                        $(this).text(Math.ceil(now));
                    }
                }
            });
        });
        
        $('.counter-modal').each(function () {
            let val = $(this).text();
            $(this).text(val); 
        });

        // --- 2. SWAL ALERTS ---
        const isDark = document.documentElement.classList.contains('dark');
        const swalBg = isDark ? '#0f141e' : '#ffffff';
        const swalColor = isDark ? '#fff' : '#0f141e';

        @if(session('learning_access_error'))
            const learningAccessAlert = @json(session('learning_access_error'));
            Swal.fire({
                icon: 'info',
                title: learningAccessAlert.title || 'Akses Pembelajaran Terkunci',
                html: `<div style="text-align:left;line-height:1.65">
                    <p style="margin-bottom:12px">${learningAccessAlert.message || 'Materi membutuhkan akses kelas aktif.'}</p>
                    <div style="padding:12px;border-radius:14px;background:${document.documentElement.classList.contains('dark') ? 'rgba(255,255,255,.06)' : 'rgba(15,23,42,.04)'};border:1px solid ${document.documentElement.classList.contains('dark') ? 'rgba(255,255,255,.10)' : 'rgba(15,23,42,.08)'}">
                        <strong>Langkah berikutnya:</strong><br>${learningAccessAlert.action || 'Masukkan token kelas melalui dasbor.'}
                    </div>
                </div>`,
                confirmButtonText: 'Mengerti',
                footer: '<a href="{{ route('courses.curriculum') }}" style="font-weight:700;color:#06b6d4">Lihat silabus publik</a>',
                background: swalBg,
                color: swalColor,
                iconColor: '#06b6d4',
                confirmButtonColor: '#0f172a'
            });
        @endif
        @if(session('success')) Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: "{{ session('success') }}", showConfirmButton: false, timer: 3500, background: swalBg, color: swalColor, iconColor: '#10b981' }); @endif
        @if(session('error') && !session('learning_access_error')) Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: "{{ session('error') }}", showConfirmButton: false, timer: 4000, background: swalBg, color: swalColor, iconColor: '#ef4444' }); @endif
        @if(session('info')) Swal.fire({ toast: true, position: 'top-end', icon: 'info', title: "{{ session('info') }}", showConfirmButton: false, timer: 3500, background: swalBg, color: swalColor, iconColor: '#3b82f6' }); @endif

        // --- 3. CHART JS (NILAI KUIS + NILAI PRAKTIK, DATA VALID) ---
        const assessmentCanvas = document.getElementById('assessmentProgressChart');
        let assessmentProgressChart = null;
        let activeAssessmentChartView = 'all';
        let activeAssessmentChartType = 'line';

        const assessmentLabels = {!! json_encode($performanceLabels->values()) !!};
        const assessmentQuizScores = {!! json_encode($quizScoreSeries->values()) !!};
        const assessmentLabScores = {!! json_encode($labScoreSeries->values()) !!};

        function getChartTheme() {
            const dark = document.documentElement.classList.contains('dark');

            return {
                isDark: dark,
                gridColor: dark ? 'rgba(255,255,255,0.06)' : 'rgba(15,23,42,0.06)',
                textColor: dark ? '#94a3b8' : '#64748b',
                tooltipBg: dark ? 'rgba(15, 20, 30, 0.96)' : 'rgba(255, 255, 255, 0.96)',
                tooltipTitle: dark ? '#ffffff' : '#0f172a',
                tooltipBody: dark ? '#cbd5e1' : '#475569',
                pointBg: dark ? '#0f141e' : '#ffffff'
            };
        }

        function buildAssessmentDataset(ctx, type, label, scores, color, gradientColor, hidden = false) {
            if (type === 'bar') {
                return {
                    label: label,
                    data: scores,
                    backgroundColor: color.replace('1)', '0.72)'),
                    borderColor: color,
                    borderWidth: 0,
                    borderRadius: 9,
                    borderSkipped: false,
                    maxBarThickness: 38,
                    hidden: hidden
                };
            }

            const theme = getChartTheme();
            const gradient = ctx.createLinearGradient(0, 0, 0, 320);
            gradient.addColorStop(0, gradientColor.replace('0.34', '0.34'));
            gradient.addColorStop(0.55, gradientColor.replace('0.34', '0.11'));
            gradient.addColorStop(1, gradientColor.replace('0.34', '0'));

            return {
                label: label,
                data: scores,
                borderColor: color,
                backgroundColor: gradient,
                borderWidth: 3,
                pointBackgroundColor: theme.pointBg,
                pointBorderColor: color,
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 7,
                pointHoverBackgroundColor: color,
                pointHoverBorderColor: '#ffffff',
                fill: true,
                tension: 0.42,
                spanGaps: false,
                hidden: hidden
            };
        }

        function buildAssessmentOptions(type) {
            const theme = getChartTheme();

            return {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 850,
                    easing: 'easeOutQuart'
                },
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: theme.tooltipBg,
                        titleColor: theme.tooltipTitle,
                        bodyColor: theme.tooltipBody,
                        borderColor: theme.gridColor,
                        borderWidth: 1,
                        padding: 12,
                        displayColors: true,
                        usePointStyle: true,
                        titleFont: {
                            family: 'Inter',
                            size: 12,
                            weight: '800'
                        },
                        bodyFont: {
                            family: 'Inter',
                            size: 11,
                            weight: '600'
                        },
                        callbacks: {
                            title: function (items) {
                                return items[0]?.label || 'Performa Akademik';
                            },
                            label: function (context) {
                                if (context.raw === null || context.raw === undefined) {
                                    return context.dataset.label + ': belum ada data';
                                }

                                return context.dataset.label + ': ' + context.raw + ' nilai';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: theme.textColor,
                            font: {
                                family: 'JetBrains Mono',
                                size: 10,
                                weight: '600'
                            }
                        },
                        border: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        max: 100,
                        grid: {
                            color: theme.gridColor,
                            drawBorder: false
                        },
                        ticks: {
                            color: theme.textColor,
                            stepSize: 20,
                            callback: function (value) {
                                return value + ' nilai';
                            },
                            font: {
                                family: 'JetBrains Mono',
                                size: 10,
                                weight: '600'
                            }
                        },
                        border: {
                            display: false
                        }
                    }
                }
            };
        }

        function renderAssessmentChart(view = 'all', type = 'line') {
            if (!assessmentCanvas || assessmentLabels.length === 0) {
                return;
            }

            const ctx = assessmentCanvas.getContext('2d');

            if (assessmentProgressChart) {
                assessmentProgressChart.destroy();
            }

            assessmentProgressChart = new Chart(ctx, {
                type: type,
                data: {
                    labels: assessmentLabels,
                    datasets: [
                        buildAssessmentDataset(
                            ctx,
                            type,
                            'Nilai Kuis',
                            assessmentQuizScores,
                            'rgba(6, 182, 212, 1)',
                            'rgba(6, 182, 212, 0.34)',
                            view === 'lab'
                        ),
                        buildAssessmentDataset(
                            ctx,
                            type,
                            'Nilai Praktik',
                            assessmentLabScores,
                            'rgba(59, 130, 246, 1)',
                            'rgba(59, 130, 246, 0.34)',
                            view === 'quiz'
                        )
                    ]
                },
                options: buildAssessmentOptions(type)
            });
        }

        window.updateAssessmentChartView = function (view) {
            activeAssessmentChartView = view;

            if (!assessmentProgressChart) {
                return;
            }

            assessmentProgressChart.data.datasets[0].hidden = view === 'lab';
            assessmentProgressChart.data.datasets[1].hidden = view === 'quiz';
            assessmentProgressChart.update();
        };

        window.updateAssessmentChartType = function (type) {
            activeAssessmentChartType = type;
            renderAssessmentChart(activeAssessmentChartView, activeAssessmentChartType);
        };

        renderAssessmentChart(activeAssessmentChartView, activeAssessmentChartType);

        // --- 4. PIE CHART ACTIVITY COMPOSITION ---
        const pieCtx = document.getElementById('activityPieChart')?.getContext('2d');
        if(pieCtx && ({{ $lessonsCompleted ?? 0 }} > 0 || {{ $labsCompleted ?? 0 }} > 0 || {{ $quizzesCompleted ?? 0 }} > 0)) {
            const pieTheme = getChartTheme();

            new Chart(pieCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Materi Bacaan', 'Praktik', 'Evaluasi Kuis'],
                    datasets: [{
                        data: [{{ $lessonsCompleted ?? 0 }}, {{ $labsCompleted ?? 0 }}, {{ $quizzesCompleted ?? 0 }}],
                        backgroundColor: [
                            'rgba(217, 70, 239, 0.86)',
                            'rgba(59, 130, 246, 0.86)',
                            'rgba(6, 182, 212, 0.86)'
                        ],
                        borderColor: pieTheme.isDark ? '#0f141e' : '#ffffff',
                        borderWidth: 2,
                        hoverOffset: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '74%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: pieTheme.tooltipBg,
                            titleColor: pieTheme.tooltipTitle,
                            bodyColor: pieTheme.tooltipBody,
                            bodyFont: { family: 'Inter', size: 11, weight: '700' },
                            padding: 10,
                            borderColor: pieTheme.gridColor,
                            borderWidth: 1,
                            callbacks: {
                                label: function(context) {
                                    return ' ' + context.label + ': ' + context.raw + ' selesai';
                                }
                            }
                        }
                    }
                }
            });
        }

        // Render Live Riwayat Aktivitas
        const liveLogs = @json($liveLogData ?? []);
        renderActivityLog(liveLogs);
    });

    function renderActivityLog(logs) {
        const list = document.getElementById('activityLogList'); if(!list) return; list.innerHTML = '';
        
        if (logs.length === 0) { 
            list.innerHTML = `<li class="text-slate-400 dark:text-slate-600 text-center text-xs italic py-10 transition-colors">Belum ada aktivitas akademik tercatat.</li>`; 
            return; 
        }

        logs.forEach((item, index) => {
            let typeLower = item.type ? item.type.toLowerCase() : '';
            let activityName = item.name || ''; 
            
            // Default: Materi SVG
            let icon = '<svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>'; 
            let iconBg = 'bg-fuchsia-50 dark:bg-fuchsia-500/10 text-fuchsia-600 dark:text-fuchsia-400 border-fuchsia-100 dark:border-fuchsia-500/10';
            let typeLabel = 'Membaca Materi';
            let typeColor = 'text-fuchsia-600 dark:text-fuchsia-400';

            // Kuis SVG
            if (typeLower === 'kuis' || typeLower === 'quiz') { 
                icon = '<svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>'; 
                iconBg = 'bg-cyan-50 dark:bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 border-cyan-100 dark:border-cyan-500/10'; 
                typeLabel = 'Evaluasi Kuis';
                typeColor = 'text-cyan-600 dark:text-cyan-400';
            } 
            // Praktik SVG
            else if (typeLower === 'lab')  { 
                icon = '<svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>'; 
                iconBg = 'bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 border-blue-100 dark:border-blue-500/10'; 
                typeLabel = 'Praktik';
                typeColor = 'text-blue-600 dark:text-blue-400';
            }

            const delay = (index > 15 ? 0 : index) * 50; // Lebih cepat
            const statusDisplay = item.status === 'Lulus' ? 'text-emerald-600 dark:text-emerald-400' : (item.status === 'Selesai' || item.status === 'Tuntas' ? 'text-indigo-600 dark:text-indigo-400' : 'text-red-600 dark:text-red-400');

            list.insertAdjacentHTML('beforeend', `
                <li x-show="logFilter === 'all' || logFilter === '${typeLower === 'quiz' ? 'kuis' : typeLower}'" x-transition.opacity.duration.300ms class="group flex items-center gap-3 p-2.5 rounded-xl hover:bg-slate-50 dark:hover:bg-white/[0.02] transition-colors border border-transparent dark:hover:border-white/5 animate-fade-in-up" style="animation-delay: ${delay}ms">
                    <div class="w-7 h-7 rounded-md ${iconBg} border flex items-center justify-center shrink-0 transition-colors">${icon}</div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-center gap-2">
                            <h4 class="text-[12px] font-bold text-slate-800 dark:text-white truncate transition-colors pr-2 leading-none group-hover:text-cyan-600 dark:group-hover:text-cyan-400" title="${activityName}">${activityName}</h4>
                            ${item.status ? `<span class="text-[10px] font-bold shrink-0 transition-colors ${statusDisplay}">${item.status}</span>` : ''}
                        </div>
                        <div class="flex items-center gap-1.5 mt-1 text-[10px]">
                            <span class="font-bold transition-colors ${typeColor}">${typeLabel}</span>
                            <span class="w-1 h-1 rounded-full bg-slate-300 dark:bg-white/20 transition-colors"></span>
                            <span class="text-slate-500 dark:text-slate-500 font-mono transition-colors" title="${item.full_date || item.date || ''}">${item.time || ''}</span>
                        </div>
                    </div>
                </li>
            `);
        });
    }
</script>
@endsection
