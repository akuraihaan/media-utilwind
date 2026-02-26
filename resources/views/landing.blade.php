@extends('layouts.landing')

@section('title', 'Utilwind · Menguasai Tailwind CSS')

@section('content')
<div id="landingRoot" class="relative min-h-screen bg-[#020617] text-slate-300 font-sans overflow-x-hidden selection:bg-fuchsia-500/30 selection:text-white">

    {{-- ======================================================================
         1. ATMOSPHERE LAYER (Warna Asli: #020617 + Cyan & Fuchsia Glow)
         ====================================================================== --}}
    <div class="fixed inset-0 -z-50 pointer-events-none">
        {{-- Base Background Asli --}}
        <div class="absolute inset-0 bg-[#020617]"></div>
        
        {{-- Dynamic Glows (Bergerak perlahan via CSS/JS) --}}
        <div id="glow-1" class="absolute top-[-10%] -left-[10%] w-[500px] md:w-[800px] h-[500px] md:h-[800px] bg-fuchsia-600/10 rounded-full blur-[100px] transition-transform duration-[3s]"></div>
        <div id="glow-2" class="absolute bottom-[-10%] -right-[10%] w-[400px] md:w-[700px] h-[400px] md:h-[700px] bg-cyan-600/10 rounded-full blur-[100px] transition-transform duration-[4s]"></div>
        <div id="glow-3" class="absolute top-[30%] left-[40%] w-[300px] md:w-[500px] h-[300px] md:h-[500px] bg-indigo-600/10 rounded-full blur-[120px] transition-transform duration-[5s]"></div>
        
        {{-- Bintang Interaktif (Kembali Ditambahkan) --}}
        <canvas id="starsCanvas" class="absolute inset-0 z-0 opacity-60"></canvas>
        
        {{-- Tech Grid & Noise --}}
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGcgc3Ryb2tlPSJyZ2JhKDI1NSwgMjU1LCAyNTUsIDAuMDMpIiBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxwYXRoIGQ9Ik0wIDQwaDQwVjBIMHoiLz48L2c+PC9zdmc+')] opacity-50 z-0"></div>
        <div class="absolute inset-0 opacity-[0.04] mix-blend-overlay z-10" style="background-image: url('data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 200 200\'%3E%3Cfilter id=\'n\'%3E%3CfeTurbulence type=\'fractalNoise\' baseFrequency=\'0.8\' numOctaves=\'4\'/%3E%3C/filter%3E%3Crect width=\'100%25\' height=\'100%25\' filter=\'url(%23n)\'/%3E%3C/svg%3E');"></div>
    </div>

    @include('layouts.partials.navbar')

    {{-- Progress Bar Gradien Khas Utilwind --}}
    <div id="scrollProgress" class="fixed top-[60px] md:top-[80px] left-0 h-[2px] bg-gradient-to-r from-cyan-400 via-fuchsia-500 to-indigo-500 z-[60] shadow-[0_0_15px_#d946ef] transition-all duration-100" style="width:0%"></div>

    {{-- ======================================================================
         2. HERO SECTION (Dengan 3D Tilt & Auto Typing)
         ====================================================================== --}}
    <section id="hero" class="relative pt-32 pb-20 md:pt-40 md:pb-32 px-5 lg:px-8 min-h-[95vh] flex items-center z-20">
        <div class="max-w-7xl mx-auto w-full grid lg:grid-cols-2 gap-12 lg:gap-8 items-center">
            
            {{-- Left: Copywriting --}}
            <div class="text-center lg:text-left relative z-10">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/5 border border-white/10 text-[10px] md:text-xs font-bold font-mono tracking-widest text-fuchsia-400 mb-6 animate-fade-in-up backdrop-blur-md shadow-lg floating-element">
                    <span class="w-2 h-2 rounded-full bg-fuchsia-500 animate-pulse"></span>
                    Utility-First Mastery
                </div>

                <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-[4.5rem] font-black text-white leading-[1.1] mb-6 tracking-tight animate-fade-in-up" style="animation-delay: 0.1s;">
                    Berhenti Menulis <br class="hidden sm:block">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 via-fuchsia-400 to-indigo-400 animate-gradient-x pb-2">CSS Tradisional.</span>
                </h1>

                <p class="text-base sm:text-lg text-slate-400 leading-relaxed animate-fade-in-up max-w-2xl mx-auto lg:mx-0 mb-10 font-medium" style="animation-delay: 0.2s;">
                    Pelajari framework Tailwind CSS melalui environment <strong class="text-cyan-400 font-bold">Live Sandbox</strong>, kurikulum terstruktur, dan rasakan kecepatan membangun UI tanpa perlu meninggalkan file HTML Anda.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 animate-fade-in-up" style="animation-delay: 0.3s;">
                    @auth
                        <a href="{{ route('courses.curriculum') }}" class="w-full sm:w-auto px-8 py-3.5 rounded-xl bg-gradient-to-r from-fuchsia-600 to-indigo-600 hover:from-fuchsia-500 hover:to-indigo-500 text-white font-bold text-sm md:text-base transition-all shadow-[0_0_30px_rgba(217,70,239,0.3)] hover:scale-105 active:scale-95 flex items-center justify-center gap-2">
                            Lanjutkan Course <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-3.5 rounded-xl bg-white hover:bg-slate-200 text-[#020617] font-black text-sm md:text-base transition-all shadow-[0_0_30px_rgba(255,255,255,0.2)] hover:scale-105 active:scale-95 flex items-center justify-center">
                            Mulai Koding Gratis
                        </a>
                    @endauth
                    
                    <a href="#fitur" class="w-full sm:w-auto px-8 py-3.5 rounded-xl bg-[#0f141e]/80 border border-white/10 text-white font-bold text-sm md:text-base hover:bg-white/10 transition-all flex items-center justify-center gap-2 backdrop-blur-sm group">
                        <svg class="w-4 h-4 text-cyan-400 group-hover:translate-y-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                        Jelajahi Fitur
                    </a>
                </div>
            </div>

            {{-- Right: INTERACTIVE Code Editor Mockup (Bergerak 3D & Auto Typing) --}}
            <div class="relative w-full max-w-lg mx-auto lg:max-w-none animate-fade-in-up" style="animation-delay: 0.4s;">
                <div class="absolute -inset-1 bg-gradient-to-tr from-cyan-500 via-fuchsia-500 to-indigo-500 rounded-2xl blur-xl opacity-20 animate-pulse"></div>
                
                {{-- 3D Tilt Container --}}
                <div id="tiltEditor" class="relative rounded-2xl bg-[#0a0e17] border border-white/10 shadow-2xl overflow-hidden transform transition-transform duration-100 ease-out preserve-3d" style="transform: perspective(1000px) rotateY(-5deg) rotateX(5deg);">
                    
                    {{-- Mac OS Header --}}
                    <div class="flex items-center px-4 py-3 bg-[#020617] border-b border-white/5">
                        <div class="flex gap-2">
                            <div class="w-3 h-3 rounded-full bg-red-500/80 shadow-[0_0_5px_#ef4444]"></div>
                            <div class="w-3 h-3 rounded-full bg-yellow-500/80 shadow-[0_0_5px_#eab308]"></div>
                            <div class="w-3 h-3 rounded-full bg-emerald-500/80 shadow-[0_0_5px_#10b981]"></div>
                        </div>
                        <div class="mx-auto text-[10px] font-mono text-slate-500 font-bold">App.blade.php</div>
                    </div>
                    
                    {{-- Code Area dengan Efek Mengetik Sendiri --}}
                    <div class="p-5 font-mono text-xs sm:text-sm overflow-x-auto text-slate-300 leading-loose min-h-[200px]">
                        <div class="flex"><span class="w-6 text-slate-600 select-none">1</span> <span><span class="text-fuchsia-400">&lt;div</span> <span class="text-cyan-300">class=</span><span class="text-indigo-300">"max-w-md mx-auto bg-white rounded-xl shadow-md"</span><span class="text-fuchsia-400">&gt;</span></span></div>
                        <div class="flex"><span class="w-6 text-slate-600 select-none">2</span> <span>&nbsp;&nbsp;<span class="text-fuchsia-400">&lt;div</span> <span class="text-cyan-300">class=</span><span class="text-indigo-300">"p-8"</span><span class="text-fuchsia-400">&gt;</span></span></div>
                        <div class="flex"><span class="w-6 text-slate-600 select-none">3</span> <span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="text-fuchsia-400">&lt;div</span> <span class="text-cyan-300">class=</span><span class="text-indigo-300">"<span id="autoType" class="text-yellow-300 bg-yellow-500/10 px-1 rounded"></span><span class="animate-pulse bg-white/50 w-2 h-4 inline-block ml-[1px] align-middle"></span>"</span><span class="text-fuchsia-400">&gt;</span></span></div>
                        <div class="flex"><span class="w-6 text-slate-600 select-none">4</span> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Utilwind</span></div>
                        <div class="flex"><span class="w-6 text-slate-600 select-none">5</span> <span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="text-fuchsia-400">&lt;/div&gt;</span></span></div>
                        <div class="flex"><span class="w-6 text-slate-600 select-none">6</span> <span>&nbsp;&nbsp;<span class="text-fuchsia-400">&lt;/div&gt;</span></span></div>
                        <div class="flex"><span class="w-6 text-slate-600 select-none">7</span> <span><span class="text-fuchsia-400">&lt;/div&gt;</span></span></div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    {{-- ======================================================================
         3. BENTO GRID FEATURES (Desain Gelap & Elegan)
         ====================================================================== --}}
    <section id="fitur" class="py-24 relative border-t border-white/5 bg-[#020617]">
        <div class="max-w-7xl mx-auto px-5 lg:px-8">
            
            <div class="text-center mb-16 md:mb-24 reveal">
                <div class="inline-block px-4 py-1.5 rounded-full border border-cyan-500/30 text-cyan-400 text-[10px] font-bold tracking-widest uppercase mb-6 bg-cyan-500/10 shadow-[0_0_15px_rgba(6,182,212,0.2)]">
                    Ekosistem Belajar
                </div>
                <h2 class="text-3xl md:text-5xl font-black text-white">Senjata <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-indigo-400">Developer</span></h2>
            </div>

            {{-- BENTO GRID --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
                
                {{-- Feature 1: Kurikulum (Besar) --}}
                <div class="md:col-span-2 group p-8 rounded-[2rem] bg-[#0f141e] border border-white/5 hover:border-indigo-500/30 transition-all duration-500 reveal flex flex-col justify-between overflow-hidden relative interactive-card">
                    <div class="absolute right-0 top-0 w-64 h-64 bg-indigo-500/10 rounded-full blur-[80px] group-hover:bg-indigo-500/20 transition duration-700"></div>
                    
                    <div class="relative z-10 flex-1">
                        <div class="w-12 h-12 rounded-xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center mb-6 text-indigo-400 shadow-[0_0_15px_rgba(99,102,241,0.2)]">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-3 group-hover:text-indigo-400 transition">Kurikulum Terstruktur</h3>
                        <p class="text-sm text-slate-400 leading-relaxed max-w-md mb-8">Materi disusun bertahap. Dari memahami Fundamental Box Model, Flexbox, hingga teknik Grid Layout dan Breakpoints responsif.</p>
                        
                        <a href="{{ route('courses.htmldancss') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-indigo-600/20 text-indigo-400 font-bold text-xs hover:bg-indigo-600 hover:text-white transition border border-indigo-500/30">
                            Mulai Belajar &rarr;
                        </a>
                    </div>
                </div>

                {{-- Feature 2: Cheat Sheet --}}
                <div class="group p-8 rounded-[2rem] bg-[#0f141e] border border-white/5 hover:border-fuchsia-500/30 transition-all duration-500 reveal flex flex-col h-full relative overflow-hidden interactive-card" style="transition-delay: 100ms">
                    <div class="absolute bottom-0 left-0 w-32 h-32 bg-fuchsia-500/10 rounded-full blur-[60px] group-hover:bg-fuchsia-500/20 transition duration-700"></div>
                    <div class="relative z-10 flex-1">
                        <div class="w-12 h-12 rounded-xl bg-fuchsia-500/10 border border-fuchsia-500/20 flex items-center justify-center mb-6 text-fuchsia-400 shadow-[0_0_15px_rgba(217,70,239,0.2)]">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-3 group-hover:text-fuchsia-400 transition">Cheat Sheet</h3>
                        <p class="text-sm text-slate-400 leading-relaxed mb-6">Mesin pencari instan untuk menemukan ribuan nama class (Flex, Spacing, Color) dengan sekali klik copy.</p>
                        <a href="{{ route('cheatsheet.index') }}" class="text-xs font-bold text-fuchsia-400 uppercase tracking-widest flex items-center gap-2 group-hover:translate-x-2 transition-transform mt-auto">
                            Buka Kamus &rarr;
                        </a>
                    </div>
                </div>

                {{-- Feature 3: Live Sandbox --}}
                <div class="group p-8 rounded-[2rem] bg-[#0f141e] border border-white/5 hover:border-cyan-500/30 transition-all duration-500 reveal flex flex-col h-full relative overflow-hidden interactive-card" style="transition-delay: 200ms">
                    <div class="absolute top-0 left-0 w-32 h-32 bg-cyan-500/10 rounded-full blur-[60px] group-hover:bg-cyan-500/20 transition duration-700"></div>
                    <div class="relative z-10 flex-1">
                        <div class="w-12 h-12 rounded-xl bg-cyan-500/10 border border-cyan-500/20 flex items-center justify-center mb-6 text-cyan-400 shadow-[0_0_15px_rgba(6,182,212,0.2)]">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-3 group-hover:text-cyan-400 transition">Live Sandbox</h3>
                        <p class="text-sm text-slate-400 leading-relaxed mb-6">Playground koding interaktif. Tulis class utilitas dan lihat perubahan UI secara seketika tanpa setup Node.js.</p>
                        <a href="{{ route('sandbox') }}" class="text-xs font-bold text-cyan-400 uppercase tracking-widest flex items-center gap-2 group-hover:translate-x-2 transition-transform mt-auto">
                            Mulai Koding &rarr;
                        </a>
                    </div>
                </div>

                {{-- Feature 4: Analytics (Large) --}}
                <div class="md:col-span-2 group p-8 rounded-[2rem] bg-[#0f141e] border border-white/5 hover:border-emerald-500/30 transition-all duration-500 reveal flex flex-col md:flex-row items-center gap-8 overflow-hidden relative interactive-card" style="transition-delay: 300ms">
                    <div class="absolute bottom-0 right-0 w-64 h-64 bg-emerald-500/10 rounded-full blur-[80px] group-hover:bg-emerald-500/20 transition duration-700"></div>
                    
                    <div class="flex-1 relative z-10">
                        <div class="w-12 h-12 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center mb-6 text-emerald-400 shadow-[0_0_15px_rgba(16,185,129,0.2)]">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-3 group-hover:text-emerald-400 transition">Analitik Performa</h3>
                        <p class="text-sm text-slate-400 leading-relaxed max-w-sm mb-6">Sistem dasbor cerdas mencatat durasi praktik, nilai kuis, dan riwayat penyelesaian lab untuk memetakan pertumbuhan skill Anda.</p>
                        <a href="{{ route('dashboard') }}" class="text-xs font-bold text-emerald-400 uppercase tracking-widest flex items-center gap-2 group-hover:translate-x-2 transition-transform">
                            Lihat Dasbor &rarr;
                        </a>
                    </div>
                    
                    {{-- Animated Bar Chart Mockup (Bisa bergerak sendiri dengan jQuery) --}}
                    <div class="relative z-10 w-full md:w-64 h-32 bg-[#020617] rounded-xl border border-white/5 flex items-end gap-3 p-4 pt-10 shadow-lg">
                        <div class="w-full bg-[#1e293b] rounded-t-md bar-anim" data-h="40%"></div>
                        <div class="w-full bg-[#334155] rounded-t-md bar-anim" data-h="65%"></div>
                        <div class="w-full bg-emerald-500 rounded-t-md bar-anim shadow-[0_0_15px_rgba(16,185,129,0.4)] relative flex justify-center" data-h="95%">
                            <span class="absolute -top-6 text-[10px] font-black text-emerald-400 opacity-0 group-hover:opacity-100 transition-opacity delay-300">95%</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ======================================================================
         4. DEVELOPMENT WORKFLOW (Terminal Style)
         ====================================================================== --}}
    <section class="py-20 md:py-32 relative bg-[#060a14] border-t border-white/5 overflow-hidden">
        <div class="max-w-7xl mx-auto px-5 lg:px-8">
            <h2 class="text-3xl md:text-4xl font-black text-center text-white mb-16 reveal">Workflow <span class="font-mono text-fuchsia-400">Pembelajaran</span></h2>
            
            <div class="relative max-w-4xl mx-auto">
                {{-- Vertical Line --}}
                <div class="absolute left-[27px] md:left-1/2 top-0 bottom-0 w-[2px] bg-gradient-to-b from-cyan-500/20 via-fuchsia-500/20 to-transparent hidden sm:block md:-translate-x-1/2"></div>

                <div class="space-y-12">
                    @foreach([
                        ['icon' => '🔑', 'title' => 'Input Token & Registrasi', 'desc' => 'Dapatkan akses kelas eksklusif dari instruktur Anda.'],
                        ['icon' => '📖', 'title' => 'Pelajari Konsep', 'desc' => 'Baca teori fundamental web layouting dan tipografi.'],
                        ['icon' => '💻', 'title' => 'Praktikum Live Code', 'desc' => 'Terapkan Utility Class di dalam Interactive Code Editor.'],
                        ['icon' => '📊', 'title' => 'Validasi & Analitik', 'desc' => 'Uji pemahaman dan pantau perkembangan skor di Dasbor.']
                    ] as $i => $step)
                    <div class="relative flex flex-col sm:flex-row items-start sm:items-center justify-between md:justify-normal md:odd:flex-row-reverse group reveal">
                        
                        {{-- Node Marker --}}
                        <div class="absolute left-0 md:left-1/2 w-14 h-14 bg-[#0a0e17] border-[3px] border-white/10 rounded-full flex items-center justify-center text-xl shadow-lg z-10 group-hover:border-cyan-500 group-hover:shadow-[0_0_20px_rgba(34,211,238,0.4)] transition duration-500 md:-translate-x-1/2 hidden sm:flex floating-element" style="animation-delay: {{ $i * 0.2 }}s">
                            {{ $step['icon'] }}
                        </div>

                        {{-- Content Box --}}
                        <div class="w-full sm:pl-20 md:pl-0 md:w-[45%]">
                            <div class="p-6 bg-[#020617] border border-white/5 rounded-2xl group-hover:bg-white/[0.03] group-hover:border-white/10 transition duration-300 shadow-lg">
                                <span class="text-[10px] font-mono text-cyan-500 mb-2 block font-bold">Langkah 0{{ $i+1 }}</span>
                                <h3 class="text-base md:text-lg font-bold text-white mb-2 group-hover:text-cyan-400 transition">{{ $step['title'] }}</h3>
                                <p class="text-sm text-slate-400 leading-relaxed">{{ $step['desc'] }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ======================================================================
         5. CALL TO ACTION (Final Push with Intense Glow)
         ====================================================================== --}}
    <section class="py-24 md:py-32 relative bg-[#020617] overflow-hidden border-t border-white/5">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-gradient-to-r from-cyan-600/20 to-indigo-600/20 rounded-full blur-[150px] pointer-events-none"></div>

        <div class="max-w-3xl mx-auto px-5 text-center relative z-10 reveal">
            <h2 class="text-4xl md:text-5xl lg:text-6xl font-black text-white mb-6 leading-tight">Siap mempercepat <br> workflow koding Anda?</h2>
            <p class="text-slate-400 text-base md:text-lg mb-10 max-w-xl mx-auto">Bergabung sekarang dan rasakan kecepatan membangun UI modern dengan framework Utility-first paling populer.</p>
            
            <a href="{{ route('register') }}" class="inline-flex items-center justify-center gap-2 px-10 py-4 rounded-xl bg-white text-[#020617] font-black text-lg hover:bg-slate-200 hover:scale-105 active:scale-95 transition-all shadow-[0_0_40px_rgba(255,255,255,0.2)]">
                Buat Akun Gratis
            </a>
        </div>
    </section>

    {{-- ======================================================================
         6. FOOTER (Minimalist, Vercel Style)
         ====================================================================== --}}
    <footer class="bg-[#020617] border-t border-white/10 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-5 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6 mb-12">
                
                {{-- Logo --}}
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo.png') }}" alt="Utilwind Logo" class="h-6 w-auto grayscale opacity-70 hover:grayscale-0 hover:opacity-100 transition duration-500" onerror="this.style.display='none'">
                    <span class="text-xl font-black text-white tracking-tight">Utilwind</span>
                </div>
                
                {{-- Horizontal Links --}}
                <div class="flex flex-wrap justify-center gap-6 text-sm font-medium text-slate-400">
                    <a href="{{ route('courses.htmldancss') }}" class="hover:text-cyan-400 transition">Course</a>
                    <a href="{{ route('sandbox') }}" class="hover:text-cyan-400 transition">Sandbox</a>
                    <a href="{{ route('cheatsheet.index') }}" class="hover:text-fuchsia-400 transition">Cheat Sheet</a>
                    <a href="{{ route('gallery.index') }}" class="hover:text-emerald-400 transition">Gallery</a>
                </div>

                {{-- Action --}}
                <div class="flex gap-4 text-sm font-bold">
                    @guest
                        <a href="{{ route('login') }}" class="text-slate-400 hover:text-white transition px-4 py-2">Masuk</a>
                        <a href="{{ route('register') }}" class="text-[#020617] bg-white hover:bg-slate-200 transition px-4 py-2 rounded-lg">Daftar</a>
                    @else
                        <a href="{{ route('dashboard') }}" class="text-indigo-400 bg-indigo-500/10 hover:bg-indigo-500/20 transition px-4 py-2 rounded-lg border border-indigo-500/20">Ke Dasbor &rarr;</a>
                    @endguest
                </div>
            </div>
            
            <div class="border-t border-white/5 pt-6 text-center md:text-left text-[11px] text-slate-600 font-mono flex flex-col md:flex-row justify-between items-center gap-3">
                <p>&copy; {{ date('Y') }} Utilwind Academy. Coded with ❤️ for Developers.</p>
                <div class="flex gap-4">
                    <a href="#" class="hover:text-slate-400 transition">Privacy Policy</a>
                    <a href="#" class="hover:text-slate-400 transition">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

</div>

{{-- SCRIPT & STYLE TAMBAHAN --}}
<style>
    /* CSS Animasi Asli */
    .animate-gradient-x { background-size: 200% 200%; animation: gradient-x 5s ease infinite; }
    @keyframes gradient-x { 0%, 100% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } }
    
    .animate-fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }

    .reveal { opacity: 0; transform: translateY(40px); transition: all 1s cubic-bezier(0.16, 1, 0.3, 1); }
    .reveal.show { opacity: 1; transform: translateY(0); }

    /* 3D Perspective untuk Mockup Editor */
    .preserve-3d { transform-style: preserve-3d; }
    .perspective-1000 { perspective: 1000px; }

    /* Animasi Mengambang (Floating) */
    @keyframes float { 0% { transform: translateY(0px); } 50% { transform: translateY(-10px); } 100% { transform: translateY(0px); } }
    .floating-element { animation: float 4s ease-in-out infinite; }
</style>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function() {
    
    // 1. SCROLL PROGRESS BAR
    $(window).scroll(function() {
        let winScroll = $(this).scrollTop();
        let height = $(document).height() - $(window).height();
        let scrolled = (winScroll / height) * 100;
        $("#scrollProgress").css("width", scrolled + "%");
    });

    // 2. PARALLAX GLOW BACKGROUNDS (Interaksi Mouse)
    if(window.innerWidth > 768) {
        $(document).mousemove(function(e) { 
            let x = e.clientX / window.innerWidth;
            let y = e.clientY / window.innerHeight;
            
            $('#glow-1').css('transform', `translate(${x * 40}px, ${y * 40}px)`);
            $('#glow-2').css('transform', `translate(-${x * 30}px, -${y * 30}px)`);
            $('#glow-3').css('transform', `translate(${x * 50}px, -${y * 50}px)`);
        });
    }

    // 3. 3D TILT EFFECT MOUSE INTERACTION PADA EDITOR CODE (JQUERY)
    if(window.innerWidth > 1024) {
        $('.perspective-1000').on('mousemove', function(e) {
            let el = $('#tiltEditor');
            let offset = $(this).offset();
            let w = $(this).width();
            let h = $(this).height();
            
            // Kalkulasi posisi mouse dari tengah elemen
            let mouseX = e.pageX - offset.left - w / 2;
            let mouseY = e.pageY - offset.top - h / 2;
            
            // Putar elemen
            let rotateX = (mouseY / h) * 15; // Max 15 derajat
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

    // 4. AUTO TYPING EFFECT (JQUERY) PADA KODE TAILWIND
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
            typeSpeed = 2000; // Pause sebelum menghapus
            isDeleting = true;
        } else if (isDeleting && charIndex === 0) {
            isDeleting = false;
            typeIndex++;
            if (typeIndex >= classesToType.length) typeIndex = 0;
            typeSpeed = 500; // Pause sebelum ngetik kata baru
        }

        setTimeout(typeEffect, typeSpeed);
    }
    setTimeout(typeEffect, 1500); // Mulai setelah animasi hero selesai

    // 5. INTERSECTION OBSERVER (REVEAL & ANIMASI BAR)
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                // Munculkan elemen
                $(entry.target).addClass('show');
                
                // Jika elemen tersebut adalah Interactive Card, jalankan animasi Bar Chart
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

    // 6. CANVAS PARTICLES / BINTANG (Sesuai request dari tema sebelumnya)
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
                ctx.fillStyle = 'rgba(255, 255, 255, 0.4)';
                ctx.beginPath();
                particles.forEach(p => {
                    ctx.moveTo(p.x, p.y);
                    ctx.arc(p.x, p.y, p.radius, 0, Math.PI * 2);
                    
                    // Move particles
                    p.x += p.vx; p.y += p.vy;
                    // Reset if out of bounds
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