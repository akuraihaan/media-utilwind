<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Deep Dive · {{ $user->name ?? 'Student' }}</title>
    
    {{-- RESOURCES --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <style>
        /* --- THEME CONFIG (ORIGINAL DARK-GLASSMORPHISM) --- */
        :root { 
            --glass-bg: rgba(10, 14, 23, 0.65); 
            --glass-border: rgba(255, 255, 255, 0.08); 
            --accent: #6366f1; 
        }
        body { font-family: 'Inter', sans-serif; background-color: #020617; color: #e2e8f0; overflow: hidden; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }

        /* --- SCROLLBAR --- */
        .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: rgba(0,0,0,0.1); border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.25); }

        /* --- GLASS COMPONENTS --- */
        .glass-sidebar { background: rgba(5, 8, 16, 0.95); backdrop-filter: blur(20px); border-right: 1px solid var(--glass-border); z-index: 50; }
        .glass-header { background: rgba(2, 6, 23, 0.7); backdrop-filter: blur(16px); border-bottom: 1px solid var(--glass-border); z-index: 40; }
        .glass-panel { background: var(--glass-bg); backdrop-filter: blur(12px); border: 1px solid var(--glass-border); box-shadow: 0 4px 24px -4px rgba(0,0,0,0.3); }
        
        .glass-card { 
            background: var(--glass-bg); border: 1px solid var(--glass-border); 
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.2); backdrop-filter: blur(10px); transition: all 0.3s; 
            position: relative;
            overflow: visible !important; /* MEMASTIKAN TOOLTIP TIDAK TERPOTONG */
            z-index: 10;
        }
        .glass-card:hover { border-color: rgba(99, 102, 241, 0.4); transform: translateY(-4px); box-shadow: 0 12px 40px -10px rgba(99,102,241,0.15); z-index: 30; }

        /* Container SVG Background Card */
        .card-bg-gfx { position: absolute; inset: 0; overflow: hidden; border-radius: 1rem; pointer-events: none; z-index: 0; }

        /* --- INPUTS & NAV --- */
        .glass-input { background: rgba(255, 255, 255, 0.02); border: 1px solid rgba(255, 255, 255, 0.08); color: white; transition: 0.3s; }
        .glass-input:focus { border-color: var(--accent); background: rgba(255, 255, 255, 0.05); outline: none; box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15); }
        
        .nav-link { display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 12px; color: #94a3b8; font-weight: 500; font-size: 0.9rem; transition: all 0.2s; border: 1px solid transparent; }
        .nav-link:hover { background: rgba(255, 255, 255, 0.03); color: white; transform: translateX(4px); }
        .nav-link.active { background: linear-gradient(90deg, rgba(99, 102, 241, 0.1) 0%, rgba(99, 102, 241, 0) 100%); color: #818cf8; border-left: 3px solid #818cf8; border-radius: 4px 12px 12px 4px; }
        
        .tab-btn { position: relative; color: #64748b; transition: all 0.3s; }
        .tab-btn:hover { color: #cbd5e1; }
        .tab-btn.active { color: #fff; text-shadow: 0 0 12px rgba(255,255,255,0.3); }
        .tab-btn.active::after { content: ''; position: absolute; bottom: -1px; left: 0; width: 100%; height: 2px; background: var(--accent); box-shadow: 0 -2px 10px var(--accent); border-radius: 2px 2px 0 0; }
        
        /* --- CODE VIEWER --- */
        .code-block { background: #0d1117; border: 1px solid #30363d; border-radius: 8px; padding: 1rem; color: #c9d1d9; font-size: 0.85rem; line-height: 1.5; overflow-x: auto; }

        .reveal { opacity: 0; transform: translateY(15px); animation: revealAnim 0.5s forwards ease-out; }
        @keyframes revealAnim { to { opacity: 1; transform: translateY(0); } }
        .table-row { transition: background 0.2s; border-bottom: 1px solid rgba(255,255,255,0.03); }
        .table-row:hover { background: rgba(255,255,255,0.05); }
        [x-cloak] { display: none !important; }

        /* SweetAlert2 Custom Dark Theme */
        .swal2-popup { background: #0f141e !important; border: 1px solid rgba(255, 255, 255, 0.1) !important; color: #fff !important; border-radius: 1rem !important; }
        .swal2-title { color: #fff !important; font-family: 'Inter', sans-serif !important; }
        .swal2-html-container { color: #94a3b8 !important; font-family: 'Inter', sans-serif !important; }
        .swal2-confirm { background-color: #ef4444 !important; }
        .swal2-cancel { background-color: rgba(255, 255, 255, 0.1) !important; color: #e2e8f0 !important; }

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
            width: max-content; min-width: 200px; max-width: 260px; white-space: normal; text-align: left; 
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
        
        .tooltip-fuchsia .tooltip-trigger { background-color: #d946ef; box-shadow: 0 0 10px rgba(217,70,239,0.5); }
        .tooltip-fuchsia .tooltip-trigger:hover { background-color: #e879f9; box-shadow: 0 0 15px rgba(217,70,239,0.8); }
        .tooltip-fuchsia .tooltip-content { border: 1px solid rgba(217,70,239,0.5); }
    </style>
</head>
<body class="h-screen w-full flex overflow-hidden selection:bg-indigo-500/30 selection:text-white" 
      x-data="{ 
          activeTab: 'overview', 
          showEdit: false, 
          searchLab: '', 
          searchQuiz: '',
          copied: false,
          showProgress: false,
          
          // State Modals untuk Insight
          showLessonModal: false,
          showLabModal: false,
          showQuizModal: false,
          showAvgLabModal: false,
          showAvgQuizModal: false,

          copyEmail(email) {
              navigator.clipboard.writeText(email);
              this.copied = true;
              setTimeout(() => this.copied = false, 2000);
          },
          confirmDelete() {
              Swal.fire({
                  title: 'Hapus Siswa?',
                  text: 'Tindakan ini tidak dapat dibatalkan. Semua data riwayat lab dan kuis akan ikut terhapus.',
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#ef4444',
                  cancelButtonColor: '#334155',
                  confirmButtonText: 'Ya, Hapus Permanen',
                  cancelButtonText: 'Batal',
                  reverseButtons: true
              }).then((result) => {
                  if (result.isConfirmed) {
                      document.getElementById('delete-student-form').submit();
                  }
              })
          }
      }"
      @keydown.escape.window="showLessonModal = false; showLabModal = false; showQuizModal = false; showAvgLabModal = false; showAvgQuizModal = false; showEdit = false;"
      x-init="
          setTimeout(() => showProgress = true, 200);
          $watch('activeTab', value => {
              if(value === 'overview') { showProgress = false; setTimeout(() => showProgress = true, 50); }
          });
      ">

    {{-- HELPER DATA BLADE (AMAN DARI ERROR) --}}
    @php
        function formatTime($seconds) {
            if ($seconds === null || $seconds === '') return '-';
            $m = floor($seconds / 60);
            $s = $seconds % 60;
            return ($m > 0 ? "{$m}m " : "") . "{$s}s";
        }
        
        $labHistories = isset($labHistories) ? collect($labHistories) : collect([]);
        $quizAttempts = isset($quizAttempts) ? collect($quizAttempts) : collect([]);
        $completedLessonIds = $completedLessonIds ?? [];
        
        // Peta Kurikulum Global (Digunakan untuk Modal & Tab Curriculum)
        $curriculumMap = [
            [
                'id' => 1, 'number' => '01', 'title' => 'PENDAHULUAN', 'color' => 'cyan',
                'lab_id' => 1, 'lab_name' => 'Setup Environment', 'quiz_key' => '1',
                'topics' => [
                    ['name' => '1.1 Konsep HTML & CSS', 'ids' => range(1, 6)],
                    ['name' => '1.2 Konsep Dasar Tailwind', 'ids' => range(7, 11)],
                    ['name' => '1.3 Latar Belakang & Struktur', 'ids' => range(12, 15)],
                    ['name' => '1.4 Implementasi pada HTML', 'ids' => range(16, 19)],
                    ['name' => '1.5 Keunggulan & Utilitas', 'ids' => range(20, 23)],
                    ['name' => '1.6 Instalasi & Konfigurasi', 'ids' => range(24, 28)],
                ]
            ],
            [
                'id' => 2, 'number' => '02', 'title' => 'LAYOUTING', 'color' => 'indigo',
                'lab_id' => 2, 'lab_name' => 'Building Grid Layout', 'quiz_key' => '2',
                'topics' => [
                    ['name' => '2.1 Flexbox Architecture', 'ids' => range(29, 33)],
                    ['name' => '2.2 Grid System Mastery', 'ids' => range(34, 40)],
                    ['name' => '2.3 Layout Management', 'ids' => range(41, 45)],
                ]
            ],
            [
                'id' => 3, 'number' => '03', 'title' => 'STYLING', 'color' => 'fuchsia',
                'lab_id' => 3, 'lab_name' => 'Styling Components', 'quiz_key' => '3',
                'topics' => [
                    ['name' => '3.1 Tipografi & Font', 'ids' => range(46, 51)],
                    ['name' => '3.2 Backgrounds', 'ids' => range(52, 55)],
                    ['name' => '3.3 Borders & Rings', 'ids' => range(56, 59)],
                    ['name' => '3.4 Effects & Filters', 'ids' => range(60, 65)],
                ]
            ]
        ];
    @endphp

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
                    <a href="{{ route('admin.dashboard') }}" class="nav-link active {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
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

    {{-- MAIN CONTENT --}}
    <main class="flex-1 flex flex-col relative h-full bg-[#020617] overflow-hidden">
        
        {{-- Background Aesthetics --}}
        <div class="fixed inset-0 pointer-events-none z-0">
            <div class="absolute top-[10%] left-[20%] w-[500px] h-[500px] bg-indigo-600/10 rounded-full blur-[120px]"></div>
            <div class="absolute bottom-[10%] right-[10%] w-[400px] h-[400px] bg-cyan-600/10 rounded-full blur-[120px]"></div>
            <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.04] mix-blend-overlay"></div>
        </div>

        {{-- HEADER PROFILE --}}
        {{-- HEADER RESPONSIVE & BREADCRUMB IDENTIK DASHBOARD --}}
        <header class="glass-header flex flex-col justify-end px-6 md:px-10 shrink-0 sticky top-0 z-40 pt-5">
            
            {{-- TOP ROW: Navigasi, Profil, dan Aksi --}}
            <div class="flex items-start justify-between w-full mb-3 md:mb-5">
                
                {{-- Kiri: Hamburger, Avatar, Info --}}
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = true" class="md:hidden p-2 bg-white/5 rounded-lg text-white hover:bg-white/10 transition mt-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    
                    <div class="flex items-center gap-4">
                        {{-- Avatar Siswa (Sebagai icon header khas halaman ini) --}}
                        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-indigo-500 to-fuchsia-600 p-[1px] shadow-lg hidden sm:block">
                            <div class="w-full h-full bg-[#0f141e] rounded-[11px] flex items-center justify-center font-black text-white text-base">
                                {{ substr($user->name ?? 'S', 0, 2) }}
                            </div>
                        </div>
                        
                        <div>
                            {{-- Breadcrumb Dinamis Sesuai Nama User --}}
                            <nav class="flex text-[10px] text-white/50 mb-1.5 font-bold hidden sm:flex" aria-label="Breadcrumb">
                                <ol class="inline-flex items-center space-x-1">
                                    <li class="inline-flex items-center"><a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-400 transition">Dashboard</a></li>
                                    {{-- <li>
                                        <div class="flex items-center">
                                            <svg class="w-3 h-3 text-white/30 mx-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                            <span class="text-white">Student Directory</span>
                                        </div>
                                    </li> --}}
                                    <li>
                                        <div class="flex items-center">
                                            <svg class="w-3 h-3 text-white/30 mx-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                            <span class="text-white">{{ $user->name ?? 'Detail Siswa' }}</span>
                                        </div>
                                    </li>
                                </ol>
                            </nav>
                            
                            {{-- Nama & Email --}}
                            <h2 class="text-white font-bold text-lg md:text-xl tracking-tight flex items-center gap-2 leading-none">
                                {{ $user->name ?? 'Student Profile' }}
                            </h2>
                            <div class="flex items-center gap-2 mt-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse shadow-[0_0_8px_#10b981]"></span>
                                <p class="text-[10px] text-white/40 font-mono">{{ $user->email ?? 'No email recorded' }}</p>
                                <span class="text-[9px] bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 px-2 py-0.5 rounded font-bold ml-2 uppercase tracking-widest hidden md:inline-block">
                                    {{ $user->class_group ?? 'No Class' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- Kanan: Aksi & Informasi Dasbor --}}
                <div class="flex items-center gap-3 sm:gap-6 mt-1">
                    
                    {{-- Progress Bar Ringkas (Hanya di layar besar) --}}
                    <div class="hidden xl:flex flex-col items-end mr-2">
                        <p class="text-[9px] uppercase font-extrabold text-slate-400 tracking-widest mb-1.5">Global Progress</p>
                        <div class="flex items-center gap-3">
                            <div class="w-24 h-1.5 bg-[#0f141e] rounded-full overflow-hidden border border-white/5 shadow-inner">
                                <div class="h-full bg-cyan-400 rounded-full" style="width: {{ $globalProgress ?? 0 }}%"></div>
                            </div>
                            <span class="text-xs font-black text-cyan-400">{{ $globalProgress ?? 0 }}%</span>
                        </div>
                    </div>

                    {{-- FITUR EXPORT SISWA SPESIFIK --}}
                    <div class="relative" x-data="{ exportOpen: false }">
                        <button @click="exportOpen = !exportOpen" @click.away="exportOpen = false" class="p-2.5 sm:px-4 sm:py-2.5 rounded-full sm:rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-white text-xs font-bold transition flex items-center gap-2 shadow-lg" title="Export Student Data">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span class="hidden sm:inline">Export</span>
                        </button>
                        
                        {{-- Dropdown Export --}}
                        <div x-show="exportOpen" class="absolute right-0 mt-2 w-48 bg-[#0f141e] border border-white/10 rounded-xl shadow-[0_15px_50px_rgba(0,0,0,0.9)] z-[9999] overflow-hidden" style="display: none;" x-transition>
                            <div class="px-4 py-2 border-b border-white/5 text-[9px] font-bold text-white/30 uppercase tracking-widest bg-[#0a0e17]">Pilih Format</div>
                            
                            {{-- Route export membawa ID Siswa spesifik --}}
                            <a href="{{ route('admin.student.export.csv', $user->id) }}" class="flex items-center gap-3 px-4 py-3.5 text-[11px] font-bold text-white hover:bg-white/5 transition border-b border-white/5">
                                <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg> 
                                Export CSV
                            </a>
                            <a href="{{ route('admin.student.export.pdf', $user->id) }}" target="_blank" class="flex items-center gap-3 px-4 py-3.5 text-[11px] font-bold text-white hover:bg-white/5 transition">
                                <svg class="w-4 h-4 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg> 
                                Print PDF
                            </a>
                        </div>
                    </div>

                    {{-- Tombol Edit Siswa --}}
                    <button @click="showEdit = true" class="p-2.5 rounded-full bg-indigo-500/10 hover:bg-indigo-500 text-indigo-400 hover:text-white border border-indigo-500/20 hover:border-indigo-500 transition-all shadow-lg active:scale-95" title="Edit Student">
                        <svg class="w-4 h-4 transition-transform group-hover:rotate-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    </button>

                    <div class="w-px h-6 bg-white/10 hidden sm:block"></div>

                    {{-- Tombol Default Dasbor --}}
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

            {{-- BOTTOM ROW: Tab Navigation Terintegrasi di Bawah Header --}}
            <div class="flex gap-6 md:gap-8 mt-2 overflow-x-auto custom-scrollbar w-full relative z-10">
                <button @click="activeTab = 'overview'" :class="activeTab === 'overview' ? 'active text-white' : 'text-slate-500'" class="tab-btn pb-3.5 text-[11px] font-bold uppercase tracking-wider flex items-center gap-2 whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" /></svg>
                    Overview
                </button>
                <button @click="activeTab = 'curriculum'" :class="activeTab === 'curriculum' ? 'active text-white' : 'text-slate-500'" class="tab-btn pb-3.5 text-[11px] font-bold uppercase tracking-wider flex items-center gap-2 whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Curriculum Tracker
                </button>
                <button @click="activeTab = 'history'" :class="activeTab === 'history' ? 'active text-white' : 'text-slate-500'" class="tab-btn pb-3.5 text-[11px] font-bold uppercase tracking-wider flex items-center gap-2 whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    History Log
                </button>
            </div>
        </header>

        {{-- CONTENT BODY --}}
        <div class="flex-1 overflow-y-auto custom-scrollbar p-6 md:p-8 z-10 scroll-smooth">
            <div class="max-w-7xl mx-auto pb-20 relative">

                {{-- ============================== --}}
                {{-- TAB 1: OVERVIEW --}}
                {{-- ============================== --}}
                <div x-show="activeTab === 'overview'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6" style="display: none;" x-cloak>
                    
                    {{-- STATS GRID (DENGAN HERO MODALS DAN TOOLTIP) --}}
                    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-5 gap-4">
                        
                        {{-- 1. Materi Dibaca --}}
                        <div class="glass-card p-5 rounded-2xl relative group hover:border-cyan-500/40 cursor-pointer" @click="showLessonModal = true">
                            <div class="flex justify-between items-start mb-3">
                                <p class="text-[10px] text-slate-400 uppercase font-bold tracking-widest group-hover:text-cyan-400 transition">Materi Dibaca</p>
                                <div class="flex items-center gap-2">
                                    <div class="tooltip-container tooltip-cyan tooltip-down tooltip-left">
                                        <div class="tooltip-trigger bg-transparent border-transparent shadow-none text-white/30 group-hover:text-cyan-400">?</div>
                                        <div class="tooltip-content border-cyan-glow">
                                            <span class="block font-bold text-cyan-400 mb-1 border-b border-white/10 pb-1">Total Materi</span>
                                            Jumlah slide materi teori yang telah dibaca dan diselesaikan oleh siswa ini. Klik untuk melihat rincian.
                                        </div>
                                    </div>
                                    <span class="p-1.5 rounded-lg bg-cyan-500/10 text-cyan-400 border border-cyan-500/10 shadow-inner group-hover:scale-110 transition duration-300">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-end gap-2">
                                <p class="text-3xl font-black text-white group-hover:text-cyan-400 transition" x-data="{ count: 0 }" x-init="let i = setInterval(() => { if(count < {{ isset($completedLessonIds) ? count($completedLessonIds) : 0 }}) count++; else clearInterval(i); }, 30);" x-text="count"></p>
                                <p class="text-xs font-bold text-slate-500 mb-1 font-mono">/ 65</p>
                            </div>
                            <div class="w-full h-1.5 bg-[#0f141e] rounded-full mt-4 overflow-hidden border border-white/5">
                                <div class="h-full bg-cyan-500 rounded-full shadow-[0_0_10px_currentColor] transition-all duration-[1500ms] ease-out" :style="showProgress ? 'width: {{ (isset($completedLessonIds) && count($completedLessonIds) > 0) ? (count($completedLessonIds) / 65) * 100 : 0 }}%' : 'width: 0%'"></div>
                            </div>
                        </div>

                        {{-- 2. Labs Passed --}}
                        <div class="glass-card p-5 rounded-2xl relative group hover:border-indigo-500/40 cursor-pointer" @click="showLabModal = true">
                            <div class="flex justify-between items-start mb-3">
                                <p class="text-[10px] text-slate-400 uppercase font-bold tracking-widest group-hover:text-indigo-400 transition">Labs Lulus</p>
                                <div class="flex items-center gap-2">
                                    <div class="tooltip-container tooltip-indigo tooltip-down tooltip-left">
                                        <div class="tooltip-trigger bg-transparent border-transparent shadow-none text-white/30 group-hover:text-indigo-400">?</div>
                                        <div class="tooltip-content border-indigo-glow">
                                            <span class="block font-bold text-indigo-400 mb-1 border-b border-white/10 pb-1">Lab Praktikum</span>
                                            Jumlah modul praktikum yang telah dikerjakan dan berhasil divalidasi Lulus. Klik untuk melihat daftar riwayat.
                                        </div>
                                    </div>
                                    <span class="p-1.5 rounded-lg bg-indigo-500/10 text-indigo-400 border border-indigo-500/10 shadow-inner group-hover:scale-110 transition duration-300">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-end gap-2">
                                <p class="text-3xl font-black text-white group-hover:text-indigo-400 transition drop-shadow-[0_0_8px_rgba(99,102,241,0.3)]">{{ $labStats['total'] ?? 0 }}</p>
                                <p class="text-xs font-bold text-slate-500 mb-1 font-mono">/ 4</p>
                            </div>
                            <div class="w-full h-1.5 bg-[#0f141e] rounded-full mt-4 overflow-hidden border border-white/5">
                                <div class="h-full bg-indigo-500 rounded-full shadow-[0_0_10px_currentColor] transition-all duration-[1500ms] ease-out" :style="showProgress ? 'width: {{ (($labStats['total'] ?? 0) / 4) * 100 }}%' : 'width: 0%'"></div>
                            </div>
                        </div>

                        {{-- 3. Quiz Lulus --}}
                        <div class="glass-card p-5 rounded-2xl relative group hover:border-fuchsia-500/40 cursor-pointer" @click="showQuizModal = true">
                            <div class="flex justify-between items-start mb-3">
                                <p class="text-[10px] text-slate-400 uppercase font-bold tracking-widest group-hover:text-fuchsia-400 transition">Quiz Lulus</p>
                                <div class="flex items-center gap-2">
                                    <div class="tooltip-container tooltip-fuchsia tooltip-down tooltip-left">
                                        <div class="tooltip-trigger bg-transparent border-transparent shadow-none text-white/30 group-hover:text-fuchsia-400">?</div>
                                        <div class="tooltip-content border-fuchsia-glow">
                                            <span class="block font-bold text-fuchsia-400 mb-1 border-b border-white/10 pb-1">Evaluasi Kuis</span>
                                            Jumlah evaluasi teori yang diselesaikan dengan skor minimal 70. Klik untuk melihat detail.
                                        </div>
                                    </div>
                                    <span class="p-1.5 rounded-lg bg-fuchsia-500/10 text-fuchsia-400 border border-fuchsia-500/10 shadow-inner group-hover:scale-110 transition duration-300">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-end gap-2">
                                <p class="text-3xl font-black text-white group-hover:text-fuchsia-400 transition drop-shadow-[0_0_8px_rgba(217,70,239,0.3)]">{{ $quizStats['total'] ?? 0 }}</p>
                                <p class="text-xs font-bold text-slate-500 mb-1 font-mono">/ 4</p>
                            </div>
                            <div class="w-full h-1.5 bg-[#0f141e] rounded-full mt-4 overflow-hidden border border-white/5">
                                <div class="h-full bg-fuchsia-500 rounded-full shadow-[0_0_10px_currentColor] transition-all duration-[1500ms] ease-out" :style="showProgress ? 'width: {{ (($quizStats['total'] ?? 0) / 4) * 100 }}%' : 'width: 0%'"></div>
                            </div>
                        </div>

                        {{-- 4. Avg Lab Score --}}
                        <div class="glass-card p-5 rounded-2xl relative group hover:border-emerald-500/40 cursor-pointer" @click="showAvgLabModal = true">
                            <div class="flex justify-between items-start mb-3">
                                <p class="text-[10px] text-slate-400 uppercase font-bold tracking-widest group-hover:text-emerald-400 transition">Rata Rata nilai Labs</p>
                                <div class="flex items-center gap-2">
                                    <div class="tooltip-container tooltip-emerald tooltip-down tooltip-left">
                                        <div class="tooltip-trigger bg-transparent border-transparent shadow-none text-white/30 group-hover:text-emerald-400">?</div>
                                        <div class="tooltip-content border-emerald-glow">
                                            <span class="block font-bold text-emerald-400 mb-1 border-b border-white/10 pb-1">Average Lab</span>
                                            Nilai rata-rata dari seluruh modul praktikum lab yang dikerjakan. Klik untuk melihat.
                                        </div>
                                    </div>
                                    <span class="p-1.5 rounded-lg bg-emerald-500/10 text-emerald-400 border border-emerald-500/10 shadow-inner group-hover:scale-110 transition duration-300">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" /></svg>
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-end gap-2">
                                <p class="text-3xl font-black text-white group-hover:text-emerald-400 transition drop-shadow-[0_0_8px_rgba(16,185,129,0.3)]">{{ number_format($labStats['avg_score'] ?? 0, 1) }}</p>
                                <p class="text-xs font-bold text-slate-500 mb-1 font-mono">/ 100</p>
                            </div>
                            <div class="w-full h-1.5 bg-[#0f141e] rounded-full mt-4 overflow-hidden border border-white/5">
                                <div class="h-full bg-emerald-500 rounded-full shadow-[0_0_10px_currentColor] transition-all duration-[1500ms] ease-out" :style="showProgress ? 'width: {{ $labStats['avg_score'] ?? 0 }}%' : 'width: 0%'"></div>
                            </div>
                        </div>

                        {{-- 5. Avg Quiz Score --}}
                        <div class="glass-card p-5 rounded-2xl relative group hover:border-amber-500/40 cursor-pointer" @click="showAvgQuizModal = true">
                            <div class="flex justify-between items-start mb-3">
                                <p class="text-[10px] text-slate-400 uppercase font-bold tracking-widest group-hover:text-amber-400 transition">Rata Rata nilai Kuis</p>
                                <div class="flex items-center gap-2">
                                    <div class="tooltip-container tooltip-yellow tooltip-down tooltip-left">
                                        <div class="tooltip-trigger bg-transparent border-transparent shadow-none text-white/30 group-hover:text-yellow-400">?</div>
                                        <div class="tooltip-content border-yellow-glow">
                                            <span class="block font-bold text-yellow-400 mb-1 border-b border-white/10 pb-1">Average Quiz</span>
                                            Nilai rata-rata dari seluruh evaluasi kuis teori yang dikerjakan. Klik untuk melihat.
                                        </div>
                                    </div>
                                    <span class="p-1.5 rounded-lg bg-amber-500/10 text-amber-400 border border-amber-500/10 shadow-inner group-hover:scale-110 transition duration-300">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-end gap-2">
                                <p class="text-3xl font-black text-white group-hover:text-amber-400 transition drop-shadow-[0_0_8px_rgba(245,158,11,0.3)]">{{ number_format($quizStats['avg_score'] ?? 0, 1) }}</p>
                                <p class="text-xs font-bold text-slate-500 mb-1 font-mono">/ 100</p>
                            </div>
                            <div class="w-full h-1.5 bg-[#0f141e] rounded-full mt-4 overflow-hidden border border-white/5">
                                <div class="h-full bg-amber-500 rounded-full shadow-[0_0_10px_currentColor] transition-all duration-[1500ms] ease-out" :style="showProgress ? 'width: {{ $quizStats['avg_score'] ?? 0 }}%' : 'width: 0%'"></div>
                            </div>
                        </div>
                    </div>

                    {{-- DETAIL CHART & SUMMARY --}}
                    <div class="grid lg:grid-cols-3 gap-6">
                        <div class="glass-card rounded-2xl p-6 flex flex-col">
                            <h3 class="text-sm font-black text-white mb-5 tracking-wide">Status Kelulusan</h3>
                            <div class="flex-1 flex flex-col justify-center space-y-4">
                                <div class="bg-gradient-to-r from-white/[0.03] to-transparent border border-white/5 rounded-xl p-4 flex items-center justify-between hover:border-white/10 transition group/status cursor-default">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-indigo-500/20 border border-indigo-500/20 flex items-center justify-center text-indigo-400 text-lg shadow-inner group-hover/status:scale-110 transition">⚡</div>
                                        <div>
                                            <p class="text-xs font-bold text-white">Praktik (Labs)</p>
                                            <p class="text-[10px] text-slate-400 mt-0.5">Penyelesaian Modul</p>
                                        </div>
                                    </div>
                                    <span class="text-sm font-black {{ ($labStats['total'] ?? 0) >= 4 ? 'text-emerald-400' : 'text-slate-300' }}">{{ $labStats['total'] ?? 0 }}/4</span>
                                </div>
                                <div class="bg-gradient-to-r from-white/[0.03] to-transparent border border-white/5 rounded-xl p-4 flex items-center justify-between hover:border-white/10 transition group/status cursor-default">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-fuchsia-500/20 border border-fuchsia-500/20 flex items-center justify-center text-fuchsia-400 text-lg shadow-inner group-hover/status:scale-110 transition">📝</div>
                                        <div>
                                            <p class="text-xs font-bold text-white">Teori (Quizzes)</p>
                                            <p class="text-[10px] text-slate-400 mt-0.5">Evaluasi Pemahaman</p>
                                        </div>
                                    </div>
                                    <span class="text-sm font-black {{ ($quizStats['total'] ?? 0) >= 4 ? 'text-emerald-400' : 'text-slate-300' }}">{{ $quizStats['total'] ?? 0 }}/4</span>
                                </div>
                                <div class="bg-gradient-to-r from-cyan-500/5 to-transparent border border-cyan-500/20 rounded-xl p-4 flex items-center justify-between hover:border-cyan-500/30 transition group/status cursor-default">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-cyan-500/20 border border-cyan-500/30 flex items-center justify-center text-cyan-400 text-lg shadow-[0_0_15px_rgba(34,211,238,0.2)] group-hover/status:rotate-12 transition">🏆</div>
                                        <div>
                                            <p class="text-xs font-bold text-white">Tingkat Akhir</p>
                                            <p class="text-[10px] text-cyan-200/60 mt-0.5">Global Progress</p>
                                        </div>
                                    </div>
                                    <span class="text-lg font-black text-cyan-400 drop-shadow-[0_0_5px_rgba(34,211,238,0.5)]">{{ $globalProgress ?? 0 }}%</span>
                                </div>
                            </div>
                        </div>

                        <div class="lg:col-span-2 glass-card rounded-2xl p-6 relative flex flex-col">
                            <div class="flex justify-between items-center mb-6">
                                <div>
                                    <h3 class="text-sm font-black text-white tracking-wide">Tren Performa Lab</h3>
                                    <p class="text-xs text-slate-400 mt-1">10 percobaan praktik terakhir yang berstatus lulus</p>
                                </div>
                                <span class="px-3 py-1.5 rounded-lg bg-indigo-500/10 text-indigo-400 text-[10px] font-bold border border-indigo-500/20 flex items-center gap-1.5 shadow-inner">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
                                    Grafik Nilai
                                </span>
                            </div>
                            <div class="flex-1 min-h-[250px] w-full relative">
                                @if(isset($chartScores) && count($chartScores) > 0)
                                    <canvas id="scoreChart"></canvas>
                                @else
                                    <div class="absolute inset-0 flex flex-col items-center justify-center border-2 border-dashed border-white/5 rounded-xl bg-white/[0.01]">
                                        <svg class="w-8 h-8 text-slate-600 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                                        <p class="text-xs font-semibold text-slate-400">Belum Ada Data Grafik</p>
                                        <p class="text-[10px] text-slate-500 mt-1">Siswa belum menyelesaikan praktik lab dengan status lulus.</p>
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
                                if(!$quizPass) $allChaptersPassed = false;

                                $totalLessonIds = 0;
                                $completedLessonCount = 0;
                                foreach($chapter['topics'] as $t) {
                                    $totalLessonIds += count($t['ids']);
                                    $completedLessonCount += count(array_intersect($t['ids'], $completedLessonIds ?? []));
                                }
                                
                                $totalWeight = $totalLessonIds + 20; 
                                $currentWeight = $completedLessonCount;
                                if($labDone) $currentWeight += 10;
                                if($quizPass) $currentWeight += 10;

                                $chapterPercent = round(($currentWeight / $totalWeight) * 100);
                                if($chapterPercent > 100) $chapterPercent = 100;
                            @endphp

                            <div class="glass-card rounded-2xl overflow-hidden flex flex-col relative group h-full hover:border-{{ $chapter['color'] }}-500/40" style="animation-delay: {{ $index * 100 }}ms">
                                <div class="absolute top-0 left-0 h-1.5 bg-{{ $chapter['color'] }}-500 transition-all duration-1000 shadow-[0_0_10px_currentColor]" :style="showProgress ? 'width: {{ $chapterPercent }}%' : 'width: 0%'"></div>
                                
                                <div class="px-6 py-5 border-b border-white/5 bg-white/[0.01] flex justify-between items-center group-hover:bg-{{ $chapter['color'] }}-500/5 transition">
                                    <div class="flex items-center gap-3">
                                        <span class="text-[10px] font-black px-2.5 py-1 rounded bg-{{ $chapter['color'] }}-500/10 text-{{ $chapter['color'] }}-400 border border-{{ $chapter['color'] }}-500/20 shadow-inner">BAB {{ $chapter['number'] }}</span>
                                        <h4 class="text-sm font-bold text-white flex items-center gap-2">
                                            {{ $chapter['title'] }}
                                            @if($chapterPercent == 100)
                                                <svg class="w-4 h-4 text-emerald-400 drop-shadow-[0_0_5px_#10b981]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            @endif
                                        </h4>
                                    </div>
                                    <span class="text-xs font-mono font-bold text-{{ $chapter['color'] }}-400" x-data="{ p: 0 }" x-init="let i = setInterval(() => { if(p < {{ $chapterPercent }}) p++; else clearInterval(i); }, 20);" x-text="p + '%'"></span>
                                </div>

                                <div class="p-6 flex-1 flex flex-col gap-6">
                                    <div class="space-y-4 relative">
                                        <div class="absolute left-[7px] top-2 bottom-2 w-px border-l-2 border-dashed border-white/10 group-hover:border-{{ $chapter['color'] }}-500/20 transition"></div>
                                        
                                        @foreach($chapter['topics'] as $topic)
                                            @php 
                                                $missingIds = array_diff($topic['ids'], $completedLessonIds ?? []);
                                                $isTopicDone = empty($missingIds);
                                                $partial = count($topic['ids']) - count($missingIds);
                                                $total = count($topic['ids']);
                                                $progressW = ($partial/$total)*100;
                                            @endphp
                                            <div class="relative pl-6 flex items-center justify-between group/item hover:bg-white/[0.02] p-1.5 -ml-1.5 rounded-lg transition cursor-default">
                                                <div class="flex items-center gap-3">
                                                    <div class="absolute left-[3.5px] top-3 w-2.5 h-2.5 rounded-full border-[2px] border-[#0f141e] {{ $isTopicDone ? 'bg-emerald-500 shadow-[0_0_8px_#10b981]' : 'bg-slate-700 group-hover/item:bg-slate-500' }} transition-colors duration-300"></div>
                                                    <div class="flex flex-col">
                                                        <span class="text-[13px] font-semibold transition-colors duration-300 {{ $isTopicDone ? 'text-white' : 'text-slate-400 group-hover/item:text-slate-300' }}">{{ $topic['name'] }}</span>
                                                        <div class="flex items-center gap-2 mt-0.5">
                                                            <div class="w-16 h-1 bg-white/10 rounded-full overflow-hidden">
                                                                <div class="h-full bg-slate-500 rounded-full {{ $isTopicDone ? 'bg-emerald-400' : '' }} transition-all duration-1000" :style="showProgress ? 'width: {{ $progressW }}%' : 'width: 0%'"></div>
                                                            </div>
                                                            <span class="text-[9px] font-mono {{ $isTopicDone ? 'text-emerald-500/70' : 'text-slate-500' }}">{{ $partial }}/{{ $total }} slide</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if($isTopicDone) 
                                                    <span class="text-[9px] font-bold text-emerald-400 bg-emerald-500/10 px-1.5 py-0.5 rounded uppercase border border-emerald-500/20">Done</span> 
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="mt-auto space-y-3 pt-5 border-t border-white/5">
                                        <div class="flex items-center justify-between p-2 rounded-lg bg-white/[0.02] hover:bg-white/[0.04] transition group/milestone cursor-pointer">
                                            <div class="flex items-center gap-2.5 text-xs text-slate-300 font-medium group-hover/milestone:text-white transition">
                                                <div class="w-6 h-6 rounded bg-{{ $chapter['color'] }}-500/20 flex items-center justify-center text-{{ $chapter['color'] }}-400 text-xs shadow-inner group-hover/milestone:scale-110 transition">⚡</div>
                                                {{ $chapter['lab_name'] }}
                                            </div>
                                            <span class="text-[10px] font-black px-2 py-0.5 rounded {{ $labDone ? 'text-emerald-400 bg-emerald-500/10 border border-emerald-500/20' : 'text-slate-500 bg-slate-800/50' }}">
                                                {{ $labDone ? 'LULUS' : 'PENDING' }}
                                            </span>
                                        </div>
                                        <div class="flex items-center justify-between p-2 rounded-lg bg-white/[0.02] hover:bg-white/[0.04] transition group/milestone cursor-pointer">
                                            <div class="flex items-center gap-2.5 text-xs text-slate-300 font-medium group-hover/milestone:text-white transition">
                                                <div class="w-6 h-6 rounded bg-{{ $chapter['color'] }}-500/20 flex items-center justify-center text-{{ $chapter['color'] }}-400 text-xs shadow-inner group-hover/milestone:scale-110 transition">📝</div>
                                                Evaluasi Bab
                                            </div>
                                            <span class="text-[10px] font-black px-2 py-0.5 rounded {{ $quizPass ? 'text-fuchsia-400 bg-fuchsia-500/10 border border-fuchsia-500/20' : 'text-slate-500 bg-slate-800/50' }}">
                                                {{ $quizScore !== null ? 'SKOR: '.$quizScore : 'BELUM' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- CAPSTONE & FINAL --}}
                    @php
                        $capstoneLabId = 4;
                        $finalQuizId = '99';
                        $isCapstoneDone = in_array($capstoneLabId, $passedLabIds ?? []);
                        $finalQuizScore = $quizScoresMap['quiz_'.$finalQuizId] ?? null;
                        $isFinalDone = ($finalQuizScore !== null && $finalQuizScore >= 70);
                        
                        $isCapstoneLocked = !($allChaptersPassed ?? true);
                        $isFinalLocked = !$isCapstoneDone;
                    @endphp

                    <div class="mt-12 pt-8 border-t border-white/10 relative">
                        <div class="absolute -top-3 left-1/2 -translate-x-1/2 bg-[#020617] px-5 py-1 text-xs font-black text-amber-500 uppercase tracking-widest border border-amber-500/30 rounded-full shadow-[0_0_15px_rgba(245,158,11,0.15)] flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                            Final Phase
                        </div>
                        
                        <div class="grid md:grid-cols-2 gap-6 mt-4">
                            <div class="glass-card rounded-2xl p-6 relative overflow-hidden flex items-center gap-5 {{ $isCapstoneLocked ? 'opacity-60 grayscale hover:grayscale-0 transition-all duration-500' : 'hover:border-amber-500/40 hover:-translate-y-1' }}">
                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-500/20 to-orange-600/20 border border-amber-500/30 flex items-center justify-center text-2xl shadow-[inset_0_0_10px_rgba(245,158,11,0.2)]">🏆</div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-1">
                                        <h4 class="text-sm font-black text-white uppercase tracking-wide {{ !$isCapstoneLocked ? 'text-transparent bg-clip-text bg-gradient-to-r from-amber-200 to-amber-500' : '' }}">Capstone Project</h4>
                                        @if($isCapstoneLocked) <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg> @endif
                                    </div>
                                    <p class="text-xs text-slate-400 mb-3 font-medium">DevStudio Landing Page</p>
                                    <span class="text-[10px] font-extrabold px-2.5 py-1 rounded-md tracking-wider shadow-sm {{ $isCapstoneDone ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/20' : ($isCapstoneLocked ? 'bg-white/5 text-white/30 border border-white/5' : 'bg-amber-500/20 text-amber-400 border border-amber-500/20 animate-pulse') }}">
                                        {{ $isCapstoneDone ? 'COMPLETED' : ($isCapstoneLocked ? 'LOCKED' : 'IN PROGRESS') }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="glass-card rounded-2xl p-6 relative overflow-hidden flex items-center gap-5 {{ $isFinalLocked ? 'opacity-60 grayscale hover:grayscale-0 transition-all duration-500' : 'hover:border-indigo-500/40 hover:-translate-y-1' }}">
                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500/20 to-cyan-500/20 border border-indigo-500/30 flex items-center justify-center text-2xl shadow-[inset_0_0_10px_rgba(99,102,241,0.2)]">🎓</div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-1">
                                        <h4 class="text-sm font-black text-white uppercase tracking-wide">Final Evaluation</h4>
                                        @if($isFinalLocked) <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg> @endif
                                    </div>
                                    <p class="text-xs text-slate-400 mb-3 font-medium">Ujian Teori Komprehensif</p>
                                    <span class="text-[10px] font-extrabold px-2.5 py-1 rounded-md tracking-wider shadow-sm {{ $isFinalDone ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/20' : ($isFinalLocked ? 'bg-white/5 text-white/30 border border-white/5' : 'bg-indigo-500/20 text-indigo-400 border border-indigo-500/20 animate-pulse') }}">
                                        {{ $isFinalDone ? 'PASSED (SKOR: '.$finalQuizScore.')' : ($isFinalLocked ? 'LOCKED' : 'READY TO TAKE') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ============================== --}}
                {{-- TAB 3: HISTORY LOG --}}
                {{-- ============================== --}}
                <div x-show="activeTab === 'history'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-8" style="display: none;" x-cloak>
                    
                    {{-- Lab History --}}
                    <div class="glass-card rounded-2xl overflow-hidden shadow-2xl">
                        <div class="p-4 md:p-6 border-b border-white/5 bg-gradient-to-r from-white/[0.04] to-transparent flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                            <div class="flex items-center gap-3">
                                <div class="p-2 rounded-lg bg-indigo-500/20 text-indigo-400"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg></div>
                                <div>
                                    <h3 class="text-sm font-black text-white uppercase tracking-widest">Riwayat Praktik Lab</h3>
                                    <p class="text-[10px] text-slate-400 mt-0.5">Log pengerjaan modul code</p>
                                </div>
                            </div>
                            <div class="relative w-full md:w-auto">
                                <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                <input x-model="searchLab" type="text" placeholder="Cari nama lab, status..." class="glass-input pl-9 pr-4 py-2 text-xs rounded-lg w-full md:w-56 focus:w-64 transition-all duration-300">
                            </div>
                        </div>
                        <div class="overflow-x-auto max-h-[500px] custom-scrollbar bg-black/20">
                            <table class="w-full text-sm text-left whitespace-nowrap">
                                <thead class="bg-[#0f141e]/90 backdrop-blur-md text-white/40 text-[10px] uppercase font-bold sticky top-0 z-10 shadow-sm border-b border-white/5">
                                    <tr>
                                        <th class="px-6 py-4 tracking-wider">Lab Name</th>
                                        <th class="px-6 py-4 tracking-wider text-center">Status</th>
                                        <th class="px-6 py-4 tracking-wider text-center">Score</th>
                                        <th class="px-6 py-4 tracking-wider">Waktu</th>
                                        <th class="px-6 py-4 tracking-wider">Date & Time</th>
                                        <th class="px-6 py-4 tracking-wider text-right">Aksi Code</th>
                                    </tr>
                                </thead>
                                
                                @forelse($labHistories as $h)
                                <tbody x-data="{ expanded: false }" 
                                       class="text-slate-300 border-b border-white/5 last:border-0"
                                       x-show="searchLab === '' || '{{ addslashes(strtolower($h->lab->title ?? 'Lab #'.$h->lab_id)) }}'.includes(searchLab.toLowerCase()) || '{{ strtolower($h->status) }}'.includes(searchLab.toLowerCase())">
                                    
                                    <tr class="table-row group transition-all duration-300" :class="expanded ? 'bg-white/[0.02]' : ''">
                                        <td class="px-6 py-4">
                                            <span class="block text-white font-semibold transition group-hover:text-indigo-300">{{ $h->lab->title ?? 'Lab #'.$h->lab_id }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="px-2.5 py-1 rounded text-[10px] font-black uppercase tracking-wider {{ $h->status == 'passed' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-red-500/10 text-red-400 border border-red-500/20' }}">
                                                {{ $h->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-white font-black text-center">{{ $h->final_score }}</td>
                                        <td class="px-6 py-4 text-slate-400 text-xs font-mono">
                                            <div class="flex items-center gap-1.5">
                                                <svg class="w-3 h-3 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                {{ formatTime($h->duration_seconds) }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-slate-500 text-xs font-mono group-hover:text-slate-400 transition">{{ \Carbon\Carbon::parse($h->created_at)->format('d M Y, H:i') }}</td>
                                        <td class="px-6 py-4 text-right">
                                            @if($h->last_code_snapshot != null && $h->last_code_snapshot != '')
                                                <button @click="expanded = !expanded" class="px-3 py-1.5 rounded-lg bg-cyan-500/10 text-cyan-400 hover:bg-cyan-500 hover:text-white transition text-[10px] font-bold tracking-wide border border-cyan-500/20 hover:border-cyan-500 inline-flex items-center gap-1.5 ml-auto outline-none">
                                                    <svg class="w-3 h-3 transition-transform duration-300" :class="expanded ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                                    <span x-text="expanded ? 'Tutup Kode' : 'Lihat Kode'"></span>
                                                </button>
                                            @else
                                                <span class="text-[10px] text-slate-500 italic px-2 py-1 bg-slate-800/30 rounded">No Snapshot</span>
                                            @endif
                                        </td>
                                    </tr>

                                    @if($h->last_code_snapshot != null && $h->last_code_snapshot != '')
                                    <tr x-show="expanded" x-cloak class="bg-[#05080f] border-b-2 border-indigo-500/30 shadow-inner">
                                        <td colspan="6" class="p-0">
                                            <div x-show="expanded" x-collapse>
                                                <div class="p-6 md:p-8 bg-gradient-to-b from-[#0a0d14] to-transparent relative">
                                                    <div class="flex items-center justify-between mb-4">
                                                        <div class="flex items-center gap-2">
                                                            <div class="w-2 h-2 rounded-full bg-cyan-500 animate-pulse"></div>
                                                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                                                Snapshot Code Siswa <span class="text-white/30 lowercase font-normal ml-1">(Pekerjaan Terakhir)</span>
                                                            </p>
                                                        </div>
                                                        <button @click="navigator.clipboard.writeText($refs.codeBlock.innerText); Swal.fire({toast: true, position: 'top-end', icon: 'success', title: 'Kode disalin!', showConfirmButton: false, timer: 1500, background: '#0f141e', color: '#fff'})" class="text-[10px] bg-white/5 hover:bg-white/10 text-white font-bold px-3 py-1.5 rounded border border-white/10 transition flex items-center gap-1.5">
                                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                                            Copy Code
                                                        </button>
                                                    </div>
                                                    <div class="rounded-xl overflow-hidden border border-white/10 shadow-2xl bg-[#0d1117]">
                                                        <div class="bg-[#1e2330] px-4 py-2.5 border-b border-white/5 flex gap-1.5 items-center">
                                                            <div class="w-2.5 h-2.5 rounded-full bg-red-500/80"></div>
                                                            <div class="w-2.5 h-2.5 rounded-full bg-amber-500/80"></div>
                                                            <div class="w-2.5 h-2.5 rounded-full bg-emerald-500/80"></div>
                                                            <span class="ml-3 text-[10px] text-white/30 font-mono">index.html</span>
                                                        </div>
                                                        <div class="p-5 max-h-[400px] overflow-y-auto custom-scrollbar">
                                                            <pre class="text-cyan-50 text-xs font-mono leading-relaxed" x-ref="codeBlock"><code>{{ $h->last_code_snapshot }}</code></pre>
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
                                    <tr><td colspan="6" class="px-6 py-12 text-center text-slate-500 font-medium">Belum ada aktivitas penyelesaian lab.</td></tr>
                                </tbody>
                                @endforelse
                                
                                <tbody x-show="searchLab !== '' && !document.querySelector('tbody[x-show]:not([style*=\'display: none\'])')" style="display: none;">
                                    <tr><td colspan="6" class="px-6 py-8 text-center text-slate-500 text-xs">Pencarian tidak ditemukan.</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Quiz History --}}
                    <div class="glass-card rounded-2xl overflow-hidden shadow-2xl mt-8">
                        <div class="p-4 md:p-6 border-b border-white/5 bg-gradient-to-r from-white/[0.04] to-transparent flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                            <div class="flex items-center gap-3">
                                <div class="p-2 rounded-lg bg-fuchsia-500/20 text-fuchsia-400"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg></div>
                                <div>
                                    <h3 class="text-sm font-black text-white uppercase tracking-widest">Riwayat Kuis</h3>
                                    <p class="text-[10px] text-slate-400 mt-0.5">Filter hasil evaluasi teori</p>
                                </div>
                            </div>
                            <div class="relative w-full md:w-auto">
                                <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                <input x-model="searchQuiz" type="text" placeholder="Cari nama kuis..." class="glass-input pl-9 pr-4 py-2 text-xs rounded-lg w-full md:w-56 focus:w-64 transition-all duration-300">
                            </div>
                        </div>
                        <div class="overflow-x-auto max-h-[400px] custom-scrollbar bg-black/20">
                            <table class="w-full text-sm text-left whitespace-nowrap">
                                <thead class="bg-[#0f141e]/90 backdrop-blur-md text-white/40 text-[10px] uppercase font-bold sticky top-0 z-10 shadow-sm border-b border-white/5">
                                    <tr>
                                        <th class="px-6 py-4 tracking-wider">Quiz Name</th>
                                        <th class="px-6 py-4 tracking-wider text-center">Status</th>
                                        <th class="px-6 py-4 tracking-wider text-center">Score</th>
                                        <th class="px-6 py-4 tracking-wider">Waktu Pengerjaan</th>
                                        <th class="px-6 py-4 tracking-wider text-right">Date & Time</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/5 text-slate-300">
                                    @forelse($quizAttempts as $q)
                                    @php $qName = $q->chapter_id == '99' ? 'Final Evaluation' : 'Evaluasi Bab '.$q->chapter_id; @endphp
                                    <tr class="table-row group transition-all duration-300"
                                        x-show="searchQuiz === '' || '{{ addslashes(strtolower($qName)) }}'.includes(searchQuiz.toLowerCase()) || '{{ $q->score >= 70 ? 'lulus' : 'gagal' }}'.includes(searchQuiz.toLowerCase())">
                                        <td class="px-6 py-4 text-white font-semibold transition group-hover:text-fuchsia-300">
                                            {{ $qName }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="px-2.5 py-1 rounded text-[10px] font-black uppercase tracking-wider {{ $q->score >= 70 ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-red-500/10 text-red-400 border border-red-500/20' }}">
                                                {{ $q->score >= 70 ? 'Lulus' : 'Gagal' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-white font-black text-center">{{ $q->score }}</td>
                                        <td class="px-6 py-4 text-slate-400 text-xs font-mono">
                                            <div class="flex items-center gap-1.5">
                                                <svg class="w-3 h-3 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                {{ formatTime($q->time_spent_seconds) }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-right text-slate-500 text-xs font-mono group-hover:text-slate-400 transition">{{ \Carbon\Carbon::parse($q->created_at)->format('d M Y, H:i') }}</td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="5" class="px-6 py-12 text-center text-slate-500 font-medium">Belum ada data pengambilan kuis.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>

    {{-- ==================== HERO MODALS (INSIGHT DATA OVERVIEW) ==================== --}}

    {{-- 1. Modal: Materi Dibaca (Lesson Breakdown Scrollable) --}}
    <div x-show="showLessonModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-[#020617]/95 backdrop-blur-md" @click="showLessonModal = false"></div>
        <div class="relative w-full max-w-2xl bg-[#0f141e] border border-cyan-500/40 rounded-3xl shadow-[0_30px_100px_rgba(6,182,212,0.15)] p-6 md:p-8 flex flex-col max-h-[90vh]" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
            <div class="flex justify-between items-center mb-6 border-b border-white/5 pb-4 shrink-0">
                <div>
                    <h3 class="text-lg md:text-xl font-bold text-white flex items-center gap-2">
                        <svg class="w-6 h-6 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        Materi Teori Diselesaikan
                    </h3>
                    <p class="text-[10px] text-cyan-400 mt-1 font-mono uppercase tracking-widest">Detail Modul Bacaan Siswa</p>
                </div>
                <button @click="showLessonModal = false" class="text-white/40 hover:text-white transition bg-white/5 hover:bg-red-500/20 p-2 rounded-full border border-transparent hover:border-red-500/30"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            
            <div class="flex-1 overflow-y-auto custom-scrollbar pr-2 space-y-4">
                @forelse($curriculumMap as $chapter)
                    <div class="bg-[#0a0e17]/80 rounded-2xl border border-white/5 overflow-hidden">
                        <div class="px-5 py-3 border-b border-white/5 bg-{{ $chapter['color'] }}-500/10 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="text-[10px] font-black text-{{ $chapter['color'] }}-400 border border-{{ $chapter['color'] }}-500/20 px-2.5 py-1 rounded shadow-inner bg-[#020617]">BAB {{ $chapter['number'] }}</span>
                                <span class="text-xs font-bold text-white">{{ $chapter['title'] }}</span>
                            </div>
                        </div>
                        <div class="p-3 space-y-1.5 bg-white/[0.01]">
                            @foreach($chapter['topics'] as $topic)
                                @php
                                    $completedInTopic = array_intersect($topic['ids'], $completedLessonIds ?? []);
                                    $totalInTopic = count($topic['ids']);
                                    $isComplete = count($completedInTopic) == $totalInTopic;
                                    $hasProgress = count($completedInTopic) > 0;
                                @endphp
                                
                                @if($hasProgress)
                                <div class="flex items-center justify-between p-2.5 rounded-lg hover:bg-white/5 transition border border-transparent hover:border-white/5">
                                    <div class="flex items-center gap-3">
                                        @if($isComplete)
                                            <svg class="w-4 h-4 text-emerald-400 drop-shadow-[0_0_5px_#10b981]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        @else
                                            <svg class="w-4 h-4 text-yellow-400 drop-shadow-[0_0_5px_#eab308]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        @endif
                                        <span class="text-[11px] font-medium text-white/80">{{ $topic['name'] }}</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="w-16 h-1 bg-white/10 rounded-full overflow-hidden hidden sm:block">
                                            <div class="h-full {{ $isComplete ? 'bg-emerald-400' : 'bg-yellow-400' }}" style="width: {{ ($totalInTopic > 0 ? count($completedInTopic)/$totalInTopic : 0) * 100 }}%"></div>
                                        </div>
                                        <span class="text-[9px] font-mono font-bold {{ $isComplete ? 'text-emerald-400' : 'text-yellow-400' }}">{{ count($completedInTopic) }}/{{ $totalInTopic }} slides</span>
                                    </div>
                                </div>
                                @endif
                            @endforeach
                            
                            @php
                                $topicHasProgress = false;
                                foreach($chapter['topics'] as $t) {
                                    if(count(array_intersect($t['ids'], $completedLessonIds ?? [])) > 0) $topicHasProgress = true;
                                }
                            @endphp
                            
                            @if(!$topicHasProgress)
                                <p class="text-[10px] text-white/30 italic text-center py-2">Belum ada materi dibaca di bab ini.</p>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-white/40 text-center py-10">Data kurikulum tidak ditemukan.</p>
                @endforelse
            </div>
            
            <div class="mt-6 pt-4 border-t border-white/5 text-center shrink-0">
                <p class="text-[10px] text-cyan-400/50 font-mono">Total Keseluruhan: <span class="text-cyan-400 font-bold">{{ count($completedLessonIds ?? []) }}</span> dari 65 Materi</p>
            </div>
        </div>
    </div>

    {{-- 2. Modal: Labs Passed --}}
    <div x-show="showLabModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-[#020617]/95 backdrop-blur-md" @click="showLabModal = false"></div>
        <div class="relative w-full max-w-2xl bg-[#0f141e] border border-indigo-500/40 rounded-3xl shadow-[0_30px_100px_rgba(99,102,241,0.15)] p-6 md:p-8" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
            <div class="flex justify-between items-center mb-6 border-b border-white/5 pb-4">
                <div>
                    <h3 class="text-lg md:text-xl font-bold text-white flex items-center gap-2">
                        <svg class="w-6 h-6 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        Praktikum Lulus
                    </h3>
                    <p class="text-[10px] text-indigo-400 mt-1 font-mono uppercase tracking-widest">Daftar Modul Lab yang Lulus</p>
                </div>
                <button @click="showLabModal = false" class="text-white/40 hover:text-white transition bg-white/5 hover:bg-red-500/20 p-2 rounded-full border border-transparent hover:border-red-500/30"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            <div class="max-h-[60vh] overflow-y-auto custom-scrollbar space-y-3 pr-2">
                @forelse($labHistories->where('status', 'passed') as $h)
                <div class="flex items-center justify-between gap-4 p-4 rounded-xl bg-[#0a0e17]/80 border border-white/5 hover:border-indigo-500/30 transition group shadow-inner">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-white truncate group-hover:text-indigo-300 transition">{{ $h->lab->title ?? 'Lab #'.$h->lab_id }}</p>
                        <p class="text-[10px] text-white/50 font-mono mt-1">{{ \Carbon\Carbon::parse($h->created_at)->format('d M Y, H:i') }}</p>
                    </div>
                    <div class="text-right shrink-0">
                        <span class="text-lg font-black text-emerald-400">{{ $h->final_score }}</span>
                        <p class="text-[9px] text-white/40 uppercase tracking-widest mt-0.5">Score</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-10 bg-[#0a0e17]/50 rounded-xl border border-dashed border-white/10">
                    <p class="text-[11px] text-white/40 italic">Tidak ada lab yang berstatus lulus.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- 3. Modal: Quiz Lulus --}}
    <div x-show="showQuizModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-[#020617]/95 backdrop-blur-md" @click="showQuizModal = false"></div>
        <div class="relative w-full max-w-2xl bg-[#0f141e] border border-fuchsia-500/40 rounded-3xl shadow-[0_30px_100px_rgba(217,70,239,0.15)] p-6 md:p-8" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
            <div class="flex justify-between items-center mb-6 border-b border-white/5 pb-4">
                <div>
                    <h3 class="text-lg md:text-xl font-bold text-white flex items-center gap-2">
                        <svg class="w-6 h-6 text-fuchsia-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                        Evaluasi Lulus
                    </h3>
                    <p class="text-[10px] text-fuchsia-400 mt-1 font-mono uppercase tracking-widest">Daftar Kuis yang Lulus (>= 70)</p>
                </div>
                <button @click="showQuizModal = false" class="text-white/40 hover:text-white transition bg-white/5 hover:bg-red-500/20 p-2 rounded-full border border-transparent hover:border-red-500/30"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            <div class="max-h-[60vh] overflow-y-auto custom-scrollbar space-y-3 pr-2">
                @forelse($quizAttempts->where('score', '>=', 70) as $q)
                <div class="flex items-center justify-between gap-4 p-4 rounded-xl bg-[#0a0e17]/80 border border-white/5 hover:border-fuchsia-500/30 transition group shadow-inner">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-white truncate group-hover:text-fuchsia-300 transition">{{ $q->chapter_id == 99 ? 'Final Evaluation' : 'Evaluasi Bab ' . $q->chapter_id }}</p>
                        <p class="text-[10px] text-white/50 font-mono mt-1">{{ \Carbon\Carbon::parse($q->created_at)->format('d M Y, H:i') }}</p>
                    </div>
                    <div class="text-right shrink-0">
                        <span class="text-lg font-black text-emerald-400">{{ $q->score }}</span>
                        <p class="text-[9px] text-white/40 uppercase tracking-widest mt-0.5">Score</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-10 bg-[#0a0e17]/50 rounded-xl border border-dashed border-white/10">
                    <p class="text-[11px] text-white/40 italic">Belum ada evaluasi kuis yang lulus.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- 4. Modal: Avg Lab Score Detail --}}
    <div x-show="showAvgLabModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-[#020617]/95 backdrop-blur-md" @click="showAvgLabModal = false"></div>
        <div class="relative w-full max-w-lg bg-[#0f141e] border border-emerald-500/40 rounded-3xl shadow-[0_30px_100px_rgba(16,185,129,0.15)] p-6 md:p-8" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
            <div class="flex justify-between items-center mb-6 border-b border-white/5 pb-4">
                <div>
                    <h3 class="text-lg md:text-xl font-bold text-white flex items-center gap-2">
                        <svg class="w-6 h-6 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" /></svg>
                        Average Lab Score
                    </h3>
                    <p class="text-[10px] text-emerald-400 mt-1 font-mono uppercase tracking-widest">Rata-rata Praktikum</p>
                </div>
                <button @click="showAvgLabModal = false" class="text-white/40 hover:text-white transition bg-white/5 hover:bg-red-500/20 p-2 rounded-full border border-transparent hover:border-red-500/30"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            <div class="text-center py-6">
                <div class="inline-block p-8 rounded-full bg-emerald-500/10 border border-emerald-500/20 mb-6 shadow-[0_0_40px_rgba(16,185,129,0.2)]">
                    <span class="text-6xl font-black text-emerald-400">{{ number_format($labStats['avg_score'] ?? 0, 1) }}</span>
                </div>
                <p class="text-white/60 text-sm leading-relaxed max-w-sm mx-auto">Nilai rata-rata dari seluruh pengerjaan modul praktikum lab (kode).</p>
            </div>
        </div>
    </div>

    {{-- 5. Modal: Avg Quiz Score Detail --}}
    <div x-show="showAvgQuizModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-[#020617]/95 backdrop-blur-md" @click="showAvgQuizModal = false"></div>
        <div class="relative w-full max-w-lg bg-[#0f141e] border border-amber-500/40 rounded-3xl shadow-[0_30px_100px_rgba(245,158,11,0.15)] p-6 md:p-8" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
            <div class="flex justify-between items-center mb-6 border-b border-white/5 pb-4">
                <div>
                    <h3 class="text-lg md:text-xl font-bold text-white flex items-center gap-2">
                        <svg class="w-6 h-6 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                        Average Quiz Score
                    </h3>
                    <p class="text-[10px] text-amber-400 mt-1 font-mono uppercase tracking-widest">Rata-rata Evaluasi Teori</p>
                </div>
                <button @click="showAvgQuizModal = false" class="text-white/40 hover:text-white transition bg-white/5 hover:bg-red-500/20 p-2 rounded-full border border-transparent hover:border-red-500/30"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            <div class="text-center py-6">
                <div class="inline-block p-8 rounded-full bg-amber-500/10 border border-amber-500/20 mb-6 shadow-[0_0_40px_rgba(245,158,11,0.2)]">
                    <span class="text-6xl font-black text-amber-400">{{ number_format($quizStats['avg_score'] ?? 0, 1) }}</span>
                </div>
                <p class="text-white/60 text-sm leading-relaxed max-w-sm mx-auto">Nilai rata-rata dari seluruh evaluasi kuis pilihan ganda yang dikerjakan siswa ini.</p>
            </div>
        </div>
    </div>

    {{-- MODAL EDIT SISWA FULL CRUD (ORIGINAL) --}}
    {{-- MODAL EDIT SISWA FULL CRUD (UPDATED DENGAN DINAMIS KELAS) --}}
    <div x-show="showEdit" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-[100] flex items-center justify-center p-4" style="display: none;" x-cloak>
        <div class="absolute inset-0 bg-[#020617]/80 backdrop-blur-sm" @click="showEdit = false"></div>
        <div class="relative w-full max-w-xl bg-[#0f141e] border border-white/10 rounded-2xl shadow-[0_10px_40px_rgba(0,0,0,0.5)] p-6 md:p-8" @click.stop>
            <div class="flex items-center justify-between mb-6 border-b border-white/5 pb-4">
                <h3 class="text-lg font-black text-white">Edit Data Siswa</h3>
                <button @click="showEdit = false" class="text-slate-500 hover:text-white transition bg-white/5 hover:bg-white/10 p-1.5 rounded-lg"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            
            <form action="{{ route('admin.student.update', $user->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5" x-data="{ isSubmitting: false }" @submit="isSubmitting = true">
                @csrf 
                @method('PUT')
                
                <div>
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 block">Profile Photo <span class="text-white/30 lowercase font-normal ml-1">(Optional)</span></label>
                    <input type="file" name="avatar" accept="image/*" class="w-full glass-input rounded-xl px-4 py-2.5 text-sm font-medium focus:ring-2 ring-indigo-500/20 transition-all duration-300 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-indigo-500/20 file:text-indigo-400 hover:file:bg-indigo-500/30 cursor-pointer">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 block">Full Name</label>
                        <input type="text" name="name" value="{{ $user->name }}" class="w-full glass-input rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 ring-indigo-500/20 transition-all duration-300 focus:ml-1" required>
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 block">Email Address</label>
                        <input type="email" name="email" value="{{ $user->email }}" class="w-full glass-input rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 ring-indigo-500/20 transition-all duration-300 focus:ml-1" required>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="relative">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 block">Class Group</label>
                        <div class="relative">
                            <select name="class_group" class="w-full glass-input rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 ring-indigo-500/20 transition-all duration-300 focus:ml-1 appearance-none cursor-pointer">
    <option value="" class="bg-[#0f141e] text-slate-400" {{ empty($user->class_group) ? 'selected' : '' }}>-- Pilih Kelas --</option>
    
    {{-- LOOPING DINAMIS DARI TABEL CLASS_GROUPS --}}
    @foreach($availableClasses as $cls)
        <option value="{{ $cls->name }}" class="bg-[#0f141e] text-white" {{ trim($user->class_group) === trim($cls->name) ? 'selected' : '' }}>
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
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 block">Phone Number</label>
                        <input type="text" name="phone" value="{{ $user->phone }}" class="w-full glass-input rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 ring-indigo-500/20 transition-all duration-300 focus:ml-1">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 block">Institution</label>
                        <input type="text" name="institution" value="{{ $user->institution }}" class="w-full glass-input rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 ring-indigo-500/20 transition-all duration-300 focus:ml-1">
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 block">Study Program</label>
                        <input type="text" name="study_program" value="{{ $user->study_program }}" class="w-full glass-input rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 ring-indigo-500/20 transition-all duration-300 focus:ml-1">
                    </div>
                </div>

                <div>
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 block">Reset Password <span class="text-white/30 lowercase font-normal ml-1">(Biarkan kosong jika tidak diubah)</span></label>
                    <input type="password" name="password" placeholder="Masukkan password baru..." class="w-full glass-input rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 ring-indigo-500/20 transition-all duration-300 focus:ml-1">
                </div>
                
                <div class="flex justify-between items-center mt-8 pt-6 border-t border-white/5">
                    <button type="button" @click="confirmDelete()" class="text-[11px] font-bold text-red-500 hover:text-red-400 transition flex items-center gap-1.5 px-3 py-2 rounded-lg hover:bg-red-500/10">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Delete Student
                    </button>

                    <div class="flex gap-3">
                        <button type="button" @click="showEdit = false" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-400 hover:text-white hover:bg-white/5 transition" :disabled="isSubmitting">Cancel</button>
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow-[0_4px_15px_rgba(79,70,229,0.4)] transition-all flex items-center gap-2" :class="isSubmitting ? 'opacity-70 cursor-wait' : 'hover:bg-indigo-500 hover:shadow-[0_4px_20px_rgba(79,70,229,0.6)] active:scale-95'" :disabled="isSubmitting">
                            <svg x-show="isSubmitting" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" x-cloak><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            <svg x-show="!isSubmitting" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span x-text="isSubmitting ? 'Processing...' : 'Save Changes'"></span>
                        </button>
                    </div>
                </div>
            </form>
            
            <form id="delete-student-form" action="{{ route('admin.users.delete', $user->id) }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>

    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                background: '#0f141e',
                color: '#fff',
                iconColor: '#10b981'
            });
        });
    </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const chartConfig = { color: '#94a3b8', gridColor: 'rgba(255, 255, 255, 0.05)', fontFamily: 'Inter' };
            const ctxScore = document.getElementById('scoreChart');
            
            if(ctxScore && {!! json_encode($chartScores ?? []) !!}.length > 0) {
                const gradient = ctxScore.getContext('2d').createLinearGradient(0, 0, 0, 300);
                gradient.addColorStop(0, 'rgba(99, 102, 241, 0.3)');
                gradient.addColorStop(1, 'rgba(99, 102, 241, 0)');

                new Chart(ctxScore, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($chartLabels ?? []) !!},
                        datasets: [{
                            label: 'Nilai Praktik', 
                            data: {!! json_encode($chartScores ?? []) !!},
                            borderColor: '#818cf8', 
                            backgroundColor: gradient, 
                            borderWidth: 3, 
                            tension: 0.4, 
                            fill: true, 
                            pointBackgroundColor: '#0f141e', 
                            pointBorderColor: '#a5b4fc', 
                            pointBorderWidth: 2,
                            pointRadius: 5,
                            pointHoverRadius: 7,
                            pointHoverBackgroundColor: '#fff'
                        }]
                    },
                    options: { 
                        responsive: true, 
                        maintainAspectRatio: false, 
                        interaction: { mode: 'index', intersect: false },
                        plugins: { 
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: 'rgba(15, 20, 30, 0.9)',
                                titleFont: { family: 'Inter', size: 13, weight: 'bold' },
                                bodyFont: { family: 'Inter', size: 12 },
                                padding: 12,
                                borderColor: 'rgba(255,255,255,0.1)',
                                borderWidth: 1,
                                displayColors: false,
                                callbacks: {
                                    label: function(context) { return 'Skor Akhir: ' + context.parsed.y; }
                                }
                            }
                        }, 
                        scales: { 
                            y: { 
                                beginAtZero: true, 
                                max: 100, 
                                grid: { color: chartConfig.gridColor, drawBorder: false }, 
                                ticks: { color: chartConfig.color, font: { family: 'Inter', size: 10, weight: '600' }, padding: 10 } 
                            }, 
                            x: { display: false } 
                        } 
                    }
                });
            }
        });
        
        // 2. STATUS CHART (Doughnut)
        const ctxStatus = document.getElementById('statusChart');
        if(ctxStatus) {
            new Chart(ctxStatus.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Passed', 'Failed'],
                    datasets: [{
                        data: [{{ $passedLabsCount ?? 0 }}, {{ $failedLabsCount ?? 0 }}],
                        backgroundColor: ['#10b981', '#ef4444'], 
                        borderColor: '#020617',
                        borderWidth: 4
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