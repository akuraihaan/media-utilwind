@extends('layouts.landing')

@section('title', 'Dashboard Akademik')

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

    // Total materi dari tabel lessons jika controller belum mengirimkan.
    try {
        if ((!isset($totalLessons) || (int) $totalLessons === 0) && class_exists(\App\Models\Lesson::class)) {
            $totalLessons = \App\Models\Lesson::count();
        }
    } catch (\Throwable $e) {
        $totalLessons = $totalLessons ?? 0;
    }

    // Total lab dari tabel labs jika controller belum mengirimkan.
    try {
        if ((!isset($totalLabs) || (int) $totalLabs === 0) && class_exists(\App\Models\Lab::class)) {
            $totalLabs = \App\Models\Lab::count();
        }
    } catch (\Throwable $e) {
        $totalLabs = $totalLabs ?? 0;
    }

    // Materi selesai.
    try {
        if (class_exists(\App\Models\UserLessonProgress::class)) {
            $lessonProgressQuery = \App\Models\UserLessonProgress::where('user_id', $userId)
                ->where('completed', true);

            if ((!isset($lessonsCompleted) || (int) $lessonsCompleted === 0)) {
                $lessonsCompleted = (clone $lessonProgressQuery)->count();
            }

            $allLessons = (clone $lessonProgressQuery)
                ->with('lesson')
                ->get()
                ->sortBy(fn($m) => $m->lesson->order ?? $m->lesson_id)
                ->values()
                ->map(function ($m) {
                    $urutan = $m->lesson->order ?? $m->lesson_id;

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
                    ];
                });
        }
    } catch (\Throwable $e) {
        $allLessons = collect();
    }

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
                    'status' => $q->score >= 70 ? 'Lulus' : 'Remedial',
                    'duration' => (int) ($q->time_spent_seconds ?? 0),
                    'duration_text' => gmdate(((int) ($q->time_spent_seconds ?? 0)) >= 3600 ? 'H:i:s' : 'i:s', max(0, (int) ($q->time_spent_seconds ?? 0))),
                    'answered_count' => (int) ($q->answered_count ?? 0),
                    'unanswered_count' => (int) ($q->unanswered_count ?? 0),
                    'flagged_count' => (int) ($q->flagged_count ?? 0),
                    'focus_lost_count' => (int) ($q->focus_lost_count ?? 0),
                    'feedback_level' => $q->feedback_level ?? ($q->score >= 70 ? 'Lulus' : 'Perlu Penguatan'),
                    'feedback_message' => $q->feedback_message ?? ($q->score >= 70 ? 'Evaluasi sudah memenuhi KKM. Tinjau kembali soal yang salah untuk memperkuat pemahaman.' : 'Skor belum mencapai KKM. Pelajari kembali materi dan ulangi evaluasi setelah latihan.'),
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
                ->with('lab')
                ->latest('updated_at')
                ->get()
                ->map(fn($l) => [
                    'name' => 'Praktik Lab: ' . ($l->lab->title ?? 'Modul ' . $l->lab_id),
                    'type' => 'lab',
                    'score' => $l->final_score,
                    'lab_id' => $l->lab_id,
                    'date' => $l->updated_at,
                    'full_date' => \Carbon\Carbon::parse($l->updated_at)->format('d M Y, H:i'),
                    'time' => \Carbon\Carbon::parse($l->updated_at)->diffForHumans(),
                    'status' => ($l->status === 'passed' || $l->final_score >= 70) ? 'Lulus' : 'Remedial',
                ]);
        }
    } catch (\Throwable $e) {
        $allLabs = collect();
    }

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
        'remedial' => $allQuizzes->where('score', '<', 70)->count(),
    ];

    $latestQuiz = $allQuizzes->sortByDesc('date')->first();
    $latestLab = $allLabs->sortByDesc('date')->first();
    $learningRecommendations = collect();
    $riskScore = 0;

    if ($latestQuiz && ($latestQuiz['score'] ?? 0) < 70) {
        $riskScore += 2;
        $learningRecommendations->push([
            'title' => 'Perkuat evaluasi terakhir',
            'body' => 'Nilai evaluasi terakhir belum mencapai KKM. Buka tinjauan hasil, pelajari bagian yang salah, lalu ulangi setelah latihan singkat.',
            'tone' => 'red',
            'action' => 'Tinjau Hasil',
            'url' => $latestQuiz['review_url'] ?? null,
        ]);
    }

    if ($latestQuiz && (($latestQuiz['flagged_count'] ?? 0) > 0)) {
        $riskScore += 1;
        $learningRecommendations->push([
            'title' => 'Tinjau soal ragu-ragu',
            'body' => 'Masih ada soal yang ditandai ragu-ragu. Gunakan daftar tinjauan untuk memastikan konsepnya benar-benar dipahami.',
            'tone' => 'amber',
            'action' => 'Buka Detail',
            'url' => $latestQuiz['review_url'] ?? null,
        ]);
    }

    if (($pctLesson ?? 0) < 75) {
        $learningRecommendations->push([
            'title' => 'Lanjutkan materi bacaan',
            'body' => 'Progress materi belum penuh. Selesaikan materi berikutnya sebelum mengejar evaluasi baru agar dasar konsep lebih stabil.',
            'tone' => 'cyan',
            'action' => 'Buka Kurikulum',
            'url' => route('courses.curriculum'),
        ]);
    }

    if (($totalLabs ?? 0) > 0 && ($labsCompleted ?? 0) < ($totalLabs ?? 0)) {
        $learningRecommendations->push([
            'title' => 'Lengkapi praktik lab',
            'body' => 'Masih ada lab yang belum lulus. Praktik akan membantu menguatkan pemahaman dari materi dan evaluasi teori.',
            'tone' => 'blue',
            'action' => 'Lihat Kurikulum',
            'url' => route('courses.curriculum'),
        ]);
    }

    if ($allQuizzes->isEmpty() && ($lessonsCompleted ?? 0) > 0) {
        $learningRecommendations->push([
            'title' => 'Mulai evaluasi pertama',
            'body' => 'Anda sudah memiliki progress materi. Ambil evaluasi bab yang sudah terbuka untuk mengukur pemahaman.',
            'tone' => 'fuchsia',
            'action' => 'Buka Kurikulum',
            'url' => route('courses.curriculum'),
        ]);
    }

    if ($learningRecommendations->isEmpty()) {
        $learningRecommendations->push([
            'title' => 'Pertahankan ritme belajar',
            'body' => 'Progress dan hasil evaluasi terlihat stabil. Lanjutkan materi berikutnya dan gunakan riwayat evaluasi untuk menjaga konsistensi.',
            'tone' => 'emerald',
            'action' => 'Lanjut Belajar',
            'url' => route('courses.curriculum'),
        ]);
    }

    if (($chartSummary['remedial'] ?? 0) >= 2 || ($quizAverage ?? 0) < 70) {
        $riskScore += 1;
    }

    $learningRisk = $riskScore >= 3
        ? ['label' => 'Perlu Perhatian', 'class' => 'text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-500/10 border-red-200 dark:border-red-500/20']
        : ($riskScore >= 1
            ? ['label' => 'Perlu Penguatan', 'class' => 'text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-500/10 border-amber-200 dark:border-amber-500/20']
            : ['label' => 'Stabil', 'class' => 'text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10 border-emerald-200 dark:border-emerald-500/20']);

    $learningRecommendations = $learningRecommendations->take(3)->values();


    /*
    |--------------------------------------------------------------------------
    | DATA GRAFIK VALID: NILAI KUIS + NILAI LAB
    |--------------------------------------------------------------------------
    | - Kuis memakai skor terbaik per chapter_id.
    | - Lab memakai skor terbaik per chapter_id jika tersedia; jika tidak, fallback ke lab_id.
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
                'label' => is_numeric($chapterKey) ? 'Bab ' . $chapterKey : 'Lab',
                'title' => $best->lab->title ?? 'Lab Praktik',
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
            showQuizReviewModal: false,
            selectedQuizReview: null
         }"
         @keydown.escape.window="sidebarOpen = false; showJoinModal = false; showLessonModal = false; showLabModal = false; showQuizModal = false; showChapterModal = false; showDashboardInfoModal = false; showQuizReviewModal = false;">

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
                        <div class="w-full group flex items-center justify-between px-3 py-2.5 rounded-lg text-slate-400 dark:text-slate-600 cursor-not-allowed transition-colors" title="Anda belum bergabung di kelas manapun">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                                <span class="text-[14px] font-medium">Materi Belajar</span>
                            </div>
                            <svg class="w-3.5 h-3.5 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
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
                     1. HERO SECTION & OVERALL PROGRESS
                     ========================================================= --}}
                <div class="flex flex-col xl:flex-row justify-between items-start gap-8">
                    <div class="flex-1 w-full">
                        {{-- BREADCRUMB --}}
                        <nav class="flex items-center gap-2 mb-4 text-[11px] font-bold uppercase tracking-widest text-slate-500 dark:text-slate-500 transition-colors" aria-label="Breadcrumb">
                            <a href="/" class="hover:text-slate-900 dark:hover:text-white transition-colors flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                Home
                            </a>
                            <span class="text-slate-300 dark:text-slate-700 transition-colors">/</span>
                            <span class="text-cyan-600 dark:text-cyan-400 transition-colors">Dashboard Akademik</span>
                        </nav>

                        {{-- HEADLINE & PANDUAN BUTTON --}}
                        <div class="flex items-center gap-4 mb-3 reveal-up">
                            <h1 class="text-3xl md:text-4xl lg:text-5xl font-black text-slate-900 dark:text-white tracking-tight transition-colors">
                                <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-600 to-indigo-600 dark:from-cyan-400 dark:to-indigo-400">Dashboard</span> 
                            </h1>
                            <button @click="showDashboardInfoModal = true" class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-500 dark:text-slate-400 hover:text-cyan-600 dark:hover:text-cyan-400 hover:bg-white dark:hover:bg-white/10 hover:border-cyan-200 dark:hover:border-cyan-500/30 transition-all duration-300 shadow-sm hover:shadow-md focus:outline-none group" title="Panduan Dasbor">
                                <svg class="w-4 h-4 md:w-5 md:h-5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </button>
                        </div>
                        <p class="text-slate-600 dark:text-slate-400 text-sm md:text-base transition-colors max-w-2xl reveal-up delay-100">Pantau pencapaian materi, hasil evaluasi, dan analitik kinerja belajar.</p>
                        
                        <div class="mt-6 flex flex-wrap items-center gap-4 reveal-up delay-200">
                            {{-- Status Kelas Badge --}}
                            <div class="inline-flex items-center gap-4 px-4 py-3 rounded-2xl bg-white/50 dark:bg-white/[0.02] backdrop-blur-sm border border-slate-200/80 dark:border-white/[0.05] shadow-sm w-full md:w-auto transition-colors">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-cyan-500 flex items-center justify-center font-bold text-white shadow-sm text-lg shrink-0">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-[10px] text-slate-500 dark:text-slate-400 uppercase tracking-widest font-bold mb-0.5 transition-colors">Status Kelas</p>
                                    <div class="flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full shrink-0 {{ Auth::user()->class_group ? 'bg-emerald-500 shadow-[0_0_8px_#10b981]' : 'bg-yellow-500 shadow-[0_0_8px_#eab308] animate-pulse' }}"></span>
                                        <span class="text-[13px] md:text-sm font-bold text-slate-800 dark:text-white truncate transition-colors">{{ Auth::user()->class_group ?? 'Belum Terhubung ke Kelas' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- OVERALL ACADEMIC PROGRESS CARD --}}
                    <div class="w-full xl:w-96 glass-card rounded-[2rem] p-6 md:p-8 relative group hover:border-cyan-400/50 dark:hover:border-cyan-500/30 transition-colors duration-500 shrink-0 reveal-up delay-100 shadow-sm hover:shadow-md dark:shadow-none">
                        <div class="absolute inset-0 rounded-[2rem] overflow-hidden pointer-events-none">
                            <div class="absolute -right-12 -top-12 w-40 h-40 bg-cyan-400/10 dark:bg-cyan-500/10 rounded-full blur-[40px] group-hover:bg-cyan-400/20 dark:group-hover:bg-cyan-500/20 transition-colors"></div>
                        </div>
                        
                        <div class="flex justify-between items-end mb-4 relative z-10">
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <p class="text-[10px] uppercase tracking-widest text-slate-500 dark:text-slate-400 font-bold transition-colors">Total Progress</p>
                                    <div class="tooltip-container tooltip-blue tooltip-down">
                                        <div class="tooltip-trigger">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        </div>
                                        <div class="tooltip-content">Kalkulasi dari persentase penyelesaian seluruh <b>Materi Bacaan</b> dan <b>Modul Lab Praktikum</b> yang wajib diselesaikan.</div>
                                    </div>
                                </div>
                                <h3 class="text-4xl font-black text-slate-900 dark:text-white tracking-tight transition-colors"><span class="counter-value">{{ $overallProgress }}</span><span class="text-xl text-slate-400 dark:text-slate-500 ml-0.5">%</span></h3>
                            </div>
                            <div class="text-right">
                                <svg class="w-8 h-8 text-cyan-500/80" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                        </div>

                        <div class="w-full h-2.5 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden border border-slate-200/50 dark:border-white/5 relative z-10 transition-colors">
                            <div class="h-full bg-gradient-to-r from-cyan-400 to-blue-500 dark:from-cyan-500 dark:to-blue-600 shadow-none dark:shadow-[0_0_10px_#06b6d4] transition-all duration-1000 relative" style="width: {{ $overallProgress }}%">
                                <div class="absolute inset-0 bg-white/30 animate-[shimmer_2s_infinite]"></div>
                            </div>
                        </div>
                        <div class="flex justify-between items-center mt-3 text-[10px] font-bold text-slate-500 dark:text-slate-500 relative z-10 transition-colors">
                            <span>0%</span>
                            <span><span class="counter-value">{{ $completedTasks }}</span> / {{ $totalTasks }} Modul</span>
                            <span>100%</span>
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
                                    <h3 class="font-bold text-[13px] text-indigo-900 dark:text-indigo-100 transition-colors leading-tight">Lab Sedang Berjalan: {{ $activeSession->lab->title ?? 'Praktikum' }}</h3>
                                    <p class="text-indigo-600/80 dark:text-indigo-300 text-[11px] transition-colors mt-0.5 font-medium">Sesi ngoding Anda masih menggantung. Lanjutkan sekarang!</p>
                                </div>
                            </div>
                            <a href="{{ route('lab.workspace', $activeSession->lab_id) }}" class="w-full sm:w-auto px-5 py-2 bg-indigo-600 dark:bg-indigo-500 hover:bg-indigo-700 dark:hover:bg-indigo-400 text-white text-center font-bold rounded-lg text-[13px] transition shadow-sm flex items-center justify-center gap-2 shrink-0 focus:outline-none">
                                Lanjut Coding <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        </div>
                    @endif
                </div>

                {{-- VISUAL SEPARATOR --}}
                <div class="flex items-center gap-4 py-2 reveal-up delay-300">
                    <div class="h-px bg-slate-200 dark:bg-white/[0.05] flex-1 transition-colors"></div>
                    <span class="text-[10px] font-black text-slate-400 dark:text-slate-600 uppercase tracking-[0.2em] px-3 py-1 transition-colors">Analitik Akademik</span>
                    <div class="h-px bg-slate-200 dark:bg-white/[0.05] flex-1 transition-colors"></div>
                </div>

                {{-- =========================================================
                     2. GRID STATISTIK AKADEMIK (ULTIMATE CARDS)
                     ========================================================= --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 reveal-up delay-300">
                    
                    {{-- CARD 1: MATERI --}}
                    <div class="academic-card group bg-white dark:bg-[#0f141e] rounded-[1.5rem] p-5 relative flex flex-col justify-between border border-slate-200/80 dark:border-white/[0.05] hover:border-fuchsia-300 dark:hover:border-fuchsia-500/30 shadow-sm hover:shadow-md dark:shadow-none transition-all duration-300 cursor-pointer" @click="showLessonModal = true">
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
                    <div class="academic-card group bg-white dark:bg-[#0f141e] rounded-[1.5rem] p-5 relative flex flex-col justify-between border border-slate-200/80 dark:border-white/[0.05] hover:border-blue-300 dark:hover:border-blue-500/30 shadow-sm hover:shadow-md dark:shadow-none transition-all duration-300 cursor-pointer" @click="showLabModal = true">
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-50/50 dark:from-blue-500/5 to-transparent opacity-0 group-hover:opacity-100 transition duration-300 pointer-events-none rounded-[1.5rem]"></div>
                        <div class="relative z-10">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center border border-blue-100 dark:border-blue-500/20 transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                                    </div>
                                    <p class="text-[10px] font-bold text-slate-500 dark:text-slate-500 uppercase tracking-widest group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">Hands-on Labs</p>
                                </div>
                                <div class="tooltip-container tooltip-blue tooltip-down" @click.stop>
                                    <div class="tooltip-trigger"><svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></div>
                                    <div class="tooltip-content">Praktikum per bab yang berhasil diselesaikan dengan nilai kelulusan minimal 70.</div>
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
                    <div class="academic-card group bg-white dark:bg-[#0f141e] rounded-[1.5rem] p-5 relative flex flex-col justify-between border border-slate-200/80 dark:border-white/[0.05] hover:border-cyan-300 dark:hover:border-cyan-500/30 shadow-sm hover:shadow-md dark:shadow-none transition-all duration-300 cursor-pointer" @click="showQuizModal = true">
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
                                        <div class="tooltip-content">Nilai rata-rata dari seluruh percobaan evaluasi teori (Kuis) yang pernah Anda ikuti.</div>
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
                    <div class="academic-card group bg-white dark:bg-[#0f141e] rounded-[1.5rem] p-5 relative flex flex-col justify-between border border-slate-200/80 dark:border-white/[0.05] hover:border-emerald-300 dark:hover:border-emerald-500/30 shadow-sm hover:shadow-md dark:shadow-none transition-all duration-300 cursor-pointer" @click="showChapterModal = true">
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
                            <p class="text-[10px] text-emerald-600 dark:text-emerald-500 mt-4 font-bold uppercase tracking-wider transition-colors border-t border-slate-100 dark:border-white/[0.05] pt-3 group-hover:tracking-widest duration-300">Lanjutkan!</p>
                        </div>
                    </div>
                </div>

                {{-- =========================================================
                     3. CHART & LOGS PURE ACADEMIC
                     ========================================================= --}}
                <div class="grid lg:grid-cols-3 gap-6 md:gap-8 reveal-up delay-400 mt-2">
                    
                    {{-- KIRI: GRAFIK & TABEL (2 Kolom) --}}
                    <div class="lg:col-span-2 space-y-6 md:space-y-8">
                        
                        {{-- GRAFIK PERKEMBANGAN NILAI KUIS DAN LAB - Data Valid + Adaptasi Chart ZIP --}}
                        <div class="academic-card rounded-[1.5rem] bg-white dark:bg-[#0f141e] border border-slate-200/80 dark:border-white/[0.05] overflow-hidden shadow-sm dark:shadow-none transition-colors duration-500"
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
                                                Academic Performance
                                            </span>

                                            <div class="tooltip-container tooltip-cyan tooltip-down">
                                                <div class="tooltip-trigger">
                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </div>
                                                <div class="tooltip-content">
                                                    Grafik membandingkan nilai terbaik kuis dan lab pada setiap bab. Data kosong ditampilkan sebagai jeda, bukan angka 0.
                                                </div>
                                            </div>
                                        </div>

                                        <h3 class="text-[17px] md:text-xl font-black text-slate-900 dark:text-white tracking-tight transition-colors">
                                            Grafik Perkembangan Nilai Kuis dan Lab
                                        </h3>

                                        <p class="text-[11px] md:text-xs text-slate-500 dark:text-slate-400 mt-1 transition-colors font-medium max-w-xl">
                                            Nilai diambil dari skor terbaik tiap bab agar perkembangan belajar lebih valid.
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
                                                Lab
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
                                                Line
                                            </button>

                                            <button
                                                type="button"
                                                @click="chartType = 'bar'; updateAssessmentChartType('bar')"
                                                :class="chartType === 'bar'
                                                    ? 'bg-slate-900 dark:bg-white text-white dark:text-[#020617] shadow-md'
                                                    : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'"
                                                class="px-3.5 py-2 rounded-lg text-[10px] font-black transition focus:outline-none">
                                                Bar
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
                                        <p class="text-[9px] uppercase tracking-widest font-bold text-slate-400 dark:text-slate-500">Avg Kuis</p>
                                        <p class="mt-1 text-lg font-black text-cyan-600 dark:text-cyan-400">{{ $quizChartAverage !== null ? $quizChartAverage : '-' }}</p>
                                    </div>

                                    <div class="rounded-2xl bg-slate-50/80 dark:bg-[#020617]/70 border border-slate-200/70 dark:border-white/5 px-4 py-3">
                                        <p class="text-[9px] uppercase tracking-widest font-bold text-slate-400 dark:text-slate-500">Avg Lab</p>
                                        <p class="mt-1 text-lg font-black text-blue-600 dark:text-blue-400">{{ $labChartAverage !== null ? $labChartAverage : '-' }}</p>
                                    </div>

                                    <div class="rounded-2xl bg-slate-50/80 dark:bg-[#020617]/70 border border-slate-200/70 dark:border-white/5 px-4 py-3">
                                        <p class="text-[9px] uppercase tracking-widest font-bold text-slate-400 dark:text-slate-500">Best Kuis</p>
                                        <p class="mt-1 text-lg font-black text-emerald-600 dark:text-emerald-400">{{ $quizChartHighest !== null ? $quizChartHighest : '-' }}</p>
                                    </div>

                                    <div class="rounded-2xl bg-slate-50/80 dark:bg-[#020617]/70 border border-slate-200/70 dark:border-white/5 px-4 py-3">
                                        <p class="text-[9px] uppercase tracking-widest font-bold text-slate-400 dark:text-slate-500">Best Lab</p>
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
                                            <p class="text-[11px] font-bold text-slate-400 dark:text-slate-500">Belum Ada Data Kuis atau Lab</p>
                                            <p class="text-[10px] text-slate-400 dark:text-slate-600 mt-1">Grafik muncul setelah evaluasi atau lab diselesaikan.</p>
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
                                            Nilai Lab
                                        </span>
                                    </div>

                                    <p>Data kosong tidak dianggap 0.</p>
                                </div>
                            </div>
                        </div>

                        {{-- TABEL HISTORY EVALUASI --}}
                        <div class="rounded-[1.5rem] bg-white dark:bg-[#0f141e] border border-slate-200/80 dark:border-white/[0.05] p-5 md:p-6 flex flex-col h-[400px] shadow-sm dark:shadow-none transition-colors duration-500" x-data="{ filterTable: 'all' }">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4 shrink-0 border-b border-slate-100 dark:border-white/[0.05] pb-4 transition-colors">
                                <h3 class="text-[15px] md:text-[16px] font-bold text-slate-900 dark:text-white flex items-center gap-2 transition-colors">
                                    Riwayat Evaluasi
                                </h3>
                                
                                {{-- Filter Interaktif AlpineJS --}}
                                <div class="flex items-center bg-slate-100/80 dark:bg-[#020617] p-1 rounded-lg border border-slate-200/50 dark:border-white/5 transition-colors">
                                    <button @click="filterTable = 'all'" :class="filterTable === 'all' ? 'bg-cyan-500 text-white shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white'" class="px-3 py-1.5 rounded-md text-[11px] font-semibold transition focus:outline-none">Semua</button>
                                    <button @click="filterTable = 'kuis'" :class="filterTable === 'kuis' ? 'bg-fuchsia-500 text-white shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white'" class="px-3 py-1.5 rounded-md text-[11px] font-semibold transition focus:outline-none">Kuis</button>
                                    <button @click="filterTable = 'lab'" :class="filterTable === 'lab' ? 'bg-blue-500 text-white shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white'" class="px-3 py-1.5 rounded-md text-[11px] font-semibold transition focus:outline-none">Lab</button>
                                </div>
                            </div>
                            
                            <div class="overflow-x-auto custom-scrollbar -mx-5 md:mx-0 px-5 md:px-0 flex-1 relative">
                                <div class="absolute top-0 bottom-0 right-0 w-4 bg-gradient-to-l from-white dark:from-[#0f141e] to-transparent pointer-events-none md:hidden z-10 transition-colors duration-500"></div>
                                <div class="h-full overflow-y-auto custom-scrollbar pr-2 pb-4">
                                    <table class="w-full text-left border-collapse min-w-[400px]">
                                        <thead class="sticky top-0 z-20 bg-white dark:bg-[#0f141e] after:absolute after:bottom-0 after:left-0 after:right-0 after:h-px after:bg-slate-100 dark:after:bg-white/[0.05] transition-colors duration-500">
                                            <tr class="text-[10px] text-slate-400 dark:text-slate-500 uppercase tracking-widest transition-colors font-bold">
                                                <th class="py-3 pl-2">Aktivitas Ujian</th>
                                                <th class="py-3 hidden sm:table-cell">Waktu Submit</th>
                                                <th class="py-3 text-right pr-2">Skor Akhir</th>
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
                                                        $typeLabel = 'Praktik Lab';
                                                        $typeColor = 'text-blue-600 dark:text-blue-400';
                                                        $iconBg = 'bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 border-blue-100 dark:border-blue-500/10';
                                                        $icon = '<svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>';
                                                    }
                                                @endphp
                                                @php
                                                    $quizReviewPayload = null;
                                                    if ($typeLower == 'kuis' || $typeLower == 'quiz') {
                                                        $quizReviewPayload = [
                                                            'title' => $item['name'] ?? 'Evaluasi Teori',
                                                            'score' => $item['score'] ?? 0,
                                                            'status' => $item['status'] ?? (($item['score'] ?? 0) >= 70 ? 'Lulus' : 'Remedial'),
                                                            'chapter' => $item['chapter_id'] ?? null,
                                                            'date' => $item['full_date'] ?? '-',
                                                            'time' => $item['time'] ?? '-',
                                                            'duration' => $item['duration_text'] ?? '-',
                                                            'answered' => $item['answered_count'] ?? 0,
                                                            'unanswered' => $item['unanswered_count'] ?? 0,
                                                            'flagged' => $item['flagged_count'] ?? 0,
                                                            'focusLost' => $item['focus_lost_count'] ?? 0,
                                                            'feedbackLevel' => $item['feedback_level'] ?? (($item['score'] ?? 0) >= 70 ? 'Lulus' : 'Perlu Penguatan'),
                                                            'feedbackMessage' => $item['feedback_message'] ?? '',
                                                            'reflectionNote' => $item['reflection_note'] ?? '',
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
                                                            <span class="px-2.5 py-1 rounded-md text-[11px] font-bold border transition-colors {{ $item['score'] >= 70 ? 'text-emerald-700 bg-emerald-50 dark:text-emerald-400 dark:bg-emerald-500/10 border-emerald-200 dark:border-emerald-500/10' : 'text-red-700 bg-red-50 dark:text-red-400 dark:bg-red-500/10 border-red-200 dark:border-red-500/10' }}">{{ $item['score'] }} pts</span>
                                                        @endif
                                                        @if($quizReviewPayload)
                                                            <button type="button"
                                                                    @click.stop="selectedQuizReview = {{ \Illuminate\Support\Js::from($quizReviewPayload) }}; showQuizReviewModal = true"
                                                                    class="mt-1 inline-flex items-center justify-end gap-1 text-[10px] font-bold text-fuchsia-600 dark:text-fuchsia-400 hover:text-fuchsia-700 dark:hover:text-fuchsia-300 transition-colors focus:outline-none">
                                                                Tinjau
                                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                                            </button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="3" class="py-8 text-center text-slate-400 dark:text-slate-600 italic text-[13px] transition-colors">Belum ada riwayat pengerjaan kuis atau lab.</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- KANAN: AKTIVITAS PIE CHART & LIVE LOG (1 Kolom) --}}
                    <div class="lg:col-span-1 space-y-6 md:space-y-8">

                        {{-- Rekomendasi Belajar Otomatis --}}
                        <div class="academic-card rounded-[1.5rem] bg-white dark:bg-[#0f141e] border border-slate-200/80 dark:border-white/[0.05] p-5 md:p-6 shadow-sm dark:shadow-none transition-colors duration-500">
                            <div class="flex items-start justify-between gap-3 mb-5">
                                <div>
                                    <p class="text-[10px] uppercase tracking-widest font-black text-slate-400 dark:text-slate-500 mb-1">Arahan Belajar</p>
                                    <h3 class="text-[15px] md:text-[16px] font-bold text-slate-900 dark:text-white transition-colors">
                                        Rekomendasi Berikutnya
                                    </h3>
                                </div>
                                <span class="px-3 py-1.5 rounded-lg border text-[10px] font-black uppercase tracking-widest {{ $learningRisk['class'] }}">
                                    {{ $learningRisk['label'] }}
                                </span>
                            </div>

                            <div class="space-y-3">
                                @foreach($learningRecommendations as $index => $rec)
                                    @php
                                        $tone = $rec['tone'] ?? 'cyan';
                                        $toneClass = match($tone) {
                                            'red' => 'border-red-200 dark:border-red-500/20 bg-red-50/70 dark:bg-red-500/[0.06] text-red-600 dark:text-red-400',
                                            'amber' => 'border-amber-200 dark:border-amber-500/20 bg-amber-50/70 dark:bg-amber-500/[0.06] text-amber-600 dark:text-amber-400',
                                            'blue' => 'border-blue-200 dark:border-blue-500/20 bg-blue-50/70 dark:bg-blue-500/[0.06] text-blue-600 dark:text-blue-400',
                                            'fuchsia' => 'border-fuchsia-200 dark:border-fuchsia-500/20 bg-fuchsia-50/70 dark:bg-fuchsia-500/[0.06] text-fuchsia-600 dark:text-fuchsia-400',
                                            'emerald' => 'border-emerald-200 dark:border-emerald-500/20 bg-emerald-50/70 dark:bg-emerald-500/[0.06] text-emerald-600 dark:text-emerald-400',
                                            default => 'border-cyan-200 dark:border-cyan-500/20 bg-cyan-50/70 dark:bg-cyan-500/[0.06] text-cyan-600 dark:text-cyan-400',
                                        };
                                    @endphp
                                    <div class="rounded-2xl border {{ $toneClass }} p-4 transition-colors">
                                        <div class="flex items-start gap-3">
                                            <div class="w-7 h-7 rounded-lg bg-white/70 dark:bg-white/10 flex items-center justify-center text-[11px] font-black shrink-0">
                                                {{ $index + 1 }}
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <h4 class="text-sm font-black text-slate-900 dark:text-white">{{ $rec['title'] }}</h4>
                                                <p class="text-xs leading-relaxed text-slate-600 dark:text-slate-400 mt-1">{{ $rec['body'] }}</p>
                                                @if(!empty($rec['url']))
                                                    <a href="{{ $rec['url'] }}" class="inline-flex items-center gap-1.5 mt-3 text-[10px] font-black uppercase tracking-widest hover:opacity-80 transition">
                                                        {{ $rec['action'] ?? 'Buka' }}
                                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        
                        {{-- Komposisi Aktivitas --}}
                        <div class="academic-card rounded-[1.5rem] bg-white dark:bg-[#0f141e] border border-slate-200/80 dark:border-white/[0.05] p-5 md:p-6 shadow-sm dark:shadow-none transition-colors duration-500 relative">
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
                                    <p class="text-[9px] text-slate-500 dark:text-slate-500 uppercase tracking-widest font-bold">Lab</p>
                                    <p class="text-[15px] font-black text-blue-500 counter-value">{{ $labsCompleted ?? 0 }}</p>
                                </div>
                                <div>
                                    <p class="text-[9px] text-slate-500 dark:text-slate-500 uppercase tracking-widest font-bold">Kuis</p>
                                    <p class="text-[15px] font-black text-cyan-500 counter-value">{{ $quizzesCompleted ?? 0 }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Log Real-time Aktivitas --}}
                        <div class="rounded-[1.5rem] bg-white dark:bg-[#0f141e] border border-slate-200/80 dark:border-white/[0.05] p-5 md:p-6 h-[400px] flex flex-col relative overflow-hidden shadow-sm dark:shadow-none transition-colors duration-500" x-data="{ logFilter: 'all' }">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 border-b border-slate-100 dark:border-white/[0.05] pb-4 shrink-0 gap-3 transition-colors">
                                <h3 class="text-[14px] md:text-[15px] font-bold text-slate-900 dark:text-white transition-colors">
                                    Log Terkini
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
                    <p class="text-slate-500 dark:text-slate-500 text-[11px] font-medium">&copy; {{ date('Y') }} Utilwind CSS Academic Platform</p>
                </div>
            </div>

            {{-- =========================================================================
                 MODAL INSIGHT ANALITIK & PANDUAN DASBOR
                 ========================================================================= --}}
            
            {{-- Modal Insight Materi --}}
            <div x-show="showLessonModal" class="fixed inset-0 z-[99999] flex items-center justify-center p-4 sm:p-6" x-cloak>
                <div class="absolute inset-0 bg-slate-900/40 dark:bg-[#000000]/60 backdrop-blur-sm transition-colors" @click="showLessonModal = false" x-transition.opacity></div>
                <div class="relative w-full max-w-sm md:max-w-md bg-white dark:bg-[#0f141e] border border-slate-200/80 dark:border-white/10 rounded-[2rem] p-6 md:p-8 shadow-2xl transition-colors flex flex-col max-h-[85vh]" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0">
                    
                    <div class="flex justify-between items-start mb-4 md:mb-6 shrink-0">
                        <div class="w-10 h-10 rounded-xl bg-fuchsia-50 dark:bg-fuchsia-500/10 text-fuchsia-600 dark:text-fuchsia-400 border border-fuchsia-100 dark:border-fuchsia-500/20 flex items-center justify-center transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                        </div>
                        <button @click="showLessonModal = false" class="text-slate-400 hover:text-slate-800 dark:hover:text-white transition p-2 rounded-full bg-slate-50 dark:bg-white/5 hover:bg-slate-100 dark:hover:bg-white/10 focus:outline-none"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
                    </div>
                    
                    <h3 class="text-xl md:text-2xl font-bold text-slate-900 dark:text-white mb-2 transition-colors shrink-0">Materi Bacaan</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-[13px] leading-relaxed mb-4 transition-colors shrink-0">Jumlah modul teori yang telah diselesaikan dari keseluruhan materi kurikulum.</p>
                    
                    <div class="bg-slate-50 dark:bg-[#020617] rounded-2xl p-4 md:p-5 border border-slate-100 dark:border-white/5 text-center transition-colors mb-6 shrink-0 flex items-center justify-center gap-4">
                        <div class="text-right border-r border-slate-200 dark:border-white/10 pr-4">
                            <span class="text-3xl md:text-4xl font-black text-fuchsia-600 dark:text-fuchsia-400 transition-colors counter-modal">{{ $lessonsCompleted ?? 0 }}</span>
                            <span class="text-base text-slate-400 dark:text-slate-600 font-bold transition-colors">/ {{ $totalLessons ?? 0 }}</span>
                        </div>
                        <div class="text-left">
                            <p class="text-[9px] text-slate-500 dark:text-slate-500 uppercase tracking-widest font-bold mb-0.5 transition-colors">Penyelesaian</p>
                            <p class="text-lg font-black text-slate-800 dark:text-white transition-colors">{{ $pctLesson ?? 0 }}%</p>
                        </div>
                    </div>

                    <div class="flex-1 overflow-y-auto custom-scrollbar pr-2 relative">
                        <div class="sticky top-0 bg-white dark:bg-[#0f141e] z-20 pb-2 border-b border-slate-100 dark:border-white/5 mb-2 transition-colors">
                            <h4 class="text-[11px] font-bold text-slate-500 dark:text-slate-500 uppercase tracking-wider">Jejak Penyelesaian</h4>
                        </div>
                        <ul class="space-y-1.5 pb-4">
                            @forelse($allLessons as $lessonLog)
                                <li class="group flex items-center gap-3 p-2.5 rounded-xl hover:bg-slate-50 dark:hover:bg-white/[0.02] transition-colors border border-transparent dark:hover:border-white/5 relative overflow-hidden">
                                    <div class="w-8 h-8 rounded-md bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-slate-400 font-bold text-[11px] flex items-center justify-center shrink-0 transition-colors font-mono">
                                        {{ $lessonLog['badge_number'] }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-[12px] font-bold text-slate-800 dark:text-white truncate transition-colors group-hover:text-fuchsia-600 dark:group-hover:text-fuchsia-400" title="{{ $lessonLog['name'] }}">
                                            {{ str_replace('Materi Bacaan: ', '', $lessonLog['name']) }}
                                        </h4>
                                        <div class="flex items-center gap-1.5 mt-0.5 text-[10px] font-medium text-slate-500 dark:text-slate-500">
                                            <span>{{ $lessonLog['time'] }}</span>
                                        </div>
                                    </div>
                                </li>
                            @empty
                                <div class="text-center py-8">
                                    <p class="text-slate-400 dark:text-slate-600 text-xs font-medium transition-colors">Belum ada materi yang diselesaikan.</p>
                                </div>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Modal Insight Lab --}}
            <div x-show="showLabModal" class="fixed inset-0 z-[99999] flex items-center justify-center p-4 sm:p-6" x-cloak>
                <div class="absolute inset-0 bg-slate-900/40 dark:bg-[#000000]/60 backdrop-blur-sm transition-colors" @click="showLabModal = false" x-transition.opacity></div>
                <div class="relative w-full max-w-sm md:max-w-md bg-white dark:bg-[#0f141e] border border-slate-200/80 dark:border-white/10 rounded-[2rem] p-6 md:p-8 shadow-2xl transition-colors" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0">
                    <div class="flex justify-between items-start mb-4 md:mb-6">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-blue-500/20 flex items-center justify-center transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                        </div>
                        <button @click="showLabModal = false" class="text-slate-400 hover:text-slate-800 dark:hover:text-white transition p-2 rounded-full bg-slate-50 dark:bg-white/5 hover:bg-slate-100 dark:hover:bg-white/10 focus:outline-none"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
                    </div>
                    <h3 class="text-xl md:text-2xl font-bold text-slate-900 dark:text-white mb-2 transition-colors">Detail Praktikum</h3>
                    <p class="text-slate-600 dark:text-slate-400 text-[13px] leading-relaxed mb-6 transition-colors">Sebuah lab dinyatakan <span class="text-emerald-600 dark:text-emerald-400 font-bold">Lulus</span> secara otomatis jika validasi sistem menunjukkan kode Anda mencapai skor minimal 70.</p>
                    
                    <div class="bg-slate-50 dark:bg-[#020617] rounded-2xl p-5 md:p-6 border border-slate-100 dark:border-white/5 text-center transition-colors">
                        <span class="text-4xl md:text-5xl font-black text-blue-600 dark:text-blue-400 transition-colors counter-modal">{{ $labsCompleted ?? 0 }}</span>
                        <span class="text-lg md:text-xl text-slate-400 dark:text-slate-600 font-bold transition-colors">/ {{ $totalLabs ?? 0 }}</span>
                        <p class="text-[10px] text-slate-500 dark:text-slate-500 uppercase tracking-widest font-bold mt-2 transition-colors">Modul Diselesaikan</p>
                    </div>
                </div>
            </div>

            {{-- Modal Insight Kuis --}}
            <div x-show="showQuizModal" class="fixed inset-0 z-[99999] flex items-center justify-center p-4 sm:p-6" x-cloak>
                <div class="absolute inset-0 bg-slate-900/40 dark:bg-[#000000]/60 backdrop-blur-sm transition-colors" @click="showQuizModal = false" x-transition.opacity></div>
                <div class="relative w-full max-w-sm md:max-w-md bg-white dark:bg-[#0f141e] border border-slate-200/80 dark:border-white/10 rounded-[2rem] p-6 md:p-8 shadow-2xl transition-colors" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0">
                    <div class="flex justify-between items-start mb-4 md:mb-6">
                        <div class="w-10 h-10 rounded-xl bg-cyan-50 dark:bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 border border-cyan-100 dark:border-cyan-500/20 flex items-center justify-center transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        </div>
                        <button @click="showQuizModal = false" class="text-slate-400 hover:text-slate-800 dark:hover:text-white transition p-2 rounded-full bg-slate-50 dark:bg-white/5 hover:bg-slate-100 dark:hover:bg-white/10 focus:outline-none"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
                    </div>
                    <h3 class="text-xl md:text-2xl font-bold text-slate-900 dark:text-white mb-2 transition-colors">Rata-rata Evaluasi</h3>
                    <p class="text-slate-600 dark:text-slate-400 text-[13px] leading-relaxed mb-6 transition-colors">Akumulasi total nilai dibagi frekuensi pengerjaan. Anda telah men-submit evaluasi sebanyak <b class="text-slate-800 dark:text-white">{{ $quizzesCompleted ?? 0 }} kali</b>.</p>
                    
                    <div class="bg-slate-50 dark:bg-[#020617] rounded-2xl p-5 md:p-6 border border-slate-100 dark:border-white/5 text-center transition-colors">
                        <span class="text-4xl md:text-5xl font-black text-cyan-600 dark:text-cyan-400 transition-colors counter-modal">{{ round($quizAverage ?? 0, 1) }}</span>
                        <p class="text-[10px] text-slate-500 dark:text-slate-500 uppercase tracking-widest font-bold mt-2 transition-colors">Poin Rata-Rata</p>
                    </div>
                </div>
            </div>

            {{-- Modal Insight Bab Lulus --}}
            <div x-show="showChapterModal" class="fixed inset-0 z-[99999] flex items-center justify-center p-4 sm:p-6" x-cloak>
                <div class="absolute inset-0 bg-slate-900/40 dark:bg-[#000000]/60 backdrop-blur-sm transition-colors" @click="showChapterModal = false" x-transition.opacity></div>
                <div class="relative w-full max-w-sm md:max-w-md bg-white dark:bg-[#0f141e] border border-slate-200/80 dark:border-white/10 rounded-[2rem] p-6 md:p-8 shadow-2xl transition-colors" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0">
                    <div class="flex justify-between items-start mb-4 md:mb-6">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-500/20 flex items-center justify-center transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <button @click="showChapterModal = false" class="text-slate-400 hover:text-slate-800 dark:hover:text-white transition p-2 rounded-full bg-slate-50 dark:bg-white/5 hover:bg-slate-100 dark:hover:bg-white/10 focus:outline-none"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
                    </div>
                    <h3 class="text-xl md:text-2xl font-bold text-slate-900 dark:text-white mb-2 transition-colors">Kelulusan Bab</h3>
                    <p class="text-slate-600 dark:text-slate-400 text-[13px] leading-relaxed mb-6 transition-colors">Bab kurikulum dinyatakan lulus dan terbuka untuk tahap selanjutnya apabila nilai kuis akhir mencapai <b class="text-slate-800 dark:text-white">minimal 70</b>.</p>
                    
                    <div class="bg-slate-50 dark:bg-[#020617] rounded-2xl p-5 md:p-6 border border-slate-100 dark:border-white/5 text-center transition-colors">
                        <span class="text-4xl md:text-5xl font-black text-emerald-600 dark:text-emerald-400 transition-colors counter-modal">{{ $chaptersPassed ?? 0 }}</span>
                        <p class="text-[10px] text-slate-500 dark:text-slate-500 uppercase tracking-widest font-bold mt-2 transition-colors">Bab Diselesaikan</p>
                    </div>
                </div>
            </div>

            {{-- Modal Hero Tinjauan Kuis --}}
            <div x-show="showQuizReviewModal" class="fixed inset-0 z-[99999] flex items-center justify-center p-4 sm:p-6" x-cloak>
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
                            <div class="text-[10px] uppercase tracking-widest font-bold text-white/70">Skor Akhir</div>
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

            {{-- MODAL PANDUAN DASBOR --}}
            <div x-show="showDashboardInfoModal" class="fixed inset-0 z-[99999] flex items-center justify-center p-4 sm:p-6" x-cloak>
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
                        Halaman ini adalah pusat kendali akademik Anda. Gunakan metrik yang tersedia untuk memantau progress belajar dan hasil evaluasi.
                    </div>
                    
                    <div class="space-y-3">
                        <div class="flex items-start gap-3 p-4 bg-slate-50/50 dark:bg-slate-800/30 rounded-xl border border-slate-100 dark:border-white/5">
                            <span class="text-slate-400 dark:text-slate-500 mt-0.5 font-mono text-xs">01</span>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200">Kartu Insight Interaktif</h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Metrik utama (Materi, Lab, Kuis, Bab) dapat diklik untuk membuka rincian spesifik dan jejak penyelesaian dari masing-masing kategori.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-4 bg-slate-50/50 dark:bg-slate-800/30 rounded-xl border border-slate-100 dark:border-white/5">
                            <span class="text-slate-400 dark:text-slate-500 mt-0.5 font-mono text-xs">02</span>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200">Grafik Perkembangan</h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Visualisasi tren nilai dari waktu ke waktu. Membantu Anda memonitor konsistensi pemahaman materi pada setiap bab.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-4 bg-slate-50/50 dark:bg-slate-800/30 rounded-xl border border-slate-100 dark:border-white/5">
                            <span class="text-slate-400 dark:text-slate-500 mt-0.5 font-mono text-xs">03</span>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200">Log Aktivitas & Riwayat</h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Daftar rekaman langsung (real-time) setiap tindakan akademik yang Anda lakukan, lengkap dengan filter kategori untuk pencarian cepat.</p>
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
            <div x-show="showJoinModal" class="fixed inset-0 z-[99999] flex items-center justify-center p-4 sm:p-6" x-cloak>
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

        @if(session('success')) Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: "{{ session('success') }}", showConfirmButton: false, timer: 3500, background: swalBg, color: swalColor, iconColor: '#10b981' }); @endif
        @if(session('error')) Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: "{{ session('error') }}", showConfirmButton: false, timer: 4000, background: swalBg, color: swalColor, iconColor: '#ef4444' }); @endif
        @if(session('info')) Swal.fire({ toast: true, position: 'top-end', icon: 'info', title: "{{ session('info') }}", showConfirmButton: false, timer: 3500, background: swalBg, color: swalColor, iconColor: '#3b82f6' }); @endif

        // --- 3. CHART JS (NILAI KUIS + NILAI LAB, DATA VALID) ---
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

                                return context.dataset.label + ': ' + context.raw + ' poin';
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
                                return value + ' pts';
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
                            'Nilai Lab',
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
                    labels: ['Materi Bacaan', 'Praktik Lab', 'Evaluasi Kuis'],
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

        // Render Live Log Terkini
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
            // Lab SVG
            else if (typeLower === 'lab')  { 
                icon = '<svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>'; 
                iconBg = 'bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 border-blue-100 dark:border-blue-500/10'; 
                typeLabel = 'Praktik Lab';
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
