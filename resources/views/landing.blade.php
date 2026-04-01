@extends('layouts.landing')

@section('title', 'Utilwind')

@section('content')

{{-- STYLE TAMBAHAN (DI PINDAH KE ATAS AGAR LOAD LEBIH CEPAT) --}}
<style>
    /* PREMIUM LIQUID GLASS EFFECT (Menyatu dengan palet asli) */
    .liquid-glass {
        position: relative;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.15);
        box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.05), 0 4px 15px rgba(0,0,0,0.1);
    }

    .liquid-glass::before {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: inherit;
        padding: 1.4px;
        background: linear-gradient(180deg, rgba(255,255,255,0.40) 0%, rgba(255,255,255,0.1) 20%, rgba(255,255,255,0) 40%, rgba(255,255,255,0) 60%, rgba(255,255,255,0.1) 80%, rgba(255,255,255,0.40) 100%);
        -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        pointer-events: none;
    }

    .liquid-glass:hover {
        transform: scale(1.03);
        box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.1), 0 8px 30px rgba(0,0,0,0.15);
    }

    /* ANIMASI ASLI UTILWIND */
    .animate-gradient-x { background-size: 200% 200%; animation: gradient-x 5s ease infinite; }
    @keyframes gradient-x { 0%, 100% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } }
    
    .animate-fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }

    .reveal { opacity: 0; transform: translateY(40px); transition: all 1s cubic-bezier(0.16, 1, 0.3, 1); }
    .reveal.show { opacity: 1; transform: translateY(0); }

    .preserve-3d { transform-style: preserve-3d; }
    .perspective-1000 { perspective: 1000px; }

    @keyframes float { 0% { transform: translateY(0px); } 50% { transform: translateY(-10px); } 100% { transform: translateY(0px); } }
    .floating-element { animation: float 4s ease-in-out infinite; }
</style>

{{-- LANDING ROOT MENGGUNAKAN WARNA BACKGROUND ASLI --}}
<div id="landingRoot" class="relative min-h-screen bg-slate-50 dark:bg-[#020617] text-slate-600 dark:text-slate-300 font-sans overflow-x-hidden selection:bg-fuchsia-500/30 selection:text-fuchsia-900 dark:selection:text-white transition-colors duration-500">

    {{-- ======================================================================
         1. ATMOSPHERE LAYER (Background Effect Glow Asli)
         ====================================================================== --}}
    <div class="fixed inset-0 -z-50 pointer-events-none">
        <div id="glow-1" class="absolute top-[-10%] -left-[10%] w-[500px] md:w-[800px] h-[500px] md:h-[800px] bg-fuchsia-300/30 dark:bg-fuchsia-600/10 rounded-full blur-[100px] transition-transform duration-[3s]"></div>
        <div id="glow-2" class="absolute bottom-[-10%] -right-[10%] w-[400px] md:w-[700px] h-[400px] md:h-[700px] bg-cyan-300/30 dark:bg-cyan-600/10 rounded-full blur-[100px] transition-transform duration-[4s]"></div>
        <div id="glow-3" class="absolute top-[30%] left-[40%] w-[300px] md:w-[500px] h-[300px] md:h-[500px] bg-indigo-300/30 dark:bg-indigo-600/10 rounded-full blur-[120px] transition-transform duration-[5s]"></div>
        
        <canvas id="starsCanvas" class="absolute inset-0 z-0 opacity-0 dark:opacity-60 transition-opacity duration-500"></canvas>
        
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGcgc3Ryb2tlPSJyZ2JhKDE1MCwgMTUwLCAxNTAsIDAuMDUpIiBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxwYXRoIGQ9Ik0wIDQwaDQwVjBIMHoiLz48L2c+PC9zdmc+')] dark:bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGcgc3Ryb2tlPSJyZ2JhKDI1NSwgMjU1LCAyNTUsIDAuMDMpIiBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxwYXRoIGQ9Ik0wIDQwaDQwVjBIMHoiLz48L2c+PC9zdmc+')] opacity-50 z-0 transition-all duration-500"></div>
    </div>

    @include('layouts.partials.navbar')

    <div id="scrollProgress" class="fixed top-[60px] md:top-[80px] left-0 h-[3px] bg-gradient-to-r from-cyan-400 via-fuchsia-500 to-indigo-500 z-[60] shadow-[0_0_15px_#d946ef] transition-all duration-100" style="width:0%"></div>

    {{-- ======================================================================
         2. HERO SECTION (VIDEO BACKGROUND DISEMPURNAKAN UNTUK LIGHT MODE)
         ====================================================================== --}}
    <section id="hero" class="relative pt-32 pb-20 md:pt-40 md:pb-32 px-5 lg:px-8 min-h-[95vh] flex items-center z-20 overflow-hidden">
        
        {{-- Video Background --}}
        <div class="absolute inset-0 z-0 h-full w-full pointer-events-none">
            {{-- Opacity di light mode dinaikkan menjadi 30% agar lebih terlihat --}}
            <video autoPlay loop muted playsInline class="w-full h-full object-cover opacity-30 dark:opacity-15" src="https://d8j0ntlcm91z4.cloudfront.net/user_38xzZboKViGWJOttwIXH07lWA1P/hf_20260314_131748_f2ca2a28-fed7-44c8-b9a9-bd9acdd5ec31.mp4"></video>
            {{-- Gradient overlay disesuaikan agar area atas transparan dan bawah menyatu dengan body --}}
            <div class="absolute inset-0 bg-gradient-to-b from-transparent via-slate-50/50 to-slate-50 dark:via-[#020617]/70 dark:to-[#020617]"></div>
        </div>

        <div class="max-w-7xl mx-auto w-full grid lg:grid-cols-2 gap-12 lg:gap-8 items-center relative z-10">
            
            {{-- Bagian Copywriting --}}
            <div class="text-center lg:text-left relative z-10">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 text-[10px] md:text-xs font-bold font-mono tracking-widest text-fuchsia-600 dark:text-fuchsia-400 mb-6 animate-fade-in-up backdrop-blur-md shadow-sm dark:shadow-lg floating-element transition-colors">
                    <span class="w-2 h-2 rounded-full bg-fuchsia-500 animate-pulse"></span>
                    Mari Mulai Belajar
                </div>

                {{-- H1 ASLI --}}
                <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-[4.5rem] font-black text-slate-900 dark:text-white leading-[1.1] mb-6 tracking-tight animate-fade-in-up transition-colors" style="animation-delay: 0.1s;">
                    Belajar Dasar Framework <br class="hidden sm:block">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-600 via-fuchsia-600 to-indigo-600 dark:from-cyan-400 dark:via-fuchsia-400 dark:to-indigo-400 animate-gradient-x pb-2">Tailwind CSS</span>
                </h1>

                <p class="text-base sm:text-lg text-slate-600 dark:text-slate-400 leading-relaxed animate-fade-in-up max-w-2xl mx-auto lg:mx-0 mb-10 font-medium transition-colors" style="animation-delay: 0.2s;">
                    Pelajari dasar framework Tailwind CSS lewat materi yang terstruktur dan coba langsung kodenya di fitur <strong class="text-cyan-600 dark:text-cyan-400 font-bold">Live Sandbox</strong>.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 animate-fade-in-up" style="animation-delay: 0.3s;">
                    @auth
                        <a href="{{ route('courses.curriculum') }}" class="liquid-glass w-full sm:w-auto px-8 py-3.5 rounded-full bg-gradient-to-r from-fuchsia-600 to-indigo-600 hover:from-fuchsia-500 hover:to-indigo-500 text-white font-bold text-sm md:text-base transition-all flex items-center justify-center gap-2 cursor-pointer shadow-lg dark:shadow-[0_0_30px_rgba(217,70,239,0.3)]">
                            Lanjutkan Belajar <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="liquid-glass w-full sm:w-auto px-8 py-3.5 rounded-full bg-slate-900 text-white hover:bg-slate-800 transition-all font-black text-sm md:text-base flex items-center justify-center shadow-lg dark:shadow-[0_0_30px_rgba(255,255,255,0.2)] cursor-pointer">
                            Mulai Belajar 
                        </a>
                    @endauth
                    
                    <a href="#fitur" class="w-full sm:w-auto px-8 py-3.5 rounded-full bg-white/80 dark:bg-[#0f141e]/80 border border-slate-200 dark:border-white/10 text-slate-700 dark:text-white font-bold text-sm md:text-base hover:bg-slate-50 dark:hover:bg-white/10 transition-all flex items-center justify-center gap-2 backdrop-blur-sm group shadow-sm">
                        <svg class="w-4 h-4 text-cyan-500 dark:text-cyan-400 group-hover:translate-y-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                        Lihat Fitur
                    </a>
                </div>
            </div>

            {{-- INTERACTIVE Code Editor Mockup (Asli) --}}
            <div class="relative w-full max-w-lg mx-auto lg:max-w-none animate-fade-in-up" style="animation-delay: 0.4s;">
                <div class="absolute -inset-1 bg-gradient-to-tr from-cyan-400 via-fuchsia-400 to-indigo-400 dark:from-cyan-500 dark:via-fuchsia-500 dark:to-indigo-500 rounded-2xl blur-xl opacity-30 dark:opacity-20 animate-pulse"></div>
                
                <div id="tiltEditor" class="relative rounded-2xl bg-white dark:bg-[#0a0e17] border border-slate-200 dark:border-white/10 shadow-2xl overflow-hidden transform transition-all duration-100 ease-out preserve-3d" style="transform: perspective(1000px) rotateY(-5deg) rotateX(5deg);">
                    
                    <div class="flex items-center px-4 py-3 bg-slate-100 dark:bg-[#020617] border-b border-slate-200 dark:border-white/5 transition-colors">
                        <div class="flex gap-2">
                            <div class="w-3 h-3 rounded-full bg-red-400 dark:bg-red-500/80"></div>
                            <div class="w-3 h-3 rounded-full bg-yellow-400 dark:bg-yellow-500/80"></div>
                            <div class="w-3 h-3 rounded-full bg-emerald-400 dark:bg-emerald-500/80"></div>
                        </div>
                        <div class="mx-auto text-[10px] font-mono text-slate-500 font-bold">index.html</div>
                    </div>
                    
                    <div class="p-5 font-mono text-xs sm:text-sm overflow-x-auto text-slate-700 dark:text-slate-300 leading-loose min-h-[200px] transition-colors">
                        <div class="flex"><span class="w-6 text-slate-400 dark:text-slate-600 select-none">1</span> <span><span class="text-fuchsia-600 dark:text-fuchsia-400">&lt;div</span> <span class="text-cyan-600 dark:text-cyan-300">class=</span><span class="text-indigo-600 dark:text-indigo-300">"max-w-md mx-auto bg-white rounded-xl shadow-md"</span><span class="text-fuchsia-600 dark:text-fuchsia-400">&gt;</span></span></div>
                        <div class="flex"><span class="w-6 text-slate-400 dark:text-slate-600 select-none">2</span> <span>&nbsp;&nbsp;<span class="text-fuchsia-600 dark:text-fuchsia-400">&lt;div</span> <span class="text-cyan-600 dark:text-cyan-300">class=</span><span class="text-indigo-600 dark:text-indigo-300">"p-8"</span><span class="text-fuchsia-600 dark:text-fuchsia-400">&gt;</span></span></div>
                        <div class="flex"><span class="w-6 text-slate-400 dark:text-slate-600 select-none">3</span> <span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="text-fuchsia-600 dark:text-fuchsia-400">&lt;div</span> <span class="text-cyan-600 dark:text-cyan-300">class=</span><span class="text-indigo-600 dark:text-indigo-300">"<span id="autoType" class="text-yellow-700 dark:text-yellow-300 bg-yellow-100 dark:bg-yellow-500/10 px-1 rounded"></span><span class="animate-pulse bg-slate-800 dark:bg-white/50 w-2 h-4 inline-block ml-[1px] align-middle"></span>"</span><span class="text-fuchsia-600 dark:text-fuchsia-400">&gt;</span></span></div>
                        <div class="flex"><span class="w-6 text-slate-400 dark:text-slate-600 select-none">4</span> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Utilwind</span></div>
                        <div class="flex"><span class="w-6 text-slate-400 dark:text-slate-600 select-none">5</span> <span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="text-fuchsia-600 dark:text-fuchsia-400">&lt;/div&gt;</span></span></div>
                        <div class="flex"><span class="w-6 text-slate-400 dark:text-slate-600 select-none">6</span> <span>&nbsp;&nbsp;<span class="text-fuchsia-600 dark:text-fuchsia-400">&lt;/div&gt;</span></span></div>
                        <div class="flex"><span class="w-6 text-slate-400 dark:text-slate-600 select-none">7</span> <span><span class="text-fuchsia-600 dark:text-fuchsia-400">&lt;/div&gt;</span></span></div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    {{-- ======================================================================
         3. BENTO GRID FEATURES (Asli)
         ====================================================================== --}}
    <section id="fitur" class="py-24 relative border-t border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-[#020617] transition-colors duration-500">
        <div class="max-w-7xl mx-auto px-5 lg:px-8">
            
            <div class="text-center mb-16 md:mb-24 reveal">
                <div class="inline-block px-4 py-1.5 rounded-full border border-cyan-300 dark:border-cyan-500/30 text-cyan-700 dark:text-cyan-400 text-[10px] font-bold tracking-widest uppercase mb-6 bg-cyan-50 dark:bg-cyan-500/10 shadow-sm dark:shadow-[0_0_15px_rgba(6,182,212,0.2)]">
                    Fasilitas Belajar
                </div>
                <h2 class="text-3xl md:text-5xl font-black text-slate-900 dark:text-white transition-colors">Apa saja yang <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-600 to-indigo-600 dark:from-cyan-400 dark:to-indigo-400">akan didapat?</span></h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
                
                {{-- Feature 1: Kurikulum --}}
                <div class="md:col-span-2 group p-8 rounded-[2rem] bg-white dark:bg-[#0f141e] border border-slate-200 dark:border-white/5 hover:border-indigo-400 dark:hover:border-indigo-500/30 transition-all duration-500 reveal flex flex-col justify-between overflow-hidden relative interactive-card shadow-sm hover:shadow-md dark:shadow-none">
                    <div class="absolute right-0 top-0 w-64 h-64 bg-indigo-100 dark:bg-indigo-500/10 rounded-full blur-[80px] group-hover:bg-indigo-200 dark:group-hover:bg-indigo-500/20 transition duration-700"></div>
                    <div class="relative z-10 flex-1">
                        <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-500/10 border border-indigo-100 dark:border-indigo-500/20 flex items-center justify-center mb-6 text-indigo-600 dark:text-indigo-400 transition-colors">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-3 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">Materi Terstruktur</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed max-w-md mb-8 transition-colors">Tidak perlu bingung mulai dari mana. Materi disusun bertahap, mulai dari dasar Box Model hingga teknik layout yang responsif.</p>
                        <a href="{{ route('courses.htmldancss') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-indigo-50 dark:bg-indigo-600/20 text-indigo-700 dark:text-indigo-400 font-bold text-xs hover:bg-indigo-600 hover:text-white dark:hover:bg-indigo-600 transition-colors border border-indigo-200 dark:border-indigo-500/30">
                            Lihat Silabus &rarr;
                        </a>
                    </div>
                </div>

                {{-- Feature 2: Cheat Sheet --}}
                <div class="group p-8 rounded-[2rem] bg-white dark:bg-[#0f141e] border border-slate-200 dark:border-white/5 hover:border-fuchsia-400 dark:hover:border-fuchsia-500/30 transition-all duration-500 reveal flex flex-col h-full relative overflow-hidden interactive-card shadow-sm hover:shadow-md dark:shadow-none" style="transition-delay: 100ms">
                    <div class="absolute bottom-0 left-0 w-32 h-32 bg-fuchsia-100 dark:bg-fuchsia-500/10 rounded-full blur-[60px] group-hover:bg-fuchsia-200 dark:group-hover:bg-fuchsia-500/20 transition duration-700"></div>
                    <div class="relative z-10 flex-1">
                        <div class="w-12 h-12 rounded-xl bg-fuchsia-50 dark:bg-fuchsia-500/10 border border-fuchsia-100 dark:border-fuchsia-500/20 flex items-center justify-center mb-6 text-fuchsia-600 dark:text-fuchsia-400 transition-colors">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3 group-hover:text-fuchsia-600 dark:group-hover:text-fuchsia-400 transition-colors">Kamus Class</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed mb-6 transition-colors">Lupa nama utility class? Tenang, tersedia kamus mini untuk mencari dan menyalin class Tailwind dengan mudah.</p>
                        <a href="{{ route('cheatsheet.index') }}" class="text-xs font-bold text-fuchsia-600 dark:text-fuchsia-400 uppercase tracking-widest flex items-center gap-2 group-hover:translate-x-2 transition-transform mt-auto">Buka Kamus &rarr;</a>
                    </div>
                </div>

                {{-- Feature 3: Live Sandbox --}}
                <div class="group p-8 rounded-[2rem] bg-white dark:bg-[#0f141e] border border-slate-200 dark:border-white/5 hover:border-cyan-400 dark:hover:border-cyan-500/30 transition-all duration-500 reveal flex flex-col h-full relative overflow-hidden interactive-card shadow-sm hover:shadow-md dark:shadow-none" style="transition-delay: 200ms">
                    <div class="absolute top-0 left-0 w-32 h-32 bg-cyan-100 dark:bg-cyan-500/10 rounded-full blur-[60px] group-hover:bg-cyan-200 dark:group-hover:bg-cyan-500/20 transition duration-700"></div>
                    <div class="relative z-10 flex-1">
                        <div class="w-12 h-12 rounded-xl bg-cyan-50 dark:bg-cyan-500/10 border border-cyan-100 dark:border-cyan-500/20 flex items-center justify-center mb-6 text-cyan-600 dark:text-cyan-400 transition-colors">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3 group-hover:text-cyan-600 dark:group-hover:text-cyan-400 transition-colors">Live Sandbox</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed mb-6 transition-colors">Coba tulis kode dan lihat hasilnya secara langsung di browser tanpa perlu setup aplikasi tambahan.</p>
                        <a href="{{ route('sandbox') }}" class="text-xs font-bold text-cyan-600 dark:text-cyan-400 uppercase tracking-widest flex items-center gap-2 group-hover:translate-x-2 transition-transform mt-auto">Mulai Koding &rarr;</a>
                    </div>
                </div>

                {{-- Feature 4: Analytics --}}
                <div class="md:col-span-2 group p-8 rounded-[2rem] bg-white dark:bg-[#0f141e] border border-slate-200 dark:border-white/5 hover:border-emerald-400 dark:hover:border-emerald-500/30 transition-all duration-500 reveal flex flex-col md:flex-row items-center gap-8 overflow-hidden relative interactive-card shadow-sm hover:shadow-md dark:shadow-none" style="transition-delay: 300ms">
                    <div class="absolute bottom-0 right-0 w-64 h-64 bg-emerald-100 dark:bg-emerald-500/10 rounded-full blur-[80px] group-hover:bg-emerald-200 dark:group-hover:bg-emerald-500/20 transition duration-700"></div>
                    <div class="flex-1 relative z-10">
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-100 dark:border-emerald-500/20 flex items-center justify-center mb-6 text-emerald-600 dark:text-emerald-400 transition-colors">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-3 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">Pantau Progres Belajarmu</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed max-w-sm mb-6 transition-colors">Mulai dari durasi belajar, materi yang sudah diselesaikan, hingga nilai kuis, semua tercatat di Dasbor agar kamu tahu sejauh mana perkembanganmu.</p>
                        <a href="{{ route('dashboard') }}" class="text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-widest flex items-center gap-2 group-hover:translate-x-2 transition-transform">Lihat Dasbor &rarr;</a>
                    </div>
                    <div class="relative z-10 w-full md:w-64 h-32 bg-slate-50 dark:bg-[#020617] rounded-xl border border-slate-200 dark:border-white/5 flex items-end gap-3 p-4 pt-10 shadow-inner dark:shadow-lg transition-colors">
                        <div class="w-full bg-slate-300 dark:bg-[#1e293b] rounded-t-md bar-anim transition-colors" data-h="40%"></div>
                        <div class="w-full bg-slate-400 dark:bg-[#334155] rounded-t-md bar-anim transition-colors" data-h="65%"></div>
                        <div class="w-full bg-emerald-500 rounded-t-md bar-anim shadow-[0_0_15px_rgba(16,185,129,0.4)] relative flex justify-center" data-h="95%">
                            <span class="absolute -top-6 text-[10px] font-black text-emerald-600 dark:text-emerald-400 opacity-0 group-hover:opacity-100 transition-opacity delay-300">95%</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ======================================================================
         4. DEVELOPMENT WORKFLOW (Asli)
         ====================================================================== --}}
    <section class="py-20 md:py-32 relative bg-white dark:bg-[#060a14] border-t border-slate-200 dark:border-white/5 overflow-hidden transition-colors duration-500">
        <div class="max-w-7xl mx-auto px-5 lg:px-8">
            <h2 class="text-3xl md:text-4xl font-black text-center text-slate-900 dark:text-white mb-16 reveal transition-colors">Alur <span class="font-mono text-fuchsia-600 dark:text-fuchsia-400">Belajar</span></h2>
            
            <div class="relative max-w-4xl mx-auto">
                <div class="absolute left-[27px] md:left-1/2 top-0 bottom-0 w-[2px] bg-gradient-to-b from-cyan-400/30 via-fuchsia-400/30 dark:from-cyan-500/20 dark:via-fuchsia-500/20 to-transparent hidden sm:block md:-translate-x-1/2"></div>
                <div class="space-y-12">
                    @foreach([
                        ['icon' => '🔑', 'title' => 'Pilih Materi', 'desc' => 'Tentukan kelas atau materi spesifik yang ingin kamu pelajari hari ini.'],
                        ['icon' => '📖', 'title' => 'Baca Konsepnya', 'desc' => 'Pahami teori dasarnya dengan bahasa yang sederhana dan mudah dicerna.'],
                        ['icon' => '💻', 'title' => 'Langsung Praktik', 'desc' => 'Jangan cuma dibaca, cobain langsung kodenya di editor interaktif yang disediakan.'],
                        ['icon' => '📊', 'title' => 'Lihat Hasilnya', 'desc' => 'Uji pemahamanmu lewat kuis ringan dan lihat poinmu bertambah.']
                    ] as $i => $step)
                    <div class="relative flex flex-col sm:flex-row items-start sm:items-center justify-between md:justify-normal md:odd:flex-row-reverse group reveal">
                        <div class="absolute left-0 md:left-1/2 w-14 h-14 bg-slate-50 dark:bg-[#0a0e17] border-[3px] border-slate-200 dark:border-white/10 rounded-full flex items-center justify-center text-xl shadow-md z-10 group-hover:border-cyan-500 group-hover:shadow-[0_0_20px_rgba(6,182,212,0.3)] transition duration-500 md:-translate-x-1/2 hidden sm:flex floating-element" style="animation-delay: {{ $i * 0.2 }}s">
                            {{ $step['icon'] }}
                        </div>
                        <div class="w-full sm:pl-20 md:pl-0 md:w-[45%]">
                            <div class="p-6 bg-slate-50 dark:bg-[#020617] border border-slate-200 dark:border-white/5 rounded-2xl group-hover:bg-white dark:group-hover:bg-white/[0.03] group-hover:border-slate-300 dark:group-hover:border-white/10 transition-colors duration-300 shadow-sm">
                                <span class="text-[10px] font-mono text-cyan-600 dark:text-cyan-500 mb-2 block font-bold">Langkah 0{{ $i+1 }}</span>
                                <h3 class="text-base md:text-lg font-bold text-slate-900 dark:text-white mb-2 group-hover:text-cyan-600 dark:group-hover:text-cyan-400 transition-colors">{{ $step['title'] }}</h3>
                                <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed transition-colors">{{ $step['desc'] }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ======================================================================
         5. CALL TO ACTION (Asli dengan Tombol Liquid Glass)
         ====================================================================== --}}
    <section class="py-24 md:py-32 relative bg-slate-50 dark:bg-[#020617] overflow-hidden border-t border-slate-200 dark:border-white/5 transition-colors duration-500">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-gradient-to-r from-cyan-300/30 to-indigo-300/30 dark:from-cyan-600/20 dark:to-indigo-600/20 rounded-full blur-[150px] pointer-events-none"></div>

        <div class="max-w-3xl mx-auto px-5 text-center relative z-10 reveal">
            <h2 class="text-4xl md:text-5xl lg:text-6xl font-black text-slate-900 dark:text-white mb-6 leading-tight transition-colors">Mulai Belajar <br> Tailwind CSS Sekarang</h2>
            <p class="text-slate-600 dark:text-slate-400 text-base md:text-lg mb-10 max-w-xl mx-auto transition-colors">Buat akun gratismu dan nikmati pengalaman belajar yang terstruktur, mudah dipahami, dan langsung bisa dipraktikkan.</p>
            
            {{-- Tombol Daftar Gratis dengan palet warna asli --}}
            <a href="{{ route('register') }}" class="liquid-glass inline-flex items-center justify-center gap-2 px-10 py-4 rounded-xl bg-slate-900 text-white dark:bg-white/10 dark:text-white hover:scale-105 transition-all shadow-xl dark:shadow-[0_0_40px_rgba(255,255,255,0.2)] font-black text-lg cursor-pointer">
                Daftar Gratis
            </a>
        </div>
    </section>

    {{-- ======================================================================
         6. FOOTER (Asli)
         ====================================================================== --}}
    <footer class="bg-white dark:bg-[#020617] border-t border-slate-200 dark:border-white/10 pt-16 pb-8 transition-colors duration-500">
        <div class="max-w-7xl mx-auto px-5 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6 mb-12">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo.png') }}" alt="Utilwind Logo" class="h-6 w-auto grayscale opacity-70 hover:grayscale-0 hover:opacity-100 transition duration-500" onerror="this.style.display='none'">
                    <span class="text-xl font-black text-slate-900 dark:text-white tracking-tight transition-colors">Utilwind</span>
                </div>
                <div class="flex flex-wrap justify-center gap-6 text-sm font-medium text-slate-600 dark:text-slate-400 transition-colors">
                    <a href="{{ route('courses.htmldancss') }}" class="hover:text-cyan-600 dark:hover:text-cyan-400 transition-colors">Course</a>
                    <a href="{{ route('sandbox') }}" class="hover:text-cyan-600 dark:hover:text-cyan-400 transition-colors">Sandbox</a>
                    <a href="{{ route('cheatsheet.index') }}" class="hover:text-fuchsia-600 dark:hover:text-fuchsia-400 transition-colors">Cheat Sheet</a>
                    <a href="{{ route('gallery.index') }}" class="hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Gallery</a>
                </div>
                <div class="flex gap-4 text-sm font-bold">
                    @guest
                        <a href="{{ route('login') }}" class="text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors px-4 py-2">Masuk</a>
                        <a href="{{ route('register') }}" class="text-white dark:text-[#020617] bg-slate-900 dark:bg-white hover:bg-slate-800 dark:hover:bg-slate-200 transition-colors px-4 py-2 rounded-lg shadow-sm">Daftar</a>
                    @else
                        <a href="{{ route('dashboard') }}" class="text-indigo-700 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-500/10 hover:bg-indigo-100 dark:hover:bg-indigo-500/20 transition-colors px-4 py-2 rounded-lg border border-indigo-200 dark:border-indigo-500/20">Ke Dasbor &rarr;</a>
                    @endguest
                </div>
            </div>
            <div class="border-t border-slate-200 dark:border-white/5 pt-6 text-center md:text-left text-[11px] text-slate-500 dark:text-slate-600 font-mono flex flex-col md:flex-row justify-between items-center gap-3 transition-colors">
                <p>&copy; {{ date('Y') }} Utilwind </p>
            </div>
        </div>
    </footer>

</div>

{{-- SCRIPT JQUERY ASLI UTILWIND --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
// ==========================================
// 1. THEME SWITCHER LOGIC (Vanilla JS)
// ==========================================
const htmlEl = document.documentElement;
const themeToggleBtn = document.getElementById('themeToggle');

function initTheme() {
    if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        htmlEl.classList.add('dark');
    } else {
        htmlEl.classList.remove('dark');
    }
}
initTheme();

if(themeToggleBtn) {
    themeToggleBtn.addEventListener('click', function() {
        htmlEl.classList.toggle('dark');
        if (htmlEl.classList.contains('dark')) {
            localStorage.setItem('color-theme', 'dark');
        } else {
            localStorage.setItem('color-theme', 'light');
        }
    });
}

$(document).ready(function() {
    // 2. SCROLL PROGRESS BAR
    $(window).scroll(function() {
        let winScroll = $(this).scrollTop();
        let height = $(document).height() - $(window).height();
        let scrolled = (winScroll / height) * 100;
        $("#scrollProgress").css("width", scrolled + "%");
    });

    // 3. PARALLAX GLOW BACKGROUNDS
    if(window.innerWidth > 768) {
        $(document).mousemove(function(e) { 
            let x = e.clientX / window.innerWidth;
            let y = e.clientY / window.innerHeight;
            $('#glow-1').css('transform', `translate(${x * 40}px, ${y * 40}px)`);
            $('#glow-2').css('transform', `translate(-${x * 30}px, -${y * 30}px)`);
            $('#glow-3').css('transform', `translate(${x * 50}px, -${y * 50}px)`);
        });
    }

    // 4. 3D TILT EFFECT MOUSE INTERACTION
    if(window.innerWidth > 1024) {
        $('.perspective-1000').on('mousemove', function(e) {
            let el = $('#tiltEditor');
            let offset = $(this).offset();
            let w = $(this).width();
            let h = $(this).height();
            let mouseX = e.pageX - offset.left - w / 2;
            let mouseY = e.pageY - offset.top - h / 2;
            let rotateX = (mouseY / h) * 15;
            let rotateY = (mouseX / w) * -15;
            el.css('transform', `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.02, 1.02, 1.02)`);
        });

        $('.perspective-1000').on('mouseleave', function() {
            $('#tiltEditor').css({
                'transform': 'perspective(1000px) rotateY(-5deg) rotateX(5deg) scale3d(1, 1, 1)',
                'transition': 'transform 0.5s ease-out'
            });
            setTimeout(() => { $('#tiltEditor').css('transition', 'transform 0.1s ease-out'); }, 500);
        });
    }

    // 5. AUTO TYPING EFFECT 
    const classesToType = ["uppercase tracking-wide", "text-sm text-indigo-500", "font-semibold rounded-md"];
    let typeIndex = 0;
    let charIndex = 0;
    let isDeleting = false;
    let typeElement = $('#autoType');

    function typeEffect() {
        let currentString = classesToType[typeIndex];
        if (isDeleting) {
            typeElement.text(currentString.substring(0, charIndex - 1));
            charIndex--;
        } else {
            typeElement.text(currentString.substring(0, charIndex + 1));
            charIndex++;
        }
        let typeSpeed = isDeleting ? 30 : 80;
        if (!isDeleting && charIndex === currentString.length) {
            typeSpeed = 2000; 
            isDeleting = true;
        } else if (isDeleting && charIndex === 0) {
            isDeleting = false;
            typeIndex++;
            if (typeIndex >= classesToType.length) typeIndex = 0;
            typeSpeed = 500; 
        }
        setTimeout(typeEffect, typeSpeed);
    }
    setTimeout(typeEffect, 1500); 

    // 6. INTERSECTION OBSERVER (REVEAL ANIMATION)
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                $(entry.target).addClass('show');
                if($(entry.target).hasClass('interactive-card')) {
                    $(entry.target).find('.bar-anim').each(function() {
                        let h = $(this).data('h');
                        $(this).css('height', h);
                    });
                }
                observer.unobserve(entry.target); 
            }
        });
    }, { threshold: 0.2 });
    $('.reveal').each(function() { observer.observe(this); });

    // 7. CANVAS PARTICLES / BINTANG
    if(window.innerWidth > 768) {
        const canvas = document.getElementById('starsCanvas');
        if(canvas) {
            const ctx = canvas.getContext('2d');
            let particles = [];
            function resize() { canvas.width = window.innerWidth; canvas.height = window.innerHeight; }
            $(window).on('resize', resize);
            resize();
            for(let i=0; i<70; i++) {
                particles.push({
                    x: Math.random() * canvas.width,
                    y: Math.random() * canvas.height,
                    radius: Math.random() * 1.5,
                    vx: Math.random() * 0.5 - 0.25,
                    vy: Math.random() * 0.5 - 0.25
                });
            }
            function drawParticles() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                ctx.fillStyle = htmlEl.classList.contains('dark') ? 'rgba(255, 255, 255, 0.4)' : 'rgba(15, 23, 42, 0.1)';
                ctx.beginPath();
                particles.forEach(p => {
                    ctx.moveTo(p.x, p.y);
                    ctx.arc(p.x, p.y, p.radius, 0, Math.PI * 2);
                    p.x += p.vx; p.y += p.vy;
                    if(p.x < 0 || p.x > canvas.width) p.vx = -p.vx;
                    if(p.y < 0 || p.y > canvas.height) p.vy = -p.vy;
                });
                ctx.fill();
                requestAnimationFrame(drawParticles);
            }
            drawParticles();
        }
    }
});
</script>
@endsection