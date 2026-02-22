@extends('layouts.landing')
@section('title', 'Bab 3.4 · Efek Visual Masterclass')

@section('content')
<div id="courseRoot" class="relative h-screen bg-[#020617] text-white font-sans overflow-hidden flex flex-col selection:bg-purple-500/30 pt-20">

    {{-- DYNAMIC BACKGROUND SYSTEM --}}
    <div class="fixed inset-0 -z-50 pointer-events-none">
        <div id="animated-bg" class="absolute inset-0 opacity-60"></div>
        <div id="gradient-wave" class="absolute inset-0"></div>
        <canvas id="stars" class="absolute inset-0 pointer-events-none"></canvas>
        <div id="noise-overlay" class="absolute inset-0 z-10 opacity-[0.035]"></div>
        <div id="cursor-glow"></div>
    </div>

    @include('layouts.partials.navbar')

    <div class="flex flex-1 overflow-hidden relative z-20">

        {{-- Sidebar --}}
        @include('layouts.partials.course-sidebar')

        {{-- Main Content --}}
        <main id="mainScroll" class="flex-1 h-full overflow-y-auto scroll-smooth relative bg-transparent custom-scrollbar scroll-padding-top-24">
            
            {{-- STICKY HEADER --}}
            <div id="stickyHeader" class="sticky top-0 z-30 w-full bg-[#020617]/80 backdrop-blur-2xl border-b border-white/5 px-8 py-4 flex items-center justify-between transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500/20 to-transparent border border-indigo-500/20 flex items-center justify-center font-bold text-xs text-indigo-400">3.4</div>
                    <div>
                        <h1 class="text-sm font-bold text-white">Efek Visual</h1>
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
                
                {{-- HERO & OBJECTIVES --}}
                <div class="mb-24 animate-fade-in-up">
                    
                    
                    <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Tujuan Pembelajaran
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <div class="bg-[#1e1e1e] border border-white/5 p-5 rounded-xl flex items-start gap-4 hover:border-purple-500/30 transition group h-full">
                            <div class="w-8 h-8 rounded bg-purple-500/10 text-purple-400 flex items-center justify-center shrink-0 font-bold text-xs">1</div>
                            <div><h4 class="text-sm font-bold text-white mb-1">Memahami Kedalaman</h4><p class="text-[11px] text-white/50 leading-relaxed">Membangun hierarki visual 3D pada layar 2D menggunakan manipulasi bayangan (Shadow).</p></div>
                        </div>
                        <div class="bg-[#1e1e1e] border border-white/5 p-5 rounded-xl flex items-start gap-4 hover:border-pink-500/30 transition group h-full">
                            <div class="w-8 h-8 rounded bg-pink-500/10 text-pink-400 flex items-center justify-center shrink-0 font-bold text-xs">2</div>
                            <div><h4 class="text-sm font-bold text-white mb-1">Memahami Fisika UI</h4><p class="text-[11px] text-white/50 leading-relaxed">Menerapkan prinsip animasi dan transisi agar interaksi terasa natural dan tidak robotik.</p></div>
                        </div>
                        <div class="bg-[#1e1e1e] border border-white/5 p-5 rounded-xl flex items-start gap-4 hover:border-indigo-500/30 transition group h-full">
                            <div class="w-8 h-8 rounded bg-indigo-500/10 text-indigo-400 flex items-center justify-center shrink-0 font-bold text-xs">3</div>
                            <div><h4 class="text-sm font-bold text-white mb-1">Memahami Rendering</h4><p class="text-[11px] text-white/50 leading-relaxed">Memanipulasi piksel menggunakan Filter dan Transform untuk performa GPU maksimal.</p></div>
                        </div>
                    </div>
                </div>

                <article class="space-y-40">
                    
                    {{-- LESSON 60: BOX SHADOW --}}
                    <section id="box-shadow" class="lesson-section scroll-mt-32" data-lesson-id="60">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-purple-500 pl-6">
                                <span class="text-purple-400 font-mono text-xs uppercase tracking-widest">Lesson 3.4.1</span>
                                <h2 class="text-3xl lg:text-4xl font-black text-white leading-[1.1]">Box Shadow & Color</h2>
                            </div>

                            <div class="grid lg:grid-cols-2 gap-12">
                                <div class="space-y-6 text-white/80 leading-relaxed text-sm md:text-base">
                                    <p>
                                        Dalam desain antarmuka, <strong>Box Shadow</strong> bukan sekadar hiasan, melainkan indikator hierarki. Konsep ini, yang dipopulerkan oleh Material Design, menggunakan bayangan untuk mensimulasikan "elevasi" (ketinggian) pada sumbu Z. Semakin besar dan kabur bayangannya (<code>shadow-xl</code> atau <code>shadow-2xl</code>), semakin tinggi elemen tersebut tampak melayang dari permukaan, menandakan bahwa elemen tersebut penting atau dapat diinteraksi (seperti modal atau tombol mengambang).
                                    </p>
                                    <p>
                                        Tailwind CSS memperluas konsep ini dengan fitur modern: <strong>Colored Shadows</strong>. Secara default, bayangan berwarna hitam transparan. Namun, dalam desain *Dark Mode* atau *Cyberpunk*, bayangan hitam sering kali tidak terlihat atau membuat desain tampak kotor. Menggunakan warna yang senada dengan elemen (misalnya <code>shadow-purple-500/50</code>) menciptakan efek "Glow" atau neon yang memukau.
                                    </p>
                                    <p>
                                        Sebaliknya, utilitas <code>shadow-inner</code> memberikan ilusi bahwa elemen tersebut ditekan ke dalam permukaan (embossed). Teknik ini sangat efektif untuk formulir input, panel indikator, atau status tombol yang sedang aktif (*active state*), memberikan umpan balik visual yang realistis kepada pengguna.
                                    </p>
                                </div>

                                {{-- SIMULATOR 60 --}}
                                <div class="bg-[#0f141e] border border-white/10 rounded-2xl p-6 shadow-2xl relative flex flex-col justify-between">
                                    <div class="absolute top-4 right-4 text-[10px] font-mono text-white/30 uppercase tracking-widest">Simulasi: Shadow Physics</div>
                                    
                                    <div class="flex-1 flex items-center justify-center min-h-[200px] bg-[url('https://grainy-gradients.vercel.app/noise.svg')] rounded-xl mb-6 border border-white/5">
                                        <div id="sim60-target" class="w-32 h-32 bg-white rounded-2xl flex items-center justify-center text-slate-900 font-bold transition-all duration-500 shadow-none">
                                            BOX
                                        </div>
                                    </div>

                                    <div class="space-y-5">
                                        {{-- Part 1: Elevation --}}
                                        <div>
                                            <label class="text-[10px] text-purple-400 font-bold uppercase mb-2 block flex justify-between">
                                                <span>1. Elevation (Ketinggian)</span>
                                                <span class="text-white/30">Z-Axis</span>
                                            </label>
                                            <div class="flex gap-2">
                                                <button onclick="updateSim60('elevation', 'shadow-none')" class="flex-1 py-2 bg-white/5 rounded text-[10px] hover:bg-white/10 border border-white/10 transition focus:ring-2 focus:ring-purple-500">Flat</button>
                                                <button onclick="updateSim60('elevation', 'shadow-md')" class="flex-1 py-2 bg-white/5 rounded text-[10px] hover:bg-white/10 border border-white/10 transition focus:ring-2 focus:ring-purple-500">Medium</button>
                                                <button onclick="updateSim60('elevation', 'shadow-2xl')" class="flex-1 py-2 bg-white/5 rounded text-[10px] hover:bg-white/10 border border-white/10 transition focus:ring-2 focus:ring-purple-500">High (2XL)</button>
                                            </div>
                                        </div>
                                        
                                        {{-- Part 2: Color --}}
                                        <div>
                                            <label class="text-[10px] text-blue-400 font-bold uppercase mb-2 block flex justify-between">
                                                <span>2. Atmosphere (Warna)</span>
                                                <span class="text-white/30">Glow Effect</span>
                                            </label>
                                            <div class="flex gap-2">
                                                <button onclick="updateSim60('color', 'shadow-black/50')" class="flex-1 py-2 bg-white/5 rounded text-[10px] hover:bg-white/10 border border-white/10 transition focus:ring-2 focus:ring-blue-500">Realistic</button>
                                                <button onclick="updateSim60('color', 'shadow-purple-500/50')" class="flex-1 py-2 bg-purple-500/10 text-purple-300 rounded text-[10px] hover:bg-purple-500/20 border border-purple-500/30 transition focus:ring-2 focus:ring-purple-500">Neon Purple</button>
                                                <button onclick="updateSim60('color', 'shadow-cyan-400/50')" class="flex-1 py-2 bg-cyan-500/10 text-cyan-300 rounded text-[10px] hover:bg-cyan-500/20 border border-cyan-500/30 transition focus:ring-2 focus:ring-cyan-500">Neon Cyan</button>
                                            </div>
                                        </div>

                                        {{-- Part 3: Inset --}}
                                        <div>
                                            <label class="text-[10px] text-pink-400 font-bold uppercase mb-2 block flex justify-between">
                                                <span>3. Direction (Arah)</span>
                                                <span class="text-white/30">Depth</span>
                                            </label>
                                            <div class="flex gap-2">
                                                <button onclick="updateSim60('inset', false)" class="flex-1 py-2 bg-white/5 rounded text-[10px] hover:bg-white/10 border border-white/10 transition focus:ring-2 focus:ring-pink-500">Outer (Float)</button>
                                                <button onclick="updateSim60('inset', true)" class="flex-1 py-2 bg-white/5 rounded text-[10px] hover:bg-white/10 border border-white/10 transition focus:ring-2 focus:ring-pink-500">Inner (Sink)</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 61: OPACITY --}}
                    <section id="opacity" class="lesson-section scroll-mt-32" data-lesson-id="61">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-purple-500 pl-6">
                                <span class="text-purple-400 font-mono text-xs uppercase tracking-widest">Lesson 3.4.2</span>
                                <h2 class="text-3xl lg:text-4xl font-black text-white leading-[1.1]">Opacity & Visibility</h2>
                            </div>

                            <div class="grid lg:grid-cols-2 gap-12">
                                <div class="space-y-6 text-white/80 leading-relaxed text-sm md:text-base">
                                    <p>
                                        Utilitas <strong>Opacity</strong> mengontrol transparansi elemen. Namun, ada perbedaan krusial antara `opacity-{n}` dan `bg-opacity` (atau alpha channel pada warna).
                                        <br>• <strong>Element Opacity (`opacity-50`):</strong> Mempengaruhi seluruh elemen, termasuk teks, border, dan gambar di dalamnya. Ini bisa menurunkan keterbacaan (aksesibilitas) jika tidak hati-hati.
                                        <br>• <strong>Background Alpha (`bg-blue-500/50`):</strong> Hanya mentransparankan latar belakang, sementara teks di atasnya tetap 100% solid. Ini adalah metode yang disarankan untuk membuat overlay teks yang terbaca.
                                    </p>
                                    <p>
                                        Opacity juga memainkan peran kunci dalam <strong>Stacking Context</strong>. Elemen dengan opacity kurang dari 1 akan membentuk konteks tumpukan baru, yang bisa mempengaruhi bagaimana `z-index` bekerja.
                                    </p>
                                    <p>
                                        Penggunaan paling umum adalah untuk indikasi status: `opacity-100` untuk aktif, `opacity-50` untuk non-aktif (disabled), dan `hover:opacity-80` untuk memberikan umpan balik interaksi pada tombol.
                                    </p>
                                </div>

                                {{-- SIMULATOR 61 --}}
                                <div class="bg-[#0f141e] border border-white/10 rounded-2xl p-6 shadow-2xl relative flex flex-col justify-between">
                                    <div class="absolute top-4 right-4 text-[10px] font-mono text-white/30 uppercase tracking-widest">Simulasi: Visibility</div>
                                    
                                    <div class="flex-1 flex items-center justify-center min-h-[200px] bg-[url('https://images.unsplash.com/photo-1550684848-fac1c5b4e853?q=80&w=1000&auto=format&fit=crop')] bg-cover rounded-xl mb-6 border border-white/5 relative group">
                                        <div id="sim61-target" class="w-48 h-24 bg-purple-600 rounded-xl flex flex-col items-center justify-center text-white transition-all duration-300 border border-white/20">
                                            <span class="font-bold">Content Text</span>
                                            <span class="text-xs">Sub-content</span>
                                        </div>
                                    </div>

                                    <div class="space-y-5">
                                        {{-- Part 1: Global Opacity --}}
                                        <div>
                                            <label class="text-[10px] text-purple-400 font-bold uppercase mb-2 block flex justify-between">
                                                <span>1. Intensity (Nilai)</span>
                                                <span id="opacity-val-label" class="text-white/30">100%</span>
                                            </label>
                                            <input type="range" min="0" max="100" value="100" class="w-full h-1 bg-white/10 rounded-lg appearance-none cursor-pointer accent-purple-500" oninput="updateSim61('global', this.value)">
                                        </div>

                                        {{-- Part 2: Context --}}
                                        <div>
                                            <label class="text-[10px] text-blue-400 font-bold uppercase mb-2 block flex justify-between">
                                                <span>2. Context (Target)</span>
                                                <span class="text-white/30">Scope</span>
                                            </label>
                                            <div class="grid grid-cols-2 gap-2">
                                                <button onclick="updateSim61('mode', 'element')" class="py-2 bg-white/5 rounded text-[10px] hover:bg-white/10 border border-white/10 transition focus:ring-2 focus:ring-blue-500">Whole Element</button>
                                                <button onclick="updateSim61('mode', 'bg')" class="py-2 bg-white/5 rounded text-[10px] hover:bg-white/10 border border-white/10 transition focus:ring-2 focus:ring-blue-500">Background Only</button>
                                            </div>
                                            <p id="sim61-desc" class="text-[10px] text-white/40 mt-2 italic">Mode: Mempengaruhi seluruh elemen.</p>
                                        </div>

                                        {{-- Part 3: State --}}
                                        <div>
                                            <label class="text-[10px] text-pink-400 font-bold uppercase mb-2 block flex justify-between">
                                                <span>3. State (Interaksi)</span>
                                                <span class="text-white/30">Hover</span>
                                            </label>
                                            <div class="flex items-center gap-3 bg-white/5 p-2 rounded-lg border border-white/5">
                                                <input type="checkbox" id="sim61-hover" onchange="updateSim61('hover', this.checked)" class="accent-pink-500 w-4 h-4 rounded cursor-pointer">
                                                <span class="text-xs text-white/70">Aktifkan `hover:opacity-100` (Arahkan mouse ke box)</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 62: FILTERS --}}
                    <section id="filters" class="lesson-section scroll-mt-32" data-lesson-id="62">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-purple-500 pl-6">
                                <span class="text-purple-400 font-mono text-xs uppercase tracking-widest">Lesson 3.4.3</span>
                                <h2 class="text-3xl lg:text-4xl font-black text-white leading-[1.1]">Filters & Backdrop Blur</h2>
                            </div>

                            <div class="grid lg:grid-cols-2 gap-12">
                                <div class="space-y-6 text-white/80 leading-relaxed text-sm md:text-base">
                                    <p>
                                        Tailwind membawa kekuatan pemrosesan gambar langsung ke browser melalui utilitas <strong>Filters</strong>. Tidak seperti mengedit gambar di Photoshop, filter CSS dirender secara <em>real-time</em> oleh GPU pengguna. Ini memungkinkan efek dinamis seperti mengubah gambar menjadi <code>grayscale</code> saat non-aktif dan berwarna saat di-hover.
                                    </p>
                                    <p>
                                        Konsep terpenting di sini adalah perbedaan antara <strong>Blur</strong> dan <strong>Backdrop Blur</strong>:
                                        <br>• <code>blur-{n}</code>: Memburamkan <strong>elemen itu sendiri</strong>. Berguna untuk menyensor konten sensitif atau membuat efek bayangan lembut.
                                        <br>• <code>backdrop-blur-{n}</code>: Memburamkan <strong>apa pun yang ada di belakang</strong> elemen tersebut. Ini adalah kunci dari teknik desain <em>Glassmorphism</em> (kaca buram) yang dipopulerkan oleh ekosistem iOS dan Windows 11.
                                    </p>
                                    <p>
                                        <strong>Tips Performa:</strong> Meskipun kuat, filter (terutama blur) cukup berat bagi browser. Gunakan dengan bijak pada elemen yang dianimasikan.
                                    </p>
                                </div>

                                {{-- SIMULATOR 62 --}}
                                <div class="bg-[#0f141e] border border-white/10 rounded-2xl p-6 shadow-2xl relative flex flex-col justify-between">
                                    <div class="absolute top-4 right-4 text-[10px] font-mono text-white/30 uppercase tracking-widest">Simulasi: Lens Effect</div>
                                    
                                    <div class="relative h-48 rounded-xl mb-6 overflow-hidden border border-white/5 group">
                                        {{-- Base Image --}}
                                        <img src="https://images.unsplash.com/photo-1620641788421-7a1c342ea42e?q=80&w=1000&auto=format&fit=crop" class="absolute inset-0 w-full h-full object-cover transition-all duration-500" id="sim62-img">
                                        
                                        {{-- Overlay for Backdrop --}}
                                        <div id="sim62-overlay" class="absolute inset-0 flex items-center justify-center transition-all duration-500 bg-transparent">
                                            <span class="font-bold text-white text-xl drop-shadow-lg hidden" id="sim62-text">Glass Overlay</span>
                                        </div>
                                    </div>

                                    <div class="space-y-5">
                                        {{-- Part 1: Standard Filters --}}
                                        <div>
                                            <label class="text-[10px] text-purple-400 font-bold uppercase mb-2 block flex justify-between">
                                                <span>1. Correction (Warna)</span>
                                                <span class="text-white/30">Filter</span>
                                            </label>
                                            <div class="flex gap-2">
                                                <button onclick="updateSim62('filter', 'none')" class="flex-1 py-2 bg-white/5 rounded text-[10px] hover:bg-white/10 border border-white/10 transition focus:ring-2 focus:ring-purple-500">Normal</button>
                                                <button onclick="updateSim62('filter', 'grayscale')" class="flex-1 py-2 bg-white/5 rounded text-[10px] hover:bg-white/10 border border-white/10 transition focus:ring-2 focus:ring-purple-500">B&W</button>
                                                <button onclick="updateSim62('filter', 'sepia')" class="flex-1 py-2 bg-white/5 rounded text-[10px] hover:bg-white/10 border border-white/10 transition focus:ring-2 focus:ring-purple-500">Sepia</button>
                                            </div>
                                        </div>

                                        {{-- Part 2: Blur Intensity --}}
                                        <div>
                                            <label class="text-[10px] text-blue-400 font-bold uppercase mb-2 block flex justify-between">
                                                <span>2. Blur Strength (Kekuatan)</span>
                                                <span class="text-white/30">Pixel Radius</span>
                                            </label>
                                            <input type="range" min="0" max="16" step="4" value="0" class="w-full h-1 bg-white/10 rounded-lg appearance-none cursor-pointer accent-blue-500" oninput="updateSim62('blur', this.value)">
                                        </div>

                                        {{-- Part 3: Backdrop Mode --}}
                                        <div>
                                            <label class="text-[10px] text-pink-400 font-bold uppercase mb-2 block flex justify-between">
                                                <span>3. Render Mode (Teknik)</span>
                                                <span class="text-white/30">Effect Type</span>
                                            </label>
                                            <div class="flex items-center justify-between bg-white/5 p-2 rounded-lg border border-white/10">
                                                <span class="text-xs text-white/70 ml-2">Glassmorphism Mode (Backdrop)</span>
                                                <button onclick="updateSim62('mode', 'toggle')" id="sim62-mode-btn" class="px-3 py-1 bg-white/10 rounded text-[10px] hover:bg-pink-500 transition border border-white/10">OFF</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 63: TRANSITIONS --}}
                    <section id="transitions" class="lesson-section scroll-mt-32" data-lesson-id="63">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-purple-500 pl-6">
                                <span class="text-purple-400 font-mono text-xs uppercase tracking-widest">Lesson 3.4.4</span>
                                <h2 class="text-3xl lg:text-4xl font-black text-white leading-[1.1]">Transitions & Animations</h2>
                            </div>

                            <div class="grid lg:grid-cols-2 gap-12">
                                <div class="space-y-6 text-white/80 leading-relaxed text-sm md:text-base">
                                    <p>
                                        Tanpa <strong>Transitions</strong>, perubahan state di web (seperti hover) terjadi secara instan (0ms), yang terasa kaku dan robotik bagi otak manusia. Transisi berfungsi untuk menginterpolasi nilai antara dua state, membuat perubahan terasa halus dan alami.
                                    </p>
                                    <p>
                                        <strong>Fisika Gerak (Duration & Ease):</strong>
                                        <br>• <strong>Duration:</strong> Standar industri untuk mikro-interaksi UI adalah <code>150ms</code> hingga <code>300ms</code>. Lebih cepat tidak terlihat, lebih lambat terasa <em>laggy</em>.
                                        <br>• <strong>Easing:</strong> <code>ease-out</code> (cepat di awal, melambat di akhir) terasa paling natural karena meniru gesekan di dunia nyata (seperti mengerem mobil). <code>linear</code> sering terasa membosankan dan mesin-like.
                                    </p>
                                    <p>
                                        Untuk gerakan yang terus-menerus (loop), Tailwind menyediakan <strong>Animations</strong> bawaan seperti <code>animate-spin</code> (loading indicators), <code>animate-pulse</code> (skeleton screens), dan <code>animate-bounce</code> (notifikasi perhatian).
                                    </p>
                                </div>

                                {{-- SIMULATOR 63 --}}
                                <div class="bg-[#0f141e] border border-white/10 rounded-2xl p-6 shadow-2xl relative flex flex-col justify-between">
                                    <div class="absolute top-4 right-4 text-[10px] font-mono text-white/30 uppercase tracking-widest">Simulasi: Motion Lab</div>
                                    
                                    <div class="flex-1 h-48 bg-white/5 rounded-xl mb-6 border border-white/5 relative overflow-hidden flex items-center justify-center">
                                        <div id="sim63-object" class="w-16 h-16 bg-gradient-to-tr from-purple-500 to-pink-500 rounded-xl shadow-lg border border-white/20 transition-all"></div>
                                        
                                        {{-- Track line for move demo --}}
                                        <div class="absolute bottom-4 left-4 right-4 h-px bg-white/10 flex justify-between text-[8px] text-white/30 font-mono uppercase">
                                            <span>Start</span>
                                            <span>End</span>
                                        </div>
                                    </div>

                                    <div class="space-y-5">
                                        {{-- Part 1: Transition Logic --}}
                                        <div>
                                            <label class="text-[10px] text-purple-400 font-bold uppercase mb-2 block flex justify-between">
                                                <span>1. Easing (Percepatan)</span>
                                                <span class="text-white/30">Physics</span>
                                            </label>
                                            <div class="grid grid-cols-3 gap-2">
                                                <button onclick="updateSim63('ease', 'ease-linear')" class="py-2 bg-white/5 rounded text-[10px] hover:bg-white/10 border border-white/10 transition focus:ring-2 focus:ring-purple-500">Linear</button>
                                                <button onclick="updateSim63('ease', 'ease-out')" class="py-2 bg-white/5 rounded text-[10px] hover:bg-white/10 border border-white/10 transition focus:ring-2 focus:ring-purple-500">Ease Out</button>
                                                <button onclick="updateSim63('ease', 'ease-in-out')" class="py-2 bg-white/5 rounded text-[10px] hover:bg-white/10 border border-white/10 transition focus:ring-2 focus:ring-purple-500">In-Out</button>
                                            </div>
                                        </div>

                                        {{-- Part 2: Duration --}}
                                        <div>
                                            <label class="text-[10px] text-blue-400 font-bold uppercase mb-2 block flex justify-between">
                                                <span>2. Duration (Durasi)</span>
                                                <span id="sim63-dur-label" class="text-white/30">300ms</span>
                                            </label>
                                            <input type="range" min="100" max="2000" step="100" value="300" class="w-full h-1 bg-white/10 rounded-lg appearance-none cursor-pointer accent-blue-500" oninput="updateSim63('duration', this.value)">
                                        </div>

                                        {{-- Part 3: Trigger / Animation --}}
                                        <div>
                                            <label class="text-[10px] text-pink-400 font-bold uppercase mb-2 block flex justify-between">
                                                <span>3. Action (Pemicu)</span>
                                                <span class="text-white/30">Event</span>
                                            </label>
                                            <div class="flex gap-2">
                                                <button onclick="updateSim63('action', 'move')" class="flex-1 py-2 bg-purple-600 text-white rounded text-[10px] hover:bg-purple-500 transition shadow-lg shadow-purple-900/50">Test Move</button>
                                                <button onclick="updateSim63('action', 'spin')" class="flex-1 py-2 bg-white/5 rounded text-[10px] hover:bg-white/10 border border-white/10 transition focus:ring-2 focus:ring-pink-500">Loop Spin</button>
                                                <button onclick="updateSim63('action', 'bounce')" class="flex-1 py-2 bg-white/5 rounded text-[10px] hover:bg-white/10 border border-white/10 transition focus:ring-2 focus:ring-pink-500">Loop Bounce</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 64: TRANSFORMS --}}
                    <section id="transforms" class="lesson-section scroll-mt-32" data-lesson-id="64">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-purple-500 pl-6">
                                <span class="text-purple-400 font-mono text-xs uppercase tracking-widest">Lesson 3.4.5</span>
                                <h2 class="text-3xl lg:text-4xl font-black text-white leading-[1.1]">Transform (2D & 3D)</h2>
                            </div>

                            <div class="grid lg:grid-cols-2 gap-12">
                                <div class="space-y-6 text-white/80 leading-relaxed text-sm md:text-base">
                                    <p>
                                        <strong>Hardware Acceleration:</strong><br>
                                        Properti <strong>Transform</strong> (`scale`, `rotate`, `translate`, `skew`) adalah cara paling efisien untuk memindahkan atau mengubah bentuk elemen. Berbeda dengan mengubah `margin` atau `width` yang memicu kalkulasi ulang tata letak (*layout thrashing*), Transform diproses di <em>Composite Layer</em> browser menggunakan GPU. Hasilnya adalah animasi yang sangat mulus (60fps) bahkan pada perangkat lambat.
                                    </p>
                                    <p>
                                        <strong>Kombinasi Utilitas:</strong><br>
                                        Di Tailwind, Anda dapat menggabungkan beberapa transformasi sekaligus. Contoh umum adalah efek kartu: `hover:scale-105 hover:-rotate-1`. Ini menciptakan efek "pop" yang dinamis dan menyenangkan.
                                    </p>
                                    <p>
                                        <strong>Origin Point:</strong><br>
                                        Secara default, transformasi terjadi dari titik tengah (`origin-center`). Gunakan `origin-bottom-left` atau lainnya untuk mengubah poros putaran, memberikan nuansa fisik seperti kertas yang tertiup angin.
                                    </p>
                                </div>

                                {{-- SIMULATOR 64 --}}
                                <div class="bg-[#0f141e] border border-white/10 rounded-2xl p-6 shadow-2xl relative flex flex-col justify-between">
                                    <div class="absolute top-4 right-4 text-[10px] font-mono text-white/30 uppercase tracking-widest">Simulasi: Geometric</div>
                                    
                                    <div class="flex-1 h-48 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] rounded-xl mb-6 border border-white/5 flex items-center justify-center overflow-hidden">
                                        <div id="sim64-target" class="w-24 h-24 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-xl shadow-xl flex items-center justify-center font-bold text-white transition-transform duration-300 ease-out">
                                            TF
                                        </div>
                                    </div>

                                    <div class="space-y-5">
                                        {{-- Part 1: Scale --}}
                                        <div>
                                            <div class="flex justify-between mb-1">
                                                <label class="text-[10px] text-purple-400 font-bold uppercase">1. Scale (Zoom)</label>
                                                <span id="sim64-scale-val" class="text-[10px] text-white/50">100%</span>
                                            </div>
                                            <input type="range" min="50" max="150" step="10" value="100" class="w-full h-1 bg-white/10 rounded-lg appearance-none cursor-pointer accent-purple-500" oninput="updateSim64('scale', this.value)">
                                        </div>

                                        {{-- Part 2: Rotate & Skew --}}
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <div class="flex justify-between mb-1">
                                                    <label class="text-[10px] text-blue-400 font-bold uppercase">2. Rotate</label>
                                                    <span id="sim64-rotate-val" class="text-[10px] text-white/50">0deg</span>
                                                </div>
                                                <input type="range" min="-180" max="180" step="15" value="0" class="w-full h-1 bg-white/10 rounded-lg appearance-none cursor-pointer accent-blue-500" oninput="updateSim64('rotate', this.value)">
                                            </div>
                                            <div>
                                                <div class="flex justify-between mb-1">
                                                    <label class="text-[10px] text-blue-400 font-bold uppercase">Skew</label>
                                                    <span id="sim64-skew-val" class="text-[10px] text-white/50">0deg</span>
                                                </div>
                                                <input type="range" min="-20" max="20" step="5" value="0" class="w-full h-1 bg-white/10 rounded-lg appearance-none cursor-pointer accent-blue-500" oninput="updateSim64('skew', this.value)">
                                            </div>
                                        </div>

                                        {{-- Part 3: Translate --}}
                                        <div>
                                            <div class="flex justify-between mb-1">
                                                <label class="text-[10px] text-pink-400 font-bold uppercase">3. Translate X (Geser)</label>
                                                <span id="sim64-trans-val" class="text-[10px] text-white/50">0px</span>
                                            </div>
                                            <input type="range" min="-100" max="100" step="10" value="0" class="w-full h-1 bg-white/10 rounded-lg appearance-none cursor-pointer accent-pink-500" oninput="updateSim64('translate', this.value)">
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
                    <a href="{{ route('courses.borders') }}" class="group flex items-center gap-4 text-slate-400 hover:text-white transition">
                        <div class="w-12 h-12 rounded-full border border-white/10 flex items-center justify-center group-hover:border-purple-500/50 group-hover:bg-purple-500/10 transition-all duration-300">
                            <span class="text-lg group-hover:-translate-x-1 transition-transform">←</span>
                        </div>
                        <div class="text-left">
                            <div class="text-[10px] uppercase tracking-widest opacity-50 mb-1">Sebelumnya</div>
                            <div class="font-bold text-sm">3.3 Borders & Effects</div>
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
    /* UTILS & ANIMATION (Indigo/Purple Theme) */
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
</style>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    /* --- CONFIGURATION --- */
    window.SUBBAB_LESSON_IDS = [60, 61, 62, 63, 64, 65]; 
    window.INIT_COMPLETED_LESSONS = {!! json_encode($completedLessonIds ?? []) !!};
    let completedLessons = new Set(window.INIT_COMPLETED_LESSONS);
    const ACTIVITY_ID = {{ $activityId ?? 13 }}; 
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

    /* --- SIMULATORS (VANILLA JS FOR PERFORMANCE) --- */
    
    // Sim 60: Box Shadow
    let sim60State = { elevation: 'shadow-none', color: 'shadow-black/50', inset: false };
    window.updateSim60 = function(key, val) {
        sim60State[key] = val;
        const target = document.getElementById('sim60-target');
        // Reset classes
        target.className = 'w-32 h-32 bg-white rounded-2xl flex items-center justify-center text-slate-900 font-bold transition-all duration-500';
        
        // Build classes
        let shadowClass = sim60State.elevation;
        if(sim60State.inset) shadowClass = 'shadow-inner';
        
        // Add classes
        target.classList.add(shadowClass);
        if(!sim60State.inset && sim60State.elevation !== 'shadow-none') {
            target.classList.add(sim60State.color);
        }
    }

    // Sim 61: Opacity
    let sim61State = { global: 100, mode: 'element', hover: false };
    window.updateSim61 = function(key, val) {
        sim61State[key] = val;
        const target = document.getElementById('sim61-target');
        const desc = document.getElementById('sim61-desc');
        const label = document.getElementById('opacity-val-label');
        
        if(key === 'global') label.innerText = val + '%';

        // Reset style/classes
        target.style.opacity = '';
        target.className = 'w-48 h-24 bg-purple-600 rounded-xl flex flex-col items-center justify-center text-white transition-all duration-300 border border-white/20';
        
        // Apply Mode
        if(sim61State.mode === 'element') {
            target.style.opacity = sim61State.global / 100;
            desc.innerText = "Mode: Opasitas elemen (teks ikut transparan).";
        } else {
            // Background opacity simulation using RGBA
            target.style.backgroundColor = `rgba(147, 51, 234, ${sim61State.global / 100})`; // purple-600
            desc.innerText = "Mode: Opasitas background (teks tetap jelas).";
        }

        // Apply Hover
        if(sim61State.hover) {
            target.classList.add('hover:opacity-100', 'cursor-pointer');
            if(sim61State.mode === 'element') target.style.opacity = '0.5'; // force dimmed state to show hover
        }
    }

    // Sim 62: Filters
    let sim62State = { filter: 'none', blur: 0, mode: 'off' };
    window.updateSim62 = function(key, val) {
        const img = document.getElementById('sim62-img');
        const overlay = document.getElementById('sim62-overlay');
        const text = document.getElementById('sim62-text');
        const btnMode = document.getElementById('sim62-mode-btn');

        if(key === 'filter') {
            img.style.filter = val === 'none' ? '' : `${val}(100%)`;
        } else if(key === 'blur') {
            if(sim62State.mode === 'on') {
                overlay.style.backdropFilter = `blur(${val}px)`;
            } else {
                img.style.filter = `blur(${val}px)`;
            }
        } else if(key === 'mode') {
            sim62State.mode = sim62State.mode === 'off' ? 'on' : 'off';
            btnMode.innerText = sim62State.mode.toUpperCase();
            
            if(sim62State.mode === 'on') {
                img.style.filter = ''; // Reset img blur
                overlay.classList.remove('bg-transparent');
                overlay.classList.add('bg-white/10');
                text.classList.remove('hidden');
            } else {
                overlay.style.backdropFilter = '';
                overlay.classList.add('bg-transparent');
                overlay.classList.remove('bg-white/10');
                text.classList.add('hidden');
            }
        }
    }

    // Sim 63: Transition
    let sim63State = { ease: 'ease-linear', duration: 300, action: null };
    window.updateSim63 = function(key, val) {
        sim63State[key] = val;
        const obj = document.getElementById('sim63-object');
        const durLabel = document.getElementById('sim63-dur-label');

        if(key === 'duration') durLabel.innerText = val + 'ms';

        // Apply styles
        obj.style.transitionTimingFunction = sim63State.ease;
        obj.style.transitionDuration = sim63State.duration + 'ms';

        // Actions
        obj.className = 'w-16 h-16 bg-gradient-to-tr from-purple-500 to-pink-500 rounded-xl shadow-lg border border-white/20 transition-all'; // Reset
        
        if(key === 'action') {
            if(val === 'move') {
                // Toggle move
                obj.classList.toggle('translate-x-48');
            } else if (val === 'spin') {
                obj.classList.add('animate-spin');
            } else if (val === 'bounce') {
                obj.classList.add('animate-bounce');
            }
        }
    }

    // Sim 64: Transform
    let sim64State = { scale: 100, rotate: 0, skew: 0, translate: 0 };
    window.updateSim64 = function(key, val) {
        sim64State[key] = val;
        // Update Labels
        document.getElementById(`sim64-${key === 'translate' ? 'trans' : key}-val`).innerText = val + (key === 'translate' ? 'px' : key === 'scale' ? '%' : 'deg');
        
        // Apply Transform
        const el = document.getElementById('sim64-target');
        el.style.transform = `scale(${sim64State.scale/100}) rotate(${sim64State.rotate}deg) skewX(${sim64State.skew}deg) translateX(${sim64State.translate}px)`;
    }

    /* --- EXPERT CHALLENGE LOGIC --- */
    const challengeData = {
        shadow: {
            label: "1. Shadow Depth",
            options: [
                { val: 'shadow-none', label: 'Flat' },
                { val: 'shadow-xl', label: 'Deep ', correct: true },
                { val: 'shadow-inner', label: 'Inner' }
            ]
        },
        rounded: {
            label: "2. Corner Radius",
            options: [
                { val: 'rounded-none', label: 'Square' },
                { val: 'rounded-2xl', label: 'Smooth ', correct: true },
                { val: 'rounded-full', label: 'Pill' }
            ]
        },
        hover: {
            label: "3. Hover Effect",
            options: [
                { val: 'hover:opacity-50', label: 'Fade' },
                { val: 'hover:-translate-y-2 hover:scale-105', label: 'Lift & Zoom ', correct: true },
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
            btn.onclick = () => window.location.href = "{{ route('lab.start', ['id' => 3]) }}"; // Adjust route
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