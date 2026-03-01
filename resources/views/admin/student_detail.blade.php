<!DOCTYPE html>
<html lang="id">
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
    
    <style>
        /* --- THEME CONFIG --- */
        :root { --glass-bg: rgba(10, 14, 23, 0.65); --glass-border: rgba(255, 255, 255, 0.08); --accent: #6366f1; }
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
        
        .glass-card { 
            background: var(--glass-bg); border: 1px solid var(--glass-border); 
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.2); backdrop-filter: blur(10px); transition: all 0.3s; 
            position: relative; overflow: visible !important; z-index: 10;
        }
        .glass-card:hover { border-color: rgba(99, 102, 241, 0.4); transform: translateY(-4px); box-shadow: 0 12px 40px -10px rgba(99,102,241,0.15); z-index: 30; }

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

        .reveal { opacity: 0; transform: translateY(15px); animation: revealAnim 0.5s forwards ease-out; }
        @keyframes revealAnim { to { opacity: 1; transform: translateY(0); } }
        [x-cloak] { display: none !important; }

        /* Dynamic Tailwind Colors (Dideklarasikan di CSS agar tidak di-purge) */
        .border-emerald-500\/40 { border-color: rgba(16, 185, 129, 0.4); } .bg-emerald-500\/10 { background-color: rgba(16, 185, 129, 0.1); } .text-emerald-400 { color: rgba(52, 211, 153, 1); } .bg-emerald-500 { background-color: #10b981; }
        .border-blue-500\/40 { border-color: rgba(59, 130, 246, 0.4); } .bg-blue-500\/10 { background-color: rgba(59, 130, 246, 0.1); } .text-blue-400 { color: #60a5fa; } .bg-blue-500 { background-color: #3b82f6; }
        .border-indigo-500\/40 { border-color: rgba(99, 102, 241, 0.4); } .bg-indigo-500\/10 { background-color: rgba(99, 102, 241, 0.1); } .text-indigo-400 { color: #818cf8; } .bg-indigo-500 { background-color: #6366f1; }
        .border-cyan-500\/40 { border-color: rgba(6, 182, 212, 0.4); } .bg-cyan-500\/10 { background-color: rgba(6, 182, 212, 0.1); } .text-cyan-400 { color: #22d3ee; } .bg-cyan-500 { background-color: #06b6d4; }
        .border-fuchsia-500\/40 { border-color: rgba(217, 70, 239, 0.4); } .bg-fuchsia-500\/10 { background-color: rgba(217, 70, 239, 0.1); } .text-fuchsia-400 { color: #e879f9; } .bg-fuchsia-500 { background-color: #d946ef; }
        .border-yellow-500\/40 { border-color: rgba(234, 179, 8, 0.4); } .bg-yellow-500\/10 { background-color: rgba(234, 179, 8, 0.1); } .text-yellow-400 { color: #facc15; } .bg-yellow-500 { background-color: #eab308; }
        .border-rose-500\/40 { border-color: rgba(244, 63, 94, 0.4); } .bg-rose-500\/10 { background-color: rgba(244, 63, 94, 0.1); } .text-rose-400 { color: #fb7185; } .bg-rose-500 { background-color: #f43f5e; }
        .border-slate-500\/40 { border-color: rgba(100, 116, 139, 0.4); } .bg-slate-500\/10 { background-color: rgba(100, 116, 139, 0.1); } .text-slate-400 { color: #94a3b8; } .bg-slate-500 { background-color: #64748b; }
        .animate-spin-slow { animation: spin 8s linear infinite; } @keyframes spin { 100% { transform: rotate(360deg); } }

        /* SISTEM TOOLTIP SUPER SOLID */
        .tooltip-container { position: relative; display: inline-flex; align-items: center; justify-content: center; z-index: 50; }
        .tooltip-container:hover { z-index: 99999; }
        .tooltip-trigger { width: 18px; height: 18px; border-radius: 50%; color: white; font-size: 11px; font-weight: 900; display: flex; align-items: center; justify-content: center; cursor: help; transition: all 0.2s; border: 1px solid rgba(255,255,255,0.2); }
        .tooltip-trigger:hover { transform: scale(1.15); }
        .tooltip-content { opacity: 0; visibility: hidden; position: absolute; pointer-events: none; width: max-content; min-width: 200px; max-width: 260px; white-space: normal; text-align: left; background-color: #020617; color: #e2e8f0; font-size: 11px; padding: 14px 16px; line-height: 1.5; border-radius: 12px; box-shadow: 0 20px 60px rgba(0,0,0,1); z-index: 99999; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .tooltip-down .tooltip-content { top: calc(100% + 12px); left: 50%; transform: translateX(-50%) translateY(-10px); }
        .tooltip-down:hover .tooltip-content { transform: translateX(-50%) translateY(0); opacity: 1; visibility: visible; }
        .tooltip-down .tooltip-content::after { content: ''; position: absolute; bottom: 100%; left: 50%; margin-left: -6px; border-width: 6px; border-style: solid; border-color: transparent transparent #020617 transparent; }
        .tooltip-left .tooltip-content { left: auto; right: -12px; transform: translateX(0) translateY(-10px); }
        .tooltip-down.tooltip-left:hover .tooltip-content { transform: translateX(0) translateY(0); }
        .tooltip-left .tooltip-content::after { left: auto; right: 15px; margin-left: 0; }
        .tooltip-indigo .tooltip-trigger { background-color: #6366f1; box-shadow: 0 0 10px rgba(99,102,241,0.5); } .tooltip-indigo .tooltip-trigger:hover { background-color: #818cf8; box-shadow: 0 0 15px rgba(99,102,241,0.8); } .tooltip-indigo .tooltip-content { border: 1px solid rgba(99,102,241,0.5); }
        .tooltip-emerald .tooltip-trigger { background-color: #10b981; box-shadow: 0 0 10px rgba(16,185,129,0.5); } .tooltip-emerald .tooltip-trigger:hover { background-color: #34d399; box-shadow: 0 0 15px rgba(16,185,129,0.8); } .tooltip-emerald .tooltip-content { border: 1px solid rgba(16,185,129,0.5); }
        .tooltip-yellow .tooltip-trigger { background-color: #eab308; color: #020617; box-shadow: 0 0 10px rgba(234,179,8,0.5); } .tooltip-yellow .tooltip-trigger:hover { background-color: #facc15; box-shadow: 0 0 15px rgba(234,179,8,0.8); } .tooltip-yellow .tooltip-content { border: 1px solid rgba(234,179,8,0.5); }
        .tooltip-cyan .tooltip-trigger { background-color: #06b6d4; box-shadow: 0 0 10px rgba(6,182,212,0.5); } .tooltip-cyan .tooltip-trigger:hover { background-color: #22d3ee; box-shadow: 0 0 15px rgba(6,182,212,0.8); } .tooltip-cyan .tooltip-content { border: 1px solid rgba(6,182,212,0.5); }
        .tooltip-fuchsia .tooltip-trigger { background-color: #d946ef; box-shadow: 0 0 10px rgba(217,70,239,0.5); } .tooltip-fuchsia .tooltip-trigger:hover { background-color: #e879f9; box-shadow: 0 0 15px rgba(217,70,239,0.8); } .tooltip-fuchsia .tooltip-content { border: 1px solid rgba(217,70,239,0.5); }
    </style>
</head>
<body class="h-screen w-full flex overflow-hidden selection:bg-indigo-500/30 selection:text-white" 
      x-data="{ 
          sidebarOpen: false,
          activeTab: 'overview', 
          showEdit: false, 
          searchLab: '', 
          searchQuiz: '',
          showProgress: false,
          
          // State Modals Gamifikasi & Insight
          showTitleModal: false,
          showBadgeModal: false,
          activeBadge: null,
          showLessonModal: false,
          showLabModal: false,
          showQuizModal: false,
          showAvgLabModal: false,
          showAvgQuizModal: false,
          showChapterModal: false,

          confirmDelete() {
              Swal.fire({ title: 'Hapus Siswa?', text: 'Tindakan ini tidak dapat dibatalkan. Semua data riwayat akan terhapus.', icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#334155', confirmButtonText: 'Ya, Hapus Permanen', cancelButtonText: 'Batal', reverseButtons: true })
              .then((result) => { if (result.isConfirmed) document.getElementById('delete-student-form').submit(); })
          }
      }"
      @keydown.escape.window="showTitleModal = false; showBadgeModal = false; showLessonModal = false; showLabModal = false; showQuizModal = false; showAvgLabModal = false; showAvgQuizModal = false; showChapterModal = false; showEdit = false;"
      x-init="setTimeout(() => showProgress = true, 200); $watch('activeTab', value => { if(value === 'overview') { showProgress = false; setTimeout(() => showProgress = true, 50); } });">

    {{-- HELPER DATA BLADE --}}
    @php
        function formatTime($seconds) {
            if ($seconds === null || $seconds === '') return '-';
            $m = floor($seconds / 60); $s = $seconds % 60;
            return ($m > 0 ? "{$m}m " : "") . "{$s}s";
        }
        $labHistories = isset($labHistories) ? collect($labHistories) : collect([]);
        $quizAttempts = isset($quizAttempts) ? collect($quizAttempts) : collect([]);
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
    @endphp

    <div x-show="sidebarOpen" class="fixed inset-0 bg-[#020617]/80 backdrop-blur-sm z-[90] md:hidden" @click="sidebarOpen = false" x-transition.opacity style="display: none;" x-cloak></div>
    
    {{-- SIDEBAR ADMIN --}}
    <aside class="glass-sidebar w-72 h-full flex flex-col fixed md:relative z-[100] transition-transform duration-300 transform md:translate-x-0" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
        <div class="h-24 flex items-center justify-between px-8 border-b border-white/5 relative overflow-hidden group">
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-20 h-20 bg-indigo-500/20 rounded-full blur-[40px] opacity-0 group-hover:opacity-100 transition duration-500"></div>
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 relative z-10">
                <img src="{{ asset('images/logo.png') }}" onerror="this.style.display='none'" class="h-9 w-auto object-contain">
                <div><h1 class="text-lg font-black text-white tracking-tight leading-none">Util<span class="text-indigo-400">wind</span></h1><span class="text-[10px] font-bold text-white/40 tracking-[0.2em] uppercase">Admin Panel</span></div>
            </a>
            <button @click="sidebarOpen = false" class="md:hidden text-white/50 hover:text-white relative z-10"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
        </div>

        <nav class="flex-1 overflow-y-auto custom-scrollbar py-8 px-4 space-y-8">
            <div>
                <p class="px-4 text-[10px] font-extrabold text-white/30 uppercase tracking-widest mb-3">Overview</p>
                <div class="space-y-1">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link active">
                        <svg class="w-5 h-5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg> Dashboard
                    </a>
                </div>
            </div>
            <div>
                <p class="px-4 text-[10px] font-extrabold text-white/30 uppercase tracking-widest mb-3">Academic</p>
                <div class="space-y-1">
                    <a href="{{ route('admin.analytics.questions') }}" class="nav-link"><svg class="w-5 h-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg> Quiz Management</a>
                    <a href="{{ route('admin.labs.index') }}" class="nav-link"><svg class="w-5 h-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg> Lab Configuration</a>
                    <a href="{{ route('admin.lab.analytics') }}" class="nav-link"><svg class="w-5 h-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg> Lab Analytics</a>
                    <a href="{{ route('admin.classes.index') }}" class="nav-link"><svg class="w-5 h-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg> Class Management</a>
                </div>
            </div>
        </nav>

        <div class="p-4 border-t border-white/5 bg-[#05080f]/50">
            <div class="flex items-center gap-3 mb-4 px-2">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-fuchsia-500 flex items-center justify-center font-bold text-white text-xs shadow-lg">AD</div>
                <div class="overflow-hidden"><p class="text-xs font-bold text-white truncate">{{ Auth::user()->name ?? 'Administrator' }}</p><p class="text-[10px] text-white/40 truncate">System Admin</p></div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500 hover:text-white transition text-xs font-bold border border-red-500/20">Logout</button>
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
        <header class="glass-header flex flex-col justify-end px-6 md:px-10 shrink-0 sticky top-0 z-40 pt-5">
            <div class="flex items-start justify-between w-full mb-3 md:mb-5">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = true" class="md:hidden p-2 bg-white/5 rounded-lg text-white hover:bg-white/10 transition mt-1"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg></button>
                    <div class="flex items-center gap-4">
                        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-indigo-500 to-cyan-400 p-[1px] shadow-lg hidden sm:block">
    @if($user->avatar)
        <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="w-full h-full object-cover rounded-[10px]">
    @else
        <div class="w-full h-full bg-[#0f141e] rounded-[11px] flex items-center justify-center font-black text-white text-base">{{ substr($user->name ?? 'S', 0, 2) }}</div>
    @endif
</div>
                        <div>
                            <nav class="flex text-[10px] text-white/50 mb-1.5 font-bold hidden sm:flex">
                                <ol class="inline-flex items-center space-x-1">
                                    <li class="inline-flex items-center"><a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-400 transition">Dashboard</a></li>
                                    <li><div class="flex items-center"><svg class="w-3 h-3 text-white/30 mx-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg><span class="text-white">{{ $user->name ?? 'Detail Siswa' }}</span></div></li>
                                </ol>
                            </nav>
                            <h2 class="text-white font-bold text-lg md:text-xl tracking-tight flex items-center gap-2 leading-none">{{ $user->name ?? 'Student Profile' }}</h2>
                            <div class="flex items-center gap-2 mt-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse shadow-[0_0_8px_#10b981]"></span>
                                <p class="text-[10px] text-white/40 font-mono">{{ $user->email ?? 'No email recorded' }}</p>
                                <span class="text-[9px] bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 px-2 py-0.5 rounded font-bold ml-2 uppercase tracking-widest hidden md:inline-block">{{ $user->class_group ?? 'No Class' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center gap-3 sm:gap-6 mt-1">
                    {{-- Global Progress --}}
                    <div class="hidden xl:flex flex-col items-end mr-2">
                        <p class="text-[9px] uppercase font-extrabold text-slate-400 tracking-widest mb-1.5">Global Progress</p>
                        <div class="flex items-center gap-3">
                            <div class="w-24 h-1.5 bg-[#0f141e] rounded-full overflow-hidden border border-white/5 shadow-inner">
                                <div class="h-full bg-cyan-400 rounded-full" style="width: {{ $globalProgress ?? 0 }}%"></div>
                            </div>
                            <span class="text-xs font-black text-cyan-400">{{ $globalProgress ?? 0 }}%</span>
                        </div>
                    </div>

                    {{-- Export Dropdown --}}
                    <div class="relative" x-data="{ exportOpen: false }">
                        <button @click="exportOpen = !exportOpen" @click.away="exportOpen = false" class="p-2.5 sm:px-4 sm:py-2.5 rounded-full sm:rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-white text-xs font-bold transition flex items-center gap-2 shadow-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span class="hidden sm:inline">Export</span>
                        </button>
                        <div x-show="exportOpen" class="absolute right-0 mt-2 w-48 bg-[#0f141e] border border-white/10 rounded-xl shadow-[0_15px_50px_rgba(0,0,0,0.9)] z-[9999] overflow-hidden" style="display: none;" x-transition>
                            <div class="px-4 py-2 border-b border-white/5 text-[9px] font-bold text-white/30 uppercase tracking-widest bg-[#0a0e17]">Pilih Format</div>
                            <a href="{{ route('admin.student.export.csv', $user->id) }}" class="flex items-center gap-3 px-4 py-3.5 text-[11px] font-bold text-white hover:bg-white/5 transition border-b border-white/5"><svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg> Export CSV</a>
                            <a href="{{ route('admin.student.export.pdf', $user->id) }}" target="_blank" class="flex items-center gap-3 px-4 py-3.5 text-[11px] font-bold text-white hover:bg-white/5 transition"><svg class="w-4 h-4 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg> Print PDF</a>
                        </div>
                    </div>

                    <button @click="showEdit = true" class="p-2.5 rounded-full bg-indigo-500/10 hover:bg-indigo-500 text-indigo-400 hover:text-white border border-indigo-500/20 hover:border-indigo-500 transition-all shadow-lg active:scale-95" title="Edit Student"><svg class="w-4 h-4 transition-transform hover:rotate-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg></button>
                </div>
            </div>

            {{-- Tabs --}}
            <div class="flex gap-6 md:gap-8 mt-2 overflow-x-auto custom-scrollbar w-full relative z-10">
                <button @click="activeTab = 'overview'" :class="activeTab === 'overview' ? 'active text-white' : 'text-slate-500'" class="tab-btn pb-3.5 text-[11px] font-bold uppercase tracking-wider flex items-center gap-2 whitespace-nowrap"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" /></svg> Overview</button>
                <button @click="activeTab = 'curriculum'" :class="activeTab === 'curriculum' ? 'active text-white' : 'text-slate-500'" class="tab-btn pb-3.5 text-[11px] font-bold uppercase tracking-wider flex items-center gap-2 whitespace-nowrap"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg> Curriculum Tracker</button>
                <button @click="activeTab = 'history'" :class="activeTab === 'history' ? 'active text-white' : 'text-slate-500'" class="tab-btn pb-3.5 text-[11px] font-bold uppercase tracking-wider flex items-center gap-2 whitespace-nowrap"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> History Log</button>
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
                         ZONA GAMIFIKASI: IDENTITY & BADGES
                         ========================================================= --}}
                    <div class="animate-fade-in-up">
                        <div class="flex items-center gap-4 mb-6">
                            
                            <span class="px-2.5 py-1 rounded bg-indigo-500/10 border border-indigo-500/20 text-[9px] font-bold text-indigo-400 tracking-widest uppercase">Gamification Zone</span>
                        </div>

                        {{-- Layout Responsif: Identity 1/3, Badges 2/3 (Disesuaikan permintaaan) --}}
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            
                            {{-- IDENTITY & XP CARD (Kiri, 1 Kolom) --}}
                            <div @click="showTitleModal = true" class="lg:col-span-1 glass-card rounded-[2rem] p-6 md:p-8 border-t-2 border-t-indigo-500/50 relative overflow-hidden flex flex-col items-center text-center shadow-2xl group cursor-pointer hover:border-indigo-500/40">
                                <div class="absolute inset-0 bg-gradient-to-b from-indigo-500/5 to-transparent pointer-events-none"></div>
                                <div class="absolute -top-10 -right-10 w-40 h-40 bg-indigo-500/20 rounded-full blur-[60px] group-hover:bg-indigo-500/30 transition duration-700"></div>

                                <div class="relative w-24 h-24 mb-5 mt-2">
                                    <div class="absolute inset-0 bg-gradient-to-tr from-indigo-500 to-cyan-500 rounded-full animate-spin-slow blur-md opacity-70 group-hover:opacity-100 transition"></div>
                                    <div class="absolute inset-1 bg-[#020617] rounded-full z-10"></div>
                                    <div class="absolute inset-2 bg-indigo-900 rounded-full flex items-center justify-center text-3xl font-black text-white z-20 shadow-inner overflow-hidden">
    @if($user->avatar)
        <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
    @else
        {{ substr($user->name, 0, 1) }}
    @endif
</div>
                                    <div class="absolute -bottom-3 left-1/2 -translate-x-1/2 z-30 bg-indigo-500 text-[#020617] text-[10px] font-black uppercase px-4 py-1.5 rounded-full shadow-lg whitespace-nowrap">
                                        {{ $user->developer_title ?? 'Intern Coder' }}
                                    </div>
                                </div>

                                <h3 class="text-lg font-bold text-white mb-1">{{ $user->name }}</h3>
                                <p class="text-xs text-slate-500 font-mono mb-6">{{ $user->email }}</p>

                                <div class="w-full text-left bg-white/[0.02] p-4 rounded-xl border border-white/5 group-hover:bg-white/[0.04] transition">
                                    <div class="flex justify-between items-center text-xs font-bold mb-2">
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-indigo-400 drop-shadow-md">{{ number_format($user->xp ?? 0) }} XP</span>
                                            <div class="w-3.5 h-3.5 rounded-full bg-indigo-500/20 text-indigo-400 flex items-center justify-center border border-indigo-500/30 text-[8px]">?</div>
                                        </div>
                                        <span class="text-white/50">Target: {{ number_format($user->next_level_xp ?? 300) }}</span>
                                    </div>
                                    <div class="w-full h-2 bg-[#020617] rounded-full overflow-hidden border border-white/10 relative">
                                        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20"></div>
                                        <div class="h-full bg-gradient-to-r from-indigo-500 to-cyan-400 shadow-[0_0_10px_rgba(99,102,241,0.5)] transition-all duration-[1.5s]" style="width: {{ $user->xp_progress ?? 0 }}%"></div>
                                    </div>
                                </div>

                                {{-- STATUS KELAS (ADMIN VIEW) --}}
                                @empty($user->class_group)
                                    <div class="mt-6 flex flex-col items-center gap-2 w-full relative z-10">
                                        <button @click.stop="showEdit = true" class="w-full py-2.5 rounded-xl bg-indigo-600/20 hover:bg-indigo-600 text-indigo-400 hover:text-white text-xs font-bold transition border border-indigo-500/30">
                                            Set Kelas Manual
                                        </button>
                                    </div>
                                @else
                                    <div class="mt-6 flex flex-col items-center gap-2 w-full relative z-10" @click.stop>
                                        <div class="flex items-center gap-2 text-xs text-emerald-400 bg-emerald-500/10 px-4 py-2 rounded-lg border border-emerald-500/20 w-full justify-center">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> {{ $user->class_group }}
                                        </div>
                                        @if(isset($classGroup))
                                            <div class="text-[10px] font-mono text-slate-400 bg-[#020617] border border-white/5 px-3 py-1.5 rounded-md shadow-inner flex justify-between items-center w-full">
                                                <span>Token: <span class="font-bold text-white tracking-widest">{{ $classGroup->token }}</span></span>
                                                <span class="{{ $classGroup->is_active ? 'text-emerald-500' : 'text-red-500' }}">{{ $classGroup->is_active ? 'Active' : 'Closed' }}</span>
                                            </div>
                                        @endif
                                    </div>
                                @endempty
                            </div>

                            {{-- 1.B BADGES COLLECTION (Kanan, 2 Kolom, Lebih Lega & Natural) --}}
                            <div class="lg:col-span-2 glass-card rounded-[2rem] p-6 md:p-8 relative overflow-hidden flex flex-col shadow-lg">
                                <div class="flex justify-between items-center mb-6 border-b border-white/5 pb-4">
                                    <h4 class="font-bold text-white flex items-center gap-2"><span class="text-xl">🎖️</span> Koleksi Lencana</h4>
                                    <span class="text-[10px] bg-white/5 px-3 py-1.5 rounded-lg text-white/50 border border-white/5 font-mono font-bold tracking-wider">{{ count($unlockedBadges ?? []) }} Unlocked</span>
                                </div>
                                <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 xl:grid-cols-6 gap-4 overflow-y-auto custom-scrollbar pr-2 max-h-[350px]">
                                    @forelse($allBadges ?? [] as $badge)
                                        @php
                                            $isUnlocked = in_array($badge->id, $unlockedBadges ?? []);
                                            $c = $badge->color ?? 'indigo';
                                            $badgeData = json_encode(['name'=>$badge->name, 'description'=>$badge->description, 'color'=>$c, 'icon'=>$badge->icon, 'status'=>$isUnlocked?'Unlocked':'Locked']);
                                        @endphp
                                        <div @click="activeBadge = {{ $badgeData }}; showBadgeModal = true" class="aspect-square rounded-2xl flex flex-col items-center justify-center p-3 cursor-pointer transition border hover:-translate-y-1 relative overflow-hidden group {{ $isUnlocked ? 'bg-[#0a0e17] border-'.$c.'-500/40 shadow-[0_0_20px_rgba(var(--color-'.$c.'-500),0.15)]' : 'bg-white/[0.02] border-white/5 grayscale opacity-40 hover:grayscale-[0.5]' }}">
                                            @if($isUnlocked)<div class="absolute inset-0 bg-gradient-to-b from-{{$c}}-500/10 to-transparent opacity-0 group-hover:opacity-100 transition duration-300"></div>@endif
                                            <div class="w-10 h-10 {{ $isUnlocked ? 'text-'.$c.'-400 drop-shadow-[0_0_10px_rgba(var(--color-'.$c.'-500),0.8)]' : 'text-slate-500' }} mb-2.5 transition-transform duration-300 group-hover:scale-110 flex justify-center">{!! $badge->icon !!}</div>
                                            <p class="text-[9px] font-black uppercase text-center leading-tight tracking-wide {{ $isUnlocked ? 'text-'.$c.'-400' : 'text-white/50' }} relative z-10">{{ $badge->name }}</p>
                                        </div>
                                    @empty
                                        <div class="col-span-full text-center py-12 border-2 border-dashed border-white/10 rounded-2xl bg-white/[0.02]"><p class="text-xs text-white/40 italic">Lencana sedang dipersiapkan oleh sistem.</p></div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- VISUAL SEPARATOR --}}
                    <div class="flex items-center gap-4 py-4">
                        <div class="h-px bg-white/10 flex-1"></div>
                        <span class="text-[10px] font-black text-slate-600 uppercase tracking-[0.2em] bg-[#020617] px-3 py-1 border border-white/5 rounded-full">Academic Analytics</span>
                        <div class="h-px bg-white/10 flex-1"></div>
                    </div>

                    {{-- =========================================================
                         ZONA AKADEMIK (DESAIN ASLI ANDA)
                         ========================================================= --}}
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
                                $totalLessonIds = 0; $completedLessonCount = 0;
                                foreach($chapter['topics'] as $t) {
                                    $totalLessonIds += count($t['ids']);
                                    $completedLessonCount += count(array_intersect($t['ids'], $completedLessonIds ?? []));
                                }
                                $totalWeight = $totalLessonIds + 20; 
                                $currentWeight = $completedLessonCount + ($labDone ? 10 : 0) + ($quizPass ? 10 : 0);
                                $chapterPercent = min(round(($currentWeight / $totalWeight) * 100), 100);
                            @endphp

                            <div class="glass-card rounded-2xl overflow-hidden flex flex-col relative group h-full hover:border-{{ $chapter['color'] }}-500/40" style="animation-delay: {{ $index * 100 }}ms">
                                <div class="absolute top-0 left-0 h-1.5 bg-{{ $chapter['color'] }}-500 transition-all duration-1000 shadow-[0_0_10px_currentColor]" :style="showProgress ? 'width: {{ $chapterPercent }}%' : 'width: 0%'"></div>
                                
                                <div class="px-6 py-5 border-b border-white/5 bg-white/[0.01] flex justify-between items-center group-hover:bg-{{ $chapter['color'] }}-500/5 transition">
                                    <div class="flex items-center gap-3">
                                        <span class="text-[10px] font-black px-2.5 py-1 rounded bg-{{ $chapter['color'] }}-500/10 text-{{ $chapter['color'] }}-400 border border-{{ $chapter['color'] }}-500/20 shadow-inner">BAB {{ $chapter['number'] }}</span>
                                        <h4 class="text-sm font-bold text-white flex items-center gap-2">
                                            {{ $chapter['title'] }}
                                            @if($chapterPercent == 100) <svg class="w-4 h-4 text-emerald-400 drop-shadow-[0_0_5px_#10b981]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> @endif
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
                                                    <div class="absolute left-[3.5px] top-3 w-2.5 h-2.5 rounded-full border-[2px] border-[#0f141e] {{ $isTopicDone ? 'bg-emerald-500 shadow-[0_0_8px_#10b981]' : 'bg-slate-700' }} transition-colors duration-300"></div>
                                                    <div class="flex flex-col">
                                                        <span class="text-[13px] font-semibold transition-colors duration-300 {{ $isTopicDone ? 'text-white' : 'text-slate-400' }}">{{ $topic['name'] }}</span>
                                                        <div class="flex items-center gap-2 mt-0.5">
                                                            <div class="w-16 h-1 bg-white/10 rounded-full overflow-hidden">
                                                                <div class="h-full bg-slate-500 rounded-full {{ $isTopicDone ? 'bg-emerald-400' : '' }} transition-all duration-1000" style="width: {{ $progressW }}%"></div>
                                                            </div>
                                                            <span class="text-[9px] font-mono {{ $isTopicDone ? 'text-emerald-500/70' : 'text-slate-500' }}">{{ $partial }}/{{ $total }} slide</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if($isTopicDone) <span class="text-[9px] font-bold text-emerald-400 bg-emerald-500/10 px-1.5 py-0.5 rounded uppercase border border-emerald-500/20">Done</span> @endif
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="mt-auto space-y-3 pt-5 border-t border-white/5">
                                        <div class="flex items-center justify-between p-2 rounded-lg bg-white/[0.02] hover:bg-white/[0.04] transition">
                                            <div class="flex items-center gap-2.5 text-xs text-slate-300 font-medium">
                                                <div class="w-6 h-6 rounded bg-{{ $chapter['color'] }}-500/20 flex items-center justify-center text-{{ $chapter['color'] }}-400 text-xs shadow-inner">⚡</div>
                                                {{ $chapter['lab_name'] }}
                                            </div>
                                            <span class="text-[10px] font-black px-2 py-0.5 rounded {{ $labDone ? 'text-emerald-400 bg-emerald-500/10 border border-emerald-500/20' : 'text-slate-500 bg-slate-800/50' }}">
                                                {{ $labDone ? 'LULUS' : 'PENDING' }}
                                            </span>
                                        </div>
                                        <div class="flex items-center justify-between p-2 rounded-lg bg-white/[0.02] hover:bg-white/[0.04] transition">
                                            <div class="flex items-center gap-2.5 text-xs text-slate-300 font-medium">
                                                <div class="w-6 h-6 rounded bg-{{ $chapter['color'] }}-500/20 flex items-center justify-center text-{{ $chapter['color'] }}-400 text-xs shadow-inner">📝</div>
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
                                <div><h3 class="text-sm font-black text-white uppercase tracking-widest">Riwayat Praktik Lab</h3></div>
                            </div>
                            <div class="relative w-full md:w-auto">
                                <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                <input x-model="searchLab" type="text" placeholder="Cari lab..." class="glass-input pl-9 pr-4 py-2 text-xs rounded-lg w-full md:w-56 focus:w-64 transition-all duration-300">
                            </div>
                        </div>
                        <div class="overflow-x-auto max-h-[500px] custom-scrollbar bg-black/20">
                            <table class="w-full text-sm text-left whitespace-nowrap">
                                <thead class="bg-[#0f141e]/90 backdrop-blur-md text-white/40 text-[10px] uppercase font-bold sticky top-0 z-10 shadow-sm border-b border-white/5">
                                    <tr><th class="px-6 py-4">Lab Name</th><th class="px-6 py-4 text-center">Status</th><th class="px-6 py-4 text-center">Score</th><th class="px-6 py-4">Waktu</th><th class="px-6 py-4 text-right">Aksi Code</th></tr>
                                </thead>
                                @forelse($labHistories as $h)
                                <tbody x-data="{ expanded: false }" class="text-slate-300 border-b border-white/5 last:border-0" x-show="searchLab === '' || '{{ addslashes(strtolower($h->lab->title ?? 'Lab #'.$h->lab_id)) }}'.includes(searchLab.toLowerCase()) || '{{ strtolower($h->status) }}'.includes(searchLab.toLowerCase())">
                                    <tr class="table-row group transition-all duration-300" :class="expanded ? 'bg-white/[0.02]' : ''">
                                        <td class="px-6 py-4"><span class="block text-white font-semibold transition group-hover:text-indigo-300">{{ $h->lab->title ?? 'Lab #'.$h->lab_id }}</span></td>
                                        <td class="px-6 py-4 text-center"><span class="px-2.5 py-1 rounded text-[10px] font-black uppercase tracking-wider {{ $h->status == 'passed' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-red-500/10 text-red-400 border border-red-500/20' }}">{{ $h->status }}</span></td>
                                        <td class="px-6 py-4 text-white font-black text-center">{{ $h->final_score }}</td>
                                        <td class="px-6 py-4 text-slate-400 text-xs font-mono">{{ formatTime($h->duration_seconds) }}</td>
                                        <td class="px-6 py-4 text-right">
                                            @if($h->last_code_snapshot != null && $h->last_code_snapshot != '')
                                                <button @click="expanded = !expanded" class="px-3 py-1.5 rounded-lg bg-cyan-500/10 text-cyan-400 hover:bg-cyan-500 hover:text-white transition text-[10px] font-bold border border-cyan-500/20 inline-flex items-center gap-1.5"><span x-text="expanded ? 'Tutup Kode' : 'Lihat Kode'"></span></button>
                                            @else
                                                <span class="text-[10px] text-slate-500 italic px-2 py-1 bg-slate-800/30 rounded">No Snapshot</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @if($h->last_code_snapshot != null && $h->last_code_snapshot != '')
                                    <tr x-show="expanded" x-cloak class="bg-[#05080f] border-b-2 border-indigo-500/30 shadow-inner">
                                        <td colspan="5" class="p-0">
                                            <div x-show="expanded" x-collapse>
                                                <div class="p-6 md:p-8 bg-gradient-to-b from-[#0a0d14] to-transparent">
                                                    <div class="rounded-xl overflow-hidden border border-white/10 shadow-2xl bg-[#0d1117]">
                                                        <div class="bg-[#1e2330] px-4 py-2.5 border-b border-white/5 flex gap-1.5 items-center">
                                                            <div class="w-2.5 h-2.5 rounded-full bg-red-500/80"></div><div class="w-2.5 h-2.5 rounded-full bg-amber-500/80"></div><div class="w-2.5 h-2.5 rounded-full bg-emerald-500/80"></div><span class="ml-3 text-[10px] text-white/30 font-mono">index.html</span>
                                                        </div>
                                                        <div class="p-5 max-h-[300px] overflow-y-auto custom-scrollbar"><pre class="text-cyan-50 text-xs font-mono leading-relaxed"><code>{{ $h->last_code_snapshot }}</code></pre></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                                @empty
                                <tbody><tr><td colspan="5" class="px-6 py-12 text-center text-slate-500 font-medium">Belum ada aktivitas penyelesaian lab.</td></tr></tbody>
                                @endforelse
                            </table>
                        </div>
                    </div>

                    {{-- Quiz History --}}
                    <div class="glass-card rounded-2xl overflow-hidden shadow-2xl mt-8">
                        <div class="p-4 md:p-6 border-b border-white/5 bg-gradient-to-r from-white/[0.04] to-transparent flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                            <div class="flex items-center gap-3">
                                <div class="p-2 rounded-lg bg-fuchsia-500/20 text-fuchsia-400"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg></div>
                                <div><h3 class="text-sm font-black text-white uppercase tracking-widest">Riwayat Kuis</h3></div>
                            </div>
                        </div>
                        <div class="overflow-x-auto max-h-[400px] custom-scrollbar bg-black/20">
                            <table class="w-full text-sm text-left whitespace-nowrap">
                                <thead class="bg-[#0f141e]/90 backdrop-blur-md text-white/40 text-[10px] uppercase font-bold sticky top-0 z-10 shadow-sm border-b border-white/5">
                                    <tr><th class="px-6 py-4">Quiz Name</th><th class="px-6 py-4 text-center">Status</th><th class="px-6 py-4 text-center">Score</th><th class="px-6 py-4 text-right">Date & Time</th></tr>
                                </thead>
                                <tbody class="divide-y divide-white/5 text-slate-300">
                                    @forelse($quizAttempts as $q)
                                    @php $qName = $q->chapter_id == '99' ? 'Final Evaluation' : 'Evaluasi Bab '.$q->chapter_id; @endphp
                                    <tr class="table-row group transition-all duration-300">
                                        <td class="px-6 py-4 text-white font-semibold transition group-hover:text-fuchsia-300">{{ $qName }}</td>
                                        <td class="px-6 py-4 text-center"><span class="px-2.5 py-1 rounded text-[10px] font-black uppercase tracking-wider {{ $q->score >= 70 ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-red-500/10 text-red-400 border border-red-500/20' }}">{{ $q->score >= 70 ? 'Lulus' : 'Gagal' }}</span></td>
                                        <td class="px-6 py-4 text-white font-black text-center">{{ $q->score }}</td>
                                        <td class="px-6 py-4 text-right text-slate-500 text-xs font-mono group-hover:text-slate-400 transition">{{ \Carbon\Carbon::parse($q->created_at)->format('d M Y, H:i') }}</td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="4" class="px-6 py-12 text-center text-slate-500 font-medium">Belum ada data pengambilan kuis.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>

    {{-- ==================== HERO MODALS (GAMIFICATION & INSIGHTS) ==================== --}}
    
    {{-- MODAL PANGKAT & XP --}}
    <div x-show="showTitleModal" class="fixed inset-0 z-[99999] flex items-center justify-center p-4" x-cloak>
        <div class="absolute inset-0 bg-[#020617]/90 backdrop-blur-sm" @click="showTitleModal = false"></div>
        <div class="relative w-full max-w-md bg-[#0f141e] border border-indigo-500/40 rounded-3xl p-6 md:p-8 shadow-[0_20px_70px_rgba(99,102,241,0.15)]" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <button @click="showTitleModal = false" class="absolute top-4 right-4 text-slate-500 hover:text-white transition p-2 rounded-lg bg-white/5 hover:bg-white/10 z-20">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            <div class="flex flex-col items-center text-center mt-2 relative">
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-32 h-32 rounded-full bg-indigo-500 blur-[40px] opacity-20 pointer-events-none"></div>
                <div class="w-16 h-16 rounded-2xl bg-indigo-500/10 text-indigo-400 flex items-center justify-center border border-indigo-500/30 mb-6 relative z-10 shadow-[0_0_15px_rgba(99,102,241,0.5)]">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                </div>
                <h3 class="text-2xl font-black text-white mb-2 tracking-tight">Sistem Pangkat & XP</h3>
                <p class="text-slate-400 text-sm leading-relaxed mb-6">Kumpulkan Experience Points (XP) dari aktivitas belajar untuk membuka titel developer baru.</p>

                <div class="w-full space-y-2 text-left relative z-10">
                    <div class="flex justify-between items-center p-3 rounded-xl border {{ ($user->xp ?? 0) >= 4000 ? 'bg-indigo-500/20 border-indigo-500/50 text-white shadow-[0_0_10px_rgba(99,102,241,0.3)]' : 'bg-white/5 border-white/10 text-slate-400' }} transition-colors">
                        <span class="text-xs font-bold flex items-center gap-2"><span class="text-rose-400 text-sm">💎</span> Tailwind Architect</span><span class="text-[10px] font-mono">4000+ XP</span>
                    </div>
                    <div class="flex justify-between items-center p-3 rounded-xl border {{ (($user->xp ?? 0) >= 2500 && ($user->xp ?? 0) < 4000) ? 'bg-indigo-500/20 border-indigo-500/50 text-white shadow-[0_0_10px_rgba(99,102,241,0.3)]' : 'bg-white/5 border-white/10 text-slate-400' }} transition-colors">
                        <span class="text-xs font-bold flex items-center gap-2"><span class="text-amber-400 text-sm">🥇</span> Component Crafter</span><span class="text-[10px] font-mono">2500 XP</span>
                    </div>
                    <div class="flex justify-between items-center p-3 rounded-xl border {{ (($user->xp ?? 0) >= 1000 && ($user->xp ?? 0) < 2500) ? 'bg-indigo-500/20 border-indigo-500/50 text-white shadow-[0_0_10px_rgba(99,102,241,0.3)]' : 'bg-white/5 border-white/10 text-slate-400' }} transition-colors">
                        <span class="text-xs font-bold flex items-center gap-2"><span class="text-slate-300 text-sm">🥈</span> Frontend Stylist</span><span class="text-[10px] font-mono">1000 XP</span>
                    </div>
                    <div class="flex justify-between items-center p-3 rounded-xl border {{ (($user->xp ?? 0) >= 300 && ($user->xp ?? 0) < 1000) ? 'bg-indigo-500/20 border-indigo-500/50 text-white shadow-[0_0_10px_rgba(99,102,241,0.3)]' : 'bg-white/5 border-white/10 text-slate-400' }} transition-colors">
                        <span class="text-xs font-bold flex items-center gap-2"><span class="text-orange-400 text-sm">🥉</span> Utility Apprentice</span><span class="text-[10px] font-mono">300 XP</span>
                    </div>
                    <div class="flex justify-between items-center p-3 rounded-xl border {{ ($user->xp ?? 0) < 300 ? 'bg-indigo-500/20 border-indigo-500/50 text-white shadow-[0_0_10px_rgba(99,102,241,0.3)]' : 'bg-white/5 border-white/10 text-slate-400' }} transition-colors">
                        <span class="text-xs font-bold flex items-center gap-2"><span class="text-slate-500 text-sm">⚪</span> CSS Novice</span><span class="text-[10px] font-mono">0 XP</span>
                    </div>
                </div>

                <div class="mt-6 flex justify-between w-full text-[10px] font-mono font-bold text-slate-400 bg-[#020617] p-3.5 rounded-xl border border-white/5 shadow-inner">
                    <span>Materi: +10 XP</span><span>Lab: +50 XP</span><span>Kuis: Max +100 XP</span>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL BADGE --}}
    <div x-show="showBadgeModal" class="fixed inset-0 z-[99999] flex items-center justify-center p-4" x-cloak>
        <div class="absolute inset-0 bg-[#020617]/90 backdrop-blur-sm" @click="showBadgeModal = false"></div>
        <div class="relative w-full max-w-sm bg-[#0f141e] border rounded-3xl p-8 shadow-2xl transition-colors duration-300 transform scale-100"
             :class="'border-' + activeBadge?.color + '-500/40 shadow-[0_20px_70px_rgba(var(--color-' + activeBadge?.color + '-500),0.15)]'"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <button @click="showBadgeModal = false" class="absolute top-4 right-4 text-slate-500 hover:text-white bg-white/5 rounded-full p-1 transition"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            <div class="flex flex-col items-center text-center mt-2 relative">
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-40 h-40 rounded-full blur-[50px] pointer-events-none transition-colors duration-300 opacity-20" :class="'bg-' + activeBadge?.color + '-500'"></div>
                <div class="mb-6 relative z-10 transition-colors duration-300 w-20 h-20" :class="'text-' + activeBadge?.color + '-400 drop-shadow-[0_0_20px_rgba(var(--color-' + activeBadge?.color + '-500),0.8)]'" x-html="activeBadge?.icon"></div>
                <h3 class="text-2xl font-black text-white mb-2 tracking-tight" x-text="activeBadge?.name"></h3>
                <div class="mb-6">
                    <span class="px-4 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-widest border transition-colors duration-300" 
                          :class="activeBadge?.status === 'Unlocked' ? 'bg-' + activeBadge?.color + '-500/10 text-' + activeBadge?.color + '-400 border-' + activeBadge?.color + '-500/20' : 'bg-slate-500/10 text-slate-400 border-slate-500/20'" 
                          x-text="activeBadge?.status === 'Unlocked' ? 'Siswa Memiliki Ini' : 'Lencana Terkunci'"></span>
                </div>
                <div class="bg-[#020617] w-full rounded-2xl p-5 border border-white/5 shadow-inner">
                    <p class="text-[9px] text-white/40 uppercase font-bold tracking-widest mb-2 border-b border-white/5 pb-2 text-left">Deskripsi & Syarat</p>
                    <p class="text-slate-300 text-sm leading-relaxed text-left" x-text="activeBadge?.description"></p>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL INFO AKADEMIK (LESSON, LAB, QUIZ, CHAPTER) DARI DESAIN ASLI ANDA --}}
    <div x-show="showLessonModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-[#020617]/95 backdrop-blur-md" @click="showLessonModal = false"></div>
        <div class="relative w-full max-w-md bg-[#0f141e] border border-fuchsia-500/40 rounded-3xl p-8" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <h3 class="text-xl font-bold text-white mb-2">Statistik Materi</h3>
            <p class="text-slate-400 text-sm mb-4">Anda telah menyelesaikan {{ $lessonsCompleted ?? 0 }} dari {{ $totalLessons ?? 0 }} materi teori.</p>
            <button @click="showLessonModal = false" class="text-sm text-fuchsia-400 font-bold hover:underline">Tutup</button>
        </div>
    </div>
    
    <div x-show="showLabModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-[#020617]/95 backdrop-blur-md" @click="showLabModal = false"></div>
        <div class="relative w-full max-w-md bg-[#0f141e] border border-blue-500/40 rounded-3xl p-8" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <h3 class="text-xl font-bold text-white mb-2">Statistik Lab</h3>
            <p class="text-slate-400 text-sm mb-4">Anda telah lulus {{ $labsCompleted ?? 0 }} dari {{ $totalLabs ?? 0 }} praktikum coding dengan nilai KKM.</p>
            <button @click="showLabModal = false" class="text-sm text-blue-400 font-bold hover:underline">Tutup</button>
        </div>
    </div>
    
    <div x-show="showQuizModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-[#020617]/95 backdrop-blur-md" @click="showQuizModal = false"></div>
        <div class="relative w-full max-w-md bg-[#0f141e] border border-cyan-500/40 rounded-3xl p-8" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <h3 class="text-xl font-bold text-white mb-2">Statistik Kuis</h3>
            <p class="text-slate-400 text-sm mb-4">Rata-rata nilai evaluasi teori Anda adalah {{ round($quizAverage ?? 0, 1) }} poin.</p>
            <button @click="showQuizModal = false" class="text-sm text-cyan-400 font-bold hover:underline">Tutup</button>
        </div>
    </div>
    
    <div x-show="showChapterModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-[#020617]/95 backdrop-blur-md" @click="showChapterModal = false"></div>
        <div class="relative w-full max-w-md bg-[#0f141e] border border-emerald-500/40 rounded-3xl p-8" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <h3 class="text-xl font-bold text-white mb-2">Statistik Bab</h3>
            <p class="text-slate-400 text-sm mb-4">Anda telah menuntaskan {{ $chaptersPassed ?? 0 }} bab pembelajaran secara lengkap.</p>
            <button @click="showChapterModal = false" class="text-sm text-emerald-400 font-bold hover:underline">Tutup</button>
        </div>
    </div>

    {{-- MODAL EDIT DATA SISWA (ADMIN) --}}
    <div x-show="showEdit" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="fixed inset-0 z-[100] flex items-center justify-center p-4" style="display: none;" x-cloak>
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
                                @foreach($availableClasses ?? [] as $cls)
                                    <option value="{{ $cls->name }}" class="bg-[#0f141e] text-white" {{ trim($user->class_group) === trim($cls->name) ? 'selected' : '' }}>
                                        {{ $cls->name }} {{ $cls->major ? ' - '.$cls->major : '' }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-500"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg></div>
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
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow-[0_4px_15px_rgba(79,70,229,0.4)] transition-all flex items-center gap-2" :class="isSubmitting ? 'opacity-70 cursor-wait' : 'hover:bg-indigo-500 active:scale-95'" :disabled="isSubmitting">
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
    @if(session('success')) <script> document.addEventListener('DOMContentLoaded', () => { Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: "{{ session('success') }}", showConfirmButton: false, timer: 3000, background: '#0f141e', color: '#fff', iconColor: '#10b981' }); }); </script> @endif
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctxScore = document.getElementById('scoreChart');
            if(ctxScore && {!! json_encode($chartScores ?? []) !!}.length > 0) {
                const gradient = ctxScore.getContext('2d').createLinearGradient(0, 0, 0, 300);
                gradient.addColorStop(0, 'rgba(99, 102, 241, 0.3)'); gradient.addColorStop(1, 'rgba(99, 102, 241, 0)');
                new Chart(ctxScore, { type: 'line', data: { labels: {!! json_encode($chartLabels ?? []) !!}, datasets: [{ label: 'Nilai Praktik', data: {!! json_encode($chartScores ?? []) !!}, borderColor: '#818cf8', backgroundColor: gradient, borderWidth: 3, tension: 0.4, fill: true, pointBackgroundColor: '#0f141e', pointBorderColor: '#a5b4fc', pointBorderWidth: 2, pointRadius: 5 }] }, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, max: 100, grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#94a3b8' } }, x: { display: false } } } });
            }
            const ctxQuiz = document.getElementById('quizChart');
            if(ctxQuiz && {!! json_encode($chartData['scores'] ?? []) !!}.length > 0) {
                const gradientQ = ctxQuiz.getContext('2d').createLinearGradient(0, 0, 0, 300);
                gradientQ.addColorStop(0, 'rgba(232, 121, 249, 0.5)'); gradientQ.addColorStop(1, 'rgba(232, 121, 249, 0)');
                new Chart(ctxQuiz, { type: 'line', data: { labels: {!! json_encode($chartData['labels'] ?? []) !!}, datasets: [{ label: 'Nilai', data: {!! json_encode($chartData['scores'] ?? []) !!}, borderColor: '#e879f9', backgroundColor: gradientQ, borderWidth: 3, pointBackgroundColor: '#020617', pointBorderColor: '#fff', pointBorderWidth: 2, pointRadius: 6, fill: true, tension: 0.4 }] }, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: {display:false} }, scales: { x: {grid:{display:false}}, y: {beginAtZero:true, max:100, grid:{color:'rgba(255,255,255,0.05)'}} } } });
            }
            
            async function fetchAdminDashboardData() {
                try {
                    const response = await fetch("{{ route('api.dashboard.progress') }}?user_id={{ $user->id }}", { headers: { 'Accept': 'application/json' } });
                    if (!response.ok) throw new Error('API Error');
                    const data = await response.json();
                    
                    const el = document.getElementById('heatmap'); if(el) { el.innerHTML = ''; const map = {}; (data.activity_timeline || []).forEach(t => map[t.date] = t.count); for(let i=83; i>=0; i--) { const d = new Date(); d.setDate(d.getDate()-i); const k = d.toISOString().split('T')[0]; const v = map[k]||0; let c = 'bg-white/5'; if(v>=1) c='bg-cyan-500/40 shadow-[0_0_5px_#22d3ee]'; if(v>=3) c='bg-fuchsia-500 shadow-[0_0_8px_#d946ef]'; const div = document.createElement('div'); div.className = `w-2.5 h-2.5 rounded-[2px] ${c} relative cursor-pointer hover:scale-150 transition hover:z-20 hover:border hover:border-white`; div.setAttribute('title', `${k}: ${v} Aktivitas`); el.appendChild(div); } }
                    
                    const list = document.getElementById('activityLogList'); if(list) { list.innerHTML = ''; if ((data.activity_log || []).length === 0) { list.innerHTML = `<li class="text-white/30 text-center text-xs italic py-10">Belum ada aktivitas.</li>`; return; } (data.activity_log || []).forEach((item, index) => { let icon = '✓'; let iconBg = 'bg-cyan-500/10 text-cyan-400 border-cyan-500/20'; if (item.type === 'Kuis') { icon = '📝'; iconBg = 'bg-fuchsia-500/10 text-fuchsia-400 border-fuchsia-500/20'; } if (item.type === 'Lab')  { icon = '💻'; iconBg = 'bg-blue-500/10 text-blue-400 border-blue-500/20'; } list.insertAdjacentHTML('beforeend', `<li class="group flex items-center gap-3 p-3 rounded-xl bg-white/[0.02] hover:bg-white/[0.05] transition border border-white/5 animate-fade-in-up" style="animation-delay: ${index*100}ms"><div class="w-8 h-8 rounded-lg ${iconBg} border flex items-center justify-center shrink-0 font-bold text-xs shadow-inner">${icon}</div><div class="flex-1 min-w-0"><div class="flex justify-between items-center mb-0.5"><h4 class="text-xs font-bold text-white truncate w-24">${item.activity}</h4><span class="text-[9px] font-bold px-1.5 py-0.5 rounded ${item.status === 'Lulus' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-red-500/10 text-red-400'}">${item.status}</span></div><div class="flex justify-between items-center"><span class="text-[10px] text-white/30 font-mono">${item.time}</span></div></div></li>`); }); }
                } catch (e) { console.error(e); }
            }
            fetchAdminDashboardData();
        });
    </script>
</body>
</html>