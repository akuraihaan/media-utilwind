@extends('layouts.landing')

@section('title', 'Informasi')

@section('content')
<div id="appRoot" class="relative h-screen bg-slate-50 dark:bg-[#020617] text-slate-800 dark:text-slate-200 font-sans overflow-hidden flex flex-col selection:bg-cyan-500/30 selection:text-cyan-900 dark:selection:text-white transition-colors duration-500 pt-[76px] md:pt-[88px]">

    {{-- ======================================================================
         1. BACKGROUND EFFECTS
         ====================================================================== --}}
    <div class="fixed inset-0 -z-50 pointer-events-none">
        <div id="animated-bg" class="absolute inset-0 opacity-40 transition-colors duration-500"></div>
        <div class="absolute top-[-10%] left-[-10%] w-[800px] h-[800px] bg-indigo-300/30 dark:bg-indigo-900/10 rounded-full blur-[120px] animate-pulse transition-colors duration-500"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[600px] h-[600px] bg-cyan-300/30 dark:bg-cyan-900/10 rounded-full blur-[100px] transition-colors duration-500"></div>
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.02] dark:opacity-[0.03] mix-blend-overlay transition-opacity duration-500"></div>
    </div>

    @include('layouts.partials.navbar')
    
    {{-- WRAPPER UTAMA DENGAN ALPINEJS --}}
    <div class="flex flex-1 overflow-hidden relative" x-data="{ sidebarOpen: false }" @keydown.escape.window="sidebarOpen = false">

        {{-- Overlay Mobile --}}
        <div x-show="sidebarOpen" class="fixed inset-0 bg-slate-900/60 dark:bg-[#020617]/80 backdrop-blur-sm z-[90] lg:hidden transition-colors" @click="sidebarOpen = false" x-transition.opacity style="display: none;" x-cloak></div>
        
        {{-- ======================================================================
             SIDEBAR MENU (IDENTIK DENGAN DASHBOARD)
             ====================================================================== --}}
        <aside class="w-[260px] bg-white/80 dark:bg-[#020617]/80 backdrop-blur-2xl border-r border-slate-200/80 dark:border-white/5 flex flex-col shrink-0 z-[100] absolute lg:relative inset-y-0 left-0 h-full transition-transform duration-300 transform lg:translate-x-0 shadow-2xl lg:shadow-none" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            
            {{-- Tombol Tutup Sidebar Mobile --}}
            <button @click="sidebarOpen = false" class="lg:hidden absolute top-4 right-4 p-2 rounded-lg text-slate-500 hover:bg-slate-100 dark:hover:bg-white/5 transition-colors focus:outline-none z-50">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <div class="p-5 pt-8 lg:pt-6 overflow-y-auto custom-scrollbar flex-1 relative z-10">
                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-3 pl-3 transition-colors">Menu Utama</p>
                <nav class="space-y-1">
                    
                    {{-- Nav 1: Dashboard (INACTIVE) --}}
                    <a href="{{ route('dashboard') }}" class="group flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-50 dark:hover:bg-white/[0.03] text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors">
                        <svg class="w-5 h-5 text-slate-400 group-hover:text-slate-700 dark:group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                        <span class="text-[14px] font-medium">Overview</span>
                    </a>
                    
                    {{-- Nav 2: Materi Belajar --}}
                    @php $isUnlocked = Auth::user() && (Auth::user()->role === 'admin' || !empty(Auth::user()->class_group)); @endphp
                    @if($isUnlocked)
                        <a href="{{ route('courses.htmldancss') }}" class="group flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-50 dark:hover:bg-white/[0.03] text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors">
                            <svg class="w-5 h-5 text-slate-400 group-hover:text-slate-700 dark:group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                            <span class="text-[14px] font-medium">Materi Belajar</span>
                        </a>
                    @else
                        <div class="w-full group flex items-center justify-between px-3 py-2.5 rounded-lg text-slate-400 dark:text-slate-600 cursor-not-allowed transition-colors" title="Anda belum bergabung di kelas manapun">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                                <span class="text-[14px] font-medium">Materi Belajar</span>
                            </div>
                            <svg class="w-3.5 h-3.5 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                    @endif

                    {{-- Nav 3: Pengaturan --}}
                    <a href="{{ route('profile.edit') }}" class="group flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-50 dark:hover:bg-white/[0.03] text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors">
                        <svg class="w-5 h-5 text-slate-400 group-hover:text-slate-700 dark:group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        <span class="text-[14px] font-medium">Pengaturan</span>
                    </a>
                    
                    {{-- Nav 4: Informasi (ACTIVE) --}}
                    <a href="{{ route('developer.info') }}" class="group flex items-center gap-3 px-3 py-2.5 rounded-lg bg-slate-100/80 dark:bg-white/5 text-slate-900 dark:text-white font-semibold transition-all">
                        <svg class="w-5 h-5 text-cyan-600 dark:text-cyan-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span class="text-[14px]">Informasi Sistem</span>
                    </a>
                </nav>
            </div>

            {{-- Profil Bawah --}}
            <div class="mt-auto p-4 shrink-0 border-t border-slate-200/80 dark:border-white/5 transition-colors relative z-10">
                <div class="flex items-center gap-3 mb-3 px-2">
                    <div class="w-9 h-9 rounded-full bg-indigo-600 dark:bg-indigo-500 flex items-center justify-center text-white font-bold text-xs shrink-0 border border-white dark:border-[#020617] transition-colors shadow-sm">
                        @if(Auth::user()->avatar)
                            <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="w-full h-full rounded-full object-cover">
                        @else
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[13px] font-bold text-slate-900 dark:text-white truncate transition-colors leading-tight">{{ Auth::user()->name }}</p>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 truncate transition-colors">{{ Auth::user()->email }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-3 py-2 rounded-lg text-red-600 dark:text-red-400 font-medium text-[13px] hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors focus:outline-none">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Keluar Akun
                    </button>
                </form>
            </div>
        </aside>

        {{-- ==================== MAIN CONTENT ==================== --}}
        <main class="flex-1 h-full overflow-y-auto scroll-smooth relative custom-scrollbar p-5 md:p-8 lg:p-10 perspective-container">
            <div class="max-w-7xl mx-auto pb-20">
                
                {{-- TOMBOL HAMBURGER MOBILE --}}
                <div class="flex items-center gap-3 mb-6 lg:hidden">
                    <button @click="sidebarOpen = true" class="p-2 bg-white/80 dark:bg-white/5 backdrop-blur-md rounded-lg text-slate-700 dark:text-white hover:bg-slate-100 dark:hover:bg-white/10 transition-colors border border-slate-200 dark:border-white/10 shadow-sm focus:outline-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <div>
                        {{-- BREADCRUMB START --}}
                        <nav class="flex items-center gap-2 mb-0.5 text-[10px] font-bold uppercase tracking-widest text-slate-500 dark:text-white/40 transition-colors" aria-label="Breadcrumb">
                            <a href="/" class="hover:text-slate-900 dark:hover:text-white transition-colors flex items-center gap-1.5">Home</a>
                            <span class="text-slate-300 dark:text-white/20 transition-colors">/</span>
                            <span class="text-cyan-600 dark:text-cyan-400 transition-colors">Informasi</span>
                        </nav>
                        <h2 class="text-slate-900 dark:text-white font-bold text-lg tracking-tight transition-colors">Developer Hub</h2>
                    </div>
                </div>

                {{-- HEADER CONTENT DESKTOP --}}
                <header class="hidden lg:flex flex-col justify-center mb-10 shrink-0">
                    <div>
                        {{-- BREADCRUMB START --}}
                        <nav class="flex items-center gap-2 mb-3 text-[11px] font-bold uppercase tracking-widest text-slate-500 dark:text-slate-500 transition-colors" aria-label="Breadcrumb">
                            <a href="/" class="hover:text-slate-900 dark:hover:text-white transition-colors flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                Home
                            </a>
                            <span class="text-slate-300 dark:text-slate-700 transition-colors">/</span>
                            <span class="text-cyan-600 dark:text-cyan-400 transition-colors">Informasi</span>
                        </nav>
                        {{-- BREADCRUMB END --}}

                        <h2 class="text-slate-900 dark:text-white font-black text-3xl md:text-4xl tracking-tight transition-colors">Project & Developer Hub</h2>
                        <p class="text-[14px] text-slate-600 dark:text-slate-400 mt-2 transition-colors">Informasi penelitian dan penyusun platform.</p>
                    </div>
                </header>

                {{-- BENTO GRID SHOWCASE (PROFIL) --}}
                <div class="w-full grid grid-cols-1 xl:grid-cols-3 gap-5 md:gap-6 reveal">
                    
                    {{-- KIRI: DIGITAL ID CARD (3D HOVER EFFECT) --}}
                    <div class="xl:col-span-1 tilt-element bg-white dark:bg-[#0f141e] rounded-[2rem] p-6 md:p-8 relative overflow-hidden flex flex-col items-center text-center group border border-slate-200/80 dark:border-white/[0.05] shadow-sm hover:shadow-md dark:shadow-none transition-all duration-500">
                        {{-- BG Card --}}
                        <div class="absolute inset-0 bg-gradient-to-b from-cyan-50/50 dark:from-cyan-500/[0.02] to-transparent pointer-events-none transition-colors"></div>
                        <div class="absolute -top-20 -right-20 w-48 h-48 bg-cyan-300/20 dark:bg-cyan-500/10 rounded-full blur-[60px] transition duration-700 pointer-events-none"></div>
                        
                        {{-- Avatar --}}
                        <div class="relative w-32 h-32 md:w-44 md:h-44 mb-8 inner-3d mt-4">
                            <div class="absolute inset-0 rounded-full bg-gradient-to-tr from-cyan-400 to-indigo-500 animate-spin-slow blur-md opacity-30 group-hover:opacity-60 transition duration-500"></div>
                            <div class="absolute inset-1 bg-white dark:bg-[#0f141e] rounded-full z-10 transition-colors"></div>
                            
                            <img src="{{ asset('images/Raihan.jpg') }}" alt="Taufik Raihandani" onerror="this.src='https://ui-avatars.com/api/?name=Taufik+Raihandani&background=06b6d4&color=fff&size=200'" 
                                 class="absolute inset-2 w-[calc(100%-16px)] h-[calc(100%-16px)] object-cover rounded-full z-20 border border-slate-100 dark:border-white/5 transition-colors">
                            
                            {{-- Verified Badge --}}
                            <div class="absolute bottom-2 right-2 w-10 h-10 bg-white dark:bg-[#0f141e] rounded-full z-30 flex items-center justify-center transition-colors">
                                <div class="w-8 h-8 bg-cyan-500 rounded-full flex items-center justify-center text-white shadow-sm">
                                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </div>
                            </div>
                        </div>

                        {{-- Data Diri --}}
                        <div class="inner-3d w-full mb-8">
                            <h3 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight mb-2 transition-colors">Taufik Raihandani</h3>
                            <div class="inline-block px-3.5 py-1 rounded-lg bg-slate-50 dark:bg-white/5 border border-slate-100 dark:border-white/5 text-cyan-600 dark:text-cyan-400 font-mono text-[12px] font-semibold mb-6 transition-colors">
                                2210131210018
                            </div>
                            
                            <div class="space-y-2.5 w-full text-left bg-slate-50/50 dark:bg-white/[0.02] p-4 rounded-xl border border-slate-100 dark:border-white/5 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-white dark:bg-white/5 text-slate-600 dark:text-slate-400 flex items-center justify-center border border-slate-200/50 dark:border-white/5 shrink-0">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M12 14l9-5-9-5-9 5 9 5z"/><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                                    </div>
                                    <div>
                                        <p class="text-[9px] text-slate-400 dark:text-slate-500 uppercase font-bold tracking-widest transition-colors">Program Studi</p>
                                        <p class="text-[13px] font-semibold text-slate-800 dark:text-white mt-0.5 transition-colors">Pendidikan Komputer</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-white dark:bg-white/5 text-slate-600 dark:text-slate-400 flex items-center justify-center border border-slate-200/50 dark:border-white/5 shrink-0">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                    </div>
                                    <div>
                                        <p class="text-[9px] text-slate-400 dark:text-slate-500 uppercase font-bold tracking-widest transition-colors">Domisili</p>
                                        <p class="text-[13px] font-semibold text-slate-800 dark:text-white mt-0.5 transition-colors">HKSN Permai, Banjarmasin</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Contact Link --}}
                        <a href="mailto:2210131210018@mhs.ulm.ac.id" class="mt-auto w-full inner-3d py-3 rounded-xl bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-semibold text-[13px] flex items-center justify-center gap-2 hover:-translate-y-0.5 transition-transform shadow-sm group/mail">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            Kirim Email
                        </a>
                    </div>

                    {{-- KANAN: PROJECT SHOWCASE (BENTO BOXES) --}}
                    <div class="xl:col-span-2 flex flex-col gap-5 md:gap-6 h-full">
                        
                        {{-- Judul Penelitian Box --}}
                        <div class="bg-white dark:bg-[#0f141e] rounded-[2rem] p-8 md:p-10 relative overflow-hidden flex-1 flex flex-col justify-center border border-slate-200/80 dark:border-white/[0.05] shadow-sm dark:shadow-none transition-colors duration-500">
                            
                            <div class="relative z-10">
                                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-50 dark:bg-white/5 border border-slate-100 dark:border-white/5 text-slate-600 dark:text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-5 transition-colors">
                                    Topik Skripsi / Penelitian
                                </div>
                                
                                <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-slate-900 dark:text-white leading-tight mb-5 transition-colors">
                                    Penerapan Pemantauan Kinerja Siswa Pada Media Pembelajaran Interaktif Materi <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-600 to-indigo-600 dark:from-cyan-400 dark:to-indigo-400">Tailwind CSS</span>
                                </h2>

                                <p class="text-slate-600 dark:text-slate-400 text-[13px] md:text-[14px] leading-relaxed max-w-2xl transition-colors">
                                    Proyek media pembelajaran mandiri ini dirancang untuk memfasilitasi siswa dalam memahami framework <span class="text-slate-800 dark:text-white font-semibold transition-colors">utility-first</span> secara interaktif, dilengkapi sistem analitik untuk memetakan progres belajar.
                                </p>
                            </div>

                            {{-- Tech Stack Badges --}}
                            <div class="relative z-10 flex flex-wrap items-center gap-2.5 mt-8">
                                <span class="px-3.5 py-1.5 rounded-lg bg-slate-50 dark:bg-white/5 border border-slate-100 dark:border-white/5 text-[11px] font-semibold text-slate-700 dark:text-slate-300 flex items-center gap-2 transition-colors"><div class="w-2 h-2 bg-red-500 rounded-sm"></div> Laravel</span>
                                <span class="px-3.5 py-1.5 rounded-lg bg-slate-50 dark:bg-white/5 border border-slate-100 dark:border-white/5 text-[11px] font-semibold text-slate-700 dark:text-slate-300 flex items-center gap-2 transition-colors"><div class="w-2 h-2 bg-cyan-500 dark:bg-cyan-400 rounded-full"></div> Tailwind CSS</span>
                                <span class="px-3.5 py-1.5 rounded-lg bg-slate-50 dark:bg-white/5 border border-slate-100 dark:border-white/5 text-[11px] font-semibold text-slate-700 dark:text-slate-300 flex items-center gap-2 transition-colors"><div class="w-2 h-2 bg-blue-500 dark:bg-blue-400 rounded-sm rotate-45"></div> Alpine.js</span>
                            </div>
                        </div>

                        {{-- Dosen & CTA Row --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-6">
                            
                            {{-- Card Dosen --}}
                            <div class="bg-white dark:bg-[#0f141e] rounded-[2rem] p-6 md:p-8 flex flex-col justify-center relative overflow-hidden transition-colors duration-500 border border-slate-200/80 dark:border-white/[0.05] shadow-sm dark:shadow-none">
                                <h4 class="text-[10px] text-slate-400 dark:text-slate-500 uppercase font-bold tracking-widest mb-5 transition-colors">Dosen Pembimbing</h4>
                                
                                <div class="space-y-4 relative z-10">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-slate-50 dark:bg-white/5 border border-slate-100 dark:border-white/5 text-slate-600 dark:text-slate-400 flex items-center justify-center text-[11px] font-bold shrink-0 transition-colors">1</div>
                                        <p class="text-[12px] font-semibold text-slate-800 dark:text-white leading-tight transition-colors">Novan Alkaf Bahrain Saputra, S.Kom., M.T.</p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-slate-50 dark:bg-white/5 border border-slate-100 dark:border-white/5 text-slate-600 dark:text-slate-400 flex items-center justify-center text-[11px] font-bold shrink-0 transition-colors">2</div>
                                        <p class="text-[12px] font-semibold text-slate-800 dark:text-white leading-tight transition-colors">Muhammad Hifdzi Adini, S.Kom., M.T.</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Card Action --}}
                            <div class="bg-white dark:bg-[#0f141e] rounded-[2rem] p-6 md:p-8 flex flex-col items-center justify-center text-center relative overflow-hidden group transition-colors duration-500 border border-slate-200/80 dark:border-white/[0.05] shadow-sm dark:shadow-none">
                                <div class="w-12 h-12 rounded-xl bg-slate-50 dark:bg-white/5 border border-slate-100 dark:border-white/5 flex items-center justify-center mb-4 text-cyan-600 dark:text-cyan-400 group-hover:scale-105 transition-transform duration-300">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                                </div>
                                
                                <h4 class="text-[15px] font-bold text-slate-900 dark:text-white mb-1.5 transition-colors">Eksplorasi Media</h4>
                                <p class="text-[12px] text-slate-500 dark:text-slate-400 mb-5 leading-relaxed max-w-[200px] transition-colors">Lihat hasil implementasi langsung dari platform ini.</p>
                                
                                <a href="https://utilwind.research-media.web.id/" target="_blank" class="w-full relative z-10 flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg bg-cyan-600 hover:bg-cyan-700 dark:bg-cyan-500 dark:hover:bg-cyan-400 text-white dark:text-[#020617] font-semibold text-[12px] hover:-translate-y-0.5 transition-transform shadow-sm focus:outline-none">
                                    Kunjungi Situs
                                </a>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </main>
    </div>
</div>

{{-- SCRIPT & STYLE TAMBAHAN --}}
<style>
    /* Custom Scrollbar */
    .custom-scrollbar::-webkit-scrollbar { width: 4px; height: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(150,150,150,0.2); border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(150,150,150,0.4); }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.2); }

    .animate-spin-slow { animation: spin 8s linear infinite; }
    @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }

    .reveal { opacity: 0; transform: translateY(20px); animation: revealAnim 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes revealAnim { to { opacity: 1; transform: translateY(0); } }

    /* 3D Perspective Card Setup */
    .perspective-container { perspective: 1000px; transform-style: preserve-3d; }
    .tilt-element { transition: transform 0.1s ease-out; transform-style: preserve-3d; }
    .tilt-element::before {
        content: ''; position: absolute; inset: 0; border-radius: inherit;
        background: linear-gradient(135deg, rgba(255,255,255,0.4) 0%, rgba(255,255,255,0) 100%);
        opacity: 0; transition: opacity 0.3s; pointer-events: none; z-index: 10;
    }
    .dark .tilt-element::before {
        background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 100%);
    }
    .tilt-element:hover::before { opacity: 1; }
    .inner-3d { transform: translateZ(30px); }
    [x-cloak] { display: none !important; }
</style>

{{-- Alpine JS --}}
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // --- 1. LOGIKA THEME SWITCHER GLOBAL ---
        if (typeof window.themeSwitcherInitialized === 'undefined') {
            window.themeSwitcherInitialized = true;
            
            const htmlEl = document.documentElement;
            const themeToggleBtns = document.querySelectorAll('.theme-toggle-btn');
            
            if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                htmlEl.classList.add('dark');
            } else {
                htmlEl.classList.remove('dark');
            }

            themeToggleBtns.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault(); 
                    htmlEl.classList.toggle('dark');
                    if (htmlEl.classList.contains('dark')) {
                        localStorage.setItem('color-theme', 'dark');
                    } else {
                        localStorage.setItem('color-theme', 'light');
                    }
                });
            });
        }

        // --- 2. Efek 3D Card Hover (Hanya Desktop) ---
        if(window.innerWidth > 1024) {
            const tiltElements = document.querySelectorAll('.tilt-element');
            
            tiltElements.forEach(el => {
                el.addEventListener('mousemove', (e) => {
                    const rect = el.getBoundingClientRect();
                    const x = e.clientX - rect.left;
                    const y = e.clientY - rect.top;
                    
                    const centerX = rect.width / 2;
                    const centerY = rect.height / 2;
                    
                    const rotateX = ((y - centerY) / centerY) * -10;
                    const rotateY = ((x - centerX) / centerX) * 10;
                    
                    el.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.01, 1.01, 1.01)`;
                });
                
                el.addEventListener('mouseleave', () => {
                    el.style.transform = `perspective(1000px) rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1)`;
                });
            });
        }
    });

    // --- 3. RESOURCE MENU DESKTOP ---
    function toggleResourceMenu() {
        const menu = document.getElementById('resource-dropdown');
        const chevron = document.getElementById('resource-chevron');
        closeUserMenu();

        if (menu.classList.contains('hidden')) {
            menu.classList.remove('hidden');
            setTimeout(() => {
                menu.classList.remove('opacity-0', 'scale-95');
                if(chevron) chevron.classList.add('rotate-180');
            }, 10);
        } else { closeResourceMenu(); }
    }
    
    function closeResourceMenu() {
        const menu = document.getElementById('resource-dropdown');
        const chevron = document.getElementById('resource-chevron');
        if(!menu) return;
        menu.classList.add('opacity-0', 'scale-95');
        if(chevron) chevron.classList.remove('rotate-180');
        setTimeout(() => { menu.classList.add('hidden'); }, 200);
    }

    // --- 4. USER MENU DESKTOP ---
    function toggleUserMenu() {
        const menu = document.getElementById('user-dropdown');
        const chevron = document.getElementById('menu-chevron');
        closeResourceMenu();

        if (menu.classList.contains('hidden')) {
            menu.classList.remove('hidden');
            setTimeout(() => {
                menu.classList.remove('opacity-0', 'scale-95');
                if(chevron) chevron.classList.add('rotate-180');
            }, 10);
        } else { closeUserMenu(); }
    }
    
    function closeUserMenu() {
        const menu = document.getElementById('user-dropdown');
        const chevron = document.getElementById('menu-chevron');
        if(!menu) return;
        menu.classList.add('opacity-0', 'scale-95');
        if(chevron) chevron.classList.remove('rotate-180');
        setTimeout(() => { menu.classList.add('hidden'); }, 200);
    }

    // --- 5. KLIK DI LUAR UNTUK MENUTUP DROPDOWN ---
    document.addEventListener('click', function(event) {
        const desktopMenu = document.getElementById('desktop-user-menu');
        const userDropdown = document.getElementById('user-dropdown');
        if (desktopMenu && !desktopMenu.contains(event.target) && userDropdown && !userDropdown.classList.contains('hidden')) {
            closeUserMenu();
        }

        const resourceContainer = document.getElementById('resource-menu-container');
        const resourceDropdown = document.getElementById('resource-dropdown');
        if (resourceContainer && !resourceContainer.contains(event.target) && resourceDropdown && !resourceDropdown.classList.contains('hidden')) {
            closeResourceMenu();
        }
    });
</script>
@endsection