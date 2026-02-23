<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Bank Soal & Analisis · Utilwind Admin</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    
    {{-- RESOURCES --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        .glass-header { background: rgba(2, 6, 23, 0.8); backdrop-filter: blur(12px); border-bottom: 1px solid var(--glass-border); z-index: 40; }
        
        .glass-card {
            background: var(--glass-bg); border: 1px solid var(--glass-border);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.2); backdrop-filter: blur(10px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative; 
            overflow: visible !important; /* MEMASTIKAN TOOLTIP TIDAK TERPOTONG */
            z-index: 10;
        }
        .glass-card:hover { border-color: rgba(99, 102, 241, 0.4); transform: translateY(-4px); box-shadow: 0 10px 40px -10px rgba(0,0,0,0.5); z-index: 30; }
        
        /* Container SVG Background Card agar desain gradient tetap didalam border */
        .card-bg-gfx { position: absolute; inset: 0; overflow: hidden; border-radius: 1rem; pointer-events: none; z-index: 0; }

        /* --- INPUTS & NAV --- */
        .glass-input { background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.1); color: white; transition: 0.3s; }
        .glass-input:focus { border-color: var(--accent); background: rgba(255, 255, 255, 0.05); outline: none; box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2); }
        
        .nav-link { display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 12px; color: #94a3b8; font-weight: 500; font-size: 0.9rem; transition: all 0.2s; border: 1px solid transparent; }
        .nav-link:hover { background: rgba(255, 255, 255, 0.03); color: white; }
        .nav-link.active { background: linear-gradient(90deg, rgba(99, 102, 241, 0.1) 0%, rgba(99, 102, 241, 0) 100%); color: #818cf8; border-left: 3px solid #818cf8; border-radius: 4px 12px 12px 4px; }

        .reveal { opacity: 0; transform: translateY(20px); animation: revealAnim 0.6s forwards ease-out; }
        @keyframes revealAnim { to { opacity: 1; transform: translateY(0); } }
        .table-row { transition: background 0.2s; border-bottom: 1px solid rgba(255,255,255,0.03); }
        .table-row:hover { background: rgba(255,255,255,0.02); }

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
            background-color: #020617; /* Background super gelap kontras */
            color: #e2e8f0; font-size: 11px; padding: 14px 16px; line-height: 1.5;
            border-radius: 12px; box-shadow: 0 20px 60px rgba(0,0,0,1); z-index: 99999;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
        }

        /* Tooltip Dropdown (Buka ke Bawah agar tidak menabrak atas header) */
        .tooltip-down .tooltip-content { top: calc(100% + 12px); left: 50%; transform: translateX(-50%) translateY(-10px); }
        .tooltip-down:hover .tooltip-content { transform: translateX(-50%) translateY(0); opacity: 1; visibility: visible; }
        .tooltip-down .tooltip-content::after { content: ''; position: absolute; bottom: 100%; left: 50%; margin-left: -6px; border-width: 6px; border-style: solid; border-color: transparent transparent #020617 transparent; }
        
        .tooltip-left .tooltip-content { left: auto; right: -12px; transform: translateX(0) translateY(-10px); }
        .tooltip-down.tooltip-left:hover .tooltip-content { transform: translateX(0) translateY(0); }
        .tooltip-left .tooltip-content::after { left: auto; right: 15px; margin-left: 0; }

        .tooltip-indigo .tooltip-trigger { background-color: #6366f1; box-shadow: 0 0 10px rgba(99,102,241,0.5); }
        .tooltip-indigo .tooltip-trigger:hover { background-color: #818cf8; box-shadow: 0 0 15px rgba(99,102,241,0.8); }
        .tooltip-indigo .tooltip-content { border: 1px solid rgba(99,102,241,0.5); }

        .tooltip-cyan .tooltip-trigger { background-color: #06b6d4; box-shadow: 0 0 10px rgba(6,182,212,0.5); }
        .tooltip-cyan .tooltip-trigger:hover { background-color: #22d3ee; box-shadow: 0 0 15px rgba(6,182,212,0.8); }
        .tooltip-cyan .tooltip-content { border: 1px solid rgba(6,182,212,0.5); }

        .tooltip-emerald .tooltip-trigger { background-color: #10b981; box-shadow: 0 0 10px rgba(16,185,129,0.5); }
        .tooltip-emerald .tooltip-trigger:hover { background-color: #34d399; box-shadow: 0 0 15px rgba(16,185,129,0.8); }
        .tooltip-emerald .tooltip-content { border: 1px solid rgba(16,185,129,0.5); }

        .tooltip-red .tooltip-trigger { background-color: #ef4444; box-shadow: 0 0 10px rgba(239,68,68,0.5); }
        .tooltip-red .tooltip-trigger:hover { background-color: #f87171; box-shadow: 0 0 15px rgba(239,68,68,0.8); }
        .tooltip-red .tooltip-content { border: 1px solid rgba(239,68,68,0.5); }

        .modal-open { overflow: hidden; padding-right: 5px; } 
    </style>
</head>
<body x-data="{ 
    sidebarOpen: false,
    isFullscreen: false,
    currentView: 'dashboard', 
    activeChapter: null,
    activeChapterName: '',
    search: '',
    difficulty: 'all',
    
    // State Modal Hero Insight
    showQuestionsModal: false,
    showParticipantsModal: false,
    showAccuracyModal: false,
    showHardModal: false,

    selectChapter(id, name) {
        this.activeChapter = id;
        this.activeChapterName = name;
        this.currentView = 'table';
        this.search = '';
    },
    resetView() {
        this.currentView = 'dashboard';
        this.activeChapter = null;
    }
}" @keydown.escape.window="isFullscreen = false; document.exitFullscreen(); showQuestionsModal = false; showParticipantsModal = false; showAccuracyModal = false; showHardModal = false; closeModal(); closeInsightModal();" :class="{'modal-open': sidebarOpen || showQuestionsModal || showParticipantsModal || showAccuracyModal || showHardModal}">

    {{-- ==============================================================================
         LOGIKA BLADE BULLETPROOF
         Semua data diderivasi MURNI dari variabel AdminQuizController yang Anda berikan.
         Fungsi isset() dan collect() mencegah error jika Anda belum memasukkan variabel
         seperti $questions atau $studentStats ke dalam controller.
         ============================================================================== --}}
    @php
        $totalAttempts = $totalAttempts ?? 0;
        $avgScore = $avgScore ?? 0;
        $passRate = $passRate ?? 0;
        
        $chapterStats = isset($chapterStats) ? collect($chapterStats) : collect([]);
        $hardestQuestions = isset($hardestQuestions) ? collect($hardestQuestions) : collect([]);
        $recentAttempts = isset($recentAttempts) ? collect($recentAttempts) : collect([]);
        
        // Data pendukung Bank Soal. Jika tidak ada di controller, ini diset kosong (aman dari error)
        $questions = isset($questions) ? collect($questions) : collect([]);
        $studentStats = isset($studentStats) ? collect($studentStats) : collect([]);

        // Kalkulasi Manual yang sangat aman
        $totalQuestions = $questions->count();
        $totalParticipants = $studentStats->count();
        $globalAcc = $totalQuestions > 0 ? round($questions->avg('accuracy'), 1) : 0;
        $hardQuestionsCount = $questions->where('status', 'Sulit')->count();

        // Mengelompokkan soal per BAB (Untuk Tampilan Chapter Cards)
        $chapterGroups = $questions->where('chapter_id', '!=', 99)->groupBy('chapter_id');
        $finalExam = $questions->where('chapter_id', 99);
    @endphp

    <div class="flex h-screen w-full relative">

        <div x-show="sidebarOpen" class="fixed inset-0 bg-[#020617]/80 backdrop-blur-sm z-[90] md:hidden" @click="sidebarOpen = false" x-transition.opacity style="display: none;"></div>

    {{-- ==================== SIDEBAR ==================== --}}
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
                    <a href="{{ route('admin.analytics.questions') }}" class="nav-link active {{ request()->routeIs('admin.analytics.questions') ? 'active' : '' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.analytics.questions') ? 'text-indigo-400' : 'text-slate-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                        Quiz Management
                    </a>
                    <a href="{{ route('admin.labs.index') }}" class="nav-link {{ request()->routeIs('admin.labs.index') ? 'active' : '' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.labs.index') ? 'text-indigo-400' : 'text-slate-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                        Lab Configuration
                    </a>
                    <a href="{{ route('admin.lab.analytics') }}" class="nav-link {{ request()->routeIs('admin.lab.analytics') ? 'active' : '' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.lab.analytics') ? 'text-indigo-400' : 'text-slate-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
                        Lab Analytics
                    </a>
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
                <div class="absolute top-[10%] left-[20%] w-[500px] h-[500px] bg-cyan-600/10 rounded-full blur-[120px]"></div>
                <div class="absolute bottom-[10%] right-[10%] w-[400px] h-[400px] bg-indigo-600/10 rounded-full blur-[120px]"></div>
                <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.04] mix-blend-overlay"></div>
            </div>

            {{-- HEADER RESPONSIVE & BREADCRUMB IDENTIK DASHBOARD --}}
            <header class="h-24 glass-header flex flex-col justify-center px-6 md:px-10 shrink-0 sticky top-0 z-40">
                <div class="flex items-center justify-between w-full">
                    <div class="flex items-center gap-4">
                        <button @click="sidebarOpen = true" class="md:hidden p-2 bg-white/5 rounded-lg text-white hover:bg-white/10 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        </button>
                        
                        <div class="flex items-center gap-3">
                            {{-- Back Button (Muncul jika ada di tabel bank soal) --}}
                            <button x-show="currentView === 'table'" @click="resetView()" x-cloak x-transition class="p-2 rounded-full bg-white/5 hover:bg-white/10 text-white transition group border border-white/10" title="Back to Overview">
                                <svg class="w-4 h-4 text-white/70 group-hover:text-white transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            </button>

                            <div>
                                <nav class="flex text-[10px] text-white/50 mb-1.5 font-bold hidden sm:flex" aria-label="Breadcrumb">
                                    <ol class="inline-flex items-center space-x-1">
                                        <li class="inline-flex items-center"><a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-400 transition">Dashboard</a></li>
                                        <li>
                                            <div class="flex items-center">
                                                <svg class="w-3 h-3 text-white/30 mx-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                                <span class="text-white">Quiz Management</span>
                                            </div>
                                        </li>
                                    </ol>
                                </nav>
                                <h2 class="text-white font-bold text-lg md:text-xl tracking-tight" x-text="currentView === 'dashboard' ? 'Quiz Management Center' : 'Detail Bab: ' + activeChapterName"></h2>
                                <p class="text-[9px] md:text-xs text-white/40 flex items-center gap-1.5 mt-0.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse shadow-[0_0_8px_#10b981]"></span>
                                    <span x-text="currentView === 'dashboard' ? 'Tinjauan Performa & Basis Data' : 'Bank Soal & Mode Edit'"></span>
                                </p>
                            </div>
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

                        {{-- Action Button (Hanya Muncul di Mode Tabel Bank Soal) --}}
                        <div class="border-l border-white/10 pl-5 ml-1 hidden lg:block" x-show="currentView === 'table'">
                            <button onclick="openModal('create')" class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow-[0_0_15px_rgba(99,102,241,0.3)] transition border border-indigo-400">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg> Tambah Soal
                            </button>
                        </div>

                        <div class="text-right hidden lg:block border-l border-white/10 pl-5 ml-1">
                            <p class="text-sm font-bold text-white">{{ \Carbon\Carbon::now()->translatedFormat('d M Y') }}</p>
                            <p class="text-[10px] text-white/40 font-mono mt-0.5">{{ \Carbon\Carbon::now()->format('H:i') }} WIB</p>
                        </div>

                        {{-- Mobile Add Button --}}
                        <button onclick="openModal('create')" x-show="currentView === 'table'" class="lg:hidden p-2 rounded-lg bg-indigo-600 text-white shadow-lg">
                             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        </button>
                    </div>
                </div>
            </header>

            {{-- CONTENT SCROLLABLE --}}
            <div class="flex-1 p-6 md:p-10 relative z-10">
                <div class="max-w-7xl mx-auto space-y-8 md:space-y-12">

                    {{-- =======================================================
                         VIEW 1: DASHBOARD GRID (OVERVIEW)
                         ======================================================= --}}
                    <div x-show="currentView === 'dashboard'" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                        
                        {{-- 1. OVERVIEW STATS (4 KARTU DENGAN HERO MODAL TRIGGER) --}}
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 mb-8 reveal">
                            <div class="glass-card rounded-2xl p-5 border-l-4 border-l-cyan-500 cursor-pointer group transition-all" @click="showQuestionsModal = true">
                                <div class="flex justify-between items-start">
                                    <p class="text-[10px] font-bold text-white/40 uppercase tracking-widest group-hover:text-cyan-400 transition">Total Soal</p>
                                    <div class="tooltip-container tooltip-cyan tooltip-down tooltip-left">
                                        <div class="tooltip-trigger bg-transparent border-transparent shadow-none text-white/30 group-hover:text-cyan-400">?</div>
                                        <div class="tooltip-content">
                                            <span class="block font-bold text-cyan-400 mb-1 border-b border-white/10 pb-1">Total Soal</span>
                                            Total akumulasi seluruh soal teori yang ada di dalam database saat ini.
                                        </div>
                                    </div>
                                </div>
                                <h3 class="text-2xl md:text-3xl font-black text-white mt-2">{{ $totalQuestions }}</h3>
                                <p class="text-[9px] text-cyan-400 mt-2 opacity-0 group-hover:opacity-100 transition translate-y-2 group-hover:translate-y-0">Lihat Database &rarr;</p>
                            </div>

                            <div class="glass-card rounded-2xl p-5 border-l-4 border-l-indigo-500 cursor-pointer group transition-all" @click="showParticipantsModal = true">
                                <div class="flex justify-between items-start">
                                    <p class="text-[10px] font-bold text-white/40 uppercase tracking-widest group-hover:text-indigo-400 transition">Peserta Ujian</p>
                                    <div class="tooltip-container tooltip-indigo tooltip-down tooltip-left">
                                        <div class="tooltip-trigger bg-transparent border-transparent shadow-none text-white/30 group-hover:text-indigo-400">?</div>
                                        <div class="tooltip-content">
                                            <span class="block font-bold text-indigo-400 mb-1 border-b border-white/10 pb-1">Total Peserta</span>
                                            Total siswa unik yang telah mengumpulkan setidaknya satu evaluasi kuis.
                                        </div>
                                    </div>
                                </div>
                                <h3 class="text-2xl md:text-3xl font-black text-white mt-2">{{ $totalParticipants }}</h3>
                                <p class="text-[9px] text-indigo-400 mt-2 opacity-0 group-hover:opacity-100 transition translate-y-2 group-hover:translate-y-0">Lihat Peringkat &rarr;</p>
                            </div>

                            <div class="glass-card rounded-2xl p-5 border-l-4 border-l-emerald-500 cursor-pointer group transition-all" @click="showAccuracyModal = true">
                                <div class="flex justify-between items-start">
                                    <p class="text-[10px] font-bold text-white/40 uppercase tracking-widest group-hover:text-emerald-400 transition">Akurasi Global</p>
                                    <div class="tooltip-container tooltip-emerald tooltip-down tooltip-left">
                                        <div class="tooltip-trigger bg-transparent border-transparent shadow-none text-white/30 group-hover:text-emerald-400">?</div>
                                        <div class="tooltip-content">
                                            <span class="block font-bold text-emerald-400 mb-1 border-b border-white/10 pb-1">Akurasi Rata-rata</span>
                                            Kalkulasi persentase ketepatan jawaban rata-rata dari seluruh soal yang pernah dijawab.
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-baseline gap-1 mt-2">
                                    <h3 class="text-2xl md:text-3xl font-black text-white">{{ $globalAcc }}</h3>
                                    <span class="text-[10px] text-emerald-500 font-bold">%</span>
                                </div>
                                <p class="text-[9px] text-emerald-400 mt-2 opacity-0 group-hover:opacity-100 transition translate-y-2 group-hover:translate-y-0">Lihat Rincian &rarr;</p>
                            </div>

                            <div class="glass-card rounded-2xl p-5 border-l-4 border-l-red-500 cursor-pointer group transition-all" @click="showHardModal = true">
                                <div class="flex justify-between items-start">
                                    <p class="text-[10px] font-bold text-white/40 uppercase tracking-widest group-hover:text-red-400 transition">Soal Sulit</p>
                                    <div class="tooltip-container tooltip-red tooltip-down tooltip-left">
                                        <div class="tooltip-trigger bg-transparent border-transparent shadow-none text-white/30 group-hover:text-red-400">?</div>
                                        <div class="tooltip-content">
                                            <span class="block font-bold text-red-400 mb-1 border-b border-white/10 pb-1">Butuh Perhatian</span>
                                            Jumlah spesifik soal yang memiliki tingkat kegagalan (salah jawab) di atas 50%.
                                        </div>
                                    </div>
                                </div>
                                <h3 class="text-2xl md:text-3xl font-black text-red-500 mt-2 drop-shadow-[0_0_8px_#ef4444]">{{ $hardQuestionsCount }}</h3>
                                <p class="text-[9px] text-red-400 mt-2 opacity-0 group-hover:opacity-100 transition translate-y-2 group-hover:translate-y-0">Tinjau Soal &rarr;</p>
                            </div>
                        </div>

                        {{-- 2. CHAPTER CARDS (REGULAR) --}}
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8 md:mb-12">
                            {{-- KOLOM KIRI: MATERI REGULER --}}
                            <div class="lg:col-span-2 space-y-6">
                                <h3 class="text-lg font-bold text-white flex items-center gap-2"><svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg> Direktori Bank Soal</h3>
                                
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 reveal" style="animation-delay: 0.1s;">
                                    @php
                                        // Meta mapping warna & judul untuk chapter standard
                                        $chapterMeta = [
                                            1 => ['title' => 'BAB 1: Pendahuluan', 'desc' => 'Dasar HTML, CSS & Tailwind', 'color' => 'cyan'],
                                            2 => ['title' => 'BAB 2: Layouting', 'desc' => 'Sistem Flexbox & Grid', 'color' => 'indigo'],
                                            3 => ['title' => 'BAB 3: Styling', 'desc' => 'Efek, Dekorasi & Tipografi', 'color' => 'fuchsia'],
                                        ];
                                    @endphp

                                    @if($chapterGroups->count() > 0)
                                        @foreach($chapterGroups as $id => $chQs)
                                            @php
                                                $meta = $chapterMeta[$id] ?? ['title' => 'BAB '.$id.': Lanjutan', 'desc' => 'Materi Tambahan', 'color' => 'emerald'];
                                                $cnt = $chQs->count();
                                                $acc = $cnt > 0 ? round($chQs->avg('accuracy')) : 0;
                                            @endphp
                                            <div @click="selectChapter({{ $id }}, '{{ addslashes($meta['title']) }}')" 
                                                 class="glass-card rounded-3xl p-6 cursor-pointer group hover:border-{{ $meta['color'] }}-500/50 flex flex-col justify-between h-48 md:h-56">
                                                
                                                <div class="card-bg-gfx">
                                                    <div class="absolute -right-8 -top-8 w-32 h-32 bg-{{ $meta['color'] }}-500/10 rounded-full blur-2xl group-hover:bg-{{ $meta['color'] }}-500/20 transition duration-500"></div>
                                                </div>
                                                
                                                <div class="relative z-10">
                                                    <div class="flex justify-between items-start mb-4">
                                                        <span class="text-xl md:text-2xl font-black font-mono text-{{ $meta['color'] }}-400 bg-{{ $meta['color'] }}-500/10 px-3 py-1 rounded-lg border border-{{ $meta['color'] }}-500/20">0{{ $id }}</span>
                                                        <div class="text-right">
                                                            <p class="text-xl md:text-2xl font-black text-white">{{ $cnt }}</p>
                                                            <p class="text-[9px] md:text-[10px] text-white/40 uppercase tracking-widest font-bold">Soal</p>
                                                        </div>
                                                    </div>
                                                    <h3 class="text-base md:text-lg font-bold text-white group-hover:text-{{ $meta['color'] }}-400 transition">{{ $meta['title'] }}</h3>
                                                    <p class="text-[10px] md:text-xs text-white/50 mt-1">{{ $meta['desc'] }}</p>
                                                </div>
                                                <div class="relative z-10 w-full bg-white/10 h-1.5 rounded-full overflow-hidden mt-4">
                                                    <div class="h-full bg-{{ $meta['color'] }}-500 transition-all duration-1000" style="width: {{ $acc }}%"></div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="col-span-2 text-center py-10 text-white/30 text-xs italic bg-[#0a0e17]/50 rounded-xl border border-dashed border-white/10">Belum ada soal terdaftar untuk bab reguler.</div>
                                    @endif
                                </div>
                            </div>

                            {{-- KOLOM KANAN: DISTRIBUTION CHART --}}
                            <div class="lg:col-span-1 space-y-6 reveal" style="animation-delay: 0.2s;">
                                <h3 class="text-lg font-bold text-white flex items-center gap-2"><svg class="w-5 h-5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg> Rasio Kesulitan Soal</h3>
                                <div class="glass-card rounded-3xl p-6 flex flex-col items-center justify-center h-auto lg:h-[calc(100%-3rem)]">
                                    <div class="w-40 h-40 md:w-48 md:h-48 relative">
                                        <canvas id="difficultyChart"></canvas>
                                        <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                                            <span class="text-2xl md:text-3xl font-black text-white">{{ $totalQuestions }}</span>
                                            <span class="text-[9px] md:text-[10px] uppercase text-white/40 font-bold tracking-widest">Total</span>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-3 gap-2 mt-6 w-full text-center">
                                        <div class="p-2 rounded-xl bg-emerald-500/10 border border-emerald-500/20">
                                            <span class="block text-emerald-400 font-bold text-xs md:text-sm">{{ $questions->where('status', 'Mudah')->count() }}</span>
                                            <span class="text-[8px] md:text-[9px] text-white/40 uppercase">Mudah</span>
                                        </div>
                                        <div class="p-2 rounded-xl bg-yellow-500/10 border border-yellow-500/20">
                                            <span class="block text-yellow-400 font-bold text-xs md:text-sm">{{ $questions->where('status', 'Sedang')->count() }}</span>
                                            <span class="text-[8px] md:text-[9px] text-white/40 uppercase">Sedang</span>
                                        </div>
                                        <div class="p-2 rounded-xl bg-red-500/10 border border-red-500/20">
                                            <span class="block text-red-400 font-bold text-xs md:text-sm">{{ $hardQuestionsCount }}</span>
                                            <span class="text-[8px] md:text-[9px] text-white/40 uppercase">Sulit</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- 3. FINAL EXAM CARD (SPECIAL) --}}
                        @if($finalExam->count() > 0)
                        @php
                            $finalCnt = $finalExam->count();
                            $finalAcc = $finalCnt > 0 ? round($finalExam->avg('accuracy')) : 0;
                        @endphp
                        <div class="mb-8 md:mb-12 reveal" style="animation-delay: 0.3s;">
                            <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2"><svg class="w-5 h-5 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg> Evaluasi Komprehensif</h3>
                            <div @click="selectChapter(99, 'FINAL EXAM: Evaluasi Akhir')" 
                                class="glass-card rounded-3xl p-6 md:p-8 cursor-pointer group hover:border-yellow-500/50 flex flex-col md:flex-row items-start md:items-center justify-between gap-6 md:gap-8 bg-gradient-to-r from-yellow-900/10 to-transparent border-t-2 border-yellow-500/50">
                                
                                <div class="card-bg-gfx">
                                    <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-10"></div>
                                    <div class="absolute -left-20 -top-20 w-64 h-64 bg-yellow-500/10 rounded-full blur-[80px] group-hover:bg-yellow-500/20 transition duration-500"></div>
                                </div>

                                <div class="relative z-10 flex-1">
                                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-yellow-500/10 border border-yellow-500/20 text-yellow-400 text-[10px] md:text-xs font-bold uppercase tracking-widest mb-3">
                                        <span class="w-2 h-2 rounded-full bg-yellow-500 animate-pulse"></span> Capstone Exam
                                    </div>
                                    <h3 class="text-2xl md:text-3xl font-black text-white mb-2">Evaluasi Akhir (Bab 1 - 3)</h3>
                                    <p class="text-xs text-white/60">Kumpulan seluruh soal teori dari semua materi untuk menguji tingkat pemahaman komprehensif siswa.</p>
                                </div>

                                <div class="relative z-10 flex gap-6 md:gap-8 text-center w-full md:w-auto justify-around md:justify-end border-t md:border-none border-white/10 pt-4 md:pt-0">
                                    <div>
                                        <p class="text-3xl md:text-4xl font-black text-white group-hover:scale-110 transition">{{ $finalCnt }}</p>
                                        <p class="text-[9px] md:text-[10px] text-white/40 uppercase font-bold tracking-widest mt-1">Soal</p>
                                    </div>
                                    <div>
                                        <p class="text-3xl md:text-4xl font-black {{ $finalAcc >= 70 ? 'text-emerald-400' : 'text-yellow-400' }} group-hover:scale-110 transition">{{ $finalAcc }}%</p>
                                        <p class="text-[9px] md:text-[10px] text-white/40 uppercase font-bold tracking-widest mt-1">Avg Skor</p>
                                    </div>
                                    <div class="flex items-center hidden sm:flex">
                                        <div class="w-12 h-12 rounded-full bg-white/5 border border-white/10 flex items-center justify-center group-hover:bg-yellow-500 group-hover:text-black transition">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    {{-- =======================================================
                         VIEW 2: DETAIL TABLE BANK SOAL (DRILL DOWN PER BAB)
                         ======================================================= --}}
                    <div x-show="currentView === 'table'" x-cloak x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                        
                        {{-- Controls --}}
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                            <div class="relative w-full md:w-96 group">
                                <input x-model="search" type="text" placeholder="Cari teks pertanyaan, atau opsi jawaban..." class="w-full bg-[#0a0e17] border border-white/10 rounded-xl pl-10 pr-4 py-3 text-xs md:text-sm text-white focus:border-indigo-500 outline-none transition shadow-inner">
                                <div class="absolute left-3 top-3 md:top-3.5 text-white/30 group-focus-within:text-indigo-400 transition"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg></div>
                            </div>
                            <div class="flex gap-3 w-full md:w-auto">
                                <select x-model="difficulty" class="w-full md:w-auto bg-[#0a0e17] border border-white/10 text-white text-xs rounded-xl px-4 py-3 outline-none focus:border-indigo-500 cursor-pointer min-w-[150px] shadow-inner">
                                    <option value="all">Semua Kesulitan</option>
                                    <option value="Sulit">🔥 Sulit (< 50%)</option>
                                    <option value="Sedang">⚖️ Sedang (50-79%)</option>
                                    <option value="Mudah">✅ Mudah (≥ 80%)</option>
                                </select>
                            </div>
                        </div>

                        {{-- Main Table Bank Soal (Tampil jika ada $questions) --}}
                        @if($questions->count() > 0)
                        <div class="glass-card rounded-2xl overflow-hidden border-t border-white/10">
                            <div class="overflow-x-auto custom-scrollbar rounded-2xl">
                                <table class="w-full text-sm text-left min-w-[800px]">
                                    <thead class="bg-[#0f141e] text-white/40 text-[10px] uppercase font-bold border-b border-white/5 sticky top-0 z-10 shadow-lg">
                                        <tr>
                                            <th class="px-6 py-4 w-[50%]">Teks Pertanyaan & Opsi Jawaban</th>
                                            <th class="px-6 py-4 text-center">Analisis Rasio Jawaban</th>
                                            <th class="px-6 py-4 text-center">Status Label</th>
                                            <th class="px-6 py-4 text-right">Aksi Panel</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-white/5 bg-[#0a0e17]/30">
                                        @foreach($questions as $q)
                                        <tr class="hover:bg-white/5 transition group question-row" 
                                            x-show="(activeChapter == {{ $q->chapter_id ?? 0 }}) && 
                                                    ('{{ strtolower($q->question_text ?? '') }}'.includes(search.toLowerCase())) &&
                                                    (difficulty === 'all' || difficulty === '{{ $q->status ?? '' }}')"
                                            x-transition>
                                            <td class="px-6 py-5 align-top">
                                                <p class="text-white font-medium text-xs md:text-sm leading-relaxed mb-3 group-hover:text-indigo-300 transition pr-4">{{ $q->question_text }}</p>
                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 opacity-80 sm:opacity-60 group-hover:opacity-100 transition">
                                                    @if(isset($q->options))
                                                        @foreach($q->options as $idx => $opt)
                                                            <div class="flex items-start gap-2 text-[10px] md:text-xs {{ $opt->is_correct ? 'text-emerald-400 font-bold' : 'text-white/50 sm:text-white/40' }}">
                                                                <span class="uppercase w-4 shrink-0">{{ ['A','B','C','D'][$idx] ?? '' }}.</span>
                                                                <span class="truncate pr-2">{{ Str::limit($opt->option_text, 50) }}</span>
                                                                @if($opt->is_correct) <svg class="w-3 h-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" d="M5 13l4 4L19 7"/></svg> @endif
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-5 align-middle">
                                                <div class="flex flex-col gap-2">
                                                    <div class="flex justify-between text-[9px] md:text-[10px] font-bold text-white/60 px-4">
                                                        <span class="text-emerald-400">{{ $q->correct_count ?? 0 }} Benar</span>
                                                        <span class="text-red-400">{{ $q->wrong_count ?? 0 }} Salah</span>
                                                    </div>
                                                    <div class="w-24 md:w-32 h-1.5 md:h-2 bg-[#020617] rounded-full overflow-hidden flex mx-auto border border-white/5 shadow-inner">
                                                        @if(isset($q->total_attempts) && $q->total_attempts > 0)
                                                            <div class="h-full bg-emerald-500" style="width: {{ $q->accuracy }}%"></div>
                                                            <div class="h-full bg-red-500" style="width: {{ 100 - $q->accuracy }}%"></div>
                                                        @else
                                                            <div class="w-full h-full bg-white/5"></div>
                                                        @endif
                                                    </div>
                                                    <div class="text-center mt-1"><span class="text-xs font-black text-white">{{ $q->total_attempts ?? 0 }}</span> <span class="text-[9px] md:text-[10px] text-white/30">Total Dicoba</span></div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-5 text-center align-middle">
                                                <span class="px-2 py-1 rounded text-[9px] md:text-[10px] font-bold uppercase border whitespace-nowrap {{ ($q->status ?? '') == 'Sulit' ? 'bg-red-500/10 text-red-400 border-red-500/20' : (($q->status ?? '') == 'Sedang' ? 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20' : 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20') }}">{{ $q->status }}</span>
                                            </td>
                                            <td class="px-6 py-5 text-right align-middle">
                                                <div class="flex justify-end gap-2 sm:opacity-0 group-hover:opacity-100 transition duration-300">
                                                    <button onclick='openInsightModal(@json($q->list_correct ?? []), @json($q->list_wrong ?? []))' class="p-2 rounded-lg bg-white/10 sm:bg-[#020617] hover:bg-indigo-500 text-white sm:text-indigo-400 hover:text-white transition shadow-inner border border-white/10 hover:border-indigo-400" title="Lihat Daftar Siswa"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></button>
                                                    <button onclick='openModal("edit", @json($q))' class="p-2 rounded-lg bg-white/10 sm:bg-[#020617] hover:bg-amber-500 text-white sm:text-amber-400 hover:text-white transition shadow-inner border border-white/10 hover:border-amber-400" title="Edit Soal"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                                                    <button onclick="confirmDelete('{{ $q->id }}')" class="p-2 rounded-lg bg-white/10 sm:bg-[#020617] hover:bg-red-500 text-white sm:text-red-400 hover:text-white transition shadow-inner border border-white/10 hover:border-red-400" title="Hapus Soal"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @else
                        <div class="glass-card rounded-2xl p-10 text-center flex flex-col items-center justify-center min-h-[300px] opacity-60">
                            <div class="text-4xl mb-4 grayscale">📂</div>
                            <h3 class="text-white font-bold">Data Bank Soal Kosong</h3>
                            <p class="text-xs text-white/50 mt-2">Tidak ada data pertanyaan yang ditemukan di database untuk bab ini.</p>
                        </div>
                        @endif
                    </div>

                </div>
            </div>
        </main>
    </div>

    {{-- ==================== MODALS HERO INSIGHT (DASHBOARD CARDS) ==================== --}}

    {{-- 1. MODAL: TOTAL QUESTIONS --}}
    <div x-show="showQuestionsModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-[#020617]/95 backdrop-blur-md" @click="showQuestionsModal = false"></div>
        <div class="relative w-full max-w-2xl bg-[#0f141e] border border-cyan-500/40 rounded-2xl shadow-[0_20px_70px_rgba(6,182,212,0.15)] p-6" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
            <div class="flex justify-between items-center mb-6 border-b border-white/5 pb-4">
                <div>
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-cyan-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        Tinjauan Basis Data Soal
                    </h3>
                    <p class="text-[10px] text-cyan-400 mt-1 font-mono">Daftar seluruh soal teori yang tersedia di sistem.</p>
                </div>
                <button @click="showQuestionsModal = false" class="text-white/40 hover:text-white transition bg-white/5 hover:bg-red-500/20 rounded-full p-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            <div class="max-h-[60vh] overflow-y-auto custom-scrollbar space-y-2 pr-2">
                @forelse($questions as $q)
                <div class="flex items-center justify-between gap-4 p-3.5 rounded-xl bg-[#0a0e17]/80 border border-white/5 hover:border-cyan-500/30 transition group">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-white truncate" title="{{ $q->question_text }}">{{ $q->question_text }}</p>
                        <p class="text-[10px] text-white/50 font-mono mt-0.5">Bab {{ $q->chapter_id }}</p>
                    </div>
                    <div class="text-right shrink-0">
                        <span class="text-[9px] font-bold uppercase tracking-widest border px-2 py-1 rounded {{ $q->status == 'Sulit' ? 'bg-red-500/10 text-red-400 border-red-500/20' : ($q->status == 'Sedang' ? 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20' : 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20') }}">{{ $q->status }}</span>
                    </div>
                </div>
                @empty
                <p class="text-[11px] text-white/40 text-center py-10">Belum ada data soal yang dimasukkan.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- 2. MODAL: PARTICIPANTS --}}
    <div x-show="showParticipantsModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-[#020617]/95 backdrop-blur-md" @click="showParticipantsModal = false"></div>
        <div class="relative w-full max-w-2xl bg-[#0f141e] border border-indigo-500/40 rounded-2xl shadow-[0_20px_70px_rgba(99,102,241,0.15)] p-6" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
            <div class="flex justify-between items-center mb-6 border-b border-white/5 pb-4">
                <div>
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        Daftar Peserta Kuis
                    </h3>
                    <p class="text-[10px] text-indigo-400 mt-1 font-mono">Data siswa unik yang telah berpartisipasi mencoba kuis.</p>
                </div>
                <button @click="showParticipantsModal = false" class="text-white/40 hover:text-white transition bg-white/5 hover:bg-red-500/20 rounded-full p-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            <div class="max-h-[60vh] overflow-y-auto custom-scrollbar space-y-2 pr-2">
                @forelse($studentStats as $stat)
                <div class="flex items-center gap-4 p-3.5 rounded-xl bg-[#0a0e17]/80 border border-white/5 hover:border-indigo-500/30 transition group">
                    <div class="w-10 h-10 rounded-full bg-indigo-500/10 flex items-center justify-center text-sm font-bold text-indigo-400 border border-indigo-500/30 shrink-0">{{ substr($stat->name, 0, 2) }}</div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-white truncate">{{ $stat->name }}</p>
                        <p class="text-[10px] text-white/50 font-mono mt-0.5">{{ $stat->email }}</p>
                    </div>
                    <div class="text-right shrink-0">
                        <span class="block text-sm font-black {{ $stat->avg_score >= 70 ? 'text-emerald-400' : 'text-red-400' }}">{{ $stat->avg_score }} Avg</span>
                        <span class="text-[9px] text-white/40">{{ $stat->total_attempts }} Evaluasi Selesai</span>
                    </div>
                </div>
                @empty
                <p class="text-[11px] text-white/40 text-center py-10">Belum ada siswa yang berpartisipasi.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- 3. MODAL: GLOBAL ACCURACY (PER CHAPTER) --}}
    <div x-show="showAccuracyModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-[#020617]/95 backdrop-blur-md" @click="showAccuracyModal = false"></div>
        <div class="relative w-full max-w-2xl bg-[#0f141e] border border-emerald-500/40 rounded-2xl shadow-[0_20px_70px_rgba(16,185,129,0.15)] p-6" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
            <div class="flex justify-between items-center mb-6 border-b border-white/5 pb-4">
                <div>
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Rincian Akurasi per Bab
                    </h3>
                    <p class="text-[10px] text-emerald-400 mt-1 font-mono">Menampilkan persentase ketepatan jawaban rata-rata untuk setiap materi.</p>
                </div>
                <button @click="showAccuracyModal = false" class="text-white/40 hover:text-white transition bg-white/5 hover:bg-red-500/20 rounded-full p-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            <div class="max-h-[60vh] overflow-y-auto custom-scrollbar space-y-3 pr-2">
                @forelse($chapterGroups as $id => $chQs)
                @php 
                    $avgCh = $chQs->count() > 0 ? round($chQs->avg('accuracy'), 1) : 0; 
                @endphp
                <div class="flex items-center justify-between p-4 rounded-xl bg-[#0a0e17]/80 border border-white/5 hover:border-emerald-500/30 transition group">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-lg bg-emerald-500/10 flex items-center justify-center font-bold text-emerald-400 border border-emerald-500/30">
                            0{{ $id }}
                        </div>
                        <div>
                            <p class="text-sm font-bold text-white">Materi Bab {{ $id }}</p>
                            <p class="text-[10px] text-white/50 mt-0.5">{{ $chQs->count() }} Soal Dievaluasi</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-2xl font-black {{ $avgCh >= 70 ? 'text-emerald-400' : 'text-red-400' }}">{{ $avgCh }}%</span>
                    </div>
                </div>
                @empty
                <p class="text-[11px] text-white/40 text-center py-10">Belum ada data akurasi yang dihitung.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- 4. MODAL: HARD QUESTIONS (SULIT SAJA) --}}
    <div x-show="showHardModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-[#020617]/95 backdrop-blur-md" @click="showHardModal = false"></div>
        <div class="relative w-full max-w-3xl bg-[#0f141e] border border-red-500/40 rounded-2xl shadow-[0_20px_70px_rgba(239,68,68,0.15)] p-6" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
            <div class="flex justify-between items-center mb-6 border-b border-white/5 pb-4">
                <div>
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        Daftar Soal Kritis (Banyak yang Gagal)
                    </h3>
                    <p class="text-[10px] text-red-400 mt-1 font-mono">Hanya menampilkan soal dengan rasio kegagalan > 50%</p>
                </div>
                <button @click="showHardModal = false" class="text-white/40 hover:text-white transition bg-white/5 hover:bg-red-500/20 rounded-full p-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            <div class="max-h-[60vh] overflow-y-auto custom-scrollbar pr-2">
                <table class="w-full text-sm text-left">
                    <thead class="bg-white/5 text-white/40 text-[10px] uppercase font-bold sticky top-0 z-10">
                        <tr>
                            <th class="px-4 py-3 rounded-tl-lg border-b border-white/5">Kutipan Soal</th>
                            <th class="px-4 py-3 text-center border-b border-white/5">Bab</th>
                            <th class="px-4 py-3 text-center border-b border-white/5">Salah / Total Coba</th>
                            <th class="px-4 py-3 text-right rounded-tr-lg border-b border-white/5">Rasio Kegagalan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5 bg-[#0a0e17]/30">
                        @forelse($questions->where('status', 'Sulit') as $q)
                        <tr class="hover:bg-red-500/5 transition group">
                            <td class="px-4 py-4 text-white/80 text-[11px]" title="{{ $q->question_text }}">
                                {{ \Illuminate\Support\Str::limit($q->question_text, 60) }}
                            </td>
                            <td class="px-4 py-4 text-center text-white/50 text-[10px] font-bold">Bab {{ $q->chapter_id }}</td>
                            <td class="px-4 py-4 text-center">
                                <span class="text-red-400 font-bold">{{ $q->wrong_count }}</span> <span class="text-white/30">/ {{ $q->total_attempts }}</span>
                            </td>
                            <td class="px-4 py-4 text-right">
                                <span class="px-2 py-1 rounded bg-red-500/20 text-red-400 font-black text-[10px]">{{ 100 - $q->accuracy }}%</span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-10 text-emerald-400 text-xs font-bold uppercase tracking-widest">Aman! Tidak Ditemukan Soal Sulit.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ==================== MODALS PENGOLAHAN SOAL (FORM/INSIGHT) ==================== --}}
    
    {{-- MODAL CREATE/EDIT QUESTION --}}
    <div id="quizModal" class="fixed inset-0 z-[999999] hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-[#020617]/90 backdrop-blur-md transition-opacity" onclick="closeModal()"></div>
        <div id="modalContent" class="relative w-full max-w-2xl bg-[#0f141e] border border-white/10 rounded-3xl shadow-[0_20px_70px_rgba(0,0,0,0.9)] transform scale-95 opacity-0 transition-all duration-300 flex flex-col max-h-[90vh]">
            <div class="p-5 md:p-6 border-b border-white/5 flex justify-between items-center bg-[#0a0e17] rounded-t-3xl">
                <h3 class="text-lg md:text-xl font-bold text-white flex items-center gap-2" id="modalTitle"><span class="p-1.5 bg-indigo-500/20 rounded-lg text-indigo-400 text-[10px] tracking-widest border border-indigo-500/30 shadow-inner">BARU</span> Tambah Soal</h3>
                <button onclick="closeModal()" class="text-white/40 hover:text-white transition bg-white/5 hover:bg-red-500/20 p-2 rounded-full border border-transparent hover:border-red-500/30"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            <div class="p-5 md:p-6 overflow-y-auto custom-scrollbar flex-1 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] relative">
                <div class="absolute inset-0 bg-[#0f141e]/95 mix-blend-overlay pointer-events-none"></div>
                <form id="quizForm" class="relative z-10">
                    @csrf
                    <input type="hidden" id="questionId" name="id">
                    <div class="space-y-6">
                        <div><label class="text-[10px] font-bold text-white/50 uppercase tracking-wider mb-2 block">Teks Pertanyaan</label><textarea name="question_text" id="inputQuestion" rows="3" class="w-full bg-[#0a0e17] border border-white/10 rounded-xl p-4 text-white text-sm outline-none focus:border-indigo-500 transition resize-none shadow-inner" placeholder="Tuliskan pertanyaan di sini..." required></textarea></div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-[10px] font-bold text-white/50 uppercase tracking-wider mb-2 block">Materi Bab</label>
                                <select name="chapter_id" id="inputChapter" class="w-full bg-[#0a0e17] border border-white/10 rounded-xl px-4 py-3 text-white text-sm outline-none focus:border-indigo-500 cursor-pointer shadow-inner">
                                    <option value="1">Bab 1: Pendahuluan</option>
                                    <option value="2">Bab 2: Layouting</option>
                                    <option value="3">Bab 3: Styling</option>
                                    <option value="99">Evaluasi Akhir (Final)</option>
                                </select>
                            </div>
                            <div><label class="text-[10px] font-bold text-white/50 uppercase tracking-wider mb-2 block">Jawaban Benar</label><select name="correct_answer" id="inputCorrect" class="w-full bg-[#0a0e17] border border-emerald-500/30 rounded-xl px-4 py-3 text-emerald-400 text-sm font-bold outline-none focus:border-emerald-500 cursor-pointer shadow-inner"><option value="option_a">Pilihan A</option><option value="option_b">Pilihan B</option><option value="option_c">Pilihan C</option><option value="option_d">Pilihan D</option></select></div>
                        </div>
                        <div class="space-y-3"><label class="text-[10px] font-bold text-white/50 uppercase tracking-wider block">Opsi Jawaban</label>
                            @foreach(['a','b','c','d'] as $opt)
                            <div class="flex items-center gap-3"><span class="w-10 h-10 rounded-xl bg-[#0a0e17] border border-white/10 shadow-inner flex items-center justify-center font-black text-white/50 text-xs uppercase shrink-0">{{ $opt }}</span><input type="text" name="option_{{ $opt }}" id="inputOption_{{ $opt }}" class="flex-1 bg-[#0a0e17] border border-white/10 rounded-xl px-4 py-2.5 text-white outline-none focus:border-indigo-500 text-sm shadow-inner transition" placeholder="Pilihan {{ strtoupper($opt) }}" required></div>
                            @endforeach
                        </div>
                    </div>
                </form>
            </div>
            <div class="p-5 md:p-6 border-t border-white/5 bg-[#0a0e17] flex justify-end gap-3 rounded-b-3xl">
                <button onclick="closeModal()" class="px-5 md:px-6 py-2.5 rounded-xl text-white/60 hover:text-white hover:bg-white/5 font-bold text-xs transition border border-transparent hover:border-white/10">Batal</button>
                <button onclick="submitForm()" class="px-6 md:px-8 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs shadow-[0_0_15px_rgba(99,102,241,0.4)] transition transform hover:-translate-y-0.5 border border-indigo-400">Simpan Soal</button>
            </div>
        </div>
    </div>

    {{-- MODAL INSIGHT (DETAIL PENJAWAB BENAR/SALAH) --}}
    <div id="insightModal" class="fixed inset-0 z-[999999] hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-[#020617]/90 backdrop-blur-md transition-opacity" onclick="closeInsightModal()"></div>
        <div id="insightContent" class="relative w-full max-w-md bg-[#0f141e] border border-white/10 rounded-3xl shadow-[0_20px_70px_rgba(0,0,0,0.9)] transform scale-95 opacity-0 transition-all duration-300 flex flex-col max-h-[85vh]">
            <div class="p-5 md:p-6 border-b border-white/5 bg-[#0a0e17] flex justify-between items-center rounded-t-3xl">
                <h3 class="font-bold text-white text-lg flex items-center gap-2"><svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg> Tinjauan Siswa</h3>
                <button onclick="closeInsightModal()" class="text-white/40 hover:text-white transition bg-white/5 hover:bg-red-500/20 p-2 rounded-full border border-transparent hover:border-red-500/30"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            <div class="p-5 md:p-6 overflow-y-auto custom-scrollbar flex-1 space-y-6">
                <div>
                    <div class="flex items-center justify-between mb-3"><p class="text-[10px] font-bold text-emerald-400 uppercase tracking-widest flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Jawaban Benar</p><span id="countCorrect" class="text-[10px] bg-emerald-500/10 text-emerald-400 px-3 py-1 rounded-lg font-bold border border-emerald-500/20">0 Siswa</span></div>
                    <div id="listCorrect" class="grid grid-cols-1 sm:grid-cols-2 gap-2"></div>
                </div>
                <div class="h-px bg-white/5 w-full"></div>
                <div>
                    <div class="flex items-center justify-between mb-3"><p class="text-[10px] font-bold text-red-400 uppercase tracking-widest flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span> Jawaban Salah</p><span id="countWrong" class="text-[10px] bg-red-500/10 text-red-400 px-3 py-1 rounded-lg font-bold border border-red-500/20">0 Siswa</span></div>
                    <div id="listWrong" class="grid grid-cols-1 sm:grid-cols-2 gap-2"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPT --}}
    <script>
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

        // --- CHART DONUT ---
        const ctx = document.getElementById('difficultyChart');
        if(ctx) {
            new Chart(ctx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Mudah', 'Sedang', 'Sulit'],
                    datasets: [{
                        data: [
                            {{ $questions->where('status', 'Mudah')->count() }},
                            {{ $questions->where('status', 'Sedang')->count() }},
                            {{ $questions->where('status', 'Sulit')->count() }}
                        ],
                        backgroundColor: ['#10b981', '#eab308', '#ef4444'],
                        borderWidth: 0, hoverOffset: 4
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: {display: false} }, cutout: '75%' }
            });
        }

        // MODAL FUNCTIONS UNTUK BANK SOAL
        function openModal(mode, data = null) {
            $('#quizModal').removeClass('hidden');
            setTimeout(() => { $('#modalContent').removeClass('scale-95 opacity-0').addClass('scale-100 opacity-100'); }, 10);
            if(mode === 'create') {
                $('#modalTitle').html('<span class="p-1.5 bg-indigo-500/20 rounded-lg text-indigo-400 text-[10px] tracking-widest border border-indigo-500/30 shadow-inner">BARU</span> Tambah Soal');
                $('#quizForm')[0].reset(); $('#questionId').val(''); $('#inputChapter').val(1);
            } else {
                $('#modalTitle').html('<span class="p-1.5 bg-amber-500/20 rounded-lg text-amber-400 text-[10px] tracking-widest border border-amber-500/30 shadow-inner">EDIT</span> Perbarui Soal');
                $('#questionId').val(data.id); $('#inputQuestion').val(data.question_text); $('#inputChapter').val(data.chapter_id);
                if(data.options && data.options.length >= 4) {
                    $('#inputOption_a').val(data.options[0].option_text); $('#inputOption_b').val(data.options[1].option_text);
                    $('#inputOption_c').val(data.options[2].option_text); $('#inputOption_d').val(data.options[3].option_text);
                    if(data.options[0].is_correct) $('#inputCorrect').val('option_a'); else if(data.options[1].is_correct) $('#inputCorrect').val('option_b');
                    else if(data.options[2].is_correct) $('#inputCorrect').val('option_c'); else if(data.options[3].is_correct) $('#inputCorrect').val('option_d');
                }
            }
        }
        function closeModal() { $('#modalContent').removeClass('scale-100 opacity-100').addClass('scale-95 opacity-0'); setTimeout(() => { $('#quizModal').addClass('hidden'); }, 300); }
        
        function submitForm() {
            const form = $('#quizForm'); const id = $('#questionId').val(); const url = id ? `/admin/questions/update/${id}` : `{{ route('admin.questions.store') }}`;
            if(!form[0].checkValidity()) { form[0].reportValidity(); return; }
            Swal.fire({ title: 'Menyimpan...', didOpen: () => Swal.showLoading(), background: '#0f141e', color: '#fff' });
            $.post(url, form.serialize()).done((res) => { Swal.fire({ title: 'Berhasil!', text: res.message, icon: 'success', background: '#0f141e', color: '#fff', confirmButtonColor: '#6366f1' }).then(() => location.reload()); }).fail((err) => { Swal.fire({ title: 'Error', text: err.responseJSON?.message || 'Terjadi kesalahan sistem', icon: 'error', background: '#0f141e', color: '#fff', confirmButtonColor: '#ef4444' }); });
        }

        function confirmDelete(id) {
            Swal.fire({ title: 'Hapus Pertanyaan?', text: "Tindakan ini akan menghapus soal secara permanen!", icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#334155', confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal', background: '#0f141e', color: '#fff' }).then((result) => { if (result.isConfirmed) { $.ajax({ url: `/admin/questions/delete/${id}`, type: 'DELETE', success: function(res) { Swal.fire({ title: 'Terhapus!', text: res.message, icon: 'success', background: '#0f141e', color: '#fff', confirmButtonColor: '#6366f1' }).then(() => location.reload()); } }); } });
        }

        function openInsightModal(correct, wrong) {
            $('#countCorrect').text(correct.length + ' Siswa'); $('#countWrong').text(wrong.length + ' Siswa');
            const renderList = (list, color) => list.length ? list.map(name => `<div class="flex items-center gap-3 px-3 py-2.5 rounded-xl bg-[#0a0e17] border border-${color}-500/20 text-xs text-white shadow-inner transition hover:border-${color}-500/50"><div class="w-6 h-6 rounded-lg bg-${color}-500/20 border border-${color}-500/30 text-${color}-400 flex items-center justify-center font-bold text-[10px] shadow-inner shrink-0">${name.charAt(0)}</div><span class="font-medium truncate">${name}</span></div>`).join('') : `<div class="col-span-full"><p class="text-[10px] text-white/30 italic pl-1 border border-dashed border-white/10 p-3 rounded-xl text-center bg-[#0a0e17]/50">Tidak ada peserta pada kategori ini.</p></div>`;
            $('#listCorrect').html(renderList(correct, 'emerald')); $('#listWrong').html(renderList(wrong, 'red'));
            $('#insightModal').removeClass('hidden'); setTimeout(() => { $('#insightContent').removeClass('scale-95 opacity-0').addClass('scale-100 opacity-100'); }, 10);
        }
        function closeInsightModal() { $('#insightContent').removeClass('scale-100 opacity-100').addClass('scale-95 opacity-0'); setTimeout(() => { $('#insightModal').addClass('hidden'); }, 300); }
    </script>

</body>
</html>