<!DOCTYPE html>
{{-- Analitik TP: adaptasi visual dan interaksi jQuery dari halaman kuis. --}}
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tujuan Pembelajaran · Panel Admin Utilwind</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: { extend: { fontFamily: { sans: ['Inter', 'sans-serif'] } } }
        };

        // Membaca preferensi tema yang sudah disimpan oleh switcher sistem.
        const savedTheme = localStorage.getItem('color-theme');
        const useDarkTheme = savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches);
        document.documentElement.classList.toggle('dark', useDarkTheme);
        document.documentElement.classList.add('js-motion');
    </script>
    <style>
        :root {
            --page-bg: #f8fafc;
            --page-surface: rgba(255, 255, 255, .88);
            --page-surface-strong: rgba(255, 255, 255, .96);
            --page-border: rgba(15, 23, 42, .075);
            --page-border-strong: rgba(99, 102, 241, .22);
            --page-ink: #0f172a;
            --page-muted: #64748b;
            --page-primary: #4f46e5;
            --page-secondary: #0891b2;
            --page-shadow: 0 10px 30px rgba(15, 23, 42, .055);
            --page-shadow-hover: 0 18px 40px rgba(15, 23, 42, .10);
        }

        .dark {
            --page-bg: #020617;
            --page-surface: rgba(10, 14, 23, .86);
            --page-surface-strong: rgba(2, 6, 23, .94);
            --page-border: rgba(255, 255, 255, .08);
            --page-border-strong: rgba(129, 140, 248, .30);
            --page-ink: #e2e8f0;
            --page-muted: #94a3b8;
            --page-shadow: 0 12px 34px rgba(0, 0, 0, .22);
            --page-shadow-hover: 0 20px 46px rgba(0, 0, 0, .34);
        }

        * { -webkit-tap-highlight-color: transparent; }
        html { color-scheme: light; }
        html.dark { color-scheme: dark; }
        body {
            font-family: 'Inter', sans-serif;
            transition: background-color .28s ease, color .28s ease;
        }

        .app-background {
            background:
                radial-gradient(circle at 12% 100%, rgba(6, 182, 212, .10), transparent 25rem),
                radial-gradient(circle at 88% 8%, rgba(99, 102, 241, .12), transparent 29rem),
                var(--page-bg);
        }
        .dark .app-background {
            background:
                radial-gradient(circle at 12% 100%, rgba(6, 182, 212, .08), transparent 26rem),
                radial-gradient(circle at 88% 8%, rgba(99, 102, 241, .14), transparent 30rem),
                #020617;
        }

        .app-header {
            background: rgba(255, 255, 255, .82);
            border-color: rgba(15, 23, 42, .055);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            transition: background-color .28s ease, border-color .28s ease;
        }
        .dark .app-header {
            background: rgba(2, 6, 23, .82);
            border-color: rgba(255, 255, 255, .07);
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border: 1px solid transparent;
            border-radius: 12px;
            color: #64748b;
            font-size: .9rem;
            font-weight: 600;
            transition: all .2s;
        }
        .dark .nav-link { color: #94a3b8; font-weight: 500; }
        .nav-link:hover { background: rgba(0, 0, 0, .03); color: #0f172a; }
        .dark .nav-link:hover { background: rgba(255, 255, 255, .03); color: white; }
        .nav-link.active {
            background: linear-gradient(90deg, rgba(99, 102, 241, .1) 0%, rgba(99, 102, 241, 0) 100%);
            border-left: 3px solid #6366f1;
            border-radius: 4px 12px 12px 4px;
            color: #6366f1;
        }
        .dark .nav-link.active { color: #818cf8; border-left-color: #818cf8; }

        .panel {
            position: relative;
            background: var(--page-surface);
            border: 1px solid var(--page-border);
            box-shadow: var(--page-shadow);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            transition: border-color .22s ease, box-shadow .22s ease, transform .22s ease, background-color .28s ease;
        }
        .panel:hover { border-color: var(--page-border-strong); }

        .chapter-summary {
            overflow: hidden;
            background:
                linear-gradient(135deg, rgba(99, 102, 241, .10), rgba(255, 255, 255, 0) 50%),
                var(--page-surface);
        }
        .dark .chapter-summary {
            background:
                linear-gradient(135deg, rgba(99, 102, 241, .17), rgba(15, 23, 42, 0) 50%),
                var(--page-surface);
        }
        .chapter-summary::after {
            content: '';
            position: absolute;
            right: -2.6rem;
            top: -2.6rem;
            width: 8.5rem;
            height: 8.5rem;
            border: 18px solid rgba(99, 102, 241, .08);
            border-radius: 999px;
            pointer-events: none;
        }
        .dark .chapter-summary::after { border-color: rgba(129, 140, 248, .12); }

        .metric-card {
            background: rgba(255, 255, 255, .64);
            border: 1px solid rgba(15, 23, 42, .065);
            box-shadow: 0 8px 20px rgba(15, 23, 42, .03);
            transition: transform .20s ease, border-color .20s ease, box-shadow .20s ease, background-color .28s ease;
        }
        .dark .metric-card {
            background: rgba(255, 255, 255, .035);
            border-color: rgba(255, 255, 255, .075);
            box-shadow: none;
        }
        .metric-card:hover {
            transform: translateY(-2px);
            border-color: rgba(99, 102, 241, .23);
            box-shadow: 0 12px 28px rgba(15, 23, 42, .07);
        }
        .dark .metric-card:hover {
            border-color: rgba(129, 140, 248, .28);
            box-shadow: 0 16px 30px rgba(0, 0, 0, .15);
        }

        .analytics-toolbar {
            background: rgba(255, 255, 255, .75);
        }
        .dark .analytics-toolbar { background: rgba(15, 23, 42, .64); }

        .chapter-select {
            width: 100%;
            appearance: none;
            border: 1px solid rgba(15, 23, 42, .10);
            background: rgba(255, 255, 255, .90);
            color: #334155;
            transition: border-color .20s ease, box-shadow .20s ease, background-color .28s ease;
        }
        .chapter-select:focus {
            outline: none;
            border-color: rgba(99, 102, 241, .46);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, .12);
        }
        .dark .chapter-select {
            border-color: rgba(255, 255, 255, .10);
            background: rgba(255, 255, 255, .045);
            color: #e2e8f0;
        }
        .dark .chapter-select:focus {
            border-color: rgba(129, 140, 248, .50);
            box-shadow: 0 0 0 3px rgba(129, 140, 248, .15);
        }

        .header-action {
            border: 1px solid rgba(99, 102, 241, .14);
            background: rgba(238, 242, 255, .84);
            color: #4338ca;
            box-shadow: 0 8px 20px rgba(79, 70, 229, .06);
            transition: transform .20s ease, background-color .20s ease, border-color .20s ease, box-shadow .20s ease;
        }
        .header-action:hover {
            transform: translateY(-1px);
            border-color: rgba(99, 102, 241, .28);
            background: #eef2ff;
            box-shadow: 0 12px 26px rgba(79, 70, 229, .11);
        }
        .dark .header-action {
            border-color: rgba(129, 140, 248, .22);
            background: rgba(99, 102, 241, .12);
            color: #c7d2fe;
            box-shadow: none;
        }
        .dark .header-action:hover { background: rgba(99, 102, 241, .20); border-color: rgba(129, 140, 248, .34); }

        .theme-toggle {
            border: 1px solid rgba(15, 23, 42, .07);
            background: rgba(255, 255, 255, .74);
            color: #475569;
            transition: transform .20s ease, background-color .20s ease, color .20s ease, border-color .20s ease;
        }
        .theme-toggle:hover { transform: translateY(-1px); border-color: rgba(99, 102, 241, .22); background: #fff; color: #4338ca; }
        .dark .theme-toggle { border-color: rgba(255, 255, 255, .08); background: rgba(255, 255, 255, .045); color: #cbd5e1; }
        .dark .theme-toggle:hover { border-color: rgba(129, 140, 248, .30); background: rgba(255, 255, 255, .08); color: #e0e7ff; }

        .logout-button {
            border: 1px solid rgba(239, 68, 68, .16);
            background: rgba(254, 242, 242, .78);
            color: #dc2626;
            transition: transform .20s ease, background-color .20s ease, border-color .20s ease, color .20s ease;
        }
        .logout-button:hover { transform: translateY(-1px); border-color: rgba(239, 68, 68, .28); background: #fee2e2; }
        .dark .logout-button { border-color: rgba(248, 113, 113, .20); background: rgba(239, 68, 68, .08); color: #fca5a5; }
        .dark .logout-button:hover { background: rgba(239, 68, 68, .16); color: #fff; }

        .chapter-panel {
            overflow: hidden;
        }
        .chapter-panel::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 3px;
            width: 100%;
            background: linear-gradient(90deg, #6366f1, #06b6d4, transparent 72%);
            opacity: .72;
        }

        .objective-grid {
            align-items: start;
            grid-auto-rows: max-content;
        }
        .objective-grid > .objective-card {
            align-self: start;
            height: fit-content;
        }

        .objective-card {
            position: relative;
            background: rgba(255, 255, 255, .72);
            border-color: rgba(15, 23, 42, .065);
            box-shadow: 0 7px 18px rgba(15, 23, 42, .025);
            transition: transform .20s ease, border-color .20s ease, box-shadow .20s ease, background-color .28s ease;
        }
        .dark .objective-card {
            background: rgba(255, 255, 255, .03);
            border-color: rgba(255, 255, 255, .08);
            box-shadow: none;
        }
        .objective-card:hover {
            transform: translateY(-2px);
            border-color: rgba(99, 102, 241, .24);
            box-shadow: var(--page-shadow-hover);
        }
        .dark .objective-card:hover { border-color: rgba(129, 140, 248, .28); }
        .objective-card.is-attention {
            background: linear-gradient(145deg, rgba(255, 251, 235, .95), rgba(255, 255, 255, .80));
            border-color: rgba(245, 158, 11, .26);
        }
        .objective-card.is-empty {
            background: linear-gradient(145deg, rgba(255, 247, 247, .96), rgba(255, 255, 255, .80));
            border-color: rgba(244, 63, 94, .24);
        }
        .objective-card.is-stable {
            background: linear-gradient(145deg, rgba(247, 254, 250, .96), rgba(255, 255, 255, .80));
            border-color: rgba(16, 185, 129, .16);
        }
        .dark .objective-card.is-attention {
            background: linear-gradient(145deg, rgba(120, 53, 15, .18), rgba(255,255,255,.025));
            border-color: rgba(245, 158, 11, .24);
        }
        .dark .objective-card.is-empty {
            background: linear-gradient(145deg, rgba(136, 19, 55, .18), rgba(255,255,255,.025));
            border-color: rgba(251, 113, 133, .24);
        }
        .dark .objective-card.is-stable {
            background: linear-gradient(145deg, rgba(6, 78, 59, .14), rgba(255,255,255,.025));
            border-color: rgba(52, 211, 153, .16);
        }

        .objective-progress {
            box-shadow: inset 0 1px 1px rgba(15, 23, 42, .045);
        }

        .state-note {
            display: flex;
            align-items: flex-start;
            gap: .65rem;
            border: 1px solid transparent;
            border-radius: .8rem;
            padding: .72rem .76rem;
            font-size: .75rem;
            line-height: 1.35rem;
        }
        .state-note .state-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 auto;
            width: 1.65rem;
            height: 1.65rem;
            border-radius: .55rem;
        }
        .state-note .state-label {
            margin-bottom: .08rem;
            font-size: .60rem;
            font-weight: 800;
            letter-spacing: .08em;
            text-transform: uppercase;
        }
        .state-note.attention { border-color: rgba(245,158,11,.20); background: rgba(255,251,235,.82); color: #92400e; }
        .state-note.attention .state-icon { background: rgba(251,191,36,.16); color: #b45309; }
        .state-note.empty { border-color: rgba(244,63,94,.18); background: rgba(255,241,242,.80); color: #9f1239; }
        .state-note.empty .state-icon { background: rgba(251,113,133,.14); color: #be123c; }
        .state-note.stable { border-color: rgba(16,185,129,.16); background: rgba(236,253,245,.80); color: #047857; }
        .state-note.stable .state-icon { background: rgba(52,211,153,.14); color: #059669; }
        .dark .state-note.attention { border-color: rgba(245,158,11,.20); background: rgba(245,158,11,.09); color: #fde68a; }
        .dark .state-note.attention .state-icon { background: rgba(245,158,11,.14); color: #fcd34d; }
        .dark .state-note.empty { border-color: rgba(251,113,133,.22); background: rgba(244,63,94,.09); color: #fecdd3; }
        .dark .state-note.empty .state-icon { background: rgba(251,113,133,.14); color: #fda4af; }
        .dark .state-note.stable { border-color: rgba(52,211,153,.16); background: rgba(16,185,129,.09); color: #a7f3d0; }
        .dark .state-note.stable .state-icon { background: rgba(52,211,153,.13); color: #6ee7b7; }

        .tooltip-container {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            z-index: 35;
        }
        .tooltip-container:hover, .tooltip-container:focus-within { z-index: 75; }
        .tooltip-trigger {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 1.05rem;
            height: 1.05rem;
            border: 1px solid rgba(15,23,42,.11);
            border-radius: 999px;
            background: rgba(255,255,255,.84);
            color: #64748b;
            cursor: help;
            font-size: .62rem;
            font-weight: 900;
            line-height: 1;
            transition: transform .18s ease, color .18s ease, background-color .18s ease, border-color .18s ease;
        }
        .tooltip-trigger:hover, .tooltip-trigger:focus {
            outline: none;
            transform: scale(1.08);
            border-color: rgba(99,102,241,.30);
            background: #eef2ff;
            color: #4f46e5;
        }
        .dark .tooltip-trigger { border-color: rgba(255,255,255,.15); background: rgba(255,255,255,.045); color: #94a3b8; }
        .dark .tooltip-trigger:hover, .dark .tooltip-trigger:focus { border-color: rgba(129,140,248,.34); background: rgba(129,140,248,.13); color: #c7d2fe; }
        .tooltip-content {
            position: absolute;
            z-index: 80;
            width: max-content;
            min-width: 13rem;
            max-width: 17rem;
            padding: .70rem .80rem;
            border: 1px solid rgba(15,23,42,.10);
            border-radius: .75rem;
            background: rgba(255,255,255,.98);
            color: #475569;
            box-shadow: 0 16px 34px rgba(15,23,42,.14);
            font-size: .68rem;
            font-weight: 600;
            line-height: 1.35;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: opacity .18s ease, transform .20s ease, visibility .20s ease;
        }
        .dark .tooltip-content { border-color: rgba(255,255,255,.10); background: #0f172a; color: #cbd5e1; box-shadow: 0 18px 38px rgba(0,0,0,.48); }
        .tooltip-up .tooltip-content { bottom: calc(100% + .6rem); left: 50%; transform: translate(-50%, .38rem); }
        .tooltip-down .tooltip-content { top: calc(100% + .6rem); left: 50%; transform: translate(-50%, -.38rem); }
        .tooltip-right .tooltip-content { left: auto; right: -.35rem; transform: translate(0, -.38rem); }
        .tooltip-container:hover .tooltip-content, .tooltip-container:focus-within .tooltip-content { opacity: 1; visibility: visible; transform: translate(-50%, 0); }
        .tooltip-right:hover .tooltip-content, .tooltip-right:focus-within .tooltip-content { transform: translate(0, 0); }

        .objective-details {
            isolation: isolate;
            overflow: hidden;
            overflow-anchor: none;
            border: 1px solid rgba(15,23,42,.075);
            border-radius: .9rem;
            background: rgba(248,250,252,.62);
            transition: border-color .20s ease, background-color .20s ease, box-shadow .20s ease;
        }
        .objective-details.is-open {
            border-color: rgba(99,102,241,.18);
            background: rgba(255,255,255,.82);
            box-shadow: 0 10px 22px rgba(15,23,42,.045);
        }
        .dark .objective-details { border-color: rgba(255,255,255,.08); background: rgba(255,255,255,.018); }
        .dark .objective-details.is-open { border-color: rgba(129,140,248,.22); background: rgba(255,255,255,.035); box-shadow: 0 12px 24px rgba(0,0,0,.13); }

        .details-toggle {
            display: flex;
            min-height: 2.85rem;
            list-style: none;
            cursor: pointer;
            user-select: none;
            padding: .72rem .82rem;
            transition: background-color .18s ease, color .18s ease;
        }
        .details-toggle::-webkit-details-marker { display: none; }
        .details-toggle:hover { background: rgba(99,102,241,.045); }
        .dark .details-toggle:hover { background: rgba(129,140,248,.08); }
        .objective-details.is-open .details-toggle {
            border-bottom: 1px solid rgba(15,23,42,.065);
            background: rgba(99,102,241,.045);
            color: #3730a3;
        }
        .dark .objective-details.is-open .details-toggle {
            border-bottom-color: rgba(255,255,255,.075);
            background: rgba(129,140,248,.09);
            color: #e0e7ff;
        }
        .details-toggle .toggle-icon { transition: transform .22s ease, color .18s ease; }
        .objective-details.is-open .details-toggle .toggle-icon { transform: rotate(180deg); color: #4f46e5; }
        .dark .objective-details.is-open .details-toggle .toggle-icon { color: #a5b4fc; }
        .detail-count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 1.45rem;
            border: 1px solid rgba(15,23,42,.08);
            border-radius: .45rem;
            background: rgba(255,255,255,.72);
            color: #64748b;
            padding: .1rem .38rem;
            font-size: .625rem;
            font-weight: 800;
            line-height: 1.1;
        }
        .dark .detail-count { border-color: rgba(255,255,255,.10); background: rgba(255,255,255,.05); color: #cbd5e1; }

        .details-toggle {
            width: 100%;
            border: 0;
            background: transparent;
            text-align: left;
        }
        .details-content {
            display: none;
            overflow: hidden;
        }
        .details-content-inner { min-height: 0; overflow: hidden; }
        .details-list { display: grid; gap: .5rem; padding: .72rem; }

        .question-row {
            border-color: rgba(15,23,42,.065);
            background: rgba(255,255,255,.74);
            transition: border-color .18s ease, background-color .18s ease;
        }
        .question-row:hover { border-color: rgba(99,102,241,.18); background: rgba(255,255,255,.95); }
        .dark .question-row { border-color: rgba(255,255,255,.075); background: rgba(255,255,255,.025); }
        .dark .question-row:hover { border-color: rgba(129,140,248,.20); background: rgba(255,255,255,.048); }
        .question-number {
            display: inline-flex;
            align-items: center;
            border-radius: .45rem;
            background: rgba(99,102,241,.08);
            color: #4338ca;
            padding: .14rem .4rem;
            font-size: .60rem;
            font-weight: 900;
            letter-spacing: .04em;
        }
        .dark .question-number { background: rgba(99,102,241,.14); color: #c7d2fe; }
        .question-accuracy { color: #475569; }
        .dark .question-accuracy { color: #cbd5e1; }

        .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(148,163,184,.45); border-radius: 999px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #6366f1; }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #818cf8; }

        .page-reveal { animation: pageReveal .46s ease both; }
        @keyframes pageReveal {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .status-filter {
            border-color: rgba(15, 23, 42, .09);
            background: rgba(255, 255, 255, .68);
            color: #64748b;
        }
        .dark .status-filter {
            border-color: rgba(255, 255, 255, .10);
            background: rgba(255, 255, 255, .03);
            color: #94a3b8;
        }
        .status-filter:hover { border-color: rgba(99, 102, 241, .25); color: #4338ca; }
        .dark .status-filter:hover { border-color: rgba(129, 140, 248, .35); color: #c7d2fe; }
        .status-filter.is-active[data-status-filter="all"] { border-color: rgba(99,102,241,.38); background: rgba(238,242,255,.92); color: #4338ca; box-shadow: 0 5px 14px rgba(79,70,229,.09); }
        .status-filter.is-active[data-status-filter="attention"] { border-color: rgba(245,158,11,.40); background: rgba(255,251,235,.94); color: #b45309; box-shadow: 0 5px 14px rgba(180,83,9,.08); }
        .status-filter.is-active[data-status-filter="empty"] { border-color: rgba(244,63,94,.36); background: rgba(255,241,242,.94); color: #be123c; box-shadow: 0 5px 14px rgba(190,24,93,.08); }
        .status-filter.is-active[data-status-filter="stable"] { border-color: rgba(16,185,129,.34); background: rgba(236,253,245,.94); color: #047857; box-shadow: 0 5px 14px rgba(5,150,105,.08); }
        .dark .status-filter.is-active[data-status-filter="all"] { border-color: rgba(129,140,248,.45); background: rgba(99,102,241,.17); color: #c7d2fe; }
        .dark .status-filter.is-active[data-status-filter="attention"] { border-color: rgba(245,158,11,.40); background: rgba(245,158,11,.14); color: #fde68a; }
        .dark .status-filter.is-active[data-status-filter="empty"] { border-color: rgba(251,113,133,.42); background: rgba(244,63,94,.14); color: #fecdd3; }
        .dark .status-filter.is-active[data-status-filter="stable"] { border-color: rgba(52,211,153,.36); background: rgba(16,185,129,.13); color: #a7f3d0; }

        .tooltip-container.is-tooltip-open .tooltip-content {
            opacity: 1;
            visibility: visible;
            transform: translate(-50%, 0);
        }
        .tooltip-right.is-tooltip-open .tooltip-content { transform: translate(0, 0); }

        html.theme-changing,
        html.theme-changing * {
            transition: background-color .28s ease, color .28s ease, border-color .28s ease, box-shadow .28s ease !important;
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: .01ms !important;
                scroll-behavior: auto !important;
            }
        }
    
        /* Adaptasi visual kuis untuk Tujuan Pembelajaran. */
        .glass-sidebar {
            background: rgba(255, 255, 255, .95);
            border-color: rgba(15, 23, 42, .055);
            box-shadow: 12px 0 36px rgba(15, 23, 42, .035);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }
        .dark .glass-sidebar {
            background: rgba(5, 8, 16, .95);
            border-color: rgba(255, 255, 255, .08);
            box-shadow: 12px 0 40px rgba(0, 0, 0, .22);
        }
        .glass-header {
            background: rgba(255, 255, 255, .80);
            border-color: rgba(15, 23, 42, .055);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
        }
        .dark .glass-header {
            background: rgba(2, 6, 23, .80);
            border-color: rgba(255, 255, 255, .08);
        }
        .glass-card {
            position: relative;
            background: rgba(255, 255, 255, .85);
            border-color: rgba(15, 23, 42, .06);
            box-shadow: 0 4px 30px rgba(0, 0, 0, .03);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        .dark .glass-card {
            background: rgba(10, 14, 23, .85);
            border-color: rgba(255, 255, 255, .08);
            box-shadow: 0 4px 30px rgba(0, 0, 0, .20);
        }
        .glass-card.panel:hover,
        .glass-card.objective-card:hover {
            border-color: rgba(99, 102, 241, .28);
        }

        .metric-tone-indigo { border-left: 4px solid #6366f1 !important; }
        .metric-tone-cyan { border-left: 4px solid #06b6d4 !important; }
        .metric-tone-emerald { border-left: 4px solid #10b981 !important; }
        .metric-tone-rose { border-left: 4px solid #ef4444 !important; }
        .metric-tone-indigo:hover { border-left-color: #4f46e5 !important; }
        .metric-tone-cyan:hover { border-left-color: #0891b2 !important; }
        .metric-tone-emerald:hover { border-left-color: #059669 !important; }
        .metric-tone-rose:hover { border-left-color: #dc2626 !important; }

        /* Sumber tooltip hanya dipakai oleh portal; konten tampil melalui layer global. */
        .tooltip-container { z-index: auto; }
        .tooltip-content { display: none !important; }
        #global-tooltip-layer {
            position: fixed;
            z-index: 2147483000;
            width: max-content;
            min-width: 13.75rem;
            max-width: min(24rem, calc(100vw - 1.5rem));
            padding: .78rem .9rem;
            border: 1px solid #e2e8f0;
            border-radius: .82rem;
            background: rgba(255, 255, 255, .98);
            color: #334155;
            box-shadow: 0 20px 42px rgba(15, 23, 42, .18);
            font-size: .70rem;
            font-weight: 600;
            line-height: 1.5;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transform: translate3d(0, 6px, 0) scale(.985);
            transition: opacity .16s ease, transform .16s ease, visibility .16s ease;
        }
        #global-tooltip-layer.is-visible {
            opacity: 1;
            visibility: visible;
            transform: translate3d(0, 0, 0) scale(1);
        }
        #global-tooltip-layer::after {
            content: '';
            position: absolute;
            left: var(--tooltip-arrow-left, 50%);
            width: .65rem;
            height: .65rem;
            background: inherit;
            border: inherit;
            transform: translateX(-50%) rotate(45deg);
        }
        #global-tooltip-layer[data-placement="bottom"]::after {
            top: -.37rem;
            border-right: 0;
            border-bottom: 0;
        }
        #global-tooltip-layer[data-placement="top"]::after {
            bottom: -.37rem;
            border-left: 0;
            border-top: 0;
        }
        .dark #global-tooltip-layer {
            border-color: rgba(255, 255, 255, .10);
            background: #020617;
            color: #e2e8f0;
            box-shadow: 0 20px 54px rgba(0, 0, 0, .62);
        }
        .tooltip-trigger:focus-visible {
            outline: 2px solid rgba(99, 102, 241, .55);
            outline-offset: 2px;
        }
        #global-tooltip-layer .tp-insight-tooltip { min-width: min(20rem, calc(100vw - 3rem)); }
        #global-tooltip-layer .tp-insight-kicker {
            display: block;
            margin: 0;
            color: #4f46e5;
            font-size: .62rem;
            font-weight: 900;
            letter-spacing: .14em;
            text-transform: uppercase;
        }
        #global-tooltip-layer .tp-insight-title {
            display: block;
            margin: .25rem 0 0;
            color: #0f172a;
            font-size: .86rem;
            font-weight: 900;
            line-height: 1.35;
        }
        #global-tooltip-layer .tp-insight-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: .45rem;
            margin-top: .72rem;
        }
        #global-tooltip-layer .tp-insight-metric {
            display: block;
            border: 1px solid rgba(15, 23, 42, .08);
            border-radius: .62rem;
            background: rgba(248, 250, 252, .92);
            padding: .5rem .58rem;
        }
        #global-tooltip-layer .tp-insight-metric b {
            display: block;
            color: #0f172a;
            font-size: .94rem;
            line-height: 1;
        }
        #global-tooltip-layer .tp-insight-metric span {
            display: block;
            margin-top: .16rem;
            color: #64748b;
            font-size: .58rem;
            font-weight: 900;
            letter-spacing: .10em;
            text-transform: uppercase;
        }
        #global-tooltip-layer .tp-insight-note,
        #global-tooltip-layer .tp-insight-source {
            display: block;
            margin: .72rem 0 0;
            border-radius: .68rem;
            padding: .58rem .65rem;
        }
        #global-tooltip-layer .tp-insight-note {
            border: 1px solid rgba(99, 102, 241, .14);
            background: rgba(238, 242, 255, .72);
            color: #3730a3;
        }
        #global-tooltip-layer .tp-insight-source {
            border: 1px dashed rgba(100, 116, 139, .24);
            background: rgba(248, 250, 252, .62);
            color: #64748b;
            font-size: .64rem;
        }
        .dark #global-tooltip-layer .tp-insight-kicker { color: #a5b4fc; }
        .dark #global-tooltip-layer .tp-insight-title { color: #f8fafc; }
        .dark #global-tooltip-layer .tp-insight-metric {
            border-color: rgba(255, 255, 255, .09);
            background: rgba(255, 255, 255, .045);
        }
        .dark #global-tooltip-layer .tp-insight-metric b { color: #f8fafc; }
        .dark #global-tooltip-layer .tp-insight-metric span { color: #94a3b8; }
        .dark #global-tooltip-layer .tp-insight-note {
            border-color: rgba(129, 140, 248, .22);
            background: rgba(99, 102, 241, .13);
            color: #e0e7ff;
        }
        .dark #global-tooltip-layer .tp-insight-source {
            border-color: rgba(148, 163, 184, .22);
            background: rgba(255, 255, 255, .035);
            color: #94a3b8;
        }

        .page-content {
            animation: bankSoalReveal .36s ease both;
        }
        @keyframes bankSoalReveal {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }

    </style>

    <style>
        /* ==========================================================
           PENYAJIAN DATA LEARNING ANALYTICS — PEMETAAN TP
           Semua nilai menunjukkan basis hitung agar angka dapat dibaca
           sebagai data, bukan sekadar dekorasi tampilan.
           ========================================================== */
        .tp-learning-board {
            overflow: hidden;
            border: 1px solid var(--page-border);
            border-radius: 1.25rem;
            background: linear-gradient(135deg, rgba(99,102,241,.08), rgba(255,255,255,.74) 42%, rgba(6,182,212,.055)), var(--page-surface);
            box-shadow: var(--page-shadow);
        }
        .dark .tp-learning-board {
            background: linear-gradient(135deg, rgba(99,102,241,.15), rgba(15,23,42,.28) 42%, rgba(6,182,212,.08)), var(--page-surface);
        }
        .tp-learning-board-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            padding: 1.15rem 1.25rem;
            border-bottom: 1px solid var(--page-border);
        }
        .tp-board-kicker {
            color: #4f46e5;
            font-size: .60rem;
            font-weight: 900;
            letter-spacing: .16em;
            text-transform: uppercase;
        }
        .dark .tp-board-kicker { color: #a5b4fc; }
        .tp-board-title {
            margin-top: .28rem;
            color: var(--page-ink);
            font-size: 1rem;
            font-weight: 900;
            letter-spacing: -.025em;
        }
        .tp-board-caption {
            margin-top: .28rem;
            color: var(--page-muted);
            font-size: .72rem;
            font-weight: 650;
            line-height: 1.45;
        }
        .tp-basis-button {
            display: inline-flex;
            flex: 0 0 auto;
            align-items: center;
            gap: .42rem;
            border: 1px solid rgba(99,102,241,.18);
            border-radius: .72rem;
            background: rgba(238,242,255,.82);
            color: #4338ca;
            padding: .58rem .72rem;
            font-size: .66rem;
            font-weight: 900;
            letter-spacing: .055em;
            text-transform: uppercase;
            transition: transform .18s ease, border-color .18s ease, background-color .18s ease;
        }
        .tp-basis-button:hover { transform: translateY(-1px); border-color: rgba(99,102,241,.36); background: #eef2ff; }
        .dark .tp-basis-button { border-color: rgba(129,140,248,.26); background: rgba(99,102,241,.13); color: #c7d2fe; }
        .dark .tp-basis-button:hover { background: rgba(99,102,241,.22); }
        .tp-learning-board-grid {
            display: grid;
            gap: 1px;
            background: var(--page-border);
        }
        .tp-board-item {
            min-width: 0;
            background: rgba(255,255,255,.68);
            padding: 1.05rem 1.15rem 1.1rem;
        }
        .dark .tp-board-item { background: rgba(15,23,42,.38); }
        .tp-board-label {
            display: flex;
            align-items: center;
            gap: .42rem;
            color: var(--page-muted);
            font-size: .60rem;
            font-weight: 900;
            letter-spacing: .12em;
            text-transform: uppercase;
        }
        .tp-board-figure {
            display: flex;
            align-items: center;
            gap: .85rem;
            margin-top: .85rem;
        }
        .tp-radial-progress {
            --tp-progress: 0%;
            display: grid;
            width: 4.1rem;
            height: 4.1rem;
            flex: 0 0 auto;
            place-items: center;
            border-radius: 999px;
            background: conic-gradient(#4f46e5 var(--tp-progress), rgba(148,163,184,.20) 0);
        }
        .tp-radial-progress > span {
            display: grid;
            width: 3.15rem;
            height: 3.15rem;
            place-items: center;
            border-radius: inherit;
            background: rgba(255,255,255,.96);
            color: #0f172a;
            font-size: .93rem;
            font-weight: 900;
            letter-spacing: -.04em;
            font-variant-numeric: tabular-nums;
        }
        .dark .tp-radial-progress { background: conic-gradient(#818cf8 var(--tp-progress), rgba(255,255,255,.10) 0); }
        .dark .tp-radial-progress > span { background: #0f172a; color: #f8fafc; }
        .tp-board-figure strong {
            display: block;
            color: var(--page-ink);
            font-size: 1.36rem;
            font-weight: 900;
            letter-spacing: -.055em;
            line-height: 1;
            font-variant-numeric: tabular-nums;
        }
        .tp-board-figure p {
            margin-top: .32rem;
            color: var(--page-muted);
            font-size: .68rem;
            font-weight: 700;
            line-height: 1.38;
        }
        .tp-bar-row { margin-top: .82rem; }
        .tp-bar-row:first-of-type { margin-top: .92rem; }
        .tp-bar-label {
            display: flex;
            justify-content: space-between;
            gap: .5rem;
            color: var(--page-muted);
            font-size: .66rem;
            font-weight: 750;
            line-height: 1.25;
        }
        .tp-bar-label b { color: var(--page-ink); font-weight: 900; font-variant-numeric: tabular-nums; }
        .tp-progress-track {
            position: relative;
            overflow: hidden;
            height: .46rem;
            margin-top: .36rem;
            border-radius: 999px;
            background: rgba(148,163,184,.18);
        }
        .dark .tp-progress-track { background: rgba(255,255,255,.075); }
        .tp-progress-track > span { display: block; height: 100%; border-radius: inherit; transition: width .56s cubic-bezier(.22,1,.36,1); }
        .tp-indigo-bar { background: #6366f1; }
        .tp-cyan-bar { background: #06b6d4; }
        .tp-emerald-bar { background: #10b981; }
        .tp-amber-bar { background: #f59e0b; }
        .tp-rose-bar { background: #f43f5e; }
        .tp-status-stack {
            display: flex;
            overflow: hidden;
            height: .78rem;
            margin-top: .95rem;
            border-radius: 999px;
            background: rgba(148,163,184,.18);
        }
        .dark .tp-status-stack { background: rgba(255,255,255,.075); }
        .tp-status-stack > span { display: block; min-width: 0; height: 100%; transition: width .56s cubic-bezier(.22,1,.36,1); }
        .tp-status-stable { background: #10b981; }
        .tp-status-attention { background: #f59e0b; }
        .tp-status-empty { background: #f43f5e; }
        .tp-status-legend { display: flex; flex-wrap: wrap; gap: .45rem .7rem; margin-top: .64rem; color: var(--page-muted); font-size: .61rem; font-weight: 800; }
        .tp-status-legend span { display: inline-flex; align-items: center; gap: .28rem; }
        .tp-status-legend i { display: inline-block; width: .44rem; height: .44rem; border-radius: 999px; }
        /* Data soal pada setiap kartu TP: jumlah butir, respons, dan hasil jawaban. */
        .tp-objective-question-data {
            margin-top: .92rem;
            border: 1px solid rgba(15,23,42,.065);
            border-radius: .92rem;
            background: rgba(248,250,252,.72);
            padding: .76rem;
        }
        .dark .tp-objective-question-data {
            border-color: rgba(255,255,255,.08);
            background: rgba(2,6,23,.25);
        }
        .tp-objective-question-data-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: .65rem;
        }
        .tp-objective-question-data-head p {
            color: var(--page-muted);
            font-size: .58rem;
            font-weight: 900;
            letter-spacing: .12em;
            text-transform: uppercase;
        }
        .tp-objective-question-data-head small {
            display: block;
            margin-top: .18rem;
            color: var(--page-muted);
            font-size: .62rem;
            font-weight: 700;
            line-height: 1.32;
        }
        .tp-objective-question-total {
            flex: 0 0 auto;
            border: 1px solid rgba(99,102,241,.16);
            border-radius: .56rem;
            background: rgba(238,242,255,.78);
            color: #4338ca;
            padding: .34rem .46rem;
            font-size: .61rem;
            font-weight: 900;
            line-height: 1;
            font-variant-numeric: tabular-nums;
        }
        .dark .tp-objective-question-total {
            border-color: rgba(129,140,248,.22);
            background: rgba(99,102,241,.13);
            color: #c7d2fe;
        }
        .tp-objective-metric-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0,1fr));
            gap: .56rem;
            margin-top: .7rem;
        }
        .tp-objective-metric {
            min-width: 0;
            min-height: 4.45rem;
            border: 1px solid rgba(15,23,42,.06);
            border-radius: .72rem;
            background: rgba(255,255,255,.72);
            padding: .66rem .70rem;
        }
        .dark .tp-objective-metric { border-color: rgba(255,255,255,.075); background: rgba(255,255,255,.035); }
        .tp-objective-metric span { display: block; overflow: hidden; color: var(--page-muted); font-size: .54rem; font-weight: 900; letter-spacing: .085em; text-overflow: ellipsis; text-transform: uppercase; white-space: nowrap; }
        .tp-objective-metric b { display: block; margin-top: .30rem; color: var(--page-ink); font-size: .98rem; font-weight: 900; line-height: 1; font-variant-numeric: tabular-nums; }
        .tp-objective-metric small { display: block; margin-top: .28rem; color: var(--page-muted); font-size: .60rem; font-weight: 700; line-height: 1.24; }
        .tp-objective-progress-wrap {
            margin-top: .82rem;
            border-top: 1px solid rgba(15,23,42,.06);
            padding-top: .82rem;
        }
        .dark .tp-objective-progress-wrap { border-color: rgba(255,255,255,.075); }
        .tp-objective-progress-meta { display: flex; justify-content: space-between; gap: .5rem; color: var(--page-muted); font-size: .61rem; font-weight: 800; line-height: 1.3; }
        .tp-objective-progress {
            position: relative;
            overflow: visible;
            height: .52rem;
            margin-top: .42rem;
            border-radius: 999px;
            background: rgba(148,163,184,.18);
        }
        .dark .tp-objective-progress { background: rgba(255,255,255,.075); }
        .tp-objective-progress > span { display: block; height: 100%; border-radius: inherit; transition: width .56s cubic-bezier(.22,1,.36,1); }
        .tp-objective-progress > i { position: absolute; top: -.22rem; bottom: -.22rem; width: 2px; border-radius: 999px; background: rgba(15,23,42,.48); transform: translateX(-1px); }
        .dark .tp-objective-progress > i { background: rgba(248,250,252,.58); }

        /* Urutan masuk kartu dibuat seirama dengan fade-up pada dasbor. */
        html.js-motion .objective-card.tp-objective-enter {
            opacity: 0;
            pointer-events: none;
            transform: translate3d(0, 16px, 0);
            transition: opacity .54s cubic-bezier(.22, .61, .36, 1),
                        transform .54s cubic-bezier(.22, .61, .36, 1);
            will-change: opacity, transform;
        }
        html.js-motion .objective-card.tp-objective-enter.is-visible {
            opacity: 1;
            pointer-events: auto;
            transform: translate3d(0, 0, 0);
        }

        .tp-metric-modal-card { overflow: hidden; background: rgba(255,255,255,.98); }
        .dark .tp-metric-modal-card { background: #0f172a; }
        .tp-metric-modal-hero {
            position: relative;
            overflow: hidden;
            border-bottom: 1px solid rgba(15,23,42,.07);
            background: linear-gradient(135deg, #eef2ff, #f8fafc 55%, #ecfeff);
        }
        .tp-metric-modal-hero::after {
            content: '';
            position: absolute;
            right: -3.8rem;
            top: -3.8rem;
            width: 10rem;
            height: 10rem;
            border: 18px solid rgba(99,102,241,.10);
            border-radius: 999px;
        }
        .dark .tp-metric-modal-hero { border-color: rgba(255,255,255,.08); background: linear-gradient(135deg, rgba(99,102,241,.18), rgba(15,23,42,.9) 55%, rgba(6,182,212,.12)); }
        .dark .tp-metric-modal-hero::after { border-color: rgba(129,140,248,.16); }
        .tp-metric-modal-grid { display: grid; gap: .7rem; }
        .tp-metric-modal-item { min-width: 0; border: 1px solid rgba(15,23,42,.075); border-radius: .86rem; background: #f8fafc; padding: .82rem .88rem; }
        .dark .tp-metric-modal-item { border-color: rgba(255,255,255,.08); background: rgba(255,255,255,.035); }
        .tp-metric-modal-item p { color: var(--page-muted); font-size: .60rem; font-weight: 900; letter-spacing: .11em; text-transform: uppercase; }
        .tp-metric-modal-item strong { display: block; margin-top: .30rem; color: var(--page-ink); font-size: .95rem; font-weight: 900; line-height: 1.25; }
        .tp-metric-modal-item span { display: block; margin-top: .25rem; color: var(--page-muted); font-size: .67rem; font-weight: 700; line-height: 1.42; }

        @media (min-width: 768px) {
            .tp-learning-board-grid { grid-template-columns: 1.02fr 1fr 1fr; }
            .tp-metric-modal-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }
        @media (max-width: 640px) {
            .tp-learning-board-head { align-items: stretch; flex-direction: column; }
            .tp-basis-button { justify-content: center; }
            .tp-objective-metric-grid { grid-template-columns: 1fr; }
        }
    </style>

    {{-- ==============================================================
         GULIR & KEMUNCULAN AWAL
         Mengikuti mekanisme halaman admin lain: area konten mandiri,
         gulir native area konten, dan transisi awal yang ringan.
         ============================================================== --}}
    <style>
        .smooth-tp-scroll {
            scroll-behavior: smooth;
            scroll-padding-top: 7rem;
            overscroll-behavior-y: contain;
            -webkit-overflow-scrolling: touch;
            scrollbar-gutter: stable both-edges;
            scrollbar-width: thin;
            scrollbar-color: rgba(99, 102, 241, .38) transparent;
        }

        .smooth-tp-scroll:focus { outline: none; }

        /* Sidebar dan panduan tidak membiarkan area konten bergerak di belakangnya. */
        html.tp-scroll-locked .smooth-tp-scroll { overflow-y: hidden !important; }

        .smooth-tp-scroll::-webkit-scrollbar { width: 6px; height: 6px; }
        .smooth-tp-scroll::-webkit-scrollbar-track { background: transparent; }
        .smooth-tp-scroll::-webkit-scrollbar-thumb {
            border: 2px solid transparent;
            border-radius: 999px;
            background: rgba(148, 163, 184, .42);
            background-clip: padding-box;
        }
        .smooth-tp-scroll::-webkit-scrollbar-thumb:hover {
            background: rgba(99, 102, 241, .52);
            background-clip: padding-box;
        }
        .dark .smooth-tp-scroll::-webkit-scrollbar-thumb {
            background: rgba(148, 163, 184, .40);
            background-clip: padding-box;
        }

        /* Urutan masuk konsisten dengan dasbor: fade up sederhana, tanpa skala atau blur. */
        html.js-motion .tp-entry {
            opacity: 0;
            transform: translate3d(0, 16px, 0);
            transition: opacity .54s cubic-bezier(.22, .61, .36, 1),
                        transform .54s cubic-bezier(.22, .61, .36, 1);
            will-change: opacity, transform;
        }
        html.js-motion .tp-entry.is-visible {
            opacity: 1;
            transform: translate3d(0, 0, 0);
        }
        html.js-motion .tp-entry[data-entry-order="2"] { transition-delay: 120ms; }
        html.js-motion .tp-entry[data-entry-order="3"] { transition-delay: 220ms; }

        html.js-motion .chapter-panel.tp-panel-enter {
            opacity: 0;
            transform: translate3d(0, 16px, 0);
            transition: opacity .52s cubic-bezier(.22, .61, .36, 1),
                        transform .52s cubic-bezier(.22, .61, .36, 1);
            will-change: opacity, transform;
        }
        html.js-motion .chapter-panel.tp-panel-enter.is-visible {
            opacity: 1;
            transform: translate3d(0, 0, 0);
        }

        @media (prefers-reduced-motion: reduce) {
            .smooth-tp-scroll { scroll-behavior: auto; }
            html.js-motion .tp-entry,
            html.js-motion .chapter-panel.tp-panel-enter {
                opacity: 1;
                transform: none;
                transition: none;
            }
        }
    </style>



    <style id="tp-standard-motion-override">
        html.js-motion .objective-card.tp-objective-enter {
            opacity: 0;
            pointer-events: none;
            transform: translate3d(0, 12px, 0);
            transition: opacity .34s cubic-bezier(.22, .61, .36, 1),
                        transform .34s cubic-bezier(.22, .61, .36, 1);
            will-change: opacity, transform;
        }
        html.js-motion .objective-card.tp-objective-enter.is-visible {
            opacity: 1;
            pointer-events: auto;
            transform: translate3d(0, 0, 0);
        }

        @media (prefers-reduced-motion: reduce) {
            html.js-motion .objective-card.tp-objective-enter {
                opacity: 1;
                pointer-events: auto;
                transform: none;
                transition: none;
            }
        }
    </style>


    <style id="learning-outcomes-quiz-shell-compat">
        /* Kerangka halaman mengikuti Analitik Kuis: sidebar, header, aksi, dan state Alpine. */
        .text-adaptive { color: #1e293b; }
        .dark .text-adaptive { color: #f8fafc; }
        .text-adaptive-muted { color: #64748b; }
        .dark .text-adaptive-muted { color: rgba(255,255,255,.4); }
        .modal-open { overflow: hidden; padding-right: 5px; }
        [x-cloak] { display: none !important; }

        /* Menyamakan frame utama dengan halaman Analitik Kuis. */
        .learning-outcomes-quiz-frame { background-color:#f8fafc; color:#0f172a; }
        .dark .learning-outcomes-quiz-frame { background-color:#020617; color:#e2e8f0; }
        .learning-outcomes-quiz-frame #admin-main-content {
            scroll-behavior:smooth;
            scroll-padding-top:7.5rem;
            overscroll-behavior-y:contain;
            scrollbar-gutter:stable both-edges;
        }
        .learning-outcomes-quiz-frame .glass-card {
            background:rgba(255,255,255,.85);
            border:1px solid rgba(0,0,0,.05);
            box-shadow:0 4px 30px rgba(0,0,0,.03);
            backdrop-filter:blur(10px);
            transition:all .3s cubic-bezier(.4,0,.2,1);
        }
        .dark .learning-outcomes-quiz-frame .glass-card {
            background:rgba(10,14,23,.85);
            border-color:rgba(255,255,255,.08);
            box-shadow:0 4px 30px rgba(0,0,0,.2);
        }
        .learning-outcomes-quiz-frame .glass-card:hover {
            border-color:rgba(99,102,241,.4);
            transform:translateY(-3px);
            box-shadow:0 10px 40px -10px rgba(0,0,0,.1);
        }
        .dark .learning-outcomes-quiz-frame .glass-card:hover { box-shadow:0 10px 40px -10px rgba(0,0,0,.5); }

        /* Header disamakan dengan Analitik Kuis; konten TP tetap mempertahankan datanya sendiri. */
        .lo-quiz-header { min-height:6rem; }
        .lo-quiz-header .header-status-dot { box-shadow:0 0 0 4px rgba(16,185,129,.09); }
        .lo-quiz-header .header-icon-btn { transition:background-color .2s ease,color .2s ease,transform .2s ease; }
        .lo-quiz-header .header-icon-btn:hover { transform:translateY(-1px); }
        .lo-quiz-header .header-divider { border-left:1px solid rgba(148,163,184,.42); }
        .dark .lo-quiz-header .header-divider { border-left-color:rgba(255,255,255,.10); }

        /* Pola gerak awal tetap seirama dengan dasbor: kartu naik dari bawah secara bertahap. */
        html.js-motion .tp-entry,
        html.js-motion .chapter-panel.tp-panel-enter,
        html.js-motion .objective-card.tp-objective-enter {
            will-change:opacity,transform;
        }
    </style>

</head>
<body class="learning-outcomes-quiz-frame flex h-screen w-full bg-slate-50 text-slate-800 transition-colors duration-500 dark:bg-[#020617] dark:text-slate-200"
      x-data="{
          sidebarOpen: false,
          isFullscreen: false,
          showDashboardInfoModal: false,
          syncFullscreen() { this.isFullscreen = !!document.fullscreenElement; },
          toggleFullscreen() {
              if (!document.fullscreenElement) { document.documentElement.requestFullscreen?.(); }
              else { document.exitFullscreen?.(); }
          }
      }"
      @fullscreenchange.window="syncFullscreen()"
      @keydown.escape.window="sidebarOpen = false; showDashboardInfoModal = false; if (document.fullscreenElement) document.exitFullscreen();"
      :class="{'modal-open': sidebarOpen || showDashboardInfoModal}">

    <div x-show="sidebarOpen" class="fixed inset-0 z-[90] bg-slate-900/60 backdrop-blur-sm transition-opacity dark:bg-[#020617]/80 md:hidden" @click="sidebarOpen = false" x-transition.opacity style="display:none" x-cloak></div>

    {{-- ==================== 1. SIDEBAR — SAMA DENGAN ANALITIK KUIS ==================== --}}
    <aside id="app-sidebar" class="glass-sidebar fixed z-[100] flex h-full w-72 shrink-0 -translate-x-full flex-col transition-transform duration-300 md:relative md:translate-x-0" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
        <div class="group relative flex h-24 items-center justify-between overflow-hidden border-b border-slate-200 px-8 transition-colors dark:border-white/5">
            <div class="absolute left-1/2 top-1/2 h-20 w-20 -translate-x-1/2 -translate-y-1/2 rounded-full bg-indigo-200/50 blur-[40px] opacity-0 transition duration-500 group-hover:opacity-100 dark:bg-indigo-500/20"></div>
            <a href="{{ route('landing') }}" class="relative z-10 flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" class="block h-8 w-auto object-contain dark:hidden" style="filter:brightness(0.1);" alt="Logo">
                <img src="{{ asset('images/logo.png') }}" class="hidden h-8 w-auto object-contain drop-shadow-sm dark:block" alt="Logo dark">
                <div>
                    <h1 class="text-xl font-black leading-none tracking-tight text-slate-900 transition-colors dark:text-white">Util<span class="text-indigo-600 dark:text-indigo-400">wind</span></h1>
                    <span class="text-[9px] font-bold uppercase tracking-[0.2em] text-slate-500 transition-colors dark:text-white/40">Panel Admin</span>
                </div>
            </a>
            <button id="sidebar-close" type="button" @click="sidebarOpen = false" class="relative z-10 text-slate-500 transition-colors hover:text-slate-800 dark:text-white/50 dark:hover:text-white md:hidden" aria-label="Tutup navigasi">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 18 6M6 6l12 12"/></svg>
            </button>
        </div>

        @include('admin.partials.sidebar-nav')

        <div class="mt-auto border-t border-slate-200 bg-slate-50/50 p-4 transition-colors dark:border-white/5 dark:bg-[#05080f]/50">
            <div class="mb-4 flex items-center gap-3 px-2">
                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-fuchsia-500 text-xs font-bold text-white shadow-lg">AD</div>
                <div class="min-w-0 overflow-hidden">
                    <p class="truncate text-xs font-bold text-slate-900 transition-colors dark:text-white">{{ Auth::user()->name ?? 'Administrator' }}</p>
                    <p class="truncate text-[10px] text-slate-500 transition-colors dark:text-white/40">Administrator Sistem</p>
                </div>
            </div>
            <button id="theme-toggle-sidebar" type="button" class="mb-2 flex w-full items-center justify-center gap-2 rounded-lg border border-transparent bg-slate-200/50 px-4 py-2.5 text-xs font-bold text-slate-700 shadow-sm transition-colors hover:bg-slate-200 dark:bg-white/5 dark:text-slate-300 dark:shadow-none dark:hover:bg-white/10">
                <svg id="theme-toggle-dark-icon-sidebar" class="hidden h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 0 1 6.707 2.707a8.001 8.001 0 1 0 10.586 10.586z"/></svg>
                <svg id="theme-toggle-light-icon-sidebar" class="hidden h-4 w-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a1 1 0 0 1 1 1v1a1 1 0 1 1-2 0V3a1 1 0 0 1 1-1zm4 8a4 4 0 1 1-8 0 4 4 0 0 1 8 0zm-.464 4.95.707.707a1 1 0 0 0 1.414-1.414l-.707-.707a1 1 0 0 0-1.414 1.414zm2.12-10.607a1 1 0 0 1 0 1.414l-.706.707a1 1 0 1 1-1.414-1.414l.707-.707a1 1 0 0 1 1.414 0zM17 11a1 1 0 1 0 0-2h-1a1 1 0 1 0 0 2h1zm-7 4a1 1 0 0 1 1 1v1a1 1 0 1 1-2 0v-1a1 1 0 0 1 1-1zM5.05 6.464A1 1 0 1 0 6.465 5.05l-.708-.707a1 1 0 0 0-1.414 1.414l.707.707zm1.414 8.486-.707.707a1 1 0 0 1-1.414-1.414l.707-.707a1 1 0 0 1 1.414 1.414zM4 11a1 1 0 1 0 0-2H3a1 1 0 0 0 0 2h1z" clip-rule="evenodd"/></svg>
                <span id="theme-toggle-text-sidebar">Ubah Tema</span>
            </button>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="group flex w-full items-center justify-center gap-2 rounded-lg border border-red-200 bg-red-50 px-4 py-2.5 text-xs font-bold text-red-600 shadow-sm transition-colors hover:border-red-300 hover:bg-red-100 hover:text-red-700 dark:border-red-500/20 dark:bg-red-500/10 dark:text-red-400 dark:shadow-none dark:hover:border-red-500 dark:hover:bg-red-500 dark:hover:text-white">
                    <svg class="h-3.5 w-3.5 transition group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0-4-4m4 4H7m6 4v1a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1"/></svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- ==================== MAIN CONTENT — SAMA DENGAN ANALITIK KUIS ==================== --}}
    <main id="admin-main-content" data-smooth-tp-scroll tabindex="-1" class="flex h-full min-w-0 flex-1 flex-col overflow-x-hidden overflow-y-auto">
        <header class="lo-quiz-header glass-header sticky top-0 z-40 flex shrink-0 flex-col justify-center px-6 transition-colors duration-500 md:px-10">
            <div class="flex w-full items-center justify-between">
                <div class="flex items-center gap-4">
                    <button id="sidebar-open" type="button" @click="sidebarOpen = true" class="rounded-lg bg-slate-100 p-2 text-slate-700 transition-colors hover:bg-slate-200 dark:bg-white/5 dark:text-white dark:hover:bg-white/10 md:hidden" aria-label="Buka navigasi">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <div class="flex items-center gap-3">
                        <div>
                            <nav class="mb-1.5 hidden text-[10px] font-bold text-slate-500 transition-colors dark:text-white/50 sm:flex" aria-label="Breadcrumb">
                                <ol class="inline-flex items-center space-x-1">
                                    <li class="inline-flex items-center"><a href="{{ route('admin.dashboard') }}" class="transition-colors hover:text-indigo-600 dark:hover:text-indigo-400">Dasbor</a></li>
                                    <li aria-hidden="true" class="flex items-center"><svg class="mx-1 h-3 w-3 text-slate-400 dark:text-white/30" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7"/></svg></li>
                                    <li class="inline-flex items-center"><a href="{{ route('admin.analytics.questions') }}" class="transition-colors hover:text-indigo-600 dark:hover:text-indigo-400">Kuis</a></li>
                                    <li aria-hidden="true" class="flex items-center"><svg class="mx-1 h-3 w-3 text-slate-400 dark:text-white/30" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7"/></svg></li>
                                    <li class="text-slate-700 transition-colors dark:text-white">Tujuan Pembelajaran</li>
                                </ol>
                            </nav>
                            <div class="flex items-center gap-2">
                                <h2 class="text-adaptive text-lg font-bold tracking-tight transition-colors md:text-xl">Tujuan Pembelajaran</h2>
                                <button id="learning-guide-open" type="button" class="mt-0.5 flex h-6 w-6 items-center justify-center rounded-full border border-slate-200 bg-white/50 text-[10px] font-black text-slate-400 shadow-sm transition-all duration-300 hover:border-indigo-200 hover:bg-white hover:text-indigo-600 hover:shadow-md focus:outline-none dark:border-white/10 dark:bg-white/5 dark:text-slate-500 dark:hover:border-indigo-500/30 dark:hover:bg-white/10 dark:hover:text-indigo-400 md:h-7 md:w-7 md:text-xs" title="Panduan tujuan pembelajaran">?</button>
                            </div>
                            <p class="text-adaptive-muted mt-0.5 flex items-center gap-1.5 text-[9px] transition-colors md:text-xs">
                                <span class="header-status-dot h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                <span>Soal terhubung, respons siswa, dan capaian TP</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-3 sm:gap-6">
                    <button type="button" onclick="window.location.reload()" class="header-icon-btn hidden rounded-full border border-transparent p-2.5 text-slate-500 transition-colors hover:bg-slate-200 hover:text-slate-900 dark:text-white/40 dark:hover:border-white/10 dark:hover:bg-white/5 dark:hover:text-white sm:block" title="Perbarui data">
                        <svg class="h-4 w-4 transition-transform duration-500 hover:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 0 0 4.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 0 1-15.357-2m15.357 2H15"/></svg>
                    </button>
                    <button type="button" @click="toggleFullscreen()" class="header-icon-btn hidden rounded-full border border-transparent p-2.5 text-slate-500 transition-colors hover:bg-slate-200 hover:text-slate-900 dark:text-white/40 dark:hover:border-white/10 dark:hover:bg-white/5 dark:hover:text-white md:block" title="Mode layar penuh">
                        <svg x-show="!isFullscreen" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                        <svg x-show="isFullscreen" x-cloak class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 18 6M6 6l12 12"/></svg>
                    </button>
                    <div class="header-divider ml-1 hidden pl-5 text-right transition-colors lg:block">
                        <p class="text-adaptive text-sm font-bold">{{ \Carbon\Carbon::now()->translatedFormat('d M Y') }}</p>
                        <p class="text-adaptive-muted mt-0.5 font-mono text-[10px]">{{ \Carbon\Carbon::now()->format('H:i') }} WIB</p>
                    </div>
                </div>
            </div>
        </header>


            <div class="page-content px-5 py-6 md:px-8 md:py-8">
                <div class="mx-auto max-w-7xl">
                    @php
                        $allObjectivesForAnalytics = collect($chapters ?? [])->flatMap(fn ($chapter) => collect($chapter['objectives'] ?? []))->values();
                        $objectiveState = function ($objective) {
                            $questionCount = (int) ($objective['question_count'] ?? 0);
                            $statusGroup = (string) ($objective['status_group'] ?? '');
                            $statusKey = (string) ($objective['status_key'] ?? '');
                            $needsQuestions = (bool) ($objective['needs_questions'] ?? false) || $questionCount === 0;
                            $needsAttention = (bool) ($objective['needs_attention'] ?? false) || in_array($statusGroup, ['attention', 'waiting'], true) || in_array($statusKey, ['attention', 'waiting'], true);

                            return $needsQuestions ? 'empty' : ($needsAttention ? 'attention' : 'stable');
                        };

                        $totalObjectivesForAnalytics = $allObjectivesForAnalytics->count();
                        $stableObjectivesForAnalytics = $allObjectivesForAnalytics->filter(fn ($objective) => $objectiveState($objective) === 'stable')->count();
                        $attentionObjectivesForAnalytics = $allObjectivesForAnalytics->filter(fn ($objective) => $objectiveState($objective) === 'attention')->count();
                        $emptyObjectivesForAnalytics = $allObjectivesForAnalytics->filter(fn ($objective) => $objectiveState($objective) === 'empty')->count();
                        $objectivesWithQuestionsForAnalytics = $allObjectivesForAnalytics->filter(fn ($objective) => (int) ($objective['question_count'] ?? 0) > 0)->count();

                        $connectedQuestionsForAnalytics = max(0, (int) ($totals['questions'] ?? 0));
                        $allQuestionsForAnalytics = max($connectedQuestionsForAnalytics, (int) ($totals['all_questions'] ?? $connectedQuestionsForAnalytics));
                        $mappingCoverageForAnalytics = $allQuestionsForAnalytics > 0
                            ? round(($connectedQuestionsForAnalytics / $allQuestionsForAnalytics) * 100)
                            : 0;
                        $objectiveCoverageForAnalytics = $totalObjectivesForAnalytics > 0
                            ? round(($objectivesWithQuestionsForAnalytics / $totalObjectivesForAnalytics) * 100)
                            : 0;

                        $totalAnswersForAnalytics = (int) $allObjectivesForAnalytics->sum(fn ($objective) => (int) ($objective['total_answers'] ?? 0));
                        $correctAnswersForAnalytics = (int) $allObjectivesForAnalytics->sum(fn ($objective) => (int) ($objective['correct_count'] ?? 0));
                        $masteryFromAnswersForAnalytics = $totalAnswersForAnalytics > 0
                            ? round(($correctAnswersForAnalytics / $totalAnswersForAnalytics) * 100)
                            : 0;
                        $minimumMasteryForAnalytics = (int) ($totals['minimum_mastery_percent'] ?? 70);
                        $minimumQuestionsForAnalytics = (int) ($totals['minimum_questions_per_outcome'] ?? 2);
                        $reviewObjectivesForAnalytics = $attentionObjectivesForAnalytics + $emptyObjectivesForAnalytics;
                        $learningConclusionForAnalytics = $reviewObjectivesForAnalytics > 0
                            ? number_format($reviewObjectivesForAnalytics) . ' TP masih perlu ditinjau agar pemetaan soal dan hasil belajar lebih seimbang.'
                            : 'Tujuan Pembelajaran terlihat cukup rapi. Seluruh TP utama sudah berada pada status aman berdasarkan data saat ini.';

                        $analyticsTitle = 'Ringkasan Capaian TP';
                        $analyticsSubtitle = 'Ikhtisar umum kelengkapan soal, capaian jawaban, dan TP yang perlu ditinjau.';
                        $analyticsItems = [
                            ['label' => 'Bab', 'value' => number_format($totals['chapters'] ?? 0), 'hint' => 'Jumlah bab yang memiliki tujuan pembelajaran.', 'tone' => 'indigo'],
                            ['label' => 'Tujuan Pembelajaran', 'value' => number_format($totalObjectivesForAnalytics), 'hint' => number_format($objectivesWithQuestionsForAnalytics) . ' TP sudah memiliki soal pendukung.', 'tone' => 'cyan'],
                            ['label' => 'Soal Terhubung', 'value' => number_format($connectedQuestionsForAnalytics), 'hint' => $mappingCoverageForAnalytics . '% dari seluruh soal sudah dipetakan ke TP.', 'tone' => 'emerald'],
                            ['label' => 'Perlu Tinjau', 'value' => number_format($reviewObjectivesForAnalytics), 'hint' => number_format($attentionObjectivesForAnalytics) . ' perlu dicek dan ' . number_format($emptyObjectivesForAnalytics) . ' belum memiliki soal.', 'tone' => $reviewObjectivesForAnalytics > 0 ? 'rose' : 'emerald'],
                        ];
                        $analyticsActions = [];
                    @endphp

                    <div id="tp-analytics-strip" class="tp-entry mb-5" data-entry-order="1">
                        @include('admin.partials.compact_analytics_strip')
                    </div>

                    <section class="tp-learning-board tp-entry mb-5" data-entry-order="2" aria-labelledby="tp-learning-board-title">
                        <div class="tp-learning-board-head">
                            <div>
                                <p class="tp-board-kicker">Ringkasan umum</p>
                                <h3 id="tp-learning-board-title" class="tp-board-title">Capaian TP secara keseluruhan</h3>
                                <p class="tp-board-caption">Menampilkan kondisi umum TP berdasarkan kelengkapan soal, jawaban siswa, dan kebutuhan peninjauan.</p>
                            </div>
                            <button type="button" data-tp-metric-modal-open class="tp-basis-button" aria-haspopup="dialog">
                                Dasar hitung
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M12 18a6 6 0 100-12 6 6 0 000 12z"/></svg>
                            </button>
                        </div>
                        <div class="tp-learning-board-grid">
                            <article class="tp-board-item">
                                <div class="tp-board-label">TP memiliki soal</div>
                                <div class="tp-board-figure">
                                    <div class="tp-radial-progress" style="--tp-progress: {{ min(100, max(0, $objectiveCoverageForAnalytics)) }}%;"><span>{{ $objectiveCoverageForAnalytics }}%</span></div>
                                    <div>
                                        <strong>{{ number_format($objectivesWithQuestionsForAnalytics) }} / {{ number_format($totalObjectivesForAnalytics) }}</strong>
                                        <p>TP sudah memiliki soal pendukung</p>
                                    </div>
                                </div>
                            </article>

                            <article class="tp-board-item">
                                <div class="tp-board-label">Capaian jawaban siswa</div>
                                <div class="tp-bar-row">
                                    <div class="tp-bar-label"><span>Jawaban benar</span><b>{{ number_format($correctAnswersForAnalytics) }} / {{ number_format($totalAnswersForAnalytics) }}</b></div>
                                    <div class="tp-progress-track"><span class="tp-cyan-bar" style="width: {{ min(100, max(0, $masteryFromAnswersForAnalytics)) }}%"></span></div>
                                </div>
                                <div class="tp-board-figure">
                                    <div>
                                        <strong>{{ $masteryFromAnswersForAnalytics }}%</strong>
                                        <p>rata-rata capaian dari jawaban yang tercatat</p>
                                    </div>
                                </div>
                            </article>

                            <article class="tp-board-item">
                                <div class="tp-board-label">TP perlu ditinjau</div>
                                <div class="tp-status-stack" aria-label="Komposisi status tujuan pembelajaran">
                                    @if($totalObjectivesForAnalytics > 0)
                                        <span class="tp-status-stable" style="width: {{ round(($stableObjectivesForAnalytics / $totalObjectivesForAnalytics) * 100, 2) }}%"></span>
                                        <span class="tp-status-attention" style="width: {{ round(($attentionObjectivesForAnalytics / $totalObjectivesForAnalytics) * 100, 2) }}%"></span>
                                        <span class="tp-status-empty" style="width: {{ round(($emptyObjectivesForAnalytics / $totalObjectivesForAnalytics) * 100, 2) }}%"></span>
                                    @endif
                                </div>
                                <div class="tp-status-legend">
                                    <span><i class="tp-status-stable"></i>{{ number_format($stableObjectivesForAnalytics) }} aman</span>
                                    <span><i class="tp-status-attention"></i>{{ number_format($attentionObjectivesForAnalytics) }} cek ulang</span>
                                    <span><i class="tp-status-empty"></i>{{ number_format($emptyObjectivesForAnalytics) }} tanpa soal</span>
                                </div>
                                <div class="tp-board-figure">
                                    <div>
                                        <strong>{{ number_format($reviewObjectivesForAnalytics) }}</strong>
                                        <p>TP membutuhkan perhatian lanjutan</p>
                                    </div>
                                </div>
                            </article>
                        </div>
                        <div class="mt-4 rounded-2xl border border-slate-200/70 bg-white/65 px-4 py-3 text-sm font-semibold leading-6 text-slate-600 dark:border-white/10 dark:bg-white/[0.03] dark:text-slate-300">
                            <span class="font-black text-slate-900 dark:text-white">Kesimpulan umum:</span>
                            {{ $learningConclusionForAnalytics }}
                        </div>
                    </section>

                    <section class="hidden" aria-hidden="true">
                        <div class="glass-card panel chapter-summary rounded-2xl p-5 md:p-6">
                            <p class="text-[10px] font-black uppercase tracking-[0.22em] text-indigo-600 dark:text-indigo-400">Analitik Akademik</p>
                            <div class="mt-2 flex items-center gap-2">
                                <h1 class="text-2xl font-black tracking-tight text-slate-900 dark:text-white md:text-3xl">Tujuan Pembelajaran</h1>
                                <span class="tooltip-container tooltip-down">
                                    <button type="button" class="tooltip-trigger" aria-label="Panduan Analitik TP">?</button>
                                    <span class="tooltip-content" role="tooltip">Pilih bab, lalu gunakan filter status untuk melihat ringkasan TP prioritas.</span>
                                </span>
                            </div>
                            <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600 dark:text-slate-300">Pilih bab dan status untuk melihat TP yang paling perlu ditinjau.</p>
                        </div>

                        <div class="grid grid-cols-2 gap-3 sm:grid-cols-4 lg:w-[420px]">
                            <div class="glass-card panel metric-card metric-tone-indigo rounded-2xl px-4 py-4">
                                <p class="flex items-center gap-1 text-[9px] font-black uppercase tracking-widest text-slate-400">Bab
                                    <span class="tooltip-container tooltip-down"><button type="button" class="tooltip-trigger" aria-label="Panduan jumlah bab">?</button><span class="tooltip-content" role="tooltip">Jumlah bab yang tersedia pada pemetaan analitik.</span></span>
                                </p>
                                <p class="mt-1 text-2xl font-black text-slate-900 dark:text-white">{{ $totals['chapters'] }}</p>
                            </div>
                            <div class="glass-card panel metric-card metric-tone-cyan rounded-2xl px-4 py-4">
                                <p class="flex items-center gap-1 text-[9px] font-black uppercase tracking-widest text-slate-400">TP
                                    <span class="tooltip-container tooltip-down"><button type="button" class="tooltip-trigger" aria-label="Panduan tujuan pembelajaran">?</button><span class="tooltip-content" role="tooltip">Jumlah tujuan pembelajaran yang dipetakan dalam seluruh bab.</span></span>
                                </p>
                                <p class="mt-1 text-2xl font-black text-slate-900 dark:text-white">{{ $totals['objectives'] }}</p>
                            </div>
                            <div class="glass-card panel metric-card metric-tone-emerald rounded-2xl px-4 py-4">
                                <p class="flex items-center gap-1 text-[9px] font-black uppercase tracking-widest text-slate-400">Soal
                                    <span class="tooltip-container tooltip-down"><button type="button" class="tooltip-trigger" aria-label="Panduan jumlah soal">?</button><span class="tooltip-content" role="tooltip">Jumlah soal yang sudah terhubung dengan tujuan pembelajaran.</span></span>
                                </p>
                                <p class="mt-1 text-2xl font-black text-slate-900 dark:text-white">{{ $totals['questions'] }}</p>
                            </div>
                            <div class="glass-card panel metric-card metric-tone-rose rounded-2xl px-4 py-4">
                                <p class="flex items-center gap-1 text-[9px] font-black uppercase tracking-widest text-slate-400">Perlu di cek
                                    <span class="tooltip-container tooltip-right tooltip-down"><button type="button" class="tooltip-trigger" aria-label="Panduan status perlu cek">?</button><span class="tooltip-content" role="tooltip">Jumlah TP yang memerlukan peninjauan berdasarkan kelengkapan soal atau capaian jawaban.</span></span>
                                </p>
                                <p class="mt-1 text-2xl font-black text-amber-600 dark:text-amber-300">{{ $totals['attention'] }}</p>
                            </div>
                        </div>
                    </section>

                    <section class="glass-card panel analytics-toolbar tp-entry mb-5 rounded-2xl p-4 md:p-5" data-entry-order="3">
                        <div class="grid gap-4 xl:grid-cols-[minmax(240px,.72fr),minmax(0,1fr)] xl:items-end">
                            <label class="block">
                                <span class="mb-2 block text-[10px] font-black uppercase tracking-[.18em] text-slate-400">Pilih Bab</span>
                                <div class="relative">
                                    <select id="chapter-select" class="chapter-select rounded-xl px-4 py-3 pr-10 text-sm font-bold" aria-label="Pilih bab">
                                        @foreach($chapters as $chapter)
                                            <option value="chapter-{{ $chapter['id'] }}">{{ $chapter['label'] }} — {{ $chapter['title'] }}</option>
                                        @endforeach
                                    </select>
                                    <svg class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/></svg>
                                </div>
                            </label>

                            <div>
                                <p class="mb-2 flex items-center gap-1 text-[10px] font-black uppercase tracking-[.18em] text-slate-400">Status Tujuan Pembelajaran
                                    <span class="tooltip-container tooltip-down"><button type="button" class="tooltip-trigger" aria-label="Panduan filter status tujuan pembelajaran">?</button><span class="tooltip-content" role="tooltip">Filter ini menyaring TP prioritas yang tampil pada bab terpilih.</span></span>
                                </p>
                                <div class="flex flex-wrap gap-2">
                                    <button type="button" data-status-filter="all" class="status-filter rounded-xl border px-4 py-2.5 text-xs font-black transition">Semua</button>
                                    <button type="button" data-status-filter="attention" class="status-filter rounded-xl border px-4 py-2.5 text-xs font-black transition">Perlu di Cek</button>
                                    <button type="button" data-status-filter="empty" class="status-filter rounded-xl border px-4 py-2.5 text-xs font-black transition">Belum ada soal</button>
                                    <button type="button" data-status-filter="stable" class="status-filter rounded-xl border px-4 py-2.5 text-xs font-black transition">Tercukupi</button>
                                </div>
                            </div>
                        </div>
                    </section>

                    <div class="space-y-5">
                        @foreach($chapters as $chapter)
                            @php
                                $chapterObjectivesForAnalytics = collect($chapter['objectives'] ?? []);
                                $chapterObjectiveTotal = $chapterObjectivesForAnalytics->count();
                                $chapterState = function ($objective) use ($objectiveState) {
                                    return $objectiveState($objective);
                                };
                                $chapterStableCount = $chapterObjectivesForAnalytics->filter(fn ($objective) => $chapterState($objective) === 'stable')->count();
                                $chapterAttentionCount = $chapterObjectivesForAnalytics->filter(fn ($objective) => $chapterState($objective) === 'attention')->count();
                                $chapterEmptyCount = $chapterObjectivesForAnalytics->filter(fn ($objective) => $chapterState($objective) === 'empty')->count();
                                $chapterObjectivesWithQuestions = $chapterObjectivesForAnalytics->filter(fn ($objective) => (int) ($objective['question_count'] ?? 0) > 0)->count();
                                $chapterQuestionCount = (int) $chapterObjectivesForAnalytics->sum(fn ($objective) => (int) ($objective['question_count'] ?? 0));
                                $chapterAnswerCount = (int) $chapterObjectivesForAnalytics->sum(fn ($objective) => (int) ($objective['total_answers'] ?? 0));
                                $chapterCorrectCount = (int) $chapterObjectivesForAnalytics->sum(fn ($objective) => (int) ($objective['correct_count'] ?? 0));
                                $chapterMasteryFromAnswers = $chapterAnswerCount > 0 ? round(($chapterCorrectCount / $chapterAnswerCount) * 100) : 0;
                                $chapterCoverage = $chapterObjectiveTotal > 0 ? round(($chapterObjectivesWithQuestions / $chapterObjectiveTotal) * 100) : 0;
                                $chapterAverageQuestions = $chapterObjectiveTotal > 0 ? round($chapterQuestionCount / $chapterObjectiveTotal, 1) : 0;
                            @endphp
                            <section data-chapter-panel="chapter-{{ $chapter['id'] }}" class="glass-card panel chapter-panel rounded-2xl p-5 md:p-6" style="display: none;">
                                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                                    <div class="min-w-0">
                                        <p class="text-[10px] font-black uppercase tracking-[0.22em] text-indigo-600 dark:text-indigo-400">{{ $chapter['label'] }}</p>
                                        <h3 class="mt-1 text-xl font-black tracking-tight text-slate-900 dark:text-white md:text-2xl">{{ $chapter['title'] }}</h3>
                                        <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-600 dark:text-slate-300">{{ $chapter['description'] }}</p>
                                    </div>
                                </div>

                                <div class="mt-5 grid grid-cols-2 gap-2.5 sm:grid-cols-4">
                                    <div class="metric-card rounded-xl px-3 py-3">
                                        <p class="flex items-center gap-1 text-[9px] font-black uppercase tracking-widest text-slate-400">Capaian jawaban
                                            <span class="tooltip-container tooltip-down"><button type="button" class="tooltip-trigger" aria-label="Dasar hitung capaian bab">?</button><span class="tooltip-content" role="tooltip">Capaian bab = jawaban benar dibagi seluruh jawaban pada soal yang terhubung ke TP di bab ini.</span></span>
                                        </p>
                                        <p class="mt-1 text-xl font-black text-slate-900 dark:text-white">{{ $chapterMasteryFromAnswers }}%</p>
                                        <p class="mt-1 text-[10px] font-bold text-slate-500 dark:text-slate-400">{{ number_format($chapterCorrectCount) }} / {{ number_format($chapterAnswerCount) }} benar</p>
                                    </div>
                                    <div class="metric-card rounded-xl px-3 py-3">
                                        <p class="flex items-center gap-1 text-[9px] font-black uppercase tracking-widest text-slate-400">TP memiliki soal
                                            <span class="tooltip-container tooltip-down"><button type="button" class="tooltip-trigger" aria-label="Dasar hitung cakupan TP bab">?</button><span class="tooltip-content" role="tooltip">TP memiliki soal = jumlah TP dengan sedikitnya satu soal dibagi seluruh TP pada bab ini.</span></span>
                                        </p>
                                        <p class="mt-1 text-xl font-black text-slate-900 dark:text-white">{{ number_format($chapterObjectivesWithQuestions) }} / {{ number_format($chapterObjectiveTotal) }}</p>
                                        <p class="mt-1 text-[10px] font-bold text-slate-500 dark:text-slate-400">{{ $chapterCoverage }}% cakupan TP</p>
                                    </div>
                                    <div class="metric-card rounded-xl px-3 py-3">
                                        <p class="text-[9px] font-black uppercase tracking-widest text-slate-400">Status perlu cek</p>
                                        <p class="mt-1 text-xl font-black text-amber-600 dark:text-amber-300">{{ number_format($chapterAttentionCount + $chapterEmptyCount) }} <span class="text-sm text-slate-400">TP</span></p>
                                        <p class="mt-1 text-[10px] font-bold text-slate-500 dark:text-slate-400">{{ number_format($chapterAttentionCount) }} cek · {{ number_format($chapterEmptyCount) }} tanpa soal</p>
                                    </div>
                                    <div class="metric-card rounded-xl px-3 py-3">
                                        <p class="text-[9px] font-black uppercase tracking-widest text-slate-400">Soal terhubung</p>
                                        <p class="mt-1 text-xl font-black text-slate-900 dark:text-white">{{ number_format($chapterQuestionCount) }} <span class="text-sm text-slate-400">soal</span></p>
                                        <p class="mt-1 text-[10px] font-bold text-slate-500 dark:text-slate-400">Rata-rata {{ number_format($chapterAverageQuestions, 1) }} soal / TP</p>
                                    </div>
                                </div>

                                <div class="mt-4 grid gap-3 lg:grid-cols-2">
                                    <article class="rounded-xl border border-slate-200 bg-white/60 p-4 dark:border-white/10 dark:bg-white/[0.025]">
                                        <div class="flex items-center justify-between gap-3">
                                            <p class="text-[9px] font-black uppercase tracking-widest text-slate-400">Komposisi status TP</p>
                                            <span class="tooltip-container tooltip-right tooltip-down"><button type="button" class="tooltip-trigger" aria-label="Keterangan komposisi status TP bab">?</button><span class="tooltip-content" role="tooltip">Batang membandingkan TP tercukupi, perlu dicek, dan belum memiliki soal pada bab yang dipilih.</span></span>
                                        </div>
                                        <div class="tp-status-stack" aria-label="Komposisi status TP pada {{ $chapter['label'] }}">
                                            @if($chapterObjectiveTotal > 0)
                                                <span class="tp-status-stable" style="width: {{ round(($chapterStableCount / $chapterObjectiveTotal) * 100, 2) }}%"></span>
                                                <span class="tp-status-attention" style="width: {{ round(($chapterAttentionCount / $chapterObjectiveTotal) * 100, 2) }}%"></span>
                                                <span class="tp-status-empty" style="width: {{ round(($chapterEmptyCount / $chapterObjectiveTotal) * 100, 2) }}%"></span>
                                            @endif
                                        </div>
                                        <div class="tp-status-legend">
                                            <span><i class="tp-status-stable"></i>{{ number_format($chapterStableCount) }} tercukupi</span>
                                            <span><i class="tp-status-attention"></i>{{ number_format($chapterAttentionCount) }} perlu cek</span>
                                            <span><i class="tp-status-empty"></i>{{ number_format($chapterEmptyCount) }} tanpa soal</span>
                                        </div>
                                    </article>
                                    <article class="rounded-xl border border-slate-200 bg-white/60 p-4 dark:border-white/10 dark:bg-white/[0.025]">
                                        <p class="text-[9px] font-black uppercase tracking-widest text-slate-400">Keterhubungan dan capaian</p>
                                        <div class="tp-bar-row">
                                            <div class="tp-bar-label"><span>TP memiliki soal</span><b>{{ number_format($chapterObjectivesWithQuestions) }} / {{ number_format($chapterObjectiveTotal) }}</b></div>
                                            <div class="tp-progress-track"><span class="tp-indigo-bar" style="width: {{ min(100, max(0, $chapterCoverage)) }}%"></span></div>
                                        </div>
                                        <div class="tp-bar-row">
                                            <div class="tp-bar-label"><span>Jawaban benar</span><b>{{ number_format($chapterCorrectCount) }} / {{ number_format($chapterAnswerCount) }}</b></div>
                                            <div class="tp-progress-track"><span class="tp-cyan-bar" style="width: {{ min(100, max(0, $chapterMasteryFromAnswers)) }}%"></span></div>
                                        </div>
                                    </article>
                                </div>

                                @php
                                    $chapterObjectivePreview = collect($chapter['objectives'])
                                        ->sortBy(function ($objective) {
                                            $questionCount = (int) ($objective['question_count'] ?? 0);
                                            $needsQuestions = (bool) ($objective['needs_questions'] ?? false) || $questionCount === 0;
                                            $needsAttention = (bool) ($objective['needs_attention'] ?? false) || in_array(($objective['status_key'] ?? ''), ['attention', 'waiting'], true);
                                            $stateKey = $needsQuestions ? 'empty' : ($needsAttention ? 'attention' : 'stable');

                                            return match ($stateKey) {
                                                'empty' => 0,
                                                'attention' => 1,
                                                default => 2,
                                            };
                                        })
                                        ->take(4)
                                        ->values();
                                @endphp

                                <div class="mt-5 flex flex-col gap-2 border-t border-slate-200 pt-4 dark:border-white/10 sm:flex-row sm:items-center sm:justify-between">

                                    <span class="rounded-lg border border-slate-200 bg-white/70 px-3 py-1.5 text-[10px] font-black uppercase tracking-widest text-slate-500 dark:border-white/10 dark:bg-black/10 dark:text-slate-300">
                                        {{ $chapterObjectivePreview->count() }} dari {{ count($chapter['objectives']) }} TP
                                    </span>
                                </div>

                                <div class="objective-grid mt-3 grid gap-3 lg:grid-cols-2">
                                    @foreach($chapterObjectivePreview as $objective)
                                        @php
                                            $questionCount = (int) ($objective['question_count'] ?? 0);
                                            $answerCount = (int) ($objective['total_answers'] ?? 0);
                                            $correctCount = (int) ($objective['correct_count'] ?? 0);
                                            $wrongCount = (int) ($objective['wrong_count'] ?? 0);
                                            $studentCount = (int) ($objective['student_count'] ?? 0);
                                            $minimumQuestionCount = (int) ($objective['minimum_question_count'] ?? ($totals['minimum_questions_per_outcome'] ?? 2));
                                            $stateKey = $objective['status_key'] ?? ($questionCount === 0 ? 'empty' : 'stable');
                                            $stateGroup = $objective['status_group'] ?? ($stateKey === 'empty' ? 'empty' : ($stateKey === 'stable' ? 'stable' : 'attention'));
                                            $stateLabel = $objective['status_label'] ?? ($stateGroup === 'empty' ? 'Belum ada soal' : ($stateGroup === 'attention' ? 'Perlu di cek' : 'Tercukupi'));
                                            $stateDescription = $objective['status_reason'] ?? 'Data TP dihitung dari soal dan jawaban siswa yang sudah tercatat.';
                                            // Ketepatan jawaban ditampilkan langsung dari respons benar ÷ seluruh respons.
                                            $masteryPercent = $answerCount > 0 ? (int) round(($correctCount / $answerCount) * 100) : 0;
                                            $questionCoveragePercent = $minimumQuestionCount > 0 ? min(100, round(($questionCount / $minimumQuestionCount) * 100)) : 100;
                                            $answerBasisText = $answerCount > 0
                                                ? number_format($correctCount) . ' benar dari ' . number_format($answerCount) . ' jawaban'
                                                : 'Belum ada jawaban tercatat';
                                            $questionBasisText = number_format($questionCount) . ' dari minimal ' . number_format($minimumQuestionCount) . ' soal';
                                            $bar = $stateGroup === 'empty' ? 'bg-rose-500' : ($stateGroup === 'attention' ? 'bg-amber-500' : 'bg-emerald-500');
                                            $scoreClass = $stateGroup === 'empty' ? 'text-rose-700 dark:text-rose-200' : ($stateGroup === 'attention' ? 'text-amber-700 dark:text-amber-200' : 'text-emerald-700 dark:text-emerald-300');
                                            $badgeClass = $stateGroup === 'empty'
                                                ? 'border-rose-200 bg-rose-50 text-rose-700 dark:border-rose-500/20 dark:bg-rose-500/10 dark:text-rose-200'
                                                : ($stateGroup === 'attention'
                                                    ? 'border-amber-200 bg-amber-50 text-amber-700 dark:border-amber-500/20 dark:bg-amber-500/10 dark:text-amber-200'
                                                    : 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-200');
                                        @endphp
                                        <article data-objective-status="{{ $stateGroup }}" data-objective-status-key="{{ $stateKey }}" class="glass-card objective-card is-{{ $stateGroup }} rounded-2xl border p-4">
                                            <div class="flex items-start justify-between gap-3">
                                                <div class="min-w-0">
                                                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">{{ $objective['display_code'] }}</p>
                                                    <h4 class="mt-1 text-sm font-black leading-snug text-slate-900 dark:text-white">{{ $objective['title'] }}</h4>
                                                </div>
                                                <div class="shrink-0 text-right">
                                                    <p class="text-[8px] font-black uppercase tracking-widest text-slate-400">Ketepatan jawaban</p>
                                                    <span class="text-xl font-black {{ $scoreClass }}">{{ $masteryPercent }}%</span>
                                                    <p class="mt-0.5 text-[9px] font-bold text-slate-400">{{ number_format($correctCount) }}/{{ number_format($answerCount) }} respons</p>
                                                </div>
                                            </div>

                                            <div class="mt-3 flex flex-wrap items-center gap-2">
                                                <span class="rounded-lg border {{ $badgeClass }} px-2.5 py-1 text-[10px] font-black uppercase tracking-widest">{{ $stateLabel }}</span>
                                                <span class="rounded-lg border border-slate-200 bg-white/70 px-2.5 py-1 text-[10px] font-black uppercase tracking-widest text-slate-500 dark:border-white/10 dark:bg-black/10 dark:text-slate-300">{{ number_format($questionCount) }} soal terkait</span>
                                                <span class="tooltip-container tooltip-right tooltip-down">
                                                    <button type="button" class="tooltip-trigger" aria-label="Dasar data soal {{ $objective['display_code'] }}">?</button>
                                                    <span class="tooltip-content" role="tooltip">
                                                        <span class="tp-insight-tooltip">
                                                            <span class="tp-insight-kicker">{{ $objective['display_code'] }} · dasar data</span>
                                                            <span class="tp-insight-title">{{ $objective['title'] }}</span>
                                                            <span class="tp-insight-grid">
                                                                <span class="tp-insight-metric"><b>{{ number_format($questionCount) }}</b><span>Soal terkait</span></span>
                                                                <span class="tp-insight-metric"><b>{{ number_format($answerCount) }}</b><span>Respons</span></span>
                                                                <span class="tp-insight-metric"><b>{{ number_format($correctCount) }}</b><span>Benar</span></span>
                                                                <span class="tp-insight-metric"><b>{{ number_format($wrongCount) }}</b><span>Belum tepat</span></span>
                                                            </span>
                                                            <span class="tp-insight-note">Ketepatan jawaban = respons benar ÷ seluruh respons pada soal yang terhubung ke TP ini. Batas capaian: {{ $minimumMasteryForAnalytics }}%.</span>
                                                            <span class="tp-insight-source">Respons diambil dari jawaban terakhir setiap siswa pada evaluasi yang telah selesai. {{ number_format($studentCount) }} siswa memiliki respons pada TP ini.</span>
                                                        </span>
                                                    </span>
                                                </span>
                                            </div>

                                            <section class="tp-objective-question-data" aria-label="Data soal {{ $objective['display_code'] }}">
                                                <div class="tp-objective-question-data-head">
                                                    <div>
                                                        <p>Data soal pada TP ini</p>
                                                        <small>Jumlah soal yang mengukur TP serta hasil respons siswa.</small>
                                                    </div>
                                                    <span class="tp-objective-question-total">{{ number_format($questionCount) }} soal</span>
                                                </div>

                                                <div class="tp-objective-metric-grid">
                                                    <div class="tp-objective-metric">
                                                        <span>Soal terhubung</span>
                                                        <b>{{ number_format($questionCount) }} soal</b>
                                                        <small>minimal {{ number_format($minimumQuestionCount) }} soal</small>
                                                    </div>
                                                    <div class="tp-objective-metric">
                                                        <span>Respons jawaban</span>
                                                        <b>{{ number_format($answerCount) }}</b>
                                                        <small>{{ number_format($studentCount) }} siswa menjawab</small>
                                                    </div>
                                                    <div class="tp-objective-metric">
                                                        <span>Jawaban benar</span>
                                                        <b class="{{ $answerCount > 0 ? 'text-emerald-700 dark:text-emerald-300' : '' }}">{{ number_format($correctCount) }}</b>
                                                        <small>dari {{ number_format($answerCount) }} respons</small>
                                                    </div>
                                                    <div class="tp-objective-metric">
                                                        <span>Belum tepat</span>
                                                        <b class="{{ $answerCount > 0 && $wrongCount > 0 ? 'text-rose-700 dark:text-rose-300' : '' }}">{{ number_format($wrongCount) }}</b>
                                                        <small>dari {{ number_format($answerCount) }} respons</small>
                                                    </div>
                                                </div>
                                            </section>

                                            <div class="tp-objective-progress-wrap">
                                                <div class="tp-objective-progress-meta">
                                                    <span>Ketepatan: {{ number_format($correctCount) }} benar dari {{ number_format($answerCount) }} respons</span>
                                                    <span>Batas capaian {{ $minimumMasteryForAnalytics }}%</span>
                                                </div>
                                                <div class="tp-objective-progress" aria-label="Ketepatan jawaban {{ $objective['display_code'] }} {{ $masteryPercent }} persen dari {{ number_format($answerCount) }} respons">
                                                    <span class="{{ $bar }}" style="width: {{ min(100, max(0, $masteryPercent)) }}%"></span>
                                                    <i style="left: {{ min(100, max(0, $minimumMasteryForAnalytics)) }}%"></i>
                                                </div>
                                            </div>

                                            @if($questionCount === 0)
                                                <div class="mt-3 rounded-xl border border-dashed border-rose-300 bg-rose-50/70 px-3 py-3 text-xs font-bold text-rose-700 dark:border-rose-500/30 dark:bg-rose-500/10 dark:text-rose-200">
                                                    Belum ada soal yang terhubung ke {{ $objective['display_code'] }}.
                                                </div>
                                            @elseif($answerCount === 0)
                                                <div class="mt-3 rounded-xl border border-dashed border-slate-300 bg-slate-50/70 px-3 py-3 text-xs font-bold text-slate-600 dark:border-white/15 dark:bg-white/[0.035] dark:text-slate-300">
                                                    {{ number_format($questionCount) }} soal sudah terhubung, tetapi belum ada respons siswa yang tercatat.
                                                </div>
                                            @endif
                                        </article>
                                    @endforeach
                                    <div class="filter-empty hidden rounded-2xl border border-dashed border-slate-300 bg-slate-50/70 px-4 py-8 text-center text-sm font-semibold text-slate-500 dark:border-white/10 dark:bg-white/[0.025] dark:text-slate-400">
                                        Tidak ada TP prioritas untuk status yang dipilih pada bab ini.
                                    </div>
                                </div>
                            </section>
                        @endforeach
                    </div>
                </div>
            </div>
        </main>
    </div>

    <div id="tp-metric-modal" class="fixed inset-0 z-[999999] hidden items-center justify-center p-4 sm:p-6" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="tp-metric-modal-title">
        <button type="button" class="absolute inset-0 bg-slate-900/55 backdrop-blur-md dark:bg-[#020617]/80" data-tp-metric-modal-close aria-label="Tutup dasar hitung analitik"></button>
        <section class="tp-metric-modal-card relative max-h-[90vh] w-full max-w-4xl overflow-y-auto rounded-[1.65rem] border border-slate-200 shadow-2xl custom-scrollbar dark:border-white/10">
            <div class="tp-metric-modal-hero px-5 py-5 sm:px-7 sm:py-6">
                <button type="button" data-tp-metric-modal-close class="absolute right-4 top-4 z-10 rounded-full p-2 text-slate-400 transition hover:bg-white/70 hover:text-slate-700 dark:hover:bg-white/10 dark:hover:text-white" aria-label="Tutup dasar hitung analitik">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 18 6M6 6l12 12"/></svg>
                </button>
                <div class="relative z-10 pr-10">
                    <p class="text-[10px] font-black uppercase tracking-[.18em] text-indigo-600 dark:text-indigo-300">Dasar hitung analitik</p>
                    <h2 id="tp-metric-modal-title" class="mt-1 text-xl font-black tracking-tight text-slate-950 dark:text-white sm:text-2xl">Sumber angka pada Tujuan Pembelajaran</h2>
                    <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600 dark:text-slate-300">Setiap kartu menggunakan pemetaan soal ke TP dan jawaban terakhir siswa dari kuis yang telah selesai.</p>
                </div>
            </div>
            <div class="p-5 sm:p-7">
                <div class="tp-metric-modal-grid">
                    <article class="tp-metric-modal-item">
                        <p>Cakupan soal</p>
                        <strong>{{ number_format($connectedQuestionsForAnalytics) }} / {{ number_format($allQuestionsForAnalytics) }} soal · {{ $mappingCoverageForAnalytics }}%</strong>
                        <span>Soal terhubung ke TP ÷ seluruh soal kuis.</span>
                    </article>
                    <article class="tp-metric-modal-item">
                        <p>Capaian jawaban</p>
                        <strong>{{ number_format($correctAnswersForAnalytics) }} / {{ number_format($totalAnswersForAnalytics) }} jawaban · {{ $masteryFromAnswersForAnalytics }}%</strong>
                        <span>Jawaban benar ÷ seluruh jawaban terakhir pada soal yang memiliki TP.</span>
                    </article>
                    <article class="tp-metric-modal-item">
                        <p>TP memiliki soal</p>
                        <strong>{{ number_format($objectivesWithQuestionsForAnalytics) }} / {{ number_format($totalObjectivesForAnalytics) }} TP · {{ $objectiveCoverageForAnalytics }}%</strong>
                        <span>TP dengan sedikitnya satu soal ÷ seluruh TP yang dipetakan.</span>
                    </article>
                    <article class="tp-metric-modal-item">
                        <p>Status TP</p>
                        <strong>{{ number_format($stableObjectivesForAnalytics) }} tercukupi · {{ number_format($attentionObjectivesForAnalytics + $emptyObjectivesForAnalytics) }} perlu cek</strong>
                        <span>Minimal {{ $minimumQuestionsForAnalytics }} soal per TP dan ambang capaian {{ $minimumMasteryForAnalytics }}% digunakan sebagai acuan status.</span>
                    </article>
                </div>
                <div class="mt-5 rounded-xl border border-indigo-100 bg-indigo-50/70 px-4 py-3 text-xs font-semibold leading-5 text-indigo-900 dark:border-indigo-500/20 dark:bg-indigo-500/10 dark:text-indigo-100">
                    Catatan: satu siswa dapat menyumbang jawaban pada beberapa soal. Karena itu, angka jawaban menunjukkan rekaman respons, bukan jumlah siswa unik.
                </div>
            </div>
        </section>
    </div>


    <div id="learning-guide-modal" class="fixed inset-0 z-[999999] hidden items-center justify-center p-4 sm:p-6" aria-hidden="true">
        <button type="button" class="absolute inset-0 bg-slate-900/60 backdrop-blur-md dark:bg-[#020617]/80" data-learning-guide-close aria-label="Tutup panduan"></button>
        <section class="relative max-h-[92vh] w-full max-w-6xl overflow-y-auto rounded-[2rem] border border-slate-200 bg-white/95 p-6 shadow-2xl custom-scrollbar dark:border-white/10 dark:bg-[#0f141e]/95 sm:p-8">
            <button type="button" data-learning-guide-close class="absolute right-5 top-5 z-10 rounded-full p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-white/5 dark:hover:text-white" aria-label="Tutup panduan">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            @php
                $guideTitle = 'Panduan Tujuan Pembelajaran';
                $guideSubtitle = 'Membaca tujuan pembelajaran';
                $guideImage = 'images/guides/current-admin-learning-outcomes.png';
                $guideIntro = 'Gunakan nomor pada gambar untuk membaca ringkasan TP, filter bab, status prioritas, dan kartu tujuan pembelajaran yang perlu dicek.';
                $guidePoints = [
                    ['x' => 52, 'y' => 28, 'title' => 'Ringkasan TP', 'description' => 'Baca jumlah bab, TP, soal terhubung, dan TP yang membutuhkan peninjauan.'],
                    ['x' => 47, 'y' => 45, 'title' => 'Filter bab dan status', 'description' => 'Pilih bab serta status agar TP prioritas tidak bercampur dengan TP lain.'],
                    ['x' => 56, 'y' => 72, 'title' => 'Kartu tujuan', 'description' => 'Gunakan kartu ini untuk melihat materi, persentase penguasaan, dan kebutuhan soal.'],
                ];
            @endphp
            @include('admin.partials.analytics_guide_mockup')

            <div class="mt-8 border-t border-slate-200 pt-6 dark:border-white/5">
                <button type="button" data-learning-guide-close class="w-full rounded-xl bg-slate-900 py-3 text-sm font-bold text-white shadow-md transition hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-slate-200">Mengerti, Tutup Panduan</button>
            </div>
        </section>
    </div>


    <script>
        $(function () {
            const $root = $('html');
            const $body = $('body');
            const $sidebar = $('#app-sidebar');
            const $overlay = $('#sidebar-overlay');
            const $chapterSelect = $('#chapter-select');
            const $themeButton = $('#theme-toggle-sidebar');
            const $darkIcon = $('#theme-toggle-dark-icon-sidebar');
            const $lightIcon = $('#theme-toggle-light-icon-sidebar');
            const $themeLabel = $('#theme-toggle-text-sidebar');
            const $guideModal = $('#learning-guide-modal');
            const $metricModal = $('#tp-metric-modal');
            const selectedClass = 'is-active';

            let activeChapter = $chapterSelect.val() || $('.chapter-panel').first().data('chapter-panel');
            let activeStatus = 'all';

            function syncThemeControl() {
                const isDark = $root.hasClass('dark');
                $darkIcon.toggleClass('hidden', isDark);
                $lightIcon.toggleClass('hidden', !isDark);
                $themeLabel.text(isDark ? 'Mode Terang' : 'Mode Gelap');
            }

            function syncScrollLock() {
                const sidebarVisible = window.innerWidth < 768 && $sidebar.hasClass('translate-x-0');
                const guideVisible = !$guideModal.hasClass('hidden');
                const metricVisible = !$metricModal.hasClass('hidden');
                const shouldLock = sidebarVisible || guideVisible || metricVisible;

                $root.toggleClass('tp-scroll-locked', shouldLock);
                $body.toggleClass('overflow-hidden', shouldLock);
            }

            function openSidebar() {
                $sidebar.removeClass('-translate-x-full').addClass('translate-x-0');
                $overlay.stop(true, true).fadeIn(180);
                syncScrollLock();
            }

            function closeSidebar() {
                $sidebar.removeClass('translate-x-0').addClass('-translate-x-full');
                $overlay.stop(true, true).fadeOut(160);
                syncScrollLock();
            }

            function openGuide() {
                $guideModal.removeClass('hidden').addClass('flex').attr('aria-hidden', 'false');
                syncScrollLock();
            }

            function closeGuide() {
                $guideModal.addClass('hidden').removeClass('flex').attr('aria-hidden', 'true');
                syncScrollLock();
            }

            function openMetricModal() {
                $metricModal.removeClass('hidden').addClass('flex').attr('aria-hidden', 'false');
                syncScrollLock();
            }

            function closeMetricModal() {
                $metricModal.addClass('hidden').removeClass('flex').attr('aria-hidden', 'true');
                syncScrollLock();
            }

            function setStatusButtons() {
                $('[data-status-filter]').removeClass(selectedClass)
                    .attr('aria-pressed', 'false');
                $('[data-status-filter="' + activeStatus + '"]')
                    .addClass(selectedClass)
                    .attr('aria-pressed', 'true');
            }

            function forceMotionFrame($element) {
                const node = $element && $element.get ? $element.get(0) : null;
                if (node) void node.offsetWidth;
            }

            function applyStatusFilter($panel, animate) {
                const $cards = $panel.find('[data-objective-status]');
                const $empty = $panel.find('.filter-empty');
                let visible = 0;

                $cards.each(function () {
                    const $card = $(this);
                    const isMatch = activeStatus === 'all' || $card.data('objective-status') === activeStatus;

                    if (isMatch) {
                        visible += 1;
                        $card.stop(true, true).show();
                    } else {
                        $card.stop(true, true).hide();
                    }
                });

                if (visible === 0) {
                    $empty.stop(true, true).show();
                } else {
                    $empty.stop(true, true).hide();
                }

                // Saat filter berubah, kartu yang tersisa tampil ulang dengan transisi ringan.
                if (animate && visible > 0) {
                    window.requestAnimationFrame(function () {
                        revealObjectiveCards($panel, 0);
                    });
                }
            }

            function revealObjectiveCards($panel, delay) {
                if (!$panel.length) return;

                const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
                const startDelay = Number(delay || 0);
                const $cards = $panel.find('[data-objective-status]:visible');

                $cards.each(function (index) {
                    const $card = $(this);
                    $card.removeClass('tp-objective-enter is-visible');

                    if (reducedMotion) {
                        $card.addClass('is-visible');
                        return;
                    }

                    $card.addClass('tp-objective-enter');
                    forceMotionFrame($card);

                    window.setTimeout(function () {
                        $card.addClass('is-visible');

                        window.setTimeout(function () {
                            $card.removeClass('tp-objective-enter');
                        }, 420);
                    }, startDelay + (Math.min(index, 4) * 55));
                });
            }

            function revealChapterPanel($panel) {
                if (!$panel.length) return;

                const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
                $panel.removeClass('tp-panel-enter is-visible');

                if (reducedMotion) {
                    $panel.addClass('is-visible');
                    revealObjectiveCards($panel, 0);
                    return;
                }

                $panel.addClass('tp-panel-enter');
                forceMotionFrame($panel);
                $panel.addClass('is-visible');

                window.setTimeout(function () {
                    $panel.removeClass('tp-panel-enter');
                }, 420);

                revealObjectiveCards($panel, 90);
            }

            function showChapter(chapterKey, animate) {
                activeChapter = chapterKey;
                $chapterSelect.val(chapterKey);

                const $target = $('.chapter-panel[data-chapter-panel="' + chapterKey + '"]');
                const $visible = $('.chapter-panel:visible');

                if (!$target.length) return;

                if ($visible.is($target)) {
                    applyStatusFilter($target, animate);
                    return;
                }

                $visible.stop(true, true).hide();
                $target.show();
                applyStatusFilter($target, false);
                revealChapterPanel($target);
            }

            function toggleDetail($detail) {
                const $content = $detail.find('.details-content').first();
                const $toggle = $detail.find('.details-toggle').first();
                const isOpen = $detail.hasClass('is-open');

                if (isOpen) {
                    $content.stop(true, true).slideUp(180, function () {
                        $detail.removeClass('is-open');
                        $toggle.attr('aria-expanded', 'false');
                    });
                } else {
                    $detail.addClass('is-open');
                    $toggle.attr('aria-expanded', 'true');
                    $content.stop(true, true).slideDown(220);
                }
            }

            syncThemeControl();
            setStatusButtons();
            $('.details-content').hide();
            syncScrollLock();

            // Transisi awal mengikuti pola panel admin: fade-up singkat dan berurutan.
            function revealInitialEntries() {
                const $entries = $('.tp-entry');
                const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

                $entries.removeClass('is-visible');
                forceMotionFrame($entries.first());

                if (reducedMotion) {
                    $entries.addClass('is-visible');
                    showChapter(activeChapter, false);
                    return;
                }

                $entries.each(function (index) {
                    const $entry = $(this);
                    window.setTimeout(function () {
                        $entry.addClass('is-visible');
                    }, 70 + (index * 80));
                });

                window.setTimeout(function () {
                    showChapter(activeChapter, false);
                }, 320);
            }

            window.requestAnimationFrame(revealInitialEntries);

            $('#sidebar-open').on('click', openSidebar);
            $('#sidebar-close, #sidebar-overlay').on('click', closeSidebar);
            $('#learning-guide-open').on('click', openGuide);
            $('[data-learning-guide-close]').on('click', closeGuide);
            $('[data-tp-metric-modal-open]').on('click', openMetricModal);
            $('[data-tp-metric-modal-close]').on('click', closeMetricModal);

            $themeButton.on('click', function () {
                const enableDark = !$root.hasClass('dark');
                $root.addClass('theme-changing');
                $body.stop(true, true).animate({ opacity: 0.985 }, 75, function () {
                    $root.toggleClass('dark', enableDark);
                    localStorage.setItem('color-theme', enableDark ? 'dark' : 'light');
                    syncThemeControl();
                }).animate({ opacity: 1 }, 175);

                window.setTimeout(function () {
                    $root.removeClass('theme-changing');
                }, 320);
            });

            $chapterSelect.on('change', function () {
                showChapter($(this).val(), true);
            });

            $(document).on('click', '[data-status-filter]', function () {
                activeStatus = String($(this).data('status-filter'));
                setStatusButtons();
                applyStatusFilter($('.chapter-panel:visible'), true);
            });

            $(document).on('click', '.details-toggle', function () {
                toggleDetail($(this).closest('[data-detail]'));
            });

            // Tooltip portal: selalu berada di depan sidebar, card, dan panel rincian.
            const $globalTooltip = $('<div id="global-tooltip-layer" role="tooltip" aria-hidden="true"></div>').appendTo(document.body);
            let $activeTooltipTrigger = $();
            let tooltipPinned = false;
            let tooltipHideTimer = null;

            function hideGlobalTooltip(force) {
                if (!force && tooltipPinned) return;
                window.clearTimeout(tooltipHideTimer);
                $globalTooltip.removeClass('is-visible').attr('aria-hidden', 'true');
                $activeTooltipTrigger.attr('aria-expanded', 'false');
                $activeTooltipTrigger = $();
                tooltipPinned = false;
            }

            function placeGlobalTooltip($trigger) {
                if (!$trigger.length) return;

                const sourceHtml = $trigger.closest('.tooltip-container').find('.tooltip-content').first().html();
                if (!sourceHtml) return;

                window.clearTimeout(tooltipHideTimer);
                $activeTooltipTrigger = $trigger;
                $globalTooltip
                    .html(sourceHtml)
                    .attr('aria-hidden', 'false')
                    .addClass('is-visible')
                    .css({ left: '12px', top: '12px', '--tooltip-arrow-left': '50%' });

                const triggerRect = $trigger.get(0).getBoundingClientRect();
                const tooltipWidth = $globalTooltip.outerWidth();
                const tooltipHeight = $globalTooltip.outerHeight();
                const viewportWidth = window.innerWidth;
                const viewportHeight = window.innerHeight;
                const gap = 12;

                let placement = 'bottom';
                let top = triggerRect.bottom + gap;
                if (top + tooltipHeight > viewportHeight - gap && triggerRect.top - tooltipHeight - gap >= gap) {
                    placement = 'top';
                    top = triggerRect.top - tooltipHeight - gap;
                }

                const left = Math.max(
                    gap,
                    Math.min(
                        triggerRect.left + (triggerRect.width / 2) - (tooltipWidth / 2),
                        viewportWidth - tooltipWidth - gap
                    )
                );
                const arrowLeft = Math.max(
                    16,
                    Math.min(triggerRect.left + (triggerRect.width / 2) - left, tooltipWidth - 16)
                );

                $globalTooltip
                    .attr('data-placement', placement)
                    .css({
                        left: Math.round(left) + 'px',
                        top: Math.round(top) + 'px',
                        '--tooltip-arrow-left': Math.round(arrowLeft) + 'px'
                    });
            }

            function scheduleTooltipHide() {
                window.clearTimeout(tooltipHideTimer);
                tooltipHideTimer = window.setTimeout(function () {
                    hideGlobalTooltip(false);
                }, 120);
            }

            $(document).on('mouseenter focusin', '.tooltip-trigger', function () {
                tooltipPinned = false;
                placeGlobalTooltip($(this));
            });

            $(document).on('mouseleave focusout', '.tooltip-trigger', function () {
                scheduleTooltipHide();
            });

            $(document).on('click', '.tooltip-trigger', function (event) {
                event.preventDefault();
                event.stopPropagation();

                const $trigger = $(this);
                const isSame = $activeTooltipTrigger.length && $activeTooltipTrigger.is($trigger) && tooltipPinned;

                if (isSame) {
                    hideGlobalTooltip(true);
                    return;
                }

                tooltipPinned = true;
                $trigger.attr('aria-expanded', 'true');
                placeGlobalTooltip($trigger);
            });

            $(document).on('click', function () {
                hideGlobalTooltip(true);
            });

            $(document).on('keydown', function (event) {
                if (event.key === 'Escape') {
                    closeSidebar();
                    closeGuide();
                    closeMetricModal();
                    hideGlobalTooltip(true);
                }
            });

            function syncViewportState() {
                if ($activeTooltipTrigger.length) {
                    placeGlobalTooltip($activeTooltipTrigger);
                }
                if (window.innerWidth >= 768) {
                    $overlay.hide();
                    $sidebar.removeClass('translate-x-0').addClass('md:translate-x-0');
                }
                syncScrollLock();
            }

            $(window).on('resize', syncViewportState);
            $('#admin-main-content').on('scroll', function () {
                if ($activeTooltipTrigger.length) {
                    placeGlobalTooltip($activeTooltipTrigger);
                }
            });

        });
    </script>

</body>
</html>
