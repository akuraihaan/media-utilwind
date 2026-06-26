<!DOCTYPE html>
<html lang="id" class="h-full scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Lab | {{ $lab->title ?? 'Praktik' }} | Utilwind</title>

    {{-- Tema mengikuti pengaturan global aplikasi. --}}
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
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        mono: ['DM Mono', 'monospace'],
                    }
                }
            }
        }
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --page: #f5f7fb;
            --glow-a: rgba(14, 165, 233, .10);
            --glow-b: rgba(99, 102, 241, .07);
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
        html, body { height: 100%; }
        html { scroll-behavior: auto; }
        body {
            height: 100%;
            min-height: 100%;
            overflow: hidden;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background:
                radial-gradient(circle at 10% 0%, var(--glow-a), transparent 30rem),
                radial-gradient(circle at 95% 8%, var(--glow-b), transparent 28rem),
                var(--page);
            color: var(--text);
            overscroll-behavior-y: none;
        }

        .font-mono { font-family: 'DM Mono', monospace; }
        .page-shell { isolation: isolate; }
        .page-shell::before {
            content: "";
            position: fixed;
            inset: 0;
            z-index: -1;
            pointer-events: none;
            background-image:
                linear-gradient(rgba(148,163,184,.035) 1px, transparent 1px),
                linear-gradient(90deg, rgba(148,163,184,.035) 1px, transparent 1px);
            background-size: 44px 44px;
            mask-image: linear-gradient(to bottom, black, transparent 78%);
        }

        .ui-card,
        .ui-card-strong,
        .metric-tile,
        .task-row,
        .outcome-card,
        .action-primary,
        .action-secondary,
        .priority-card,
        .result-status {
            will-change: transform, box-shadow, border-color;
        }

        .ui-card {
            background: var(--surface);
            border: 1px solid var(--line);
            box-shadow: var(--shadow-soft);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }

        .ui-card-strong {
            background: var(--surface-solid);
            border: 1px solid var(--line);
            box-shadow: var(--shadow);
        }

        .section-heading { color: var(--text); letter-spacing: -.025em; }
        .eyebrow { color: var(--accent); letter-spacing: .16em; }
        .muted { color: var(--muted); }
        .subtle { color: var(--subtle); }

        .score-ring {
            --progress: 0;
            --ring-color: var(--accent);
            --ring-track: rgba(8,145,178,.16);
            --ring-line: rgba(8,145,178,.22);
            width: 154px;
            height: 154px;
            border-radius: 999px;
            padding: 10px;
            display: grid;
            place-items: center;
            background: conic-gradient(var(--ring-color) calc(var(--progress) * 1%), var(--ring-track) 0);
            box-shadow: inset 0 0 0 1px var(--ring-line), 0 18px 34px rgba(15,23,42,.10);
            transition: --progress 900ms cubic-bezier(.22,.61,.36,1);
        }

        .score-ring__inner {
            width: 100%;
            height: 100%;
            display: grid;
            place-items: center;
            border-radius: 999px;
            text-align: center;
            background: var(--surface-solid);
            border: 1px solid var(--line);
        }

        .metric-tile {
            background: var(--surface-muted);
            border: 1px solid var(--line);
            transition: transform 200ms cubic-bezier(.22,.61,.36,1), border-color 200ms ease, box-shadow 200ms ease, background-color 200ms ease;
        }

        .metric-tile:hover {
            transform: translateY(-2px);
            border-color: var(--line-strong);
            box-shadow: var(--shadow-soft);
        }

        .soft-panel { background: var(--surface-muted); border: 1px solid var(--line); }

        .outcome-card {
            position: relative;
            overflow: hidden;
            background: var(--surface-muted);
            border: 1px solid var(--line);
            transition: transform 200ms cubic-bezier(.22,.61,.36,1), border-color 200ms ease, box-shadow 200ms ease;
        }

        .outcome-card::before {
            content: "";
            position: absolute;
            inset: 0 auto 0 0;
            width: 4px;
            background: var(--outcome-color, var(--accent));
        }

        .outcome-card:hover {
            transform: translateY(-2px);
            border-color: var(--line-strong);
            box-shadow: var(--shadow-soft);
        }

        .task-row {
            border-bottom: 1px solid var(--line);
            transition: background-color 180ms ease, transform 180ms cubic-bezier(.22,.61,.36,1);
        }

        .task-row:hover { background: color-mix(in srgb, var(--surface-muted) 86%, transparent); }
        .task-row:last-child { border-bottom: 0; }

        .priority-card {
            transition: transform 180ms cubic-bezier(.22,.61,.36,1), box-shadow 180ms ease;
        }

        .priority-card:hover { transform: translateY(-1px); box-shadow: 0 10px 22px rgba(15,23,42,.06); }

        .action-primary {
            background: var(--accent);
            color: #fff;
            box-shadow: 0 12px 24px color-mix(in srgb, var(--accent) 25%, transparent);
            transition: transform 180ms cubic-bezier(.22,.61,.36,1), background-color 180ms ease, box-shadow 180ms ease;
        }

        .action-primary:hover { background: var(--accent-strong); transform: translateY(-1px); }
        .action-primary:active { transform: translateY(0) scale(.985); }

        .action-secondary {
            background: var(--surface-solid);
            color: var(--text);
            border: 1px solid var(--line-strong);
            transition: transform 180ms cubic-bezier(.22,.61,.36,1), color 180ms ease, border-color 180ms ease, background-color 180ms ease;
        }

        .action-secondary:hover { color: var(--accent-strong); border-color: var(--accent-line); transform: translateY(-1px); }
        .action-secondary:active { transform: translateY(0) scale(.985); }

        .progress-track { background: color-mix(in srgb, var(--line) 72%, transparent); }
        .progress-fill {
            width: 0;
            background: linear-gradient(90deg, var(--accent), #6366f1);
            transition: width 900ms cubic-bezier(.22,.61,.36,1);
        }

        .code-preview {
            background: #08111f;
            color: #e2e8f0;
            border: 1px solid rgba(148,163,184,.18);
        }

        .custom-scrollbar::-webkit-scrollbar { width: 8px; height: 8px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: color-mix(in srgb, var(--subtle) 42%, transparent); border-radius: 999px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: color-mix(in srgb, var(--accent) 52%, transparent); }

        [data-native-scroll] {
            overscroll-behavior: contain;
            -webkit-overflow-scrolling: touch;
            scrollbar-gutter: stable;
        }

        .theme-ready,
        .theme-ready * {
            transition-property: background-color, border-color, color, box-shadow;
            transition-duration: 180ms;
            transition-timing-function: ease;
        }



        /* ==========================================================
           POLA INTERAKSI DIREKTORI — HASIL LAB
           Scroll utama dibuat sebagai satu host khusus. Panel tugas
           dan kode tetap native agar tidak saling berebut saat digulir.
           ========================================================== */
        :root {
            --result-ease: cubic-bezier(.22, .61, .36, 1);
            --result-soft-ease: cubic-bezier(.16, 1, .3, 1);
        }

        .page-shell {
            height: 100%;
            min-height: 0;
            overflow: hidden;
        }

        .smooth-result-scroll {
            scroll-behavior: auto;
            scroll-padding: 1.5rem 0 3rem;
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
            scroll-behavior: smooth;
            overscroll-behavior: contain;
            -webkit-overflow-scrolling: touch;
            touch-action: pan-y;
            scrollbar-gutter: stable;
        }
        [data-native-scroll]::-webkit-scrollbar,
        .smooth-result-scroll::-webkit-scrollbar { width: 6px; height: 6px; }
        [data-native-scroll]::-webkit-scrollbar-thumb,
        .smooth-result-scroll::-webkit-scrollbar-thumb {
            border: 2px solid transparent;
            border-radius: 999px;
            background: color-mix(in srgb, var(--subtle) 42%, transparent);
            background-clip: padding-box;
            transition: background-color .24s var(--result-ease);
        }
        [data-native-scroll]::-webkit-scrollbar-thumb:hover,
        .smooth-result-scroll::-webkit-scrollbar-thumb:hover {
            background-color: color-mix(in srgb, var(--accent) 58%, transparent);
        }

        .ui-card,
        .ui-card-strong,
        .metric-tile,
        .outcome-card,
        .task-row,
        .priority-card,
        .action-primary,
        .action-secondary,
        .result-status {
            transition-timing-function: var(--result-ease);
        }
        .ui-card,
        .ui-card-strong {
            transition: border-color .3s var(--result-ease), box-shadow .3s var(--result-ease), background-color .3s var(--result-ease);
        }
        .metric-tile {
            transition: transform .28s var(--result-soft-ease), border-color .28s var(--result-ease), box-shadow .28s var(--result-ease), background-color .28s var(--result-ease);
        }
        .outcome-card {
            transition: transform .28s var(--result-soft-ease), border-color .28s var(--result-ease), box-shadow .28s var(--result-ease), background-color .28s var(--result-ease);
        }
        .task-row {
            transition: background-color .26s var(--result-ease), box-shadow .26s var(--result-ease), color .22s var(--result-ease);
        }
        .priority-card {
            transition: transform .24s var(--result-soft-ease), border-color .24s var(--result-ease), background-color .24s var(--result-ease), box-shadow .24s var(--result-ease);
        }
        .action-primary,
        .action-secondary {
            transition: transform .22s var(--result-ease), color .22s var(--result-ease), background-color .22s var(--result-ease), border-color .22s var(--result-ease), box-shadow .22s var(--result-ease), opacity .22s var(--result-ease);
        }
        .score-ring {
            transition: --progress 900ms var(--result-soft-ease), transform .34s var(--result-soft-ease), box-shadow .34s var(--result-ease), filter .34s var(--result-ease);
        }
        .progress-fill { transition: width .9s var(--result-soft-ease), filter .28s var(--result-ease); }

        @media (hover: hover) and (pointer: fine) {
            .metric-tile:hover,
            .outcome-card:hover {
                transform: translateY(-1px);
                border-color: var(--line-strong);
                box-shadow: 0 14px 28px -22px rgba(15,23,42,.38);
            }
            .dark .metric-tile:hover,
            .dark .outcome-card:hover { box-shadow: 0 18px 32px -24px rgba(0,0,0,.74); }
            .task-row:hover {
                background: linear-gradient(90deg, color-mix(in srgb, var(--accent) 7%, var(--surface-muted)), transparent 72%);
                box-shadow: inset 3px 0 0 color-mix(in srgb, var(--accent) 48%, transparent);
            }
            .priority-card:hover {
                transform: translateY(-1px);
                box-shadow: 0 12px 24px -18px rgba(15,23,42,.28);
            }
            .action-primary:hover,
            .action-secondary:hover { transform: translateY(-1px); }
            .score-ring:hover {
                transform: scale(1.018);
                box-shadow: inset 0 0 0 1px var(--ring-line), 0 22px 38px -18px rgba(15,23,42,.34);
                filter: saturate(1.04);
            }
            .metric-tile:hover .progress-fill { filter: saturate(1.08) brightness(1.02); }
        }

        @media (hover: none), (pointer: coarse) {
            .metric-tile:hover,
            .outcome-card:hover,
            .priority-card:hover,
            .action-primary:hover,
            .action-secondary:hover,
            .score-ring:hover { transform: none; }
            .task-row:hover { background: transparent; box-shadow: none; }
        }

        @media (prefers-reduced-motion: reduce) {
            html { scroll-behavior: auto; }
            .theme-ready,
            .theme-ready *,
            .metric-tile,
            .outcome-card,
            .task-row,
            .priority-card,
            .action-primary,
            .action-secondary,
            .progress-fill,
            .smooth-result-scroll,
            [data-native-scroll],
            .score-ring { transition: none !important; scroll-behavior: auto !important; }
        }
    </style>
</head>
<body class="h-screen overflow-hidden selection:bg-cyan-200/70 dark:selection:bg-cyan-300/20">
    @php
        $summary = $chapterSummary ?? [];
        $theme = $summary['theme'] ?? 'cyan';
        $themeMap = [
            'cyan' => [
                'soft' => 'bg-cyan-50 text-cyan-700 border-cyan-200',
                'text' => 'text-cyan-700',
                'solid' => 'bg-cyan-600 hover:bg-cyan-700',
                'bar' => 'bg-cyan-500',
                'wash' => 'from-cyan-50 via-white to-blue-50',
            ],
            'indigo' => [
                'soft' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                'text' => 'text-indigo-700',
                'solid' => 'bg-indigo-600 hover:bg-indigo-700',
                'bar' => 'bg-indigo-500',
                'wash' => 'from-indigo-50 via-white to-violet-50',
            ],
            'fuchsia' => [
                'soft' => 'bg-fuchsia-50 text-fuchsia-700 border-fuchsia-200',
                'text' => 'text-fuchsia-700',
                'solid' => 'bg-fuchsia-600 hover:bg-fuchsia-700',
                'bar' => 'bg-fuchsia-500',
                'wash' => 'from-fuchsia-50 via-white to-pink-50',
            ],
        ];
        $tone = $themeMap[$theme] ?? $themeMap['cyan'];
        $scoreTone = $score >= 90
            ? ['text' => 'text-emerald-700', 'soft' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'bar' => 'bg-emerald-500']
            : ($isPassed
                ? ['text' => 'text-cyan-700', 'soft' => 'bg-cyan-50 text-cyan-700 border-cyan-200', 'bar' => 'bg-cyan-500']
                : ['text' => 'text-red-700', 'soft' => 'bg-red-50 text-red-700 border-red-200', 'bar' => 'bg-red-500']);
        $remainingSteps = max(0, (int) ($metrics['total_steps'] ?? 0) - (int) ($metrics['completed_steps'] ?? 0));
        $scoreGap = max(0, (int) ($metrics['passing_grade'] ?? 70) - (int) $score);
        $firstFailedRule = null;

        foreach (($reviewItems ?? collect()) as $reviewItem) {
            if (!($reviewItem['is_completed'] ?? false) && !empty($reviewItem['failed_rule'])) {
                $firstFailedRule = $reviewItem['failed_rule'];
                break;
            }
        }

        $labPriorityItems = collect();

        if ($remainingSteps > 0) {
            $labPriorityItems->push([
                'label' => 'Selesaikan tugas tersisa',
                'detail' => $remainingSteps . ' tugas belum tervalidasi. Mulai dari instruksi yang statusnya belum selesai.',
                'tone' => 'border-red-200 bg-red-50 text-red-700',
            ]);
        }

        if ($scoreGap > 0) {
            $labPriorityItems->push([
                'label' => 'Kejar batas lulus',
                'detail' => 'Tambahkan minimal ' . $scoreGap . ' poin agar hasil praktik memenuhi standar lab.',
                'tone' => 'border-amber-200 bg-amber-50 text-amber-700',
            ]);
        }

        if ($firstFailedRule) {
            $labPriorityItems->push([
                'label' => 'Periksa aturan validasi',
                'detail' => 'Aturan pertama yang belum terpenuhi: ' . $firstFailedRule . '.',
                'tone' => 'border-cyan-200 bg-cyan-50 text-cyan-700',
            ]);
        }

        if (trim((string) ($sourceCode ?? '')) === '') {
            $labPriorityItems->push([
                'label' => 'Simpan bukti kode',
                'detail' => 'Cuplikan kode belum tersedia, pastikan kode tersimpan saat mengumpulkan lab berikutnya.',
                'tone' => 'border-slate-200 bg-slate-50 text-slate-700',
            ]);
        }

        if ($labPriorityItems->isEmpty()) {
            $labPriorityItems->push([
                'label' => 'Rapikan implementasi',
                'detail' => 'Tugas utama sudah terpenuhi. Gunakan waktu lanjutan untuk merapikan struktur, penamaan class, dan konsistensi tampilan.',
                'tone' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
            ]);
        }

        $studyRoutes = [
            1 => ['title' => 'Bab 1: Pendahuluan Tailwind CSS', 'route' => 'courses.htmldancss'],
            2 => ['title' => 'Bab 2: Layouting', 'route' => 'courses.layout-basics'],
            3 => ['title' => 'Bab 3: Styling Komponen', 'route' => 'courses.typography'],
        ];
        $studyTarget = $studyRoutes[(int) ($lab->chapter_id ?? $lab->id ?? 1)] ?? $studyRoutes[1];
        $studyUrl = \Illuminate\Support\Facades\Route::has($studyTarget['route'])
            ? route($studyTarget['route'])
            : route('courses.curriculum');
        $outcomeAnalytics = $analysis['outcome_analytics'] ?? [];
        $outcomeRows = collect($outcomeAnalytics['outcomes'] ?? []);
        $outcomeNeedsReview = collect($outcomeAnalytics['needs_review'] ?? []);
        $outcomeToneMap = [
            'emerald' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
            'cyan' => 'border-cyan-200 bg-cyan-50 text-cyan-700',
            'amber' => 'border-amber-200 bg-amber-50 text-amber-700',
            'red' => 'border-red-200 bg-red-50 text-red-700',
            'slate' => 'border-slate-200 bg-slate-50 text-slate-700',
        ];
    @endphp
    @php
        $themeTokens = [
            'cyan' => ['accent' => '#0891b2', 'strong' => '#0e7490', 'soft' => '#ecfeff', 'line' => '#a5f3fc'],
            'indigo' => ['accent' => '#4f46e5', 'strong' => '#4338ca', 'soft' => '#eef2ff', 'line' => '#c7d2fe'],
            'fuchsia' => ['accent' => '#c026d3', 'strong' => '#a21caf', 'soft' => '#fdf4ff', 'line' => '#f5d0fe'],
        ];
        $themeToken = $themeTokens[$theme] ?? $themeTokens['cyan'];

        $scoreVisual = $score >= 90
            ? ['color' => '#10b981', 'track' => 'rgba(16,185,129,.16)', 'line' => 'rgba(16,185,129,.22)', 'label' => 'Sangat Baik']
            : ($isPassed
                ? ['color' => $themeToken['accent'], 'track' => 'rgba(8,145,178,.16)', 'line' => 'rgba(8,145,178,.22)', 'label' => 'Memenuhi Standar']
                : ['color' => '#e11d48', 'track' => 'rgba(225,29,72,.16)', 'line' => 'rgba(225,29,72,.22)', 'label' => 'Perlu Perbaikan']);
    @endphp

<div class="page-shell h-full min-h-0" style="--accent: {{ $themeToken['accent'] }}; --accent-strong: {{ $themeToken['strong'] }}; --accent-soft: {{ $themeToken['soft'] }}; --accent-line: {{ $themeToken['line'] }};">
    <main data-smooth-scroll class="smooth-result-scroll custom-scrollbar mx-auto h-full w-full max-w-[1320px] overflow-y-auto overflow-x-hidden px-4 py-6 sm:px-6 sm:py-8 lg:px-8 lg:py-10" tabindex="-1">
        <header class="ui-card-strong overflow-hidden rounded-[28px]">
            <div class="flex flex-col gap-6 px-5 py-6 sm:px-7 lg:flex-row lg:items-center lg:justify-between lg:px-8 lg:py-7">
                <div class="min-w-0">
                    <p class="eyebrow text-[10px] font-extrabold uppercase">
                        {{ $isPassed ? 'Praktik telah diselesaikan' : 'Tindak lanjut praktik' }}
                    </p>
                    <h1 class="section-heading mt-2 text-2xl font-extrabold sm:text-3xl">
                        {{ $lab->title ?? 'Praktik Lab' }}
                    </h1>
                    <p class="muted mt-2 max-w-2xl text-sm leading-6">
                        {{ $isPassed
                            ? 'Hasil praktik telah tersimpan. Gunakan ringkasan berikut untuk memahami capaian tugas, tujuan praktik, dan langkah belajar selanjutnya.'
                            : 'Hasil belum memenuhi standar lab. Tinjau tugas yang belum tervalidasi sebelum memperbaiki kode dan mengumpulkan ulang.' }}
                    </p>
                </div>

                <div class="flex shrink-0 flex-wrap gap-2.5">
                    @if(!$isPassed && $lab)
                        <a href="{{ route('lab.start', ['id' => $lab->id]) }}" class="action-primary inline-flex min-h-11 items-center justify-center rounded-xl px-4 text-sm font-bold">
                            Ulangi Lab
                        </a>
                    @endif
                    @if($lab)
                        <a href="{{ route('lab.workspace.history', ['historyId' => $history->id]) }}" class="action-secondary inline-flex min-h-11 items-center justify-center rounded-xl px-4 text-sm font-bold">
                            Tinjau Ruang Kerja
                        </a>
                    @endif
                    <a href="{{ route('dashboard') }}" class="action-secondary inline-flex min-h-11 items-center justify-center rounded-xl px-4 text-sm font-bold">
                        Kembali ke Dasbor
                    </a>
                </div>
            </div>
            <div class="h-[3px] w-full" style="background:linear-gradient(90deg, {{ $themeToken['accent'] }}, #6366f1);"></div>
        </header>

        @if(session('info') || session('success') || session('error'))
            <div class="mt-5 rounded-2xl border px-4 py-3 text-sm font-semibold
                {{ session('error')
                    ? 'border-rose-200 bg-rose-50 text-rose-700 dark:border-rose-300/20 dark:bg-rose-400/[.08] dark:text-rose-200'
                    : 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-300/20 dark:bg-emerald-400/[.08] dark:text-emerald-200' }}">
                {{ session('error') ?? session('success') ?? session('info') }}
            </div>
        @endif

        @if(!$isPassed)
            <section class="ui-card mt-5 rounded-[24px] border-rose-200/80 p-5 dark:border-rose-300/15 sm:p-6">
                <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <p class="text-[10px] font-extrabold uppercase tracking-[.16em] text-rose-600 dark:text-rose-300">Rencana perbaikan</p>
                        <h2 class="section-heading mt-1 text-lg font-extrabold">Perbaiki tugas yang belum tervalidasi</h2>
                        <p class="muted mt-2 max-w-3xl text-sm leading-6">
                            Mulai dari {{ $studyTarget['title'] }}, tinjau aturan yang belum terpenuhi, perbaiki kode di ruang kerja, lalu kumpulkan ulang setelah skor memenuhi standar lab.
                        </p>
                    </div>
                    <div class="flex flex-col gap-2 sm:flex-row">
                        <a href="{{ $studyUrl }}" class="action-secondary inline-flex min-h-10 items-center justify-center rounded-xl px-4 text-xs font-extrabold">
                            Pelajari Materi
                        </a>
                        <a href="#reviewLabTasks" class="inline-flex min-h-10 items-center justify-center rounded-xl border px-4 text-xs font-extrabold text-rose-700 transition hover:bg-rose-50 dark:text-rose-200 dark:hover:bg-rose-400/[.10]" style="border-color:rgba(225,29,72,.28);">
                            Tinjau Tugas
                        </a>
                    </div>
                </div>
            </section>
        @endif

        <section class="mt-6 grid gap-5 xl:grid-cols-[minmax(0,1.42fr)_minmax(370px,.58fr)]">
            <article class="ui-card-strong rounded-[28px] p-5 sm:p-6 lg:p-7">
                <div class="grid items-center gap-6 sm:grid-cols-[154px_minmax(0,1fr)]">
                    <div class="score-ring mx-auto sm:mx-0 js-score-ring"
                         data-progress="{{ min(100, max(0, $score)) }}"
                         style="--progress:0; --ring-color:{{ $scoreVisual['color'] }}; --ring-track:{{ $scoreVisual['track'] }}; --ring-line:{{ $scoreVisual['line'] }};">
                        <div class="score-ring__inner">
                            <span class="font-mono text-4xl font-medium {{ $scoreTone['text'] }}">{{ $score }}</span>
                            <span class="subtle -mt-1 text-[10px] font-bold uppercase tracking-[.16em]">dari 100</span>
                        </div>
                    </div>

                    <div class="min-w-0 text-center sm:text-left">
                        <p class="eyebrow text-[10px] font-extrabold uppercase">Hasil akhir</p>
                        <h2 class="section-heading mt-1 text-2xl font-extrabold">{{ $feedback['level'] }}</h2>
                        <p class="muted mt-3 max-w-2xl text-sm leading-6">{{ $feedback['message'] }}</p>
                        <div class="mt-4 inline-flex items-center gap-2 rounded-xl border px-3 py-2 text-xs font-bold {{ $scoreTone['soft'] }}">
                            <span class="h-1.5 w-1.5 rounded-full" style="background:{{ $scoreVisual['color'] }};"></span>
                            {{ $isPassed ? 'Memenuhi standar lab ' . ($metrics['passing_grade'] ?? 70) : 'Belum memenuhi standar lab ' . ($metrics['passing_grade'] ?? 70) }}
                        </div>
                    </div>
                </div>

                <div class="mt-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="metric-tile rounded-2xl p-4">
                        <p class="subtle text-[10px] font-extrabold uppercase tracking-[.14em]">Kelengkapan tugas</p>
                        <p class="section-heading mt-2 text-2xl font-extrabold">{{ $metrics['completion_percent'] }}%</p>
                        <p class="muted mt-1 text-xs leading-5">{{ $metrics['completed_steps'] }} dari {{ $metrics['total_steps'] }} tugas tervalidasi</p>
                    </div>
                    <div class="metric-tile rounded-2xl p-4">
                        <p class="subtle text-[10px] font-extrabold uppercase tracking-[.14em]">Poin tugas</p>
                        <p class="mt-2 text-2xl font-extrabold text-emerald-600 dark:text-emerald-300">{{ $metrics['earned_points'] }}</p>
                        <p class="muted mt-1 text-xs leading-5">dari {{ $metrics['total_points'] }} poin yang tersedia</p>
                    </div>
                    <div class="metric-tile rounded-2xl p-4">
                        <p class="subtle text-[10px] font-extrabold uppercase tracking-[.14em]">Durasi</p>
                        <p class="section-heading font-mono mt-2 text-2xl font-medium">{{ $metrics['duration_text'] }}</p>
                        <p class="muted mt-1 text-xs leading-5">Waktu pengerjaan praktik</p>
                    </div>
                    <div class="metric-tile rounded-2xl p-4">
                        <p class="subtle text-[10px] font-extrabold uppercase tracking-[.14em]">Standar lulus</p>
                        <p class="mt-2 text-2xl font-extrabold text-amber-600 dark:text-amber-300">{{ $metrics['passing_grade'] }}</p>
                        <p class="muted mt-1 text-xs leading-5">{{ $isPassed ? 'Target telah dicapai' : $scoreGap . ' poin lagi diperlukan' }}</p>
                    </div>
                </div>
            </article>

            <aside class="ui-card rounded-[28px] p-5 sm:p-6">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="eyebrow text-[10px] font-extrabold uppercase">Status pengerjaan</p>
                        <h2 class="section-heading mt-1 text-lg font-extrabold">Ringkasan praktik</h2>
                    </div>
                    <span class="rounded-lg border px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-[.12em] {{ $scoreTone['soft'] }}">
                        {{ $isPassed ? 'Lulus' : 'Perbaikan' }}
                    </span>
                </div>

                <div class="mt-5 space-y-2.5">
                    <div class="soft-panel flex items-center justify-between rounded-xl px-4 py-3">
                        <span class="muted text-sm">Tugas belum selesai</span>
                        <span class="section-heading text-sm font-extrabold">{{ $remainingSteps }}</span>
                    </div>
                    <div class="soft-panel flex items-center justify-between rounded-xl px-4 py-3">
                        <span class="muted text-sm">Aturan perlu diperiksa</span>
                        <span class="section-heading text-sm font-extrabold">{{ $firstFailedRule ? 'Ada' : 'Tidak ada' }}</span>
                    </div>
                    <div class="soft-panel flex items-center justify-between rounded-xl px-4 py-3">
                        <span class="muted text-sm">Bukti kode</span>
                        <span class="section-heading text-sm font-extrabold">{{ trim((string) ($sourceCode ?? '')) === '' ? 'Belum ada' : 'Tersimpan' }}</span>
                    </div>
                </div>

                <div class="mt-5">
                    <div class="mb-2 flex items-center justify-between">
                        <span class="subtle text-[10px] font-extrabold uppercase tracking-[.14em]">Penyelesaian tugas</span>
                        <span class="text-xs font-extrabold" style="color:var(--accent);">{{ $metrics['completion_percent'] }}%</span>
                    </div>
                    <div class="progress-track h-2 overflow-hidden rounded-full">
                        <div class="progress-fill js-progress h-full rounded-full" data-progress="{{ min(100, max(0, $metrics['completion_percent'])) }}"></div>
                    </div>
                </div>
            </aside>
        </section>

        <section class="ui-card mt-6 rounded-[28px] p-5 sm:p-6 lg:p-7">
            <div class="flex flex-col gap-4 border-b pb-5" style="border-color:var(--line)">
                <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <p class="eyebrow text-[10px] font-extrabold uppercase">Analitik tujuan praktik</p>
                        <h2 class="section-heading mt-1 text-xl font-extrabold">Capaian berdasarkan tujuan praktik</h2>
                        <p class="muted mt-2 max-w-3xl text-sm leading-6">
                            {{ $outcomeAnalytics['summary_text'] ?? 'Belum ada analitik tujuan praktik.' }}
                        </p>
                    </div>
                    <div class="soft-panel shrink-0 rounded-xl px-4 py-3">
                        <p class="subtle text-[10px] font-extrabold uppercase tracking-[.13em]">Keputusan belajar</p>
                        <p class="section-heading mt-1 text-sm font-extrabold">{{ $outcomeAnalytics['decision'] ?? 'Belum ada data' }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-5 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                @forelse($outcomeRows as $tp)
                    @php
                        $tpToneKey = $tp['tone'] ?? 'slate';
                        $tpColor = [
                            'emerald' => '#10b981',
                            'cyan' => '#0891b2',
                            'amber' => '#d97706',
                            'red' => '#e11d48',
                            'slate' => '#64748b',
                        ][$tpToneKey] ?? '#64748b';
                        $tpText = [
                            'emerald' => 'text-emerald-700 dark:text-emerald-300',
                            'cyan' => 'text-cyan-700 dark:text-cyan-200',
                            'amber' => 'text-amber-700 dark:text-amber-300',
                            'red' => 'text-rose-700 dark:text-rose-300',
                            'slate' => 'text-slate-700 dark:text-slate-300',
                        ][$tpToneKey] ?? 'text-slate-700 dark:text-slate-300';
                    @endphp
                    <article class="outcome-card rounded-2xl p-4 pl-5" style="--outcome-color:{{ $tpColor }};">
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0">
                                <p class="text-[10px] font-extrabold uppercase tracking-[.13em] {{ $tpText }}">{{ $tp['display_code'] ?? $tp['code'] ?? 'TP' }}</p>
                                <h3 class="section-heading mt-1 text-sm font-extrabold leading-6">{{ $tp['title'] ?? 'Tujuan Praktik' }}</h3>
                            </div>
                            <span class="font-mono shrink-0 text-2xl font-medium {{ $tpText }}">{{ $tp['mastery_percent'] ?? 0 }}%</span>
                        </div>

                        <div class="mt-4 space-y-3 text-xs leading-5">
                            <div class="rounded-xl border px-3 py-2.5" style="border-color:var(--line);background:var(--surface-solid)">
                                <p class="subtle text-[9px] font-extrabold uppercase tracking-[.13em]">Data aktivitas</p>
                                <p class="muted mt-1">{{ $tp['activity_data'] ?? '-' }}</p>
                            </div>
                            <p class="muted"><span class="section-heading font-extrabold">Capaian:</span> {{ $tp['mastery_statement'] ?? '-' }}</p>
                            <p class="muted"><span class="section-heading font-extrabold">Arah materi:</span> {{ $tp['material_direction'] ?? '-' }}</p>
                        </div>

                        <div class="mt-4 flex items-center justify-between gap-2">
                            <span class="text-[10px] font-extrabold uppercase tracking-[.11em] {{ $tpText }}">{{ $tp['status'] ?? '-' }}</span>
                            <span class="rounded-lg border px-2 py-1 text-[10px] font-bold" style="border-color:var(--line);color:var(--muted)">{{ $tp['decision'] ?? '-' }}</span>
                        </div>
                    </article>
                @empty
                    <div class="soft-panel rounded-2xl p-6 text-center text-sm muted md:col-span-2 xl:col-span-3">
                        Belum ada tujuan praktik untuk dianalisis.
                    </div>
                @endforelse
            </div>

            @if($outcomeNeedsReview->isNotEmpty())
                <div class="mt-5 rounded-2xl border border-amber-200 bg-amber-50/80 p-4 dark:border-amber-300/15 dark:bg-amber-400/[.07]">
                    <p class="text-[10px] font-extrabold uppercase tracking-[.14em] text-amber-700 dark:text-amber-300">Prioritas perbaikan praktik</p>
                    <div class="mt-3 grid gap-3 md:grid-cols-2">
                        @foreach($outcomeNeedsReview->take(4) as $tp)
                            <div class="rounded-xl border border-amber-200/80 bg-white/75 px-4 py-3 dark:border-amber-300/10 dark:bg-slate-950/20">
                                <p class="section-heading text-sm font-extrabold">{{ $tp['label'] }}</p>
                                <p class="muted mt-1 text-xs leading-5">{{ $tp['material_direction'] ?? '-' }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </section>

        <section class="mt-6 grid gap-6 xl:grid-cols-[minmax(0,1fr)_360px]">
            <section class="min-w-0 space-y-6">
                <section id="reviewLabTasks" class="ui-card overflow-hidden rounded-[28px]">
                    <div class="flex flex-col gap-3 border-b px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6" style="border-color:var(--line)">
                        <div>
                            <p class="eyebrow text-[10px] font-extrabold uppercase">Tinjauan tugas</p>
                            <h2 class="section-heading mt-1 text-xl font-extrabold">Validasi setiap tugas lab</h2>
                            <p class="muted mt-1 text-xs leading-5">Periksa status, aturan validasi, dan bagian yang perlu diperbaiki sebelum mengulang lab.</p>
                        </div>
                        <span class="soft-panel shrink-0 rounded-lg px-3 py-2 font-mono text-[11px] font-medium">
                            {{ $metrics['completed_steps'] }} selesai / {{ $remainingSteps }} perlu ditinjau
                        </span>
                    </div>

                    <div data-native-scroll class="custom-scrollbar max-h-[760px] overflow-y-auto">
                        @forelse($reviewItems as $item)
                            <article class="task-row p-5 sm:p-6">
                                <div class="flex flex-wrap items-start justify-between gap-3">
                                    <div class="flex min-w-0 items-center gap-3">
                                        <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl border text-sm font-extrabold
                                            {{ $item['is_completed']
                                                ? 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-300/20 dark:bg-emerald-400/[.10] dark:text-emerald-200'
                                                : 'border-rose-200 bg-rose-50 text-rose-700 dark:border-rose-300/20 dark:bg-rose-400/[.10] dark:text-rose-200' }}">
                                            {{ $item['number'] }}
                                        </span>
                                        <div class="min-w-0">
                                            <h3 class="section-heading truncate text-sm font-extrabold">{{ $item['step']->title }}</h3>
                                            <p class="mt-0.5 text-[10px] font-extrabold uppercase tracking-[.12em] {{ $item['is_completed'] ? 'text-emerald-700 dark:text-emerald-300' : 'text-rose-700 dark:text-rose-300' }}">
                                                {{ $item['status'] }}
                                            </p>
                                        </div>
                                    </div>
                                    <span class="soft-panel shrink-0 rounded-lg px-2.5 py-1 text-[10px] font-extrabold">{{ $item['points'] }} poin</span>
                                </div>

                                @if($item['step']->instruction)
                                    <p class="muted mt-4 text-sm leading-6">{{ $item['step']->instruction }}</p>
                                @endif

                                <div class="mt-4 flex flex-wrap gap-2">
                                    @forelse($item['rules'] as $rule)
                                        <span class="rounded-lg border px-2.5 py-1 text-[11px] font-mono font-bold
                                            {{ $item['is_completed']
                                                ? 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-300/20 dark:bg-emerald-400/[.08] dark:text-emerald-200'
                                                : 'border-slate-200 bg-slate-50 text-slate-600 dark:border-white/10 dark:bg-white/5 dark:text-slate-300' }}">
                                            {{ $rule }}
                                        </span>
                                    @empty
                                        <span class="soft-panel rounded-lg px-2.5 py-1 text-[11px] font-bold muted">Tidak ada aturan khusus</span>
                                    @endforelse
                                </div>

                                @if(!$item['is_completed'] && $item['failed_rule'])
                                    <div class="mt-4 rounded-xl border border-rose-200 bg-rose-50 px-3.5 py-3 text-xs leading-5 text-rose-700 dark:border-rose-300/20 dark:bg-rose-400/[.08] dark:text-rose-200">
                                        <span class="font-extrabold">Aturan belum terpenuhi:</span>
                                        <span class="font-mono">{{ $item['failed_rule'] }}</span>
                                    </div>
                                @endif
                            </article>
                        @empty
                            <div class="p-8 text-center text-sm muted">Belum ada tugas yang tercatat untuk lab ini.</div>
                        @endforelse
                    </div>
                </section>

                <section class="ui-card overflow-hidden rounded-[28px]">
                    <div class="border-b px-5 py-5 sm:px-6" style="border-color:var(--line)">
                        <p class="eyebrow text-[10px] font-extrabold uppercase">Bukti implementasi</p>
                        <h2 class="section-heading mt-1 text-xl font-extrabold">Cuplikan kode akhir</h2>
                        <p class="muted mt-1 text-xs leading-5">Kode yang tersimpan ketika lab dikumpulkan.</p>
                    </div>
                    <pre data-native-scroll class="code-preview custom-scrollbar max-h-[390px] overflow-auto p-5 text-xs leading-relaxed font-mono whitespace-pre-wrap break-words sm:p-6"><code>{{ $sourceCode ?: 'Tidak ada kode yang tersimpan.' }}</code></pre>
                </section>
            </section>

            <aside class="space-y-5">
                <section class="ui-card rounded-[24px] p-5">
                    <p class="eyebrow text-[10px] font-extrabold uppercase">Ringkasan materi</p>
                    <h2 class="section-heading mt-1 text-lg font-extrabold">{{ $summary['title'] ?? 'Materi Tailwind CSS' }}</h2>

                    @if(!empty($summary['subtitle']))
                        <span class="mt-3 inline-flex rounded-lg border px-2.5 py-1 text-[10px] font-extrabold" style="border-color:var(--accent-line);background:var(--accent-soft);color:var(--accent-strong)">
                            {{ $summary['subtitle'] }}
                        </span>
                    @endif

                    <p class="muted mt-4 text-sm leading-6">{{ $summary['summary'] ?? 'Ringkasan materi belum tersedia.' }}</p>

                    @if(!empty($summary['key_points']))
                        <div class="mt-4 space-y-3">
                            @foreach($summary['key_points'] as $point)
                                <div class="flex gap-3">
                                    <span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full" style="background:var(--accent)"></span>
                                    <p class="muted text-sm leading-6">{{ $point }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </section>

                <section class="ui-card rounded-[24px] p-5">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="eyebrow text-[10px] font-extrabold uppercase">Fokus tindak lanjut</p>
                            <h2 class="section-heading mt-1 text-lg font-extrabold">Prioritas perbaikan</h2>
                        </div>
                        <span class="soft-panel rounded-lg px-2.5 py-1 text-[10px] font-extrabold">{{ $labPriorityItems->count() }} poin</span>
                    </div>

                    <div class="mt-4 space-y-3">
                        @foreach($labPriorityItems->take(3) as $item)
                            <div class="priority-card rounded-xl border px-4 py-3 {{ $item['tone'] }} dark:border-white/10 dark:bg-white/5 dark:text-slate-100">
                                <p class="text-sm font-extrabold">{{ $item['label'] }}</p>
                                <p class="mt-1 text-xs leading-5 opacity-80">{{ $item['detail'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </section>

                <section class="ui-card rounded-[24px] p-5">
                    <p class="eyebrow text-[10px] font-extrabold uppercase">Langkah berikutnya</p>
                    <h2 class="section-heading mt-1 text-lg font-extrabold">
                        {{ $isPassed ? 'Pertahankan kualitas implementasi' : 'Lanjutkan perbaikan di ruang kerja' }}
                    </h2>
                    <p class="muted mt-2 text-sm leading-6">
                        {{ $summary['next_step'] ?? ($isPassed
                            ? 'Tinjau kembali kode akhir untuk merapikan struktur dan konsistensi class sebelum melanjutkan materi berikutnya.'
                            : 'Gunakan daftar tugas sebagai panduan, lalu cek kembali hasil visual dan aturan yang wajib dipenuhi.') }}
                    </p>

                    <div class="mt-4 flex flex-col gap-2">
                        <a href="{{ $studyUrl }}" class="action-secondary inline-flex min-h-10 items-center justify-center rounded-xl px-4 text-xs font-extrabold">
                            Buka Materi Pendukung
                        </a>
                        @if(!$isPassed && $lab)
                            <a href="{{ route('lab.start', ['id' => $lab->id]) }}" class="action-primary inline-flex min-h-10 items-center justify-center rounded-xl px-4 text-xs font-extrabold">
                                Perbaiki Lab
                            </a>
                        @endif
                    </div>
                </section>
            </aside>
        </section>
    </main>
</div>

<script>
    (function () {
        const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
        const finePointer = window.matchMedia('(pointer: fine)');
        const scrollHost = document.querySelector('[data-smooth-scroll]');

        function startProgressAnimation() {
            document.querySelectorAll('.js-progress').forEach((bar) => {
                const progress = Math.max(0, Math.min(100, Number(bar.dataset.progress || 0)));
                requestAnimationFrame(() => { bar.style.width = progress + '%'; });
            });

            document.querySelectorAll('.js-score-ring').forEach((ring) => {
                const progress = Math.max(0, Math.min(100, Number(ring.dataset.progress || 0)));
                requestAnimationFrame(() => { ring.style.setProperty('--progress', progress); });
            });
        }

        function isNativeScrollTarget(target) {
            return target instanceof Element
                ? target.closest('[data-native-scroll], textarea, input, select, [contenteditable="true"]')
                : null;
        }

        let targetScrollTop = scrollHost ? scrollHost.scrollTop : 0;
        let scrollAnimationFrame = null;
        let lastScrollFrame = 0;
        let smoothWheelAnimating = false;

        function canSmoothWheel() {
            return !!scrollHost && finePointer.matches && !reducedMotion.matches;
        }

        function cancelSmoothWheel() {
            if (scrollAnimationFrame) window.cancelAnimationFrame(scrollAnimationFrame);
            scrollAnimationFrame = null;
            lastScrollFrame = 0;
            smoothWheelAnimating = false;
            if (scrollHost) {
                targetScrollTop = scrollHost.scrollTop;
                scrollHost.classList.remove('is-smooth-scrolling');
            }
        }

        function normaliseWheelDelta(event) {
            if (!scrollHost) return 0;
            if (event.deltaMode === 1) return event.deltaY * 18;
            if (event.deltaMode === 2) return event.deltaY * scrollHost.clientHeight;
            return event.deltaY;
        }

        function animateSmoothWheel(timestamp) {
            if (!scrollHost) return;
            smoothWheelAnimating = true;

            const elapsed = Math.min(48, Math.max(8, timestamp - (lastScrollFrame || timestamp - 16)));
            lastScrollFrame = timestamp;
            const maxScrollTop = Math.max(0, scrollHost.scrollHeight - scrollHost.clientHeight);
            targetScrollTop = Math.max(0, Math.min(maxScrollTop, targetScrollTop));

            const current = scrollHost.scrollTop;
            const distance = targetScrollTop - current;
            const easing = 1 - Math.exp(-elapsed / 42);

            if (Math.abs(distance) < 0.45) {
                scrollHost.scrollTop = targetScrollTop;
                cancelSmoothWheel();
                return;
            }

            scrollHost.scrollTop = current + (distance * easing);
            scrollAnimationFrame = window.requestAnimationFrame(animateSmoothWheel);
        }

        function enableSmoothResultWheel() {
            if (!scrollHost) return;

            scrollHost.addEventListener('wheel', (event) => {
                if (!canSmoothWheel() || event.ctrlKey || event.shiftKey || !event.deltaY) return;
                if (isNativeScrollTarget(event.target)) return;

                const maxScrollTop = Math.max(0, scrollHost.scrollHeight - scrollHost.clientHeight);
                if (!maxScrollTop) return;

                const delta = normaliseWheelDelta(event);
                const atTop = scrollHost.scrollTop <= 0 && delta < 0;
                const atBottom = scrollHost.scrollTop >= maxScrollTop - 1 && delta > 0;
                if (atTop || atBottom) return;

                event.preventDefault();
                if (!scrollAnimationFrame) targetScrollTop = scrollHost.scrollTop;

                const multiplier = Math.abs(delta) > 120 ? .78 : .64;
                targetScrollTop = Math.max(0, Math.min(maxScrollTop, targetScrollTop + (delta * multiplier)));
                scrollHost.classList.add('is-smooth-scrolling');

                if (!scrollAnimationFrame) {
                    lastScrollFrame = performance.now();
                    scrollAnimationFrame = window.requestAnimationFrame(animateSmoothWheel);
                }
            }, { passive: false });

            scrollHost.addEventListener('scroll', () => {
                if (!smoothWheelAnimating) targetScrollTop = scrollHost.scrollTop;
            }, { passive: true });

            scrollHost.addEventListener('pointerdown', cancelSmoothWheel, { passive: true });
            scrollHost.addEventListener('touchstart', cancelSmoothWheel, { passive: true });
            scrollHost.addEventListener('keydown', cancelSmoothWheel, { passive: true });
            window.addEventListener('blur', cancelSmoothWheel, { passive: true });
            window.addEventListener('resize', cancelSmoothWheel, { passive: true });
        }

        function enableInPageAnchors() {
            document.querySelectorAll('a[href^="#"]').forEach((link) => {
                link.addEventListener('click', (event) => {
                    const id = link.getAttribute('href');
                    if (!id || id === '#') return;
                    const target = document.querySelector(id);
                    if (!target) return;

                    event.preventDefault();
                    cancelSmoothWheel();
                    target.scrollIntoView({ behavior: reducedMotion.matches ? 'auto' : 'smooth', block: 'start' });
                });
            });
        }

        window.addEventListener('DOMContentLoaded', () => {
            requestAnimationFrame(() => {
                document.documentElement.classList.add('theme-ready');
                startProgressAnimation();
                enableSmoothResultWheel();
                enableInPageAnchors();
            });
        });
    })();
</script>
</body>
</html>
