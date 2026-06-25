<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Analitik Lab</title>
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
        
        .card-bg-gfx { position: absolute; inset: 0; overflow: hidden; border-radius: 1rem; pointer-events: none; z-index: 0; }

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
        $performanceBest = $userPerformance->sortByDesc('best_score')->values();
        $performanceWorst = $userPerformance->sortBy('best_score')->values();
        $labsList = isset($labsList) ? collect($labsList) : collect([]);
        $classGroups = isset($classGroups) ? collect($classGroups) : collect([]);
        $classPerformance = isset($classPerformance) ? collect($classPerformance) : collect([]);
        $selectedClass = $selectedClass ?? request('class_group');
        $analyticsRouteParams = !empty($labId) ? ['labId' => $labId] : [];
        
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
        <main class="flex-1 flex flex-col relative z-10 transition-colors duration-300 h-full overflow-y-auto overflow-x-hidden">
            
            {{-- Background FX --}}
            <div class="fixed inset-0 pointer-events-none z-0">
                <div class="absolute top-[10%] left-[20%] w-[500px] h-[500px] bg-cyan-400/20 dark:bg-cyan-600/10 rounded-full blur-[120px] transition-colors"></div>
                <div class="absolute bottom-[10%] right-[10%] w-[400px] h-[400px] bg-indigo-400/20 dark:bg-indigo-600/10 rounded-full blur-[120px] transition-colors"></div>
                <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.02] dark:opacity-[0.04] mix-blend-overlay"></div>
            </div>

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
                                                <span class="text-slate-900 dark:text-white transition-colors">Analitik Lab</span>
                                            </div>
                                        </li>
                                    </ol>
                                </nav>
                                <div class="flex items-center gap-2">
                                    <h2 class="text-slate-900 dark:text-white font-bold text-lg md:text-xl tracking-tight transition-colors">Analitik Lab</h2>
                                    
                                    {{-- TOMBOL TRIGGER HERO MODAL PANDUAN --}}
                                    <button @click="showDashboardInfoModal = true" class="w-6 h-6 md:w-7 md:h-7 rounded-full border border-slate-200 dark:border-white/10 flex items-center justify-center text-[10px] md:text-xs font-black text-slate-400 dark:text-slate-500 hover:text-indigo-600 dark:hover:text-indigo-400 bg-white/50 dark:bg-white/5 backdrop-blur-sm hover:bg-white dark:hover:bg-white/10 hover:border-indigo-200 dark:hover:border-indigo-500/30 transition-all duration-300 shadow-sm hover:shadow-md focus:outline-none mt-0.5" title="Panduan Analitik Lab">
                                        ?
                                    </button>
                                </div>
                                <p class="text-[9px] md:text-xs text-slate-500 dark:text-white/40 flex items-center gap-1.5 mt-0.5 transition-colors">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse shadow-[0_0_8px_#10b981]"></span>
                                    Ringkasan lab, kelas, dan performa siswa
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
            <div class="flex-1 p-6 md:p-10 relative z-10">
                <div class="max-w-7xl mx-auto space-y-8 md:space-y-12">

                    {{-- =======================================================
                         A. STATS GRID DENGAN HERO MODAL TRIGGERS
                         ======================================================= --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 reveal" style="animation-delay: 0.1s;">
                        
                        {{-- 1. Percobaan --}}
                        <div class="glass-card rounded-2xl p-5 border-l-4 border-l-indigo-500 cursor-pointer group transition-all" @click="showAttemptsModal = true">
                            <div class="flex justify-between items-start">
                                <p class="text-[10px] font-bold text-slate-500 dark:text-white/40 uppercase tracking-widest group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">Total Percobaan</p>
                                <div class="tooltip-container tooltip-indigo tooltip-down tooltip-left">
                                    <div class="tooltip-trigger">?</div>
                                    <div class="tooltip-content">
                                        <span class="block font-bold text-indigo-600 dark:text-indigo-400 mb-1 border-b border-slate-200 dark:border-white/10 pb-1">Percobaan Lab</span>
                                        Total akumulasi seluruh percobaan lab yang dilakukan siswa.
                                    </div>
                                </div>
                            </div>
                            <h3 class="text-2xl md:text-3xl font-black text-slate-900 dark:text-white mt-2 transition-colors">{{ number_format($totalAttempts) }}</h3>
                            <div class="mt-3 flex gap-2">
                                <span class="bg-emerald-100 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 px-2 py-0.5 rounded text-[10px] font-bold border border-emerald-200 dark:border-emerald-500/10 transition-colors">{{ $passedCount }} Lulus</span>
                                <span class="bg-red-100 dark:bg-red-500/10 text-red-700 dark:text-red-400 px-2 py-0.5 rounded text-[10px] font-bold border border-red-200 dark:border-red-500/10 transition-colors">{{ $failedCount }} Belum Lulus</span>
                            </div>
                        </div>

                        {{-- 2. Rasio Kelulusan --}}
                        <div class="glass-card rounded-2xl p-5 border-l-4 border-l-emerald-500 cursor-pointer group transition-all" @click="showSuccessRateModal = true">
                            <div class="flex justify-between items-start">
                                <p class="text-[10px] font-bold text-slate-500 dark:text-white/40 uppercase tracking-widest group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">Rasio Kelulusan</p>
                                <div class="tooltip-container tooltip-emerald tooltip-down tooltip-left">
                                    <div class="tooltip-trigger">?</div>
                                    <div class="tooltip-content">
                                        <span class="block font-bold text-emerald-600 dark:text-emerald-400 mb-1 border-b border-slate-200 dark:border-white/10 pb-1">Rasio Kelulusan</span>
                                        Persentase modul praktikum yang diselesaikan dengan status lulus.
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-baseline gap-1 mt-2">
                                <h3 class="text-2xl md:text-3xl font-black transition-colors {{ $completionRate >= 70 ? 'text-emerald-600 dark:text-emerald-400' : ($completionRate >= 50 ? 'text-amber-600 dark:text-yellow-400' : 'text-red-600 dark:text-red-400') }}">
                                    {{ $completionRate }}
                                </h3>
                                <span class="text-lg font-bold transition-colors {{ $completionRate >= 70 ? 'text-emerald-600 dark:text-emerald-400' : ($completionRate >= 50 ? 'text-amber-600 dark:text-yellow-400' : 'text-red-600 dark:text-red-400') }}">%</span>
                            </div>
                            <div class="w-full bg-slate-200 dark:bg-white/10 h-1.5 mt-3 rounded-full overflow-hidden border border-slate-300 dark:border-white/5 transition-colors">
                                <div class="h-full {{ $completionRate >= 70 ? 'bg-emerald-500' : 'bg-red-500' }}" style="width: {{ $completionRate }}%"></div>
                            </div>
                        </div>

                        {{-- 3. Rata-rata Nilai --}}
                        <div class="glass-card rounded-2xl p-5 border-l-4 border-l-amber-500 cursor-pointer group transition-all" @click="showAvgScoreModal = true">
                            <div class="flex justify-between items-start">
                                <p class="text-[10px] font-bold text-slate-500 dark:text-white/40 uppercase tracking-widest group-hover:text-amber-600 dark:group-hover:text-yellow-400 transition-colors">Rata-rata Nilai</p>
                                <div class="tooltip-container tooltip-yellow tooltip-down tooltip-left">
                                    <div class="tooltip-trigger">?</div>
                                    <div class="tooltip-content">
                                        <span class="block font-bold text-amber-600 dark:text-yellow-400 mb-1 border-b border-slate-200 dark:border-white/10 pb-1">Rata-rata Keseluruhan</span>
                                        Rata-rata nilai yang didapatkan dari seluruh pengerjaan modul lab.
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-baseline gap-1 mt-2">
                                <h3 class="text-2xl md:text-3xl font-black text-slate-900 dark:text-white transition-colors">{{ $avgScore }}</h3>
                                <span class="text-[10px] text-amber-500 font-bold"></span>
                            </div>
                            <p class="text-[9px] text-amber-600 dark:text-yellow-400 mt-2 opacity-0 group-hover:opacity-100 transition-all translate-y-2 group-hover:translate-y-0">Buka daftar nilai &rarr;</p>
                        </div>

                        {{-- 4. Avg Time --}}
                        <div class="glass-card rounded-2xl p-5 border-l-4 border-l-cyan-500 cursor-pointer group transition-all" @click="showDurationModal = true">
                            <div class="flex justify-between items-start">
                                <p class="text-[10px] font-bold text-slate-500 dark:text-white/40 uppercase tracking-widest group-hover:text-cyan-600 dark:group-hover:text-cyan-400 transition-colors">Rata-rata Durasi</p>
                                <div class="tooltip-container tooltip-cyan tooltip-down tooltip-left">
                                    <div class="tooltip-trigger">?</div>
                                    <div class="tooltip-content">
                                        <span class="block font-bold text-cyan-600 dark:text-cyan-400 mb-1 border-b border-slate-200 dark:border-white/10 pb-1">Durasi per Sesi</span>
                                        Rata-rata waktu yang dihabiskan siswa per percobaan modul lab.
                                    </div>
                                </div>
                            </div>
                            <h3 class="text-2xl md:text-3xl font-black text-cyan-600 dark:text-cyan-400 mt-2 font-mono tracking-tight transition-colors">{{ $avgDuration }}</h3>
                            <p class="text-[9px] text-cyan-600 dark:text-cyan-400 mt-2 opacity-0 group-hover:opacity-100 transition-all translate-y-2 group-hover:translate-y-0">Buka riwayat durasi &rarr;</p>
                        </div>
                    </div>

                    {{-- =======================================================
                         B. RINGKASAN PER KELAS
                         ======================================================= --}}
                    <div class="reveal" style="animation-delay: 0.18s;">
                        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4 mb-4">
                            <div>
                                <p class="text-[10px] font-extrabold uppercase tracking-[0.2em] text-cyan-600 dark:text-cyan-400 mb-1">
                                    Navigasi Kelas
                                </p>
                                <h3 class="text-xl md:text-2xl font-black text-slate-900 dark:text-white transition-colors">
                                    Ringkasan Kelas
                                </h3>
                                <p class="text-xs text-slate-500 dark:text-white/40 mt-1 max-w-2xl">
                                    Pilih kelas dari dropdown untuk memfilter halaman. Klik kartu kelas untuk melihat ringkasan.
                                </p>
                            </div>

                            <form method="GET" action="{{ route('admin.lab.analytics', $analyticsRouteParams) }}" class="flex flex-col sm:flex-row gap-2 sm:items-center" aria-label="Filter kelas lab">
                                <select name="class_group" onchange="this.form.submit()" class="glass-input rounded-xl px-4 py-2.5 text-xs font-bold min-w-[220px]">
                                    <option value="">Semua kelas</option>
                                    @foreach($classGroups as $className)
                                        <option value="{{ $className }}" @selected($selectedClass === $className)>{{ $className }}</option>
                                    @endforeach
                                </select>
                                <noscript>
                                    <button type="submit" class="px-3 py-2 text-[10px] font-black uppercase tracking-widest text-indigo-700 dark:text-indigo-300">
                                        Filter
                                    </button>
                                </noscript>
                                @if($selectedClass)
                                    <a href="{{ route('admin.lab.analytics', $analyticsRouteParams) }}" class="px-2 py-2 text-[10px] font-black uppercase tracking-widest text-slate-500 transition hover:text-indigo-600 dark:text-white/45 dark:hover:text-indigo-300">
                                        Reset
                                    </a>
                                @endif
                                @if($classPerformance->count() > 3)
                                    <button type="button" @click="showClassListModal = true" class="px-2 py-2 text-[10px] font-black uppercase tracking-widest text-cyan-700 transition hover:text-cyan-500 dark:text-cyan-300 dark:hover:text-cyan-200">
                                        Lihat semua
                                    </button>
                                @endif
                            </form>
                        </div>

                        @php
                            $classPreviewRows = $classPerformance->take(3);
                            $hiddenClassCount = max(0, $classPerformance->count() - $classPreviewRows->count());
                        @endphp

                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                            @forelse($classPreviewRows as $classRow)
                                @php
                                    $classAttempts = (int) ($classRow->total_attempts ?? 0);
                                    $classLulus = (int) ($classRow->passed_attempts ?? 0);
                                    $classFailed = (int) ($classRow->failed_attempts ?? 0);
                                    $classRate = $classRow->pass_rate ?? ($classAttempts > 0 ? round(($classLulus / $classAttempts) * 100, 1) : 0);
                                    $classAvg = round((float) ($classRow->avg_score ?? 0), 1);
                                    $classQueryUrl = route('admin.lab.analytics', $analyticsRouteParams) . '?class_group=' . urlencode($classRow->class_group);
                                    $classInsightPayload = [
                                        'name' => $classRow->class_group,
                                        'major' => $classRow->major ?: 'Program belum diatur',
                                        'token' => $classRow->token ?: '-',
                                        'status' => $classRow->status_label ?? 'Aktif',
                                        'students_count' => (int) ($classRow->students_count ?? 0),
                                        'enrolled_students' => (int) ($classRow->enrolled_students ?? $classRow->students_count ?? 0),
                                        'total_attempts' => $classAttempts,
                                        'passed_attempts' => $classLulus,
                                        'failed_attempts' => $classFailed,
                                        'pass_rate' => $classRate,
                                        'avg_score' => $classAvg,
                                        'avg_time' => $classRow->avg_time_label ?? '-',
                                        'last_attempt' => $classRow->last_attempt_label ?? 'Belum ada aktivitas',
                                        'url' => $classQueryUrl,
                                        'note' => $classAvg >= 70
                                            ? 'Kinerja kelas sudah berada pada jalur baik.'
                                            : 'Kelas perlu ditinjau pada percobaan yang belum lulus.',
                                    ];
                                @endphp
                                <article
                                    role="button"
                                    tabindex="0"
                                    data-class-insight='@json($classInsightPayload, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_HEX_TAG)'
                                    @click="openClassInsight(JSON.parse($event.currentTarget.dataset.classInsight))"
                                    @keydown.enter="openClassInsight(JSON.parse($event.currentTarget.dataset.classInsight))"
                                    @keydown.space.prevent="openClassInsight(JSON.parse($event.currentTarget.dataset.classInsight))"
                                    class="glass-card rounded-2xl p-5 border-l-4 {{ $classAvg >= 70 ? 'border-l-emerald-500' : 'border-l-rose-500' }} group cursor-pointer transition-all focus:outline-none focus:ring-2 focus:ring-indigo-400/40">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="min-w-0">
                                            <p class="text-[10px] uppercase tracking-widest font-black text-slate-400 dark:text-white/30">Kelas</p>
                                            <h4 class="text-lg font-black text-slate-900 dark:text-white truncate mt-1">{{ $classRow->class_group }}</h4>
                                            <p class="mt-1 text-[10px] font-bold text-slate-500 dark:text-white/40 truncate">{{ $classRow->major ?: 'Program belum diatur' }}</p>
                                        </div>
                                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-black border {{ $classAvg >= 70 ? 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border-emerald-200 dark:border-emerald-500/20' : 'bg-rose-50 dark:bg-rose-500/10 text-rose-700 dark:text-rose-400 border-rose-200 dark:border-rose-500/20' }}">
                                            {{ $classRate }}%
                                        </span>
                                    </div>
                                    <div class="grid grid-cols-3 gap-2 mt-4">
                                        <div class="rounded-xl bg-white/70 dark:bg-[#020617]/70 border border-slate-200 dark:border-white/5 p-3">
                                            <p class="text-[9px] uppercase tracking-widest text-slate-400 dark:text-white/30 font-bold">Siswa</p>
                                            <p class="text-lg font-black text-slate-900 dark:text-white">{{ $classRow->students_count ?? 0 }}</p>
                                        </div>
                                        <div class="rounded-xl bg-white/70 dark:bg-[#020617]/70 border border-slate-200 dark:border-white/5 p-3">
                                            <p class="text-[9px] uppercase tracking-widest text-slate-400 dark:text-white/30 font-bold">Percobaan</p>
                                            <p class="text-lg font-black text-indigo-600 dark:text-indigo-400">{{ $classAttempts }}</p>
                                        </div>
                                        <div class="rounded-xl bg-white/70 dark:bg-[#020617]/70 border border-slate-200 dark:border-white/5 p-3">
                                            <p class="text-[9px] uppercase tracking-widest text-slate-400 dark:text-white/30 font-bold">Rata-rata</p>
                                            <p class="text-lg font-black {{ $classAvg >= 70 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">{{ $classAvg }}</p>
                                        </div>
                                    </div>
                                    <div class="mt-4 flex items-center justify-between text-[10px] text-slate-500 dark:text-white/40">
                                        <span>{{ $classLulus }} lulus / {{ $classFailed }} belum</span>
                                        <span class="font-mono">Token: {{ $classRow->token ?: '-' }}</span>
                                    </div>
                                    <div class="mt-4 flex items-center justify-between gap-3 border-t border-slate-200 pt-3 dark:border-white/10">
                                        <span class="text-[10px] font-bold text-slate-400 dark:text-white/35">Klik kartu untuk ringkasan</span>
                                        <a href="{{ $classQueryUrl }}" @click.stop class="text-[10px] font-black uppercase tracking-widest text-indigo-600 transition hover:text-indigo-500 dark:text-indigo-300 dark:hover:text-indigo-200">
                                            Filter
                                        </a>
                                    </div>
                                </article>
                            @empty
                                <div class="md:col-span-2 xl:col-span-3 rounded-2xl border border-dashed border-slate-300 dark:border-white/10 bg-slate-50 dark:bg-white/[0.02] p-8 text-center">
                                    <p class="text-xs text-slate-500 dark:text-white/40 italic">Belum ada data kelas pada riwayat lab.</p>
                                </div>
                            @endforelse

                            @if($hiddenClassCount > 0)
                                <article role="button" tabindex="0" @click="showClassListModal = true" @keydown.enter="showClassListModal = true" @keydown.space.prevent="showClassListModal = true" class="rounded-2xl border border-dashed border-cyan-300 bg-cyan-50/70 p-5 text-center cursor-pointer transition hover:border-cyan-400 hover:bg-cyan-100 focus:outline-none focus:ring-2 focus:ring-cyan-400/40 dark:border-cyan-500/25 dark:bg-cyan-500/10 dark:hover:bg-cyan-500/15">
                                    <span class="block text-2xl font-black text-cyan-700 dark:text-cyan-200">+{{ $hiddenClassCount }}</span>
                                    <span class="mt-1 block text-[10px] font-black uppercase tracking-widest text-cyan-700 dark:text-cyan-200">kelas lain</span>
                                    <span class="mt-2 block text-xs font-semibold text-slate-500 dark:text-white/40">Lihat daftar kelas tanpa mengubah filter.</span>
                                </article>
                            @endif
                        </div>
                    </div>

                    {{-- =======================================================
                         B. GRAFIK PERFORMA LAB
                         ======================================================= --}}
                    <div class="reveal" style="animation-delay: 0.2s;" x-data="{ chartType: 'line' }">
                        <div class="glass-card rounded-2xl overflow-hidden relative">
                            <div class="relative px-6 py-5 border-b border-slate-200 dark:border-white/5 bg-slate-50/60 dark:bg-[#0a0e17]/50 transition-colors overflow-hidden">
                                <div class="absolute -top-24 -right-16 w-72 h-72 bg-indigo-300/20 dark:bg-indigo-600/10 rounded-full blur-[90px] pointer-events-none"></div>
                                <div class="absolute -bottom-28 -left-16 w-72 h-72 bg-cyan-300/20 dark:bg-cyan-600/10 rounded-full blur-[90px] pointer-events-none"></div>

                                <div class="relative z-10 flex flex-col gap-5 xl:flex-row xl:items-center xl:justify-between">
                                    <div>
                                        <p class="text-[10px] font-extrabold uppercase tracking-[0.2em] text-indigo-600 dark:text-indigo-400 mb-1">
                                            Performa Lab
                                        </p>
                                        <h3 class="text-xl md:text-2xl font-black text-slate-900 dark:text-white transition-colors">
                                            Grafik Perkembangan Nilai Lab
                                        </h3>
                                        <p class="text-xs text-slate-500 dark:text-white/40 mt-1 font-medium transition-colors max-w-2xl">
                                            Menampilkan rata-rata skor terbaik siswa pada setiap modul lab. Data kosong dibiarkan kosong, bukan dianggap nol.
                                        </p>
                                    </div>

                                    <div class="flex flex-col sm:flex-row gap-2 sm:items-center">
                                        <div class="flex items-center bg-white/80 dark:bg-[#020617] p-1 rounded-xl border border-slate-200 dark:border-white/5 shadow-inner transition-colors">
                                            <button type="button" @click="chartType = 'line'; window.updateMainPerformanceChartType('line')" :class="chartType === 'line' ? 'bg-slate-900 dark:bg-white text-white dark:text-[#020617] shadow-sm' : 'text-slate-500 dark:text-white/50 hover:text-slate-900 dark:hover:text-white'" class="px-4 py-2 rounded-lg text-[10px] font-bold transition focus:outline-none">Garis</button>
                                            <button type="button" @click="chartType = 'bar'; window.updateMainPerformanceChartType('bar')" :class="chartType === 'bar' ? 'bg-slate-900 dark:bg-white text-white dark:text-[#020617] shadow-sm' : 'text-slate-500 dark:text-white/50 hover:text-slate-900 dark:hover:text-white'" class="px-4 py-2 rounded-lg text-[10px] font-bold transition focus:outline-none">Batang</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 px-6 pt-5">
                                <div class="rounded-2xl bg-slate-50 dark:bg-[#020617]/70 border border-slate-200 dark:border-white/5 p-4 transition-colors">
                                    <p class="text-[9px] uppercase tracking-widest font-bold text-slate-400 dark:text-white/30">Rata-rata Lab</p>
                                    <p class="mt-1 text-xl font-black text-blue-600 dark:text-blue-400">{{ $labChartAverage !== null ? $labChartAverage : '-' }}</p>
                                </div>
                                <div class="rounded-2xl bg-slate-50 dark:bg-[#020617]/70 border border-slate-200 dark:border-white/5 p-4 transition-colors">
                                    <p class="text-[9px] uppercase tracking-widest font-bold text-slate-400 dark:text-white/30">Lab Tertinggi</p>
                                    <p class="mt-1 text-xl font-black text-emerald-600 dark:text-emerald-400">{{ $labChartHighest !== null ? $labChartHighest : '-' }}</p>
                                </div>
                                <div class="rounded-2xl bg-slate-50 dark:bg-[#020617]/70 border border-slate-200 dark:border-white/5 p-4 transition-colors">
                                    <p class="text-[9px] uppercase tracking-widest font-bold text-slate-400 dark:text-white/30">Lab Terendah</p>
                                    <p class="mt-1 text-xl font-black text-rose-600 dark:text-rose-400">{{ $labChartLowest !== null ? $labChartLowest : '-' }}</p>
                                </div>
                                <div class="rounded-2xl bg-slate-50 dark:bg-[#020617]/70 border border-slate-200 dark:border-white/5 p-4 transition-colors">
                                    <p class="text-[9px] uppercase tracking-widest font-bold text-slate-400 dark:text-white/30">Modul Dianalisis</p>
                                    <p class="mt-1 text-xl font-black text-indigo-600 dark:text-indigo-400">{{ $hasLabChartData ? $labChartLabels->count() : 0 }}</p>
                                </div>
                            </div>

                            <div class="relative p-6">
                                <div class="absolute inset-0 pointer-events-none overflow-hidden">
                                    <div class="absolute -top-16 right-8 w-72 h-72 bg-indigo-300/10 dark:bg-indigo-500/10 rounded-full blur-[90px]"></div>
                                    <div class="absolute -bottom-20 left-4 w-72 h-72 bg-cyan-300/10 dark:bg-cyan-500/10 rounded-full blur-[90px]"></div>
                                </div>

                                <div class="relative h-[330px] w-full z-10">
                                    @if($hasLabChartData)
                                        <canvas id="mainPerformanceChart"></canvas>
                                    @else
                                        <div class="absolute inset-0 flex flex-col items-center justify-center border border-dashed border-slate-200 dark:border-white/10 rounded-xl bg-slate-50 dark:bg-white/[0.02] transition-colors">
                                            <p class="text-xs font-semibold text-slate-400 dark:text-white/40">Belum ada data nilai lab.</p>
                                        </div>
                                    @endif
                                </div>

                                <div class="relative z-10 mt-5 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between text-[10px] font-bold text-slate-500 dark:text-white/40">
                                    <div class="flex items-center gap-4">
                                        <span class="inline-flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>Nilai Lab</span>
                                    </div>
                                    <p>Rata-rata skor terbaik per modul lab. Data kosong tidak dipaksa menjadi 0.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- =======================================================
                         C. SEMUA PERFORMA SISWA
                         ======================================================= --}}
                    <div class="glass-card rounded-2xl overflow-hidden reveal border-t-2 border-amber-500/50" style="animation-delay: 0.3s;" x-data="{ performanceView: 'best' }">
                        <div class="p-5 md:p-6 border-b border-slate-200 dark:border-white/5 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 bg-slate-50/50 dark:bg-[#0a0e17]/30 transition-colors">
                            <div>
                                <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2 transition-colors">
                                    Semua Performa Siswa
                                </h3>
                                <p class="text-[10px] text-slate-500 dark:text-white/40 mt-1 transition-colors">Memuat seluruh siswa yang memiliki riwayat lab. Gunakan filter untuk melihat urutan terbaik atau yang perlu pendampingan.</p>
                            </div>
                            <div class="flex flex-col sm:flex-row gap-2 sm:items-center">
                                <span class="text-[10px] bg-white dark:bg-[#020617] px-3 py-1.5 rounded-lg text-slate-500 dark:text-white/50 border border-slate-200 dark:border-white/10 shadow-sm dark:shadow-inner transition-colors">{{ $userPerformance->count() }} siswa</span>
                                <div class="flex items-center bg-white/80 dark:bg-[#020617] p-1 rounded-xl border border-slate-200 dark:border-white/5 shadow-inner transition-colors">
                                    <button type="button" @click="performanceView = 'best'" :class="performanceView === 'best' ? 'bg-emerald-600 text-white shadow-sm' : 'text-slate-500 dark:text-white/50 hover:text-slate-900 dark:hover:text-white'" class="px-4 py-2 rounded-lg text-[10px] font-bold transition focus:outline-none">Terbaik</button>
                                    <button type="button" @click="performanceView = 'worst'" :class="performanceView === 'worst' ? 'bg-rose-600 text-white shadow-sm' : 'text-slate-500 dark:text-white/50 hover:text-slate-900 dark:hover:text-white'" class="px-4 py-2 rounded-lg text-[10px] font-bold transition focus:outline-none">Terburuk</button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="overflow-x-auto overflow-y-auto custom-scrollbar max-h-[560px]">
                            <table class="w-full text-sm text-left border-collapse min-w-[1080px]">
                                <thead class="bg-slate-100 dark:bg-[#0f141e] text-slate-500 dark:text-white/40 text-[10px] uppercase font-bold border-b border-slate-200 dark:border-white/5 sticky top-0 z-10 shadow-sm dark:shadow-lg transition-colors">
                                    <tr>
                                        <th class="px-6 py-4 w-16 text-center border-b border-slate-200 dark:border-white/5">Peringkat</th>
                                        <th class="px-6 py-4 border-b border-slate-200 dark:border-white/5">Profil Siswa</th>
                                        <th class="px-6 py-4 text-center border-b border-slate-200 dark:border-white/5">Kelas</th>
                                        <th class="px-6 py-4 text-center border-b border-slate-200 dark:border-white/5">Percobaan</th>
                                        <th class="px-6 py-4 text-center border-b border-slate-200 dark:border-white/5">Status</th>
                                        <th class="px-6 py-4 text-center border-b border-slate-200 dark:border-white/5">Rata-rata</th>
                                        <th class="px-6 py-4 text-center border-b border-slate-200 dark:border-white/5">Rentang Skor</th>
                                        <th class="px-6 py-4 text-center border-b border-slate-200 dark:border-white/5">Aktivitas Terakhir</th>
                                        <th class="px-6 py-4 text-right border-b border-slate-200 dark:border-white/5">Aksi</th>
                                    </tr>
                                </thead>

                                @foreach(['best' => $performanceBest, 'worst' => $performanceWorst] as $view => $rows)
                                    <tbody x-show="performanceView === '{{ $view }}'" @if($view !== 'best') style="display: none;" @endif class="divide-y divide-slate-200 dark:divide-white/5 bg-white/50 dark:bg-[#0a0e17]/30 transition-colors">
                                        @forelse($rows as $index => $usr)
                                            <tr class="table-row group">
                                                <td class="px-6 py-4 text-center">
                                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center font-black text-xs mx-auto shadow-sm dark:shadow-inner
                                                        {{ $view === 'best' && $index == 0 ? 'bg-gradient-to-br from-amber-300 to-amber-500 text-amber-900 shadow-md dark:shadow-[0_0_10px_#eab308]' :
                                                           ($view === 'best' && $index == 1 ? 'bg-gradient-to-br from-slate-200 to-slate-400 text-slate-800' :
                                                           ($view === 'best' && $index == 2 ? 'bg-gradient-to-br from-orange-400 to-orange-600 text-white' : 'bg-indigo-50 dark:bg-indigo-500/20 border border-indigo-200 dark:border-indigo-500/30 text-indigo-600 dark:text-indigo-400')) }}">
                                                        {{ $index + 1 }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="flex flex-col">
                                                        <a href="{{ route('admin.student.analytics', $usr->student_id ?? 1) }}" class="font-bold text-slate-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400 transition flex items-center gap-2 group-hover:translate-x-1 duration-200">
                                                            {{ $usr->name ?? 'Siswa' }}
                                                            <svg class="w-3 h-3 opacity-0 group-hover:opacity-100 transition-opacity text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                                        </a>
                                                        <span class="text-[10px] text-slate-500 dark:text-white/30 font-mono mt-0.5 transition-colors">{{ $usr->email ?? '-' }}</span>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    <span class="inline-flex items-center justify-center px-3 py-1.5 rounded-full bg-indigo-50 dark:bg-indigo-500/10 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-500/20 text-[10px] font-black">
                                                        {{ $usr->class_group ?: '-' }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    <div class="flex justify-center items-center gap-2">
                                                        <span class="bg-white dark:bg-[#020617] px-2 py-1.5 rounded-lg text-[10px] text-slate-600 dark:text-white/60 border border-slate-200 dark:border-white/5 shadow-sm dark:shadow-inner transition-colors">
                                                            {{ $usr->total_tries ?? 0 }}x
                                                        </span>
                                                        <span class="bg-white dark:bg-[#020617] px-2 py-1.5 rounded-lg text-[10px] text-cyan-600 dark:text-cyan-400 border border-slate-200 dark:border-white/5 shadow-sm dark:shadow-inner transition-colors">
                                                            {{ is_numeric($usr->avg_time ?? 0) ? gmdate("i:s", $usr->avg_time) : ($usr->avg_time ?? 0) }}
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    <div class="flex justify-center items-center gap-2">
                                                        <span class="bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 px-2 py-1.5 rounded-lg text-[10px] border border-emerald-200 dark:border-emerald-500/20 font-bold">{{ $usr->passed_tries ?? 0 }} lulus</span>
                                                        <span class="bg-rose-50 dark:bg-rose-500/10 text-rose-700 dark:text-rose-400 px-2 py-1.5 rounded-lg text-[10px] border border-rose-200 dark:border-rose-500/20 font-bold">{{ $usr->failed_tries ?? 0 }} belum</span>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    <span class="font-black {{ ($usr->average_score ?? 0) >= 70 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                                        {{ round((float) ($usr->average_score ?? 0), 1) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    <span class="font-mono text-[10px] text-slate-600 dark:text-white/60">
                                                        {{ $usr->lowest_score ?? 0 }} - {{ $usr->best_score ?? 0 }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    <span class="text-[10px] text-slate-500 dark:text-white/50 bg-white dark:bg-[#020617] px-3 py-1.5 rounded-full border border-slate-200 dark:border-white/5 shadow-sm dark:shadow-inner transition-colors">
                                                        {{ isset($usr->last_attempt) ? \Carbon\Carbon::parse($usr->last_attempt)->diffForHumans() : '-' }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 text-right">
                                                    <a href="{{ route('admin.student.analytics', $usr->student_id ?? 1) }}" class="inline-flex items-center justify-center px-3 py-2 rounded-lg bg-indigo-50 dark:bg-indigo-600/10 hover:bg-indigo-600 dark:hover:bg-indigo-600 text-indigo-600 dark:text-indigo-400 hover:text-white border border-indigo-200 dark:border-indigo-600/30 text-[10px] font-bold transition-all shadow-sm dark:shadow-inner">
                                                        Tinjau Siswa
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="py-16 text-center text-slate-500 dark:text-white/30 text-xs italic bg-slate-50 dark:bg-[#0a0e17]/50 transition-colors">
                                                    Belum ada data riwayat pengerjaan siswa.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                @endforeach
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </main>
    </div>

    {{-- MODAL PANDUAN DASBOR ADMIN (HERO MODAL POPUP) --}}
    <div x-show="showDashboardInfoModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4 sm:p-6" x-cloak style="display: none;">
        <div class="absolute inset-0 bg-slate-900/60 dark:bg-[#020617]/80 backdrop-blur-md cursor-pointer transition-opacity" @click="showDashboardInfoModal = false" x-transition.opacity></div>
        
        <div class="relative w-full max-w-xl bg-white/90 dark:bg-[#0f141e]/95 backdrop-blur-xl border border-slate-200 dark:border-white/10 rounded-[2rem] p-8 md:p-10 shadow-2xl transition-all text-center" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-95 translate-y-4">
            
            <button @click="showDashboardInfoModal = false" class="absolute top-5 right-5 p-2 rounded-full hover:bg-slate-100 dark:hover:bg-white/5 text-slate-400 hover:text-slate-600 dark:hover:text-white transition-all focus:outline-none z-10">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <!-- Hero Logo Section -->
            <div class="relative w-4 h-4 mx-auto mb-6">
                
            </div>
            
            <h3 class="text-2xl font-black text-slate-900 dark:text-white leading-tight mb-2">Panduan <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-cyan-500 dark:from-indigo-400 dark:to-cyan-400">Analitik Lab</span></h3>
            <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-6">Pusat Analitik & Pemantauan Performa</p>
            
            <div class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed font-medium text-justify space-y-4">
                <p>Halaman ini membantu admin membaca perkembangan praktikum siswa secara lebih cepat. Data utama ditampilkan dalam bentuk ringkasan, grafik, dan daftar peringkat agar mudah ditindaklanjuti.</p>
                
                <div class="space-y-3 mt-4 text-left">
                    <div class="flex items-start gap-3 p-3 bg-slate-50/50 dark:bg-slate-800/30 rounded-xl border border-slate-100 dark:border-white/5">
                        <span class="text-slate-400 dark:text-slate-500 mt-0.5 font-mono text-xs">01</span>
                        <div>
                            <h4 class="text-xs font-bold text-slate-800 dark:text-slate-200">Ringkasan Kinerja</h4>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">Menampilkan jumlah percobaan, rasio kelulusan, rata-rata nilai, dan rata-rata durasi pengerjaan lab.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-3 bg-slate-50/50 dark:bg-slate-800/30 rounded-xl border border-slate-100 dark:border-white/5">
                        <span class="text-slate-400 dark:text-slate-500 mt-0.5 font-mono text-xs">02</span>
                        <div>
                            <h4 class="text-xs font-bold text-slate-800 dark:text-slate-200">Visualisasi Tren & Rasio</h4>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">Menampilkan grafik perkembangan nilai lab agar pola pengerjaan praktikum siswa lebih mudah dibaca.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-3 bg-slate-50/50 dark:bg-slate-800/30 rounded-xl border border-slate-100 dark:border-white/5">
                        <span class="text-slate-400 dark:text-slate-500 mt-0.5 font-mono text-xs">03</span>
                        <div>
                            <h4 class="text-xs font-bold text-slate-800 dark:text-slate-200">Peringkat Peserta</h4>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">Menampilkan siswa dengan performa terbaik berdasarkan nilai, jumlah percobaan, dan waktu pengerjaan.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-slate-200 dark:border-white/5">
                <button @click="showDashboardInfoModal = false" class="w-full py-3 bg-slate-900 hover:bg-slate-800 dark:bg-white dark:hover:bg-slate-200 text-white dark:text-slate-900 font-bold text-sm rounded-xl transition-colors shadow-md focus:outline-none">
                    Mengerti, Tutup Panduan
                </button>
            </div>
        </div>
    </div>

    {{-- ==================== HERO MODALS (INSIGHT DATA PER CARD) ==================== --}}

    {{-- Modal: Daftar Kelas --}}
    <div x-show="showClassListModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4 sm:p-6" x-cloak style="display: none;">
        <div class="absolute inset-0 bg-slate-900/80 dark:bg-[#020617]/90 backdrop-blur-md transition-opacity" @click="showClassListModal = false" x-transition.opacity></div>

        <div class="relative flex max-h-[90vh] w-full max-w-5xl flex-col overflow-hidden rounded-[2rem] border border-cyan-200 bg-white/95 shadow-2xl backdrop-blur-xl transition-all dark:border-cyan-500/30 dark:bg-[#0f141e]/95"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            <div class="flex items-start justify-between gap-4 border-b border-slate-200 bg-slate-50/80 px-6 py-5 dark:border-white/5 dark:bg-[#0a0e17]/80">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] text-cyan-600 dark:text-cyan-300">Daftar kelas lab</p>
                    <h3 class="mt-1 text-xl font-black text-slate-900 dark:text-white">Ringkasan Seluruh Kelas</h3>
                    <p class="mt-1 text-xs font-semibold text-slate-500 dark:text-white/40">Klik kartu untuk ringkasan. Tautan Filter memuat halaman kelas.</p>
                </div>
                <button @click="showClassListModal = false" class="rounded-full p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700 dark:hover:bg-white/5 dark:hover:text-white">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="overflow-y-auto p-5 custom-scrollbar">
                <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-3">
                    @forelse($classPerformance as $classRow)
                        @php
                            $classAttempts = (int) ($classRow->total_attempts ?? 0);
                            $classLulus = (int) ($classRow->passed_attempts ?? 0);
                            $classFailed = (int) ($classRow->failed_attempts ?? 0);
                            $classRate = $classRow->pass_rate ?? ($classAttempts > 0 ? round(($classLulus / $classAttempts) * 100, 1) : 0);
                            $classAvg = round((float) ($classRow->avg_score ?? 0), 1);
                            $classQueryUrl = route('admin.lab.analytics', $analyticsRouteParams) . '?class_group=' . urlencode($classRow->class_group);
                            $classInsightPayload = [
                                'name' => $classRow->class_group,
                                'major' => $classRow->major ?: 'Program belum diatur',
                                'token' => $classRow->token ?: '-',
                                'status' => $classRow->status_label ?? 'Aktif',
                                'students_count' => (int) ($classRow->students_count ?? 0),
                                'enrolled_students' => (int) ($classRow->enrolled_students ?? $classRow->students_count ?? 0),
                                'total_attempts' => $classAttempts,
                                'passed_attempts' => $classLulus,
                                'failed_attempts' => $classFailed,
                                'pass_rate' => $classRate,
                                'avg_score' => $classAvg,
                                'avg_time' => $classRow->avg_time_label ?? '-',
                                'last_attempt' => $classRow->last_attempt_label ?? 'Belum ada aktivitas',
                                'url' => $classQueryUrl,
                                'note' => $classAvg >= 70
                                    ? 'Kinerja kelas sudah berada pada jalur baik.'
                                    : 'Kelas perlu ditinjau pada percobaan yang belum lulus.',
                            ];
                        @endphp
                        <article
                            role="button"
                            tabindex="0"
                            data-class-insight='@json($classInsightPayload, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_HEX_TAG)'
                            @click="showClassListModal = false; openClassInsight(JSON.parse($event.currentTarget.dataset.classInsight))"
                            @keydown.enter="showClassListModal = false; openClassInsight(JSON.parse($event.currentTarget.dataset.classInsight))"
                            @keydown.space.prevent="showClassListModal = false; openClassInsight(JSON.parse($event.currentTarget.dataset.classInsight))"
                            class="rounded-2xl border border-slate-200 bg-slate-50/80 p-4 cursor-pointer transition hover:border-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-400/40 dark:border-white/10 dark:bg-[#020617]/70 dark:hover:border-cyan-500/30">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Kelas</p>
                                    <h4 class="mt-1 truncate text-base font-black text-slate-900 dark:text-white">{{ $classRow->class_group }}</h4>
                                    <p class="mt-1 truncate text-[10px] font-bold text-slate-500 dark:text-white/40">{{ $classRow->major ?: 'Program belum diatur' }}</p>
                                </div>
                                <span class="rounded-lg border px-2.5 py-1 text-[10px] font-black {{ $classAvg >= 70 ? 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-300' : 'border-rose-200 bg-rose-50 text-rose-700 dark:border-rose-500/20 dark:bg-rose-500/10 dark:text-rose-300' }}">{{ $classRate }}%</span>
                            </div>
                            <div class="mt-4 grid grid-cols-3 gap-2 text-center">
                                <div class="rounded-xl bg-white px-2 py-2 dark:bg-black/10">
                                    <p class="font-black text-slate-900 dark:text-white">{{ $classRow->students_count ?? 0 }}</p>
                                    <p class="text-[8px] font-bold uppercase tracking-widest text-slate-400">Siswa</p>
                                </div>
                                <div class="rounded-xl bg-white px-2 py-2 dark:bg-black/10">
                                    <p class="font-black text-indigo-600 dark:text-indigo-300">{{ $classAttempts }}</p>
                                    <p class="text-[8px] font-bold uppercase tracking-widest text-slate-400">Coba</p>
                                </div>
                                <div class="rounded-xl bg-white px-2 py-2 dark:bg-black/10">
                                    <p class="font-black {{ $classAvg >= 70 ? 'text-emerald-600 dark:text-emerald-300' : 'text-rose-600 dark:text-rose-300' }}">{{ $classAvg }}</p>
                                    <p class="text-[8px] font-bold uppercase tracking-widest text-slate-400">Nilai</p>
                                </div>
                            </div>
                            <div class="mt-4 flex items-center justify-between border-t border-slate-200 pt-3 dark:border-white/10">
                                <span class="text-[10px] font-bold text-slate-400 dark:text-white/35">Ringkasan</span>
                                <a href="{{ $classQueryUrl }}" @click.stop class="text-[10px] font-black uppercase tracking-widest text-cyan-700 transition hover:text-cyan-500 dark:text-cyan-300 dark:hover:text-cyan-200">
                                    Filter
                                </a>
                            </div>
                        </article>
                    @empty
                        <div class="md:col-span-2 xl:col-span-3 rounded-2xl border border-dashed border-slate-300 p-8 text-center text-xs font-semibold text-slate-500 dark:border-white/10 dark:text-white/40">
                            Belum ada data kelas pada riwayat lab.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Modal: Insight Per Kelas --}}
    <div x-show="showClassInsightModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4 sm:p-6" x-cloak style="display: none;">
        <div class="absolute inset-0 bg-slate-900/80 dark:bg-[#020617]/90 backdrop-blur-md transition-opacity" @click="showClassInsightModal = false" x-transition.opacity></div>

        <div class="relative w-full max-w-3xl max-h-[90vh] overflow-hidden rounded-[2rem] border border-indigo-200 bg-white/95 shadow-2xl backdrop-blur-xl transition-all dark:border-indigo-500/30 dark:bg-[#0f141e]/95 dark:shadow-[0_30px_100px_rgba(99,102,241,0.18)]"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4">

            <button @click="showClassInsightModal = false" class="absolute right-5 top-5 z-10 rounded-full p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700 dark:hover:bg-white/5 dark:hover:text-white">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <div class="relative overflow-hidden border-b border-slate-200 bg-slate-50/80 px-6 py-7 dark:border-white/5 dark:bg-[#0a0e17]/80 sm:px-8">
                <div class="absolute -right-16 -top-20 h-56 w-56 rounded-full bg-indigo-300/25 blur-3xl dark:bg-indigo-500/10"></div>
                <div class="absolute -bottom-24 left-10 h-48 w-48 rounded-full bg-cyan-300/20 blur-3xl dark:bg-cyan-500/10"></div>

                <div class="relative z-10 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div class="flex min-w-0 items-start gap-4">
                        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl border border-indigo-200 bg-indigo-50 text-indigo-600 shadow-sm dark:border-indigo-500/30 dark:bg-indigo-500/10 dark:text-indigo-300">
                            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M4 7a2 2 0 012-2h12a2 2 0 012 2v11a1 1 0 01-1 1H5a1 1 0 01-1-1V7zm4 4h8M8 15h5"/></svg>
                        </div>
                        <div class="min-w-0 pr-10 sm:pr-0">
                            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-indigo-600 dark:text-indigo-300">Ringkasan Kelas Lab</p>
                            <h3 class="mt-1 truncate text-2xl font-black text-slate-900 dark:text-white" x-text="selectedClassInsight.name || 'Kelas'"></h3>
                            <p class="mt-1 text-xs font-semibold text-slate-500 dark:text-slate-400" x-text="selectedClassInsight.major || 'Program belum diatur'"></p>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2 sm:justify-end">
                        <span class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-[10px] font-black uppercase tracking-widest text-slate-600 shadow-sm dark:border-white/10 dark:bg-[#020617] dark:text-white/60" x-text="'Token ' + (selectedClassInsight.token || '-')"></span>
                        <span class="rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-2 text-[10px] font-black uppercase tracking-widest text-emerald-700 dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-300" x-text="selectedClassInsight.status || 'Aktif'"></span>
                    </div>
                </div>
            </div>

            <div class="max-h-[calc(90vh-180px)] overflow-y-auto p-6 custom-scrollbar sm:p-8">
                <div class="grid grid-cols-2 gap-3 md:grid-cols-4">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-white/5 dark:bg-[#020617]/70">
                        <p class="text-[9px] font-bold uppercase tracking-widest text-slate-400 dark:text-white/30">Siswa Aktif</p>
                        <p class="mt-1 text-2xl font-black text-indigo-600 dark:text-indigo-300" x-text="selectedClassInsight.students_count || 0"></p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-white/5 dark:bg-[#020617]/70">
                        <p class="text-[9px] font-bold uppercase tracking-widest text-slate-400 dark:text-white/30">Percobaan</p>
                        <p class="mt-1 text-2xl font-black text-slate-900 dark:text-white" x-text="selectedClassInsight.total_attempts || 0"></p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-white/5 dark:bg-[#020617]/70">
                        <p class="text-[9px] font-bold uppercase tracking-widest text-slate-400 dark:text-white/30">Rata-rata</p>
                        <p class="mt-1 text-2xl font-black" :class="(selectedClassInsight.avg_score || 0) >= 70 ? 'text-emerald-600 dark:text-emerald-300' : 'text-rose-600 dark:text-rose-300'" x-text="selectedClassInsight.avg_score || 0"></p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-white/5 dark:bg-[#020617]/70">
                        <p class="text-[9px] font-bold uppercase tracking-widest text-slate-400 dark:text-white/30">Durasi Rata-rata</p>
                        <p class="mt-1 text-2xl font-black text-cyan-600 dark:text-cyan-300" x-text="selectedClassInsight.avg_time || '-'"></p>
                    </div>
                </div>

                <div class="mt-5 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-white/5 dark:bg-[#020617]/70">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-white/30">Rasio Kelulusan</p>
                            <p class="mt-1 text-sm font-semibold text-slate-600 dark:text-slate-300" x-text="selectedClassInsight.note || 'Belum ada catatan kelas.'"></p>
                        </div>
                        <p class="text-3xl font-black text-indigo-600 dark:text-indigo-300" x-text="(selectedClassInsight.pass_rate || 0) + '%'"></p>
                    </div>
                    <div class="mt-4 h-2.5 overflow-hidden rounded-full bg-slate-200 dark:bg-white/10">
                        <div class="h-full rounded-full transition-all"
                             :class="(selectedClassInsight.pass_rate || 0) >= 70 ? 'bg-emerald-500' : 'bg-rose-500'"
                             :style="'width: ' + Math.min(selectedClassInsight.pass_rate || 0, 100) + '%'"></div>
                    </div>
                    <div class="mt-3 flex flex-wrap items-center gap-2 text-[10px] font-bold text-slate-500 dark:text-white/40">
                        <span class="rounded-lg border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-emerald-700 dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-300" x-text="(selectedClassInsight.passed_attempts || 0) + ' lulus'"></span>
                        <span class="rounded-lg border border-rose-200 bg-rose-50 px-2.5 py-1 text-rose-700 dark:border-rose-500/20 dark:bg-rose-500/10 dark:text-rose-300" x-text="(selectedClassInsight.failed_attempts || 0) + ' belum lulus'"></span>
                        <span class="ml-auto" x-text="'Aktivitas terakhir: ' + (selectedClassInsight.last_attempt || '-')"></span>
                    </div>
                </div>

                <div class="mt-6 flex flex-col gap-3 border-t border-slate-200 pt-5 dark:border-white/10 sm:flex-row sm:items-center sm:justify-between">
                    <button type="button" @click="showClassInsightModal = false" class="text-left text-sm font-bold text-slate-500 transition hover:text-slate-900 dark:text-white/50 dark:hover:text-white">
                        Tutup ringkasan
                    </button>
                    <a :href="selectedClassInsight.url || '#'" class="text-sm font-black text-indigo-600 transition hover:text-indigo-500 dark:text-indigo-300 dark:hover:text-indigo-200">
                        Filter halaman ke kelas ini &rarr;
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- 1. Modal: Rincian Total Percobaan --}}
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

</body>
</html>
