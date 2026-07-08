<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tinjauan Hasil Kuis · {{ $student->name ?? 'Siswa' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        mono: ['DM Mono', 'monospace'],
                    },
                    boxShadow: {
                        soft: '0 18px 60px -28px rgba(15, 23, 42, 0.30)',
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; background: #f8fafc; color: #0f172a; }
        .font-mono { font-family: 'DM Mono', monospace; font-variant-numeric: tabular-nums; }
        .data-number { font-variant-numeric: tabular-nums lining-nums; font-feature-settings: 'tnum' 1, 'lnum' 1; }
        .review-scroll::-webkit-scrollbar { width: 8px; height: 8px; }
        .review-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 999px; }
        .review-scroll::-webkit-scrollbar-track { background: transparent; }
        .answer-rich-text :is(p, ul, ol) { margin-top: .65rem; }
        .answer-rich-text :is(ul, ol) { padding-left: 1.25rem; }
        .answer-rich-text code { overflow-wrap: anywhere; border-radius: .375rem; background: #e0f2fe; padding: .1rem .35rem; color: #0c4a6e; font-family: 'DM Mono', monospace; font-size: .88em; }
        .answer-rich-text pre { overflow-x: auto; border-radius: .875rem; background: #0f172a; padding: 1rem; color: #e2e8f0; font-family: 'DM Mono', monospace; font-size: .8rem; line-height: 1.65; }
        .answer-rich-text pre code { background: transparent; padding: 0; color: inherit; }

        /* Capaian tujuan pembelajaran ditampilkan sebagai ringkasan data. */
        .tp-overview-card { overflow: hidden; }
        .tp-item-card { min-height: 138px; transition: border-color .18s ease, box-shadow .18s ease, transform .18s ease; }
        .tp-item-card:hover { transform: translateY(-1px); border-color: #bae6fd; box-shadow: 0 12px 26px -22px rgba(8, 145, 178, .52); }
        .tp-title-clamp { display: -webkit-box; overflow: hidden; -webkit-box-orient: vertical; -webkit-line-clamp: 2; }
        .tp-progress-track { background: #e2e8f0; }
        .question-tp-tag { max-width: 100%; }
        .question-tp-title { min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        @media (max-width: 639px) { .tp-item-card { min-height: 0; } .question-tp-title { white-space: normal; } }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { scroll-behavior: auto !important; transition-duration: .01ms !important; animation-duration: .01ms !important; }
        }
    </style>
</head>
<body class="min-h-screen antialiased">
@php
    $safeRoute = function ($name, $parameters = [], $fallback = '#') {
        return \Illuminate\Support\Facades\Route::has($name) ? route($name, $parameters) : $fallback;
    };

    $passingScore = (int) ($passingScore ?? 70);
    $chapterId = (int) ($attempt->chapter_id ?? 0);
    $score = (int) round((float) ($attempt->score ?? 0));
    $isPassed = $score >= $passingScore;
    $duration = max(0, (int) ($attempt->time_spent_seconds ?? 0));
    $durationText = gmdate($duration >= 3600 ? 'H:i:s' : 'i:s', $duration);
    $focusLost = max(0, (int) ($attempt->focus_lost_count ?? 0));
    $completedAt = $attempt->completed_at ?? $attempt->created_at ?? null;
    $evaluationTitle = $chapterId === 99 ? 'Evaluasi Akhir' : 'Evaluasi Bab ' . ($chapterId ?: '-');
    $studentName = $student->name ?? 'Siswa';
    $studentProfileUrl = !empty($student?->id)
        ? $safeRoute('admin.student.detail', $student->id, $safeRoute('admin.students.index'))
        : $safeRoute('admin.students.index');

    // Pertahankan filter Analitik Kuis saat halaman ini dibuka dari daftar siswa.
    $quizAnalyticsQuery = array_filter([
        'class_group' => request('class_group'),
        'period' => request('period'),
        'chapter' => request('chapter'),
    ], fn ($value) => filled($value));
    $quizAnalyticsUrl = $safeRoute('admin.analytics.questions', $quizAnalyticsQuery);

    $summary = is_array($chapterSummary ?? null) ? $chapterSummary : [];
    $metrics = is_array($metrics ?? null) ? $metrics : [];
    $outcomeAnalytics = is_array($outcomeAnalytics ?? null) ? $outcomeAnalytics : [];
    $reviewItems = isset($reviewItems) ? collect($reviewItems) : collect();
    $outcomeRows = collect($outcomeAnalytics['outcomes'] ?? []);

    $answeredCount = max(0, (int) data_get($metrics, 'answered_count', 0));
    $totalQuestions = max(0, (int) data_get($metrics, 'total_questions', 0));
    $correctCount = max(0, (int) data_get($metrics, 'correct_count', 0));
    $wrongCount = max(0, (int) data_get($metrics, 'wrong_count', 0));
    $unansweredCount = max(0, (int) data_get($metrics, 'unanswered_count', 0));
    $flaggedCount = max(0, (int) data_get($metrics, 'flagged_count', 0));
    $answerChangeCount = max(0, (int) data_get($metrics, 'answer_change_count', 0));
    $accuracy = $totalQuestions > 0 ? round(($correctCount / $totalQuestions) * 100) : 0;
    $answerCompletionPercent = $totalQuestions > 0 ? round(($answeredCount / $totalQuestions) * 100) : 0;
    $incorrectOrEmptyCount = max(0, $wrongCount + $unansweredCount);
    $interactionCount = max(0, $flaggedCount + $answerChangeCount + $focusLost);

    $scoreTone = $score >= 85
        ? ['ring' => '#10b981', 'text' => 'text-emerald-700', 'badge' => 'border-emerald-200 bg-emerald-50 text-emerald-700', 'soft' => 'border-emerald-100 bg-emerald-50 text-emerald-800']
        : ($isPassed
            ? ['ring' => '#0891b2', 'text' => 'text-cyan-700', 'badge' => 'border-cyan-200 bg-cyan-50 text-cyan-700', 'soft' => 'border-cyan-100 bg-cyan-50 text-cyan-800']
            : ['ring' => '#e11d48', 'text' => 'text-rose-700', 'badge' => 'border-rose-200 bg-rose-50 text-rose-700', 'soft' => 'border-rose-100 bg-rose-50 text-rose-800']);

    $outcomeVisualMap = [
        'emerald' => ['bar' => 'bg-emerald-500', 'badge' => 'border-emerald-200 bg-emerald-50 text-emerald-700'],
        'cyan' => ['bar' => 'bg-cyan-500', 'badge' => 'border-cyan-200 bg-cyan-50 text-cyan-700'],
        'amber' => ['bar' => 'bg-amber-500', 'badge' => 'border-amber-200 bg-amber-50 text-amber-700'],
        'red' => ['bar' => 'bg-rose-500', 'badge' => 'border-rose-200 bg-rose-50 text-rose-700'],
        'slate' => ['bar' => 'bg-slate-400', 'badge' => 'border-slate-200 bg-slate-50 text-slate-600'],
    ];

    $completionText = $completedAt
        ? \Carbon\Carbon::parse($completedAt)->translatedFormat('d M Y, H:i')
        : 'Waktu pengerjaan tidak tersedia';
@endphp

<div class="min-h-screen">
    <header class="sticky top-0 z-40 border-b border-slate-200/80 bg-white/90 backdrop-blur-xl">
        <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-4 sm:px-6 lg:flex-row lg:items-center lg:justify-between lg:px-8">
            <div class="flex min-w-0 items-center gap-4">
                <a href="{{ $quizAnalyticsUrl }}" class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-slate-600 transition hover:border-cyan-200 hover:text-cyan-700" title="Kembali ke Analitik Kuis" aria-label="Kembali ke Analitik Kuis">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <div class="min-w-0">
                    <nav class="mb-1 hidden text-[11px] font-bold uppercase tracking-[0.18em] text-slate-400 sm:block" aria-label="Breadcrumb">
                        <a href="{{ $safeRoute('admin.dashboard') }}" class="hover:text-cyan-700">Dasbor</a>
                        <span class="mx-2">/</span>
                        <a href="{{ $quizAnalyticsUrl }}" class="hover:text-cyan-700">Analitik Kuis</a>
                        <span class="mx-2">/</span>
                        <span class="text-slate-600">Tinjauan Hasil Kuis</span>
                    </nav>
                    <h1 class="truncate text-xl font-black tracking-tight text-slate-950 sm:text-2xl">Tinjauan Hasil Kuis</h1>
                    <p class="mt-1 truncate text-sm text-slate-500">{{ $evaluationTitle }} · {{ $studentName }}</p>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ $quizAnalyticsUrl }}" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-bold text-slate-600 shadow-sm transition hover:border-cyan-200 hover:text-cyan-700">Kembali ke Analitik Kuis</a>
                <a href="{{ $studentProfileUrl }}" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-bold text-slate-600 shadow-sm transition hover:border-cyan-200 hover:text-cyan-700">Profil Siswa</a>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-soft">
            <div class="grid gap-0 lg:grid-cols-[1.15fr_.85fr]">
                <div class="p-6 sm:p-8">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-extrabold uppercase tracking-[0.16em] {{ $scoreTone['badge'] }}">{{ $isPassed ? 'Lulus' : 'Belum lulus' }}</span>
                        <span class="inline-flex items-center rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-bold text-slate-500">{{ $completionText }}</span>
                    </div>

                    <div class="mt-8 grid gap-6 sm:grid-cols-[auto_1fr] sm:items-center">
                        <div class="relative h-40 w-40">
                            <svg class="h-full w-full -rotate-90" viewBox="0 0 120 120" aria-label="Skor {{ $score }} dari 100">
                                <circle cx="60" cy="60" r="52" stroke="#e2e8f0" stroke-width="12" fill="none"/>
                                <circle cx="60" cy="60" r="52" stroke="{{ $scoreTone['ring'] }}" stroke-width="12" fill="none" stroke-linecap="round" stroke-dasharray="{{ min(100, max(0, $score)) * 3.27 }} 327"/>
                            </svg>
                            <div class="absolute inset-0 flex flex-col items-center justify-center">
                                <span class="text-4xl font-black {{ $scoreTone['text'] }}">{{ $score }}</span>
                                <span class="text-[10px] font-bold uppercase tracking-[0.18em] text-slate-400">Skor</span>
                            </div>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[11px] font-extrabold uppercase tracking-[0.2em] text-cyan-700">Data Pengerjaan</p>
                            <h2 class="mt-2 text-2xl font-black text-slate-950">Skor akhir <span class="data-number">{{ $score }}</span> dari 100</h2>
                            <p class="mt-3 max-w-2xl text-sm leading-7 text-slate-600">
                                <span class="data-number">{{ $answeredCount }}/{{ $totalQuestions }}</span> soal terjawab ·
                                <span class="data-number">{{ $correctCount }}</span> jawaban benar ·
                                akurasi <span class="data-number">{{ $accuracy }}%</span> ·
                                durasi <span class="data-number font-mono">{{ $durationText }}</span>.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="border-t border-slate-200 bg-slate-50 p-6 sm:p-8 lg:border-l lg:border-t-0">
                    <p class="text-[11px] font-extrabold uppercase tracking-[0.16em] text-slate-500">Ringkasan Kuis</p>
                    <p class="mt-3 text-xl font-black text-slate-950">{{ $summary['title'] ?? $evaluationTitle }}</p>
                    @if(!empty($summary['subtitle']))
                        <span class="mt-3 inline-flex rounded-lg border border-cyan-200 bg-cyan-50 px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-wider text-cyan-700">{{ $summary['subtitle'] }}</span>
                    @endif
                    <p class="mt-4 text-sm leading-7 text-slate-600">{{ $summary['summary'] ?? 'Data berikut berasal dari satu riwayat kuis yang telah diselesaikan oleh siswa.' }}</p>

                    <dl class="mt-6 divide-y divide-slate-200 overflow-hidden rounded-xl border border-slate-200 bg-white text-sm">
                        <div class="flex items-center justify-between gap-4 px-4 py-3"><dt class="text-slate-500">Siswa</dt><dd class="text-right font-bold text-slate-950">{{ $studentName }}</dd></div>
                        <div class="flex items-center justify-between gap-4 px-4 py-3"><dt class="text-slate-500">Evaluasi</dt><dd class="text-right font-bold text-slate-950">{{ $evaluationTitle }}</dd></div>
                        <div class="flex items-center justify-between gap-4 px-4 py-3"><dt class="text-slate-500">Status</dt><dd class="text-right font-bold {{ $isPassed ? 'text-emerald-700' : 'text-rose-700' }}">{{ $isPassed ? 'Lulus' : 'Belum lulus' }}</dd></div>
                    </dl>
                </div>
            </div>
        </section>

        <section class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4" aria-label="Ringkasan angka kuis">
            <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-[10px] font-extrabold uppercase tracking-[0.18em] text-slate-400">Soal Terjawab</p>
                <p class="data-number mt-2 text-2xl font-black text-slate-950">{{ $answeredCount }}/{{ $totalQuestions }}</p>
                <p class="mt-2 text-xs font-semibold text-slate-500">Respons yang tersimpan pada kuis.</p>
            </article>
            <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-[10px] font-extrabold uppercase tracking-[0.18em] text-slate-400">Jawaban Benar</p>
                <p class="data-number mt-2 text-2xl font-black text-emerald-700">{{ $correctCount }}/{{ $totalQuestions }}</p>
                <p class="mt-2 text-xs font-semibold text-slate-500">Jawaban yang sesuai kunci.</p>
            </article>
            <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-[10px] font-extrabold uppercase tracking-[0.18em] text-slate-400">Tidak Sesuai Kunci</p>
                <p class="data-number mt-2 text-2xl font-black text-rose-700">{{ $incorrectOrEmptyCount }}</p>
                <p class="mt-2 text-xs font-semibold text-slate-500">Jawaban salah atau belum terjawab.</p>
            </article>
            <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-[10px] font-extrabold uppercase tracking-[0.18em] text-slate-400">Durasi</p>
                <p class="data-number mt-2 font-mono text-2xl font-black text-cyan-700">{{ $durationText }}</p>
                <p class="mt-2 text-xs font-semibold text-slate-500">Waktu pengerjaan kuis.</p>
            </article>
        </section>

        <section class="mt-6 grid gap-6 lg:grid-cols-[1.15fr_.85fr]">
            <div class="tp-overview-card rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-6 py-5">
                    <p class="text-[11px] font-extrabold uppercase tracking-[0.2em] text-cyan-700">Tujuan Pembelajaran</p>
                    <h2 class="mt-2 text-xl font-black text-slate-950">Capaian per Tujuan Pembelajaran</h2>
                    <p class="mt-1 text-sm text-slate-500">Persentase jawaban benar pada soal yang terhubung dengan setiap TP.</p>
                </div>

                <div class="grid gap-3 p-5 sm:p-6 md:grid-cols-2">
                    @forelse($outcomeRows as $tp)
                        @php
                            $toneKey = $tp['tone'] ?? 'slate';
                            $visual = $outcomeVisualMap[$toneKey] ?? $outcomeVisualMap['slate'];
                            $mastery = max(0, min(100, (int) ($tp['mastery_percent'] ?? 0)));
                            $tpCode = $tp['display_code'] ?? $tp['code'] ?? 'TP';
                            $tpTitle = $tp['title'] ?? 'Tujuan Pembelajaran';
                            $tpQuestionTotal = data_get($tp, 'total_questions', data_get($tp, 'question_count', data_get($tp, 'questions_count')));
                            $tpCorrectTotal = data_get($tp, 'correct_count', data_get($tp, 'correct_answers', data_get($tp, 'correct')));
                            $tpQuestionTotal = is_numeric($tpQuestionTotal) ? (int) $tpQuestionTotal : null;
                            $tpCorrectTotal = is_numeric($tpCorrectTotal) ? (int) $tpCorrectTotal : null;
                        @endphp
                        <article class="tp-item-card rounded-xl border border-slate-200 bg-white p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <span class="inline-flex rounded-md border border-slate-200 bg-slate-50 px-2 py-1 text-[10px] font-extrabold uppercase tracking-[0.14em] text-slate-600">{{ $tpCode }}</span>
                                    <h3 class="tp-title-clamp mt-2 text-sm font-black leading-5 text-slate-900" title="{{ $tpTitle }}">{{ $tpTitle }}</h3>
                                </div>
                                <div class="shrink-0 text-right">
                                    <p class="data-number text-2xl font-black text-slate-950">{{ $mastery }}%</p>
                                    <p class="mt-0.5 text-[10px] font-bold uppercase tracking-[0.12em] text-slate-400">Benar</p>
                                </div>
                            </div>

                            <div class="tp-progress-track mt-4 h-2 overflow-hidden rounded-full" aria-label="Capaian {{ $tpCode }}: {{ $mastery }} persen">
                                <div class="h-full rounded-full {{ $visual['bar'] }}" style="width: {{ $mastery }}%"></div>
                            </div>

                            <p class="mt-3 text-xs font-semibold text-slate-500">
                                @if($tpQuestionTotal !== null && $tpQuestionTotal > 0 && $tpCorrectTotal !== null)
                                    {{ $tpCorrectTotal }} benar dari {{ $tpQuestionTotal }} soal
                                @else
                                    Data jumlah soal belum tersedia
                                @endif
                            </p>
                        </article>
                    @empty
                        <div class="rounded-xl border border-dashed border-slate-200 bg-slate-50 p-6 text-center text-sm text-slate-500 md:col-span-2">Belum ada data tujuan pembelajaran untuk kuis ini.</div>
                    @endforelse
                </div>
            </div>

            <aside class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm" aria-label="Catatan kuis">
                <p class="text-[11px] font-extrabold uppercase tracking-[0.2em] text-slate-400">Data Pengerjaan</p>
                <h2 class="mt-2 text-xl font-black text-slate-950">Catatan Kuis</h2>
                <p class="mt-2 text-sm leading-6 text-slate-600">Ringkasan angka yang tercatat pada riwayat kuis ini.</p>

                <div class="mt-5 space-y-3 text-sm">
                    <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                        <div class="flex items-center justify-between gap-4">
                            <span class="text-slate-500">Soal terjawab</span>
                            <span class="data-number font-black text-slate-950">{{ $answeredCount }}/{{ $totalQuestions }}</span>
                        </div>
                        <div class="mt-2 h-1.5 overflow-hidden rounded-full bg-slate-200" aria-label="Soal terjawab {{ $answerCompletionPercent }} persen">
                            <span class="block h-full rounded-full bg-cyan-500" style="width: {{ $answerCompletionPercent }}%"></span>
                        </div>
                        <p class="mt-1.5 text-[11px] font-semibold text-slate-500">{{ $answerCompletionPercent }}% dari seluruh soal.</p>
                    </div>

                    <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                        <div class="flex items-center justify-between gap-4">
                            <span class="text-slate-500">Jawaban benar</span>
                            <span class="data-number font-black text-slate-950">{{ $correctCount }}/{{ $totalQuestions }}</span>
                        </div>
                        <div class="mt-2 h-1.5 overflow-hidden rounded-full bg-slate-200" aria-label="Akurasi jawaban {{ $accuracy }} persen">
                            <span class="block h-full rounded-full bg-indigo-500" style="width: {{ $accuracy }}%"></span>
                        </div>
                        <p class="mt-1.5 text-[11px] font-semibold text-slate-500">Akurasi jawaban {{ $accuracy }}%.</p>
                    </div>

                    <div class="flex items-center justify-between gap-4 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                        <span class="text-slate-500">Jawaban kosong</span>
                        <span class="data-number font-black text-slate-950">{{ $unansweredCount }}</span>
                    </div>

                    <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                        <div class="flex items-center justify-between gap-4">
                            <span class="text-slate-500">Interaksi tercatat</span>
                            <span class="data-number font-black text-slate-950">{{ $interactionCount }}</span>
                        </div>
                        <p class="mt-1.5 text-[11px] font-semibold text-slate-500">
                            {{ $flaggedCount }} ragu-ragu · {{ $answerChangeCount }} jawaban diubah · {{ $focusLost }} pindah tab/jendela.
                        </p>
                    </div>

                    <div class="flex items-center justify-between gap-4 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                        <span class="text-slate-500">Durasi kuis</span>
                        <span class="data-number font-mono font-black text-cyan-700">{{ $durationText }}</span>
                    </div>
                </div>
            </aside>
        </section>

        <section id="reviewAnswers" class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-col gap-3 border-b border-slate-200 p-6 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-[11px] font-extrabold uppercase tracking-[0.2em] text-cyan-700">Tinjauan Jawaban</p>
                    <h2 class="mt-2 text-xl font-black text-slate-950">Jawaban {{ $studentName }} dan Kunci Jawaban</h2>
                    <p class="mt-1 text-sm text-slate-500">Setiap soal memuat jawaban siswa, kunci jawaban, dan status pemeriksaan.</p>
                </div>
                <span class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 font-mono text-[11px] font-medium text-slate-600">{{ $flaggedCount }} ragu / {{ $unansweredCount }} kosong</span>
            </div>

            <div class="review-scroll max-h-[820px] overflow-y-auto">
                @forelse($reviewItems as $item)
                    @php
                        $question = $item['question'] ?? null;
                        $interactionType = $question->interaction_type ?? 'multiple_choice';
                        $interactionLabel = ['multiple_choice' => 'Pilihan ganda', 'image_context' => 'Gambar'][$interactionType] ?? 'Kuis';
                        $hasAnswer = (bool) ($item['has_answer'] ?? false);
                        $isCorrect = (bool) ($item['is_correct'] ?? false);
                        $statusClass = $isCorrect
                            ? 'border-emerald-200 bg-emerald-50 text-emerald-700'
                            : ($hasAnswer ? 'border-rose-200 bg-rose-50 text-rose-700' : 'border-amber-200 bg-amber-50 text-amber-700');
                        $statusLabel = $isCorrect ? 'Benar' : ($hasAnswer ? 'Salah' : 'Kosong');

                        // Ambil metadata TP dari soal tanpa menambah query atau mengubah logika penilaian.
                        $questionOutcomeMeta = [];
                        if ($question) {
                            try {
                                $resolvedOutcomeMeta = \App\Support\LearningOutcomeAnalytics::quizOutcomeMetadata($question);
                                $questionOutcomeMeta = is_array($resolvedOutcomeMeta) ? $resolvedOutcomeMeta : (array) $resolvedOutcomeMeta;
                            } catch (\Throwable $exception) {
                                $questionOutcomeMeta = [];
                            }
                        }

                        $questionOutcomeCode = trim((string) (
                            data_get($questionOutcomeMeta, 'display_code')
                            ?? data_get($questionOutcomeMeta, 'code')
                            ?? data_get($question, 'learning_objective_code')
                            ?? data_get($item, 'outcome.display_code')
                            ?? data_get($item, 'outcome.code')
                            ?? ''
                        ));
                        $questionOutcomeTitle = trim((string) (
                            data_get($questionOutcomeMeta, 'title')
                            ?? data_get($question, 'learning_objective_title')
                            ?? data_get($item, 'outcome.title')
                            ?? ''
                        ));
                        $questionOutcomeTitleShort = $questionOutcomeTitle !== ''
                            ? \Illuminate\Support\Str::limit(strip_tags($questionOutcomeTitle), 94)
                            : '';
                    @endphp
                    <article class="border-b border-slate-100 p-6 last:border-b-0">
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border text-sm font-black {{ $statusClass }}">{{ $item['number'] ?? '-' }}</span>
                                <div>
                                    <p class="text-sm font-black text-slate-950">{{ $item['status'] ?? $statusLabel }}</p>
                                    <p class="text-[10px] font-bold uppercase tracking-[0.14em] text-slate-400">{{ $interactionLabel }}</p>
                                </div>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                @if(!empty($item['is_flagged']))
                                    <span class="rounded-lg border border-amber-200 bg-amber-50 px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-wider text-amber-700">Ragu-ragu</span>
                                @endif
                                <span class="rounded-lg border px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-wider {{ $statusClass }}">{{ $statusLabel }}</span>
                            </div>
                        </div>

                        @if(!empty($question?->media_url) || !empty($question?->interaction_prompt))
                            <div class="mt-4 grid gap-3">
                                @if(!empty($question?->media_url))
                                    <figure class="overflow-hidden rounded-xl border border-slate-200 bg-slate-50">
                                        <img src="{{ $question->media_url }}" alt="{{ $question->media_caption ?: 'Media soal' }}" class="max-h-80 w-full object-contain">
                                        @if(!empty($question?->media_caption))
                                            <figcaption class="border-t border-slate-200 px-4 py-2.5 text-xs leading-5 text-slate-500">{{ $question->media_caption }}</figcaption>
                                        @endif
                                    </figure>
                                @endif
                                @if(!empty($question?->interaction_prompt))
                                    <div class="rounded-xl border border-cyan-200 bg-cyan-50 px-4 py-3">
                                        <p class="text-[10px] font-extrabold uppercase tracking-[0.13em] text-cyan-700">Konteks Soal</p>
                                        <p class="mt-1 text-sm leading-6 text-slate-700">{{ $question->interaction_prompt }}</p>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <div class="answer-rich-text mt-4 text-sm font-semibold leading-7 text-slate-900 sm:text-[15px]">{!! $question->question_text ?? 'Teks soal tidak tersedia.' !!}</div>

                        @if($questionOutcomeCode !== '')
                            <div class="question-tp-tag mt-3 flex flex-wrap items-center gap-x-2 gap-y-1 rounded-lg border border-cyan-100 bg-cyan-50 px-3 py-2 text-xs text-slate-700" aria-label="Tujuan pembelajaran soal">
                                <span class="font-extrabold uppercase tracking-[0.12em] text-cyan-700">TP terkait</span>
                                <span class="font-mono font-bold text-slate-950">{{ $questionOutcomeCode }}</span>
                                @if($questionOutcomeTitleShort !== '')
                                    <span class="question-tp-title border-l border-cyan-200 pl-2 text-slate-600" title="{{ $questionOutcomeTitle }}">{{ $questionOutcomeTitleShort }}</span>
                                @endif
                            </div>
                        @endif

                        <div class="mt-4 grid gap-3 md:grid-cols-2">
                            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                                <p class="text-[10px] font-extrabold uppercase tracking-[0.13em] text-slate-400">Jawaban {{ $studentName }}</p>
                                <p class="mt-2 text-sm leading-6 text-slate-800">{{ $item['selected_option']->option_text ?? 'Belum dijawab' }}</p>
                            </div>
                            <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4">
                                <p class="text-[10px] font-extrabold uppercase tracking-[0.13em] text-emerald-700">Kunci Jawaban</p>
                                <p class="mt-2 text-sm leading-6 text-slate-800">{{ $item['correct_option']->option_text ?? 'Kunci belum tersedia' }}</p>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="p-10 text-center text-sm text-slate-500">Belum ada data soal untuk riwayat kuis ini.</div>
                @endforelse
            </div>
        </section>
    </main>
</div>
</body>
</html>
