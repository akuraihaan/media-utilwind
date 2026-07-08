<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siswa · {{ $user->name ?? 'Profil Siswa' }}</title>
    
    {{-- RESOURCES --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    
    {{-- KONFIGURASI DARK MODE TAILWIND --}}
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    }
                }
            }
        }
    </script>

    {{-- SCRIPT PENGECEKAN TEMA OTOMATIS --}}
    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <style>
        /* --- THEME CONFIG --- */
        :root { --glass-bg: rgba(255, 255, 255, 0.85); --glass-border: rgba(0, 0, 0, 0.05); --accent: #6366f1; }
        .dark { --glass-bg: rgba(10, 14, 23, 0.65); --glass-border: rgba(255, 255, 255, 0.08); }
        
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; color: #1e293b; overflow: hidden; transition: background-color 0.3s, color 0.3s; }
        .dark body { background-color: #020617; color: #e2e8f0; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }

        /* --- SCROLLBAR --- */
        .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: rgba(0,0,0,0.05); border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(150,150,150,0.3); border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(150,150,150,0.5); }
        .dark .custom-scrollbar::-webkit-scrollbar-track { background: rgba(0,0,0,0.1); }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.25); }

        /* --- GLASS COMPONENTS --- */
        .glass-sidebar { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px); border-right: 1px solid rgba(0,0,0,0.05); z-index: 50; }
        .dark .glass-sidebar { background: rgba(5, 8, 16, 0.95); border-right: 1px solid var(--glass-border); }
        
        .glass-header { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(16px); border-bottom: 1px solid rgba(0,0,0,0.05); z-index: 40; }
        .dark .glass-header { background: rgba(2, 6, 23, 0.7); border-bottom: 1px solid var(--glass-border); }
        
        .glass-card { 
            background: var(--glass-bg); border: 1px solid var(--glass-border); 
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.05); backdrop-filter: blur(10px); transition: all 0.3s; 
            position: relative; overflow: visible !important; z-index: 10;
        }
        .dark .glass-card { box-shadow: 0 4px 30px rgba(0, 0, 0, 0.2); }
        .glass-card:hover { border-color: rgba(99, 102, 241, 0.4); transform: translateY(-4px); box-shadow: 0 12px 40px -10px rgba(99,102,241,0.15); z-index: 30; }

        /* --- INPUTS & NAV --- */
        .glass-input { background: rgba(0, 0, 0, 0.03); border: 1px solid rgba(0, 0, 0, 0.1); color: #1e293b; transition: 0.3s; }
        .dark .glass-input { background: rgba(255, 255, 255, 0.02); border: 1px solid rgba(255, 255, 255, 0.08); color: white; }
        .glass-input:focus { border-color: var(--accent); background: rgba(0, 0, 0, 0.05); outline: none; box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15); }
        .dark .glass-input:focus { background: rgba(255, 255, 255, 0.05); }
        
        .nav-link { display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 12px; color: #64748b; font-weight: 600; font-size: 0.9rem; transition: all 0.2s; border: 1px solid transparent; }
        .dark .nav-link { color: #94a3b8; font-weight: 500; }
        .nav-link:hover { background: rgba(0, 0, 0, 0.03); color: #0f172a; transform: translateX(4px); }
        .dark .nav-link:hover { background: rgba(255, 255, 255, 0.03); color: white; }
        .nav-link.active { background: linear-gradient(90deg, rgba(99, 102, 241, 0.1) 0%, rgba(99, 102, 241, 0) 100%); color: #6366f1; border-left: 3px solid #6366f1; border-radius: 4px 12px 12px 4px; }
        .dark .nav-link.active { color: #818cf8; border-left-color: #818cf8; }
        
        .tab-btn { position: relative; color: #64748b; transition: all 0.3s; }
        .tab-btn:hover { color: #1e293b; }
        .dark .tab-btn:hover { color: #cbd5e1; }
        .tab-btn.active { color: #6366f1; font-weight: 700; }
        .dark .tab-btn.active { color: #fff; font-weight: 600; text-shadow: 0 0 12px rgba(255,255,255,0.3); }
        .tab-btn.active::after { content: ''; position: absolute; bottom: -1px; left: 0; width: 100%; height: 2px; background: var(--accent); box-shadow: 0 -2px 10px var(--accent); border-radius: 2px 2px 0 0; }

        .table-row { transition: background 0.2s; border-bottom: 1px solid rgba(0,0,0,0.03); }
        .table-row:hover { background: rgba(0,0,0,0.02); }
        .dark .table-row { border-bottom: 1px solid rgba(255,255,255,0.03); }
        .dark .table-row:hover { background: rgba(255,255,255,0.02); }

        .reveal { opacity: 0; transform: translateY(15px); animation: revealAnim 0.5s forwards ease-out; }
        @keyframes revealAnim { to { opacity: 1; transform: translateY(0); } }
        [x-cloak] { display: none !important; }


        /* ==========================================================
           PENYEMPURNAAN DETAIL INTERAKSI — DISAMAKAN DENGAN DIREKTORI
           ========================================================== */
        html { scroll-behavior: smooth; }
        .smooth-student-scroll {
            scroll-behavior: smooth;
            overscroll-behavior-y: contain;
            -webkit-overflow-scrolling: touch;
            scrollbar-gutter: stable both-edges;
            scroll-padding-top: 8rem;
            will-change: auto;
        }
        .smooth-student-scroll:focus { outline: none; }

        .glass-card {
            transition-property: transform, border-color, box-shadow, background-color, opacity;
            transition-duration: .28s;
            transition-timing-function: cubic-bezier(.22,.61,.36,1);
            will-change: transform;
        }
        .glass-card:hover {
            transform: translate3d(0,-2px,0);
            box-shadow: 0 14px 36px -18px rgba(15,23,42,.28);
        }
        .dark .glass-card:hover { box-shadow: 0 18px 46px -20px rgba(0,0,0,.72); }

        .soft-hover,
        .tab-btn,
        .table-row,
        .glass-input,
        button,
        a {
            transition-property: transform, background-color, border-color, color, box-shadow, opacity;
            transition-duration: .22s;
            transition-timing-function: cubic-bezier(.22,.61,.36,1);
        }
        .tab-btn:hover { transform: translateY(-1px); }
        .table-row:hover { background: rgba(99,102,241,.045); }
        .dark .table-row:hover { background: rgba(129,140,248,.055); }
        .nav-link:hover { transform: translateX(2px); }
        button:hover, a:hover { will-change: transform; }
        button:active, a:active { transform: scale(.985); }

        .metric-card-quiet:hover { transform: translate3d(0,-2px,0); }
        .metric-card-quiet:hover .metric-icon-quiet { transform: scale(1.06); }
        .metric-icon-quiet { transition: transform .28s cubic-bezier(.22,.61,.36,1); }

        .la-pulse-card {
            background: rgba(255,255,255,.72);
            border: 1px solid rgba(15,23,42,.08);
            box-shadow: 0 10px 32px -24px rgba(15,23,42,.30);
            transition: transform .24s cubic-bezier(.22,.61,.36,1), border-color .24s ease, background-color .24s ease, box-shadow .24s ease;
        }
        .dark .la-pulse-card { background: rgba(255,255,255,.035); border-color: rgba(255,255,255,.08); box-shadow: none; }
        .la-pulse-card:hover { transform: translate3d(0,-2px,0); border-color: rgba(99,102,241,.28); box-shadow: 0 16px 34px -26px rgba(79,70,229,.42); }
        .dark .la-pulse-card:hover { border-color: rgba(129,140,248,.22); box-shadow: 0 16px 42px -30px rgba(0,0,0,.9); }
        .la-mini-bar { overflow: hidden; border-radius: 999px; background: rgba(148,163,184,.20); }
        .dark .la-mini-bar { background: rgba(255,255,255,.075); }
        .la-mini-bar > span { display: block; height: 100%; border-radius: inherit; transition: width 1s cubic-bezier(.22,.61,.36,1); }


        /* ==========================================================
           PRESENTASI LEARNING ANALYTICS
           Kelas berikut hanya mengatur tampilan. Seluruh data tetap
           memakai variabel dan koleksi yang sama dari controller.
           ========================================================== */
        .analytics-panel,
        .analytics-metric-card {
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(15,23,42,.08);
            background: rgba(255,255,255,.76);
            box-shadow: 0 12px 32px -26px rgba(15,23,42,.34);
            transition: transform .24s cubic-bezier(.22,.61,.36,1), border-color .24s ease, box-shadow .24s ease, background-color .24s ease;
        }
        .dark .analytics-panel,
        .dark .analytics-metric-card {
            border-color: rgba(255,255,255,.09);
            background: rgba(255,255,255,.035);
            box-shadow: none;
        }
        .analytics-panel:hover,
        .analytics-metric-card:hover {
            transform: translate3d(0,-1px,0);
            border-color: rgba(99,102,241,.28);
            box-shadow: 0 18px 38px -28px rgba(79,70,229,.42);
        }
        .dark .analytics-panel:hover,
        .dark .analytics-metric-card:hover {
            border-color: rgba(129,140,248,.24);
            box-shadow: 0 18px 46px -30px rgba(0,0,0,.9);
        }
        .analytics-ring {
            --progress: 0%;
            width: 96px;
            height: 96px;
            border-radius: 999px;
            display: grid;
            place-items: center;
            background: conic-gradient(#6366f1 var(--progress), rgba(148,163,184,.18) 0);
            flex: 0 0 auto;
            transition: background .45s ease;
        }
        .dark .analytics-ring { background: conic-gradient(#818cf8 var(--progress), rgba(255,255,255,.10) 0); }
        .analytics-ring__inner {
            width: 76px;
            height: 76px;
            border-radius: inherit;
            display: grid;
            place-items: center;
            background: rgba(255,255,255,.96);
            box-shadow: inset 0 0 0 1px rgba(15,23,42,.05);
        }
        .dark .analytics-ring__inner { background: #0f141e; box-shadow: inset 0 0 0 1px rgba(255,255,255,.07); }
        .analytics-track { height: 6px; overflow: hidden; border-radius: 999px; background: rgba(148,163,184,.20); }
        .dark .analytics-track { background: rgba(255,255,255,.075); }
        .analytics-track > span { display: block; height: 100%; border-radius: inherit; transition: width 1s cubic-bezier(.22,.61,.36,1); }
        .analytics-metric-card {
            width: 100%;
            min-height: 178px;
            text-align: left;
            padding: 1.15rem;
            border-radius: 1rem;
        }
        .analytics-metric-card:focus-visible {
            outline: 3px solid rgba(99,102,241,.28);
            outline-offset: 3px;
        }
        .analytics-metric-card .analytics-metric-icon {
            transition: transform .24s cubic-bezier(.22,.61,.36,1), background-color .24s ease;
        }
        .analytics-metric-card:hover .analytics-metric-icon { transform: scale(1.05); }
        .analytics-performance-row {
            transition: background-color .2s ease, transform .2s cubic-bezier(.22,.61,.36,1), border-color .2s ease;
        }
        .analytics-performance-row:hover {
            transform: translateX(2px);
            background: rgba(99,102,241,.045);
            border-color: rgba(99,102,241,.18);
        }
        .dark .analytics-performance-row:hover { background: rgba(129,140,248,.055); border-color: rgba(129,140,248,.16); }

        .insight-trigger { transition-duration: .2s; }
        .insight-tooltip:hover .insight-trigger { transform: scale(1.06); }
        .insight-content { z-index: 2147483000 !important; transition-duration: .18s; }
        .sticky, .glass-header { transform: translateZ(0); }

        .chart-container canvas { transition: opacity .22s ease; }
        .chart-container:hover canvas { opacity: .96; }

        @media (prefers-reduced-motion: reduce) {
            html,
            .smooth-student-scroll { scroll-behavior: auto !important; }
            *, *::before, *::after { animation-duration: .01ms !important; animation-iteration-count: 1 !important; transition-duration: .01ms !important; }
        }

        /* --- TOOLTIP INSIGHT (PETUNJUK HALAMAN) --- */
        .insight-tooltip { position: relative; display: inline-flex; align-items: center; justify-content: center; z-index: 50; margin-left: 6px; }
        .insight-tooltip:hover { z-index: 99999; }
        
        .insight-trigger { 
            display: flex; align-items: center; justify-content: center;
            width: 18px; height: 18px; border-radius: 50%;
            background: rgba(99, 102, 241, 0.1); border: 1px solid rgba(99, 102, 241, 0.3);
            color: #6366f1; font-size: 11px; font-weight: 800; cursor: help;
            transition: all 0.3s ease;
        }
        .dark .insight-trigger { background: rgba(129, 140, 248, 0.1); border-color: rgba(129, 140, 248, 0.3); color: #818cf8; }
        .insight-tooltip:hover .insight-trigger { background: #6366f1; color: white; border-color: #6366f1; transform: scale(1.1); box-shadow: 0 0 10px rgba(99,102,241,0.5); }
        
        .insight-content {
            opacity: 0; visibility: hidden; position: absolute; bottom: calc(100% + 10px); left: 50%; transform: translateX(-50%) translateY(10px);
            width: max-content; max-width: 260px; background: rgba(15, 23, 42, 0.95); backdrop-filter: blur(10px);
            color: #f8fafc; font-size: 12px; padding: 12px 16px; border-radius: 12px; text-align: center;
            box-shadow: 0 10px 30px -5px rgba(0,0,0,0.5); border: 1px solid rgba(255,255,255,0.1); 
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); pointer-events: none; font-weight: 500; line-height: 1.5; z-index: 99999;
        }
        .dark .insight-content { background: rgba(255, 255, 255, 0.95); color: #0f172a; border: 1px solid rgba(0,0,0,0.1); }
        
        .insight-tooltip:hover .insight-content { opacity: 1; visibility: visible; transform: translateX(-50%) translateY(0); }
        .insight-content::after {
            content: ''; position: absolute; top: 100%; left: 50%; transform: translateX(-50%);
            border-width: 6px; border-style: solid; border-color: rgba(15, 23, 42, 0.95) transparent transparent transparent;
        }
        .dark .insight-content::after { border-color: rgba(255, 255, 255, 0.95) transparent transparent transparent; }
        
        .insight-right .insight-content { bottom: auto; top: 50%; left: calc(100% + 12px); transform: translateY(-50%) translateX(-10px); text-align: left; }
        .insight-right:hover .insight-content { transform: translateY(-50%) translateX(0); }
        .insight-right .insight-content::after { top: 50%; left: -12px; transform: translateY(-50%); border-color: transparent rgba(15, 23, 42, 0.95) transparent transparent; border-width: 6px; }
        .dark .insight-right .insight-content::after { border-color: transparent rgba(255, 255, 255, 0.95) transparent transparent; }

    

       
        .dashboard-fade-up {
            opacity: 1;
            transform: translate3d(0, 0, 0);
        }
        .dashboard-fade-up.is-visible {
            animation: dashboardFadeUp .54s cubic-bezier(.22, .61, .36, 1) both;
            animation-delay: var(--reveal-delay, 0s);
        }
        @keyframes dashboardFadeUp {
            from { opacity: 0; transform: translate3d(0, 16px, 0); }
            to { opacity: 1; transform: translate3d(0, 0, 0); }
        }
        .overview-shell { gap: 2.25rem; }
        .overview-shell > .dashboard-fade-up { margin: 0; }
        @media (max-width: 767px) {
            .overview-shell { gap: 1.5rem; }
            .dashboard-fade-up.is-visible { animation-duration: .42s; }
        }
        @media (prefers-reduced-motion: reduce) {
            .dashboard-fade-up.is-visible { animation: none !important; }
        }

      
        .history-shell {
            display: flex;
            flex-direction: column;
            gap: 1.75rem;
        }
        .history-shell > * { margin: 0; }
        .history-fade-up {
            opacity: 1;
            transform: translate3d(0, 0, 0);
        }
        .history-fade-up.is-visible {
            animation: historyFadeUp .52s linear both;
            animation-delay: var(--history-delay, 0s);
        }
        @keyframes historyFadeUp {
            from { opacity: 0; transform: translate3d(0, 12px, 0); }
            to { opacity: 1; transform: translate3d(0, 0, 0); }
        }
        .history-metric-card,
        .history-panel {
            border: 1px solid rgba(15, 23, 42, .08);
            background: rgba(255, 255, 255, .76);
            box-shadow: 0 12px 32px -26px rgba(15, 23, 42, .34);
            transition: transform .24s cubic-bezier(.22,.61,.36,1), border-color .24s ease, box-shadow .24s ease, background-color .24s ease;
        }
        .dark .history-metric-card,
        .dark .history-panel {
            border-color: rgba(255, 255, 255, .09);
            background: rgba(255, 255, 255, .035);
            box-shadow: none;
        }
        .history-metric-card:hover,
        .history-panel:hover {
            transform: translate3d(0, -1px, 0);
            border-color: rgba(99, 102, 241, .26);
            box-shadow: 0 18px 38px -28px rgba(79, 70, 229, .38);
        }
        .dark .history-metric-card:hover,
        .dark .history-panel:hover {
            border-color: rgba(129, 140, 248, .22);
            box-shadow: 0 18px 46px -30px rgba(0, 0, 0, .9);
        }
        .history-progress-track {
            height: 5px;
            overflow: hidden;
            border-radius: 999px;
            background: rgba(148, 163, 184, .18);
        }
        .dark .history-progress-track { background: rgba(255, 255, 255, .075); }
        .history-progress-track > span {
            display: block;
            height: 100%;
            border-radius: inherit;
            transition: width .52s linear;
        }
        .history-panel-head {
            background: rgba(248, 250, 252, .72);
        }
        .dark .history-panel-head { background: rgba(2, 6, 23, .32); }
        .history-table thead { background: rgba(248, 250, 252, .76); }
        .dark .history-table thead { background: rgba(255, 255, 255, .025); }
        .history-table tbody tr { transition: background-color .2s ease; }
        .history-table tbody tr:hover { background: rgba(99, 102, 241, .045); }
        .dark .history-table tbody tr:hover { background: rgba(129, 140, 248, .055); }

        /* Ringkasan riwayat: setiap angka selalu diberi basis data dan satuan. */
        .history-summary-grid {
            display: grid;
            gap: 1rem;
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
        .history-summary-card {
            min-width: 0;
            overflow: hidden;
            border: 1px solid rgba(15, 23, 42, .08);
            border-radius: 1rem;
            background: rgba(255, 255, 255, .76);
            box-shadow: 0 12px 32px -26px rgba(15, 23, 42, .34);
            transition: transform .24s cubic-bezier(.22,.61,.36,1), border-color .24s ease, box-shadow .24s ease;
        }
        .dark .history-summary-card {
            border-color: rgba(255, 255, 255, .09);
            background: rgba(255, 255, 255, .035);
            box-shadow: none;
        }
        .history-summary-card:hover {
            transform: translate3d(0, -1px, 0);
            border-color: rgba(99, 102, 241, .28);
            box-shadow: 0 18px 38px -28px rgba(79, 70, 229, .38);
        }
        .dark .history-summary-card:hover {
            border-color: rgba(129, 140, 248, .22);
            box-shadow: 0 18px 46px -30px rgba(0, 0, 0, .9);
        }
        .history-summary-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: .75rem;
            padding: 1rem 1.1rem .9rem;
            border-bottom: 1px solid rgba(15, 23, 42, .06);
        }
        .dark .history-summary-head { border-color: rgba(255, 255, 255, .07); }
        .history-summary-title { display: flex; min-width: 0; align-items: center; gap: .65rem; }
        .history-summary-icon {
            display: inline-flex;
            width: 2rem;
            height: 2rem;
            flex: 0 0 auto;
            align-items: center;
            justify-content: center;
            border-radius: .7rem;
        }
        .history-summary-label {
            color: #64748b;
            font-size: .61rem;
            font-weight: 900;
            letter-spacing: .13em;
            line-height: 1.1;
            text-transform: uppercase;
        }
        .dark .history-summary-label { color: rgba(226, 232, 240, .48); }
        .history-summary-basis {
            margin-top: .22rem;
            color: #94a3b8;
            font-size: .66rem;
            font-weight: 750;
            line-height: 1.25;
        }
        .dark .history-summary-basis { color: rgba(226, 232, 240, .42); }
        .history-summary-count {
            flex: 0 0 auto;
            border-radius: .6rem;
            padding: .38rem .52rem;
            font-size: .66rem;
            font-weight: 900;
            line-height: 1;
        }
        .history-summary-body { padding: 1rem 1.1rem 1.1rem; }
        .history-summary-result { display: flex; align-items: flex-end; justify-content: space-between; gap: 1rem; }
        .history-summary-result-label {
            display: block;
            color: #64748b;
            font-size: .62rem;
            font-weight: 850;
            letter-spacing: .1em;
            text-transform: uppercase;
        }
        .dark .history-summary-result-label { color: rgba(226, 232, 240, .46); }
        .history-summary-result-value {
            display: inline-flex;
            align-items: baseline;
            gap: .32rem;
            margin-top: .25rem;
            color: #0f172a;
            font-size: 2rem;
            font-weight: 900;
            letter-spacing: -.06em;
            line-height: 1;
            font-variant-numeric: tabular-nums;
        }
        .dark .history-summary-result-value { color: #f8fafc; }
        .history-summary-result-value small {
            color: #94a3b8;
            font-size: .72rem;
            font-weight: 850;
            letter-spacing: 0;
        }
        .history-summary-rate { text-align: right; }
        .history-summary-rate strong {
            display: block;
            color: #0f172a;
            font-size: 1.2rem;
            font-weight: 900;
            letter-spacing: -.045em;
            line-height: 1;
            font-variant-numeric: tabular-nums;
        }
        .dark .history-summary-rate strong { color: #f8fafc; }
        .history-summary-rate span {
            display: block;
            margin-top: .25rem;
            color: #94a3b8;
            font-size: .61rem;
            font-weight: 800;
            line-height: 1;
        }
        .history-summary-data {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: .55rem;
            margin-top: .9rem;
        }
        .history-summary-data > div,
        .history-signal-grid > div {
            min-width: 0;
            border: 1px solid rgba(15, 23, 42, .06);
            border-radius: .7rem;
            background: rgba(248, 250, 252, .8);
            padding: .62rem .7rem;
        }
        .dark .history-summary-data > div,
        .dark .history-signal-grid > div {
            border-color: rgba(255, 255, 255, .065);
            background: rgba(2, 6, 23, .28);
        }
        .history-summary-data dt,
        .history-signal-grid span {
            display: block;
            overflow: hidden;
            color: #94a3b8;
            font-size: .58rem;
            font-weight: 850;
            letter-spacing: .075em;
            line-height: 1.2;
            text-overflow: ellipsis;
            text-transform: uppercase;
            white-space: nowrap;
        }
        .history-summary-data dd,
        .history-signal-grid strong {
            display: block;
            margin-top: .28rem;
            color: #334155;
            font-size: .9rem;
            font-weight: 900;
            line-height: 1;
            font-variant-numeric: tabular-nums;
        }
        .dark .history-summary-data dd,
        .dark .history-signal-grid strong { color: #e2e8f0; }
        .history-summary-data dd small { color: #94a3b8; font-size: .62rem; font-weight: 850; }
        .history-signal-label {
            margin-top: .9rem;
            color: #94a3b8;
            font-size: .58rem;
            font-weight: 900;
            letter-spacing: .12em;
            text-transform: uppercase;
        }
        .history-signal-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: .55rem; margin-top: .45rem; }
        .history-header-chip {
            display: inline-flex;
            align-items: center;
            border: 1px solid rgba(148, 163, 184, .26);
            border-radius: .55rem;
            padding: .36rem .52rem;
            color: #64748b;
            font-size: .62rem;
            font-weight: 800;
            line-height: 1;
            white-space: nowrap;
        }
        .dark .history-header-chip { border-color: rgba(255, 255, 255, .10); color: rgba(226, 232, 240, .62); }
        .history-metric-number {
            display: inline-flex;
            align-items: baseline;
            gap: .35rem;
        }
        .history-metric-unit {
            color: #94a3b8;
            font-size: .72rem;
            font-weight: 800;
        }
        .history-metric-description {
            min-height: 2.7rem;
        }
        @media (max-width: 767px) {
            .history-shell { gap: 1.35rem; }
            .history-fade-up.is-visible { animation-duration: .42s; }
        }
        @media (prefers-reduced-motion: reduce) {
            .history-fade-up.is-visible { animation: none !important; }
            .history-progress-track > span { transition: none !important; }
        }

    

        /* ==========================================================
           DATA LEARNING ANALYTICS YANG EKSPLISIT
           Tooltip memakai portal fixed agar selalu tampil di atas kartu,
           tabel, modal, dan area overflow.
           ========================================================== */
        .analytics-info-button {
            display: inline-flex;
            width: 1.1rem;
            height: 1.1rem;
            flex: 0 0 auto;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(99,102,241,.34);
            border-radius: 999px;
            background: rgba(99,102,241,.07);
            color: #6366f1;
            font-size: .62rem;
            font-weight: 900;
            line-height: 1;
            cursor: help;
        }
        .analytics-info-button:hover,
        .analytics-info-button:focus-visible { border-color: #6366f1; background: #6366f1; color: #fff; outline: none; }
        .dark .analytics-info-button { border-color: rgba(129,140,248,.45); background: rgba(129,140,248,.12); color: #a5b4fc; }
        .dark .analytics-info-button:hover,
        .dark .analytics-info-button:focus-visible { background: #818cf8; color: #fff; }
        .analytics-source-list { display: grid; gap: .6rem; }
        .analytics-source-list > div { display: flex; align-items: center; justify-content: space-between; gap: .6rem; border: 1px solid rgba(15,23,42,.06); border-radius: .75rem; background: rgba(248,250,252,.78); padding: .72rem .75rem; }
        .dark .analytics-source-list > div { border-color: rgba(255,255,255,.07); background: rgba(2,6,23,.28); }
        .analytics-source-list dt { color: #64748b; font-size: .63rem; font-weight: 850; line-height: 1.3; }
        .dark .analytics-source-list dt { color: rgba(226,232,240,.52); }
        .analytics-source-list dd { display: flex; flex-direction: column; align-items: flex-end; gap: .12rem; margin: 0; text-align: right; }
        .analytics-source-list dd strong { color: #0f172a; font-size: .78rem; font-weight: 900; font-variant-numeric: tabular-nums; }
        .dark .analytics-source-list dd strong { color: #f8fafc; }
        .analytics-source-list dd span { color: #94a3b8; font-size: .61rem; font-weight: 850; }
        .analytics-score-tile { min-width: 0; border-width: 1px; border-radius: .85rem; padding: .9rem; }
        .analytics-score-tile p { color: #64748b; font-size: .58rem; font-weight: 900; letter-spacing: .1em; text-transform: uppercase; }
        .analytics-score-tile strong { display: block; margin-top: .45rem; color: #0f172a; font-size: 1.65rem; font-weight: 900; letter-spacing: -.05em; font-variant-numeric: tabular-nums; }
        .dark .analytics-score-tile strong { color: #f8fafc; }
        .analytics-score-tile small { color: #94a3b8; font-size: .67rem; font-weight: 850; }
        .analytics-score-tile span { display: block; margin-top: .35rem; color: #64748b; font-size: .64rem; font-weight: 750; }
        .dark .analytics-score-tile span { color: rgba(226,232,240,.48); }
        .analytics-signal-tile { min-width: 0; border: 1px solid rgba(15,23,42,.06); border-radius: .85rem; background: rgba(248,250,252,.8); padding: .8rem; text-align: center; }
        .dark .analytics-signal-tile { border-color: rgba(255,255,255,.07); background: rgba(2,6,23,.28); }
        .analytics-signal-tile p { min-height: 2.1em; color: #64748b; font-size: .57rem; font-weight: 900; letter-spacing: .08em; line-height: 1.2; text-transform: uppercase; }
        .analytics-signal-tile strong { display: block; margin-top: .35rem; color: #0f172a; font-size: 1.3rem; font-weight: 900; letter-spacing: -.05em; font-variant-numeric: tabular-nums; }
        .dark .analytics-signal-tile strong { color: #f8fafc; }
        .analytics-signal-tile span { display: block; margin-top: .15rem; color: #94a3b8; font-size: .58rem; font-weight: 850; }
        .history-composition { display: flex; height: .5rem; overflow: hidden; border-radius: 999px; background: rgba(148,163,184,.18); }
        .history-composition > span { display: block; height: 100%; min-width: 0; transition: width .52s linear; }
        .history-composition-legend { display: flex; flex-wrap: wrap; gap: .5rem .85rem; margin-top: .55rem; color: #64748b; font-size: .61rem; font-weight: 800; }
        .dark .history-composition-legend { color: rgba(226,232,240,.52); }
        .history-composition-legend span { display: inline-flex; align-items: center; gap: .28rem; }
        .history-composition-legend i { display: inline-block; width: .48rem; height: .48rem; border-radius: 999px; }
        #learningAnalyticsTooltip {
            position: fixed;
            z-index: 2147483647;
            width: min(310px, calc(100vw - 24px));
            border: 1px solid rgba(99,102,241,.28);
            border-radius: .85rem;
            background: rgba(255,255,255,.98);
            padding: .75rem .85rem;
            color: #334155;
            box-shadow: 0 18px 46px rgba(15,23,42,.24);
            font-size: .72rem;
            font-weight: 650;
            line-height: 1.55;
            opacity: 0;
            pointer-events: none;
            transform: translateY(5px);
            transition: opacity .14s ease, transform .14s ease;
        }
        #learningAnalyticsTooltip.is-visible { opacity: 1; transform: translateY(0); }
        .dark #learningAnalyticsTooltip { border-color: rgba(129,140,248,.34); background: rgba(15,23,42,.98); color: #e2e8f0; box-shadow: 0 20px 54px rgba(2,6,23,.82); }
        @media (max-width: 640px) {
            .analytics-signal-tile { padding: .65rem .5rem; }
            .analytics-signal-tile strong { font-size: 1.15rem; }
        }

    </style>
</head>
<body class="h-screen w-full flex overflow-hidden selection:bg-indigo-500/30 selection:text-indigo-900 dark:selection:text-white" 
      x-data="{ 
          sidebarOpen: false,
          pageReady: false,
          activeTab: 'overview', 
          showEdit: false, 
          searchLab: '', 
          searchQuiz: '',
          searchLesson: '',
          showProgress: false,
          historyReady: false,
          
          // State modal ringkasan analitik
          showLessonModal: false,
          showLabModal: false,
          showQuizModal: false,
          showGlobalProgressModal: false,
          showQuizReviewModal: false,
          selectedQuizReview: null,
          showStudentGuideModal: false,

          confirmHapus() {
              Swal.fire({ title: 'Hapus Siswa?', text: 'Tindakan ini tidak dapat dibatalkan. Semua data riwayat akan terhapus.', icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#334155', confirmButtonText: 'Ya, Hapus Permanen', cancelButtonText: 'Batal', reverseButtons: true })
              .then((result) => { if (result.isConfirmed) document.getElementById('delete-student-form').submit(); })
          }
      }"
      @keydown.escape.window="showLessonModal = false; showLabModal = false; showQuizModal = false; showGlobalProgressModal = false; showQuizReviewModal = false; showStudentGuideModal = false; showEdit = false;"
      x-init="$nextTick(() => { pageReady = true; }); setTimeout(() => showProgress = true, 200); $watch('activeTab', value => { if(value === 'overview') { showProgress = false; setTimeout(() => showProgress = true, 50); } if(value === 'history') { historyReady = false; setTimeout(() => historyReady = true, 70); } });">

    {{-- HELPER DATA BLADE (Real Database Collections & Logical Duration Formatter) --}}
    @php
        use Illuminate\Support\Str;

        function formatTime($seconds) {
            if ($seconds === null || $seconds === '') return '-';
            if ($seconds == 0) return '0s';
            if ($seconds > 43200) { return '> 12j'; }
            
            $h = floor($seconds / 3600);
            $m = floor(($seconds % 3600) / 60);
            $s = $seconds % 60;
            
            $res = [];
            if ($h > 0) $res[] = "{$h}j";
            if ($m > 0) $res[] = "{$m}m";
            if ($s > 0 || empty($res)) $res[] = "{$s}s";
            
            return implode(' ', $res);
        }
        
        $labHistories = isset($labHistories) ? collect($labHistories) : collect([]);
        $quizAttempts = isset($quizAttempts) ? collect($quizAttempts) : collect([]);
        $lessonHistories = isset($lessonHistories) ? collect($lessonHistories) : collect([]);
        $completedLessonIds = $completedLessonIds ?? [];
        
        // PETA BLUEPRINT TRACKER
        $curriculumMap = [
            [
                'id' => 1, 'number' => '01', 'title' => 'PENDAHULUAN', 'color' => 'cyan', 'lab_id' => 1, 'lab_name' => 'Setup Environment', 'quiz_key' => '1',
                'topics' => [['name' => '1.1 Konsep HTML & CSS', 'ids' => range(1, 6)], ['name' => '1.2 Konsep Dasar Tailwind', 'ids' => range(7, 11)], ['name' => '1.3 Latar Belakang & Struktur', 'ids' => range(12, 15)], ['name' => '1.4 Implementasi pada HTML', 'ids' => range(16, 19)], ['name' => '1.5 Keunggulan & Utilitas', 'ids' => range(20, 23)], ['name' => '1.6 Instalasi & Konfigurasi', 'ids' => range(24, 28)]]
            ],
            [
                'id' => 2, 'number' => '02', 'title' => 'LAYOUTING', 'color' => 'indigo', 'lab_id' => 2, 'lab_name' => 'Building Grid Layout', 'quiz_key' => '2',
                'topics' => [['name' => '2.1 Arsitektur Flexbox', 'ids' => range(29, 33)], ['name' => '2.2 Penguasaan Sistem Grid', 'ids' => range(34, 40)], ['name' => '2.3 Pengelolaan Layout', 'ids' => range(41, 45)]]
            ],
            [
                'id' => 3, 'number' => '03', 'title' => 'STYLING', 'color' => 'fuchsia', 'lab_id' => 3, 'lab_name' => 'Styling Components', 'quiz_key' => '3',
                'topics' => [['name' => '3.1 Tipografi & Font', 'ids' => range(46, 51)], ['name' => '3.2 Latar Belakang', 'ids' => range(52, 55)], ['name' => '3.3 Borders & Rings', 'ids' => range(56, 59)], ['name' => '3.4 Efek dan Filter', 'ids' => range(60, 64)]]
            ]
        ];

        // 🔹 LOGIKA GAMBAR AVATAR (MENGGUNAKAN UPLOADS & CACHE BUSTING)
        $pathPrefix = 'uploads/'; 

        // Avatar untuk ADMIN (User Login)
        $adminAvatarUrl = 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name ?? 'A').'&background=6366f1&color=fff&size=256';
        if (!empty(Auth::user()->avatar)) {
            $adminAvatarUrl = Str::startsWith(Auth::user()->avatar, ['http://', 'https://']) 
                ? Auth::user()->avatar 
                : asset($pathPrefix . Auth::user()->avatar) . '?v=' . time(); 
        }

        // Avatar untuk SISWA (Profile yang sedang dilihat)
        $studentAvatarUrl = 'https://ui-avatars.com/api/?name='.urlencode($user->name ?? 'S').'&background=06b6d4&color=fff&size=256';
        if (!empty($user->avatar)) {
            $studentAvatarUrl = Str::startsWith($user->avatar, ['http://', 'https://']) 
                ? $user->avatar 
                : asset($pathPrefix . $user->avatar) . '?v=' . time();
        }

        $latestQuizForAnalytics = $quizAttempts->sortByDesc('completed_at')->first() ?? $quizAttempts->sortByDesc('created_at')->first();


        $latestActivityCandidates = collect();
        if ($latestQuizForAnalytics) {
            $latestActivityCandidates->push($latestQuizForAnalytics->completed_at ?? $latestQuizForAnalytics->created_at ?? null);
        }
        $latestLabForPulse = $labHistories->sortByDesc('created_at')->first();
        if ($latestLabForPulse) {
            $latestActivityCandidates->push($latestLabForPulse->completed_at ?? $latestLabForPulse->created_at ?? null);
        }
        $latestLessonForPulse = $lessonHistories->sortByDesc('created_at')->first();
        if ($latestLessonForPulse) {
            $latestActivityCandidates->push($latestLessonForPulse->created_at ?? null);
        }
        $latestActivityAt = $latestActivityCandidates->filter()->map(fn($date) => \Carbon\Carbon::parse($date))->sortDesc()->first();
        $quizPassedCountForPulse = count(array_filter($quizScoresMap ?? [], fn($s) => $s >= 70));
        $totalAcademicItemsForPulse = max(1, (int)($totalLessons ?? 65) + (int)($totalLabs ?? 4) + (int)($totalQuizzes ?? 4));
        $completedAcademicItemsForPulse = (int)($lessonsCompleted ?? count($completedLessonIds ?? [])) + (int)($labsCompleted ?? ($labStats['total'] ?? 0)) + (int)$quizPassedCountForPulse;


        // RINGKASAN PRESENTASI ANALITIK — dihitung dari data yang telah tersedia.
        $lessonsDoneForAnalytics = (int) ($lessonsCompleted ?? count($completedLessonIds ?? []));
        $lessonsTotalForAnalytics = max(1, (int) ($totalLessons ?? 65));
        $labsDoneForAnalytics = (int) ($labsCompleted ?? ($labStats['total'] ?? 0));
        $labsTotalForAnalytics = max(1, (int) ($totalLabs ?? 4));
        $quizzesPassedForAnalytics = (int) $quizPassedCountForPulse;
        $quizzesTotalForAnalytics = max(1, (int) ($totalQuizzes ?? 4));

        $lessonProgressForAnalytics = min(100, round(($lessonsDoneForAnalytics / $lessonsTotalForAnalytics) * 100));
        $labProgressForAnalytics = min(100, round(($labsDoneForAnalytics / $labsTotalForAnalytics) * 100));
        $quizProgressForAnalytics = min(100, round(($quizzesPassedForAnalytics / $quizzesTotalForAnalytics) * 100));
        $quizAverageForAnalytics = (float) ($quizAverage ?? ($quizStats['avg_score'] ?? 0));
        $labAverageForAnalytics = (float) ($labAverage ?? ($labStats['avg_score'] ?? 0));

        $quizFocusLostForAnalytics = (int) data_get($studentAnalyticsSummary ?? [], 'quiz_focus_lost_total', 0);
        $quizFlaggedForAnalytics = (int) data_get($studentAnalyticsSummary ?? [], 'quiz_flagged_total', 0);
        $quizUnansweredForAnalytics = (int) data_get($studentAnalyticsSummary ?? [], 'quiz_unanswered_total', 0);
        $quizInteractionSignalsForAnalytics = $quizFocusLostForAnalytics + $quizFlaggedForAnalytics + $quizUnansweredForAnalytics;
        $latestActivityLabelForAnalytics = $latestActivityAt ? $latestActivityAt->diffForHumans() : 'Belum tercatat';
        $latestActivityDateForAnalytics = $latestActivityAt ? $latestActivityAt->translatedFormat('d M Y, H:i') : 'Belum ada riwayat aktivitas';

        // Basis angka analitik dibuat eksplisit agar setiap visual dapat dibaca bersama pembilang dan targetnya.
        $progressCompletedForAnalytics = $lessonsDoneForAnalytics + $labsDoneForAnalytics + $quizzesPassedForAnalytics;
        $progressTargetForAnalytics = $lessonsTotalForAnalytics + $labsTotalForAnalytics + $quizzesTotalForAnalytics;
        $progressPercentForAnalytics = $progressTargetForAnalytics > 0
            ? min(100, round(($progressCompletedForAnalytics / $progressTargetForAnalytics) * 100))
            : 0;
        $lessonRecordCountForAnalytics = $lessonHistories->count();
        $labRecordCountForAnalytics = $labHistories->count();
        $quizRecordCountForAnalytics = $quizAttempts->count();
    @endphp

     {{-- ==================== 1. SIDEBAR ==================== --}}
    <div x-show="sidebarOpen" class="fixed inset-0 bg-slate-900/60 dark:bg-[#020617]/80 backdrop-blur-sm z-[90] md:hidden transition-colors" @click="sidebarOpen = false" x-transition.opacity style="display: none;" x-cloak></div>

    <aside class="glass-sidebar w-72 h-full flex flex-col fixed md:relative z-[100] transition-transform duration-300 transform md:translate-x-0" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
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
            <button @click="sidebarOpen = false" class="md:hidden text-slate-500 dark:text-white/50 hover:text-slate-800 dark:hover:text-white relative z-10 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        @include('admin.partials.sidebar-nav')

        {{-- USER PROFILE Bawah Sidebar --}}
        <div class="p-4 border-t border-slate-200 dark:border-white/5 bg-slate-50/50 dark:bg-[#05080f]/50 transition-colors">
            <div class="flex items-center gap-3 mb-4 px-2">
                <img src="{{ $adminAvatarUrl }}" alt="Admin Avatar" class="w-8 h-8 rounded-full object-cover shadow-lg border border-slate-200 dark:border-white/10 bg-indigo-500">
                <div class="overflow-hidden">
                    <p class="text-xs font-bold text-slate-900 dark:text-white truncate transition-colors">{{ Auth::user()->name ?? 'Administrator' }}</p>
                    <p class="text-[10px] text-slate-500 dark:text-white/40 truncate transition-colors">Administrator Sistem</p>
                </div>
            </div>
            
            {{-- THEME TOGGLE BUTTON --}}
            <button id="theme-toggle-sidebar" type="button" class="w-full mb-2 flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg bg-slate-200/50 dark:bg-white/5 hover:bg-slate-200 dark:hover:bg-white/10 text-slate-700 dark:text-slate-300 transition-colors border border-transparent dark:border-transparent text-xs font-bold shadow-sm dark:shadow-none">
                <svg id="theme-toggle-dark-icon-sidebar" class="hidden w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                <svg id="theme-toggle-light-icon-sidebar" class="hidden w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"></path></svg>
                <span id="theme-toggle-text-sidebar">Ubah Tema</span>
            </button>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-500 hover:text-red-700 dark:hover:text-white transition-colors text-xs font-bold border border-red-200 dark:border-red-500/20 hover:border-red-300 dark:hover:border-red-500 group shadow-sm dark:shadow-none">
                    <svg class="w-3.5 h-3.5 transition group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- MAIN CONTENT --}}
    <main id="admin-main-content" class="flex-1 flex flex-col relative h-full bg-slate-50 dark:bg-[#020617] overflow-hidden transition-colors">
        
        {{-- Background Aesthetics --}}
        <div class="fixed inset-0 pointer-events-none z-0">
            <div class="absolute top-[10%] left-[20%] w-[500px] h-[500px] bg-indigo-300/20 dark:bg-indigo-600/10 rounded-full blur-[120px] transition-colors duration-500"></div>
            <div class="absolute bottom-[10%] right-[10%] w-[400px] h-[400px] bg-cyan-300/20 dark:bg-cyan-600/10 rounded-full blur-[120px] transition-colors duration-500"></div>
            <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.02] dark:opacity-[0.04] mix-blend-overlay transition-opacity"></div>
        </div>

        {{-- HEADER PROFILE --}}
        <header class="glass-header flex flex-col justify-end px-6 md:px-10 shrink-0 sticky top-0 z-40 pt-5 transition-colors">
            <div class="flex items-start justify-between w-full mb-3 md:mb-5">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = true" class="md:hidden p-2 bg-slate-200/50 dark:bg-white/5 rounded-lg text-slate-700 dark:text-white hover:bg-slate-200 dark:hover:bg-white/10 transition mt-1"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg></button>
                    <div class="flex items-center gap-4">
                        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-indigo-500 to-cyan-400 p-[1px] shadow-lg hidden sm:block relative">
                            <img src="{{ $studentAvatarUrl }}" alt="Avatar" class="w-full h-full object-cover rounded-[10px] bg-white dark:bg-[#0f141e]">
                            <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-emerald-500 rounded-full border-2 border-white dark:border-[#020617] flex items-center justify-center text-white" title="Akun Siswa Terverifikasi Aktif">
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                        </div>
                        <div>
                            <nav class="flex text-[10px] text-slate-500 dark:text-white/50 mb-1.5 font-bold hidden sm:flex transition-colors">
                                <ol class="inline-flex items-center space-x-1">
                                    <li class="inline-flex items-center"><a href="{{ route('admin.students.index') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition">Direktori Siswa</a></li>
                                    <li><div class="flex items-center"><svg class="w-3 h-3 text-slate-400 dark:text-white/30 mx-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg><span class="text-slate-900 dark:text-white transition-colors">{{ $user->name ?? 'Detail Siswa' }}</span></div></li>
                                </ol>
                            </nav>
                            <h2 class="text-slate-900 dark:text-white font-bold text-lg md:text-xl tracking-tight flex items-center gap-2 leading-none transition-colors">{{ $user->name ?? 'Profil Siswa' }}</h2>
                            <div class="flex items-center gap-2 mt-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse shadow-[0_0_8px_#10b981]"></span>
                                <p class="text-[10px] text-slate-500 dark:text-white/40 font-mono transition-colors">{{ $user->email ?? 'No email recorded' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center gap-3 sm:gap-6 mt-1">

                    {{-- Panduan halaman --}}
                    <button type="button" @click="showStudentGuideModal = true" class="p-2.5 sm:px-4 sm:py-2.5 rounded-full sm:rounded-xl bg-slate-200/50 dark:bg-white/5 hover:bg-slate-200 dark:hover:bg-white/10 border border-transparent dark:border-white/10 text-slate-700 dark:text-white text-xs font-bold transition flex items-center gap-2 shadow-sm dark:shadow-lg" title="Panduan detail siswa">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M8.228 9.228a3.75 3.75 0 115.303 5.304c-.93.93-1.531 1.408-1.531 2.468m0 3h.01M12 3a9 9 0 100 18 9 9 0 000-18z"/></svg>
                        
                    </button>

                    {{-- Menu Ekspor --}}
                    <div class="relative" x-data="{ exportOpen: false }">
                        <div class="flex items-center gap-1.5">
                            <button @click="exportOpen = !exportOpen" @click.away="exportOpen = false" class="p-2.5 sm:px-4 sm:py-2.5 rounded-full sm:rounded-xl bg-slate-200/50 dark:bg-white/5 hover:bg-slate-200 dark:hover:bg-white/10 border border-transparent dark:border-white/10 text-slate-700 dark:text-white text-xs font-bold transition flex items-center gap-2 shadow-sm dark:shadow-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span class="hidden sm:inline">Ekspor</span>
                            </button>
                            
                        </div>

                        <div x-show="exportOpen" class="absolute right-0 mt-2 w-48 bg-white dark:bg-[#0f141e] border border-slate-200 dark:border-white/10 rounded-xl shadow-lg dark:shadow-[0_15px_50px_rgba(0,0,0,0.9)] z-[9999] overflow-hidden transition-colors" style="display: none;" x-transition>
                            <div class="px-4 py-2 border-b border-slate-100 dark:border-white/5 text-[9px] font-bold text-slate-400 dark:text-white/30 uppercase tracking-widest bg-slate-50 dark:bg-[#0a0e17] transition-colors">Pilih Format</div>
                            <a href="{{ route('admin.student.export.csv', $user->id) }}" class="flex items-center gap-3 px-4 py-3.5 text-[11px] font-bold text-slate-700 dark:text-white hover:bg-slate-50 dark:hover:bg-white/5 transition border-b border-slate-100 dark:border-white/5"><svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg> Ekspor CSV</a>
                            <a href="{{ route('admin.student.export.pdf', $user->id) }}" target="_blank" class="flex items-center gap-3 px-4 py-3.5 text-[11px] font-bold text-slate-700 dark:text-white hover:bg-slate-50 dark:hover:bg-white/5 transition"><svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg> Cetak PDF</a>
                        </div>
                    </div>

                    <div class="flex items-center gap-1.5">
                        <button @click="showEdit = true" class="p-2.5 rounded-full bg-indigo-50 dark:bg-indigo-500/10 hover:bg-indigo-100 dark:hover:bg-indigo-500 text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-white border border-indigo-200 dark:border-indigo-500/20 hover:border-indigo-300 dark:hover:border-indigo-500 transition-all shadow-sm dark:shadow-lg active:scale-95"><svg class="w-4 h-4 transition-transform hover:rotate-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg></button>
                        <div class="insight-tooltip insight-right">
                            
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabs --}}
            <div class="flex gap-6 md:gap-8 mt-2 overflow-x-auto custom-scrollbar w-full relative z-10 items-center pb-2">
                <button @click="activeTab = 'overview'" :class="activeTab === 'overview' ? 'active text-slate-900 dark:text-white' : 'text-slate-500'" class="tab-btn pb-3 text-[11px] font-bold uppercase tracking-wider flex items-center gap-2 whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" /></svg> Ikhtisar
                </button>
                <button @click="activeTab = 'curriculum'" :class="activeTab === 'curriculum' ? 'active text-slate-900 dark:text-white' : 'text-slate-500'" class="tab-btn pb-3 text-[11px] font-bold uppercase tracking-wider flex items-center gap-2 whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg> Pelacakan Kurikulum
                </button>
                <button @click="activeTab = 'history'" :class="activeTab === 'history' ? 'active text-slate-900 dark:text-white' : 'text-slate-500'" class="tab-btn pb-3 text-[11px] font-bold uppercase tracking-wider flex items-center gap-2 whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Riwayat Aktivitas
                </button>
            </div>
        </header>

        {{-- CONTENT BODY --}}
        <div data-smooth-student-scroll class="smooth-student-scroll flex-1 overflow-y-auto custom-scrollbar p-5 md:p-8 z-10" tabindex="-1">
            <div class="max-w-7xl mx-auto pb-20 relative">

                {{-- =========================================================
                     TAB 1: OVERVIEW 
                     ========================================================= --}}
                <div x-show="activeTab === 'overview'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="overview-shell flex flex-col" style="display: none;" x-cloak>
                    
                    <div class="flex items-center">
                        <h2 class="text-lg font-black text-slate-800 dark:text-white tracking-tight">Profil Siswa</h2>
                    </div>

                    {{-- PROFIL & STATUS KELAS --}}
                    <div class="dashboard-fade-up" :class="pageReady ? 'is-visible' : ''" style="--reveal-delay: .04s;">
                        <div class="glass-card rounded-2xl p-6 md:p-8 flex flex-col md:flex-row items-start md:items-center justify-between gap-6 shadow-sm dark:shadow-lg border border-slate-200 dark:border-white/5 transition-colors">
                            
                            {{-- Informasi Dasar Siswa --}}
                            <div class="flex items-center gap-5 w-full md:w-auto">
                                <div class="w-16 h-16 rounded-2xl bg-indigo-50 dark:bg-indigo-500/20 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-2xl font-black border border-indigo-200 dark:border-indigo-500/30 shrink-0 transition-colors shadow-inner overflow-hidden relative">
                                    <img src="{{ $studentAvatarUrl }}" alt="Avatar" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight transition-colors flex items-center gap-2">
                                        {{ $user->name }}
                                    </h3>
                                    <p class="text-sm text-slate-500 dark:text-slate-400 font-mono mt-0.5 transition-colors">{{ $user->email }}</p>
                                </div>
                            </div>
                            
                            {{-- Class Status (Interactive Edit) --}}
                            <div class="w-full md:w-auto min-w-[280px]">
                                @empty($user->class_group)
                                    <button @click.stop="showEdit = true" class="w-full py-3 rounded-xl bg-indigo-50 dark:bg-indigo-600/20 hover:bg-indigo-100 dark:hover:bg-indigo-600 text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-white text-xs font-bold transition-colors border border-indigo-200 dark:border-indigo-500/30">
                                        Set Kelas Manual
                                    </button>
                                @else
                                    <div class="flex flex-col gap-2 w-full relative z-10" @click.stop>
                                        <div @click="showEdit = true" class="flex items-center justify-between gap-4 text-xs text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10 px-4 py-3 rounded-xl border border-emerald-200 dark:border-emerald-500/20 w-full transition-colors cursor-pointer hover:bg-emerald-100 dark:hover:bg-emerald-500/20 group" title="Ubah data siswa & kelas">
                                            <span class="flex items-center gap-2 font-bold"><span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse shadow-[0_0_8px_#10b981]"></span> Kelas: {{ $user->class_group }}</span>
                                            <div class="flex items-center gap-3">
                                                @if(isset($classGroup))
                                                    <span class="{{ $classGroup->is_active ? 'text-emerald-600 dark:text-emerald-500' : 'text-red-600 dark:text-red-500' }} font-black">{{ $classGroup->is_active ? 'Aktif' : 'Ditutup' }}</span>
                                                @endif
                                                <div class="p-1 rounded-md bg-emerald-200/50 dark:bg-emerald-500/30 text-emerald-700 dark:text-emerald-300 opacity-0 group-hover:opacity-100 transition-opacity">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endempty
                            </div>
                        </div>
                    </div>
                    {{-- RINGKASAN ANALITIK PEMBELAJARAN --}}
                    <section class="dashboard-fade-up space-y-4" :class="pageReady ? 'is-visible' : ''" style="--reveal-delay: .10s;" aria-labelledby="learning-analytics-heading">
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-[0.18em] text-indigo-600 dark:text-indigo-300">Learning Analytics</p>
                                <h2 id="learning-analytics-heading" class="mt-1 text-lg font-black tracking-tight text-slate-900 dark:text-white">Ringkasan belajar {{ $user->name }}</h2>
                                <p class="mt-1 max-w-2xl text-xs font-semibold leading-5 text-slate-500 dark:text-white/45">Ikhtisar umum progres, nilai, dan catatan pengerjaan agar perkembangan siswa lebih mudah dibaca.</p>
                            </div>
                            
                        </div>

                        <div class="grid gap-4 xl:grid-cols-12">
                            <article class="analytics-panel xl:col-span-3 rounded-2xl p-5 md:p-6">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-white/35">Progres umum</p>
                                            <button type="button" @click.stop data-la-tooltip="Progres umum menggabungkan materi selesai, praktik tuntas, dan evaluasi lulus." class="analytics-info-button" aria-label="Penjelasan progres umum">i</button>
                                        </div>
                                        <p class="mt-1 text-[11px] font-semibold text-slate-500 dark:text-white/45">Aktivitas selesai dibanding seluruh target.</p>
                                    </div>
                                    <button type="button" @click="showGlobalProgressModal = true" class="rounded-xl border border-indigo-100 bg-indigo-50 px-2.5 py-1.5 text-[10px] font-black text-indigo-700 transition hover:border-indigo-300 hover:bg-indigo-100 dark:border-indigo-500/20 dark:bg-indigo-500/10 dark:text-indigo-300 dark:hover:bg-indigo-500/20">Rincian</button>
                                </div>
                                <div class="mt-5 flex items-center gap-4 xl:flex-col xl:items-start 2xl:flex-row 2xl:items-center">
                                    <button type="button" @click="showGlobalProgressModal = true" class="analytics-ring focus:outline-none focus-visible:ring-4 focus-visible:ring-indigo-200 dark:focus-visible:ring-indigo-500/20" style="--progress: {{ $progressPercentForAnalytics }}%;" aria-label="Buka rincian progres belajar">
                                        <span class="analytics-ring__inner text-center">
                                            <strong class="text-lg font-black text-slate-900 dark:text-white">{{ $progressPercentForAnalytics }}%</strong>
                                            <span class="-mt-1 text-[8px] font-black uppercase tracking-widest text-slate-400">Tuntas</span>
                                        </span>
                                    </button>
                                    <div class="min-w-0">
                                        <p class="text-2xl font-black tracking-tight text-slate-900 dark:text-white">{{ number_format($progressCompletedForAnalytics) }}<span class="ml-1 text-sm text-slate-400">/ {{ number_format($progressTargetForAnalytics) }}</span> </p>
                                        <p class="mt-1 text-[11px] font-semibold leading-5 text-slate-500 dark:text-white/45">target pembelajaran sudah tercapai.</p>
                                    </div>
                                </div>
                            </article>

                            <article class="analytics-panel xl:col-span-3 rounded-2xl p-5 md:p-6">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-white/35">Nilai rata-rata</p>
                                            <button type="button" @click.stop data-la-tooltip="Nilai rata-rata diambil dari rekaman evaluasi dan praktik yang tersimpan." class="analytics-info-button" aria-label="Penjelasan nilai rata-rata">i</button>
                                        </div>
                                        <p class="mt-1 text-[11px] font-semibold text-slate-500 dark:text-white/45">Gambaran umum hasil pengerjaan siswa.</p>
                                    </div>
                                </div>
                                <div class="mt-4 grid gap-3">
                                    <div class="analytics-score-tile border-amber-100 bg-amber-50/70 dark:border-amber-500/20 dark:bg-amber-500/[0.08]">
                                        <p>Evaluasi</p>
                                        <strong>{{ number_format($quizAverageForAnalytics, 1) }}<small>/100</small></strong>
                                        <span>{{ number_format($quizRecordCountForAnalytics) }} rekaman</span>
                                    </div>
                                    <div class="analytics-score-tile border-emerald-100 bg-emerald-50/70 dark:border-emerald-500/20 dark:bg-emerald-500/[0.08]">
                                        <p>Praktik</p>
                                        <strong>{{ number_format($labAverageForAnalytics, 1) }}<small>/100</small></strong>
                                        <span>{{ number_format($labRecordCountForAnalytics) }} rekaman</span>
                                    </div>
                                </div>
                            </article>

                            <article class="analytics-panel xl:col-span-3 rounded-2xl p-5 md:p-6">
                                <div class="flex items-center gap-2">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-white/35">Penyelesaian aktivitas</p>
                                    <button type="button" @click.stop data-la-tooltip="Menunjukkan bagian mana yang sudah selesai pada materi, praktik, dan evaluasi." class="analytics-info-button" aria-label="Penjelasan penyelesaian aktivitas">i</button>
                                </div>
                                <dl class="analytics-source-list mt-4">
                                    <div>
                                        <dt>Materi</dt>
                                        <dd><strong>{{ $lessonsDoneForAnalytics }}/{{ $lessonsTotalForAnalytics }}</strong><span>{{ $lessonProgressForAnalytics }}%</span></dd>
                                    </div>
                                    <div>
                                        <dt>Praktik</dt>
                                        <dd><strong>{{ $labsDoneForAnalytics }}/{{ $labsTotalForAnalytics }}</strong><span>{{ $labProgressForAnalytics }}%</span></dd>
                                    </div>
                                    <div>
                                        <dt>Evaluasi</dt>
                                        <dd><strong>{{ $quizzesPassedForAnalytics }}/{{ $quizzesTotalForAnalytics }}</strong><span>{{ $quizProgressForAnalytics }}%</span></dd>
                                    </div>
                                </dl>
                            </article>

                            <article class="analytics-panel xl:col-span-3 rounded-2xl p-5 md:p-6">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-white/35">Catatan evaluasi</p>
                                            <button type="button" @click.stop data-la-tooltip="Catatan ini membantu melihat pola pengerjaan evaluasi, bukan sebagai nilai akhir." class="analytics-info-button" aria-label="Penjelasan catatan evaluasi">i</button>
                                        </div>
                                        <p class="mt-1 text-[11px] font-semibold text-slate-500 dark:text-white/45">{{ number_format($quizRecordCountForAnalytics) }} evaluasi tercatat.</p>
                                    </div>
                                </div>
                                <div class="mt-4 grid grid-cols-3 gap-3">
                                    <div class="analytics-signal-tile"><p>Ragu-ragu</p><strong>{{ number_format($quizFlaggedForAnalytics) }}</strong><span>butir</span></div>
                                    <div class="analytics-signal-tile"><p>Jawaban kosong</p><strong>{{ number_format($quizUnansweredForAnalytics) }}</strong><span>butir</span></div>
                                    <div class="analytics-signal-tile"><p>Pindah fokus</p><strong>{{ number_format($quizFocusLostForAnalytics) }}</strong><span>kejadian</span></div>
                                </div>
                            </article>
                        </div>
                    </section>



                    {{-- INDIKATOR AKADEMIK UTAMA --}}
                    <section class="dashboard-fade-up space-y-4" :class="pageReady ? 'is-visible' : ''" style="--reveal-delay: .18s;" aria-labelledby="academic-indicators-heading">
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-[0.18em] text-slate-400 dark:text-white/35">Penyelesaian aktivitas</p>
                                <h3 id="academic-indicators-heading" class="mt-1 text-base font-black text-slate-900 dark:text-white">Materi, praktik, dan evaluasi</h3>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                            <button type="button" @click="showLessonModal = true" class="analytics-metric-card group" aria-label="Lihat detail materi yang diselesaikan">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex items-center gap-2"><p class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-white/35">Materi selesai</p><span @click.stop data-la-tooltip="Materi selesai adalah jumlah materi yang sudah ditandai selesai dibanding total materi pada sistem." class="analytics-info-button" aria-label="Penjelasan materi selesai">i</span></div>
                                    <span class="analytics-metric-icon inline-flex h-9 w-9 items-center justify-center rounded-xl border border-cyan-100 bg-cyan-50 text-cyan-600 dark:border-cyan-500/20 dark:bg-cyan-500/10 dark:text-cyan-300"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg></span>
                                </div>
                                <p class="mt-5 text-2xl font-black text-slate-900 dark:text-white">{{ $lessonsDoneForAnalytics }}<span class="ml-1 text-sm text-slate-400">/ {{ $lessonsTotalForAnalytics }}</span></p>
                                <p class="mt-1 text-[11px] font-semibold text-slate-500 dark:text-white/45">{{ $lessonProgressForAnalytics }}% · sisa {{ max(0, $lessonsTotalForAnalytics - $lessonsDoneForAnalytics) }} materi</p>
                                <div class="analytics-track mt-4"><span class="bg-cyan-500" style="width: {{ $lessonProgressForAnalytics }}%"></span></div>
                            </button>

                            <button type="button" @click="showLabModal = true" class="analytics-metric-card group" aria-label="Lihat detail praktik lab tuntas">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex items-center gap-2"><p class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-white/35">Praktik tuntas</p><span @click.stop data-la-tooltip="Praktik tuntas adalah jumlah lab dengan status selesai dibanding jumlah target lab. Jumlah percobaan tidak menambah progres." class="analytics-info-button" aria-label="Penjelasan praktik tuntas">i</span></div>
                                    <span class="analytics-metric-icon inline-flex h-9 w-9 items-center justify-center rounded-xl border border-indigo-100 bg-indigo-50 text-indigo-600 dark:border-indigo-500/20 dark:bg-indigo-500/10 dark:text-indigo-300"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg></span>
                                </div>
                                <p class="mt-5 text-2xl font-black text-slate-900 dark:text-white">{{ $labsDoneForAnalytics }}<span class="ml-1 text-sm text-slate-400">/ {{ $labsTotalForAnalytics }}</span></p>
                                <p class="mt-1 text-[11px] font-semibold text-slate-500 dark:text-white/45">{{ $labProgressForAnalytics }}% · sisa {{ max(0, $labsTotalForAnalytics - $labsDoneForAnalytics) }} praktik</p>
                                <div class="analytics-track mt-4"><span class="bg-indigo-500" style="width: {{ $labProgressForAnalytics }}%"></span></div>
                            </button>

                            <button type="button" @click="showQuizModal = true" class="analytics-metric-card group" aria-label="Lihat detail evaluasi yang lulus">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex items-center gap-2"><p class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-white/35">Evaluasi lulus</p><span @click.stop data-la-tooltip="Evaluasi dihitung sebagai tuntas setelah nilainya memenuhi KKM 70. Percobaan ulang tidak dihitung sebagai progres tambahan." class="analytics-info-button" aria-label="Penjelasan evaluasi lulus">i</span></div>
                                    <span class="analytics-metric-icon inline-flex h-9 w-9 items-center justify-center rounded-xl border border-fuchsia-100 bg-fuchsia-50 text-fuchsia-600 dark:border-fuchsia-500/20 dark:bg-fuchsia-500/10 dark:text-fuchsia-300"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg></span>
                                </div>
                                <p class="mt-5 text-2xl font-black text-slate-900 dark:text-white">{{ $quizzesPassedForAnalytics }}<span class="ml-1 text-sm text-slate-400">/ {{ $quizzesTotalForAnalytics }}</span></p>
                                <p class="mt-1 text-[11px] font-semibold text-slate-500 dark:text-white/45">{{ $quizProgressForAnalytics }}% · sisa {{ max(0, $quizzesTotalForAnalytics - $quizzesPassedForAnalytics) }} evaluasi</p>
                                <div class="analytics-track mt-4"><span class="bg-fuchsia-500" style="width: {{ $quizProgressForAnalytics }}%"></span></div>
                            </button>
                        </div>
                    </section>

                    @php
                        $chapterRows = collect($chapterPerformance ?? [])->take(4);
                    @endphp

                    {{-- ANALISIS HASIL EVALUASI --}}
                    <section class="dashboard-fade-up grid gap-4 xl:grid-cols-[1.35fr_.65fr]" :class="pageReady ? 'is-visible' : ''" style="--reveal-delay: .26s;" aria-label="Analisis hasil evaluasi">
                        <article class="analytics-panel rounded-2xl p-5 md:p-6">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                <div>
                                    <p class="text-[10px] font-black uppercase tracking-[0.18em] text-slate-400 dark:text-white/35">Analisis evaluasi</p>
                                    <h3 class="mt-1 text-base font-black text-slate-900 dark:text-white">Capaian per bab</h3>
                                    <p class="mt-1 text-xs font-semibold text-slate-500 dark:text-white/45">Skor terbaik per bab dari seluruh percobaan; lulus apabila skor mencapai KKM 70.</p>
                                </div>
                                <span class="inline-flex w-fit rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 text-[10px] font-black text-slate-500 dark:border-white/10 dark:bg-white/5 dark:text-white/45">{{ $chapterRows->count() }} bab terukur</span>
                            </div>

                            <div class="mt-5 space-y-2.5">
                                @forelse($chapterRows as $row)
                                    @php
                                        $rowScore = (float) ($row['best_score'] ?? 0);
                                        $rowPassed = (bool) ($row['passed'] ?? false);
                                        $rowAttempts = (int) ($row['attempts'] ?? 0);
                                    @endphp
                                    <div class="analytics-performance-row rounded-xl border border-slate-100 p-3 dark:border-white/5">
                                        <div class="flex items-center justify-between gap-3">
                                            <div class="min-w-0">
                                                <p class="truncate text-xs font-black text-slate-800 dark:text-white">{{ $row['label'] ?? 'Bab' }}</p>
                                                <p class="mt-0.5 text-[10px] font-semibold text-slate-500 dark:text-white/45">{{ $rowAttempts }} percobaan · {{ $rowPassed ? 'Memenuhi KKM' : 'Belum memenuhi KKM' }}</p>
                                            </div>
                                            <div class="shrink-0 text-right">
                                                <p class="font-mono text-sm font-black {{ $rowPassed ? 'text-emerald-600 dark:text-emerald-300' : 'text-amber-600 dark:text-amber-300' }}">{{ number_format($rowScore, 0) }}</p>
                                                <p class="text-[9px] font-bold text-slate-400">/100</p>
                                            </div>
                                        </div>
                                        <div class="analytics-track mt-3"><span class="{{ $rowPassed ? 'bg-emerald-500' : 'bg-amber-500' }}" style="width: {{ min(100, max(0, $rowScore)) }}%"></span></div>
                                    </div>
                                @empty
                                    <div class="rounded-xl border border-dashed border-slate-200 px-4 py-8 text-center dark:border-white/10">
                                        <p class="text-xs font-black text-slate-700 dark:text-white">Belum ada evaluasi yang selesai</p>
                                        <p class="mt-1 text-[11px] font-semibold text-slate-500 dark:text-white/45">Data per bab muncul setelah siswa mengumpulkan kuis.</p>
                                    </div>
                                @endforelse
                            </div>
                        </article>

                        <article class="analytics-panel rounded-2xl p-5 md:p-6">
                            <div class="flex items-center gap-2"><p class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-white/35">Sinyal pengerjaan</p><button type="button" @click.stop data-la-tooltip="Sinyal mencatat perilaku saat evaluasi, bukan penentu nilai. Nilai akhir tetap berasal dari jawaban yang benar." class="analytics-info-button" aria-label="Penjelasan sinyal pengerjaan">i</button></div>
                            <h3 class="mt-1 text-sm font-black text-slate-900 dark:text-white">Interaksi selama evaluasi</h3>
                            <div class="mt-4 space-y-2.5">
                                <div class="flex items-center justify-between gap-3 rounded-xl border border-slate-100 bg-slate-50/75 px-3 py-3 dark:border-white/5 dark:bg-white/[0.035]"><span class="text-[11px] font-semibold text-slate-600 dark:text-white/55">Soal ditandai ragu</span><strong class="font-mono text-sm text-slate-900 dark:text-white">{{ $quizFlaggedForAnalytics }} <small class="text-[9px] text-slate-400">butir</small></strong></div>
                                <div class="flex items-center justify-between gap-3 rounded-xl border border-slate-100 bg-slate-50/75 px-3 py-3 dark:border-white/5 dark:bg-white/[0.035]"><span class="text-[11px] font-semibold text-slate-600 dark:text-white/55">Pindah fokus</span><strong class="font-mono text-sm text-slate-900 dark:text-white">{{ $quizFocusLostForAnalytics }} <small class="text-[9px] text-slate-400">kejadian</small></strong></div>
                                <div class="flex items-center justify-between gap-3 rounded-xl border border-slate-100 bg-slate-50/75 px-3 py-3 dark:border-white/5 dark:bg-white/[0.035]"><span class="text-[11px] font-semibold text-slate-600 dark:text-white/55">Jawaban tidak diisi</span><strong class="font-mono text-sm text-slate-900 dark:text-white">{{ $quizUnansweredForAnalytics }} <small class="text-[9px] text-slate-400">butir</small></strong></div>
                            </div>
                            <div class="mt-4 border-t border-slate-100 pt-4 dark:border-white/5">
                                <p class="text-[10px] font-semibold leading-5 text-slate-500 dark:text-white/45">Akumulasi {{ $quizInteractionSignalsForAnalytics }} sinyal dari {{ number_format($quizRecordCountForAnalytics) }} evaluasi yang tercatat.</p>
                            </div>
                        </article>
                    </section>


                    {{-- DETAIL CHART & SUMMARY --}}
                    <div class="grid lg:grid-cols-3 gap-6">
                        <div class="glass-card rounded-2xl p-6 flex flex-col transition-colors">
                            <div class="mb-5 flex items-center gap-2"><h3 class="text-sm font-black text-slate-900 dark:text-white tracking-wide transition-colors">Komponen progres</h3><button type="button" @click.stop data-la-tooltip="Angka pada kartu ini sama dengan komponen pembentuk progres belajar: praktik harus tuntas dan evaluasi harus lulus KKM 70." class="analytics-info-button" aria-label="Penjelasan komponen progres">i</button></div>
                            <div class="flex-1 flex flex-col justify-center space-y-4">
                                <div class="bg-slate-50 dark:bg-white/[0.03] border border-slate-200 dark:border-white/5 rounded-xl p-4 flex items-center justify-between hover:border-cyan-300 dark:hover:border-white/10 transition group/status cursor-default">
                                    <div class="flex items-center gap-4 w-full">
                                        <div class="w-10 h-10 rounded-xl bg-cyan-50 dark:bg-cyan-500/20 border border-cyan-200 dark:border-cyan-500/20 flex items-center justify-center text-cyan-600 dark:text-cyan-300 text-lg shadow-sm dark:shadow-inner group-hover/status:scale-110 transition-colors shrink-0">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                        </div>
                                        <div class="flex-1 pr-4">
                                            <p class="text-xs font-bold text-slate-900 dark:text-white transition-colors">Materi selesai</p>
                                            <div class="flex items-center justify-between w-full mt-2" title="Kemajuan materi">
                                                <div class="w-full bg-slate-200 dark:bg-[#020617] h-1 rounded-full mr-3 shadow-inner">
                                                    <div class="{{ $lessonProgressForAnalytics == 100 ? 'bg-emerald-500' : 'bg-cyan-500' }} h-1 rounded-full transition-all duration-1000" style="width: {{ $lessonProgressForAnalytics }}%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="text-sm font-black transition-colors {{ $lessonsDoneForAnalytics >= $lessonsTotalForAnalytics ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-400 dark:text-slate-300' }} shrink-0">{{ $lessonsDoneForAnalytics }}/{{ $lessonsTotalForAnalytics }}</span>
                                </div>
                                <div class="bg-slate-50 dark:bg-white/[0.03] border border-slate-200 dark:border-white/5 rounded-xl p-4 flex items-center justify-between hover:border-indigo-300 dark:hover:border-white/10 transition group/status cursor-default">
                                    <div class="flex items-center gap-4 w-full">
                                        <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-500/20 border border-indigo-200 dark:border-indigo-500/20 flex items-center justify-center text-indigo-600 dark:text-indigo-400 text-lg shadow-sm dark:shadow-inner group-hover/status:scale-110 transition-colors shrink-0">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                                        </div>
                                        <div class="flex-1 pr-4">
                                            <p class="text-xs font-bold text-slate-900 dark:text-white transition-colors">Praktik tuntas</p>
                                            @php $pLabs = ($totalLabs ?? 4) > 0 ? (($labsCompleted ?? ($labStats['total'] ?? 0)) / ($totalLabs ?? 4)) * 100 : 0; @endphp
                                            <div class="flex items-center justify-between w-full mt-2" title="Kemajuan Praktikum">
                                                <div class="w-full bg-slate-200 dark:bg-[#020617] h-1 rounded-full mr-3 shadow-inner">
                                                    <div class="{{ $pLabs == 100 ? 'bg-emerald-500' : 'bg-indigo-500' }} h-1 rounded-full transition-all duration-1000" style="width: {{ $pLabs }}%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="text-sm font-black transition-colors {{ ($labsCompleted ?? $labStats['total'] ?? 0) >= ($totalLabs ?? 4) ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-400 dark:text-slate-300' }} shrink-0">{{ $labsCompleted ?? ($labStats['total'] ?? 0) }}/{{ $totalLabs ?? 4 }}</span>
                                </div>
                                <div class="bg-slate-50 dark:bg-white/[0.03] border border-slate-200 dark:border-white/5 rounded-xl p-4 flex items-center justify-between hover:border-fuchsia-300 dark:hover:border-white/10 transition group/status cursor-default">
                                    <div class="flex items-center gap-4 w-full">
                                        <div class="w-10 h-10 rounded-xl bg-fuchsia-50 dark:bg-fuchsia-500/20 border border-fuchsia-200 dark:border-fuchsia-500/20 flex items-center justify-center text-fuchsia-600 dark:text-fuchsia-400 text-lg shadow-sm dark:shadow-inner group-hover/status:scale-110 transition-colors shrink-0">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                                        </div>
                                        <div class="flex-1 pr-4">
                                            <p class="text-xs font-bold text-slate-900 dark:text-white transition-colors">Evaluasi lulus</p>
                                            @php 
                                                $qC = count(array_filter($quizScoresMap ?? [], fn($s) => $s >= 70));
                                                $pQuiz = ($totalQuizzes ?? 4) > 0 ? ($qC / ($totalQuizzes ?? 4)) * 100 : 0; 
                                            @endphp
                                            <div class="flex items-center justify-between w-full mt-2" title="Kemajuan Ujian Kuis">
                                                <div class="w-full bg-slate-200 dark:bg-[#020617] h-1 rounded-full mr-3 shadow-inner">
                                                    <div class="{{ $pQuiz == 100 ? 'bg-emerald-500' : 'bg-fuchsia-500' }} h-1 rounded-full transition-all duration-1000" style="width: {{ $pQuiz }}%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="text-sm font-black transition-colors {{ $qC >= ($totalQuizzes ?? 4) ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-400 dark:text-slate-300' }} shrink-0">{{ $qC }}/{{ $totalQuizzes ?? 4 }}</span>
                                </div>
                                <div class="bg-cyan-50/50 dark:bg-cyan-500/5 border border-cyan-200 dark:border-cyan-500/20 rounded-xl p-4 flex items-center justify-between hover:border-cyan-400 dark:hover:border-cyan-500/30 transition group/status cursor-default cursor-pointer" @click="showGlobalProgressModal = true" title="Klik untuk rincian formula progres keseluruhan">
                                    <div class="flex items-center gap-4 w-full">
                                        <div class="w-10 h-10 rounded-xl bg-cyan-100 dark:bg-cyan-500/20 border border-cyan-300 dark:border-cyan-500/30 flex items-center justify-center text-cyan-600 dark:text-cyan-400 text-lg shadow-sm dark:shadow-[0_0_15px_rgba(34,211,238,0.2)] group-hover/status:rotate-12 transition-colors shrink-0">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" /></svg>
                                        </div>
                                        <div class="flex-1 pr-4">
                                            <p class="text-xs font-bold text-slate-900 dark:text-white transition-colors">Progres belajar</p>
                                            <div class="flex items-center justify-between w-full mt-2">
                                                <div class="w-full bg-cyan-100 dark:bg-[#020617] h-1 rounded-full mr-3 shadow-inner">
                                                    <div class="bg-cyan-500 h-1 rounded-full shadow-[0_0_5px_currentColor] transition-all duration-1000" style="width: {{ $progressPercentForAnalytics }}%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="text-lg font-black text-cyan-600 dark:text-cyan-400 drop-shadow-sm dark:drop-shadow-[0_0_5px_rgba(34,211,238,0.5)] transition-colors shrink-0">{{ $progressPercentForAnalytics }}%</span>
                                </div>
                            </div>
                        </div>

                        <div class="lg:col-span-2 glass-card rounded-2xl p-6 relative flex flex-col transition-colors">
                            <div class="flex justify-between items-center mb-6">
                                <div>
                                    <h3 class="text-sm font-black text-slate-900 dark:text-white tracking-wide transition-colors">Tren Performa Lab</h3>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 transition-colors">Visualisasi skor dari 10 modul praktik terakhir (Sumbu Y = Skor 0-100, Sumbu X = Nama Modul).</p>
                                </div>
                                <span class="px-3 py-1.5 rounded-lg bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 text-[10px] font-bold border border-indigo-200 dark:border-indigo-500/20 flex items-center gap-1.5 shadow-sm dark:shadow-inner transition-colors cursor-default">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
                                    Grafik Nilai
                                </span>
                            </div>
                            <div class="flex-1 min-h-[250px] w-full relative">
                                @if(isset($chartScores) && count($chartScores) > 0)
                                    <canvas id="scoreChart"></canvas>
                                @else
                                    <div class="absolute inset-0 flex flex-col items-center justify-center border-2 border-dashed border-slate-200 dark:border-white/5 rounded-xl bg-slate-50 dark:bg-white/[0.01] transition-colors">
                                        <svg class="w-8 h-8 text-slate-400 dark:text-slate-600 mb-3 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                                        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 transition-colors">Belum ada data grafik</p>
                                        <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-1 transition-colors">Siswa belum menyelesaikan praktik lab dengan status lulus.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ============================== --}}
                {{-- TAB 2: CURRICULUM TRACKER --}}
                {{-- ============================== --}}
                <div x-show="activeTab === 'curriculum'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;" x-cloak>
                    
                    {{-- HERO TAB 2 --}}
                    <div class="flex items-center gap-2.5 mb-6 ml-2">
                        <h2 class="text-lg font-black text-slate-800 dark:text-white tracking-tight">Capaian Kurikulum</h2>
                        <div class="insight-tooltip insight-right">
                            <span class="insight-trigger">?</span>
                            <div class="insight-content text-left">Fitur ini melacak progres materi (slide), kelulusan lab praktikum, dan status evaluasi untuk setiap bab pembelajaran secara mendetail.</div>
                        </div>
                    </div>

                    <div class="grid lg:grid-cols-3 gap-6 mb-8">
                        @foreach($curriculumMap as $index => $chapter)
                            @php
                                $labDone = in_array($chapter['lab_id'], $passedLabIds ?? []);
                                $quizScore = $quizScoresMap['quiz_' . $chapter['quiz_key']] ?? null;
                                $quizPass = ($quizScore !== null && $quizScore >= 70);
                                $totalLessonIds = 0; $completedLessonCount = 0;
                                foreach($chapter['topics'] as $t) {
                                    $totalLessonIds += count($t['ids']);
                                    $completedLessonCount += count(array_intersect($t['ids'], $completedLessonIds ?? []));
                                }
                                $totalWeight = $totalLessonIds + 20; 
                                $currentWeight = $completedLessonCount + ($labDone ? 10 : 0) + ($quizPass ? 10 : 0);
                                $chapterPercent = min(round(($currentWeight / $totalWeight) * 100), 100);
                            @endphp

                            <div class="glass-card rounded-2xl overflow-hidden flex flex-col relative group h-full hover:border-{{ $chapter['color'] }}-400 dark:hover:border-{{ $chapter['color'] }}-500/40 transition-colors" style="animation-delay: {{ $index * 100 }}ms">
                                <div class="absolute top-0 left-0 h-1.5 bg-{{ $chapter['color'] }}-500 transition-all duration-1000 shadow-[0_0_10px_currentColor]" :style="showProgress ? 'width: {{ $chapterPercent }}%' : 'width: 0%'"></div>
                                
                                <div class="px-6 py-5 border-b border-slate-200 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.01] flex justify-between items-center group-hover:bg-{{ $chapter['color'] }}-50 dark:group-hover:bg-{{ $chapter['color'] }}-500/5 transition-colors">
                                    <div class="flex items-center gap-3">
                                        <span class="text-[10px] font-black px-2.5 py-1 rounded bg-{{ $chapter['color'] }}-100 dark:bg-{{ $chapter['color'] }}-500/10 text-{{ $chapter['color'] }}-700 dark:text-{{ $chapter['color'] }}-400 border border-{{ $chapter['color'] }}-200 dark:border-{{ $chapter['color'] }}-500/20 shadow-sm dark:shadow-inner transition-colors">BAB {{ $chapter['number'] }}</span>
                                        <h4 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2 transition-colors">
                                            {{ $chapter['title'] }}
                                            @if($chapterPercent == 100) <svg class="w-4 h-4 text-emerald-500 dark:text-emerald-400 drop-shadow-sm dark:drop-shadow-[0_0_5px_#10b981] transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> @endif
                                        </h4>
                                    </div>
                                    <span class="text-xs font-mono font-bold text-{{ $chapter['color'] }}-600 dark:text-{{ $chapter['color'] }}-400 transition-colors" x-data="{ p: 0 }" x-init="let i = setInterval(() => { if(p < {{ $chapterPercent }}) p++; else clearInterval(i); }, 20);" x-text="p + '%'"></span>
                                </div>

                                <div class="p-6 flex-1 flex flex-col gap-6">
                                    <div class="space-y-4 relative">
                                        <div class="absolute left-[7px] top-2 bottom-2 w-px border-l-2 border-dashed border-slate-200 dark:border-white/10 group-hover:border-{{ $chapter['color'] }}-300 dark:group-hover:border-{{ $chapter['color'] }}-500/20 transition-colors"></div>
                                        
                                        @foreach($chapter['topics'] as $topic)
                                            @php 
                                                $missingIds = array_diff($topic['ids'], $completedLessonIds ?? []);
                                                $isTopicDone = empty($missingIds);
                                                $partial = count($topic['ids']) - count($missingIds);
                                                $total = count($topic['ids']);
                                                $progressW = ($partial/$total)*100;
                                            @endphp
                                            <div class="relative pl-6 flex items-center justify-between group/item hover:bg-slate-50 dark:hover:bg-white/[0.02] p-1.5 -ml-1.5 rounded-lg transition-colors cursor-default" title="Tersisa {{ $total - $partial }} slide untuk diselesaikan">
                                                <div class="flex items-center gap-3">
                                                    <div class="absolute left-[3.5px] top-3 w-2.5 h-2.5 rounded-full border-[2px] border-white dark:border-[#0f141e] {{ $isTopicDone ? 'bg-emerald-500 shadow-sm dark:shadow-[0_0_8px_#10b981]' : 'bg-slate-300 dark:bg-slate-700' }} transition-colors duration-300"></div>
                                                    <div class="flex flex-col">
                                                        <span class="text-[13px] font-semibold transition-colors duration-300 {{ $isTopicDone ? 'text-slate-900 dark:text-white' : 'text-slate-500 dark:text-slate-400' }}">{{ $topic['name'] }}</span>
                                                        <div class="flex items-center gap-2 mt-0.5">
                                                            <div class="w-16 h-1 bg-slate-200 dark:bg-white/10 rounded-full overflow-hidden transition-colors">
                                                                <div class="h-full bg-slate-400 dark:bg-slate-500 rounded-full {{ $isTopicDone ? 'bg-emerald-500 dark:bg-emerald-400' : '' }} transition-all duration-1000" style="width: {{ $progressW }}%"></div>
                                                            </div>
                                                            <span class="text-[9px] font-mono {{ $isTopicDone ? 'text-emerald-600 dark:text-emerald-500/70' : 'text-slate-400 dark:text-slate-500' }} transition-colors">{{ $partial }}/{{ $total }} slide</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if($isTopicDone) <span class="text-[9px] font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10 px-1.5 py-0.5 rounded uppercase border border-emerald-200 dark:border-emerald-500/20 transition-colors">Done</span> @endif
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="mt-auto space-y-3 pt-5 border-t border-slate-200 dark:border-white/5 transition-colors">
                                        <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50 dark:bg-white/[0.02] hover:bg-slate-100 dark:hover:bg-white/[0.04] transition-colors" title="Bobot Praktik: Tambahan persentase jika Lulus">
                                            <div class="flex items-center gap-2.5 text-xs text-slate-700 dark:text-slate-300 font-medium transition-colors">
                                                <div class="w-6 h-6 rounded bg-{{ $chapter['color'] }}-100 dark:bg-{{ $chapter['color'] }}-500/20 flex items-center justify-center text-{{ $chapter['color'] }}-600 dark:text-{{ $chapter['color'] }}-400 text-xs shadow-sm dark:shadow-inner transition-colors">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                                                </div>
                                                {{ $chapter['lab_name'] }}
                                            </div>
                                            <span class="text-[10px] font-black px-2 py-0.5 rounded transition-colors {{ $labDone ? 'text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20' : 'text-slate-500 bg-slate-200 dark:bg-slate-800/50' }}">
                                                {{ $labDone ? 'LULUS' : 'PENDING' }}
                                            </span>
                                        </div>
                                        <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50 dark:bg-white/[0.02] hover:bg-slate-100 dark:hover:bg-white/[0.04] transition-colors" title="Bobot Evaluasi: Tambahan persentase jika Skor >= 70">
                                            <div class="flex items-center gap-2.5 text-xs text-slate-700 dark:text-slate-300 font-medium transition-colors">
                                                <div class="w-6 h-6 rounded bg-{{ $chapter['color'] }}-100 dark:bg-{{ $chapter['color'] }}-500/20 flex items-center justify-center text-{{ $chapter['color'] }}-600 dark:text-{{ $chapter['color'] }}-400 text-xs shadow-sm dark:shadow-inner transition-colors">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                                                </div>
                                                Evaluasi Bab
                                            </div>
                                            <span class="text-[10px] font-black px-2 py-0.5 rounded transition-colors {{ $quizPass ? 'text-fuchsia-600 dark:text-fuchsia-400 bg-fuchsia-50 dark:bg-fuchsia-500/10 border border-fuchsia-200 dark:border-fuchsia-500/20' : 'text-slate-500 bg-slate-200 dark:bg-slate-800/50' }}">
                                                {{ $quizScore !== null ? 'SKOR: '.$quizScore : 'BELUM' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- ============================== --}}
                {{-- TAB 3: RIWAYAT AKTIVITAS --}}
                {{-- ============================== --}}
                <div x-show="activeTab === 'history'"
                     x-transition:enter="transition ease-linear duration-500"
                     x-transition:enter-start="opacity-0 translate-y-3"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="history-shell"
                     style="display: none;"
                     x-cloak>

                    @php
                        $labHistoryItems = $labHistories->values();
                        $quizHistoryItems = $quizAttempts->values();

                        $labHistoryPassedCount = $labHistoryItems->filter(function ($history) {
                            return in_array(strtolower(trim((string) ($history->status ?? ''))), ['passed', 'lulus'], true);
                        })->count();

                        $quizHistoryPassedCount = $quizHistoryItems->filter(function ($attempt) {
                            return (float) ($attempt->score ?? 0) >= 70;
                        })->count();

                        $labHistoryAverage = $labHistoryItems->count() > 0
                            ? round((float) $labHistoryItems->avg(fn ($history) => (float) ($history->final_score ?? 0)), 1)
                            : 0;

                        $quizHistoryAverage = $quizHistoryItems->count() > 0
                            ? round((float) $quizHistoryItems->avg(fn ($attempt) => (float) ($attempt->score ?? 0)), 1)
                            : 0;

                        $labHistoryNotPassedCount = max(0, $labHistoryItems->count() - $labHistoryPassedCount);
                        $quizHistoryNotPassedCount = max(0, $quizHistoryItems->count() - $quizHistoryPassedCount);
                        $labHistoryPassRate = $labHistoryItems->count() > 0
                            ? round(($labHistoryPassedCount / $labHistoryItems->count()) * 100)
                            : 0;
                        $quizHistoryPassRate = $quizHistoryItems->count() > 0
                            ? round(($quizHistoryPassedCount / $quizHistoryItems->count()) * 100)
                            : 0;

                        // Indikator pendukung untuk membaca riwayat tanpa mengubah sumber data utama.
                        $labHistoryAverageDurationSeconds = $labHistoryItems->count() > 0
                            ? (int) round($labHistoryItems->avg(fn ($history) => (int) ($history->duration_seconds ?? 0)))
                            : 0;
                        $quizHistoryAverageDurationSeconds = $quizHistoryItems->count() > 0
                            ? (int) round($quizHistoryItems->avg(fn ($attempt) => (int) ($attempt->time_spent_seconds ?? 0)))
                            : 0;

                        $quizHistoryFlaggedCount = (int) $quizHistoryItems->sum(fn ($attempt) => (int) ($attempt->flagged_count ?? 0));
                        $quizHistoryUnansweredCount = (int) $quizHistoryItems->sum(fn ($attempt) => (int) ($attempt->unanswered_count ?? 0));
                        $quizHistoryFocusLostCount = (int) $quizHistoryItems->sum(fn ($attempt) => (int) ($attempt->focus_lost_count ?? 0));
                    @endphp

                    <div class="history-fade-up flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between" :class="historyReady ? 'is-visible' : ''" style="--history-delay: .03s;">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-[0.18em] text-indigo-600 dark:text-indigo-300">Riwayat aktivitas</p>
                            <h2 class="mt-1 text-lg font-black tracking-tight text-slate-900 dark:text-white">Praktik lab dan evaluasi kuis</h2>
                        </div>
                        <span class="inline-flex w-fit items-center rounded-lg border border-slate-200 bg-white/70 px-3 py-2 text-[10px] font-black uppercase tracking-[0.12em] text-slate-500 shadow-sm dark:border-white/10 dark:bg-white/[0.035] dark:text-white/45">
                            {{ number_format($labHistoryItems->count() + $quizHistoryItems->count()) }} aktivitas tercatat
                        </span>
                    </div>

                    <section class="history-fade-up history-summary-grid" :class="historyReady ? 'is-visible' : ''" style="--history-delay: .10s;" aria-label="Ringkasan analitik riwayat aktivitas">
                        <article class="history-summary-card">
                            <div class="history-summary-head">
                                <div class="history-summary-title">
                                    <span class="history-summary-icon border border-indigo-100 bg-indigo-50 text-indigo-600 dark:border-indigo-500/20 dark:bg-indigo-500/10 dark:text-indigo-300">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                    </span>
                                    <div>
                                        <div class="flex items-center gap-1.5"><p class="history-summary-label">Praktik Lab</p><button type="button" @click.stop data-la-tooltip="Satu catatan mewakili satu percobaan praktik. Kelulusan dihitung dari jumlah catatan berstatus lulus dibanding total percobaan praktik." class="analytics-info-button" aria-label="Penjelasan data praktik lab">i</button></div>
                                        <p class="history-summary-basis">Basis: {{ number_format($labHistoryItems->count()) }} percobaan praktik</p>
                                    </div>
                                </div>
                                <span class="history-summary-count border border-indigo-100 bg-indigo-50 text-indigo-700 dark:border-indigo-500/20 dark:bg-indigo-500/10 dark:text-indigo-300">{{ number_format($labHistoryItems->count()) }} kali percobaan</span>
                            </div>
                            <div class="history-summary-body">
                                <div class="history-summary-result">
                                    <div>
                                        <span class="history-summary-result-label">Lulus</span>
                                        <strong class="history-summary-result-value">{{ number_format($labHistoryPassedCount) }}<small>dari {{ number_format($labHistoryItems->count()) }} kali</small></strong>
                                    </div>
                                    <div class="history-summary-rate"><strong>{{ $labHistoryPassRate }}%</strong><span>Kelulusan</span></div>
                                </div>
                                <div class="history-composition mt-3" aria-label="Komposisi kelulusan praktik lab"><span class="bg-indigo-500" style="width: {{ $labHistoryPassRate }}%"></span><span class="bg-slate-200 dark:bg-white/10" style="width: {{ 100 - $labHistoryPassRate }}%"></span></div>
                                <div class="history-composition-legend"><span><i class="bg-indigo-500"></i>Lulus {{ number_format($labHistoryPassedCount) }}</span><span><i class="bg-slate-300 dark:bg-white/25"></i>Belum lulus {{ number_format($labHistoryNotPassedCount) }}</span></div>
                                <dl class="history-summary-data">
                                    <div><dt>Skor rata-rata</dt><dd>{{ number_format($labHistoryAverage, 1) }}<small> /100</small></dd></div>
                                    <div><dt>Durasi rata-rata</dt><dd>{{ formatTime($labHistoryAverageDurationSeconds) }}</dd></div>
                                </dl>
                            </div>
                        </article>

                        <article class="history-summary-card">
                            <div class="history-summary-head">
                                <div class="history-summary-title">
                                    <span class="history-summary-icon border border-fuchsia-100 bg-fuchsia-50 text-fuchsia-600 dark:border-fuchsia-500/20 dark:bg-fuchsia-500/10 dark:text-fuchsia-300">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                    </span>
                                    <div>
                                        <div class="flex items-center gap-1.5"><p class="history-summary-label">Evaluasi Kuis</p><button type="button" @click.stop data-la-tooltip="Satu catatan mewakili satu evaluasi yang dikumpulkan. Kelulusan dihitung dari skor minimal 70 dibanding total evaluasi yang tercatat." class="analytics-info-button" aria-label="Penjelasan data evaluasi kuis">i</button></div>
                                        <p class="history-summary-basis">Basis: {{ number_format($quizHistoryItems->count()) }} evaluasi yang dikumpulkan</p>
                                    </div>
                                </div>
                                <span class="history-summary-count border border-fuchsia-100 bg-fuchsia-50 text-fuchsia-700 dark:border-fuchsia-500/20 dark:bg-fuchsia-500/10 dark:text-fuchsia-300">{{ number_format($quizHistoryItems->count()) }} kali percobaan</span>
                            </div>
                            <div class="history-summary-body">
                                <div class="history-summary-result">
                                    <div>
                                        <span class="history-summary-result-label">Lulus</span>
                                        <strong class="history-summary-result-value">{{ number_format($quizHistoryPassedCount) }}<small>dari {{ number_format($quizHistoryItems->count()) }} kali</small></strong>
                                    </div>
                                    <div class="history-summary-rate"><strong>{{ $quizHistoryPassRate }}%</strong><span>Kelulusan</span></div>
                                </div>
                                <div class="history-composition mt-3" aria-label="Komposisi kelulusan evaluasi kuis"><span class="bg-fuchsia-500" style="width: {{ $quizHistoryPassRate }}%"></span><span class="bg-slate-200 dark:bg-white/10" style="width: {{ 100 - $quizHistoryPassRate }}%"></span></div>
                                <div class="history-composition-legend"><span><i class="bg-fuchsia-500"></i>Lulus {{ number_format($quizHistoryPassedCount) }}</span><span><i class="bg-slate-300 dark:bg-white/25"></i>Belum lulus {{ number_format($quizHistoryNotPassedCount) }}</span></div>
                                <dl class="history-summary-data">
                                    <div><dt>Skor rata-rata</dt><dd>{{ number_format($quizHistoryAverage, 1) }}<small> /100</small></dd></div>
                                    <div><dt>Durasi rata-rata</dt><dd>{{ formatTime($quizHistoryAverageDurationSeconds) }}</dd></div>
                                </dl>
                                <p class="history-signal-label">Sinyal pengerjaan · basis {{ number_format($quizHistoryItems->count()) }} evaluasi</p>
                                <div class="history-signal-grid">
                                    <div><span>Ragu-ragu</span><strong>{{ number_format($quizHistoryFlaggedCount) }} <small>butir</small></strong></div>
                                    <div><span>Jawaban kosong</span><strong>{{ number_format($quizHistoryUnansweredCount) }} <small>butir</small></strong></div>
                                    <div><span>Pindah fokus</span><strong>{{ number_format($quizHistoryFocusLostCount) }} <small>kejadian</small></strong></div>
                                </div>
                            </div>
                        </article>
                    </section>

                    {{-- RIWAYAT PRAKTIK LAB --}}
                    <section class="history-fade-up history-panel overflow-hidden rounded-2xl" :class="historyReady ? 'is-visible' : ''" style="--history-delay: .24s;">
                        <div class="history-panel-head flex flex-col gap-4 border-b border-slate-200 p-5 dark:border-white/5 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg border border-indigo-100 bg-indigo-50 text-indigo-600 dark:border-indigo-500/20 dark:bg-indigo-500/10 dark:text-indigo-300">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                    </span>
                                    <h3 class="text-base font-black text-slate-900 dark:text-white">Riwayat Praktik Lab</h3>
                                </div>
                                <div class="mt-2 flex flex-wrap gap-2">
                                    <span class="history-header-chip">{{ number_format($labHistoryItems->count()) }} kali praktik</span>
                                    <span class="history-header-chip">{{ number_format($labHistoryPassedCount) }} lulus</span>
                                    <span class="history-header-chip">Rata-rata {{ number_format($labHistoryAverage, 1) }}/100</span>
                                    <span class="history-header-chip">KKM 70</span>
                                </div>
                            </div>
                            <label class="relative block w-full sm:w-60">
                                <span class="sr-only">Cari riwayat praktik lab</span>
                                <input type="text"
                                       x-model="searchLab"
                                       placeholder="Cari praktik lab"
                                       class="w-full rounded-xl border border-slate-200 bg-white py-2.5 pl-9 pr-3 text-xs font-medium text-slate-700 outline-none transition focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 dark:border-white/10 dark:bg-[#020617] dark:text-white dark:focus:border-indigo-400 dark:focus:ring-indigo-500/15">
                                <svg class="absolute left-3 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m1.35-5.15a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"/>
                                </svg>
                            </label>
                        </div>

                        <div class="overflow-x-auto custom-scrollbar">
                            <table class="history-table w-full min-w-[860px] text-left">
                                <thead class="border-b border-slate-200 bg-slate-50/70 text-[10px] font-black uppercase tracking-[0.14em] text-slate-400 dark:border-white/5 dark:bg-white/[0.025] dark:text-white/35">
                                    <tr>
                                        <th class="w-12 px-5 py-3 text-center">#</th>
                                        <th class="px-5 py-3">Aktivitas</th>
                                        <th class="px-5 py-3 text-center">Status</th>
                                        <th class="px-5 py-3 text-center">Skor /100</th>
                                        <th class="px-5 py-3">Durasi</th>
                                        <th class="px-5 py-3">Waktu</th>
                                        <th class="px-5 py-3 text-right">Detail</th>
                                    </tr>
                                </thead>
                                @forelse($labHistoryItems as $idx => $history)
                                        @php
                                            $labTitle = $history->lab->title ?? ('Lab #' . ($history->lab_id ?? '-'));
                                            $labStatusRaw = strtolower(trim((string) ($history->status ?? '')));
                                            $labDuration = (int) ($history->duration_seconds ?? 0);
                                            $labLimitSeconds = isset($history->lab->time_limit)
                                                ? (int) $history->lab->time_limit * 60
                                                : (isset($history->lab->duration) ? (int) $history->lab->duration * 60 : 0);
                                            $labTimedOut = $labStatusRaw === 'timeout'
                                                || $labStatusRaw === 'waktu habis'
                                                || (int) ($history->is_timeout ?? 0) === 1
                                                || ($labLimitSeconds > 0 && $labDuration >= $labLimitSeconds)
                                                || $labDuration > 43200;
                                            $labPassed = in_array($labStatusRaw, ['passed', 'lulus'], true);
                                            $labStatusLabel = $labTimedOut ? 'Waktu habis' : ($labPassed ? 'Lulus' : 'Belum lulus');
                                            $labStatusClass = $labTimedOut
                                                ? 'border-amber-200 bg-amber-50 text-amber-700 dark:border-amber-500/20 dark:bg-amber-500/10 dark:text-amber-300'
                                                : ($labPassed
                                                    ? 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-300'
                                                    : 'border-rose-200 bg-rose-50 text-rose-700 dark:border-rose-500/20 dark:bg-rose-500/10 dark:text-rose-300');
                                            $labScore = is_numeric($history->final_score ?? null) ? number_format((float) $history->final_score, 1) : '-';
                                            $labSearchText = strtolower($labTitle . ' ' . $labStatusLabel);
                                        @endphp
                                        <tbody x-data="{ expanded: false }"
                                               x-show='searchLab === "" || @js($labSearchText).includes(searchLab.toLowerCase())' 
                                               class="divide-y divide-slate-100 dark:divide-white/[0.055]">
                                            <tr class="transition hover:bg-indigo-50/40 dark:hover:bg-indigo-500/[0.055]">
                                                <td class="px-5 py-4 text-center font-mono text-xs text-slate-400">{{ $idx + 1 }}</td>
                                                <td class="px-5 py-4">
                                                    <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $labTitle }}</p>
                                                    <p class="mt-0.5 text-[10px] font-semibold text-slate-400 dark:text-white/35">Praktik ke-{{ $idx + 1 }} · skor akhir</p>
                                                </td>
                                                <td class="px-5 py-4 text-center">
                                                    <span class="inline-flex rounded-lg border px-2.5 py-1 text-[10px] font-black uppercase tracking-wider {{ $labStatusClass }}">{{ $labStatusLabel }}</span>
                                                </td>
                                                <td class="px-5 py-4 text-center">
                                                    <span class="font-mono text-sm font-black text-slate-900 dark:text-white">{{ $labScore }}</span>
                                                </td>
                                                <td class="px-5 py-4 text-xs font-semibold text-slate-600 dark:text-white/55">{{ formatTime($labDuration) }}</td>
                                                <td class="px-5 py-4">
                                                    <p class="text-xs font-semibold text-slate-700 dark:text-white/70">{{ \Carbon\Carbon::parse($history->created_at)->format('d M Y, H:i') }} WIB</p>
                                                    <p class="mt-0.5 text-[10px] text-slate-400 dark:text-white/35">{{ \Carbon\Carbon::parse($history->created_at)->diffForHumans() }}</p>
                                                </td>
                                                <td class="px-5 py-4 text-right">
                                                    @if(filled($history->last_code_snapshot ?? null))
                                                        <button type="button"
                                                                @click="expanded = !expanded"
                                                                class="inline-flex items-center gap-1.5 rounded-lg border border-indigo-200 bg-indigo-50 px-3 py-2 text-[10px] font-black text-indigo-700 transition hover:border-indigo-300 hover:bg-indigo-100 dark:border-indigo-500/20 dark:bg-indigo-500/10 dark:text-indigo-300 dark:hover:bg-indigo-500/20">
                                                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                                                            <span x-text="expanded ? 'Tutup kode' : 'Lihat kode'"></span>
                                                        </button>
                                                    @else
                                                        <span class="text-[10px] font-semibold text-slate-400 dark:text-white/30">Tidak ada kode</span>
                                                    @endif
                                                </td>
                                            </tr>

                                            @if(filled($history->last_code_snapshot ?? null))
                                                <tr x-show="expanded" x-cloak class="bg-slate-50/70 dark:bg-[#020617]/45">
                                                    <td colspan="7" class="px-5 py-5">
                                                        <div x-show="expanded" x-collapse class="overflow-hidden rounded-xl border border-slate-200 bg-[#0f172a] dark:border-white/10">
                                                            <div class="flex items-center justify-between border-b border-white/10 px-4 py-3">
                                                                <p class="text-[10px] font-black uppercase tracking-[0.14em] text-slate-300">Kode terakhir</p>
                                                                <button type="button"
                                                                        x-data="{ copied: false }"
                                                                        @click='navigator.clipboard.writeText(@js($history->last_code_snapshot)); copied = true; setTimeout(() => copied = false, 1600)' 
                                                                        class="rounded-lg border border-white/10 px-2.5 py-1.5 text-[10px] font-bold text-slate-300 transition hover:bg-white/10 hover:text-white">
                                                                    <span x-text="copied ? 'Tersalin' : 'Salin'"></span>
                                                                </button>
                                                            </div>
                                                            <pre class="max-h-72 overflow-auto p-4 text-xs leading-6 text-cyan-50 custom-scrollbar"><code>{{ $history->last_code_snapshot }}</code></pre>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    @empty
                                        <tbody>
                                            <tr>
                                                <td colspan="7" class="px-5 py-12 text-center text-sm font-medium text-slate-500 dark:text-white/40">Belum ada riwayat praktik lab.</td>
                                            </tr>
                                        </tbody>
                                    @endforelse
                            </table>
                        </div>
                    </section>

                    {{-- RIWAYAT EVALUASI KUIS --}}
                    <section class="history-fade-up history-panel overflow-hidden rounded-2xl" :class="historyReady ? 'is-visible' : ''" style="--history-delay: .32s;">
                        <div class="history-panel-head flex flex-col gap-4 border-b border-slate-200 p-5 dark:border-white/5 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg border border-fuchsia-100 bg-fuchsia-50 text-fuchsia-600 dark:border-fuchsia-500/20 dark:bg-fuchsia-500/10 dark:text-fuchsia-300">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                    </span>
                                    <h3 class="text-base font-black text-slate-900 dark:text-white">Riwayat Evaluasi Kuis</h3>
                                </div>
                                <div class="mt-2 flex flex-wrap gap-2">
                                    <span class="history-header-chip">{{ number_format($quizHistoryItems->count()) }} kali evaluasi</span>
                                    <span class="history-header-chip">{{ number_format($quizHistoryPassedCount) }} lulus</span>
                                    <span class="history-header-chip">Rata-rata {{ number_format($quizHistoryAverage, 1) }}/100</span>
                                    <span class="history-header-chip">KKM 70</span>
                                </div>
                            </div>
                            <label class="relative block w-full sm:w-60">
                                <span class="sr-only">Cari riwayat evaluasi kuis</span>
                                <input type="text"
                                       x-model="searchQuiz"
                                       placeholder="Cari evaluasi"
                                       class="w-full rounded-xl border border-slate-200 bg-white py-2.5 pl-9 pr-3 text-xs font-medium text-slate-700 outline-none transition focus:border-fuchsia-400 focus:ring-2 focus:ring-fuchsia-100 dark:border-white/10 dark:bg-[#020617] dark:text-white dark:focus:border-fuchsia-400 dark:focus:ring-fuchsia-500/15">
                                <svg class="absolute left-3 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m1.35-5.15a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"/>
                                </svg>
                            </label>
                        </div>

                        <div class="overflow-x-auto custom-scrollbar">
                            <table class="history-table w-full min-w-[860px] text-left">
                                <thead class="border-b border-slate-200 bg-slate-50/70 text-[10px] font-black uppercase tracking-[0.14em] text-slate-400 dark:border-white/5 dark:bg-white/[0.025] dark:text-white/35">
                                    <tr>
                                        <th class="w-12 px-5 py-3 text-center">#</th>
                                        <th class="px-5 py-3">Aktivitas</th>
                                        <th class="px-5 py-3 text-center">Status</th>
                                        <th class="px-5 py-3 text-center">Skor /100</th>
                                        <th class="px-5 py-3">Durasi</th>
                                        <th class="px-5 py-3">Waktu</th>
                                        <th class="px-5 py-3 text-right">Detail</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-white/[0.055]">
                                    @forelse($quizHistoryItems as $idx => $attempt)
                                        @php
                                            $quizTitle = (int) ($attempt->chapter_id ?? 0) === 99
                                                ? 'Evaluasi Akhir'
                                                : 'Evaluasi Bab ' . ($attempt->chapter_id ?? '-');
                                            $quizStatusRaw = strtolower(trim((string) ($attempt->status ?? '')));
                                            $quizDuration = (int) ($attempt->time_spent_seconds ?? 0);
                                            $quizLimitSeconds = isset($attempt->time_limit) ? (int) $attempt->time_limit * 60 : 0;
                                            $quizTimedOut = $quizStatusRaw === 'timeout'
                                                || $quizStatusRaw === 'waktu habis'
                                                || (int) ($attempt->is_timeout ?? 0) === 1
                                                || ($quizLimitSeconds > 0 && $quizDuration >= $quizLimitSeconds)
                                                || $quizDuration > 43200;
                                            $quizPassed = (float) ($attempt->score ?? 0) >= 70;
                                            $quizStatusLabel = $quizTimedOut ? 'Waktu habis' : ($quizPassed ? 'Lulus' : 'Belum lulus');
                                            $quizStatusClass = $quizTimedOut
                                                ? 'border-amber-200 bg-amber-50 text-amber-700 dark:border-amber-500/20 dark:bg-amber-500/10 dark:text-amber-300'
                                                : ($quizPassed
                                                    ? 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-300'
                                                    : 'border-rose-200 bg-rose-50 text-rose-700 dark:border-rose-500/20 dark:bg-rose-500/10 dark:text-rose-300');
                                            $quizScore = is_numeric($attempt->score ?? null) ? number_format((float) $attempt->score, 1) : '-';
                                            $quizSearchText = strtolower($quizTitle . ' ' . $quizStatusLabel);
                                            $quizNotes = [];
                                            if ((int) ($attempt->flagged_count ?? 0) > 0) $quizNotes[] = (int) $attempt->flagged_count . ' soal ragu';
                                            if ((int) ($attempt->unanswered_count ?? 0) > 0) $quizNotes[] = (int) $attempt->unanswered_count . ' kosong';
                                        @endphp
                                        <tr x-show='searchQuiz === "" || @js($quizSearchText).includes(searchQuiz.toLowerCase())' 
                                            class="transition hover:bg-fuchsia-50/40 dark:hover:bg-fuchsia-500/[0.055]">
                                            <td class="px-5 py-4 text-center font-mono text-xs text-slate-400">{{ $idx + 1 }}</td>
                                            <td class="px-5 py-4">
                                                <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $quizTitle }}</p>
                                                <p class="mt-0.5 text-[10px] font-semibold text-slate-400 dark:text-white/35">
                                                    {{ count($quizNotes) ? implode(' · ', $quizNotes) : 'Tidak ada catatan interaksi' }}
                                                </p>
                                            </td>
                                            <td class="px-5 py-4 text-center">
                                                <span class="inline-flex rounded-lg border px-2.5 py-1 text-[10px] font-black uppercase tracking-wider {{ $quizStatusClass }}">{{ $quizStatusLabel }}</span>
                                            </td>
                                            <td class="px-5 py-4 text-center">
                                                <span class="font-mono text-sm font-black text-slate-900 dark:text-white">{{ $quizScore }}</span>
                                            </td>
                                            <td class="px-5 py-4 text-xs font-semibold text-slate-600 dark:text-white/55">{{ formatTime($quizDuration) }}</td>
                                            <td class="px-5 py-4">
                                                <p class="text-xs font-semibold text-slate-700 dark:text-white/70">{{ \Carbon\Carbon::parse($attempt->completed_at ?? $attempt->created_at)->format('d M Y, H:i') }} WIB</p>
                                                <p class="mt-0.5 text-[10px] text-slate-400 dark:text-white/35">{{ \Carbon\Carbon::parse($attempt->completed_at ?? $attempt->created_at)->diffForHumans() }}</p>
                                            </td>
                                            <td class="px-5 py-4 text-right">
                                                <a href="{{ route('admin.quiz.results.show', $attempt->id) }}"
                                                   class="inline-flex items-center gap-1.5 rounded-lg border border-fuchsia-200 bg-fuchsia-50 px-3 py-2 text-[10px] font-black text-fuchsia-700 transition hover:border-fuchsia-300 hover:bg-fuchsia-100 dark:border-fuchsia-500/20 dark:bg-fuchsia-500/10 dark:text-fuchsia-300 dark:hover:bg-fuchsia-500/20">
                                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                                    Tinjau
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="px-5 py-12 text-center text-sm font-medium text-slate-500 dark:text-white/40">Belum ada riwayat evaluasi kuis.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </section>
                </div>

            </div>
        </div>
    </main>

    {{-- ==================== MODALS (INSIGHTS) ==================== --}}

    {{-- MODAL INFO AKADEMIK 1: MATERI (LESSON) --}}
    <div x-show="showLessonModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-slate-900/40 dark:bg-[#020617]/80 backdrop-blur-sm transition-colors" @click="showLessonModal = false"></div>
        <div class="relative w-full max-w-lg bg-white dark:bg-[#0f141e] border border-cyan-200 dark:border-cyan-500/40 rounded-3xl p-6 md:p-8 transition-colors shadow-2xl" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white transition-colors">Detail Materi Dibaca</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm mt-1 transition-colors">Siswa menyelesaikan <span class="font-bold text-slate-900 dark:text-white">{{ count($completedLessonIds ?? []) }} dari {{ $totalLessons ?? 65 }}</span> materi.</p>
                </div>
                <button @click="showLessonModal = false" class="text-slate-400 hover:text-slate-700 dark:text-slate-500 dark:hover:text-white transition-colors p-2" title="Tutup">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <div class="max-h-[50vh] overflow-y-auto custom-scrollbar pr-2 space-y-4">
                @foreach($curriculumMap as $chapter)
                    <div class="bg-slate-50 dark:bg-[#1d1d1f] rounded-2xl p-5 transition-colors">
                        <h4 class="text-sm font-semibold text-slate-900 dark:text-white mb-4 transition-colors">Bab {{ $chapter['number'] }}: {{ $chapter['title'] }}</h4>
                        <div class="space-y-3">
                            @foreach($chapter['topics'] as $topic)
                                @php 
                                    $intersect = array_intersect($topic['ids'], $completedLessonIds ?? []);
                                    $doneCount = count($intersect);
                                    $totalCount = count($topic['ids']);
                                    $isDone = $doneCount === $totalCount;
                                @endphp
                                @if($doneCount > 0)
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-slate-600 dark:text-slate-300 font-medium transition-colors">
                                        {{ $topic['name'] }}
                                    </span>
                                    <span class="font-semibold text-slate-900 dark:text-white transition-colors">{{ $doneCount }}/{{ $totalCount }}</span>
                                </div>
                                @endif
                            @endforeach
                            @if(count(array_intersect(Arr::collapse(array_column($chapter['topics'], 'ids')), $completedLessonIds ?? [])) === 0)
                                <p class="text-sm text-slate-400 dark:text-slate-500 italic transition-colors">Belum ada materi yang dibaca.</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    
    {{-- MODAL INFO AKADEMIK 2: LAB LULUS --}}
    <div x-show="showLabModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-slate-900/40 dark:bg-[#020617]/80 backdrop-blur-sm transition-colors" @click="showLabModal = false"></div>
        <div class="relative w-full max-w-lg bg-white dark:bg-[#0f141e] border border-slate-200 dark:border-white/10 rounded-3xl p-6 md:p-8 transition-colors shadow-2xl" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white transition-colors">Detail Kelulusan Praktik</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm mt-1 transition-colors">Siswa lulus <span class="font-bold text-slate-900 dark:text-white">{{ count($passedLabIds ?? []) }} dari {{ $totalLabs ?? 4 }}</span> modul.</p>
                </div>
                <button @click="showLabModal = false" class="text-slate-400 hover:text-slate-700 dark:text-slate-500 dark:hover:text-white transition-colors p-2" title="Tutup">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="max-h-[50vh] overflow-y-auto custom-scrollbar pr-2 space-y-3">
                @php $passedLabsList = $labHistories->where('status', 'passed'); @endphp
                @forelse($passedLabsList as $lab)
                <div class="flex justify-between items-center p-4 bg-slate-50 dark:bg-[#1d1d1f] rounded-2xl transition-colors">
                    <div>
                        <p class="text-sm font-semibold text-slate-900 dark:text-white transition-colors">{{ $lab->lab->title ?? 'Modul Lab #'.$lab->lab_id }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 transition-colors">
                            {{ \Carbon\Carbon::parse($lab->created_at)->format('d M Y, H:i') }}
                        </p>
                    </div>
                    <div class="text-right">
                        <span class="text-lg font-bold text-slate-900 dark:text-white transition-colors">{{ $lab->final_score }}</span>
                    </div>
                </div>
                @empty
                <div class="text-center py-10">
                    <p class="text-sm text-slate-500 dark:text-slate-400 italic transition-colors">Belum ada modul yang lulus.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
    
    {{-- MODAL INFO AKADEMIK 3: KUIS LULUS --}}
    <div x-show="showQuizModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-slate-900/40 dark:bg-[#020617]/80 backdrop-blur-sm transition-colors" @click="showQuizModal = false"></div>
        <div class="relative w-full max-w-lg bg-white dark:bg-[#0f141e] border border-slate-200 dark:border-white/10 rounded-3xl p-6 md:p-8 transition-colors shadow-2xl" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white transition-colors">Detail Evaluasi Lulus</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm mt-1 transition-colors">Siswa lulus <span class="font-bold text-slate-900 dark:text-white">{{ count(array_filter($quizScoresMap ?? [], fn($s) => $s >= 70)) }} dari {{ $totalQuizzes ?? 4 }}</span> evaluasi.</p>
                </div>
                <button @click="showQuizModal = false" class="text-slate-400 hover:text-slate-700 dark:text-slate-500 dark:hover:text-white transition-colors p-2" title="Tutup">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="max-h-[50vh] overflow-y-auto custom-scrollbar pr-2 space-y-3">
                @php $passedQuizzesList = $quizAttempts->where('score', '>=', 70); @endphp
                @forelse($passedQuizzesList as $quiz)
                <div class="flex justify-between items-center p-4 bg-slate-50 dark:bg-[#1d1d1f] rounded-2xl transition-colors">
                    <div>
                        <p class="text-sm font-semibold text-slate-900 dark:text-white transition-colors">{{ $quiz->chapter_id == 99 ? 'Evaluasi Akhir' : 'Evaluasi Bab '.$quiz->chapter_id }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 transition-colors">
                            {{ \Carbon\Carbon::parse($quiz->created_at)->format('d M Y, H:i') }}
                        </p>
                    </div>
                    <div class="text-right">
                        <span class="text-lg font-bold text-slate-900 dark:text-white transition-colors">{{ $quiz->score }}</span>
                    </div>
                </div>
                @empty
                <div class="text-center py-10">
                    <p class="text-sm text-slate-500 dark:text-slate-400 italic transition-colors">Belum ada evaluasi yang lulus.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- MODAL HERO TINJAUAN KUIS SISWA --}}
    <div x-show="showQuizReviewModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4 sm:p-6" style="display: none;" x-transition.opacity x-cloak>
        <button type="button" class="absolute inset-0 bg-slate-900/60 backdrop-blur-md dark:bg-[#020617]/90" @click="showQuizReviewModal = false" aria-label="Tutup tinjauan evaluasi"></button>

        <section class="relative flex max-h-[92vh] w-full max-w-4xl flex-col overflow-hidden rounded-3xl border border-fuchsia-200 bg-white shadow-2xl dark:border-fuchsia-500/20 dark:bg-[#0f141e]"
                 role="dialog"
                 aria-modal="true"
                 aria-label="Tinjauan hasil evaluasi siswa"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                 @click.stop>

            {{-- Hero dipadatkan agar ringkasan utama langsung terlihat. --}}
            <header class="relative shrink-0 border-b border-fuchsia-100 bg-gradient-to-r from-fuchsia-600 via-purple-600 to-cyan-600 px-5 py-5 text-white sm:px-7">
                <button @click="showQuizReviewModal = false" class="absolute right-4 top-4 z-10 rounded-full bg-white/10 p-2 transition hover:bg-white/20 focus:outline-none" title="Tutup" aria-label="Tutup tinjauan evaluasi">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>

                <div class="flex items-start justify-between gap-5 pr-10">
                    <div class="min-w-0">
                        <p class="text-[10px] font-black uppercase tracking-[0.22em] text-white/70">Tinjauan evaluasi</p>
                        <h3 class="mt-1 truncate text-xl font-black tracking-tight sm:text-2xl" x-text="selectedQuizReview?.title || 'Evaluasi'"></h3>
                        <p class="mt-1 text-xs font-semibold text-white/75">Siswa: <span x-text="selectedQuizReview?.student || '-'"></span> · <span x-text="selectedQuizReview?.date || '-'"></span></p>
                    </div>

                    <div class="shrink-0 rounded-2xl border border-white/20 bg-white/10 px-4 py-3 text-right backdrop-blur-sm">
                        <p class="text-[9px] font-black uppercase tracking-widest text-white/65">Skor</p>
                        <p class="mt-1 text-3xl font-black leading-none" x-text="selectedQuizReview?.score ?? 0"></p>
                        <p class="mt-1 text-[10px] font-bold text-white/70">dari 100</p>
                    </div>
                </div>
            </header>

            <div class="custom-scrollbar min-h-0 overflow-y-auto p-5 sm:p-6">
                {{-- Angka inti: jelas, singkat, dan langsung dapat dibandingkan. --}}
                <section class="grid grid-cols-2 gap-3 sm:grid-cols-4" aria-label="Ringkasan hasil evaluasi">
                    <article class="rounded-2xl border border-slate-200 bg-slate-50 p-3.5 dark:border-white/10 dark:bg-white/[0.04]">
                        <p class="text-[9px] font-black uppercase tracking-widest text-slate-400">Status · KKM 70</p>
                        <p class="mt-1 text-base font-black" :class="(selectedQuizReview?.score ?? 0) >= 70 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400'" x-text="selectedQuizReview?.status || '-'"></p>
                    </article>
                    <article class="rounded-2xl border border-slate-200 bg-slate-50 p-3.5 dark:border-white/10 dark:bg-white/[0.04]">
                        <p class="text-[9px] font-black uppercase tracking-widest text-slate-400">Durasi</p>
                        <p class="mt-1 font-mono text-base font-black text-slate-900 dark:text-white" x-text="selectedQuizReview?.duration || '-'"></p>
                    </article>
                    <article class="rounded-2xl border border-slate-200 bg-slate-50 p-3.5 dark:border-white/10 dark:bg-white/[0.04]">
                        <p class="text-[9px] font-black uppercase tracking-widest text-slate-400">Terjawab</p>
                        <p class="mt-1 text-base font-black text-slate-900 dark:text-white"><span x-text="selectedQuizReview?.answered ?? 0"></span> soal</p>
                    </article>
                    <article class="rounded-2xl border border-slate-200 bg-slate-50 p-3.5 dark:border-white/10 dark:bg-white/[0.04]">
                        <p class="text-[9px] font-black uppercase tracking-widest text-slate-400">Belum dijawab</p>
                        <p class="mt-1 text-base font-black text-amber-600 dark:text-amber-400"><span x-text="selectedQuizReview?.unanswered ?? 0"></span> soal</p>
                    </article>
                </section>

               

                {{-- Catatan pengerjaan dipisahkan dari hasil akademik agar tidak menimbulkan tafsir keliru. --}}
                <section class="mt-4 flex flex-wrap gap-2" aria-label="Catatan pengerjaan">
                    <span class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-[10px] font-bold text-slate-600 dark:border-white/10 dark:bg-white/[0.03] dark:text-slate-300">
                        Ragu-ragu: <b class="text-slate-900 dark:text-white" x-text="selectedQuizReview?.flagged ?? 0"></b> soal
                    </span>
                    <span class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-[10px] font-bold text-slate-600 dark:border-white/10 dark:bg-white/[0.03] dark:text-slate-300">
                        Fokus Terganggu: <b class="text-slate-900 dark:text-white" x-text="selectedQuizReview?.focusLost ?? 0"></b> kali
                    </span>
                </section>

                {{-- Per TP diringkas dahulu; uraian panjang tetap ada di detail terbuka. --}}
                <template x-if="selectedQuizReview?.outcomes?.length">
                    <section class="mt-5" aria-label="Capaian tujuan pembelajaran">
                        <div class="mb-3 flex items-center justify-between gap-3">
                            <div>
                                <p class="text-[9px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500">Analitik tujuan pembelajaran</p>
                                <h4 class="mt-1 text-sm font-black text-slate-900 dark:text-white">Capaian per TP</h4>
                            </div>
                            <span class="rounded-lg border border-slate-200 bg-slate-50 px-2.5 py-1 text-[10px] font-black text-slate-500 dark:border-white/10 dark:bg-white/[0.04] dark:text-slate-400" x-text="selectedQuizReview.outcomes.length + ' TP'"></span>
                        </div>

                        <div class="space-y-2.5">
                            <template x-for="tp in selectedQuizReview.outcomes" :key="tp.key">
                                <details class="group overflow-hidden rounded-2xl border border-slate-200 bg-white dark:border-white/10 dark:bg-white/[0.03]" x-data="{ open: false }" @toggle="open = $event.target.open">
                                    <summary class="flex cursor-pointer list-none items-start justify-between gap-3 p-3.5 transition hover:bg-slate-50 dark:hover:bg-white/[0.04]">
                                        <div class="min-w-0">
                                            <p class="text-[9px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500" x-text="tp.display_code || tp.code || 'TP'"></p>
                                            <p class="mt-1 line-clamp-1 text-xs font-black text-slate-900 dark:text-white" x-text="tp.title || 'Tujuan Pembelajaran'"></p>
                                        </div>
                                        <div class="flex shrink-0 items-center gap-2">
                                            <span class="text-lg font-black text-slate-900 dark:text-white" x-text="(tp.mastery_percent ?? 0) + '%'"></span>
                                            <svg class="h-4 w-4 text-slate-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 9 6 6 6-6"/></svg>
                                        </div>
                                    </summary>

                                    <div class="border-t border-slate-100 px-3.5 pb-3.5 pt-3 dark:border-white/10">
                                        <div class="h-1.5 overflow-hidden rounded-full bg-slate-100 dark:bg-black/20">
                                            <div class="h-full rounded-full"
                                                 :class="{
                                                    'bg-emerald-500': tp.tone === 'emerald',
                                                    'bg-cyan-500': tp.tone === 'cyan',
                                                    'bg-amber-500': tp.tone === 'amber',
                                                    'bg-rose-500': tp.tone === 'red',
                                                    'bg-slate-400': !['emerald','cyan','amber','red'].includes(tp.tone)
                                                 }"
                                                 :style="'width: ' + Math.min(100, Math.max(0, tp.mastery_percent ?? 0)) + '%'"></div>
                                        </div>
                                        <div class="mt-3 grid gap-2 text-xs leading-5 text-slate-600 dark:text-slate-300">
                                            <p><span class="font-black text-slate-900 dark:text-white">Data:</span> <span x-text="tp.activity_data || 'Belum ada rincian respons.'"></span></p>
                                            <p><span class="font-black text-slate-900 dark:text-white">Arahan:</span> <span x-text="tp.material_direction || 'Lanjutkan materi dan evaluasi terkait.'"></span></p>
                                        </div>
                                    </div>
                                </details>
                            </template>
                        </div>
                    </section>
                </template>

                <template x-if="selectedQuizReview?.reflectionNote">
                    <details class="mt-4 rounded-2xl border border-slate-200 bg-slate-50 dark:border-white/10 dark:bg-white/[0.03]">
                        <summary class="cursor-pointer list-none px-4 py-3 text-xs font-black text-slate-700 dark:text-slate-200">Catatan siswa</summary>
                        <p class="border-t border-slate-200 px-4 py-3 text-xs italic leading-5 text-slate-600 dark:border-white/10 dark:text-slate-300" x-text="selectedQuizReview.reflectionNote"></p>
                    </details>
                </template>
            </div>

            <footer class="flex shrink-0 justify-end border-t border-slate-200 bg-white px-5 py-4 dark:border-white/10 dark:bg-[#0f141e] sm:px-6">
                <button type="button" @click="showQuizReviewModal = false" class="rounded-xl bg-slate-900 px-5 py-2.5 text-xs font-black text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-950 dark:hover:bg-slate-200">Tutup</button>
            </footer>
        </section>
    </div>

    {{-- INSIGHT HERO: DASAR HITUNG PROGRES BELAJAR --}}
    <div x-show="showGlobalProgressModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4 sm:p-6" style="display: none;" x-transition.opacity x-cloak>
        <button type="button" class="absolute inset-0 bg-slate-900/35 backdrop-blur-md dark:bg-[#020617]/80" @click="showGlobalProgressModal = false" aria-label="Tutup rincian progres"></button>
        <section class="relative flex max-h-[90vh] w-full max-w-2xl flex-col overflow-hidden rounded-3xl border border-indigo-200 bg-white shadow-2xl dark:border-indigo-500/25 dark:bg-[#0f141e]"
                 role="dialog" aria-modal="true" aria-label="Dasar hitung progres belajar"
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                 @click.stop>
            <header class="relative overflow-hidden border-b border-indigo-100 bg-gradient-to-br from-indigo-50 via-white to-cyan-50 px-6 py-6 dark:border-indigo-500/15 dark:from-indigo-500/15 dark:via-[#0f141e] dark:to-cyan-500/10 sm:px-7">
                <div class="pointer-events-none absolute -right-10 -top-12 h-36 w-36 rounded-full bg-indigo-300/30 blur-3xl dark:bg-indigo-500/20"></div>
                <div class="relative flex items-start justify-between gap-4">
                    <div class="flex items-start gap-3">
                        <span class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl border border-indigo-200 bg-white text-indigo-600 shadow-sm dark:border-indigo-500/25 dark:bg-white/10 dark:text-indigo-300">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" /></svg>
                        </span>
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-[0.18em] text-indigo-600 dark:text-indigo-300">Dasar hitung data</p>
                            <h3 class="mt-1 text-xl font-black tracking-tight text-slate-900 dark:text-white">Progres belajar</h3>
                            <p class="mt-1 text-xs font-semibold leading-5 text-slate-500 dark:text-white/50">Progres menunjukkan aktivitas tuntas dibanding seluruh target pembelajaran.</p>
                        </div>
                    </div>
                    <button type="button" @click="showGlobalProgressModal = false" class="rounded-full p-2 text-slate-400 transition hover:bg-white/70 hover:text-slate-700 dark:hover:bg-white/10 dark:hover:text-white" aria-label="Tutup rincian progres">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </header>

            <div class="custom-scrollbar overflow-y-auto px-6 py-6 sm:px-7">
                <div class="rounded-2xl border border-indigo-100 bg-indigo-50/70 p-5 dark:border-indigo-500/20 dark:bg-indigo-500/[0.08]">
                    <div class="flex items-end justify-between gap-4">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-[0.14em] text-indigo-600 dark:text-indigo-300">Perhitungan</p>
                            <p class="mt-2 text-sm font-bold leading-6 text-slate-700 dark:text-slate-200">Aktivitas tuntas ÷ seluruh target × 100</p>
                        </div>
                        <strong class="text-4xl font-black tracking-[-0.06em] text-indigo-700 dark:text-indigo-300">{{ $progressPercentForAnalytics }}%</strong>
                    </div>
                    <div class="mt-4 h-2 overflow-hidden rounded-full bg-indigo-100 dark:bg-white/10"><span class="block h-full rounded-full bg-indigo-500" style="width: {{ $progressPercentForAnalytics }}%"></span></div>
                    <div class="mt-3 flex items-center justify-between text-xs font-bold text-slate-600 dark:text-slate-300"><span>Pembilang</span><span>{{ number_format($progressCompletedForAnalytics) }} aktivitas tuntas</span></div>
                    <div class="mt-1 flex items-center justify-between text-xs font-bold text-slate-600 dark:text-slate-300"><span>Penyebut</span><span>{{ number_format($progressTargetForAnalytics) }} target aktivitas</span></div>
                </div>

                <div class="mt-5 grid gap-3 sm:grid-cols-3">
                    <article class="rounded-xl border border-cyan-100 bg-cyan-50/60 p-4 dark:border-cyan-500/20 dark:bg-cyan-500/[0.08]">
                        <p class="text-[10px] font-black uppercase tracking-widest text-cyan-700 dark:text-cyan-300">Materi selesai</p>
                        <p class="mt-2 text-2xl font-black text-slate-900 dark:text-white">{{ $lessonsDoneForAnalytics }}<span class="ml-1 text-sm text-slate-400">/ {{ $lessonsTotalForAnalytics }}</span></p>
                        <p class="mt-2 text-[11px] font-semibold text-slate-500 dark:text-white/45">{{ $lessonProgressForAnalytics }}% target materi</p>
                    </article>
                    <article class="rounded-xl border border-indigo-100 bg-indigo-50/60 p-4 dark:border-indigo-500/20 dark:bg-indigo-500/[0.08]">
                        <p class="text-[10px] font-black uppercase tracking-widest text-indigo-700 dark:text-indigo-300">Praktik tuntas</p>
                        <p class="mt-2 text-2xl font-black text-slate-900 dark:text-white">{{ $labsDoneForAnalytics }}<span class="ml-1 text-sm text-slate-400">/ {{ $labsTotalForAnalytics }}</span></p>
                        <p class="mt-2 text-[11px] font-semibold text-slate-500 dark:text-white/45">{{ $labProgressForAnalytics }}% target praktik</p>
                    </article>
                    <article class="rounded-xl border border-fuchsia-100 bg-fuchsia-50/60 p-4 dark:border-fuchsia-500/20 dark:bg-fuchsia-500/[0.08]">
                        <p class="text-[10px] font-black uppercase tracking-widest text-fuchsia-700 dark:text-fuchsia-300">Evaluasi lulus</p>
                        <p class="mt-2 text-2xl font-black text-slate-900 dark:text-white">{{ $quizzesPassedForAnalytics }}<span class="ml-1 text-sm text-slate-400">/ {{ $quizzesTotalForAnalytics }}</span></p>
                        <p class="mt-2 text-[11px] font-semibold text-slate-500 dark:text-white/45">{{ $quizProgressForAnalytics }}% target evaluasi</p>
                    </article>
                </div>

                <div class="mt-5 rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-white/10 dark:bg-white/[0.035]">
                    <p class="text-[10px] font-black uppercase tracking-[0.14em] text-slate-500 dark:text-white/45">Aturan pembacaan</p>
                    <div class="mt-3 grid gap-2 text-xs font-semibold leading-5 text-slate-600 dark:text-white/60 sm:grid-cols-3">
                        <p><strong class="text-slate-900 dark:text-white">Materi:</strong> dihitung saat materi ditandai selesai.</p>
                        <p><strong class="text-slate-900 dark:text-white">Praktik:</strong> dihitung saat lab tuntas.</p>
                        <p><strong class="text-slate-900 dark:text-white">Evaluasi:</strong> dihitung saat skor mencapai KKM 70.</p>
                    </div>
                </div>
            </div>

            <footer class="flex justify-end border-t border-slate-200 bg-white px-6 py-4 dark:border-white/10 dark:bg-[#0f141e] sm:px-7">
                <button type="button" @click="showGlobalProgressModal = false" class="rounded-xl bg-slate-900 px-5 py-2.5 text-xs font-black text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-950 dark:hover:bg-slate-200">Tutup</button>
            </footer>
        </section>
    </div>


    {{-- MODAL PANDUAN DETAIL SISWA --}}
    <div x-show="showStudentGuideModal" x-transition.opacity class="fixed inset-0 z-[120] flex items-center justify-center p-4 sm:p-6" style="display: none;" x-cloak>
        <button type="button" class="absolute inset-0 bg-slate-900/60 backdrop-blur-md dark:bg-[#020617]/80" @click="showStudentGuideModal = false" aria-label="Tutup panduan"></button>
        <section class="relative max-h-[88vh] w-full max-w-4xl overflow-y-auto rounded-3xl border border-slate-200 bg-white/95 p-5 shadow-2xl custom-scrollbar dark:border-white/10 dark:bg-[#0f141e]/95 sm:p-6" @click.stop>
            <button type="button" @click="showStudentGuideModal = false" class="absolute right-5 top-5 z-10 rounded-full p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-white/5 dark:hover:text-white" aria-label="Tutup panduan">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            @php
                $guideTitle = 'Panduan Detail Siswa';
                $guideSubtitle = 'Membaca profil dan riwayat';
                $guideImage = 'images/guides/current-admin-student-detail.png';
                $guideIntro = 'Gunakan nomor pada gambar untuk membaca area identitas siswa, tab capaian, dan riwayat aktivitas saat menindaklanjuti progres belajar.';
                $guidePoints = [
                    ['x' => 24, 'y' => 22, 'title' => 'Identitas siswa', 'description' => 'Periksa nama, email, kelas, dan tombol edit sebelum membuat tindakan administratif.'],
                    ['x' => 56, 'y' => 42, 'title' => 'Capaian belajar', 'description' => 'Baca ringkasan materi, lab, kuis, dan riwayat aktivitas untuk memahami data belajar siswa.'],
                    ['x' => 70, 'y' => 72, 'title' => 'Riwayat aktivitas', 'description' => 'Gunakan tab riwayat untuk meninjau percobaan kuis atau lab yang perlu dicek ulang.'],
                ];
            @endphp
            @include('admin.partials.analytics_guide_mockup')

            <div class="mt-8 border-t border-slate-200 pt-6 dark:border-white/5">
                <button type="button" @click="showStudentGuideModal = false" class="w-full rounded-xl bg-slate-900 py-3 text-sm font-bold text-white shadow-md transition hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-slate-200">Mengerti, Tutup Panduan</button>
            </div>
        </section>
    </div>

    {{-- MODAL EDIT DATA SISWA (ADMIN) --}}
    <div x-show="showEdit" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="fixed inset-0 z-[100] flex items-center justify-center p-4" style="display: none;" x-cloak>
        <div class="absolute inset-0 bg-slate-900/40 dark:bg-[#020617]/80 backdrop-blur-sm transition-colors" @click="showEdit = false"></div>
        <div class="relative w-full max-w-xl bg-white dark:bg-[#0f141e] border border-slate-200 dark:border-white/10 rounded-3xl p-6 md:p-8 transition-colors shadow-2xl" @click.stop>
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white transition-colors">Perbarui Data Siswa</h3>
                </div>
                <button @click="showEdit = false" class="text-slate-400 dark:text-slate-500 hover:text-slate-800 dark:hover:text-white transition bg-slate-100 dark:bg-white/5 hover:bg-slate-200 dark:bg-white/10 p-2 rounded-full" title="Tutup">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <form action="{{ route('admin.student.update', $user->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6" x-data="{ isSubmitting: false }" @submit="isSubmitting = true">
                @csrf 
                @method('PUT')
                
                <div>
                    <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2 block transition-colors">Profile Photo <span class="text-slate-400 dark:text-slate-500 font-normal">(Opsional)</span></label>
                    <input type="file" name="avatar" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-slate-100 dark:file:bg-white/5 file:text-slate-700 dark:file:text-slate-300 hover:file:bg-slate-200 dark:hover:file:bg-white/10 cursor-pointer transition-colors">
                </div>

                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2 block transition-colors">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ $user->name }}" class="w-full bg-slate-50 dark:bg-[#1d1d1f] border border-slate-200 dark:border-white/10 rounded-xl px-4 py-3 text-sm text-slate-900 dark:text-white focus:border-blue-500 outline-none transition-colors" required>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2 block transition-colors">Alamat Email</label>
                        <input type="email" name="email" value="{{ $user->email }}" class="w-full bg-slate-50 dark:bg-[#1d1d1f] border border-slate-200 dark:border-white/10 rounded-xl px-4 py-3 text-sm text-slate-900 dark:text-white focus:border-blue-500 outline-none transition-colors" required>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2 block transition-colors">Grup Kelas</label>
                        <div class="relative">
                            <select name="class_group" class="w-full bg-slate-50 dark:bg-[#1d1d1f] border border-slate-200 dark:border-white/10 rounded-xl px-4 py-3 text-sm text-slate-900 dark:text-white focus:border-blue-500 outline-none appearance-none transition-colors cursor-pointer">
                                <option value="" class="text-slate-400" {{ empty($user->class_group) ? 'selected' : '' }}>-- Pilih Kelas --</option>
                                @foreach($availableClasses ?? [] as $cls)
                                    <option value="{{ $cls->name }}" class="text-slate-900 dark:text-white" {{ trim($user->class_group) === trim($cls->name) ? 'selected' : '' }}>
                                        {{ $cls->name }} {{ $cls->major ? ' - '.$cls->major : '' }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2 block transition-colors">Phone Number</label>
                        <input type="text" name="phone" value="{{ $user->phone }}" class="w-full bg-slate-50 dark:bg-[#1d1d1f] border border-slate-200 dark:border-white/10 rounded-xl px-4 py-3 text-sm text-slate-900 dark:text-white focus:border-blue-500 outline-none transition-colors">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2 block transition-colors">Institution</label>
                        <input type="text" name="institution" value="{{ $user->institution }}" class="w-full bg-slate-50 dark:bg-[#1d1d1f] border border-slate-200 dark:border-white/10 rounded-xl px-4 py-3 text-sm text-slate-900 dark:text-white focus:border-blue-500 outline-none transition-colors">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2 block transition-colors">Study Program</label>
                        <input type="text" name="study_program" value="{{ $user->study_program }}" class="w-full bg-slate-50 dark:bg-[#1d1d1f] border border-slate-200 dark:border-white/10 rounded-xl px-4 py-3 text-sm text-slate-900 dark:text-white focus:border-blue-500 outline-none transition-colors">
                    </div>
                </div>

                <div>
                    <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2 block transition-colors">Atur Ulang Kata Sandi <span class="text-slate-400 dark:text-slate-500 font-normal">(Kosongkan jika tidak diubah)</span></label>
                    <input type="password" name="password" placeholder="Masukkan password baru..." class="w-full bg-slate-50 dark:bg-[#1d1d1f] border border-slate-200 dark:border-white/10 rounded-xl px-4 py-3 text-sm text-slate-900 dark:text-white focus:border-blue-500 outline-none transition-colors">
                </div>
                
                <div class="flex justify-between items-center mt-10 pt-6 border-t border-slate-200 dark:border-white/5 transition-colors">
                    <button type="button" @click="confirmHapus()" class="text-sm font-semibold text-red-500 hover:text-red-600 transition-colors px-3 py-2 rounded-lg hover:bg-red-50 dark:hover:bg-red-500/10">
                        Hapus Akun
                    </button>

                    <div class="flex gap-3">
                        <button type="button" @click="showEdit = false" class="px-6 py-3 rounded-xl text-sm font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/5 transition-colors" :disabled="isSubmitting">Batal</button>
                        <button type="submit" class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold shadow-md transition-colors flex items-center gap-2" :class="isSubmitting ? 'opacity-70 cursor-wait' : ''" :disabled="isSubmitting">
                            <span x-text="isSubmitting ? 'Menyimpan...' : 'Simpan Perubahan'"></span>
                        </button>
                    </div>
                </div>
            </form>
            
            <form id="delete-student-form" action="{{ route('admin.users.delete', $user->id) }}" method="POST" class="hidden">
                @csrf @method('DELETE')
            </form>
        </div>
    </div>

    {{-- SCRIPTS KHUSUS ADMIN DETAIL --}}
    @if(session('success')) <script> document.addEventListener('DOMContentLoaded', () => { Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: "{{ session('success') }}", showConfirmButton: false, timer: 3000, background: document.documentElement.classList.contains('dark') ? '#0f141e' : '#fff', color: document.documentElement.classList.contains('dark') ? '#fff' : '#1e293b', iconColor: '#10b981' }); }); </script> @endif
    
    <script>
        // SCRIPT THEME SWITCHER
        document.addEventListener('DOMContentLoaded', () => {
            const themeToggleBtnSidebar = document.getElementById('theme-toggle-sidebar');
            const themeToggleDarkIconSidebar = document.getElementById('theme-toggle-dark-icon-sidebar');
            const themeToggleLightIconSidebar = document.getElementById('theme-toggle-light-icon-sidebar');
            const themeToggleTextSidebar = document.getElementById('theme-toggle-text-sidebar');

            const syncIcons = (isDark) => {
                if (isDark) {
                    themeToggleLightIconSidebar?.classList.remove('hidden');
                    themeToggleDarkIconSidebar?.classList.add('hidden');
                    if(themeToggleTextSidebar) themeToggleTextSidebar.textContent = "Tema Terang";
                } else {
                    themeToggleLightIconSidebar?.classList.add('hidden');
                    themeToggleDarkIconSidebar?.classList.remove('hidden');
                    if(themeToggleTextSidebar) themeToggleTextSidebar.textContent = "Tema Gelap";
                }
            };

            const isDarkTheme = document.documentElement.classList.contains('dark');
            syncIcons(isDarkTheme);

            themeToggleBtnSidebar?.addEventListener('click', function() {
                const willBeDark = !document.documentElement.classList.contains('dark');
                if (willBeDark) {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                }
                syncIcons(willBeDark);
                window.dispatchEvent(new Event('theme-toggled'));
            });
        });

        // SCRIPT CHART (Beradaptasi dengan Tema)
        let scoreChartInstance = null;

        function renderCharts() {
            const isDark = document.documentElement.classList.contains('dark');
            const gridColor = isDark ? 'rgba(255,255,255,0.07)' : 'rgba(15,23,42,0.07)';
            const textColor = isDark ? '#94a3b8' : '#64748b';
            const pointBg = isDark ? '#0f141e' : '#ffffff';

            const ctxScore = document.getElementById('scoreChart');
            if(ctxScore && {!! json_encode($chartScores ?? []) !!}.length > 0) {
                if(scoreChartInstance) scoreChartInstance.destroy();
                const gradient = ctxScore.getContext('2d').createLinearGradient(0, 0, 0, 300);
                gradient.addColorStop(0, 'rgba(59, 130, 246, 0.2)');
                gradient.addColorStop(1, 'rgba(59, 130, 246, 0)');
                scoreChartInstance = new Chart(ctxScore, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($chartLabels ?? []) !!},
                        datasets: [{
                            label: 'Nilai Praktik',
                            data: {!! json_encode($chartScores ?? []) !!},
                            borderColor: '#3b82f6', backgroundColor: gradient,
                            borderWidth: 2, tension: 0.3, fill: true,
                            pointBackgroundColor: pointBg, pointBorderColor: '#3b82f6', pointBorderWidth: 2, pointRadius: 4, pointHoverRadius: 6
                        }]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: isDark ? 'rgba(15,23,42,0.98)' : 'rgba(255,255,255,0.98)',
                                titleColor: isDark ? '#f8fafc' : '#0f172a',
                                bodyColor: isDark ? '#cbd5e1' : '#64748b',
                                titleFont: { size: 13, family: 'Inter', weight: 'bold' },
                                bodyFont: { size: 12, family: 'Inter' },
                                borderColor: isDark ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.1)',
                                borderWidth: 1, padding: 12, displayColors: false
                            }
                        },
                        scales: {
                            y: { beginAtZero: true, max: 100, grid: { color: gridColor }, ticks: { color: textColor, stepSize: 20 } },
                            x: { display: true, grid: { display: false }, ticks: { color: textColor, font: { size: 11 } } }
                        }
                    }
                });
            }
        }

        document.addEventListener('DOMContentLoaded', renderCharts);
        window.addEventListener('theme-toggled', renderCharts);



        // TOOLTIP DATA ANALITIK — portal fixed agar tidak terpotong card, tabel, atau area scroll.
        document.addEventListener('DOMContentLoaded', () => {
            const portal = document.createElement('div');
            portal.id = 'learningAnalyticsTooltip';
            portal.setAttribute('role', 'tooltip');
            document.body.appendChild(portal);

            let activeTrigger = null;

            const placeTooltip = (trigger) => {
                if (!trigger) return;
                const rect = trigger.getBoundingClientRect();
                const gap = 12;
                const maxLeft = window.innerWidth - portal.offsetWidth - 12;
                let left = rect.left + (rect.width / 2) - (portal.offsetWidth / 2);
                let top = rect.top - portal.offsetHeight - gap;

                if (top < 12) top = rect.bottom + gap;
                left = Math.max(12, Math.min(left, maxLeft));

                portal.style.left = `${left}px`;
                portal.style.top = `${top}px`;
            };

            const showTooltip = (trigger) => {
                const text = trigger?.getAttribute('data-la-tooltip');
                if (!text) return;
                activeTrigger = trigger;
                portal.textContent = text;
                portal.classList.add('is-visible');
                placeTooltip(trigger);
            };

            const hideTooltip = () => {
                portal.classList.remove('is-visible');
                activeTrigger = null;
            };

            document.addEventListener('pointerover', (event) => {
                const trigger = event.target.closest('[data-la-tooltip]');
                if (trigger) showTooltip(trigger);
            });

            document.addEventListener('pointerout', (event) => {
                const trigger = event.target.closest('[data-la-tooltip]');
                if (trigger && !trigger.contains(event.relatedTarget)) hideTooltip();
            });

            document.addEventListener('focusin', (event) => {
                const trigger = event.target.closest('[data-la-tooltip]');
                if (trigger) showTooltip(trigger);
            });

            document.addEventListener('focusout', (event) => {
                const trigger = event.target.closest('[data-la-tooltip]');
                if (trigger && !trigger.contains(event.relatedTarget)) hideTooltip();
            });

            window.addEventListener('scroll', () => placeTooltip(activeTrigger), true);
            window.addEventListener('resize', () => placeTooltip(activeTrigger));
        });

    </script>
</body>
</html>
