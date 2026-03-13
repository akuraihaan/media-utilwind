@extends('layouts.landing')

@section('title', 'Informasi')

@section('content')
<div id="appRoot" class="relative h-screen bg-slate-50 dark:bg-[#020617] text-slate-800 dark:text-slate-300 font-sans overflow-hidden flex flex-col selection:bg-fuchsia-500/30 selection:text-fuchsia-900 dark:selection:text-white pt-16 md:pt-20 transition-colors duration-500">

    {{-- ==================== BACKGROUND FX (ULTIMATE) ==================== --}}
    <div class="fixed inset-0 -z-50 pointer-events-none overflow-hidden">
        <div id="animated-bg" class="absolute inset-0 opacity-40 transition-colors duration-500"></div>
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGcgc3Ryb2tlPSJyZ2JhKDE1MCwgMTUwLCAxNTAsIDAuMDUpIiBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxwYXRoIGQ9Ik0wIDQwaDQwVjBIMHoiLz48L2c+PC9zdmc+')] dark:bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGcgc3Ryb2tlPSJyZ2JhKDI1NSwgMjU1LCAyNTUsIDAuMDMpIiBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxwYXRoIGQ9Ik0wIDQwaDQwVjBIMHoiLz48L2c+PC9zdmc+')] opacity-50 transition-all duration-500"></div>
        
        <div class="absolute top-[5%] left-[10%] w-[600px] h-[600px] bg-cyan-400/20 dark:bg-cyan-600/10 rounded-full blur-[120px] mix-blend-multiply dark:mix-blend-screen floating transition-colors duration-500"></div>
        <div class="absolute bottom-[5%] right-[10%] w-[500px] h-[500px] bg-indigo-400/20 dark:bg-indigo-600/10 rounded-full blur-[120px] mix-blend-multiply dark:mix-blend-screen floating transition-colors duration-500" style="animation-delay: -3s;"></div>
        
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.02] dark:opacity-[0.03] mix-blend-overlay transition-opacity duration-500"></div>
    </div>

    @include('layouts.partials.navbar')
    
    <div class="flex flex-1 overflow-hidden relative" x-data="{ sidebarOpen: false }">

        {{-- Overlay Mobile --}}
        <div x-show="sidebarOpen" class="fixed inset-0 bg-slate-900/60 dark:bg-[#020617]/80 backdrop-blur-sm z-[90] lg:hidden transition-colors" @click="sidebarOpen = false" x-transition.opacity style="display: none;" x-cloak></div>
        
        {{-- ======================================================================
             SIDEBAR MENU (KONSISTEN DENGAN DASHBOARD)
             ====================================================================== --}}
        <aside class="w-[280px] bg-white/90 dark:bg-[#050912]/90 backdrop-blur-2xl border-r border-slate-200 dark:border-white/5 flex flex-col shrink-0 z-[100] absolute lg:relative inset-y-0 left-0 h-full transition-transform duration-300 transform lg:translate-x-0 shadow-2xl lg:shadow-none" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            
            {{-- Tombol Close (Hanya Muncul di Mobile) --}}
            <button @click="sidebarOpen = false" class="lg:hidden absolute top-4 right-4 p-2 bg-slate-100 dark:bg-white/5 rounded-xl text-slate-500 dark:text-white/50 hover:text-slate-800 dark:hover:text-white hover:bg-slate-200 dark:hover:bg-white/10 transition-colors z-50">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <div class="p-6 pt-8 lg:pt-6 overflow-y-auto custom-scrollbar flex-1 relative z-10">
                <p class="text-[10px] font-bold text-slate-400 dark:text-white/30 uppercase tracking-widest mb-4 pl-2 transition-colors">Menu Utama</p>
                <nav class="space-y-2">
                    {{-- Nav 1: Dashboard --}}
                    <a href="{{ route('dashboard') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-100 dark:hover:bg-white/5 text-slate-600 dark:text-white/60 hover:text-slate-900 dark:hover:text-white transition border border-transparent hover:border-slate-200 dark:hover:border-white/5">
                        <svg class="w-5 h-5 text-slate-400 group-hover:text-slate-700 dark:text-white/40 dark:group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                        Overview
                    </a>
                    
                    {{-- Nav 2: Materi Belajar --}}
                    @php $isUnlocked = Auth::user() && (Auth::user()->role === 'admin' || !empty(Auth::user()->class_group)); @endphp
                    @if($isUnlocked)
                        <a href="{{ route('courses.curriculum') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-100 dark:hover:bg-white/5 text-slate-600 dark:text-white/60 hover:text-slate-900 dark:hover:text-white transition border border-transparent hover:border-slate-200 dark:hover:border-white/5">
                            <svg class="w-5 h-5 text-slate-400 group-hover:text-slate-700 dark:text-white/40 dark:group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                            Materi Belajar
                        </a>
                    @else
                        <div class="w-full group flex items-center justify-between px-4 py-3 rounded-xl bg-slate-50 dark:bg-white/[0.02] text-slate-400 dark:text-white/30 cursor-not-allowed border border-slate-200 dark:border-white/5 transition-colors relative overflow-hidden" title="Anda belum bergabung di kelas manapun">
                            <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-5"></div>
                            <div class="flex items-center gap-3 relative z-10">
                                <svg class="w-5 h-5 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                                <span class="font-medium text-sm">Materi Belajar</span>
                            </div>
                            <svg class="w-4 h-4 opacity-50 relative z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                    @endif

                    {{-- Nav 3: Pengaturan --}}
                    <a href="{{ route('profile.edit') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-100 dark:hover:bg-white/5 text-slate-600 dark:text-white/60 hover:text-slate-900 dark:hover:text-white transition border border-transparent hover:border-slate-200 dark:hover:border-white/5">
                        <svg class="w-5 h-5 text-slate-400 group-hover:text-slate-700 dark:text-white/40 dark:group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        Pengaturan
                    </a>
                    
                    {{-- Nav 4: Informasi (ACTIVE) --}}
                    <a href="{{ route('developer.info') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl bg-slate-100 dark:bg-white/10 border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white font-bold shadow-sm dark:shadow-[0_0_15px_rgba(255,255,255,0.05)] transition-all hover:scale-[1.02]">
                        <svg class="w-5 h-5 text-fuchsia-600 dark:text-fuchsia-400 group-hover:scale-110 transition-transform drop-shadow-none dark:drop-shadow-[0_0_8px_rgba(217,70,239,0.5)]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Informasi
                    </a>
                </nav>
            </div>

            {{-- Kartu Profil User Bawah --}}
            <div class="mt-auto p-5 shrink-0 border-t border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-white/[0.02] transition-colors relative z-10">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-cyan-500 flex items-center justify-center text-white font-bold shadow-md shrink-0 border-2 border-white dark:border-[#0f141e] transition-colors">
                        @if(Auth::user()->avatar)
                            <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="w-full h-full rounded-full object-cover">
                        @else
                            {{ substr(Auth::user()->name, 0, 1) }}
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-slate-900 dark:text-white truncate transition-colors">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] text-slate-500 dark:text-white/40 truncate transition-colors font-mono">{{ Auth::user()->email }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 font-bold text-xs hover:bg-red-100 dark:hover:bg-red-500/20 transition-colors border border-red-100 dark:border-red-500/20 shadow-sm">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Keluar Akun
                    </button>
                </form>
            </div>
        </aside>

        {{-- ==================== MAIN CONTENT ==================== --}}
        <main class="flex-1 h-full overflow-y-auto scroll-smooth relative custom-scrollbar p-6 lg:p-10 perspective-container">
            <div class="max-w-7xl mx-auto pb-20">
                
                {{-- TOMBOL HAMBURGER MOBILE --}}
                <div class="flex items-center gap-4 mb-6 lg:hidden">
                    <button @click="sidebarOpen = true" class="p-2 bg-white dark:bg-white/5 rounded-lg text-slate-700 dark:text-white hover:bg-slate-100 dark:hover:bg-white/10 transition border border-slate-200 dark:border-white/10 shadow-sm dark:shadow-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <div>
                        {{-- BREADCRUMB START --}}
                        <nav class="flex items-center gap-2 mb-1 text-[10px] font-bold uppercase tracking-widest text-slate-500 dark:text-white/40 transition-colors" aria-label="Breadcrumb">
                            <a href="/" class="hover:text-slate-900 dark:hover:text-white transition-colors flex items-center gap-1.5">Home</a>
                            <span class="text-slate-300 dark:text-white/20 transition-colors">/</span>
                            <span class="text-fuchsia-600 dark:text-fuchsia-400 drop-shadow-none dark:drop-shadow-[0_0_8px_rgba(217,70,239,0.5)] transition-colors">Informasi</span>
                        </nav>
                        <h2 class="text-slate-900 dark:text-white font-bold text-xl tracking-tight transition-colors">Developer Hub</h2>
                    </div>
                </div>

                {{-- HEADER CONTENT DESKTOP --}}
                <header class="hidden lg:flex flex-col justify-center mb-10 shrink-0">
                    <div>
                        {{-- BREADCRUMB START --}}
                        <nav class="flex items-center gap-2 mb-3 text-xs font-bold uppercase tracking-widest text-slate-500 dark:text-white/40 transition-colors" aria-label="Breadcrumb">
                            <a href="/" class="hover:text-slate-900 dark:hover:text-white transition-colors flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                Home
                            </a>
                            <span class="text-slate-300 dark:text-white/20 transition-colors">/</span>
                            <span class="text-fuchsia-600 dark:text-fuchsia-400 drop-shadow-none dark:drop-shadow-[0_0_8px_rgba(217,70,239,0.5)] transition-colors">Informasi</span>
                        </nav>
                        {{-- BREADCRUMB END --}}

                        <h2 class="text-slate-900 dark:text-white font-black text-3xl md:text-4xl tracking-tight transition-colors">Project & Developer Hub</h2>
                        <p class="text-base text-slate-600 dark:text-white/60 mt-2 transition-colors">Informasi penelitian dan penyusun media</p>
                    </div>
                </header>

                {{-- BENTO GRID SHOWCASE (PROFIL) --}}
                <div class="w-full grid grid-cols-1 xl:grid-cols-3 gap-6 md:gap-8 reveal">
                    
                    {{-- KIRI: DIGITAL ID CARD (3D HOVER EFFECT) --}}
                    <div class="xl:col-span-1 tilt-element glass-card rounded-3xl p-6 md:p-8 relative overflow-hidden flex flex-col items-center text-center group border border-slate-200 dark:border-white/10 hover:border-cyan-400 dark:hover:border-cyan-500/50 shadow-md hover:shadow-xl dark:shadow-xl dark:hover:shadow-[0_20px_50px_rgba(6,182,212,0.15)] transition-all duration-500">
                        {{-- BG Card --}}
                        <div class="absolute inset-0 bg-gradient-to-b from-cyan-50 dark:from-cyan-500/5 to-transparent pointer-events-none transition-colors"></div>
                        <div class="absolute -top-20 -right-20 w-48 h-48 bg-cyan-300/20 dark:bg-cyan-500/20 rounded-full blur-[60px] group-hover:bg-cyan-400/30 dark:group-hover:bg-cyan-500/30 transition duration-700 pointer-events-none"></div>
                        
                        {{-- Avatar --}}
                        <div class="relative w-32 h-32 md:w-48 md:h-48 mb-8 inner-3d mt-4">
                            <div class="absolute inset-0 rounded-full bg-gradient-to-tr from-cyan-400 to-indigo-500 animate-spin-slow blur-md opacity-40 group-hover:opacity-100 transition duration-500"></div>
                            <div class="absolute inset-1 bg-white dark:bg-[#020617] rounded-full z-10 transition-colors"></div>
                            
                            <img src="{{ asset('images/Raihan.jpg') }}" alt="Taufik Raihandani" onerror="this.src='https://ui-avatars.com/api/?name=Taufik+Raihandani&background=06b6d4&color=fff&size=200'" 
                                 class="absolute inset-2 w-[calc(100%-16px)] h-[calc(100%-16px)] object-cover rounded-full z-20 border-2 border-white dark:border-[#020617] transition-colors">
                            
                            {{-- Verified Badge --}}
                            <div class="absolute bottom-3 right-3 w-10 h-10 bg-white dark:bg-[#020617] rounded-full z-30 flex items-center justify-center transition-colors">
                                <div class="w-8 h-8 bg-cyan-500 rounded-full flex items-center justify-center text-white shadow-md dark:shadow-[0_0_15px_#06b6d4]">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </div>
                            </div>
                        </div>

                        {{-- Data Diri --}}
                        <div class="inner-3d w-full mb-8">
                            <h3 class="text-2xl md:text-3xl font-black text-slate-900 dark:text-white tracking-tight mb-2 transition-colors">Taufik Raihandani</h3>
                            <div class="inline-block px-4 py-1.5 rounded-lg bg-slate-100 dark:bg-[#020617] border border-slate-200 dark:border-white/10 text-cyan-600 dark:text-cyan-400 font-mono text-sm font-bold shadow-inner mb-6 transition-colors">
                                2210131210018
                            </div>
                            
                            <div class="space-y-3 w-full text-left bg-slate-50 dark:bg-white/[0.02] p-5 rounded-2xl border border-slate-200 dark:border-white/5 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center border border-indigo-200 dark:border-indigo-500/20 shrink-0">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M12 14l9-5-9-5-9 5 9 5z"/><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                                    </div>
                                    <div>
                                        <p class="text-[10px] text-slate-500 dark:text-white/40 uppercase font-bold tracking-widest transition-colors">Program Studi</p>
                                        <p class="text-sm font-medium text-slate-800 dark:text-white/90 mt-0.5 transition-colors">Pendidikan Komputer</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-rose-50 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400 flex items-center justify-center border border-rose-200 dark:border-rose-500/20 shrink-0">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                    </div>
                                    <div>
                                        <p class="text-[10px] text-slate-500 dark:text-white/40 uppercase font-bold tracking-widest transition-colors">Domisili</p>
                                        <p class="text-sm font-medium text-slate-800 dark:text-white/90 mt-0.5 transition-colors">HKSN Permai, Banjarmasin</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Contact Link --}}
                        <a href="mailto:2210131210018@mhs.ulm.ac.id" class="mt-auto w-full inner-3d py-3.5 rounded-xl bg-white dark:bg-[#020617] border border-slate-200 dark:border-white/10 text-slate-600 dark:text-white/60 hover:text-cyan-600 dark:hover:text-cyan-400 hover:border-cyan-400 dark:hover:border-cyan-500/50 shadow-sm hover:shadow-md dark:hover:shadow-[0_0_20px_rgba(6,182,212,0.2)] flex items-center justify-center gap-2 transition-all group/mail">
                            <svg class="w-4 h-4 text-cyan-500 group-hover/mail:-translate-y-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <span class="text-xs font-mono font-bold">Kirim Email</span>
                        </a>
                    </div>

                    {{-- KANAN: PROJECT SHOWCASE (BENTO BOXES) --}}
                    <div class="xl:col-span-2 flex flex-col gap-6 md:gap-8 h-full">
                        
                        {{-- Judul Penelitian Box --}}
                        <div class="glass-card rounded-3xl p-8 md:p-10 relative overflow-hidden flex-1 flex flex-col justify-center border-l-4 border-l-indigo-500 shadow-md dark:shadow-xl dark:shadow-indigo-900/10 transition-colors duration-500">
                            <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.02] dark:opacity-[0.02]"></div>
                            
                            <div class="relative z-10">
                                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-indigo-50 dark:bg-indigo-500/10 border border-indigo-200 dark:border-indigo-500/20 text-indigo-700 dark:text-indigo-400 text-[10px] font-bold uppercase tracking-widest mb-6 shadow-sm dark:shadow-inner transition-colors">
                                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-pulse"></span>
                                    Topik Skripsi / Penelitian
                                </div>
                                
                                <h2 class="text-2xl md:text-3xl lg:text-4xl font-black text-slate-900 dark:text-white leading-snug md:leading-tight mb-6 transition-colors">
                                    Penerapan Pemantauan Kinerja Siswa Pada Media Pembelajaran Interaktif Materi <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-600 to-indigo-600 dark:from-cyan-400 dark:to-indigo-400">Tailwind CSS</span>
                                </h2>

                                <p class="text-slate-600 dark:text-white/60 text-sm md:text-base leading-relaxed max-w-2xl border-l-2 border-slate-300 dark:border-white/10 pl-5 py-1 transition-colors">
                                    Proyek media pembelajaran mandiri ini dirancang untuk memfasilitasi siswa dalam memahami framework <span class="text-slate-800 dark:text-white font-bold transition-colors">utility-first</span> secara interaktif, dilengkapi sistem analitik untuk memetakan progres belajar.
                                </p>
                            </div>

                            {{-- Tech Stack Badges --}}
                            <div class="relative z-10 flex flex-wrap items-center gap-3 mt-8">
                                <span class="px-4 py-2 rounded-xl bg-slate-50 dark:bg-[#020617] border border-slate-200 dark:border-white/10 text-xs font-bold text-slate-700 dark:text-slate-300 flex items-center gap-2 shadow-sm dark:shadow-inner transition-colors"><div class="w-3 h-3 bg-red-500 rounded-sm"></div> Laravel</span>
                                <span class="px-4 py-2 rounded-xl bg-slate-50 dark:bg-[#020617] border border-slate-200 dark:border-white/10 text-xs font-bold text-slate-700 dark:text-slate-300 flex items-center gap-2 shadow-sm dark:shadow-inner transition-colors"><div class="w-3 h-3 bg-cyan-500 dark:bg-cyan-400 rounded-full"></div> Tailwind CSS</span>
                                <span class="px-4 py-2 rounded-xl bg-slate-50 dark:bg-[#020617] border border-slate-200 dark:border-white/10 text-xs font-bold text-slate-700 dark:text-slate-300 flex items-center gap-2 shadow-sm dark:shadow-inner transition-colors"><div class="w-3 h-3 bg-blue-500 dark:bg-blue-400 rounded-sm rotate-45"></div> Alpine.js</span>
                            </div>
                        </div>

                        {{-- Dosen & CTA Row --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8">
                            
                            {{-- Card Dosen --}}
                            <div class="glass-card rounded-3xl p-6 md:p-8 flex flex-col justify-center relative overflow-hidden transition-colors duration-500 shadow-sm border border-slate-200 dark:border-white/10 dark:shadow-none">
                                <div class="absolute right-0 bottom-0 opacity-[0.05] dark:opacity-[0.03] transition-opacity">
                                    <svg class="w-48 h-48 -mb-10 -mr-10 text-slate-900 dark:text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5z"/><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                                </div>
                                
                                <h4 class="text-[10px] text-slate-500 dark:text-white/40 uppercase font-black tracking-widest mb-6 transition-colors">Dosen Pembimbing</h4>
                                
                                <div class="space-y-4 relative z-10">
                                    <div class="flex items-center gap-4">
                                        <div class="w-8 h-8 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xs font-black shrink-0 shadow-sm dark:shadow-inner transition-colors">1</div>
                                        <p class="text-xs font-bold text-slate-800 dark:text-white/90 leading-tight transition-colors">Novan Alkaf Bahrain Saputra, S.Kom., M.T.</p>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <div class="w-8 h-8 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xs font-black shrink-0 shadow-sm dark:shadow-inner transition-colors">2</div>
                                        <p class="text-xs font-bold text-slate-800 dark:text-white/90 leading-tight transition-colors">Muhammad Hifdzi Adini, S.Kom., M.T.</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Card Action --}}
                            <div class="glass-card rounded-3xl p-6 md:p-8 flex flex-col items-center justify-center text-center relative overflow-hidden group transition-colors duration-500 shadow-sm border border-slate-200 dark:border-white/10 dark:shadow-none">
                                <div class="absolute inset-0 bg-gradient-to-br from-cyan-50 to-indigo-50 dark:from-cyan-600/10 dark:to-indigo-600/10 opacity-0 group-hover:opacity-100 transition duration-500"></div>
                                
                                <div class="w-14 h-14 rounded-2xl bg-white dark:bg-[#020617] border border-slate-200 dark:border-white/10 flex items-center justify-center mb-4 text-cyan-600 dark:text-cyan-400 shadow-md dark:shadow-lg relative z-10 group-hover:scale-110 group-hover:-translate-y-1 transition duration-300">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                                </div>
                                
                                <h4 class="text-lg font-bold text-slate-900 dark:text-white mb-2 relative z-10 transition-colors">Eksplorasi Media</h4>
                                <p class="text-xs text-slate-600 dark:text-white/50 mb-6 relative z-10 leading-relaxed max-w-xs transition-colors">Lihat hasil implementasi langsung dari media pembelajaran ini.</p>
                                
                                <a href="https://utilwind.research-media.web.id/" target="_blank" class="w-full relative z-10 inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-cyan-500 hover:bg-cyan-600 dark:bg-cyan-500 dark:hover:bg-cyan-400 text-white dark:text-[#020617] font-black text-xs uppercase tracking-wider transition-all shadow-md dark:shadow-[0_0_20px_rgba(6,182,212,0.3)] hover:scale-105 active:scale-95">
                                    Kunjungi Situs
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
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
    .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(150,150,150,0.3); border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(150,150,150,0.5); }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.2); }

    /* CSS Animasi & Glassmorphism */
    .glass-card { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(16px); }
    .dark .glass-card { background: rgba(10, 14, 23, 0.7); }
    
    .animate-spin-slow { animation: spin 8s linear infinite; }
    @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }

    .reveal { opacity: 0; transform: translateY(30px); animation: revealAnim 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes revealAnim { to { opacity: 1; transform: translateY(0); } }
    
    @keyframes float { 0% { transform: translateY(0px); } 50% { transform: translateY(-10px); } 100% { transform: translateY(0px); } }
    .floating { animation: float 6s ease-in-out infinite; }

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
    .inner-3d { transform: translateZ(40px); } /* Efek popping up dari dalam card */
</style>

{{-- Alpine JS Wajib --}}
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Efek 3D Card Hover (Hanya jalan di Desktop agar mobile tidak berat)
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
                    
                    el.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.02, 1.02, 1.02)`;
                });
                
                el.addEventListener('mouseleave', () => {
                    el.style.transform = `perspective(1000px) rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1)`;
                });
            });
        }
    });
</script>
@endsection