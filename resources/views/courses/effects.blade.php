@extends('layouts.landing')
@section('title', 'Bab 3.4 · Efek Visual Masterclass')

@section('content')
<div id="courseRoot" class="relative h-screen bg-[#020617] text-white font-sans overflow-hidden flex flex-col selection:bg-purple-500/30">

    {{-- DYNAMIC BACKGROUND --}}
    <div class="fixed inset-0 -z-50 pointer-events-none">
        <div id="animated-bg" class="absolute inset-0 opacity-60"></div>
        <div id="gradient-wave" class="absolute inset-0"></div>
        <canvas id="stars" class="absolute inset-0 pointer-events-none"></canvas>
        <div id="noise-overlay" class="absolute inset-0 z-10 opacity-[0.035]"></div>
        <div id="cursor-glow"></div>
    </div>

    {{-- NAVBAR --}}
    <nav id="navbar" class="h-[74px] w-full bg-[#020617]/10 backdrop-blur-xl border-b border-white/5 shrink-0 z-50 flex items-center justify-between px-6 lg:px-8 transition-all duration-500 relative">
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-fuchsia-500 to-cyan-400 flex items-center justify-center font-extrabold text-black shadow-xl">TW</div>
            <span class="font-semibold tracking-wide text-lg">TailwindLearn</span>
        </div>
        <div class="hidden md:flex gap-10 text-sm font-medium">
            <a href="{{ route('landing') }}" class="nav-link opacity-70 hover:opacity-100 transition">Beranda</a>
            <span class="nav-link active cursor-default">Course</span> 
            <a href="{{ route('dashboard') }}" class="nav-link opacity-70 hover:opacity-100 transition">Dashboard</a>
            <a href="{{ route('sandbox') }}" class="nav-link opacity-70 hover:opacity-100 transition">Sandbox</a>
        </div>
        <div class="flex gap-3 items-center">
            <span class="text-white/70 text-sm hidden sm:block">{{ Auth::user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="cta-main px-6 py-2 rounded-xl bg-gradient-to-r from-fuchsia-500 to-purple-600 text-sm font-semibold shadow-xl hover:scale-105 transition">Keluar</button>
            </form>
        </div>
    </nav>

    <div class="flex flex-1 overflow-hidden relative z-20">

        @include('layouts.partials.course-sidebar')

        <main id="mainScroll" class="flex-1 h-full overflow-y-auto scroll-smooth relative bg-transparent custom-scrollbar scroll-padding-top-24">
            
            {{-- STICKY HEADER --}}

            <div id="stickyHeader" class="sticky top-0 z-30 w-full bg-[#020617]/80 backdrop-blur-2xl border-b border-white/5 px-8 py-4 flex items-center justify-between transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500/20 to-transparent border border-indigo-500/20 flex items-center justify-center font-bold text-xs text-indigo-400">3.3</div>
                    <div>
                        <h1 class="text-base font-bold text-white tracking-tight">Efek Visual</h1>
                        <p class="text-[10px] text-white/50 font-mono uppercase tracking-widest">Shadows, Filters & Animations</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="hidden sm:block w-32 h-1.5 bg-white/10 rounded-full overflow-hidden">
                        <div id="topProgressBar" class="h-full bg-gradient-to-r from-indigo-500 to-purple-500 w-0 transition-all duration-500 shadow-[0_0_10px_#6366f1]"></div>
                    </div>
                    <span id="progressLabelTop" class="text-indigo-400 font-bold text-xs">0%</span>
                </div>
            </div>

            <div class="p-6 lg:p-16 max-w-5xl mx-auto pb-40">
                <article class="space-y-40">
                    
                    {{-- INTRO --}}
                    <header class="space-y-8 animate-fade-in-up">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-300 text-[10px] font-bold uppercase tracking-widest">
                                <!-- <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 animate-pulse"></span> -->
                                Dasar Efek Styling
                            </div>
                        <h1 class="text-8xl lg:text-7xl font-black text-white leading-tight">
                            Mastering <br>
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-400 animate-gradient-x">Visual Effects</span>
                        </h1>
                        <p class="text-l text-white/60 max-w-3xl leading-relaxed border-l-4 border-purple-500/30 pl-6">
                            Efek visual bukan sekadar hiasan, melainkan alat komunikasi. Shadow menciptakan hierarki, Opacity menunjukkan status, dan Animasi memberikan nyawa. Modul ini akan mengajarkan Anda cara memanipulasi properti visual tersebut menggunakan utilitas Tailwind CSS yang powerful.
                        </p>
                    </header>

                    {{-- 1. BOX SHADOW --}}
                    <section id="box-shadow" class="lesson-section scroll-mt-2" data-lesson-id="60">
                        <div class="space-y-8">
                            <h2 class="text-3xl font-bold text-white flex items-center gap-3">
                                <span class="w-8 h-8 rounded-lg bg-purple-600 flex items-center justify-center text-sm shadow-lg shadow-purple-500/30">1</span>
                                Box Shadow & Color
                            </h2>
                            
                            <div class="prose prose-invert max-w-none text-white/80 space-y-6">
                                <p class="text-lg leading-relaxed">
                                    <strong>Box Shadow</strong> memberikan ilusi kedalaman (depth) pada desain 2D. Dalam Material Design, ini disebut "elevasi". Semakin besar shadow, semakin "tinggi" elemen tersebut melayang dari permukaan, yang biasanya menandakan bahwa elemen tersebut penting atau bisa diinteraksi (seperti tombol atau kartu).
                                </p>
                                <p>
                                    Tailwind menyediakan serangkaian utilitas shadow yang telah dikalibrasi secara profesional, mulai dari <code>shadow-sm</code> (halus) hingga <code>shadow-2xl</code> (sangat menonjol).
                                </p>

                                <div class="bg-purple-900/10 border border-purple-500/20 p-4 rounded-xl text-sm text-purple-200">
                                    <strong>💡 Pro Tip:</strong> Gunakan <code>shadow-inner</code> untuk formulir input agar terlihat "tenggelam" ke dalam, memberikan affordance bahwa area tersebut dapat diisi.
                                </div>
                                
                                {{-- Simulator Shadow --}}
                                <div class="bg-[#0f141e] border border-white/10 rounded-2xl p-8 mt-6 relative overflow-hidden shadow-2xl">
                                    <div class="absolute top-0 right-0 p-4 opacity-20 text-6xl font-black text-white pointer-events-none">SHADOW</div>
                                    <h3 class="text-sm font-bold text-white/50 uppercase tracking-widest mb-6">Interactive Lab: Shadow Depth</h3>
                                    
                                    <div class="grid md:grid-cols-2 gap-10 items-center">
                                        <div class="space-y-4">
                                            <p class="text-xs text-white/40 mb-2">Pilih intensitas bayangan:</p>
                                            <div class="grid grid-cols-2 gap-3">
                                                <button onclick="updateShadow('shadow-none')" class="p-3 bg-white/5 hover:bg-white/10 rounded-lg text-xs border border-white/10 text-left transition group"><span class="text-purple-400 group-hover:text-white">●</span> shadow-none</button>
                                                <button onclick="updateShadow('shadow-sm')" class="p-3 bg-white/5 hover:bg-white/10 rounded-lg text-xs border border-white/10 text-left transition group"><span class="text-purple-400 group-hover:text-white">●</span> shadow-sm</button>
                                                <button onclick="updateShadow('shadow-md')" class="p-3 bg-white/5 hover:bg-white/10 rounded-lg text-xs border border-white/10 text-left transition group"><span class="text-purple-400 group-hover:text-white">●</span> shadow-md</button>
                                                <button onclick="updateShadow('shadow-xl')" class="p-3 bg-white/5 hover:bg-white/10 rounded-lg text-xs border border-white/10 text-left transition group"><span class="text-purple-400 group-hover:text-white">●</span> shadow-xl</button>
                                                <button onclick="updateShadow('shadow-2xl')" class="p-3 bg-white/5 hover:bg-white/10 rounded-lg text-xs border border-white/10 text-left transition group"><span class="text-purple-400 group-hover:text-white">●</span> shadow-2xl</button>
                                                <button onclick="updateShadow('shadow-inner')" class="p-3 bg-white/5 hover:bg-white/10 rounded-lg text-xs border border-white/10 text-left transition group"><span class="text-purple-400 group-hover:text-white">●</span> shadow-inner</button>
                                            </div>
                                        </div>
                                        
                                        <div class="flex justify-center py-10 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] bg-cover rounded-xl border border-white/5">
                                            <div id="shadow-target" class="w-40 h-40 bg-white rounded-2xl flex items-center justify-center text-slate-800 font-bold transition-all duration-500 shadow-none transform hover:-translate-y-1">
                                                Box Element
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-8 border-t border-white/5 pt-6">
                                        <h4 class="text-sm font-bold text-white mb-4">Shadow Color (Glow Effect)</h4>
                                        <p class="text-xs text-white/60 mb-4">Tailwind memungkinkan kita mewarnai bayangan untuk efek neon atau glow. Ini sangat populer dalam desain "Cyberpunk" atau "Dark Mode".</p>
                                        <div class="flex flex-wrap gap-4">
                                            <button onclick="updateShadowColor('shadow-blue-500/50')" class="px-4 py-2 rounded-lg bg-blue-500/10 text-blue-300 text-xs border border-blue-500/30 hover:bg-blue-500/30 transition shadow-[0_0_10px_rgba(59,130,246,0.2)]">Blue Glow</button>
                                            <button onclick="updateShadowColor('shadow-purple-500/50')" class="px-4 py-2 rounded-lg bg-purple-500/10 text-purple-300 text-xs border border-purple-500/30 hover:bg-purple-500/30 transition shadow-[0_0_10px_rgba(168,85,247,0.2)]">Purple Glow</button>
                                            <button onclick="updateShadowColor('shadow-emerald-500/50')" class="px-4 py-2 rounded-lg bg-emerald-500/10 text-emerald-300 text-xs border border-emerald-500/30 hover:bg-emerald-500/30 transition shadow-[0_0_10px_rgba(16,185,129,0.2)]">Emerald Glow</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- 2. OPACITY --}}
                    <section id="opacity" class="lesson-section scroll-mt-32" data-lesson-id="61">
                        <div class="space-y-8">
                            <h2 class="text-3xl font-bold text-white flex items-center gap-3">
                                <span class="w-8 h-8 rounded-lg bg-purple-600 flex items-center justify-center text-sm shadow-lg shadow-purple-500/30">2</span>
                                Opacity
                            </h2>
                            
                            <div class="prose prose-invert max-w-none text-white/80 space-y-6">
                                <p>
                                    Utilitas <code>opacity-{amount}</code> mengatur transparansi seluruh elemen, termasuk konten dan anak elemennya (child elements). Ini berbeda dengan mengubah alpha channel pada warna background (RGBA), yang hanya mentransparankan latar belakang.
                                </p>
                                <ul class="list-disc pl-5 space-y-2 marker:text-purple-500">
                                    <li><code>opacity-0</code>: Elemen sepenuhnya tidak terlihat (tapi masih ada di DOM dan bisa di-klik jika tidak di-hidden).</li>
                                    <li><code>opacity-50</code>: Semi-transparan, sering digunakan untuk state "disabled".</li>
                                    <li><code>opacity-100</code>: Opasitas penuh (default).</li>
                                </ul>

                                <div class="bg-[#0f141e] border border-white/10 rounded-2xl p-8 mt-4">
                                    <div class="flex flex-col md:flex-row gap-8 items-center">
                                        <div class="w-full md:w-1/2 space-y-6">
                                            <div class="flex justify-between items-center">
                                                <label class="text-sm font-bold text-white">Atur Tingkat Opasitas</label>
                                                <span id="opacity-val" class="text-xs font-mono text-purple-400 bg-purple-900/30 px-2 py-1 rounded">opacity-100</span>
                                            </div>
                                            <input type="range" min="0" max="100" step="10" value="100" class="w-full h-2 bg-white/10 rounded-lg appearance-none cursor-pointer accent-purple-500 hover:accent-purple-400 transition-all" oninput="updateOpacity(this.value)">
                                            <p class="text-xs text-white/40">Geser slider untuk melihat efek transparansi pada elemen di sebelah kanan.</p>
                                        </div>
                                        <div class="w-full md:w-1/2 flex justify-center bg-[url('https://grainy-gradients.vercel.app/noise.svg')] bg-cover rounded-xl p-10 border border-white/5 relative overflow-hidden">
                                            <div class="absolute inset-0 bg-grid-white/[0.05]"></div>
                                            <div id="opacity-target" class="w-full h-32 bg-gradient-to-r from-purple-600 to-indigo-600 rounded-xl flex items-center justify-center shadow-2xl transition-opacity duration-200 border border-white/20">
                                                <div class="text-center">
                                                    <span class="font-bold text-white text-lg">Konten Utama</span>
                                                    <p class="text-[10px] text-white/70">Ikut transparan</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- 3. FILTERS --}}
                    <section id="filters" class="lesson-section scroll-mt-32" data-lesson-id="62">
                        <h2 class="text-3xl font-bold text-white mb-6 flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-purple-600 flex items-center justify-center text-sm shadow-lg shadow-purple-500/30">3</span>
                            Filters (Blur & Effects)
                        </h2>
                        
                        <div class="prose prose-invert max-w-none text-white/80 space-y-6 mb-8">
                            <p>
                                Tailwind membawa kekuatan Photoshop langsung ke browser. Anda bisa memanipulasi rendering elemen menggunakan CSS Filters. Ini sangat berguna untuk image processing ringan atau efek UI seperti <em>Glassmorphism</em> (menggunakan backdrop-blur).
                            </p>
                        </div>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            {{-- Simulator Image --}}
                            <div class="bg-[#0f141e] border border-white/10 rounded-2xl p-6 flex items-center justify-center relative group">
                                <div class="absolute inset-0 bg-gradient-to-b from-transparent to-black/50 rounded-2xl pointer-events-none"></div>
                                <div class="relative overflow-hidden rounded-xl shadow-2xl border border-white/10">
                                    <img id="filter-img" src="https://images.unsplash.com/photo-1550684848-fac1c5b4e853?q=80&w=1000&auto=format&fit=crop" alt="Demo" class="w-full max-w-sm rounded-xl transition-all duration-500 object-cover h-64">
                                    <div class="absolute top-2 right-2 bg-black/60 backdrop-blur px-3 py-1 rounded-full text-[10px] text-white/90 font-mono border border-white/10">Original Image</div>
                                </div>
                            </div>

                            {{-- Controls --}}
                            <div class="bg-[#0f141e] border border-white/10 rounded-2xl p-8 space-y-8">
                                <div>
                                    <label class="text-xs font-bold text-purple-400 uppercase tracking-widest block mb-3">Blur Effect (Keburaman)</label>
                                    <div class="flex flex-wrap gap-2">
                                        <button onclick="setFilter('blur', 'blur-none')" class="px-3 py-1.5 bg-white/5 border border-white/10 rounded text-xs hover:bg-purple-600 hover:border-purple-500 transition text-white">None</button>
                                        <button onclick="setFilter('blur', 'blur-sm')" class="px-3 py-1.5 bg-white/5 border border-white/10 rounded text-xs hover:bg-purple-600 hover:border-purple-500 transition text-white">sm</button>
                                        <button onclick="setFilter('blur', 'blur-md')" class="px-3 py-1.5 bg-white/5 border border-white/10 rounded text-xs hover:bg-purple-600 hover:border-purple-500 transition text-white">md</button>
                                        <button onclick="setFilter('blur', 'blur-xl')" class="px-3 py-1.5 bg-white/5 border border-white/10 rounded text-xs hover:bg-purple-600 hover:border-purple-500 transition text-white">xl</button>
                                    </div>
                                    <p class="text-[10px] text-white/40 mt-2">Digunakan untuk background overlay atau menyembunyikan konten sensitif.</p>
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-purple-400 uppercase tracking-widest block mb-3">Color Adjustments</label>
                                    <div class="grid grid-cols-2 gap-3">
                                        <button onclick="toggleFilter('grayscale')" class="px-4 py-3 bg-white/5 rounded-lg text-xs border border-white/10 hover:border-purple-500 hover:bg-white/10 transition text-left flex justify-between items-center group">
                                            Grayscale <span id="st-grayscale" class="text-white/20 group-hover:text-purple-400 transition">●</span>
                                        </button>
                                        <button onclick="toggleFilter('sepia')" class="px-4 py-3 bg-white/5 rounded-lg text-xs border border-white/10 hover:border-purple-500 hover:bg-white/10 transition text-left flex justify-between items-center group">
                                            Sepia <span id="st-sepia" class="text-white/20 group-hover:text-purple-400 transition">●</span>
                                        </button>
                                        <button onclick="toggleFilter('brightness-125')" class="px-4 py-3 bg-white/5 rounded-lg text-xs border border-white/10 hover:border-purple-500 hover:bg-white/10 transition text-left flex justify-between items-center group">
                                            Brightness + <span id="st-brightness-125" class="text-white/20 group-hover:text-purple-400 transition">●</span>
                                        </button>
                                        <button onclick="toggleFilter('contrast-150')" class="px-4 py-3 bg-white/5 rounded-lg text-xs border border-white/10 hover:border-purple-500 hover:bg-white/10 transition text-left flex justify-between items-center group">
                                            High Contrast <span id="st-contrast-150" class="text-white/20 group-hover:text-purple-400 transition">●</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- 4. TRANSITIONS & ANIMATIONS --}}
                    <section id="transitions" class="lesson-section scroll-mt-32" data-lesson-id="63">
                        <div class="space-y-8">
                            <h2 class="text-3xl font-bold text-white flex items-center gap-3">
                                <span class="w-8 h-8 rounded-lg bg-purple-600 flex items-center justify-center text-sm shadow-lg shadow-purple-500/30">4</span>
                                Transitions & Animation
                            </h2>
                            
                            <div class="prose prose-invert max-w-none text-white/80">
                                <p>
                                    Tanpa transisi, perubahan state (seperti hover) akan terasa kaku dan robotik. Tailwind menyediakan utilitas <code>transition</code>, <code>duration</code>, dan <code>ease</code> untuk membuat pergerakan UI terasa alami.
                                </p>
                            </div>

                            <div class="grid md:grid-cols-2 gap-8 mt-6">
                                <div class="bg-[#0f141e] border border-white/10 rounded-2xl p-6 relative overflow-hidden">
                                    <div class="absolute top-0 right-0 p-4 opacity-5 text-4xl font-black text-white pointer-events-none">TRANSITION</div>
                                    <h3 class="text-sm font-bold text-purple-400 mb-6 uppercase tracking-widest">Hover Transitions</h3>
                                    
                                    <div class="space-y-6">
                                        <div class="group flex items-center justify-between p-4 bg-white/5 rounded-xl border border-white/5 hover:border-blue-500/50 transition duration-300">
                                            <div class="w-10 h-10 bg-blue-500 rounded-lg transition-all duration-75 ease-linear group-hover:w-32 flex items-center justify-center overflow-hidden">
                                                <span class="text-[10px] text-white opacity-0 group-hover:opacity-100 transition delay-75 whitespace-nowrap">Instant (75ms)</span>
                                            </div>
                                            <span class="text-xs font-mono text-white/50">duration-75 ease-linear</span>
                                        </div>
                                        <div class="group flex items-center justify-between p-4 bg-white/5 rounded-xl border border-white/5 hover:border-purple-500/50 transition duration-300">
                                            <div class="w-10 h-10 bg-purple-500 rounded-lg transition-all duration-500 ease-out group-hover:w-48 flex items-center justify-center overflow-hidden">
                                                <span class="text-[10px] text-white opacity-0 group-hover:opacity-100 transition delay-100 whitespace-nowrap">Smooth (500ms)</span>
                                            </div>
                                            <span class="text-xs font-mono text-white/50">duration-500 ease-out</span>
                                        </div>
                                        <div class="group flex items-center justify-between p-4 bg-white/5 rounded-xl border border-white/5 hover:border-pink-500/50 transition duration-300">
                                            <div class="w-10 h-10 bg-pink-500 rounded-lg transition-all duration-1000 ease-in-out group-hover:w-full flex items-center justify-center overflow-hidden">
                                                <span class="text-[10px] text-white opacity-0 group-hover:opacity-100 transition delay-200 whitespace-nowrap">Cinematic (1000ms)</span>
                                            </div>
                                            <span class="text-xs font-mono text-white/50">duration-1000 in-out</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-[#0f141e] border border-white/10 rounded-2xl p-6 relative overflow-hidden">
                                    <div class="absolute top-0 right-0 p-4 opacity-5 text-4xl font-black text-white pointer-events-none">ANIMATE</div>
                                    <h3 class="text-sm font-bold text-pink-400 mb-6 uppercase tracking-widest">Infinite Animations</h3>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="p-6 bg-white/5 rounded-xl flex flex-col items-center justify-center gap-3 border border-white/5 hover:bg-white/10 transition">
                                            <div class="w-8 h-8 border-4 border-blue-400 border-t-transparent rounded-full animate-spin"></div>
                                            <code class="text-[10px] text-blue-300">animate-spin</code>
                                        </div>
                                        <div class="p-6 bg-white/5 rounded-xl flex flex-col items-center justify-center gap-3 border border-white/5 hover:bg-white/10 transition">
                                            <div class="w-3 h-3 bg-purple-400 rounded-full animate-ping"></div>
                                            <code class="text-[10px] text-purple-300">animate-ping</code>
                                        </div>
                                        <div class="p-6 bg-white/5 rounded-xl flex flex-col items-center justify-center gap-3 border border-white/5 hover:bg-white/10 transition">
                                            <div class="w-8 h-8 bg-pink-400 rounded-full animate-bounce"></div>
                                            <code class="text-[10px] text-pink-300">animate-bounce</code>
                                        </div>
                                        <div class="p-6 bg-white/5 rounded-xl flex flex-col items-center justify-center gap-3 border border-white/5 hover:bg-white/10 transition">
                                            <div class="w-8 h-8 bg-green-400 rounded animate-pulse"></div>
                                            <code class="text-[10px] text-green-300">animate-pulse</code>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- 5. TRANSFORMS --}}
                    <section id="transforms" class="lesson-section scroll-mt-32" data-lesson-id="64">
                        <div class="space-y-8">
                            <h2 class="text-3xl font-bold text-white flex items-center gap-3">
                                <span class="w-8 h-8 rounded-lg bg-purple-600 flex items-center justify-center text-sm shadow-lg shadow-purple-500/30">5</span>
                                Transforms (2D & 3D)
                            </h2>
                            
                            <div class="bg-[#0f141e] border border-white/10 rounded-2xl p-8 overflow-hidden relative shadow-2xl">
                                <div class="grid md:grid-cols-2 gap-12 items-center">
                                    <div class="space-y-8">
                                        <div>
                                            <div class="flex justify-between mb-2">
                                                <label class="text-xs font-bold text-white/50 uppercase">Scale (Ukuran)</label>
                                                <span id="val-scale" class="text-xs font-mono text-purple-400">100%</span>
                                            </div>
                                            <input type="range" min="50" max="150" step="10" value="100" class="w-full h-1 bg-white/10 rounded accent-purple-500 hover:accent-purple-400 cursor-pointer" oninput="updateTransform('scale', this.value)">
                                        </div>
                                        <div>
                                            <div class="flex justify-between mb-2">
                                                <label class="text-xs font-bold text-white/50 uppercase">Rotate (Putar)</label>
                                                <span id="val-rotate" class="text-xs font-mono text-purple-400">0deg</span>
                                            </div>
                                            <input type="range" min="0" max="360" step="15" value="0" class="w-full h-1 bg-white/10 rounded accent-purple-500 hover:accent-purple-400 cursor-pointer" oninput="updateTransform('rotate', this.value)">
                                        </div>
                                        <div>
                                            <div class="flex justify-between mb-2">
                                                <label class="text-xs font-bold text-white/50 uppercase">Translate X (Geser)</label>
                                                <span id="val-translate" class="text-xs font-mono text-purple-400">0px</span>
                                            </div>
                                            <input type="range" min="-100" max="100" step="10" value="0" class="w-full h-1 bg-white/10 rounded accent-purple-500 hover:accent-purple-400 cursor-pointer" oninput="updateTransform('translate', this.value)">
                                        </div>
                                        <div>
                                            <div class="flex justify-between mb-2">
                                                <label class="text-xs font-bold text-white/50 uppercase">Skew (Miring)</label>
                                                <span id="val-skew" class="text-xs font-mono text-purple-400">0deg</span>
                                            </div>
                                            <input type="range" min="-20" max="20" step="5" value="0" class="w-full h-1 bg-white/10 rounded accent-purple-500 hover:accent-purple-400 cursor-pointer" oninput="updateTransform('skew', this.value)">
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center justify-center bg-[url('https://grainy-gradients.vercel.app/noise.svg')] bg-cover rounded-xl border border-white/5 min-h-[350px] relative overflow-hidden">
                                        <div class="absolute inset-0 bg-grid-white/[0.05]"></div>
                                        <div id="transform-target" class="w-32 h-32 bg-gradient-to-br from-pink-500 to-orange-400 rounded-2xl shadow-2xl flex items-center justify-center font-bold text-white text-xl transition-transform duration-300 ease-out border border-white/20">
                                            BOX
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- 6. EXPERT CHALLENGE --}}
                    <section id="visual-challenge" class="lesson-section scroll-mt-32 pt-10 border-t border-white/10" data-lesson-id="65" data-manual="true">
                        <div class="relative rounded-[2rem] bg-[#0b0f19] border border-white/10 p-0 overflow-hidden shadow-2xl text-center group hover:border-purple-500/30 transition-all duration-500">
                            
                            {{-- Challenge Header --}}
                            <div class="p-10 border-b border-white/10 bg-gradient-to-r from-purple-900/30 via-[#0b0f19] to-transparent relative">
                                <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-10 mix-blend-overlay"></div>
                                <div class="relative z-10">
                                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-purple-600 text-white text-[10px] font-bold uppercase mb-4 tracking-widest shadow-lg shadow-purple-600/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                                        Final Mission
                                    </div>
                                    <h2 class="text-3xl md:text-4xl font-black text-white mb-4 leading-tight">Desain NFT Card Interaktif</h2>
                                    <p class="text-white/60 text-sm max-w-2xl mx-auto leading-relaxed">
                                        Klien Anda, sebuah marketplace NFT, membutuhkan kartu produk yang menarik. Terapkan spesifikasi berikut untuk menyelesaikan modul ini:
                                    </p>
                                    <div class="flex flex-wrap justify-center gap-4 mt-6">
                                        <span class="px-3 py-1 bg-white/5 rounded-full text-xs text-purple-300 border border-purple-500/20">1. Shadow: Deep (XL)</span>
                                        <span class="px-3 py-1 bg-white/5 rounded-full text-xs text-purple-300 border border-purple-500/20">2. Rounded: Smooth (2XL)</span>
                                        <span class="px-3 py-1 bg-white/5 rounded-full text-xs text-purple-300 border border-purple-500/20">3. Hover: Lift Up + Zoom</span>
                                    </div>
                                </div>
                            </div>

                            <div class="grid lg:grid-cols-12 min-h-[600px] text-left border-x border-b border-white/5 rounded-b-[2rem] bg-[#0f141e]">
                                
                                {{-- Controls --}}
                                <div class="lg:col-span-4 bg-[#0f141e] border-r border-white/5 p-6 flex flex-col h-full relative z-10">
                                    <div class="flex items-center justify-between mb-8 pb-4 border-b border-white/5">
                                        <h3 class="text-xs font-bold text-white/80 uppercase tracking-widest flex items-center gap-2">
                                            <svg class="w-4 h-4 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" /></svg>
                                            Configurator
                                        </h3>
                                    </div>

                                    <div class="flex-1 space-y-8 overflow-y-auto custom-scrollbar pr-2" id="challenge-controls">
                                        {{-- Injected via JS --}}
                                    </div>
                                    
                                    <div class="pt-6 mt-6 border-t border-white/5">
                                        <button id="checkBtn" onclick="checkSolution()" class="w-full py-4 rounded-xl bg-gradient-to-r from-purple-600 to-indigo-500 text-white font-bold text-lg shadow-lg hover:shadow-purple-500/25 transition-all transform hover:scale-[1.02] active:scale-[0.98] relative overflow-hidden group">
                                            <span class="relative z-10">Verifikasi Desain 🚀</span>
                                            <div class="absolute inset-0 bg-white/10 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                                        </button>
                                        <div id="feedback-area" class="mt-4 hidden p-4 rounded-lg text-center text-sm font-bold animate-pulse border"></div>
                                    </div>
                                </div>

                                {{-- Preview --}}
                                <div class="lg:col-span-8 bg-slate-100 text-slate-900 p-0 relative overflow-y-auto flex flex-col items-center justify-center">
                                    <div class="absolute top-4 right-4 text-[10px] font-mono text-slate-400 bg-white/90 backdrop-blur px-3 py-1.5 rounded-full border border-slate-200 shadow-sm z-50 flex items-center gap-2">
                                        <span class="relative flex h-2 w-2">
                                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                          <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                                        </span>
                                        LIVE PREVIEW (Hover Me)
                                    </div>

                                    {{-- Target Card --}}
                                    <div id="nft-card" class="w-72 bg-white p-4 transition-all duration-300 ease-out cursor-pointer group/card border border-slate-200 relative z-10">
                                        <div class="w-full aspect-square rounded-xl overflow-hidden mb-4 relative bg-slate-200">
                                            <img src="https://images.unsplash.com/photo-1620641788421-7a1c342ea42e?q=80&w=1000&auto=format&fit=crop" class="w-full h-full object-cover group-hover/card:scale-110 transition-transform duration-500" alt="NFT">
                                            <div class="absolute top-2 right-2 bg-black/50 backdrop-blur text-white text-[10px] px-2 py-1 rounded-md border border-white/10">3h 20m</div>
                                        </div>
                                        <div class="flex justify-between items-start mb-2">
                                            <div>
                                                <h3 class="font-bold text-slate-800 text-lg leading-tight">Cosmic Cube</h3>
                                                <p class="text-slate-500 text-xs mt-1">@GalaxyArt</p>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-[10px] text-slate-400 uppercase font-bold">Price</p>
                                                <p class="text-purple-600 font-bold text-sm">0.5 ETH</p>
                                            </div>
                                        </div>
                                        <button class="w-full py-2 mt-2 rounded-lg bg-slate-900 text-white text-xs font-bold hover:bg-purple-600 transition-colors">Place Bid</button>
                                    </div>
                                    
                                    {{-- Background Decor --}}
                                    <div class="absolute inset-0 opacity-5 pointer-events-none" style="background-image: radial-gradient(#64748b 1px, transparent 1px); background-size: 24px 24px;"></div>

                                </div>
                            </div>
                        </div>
                    </section>

                </article>

                {{-- FOOTER --}}
                <div class="mt-32 pt-10 border-t border-white/10 flex justify-between items-center animate-fade-in-up">
                    <a href="#" class="group flex items-center gap-4 text-slate-400 hover:text-white transition">
                        <div class="w-12 h-12 rounded-full border border-white/10 flex items-center justify-center group-hover:border-purple-500/50 group-hover:bg-purple-500/10 transition-all duration-300">
                            <span class="text-lg group-hover:-translate-x-1 transition-transform">←</span>
                        </div>
                        <div class="text-left">
                            <div class="text-[10px] uppercase tracking-widest opacity-50 mb-1">Sebelumnya</div>
                            <div class="font-bold text-sm">3.3 Tata Letak</div>
                        </div>
                    </a>

                    <div id="nextChapterBtn" class="group flex items-center gap-4 text-right text-slate-500 cursor-not-allowed opacity-70">
                        <div class="text-right">
                            <div class="text-[10px] uppercase tracking-widest opacity-50 mb-1">Terkunci</div>
                            <div class="font-bold text-sm">Bab 4: Komponen</div>
                        </div>
                        <div class="w-12 h-12 rounded-full border border-white/10 flex items-center justify-center bg-white/5">🔒</div>
                    </div>
                </div>

                <div class="mt-20 text-center text-white/20 text-xs font-mono">
                    &copy; {{ date('Y') }} Flowwind Learn. Materi dilindungi hak cipta.
                </div>
            </div>
        </main>
    </div>
</div>

<style>
    /* UTILS & ANIMATION (Indigo Theme) */
    .nav-link.active { color: #818cf8; position: relative; }
    .nav-link.active::after { content: ''; position: absolute; left: 0; bottom: -6px; width: 100%; height: 2px; background: linear-gradient(to right,#818cf8,#a78bfa); box-shadow: 0 0 12px rgba(129,140,248,.8); border-radius: 2px; }
    .custom-scrollbar::-webkit-scrollbar { width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
    
    @keyframes shake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-5px); } 75% { transform: translateX(5px); } }
    .shake { animation: shake 0.4s ease-in-out; }

    #animated-bg{ background: radial-gradient(600px circle at 20% 20%, rgba(99,102,241,.15), transparent 40%), radial-gradient(700px circle at 80% 30%, rgba(124,58,237,.15), transparent 40%), radial-gradient(800px circle at 50% 80%, rgba(167,139,250,.15), transparent 40%); animation:bgMove 20s ease-in-out infinite alternate; }
    @keyframes bgMove{to{transform:scale(1.15)}}
    
    .nav-item { display: flex; width: 100%; text-align: left; align-items: center; gap: 12px; padding: 10px 14px; font-size: 0.85rem; color: rgba(255,255,255,0.5); border-radius: 8px; transition: all 0.2s; position: relative; }
    .nav-item:hover { color: #fff; background: rgba(255,255,255,0.03); }
    .nav-item.active { color: #818cf8; background: rgba(129, 140, 248, 0.05); font-weight: 600; }
    .dot { width: 6px; height: 6px; border-radius: 50%; background: #334155; transition: all 0.3s; }
    .nav-item.active .dot { background: #818cf8; box-shadow: 0 0 8px #818cf8; transform: scale(1.2); }

    /* SIDEBAR COMPATIBILITY */
    .sb-group.open .accordion-content { max-height: 1000px; opacity: 1; }
    .sb-group:not(.open) .accordion-content { max-height: 0; opacity: 0; overflow: hidden; }
    .sb-group.open svg { transform: rotate(180deg); }
    .nav-item { display: flex; width: 100%; text-align: left; align-items: center; gap: 12px; padding: 10px 14px; font-size: 0.85rem; color: rgba(255,255,255,0.5); border-radius: 8px; transition: all 0.2s; position: relative; }
    .nav-item:hover { color: #fff; background: rgba(255,255,255,0.03); }
    .nav-item.active { color: #22d3ee; background: rgba(34,211,238,0.05); font-weight: 600; }
    .dot { width: 6px; height: 6px; border-radius: 50%; background: #334155; transition: all 0.3s; }
    .nav-item.active .dot { background: #22d3ee; box-shadow: 0 0 8px #22d3ee; transform: scale(1.2); }
</style>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    /* --- CONFIGURATION --- */
    window.SUBBAB_LESSON_IDS = [60, 61, 62, 63, 64, 65]; 
    window.INIT_COMPLETED_LESSONS = {!! json_encode($completedLessonIds ?? []) !!};
    let completedLessons = new Set(window.INIT_COMPLETED_LESSONS);
    const ACTIVITY_ID = {{ $activityId ?? 13 }}; // Visual Effects Activity
    let activityCompleted = {!! ($activityCompleted ?? false) ? 'true' : 'false' !!};

    document.addEventListener('DOMContentLoaded', () => {
        updateProgressUI();
        initLessonObserver();
        renderChallengeControls();
        updateCardPreview();
        initSidebarScroll();
        
        if (activityCompleted) {
            disableExpertUI();
        }
    });

    /* --- SIMULATORS --- */
    function updateShadow(cls) {
        document.getElementById('shadow-target').className = `w-40 h-40 bg-white rounded-2xl flex items-center justify-center text-slate-800 font-bold transition-all duration-500 ${cls}`;
    }
    
    function updateShadowColor(cls) {
        const el = document.getElementById('shadow-target');
        // Clear color shadows
        el.className = el.className.replace(/\bshadow-(blue|purple|green|emerald)-500\/50\b/g, '');
        el.classList.add(cls);
        if(!el.classList.value.includes('shadow-')) el.classList.add('shadow-xl'); // ensure base shadow
    }
    
    function updateOpacity(val) {
        document.getElementById('opacity-target').style.opacity = val / 100;
        document.getElementById('opacity-val').innerText = `opacity-${val}`;
    }
    
    let activeFilters = new Set();
    function setFilter(type, cls) {
        const img = document.getElementById('filter-img');
        img.className = img.className.replace(/\bblur-(none|sm|md|xl)\b/g, '');
        if(cls !== 'blur-none') img.classList.add(cls);
    }
    function toggleFilter(cls) {
        const img = document.getElementById('filter-img');
        const indicator = document.getElementById('st-' + cls);
        if (activeFilters.has(cls)) {
            activeFilters.delete(cls);
            img.classList.remove(cls);
            indicator.classList.remove('text-purple-400');
            indicator.classList.add('text-white/20');
        } else {
            activeFilters.add(cls);
            img.classList.add(cls);
            indicator.classList.add('text-purple-400');
            indicator.classList.remove('text-white/20');
        }
    }

    let transformState = { scale: 100, rotate: 0, translate: 0, skew: 0 };
    function updateTransform(prop, val) {
        transformState[prop] = val;
        // Update Label
        document.getElementById(`val-${prop}`).innerText = val + (prop === 'translate' ? 'px' : prop === 'scale' ? '%' : 'deg');
        // Apply Style
        const el = document.getElementById('transform-target');
        el.style.transform = `scale(${transformState.scale/100}) rotate(${transformState.rotate}deg) translateX(${transformState.translate}px) skewY(${transformState.skew}deg)`;
    }

    /* --- EXPERT CHALLENGE LOGIC --- */
    const challengeData = {
        shadow: {
            label: "1. Shadow Depth",
            options: [
                { val: 'shadow-none', label: 'Flat' },
                { val: 'shadow-xl', label: 'Deep (Correct)', correct: true },
                { val: 'shadow-inner', label: 'Inner' }
            ]
        },
        rounded: {
            label: "2. Corner Radius",
            options: [
                { val: 'rounded-none', label: 'Square' },
                { val: 'rounded-2xl', label: 'Smooth (Correct)', correct: true },
                { val: 'rounded-full', label: 'Pill' }
            ]
        },
        hover: {
            label: "3. Hover Effect",
            options: [
                { val: 'hover:opacity-50', label: 'Fade' },
                { val: 'hover:-translate-y-2 hover:scale-105', label: 'Lift & Zoom (Correct)', correct: true },
                { val: 'hover:rotate-12', label: 'Spin' }
            ]
        }
    };

    let userChoices = { shadow: 'shadow-sm', rounded: 'rounded-md', hover: '' };

    function renderChallengeControls() {
        const container = $('#challenge-controls');
        container.empty();
        
        Object.entries(challengeData).forEach(([key, data]) => {
            let html = `<div class="mb-6 last:mb-0">
                <h4 class="text-purple-400 font-bold mb-3 uppercase text-[10px] tracking-widest flex items-center gap-2">
                    <span class="w-1.5 h-1.5 bg-purple-500 rounded-full"></span>
                    ${data.label}
                </h4>
                <div class="grid grid-cols-1 gap-2">`;
            data.options.forEach(opt => {
                const isActive = userChoices[key] === opt.val;
                const activeClass = isActive ? 'bg-purple-600 text-white border-purple-500 shadow-lg shadow-purple-900/30' : 'bg-white/5 text-white/60 border-white/10 hover:bg-white/10 hover:text-white';
                html += `<button onclick="setChoice('${key}','${opt.val}')" class="${activeClass} w-full text-left px-4 py-3 rounded-xl text-xs font-mono border transition-all duration-200 active:scale-[0.98] flex justify-between items-center group">
                    <span>${opt.label}</span>
                    ${isActive ? '<span class="text-white animate-pulse">●</span>' : ''}
                </button>`;
            });
            html += `</div></div>`;
            container.append(html);
        });
    }

    window.setChoice = function(key, val) {
        if(activityCompleted) return;
        userChoices[key] = val;
        renderChallengeControls();
        updateCardPreview();
    }

    function updateCardPreview() {
        const card = document.getElementById('nft-card');
        // Reset base classes + dynamic
        card.className = `w-72 bg-white p-4 transition-all duration-300 ease-out cursor-pointer group/card border border-slate-200 relative z-10 ${userChoices.shadow} ${userChoices.rounded} ${userChoices.hover}`;
    }

    window.checkSolution = async function() {
        if(activityCompleted) return;
        let isCorrect = true;
        let errors = [];

        Object.entries(challengeData).forEach(([key, data]) => {
            const correctVal = data.options.find(o => o.correct).val;
            if(userChoices[key] !== correctVal) {
                isCorrect = false;
                errors.push(data.label);
            }
        });

        const fb = $('#feedback-area');
        fb.removeClass('hidden bg-red-500/10 text-red-400 bg-emerald-500/10 text-emerald-400 border-red-500/20 border-emerald-500/20 border-purple-500/20 text-purple-400 animate-pulse');
        const btn = document.getElementById('checkBtn');

        if(isCorrect) {
            fb.addClass('bg-emerald-500/10 text-emerald-400 border-emerald-500/20').html(`
                <div class="text-4xl mb-2 animate-bounce">🎉</div>
                <div class="font-bold">Sempurna!</div>
                <div class="text-xs opacity-80 mt-1">Kartu terlihat modern dan interaktif.</div>
            `);
            await finishChapter();
        } else {
            btn.classList.add('shake'); setTimeout(() => btn.classList.remove('shake'), 500);
            fb.addClass('bg-red-500/10 text-red-300 border-red-500/30').html(`
                <div class="text-4xl mb-2">🧐</div>
                <div class="font-bold mb-1">Cek Lagi</div>
                <div class="text-xs">Parameter belum tepat: ${errors.join(', ')}</div>
            `);
        }
        fb.removeClass('hidden');
    }

    async function finishChapter() {
        const btn = document.getElementById('checkBtn');
        btn.innerHTML = "Menyimpan... 💾";
        btn.classList.add('pointer-events-none', 'opacity-80');
        try {
            await fetch('/activity/complete', { method: 'POST', headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json'}, body: JSON.stringify({ activity_id: ACTIVITY_ID, score: 100 }) });
            await saveLessonToDB(65); // Last lesson ID
            completedLessons.add(65);
            activityCompleted = true;
            updateProgressUI();
            disableExpertUI();
        } catch(e) { console.error(e); btn.innerHTML = "Gagal. Coba lagi."; }
    }

    function disableExpertUI() {
        $('#challenge-controls').addClass('pointer-events-none opacity-50 grayscale');
        const btn = document.getElementById('checkBtn');
        if(btn) {
            btn.innerHTML = "Misi Selesai ✔";
            btn.className = "w-full py-4 rounded-xl bg-emerald-600/90 text-white font-bold text-lg shadow-lg shadow-emerald-900/30 cursor-default";
            btn.onclick = null;
        }
        $('#feedback-area').removeClass('hidden animate-pulse bg-red-500/10 border-red-500/30 text-red-300').addClass('bg-emerald-500/10 text-emerald-400 border-emerald-500/20').html('<strong>Hebat!</strong> Anda telah menguasai efek visual.');
        unlockNext();
    }

    /* --- CORE SYSTEM UTILS --- */
    function updateProgressUI() {
        const total = window.SUBBAB_LESSON_IDS.length;
        const done = window.SUBBAB_LESSON_IDS.filter(id => completedLessons.has(id)).length;
        let percent = Math.round((done / total) * 100);
        // Logic: 90% if lessons done but activity pending, 100% if everything done
        if (done === total && !activityCompleted) percent = 90;
        else if (done === total && activityCompleted) percent = 100;
        
        ['topProgressBar', 'sideProgressBar'].forEach(id => { const el = document.getElementById(id); if(el) el.style.width = percent + '%'; });
        ['progressLabelTop', 'progressLabelSide'].forEach(id => { const el = document.getElementById(id); if(el) el.innerText = percent + '%'; });
        if (percent === 100) unlockNext();
    }

    function initLessonObserver() {
        const obs = new IntersectionObserver(async entries => {
            for (const entry of entries) {
                if (entry.isIntersecting) {
                    const id = Number(entry.target.dataset.lessonId);
                    const isManual = entry.target.getAttribute('data-manual') === 'true';
                    if (id && !isManual && !completedLessons.has(id)) {
                        try { await saveLessonToDB(id); completedLessons.add(id); updateProgressUI(); } catch (e) {}
                    }
                }
            }
        }, { threshold: 0.5, root: document.getElementById('mainScroll') });
        document.querySelectorAll('.lesson-section').forEach(s => obs.observe(s));
    }

    async function saveLessonToDB(id) {
        const form = new FormData(); form.append('lesson_id', id);
        await fetch('/lesson/complete', { method: 'POST', headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}, body: form });
    }

    function unlockNext() {
        const btn = document.getElementById('nextChapterBtn');
        if(btn && activityCompleted) {
            btn.className = "group flex items-center gap-4 text-right text-purple-400 hover:text-purple-300 transition cursor-pointer opacity-100 animate-fade-in-up";
            btn.innerHTML = `<div class="text-right"><div class="text-[10px] uppercase tracking-widest opacity-70 mb-1">Modul Berikutnya</div><div class="font-bold text-sm">Bab 4: Komponen</div></div><div class="w-12 h-12 rounded-full border border-purple-500/30 bg-purple-500/10 flex items-center justify-center shadow-[0_0_15px_rgba(168,85,247,0.3)]">→</div>`;
            // Redirect logic if needed
            // btn.onclick = () => window.location.href = '/courses/next';
        }
    }

    function initSidebarScroll() {
        const main = document.getElementById('mainScroll');
        const links = document.querySelectorAll('.accordion-content .nav-item');
        main.addEventListener('scroll', () => {
            let current = '';
            document.querySelectorAll('.lesson-section').forEach(sec => { if (main.scrollTop >= sec.offsetTop - 250) current = '#' + sec.id; });
            links.forEach(link => { link.classList.remove('active'); if(link.getAttribute('data-target') === current) link.classList.add('active'); });
        });
        links.forEach(link => {
            link.addEventListener('click', () => {
                const target = document.querySelector(link.getAttribute('data-target'));
                if(target) main.scrollTo({ top: target.offsetTop - 120, behavior: 'smooth' });
            });
        });
    }

    function toggleAccordion(id) {
        const el = document.getElementById(id);
        const group = el.closest('.accordion-group');
        const arrow = document.getElementById(id.replace('content', 'arrow'));
        if(el.style.maxHeight){ el.style.maxHeight=null; group.classList.remove('open'); if(arrow) arrow.style.transform='rotate(0deg)'; }
        else{ el.style.maxHeight=el.scrollHeight+"px"; group.classList.add('open'); if(arrow) arrow.style.transform='rotate(180deg)'; }
    }
</script>
@endsection