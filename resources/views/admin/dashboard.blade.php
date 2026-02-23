<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics Dashboard · Utilwind</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    
    {{-- RESOURCES --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

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
        }
        .glass-card:hover { border-color: rgba(99, 102, 241, 0.4); transform: translateY(-4px); box-shadow: 0 10px 40px -10px rgba(0,0,0,0.5); z-index: 30; }
        
        /* Container SVG Background Card (Bebaskan Tooltip) */
        .card-bg-gfx { position: absolute; inset: 0; overflow: hidden; border-radius: 1rem; pointer-events: none; z-index: 0; }

        /* --- INPUTS & NAV --- */
        .glass-input { background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.1); color: white; transition: 0.3s; }
        .glass-input:focus { border-color: var(--accent); background: rgba(255, 255, 255, 0.05); outline: none; box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2); }
        .nav-link { display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 12px; color: #94a3b8; font-weight: 500; font-size: 0.9rem; transition: all 0.2s; border: 1px solid transparent; }
        .nav-link:hover { background: rgba(255, 255, 255, 0.03); color: white; }
        .nav-link.active { background: linear-gradient(90deg, rgba(99, 102, 241, 0.1) 0%, rgba(99, 102, 241, 0) 100%); color: #818cf8; border-left: 3px solid #818cf8; border-radius: 4px 12px 12px 4px; }

        .reveal { opacity: 0; transform: translateY(20px); animation: revealAnim 0.6s forwards; }
        @keyframes revealAnim { to { opacity: 1; transform: translateY(0); } }
        .table-row { transition: background 0.2s; border-bottom: 1px solid rgba(255,255,255,0.03); }
        .table-row:hover { background: rgba(255,255,255,0.02); }

        /* =========================================================================
           SISTEM TOOLTIP SOLID (ANTI POTONG)
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
            background-color: #0f141e; color: #e2e8f0; font-size: 11px; padding: 12px 14px; line-height: 1.5;
            border-radius: 12px; box-shadow: 0 20px 50px rgba(0,0,0,0.9); z-index: 99999;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
        }

        .tooltip-down .tooltip-content { top: calc(100% + 12px); left: 50%; transform: translateX(-50%) translateY(-10px); }
        .tooltip-down:hover .tooltip-content { transform: translateX(-50%) translateY(0); opacity: 1; visibility: visible; }
        .tooltip-down .tooltip-content::after { content: ''; position: absolute; bottom: 100%; left: 50%; margin-left: -6px; border-width: 6px; border-style: solid; border-color: transparent transparent #0f141e transparent; }

        .tooltip-up .tooltip-content { bottom: calc(100% + 12px); left: 50%; transform: translateX(-50%) translateY(10px); }
        .tooltip-up:hover .tooltip-content { transform: translateX(-50%) translateY(0); opacity: 1; visibility: visible; }
        .tooltip-up .tooltip-content::after { content: ''; position: absolute; top: 100%; left: 50%; margin-left: -6px; border-width: 6px; border-style: solid; border-color: #0f141e transparent transparent transparent; }

        .tooltip-left .tooltip-content { left: auto; right: -12px; transform: translateX(0) translateY(-10px); }
        .tooltip-down.tooltip-left:hover .tooltip-content { transform: translateX(0) translateY(0); }
        .tooltip-up.tooltip-left .tooltip-content { transform: translateX(0) translateY(10px); }
        .tooltip-up.tooltip-left:hover .tooltip-content { transform: translateX(0) translateY(0); }
        .tooltip-left .tooltip-content::after { left: auto; right: 15px; margin-left: 0; }

        .tooltip-indigo .tooltip-trigger { background-color: #6366f1; box-shadow: 0 0 10px rgba(99,102,241,0.5); }
        .tooltip-indigo .tooltip-trigger:hover { background-color: #818cf8; box-shadow: 0 0 15px rgba(99,102,241,0.8); }
        .tooltip-indigo .tooltip-content { border: 1px solid rgba(99,102,241,0.4); }

        .tooltip-cyan .tooltip-trigger { background-color: #06b6d4; box-shadow: 0 0 10px rgba(6,182,212,0.5); }
        .tooltip-cyan .tooltip-trigger:hover { background-color: #22d3ee; box-shadow: 0 0 15px rgba(6,182,212,0.8); }
        .tooltip-cyan .tooltip-content { border: 1px solid rgba(6,182,212,0.4); }

        .tooltip-emerald .tooltip-trigger { background-color: #10b981; box-shadow: 0 0 10px rgba(16,185,129,0.5); }
        .tooltip-emerald .tooltip-trigger:hover { background-color: #34d399; box-shadow: 0 0 15px rgba(16,185,129,0.8); }
        .tooltip-emerald .tooltip-content { border: 1px solid rgba(16,185,129,0.4); }

        .tooltip-red .tooltip-trigger { background-color: #ef4444; box-shadow: 0 0 10px rgba(239,68,68,0.5); }
        .tooltip-red .tooltip-trigger:hover { background-color: #f87171; box-shadow: 0 0 15px rgba(239,68,68,0.8); }
        .tooltip-red .tooltip-content { border: 1px solid rgba(239,68,68,0.4); }

        .tooltip-fuchsia .tooltip-trigger { background-color: #d946ef; box-shadow: 0 0 10px rgba(217,70,239,0.5); }
        .tooltip-fuchsia .tooltip-trigger:hover { background-color: #e879f9; box-shadow: 0 0 15px rgba(217,70,239,0.8); }
        .tooltip-fuchsia .tooltip-content { border: 1px solid rgba(217,70,239,0.4); }

        .tooltip-yellow .tooltip-trigger { background-color: #eab308; color: #020617; box-shadow: 0 0 10px rgba(234,179,8,0.5); }
        .tooltip-yellow .tooltip-trigger:hover { background-color: #facc15; box-shadow: 0 0 15px rgba(234,179,8,0.8); }
        .tooltip-yellow .tooltip-content { border: 1px solid rgba(234,179,8,0.4); }

        .modal-open { overflow: hidden; padding-right: 5px; } 
    </style>
</head>
<body class="flex h-screen w-full" x-data="{ 
    sidebarOpen: false, showImport: false, showAdd: false, 
    showLabModal: false, showStudentModal: false, showQuizModal: false, 
    showAvgModal: false, showRemedialModal: false, showPassedModal: false, isFullscreen: false 
}" @keydown.escape.window="isFullscreen = false; document.exitFullscreen(); showLabModal = false; showStudentModal = false; showQuizModal = false; showAvgModal = false; showRemedialModal = false; showPassedModal = false;" :class="{'modal-open': showStudentModal || showQuizModal || showLabModal || showAvgModal || showRemedialModal || showPassedModal || showAdd || showImport}">

    {{-- ==============================================================================
         LOGIKA DATA BLADE (AMAN & BEBAS DUPLIKAT)
         ============================================================================== --}}
    @php
        $totalPassedQuizzesCount = 0;
        $passedQuizzesDetail = collect();
        $passRate = 0;
        $realRemedialCount = 0;
        $trueRemedialList = collect();
        $realLabCount = 0;
        $passedLabsDetail = collect();
        $chapterAverages = collect();

        // 1. Kuis Lulus (Score >= 70) Unique
        try {
            $passedQuery = DB::table('quiz_attempts')
                ->join('users', 'quiz_attempts.user_id', '=', 'users.id')
                ->select('users.name', 'quiz_attempts.score', 'quiz_attempts.chapter_id', 'quiz_attempts.created_at')
                ->where('quiz_attempts.score', '>=', 70)
                ->orderByDesc('quiz_attempts.created_at')
                ->get();
            
            $passedQuizzesDetail = $passedQuery->unique(function ($item) { return $item->name . '-' . $item->chapter_id; });
            $totalPassedQuizzesCount = DB::table('quiz_attempts')->where('score', '>=', 70)->count();
            
            if(isset($totalAttempts) && $totalAttempts > 0) {
                $passRate = round(($totalPassedQuizzesCount / $totalAttempts) * 100);
            }
        } catch(\Exception $e) {}

        // 2. Remedial Akurat (Hanya Gagal & Belum Lulus)
        try {
            $passedUserIds = DB::table('quiz_attempts')->where('score', '>=', 70)->pluck('user_id')->toArray();
            $remQuery = DB::table('quiz_attempts')
                ->join('users', 'quiz_attempts.user_id', '=', 'users.id')
                ->select('users.name', 'quiz_attempts.score', 'quiz_attempts.chapter_id', 'quiz_attempts.created_at')
                ->whereNotIn('quiz_attempts.user_id', $passedUserIds) 
                ->orderByDesc('quiz_attempts.created_at')
                ->get()
                ->unique('name'); 

            $trueRemedialList = $remQuery->take(10);
            $realRemedialCount = $remQuery->count();
        } catch(\Exception $e) {}

        // 3. Lab Completed (Sesuai lab_histories = passed)
        try {
            $labQuery = DB::table('lab_histories')
                ->join('users', 'lab_histories.user_id', '=', 'users.id')
                ->leftJoin('labs', 'lab_histories.lab_id', '=', 'labs.id')
                ->select('users.name as student_name', 'labs.title as lab_title', 'lab_histories.lab_id', 'lab_histories.final_score', 'lab_histories.created_at')
                ->where('lab_histories.status', 'passed');

            $realLabCount = $labQuery->count();
            $passedLabsDetail = $labQuery->orderByDesc('lab_histories.created_at')->take(15)->get();
        } catch (\Exception $e) {
            if(isset($totalLabsCompleted)) $realLabCount = $totalLabsCompleted;
        }

        // 4. Rata-Rata Per Bab
        try {
            $chapterAverages = DB::table('quiz_attempts')
                ->select('chapter_id', DB::raw('ROUND(AVG(score),1) as avg_score'), DB::raw('COUNT(*) as total'))
                ->groupBy('chapter_id')
                ->get();
        } catch (\Exception $e) {}
    @endphp

    {{-- ==================== 1. SIDEBAR (RESPONSIVE) ==================== --}}
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
            <div class="absolute top-[10%] left-[20%] w-[500px] h-[500px] bg-indigo-600/10 rounded-full blur-[120px]"></div>
            <div class="absolute bottom-[10%] right-[10%] w-[400px] h-[400px] bg-fuchsia-600/10 rounded-full blur-[120px]"></div>
            <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.04] mix-blend-overlay"></div>
        </div>

       {{-- HEADER RESPONSIVE & BREADCRUMB IDENTIK --}}
        <header class="h-24 glass-header flex flex-col justify-center px-6 md:px-10 shrink-0 sticky top-0 z-40">
            <div class="flex items-center justify-between w-full">
                <div class="flex items-center gap-4">
                    {{-- Hamburger Menu --}}
                    <button @click="sidebarOpen = true" class="md:hidden p-2 bg-white/5 rounded-lg text-white hover:bg-white/10 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    
                    {{-- Judul & Breadcrumb --}}
                    <div class="flex items-center gap-3">
                        <div>
                            <nav class="flex text-[10px] text-white/50 mb-1.5 font-bold hidden sm:flex" aria-label="Breadcrumb">
                                <ol class="inline-flex items-center space-x-1">
                                    <li class="inline-flex items-center"><a href="" class="hover:text-indigo-400 transition">Dashboard</a></li>
                                    
                                </ol>
                            </nav>
                            <h2 class="text-white font-bold text-lg md:text-xl tracking-tight">Analytics Overview</h2>
                            <p class="text-[9px] md:text-xs text-white/40 flex items-center gap-1.5 mt-0.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse shadow-[0_0_8px_#10b981]"></span>
                                Live Data Monitoring
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center gap-3 sm:gap-6">
                    {{-- Tombol Refresh & Fullscreen --}}
                    <button onclick="window.location.reload()" class="p-2.5 text-white/40 hover:text-white transition rounded-full hover:bg-white/5 group hidden sm:block border border-transparent hover:border-white/10" title="Refresh">
                        <svg class="w-4 h-4 group-hover:rotate-180 transition-transform duration-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </button>

                    <button @click="isFullscreen = !isFullscreen; isFullscreen ? document.documentElement.requestFullscreen() : document.exitFullscreen()" class="p-2.5 text-white/40 hover:text-white transition rounded-full hover:bg-white/5 hidden md:block border border-transparent hover:border-white/10" title="Fullscreen">
                        <svg x-show="!isFullscreen" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                        <svg x-show="isFullscreen" style="display: none;" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>

                    {{-- Action Button (Enroll) - Desktop --}}
                    <div class="border-l border-white/10 pl-5 ml-1 hidden lg:block">
                        <button @click="showAdd = true" class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow-[0_0_15px_rgba(99,102,241,0.3)] transition border border-indigo-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg> Enroll Student
                        </button>
                    </div>

                    {{-- Time Display --}}
                    <div class="text-right hidden lg:block border-l border-white/10 pl-5 ml-1">
                        <p class="text-sm font-bold text-white">{{ \Carbon\Carbon::now()->translatedFormat('d M Y') }}</p>
                        <p class="text-[10px] text-white/40 font-mono mt-0.5">{{ \Carbon\Carbon::now()->format('H:i') }} WIB</p>
                    </div>

                    {{-- Mobile Action Button --}}
                    <button @click="showAdd = true" class="lg:hidden p-2 rounded-lg bg-indigo-600 text-white shadow-[0_0_10px_rgba(99,102,241,0.5)]">
                         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    </button>
                </div>
            </div>
        </header>

        {{-- Scroll Area Data --}}
        <div class="flex-1 p-4 md:p-10 relative z-10">
            <div class="max-w-7xl mx-auto space-y-8">

                {{-- =======================================================
                     1. HERO INSIGHT SECTION (LAYOUT ORISINAL 3 KARTU)
                     ======================================================= --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 reveal">
                    
                    {{-- Card 1: Passed Quizzes --}}
                    <div class="glass-card rounded-2xl group/card flex flex-col justify-between">
                        <div class="card-bg-gfx">
                            <div class="absolute right-0 top-0 p-4 opacity-10 transition-transform duration-500 group-hover/card:scale-110">
                                <svg class="w-20 h-20 text-cyan-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            </div>
                        </div>

                        <div class="p-6 relative z-20 flex flex-col h-full">
                            <h3 class="text-lg font-bold text-white mb-4 flex items-center justify-between">
                                Kuis Lulus
                                <div class="tooltip-container tooltip-cyan tooltip-down tooltip-left">
                                    <div class="tooltip-trigger">?</div>
                                    <div class="tooltip-content">
                                        <span class="font-bold text-cyan-400 block mb-1">Filter Aktif:</span>
                                        Menampilkan pengumpulan kuis unik terbaru yang berhasil mencapai KKM (Skor >= 70).
                                    </div>
                                </div>
                            </h3>
                            
                            <div class="space-y-3 flex-1 mb-4">
                                @forelse($passedQuizzesDetail->take(3) as $act)
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-cyan-500/20 flex items-center justify-center text-xs font-bold text-cyan-400 border border-cyan-500/30">
                                        {{ substr($act->name, 0, 1) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-bold text-white truncate">{{ $act->name }}</p>
                                        <p class="text-[9px] text-white/40 mt-0.5">Skor: {{ $act->score }} • {{ \Carbon\Carbon::parse($act->created_at)->diffForHumans() }}</p>
                                    </div>
                                </div>
                                @empty
                                <p class="text-xs text-white/30 italic text-center py-4">Belum ada kuis yang lulus.</p>
                                @endforelse
                            </div>

                            <div class="pt-3 border-t border-white/5 mt-auto">
                                <button @click="showQuizModal = true" class="text-[10px] font-bold text-cyan-400 hover:text-white transition flex items-center gap-1 w-max">
                                    Lihat Daftar Kelulusan &rarr;
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Card 2: Remedial Warning (Unique, Tanpa Lulus) --}}
                    <div class="glass-card rounded-2xl group/card flex flex-col justify-between">
                        <div class="card-bg-gfx">
                            <div class="absolute right-0 top-0 p-4 opacity-10 transition-transform duration-500 group-hover/card:scale-110">
                                <svg class="w-20 h-20 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            </div>
                        </div>

                        <div class="p-6 relative z-20 flex flex-col h-full">
                            <h3 class="text-lg font-bold text-white mb-4 flex items-center justify-between">
                                Peringatan Remedial
                                <div class="tooltip-container tooltip-red tooltip-down tooltip-left">
                                    <div class="tooltip-trigger">?</div>
                                    <div class="tooltip-content">
                                        <span class="font-bold text-red-400 block mb-1">Aturan Filter:</span>
                                        Siswa unik yang saat ini gagal mencapai nilai KKM (Skor < 70). Siswa yang sudah lulus dikecualikan.
                                    </div>
                                </div>
                            </h3>

                            <div class="space-y-3 flex-1 mb-4">
                                @forelse($trueRemedialList->take(3) as $act)
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-red-500/20 flex items-center justify-center text-xs font-bold text-red-400 border border-red-500/30">!</div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-bold text-white truncate">{{ $act->name }}</p>
                                        <p class="text-[9px] text-red-400 mt-0.5">Skor: {{ $act->score }} (Kurang: {{ 70 - $act->score }})</p>
                                    </div>
                                </div>
                                @empty
                                <div class="flex items-center justify-center gap-2 py-6 text-emerald-400">
                                    <span class="text-xs font-bold uppercase tracking-widest">Semua Aman!</span>
                                    <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                @endforelse
                            </div>

                            <div class="pt-3 border-t border-white/5 mt-auto">
                                <button @click="showRemedialModal = true" class="text-[10px] font-bold text-red-400 hover:text-white transition flex items-center gap-1 w-max">
                                    Cek Daftar Tindakan &rarr;
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Card 3: Lab Completion (Orisinal Layout) --}}
                    <div class="glass-card rounded-2xl group/card flex flex-col justify-between">
                        <div class="card-bg-gfx">
                            <div class="absolute right-0 top-0 p-4 opacity-10 transition-transform duration-500 group-hover/card:scale-110">
                                <svg class="w-20 h-20 text-fuchsia-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                            </div>
                        </div>
                        
                        <div class="p-6 relative z-20 flex flex-col h-full">
                            <h3 class="text-lg font-bold text-white mb-4 flex items-center justify-between">
                                Penyelesaian Lab
                                <div class="tooltip-container tooltip-fuchsia tooltip-down tooltip-left">
                                    <div class="tooltip-trigger">?</div>
                                    <div class="tooltip-content">
                                        <span class="font-bold text-fuchsia-400 block mb-1">Sumber Data:</span>
                                        Total sesi praktikum lab tervalidasi yang dievaluasi dengan status 'lulus'.
                                    </div>
                                </div>
                            </h3>
                            
                            <div class="flex items-center justify-between mt-2 flex-1">
                                <div>
                                    <span class="text-4xl font-black text-white drop-shadow-md">{{ $realLabCount }}</span>
                                    <span class="text-sm text-white/40 block mt-1">Total Lulus</span>
                                </div>
                                <div class="text-right">
                                    <button @click="showLabModal = true" class="px-3 py-1.5 rounded-lg bg-fuchsia-600/20 text-fuchsia-400 text-[10px] font-bold border border-fuchsia-600/30 hover:bg-fuchsia-600 hover:text-white transition">
                                        Lihat Data
                                    </button>
                                </div>
                            </div>
                            
                            <div class="mt-6 pt-3 border-t border-white/5">
                                <div class="w-full bg-white/10 h-1.5 rounded-full overflow-hidden shadow-inner border border-white/5">
                                    <div class="h-full bg-gradient-to-r from-fuchsia-600 to-fuchsia-400 w-3/4 animate-pulse relative"></div>
                                </div>
                                <p class="text-[9px] text-white/30 mt-2 text-right">Progres Global</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- =======================================================
                     2. STATS GRID (LAYOUT ORISINAL 4 KARTU)
                     Remedial diganti Pass Rate agar tidak duplikat
                     ======================================================= --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 reveal" style="animation-delay: 0.2s;">
                    
                    {{-- Total Students --}}
                    <div class="glass-card rounded-2xl p-5 border-l-4 border-l-indigo-500 cursor-pointer group transition-all" @click="showStudentModal = true">
                        <div class="flex justify-between items-start">
                            <p class="text-[10px] uppercase font-bold text-white/40 tracking-widest group-hover:text-indigo-400 transition">Total Siswa</p>
                            <div class="tooltip-container tooltip-indigo tooltip-up tooltip-left">
                                <div class="tooltip-trigger bg-transparent border-transparent shadow-none text-white/30 group-hover:text-indigo-400">?</div>
                                <div class="tooltip-content">Total akun pengguna aktif yang saat ini terdaftar dengan peran 'siswa'.</div>
                            </div>
                        </div>
                        <h3 class="text-2xl md:text-3xl font-black text-white mt-2">{{ number_format($totalStudents) }}</h3>
                        <p class="text-[9px] text-indigo-400 mt-2 opacity-0 group-hover:opacity-100 transition translate-y-2 group-hover:translate-y-0">Lihat Daftar &rarr;</p>
                    </div>

                    {{-- Passed Quizzes --}}
                    <div class="glass-card rounded-2xl p-5 border-l-4 border-l-cyan-500 cursor-pointer group transition-all" @click="showQuizModal = true">
                        <div class="flex justify-between items-start">
                            <p class="text-[10px] uppercase font-bold text-white/40 tracking-widest group-hover:text-cyan-400 transition">Kuis Lulus</p>
                            <div class="tooltip-container tooltip-cyan tooltip-up tooltip-left">
                                <div class="tooltip-trigger bg-transparent border-transparent shadow-none text-white/30 group-hover:text-cyan-400">?</div>
                                <div class="tooltip-content">Total pengumpulan kuis yang berhasil mencapai KKM (Skor >= 70).</div>
                            </div>
                        </div>
                        <h3 class="text-2xl md:text-3xl font-black text-white mt-2">{{ number_format($totalPassedQuizzesCount) }}</h3>
                        <p class="text-[9px] text-cyan-400 mt-2 opacity-0 group-hover:opacity-100 transition translate-y-2 group-hover:translate-y-0">Lihat Riwayat &rarr;</p>
                    </div>

                    {{-- Global Avg --}}
                    <div class="glass-card rounded-2xl p-5 border-l-4 border-l-emerald-500 cursor-pointer group transition-all" @click="showAvgModal = true">
                        <div class="flex justify-between items-start">
                            <p class="text-[10px] uppercase font-bold text-white/40 tracking-widest group-hover:text-emerald-400 transition">Rata-rata Global</p>
                            <div class="tooltip-container tooltip-emerald tooltip-up tooltip-left">
                                <div class="tooltip-trigger bg-transparent border-transparent shadow-none text-white/30 group-hover:text-emerald-400">?</div>
                                <div class="tooltip-content">Perhitungan nilai rata-rata dari semua kuis teori yang dievaluasi di dalam sistem.</div>
                            </div>
                        </div>
                        <div class="flex items-baseline gap-1 mt-2">
                            <h3 class="text-2xl md:text-3xl font-black text-white">{{ $globalAverage }}</h3>
                            <span class="text-[10px] text-emerald-500 font-bold">Pts</span>
                        </div>
                        <p class="text-[9px] text-emerald-400 mt-2 opacity-0 group-hover:opacity-100 transition translate-y-2 group-hover:translate-y-0">Lihat Bab &rarr;</p>
                    </div>

                    {{-- Pass Rate --}}
                    <div class="glass-card rounded-2xl p-5 border-l-4 border-l-violet-500 cursor-pointer group transition-all" @click="showPassedModal = true">
                        <div class="flex justify-between items-start">
                            <p class="text-[10px] uppercase font-bold text-white/40 tracking-widest group-hover:text-violet-400 transition">Tingkat Kelulusan</p>
                            <div class="tooltip-container tooltip-violet tooltip-up tooltip-left">
                                <div class="tooltip-trigger bg-transparent border-transparent shadow-none text-white/30 group-hover:text-violet-400">?</div>
                                <div class="tooltip-content">Persentase dari total pengumpulan kuis yang berhasil melewati ambang batas nilai minimum.</div>
                            </div>
                        </div>
                        <div class="flex items-baseline gap-1 mt-2">
                            <h3 class="text-2xl md:text-3xl font-black text-white">{{ $passRate }}</h3>
                            <span class="text-lg text-violet-400 font-bold">%</span>
                        </div>
                        <p class="text-[9px] text-violet-400 mt-2 opacity-0 group-hover:opacity-100 transition translate-y-2 group-hover:translate-y-0">Lihat Detail &rarr;</p>
                    </div>
                </div>

                {{-- =======================================================
                     3. CHART & LEADERBOARD (LAYOUT ORISINAL)
                     ======================================================= --}}
                <div class="grid lg:grid-cols-3 gap-8 reveal" style="animation-delay: 0.3s;">
                    
                    {{-- Chart --}}
                    <div class="lg:col-span-2 glass-card rounded-2xl p-6 flex flex-col relative z-20">
                        <div class="flex justify-between items-center mb-6 relative z-10">
                            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                                Tren Performa (7 Hari)
                                <div class="tooltip-container tooltip-indigo tooltip-up tooltip-left">
                                    <div class="tooltip-trigger">?</div>
                                    <div class="tooltip-content">
                                        Melacak rata-rata skor kuis harian yang dikumpulkan untuk mengukur stabilitas.
                                    </div>
                                </div>
                            </h3>
                            <div class="flex p-1 bg-[#0a0e17] rounded-lg border border-white/5 shadow-inner" x-data="{ currentType: 'line' }">
                                <button @click="currentType = 'line'; updateChartType('line')" :class="currentType === 'line' ? 'bg-indigo-500 text-white shadow-md' : 'text-white/50 hover:text-white'" class="px-3 py-1.5 text-[9px] font-bold rounded transition">Line</button>
                                <button @click="currentType = 'bar'; updateChartType('bar')" :class="currentType === 'bar' ? 'bg-indigo-500 text-white shadow-md' : 'text-white/50 hover:text-white'" class="px-3 py-1.5 text-[9px] font-bold rounded transition">Bar</button>
                            </div>
                        </div>
                        <div class="flex-1 w-full h-[300px] relative z-10">
                            <canvas id="quizChart"></canvas>
                        </div>
                    </div>

                    {{-- Leaderboard --}}
                    <div class="glass-card rounded-2xl p-6 flex flex-col h-full z-20 border-t-2 border-t-yellow-500/50">
                        <h3 class="text-lg font-bold text-white mb-1 flex items-center justify-between">
                            <span class="flex items-center gap-2"><span class="text-yellow-400 drop-shadow-[0_0_8px_#eab308]">🏆</span> Top 5 Siswa</span>
                            <div class="tooltip-container tooltip-yellow tooltip-up tooltip-left">
                                <div class="tooltip-trigger text-[#020617]">?</div>
                                <div class="tooltip-content">
                                    <span class="font-bold text-yellow-400 block mb-1">Algoritma Peringkat:</span>
                                    Dihitung dari rata-rata nilai kuis tertinggi. Mengecualikan metrik validasi lab.
                                </div>
                            </div>
                        </h3>
                        <p class="text-[10px] text-white/40 mb-4 pb-2 border-b border-white/5">Peringkat berdasarkan rata-rata kuis tertinggi.</p>
                        
                        <div class="flex-1 overflow-y-auto custom-scrollbar space-y-3 pr-2">
                            @forelse($topStudents as $index => $student)
                            <div class="flex items-center gap-4 p-3 rounded-xl bg-[#0a0e17]/50 border border-white/5 hover:border-white/10 transition group">
                                <div class="w-9 h-9 rounded-lg flex items-center justify-center font-black text-xs shadow-inner
                                    {{ $index == 0 ? 'bg-gradient-to-br from-yellow-300 to-yellow-600 text-[#020617] shadow-[0_0_10px_#eab308]' : 
                                      ($index == 1 ? 'bg-gradient-to-br from-gray-300 to-gray-500 text-[#020617]' : 
                                      ($index == 2 ? 'bg-gradient-to-br from-amber-600 to-amber-800 text-white' : 'bg-[#0f141e] text-white/50 border border-white/10')) }}">
                                    #{{ $index + 1 }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-bold text-white truncate group-hover:text-yellow-200 transition">{{ $student->name }}</p>
                                    <p class="text-[10px] text-white/40 font-mono mt-0.5">{{ $student->email }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="block text-sm font-black text-emerald-400">{{ round($student->avg_score, 1) }}</span>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-10 text-white/30 text-xs bg-[#0a0e17]/50 rounded-xl border border-dashed border-white/10">Belum ada data peringkat.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- =======================================================
                     4. QUESTION ANALYSIS (LAYOUT ORISINAL)
                     ======================================================= --}}
                <div class="glass-card rounded-2xl p-6 relative z-10 reveal" style="animation-delay: 0.4s;">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-5 gap-4">
                        <div>
                            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                                Analisis Soal (Top 10)
                                <div class="tooltip-container tooltip-indigo tooltip-up tooltip-left">
                                    <div class="tooltip-trigger">?</div>
                                    <div class="tooltip-content">
                                        Tingkat akurasi per soal spesifik untuk mengidentifikasi topik sulit yang perlu ditinjau ulang.
                                    </div>
                                </div>
                            </h3>
                            <p class="text-[10px] text-white/40 mt-1">Metrik untuk meninjau efektivitas kurikulum.</p>
                        </div>
                        
                        <a href="{{ route('admin.analytics.questions') }}" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-[11px] font-bold shadow-[0_0_15px_rgba(99,102,241,0.3)] transition flex items-center gap-1.5 border border-indigo-400">
                            Analisis Penuh <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>
                    
                    <div class="overflow-x-auto custom-scrollbar border border-white/5 rounded-xl shadow-inner bg-[#0a0e17]/50">
                        <table class="w-full text-sm text-left whitespace-nowrap md:whitespace-normal">
                            <thead class="bg-[#0f141e] text-white/40 text-[10px] uppercase font-bold border-b border-white/5">
                                <tr>
                                    <th class="px-5 py-4 w-[50%]">Kutipan Soal</th>
                                    <th class="px-5 py-4 text-center">Jawaban</th>
                                    <th class="px-5 py-4 text-center">Tingkat Akurasi</th>
                                    <th class="px-5 py-4 text-right">Tingkat Kesulitan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @forelse($questionStats as $q)
                                <tr class="hover:bg-white/5 transition group table-row">
                                    <td class="px-5 py-4 text-[11px] text-white/80 font-medium group-hover:text-white transition" title="{{ $q->question_text }}">
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-1.5 h-1.5 rounded-full shadow-lg {{ $q->accuracy >= 70 ? 'bg-emerald-500 shadow-[0_0_5px_#10b981]' : ($q->accuracy >= 40 ? 'bg-yellow-500 shadow-[0_0_5px_#eab308]' : 'bg-red-500 shadow-[0_0_5px_#ef4444]') }}"></div>
                                            <span class="truncate max-w-[200px] md:max-w-[400px]">{{ \Illuminate\Support\Str::limit($q->question_text, 65) }}</span>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 text-center text-white/50 text-[10px] font-mono">{{ $q->total_answers }} Siswa</td>
                                    <td class="px-5 py-4 text-center">
                                        <div class="flex items-center justify-center gap-3">
                                            <div class="flex-1 max-w-[100px] h-1.5 bg-[#020617] rounded-full overflow-hidden border border-white/5 hidden md:block shadow-inner">
                                                <div class="h-full rounded-full transition-all duration-1000 
                                                    {{ $q->accuracy >= 70 ? 'bg-emerald-500' : ($q->accuracy >= 40 ? 'bg-yellow-500' : 'bg-red-500') }}" 
                                                    style="width: {{ $q->accuracy }}%"></div>
                                            </div>
                                            <span class="font-black text-[11px] w-8 text-right {{ $q->accuracy >= 70 ? 'text-emerald-400' : ($q->accuracy >= 40 ? 'text-yellow-400' : 'text-red-400') }}">{{ $q->accuracy }}%</span>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 text-right">
                                        <span class="px-3 py-1.5 rounded-lg text-[8px] font-bold border uppercase tracking-wider
                                            {{ $q->difficulty == 'Mudah' ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20' : 
                                              ($q->difficulty == 'Sedang' ? 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20' : 'bg-red-500/10 text-red-400 border-red-500/20') }}">
                                            {{ $q->difficulty }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="text-center py-10 text-white/30 text-[10px] italic">Belum ada data analitik yang tersedia.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- =======================================================
                     5. STUDENT DIRECTORY (CLEAN NO DUPLICATES)
                     ======================================================= --}}
                <div class="glass-card rounded-2xl relative z-10 reveal flex flex-col" style="animation-delay: 0.5s;" x-data="{ searchQuery: '' }">
                    <div class="p-6 border-b border-white/5 bg-[#020617]/40 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 rounded-t-2xl">
                        <div>
                            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                                <span class="text-indigo-400">👥</span> Direktori Siswa
                                <div class="tooltip-container tooltip-indigo tooltip-up tooltip-left">
                                    <div class="tooltip-trigger">?</div>
                                    <div class="tooltip-content">
                                        <span class="font-bold text-indigo-400 block mb-1">Tanpa Duplikasi:</span>
                                        Desain tabel bersih. Titik Hijau pada avatar pengguna menandakan akun yang AKTIF & Tervalidasi.
                                    </div>
                                </div>
                            </h3>
                            <p class="text-xs text-white/40 mt-1">Mengelola {{ $totalStudents }} siswa aktif.</p>
                        </div>
                        
                        <div class="flex flex-wrap items-center gap-3 w-full md:w-auto relative z-40">
                            <div class="relative w-full sm:w-64">
                                <input type="text" x-model="searchQuery" placeholder="Cari berdasarkan nama atau email..." 
                                    class="w-full bg-[#0a0e17] border border-white/10 rounded-xl pl-10 pr-4 py-2.5 text-xs text-white focus:border-indigo-500 outline-none transition shadow-inner placeholder-white/20">
                                <svg class="w-3.5 h-3.5 absolute left-3 top-3 text-white/30 group-focus-within:text-indigo-400 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                            
                            <div class="flex gap-2 w-full sm:w-auto relative z-50">
                                <div class="relative flex-1 sm:flex-none" x-data="{ exportOpen: false }">
                                    <button @click="exportOpen = !exportOpen" @click.away="exportOpen = false" class="w-full justify-center flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-white text-[11px] font-bold transition">
                                        Ekspor <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </button>
                                    <div x-show="exportOpen" class="absolute right-0 mt-2 w-48 bg-[#0f141e] border border-white/10 rounded-xl shadow-[0_15px_50px_rgba(0,0,0,0.9)] z-[9999] overflow-hidden" style="display: none;" x-transition>
                                        <div class="px-4 py-2 border-b border-white/5 text-[9px] font-bold text-white/30 uppercase tracking-widest bg-[#0a0e17]">Pilih Format</div>
                                        <a href="{{ route('admin.user.export.csv') }}" class="flex items-center gap-3 px-4 py-3.5 text-[11px] font-bold text-white hover:bg-white/5 transition border-b border-white/5">
                                            <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg> Ekspor CSV
                                        </a>
                                        <a href="{{ route('admin.user.export.pdf') }}" target="_blank" class="flex items-center gap-3 px-4 py-3.5 text-[11px] font-bold text-white hover:bg-white/5 transition">
                                            <svg class="w-4 h-4 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg> Cetak PDF
                                        </a>
                                    </div>
                                </div>
                                <button @click="showImport = true" class="px-3 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-white transition text-xs" title="Impor Data">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                </button>
                                <button @click="showAdd = true" class="flex-1 sm:flex-none flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 border border-emerald-400 text-white text-[11px] font-bold shadow-[0_0_15px_rgba(16,185,129,0.3)] transition hover:-translate-y-0.5">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg> Tambah Baru
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto relative p-0 sm:p-6 pt-0 border-t border-white/5 sm:border-none">
                        <table class="w-full text-sm text-left whitespace-nowrap sm:whitespace-normal border border-white/5 rounded-xl shadow-inner bg-[#0a0e17]/30">
                            <thead class="bg-[#0f141e] text-white/40 text-[10px] uppercase font-bold border-b border-white/5 sticky top-0 z-20">
                                <tr>
                                    <th class="px-6 py-4 border-b border-white/5">Profil Siswa (Aktif)</th> 
                                    <th class="px-6 py-4 border-b border-white/5">Grup Kelas</th>
                                    <th class="px-6 py-4 border-b border-white/5">Institusi</th>
                                    <th class="px-6 py-4 text-right border-b border-white/5">Aksi Panel</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @forelse($users as $user)
                                @if($user->role == 'student')
                                <tr class="hover:bg-white/5 transition group table-row" 
                                    x-show="searchQuery === '' || '{{ strtolower($user->name) }}'.includes(searchQuery.toLowerCase()) || '{{ strtolower($user->email) }}'.includes(searchQuery.toLowerCase())">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center font-bold text-white text-sm shadow-inner border border-white/10 relative group-hover:shadow-[0_0_15px_rgba(99,102,241,0.5)] transition">
                                                {{ substr($user->name, 0, 2) }}
                                                {{-- GREEN DOT INDICATOR --}}
                                                <span class="absolute -bottom-1 -right-1 w-3.5 h-3.5 bg-emerald-500 border-2 border-[#020617] rounded-full shadow-[0_0_5px_#10b981]" title="Akun Aktif"></span>
                                            </div>
                                            <div>
                                                <p class="font-bold text-white text-xs group-hover:text-indigo-300 transition">{{ $user->name }}</p>
                                                <p class="text-[9px] text-white/40 font-mono mt-0.5 tracking-wider">{{ $user->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="bg-[#020617] text-indigo-300 border border-white/10 px-3 py-1.5 rounded-lg text-[10px] font-bold shadow-inner uppercase tracking-wider">
                                            {{ $user->class_group ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-white/50 text-[11px] font-medium">{{ $user->institution ?? 'Belum Ditentukan' }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('admin.student.detail', $user->id) }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-[#020617] hover:bg-indigo-600 border border-white/10 hover:border-indigo-500 text-white text-[10px] font-bold transition shadow-inner hover:shadow-[0_0_15px_rgba(99,102,241,0.5)] group/btn relative z-30">
                                            <svg class="w-3.5 h-3.5 text-indigo-400 group-hover/btn:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            Lihat Insight
                                        </a>
                                    </td>
                                </tr>
                                @endif
                                @empty
                                <tr><td colspan="4" class="text-center py-16 text-white/30 text-xs italic bg-[#0a0e17]/50 rounded-xl m-4 block border border-dashed border-white/10">Belum ada data siswa ditemukan di direktori.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </main>

    {{-- ==================== ALL HERO MODALS ==================== --}}

    {{-- 1. MODAL: DATA SISWA TERDAFTAR --}}
    <div x-show="showStudentModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-[#020617]/95 backdrop-blur-md" @click="showStudentModal = false"></div>
        <div class="relative w-full max-w-2xl bg-[#0f141e] border border-indigo-500/40 rounded-2xl shadow-[0_20px_70px_rgba(99,102,241,0.15)] p-6" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
            <div class="flex justify-between items-center mb-6 border-b border-white/5 pb-4">
                <div>
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        Daftar Siswa Terdaftar
                    </h3>
                    <p class="text-[10px] text-indigo-400 mt-1 font-mono">10 Pendaftaran Terbaru</p>
                </div>
                <button @click="showStudentModal = false" class="text-white/40 hover:text-white transition bg-white/5 hover:bg-red-500/20 rounded-full p-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            <div class="max-h-[60vh] overflow-y-auto custom-scrollbar space-y-2 pr-2">
                @forelse($users->where('role', 'student')->take(10) as $usr)
                <div class="flex items-center gap-4 p-3.5 rounded-xl bg-[#0a0e17]/80 border border-white/5 hover:border-indigo-500/30 transition group">
                    <div class="w-10 h-10 rounded-full bg-indigo-500/10 flex items-center justify-center text-sm font-bold text-indigo-400 border border-indigo-500/30 shrink-0">{{ substr($usr->name, 0, 2) }}</div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-white truncate">{{ $usr->name }}</p>
                        <p class="text-[10px] text-white/50 font-mono mt-0.5">{{ $usr->email }}</p>
                    </div>
                    <div class="text-right shrink-0">
                        <span class="text-[9px] font-bold text-indigo-300 bg-[#020617] px-3 py-1.5 rounded-lg border border-white/10 uppercase tracking-widest">Kelas: {{ $usr->class_group ?? 'N/A' }}</span>
                    </div>
                </div>
                @empty
                <p class="text-[11px] text-white/40 text-center py-10">Belum ada data siswa.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- 2. MODAL: DATA UJIAN KUIS (PASSED ONLY - KARTU BIRU) --}}
    <div x-show="showQuizModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-[#020617]/95 backdrop-blur-md" @click="showQuizModal = false"></div>
        <div class="relative w-full max-w-2xl bg-[#0f141e] border border-cyan-500/40 rounded-2xl shadow-[0_20px_70px_rgba(6,182,212,0.15)] p-6" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
            <div class="flex justify-between items-center mb-6 border-b border-white/5 pb-4">
                <div>
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-cyan-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Log Kuis Lulus
                    </h3>
                    <p class="text-[10px] text-cyan-400 mt-1 font-mono">15 Pengumpulan Valid Terbaru (Skor >= 70)</p>
                </div>
                <button @click="showQuizModal = false" class="text-white/40 hover:text-white transition bg-white/5 hover:bg-red-500/20 rounded-full p-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            <div class="max-h-[60vh] overflow-y-auto custom-scrollbar space-y-3 pr-2">
                @forelse($passedQuizzesDetail as $act)
                <div class="flex items-center gap-4 p-3.5 rounded-xl bg-[#0a0e17]/80 border border-white/5 hover:border-cyan-500/30 transition group">
                    <div class="w-10 h-10 rounded-full bg-cyan-500/10 flex items-center justify-center text-sm font-bold text-cyan-400 border border-cyan-500/30 shrink-0">{{ substr($act->name, 0, 2) }}</div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-white truncate">{{ $act->name }}</p>
                        <p class="text-[11px] text-white/50 mt-0.5">Kuis Bab <span class="font-bold text-white">{{ $act->chapter_id ?? 'Eval' }}</span></p>
                    </div>
                    <div class="text-right shrink-0">
                        <span class="block text-sm font-black text-emerald-400">{{ $act->score }} Pts</span>
                        <span class="text-[9px] text-white/30 hidden sm:inline-block font-mono mt-1">{{ \Carbon\Carbon::parse($act->created_at)->diffForHumans() }}</span>
                    </div>
                </div>
                @empty
                <div class="flex flex-col items-center justify-center py-10">
                    <p class="text-[11px] text-white/40 italic">Belum ada data kuis lulus.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- 3. MODAL: PASS RATE (KARTU UNGU) --}}
    <div x-show="showPassedModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-[#020617]/95 backdrop-blur-md" @click="showPassedModal = false"></div>
        <div class="relative w-full max-w-2xl bg-[#0f141e] border border-violet-500/40 rounded-2xl shadow-[0_20px_70px_rgba(139,92,246,0.15)] p-6" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
            <div class="flex justify-between items-center mb-6 border-b border-white/5 pb-4">
                <div>
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-violet-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Detail Tingkat Kelulusan
                    </h3>
                    <p class="text-[10px] text-violet-400 mt-1 font-mono">15 Kesuksesan Terbaru</p>
                </div>
                <button @click="showPassedModal = false" class="text-white/40 hover:text-white transition bg-white/5 hover:bg-red-500/20 rounded-full p-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            <div class="max-h-[60vh] overflow-y-auto custom-scrollbar space-y-3 pr-2">
                @forelse($passedQuizzesDetail as $act)
                <div class="flex items-center gap-4 p-3.5 rounded-xl bg-[#0a0e17]/80 border border-white/5 hover:border-violet-500/30 transition group">
                    <div class="w-10 h-10 rounded-full bg-violet-500/10 flex items-center justify-center text-sm font-bold text-violet-400 border border-violet-500/30 shrink-0">{{ substr($act->name, 0, 2) }}</div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-white truncate">{{ $act->name }}</p>
                        <p class="text-[11px] text-white/50 mt-0.5">Kuis Bab <span class="font-bold text-white">{{ $act->chapter_id ?? 'Eval' }}</span></p>
                    </div>
                    <div class="text-right shrink-0">
                        <span class="block text-sm font-black text-emerald-400">{{ $act->score }} Pts</span>
                        <span class="text-[9px] text-white/30 hidden sm:inline-block font-mono mt-1">{{ \Carbon\Carbon::parse($act->created_at)->diffForHumans() }}</span>
                    </div>
                </div>
                @empty
                <div class="flex flex-col items-center justify-center py-10">
                    <p class="text-[11px] text-white/40 italic">Belum ada data kuis lulus.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- 4. MODAL: DATA LAB SELESAI --}}
    <div x-show="showLabModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-[#020617]/95 backdrop-blur-md" @click="showLabModal = false"></div>
        <div class="relative w-full max-w-2xl bg-[#0f141e] border border-fuchsia-500/40 rounded-2xl shadow-[0_20px_70px_rgba(217,70,239,0.15)] p-6" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
            <div class="flex justify-between items-center mb-6 border-b border-white/5 pb-4">
                <div>
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-fuchsia-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                        Riwayat Penyelesaian Lab
                    </h3>
                    <p class="text-[10px] text-fuchsia-400 mt-1 font-mono">Status: Lulus | 15 Terbaru</p>
                </div>
                <button @click="showLabModal = false" class="text-white/40 hover:text-white transition bg-white/5 hover:bg-red-500/20 rounded-full p-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            
            <div class="max-h-[60vh] overflow-y-auto custom-scrollbar space-y-3 pr-2">
                @forelse($passedLabsDetail as $lab)
                <div class="flex items-center gap-4 p-3.5 rounded-xl bg-[#0a0e17]/80 border border-white/5 hover:border-fuchsia-500/30 transition group">
                    <div class="w-10 h-10 rounded-full bg-fuchsia-500/10 flex items-center justify-center text-sm font-bold text-fuchsia-400 border border-fuchsia-500/30 shrink-0">
                        {{ substr($lab->student_name, 0, 2) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-white truncate">{{ $lab->student_name }}</p>
                        <p class="text-[10px] text-white/50 mt-0.5">Lab ID: <span class="text-white/80 font-bold">#{{ $lab->lab_id }}</span></p>
                    </div>
                    <div class="flex-1 text-center hidden sm:block">
                        <p class="text-[11px] text-white/60 bg-white/5 px-3 py-1.5 rounded-lg inline-block truncate max-w-full font-medium" title="{{ $lab->lab_title ?? 'Tugas Lab' }}">{{ $lab->lab_title ?? 'Tugas Lab' }}</p>
                    </div>
                    <div class="text-right shrink-0">
                        <span class="block text-[11px] font-black text-emerald-400 mb-1">Skor: {{ $lab->final_score ?? 'N/A' }}</span>
                        <span class="text-[9px] font-mono text-white/40 hidden sm:block">{{ \Carbon\Carbon::parse($lab->created_at)->diffForHumans() }}</span>
                    </div>
                </div>
                @empty
                <div class="flex flex-col items-center justify-center py-10">
                    <p class="text-[11px] text-white/40 italic">Belum ada sesi lab yang diselesaikan.</p>
                </div>
                @endforelse
            </div>
            <div class="mt-6 pt-4 border-t border-white/5 text-center">
                <a href="{{ route('admin.lab.analytics') }}" class="text-[11px] font-bold text-fuchsia-400 hover:text-white transition bg-fuchsia-500/10 px-4 py-2 rounded-lg border border-fuchsia-500/30">Lihat Panel Analitik Lab &rarr;</a>
            </div>
        </div>
    </div>

    {{-- 5. MODAL: DATA RATA-RATA --}}
    <div x-show="showAvgModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-[#020617]/95 backdrop-blur-md" @click="showAvgModal = false"></div>
        <div class="relative w-full max-w-lg bg-[#0f141e] border border-emerald-500/40 rounded-2xl shadow-[0_20px_70px_rgba(16,185,129,0.15)] p-6" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
            <div class="flex justify-between items-center mb-6 border-b border-white/5 pb-4">
                <div>
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Skor Rata-rata per Bab
                    </h3>
                </div>
                <button @click="showAvgModal = false" class="text-white/40 hover:text-white transition bg-white/5 hover:bg-red-500/20 rounded-full p-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            <div class="max-h-[60vh] overflow-y-auto custom-scrollbar space-y-3 pr-2">
                @forelse($chapterAverages as $avg)
                <div class="flex items-center justify-between p-3.5 rounded-xl bg-[#0a0e17]/80 border border-white/5 hover:border-emerald-500/30 transition group">
                    <div>
                        <p class="text-sm font-bold text-white">Kuis Bab {{ $avg->chapter_id }}</p>
                        <p class="text-[10px] text-white/50 mt-0.5">Total Percobaan: <span class="text-white">{{ $avg->total }}</span></p>
                    </div>
                    <div class="text-right">
                        <span class="text-xl font-black {{ $avg->avg_score >= 70 ? 'text-emerald-400' : 'text-red-400' }}">{{ $avg->avg_score }}</span>
                    </div>
                </div>
                @empty
                <p class="text-[11px] text-white/40 text-center py-10">Belum ada data rata-rata.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- 6. MODAL: DATA REMEDIAL --}}
    <div x-show="showRemedialModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-[#020617]/95 backdrop-blur-md" @click="showRemedialModal = false"></div>
        <div class="relative w-full max-w-2xl bg-[#0f141e] border border-red-500/40 rounded-2xl shadow-[0_20px_70px_rgba(239,68,68,0.15)] p-6" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
            <div class="flex justify-between items-center mb-6 border-b border-white/5 pb-4">
                <div>
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        Daftar Peringatan Remedial
                    </h3>
                    <p class="text-[10px] text-red-400 mt-1 font-mono">Disaring secara otomatis (Siswa lulus & duplikat dikecualikan)</p>
                </div>
                <button @click="showRemedialModal = false" class="text-white/40 hover:text-white transition bg-white/5 hover:bg-red-500/20 rounded-full p-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            <div class="max-h-[60vh] overflow-y-auto custom-scrollbar space-y-3 pr-2">
                @forelse($trueRemedialList as $act)
                <div class="flex items-center gap-4 p-3.5 rounded-xl bg-[#0a0e17]/80 border border-red-500/20 hover:border-red-500/40 transition group">
                    <div class="w-10 h-10 rounded-full bg-red-500/20 flex items-center justify-center text-sm font-black text-red-500 border border-red-500/30 shrink-0">!</div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-white truncate">{{ $act->name }}</p>
                        <p class="text-[10px] text-red-400/80 mt-0.5">Kuis Bab: <span class="font-bold text-red-300">{{ $act->chapter_id ?? 'N/A' }}</span></p>
                    </div>
                    <div class="text-right shrink-0">
                        <span class="block text-sm font-black text-red-500">{{ $act->score }} Pts</span>
                        <span class="text-[9px] text-red-300 font-bold bg-red-500/10 px-2 py-0.5 rounded mt-1 hidden sm:inline-block">Kurang {{ 70 - $act->score }} dari KKM</span>
                    </div>
                </div>
                @empty
                <div class="flex flex-col items-center justify-center py-10 text-emerald-400">
                    <span class="text-xs font-bold tracking-widest uppercase text-center">Semua Siswa Lulus!<br>Tidak Ada Tindakan Diperlukan.</span>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ==================== MODALS DATA ENTRY ==================== --}}
    
    {{-- 1. IMPORT CSV --}}
    <div x-show="showImport" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-[#020617]/90 backdrop-blur-md" @click="showImport = false"></div>
        <div class="relative w-full max-w-md bg-[#0f141e] border border-white/10 rounded-2xl shadow-[0_20px_70px_rgba(0,0,0,0.9)] p-6" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
            <h3 class="text-lg font-bold text-white mb-2">Impor Data Siswa</h3>
            <p class="text-[10px] text-white/50 mb-6 border-b border-white/5 pb-4">Header CSV yang Dibutuhkan: <code class="bg-[#0a0e17] px-1.5 py-0.5 rounded text-indigo-300 font-mono font-bold mt-1 inline-block border border-white/5">Name, Email, Class, Institution, Password</code></p>
            <form action="{{ route('admin.user.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="relative w-full h-32 border-2 border-dashed border-white/10 rounded-xl flex flex-col items-center justify-center hover:border-indigo-500/50 bg-[#0a0e17] group cursor-pointer mb-5 shadow-inner transition-colors">
                    <input type="file" name="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" required onchange="document.getElementById('fileName').innerText = this.files[0].name">
                    <svg class="w-8 h-8 text-white/30 group-hover:text-indigo-400 mb-2 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                    <span id="fileName" class="text-[10px] font-bold text-white/50 group-hover:text-white transition">Klik untuk memilih (.csv)</span>
                </div>
                <div class="flex justify-end gap-3 mt-2">
                    <button type="button" @click="showImport = false" class="px-5 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 text-white/60 hover:text-white text-xs font-bold transition border border-transparent hover:border-white/10">Batal</button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition shadow-[0_0_15px_rgba(99,102,241,0.4)] border border-indigo-400">Jalankan Impor</button>
                </div>
            </form>
        </div>
    </div>

    {{-- 2. ADD USER --}}
    <div x-show="showAdd" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-[#020617]/90 backdrop-blur-md" @click="showAdd = false"></div>
        <div class="relative w-full max-w-md bg-[#0f141e] border border-white/10 rounded-2xl shadow-[0_20px_70px_rgba(0,0,0,0.9)] p-6" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
            <h3 class="text-lg font-bold text-white mb-6 border-b border-white/5 pb-3">Daftarkan Siswa Baru</h3>
            <form action="{{ route('admin.user.store') }}" method="POST" class="space-y-4">
                @csrf
                <div><label class="text-[9px] font-bold text-white/50 uppercase mb-1.5 block tracking-widest">Nama Lengkap</label><input type="text" name="name" class="w-full glass-input rounded-xl px-4 py-3 text-sm focus:bg-[#0a0e17]" required></div>
                <div><label class="text-[9px] font-bold text-white/50 uppercase mb-1.5 block tracking-widest">Alamat Email</label><input type="email" name="email" class="w-full glass-input rounded-xl px-4 py-3 text-sm focus:bg-[#0a0e17]" required></div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-2">Grup Kelas</label>
                        <div class="relative">
                            <select name="class_group" class="w-full glass-input rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 ring-indigo-500/20 appearance-none cursor-pointer">
                                <option value="" class="bg-[#0f141e] text-slate-400">-- Pilih Kelas --</option>
                                
                                {{-- Loop data kelas dari database --}}
                                @foreach($availableClasses as $cls)
                                    <option value="{{ $cls->name }}" class="bg-[#0f141e] text-white">
                                        {{ $cls->name }} {{ $cls->major ? ' - '.$cls->major : '' }}
                                    </option>
                                @endforeach

                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-500">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="text-[9px] font-bold text-white/50 uppercase mb-1.5 block tracking-widest">Institusi</label>
                        <input type="text" name="institution" class="w-full glass-input rounded-xl px-4 py-3 text-sm focus:bg-[#0a0e17]">
                    </div>
                </div>
                <div><label class="text-[9px] font-bold text-white/50 uppercase mb-1.5 block tracking-widest">Kata Sandi</label><input type="password" name="password" class="w-full glass-input rounded-xl px-4 py-3 text-sm focus:bg-[#0a0e17]" required></div>
                <div class="flex justify-end gap-3 pt-5 border-t border-white/5 mt-5">
                    <button type="button" @click="showAdd = false" class="px-5 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 text-white/60 hover:text-white text-xs font-bold transition border border-transparent hover:border-white/10">Batal</button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition shadow-[0_0_15px_rgba(99,102,241,0.4)] border border-indigo-400">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>

    {{-- SCRIPTS --}}
    <script>
        let myChart = null;

        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('quizChart');
            if(ctx) {
                const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 300);
                gradient.addColorStop(0, 'rgba(99, 102, 241, 0.5)'); 
                gradient.addColorStop(1, 'rgba(99, 102, 241, 0)');
                
                myChart = new Chart(ctx.getContext('2d'), {
                    type: 'line', 
                    data: {
                        labels: {!! json_encode($chartLabels) !!},
                        datasets: [{
                            label: 'Skor Rata-rata', 
                            data: {!! json_encode($chartScores) !!},
                            borderColor: '#818cf8', backgroundColor: gradient, borderWidth: 3,
                            pointBackgroundColor: '#0f141e', pointBorderColor: '#818cf8', pointBorderWidth: 2, pointRadius: 5, fill: true, tension: 0.4,
                            borderRadius: 4 
                        }]
                    },
                    options: { 
                        responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, 
                        scales: { 
                            x: { grid: { display: false }, ticks: { color: '#94a3b8', font: {size: 10, family: 'JetBrains Mono'} } }, 
                            y: { beginAtZero: true, max: 100, grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#94a3b8', font: {size: 10, family: 'JetBrains Mono'} } } 
                        }, interaction: { mode: 'index', intersect: false }
                    }
                });
            }
        });

        function updateChartType(type) {
            if(myChart) {
                myChart.config.type = type;
                if(type === 'bar') {
                    myChart.data.datasets[0].backgroundColor = '#818cf8';
                } else {
                    const gradient = myChart.ctx.createLinearGradient(0, 0, 0, 300);
                    gradient.addColorStop(0, 'rgba(99, 102, 241, 0.5)'); gradient.addColorStop(1, 'rgba(99, 102, 241, 0)');
                    myChart.data.datasets[0].backgroundColor = gradient;
                }
                myChart.update();
            }
        }

        // SWAL
        @if(session('success')) Swal.fire({ title: 'Berhasil!', text: "{{ session('success') }}", icon: 'success', background: '#0f141e', color: '#fff', confirmButtonColor: '#6366f1', customClass: { popup: 'rounded-2xl border border-white/10 shadow-[0_10px_50px_rgba(0,0,0,0.8)]' } }); @endif
        @if(session('error')) Swal.fire({ title: 'Error!', text: "{{ session('error') }}", icon: 'error', background: '#0f141e', color: '#fff', confirmButtonColor: '#ef4444', customClass: { popup: 'rounded-2xl border border-white/10 shadow-[0_10px_50px_rgba(0,0,0,0.8)]' } }); @endif
    </script>

</body>
</html>