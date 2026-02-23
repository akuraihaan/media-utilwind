<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <title>Class & Token Management · Utilwind Admin</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700;800&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root { --glass-bg: rgba(10, 14, 23, 0.85); --glass-border: rgba(255, 255, 255, 0.08); --accent: #6366f1; }
        body { font-family: 'Inter', sans-serif; background-color: #020617; color: #e2e8f0; overflow-x: hidden; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
        .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: var(--accent); }
        .glass-sidebar { background: rgba(5, 8, 16, 0.95); backdrop-filter: blur(20px); border-right: 1px solid var(--glass-border); z-index: 50; }
        .glass-header { background: rgba(2, 6, 23, 0.85); backdrop-filter: blur(12px); border-bottom: 1px solid var(--glass-border); z-index: 40; }
        .glass-card { background: var(--glass-bg); border: 1px solid var(--glass-border); box-shadow: 0 4px 30px rgba(0, 0, 0, 0.2); backdrop-filter: blur(10px); transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); position: relative; overflow: visible !important; z-index: 10; }
        .glass-card:hover { border-color: rgba(99, 102, 241, 0.4); transform: translateY(-3px); box-shadow: 0 10px 40px -10px rgba(0,0,0,0.5); z-index: 30; }
        .glass-input { background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.1); color: white; transition: 0.3s; }
        .glass-input:focus { border-color: var(--accent); background: rgba(255, 255, 255, 0.05); outline: none; box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2); }
        .nav-link { display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 12px; color: #94a3b8; font-weight: 500; font-size: 0.875rem; transition: all 0.2s; border: 1px solid transparent; }
        .nav-link:hover { background: rgba(255, 255, 255, 0.03); color: white; }
        .nav-link.active { background: linear-gradient(90deg, rgba(99, 102, 241, 0.1) 0%, rgba(99, 102, 241, 0) 100%); color: #818cf8; border-left: 3px solid #818cf8; border-radius: 4px 12px 12px 4px; }
        .reveal { opacity: 0; transform: translateY(15px); animation: revealAnim 0.5s forwards ease-out; }
        @keyframes revealAnim { to { opacity: 1; transform: translateY(0); } }
        .table-row { transition: background 0.2s; border-bottom: 1px solid rgba(255,255,255,0.03); }
        .table-row:hover { background: rgba(255,255,255,0.05); }
        [x-cloak] { display: none !important; }
        .token-blur { filter: blur(4px); transition: filter 0.3s; user-select: none; }
        .group\/token:hover .token-blur { filter: blur(0); user-select: auto; text-shadow: 0 0 10px rgba(99,102,241,0.8); }
    </style>
</head>
<body x-data="{ 
    sidebarOpen: false, 
    isFullscreen: false,
    showAddModal: false,
    showEditModal: false,
    showInsightModal: false,
    editData: { id: '', name: '', major: '', is_active: 1 },
    insightData: {},
    
    openEdit(item) { this.editData = { ...item }; this.showEditModal = true; },
    openInsight(item) { this.insightData = item; this.showInsightModal = true; },
    copyToken(token) { navigator.clipboard.writeText(token); Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Token Disalin!', showConfirmButton: false, timer: 1500, background: '#0f141e', color: '#fff', iconColor: '#10b981' }); },
    confirmRegenerate(id) { Swal.fire({ title: 'Regenerate Token?', text: 'Token lama akan hangus.', icon: 'warning', showCancelButton: true, confirmButtonColor: '#eab308', background: '#0f141e', color: '#fff' }).then((r) => { if (r.isConfirmed) document.getElementById('form-token-'+id).submit(); }) },
    confirmDelete(id) { Swal.fire({ title: 'Hapus Kelas?', text: 'Data kelas dihapus permanen.', icon: 'error', showCancelButton: true, confirmButtonColor: '#ef4444', background: '#0f141e', color: '#fff' }).then((r) => { if (r.isConfirmed) document.getElementById('form-delete-'+id).submit(); }) }
}" @keydown.escape.window="showAddModal = false; showEditModal = false; showInsightModal = false; isFullscreen = false; document.exitFullscreen();" :class="{'overflow-hidden': showAddModal || showEditModal || showInsightModal || sidebarOpen}">

    <div class="flex h-screen w-full relative">

        {{-- ==================== SIDEBAR ==================== --}}
        <div x-show="sidebarOpen" class="fixed inset-0 bg-[#020617]/80 backdrop-blur-sm z-[90] md:hidden" @click="sidebarOpen = false" x-transition.opacity style="display: none;"></div>

        <aside class="glass-sidebar w-72 h-full flex flex-col fixed md:relative z-[100] transition-transform duration-300 transform md:translate-x-0" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            <div class="h-24 flex items-center justify-between px-8 border-b border-white/5 relative overflow-hidden group">
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-20 h-20 bg-indigo-500/20 rounded-full blur-[40px] opacity-0 group-hover:opacity-100 transition duration-500"></div>
                <a href="{{ route('landing') ?? '#' }}" class="flex items-center gap-3 relative z-10">
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
                        <a href="{{ route('admin.dashboard') }}" class="nav-link">
                            <svg class="w-5 h-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg> Dashboard
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
                        <a href="{{ route('admin.lab.analytics') }}" class="nav-link">
                            <svg class="w-5 h-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg> Lab Analytics
                        </a>
                        <a href="{{ route('admin.classes.index') }}" class="nav-link active mt-2 pt-3 border-t border-white/5">
                            <svg class="w-5 h-5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg> Class Management
                        </a>
                    </div>
                </div>
            </nav>

            <div class="p-4 border-t border-white/5 bg-[#05080f]/50">
                <div class="flex items-center gap-3 mb-4 px-2">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-fuchsia-500 flex items-center justify-center font-bold text-white text-xs border border-white/10 shadow-lg">AD</div>
                    <div class="overflow-hidden">
                        <p class="text-xs font-bold text-white truncate">{{ Auth::user()->name ?? 'Administrator' }}</p>
                        <p class="text-[10px] text-white/40 truncate">System Authority</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500 hover:text-white transition text-xs font-bold border border-red-500/20 hover:border-red-500 group">
                        <svg class="w-3.5 h-3.5 transition group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg> Logout
                    </button>
                </form>
            </div>
        </aside>

        {{-- ==================== MAIN CONTENT ==================== --}}
        <main class="flex-1 flex flex-col relative z-10 bg-[#020617] h-full overflow-y-auto overflow-x-hidden">
            
            <div class="fixed inset-0 pointer-events-none z-0">
                <div class="absolute top-[10%] left-[20%] w-[500px] h-[500px] bg-indigo-600/10 rounded-full blur-[120px]"></div>
                <div class="absolute bottom-[10%] right-[10%] w-[400px] h-[400px] bg-cyan-600/5 rounded-full blur-[120px]"></div>
                <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.04] mix-blend-overlay"></div>
            </div>

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
                                    <li><div class="flex items-center"><svg class="w-3 h-3 text-white/30 mx-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg><span class="text-white">Class Management</span></div></li>
                                </ol>
                            </nav>
                            <h2 class="text-white font-bold text-lg md:text-xl tracking-tight">Class & Access Tokens</h2>
                            <p class="text-[9px] md:text-xs text-white/40 flex items-center gap-1.5 mt-0.5"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse shadow-[0_0_8px_#10b981]"></span> Security Access Control</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3 sm:gap-6">
                        <button onclick="window.location.reload()" class="p-2.5 text-white/40 hover:text-white transition rounded-full hover:bg-white/5 hidden sm:block border border-transparent hover:border-white/10" title="Refresh">
                            <svg class="w-4 h-4 group-hover:rotate-180 transition-transform duration-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        </button>
                        <button @click="isFullscreen = !isFullscreen; isFullscreen ? document.documentElement.requestFullscreen() : document.exitFullscreen()" class="p-2.5 text-white/40 hover:text-white transition rounded-full hover:bg-white/5 hidden md:block border border-transparent hover:border-white/10" title="Fullscreen">
                            <svg x-show="!isFullscreen" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                            <svg x-show="isFullscreen" style="display: none;" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                        <div class="border-l border-white/10 pl-5 ml-1">
                            <button @click="showAddModal = true" class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow-[0_0_15px_rgba(99,102,241,0.3)] transition border border-indigo-400">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg> 
                                <span class="hidden sm:inline">Add Class</span>
                            </button>

                            
                        </div>
                         <div class="text-right hidden lg:block border-l border-white/10 pl-5 ml-1">
                        <p class="text-sm font-bold text-white">{{ \Carbon\Carbon::now()->translatedFormat('d M Y') }}</p>
                        <p class="text-[10px] text-white/40 font-mono mt-0.5">{{ \Carbon\Carbon::now()->format('H:i') }} WIB</p>
                    </div>
                    </div>
                </div>
            </header>

            <div class="flex-1 p-6 md:p-10 relative z-10">
                <div class="max-w-7xl mx-auto space-y-8 md:space-y-12">
                    
                    {{-- 3 STATS CARDS --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 reveal">
                        <div class="glass-card rounded-2xl p-6 border-l-4 border-l-indigo-500">
                            <p class="text-[10px] font-bold text-white/40 uppercase tracking-widest">Total Classes</p>
                            <h3 class="text-3xl md:text-4xl font-black text-white mt-2">{{ $totalClasses ?? 0 }}</h3>
                        </div>
                        <div class="glass-card rounded-2xl p-6 border-l-4 border-l-emerald-500">
                            <p class="text-[10px] font-bold text-white/40 uppercase tracking-widest">Active Tokens</p>
                            <h3 class="text-3xl md:text-4xl font-black text-emerald-400 mt-2 drop-shadow-[0_0_8px_#10b981]">{{ $totalActive ?? 0 }}</h3>
                        </div>
                        <div class="glass-card rounded-2xl p-6 border-l-4 border-l-cyan-500">
                            <p class="text-[10px] font-bold text-white/40 uppercase tracking-widest">Students Connected</p>
                            <h3 class="text-3xl md:text-4xl font-black text-cyan-400 mt-2">{{ $totalStudents ?? 0 }}</h3>
                        </div>
                    </div>

                    {{-- MAIN TABLE --}}
                    <div class="glass-card rounded-2xl overflow-hidden reveal" style="animation-delay: 0.2s">
                        <div class="p-6 border-b border-white/5 bg-white/[0.02]">
                            <h3 class="text-lg font-bold text-white flex items-center gap-2">Database Kelas</h3>
                            <p class="text-xs text-white/40 mt-1">Kelola kelas, bagikan token akses, dan analisis performa siswa secara mendalam.</p>
                        </div>
                        
                        <div class="overflow-x-auto custom-scrollbar min-h-[300px]">
                            <table class="w-full text-sm text-left">
                                <thead class="bg-[#0f141e] text-white/40 text-[10px] uppercase font-bold border-b border-white/5">
                                    <tr>
                                        <th class="px-6 py-4">Nama Kelas / Jurusan</th>
                                        <th class="px-6 py-4 text-center">Students</th>
                                        <th class="px-6 py-4 text-center">Avg Quiz</th>
                                        <th class="px-6 py-4 text-center">Token Akses</th>
                                        <th class="px-6 py-4 text-center">Status</th>
                                        <th class="px-6 py-4 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/5 bg-[#0a0e17]/30">
                                    @forelse($classes ?? [] as $class)
                                    <tr class="table-row group">
                                        <td class="px-6 py-4">
                                            <p class="text-sm font-bold text-white">{{ $class['name'] ?? $class->name }}</p>
                                            <p class="text-[10px] text-white/40 mt-0.5">{{ $class['major'] ?? $class->major ?? 'Umum' }}</p>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="px-2.5 py-1 rounded bg-[#020617] text-xs font-black text-slate-300 border border-white/5 shadow-inner">{{ $class['students_count'] ?? 0 }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="font-bold {{ ($class['avg_quiz'] ?? 0) >= 70 ? 'text-emerald-400' : 'text-red-400' }}">
                                                {{ $class['avg_quiz'] ?? 0 }} pts
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center group/token relative cursor-pointer" @click="copyToken('{{ $class['token'] ?? $class->token }}')" title="Klik untuk copy">
                                            <div class="inline-flex items-center justify-center bg-[#020617] border border-white/10 rounded-lg px-4 py-2 shadow-inner group-hover/token:border-indigo-500/50 transition">
                                                <span class="font-mono text-lg font-black text-indigo-400 tracking-[0.3em] token-blur group-hover/token:text-white transition-colors">{{ $class['token'] ?? $class->token }}</span>
                                                <svg class="w-3 h-3 absolute right-3 opacity-0 group-hover/token:opacity-100 text-indigo-400 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if(($class['is_active'] ?? $class->is_active))
                                                <span class="px-2.5 py-1 rounded text-[9px] font-bold uppercase tracking-widest border bg-emerald-500/10 text-emerald-400 border-emerald-500/20">Active</span>
                                            @else
                                                <span class="px-2.5 py-1 rounded text-[9px] font-bold uppercase tracking-widest border bg-slate-800 text-slate-400 border-slate-700">Closed</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex justify-end gap-2">
                                                {{-- TOMBOL INSIGHT (DETAIL) --}}
                                                <button @click="openInsight({{ is_array($class) ? json_encode($class) : collect($class)->toJson() }})" class="p-2 rounded-lg bg-cyan-500/10 text-cyan-400 hover:bg-cyan-500 hover:text-white transition shadow-inner border border-transparent hover:border-cyan-400" title="Detail Insight"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg></button>

                                                <form id="form-token-{{ $class['id'] ?? $class->id }}" action="{{ route('admin.classes.token', $class['id'] ?? $class->id) }}" method="POST">
                                                    @csrf <button type="button" @click="confirmRegenerate({{ $class['id'] ?? $class->id }})" class="p-2 rounded-lg bg-white/5 text-yellow-500 hover:bg-yellow-500 hover:text-black transition shadow-inner border border-transparent hover:border-yellow-400" title="Regenerate Token"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg></button>
                                                </form>
                                                <button @click="openEdit({{ is_array($class) ? collect($class)->only(['id','name','major','is_active'])->toJson() : collect($class)->only(['id','name','major','is_active'])->toJson() }})" class="p-2 rounded-lg bg-white/5 text-indigo-400 hover:bg-indigo-500 hover:text-white transition shadow-inner border border-transparent hover:border-indigo-400" title="Edit"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                                                <form id="form-delete-{{ $class['id'] ?? $class->id }}" action="{{ route('admin.classes.destroy', $class['id'] ?? $class->id) }}" method="POST">
                                                    @csrf @method('DELETE') <button type="button" @click="confirmDelete({{ $class['id'] ?? $class->id }})" class="p-2 rounded-lg bg-white/5 text-red-400 hover:bg-red-500 hover:text-white transition shadow-inner border border-transparent hover:border-red-400" title="Hapus"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="6" class="py-20 text-center text-slate-500 text-xs italic bg-white/[0.01]">Belum ada kelas yang dibuat di sistem.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    {{-- ==================== MODALS PENGELOLAAN KELAS & INSIGHT ==================== --}}

    {{-- MODAL HERO INSIGHT (ANALITIK MENDALAM DENGAN PROGRESS BAR) --}}
    <div x-show="showInsightModal" class="fixed inset-0 z-[300] flex items-center justify-center p-4" x-cloak>
        <div class="absolute inset-0 bg-[#020617]/90 backdrop-blur-md" @click="showInsightModal = false"></div>
        
        {{-- Diperlebar menjadi max-w-6xl agar tabel data siswa muat sempurna --}}
        <div class="relative w-full max-w-6xl max-h-[90vh] flex flex-col bg-[#0f141e] border border-cyan-500/30 rounded-3xl shadow-[0_20px_70px_rgba(6,182,212,0.15)] overflow-hidden" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            
            {{-- Header Insight Modal --}}
            <div class="p-6 md:p-8 border-b border-white/10 flex justify-between items-start shrink-0 bg-white/[0.02]">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-cyan-500/20 text-cyan-400 flex items-center justify-center border border-cyan-500/30 shadow-[0_0_15px_rgba(6,182,212,0.2)]">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-white" x-text="insightData.name"></h2>
                        <p class="text-sm text-cyan-400 font-mono tracking-wider font-bold mt-1" x-text="'TOKEN KELAS: ' + (insightData.token || 'N/A')"></p>
                    </div>
                </div>
                <button @click="showInsightModal = false" class="text-slate-500 hover:text-white transition p-2 rounded-xl bg-white/5 hover:bg-red-500/20"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>

            {{-- Body Insight Modal --}}
            <div class="p-6 md:p-8 overflow-y-auto custom-scrollbar flex-1 space-y-8 bg-[#020617]">
                
                {{-- Insight Grid 3 Kotak --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-[#0a0e17] rounded-2xl p-6 border border-white/5 shadow-inner hover:border-indigo-500/30 transition">
                        <p class="text-[10px] font-bold text-white/40 uppercase tracking-widest mb-2">Total Siswa Aktif</p>
                        <h3 class="text-4xl font-black text-indigo-400 drop-shadow-[0_0_8px_rgba(99,102,241,0.5)]" x-text="insightData.students_count || 0"></h3>
                    </div>
                    <div class="bg-[#0a0e17] rounded-2xl p-6 border border-white/5 shadow-inner hover:border-fuchsia-500/30 transition">
                        <p class="text-[10px] font-bold text-white/40 uppercase tracking-widest mb-2">Rata-rata Evaluasi Kelas</p>
                        <div class="flex items-baseline gap-1">
                            <h3 class="text-4xl font-black text-fuchsia-400 drop-shadow-[0_0_8px_rgba(217,70,239,0.5)]" x-text="insightData.avg_quiz || 0"></h3>
                            <span class="text-white/40 text-xs font-bold">pts</span>
                        </div>
                    </div>
                    <div class="bg-[#0a0e17] rounded-2xl p-6 border border-white/5 shadow-inner hover:border-blue-500/30 transition">
                        <p class="text-[10px] font-bold text-white/40 uppercase tracking-widest mb-2">Total Lab Diselesaikan</p>
                        <h3 class="text-4xl font-black text-blue-400 drop-shadow-[0_0_8px_rgba(59,130,246,0.5)]" x-text="insightData.lab_passes || 0"></h3>
                    </div>
                </div>

                {{-- Tabel Leaderboard Siswa & Progress (Advanced) --}}
                <div>
                    <h3 class="text-sm font-bold text-white uppercase tracking-widest mb-4 flex items-center gap-2 border-l-4 border-cyan-500 pl-3">Daftar Siswa & Detail Progres</h3>
                    <div class="bg-[#0a0e17] border border-white/10 rounded-2xl overflow-hidden shadow-[0_10px_30px_rgba(0,0,0,0.5)]">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-white/[0.03] border-b border-white/10">
                                <tr>
                                    <th class="px-6 py-4 text-[10px] text-white/40 uppercase tracking-widest font-bold w-16 text-center">Rank</th>
                                    <th class="px-6 py-4 text-[10px] text-white/40 uppercase tracking-widest font-bold">Profil Siswa</th>
                                    <th class="px-6 py-4 text-[10px] text-white/40 uppercase tracking-widest font-bold w-[25%]">Global Progress</th>
                                    <th class="px-6 py-4 text-[10px] text-white/40 uppercase tracking-widest font-bold">Detail Pencapaian</th>
                                    <th class="px-6 py-4 text-[10px] text-white/40 uppercase tracking-widest font-bold text-right">Avg Quiz</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                <template x-if="insightData.students_list && insightData.students_list.length > 0">
                                    <template x-for="(student, index) in insightData.students_list" :key="index">
                                        <tr class="hover:bg-white/[0.02] transition">
                                            {{-- RANKING --}}
                                            <td class="px-6 py-4 text-center align-middle">
                                                <span class="w-7 h-7 rounded-full inline-flex items-center justify-center text-xs font-black shadow-inner"
                                                      :class="index === 0 ? 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/50 shadow-[0_0_10px_rgba(234,179,8,0.3)]' : (index === 1 ? 'bg-slate-300/20 text-slate-300 border border-slate-300/50' : (index === 2 ? 'bg-amber-700/20 text-amber-600 border border-amber-700/50' : 'bg-white/5 text-slate-500'))"
                                                      x-text="index + 1"></span>
                                            </td>
                                            
                                            {{-- PROFILE --}}
                                            <td class="px-6 py-4 align-middle">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-fuchsia-500 flex items-center justify-center font-bold text-white text-xs shadow-lg" x-text="student.name.charAt(0)"></div>
                                                    <div>
                                                        <p class="font-bold text-white text-sm" x-text="student.name"></p>
                                                        <p class="text-[10px] text-white/40 font-mono mt-0.5" x-text="student.email"></p>
                                                    </div>
                                                </div>
                                            </td>
                                            
                                            {{-- PROGRESS BAR --}}
                                            <td class="px-6 py-4 align-middle">
                                                <div class="w-full">
                                                    <div class="flex justify-between items-center mb-1.5">
                                                        <span class="text-[10px] font-bold text-white/50 uppercase tracking-widest">Selesai</span>
                                                        <span class="text-xs font-black text-indigo-400" x-text="student.progress_pct + '%'"></span>
                                                    </div>
                                                    <div class="w-full bg-white/5 rounded-full h-2 overflow-hidden border border-white/5">
                                                        <div class="bg-gradient-to-r from-indigo-500 to-cyan-400 h-2 rounded-full transition-all duration-1000 shadow-[0_0_10px_rgba(99,102,241,0.5)]" :style="`width: ${student.progress_pct}%`"></div>
                                                    </div>
                                                </div>
                                            </td>

                                            {{-- BADGES PENCAPAIAN --}}
                                            <td class="px-6 py-4 align-middle">
                                                <div class="flex flex-wrap gap-2">
                                                    <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded bg-fuchsia-500/10 text-fuchsia-400 border border-fuchsia-500/20 text-[10px] font-bold" title="Materi Dibaca">
                                                        <span>📚</span> <span x-text="student.lessons_done"></span>
                                                    </span>
                                                    <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded bg-blue-500/10 text-blue-400 border border-blue-500/20 text-[10px] font-bold" title="Lab Lulus">
                                                        <span>💻</span> <span x-text="student.labs_done"></span>
                                                    </span>
                                                    <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded bg-cyan-500/10 text-cyan-400 border border-cyan-500/20 text-[10px] font-bold" title="Kuis Lulus">
                                                        <span>📝</span> <span x-text="student.quizzes_passed"></span>
                                                    </span>
                                                </div>
                                            </td>

                                            {{-- AVG QUIZ SCORE --}}
                                            <td class="px-6 py-4 text-right align-middle">
                                                <span class="px-3 py-1.5 rounded-lg text-sm font-black border" 
                                                      :class="student.avg_score >= 70 ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20 shadow-[0_0_10px_rgba(16,185,129,0.2)]' : 'bg-red-500/10 text-red-400 border-red-500/20'" 
                                                      x-text="student.avg_score + ' pts'"></span>
                                            </td>
                                        </tr>
                                    </template>
                                </template>
                                <template x-if="!insightData.students_list || insightData.students_list.length === 0">
                                    <tr>
                                        <td colspan="5" class="px-6 py-16 text-center text-white/30 text-xs italic bg-white/[0.01]">Belum ada siswa yang tergabung di kelas ini. Bagikan token untuk mengundang mereka.</td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL ADD CLASS --}}
    <div x-show="showAddModal" class="fixed inset-0 z-[200] flex items-center justify-center p-4" x-cloak>
        <div class="absolute inset-0 bg-[#020617]/90 backdrop-blur-sm" @click="showAddModal = false"></div>
        <div class="relative w-full max-w-md bg-[#0f141e] border border-indigo-500/30 rounded-3xl p-6 md:p-8 shadow-[0_20px_70px_rgba(99,102,241,0.15)]" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-3">
                <div class="p-2 rounded-lg bg-indigo-500/20 text-indigo-400 border border-indigo-500/30">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg> 
                </div>
                Buat Kelas Baru
            </h3>
            
            <form action="{{ route('admin.classes.store') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-2">Nama Kelas <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required placeholder="Contoh: XII RPL 1" class="w-full glass-input rounded-xl px-4 py-3 text-sm focus:ring-2 ring-indigo-500/20 shadow-inner">
                </div>
                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-2">Jurusan / Info Tambahan</label>
                    <input type="text" name="major" placeholder="Contoh: Rekayasa Perangkat Lunak" class="w-full glass-input rounded-xl px-4 py-3 text-sm focus:ring-2 ring-indigo-500/20 shadow-inner">
                </div>
                <div class="bg-indigo-500/10 border border-indigo-500/20 rounded-xl p-4 mt-2 shadow-inner">
                    <p class="text-xs text-indigo-300 flex items-start gap-2 leading-relaxed">
                        <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Token akses unik (6 karakter) akan dibuatkan oleh sistem secara otomatis setelah Anda menekan Simpan.
                    </p>
                </div>
                <div class="flex justify-end gap-3 pt-4 border-t border-white/5 mt-6">
                    <button type="button" @click="showAddModal = false" class="px-5 py-2.5 rounded-xl text-slate-400 hover:text-white font-bold text-xs transition border border-transparent hover:border-white/10">Batal</button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs shadow-[0_0_15px_rgba(99,102,241,0.4)] transition border border-indigo-400">Simpan Kelas</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL EDIT CLASS --}}
    <div x-show="showEditModal" class="fixed inset-0 z-[200] flex items-center justify-center p-4" x-cloak>
        <div class="absolute inset-0 bg-[#020617]/90 backdrop-blur-sm" @click="showEditModal = false"></div>
        <div class="relative w-full max-w-md bg-[#0f141e] border border-indigo-500/30 rounded-3xl p-6 md:p-8 shadow-[0_20px_70px_rgba(99,102,241,0.15)]" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-3">
                <div class="p-2 rounded-lg bg-indigo-500/20 text-indigo-400 border border-indigo-500/30">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg> 
                </div>
                Edit Kelas
            </h3>

            <form :action="`/admin/classes/${editData.id}`" method="POST" class="space-y-5">
                @csrf @method('PUT')
                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-2">Nama Kelas <span class="text-red-500">*</span></label>
                    <input type="text" name="name" x-model="editData.name" required class="w-full glass-input rounded-xl px-4 py-3 text-sm focus:ring-2 ring-indigo-500/20 shadow-inner">
                </div>
                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-2">Jurusan / Info Tambahan</label>
                    <input type="text" name="major" x-model="editData.major" class="w-full glass-input rounded-xl px-4 py-3 text-sm focus:ring-2 ring-indigo-500/20 shadow-inner">
                </div>
                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-2">Status Pendaftaran</label>
                    <select name="is_active" x-model="editData.is_active" class="w-full glass-input rounded-xl px-4 py-3 text-sm focus:ring-2 ring-indigo-500/20 cursor-pointer shadow-inner">
                        <option value="1" class="bg-[#0f141e] text-emerald-400">Active (Menerima Siswa)</option>
                        <option value="0" class="bg-[#0f141e] text-red-400">Closed (Terkunci)</option>
                    </select>
                </div>
                <div class="flex justify-end gap-3 pt-4 border-t border-white/5 mt-6">
                    <button type="button" @click="showEditModal = false" class="px-5 py-2.5 rounded-xl text-slate-400 hover:text-white font-bold text-xs transition border border-transparent hover:border-white/10">Batal</button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs shadow-[0_0_15px_rgba(99,102,241,0.4)] transition border border-indigo-400">Update Kelas</button>
                </div>
            </form>
        </div>
    </div>

    @if(session('success')) <script> document.addEventListener('DOMContentLoaded', () => { Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: "{{ session('success') }}", showConfirmButton: false, timer: 3500, background: '#0f141e', color: '#fff', iconColor: '#10b981' }); }); </script> @endif
    @if(session('error')) <script> document.addEventListener('DOMContentLoaded', () => { Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: "{{ session('error') }}", showConfirmButton: false, timer: 4000, background: '#0f141e', color: '#fff', iconColor: '#ef4444' }); }); </script> @endif

</body>
</html>