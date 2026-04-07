@extends('layouts.landing')

@section('title', 'Utilwind')

@section('content')

{{-- GOOGLE FONTS: PLUS JAKARTA SANS & FIRA CODE --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@400;500;600&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">

{{-- STYLE TAMBAHAN --}}
<style>
    /* OVERRIDE FONT DEFAULT */
    body, .font-sans { font-family: 'Plus Jakarta Sans', sans-serif; }
    .font-mono { font-family: 'Fira Code', monospace; font-variant-ligatures: normal; }

    /* PREMIUM LIQUID GLASS EFFECT */
    .liquid-glass {
        position: relative; overflow: hidden; transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.1), 0 8px 32px rgba(0,0,0,0.08);
    }
    .liquid-glass::before {
        content: ''; position: absolute; inset: 0; border-radius: inherit; padding: 1.5px;
        background: linear-gradient(180deg, rgba(255,255,255,0.3) 0%, rgba(255,255,255,0.05) 20%, rgba(255,255,255,0) 40%, rgba(255,255,255,0.05) 80%, rgba(255,255,255,0.2) 100%);
        -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0); -webkit-mask-composite: xor; mask-composite: exclude; pointer-events: none;
    }
    .liquid-glass:hover {
        transform: translateY(-3px) scale(1.01);
        box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.2), 0 15px 40px rgba(0,0,0,0.15);
    }

    /* PREMIUM ICON WRAPPER (LUXURY) */
    .icon-luxury {
        background: linear-gradient(135deg, rgba(255,255,255,0.9), rgba(255,255,255,0.4));
        box-shadow: inset 0px 2px 4px rgba(255,255,255,1), 0 8px 20px rgba(0,0,0,0.04);
        border: 1px solid rgba(255,255,255,0.8);
    }
    .dark .icon-luxury {
        background: linear-gradient(135deg, rgba(30,41,59,0.9), rgba(15,23,42,0.7));
        box-shadow: inset 0px 2px 4px rgba(255,255,255,0.1), 0 8px 20px rgba(0,0,0,0.4);
        border: 1px solid rgba(255,255,255,0.05);
    }

    /* ANIMASI IDE CODE STREAM BACKGROUND */
    .marquee-container { display: flex; width: max-content; gap: 4rem; }
    .animate-ide-scroll-1 { animation: ide-scroll 75s linear infinite; }
    .animate-ide-scroll-2 { animation: ide-scroll-reverse 90s linear infinite; }
    .animate-ide-scroll-3 { animation: ide-scroll 110s linear infinite; }
    @keyframes ide-scroll { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }
    @keyframes ide-scroll-reverse { 0% { transform: translateX(-50%); } 100% { transform: translateX(0); } }

    /* ANIMASI UTILWIND */
    .animate-gradient-x { background-size: 200% 200%; animation: gradient-x 6s ease infinite; }
    @keyframes gradient-x { 0%, 100% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } }
    .animate-fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    .reveal { opacity: 0; transform: translateY(40px); transition: all 1s cubic-bezier(0.16, 1, 0.3, 1); }
    .reveal.show { opacity: 1; transform: translateY(0); }
    .preserve-3d { transform-style: preserve-3d; }
    .perspective-1000 { perspective: 1000px; }

    /* ANIMASI BACKGROUND MEWAH (LAYERED GLASS BLOBCAPES) */
    .lux-blob-layer {
        position: absolute; inset: 0;
        mask-image: linear-gradient(to bottom, black 20%, transparent 95%);
    }
    .lux-blob {
        position: absolute; border-radius: 50%; shadow: 2xl; blur: xl;
        animation: float-blob infinite ease-in-out;
    }
    @keyframes float-blob {
        0% { transform: translateY(0) rotate(0); }
        50% { transform: translateY(-15px) rotate(5deg); }
        100% { transform: translateY(0) rotate(0); }
    }

    /* LAYER 1 (BACK) */
    .lux-blob-layer-1 { z-index: -30; opacity: 0.15; blur: 2xl; }
    /* Fuchsia blobs */
    .lux-blob-1-1 { width: 50vw; height: 50vw; background: conic-gradient(from 180deg at 50% 50%, #d946ef, #a21caf); left: -10vw; top: 10vh; animation-duration: 25s; animation-delay: 2s; }
    .lux-blob-1-2 { width: 35vw; height: 35vw; background: conic-gradient(from 180deg at 50% 50%, #a21caf, #d946ef); right: -5vw; bottom: -5vh; animation-duration: 28s; animation-delay: 0s; }
    
    /* LAYER 2 (MIDDLE) */
    .lux-blob-layer-2 { z-index: -20; opacity: 0.25; blur: xl; }
    /* Cyan blobs */
    .lux-blob-2-1 { width: 40vw; height: 40vw; background: conic-gradient(from 180deg at 50% 50%, #06b6d4, #0891b2); right: 20vw; top: 30vh; animation-duration: 22s; animation-delay: 4s; }
    .lux-blob-2-2 { width: 30vw; height: 30vw; background: conic-gradient(from 180deg at 50% 50%, #0891b2, #06b6d4); left: 15vw; bottom: 20vh; animation-duration: 26s; animation-delay: 1s; }
    
    /* LAYER 3 (FRONT) */
    .lux-blob-layer-3 { z-index: -10; opacity: 0.35; blur: lg; }
    /* Indigo blobs */
    .lux-blob-3-1 { width: 30vw; height: 30vw; background: conic-gradient(from 180deg at 50% 50%, #4338ca, #3730a3); left: 35vw; top: 50vh; animation-duration: 18s; animation-delay: 6s; }
    .lux-blob-3-2 { width: 25vw; height: 25vw; background: conic-gradient(from 180deg at 50% 50%, #3730a3, #4338ca); right: 10vw; top: 10vh; animation-duration: 21s; animation-delay: 3s; }
    .lux-blob-3-3 { width: 20vw; height: 20vw; background: conic-gradient(from 180deg at 50% 50%, #4338ca, #3730a3); left: 5vw; top: 70vh; animation-duration: 19s; animation-delay: 5s; }

    @keyframes float { 0% { transform: translateY(0px); } 50% { transform: translateY(-8px); } 100% { transform: translateY(0px); } }
    .floating-element { animation: float 6s ease-in-out infinite; }
</style>

<div id="landingRoot" class="relative min-h-screen bg-slate-50 dark:bg-[#020617] text-slate-600 dark:text-slate-300 overflow-x-hidden selection:bg-fuchsia-500/30 selection:text-fuchsia-900 dark:selection:text-white transition-colors duration-500">

    {{-- ATMOSPHERE LAYER (BACKGROUND ANIMASI MEWAH) --}}
    <div class="fixed inset-0 -z-50 pointer-events-none lux-atmosphere-layer">
        
        {{-- Layered Glass Blobcapes --}}
        <div class="lux-blob-layer lux-blob-layer-1">
            <div class="lux-blob lux-blob-1-1"></div>
            <div class="lux-blob lux-blob-1-2"></div>
        </div>
        <div class="lux-blob-layer lux-blob-layer-2">
            <div class="lux-blob lux-blob-2-1"></div>
            <div class="lux-blob lux-blob-2-2"></div>
        </div>
        <div class="lux-blob-layer lux-blob-layer-3">
            <div class="lux-blob lux-blob-3-1"></div>
            <div class="lux-blob lux-blob-3-2"></div>
            <div class="lux-blob lux-blob-3-3"></div>
        </div>
        
        <canvas id="starsCanvas" class="absolute inset-0 z-0 opacity-0 dark:opacity-40 transition-opacity duration-500"></canvas>
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGcgc3Ryb2tlPSJyZ2JhKDE1MCwgMTUwLCAxNTAsIDAuMDUpIiBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxwYXRoIGQ9Ik0wIDQwaDQwVjBIMHoiLz48L2c+PC9zdmc+')] dark:bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGcgc3Ryb2tlPSJyZ2JhKDI1NSwgMjU1LCAyNTUsIDAuMDMpIiBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxwYXRoIGQ9Ik0wIDQwaDQwVjBIMHoiLz48L2c+PC9zdmc+')] opacity-40 z-0"></div>
    </div>

    @include('layouts.partials.navbar')

    <div id="scrollProgress" class="fixed top-[60px] md:top-[80px] left-0 h-[3px] bg-gradient-to-r from-cyan-400 via-fuchsia-500 to-indigo-500 z-[60] shadow-[0_0_15px_#d946ef] transition-all duration-100" style="width:0%"></div>

    {{-- HERO SECTION --}}
    <section id="hero" class="relative pt-32 pb-20 md:pt-40 md:pb-24 px-5 lg:px-8 min-h-[90vh] flex items-center z-20 overflow-hidden">
        
        {{-- Animated IDE Code Background --}}
        <div class="absolute inset-0 z-0 h-full w-full pointer-events-none overflow-hidden flex flex-col justify-center gap-10 opacity-40 dark:opacity-30 select-none font-mono text-xl md:text-3xl whitespace-nowrap drop-shadow-sm">
            <div class="marquee-container animate-ide-scroll-1">
                @for($i=0; $i<3; $i++)
                <div>
                    <span class="text-pink-500 dark:text-pink-400">&lt;nav</span> <span class="text-sky-500 dark:text-sky-400">class=</span><span class="text-emerald-500 dark:text-emerald-300">"fixed w-full z-50 backdrop-blur-md bg-white/70"</span><span class="text-pink-500 dark:text-pink-400">&gt;</span> &nbsp; <span class="text-pink-500 dark:text-pink-400">&lt;ul</span> <span class="text-sky-500 dark:text-sky-400">class=</span><span class="text-emerald-500 dark:text-emerald-300">"flex items-center justify-between mx-auto px-6"</span><span class="text-pink-500 dark:text-pink-400">&gt;</span> &nbsp; <span class="text-slate-400 dark:text-slate-500 italic">&lt;!-- Nav --&gt;</span>
                </div>
                @endfor
            </div>
            <div class="marquee-container animate-ide-scroll-2 ml-[-30%]">
                @for($i=0; $i<3; $i++)
                <div>
                    <span class="text-pink-500 dark:text-pink-400">&lt;button</span> <span class="text-sky-500 dark:text-sky-400">class=</span><span class="text-emerald-500 dark:text-emerald-300">"group relative px-8 py-4 bg-gradient-to-r rounded-full shadow-lg"</span><span class="text-pink-500 dark:text-pink-400">&gt;</span> <span class="text-slate-700 dark:text-white">Learn</span> <span class="text-pink-500 dark:text-pink-400">&lt;/button&gt;</span>
                </div>
                @endfor
            </div>
            <div class="marquee-container animate-ide-scroll-3">
                @for($i=0; $i<3; $i++)
                <div>
                    <span class="text-slate-400 dark:text-slate-500 italic">&lt;!-- Container --&gt;</span> &nbsp; <span class="text-pink-500 dark:text-pink-400">&lt;section</span> <span class="text-sky-500 dark:text-sky-400">class=</span><span class="text-emerald-500 dark:text-emerald-300">"grid grid-cols-1 md:grid-cols-2 gap-6"</span><span class="text-pink-500 dark:text-pink-400">&gt;</span> &nbsp; <span class="text-pink-500 dark:text-pink-400">&lt;div</span> <span class="text-sky-500 dark:text-sky-400">class=</span><span class="text-emerald-500 dark:text-emerald-300">"rounded-3xl bg-white p-8"</span><span class="text-pink-500 dark:text-pink-400">&gt;</span>
                </div>
                @endfor
            </div>
            <div class="absolute inset-0 bg-gradient-to-b from-slate-50/60 via-slate-50/90 to-slate-50 dark:from-[#020617]/70 dark:via-[#020617]/90 dark:to-[#020617]"></div>
        </div>

        <div class="max-w-7xl mx-auto w-full grid lg:grid-cols-2 gap-12 lg:gap-8 items-center relative z-10">
            {{-- Copywriting --}}
            <div class="text-center lg:text-left relative z-10">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white dark:bg-[#0f141e] border border-slate-200 dark:border-white/10 text-xs font-bold font-mono tracking-widest text-fuchsia-600 dark:text-fuchsia-400 mb-6 animate-fade-in-up backdrop-blur-md shadow-sm dark:shadow-lg floating-element">
                    <span class="relative flex h-3 w-3">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-fuchsia-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-3 w-3 bg-fuchsia-500"></span>
                    </span>
                    Release 1.0 is Live
                </div>

                <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-[4.5rem] font-black text-slate-900 dark:text-white leading-[1.15] mb-6 tracking-tight animate-fade-in-up" style="animation-delay: 0.1s;">
                    Belajar <span class="font-mono font-light italic text-slate-500 dark:text-slate-400">Utility-First</span><br class="hidden sm:block">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-500 via-fuchsia-500 to-indigo-500 animate-gradient-x pb-2">Tailwind CSS</span>
                </h1>

                <p class="text-base sm:text-lg text-slate-600 dark:text-slate-400 leading-relaxed animate-fade-in-up max-w-2xl mx-auto lg:mx-0 mb-10 font-medium" style="animation-delay: 0.2s;">
                    Tinggalkan cara lama menulis CSS panjang. Pelajari cara merancang antarmuka modern dengan cepat, terstruktur, dan cobain langsung di fitur <strong class="text-cyan-600 dark:text-cyan-400 font-bold">Live Sandbox</strong>.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 animate-fade-in-up" style="animation-delay: 0.3s;">
                    @auth
                        <a href="{{ route('courses.curriculum') }}" class="liquid-glass w-full sm:w-auto px-8 py-4 rounded-full bg-gradient-to-r from-fuchsia-600 to-indigo-600 text-white font-bold text-base flex items-center justify-center gap-2 shadow-lg shadow-fuchsia-500/25">
                            Lanjutkan Belajar <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="liquid-glass w-full sm:w-auto px-8 py-4 rounded-full bg-slate-900 text-white dark:bg-white dark:text-slate-900 font-bold text-base flex items-center justify-center shadow-xl">
                            Mulai Belajar Sekarang
                        </a>
                    @endauth
                    
                    <a href="#fitur" class="w-full sm:w-auto px-8 py-4 rounded-full bg-white/80 dark:bg-[#0f141e]/80 border border-slate-200 dark:border-white/10 text-slate-700 dark:text-white font-semibold text-base hover:bg-slate-50 dark:hover:bg-white/10 transition-colors flex items-center justify-center gap-2 backdrop-blur-sm group">
                        <svg class="w-5 h-5 text-cyan-500 dark:text-cyan-400 group-hover:translate-y-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5L12 21m0 0l-7.5-7.5M12 21V3"/></svg>
                        Lihat Fitur
                    </a>
                </div>
            </div>

            {{-- 3D Editor Mockup --}}
            <div class="relative w-full max-w-lg mx-auto lg:max-w-none animate-fade-in-up perspective-1000" style="animation-delay: 0.4s;">
                <div class="absolute -inset-1 bg-gradient-to-tr from-cyan-400 via-fuchsia-400 to-indigo-400 dark:from-cyan-500 dark:via-fuchsia-500 dark:to-indigo-500 rounded-2xl blur-2xl opacity-40 dark:opacity-30 animate-pulse"></div>
                
                <div id="tiltEditor" class="relative rounded-2xl bg-[#fafafa] dark:bg-[#0a0e17] border border-slate-200 dark:border-white/10 shadow-2xl overflow-hidden preserve-3d transition-transform duration-300 ease-out">
                    <div class="flex items-center px-4 py-3 bg-slate-100 dark:bg-[#060a14] border-b border-slate-200 dark:border-white/5">
                        <div class="flex gap-2">
                            <div class="w-3 h-3 rounded-full bg-red-400"></div>
                            <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                            <div class="w-3 h-3 rounded-full bg-emerald-400"></div>
                        </div>
                        <div class="mx-auto flex items-center gap-2 text-[10px] font-mono text-slate-500 font-bold bg-white dark:bg-white/5 px-3 py-1.5 rounded-md border border-slate-200 dark:border-white/10 shadow-sm">
                            <svg class="w-3 h-3 text-cyan-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg> Live Preview
                        </div>
                    </div>
                    
                    <div class="p-6 font-mono text-[13px] overflow-x-auto text-slate-700 dark:text-slate-300 leading-loose min-h-[220px]">
                        <div class="flex"><span class="w-8 text-slate-400 select-none">1</span> <span><span class="text-pink-600 dark:text-pink-400">&lt;div</span> <span class="text-sky-600 dark:text-sky-400">class=</span><span class="text-emerald-600 dark:text-emerald-300">"group hover:scale-105 transition-all"</span><span class="text-pink-600 dark:text-pink-400">&gt;</span></span></div>
                        <div class="flex"><span class="w-8 text-slate-400 select-none">2</span> <span>&nbsp;&nbsp;<span class="text-pink-600 dark:text-pink-400">&lt;button</span> <span class="text-sky-600 dark:text-sky-400">class=</span><span class="text-emerald-600 dark:text-emerald-300">"<span id="autoType" class="text-amber-600 dark:text-amber-300 bg-amber-50 dark:bg-amber-500/10 px-1 py-0.5 rounded"></span><span class="animate-pulse bg-slate-800 dark:bg-white/60 w-1.5 h-4 inline-block align-middle ml-1"></span>"</span><span class="text-pink-600 dark:text-pink-400">&gt;</span></span></div>
                        <div class="flex"><span class="w-8 text-slate-400 select-none">3</span> <span>&nbsp;&nbsp;&nbsp;&nbsp;Submit</span></div>
                        <div class="flex"><span class="w-8 text-slate-400 select-none">4</span> <span>&nbsp;&nbsp;<span class="text-pink-600 dark:text-pink-400">&lt;/button&gt;</span></span></div>
                        <div class="flex"><span class="w-8 text-slate-400 select-none">5</span> <span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="text-slate-400 italic">&lt;!-- &rarr; --&gt;</span></span></div>
                        <div class="flex"><span class="w-8 text-slate-400 select-none">6</span> <span><span class="text-pink-600 dark:text-pink-400">&lt;/div&gt;</span></span></div>
                    </div>
                </div>
                
                {{-- Decorative floating UI elements --}}
                <div class="absolute -bottom-6 -left-6 bg-white/80 dark:bg-[#1e293b]/80 backdrop-blur-md p-4 rounded-2xl shadow-2xl border border-white/20 dark:border-white/10 floating-element z-20 hidden md:block">
                    <div class="flex gap-3 items-center">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-cyan-400 to-indigo-500 shadow-lg shadow-cyan-500/30"></div>
                        <div>
                            <div class="h-2.5 w-16 bg-slate-200 dark:bg-slate-600 rounded-full mb-2"></div>
                            <div class="h-2 w-10 bg-slate-100 dark:bg-slate-700 rounded-full"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- STATS BANNER SECTION --}}
    <section class="py-8 border-y border-slate-200 dark:border-white/5 bg-white/40 dark:bg-[#060a14]/40 backdrop-blur-xl relative z-20">
        <div class="max-w-7xl mx-auto px-5 lg:px-8">
            <div class="flex flex-wrap justify-center md:justify-between items-center gap-6 md:gap-12 text-center md:text-left">
                @foreach([
                    ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />', 'num' => '10+ Modul', 'text' => 'Materi Terstruktur', 'color' => 'text-fuchsia-600 dark:text-fuchsia-400'],
                    ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M6.75 7.5l3 2.25-3 2.25m4.5 0h3m-9 8.25h13.5A2.25 2.25 0 0021 18V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v12a2.25 2.25 0 002.25 2.25z" />', 'num' => 'Live Editor', 'text' => 'Koding Tanpa Setup', 'color' => 'text-cyan-600 dark:text-cyan-400'],
                    ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />', 'num' => 'Mini Quiz', 'text' => 'Evaluasi Pemahaman', 'color' => 'text-indigo-600 dark:text-indigo-400'],
                    ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456z" />', 'num' => '100% Gratis', 'text' => 'Tanpa Biaya Tersembunyi', 'color' => 'text-emerald-600 dark:text-emerald-400']
                ] as $stat)
                <div class="flex items-center gap-4 reveal">
                    <div class="w-12 h-12 rounded-xl icon-luxury flex items-center justify-center {{ $stat['color'] }} transition-transform hover:scale-110">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.25" viewBox="0 0 24 24">{!! $stat['icon'] !!}</svg>
                    </div>
                    <div><h4 class="text-lg font-bold text-slate-900 dark:text-white">{{ $stat['num'] }}</h4><p class="text-xs font-medium text-slate-500">{{ $stat['text'] }}</p></div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- INTERACTIVE SHOWCASE (DENGAN LOGO UTILWIND MEWAH) --}}
    <section class="py-24 relative overflow-hidden bg-slate-50 dark:bg-[#020617]">
        <div class="max-w-7xl mx-auto px-5 lg:px-8 wrapper-sihir-sandbox">
            <div class="text-center mb-16 reveal">
                <h2 class="text-3xl md:text-5xl font-black text-slate-900 dark:text-white mb-4 leading-tight">Sihir <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-500 to-indigo-500">Live Sandbox</span></h2>
                <p class="text-slate-600 dark:text-slate-400 max-w-2xl mx-auto font-medium">Ketik kodenya, dan lihat UI berubah secara <i class="font-semibold">real-time</i>. Tidak perlu refresh, tidak perlu instalasi.</p>
            </div>

            <div class="grid lg:grid-cols-2 gap-8 items-center bg-white/70 dark:bg-[#0a0e17]/70 rounded-[2rem] p-6 md:p-8 border border-slate-200 dark:border-white/5 shadow-2xl reveal backdrop-blur-md relative z-10">
                
                {{-- Editor Side (Menyesuaikan dengan tag img logo) --}}
                <div class="rounded-2xl overflow-hidden bg-[#0d1117] shadow-inner border border-white/5">
                    <div class="px-4 py-3 bg-[#161b22] flex gap-2 border-b border-white/5">
                        <div class="w-3 h-3 rounded-full bg-rose-500"></div><div class="w-3 h-3 rounded-full bg-amber-500"></div><div class="w-3 h-3 rounded-full bg-emerald-500"></div>
                    </div>
                    <div class="p-6 font-mono text-[13px] text-slate-300 leading-[1.8] overflow-x-auto">
                        <div class="flex"><span class="text-pink-400">&lt;div</span> <span class="text-sky-400">class=</span><span class="text-emerald-300">"bg-white rounded-2xl shadow-xl p-6 flex items-center gap-6"</span><span class="text-pink-400">&gt;</span></div>
                        <div class="flex pl-4"><span class="text-pink-400">&lt;div</span> <span class="text-sky-400">class=</span><span class="text-emerald-300">"w-16 h-16 rounded-full flex items-center justify-center border"</span><span class="text-pink-400">&gt;</span></div>
                        <div class="flex pl-8"><span class="text-pink-400">&lt;img</span> <span class="text-sky-400">src=</span><span class="text-emerald-300">"utilwind.png"</span> <span class="text-sky-400">class=</span><span class="text-emerald-300">"w-10 h-10 hover:scale-110"</span><span class="text-pink-400">&gt;</span></div>
                        <div class="flex pl-4"><span class="text-pink-400">&lt;/div&gt;</span></div>
                        <div class="flex pl-4"><span class="text-pink-400">&lt;div&gt;</span></div>
                        <div class="flex pl-8"><span class="text-pink-400">&lt;h3</span> <span class="text-sky-400">class=</span><span class="text-emerald-300">"text-xl font-bold text-slate-800"</span><span class="text-pink-400">&gt;</span>Utilwind<span class="text-pink-400">&lt;/h3&gt;</span></div>
                        <div class="flex pl-8"><span class="text-pink-400">&lt;p</span> <span class="text-sky-400">class=</span><span class="text-emerald-300">"text-slate-500"</span><span class="text-pink-400">&gt;</span>Membuat UI itu mudah.<span class="text-pink-400">&lt;/p&gt;</span></div>
                        <div class="flex pl-4"><span class="text-pink-400">&lt;/div&gt;</span></div>
                        <div class="flex"><span class="text-pink-400">&lt;/div&gt;</span></div>
                    </div>
                </div>

                {{-- Result Side (Menampilkan Gambar Logo Asli) --}}
                <div class="h-full min-h-[250px] rounded-2xl bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGcgc3Ryb2tlPSJyZ2JhKDIwMCwgMjAwLCAyMDAsIDAuNSkiIGZpbGw9Im5vbmUiPjxwb2x5Z29uIHBvaW50cz0iMjAgMCAyMCAyMCAwIDIwIi8+PC9nPjwvc3ZnPg==')] dark:bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGcgc3Ryb2tlPSJyZ2JhKDQwLCA0MCwgNDAsIDAuOCkiIGZpbGw9Im5vbmUiPjxwb2x5Z29uIHBvaW50cz0iMjAgMCAyMCAyMCAwIDIwIi8+PC9nPjwvc3ZnPg==')] border border-slate-200 dark:border-white/5 flex items-center justify-center p-8 relative overflow-hidden">
                    <div class="absolute inset-0 bg-slate-100/50 dark:bg-slate-900/50 backdrop-blur-[2px]"></div>
                    
                    <div class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-2xl p-6 flex items-center gap-6 hover:-translate-y-2 transition-transform duration-300 w-full max-w-sm border border-slate-100 dark:border-white/5">
                        
                        {{-- Menggunakan Image Logo Utilwind --}}
                        <div class="w-16 h-16 rounded-full bg-white dark:bg-slate-900 shrink-0 shadow-inner border border-slate-200/70 dark:border-white/10 flex items-center justify-center overflow-hidden">
                            <img src="{{ asset('images/logo.png') }}" alt="Utilwind Logo" class="w-10 h-10 object-contain hover:scale-110 transition-transform duration-500" onerror="this.style.display='none'">
                        </div>
                        
                        <div>
                            <h3 class="text-xl font-bold text-slate-800 dark:text-white">Utilwind</h3>
                            <p class="text-slate-500 dark:text-slate-400 text-sm">Membuat UI itu mudah.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Background Animation khusus sihir sandbox (Floating Glass Blobs) --}}
            <div class="absolute inset-0 -z-0 lux-blobscape lux-atmosphere-layer opacity-60 wrapper-sandbox-bg">
                <div class="lux-blob-layer lux-blob-layer-1">
                    <div class="lux-blob lux-blob-1-1" style="width: 30vw; height: 30vw; left: 10vw; top: 20vh;"></div>
                    <div class="lux-blob lux-blob-1-2" style="width: 25vw; height: 25vw; right: 5vw; top: 10vh;"></div>
                </div>
                <div class="lux-blob-layer lux-blob-layer-2">
                    <div class="lux-blob lux-blob-2-1" style="width: 20vw; height: 20vw; right: 30vw; top: 50vh;"></div>
                </div>
                <div class="lux-blob-layer lux-blob-layer-3">
                    <div class="lux-blob lux-blob-3-1" style="width: 15vw; height: 15vw; left: 50vw; top: 60vh;"></div>
                </div>
                <div class="absolute inset-0 bg-slate-50/70 dark:bg-[#020617]/70 backdrop-blur-sm"></div>
            </div>
        </div>
    </section>

    {{-- BENTO GRID FEATURES --}}
    <section id="fitur" class="py-24 relative border-t border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-[#020617]">
        <div class="max-w-7xl mx-auto px-5 lg:px-8">
            <div class="text-center mb-16 md:mb-24 reveal">
                <div class="inline-block px-4 py-1.5 rounded-full border border-cyan-300 dark:border-cyan-500/30 text-cyan-700 dark:text-cyan-400 text-[10px] font-bold tracking-widest uppercase mb-6 bg-cyan-50 dark:bg-cyan-500/10 shadow-sm">Fasilitas Belajar</div>
                <h2 class="text-3xl md:text-5xl font-black text-slate-900 dark:text-white mb-6 leading-tight">Apa saja yang <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-600 to-indigo-600">akan didapat?</span></h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Feature 1 --}}
                <div class="md:col-span-2 group p-8 rounded-[2rem] bg-white dark:bg-[#0f141e] border border-slate-200 dark:border-white/5 hover:border-indigo-400/50 transition-all duration-500 reveal flex flex-col justify-between overflow-hidden relative shadow-lg hover:shadow-xl dark:shadow-none">
                    <div class="absolute right-0 top-0 w-64 h-64 bg-indigo-100 dark:bg-indigo-500/10 rounded-full blur-[80px] group-hover:bg-indigo-200 transition duration-700"></div>
                    <div class="relative z-10 flex-1">
                        <div class="w-14 h-14 rounded-2xl icon-luxury flex items-center justify-center mb-6 text-indigo-600 dark:text-indigo-400">
                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.25"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" /></svg>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-3">Materi Terstruktur</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed max-w-md mb-8 font-medium">Tidak perlu bingung mulai dari mana. Materi disusun bertahap, mulai dari dasar Box Model hingga teknik layout yang responsif.</p>
                        <a href="{{ route('courses.htmldancss') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-indigo-50 dark:bg-indigo-600/20 text-indigo-700 dark:text-indigo-300 font-bold text-sm hover:bg-indigo-600 hover:text-white transition-colors border border-indigo-200 dark:border-indigo-500/30 shadow-sm">Lihat Silabus &rarr;</a>
                    </div>
                </div>

                {{-- Feature 2 --}}
                <div class="group p-8 rounded-[2rem] bg-white dark:bg-[#0f141e] border border-slate-200 dark:border-white/5 hover:border-fuchsia-400/50 transition-all duration-500 reveal flex flex-col h-full relative overflow-hidden shadow-lg hover:shadow-xl dark:shadow-none" style="transition-delay: 100ms">
                    <div class="absolute bottom-0 left-0 w-32 h-32 bg-fuchsia-100 dark:bg-fuchsia-500/10 rounded-full blur-[60px] group-hover:bg-fuchsia-200 transition duration-700"></div>
                    <div class="relative z-10 flex-1">
                        <div class="w-14 h-14 rounded-2xl icon-luxury flex items-center justify-center mb-6 text-fuchsia-600 dark:text-fuchsia-400">
                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.25"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3">Kamus Class</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed mb-6 font-medium">Kamus mini terintegrasi untuk mencari class Tailwind dengan mudah dan efisien.</p>
                        <a href="{{ route('cheatsheet.index') }}" class="text-xs font-bold text-fuchsia-600 dark:text-fuchsia-400 uppercase tracking-widest flex items-center gap-2 group-hover:translate-x-2 transition-transform mt-auto">Buka Kamus &rarr;</a>
                    </div>
                </div>

                {{-- Feature 3 --}}
                <div class="group p-8 rounded-[2rem] bg-white dark:bg-[#0f141e] border border-slate-200 dark:border-white/5 hover:border-cyan-400/50 transition-all duration-500 reveal flex flex-col h-full relative overflow-hidden shadow-lg hover:shadow-xl dark:shadow-none" style="transition-delay: 200ms">
                    <div class="absolute top-0 left-0 w-32 h-32 bg-cyan-100 dark:bg-cyan-500/10 rounded-full blur-[60px] group-hover:bg-cyan-200 transition duration-700"></div>
                    <div class="relative z-10 flex-1">
                        <div class="w-14 h-14 rounded-2xl icon-luxury flex items-center justify-center mb-6 text-cyan-600 dark:text-cyan-400">
                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.25"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5" /></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3">Editor Native</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed mb-6 font-medium">Tidak perlu install ekstensi. Rasakan pengalaman koding modern langsung di browser.</p>
                        <a href="{{ route('sandbox') }}" class="text-xs font-bold text-cyan-600 dark:text-cyan-400 uppercase tracking-widest flex items-center gap-2 group-hover:translate-x-2 transition-transform mt-auto">Mulai Koding &rarr;</a>
                    </div>
                </div>

                {{-- Feature 4: Analytics --}}
                <div class="md:col-span-2 group p-8 rounded-[2rem] bg-white dark:bg-[#0f141e] border border-slate-200 dark:border-white/5 hover:border-emerald-400/50 transition-all duration-500 reveal flex flex-col md:flex-row items-center gap-8 overflow-hidden relative shadow-lg hover:shadow-xl dark:shadow-none" style="transition-delay: 300ms">
                    <div class="absolute bottom-0 right-0 w-64 h-64 bg-emerald-100 dark:bg-emerald-500/10 rounded-full blur-[80px] group-hover:bg-emerald-200 transition duration-700"></div>
                    <div class="flex-1 relative z-10">
                        <div class="w-14 h-14 rounded-2xl icon-luxury flex items-center justify-center mb-6 text-emerald-600 dark:text-emerald-400">
                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.25"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" /></svg>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-3">Pantau Progres Belajarmu</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed max-w-sm mb-6 font-medium">Mulai dari durasi belajar, materi yang diselesaikan, hingga nilai kuis tercatat rapi secara visual di Dasbor.</p>
                        <a href="{{ route('dashboard') }}" class="text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-widest flex items-center gap-2 group-hover:translate-x-2 transition-transform">Lihat Dasbor &rarr;</a>
                    </div>
                    <div class="relative z-10 w-full md:w-64 h-36 bg-slate-50 dark:bg-[#020617] rounded-2xl border border-slate-200 dark:border-white/5 flex items-end gap-3 p-5 pt-10 shadow-inner">
                        <div class="w-full bg-slate-300 dark:bg-slate-700 rounded-t-lg bar-anim transition-all duration-1000" data-h="40%"></div>
                        <div class="w-full bg-slate-400 dark:bg-slate-600 rounded-t-lg bar-anim transition-all duration-1000 delay-100" data-h="65%"></div>
                        <div class="w-full bg-emerald-500 rounded-t-lg bar-anim shadow-[0_0_20px_rgba(16,185,129,0.5)] relative flex justify-center transition-all duration-1000 delay-200" data-h="95%">
                            <span class="absolute -top-7 text-[11px] font-black text-emerald-600 dark:text-emerald-400 opacity-0 group-hover:opacity-100 transition-opacity delay-500">95%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ALUR BELAJAR --}}
    <section class="py-20 md:py-32 relative bg-white dark:bg-[#060a14] border-t border-slate-200 dark:border-white/5 overflow-hidden">
        <div class="max-w-7xl mx-auto px-5 lg:px-8">
            <h2 class="text-3xl md:text-5xl font-black text-center text-slate-900 dark:text-white mb-16 reveal">Alur <span class="font-mono text-fuchsia-600 dark:text-fuchsia-400">Belajar</span></h2>
            <div class="relative max-w-4xl mx-auto">
                <div class="absolute left-[27px] md:left-1/2 top-0 bottom-0 w-[2px] bg-gradient-to-b from-cyan-400/30 via-fuchsia-400/30 to-transparent hidden sm:block md:-translate-x-1/2"></div>
                <div class="space-y-12">
                    @foreach([
                        ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15.042 21.672L13.684 16.6m0 0l-2.51 2.225.569-9.47 5.227 7.917-3.286-.672zm-7.518-.267A8.25 8.25 0 1120.25 10.5M8.288 14.212A5.25 5.25 0 1117.25 10.5" />', 'color' => 'text-cyan-500', 'title' => 'Pilih Materi', 'desc' => 'Tentukan kelas atau topik spesifik Tailwind CSS yang ingin kamu kuasai hari ini.'],
                        ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />', 'color' => 'text-fuchsia-500', 'title' => 'Baca Teori Singkat', 'desc' => 'Pahami konsep utility-first dengan bahasa yang sederhana dan langsung ke intinya.'],
                        ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5" />', 'color' => 'text-indigo-500', 'title' => 'Langsung Praktik', 'desc' => 'Gunakan Live Sandbox untuk menulis dan melihat hasil kodemu secara instan.'],
                        ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />', 'color' => 'text-emerald-500', 'title' => 'Kumpulkan Poin', 'desc' => 'Jawab kuis ringan di akhir materi untuk memastikan kamu benar-benar paham.']
                    ] as $i => $step)
                    <div class="relative flex flex-col sm:flex-row items-start sm:items-center justify-between md:justify-normal md:odd:flex-row-reverse group reveal">
                        <div class="absolute left-0 md:left-1/2 w-14 h-14 icon-luxury rounded-full flex items-center justify-center shadow-lg z-10 group-hover:scale-110 transition duration-500 md:-translate-x-1/2 hidden sm:flex {{ $step['color'] }}">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.25" viewBox="0 0 24 24">{!! $step['icon'] !!}</svg>
                        </div>
                        <div class="w-full sm:pl-20 md:pl-0 md:w-[45%]">
                            <div class="p-6 md:p-8 bg-slate-50 dark:bg-[#020617] border border-slate-200 dark:border-white/5 rounded-2xl group-hover:bg-white dark:group-hover:bg-slate-900/50 transition-all duration-300 shadow-sm hover:shadow-xl hover:-translate-y-1 cursor-default">
                                <span class="text-[10px] font-mono {{ $step['color'] }} mb-3 block font-bold uppercase tracking-widest">Langkah 0{{ $i+1 }}</span>
                                <h3 class="text-lg md:text-xl font-bold text-slate-900 dark:text-white mb-2">{{ $step['title'] }}</h3>
                                <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed font-medium">{{ $step['desc'] }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- TARGET AUDIENCE SECTION --}}
    <section class="py-24 border-t border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-[#020617] relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-5 lg:px-8 relative z-10">
            <h2 class="text-3xl md:text-4xl font-black text-center text-slate-900 dark:text-white mb-12 revealleading-tight">Siapa yang <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-500 to-indigo-500 leading-tight">Cocok Belajar di Sini?</span></h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 reveal">
                @foreach([
                    ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15.59 14.37a6 6 0 01-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 006.16-12.12A14.98 14.98 0 009.631 8.41m5.96 5.96a14.926 14.926 0 01-5.841 2.58m-.119-8.54a6 6 0 00-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 00-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 01-2.448-2.448 14.9 14.9 0 01.06-.312m-2.24 2.39a4.493 4.493 0 00-1.757 4.306 4.434 4.434 0 002.946-2.946 4.493 4.493 0 004.306-1.758q-2.25-.001-4.501-4.5m0 0l-1.06-1.06" />', 'color' => 'text-cyan-600 dark:text-cyan-400', 'title' => 'Pemula (Beginner)', 'desc' => 'Yang baru belajar HTML & CSS dan ingin transisi ke framework modern.'],
                    ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5" />', 'color' => 'text-fuchsia-600 dark:text-fuchsia-400', 'title' => 'Frontend Developer', 'desc' => 'Yang ingin membuat UI cepat dan responsif tanpa pusing memikirkan class CSS.'],
                    ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" />', 'color' => 'text-indigo-600 dark:text-indigo-400', 'title' => 'Mahasiswa IT', 'desc' => 'Sebagai bahan referensi tugas akhir, atau persiapan portofolio kerja.']
                ] as $aud)
                <div class="bg-white/90 dark:bg-[#0a0e17]/90 backdrop-blur-sm p-8 rounded-[2rem] border border-slate-200 dark:border-white/5 text-center hover:-translate-y-2 transition-transform duration-300 shadow-sm hover:shadow-xl relative overflow-hidden">
                    <div class="w-16 h-16 mx-auto rounded-2xl icon-luxury flex items-center justify-center mb-6 {{ $aud['color'] }} relative z-10">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.25" viewBox="0 0 24 24">{!! $aud['icon'] !!}</svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3 relative z-10">{{ $aud['title'] }}</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 font-medium leading-relaxed relative z-10">{{ $aud['desc'] }}</p>
                    {{-- Subtle background blobcape --}}
                    <div class="absolute -inset-2 wrapper-bento-audience-bg opacity-30">
                        <div class="lux-blobascape" style="position: absolute; inset: 0; background: conic-gradient(from 180deg at 50% 50%, #d946ef22, #06b6d422); blur: 3xl; rounded: full;"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        {{-- Background Animation Target Audience (Layered Glass Blobs) --}}
        <div class="absolute inset-0 -z-0 lux-blobscape lux-atmosphere-layer opacity-40 wrapper-audience-bg">
            <div class="lux-blob-layer lux-blob-layer-1">
                <div class="lux-blob lux-blob-1-1" style="width: 25vw; height: 25vw; left: -5vw; top: 10vh; background: #06b6d433; animation-duration: 22s;"></div>
            </div>
            <div class="lux-blob-layer lux-blob-layer-3">
                <div class="lux-blob lux-blob-3-1" style="width: 18vw; height: 18vw; right: 10vw; bottom: 5vh; background: #d946ef33; animation-duration: 18s;"></div>
            </div>
        </div>
    </section>

    {{-- CALL TO ACTION --}}
    <section class="py-24 md:py-32 relative bg-white dark:bg-[#060a14] overflow-hidden border-t border-slate-200 dark:border-white/5">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-gradient-to-r from-cyan-400/20 via-fuchsia-400/20 to-indigo-400/20 rounded-full blur-[120px] pointer-events-none animate-pulse"></div>
        <div class="max-w-3xl mx-auto px-5 text-center relative z-10 reveal">
            <h2 class="text-4xl md:text-5xl lg:text-6xl font-black text-slate-900 dark:text-white mb-6 leading-[1.15]">Siap Menjadi <br> Tailwind Expert?</h2>
            <p class="text-slate-600 dark:text-slate-400 text-base md:text-lg mb-10 max-w-xl mx-auto font-medium">Buat akun gratismu hari ini. Akses penuh ke materi, kamus class, dan live sandbox tanpa batasan.</p>
            <a href="{{ route('register') }}" class="liquid-glass inline-flex items-center justify-center gap-2 px-10 py-5 rounded-full bg-slate-900 text-white dark:bg-white dark:text-slate-900 font-bold text-lg shadow-xl hover:shadow-2xl">
                Daftar Sekarang - Gratis!
            </a>
        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="bg-slate-50 dark:bg-[#020617] border-t border-slate-200 dark:border-white/10 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-5 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6 mb-12">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo.png') }}" alt="Utilwind" class="h-8 w-auto grayscale opacity-70 hover:grayscale-0 hover:opacity-100 transition duration-500" onerror="this.style.display='none'">
                    <span class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Utilwind</span>
                </div>
                <div class="flex flex-wrap justify-center gap-6 text-sm font-bold text-slate-600 dark:text-slate-400">
                    <a href="{{ route('courses.htmldancss') }}" class="hover:text-cyan-600 transition-colors">Course</a>
                    <a href="{{ route('sandbox') }}" class="hover:text-cyan-600 transition-colors">Sandbox</a>
                    <a href="{{ route('cheatsheet.index') }}" class="hover:text-fuchsia-600 transition-colors">Cheat Sheet</a>
                    <a href="{{ route('gallery.index') }}" class="hover:text-emerald-600 transition-colors">Gallery</a>
                </div>
                <div class="flex gap-4 text-sm font-bold">
                    @guest
                        <a href="{{ route('login') }}" class="text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors px-4 py-2">Masuk</a>
                        <a href="{{ route('register') }}" class="text-white dark:text-[#020617] bg-slate-900 dark:bg-white hover:bg-slate-800 transition-colors px-4 py-2 rounded-xl shadow-sm">Daftar</a>
                    @else
                        <a href="{{ route('dashboard') }}" class="text-indigo-700 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-500/20 hover:bg-indigo-100 dark:hover:bg-indigo-500/30 transition-colors px-4 py-2 rounded-xl border border-indigo-200 dark:border-indigo-500/30">Ke Dasbor &rarr;</a>
                    @endguest
                </div>
            </div>
            <div class="border-t border-slate-200 dark:border-white/5 pt-6 text-center md:text-left text-[11px] text-slate-500 font-mono">
                <p>&copy; {{ date('Y') }} Utilwind Interactive Learning.</p>
            </div>
        </div>
    </footer>

</div>

{{-- SCRIPT JQUERY & LOGIC ANIMASI --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
// THEME SWITCHER
const htmlEl = document.documentElement;
const themeToggleBtn = document.getElementById('themeToggle');
function initTheme() {
    if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        htmlEl.classList.add('dark');
    } else { htmlEl.classList.remove('dark'); }
}
initTheme();
if(themeToggleBtn) {
    themeToggleBtn.addEventListener('click', function() {
        htmlEl.classList.toggle('dark');
        localStorage.setItem('color-theme', htmlEl.classList.contains('dark') ? 'dark' : 'light');
    });
}

$(document).ready(function() {
    // Scroll Progress
    $(window).scroll(function() {
        let winScroll = $(this).scrollTop();
        let height = $(document).height() - $(window).height();
        $("#scrollProgress").css("width", (winScroll / height) * 100 + "%");
    });

    // Mouse Parallax Glow
    if(window.innerWidth > 768) {
        $(document).mousemove(function(e) { 
            let x = e.clientX / window.innerWidth, y = e.clientY / window.innerHeight;
            
            // Atmosfera glows
            $('#glow-1').css('transform', `translate(${x * 40}px, ${y * 40}px)`);
            $('#glow-2').css('transform', `translate(-${x * 30}px, -${y * 30}px)`);
            
            // Sihir Sandbox blobcscape parallax
            $('.wrapper-sandbox-bg').css('transform', `translate(${x * -20}px, ${y * -20}px)`);
            
            // Audience blobcscape parallax
            $('.wrapper-audience-bg').css('transform', `translate(${x * 20}px, ${y * 20}px)`);
        });
    }

    // 3D Tilt Effect
    if(window.innerWidth > 1024) {
        $('.perspective-1000').on('mousemove', function(e) {
            let el = $('#tiltEditor');
            let offset = $(this).offset(), w = $(this).width(), h = $(this).height();
            let mouseX = e.pageX - offset.left - w / 2, mouseY = e.pageY - offset.top - h / 2;
            let rotateX = (mouseY / h) * 12, rotateY = (mouseX / w) * -12;
            el.css('transform', `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.02, 1.02, 1.02)`);
        });
        $('.perspective-1000').on('mouseleave', function() {
            $('#tiltEditor').css({ 'transform': 'perspective(1000px) rotateY(-3deg) rotateX(3deg) scale3d(1, 1, 1)' });
        });
    }

    // Advanced Auto Typing
    const classesToType = [
        "bg-gradient-to-r from-cyan-500", 
        "hover:scale-110 shadow-lg", 
        "px-6 py-3 rounded-full text-white",
    ];
    let typeIndex = 0, charIndex = 0, isDeleting = false, typeElement = $('#autoType');

    function typeEffect() {
        let currentString = classesToType[typeIndex];
        if (isDeleting) {
            typeElement.text(currentString.substring(0, charIndex - 1)); charIndex--;
        } else {
            typeElement.text(currentString.substring(0, charIndex + 1)); charIndex++;
        }
        let typeSpeed = isDeleting ? 25 : 70;
        if (!isDeleting && charIndex === currentString.length) {
            typeSpeed = 2500; isDeleting = true;
        } else if (isDeleting && charIndex === 0) {
            isDeleting = false; typeIndex = (typeIndex + 1) % classesToType.length; typeSpeed = 500; 
        }
        setTimeout(typeEffect, typeSpeed);
    }
    setTimeout(typeEffect, 1000); 

    // Intersection Observer
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                $(entry.target).addClass('show');
                $(entry.target).find('.bar-anim').each(function() {
                    $(this).css('height', $(this).data('h'));
                });
                observer.unobserve(entry.target); 
            }
        });
    }, { threshold: 0.15 });
    $('.reveal').each(function() { observer.observe(this); });

    // Canvas Particles
    if(window.innerWidth > 768) {
        const canvas = document.getElementById('starsCanvas');
        if(canvas) {
            const ctx = canvas.getContext('2d');
            let particles = [];
            function resize() { canvas.width = window.innerWidth; canvas.height = window.innerHeight; }
            $(window).on('resize', resize); resize();
            for(let i=0; i<50; i++) {
                particles.push({ x: Math.random() * canvas.width, y: Math.random() * canvas.height, radius: Math.random() * 1.5, vx: Math.random() * 0.3 - 0.15, vy: Math.random() * 0.3 - 0.15 });
            }
            function drawParticles() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                ctx.fillStyle = htmlEl.classList.contains('dark') ? 'rgba(255, 255, 255, 0.3)' : 'rgba(15, 23, 42, 0.1)';
                ctx.beginPath();
                particles.forEach(p => {
                    ctx.moveTo(p.x, p.y); ctx.arc(p.x, p.y, p.radius, 0, Math.PI * 2);
                    p.x += p.vx; p.y += p.vy;
                    if(p.x < 0 || p.x > canvas.width) p.vx *= -1;
                    if(p.y < 0 || p.y > canvas.height) p.vy *= -1;
                });
                ctx.fill(); requestAnimationFrame(drawParticles);
            }
            drawParticles();
        }
    }
});
</script>
@endsection