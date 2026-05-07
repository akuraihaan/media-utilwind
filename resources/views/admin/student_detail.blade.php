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
        .tab-btn.active::after { content: ''; position: absolute; bottom: -1px; left: 0; width: 100%; height: 2px; background: #6366f1; box-shadow: 0 -2px 10px #6366f1; border-radius: 2px 2px 0 0; }

        .reveal { opacity: 0; transform: translateY(15px); animation: revealAnim 0.5s forwards ease-out; }
        @keyframes revealAnim { to { opacity: 1; transform: translateY(0); } }
        [x-cloak] { display: none !important; }

        /* --- TOOLTIP INSIGHT (PETUNJUK HALAMAN) --- */
        .insight-tooltip { position: relative; display: inline-flex; align-items: center; justify-content: center; z-index: 50; }
        .insight-tooltip:hover { z-index: 99999; }
        
        .insight-trigger { 
            display: flex; align-items: center; justify-content: center;
            width: 16px; height: 16px; border-radius: 50%;
            background: rgba(99, 102, 241, 0.1); border: 1px solid rgba(99, 102, 241, 0.3);
            color: #6366f1; font-size: 10px; font-weight: 800; cursor: help;
            transition: all 0.3s;
        }
        .dark .insight-trigger { background: rgba(129, 140, 248, 0.1); border-color: rgba(129, 140, 248, 0.3); color: #818cf8; }
        .insight-tooltip:hover .insight-trigger { background: #6366f1; color: white; border-color: #6366f1; transform: scale(1.1); }
        
        .insight-content {
            opacity: 0; visibility: hidden; position: absolute; bottom: calc(100% + 10px); left: 50%; transform: translateX(-50%) translateY(10px);
            width: max-content; max-width: 240px; background: rgba(15, 23, 42, 0.95); backdrop-filter: blur(10px);
            color: #f8fafc; font-size: 11px; padding: 10px 14px; border-radius: 12px; text-align: center;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); 
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); pointer-events: none; font-weight: 500; line-height: 1.5;
        }
        .dark .insight-content { background: rgba(255, 255, 255, 0.95); color: #0f172a; border: 1px solid rgba(0,0,0,0.1); }
        
        .insight-tooltip:hover .insight-content { opacity: 1; visibility: visible; transform: translateX(-50%) translateY(0); }
        .insight-content::after {
            content: ''; position: absolute; top: 100%; left: 50%; transform: translateX(-50%);
            border-width: 6px; border-style: solid; border-color: rgba(15, 23, 42, 0.95) transparent transparent transparent;
        }
        .dark .insight-content::after { border-color: rgba(255, 255, 255, 0.95) transparent transparent transparent; }
        
        /* Tooltip Posisi Kanan (Bawah) */
        .insight-right .insight-content { bottom: auto; top: 50%; left: calc(100% + 10px); transform: translateY(-50%) translateX(-10px); }
        .insight-right:hover .insight-content { transform: translateY(-50%) translateX(0); }
        .insight-right .insight-content::after { top: 50%; left: -11px; transform: translateY(-50%); border-color: transparent rgba(15, 23, 42, 0.95) transparent transparent; border-width: 6px; }
        .dark .insight-right .insight-content::after { border-color: transparent rgba(255, 255, 255, 0.95) transparent transparent; }

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

    {{-- HELPER DATA BLADE --}}
    @php
        use Illuminate\Support\Str;

        function formatTime($seconds) {
            if ($seconds === null || $seconds === '') return '-';
            if ($seconds == 0) return '0s';
            if ($seconds > 43200) return '> 12j'; 
            
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

        // ANTI-BROKEN IMAGE LOGIC (Uploads & Cache Busting)
        $pathPrefix = 'uploads/'; 

        // Avatar untuk ADMIN
        $adminAvatarUrl = 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name ?? 'A').'&background=6366f1&color=fff&size=256';
        if (!empty(Auth::user()->avatar)) {
            $adminAvatarUrl = Str::startsWith(Auth::user()->avatar, ['http://', 'https://']) 
                ? Auth::user()->avatar 
                : asset($pathPrefix . Auth::user()->avatar) . '?v=' . time(); 
        }

        // Avatar untuk SISWA
        $studentAvatarUrl = 'https://ui-avatars.com/api/?name='.urlencode($user->name ?? 'S').'&background=06b6d4&color=fff&size=256';
        if (!empty($user->avatar)) {
            $studentAvatarUrl = Str::startsWith($user->avatar, ['http://', 'https://']) 
                ? $user->avatar 
                : asset($pathPrefix . $user->avatar) . '?v=' . time();
        }
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
                <img src="{{ $adminAvatarUrl }}" alt="Admin Avatar" class="w-8 h-8 rounded-full object-cover shadow-lg border border-slate-200 dark:border-white/10 bg-indigo-500">
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
                            {{-- LOGO SISWA DI HEADER --}}
                            <img src="{{ $studentAvatarUrl }}" alt="Avatar" class="w-full h-full object-cover rounded-[10px] bg-white dark:bg-[#0f141e]">
                            
                            {{-- Lencana Terverifikasi --}}
                            <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-emerald-500 rounded-full border-2 border-white dark:border-[#020617] flex items-center justify-center text-white">
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
                    {{-- Global Progress --}}
                    <div class="hidden xl:flex flex-col items-end mr-2 cursor-pointer hover:opacity-80 transition-opacity" @click="showGlobalProgressModal = true">
                        <div class="flex items-center gap-1.5 mb-1.5">
                            <p class="text-[9px] uppercase font-extrabold text-slate-500 dark:text-slate-400 tracking-widest transition-colors">Global Progress</p>
                            {{-- TOOLTIP INSIGHT --}}
                            <div class="insight-tooltip">
                                <span class="insight-trigger">?</span>
                                <div class="insight-content">Kalkulasi persentase penyelesaian keseluruhan berdasarkan materi bacaan, kelulusan praktikum lab, dan nilai evaluasi kuis siswa.</div>
                            </div>
                        </div>
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
                            <div class="px-4 py-2 border-b border-slate-100 dark:border-white/5 text-[9px] font-bold text-slate-400 dark:text-white/30 uppercase tracking-widest bg-slate-50 dark:bg-[#0a0e17] flex justify-between items-center transition-colors">
                                Pilih Format
                                {{-- TOOLTIP INSIGHT --}}
                                <div class="insight-tooltip insight-right">
                                    <span class="insight-trigger">?</span>
                                    <div class="insight-content">Unduh seluruh riwayat pengerjaan materi, praktik, dan kuis siswa dalam format dokumen yang Anda pilih.</div>
                                </div>
                            </div>
                            <a href="{{ route('admin.student.export.csv', $user->id) }}" class="flex items-center gap-3 px-4 py-3.5 text-[11px] font-bold text-slate-700 dark:text-white hover:bg-slate-50 dark:hover:bg-white/5 transition border-b border-slate-100 dark:border-white/5"><svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg> Export CSV</a>
                            <a href="{{ route('admin.student.export.pdf', $user->id) }}" target="_blank" class="flex items-center gap-3 px-4 py-3.5 text-[11px] font-bold text-slate-700 dark:text-white hover:bg-slate-50 dark:hover:bg-white/5 transition"><svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg> Print PDF</a>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <button @click="showEdit = true" class="p-2.5 rounded-full bg-indigo-50 dark:bg-indigo-500/10 hover:bg-indigo-100 dark:hover:bg-indigo-500 text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-white border border-indigo-200 dark:border-indigo-500/20 hover:border-indigo-300 dark:hover:border-indigo-500 transition-all shadow-sm dark:shadow-lg active:scale-95"><svg class="w-4 h-4 transition-transform hover:rotate-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg></button>
                        {{-- TOOLTIP INSIGHT --}}
                        <div class="insight-tooltip insight-right">
                            <span class="insight-trigger">?</span>
                            <div class="insight-content">Klik tombol edit ini untuk memperbarui profil, mengganti kelas, atau mengatur ulang kata sandi milik siswa.</div>
                        </div>
                    </div>
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
                    
                    {{-- PROFIL & STATUS KELAS --}}
                    <div class="animate-fade-in-up mb-8">
                        <div class="glass-card rounded-2xl p-6 md:p-8 flex flex-col md:flex-row items-start md:items-center justify-between gap-6 shadow-sm dark:shadow-lg border border-slate-200 dark:border-white/5 transition-colors">
                            
                            {{-- Student Basic Info --}}
                            <div class="flex items-center gap-5 w-full md:w-auto">
                                <div class="w-16 h-16 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 flex items-center justify-center text-xl font-bold shrink-0 transition-colors overflow-hidden border border-slate-200 dark:border-white/5">
                                    <img src="{{ $studentAvatarUrl }}" alt="Avatar" class="w-full h-full object-cover">
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
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Class Status --}}
                            <div class="w-full md:w-auto min-w-[280px]">
                                @empty($user->class_group)
                                    <button @click.stop="showEdit = true" class="w-full py-3 rounded-xl bg-indigo-50 dark:bg-indigo-600/20 hover:bg-indigo-100 dark:hover:bg-indigo-600 text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-white text-xs font-bold transition-colors border border-indigo-200 dark:border-indigo-500/30">
                                        Set Kelas Manual
                                    </button>
                                @else
                                    <div class="flex flex-col gap-2 w-full relative z-10" @click.stop>
                                        <div @click="showEdit = true" class="flex items-center justify-between gap-4 text-xs text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10 px-4 py-3 rounded-xl border border-emerald-200 dark:border-emerald-500/20 w-full transition-colors cursor-pointer hover:bg-emerald-100 dark:hover:bg-emerald-500/20 group">
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

                    {{-- VISUAL SEPARATOR & TOOLTIP PETUNJUK --}}
                    <div class="flex items-center gap-4 py-4">
                        <div class="h-px bg-slate-200 dark:bg-white/10 flex-1 transition-colors"></div>
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] font-black text-slate-500 dark:text-slate-600 uppercase tracking-[0.2em] bg-white dark:bg-[#020617] px-3 py-1 border border-slate-200 dark:border-white/5 rounded-full transition-colors">Academic Analytics</span>
                            <div class="insight-tooltip">
                                <span class="insight-trigger">?</span>
                                <div class="insight-content">Kartu-kartu di bawah dapat diklik untuk menampilkan modal detail rincian data metrik akademik siswa.</div>
                            </div>
                        </div>
                        <div class="h-px bg-slate-200 dark:bg-white/10 flex-1 transition-colors"></div>
                    </div>

                    {{-- ZONA AKADEMIK CARDS --}}
                    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-5 gap-4">
                        
                        {{-- 1. Materi Dibaca --}}
                        <div class="glass-card p-5 rounded-2xl relative group hover:border-cyan-400 dark:hover:border-cyan-500/40 cursor-pointer transition-colors" @click="showLessonModal = true">
                            <div class="flex justify-between items-start mb-3">
                                <p class="text-[10px] text-slate-500 dark:text-slate-400 uppercase font-bold tracking-widest group-hover:text-cyan-600 dark:group-hover:text-cyan-400 transition-colors">Materi Dibaca</p>
                                <span class="p-1.5 rounded-lg bg-cyan-50 dark:bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 border border-cyan-200 dark:border-cyan-500/10 shadow-sm dark:shadow-inner group-hover:scale-110 transition duration-300">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                                </span>
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
                                <span class="p-1.5 rounded-lg bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-500/10 shadow-sm dark:shadow-inner group-hover:scale-110 transition duration-300">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                                </span>
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
                                <span class="p-1.5 rounded-lg bg-fuchsia-50 dark:bg-fuchsia-500/10 text-fuchsia-600 dark:text-fuchsia-400 border border-fuchsia-200 dark:border-fuchsia-500/10 shadow-sm dark:shadow-inner group-hover:scale-110 transition duration-300">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                                </span>
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
                                <p class="text-[10px] text-slate-500 dark:text-slate-400 uppercase font-bold tracking-widest group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">Rata-rata Labs</p>
                                <span class="p-1.5 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/10 shadow-sm dark:shadow-inner group-hover:scale-110 transition duration-300">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" /></svg>
                                </span>
                            </div>
                            <div class="flex items-end gap-2">
                                <p class="text-3xl font-black text-slate-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors drop-shadow-sm dark:drop-shadow-[0_0_8px_rgba(16,185,129,0.3)]">{{ number_format($labAverage ?? ($labStats['avg_score'] ?? 0), 1) }}</p>
                                <p class="text-xs font-bold text-slate-400 dark:text-slate-500 mb-1 font-mono transition-colors">pts</p>
                            </div>
                            <div class="w-full h-1.5 bg-slate-200 dark:bg-[#0f141e] rounded-full mt-4 overflow-hidden border border-slate-300 dark:border-white/5 transition-colors">
                                <div class="h-full bg-emerald-500 rounded-full shadow-[0_0_10px_currentColor] transition-all duration-[1500ms] ease-out" :style="showProgress ? 'width: {{ $labAverage ?? ($labStats['avg_score'] ?? 0) }}%' : 'width: 0%'"></div>
                            </div>
                        </div>

                        {{-- 5. Avg Quiz Score --}}
                        <div class="glass-card p-5 rounded-2xl relative group hover:border-amber-400 dark:hover:border-amber-500/40 cursor-pointer transition-colors" @click="showAvgQuizModal = true">
                            <div class="flex justify-between items-start mb-3">
                                <p class="text-[10px] text-slate-500 dark:text-slate-400 uppercase font-bold tracking-widest group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors">Rata-rata Kuis</p>
                                <span class="p-1.5 rounded-lg bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-500/10 shadow-sm dark:shadow-inner group-hover:scale-110 transition duration-300">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                                </span>
                            </div>
                            <div class="flex items-end gap-2">
                                <p class="text-3xl font-black text-slate-900 dark:text-white group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors drop-shadow-sm dark:drop-shadow-[0_0_8px_rgba(245,158,11,0.3)]">{{ number_format($quizAverage ?? ($quizStats['avg_score'] ?? 0), 1) }}</p>
                                <p class="text-xs font-bold text-slate-400 dark:text-slate-500 mb-1 font-mono transition-colors">pts</p>
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
                                            <div class="flex items-center justify-between w-full mt-2">
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
                                            <div class="flex items-center justify-between w-full mt-2">
                                                <div class="w-full bg-slate-200 dark:bg-[#020617] h-1 rounded-full mr-3 shadow-inner">
                                                    <div class="{{ $pQuiz == 100 ? 'bg-emerald-500' : 'bg-fuchsia-500' }} h-1 rounded-full transition-all duration-1000" style="width: {{ $pQuiz }}%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="text-sm font-black transition-colors {{ $qC >= ($totalQuizzes ?? 4) ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-400 dark:text-slate-300' }} shrink-0">{{ $qC }}/{{ $totalQuizzes ?? 4 }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="lg:col-span-2 glass-card rounded-2xl p-6 relative flex flex-col transition-colors">
                            <div class="flex justify-between items-center mb-6">
                                <div>
                                    <h3 class="text-sm font-black text-slate-900 dark:text-white tracking-wide transition-colors">Tren Performa Lab</h3>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 transition-colors">Visualisasi skor dari 10 modul praktik terakhir (Sumbu Y = Skor 0-100, Sumbu X = Nama Modul).</p>
                                </div>
                            </div>
                            <div class="flex-1 min-h-[250px] w-full relative">
                                @if(isset($chartScores) && count($chartScores) > 0)
                                    <canvas id="scoreChart"></canvas>
                                @else
                                    <div class="absolute inset-0 flex flex-col items-center justify-center border-2 border-dashed border-slate-200 dark:border-white/5 rounded-xl bg-slate-50 dark:bg-white/[0.01] transition-colors">
                                        <svg class="w-8 h-8 text-slate-400 dark:text-slate-600 mb-3 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2m0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
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
                    
                    <div class="flex items-center gap-2 mb-6 ml-2">
                        <span class="text-lg font-black text-slate-800 dark:text-white tracking-tight">Capaian Kurikulum</span>
                        <div class="insight-tooltip insight-right">
                            <span class="insight-trigger">?</span>
                            <div class="insight-content">Fitur ini melacak progres materi (slide), kelulusan lab praktikum, dan status evaluasi untuk setiap sub-bab.</div>
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
                                            <div class="relative pl-6 flex items-center justify-between group/item hover:bg-slate-50 dark:hover:bg-white/[0.02] p-1.5 -ml-1.5 rounded-lg transition-colors cursor-default">
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
                                        <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50 dark:bg-white/[0.02] hover:bg-slate-100 dark:hover:bg-white/[0.04] transition-colors">
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
                                        <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50 dark:bg-white/[0.02] hover:bg-slate-100 dark:hover:bg-white/[0.04] transition-colors">
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
                    
                    <div class="flex items-center gap-2 mb-6 ml-2">
                        <span class="text-lg font-black text-slate-800 dark:text-white tracking-tight">Log Aktivitas</span>
                        <div class="insight-tooltip insight-right">
                            <span class="insight-trigger">?</span>
                            <div class="insight-content">Tabel di bawah berisi rekaman absolut dari setiap percobaan praktik dan evaluasi kuis yang dikumpulkan oleh siswa.</div>
                        </div>
                    </div>

                    {{-- 1. Lab History --}}
                    <div class="glass-card rounded-2xl relative z-10 flex flex-col transition-colors" style="overflow: visible !important;">
                        <div class="p-6 border-b border-slate-200 dark:border-white/5 bg-slate-50/50 dark:bg-[#020617]/40 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 rounded-t-2xl transition-colors">
                            <div>
                                <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2 transition-colors">
                                    <span class="text-indigo-600 dark:text-indigo-400">
                                        <svg class="w-5 h-5 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                                    </span> Riwayat Praktik Lab
                                </h3>
                            </div>
                            
                            <div class="flex flex-wrap items-center gap-3 w-full md:w-auto relative z-40">
                                <div class="relative w-full sm:w-64 group">
                                    <input type="text" x-model="searchLab" placeholder="Cari lab atau status..." 
                                        class="w-full bg-white dark:bg-[#0a0e17] border border-slate-200 dark:border-white/10 rounded-xl pl-10 pr-4 py-2.5 text-xs text-slate-900 dark:text-white focus:border-indigo-500 outline-none transition-colors shadow-sm dark:shadow-inner placeholder-slate-400 dark:placeholder-white/20">
                                    <svg class="w-3.5 h-3.5 absolute left-3 top-3 text-slate-400 dark:text-white/30 group-focus-within:text-indigo-600 dark:group-focus-within:text-indigo-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </div>
                            </div>
                        </div>

                        <div class="overflow-x-auto relative p-0 sm:p-6 pt-0 border-t border-slate-200 dark:border-white/5 sm:border-none transition-colors">
                            <table class="w-full text-sm text-left whitespace-nowrap sm:whitespace-normal border border-slate-200 dark:border-white/5 rounded-xl shadow-inner bg-slate-50/50 dark:bg-[#0a0e17]/30 transition-colors duration-500">
                                <thead class="bg-slate-100 dark:bg-[#0f141e] text-slate-500 dark:text-white/40 text-[10px] uppercase font-bold border-b border-slate-200 dark:border-white/5 sticky top-0 z-20 transition-colors">
                                    <tr>
                                        <th class="px-6 py-4 border-b border-slate-200 dark:border-white/5 w-10 text-center">#</th>
                                        <th class="px-6 py-4 border-b border-slate-200 dark:border-white/5">Judul Modul</th>
                                        <th class="px-6 py-4 text-center border-b border-slate-200 dark:border-white/5">Status</th>
                                        <th class="px-6 py-4 text-center border-b border-slate-200 dark:border-white/5">Skor</th>
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
                                            <span class="text-slate-900 dark:text-white font-black transition-colors">{{ $h->final_score }}</span>
                                        </td>
                                        
                                        <td class="px-6 py-4 text-right text-slate-500 dark:text-slate-400 text-xs font-mono transition-colors">
                                            <span class="block text-[11px] text-slate-700 dark:text-slate-300">
                                                {{ \Carbon\Carbon::parse($h->created_at)->format('d M Y, H:i') }} WIB
                                            </span>
                                            <div class="flex items-center justify-end gap-1.5 mt-0.5">
                                                <span class="text-[9px] opacity-70">Durasi: {{ formatTime($h->duration_seconds) }}</span>
                                                @if($isLabTimeout)
                                                    <span class="px-1 py-[1px] rounded-[4px] bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-400 text-[8px] font-bold tracking-wider uppercase border border-amber-200 dark:border-amber-500/30">Sesi Habis</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            @if($h->last_code_snapshot != null && $h->last_code_snapshot != '')
                                                <button @click="expanded = !expanded" class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-white dark:bg-[#020617] hover:bg-indigo-600 border border-slate-200 dark:border-white/10 hover:border-indigo-500 text-slate-700 hover:text-white dark:text-white text-[10px] font-bold transition-all shadow-sm dark:shadow-inner hover:shadow-[0_0_15px_rgba(99,102,241,0.5)] group/btn relative z-30 gap-1.5">
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
                                                        <button @click="navigator.clipboard.writeText(`{{ addslashes($h->last_code_snapshot) }}`); copied = true; setTimeout(() => copied = false, 2000)" 
                                                                class="absolute top-2 right-2 p-1.5 rounded-lg bg-white/10 hover:bg-white/20 text-white/50 hover:text-white transition-all z-10 flex items-center justify-center">
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
                        </div>
                    </div>

                    {{-- 2. Quiz History --}}
                    <div class="glass-card rounded-2xl relative z-10 flex flex-col transition-colors mt-8" style="overflow: visible !important;">
                        <div class="p-6 border-b border-slate-200 dark:border-white/5 bg-slate-50/50 dark:bg-[#020617]/40 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 rounded-t-2xl transition-colors">
                            <div>
                                <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2 transition-colors">
                                    <span class="text-fuchsia-600 dark:text-fuchsia-400">
                                        <svg class="w-5 h-5 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                                    </span> Riwayat Evaluasi Kuis
                                </h3>
                            </div>
                            <div class="flex flex-wrap items-center gap-3 w-full md:w-auto relative z-40">
                                <div class="relative w-full sm:w-64 group">
                                    <input type="text" x-model="searchQuiz" placeholder="Cari evaluasi..." 
                                        class="w-full bg-white dark:bg-[#0a0e17] border border-slate-200 dark:border-white/10 rounded-xl pl-10 pr-4 py-2.5 text-xs text-slate-900 dark:text-white focus:border-fuchsia-500 outline-none transition-colors shadow-sm dark:shadow-inner placeholder-slate-400 dark:placeholder-white/20">
                                    <svg class="w-3.5 h-3.5 absolute left-3 top-3 text-slate-400 dark:text-white/30 group-focus-within:text-fuchsia-600 dark:group-focus-within:text-fuchsia-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </div>
                            </div>
                        </div>
                        
                        <div class="overflow-x-auto relative p-0 sm:p-6 pt-0 border-t border-slate-200 dark:border-white/5 sm:border-none transition-colors">
                            <table class="w-full text-sm text-left whitespace-nowrap sm:whitespace-normal border border-slate-200 dark:border-white/5 rounded-xl shadow-inner bg-slate-50/50 dark:bg-[#0a0e17]/30 transition-colors duration-500">
                                <thead class="bg-slate-100 dark:bg-[#0f141e] text-slate-500 dark:text-white/40 text-[10px] uppercase font-bold border-b border-slate-200 dark:border-white/5 sticky top-0 z-20 transition-colors">
                                    <tr>
                                        <th class="px-6 py-4 border-b border-slate-200 dark:border-white/5 w-10 text-center">#</th>
                                        <th class="px-6 py-4 border-b border-slate-200 dark:border-white/5">Judul Evaluasi</th>
                                        <th class="px-6 py-4 text-center border-b border-slate-200 dark:border-white/5">Status</th>
                                        <th class="px-6 py-4 text-center border-b border-slate-200 dark:border-white/5">Skor</th>
                                        <th class="px-6 py-4 text-right border-b border-slate-200 dark:border-white/5">Waktu Pengumpulan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200 dark:divide-white/5 transition-colors">
                                    @forelse($quizAttempts as $idx => $q)
                                    @php 
                                        $qName = $q->chapter_id == '99' ? 'Evaluasi Akhir' : 'Evaluasi Bab '.$q->chapter_id; 
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
                                    <tr class="hover:bg-slate-50 dark:hover:bg-white/[0.02] transition-colors group table-row" x-show="searchQuiz === '' || '{{ addslashes(strtolower($qName)) }}'.includes(searchQuiz.toLowerCase())">
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
                                            <span class="text-slate-900 dark:text-white font-black transition-colors">{{ $q->score }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-right text-slate-500 dark:text-slate-400 text-xs font-mono transition-colors">
                                            <span class="block text-[11px] text-slate-700 dark:text-slate-300">
                                                {{ \Carbon\Carbon::parse($q->created_at)->format('d M Y, H:i') }} WIB
                                            </span>
                                            <div class="flex items-center justify-end gap-1.5 mt-0.5">
                                                <span class="text-[9px] opacity-70">Durasi: {{ formatTime($qDuration) }}</span>
                                                @if($isQuizTimeout)
                                                    <span class="px-1 py-[1px] rounded-[4px] bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-400 text-[8px] font-bold tracking-wider uppercase border border-amber-200 dark:border-amber-500/30">Sesi Habis</span>
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
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>

    {{-- ==================== MODALS (INSIGHTS) ==================== --}}

    {{-- MODAL INFO AKADEMIK 1: MATERI (LESSON) --}}
    <div x-show="showLessonModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-slate-900/40 dark:bg-[#020617]/80 backdrop-blur-sm transition-colors" @click="showLessonModal = false"></div>
        <div class="relative w-full max-w-lg bg-white dark:bg-[#0f141e] border border-slate-200 dark:border-white/10 rounded-3xl p-6 md:p-8 transition-colors shadow-2xl" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white transition-colors">Detail Materi Dibaca</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm mt-1 transition-colors">Siswa menyelesaikan <span class="font-bold text-slate-900 dark:text-white">{{ count($completedLessonIds ?? []) }} dari {{ $totalLessons ?? 65 }}</span> materi.</p>
                </div>
                <button @click="showLessonModal = false" class="text-slate-400 hover:text-slate-700 dark:text-slate-500 dark:hover:text-white transition-colors p-2">
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
                <button @click="showLabModal = false" class="text-slate-400 hover:text-slate-700 dark:text-slate-500 dark:hover:text-white transition-colors p-2">
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
                <button @click="showQuizModal = false" class="text-slate-400 hover:text-slate-700 dark:text-slate-500 dark:hover:text-white transition-colors p-2">
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

    {{-- MODAL INFO AKADEMIK 4: AVG LAB --}}
    <div x-show="showAvgLabModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-slate-900/40 dark:bg-[#020617]/80 backdrop-blur-sm transition-colors" @click="showAvgLabModal = false"></div>
        <div class="relative w-full max-w-lg bg-white dark:bg-[#0f141e] border border-slate-200 dark:border-white/10 rounded-3xl p-6 md:p-8 transition-colors shadow-2xl" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white transition-colors">Analisis Praktik</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm mt-1 transition-colors">Total percobaan: <span class="font-bold text-slate-900 dark:text-white">{{ count($labHistories) }} Kali</span></p>
                </div>
                <button @click="showAvgLabModal = false" class="text-slate-400 hover:text-slate-700 dark:text-slate-500 dark:hover:text-white transition-colors p-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="bg-slate-50 dark:bg-[#1d1d1f] p-5 rounded-2xl transition-colors">
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-semibold mb-1 transition-colors">Skor Tertinggi</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white transition-colors">{{ $labHistories->max('final_score') ?? 0 }}</p>
                </div>
                <div class="bg-slate-50 dark:bg-[#1d1d1f] p-5 rounded-2xl transition-colors">
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-semibold mb-1 transition-colors">Skor Terendah</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white transition-colors">{{ $labHistories->min('final_score') ?? 0 }}</p>
                </div>
            </div>

            <h4 class="text-sm font-semibold text-slate-900 dark:text-white mb-4 transition-colors">Riwayat Percobaan</h4>
            <div class="max-h-[35vh] overflow-y-auto custom-scrollbar pr-2 space-y-3">
                @forelse($labHistories as $lab)
                <div class="flex justify-between items-center p-4 bg-slate-50 dark:bg-[#1d1d1f] rounded-2xl transition-colors">
                    <div>
                        <span class="font-semibold text-sm text-slate-900 dark:text-white block">{{ $lab->lab->title ?? 'Lab #'.$lab->lab_id }}</span>
                        <span class="text-xs text-slate-500 dark:text-slate-400">{{ \Carbon\Carbon::parse($lab->created_at)->format('d M Y') }}</span>
                    </div>
                    <span class="font-bold text-slate-900 dark:text-white transition-colors">{{ $lab->final_score }}</span>
                </div>
                @empty
                <div class="text-center py-6 text-sm text-slate-500 dark:text-slate-400 italic">Belum ada percobaan dilakukan.</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- MODAL INFO AKADEMIK 5: AVG QUIZ --}}
    <div x-show="showAvgQuizModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-slate-900/40 dark:bg-[#020617]/80 backdrop-blur-sm transition-colors" @click="showAvgQuizModal = false"></div>
        <div class="relative w-full max-w-lg bg-white dark:bg-[#0f141e] border border-slate-200 dark:border-white/10 rounded-3xl p-6 md:p-8 transition-colors shadow-2xl" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white transition-colors">Analisis Evaluasi</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm mt-1 transition-colors">Total percobaan: <span class="font-bold text-slate-900 dark:text-white">{{ count($quizAttempts) }} Kali</span></p>
                </div>
                <button @click="showAvgQuizModal = false" class="text-slate-400 hover:text-slate-700 dark:text-slate-500 dark:hover:text-white transition-colors p-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="bg-slate-50 dark:bg-[#1d1d1f] p-5 rounded-2xl transition-colors">
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-semibold mb-1 transition-colors">Skor Tertinggi</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white transition-colors">{{ $quizAttempts->max('score') ?? 0 }}</p>
                </div>
                <div class="bg-slate-50 dark:bg-[#1d1d1f] p-5 rounded-2xl transition-colors">
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-semibold mb-1 transition-colors">Skor Terendah</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white transition-colors">{{ $quizAttempts->min('score') ?? 0 }}</p>
                </div>
            </div>

            <h4 class="text-sm font-semibold text-slate-900 dark:text-white mb-4 transition-colors">Riwayat Percobaan</h4>
            <div class="max-h-[35vh] overflow-y-auto custom-scrollbar pr-2 space-y-3">
                @forelse($quizAttempts as $quiz)
                <div class="flex justify-between items-center p-4 bg-slate-50 dark:bg-[#1d1d1f] rounded-2xl transition-colors">
                    <div>
                        <span class="font-semibold text-sm text-slate-900 dark:text-white block">{{ $quiz->chapter_id == 99 ? 'Evaluasi Akhir' : 'Evaluasi Bab '.$quiz->chapter_id }}</span>
                        <span class="text-xs text-slate-500 dark:text-slate-400">{{ \Carbon\Carbon::parse($quiz->created_at)->format('d M Y') }}</span>
                    </div>
                    <span class="font-bold text-slate-900 dark:text-white transition-colors">{{ $quiz->score }}</span>
                </div>
                @empty
                <div class="text-center py-6 text-sm text-slate-500 dark:text-slate-400 italic">Belum ada percobaan dilakukan.</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- MODAL INFO AKADEMIK 6: GLOBAL PROGRESS --}}
    <div x-show="showGlobalProgressModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-slate-900/40 dark:bg-[#020617]/80 backdrop-blur-sm transition-colors" @click="showGlobalProgressModal = false"></div>
        <div class="relative w-full max-w-lg bg-white dark:bg-[#0f141e] border border-slate-200 dark:border-white/10 rounded-3xl p-6 md:p-8 transition-colors shadow-2xl" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white transition-colors">Progres Global</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm mt-1 transition-colors">Kalkulasi tingkat penyelesaian akhir.</p>
                </div>
                <button @click="showGlobalProgressModal = false" class="text-slate-400 hover:text-slate-700 dark:text-slate-500 dark:hover:text-white transition-colors p-2">
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
            @endphp

            <div class="bg-slate-50 dark:bg-[#1d1d1f] rounded-2xl p-6 mb-8 transition-colors">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-sm font-semibold text-slate-500 dark:text-slate-400 transition-colors">Penyelesaian</span>
                    <span class="text-3xl font-bold text-slate-900 dark:text-white transition-colors">{{ $gProg }}%</span>
                </div>
                <div class="w-full bg-slate-200 dark:bg-slate-800 h-2 rounded-full overflow-hidden transition-colors">
                    <div class="h-full bg-blue-500 rounded-full transition-all duration-1000" style="width: {{ $gProg }}%"></div>
                </div>
            </div>

            <h4 class="text-sm font-semibold text-slate-900 dark:text-white mb-4 transition-colors">Rincian Komponen</h4>
            <div class="space-y-4">
                <div class="flex justify-between items-center text-sm transition-colors">
                    <span class="font-medium text-slate-700 dark:text-slate-300">Materi Teori</span>
                    <span class="font-semibold text-slate-900 dark:text-white">{{ $calcLessonsDone }} / {{ $calcLessonsTotal }}</span>
                </div>
                <div class="w-full h-px bg-slate-200 dark:bg-white/5"></div>
                <div class="flex justify-between items-center text-sm transition-colors">
                    <span class="font-medium text-slate-700 dark:text-slate-300">Praktik Selesai</span>
                    <span class="font-semibold text-slate-900 dark:text-white">{{ $calcLabsDone }} / {{ $calcLabsTotal }}</span>
                </div>
                <div class="w-full h-px bg-slate-200 dark:bg-white/5"></div>
                <div class="flex justify-between items-center text-sm transition-colors">
                    <span class="font-medium text-slate-700 dark:text-slate-300">Evaluasi Lulus</span>
                    <span class="font-semibold text-slate-900 dark:text-white">{{ $calcQuizzesDone }} / {{ $calcQuizzesTotal }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL EDIT DATA SISWA (ADMIN) --}}
    <div x-show="showEdit" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="fixed inset-0 z-[100] flex items-center justify-center p-4" style="display: none;" x-cloak>
        <div class="absolute inset-0 bg-slate-900/40 dark:bg-[#020617]/80 backdrop-blur-sm transition-colors" @click="showEdit = false"></div>
        <div class="relative w-full max-w-xl bg-white dark:bg-[#0f141e] border border-slate-200 dark:border-white/10 rounded-3xl p-6 md:p-8 transition-colors shadow-2xl" @click.stop>
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white transition-colors">Edit Data Siswa</h3>
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
                        <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2 block transition-colors">Email Address</label>
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
                    <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2 block transition-colors">Reset Password <span class="text-slate-400 dark:text-slate-500 font-normal">(Kosongkan jika tidak diubah)</span></label>
                    <input type="password" name="password" placeholder="Masukkan password baru..." class="w-full bg-slate-50 dark:bg-[#1d1d1f] border border-slate-200 dark:border-white/10 rounded-xl px-4 py-3 text-sm text-slate-900 dark:text-white focus:border-blue-500 outline-none transition-colors">
                </div>
                
                <div class="flex justify-between items-center mt-10 pt-6 border-t border-slate-200 dark:border-white/5 transition-colors">
                    <button type="button" @click="confirmDelete()" class="text-sm font-semibold text-red-500 hover:text-red-600 transition-colors px-3 py-2 rounded-lg hover:bg-red-50 dark:hover:bg-red-500/10">
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
            const gridColor = isDark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)';
            const textColor = isDark ? '#64748b' : '#64748b';
            const pointBg = isDark ? '#1d1d1f' : '#ffffff';

            const ctxScore = document.getElementById('scoreChart');
            if(ctxScore && {!! json_encode($chartScores ?? []) !!}.length > 0) {
                if(scoreChartInstance) scoreChartInstance.destroy();
                const gradient = ctxScore.getContext('2d').createLinearGradient(0, 0, 0, 300);
                gradient.addColorStop(0, 'rgba(59, 130, 246, 0.2)'); gradient.addColorStop(1, 'rgba(59, 130, 246, 0)');
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
                                backgroundColor: isDark ? 'rgba(30, 30, 30, 0.95)' : 'rgba(255, 255, 255, 0.95)',
                                titleColor: isDark ? '#ffffff' : '#0f172a',
                                bodyColor: isDark ? '#cbd5e1' : '#64748b',
                                titleFont: { size: 13, family: 'Inter', weight: 'bold' },
                                bodyFont: { size: 12, family: 'Inter' },
                                borderColor: isDark ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.1)',
                                borderWidth: 1,
                                padding: 12,
                                displayColors: false
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
                                ticks: { color: textColor, font: { size: 11 } }
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