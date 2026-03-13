<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student · {{ $user->name ?? 'Student' }}</title>
    
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

        /* SISTEM TOOLTIP SUPER SOLID (Theme Adaptive) */
        .tooltip-container { position: relative; display: inline-flex; align-items: center; justify-content: center; z-index: 50; }
        .tooltip-container:hover { z-index: 99999; }
        .tooltip-trigger { width: 18px; height: 18px; border-radius: 50%; color: #64748b; display: flex; align-items: center; justify-content: center; cursor: help; transition: all 0.2s; border: 1px solid rgba(0,0,0,0.1); }
        .dark .tooltip-trigger { color: white; border-color: rgba(255,255,255,0.2); }
        .tooltip-trigger:hover { transform: scale(1.15); }
        .tooltip-content { opacity: 0; visibility: hidden; position: absolute; pointer-events: none; width: max-content; min-width: 200px; max-width: 260px; white-space: normal; text-align: left; background-color: #ffffff; color: #1e293b; font-size: 11px; padding: 14px 16px; line-height: 1.5; border-radius: 12px; box-shadow: 0 20px 50px rgba(0,0,0,0.15); z-index: 99999; border: 1px solid #e2e8f0; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .dark .tooltip-content { background-color: #020617; color: #e2e8f0; box-shadow: 0 20px 60px rgba(0,0,0,1); border: none; }
        .tooltip-down .tooltip-content { top: calc(100% + 12px); left: 50%; transform: translateX(-50%) translateY(-10px); }
        .tooltip-down:hover .tooltip-content { transform: translateX(-50%) translateY(0); opacity: 1; visibility: visible; }
        .tooltip-down .tooltip-content::after { content: ''; position: absolute; bottom: 100%; left: 50%; margin-left: -6px; border-width: 6px; border-style: solid; border-color: transparent transparent #ffffff transparent; }
        .dark .tooltip-down .tooltip-content::after { border-color: transparent transparent #020617 transparent; }
        .tooltip-left .tooltip-content { left: auto; right: -12px; transform: translateX(0) translateY(-10px); }
        .tooltip-down.tooltip-left:hover .tooltip-content { transform: translateX(0) translateY(0); }
        .tooltip-left .tooltip-content::after { left: auto; right: 15px; margin-left: 0; }
        
        .dark .tooltip-indigo .tooltip-trigger { background-color: #6366f1; box-shadow: 0 0 10px rgba(99,102,241,0.5); border-color: transparent; } .dark .tooltip-indigo .tooltip-trigger:hover { background-color: #818cf8; box-shadow: 0 0 15px rgba(99,102,241,0.8); } .dark .tooltip-indigo .tooltip-content { border: 1px solid rgba(99,102,241,0.5); }
        .dark .tooltip-emerald .tooltip-trigger { background-color: #10b981; box-shadow: 0 0 10px rgba(16,185,129,0.5); border-color: transparent; } .dark .tooltip-emerald .tooltip-trigger:hover { background-color: #34d399; box-shadow: 0 0 15px rgba(16,185,129,0.8); } .dark .tooltip-emerald .tooltip-content { border: 1px solid rgba(16,185,129,0.5); }
        .dark .tooltip-yellow .tooltip-trigger { background-color: #eab308; color: #020617; box-shadow: 0 0 10px rgba(234,179,8,0.5); border-color: transparent; } .dark .tooltip-yellow .tooltip-trigger:hover { background-color: #facc15; box-shadow: 0 0 15px rgba(234,179,8,0.8); } .dark .tooltip-yellow .tooltip-content { border: 1px solid rgba(234,179,8,0.5); }
        .dark .tooltip-cyan .tooltip-trigger { background-color: #06b6d4; box-shadow: 0 0 10px rgba(6,182,212,0.5); border-color: transparent; } .dark .tooltip-cyan .tooltip-trigger:hover { background-color: #22d3ee; box-shadow: 0 0 15px rgba(6,182,212,0.8); } .dark .tooltip-cyan .tooltip-content { border: 1px solid rgba(6,182,212,0.5); }
        .dark .tooltip-fuchsia .tooltip-trigger { background-color: #d946ef; box-shadow: 0 0 10px rgba(217,70,239,0.5); border-color: transparent; } .dark .tooltip-fuchsia .tooltip-trigger:hover { background-color: #e879f9; box-shadow: 0 0 15px rgba(217,70,239,0.8); } .dark .tooltip-fuchsia .tooltip-content { border: 1px solid rgba(217,70,239,0.5); }
    </style>
</head>
<body class="h-screen w-full flex overflow-hidden selection:bg-indigo-500/30 selection:text-indigo-900 dark:selection:text-white" 
      x-data="{ 
          sidebarOpen: false,
          activeTab: 'overview', 
          showEdit: false, 
          searchLab: '', 
          searchQuiz: '',
          searchLesson: '',
          showProgress: false,
          
          // State Modals Insight Database Absolut
          showLessonModal: false,
          showLabModal: false,
          showQuizModal: false,
          showAvgLabModal: false,
          showAvgQuizModal: false,
          showGlobalProgressModal: false,

          confirmDelete() {
              Swal.fire({ title: 'Hapus Siswa?', text: 'Tindakan ini tidak dapat dibatalkan. Semua data riwayat akan terhapus.', icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#334155', confirmButtonText: 'Ya, Hapus Permanen', cancelButtonText: 'Batal', reverseButtons: true })
              .then((result) => { if (result.isConfirmed) document.getElementById('delete-student-form').submit(); })
          }
      }"
      @keydown.escape.window="showLessonModal = false; showLabModal = false; showQuizModal = false; showAvgLabModal = false; showAvgQuizModal = false; showGlobalProgressModal = false; showEdit = false;"
      x-init="setTimeout(() => showProgress = true, 200); $watch('activeTab', value => { if(value === 'overview') { showProgress = false; setTimeout(() => showProgress = true, 50); } });">

    {{-- HELPER DATA BLADE (Real Database Collections & Logical Duration Formatter) --}}
    @php
        // Fungsi format waktu cerdas dengan fallback
        function formatTime($seconds) {
            if ($seconds === null || $seconds === '') return '-';
            if ($seconds == 0) return '0s';
            
            // Jika lebih dari 12 jam, anggap sesi ditinggalkan/lupa logout
            if ($seconds > 43200) { 
                return '> 12j'; 
            }
            
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
                'topics' => [['name' => '2.1 Flexbox Architecture', 'ids' => range(29, 33)], ['name' => '2.2 Grid System Mastery', 'ids' => range(34, 40)], ['name' => '2.3 Layout Management', 'ids' => range(41, 45)]]
            ],
            [
                'id' => 3, 'number' => '03', 'title' => 'STYLING', 'color' => 'fuchsia', 'lab_id' => 3, 'lab_name' => 'Styling Components', 'quiz_key' => '3',
                'topics' => [['name' => '3.1 Tipografi & Font', 'ids' => range(46, 51)], ['name' => '3.2 Backgrounds', 'ids' => range(52, 55)], ['name' => '3.3 Borders & Rings', 'ids' => range(56, 59)], ['name' => '3.4 Effects & Filters', 'ids' => range(60, 65)]]
            ]
        ];
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

    {{-- MAIN CONTENT --}}
    <main class="flex-1 flex flex-col relative h-full bg-slate-50 dark:bg-[#020617] overflow-hidden transition-colors">
        
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
                            @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="w-full h-full object-cover rounded-[10px]">
                            @else
                                <div class="w-full h-full bg-white dark:bg-[#0f141e] rounded-[11px] flex items-center justify-center font-black text-slate-900 dark:text-white text-base transition-colors">{{ substr($user->name ?? 'S', 0, 2) }}</div>
                            @endif
                            {{-- Lencana Terverifikasi --}}
                            <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-emerald-500 rounded-full border-2 border-white dark:border-[#020617] flex items-center justify-center text-white" title="Akun Siswa Terverifikasi Aktif">
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                        </div>
                        <div>
                            <nav class="flex text-[10px] text-slate-500 dark:text-white/50 mb-1.5 font-bold hidden sm:flex transition-colors">
                                <ol class="inline-flex items-center space-x-1">
                                    <li class="inline-flex items-center"><a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition">Dashboard</a></li>
                                    <li><div class="flex items-center"><svg class="w-3 h-3 text-slate-400 dark:text-white/30 mx-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg><span class="text-slate-900 dark:text-white transition-colors">{{ $user->name ?? 'Detail Siswa' }}</span></div></li>
                                </ol>
                            </nav>
                            <h2 class="text-slate-900 dark:text-white font-bold text-lg md:text-xl tracking-tight flex items-center gap-2 leading-none transition-colors">{{ $user->name ?? 'Student Profile' }}</h2>
                            <div class="flex items-center gap-2 mt-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse shadow-[0_0_8px_#10b981]"></span>
                                <p class="text-[10px] text-slate-500 dark:text-white/40 font-mono transition-colors">{{ $user->email ?? 'No email recorded' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center gap-3 sm:gap-6 mt-1">
                    {{-- Global Progress (Interactive) --}}
                    <div class="hidden xl:flex flex-col items-end mr-2 cursor-pointer hover:opacity-80 transition-opacity" @click="showGlobalProgressModal = true" title="Klik untuk melihat detail kalkulasi progres">
                        <p class="text-[9px] uppercase font-extrabold text-slate-500 dark:text-slate-400 tracking-widest mb-1.5 transition-colors">Global Progress</p>
                        <div class="flex items-center gap-3">
                            <div class="w-24 h-1.5 bg-slate-200 dark:bg-[#0f141e] rounded-full overflow-hidden border border-slate-300 dark:border-white/5 shadow-inner transition-colors">
                                <div class="h-full bg-cyan-500 dark:bg-cyan-400 rounded-full transition-colors" style="width: {{ $globalProgress ?? 0 }}%"></div>
                            </div>
                            <span class="text-xs font-black text-cyan-600 dark:text-cyan-400 transition-colors">{{ $globalProgress ?? 0 }}%</span>
                        </div>
                    </div>

                    {{-- Export Dropdown --}}
                    <div class="relative" x-data="{ exportOpen: false }">
                        <button @click="exportOpen = !exportOpen" @click.away="exportOpen = false" class="p-2.5 sm:px-4 sm:py-2.5 rounded-full sm:rounded-xl bg-slate-200/50 dark:bg-white/5 hover:bg-slate-200 dark:hover:bg-white/10 border border-transparent dark:border-white/10 text-slate-700 dark:text-white text-xs font-bold transition flex items-center gap-2 shadow-sm dark:shadow-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span class="hidden sm:inline">Export</span>
                        </button>
                        <div x-show="exportOpen" class="absolute right-0 mt-2 w-48 bg-white dark:bg-[#0f141e] border border-slate-200 dark:border-white/10 rounded-xl shadow-lg dark:shadow-[0_15px_50px_rgba(0,0,0,0.9)] z-[9999] overflow-hidden transition-colors" style="display: none;" x-transition>
                            <div class="px-4 py-2 border-b border-slate-100 dark:border-white/5 text-[9px] font-bold text-slate-400 dark:text-white/30 uppercase tracking-widest bg-slate-50 dark:bg-[#0a0e17] transition-colors">Pilih Format</div>
                            <a href="{{ route('admin.student.export.csv', $user->id) }}" class="flex items-center gap-3 px-4 py-3.5 text-[11px] font-bold text-slate-700 dark:text-white hover:bg-slate-50 dark:hover:bg-white/5 transition border-b border-slate-100 dark:border-white/5"><svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg> Export CSV</a>
                            <a href="{{ route('admin.student.export.pdf', $user->id) }}" target="_blank" class="flex items-center gap-3 px-4 py-3.5 text-[11px] font-bold text-slate-700 dark:text-white hover:bg-slate-50 dark:hover:bg-white/5 transition"><svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg> Print PDF</a>
                        </div>
                    </div>

                    <button @click="showEdit = true" class="p-2.5 rounded-full bg-indigo-50 dark:bg-indigo-500/10 hover:bg-indigo-100 dark:hover:bg-indigo-500 text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-white border border-indigo-200 dark:border-indigo-500/20 hover:border-indigo-300 dark:hover:border-indigo-500 transition-all shadow-sm dark:shadow-lg active:scale-95" title="Edit Student"><svg class="w-4 h-4 transition-transform hover:rotate-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg></button>
                </div>
            </div>

            {{-- Tabs --}}
            <div class="flex gap-6 md:gap-8 mt-2 overflow-x-auto custom-scrollbar w-full relative z-10">
                <button @click="activeTab = 'overview'" :class="activeTab === 'overview' ? 'active text-slate-900 dark:text-white' : 'text-slate-500'" class="tab-btn pb-3.5 text-[11px] font-bold uppercase tracking-wider flex items-center gap-2 whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" /></svg> Overview
                </button>
                <button @click="activeTab = 'curriculum'" :class="activeTab === 'curriculum' ? 'active text-slate-900 dark:text-white' : 'text-slate-500'" class="tab-btn pb-3.5 text-[11px] font-bold uppercase tracking-wider flex items-center gap-2 whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg> Curriculum Tracker
                </button>
                <button @click="activeTab = 'history'" :class="activeTab === 'history' ? 'active text-slate-900 dark:text-white' : 'text-slate-500'" class="tab-btn pb-3.5 text-[11px] font-bold uppercase tracking-wider flex items-center gap-2 whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> History Log
                </button>
            </div>
        </header>

        {{-- CONTENT BODY --}}
        <div class="flex-1 overflow-y-auto custom-scrollbar p-6 md:p-8 z-10 scroll-smooth">
            <div class="max-w-7xl mx-auto pb-20 relative">

                {{-- =========================================================
                     TAB 1: OVERVIEW 
                     ========================================================= --}}
                <div x-show="activeTab === 'overview'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-12" style="display: none;" x-cloak>
                    
                    {{-- =========================================================
                         PROFIL & STATUS KELAS
                         ========================================================= --}}
                    <div class="animate-fade-in-up mb-8">
                        <div class="glass-card rounded-2xl p-6 md:p-8 flex flex-col md:flex-row items-start md:items-center justify-between gap-6 shadow-sm dark:shadow-lg border border-slate-200 dark:border-white/5 transition-colors">
                            
                            {{-- Student Basic Info --}}
                            <div class="flex items-center gap-5 w-full md:w-auto">
                                <div class="w-16 h-16 rounded-2xl bg-indigo-50 dark:bg-indigo-500/20 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-2xl font-black border border-indigo-200 dark:border-indigo-500/30 shrink-0 transition-colors shadow-inner overflow-hidden relative">
                                    @if($user->avatar)
                                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                                    @else
                                        {{ substr($user->name, 0, 2) }}
                                    @endif
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight transition-colors flex items-center gap-2">
                                        {{ $user->name }}
                                    </h3>
                                    <p class="text-sm text-slate-500 dark:text-slate-400 font-mono mt-0.5 transition-colors">{{ $user->email }}</p>
                                    <div class="flex flex-wrap items-center gap-2 mt-2">
                                        @if($user->institution)
                                            <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-white/10 uppercase tracking-widest">{{ $user->institution }}</span>
                                        @endif
                                        @if($user->study_program)
                                            <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-white/10 uppercase tracking-widest">{{ $user->study_program }}</span>
                                        @endif
                                        <span class="text-[10px] text-slate-400 dark:text-slate-500 flex items-center gap-1" title="Tanggal Mendaftar: {{ \Carbon\Carbon::parse($user->created_at)->translatedFormat('d F Y, H:i') }}">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg> 
                                            Joined {{ \Carbon\Carbon::parse($user->created_at)->translatedFormat('M Y') }}
                                        </span>
                                        <span class="text-[10px] text-slate-400 dark:text-slate-500 flex items-center gap-1" title="Terakhir Update: {{ \Carbon\Carbon::parse($user->updated_at)->translatedFormat('d F Y, H:i') }}">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                            Upd. {{ \Carbon\Carbon::parse($user->updated_at)->diffForHumans() }}
                                        </span>
                                    </div>
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
                                                    <span class="{{ $classGroup->is_active ? 'text-emerald-600 dark:text-emerald-500' : 'text-red-600 dark:text-red-500' }} font-black">{{ $classGroup->is_active ? 'Active' : 'Closed' }}</span>
                                                @endif
                                                <div class="p-1 rounded-md bg-emerald-200/50 dark:bg-emerald-500/30 text-emerald-700 dark:text-emerald-300 opacity-0 group-hover:opacity-100 transition-opacity">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                                </div>
                                            </div>
                                        </div>
                                        @if(isset($classGroup))
                                            <div class="text-[10px] font-mono text-slate-500 dark:text-slate-400 bg-slate-100 dark:bg-[#020617] border border-slate-200 dark:border-white/5 px-4 py-2.5 rounded-lg shadow-inner flex justify-between items-center w-full transition-colors">
                                                <span>Token Pendaftaran: <span class="font-bold text-slate-900 dark:text-white tracking-widest transition-colors">{{ $classGroup->token }}</span></span>
                                            </div>
                                        @endif
                                    </div>
                                @endempty
                            </div>
                        </div>
                    </div>

                    {{-- VISUAL SEPARATOR --}}
                    <div class="flex items-center gap-4 py-4">
                        <div class="h-px bg-slate-200 dark:bg-white/10 flex-1 transition-colors"></div>
                        <span class="text-[10px] font-black text-slate-500 dark:text-slate-600 uppercase tracking-[0.2em] bg-white dark:bg-[#020617] px-3 py-1 border border-slate-200 dark:border-white/5 rounded-full transition-colors">Academic Analytics</span>
                        <div class="h-px bg-slate-200 dark:bg-white/10 flex-1 transition-colors"></div>
                    </div>

                    {{-- =========================================================
                         ZONA AKADEMIK
                         ========================================================= --}}
                    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-5 gap-4">
                        
                        {{-- 1. Materi Dibaca --}}
                        <div class="glass-card p-5 rounded-2xl relative group hover:border-cyan-400 dark:hover:border-cyan-500/40 cursor-pointer transition-colors" @click="showLessonModal = true">
                            <div class="flex justify-between items-start mb-3">
                                <p class="text-[10px] text-slate-500 dark:text-slate-400 uppercase font-bold tracking-widest group-hover:text-cyan-600 dark:group-hover:text-cyan-400 transition-colors">Materi Dibaca</p>
                                <div class="flex items-center gap-2">
                                    <div class="tooltip-container tooltip-cyan tooltip-down tooltip-left">
                                        <div class="tooltip-trigger bg-transparent border-transparent shadow-none text-slate-400 dark:text-white/30 group-hover:text-cyan-600 dark:group-hover:text-cyan-400 transition-colors">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        </div>
                                        <div class="tooltip-content border-cyan-glow">
                                            <span class="block font-bold text-cyan-600 dark:text-cyan-400 mb-1 border-b border-slate-200 dark:border-white/10 pb-1">Total Materi</span>
                                            Jumlah slide materi teori yang telah dibaca dan diselesaikan oleh siswa ini. Klik untuk melihat rincian.
                                        </div>
                                    </div>
                                    <span class="p-1.5 rounded-lg bg-cyan-50 dark:bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 border border-cyan-200 dark:border-cyan-500/10 shadow-sm dark:shadow-inner group-hover:scale-110 transition duration-300">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-end gap-2">
                                <p class="text-3xl font-black text-slate-900 dark:text-white group-hover:text-cyan-600 dark:group-hover:text-cyan-400 transition-colors" x-data="{ count: 0 }" x-init="let target = {{ $lessonsCompleted ?? count($completedLessonIds ?? []) }}; let i = setInterval(() => { if(count < target) count++; else clearInterval(i); }, 30);" x-text="count"></p>
                                <p class="text-xs font-bold text-slate-400 dark:text-slate-500 mb-1 font-mono transition-colors">/ {{ $totalLessons ?? 65 }}</p>
                            </div>
                            <div class="w-full h-1.5 bg-slate-200 dark:bg-[#0f141e] rounded-full mt-4 overflow-hidden border border-slate-300 dark:border-white/5 transition-colors">
                                <div class="h-full bg-cyan-500 rounded-full shadow-[0_0_10px_currentColor] transition-all duration-[1500ms] ease-out" :style="showProgress ? 'width: {{ ($totalLessons ?? 65) > 0 ? (($lessonsCompleted ?? count($completedLessonIds ?? [])) / ($totalLessons ?? 65)) * 100 : 0 }}%' : 'width: 0%'"></div>
                            </div>
                        </div>

                        {{-- 2. Labs Passed --}}
                        <div class="glass-card p-5 rounded-2xl relative group hover:border-indigo-400 dark:hover:border-indigo-500/40 cursor-pointer transition-colors" @click="showLabModal = true">
                            <div class="flex justify-between items-start mb-3">
                                <p class="text-[10px] text-slate-500 dark:text-slate-400 uppercase font-bold tracking-widest group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">Labs Lulus</p>
                                <div class="flex items-center gap-2">
                                    <div class="tooltip-container tooltip-indigo tooltip-down tooltip-left">
                                        <div class="tooltip-trigger bg-transparent border-transparent shadow-none text-slate-400 dark:text-white/30 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        </div>
                                        <div class="tooltip-content border-indigo-glow">
                                            <span class="block font-bold text-indigo-600 dark:text-indigo-400 mb-1 border-b border-slate-200 dark:border-white/10 pb-1">Lab Praktikum</span>
                                            Jumlah modul praktikum yang telah dikerjakan dan berhasil divalidasi Lulus. Klik untuk melihat daftar riwayat.
                                        </div>
                                    </div>
                                    <span class="p-1.5 rounded-lg bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-500/10 shadow-sm dark:shadow-inner group-hover:scale-110 transition duration-300">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-end gap-2">
                                <p class="text-3xl font-black text-slate-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors drop-shadow-sm dark:drop-shadow-[0_0_8px_rgba(99,102,241,0.3)]">{{ $labsCompleted ?? ($labStats['total'] ?? 0) }}</p>
                                <p class="text-xs font-bold text-slate-400 dark:text-slate-500 mb-1 font-mono transition-colors">/ {{ $totalLabs ?? 4 }}</p>
                            </div>
                            <div class="w-full h-1.5 bg-slate-200 dark:bg-[#0f141e] rounded-full mt-4 overflow-hidden border border-slate-300 dark:border-white/5 transition-colors">
                                <div class="h-full bg-indigo-500 rounded-full shadow-[0_0_10px_currentColor] transition-all duration-[1500ms] ease-out" :style="showProgress ? 'width: {{ ($totalLabs ?? 4) > 0 ? (($labsCompleted ?? ($labStats['total'] ?? 0)) / ($totalLabs ?? 4)) * 100 : 0 }}%' : 'width: 0%'"></div>
                            </div>
                        </div>

                        {{-- 3. Quiz Lulus --}}
                        <div class="glass-card p-5 rounded-2xl relative group hover:border-fuchsia-400 dark:hover:border-fuchsia-500/40 cursor-pointer transition-colors" @click="showQuizModal = true">
                            <div class="flex justify-between items-start mb-3">
                                <p class="text-[10px] text-slate-500 dark:text-slate-400 uppercase font-bold tracking-widest group-hover:text-fuchsia-600 dark:group-hover:text-fuchsia-400 transition-colors">Quiz Lulus</p>
                                <div class="flex items-center gap-2">
                                    <div class="tooltip-container tooltip-fuchsia tooltip-down tooltip-left">
                                        <div class="tooltip-trigger bg-transparent border-transparent shadow-none text-slate-400 dark:text-white/30 group-hover:text-fuchsia-600 dark:group-hover:text-fuchsia-400 transition-colors">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        </div>
                                        <div class="tooltip-content border-fuchsia-glow">
                                            <span class="block font-bold text-fuchsia-600 dark:text-fuchsia-400 mb-1 border-b border-slate-200 dark:border-white/10 pb-1">Evaluasi Kuis</span>
                                            Jumlah evaluasi teori yang diselesaikan dengan skor minimal 70. Klik untuk melihat detail.
                                        </div>
                                    </div>
                                    <span class="p-1.5 rounded-lg bg-fuchsia-50 dark:bg-fuchsia-500/10 text-fuchsia-600 dark:text-fuchsia-400 border border-fuchsia-200 dark:border-fuchsia-500/10 shadow-sm dark:shadow-inner group-hover:scale-110 transition duration-300">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-end gap-2">
                                <p class="text-3xl font-black text-slate-900 dark:text-white group-hover:text-fuchsia-600 dark:group-hover:text-fuchsia-400 transition-colors drop-shadow-sm dark:drop-shadow-[0_0_8px_rgba(217,70,239,0.3)]">{{ count(array_filter($quizScoresMap ?? [], fn($s) => $s >= 70)) }}</p>
                                <p class="text-xs font-bold text-slate-400 dark:text-slate-500 mb-1 font-mono transition-colors">/ {{ $totalQuizzes ?? 4 }}</p>
                            </div>
                            <div class="w-full h-1.5 bg-slate-200 dark:bg-[#0f141e] rounded-full mt-4 overflow-hidden border border-slate-300 dark:border-white/5 transition-colors">
                                <div class="h-full bg-fuchsia-500 rounded-full shadow-[0_0_10px_currentColor] transition-all duration-[1500ms] ease-out" :style="showProgress ? 'width: {{ ($totalQuizzes ?? 4) > 0 ? (count(array_filter($quizScoresMap ?? [], fn($s) => $s >= 70)) / ($totalQuizzes ?? 4)) * 100 : 0 }}%' : 'width: 0%'"></div>
                            </div>
                        </div>

                        {{-- 4. Avg Lab Score --}}
                        <div class="glass-card p-5 rounded-2xl relative group hover:border-emerald-400 dark:hover:border-emerald-500/40 cursor-pointer transition-colors" @click="showAvgLabModal = true">
                            <div class="flex justify-between items-start mb-3">
                                <p class="text-[10px] text-slate-500 dark:text-slate-400 uppercase font-bold tracking-widest group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">Rata Rata nilai Labs</p>
                                <div class="flex items-center gap-2">
                                    <div class="tooltip-container tooltip-emerald tooltip-down tooltip-left">
                                        <div class="tooltip-trigger bg-transparent border-transparent shadow-none text-slate-400 dark:text-white/30 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        </div>
                                        <div class="tooltip-content border-emerald-glow">
                                            <span class="block font-bold text-emerald-600 dark:text-emerald-400 mb-1 border-b border-slate-200 dark:border-white/10 pb-1">Average Lab</span>
                                            Nilai rata-rata dari seluruh modul praktikum lab yang dikerjakan. Klik untuk melihat.
                                        </div>
                                    </div>
                                    <span class="p-1.5 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/10 shadow-sm dark:shadow-inner group-hover:scale-110 transition duration-300">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" /></svg>
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-end gap-2">
                                <p class="text-3xl font-black text-slate-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors drop-shadow-sm dark:drop-shadow-[0_0_8px_rgba(16,185,129,0.3)]">{{ number_format($labAverage ?? ($labStats['avg_score'] ?? 0), 1) }}</p>
                                <p class="text-xs font-bold text-slate-400 dark:text-slate-500 mb-1 font-mono transition-colors">/ 100</p>
                            </div>
                            <div class="w-full h-1.5 bg-slate-200 dark:bg-[#0f141e] rounded-full mt-4 overflow-hidden border border-slate-300 dark:border-white/5 transition-colors">
                                <div class="h-full bg-emerald-500 rounded-full shadow-[0_0_10px_currentColor] transition-all duration-[1500ms] ease-out" :style="showProgress ? 'width: {{ $labAverage ?? ($labStats['avg_score'] ?? 0) }}%' : 'width: 0%'"></div>
                            </div>
                        </div>

                        {{-- 5. Avg Quiz Score --}}
                        <div class="glass-card p-5 rounded-2xl relative group hover:border-amber-400 dark:hover:border-amber-500/40 cursor-pointer transition-colors" @click="showAvgQuizModal = true">
                            <div class="flex justify-between items-start mb-3">
                                <p class="text-[10px] text-slate-500 dark:text-slate-400 uppercase font-bold tracking-widest group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors">Rata Rata nilai Kuis</p>
                                <div class="flex items-center gap-2">
                                    <div class="tooltip-container tooltip-yellow tooltip-down tooltip-left">
                                        <div class="tooltip-trigger bg-transparent border-transparent shadow-none text-slate-400 dark:text-white/30 group-hover:text-yellow-600 dark:group-hover:text-yellow-400 transition-colors">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        </div>
                                        <div class="tooltip-content border-yellow-glow">
                                            <span class="block font-bold text-yellow-600 dark:text-yellow-400 mb-1 border-b border-slate-200 dark:border-white/10 pb-1">Average Quiz</span>
                                            Nilai rata-rata dari seluruh evaluasi kuis teori yang dikerjakan. Klik untuk melihat.
                                        </div>
                                    </div>
                                    <span class="p-1.5 rounded-lg bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-500/10 shadow-sm dark:shadow-inner group-hover:scale-110 transition duration-300">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-end gap-2">
                                <p class="text-3xl font-black text-slate-900 dark:text-white group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors drop-shadow-sm dark:drop-shadow-[0_0_8px_rgba(245,158,11,0.3)]">{{ number_format($quizAverage ?? ($quizStats['avg_score'] ?? 0), 1) }}</p>
                                <p class="text-xs font-bold text-slate-400 dark:text-slate-500 mb-1 font-mono transition-colors">/ 100</p>
                            </div>
                            <div class="w-full h-1.5 bg-slate-200 dark:bg-[#0f141e] rounded-full mt-4 overflow-hidden border border-slate-300 dark:border-white/5 transition-colors">
                                <div class="h-full bg-amber-500 rounded-full shadow-[0_0_10px_currentColor] transition-all duration-[1500ms] ease-out" :style="showProgress ? 'width: {{ $quizAverage ?? ($quizStats['avg_score'] ?? 0) }}%' : 'width: 0%'"></div>
                            </div>
                        </div>
                    </div>

                    {{-- DETAIL CHART & SUMMARY --}}
                    <div class="grid lg:grid-cols-3 gap-6">
                        <div class="glass-card rounded-2xl p-6 flex flex-col transition-colors">
                            <h3 class="text-sm font-black text-slate-900 dark:text-white mb-5 tracking-wide transition-colors">Status Kelulusan</h3>
                            <div class="flex-1 flex flex-col justify-center space-y-4">
                                <div class="bg-slate-50 dark:bg-white/[0.03] border border-slate-200 dark:border-white/5 rounded-xl p-4 flex items-center justify-between hover:border-indigo-300 dark:hover:border-white/10 transition group/status cursor-default">
                                    <div class="flex items-center gap-4 w-full">
                                        <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-500/20 border border-indigo-200 dark:border-indigo-500/20 flex items-center justify-center text-indigo-600 dark:text-indigo-400 text-lg shadow-sm dark:shadow-inner group-hover/status:scale-110 transition-colors shrink-0">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                                        </div>
                                        <div class="flex-1 pr-4">
                                            <p class="text-xs font-bold text-slate-900 dark:text-white transition-colors">Praktik (Labs)</p>
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
                                            <p class="text-xs font-bold text-slate-900 dark:text-white transition-colors">Teori (Quizzes)</p>
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
                                            <p class="text-xs font-bold text-slate-900 dark:text-white transition-colors">Tingkat Akhir</p>
                                            <div class="flex items-center justify-between w-full mt-2">
                                                <div class="w-full bg-cyan-100 dark:bg-[#020617] h-1 rounded-full mr-3 shadow-inner">
                                                    <div class="bg-cyan-500 h-1 rounded-full shadow-[0_0_5px_currentColor] transition-all duration-1000" style="width: {{ $globalProgress ?? 0 }}%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="text-lg font-black text-cyan-600 dark:text-cyan-400 drop-shadow-sm dark:drop-shadow-[0_0_5px_rgba(34,211,238,0.5)] transition-colors shrink-0">{{ $globalProgress ?? 0 }}%</span>
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
                                        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 transition-colors">Belum Ada Data Grafik</p>
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
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
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
                {{-- TAB 3: HISTORY LOG --}}
                {{-- ============================== --}}
                <div x-show="activeTab === 'history'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-8" style="display: none;" x-cloak>
                    
                    {{-- 1. Lab History --}}
                    <div class="glass-card rounded-2xl relative z-10 flex flex-col transition-colors" style="overflow: visible !important;">
                        {{-- Header Table --}}
                        <div class="p-6 border-b border-slate-200 dark:border-white/5 bg-slate-50/50 dark:bg-[#020617]/40 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 rounded-t-2xl transition-colors">
                            <div>
                                <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2 transition-colors">
                                    <span class="text-indigo-600 dark:text-indigo-400">
                                        <svg class="w-5 h-5 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                                    </span> Riwayat Praktik Lab
                                </h3>
                                <p class="text-xs text-slate-500 dark:text-white/40 mt-1 transition-colors">Log aktivitas penyelesaian modul praktikum yang dikerjakan siswa.</p>
                            </div>
                            
                            <div class="flex flex-wrap items-center gap-3 w-full md:w-auto relative z-40">
                                <div class="relative w-full sm:w-64 group">
                                    <input type="text" x-model="searchLab" placeholder="Cari lab atau status..." 
                                        class="w-full bg-white dark:bg-[#0a0e17] border border-slate-200 dark:border-white/10 rounded-xl pl-10 pr-4 py-2.5 text-xs text-slate-900 dark:text-white focus:border-indigo-500 outline-none transition-colors shadow-sm dark:shadow-inner placeholder-slate-400 dark:placeholder-white/20">
                                    <svg class="w-3.5 h-3.5 absolute left-3 top-3 text-slate-400 dark:text-white/30 group-focus-within:text-indigo-600 dark:group-focus-within:text-indigo-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </div>
                            </div>
                        </div>

                        {{-- Data Table --}}
                        <div class="overflow-x-auto relative p-0 sm:p-6 pt-0 border-t border-slate-200 dark:border-white/5 sm:border-none transition-colors">
                            <table class="w-full text-sm text-left whitespace-nowrap sm:whitespace-normal border border-slate-200 dark:border-white/5 rounded-xl shadow-inner bg-slate-50/50 dark:bg-[#0a0e17]/30 transition-colors duration-500">
                                <thead class="bg-slate-100 dark:bg-[#0f141e] text-slate-500 dark:text-white/40 text-[10px] uppercase font-bold border-b border-slate-200 dark:border-white/5 sticky top-0 z-20 transition-colors">
                                    <tr>
                                        <th class="px-6 py-4 border-b border-slate-200 dark:border-white/5 w-10 text-center">#</th>
                                        <th class="px-6 py-4 border-b border-slate-200 dark:border-white/5">Judul Modul</th>
                                        <th class="px-6 py-4 text-center border-b border-slate-200 dark:border-white/5">Status</th>
                                        <th class="px-6 py-4 text-center border-b border-slate-200 dark:border-white/5" title="Skor dalam rentang 0-100">Skor</th>
                                        <th class="px-6 py-4 text-right border-b border-slate-200 dark:border-white/5">Waktu Pengumpulan</th>
                                        <th class="px-6 py-4 text-right border-b border-slate-200 dark:border-white/5">Aksi Panel</th>
                                    </tr>
                                </thead>
                                @forelse($labHistories as $idx => $h)
                                <tbody x-data="{ expanded: false }" class="divide-y divide-slate-200 dark:divide-white/5 transition-colors" x-show="searchLab === '' || '{{ addslashes(strtolower($h->lab->title ?? 'Lab #'.$h->lab_id)) }}'.includes(searchLab.toLowerCase()) || '{{ strtolower($h->status) }}'.includes(searchLab.toLowerCase())">
                                    <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors group table-row">
                                        <td class="px-6 py-4 text-center text-slate-400 font-mono text-xs">{{ $idx + 1 }}</td>
                                        <td class="px-6 py-4">
                                            <span class="block text-slate-900 dark:text-white font-semibold transition group-hover:text-indigo-600 dark:group-hover:text-indigo-300">{{ $h->lab->title ?? 'Lab #'.$h->lab_id }}</span>
                                        </td>
                                        
                                        @php
                                            $labStatusStr = strtolower($h->status ?? '');
                                            // Deteksi Waktu Habis: Status spesifik, is_timeout true, atau durasi melebihi batas/tidak masuk akal (>12 jam)
                                            $limitLabSec = isset($h->lab->time_limit) ? $h->lab->time_limit * 60 : (isset($h->lab->duration) ? $h->lab->duration * 60 : 0);
                                            $isLabTimeout = $labStatusStr === 'timeout' || $labStatusStr === 'waktu habis' || (isset($h->is_timeout) && $h->is_timeout == 1) || ($limitLabSec > 0 && $h->duration_seconds >= $limitLabSec) || $h->duration_seconds > 43200;
                                            $isLabPassed = $labStatusStr === 'passed' || $labStatusStr === 'lulus';
                                            
                                            if ($isLabPassed) {
                                                $lStatClass = 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20';
                                            } elseif ($isLabTimeout) {
                                                $lStatClass = 'bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-500/20';
                                            } else {
                                                $lStatClass = 'bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-500/20';
                                            }
                                        @endphp

                                        <td class="px-6 py-4 text-center">
                                            <span class="px-2.5 py-1 rounded text-[10px] font-black uppercase tracking-wider transition-colors {{ $lStatClass }}">
                                                {{ $isLabTimeout ? 'TIMEOUT' : $h->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="text-slate-900 dark:text-white font-black transition-colors" title="Skor Evaluasi Otomatis">{{ $h->final_score }}</span>
                                        </td>
                                        
                                        <td class="px-6 py-4 text-right text-slate-500 dark:text-slate-400 text-xs font-mono transition-colors">
                                            <span class="block text-[11px] text-slate-700 dark:text-slate-300" title="{{ \Carbon\Carbon::parse($h->created_at)->diffForHumans() }}">
                                                {{ \Carbon\Carbon::parse($h->created_at)->format('d M Y, H:i') }} WIB
                                            </span>
                                            <div class="flex items-center justify-end gap-1.5 mt-0.5">
                                                <span class="text-[9px] opacity-70" title="Total durasi yang dihabiskan siswa">Durasi: {{ formatTime($h->duration_seconds) }}</span>
                                                @if($isLabTimeout)
                                                    <span class="px-1 py-[1px] rounded-[4px] bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-400 text-[8px] font-bold tracking-wider uppercase border border-amber-200 dark:border-amber-500/30" title="Sesi ditinggalkan atau melebihi batas waktu">Sesi Habis</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            @if($h->last_code_snapshot != null && $h->last_code_snapshot != '')
                                                <button @click="expanded = !expanded" title="Lihat Snapshot Kode" class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-white dark:bg-[#020617] hover:bg-indigo-600 border border-slate-200 dark:border-white/10 hover:border-indigo-500 text-slate-700 hover:text-white dark:text-white text-[10px] font-bold transition-all shadow-sm dark:shadow-inner hover:shadow-[0_0_15px_rgba(99,102,241,0.5)] group/btn relative z-30 gap-1.5">
                                                    <svg x-show="!expanded" class="w-3.5 h-3.5 text-indigo-500 dark:text-indigo-400 group-hover/btn:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                                                    <svg x-show="expanded" style="display:none;" class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                                    <span x-text="expanded ? 'Tutup Kode' : 'Lihat Kode'"></span>
                                                </button>
                                            @else
                                                <span class="text-[10px] text-slate-400 dark:text-slate-500 italic px-3 py-1.5 bg-slate-100 dark:bg-slate-800/30 rounded-lg transition-colors border border-slate-200 dark:border-transparent">No Snapshot</span>
                                            @endif
                                        </td>
                                    </tr>
                                    
                                    {{-- Expanded Code Snippet View --}}
                                    @if($h->last_code_snapshot != null && $h->last_code_snapshot != '')
                                    <tr x-show="expanded" x-cloak class="bg-slate-50 dark:bg-[#05080f] shadow-inner transition-colors">
                                        <td colspan="6" class="p-0 border-b border-slate-200 dark:border-white/5">
                                            <div x-show="expanded" x-collapse>
                                                <div class="p-6 md:p-8 bg-slate-100/50 dark:bg-gradient-to-b dark:from-[#0a0d14] dark:to-transparent transition-colors border-t border-slate-200 dark:border-white/5 relative">
                                                    <div class="flex justify-between items-center mb-3 ml-1">
                                                        <p class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest transition-colors">Hasil Kode Terakhir</p>
                                                    </div>
                                                    <div class="rounded-xl overflow-hidden border border-slate-300/80 dark:border-slate-800 shadow-xl bg-[#0d1117] transition-colors relative" x-data="{ copied: false }">
                                                        {{-- Copy Button Overlay --}}
                                                        <button @click="navigator.clipboard.writeText(`{{ addslashes($h->last_code_snapshot) }}`); copied = true; setTimeout(() => copied = false, 2000)" 
                                                                class="absolute top-2 right-2 p-1.5 rounded-lg bg-white/10 hover:bg-white/20 text-white/50 hover:text-white transition-all z-10 flex items-center justify-center" title="Salin Kode ke Clipboard">
                                                            <svg x-show="!copied" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                                            <svg x-show="copied" style="display:none;" class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                        </button>
                                                        
                                                        <div class="bg-[#1e2330] px-4 py-2.5 border-b border-white/5 flex gap-1.5 items-center transition-colors">
                                                            <div class="w-2.5 h-2.5 rounded-full bg-red-500/80"></div>
                                                            <div class="w-2.5 h-2.5 rounded-full bg-amber-500/80"></div>
                                                            <div class="w-2.5 h-2.5 rounded-full bg-emerald-500/80"></div>
                                                            <span class="ml-3 text-[10px] text-slate-400 font-mono transition-colors">index.html</span>
                                                        </div>
                                                        <div class="p-5 max-h-[300px] overflow-y-auto custom-scrollbar">
                                                            <pre class="text-cyan-50 text-xs font-mono leading-relaxed"><code>{{ $h->last_code_snapshot }}</code></pre>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                                @empty
                                <tbody>
                                    <tr>
                                        <td colspan="6" class="text-center py-16 text-slate-500 dark:text-white/30 text-xs italic bg-slate-50 dark:bg-[#0a0e17]/50 rounded-xl m-4 block border border-dashed border-slate-300 dark:border-white/10 transition-colors">
                                            <svg class="w-8 h-8 mx-auto mb-3 text-slate-300 dark:text-white/10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            Belum ada aktivitas penyelesaian lab.
                                        </td>
                                    </tr>
                                </tbody>
                                @endforelse
                            </table>
                            @if(count($labHistories) > 0)
                                <div class="p-3 border-t border-slate-200 dark:border-white/5 bg-slate-50/30 dark:bg-[#020617]/20 text-center transition-colors">
                                    <span class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest transition-colors">Menampilkan {{ count($labHistories) }} Riwayat Terakhir</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- 2. Quiz History --}}
                    <div class="glass-card rounded-2xl relative z-10 flex flex-col transition-colors mt-8" style="overflow: visible !important;">
                        {{-- Header Table --}}
                        <div class="p-6 border-b border-slate-200 dark:border-white/5 bg-slate-50/50 dark:bg-[#020617]/40 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 rounded-t-2xl transition-colors">
                            <div>
                                <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2 transition-colors">
                                    <span class="text-fuchsia-600 dark:text-fuchsia-400">
                                        <svg class="w-5 h-5 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                                    </span> Riwayat Evaluasi Kuis
                                </h3>
                                <p class="text-xs text-slate-500 dark:text-white/40 mt-1 transition-colors">Log aktivitas pengambilan kuis dan evaluasi teori.</p>
                            </div>
                            <div class="flex flex-wrap items-center gap-3 w-full md:w-auto relative z-40">
                                <div class="relative w-full sm:w-64 group">
                                    <input type="text" x-model="searchQuiz" placeholder="Cari evaluasi..." 
                                        class="w-full bg-white dark:bg-[#0a0e17] border border-slate-200 dark:border-white/10 rounded-xl pl-10 pr-4 py-2.5 text-xs text-slate-900 dark:text-white focus:border-fuchsia-500 outline-none transition-colors shadow-sm dark:shadow-inner placeholder-slate-400 dark:placeholder-white/20">
                                    <svg class="w-3.5 h-3.5 absolute left-3 top-3 text-slate-400 dark:text-white/30 group-focus-within:text-fuchsia-600 dark:group-focus-within:text-fuchsia-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Data Table --}}
                        <div class="overflow-x-auto relative p-0 sm:p-6 pt-0 border-t border-slate-200 dark:border-white/5 sm:border-none transition-colors">
                            <table class="w-full text-sm text-left whitespace-nowrap sm:whitespace-normal border border-slate-200 dark:border-white/5 rounded-xl shadow-inner bg-slate-50/50 dark:bg-[#0a0e17]/30 transition-colors duration-500">
                                <thead class="bg-slate-100 dark:bg-[#0f141e] text-slate-500 dark:text-white/40 text-[10px] uppercase font-bold border-b border-slate-200 dark:border-white/5 sticky top-0 z-20 transition-colors">
                                    <tr>
                                        <th class="px-6 py-4 border-b border-slate-200 dark:border-white/5 w-10 text-center" title="Diurutkan dari yang terbaru">#</th>
                                        <th class="px-6 py-4 border-b border-slate-200 dark:border-white/5">Judul Evaluasi</th>
                                        <th class="px-6 py-4 text-center border-b border-slate-200 dark:border-white/5">Status</th>
                                        <th class="px-6 py-4 text-center border-b border-slate-200 dark:border-white/5" title="Skor dalam rentang 0-100">Skor</th>
                                        <th class="px-6 py-4 text-right border-b border-slate-200 dark:border-white/5">Waktu Pengumpulan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200 dark:divide-white/5 transition-colors">
                                    @forelse($quizAttempts as $idx => $q)
                                    @php 
                                        $qName = $q->chapter_id == '99' ? 'Final Evaluation' : 'Evaluasi Bab '.$q->chapter_id; 
                                        $quizStatusStr = strtolower($q->status ?? '');
                                        
                                        $limitQuizSec = isset($q->time_limit) ? $q->time_limit * 60 : 0;
                                        $qDuration = $q->time_spent_seconds ?? 0;
                                        $isQuizTimeout = $quizStatusStr === 'timeout' || $quizStatusStr === 'waktu habis' || (isset($q->is_timeout) && $q->is_timeout == 1) || ($limitQuizSec > 0 && $qDuration >= $limitQuizSec) || $qDuration > 43200;
                                        $isQuizPassed = $q->score >= 70;

                                        if ($isQuizPassed) {
                                            $qStatClass = 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20';
                                        } elseif ($isQuizTimeout) {
                                            $qStatClass = 'bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-500/20';
                                        } else {
                                            $qStatClass = 'bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-500/20';
                                        }
                                    @endphp
                                    <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors group table-row" x-show="searchQuiz === '' || '{{ addslashes(strtolower($qName)) }}'.includes(searchQuiz.toLowerCase())">
                                        <td class="px-6 py-4 text-center text-slate-400 font-mono text-xs">{{ $idx + 1 }}</td>
                                        <td class="px-6 py-4">
                                            <span class="block text-slate-900 dark:text-white font-semibold transition group-hover:text-fuchsia-600 dark:group-hover:text-fuchsia-300">{{ $qName }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="px-2.5 py-1 rounded text-[10px] font-black uppercase tracking-wider transition-colors {{ $qStatClass }}">
                                                {{ $isQuizTimeout ? 'TIMEOUT' : ($isQuizPassed ? 'LULUS' : 'GAGAL') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="text-slate-900 dark:text-white font-black transition-colors" title="Skor Tertinggi Evaluasi">{{ $q->score }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-right text-slate-500 dark:text-slate-400 text-xs font-mono transition-colors">
                                            <span class="block text-[11px] text-slate-700 dark:text-slate-300" title="{{ \Carbon\Carbon::parse($q->created_at)->diffForHumans() }}">
                                                {{ \Carbon\Carbon::parse($q->created_at)->format('d M Y, H:i') }} WIB
                                            </span>
                                            <div class="flex items-center justify-end gap-1.5 mt-0.5">
                                                <span class="text-[9px] opacity-70" title="Total durasi yang dihabiskan siswa">Durasi: {{ formatTime($qDuration) }}</span>
                                                @if($isQuizTimeout)
                                                    <span class="px-1 py-[1px] rounded-[4px] bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-400 text-[8px] font-bold tracking-wider uppercase border border-amber-200 dark:border-amber-500/30" title="Sesi ditinggalkan atau melebihi batas waktu evaluasi">Sesi Habis</span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-16 text-slate-500 dark:text-white/30 text-xs italic bg-slate-50 dark:bg-[#0a0e17]/50 rounded-xl m-4 block border border-dashed border-slate-300 dark:border-white/10 transition-colors">
                                            <svg class="w-8 h-8 mx-auto mb-3 text-slate-300 dark:text-white/10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            Belum ada data pengambilan kuis.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            @if(count($quizAttempts) > 0)
                                <div class="p-3 border-t border-slate-200 dark:border-white/5 bg-slate-50/30 dark:bg-[#020617]/20 text-center transition-colors">
                                    <span class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest transition-colors">Menampilkan {{ count($quizAttempts) }} Riwayat Terakhir</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>

    {{-- ==================== MODALS (INSIGHTS) ==================== --}}

    {{-- MODAL INFO AKADEMIK 1: MATERI (LESSON) --}}
    <div x-show="showLessonModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-slate-900/80 dark:bg-[#020617]/95 backdrop-blur-md transition-colors" @click="showLessonModal = false"></div>
        <div class="relative w-full max-w-lg bg-white dark:bg-[#0f141e] border border-cyan-200 dark:border-cyan-500/40 rounded-3xl p-6 md:p-8 transition-colors shadow-2xl" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2 transition-colors">Detail Materi Dibaca</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm mt-1 transition-colors">Siswa menyelesaikan <span class="font-bold text-cyan-600 dark:text-cyan-400">{{ count($completedLessonIds ?? []) }} dari {{ $totalLessons ?? 65 }}</span> materi teori.</p>
                </div>
                <button @click="showLessonModal = false" class="text-slate-400 hover:text-slate-700 dark:text-slate-500 dark:hover:text-white transition-colors bg-slate-100 hover:bg-slate-200 dark:bg-white/5 dark:hover:bg-white/10 rounded-full p-1.5" title="Tutup">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <div class="max-h-[50vh] overflow-y-auto custom-scrollbar pr-2 space-y-4">
                @foreach($curriculumMap as $chapter)
                    <div class="bg-slate-50 dark:bg-[#0a0e17] border border-slate-200 dark:border-white/5 rounded-xl p-4 transition-colors">
                        <h4 class="text-xs font-black text-slate-800 dark:text-white mb-3 uppercase tracking-widest transition-colors">Bab {{ $chapter['number'] }}: {{ $chapter['title'] }}</h4>
                        <div class="space-y-2">
                            @foreach($chapter['topics'] as $topic)
                                @php 
                                    $intersect = array_intersect($topic['ids'], $completedLessonIds ?? []);
                                    $doneCount = count($intersect);
                                    $totalCount = count($topic['ids']);
                                    $isDone = $doneCount === $totalCount;
                                @endphp
                                @if($doneCount > 0)
                                <div class="flex justify-between items-center text-[11px]">
                                    <span class="text-slate-600 dark:text-slate-300 flex items-center gap-2 font-medium transition-colors">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $isDone ? 'bg-emerald-500 shadow-[0_0_5px_#10b981]' : 'bg-amber-500' }}"></span>
                                        {{ $topic['name'] }}
                                    </span>
                                    <span class="font-mono font-bold {{ $isDone ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400' }} transition-colors">{{ $doneCount }}/{{ $totalCount }} Slide</span>
                                </div>
                                @endif
                            @endforeach
                            @if(count(array_intersect(Arr::collapse(array_column($chapter['topics'], 'ids')), $completedLessonIds ?? [])) === 0)
                                <p class="text-[10px] text-slate-400 dark:text-slate-500 italic transition-colors">Belum ada materi yang dibaca pada bab ini.</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    
    {{-- MODAL INFO AKADEMIK 2: LAB LULUS --}}
    <div x-show="showLabModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-slate-900/80 dark:bg-[#020617]/95 backdrop-blur-md transition-colors" @click="showLabModal = false"></div>
        <div class="relative w-full max-w-lg bg-white dark:bg-[#0f141e] border border-indigo-200 dark:border-indigo-500/40 rounded-3xl p-6 md:p-8 transition-colors shadow-2xl" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2 transition-colors">Detail Kelulusan Lab</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm mt-1 transition-colors">Siswa lulus <span class="font-bold text-indigo-600 dark:text-indigo-400">{{ count($passedLabIds ?? []) }} dari {{ $totalLabs ?? 4 }}</span> modul praktikum.</p>
                </div>
                <button @click="showLabModal = false" class="text-slate-400 hover:text-slate-700 dark:text-slate-500 dark:hover:text-white transition-colors bg-slate-100 hover:bg-slate-200 dark:bg-white/5 dark:hover:bg-white/10 rounded-full p-1.5" title="Tutup">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="max-h-[50vh] overflow-y-auto custom-scrollbar pr-2 space-y-3">
                @php $passedLabsList = $labHistories->where('status', 'passed'); @endphp
                @forelse($passedLabsList as $lab)
                <div class="flex justify-between items-center p-4 bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/5 rounded-xl transition-colors hover:border-indigo-300 dark:hover:border-indigo-500/30">
                    <div>
                        <p class="text-sm font-bold text-slate-900 dark:text-white transition-colors">{{ $lab->lab->title ?? 'Modul Lab #'.$lab->lab_id }}</p>
                        <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-1 flex items-center gap-1.5 transition-colors" title="{{ \Carbon\Carbon::parse($lab->created_at)->translatedFormat('l, d F Y') }}">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            {{ \Carbon\Carbon::parse($lab->created_at)->format('d M Y, H:i') }} WIB
                        </p>
                    </div>
                    <div class="text-right">
                        <span class="text-base font-black text-emerald-600 dark:text-emerald-400 transition-colors">{{ $lab->final_score }}</span>
                        <div class="flex items-center justify-end gap-1 mt-0.5">
                            <p class="text-[10px] text-slate-500 dark:text-slate-400 font-mono flex items-center gap-1 transition-colors">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ formatTime($lab->duration_seconds) }}
                            </p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-10">
                    <p class="text-xs text-slate-500 dark:text-slate-400 italic transition-colors">Belum ada modul praktikum yang diselesaikan dengan status lulus.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
    
    {{-- MODAL INFO AKADEMIK 3: KUIS LULUS --}}
    <div x-show="showQuizModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-slate-900/80 dark:bg-[#020617]/95 backdrop-blur-md transition-colors" @click="showQuizModal = false"></div>
        <div class="relative w-full max-w-lg bg-white dark:bg-[#0f141e] border border-fuchsia-200 dark:border-fuchsia-500/40 rounded-3xl p-6 md:p-8 transition-colors shadow-2xl" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2 transition-colors">Detail Kuis Lulus</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm mt-1 transition-colors">Siswa lulus <span class="font-bold text-fuchsia-600 dark:text-fuchsia-400">{{ count(array_filter($quizScoresMap ?? [], fn($s) => $s >= 70)) }} dari {{ $totalQuizzes ?? 4 }}</span> evaluasi bab.</p>
                </div>
                <button @click="showQuizModal = false" class="text-slate-400 hover:text-slate-700 dark:text-slate-500 dark:hover:text-white transition-colors bg-slate-100 hover:bg-slate-200 dark:bg-white/5 dark:hover:bg-white/10 rounded-full p-1.5" title="Tutup">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="max-h-[50vh] overflow-y-auto custom-scrollbar pr-2 space-y-3">
                @php $passedQuizzesList = $quizAttempts->where('score', '>=', 70); @endphp
                @forelse($passedQuizzesList as $quiz)
                <div class="flex justify-between items-center p-4 bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/5 rounded-xl transition-colors hover:border-fuchsia-300 dark:hover:border-fuchsia-500/30">
                    <div>
                        <p class="text-sm font-bold text-slate-900 dark:text-white transition-colors">{{ $quiz->chapter_id == 99 ? 'Evaluasi Akhir' : 'Evaluasi Bab '.$quiz->chapter_id }}</p>
                        <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-1 flex items-center gap-1.5 transition-colors" title="{{ \Carbon\Carbon::parse($quiz->created_at)->translatedFormat('l, d F Y') }}">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            {{ \Carbon\Carbon::parse($quiz->created_at)->format('d M Y, H:i') }} WIB
                        </p>
                    </div>
                    <div class="text-right">
                        <span class="text-base font-black text-emerald-600 dark:text-emerald-400 transition-colors">{{ $quiz->score }}</span>
                        <div class="flex items-center justify-end gap-1 mt-0.5">
                            <p class="text-[10px] text-slate-500 dark:text-slate-400 font-mono flex items-center gap-1 transition-colors">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ formatTime($quiz->time_spent_seconds ?? 0) }}
                            </p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-10">
                    <p class="text-xs text-slate-500 dark:text-slate-400 italic transition-colors">Belum ada riwayat kuis dengan nilai kelulusan (>= 70).</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- MODAL INFO AKADEMIK 4: AVG LAB --}}
    <div x-show="showAvgLabModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-slate-900/80 dark:bg-[#020617]/95 backdrop-blur-md transition-colors" @click="showAvgLabModal = false"></div>
        <div class="relative w-full max-w-lg bg-white dark:bg-[#0f141e] border border-emerald-200 dark:border-emerald-500/40 rounded-3xl p-6 md:p-8 transition-colors shadow-2xl" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2 transition-colors">Analisis Rata-rata Lab</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm mt-1 transition-colors">Total percobaan lab: <span class="font-bold text-emerald-600 dark:text-emerald-400">{{ count($labHistories) }} Kali</span></p>
                </div>
                <button @click="showAvgLabModal = false" class="text-slate-400 hover:text-slate-700 dark:text-slate-500 dark:hover:text-white transition-colors bg-slate-100 hover:bg-slate-200 dark:bg-white/5 dark:hover:bg-white/10 rounded-full p-1.5" title="Tutup">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="bg-slate-50 dark:bg-[#0a0e17] p-4 rounded-xl border border-slate-200 dark:border-white/5 transition-colors">
                    <p class="text-[10px] text-slate-500 dark:text-slate-400 uppercase tracking-widest font-bold mb-1 transition-colors">Skor Tertinggi</p>
                    <p class="text-2xl font-black text-emerald-600 dark:text-emerald-400 transition-colors">{{ $labHistories->max('final_score') ?? 0 }}</p>
                </div>
                <div class="bg-slate-50 dark:bg-[#0a0e17] p-4 rounded-xl border border-slate-200 dark:border-white/5 transition-colors">
                    <p class="text-[10px] text-slate-500 dark:text-slate-400 uppercase tracking-widest font-bold mb-1 transition-colors">Skor Terendah</p>
                    <p class="text-2xl font-black text-red-500 dark:text-red-400 transition-colors">{{ $labHistories->min('final_score') ?? 0 }}</p>
                </div>
            </div>

            <h4 class="text-xs font-bold text-slate-900 dark:text-white mb-3 transition-colors">Riwayat Semua Percobaan Lab</h4>
            <div class="max-h-[35vh] overflow-y-auto custom-scrollbar pr-2 space-y-2">
                @forelse($labHistories as $lab)
                <div class="flex justify-between items-center p-3 text-xs bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/5 rounded-lg transition-colors hover:bg-slate-100 dark:hover:bg-white/[0.04]">
                    <div>
                        <span class="font-semibold text-slate-800 dark:text-slate-200 block">{{ $lab->lab->title ?? 'Lab #'.$lab->lab_id }}</span>
                        <span class="text-[9px] text-slate-500 dark:text-slate-400 font-mono">{{ \Carbon\Carbon::parse($lab->created_at)->format('d M Y, H:i') }} WIB</span>
                    </div>
                    <div class="flex items-center gap-3">
                        @php
                            $st = strtolower($lab->status ?? '');
                            $lSec = isset($lab->lab->time_limit) ? $lab->lab->time_limit * 60 : (isset($lab->lab->duration) ? $lab->lab->duration * 60 : 0);
                            $isLabTO = $st === 'timeout' || $st === 'waktu habis' || (isset($lab->is_timeout) && $lab->is_timeout == 1) || ($lSec > 0 && $lab->duration_seconds >= $lSec) || $lab->duration_seconds > 43200;
                        @endphp
                        <span class="text-[9px] font-bold px-2 py-0.5 rounded transition-colors {{ strtolower($lab->status) == 'passed' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400' : ($isLabTO ? 'bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400' : 'bg-red-100 text-red-700 dark:bg-red-500/10 dark:text-red-400') }}">
                            {{ $isLabTO ? 'TIMEOUT' : strtoupper($lab->status) }}
                        </span>
                        <span class="font-mono font-bold w-6 text-right transition-colors {{ $lab->final_score >= 70 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-500 dark:text-red-400' }}">{{ $lab->final_score }}</span>
                    </div>
                </div>
                @empty
                <div class="text-center py-6 text-xs text-slate-500 dark:text-slate-400 italic">Belum ada percobaan lab dilakukan.</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- MODAL INFO AKADEMIK 5: AVG QUIZ --}}
    <div x-show="showAvgQuizModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-slate-900/80 dark:bg-[#020617]/95 backdrop-blur-md transition-colors" @click="showAvgQuizModal = false"></div>
        <div class="relative w-full max-w-lg bg-white dark:bg-[#0f141e] border border-amber-200 dark:border-amber-500/40 rounded-3xl p-6 md:p-8 transition-colors shadow-2xl" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2 transition-colors">Analisis Rata-rata Kuis</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm mt-1 transition-colors">Total percobaan evaluasi: <span class="font-bold text-amber-600 dark:text-amber-400">{{ count($quizAttempts) }} Kali</span></p>
                </div>
                <button @click="showAvgQuizModal = false" class="text-slate-400 hover:text-slate-700 dark:text-slate-500 dark:hover:text-white transition-colors bg-slate-100 hover:bg-slate-200 dark:bg-white/5 dark:hover:bg-white/10 rounded-full p-1.5" title="Tutup">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="bg-slate-50 dark:bg-[#0a0e17] p-4 rounded-xl border border-slate-200 dark:border-white/5 transition-colors">
                    <p class="text-[10px] text-slate-500 dark:text-slate-400 uppercase tracking-widest font-bold mb-1 transition-colors">Skor Tertinggi</p>
                    <p class="text-2xl font-black text-emerald-600 dark:text-emerald-400 transition-colors">{{ $quizAttempts->max('score') ?? 0 }}</p>
                </div>
                <div class="bg-slate-50 dark:bg-[#0a0e17] p-4 rounded-xl border border-slate-200 dark:border-white/5 transition-colors">
                    <p class="text-[10px] text-slate-500 dark:text-slate-400 uppercase tracking-widest font-bold mb-1 transition-colors">Skor Terendah</p>
                    <p class="text-2xl font-black text-red-500 dark:text-red-400 transition-colors">{{ $quizAttempts->min('score') ?? 0 }}</p>
                </div>
            </div>

            <h4 class="text-xs font-bold text-slate-900 dark:text-white mb-3 transition-colors">Riwayat Semua Percobaan Kuis</h4>
            <div class="max-h-[35vh] overflow-y-auto custom-scrollbar pr-2 space-y-2">
                @forelse($quizAttempts as $quiz)
                <div class="flex justify-between items-center p-3 text-xs bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/5 rounded-lg transition-colors hover:bg-slate-100 dark:hover:bg-white/[0.04]">
                    <div>
                        <span class="font-semibold text-slate-800 dark:text-slate-200 block">{{ $quiz->chapter_id == 99 ? 'Evaluasi Akhir' : 'Evaluasi Bab '.$quiz->chapter_id }}</span>
                        <span class="text-[9px] text-slate-500 dark:text-slate-400 font-mono">{{ \Carbon\Carbon::parse($quiz->created_at)->format('d M Y, H:i') }} WIB</span>
                    </div>
                    <div class="flex items-center gap-3">
                        @php
                            $stQ = strtolower($quiz->status ?? '');
                            $qDur = $quiz->time_spent_seconds ?? 0;
                            $qLim = isset($quiz->time_limit) ? $quiz->time_limit * 60 : 0;
                            $isQTO = $stQ === 'timeout' || $stQ === 'waktu habis' || (isset($quiz->is_timeout) && $quiz->is_timeout == 1) || ($qLim > 0 && $qDur >= $qLim) || $qDur > 43200;
                        @endphp
                        <span class="text-[9px] font-bold px-2 py-0.5 rounded transition-colors {{ $quiz->score >= 70 ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400' : ($isQTO ? 'bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400' : 'bg-red-100 text-red-700 dark:bg-red-500/10 dark:text-red-400') }}">
                            {{ $isQTO ? 'TIMEOUT' : ($quiz->score >= 70 ? 'LULUS' : 'GAGAL') }}
                        </span>
                        <span class="font-mono font-bold w-6 text-right transition-colors {{ $quiz->score >= 70 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-500 dark:text-red-400' }}">{{ $quiz->score }}</span>
                    </div>
                </div>
                @empty
                <div class="text-center py-6 text-xs text-slate-500 dark:text-slate-400 italic">Belum ada percobaan kuis dilakukan.</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- MODAL INFO AKADEMIK 6: GLOBAL PROGRESS (CYAN HERO) --}}
    <div x-show="showGlobalProgressModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-slate-900/80 dark:bg-[#020617]/95 backdrop-blur-md transition-colors" @click="showGlobalProgressModal = false"></div>
        <div class="relative w-full max-w-lg bg-white dark:bg-[#0f141e] border border-cyan-200 dark:border-cyan-500/40 rounded-3xl p-6 md:p-8 transition-colors shadow-2xl" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2 transition-colors">Kalkulasi Progres Global</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm mt-1 transition-colors">Transparansi perhitungan tingkat penyelesaian akhir siswa.</p>
                </div>
                <button @click="showGlobalProgressModal = false" class="text-slate-400 hover:text-slate-700 dark:text-slate-500 dark:hover:text-white transition-colors bg-slate-100 hover:bg-slate-200 dark:bg-white/5 dark:hover:bg-white/10 rounded-full p-1.5" title="Tutup">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            @php
                $calcLessonsDone = $lessonsCompleted ?? count($completedLessonIds ?? []);
                $calcLessonsTotal = $totalLessons ?? 65;
                
                $calcLabsDone = $labsCompleted ?? ($labStats['total'] ?? 0);
                $calcLabsTotal = $totalLabs ?? 4;
                
                $calcQuizzesDone = $chaptersPassed ?? count(array_filter($quizScoresMap ?? [], fn($s) => $s >= 70));
                $calcQuizzesTotal = $totalQuizzes ?? 4;
                
                $calcTotalDone = $calcLessonsDone + $calcLabsDone + $calcQuizzesDone;
                $calcTotalTarget = $calcLessonsTotal + $calcLabsTotal + $calcQuizzesTotal;
                
                $gProg = $globalProgress ?? 0;
                $pLabel = $gProg >= 100 ? 'Sempurna' : ($gProg >= 80 ? 'Sangat Baik' : ($gProg >= 50 ? 'Berkembang' : 'Perlu Perhatian'));
            @endphp

            <div class="bg-slate-50 dark:bg-[#0a0e17] rounded-xl p-5 border border-slate-200 dark:border-white/5 mb-6 transition-colors relative overflow-hidden">
                <div class="absolute right-0 top-0 bottom-0 w-24 bg-gradient-to-l from-cyan-500/10 to-transparent"></div>
                <div class="flex justify-between items-center mb-4 relative z-10">
                    <div>
                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300 block transition-colors">Total Keseluruhan</span>
                        <span class="text-[10px] text-cyan-600 dark:text-cyan-400 font-bold uppercase tracking-widest">{{ $pLabel }}</span>
                    </div>
                    <span class="text-2xl font-black text-cyan-600 dark:text-cyan-400 transition-colors">{{ $gProg }}%</span>
                </div>
                <div class="w-full bg-slate-200 dark:bg-[#020617] h-2 rounded-full overflow-hidden shadow-inner transition-colors relative z-10">
                    <div class="h-full bg-cyan-500 rounded-full transition-all duration-1000 shadow-[0_0_8px_rgba(6,182,212,0.6)]" style="width: {{ $gProg }}%"></div>
                </div>
                <p class="text-[10px] text-center text-slate-500 dark:text-slate-400 font-mono mt-3 relative z-10 transition-colors">Rumus: (Materi + Lab + Kuis) / Total Target * 100%</p>
            </div>

            <h4 class="text-xs font-bold text-slate-900 dark:text-white mb-3 uppercase tracking-widest transition-colors">Rincian Komponen Penilaian</h4>
            <div class="space-y-3">
                <div class="flex justify-between items-center p-3 text-xs bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/5 rounded-lg transition-colors hover:border-cyan-200 dark:hover:border-cyan-500/30" title="Setiap slide materi berkontribusi pada total">
                    <div class="flex items-center gap-2.5">
                        <div class="w-6 h-6 rounded bg-cyan-100 text-cyan-600 dark:bg-cyan-500/20 dark:text-cyan-400 flex items-center justify-center shrink-0 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                        </div>
                        <span class="font-semibold text-slate-800 dark:text-slate-200 transition-colors">Materi Teori (Dibaca)</span>
                    </div>
                    <span class="font-mono font-bold text-slate-600 dark:text-slate-300 transition-colors">{{ $calcLessonsDone }} / {{ $calcLessonsTotal }}</span>
                </div>
                
                <div class="flex justify-between items-center p-3 text-xs bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/5 rounded-lg transition-colors hover:border-indigo-200 dark:hover:border-indigo-500/30" title="Hanya lab berstatus 'Lulus' yang dihitung">
                    <div class="flex items-center gap-2.5">
                        <div class="w-6 h-6 rounded bg-indigo-100 text-indigo-600 dark:bg-indigo-500/20 dark:text-indigo-400 flex items-center justify-center shrink-0 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                        </div>
                        <span class="font-semibold text-slate-800 dark:text-slate-200 transition-colors">Praktik Lab (Lulus KKM)</span>
                    </div>
                    <span class="font-mono font-bold text-slate-600 dark:text-slate-300 transition-colors">{{ $calcLabsDone }} / {{ $calcLabsTotal }}</span>
                </div>
                
                <div class="flex justify-between items-center p-3 text-xs bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/5 rounded-lg transition-colors hover:border-fuchsia-200 dark:hover:border-fuchsia-500/30" title="Hanya evaluasi dengan skor >= 70 yang dihitung">
                    <div class="flex items-center gap-2.5">
                        <div class="w-6 h-6 rounded bg-fuchsia-100 text-fuchsia-600 dark:bg-fuchsia-500/20 dark:text-fuchsia-400 flex items-center justify-center shrink-0 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                        </div>
                        <span class="font-semibold text-slate-800 dark:text-slate-200 transition-colors">Evaluasi Kuis (Skor >= 70)</span>
                    </div>
                    <span class="font-mono font-bold text-slate-600 dark:text-slate-300 transition-colors">{{ $calcQuizzesDone }} / {{ $calcQuizzesTotal }}</span>
                </div>

                <div class="flex justify-between items-center p-3 mt-2 text-xs bg-cyan-50 dark:bg-cyan-500/10 border border-cyan-200 dark:border-cyan-500/20 rounded-lg transition-colors">
                    <span class="font-bold text-cyan-800 dark:text-cyan-200 uppercase tracking-widest transition-colors">Total Akumulasi</span>
                    <span class="font-mono font-black text-cyan-600 dark:text-cyan-400 transition-colors">{{ $calcTotalDone }} / {{ $calcTotalTarget }} Elemen</span>
                </div>
            </div>
            <p class="text-[9px] text-slate-400 dark:text-slate-500 mt-4 italic text-center transition-colors">*Hanya aktivitas dengan status Selesai atau Lulus yang memberikan kontribusi pada rasio pencapaian.</p>
        </div>
    </div>

    {{-- MODAL EDIT DATA SISWA (ADMIN) --}}
    <div x-show="showEdit" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="fixed inset-0 z-[100] flex items-center justify-center p-4" style="display: none;" x-cloak>
        <div class="absolute inset-0 bg-slate-900/80 dark:bg-[#020617]/80 backdrop-blur-sm transition-colors" @click="showEdit = false"></div>
        <div class="relative w-full max-w-xl bg-white dark:bg-[#0f141e] border border-slate-200 dark:border-white/10 rounded-2xl shadow-xl dark:shadow-[0_10px_40px_rgba(0,0,0,0.5)] p-6 md:p-8 transition-colors" @click.stop>
            <div class="flex items-center justify-between mb-6 border-b border-slate-200 dark:border-white/5 pb-4 transition-colors">
                <div>
                    <h3 class="text-lg font-black text-slate-900 dark:text-white transition-colors">Edit Data Siswa</h3>
                    <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-1">Terdaftar sejak: {{ \Carbon\Carbon::parse($user->created_at)->format('d M Y, H:i') }}</p>
                </div>
                <button @click="showEdit = false" class="text-slate-400 dark:text-slate-500 hover:text-slate-800 dark:hover:text-white transition bg-slate-100 dark:bg-white/5 hover:bg-slate-200 dark:hover:bg-white/10 p-1.5 rounded-lg" title="Tutup">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <form action="{{ route('admin.student.update', $user->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5" x-data="{ isSubmitting: false }" @submit="isSubmitting = true">
                @csrf 
                @method('PUT')
                
                <div>
                    <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 block transition-colors">Profile Photo <span class="text-slate-400 dark:text-white/30 lowercase font-normal ml-1">(Optional)</span></label>
                    <input type="file" name="avatar" accept="image/*" class="w-full glass-input rounded-xl px-4 py-2.5 text-sm font-medium focus:ring-2 ring-indigo-500/20 transition-all duration-300 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-indigo-50 dark:file:bg-indigo-500/20 file:text-indigo-600 dark:file:text-indigo-400 hover:file:bg-indigo-100 dark:hover:file:bg-indigo-500/30 cursor-pointer">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 block transition-colors">Full Name</label>
                        <input type="text" name="name" value="{{ $user->name }}" class="w-full glass-input rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 ring-indigo-500/20 transition-all duration-300 focus:ml-1" required>
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 block transition-colors">Email Address</label>
                        <input type="email" name="email" value="{{ $user->email }}" class="w-full glass-input rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 ring-indigo-500/20 transition-all duration-300 focus:ml-1" required>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="relative">
                        <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 block transition-colors">Class Group</label>
                        <div class="relative">
                            <select name="class_group" class="w-full glass-input rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 ring-indigo-500/20 transition-all duration-300 focus:ml-1 appearance-none cursor-pointer">
                                <option value="" class="bg-white dark:bg-[#0f141e] text-slate-400" {{ empty($user->class_group) ? 'selected' : '' }}>-- Pilih Kelas --</option>
                                @foreach($availableClasses ?? [] as $cls)
                                    <option value="{{ $cls->name }}" class="bg-white dark:bg-[#0f141e] text-slate-900 dark:text-white" {{ trim($user->class_group) === trim($cls->name) ? 'selected' : '' }}>
                                        {{ $cls->name }} {{ $cls->major ? ' - '.$cls->major : '' }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-400 dark:text-slate-500 transition-colors"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg></div>
                        </div>
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 block transition-colors">Phone Number</label>
                        <input type="text" name="phone" value="{{ $user->phone }}" class="w-full glass-input rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 ring-indigo-500/20 transition-all duration-300 focus:ml-1">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 block transition-colors">Institution</label>
                        <input type="text" name="institution" value="{{ $user->institution }}" class="w-full glass-input rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 ring-indigo-500/20 transition-all duration-300 focus:ml-1">
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 block transition-colors">Study Program</label>
                        <input type="text" name="study_program" value="{{ $user->study_program }}" class="w-full glass-input rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 ring-indigo-500/20 transition-all duration-300 focus:ml-1">
                    </div>
                </div>

                <div>
                    <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5 block transition-colors">Reset Password <span class="text-slate-400 dark:text-white/30 lowercase font-normal ml-1">(Biarkan kosong jika tidak diubah)</span></label>
                    <input type="password" name="password" placeholder="Masukkan password baru..." class="w-full glass-input rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 ring-indigo-500/20 transition-all duration-300 focus:ml-1">
                </div>
                
                <div class="flex justify-between items-center mt-8 pt-6 border-t border-slate-200 dark:border-white/5 transition-colors">
                    <button type="button" @click="confirmDelete()" class="text-[11px] font-bold text-red-600 dark:text-red-500 hover:text-red-700 dark:hover:text-red-400 transition flex items-center gap-1.5 px-3 py-2 rounded-lg hover:bg-red-50 dark:hover:bg-red-500/10">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Delete Student
                    </button>

                    <div class="flex gap-3">
                        <button type="button" @click="showEdit = false" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-white/5 transition-colors" :disabled="isSubmitting">Cancel</button>
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 dark:hover:bg-indigo-500 text-white text-xs font-bold shadow-md dark:shadow-[0_4px_15px_rgba(79,70,229,0.4)] transition-all flex items-center gap-2" :class="isSubmitting ? 'opacity-70 cursor-wait' : 'active:scale-95'" :disabled="isSubmitting">
                            <svg x-show="isSubmitting" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" x-cloak><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            <svg x-show="!isSubmitting" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span x-text="isSubmitting ? 'Processing...' : 'Save Changes'"></span>
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

        // SCRIPT CHART (Beradaptasi dengan Tema & Kaya Detail Tooltip)
        let scoreChartInstance = null;
        
        function renderCharts() {
            const isDark = document.documentElement.classList.contains('dark');
            const gridColor = isDark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)';
            const textColor = isDark ? '#94a3b8' : '#64748b';
            const pointBg = isDark ? '#0f141e' : '#ffffff';

            const ctxScore = document.getElementById('scoreChart');
            if(ctxScore && {!! json_encode($chartScores ?? []) !!}.length > 0) {
                if(scoreChartInstance) scoreChartInstance.destroy();
                const gradient = ctxScore.getContext('2d').createLinearGradient(0, 0, 0, 300);
                gradient.addColorStop(0, 'rgba(99, 102, 241, 0.3)'); gradient.addColorStop(1, 'rgba(99, 102, 241, 0)');
                scoreChartInstance = new Chart(ctxScore, { 
                    type: 'line', 
                    data: { 
                        labels: {!! json_encode($chartLabels ?? []) !!}, 
                        datasets: [{ 
                            label: 'Nilai Praktik', 
                            data: {!! json_encode($chartScores ?? []) !!}, 
                            borderColor: '#818cf8', backgroundColor: gradient, 
                            borderWidth: 3, tension: 0.4, fill: true, 
                            pointBackgroundColor: pointBg, pointBorderColor: '#a5b4fc', pointBorderWidth: 2, pointRadius: 5, pointHoverRadius: 7 
                        }] 
                    }, 
                    options: { 
                        responsive: true, maintainAspectRatio: false, 
                        plugins: { 
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: isDark ? 'rgba(15, 20, 30, 0.95)' : 'rgba(255, 255, 255, 0.95)',
                                titleColor: isDark ? '#ffffff' : '#0f172a',
                                bodyColor: isDark ? '#94a3b8' : '#64748b',
                                titleFont: { size: 13, family: 'Inter', weight: 'bold' },
                                bodyFont: { size: 12, family: 'JetBrains Mono' },
                                borderColor: isDark ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.1)',
                                borderWidth: 1,
                                padding: 12,
                                displayColors: false,
                                callbacks: {
                                    title: function(context) {
                                        return context[0].label;
                                    },
                                    label: function(context) {
                                        return ' Skor Capaian : ' + context.parsed.y + ' / 100';
                                    }
                                }
                            }
                        }, 
                        scales: { 
                            y: { 
                                beginAtZero: true, 
                                max: 100, 
                                grid: { color: gridColor }, 
                                ticks: { color: textColor, stepSize: 20 } 
                            }, 
                            x: { 
                                display: true,
                                grid: { display: false },
                                ticks: { color: textColor, font: { size: 10 }, maxRotation: 45, minRotation: 0 }
                            } 
                        } 
                    } 
                });
            }
        }

        document.addEventListener('DOMContentLoaded', renderCharts);
        window.addEventListener('theme-toggled', renderCharts);
    </script>
</body>
</html>