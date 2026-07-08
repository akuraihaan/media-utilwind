<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Analitik Praktik Lab</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    {{-- RESOURCES --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

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

    {{-- SCRIPT PENGECEKAN TEMA OTOMATIS (Mencegah FOUC) --}}
    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <style>
        /* --- THEME CONFIG (DYNAMIC GLASSMORPHISM) --- */
        :root { 
            --bg-main: #f8fafc;
            --text-main: #0f172a;
            --glass-bg: rgba(255, 255, 255, 0.85); 
            --glass-border: rgba(0, 0, 0, 0.05); 
            --glass-sidebar: rgba(255, 255, 255, 0.95);
            --glass-header: rgba(255, 255, 255, 0.85);
            --input-bg: rgba(0, 0, 0, 0.03);
            --input-border: rgba(0, 0, 0, 0.1);
            --nav-text: #64748b;
            --nav-hover-bg: rgba(0, 0, 0, 0.03);
            --table-hover: rgba(0, 0, 0, 0.02);
            --tooltip-bg: #ffffff;
            --tooltip-text: #1e293b;
            --chart-grid: rgba(0, 0, 0, 0.05);
            --chart-tick: rgba(15, 23, 42, 0.5);
            --accent: #6366f1; 
        }

        .dark {
            /* ORIGINAL DARK THEME VALUES - 100% MATCH */
            --bg-main: #020617;
            --text-main: #e2e8f0;
            --glass-bg: rgba(10, 14, 23, 0.85); 
            --glass-border: rgba(255, 255, 255, 0.08); 
            --glass-sidebar: rgba(5, 8, 16, 0.95);
            --glass-header: rgba(2, 6, 23, 0.85);
            --input-bg: rgba(255, 255, 255, 0.03);
            --input-border: rgba(255, 255, 255, 0.1);
            --nav-text: #94a3b8;
            --nav-hover-bg: rgba(255, 255, 255, 0.03);
            --table-hover: rgba(255, 255, 255, 0.05);
            --tooltip-bg: #020617;
            --tooltip-text: #e2e8f0;
            --chart-grid: rgba(255, 255, 255, 0.05);
            --chart-tick: rgba(255, 255, 255, 0.4);
        }

        body { font-family: 'Inter', sans-serif; background-color: var(--bg-main); color: var(--text-main); overflow-x: hidden; transition: background-color 0.3s, color 0.3s; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }

        /* --- SCROLLBAR --- */
        .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(150,150,150,0.3); border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: var(--accent); }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }

        /* --- GLASS COMPONENTS --- */
        .glass-sidebar { background: var(--glass-sidebar); backdrop-filter: blur(20px); border-right: 1px solid var(--glass-border); z-index: 50; transition: background 0.3s, border 0.3s; }
        .glass-header { background: var(--glass-header); backdrop-filter: blur(12px); border-bottom: 1px solid var(--glass-border); z-index: 40; transition: background 0.3s, border 0.3s; }
        
        .glass-card {
            background: var(--glass-bg); border: 1px solid var(--glass-border);
            backdrop-filter: blur(10px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative; overflow: visible !important; z-index: 10;
        }
        /* Penyesuaian Shadow agar Light mode lebih soft, dan Dark mode tetap pekat seperti asal */
        .glass-card { box-shadow: 0 4px 30px rgba(0, 0, 0, 0.03); }
        .dark .glass-card { box-shadow: 0 4px 30px rgba(0, 0, 0, 0.2); }
        
        .glass-card:hover { border-color: rgba(99, 102, 241, 0.4); transform: translateY(-3px); z-index: 30; }
        .glass-card:hover { box-shadow: 0 10px 40px -10px rgba(0,0,0,0.1); }
        .dark .glass-card:hover { box-shadow: 0 10px 40px -10px rgba(0,0,0,0.5); }
        
        /* --- INPUTS & NAV --- */
        .glass-input { background: var(--input-bg); border: 1px solid var(--input-border); color: var(--text-main); transition: 0.3s; }
        .glass-input:focus { border-color: var(--accent); outline: none; box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2); }
        
        .nav-link { display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 12px; color: var(--nav-text); font-weight: 500; font-size: 0.875rem; transition: all 0.2s; border: 1px solid transparent; }
        .nav-link:hover { background: var(--nav-hover-bg); color: var(--text-main); }
        .nav-link.active { background: linear-gradient(90deg, rgba(99, 102, 241, 0.1) 0%, rgba(99, 102, 241, 0) 100%); color: #818cf8; border-left: 3px solid #818cf8; border-radius: 4px 12px 12px 4px; }
        html:not(.dark) .nav-link.active { color: #6366f1; border-left-color: #6366f1; }

        .reveal { opacity: 0; transform: translateY(15px); animation: revealAnim 0.5s forwards ease-out; }
        @keyframes revealAnim { to { opacity: 1; transform: translateY(0); } }
        .table-row { transition: background 0.2s; border-bottom: 1px solid var(--glass-border); }
        .table-row:hover { background: var(--table-hover); }

        /* ==================== TOOLTIP SYSTEM ==================== */
        .tooltip-container { position: relative; display: inline-flex; align-items: center; justify-content: center; z-index: 50; }
        .tooltip-container:hover { z-index: 99999; }
        .tooltip-trigger { 
            width: 18px; height: 18px; border-radius: 50%; color: inherit; 
            font-size: 11px; font-weight: 900; display: flex; align-items: center; justify-content: center; 
            cursor: help; transition: all 0.2s; border: 1px solid currentColor; opacity: 0.5;
        }
        .tooltip-trigger:hover { transform: scale(1.15); opacity: 1; }
        .tooltip-content { 
            opacity: 0; visibility: hidden; position: absolute; pointer-events: none; 
            width: max-content; min-width: 220px; max-width: 280px; white-space: normal; text-align: left; 
            background-color: var(--tooltip-bg); 
            color: var(--tooltip-text); font-size: 11px; padding: 14px 16px; line-height: 1.5;
            border-radius: 12px; box-shadow: 0 20px 60px rgba(0,0,0,0.15); z-index: 99999;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); border: 1px solid var(--glass-border);
        }
        .dark .tooltip-content { box-shadow: 0 20px 60px rgba(0,0,0,1); }

        .tooltip-down .tooltip-content { top: calc(100% + 12px); left: 50%; transform: translateX(-50%) translateY(-10px); }
        .tooltip-down:hover .tooltip-content { transform: translateX(-50%) translateY(0); opacity: 1; visibility: visible; }
        .tooltip-down .tooltip-content::after { content: ''; position: absolute; bottom: 100%; left: 50%; margin-left: -6px; border-width: 6px; border-style: solid; border-color: transparent transparent var(--tooltip-bg) transparent; }
        
        .tooltip-left .tooltip-content { left: auto; right: -12px; transform: translateX(0) translateY(-10px); }
        .tooltip-down.tooltip-left:hover .tooltip-content { transform: translateX(0) translateY(0); }
        .tooltip-left .tooltip-content::after { left: auto; right: 15px; margin-left: 0; }

        .tooltip-indigo .tooltip-trigger { background-color: #6366f1; box-shadow: 0 0 10px rgba(99,102,241,0.5); color: white; border:none; opacity: 1;}
        .tooltip-indigo .tooltip-trigger:hover { background-color: #818cf8; box-shadow: 0 0 15px rgba(99,102,241,0.8); }
        .tooltip-indigo .tooltip-content { border: 1px solid rgba(99,102,241,0.5); }

        .tooltip-emerald .tooltip-trigger { background-color: #10b981; box-shadow: 0 0 10px rgba(16,185,129,0.5); color: white; border:none; opacity: 1;}
        .tooltip-emerald .tooltip-trigger:hover { background-color: #34d399; box-shadow: 0 0 15px rgba(16,185,129,0.8); }
        .tooltip-emerald .tooltip-content { border: 1px solid rgba(16,185,129,0.5); }

        .tooltip-yellow .tooltip-trigger { background-color: #eab308; color: #020617; box-shadow: 0 0 10px rgba(234,179,8,0.5); border:none; opacity: 1;}
        .tooltip-yellow .tooltip-trigger:hover { background-color: #facc15; box-shadow: 0 0 15px rgba(234,179,8,0.8); }
        .tooltip-yellow .tooltip-content { border: 1px solid rgba(234,179,8,0.5); }

        .tooltip-cyan .tooltip-trigger { background-color: #06b6d4; box-shadow: 0 0 10px rgba(6,182,212,0.5); color: white; border:none; opacity: 1;}
        .tooltip-cyan .tooltip-trigger:hover { background-color: #22d3ee; box-shadow: 0 0 15px rgba(6,182,212,0.8); }
        .tooltip-cyan .tooltip-content { border: 1px solid rgba(6,182,212,0.5); }

        .modal-open { overflow: hidden; padding-right: 5px; }
        [x-cloak] { display: none !important; }
    </style>

    {{-- =========================================================================
         PENYEMPURNAAN VISUAL ANALITIK LAB
         Seluruh data dan mekanisme lama dipertahankan. Blok ini hanya
         menata ulang hierarchy visual, kartu data, bento, dan interaksi gulir.
         ========================================================================= --}}
    <style>
        :root {
            --analytics-surface: rgba(255, 255, 255, .94);
            --analytics-surface-soft: #f8fafc;
            --analytics-border: rgba(148, 163, 184, .28);
            --analytics-border-strong: rgba(99, 102, 241, .28);
            --analytics-muted: #64748b;
            --analytics-shadow: 0 16px 38px rgba(15, 23, 42, .065);
            --analytics-shadow-hover: 0 20px 46px rgba(15, 23, 42, .10);
            --analytics-accent: #4f46e5;
            --analytics-accent-soft: #eef2ff;
        }

        .dark {
            --analytics-surface: rgba(15, 23, 42, .88);
            --analytics-surface-soft: rgba(30, 41, 59, .56);
            --analytics-border: rgba(148, 163, 184, .15);
            --analytics-border-strong: rgba(129, 140, 248, .34);
            --analytics-muted: #94a3b8;
            --analytics-shadow: 0 18px 42px rgba(2, 6, 23, .24);
            --analytics-shadow-hover: 0 24px 52px rgba(2, 6, 23, .34);
            --analytics-accent: #818cf8;
            --analytics-accent-soft: rgba(99, 102, 241, .14);
        }

        html { scroll-behavior: smooth; }

        .smooth-analytics-scroll {
            scroll-behavior: auto;
            scroll-padding-top: 7.5rem;
            overscroll-behavior-y: contain;
            scrollbar-gutter: stable both-edges;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
            scrollbar-color: rgba(99, 102, 241, .38) transparent;
        }

        .smooth-analytics-scroll:focus { outline: none; }

        .smooth-analytics-scroll .glass-card {
            background: var(--analytics-surface);
            border-color: var(--analytics-border);
            box-shadow: var(--analytics-shadow);
            border-radius: 1.35rem;
        }

        .smooth-analytics-scroll .glass-card:hover {
            border-color: var(--analytics-border-strong);
            box-shadow: var(--analytics-shadow-hover);
            transform: translateY(-2px);
        }

        .smooth-analytics-scroll .glass-card,
        .smooth-analytics-scroll .glass-card *,
        .smooth-analytics-scroll .glass-input,
        .smooth-analytics-scroll button,
        .smooth-analytics-scroll a {
            transition-timing-function: cubic-bezier(.22, 1, .36, 1);
        }

        .analytics-content-shell {
            max-width: 84rem;
            margin-inline: auto;
            padding-bottom: 5rem;
        }

        .analytics-kpi-card {
            min-height: 148px;
            overflow: hidden !important;
            border-left-width: 1px !important;
            border-radius: 1.25rem !important;
            padding: 1.25rem !important;
            isolation: isolate;
        }

        .analytics-kpi-card::before {
            content: '';
            position: absolute;
            inset: 0 auto auto 0;
            width: 100%;
            height: 3px;
            background: var(--analytics-accent);
            opacity: .82;
        }

        .analytics-kpi-card.kpi-indigo { --analytics-accent: #6366f1; }
        .analytics-kpi-card.kpi-emerald { --analytics-accent: #059669; }
        .analytics-kpi-card.kpi-amber { --analytics-accent: #d97706; }
        .analytics-kpi-card.kpi-cyan { --analytics-accent: #0891b2; }
        .dark .analytics-kpi-card.kpi-indigo { --analytics-accent: #818cf8; }
        .dark .analytics-kpi-card.kpi-emerald { --analytics-accent: #34d399; }
        .dark .analytics-kpi-card.kpi-amber { --analytics-accent: #fbbf24; }
        .dark .analytics-kpi-card.kpi-cyan { --analytics-accent: #67e8f9; }

        .analytics-kpi-card h3 {
            letter-spacing: -.035em;
            font-variant-numeric: tabular-nums;
        }

        .analytics-kpi-card .tooltip-trigger {
            width: 17px;
            height: 17px;
            color: var(--analytics-muted);
            background: transparent !important;
            box-shadow: none !important;
            border-color: currentColor !important;
            opacity: .72;
        }

        .analytics-kpi-card .tooltip-trigger:hover {
            color: var(--analytics-accent);
            opacity: 1;
            transform: none;
        }

        .analytics-kpi-card .tooltip-content {
            z-index: 2147483000;
            border-color: var(--analytics-border);
            box-shadow: 0 20px 48px rgba(15, 23, 42, .18);
        }

        .analytics-section-heading {
            display: flex;
            align-items: center;
            gap: .65rem;
        }

        .analytics-section-heading::before {
            content: '';
            width: .18rem;
            height: 1rem;
            flex: 0 0 auto;
            border-radius: 999px;
            background: var(--analytics-accent);
        }

        .class-insight-card {
            min-height: 218px;
            padding: 1.2rem !important;
            border-left-width: 1px !important;
        }

        .class-insight-card .grid > div {
            background: var(--analytics-surface-soft) !important;
            border-color: var(--analytics-border) !important;
            border-radius: .9rem;
        }

        .class-insight-card > .mt-4:last-child {
            border-color: var(--analytics-border) !important;
        }

        .analytics-panel > div:first-child {
            background: linear-gradient(115deg, var(--analytics-surface-soft), transparent) !important;
            border-color: var(--analytics-border) !important;
        }

        .analytics-panel h3,
        .analytics-panel h4 {
            letter-spacing: -.02em;
        }

        .analytics-data-row {
            background: var(--analytics-surface-soft) !important;
            border-color: var(--analytics-border) !important;
            border-radius: 1rem !important;
        }

        .analytics-data-row:hover {
            border-color: var(--analytics-border-strong) !important;
            transform: translateY(-1px);
        }

        .chart-surface {
            background: linear-gradient(145deg, var(--analytics-surface-soft), transparent 72%);
        }

        .student-performance-card {
            background: var(--analytics-surface-soft) !important;
            border-color: var(--analytics-border) !important;
            border-radius: 1rem !important;
        }

        .student-performance-card:hover {
            border-color: var(--analytics-border-strong) !important;
            background: var(--analytics-surface) !important;
            transform: translateY(-1px);
        }

        .data-chip {
            border: 1px solid var(--analytics-border);
            background: var(--analytics-surface-soft);
            border-radius: .65rem;
        }

        .page-context-subtitle {
            color: var(--analytics-muted) !important;
        }

        .analytics-compact-control {
            border-color: var(--analytics-border) !important;
            background: var(--analytics-surface-soft) !important;
            box-shadow: none !important;
        }

        .analytics-compact-control:hover {
            border-color: var(--analytics-border-strong) !important;
            background: var(--analytics-surface) !important;
        }

        .analytics-empty-state {
            border-color: var(--analytics-border) !important;
            background: var(--analytics-surface-soft) !important;
        }

        @media (hover: hover) and (pointer: fine) {
            .smooth-analytics-scroll .glass-card:hover { transform: translateY(-2px); }
            .analytics-data-row:hover,
            .student-performance-card:hover { transform: translateY(-1px); }
        }

        @media (hover: none), (pointer: coarse) {
            .smooth-analytics-scroll .glass-card:hover,
            .analytics-data-row:hover,
            .student-performance-card:hover { transform: none; }
        }

        @media (max-width: 767px) {
            .smooth-analytics-scroll { scroll-padding-top: 6rem; }
            .analytics-kpi-card { min-height: 132px; padding: 1rem !important; }
            .class-insight-card { min-height: auto; }
        }

        @media (prefers-reduced-motion: reduce) {
            .smooth-analytics-scroll { scroll-behavior: auto; }
            .smooth-analytics-scroll .glass-card,
            .smooth-analytics-scroll .glass-card *,
            .smooth-analytics-scroll .glass-input,
            .smooth-analytics-scroll button,
            .smooth-analytics-scroll a {
                transition-duration: .01ms !important;
                animation-duration: .01ms !important;
            }
        }

        /* Tampilan ringkas: angka dan status menjadi fokus, narasi diminimalkan. */
        .analytics-focus-bar {
            overflow: hidden;
            border: 1px solid var(--analytics-border);
            border-radius: 1.35rem;
            background: var(--analytics-surface);
            box-shadow: var(--analytics-shadow);
        }
        .analytics-focus-head {
            display: flex;
            align-items: baseline;
            justify-content: space-between;
            gap: 1rem;
            padding: 1rem 1.2rem;
            border-bottom: 1px solid var(--analytics-border);
        }
        .analytics-focus-head p {
            color: var(--analytics-muted);
            font-size: .62rem;
            font-weight: 900;
            letter-spacing: .16em;
            text-transform: uppercase;
        }
        .analytics-focus-head h3 {
            color: var(--text-main);
            font-size: .9rem;
            font-weight: 900;
            letter-spacing: -.015em;
        }
        .analytics-focus-grid { display: grid; gap: 1px; background: var(--analytics-border); }
        .analytics-focus-item {
            min-width: 0;
            padding: 1rem 1.2rem;
            background: var(--analytics-surface);
        }
        .analytics-focus-label {
            color: var(--analytics-muted);
            font-size: .6rem;
            font-weight: 900;
            letter-spacing: .14em;
            text-transform: uppercase;
        }
        .analytics-focus-value {
            margin-top: .35rem;
            color: var(--text-main);
            font-size: 1.65rem;
            font-weight: 900;
            letter-spacing: -.055em;
            line-height: 1;
            font-variant-numeric: tabular-nums;
        }
        .analytics-focus-item h4 {
            margin-top: .45rem;
            overflow: hidden;
            color: var(--text-main);
            font-size: .88rem;
            font-weight: 900;
            line-height: 1.35;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .analytics-focus-item span {
            display: block;
            margin-top: .38rem;
            overflow: hidden;
            color: var(--analytics-muted);
            font-size: .68rem;
            font-weight: 750;
            line-height: 1.35;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .focus-pass .analytics-focus-value { color: #059669; }
        .focus-obstacle h4 { color: #be123c; }
        .focus-action h4 { color: #0f766e; }
        .dark .focus-pass .analytics-focus-value { color: #6ee7b7; }
        .dark .focus-obstacle h4 { color: #fda4af; }
        .dark .focus-action h4 { color: #67e8f9; }
        .analytics-section-helper,
        .analytics-class-caption { display: none !important; }
        @media (min-width: 768px) {
            .analytics-focus-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        }
        @media (max-width: 639px) {
            .analytics-focus-head { align-items: flex-start; flex-direction: column; gap: .3rem; }
        }

    </style>



    {{-- =========================================================================
         TOOLTIP DAN VISUALISASI RINGKAS ANALITIK
         - Tooltip memakai portal global supaya tidak terpotong panel/overflow.
         - Visual status dan interaksi memakai batang komposisi sederhana.
         ========================================================================= --}}
    <style>
        .analytics-overview { display: grid; gap: 1rem; }
        .analytics-overview-head { display: flex; align-items: flex-end; justify-content: space-between; gap: 1rem; }
        .analytics-overview-head p { color: var(--analytics-accent); font-size: .64rem; font-weight: 900; letter-spacing: .18em; text-transform: uppercase; }
        .analytics-overview-head h3 { margin-top: .2rem; color: var(--text-main); font-size: 1.25rem; font-weight: 900; letter-spacing: -.035em; }
        .analytics-overview-grid { display: grid; gap: .75rem; grid-template-columns: repeat(4, minmax(0, 1fr)); }
        .analytics-metric-card { min-height: 132px; overflow: hidden; position: relative; border: 1px solid var(--analytics-border); border-radius: 1rem; background: var(--analytics-surface); padding: 1rem; transition: transform .18s ease, border-color .18s ease, box-shadow .18s ease; }
        .analytics-metric-card::before { content: ''; position: absolute; inset: 0 auto auto 0; width: 100%; height: 3px; background: var(--metric-tone, var(--analytics-accent)); }
        .analytics-metric-card:hover { transform: translateY(-1px); border-color: var(--analytics-border-strong); box-shadow: var(--analytics-shadow); }
        .metric-indigo { --metric-tone: #6366f1; } .metric-emerald { --metric-tone: #10b981; } .metric-cyan { --metric-tone: #06b6d4; } .metric-amber { --metric-tone: #f59e0b; }
        .analytics-metric-title { display: flex; align-items: center; gap: .45rem; color: var(--analytics-muted); font-size: .6rem; font-weight: 900; letter-spacing: .12em; text-transform: uppercase; }
        .analytics-metric-card strong { display: block; margin-top: 1rem; color: var(--text-main); font-size: 1.75rem; font-weight: 900; letter-spacing: -.045em; font-variant-numeric: tabular-nums; }
        .analytics-metric-card small { display: block; margin-top: .35rem; color: var(--analytics-muted); font-size: .68rem; font-weight: 700; line-height: 1.4; }
        .analytics-help { display: inline-flex; align-items: center; justify-content: center; flex: 0 0 auto; width: 1.05rem; height: 1.05rem; border: 1px solid color-mix(in srgb, var(--analytics-muted) 48%, transparent); border-radius: 999px; color: var(--analytics-muted); font-family: 'Inter', sans-serif; font-size: .62rem; font-weight: 900; line-height: 1; cursor: help; user-select: none; transition: border-color .16s ease, color .16s ease, background .16s ease; }
        .analytics-help:hover, .analytics-help:focus { border-color: var(--analytics-accent); outline: none; background: var(--analytics-accent-soft); color: var(--analytics-accent); }
        .analytics-help-inline { width: .95rem; height: .95rem; margin-left: .25rem; vertical-align: text-bottom; font-size: .57rem; }
        #analyticsTooltipPortal { position: fixed; z-index: 2147483647; width: min(300px, calc(100vw - 24px)); border: 1px solid var(--analytics-border-strong); border-radius: .8rem; background: var(--analytics-surface); color: var(--text-main); box-shadow: 0 18px 42px rgba(15, 23, 42, .2); padding: .75rem .85rem; font-size: .72rem; font-weight: 650; line-height: 1.55; opacity: 0; pointer-events: none; transform: translateY(4px); transition: opacity .14s ease, transform .14s ease; }
        .dark #analyticsTooltipPortal { box-shadow: 0 20px 54px rgba(2, 6, 23, .7); }
        #analyticsTooltipPortal.is-visible { opacity: 1; transform: translateY(0); }
        .analytics-focus-grid-visual { grid-template-columns: 1.25fr 1fr 1fr; }
        .focus-status { min-height: 0; }
        .analytics-status-chart { display: flex; overflow: hidden; height: .72rem; margin-top: .9rem; border-radius: 999px; background: var(--analytics-border); }
        .analytics-status-chart > span { display: block; min-width: 0; height: 100%; transition: width .35s cubic-bezier(.22,1,.36,1); }
        .status-pass { background: #10b981; } .status-fail { background: #fb7185; } .status-wait { background: #94a3b8; }
        .analytics-status-legend { display: flex; flex-wrap: wrap; gap: .55rem .8rem; margin-top: .75rem; color: var(--analytics-muted); font-size: .62rem; font-weight: 800; }
        .analytics-status-legend span { display: inline-flex; align-items: center; gap: .3rem; }
        .analytics-status-legend i { display: inline-block; width: .5rem; height: .5rem; border-radius: 999px; }
        .legend-pass { background: #10b981; } .legend-fail { background: #fb7185; } .legend-wait { background: #94a3b8; }
        .analytics-activity-summary { min-height: 100%; border: 1px solid var(--analytics-border); border-radius: 1rem; background: var(--analytics-surface-soft); padding: 1.05rem; }
        .analytics-activity-summary strong { display: block; margin-top: .35rem; color: var(--text-main); font-size: 2rem; font-weight: 900; letter-spacing: -.05em; font-variant-numeric: tabular-nums; }
        .activity-status-chart { margin-top: .72rem; }
        .activity-save { background: #06b6d4; } .activity-validation { background: #6366f1; } .activity-change { background: #f59e0b; }
        .legend-save { background: #06b6d4; } .legend-validation { background: #6366f1; } .legend-change { background: #f59e0b; }
        @media (max-width: 1100px) { .analytics-overview-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } .analytics-focus-grid-visual { grid-template-columns: 1.2fr 1fr; } .focus-action { grid-column: span 2; } }
        @media (max-width: 640px) { .analytics-overview-head { align-items: flex-start; flex-direction: column; } .analytics-overview-grid { grid-template-columns: 1fr 1fr; } .analytics-metric-card { min-height: 124px; padding: .9rem; } .analytics-metric-card strong { font-size: 1.45rem; } .analytics-focus-grid-visual { grid-template-columns: 1fr; } .focus-action { grid-column: auto; } }
        @media (prefers-reduced-motion: reduce) { .analytics-metric-card, #analyticsTooltipPortal, .analytics-status-chart > span { transition: none !important; } }
    </style>


    {{-- =====================================================================
         PENYEMPURNAAN JARAK DAN PENYEDERHANAAN ANALITIK
         Fokus pada ruang baca, urutan data, dan elemen inti.
         ===================================================================== --}}
    <style>
        .analytics-content-shell {
            display: flex !important;
            flex-direction: column;
            gap: clamp(2rem, 3vw, 3.25rem) !important;
            max-width: 92rem;
            padding-bottom: 6rem;
        }

        .analytics-content-shell > * {
            margin-top: 0 !important;
            margin-bottom: 0 !important;
        }

        .analytics-filter-wrap,
        .analytics-section {
            margin: 0 !important;
        }

        .analytics-overview {
            gap: 1.35rem;
        }

        .analytics-overview-grid {
            gap: 1rem;
        }

        .analytics-metric-card {
            min-height: 150px;
            padding: 1.25rem;
        }

        #lab-status .analytics-focus-head {
            padding: 1.2rem 1.45rem;
        }

        #lab-status .analytics-focus-grid {
            display: block;
            padding: 1.2rem 1.45rem 1.45rem;
            background: transparent;
        }

        #lab-status .analytics-focus-item {
            padding: 1.05rem 1.15rem;
            border: 1px solid var(--analytics-border);
            border-radius: 1rem;
            background: var(--analytics-surface-soft);
        }

        #lab-status .analytics-status-chart {
            margin-top: 1rem;
        }

        #lab-classes .analytics-section-heading-row {
            margin-bottom: 1.5rem !important;
        }

        #lab-chart-section .analytics-panel > :first-child {
            padding: 1.5rem 1.65rem !important;
        }

        #lab-chart-section .analytics-chart-stats {
            gap: 1rem !important;
            padding: 1.35rem 1.65rem 0 !important;
        }

        #lab-chart-section .analytics-chart-body {
            padding: 1.65rem !important;
        }

        #lab-students > :first-child,
        #lab-students > :last-child {
            padding: 1.5rem 1.65rem !important;
        }

        #lab-students .analytics-student-grid {
            gap: 1rem !important;
        }

        @media (max-width: 767px) {
            .analytics-content-shell {
                gap: 1.75rem !important;
                padding-bottom: 4rem;
            }

            .analytics-metric-card {
                min-height: 138px;
                padding: 1rem;
            }

            #lab-status .analytics-focus-head,
            #lab-status .analytics-focus-grid,
        }
    </style>



    {{-- =====================================================================
         RUANG BACA BAGIAN BAWAH
         Menjaga semua data analitik, dengan jarak tegas antarcontainer.
         ===================================================================== --}}
    <style>
        .analytics-content-shell {
            gap: clamp(2.5rem, 4vw, 4rem) !important;
        }

        /* Margin langsung dipakai agar jarak tetap terlihat meskipun layout berubah. */
        #lab-chart-section.analytics-section,
        #lab-students.analytics-section {
            margin-top: clamp(3rem, 4.8vw, 4.75rem) !important;
        }

        #lab-students.analytics-section {
            margin-bottom: 3rem !important;
        }

        /* Kembalikan tiga data ringkas pada status, bukan hanya grafik hasil. */
        #lab-status .analytics-focus-grid {
            display: grid !important;
            grid-template-columns: minmax(0, 1.25fr) repeat(2, minmax(0, 1fr));
            gap: 1.25rem !important;
            padding: 1.35rem 1.5rem 1.55rem !important;
            background: transparent !important;
        }

        #lab-status .analytics-focus-item {
            min-height: 150px;
            padding: 1.15rem 1.2rem;
            border: 1px solid var(--analytics-border);
            border-radius: 1rem;
            background: var(--analytics-surface-soft);
        }

        #lab-status .focus-obstacle h4,
        #lab-status .focus-action h4,
        #lab-status .focus-obstacle span,
        #lab-status .focus-action span {
            overflow: visible;
            white-space: normal;
            text-overflow: clip;
        }

        #lab-chart-section .analytics-panel > :first-child {
            padding: 1.65rem 1.8rem !important;
        }

        #lab-chart-section .analytics-chart-stats {
            gap: 1.15rem !important;
            padding: 1.55rem 1.8rem 0 !important;
        }

        #lab-chart-section .analytics-chart-body {
            padding: 1.8rem !important;
        }

        #lab-students > :first-child,
        #lab-students > :last-child {
            padding: 1.65rem 1.8rem !important;
        }

        #lab-students .analytics-student-grid {
            gap: 1.25rem !important;
        }

        #lab-students .student-performance-card {
            padding: 1.2rem !important;
        }

        @media (max-width: 1100px) {
            #lab-status .analytics-focus-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
            #lab-status .focus-action {
                grid-column: span 2;
            }
        }

        @media (max-width: 767px) {
            .analytics-content-shell {
                gap: 2.25rem !important;
            }
        #lab-chart-section.analytics-section,
            #lab-students.analytics-section {
                margin-top: 2.5rem !important;
            }

            #lab-status .analytics-focus-grid {
                grid-template-columns: 1fr;
                gap: .85rem !important;
                padding: 1rem !important;
            }

            #lab-status .focus-action {
                grid-column: auto;
            }

            #lab-status .analytics-focus-item {
                min-height: auto;
                padding: 1rem;
            }
        }
    </style>


    {{-- =====================================================================
         PENYEMPURNAAN HEADING & TIPOGRAFI DATA ANALITIK
         - Heading dibuat seragam: konteks singkat di atas, judul data di bawah.
         - Angka memakai Inter dengan tabular figures agar nyaman dipindai.
         - JetBrains Mono tetap dipakai pada area kode saja; data analitik tidak.
         ===================================================================== --}}
    <style id="analytics-heading-typography-refinement">
        :root {
            --analytics-reading-font: 'Inter', sans-serif;
            --analytics-number-font: 'Inter', sans-serif;
        }

        /* Hierarki heading: label konteks lebih tenang, judul fokus pada data. */
        .analytics-overview-head {
            align-items: flex-start;
        }
        .analytics-overview-head p,
        #lab-classes .analytics-section-heading-row > div > p,
        .analytics-overview-head h3,
        #lab-classes .analytics-section-heading-row h3,
        .analytics-overview-head h3 {
            font-size: clamp(1.18rem, 1.7vw, 1.55rem) !important;
        }

        /* Bahasa heading mikro disederhanakan dan tidak terlalu rapat. */
        .analytics-metric-title,
        .analytics-focus-head p,
        .analytics-focus-label,
        .analytics-section-heading-row p,
        .analytics-chart-stats p,
        #lab-students thead {
            font-family: var(--analytics-reading-font) !important;
            letter-spacing: .09em !important;
        }
        .analytics-metric-title {
            font-size: .64rem !important;
            font-weight: 800 !important;
            line-height: 1.3 !important;
        }

        /* Angka data: tabular numerals memudahkan perbandingan lintas kartu/tabel. */
        .smooth-analytics-scroll .analytics-data-number,
        .smooth-analytics-scroll .analytics-metric-card strong,
        .smooth-analytics-scroll .analytics-focus-value,
        .smooth-analytics-scroll .analytics-focus-item strong,
        .smooth-analytics-scroll .analytics-chart-stats .text-xl,
        .smooth-analytics-scroll .font-mono {
            font-family: var(--analytics-number-font) !important;
            font-variant-numeric: tabular-nums lining-nums;
            font-feature-settings: 'tnum' 1, 'lnum' 1;
            letter-spacing: -.02em;
        }
        .smooth-analytics-scroll .analytics-metric-card strong {
            font-size: clamp(1.7rem, 2vw, 1.95rem) !important;
            font-weight: 800 !important;
            line-height: 1.05 !important;
        }
        .smooth-analytics-scroll .analytics-metric-card small {
            max-width: 17rem;
            font-size: .72rem !important;
            font-weight: 650 !important;
            line-height: 1.45 !important;
        }
        .smooth-analytics-scroll .analytics-chart-stats .text-xl,
        .smooth-analytics-scroll #lab-classes .text-lg.font-black,
        .smooth-analytics-scroll #lab-obstacles .text-lg.font-black,
        .smooth-analytics-scroll #lab-students tbody .font-black {
            font-weight: 800 !important;
        }

        /* Tabel siswa: data tetap jelas tanpa kesan teknis/monospace berlebihan. */
        #lab-students table {
            font-family: var(--analytics-reading-font) !important;
            font-size: .78rem !important;
        }
        #lab-students thead {
            font-size: .61rem !important;
            font-weight: 800 !important;
        }
        #lab-students tbody td {
            line-height: 1.45;
        }
        #lab-students tbody td.font-mono,
        #lab-students tbody .font-mono {
            font-size: .76rem !important;
            font-weight: 700 !important;
        }

        /* Subjudul data dibuat lebih mudah dibaca pada kartu dan panel. */
        .analytics-data-row h5,
        .analytics-data-row h4,
        .analytics-panel h4,
        .student-performance-card h4 {
            font-family: var(--analytics-reading-font) !important;
            font-weight: 800 !important;
            letter-spacing: -.012em !important;
        }
        .analytics-data-row p,
        .analytics-panel p,
        .student-performance-card p {
            font-family: var(--analytics-reading-font) !important;
        }

        @media (max-width: 640px) {
            .analytics-overview-head h3,
            #lab-classes .analytics-section-heading-row h3,
            .smooth-analytics-scroll .analytics-metric-card strong {
                font-size: 1.55rem !important;
            }
        }
    </style>


    <style id="class-analytics-focus-style">
        /* Performa kelas: semua elemen menampilkan ukuran yang dapat dibandingkan langsung. */
        .class-performance-board { overflow: hidden; }
        .class-performance-row { background: rgba(255,255,255,.26); }
        .dark .class-performance-row { background: rgba(255,255,255,.012); }
        .class-performance-row:hover { background: rgba(6,182,212,.045); }
        .dark .class-performance-row:hover { background: rgba(34,211,238,.045); }
        .class-analytics-cell {
            min-width: 0;
            border: 1px solid var(--analytics-border);
            border-radius: .92rem;
            background: var(--analytics-surface-soft);
            padding: .78rem .82rem;
        }
        .class-analytics-cell.compact { padding: .68rem .72rem; }
        .class-analytics-label {
            color: var(--analytics-muted);
            font-size: .57rem;
            font-weight: 900;
            letter-spacing: .105em;
            line-height: 1.2;
            text-transform: uppercase;
        }
        .class-analytics-value {
            font-size: 1.18rem;
            font-weight: 900;
            letter-spacing: -.045em;
            line-height: 1;
            font-variant-numeric: tabular-nums;
        }
        .class-analytics-meter { overflow: hidden; height: .46rem; border-radius: 999px; background: rgba(148,163,184,.18); }
        .dark .class-analytics-meter { background: rgba(255,255,255,.08); }
        .class-analytics-meter > span { display:block; height:100%; border-radius:inherit; transition:width .35s cubic-bezier(.22,1,.36,1); }
        .class-analytics-note { margin-top:.45rem; color:var(--analytics-muted); font-size:.62rem; font-weight:750; line-height:1.35; }
        .class-modal-row:hover { box-shadow: 0 12px 26px rgba(15,23,42,.06); }
        .class-detail-metric { min-width:0; border:1px solid var(--analytics-border); border-radius:1rem; background:var(--analytics-surface-soft); padding:1rem; }
        .class-detail-metric p { color:var(--analytics-muted); font-size:.58rem; font-weight:900; letter-spacing:.11em; text-transform:uppercase; }
        .class-detail-metric strong { display:block; margin-top:.42rem; color:var(--text-main); font-size:1.65rem; font-weight:900; letter-spacing:-.05em; line-height:1; font-variant-numeric:tabular-nums; }
        .class-detail-metric span { display:block; margin-top:.42rem; color:var(--analytics-muted); font-size:.63rem; font-weight:700; line-height:1.35; }
        @media (max-width: 1023px) { .class-performance-row { grid-template-columns: repeat(2, minmax(0,1fr)); } }
        @media (max-width: 639px) { .class-performance-row { grid-template-columns: 1fr; padding:1rem; } .class-analytics-cell { padding:.72rem; } }
        @media (prefers-reduced-motion: reduce) { .class-performance-row, .class-analytics-meter > span { transition:none !important; } }
    </style>



    {{-- ================================================================
         PENYELARASAN UKURAN DENGAN DESAIN AWAL PANEL ADMIN
         Mempertahankan data analitik berbasis kelas, tetapi mengembalikan
         proporsi ruang, kartu, heading, grafik, dan tabel agar tidak terlalu
         besar dibanding halaman panel admin lainnya.
         ================================================================ --}}
    <style id="analytics-initial-scale-alignment">
        /* Kanvas konten kembali ke lebar dan jarak panel awal. */
        .analytics-content-shell {
            max-width: 84rem !important;
            gap: clamp(1.5rem, 2.25vw, 2.5rem) !important;
            padding-bottom: 5rem !important;
        }
        .analytics-content-shell > .analytics-section,
        #lab-chart-section.analytics-section,
        #lab-students.analytics-section {
            margin-top: 0 !important;
        }

        /* Heading: label konteks kecil, judul menjadi fokus utama. */
        .analytics-overview { gap: 1rem !important; }
        .analytics-overview-head { align-items: flex-start !important; }
        .analytics-overview-head p,
        #lab-classes .analytics-section-heading-row > div > p:first-child,
        #lab-chart-section .analytics-panel > :first-child > div > div > p {
            font-size: .63rem !important;
            font-weight: 900 !important;
            letter-spacing: .16em !important;
            line-height: 1.25 !important;
        }
        .analytics-overview-head h3,
        #lab-classes .analytics-section-heading-row h3,
        #lab-chart-section .analytics-panel h3 {
            font-size: 1.25rem !important;
            font-weight: 800 !important;
            letter-spacing: -.025em !important;
            line-height: 1.2 !important;
        }
        #lab-classes .analytics-section-heading-row > div > p:last-child {
            max-width: 42rem !important;
            font-size: .72rem !important;
            font-weight: 600 !important;
            line-height: 1.45 !important;
        }
        #lab-classes .analytics-section-heading-row {
            gap: .75rem !important;
            margin-bottom: .9rem !important;
        }

        /* Kartu ringkasan dikembalikan ke tinggi dan padding awal. */
        .analytics-overview-grid { gap: .75rem !important; }
        .analytics-metric-card {
            min-height: 132px !important;
            border-radius: 1rem !important;
            padding: 1rem !important;
        }
        .analytics-metric-title {
            font-size: .60rem !important;
            font-weight: 900 !important;
            letter-spacing: .12em !important;
            line-height: 1.25 !important;
        }
        .smooth-analytics-scroll .analytics-metric-card strong {
            margin-top: .82rem !important;
            font-size: 1.75rem !important;
            font-weight: 800 !important;
            line-height: 1 !important;
        }
        .smooth-analytics-scroll .analytics-metric-card small {
            margin-top: .32rem !important;
            font-size: .68rem !important;
            font-weight: 650 !important;
            line-height: 1.35 !important;
        }

        /* Papan kelas mempertahankan empat aspek data, dengan densitas panel awal. */
        .class-performance-board {
            margin-top: .85rem !important;
            border-radius: 1rem !important;
        }
        .class-performance-row {
            gap: .75rem !important;
            padding: 1rem !important;
        }
        .class-performance-row h4 { font-size: .94rem !important; }
        .class-performance-row > div:first-child > p { font-size: .62rem !important; }
        .class-analytics-cell {
            border-radius: .75rem !important;
            padding: .65rem .7rem !important;
        }
        .class-analytics-cell.compact { padding: .62rem .65rem !important; }
        .class-analytics-label { font-size: .54rem !important; }
        .class-analytics-value { font-size: 1.02rem !important; }
        .class-analytics-note { margin-top: .34rem !important; font-size: .58rem !important; }
        .class-performance-row .text-lg { font-size: 1rem !important; }
        .class-performance-row .text-\[10px\] { font-size: .58rem !important; }

        /* Grafik dipadatkan agar proporsional dengan kartu analitik awal. */
        #lab-chart-section .analytics-panel { border-radius: 1rem !important; }
        #lab-chart-section .analytics-panel > :first-child {
            padding: 1.1rem 1.25rem !important;
        }
        #lab-chart-section .analytics-chart-stats {
            gap: .75rem !important;
            padding: .9rem 1.25rem 0 !important;
        }
        #lab-chart-section .analytics-chart-stats > div {
            border-radius: .75rem !important;
            padding: .75rem !important;
        }
        #lab-chart-section .analytics-chart-stats p { font-size: .56rem !important; }
        #lab-chart-section .analytics-chart-stats .text-xl { font-size: 1.1rem !important; }
        #lab-chart-section .analytics-chart-body { padding: 1.25rem !important; }
        #lab-chart-section .analytics-chart-body > div { height: 280px !important; }

        /* Tabel siswa mengikuti skala tabel awal, tanpa membesar dari panel lain. */
        #lab-students {
            border-radius: 1rem !important;
            margin-top: 0 !important;
        }
        #lab-students > :first-child,
        #lab-students > :last-child {
            padding: 1.1rem 1.25rem !important;
        }
        #lab-students > :first-child { gap: .75rem !important; }
        #lab-students > :first-child h3 { font-size: 1rem !important; }
        #lab-students > :first-child p { font-size: .68rem !important; }
        #lab-students .max-h-\[460px\] {
            max-height: 390px !important;
            border-radius: .75rem !important;
        }
        #lab-students table { font-size: .72rem !important; }
        #lab-students thead { font-size: .55rem !important; }
        #lab-students tbody td { padding-top: .7rem !important; padding-bottom: .7rem !important; }
        #lab-students tbody td:first-child > p:first-child { font-size: .82rem !important; }

        @media (max-width: 767px) {
            .analytics-content-shell {
                gap: 1.25rem !important;
                padding-bottom: 3.5rem !important;
            }
            .analytics-overview { gap: .85rem !important; }
            .analytics-overview-head h3,
            #lab-classes .analytics-section-heading-row h3,
            #lab-chart-section .analytics-panel h3 {
                font-size: 1.08rem !important;
            }
            .analytics-overview-grid { gap: .65rem !important; }
            .analytics-metric-card { min-height: 120px !important; padding: .9rem !important; }
            .smooth-analytics-scroll .analytics-metric-card strong { font-size: 1.5rem !important; }
            .class-performance-row { gap: .65rem !important; padding: .85rem !important; }
            #lab-chart-section .analytics-panel > :first-child,
            #lab-chart-section .analytics-chart-body,
            #lab-students > :first-child,
            #lab-students > :last-child { padding-left: 1rem !important; padding-right: 1rem !important; }
            #lab-chart-section .analytics-chart-stats { padding-left: 1rem !important; padding-right: 1rem !important; }
            #lab-chart-section .analytics-chart-body > div { height: 245px !important; }
        }
    </style>


    {{-- =====================================================================
         PENYELARASAN FINAL: ANALITIK PRAKTIK LAB = BAHASA VISUAL ANALITIK KUIS
         Satu sistem untuk skala, tabel kelas, panel, chart, dan insight hero.
         ===================================================================== --}}
    <style id="lab-quiz-design-system-alignment">
        :root {
            --quiz-surface: rgba(255,255,255,.92);
            --quiz-surface-soft: #f8fafc;
            --quiz-line: rgba(148,163,184,.24);
            --quiz-line-strong: rgba(99,102,241,.30);
            --quiz-muted: #64748b;
            --quiz-ink: #0f172a;
            --quiz-shadow: 0 16px 36px rgba(15,23,42,.06);
        }
        .dark {
            --quiz-surface: rgba(10,14,23,.90);
            --quiz-surface-soft: rgba(15,23,42,.78);
            --quiz-line: rgba(148,163,184,.16);
            --quiz-line-strong: rgba(129,140,248,.34);
            --quiz-muted: #94a3b8;
            --quiz-ink: #f8fafc;
            --quiz-shadow: 0 18px 42px rgba(2,6,23,.28);
        }

        /* Skala kanvas dan ritme bagian sama dengan dasbor Analitik Kuis. */
        .lab-quiz-analytics-shell {
            display: flex !important;
            max-width: 84rem !important;
            margin-inline: auto;
            flex-direction: column;
            gap: clamp(1.5rem, 2.2vw, 2.25rem) !important;
            padding-bottom: 4.25rem !important;
        }
        .lab-quiz-analytics-shell > * { margin-top: 0 !important; margin-bottom: 0 !important; }
        .lab-quiz-analytics-shell .glass-card,
        .lab-quiz-chart-panel,
        .quiz-student-analytics.analytics-panel,
        .quiz-class-performance-panel {
            border-color: var(--quiz-line) !important;
            box-shadow: var(--quiz-shadow) !important;
        }

        /* Ringkasan memakai partial compact_analytics_strip yang sama dengan kuis. */
        .lab-quiz-analytics-shell .compact-analytics-strip,
        .lab-quiz-analytics-shell [data-analytics-strip] {
            border-color: var(--quiz-line) !important;
            box-shadow: var(--quiz-shadow) !important;
        }

        /* Heading bagian mengikuti ukuran dan hirarki Analitik Kuis. */
        #lab-classes > .mb-3 { margin-bottom: .75rem !important; }
        #lab-classes > .mb-3 > div > p {
            font-size: .625rem !important;
            letter-spacing: .18em !important;
            line-height: 1.25 !important;
        }
        #lab-classes > .mb-3 h3 {
            font-size: 1.125rem !important;
            font-weight: 700 !important;
            letter-spacing: -.02em !important;
            line-height: 1.25 !important;
        }

        /* Papan kelas sama: header enam kolom, baris ringkas, responsif. */
        .quiz-class-performance-panel {
            overflow: hidden;
            border: 1px solid var(--quiz-line);
            border-radius: 1.15rem;
            background: var(--quiz-surface);
        }
        .quiz-class-performance-head {
            display: grid;
            grid-template-columns: minmax(0, 1.25fr) repeat(5, minmax(0, .72fr));
            gap: .75rem;
            padding: .72rem 1.1rem;
            border-bottom: 1px solid var(--quiz-line);
            background: var(--quiz-surface-soft);
            color: var(--quiz-muted);
            font-size: .56rem;
            font-weight: 900;
            letter-spacing: .09em;
            text-transform: uppercase;
        }
        .quiz-class-row {
            display: grid;
            grid-template-columns: minmax(0, 1.25fr) repeat(5, minmax(0, .72fr));
            gap: .75rem;
            align-items: center;
            padding: .92rem 1.1rem;
            border-bottom: 1px solid var(--quiz-line);
            transition: background-color .18s ease;
        }
        .quiz-class-row:last-child { border-bottom: 0; }
        .quiz-class-row:hover { background: rgba(99,102,241,.035); }
        .dark .quiz-class-row:hover { background: rgba(129,140,248,.055); }
        .quiz-class-name { min-width: 0; }
        .quiz-class-name strong {
            display: block;
            overflow: hidden;
            color: var(--quiz-ink);
            font-size: .82rem;
            font-weight: 850;
            line-height: 1.3;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .quiz-class-name span {
            display: block;
            margin-top: .2rem;
            color: var(--quiz-muted);
            font-size: .63rem;
            font-weight: 650;
            line-height: 1.2;
        }
        .quiz-class-metric { min-width: 0; text-align: center; }
        .quiz-class-metric strong {
            display: block;
            color: var(--quiz-ink);
            font-family: 'Inter', sans-serif;
            font-size: .91rem;
            font-weight: 850;
            letter-spacing: -.025em;
            line-height: 1.15;
            font-variant-numeric: tabular-nums;
        }
        .quiz-class-metric small {
            display: block;
            margin-top: .18rem;
            color: var(--quiz-muted);
            font-size: .60rem;
            font-weight: 700;
            line-height: 1.2;
        }
        .quiz-class-rate { color: #059669 !important; }
        .dark .quiz-class-rate { color: #6ee7b7 !important; }

        /* Grafik lab memakai permukaan, header, statistik, dan ukuran panel yang sama. */
        .lab-quiz-chart-panel {
            overflow: hidden !important;
            border: 1px solid var(--quiz-line) !important;
            border-radius: 1.15rem !important;
            background: var(--quiz-surface) !important;
        }
        .lab-quiz-panel-head {
            padding: 1.1rem 1.2rem !important;
            border-color: var(--quiz-line) !important;
            background: linear-gradient(110deg, var(--quiz-surface-soft), transparent) !important;
        }
        .lab-quiz-panel-head p {
            font-size: .625rem !important;
            letter-spacing: .16em !important;
        }
        .lab-quiz-panel-head h3 {
            font-size: 1.125rem !important;
            font-weight: 700 !important;
            letter-spacing: -.02em !important;
        }
        .lab-quiz-chart-stats {
            gap: .75rem !important;
            padding: .9rem 1.2rem 0 !important;
        }
        .lab-quiz-chart-stats > div {
            border-color: var(--quiz-line) !important;
            border-radius: .75rem !important;
            background: var(--quiz-surface-soft) !important;
            padding: .75rem !important;
        }
        .lab-quiz-chart-stats p { font-size: .56rem !important; }
        .lab-quiz-chart-stats .text-xl {
            font-family: 'Inter', sans-serif !important;
            font-size: 1.1rem !important;
            font-weight: 850 !important;
            font-variant-numeric: tabular-nums;
        }
        .lab-quiz-chart-body { padding: 1.2rem !important; }
        .lab-quiz-chart-body > div { height: 280px !important; }

        /* Tabel pengguna mengikuti panel Analitik Kuis tanpa tooltip atau narasi tindakan. */
        .quiz-student-analytics.analytics-panel {
            border-radius: 1.15rem !important;
            background: var(--quiz-surface) !important;
        }
        .quiz-student-analytics.analytics-panel > div:first-child {
            padding: 1.1rem 1.2rem !important;
            border-color: var(--quiz-line) !important;
            background: linear-gradient(115deg, var(--quiz-surface-soft), transparent) !important;
        }
        .quiz-student-analytics.analytics-panel > div:first-child h3 {
            font-size: 1rem !important;
            font-weight: 700 !important;
        }
        .quiz-student-analytics.analytics-panel > div:first-child p {
            font-size: .68rem !important;
        }
        .quiz-student-analytics.analytics-panel > div:last-child { padding: 1.1rem 1.2rem !important; }
        .quiz-student-analytics .max-h-\[460px\] {
            max-height: 390px !important;
            border-color: var(--quiz-line) !important;
            border-radius: .75rem !important;
            background: var(--quiz-surface-soft) !important;
        }
        .quiz-student-analytics table { font-family: 'Inter', sans-serif !important; font-size: .72rem !important; }
        .quiz-student-analytics thead {
            background: var(--quiz-surface-soft) !important;
            color: var(--quiz-muted) !important;
            font-size: .55rem !important;
            letter-spacing: .09em !important;
        }
        .quiz-student-analytics tbody td { padding-top: .7rem !important; padding-bottom: .7rem !important; }
        .quiz-student-analytics tbody td.font-mono,
        .quiz-student-analytics tbody .font-mono,
        .quiz-student-analytics .analytics-data-number {
            font-family: 'Inter', sans-serif !important;
            font-weight: 800 !important;
            font-variant-numeric: tabular-nums;
            font-feature-settings: 'tnum' 1, 'lnum' 1;
            letter-spacing: -.02em;
        }
        .quiz-student-analytics .table-row:hover { background: rgba(99,102,241,.035) !important; }
        .dark .quiz-student-analytics .table-row:hover { background: rgba(129,140,248,.055) !important; }

        /* Insight hero kelas memakai komponen dan proporsi modal Analitik Kuis. */
        .quiz-class-hero-modal {
            overflow: hidden;
            border: 1px solid rgba(99,102,241,.18);
            border-radius: 1.5rem;
            background: rgba(255,255,255,.98);
            box-shadow: 0 28px 78px rgba(15,23,42,.28);
        }
        .dark .quiz-class-hero-modal {
            border-color: rgba(129,140,248,.20);
            background: #0f141e;
            box-shadow: 0 30px 82px rgba(0,0,0,.74);
        }
        .quiz-class-hero-head {
            position: relative;
            overflow: hidden;
            border-bottom: 1px solid var(--quiz-line);
            background: linear-gradient(135deg, rgba(99,102,241,.15), rgba(255,255,255,.78) 58%, rgba(6,182,212,.10));
        }
        .quiz-class-hero-head::after {
            content: '';
            position: absolute;
            right: -3.8rem;
            top: -3.8rem;
            width: 10rem;
            height: 10rem;
            border: 18px solid rgba(99,102,241,.11);
            border-radius: 999px;
            pointer-events: none;
        }
        .dark .quiz-class-hero-head { background: linear-gradient(135deg, rgba(99,102,241,.20), rgba(15,23,42,.92) 58%, rgba(6,182,212,.12)); }
        .dark .quiz-class-hero-head::after { border-color: rgba(129,140,248,.16); }
        .quiz-class-hero-kicker { color: #4f46e5; font-size: .60rem; font-weight: 900; letter-spacing: .16em; text-transform: uppercase; }
        .dark .quiz-class-hero-kicker { color: #a5b4fc; }
        .quiz-class-hero-metric {
            min-width: 0;
            border: 1px solid var(--quiz-line);
            border-radius: .95rem;
            background: var(--quiz-surface-soft);
            padding: .92rem;
        }
        .quiz-class-hero-metric p,
        .quiz-class-hero-section-label { color: var(--quiz-muted); font-size: .58rem; font-weight: 900; letter-spacing: .11em; text-transform: uppercase; }
        .quiz-class-hero-metric strong {
            display: block;
            margin-top: .35rem;
            color: var(--quiz-ink);
            font-family: 'Inter', sans-serif;
            font-size: 1.3rem;
            font-weight: 900;
            letter-spacing: -.05em;
            line-height: 1;
            font-variant-numeric: tabular-nums;
        }
        .quiz-class-hero-metric span { display: block; margin-top: .30rem; color: var(--quiz-muted); font-size: .64rem; font-weight: 700; line-height: 1.28; }
        .quiz-class-hero-meter { display: flex; overflow: hidden; height: .65rem; border-radius: 999px; background: rgba(148,163,184,.18); }
        .dark .quiz-class-hero-meter { background: rgba(255,255,255,.075); }
        .quiz-class-hero-meter > span { display: block; height: 100%; transition: width .35s cubic-bezier(.22,1,.36,1); }

        @media (max-width: 900px) {
            .quiz-class-performance-head { display: none; }
            .quiz-class-row { grid-template-columns: minmax(0,1fr) repeat(2,minmax(0,1fr)); gap: .65rem; padding: 1rem; }
            .quiz-class-name { grid-column: 1 / -1; padding-bottom: .25rem; }
            .quiz-class-metric { padding: .62rem .68rem; border: 1px solid var(--quiz-line); border-radius: .7rem; background: var(--quiz-surface-soft); }
            .quiz-class-metric::before { content: attr(data-label); display:block; overflow:hidden; color:var(--quiz-muted); font-size:.53rem; font-weight:850; letter-spacing:.08em; line-height:1.15; text-overflow:ellipsis; text-transform:uppercase; white-space:nowrap; }
            .quiz-class-metric small { margin-top: .26rem; }
        }
        @media (max-width: 640px) {
            .lab-quiz-analytics-shell { gap: 1.25rem !important; padding-bottom: 3rem !important; }
            .quiz-class-row { grid-template-columns: repeat(2,minmax(0,1fr)); }
            .quiz-class-metric { min-height: 4rem; }
            .lab-quiz-chart-body > div { height: 245px !important; }
            .quiz-class-hero-modal { border-radius: 1.25rem; }
        }
        @media (prefers-reduced-motion: reduce) {
            .quiz-class-row,
            .quiz-class-hero-meter > span,
            .lab-quiz-chart-panel,
            .quiz-student-analytics .table-row { transition: none !important; }
        }
    </style>

</head>
<body x-data="{ 
    sidebarOpen: false,
    isFullscreen: false,
    
    // State Modal Hero Insight
    showAttemptsModal: false,
    showSuccessRateModal: false,
    showAvgScoreModal: false,
    showDurationModal: false,
    showClassInsightModal: false,
    showClassListModal: false,
    selectedClassInsight: {},
    openClassInsight(data) {
        this.selectedClassInsight = data || {};
        this.showClassInsightModal = true;
    },
    showDashboardInfoModal: false
}" @keydown.escape.window="isFullscreen = false; document.exitFullscreen(); showAttemptsModal = false; showSuccessRateModal = false; showAvgScoreModal = false; showDurationModal = false; showClassInsightModal = false; showClassListModal = false; showDashboardInfoModal = false;" :class="{'modal-open': sidebarOpen || showAttemptsModal || showSuccessRateModal || showAvgScoreModal || showDurationModal || showClassInsightModal || showClassListModal || showDashboardInfoModal}">

    @php
        $totalAttempts = $totalAttempts ?? 0;
        $passedCount = $passedCount ?? 0;
        $failedCount = $failedCount ?? 0;
        $completionRate = $completionRate ?? 0;
        $avgScore = $avgScore ?? 0;
        $avgDuration = $avgDuration ?? '00:00';
        
        $userPerformance = isset($userPerformance) ? collect($userPerformance) : collect([]);
        $labsList = isset($labsList) ? collect($labsList) : collect([]);
        $classGroups = isset($classGroups) ? collect($classGroups) : collect([]);
        $classPerformance = isset($classPerformance) ? collect($classPerformance) : collect([]);
        $selectedClass = $selectedClass ?? request('class_group');
        $selectedPeriod = $selectedPeriod ?? request('period', 'all');
        $periodOptions = collect($periodOptions ?? [
            'all' => 'Semua waktu',
            '7d' => '7 hari terakhir',
            '30d' => '30 hari terakhir',
            '6m' => '6 bulan terakhir',
        ]);
        $periodLabel = $periodLabel ?? ($periodOptions[$selectedPeriod] ?? 'Semua waktu');
        $analyticsRouteParams = !empty($labId) ? ['labId' => $labId] : [];
        $analyticsUrl = function (array $overrides = []) use ($analyticsRouteParams, $selectedClass, $selectedPeriod) {
            $query = [
                'class_group' => $selectedClass ?: null,
                'period' => $selectedPeriod !== 'all' ? $selectedPeriod : null,
            ];

            foreach ($overrides as $key => $value) {
                $query[$key] = $value;
            }

            $query = array_filter($query, fn ($value) => filled($value));

            return route('admin.lab.analytics', $analyticsRouteParams)
                . ($query ? ('?' . http_build_query($query)) : '');
        };
        
        $labChartLabels = isset($labChartLabels) ? collect($labChartLabels) : collect([]);
        $labChartScores = isset($labChartScores) ? collect($labChartScores) : collect([]);
        $labChartParticipants = isset($labChartParticipants) ? collect($labChartParticipants) : collect([]);
        $labChartAverage = $labChartAverage ?? null;
        $labChartHighest = $labChartHighest ?? null;
        $labChartLowest = $labChartLowest ?? null;
        $hasLabChartData = $hasLabChartData ?? $labChartScores->filter(fn ($score) => $score !== null)->count() > 0;

        if (!$hasLabChartData) {
            $labChartLabels = collect(['Belum ada data']);
            $labChartScores = collect([null]);
            $labChartParticipants = collect([0]);
        }

    @endphp

    <div class="flex h-screen w-full relative">

        <div x-show="sidebarOpen" class="fixed inset-0 bg-slate-900/80 dark:bg-[#020617]/80 backdrop-blur-sm z-[90] md:hidden transition-opacity" @click="sidebarOpen = false" x-transition.opacity style="display: none;" x-cloak></div>

    {{-- ==================== 1. SIDEBAR ==================== --}}
    <div x-show="sidebarOpen" class="fixed inset-0 bg-slate-900/60 dark:bg-[#020617]/80 backdrop-blur-sm z-[90] md:hidden transition-colors" @click="sidebarOpen = false" x-transition.opacity style="display: none;" x-cloak></div>

    <aside class="glass-sidebar w-72 h-full flex flex-col fixed md:relative z-[100] transition-transform duration-300 transform md:translate-x-0" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
        <div class="h-24 flex items-center justify-between px-8 border-b border-slate-200 dark:border-white/5 relative overflow-hidden group transition-colors">
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
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-fuchsia-500 flex items-center justify-center font-bold text-white text-xs shadow-lg">AD</div>
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

        {{-- ==================== MAIN CONTENT ==================== --}}
        <main id="admin-main-content" class="smooth-analytics-scroll custom-scrollbar flex-1 flex flex-col relative z-10 transition-colors duration-300 h-full overflow-y-auto overflow-x-hidden">
            
            {{-- HEADER RESPONSIVE --}}
            <header class="h-24 glass-header flex flex-col justify-center px-6 md:px-10 shrink-0 sticky top-0 z-40 transition-colors">
                <div class="flex items-center justify-between w-full">
                    <div class="flex items-center gap-4">
                        <button @click="sidebarOpen = true" class="md:hidden p-2 bg-slate-200 dark:bg-white/5 rounded-lg text-slate-700 dark:text-white hover:bg-slate-300 dark:hover:bg-white/10 transition-colors shadow-sm dark:shadow-none">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        </button>
                        
                        <div class="flex items-center gap-3">
                            <div>
                                <nav class="flex text-[10px] text-slate-500 dark:text-white/50 mb-1.5 font-bold hidden sm:flex transition-colors" aria-label="Breadcrumb">
                                    <ol class="inline-flex items-center space-x-1">
                                        <li class="inline-flex items-center"><a href="{{ route('admin.dashboard') ?? '#' }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Dasbor</a></li>
                                        <li>
                                            <div class="flex items-center transition-colors">
                                                <svg class="w-3 h-3 text-slate-400 dark:text-white/30 mx-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                                <span class="text-slate-900 dark:text-white transition-colors">Analitik Praktik </span>
                                            </div>
                                        </li>
                                    </ol>
                                </nav>
                                <div class="flex items-center gap-2">
                                    <h2 class="text-slate-900 dark:text-white font-bold text-lg md:text-xl tracking-tight transition-colors">Analitik Praktik Lab</h2>
                                    
                                    {{-- TOMBOL TRIGGER HERO MODAL PANDUAN --}}
                                    <button @click="showDashboardInfoModal = true" class="w-6 h-6 md:w-7 md:h-7 rounded-full border border-slate-200 dark:border-white/10 flex items-center justify-center text-[10px] md:text-xs font-black text-slate-400 dark:text-slate-500 hover:text-indigo-600 dark:hover:text-indigo-400 bg-white/50 dark:bg-white/5 backdrop-blur-sm hover:bg-white dark:hover:bg-white/10 hover:border-indigo-200 dark:hover:border-indigo-500/30 transition-all duration-300 shadow-sm hover:shadow-md focus:outline-none mt-0.5" title="Panduan Analitik Lab">
                                        ?
                                    </button>
                                </div>
                                <p class="page-context-subtitle text-[10px] md:text-xs flex items-center gap-1.5 mt-1 transition-colors">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Ringkasan hasil praktik, perbandingan kelas, dan data performa pengguna
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3 sm:gap-6">
                        <button onclick="window.location.reload()" class="p-2.5 text-slate-500 dark:text-white/40 hover:text-slate-900 dark:hover:text-white transition-colors rounded-full hover:bg-slate-200 dark:hover:bg-white/5 group hidden sm:block border border-transparent dark:hover:border-white/10" title="Segarkan">
                            <svg class="w-4 h-4 group-hover:rotate-180 transition-transform duration-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        </button>
                        <button @click="isFullscreen = !isFullscreen; isFullscreen ? document.documentElement.requestFullscreen() : document.exitFullscreen()" class="p-2.5 text-slate-500 dark:text-white/40 hover:text-slate-900 dark:hover:text-white transition-colors rounded-full hover:bg-slate-200 dark:hover:bg-white/5 hidden md:block border border-transparent dark:hover:border-white/10" title="Mode Layar Penuh">
                            <svg x-show="!isFullscreen" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                            <svg x-show="isFullscreen" style="display: none;" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                        <div class="text-right hidden lg:block border-l border-slate-300 dark:border-white/10 pl-5 ml-1 transition-colors">
                            <p class="text-sm font-bold text-slate-900 dark:text-white transition-colors">{{ \Carbon\Carbon::now()->translatedFormat('d M Y') }}</p>
                            <p class="text-[10px] text-slate-500 dark:text-white/40 font-mono mt-0.5 transition-colors">{{ \Carbon\Carbon::now()->format('H:i') }} WIB</p>
                        </div>
                    </div>
                </div>
            </header>

            {{-- CONTENT SCROLLABLE --}}
            <div class="flex-1 p-5 md:p-8 lg:p-10 relative z-10">
                <div class="analytics-content-shell lab-quiz-analytics-shell">

                    @php
                        $filterId = 'lab-analytics-filter';
                        $filterTitle = 'Filter Data';
                        $filterSummary = $periodLabel . ($selectedClass ? ' · ' . $selectedClass : ' · Semua kelas');
                        $filterAction = route('admin.lab.analytics', $analyticsRouteParams);
                        $filterControls = [
                            [
                                'name' => 'period',
                                'label' => 'Periode',
                                'selected' => $selectedPeriod,
                                'options' => $periodOptions,
                                'minWidth' => 'min-w-[180px]',
                            ],
                            [
                                'name' => 'class_group',
                                'label' => 'Kelas',
                                'selected' => $selectedClass,
                                'emptyLabel' => 'Semua kelas',
                                'options' => $classGroups->mapWithKeys(fn ($className) => [$className => $className]),
                                'minWidth' => 'min-w-[220px]',
                            ],
                        ];
                        $filterResetHref = route('admin.lab.analytics', $analyticsRouteParams);
                        $filterResetVisible = $selectedClass || $selectedPeriod !== 'all';
                    @endphp
                    <div class="analytics-filter-wrap">
                        @include('admin.partials.analytics_filter_bar')
                    </div>

                    @php
                        $attemptCount = max(0, (int) $totalAttempts);
                        $passedAttemptCount = max(0, (int) $passedCount);
                        $failedAttemptCount = max(0, (int) $failedCount);
                        $statusTotal = max(1, $attemptCount, $passedAttemptCount + $failedAttemptCount);
                        $unfinishedAttemptCount = max(0, $statusTotal - $passedAttemptCount - $failedAttemptCount);
                        $passedShare = round(($passedAttemptCount / $statusTotal) * 100, 1);
                        $failedShare = round(($failedAttemptCount / $statusTotal) * 100, 1);
                        $unfinishedShare = round(($unfinishedAttemptCount / $statusTotal) * 100, 1);
                    @endphp

                    @php
                        $labParticipantCount = max(0, (int) $userPerformance->count());
                        $analyticsTitle = 'Ringkasan Praktik Lab';
                        $analyticsSubtitle = 'Data pada periode dan kelas yang dipilih.';
                        $analyticsItems = [
                            [
                                'label' => 'Percobaan',
                                'value' => number_format($attemptCount),
                                'hint' => 'sesi praktik selesai',
                                'tone' => 'cyan',
                            ],
                            [
                                'label' => 'Pengguna',
                                'value' => number_format($labParticipantCount),
                                'hint' => 'pengguna dengan praktik tercatat',
                                'tone' => 'indigo',
                            ],
                            [
                                'label' => 'Kelulusan',
                                'value' => $completionRate . '%',
                                'hint' => number_format($passedAttemptCount) . ' dari ' . number_format($attemptCount) . ' percobaan',
                                'tone' => 'emerald',
                            ],
                            [
                                'label' => 'Skor Rata-rata',
                                'value' => number_format((float) $avgScore, 1),
                                'hint' => 'nilai akhir praktik',
                                'tone' => 'amber',
                            ],
                        ];
                        $analyticsActions = [];
                    @endphp
                    @include('admin.partials.compact_analytics_strip')

                    {{-- =======================================================
                         B. RINGKASAN PER KELAS
                         ======================================================= --}}
                    {{-- =======================================================
                         KINERJA PRAKTIK PER KELAS
                         Tata baca, proporsi, dan insight mengikuti Analitik Kuis.
                         ======================================================= --}}
                    <section id="lab-classes" class="analytics-section reveal scroll-mt-28" style="animation-delay: .08s;" aria-label="Kinerja praktik per kelas">
                        <div class="mb-3 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                            <div>
                                <p class="text-[10px] font-extrabold uppercase tracking-[.18em] text-cyan-600 dark:text-cyan-400">Perbandingan Kelas</p>
                                <h3 class="mt-1 text-lg font-bold text-slate-900 dark:text-white">Kinerja Praktik per Kelas</h3>
                            </div>
                            <span class="text-[10px] font-bold text-slate-500 dark:text-white/45">{{ number_format($classPerformance->count()) }} kelas · praktik tercatat · pilih baris untuk insight</span>
                        </div>

                        <div class="quiz-class-performance-panel lab-class-performance-panel">
                            <div class="quiz-class-performance-head">
                                <span>Kelas</span>
                                <span class="text-center">pengguna</span>
                                <span class="text-center">Percobaan</span>
                                <span class="text-center">Kelulusan</span>
                                <span class="text-center">Skor rata-rata</span>
                                <span class="text-center">Durasi rata-rata</span>
                            </div>
                            @forelse($classPerformance as $classRow)
                                @php
                                    $classAttempts = max(0, (int) ($classRow->total_attempts ?? 0));
                                    $classLulus = max(0, (int) ($classRow->passed_attempts ?? 0));
                                    $classFailed = max(0, (int) ($classRow->failed_attempts ?? max(0, $classAttempts - $classLulus)));
                                    $classRate = $classAttempts > 0
                                        ? round(($classLulus / $classAttempts) * 100, 1)
                                        : 0;
                                    $classAvg = round((float) ($classRow->avg_score ?? 0), 1);
                                    $classStudentsWithAttempts = max(0, (int) ($classRow->students_count ?? 0));
                                    $classEnrolled = max(0, (int) ($classRow->enrolled_students ?? $classStudentsWithAttempts));
                                    $classAttemptsPerStudent = $classStudentsWithAttempts > 0
                                        ? round($classAttempts / $classStudentsWithAttempts, 1)
                                        : 0;
                                    $classAverageDuration = $classRow->avg_time_label ?? '-';
                                    $classInsightPayload = [
                                        'name' => $classRow->class_group ?: 'Kelas belum diatur',
                                        'major' => $classRow->major ?: 'Program belum diatur',
                                        'students_count' => $classStudentsWithAttempts,
                                        'enrolled_students' => $classEnrolled,
                                        'total_attempts' => $classAttempts,
                                        'passed_attempts' => $classLulus,
                                        'failed_attempts' => $classFailed,
                                        'pass_rate' => $classRate,
                                        'avg_score' => $classAvg,
                                        'avg_time' => $classAverageDuration,
                                        'attempts_per_student' => $classAttemptsPerStudent,
                                    ];
                                @endphp
                                <article
                                    role="button"
                                    tabindex="0"
                                    aria-label="Buka insight kinerja praktik kelas {{ $classRow->class_group }}"
                                    data-class-insight='@json($classInsightPayload, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_HEX_TAG)'
                                    @click="openClassInsight(JSON.parse($event.currentTarget.dataset.classInsight))"
                                    @keydown.enter="openClassInsight(JSON.parse($event.currentTarget.dataset.classInsight))"
                                    @keydown.space.prevent="openClassInsight(JSON.parse($event.currentTarget.dataset.classInsight))"
                                    class="quiz-class-row cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-indigo-500/50"
                                >
                                    <div class="quiz-class-name">
                                        <strong>{{ $classRow->class_group ?: 'Kelas belum diatur' }}</strong>
                                        <span>{{ number_format($classStudentsWithAttempts) }} pengguna dengan riwayat praktik</span>
                                    </div>
                                    <div class="quiz-class-metric" data-label="pengguna">
                                        <strong>{{ number_format($classStudentsWithAttempts) }}</strong>
                                        <small>{{ $classEnrolled > 0 ? 'dari ' . number_format($classEnrolled) . ' terdaftar' : 'pengguna' }}</small>
                                    </div>
                                    <div class="quiz-class-metric" data-label="Percobaan">
                                        <strong>{{ number_format($classAttempts) }}</strong>
                                        <small>{{ $classAttemptsPerStudent }} / pengguna</small>
                                    </div>
                                    <div class="quiz-class-metric" data-label="Kelulusan">
                                        <strong class="quiz-class-rate">{{ number_format($classLulus) }}/{{ number_format($classAttempts) }}</strong>
                                        <small>{{ $classRate }}%</small>
                                    </div>
                                    <div class="quiz-class-metric" data-label="Skor rata-rata">
                                        <strong>{{ $classAvg }}</strong>
                                        <small>dari 100</small>
                                    </div>
                                    <div class="quiz-class-metric" data-label="Durasi rata-rata">
                                        <strong>{{ $classAverageDuration }}</strong>
                                        <small>waktu per sesi</small>
                                    </div>
                                </article>
                            @empty
                                <div class="px-5 py-10 text-center text-xs font-semibold text-slate-500 dark:text-white/40">
                                    Belum ada riwayat praktik yang dapat dikelompokkan berdasarkan kelas.
                                </div>
                            @endforelse
                        </div>
                    </section>

                    <div id="lab-chart-section" class="analytics-section reveal scroll-mt-28" style="animation-delay: 0.26s;" x-data="{ chartType: 'line' }">
                        <div class="lab-quiz-chart-panel glass-card analytics-panel chart-surface rounded-2xl overflow-hidden relative">
                            <div class="lab-quiz-panel-head relative px-6 py-5 border-b border-slate-200 dark:border-white/5 bg-slate-50/60 dark:bg-[#0a0e17]/50 transition-colors overflow-hidden">
                                <div class="relative z-10 flex flex-col gap-5 xl:flex-row xl:items-center xl:justify-between">
                                    <div>
                                        <p class="text-[10px] font-extrabold uppercase tracking-[0.2em] text-indigo-600 dark:text-indigo-400 mb-1">
                                            Performa Praktik
                                        </p>
                                        <div class="flex items-center gap-2"><h3 class="text-xl md:text-2xl font-black text-slate-900 dark:text-white transition-colors">Skor Rata-rata per Lab</h3><span class="analytics-help" tabindex="0" role="button" data-analytics-tooltip="Setiap titik atau batang mewakili rata-rata skor terbaik siswa pada satu lab.">i</span></div>
                                    </div>

                                    <div class="flex flex-col sm:flex-row gap-2 sm:items-center">
                                        <div class="flex items-center bg-white/80 dark:bg-[#020617] p-1 rounded-xl border border-slate-200 dark:border-white/5 shadow-inner transition-colors">
                                            <button type="button" @click="chartType = 'line'; window.updateMainPerformanceChartType('line')" :class="chartType === 'line' ? 'bg-slate-900 dark:bg-white text-white dark:text-[#020617] shadow-sm' : 'text-slate-500 dark:text-white/50 hover:text-slate-900 dark:hover:text-white'" class="px-4 py-2 rounded-lg text-[10px] font-bold transition focus:outline-none">Garis</button>
                                            <button type="button" @click="chartType = 'bar'; window.updateMainPerformanceChartType('bar')" :class="chartType === 'bar' ? 'bg-slate-900 dark:bg-white text-white dark:text-[#020617] shadow-sm' : 'text-slate-500 dark:text-white/50 hover:text-slate-900 dark:hover:text-white'" class="px-4 py-2 rounded-lg text-[10px] font-bold transition focus:outline-none">Batang</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="lab-quiz-chart-stats analytics-chart-stats grid grid-cols-2 md:grid-cols-4 gap-3 px-6 pt-5">
                                <div class="rounded-2xl bg-slate-50 dark:bg-[#020617]/70 border border-slate-200 dark:border-white/5 p-4 transition-colors">
                                    <p class="text-[9px] uppercase tracking-widest font-bold text-slate-400 dark:text-white/30">Skor rata-rata <span class="analytics-help analytics-help-inline" tabindex="0" role="button" data-analytics-tooltip="Rata-rata skor terbaik siswa pada seluruh lab yang memiliki data pada filter aktif.">i</span></p>
                                    <p class="mt-1 text-xl font-black text-blue-600 dark:text-blue-400">{{ $labChartAverage !== null ? $labChartAverage : '-' }}</p>
                                </div>
                                <div class="rounded-2xl bg-slate-50 dark:bg-[#020617]/70 border border-slate-200 dark:border-white/5 p-4 transition-colors">
                                    <p class="text-[9px] uppercase tracking-widest font-bold text-slate-400 dark:text-white/30">Skor paling tinggi <span class="analytics-help analytics-help-inline" tabindex="0" role="button" data-analytics-tooltip="Nilai tertinggi yang muncul di antara rata-rata skor terbaik setiap lab, bukan nilai tertinggi dari satu percobaan individu.">i</span></p>
                                    <p class="mt-1 text-xl font-black text-emerald-600 dark:text-emerald-400">{{ $labChartHighest !== null ? $labChartHighest : '-' }}</p>
                                </div>
                                <div class="rounded-2xl bg-slate-50 dark:bg-[#020617]/70 border border-slate-200 dark:border-white/5 p-4 transition-colors">
                                    <p class="text-[9px] uppercase tracking-widest font-bold text-slate-400 dark:text-white/30">Skor paling rendah <span class="analytics-help analytics-help-inline" tabindex="0" role="button" data-analytics-tooltip="Nilai terendah yang muncul di antara rata-rata skor lab pada filter aktif.">i</span></p>
                                    <p class="mt-1 text-xl font-black text-rose-600 dark:text-rose-400">{{ $labChartLowest !== null ? $labChartLowest : '-' }}</p>
                                </div>
                                <div class="rounded-2xl bg-slate-50 dark:bg-[#020617]/70 border border-slate-200 dark:border-white/5 p-4 transition-colors">
                                    <p class="text-[9px] uppercase tracking-widest font-bold text-slate-400 dark:text-white/30">Modul terbaca <span class="analytics-help analytics-help-inline" tabindex="0" role="button" data-analytics-tooltip="Jumlah modul lab yang memiliki data skor dan dapat ditampilkan pada grafik.">i</span></p>
                                    <p class="mt-1 text-xl font-black text-indigo-600 dark:text-indigo-400">{{ $hasLabChartData ? $labChartLabels->count() : 0 }}</p>
                                </div>
                            </div>

                            <div class="lab-quiz-chart-body analytics-chart-body relative p-6">
                                <div class="relative h-[330px] w-full z-10">
                                    @if($hasLabChartData)
                                        <canvas id="mainPerformanceChart"></canvas>
                                    @else
                                        <div class="absolute inset-0 flex flex-col items-center justify-center border border-dashed border-slate-200 dark:border-white/10 rounded-xl bg-slate-50 dark:bg-white/[0.02] transition-colors">
                                            <p class="text-xs font-semibold text-slate-400 dark:text-white/40">Belum ada data nilai lab.</p>
                                        </div>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- =======================================================
                         D. SEMUA PERFORMA SISWA
                         ======================================================= --}}
                    <div id="lab-students" class="analytics-section glass-card analytics-panel quiz-student-analytics lab-quiz-student-analytics rounded-2xl overflow-hidden reveal border-t-2 border-amber-500/50 scroll-mt-28" style="animation-delay: 0.38s;">
                        <div class="p-5 md:p-6 border-b border-slate-200 dark:border-white/5 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 bg-slate-50/50 dark:bg-[#0a0e17]/30 transition-colors">
                            <div>
                                <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2 transition-colors">
                                    Performa pengguna
                                </h3>
                                <p class="text-[10px] text-slate-500 dark:text-white/40 mt-1 transition-colors">Rata-rata skor, ketuntasan, durasi, dan percobaan setiap pengguna.</p>
                            </div>
                            <span class="text-[10px] bg-white dark:bg-[#020617] px-3 py-1.5 rounded-lg text-slate-500 dark:text-white/50 border border-slate-200 dark:border-white/10 shadow-sm dark:shadow-inner transition-colors">{{ $userPerformance->count() }} pengguna</span>
                        </div>
                        
                        <div class="p-5 md:p-6">
                            <div class="max-h-[460px] overflow-x-auto overflow-y-auto custom-scrollbar rounded-2xl border border-slate-200 bg-white/70 dark:border-white/10 dark:bg-[#020617]/60">
                                <table class="w-full min-w-[1060px] text-left text-xs">
                                    <thead class="sticky top-0 z-10 bg-slate-50/95 text-[9px] font-black uppercase tracking-widest text-slate-400 backdrop-blur dark:bg-[#0a0e17]/95 dark:text-white/30">
                                        <tr>
                                            <th class="px-4 py-3">
                                                <span class="inline-flex items-center gap-1.5 whitespace-nowrap">pengguna / Kelas</span>
                                            </th>
                                            <th class="px-3 py-3 text-center">
                                                <span class="inline-flex items-center justify-center gap-1.5 whitespace-nowrap">Percobaan Lab</span>
                                            </th>
                                            <th class="px-3 py-3 text-center">
                                                <span class="inline-flex items-center justify-center gap-1.5 whitespace-nowrap">Skor Rata-rata</span>
                                            </th>
                                            <th class="px-3 py-3 text-center">
                                                <span class="inline-flex items-center justify-center gap-1.5 whitespace-nowrap">Rentang Skor</span>
                                            </th>
                                            <th class="px-3 py-3 text-center">
                                                <span class="inline-flex items-center justify-center gap-1.5 whitespace-nowrap">Kelulusan Lab</span>
                                            </th>
                                            <th class="px-3 py-3 text-center">
                                                <span class="inline-flex items-center justify-center gap-1.5 whitespace-nowrap">Durasi Rata-rata</span>
                                            </th>
                                            <th class="px-3 py-3">
                                                <span class="inline-flex items-center gap-1.5 whitespace-nowrap">Waktu </span>
                                            </th>
                                            <th class="px-4 py-3 text-right">
                                                <span class="inline-flex items-center justify-end gap-1.5 whitespace-nowrap">Tinjau</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                                        @forelse($userPerformance as $usr)
                                            @php
                                                $score = round((float) ($usr->average_score ?? 0), 1);
                                                $passed = (int) ($usr->passed_tries ?? 0);
                                                $failed = (int) ($usr->failed_tries ?? 0);
                                                $totalTries = (int) ($usr->total_tries ?? 0);
                                                $passPercent = $totalTries > 0 ? round(($passed / $totalTries) * 100) : 0;
                                                $avgTimeLabel = is_numeric($usr->avg_time ?? 0) ? gmdate('i:s', (int) ($usr->avg_time ?? 0)) : ($usr->avg_time ?? '00:00');
                                            @endphp
                                            <tr class="table-row">
                                                <td class="px-4 py-3">
                                                    <p class="max-w-[220px] truncate text-sm font-black text-slate-900 dark:text-white">{{ $usr->name ?? 'pengguna' }}</p>
                                                    <p class="mt-0.5 truncate text-[10px] font-semibold text-slate-500 dark:text-white/40">{{ $usr->class_group ?: 'Kelas belum diatur' }}</p>
                                                </td>
                                                <td class="px-3 py-3 text-center font-mono font-black text-adaptive">{{ number_format($totalTries) }}</td>
                                                <td class="px-3 py-3 text-center">
                                                    <span class="font-black {{ $score >= 70 ? 'text-emerald-600 dark:text-emerald-300' : 'text-rose-600 dark:text-rose-300' }}">{{ $score }}</span>
                                                </td>
                                                <td class="px-3 py-3 text-center font-mono text-adaptive-muted">{{ $usr->lowest_score ?? 0 }}-{{ $usr->best_score ?? 0 }}</td>
                                                <td class="px-3 py-3 text-center">
                                                    <span class="font-mono font-black text-cyan-700 dark:text-cyan-300">{{ $passed }}/{{ $totalTries }}</span>
                                                    <span class="ml-1 text-[10px] font-bold text-adaptive-muted">({{ $passPercent }}%)</span>
                                                    @if($failed > 0)
                                                        <span class="ml-1 rounded-md bg-rose-50 px-1.5 py-0.5 text-[9px] font-black text-rose-700 dark:bg-rose-500/10 dark:text-rose-300">{{ $failed }} belum</span>
                                                    @endif
                                                </td>
                                                <td class="px-3 py-3 text-center font-mono text-adaptive-muted">{{ $avgTimeLabel }}</td>
                                                <td class="px-3 py-3 text-[10px] font-semibold text-adaptive-muted">{{ isset($usr->last_attempt) ? \Carbon\Carbon::parse($usr->last_attempt)->diffForHumans() : '-' }}</td>
                                                <td class="px-4 py-3 text-right">
                                                    <a href="{{ route('admin.student.analytics', $usr->student_id ?? 1) }}" class="inline-flex items-center justify-center rounded-lg bg-indigo-50 px-3 py-2 text-[10px] font-black text-indigo-700 transition hover:bg-indigo-600 hover:text-white dark:bg-indigo-500/10 dark:text-indigo-300 dark:hover:bg-indigo-600 dark:hover:text-white">
                                                        Tinjau
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="px-4 py-10 text-center text-xs font-semibold text-slate-500 dark:text-white/40">
                                                    Belum ada data riwayat pengerjaan pengguna.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </main>
    </div>

    {{-- MODAL PANDUAN DASBOR ADMIN (HERO MODAL POPUP) --}}
    <div x-show="showDashboardInfoModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4 sm:p-6" x-cloak style="display: none;">
        <div class="absolute inset-0 bg-slate-900/60 dark:bg-[#020617]/80 backdrop-blur-md cursor-pointer transition-opacity" @click="showDashboardInfoModal = false" x-transition.opacity></div>
        
        <div class="relative max-h-[92vh] w-full max-w-6xl overflow-y-auto bg-white/95 dark:bg-[#0f141e]/95 backdrop-blur-xl border border-slate-200 dark:border-white/10 rounded-[2rem] p-6 md:p-8 shadow-2xl transition-all custom-scrollbar" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-95 translate-y-4">
            
            <button @click="showDashboardInfoModal = false" class="absolute top-5 right-5 p-2 rounded-full hover:bg-slate-100 dark:hover:bg-white/5 text-slate-400 hover:text-slate-600 dark:hover:text-white transition-all focus:outline-none z-10">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            @php
                $guideTitle = 'Panduan Analitik Lab';
                $guideSubtitle = 'Membaca ringkasan praktik dan perbandingan kelas.';
                $guideImage = 'images/guides/current-admin-lab-analytics.png';
                $guideIntro = 'Gunakan nomor pada gambar untuk mengenali area ringkasan, grafik, dan tombol tampilan sebelum membaca performa lab lebih rinci.';
                $guidePoints = [
                    ['x' => 52, 'y' => 28, 'title' => 'Ringkasan lab', 'description' => 'Baca total percobaan, rasio lulus, rata-rata skor, dan durasi sebagai gambaran awal kondisi praktik.'],
                    ['x' => 85, 'y' => 28, 'title' => 'Filter dan pilihan data', 'description' => 'Gunakan filter saat ingin melihat kelas atau modul tertentu tanpa mencampur semua data.'],
                    ['x' => 48, 'y' => 58, 'title' => 'Grafik perkembangan', 'description' => 'Grafik membantu melihat apakah capaian lab naik, turun, atau stabil dari waktu ke waktu.'],
                    ['x' => 86, 'y' => 52, 'title' => 'Mode tampilan', 'description' => 'Ganti bentuk grafik saat pola lebih mudah dibaca dalam garis atau batang.'],
                ];
            @endphp
            @include('admin.partials.analytics_guide_mockup')

            <div class="mt-8 pt-6 border-t border-slate-200 dark:border-white/5">
                <button @click="showDashboardInfoModal = false" class="w-full py-3 bg-slate-900 hover:bg-slate-800 dark:bg-white dark:hover:bg-slate-200 text-white dark:text-slate-900 font-bold text-sm rounded-xl transition-colors shadow-md focus:outline-none">
                    Mengerti, Tutup Panduan
                </button>
            </div>
        </div>
    </div>

    {{-- ==================== HERO MODALS (INSIGHT DATA PER CARD) ==================== --}}

    {{-- Modal: Daftar Kelas --}}
    <div x-show="showClassListModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4 sm:p-6" x-cloak style="display: none;" role="dialog" aria-modal="true" aria-labelledby="class-list-modal-title">
        <div class="absolute inset-0 bg-slate-900/80 dark:bg-[#020617]/90 backdrop-blur-md transition-opacity" @click="showClassListModal = false" x-transition.opacity></div>
        <div class="relative flex max-h-[88vh] w-full max-w-5xl flex-col overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-2xl dark:border-white/10 dark:bg-[#0f141e]" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-6 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
            <div class="flex items-start justify-between gap-4 border-b border-slate-200 px-6 py-5 dark:border-white/5 sm:px-8">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-[.2em] text-cyan-600 dark:text-cyan-300">Analitik Kelas</p>
                    <h3 id="class-list-modal-title" class="mt-1 text-xl font-black text-slate-900 dark:text-white">Seluruh Kinerja Praktik Kelas</h3>
                    <p class="mt-1 text-xs font-semibold text-slate-500 dark:text-white/45">Kelulusan, partisipasi, skor rata-rata, dan frekuensi percobaan.</p>
                </div>
                <button @click="showClassListModal = false" aria-label="Tutup daftar kelas" class="rounded-full p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700 dark:hover:bg-white/5 dark:hover:text-white">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="custom-scrollbar overflow-y-auto p-4 sm:p-6">
                <div class="grid gap-3">
                    @forelse($classPerformance as $classRow)
                        @php
                            $classAttempts = max(0, (int) ($classRow->total_attempts ?? 0));
                            $classLulus = max(0, (int) ($classRow->passed_attempts ?? 0));
                            $classFailed = max(0, (int) ($classRow->failed_attempts ?? 0));
                            $classRate = $classAttempts > 0 ? round(($classLulus / $classAttempts) * 100, 1) : 0;
                            $classAvg = round((float) ($classRow->avg_score ?? 0), 1);
                            $classStudentsWithAttempts = max(0, (int) ($classRow->students_count ?? 0));
                            $classEnrolled = max(0, (int) ($classRow->enrolled_students ?? $classStudentsWithAttempts));
                            $classCoverage = $classEnrolled > 0 ? min(100, round(($classStudentsWithAttempts / $classEnrolled) * 100, 1)) : 0;
                            $classAttemptsPerStudent = $classStudentsWithAttempts > 0 ? round($classAttempts / $classStudentsWithAttempts, 1) : 0;
                            $classInsightPayload = [
                                'name' => $classRow->class_group,
                                'major' => $classRow->major ?: 'Program belum diatur',
                                'token' => $classRow->token ?: '-',
                                'status' => $classRow->status_label ?? 'Aktif',
                                'students_count' => $classStudentsWithAttempts,
                                'enrolled_students' => $classEnrolled,
                                'total_attempts' => $classAttempts,
                                'passed_attempts' => $classLulus,
                                'failed_attempts' => $classFailed,
                                'pass_rate' => $classRate,
                                'avg_score' => $classAvg,
                                'avg_time' => $classRow->avg_time_label ?? '-',
                                'last_attempt' => $classRow->last_attempt_label ?? 'Belum ada aktivitas',
                                'attempts_per_student' => $classAttemptsPerStudent,
                                'coverage' => $classCoverage,
                            ];
                        @endphp
                        <button type="button" data-class-insight='@json($classInsightPayload, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_HEX_TAG)' @click="showClassListModal = false; openClassInsight(JSON.parse($event.currentTarget.dataset.classInsight))" @keydown.enter="showClassListModal = false; openClassInsight(JSON.parse($event.currentTarget.dataset.classInsight))" class="class-modal-row grid w-full gap-3 rounded-2xl border border-slate-200 bg-slate-50/70 p-4 text-left transition hover:border-cyan-300 hover:bg-white focus:outline-none focus:ring-2 focus:ring-cyan-400/40 dark:border-white/10 dark:bg-white/[0.03] dark:hover:border-cyan-500/30 dark:hover:bg-white/[0.05] md:grid-cols-[1.1fr_.9fr_.9fr_.9fr] md:items-center">
                            <div class="min-w-0">
                                <p class="truncate text-sm font-black text-slate-900 dark:text-white">{{ $classRow->class_group }}</p>
                                <p class="mt-1 truncate text-[10px] font-semibold text-slate-500 dark:text-white/40">{{ $classRow->major ?: 'Program belum diatur' }}</p>
                            </div>
                            <div><p class="class-analytics-label">Kelulusan</p><p class="mt-1 text-base font-black text-emerald-600 dark:text-emerald-300">{{ $classRate }}%</p><p class="mt-1 text-[10px] font-semibold text-slate-500 dark:text-white/40">{{ $classLulus }}/{{ $classAttempts }} lulus</p></div>
                            <div><p class="class-analytics-label">Partisipasi</p><p class="mt-1 text-base font-black text-cyan-700 dark:text-cyan-300">{{ $classCoverage }}%</p><p class="mt-1 text-[10px] font-semibold text-slate-500 dark:text-white/40">{{ $classStudentsWithAttempts }}/{{ $classEnrolled ?: $classStudentsWithAttempts }} pengguna</p></div>
                            <div><p class="class-analytics-label">Skor rata-rata</p><p class="mt-1 text-base font-black text-slate-900 dark:text-white">{{ $classAvg }}<span class="ml-1 text-[10px] text-slate-400">/100</span></p><p class="mt-1 text-[10px] font-semibold text-slate-500 dark:text-white/40">{{ $classAttemptsPerStudent }} percobaan/pengguna</p></div>
                        </button>
                    @empty
                        <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-8 text-center text-xs font-semibold text-slate-500 dark:border-white/10 dark:bg-white/[0.02] dark:text-white/40">Belum ada data kelas pada filter aktif.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Modal: Insight Per Kelas --}}
    {{-- MODAL INSIGHT HERO KELAS PRAKTIK --}}
    <div x-show="showClassInsightModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4 sm:p-6" x-cloak style="display: none;" role="dialog" aria-modal="true" aria-label="Insight kinerja praktik per kelas">
        <div class="absolute inset-0 bg-slate-900/80 backdrop-blur-md transition-opacity dark:bg-[#020617]/90" @click="showClassInsightModal = false" x-transition.opacity></div>

        <div class="quiz-class-hero-modal relative max-h-[92vh] w-full max-w-4xl overflow-y-auto custom-scrollbar" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-5 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 scale-100" x-transition:leave-end="opacity-0 translate-y-5 scale-95">
            <button @click="showClassInsightModal = false" aria-label="Tutup insight kelas" class="absolute right-5 top-5 z-10 rounded-full p-2 text-slate-400 transition hover:bg-white/70 hover:text-slate-700 dark:hover:bg-white/5 dark:hover:text-white">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <div class="quiz-class-hero-head px-5 py-6 sm:px-7 sm:py-7">
                <p class="quiz-class-hero-kicker">Insight Kinerja Kelas</p>
                <h3 class="mt-2 truncate pr-10 text-2xl font-black tracking-tight text-slate-900 dark:text-white" x-text="selectedClassInsight.name || 'Kelas'"></h3>
                <p class="mt-2 max-w-2xl text-xs font-semibold leading-5 text-slate-600 dark:text-slate-300">Ringkasan dihitung dari seluruh praktik lab yang selesai pada periode dan kelas yang sedang difilter.</p>
            </div>

            <div class="space-y-5 p-5 sm:p-7">
                <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="quiz-class-hero-metric">
                        <p>pengguna</p>
                        <strong class="text-cyan-700 dark:text-cyan-300" x-text="selectedClassInsight.students_count || 0"></strong>
                        <span x-text="'dari ' + (selectedClassInsight.enrolled_students || 0) + ' pengguna terdaftar'"></span>
                    </div>
                    <div class="quiz-class-hero-metric">
                        <p>Percobaan</p>
                        <strong x-text="selectedClassInsight.total_attempts || 0"></strong>
                        <span x-text="(selectedClassInsight.attempts_per_student || 0) + ' sesi per pengguna'"></span>
                    </div>
                    <div class="quiz-class-hero-metric">
                        <p>Skor rata-rata</p>
                        <strong class="text-indigo-700 dark:text-indigo-300" x-text="selectedClassInsight.avg_score || 0"></strong>
                        <span>nilai akhir seluruh sesi</span>
                    </div>
                    <div class="quiz-class-hero-metric">
                        <p>Durasi rata-rata</p>
                        <strong class="text-amber-700 dark:text-amber-300" x-text="selectedClassInsight.avg_time || '-'"></strong>
                        <span>waktu per sesi praktik</span>
                    </div>
                </div>

                <section class="rounded-2xl border border-slate-200 bg-slate-50/80 p-4 dark:border-white/10 dark:bg-white/[0.025]">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <p class="quiz-class-hero-section-label">Kelulusan Praktik</p>
                            <div class="mt-2 flex items-end gap-3">
                                <strong class="text-3xl font-black tracking-tight text-emerald-600 dark:text-emerald-300" x-text="(selectedClassInsight.pass_rate || 0) + '%'"></strong>
                                <span class="pb-1 text-xs font-semibold text-slate-500 dark:text-slate-400" x-text="(selectedClassInsight.passed_attempts || 0) + ' lulus dari ' + (selectedClassInsight.total_attempts || 0) + ' percobaan'"></span>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-2 text-[10px] font-black uppercase tracking-widest">
                            <span class="rounded-lg border border-emerald-200 bg-emerald-50 px-2.5 py-1.5 text-emerald-700 dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-300" x-text="(selectedClassInsight.passed_attempts || 0) + ' lulus'"></span>
                            <span class="rounded-lg border border-rose-200 bg-rose-50 px-2.5 py-1.5 text-rose-700 dark:border-rose-500/20 dark:bg-rose-500/10 dark:text-rose-300" x-text="(selectedClassInsight.failed_attempts || 0) + ' belum lulus'"></span>
                        </div>
                    </div>
                    <div class="quiz-class-hero-meter mt-4">
                        <span class="bg-emerald-500" :style="'width: ' + Math.min(selectedClassInsight.pass_rate || 0, 100) + '%'" aria-label="Proporsi lulus"></span>
                        <span class="bg-rose-400" :style="'width: ' + Math.max(0, 100 - Math.min(selectedClassInsight.pass_rate || 0, 100)) + '%'" aria-label="Proporsi belum lulus"></span>
                    </div>
                </section>

                <div class="rounded-xl border border-dashed border-slate-300 bg-white/60 px-4 py-3 text-[11px] font-semibold leading-5 text-slate-500 dark:border-white/10 dark:bg-black/10 dark:text-slate-400">
                    Basis data: jumlah pengguna dihitung dari pengguna dengan minimal satu praktik selesai; kelulusan, skor, dan durasi dihitung dari seluruh percobaan praktik pada kelas ini.
                </div>
            </div>

            <div class="flex justify-end border-t border-slate-200 px-5 py-4 dark:border-white/10 sm:px-7">
                <button type="button" @click="showClassInsightModal = false" class="text-sm font-bold text-slate-500 transition hover:text-slate-900 dark:text-white/50 dark:hover:text-white">Tutup</button>
            </div>
        </div>
    </div>

    <div x-show="showAttemptsModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-slate-900/80 dark:bg-[#020617]/95 backdrop-blur-md transition-colors" @click="showAttemptsModal = false"></div>
        <div class="relative w-full max-w-2xl bg-white dark:bg-[#0f141e] border border-indigo-200 dark:border-indigo-500/40 rounded-3xl shadow-2xl dark:shadow-[0_30px_100px_rgba(99,102,241,0.15)] p-6 md:p-8 transition-colors" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
            <div class="flex justify-between items-center mb-6 border-b border-slate-200 dark:border-white/5 pb-4 transition-colors">
                <div>
                    <h3 class="text-lg md:text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2 transition-colors">
                        <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                        Rincian Percobaan Lab
                    </h3>
                    <p class="text-[10px] text-indigo-600 dark:text-indigo-400 mt-1 font-mono uppercase tracking-widest transition-colors">Siswa paling aktif berdasarkan jumlah percobaan</p>
                </div>
                <button @click="showAttemptsModal = false" class="text-slate-400 hover:text-slate-900 dark:text-white/40 dark:hover:text-white transition-colors bg-slate-100 dark:bg-white/5 hover:bg-red-100 dark:hover:bg-red-500/20 p-2 rounded-full border border-transparent hover:border-red-200 dark:hover:border-red-500/30"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            <div class="max-h-[60vh] overflow-y-auto custom-scrollbar space-y-3 pr-2">
                @forelse($userPerformance->sortByDesc('total_tries')->take(10) as $usr)
                <div class="flex items-center justify-between gap-4 p-4 rounded-xl bg-slate-50 dark:bg-[#0a0e17]/80 border border-slate-200 dark:border-white/5 hover:border-indigo-300 dark:hover:border-indigo-500/30 transition-colors group shadow-sm dark:shadow-inner">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-slate-900 dark:text-white truncate group-hover:text-indigo-600 dark:group-hover:text-indigo-300 transition-colors">{{ $usr->name ?? 'Siswa' }}</p>
                        <p class="text-[10px] text-slate-500 dark:text-white/50 font-mono mt-1 transition-colors">{{ $usr->email ?? '-' }}</p>
                    </div>
                    <div class="text-right shrink-0">
                        <span class="text-lg font-black text-indigo-600 dark:text-indigo-400 transition-colors">{{ $usr->total_tries ?? 0 }}</span>
                        <p class="text-[9px] text-slate-500 dark:text-white/40 uppercase tracking-widest mt-0.5 transition-colors">Percobaan</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-10 bg-slate-50 dark:bg-[#0a0e17]/50 rounded-xl border border-dashed border-slate-300 dark:border-white/10 transition-colors">
                    <p class="text-[11px] text-slate-500 dark:text-white/40 italic transition-colors">Tidak ada data percobaan.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- 2. Modal: Rincian Rasio Kelulusan --}}
    <div x-show="showSuccessRateModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-slate-900/80 dark:bg-[#020617]/95 backdrop-blur-md transition-colors" @click="showSuccessRateModal = false"></div>
        <div class="relative w-full max-w-lg bg-white dark:bg-[#0f141e] border border-emerald-200 dark:border-emerald-500/40 rounded-3xl shadow-2xl dark:shadow-[0_30px_100px_rgba(16,185,129,0.15)] p-6 md:p-8 transition-colors" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
            <div class="flex justify-between items-center mb-6 border-b border-slate-200 dark:border-white/5 pb-4 transition-colors">
                <div>
                    <h3 class="text-lg md:text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2 transition-colors">
                        <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Rasio Kelulusan Keseluruhan
                    </h3>
                </div>
                <button @click="showSuccessRateModal = false" class="text-slate-400 hover:text-slate-900 dark:text-white/40 dark:hover:text-white transition-colors bg-slate-100 dark:bg-white/5 hover:bg-red-100 dark:hover:bg-red-500/20 p-2 rounded-full border border-transparent hover:border-red-200 dark:hover:border-red-500/30"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            <div class="text-center py-6">
                <div class="inline-block p-8 rounded-full transition-colors {{ $completionRate >= 70 ? 'bg-emerald-50 dark:bg-emerald-500/10 border-emerald-200 dark:border-emerald-500/20 shadow-[0_0_40px_rgba(16,185,129,0.15)] text-emerald-600 dark:text-emerald-400' : 'bg-red-50 dark:bg-red-500/10 border-red-200 dark:border-red-500/20 shadow-[0_0_40px_rgba(239,68,68,0.15)] text-red-600 dark:text-red-400' }} border mb-6">
                    <span class="text-6xl font-black">{{ $completionRate }}%</span>
                </div>
                
                <div class="flex justify-around items-center text-xs text-slate-600 dark:text-white/60 bg-slate-50 dark:bg-[#0a0e17] rounded-xl p-4 border border-slate-200 dark:border-white/5 mt-4 transition-colors">
                    <div>
                        <span class="block text-2xl font-black text-emerald-600 dark:text-emerald-400 mb-1 transition-colors">{{ $passedCount }}</span>
                        Lulus
                    </div>
                    <div class="w-px h-10 bg-slate-300 dark:bg-white/10 transition-colors"></div>
                    <div>
                        <span class="block text-2xl font-black text-red-600 dark:text-red-400 mb-1 transition-colors">{{ $failedCount }}</span>
                        Belum Lulus
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. Modal: Rincian Nilai Rata-rata --}}
    <div x-show="showAvgScoreModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-slate-900/80 dark:bg-[#020617]/95 backdrop-blur-md transition-colors" @click="showAvgScoreModal = false"></div>
        <div class="relative w-full max-w-2xl bg-white dark:bg-[#0f141e] border border-amber-200 dark:border-yellow-500/40 rounded-3xl shadow-2xl dark:shadow-[0_30px_100px_rgba(234,179,8,0.15)] p-6 md:p-8 transition-colors" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
            <div class="flex justify-between items-center mb-6 border-b border-slate-200 dark:border-white/5 pb-4 transition-colors">
                <div>
                    <h3 class="text-lg md:text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2 transition-colors">
                        <svg class="w-6 h-6 text-amber-500 dark:text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 001.902 0l1.519-4.674z"/></svg>
                        Rincian Skor Tertinggi
                    </h3>
                    <p class="text-[10px] text-amber-600 dark:text-yellow-400 mt-1 font-mono uppercase tracking-widest transition-colors">Siswa dengan capaian nilai tertinggi</p>
                </div>
                <button @click="showAvgScoreModal = false" class="text-slate-400 hover:text-slate-900 dark:text-white/40 dark:hover:text-white transition-colors bg-slate-100 dark:bg-white/5 hover:bg-red-100 dark:hover:bg-red-500/20 p-2 rounded-full border border-transparent hover:border-red-200 dark:hover:border-red-500/30"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            <div class="max-h-[60vh] overflow-y-auto custom-scrollbar space-y-3 pr-2">
                @forelse($userPerformance->sortByDesc('best_score')->take(10) as $usr)
                <div class="flex items-center justify-between gap-4 p-4 rounded-xl bg-slate-50 dark:bg-[#0a0e17]/80 border border-slate-200 dark:border-white/5 hover:border-amber-300 dark:hover:border-yellow-500/30 transition-colors group shadow-sm dark:shadow-inner">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-slate-900 dark:text-white truncate group-hover:text-amber-600 dark:group-hover:text-yellow-300 transition-colors">{{ $usr->name ?? 'Siswa' }}</p>
                        <p class="text-[10px] text-slate-500 dark:text-white/50 font-mono mt-1 transition-colors">{{ $usr->email ?? '-' }}</p>
                    </div>
                    <div class="text-right shrink-0">
                        <span class="text-lg font-black transition-colors {{ ($usr->best_score ?? 0) >= 80 ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-yellow-400' }}">{{ $usr->best_score ?? 0 }}</span>
                        <p class="text-[9px] text-slate-500 dark:text-white/40 uppercase tracking-widest mt-0.5 transition-colors">Skor Terbaik</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-10 bg-slate-50 dark:bg-[#0a0e17]/50 rounded-xl border border-dashed border-slate-300 dark:border-white/10 transition-colors">
                    <p class="text-[11px] text-slate-500 dark:text-white/40 italic transition-colors">Tidak ada data nilai.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- 4. Modal: Rincian Durasi --}}
    <div x-show="showDurationModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-slate-900/80 dark:bg-[#020617]/95 backdrop-blur-md transition-colors" @click="showDurationModal = false"></div>
        <div class="relative w-full max-w-2xl bg-white dark:bg-[#0f141e] border border-cyan-200 dark:border-cyan-500/40 rounded-3xl shadow-2xl dark:shadow-[0_30px_100px_rgba(6,182,212,0.15)] p-6 md:p-8 transition-colors" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
            <div class="flex justify-between items-center mb-6 border-b border-slate-200 dark:border-white/5 pb-4 transition-colors">
                <div>
                    <h3 class="text-lg md:text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2 transition-colors">
                        <svg class="w-6 h-6 text-cyan-600 dark:text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Rata-rata Durasi
                    </h3>
                    <p class="text-[10px] text-cyan-600 dark:text-cyan-400 mt-1 font-mono uppercase tracking-widest transition-colors">Rincian durasi per sesi</p>
                </div>
                <button @click="showDurationModal = false" class="text-slate-400 hover:text-slate-900 dark:text-white/40 dark:hover:text-white transition-colors bg-slate-100 dark:bg-white/5 hover:bg-red-100 dark:hover:bg-red-500/20 p-2 rounded-full border border-transparent hover:border-red-200 dark:hover:border-red-500/30"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            <div class="max-h-[60vh] overflow-y-auto custom-scrollbar space-y-3 pr-2">
                @forelse($userPerformance->sortBy('avg_time')->take(10) as $usr)
                <div class="flex items-center justify-between gap-4 p-4 rounded-xl bg-slate-50 dark:bg-[#0a0e17]/80 border border-slate-200 dark:border-white/5 hover:border-cyan-300 dark:hover:border-cyan-500/30 transition-colors group shadow-sm dark:shadow-inner">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-slate-900 dark:text-white truncate group-hover:text-cyan-600 dark:group-hover:text-cyan-300 transition-colors">{{ $usr->name ?? 'Siswa' }}</p>
                    </div>
                    <div class="text-right shrink-0">
                        <span class="text-lg font-black text-cyan-600 dark:text-cyan-400 font-mono transition-colors">{{ is_numeric($usr->avg_time ?? 0) ? gmdate("i:s", $usr->avg_time) : ($usr->avg_time ?? '00:00') }}</span>
                        <p class="text-[9px] text-slate-500 dark:text-white/40 uppercase tracking-widest mt-0.5 transition-colors">Rata-rata Waktu</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-10 bg-slate-50 dark:bg-[#0a0e17]/50 rounded-xl border border-dashed border-slate-300 dark:border-white/10 transition-colors">
                    <p class="text-[11px] text-slate-500 dark:text-white/40 italic transition-colors">Tidak ada data durasi.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- CHART SCRIPTS & THEME LOGIC --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // ==========================================
            // THEME SWITCHER LOGIC
            // ==========================================
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

            // ==========================================
            // MAIN PERFORMANCE CHART
            // ==========================================
            const performanceCanvas = document.getElementById('mainPerformanceChart');
            let mainPerformanceChart = null;
            let activeChartType = 'line';

            function createGradient(ctx) {
                const gradient = ctx.createLinearGradient(0, 0, 0, 330);
                gradient.addColorStop(0, 'rgba(59, 130, 246, 0.32)');
                gradient.addColorStop(0.55, 'rgba(99, 102, 241, 0.11)');
                gradient.addColorStop(1, 'rgba(59, 130, 246, 0)');
                return gradient;
            }

            function initCharts() {
                if (!performanceCanvas) return;

                const isDark = document.documentElement.classList.contains('dark');
                const gridColor = getComputedStyle(document.documentElement).getPropertyValue('--chart-grid').trim();
                const tickColor = getComputedStyle(document.documentElement).getPropertyValue('--chart-tick').trim();
                const ctx = performanceCanvas.getContext('2d');

                if (mainPerformanceChart) mainPerformanceChart.destroy();

                const isLine = activeChartType === 'line';
                const labGradient = createGradient(ctx);

                mainPerformanceChart = new Chart(ctx, {
                    type: activeChartType,
                    data: {
                        labels: {!! json_encode($labChartLabels->values()) !!},
                        datasets: [
                            {
                                label: 'Nilai Lab',
                                data: {!! json_encode($labChartScores->values()) !!},
                                borderColor: '#3b82f6',
                                backgroundColor: isLine ? labGradient : 'rgba(59, 130, 246, 0.72)',
                                borderWidth: isLine ? 3 : 0,
                                borderRadius: isLine ? 0 : 8,
                                pointBackgroundColor: isDark ? '#0f141e' : '#ffffff',
                                pointBorderColor: '#3b82f6',
                                pointBorderWidth: 2,
                                pointRadius: isLine ? 4 : 0,
                                pointHoverRadius: 7,
                                fill: isLine,
                                tension: 0.42,
                                spanGaps: false
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: { mode: 'index', intersect: false },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: isDark ? 'rgba(15, 20, 30, 0.96)' : 'rgba(255, 255, 255, 0.96)',
                                titleColor: isDark ? '#ffffff' : '#0f172a',
                                bodyColor: isDark ? '#cbd5e1' : '#475569',
                                borderColor: gridColor,
                                borderWidth: 1,
                                padding: 12,
                                displayColors: true,
                                usePointStyle: true,
                                titleFont: { family: 'Inter', size: 12, weight: '800' },
                                bodyFont: { family: 'Inter', size: 11, weight: '600' },
                                callbacks: {
                                    label: function(context) {
                                        if (context.raw === null || context.raw === undefined) {
                                            return context.dataset.label + ': belum ada data';
                                        }
                                        return context.dataset.label + ': ' + context.raw + ' poin';
                                    },
                                    footer: function(items) {
                                        const index = items?.[0]?.dataIndex ?? 0;
                                        const participants = {!! json_encode($labChartParticipants->values()) !!}[index] ?? 0;
                                        return 'Siswa dihitung: ' + participants;
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: { display: false },
                                ticks: { color: tickColor, font: { size: 10, family: 'JetBrains Mono', weight: '600' } }
                            },
                            y: {
                                beginAtZero: true,
                                max: 100,
                                grid: { color: gridColor, drawBorder: false },
                                ticks: {
                                    color: tickColor,
                                    stepSize: 20,
                                    font: { size: 10, family: 'JetBrains Mono', weight: '600' },
                                    callback: function(value) { return value + ' pts'; }
                                }
                            }
                        }
                    }
                });
            }

            window.updateMainPerformanceChartType = function(type) {
                activeChartType = type;
                initCharts();
            };

            initCharts();

            // Re-render chart colors when theme is toggled
            window.addEventListener('theme-toggled', () => {
                initCharts();
            });
        });
    </script>



    <div id="analyticsTooltipPortal" role="tooltip" aria-hidden="true"></div>
    <script>
        (function () {
            const portal = document.getElementById('analyticsTooltipPortal');
            const triggers = Array.from(document.querySelectorAll('[data-analytics-tooltip]'));
            if (!portal || !triggers.length) return;

            let activeTrigger = null;
            let hideTimer = null;

            const hide = () => {
                window.clearTimeout(hideTimer);
                portal.classList.remove('is-visible');
                portal.setAttribute('aria-hidden', 'true');
                activeTrigger = null;
            };

            const position = () => {
                if (!activeTrigger || !portal.classList.contains('is-visible')) return;
                const rect = activeTrigger.getBoundingClientRect();
                const gap = 10;
                const viewportPadding = 12;
                const portalRect = portal.getBoundingClientRect();
                let top = rect.bottom + gap;
                let left = rect.left + (rect.width / 2) - (portalRect.width / 2);

                if (top + portalRect.height > window.innerHeight - viewportPadding) {
                    top = rect.top - portalRect.height - gap;
                }

                top = Math.max(viewportPadding, top);
                left = Math.max(viewportPadding, Math.min(left, window.innerWidth - portalRect.width - viewportPadding));
                portal.style.top = `${top}px`;
                portal.style.left = `${left}px`;
            };

            const show = (trigger) => {
                window.clearTimeout(hideTimer);
                const message = trigger.getAttribute('data-analytics-tooltip');
                if (!message) return;
                activeTrigger = trigger;
                portal.textContent = message;
                portal.classList.add('is-visible');
                portal.setAttribute('aria-hidden', 'false');
                requestAnimationFrame(position);
            };

            triggers.forEach((trigger) => {
                trigger.addEventListener('mouseenter', () => show(trigger));
                trigger.addEventListener('mouseleave', () => {
                    hideTimer = window.setTimeout(hide, 70);
                });
                trigger.addEventListener('focus', () => show(trigger));
                trigger.addEventListener('blur', () => {
                    hideTimer = window.setTimeout(hide, 70);
                });
                trigger.addEventListener('click', (event) => {
                    event.preventDefault();
                    event.stopPropagation();
                    if (activeTrigger === trigger && portal.classList.contains('is-visible')) {
                        hide();
                    } else {
                        show(trigger);
                    }
                });
            });

            document.addEventListener('pointerdown', (event) => {
                if (activeTrigger && !event.target.closest('[data-analytics-tooltip]')) hide();
            });
            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') hide();
            });
            window.addEventListener('scroll', hide, true);
            window.addEventListener('resize', position);
        })();
    </script>

</body>
</html>
