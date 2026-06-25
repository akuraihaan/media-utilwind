<!DOCTYPE html>
{{-- Analitik TP: adaptasi visual dan interaksi jQuery dari halaman Bank Soal. --}}
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analitik TP · Panel Admin Utilwind</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: { extend: { fontFamily: { sans: ['Inter', 'sans-serif'] } } }
        };

        // Membaca preferensi tema yang sudah disimpan oleh switcher sistem.
        const savedTheme = localStorage.getItem('color-theme');
        const useDarkTheme = savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches);
        document.documentElement.classList.toggle('dark', useDarkTheme);
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
    
        /* Adaptasi visual Bank Soal untuk Pemetaan TP. */
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
            max-width: min(18rem, calc(100vw - 1.5rem));
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

        .page-content {
            animation: bankSoalReveal .36s ease both;
        }
        @keyframes bankSoalReveal {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }

    </style>
</head>
<body class="app-background min-h-screen text-slate-900 antialiased dark:text-slate-100">
    <div id="sidebar-overlay" class="fixed inset-0 z-[90] hidden bg-slate-900/60 backdrop-blur-sm md:hidden"></div>

    <div class="pointer-events-none fixed inset-0 overflow-hidden" aria-hidden="true">
        <div class="absolute -right-32 -top-32 h-80 w-80 rounded-full bg-indigo-300/20 blur-3xl dark:bg-indigo-500/10"></div>
        <div class="absolute -bottom-40 left-[18%] h-72 w-72 rounded-full bg-cyan-200/20 blur-3xl dark:bg-cyan-500/10"></div>
    </div>

    <div class="relative z-10 flex min-h-screen">
        <aside id="app-sidebar" class="glass-sidebar fixed top-0 z-[100] flex h-screen w-72 shrink-0 -translate-x-full flex-col border-r transition-transform duration-300 md:sticky md:translate-x-0">
            <div class="h-24 flex items-center justify-between px-8 border-b border-slate-200 dark:border-white/5 relative overflow-hidden group transition-colors">
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-20 h-20 bg-indigo-200/50 dark:bg-indigo-500/20 rounded-full blur-[40px] opacity-0 group-hover:opacity-100 transition duration-500"></div>

                <a href="{{ route('landing') }}" class="flex items-center gap-3 relative z-10">
                    <img src="{{ asset('images/logo.png') }}" class="h-8 w-auto object-contain block dark:hidden" style="filter: brightness(0.1);" alt="Logo">
                    <img src="{{ asset('images/logo.png') }}" class="h-8 w-auto object-contain hidden dark:block drop-shadow-sm" alt="Logo Dark">
                    <div>
                        <h1 class="text-xl font-black text-slate-900 dark:text-white tracking-tight leading-none transition-colors">Util<span class="text-indigo-600 dark:text-indigo-400">wind</span></h1>
                        <span class="text-[9px] font-bold text-slate-500 dark:text-white/40 tracking-[0.2em] uppercase transition-colors">Panel Admin</span>
                    </div>
                </a>
                <button id="sidebar-close" type="button" class="md:hidden text-slate-500 dark:text-white/50 hover:text-slate-800 dark:hover:text-white relative z-10 transition-colors" aria-label="Tutup navigasi">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            @include('admin.partials.sidebar-nav')

            <div class="p-4 border-t border-slate-200 dark:border-white/5 bg-slate-50/50 dark:bg-[#05080f]/50 transition-colors">
                <div class="flex items-center gap-3 mb-4 px-2">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-fuchsia-500 flex items-center justify-center font-bold text-white text-xs shadow-lg">AD</div>
                    <div class="overflow-hidden">
                        <p class="text-xs font-bold text-slate-900 dark:text-white truncate transition-colors">{{ Auth::user()->name ?? 'Administrator' }}</p>
                        <p class="text-[10px] text-slate-500 dark:text-white/40 truncate transition-colors">Administrator Sistem</p>
                    </div>
                </div>

                <button id="theme-toggle-sidebar" type="button" class="w-full mb-2 flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg bg-slate-200/50 dark:bg-white/5 hover:bg-slate-200 dark:hover:bg-white/10 text-slate-700 dark:text-slate-300 transition-colors border border-transparent dark:border-transparent text-xs font-bold shadow-sm dark:shadow-none">
                    <svg id="theme-toggle-dark-icon-sidebar" class="hidden w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 0 1 6.707 2.707a8.001 8.001 0 1 0 10.586 10.586z"/></svg>
                    <svg id="theme-toggle-light-icon-sidebar" class="hidden w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a1 1 0 0 1 1 1v1a1 1 0 1 1-2 0V3a1 1 0 0 1 1-1zm4 8a4 4 0 1 1-8 0 4 4 0 0 1 8 0zm-.464 4.95.707.707a1 1 0 0 0 1.414-1.414l-.707-.707a1 1 0 0 0-1.414 1.414zm2.12-10.607a1 1 0 0 1 0 1.414l-.706.707a1 1 0 1 1-1.414-1.414l.707-.707a1 1 0 0 1 1.414 0zM17 11a1 1 0 1 0 0-2h-1a1 1 0 1 0 0 2h1zm-7 4a1 1 0 0 1 1 1v1a1 1 0 1 1-2 0v-1a1 1 0 0 1 1-1zM5.05 6.464A1 1 0 1 0 6.465 5.05l-.708-.707a1 1 0 0 0-1.414 1.414l.707.707zm1.414 8.486-.707.707a1 1 0 0 1-1.414-1.414l.707-.707a1 1 0 0 1 1.414 1.414zM4 11a1 1 0 1 0 0-2H3a1 1 0 0 0 0 2h1z" clip-rule="evenodd"/></svg>
                    <span id="theme-toggle-text-sidebar">Ubah Tema</span>
                </button>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-500 hover:text-red-700 dark:hover:text-white transition-colors text-xs font-bold border border-red-200 dark:border-red-500/20 hover:border-red-300 dark:hover:border-red-500 group shadow-sm dark:shadow-none">
                        <svg class="w-3.5 h-3.5 transition group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0-4-4m4 4H7m6 4v1a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1"/></svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 min-w-0">
            <header class="glass-header app-header sticky top-0 z-30 h-24 border-b px-6 md:px-10">
                <div class="flex h-full w-full items-center justify-between gap-4">
                    <div class="flex min-w-0 items-center gap-4">
                        <button id="sidebar-open" type="button" class="rounded-lg bg-slate-100 p-2 text-slate-700 transition hover:bg-slate-200 dark:bg-white/5 dark:text-white dark:hover:bg-white/10 md:hidden" aria-label="Buka navigasi">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        </button>

                        <div class="min-w-0">
                            <nav class="mb-1.5 hidden text-[10px] font-bold text-slate-500 transition-colors dark:text-white/50 sm:flex" aria-label="Breadcrumb">
                                <ol class="inline-flex items-center space-x-1">
                                    <li class="inline-flex items-center">
                                        <a href="{{ route('admin.analytics.questions') }}" class="transition-colors hover:text-indigo-600 dark:hover:text-indigo-400">Manajemen Kuis</a>
                                    </li>
                                    <li aria-hidden="true" class="flex items-center text-slate-300 dark:text-white/20">
                                        <svg class="mx-1 h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="m9 5 7 7-7 7"/></svg>
                                    </li>
                                    <li class="text-slate-700 dark:text-white">Pemetaan TP</li>
                                </ol>
                            </nav>
                            <h2 class="truncate text-lg font-bold tracking-tight text-slate-900 transition-colors dark:text-white md:text-xl">Pemetaan Tujuan Pembelajaran</h2>
                            <p class="mt-0.5 hidden text-xs text-slate-500 transition-colors dark:text-white/40 sm:block">Ringkasan prioritas TP, soal, dan capaian per bab.</p>
                        </div>
                    </div>

                    <a href="{{ route('admin.analytics.questions') }}" class="header-action hidden shrink-0 items-center gap-2 rounded-xl px-4 py-2.5 text-xs font-bold sm:inline-flex" title="Kembali ke Bank Soal">
                        Bank Soal
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" d="m9 5 7 7-7 7"/></svg>
                    </a>
                </div>
            </header>

            <div class="page-reveal page-content px-5 py-6 md:px-8 md:py-8">
                <div class="mx-auto max-w-7xl">
                    <section class="mb-5 grid gap-4 lg:grid-cols-[minmax(0,1fr),auto]">
                        <div class="glass-card panel chapter-summary rounded-2xl p-5 md:p-6">
                            <p class="text-[10px] font-black uppercase tracking-[0.22em] text-indigo-600 dark:text-indigo-400">Analitik Akademik</p>
                            <div class="mt-2 flex items-center gap-2">
                                <h1 class="text-2xl font-black tracking-tight text-slate-900 dark:text-white md:text-3xl">Pemetaan Tujuan Pembelajaran</h1>
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
                                <p class="flex items-center gap-1 text-[9px] font-black uppercase tracking-widest text-slate-400">Perlu cek
                                    <span class="tooltip-container tooltip-right tooltip-down"><button type="button" class="tooltip-trigger" aria-label="Panduan status perlu cek">?</button><span class="tooltip-content" role="tooltip">Jumlah TP yang memerlukan peninjauan berdasarkan kelengkapan soal atau capaian jawaban.</span></span>
                                </p>
                                <p class="mt-1 text-2xl font-black text-amber-600 dark:text-amber-300">{{ $totals['attention'] }}</p>
                            </div>
                        </div>
                    </section>

                    <section class="glass-card panel analytics-toolbar mb-5 rounded-2xl p-4 md:p-5">
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
                                <p class="mb-2 flex items-center gap-1 text-[10px] font-black uppercase tracking-[.18em] text-slate-400">Status TP Prioritas
                                    <span class="tooltip-container tooltip-down"><button type="button" class="tooltip-trigger" aria-label="Panduan filter status tujuan pembelajaran">?</button><span class="tooltip-content" role="tooltip">Filter ini menyaring TP prioritas yang tampil pada bab terpilih.</span></span>
                                </p>
                                <div class="flex flex-wrap gap-2">
                                    <button type="button" data-status-filter="all" class="status-filter rounded-xl border px-4 py-2.5 text-xs font-black transition">Semua</button>
                                    <button type="button" data-status-filter="attention" class="status-filter rounded-xl border px-4 py-2.5 text-xs font-black transition">Perlu Cek</button>
                                    <button type="button" data-status-filter="empty" class="status-filter rounded-xl border px-4 py-2.5 text-xs font-black transition">Belum ada soal</button>
                                    <button type="button" data-status-filter="stable" class="status-filter rounded-xl border px-4 py-2.5 text-xs font-black transition">Tercukupi</button>
                                </div>
                            </div>
                        </div>
                    </section>

                    <div class="space-y-5">
                        @foreach($chapters as $chapter)
                            <section data-chapter-panel="chapter-{{ $chapter['id'] }}" class="glass-card panel chapter-panel rounded-2xl p-5 md:p-6" style="display: none;">
                                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                                    <div class="min-w-0">
                                        <p class="text-[10px] font-black uppercase tracking-[0.22em] text-indigo-600 dark:text-indigo-400">{{ $chapter['label'] }}</p>
                                        <h3 class="mt-1 text-xl font-black tracking-tight text-slate-900 dark:text-white md:text-2xl">{{ $chapter['title'] }}</h3>
                                        <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-600 dark:text-slate-300">{{ $chapter['description'] }}</p>
                                    </div>
                                    <a href="{{ $chapter['bank_url'] }}" class="header-action inline-flex shrink-0 items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-xs font-bold">
                                        Bank Soal
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" d="m9 5 7 7-7 7"/></svg>
                                    </a>
                                </div>

                                <div class="mt-5 grid grid-cols-2 gap-2.5 sm:grid-cols-4">
                                    <div class="metric-card rounded-xl px-3 py-3">
                                        <p class="text-[9px] font-black uppercase tracking-widest text-slate-400">Capaian</p>
                                        <p class="mt-1 text-xl font-black text-slate-900 dark:text-white">{{ $chapter['average_mastery'] }}%</p>
                                    </div>
                                    <div class="metric-card rounded-xl px-3 py-3">
                                        <p class="text-[9px] font-black uppercase tracking-widest text-slate-400">Perlu cek</p>
                                        <p class="mt-1 text-xl font-black text-amber-600 dark:text-amber-300">{{ $chapter['attention_count'] }}</p>
                                    </div>
                                    <div class="metric-card rounded-xl px-3 py-3">
                                        <p class="text-[9px] font-black uppercase tracking-widest text-slate-400">Belum ada soal</p>
                                        <p class="mt-1 text-xl font-black text-rose-600 dark:text-rose-300">{{ $chapter['empty_count'] }}</p>
                                    </div>
                                    <div class="metric-card rounded-xl px-3 py-3">
                                        <p class="text-[9px] font-black uppercase tracking-widest text-slate-400">Soal</p>
                                        <p class="mt-1 text-xl font-black text-slate-900 dark:text-white">{{ $chapter['question_count'] }}</p>
                                    </div>
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
                                    <div>
                                        <p class="text-[10px] font-black uppercase tracking-[0.18em] text-slate-400">TP prioritas</p>
                                        <p class="mt-1 text-xs font-semibold text-slate-500 dark:text-slate-400">Menampilkan beberapa TP utama agar halaman tetap ringkas.</p>
                                    </div>
                                    <span class="rounded-lg border border-slate-200 bg-white/70 px-3 py-1.5 text-[10px] font-black uppercase tracking-widest text-slate-500 dark:border-white/10 dark:bg-black/10 dark:text-slate-300">
                                        {{ $chapterObjectivePreview->count() }} dari {{ count($chapter['objectives']) }} TP
                                    </span>
                                </div>

                                <div class="objective-grid mt-3 grid gap-3 lg:grid-cols-2">
                                    @foreach($chapterObjectivePreview as $objective)
                                        @php
                                            $questionCount = (int) ($objective['question_count'] ?? 0);
                                            $needsQuestions = (bool) ($objective['needs_questions'] ?? false) || $questionCount === 0;
                                            $needsAttention = (bool) ($objective['needs_attention'] ?? false) || in_array(($objective['status_key'] ?? ''), ['attention', 'waiting'], true);
                                            $stateKey = $needsQuestions ? 'empty' : ($needsAttention ? 'attention' : 'stable');
                                            $stateLabel = $stateKey === 'empty' ? 'Belum ada soal' : ($stateKey === 'attention' ? 'Perlu cek' : 'Tercukupi');
                                            $stateDescription = $stateKey === 'empty'
                                                ? 'TP ini belum memiliki soal evaluasi. Tambahkan soal agar capaian dapat diukur.'
                                                : ($stateKey === 'attention'
                                                    ? 'Data capaian perlu ditinjau. Periksa hasil jawaban dan arahan tindak lanjut di bawah.'
                                                    : 'Data soal dan capaian sudah tersedia. Tetap tinjau arahan untuk menjaga ketuntasan.');
                                            $bar = $stateKey === 'empty' ? 'bg-rose-500' : ($stateKey === 'attention' ? 'bg-amber-500' : 'bg-emerald-500');
                                            $scoreClass = $stateKey === 'empty' ? 'text-rose-700 dark:text-rose-200' : ($stateKey === 'attention' ? 'text-amber-700 dark:text-amber-200' : 'text-emerald-700 dark:text-emerald-300');
                                            $badgeClass = $stateKey === 'empty'
                                                ? 'border-rose-200 bg-rose-50 text-rose-700 dark:border-rose-500/20 dark:bg-rose-500/10 dark:text-rose-200'
                                                : ($stateKey === 'attention'
                                                    ? 'border-amber-200 bg-amber-50 text-amber-700 dark:border-amber-500/20 dark:bg-amber-500/10 dark:text-amber-200'
                                                    : 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-200');
                                        @endphp
                                        <article data-objective-status="{{ $stateKey }}" class="glass-card objective-card is-{{ $stateKey }} rounded-2xl border p-4">
                                            <div class="flex items-start justify-between gap-3">
                                                <div class="min-w-0">
                                                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">{{ $objective['display_code'] }}</p>
                                                    <h4 class="mt-1 text-sm font-black leading-snug text-slate-900 dark:text-white">{{ $objective['title'] }}</h4>
                                                </div>
                                                <span class="shrink-0 text-xl font-black {{ $scoreClass }}">{{ $objective['mastery_percent'] }}%</span>
                                            </div>

                                            <div class="mt-3 flex flex-wrap items-center gap-2">
                                                <span class="rounded-lg border {{ $badgeClass }} px-2.5 py-1 text-[10px] font-black uppercase tracking-widest">{{ $stateLabel }}</span>
                                                <span class="tooltip-container tooltip-right tooltip-down">
                                                    <button type="button" class="tooltip-trigger" aria-label="Penjelasan status {{ $stateLabel }}">?</button>
                                                    <span class="tooltip-content" role="tooltip">{{ $stateDescription }}</span>
                                                </span>
                                                <span class="rounded-lg border border-slate-200 bg-white/70 px-2.5 py-1 text-[10px] font-black uppercase tracking-widest text-slate-500 dark:border-white/10 dark:bg-black/10 dark:text-slate-300">{{ $objective['code'] }}</span>
                                            </div>

                                            <div class="objective-progress mt-3 h-1.5 overflow-hidden rounded-full bg-slate-200 dark:bg-black/20">
                                                <div class="h-full {{ $bar }}" style="width: {{ min(100, max(0, $objective['mastery_percent'])) }}%"></div>
                                            </div>

                                             <div class="mt-3 grid grid-cols-3 gap-2 text-center text-[11px]">
                                                 <div class="rounded-lg bg-white/70 px-2 py-2 dark:bg-black/10"><b>{{ $questionCount }}</b><br><span class="text-slate-500 dark:text-slate-400">soal</span></div>
                                                 <div class="rounded-lg bg-white/70 px-2 py-2 dark:bg-black/10"><b>{{ $objective['total_answers'] }}</b><br><span class="text-slate-500 dark:text-slate-400">jawaban</span></div>
                                                 <div class="rounded-lg bg-white/70 px-2 py-2 dark:bg-black/10"><b>{{ $objective['correct_count'] }}</b><br><span class="text-slate-500 dark:text-slate-400">benar</span></div>
                                             </div>

                                            <div class="mt-3 rounded-xl border border-slate-200 bg-white/70 px-3 py-2 text-xs leading-5 dark:border-white/10 dark:bg-black/10">
                                                <p class="line-clamp-2"><span class="font-black">Arahan:</span> {{ $objective['direction'] }}</p>
                                            </div>

                                            @if(empty($objective['questions']))
                                                <div class="mt-3 rounded-xl border border-dashed border-rose-300 bg-rose-50/70 px-3 py-3 text-xs font-bold text-rose-700 dark:border-rose-500/30 dark:bg-rose-500/10 dark:text-rose-200">
                                                    Belum ada soal. Tambahkan melalui Bank Soal {{ $chapter['label'] }}.
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
            const selectedClass = 'is-active';

            let activeChapter = $chapterSelect.val() || $('.chapter-panel').first().data('chapter-panel');
            let activeStatus = 'all';

            function syncThemeControl() {
                const isDark = $root.hasClass('dark');
                $darkIcon.toggleClass('hidden', isDark);
                $lightIcon.toggleClass('hidden', !isDark);
                $themeLabel.text(isDark ? 'Mode Terang' : 'Mode Gelap');
            }

            function openSidebar() {
                $sidebar.removeClass('-translate-x-full').addClass('translate-x-0');
                $overlay.stop(true, true).fadeIn(180);
                $body.addClass('overflow-hidden');
            }

            function closeSidebar() {
                $sidebar.removeClass('translate-x-0').addClass('-translate-x-full');
                $overlay.stop(true, true).fadeOut(160);
                $body.removeClass('overflow-hidden');
            }

            function setStatusButtons() {
                $('[data-status-filter]').removeClass(selectedClass)
                    .attr('aria-pressed', 'false');
                $('[data-status-filter="' + activeStatus + '"]')
                    .addClass(selectedClass)
                    .attr('aria-pressed', 'true');
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
                        if (animate) {
                            $card.stop(true, true).fadeIn(180);
                        } else {
                            $card.show();
                        }
                    } else if (animate) {
                        $card.stop(true, true).fadeOut(110);
                    } else {
                        $card.hide();
                    }
                });

                if (visible === 0) {
                    animate ? $empty.stop(true, true).fadeIn(160) : $empty.show();
                } else {
                    animate ? $empty.stop(true, true).fadeOut(100) : $empty.hide();
                }
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

                if (animate) {
                    $visible.stop(true, true).fadeOut(120, function () {
                        $target.stop(true, true).fadeIn(190, function () {
                            applyStatusFilter($target, false);
                        });
                    });
                } else {
                    $('.chapter-panel').hide();
                    $target.show();
                    applyStatusFilter($target, false);
                }
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
            showChapter(activeChapter, false);
            setStatusButtons();
            $('.details-content').hide();

            $('#sidebar-open').on('click', openSidebar);
            $('#sidebar-close, #sidebar-overlay').on('click', closeSidebar);

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
                    hideGlobalTooltip(true);
                }
            });

            $(window).on('resize scroll', function () {
                if ($activeTooltipTrigger.length) {
                    placeGlobalTooltip($activeTooltipTrigger);
                }
                if (window.innerWidth >= 768) {
                    $overlay.hide();
                    $body.removeClass('overflow-hidden');
                    $sidebar.removeClass('translate-x-0').addClass('md:translate-x-0');
                }
            });
        });
    </script>

</body>
</html>
