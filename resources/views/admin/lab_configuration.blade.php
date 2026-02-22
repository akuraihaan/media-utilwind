<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lab Configuration & Management · Utilwind</title>
    
    {{-- RESOURCES --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

    <style>
        /* --- THEME CONFIG (CONSISTENT WITH DASHBOARD) --- */
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
        
        /* Container SVG Background Card */
        .card-bg-gfx { position: absolute; inset: 0; overflow: hidden; border-radius: 1rem; pointer-events: none; z-index: 0; }

        /* --- INPUTS & NAV --- */
        .glass-input { background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.1); color: white; transition: 0.3s; }
        .glass-input:focus { border-color: var(--accent); background: rgba(255, 255, 255, 0.05); outline: none; box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2); }
        .glass-input:read-only { opacity: 0.5; cursor: not-allowed; background: rgba(255,255,255,0.02); }

        .nav-link { display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 12px; color: #94a3b8; font-weight: 500; font-size: 0.9rem; transition: all 0.2s; border: 1px solid transparent; }
        .nav-link:hover { background: rgba(255, 255, 255, 0.03); color: white; }
        .nav-link.active { background: linear-gradient(90deg, rgba(99, 102, 241, 0.1) 0%, rgba(99, 102, 241, 0) 100%); color: #818cf8; border-left: 3px solid #818cf8; border-radius: 4px 12px 12px 4px; }

        .reveal { opacity: 0; transform: translateY(20px); animation: revealAnim 0.6s forwards ease-out; }
        @keyframes revealAnim { to { opacity: 1; transform: translateY(0); } }
        .table-row { transition: background 0.2s; border-bottom: 1px solid rgba(255,255,255,0.03); }
        .table-row:hover { background: rgba(255,255,255,0.05); }

        /* Mencegah scroll background saat modal aktif */
        .modal-open { overflow: hidden; padding-right: 5px; } 
    </style>
</head>
<body class="flex h-screen w-full" x-data="{ 
    sidebarOpen: false, 
    isFullscreen: false 
}" @keydown.escape.window="isFullscreen = false; document.exitFullscreen(); closeLabModal(); closeTaskManager();" :class="{'modal-open': sidebarOpen}">

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
                    <a href="{{ route('admin.labs.index') }}" class="nav-link active {{ request()->routeIs('admin.labs.index') ? 'active' : '' }}">
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

        {{-- ==================== MAIN CONTENT ==================== --}}
        <main class="flex-1 flex flex-col relative z-10 bg-[#020617] h-full overflow-y-auto overflow-x-hidden">
            
            {{-- Background FX (Identik Dashboard) --}}
            <div class="fixed inset-0 pointer-events-none z-0">
                <div class="absolute top-[10%] left-[20%] w-[500px] h-[500px] bg-indigo-600/10 rounded-full blur-[120px]"></div>
                <div class="absolute bottom-[10%] right-[10%] w-[400px] h-[400px] bg-fuchsia-600/10 rounded-full blur-[120px]"></div>
                <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.04] mix-blend-overlay"></div>
            </div>

            {{-- HEADER RESPONSIVE & BREADCRUMB IDENTIK --}}
            <header class="h-24 glass-header flex flex-col justify-center px-6 md:px-10 shrink-0 sticky top-0 z-40">
                <div class="flex items-center justify-between w-full">
                    <div class="flex items-center gap-4">
                        {{-- Hamburger --}}
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
                                            <span class="text-white">Lab Configuration</span>
                                        </div>
                                    </li>
                                </ol>
                            </nav>
                            <h2 class="text-white font-bold text-lg md:text-xl tracking-tight">Lab Ecosystem Control</h2>
                            <p class="text-[9px] md:text-xs text-white/40 flex items-center gap-1.5 mt-0.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse shadow-[0_0_8px_#10b981]"></span>
                                Practical Module Management
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3 sm:gap-6">
                        <button onclick="window.location.reload()" class="p-2.5 text-white/40 hover:text-white transition rounded-full hover:bg-white/5 group hidden sm:block border border-transparent hover:border-white/10" title="Refresh Data">
                            <svg class="w-4 h-4 transition-transform duration-500 group-hover:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        </button>
                        
                        <button @click="isFullscreen = !isFullscreen; isFullscreen ? document.documentElement.requestFullscreen() : document.exitFullscreen()" class="p-2.5 text-white/40 hover:text-white transition rounded-full hover:bg-white/5 hidden md:block border border-transparent hover:border-white/10" title="Fullscreen">
                            <svg x-show="!isFullscreen" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                            <svg x-show="isFullscreen" style="display: none;" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>

                        <div class="border-l border-white/10 pl-5 ml-1 hidden lg:block">
                            <button onclick="openLabModal()" class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow-[0_0_15px_rgba(99,102,241,0.3)] transition border border-indigo-400 transform hover:-translate-y-0.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg> Add Module
                            </button>
                        </div>
                        
                        <div class="text-right hidden lg:block border-l border-white/10 pl-5 ml-1">
                            <p class="text-sm font-bold text-white">{{ \Carbon\Carbon::now()->translatedFormat('d M Y') }}</p>
                            <p class="text-[10px] text-white/40 font-mono mt-0.5">{{ \Carbon\Carbon::now()->format('H:i') }} WIB</p>
                        </div>

                        {{-- Mobile Add Button --}}
                        <button onclick="openLabModal()" class="lg:hidden p-2 rounded-lg bg-indigo-600 text-white shadow-lg">
                             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        </button>
                    </div>
                </div>
            </header>

            {{-- CONTENT SCROLLABLE --}}
            <div class="flex-1 p-4 md:p-10 relative z-10">
                <div class="max-w-7xl mx-auto space-y-8">

                    {{-- HERO SECTION --}}
                    <div class="glass-card rounded-2xl p-6 md:p-10 overflow-hidden bg-gradient-to-r from-indigo-900/30 via-[#0f141e] to-purple-900/20 group reveal">
                        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.05] mix-blend-overlay"></div>
                        <div class="absolute -right-20 -top-20 w-80 h-80 bg-indigo-500/10 rounded-full blur-[100px] group-hover:bg-indigo-500/20 transition duration-1000"></div>
                        
                        <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                            <div>
                                <h1 class="text-3xl md:text-4xl font-black text-white tracking-tight mb-3">Lab Ecosystem <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-cyan-400">Control</span></h1>
                                <p class="text-indigo-200/60 max-w-xl text-xs md:text-sm leading-relaxed mb-6">
                                    Pusat manajemen modul praktikum. Tambahkan lab baru, atur durasi pengerjaan, dan kelola langkah-langkah (tasks) validasi secara terstruktur.
                                </p>
                                
                                <div class="flex items-center gap-3">
                                    <div class="flex items-center gap-2 px-4 py-2 rounded-xl bg-[#020617]/50 border border-white/10 shadow-inner">
                                        <span class="text-2xl font-bold text-white">{{ $totalLabs ?? 0 }}</span>
                                        <span class="text-[9px] text-white/40 uppercase tracking-widest font-bold">Total Modules</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SEARCH & TABLE SECTION --}}
                    <div class="glass-card rounded-2xl flex flex-col transition-all duration-300 z-10 reveal" style="animation-delay: 0.1s;">
                        
                        {{-- Controls & Search --}}
                        <div class="p-5 md:p-6 border-b border-white/5 bg-[#020617]/40 rounded-t-2xl flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                            <div>
                                <h3 class="text-base font-bold text-white flex items-center gap-2">
                                    <svg class="w-4 h-4 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg> 
                                    Lab Directory
                                </h3>
                            </div>
                            <div class="relative w-full sm:w-64 group">
                                <input id="searchLab" type="text" placeholder="Search by title or slug..." 
                                    class="w-full bg-[#0a0e17] border border-white/10 rounded-xl pl-9 pr-4 py-2.5 text-xs text-white focus:border-indigo-500 outline-none transition shadow-inner placeholder-white/20">
                                <div class="absolute left-3 top-2.5 text-white/30 group-focus-within:text-indigo-400 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </div>
                            </div>
                        </div>

                        {{-- Table --}}
                        <div class="overflow-x-auto custom-scrollbar relative p-0 sm:p-6 pt-0 border-t border-white/5 sm:border-none">
                            <table class="w-full text-sm text-left border-collapse min-w-[700px] border border-white/5 rounded-xl shadow-inner bg-[#0a0e17]/30">
                                <thead class="bg-[#0f141e] text-white/40 text-[10px] uppercase font-bold border-b border-white/5 sticky top-0 z-20 shadow-md">
                                    <tr>
                                        <th class="px-6 py-4 w-[35%] border-b border-white/5">Module Info</th>
                                        <th class="px-6 py-4 border-b border-white/5">Slug Identifier</th>
                                        <th class="px-6 py-4 text-center border-b border-white/5">Duration</th>
                                        <th class="px-6 py-4 text-center border-b border-white/5">Pass Grade</th>
                                        <th class="px-6 py-4 text-right border-b border-white/5">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/5 relative z-10" id="labTableBody">
                                    @forelse($labs ?? [] as $lab)
                                    <tr class="table-row group hover:bg-white/5 transition">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-4">
                                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center font-bold text-white text-sm shadow-inner border border-white/10 group-hover:shadow-[0_0_15px_rgba(99,102,241,0.5)] transition shrink-0">
                                                    {{ substr($lab->title, 0, 1) }}
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="font-bold text-white text-xs group-hover:text-indigo-300 transition truncate">{{ $lab->title }}</p>
                                                    <p class="text-[9px] text-white/40 mt-0.5 line-clamp-1">{{ $lab->description }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="font-mono text-[10px] text-indigo-300 bg-[#020617] border border-white/10 px-3 py-1.5 rounded-lg shadow-inner">{{ $lab->slug }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="text-white font-bold">{{ $lab->duration_minutes }}</span> <span class="text-[9px] text-white/40">mins</span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="px-2 py-1.5 rounded-lg bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-[9px] font-bold uppercase tracking-widest">Min {{ $lab->passing_grade }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex justify-end gap-2 opacity-60 group-hover:opacity-100 transition">
                                                <button onclick="openTaskManager({{ $lab->id }}, '{{ addslashes($lab->title) }}')" class="px-3 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white text-[10px] font-bold shadow-[0_0_15px_rgba(99,102,241,0.3)] transition flex items-center gap-1.5 border border-indigo-400">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg> Steps
                                                </button>
                                                <button onclick="openLabModal('edit', {{ htmlspecialchars(json_encode($lab), ENT_QUOTES, 'UTF-8') }})" class="p-1.5 rounded-lg bg-[#020617] hover:bg-amber-500 text-white/50 hover:text-white transition border border-white/10 hover:border-amber-400 shadow-inner" title="Edit">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                                </button>
                                                <button onclick="deleteLab({{ $lab->id }})" class="p-1.5 rounded-lg bg-[#020617] hover:bg-red-500 text-white/50 hover:text-white transition border border-white/10 hover:border-red-400 shadow-inner" title="Delete">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="5" class="py-20 text-center"><div class="flex flex-col items-center opacity-30"><div class="text-5xl mb-4 grayscale">📦</div><h3 class="text-sm font-bold text-white">No Labs Found</h3><p class="text-[10px] text-white/50">Start by clicking "Create New Module".</p></div></td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </main>
    </div>

    {{-- ==================== MODALS ==================== --}}

    {{-- 1. LAB MODAL (CREATE/EDIT) --}}
    <div id="labModal" class="fixed inset-0 z-[999999] hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-[#020617]/90 backdrop-blur-md transition-opacity" onclick="closeLabModal()"></div>
        <div id="labModalContent" class="relative w-full max-w-lg bg-[#0f141e] border border-white/10 rounded-3xl shadow-[0_20px_70px_rgba(0,0,0,0.9)] transform scale-95 opacity-0 transition-all duration-300 flex flex-col">
            <div class="p-5 md:p-6 border-b border-white/5 flex justify-between items-center bg-[#0a0e17] rounded-t-3xl">
                <h3 class="text-lg font-bold text-white flex items-center gap-2"><span class="p-1.5 bg-indigo-500/20 rounded-lg text-indigo-400 text-[9px] border border-indigo-500/30">MODULE</span> <span id="modalTitle">New Lab</span></h3>
                <button onclick="closeLabModal()" class="text-white/40 hover:text-white transition bg-white/5 p-1.5 rounded-full hover:bg-red-500/20 border border-transparent hover:border-red-500/30"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            <div class="p-5 md:p-6 space-y-5 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] relative">
                <div class="absolute inset-0 bg-[#0f141e]/95 mix-blend-overlay pointer-events-none"></div>
                <form id="labForm" class="relative z-10">
                    @csrf
                    <input type="hidden" id="labId" name="id">
                    <div><label class="text-[9px] font-bold text-white/50 uppercase tracking-widest mb-1.5 block">Lab Title</label><input type="text" id="labTitle" name="title" class="w-full glass-input rounded-xl px-4 py-3 text-sm outline-none shadow-inner" placeholder="e.g. Advanced CSS Layout" required></div>
                    <div class="mt-4"><label class="text-[9px] font-bold text-white/50 uppercase tracking-widest mb-1.5 block">Description</label><textarea id="labDesc" name="description" rows="3" class="w-full glass-input rounded-xl px-4 py-3 text-sm outline-none resize-none shadow-inner" placeholder="Brief objectives..."></textarea></div>
                    <div class="grid grid-cols-2 gap-4 mt-4">
                        <div><label class="text-[9px] font-bold text-white/50 uppercase tracking-widest mb-1.5 block">Duration (Mins)</label><input type="number" id="labDuration" name="duration" class="w-full glass-input rounded-xl px-4 py-3 text-sm outline-none shadow-inner" placeholder="60" value="60"></div>
                        <div><label class="text-[9px] font-bold text-white/50 uppercase tracking-widest mb-1.5 block">Passing Grade</label><input type="number" id="labGrade" name="passing_grade" class="w-full glass-input rounded-xl px-4 py-3 text-sm outline-none shadow-inner" placeholder="100" value="100"></div>
                    </div>
                </form>
            </div>
            <div class="p-5 md:p-6 border-t border-white/5 bg-[#0a0e17] flex justify-end gap-3 rounded-b-3xl">
                <button onclick="closeLabModal()" class="px-5 py-2.5 rounded-xl text-white/60 hover:text-white hover:bg-white/5 font-bold text-xs transition border border-transparent hover:border-white/10">Cancel</button>
                <button onclick="saveLab()" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs shadow-[0_0_15px_rgba(99,102,241,0.4)] border border-indigo-400 transition transform hover:-translate-y-0.5">Save Module</button>
            </div>
        </div>
    </div>

    {{-- 2. TASK MANAGER MODAL --}}
    <div id="taskModal" class="fixed inset-0 z-[999999] hidden flex items-center justify-center p-4 md:p-10">
        <div class="absolute inset-0 bg-[#020617]/95 backdrop-blur-md transition-opacity" onclick="closeTaskManager()"></div>
        <div id="taskContent" class="relative w-full max-w-6xl bg-[#0f141e] border border-white/10 rounded-3xl shadow-[0_30px_100px_rgba(0,0,0,1)] transform scale-95 opacity-0 transition-all duration-300 flex flex-col h-full max-h-[90vh]">
            
            <div class="p-5 md:p-6 border-b border-white/5 flex justify-between items-center bg-[#0a0e17] rounded-t-3xl shrink-0">
                <div>
                    <h3 class="text-lg md:text-xl font-bold text-white flex items-center gap-2"><span class="p-1.5 bg-indigo-500/20 rounded-lg border border-indigo-500/30 text-indigo-400 text-[10px] tracking-widest">STEPS MANAGER</span> <span id="modalLabTitle">Lab Title</span></h3>
                    <p class="text-[10px] text-white/40 mt-1 font-mono">Configure step-by-step instructions and validations.</p>
                </div>
                <button onclick="closeTaskManager()" class="text-white/40 hover:text-white transition bg-white/5 p-2 rounded-full hover:bg-red-500/20 border border-transparent hover:border-red-500/30"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>

            <div class="flex flex-col md:flex-row flex-1 overflow-hidden">
                {{-- LEFT: LIST STEPS --}}
                <div class="w-full md:w-1/3 border-b md:border-b-0 md:border-r border-white/5 flex flex-col bg-[#0a0e17]/50 h-64 md:h-auto shrink-0">
                    <div class="p-4 border-b border-white/5 bg-[#05080f]/50"><h4 class="text-[10px] font-bold text-white/50 uppercase tracking-widest">Existing Steps</h4></div>
                    <div id="taskListContainer" class="flex-1 overflow-y-auto custom-scrollbar p-4 space-y-2"></div>
                </div>

                {{-- RIGHT: FORM --}}
                <div class="w-full md:w-2/3 flex flex-col bg-[url('https://grainy-gradients.vercel.app/noise.svg')] relative flex-1">
                    <div class="absolute inset-0 bg-[#0f141e]/95 mix-blend-overlay pointer-events-none z-0"></div>
                    <div class="p-4 border-b border-white/5 bg-[#05080f]/50 relative z-10 shrink-0"><h4 class="text-[10px] font-bold text-white/50 uppercase tracking-widest">Step Editor</h4></div>
                    <div class="flex-1 overflow-y-auto custom-scrollbar p-5 md:p-8 relative z-10">
                        <form id="taskForm">
                            @csrf
                            <input type="hidden" name="lab_id" id="taskLabId">
                            <input type="hidden" name="id" id="taskId">

                            <div class="grid grid-cols-1 sm:grid-cols-6 gap-4 mb-5">
                                <div class="col-span-1 sm:col-span-5"><label class="text-[9px] font-bold text-white/50 uppercase tracking-widest mb-1.5 block">Step Title</label><input type="text" name="title" class="w-full glass-input rounded-xl px-4 py-3 text-sm outline-none shadow-inner" required></div>
                                <div class="col-span-1"><label class="text-[9px] font-bold text-white/50 uppercase tracking-widest mb-1.5 block">Index</label><input type="number" name="order_index" id="taskOrderIndex" class="w-full glass-input rounded-xl px-4 py-3 text-sm outline-none text-center font-bold text-indigo-400 bg-[#020617]" readonly></div>
                            </div>
                            
                            <div class="mb-5"><label class="text-[9px] font-bold text-white/50 uppercase tracking-widest mb-1.5 block">Instruction (Markdown)</label><textarea name="instruction" rows="3" class="w-full glass-input rounded-xl px-4 py-3 text-sm outline-none resize-none shadow-inner" required></textarea></div>
                            
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-5">
                                <div><label class="text-[9px] font-bold text-white/50 uppercase tracking-widest mb-1.5 block">Initial Code</label><textarea name="initial_code" rows="7" class="w-full glass-input rounded-xl px-4 py-3 text-[11px] outline-none font-mono bg-[#020617] leading-relaxed shadow-inner" required></textarea></div>
                                <div>
                                    <label class="text-[9px] font-bold text-white/50 uppercase tracking-widest mb-1.5 block">Validation Rules (Comma Separated)</label>
                                    <textarea name="validation_rules" rows="7" class="w-full glass-input rounded-xl px-4 py-3 text-[11px] outline-none font-mono bg-[#020617] leading-relaxed shadow-inner" placeholder='"bg-red-500", "p-4"' required></textarea>
                                    <p class="text-[9px] text-white/30 mt-1 font-mono">Example: flex, justify-between, bg-red-500</p>
                                </div>
                            </div>
                            
                            <div><label class="text-[9px] font-bold text-white/50 uppercase tracking-widest mb-1.5 block">Points</label><input type="number" name="points" class="w-32 glass-input rounded-xl px-4 py-3 text-sm outline-none shadow-inner" value="25" required></div>
                            
                            {{-- BUTTON GROUP --}}
                            <div class="p-4 border border-white/10 bg-[#0a0e17] flex justify-between items-center mt-6 rounded-xl shadow-inner">
                                <button type="button" onclick="resetTaskForm()" class="text-[10px] font-bold text-white/40 hover:text-white transition px-3 py-2 hover:bg-white/5 rounded-lg border border-transparent hover:border-white/10">Clear / New Step</button>
                                <button type="button" onclick="submitTask()" id="btnSaveTask" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs transition shadow-[0_0_15px_rgba(99,102,241,0.4)] border border-indigo-400 transform hover:-translate-y-0.5">Save Step Configuration</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPT LOGIC --}}
    <script>
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

        // --- 1. LAB CRUD ---
        function openLabModal(mode = 'create', data = null) {
            const modal = document.getElementById('labModal');
            const content = document.getElementById('labModalContent');
            const title = document.getElementById('modalTitle');
            document.getElementById('labForm').reset();

            if (mode === 'edit' && data) {
                title.innerText = 'Edit Module';
                $('#labId').val(data.id);
                $('#labTitle').val(data.title);
                $('#labDesc').val(data.description);
                $('#labDuration').val(data.duration_minutes);
                $('#labGrade').val(data.passing_grade);
            } else { title.innerText = 'New Module'; $('#labId').val(''); }

            modal.classList.remove('hidden');
            setTimeout(() => { content.classList.remove('scale-95', 'opacity-0'); content.classList.add('scale-100', 'opacity-100'); }, 10);
        }
        function closeLabModal() {
            const modal = document.getElementById('labModal');
            const content = document.getElementById('labModalContent');
            content.classList.remove('scale-100', 'opacity-100'); content.classList.add('scale-95', 'opacity-0');
            setTimeout(() => { modal.classList.add('hidden'); }, 300);
        }
        function saveLab() {
            const form = $('#labForm');
            const id = $('#labId').val();
            let url = "{{ route('admin.labs.store') }}";
            let formData = form.serialize();
            if (id) { url = `/admin/labs/${id}`; formData += "&_method=PUT"; }

            Swal.fire({ title: 'Processing...', didOpen: () => { Swal.showLoading() }, background: '#0f141e', color: '#fff' });
            
            $.ajax({
                url: url, type: "POST", data: formData, 
                success: function(res) {
                    Swal.fire({ title: 'Success!', text: 'Module saved successfully.', icon: 'success', background: '#0f141e', color: '#fff', confirmButtonColor: '#6366f1' }).then(() => { closeLabModal(); location.reload(); });
                },
                error: function() { Swal.fire({ title: 'Error!', text: 'Failed to save module.', icon: 'error', background: '#0f141e', color: '#fff', confirmButtonColor: '#ef4444' }); }
            });
        }
        function deleteLab(id) {
            Swal.fire({ title: 'Delete Module?', text: "This cannot be undone!", icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#334155', confirmButtonText: 'Yes, Delete!', cancelButtonText: 'Cancel', background: '#0f141e', color: '#fff' }).then((result) => {
                if (result.isConfirmed) { $.ajax({ url: `/admin/labs/${id}`, type: 'DELETE', success: function() { location.reload(); } }); }
            });
        }

        // --- 2. TASK MANAGER ---
        let currentLabId = null;

        function openTaskManager(labId, labTitle) {
            currentLabId = labId;
            $('#modalLabTitle').text(labTitle);
            $('#taskLabId').val(labId);
            resetTaskForm(); 
            $('#taskModal').removeClass('hidden');
            setTimeout(() => { $('#taskContent').removeClass('scale-95 opacity-0').addClass('scale-100 opacity-100'); }, 10);
        }
        function closeTaskManager() {
            $('#taskContent').removeClass('scale-100 opacity-100').addClass('scale-95 opacity-0');
            setTimeout(() => { $('#taskModal').addClass('hidden'); }, 300);
        }

        function loadTasks(labId) {
            $('#taskListContainer').html('<div class="text-center py-10 text-indigo-400 text-[10px] tracking-widest uppercase font-bold animate-pulse">Loading Data...</div>');
            $.get(`/admin/labs/${labId}/tasks`, function(tasks) {
                let nextIndex = 1;
                if(tasks.length > 0) { const maxOrder = Math.max(...tasks.map(t => t.order_index)); nextIndex = maxOrder + 1; }
                if(!$('#taskId').val()) { $('#taskOrderIndex').val(nextIndex); }

                if(tasks.length === 0) { $('#taskListContainer').html('<div class="text-center py-10 text-white/30 text-xs italic bg-white/5 rounded-xl border border-dashed border-white/10 m-2">No steps created yet.</div>'); return; }
                
                let html = '';
                tasks.forEach(task => {
                    // Safe injection handling strings with quotes
                    const taskJson = JSON.stringify(task).replace(/"/g, '&quot;');
                    html += `
                    <div class="p-3.5 rounded-xl bg-white/5 border border-transparent hover:border-white/10 transition group relative mb-2 flex justify-between items-start shadow-inner">
                        <div>
                            <span class="text-[9px] font-bold text-indigo-400 bg-indigo-500/10 px-2 py-0.5 rounded border border-indigo-500/20">Step ${task.order_index}</span>
                            <h5 class="text-xs font-bold text-white mt-1.5 line-clamp-1">${task.title}</h5>
                            <p class="text-[10px] text-white/40 mt-1 font-mono">${task.points} Pts</p>
                        </div>
                        <div class="flex gap-1 md:opacity-0 group-hover:opacity-100 transition duration-300">
                            <button onclick="editTask(${taskJson})" class="bg-[#020617] rounded-md hover:bg-amber-500 text-white/40 hover:text-white transition p-1.5 border border-white/10 hover:border-amber-400" title="Edit"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                            <button onclick="deleteTask(${task.id})" class="bg-[#020617] rounded-md hover:bg-red-500 text-white/40 hover:text-white transition p-1.5 border border-white/10 hover:border-red-400" title="Delete"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                        </div>
                    </div>`;
                });
                $('#taskListContainer').html(html);
            });
        }

        function editTask(task) {
            $('#taskId').val(task.id);
            $('#taskLabId').val(task.lab_id);
            $('#taskOrderIndex').val(task.order_index);
            $('[name="title"]').val(task.title);
            $('[name="instruction"]').val(task.instruction);
            $('[name="initial_code"]').val(task.initial_code);
            $('[name="points"]').val(task.points);
            
            let rules = task.validation_rules;
            if (typeof rules !== 'string') { rules = JSON.parse(rules).join(', '); } 
            $('[name="validation_rules"]').val(rules.replace(/[\[\]"]/g, ''));

            $('#btnSaveTask').text('Update Step').removeClass('bg-indigo-600 hover:bg-indigo-500 border-indigo-400 shadow-[0_0_15px_rgba(99,102,241,0.4)]').addClass('bg-amber-600 hover:bg-amber-500 border-amber-400 shadow-[0_0_15px_rgba(217,119,6,0.4)]');
        }

        function resetTaskForm() {
            const labId = $('#taskLabId').val();
            $('#taskForm')[0].reset();
            $('#taskId').val(''); 
            $('#taskLabId').val(labId); 
            $('#btnSaveTask').text('Save Step Configuration').removeClass('bg-amber-600 hover:bg-amber-500 border-amber-400 shadow-[0_0_15px_rgba(217,119,6,0.4)]').addClass('bg-indigo-600 hover:bg-indigo-500 border-indigo-400 shadow-[0_0_15px_rgba(99,102,241,0.4)]');
            loadTasks(labId); 
        }

        function submitTask() {
            const form = $('#taskForm');
            if(!form[0].checkValidity()) { form[0].reportValidity(); return; }

            const id = $('#taskId').val();
            const labId = $('#taskLabId').val();
            let url = "{{ route('admin.labs.tasks.store') }}";
            let formData = form.serialize();
            
            if (id) { url = `/admin/labs/tasks/${id}`; formData += "&_method=PUT"; }

            $.ajax({
                url: url, type: "POST", data: formData,
                success: function(res) {
                    const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, background: '#0f141e', color: '#fff', iconColor: '#10b981' });
                    Toast.fire({ icon: 'success', title: id ? "Step updated successfully" : "Step added successfully" });
                    resetTaskForm();
                },
                error: function() { Swal.fire({ title: 'Error!', text: 'Failed to save task.', icon: 'error', background: '#0f141e', color: '#fff', confirmButtonColor: '#ef4444' }); }
            });
        }

        function deleteTask(id) {
            Swal.fire({ title: 'Delete Step?', text: "Permanent deletion!", icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#334155', confirmButtonText: 'Yes, Delete', background: '#0f141e', color: '#fff' }).then((result) => {
                if (result.isConfirmed) { $.ajax({ url: `/admin/labs/tasks/${id}`, type: 'DELETE', success: function() { loadTasks(currentLabId); } }); }
            });
        }

        // --- SEARCH ---
        $('#searchLab').on('keyup', function() {
            const val = $(this).val().toLowerCase();
            $('#labTableBody tr').filter(function() { $(this).toggle($(this).text().toLowerCase().indexOf(val) > -1) });
        });

    </script>

</body>
</html>