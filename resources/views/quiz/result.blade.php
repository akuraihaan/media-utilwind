<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $chapterId == 99 ? 'Hasil Evaluasi Akhir' : 'Hasil Evaluasi Bab ' . $chapterId }} | Utilwind</title>

    {{-- Tema mengikuti localStorage global tanpa switcher di halaman ini. --}}
    <script>
        (function () {
            try {
                const savedTheme = localStorage.getItem('color-theme');
                const isDark = savedTheme ? savedTheme === 'dark' : window.matchMedia('(prefers-color-scheme: dark)').matches;
                document.documentElement.classList.toggle('dark', isDark);
                document.documentElement.style.colorScheme = isDark ? 'dark' : 'light';
            } catch (error) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { darkMode: 'class' };
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --page: #f5f7fb;
            --glow-a: rgba(14, 165, 233, .10);
            --glow-b: rgba(99, 102, 241, .06);
            --surface: rgba(255,255,255,.88);
            --surface-solid: #ffffff;
            --surface-muted: #f8fafc;
            --line: #e2e8f0;
            --line-strong: #cbd5e1;
            --text: #162033;
            --muted: #64748b;
            --subtle: #94a3b8;
            --accent: #0891b2;
            --accent-strong: #0e7490;
            --accent-soft: #ecfeff;
            --accent-line: #a5f3fc;
            --shadow: 0 18px 46px rgba(15,23,42,.08);
            --shadow-soft: 0 10px 26px rgba(15,23,42,.05);
        }
        .dark {
            --page: #07111f;
            --glow-a: rgba(34,211,238,.09);
            --glow-b: rgba(99,102,241,.10);
            --surface: rgba(13,25,43,.88);
            --surface-solid: #0d192b;
            --surface-muted: #101f35;
            --line: rgba(148,163,184,.16);
            --line-strong: rgba(148,163,184,.27);
            --text: #eff6ff;
            --muted: #a7b5c9;
            --subtle: #73839a;
            --accent: #22d3ee;
            --accent-strong: #67e8f9;
            --accent-soft: rgba(34,211,238,.08);
            --accent-line: rgba(103,232,249,.24);
            --shadow: 0 20px 54px rgba(0,0,0,.26);
            --shadow-soft: 0 12px 30px rgba(0,0,0,.18);
        }
        * { -webkit-tap-highlight-color: transparent; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: radial-gradient(circle at 10% 0%, var(--glow-a), transparent 30rem), radial-gradient(circle at 95% 8%, var(--glow-b), transparent 28rem), var(--page);
            color: var(--text);
        }
        .font-mono { font-family: 'DM Mono', monospace; }
        .page-shell { isolation: isolate; }
        .page-shell::before {
            content:""; position:fixed; inset:0; z-index:-1; pointer-events:none;
            background-image:linear-gradient(rgba(148,163,184,.035) 1px, transparent 1px), linear-gradient(90deg, rgba(148,163,184,.035) 1px, transparent 1px);
            background-size:44px 44px; mask-image:linear-gradient(to bottom, black, transparent 78%);
        }
        .ui-card { background:var(--surface); border:1px solid var(--line); box-shadow:var(--shadow-soft); backdrop-filter:blur(16px); -webkit-backdrop-filter:blur(16px); }
        .ui-card-strong { background:var(--surface-solid); border:1px solid var(--line); box-shadow:var(--shadow); }
        .section-heading { color:var(--text); letter-spacing:-.025em; }
        .eyebrow { color:var(--accent); letter-spacing:.16em; }
        .muted { color:var(--muted); }
        .subtle { color:var(--subtle); }
        .score-ring { --progress:0; width:154px; height:154px; border-radius:50%; padding:10px; display:grid; place-items:center; background:conic-gradient(var(--ring-color) calc(var(--progress) * 1%), var(--ring-track) 0); box-shadow:inset 0 0 0 1px var(--ring-line), 0 18px 34px rgba(15,23,42,.10); }
        .score-ring__inner { width:100%; height:100%; display:grid; place-items:center; border-radius:50%; text-align:center; background:var(--surface-solid); border:1px solid var(--line); }
        .metric-tile { background:var(--surface-muted); border:1px solid var(--line); transition:transform 180ms ease, border-color 180ms ease, box-shadow 180ms ease; }
        .soft-panel { background:var(--surface-muted); border:1px solid var(--line); }
        .review-item { border-bottom:1px solid var(--line); transition:background-color 180ms ease; }
        .review-item:last-child { border-bottom:0; }
        .answer-panel { background:var(--surface-muted); border:1px solid var(--line); }
        .answer-correct { background:rgba(16,185,129,.06); border-color:rgba(16,185,129,.28); }
        .dark .answer-correct { background:rgba(16,185,129,.07); border-color:rgba(52,211,153,.20); }
        .outcome-card { position:relative; overflow:hidden; background:var(--surface-muted); border:1px solid var(--line); }
        .outcome-card::before { content:""; position:absolute; inset:0 auto 0 0; width:4px; background:var(--outcome-color, var(--accent)); }
        .action-primary { background:var(--accent); color:white; box-shadow:0 12px 24px color-mix(in srgb, var(--accent) 25%, transparent); }
        .action-secondary { background:var(--surface-solid); color:var(--text); border:1px solid var(--line-strong); }
        .progress-track { background:color-mix(in srgb, var(--line) 72%, transparent); }
        .progress-fill { background:linear-gradient(90deg, var(--accent), #6366f1); }
        .custom-scrollbar::-webkit-scrollbar { width:8px; }
        .custom-scrollbar::-webkit-scrollbar-track { background:transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background:color-mix(in srgb, var(--subtle) 42%, transparent); border-radius:999px; }
        .theme-ready, .theme-ready * { transition:background-color 180ms ease, border-color 180ms ease, color 180ms ease, box-shadow 180ms ease; }

        @property --progress {
            syntax: '<number>';
            inherits: false;
            initial-value: 0;
        }

        /* ==========================================================
           POLA INTERAKSI DIREKTORI — HASIL EVALUASI
           Satu host menangani gulir halaman. Panel jawaban memakai
           gulir mandiri dan menyerahkan kembali gulir ke halaman saat
           sudah mencapai batas atas atau bawah.
           ========================================================== */
        :root {
            --result-ease: cubic-bezier(.22, .61, .36, 1);
            --result-soft-ease: cubic-bezier(.16, 1, .3, 1);
            --result-motion-fast: 180ms;
            --result-motion-base: 220ms;
        }

        html, body { height: 100%; scroll-behavior: auto; }
        body { overscroll-behavior: none; }

        .page-shell {
            height: 100%;
            min-height: 0;
            overflow: hidden;
        }

        .smooth-result-scroll {
            scroll-behavior: auto;
            scroll-padding: 1.25rem 0 3rem;
            overscroll-behavior-y: contain;
            -webkit-overflow-scrolling: touch;
            touch-action: pan-y;
            scrollbar-gutter: stable both-edges;
            scrollbar-width: thin;
            scrollbar-color: color-mix(in srgb, var(--accent) 34%, transparent) transparent;
        }
        .smooth-result-scroll:focus { outline: none; }
        .smooth-result-scroll.is-smooth-scrolling { cursor: default; }

        [data-native-scroll] {
            scroll-behavior: auto;
            overscroll-behavior: contain;
            -webkit-overflow-scrolling: touch;
            touch-action: pan-y;
            scrollbar-gutter: stable;
            scrollbar-width: thin;
            scrollbar-color: color-mix(in srgb, var(--subtle) 42%, transparent) transparent;
        }
        .review-scroll {
            scroll-padding-block: 1rem;
            overscroll-behavior-y: contain;
        }
        #reviewAnswers { scroll-margin-block: 1.25rem; }

        [data-native-scroll]::-webkit-scrollbar,
        .smooth-result-scroll::-webkit-scrollbar { width: 6px; height: 6px; }
        [data-native-scroll]::-webkit-scrollbar-track,
        .smooth-result-scroll::-webkit-scrollbar-track { background: transparent; }
        [data-native-scroll]::-webkit-scrollbar-thumb,
        .smooth-result-scroll::-webkit-scrollbar-thumb {
            border: 2px solid transparent;
            border-radius: 999px;
            background: color-mix(in srgb, var(--subtle) 42%, transparent);
            background-clip: padding-box;
            transition: background-color var(--result-motion-fast) var(--result-ease);
        }
        [data-native-scroll]::-webkit-scrollbar-thumb:hover,
        .smooth-result-scroll::-webkit-scrollbar-thumb:hover {
            background-color: color-mix(in srgb, var(--accent) 58%, transparent);
        }

        .ui-card,
        .ui-card-strong,
        .metric-tile,
        .outcome-card,
        .review-item,
        .priority-card,
        .action-primary,
        .action-secondary,
        .score-ring {
            transition-timing-function: var(--result-ease);
        }

        .metric-tile,
        .outcome-card,
        .priority-card {
            transition-property: transform, border-color, box-shadow, background-color;
            transition-duration: var(--result-motion-base);
        }
        .review-item {
            transition-property: background-color, box-shadow;
            transition-duration: 200ms;
        }
        .action-primary,
        .action-secondary {
            transition-property: transform, background-color, color, border-color, box-shadow;
            transition-duration: var(--result-motion-fast);
        }
        .score-ring {
            transition: transform var(--result-motion-base) var(--result-ease), box-shadow var(--result-motion-base) var(--result-ease);
        }
        .progress-fill {
            width: 0;
            transition: width 900ms var(--result-soft-ease);
        }

        @media (hover: hover) and (pointer: fine) {
            .metric-tile,
            .outcome-card,
            .priority-card,
            .score-ring,
            .action-primary,
            .action-secondary { will-change: transform; }

            .metric-tile:hover {
                transform: translateY(-2px);
                border-color: var(--line-strong);
                box-shadow: var(--shadow-soft);
            }
            .outcome-card:hover {
                transform: translateY(-2px);
                border-color: var(--line-strong);
                box-shadow: 0 14px 28px rgba(15,23,42,.06);
            }
            .dark .outcome-card:hover { box-shadow: 0 14px 28px rgba(0,0,0,.20); }
            .priority-card:hover {
                transform: translateY(-1px);
                box-shadow: 0 10px 20px rgba(15,23,42,.05);
            }
            .review-item:hover {
                background: color-mix(in srgb, var(--surface-muted) 88%, transparent);
                box-shadow: inset 3px 0 0 color-mix(in srgb, var(--accent) 52%, transparent);
            }
            .score-ring:hover {
                transform: scale(1.018);
                box-shadow: inset 0 0 0 1px var(--ring-line), 0 20px 38px rgba(15,23,42,.13);
            }
            .action-primary:hover {
                background: var(--accent-strong);
                transform: translateY(-1px);
            }
            .action-secondary:hover {
                color: var(--accent-strong);
                border-color: var(--accent-line);
                transform: translateY(-1px);
            }
        }
        .action-primary:active,
        .action-secondary:active { transform: translateY(0) scale(.985); }

        @media (hover: none), (pointer: coarse) {
            .metric-tile:hover,
            .outcome-card:hover,
            .priority-card:hover,
            .score-ring:hover { transform: none; }
            .review-item:hover { background: transparent; box-shadow: none; }
            .action-primary:hover { background: var(--accent); transform: none; }
            .action-secondary:hover { color: var(--text); border-color: var(--line-strong); transform: none; }
        }

        @media (prefers-reduced-motion: reduce) {
            html, .smooth-result-scroll, [data-native-scroll] { scroll-behavior: auto; }
            .theme-ready, .theme-ready *, .metric-tile, .review-item, .outcome-card, .priority-card, .action-primary, .action-secondary, .score-ring, .progress-fill { transition: none !important; }
        }
    </style>
</head>
<body class="h-full overflow-hidden selection:bg-cyan-200/70 dark:selection:bg-cyan-300/20">
@php
    $score = (int) $attempt->score;
    $passingScore = 70;
    $isPassed = $score >= $passingScore;
    $duration = (int) ($attempt->time_spent_seconds ?? 0);
    $durationText = gmdate($duration >= 3600 ? 'H:i:s' : 'i:s', max(0, $duration));
    $focusLost = (int) ($attempt->focus_lost_count ?? 0);
    $summary = $chapterSummary ?? [];
    $evaluationTitle = (int) $chapterId === 99 ? 'Evaluasi Akhir' : 'Evaluasi Bab ' . $chapterId;

    $scoreStyle = $score >= 85
        ? ['text' => 'text-emerald-700 dark:text-emerald-300', 'label' => 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-400/[.10] dark:text-emerald-200 dark:border-emerald-300/20', 'ring' => '#10b981', 'track' => 'rgba(16,185,129,.16)', 'line' => 'rgba(16,185,129,.22)']
        : ($isPassed
            ? ['text' => 'text-cyan-700 dark:text-cyan-200', 'label' => 'bg-cyan-50 text-cyan-700 border-cyan-200 dark:bg-cyan-400/[.10] dark:text-cyan-200 dark:border-cyan-300/20', 'ring' => '#0891b2', 'track' => 'rgba(8,145,178,.16)', 'line' => 'rgba(8,145,178,.22)']
            : ['text' => 'text-rose-700 dark:text-rose-300', 'label' => 'bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-400/[.10] dark:text-rose-200 dark:border-rose-300/20', 'ring' => '#e11d48', 'track' => 'rgba(225,29,72,.16)', 'line' => 'rgba(225,29,72,.22)']);

    $studyRoutes = [
        1 => ['title' => 'Bab 1: Pendahuluan Tailwind CSS', 'route' => 'courses.htmldancss'],
        2 => ['title' => 'Bab 2: Layouting', 'route' => 'courses.layout-basics'],
        3 => ['title' => 'Bab 3: Styling Komponen', 'route' => 'courses.typography'],
        99 => ['title' => 'Silabus Utama', 'route' => 'courses.curriculum'],
    ];
    $studyTarget = $studyRoutes[(int) $chapterId] ?? $studyRoutes[1];
    $studyUrl = \Illuminate\Support\Facades\Route::has($studyTarget['route']) ? route($studyTarget['route']) : route('courses.curriculum');

    $priorityItems = collect();
    if (($metrics['wrong_count'] ?? 0) > 0) $priorityItems->push(['title' => 'Tinjau jawaban yang belum tepat', 'detail' => ($metrics['wrong_count'] ?? 0) . ' soal perlu dibaca kembali bersama pembahasannya.', 'tone' => 'rose']);
    if (($metrics['flagged_count'] ?? 0) > 0) $priorityItems->push(['title' => 'Bahas soal yang ditandai ragu-ragu', 'detail' => ($metrics['flagged_count'] ?? 0) . ' soal dapat dijadikan catatan untuk diskusi atau latihan berikutnya.', 'tone' => 'amber']);
    if (($metrics['unanswered_count'] ?? 0) > 0) $priorityItems->push(['title' => 'Periksa kembali kelengkapan jawaban', 'detail' => 'Biasakan kembali ke soal kosong sebelum evaluasi dikumpulkan.', 'tone' => 'amber']);
    if ($focusLost > 0) $priorityItems->push(['title' => 'Jaga fokus selama pengerjaan', 'detail' => 'Terdapat ' . $focusLost . ' kali perpindahan fokus selama evaluasi.', 'tone' => 'slate']);
    if ($priorityItems->isEmpty()) $priorityItems->push(['title' => 'Pertahankan pola belajar', 'detail' => 'Hasil sudah stabil. Lanjutkan latihan secara bertahap dan catat konsep yang penting.', 'tone' => 'emerald']);

    $priorityTone = [
        'rose' => 'border-rose-200 bg-rose-50 text-rose-800 dark:border-rose-300/15 dark:bg-rose-400/[.08] dark:text-rose-100',
        'amber' => 'border-amber-200 bg-amber-50 text-amber-800 dark:border-amber-300/15 dark:bg-amber-400/[.08] dark:text-amber-100',
        'slate' => 'border-slate-200 bg-slate-50 text-slate-700 dark:border-slate-300/15 dark:bg-slate-400/[.08] dark:text-slate-200',
        'emerald' => 'border-emerald-200 bg-emerald-50 text-emerald-800 dark:border-emerald-300/15 dark:bg-emerald-400/[.08] dark:text-emerald-100',
    ];

    $outcomeRows = collect($outcomeAnalytics['outcomes'] ?? []);
    $outcomeNeedsReview = collect($outcomeAnalytics['needs_review'] ?? []);
    $outcomeToneMap = [
        'emerald' => ['style' => '--outcome-color:#10b981', 'label' => 'text-emerald-700 dark:text-emerald-300'],
        'cyan' => ['style' => '--outcome-color:#0891b2', 'label' => 'text-cyan-700 dark:text-cyan-200'],
        'amber' => ['style' => '--outcome-color:#d97706', 'label' => 'text-amber-700 dark:text-amber-300'],
        'red' => ['style' => '--outcome-color:#e11d48', 'label' => 'text-rose-700 dark:text-rose-300'],
        'slate' => ['style' => '--outcome-color:#64748b', 'label' => 'text-slate-700 dark:text-slate-300'],
    ];
@endphp

<div class="page-shell h-full min-h-0">
    <main data-smooth-scroll class="smooth-result-scroll custom-scrollbar mx-auto h-full w-full max-w-[1320px] overflow-y-auto overflow-x-hidden px-4 py-6 sm:px-6 sm:py-8 lg:px-8 lg:py-10" tabindex="-1">
        <header class="ui-card-strong overflow-hidden rounded-[28px]">
            <div class="flex flex-col gap-6 px-5 py-6 sm:px-7 lg:flex-row lg:items-center lg:justify-between lg:px-8 lg:py-7">
                <div class="min-w-0">
                    <p class="eyebrow text-[10px] font-extrabold uppercase">{{ $isPassed ? 'Evaluasi telah diselesaikan' : 'Tindak lanjut evaluasi' }}</p>
                    <h1 class="section-heading mt-2 text-2xl font-extrabold sm:text-3xl">{{ $evaluationTitle }}</h1>
                    <p class="muted mt-2 max-w-2xl text-sm leading-6">{{ $isPassed ? 'Hasil pengerjaan telah tersimpan. Gunakan ringkasan berikut untuk memahami capaian dan langkah belajar selanjutnya.' : 'Nilai belum memenuhi KKM. Tinjau kembali bagian yang perlu diperkuat sebelum mengulang evaluasi.' }}</p>
                </div>
                <div class="flex shrink-0 flex-wrap gap-2.5">
                    @if(!$isPassed)
                        <a href="{{ route('quiz.intro', ['chapterId' => $chapterId]) }}" class="action-primary inline-flex min-h-11 items-center justify-center rounded-xl px-4 text-sm font-bold transition">Ulangi Evaluasi</a>
                    @endif
                    <a href="{{ route('dashboard') }}" class="action-secondary inline-flex min-h-11 items-center justify-center rounded-xl px-4 text-sm font-bold transition">Kembali ke Dasbor</a>
                </div>
            </div>
            <div class="h-[3px] w-full bg-gradient-to-r from-cyan-400 via-sky-400 to-indigo-400"></div>
        </header>

        @if(session('info') || session('success') || session('error'))
            <div class="mt-5 rounded-2xl border px-4 py-3 text-sm font-semibold {{ session('error') ? 'border-rose-200 bg-rose-50 text-rose-700 dark:border-rose-300/20 dark:bg-rose-400/[.08] dark:text-rose-200' : 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-300/20 dark:bg-emerald-400/[.08] dark:text-emerald-200' }}">{{ session('error') ?? session('success') ?? session('info') }}</div>
        @endif

        @if(!$isPassed)
            <section class="ui-card mt-5 rounded-[24px] border-rose-200/80 p-5 dark:border-rose-300/15 sm:p-6">
                <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <p class="text-[10px] font-extrabold uppercase tracking-[.16em] text-rose-600 dark:text-rose-300">Rencana remedial</p>
                        <h2 class="section-heading mt-1 text-lg font-extrabold">Pelajari ulang bagian yang belum kuat</h2>
                        <p class="muted mt-2 max-w-3xl text-sm leading-6">Mulai dari {{ $studyTarget['title'] }}, tinjau jawaban yang belum tepat, lalu ulangi evaluasi ketika konsep sudah lebih dipahami.</p>
                    </div>
                    <div class="flex flex-col gap-2 sm:flex-row">
                        <a href="{{ $studyUrl }}" class="action-secondary inline-flex min-h-10 items-center justify-center rounded-xl px-4 text-xs font-extrabold transition">Pelajari Materi</a>
                        <a href="#reviewAnswers" class="inline-flex min-h-10 items-center justify-center rounded-xl border border-cyan-200 bg-cyan-50 px-4 text-xs font-extrabold text-cyan-700 transition hover:border-cyan-300 hover:bg-cyan-100 dark:border-cyan-300/20 dark:bg-cyan-400/[.08] dark:text-cyan-200 dark:hover:bg-cyan-400/[.14]">Tinjau Jawaban</a>
                    </div>
                </div>
            </section>
        @endif

        <section class="mt-6 grid gap-5 xl:grid-cols-[minmax(0,1.42fr)_minmax(370px,.58fr)]">
            <article class="ui-card-strong rounded-[28px] p-5 sm:p-6 lg:p-7">
                <div class="grid items-center gap-6 sm:grid-cols-[154px_minmax(0,1fr)]">
                    <div class="score-ring js-score-ring mx-auto sm:mx-0" data-progress="{{ min(100, max(0, $score)) }}" style="--progress: 0; --ring-color: {{ $scoreStyle['ring'] }}; --ring-track: {{ $scoreStyle['track'] }}; --ring-line: {{ $scoreStyle['line'] }};">
                        <div class="score-ring__inner"><span class="font-mono text-4xl font-medium {{ $scoreStyle['text'] }}">{{ $score }}</span><span class="subtle -mt-1 text-[10px] font-bold uppercase tracking-[.16em]">dari 100</span></div>
                    </div>
                    <div class="min-w-0 text-center sm:text-left">
                        <p class="eyebrow text-[10px] font-extrabold uppercase">Hasil akhir</p>
                        <h2 class="section-heading mt-1 text-2xl font-extrabold">{{ $feedback['level'] }}</h2>
                        <p class="muted mt-3 max-w-2xl text-sm leading-6">{{ $feedback['message'] }}</p>
                        <div class="mt-4 inline-flex items-center gap-2 rounded-xl border px-3 py-2 text-xs font-bold {{ $scoreStyle['label'] }}"><span class="h-1.5 w-1.5 rounded-full bg-current"></span>{{ $isPassed ? 'Memenuhi KKM ' . $passingScore : 'Belum memenuhi KKM ' . $passingScore }}</div>
                    </div>
                </div>
                <div class="mt-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="metric-tile rounded-2xl p-4"><p class="subtle text-[10px] font-extrabold uppercase tracking-[.14em]">Kelengkapan</p><p class="section-heading mt-2 text-2xl font-extrabold">{{ $metrics['completion_percent'] }}%</p><p class="muted mt-1 text-xs leading-5">{{ $metrics['answered_count'] }} dari {{ $metrics['total_questions'] }} soal terjawab</p></div>
                    <div class="metric-tile rounded-2xl p-4"><p class="subtle text-[10px] font-extrabold uppercase tracking-[.14em]">Jawaban benar</p><p class="mt-2 text-2xl font-extrabold text-emerald-600 dark:text-emerald-300">{{ $metrics['correct_count'] }}</p><p class="muted mt-1 text-xs leading-5">{{ $metrics['wrong_count'] }} soal perlu ditinjau</p></div>
                    <div class="metric-tile rounded-2xl p-4"><p class="subtle text-[10px] font-extrabold uppercase tracking-[.14em]">Durasi</p><p class="section-heading font-mono mt-2 text-2xl font-medium">{{ $durationText }}</p><p class="muted mt-1 text-xs leading-5">Waktu pengerjaan tercatat</p></div>
                    <div class="metric-tile rounded-2xl p-4"><p class="subtle text-[10px] font-extrabold uppercase tracking-[.14em]">Fokus</p><p class="mt-2 text-2xl font-extrabold {{ $focusLost > 0 ? 'text-amber-600 dark:text-amber-300' : 'text-emerald-600 dark:text-emerald-300' }}">{{ $focusLost }}</p><p class="muted mt-1 text-xs leading-5">Perpindahan halaman terdeteksi</p></div>
                </div>
            </article>

            <aside class="ui-card rounded-[28px] p-5 sm:p-6">
                <div class="flex items-start justify-between gap-3"><div><p class="eyebrow text-[10px] font-extrabold uppercase">Ringkasan pengerjaan</p><h2 class="section-heading mt-1 text-lg font-extrabold">Status evaluasi</h2></div><span class="rounded-lg border px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-[.12em] {{ $scoreStyle['label'] }}">{{ $isPassed ? 'Lulus' : 'Remedial' }}</span></div>
                <div class="mt-5 space-y-2.5">
                    <div class="soft-panel flex items-center justify-between rounded-xl px-4 py-3"><span class="muted text-sm">Soal belum dijawab</span><span class="section-heading text-sm font-extrabold">{{ $metrics['unanswered_count'] }}</span></div>
                    <div class="soft-panel flex items-center justify-between rounded-xl px-4 py-3"><span class="muted text-sm">Ditandai ragu-ragu</span><span class="section-heading text-sm font-extrabold">{{ $metrics['flagged_count'] }}</span></div>
                    <div class="soft-panel flex items-center justify-between rounded-xl px-4 py-3"><span class="muted text-sm">Perubahan jawaban</span><span class="section-heading text-sm font-extrabold">{{ $metrics['answer_change_count'] }}</span></div>
                </div>
                <div class="mt-5"><div class="mb-2 flex items-center justify-between"><span class="subtle text-[10px] font-extrabold uppercase tracking-[.14em]">Kelengkapan jawaban</span><span class="text-xs font-extrabold text-cyan-700 dark:text-cyan-200">{{ $metrics['completion_percent'] }}%</span></div><div class="progress-track h-2 overflow-hidden rounded-full"><div class="progress-fill js-progress h-full rounded-full" data-progress="{{ min(100, max(0, $metrics['completion_percent'])) }}" style="width: 0%"></div></div></div>
            </aside>
        </section>

        <section class="ui-card mt-6 rounded-[28px] p-5 sm:p-6 lg:p-7">
            <div class="flex flex-col gap-4 border-b pb-5" style="border-color:var(--line)">
                <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between"><div><p class="eyebrow text-[10px] font-extrabold uppercase">Analitik tujuan pembelajaran</p><h2 class="section-heading mt-1 text-xl font-extrabold">Capaian berdasarkan tujuan pembelajaran</h2><p class="muted mt-2 max-w-3xl text-sm leading-6">{{ $outcomeAnalytics['summary_text'] ?? 'Belum ada analitik tujuan pembelajaran.' }}</p></div><div class="soft-panel shrink-0 rounded-xl px-4 py-3"><p class="subtle text-[10px] font-extrabold uppercase tracking-[.13em]">Keputusan belajar</p><p class="section-heading mt-1 text-sm font-extrabold">{{ $outcomeAnalytics['decision'] ?? 'Belum ada data' }}</p></div></div>
            </div>
            <div class="mt-5 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                @forelse($outcomeRows as $tp)
                    @php $tpTone = $outcomeToneMap[$tp['tone'] ?? 'slate'] ?? $outcomeToneMap['slate']; @endphp
                    <article class="outcome-card rounded-2xl p-4 pl-5" style="{{ $tpTone['style'] }}">
                        <div class="flex items-start justify-between gap-4"><div class="min-w-0"><p class="text-[10px] font-extrabold uppercase tracking-[.13em] {{ $tpTone['label'] }}">{{ $tp['display_code'] ?? $tp['code'] ?? 'TP' }}</p><h3 class="section-heading mt-1 text-sm font-extrabold leading-6">{{ $tp['title'] ?? 'Tujuan Pembelajaran' }}</h3></div><span class="font-mono shrink-0 text-2xl font-medium {{ $tpTone['label'] }}">{{ $tp['mastery_percent'] ?? 0 }}%</span></div>
                        <div class="mt-4 space-y-3 text-xs leading-5"><div class="rounded-xl border px-3 py-2.5" style="border-color:var(--line);background:var(--surface-solid)"><p class="subtle text-[9px] font-extrabold uppercase tracking-[.13em]">Data aktivitas</p><p class="muted mt-1">{{ $tp['activity_data'] ?? '-' }}</p></div><p class="muted"><span class="section-heading font-extrabold">Capaian:</span> {{ $tp['mastery_statement'] ?? '-' }}</p><p class="muted"><span class="section-heading font-extrabold">Arah materi:</span> {{ $tp['material_direction'] ?? '-' }}</p></div>
                        <div class="mt-4 flex items-center justify-between gap-2"><span class="text-[10px] font-extrabold uppercase tracking-[.11em] {{ $tpTone['label'] }}">{{ $tp['status'] ?? '-' }}</span><span class="rounded-lg border px-2 py-1 text-[10px] font-bold" style="border-color:var(--line);color:var(--muted)">{{ $tp['decision'] ?? '-' }}</span></div>
                    </article>
                @empty
                    <div class="soft-panel rounded-2xl p-6 text-center text-sm muted md:col-span-2 xl:col-span-3">Belum ada data tujuan pembelajaran untuk evaluasi ini.</div>
                @endforelse
            </div>
            @if($outcomeNeedsReview->isNotEmpty())
                <div class="mt-5 rounded-2xl border border-amber-200 bg-amber-50/80 p-4 dark:border-amber-300/15 dark:bg-amber-400/[.07]"><p class="text-[10px] font-extrabold uppercase tracking-[.14em] text-amber-700 dark:text-amber-300">Prioritas belajar ulang</p><div class="mt-3 grid gap-3 md:grid-cols-2">@foreach($outcomeNeedsReview->take(4) as $tp)<div class="rounded-xl border border-amber-200/80 bg-white/75 px-4 py-3 dark:border-amber-300/10 dark:bg-slate-950/20"><p class="section-heading text-sm font-extrabold">{{ $tp['label'] }}</p><p class="muted mt-1 text-xs leading-5">{{ $tp['material_direction'] ?? '-' }}</p></div>@endforeach</div></div>
            @endif
        </section>

        <section class="mt-6 grid gap-6 xl:grid-cols-[minmax(0,1fr)_360px]">
            <section id="reviewAnswers" class="ui-card overflow-hidden rounded-[28px]">
                <div class="flex flex-col gap-3 border-b px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6" style="border-color:var(--line)"><div><p class="eyebrow text-[10px] font-extrabold uppercase">Tinjauan jawaban</p><h2 class="section-heading mt-1 text-xl font-extrabold">Lihat kembali setiap jawaban</h2><p class="muted mt-1 text-xs leading-5">Bandingkan jawaban Anda dengan kunci untuk menentukan bagian yang perlu diulang.</p></div><span class="soft-panel shrink-0 rounded-lg px-3 py-2 font-mono text-[11px] font-medium">{{ $metrics['flagged_count'] }} ragu / {{ $metrics['unanswered_count'] }} kosong</span></div>
                <div data-native-scroll tabindex="0" aria-label="Daftar tinjauan jawaban" class="review-scroll custom-scrollbar max-h-[min(760px,calc(100dvh-12rem))] overflow-y-auto">
                    @foreach($reviewItems as $item)
                        @php
                            $interactionType = $item['question']->interaction_type ?? 'multiple_choice';
                            $interactionLabel = ['multiple_choice' => 'Pilihan Ganda', 'image_context' => 'Gambar'][$interactionType] ?? 'Pilihan Ganda';
                            $promptLabel = ['multiple_choice' => 'Konteks Soal', 'image_context' => 'Media Soal'][$interactionType] ?? 'Konteks Soal';
                        @endphp
                        <article class="review-item p-5 sm:p-6">
                            <div class="flex flex-wrap items-center justify-between gap-3"><div class="flex items-center gap-2.5"><span class="grid h-9 w-9 place-items-center rounded-xl border text-sm font-extrabold {{ $item['is_correct'] ? 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-300/20 dark:bg-emerald-400/[.10] dark:text-emerald-200' : 'border-rose-200 bg-rose-50 text-rose-700 dark:border-rose-300/20 dark:bg-rose-400/[.10] dark:text-rose-200' }}">{{ $item['number'] }}</span><div><p class="text-xs font-extrabold {{ $item['is_correct'] ? 'text-emerald-700 dark:text-emerald-300' : 'text-rose-700 dark:text-rose-300' }}">{{ $item['status'] }}</p><p class="subtle text-[10px] font-bold uppercase tracking-[.12em]">{{ $interactionLabel }}</p></div></div>@if($item['is_flagged'])<span class="rounded-lg border border-amber-200 bg-amber-50 px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-[.11em] text-amber-700 dark:border-amber-300/20 dark:bg-amber-400/[.08] dark:text-amber-200">Ragu-ragu</span>@endif</div>
                            @if(!empty($item['question']->media_url) || !empty($item['question']->interaction_prompt))
                                <div class="mt-4 grid gap-3">
                                    @if(!empty($item['question']->media_url))<figure class="overflow-hidden rounded-2xl border" style="border-color:var(--line);background:var(--surface-solid)"><img src="{{ $item['question']->media_url }}" alt="{{ $item['question']->media_caption ?: 'Media soal' }}" class="max-h-80 w-full object-contain" style="background:var(--surface-muted)">@if(!empty($item['question']->media_caption))<figcaption class="border-t px-4 py-2.5 text-xs leading-5 muted" style="border-color:var(--line)">{{ $item['question']->media_caption }}</figcaption>@endif</figure>@endif
                                    @if(!empty($item['question']->interaction_prompt))<div class="rounded-2xl border border-cyan-200 bg-cyan-50/80 px-4 py-3 dark:border-cyan-300/15 dark:bg-cyan-400/[.07]"><p class="text-[10px] font-extrabold uppercase tracking-[.13em] text-cyan-700 dark:text-cyan-200">{{ $promptLabel }}</p><p class="mt-1 text-sm leading-6 text-slate-700 dark:text-slate-200">{{ $item['question']->interaction_prompt }}</p></div>@endif
                                </div>
                            @endif
                            <div class="section-heading mt-4 text-sm font-semibold leading-7 sm:text-[15px]">{!! $item['question']->question_text !!}</div>
                            <div class="mt-4 grid gap-3 md:grid-cols-2"><div class="answer-panel rounded-2xl p-4"><p class="subtle text-[10px] font-extrabold uppercase tracking-[.13em]">Jawaban Anda</p><p class="section-heading mt-2 text-sm leading-6">{{ $item['selected_option']->option_text ?? 'Belum dijawab' }}</p></div><div class="answer-panel answer-correct rounded-2xl p-4"><p class="text-[10px] font-extrabold uppercase tracking-[.13em] text-emerald-700 dark:text-emerald-300">Jawaban benar</p><p class="section-heading mt-2 text-sm leading-6">{{ $item['correct_option']->option_text ?? 'Kunci belum tersedia' }}</p></div></div>
                        </article>
                    @endforeach
                </div>
            </section>

            <aside class="space-y-5">
                <section class="ui-card rounded-[24px] p-5"><p class="eyebrow text-[10px] font-extrabold uppercase">Ringkasan materi</p><h2 class="section-heading mt-1 text-lg font-extrabold">{{ $summary['title'] ?? 'Materi Tailwind CSS' }}</h2>@if(!empty($summary['subtitle']))<span class="mt-3 inline-flex rounded-lg border px-2.5 py-1 text-[10px] font-extrabold text-cyan-700 dark:text-cyan-200" style="border-color:var(--accent-line);background:var(--accent-soft)">{{ $summary['subtitle'] }}</span>@endif<p class="muted mt-4 text-sm leading-6">{{ $summary['summary'] ?? 'Ringkasan materi belum tersedia.' }}</p>@if(!empty($summary['key_points']))<div class="mt-4 space-y-3">@foreach($summary['key_points'] as $point)<div class="flex gap-3"><span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-cyan-500 dark:bg-cyan-300"></span><p class="muted text-sm leading-6">{{ $point }}</p></div>@endforeach</div>@endif<div class="soft-panel mt-5 rounded-2xl px-4 py-3.5"><p class="subtle text-[10px] font-extrabold uppercase tracking-[.13em]">Langkah berikutnya</p><p class="section-heading mt-1 text-sm leading-6">{{ $summary['next_step'] ?? 'Tinjau bagian yang belum stabil lalu lanjutkan latihan.' }}</p></div></section>
                <section class="ui-card rounded-[24px] p-5"><div class="flex items-start justify-between gap-3"><div><p class="eyebrow text-[10px] font-extrabold uppercase">Fokus tindak lanjut</p><h2 class="section-heading mt-1 text-lg font-extrabold">Prioritas belajar</h2></div><span class="soft-panel rounded-lg px-2.5 py-1 text-[10px] font-extrabold">{{ $priorityItems->count() }} poin</span></div><div class="mt-4 space-y-3">@foreach($priorityItems->take(3) as $priority)<div class="priority-card rounded-xl border px-4 py-3 {{ $priorityTone[$priority['tone']] ?? $priorityTone['slate'] }}"><p class="text-sm font-extrabold">{{ $priority['title'] }}</p><p class="mt-1 text-xs leading-5 opacity-80">{{ $priority['detail'] }}</p></div>@endforeach</div></section>
                <section class="ui-card rounded-[24px] p-5"><p class="eyebrow text-[10px] font-extrabold uppercase">Catatan refleksi</p><h2 class="section-heading mt-1 text-lg font-extrabold">Simpan rencana belajar</h2><p class="muted mt-2 text-xs leading-5">Tuliskan konsep yang perlu diperkuat atau strategi yang akan digunakan pada pembelajaran berikutnya.</p>@if(session('success'))<div class="mt-4 rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-2.5 text-xs font-bold text-emerald-700 dark:border-emerald-300/20 dark:bg-emerald-400/[.08] dark:text-emerald-200">{{ session('success') }}</div>@endif<form action="{{ route('quiz.reflection', $attempt->id) }}" method="POST" class="mt-4 space-y-3">@csrf<textarea name="reflection_note" rows="5" maxlength="1000" class="w-full resize-none rounded-2xl border p-4 text-sm leading-6 outline-none transition focus:border-cyan-400 focus:ring-4 focus:ring-cyan-100 dark:focus:border-cyan-300 dark:focus:ring-cyan-400/10" style="border-color:var(--line);background:var(--surface-muted);color:var(--text)" placeholder="Contoh: Saya perlu mengulang konsep grid dan meninjau soal yang belum tepat.">{{ old('reflection_note', $attempt->reflection_note) }}</textarea>@error('reflection_note')<p class="text-xs font-bold text-rose-600 dark:text-rose-300">{{ $message }}</p>@enderror<button type="submit" class="action-primary w-full rounded-xl px-4 py-3 text-xs font-extrabold uppercase tracking-[.12em] transition">Simpan Refleksi</button></form></section>
            </aside>
        </section>
    </main>
</div>
<script>
    (function () {
        const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
        const finePointer = window.matchMedia('(pointer: fine)');
        const scrollHost = document.querySelector('[data-smooth-scroll]');
        const clamp = (value, min, max) => Math.max(min, Math.min(max, value));

        const scrollState = {
            target: scrollHost ? scrollHost.scrollTop : 0,
            frame: null,
            previousTime: 0,
            animating: false,
        };

        function maxHostScroll() {
            return scrollHost ? Math.max(0, scrollHost.scrollHeight - scrollHost.clientHeight) : 0;
        }

        function canUseSmoothWheel() {
            return !!scrollHost && finePointer.matches && !reducedMotion.matches;
        }

        function normaliseWheelDelta(event) {
            if (!scrollHost) return 0;
            if (event.deltaMode === 1) return event.deltaY * 18;
            if (event.deltaMode === 2) return event.deltaY * scrollHost.clientHeight;
            return event.deltaY;
        }

        function getNativeScrollTarget(target) {
            return target instanceof Element
                ? target.closest('[data-native-scroll], textarea, input, select, [contenteditable="true"]')
                : null;
        }

        function nativeTargetCanConsume(target, delta) {
            if (!target || !(target instanceof Element)) return false;
            if (target.scrollHeight <= target.clientHeight + 1) return false;

            const max = Math.max(0, target.scrollHeight - target.clientHeight);
            return (delta < 0 && target.scrollTop > 0) || (delta > 0 && target.scrollTop < max - 1);
        }

        function stopSmoothScroll() {
            if (scrollState.frame) window.cancelAnimationFrame(scrollState.frame);
            scrollState.frame = null;
            scrollState.previousTime = 0;
            scrollState.animating = false;

            if (scrollHost) {
                scrollState.target = scrollHost.scrollTop;
                scrollHost.classList.remove('is-smooth-scrolling');
            }
        }

        function runSmoothScroll(timestamp) {
            if (!scrollHost) return;

            scrollState.animating = true;
            const elapsed = clamp(timestamp - (scrollState.previousTime || timestamp - 16), 8, 48);
            scrollState.previousTime = timestamp;

            const max = maxHostScroll();
            scrollState.target = clamp(scrollState.target, 0, max);

            const current = scrollHost.scrollTop;
            const distance = scrollState.target - current;
            const blend = 1 - Math.exp(-elapsed / 34);

            if (Math.abs(distance) <= 0.35) {
                scrollHost.scrollTop = scrollState.target;
                stopSmoothScroll();
                return;
            }

            scrollHost.scrollTop = current + (distance * blend);
            scrollState.frame = window.requestAnimationFrame(runSmoothScroll);
        }

        function scrollHostTo(top, immediate = false) {
            if (!scrollHost) return;
            scrollState.target = clamp(top, 0, maxHostScroll());

            if (immediate || reducedMotion.matches) {
                scrollHost.scrollTop = scrollState.target;
                stopSmoothScroll();
                return;
            }

            scrollHost.classList.add('is-smooth-scrolling');
            if (!scrollState.frame) {
                scrollState.previousTime = performance.now();
                scrollState.frame = window.requestAnimationFrame(runSmoothScroll);
            }
        }

        function enableSmoothWheel() {
            if (!scrollHost) return;

            scrollHost.addEventListener('wheel', (event) => {
                if (!canUseSmoothWheel() || event.ctrlKey || event.metaKey || event.shiftKey || !event.deltaY) return;

                const delta = normaliseWheelDelta(event);
                const nativeTarget = getNativeScrollTarget(event.target);

                // Input dan textarea mempertahankan perilaku bawaan. Panel review
                // tetap menggulir sendiri sampai mencapai ujungnya.
                if (nativeTarget) {
                    if (nativeTargetCanConsume(nativeTarget, delta)) return;
                    if (!nativeTarget.matches('[data-native-scroll]')) return;
                }

                const max = maxHostScroll();
                if (!max) return;

                const base = scrollState.frame ? scrollState.target : scrollHost.scrollTop;
                const cappedDelta = Math.sign(delta) * Math.min(Math.abs(delta), 190);
                const multiplier = Math.abs(cappedDelta) > 96 ? .74 : .62;
                const next = clamp(base + (cappedDelta * multiplier), 0, max);

                if (Math.abs(next - base) < .1) return;

                event.preventDefault();
                scrollHostTo(next);
            }, { passive: false });

            scrollHost.addEventListener('scroll', () => {
                if (!scrollState.animating) scrollState.target = scrollHost.scrollTop;
            }, { passive: true });

            scrollHost.addEventListener('pointerdown', stopSmoothScroll, { passive: true });
            scrollHost.addEventListener('touchstart', stopSmoothScroll, { passive: true });
            scrollHost.addEventListener('keydown', stopSmoothScroll, { passive: true });
            window.addEventListener('blur', stopSmoothScroll, { passive: true });
            window.addEventListener('resize', stopSmoothScroll, { passive: true });
            document.addEventListener('visibilitychange', () => {
                if (document.hidden) stopSmoothScroll();
            }, { passive: true });
        }

        function enableAnchors() {
            document.querySelectorAll('a[href^="#"]').forEach((link) => {
                link.addEventListener('click', (event) => {
                    const selector = link.getAttribute('href');
                    if (!selector || selector === '#') return;

                    const target = document.querySelector(selector);
                    if (!target || !scrollHost) return;

                    event.preventDefault();
                    const hostBounds = scrollHost.getBoundingClientRect();
                    const targetBounds = target.getBoundingClientRect();
                    const desiredTop = scrollHost.scrollTop + targetBounds.top - hostBounds.top - 20;
                    scrollHostTo(desiredTop);
                });
            });
        }

        function animateIndicators() {
            const apply = () => {
                document.querySelectorAll('.js-score-ring').forEach((ring) => {
                    ring.style.setProperty('--progress', clamp(Number(ring.dataset.progress || 0), 0, 100));
                });
                document.querySelectorAll('.js-progress').forEach((bar) => {
                    bar.style.width = clamp(Number(bar.dataset.progress || 0), 0, 100) + '%';
                });
            };

            if (reducedMotion.matches) {
                apply();
                return;
            }

            window.requestAnimationFrame(() => window.requestAnimationFrame(apply));
        }

        window.addEventListener('DOMContentLoaded', () => {
            window.requestAnimationFrame(() => {
                document.documentElement.classList.add('theme-ready');
                animateIndicators();
                enableSmoothWheel();
                enableAnchors();
            });
        });
    })();
</script>
</body>
</html>
