<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <title>Class & Token Management · Utilwind Admin</title>
    
    {{-- RESOURCES --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700;800&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
        
        .glass-input { background: var(--input-bg); border: 1px solid var(--input-border); color: var(--text-main); transition: 0.3s; }
        .glass-input:focus { border-color: var(--accent); outline: none; box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2); }
        .dark .glass-input:focus { border-color: #818cf8; box-shadow: 0 0 0 2px rgba(129, 140, 248, 0.2); }
        
        .nav-link { display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 12px; color: var(--nav-text); font-weight: 500; font-size: 0.875rem; transition: all 0.2s; border: 1px solid transparent; }
        .nav-link:hover { background: var(--nav-hover-bg); color: var(--text-main); }
        .nav-link.active { background: linear-gradient(90deg, rgba(99, 102, 241, 0.1) 0%, rgba(99, 102, 241, 0) 100%); color: #818cf8; border-left: 3px solid #818cf8; border-radius: 4px 12px 12px 4px; }
        html:not(.dark) .nav-link.active { color: #6366f1; border-left-color: #6366f1; }

        .reveal { opacity: 0; transform: translateY(15px); animation: revealAnim 0.5s forwards ease-out; }
        @keyframes revealAnim { to { opacity: 1; transform: translateY(0); } }
        
        .table-row { transition: background 0.2s; border-bottom: 1px solid var(--glass-border); }
        .table-row:hover { background: var(--table-hover); }
        [x-cloak] { display: none !important; }

        /* --- TOKEN BLUR EFFECT & HOVER UI --- */
        .token-blur { filter: blur(5px); transition: filter 0.4s ease-in-out, text-shadow 0.3s; user-select: none; }
        .group\/token:hover .token-blur { filter: blur(0); user-select: auto; text-shadow: 0 0 12px rgba(99,102,241,0.5); }
        .dark .group\/token:hover .token-blur { text-shadow: 0 0 15px rgba(99,102,241,0.9); }
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
    
    // Helper Tema untuk SweetAlert agar responsif Light/Dark
    getSwalTheme() {
        const isDark = document.documentElement.classList.contains('dark');
        return {
            bg: isDark ? '#0f141e' : '#ffffff',
            color: isDark ? '#fff' : '#1e293b',
            cancelBg: isDark ? '#334155' : '#e2e8f0',
        };
    },

    openEdit(item) { this.editData = { ...item }; this.showEditModal = true; },
    openInsight(item) { this.insightData = item; this.showInsightModal = true; },
    copyToken(token) { 
        navigator.clipboard.writeText(token); 
        const t = this.getSwalTheme();
        Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Token berhasil disalin!', showConfirmButton: false, timer: 2000, background: t.bg, color: t.color, iconColor: '#10b981' }); 
    },
    confirmRegenerate(id) { 
        const t = this.getSwalTheme();
        Swal.fire({ title: 'Regenerate Token?', text: 'Token yang lama akan tidak berlaku lagi.', icon: 'warning', showCancelButton: true, confirmButtonColor: '#eab308', cancelButtonColor: t.cancelBg, background: t.bg, color: t.color }).then((r) => { if (r.isConfirmed) document.getElementById('form-token-'+id).submit(); }) 
    },
    confirmDelete(id) { 
        const t = this.getSwalTheme();
        Swal.fire({ title: 'Hapus Kelas?', text: 'Semua data terkait kelas ini akan dihapus.', icon: 'error', showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: t.cancelBg, background: t.bg, color: t.color }).then((r) => { if (r.isConfirmed) document.getElementById('form-delete-'+id).submit(); }) 
    }
}" @keydown.escape.window="showAddModal = false; showEditModal = false; showInsightModal = false; isFullscreen = false; document.exitFullscreen();" :class="{'overflow-hidden': showAddModal || showEditModal || showInsightModal || sidebarOpen}">

    <div class="flex h-screen w-full relative">

        {{-- ==================== SIDEBAR ==================== --}}
        <div x-show="sidebarOpen" class="fixed inset-0 bg-slate-900/60 dark:bg-[#020617]/80 backdrop-blur-sm z-[90] md:hidden transition-opacity" @click="sidebarOpen = false" x-transition.opacity style="display: none;" x-cloak></div>
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
                <div class="absolute top-[10%] left-[20%] w-[500px] h-[500px] bg-indigo-300/30 dark:bg-indigo-600/10 rounded-full blur-[120px] transition-colors duration-500"></div>
                <div class="absolute bottom-[10%] right-[10%] w-[400px] h-[400px] bg-cyan-300/30 dark:bg-cyan-600/5 rounded-full blur-[120px] transition-colors duration-500"></div>
                <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.02] dark:opacity-[0.04] mix-blend-overlay transition-opacity"></div>
            </div>

            {{-- HEADER RESPONSIVE & BREADCRUMB --}}
            <header class="h-24 glass-header flex flex-col justify-center px-6 md:px-10 shrink-0 sticky top-0 z-40 transition-colors">
                <div class="flex items-center justify-between w-full">
                    <div class="flex items-center gap-4">
                        <button @click="sidebarOpen = true" class="md:hidden p-2 bg-slate-200 dark:bg-white/5 rounded-lg text-slate-700 dark:text-white hover:bg-slate-300 dark:hover:bg-white/10 transition-colors shadow-sm dark:shadow-none">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        </button>
                        <div>
                            <nav class="flex text-[10px] text-slate-500 dark:text-white/50 mb-1.5 font-bold hidden sm:flex transition-colors" aria-label="Breadcrumb">
                                <ol class="inline-flex items-center space-x-1">
                                    <li class="inline-flex items-center"><a href="{{ route('admin.dashboard') ?? '#' }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Dashboard</a></li>
                                    <li><div class="flex items-center"><svg class="w-3 h-3 text-slate-400 dark:text-white/30 mx-1 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg><span class="text-slate-900 dark:text-white transition-colors">Class Management</span></div></li>
                                </ol>
                            </nav>
                            <h2 class="text-slate-900 dark:text-white font-bold text-lg md:text-xl tracking-tight transition-colors">Class & Access Tokens</h2>
                            <p class="text-[9px] md:text-xs text-slate-500 dark:text-white/40 flex items-center gap-1.5 mt-0.5 transition-colors"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse shadow-[0_0_8px_#10b981]"></span> Security Access Control</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3 sm:gap-6">
                        <button onclick="window.location.reload()" class="p-2.5 text-slate-500 dark:text-white/40 hover:text-slate-900 dark:hover:text-white transition-colors rounded-full hover:bg-slate-200 dark:hover:bg-white/5 hidden sm:block border border-transparent dark:hover:border-white/10" title="Refresh">
                            <svg class="w-4 h-4 group-hover:rotate-180 transition-transform duration-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        </button>
                        <button @click="isFullscreen = !isFullscreen; isFullscreen ? document.documentElement.requestFullscreen() : document.exitFullscreen()" class="p-2.5 text-slate-500 dark:text-white/40 hover:text-slate-900 dark:hover:text-white transition-colors rounded-full hover:bg-slate-200 dark:hover:bg-white/5 hidden md:block border border-transparent dark:hover:border-white/10" title="Fullscreen">
                            <svg x-show="!isFullscreen" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                            <svg x-show="isFullscreen" style="display: none;" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                        <div class="border-l border-slate-300 dark:border-white/10 pl-5 ml-1 transition-colors">
                            <button @click="showAddModal = true" class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow-md dark:shadow-[0_0_15px_rgba(99,102,241,0.3)] transition border border-indigo-500 dark:border-indigo-400">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg> 
                                <span class="hidden sm:inline">Add Class</span>
                            </button>
                        </div>
                        <div class="text-right hidden lg:block border-l border-slate-300 dark:border-white/10 pl-5 ml-1 transition-colors">
                            <p class="text-sm font-bold text-slate-900 dark:text-white transition-colors">{{ \Carbon\Carbon::now()->translatedFormat('d M Y') }}</p>
                            <p class="text-[10px] text-slate-500 dark:text-white/40 font-mono mt-0.5 transition-colors">{{ \Carbon\Carbon::now()->format('H:i') }} WIB</p>
                        </div>
                    </div>
                </div>
            </header>

            <div class="flex-1 p-6 md:p-10 relative z-10">
                <div class="max-w-7xl mx-auto space-y-8 md:space-y-12">
                    
                    {{-- 3 STATS CARDS --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 md:gap-6 reveal">
                        <div class="glass-card rounded-2xl p-6 border-l-4 border-l-indigo-500 transition-colors">
                            <p class="text-[10px] font-bold text-slate-500 dark:text-white/40 uppercase tracking-widest transition-colors">Total Classes</p>
                            <h3 class="text-3xl md:text-4xl font-black text-slate-900 dark:text-white mt-2 transition-colors">{{ $totalClasses ?? 0 }}</h3>
                        </div>
                        <div class="glass-card rounded-2xl p-6 border-l-4 border-l-emerald-500 transition-colors">
                            <p class="text-[10px] font-bold text-slate-500 dark:text-white/40 uppercase tracking-widest transition-colors">Active Tokens</p>
                            <h3 class="text-3xl md:text-4xl font-black text-emerald-600 dark:text-emerald-400 mt-2 drop-shadow-sm dark:drop-shadow-[0_0_8px_#10b981] transition-colors">{{ $totalActive ?? 0 }}</h3>
                        </div>
                        <div class="glass-card rounded-2xl p-6 border-l-4 border-l-cyan-500 transition-colors">
                            <p class="text-[10px] font-bold text-slate-500 dark:text-white/40 uppercase tracking-widest transition-colors">Students Connected</p>
                            <h3 class="text-3xl md:text-4xl font-black text-cyan-600 dark:text-cyan-400 mt-2 transition-colors">{{ $totalStudents ?? 0 }}</h3>
                        </div>
                    </div>

                    {{-- MAIN TABLE --}}
                    <div class="glass-card rounded-2xl overflow-hidden reveal flex flex-col transition-colors" style="animation-delay: 0.2s">
                        <div class="p-6 border-b border-slate-200 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.02] shrink-0 transition-colors">
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2 transition-colors">Database Kelas</h3>
                            <p class="text-xs text-slate-500 dark:text-white/40 mt-1 transition-colors">Kelola kelas, bagikan token akses, dan analisis performa siswa secara mendalam.</p>
                        </div>
                        
                        <div class="overflow-x-auto overflow-y-auto custom-scrollbar max-h-[500px] relative">
                            <table class="w-full text-sm text-left">
                                <thead class="bg-slate-100 dark:bg-[#0f141e] text-slate-500 dark:text-white/40 text-[10px] uppercase font-bold sticky top-0 z-20 shadow-sm dark:shadow-md transition-colors after:absolute after:inset-x-0 after:bottom-0 after:border-b after:border-slate-200 dark:after:border-white/5">
                                    <tr>
                                        <th class="px-6 py-4 transition-colors">Nama Kelas / Jurusan</th>
                                        <th class="px-6 py-4 text-center transition-colors">Students</th>
                                        <th class="px-6 py-4 text-center transition-colors">Avg Quiz</th>
                                        <th class="px-6 py-4 text-center transition-colors w-48">Token Akses</th>
                                        <th class="px-6 py-4 text-center transition-colors">Status</th>
                                        <th class="px-6 py-4 text-right transition-colors">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200 dark:divide-white/5 bg-white/50 dark:bg-[#0a0e17]/30 transition-colors">
                                    @forelse($classes ?? [] as $class)
                                    <tr class="table-row group transition-colors">
                                        <td class="px-6 py-4">
                                            <p class="text-sm font-bold text-slate-900 dark:text-white transition-colors">{{ $class['name'] ?? $class->name }}</p>
                                            <p class="text-[10px] text-slate-500 dark:text-white/40 mt-0.5 transition-colors">{{ $class['major'] ?? $class->major ?? 'Umum' }}</p>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="px-2.5 py-1 rounded bg-slate-100 dark:bg-[#020617] text-xs font-black text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-white/5 shadow-sm dark:shadow-inner transition-colors">{{ $class['students_count'] ?? 0 }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="font-bold transition-colors {{ ($class['avg_quiz'] ?? 0) >= 70 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                                                {{ $class['avg_quiz'] ?? 0 }} pts
                                            </span>
                                        </td>
                                        
                                        {{-- DETAIL IMPROVISASI TOKEN HOVER --}}
                                        <td class="px-6 py-4 text-center relative group/tooltip" title="Hover to reveal">
                                            <div class="group/token inline-flex flex-col items-center justify-center cursor-pointer transition-transform transform hover:scale-105" @click="copyToken('{{ $class['token'] ?? $class->token }}')">
                                                <div class="relative flex items-center justify-center bg-indigo-50 dark:bg-[#020617] border border-indigo-200 dark:border-white/10 rounded-lg px-4 py-2 shadow-sm dark:shadow-inner group-hover/token:border-indigo-400 dark:group-hover/token:border-indigo-500/50 transition-colors overflow-hidden">
                                                    
                                                    {{-- Token Text (Blurred normally) --}}
                                                    <span class="font-mono text-lg font-black text-indigo-600 dark:text-indigo-400 tracking-[0.3em] token-blur group-hover/token:text-slate-900 dark:group-hover/token:text-white transition-colors relative z-10">
                                                        {{ $class['token'] ?? $class->token }}
                                                    </span>

                                                    {{-- Icon Copy (Appears on Hover) --}}
                                                    <div class="absolute right-2 opacity-0 group-hover/token:opacity-100 text-indigo-600 dark:text-indigo-400 transition-opacity z-20">
                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                                    </div>
                                                </div>
                                                
                                                {{-- Helper Text Bawah Token --}}
                                                <span class="text-[8px] font-bold text-slate-400 dark:text-white/30 uppercase tracking-widest mt-1.5 opacity-0 group-hover/token:opacity-100 transition-opacity absolute -bottom-3 whitespace-nowrap">
                                                    Click to Copy
                                                </span>
                                            </div>
                                        </td>

                                        <td class="px-6 py-4 text-center">
                                            @if(($class['is_active'] ?? $class->is_active))
                                                <span class="px-2.5 py-1 rounded text-[9px] font-bold uppercase tracking-widest border bg-emerald-100 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border-emerald-200 dark:border-emerald-500/20 transition-colors">Active</span>
                                            @else
                                                <span class="px-2.5 py-1 rounded text-[9px] font-bold uppercase tracking-widest border bg-slate-200 dark:bg-slate-800 text-slate-500 dark:text-slate-400 border-slate-300 dark:border-slate-700 transition-colors">Closed</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex justify-end gap-2">
                                                {{-- TOMBOL INSIGHT (DETAIL) --}}
                                                <button @click="openInsight({{ is_array($class) ? json_encode($class) : collect($class)->toJson() }})" class="p-2 rounded-lg bg-cyan-100 dark:bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 hover:bg-cyan-500 dark:hover:bg-cyan-500 hover:text-white transition-colors shadow-sm dark:shadow-inner border border-transparent hover:border-cyan-400" title="Detail Insight"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg></button>

                                                <form id="form-token-{{ $class['id'] ?? $class->id }}" action="{{ route('admin.classes.token', $class['id'] ?? $class->id) ?? '#' }}" method="POST">
                                                    @csrf <button type="button" @click="confirmRegenerate({{ $class['id'] ?? $class->id }})" class="p-2 rounded-lg bg-white dark:bg-white/5 text-amber-500 dark:text-yellow-500 hover:bg-amber-500 dark:hover:bg-yellow-500 hover:text-white dark:hover:text-black transition-colors shadow-sm dark:shadow-inner border border-slate-200 dark:border-transparent hover:border-amber-400 dark:hover:border-yellow-400" title="Regenerate Token"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg></button>
                                                </form>
                                                <button @click="openEdit({{ is_array($class) ? collect($class)->only(['id','name','major','is_active'])->toJson() : collect($class)->only(['id','name','major','is_active'])->toJson() }})" class="p-2 rounded-lg bg-white dark:bg-white/5 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-600 dark:hover:bg-indigo-500 hover:text-white transition-colors shadow-sm dark:shadow-inner border border-slate-200 dark:border-transparent hover:border-indigo-400" title="Edit"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                                                <form id="form-delete-{{ $class['id'] ?? $class->id }}" action="{{ route('admin.classes.destroy', $class['id'] ?? $class->id) ?? '#' }}" method="POST">
                                                    @csrf @method('DELETE') <button type="button" @click="confirmDelete({{ $class['id'] ?? $class->id }})" class="p-2 rounded-lg bg-white dark:bg-white/5 text-red-500 dark:text-red-400 hover:bg-red-500 hover:text-white transition-colors shadow-sm dark:shadow-inner border border-slate-200 dark:border-transparent hover:border-red-400" title="Hapus"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="6" class="py-20 text-center text-slate-500 dark:text-white/30 text-xs italic bg-slate-50/50 dark:bg-white/[0.01] transition-colors">Belum ada kelas yang dibuat di sistem.</td></tr>
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
        <div class="absolute inset-0 bg-slate-900/80 dark:bg-[#020617]/90 backdrop-blur-md transition-colors" @click="showInsightModal = false"></div>
        
        <div class="relative w-full max-w-6xl max-h-[90vh] flex flex-col bg-white dark:bg-[#0f141e] border border-cyan-200 dark:border-cyan-500/30 rounded-3xl shadow-xl dark:shadow-[0_20px_70px_rgba(6,182,212,0.15)] overflow-hidden transition-colors" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            
            {{-- Header Insight Modal --}}
            <div class="p-6 md:p-8 border-b border-slate-200 dark:border-white/10 flex justify-between items-start shrink-0 bg-slate-50/50 dark:bg-white/[0.02] transition-colors">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-cyan-100 dark:bg-cyan-500/20 text-cyan-600 dark:text-cyan-400 flex items-center justify-center border border-cyan-200 dark:border-cyan-500/30 shadow-sm dark:shadow-[0_0_15px_rgba(6,182,212,0.2)] transition-colors">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-slate-900 dark:text-white transition-colors" x-text="insightData.name"></h2>
                        <p class="text-sm text-cyan-600 dark:text-cyan-400 font-mono tracking-wider font-bold mt-1 transition-colors" x-text="'TOKEN KELAS: ' + (insightData.token || 'N/A')"></p>
                    </div>
                </div>
                <button @click="showInsightModal = false" class="text-slate-400 hover:text-slate-900 dark:text-slate-500 dark:hover:text-white transition-colors p-2 rounded-xl bg-slate-100 dark:bg-white/5 hover:bg-red-100 dark:hover:bg-red-500/20 border border-transparent hover:border-red-200 dark:hover:border-red-500/30"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>

            {{-- Body Insight Modal --}}
            <div class="p-6 md:p-8 overflow-y-auto custom-scrollbar flex-1 space-y-8 bg-white dark:bg-[#020617] transition-colors">
                
                {{-- Insight Grid 3 Kotak --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-slate-50 dark:bg-[#0a0e17] rounded-2xl p-6 border border-slate-200 dark:border-white/5 shadow-sm dark:shadow-inner hover:border-indigo-300 dark:hover:border-indigo-500/30 transition-colors">
                        <p class="text-[10px] font-bold text-slate-500 dark:text-white/40 uppercase tracking-widest mb-2 transition-colors">Total Siswa Aktif</p>
                        <h3 class="text-4xl font-black text-indigo-600 dark:text-indigo-400 drop-shadow-sm dark:drop-shadow-[0_0_8px_rgba(99,102,241,0.5)] transition-colors" x-text="insightData.students_count || 0"></h3>
                    </div>
                    <div class="bg-slate-50 dark:bg-[#0a0e17] rounded-2xl p-6 border border-slate-200 dark:border-white/5 shadow-sm dark:shadow-inner hover:border-fuchsia-300 dark:hover:border-fuchsia-500/30 transition-colors">
                        <p class="text-[10px] font-bold text-slate-500 dark:text-white/40 uppercase tracking-widest mb-2 transition-colors">Rata-rata Evaluasi Kelas</p>
                        <div class="flex items-baseline gap-1">
                            <h3 class="text-4xl font-black text-fuchsia-600 dark:text-fuchsia-400 drop-shadow-sm dark:drop-shadow-[0_0_8px_rgba(217,70,239,0.5)] transition-colors" x-text="insightData.avg_quiz || 0"></h3>
                            <span class="text-slate-400 dark:text-white/40 text-xs font-bold transition-colors"></span>
                        </div>
                    </div>
                    <div class="bg-slate-50 dark:bg-[#0a0e17] rounded-2xl p-6 border border-slate-200 dark:border-white/5 shadow-sm dark:shadow-inner hover:border-blue-300 dark:hover:border-blue-500/30 transition-colors">
                        <p class="text-[10px] font-bold text-slate-500 dark:text-white/40 uppercase tracking-widest mb-2 transition-colors">Total Lab Diselesaikan</p>
                        <h3 class="text-4xl font-black text-blue-600 dark:text-blue-400 drop-shadow-sm dark:drop-shadow-[0_0_8px_rgba(59,130,246,0.5)] transition-colors" x-text="insightData.lab_passes || 0"></h3>
                    </div>
                </div>

                {{-- Tabel Leaderboard Siswa & Progress (Advanced) --}}
                <div>
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-widest mb-4 flex items-center gap-2 border-l-4 border-cyan-500 pl-3 transition-colors">Daftar Siswa & Detail Progres</h3>
                    <div class="bg-slate-50 dark:bg-[#0a0e17] border border-slate-200 dark:border-white/10 rounded-2xl overflow-hidden shadow-sm dark:shadow-[0_10px_30px_rgba(0,0,0,0.5)] transition-colors">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-slate-100 dark:bg-white/[0.03] border-b border-slate-200 dark:border-white/10 transition-colors">
                                <tr>
                                    <th class="px-6 py-4 text-[10px] text-slate-500 dark:text-white/40 uppercase tracking-widest font-bold w-16 text-center transition-colors">Rank</th>
                                    <th class="px-6 py-4 text-[10px] text-slate-500 dark:text-white/40 uppercase tracking-widest font-bold transition-colors">Profil Siswa</th>
                                    <th class="px-6 py-4 text-[10px] text-slate-500 dark:text-white/40 uppercase tracking-widest font-bold w-[25%] transition-colors">Global Progress</th>
                                    <th class="px-6 py-4 text-[10px] text-slate-500 dark:text-white/40 uppercase tracking-widest font-bold transition-colors">Detail Pencapaian</th>
                                    <th class="px-6 py-4 text-[10px] text-slate-500 dark:text-white/40 uppercase tracking-widest font-bold text-right transition-colors">Avg Quiz</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-white/5 transition-colors">
                                <template x-if="insightData.students_list && insightData.students_list.length > 0">
                                    <template x-for="(student, index) in insightData.students_list" :key="index">
                                        <tr class="hover:bg-white dark:hover:bg-white/[0.02] transition-colors">
                                            {{-- RANKING --}}
                                            <td class="px-6 py-4 text-center align-middle">
                                                <span class="w-7 h-7 rounded-full inline-flex items-center justify-center text-xs font-black shadow-inner transition-colors"
                                                      :class="index === 0 ? 'bg-amber-100 dark:bg-yellow-500/20 text-amber-600 dark:text-yellow-400 border border-amber-300 dark:border-yellow-500/50 dark:shadow-[0_0_10px_rgba(234,179,8,0.3)]' : (index === 1 ? 'bg-slate-200 dark:bg-slate-300/20 text-slate-600 dark:text-slate-300 border border-slate-300 dark:border-slate-300/50' : (index === 2 ? 'bg-orange-100 dark:bg-amber-700/20 text-orange-600 dark:text-amber-600 border border-orange-300 dark:border-amber-700/50' : 'bg-slate-100 dark:bg-white/5 text-slate-500'))"
                                                      x-text="index + 1"></span>
                                            </td>
                                            
                                            {{-- PROFILE --}}
                                            <td class="px-6 py-4 align-middle">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-fuchsia-500 flex items-center justify-center font-bold text-white text-xs shadow-lg" x-text="student.name.charAt(0)"></div>
                                                    <div>
                                                        <p class="font-bold text-slate-900 dark:text-white text-sm transition-colors" x-text="student.name"></p>
                                                        <p class="text-[10px] text-slate-500 dark:text-white/40 font-mono mt-0.5 transition-colors" x-text="student.email"></p>
                                                    </div>
                                                </div>
                                            </td>
                                            
                                            {{-- PROGRESS BAR --}}
                                            <td class="px-6 py-4 align-middle">
                                                <div class="w-full">
                                                    <div class="flex justify-between items-center mb-1.5">
                                                        <span class="text-[10px] font-bold text-slate-500 dark:text-white/50 uppercase tracking-widest transition-colors">Selesai</span>
                                                        <span class="text-xs font-black text-indigo-600 dark:text-indigo-400 transition-colors" x-text="student.progress_pct + '%'"></span>
                                                    </div>
                                                    <div class="w-full bg-slate-200 dark:bg-white/5 rounded-full h-2 overflow-hidden border border-slate-300 dark:border-white/5 transition-colors">
                                                        <div class="bg-gradient-to-r from-indigo-500 to-cyan-400 h-2 rounded-full transition-all duration-1000 dark:shadow-[0_0_10px_rgba(99,102,241,0.5)]" :style="`width: ${student.progress_pct}%`"></div>
                                                    </div>
                                                </div>
                                            </td>

                                            {{-- BADGES PENCAPAIAN --}}
                                            <td class="px-6 py-4 align-middle">
                                                <div class="flex flex-wrap gap-2">
                                                    <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded bg-fuchsia-50 dark:bg-fuchsia-500/10 text-fuchsia-600 dark:text-fuchsia-400 border border-fuchsia-200 dark:border-fuchsia-500/20 text-[10px] font-bold transition-colors" title="Materi Dibaca">
                                                        <span>📚</span> <span x-text="student.lessons_done"></span>
                                                    </span>
                                                    <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-blue-500/20 text-[10px] font-bold transition-colors" title="Lab Lulus">
                                                        <span>💻</span> <span x-text="student.labs_done"></span>
                                                    </span>
                                                    <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded bg-cyan-50 dark:bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 border border-cyan-200 dark:border-cyan-500/20 text-[10px] font-bold transition-colors" title="Kuis Lulus">
                                                        <span>📝</span> <span x-text="student.quizzes_passed"></span>
                                                    </span>
                                                </div>
                                            </td>

                                            {{-- AVG QUIZ SCORE --}}
                                            <td class="px-6 py-4 text-right align-middle">
                                                <span class="px-3 py-1.5 rounded-lg text-sm font-black border transition-colors" 
                                                      :class="student.avg_score >= 70 ? 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border-emerald-200 dark:border-emerald-500/20 dark:shadow-[0_0_10px_rgba(16,185,129,0.2)]' : 'bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 border-red-200 dark:border-red-500/20'" 
                                                      x-text="student.avg_score + ' pts'"></span>
                                            </td>
                                        </tr>
                                    </template>
                                </template>
                                <template x-if="!insightData.students_list || insightData.students_list.length === 0">
                                    <tr>
                                        <td colspan="5" class="px-6 py-16 text-center text-slate-400 dark:text-white/30 text-xs italic bg-slate-50/50 dark:bg-white/[0.01] transition-colors">Belum ada siswa yang tergabung di kelas ini. Bagikan token untuk mengundang mereka.</td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL ADD CLASS (Diperbaiki dengan Dropdown Custom Status) --}}
    <div x-show="showAddModal" class="fixed inset-0 z-[200] flex items-center justify-center p-4" x-cloak>
        <div class="absolute inset-0 bg-slate-900/80 dark:bg-[#020617]/90 backdrop-blur-sm transition-colors" @click="showAddModal = false"></div>
        <div class="relative w-full max-w-md bg-white dark:bg-[#0f141e] border border-indigo-200 dark:border-indigo-500/30 rounded-3xl p-6 md:p-8 shadow-xl dark:shadow-[0_20px_70px_rgba(99,102,241,0.15)] transition-colors" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-3 transition-colors">
                <div class="p-2 rounded-lg bg-indigo-100 dark:bg-indigo-500/20 text-indigo-600 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-500/30 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg> 
                </div>
                Buat Kelas Baru
            </h3>
            
            <form action="{{ route('admin.classes.store') ?? '#' }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest block mb-2 transition-colors">Nama Kelas <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required placeholder="Contoh: XII RPL 1" class="w-full glass-input rounded-xl px-4 py-3 text-sm focus:ring-2 ring-indigo-500/20 shadow-inner">
                </div>
                <div>
                    <label class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest block mb-2 transition-colors">Jurusan / Info Tambahan</label>
                    <input type="text" name="major" placeholder="Contoh: Rekayasa Perangkat Lunak" class="w-full glass-input rounded-xl px-4 py-3 text-sm focus:ring-2 ring-indigo-500/20 shadow-inner">
                </div>
                
                {{-- DETAIL: Dropdown Status Pendaftaran di Create Form --}}
                <div>
                    <label class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest block mb-2 transition-colors">Status Pendaftaran</label>
                    <div class="relative">
                        <select name="is_active" class="w-full glass-input rounded-xl px-4 py-3 text-sm focus:ring-2 ring-indigo-500/20 cursor-pointer shadow-inner font-bold appearance-none transition-colors">
                            <option value="1" class="bg-white dark:bg-[#0f141e] text-emerald-600 dark:text-emerald-400">Active (Menerima Siswa)</option>
                            <option value="0" class="bg-white dark:bg-[#0f141e] text-red-600 dark:text-red-400">Closed (Terkunci)</option>
                        </select>
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-500 dark:text-white/40 transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                </div>

                <div class="bg-indigo-50 dark:bg-indigo-500/10 border border-indigo-100 dark:border-indigo-500/20 rounded-xl p-4 mt-2 shadow-sm dark:shadow-inner transition-colors">
                    <p class="text-xs text-indigo-700 dark:text-indigo-300 flex items-start gap-2 leading-relaxed transition-colors">
                        <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Token akses unik (6 karakter) akan dibuatkan oleh sistem secara otomatis.
                    </p>
                </div>
                <div class="flex justify-end gap-3 pt-4 border-t border-slate-200 dark:border-white/5 mt-6 transition-colors">
                    <button type="button" @click="showAddModal = false" class="px-5 py-2.5 rounded-xl text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white font-bold text-xs transition border border-transparent hover:border-slate-300 dark:hover:border-white/10 transition-colors">Batal</button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs shadow-md dark:shadow-[0_0_15px_rgba(99,102,241,0.4)] transition border border-indigo-500 dark:border-indigo-400 transform hover:-translate-y-0.5">Simpan Kelas</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL EDIT CLASS --}}
    <div x-show="showEditModal" class="fixed inset-0 z-[200] flex items-center justify-center p-4" x-cloak>
        <div class="absolute inset-0 bg-slate-900/80 dark:bg-[#020617]/90 backdrop-blur-sm transition-colors" @click="showEditModal = false"></div>
        <div class="relative w-full max-w-md bg-white dark:bg-[#0f141e] border border-indigo-200 dark:border-indigo-500/30 rounded-3xl p-6 md:p-8 shadow-xl dark:shadow-[0_20px_70px_rgba(99,102,241,0.15)] transition-colors" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-3 transition-colors">
                <div class="p-2 rounded-lg bg-indigo-100 dark:bg-indigo-500/20 text-indigo-600 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-500/30 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg> 
                </div>
                Edit Kelas
            </h3>

            <form :action="`/admin/classes/${editData.id}`" method="POST" class="space-y-5">
                @csrf @method('PUT')
                <div>
                    <label class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest block mb-2 transition-colors">Nama Kelas <span class="text-red-500">*</span></label>
                    <input type="text" name="name" x-model="editData.name" required class="w-full glass-input rounded-xl px-4 py-3 text-sm focus:ring-2 ring-indigo-500/20 shadow-inner">
                </div>
                <div>
                    <label class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest block mb-2 transition-colors">Jurusan / Info Tambahan</label>
                    <input type="text" name="major" x-model="editData.major" class="w-full glass-input rounded-xl px-4 py-3 text-sm focus:ring-2 ring-indigo-500/20 shadow-inner">
                </div>
                
                {{-- DETAIL: Dropdown Status Pendaftaran dengan Custom Arrow --}}
                <div>
                    <label class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest block mb-2 transition-colors">Status Pendaftaran</label>
                    <div class="relative">
                        <select name="is_active" x-model="editData.is_active" class="w-full glass-input rounded-xl px-4 py-3 text-sm focus:ring-2 ring-indigo-500/20 cursor-pointer shadow-inner font-bold appearance-none transition-colors">
                            <option value="1" class="bg-white dark:bg-[#0f141e] text-emerald-600 dark:text-emerald-400">Active (Menerima Siswa)</option>
                            <option value="0" class="bg-white dark:bg-[#0f141e] text-red-600 dark:text-red-400">Closed (Terkunci)</option>
                        </select>
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-500 dark:text-white/40 transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-slate-200 dark:border-white/5 mt-6 transition-colors">
                    <button type="button" @click="showEditModal = false" class="px-5 py-2.5 rounded-xl text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white font-bold text-xs transition border border-transparent hover:border-slate-300 dark:hover:border-white/10 transition-colors">Batal</button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs shadow-md dark:shadow-[0_0_15px_rgba(99,102,241,0.4)] transition border border-indigo-500 dark:border-indigo-400 transform hover:-translate-y-0.5">Update Kelas</button>
                </div>
            </form>
        </div>
    </div>

    {{-- SCRIPT THEME SWITCHER LOGIC TAMBAHAN --}}
    <script>
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
            });
        });
    </script>

    @if(session('success')) <script> document.addEventListener('DOMContentLoaded', () => { const isDark = document.documentElement.classList.contains('dark'); Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: "{{ session('success') }}", showConfirmButton: false, timer: 3500, background: isDark ? '#0f141e' : '#fff', color: isDark ? '#fff' : '#1e293b', iconColor: '#10b981' }); }); </script> @endif
    @if(session('error')) <script> document.addEventListener('DOMContentLoaded', () => { const isDark = document.documentElement.classList.contains('dark'); Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: "{{ session('error') }}", showConfirmButton: false, timer: 4000, background: isDark ? '#0f141e' : '#fff', color: isDark ? '#fff' : '#1e293b', iconColor: '#ef4444' }); }); </script> @endif

</body>
</html>