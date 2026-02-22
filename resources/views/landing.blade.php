@extends('layouts.landing')

@section('title', 'Utilwind · Media Pembelajaran Interaktif')

@section('content')
<div id="landingRoot" class="relative min-h-screen bg-[#020617] text-slate-200 font-sans overflow-x-hidden selection:bg-fuchsia-500/30 selection:text-white">

    {{-- ======================================================================
         1. ATMOSPHERE LAYER (Global Effects)
         ====================================================================== --}}
    <div class="fixed inset-0 -z-50 pointer-events-none">
        <div id="animated-bg" class="absolute inset-0 opacity-60"></div>
        <div id="gradient-wave" class="absolute inset-0"></div>
        <canvas id="stars" class="absolute inset-0 pointer-events-none"></canvas>
        <div id="noise-overlay" class="absolute inset-0 z-10 opacity-[0.04]"></div>
        <div id="cursor-glow"></div>
    </div>

    @include('layouts.partials.navbar')

    {{-- Scroll Progress Bar --}}
    <div id="scrollProgress" class="fixed top-[70px] md:top-[80px] left-0 h-[3px] bg-gradient-to-r from-cyan-400 via-fuchsia-500 to-indigo-500 z-[60] shadow-[0_0_15px_#d946ef] transition-all duration-100" style="width:0%"></div>

    {{-- ======================================================================
         2. HERO SECTION
         Transisi: Background Transparent (mengikuti Global BG) -> Menuju Toolkit
         ====================================================================== --}}
    <section id="hero" class="relative pt-32 pb-24 md:pt-48 md:pb-40 px-6 lg:px-8 overflow-hidden min-h-[90vh] flex items-center justify-center z-20">
        
        {{-- Parallax Orbs --}}
        <div class="absolute top-1/4 left-[5%] w-40 h-40 md:w-64 md:h-64 bg-fuchsia-600/10 rounded-full blur-[80px] parallax" data-speed="2"></div>
        <div class="absolute bottom-1/4 right-[5%] w-60 h-60 md:w-80 md:h-80 bg-cyan-600/10 rounded-full blur-[100px] parallax" data-speed="-2"></div>

        <div class="max-w-6xl mx-auto text-center relative z-10">
            
            {{-- LOGO UTAMA --}}
            <div class="mb-10 animate-fade-in-up flex justify-center">
                {{-- <img src="{{ asset('images/logo.png') }}" 
                     alt="Logo Utilwind" 
                     class="h-20 md:h-28 lg:h-32 w-auto object-contain drop-shadow-[0_0_35px_rgba(255,255,255,0.1)] hover:scale-105 transition duration-500"> --}}
            </div>

            {{-- Headline --}}
            <h1 class="text-5xl md:text-7xl lg:text-8xl font-black text-white leading-[1.1] mb-8 tracking-tight animate-fade-in-up" style="animation-delay: 0.1s;">
                Belajar <span class="text-transparent bg-clip-text bg-gradient-to-r from-fuchsia-400 via-purple-400 to-cyan-400 animate-gradient-x">Web Design</span> <br>
                Dengan Tutorial Terstruktur
            </h1>

            @auth
                <p class="mt-4 text-emerald-400 font-bold text-lg animate-fade-in-up" style="animation-delay: 0.2s;">
                    👋 Selamat datang kembali, {{ Auth::user()->name }}
                </p>
            @endauth

            {{-- Deskripsi --}}
            <p class="mt-8 max-w-3xl mx-auto text-lg md:text-xl lg:text-2xl text-white/60 leading-relaxed animate-fade-in-up px-4" style="animation-delay: 0.2s;">
                Platform pembelajaran berbasis web yang dirancang untuk mendukung proses belajar secara bertahap, interaktif, dan terukur melalui sistem monitoring progress.
            </p>

            {{-- CTA Buttons Area --}}
            <div class="mt-12 flex flex-col sm:flex-row justify-center items-center gap-4 animate-fade-in-up w-full px-4" style="animation-delay: 0.3s;">
                
                {{-- Primary Action --}}
                @auth
                    <a href="{{ route('courses.curriculum') }}" class="w-full sm:w-auto px-8 py-4 rounded-2xl bg-gradient-to-r from-emerald-500 to-cyan-600 text-white font-bold text-lg hover:scale-105 transition-transform shadow-[0_0_30px_rgba(16,185,129,0.4)] ring-1 ring-white/20 flex justify-center items-center gap-2">
                        <span>Lanjutkan Belajar</span>
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                    </a>
                @else
                    <a href="{{ route('register') }}" class="w-full sm:w-auto group relative px-8 py-4 rounded-2xl bg-white text-black font-bold text-lg hover:scale-105 transition-transform shadow-[0_0_40px_rgba(255,255,255,0.3)] overflow-hidden text-center flex justify-center items-center gap-2">
                        <span class="relative z-10">Mulai Belajar Gratis</span>
                        <div class="absolute inset-0 bg-gradient-to-r from-cyan-100 to-fuchsia-100 opacity-0 group-hover:opacity-100 transition duration-300"></div>
                    </a>
                @endauth

                {{-- Secondary Toolkit Actions --}}
                <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto mt-4 sm:mt-0">
                    <a href="{{ route('cheatsheet.index') }}" class="flex-1 sm:flex-none px-6 py-4 rounded-2xl bg-white/5 border border-white/10 text-white font-bold hover:bg-white/10 hover:border-cyan-500/50 transition flex items-center justify-center gap-2 group backdrop-blur-sm">
                        <span class="text-cyan-400 group-hover:scale-110 transition">⚡</span> Cheat Sheet
                    </a>
                    <a href="{{ route('gallery.index') }}" class="flex-1 sm:flex-none px-6 py-4 rounded-2xl bg-white/5 border border-white/10 text-white font-bold hover:bg-white/10 hover:border-fuchsia-500/50 transition flex items-center justify-center gap-2 group backdrop-blur-sm">
                        <span class="text-fuchsia-400 group-hover:scale-110 transition">🧩</span> Gallery
                    </a>
                </div>
            </div>

            {{-- Scroll Indicator --}}
            <div id="scrollHint" class="absolute bottom-[-50px] left-1/2 -translate-x-1/2 flex flex-col items-center gap-3 animate-bounce opacity-50 cursor-pointer" onclick="document.getElementById('fitur').scrollIntoView({behavior: 'smooth'})">
                <span class="text-[10px] uppercase tracking-[0.3em] font-bold">Scroll Down</span>
                <div class="w-5 h-8 border-2 border-white/30 rounded-full flex justify-center p-1">
                    <div class="w-1 h-2 bg-white/50 rounded-full animate-scroll-dot"></div>
                </div>
            </div>
        </div>
    </section>

    {{-- ======================================================================
         3. TOOLKIT & EKOSISTEM (GRADIENT TRANSITION 1)
         Transisi: Dari Dark (#020617) -> Smooth Fade ke Medium Dark (#0b0f19)
         ====================================================================== --}}
    <section id="fitur" class="py-24 md:py-32 relative bg-gradient-to-b from-[#020617] via-[#060a14] to-[#0b0f19] border-t border-white/5">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-16 md:mb-24 reveal">
                <h2 class="text-3xl md:text-5xl font-black text-white mb-6">Toolkit & Ekosistem</h2>
                <p class="text-white/60 text-lg md:text-xl max-w-2xl mx-auto">
                    Akses alat bantu development yang kami sediakan untuk mempercepat workflow Anda.
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                
                {{-- CARD 1: MATERI --}}
                <a href="{{ route('courses.htmldancss') }}" class="group relative p-8 rounded-[2.5rem] bg-[#0f141e] border border-white/5 hover:border-emerald-500/50 transition-all duration-500 hover:-translate-y-2 reveal hover:shadow-2xl hover:shadow-emerald-900/20 h-full flex flex-col overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/10 to-transparent opacity-0 group-hover:opacity-100 transition duration-500"></div>
                    <div class="w-16 h-16 rounded-2xl bg-emerald-500/10 flex items-center justify-center text-3xl mb-8 border border-emerald-500/20 group-hover:scale-110 transition shadow-inner shadow-emerald-500/20 relative z-10">📚</div>
                    <div class="relative z-10 flex-1 flex flex-col">
                        <h3 class="text-2xl font-bold text-white mb-3 group-hover:text-emerald-400 transition">Materi Course</h3>
                        <p class="text-white/50 text-sm leading-relaxed mb-8 flex-1">Kurikulum langkah-demi-langkah dari HTML dasar hingga teknik layouting advanced.</p>
                        <div class="flex items-center text-emerald-400 text-xs font-bold uppercase tracking-wider">
                            Mulai Belajar <span class="ml-2 group-hover:translate-x-2 transition">→</span>
                        </div>
                    </div>
                </a>

                {{-- CARD 2: CHEAT SHEET --}}
                <a href="{{ route('cheatsheet.index') }}" class="group relative p-8 rounded-[2.5rem] bg-[#0f141e] border border-white/5 hover:border-cyan-500/50 transition-all duration-500 hover:-translate-y-2 reveal hover:shadow-2xl hover:shadow-cyan-900/20 h-full flex flex-col overflow-hidden" style="transition-delay: 100ms">
                    <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/10 to-transparent opacity-0 group-hover:opacity-100 transition duration-500"></div>
                    <div class="w-16 h-16 rounded-2xl bg-cyan-500/10 flex items-center justify-center text-3xl mb-8 border border-cyan-500/20 group-hover:scale-110 transition shadow-inner shadow-cyan-500/20 relative z-10">⚡</div>
                    <div class="relative z-10 flex-1 flex flex-col">
                        <h3 class="text-2xl font-bold text-white mb-3 group-hover:text-cyan-400 transition">Cheat Sheet</h3>
                        <p class="text-white/50 text-sm leading-relaxed mb-8 flex-1">Kamus lengkap class utilitas dengan fitur pencarian instan dan one-click copy.</p>
                        <div class="flex items-center text-cyan-400 text-xs font-bold uppercase tracking-wider">
                            Buka Kamus <span class="ml-2 group-hover:translate-x-2 transition">→</span>
                        </div>
                    </div>
                </a>

                {{-- CARD 3: COMPONENT GALLERY --}}
                <a href="{{ route('gallery.index') }}" class="group relative p-8 rounded-[2.5rem] bg-[#0f141e] border border-white/5 hover:border-fuchsia-500/50 transition-all duration-500 hover:-translate-y-2 reveal hover:shadow-2xl hover:shadow-fuchsia-900/20 h-full flex flex-col overflow-hidden" style="transition-delay: 200ms">
                    <div class="absolute inset-0 bg-gradient-to-br from-fuchsia-500/10 to-transparent opacity-0 group-hover:opacity-100 transition duration-500"></div>
                    <div class="w-16 h-16 rounded-2xl bg-fuchsia-500/10 flex items-center justify-center text-3xl mb-8 border border-fuchsia-500/20 group-hover:scale-110 transition shadow-inner shadow-fuchsia-500/20 relative z-10">🧩</div>
                    <div class="relative z-10 flex-1 flex flex-col">
                        <h3 class="text-2xl font-bold text-white mb-3 group-hover:text-fuchsia-400 transition">UI Gallery</h3>
                        <p class="text-white/50 text-sm leading-relaxed mb-8 flex-1">Koleksi komponen modern (Card, Button, Navbar) siap pakai. Copy, paste, selesai.</p>
                        <div class="flex items-center text-fuchsia-400 text-xs font-bold uppercase tracking-wider">
                            Lihat Komponen <span class="ml-2 group-hover:translate-x-2 transition">→</span>
                        </div>
                    </div>
                </a>

                {{-- CARD 4: SANDBOX --}}
                <a href="{{ route('sandbox') }}" class="group relative p-8 rounded-[2.5rem] bg-[#0f141e] border border-white/5 hover:border-amber-500/50 transition-all duration-500 hover:-translate-y-2 reveal hover:shadow-2xl hover:shadow-amber-900/20 h-full flex flex-col overflow-hidden" style="transition-delay: 300ms">
                    <div class="absolute inset-0 bg-gradient-to-br from-amber-500/10 to-transparent opacity-0 group-hover:opacity-100 transition duration-500"></div>
                    <div class="w-16 h-16 rounded-2xl bg-amber-500/10 flex items-center justify-center text-3xl mb-8 border border-amber-500/20 group-hover:scale-110 transition shadow-inner shadow-amber-500/20 relative z-10">🛠️</div>
                    <div class="relative z-10 flex-1 flex flex-col">
                        <h3 class="text-2xl font-bold text-white mb-3 group-hover:text-amber-400 transition">Live Sandbox</h3>
                        <p class="text-white/50 text-sm leading-relaxed mb-8 flex-1">Editor kode real-time. Bereksperimen dengan Tailwind tanpa perlu setup environment.</p>
                        <div class="flex items-center text-amber-400 text-xs font-bold uppercase tracking-wider">
                            Mulai Coding <span class="ml-2 group-hover:translate-x-2 transition">→</span>
                        </div>
                    </div>
                </a>

            </div>
        </div>
    </section>

    {{-- ======================================================================
         4. METODE BELAJAR (SOLID BACKGROUND)
         Bagian Tengah: Warna Solid Medium Dark (#0b0f19)
         ====================================================================== --}}
    <section id="tentang" class="py-24 md:py-32 relative bg-[#0b0f19]">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-16 lg:gap-24 items-center">
                <div class="reveal">
                    <h2 class="text-3xl md:text-5xl font-black text-white leading-tight mb-6">
                        Metode Belajar <br>
                        <span class="text-fuchsia-400">Step-by-Step</span>
                    </h2>
                    <p class="text-white/60 text-lg leading-relaxed mb-8">
                        Utilwind dikembangkan untuk mendukung proses pembelajaran mandiri. Evaluasi quiz dan pencatatan attempt digunakan untuk memonitor perkembangan secara objektif.
                    </p>
                    <div class="flex flex-wrap gap-3">
                        @foreach(['Materi Terstruktur', 'Evaluasi Quiz', 'Tracking Progress', 'Gamifikasi'] as $tag)
                            <span class="px-4 py-2 rounded-xl bg-white/5 border border-white/10 text-sm font-bold text-white/70 hover:bg-white/10 transition cursor-default">{{ $tag }}</span>
                        @endforeach
                    </div>
                </div>

                {{-- 3D Tilt Card --}}
                <div class="tilt-card group relative p-1 rounded-3xl bg-gradient-to-b from-white/10 to-white/5 hover:from-cyan-500/50 hover:to-fuchsia-500/50 transition-all duration-500 reveal">
                    <div class="relative h-full bg-[#0e121d] rounded-[22px] p-8 md:p-10 overflow-hidden border border-white/5">
                        <div class="absolute top-0 right-0 w-40 h-40 bg-fuchsia-500/10 rounded-full blur-[50px]"></div>
                        <ul class="space-y-6">
                            @foreach(['Materi dari dasar hingga mahir', 'Studi kasus nyata', 'Quiz interaktif', 'Sandbox coding live'] as $item)
                            <li class="flex items-start gap-4 text-white/80">
                                <div class="w-6 h-6 rounded-full bg-emerald-500/20 flex items-center justify-center text-emerald-400 font-bold text-xs mt-0.5 shrink-0 border border-emerald-500/20">✓</div>
                                <span class="leading-snug">{{ $item }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ======================================================================
         5. ROADMAP ALUR (GRADIENT TRANSITION 2)
         Transisi: Medium Dark (#0b0f19) -> Smooth Fade Back to Dark (#020617)
         ====================================================================== --}}
    <section id="alur" class="py-24 md:py-32 relative bg-gradient-to-b from-[#0b0f19] to-[#020617]">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <h2 class="text-3xl md:text-5xl font-black text-center text-white mb-20 reveal">Roadmap Belajar</h2>
            
            <div class="relative">
                <div class="absolute top-12 left-0 w-full h-0.5 bg-white/5 hidden md:block"></div>

                <div class="grid md:grid-cols-4 gap-12 md:gap-8 relative z-10">
                    @foreach(['Daftar Akun', 'Pilih Materi', 'Kerjakan Quiz', 'Lihat Progress'] as $i => $step)
                    <div class="group text-center reveal" style="transition-delay: {{ $i * 100 }}ms">
                        <div class="w-24 h-24 mx-auto rounded-full bg-[#0f141e] border border-white/10 flex items-center justify-center text-4xl font-black text-white mb-6 shadow-[0_0_20px_rgba(0,0,0,0.5)] group-hover:border-cyan-500/50 group-hover:scale-110 transition duration-500 relative overflow-hidden">
                            <div class="absolute inset-0 bg-cyan-500/10 opacity-0 group-hover:opacity-100 transition duration-500 rounded-full"></div>
                            <span class="relative z-10 group-hover:text-cyan-400 transition">{{ $i+1 }}</span>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2 group-hover:text-cyan-400 transition">{{ $step }}</h3>
                        <p class="text-sm text-white/40">Langkah {{ $i+1 }} menuju pro.</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ======================================================================
         6. DASHBOARD PREVIEW (DEEP DARK MODE)
         Menggunakan Background Gelap Penuh (#020617) untuk kontras tinggi
         ====================================================================== --}}
    <section class="py-24 md:py-32 relative bg-[#020617] overflow-hidden border-t border-white/5">
        
        {{-- Background Spotlight --}}
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] md:w-[900px] h-[600px] md:h-[900px] bg-indigo-900/10 rounded-full blur-[150px] pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">
            <div class="grid lg:grid-cols-2 gap-16 md:gap-24 items-center">
                
                {{-- Content Text --}}
                <div class="reveal">
                    <h2 class="text-4xl md:text-5xl lg:text-6xl font-black text-white mb-8 leading-tight">
                        Intip Dashboard <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-500">Anda</span>
                    </h2>
                    <p class="text-white/60 text-lg md:text-xl mb-10 leading-relaxed">
                        Pantau performa belajar dengan visualisasi data yang menarik. Lihat progress, nilai rata-rata, dan riwayat aktivitas Anda di satu tempat.
                    </p>
                    
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-10 py-4 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold transition inline-block border border-white/10 text-lg">Lihat Dashboard Saya</a>
                    @else
                        <a href="{{ route('register') }}" class="px-10 py-4 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold transition inline-block shadow-lg shadow-indigo-900/40 text-lg">Daftar Sekarang</a>
                    @endauth
                </div>

                {{-- MOCKUP VISUALISASI --}}
                <div class="reveal relative group perspective-1000">
                    <div class="absolute -inset-2 bg-gradient-to-r from-blue-600 to-fuchsia-600 rounded-[2.5rem] blur-2xl opacity-20 group-hover:opacity-40 transition duration-1000"></div>
                    
                    {{-- Container Card --}}
                    <div class="relative bg-[#0f141e]/90 backdrop-blur-xl border border-white/10 rounded-[2rem] p-6 md:p-8 shadow-2xl overflow-hidden transform transition-transform duration-500 group-hover:scale-[1.02] group-hover:-rotate-1">
                        
                        {{-- Mockup Header --}}
                        <div class="flex items-center justify-between mb-10 border-b border-white/5 pb-6">
                            <div>
                                <div class="h-3 w-32 bg-white/10 rounded-full mb-3 animate-pulse"></div>
                                <div class="h-8 w-64 bg-white/10 rounded-lg animate-pulse"></div>
                            </div>
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-500 to-fuchsia-500 shadow-lg border-2 border-white/20"></div>
                        </div>

                        {{-- Mockup Stats Grid --}}
                        <div class="grid grid-cols-2 gap-6 mb-10">
                            {{-- Stat 1 --}}
                            <div class="bg-[#020617] p-6 rounded-2xl border border-white/5 relative overflow-hidden group/stat">
                                <div class="absolute inset-0 bg-fuchsia-500/5 opacity-0 group-hover/stat:opacity-100 transition"></div>
                                <p class="text-[10px] text-white/40 uppercase font-bold tracking-wider mb-2">Progress Belajar</p>
                                <p class="text-4xl md:text-5xl font-black text-white counter" data-target="72">0%</p>
                                <div class="w-full h-2 bg-white/10 mt-6 rounded-full overflow-hidden">
                                    <div id="demoBar1" class="h-full bg-fuchsia-500 w-0 transition-all duration-[2000ms] ease-out shadow-[0_0_10px_#d946ef]"></div>
                                </div>
                            </div>
                            {{-- Stat 2 --}}
                            <div class="bg-[#020617] p-6 rounded-2xl border border-white/5 relative overflow-hidden group/stat">
                                <div class="absolute inset-0 bg-cyan-500/5 opacity-0 group-hover/stat:opacity-100 transition"></div>
                                <p class="text-[10px] text-white/40 uppercase font-bold tracking-wider mb-2">Modul Selesai</p>
                                <p class="text-4xl md:text-5xl font-black text-white counter" data-target="8">0</p>
                                <div class="w-full h-2 bg-white/10 mt-6 rounded-full overflow-hidden">
                                    <div id="demoBar2" class="h-full bg-cyan-500 w-0 transition-all duration-[2000ms] ease-out shadow-[0_0_10px_#22d3ee]"></div>
                                </div>
                            </div>
                        </div>

                        {{-- Mockup Activity --}}
                        <div class="space-y-4">
                            <p class="text-xs font-bold text-white/30 uppercase mb-2 px-1">Aktivitas Terakhir</p>
                            <div class="flex items-center gap-4 p-4 rounded-2xl bg-white/[0.02] border border-white/5 hover:bg-white/5 transition">
                                <div class="w-10 h-10 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-sm font-bold border border-emerald-500/20">✓</div>
                                <div class="flex-1">
                                    <div class="h-3 w-40 bg-white/20 rounded-full mb-2"></div>
                                    <div class="h-2 w-20 bg-white/10 rounded-full"></div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ======================================================================
         7. FOOTER
         ====================================================================== --}}
    <footer class="border-t border-white/10 bg-[#020617] relative overflow-hidden pt-24 pb-12">
        <div class="absolute inset-0 bg-gradient-to-t from-indigo-900/20 to-transparent pointer-events-none"></div>
        
        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-12 mb-20">
                <div class="lg:col-span-2">
                    <h3 class="text-2xl font-black text-white flex items-center gap-3 mb-6">
                        <img src="{{ asset('images/logo.png') }}" alt="Utilwind Logo" class="h-8 w-auto">
                        Utilwind
                    </h3>
                    <p class="text-white/40 text-sm leading-relaxed max-w-sm">
                        Platform edukasi open-source untuk membantu developer Indonesia menguasai modern web development dengan standar industri.
                    </p>
                </div>
                
                <div>
                    <h4 class="font-bold text-white mb-6 uppercase tracking-widest text-xs">Produk</h4>
                    <ul class="space-y-4 text-sm text-white/60">
                        <li><a href="{{ route('courses.htmldancss') }}" class="hover:text-fuchsia-400 transition flex items-center gap-2"><span class="w-1 h-1 rounded-full bg-white/20"></span> Course</a></li>
                        <li><a href="{{ route('cheatsheet.index') }}" class="hover:text-fuchsia-400 transition flex items-center gap-2"><span class="w-1 h-1 rounded-full bg-white/20"></span> Cheat Sheet</a></li>
                        <li><a href="{{ route('gallery.index') }}" class="hover:text-fuchsia-400 transition flex items-center gap-2"><span class="w-1 h-1 rounded-full bg-white/20"></span> UI Gallery</a></li>
                        <li><a href="{{ route('sandbox') }}" class="hover:text-fuchsia-400 transition flex items-center gap-2"><span class="w-1 h-1 rounded-full bg-white/20"></span> Sandbox</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-bold text-white mb-6 uppercase tracking-widest text-xs">Akun</h4>
                    <ul class="space-y-4 text-sm text-white/60">
                        <li><a href="{{ route('login') }}" class="hover:text-fuchsia-400 transition">Masuk</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-fuchsia-400 transition">Daftar</a></li>
                        <li><a href="{{ route('dashboard') }}" class="hover:text-fuchsia-400 transition">Dashboard</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-white/5 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-white/30 font-mono">
                <p>&copy; {{ date('Y') }} Utilwind. Created with ❤️ for Education.</p>
                <div class="flex gap-6">
                    <a href="#" class="hover:text-white transition">Privacy</a>
                    <a href="#" class="hover:text-white transition">Terms</a>
                </div>
            </div>
        </div>
    </footer>

</div>

{{-- SCRIPT & STYLE --}}
<style>
    .animate-gradient-x { background-size: 200% 200%; animation: gradient-x 5s ease infinite; }
    @keyframes gradient-x { 0%, 100% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } }
    
    .animate-fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes scrollDot { 0%, 100% { transform: translateY(0); opacity: 1; } 50% { transform: translateY(6px); opacity: 0.5; } }
    .animate-scroll-dot { animation: scrollDot 1.5s infinite; }

    .reveal { opacity: 0; transform: translateY(40px); transition: all 1s cubic-bezier(0.16, 1, 0.3, 1); }
    .reveal.show { opacity: 1; transform: translateY(0); }

    .parallax { transition: transform 0.1s ease-out; }
    
    #animated-bg {
        background: radial-gradient(circle at 50% 50%, rgba(76, 29, 149, 0.1), transparent 50%),
                    radial-gradient(circle at 0% 0%, rgba(217, 70, 239, 0.05), transparent 40%);
        animation: bgMove 20s ease-in-out infinite alternate;
    }
    @keyframes bgMove { to { transform: scale(1.15); } }
    
    #gradient-wave {
        background: linear-gradient(120deg,rgba(217,70,239,.03),rgba(34,211,238,.03),rgba(168,85,247,.03));
        background-size: 400% 400%; animation: gradient-x 26s ease infinite;
    }
    
    #cursor-glow {
        position: fixed; width: 400px; height: 400px; border-radius: 50%;
        background: radial-gradient(circle, rgba(217,70,239,.08), transparent 70%);
        filter: blur(80px); pointer-events: none; z-index: 999; transform: translate(-50%,-50%); transition: width 0.3s, height 0.3s;
    }
    #noise-overlay {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 200 200'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.8' numOctaves='4'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
        mix-blend-mode: overlay;
    }
    .tilt-card { transform-style: preserve-3d; perspective: 1000px; }
</style>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function() {
    
    // 1. SCROLL PROGRESS
    $(window).scroll(function() {
        const top = $(this).scrollTop();
        const height = $(document).height() - $(window).height();
        const p = (top / height) * 100;
        $('#scrollProgress').css('width', p+'%');
    });

    // 2. PARALLAX
    $(window).scroll(function() {
        const scrolled = $(window).scrollTop();
        $('.parallax').each(function() {
            const speed = $(this).data('speed');
            $(this).css('transform', `translateY(${scrolled * speed * 0.1}px)`);
        });
    });

    // 3. REVEAL & COUNTER
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('show');
                
                if($(entry.target).find('#demoBar1').length) {
                    $('#demoBar1').css('width', '72%');
                    $('#demoBar2').css('width', '88%');
                }

                $(entry.target).find('.counter').each(function() {
                    const $this = $(this);
                    if ($this.data('animated')) return;
                    $this.data('animated', true);
                    const target = $this.data('target');
                    $({ val: 0 }).animate({ val: target }, {
                        duration: 2000, easing: 'swing',
                        step: function() { 
                            let txt = Math.ceil(this.val);
                            if($this.text().includes('%')) txt += '%';
                            $this.text(txt); 
                        }
                    });
                });
            }
        });
    }, { threshold: 0.1 });

    $('.reveal').each(function() { observer.observe(this); });

    // 4. MOUSE GLOW
    $(document).mousemove(function(e) { 
        $('#cursor-glow').css({ left: e.clientX, top: e.clientY }); 
    });

    // 5. TILT
    $('.tilt-card').mousemove(function(e) {
        const el = $(this); const offset = el.offset();
        const w = el.width(); const h = el.height();
        const mouseX = e.pageX - offset.left - w/2;
        const mouseY = e.pageY - offset.top - h/2;
        const rotateX = (mouseY / h) * -10;
        const rotateY = (mouseX / w) * 10;
        el.children().css('transform', `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale(1.02)`);
    });
    $('.tilt-card').mouseleave(function() { $(this).children().css('transform', 'perspective(1000px) rotateX(0) rotateY(0) scale(1)'); });

    // 6. STARS
    const c = document.getElementById('stars'); const x = c.getContext('2d');
    let s = []; 
    function initStars() { c.width = window.innerWidth; c.height = window.innerHeight; s=[]; for(let i=0;i<80;i++) s.push({x:Math.random()*c.width,y:Math.random()*c.height,r:Math.random()*1.5,v:Math.random()*0.2+0.1}); }
    function animateStars() { x.clearRect(0,0,c.width,c.height); x.fillStyle='rgba(255,255,255,0.4)'; s.forEach(t=>{ x.beginPath(); x.arc(t.x,t.y,t.r,0,6.28); x.fill(); t.y-=t.v; if(t.y<0) t.y=c.height; }); requestAnimationFrame(animateStars); }
    window.addEventListener('resize', initStars); initStars(); animateStars();
});
</script>
@endsection