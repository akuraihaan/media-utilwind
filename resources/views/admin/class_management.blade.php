<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Manajemen Kelas & Token</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    
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
        .modal-open { overflow: hidden; padding-right: 5px; }
    </style>
</head>
<body x-data="{ 
    sidebarOpen: false, 
    isFullscreen: false,
    showAddModal: false,
    showEditModal: false,
    showDashboardInfoModal: false,
    editData: { id: '', name: '', major: '', is_active: 1 },
    
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
    copyToken(token) { 
        navigator.clipboard.writeText(token); 
        const t = this.getSwalTheme();
        Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Token berhasil disalin!', showConfirmButton: false, timer: 2000, background: t.bg, color: t.color, iconColor: '#10b981' }); 
    },
    confirmRegenerate(id) { 
        const t = this.getSwalTheme();
        Swal.fire({ title: 'Perbarui Token?', text: 'Token lama tidak akan dapat digunakan lagi.', icon: 'warning', showCancelButton: true, confirmButtonColor: '#eab308', cancelButtonColor: t.cancelBg, background: t.bg, color: t.color }).then((r) => { if (r.isConfirmed) document.getElementById('form-token-'+id).submit(); })
    },
    confirmHapus(id) {
        const t = this.getSwalTheme();
        Swal.fire({ title: 'Hapus Kelas?', text: 'Semua data yang terkait dengan kelas ini akan dihapus.', icon: 'error', showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: t.cancelBg, background: t.bg, color: t.color }).then((r) => { if (r.isConfirmed) document.getElementById('form-delete-'+id).submit(); })
    }
}" @keydown.escape.window="showAddModal = false; showEditModal = false; showDashboardInfoModal = false; isFullscreen = false; document.exitFullscreen();" :class="{'modal-open': showAddModal || showEditModal || showDashboardInfoModal || sidebarOpen}">

    <div class="flex h-screen w-full relative">

        {{-- ==================== SIDEBAR ==================== --}}
        <div x-show="sidebarOpen" class="fixed inset-0 bg-slate-900/60 dark:bg-[#020617]/80 backdrop-blur-sm z-[90] md:hidden transition-opacity" @click="sidebarOpen = false" x-transition.opacity style="display: none;" x-cloak></div>

    <aside class="glass-sidebar w-72 h-full flex flex-col fixed md:relative z-[100] transition-transform duration-300 transform md:translate-x-0" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
        <div class="h-24 flex items-center justify-between px-8 border-b border-slate-200 dark:border-white/5 relative overflow-hidden group transition-colors">
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-20 h-20 bg-indigo-200/50 dark:bg-indigo-500/20 rounded-full blur-[40px] opacity-0 group-hover:opacity-100 transition duration-500"></div>
            
            <a href="{{ route('landing') }}" class="flex items-center gap-3 relative z-10">
                <img src="{{ asset('images/logo.png') }}" class="h-8 w-auto object-contain block dark:hidden" style="filter: brightness(0.1);" alt="Logo">
                <img src="{{ asset('images/logo.png') }}" class="h-8 w-auto object-contain hidden dark:block drop-shadow-sm" alt="Logo Dark">
                <div>
                    <h1 class="text-xl font-black text-slate-900 dark:text-white tracking-tight leading-none transition-colors">Util<span class="text-indigo-600 dark:text-indigo-400">wind</span></h1>
                    <span class="text-[9px] font-bold text-slate-500 dark:text-white/40 tracking-[0.2em] uppercase transition-colors">Panel Admin</span>
                </div>
            </a>
            <button @click="sidebarOpen = false" class="md:hidden text-slate-500 dark:text-white/50 hover:text-slate-800 dark:hover:text-white relative z-10 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        @include('admin.partials.sidebar-nav')

        {{-- USER PROFILE Bawah Sidebar --}}
        <div class="p-4 border-t border-slate-200 dark:border-white/5 bg-slate-50/50 dark:bg-[#05080f]/50 transition-colors">
            <div class="flex items-center gap-3 mb-4 px-2">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-fuchsia-500 flex items-center justify-center font-bold text-white text-xs shadow-lg">AD</div>
                <div class="overflow-hidden">
                    <p class="text-xs font-bold text-slate-900 dark:text-white truncate transition-colors">{{ Auth::user()->name ?? 'Administrator' }}</p>
                    <p class="text-[10px] text-slate-500 dark:text-white/40 truncate transition-colors">Administrator Sistem</p>
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
        <main id="admin-main-content" class="flex-1 flex flex-col relative z-10 transition-colors duration-300 h-full overflow-y-auto overflow-x-hidden">
            
            {{-- Background FX --}}
            <div class="fixed inset-0 pointer-events-none z-0">
                <div class="absolute top-[10%] left-[20%] w-[500px] h-[500px] bg-cyan-400/20 dark:bg-cyan-600/10 rounded-full blur-[120px] transition-colors"></div>
                <div class="absolute bottom-[10%] right-[10%] w-[400px] h-[400px] bg-indigo-400/20 dark:bg-indigo-600/10 rounded-full blur-[120px] transition-colors"></div>
                <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.02] dark:opacity-[0.04] mix-blend-overlay"></div>
            </div>

            {{-- HEADER RESPONSIVE --}}
            <header class="h-24 glass-header flex flex-col justify-center px-6 md:px-10 shrink-0 sticky top-0 z-40 transition-colors">
                <div class="flex items-center justify-between w-full">
                    <div class="flex items-center gap-4">
                        <button @click="sidebarOpen = true" class="md:hidden p-2 bg-slate-200 dark:bg-white/5 rounded-lg text-slate-700 dark:text-white hover:bg-slate-300 dark:hover:bg-white/10 transition-colors shadow-sm dark:shadow-none">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        </button>
                        
                        <div class="flex items-center gap-3">
                            <div>
                                <nav class="flex text-[10px] text-slate-500 dark:text-white/50 mb-1.5 font-bold hidden sm:flex transition-colors" aria-label="Breadcrumb">
                                    <ol class="inline-flex items-center space-x-1">
                                        <li class="inline-flex items-center"><a href="{{ route('admin.dashboard') ?? '#' }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Dasbor</a></li>
                                        <li><div class="flex items-center"><svg class="w-3 h-3 text-slate-400 dark:text-white/30 mx-1 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg><span class="text-slate-900 dark:text-white transition-colors">Manajemen Kelas</span></div></li>
                                    </ol>
                                </nav>
                                <div class="flex items-center gap-2">
                                    <h2 class="text-slate-900 dark:text-white font-bold text-lg md:text-xl tracking-tight transition-colors">Kelas & Token Akses</h2>
                                    
                                    {{-- TOMBOL TRIGGER HERO MODAL PANDUAN --}}
                                    <button @click="showDashboardInfoModal = true" class="w-6 h-6 md:w-7 md:h-7 rounded-full border border-slate-200 dark:border-white/10 flex items-center justify-center text-[10px] md:text-xs font-black text-slate-400 dark:text-slate-500 hover:text-indigo-600 dark:hover:text-indigo-400 bg-white/50 dark:bg-white/5 backdrop-blur-sm hover:bg-white dark:hover:bg-white/10 hover:border-indigo-200 dark:hover:border-indigo-500/30 transition-all duration-300 shadow-sm hover:shadow-md focus:outline-none mt-0.5" title="Panduan Manajemen Kelas">
                                        ?
                                    </button>
                                </div>
                                <p class="text-[9px] md:text-xs text-slate-500 dark:text-white/40 flex items-center gap-1.5 mt-0.5 transition-colors"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse shadow-[0_0_8px_#10b981]"></span> Manajemen kelas</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3 sm:gap-6">
                        <button onclick="window.location.reload()" class="p-2.5 text-slate-500 dark:text-white/40 hover:text-slate-900 dark:hover:text-white transition-colors rounded-full hover:bg-slate-200 dark:hover:bg-white/5 hidden sm:block border border-transparent dark:hover:border-white/10" title="Muat ulang">
                            <svg class="w-4 h-4 group-hover:rotate-180 transition-transform duration-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        </button>
                        <button @click="isFullscreen = !isFullscreen; isFullscreen ? document.documentElement.requestFullscreen() : document.exitFullscreen()" class="p-2.5 text-slate-500 dark:text-white/40 hover:text-slate-900 dark:hover:text-white transition-colors rounded-full hover:bg-slate-200 dark:hover:bg-white/5 hidden md:block border border-transparent dark:hover:border-white/10" title="Mode Layar Penuh">
                            <svg x-show="!isFullscreen" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                            <svg x-show="isFullscreen" style="display: none;" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                        <div class="border-l border-slate-300 dark:border-white/10 pl-5 ml-1 transition-colors">
                            <button @click="showAddModal = true" class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow-md dark:shadow-[0_0_15px_rgba(99,102,241,0.3)] transition border border-indigo-500 dark:border-indigo-400">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg> 
                                <span class="hidden sm:inline">Tambah Kelas</span>
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

                    @php
                        $classCount = (int) ($totalClasses ?? 0);
                        $activeClassCount = (int) ($totalActive ?? 0);
                        $studentCount = (int) ($totalStudents ?? 0);
                        $avgStudentsPerClass = $classCount > 0 ? round($studentCount / $classCount, 1) : 0;
                        $analyticsTitle = 'Ringkasan Kelas';
                        $analyticsSubtitle = null;
                        $analyticsItems = [
                            ['label' => 'Kelas', 'value' => number_format($classCount), 'hint' => 'total', 'tone' => 'indigo'],
                            ['label' => 'Aktif', 'value' => number_format($activeClassCount), 'hint' => number_format(max(0, $classCount - $activeClassCount)) . ' tutup', 'tone' => $activeClassCount > 0 ? 'emerald' : 'amber'],
                            ['label' => 'Siswa', 'value' => number_format($studentCount), 'hint' => 'terhubung', 'tone' => 'cyan'],
                            ['label' => 'Rata-rata', 'value' => $avgStudentsPerClass, 'hint' => 'per kelas', 'tone' => 'fuchsia'],
                        ];
                        $analyticsActions = [];
                    @endphp
                    @include('admin.partials.compact_analytics_strip')

                    {{-- MAIN TABLE --}}
                    <div class="glass-card rounded-2xl overflow-hidden reveal flex flex-col transition-colors" style="animation-delay: 0.2s">
                        <div class="p-6 border-b border-slate-200 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.02] shrink-0 transition-colors">
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2 transition-colors">Kelas</h3>
                            <p class="text-xs text-slate-500 dark:text-white/40 mt-1 transition-colors">Token, status, dan siswa.</p>
                        </div>
                        
                        <div class="overflow-x-auto overflow-y-auto custom-scrollbar max-h-[500px] relative">
                            <table class="w-full text-sm text-left">
                                <thead class="bg-slate-100 dark:bg-[#0f141e] text-slate-500 dark:text-white/40 text-[10px] uppercase font-bold sticky top-0 z-20 shadow-sm dark:shadow-md transition-colors after:absolute after:inset-x-0 after:bottom-0 after:border-b after:border-slate-200 dark:after:border-white/5">
                                    <tr>
                                        <th class="px-6 py-4 transition-colors">Nama Kelas / Jurusan</th>
                                        <th class="px-6 py-4 text-center transition-colors">Siswa</th>
                                        <th class="px-6 py-4 text-center transition-colors w-48">Token Akses</th>
                                        <th class="px-6 py-4 text-center transition-colors">Status</th>
                                        <th class="px-6 py-4 text-right transition-colors">Aksi</th>
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
                                        
                                        {{-- DETAIL IMPROVISASI TOKEN HOVER --}}
                                        <td class="px-6 py-4 text-center relative group/tooltip" title="Arahkan untuk melihat">
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
                                                    Klik untuk Menyalin
                                                </span>
                                            </div>
                                        </td>

                                        <td class="px-6 py-4 text-center">
                                            @if(($class['is_active'] ?? $class->is_active))
                                                <span class="px-2.5 py-1 rounded text-[9px] font-bold uppercase tracking-widest border bg-emerald-100 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border-emerald-200 dark:border-emerald-500/20 transition-colors">Aktif</span>
                                            @else
                                                <span class="px-2.5 py-1 rounded text-[9px] font-bold uppercase tracking-widest border bg-slate-200 dark:bg-slate-800 text-slate-500 dark:text-slate-400 border-slate-300 dark:border-slate-700 transition-colors">Tutup</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex justify-end gap-2">
                                                <form id="form-token-{{ $class['id'] ?? $class->id }}" action="{{ route('admin.classes.token', $class['id'] ?? $class->id) ?? '#' }}" method="POST">
                                                    @csrf <button type="button" @click="confirmRegenerate({{ $class['id'] ?? $class->id }})" class="p-2 rounded-lg bg-white dark:bg-white/5 text-amber-500 dark:text-yellow-500 hover:bg-amber-500 dark:hover:bg-yellow-500 hover:text-white dark:hover:text-black transition-colors shadow-sm dark:shadow-inner border border-slate-200 dark:border-transparent hover:border-amber-400 dark:hover:border-yellow-400" title="Buat Ulang Token"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg></button>
                                                </form>
                                                <button @click="openEdit({{ is_array($class) ? collect($class)->only(['id','name','major','is_active'])->toJson() : collect($class)->only(['id','name','major','is_active'])->toJson() }})" class="p-2 rounded-lg bg-white dark:bg-white/5 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-600 dark:hover:bg-indigo-500 hover:text-white transition-colors shadow-sm dark:shadow-inner border border-slate-200 dark:border-transparent hover:border-indigo-400" title="Ubah"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                                                <form id="form-delete-{{ $class['id'] ?? $class->id }}" action="{{ route('admin.classes.destroy', $class['id'] ?? $class->id) ?? '#' }}" method="POST">
                                                    @csrf @method('DELETE') <button type="button" @click="confirmHapus({{ $class['id'] ?? $class->id }})" class="p-2 rounded-lg bg-white dark:bg-white/5 text-red-500 dark:text-red-400 hover:bg-red-500 hover:text-white transition-colors shadow-sm dark:shadow-inner border border-slate-200 dark:border-transparent hover:border-red-400" title="Hapus"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="5" class="py-20 text-center text-slate-500 dark:text-white/30 text-xs italic bg-slate-50/50 dark:bg-white/[0.01] transition-colors">Belum ada kelas yang dibuat di sistem.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    {{-- MODAL PANDUAN MANAJEMEN KELAS --}}
    <div x-show="showDashboardInfoModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4 sm:p-6" x-cloak style="display: none;">
        <div class="absolute inset-0 bg-slate-900/60 dark:bg-[#020617]/80 backdrop-blur-md cursor-pointer transition-opacity" @click="showDashboardInfoModal = false" x-transition.opacity></div>
        
        <div class="relative max-h-[92vh] w-full max-w-6xl overflow-y-auto bg-white/95 dark:bg-[#0f141e]/95 backdrop-blur-xl border border-slate-200 dark:border-white/10 rounded-[2rem] p-6 md:p-8 shadow-2xl transition-all custom-scrollbar" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-95 translate-y-4">
            
            <button @click="showDashboardInfoModal = false" class="absolute top-5 right-5 p-2 rounded-full hover:bg-slate-100 dark:hover:bg-white/5 text-slate-400 hover:text-slate-600 dark:hover:text-white transition-all focus:outline-none z-10">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            @php
                $guideTitle = 'Panduan Manajemen Kelas';
                $guideSubtitle = 'Mengatur kelas dan token';
                $guideImage = 'images/guides/current-admin-classes.png';
                $guideIntro = 'Gunakan nomor pada gambar untuk membaca area ringkasan, daftar kelas, token, status, dan tombol aksi yang dipakai admin.';
                $guidePoints = [
                    ['x' => 61, 'y' => 27, 'title' => 'Ringkasan kelas', 'description' => 'Lihat jumlah kelas, token aktif, dan siswa terhubung sebelum mengubah data.'],
                    ['x' => 57, 'y' => 62, 'title' => 'Token akses', 'description' => 'Salin token dari baris kelas yang benar, lalu bagikan kepada siswa yang sesuai.'],
                    ['x' => 87, 'y' => 72, 'title' => 'Aksi kelas', 'description' => 'Gunakan tombol aksi untuk membuat ulang token, mengedit kelas, atau menghapus data.'],
                ];
            @endphp
            @include('admin.partials.analytics_guide_mockup')

            <div class="mt-8 pt-6 border-t border-slate-200 dark:border-white/5">
                <button @click="showDashboardInfoModal = false" class="w-full py-3 bg-slate-900 hover:bg-slate-800 dark:bg-white dark:hover:bg-slate-200 text-white dark:text-slate-900 font-bold text-sm rounded-xl transition-colors shadow-md focus:outline-none">
                    Mengerti, Tutup Panduan
                </button>
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
                
                {{-- DETAIL: Dropdown Status Pendaftaran di Buat Form --}}
                <div>
                    <label class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest block mb-2 transition-colors">Status Pendaftaran</label>
                    <div class="relative">
                        <select name="is_active" class="w-full glass-input rounded-xl px-4 py-3 text-sm focus:ring-2 ring-indigo-500/20 cursor-pointer shadow-inner font-bold appearance-none transition-colors">
                            <option value="1" class="bg-white dark:bg-[#0f141e] text-emerald-600 dark:text-emerald-400">Aktif (Menerima Siswa)</option>
                            <option value="0" class="bg-white dark:bg-[#0f141e] text-red-600 dark:text-red-400">Tutup (Terkunci)</option>
                        </select>
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-500 dark:text-white/40 transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                </div>

                <div class="bg-indigo-50 dark:bg-indigo-500/10 border border-indigo-100 dark:border-indigo-500/20 rounded-xl p-4 mt-2 shadow-sm dark:shadow-inner transition-colors">
                    <p class="text-xs text-indigo-700 dark:text-indigo-300 flex items-start gap-2 leading-relaxed transition-colors">
                        <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Sistem akan membuat token akses unik 6 karakter secara otomatis.
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
                Perbarui Kelas
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
                            <option value="1" class="bg-white dark:bg-[#0f141e] text-emerald-600 dark:text-emerald-400">Aktif (Menerima Siswa)</option>
                            <option value="0" class="bg-white dark:bg-[#0f141e] text-red-600 dark:text-red-400">Tutup (Terkunci)</option>
                        </select>
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-500 dark:text-white/40 transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-slate-200 dark:border-white/5 mt-6 transition-colors">
                    <button type="button" @click="showEditModal = false" class="px-5 py-2.5 rounded-xl text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white font-bold text-xs transition border border-transparent hover:border-slate-300 dark:hover:border-white/10 transition-colors">Batal</button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs shadow-md dark:shadow-[0_0_15px_rgba(99,102,241,0.4)] transition border border-indigo-500 dark:border-indigo-400 transform hover:-translate-y-0.5">Perbarui Kelas</button>
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
