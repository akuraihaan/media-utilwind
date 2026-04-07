<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Student Lab Analytics · {{ $user->name ?? 'Student' }}</title>
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

        /* --- CODE VIEWER --- */
        .code-block { background: #f1f5f9; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1.5rem; color: #1e293b; font-size: 0.85rem; line-height: 1.6; overflow-x: auto; transition: 0.3s; }
        .dark .code-block { background: #0d1117; border: 1px solid #30363d; color: #c9d1d9; }

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
        .tooltip-indigo .tooltip-trigger:hover { background-color: #818cf8; }
        .tooltip-indigo .tooltip-content { border: 1px solid rgba(99,102,241,0.5); }

        .tooltip-emerald .tooltip-trigger { background-color: #10b981; box-shadow: 0 0 10px rgba(16,185,129,0.5); color: white; border:none; opacity: 1;}
        .tooltip-emerald .tooltip-trigger:hover { background-color: #34d399; }
        .tooltip-emerald .tooltip-content { border: 1px solid rgba(16,185,129,0.5); }

        .tooltip-yellow .tooltip-trigger { background-color: #eab308; color: #020617; box-shadow: 0 0 10px rgba(234,179,8,0.5); border:none; opacity: 1;}
        .tooltip-yellow .tooltip-trigger:hover { background-color: #facc15; }
        .tooltip-yellow .tooltip-content { border: 1px solid rgba(234,179,8,0.5); }

        .tooltip-cyan .tooltip-trigger { background-color: #06b6d4; box-shadow: 0 0 10px rgba(6,182,212,0.5); color: white; border:none; opacity: 1;}
        .tooltip-cyan .tooltip-trigger:hover { background-color: #22d3ee; }
        .tooltip-cyan .tooltip-content { border: 1px solid rgba(6,182,212,0.5); }

        .modal-open { overflow: hidden; padding-right: 5px; } 
    </style>
</head>
<body x-data="{ 
    sidebarOpen: false,
    isFullscreen: false,
    
    // State Modal Hero Insight
    showAvgScoreModal: false,
    showTotalLabsModal: false,
    showPassedModal: false,
    showTimeModal: false,
}" @keydown.escape.window="isFullscreen = false; document.exitFullscreen(); showAvgScoreModal = false; showTotalLabsModal = false; showPassedModal = false; showTimeModal = false;" :class="{'modal-open': sidebarOpen || showAvgScoreModal || showTotalLabsModal || showPassedModal || showTimeModal}">

    {{-- LOGIKA DATA BLADE (BULLETPROOF FALLBACK) --}}
    @php
        $globalAvgScore = $globalAvgScore ?? 0;
        $totalLabsAttempted = $totalLabsAttempted ?? 0;
        $passedLabs = $passedLabs ?? 0;
        $totalTimeSpent = $totalTimeSpent ?? '00:00';
        $histories = isset($histories) ? collect($histories) : collect([]);
        $chartLabels = $chartLabels ?? [];
        $chartScores = $chartScores ?? [];
        $failedLabs = max(0, $totalLabsAttempted - $passedLabs); // Kalkulasi aman
    @endphp

    <div class="flex h-screen w-full relative">

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
                    <span class="text-[9px] font-bold text-slate-500 dark:text-white/40 tracking-[0.2em] uppercase transition-colors">Admin Panel</span>
                </div>
            </a>
            <button @click="sidebarOpen = false" class="md:hidden text-slate-500 dark:text-white/50 hover:text-slate-800 dark:hover:text-white relative z-10 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <nav class="flex-1 overflow-y-auto custom-scrollbar py-8 px-4 space-y-8">
            <div>
                <p class="px-4 text-[10px] font-extrabold text-slate-400 dark:text-white/30 uppercase tracking-widest mb-3 transition-colors">Overview</p>
                <div class="space-y-1">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.dashboard') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                        Dashboard
                    </a>
                </div>
            </div>

            <div>
                <p class="px-4 text-[10px] font-extrabold text-slate-400 dark:text-white/30 uppercase tracking-widest mb-3 transition-colors">Academic</p>
                <div class="space-y-1">
                    <a href="{{ route('admin.analytics.questions') }}" class="nav-link {{ request()->routeIs('admin.analytics.questions') ? 'active' : '' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.analytics.questions') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                        Quiz Management
                    </a>
                    <a href="{{ route('admin.labs.index') }}" class="nav-link {{ request()->routeIs('admin.labs.index') ? 'active' : '' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.labs.index') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                        Lab Configuration
                    </a>
                    <a href="{{ route('admin.lab.analytics') }}" class="nav-link {{ request()->routeIs('admin.lab.analytics') ? 'active' : '' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.lab.analytics') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
                        Lab Analytics
                    </a>
                    <a href="{{ route('admin.classes.index') }}" class="nav-link {{ request()->routeIs('admin.classes.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.classes.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        Class Management
                    </a>
                </div>
            </div>
        </nav>

        {{-- USER PROFILE Bawah Sidebar --}}
        <div class="p-4 border-t border-slate-200 dark:border-white/5 bg-slate-50/50 dark:bg-[#05080f]/50 transition-colors">
            <div class="flex items-center gap-3 mb-4 px-2">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-fuchsia-500 flex items-center justify-center font-bold text-white text-xs shadow-lg">AD</div>
                <div class="overflow-hidden">
                    <p class="text-xs font-bold text-slate-900 dark:text-white truncate transition-colors">{{ Auth::user()->name ?? 'Administrator' }}</p>
                    <p class="text-[10px] text-slate-500 dark:text-white/40 truncate transition-colors">System Admin</p>
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
        <main class="flex-1 flex flex-col relative z-10 h-full overflow-y-auto overflow-x-hidden">
            
            {{-- Background FX --}}
            <div class="fixed inset-0 pointer-events-none z-0">
                <div class="absolute top-[10%] left-[20%] w-[500px] h-[500px] bg-indigo-300/30 dark:bg-indigo-600/10 rounded-full blur-[120px] transition-colors"></div>
                <div class="absolute bottom-[10%] right-[10%] w-[400px] h-[400px] bg-fuchsia-300/30 dark:bg-fuchsia-600/10 rounded-full blur-[120px] transition-colors"></div>
                <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.02] dark:opacity-[0.04] mix-blend-overlay transition-opacity"></div>
            </div>

            {{-- HEADER RESPONSIVE & BREADCRUMB --}}
            <header class="h-24 glass-header flex flex-col justify-center px-6 md:px-10 shrink-0 sticky top-0 z-40 transition-colors">
                <div class="flex items-center justify-between w-full">
                    <div class="flex items-center gap-4">
                        <button @click="sidebarOpen = true" class="md:hidden p-2 bg-slate-200 dark:bg-white/5 rounded-lg text-slate-700 dark:text-white hover:bg-slate-300 dark:hover:bg-white/10 transition-colors shadow-sm dark:shadow-none">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        </button>
                        
                        <div class="flex items-center gap-4">
                            {{-- Avatar Siswa (Khas Halaman Detail) --}}
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-fuchsia-600 p-[1px] shadow-md dark:shadow-lg hidden sm:block">
                                <div class="w-full h-full bg-slate-50 dark:bg-[#0f141e] rounded-[11px] flex items-center justify-center font-black text-slate-900 dark:text-white text-sm transition-colors">
                                    {{ substr($user->name ?? 'S', 0, 2) }}
                                </div>
                            </div>
                            
                            <div>
                                <nav class="flex text-[10px] text-slate-500 dark:text-white/50 mb-1.5 font-bold hidden sm:flex transition-colors" aria-label="Breadcrumb">
                                    <ol class="inline-flex items-center space-x-1">
                                        <li class="inline-flex items-center"><a href="{{ route('admin.dashboard') ?? '#' }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Dashboard</a></li>
                                        <li>
                                            <div class="flex items-center">
                                                <svg class="w-3 h-3 text-slate-400 dark:text-white/30 mx-1 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                                <a href="{{ route('admin.lab.analytics') ?? '#' }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Lab Analytics</a>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="flex items-center">
                                                <svg class="w-3 h-3 text-slate-400 dark:text-white/30 mx-1 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                                <span class="text-slate-900 dark:text-white transition-colors">Student Lab Analytics</span>
                                            </div>
                                        </li>
                                    </ol>
                                </nav>
                                <h2 class="text-slate-900 dark:text-white font-bold text-lg md:text-xl tracking-tight flex items-center gap-2 transition-colors">
                                    {{ $user->name ?? 'Student Profile' }}
                                </h2>
                                <p class="text-[9px] md:text-xs text-slate-500 dark:text-white/40 flex items-center gap-1.5 mt-0.5 font-mono transition-colors">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse shadow-[0_0_8px_#10b981]"></span>
                                    {{ $user->email ?? 'No email recorded' }}
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3 sm:gap-6">
                        {{-- Tombol Refresh & Fullscreen --}}
                        <button onclick="window.location.reload()" class="p-2.5 text-slate-500 dark:text-white/40 hover:text-slate-900 dark:hover:text-white transition-colors rounded-full hover:bg-slate-200 dark:hover:bg-white/5 group hidden sm:block border border-transparent dark:hover:border-white/10" title="Refresh">
                            <svg class="w-4 h-4 group-hover:rotate-180 transition-transform duration-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        </button>
                        <button @click="isFullscreen = !isFullscreen; isFullscreen ? document.documentElement.requestFullscreen() : document.exitFullscreen()" class="p-2.5 text-slate-500 dark:text-white/40 hover:text-slate-900 dark:hover:text-white transition-colors rounded-full hover:bg-slate-200 dark:hover:bg-white/5 hidden md:block border border-transparent dark:hover:border-white/10" title="Fullscreen">
                            <svg x-show="!isFullscreen" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                            <svg x-show="isFullscreen" style="display: none;" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>

                        <div class="border-l border-slate-300 dark:border-white/10 pl-5 ml-1 hidden lg:block transition-colors">
                            <a href="{{ route('admin.lab.analytics') ?? '#' }}" class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-200 dark:bg-white/5 hover:bg-slate-300 dark:hover:bg-white/10 border border-slate-300 dark:border-white/10 text-slate-700 dark:text-white text-xs font-bold transition-colors group shadow-sm dark:shadow-none">
                                <svg class="w-3.5 h-3.5 text-slate-500 dark:text-white/50 group-hover:text-slate-900 dark:group-hover:text-white transition-transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                                <span>Back to Analytics</span>
                            </a>
                        </div>
                        
                        <div class="text-right hidden lg:block border-l border-slate-300 dark:border-white/10 pl-5 ml-1 transition-colors">
                            <p class="text-sm font-bold text-slate-900 dark:text-white transition-colors">{{ \Carbon\Carbon::now()->translatedFormat('d M Y') }}</p>
                            <p class="text-[10px] text-slate-500 dark:text-white/40 font-mono mt-0.5 transition-colors">{{ \Carbon\Carbon::now()->format('H:i') }} WIB</p>
                        </div>

                        {{-- Mobile Back Button --}}
                        <a href="{{ route('admin.lab.analytics') ?? '#' }}" class="lg:hidden p-2 rounded-lg bg-slate-200 dark:bg-white/5 text-slate-700 dark:text-white shadow-sm dark:shadow-lg border border-slate-300 dark:border-white/10 transition-colors">
                             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        </a>
                    </div>
                </div>
            </header>

            {{-- CONTENT SCROLLABLE --}}
            <div class="flex-1 p-6 md:p-10 relative z-10">
                <div class="max-w-7xl mx-auto space-y-8 md:space-y-12">

                    {{-- A. PERSONAL STATS (4 KARTU) --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 reveal" style="animation-delay: 0.1s;">
                        
                        {{-- Card 1: Avg Score --}}
                        <div class="glass-card rounded-2xl p-5 border-l-4 border-l-yellow-500 cursor-pointer group transition-all" @click="showAvgScoreModal = true">
                            <div class="flex justify-between items-start">
                                <p class="text-[10px] font-bold text-slate-500 dark:text-white/40 uppercase tracking-widest group-hover:text-amber-600 dark:group-hover:text-yellow-400 transition-colors">Rata-rata Nilai</p>
                                <div class="tooltip-container tooltip-yellow tooltip-down tooltip-left">
                                    <div class="tooltip-trigger">?</div>
                                    <div class="tooltip-content">
                                        <span class="block font-bold text-amber-600 dark:text-yellow-400 mb-1 border-b border-slate-200 dark:border-white/10 pb-1">Average Score</span>
                                        Rata-rata kalkulasi nilai dari semua modul lab dan kuis yang dikerjakan.
                                    </div>
                                </div>
                            </div>
                            <h3 class="text-2xl md:text-3xl font-black text-slate-900 dark:text-white mt-2 transition-colors">{{ $globalAvgScore ?? 0 }} <span class="text-xs text-slate-400 dark:text-white/30 font-normal">/ 100</span></h3>
                            <div class="w-full bg-slate-200 dark:bg-white/10 h-1 mt-3 rounded-full overflow-hidden transition-colors">
                                <div class="h-full {{ ($globalAvgScore ?? 0) >= 70 ? 'bg-yellow-400' : 'bg-red-400' }} transition-all" style="width: {{ $globalAvgScore ?? 0 }}%"></div>
                            </div>
                        </div>

                        {{-- Card 2: Total Labs Attempted --}}
                        <div class="glass-card rounded-2xl p-5 border-l-4 border-l-indigo-500 cursor-pointer group transition-all" @click="showTotalLabsModal = true">
                            <div class="flex justify-between items-start">
                                <p class="text-[10px] font-bold text-slate-500 dark:text-white/40 uppercase tracking-widest group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">Total Percobaan</p>
                                <div class="tooltip-container tooltip-indigo tooltip-down tooltip-left">
                                    <div class="tooltip-trigger">?</div>
                                    <div class="tooltip-content">
                                        <span class="block font-bold text-indigo-600 dark:text-indigo-400 mb-1 border-b border-slate-200 dark:border-white/10 pb-1">Total Attempts</span>
                                        Jumlah kali siswa ini mengerjakan atau mengulang modul lab.
                                    </div>
                                </div>
                            </div>
                            <h3 class="text-2xl md:text-3xl font-black text-slate-900 dark:text-white mt-2 transition-colors">{{ $totalLabsAttempted ?? 0 }}x</h3>
                            <p class="text-[9px] text-indigo-600 dark:text-indigo-400 mt-2 opacity-0 group-hover:opacity-100 transition-all translate-y-2 group-hover:translate-y-0">View All Attempts &rarr;</p>
                        </div>

                        {{-- Card 3: Passed Labs --}}
                        <div class="glass-card rounded-2xl p-5 border-l-4 border-l-emerald-500 cursor-pointer group transition-all" @click="showPassedModal = true">
                            <div class="flex justify-between items-start">
                                <p class="text-[10px] font-bold text-slate-500 dark:text-white/40 uppercase tracking-widest group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">Modul Lulus</p>
                                <div class="tooltip-container tooltip-emerald tooltip-down tooltip-left">
                                    <div class="tooltip-trigger">?</div>
                                    <div class="tooltip-content">
                                        <span class="block font-bold text-emerald-600 dark:text-emerald-400 mb-1 border-b border-slate-200 dark:border-white/10 pb-1">Passed Modules</span>
                                        Jumlah modul praktikum yang status akhirnya divalidasi Lulus.
                                    </div>
                                </div>
                            </div>
                            <h3 class="text-2xl md:text-3xl font-black text-slate-900 dark:text-white mt-2 transition-colors">{{ $passedLabs ?? 0 }}</h3>
                            <p class="text-[9px] text-emerald-600 dark:text-emerald-400 mt-2 opacity-0 group-hover:opacity-100 transition-all translate-y-2 group-hover:translate-y-0">View Passed Data &rarr;</p>
                        </div>

                        {{-- Card 4: Total Time Spent --}}
                        <div class="glass-card rounded-2xl p-5 border-l-4 border-l-cyan-500 cursor-pointer group transition-all" @click="showTimeModal = true">
                            <div class="flex justify-between items-start">
                                <p class="text-[10px] font-bold text-slate-500 dark:text-white/40 uppercase tracking-widest group-hover:text-cyan-600 dark:group-hover:text-cyan-400 transition-colors">Coding Time</p>
                                <div class="tooltip-container tooltip-cyan tooltip-down tooltip-left">
                                    <div class="tooltip-trigger">?</div>
                                    <div class="tooltip-content">
                                        <span class="block font-bold text-cyan-600 dark:text-cyan-400 mb-1 border-b border-slate-200 dark:border-white/10 pb-1">Time Spent</span>
                                        Total durasi jam terbang siswa selama mengerjakan coding praktikum.
                                    </div>
                                </div>
                            </div>
                            <h3 class="text-2xl md:text-3xl font-black text-cyan-600 dark:text-cyan-400 mt-2 font-mono tracking-tight transition-colors">{{ $totalTimeSpent ?? '00:00' }}</h3>
                            <p class="text-[9px] text-cyan-600 dark:text-cyan-400 mt-2 opacity-0 group-hover:opacity-100 transition-all translate-y-2 group-hover:translate-y-0">View Duration Logs &rarr;</p>
                        </div>
                    </div>

                    {{-- B. PERSONAL CHARTS --}}
                    <div class="grid md:grid-cols-3 gap-6 reveal" style="animation-delay: 0.2s;">
                        
                        {{-- Score Trend --}}
                        <div class="md:col-span-2 glass-card rounded-2xl p-6 flex flex-col relative overflow-hidden transition-colors">
                            <div class="flex justify-between items-center mb-6 relative z-10">
                                <h3 class="text-lg font-bold text-slate-900 dark:text-white transition-colors">Riwayat Nilai Terakhir</h3>
                                <span class="text-[10px] bg-indigo-100 dark:bg-indigo-500/10 text-indigo-700 dark:text-indigo-400 px-3 py-1 rounded-full border border-indigo-200 dark:border-indigo-500/20 font-bold tracking-widest uppercase transition-colors">Progression</span>
                            </div>
                            <div class="flex-1 w-full h-[280px] relative z-10">
                                <canvas id="scoreChart"></canvas>
                            </div>
                            <div class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-indigo-100/50 dark:from-indigo-900/10 to-transparent pointer-events-none transition-colors"></div>
                        </div>

                        {{-- Pass/Fail Ratio --}}
                        <div class="glass-card rounded-2xl p-6 flex flex-col justify-center items-center relative overflow-hidden transition-colors">
                             <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4 w-full text-left transition-colors">Rasio Keberhasilan</h3>
                            <div class="relative w-48 h-48">
                                <canvas id="statusChart"></canvas>
                                <div class="absolute inset-0 flex items-center justify-center pointer-events-none flex-col">
                                    <span class="text-3xl font-black text-slate-900 dark:text-white transition-colors">{{ $totalLabsAttempted ?? 0 }}</span>
                                    <span class="text-[10px] text-slate-500 dark:text-white/40 uppercase tracking-widest font-bold transition-colors">Attempts</span>
                                </div>
                            </div>
                            <div class="flex gap-4 mt-6 text-xs">
                                <div class="flex items-center gap-2 font-bold text-slate-600 dark:text-white/60 transition-colors"><span class="w-3 h-3 rounded-full bg-emerald-500 shadow-[0_0_10px_#10b981]"></span> Lulus</div>
                                <div class="flex items-center gap-2 font-bold text-slate-600 dark:text-white/60 transition-colors"><span class="w-3 h-3 rounded-full bg-red-500 shadow-[0_0_10px_#ef4444]"></span> Gagal</div>
                            </div>
                        </div>
                    </div>

                    {{-- C. HISTORY TABLE (SCROLLABLE & POLISHED) --}}
                    <div class="glass-card rounded-2xl overflow-hidden reveal transition-colors flex flex-col" style="animation-delay: 0.3s;">
                        <div class="p-6 border-b border-slate-200 dark:border-white/5 bg-slate-50/50 dark:bg-[#020617]/40 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 transition-colors shrink-0">
                            <div class="flex items-center gap-3">
                                 <h3 class="text-lg font-bold text-slate-900 dark:text-white transition-colors">Log Pengerjaan Modul</h3>
                                 <span class="px-2 py-0.5 rounded text-[10px] bg-slate-200 dark:bg-white/10 text-slate-600 dark:text-white/60 font-mono transition-colors">{{ isset($histories) ? collect($histories)->count() : 0 }} Records</span>
                            </div>
                        </div>
                        
                        {{-- Wrapper untuk Scrollable Table --}}
                        <div class="overflow-x-auto overflow-y-auto custom-scrollbar max-h-[450px] relative">
                            <table class="w-full text-sm text-left border-collapse min-w-[700px]">
                                {{-- Sticky Header --}}
                                <thead class="bg-slate-100 dark:bg-[#0f141e] text-slate-500 dark:text-white/40 text-[10px] uppercase font-bold sticky top-0 z-20 shadow-sm dark:shadow-md transition-colors after:absolute after:inset-x-0 after:bottom-0 after:border-b after:border-slate-200 dark:after:border-white/5">
                                    <tr>
                                        <th class="px-6 py-4 transition-colors">Module Name</th>
                                        <th class="px-6 py-4 text-center transition-colors">Status</th>
                                        <th class="px-6 py-4 text-center transition-colors">Score</th>
                                        <th class="px-6 py-4 text-center transition-colors">Duration</th>
                                        <th class="px-6 py-4 text-center transition-colors">Date</th>
                                        <th class="px-6 py-4 text-right transition-colors">Code Snapshot</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200 dark:divide-white/5 bg-white/50 dark:bg-[#0a0e17]/30 transition-colors">
                                    @forelse($histories ?? [] as $h)
                                    <tr class="table-row transition-colors group">
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-slate-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{{ $h->lab_title ?? 'Lab Modul' }}</div>
                                            <div class="text-[10px] text-slate-500 dark:text-white/30 font-mono mt-0.5 transition-colors">ID: {{ $h->id ?? '-' }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-widest border transition-colors {{ ($h->status ?? '') == 'passed' ? 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border-emerald-200 dark:border-emerald-500/20' : 'bg-red-50 dark:bg-red-500/10 text-red-700 dark:text-red-400 border-red-200 dark:border-red-500/20' }}">
                                                {{ $h->status ?? 'unknown' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="text-base font-black transition-colors {{ ($h->final_score ?? 0) >= 70 ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-400 dark:text-white/40' }}">
                                                {{ $h->final_score ?? 0 }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center text-slate-600 dark:text-white/60 font-mono text-xs transition-colors">
                                            {{ gmdate("i:s", $h->duration_seconds ?? 0) }}s
                                        </td>
                                        <td class="px-6 py-4 text-center text-slate-500 dark:text-white/40 text-xs transition-colors">
                                            {{ \Carbon\Carbon::parse($h->created_at)->format('d M Y, H:i') }}
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            @if(!empty($h->last_code_snapshot))
                                                <button onclick="openCodeModal(`{{ base64_encode($h->last_code_snapshot) }}`, '{{ addslashes($h->lab_title ?? 'Lab') }}')" 
                                                    class="px-4 py-2 rounded-lg bg-indigo-50 dark:bg-indigo-600/10 hover:bg-indigo-600 dark:hover:bg-indigo-600 text-indigo-600 dark:text-indigo-400 hover:text-white border border-indigo-200 dark:border-indigo-600/30 text-[10px] font-bold transition-all shadow-sm dark:shadow-inner transform hover:-translate-y-0.5">
                                                    View Code
                                                </button>
                                            @else
                                                <span class="text-[10px] text-slate-400 dark:text-white/20 italic transition-colors">No Snapshot</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="py-20 text-center">
                                            <div class="flex flex-col items-center justify-center opacity-60">
                                                <svg class="w-12 h-12 text-slate-400 dark:text-white/20 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                                <p class="text-slate-500 dark:text-white/40 text-sm font-bold transition-colors">Belum ada riwayat pengerjaan modul.</p>
                                                <p class="text-slate-400 dark:text-white/30 text-xs mt-1 transition-colors">Siswa ini belum menyelesaikan satupun tugas lab.</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </main>
    </div>

    {{-- ==================== HERO MODALS (INSIGHT DATA PER CARD) ==================== --}}

    {{-- 1. Modal: Average Score Detail --}}
    <div x-show="showAvgScoreModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-slate-900/80 dark:bg-[#020617]/95 backdrop-blur-md transition-colors" @click="showAvgScoreModal = false"></div>
        <div class="relative w-full max-w-lg bg-white dark:bg-[#0f141e] border border-amber-200 dark:border-yellow-500/40 rounded-3xl shadow-2xl dark:shadow-[0_30px_100px_rgba(234,179,8,0.15)] p-6 md:p-8 transition-colors" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
            <div class="flex justify-between items-center mb-6 border-b border-slate-200 dark:border-white/5 pb-4 transition-colors">
                <div>
                    <h3 class="text-lg md:text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2 transition-colors">
                        <svg class="w-6 h-6 text-amber-500 dark:text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 001.902 0l1.519-4.674z"/></svg>
                        Average Score Detail
                    </h3>
                    <p class="text-[10px] text-amber-600 dark:text-yellow-400 mt-1 font-mono uppercase tracking-widest transition-colors">Global data for {{ $user->name ?? 'Student' }}</p>
                </div>
                <button @click="showAvgScoreModal = false" class="text-slate-400 hover:text-slate-900 dark:text-white/40 dark:hover:text-white transition-colors bg-slate-100 dark:bg-white/5 hover:bg-red-100 dark:hover:bg-red-500/20 p-2 rounded-full border border-transparent hover:border-red-200 dark:hover:border-red-500/30"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            <div class="text-center py-6">
                <div class="inline-block p-8 rounded-full bg-amber-50 dark:bg-yellow-500/10 border border-amber-200 dark:border-yellow-500/20 mb-6 shadow-sm dark:shadow-[0_0_40px_rgba(234,179,8,0.2)] transition-colors">
                    <span class="text-6xl font-black text-amber-500 dark:text-yellow-400 transition-colors">{{ $globalAvgScore ?? 0 }}</span>
                </div>
                <p class="text-slate-600 dark:text-white/60 text-sm leading-relaxed max-w-sm mx-auto transition-colors">Ini adalah kalkulasi nilai rata-rata dari seluruh aktivitas evaluasi praktik (Lab) dan kuis yang telah diselesaikan oleh siswa ini.</p>
            </div>
        </div>
    </div>

    {{-- 2. Modal: Total Labs Detail --}}
    <div x-show="showTotalLabsModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-slate-900/80 dark:bg-[#020617]/95 backdrop-blur-md transition-colors" @click="showTotalLabsModal = false"></div>
        <div class="relative w-full max-w-2xl bg-white dark:bg-[#0f141e] border border-indigo-200 dark:border-indigo-500/40 rounded-3xl shadow-2xl dark:shadow-[0_30px_100px_rgba(99,102,241,0.15)] p-6 md:p-8 transition-colors" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
            <div class="flex justify-between items-center mb-6 border-b border-slate-200 dark:border-white/5 pb-4 transition-colors">
                <div>
                    <h3 class="text-lg md:text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2 transition-colors">
                        <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                        All Lab Attempts
                    </h3>
                    <p class="text-[10px] text-indigo-600 dark:text-indigo-400 mt-1 font-mono uppercase tracking-widest transition-colors">Riwayat percobaan lab lengkap</p>
                </div>
                <button @click="showTotalLabsModal = false" class="text-slate-400 hover:text-slate-900 dark:text-white/40 dark:hover:text-white transition-colors bg-slate-100 dark:bg-white/5 hover:bg-red-100 dark:hover:bg-red-500/20 p-2 rounded-full border border-transparent hover:border-red-200 dark:hover:border-red-500/30"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            <div class="max-h-[60vh] overflow-y-auto custom-scrollbar space-y-3 pr-2">
                @forelse($histories ?? [] as $h)
                <div class="flex items-center justify-between gap-4 p-4 rounded-xl bg-slate-50 dark:bg-[#0a0e17]/80 border border-slate-200 dark:border-white/5 hover:border-indigo-300 dark:hover:border-indigo-500/30 transition-colors group shadow-sm dark:shadow-inner">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-slate-900 dark:text-white truncate group-hover:text-indigo-600 dark:group-hover:text-indigo-300 transition-colors">{{ $h->lab_title ?? 'Lab Modul' }}</p>
                        <p class="text-[10px] text-slate-500 dark:text-white/50 font-mono mt-1 transition-colors">{{ \Carbon\Carbon::parse($h->created_at)->format('d M Y, H:i') }}</p>
                    </div>
                    <div class="text-right shrink-0">
                        <span class="inline-flex items-center px-2.5 py-1 rounded text-[9px] font-bold uppercase tracking-widest border transition-colors {{ ($h->status ?? '') == 'passed' ? 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border-emerald-200 dark:border-emerald-500/20' : 'bg-red-50 dark:bg-red-500/10 text-red-700 dark:text-red-400 border-red-200 dark:border-red-500/20' }}">
                            {{ $h->status ?? 'unknown' }}
                        </span>
                        <p class="text-[10px] text-slate-500 dark:text-white/40 mt-1 font-mono transition-colors">Score: {{ $h->final_score ?? 0 }}</p>
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

    {{-- 3. Modal: Passed Labs Detail --}}
    <div x-show="showPassedModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-slate-900/80 dark:bg-[#020617]/95 backdrop-blur-md transition-colors" @click="showPassedModal = false"></div>
        <div class="relative w-full max-w-2xl bg-white dark:bg-[#0f141e] border border-emerald-200 dark:border-emerald-500/40 rounded-3xl shadow-2xl dark:shadow-[0_30px_100px_rgba(16,185,129,0.15)] p-6 md:p-8 transition-colors" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
            <div class="flex justify-between items-center mb-6 border-b border-slate-200 dark:border-white/5 pb-4 transition-colors">
                <div>
                    <h3 class="text-lg md:text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2 transition-colors">
                        <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Passed Modules
                    </h3>
                    <p class="text-[10px] text-emerald-600 dark:text-emerald-400 mt-1 font-mono uppercase tracking-widest transition-colors">Modul yang berhasil divalidasi</p>
                </div>
                <button @click="showPassedModal = false" class="text-slate-400 hover:text-slate-900 dark:text-white/40 dark:hover:text-white transition-colors bg-slate-100 dark:bg-white/5 hover:bg-red-100 dark:hover:bg-red-500/20 p-2 rounded-full border border-transparent hover:border-red-200 dark:hover:border-red-500/30"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            <div class="max-h-[60vh] overflow-y-auto custom-scrollbar space-y-3 pr-2">
                @forelse(collect($histories ?? [])->where('status', 'passed') as $h)
                <div class="flex items-center justify-between gap-4 p-4 rounded-xl bg-slate-50 dark:bg-[#0a0e17]/80 border border-slate-200 dark:border-white/5 hover:border-emerald-300 dark:hover:border-emerald-500/30 transition-colors group shadow-sm dark:shadow-inner">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-slate-900 dark:text-white truncate group-hover:text-emerald-600 dark:group-hover:text-emerald-300 transition-colors">{{ $h->lab_title ?? 'Lab Modul' }}</p>
                        <p class="text-[10px] text-slate-500 dark:text-white/50 font-mono mt-1 transition-colors">Score: {{ $h->final_score ?? 0 }}</p>
                    </div>
                    <div class="text-right shrink-0">
                        <span class="text-[10px] font-bold text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10 px-3 py-1.5 rounded-lg border border-emerald-200 dark:border-emerald-500/20 uppercase tracking-widest transition-colors"><svg class="w-3 h-3 inline-block mr-1 -mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Validated</span>
                    </div>
                </div>
                @empty
                <div class="text-center py-10 bg-slate-50 dark:bg-[#0a0e17]/50 rounded-xl border border-dashed border-slate-300 dark:border-white/10 transition-colors">
                    <p class="text-[11px] text-slate-500 dark:text-white/40 italic transition-colors">Belum ada modul yang lulus.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- 4. Modal: Time Spent Detail --}}
    <div x-show="showTimeModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-slate-900/80 dark:bg-[#020617]/95 backdrop-blur-md transition-colors" @click="showTimeModal = false"></div>
        <div class="relative w-full max-w-lg bg-white dark:bg-[#0f141e] border border-cyan-200 dark:border-cyan-500/40 rounded-3xl shadow-2xl dark:shadow-[0_30px_100px_rgba(6,182,212,0.15)] p-6 md:p-8 transition-colors" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
            <div class="flex justify-between items-center mb-6 border-b border-slate-200 dark:border-white/5 pb-4 transition-colors">
                <div>
                    <h3 class="text-lg md:text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2 transition-colors">
                        <svg class="w-6 h-6 text-cyan-600 dark:text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Coding Duration Analysis
                    </h3>
                </div>
                <button @click="showTimeModal = false" class="text-slate-400 hover:text-slate-900 dark:text-white/40 dark:hover:text-white transition-colors bg-slate-100 dark:bg-white/5 hover:bg-red-100 dark:hover:bg-red-500/20 p-2 rounded-full border border-transparent hover:border-red-200 dark:hover:border-red-500/30"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            <div class="text-center py-6">
                <div class="inline-block p-8 rounded-full bg-cyan-50 dark:bg-cyan-500/10 border border-cyan-200 dark:border-cyan-500/20 mb-6 shadow-sm dark:shadow-[0_0_40px_rgba(6,182,212,0.2)] transition-colors">
                    <span class="text-5xl md:text-6xl font-black text-cyan-600 dark:text-cyan-400 font-mono transition-colors">{{ $totalTimeSpent ?? '00:00' }}</span>
                </div>
                <p class="text-slate-600 dark:text-white/60 text-sm leading-relaxed max-w-sm mx-auto transition-colors">Akumulasi total waktu aktif yang dihabiskan siswa dalam menyelesaikan seluruh modul praktikum di platform.</p>
            </div>
        </div>
    </div>

    {{-- ==================== MODAL: CODE VIEWER ==================== --}}
    <div id="codeModal" class="fixed inset-0 z-[999999] hidden flex items-center justify-center p-4 md:p-6 lg:p-10">
        <div class="absolute inset-0 bg-slate-900/80 dark:bg-[#020617]/95 backdrop-blur-md transition-opacity" onclick="closeCodeModal()"></div>
        <div class="relative w-full max-w-4xl bg-white dark:bg-[#0f141e] border border-slate-200 dark:border-white/10 rounded-2xl shadow-2xl dark:shadow-[0_30px_100px_rgba(0,0,0,1)] flex flex-col max-h-[85vh] overflow-hidden transform transition-all scale-95 opacity-0" id="codeModalContent">
            
            <div class="p-4 border-b border-slate-200 dark:border-white/10 flex justify-between items-center bg-slate-100 dark:bg-[#0a0e17] transition-colors shrink-0">
                <div class="flex items-center gap-3">
                    <div class="flex gap-1.5">
                        <span class="w-3 h-3 rounded-full bg-red-500 shadow-[0_0_5px_#ef4444]"></span>
                        <span class="w-3 h-3 rounded-full bg-yellow-500 shadow-[0_0_5px_#eab308]"></span>
                        <span class="w-3 h-3 rounded-full bg-emerald-500 shadow-[0_0_5px_#10b981]"></span>
                    </div>
                    <span class="text-xs font-mono text-slate-500 dark:text-white/60 ml-2 font-bold tracking-widest transition-colors" id="codeModalTitle">snapshot.html</span>
                </div>
                <button onclick="closeCodeModal()" class="text-slate-400 hover:text-slate-900 dark:text-white/40 dark:hover:text-white transition-colors bg-slate-200 dark:bg-white/5 hover:bg-red-100 dark:hover:bg-red-500/20 p-1.5 rounded-md border border-transparent hover:border-red-200 dark:hover:border-red-500/30"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>

            <div class="flex-1 overflow-y-auto bg-slate-50 dark:bg-[#0a0e17] custom-scrollbar p-0 transition-colors">
                <pre class="code-block m-0 min-h-[50vh] border-0 rounded-none shadow-none"><code id="codeContainer" class="language-html whitespace-pre-wrap break-words"></code></pre>
            </div>
        </div>
    </div>

    {{-- SCRIPTS (CHARTS & THEME SWITCHER) --}}
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
            // CHARTS INITIALIZATION (Adaptif Tema)
            // ==========================================
            const ctxScore = document.getElementById('scoreChart');
            const ctxStatus = document.getElementById('statusChart');
            let scoreChart, statusChart;

            function initCharts() {
                const isDark = document.documentElement.classList.contains('dark');
                
                // Ambil nilai color dari CSS variable
                const gridColor = getComputedStyle(document.documentElement).getPropertyValue('--chart-grid').trim();
                const tickColor = getComputedStyle(document.documentElement).getPropertyValue('--chart-tick').trim();
                const donutBorder = isDark ? '#020617' : '#ffffff';
                const trendPointBg = isDark ? '#0f141e' : '#ffffff';
                const trendLineColor = isDark ? '#818cf8' : '#6366f1';

                // 1. SCORE CHART (Line)
                if(ctxScore) {
                    const gradient = ctxScore.getContext('2d').createLinearGradient(0, 0, 0, 300);
                    gradient.addColorStop(0, isDark ? 'rgba(129, 140, 248, 0.4)' : 'rgba(99, 102, 241, 0.2)');
                    gradient.addColorStop(1, 'rgba(99, 102, 241, 0)');

                    if (scoreChart) scoreChart.destroy();

                    scoreChart = new Chart(ctxScore.getContext('2d'), {
                        type: 'line',
                        data: {
                            labels: {!! json_encode($chartLabels ?? []) !!},
                            datasets: [{
                                label: 'Nilai Akhir',
                                data: {!! json_encode($chartScores ?? []) !!},
                                borderColor: trendLineColor,
                                backgroundColor: gradient,
                                borderWidth: 3,
                                pointBackgroundColor: trendPointBg,
                                pointBorderColor: trendLineColor,
                                pointBorderWidth: 2,
                                pointRadius: 4,
                                fill: true,
                                tension: 0.4
                            }]
                        },
                        options: {
                            responsive: true, maintainAspectRatio: false,
                            plugins: { legend: {display: false} },
                            scales: {
                                x: { display: false },
                                y: { beginAtZero: true, max: 100, grid: { color: gridColor }, ticks: { color: tickColor, font: {size: 10, family: 'JetBrains Mono'} } }
                            },
                            interaction: { mode: 'index', intersect: false }
                        }
                    });
                }

                // 2. STATUS CHART (Doughnut)
                if(ctxStatus) {
                    if (statusChart) statusChart.destroy();

                    statusChart = new Chart(ctxStatus.getContext('2d'), {
                        type: 'doughnut',
                        data: {
                            labels: ['Passed', 'Failed'],
                            datasets: [{
                                data: [{{ $passedLabs ?? 0 }}, {{ $failedLabs ?? 0 }}],
                                backgroundColor: ['#10b981', '#ef4444'],
                                borderColor: donutBorder,
                                borderWidth: 4
                            }]
                        },
                        options: {
                            responsive: true, maintainAspectRatio: false, cutout: '75%',
                            plugins: { legend: {display: false} }
                        }
                    });
                }
            }

            initCharts();

            // Render ulang chart saat tema berganti
            window.addEventListener('theme-toggled', () => {
                initCharts();
            });
        });

        // ==========================================
        // MODAL LOGIC (CODE VIEWER)
        // ==========================================
        function openCodeModal(encodedCode, title) {
            const code = atob(encodedCode); 
            document.getElementById('codeContainer').textContent = code;
            document.getElementById('codeModalTitle').innerText = title + " - Snapshot";
            
            const modal = document.getElementById('codeModal');
            const content = document.getElementById('codeModalContent');
            modal.classList.remove('hidden');
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeCodeModal() {
            const modal = document.getElementById('codeModal');
            const content = document.getElementById('codeModalContent');
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            setTimeout(() => { modal.classList.add('hidden'); }, 300);
        }
    </script>

</body>
</html>