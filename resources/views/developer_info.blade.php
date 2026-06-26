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
    <div class="flex flex-1 overflow-hidden relative" x-data="{ sidebarOpen: false, activeVisualGuide: 'beranda' }" @keydown.escape.window="sidebarOpen = false">

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
                        <a href="{{ route('courses.curriculum') }}" class="group flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-50 dark:hover:bg-white/[0.03] text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors">
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
                        <h2 class="text-slate-900 dark:text-white font-bold text-lg tracking-tight transition-colors">Informasi Sistem</h2>
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

                        <div class="flex items-center gap-4">
                            <h2 class="text-slate-900 dark:text-white font-black text-3xl md:text-4xl tracking-tight transition-colors">Informasi Sistem & Panduan Media</h2>
                        </div>
                        <p class="text-[14px] text-slate-600 dark:text-slate-400 mt-2 transition-colors">Pusat informasi pengembang, identitas penelitian, dan tampilan utama media pembelajaran.</p>
                    </div>
                </header>

                <section id="developer-info" class="mb-5 md:mb-6 reveal-up">
                    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
                        <div>
                            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-white/70 dark:bg-white/5 border border-slate-200/80 dark:border-white/10 text-cyan-600 dark:text-cyan-400 text-[10px] font-bold uppercase tracking-widest mb-3 transition-colors">
                                Developer Info
                            </div>
                            <h3 class="text-xl md:text-2xl font-black text-slate-900 dark:text-white tracking-tight transition-colors">Profil Penyusun & Identitas Penelitian</h3>
                        </div>
                        <div class="flex flex-wrap items-center gap-2">
                            <a href="#panduan-media" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-[11px] font-bold hover:-translate-y-0.5 transition-transform shadow-sm">
                                Lihat Panduan
                            </a>
                        </div>
                    </div>
                </section>

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
                                    Integrasi Learning analytics Pada Media Pembelajaran Interaktif Materi <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-600 to-indigo-600 dark:from-cyan-400 dark:to-indigo-400">Tailwind CSS</span> Dasar
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

                        {{-- Dosen Pembimbing --}}
                        <div class="grid grid-cols-1 gap-5 md:gap-6">
                            
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

                        </div>
                    </div>

                </div>

                {{-- PANDUAN PENGGUNAAN MEDIA --}}
                <section id="panduan-media" class="mt-10 md:mt-12 space-y-6 reveal-up delay-100">
                    <div class="bg-white dark:bg-[#0f141e] rounded-[2rem] p-5 md:p-8 border border-slate-200/80 dark:border-white/[0.05] shadow-sm dark:shadow-none transition-colors">
                        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4 mb-6">
                            <div>
                                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-cyan-50 dark:bg-cyan-500/10 border border-cyan-100 dark:border-cyan-500/20 text-cyan-700 dark:text-cyan-300 text-[10px] font-bold uppercase tracking-widest mb-3 transition-colors">
                                    Panduan Media
                                </div>
                                <h4 class="text-2xl md:text-3xl font-black text-slate-900 dark:text-white tracking-tight transition-colors">Panduan Penggunaan </h4>
                                <p class="text-sm text-slate-600 dark:text-slate-400 mt-3 max-w-3xl leading-relaxed">Ikuti alur penggunaan secara berurutan: buat akun, masuk ke dasbor, masukkan token kelas, lalu baca materi melalui tutorial sampai siap mengerjakan kuis dan praktik.</p>
                            </div>
                            {{-- <nav class="media-guide-nav custom-scrollbar" aria-label="Navigasi Panduan Media">
                                <button type="button" @click="activeVisualGuide = 'beranda'; document.getElementById('media-guide-beranda')?.scrollIntoView({ behavior: 'smooth', block: 'start' })" class="media-guide-chip" :class="activeVisualGuide === 'beranda' ? 'active' : ''">Beranda</button>
                                <button type="button" @click="activeVisualGuide = 'login'; document.getElementById('media-guide-login')?.scrollIntoView({ behavior: 'smooth', block: 'start' })" class="media-guide-chip" :class="activeVisualGuide === 'login' ? 'active' : ''">Login</button>
                                <button type="button" @click="activeVisualGuide = 'token'; document.getElementById('media-guide-token')?.scrollIntoView({ behavior: 'smooth', block: 'start' })" class="media-guide-chip" :class="activeVisualGuide === 'token' ? 'active' : ''">Token Kelas</button>
                                <button type="button" @click="activeVisualGuide = 'materi'; document.getElementById('media-guide-materi')?.scrollIntoView({ behavior: 'smooth', block: 'start' })" class="media-guide-chip" :class="activeVisualGuide === 'materi' ? 'active' : ''">Materi</button>
                                <button type="button" @click="activeVisualGuide = 'isi-materi'; document.getElementById('media-guide-isi-materi')?.scrollIntoView({ behavior: 'smooth', block: 'start' })" class="media-guide-chip" :class="activeVisualGuide === 'isi-materi' ? 'active' : ''">Dalam Materi</button>
                                <button type="button" @click="activeVisualGuide = 'kuis'; document.getElementById('media-guide-kuis')?.scrollIntoView({ behavior: 'smooth', block: 'start' })" class="media-guide-chip" :class="activeVisualGuide === 'kuis' ? 'active' : ''">Kuis</button>
                                <button type="button" @click="activeVisualGuide = 'praktik'; document.getElementById('media-guide-praktik')?.scrollIntoView({ behavior: 'smooth', block: 'start' })" class="media-guide-chip" :class="activeVisualGuide === 'praktik' ? 'active' : ''">Praktik</button>
                                <button type="button" @click="activeVisualGuide = 'analytics'; document.getElementById('media-guide-analytics')?.scrollIntoView({ behavior: 'smooth', block: 'start' })" class="media-guide-chip" :class="activeVisualGuide === 'analytics' ? 'active' : ''">Analitik</button>
                            </nav> --}}
                        </div>

                        <div class="space-y-4">
                            <div id="media-guide-beranda" class="media-accordion-item scroll-mt-32">
                                <button @click="activeVisualGuide = activeVisualGuide === 'beranda' ? null : 'beranda'" class="media-accordion-trigger" type="button">
                                    <span class="media-accordion-icon bg-cyan-100 text-cyan-700 dark:bg-cyan-500/10 dark:text-cyan-300">01</span>
                                    <span class="flex-1">
                                        <span class="media-accordion-title">Beranda Media</span>
                                        <span class="media-accordion-subtitle">Pintu awal untuk mengenali Utilwind dan masuk ke fitur belajar.</span>
                                    </span>
                                    <svg :class="activeVisualGuide === 'beranda' ? 'rotate-180 text-red-500' : 'text-slate-400'" class="w-5 h-5 transition-transform shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div x-show="activeVisualGuide === 'beranda'" x-transition.opacity class="media-accordion-content">
                                    <div class="media-guide-grid">
                                        <div class="interactive-shot real-media-shot">
                                            <img class="guide-screenshot" src="{{ asset('images/guides/student-beranda.png') }}" alt="Tampilan asli beranda Utilwind" loading="eager" decoding="async">
                                            <div class="media-hotspot" style="--x: 6%; --y: 3%; --w: 89%; --h: 8%;"><span>1</span></div>
                                            <div class="media-hotspot" style="--x: 3%; --y: 21%; --w: 46%; --h: 68%;"><span>2</span></div>
                                            <div class="media-hotspot marker-right" style="--x: 49%; --y: 29%; --w: 48%; --h: 50%;"><span>3</span></div>
                                        </div>
                                        <div class="media-note-list">
                                            <div><span>1</span><p><strong>Logo dan navigasi</strong> digunakan untuk berpindah ke beranda, materi, sumber belajar, login, atau registrasi.</p></div>
                                            <div><span>2</span><p><strong>Bagian pembuka</strong> mengenalkan tujuan media dan tombol untuk mulai belajar.</p></div>
                                            <div><span>3</span><p><strong>Pratinjau fitur</strong> memperlihatkan gambaran sandbox, komponen UI, dan alur latihan.</p></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="media-guide-login" class="media-accordion-item scroll-mt-32">
                                <button @click="activeVisualGuide = activeVisualGuide === 'login' ? null : 'login'" class="media-accordion-trigger" type="button">
                                    <span class="media-accordion-icon bg-red-100 text-red-700 dark:bg-red-500/10 dark:text-red-300">02</span>
                                    <span class="flex-1">
                                        <span class="media-accordion-title">Buat Akun dan Login</span>
                                        <span class="media-accordion-subtitle">Pengguna harus membuat akun terlebih dahulu agar progres belajar, kuis, dan lab tersimpan.</span>
                                    </span>
                                    <svg :class="activeVisualGuide === 'login' ? 'rotate-180 text-red-500' : 'text-slate-400'" class="w-5 h-5 transition-transform shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div x-show="activeVisualGuide === 'login'" x-transition.opacity style="display: none;" class="media-accordion-content">
                                    <div class="media-guide-grid">
                                        <div class="interactive-shot real-media-shot">
                                            <img class="guide-screenshot" src="{{ asset('images/guides/student-login.png') }}" alt="Tampilan asli halaman login Utilwind" loading="eager" decoding="async">
                                            <div class="media-hotspot" style="--x: 13%; --y: 17%; --w: 34%; --h: 56%;"><span>1</span></div>
                                            <div class="media-hotspot" style="--x: 14%; --y: 64%; --w: 31%; --h: 9%;"><span>2</span></div>
                                            <div class="media-hotspot marker-right" style="--x: 55%; --y: 16%; --w: 32%; --h: 67%;"><span>3</span></div>
                                        </div>
                                        <div class="media-note-list">
                                            <div><span>1</span><p><strong>Form registrasi</strong> digunakan untuk membuat akun baru dengan nama, email, kelas, dan kata sandi.</p></div>
                                            <div><span>2</span><p><strong>Tombol akses</strong> membawa pengguna masuk ke dasbor setelah akun berhasil dibuat atau login.</p></div>
                                            <div><span>3</span><p><strong>Panel kanan</strong> menunjukkan bahwa media memuat kode, preview, dan latihan interaktif.</p></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="media-guide-token" class="media-accordion-item scroll-mt-32">
                                <button @click="activeVisualGuide = activeVisualGuide === 'token' ? null : 'token'" class="media-accordion-trigger" type="button">
                                    <span class="media-accordion-icon bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-300">03</span>
                                    <span class="flex-1">
                                        <span class="media-accordion-title">Dasbor dan Token Kelas</span>
                                        <span class="media-accordion-subtitle">Setelah login, masukkan token kelas pada dasbor agar menu materi dapat digunakan.</span>
                                    </span>
                                    <svg :class="activeVisualGuide === 'token' ? 'rotate-180 text-red-500' : 'text-slate-400'" class="w-5 h-5 transition-transform shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div x-show="activeVisualGuide === 'token'" x-transition.opacity style="display: none;" class="media-accordion-content">
                                    <div class="media-guide-grid">
                                        <div class="interactive-shot real-media-shot">
                                            <img class="guide-screenshot" src="{{ asset('images/guides/student-dashboard-token.png') }}" alt="Tampilan asli input token kelas pada dasbor Utilwind" loading="eager" decoding="async">
                                            <div class="media-hotspot" style="--x: 23%; --y: 15%; --w: 13%; --h: 55%;"><span>1</span></div>
                                            <div class="media-hotspot marker-right" style="--x: 32%; --y: 24%; --w: 36%; --h: 51%;"><span>2</span></div>
                                            <div class="media-hotspot marker-right" style="--x: 54%; --y: 65%; --w: 12%; --h: 8%;"><span>3</span></div>
                                        </div>
                                        <div class="media-note-list">
                                            <div><span>1</span><p><strong>Tombol gabung kelas</strong> dibuka dari dasbor setelah pengguna berhasil login.</p></div>
                                            <div><span>2</span><p><strong>Input token kelas</strong> diisi dengan kode enam karakter yang diberikan oleh instruktur.</p></div>
                                            <div><span>3</span><p><strong>Gabung Kelas</strong> menyimpan kelas pengguna; setelah berhasil, materi belajar dapat dibuka.</p></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="media-guide-materi" class="media-accordion-item scroll-mt-32">
                                <button @click="activeVisualGuide = activeVisualGuide === 'materi' ? null : 'materi'" class="media-accordion-trigger" type="button">
                                    <span class="media-accordion-icon bg-indigo-100 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-300">04</span>
                                    <span class="flex-1">
                                        <span class="media-accordion-title">Materi Belajar</span>
                                        <span class="media-accordion-subtitle">Materi dibaca setelah akun terhubung ke kelas; ikuti bab dan submateri sesuai alur tutorial.</span>
                                    </span>
                                    <svg :class="activeVisualGuide === 'materi' ? 'rotate-180 text-red-500' : 'text-slate-400'" class="w-5 h-5 transition-transform shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div x-show="activeVisualGuide === 'materi'" x-transition.opacity style="display: none;" class="media-accordion-content">
                                    <div class="media-guide-grid">
                                        <div class="interactive-shot real-media-shot">
                                            <img class="guide-screenshot" src="{{ asset('images/guides/student-materi.png') }}" alt="Tampilan asli halaman silabus materi Utilwind" loading="eager" decoding="async">
                                            <div class="media-hotspot" style="--x: 2%; --y: 15%; --w: 55%; --h: 39%;"><span>1</span></div>
                                            <div class="media-hotspot marker-right" style="--x: 67%; --y: 30%; --w: 30%; --h: 24%;"><span>2</span></div>
                                            <div class="media-hotspot" style="--x: 2%; --y: 67%; --w: 95%; --h: 32%;"><span>3</span></div>
                                        </div>
                                        <div class="media-note-list">
                                            <div><span>1</span><p><strong>Silabus dan progres</strong> menunjukkan posisi belajar setelah siswa berhasil bergabung ke kelas.</p></div>
                                            <div><span>2</span><p><strong>Bar Progres</strong> menunjukkan persentase progres belajar keseluruhan materi, kuis, dan lab.</p></div>
                                            <div><span>3</span><p><strong>Materi pembelajaran</strong> digunakan untuk mengakses pembelajaran per navigasi subbab.</p></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="media-guide-isi-materi" class="media-accordion-item scroll-mt-32">
                                <button @click="activeVisualGuide = activeVisualGuide === 'isi-materi' ? null : 'isi-materi'" class="media-accordion-trigger" type="button">
                                    <span class="media-accordion-icon bg-sky-100 text-sky-700 dark:bg-sky-500/10 dark:text-sky-300">05</span>
                                    <span class="flex-1">
                                        <span class="media-accordion-title">Dalam Materi</span>
                                        <span class="media-accordion-subtitle">Baca tutorial, amati contoh, lalu ikuti instruksi sampai cek pemahaman selesai.</span>
                                    </span>
                                    <svg :class="activeVisualGuide === 'isi-materi' ? 'rotate-180 text-red-500' : 'text-slate-400'" class="w-5 h-5 transition-transform shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div x-show="activeVisualGuide === 'isi-materi'" x-transition.opacity style="display: none;" class="media-accordion-content">
                                    <div class="media-guide-grid">
                                        <div class="interactive-shot real-media-shot">
                                            <img class="guide-screenshot" src="{{ asset('images/guides/student-dalam-materi.png') }}" alt="Tampilan asli halaman dalam materi Utilwind" loading="eager" decoding="async">
                                            <div class="media-hotspot" style="--x: 26%; --y: 12%; --w: 74%; --h: 9%;"><span>1</span></div>
                                            <div class="media-hotspot" style="--x: 1%; --y: 12%; --w: 25%; --h: 84%;"><span>2</span></div>
                                            <div class="media-hotspot marker-right" style="--x: 31%; --y: 28%; --w: 65%; --h: 69%;"><span>3</span></div>
                                        </div>
                                        <div class="media-note-list">
                                            <div><span>1</span><p><strong>Header submateri</strong> menunjukkan judul, posisi belajar, dan progres tutorial yang sedang dibuka.</p></div>
                                            <div><span>2</span><p><strong>Konten utama</strong> dibaca secara berurutan untuk memahami penjelasan, contoh visual, dan instruksi lanjut.</p></div>
                                            <div><span>3</span><p><strong>Panel pendukung</strong> memuat urutan konsep, cuplikan kode, simulasi singkat, serta cek pemahaman.</p></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="media-guide-kuis" class="media-accordion-item scroll-mt-32">
                                <button @click="activeVisualGuide = activeVisualGuide === 'kuis' ? null : 'kuis'" class="media-accordion-trigger" type="button">
                                    <span class="media-accordion-icon bg-fuchsia-100 text-fuchsia-700 dark:bg-fuchsia-500/10 dark:text-fuchsia-300">06</span>
                                    <span class="flex-1">
                                        <span class="media-accordion-title">Kuis dan Syarat Kelulusan</span>
                                        <span class="media-accordion-subtitle">Evaluasi pemahaman teori dengan nilai lulus minimal 70.</span>
                                    </span>
                                    <svg :class="activeVisualGuide === 'kuis' ? 'rotate-180 text-red-500' : 'text-slate-400'" class="w-5 h-5 transition-transform shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div x-show="activeVisualGuide === 'kuis'" x-transition.opacity style="display: none;" class="media-accordion-content">
                                    <div class="media-guide-grid">
                                        <div class="interactive-shot real-media-shot">
                                            <img class="guide-screenshot" src="{{ asset('images/guides/student-kuis.png') }}" alt="Tampilan asli halaman hasil evaluasi Utilwind" loading="eager" decoding="async">
                                            <div class="media-hotspot" style="--x: 2%; --y: 6%; --w: 54%; --h: 22%;"><span>1</span></div>
                                            <div class="media-hotspot" style="--x: 2%; --y: 32%; --w: 29%; --h: 47%;"><span>2</span></div>
                                            <div class="media-hotspot marker-right" style="--x: 32%; --y: 32%; --w: 65%; --h: 47%;"><span>3</span></div>
                                        </div>
                                        <div class="media-note-list">
                                            <div><span>1</span><p><strong>Identitas evaluasi</strong> menunjukkan bab yang dinilai dan tombol kembali ke dasbor.</p></div>
                                            <div><span>2</span><p><strong>Skor akhir</strong> menjadi acuan kelulusan; nilai minimal yang harus dicapai adalah 70.</p></div>
                                            <div><span>3</span><p><strong>Ringkasan hasil</strong> menampilkan kelengkapan, ketepatan, durasi, fokus, dan tinjauan jawaban.</p></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="media-guide-praktik" class="media-accordion-item scroll-mt-32">
                                <button @click="activeVisualGuide = activeVisualGuide === 'praktik' ? null : 'praktik'" class="media-accordion-trigger" type="button">
                                    <span class="media-accordion-icon bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300">07</span>
                                    <span class="flex-1">
                                        <span class="media-accordion-title">Praktik Lab dan Validasi</span>
                                        <span class="media-accordion-subtitle">Ruang latihan kode dengan instruksi, editor, preview, dan verifikasi.</span>
                                    </span>
                                    <svg :class="activeVisualGuide === 'praktik' ? 'rotate-180 text-red-500' : 'text-slate-400'" class="w-5 h-5 transition-transform shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div x-show="activeVisualGuide === 'praktik'" x-transition.opacity style="display: none;" class="media-accordion-content">
                                    <div class="media-guide-grid">
                                        <div class="interactive-shot real-media-shot">
                                            <img class="guide-screenshot" src="{{ asset('images/guides/student-praktik.png') }}" alt="Tampilan asli ruang kerja praktik lab Utilwind" loading="eager" decoding="async">
                                            <div class="media-hotspot" style="--x: 0%; --y: 7%; --w: 25%; --h: 90%;"><span>1</span></div>
                                            <div class="media-hotspot marker-right" style="--x: 25%; --y: 7%; --w: 30%; --h: 90%;"><span>2</span></div>
                                            <div class="media-hotspot marker-right" style="--x: 55%; --y: 7%; --w: 45%; --h: 90%;"><span>3</span></div>
                                        </div>
                                        <div class="media-note-list">
                                            <div><span>1</span><p><strong>Daftar tugas</strong> menunjukkan langkah praktik yang harus diselesaikan secara bertahap.</p></div>
                                            <div><span>2</span><p><strong>Editor kode</strong> digunakan untuk menulis HTML dan class Tailwind sesuai instruksi.</p></div>
                                            <div><span>3</span><p><strong>Preview dan terminal</strong> menampilkan hasil kerja serta umpan balik validasi lab.</p></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="media-guide-analytics" class="media-accordion-item scroll-mt-32">
                                <button @click="activeVisualGuide = activeVisualGuide === 'analytics' ? null : 'analytics'" class="media-accordion-trigger" type="button">
                                    <span class="media-accordion-icon bg-blue-100 text-blue-700 dark:bg-blue-500/10 dark:text-blue-300">08</span>
                                    <span class="flex-1">
                                        <span class="media-accordion-title">Learning Analytics</span>
                                        <span class="media-accordion-subtitle">Ringkasan progres, nilai kuis, nilai lab, dan riwayat belajar.</span>
                                    </span>
                                    <svg :class="activeVisualGuide === 'analytics' ? 'rotate-180 text-red-500' : 'text-slate-400'" class="w-5 h-5 transition-transform shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div x-show="activeVisualGuide === 'analytics'" x-transition.opacity style="display: none;" class="media-accordion-content">
                                    <div class="media-guide-grid">
                                        <div class="interactive-shot real-media-shot">
                                            <img class="guide-screenshot" src="{{ asset('images/guides/student-analytics.png') }}" alt="Tampilan asli dashboard learning analytics Utilwind" loading="eager" decoding="async">
                                            <div class="media-hotspot" style="--x: 0%; --y: 12%; --w: 20%; --h: 88%;"><span>1</span></div>
                                            <div class="media-hotspot marker-right" style="--x: 23%; --y: 20%; --w: 74%; --h: 33%;"><span>2</span></div>
                                            <div class="media-hotspot marker-right" style="--x: 23%; --y: 61%; --w: 74%; --h: 36%;"><span>3</span></div>
                                        </div>
                                        <div class="media-note-list">
                                            <div><span>1</span><p><strong>Sidebar siswa</strong> digunakan untuk membuka overview, materi belajar, pengaturan, dan informasi sistem.</p></div>
                                            <div><span>2</span><p><strong>Ringkasan progres</strong> menampilkan status kelas dan persentase penyelesaian modul.</p></div>
                                            <div><span>3</span><p><strong>Kartu analitik</strong> merangkum materi bacaan, praktik lab, rata-rata kuis, dan bab yang sudah lulus.</p></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
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
    .reveal-up { opacity: 0; transform: translateY(20px); animation: revealAnim 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
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
    .delay-100 { animation-delay: 0.1s; }

    .media-accordion-item {
        border: 1px solid rgba(226, 232, 240, 0.9); border-radius: 1.35rem;
        overflow: hidden; background: rgba(248, 250, 252, 0.65); transition: border-color 0.25s ease, background 0.25s ease, transform 0.25s ease, box-shadow 0.25s ease;
    }
    .dark .media-accordion-item { border-color: rgba(255,255,255,0.06); background: rgba(255,255,255,0.025); }
    .media-accordion-item:hover {
        border-color: rgba(239, 68, 68, 0.34);
        transform: translateY(-2px);
        box-shadow: 0 18px 42px -28px rgba(15, 23, 42, 0.38);
    }
    .dark .media-accordion-item:hover { box-shadow: 0 18px 42px -28px rgba(0, 0, 0, 0.9); }
    .media-accordion-trigger {
        width: 100%; display: flex; align-items: center; gap: 1rem; padding: 1rem 1.1rem;
        text-align: left; transition: background 0.25s ease;
    }
    .media-accordion-trigger:hover { background: rgba(255,255,255,0.72); }
    .dark .media-accordion-trigger:hover { background: rgba(255,255,255,0.035); }
    .media-accordion-icon {
        width: 2.5rem; height: 2.5rem; border-radius: 0.95rem; display: inline-flex; align-items: center; justify-content: center;
        flex-shrink: 0; font-size: 0.72rem; font-weight: 900; transition: transform 0.25s ease;
    }
    .media-accordion-title { display: block; font-size: 0.95rem; font-weight: 900; color: #0f172a; line-height: 1.25; }
    .dark .media-accordion-title { color: #fff; }
    .media-accordion-subtitle { display: block; margin-top: 0.2rem; font-size: 0.76rem; color: #64748b; line-height: 1.45; }
    .dark .media-accordion-subtitle { color: #94a3b8; }
    .media-accordion-content { padding: 0 1rem 1rem; }
    .media-guide-grid { display: grid; grid-template-columns: minmax(0, 1.7fr) minmax(260px, 0.8fr); gap: 1.25rem; align-items: stretch; }
    .media-guide-nav {
        display: flex; gap: 0.55rem; overflow-x: auto; max-width: 100%; padding-bottom: 0.15rem;
    }
    .media-guide-chip {
        display: inline-flex; align-items: center; white-space: nowrap; padding: 0.58rem 0.78rem;
        border-radius: 999px; border: 1px solid #e2e8f0; background: rgba(255,255,255,0.72);
        color: #64748b; font-size: 0.7rem; font-weight: 900;
        transition: transform 0.2s ease, border-color 0.2s ease, background 0.2s ease, color 0.2s ease, box-shadow 0.2s ease;
    }
    .dark .media-guide-chip { background: rgba(255,255,255,0.035); border-color: rgba(255,255,255,0.08); color: #94a3b8; }
    .media-guide-chip:hover { transform: translateY(-1px); color: #0f172a; border-color: #bae6fd; box-shadow: 0 10px 24px -20px rgba(6,182,212,0.42); }
    .dark .media-guide-chip:hover { color: #fff; border-color: rgba(103,232,249,0.28); }
    .media-guide-chip.active { color: #0891b2; background: #ecfeff; border-color: #a5f3fc; box-shadow: 0 12px 28px -22px rgba(6,182,212,0.58); }
    .dark .media-guide-chip.active { color: #67e8f9; background: rgba(6,182,212,0.13); border-color: rgba(103,232,249,0.25); }

    .interactive-shot {
        position: relative; min-height: 400px; aspect-ratio: 16 / 9; overflow: hidden; border-radius: 1.15rem;
        background: #f8fafc; border: 1px solid #e2e8f0; box-shadow: inset 0 0 0 1px rgba(255,255,255,0.7);
        cursor: crosshair; transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
    }
    .dark .interactive-shot { background: #080d16; border-color: rgba(255,255,255,0.08); box-shadow: inset 0 0 0 1px rgba(255,255,255,0.03); }
    .interactive-shot:hover {
        transform: translateY(-4px) scale(1.006);
        border-color: rgba(239, 68, 68, 0.42);
        box-shadow: 0 28px 60px -36px rgba(15, 23, 42, 0.55), inset 0 0 0 1px rgba(255,255,255,0.75);
    }
    .dark .interactive-shot:hover {
        border-color: rgba(248, 113, 113, 0.38);
        box-shadow: 0 28px 60px -36px rgba(0, 0, 0, 0.95), inset 0 0 0 1px rgba(255,255,255,0.05);
    }
    .interactive-shot::after {
        content: ''; position: absolute; inset: 0; pointer-events: none;
        background: radial-gradient(circle at 20% 10%, rgba(6,182,212,0.08), transparent 25%),
                    radial-gradient(circle at 90% 15%, rgba(99,102,241,0.08), transparent 30%);
    }
    .real-media-shot {
        background: #fff;
        cursor: zoom-in;
        min-height: 0;
    }
    .real-media-shot::after { display: none; }
    .guide-screenshot {
        position: absolute; inset: 0; z-index: 1; display: block;
        width: 100%; height: 100%; object-fit: cover; object-position: top center;
        user-select: none; pointer-events: none;
    }
    .media-hotspot {
        position: absolute; left: var(--x); top: var(--y); width: var(--w); height: var(--h);
        box-sizing: border-box; border: 2px solid rgba(239, 68, 68, 0.82); border-radius: 1rem; z-index: 20;
        background: rgba(239, 68, 68, 0.025); box-shadow: inset 0 0 0 1px rgba(255,255,255,0.35);
        pointer-events: none;
        transition: border-color 0.25s ease, box-shadow 0.25s ease, transform 0.25s ease, background-color 0.25s ease;
    }
    .media-hotspot::after {
        content: ''; position: absolute; inset: 0; background: rgba(239, 68, 68, 0.04);
        opacity: 0; transition: opacity 0.25s ease;
    }
    .interactive-shot:hover .media-hotspot {
        border-color: #fb7185;
        background-color: rgba(239, 68, 68, 0.03);
        box-shadow: 0 0 0 4px rgba(239,68,68,0.08), 0 14px 28px rgba(239,68,68,0.12), inset 0 0 0 1px rgba(255,255,255,0.42);
        transform: none;
    }
    .interactive-shot:hover .media-hotspot::after { opacity: 1; }
    .media-hotspot span {
        position: absolute; left: 0.45rem; top: 0.45rem; transform: none;
        width: 1.62rem; height: 1.62rem; border-radius: 999px; background: #ef4444; color: white;
        display: inline-flex; align-items: center; justify-content: center; font-weight: 900; font-size: 0.78rem;
        box-shadow: 0 10px 22px rgba(239, 68, 68, 0.2); transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .media-hotspot.marker-right span { left: 0.45rem; right: auto; }
    .interactive-shot:hover .media-hotspot span { transform: scale(1.08); box-shadow: 0 12px 28px rgba(239, 68, 68, 0.34); }

    .media-note-list {
        display: flex; flex-direction: column; justify-content: center; gap: 0.85rem;
        border-radius: 1.15rem; background: #ffffff; border: 1px solid #e2e8f0; padding: 1rem;
    }
    .dark .media-note-list { background: rgba(255,255,255,0.03); border-color: rgba(255,255,255,0.06); }
    .media-note-list > div { display: flex; gap: 0.8rem; align-items: flex-start; }
    .media-note-list > div {
        padding: 0.65rem; border-radius: 0.95rem; transition: transform 0.25s ease, background 0.25s ease;
    }
    .media-note-list > div:hover { transform: translateX(4px); background: #fff1f2; }
    .dark .media-note-list > div:hover { background: rgba(244, 63, 94, 0.08); }
    .media-note-list span {
        width: 1.65rem; height: 1.65rem; border-radius: 999px; display: inline-flex; align-items: center; justify-content: center;
        flex-shrink: 0; background: #ef4444; color: #fff; font-size: 0.72rem; font-weight: 900;
    }
    .media-note-list p { font-size: 0.82rem; line-height: 1.65; color: #475569; margin: 0; }
    .dark .media-note-list p { color: #cbd5e1; }
    .media-note-list strong { color: #0f172a; }
    .dark .media-note-list strong { color: #fff; }

    .media-accordion-item:hover .media-accordion-icon { transform: translateY(-1px) scale(1.04); }

    .mock-logo {
        width: 2rem; height: 2rem; border-radius: 0.7rem; display: inline-flex; align-items: center; justify-content: center;
        color: white; font-weight: 900; background: linear-gradient(135deg, #06b6d4, #6366f1);
    }
    .mock-pill, .mock-action, .mock-kicker, .auth-helper, .auth-link, .mock-code-line, .chapter-topic, .lab-step, .lab-instruction, .list-row {
        border-radius: 999px; background: #e2e8f0;
    }
    .dark .mock-pill, .dark .mock-action, .dark .mock-kicker, .dark .auth-helper, .dark .auth-link, .dark .mock-code-line, .dark .chapter-topic, .dark .lab-step, .dark .lab-instruction, .dark .list-row {
        background: rgba(255,255,255,0.12);
    }
    .mock-pill { height: 0.5rem; }
    .mock-action { width: 5.2rem; height: 1.75rem; background: #0f172a; }
    .dark .mock-action { background: #fff; }
    .mock-kicker { width: 5.4rem; height: 0.55rem; background: #67e8f9; }
    .mock-code-line { height: 0.62rem; width: 68%; background: rgba(103,232,249,0.75); }
    .mock-code-line.long { width: 88%; }
    .mock-code-line.short { width: 48%; }
    .preview-window-bar { display: flex; gap: 0.35rem; align-items: center; }
    .preview-window-bar i { display: block; width: 0.58rem; height: 0.58rem; border-radius: 999px; background: #ef4444; }
    .preview-window-bar i:nth-child(2) { background: #f59e0b; }
    .preview-window-bar i:nth-child(3) { background: #10b981; }

    .landing-shot {
        background: linear-gradient(135deg, #020617 0%, #0f172a 48%, #111827 100%);
    }
    .landing-nav-mock {
        position: absolute; left: 4%; right: 4%; top: 6%; height: 12%; border-radius: 0.95rem;
        display: flex; align-items: center; gap: 0.8rem; padding: 0 1rem; z-index: 2;
        background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.09);
        transition: transform 0.28s ease, box-shadow 0.28s ease;
    }
    .landing-nav-mock .mock-pill { background: rgba(255,255,255,0.18); }
    .landing-nav-mock .mock-action { background: linear-gradient(135deg, #06b6d4, #6366f1); }
    .landing-hero-mock {
        position: absolute; inset: 26% 6% 8%; display: grid; grid-template-columns: 1fr 0.92fr; gap: 6%; z-index: 1;
    }
    .landing-copy-mock { display: flex; flex-direction: column; justify-content: center; min-width: 0; }
    .landing-copy-mock h5 {
        margin: 0.8rem 0 0.55rem; color: #fff; font-size: clamp(1.4rem, 3vw, 2.1rem);
        line-height: 1.03; font-weight: 950; letter-spacing: 0;
    }
    .landing-copy-mock p { max-width: 22rem; margin: 0; color: rgba(226,232,240,0.82); font-size: 0.78rem; line-height: 1.7; }
    .mock-button-row { display: flex; gap: 0.7rem; margin-top: 1rem; }
    .mock-button-row span { height: 2.1rem; border-radius: 0.75rem; display: block; }
    .mock-button-row span:first-child { width: 7.4rem; background: linear-gradient(135deg, #06b6d4, #2563eb); }
    .mock-button-row span:last-child { width: 6rem; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.14); }
    .landing-preview-mock {
        border-radius: 1.15rem; padding: 1rem; display: flex; flex-direction: column; justify-content: center; gap: 0.8rem;
        background: rgba(13,17,23,0.82); border: 1px solid rgba(255,255,255,0.12);
        transition: transform 0.28s ease, box-shadow 0.28s ease;
    }
    .landing-preview-mock .mock-code-line:nth-of-type(3) { background: rgba(167,139,250,0.78); }
    .mock-preview-box { height: 4.2rem; border-radius: 0.9rem; background: linear-gradient(135deg, #06b6d4, #6366f1); box-shadow: 0 18px 34px -24px #22d3ee; }
    .mock-feature-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.45rem; }
    .mock-feature-row span { height: 2.25rem; border-radius: 0.7rem; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.08); }

    .auth-shot {
        display: grid; grid-template-columns: 0.94fr 1.06fr; gap: 4%; padding: 5%;
        background: linear-gradient(135deg, #f8fafc 0%, #eef6ff 52%, #eef2ff 100%);
    }
    .dark .auth-shot { background: linear-gradient(135deg, #020617 0%, #0a0e17 56%, #111827 100%); }
    .auth-form-mock, .auth-showcase-mock {
        border-radius: 1.2rem; z-index: 1; transition: transform 0.28s ease, box-shadow 0.28s ease;
    }
    .auth-form-mock {
        background: rgba(255,255,255,0.92); border: 1px solid rgba(226,232,240,0.9); padding: 1.15rem;
        display: flex; flex-direction: column; justify-content: center; gap: 0.8rem;
    }
    .dark .auth-form-mock { background: rgba(15,23,42,0.9); border-color: rgba(255,255,255,0.08); }
    .auth-form-mock h5 { margin: 0.15rem 0 0.25rem; font-size: 1.4rem; font-weight: 950; color: #0f172a; }
    .dark .auth-form-mock h5 { color: #fff; }
    .auth-input { height: 2.75rem; border-radius: 0.85rem; background: #f8fafc; border: 1px solid #e2e8f0; }
    .auth-input.active { border-color: #06b6d4; box-shadow: 0 0 0 4px rgba(6,182,212,0.08); }
    .dark .auth-input { background: rgba(2,6,23,0.65); border-color: rgba(255,255,255,0.08); }
    .auth-helper { width: 58%; height: 0.55rem; }
    .auth-submit {
        height: 2.8rem; border-radius: 0.9rem; display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 0.72rem; font-weight: 900; background: linear-gradient(135deg, #0f172a, #1e293b);
    }
    .dark .auth-submit { color: #020617; background: linear-gradient(135deg, #ffffff, #e2e8f0); }
    .auth-link { width: 42%; height: 0.45rem; margin: 0 auto; }
    .auth-showcase-mock {
        position: relative; overflow: hidden; background: #0a0e17; border: 1px solid rgba(255,255,255,0.08);
        display: flex; align-items: center; justify-content: center; padding: 1.2rem;
    }
    .auth-showcase-mock::before {
        content: ''; position: absolute; inset: 0;
        background: linear-gradient(135deg, rgba(6,182,212,0.13), rgba(217,70,239,0.12), rgba(99,102,241,0.12));
    }
    .showcase-status {
        position: absolute; top: 1rem; right: 1rem; z-index: 1; color: rgba(255,255,255,0.58);
        font-size: 0.55rem; font-weight: 900; text-transform: uppercase; letter-spacing: 0.08em; display: flex; gap: 0.35rem; align-items: center;
    }
    .showcase-status span { width: 0.45rem; height: 0.45rem; border-radius: 999px; background: #10b981; box-shadow: 0 0 10px #10b981; }
    .showcase-card {
        position: relative; z-index: 1; width: 76%; min-height: 68%; border-radius: 1rem; padding: 1rem;
        display: flex; flex-direction: column; justify-content: center; gap: 0.75rem;
        background: rgba(13,17,23,0.72); border: 1px solid rgba(255,255,255,0.1); transform: rotate(2deg);
        transition: transform 0.28s ease;
    }
    .showcase-card button {
        align-self: center; border: 0; border-radius: 999px; padding: 0.72rem 1.2rem; color: #fff; font-size: 0.7rem; font-weight: 900;
        background: linear-gradient(135deg, #06b6d4, #6366f1);
    }

    .lesson-shot {
        display: grid; grid-template-columns: minmax(0, 1.65fr) minmax(150px, 0.82fr); grid-template-rows: 30% 1fr; gap: 5%; padding: 5%;
        background: linear-gradient(135deg, #f8fafc 0%, #eef6ff 50%, #f5f3ff 100%);
    }
    .dark .lesson-shot { background: linear-gradient(135deg, #020617 0%, #0f172a 58%, #111827 100%); }
    .lesson-header-mock {
        grid-column: 1 / -1; display: flex; align-items: center; justify-content: space-between; gap: 1rem; z-index: 1;
        border-radius: 1.15rem; padding: 1rem 1.2rem; background: rgba(255,255,255,0.75); border: 1px solid rgba(226,232,240,0.85);
        transition: transform 0.28s ease, box-shadow 0.28s ease;
    }
    .dark .lesson-header-mock { background: rgba(255,255,255,0.04); border-color: rgba(255,255,255,0.08); }
    .lesson-header-mock h5 { margin: 0.55rem 0 0.2rem; font-size: 1.45rem; font-weight: 950; color: #0f172a; }
    .dark .lesson-header-mock h5 { color: #fff; }
    .lesson-header-mock p { margin: 0; color: #64748b; font-size: 0.72rem; }
    .dark .lesson-header-mock p { color: #94a3b8; }
    .lesson-progress-mock {
        min-width: 8.6rem; border-radius: 1rem; padding: 0.8rem; background: rgba(255,255,255,0.9); border: 1px solid #e2e8f0;
    }
    .dark .lesson-progress-mock { background: rgba(2,6,23,0.65); border-color: rgba(255,255,255,0.08); }
    .lesson-progress-mock small, .analytics-progress small { display: block; font-size: 0.55rem; font-weight: 900; text-transform: uppercase; letter-spacing: 0.08em; color: #64748b; }
    .lesson-progress-mock strong, .analytics-progress strong { display: block; color: #0f172a; font-size: 1.45rem; font-weight: 950; }
    .dark .lesson-progress-mock strong, .dark .analytics-progress strong { color: #fff; }
    .lesson-progress-mock span, .analytics-progress span, .chapter-progress {
        display: block; height: 0.55rem; border-radius: 999px; background: #e2e8f0; overflow: hidden;
    }
    .lesson-progress-mock i, .analytics-progress i, .chapter-progress span {
        display: block; height: 100%; width: 68%; background: linear-gradient(90deg, #06b6d4, #3b82f6);
    }
    .chapter-card-mock, .lesson-action-card {
        z-index: 1; border-radius: 1.15rem; background: rgba(255,255,255,0.86); border: 1px solid rgba(226,232,240,0.9);
        transition: transform 0.28s ease, box-shadow 0.28s ease;
    }
    .dark .chapter-card-mock, .dark .lesson-action-card { background: rgba(255,255,255,0.04); border-color: rgba(255,255,255,0.08); }
    .chapter-card-mock { padding: 1rem; display: flex; flex-direction: column; justify-content: center; }
    .chapter-label { align-self: flex-start; border-radius: 0.6rem; padding: 0.35rem 0.6rem; color: #0891b2; background: #ecfeff; border: 1px solid #cffafe; font-size: 0.58rem; font-weight: 950; }
    .chapter-card-mock h6 { margin: 0.7rem 0 0.25rem; color: #0f172a; font-size: 1rem; font-weight: 950; }
    .dark .chapter-card-mock h6 { color: #fff; }
    .chapter-card-mock p { margin: 0 0 0.8rem; color: #64748b; font-size: 0.68rem; line-height: 1.45; }
    .dark .chapter-card-mock p { color: #94a3b8; }
    .chapter-topic { height: 1.65rem; margin-top: 0.55rem; }
    .chapter-topic.active { background: rgba(16,185,129,0.16); border: 1px solid rgba(16,185,129,0.25); }
    .chapter-topic.locked { opacity: 0.55; }
    .lesson-action-stack { z-index: 1; display: flex; flex-direction: column; justify-content: center; gap: 0.85rem; }
    .lesson-action-card { padding: 1rem; font-size: 0.75rem; font-weight: 950; color: #0f172a; }
    .dark .lesson-action-card { color: #fff; }
    .lesson-action-card.lab { border-color: rgba(59,130,246,0.28); }
    .lesson-action-card.quiz { border-color: rgba(217,70,239,0.28); }

    .material-reader-shot {
        display: grid; grid-template-columns: minmax(0, 1.35fr) minmax(170px, 0.74fr); grid-template-rows: 23% 1fr; gap: 4%; padding: 5%;
        background: linear-gradient(135deg, #f8fafc 0%, #eef6ff 50%, #ecfdf5 100%);
    }
    .dark .material-reader-shot { background: linear-gradient(135deg, #020617 0%, #0f172a 58%, #111827 100%); }
    .material-reader-header {
        grid-column: 1 / -1; display: flex; align-items: center; justify-content: space-between; gap: 1rem; z-index: 1;
        border-radius: 1.15rem; padding: 0.95rem 1.1rem; background: rgba(255,255,255,0.78); border: 1px solid rgba(226,232,240,0.88);
        transition: transform 0.28s ease, box-shadow 0.28s ease;
    }
    .dark .material-reader-header { background: rgba(255,255,255,0.04); border-color: rgba(255,255,255,0.08); }
    .material-reader-header h5 { margin: 0.45rem 0 0.2rem; font-size: 1.35rem; font-weight: 950; color: #0f172a; }
    .dark .material-reader-header h5 { color: #fff; }
    .material-reader-header p { margin: 0; color: #64748b; font-size: 0.68rem; line-height: 1.45; }
    .dark .material-reader-header p { color: #94a3b8; }
    .material-progress-pill {
        min-width: 6.8rem; border-radius: 0.95rem; padding: 0.72rem 0.8rem; text-align: center;
        background: rgba(255,255,255,0.92); border: 1px solid #e2e8f0;
    }
    .dark .material-progress-pill { background: rgba(2,6,23,0.65); border-color: rgba(255,255,255,0.08); }
    .material-progress-pill strong { display: block; color: #0f172a; font-size: 1.2rem; line-height: 1; font-weight: 950; }
    .dark .material-progress-pill strong { color: #fff; }
    .material-progress-pill span { display: block; margin-top: 0.22rem; color: #64748b; font-size: 0.55rem; font-weight: 950; text-transform: uppercase; }
    .dark .material-progress-pill span { color: #94a3b8; }
    .material-content-card, .material-side-panel, .material-check-card {
        z-index: 1; border-radius: 1.15rem; background: rgba(255,255,255,0.88); border: 1px solid rgba(226,232,240,0.9);
        transition: transform 0.28s ease, box-shadow 0.28s ease;
    }
    .dark .material-content-card, .dark .material-side-panel, .dark .material-check-card { background: rgba(255,255,255,0.04); border-color: rgba(255,255,255,0.08); }
    .material-content-card { display: flex; flex-direction: column; gap: 0.5rem; padding: 0.82rem; min-width: 0; }
    .material-title-line, .material-paragraph-line, .material-nav-row span, .material-check-row {
        border-radius: 999px; background: #e2e8f0;
    }
    .dark .material-title-line, .dark .material-paragraph-line, .dark .material-nav-row span, .dark .material-check-row { background: rgba(255,255,255,0.12); }
    .material-title-line { width: 58%; height: 0.72rem; background: #38bdf8; }
    .material-title-line.wide { width: 72%; }
    .material-paragraph-line { width: 92%; height: 0.55rem; }
    .material-paragraph-line.short { width: 64%; }
    .material-demo-box {
        min-height: 5.1rem; border-radius: 1rem; padding: 0.65rem; display: grid; grid-template-columns: 0.62fr 1fr; gap: 0.65rem; align-items: center;
        background: #f8fafc; border: 1px solid #e2e8f0;
    }
    .dark .material-demo-box { background: rgba(2,6,23,0.65); border-color: rgba(255,255,255,0.08); }
    .material-preview-chip { height: 3.85rem; border-radius: 0.85rem; background: linear-gradient(135deg, #06b6d4, #10b981); box-shadow: 0 16px 32px -24px rgba(6,182,212,0.8); }
    .material-preview-card {
        height: 3.85rem; border-radius: 0.85rem; padding: 0.62rem; display: flex; flex-direction: column; justify-content: center; gap: 0.42rem;
        background: #fff; border: 1px solid #e2e8f0;
    }
    .dark .material-preview-card { background: rgba(15,23,42,0.9); border-color: rgba(255,255,255,0.08); }
    .material-preview-card span { height: 0.5rem; border-radius: 999px; background: #cbd5e1; }
    .material-preview-card span:nth-child(1) { width: 82%; background: #67e8f9; }
    .material-preview-card span:nth-child(2) { width: 64%; }
    .material-preview-card span:nth-child(3) { width: 46%; background: #bbf7d0; }
    .material-nav-row { display: flex; align-items: center; justify-content: space-between; gap: 0.75rem; margin-top: auto; }
    .material-nav-row span { width: 5.6rem; height: 0.55rem; }
    .material-nav-row strong {
        border-radius: 0.75rem; padding: 0.58rem 0.75rem; color: #fff; background: #0f172a;
        font-size: 0.58rem; line-height: 1; font-weight: 950;
    }
    .dark .material-nav-row strong { color: #020617; background: #fff; }
    .material-side-stack { z-index: 1; min-width: 0; display: grid; grid-template-rows: 1fr auto; gap: 0.65rem; }
    .material-side-panel, .material-check-card { padding: 0.72rem; display: flex; flex-direction: column; gap: 0.45rem; }
    .material-step-pill {
        min-height: 1.32rem; border-radius: 0.68rem; padding: 0 0.58rem; display: flex; align-items: center;
        color: #64748b; background: #f1f5f9; border: 1px solid #e2e8f0; font-size: 0.58rem; font-weight: 950;
    }
    .dark .material-step-pill { color: #94a3b8; background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.08); }
    .material-step-pill.active { color: #0f766e; background: rgba(16,185,129,0.12); border-color: rgba(16,185,129,0.26); }
    .dark .material-step-pill.active { color: #5eead4; background: rgba(20,184,166,0.12); border-color: rgba(45,212,191,0.22); }
    .material-code-panel {
        margin-top: 0.12rem; border-radius: 0.78rem; padding: 0.56rem; display: flex; flex-direction: column; gap: 0.35rem;
        background: #0f172a; border: 1px solid rgba(15,23,42,0.12);
    }
    .material-code-panel .code-line { height: 0.42rem; }
    .material-check-card strong { color: #0f172a; font-size: 0.68rem; font-weight: 950; }
    .dark .material-check-card strong { color: #fff; }
    .material-check-row { height: 1rem; border-radius: 0.58rem; }
    .material-check-row.active { background: rgba(6,182,212,0.16); border: 1px solid rgba(6,182,212,0.24); }

    .quiz-shot { background: #020617; }
    .quiz-topbar-mock {
        position: absolute; left: 4%; right: 4%; top: 5%; height: 14%; z-index: 1; display: flex; align-items: center; justify-content: space-between;
        border-radius: 1rem; padding: 0 1rem; background: rgba(15,23,42,0.78); border: 1px solid rgba(255,255,255,0.08);
        transition: transform 0.28s ease, box-shadow 0.28s ease;
    }
    .quiz-topbar-mock small { display: block; color: rgba(255,255,255,0.48); font-size: 0.55rem; font-weight: 950; text-transform: uppercase; }
    .quiz-topbar-mock span { color: #10b981; font-size: 0.68rem; font-weight: 950; }
    .quiz-topbar-mock strong { color: #fff; font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace; font-size: 1.35rem; }
    .quiz-avatar { width: 2.1rem; height: 2.1rem; border-radius: 999px; background: rgba(217,70,239,0.16); border: 1px solid rgba(217,70,239,0.24); }
    .quiz-main-mock {
        position: absolute; left: 6%; top: 25%; width: 61%; height: 61%; z-index: 1;
        display: flex; flex-direction: column; gap: 0.7rem; transition: transform 0.28s ease, box-shadow 0.28s ease;
    }
    .question-index { color: rgba(255,255,255,0.12); font-size: 2.3rem; font-weight: 950; line-height: 1; }
    .question-card-mock { border-radius: 1rem; padding: 1rem; background: rgba(255,255,255,0.035); border: 1px solid rgba(255,255,255,0.07); }
    .quiz-option { height: 1.72rem; border-radius: 0.75rem; background: rgba(255,255,255,0.055); border: 1px solid rgba(255,255,255,0.08); margin-top: 0.55rem; }
    .quiz-option.active { border-color: rgba(6,182,212,0.5); background: rgba(6,182,212,0.14); }
    .quiz-bottom-mock { display: flex; justify-content: space-between; align-items: center; margin-top: auto; }
    .quiz-bottom-mock button, .quiz-bottom-mock span {
        border: 0; border-radius: 0.85rem; padding: 0.68rem 0.9rem; font-size: 0.62rem; font-weight: 950;
    }
    .quiz-bottom-mock button { color: #fbbf24; background: rgba(245,158,11,0.12); border: 1px solid rgba(245,158,11,0.22); }
    .quiz-bottom-mock span { color: white; background: linear-gradient(135deg, #10b981, #14b8a6); }
    .quiz-side-mock {
        position: absolute; right: 6%; top: 25%; width: 22%; height: 61%; z-index: 1; border-radius: 1rem; padding: 0.9rem;
        background: rgba(15,23,42,0.78); border: 1px solid rgba(255,255,255,0.08);
        transition: transform 0.28s ease, box-shadow 0.28s ease;
    }
    .quiz-side-mock h6 { margin: 0 0 0.75rem; color: rgba(255,255,255,0.45); font-size: 0.55rem; font-weight: 950; text-transform: uppercase; }
    .quiz-number-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 0.35rem; }
    .quiz-number-grid span { aspect-ratio: 1; border-radius: 0.38rem; background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.07); }
    .quiz-number-grid span.active { background: #d946ef; }
    .quiz-number-grid span.done { background: rgba(16,185,129,0.25); border-color: rgba(16,185,129,0.38); }
    .quiz-warning { margin-top: 1rem; border-radius: 0.65rem; color: #fda4af; font-size: 0.62rem; font-weight: 950; text-align: center; padding: 0.55rem; background: rgba(244,63,94,0.1); border: 1px solid rgba(244,63,94,0.2); }

    .lab-shot {
        display: grid; grid-template-columns: 27% 31% 31%; gap: 3%; padding: 16% 4% 5%;
        background: #1e1e1e;
    }
    .lab-titlebar-mock {
        position: absolute; left: 4%; top: 6%; width: 27%; height: 8%; z-index: 1; display: flex; align-items: center; justify-content: space-between;
        border-radius: 0.75rem; padding: 0 0.75rem; color: #9ca3af; font-size: 0.55rem; font-weight: 950; background: #252526; border: 1px solid #3e3e42;
    }
    .lab-titlebar-mock strong { color: #fff; background: #3e3e42; border-radius: 0.35rem; padding: 0.12rem 0.35rem; }
    .lab-task, .lab-editor, .lab-preview {
        z-index: 1; min-width: 0; border-radius: 0.95rem; border: 1px solid #3e3e42; transition: transform 0.28s ease, box-shadow 0.28s ease;
    }
    .lab-task { background: #252526; padding: 0.9rem; display: flex; flex-direction: column; justify-content: center; }
    .lab-step { height: 1.55rem; margin-top: 0.5rem; background: #34383b; }
    .lab-step.active { background: rgba(0,122,204,0.18); border: 1px solid rgba(0,122,204,0.42); }
    .lab-step.locked { opacity: 0.5; }
    .lab-instruction { height: 3.2rem; margin-top: 0.65rem; border-radius: 0.75rem; background: #1e1e1e; border: 1px solid #3e3e42; }
    .lab-task button { align-self: flex-end; margin-top: 0.65rem; border: 0; border-radius: 0.55rem; padding: 0.48rem 0.7rem; color: #fff; background: #007acc; font-size: 0.58rem; font-weight: 950; }
    .lab-editor { background: #1e1e1e; display: flex; flex-direction: column; padding: 0.85rem; gap: 0.68rem; }
    .editor-tab { margin: -0.85rem -0.85rem 0.2rem; padding: 0.65rem 0.85rem; color: #fff; font-size: 0.62rem; font-weight: 800; background: #252526; border-bottom: 1px solid #3e3e42; }
    .code-line { height: 0.58rem; border-radius: 999px; background: rgba(103,232,249,0.72); }
    .code-line.long { width: 88%; }
    .code-line.mid { width: 68%; background: rgba(167,139,250,0.76); }
    .code-line.short { width: 46%; background: rgba(16,185,129,0.72); }
    .lab-preview { overflow: hidden; background: #fff; display: flex; flex-direction: column; }
    .preview-header { padding: 0.65rem 0.8rem; color: #555; background: #f3f3f3; border-bottom: 1px solid #e1e1e1; font-size: 0.55rem; font-weight: 950; }
    .preview-box { width: 64%; height: 5rem; border-radius: 0.9rem; margin: auto; background: linear-gradient(135deg, #06b6d4, #10b981); }
    .terminal-mock { padding: 0.55rem 0.75rem; color: #d1d5db; background: #1e1e1e; border-top: 1px solid #3e3e42; font-size: 0.58rem; font-weight: 800; }

    .analytics-shot {
        display: grid; grid-template-columns: 35% 33% 23%; grid-template-rows: 30% 1fr; gap: 5% 3%; padding: 5%;
        background: linear-gradient(135deg, #f8fafc 0%, #eef6ff 52%, #f5f3ff 100%);
    }
    .dark .analytics-shot { background: linear-gradient(135deg, #020617 0%, #0f172a 58%, #111827 100%); }
    .analytics-hero-mock, .analytics-metric-row, .analytics-chart, .analytics-list {
        z-index: 1; transition: transform 0.28s ease, box-shadow 0.28s ease;
    }
    .analytics-hero-mock {
        grid-column: 1 / -1; display: flex; justify-content: space-between; align-items: center; gap: 1rem;
        border-radius: 1.15rem; padding: 1rem 1.2rem; background: rgba(255,255,255,0.78); border: 1px solid rgba(226,232,240,0.85);
    }
    .dark .analytics-hero-mock { background: rgba(255,255,255,0.04); border-color: rgba(255,255,255,0.08); }
    .analytics-hero-mock h5 { margin: 0.55rem 0 0.2rem; font-size: 1.25rem; font-weight: 950; color: #0f172a; }
    .dark .analytics-hero-mock h5 { color: #fff; }
    .analytics-hero-mock p { margin: 0; color: #64748b; font-size: 0.68rem; }
    .dark .analytics-hero-mock p { color: #94a3b8; }
    .analytics-progress {
        min-width: 8.4rem; border-radius: 1rem; padding: 0.8rem; background: rgba(255,255,255,0.9); border: 1px solid #e2e8f0;
    }
    .dark .analytics-progress { background: rgba(2,6,23,0.65); border-color: rgba(255,255,255,0.08); }
    .analytics-metric-row { display: grid; grid-template-columns: 1fr; gap: 0.55rem; }
    .metric-card {
        border-radius: 0.9rem; padding: 0.65rem 0.75rem; background: #fff; border: 1px solid #e2e8f0;
    }
    .dark .metric-card { background: rgba(255,255,255,0.04); border-color: rgba(255,255,255,0.08); }
    .metric-card strong { display: block; font-size: 1.1rem; line-height: 1; font-weight: 950; color: #0f172a; }
    .dark .metric-card strong { color: #fff; }
    .metric-card span { color: #64748b; font-size: 0.55rem; font-weight: 950; text-transform: uppercase; }
    .metric-card.fuchsia { border-color: rgba(217,70,239,0.22); }
    .metric-card.blue { border-color: rgba(59,130,246,0.22); }
    .metric-card.emerald { border-color: rgba(16,185,129,0.22); }
    .analytics-chart, .analytics-list {
        border-radius: 1rem; background: rgba(255,255,255,0.88); border: 1px solid rgba(226,232,240,0.9); padding: 0.85rem;
    }
    .dark .analytics-chart, .dark .analytics-list { background: rgba(255,255,255,0.04); border-color: rgba(255,255,255,0.08); }
    .chart-toolbar { display: flex; justify-content: flex-end; gap: 0.35rem; margin-bottom: 0.8rem; }
    .chart-toolbar span { width: 2.2rem; height: 1rem; border-radius: 0.45rem; background: #e0f2fe; }
    .dark .chart-toolbar span { background: rgba(6,182,212,0.15); }
    .chart-bars { height: 8.6rem; display: flex; align-items: end; gap: 0.55rem; }
    .chart-bars span { flex: 1; border-radius: 0.42rem 0.42rem 0 0; background: #06b6d4; }
    .chart-bars span:nth-child(1) { height: 42%; }
    .chart-bars span:nth-child(2) { height: 70%; background: #3b82f6; }
    .chart-bars span:nth-child(3) { height: 58%; }
    .chart-bars span:nth-child(4) { height: 86%; background: #6366f1; }
    .chart-bars span:nth-child(5) { height: 76%; background: #10b981; }
    .analytics-list { display: flex; flex-direction: column; justify-content: center; gap: 0.7rem; }
    .list-row { height: 1.75rem; border-radius: 0.7rem; }
    .list-row.short { width: 76%; }

    .interactive-shot:hover .landing-nav-mock,
    .interactive-shot:hover .landing-preview-mock,
    .interactive-shot:hover .auth-form-mock,
    .interactive-shot:hover .auth-showcase-mock,
    .interactive-shot:hover .lesson-header-mock,
    .interactive-shot:hover .chapter-card-mock,
    .interactive-shot:hover .material-reader-header,
    .interactive-shot:hover .material-content-card,
    .interactive-shot:hover .quiz-topbar-mock,
    .interactive-shot:hover .quiz-main-mock,
    .interactive-shot:hover .lab-editor,
    .interactive-shot:hover .analytics-hero-mock,
    .interactive-shot:hover .analytics-chart {
        transform: none;
        box-shadow: 0 18px 38px -30px rgba(15, 23, 42, 0.52);
    }
    .interactive-shot:hover .showcase-card { transform: rotate(2deg); }
    .interactive-shot:hover .lesson-action-card,
    .interactive-shot:hover .material-side-panel,
    .interactive-shot:hover .material-check-card,
    .interactive-shot:hover .quiz-side-mock,
    .interactive-shot:hover .lab-task,
    .interactive-shot:hover .lab-preview,
    .interactive-shot:hover .analytics-metric-row,
    .interactive-shot:hover .analytics-list {
        transform: none;
    }
    .dark .interactive-shot:hover .landing-nav-mock,
    .dark .interactive-shot:hover .landing-preview-mock,
    .dark .interactive-shot:hover .auth-form-mock,
    .dark .interactive-shot:hover .auth-showcase-mock,
    .dark .interactive-shot:hover .lesson-header-mock,
    .dark .interactive-shot:hover .chapter-card-mock,
    .dark .interactive-shot:hover .material-reader-header,
    .dark .interactive-shot:hover .material-content-card,
    .dark .interactive-shot:hover .quiz-topbar-mock,
    .dark .interactive-shot:hover .quiz-main-mock,
    .dark .interactive-shot:hover .lab-editor,
    .dark .interactive-shot:hover .analytics-hero-mock,
    .dark .interactive-shot:hover .analytics-chart {
        box-shadow: 0 18px 38px -30px rgba(0, 0, 0, 0.95);
    }

    @media (max-width: 1024px) {
        .media-guide-grid { grid-template-columns: 1fr; }
        .interactive-shot { min-height: 340px; }
    }
    @media (max-width: 640px) {
        .media-accordion-trigger { align-items: flex-start; padding: 0.9rem; }
        .media-accordion-icon { width: 2.2rem; height: 2.2rem; }
        .interactive-shot { aspect-ratio: auto; min-height: 360px; }
        .landing-hero-mock { grid-template-columns: 1fr; inset: 24% 6% 7%; }
        .landing-preview-mock, .auth-showcase-mock, .lesson-action-stack, .material-side-stack, .quiz-side-mock, .lab-preview, .analytics-list { display: none; }
        .auth-shot, .lesson-shot, .material-reader-shot, .lab-shot, .analytics-shot { grid-template-columns: 1fr; }
        .auth-shot { padding: 8%; }
        .lesson-shot, .material-reader-shot, .analytics-shot { grid-template-rows: auto 1fr; }
        .lab-shot { padding: 20% 6% 8%; grid-template-columns: 1fr; gap: 0.9rem; }
        .lab-titlebar-mock { width: 88%; }
        .quiz-main-mock { width: 86%; }
        .analytics-metric-row { grid-template-columns: repeat(3, 1fr); }
        .analytics-chart { display: none; }
        .media-hotspot span { left: 0.35rem; top: 0.35rem; transform: none; }
        .media-hotspot.marker-right span { left: 0.35rem; right: auto; }
    }

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

        function syncMediaHotspots() {
            document.querySelectorAll('.interactive-shot').forEach(shot => {
                const shotRect = shot.getBoundingClientRect();
                if (!shotRect.width || !shotRect.height) return;

                shot.querySelectorAll('.media-hotspot[data-hotspot-target]').forEach(hotspot => {
                    const targets = Array.from(shot.querySelectorAll(hotspot.dataset.hotspotTarget))
                        .filter(target => {
                            const rect = target.getBoundingClientRect();
                            return rect.width > 0 && rect.height > 0;
                        });

                    if (!targets.length) {
                        hotspot.style.display = 'none';
                        return;
                    }

                    const pad = Number.parseFloat(hotspot.dataset.hotspotPad || '4');
                    const bounds = targets.reduce((box, target) => {
                        const rect = target.getBoundingClientRect();
                        return {
                            left: Math.min(box.left, rect.left),
                            top: Math.min(box.top, rect.top),
                            right: Math.max(box.right, rect.right),
                            bottom: Math.max(box.bottom, rect.bottom),
                        };
                    }, { left: Infinity, top: Infinity, right: -Infinity, bottom: -Infinity });

                    const edgeInset = 1;
                    const left = Math.max(edgeInset, bounds.left - shotRect.left - pad);
                    const top = Math.max(edgeInset, bounds.top - shotRect.top - pad);
                    const right = Math.min(shotRect.width - edgeInset, bounds.right - shotRect.left + pad);
                    const bottom = Math.min(shotRect.height - edgeInset, bounds.bottom - shotRect.top + pad);

                    hotspot.style.display = '';
                    hotspot.style.left = `${left}px`;
                    hotspot.style.top = `${top}px`;
                    hotspot.style.width = `${Math.max(0, right - left)}px`;
                    hotspot.style.height = `${Math.max(0, bottom - top)}px`;
                });
            });
        }

        requestAnimationFrame(syncMediaHotspots);
        setTimeout(syncMediaHotspots, 300);
        window.addEventListener('resize', syncMediaHotspots);
        document.querySelectorAll('.media-guide-chip, .media-accordion-trigger').forEach(control => {
            control.addEventListener('click', () => {
                requestAnimationFrame(syncMediaHotspots);
                setTimeout(syncMediaHotspots, 180);
                setTimeout(syncMediaHotspots, 420);
            });
        });

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
