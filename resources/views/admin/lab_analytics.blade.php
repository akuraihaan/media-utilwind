<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lab Intelligence · Global Overview</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    
    {{-- RESOURCES --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

    <style>
        /* --- THEME CONFIG (ORIGINAL DARK-GLASSMORPHISM) --- */
        :root { 
            --glass-bg: rgba(10, 14, 23, 0.85); 
            --glass-border: rgba(255, 255, 255, 0.08); 
            --accent: #6366f1; 
        }
        body { font-family: 'Inter', sans-serif; background-color: #020617; color: #e2e8f0; overflow-x: hidden; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }

        /* --- SCROLLBAR --- */
        .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: var(--accent); }

        /* --- GLASS COMPONENTS --- */
        .glass-sidebar { background: rgba(5, 8, 16, 0.95); backdrop-filter: blur(20px); border-right: 1px solid var(--glass-border); z-index: 50; }
        .glass-header { background: rgba(2, 6, 23, 0.85); backdrop-filter: blur(12px); border-bottom: 1px solid var(--glass-border); z-index: 40; }
        
        .glass-card {
            background: var(--glass-bg); border: 1px solid var(--glass-border);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.2); backdrop-filter: blur(10px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative; 
            overflow: visible !important; /* MEMASTIKAN TOOLTIP TIDAK TERPOTONG */
            z-index: 10;
        }
        .glass-card:hover { border-color: rgba(99, 102, 241, 0.4); transform: translateY(-3px); box-shadow: 0 10px 40px -10px rgba(0,0,0,0.5); z-index: 30; }
        
        /* Container SVG Background Card */
        .card-bg-gfx { position: absolute; inset: 0; overflow: hidden; border-radius: 1rem; pointer-events: none; z-index: 0; }

        /* --- INPUTS & NAV --- */
        .glass-input { background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.1); color: white; transition: 0.3s; }
        .glass-input:focus { border-color: var(--accent); background: rgba(255, 255, 255, 0.05); outline: none; box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2); }
        
        .nav-link { display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 12px; color: #94a3b8; font-weight: 500; font-size: 0.875rem; transition: all 0.2s; border: 1px solid transparent; }
        .nav-link:hover { background: rgba(255, 255, 255, 0.03); color: white; }
        .nav-link.active { background: linear-gradient(90deg, rgba(99, 102, 241, 0.1) 0%, rgba(99, 102, 241, 0) 100%); color: #818cf8; border-left: 3px solid #818cf8; border-radius: 4px 12px 12px 4px; }

        .reveal { opacity: 0; transform: translateY(15px); animation: revealAnim 0.5s forwards ease-out; }
        @keyframes revealAnim { to { opacity: 1; transform: translateY(0); } }
        .table-row { transition: background 0.2s; border-bottom: 1px solid rgba(255,255,255,0.03); }
        .table-row:hover { background: rgba(255,255,255,0.05); }

        /* =========================================================================
           SISTEM TOOLTIP SUPER SOLID (DIJAMIN KELIHATAN 100%)
           ========================================================================= */
        .tooltip-container { position: relative; display: inline-flex; align-items: center; justify-content: center; z-index: 50; }
        .tooltip-container:hover { z-index: 99999; }
        .tooltip-trigger { 
            width: 18px; height: 18px; border-radius: 50%; color: white; 
            font-size: 11px; font-weight: 900; display: flex; align-items: center; justify-content: center; 
            cursor: help; transition: all 0.2s; border: 1px solid rgba(255,255,255,0.2);
        }
        .tooltip-trigger:hover { transform: scale(1.15); }
        .tooltip-content { 
            opacity: 0; visibility: hidden; position: absolute; pointer-events: none; 
            width: max-content; min-width: 220px; max-width: 280px; white-space: normal; text-align: left; 
            background-color: #020617; 
            color: #e2e8f0; font-size: 11px; padding: 14px 16px; line-height: 1.5;
            border-radius: 12px; box-shadow: 0 20px 60px rgba(0,0,0,1); z-index: 99999;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
        }

        .tooltip-down .tooltip-content { top: calc(100% + 12px); left: 50%; transform: translateX(-50%) translateY(-10px); }
        .tooltip-down:hover .tooltip-content { transform: translateX(-50%) translateY(0); opacity: 1; visibility: visible; }
        .tooltip-down .tooltip-content::after { content: ''; position: absolute; bottom: 100%; left: 50%; margin-left: -6px; border-width: 6px; border-style: solid; border-color: transparent transparent #020617 transparent; }
        
        .tooltip-left .tooltip-content { left: auto; right: -12px; transform: translateX(0) translateY(-10px); }
        .tooltip-down.tooltip-left:hover .tooltip-content { transform: translateX(0) translateY(0); }
        .tooltip-left .tooltip-content::after { left: auto; right: 15px; margin-left: 0; }

        .tooltip-indigo .tooltip-trigger { background-color: #6366f1; box-shadow: 0 0 10px rgba(99,102,241,0.5); }
        .tooltip-indigo .tooltip-trigger:hover { background-color: #818cf8; box-shadow: 0 0 15px rgba(99,102,241,0.8); }
        .tooltip-indigo .tooltip-content { border: 1px solid rgba(99,102,241,0.5); }

        .tooltip-emerald .tooltip-trigger { background-color: #10b981; box-shadow: 0 0 10px rgba(16,185,129,0.5); }
        .tooltip-emerald .tooltip-trigger:hover { background-color: #34d399; box-shadow: 0 0 15px rgba(16,185,129,0.8); }
        .tooltip-emerald .tooltip-content { border: 1px solid rgba(16,185,129,0.5); }

        .tooltip-yellow .tooltip-trigger { background-color: #eab308; color: #020617; box-shadow: 0 0 10px rgba(234,179,8,0.5); }
        .tooltip-yellow .tooltip-trigger:hover { background-color: #facc15; box-shadow: 0 0 15px rgba(234,179,8,0.8); }
        .tooltip-yellow .tooltip-content { border: 1px solid rgba(234,179,8,0.5); }

        .tooltip-cyan .tooltip-trigger { background-color: #06b6d4; box-shadow: 0 0 10px rgba(6,182,212,0.5); }
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
}" @keydown.escape.window="isFullscreen = false; document.exitFullscreen(); showAttemptsModal = false; showSuccessRateModal = false; showAvgScoreModal = false; showDurationModal = false;" :class="{'modal-open': sidebarOpen || showAttemptsModal || showSuccessRateModal || showAvgScoreModal || showDurationModal}">

    {{-- ==============================================================================
         LOGIKA DATA BLADE (BULLETPROOF FALLBACK)
         Menggunakan Variabel Asli dari AdminQuizController@analytics
         ============================================================================== --}}
    @php
        $totalAttempts = $totalAttempts ?? 0;
        $passedCount = $passedCount ?? 0;
        $failedCount = $failedCount ?? 0;
        $completionRate = $completionRate ?? 0;
        $avgScore = $avgScore ?? 0;
        $avgDuration = $avgDuration ?? '00:00';
        
        $userPerformance = isset($userPerformance) ? collect($userPerformance) : collect([]);
        $labsList = isset($labsList) ? collect($labsList) : collect([]);
        
        $chartLabels = $chartLabels ?? [];
        $chartData = $chartData ?? [];
    @endphp

    <div class="flex h-screen w-full relative">

        <div x-show="sidebarOpen" class="fixed inset-0 bg-[#020617]/80 backdrop-blur-sm z-[90] md:hidden" @click="sidebarOpen = false" x-transition.opacity style="display: none;"></div>

    <aside class="glass-sidebar w-72 h-full flex flex-col fixed md:relative z-[100] transition-transform duration-300 transform md:translate-x-0" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
        <div class="h-24 flex items-center justify-between px-8 border-b border-white/5 relative overflow-hidden group">
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-20 h-20 bg-indigo-500/20 rounded-full blur-[40px] opacity-0 group-hover:opacity-100 transition duration-500"></div>
            <a href="{{ route('landing') }}" class="flex items-center gap-3 relative z-10">
                <img src="{{ asset('images/logo.png') }}" onerror="this.src='https://via.placeholder.com/40'" class="h-9 w-auto object-contain">
                <div>
                    <h1 class="text-lg font-black text-white tracking-tight leading-none">Util<span class="text-indigo-400">wind</span></h1>
                    <span class="text-[10px] font-bold text-white/40 tracking-[0.2em] uppercase">Admin Panel</span>
                </div>
            </a>
            <button @click="sidebarOpen = false" class="md:hidden text-white/50 hover:text-white relative z-10">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <nav class="flex-1 overflow-y-auto custom-scrollbar py-8 px-4 space-y-8">
            <div>
                <p class="px-4 text-[10px] font-extrabold text-white/30 uppercase tracking-widest mb-3">Overview</p>
                <div class="space-y-1">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.dashboard') ? 'text-indigo-400' : 'text-slate-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                        Dashboard
                    </a>
                </div>
            </div>

            <div>
                <p class="px-4 text-[10px] font-extrabold text-white/30 uppercase tracking-widest mb-3">Academic</p>
                <div class="space-y-1">
                    <a href="{{ route('admin.analytics.questions') }}" class="nav-link {{ request()->routeIs('admin.analytics.questions') ? 'active' : '' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.analytics.questions') ? 'text-indigo-400' : 'text-slate-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                        Quiz Management
                    </a>
                    <a href="{{ route('admin.labs.index') }}" class="nav-link {{ request()->routeIs('admin.labs.index') ? 'active' : '' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.labs.index') ? 'text-indigo-400' : 'text-slate-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                        Lab Configuration
                    </a>
                    <a href="{{ route('admin.lab.analytics') }}" class="nav-link active {{ request()->routeIs('admin.lab.analytics') ? 'active' : '' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.lab.analytics') ? 'text-indigo-400' : 'text-slate-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
                        Lab Analytics
                    </a>
                    {{-- MENU BARU: CLASS MANAGEMENT --}}
                    <a href="{{ route('admin.classes.index') }}" class="nav-link {{ request()->routeIs('admin.classes.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.classes.*') ? 'text-indigo-400' : 'text-slate-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        Class Management
                    </a>
                </div>
            </div>
        </nav>

        <div class="p-4 border-t border-white/5 bg-[#05080f]/50">
            <div class="flex items-center gap-3 mb-4 px-2">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-fuchsia-500 flex items-center justify-center font-bold text-white text-xs border border-white/10 shadow-lg">AD</div>
                <div class="overflow-hidden">
                    <p class="text-xs font-bold text-white truncate">{{ Auth::user()->name ?? 'Administrator' }}</p>
                    <p class="text-[10px] text-white/40 truncate">System Admin</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500 hover:text-white transition text-xs font-bold border border-red-500/20 hover:border-red-500 group">
                    <svg class="w-3.5 h-3.5 transition group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

        {{-- ==================== MAIN CONTENT ==================== --}}
        <main class="flex-1 flex flex-col relative z-10 bg-[#020617] h-full overflow-y-auto overflow-x-hidden">
            
            {{-- Background FX --}}
            <div class="fixed inset-0 pointer-events-none z-0">
                <div class="absolute top-[10%] left-[20%] w-[500px] h-[500px] bg-indigo-600/10 rounded-full blur-[120px]"></div>
                <div class="absolute bottom-[10%] right-[10%] w-[400px] h-[400px] bg-cyan-600/10 rounded-full blur-[120px]"></div>
                <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.04] mix-blend-overlay"></div>
            </div>

            {{-- HEADER RESPONSIVE & BREADCRUMB IDENTIK DASHBOARD --}}
            <header class="h-24 glass-header flex flex-col justify-center px-6 md:px-10 shrink-0 sticky top-0 z-40">
                <div class="flex items-center justify-between w-full">
                    <div class="flex items-center gap-4">
                        <button @click="sidebarOpen = true" class="md:hidden p-2 bg-white/5 rounded-lg text-white hover:bg-white/10 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        </button>
                        
                        <div>
                            <nav class="flex text-[10px] text-white/50 mb-1.5 font-bold hidden sm:flex" aria-label="Breadcrumb">
                                <ol class="inline-flex items-center space-x-1">
                                    <li class="inline-flex items-center"><a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-400 transition">Dashboard</a></li>
                                    <li>
                                        <div class="flex items-center">
                                            <svg class="w-3 h-3 text-white/30 mx-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                            <span class="text-white">Lab Analytics</span>
                                        </div>
                                    </li>
                                </ol>
                            </nav>
                            <h2 class="text-white font-bold text-lg md:text-xl tracking-tight">Lab Intelligence Center</h2>
                            <p class="text-[9px] md:text-xs text-white/40 flex items-center gap-1.5 mt-0.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse shadow-[0_0_8px_#10b981]"></span>
                                Global Overview & Monitoring
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3 sm:gap-6">
                        <button onclick="window.location.reload()" class="p-2.5 text-white/40 hover:text-white transition rounded-full hover:bg-white/5 group hidden sm:block border border-transparent hover:border-white/10" title="Refresh">
                            <svg class="w-4 h-4 group-hover:rotate-180 transition-transform duration-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        </button>
                        <button @click="isFullscreen = !isFullscreen; isFullscreen ? document.documentElement.requestFullscreen() : document.exitFullscreen()" class="p-2.5 text-white/40 hover:text-white transition rounded-full hover:bg-white/5 hidden md:block border border-transparent hover:border-white/10" title="Fullscreen">
                            <svg x-show="!isFullscreen" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                            <svg x-show="isFullscreen" style="display: none;" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                        <div class="text-right hidden lg:block border-l border-white/10 pl-5 ml-1">
                            <p class="text-sm font-bold text-white">{{ \Carbon\Carbon::now()->translatedFormat('d M Y') }}</p>
                            <p class="text-[10px] text-white/40 font-mono mt-0.5">{{ \Carbon\Carbon::now()->format('H:i') }} WIB</p>
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
                        
                        {{-- 1. Attempts --}}
                        <div class="glass-card rounded-2xl p-5 border-l-4 border-l-indigo-500 cursor-pointer group transition-all" @click="showAttemptsModal = true">
                            <div class="flex justify-between items-start">
                                <p class="text-[10px] font-bold text-white/40 uppercase tracking-widest group-hover:text-indigo-400 transition">Total Percobaan</p>
                                <div class="tooltip-container tooltip-indigo tooltip-down tooltip-left">
                                    <div class="tooltip-trigger bg-transparent border-transparent shadow-none text-white/30 group-hover:text-indigo-400">?</div>
                                    <div class="tooltip-content border-indigo-glow">
                                        <span class="block font-bold text-indigo-400 mb-1 border-b border-white/10 pb-1">Lab Attempts</span>
                                        Total akumulasi seluruh percobaan lab yang dilakukan siswa (Global).
                                    </div>
                                </div>
                            </div>
                            <h3 class="text-2xl md:text-3xl font-black text-white mt-2">{{ number_format($totalAttempts) }}</h3>
                            <div class="mt-3 flex gap-2">
                                <span class="bg-emerald-500/10 text-emerald-400 px-2 py-0.5 rounded text-[10px] font-bold border border-emerald-500/10">{{ $passedCount }} Pass</span>
                                <span class="bg-red-500/10 text-red-400 px-2 py-0.5 rounded text-[10px] font-bold border border-red-500/10">{{ $failedCount }} Fail</span>
                            </div>
                        </div>

                        {{-- 2. Success Rate --}}
                        <div class="glass-card rounded-2xl p-5 border-l-4 border-l-emerald-500 cursor-pointer group transition-all" @click="showSuccessRateModal = true">
                            <div class="flex justify-between items-start">
                                <p class="text-[10px] font-bold text-white/40 uppercase tracking-widest group-hover:text-emerald-400 transition">Rasio Kelulusan</p>
                                <div class="tooltip-container tooltip-emerald tooltip-down tooltip-left">
                                    <div class="tooltip-trigger bg-transparent border-transparent shadow-none text-white/30 group-hover:text-emerald-400">?</div>
                                    <div class="tooltip-content border-emerald-glow">
                                        <span class="block font-bold text-emerald-400 mb-1 border-b border-white/10 pb-1">Pass Ratio</span>
                                        Persentase modul praktikum yang diselesaikan dengan status lulus (Global).
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-baseline gap-1 mt-2">
                                <h3 class="text-2xl md:text-3xl font-black {{ $completionRate >= 70 ? 'text-emerald-400' : ($completionRate >= 50 ? 'text-yellow-400' : 'text-red-400') }}">
                                    {{ $completionRate }}
                                </h3>
                                <span class="text-lg font-bold {{ $completionRate >= 70 ? 'text-emerald-400' : ($completionRate >= 50 ? 'text-yellow-400' : 'text-red-400') }}">%</span>
                            </div>
                            <div class="w-full bg-white/10 h-1.5 mt-3 rounded-full overflow-hidden border border-white/5">
                                <div class="h-full {{ $completionRate >= 70 ? 'bg-emerald-500' : 'bg-red-500' }}" style="width: {{ $completionRate }}%"></div>
                            </div>
                        </div>

                        {{-- 3. Avg Score --}}
                        <div class="glass-card rounded-2xl p-5 border-l-4 border-l-yellow-500 cursor-pointer group transition-all" @click="showAvgScoreModal = true">
                            <div class="flex justify-between items-start">
                                <p class="text-[10px] font-bold text-white/40 uppercase tracking-widest group-hover:text-yellow-400 transition">Rata Rata Nilai</p>
                                <div class="tooltip-container tooltip-yellow tooltip-down tooltip-left">
                                    <div class="tooltip-trigger bg-transparent border-transparent shadow-none text-white/30 group-hover:text-yellow-400">?</div>
                                    <div class="tooltip-content border-yellow-glow">
                                        <span class="block font-bold text-yellow-400 mb-1 border-b border-white/10 pb-1">Global Average</span>
                                        Rata-rata nilai yang didapatkan dari seluruh pengerjaan modul lab.
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-baseline gap-1 mt-2">
                                <h3 class="text-2xl md:text-3xl font-black text-white">{{ $avgScore }}</h3>
                                <span class="text-[10px] text-yellow-400 font-bold"></span>
                            </div>
                            <p class="text-[9px] text-yellow-400 mt-2 opacity-0 group-hover:opacity-100 transition translate-y-2 group-hover:translate-y-0">View Insights &rarr;</p>
                        </div>

                        {{-- 4. Avg Time --}}
                        <div class="glass-card rounded-2xl p-5 border-l-4 border-l-cyan-500 cursor-pointer group transition-all" @click="showDurationModal = true">
                            <div class="flex justify-between items-start">
                                <p class="text-[10px] font-bold text-white/40 uppercase tracking-widest group-hover:text-cyan-400 transition">Rata Rata Durasi</p>
                                <div class="tooltip-container tooltip-cyan tooltip-down tooltip-left">
                                    <div class="tooltip-trigger bg-transparent border-transparent shadow-none text-white/30 group-hover:text-cyan-400">?</div>
                                    <div class="tooltip-content border-cyan-glow">
                                        <span class="block font-bold text-cyan-400 mb-1 border-b border-white/10 pb-1">Time per Session</span>
                                        Rata-rata waktu yang dihabiskan siswa per percobaan modul lab.
                                    </div>
                                </div>
                            </div>
                            <h3 class="text-2xl md:text-3xl font-black text-cyan-400 mt-2 font-mono tracking-tight">{{ $avgDuration }}</h3>
                            <p class="text-[9px] text-cyan-400 mt-2 opacity-0 group-hover:opacity-100 transition translate-y-2 group-hover:translate-y-0">View Time Logs &rarr;</p>
                        </div>
                    </div>

                    {{-- =======================================================
                         B. DUAL CHARTS
                         ======================================================= --}}
                    <div class="grid lg:grid-cols-3 gap-8 reveal" style="animation-delay: 0.2s;">
                        
                        {{-- Chart 1: Activity Trend --}}
                        <div class="lg:col-span-2 glass-card rounded-2xl p-6 flex flex-col relative overflow-hidden">
                            <div class="flex justify-between items-center mb-6 relative z-10">
                                <div>
                                    <h3 class="text-lg font-bold text-white">Tren Aktivitas Mingguan</h3>
                                    <p class="text-[10px] text-white/40 mt-0.5">Jumlah percobaan siswa 7 hari terakhir.</p>
                                </div>
                                <span class="text-[10px] bg-indigo-500/10 text-indigo-400 px-3 py-1 rounded-full border border-indigo-500/20 font-bold uppercase tracking-widest flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-pulse shadow-[0_0_5px_#6366f1]"></span>Live</span>
                            </div>
                            <div class="flex-1 w-full relative z-10 h-[300px]">
                                <canvas id="labTrendChart"></canvas>
                            </div>
                            <div class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-indigo-900/10 to-transparent pointer-events-none"></div>
                        </div>

                        {{-- Chart 2: Pass Ratio --}}
                        <div class="glass-card rounded-2xl p-6 flex flex-col items-center justify-center relative overflow-hidden border-t-2 border-emerald-500/30">
                            <h3 class="text-lg font-bold text-white mb-6 w-full text-left">Rasio Kelulusan Modul</h3>
                            <div class="relative w-48 h-48">
                                <canvas id="passFailChart"></canvas>
                                <div class="absolute inset-0 flex items-center justify-center flex-col pointer-events-none">
                                    <span class="text-3xl font-black text-white">{{ $totalAttempts }}</span>
                                    <span class="text-[10px] text-white/40 uppercase tracking-widest font-bold">Total</span>
                                </div>
                            </div>
                            <div class="flex justify-center gap-6 mt-8 w-full text-xs">
                                <div class="flex items-center gap-2 font-bold text-white/60"><span class="w-3 h-3 rounded-full bg-emerald-500 shadow-[0_0_10px_#10b981]"></span> Passed</div>
                                <div class="flex items-center gap-2 font-bold text-white/60"><span class="w-3 h-3 rounded-full bg-red-500 shadow-[0_0_10px_#ef4444]"></span> Failed</div>
                            </div>
                        </div>
                    </div>

                    {{-- =======================================================
                         C. LEADERBOARD (TOP STUDENTS & MODULE STATS)
                         ======================================================= --}}
                    <div class="glass-card rounded-2xl overflow-hidden reveal border-t-2 border-yellow-500/50" style="animation-delay: 0.3s;">
                        <div class="p-5 md:p-6 border-b border-white/5 flex flex-col md:flex-row justify-between items-center gap-4 bg-[#0a0e17]/30">
                            <div>
                                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                                    <span class="text-yellow-400 drop-shadow-[0_0_8px_#eab308]">🏆</span> Top Performers
                                </h3>
                                <p class="text-[10px] text-white/40 mt-1">Peringkat siswa berdasarkan nilai terbaik dan kecepatan.</p>
                            </div>
                            <span class="text-[10px] bg-[#020617] px-3 py-1.5 rounded-lg text-white/50 border border-white/10 shadow-inner">Filtered by Best Score</span>
                        </div>
                        
                        <div class="overflow-x-auto custom-scrollbar">
                            <table class="w-full text-sm text-left border-collapse min-w-[700px]">
                                <thead class="bg-[#0f141e] text-white/40 text-[10px] uppercase font-bold border-b border-white/5 sticky top-0 z-10 shadow-lg">
                                    <tr>
                                        <th class="px-6 py-4 w-16 text-center border-b border-white/5">Rank</th>
                                        <th class="px-6 py-4 border-b border-white/5">Student Profile</th>
                                        <th class="px-6 py-4 text-center border-b border-white/5">Statistics</th>
                                        <th class="px-6 py-4 text-center border-b border-white/5">Last Active</th>
                                        <th class="px-6 py-4 text-right border-b border-white/5">Best Score</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/5 bg-[#0a0e17]/30">
                                    @forelse($userPerformance as $index => $usr)
                                    <tr class="table-row group hover:bg-white/[0.05] transition">
                                        <td class="px-6 py-4 text-center">
                                            <div class="w-8 h-8 rounded-lg flex items-center justify-center font-black text-xs mx-auto shadow-inner
                                                {{ $index == 0 ? 'bg-gradient-to-br from-yellow-400 to-yellow-600 text-[#020617] shadow-[0_0_10px_#eab308]' : 
                                                   ($index == 1 ? 'bg-gradient-to-br from-slate-300 to-slate-500 text-[#020617]' : 
                                                   ($index == 2 ? 'bg-gradient-to-br from-amber-600 to-amber-800 text-white' : 'bg-indigo-500/20 border border-indigo-500/30 text-indigo-400')) }}">
                                                {{ $index + 1 }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col">
                                                {{-- LINK TO STUDENT INSIGHT --}}
                                                <a href="{{ route('admin.student.analytics', $usr->student_id ?? 1) }}" class="font-bold text-white hover:text-indigo-400 transition flex items-center gap-2 group-hover:translate-x-1 duration-200">
                                                    {{ $usr->name ?? 'Student' }}
                                                    <svg class="w-3 h-3 opacity-0 group-hover:opacity-100 transition-opacity text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                                </a>
                                                <span class="text-[10px] text-white/30 font-mono mt-0.5">{{ $usr->email ?? '-' }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex justify-center items-center gap-3">
                                                <span class="bg-[#020617] px-2 py-1.5 rounded-lg text-[10px] text-white/60 border border-white/5 shadow-inner">
                                                    🔄 {{ $usr->total_tries ?? 0 }}x
                                                </span>
                                                <span class="bg-[#020617] px-2 py-1.5 rounded-lg text-[10px] text-cyan-400 border border-white/5 shadow-inner">
                                                    ⏱️ {{ is_numeric($usr->avg_time ?? 0) ? gmdate("i:s", $usr->avg_time) : ($usr->avg_time ?? 0) }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="text-[10px] text-white/50 bg-[#020617] px-3 py-1.5 rounded-full border border-white/5 shadow-inner">
                                                {{ isset($usr->last_attempt) ? \Carbon\Carbon::parse($usr->last_attempt)->diffForHumans() : '-' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <span class="text-lg font-black {{ ($usr->best_score ?? 0) >= 80 ? 'text-emerald-400' : 'text-white' }}">
                                                {{ $usr->best_score ?? 0 }}
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="py-16 text-center text-white/30 text-xs italic bg-[#0a0e17]/50 border border-dashed border-white/10 m-4 rounded-xl block w-[calc(100%-2rem)] mx-auto mt-4">
                                            Belum ada data history pengerjaan siswa.
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

    {{-- 1. Modal: Total Attempts Detail (MENGAMBIL DATA DARI userPerformance untuk Top Contributors) --}}
    <div x-show="showAttemptsModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-[#020617]/95 backdrop-blur-md" @click="showAttemptsModal = false"></div>
        <div class="relative w-full max-w-2xl bg-[#0f141e] border border-indigo-500/40 rounded-3xl shadow-[0_30px_100px_rgba(99,102,241,0.15)] p-6 md:p-8" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
            <div class="flex justify-between items-center mb-6 border-b border-white/5 pb-4">
                <div>
                    <h3 class="text-lg md:text-xl font-bold text-white flex items-center gap-2">
                        <svg class="w-6 h-6 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                        Lab Attempts Breakdown
                    </h3>
                    <p class="text-[10px] text-indigo-400 mt-1 font-mono uppercase tracking-widest">Top contributors based on attempts</p>
                </div>
                <button @click="showAttemptsModal = false" class="text-white/40 hover:text-white transition bg-white/5 hover:bg-red-500/20 p-2 rounded-full border border-transparent hover:border-red-500/30"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            <div class="max-h-[60vh] overflow-y-auto custom-scrollbar space-y-3 pr-2">
                @forelse($userPerformance->sortByDesc('total_tries')->take(10) as $usr)
                <div class="flex items-center justify-between gap-4 p-4 rounded-xl bg-[#0a0e17]/80 border border-white/5 hover:border-indigo-500/30 transition group shadow-inner">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-white truncate group-hover:text-indigo-300 transition">{{ $usr->name ?? 'Student' }}</p>
                        <p class="text-[10px] text-white/50 font-mono mt-1">{{ $usr->email ?? '-' }}</p>
                    </div>
                    <div class="text-right shrink-0">
                        <span class="text-lg font-black text-indigo-400">{{ $usr->total_tries ?? 0 }}</span>
                        <p class="text-[9px] text-white/40 uppercase tracking-widest mt-0.5">Attempts</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-10 bg-[#0a0e17]/50 rounded-xl border border-dashed border-white/10">
                    <p class="text-[11px] text-white/40 italic">Tidak ada data percobaan.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- 2. Modal: Success Rate Detail --}}
    <div x-show="showSuccessRateModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-[#020617]/95 backdrop-blur-md" @click="showSuccessRateModal = false"></div>
        <div class="relative w-full max-w-lg bg-[#0f141e] border border-emerald-500/40 rounded-3xl shadow-[0_30px_100px_rgba(16,185,129,0.15)] p-6 md:p-8" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
            <div class="flex justify-between items-center mb-6 border-b border-white/5 pb-4">
                <div>
                    <h3 class="text-lg md:text-xl font-bold text-white flex items-center gap-2">
                        <svg class="w-6 h-6 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Global Success Ratio
                    </h3>
                </div>
                <button @click="showSuccessRateModal = false" class="text-white/40 hover:text-white transition bg-white/5 hover:bg-red-500/20 p-2 rounded-full border border-transparent hover:border-red-500/30"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            <div class="text-center py-6">
                <div class="inline-block p-8 rounded-full {{ $completionRate >= 70 ? 'bg-emerald-500/10 border-emerald-500/20 shadow-[0_0_40px_rgba(16,185,129,0.2)] text-emerald-400' : 'bg-red-500/10 border-red-500/20 shadow-[0_0_40px_rgba(239,68,68,0.2)] text-red-400' }} border mb-6">
                    <span class="text-6xl font-black">{{ $completionRate }}%</span>
                </div>
                
                <div class="flex justify-around items-center text-xs text-white/60 bg-[#0a0e17] rounded-xl p-4 border border-white/5 mt-4">
                    <div>
                        <span class="block text-2xl font-black text-emerald-400 mb-1">{{ $passedCount }}</span>
                        Passed
                    </div>
                    <div class="w-px h-10 bg-white/10"></div>
                    <div>
                        <span class="block text-2xl font-black text-red-400 mb-1">{{ $failedCount }}</span>
                        Failed
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. Modal: Average Score Detail --}}
    <div x-show="showAvgScoreModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-[#020617]/95 backdrop-blur-md" @click="showAvgScoreModal = false"></div>
        <div class="relative w-full max-w-2xl bg-[#0f141e] border border-yellow-500/40 rounded-3xl shadow-[0_30px_100px_rgba(234,179,8,0.15)] p-6 md:p-8" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
            <div class="flex justify-between items-center mb-6 border-b border-white/5 pb-4">
                <div>
                    <h3 class="text-lg md:text-xl font-bold text-white flex items-center gap-2">
                        <svg class="w-6 h-6 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 001.902 0l1.519-4.674z"/></svg>
                        Top Scores Breakdown
                    </h3>
                    <p class="text-[10px] text-yellow-400 mt-1 font-mono uppercase tracking-widest">Highest achieving students</p>
                </div>
                <button @click="showAvgScoreModal = false" class="text-white/40 hover:text-white transition bg-white/5 hover:bg-red-500/20 p-2 rounded-full border border-transparent hover:border-red-500/30"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            <div class="max-h-[60vh] overflow-y-auto custom-scrollbar space-y-3 pr-2">
                @forelse($userPerformance->sortByDesc('best_score')->take(10) as $usr)
                <div class="flex items-center justify-between gap-4 p-4 rounded-xl bg-[#0a0e17]/80 border border-white/5 hover:border-yellow-500/30 transition group shadow-inner">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-white truncate group-hover:text-yellow-300 transition">{{ $usr->name ?? 'Student' }}</p>
                        <p class="text-[10px] text-white/50 font-mono mt-1">{{ $usr->email ?? '-' }}</p>
                    </div>
                    <div class="text-right shrink-0">
                        <span class="text-lg font-black {{ ($usr->best_score ?? 0) >= 80 ? 'text-emerald-400' : 'text-yellow-400' }}">{{ $usr->best_score ?? 0 }}</span>
                        <p class="text-[9px] text-white/40 uppercase tracking-widest mt-0.5">Best Score</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-10 bg-[#0a0e17]/50 rounded-xl border border-dashed border-white/10">
                    <p class="text-[11px] text-white/40 italic">Tidak ada data nilai.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- 4. Modal: Duration Detail --}}
    <div x-show="showDurationModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-[#020617]/95 backdrop-blur-md" @click="showDurationModal = false"></div>
        <div class="relative w-full max-w-2xl bg-[#0f141e] border border-cyan-500/40 rounded-3xl shadow-[0_30px_100px_rgba(6,182,212,0.15)] p-6 md:p-8" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
            <div class="flex justify-between items-center mb-6 border-b border-white/5 pb-4">
                <div>
                    <h3 class="text-lg md:text-xl font-bold text-white flex items-center gap-2">
                        <svg class="w-6 h-6 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Average Duration
                    </h3>
                    <p class="text-[10px] text-cyan-400 mt-1 font-mono uppercase tracking-widest">Time per session breakdown</p>
                </div>
                <button @click="showDurationModal = false" class="text-white/40 hover:text-white transition bg-white/5 hover:bg-red-500/20 p-2 rounded-full border border-transparent hover:border-red-500/30"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            <div class="max-h-[60vh] overflow-y-auto custom-scrollbar space-y-3 pr-2">
                @forelse($userPerformance->sortBy('avg_time')->take(10) as $usr)
                <div class="flex items-center justify-between gap-4 p-4 rounded-xl bg-[#0a0e17]/80 border border-white/5 hover:border-cyan-500/30 transition group shadow-inner">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-white truncate group-hover:text-cyan-300 transition">{{ $usr->name ?? 'Student' }}</p>
                    </div>
                    <div class="text-right shrink-0">
                        <span class="text-lg font-black text-cyan-400 font-mono">{{ is_numeric($usr->avg_time ?? 0) ? gmdate("i:s", $usr->avg_time) : ($usr->avg_time ?? '00:00') }}</span>
                        <p class="text-[9px] text-white/40 uppercase tracking-widest mt-0.5">Average Time</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-10 bg-[#0a0e17]/50 rounded-xl border border-dashed border-white/10">
                    <p class="text-[11px] text-white/40 italic">Tidak ada data durasi.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- CHART SCRIPTS --}}
    <script>
        // 1. TREND CHART (Line)
        const ctxTrend = document.getElementById('labTrendChart');
        if(ctxTrend) {
            const gradient = ctxTrend.getContext('2d').createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(99, 102, 241, 0.5)'); // Indigo Fade
            gradient.addColorStop(1, 'rgba(99, 102, 241, 0)');

            new Chart(ctxTrend.getContext('2d'), {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartLabels ?? []) !!},
                    datasets: [{
                        label: 'Percobaan',
                        data: {!! json_encode($chartData ?? []) !!},
                        borderColor: '#818cf8',
                        backgroundColor: gradient,
                        borderWidth: 3,
                        pointBackgroundColor: '#0f141e',
                        pointBorderColor: '#818cf8',
                        pointBorderWidth: 2,
                        pointRadius: 6,
                        pointHoverRadius: 8,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { display: false }, ticks: { color: 'rgba(255,255,255,0.4)', font: {size: 10, family: 'JetBrains Mono'} } },
                        y: { beginAtZero: true, grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: 'rgba(255,255,255,0.4)', stepSize: 1, font: {family: 'JetBrains Mono'} } }
                    }
                }
            });
        }

        // 2. PASS/FAIL CHART (Doughnut)
        const ctxPie = document.getElementById('passFailChart');
        if(ctxPie) {
            new Chart(ctxPie.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Passed', 'Failed'],
                    datasets: [{
                        data: [{{ $passedCount ?? 0 }}, {{ $failedCount ?? 0 }}],
                        backgroundColor: ['#10b981', '#ef4444'], 
                        borderColor: '#020617',
                        borderWidth: 5,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false, cutout: '75%',
                    plugins: { legend: {display: false} }
                }
            });
        }
    </script>

</body>
</html>