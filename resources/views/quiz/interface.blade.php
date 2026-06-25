<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $chapterId == 99 ? 'Evaluasi Akhir' : 'Evaluasi Bab ' . $chapterId }}</title>

    <script>
        (function () {
            try {
                const storedTheme = localStorage.getItem('color-theme');
                const theme = storedTheme === 'dark' || storedTheme === 'light'
                    ? storedTheme
                    : (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');

                document.documentElement.classList.toggle('dark', theme === 'dark');
                document.documentElement.style.colorScheme = theme;
            } catch (error) {
                const theme = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
                document.documentElement.classList.toggle('dark', theme === 'dark');
                document.documentElement.style.colorScheme = theme;
            }
        })();
    </script>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { darkMode: 'class' };
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            color-scheme: light;
            --canvas: #f4f7fb;
            --canvas-deep: #edf2f8;
            --text: #172033;
            --text-strong: #0f172a;
            --muted: #64748b;
            --muted-soft: #94a3b8;
            --surface: rgba(255, 255, 255, .94);
            --surface-subtle: #f8fafc;
            --surface-soft: rgba(248, 250, 252, .88);
            --line: #e2e8f0;
            --line-strong: #cbd5e1;
            --accent: #0f8faf;
            --accent-strong: #0e7490;
            --accent-soft: #e6f8fb;
            --accent-ink: #083344;
            --success: #047857;
            --success-soft: #ecfdf5;
            --warning: #b45309;
            --warning-soft: #fffbeb;
            --danger: #be123c;
            --danger-soft: #fff1f2;
            --shadow-sm: 0 10px 24px rgba(15, 23, 42, .06);
            --shadow-lg: 0 24px 55px rgba(15, 23, 42, .10);
            --header: rgba(255, 255, 255, .78);
            --overlay: rgba(15, 23, 42, .78);
        }

        html.dark {
            color-scheme: dark;
            --canvas: #090f1a;
            --canvas-deep: #0d1522;
            --text: #e5edf8;
            --text-strong: #f8fafc;
            --muted: #94a3b8;
            --muted-soft: #64748b;
            --surface: rgba(15, 23, 42, .91);
            --surface-subtle: #111b2a;
            --surface-soft: rgba(30, 41, 59, .62);
            --line: rgba(148, 163, 184, .17);
            --line-strong: rgba(148, 163, 184, .34);
            --accent: #67e8f9;
            --accent-strong: #a5f3fc;
            --accent-soft: rgba(8, 145, 178, .14);
            --accent-ink: #083344;
            --success: #6ee7b7;
            --success-soft: rgba(16, 185, 129, .10);
            --warning: #fcd34d;
            --warning-soft: rgba(245, 158, 11, .10);
            --danger: #fda4af;
            --danger-soft: rgba(244, 63, 94, .10);
            --shadow-sm: 0 10px 24px rgba(2, 6, 23, .15);
            --shadow-lg: 0 26px 60px rgba(2, 6, 23, .30);
            --header: rgba(9, 15, 26, .78);
            --overlay: rgba(2, 6, 23, .82);
        }

        * { -webkit-tap-highlight-color: transparent; }
        [x-cloak] { display: none !important; }
        body { font-family: 'Inter', sans-serif; color: var(--text); }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
        .no-select { user-select: none; -webkit-user-select: none; }

        /* Transisi hanya berjalan saat pengguna mengubah tema, bukan saat halaman pertama kali dibuka. */
        html.theme-transition,
        html.theme-transition *,
        html.theme-transition *::before,
        html.theme-transition *::after {
            transition-property: background-color, background-image, color, border-color, box-shadow, fill, stroke !important;
            transition-duration: 340ms !important;
            transition-timing-function: cubic-bezier(.22, 1, .36, 1) !important;
        }

        ::view-transition-old(root),
        ::view-transition-new(root) {
            animation-duration: 340ms;
            animation-timing-function: cubic-bezier(.22, 1, .36, 1);
        }

        ::view-transition-old(root) { animation-name: theme-fade-out; }
        ::view-transition-new(root) { animation-name: theme-fade-in; }

        @keyframes theme-fade-out {
            from { opacity: 1; transform: scale(1); }
            to { opacity: 0; transform: scale(.992); }
        }

        @keyframes theme-fade-in {
            from { opacity: 0; transform: scale(1.008); }
            to { opacity: 1; transform: scale(1); }
        }

        .quiz-app {
            transition: background 340ms cubic-bezier(.22, 1, .36, 1), color 260ms ease;
            background:
                radial-gradient(circle at 10% 0%, rgba(14, 116, 144, .08), transparent 25rem),
                radial-gradient(circle at 100% 100%, rgba(14, 116, 144, .045), transparent 24rem),
                linear-gradient(155deg, var(--canvas), var(--canvas-deep));
        }

        html.dark .quiz-app {
            background:
                radial-gradient(circle at 10% 0%, rgba(34, 211, 238, .08), transparent 25rem),
                radial-gradient(circle at 100% 100%, rgba(34, 211, 238, .04), transparent 24rem),
                linear-gradient(155deg, var(--canvas), var(--canvas-deep));
        }

        .grid-layer {
            transition: opacity 340ms cubic-bezier(.22, 1, .36, 1), background-image 340ms cubic-bezier(.22, 1, .36, 1);
            background-image:
                linear-gradient(rgba(148, 163, 184, .06) 1px, transparent 1px),
                linear-gradient(90deg, rgba(148, 163, 184, .06) 1px, transparent 1px);
            background-size: 42px 42px;
            mask-image: linear-gradient(to bottom, black, transparent 72%);
            opacity: .55;
        }

        html.dark .grid-layer { opacity: .28; }

        .topbar,
        .bottom-bar,
        .side-panel {
            background: var(--header);
            border-color: var(--line);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            transition: background-color 180ms ease, border-color 180ms ease;
        }

        .question-card {
            background: var(--surface);
            border: 1px solid var(--line);
            box-shadow: var(--shadow-lg);
            transition: background-color 180ms ease, border-color 180ms ease, box-shadow 180ms ease;
        }

        .user-chip,
        .nav-button {
            background: var(--surface-soft);
            border: 1px solid var(--line);
            transition: background-color 180ms ease, border-color 180ms ease, transform 160ms ease;
        }

        .brand-dot {
            background: var(--accent);
            box-shadow: 0 0 0 5px color-mix(in srgb, var(--accent) 12%, transparent);
        }

        .status-badge {
            border: 1px solid var(--line);
            background: var(--surface-soft);
            color: var(--muted);
        }

        .status-badge.is-saved {
            background: var(--success-soft);
            border-color: color-mix(in srgb, var(--success) 32%, var(--line));
            color: var(--success);
        }

        .question-number {
            background: var(--accent-soft);
            border: 1px solid color-mix(in srgb, var(--accent) 28%, var(--line));
            color: var(--accent-strong);
        }

        .question-copy {
            background: var(--surface-subtle);
            border: 1px solid var(--line);
            color: var(--text-strong);
        }

        .question-context {
            border: 1px solid var(--line);
            background: var(--surface-soft);
        }

        .question-context.context-media {
            background: var(--accent-soft);
            border-color: color-mix(in srgb, var(--accent) 25%, var(--line));
        }

        .answer-card {
            position: relative;
            min-height: 86px;
            overflow: hidden;
            background: var(--surface);
            border-color: var(--line);
            color: var(--text-strong);
            box-shadow: var(--shadow-sm);
        }

        .answer-card::after {
            content: '';
            position: absolute;
            inset: 0;
            pointer-events: none;
            border-radius: inherit;
            background: linear-gradient(110deg, transparent, rgba(255,255,255,.12), transparent);
            opacity: 0;
            transition: opacity 180ms ease;
        }

        .answer-card:hover {
            transform: translateY(-1px);
            border-color: var(--line-strong);
            background: var(--surface-subtle);
        }

        .answer-card:hover::after { opacity: 1; }

        .answer-selected {
            border-color: var(--accent) !important;
            background: var(--accent-soft) !important;
            box-shadow: 0 0 0 3px color-mix(in srgb, var(--accent) 13%, transparent), var(--shadow-sm);
        }

        .option-circle {
            border: 1px solid var(--line-strong);
            background: var(--surface-subtle);
            color: var(--muted);
        }

        .answer-key-selected {
            border-color: var(--accent) !important;
            background: var(--accent) !important;
            color: var(--accent-ink) !important;
        }

        .answer-key-default { color: var(--muted); }

        .timer-chip {
            transition: transform 180ms ease, border-color 220ms ease, background-color 220ms ease, color 220ms ease;
        }

        .timer-chip:hover { transform: translateY(-1px); }

        .compact-progress { background: var(--line); }
        .compact-progress > span { background: var(--accent); }

        .nav-button:hover { border-color: var(--line-strong); background: var(--surface); transform: translateY(-1px); }

        /* Peralihan antarsoal tetap halus tanpa mengganggu fokus pengerjaan. */
        .question-stage {
            transform: translateY(0);
            opacity: 1;
            transition: opacity 170ms ease, transform 170ms cubic-bezier(.22, 1, .36, 1), filter 170ms ease;
        }

        .question-stage.is-changing {
            opacity: 0;
            transform: translateY(8px);
            filter: blur(1px);
            pointer-events: none;
        }

        .question-card,
        .question-number,
        .status-badge,
        .question-copy,
        .question-context,
        .answer-card,
        .option-circle,
        .bottom-bar,
        .side-panel,
        .compact-progress > span,
        .user-chip,
        .page-item,
        .primary-action,
        .flag-button {
            transition-timing-function: cubic-bezier(.22, 1, .36, 1);
        }

        .page-item {
            transition-property: background-color, border-color, color, transform, box-shadow;
            transition-duration: 180ms;
        }

        .page-item:hover { transform: translateY(-1px); }

        .primary-action {
            transition-property: background-color, color, transform, box-shadow, opacity;
            transition-duration: 180ms;
        }

        .primary-action:hover:not(:disabled) {
            transform: translateY(-1px);
            box-shadow: 0 12px 26px color-mix(in srgb, var(--accent) 20%, transparent);
        }

        .flag-button.is-flagged {
            box-shadow: 0 0 0 3px color-mix(in srgb, var(--warning) 12%, transparent);
        }

        .question-scroll { overscroll-behavior-y: contain; scroll-padding-block: 1rem; }
        .question-media { width: 100%; max-height: min(520px, 54dvh); object-fit: contain; }
        .mobile-question-scroll { scrollbar-width: none; -ms-overflow-style: none; }
        .mobile-question-scroll::-webkit-scrollbar { display: none; }
        .quiz-bottom-safe { padding-bottom: max(.8rem, env(safe-area-inset-bottom)); }

        .custom-scrollbar::-webkit-scrollbar { width: 7px; height: 7px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: color-mix(in srgb, var(--muted) 35%, transparent); border-radius: 999px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: color-mix(in srgb, var(--muted) 58%, transparent); }

        .focus-overlay { background: var(--overlay); }
        .focus-dialog { background: var(--surface); border: 1px solid var(--line); box-shadow: var(--shadow-lg); }

        @media (max-width: 639px) {
            .answer-card { min-height: 76px; }
            .question-media { max-height: min(390px, 46dvh); }
        }

        @media (prefers-reduced-motion: reduce) {
            ::view-transition-old(root),
            ::view-transition-new(root) { animation: none !important; }

            *, *::before, *::after {
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
                scroll-behavior: auto !important;
                transition-duration: .01ms !important;
            }
        }

        /* Lapisan visual tambahan: memberi karakter tanpa mengganggu fokus pengerjaan. */
        :root {
            --brand-one: #0f9fbd;
            --brand-two: #4f46e5;
            --brand-three: #c026d3;
            --brand-gradient: linear-gradient(135deg, var(--brand-one) 0%, var(--brand-two) 56%, var(--brand-three) 100%);
            --brand-shadow: 0 16px 34px rgba(79, 70, 229, .18);
        }

        html.dark {
            --brand-one: #67e8f9;
            --brand-two: #818cf8;
            --brand-three: #f0abfc;
            --brand-shadow: 0 18px 38px rgba(79, 70, 229, .28);
        }

        .quiz-app {
            background:
                radial-gradient(circle at 12% 2%, color-mix(in srgb, var(--brand-one) 18%, transparent), transparent 27rem),
                radial-gradient(circle at 94% 8%, color-mix(in srgb, var(--brand-two) 16%, transparent), transparent 25rem),
                radial-gradient(circle at 58% 105%, color-mix(in srgb, var(--brand-three) 10%, transparent), transparent 27rem),
                linear-gradient(155deg, var(--canvas), var(--canvas-deep));
        }

        .ambient-orb {
            position: absolute;
            border-radius: 999px;
            filter: blur(12px);
            opacity: .46;
            animation: ambient-float 14s ease-in-out infinite;
        }

        .ambient-orb-one {
            top: 10%;
            right: -7rem;
            width: 17rem;
            height: 17rem;
            background: color-mix(in srgb, var(--brand-two) 24%, transparent);
        }

        .ambient-orb-two {
            bottom: -8rem;
            left: 18%;
            width: 20rem;
            height: 20rem;
            background: color-mix(in srgb, var(--brand-three) 15%, transparent);
            animation-delay: -5s;
        }

        .ambient-orb-three {
            top: 38%;
            left: -8rem;
            width: 15rem;
            height: 15rem;
            background: color-mix(in srgb, var(--brand-one) 16%, transparent);
            animation-delay: -9s;
        }

        @keyframes ambient-float {
            0%, 100% { transform: translate3d(0, 0, 0) scale(1); }
            50% { transform: translate3d(0, -18px, 0) scale(1.04); }
        }

        .grid-layer {
            background-image:
                linear-gradient(color-mix(in srgb, var(--brand-two) 8%, transparent) 1px, transparent 1px),
                linear-gradient(90deg, color-mix(in srgb, var(--brand-one) 8%, transparent) 1px, transparent 1px);
            background-size: 48px 48px;
            opacity: .48;
        }

        html.dark .grid-layer { opacity: .25; }

        .topbar {
            position: fixed;
            overflow: hidden;
            background: linear-gradient(105deg, color-mix(in srgb, var(--header) 92%, var(--brand-one)), var(--header) 48%, color-mix(in srgb, var(--header) 93%, var(--brand-two)));
            box-shadow: 0 10px 30px rgba(15, 23, 42, .04);
        }

        .topbar::after {
            content: '';
            position: absolute;
            inset: 0;
            pointer-events: none;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,.18), transparent);
            transform: translateX(-100%);
            animation: header-sheen 8s ease-in-out infinite;
            opacity: .28;
        }

        @keyframes header-sheen {
            0%, 55%, 100% { transform: translateX(-115%); }
            70% { transform: translateX(115%); }
        }

        .brand-dot {
            width: 11px;
            height: 11px;
            background: var(--brand-gradient);
            box-shadow: 0 0 0 5px color-mix(in srgb, var(--brand-two) 12%, transparent), 0 0 22px color-mix(in srgb, var(--brand-one) 40%, transparent);
        }

        .timer-chip {
            background: linear-gradient(135deg, color-mix(in srgb, var(--surface) 86%, var(--brand-two)), var(--surface-soft)) !important;
            border-color: color-mix(in srgb, var(--brand-two) 18%, var(--line)) !important;
            box-shadow: 0 8px 18px rgba(15, 23, 42, .05);
        }

        .user-chip {
            background: linear-gradient(135deg, var(--surface-soft), color-mix(in srgb, var(--surface-soft) 88%, var(--brand-one))) !important;
            border-color: color-mix(in srgb, var(--brand-one) 14%, var(--line)) !important;
        }

        .user-avatar {
            color: #fff;
            background: var(--brand-gradient);
            box-shadow: 0 7px 14px color-mix(in srgb, var(--brand-two) 22%, transparent);
        }

        .header-progress {
            background: var(--brand-gradient) !important;
            box-shadow: 0 -1px 12px color-mix(in srgb, var(--brand-two) 38%, transparent);
        }

        .question-card {
            position: relative;
            isolation: isolate;
            background: linear-gradient(145deg, color-mix(in srgb, var(--surface) 96%, var(--brand-two)), var(--surface)) !important;
            border-color: color-mix(in srgb, var(--brand-two) 12%, var(--line)) !important;
            box-shadow: 0 28px 62px rgba(15, 23, 42, .10), inset 0 1px 0 rgba(255,255,255,.42) !important;
        }

        html.dark .question-card {
            box-shadow: 0 30px 70px rgba(2, 6, 23, .34), inset 0 1px 0 rgba(255,255,255,.04) !important;
        }

        .question-card::before {
            content: '';
            position: absolute;
            z-index: 2;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--brand-gradient);
        }

        .question-header {
            position: relative;
            background:
                radial-gradient(circle at 86% 0%, color-mix(in srgb, var(--brand-three) 12%, transparent), transparent 11rem),
                linear-gradient(110deg, color-mix(in srgb, var(--brand-one) 8%, var(--surface)), var(--surface));
        }

        .question-number {
            position: relative;
            overflow: hidden;
            background: var(--brand-gradient) !important;
            color: #fff !important;
            border: 0 !important;
            box-shadow: var(--brand-shadow), inset 0 1px 0 rgba(255,255,255,.32);
        }

        .question-number::after {
            content: '';
            position: absolute;
            inset: auto -16px -22px auto;
            width: 44px;
            height: 44px;
            border-radius: 999px;
            background: rgba(255,255,255,.20);
        }

        .question-number span:last-child { color: rgba(255,255,255,.72) !important; }

        .status-badge {
            background: color-mix(in srgb, var(--surface-soft) 88%, var(--brand-two)) !important;
            border-color: color-mix(in srgb, var(--brand-two) 16%, var(--line)) !important;
        }

        .badge-topic {
            background: color-mix(in srgb, var(--brand-one) 11%, var(--surface)) !important;
            color: var(--accent-strong) !important;
            border-color: color-mix(in srgb, var(--brand-one) 28%, var(--line)) !important;
        }

        .question-copy {
            position: relative;
            background: linear-gradient(135deg, color-mix(in srgb, var(--surface-subtle) 93%, var(--brand-two)), var(--surface-subtle)) !important;
            border-color: color-mix(in srgb, var(--brand-two) 10%, var(--line)) !important;
        }

        .question-copy::before {
            content: '';
            display: block;
            width: 38px;
            height: 3px;
            margin-bottom: 1rem;
            border-radius: 999px;
            background: var(--brand-gradient);
            box-shadow: 0 4px 12px color-mix(in srgb, var(--brand-two) 24%, transparent);
        }

        .question-copy code,
        .question-context code {
            display: inline-flex;
            max-width: 100%;
            align-items: center;
            border: 1px solid color-mix(in srgb, var(--brand-one) 32%, var(--line));
            border-radius: .65rem;
            background: linear-gradient(135deg, color-mix(in srgb, var(--brand-one) 12%, #ffffff), color-mix(in srgb, var(--brand-two) 8%, #ffffff));
            box-shadow: inset 0 1px 0 rgba(255,255,255,.85), 0 6px 16px color-mix(in srgb, var(--brand-one) 8%, transparent);
            color: #075985;
            font-family: 'JetBrains Mono', monospace;
            font-size: .92em;
            font-weight: 800;
            line-height: 1.65;
            overflow-wrap: anywhere;
            padding: .12rem .52rem;
            white-space: break-spaces;
        }

        html.dark .question-copy code,
        html.dark .question-context code {
            border-color: color-mix(in srgb, var(--accent) 34%, var(--line));
            background: linear-gradient(135deg, rgba(8, 47, 73, .72), rgba(15, 23, 42, .92));
            box-shadow: inset 0 1px 0 rgba(255,255,255,.08), 0 8px 18px rgba(2, 6, 23, .34);
            color: #67e8f9;
        }

        .question-copy pre,
        .question-context pre {
            margin: 1rem 0;
            max-width: 100%;
            overflow-x: auto;
            border: 1px solid rgba(148, 163, 184, .26);
            border-radius: 1rem;
            background: linear-gradient(135deg, #0f172a, #111827);
            box-shadow: 0 16px 34px rgba(15, 23, 42, .16);
            color: #dbeafe;
            font-family: 'JetBrains Mono', monospace;
            font-size: .92rem;
            font-weight: 700;
            line-height: 1.75;
            padding: 1rem 1.1rem;
        }

        .question-copy pre code,
        .question-context pre code {
            display: block;
            border: 0;
            border-radius: 0;
            background: transparent;
            box-shadow: none;
            color: inherit;
            padding: 0;
            white-space: pre;
        }

        .question-context.context-media {
            background: linear-gradient(135deg, color-mix(in srgb, var(--brand-one) 10%, var(--surface)), color-mix(in srgb, var(--brand-two) 6%, var(--surface))) !important;
            border-color: color-mix(in srgb, var(--brand-one) 24%, var(--line)) !important;
        }

        .answer-card {
            --option-tone: var(--brand-one);
            position: relative;
            min-height: 92px;
            background: linear-gradient(135deg, var(--surface), color-mix(in srgb, var(--surface) 92%, var(--option-tone))) !important;
            border-color: color-mix(in srgb, var(--option-tone) 17%, var(--line)) !important;
            box-shadow: 0 10px 22px rgba(15, 23, 42, .055) !important;
            transition: transform 200ms cubic-bezier(.22, 1, .36, 1), box-shadow 200ms ease, border-color 200ms ease, background 200ms ease !important;
        }

        .answer-card::before {
            content: '';
            position: absolute;
            inset: 14px auto 14px 0;
            width: 4px;
            border-radius: 0 8px 8px 0;
            background: var(--option-tone);
            box-shadow: 0 0 16px color-mix(in srgb, var(--option-tone) 42%, transparent);
            opacity: .9;
        }

        .answer-card::after {
            background: linear-gradient(110deg, transparent 24%, rgba(255,255,255,.24) 50%, transparent 76%) !important;
        }

        .answer-card:hover {
            transform: translateY(-3px) scale(1.004) !important;
            border-color: color-mix(in srgb, var(--option-tone) 48%, var(--line)) !important;
            box-shadow: 0 17px 31px color-mix(in srgb, var(--option-tone) 12%, transparent), 0 10px 22px rgba(15, 23, 42, .05) !important;
        }

        .answer-tone-sky { --option-tone: #0ea5e9; }
        .answer-tone-indigo { --option-tone: #6366f1; }
        .answer-tone-rose { --option-tone: #db2777; }
        .answer-tone-emerald { --option-tone: #059669; }

        html.dark .answer-tone-sky { --option-tone: #38bdf8; }
        html.dark .answer-tone-indigo { --option-tone: #818cf8; }
        html.dark .answer-tone-rose { --option-tone: #f472b6; }
        html.dark .answer-tone-emerald { --option-tone: #34d399; }

        .answer-card .option-circle {
            background: color-mix(in srgb, var(--option-tone) 11%, var(--surface)) !important;
            color: var(--option-tone) !important;
            border-color: color-mix(in srgb, var(--option-tone) 34%, var(--line)) !important;
            box-shadow: 0 5px 12px color-mix(in srgb, var(--option-tone) 10%, transparent);
        }

        .answer-selected {
            background: linear-gradient(135deg, color-mix(in srgb, var(--brand-one) 14%, var(--surface)), color-mix(in srgb, var(--brand-two) 10%, var(--surface))) !important;
            border-color: color-mix(in srgb, var(--brand-one) 58%, var(--line)) !important;
            box-shadow: 0 0 0 3px color-mix(in srgb, var(--brand-two) 12%, transparent), 0 17px 34px color-mix(in srgb, var(--brand-one) 14%, transparent) !important;
        }

        .answer-selected::before { background: var(--brand-gradient); }

        .answer-selected .option-circle,
        .answer-key-selected {
            color: #fff !important;
            border-color: transparent !important;
            background: var(--brand-gradient) !important;
            box-shadow: var(--brand-shadow) !important;
        }

        .bottom-bar {
            background: linear-gradient(105deg, color-mix(in srgb, var(--header) 92%, var(--brand-one)), var(--header) 54%, color-mix(in srgb, var(--header) 94%, var(--brand-two))) !important;
        }

        .nav-button {
            background: linear-gradient(135deg, var(--surface-soft), color-mix(in srgb, var(--surface-soft) 92%, var(--brand-two))) !important;
        }

        .next-action {
            color: #fff !important;
            border: 0 !important;
            background: var(--brand-gradient) !important;
            box-shadow: var(--brand-shadow);
        }

        .next-action:hover:not(:disabled) {
            box-shadow: 0 17px 32px color-mix(in srgb, var(--brand-two) 26%, transparent) !important;
        }

        .page-item.shadow-sm {
            color: #fff !important;
            border-color: transparent !important;
            background: var(--brand-gradient) !important;
            box-shadow: var(--brand-shadow) !important;
        }

        .compact-progress > span { background: var(--brand-gradient) !important; }

        .side-panel {
            background: linear-gradient(180deg, color-mix(in srgb, var(--header) 95%, var(--brand-two)), var(--header)) !important;
        }

        .progress-orb {
            position: relative;
            overflow: hidden;
            color: #fff !important;
            background: var(--brand-gradient) !important;
            box-shadow: var(--brand-shadow);
        }

        .progress-orb::after {
            content: '';
            position: absolute;
            inset: -12px auto auto -8px;
            height: 24px;
            width: 45px;
            border-radius: 50%;
            background: rgba(255,255,255,.22);
            transform: rotate(-22deg);
        }

        .page-item {
            border: 1px solid var(--line);
            background: var(--surface-soft);
        }

        .flag-button.is-flagged {
            box-shadow: 0 0 0 3px color-mix(in srgb, var(--warning) 13%, transparent), 0 10px 22px color-mix(in srgb, var(--warning) 9%, transparent) !important;
        }

        @media (max-width: 639px) {
            .ambient-orb { opacity: .34; }
            .answer-card { min-height: 82px; }
            .question-card { border-radius: 1.35rem !important; }
        }

        @media (prefers-reduced-motion: reduce) {
            .ambient-orb,
            .topbar::after { animation: none !important; }
        }
    </style>
</head>
<body class="quiz-app h-[100dvh] min-h-screen overflow-hidden no-select"
      x-data="cbtApp()" x-init="initCBT()" oncontextmenu="return false;">

    <div class="pointer-events-none fixed inset-0 -z-50 overflow-hidden">
        <div class="grid-layer absolute inset-0"></div>
        <div class="ambient-orb ambient-orb-one"></div>
        <div class="ambient-orb ambient-orb-two"></div>
        <div class="ambient-orb ambient-orb-three"></div>
    </div>

    <div x-cloak x-show="isBlurred"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="focus-overlay fixed inset-0 z-[100] flex items-center justify-center p-5 text-center">
        <div class="focus-dialog w-full max-w-md rounded-3xl p-7 sm:p-9">
            <div class="mx-auto grid h-14 w-14 place-items-center rounded-2xl border border-rose-400/25 bg-rose-500/10 text-rose-400">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z"/></svg>
            </div>
            <p class="mt-6 text-[10px] font-black uppercase tracking-[.18em]" style="color: var(--danger);">Perhatian</p>
            <h2 class="mt-2 text-xl font-black" style="color: var(--text-strong);">Fokus Anda terdeteksi berpindah</h2>
            <p class="mt-3 text-sm leading-6" style="color: var(--muted);">Sistem mendeteksi Anda meninggalkan halaman evaluasi. Kembali ke halaman ini untuk melanjutkan pengerjaan.</p>
            <button @click="isBlurred = false" class="mt-7 rounded-xl px-5 py-3 text-sm font-black transition hover:-translate-y-0.5" style="background: var(--danger); color: #fff;">
                Kembali ke Evaluasi
            </button>
        </div>
    </div>

    <header class="topbar fixed top-0 z-50 flex h-[68px] w-full items-center justify-between border-b px-4 sm:px-6 lg:px-8">
        <div class="flex min-w-0 items-center gap-3">
            <span class="brand-dot h-2.5 w-2.5 shrink-0 rounded-full"></span>
            <div class="min-w-0">
                <h1 class="truncate text-sm font-extrabold" style="color: var(--text-strong);">{{ $chapterId == 99 ? 'Evaluasi Akhir' : 'Evaluasi Bab ' . $chapterId }}</h1>
                <p class="mt-0.5 text-[10px] font-bold uppercase tracking-[.15em]" style="color: var(--muted);">Sesi evaluasi</p>
            </div>
        </div>

        <div class="flex items-center gap-2 sm:gap-3">
            <!-- Waktu ditampilkan sekali pada header. -->
            <div class="timer-chip inline-flex items-center gap-2 rounded-xl px-2.5 py-2 sm:px-3" style="background: var(--surface-soft); border: 1px solid var(--line);">
                <svg class="h-4 w-4 shrink-0" style="color: var(--muted);" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l2.5 2.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="hidden text-[9px] font-bold uppercase tracking-[.14em] sm:inline" style="color: var(--muted);">Sisa waktu</span>
                <span class="font-mono text-sm font-bold tabular-nums sm:text-base" :class="timeLeft < 300 ? 'text-rose-500 dark:text-rose-300 animate-pulse' : ''" style="color: var(--text-strong);" x-text="formatTime(timeLeft)">--:--</span>
            </div>


            <div class="user-chip flex items-center gap-2 rounded-xl p-1.5 pr-2.5">
                <div class="user-avatar grid h-7 w-7 place-items-center rounded-lg text-xs font-black">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="hidden min-w-0 sm:block">
                    <p class="max-w-[124px] truncate text-xs font-bold" style="color: var(--text-strong);">{{ Auth::user()->name }}</p>
                    <p class="text-[9px]" style="color: var(--muted);">Peserta #{{ Auth::id() }}</p>
                </div>
            </div>
        </div>

        <div class="header-progress absolute bottom-0 left-0 h-[2px] transition-all duration-500" :style="`width: ${completionPercent}%`"></div>
    </header>

    <div class="flex h-full min-h-0 flex-col pt-[68px] lg:flex-row">
        <main class="relative flex min-h-0 flex-1 flex-col overflow-hidden">
            <div x-ref="questionScroll" class="question-scroll custom-scrollbar min-h-0 flex-1 overflow-y-auto">
                <div class="mx-auto w-full max-w-[1040px] px-4 py-5 sm:px-6 sm:py-8 lg:px-8 lg:py-10">
                    <div x-show="!ready" class="flex h-64 flex-col items-center justify-center">
                        <div class="h-9 w-9 animate-spin rounded-full border-[3px] border-t-transparent" style="border-color: var(--accent); border-top-color: transparent;"></div>
                        <p class="mt-4 text-xs font-bold uppercase tracking-[.16em]" style="color: var(--muted);">Memuat lembar soal</p>
                    </div>

                    <div x-show="ready" x-transition.opacity.duration.250ms class="space-y-4">
                        <section class="question-card question-stage overflow-hidden rounded-3xl" :class="{ 'is-changing': questionTransitioning }">
                            <div class="question-header flex items-center gap-4 border-b px-5 py-5 sm:px-7 sm:py-6" style="border-color: var(--line);">
                                <div class="question-number flex h-14 min-w-14 shrink-0 flex-col items-center justify-center rounded-2xl px-2">
                                    <span class="text-lg font-black leading-none" x-text="String(currentIndex + 1).padStart(2, '0')"></span>
                                    <span class="mt-0.5 text-[8px] font-bold" style="color: var(--muted);">/<span x-text="questions.length"></span></span>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-[10px] font-black uppercase tracking-[.16em]" style="color: var(--muted);">Soal aktif</p>
                                    <div class="mt-2 flex flex-wrap items-center gap-2">
                                        <span class="status-badge badge-topic rounded-full px-2.5 py-1 text-[9px] font-black uppercase tracking-[.12em]" x-text="interactionLabel(currentQuestion?.interaction_type)"></span>
                                        <span class="status-badge rounded-full px-2.5 py-1 text-[9px] font-black uppercase tracking-[.12em]" :class="answers[currentQuestion?.id] ? 'is-saved' : ''" x-text="answers[currentQuestion?.id] ? 'Tersimpan' : 'Belum dijawab'"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="p-5 sm:p-7">
                                <div x-show="hasQuestionContext(currentQuestion)" class="mb-5 space-y-4">
                                    <template x-if="currentQuestion && currentQuestion.media_url">
                                        <figure class="overflow-hidden rounded-2xl border" style="border-color: var(--line); background: var(--surface-subtle);">
                                            <img :src="currentQuestion.media_url" :alt="currentQuestion.media_caption || 'Media soal'" loading="lazy" x-on:error="$event.target.closest('figure').classList.add('hidden')" class="question-media">
                                            <figcaption x-show="currentQuestion.media_caption" class="border-t px-4 py-3 text-xs leading-6" style="border-color: var(--line); color: var(--muted);" x-text="currentQuestion.media_caption"></figcaption>
                                        </figure>
                                    </template>

                                    <template x-if="currentQuestion && currentQuestion.interaction_prompt">
                                        <div class="question-context rounded-2xl p-4 sm:p-5" :class="contextCardClass(currentQuestion.interaction_type)">
                                            <p class="text-[10px] font-black uppercase tracking-[.16em]" style="color: var(--accent-strong);" x-text="interactionPromptLabel(currentQuestion.interaction_type)"></p>
                                            <p class="mt-2 text-sm leading-6 sm:text-[15px]" style="color: var(--text);" x-text="currentQuestion.interaction_prompt"></p>
                                        </div>
                                    </template>
                                </div>

                                <div class="question-copy rounded-2xl px-5 py-5 sm:px-6 sm:py-6">
                                    <div class="text-[15px] font-semibold leading-7 sm:text-base sm:leading-8">
                                        <span x-html="currentQuestion.question_text"></span>
                                    </div>
                                </div>

                                <p class="mt-6 mb-3 text-[10px] font-black uppercase tracking-[.15em]" style="color: var(--muted);">Pilih satu jawaban yang paling tepat</p>
                                <div class="grid gap-3 md:grid-cols-2" :class="{ 'pointer-events-none': questionTransitioning }">
                                    <template x-for="(option, idx) in currentQuestion.options" :key="option.id">
                                        <label class="group relative block cursor-pointer">
                                            <input type="radio" :name="'q_' + currentQuestion.id" class="option-input hidden" :value="option.id" :checked="answers[currentQuestion.id] == option.id" @change="selectAnswer(currentQuestion.id, option.id)">
                                            <div class="option-card flex h-full items-center gap-3 rounded-2xl border p-4 transition-all duration-200 sm:gap-4 sm:p-4.5" :class="optionCardClass(idx, currentQuestion.id, option.id)">
                                                <div class="option-circle flex h-10 w-10 shrink-0 items-center justify-center rounded-xl text-sm font-black transition-all duration-200" :class="optionLetterClass(idx, currentQuestion.id, option.id)">
                                                    <span x-text="String.fromCharCode(65 + idx)"></span>
                                                </div>
                                                <div class="min-w-0 text-sm font-bold leading-6 sm:text-[15px]" style="color: var(--text-strong);">
                                                    <span x-text="option.option_text"></span>
                                                </div>
                                                <svg x-show="answers[currentQuestion.id] == option.id" x-transition class="ml-auto h-5 w-5 shrink-0" style="color: var(--accent-strong);" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                            </div>
                                        </label>
                                    </template>
                                </div>
                            </div>
                        </section>

                        <div x-cloak x-show="feedbackFlash"
                             x-transition:enter="transition ease-out duration-180"
                             x-transition:enter-start="opacity-0 -translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 -translate-y-2"
                             class="fixed left-1/2 top-20 z-[80] -translate-x-1/2 rounded-xl border px-4 py-2.5 text-xs font-bold shadow-lg backdrop-blur" style="border-color: color-mix(in srgb, var(--success) 30%, var(--line)); background: var(--success-soft); color: var(--success);">
                            Jawaban tersimpan
                        </div>
                    </div>
                </div>
            </div>

            <footer class="bottom-bar quiz-bottom-safe shrink-0 border-t px-3 py-3 sm:px-5 sm:py-4">
                <div class="mx-auto max-w-[1040px]">
                    <div class="mobile-question-scroll mb-3 overflow-x-auto lg:hidden">
                        <div class="flex w-max min-w-full gap-2">
                            <template x-for="(q, index) in questions" :key="q.id">
                                <button @click="goToQuestion(index)" class="page-item relative flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-xs font-black"
                                    :class="{
                                        'text-white shadow-sm': currentIndex === index,
                                        'border text-amber-700 dark:text-amber-200': flags[q.id] && currentIndex !== index,
                                        'border text-emerald-700 dark:text-emerald-200': answers[q.id] && !flags[q.id] && currentIndex !== index,
                                        'nav-button': !answers[q.id] && !flags[q.id] && currentIndex !== index
                                    }"
                                    :style="currentIndex === index ? 'background: var(--accent); color: var(--accent-ink); border: 1px solid var(--accent);' : (flags[q.id] ? 'background: var(--warning-soft); border-color: color-mix(in srgb, var(--warning) 30%, var(--line)); color: var(--warning);' : (answers[q.id] ? 'background: var(--success-soft); border-color: color-mix(in srgb, var(--success) 28%, var(--line)); color: var(--success);' : 'color: var(--muted);'))">
                                    <span x-text="index + 1"></span>
                                    <span x-show="flags[q.id]" class="absolute right-1 top-1 h-1.5 w-1.5 rounded-full" style="background: var(--warning);"></span>
                                </button>
                            </template>
                        </div>
                    </div>

                    <div class="flex items-center justify-between gap-2">
                        <button @click="prevQuestion()" :disabled="currentIndex === 0 || questionTransitioning" class="nav-button inline-flex items-center gap-2 rounded-xl px-3 py-2.5 text-sm font-bold disabled:cursor-not-allowed disabled:opacity-35 sm:px-4" style="color: var(--muted);">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                            <span class="hidden sm:inline">Sebelumnya</span>
                        </button>

                        <button @click="toggleFlag(currentQuestion.id)" :disabled="questionTransitioning" class="flag-button nav-button inline-flex items-center gap-2 rounded-xl px-3 py-2.5 text-sm font-bold disabled:cursor-not-allowed disabled:opacity-60 sm:px-4" :class="{ 'is-flagged': flags[currentQuestion.id] }" :style="flags[currentQuestion.id] ? 'background: var(--warning-soft); border-color: color-mix(in srgb, var(--warning) 34%, var(--line)); color: var(--warning);' : 'color: var(--muted);'">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M5 3v18l7-4 7 4V3H5z"/></svg>
                            <span>Ragu</span>
                        </button>

                        <template x-if="currentIndex < questions.length - 1">
                            <button @click="nextQuestion()" :disabled="questionTransitioning" class="primary-action next-action inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-black disabled:cursor-not-allowed disabled:opacity-60 sm:px-5">
                                <span class="hidden sm:inline">Selanjutnya</span>
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                            </button>
                        </template>

                        <template x-if="currentIndex === questions.length - 1">
                            <button @click="confirmSubmit()" :disabled="!isComplete || questionTransitioning" class="primary-action inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-black disabled:cursor-not-allowed disabled:opacity-60 sm:px-5" :style="isComplete ? 'background: var(--success); color: #fff;' : 'background: var(--surface-soft); border: 1px solid var(--line); color: var(--muted); cursor: not-allowed;'">
                                <span>Kumpulkan</span>
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </button>
                        </template>
                    </div>
                </div>
            </footer>
        </main>

        <aside class="side-panel hidden h-full w-[292px] shrink-0 flex-col border-l lg:flex">
            <div class="border-b p-5" style="border-color: var(--line);">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[.16em]" style="color: var(--muted);">Navigasi soal</p>
                        <p class="mt-1 text-sm font-black" style="color: var(--text-strong);">Status pengerjaan</p>
                    </div>
                    <span class="progress-orb grid h-10 min-w-10 place-items-center rounded-xl px-2 text-xs font-black" x-text="completionPercent + '%'"></span>
                </div>
                <div class="mt-4 compact-progress h-1.5 overflow-hidden rounded-full">
                    <span class="block h-full rounded-full transition-all duration-500" :style="`width: ${completionPercent}%`"></span>
                </div>
                <div class="mt-3 flex items-center justify-between text-[10px] font-semibold" style="color: var(--muted);">
                    <span>Jawaban</span>
                    <span style="color: var(--text-strong);"><span x-text="answeredCount"></span>/<span x-text="questions.length"></span></span>
                </div>
            </div>

            <div class="custom-scrollbar min-h-0 flex-1 overflow-y-auto p-5">
                <div class="mb-4 flex flex-wrap gap-x-3 gap-y-2 text-[9px] font-black uppercase tracking-[.10em]" style="color: var(--muted);">
                    <span class="flex items-center gap-1.5"><i class="h-2 w-2 rounded-full" style="background: var(--success);"></i>Dijawab</span>
                    <span class="flex items-center gap-1.5"><i class="h-2 w-2 rounded-full" style="background: var(--warning);"></i>Ragu</span>
                    <span class="flex items-center gap-1.5"><i class="h-2 w-2 rounded-full" style="background: var(--muted-soft);"></i>Kosong</span>
                </div>

                <div class="grid grid-cols-5 gap-2">
                    <template x-for="(q, index) in questions" :key="q.id">
                        <button @click="goToQuestion(index)" class="page-item relative flex aspect-square items-center justify-center rounded-lg text-xs font-black"
                            :class="{ 'shadow-sm': currentIndex === index, 'nav-button': currentIndex !== index }"
                            :style="currentIndex === index ? 'background: var(--accent); color: var(--accent-ink); border: 1px solid var(--accent);' : (flags[q.id] ? 'background: var(--warning-soft); color: var(--warning); border: 1px solid color-mix(in srgb, var(--warning) 30%, var(--line));' : (answers[q.id] ? 'background: var(--success-soft); color: var(--success); border: 1px solid color-mix(in srgb, var(--success) 28%, var(--line));' : 'color: var(--muted);'))">
                            <span x-text="index + 1"></span>
                            <span x-show="flags[q.id]" class="absolute right-1 top-1 h-1.5 w-1.5 rounded-full" style="background: var(--warning);"></span>
                        </button>
                    </template>
                </div>
            </div>

            <div class="border-t p-4" style="border-color: var(--line);">
                <p x-show="!isComplete" class="rounded-xl px-3 py-2.5 text-center text-[10px] font-bold leading-5" style="background: var(--warning-soft); color: var(--warning);">
                    <span x-text="unansweredCount"></span> soal belum dijawab
                </p>
                <p class="mt-3 flex items-center justify-center gap-1.5 text-[10px] font-bold" style="color: var(--muted);">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m0 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002-2H6a2 2 0 01-2-2v-6a2 2 0 012-2zm10-10V7a4 4 0 00-8 0v4"/></svg>
                    Mode fokus aktif
                </p>
            </div>
        </aside>
    </div>

    <script>
        function cbtApp() {
            return {
                questions: @json($questions),
                savedAnswers: @json($savedAnswers),
                timeLeft: Math.floor({{ $remainingSeconds }}), 
                initialRemainingSeconds: Math.floor({{ $remainingSeconds }}),
                attemptId: {{ $attemptId }},
                
                currentIndex: 0,
                answers: {},
                flags: {},
                focusLostCount: 0,
                ready: false,
                isBlurred: false,
                feedbackFlash: false,
                feedbackTimer: null,
                timerInterval: null,
                questionTransitioning: false,
                questionSwitchTimer: null,

                get currentQuestion() { return this.questions[this.currentIndex]; },
                get answeredCount() { return this.questions.filter((question) => this.hasAnswer(question)).length; },
                get flaggedCount() { return Object.keys(this.flags).length; },
                get unansweredCount() { return Math.max(0, this.questions.length - this.answeredCount); },
                get isComplete() { return this.unansweredCount === 0; },
                get completionPercent() { return this.questions.length ? Math.round((this.answeredCount / this.questions.length) * 100) : 0; },
                get unansweredNumbers() {
                    return this.questions
                        .map((q, index) => this.hasAnswer(q) ? null : index + 1)
                        .filter(number => number !== null);
                },

                hasQuestionContext(question) {
                    return Boolean(question && (question.media_url || question.interaction_prompt));
                },

                hasAnswer(question) {
                    if (!question) return false;
                    return Boolean(this.answers[question.id]);
                },

                interactionLabel(type) {
                    return {
                        multiple_choice: 'Pilihan Ganda',
                        image_context: 'Gambar',
                    }[type || 'multiple_choice'] || 'Pilihan Ganda';
                },

                interactionPromptLabel(type) {
                    return {
                        image_context: 'Amati Media Soal',
                        multiple_choice: 'Konteks Soal'
                    }[type || 'multiple_choice'] || 'Konteks Soal';
                },

                contextCardClass(type) {
                    return type === 'image_context' ? 'context-media' : 'context-default';
                },

                optionCardClass(idx, qId, optionId) {
                    const selected = this.answers[qId] == optionId;
                    const tone = ['answer-tone-sky', 'answer-tone-indigo', 'answer-tone-rose', 'answer-tone-emerald'][idx % 4];
                    return `answer-card ${tone} ${selected ? 'answer-selected' : ''}`;
                },

                optionLetterClass(idx, qId, optionId) {
                    const selected = this.answers[qId] == optionId;
                    return selected ? 'answer-key-selected' : 'answer-key-default';
                },

                flashSaved() {
                    this.feedbackFlash = true;
                    clearTimeout(this.feedbackTimer);
                    this.feedbackTimer = setTimeout(() => {
                        this.feedbackFlash = false;
                    }, 900);
                },

                initCBT() {
                    this.initializeTheme();

                    // 1. Restore Data
                    Object.values(this.savedAnswers).forEach(record => {
                        if (record.quiz_option_id) this.answers[record.quiz_question_id] = record.quiz_option_id;
                        if (record.is_flagged) this.flags[record.quiz_question_id] = true;
                    });

                    this.ready = true;
                    this.startTimer();
                    this.activateStrictMode();
                },

                getStoredTheme() {
                    try {
                        return localStorage.getItem('color-theme');
                    } catch (error) {
                        return null;
                    }
                },

                applyStoredTheme(theme, animate = false) {
                    const selectedTheme = theme === 'dark' ? 'dark' : 'light';
                    const root = document.documentElement;
                    const reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

                    const applyTheme = () => {
                        if (animate && !reduceMotion) root.classList.add('theme-transition');

                        root.classList.toggle('dark', selectedTheme === 'dark');
                        root.style.colorScheme = selectedTheme;

                        if (animate && !reduceMotion) {
                            window.setTimeout(() => root.classList.remove('theme-transition'), 380);
                        }
                    };

                    if (animate && !reduceMotion && typeof document.startViewTransition === 'function') {
                        document.startViewTransition(applyTheme);
                    } else {
                        applyTheme();
                    }
                },

                initializeTheme() {
                    const savedTheme = this.getStoredTheme();
                    const selectedTheme = savedTheme === 'dark' || savedTheme === 'light'
                        ? savedTheme
                        : (document.documentElement.classList.contains('dark') ? 'dark' : 'light');

                    // Tema hanya dibaca dari pengaturan global; halaman kuis tidak menyediakan tombol pengubah tema.
                    this.applyStoredTheme(selectedTheme, false);

                    window.addEventListener('storage', (event) => {
                        if (event.key === 'color-theme' && (event.newValue === 'dark' || event.newValue === 'light')) {
                            this.applyStoredTheme(event.newValue, true);
                        }
                    });
                },

                formatTime(seconds) {
                    if (seconds < 0) seconds = 0;
                    const m = Math.floor(seconds / 60);
                    const s = seconds % 60;
                    return `${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
                },

                startTimer() {
                    this.timerInterval = setInterval(() => {
                        if (this.timeLeft > 0) this.timeLeft--;
                        else this.timeOut();
                    }, 1000);
                },

                scrollQuestionToTop() {
                    this.$nextTick(() => {
                        const scroller = this.$refs.questionScroll;
                        if (scroller) scroller.scrollTo({ top: 0, behavior: 'smooth' });
                    });
                },

                changeQuestion(index) {
                    const targetIndex = Number(index);

                    if (
                        this.questionTransitioning ||
                        !Number.isInteger(targetIndex) ||
                        targetIndex < 0 ||
                        targetIndex >= this.questions.length ||
                        targetIndex === this.currentIndex
                    ) {
                        return;
                    }

                    this.questionTransitioning = true;
                    window.clearTimeout(this.questionSwitchTimer);

                    this.questionSwitchTimer = window.setTimeout(() => {
                        this.currentIndex = targetIndex;
                        this.scrollQuestionToTop();

                        this.$nextTick(() => {
                            requestAnimationFrame(() => {
                                this.questionTransitioning = false;
                            });
                        });
                    }, 150);
                },

                nextQuestion() {
                    this.changeQuestion(this.currentIndex + 1);
                },

                prevQuestion() {
                    this.changeQuestion(this.currentIndex - 1);
                },

                goToQuestion(index) {
                    this.changeQuestion(index);
                },
                
                selectAnswer(qId, oId) {
                    this.answers[qId] = oId;
                    this.flashSaved();
                    this.saveToDb(qId, oId, this.flags[qId] || false);
                },

                toggleFlag(qId) {
                    if (this.flags[qId]) {
                        delete this.flags[qId];
                        this.saveToDb(qId, this.answers[qId] || null, false);
                    } else {
                        this.flags[qId] = true;
                        this.saveToDb(qId, this.answers[qId] || null, true);
                    }
                },

                saveToDb(qId, oId, isFlagged) {
                    return fetch("{{ route('quiz.save-progress') }}", {
                        method: "POST",
                        headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content },
                        body: JSON.stringify({
                            attempt_id: this.attemptId,
                            question_id: qId,
                            option_id: oId,
                            is_flagged: isFlagged ? 1 : 0,
                            client_elapsed_seconds: Math.max(0, this.initialRemainingSeconds - this.timeLeft)
                        })
                    }).catch(e => {});
                },

                confirmSubmit() {
                    if (!this.isComplete) {
                        const missing = this.unansweredNumbers.slice(0, 8).join(', ');
                        alert(`Evaluasi belum dapat dikumpulkan. Lengkapi ${this.unansweredCount} soal yang masih kosong${missing ? ': ' + missing : ''}.`);
                        if (this.unansweredNumbers.length > 0) {
                            this.goToQuestion(this.unansweredNumbers[0] - 1);
                        }
                        return;
                    }

                    if(confirm("Apakah Anda yakin ingin mengumpulkan jawaban dan mengakhiri ujian?")) {
                        this.submitQuiz();
                    }
                },

                submitQuiz() {
                    this.disableStrictMode();
                    document.body.innerHTML += `<div class="fixed inset-0 z-[200] bg-slate-950/95 flex flex-col items-center justify-center text-white"><div class="w-16 h-16 border-4 border-cyan-400 border-t-transparent rounded-full animate-spin mb-4 shadow-[0_0_20px_rgba(34,211,238,.28)]"></div><h2 class="text-xl font-bold tracking-widest uppercase">Menyimpan Jawaban...</h2></div>`;

                    fetch("{{ route('quiz.submit') }}", {
                        method: "POST",
                        headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content },
                        body: JSON.stringify({
                            attempt_id: this.attemptId,
                            time_spent: Math.max(0, this.initialRemainingSeconds - this.timeLeft),
                            focus_lost_count: this.focusLostCount
                        })
                    }).then(async res => {
                        const data = await res.json();
                        if (!res.ok) {
                            throw data;
                        }
                        return data;
                    }).then(data => {
                        if (data.redirect) {
                            window.location.href = data.redirect;
                        } else {
                            window.location.href = "{{ route('dashboard') }}";
                        }
                    }).catch(error => {
                        this.disableStrictMode();
                        alert(error.message || 'Evaluasi belum dapat dikumpulkan. Pastikan semua soal sudah dijawab.');
                        window.location.reload();
                    });
                },

                timeOut() {
                    clearInterval(this.timerInterval);
                    if (!this.isComplete) {
                        alert(`Waktu habis, tetapi evaluasi belum lengkap. Lengkapi ${this.unansweredCount} soal yang masih kosong sebelum mengumpulkan.`);
                        return;
                    }

                    alert("WAKTU HABIS! Sistem mengumpulkan jawaban otomatis.");
                    this.submitQuiz();
                },

                // === STRICT SECURITY ===
                activateStrictMode() {
                    // Anti-Back Button
                    history.pushState(null, null, location.href);
                    window.onpopstate = () => history.go(1);

                    // Anti-Refresh/Close
                    window.onbeforeunload = (e) => {
                        e.preventDefault();
                        return "Ujian sedang berlangsung!";
                    };

                    // Anti-Switch Tab
                    document.addEventListener("visibilitychange", () => {
                        if (document.hidden) {
                            this.focusLostCount++;
                            this.isBlurred = true;
                            document.title = "KEMBALI KE EVALUASI";
                        } else {
                            document.title = "{{ $chapterId == 99 ? 'Evaluasi Akhir' : 'Evaluasi Bab ' . $chapterId }}";
                        }
                    });

                    // Anti-Context Menu & Keys
                    document.addEventListener('keydown', (e) => {
                        if (e.key === 'F5' || (e.ctrlKey && e.key === 'r')) e.preventDefault();
                    });
                },

                disableStrictMode() {
                    window.onbeforeunload = null;
                }
            }
        }
    </script>
</body>
</html>
