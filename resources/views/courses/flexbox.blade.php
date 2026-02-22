@extends('layouts.landing')
@section('title','Bab 2.1 · Layouting dengan Flexbox')

@section('content')
<div id="courseRoot" class="relative h-screen bg-[#020617] text-white font-sans overflow-hidden flex flex-col selection:bg-purple-500/30 pt-20">

    {{-- BACKGROUND COSMIC LAYER --}}
    <div class="fixed inset-0 -z-50 pointer-events-none">
        <div id="animated-bg" class="absolute inset-0 opacity-50"></div>
        <div class="absolute top-[-20%] left-[-10%] w-[1000px] h-[1000px] bg-purple-900/10 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute bottom-[-20%] right-[-10%] w-[800px] h-[800px] bg-indigo-900/10 rounded-full blur-[100px]"></div>
        <canvas id="stars" class="absolute inset-0 pointer-events-none"></canvas>
        <div id="cursor-glow"></div>
    </div>

    @include('layouts.partials.navbar')

    <div class="flex flex-1 overflow-hidden relative z-20">

        @include('layouts.partials.course-sidebar')

        <main id="mainScroll" class="flex-1 h-full overflow-y-auto scroll-smooth relative bg-transparent custom-scrollbar scroll-padding-top-24">
            
            {{-- STICKY HEADER --}}
            <div id="stickyHeader" class="sticky top-0 z-30 w-full bg-[#020617]/80 backdrop-blur-2xl border-b border-white/5 px-8 py-4 flex items-center justify-between transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-purple-500/20 to-transparent border border-purple-500/20 flex items-center justify-center font-bold text-xs text-purple-400">2.1</div>
                    <div>
                        <h1 class="text-sm font-bold text-white">Layouting dengan Flexbox</h1>
                        <p class="text-[10px] text-white/50">Core Layout Concepts</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="hidden sm:block w-32 h-1.5 bg-white/10 rounded-full overflow-hidden">
                        <div id="topProgressBar" class="h-full bg-gradient-to-r from-purple-500 to-indigo-500 w-0 transition-all duration-500 shadow-[0_0_10px_#8b5cf6]"></div>
                    </div>
                    <span id="progressLabelTop" class="text-purple-400 font-bold text-xs">0%</span>
                </div>
            </div>

            {{-- CONTENT WRAPPER --}}
            <div class="p-6 lg:p-16 max-w-5xl mx-auto pb-40">
                
                {{-- LEARNING OBJECTIVES --}}
                <div class="mb-24">
                    <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Tujuan Pembelajaran
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <div class="bg-[#1e1e1e] border border-white/5 p-5 rounded-xl flex items-start gap-4 hover:border-purple-500/30 transition group h-full">
                            <div class="w-8 h-8 rounded bg-purple-500/10 text-purple-400 flex items-center justify-center shrink-0 font-bold text-xs">1</div>
                            <div><h4 class="text-sm font-bold text-white mb-1">Mental Model</h4><p class="text-[11px] text-white/50 leading-relaxed">Memahami perubahan konteks dari Block ke Flex formatting context.</p></div>
                        </div>
                        <div class="bg-[#1e1e1e] border border-white/5 p-5 rounded-xl flex items-start gap-4 hover:border-indigo-500/30 transition group h-full">
                            <div class="w-8 h-8 rounded bg-indigo-500/10 text-indigo-400 flex items-center justify-center shrink-0 font-bold text-xs">2</div>
                            <div><h4 class="text-sm font-bold text-white mb-1">Axis & Wrapping</h4><p class="text-[11px] text-white/50 leading-relaxed">Memahami manipulasi Sumbu Utama dan Sumbu Silang serta overflow.</p></div>
                        </div>
                        <div class="bg-[#1e1e1e] border border-white/5 p-5 rounded-xl flex items-start gap-4 hover:border-fuchsia-500/30 transition group h-full">
                            <div class="w-8 h-8 rounded bg-fuchsia-500/10 text-fuchsia-400 flex items-center justify-center shrink-0 font-bold text-xs">3</div>
                            <div><h4 class="text-sm font-bold text-white mb-1">Distribution</h4><p class="text-[11px] text-white/50 leading-relaxed">Memahami distribusi ruang kosong menggunakan Justify dan Align.</p></div>
                        </div>
                        <div class="bg-[#1e1e1e] border border-white/5 p-5 rounded-xl flex items-start gap-4 hover:border-pink-500/30 transition group h-full">
                            <div class="w-8 h-8 rounded bg-pink-500/10 text-pink-400 flex items-center justify-center shrink-0 font-bold text-xs">4</div>
                            <div><h4 class="text-sm font-bold text-white mb-1">Flexibility</h4><p class="text-[11px] text-white/50 leading-relaxed">Memahami algoritma Grow dan Shrink untuk elemen responsif.</p></div>
                        </div>
                        <div class="bg-gradient-to-br from-purple-900/40 to-indigo-900/40 border border-purple-500/30 p-5 rounded-xl flex items-start gap-4 hover:shadow-[0_0_20px_rgba(168,85,247,0.2)] transition group h-full col-span-2 md:col-span-2">
                            <div class="w-8 h-8 rounded bg-white/10 text-white flex items-center justify-center shrink-0 font-bold text-xs">🏁</div>
                            <div><h4 class="text-sm font-bold text-white mb-1">Final Mission</h4><p class="text-[11px] text-white/70 leading-relaxed">Live Code: Memperbaiki Navbar Rusak.</p></div>
                        </div>
                    </div>
                </div>

                <article class="space-y-40">
                    
                    {{-- LESSON 1: FONDASI FLEX --}}
                    <section id="section-29" class="lesson-section scroll-mt-32" data-lesson-id="29">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-purple-500 pl-6">
                                <span class="text-purple-400 font-mono text-xs uppercase tracking-widest">Lesson 2.1.1</span>
                                <h2 class="text-4xl lg:text-5xl font-black text-white leading-[1.1]">
                                    Filosofi & <br> 
                                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-fuchsia-500">Flex Container</span>
                                </h2>
                            </div>
                            
                            {{-- Materi Part 1 --}}
                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-white flex items-center gap-2"><span class="w-6 h-6 rounded bg-purple-600 flex items-center justify-center text-[10px] text-white">A</span> Perubahan Konteks Layout</h3>
                                <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed space-y-4">
                                    <p>
                                        Sebelum adanya Flexbox (Flexible Box Layout), pengembang web mengandalkan properti <code>float</code> dan <code>position</code> untuk mengatur tata letak. Metode ini seringkali rapuh dan sulit dikendalikan, terutama untuk penyelarasan vertikal (vertical alignment) dan distribusi ruang yang merata.
                                    </p>
                                    <p>
                                        Dalam buku <em>"Modern CSS with Tailwind"</em>, dijelaskan bahwa Flexbox memperkenalkan <strong>Formatting Context</strong> baru. Ketika Anda menambahkan kelas utilitas <code>flex</code> pada sebuah elemen pembungkus (parent), Anda mengubah aturan fisika di dalamnya. Elemen anak (children) tidak lagi berperilaku sebagai elemen <em>block</em> yang menumpuk ke bawah, melainkan menjadi <em>flex items</em> yang mengalir sesuai sumbu fleksibel.
                                    </p>
                                </div>
                            </div>

                            {{-- Materi Part 2 --}}
                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-white flex items-center gap-2"><span class="w-6 h-6 rounded bg-purple-600 flex items-center justify-center text-[10px] text-white">B</span> Flex vs Inline-Flex</h3>
                                <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed space-y-4">
                                    <p>
                                        Tailwind menyediakan dua kelas utama untuk mengaktifkan Flexbox:
                                    </p>
                                    <ul class="list-disc pl-5 space-y-2 marker:text-purple-500">
                                        <li><strong><code>flex</code></strong>: Membuat elemen pembungkus menjadi <em>block-level flex container</em>. Elemen ini akan mengambil lebar penuh (width 100%) dari induknya, mirip dengan <code>div</code> biasa.</li>
                                        <li><strong><code>inline-flex</code></strong>: Membuat elemen pembungkus menjadi <em>inline-level flex container</em>. Elemen ini hanya akan selebar konten di dalamnya dan mengalir bersama teks di sekitarnya, mirip dengan <code>span</code>.</li>
                                    </ul>
                                    <p>
                                        Penting untuk diingat: properti ini hanya mempengaruhi <strong>pembungkusnya</strong>. Perilaku elemen anak (flex items) di dalamnya tetap sama untuk kedua jenis container tersebut.
                                    </p>
                                </div>
                            </div>

                            {{-- SIMULATOR 1 --}}
                            <div class="bg-[#0b0f19] border border-white/10 rounded-xl overflow-hidden shadow-2xl flex flex-col md:flex-row h-[350px]">
                                <div class="w-full md:w-1/2 bg-[#1e1e1e] p-6 font-mono text-xs flex flex-col">
                                    <div class="flex justify-between items-center mb-4 pb-2 border-b border-white/5">
                                        <span class="text-white/40 font-bold uppercase text-[10px]">Playground: Enable Flex</span>
                                        <div class="flex gap-2">
                                            <button onclick="setSimFlex('block')" class="px-3 py-1 bg-white/5 border border-white/10 rounded text-gray-400 hover:text-white transition text-[10px]">Default (Block)</button>
                                            <button onclick="setSimFlex('flex')" class="px-3 py-1 bg-purple-600/20 border border-purple-500/50 text-purple-400 rounded transition text-[10px]">Flexbox</button>
                                        </div>
                                    </div>
                                    <div id="sim1-code" class="flex-1 overflow-auto text-purple-300 leading-relaxed whitespace-pre font-mono bg-black/20 p-4 rounded border border-white/5">
&lt;div class="<span id="sim1-class">block</span>"&gt;
  &lt;div&gt;Kotak 1&lt;/div&gt;
  &lt;div&gt;Kotak 2&lt;/div&gt;
  &lt;div&gt;Kotak 3&lt;/div&gt;
&lt;/div&gt;</div>
                                </div>
                                <div class="w-full md:w-1/2 bg-[#111] flex items-center justify-center border-l border-white/5 relative p-8">
                                    <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-10"></div>
                                    <div id="sim1-preview" class="block w-full border border-dashed border-white/20 p-4 rounded bg-white/5 gap-2 transition-all duration-500">
                                        <div class="bg-purple-600 w-16 h-16 rounded flex items-center justify-center font-bold text-white mb-2 sim1-item shadow-[0_0_15px_rgba(147,51,234,0.3)]">1</div>
                                        <div class="bg-indigo-600 w-16 h-16 rounded flex items-center justify-center font-bold text-white mb-2 sim1-item shadow-[0_0_15px_rgba(79,70,229,0.3)]">2</div>
                                        <div class="bg-fuchsia-600 w-16 h-16 rounded flex items-center justify-center font-bold text-white mb-2 sim1-item shadow-[0_0_15px_rgba(192,38,211,0.3)]">3</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 2: DIRECTION & WRAP --}}
                    <section id="section-30" class="lesson-section scroll-mt-32" data-lesson-id="30">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-indigo-500 pl-6">
                                <span class="text-indigo-400 font-mono text-xs uppercase tracking-widest">Lesson 2.1.2</span>
                                <h2 class="text-4xl lg:text-5xl font-black text-white leading-[1.1]">
                                    Arah & <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-purple-500">Wrapping</span>
                                </h2>
                            </div>

                            {{-- Materi Part 1 --}}
                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-white flex items-center gap-2"><span class="w-6 h-6 rounded bg-indigo-600 flex items-center justify-center text-[10px] text-white">A</span> Main Axis vs Cross Axis</h3>
                                <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed space-y-4">
                                    <p>
                                        
                                        Inti dari Flexbox adalah pemahaman tentang dua sumbu imajiner: <strong>Main Axis</strong> (Sumbu Utama) dan <strong>Cross Axis</strong> (Sumbu Silang). Arah sumbu ini tidak statis; mereka berubah tergantung pada properti <code>flex-direction</code>.
                                    </p>
                                    <p>
                                        Secara default (<code>flex-row</code>), Main Axis berjalan secara horizontal dari kiri ke kanan. Namun, ketika Anda menggunakan <code>flex-col</code>, Main Axis berputar 90 derajat menjadi vertikal (atas ke bawah). Pemahaman ini krusial karena properti seperti <code>justify-*</code> selalu bekerja pada Main Axis, sedangkan <code>items-*</code> bekerja pada Cross Axis.
                                    </p>
                                </div>
                            </div>

                            {{-- Materi Part 2 --}}
                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-white flex items-center gap-2"><span class="w-6 h-6 rounded bg-indigo-600 flex items-center justify-center text-[10px] text-white">B</span> Mengatasi "Squishing" dengan Wrap</h3>
                                <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed space-y-4">
                                    <p>
                                        Perilaku default Flexbox adalah <code>flex-nowrap</code>. Ini berarti container akan berusaha sekuat tenaga untuk memuat semua item dalam satu baris, bahkan jika itu berarti memaksa item menyusut (<em>squishing</em>) hingga kontennya rusak atau keluar dari container (overflow).
                                    </p>
                                    <p>
                                        Solusinya adalah utilitas <code>flex-wrap</code>. Kelas ini memberitahu browser: "Jika tidak ada cukup ruang di baris ini, turunkan item ke baris baru". Ini adalah fondasi dari sistem grid responsif tanpa menggunakan CSS Grid. Buku <em>"Ultimate Tailwind CSS Handbook"</em> menyarankan penggunaan <code>flex-wrap</code> bersamaan dengan <code>gap-*</code> untuk membuat galeri kartu yang rapi.
                                    </p>
                                </div>
                            </div>

                            {{-- SIMULATOR 2 --}}
                            <div class="bg-[#1e1e1e] rounded-xl border border-white/10 p-6 flex flex-col md:flex-row gap-8 shadow-2xl relative overflow-hidden">
                                <div class="w-full md:w-1/2 space-y-6 relative z-10">
                                    <h4 class="text-xs font-bold text-white/50 uppercase">Direction Controls</h4>
                                    <div>
                                        <label class="text-[10px] text-purple-400 block mb-2 font-bold">FLEX DIRECTION</label>
                                        <div class="flex gap-2">
                                            <button onclick="updateSimDir('flex-row')" class="flex-1 py-2 bg-white/5 border border-white/10 rounded text-xs hover:bg-purple-600/20 transition text-gray-300">flex-row</button>
                                            <button onclick="updateSimDir('flex-col')" class="flex-1 py-2 bg-white/5 border border-white/10 rounded text-xs hover:bg-purple-600/20 transition text-gray-300">flex-col</button>
                                            <button onclick="updateSimDir('flex-wrap')" class="flex-1 py-2 bg-white/5 border border-white/10 rounded text-xs hover:bg-purple-600/20 transition text-gray-300">flex-wrap</button>
                                        </div>
                                    </div>
                                    <div class="bg-black/40 p-3 rounded border border-white/5 font-mono text-[10px] text-gray-400 mt-4">
                                        &lt;div class="flex <span id="sim2-code" class="text-purple-300">flex-row</span>"&gt;...&lt;/div&gt;
                                    </div>
                                </div>
                                <div class="w-full md:w-1/2 bg-black/40 rounded-xl flex items-center justify-center border border-white/5 min-h-[200px] relative z-10 p-4 overflow-hidden">
                                    <div id="sim2-target" class="flex flex-row gap-2 transition-all duration-500 border border-dashed border-white/20 p-2 rounded w-full">
                                        <div class="w-16 h-10 bg-purple-500 rounded flex items-center justify-center font-bold text-sm shrink-0">1</div>
                                        <div class="w-16 h-10 bg-indigo-500 rounded flex items-center justify-center font-bold text-sm shrink-0">2</div>
                                        <div class="w-16 h-10 bg-fuchsia-500 rounded flex items-center justify-center font-bold text-sm shrink-0">3</div>
                                        <div class="w-16 h-10 bg-pink-500 rounded flex items-center justify-center font-bold text-sm shrink-0">4</div>
                                        <div class="w-16 h-10 bg-rose-500 rounded flex items-center justify-center font-bold text-sm shrink-0">5</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 3: ALIGNMENT --}}
                    <section id="section-31" class="lesson-section scroll-mt-32" data-lesson-id="31">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-fuchsia-500 pl-6">
                                <span class="text-fuchsia-400 font-mono text-xs uppercase tracking-widest">Lesson 2.1.3</span>
                                <h2 class="text-4xl lg:text-5xl font-black text-white leading-[1.1]">
                                    Justify & <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-fuchsia-400 to-pink-500">Align</span>
                                </h2>
                            </div>

                            {{-- Materi Part 1 --}}
                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-white flex items-center gap-2"><span class="w-6 h-6 rounded bg-fuchsia-600 flex items-center justify-center text-[10px] text-white">A</span> Justify Content (Main Axis)</h3>
                                <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed space-y-4">
                                    <p>
                                        Properti <code>justify-*</code> mengontrol distribusi ruang kosong di sepanjang Sumbu Utama.
                                    </p>
                                    <ul class="list-disc pl-5 space-y-1">
                                        <li><code>justify-start</code>: Default. Item berkumpul di awal.</li>
                                        <li><code>justify-center</code>: Item berkumpul di tengah (sering digunakan untuk memusatkan konten).</li>
                                        <li><code>justify-between</code>: Item pertama di awal, terakhir di ujung, sisanya berjarak rata. Sangat populer untuk Navbar (Logo di kiri, Menu di kanan).</li>
                                    </ul>
                                </div>
                            </div>

                            {{-- Materi Part 2 --}}
                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-white flex items-center gap-2"><span class="w-6 h-6 rounded bg-fuchsia-600 flex items-center justify-center text-[10px] text-white">B</span> Align Items (Cross Axis)</h3>
                                <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed space-y-4">
                                    <p>
                                        Properti <code>items-*</code> mengontrol bagaimana item duduk di sepanjang Sumbu Silang.
                                    </p>
                                    <ul class="list-disc pl-5 space-y-1">
                                        <li><code>items-stretch</code>: Default. Item akan ditarik memanjang memenuhi tinggi container (jika container memiliki tinggi).</li>
                                        <li><code>items-center</code>: Item berada di tengah sumbu silang. Wajib digunakan jika Anda memiliki ikon dan teks bersebelahan agar lurus secara vertikal.</li>
                                        <li><code>items-baseline</code>: Menyelaraskan item berdasarkan garis dasar teks (typography baseline).</li>
                                    </ul>
                                </div>
                            </div>

                            {{-- SIMULATOR 3 --}}
                            <div class="bg-[#1e1e1e] border border-white/10 rounded-2xl p-8 relative shadow-2xl overflow-hidden flex flex-col">
                                <div class="w-full space-y-6 mb-8 relative z-10">
                                    <div>
                                        <div class="flex justify-between mb-2">
                                            <label class="text-xs text-fuchsia-400 font-bold uppercase">Justify (Main Axis)</label>
                                            <span class="text-xs text-white/50 font-mono" id="sim3-label-j">justify-start</span>
                                        </div>
                                        <div class="flex gap-2 overflow-x-auto pb-2 custom-scrollbar">
                                            <button onclick="updateSimAlign('j', 'justify-start')" class="px-3 py-1.5 bg-white/5 rounded text-[10px] hover:bg-fuchsia-600/30 transition border border-white/10">Start</button>
                                            <button onclick="updateSimAlign('j', 'justify-center')" class="px-3 py-1.5 bg-white/5 rounded text-[10px] hover:bg-fuchsia-600/30 transition border border-white/10">Center</button>
                                            <button onclick="updateSimAlign('j', 'justify-between')" class="px-3 py-1.5 bg-white/5 rounded text-[10px] hover:bg-fuchsia-600/30 transition border border-white/10">Between</button>
                                            <button onclick="updateSimAlign('j', 'justify-end')" class="px-3 py-1.5 bg-white/5 rounded text-[10px] hover:bg-fuchsia-600/30 transition border border-white/10">End</button>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex justify-between mb-2">
                                            <label class="text-xs text-purple-400 font-bold uppercase">Items (Cross Axis)</label>
                                            <span class="text-xs text-white/50 font-mono" id="sim3-label-i">items-start</span>
                                        </div>
                                        <div class="flex gap-2">
                                            <button onclick="updateSimAlign('i', 'items-start')" class="flex-1 py-1.5 bg-white/5 rounded text-[10px] hover:bg-purple-600/30 transition border border-white/10">Start</button>
                                            <button onclick="updateSimAlign('i', 'items-center')" class="flex-1 py-1.5 bg-white/5 rounded text-[10px] hover:bg-purple-600/30 transition border border-white/10">Center</button>
                                            <button onclick="updateSimAlign('i', 'items-end')" class="flex-1 py-1.5 bg-white/5 rounded text-[10px] hover:bg-purple-600/30 transition border border-white/10">End</button>
                                            <button onclick="updateSimAlign('i', 'items-stretch')" class="flex-1 py-1.5 bg-white/5 rounded text-[10px] hover:bg-purple-600/30 transition border border-white/10">Stretch</button>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="bg-[#0f141e] p-4 rounded-xl border border-white/5 w-full flex h-48 relative z-10 transition-all duration-300 gap-2" id="sim3-target">
                                    <div class="bg-fuchsia-600 w-12 h-12 rounded flex items-center justify-center font-bold text-white shadow-lg">1</div>
                                    <div class="bg-purple-600 w-12 h-16 rounded flex items-center justify-center font-bold text-white shadow-lg">2</div>
                                    <div class="bg-indigo-600 w-12 h-10 rounded flex items-center justify-center font-bold text-white shadow-lg">3</div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 4: SIZING --}}
                    <section id="section-32" class="lesson-section scroll-mt-32" data-lesson-id="32">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-pink-500 pl-6">
                                <span class="text-pink-400 font-mono text-xs uppercase tracking-widest">Lesson 2.1.4</span>
                                <h2 class="text-4xl lg:text-5xl font-black text-white leading-[1.1]">
                                    Sizing & <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-pink-400 to-rose-500">Flexibility</span>
                                </h2>
                            </div>

                            {{-- Materi Part 1 --}}
                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-white flex items-center gap-2"><span class="w-6 h-6 rounded bg-pink-600 flex items-center justify-center text-[10px] text-white">A</span> Flex Grow & Flex 1</h3>
                                <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed space-y-4">
                                    <p>
                                        Bagaimana cara membuat sidebar lebar tetap dan konten utama mengisi sisa ruang? Jawabannya ada pada <code>flex-1</code>.
                                        Di Tailwind, <code>flex-1</code> adalah shorthand untuk membuat item tumbuh (grow) dan menyusut (shrink) sesuai kebutuhan.
                                    </p>
                                    <p>
                                        Properti <code>grow</code> (tanpa angka) akan membuat elemen tersebut "serakah", mengambil semua ruang kosong yang tersedia di Main Axis.
                                    </p>
                                </div>
                            </div>

                            {{-- Materi Part 2 --}}
                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-white flex items-center gap-2"><span class="w-6 h-6 rounded bg-pink-600 flex items-center justify-center text-[10px] text-white">B</span> Mencegah Penyusutan (Shrink-0)</h3>
                                <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed space-y-4">
                                    <p>
                                        Pernahkah Anda melihat ikon atau avatar menjadi "gepeng" saat layar mengecil? Itu karena perilaku default <code>flex-shrink: 1</code>.
                                        Untuk mencegahnya, gunakan kelas utilitas <code>shrink-0</code> pada elemen yang ukurannya harus tetap (rigid), seperti logo, ikon, atau tombol aksi.
                                    </p>
                                </div>
                            </div>

                            {{-- SIMULATOR 4 --}}
                            <div class="bg-[#1e1e1e] p-8 rounded-2xl border border-white/10">
                                <h3 class="text-sm font-bold text-white mb-4">Demo: Flex-1 vs Flex-None</h3>
                                <div class="flex gap-2 w-full bg-white/5 p-2 rounded-lg mb-4">
                                    <div class="w-20 h-12 bg-purple-600 rounded flex items-center justify-center text-[10px] flex-none border border-white/20">
                                        Sidebar<br>(flex-none)
                                    </div>
                                    <div class="h-12 bg-indigo-600 rounded flex items-center justify-center text-[10px] flex-1 border border-white/20 transition-all duration-500 hover:bg-indigo-500">
                                        Main Content<br>(flex-1 : Isi sisa ruang)
                                    </div>
                                </div>
                                <p class="text-xs text-white/40 text-center">Hover pada area konten untuk melihat efek highlight.</p>
                            </div>
                        </div>
                    </section>

                    {{-- FINAL MISSION --}}
                    <section id="section-33" class="lesson-section scroll-mt-32 pt-10 border-t border-white/10" data-lesson-id="33" data-type="activity">
                        <div class="relative rounded-[2rem] bg-[#0b0f19] border border-white/10 p-8 overflow-hidden shadow-2xl group hover:border-purple-500/30 transition-all duration-500">
                            
                            <div class="absolute -top-24 -right-24 w-64 h-64 bg-purple-600/20 blur-[100px] rounded-full pointer-events-none"></div>

                            <div class="flex items-center gap-4 mb-6 relative z-10">
                                <div class="p-3 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl text-white shadow-lg shadow-purple-500/30">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold text-white">Coding Challenge: Fix The Navbar</h2>
                                    <p class="text-purple-300 text-sm">Gunakan Flexbox untuk merapikan navigasi di bawah ini.</p>
                                </div>
                            </div>

                            <div class="grid lg:grid-cols-2 gap-6 max-w-6xl mx-auto h-[600px]">
                                
                                {{-- EDITOR --}}
                                <div class="bg-[#1e1e1e] rounded-xl border border-white/10 flex flex-col overflow-hidden h-full relative shadow-2xl">
                                    
                                    {{-- LOCK OVERLAY --}}
                                    <div id="lockOverlay" class="hidden absolute inset-0 bg-[#050912]/95 backdrop-blur-md z-50 flex flex-col items-center justify-center text-center p-6 border-4 border-emerald-500/20 m-1 rounded-lg">
                                        <div class="w-24 h-24 bg-emerald-500/10 rounded-full flex items-center justify-center mb-6 border border-emerald-500/50 shadow-[0_0_50px_rgba(16,185,129,0.3)] animate-bounce">
                                            <svg class="w-12 h-12 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                        </div>
                                        <h3 class="text-3xl font-black text-white mb-2 tracking-tight">SELESAI!</h3>
                                        <p class="text-base text-white/60 mb-8 max-w-xs">Anda telah menguasai dasar Flexbox. Lanjut ke materi Grid.</p>
                                        <button disabled class="px-8 py-3 rounded-full bg-white/5 border border-white/10 text-white/30 text-xs font-bold cursor-not-allowed uppercase tracking-widest">Review Mode</button>
                                    </div>

                                    <div class="bg-[#0f141e] px-4 py-2 border-b border-white/5 flex justify-between items-center">
                                        <span class="text-xs text-white/50 font-mono">Navbar.html</span>
                                        <button onclick="resetEditor()" class="text-[10px] text-red-400 hover:text-red-300 transition uppercase font-bold">Reset</button>
                                    </div>
                                    
                                    <div id="codeEditor" class="flex-1 w-full"></div>

                                    <div class="p-4 bg-[#0f141e] border-t border-white/5">
                                        <div class="flex justify-between items-center mb-3">
                                            <span class="text-[10px] uppercase font-bold text-white/30">Requirements</span>
                                            <span id="progressText" class="text-[10px] font-mono text-purple-400">0/4 Selesai</span>
                                        </div>
                                        <div class="grid grid-cols-2 gap-2 text-[11px] font-mono text-white/50 mb-4">
                                            <div id="check-flex" class="flex items-center gap-2 transition-colors"><span class="w-3 h-3 rounded-full border border-white/20 flex items-center justify-center text-[8px]"></span> class="flex"</div>
                                            <div id="check-justify" class="flex items-center gap-2 transition-colors"><span class="w-3 h-3 rounded-full border border-white/20 flex items-center justify-center text-[8px]"></span> justify-between</div>
                                            <div id="check-align" class="flex items-center gap-2 transition-colors"><span class="w-3 h-3 rounded-full border border-white/20 flex items-center justify-center text-[8px]"></span> items-center</div>
                                            <div id="check-padding" class="flex items-center gap-2 transition-colors"><span class="w-3 h-3 rounded-full border border-white/20 flex items-center justify-center text-[8px]"></span> p-4 / p-6</div>
                                        </div>
                                        <button id="submitExerciseBtn" onclick="submitExercise()" disabled class="w-full py-3 rounded-lg bg-emerald-600 text-white font-bold text-xs shadow-lg shadow-emerald-500/20 hover:bg-emerald-500 transition-all cursor-not-allowed opacity-50 flex items-center justify-center gap-2">
                                            <span>Selesaikan Misi</span>
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                        </button>
                                    </div>
                                </div>

                                {{-- PREVIEW --}}
                                <div class="bg-white rounded-xl border border-white/10 flex-1 flex flex-col relative overflow-hidden min-h-[500px]">
                                    <div class="bg-gray-100 px-4 py-2 border-b border-gray-200 flex items-center justify-between">
                                        <span class="text-[10px] text-gray-500 font-mono">Browser Preview</span>
                                        <span class="text-[10px] bg-green-100 text-green-600 px-2 py-0.5 rounded border border-green-200">Active</span>
                                    </div>
                                    <iframe id="previewFrame" class="w-full h-full border-0 bg-gray-50"></iframe>
                                </div>
                            </div>
                        </div>
                    </section>

                </article>

                {{-- FOOTER NAVIGATION --}}
                <div class="mt-32 pt-10 border-t border-white/10 flex justify-between items-center">
                    <a href="{{ route('courses.tailwindcss') }}" class="group flex items-center gap-3 text-slate-400 hover:text-white transition">
                        <div class="w-10 h-10 rounded-full border border-white/10 flex items-center justify-center group-hover:border-white/30 transition">←</div>
                        <div class="text-left"><div class="text-[10px] uppercase tracking-widest opacity-50">Sebelumnya</div><div class="font-bold text-sm">Konsep Dasar Tailwind</div></div>
                    </a>
                    
                    <div id="nextChapterBtn" class="group flex items-center gap-3 text-right text-slate-500 cursor-not-allowed opacity-50 pointer-events-none transition-all duration-500">
                        <div class="text-right">
                            <div id="nextLabel" class="text-[10px] uppercase tracking-widest opacity-50">Terkunci</div>
                            <div class="font-bold text-sm">Layout dengan Grid</div>
                        </div>
                        <div id="nextIcon" class="w-10 h-10 rounded-full border border-white/5 flex items-center justify-center bg-white/5">🔒</div>
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>

<style>
    /* UTILS & ANIMATION */
    .nav-link.active { color: #a855f7; position: relative; } /* Purple-500 */
    .nav-link.active::after { content: ''; position: absolute; left: 0; bottom: -6px; width: 100%; height: 2px; background: linear-gradient(to right,#a855f7,#d946ef); box-shadow: 0 0 12px rgba(168,85,247,0.8); border-radius: 2px; }
    
    .custom-scrollbar::-webkit-scrollbar { width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
    
    #animated-bg{ background: radial-gradient(800px circle at 20% 20%, rgba(168,85,247,.15), transparent 40%), radial-gradient(800px circle at 80% 80%, rgba(99,102,241,.15), transparent 40%); animation:bgMove 20s ease-in-out infinite alternate; }
    @keyframes bgMove{to{transform:scale(1.15)}}
    
    @keyframes shake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-5px); } 75% { transform: translateX(5px); } }
    .shake { animation: shake 0.4s ease-in-out; }
    
    .nav-item.active { color: #a855f7; background: rgba(168,85,247,0.05); font-weight: 600; }
    .nav-item.active .dot { background: #a855f7; box-shadow: 0 0 8px #a855f7; transform: scale(1.2); }
</style>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/monaco-editor@0.45.0/min/vs/loader.js"></script>

<script>
    /* --- CONFIGURATION --- */
    // Mapping Lesson IDs for Chapter 2.1 (Adjust these based on your DB)
    window.LESSON_IDS = [29, 30, 31, 33]; 
    window.COMPLETED_IDS = {!! json_encode($completedLessonIds ?? []) !!};
    let completedSet = new Set(window.COMPLETED_IDS);
    let activityCompleted = {!! ($activityCompleted ?? false) ? 'true' : 'false' !!};
    
    const ACTIVITY_ID = 7; // ID Activity for this Chapter
    const ACTIVITY_LESSON_ID = 33; // Lesson ID for the coding challenge

    document.addEventListener('DOMContentLoaded', () => {
        initScrollSpy();
        initLessonObserver();
        initVisualEffects();
        initSidebarScroll();
        updateProgressUI();
        initMonaco();
        setSimFlex('block'); // Init Simulator 1
        
        if (activityCompleted) {
            lockActivityUI();
            unlockNextChapter();
        }
    });

    /* --- SIMULATOR 1: FLEX TOGGLE --- */
    function setSimFlex(mode) {
        const items = document.querySelectorAll('.sim1-item');
        const container = document.getElementById('sim1-preview');
        const classSpan = document.getElementById('sim1-class');

        container.className = "w-full border border-dashed border-white/20 p-4 rounded bg-white/5 gap-2 transition-all duration-500 " + (mode === 'flex' ? 'flex' : 'block');
        classSpan.innerText = mode;
        classSpan.className = mode === 'flex' ? 'text-purple-400 font-bold' : 'text-white/50';

        items.forEach(item => {
            item.className = `w-16 h-16 rounded flex items-center justify-center font-bold text-white mb-2 sim1-item ${mode==='flex'?'mb-0':''} ` + (item.innerHTML==='1'?'bg-purple-600':item.innerHTML==='2'?'bg-indigo-600':'bg-fuchsia-600');
        });
    }

    /* --- SIMULATOR 2: DIRECTION --- */
    function updateSimDir(dir) {
        const target = document.getElementById('sim2-target');
        const code = document.getElementById('sim2-code');
        
        // Handle Wrap Mode vs Direction Mode
        if(dir === 'flex-wrap') {
             target.classList.toggle('flex-wrap');
             code.innerText = target.classList.contains('flex-wrap') ? 'flex-row flex-wrap' : 'flex-row';
        } else {
             target.className = `flex ${dir} gap-2 transition-all duration-500 border border-dashed border-white/20 p-2 rounded w-full`;
             code.innerText = dir;
        }
    }

    /* --- SIMULATOR 3: ALIGNMENT --- */
    let alignState = { justify: 'justify-start', items: 'items-start' };
    function updateSimAlign(type, val) {
        const target = document.getElementById('sim3-target');
        if(type === 'j') { alignState.justify = val; document.getElementById('sim3-label-j').innerText = val; }
        if(type === 'i') { alignState.items = val; document.getElementById('sim3-label-i').innerText = val; }
        
        target.className = `bg-[#0f141e] p-4 rounded-xl border border-white/5 w-full flex h-48 relative z-10 transition-all duration-300 gap-2 ${alignState.justify} ${alignState.items}`;
    }

    /* --- REALTIME CODING (Lesson 33) --- */
    let editor;
    // Broken Navbar Code to Fix
    const starterCode = `<nav class="bg-purple-900 text-white">
  <div class="container mx-auto bg-purple-800">
    
    <div class="font-bold text-xl">BrandLogo</div>
    
    <div class="space-x-4">
      <a href="#" class="hover:text-purple-300">Home</a>
      <a href="#" class="hover:text-purple-300">About</a>
    </div>

    <button class="bg-white text-purple-900 px-4 py-2 rounded">Login</button>

  </div>
</nav>`;

    function initMonaco() {
        require.config({ paths: { vs: 'https://cdn.jsdelivr.net/npm/monaco-editor@0.45.0/min/vs' } });
        require(['vs/editor/editor.main'], function () {
            editor = monaco.editor.create(document.getElementById('codeEditor'), {
                value: starterCode, language: 'html', theme: 'vs-dark', fontSize: 13,
                minimap: { enabled: false }, automaticLayout: true, padding: { top: 16 }, lineNumbers: 'off'
            });
            updatePreview(starterCode);
            editor.onDidChangeModelContent(() => {
                if(activityCompleted) return;
                const code = editor.getValue();
                updatePreview(code);
                validateCode(code);
            });
        });
    }

    function updatePreview(code) {
        const frame = document.getElementById('previewFrame');
        const content = `<!doctype html><html><head><script src="https://cdn.tailwindcss.com"><\/script><style>body { background-color: #f3f4f6; padding: 20px; font-family: sans-serif; }</style></head><body>${code}</body></html>`;
        frame.srcdoc = content;
    }

    function validateCode(code) {
        // Regex to check for flex utilities in the wrapper div
        const flexCheck = /class="[^"]*\bflex\b[^"]*"/;
        const justifyCheck = /class="[^"]*\bjustify-between\b[^"]*"/;
        const alignCheck = /class="[^"]*\bitems-center\b[^"]*"/;
        const paddingCheck = /class="[^"]*\bp-(4|6)\b[^"]*"/;

        const checks = [
            { id: 'check-flex', valid: flexCheck.test(code) },
            { id: 'check-justify', valid: justifyCheck.test(code) },
            { id: 'check-align', valid: alignCheck.test(code) },
            { id: 'check-padding', valid: paddingCheck.test(code) }
        ];

        let passedCount = 0;
        checks.forEach(check => {
            const el = document.getElementById(check.id);
            const dot = el.querySelector('span');
            if (check.valid) {
                el.classList.remove('text-white/50'); el.classList.add('text-emerald-400', 'font-bold');
                dot.innerHTML = '✓'; dot.classList.add('bg-emerald-500', 'border-transparent', 'text-black');
                passedCount++;
            } else {
                el.classList.add('text-white/50'); el.classList.remove('text-emerald-400', 'font-bold');
                dot.innerHTML = ''; dot.classList.remove('bg-emerald-500', 'border-transparent', 'text-black');
            }
        });

        document.getElementById('progressText').innerText = `${passedCount}/4 Selesai`;
        const btn = document.getElementById('submitExerciseBtn');
        if (passedCount === 4) {
            btn.disabled = false; btn.classList.remove('cursor-not-allowed', 'opacity-50');
            btn.innerHTML = `<span>Selesaikan Misi</span><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>`;
        } else {
            btn.disabled = true; btn.classList.add('cursor-not-allowed', 'opacity-50');
        }
    }

    function resetEditor() { if(editor && !activityCompleted) editor.setValue(starterCode); }

    /* --- SYSTEM --- */
    async function submitExercise() {
        const btn = document.getElementById('submitExerciseBtn');
        btn.innerHTML = "Menyimpan..."; btn.disabled = true;
        try {
            await fetch('/activity/complete', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'Content-Type': 'application/json' }, body: JSON.stringify({ activity_id: ACTIVITY_ID, score: 100 }) });
            await saveLessonToDB(ACTIVITY_LESSON_ID); 
            completedSet.add(ACTIVITY_LESSON_ID);
            activityCompleted = true; updateProgressUI(); lockActivityUI(); unlockNextChapter(); 
        } catch(e) { console.error(e); btn.innerHTML = "Gagal. Coba Lagi."; btn.disabled = false; }
    }

    function lockActivityUI() {
        document.getElementById('lockOverlay').classList.remove('hidden');
        if(editor) editor.updateOptions({ readOnly: true });
        const btn = document.getElementById('submitExerciseBtn'); btn.innerText = "Terkunci"; btn.disabled = true;
    }

    function unlockNextChapter() {
        const btn = document.getElementById('nextChapterBtn');
        if(btn) {
            btn.classList.remove('cursor-not-allowed', 'opacity-50', 'pointer-events-none', 'text-slate-500');
            btn.classList.add('text-purple-400', 'hover:text-purple-300', 'cursor-pointer');
            document.getElementById('nextLabel').innerText = "Selanjutnya"; document.getElementById('nextLabel').classList.remove('opacity-50');
            document.getElementById('nextIcon').innerHTML = "→"; document.getElementById('nextIcon').classList.add('bg-purple-500/20', 'border-purple-500/50');
            btn.onclick = () => window.location.href = "{{ route('courses.grid') }}"; 
        }
    }

    function updateProgressUI() {
        const total = window.LESSON_IDS.length; 
        const done = window.LESSON_IDS.filter(id => completedSet.has(id)).length; 
        const percent = Math.round((done / total) * 100);
        document.getElementById('topProgressBar').style.width = percent + '%'; 
        document.getElementById('progressLabelTop').innerText = percent + '%';
        if(percent === 100) unlockNextChapter();
    }

    function initLessonObserver() {
        const obs = new IntersectionObserver(async entries => {
            for (const entry of entries) {
                if (entry.isIntersecting) {
                    const id = Number(entry.target.dataset.lessonId);
                    if (id && entry.target.dataset.type !== 'activity' && !completedSet.has(id)) {
                        try { await saveLessonToDB(id); completedSet.add(id); updateProgressUI(); } catch(e) {}
                    }
                }
            }
        }, { threshold: 0.5, root: document.getElementById('mainScroll') });
        document.querySelectorAll('.lesson-section').forEach(s => obs.observe(s));
    }

    async function saveLessonToDB(id) { await fetch('/lesson/complete', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: new URLSearchParams({ lesson_id: id }) }); }

    function initScrollSpy() {
        const mainScroll = document.getElementById('mainScroll'); const sections = document.querySelectorAll('.lesson-section'); const navLinks = document.querySelectorAll('.sidebar-nav-link');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const id = '#' + entry.target.id;
                    navLinks.forEach(link => {
                        const dot = link.querySelector('.dot-indicator'); if(!dot) return;
                        link.classList.remove('bg-white/5'); dot.classList.remove('bg-purple-500', 'shadow-[0_0_8px_#a855f7]', 'scale-125'); dot.classList.add('bg-slate-600');
                        if (link.dataset.target === id) { link.classList.add('bg-white/5'); dot.classList.remove('bg-slate-600'); dot.classList.add('bg-purple-500', 'shadow-[0_0_8px_#a855f7]', 'scale-125'); }
                    });
                }
            });
        }, { root: mainScroll, rootMargin: '-20% 0px -60% 0px', threshold: 0 });
        sections.forEach(section => observer.observe(section));
    }

    function initSidebarScroll(){const m=document.getElementById('mainScroll');const l=document.querySelectorAll('.accordion-content .nav-item');m.addEventListener('scroll',()=>{let c='';document.querySelectorAll('.lesson-section').forEach(s=>{if(m.scrollTop>=s.offsetTop-250)c='#'+s.id;});l.forEach(k=>{k.classList.remove('active');if(k.getAttribute('data-target')===c)k.classList.add('active')})});l.forEach(k=>k.addEventListener('click',(e)=>{const t=document.querySelector(k.getAttribute('data-target'));if(t)m.scrollTo({top:t.offsetTop-120,behavior:'smooth'})}));}
    function initVisualEffects(){const c=document.getElementById('stars'),x=c.getContext('2d');function r(){c.width=innerWidth;c.height=innerHeight}r();window.onresize=r;let s=[];for(let i=0;i<100;i++)s.push({x:Math.random()*c.width,y:Math.random()*c.height,r:Math.random()*1.2,v:Math.random()*0.2+.1});(function a(){x.clearRect(0,0,c.width,c.height);x.fillStyle='rgba(255,255,255,.3)';s.forEach(t=>{x.beginPath();x.arc(t.x,t.y,t.r,0,6.28);x.fill();t.y+=t.v;if(t.y>c.height)t.y=0});requestAnimationFrame(a)})();$(window).on('mousemove',e=>{$('#cursor-glow').css({left:e.clientX,top:e.clientY})});}
</script>
@endsection