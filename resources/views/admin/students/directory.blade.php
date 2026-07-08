<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Direktori Siswa</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

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
    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <style>
        /* ==========================================================
           SISTEM VISUAL DIREKTORI — DISELARASKAN DENGAN DASBOR ADMIN
           ========================================================== */
        .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(148,163,184,.34); border-radius: 999px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(99,102,241,.48); }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,.13); }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,.24); }

        .glass-sidebar { background: rgba(255,255,255,.95); backdrop-filter: blur(20px); border-right: 1px solid rgba(15,23,42,.07); }
        .dark .glass-sidebar { background: rgba(5,8,16,.95); border-right-color: rgba(255,255,255,.06); }

        .glass-header { background: rgba(255,255,255,.80); backdrop-filter: blur(14px); border-bottom: 1px solid rgba(15,23,42,.07); }
        .dark .glass-header { background: rgba(2,6,23,.80); border-bottom-color: rgba(255,255,255,.06); }

        .glass-card {
            position: relative;
            background: rgba(255,255,255,.86);
            border: 1px solid rgba(15,23,42,.07);
            box-shadow: 0 4px 30px rgba(15,23,42,.035);
            backdrop-filter: blur(14px);
            transition: transform .3s cubic-bezier(.4,0,.2,1), border-color .3s ease, box-shadow .3s ease;
        }
        .dark .glass-card { background: rgba(10,14,23,.86); border-color: rgba(255,255,255,.08); box-shadow: 0 4px 30px rgba(0,0,0,.20); }
        .glass-card:hover { border-color: rgba(99,102,241,.38); transform: translateY(-4px); box-shadow: 0 14px 44px -16px rgba(15,23,42,.20); z-index: 20; }
        .dark .glass-card:hover { box-shadow: 0 18px 52px -18px rgba(0,0,0,.72); }

        .card-bg-gfx { position: absolute; inset: 0; overflow: hidden; border-radius: inherit; pointer-events: none; z-index: 0; }
        .metric-card { overflow: visible; min-height: 168px; }
        .metric-card .metric-icon { transition: transform .45s cubic-bezier(.22,.61,.36,1), opacity .25s ease; }
        .metric-card:hover .metric-icon { transform: scale(1.12) rotate(5deg); opacity: .13; }

        .glass-input { background: rgba(0,0,0,.025); border: 1px solid rgba(15,23,42,.10); color: #0f172a; transition: all .25s ease; }
        .dark .glass-input { background: rgba(255,255,255,.03); border-color: rgba(255,255,255,.10); color: #fff; }
        .glass-input:focus { border-color: #6366f1; background: rgba(255,255,255,.92); outline: none; box-shadow: 0 0 0 3px rgba(99,102,241,.14); }
        .dark .glass-input:focus { background: rgba(255,255,255,.055); }

        .nav-link { display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 12px; color: #64748b; font-weight: 600; font-size: .9rem; transition: all .2s ease; border: 1px solid transparent; }
        .dark .nav-link { color: #94a3b8; font-weight: 500; }
        .nav-link:hover { background: rgba(15,23,42,.035); color: #0f172a; }
        .dark .nav-link:hover { background: rgba(255,255,255,.035); color: #fff; }
        .nav-link.active { background: linear-gradient(90deg, rgba(99,102,241,.11), rgba(99,102,241,0)); color: #4f46e5; border-left: 3px solid #6366f1; border-radius: 4px 12px 12px 4px; }
        .dark .nav-link.active { color: #a5b4fc; border-left-color: #818cf8; }

        .brand-bar::after { content: ''; position: absolute; top: 50%; left: 50%; width: 86px; height: 86px; border-radius: 999px; background: rgba(99,102,241,.18); filter: blur(36px); transform: translate(-50%,-50%) scale(.55); opacity: 0; transition: .45s ease; pointer-events: none; }
        .brand-bar:hover::after { opacity: 1; transform: translate(-50%,-50%) scale(1); }

        .reveal { opacity: 0; transform: translateY(20px); animation: revealAnim .6s forwards; }
        @keyframes revealAnim { to { opacity: 1; transform: translateY(0); } }

        .class-filter {
            border: 1px solid rgba(15,23,42,.09);
            background: rgba(255,255,255,.72);
            color: #64748b;
            transition: all .22s ease;
        }
        .dark .class-filter { border-color: rgba(255,255,255,.10); background: rgba(255,255,255,.035); color: rgba(255,255,255,.55); }
        .class-filter:hover { border-color: rgba(99,102,241,.35); color: #4f46e5; transform: translateY(-1px); }
        .class-filter.active { border-color: rgba(99,102,241,.28); background: rgba(238,242,255,.94); color: #4338ca; box-shadow: 0 8px 18px -12px rgba(79,70,229,.58); }
        .dark .class-filter.active { border-color: rgba(129,140,248,.28); background: rgba(99,102,241,.13); color: #c7d2fe; }

        .student-row { transition: background .22s ease, transform .22s ease; }
        .student-row:hover { background: rgba(99,102,241,.045); }
        .student-row:hover .student-avatar { transform: translateY(-2px) scale(1.03); box-shadow: 0 10px 22px -10px rgba(79,70,229,.55); }
        .student-avatar { transition: transform .25s ease, box-shadow .25s ease; }

        .directory-shell { overflow: visible; }
        .directory-shell:hover { transform: none; }
        .directory-mobile-card { transition: border-color .25s ease, transform .25s ease, box-shadow .25s ease; }
        .directory-mobile-card:hover { border-color: rgba(99,102,241,.35); transform: translateY(-2px); box-shadow: 0 16px 28px -20px rgba(15,23,42,.46); }

        /* ==========================================================
           TOOLTIP GLOBAL — PORTAL LANGSUNG KE BODY
           Tooltip tidak lagi berada di dalam area scroll, header, kartu,
           atau tabel. Karena itu layer-nya selalu berada paling depan dan
           tidak dapat terpotong oleh overflow parent mana pun.
           ========================================================== */
        .tooltip-container { position: relative; display: inline-flex; align-items: center; justify-content: center; }
        .tooltip-trigger { width: 19px; height: 19px; display: inline-flex; align-items: center; justify-content: center; border-radius: 999px; border: 1px solid rgba(15,23,42,.10); background: rgba(255,255,255,.7); color: #64748b; font-size: 11px; font-weight: 900; cursor: help; transition: transform .2s ease, border-color .2s ease, color .2s ease; }
        .dark .tooltip-trigger { background: rgba(255,255,255,.05); border-color: rgba(255,255,255,.15); color: rgba(255,255,255,.78); }
        .tooltip-trigger:hover,
        .tooltip-trigger:focus-visible { transform: scale(1.12); border-color: rgba(99,102,241,.42); color: #4f46e5; outline: none; }
        .tooltip-container .tooltip-content { display: none !important; }

        #directoryTooltipPortal {
            position: fixed;
            left: 0;
            top: 0;
            z-index: 2147483647 !important;
            width: max-content;
            min-width: 220px;
            max-width: min(286px, calc(100vw - 28px));
            padding: 12px 14px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            background: #ffffff;
            color: #334155;
            font-size: 11px;
            line-height: 1.55;
            text-align: left;
            box-shadow: 0 20px 50px rgba(15,23,42,.20);
            pointer-events: none;
            opacity: 0;
            visibility: hidden;
            transform: translate3d(0,-8px,0);
            transition: opacity .18s ease, transform .18s cubic-bezier(.22,.61,.36,1), visibility .18s ease;
            will-change: top, left, transform, opacity;
        }
        .dark #directoryTooltipPortal { background: #0f141e; color: #e2e8f0; border-color: rgba(255,255,255,.10); box-shadow: 0 20px 52px rgba(0,0,0,.88); }
        #directoryTooltipPortal.is-visible { opacity: 1; visibility: visible; transform: translate3d(0,0,0); }
        #directoryTooltipPortal.tooltip-above { transform: translate3d(0,8px,0); }
        #directoryTooltipPortal.tooltip-above.is-visible { transform: translate3d(0,0,0); }
        #directoryTooltipPortal::after { content: ''; position: absolute; bottom: 100%; left: var(--tooltip-arrow-left, 50%); margin-left: -6px; border: 6px solid transparent; border-bottom-color: #ffffff; }
        .dark #directoryTooltipPortal::after { border-bottom-color: #0f141e; }
        #directoryTooltipPortal.tooltip-above::after { top: 100%; bottom: auto; border-bottom-color: transparent; border-top-color: #ffffff; }
        .dark #directoryTooltipPortal.tooltip-above::after { border-top-color: #0f141e; }

        .tooltip-indigo .tooltip-trigger { background: #e0e7ff; color: #4f46e5; border-color: #c7d2fe; }
        .dark .tooltip-indigo .tooltip-trigger { background: #6366f1; color: #fff; border-color: transparent; box-shadow: 0 0 10px rgba(99,102,241,.50); }
        .tooltip-emerald .tooltip-trigger { background: #d1fae5; color: #059669; border-color: #a7f3d0; }
        .dark .tooltip-emerald .tooltip-trigger { background: #10b981; color: #fff; border-color: transparent; box-shadow: 0 0 10px rgba(16,185,129,.48); }
        .tooltip-rose .tooltip-trigger { background: #ffe4e6; color: #e11d48; border-color: #fecdd3; }
        .dark .tooltip-rose .tooltip-trigger { background: #f43f5e; color: #fff; border-color: transparent; box-shadow: 0 0 10px rgba(244,63,94,.44); }

        /* ==========================================================
           SCROLL HALUS
           Area konten memakai momentum pada sentuhan dan interpolasi halus
           untuk roda mouse desktop. Scrollbar diberi gutter stabil agar isi
           halaman tidak bergeser ketika modal atau status scroll berubah.
           ========================================================== */
        html { scroll-behavior: smooth; }
        .smooth-directory-scroll {
            scroll-behavior: auto;
            scroll-padding-top: 7.5rem;
            overscroll-behavior-y: contain;
            -webkit-overflow-scrolling: touch;
            scrollbar-gutter: stable both-edges;
            scroll-snap-type: none;
            scrollbar-width: thin;
            scrollbar-color: rgba(99,102,241,.38) transparent;
        }
        .smooth-directory-scroll:focus { outline: none; }
        .smooth-directory-scroll.is-smooth-scrolling { cursor: default; }

        /* Header selalu terlihat di atas area konten; tooltip dipindahkan ke body. */
        .directory-page-header { z-index: 1000 !important; overflow: visible !important; }
        .header-guide-tooltip { isolation: isolate; }

        /* Saat modal aktif, konten di belakang tidak dapat digulir dan layout tetap stabil. */
        .modal-open { overflow: hidden; padding-right: 0; }
        .modal-open .smooth-directory-scroll { overflow-y: hidden !important; }

        @media (prefers-reduced-motion: reduce) {
            html, .smooth-directory-scroll { scroll-behavior: auto; }
            #directoryTooltipPortal { transition: none; }
        }

        /* ==========================================================
           PENYELARASAN FINAL — BAHASA VISUAL DASBOR ADMIN
           Semua komponen berikut menggunakan proporsi, kedalaman,
           hover, dan hirarki yang sama dengan halaman Dasbor.
           ========================================================== */
        .glass-sidebar {
            background: rgba(255, 255, 255, .95);
            border-right-color: rgba(0, 0, 0, .05);
        }
        .dark .glass-sidebar {
            background: rgba(5, 8, 16, .95);
            border-right-color: rgba(255, 255, 255, .05);
        }

        .glass-header {
            background: rgba(255, 255, 255, .80);
            border-bottom-color: rgba(0, 0, 0, .05);
            backdrop-filter: blur(12px);
        }
        .dark .glass-header {
            background: rgba(2, 6, 23, .80);
            border-bottom-color: rgba(255, 255, 255, .05);
        }

        .glass-card {
            background: rgba(255, 255, 255, .85);
            border-color: rgba(0, 0, 0, .05);
            box-shadow: 0 4px 30px rgba(0, 0, 0, .03);
            backdrop-filter: blur(10px);
            transition: all .3s cubic-bezier(.4, 0, .2, 1);
        }
        .dark .glass-card {
            background: rgba(10, 14, 23, .85);
            border-color: rgba(255, 255, 255, .08);
            box-shadow: 0 4px 30px rgba(0, 0, 0, .20);
        }
        .glass-card:hover {
            border-color: rgba(99, 102, 241, .40);
            transform: translateY(-4px);
            box-shadow: 0 10px 40px -10px rgba(0, 0, 0, .10);
            z-index: 30;
        }
        .dark .glass-card:hover {
            box-shadow: 0 10px 40px -10px rgba(0, 0, 0, .50);
        }

        .glass-input {
            background: rgba(0, 0, 0, .03);
            border-color: rgba(0, 0, 0, .10);
            transition: .3s;
        }
        .dark .glass-input {
            background: rgba(255, 255, 255, .03);
            border-color: rgba(255, 255, 255, .10);
        }
        .glass-input:focus {
            border-color: #6366f1;
            background: rgba(0, 0, 0, .05);
            box-shadow: 0 0 0 2px rgba(99, 102, 241, .20);
        }
        .dark .glass-input:focus { background: rgba(255, 255, 255, .05); }

        .brand-bar::after { display: none; }
        .brand-bar .brand-orb {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 5rem;
            height: 5rem;
            border-radius: 9999px;
            background: rgba(199, 210, 254, .52);
            filter: blur(40px);
            opacity: 0;
            transform: translate(-50%, -50%);
            transition: opacity .5s ease;
            pointer-events: none;
        }
        .dark .brand-bar .brand-orb { background: rgba(99, 102, 241, .20); }
        .brand-bar:hover .brand-orb { opacity: 1; }

        .dashboard-page-header {
            min-height: 6rem;
            padding-left: 1.5rem;
            padding-right: 1.5rem;
        }
        @media (min-width: 768px) {
            .dashboard-page-header {
                padding-left: 2.5rem;
                padding-right: 2.5rem;
            }
        }

        .dashboard-section-label {
            letter-spacing: .18em;
            text-transform: uppercase;
        }
        .dashboard-metric-card { min-height: 178px; }
        .dashboard-metric-card .metric-icon {
            transition: transform .5s cubic-bezier(.22, .61, .36, 1), opacity .3s ease;
        }
        .dashboard-metric-card:hover .metric-icon {
            opacity: .14;
            transform: scale(1.14) rotate(4deg);
        }

        .dashboard-table-row {
            border-bottom: 1px solid rgba(0, 0, 0, .03);
            transition: background .2s ease;
        }
        .dashboard-table-row:hover { background: rgba(0, 0, 0, .02); }
        .dark .dashboard-table-row { border-bottom-color: rgba(255, 255, 255, .03); }
        .dark .dashboard-table-row:hover { background: rgba(255, 255, 255, .02); }

        .directory-shell {
            overflow: hidden;
            isolation: isolate;
        }
        .directory-shell:hover { transform: none; }
        .directory-shell > .directory-panel-header {
            background: rgba(248, 250, 252, .70);
        }
        .dark .directory-shell > .directory-panel-header {
            background: rgba(2, 6, 23, .55);
        }

        .dashboard-action-button {
            transition: transform .2s ease, box-shadow .2s ease, background-color .2s ease, border-color .2s ease;
        }
        .dashboard-action-button:hover { transform: translateY(-1px); }
        .dashboard-primary-action:hover {
            box-shadow: 0 10px 22px rgba(79, 70, 229, .22);
        }
        .dashboard-soft-action:hover {
            border-color: rgba(99, 102, 241, .32);
            color: #4f46e5;
        }

        .directory-mobile-card {
            background: rgba(255, 255, 255, .85);
            border-color: rgba(0, 0, 0, .05);
            box-shadow: 0 4px 30px rgba(0, 0, 0, .03);
        }
        .dark .directory-mobile-card {
            background: rgba(10, 14, 23, .85);
            border-color: rgba(255, 255, 255, .08);
        }
        .directory-mobile-card:hover {
            border-color: rgba(99, 102, 241, .40);
            box-shadow: 0 10px 40px -10px rgba(0, 0, 0, .10);
        }



        /* ==========================================================
           PENYEMPURNAAN RINGKAS — mengurangi teks berulang dan gerak berlebih
           ========================================================== */
        .glass-card {
            transition: border-color .22s ease, box-shadow .22s ease;
        }
        .glass-card:hover {
            transform: none;
            border-color: rgba(15, 23, 42, .07);
            box-shadow: 0 4px 30px rgba(15, 23, 42, .035);
        }
        .dark .glass-card:hover {
            border-color: rgba(255, 255, 255, .08);
            box-shadow: 0 4px 30px rgba(0, 0, 0, .20);
        }

        .metric-card,
        .dashboard-metric-card {
            min-height: 138px;
            overflow: hidden;
        }
        .metric-card:hover,
        .dashboard-metric-card:hover {
            transform: translateY(-2px);
            border-color: rgba(99, 102, 241, .30);
            box-shadow: 0 12px 30px -20px rgba(15, 23, 42, .30);
        }
        .dark .metric-card:hover,
        .dark .dashboard-metric-card:hover {
            box-shadow: 0 16px 32px -22px rgba(0, 0, 0, .62);
        }
        .metric-card .metric-icon,
        .dashboard-metric-card .metric-icon {
            opacity: .055;
        }
        .metric-card:hover .metric-icon,
        .dashboard-metric-card:hover .metric-icon {
            opacity: .09;
            transform: scale(1.06) rotate(2deg);
        }

        .directory-toolbar {
            background: rgba(255, 255, 255, .82);
            box-shadow: 0 8px 26px rgba(15, 23, 42, .025);
        }
        .dark .directory-toolbar { background: rgba(10, 14, 23, .82); }
        .directory-toolbar:hover { border-color: rgba(15, 23, 42, .07); }
        .dark .directory-toolbar:hover { border-color: rgba(255, 255, 255, .08); }

        .directory-panel-header {
            min-height: 74px;
        }
        .directory-panel-header:hover { transform: none; }
        .student-row:hover { background: rgba(99, 102, 241, .032); }
        .student-row:hover .student-avatar {
            transform: none;
            box-shadow: 0 4px 12px -8px rgba(79, 70, 229, .28);
        }
        .directory-mobile-card:hover {
            transform: none;
            border-color: rgba(99, 102, 241, .22);
            box-shadow: 0 8px 22px -18px rgba(15, 23, 42, .30);
        }

        @media (max-width: 767px) {
            .dashboard-page-header { min-height: 5.5rem; }
            .metric-card,
            .dashboard-metric-card { min-height: 126px; }
        }


        /* ==========================================================
           PENYAJIAN DATA SISWA — fokus pada pemindaian informasi
           ========================================================== */
        .directory-data-scroll {
            max-height: min(62vh, 600px);
            scrollbar-gutter: stable;
            background: linear-gradient(180deg, rgba(255,255,255,.42), rgba(255,255,255,.08));
        }
        .dark .directory-data-scroll {
            background: linear-gradient(180deg, rgba(255,255,255,.018), rgba(255,255,255,0));
        }
        .directory-table {
            border-collapse: separate;
            border-spacing: 0;
        }
        .directory-table thead th {
            position: sticky;
            top: 0;
            z-index: 8;
            background: rgba(255,255,255,.96);
            backdrop-filter: blur(14px);
            box-shadow: 0 1px 0 rgba(15,23,42,.06);
        }
        .dark .directory-table thead th {
            background: rgba(10,14,23,.96);
            box-shadow: 0 1px 0 rgba(255,255,255,.07);
        }
        .directory-table-row td {
            vertical-align: middle;
        }
        .directory-table-row:hover {
            background: rgba(99,102,241,.028);
        }
        .student-profile-meta {
            display: flex;
            align-items: center;
            gap: .4rem;
            margin-top: .3rem;
            color: #94a3b8;
            font-size: 10px;
            font-weight: 600;
        }
        .dark .student-profile-meta { color: rgba(255,255,255,.30); }
        .student-progress-track {
            height: 7px;
            overflow: hidden;
            border-radius: 999px;
            background: rgba(148,163,184,.14);
        }
        .dark .student-progress-track { background: rgba(255,255,255,.07); }
        .student-progress-fill {
            height: 100%;
            border-radius: inherit;
            background: linear-gradient(90deg, #6366f1, #818cf8);
        }
        .student-score-number {
            letter-spacing: -.03em;
            font-variant-numeric: tabular-nums;
        }
        .directory-mobile-student-card {
            padding: 1rem;
        }
        .directory-mobile-stat {
            border: 1px solid rgba(15,23,42,.06);
            background: rgba(248,250,252,.82);
            border-radius: .9rem;
            padding: .75rem;
        }
        .dark .directory-mobile-stat {
            border-color: rgba(255,255,255,.07);
            background: rgba(255,255,255,.035);
        }
        .directory-mobile-detail {
            justify-content: center;
            width: 100%;
            border-color: rgba(99,102,241,.18);
            background: rgba(238,242,255,.72);
            color: #4f46e5;
        }
        .directory-mobile-detail:hover {
            border-color: rgba(99,102,241,.38);
            background: rgba(224,231,255,.86);
        }


        /* ==========================================================
           RINGKASAN LEARNING ANALYTICS PADA DIREKTORI
           Satu informasi kelas, lalu indikator pembelajaran yang
           dapat dipindai cepat tanpa teks status yang berulang.
           ========================================================== */
        .la-class-label,
        .la-class-empty {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            max-width: 180px;
            border-radius: .85rem;
            padding: .55rem .7rem;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: .06em;
            line-height: 1.25;
        }
        .la-class-label {
            border: 1px solid rgba(15,23,42,.08);
            background: rgba(255,255,255,.74);
            color: #475569;
        }
        .la-class-empty {
            border: 1px dashed rgba(148,163,184,.52);
            background: rgba(248,250,252,.72);
            color: #64748b;
        }
        .dark .la-class-label {
            border-color: rgba(255,255,255,.10);
            background: rgba(255,255,255,.045);
            color: rgba(255,255,255,.68);
        }
        .dark .la-class-empty {
            border-color: rgba(255,255,255,.18);
            background: rgba(255,255,255,.028);
            color: rgba(255,255,255,.48);
        }

        .la-summary {
            display: grid;
            grid-template-columns: 58px minmax(150px, 1fr);
            align-items: center;
            gap: .85rem;
            min-width: 250px;
        }
        .la-progress-ring {
            --progress: 0%;
            position: relative;
            display: grid;
            width: 58px;
            height: 58px;
            place-items: center;
            border-radius: 999px;
            background: conic-gradient(#6366f1 var(--progress), rgba(148,163,184,.18) 0);
        }
        .la-progress-ring::after {
            position: absolute;
            inset: 6px;
            content: '';
            border-radius: inherit;
            background: rgba(255,255,255,.98);
        }
        .dark .la-progress-ring::after { background: #0a0e17; }
        .la-progress-ring > span {
            position: relative;
            z-index: 1;
            font-size: 11px;
            font-weight: 900;
            letter-spacing: -.02em;
            color: #3730a3;
            font-variant-numeric: tabular-nums;
        }
        .dark .la-progress-ring > span { color: #c7d2fe; }

        .la-signal-list { display: grid; gap: .42rem; }
        .la-signal-row { min-width: 0; }
        .la-signal-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .5rem;
            color: #64748b;
            font-size: 9px;
            font-weight: 800;
            letter-spacing: .035em;
        }
        .dark .la-signal-head { color: rgba(255,255,255,.44); }
        .la-signal-head b {
            color: #475569;
            font-size: 10px;
            font-variant-numeric: tabular-nums;
        }
        .dark .la-signal-head b { color: rgba(255,255,255,.76); }
        .la-signal-track {
            height: 4px;
            margin-top: .25rem;
            overflow: hidden;
            border-radius: 999px;
            background: rgba(148,163,184,.16);
        }
        .dark .la-signal-track { background: rgba(255,255,255,.075); }
        .la-signal-fill {
            display: block;
            height: 100%;
            min-width: 0;
            border-radius: inherit;
        }
        .la-signal-fill--materi { background: #6366f1; }
        .la-signal-fill--kuis { background: #06b6d4; }
        .la-signal-fill--lab { background: #10b981; }

        .la-performance-score {
            display: flex;
            align-items: baseline;
            gap: .22rem;
            color: #0f172a;
            font-variant-numeric: tabular-nums;
        }
        .dark .la-performance-score { color: #fff; }
        .la-performance-score strong {
            font-size: 1.15rem;
            font-weight: 900;
            letter-spacing: -.045em;
        }
        .la-performance-score span {
            color: #94a3b8;
            font-size: 10px;
            font-weight: 800;
        }
        .la-performance-note {
            margin-top: .18rem;
            color: #94a3b8;
            font-size: 10px;
            font-weight: 700;
        }
        .dark .la-performance-note { color: rgba(255,255,255,.36); }

        .la-activity {
            display: grid;
            gap: .42rem;
            min-width: 138px;
        }
        .la-learning-condition {
            display: inline-flex;
            width: fit-content;
            align-items: center;
            gap: .38rem;
            border-radius: 999px;
            padding: .35rem .58rem;
            font-size: 9px;
            font-weight: 900;
            letter-spacing: .055em;
            line-height: 1;
            text-transform: uppercase;
        }
        .la-learning-condition::before {
            width: 6px;
            height: 6px;
            content: '';
            border-radius: 999px;
            background: currentColor;
            box-shadow: 0 0 0 3px currentColor;
            opacity: .18;
        }
        .la-condition--indigo { background: rgba(238,242,255,.92); color: #4f46e5; }
        .la-condition--emerald { background: rgba(236,253,245,.92); color: #059669; }
        .la-condition--rose { background: rgba(255,241,242,.92); color: #e11d48; }
        .la-condition--amber { background: rgba(255,251,235,.94); color: #b45309; }
        .dark .la-condition--indigo { background: rgba(99,102,241,.14); color: #c7d2fe; }
        .dark .la-condition--emerald { background: rgba(16,185,129,.13); color: #a7f3d0; }
        .dark .la-condition--rose { background: rgba(244,63,94,.13); color: #fecdd3; }
        .dark .la-condition--amber { background: rgba(245,158,11,.13); color: #fde68a; }
        .la-activity-time {
            color: #94a3b8;
            font-size: 10px;
            font-weight: 700;
            line-height: 1.35;
        }
        .dark .la-activity-time { color: rgba(255,255,255,.36); }

        .mobile-la-panel {
            display: grid;
            grid-template-columns: 66px minmax(0, 1fr);
            gap: .9rem;
            align-items: center;
            border: 1px solid rgba(15,23,42,.06);
            border-radius: 1rem;
            background: rgba(248,250,252,.78);
            padding: .85rem;
        }
        .dark .mobile-la-panel {
            border-color: rgba(255,255,255,.06);
            background: rgba(255,255,255,.025);
        }
        .mobile-la-panel .la-progress-ring {
            width: 64px;
            height: 64px;
        }
        .mobile-la-panel .la-progress-ring::after { inset: 6px; }
        .mobile-la-panel .la-signal-list { gap: .48rem; }
        .mobile-la-meta {
            margin-top: .9rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .75rem;
            border-top: 1px solid rgba(15,23,42,.06);
            padding-top: .8rem;
        }
        .dark .mobile-la-meta { border-top-color: rgba(255,255,255,.06); }
        .mobile-la-meta .la-activity { min-width: 0; }
        .mobile-la-meta .la-performance-score strong { font-size: 1.05rem; }

        @media (max-width: 1023px) {
            .la-summary { min-width: 220px; grid-template-columns: 52px minmax(130px, 1fr); gap: .72rem; }
            .la-progress-ring { width: 52px; height: 52px; }
        }
        .dark .directory-mobile-detail {
            border-color: rgba(129,140,248,.22);
            background: rgba(99,102,241,.12);
            color: #c7d2fe;
        }
        .dark .directory-mobile-detail:hover { background: rgba(99,102,241,.20); }

        @media (max-width: 1023px) {
            .directory-data-scroll { max-height: none; }
        }


        /* ==========================================================
           PENYEMPURNAAN INTERAKSI — GULIR & HOVER YANG LEBIH HALUS
           Gerak dibuat ringan, konsisten, dan tidak menggeser struktur data.
           ========================================================== */
        :root {
            --directory-ease: cubic-bezier(.22, .61, .36, 1);
            --directory-soft-ease: cubic-bezier(.16, 1, .3, 1);
        }

        .smooth-directory-scroll {
            scroll-behavior: auto;
            overscroll-behavior-y: contain;
            -webkit-overflow-scrolling: touch;
            touch-action: pan-y;
        }
        [data-native-scroll],
        .directory-data-scroll {
            scroll-behavior: smooth;
            overscroll-behavior: contain;
            -webkit-overflow-scrolling: touch;
        }
        .directory-data-scroll {
            scrollbar-width: thin;
            scrollbar-color: rgba(99,102,241,.28) transparent;
        }
        .directory-data-scroll::-webkit-scrollbar,
        [data-native-scroll]::-webkit-scrollbar { width: 6px; height: 6px; }
        .directory-data-scroll::-webkit-scrollbar-thumb,
        [data-native-scroll]::-webkit-scrollbar-thumb {
            background: rgba(148,163,184,.34);
            border: 2px solid transparent;
            border-radius: 999px;
            background-clip: padding-box;
            transition: background-color .24s var(--directory-ease);
        }
        .directory-data-scroll::-webkit-scrollbar-thumb:hover,
        [data-native-scroll]::-webkit-scrollbar-thumb:hover { background-color: rgba(99,102,241,.52); }
        .dark .directory-data-scroll::-webkit-scrollbar-thumb,
        .dark [data-native-scroll]::-webkit-scrollbar-thumb { background-color: rgba(255,255,255,.16); }

        .directory-table-row {
            transition: background-color .26s var(--directory-ease), box-shadow .26s var(--directory-ease);
        }
        .directory-table-row > td {
            transition: color .22s var(--directory-ease), background-color .26s var(--directory-ease);
        }
        .student-avatar,
        .la-progress-ring,
        .student-insight-trigger,
        .directory-table-row a,
        .directory-table-row button,
        .directory-mobile-student-card,
        .directory-mobile-detail,
        .la-learning-condition,
        .class-filter,
        .dashboard-action-button,
        .nav-link {
            transition-timing-function: var(--directory-ease);
        }
        .student-avatar {
            transition-property: transform, box-shadow, filter;
            transition-duration: .34s;
        }
        .la-progress-ring {
            transition: transform .34s var(--directory-soft-ease), box-shadow .34s var(--directory-ease), filter .34s var(--directory-ease);
        }
        .la-signal-fill {
            transition: width .46s var(--directory-soft-ease), filter .28s var(--directory-ease);
        }
        .student-insight-trigger {
            transition-property: color, transform, opacity;
            transition-duration: .22s;
        }
        .directory-table-row a,
        .directory-table-row button,
        .dashboard-action-button,
        .directory-mobile-detail {
            transition-property: color, background-color, border-color, box-shadow, transform, opacity;
            transition-duration: .22s;
        }
        .directory-mobile-student-card {
            transition: border-color .3s var(--directory-ease), box-shadow .3s var(--directory-ease), transform .3s var(--directory-soft-ease), background-color .3s var(--directory-ease);
        }
        .la-learning-condition { transition: color .24s var(--directory-ease), background-color .24s var(--directory-ease), box-shadow .24s var(--directory-ease); }
        .class-filter,
        .dashboard-action-button,
        .nav-link { transition: color .22s var(--directory-ease), background-color .22s var(--directory-ease), border-color .22s var(--directory-ease), box-shadow .22s var(--directory-ease), transform .22s var(--directory-ease); }

        @media (hover: hover) and (pointer: fine) {
            .directory-table-row:hover {
                background: linear-gradient(90deg, rgba(99,102,241,.052), rgba(99,102,241,.015) 58%, transparent);
                box-shadow: inset 3px 0 0 rgba(99,102,241,.42);
            }
            .dark .directory-table-row:hover {
                background: linear-gradient(90deg, rgba(129,140,248,.115), rgba(129,140,248,.030) 58%, transparent);
                box-shadow: inset 3px 0 0 rgba(129,140,248,.62);
            }
            .directory-table-row:hover .student-avatar {
                transform: translateY(-1px) scale(1.026);
                box-shadow: 0 12px 26px -14px rgba(79,70,229,.55);
                filter: saturate(1.04);
            }
            .directory-table-row:hover .la-progress-ring {
                transform: scale(1.035);
                box-shadow: 0 8px 18px -12px rgba(79,70,229,.45);
            }
            .directory-table-row:hover .la-signal-fill { filter: saturate(1.08) brightness(1.02); }
            .directory-table-row .student-insight-trigger:hover { transform: translateX(1px); }
            .directory-table-row a:hover,
            .directory-table-row button:not(.student-insight-trigger):hover,
            .dashboard-action-button:hover,
            .directory-mobile-detail:hover {
                transform: translateY(-1px);
            }
            .directory-mobile-student-card:hover {
                transform: translateY(-2px);
                border-color: rgba(99,102,241,.30);
                box-shadow: 0 18px 34px -24px rgba(15,23,42,.38);
            }
            .dark .directory-mobile-student-card:hover { box-shadow: 0 20px 38px -24px rgba(0,0,0,.72); }
            .directory-mobile-student-card:hover .student-avatar { transform: translateY(-1px) scale(1.022); }
            .directory-mobile-student-card:hover .la-progress-ring { transform: scale(1.025); }
            .class-filter:hover { transform: translateY(-1px); }
            .nav-link:hover { transform: translateX(1px); }
            .nav-link.active:hover { transform: translateX(0); }
        }

        @media (hover: none), (pointer: coarse) {
            .directory-table-row:hover,
            .dark .directory-table-row:hover { box-shadow: none; background: transparent; }
            .directory-table-row:hover .student-avatar,
            .directory-table-row:hover .la-progress-ring,
            .directory-mobile-student-card:hover,
            .directory-mobile-student-card:hover .student-avatar,
            .directory-mobile-student-card:hover .la-progress-ring { transform: none; }
        }

        @media (prefers-reduced-motion: reduce) {
            .smooth-directory-scroll,
            [data-native-scroll],
            .directory-data-scroll { scroll-behavior: auto !important; }
            .directory-table-row,
            .directory-table-row > td,
            .student-avatar,
            .la-progress-ring,
            .la-signal-fill,
            .student-insight-trigger,
            .directory-table-row a,
            .directory-table-row button,
            .directory-mobile-student-card,
            .directory-mobile-detail,
            .la-learning-condition,
            .class-filter,
            .dashboard-action-button,
            .nav-link { transition-duration: .01ms !important; }
        }

        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="flex h-screen w-full bg-slate-50 text-slate-800 transition-colors duration-500 dark:bg-[#020617] dark:text-slate-200"
      x-data="{ sidebarOpen: false, showImport: false, showAdd: false, showDirectoryGuide: false, isFullscreen: false }"
      @keydown.escape.window="showImport = false; showAdd = false; showDirectoryGuide = false; isFullscreen = false; document.exitFullscreen && document.exitFullscreen(); $('#studentInsightContent').removeClass('scale-100 opacity-100').addClass('scale-95 opacity-0'); $('#studentInsightModal').addClass('hidden').removeClass('flex'); $('body').removeClass('modal-open');"
      :class="{ 'modal-open': sidebarOpen || showImport || showAdd || showDirectoryGuide }">

    <div x-show="sidebarOpen" class="fixed inset-0 z-[90] bg-slate-900/60 backdrop-blur-sm md:hidden dark:bg-[#020617]/80" @click="sidebarOpen = false" x-transition.opacity x-cloak></div>

    <aside class="glass-sidebar fixed z-[100] flex h-full w-72 flex-col transition-transform duration-300 md:relative md:translate-x-0" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
        <div class="brand-bar group relative flex h-24 items-center justify-between overflow-hidden border-b border-slate-200 px-8 transition-colors dark:border-white/5">
            <div class="brand-orb"></div>
            <a href="{{ route('landing') }}" class="relative z-10 flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" class="block h-8 w-auto object-contain dark:hidden" style="filter: brightness(0.1);" alt="Logo">
                <img src="{{ asset('images/logo.png') }}" class="hidden h-8 w-auto object-contain drop-shadow-sm dark:block" alt="Logo Dark">
                <div>
                    <h1 class="text-xl font-black leading-none tracking-tight text-slate-900 transition-colors dark:text-white">Util<span class="text-indigo-600 dark:text-indigo-400">wind</span></h1>
                    <span class="text-[9px] font-bold uppercase tracking-[0.2em] text-slate-500 transition-colors dark:text-white/40">Panel Admin</span>
                </div>
            </a>
            <button @click="sidebarOpen = false" class="relative z-10 text-slate-500 transition-colors hover:text-slate-800 md:hidden dark:text-white/50 dark:hover:text-white">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        @include('admin.partials.sidebar-nav')

        <div class="border-t border-slate-200 bg-slate-50/60 p-4 transition-colors dark:border-white/5 dark:bg-[#05080f]/60">
            <div class="mb-4 flex items-center gap-3 px-2">
                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-fuchsia-500 text-xs font-bold text-white shadow-lg">AD</div>
                <div class="min-w-0">
                    <p class="truncate text-xs font-bold text-slate-900 transition-colors dark:text-white">{{ Auth::user()->name ?? 'Administrator' }}</p>
                    <p class="truncate text-[10px] text-slate-500 transition-colors dark:text-white/40">Administrator Sistem</p>
                </div>
            </div>
            <button id="theme-toggle-sidebar" type="button" class="mb-2 flex w-full items-center justify-center gap-2 rounded-lg border border-transparent bg-slate-200/60 px-4 py-2.5 text-xs font-bold text-slate-700 transition-colors hover:bg-slate-200 dark:bg-white/5 dark:text-slate-300 dark:hover:bg-white/10">
                <svg id="theme-toggle-dark-icon-sidebar" class="hidden h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/></svg>
                <svg id="theme-toggle-light-icon-sidebar" class="hidden h-4 w-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>
                <span id="theme-toggle-text-sidebar">Ubah Tema</span>
            </button>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="flex w-full items-center justify-center gap-2 rounded-lg border border-red-200 bg-red-50 px-4 py-2.5 text-xs font-bold text-red-600 transition-colors hover:bg-red-100 dark:border-red-500/20 dark:bg-red-500/10 dark:text-red-400 dark:hover:bg-red-500 dark:hover:text-white">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <main id="admin-main-content" data-smooth-scroll class="smooth-directory-scroll relative z-10 flex h-full flex-1 flex-col overflow-y-auto overflow-x-hidden custom-scrollbar" tabindex="-1">
        <div class="pointer-events-none fixed inset-0 z-0">
            <div class="absolute left-[20%] top-[8%] h-[500px] w-[500px] rounded-full bg-indigo-300/20 blur-[120px] transition-colors duration-500 dark:bg-indigo-600/10"></div>
            <div class="absolute bottom-[10%] right-[8%] h-[400px] w-[400px] rounded-full bg-cyan-300/20 blur-[120px] transition-colors duration-500 dark:bg-cyan-600/10"></div>
            <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.02] mix-blend-overlay transition-opacity duration-500 dark:opacity-[0.04]"></div>
        </div>

        <header class="dashboard-page-header directory-page-header glass-header sticky top-0 z-40 flex h-24 shrink-0 flex-col justify-center transition-colors duration-500">
            <div class="flex h-full w-full items-center justify-between gap-4 text-left">
                <div class="flex min-w-0 items-center gap-4">
                    <button @click="sidebarOpen = true" class="rounded-lg bg-slate-100 p-2 text-slate-700 transition-colors hover:bg-slate-200 md:hidden dark:bg-white/5 dark:text-white dark:hover:bg-white/10" aria-label="Buka navigasi">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>

                    <div class="min-w-0 text-left">
                        <nav class="mb-1.5 hidden text-[10px] font-bold text-slate-500 transition-colors sm:flex dark:text-white/50" aria-label="Breadcrumb">
                            <ol class="inline-flex items-center gap-1.5">
                                <li><a href="{{ route('admin.dashboard') }}" class="transition-colors hover:text-indigo-600 dark:hover:text-indigo-400">Dasbor</a></li>
                                
                                <li class="text-slate-300 dark:text-white/20">/</li>
                                <li aria-current="page" class="text-slate-700 dark:text-white">Direktori Siswa</li>
                            </ol>
                        </nav>

                        <div class="flex min-w-0 items-center gap-2">
                            <h2 class="truncate text-lg font-bold tracking-tight text-slate-900 transition-colors md:text-xl dark:text-white">Direktori Siswa</h2>
                            <div class="tooltip-container header-guide-tooltip tooltip-indigo tooltip-down tooltip-left shrink-0">
                                <button type="button" @click.stop="showDirectoryGuide = true" class="tooltip-trigger" aria-label="Buka panduan Direktori Siswa" title="Panduan Direktori Siswa">?</button>
                                <div class="tooltip-content" role="tooltip">
                                    <span class="mb-1 block font-bold text-indigo-600 dark:text-indigo-300">Panduan Direktori</span>
                                    Cari, saring, lalu gunakan tombol aksi untuk membuka insight atau detail siswa.
                                </div>
                            </div>
                        </div>

                        <p class="mt-0.5 flex items-center gap-1.5 text-[9px] text-slate-500 transition-colors md:text-xs dark:text-white/40">
                            <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-emerald-500 shadow-[0_0_8px_#10b981]"></span>
                            Pemantauan data siswa dan aktivitas pembelajaran.
                        </p>
                    </div>
                </div>

                <div class="flex shrink-0 items-center gap-2 sm:gap-3">
                    <button id="refreshDirectory" type="button" class="dashboard-action-button group hidden rounded-full border border-transparent p-2.5 text-slate-400 transition-colors hover:bg-slate-100 hover:text-slate-700 sm:block dark:text-white/40 dark:hover:border-white/10 dark:hover:bg-white/5 dark:hover:text-white" title="Perbarui Data">
                        <svg class="h-4 w-4 transition-transform duration-500 group-hover:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </button>

                    <button @click="isFullscreen = !isFullscreen; isFullscreen ? document.documentElement.requestFullscreen() : document.exitFullscreen()" class="dashboard-action-button hidden rounded-full border border-transparent p-2.5 text-slate-400 transition-colors hover:bg-slate-100 hover:text-slate-700 md:block dark:text-white/40 dark:hover:border-white/10 dark:hover:bg-white/5 dark:hover:text-white" title="Mode Layar Penuh">
                        <svg x-show="!isFullscreen" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                        <svg x-show="isFullscreen" x-cloak class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>

                    <div class="hidden border-l border-slate-200 pl-3 dark:border-white/10 lg:block">
                        <button @click="showImport = true" class="dashboard-action-button dashboard-soft-action rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-bold text-slate-700 shadow-sm dark:border-white/10 dark:bg-white/5 dark:text-white/70 dark:hover:border-indigo-400/30 dark:hover:text-white">
                            Impor CSV
                        </button>
                    </div>

                    <button @click="showAdd = true" class="dashboard-action-button dashboard-primary-action inline-flex items-center gap-2 rounded-xl border border-indigo-500 bg-indigo-600 px-3.5 py-2.5 text-xs font-bold text-white shadow-md hover:bg-indigo-500 dark:shadow-[0_0_15px_rgba(99,102,241,.30)]">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span class="hidden sm:inline">Tambah Siswa</span>
                    </button>

                    <div class="hidden border-l border-slate-200 pl-5 text-right transition-colors dark:border-white/10 xl:block">
                        <p class="text-sm font-bold text-slate-900 dark:text-white">{{ \Carbon\Carbon::now()->translatedFormat('d M Y') }}</p>
                        <p class="mt-0.5 font-mono text-[10px] text-slate-500 transition-colors dark:text-white/40">{{ \Carbon\Carbon::now()->format('H:i') }} WIB</p>
                    </div>
                </div>
            </div>
        </header>

        <section class="relative z-10 p-4 md:p-8 lg:p-10">
            <div class="mx-auto max-w-7xl space-y-6">
            @php
                $studentTotal = (int) ($summary['total_students'] ?? 0);
                $studentWithClass = (int) ($summary['with_class'] ?? 0);
                $studentActive = (int) ($summary['active_students'] ?? 0);
                $studentNeedAttention = (int) ($summary['need_attention'] ?? 0);
                $studentAvgProgress = $summary['avg_progress'] ?? 0;
                $analyticsTitle = 'Ringkasan Siswa';
                $analyticsSubtitle = null;
                $analyticsItems = [
                    ['label' => 'Siswa', 'value' => number_format($studentTotal), 'hint' => 'total', 'tone' => 'indigo'],
                    ['label' => 'Kelas', 'value' => number_format($studentWithClass), 'hint' => number_format(max(0, $studentTotal - $studentWithClass)) . ' kosong', 'tone' => $studentWithClass > 0 ? 'cyan' : 'amber'],
                    ['label' => 'Aktif', 'value' => number_format($studentActive), 'hint' => $studentAvgProgress . '% progres', 'tone' => 'emerald'],
                    ['label' => 'Cek', 'value' => number_format($studentNeedAttention), 'hint' => 'remedial', 'tone' => $studentNeedAttention > 0 ? 'rose' : 'emerald'],
                ];
                $analyticsActions = [];
            @endphp
            @include('admin.partials.compact_analytics_strip')

            <div class="glass-card directory-toolbar reveal rounded-2xl p-4 md:p-5" style="animation-delay: .2s">
                <div class="relative z-10 flex flex-col gap-4 lg:flex-row lg:items-center">
                    <div class="shrink-0">
                        <h3 class="text-sm font-black text-slate-900 dark:text-white">Filter Siswa</h3>
                    </div>

                    <div class="grid w-full flex-1 grid-cols-1 gap-3 sm:grid-cols-[minmax(0,1fr)_auto]">
                        <label class="relative block">
                            <span class="sr-only">Cari siswa</span>
                            <input id="studentSearch" type="text" placeholder="Cari nama, email, atau kelas" class="glass-input w-full rounded-xl py-3 pl-10 pr-4 text-sm font-semibold placeholder:text-slate-400 dark:placeholder:text-white/25">
                            <svg class="absolute left-3 top-3.5 h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 011 0z"/></svg>
                        </label>
                        <label class="block">
                            <span class="sr-only">Saring berdasarkan status belajar</span>
                            <select id="statusFilter" class="glass-input rounded-xl px-4 py-3 text-xs font-black uppercase tracking-widest" aria-label="Saring berdasarkan status belajar">
                                <option value="all">Semua Status</option>
                                <option value="active">Aktif Belajar</option>
                                <option value="attention">Perlu Penguatan</option>
                                <option value="complete">Tuntas</option>
                                <option value="idle">Belum Mulai</option>
                                <option value="unassigned">Belum Masuk Kelas</option>
                            </select>
                        </label>
                    </div>
                </div>

                <div data-native-scroll class="relative z-10 mt-4 flex gap-2 overflow-x-auto pb-1 custom-scrollbar">
                    <button type="button" data-class-filter="all" class="class-filter active shrink-0 rounded-full px-4 py-2 text-[10px] font-black uppercase tracking-widest" aria-pressed="true">Semua Kelas</button>
                    @foreach($classSummaries as $class)
                        <button type="button" data-class-filter="{{ $class['name'] }}" class="class-filter shrink-0 rounded-full px-4 py-2 text-[10px] font-black uppercase tracking-widest" aria-pressed="false">
                            {{ $class['name'] }} <span class="ml-1 font-mono opacity-60">{{ $class['total'] }}</span>
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="glass-card directory-shell reveal rounded-2xl" style="animation-delay: .25s">
                <div class="directory-panel-header relative flex flex-col gap-3 border-b border-slate-200 px-5 py-4 transition-colors sm:flex-row sm:items-center sm:justify-between dark:border-white/5">
                    <div class="relative min-w-0">
                        <h3 class="text-sm font-black text-slate-900 dark:text-white">Daftar Siswa</h3>
                        <p class="mt-1 text-[11px] font-semibold text-slate-500 dark:text-white/40" aria-live="polite" aria-atomic="true"><span id="visibleCount" class="font-black text-slate-700 dark:text-white">{{ $students->count() }}</span> siswa tampil</p>
                    </div>
                    <div class="relative flex flex-wrap gap-2">
                        <a href="{{ route('admin.user.export.csv') }}" class="dashboard-action-button inline-flex items-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-2.5 text-[10px] font-black uppercase tracking-widest text-emerald-700 hover:bg-emerald-100 dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-300">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.3" d="M12 3v12m0 0l-4-4m4 4l4-4M5 21h14"/></svg>
                            CSV
                        </a>
                        <a href="{{ route('admin.user.export.pdf') }}" target="_blank" class="dashboard-action-button inline-flex items-center gap-2 rounded-xl border border-rose-200 bg-rose-50 px-4 py-2.5 text-[10px] font-black uppercase tracking-widest text-rose-700 hover:bg-rose-100 dark:border-rose-500/20 dark:bg-rose-500/10 dark:text-rose-300">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.3" d="M6 2h9l3 3v17H6V2zm9 0v4h4"/></svg>
                            PDF
                        </a>
                    </div>
                </div>

                <div data-native-scroll class="directory-data-scroll hidden overflow-auto custom-scrollbar md:block">
                    <table class="directory-table w-full min-w-[1260px] text-left">
                        <thead class="border-b border-slate-200 text-[10px] font-black uppercase tracking-widest text-slate-400 dark:border-white/5 dark:text-white/35">
                            <tr>
                                <th class="w-[27%] px-7 py-4">Siswa</th>
                                <th class="w-[15%] px-6 py-4">Kelas</th>
                                <th class="w-[30%] px-6 py-4">Analitik Belajar</th>
                                <th class="w-[12%] px-6 py-4">Kinerja</th>
                                <th class="w-[12%] px-6 py-4">Aktivitas</th>
                                <th class="w-[4%] px-7 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                            @forelse($students as $student)
                                @php
                                    $joined = $student['joined_at'] ? \Carbon\Carbon::parse($student['joined_at']) : null;
                                    $lastActivity = $student['last_activity_at'];
                                    $progress = min(100, max(0, (int) ($student['progress_pct'] ?? 0)));
                                    $coverage = min(100, max(0, (int) ($student['learning_coverage_pct'] ?? $progress)));
                                    $quizAttempts = (int) ($student['quiz_attempts'] ?? 0);
                                    $quizPassed = (int) ($student['quiz_passed_attempts'] ?? 0);
                                    $labAttempts = (int) ($student['lab_attempts'] ?? 0);
                                    $labsDone = (int) ($student['labs_done'] ?? 0);
                                    $quizPassRate = $quizAttempts > 0 ? min(100, (int) round(($quizPassed / $quizAttempts) * 100)) : 0;
                                    $labCompletionRate = $labAttempts > 0 ? min(100, (int) round(($labsDone / $labAttempts) * 100)) : 0;
                                    $activityLabel = $lastActivity ? $lastActivity->diffForHumans() : 'Belum ada aktivitas';
                                    $learningCondition = match (true) {
                                        $progress === 0 && $quizAttempts === 0 && $labAttempts === 0 => ['label' => 'Belum aktif', 'tone' => 'amber'],
                                        ($student['status_key'] ?? '') === 'attention' => ['label' => 'Perlu penguatan', 'tone' => 'rose'],
                                        ($student['status_key'] ?? '') === 'complete' => ['label' => 'Tuntas', 'tone' => 'emerald'],
                                        default => ['label' => 'Aktif belajar', 'tone' => 'indigo'],
                                    };
                                    $insightPayload = [
                                        'name' => $student['name'],
                                        'email' => $student['email'],
                                        'class_group' => $student['class_group'] ?: 'Belum dipetakan ke kelas',
                                        'status' => $learningCondition['label'],
                                        'progress' => $progress,
                                        'avg_score' => $student['avg_score'],
                                        'quiz_attempts' => $quizAttempts,
                                        'quiz_passed_attempts' => $quizPassed,
                                        'quiz_failed_attempts' => $student['quiz_failed_attempts'],
                                        'labs_done' => $labsDone,
                                        'lab_attempts' => $labAttempts,
                                        'lab_failed_attempts' => $student['lab_failed_attempts'],
                                        'lessons_done' => $student['lessons_done'],
                                        'learning_coverage_pct' => $coverage,
                                        'strongest_chapter' => $student['strongest_chapter'],
                                        'weakest_chapter' => $student['weakest_chapter'],
                                        'focus_lost_total' => $student['focus_lost_total'],
                                        'flagged_total' => $student['flagged_total'],
                                        'unanswered_total' => $student['unanswered_total'],
                                        'last_activity' => $activityLabel,
                                        'detail_url' => route('admin.student.detail', $student['id']),
                                        'lab_url' => route('admin.student.analytics', $student['id']),
                                    ];
                                @endphp
                                <tr data-student-item data-student-row
                                    data-name="{{ \Illuminate\Support\Str::lower($student['name'] . ' ' . $student['email'] . ' ' . ($student['class_group'] ?? '')) }}"
                                    data-class="{{ $student['class_group'] ?: '__none' }}"
                                    data-status="{{ $student['status_key'] }}"
                                    class="student-row dashboard-table-row directory-table-row">
                                    <td class="px-7 py-4">
                                        <div class="flex items-center gap-3.5">
                                            <img src="{{ $student['avatar_url'] }}" alt="{{ $student['name'] }}" class="student-avatar h-12 w-12 rounded-2xl border border-white object-cover shadow-sm dark:border-white/10">
                                            <div class="min-w-0">
                                                <button type="button" class="student-insight-trigger block max-w-[240px] truncate text-left text-sm font-black leading-tight text-slate-900 transition hover:text-indigo-600 focus:outline-none focus-visible:rounded-md focus-visible:ring-2 focus-visible:ring-indigo-400 dark:text-white dark:hover:text-indigo-300" data-student='{!! json_encode($insightPayload, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_HEX_TAG) !!}' aria-label="Buka insight ringkas {{ $student['name'] }}">{{ $student['name'] }}</button>
                                                <p class="mt-1 max-w-[240px] truncate text-[11px] font-semibold text-slate-500 dark:text-white/40">{{ $student['email'] }}</p>
                                                @if($joined)
                                                    <p class="student-profile-meta">Bergabung {{ $joined->translatedFormat('d M Y') }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($student['class_group'])
                                            <span class="la-class-label" title="{{ $student['class_group'] }}">
                                                <svg class="h-3.5 w-3.5 shrink-0 text-indigo-500 dark:text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M6 3v4m12-4v4M5 11h14v8H5z"/></svg>
                                                <span class="truncate">{{ $student['class_group'] }}</span>
                                            </span>
                                        @else
                                            <span class="la-class-empty">
                                                <svg class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v4m0 4h.01M5.1 19h13.8a2 2 0 001.74-3l-6.9-12a2 2 0 00-3.48 0l-6.9 12a2 2 0 001.74 3z"/></svg>
                                                Belum dipetakan
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="la-summary" aria-label="Analitik belajar {{ $student['name'] }}">
                                            <div class="la-progress-ring" style="--progress: {{ $progress }}%;" title="Progres keseluruhan {{ $progress }}%"><span>{{ $progress }}%</span></div>
                                            <div class="la-signal-list">
                                                <div class="la-signal-row">
                                                    <div class="la-signal-head"><span>Materi</span><b>{{ $coverage }}%</b></div>
                                                    <div class="la-signal-track"><i class="la-signal-fill la-signal-fill--materi" style="width: {{ $coverage }}%"></i></div>
                                                </div>
                                                <div class="la-signal-row">
                                                    <div class="la-signal-head"><span>Kuis lulus</span><b>{{ $quizPassRate }}%</b></div>
                                                    <div class="la-signal-track"><i class="la-signal-fill la-signal-fill--kuis" style="width: {{ $quizPassRate }}%"></i></div>
                                                </div>
                                                <div class="la-signal-row">
                                                    <div class="la-signal-head"><span>Lab selesai</span><b>{{ $labCompletionRate }}%</b></div>
                                                    <div class="la-signal-track"><i class="la-signal-fill la-signal-fill--lab" style="width: {{ $labCompletionRate }}%"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="la-performance-score"><strong>{{ $student['avg_score'] }}</strong><span>/100</span></div>
                                        <p class="la-performance-note">{{ $quizAttempts > 0 ? $quizAttempts . ' percobaan' : 'Belum ada kuis' }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="la-activity">
                                            <span class="la-learning-condition la-condition--{{ $learningCondition['tone'] }}">{{ $learningCondition['label'] }}</span>
                                            <p class="la-activity-time">{{ $activityLabel }}</p>
                                        </div>
                                    </td>
                                    <td class="px-7 py-4 text-right">
                                        <div class="inline-flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.student.detail', $student['id']) }}" class="inline-flex items-center gap-1.5 rounded-xl border border-indigo-100 bg-indigo-50 px-3 py-2 text-[9px] font-black uppercase tracking-widest text-indigo-700 transition hover:border-indigo-200 hover:bg-indigo-100 dark:border-indigo-500/20 dark:bg-indigo-500/10 dark:text-indigo-300 dark:hover:bg-indigo-500/20" aria-label="Buka detail {{ $student['name'] }}">
                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6z"/></svg>
                                                Detail
                                            </a>
                                            <form action="{{ route('admin.users.delete', $student['id']) }}" method="POST" class="delete-directory-form inline-flex" data-user-name="{{ $student['name'] }}" data-user-email="{{ $student['email'] }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rounded-xl border border-rose-200 bg-white p-2.5 text-rose-600 transition hover:border-rose-300 hover:bg-rose-50 dark:border-rose-500/20 dark:bg-white/5 dark:text-rose-300 dark:hover:bg-rose-500/10" aria-label="Hapus {{ $student['name'] }}" title="Hapus siswa">
                                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 7h12m-9 4v6m3-6v6m3-6v6M9 7V4h6v3m-9 0l1 14h10l1-14"/></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-16 text-center text-xs font-semibold text-slate-500 dark:text-white/40">Belum ada data siswa.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="grid grid-cols-1 gap-3 p-4 md:hidden">
                    @foreach($students as $student)
                        @php
                            $lastActivity = $student['last_activity_at'];
                            $progress = min(100, max(0, (int) ($student['progress_pct'] ?? 0)));
                            $coverage = min(100, max(0, (int) ($student['learning_coverage_pct'] ?? $progress)));
                            $quizAttempts = (int) ($student['quiz_attempts'] ?? 0);
                            $quizPassed = (int) ($student['quiz_passed_attempts'] ?? 0);
                            $labAttempts = (int) ($student['lab_attempts'] ?? 0);
                            $labsDone = (int) ($student['labs_done'] ?? 0);
                            $quizPassRate = $quizAttempts > 0 ? min(100, (int) round(($quizPassed / $quizAttempts) * 100)) : 0;
                            $labCompletionRate = $labAttempts > 0 ? min(100, (int) round(($labsDone / $labAttempts) * 100)) : 0;
                            $activityLabel = $lastActivity ? $lastActivity->diffForHumans() : 'Belum ada aktivitas';
                            $learningCondition = match (true) {
                                $progress === 0 && $quizAttempts === 0 && $labAttempts === 0 => ['label' => 'Belum aktif', 'tone' => 'amber'],
                                ($student['status_key'] ?? '') === 'attention' => ['label' => 'Perlu penguatan', 'tone' => 'rose'],
                                ($student['status_key'] ?? '') === 'complete' => ['label' => 'Tuntas', 'tone' => 'emerald'],
                                default => ['label' => 'Aktif belajar', 'tone' => 'indigo'],
                            };
                            $insightPayload = [
                                'name' => $student['name'],
                                'email' => $student['email'],
                                'class_group' => $student['class_group'] ?: 'Belum dipetakan ke kelas',
                                'status' => $learningCondition['label'],
                                'progress' => $progress,
                                'avg_score' => $student['avg_score'],
                                'quiz_attempts' => $quizAttempts,
                                'quiz_passed_attempts' => $quizPassed,
                                'quiz_failed_attempts' => $student['quiz_failed_attempts'],
                                'labs_done' => $labsDone,
                                'lab_attempts' => $labAttempts,
                                'lab_failed_attempts' => $student['lab_failed_attempts'],
                                'lessons_done' => $student['lessons_done'],
                                'learning_coverage_pct' => $coverage,
                                'strongest_chapter' => $student['strongest_chapter'],
                                'weakest_chapter' => $student['weakest_chapter'],
                                'focus_lost_total' => $student['focus_lost_total'],
                                'flagged_total' => $student['flagged_total'],
                                'unanswered_total' => $student['unanswered_total'],
                                'last_activity' => $activityLabel,
                                'detail_url' => route('admin.student.detail', $student['id']),
                                'lab_url' => route('admin.student.analytics', $student['id']),
                            ];
                        @endphp
                        <article data-student-item
                                 data-name="{{ \Illuminate\Support\Str::lower($student['name'] . ' ' . $student['email'] . ' ' . ($student['class_group'] ?? '')) }}"
                                 data-class="{{ $student['class_group'] ?: '__none' }}"
                                 data-status="{{ $student['status_key'] }}"
                                 class="directory-mobile-card directory-mobile-student-card rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-white/10 dark:bg-[#0a0e17]">
                            <div class="flex items-start gap-3.5">
                                <img src="{{ $student['avatar_url'] }}" alt="{{ $student['name'] }}" class="student-avatar h-12 w-12 rounded-2xl border border-white object-cover shadow-sm dark:border-white/10">
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="min-w-0">
                                            <button type="button" class="student-insight-trigger block max-w-full truncate text-left text-sm font-black text-slate-900 transition hover:text-indigo-600 focus:outline-none focus-visible:rounded-md focus-visible:ring-2 focus-visible:ring-indigo-400 dark:text-white dark:hover:text-indigo-300" data-student='{!! json_encode($insightPayload, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_HEX_TAG) !!}' aria-label="Buka insight ringkas {{ $student['name'] }}">{{ $student['name'] }}</button>
                                            <p class="truncate text-[11px] font-semibold text-slate-500 dark:text-white/40">{{ $student['email'] }}</p>
                                        </div>
                                        <span class="la-learning-condition la-condition--{{ $learningCondition['tone'] }} shrink-0">{{ $learningCondition['label'] }}</span>
                                    </div>
                                    @if($student['class_group'])
                                        <span class="la-class-label mt-2 max-w-full"><svg class="h-3.5 w-3.5 shrink-0 text-indigo-500 dark:text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M6 3v4m12-4v4M5 11h14v8H5z"/></svg><span class="truncate">{{ $student['class_group'] }}</span></span>
                                    @else
                                        <span class="la-class-empty mt-2"><svg class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v4m0 4h.01M5.1 19h13.8a2 2 0 001.74-3l-6.9-12a2 2 0 00-3.48 0l-6.9 12a2 2 0 001.74 3z"/></svg>Belum dipetakan</span>
                                    @endif
                                </div>
                            </div>

                            <div class="mobile-la-panel mt-4" aria-label="Analitik belajar {{ $student['name'] }}">
                                <div class="la-progress-ring" style="--progress: {{ $progress }}%;"><span>{{ $progress }}%</span></div>
                                <div class="la-signal-list">
                                    <div class="la-signal-row"><div class="la-signal-head"><span>Materi</span><b>{{ $coverage }}%</b></div><div class="la-signal-track"><i class="la-signal-fill la-signal-fill--materi" style="width: {{ $coverage }}%"></i></div></div>
                                    <div class="la-signal-row"><div class="la-signal-head"><span>Kuis lulus</span><b>{{ $quizPassRate }}%</b></div><div class="la-signal-track"><i class="la-signal-fill la-signal-fill--kuis" style="width: {{ $quizPassRate }}%"></i></div></div>
                                    <div class="la-signal-row"><div class="la-signal-head"><span>Lab selesai</span><b>{{ $labCompletionRate }}%</b></div><div class="la-signal-track"><i class="la-signal-fill la-signal-fill--lab" style="width: {{ $labCompletionRate }}%"></i></div></div>
                                </div>
                            </div>

                            <div class="mobile-la-meta">
                                <div>
                                    <p class="text-[9px] font-black uppercase tracking-wider text-slate-400">Nilai rata-rata</p>
                                    <div class="la-performance-score mt-1"><strong>{{ $student['avg_score'] }}</strong><span>/100</span></div>
                                    <p class="la-performance-note">{{ $quizAttempts > 0 ? $quizAttempts . ' percobaan' : 'Belum ada kuis' }}</p>
                                </div>
                                <p class="max-w-[130px] text-right text-[10px] font-semibold leading-4 text-slate-400 dark:text-white/35">{{ $activityLabel }}</p>
                            </div>

                            <div class="mt-4 flex">
                                <a href="{{ route('admin.student.detail', $student['id']) }}" class="directory-mobile-detail inline-flex items-center gap-1.5 rounded-xl border px-3 py-2.5 text-center text-[9px] font-black uppercase tracking-widest transition" aria-label="Buka detail {{ $student['name'] }}">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6z"/></svg>
                                    Lihat Detail
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div id="emptyState" class="hidden px-6 py-16 text-center">
                    <p class="text-sm font-black text-slate-700 dark:text-white">Data tidak ditemukan</p>
                    <p class="mt-1 text-xs font-semibold text-slate-500 dark:text-white/40">Coba sederhanakan kata kunci atau reset filter kelas/status.</p>
                </div>
            </div>
            </div>
        </section>
    </main>

    {{-- Portal tooltip global: berada langsung di bawah <body> agar tidak pernah tertutup atau terpotong area scroll. --}}
    <div id="directoryTooltipPortal" role="tooltip" aria-hidden="true"></div>

    {{-- Modal Panduan Header / Insight Hero --}}
    <div x-show="showDirectoryGuide" x-cloak class="fixed inset-0 z-[2147483500] flex items-center justify-center p-4 sm:p-6" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-md dark:bg-[#020617]/80" @click="showDirectoryGuide = false"></div>
        <section class="relative max-h-[92vh] w-full max-w-6xl overflow-y-auto rounded-[2rem] border border-slate-200 bg-white/95 p-6 shadow-2xl custom-scrollbar dark:border-white/10 dark:bg-[#0f141e]/95 sm:p-8" @click.stop x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-6 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 scale-100" x-transition:leave-end="opacity-0 translate-y-4 scale-95">
            <button type="button" @click="showDirectoryGuide = false" class="absolute right-5 top-5 z-10 rounded-full p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-white/5 dark:hover:text-white" aria-label="Tutup panduan">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            @php
                $guideTitle = 'Panduan Direktori Siswa';
                $guideSubtitle = 'Mencari dan membaca insight';
                $guideImage = 'images/guides/current-admin-students.png';
                $guideIntro = 'Gunakan nomor pada gambar untuk membaca posisi pencarian, tabel siswa, dan tombol aksi yang biasa dipakai saat meninjau data siswa.';
                $guidePoints = [
                    ['x' => 72, 'y' => 21, 'title' => 'Cari dan saring', 'description' => 'Gunakan kolom pencarian, filter kelas, atau status agar daftar siswa langsung lebih mudah dibaca.'],
                    ['x' => 58, 'y' => 58, 'title' => 'Tabel siswa', 'description' => 'Periksa nama, email, kelas, progres, dan status sebelum membuka detail siswa.'],
                    ['x' => 85, 'y' => 58, 'title' => 'Aksi siswa', 'description' => 'Buka insight atau detail siswa dari tombol aksi, lalu gunakan hapus hanya bila akun memang tidak dipakai.'],
                ];
            @endphp
            @include('admin.partials.analytics_guide_mockup')

            <div class="mt-8 border-t border-slate-200 pt-6 dark:border-white/5">
                <button type="button" @click="showDirectoryGuide = false" class="w-full rounded-xl bg-slate-900 py-3 text-sm font-bold text-white shadow-md transition hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-slate-200">Mengerti, Tutup Panduan</button>
            </div>
        </section>
    </div>

    <div id="studentInsightModal" class="fixed inset-0 z-[2147483400] hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/80 backdrop-blur-md dark:bg-[#020617]/90" data-close-insight></div>
        <div id="studentInsightContent" class="relative max-h-[90vh] w-full max-w-3xl scale-95 overflow-hidden rounded-3xl border border-slate-200 bg-white opacity-0 shadow-2xl transition-all duration-300 dark:border-white/10 dark:bg-[#0f141e]">
            <div class="relative overflow-hidden bg-gradient-to-r from-indigo-600 to-cyan-500 px-6 py-7 text-white">
                <div class="absolute right-0 top-0 h-40 w-40 rounded-full bg-white/10 blur-[50px]"></div>
                <button type="button" data-close-insight class="absolute right-5 top-5 rounded-full bg-white/10 p-2 text-white/80 transition hover:bg-white/20 hover:text-white">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                <p class="text-[10px] font-black uppercase tracking-[0.22em] text-indigo-100">Insight Siswa</p>
                <h3 id="insightName" class="mt-2 pr-12 text-2xl font-black">Nama siswa</h3>
                <p id="insightMeta" class="mt-1 text-xs font-semibold text-indigo-100">Email dan kelas</p>
            </div>
            <div class="space-y-5 p-6">
                <div class="grid grid-cols-2 gap-3 lg:grid-cols-3">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-white/10 dark:bg-[#020617]"><p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Progres</p><p id="insightProgress" class="mt-2 text-2xl font-black text-indigo-600 dark:text-indigo-300">0%</p></div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-white/10 dark:bg-[#020617]"><p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Rata-rata</p><p id="insightAverage" class="mt-2 text-2xl font-black text-emerald-600 dark:text-emerald-300">0</p></div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-white/10 dark:bg-[#020617]"><p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Cakupan</p><p id="insightCoverage" class="mt-2 text-2xl font-black text-cyan-600 dark:text-cyan-300">0%</p></div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-white/10 dark:bg-[#020617]"><p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Kuis</p><p id="insightQuizBreakdown" class="mt-2 text-sm font-black text-slate-900 dark:text-white">0 lulus / 0 ulang</p></div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-white/10 dark:bg-[#020617]"><p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Lab</p><p id="insightLabBreakdown" class="mt-2 text-sm font-black text-slate-900 dark:text-white">0 lulus / 0 ulang</p></div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-white/10 dark:bg-[#020617]"><p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Perlu Dicek</p><p id="insightLearningFlags" class="mt-2 text-sm font-black text-slate-900 dark:text-white">0 data</p></div>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-4 dark:border-white/10 dark:bg-[#020617]">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Status Pembelajaran</p>
                            <p id="insightStatus" class="mt-1 text-sm font-black text-slate-900 dark:text-white">Status</p>
                        </div>
                        <span id="insightLastActivity" class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-[10px] font-bold text-slate-500 dark:border-white/10 dark:bg-white/5 dark:text-white/50">Terakhir aktif</span>
                    </div>
                    <div class="mt-4 h-2 overflow-hidden rounded-full bg-slate-100 dark:bg-white/5">
                        <div id="insightProgressBar" class="h-full rounded-full bg-indigo-500 transition-all" style="width: 0%"></div>
                    </div>
                </div>
                <div class="grid gap-3 md:grid-cols-2">
                    <div class="rounded-2xl border border-emerald-200 bg-emerald-50/70 p-4 dark:border-emerald-500/20 dark:bg-emerald-500/10">
                        <p class="text-[10px] font-black uppercase tracking-widest text-emerald-700 dark:text-emerald-300">Bab Terkuat</p>
                        <p id="insightStrongest" class="mt-1 text-sm font-black text-slate-900 dark:text-white">Belum ada data</p>
                    </div>
                    <div class="rounded-2xl border border-amber-200 bg-amber-50/70 p-4 dark:border-amber-500/20 dark:bg-amber-500/10">
                        <p class="text-[10px] font-black uppercase tracking-widest text-amber-700 dark:text-amber-300">Perlu Diperkuat</p>
                        <p id="insightWeakest" class="mt-1 text-sm font-black text-slate-900 dark:text-white">Belum ada data</p>
                    </div>
                </div>
                <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
                    <a id="insightLabLink" href="#" class="rounded-xl border border-cyan-200 bg-cyan-50 px-5 py-3 text-center text-xs font-black uppercase tracking-widest text-cyan-700 transition hover:bg-cyan-100 dark:border-cyan-500/20 dark:bg-cyan-500/10 dark:text-cyan-300">Analitik Lab</a>
                    <a id="insightDetailLink" href="#" class="rounded-xl bg-slate-900 px-5 py-3 text-center text-xs font-black uppercase tracking-widest text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-slate-200">Buka Detail</a>
                </div>
            </div>
        </div>
    </div>

    <div x-show="showImport" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-slate-900/80 backdrop-blur-md dark:bg-[#020617]/90" @click="showImport = false"></div>
        <div class="relative w-full max-w-md rounded-2xl border border-slate-200 bg-white p-6 shadow-xl transition-colors dark:border-white/10 dark:bg-[#0f141e]" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
            <h3 class="text-lg font-black text-slate-900 dark:text-white">Impor Data Siswa</h3>
            <p class="mt-2 border-b border-slate-200 pb-4 text-[11px] font-semibold leading-5 text-slate-500 dark:border-white/5 dark:text-white/45">Header CSV yang diperlukan: <code class="rounded bg-slate-100 px-1.5 py-0.5 font-mono text-indigo-600 dark:bg-white/5 dark:text-indigo-300">Nama, Email, Kelas, Institusi, Kata Sandi</code></p>
            <form action="{{ route('admin.user.import') }}" method="POST" enctype="multipart/form-data" class="mt-5">
                @csrf
                <label class="relative flex h-32 cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed border-slate-300 bg-slate-50 transition hover:border-indigo-400 dark:border-white/10 dark:bg-[#020617]">
                    <input id="csvFileInput" type="file" name="file" class="absolute inset-0 h-full w-full cursor-pointer opacity-0" required>
                    <svg class="mb-2 h-8 w-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                    <span id="csvFileName" class="text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-white/45">Pilih file CSV</span>
                </label>
                <div class="mt-5 flex justify-end gap-3">
                    <button type="button" @click="showImport = false" class="rounded-xl bg-slate-100 px-5 py-2.5 text-xs font-bold text-slate-600 transition hover:bg-slate-200 dark:bg-white/5 dark:text-white/60 dark:hover:bg-white/10">Batal</button>
                    <button type="submit" class="rounded-xl bg-indigo-600 px-6 py-2.5 text-xs font-bold text-white transition hover:bg-indigo-500">Jalankan Impor</button>
                </div>
            </form>
        </div>
    </div>

    <div x-show="showAdd" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-slate-900/80 backdrop-blur-md dark:bg-[#020617]/90" @click="showAdd = false"></div>
        <div class="relative w-full max-w-lg rounded-2xl border border-slate-200 bg-white p-6 shadow-xl transition-colors dark:border-white/10 dark:bg-[#0f141e]" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
            <h3 class="border-b border-slate-200 pb-3 text-lg font-black text-slate-900 dark:border-white/5 dark:text-white">Daftarkan Siswa Baru</h3>
            <form action="{{ route('admin.user.store') }}" method="POST" class="mt-5 space-y-4">
                @csrf
                <div><label class="mb-1.5 block text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-white/45">Nama Lengkap</label><input type="text" name="name" class="glass-input w-full rounded-xl px-4 py-3 text-sm font-semibold" required></div>
                <div><label class="mb-1.5 block text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-white/45">Alamat Email</label><input type="email" name="email" class="glass-input w-full rounded-xl px-4 py-3 text-sm font-semibold" required></div>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-white/45">Grup Kelas</label>
                        <select name="class_group" class="glass-input w-full rounded-xl px-4 py-3 text-sm font-semibold">
                            <option value="">Pilih kelas</option>
                            @foreach($availableClasses ?? [] as $cls)
                                <option value="{{ $cls->name }}">{{ $cls->name }}{{ $cls->major ? ' - '.$cls->major : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div><label class="mb-1.5 block text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-white/45">Institusi</label><input type="text" name="institution" class="glass-input w-full rounded-xl px-4 py-3 text-sm font-semibold"></div>
                </div>
                <div><label class="mb-1.5 block text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-white/45">Kata Sandi</label><input type="password" name="password" class="glass-input w-full rounded-xl px-4 py-3 text-sm font-semibold" required></div>
                <div class="flex justify-end gap-3 border-t border-slate-200 pt-5 dark:border-white/5">
                    <button type="button" @click="showAdd = false" class="rounded-xl bg-slate-100 px-5 py-2.5 text-xs font-bold text-slate-600 transition hover:bg-slate-200 dark:bg-white/5 dark:text-white/60 dark:hover:bg-white/10">Batal</button>
                    <button type="submit" class="rounded-xl bg-indigo-600 px-6 py-2.5 text-xs font-bold text-white transition hover:bg-indigo-500">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(function () {
            /* ==========================================================
               TOOLTIP PORTAL GLOBAL
               Konten tooltip dirender ulang pada portal yang menjadi anak
               langsung <body>, sehingga tidak terpotong main/header/tabel.
               ========================================================== */
            const tooltipPortal = document.getElementById('directoryTooltipPortal');
            let activeTooltipContainer = null;
            let tooltipHideTimer = null;

            function hideGlobalTooltip() {
                if (!tooltipPortal) return;
                window.clearTimeout(tooltipHideTimer);
                tooltipPortal.classList.remove('is-visible');
                tooltipPortal.setAttribute('aria-hidden', 'true');
                activeTooltipContainer = null;
            }

            function placeGlobalTooltip(container) {
                if (!tooltipPortal || !container) return;
                const trigger = container.querySelector('.tooltip-trigger') || container;
                const rect = trigger.getBoundingClientRect();
                const viewportPadding = 14;
                const offset = 12;

                tooltipPortal.classList.remove('tooltip-above');
                tooltipPortal.style.left = '0px';
                tooltipPortal.style.top = '0px';

                const portalWidth = tooltipPortal.offsetWidth;
                const portalHeight = tooltipPortal.offsetHeight;
                const wantsAbove = rect.bottom + offset + portalHeight > window.innerHeight - viewportPadding
                    && rect.top - offset - portalHeight >= viewportPadding;
                const top = wantsAbove ? rect.top - portalHeight - offset : rect.bottom + offset;
                const desiredLeft = rect.left + (rect.width / 2) - (portalWidth / 2);
                const left = Math.max(viewportPadding, Math.min(desiredLeft, window.innerWidth - portalWidth - viewportPadding));
                const arrowLeft = Math.max(16, Math.min(rect.left + (rect.width / 2) - left, portalWidth - 16));

                tooltipPortal.classList.toggle('tooltip-above', wantsAbove);
                tooltipPortal.style.left = `${Math.round(left)}px`;
                tooltipPortal.style.top = `${Math.round(top)}px`;
                tooltipPortal.style.setProperty('--tooltip-arrow-left', `${Math.round(arrowLeft)}px`);
            }

            function showGlobalTooltip(container) {
                if (!tooltipPortal || !container) return;
                const content = container.querySelector('.tooltip-content');
                if (!content) return;
                window.clearTimeout(tooltipHideTimer);
                activeTooltipContainer = container;
                tooltipPortal.innerHTML = content.innerHTML;
                tooltipPortal.setAttribute('aria-hidden', 'false');
                placeGlobalTooltip(container);
                requestAnimationFrame(() => tooltipPortal.classList.add('is-visible'));
            }

            document.querySelectorAll('.tooltip-container').forEach((container) => {
                const trigger = container.querySelector('.tooltip-trigger');
                if (trigger) trigger.removeAttribute('title');

                container.addEventListener('mouseenter', () => showGlobalTooltip(container));
                container.addEventListener('mouseleave', () => {
                    tooltipHideTimer = window.setTimeout(hideGlobalTooltip, 60);
                });

                if (trigger) {
                    trigger.addEventListener('focus', () => showGlobalTooltip(container));
                    trigger.addEventListener('blur', () => {
                        tooltipHideTimer = window.setTimeout(hideGlobalTooltip, 60);
                    });
                    trigger.addEventListener('click', hideGlobalTooltip);
                }
            });

            window.addEventListener('resize', () => {
                if (activeTooltipContainer) placeGlobalTooltip(activeTooltipContainer);
            }, { passive: true });
            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') hideGlobalTooltip();
            });

            /* ==========================================================
               SMOOTH WHEEL SCROLL DESKTOP
               Menggunakan interpolasi berbasis waktu agar respons scroll
               tetap halus pada trackpad/mouse tanpa terasa berat atau lambat.
               ========================================================== */
            const scrollHost = document.querySelector('[data-smooth-scroll]');
            const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
            const finePointer = window.matchMedia('(pointer: fine)');
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

            if (scrollHost) {
                scrollHost.addEventListener('wheel', (event) => {
                    if (!canSmoothWheel() || event.ctrlKey || event.shiftKey || !event.deltaY) return;
                    const target = event.target instanceof Element ? event.target : null;
                    if (target && target.closest('input, textarea, select, [contenteditable="true"], [data-native-scroll]')) return;

                    const maxScrollTop = Math.max(0, scrollHost.scrollHeight - scrollHost.clientHeight);
                    if (!maxScrollTop) return;

                    const delta = normaliseWheelDelta(event);
                    const atTop = scrollHost.scrollTop <= 0 && delta < 0;
                    const atBottom = scrollHost.scrollTop >= maxScrollTop - 1 && delta > 0;
                    if (atTop || atBottom) return;

                    event.preventDefault();
                    if (!scrollAnimationFrame) targetScrollTop = scrollHost.scrollTop;
                    const wheelMultiplier = Math.abs(delta) > 120 ? .78 : .64;
                    targetScrollTop = Math.max(0, Math.min(maxScrollTop, targetScrollTop + (delta * wheelMultiplier)));
                    scrollHost.classList.add('is-smooth-scrolling');
                    hideGlobalTooltip();

                    if (!scrollAnimationFrame) {
                        lastScrollFrame = performance.now();
                        scrollAnimationFrame = window.requestAnimationFrame(animateSmoothWheel);
                    }
                }, { passive: false });

                scrollHost.addEventListener('scroll', () => {
                    if (!smoothWheelAnimating) targetScrollTop = scrollHost.scrollTop;
                    hideGlobalTooltip();
                }, { passive: true });

                scrollHost.addEventListener('pointerdown', cancelSmoothWheel, { passive: true });
                scrollHost.addEventListener('touchstart', cancelSmoothWheel, { passive: true });
                scrollHost.addEventListener('keydown', cancelSmoothWheel, { passive: true });
                window.addEventListener('blur', cancelSmoothWheel, { passive: true });
            }

            document.addEventListener('scroll', () => {
                if (activeTooltipContainer) hideGlobalTooltip();
            }, { passive: true, capture: true });

            const $themeBtn = $('#theme-toggle-sidebar');
            const $darkIcon = $('#theme-toggle-dark-icon-sidebar');
            const $lightIcon = $('#theme-toggle-light-icon-sidebar');
            const $themeText = $('#theme-toggle-text-sidebar');

            function syncThemeIcon() {
                const isDark = $('html').hasClass('dark');
                $lightIcon.toggleClass('hidden', !isDark);
                $darkIcon.toggleClass('hidden', isDark);
                $themeText.text(isDark ? 'Tema Terang' : 'Tema Gelap');
            }

            $themeBtn.on('click', function () {
                const willBeDark = !$('html').hasClass('dark');
                $('html').toggleClass('dark', willBeDark);
                localStorage.setItem('color-theme', willBeDark ? 'dark' : 'light');
                syncThemeIcon();
            });
            syncThemeIcon();

            const $items = $('[data-student-item]');
            const $rows = $('[data-student-row]');
            let activeClass = 'all';

            function applyFilters() {
                const query = ($('#studentSearch').val() || '').toLowerCase().trim();
                const status = $('#statusFilter').val() || 'all';
                let visibleRows = 0;

                $items.each(function () {
                    const $item = $(this);
                    const matchesText = !query || ($item.data('name') || '').includes(query);
                    const matchesClass = activeClass === 'all' || ($item.data('class') || '') === activeClass;
                    const matchesStatus = status === 'all' || ($item.data('status') || '') === status;
                    const visible = matchesText && matchesClass && matchesStatus;

                    $item.toggleClass('hidden', !visible);
                    if (visible && $item.is('[data-student-row]')) visibleRows += 1;
                });

                $('#visibleCount').text(visibleRows);
                $('#emptyState').toggleClass('hidden', visibleRows > 0 || $rows.length === 0);
            }

            $('#studentSearch').on('input', applyFilters);
            $('#statusFilter').on('change', applyFilters);
            $('.class-filter').on('click', function () {
                activeClass = $(this).data('class-filter');
                $('.class-filter').removeClass('active').attr('aria-pressed', 'false');
                $(this).addClass('active').attr('aria-pressed', 'true');
                applyFilters();
            });

            $('.student-insight-trigger').on('click', function () {
                const data = JSON.parse($(this).attr('data-student') || '{}');
                const progress = Math.min(Number(data.progress || 0), 100);
                const quizPassed = Number(data.quiz_passed_attempts || 0);
                const quizFailed = Number(data.quiz_failed_attempts || 0);
                const labPassed = Number(data.labs_done || 0);
                const labFailed = Number(data.lab_failed_attempts || 0);
                const learningFlags = Number(data.focus_lost_total || 0) + Number(data.flagged_total || 0) + Number(data.unanswered_total || 0);

                $('#insightName').text(data.name || 'Siswa');
                $('#insightMeta').text((data.email || '-') + ' - ' + (data.class_group || '-'));
                $('#insightProgress').text(progress + '%');
                $('#insightAverage').text(data.avg_score || 0);
                $('#insightCoverage').text((data.learning_coverage_pct || 0) + '%');
                $('#insightQuizBreakdown').text(quizPassed + ' lulus / ' + quizFailed + ' ulang');
                $('#insightLabBreakdown').text(labPassed + ' lulus / ' + labFailed + ' ulang');
                $('#insightLearningFlags').text(learningFlags + ' data');
                $('#insightStrongest').text(data.strongest_chapter || 'Belum ada data');
                $('#insightWeakest').text(data.weakest_chapter || 'Belum ada data');
                $('#insightStatus').text(data.status || 'Belum ada status');
                $('#insightLastActivity').text('Aktivitas terakhir: ' + (data.last_activity || '-'));
                $('#insightProgressBar').css('width', progress + '%');
                $('#insightDetailLink').attr('href', data.detail_url || '#');
                $('#insightLabLink').attr('href', data.lab_url || '#');

                $('#studentInsightModal').removeClass('hidden').addClass('flex');
                setTimeout(() => $('#studentInsightContent').removeClass('scale-95 opacity-0').addClass('scale-100 opacity-100'), 10);
                $('body').addClass('modal-open');
            });

            $('[data-close-insight]').on('click', function () {
                $('#studentInsightContent').removeClass('scale-100 opacity-100').addClass('scale-95 opacity-0');
                setTimeout(() => {
                    $('#studentInsightModal').addClass('hidden').removeClass('flex');
                    $('body').removeClass('modal-open');
                }, 220);
            });

            $('#csvFileInput').on('change', function () {
                $('#csvFileName').text(this.files && this.files[0] ? this.files[0].name : 'Pilih file CSV');
            });

            $('#refreshDirectory').on('click', function () {
                window.location.reload();
            });

            function swalTheme() {
                const isDark = $('html').hasClass('dark');
                return {
                    background: isDark ? '#0f141e' : '#ffffff',
                    color: isDark ? '#fff' : '#1e293b'
                };
            }

            @if(session('success'))
                Swal.fire({ title: 'Berhasil', text: "{{ session('success') }}", icon: 'success', confirmButtonColor: '#6366f1', ...swalTheme() });
            @endif
            @if(session('error'))
                Swal.fire({ title: 'Gagal', text: "{{ session('error') }}", icon: 'error', confirmButtonColor: '#ef4444', ...swalTheme() });
            @endif

            $('.delete-directory-form').on('submit', function (event) {
                event.preventDefault();
                const form = this;
                const userName = $(form).data('user-name') || 'siswa ini';
                const userEmail = $(form).data('user-email') || '';

                Swal.fire({
                    title: 'Hapus siswa?',
                    html: `<div class="text-sm leading-6">Akun <b>${userName}</b><br><span class="text-xs opacity-70">${userEmail}</span><br><br>Data akun akan dihapus dari direktori.</div>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    ...swalTheme()
                }).then(result => {
                    if (result.isConfirmed) form.submit();
                });
            });

            applyFilters();
        });
    </script>
</body>
</html>
