@extends('layouts.landing')
@section('title','Bab 1.3 · Architecture & JIT Engine')

@section('content')
{{-- <div id="courseRoot" class="relative h-screen bg-[#020617] text-white font-sans overflow-hidden flex flex-col selection:bg-purple-500/30"> --}}
<div id="courseRoot" class="relative h-screen bg-[#020617] text-white font-sans overflow-hidden flex flex-col selection:bg-indigo-500/30 pt-20">

    <div class="fixed inset-0 -z-50 pointer-events-none">
        <div id="animated-bg" class="absolute inset-0 opacity-50"></div>
        <div class="absolute top-[-20%] left-[-10%] w-[1000px] h-[1000px] bg-purple-900/10 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute bottom-[-20%] right-[-10%] w-[800px] h-[800px] bg-indigo-900/10 rounded-full blur-[100px]"></div>
        <canvas id="stars" class="absolute inset-0 pointer-events-none"></canvas>
        <div id="noise-overlay" class="absolute inset-0 z-10 opacity-[0.03]"></div>
        <div id="cursor-glow"></div>
    </div>

               @include('layouts.partials.navbar')


    <div class="flex flex-1 overflow-hidden relative z-20">

        @include('layouts.partials.course-sidebar')

        <main id="mainScroll" class="flex-1 h-full overflow-y-auto scroll-smooth relative bg-transparent custom-scrollbar scroll-padding-top-24">
            
            <div id="stickyHeader" class="sticky top-0 z-30 w-full bg-[#020617]/90 backdrop-blur-2xl border-b border-white/5 px-8 py-4 flex items-center justify-between transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-purple-500/20 to-transparent border border-purple-500/20 flex items-center justify-center font-bold text-xs text-purple-400">1.3</div>
                    <div>
                        <h1 class="text-sm font-bold text-white">Struktur & Latar Belakang</h1>
                        <p class="text-[10px] text-white/50">History, Layers & JIT Engine</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="hidden sm:block w-32 h-1.5 bg-white/10 rounded-full overflow-hidden">
                        <div id="topProgressBar" class="h-full bg-gradient-to-r from-purple-500 to-indigo-500 w-0 transition-all duration-500 shadow-[0_0_10px_#a855f7]"></div>
                    </div>
                    <span id="progressLabelTop" class="text-purple-400 font-bold text-xs">0%</span>
                </div>
            </div>

            <div class="p-6 lg:p-16 max-w-5xl mx-auto pb-40">
                
                <div class="mb-24">
                    <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
                        Tujuan Pembelajaran
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <div class="bg-[#1e1e1e] border border-white/5 p-5 rounded-xl flex items-start gap-4 hover:border-purple-500/30 transition group h-full">
                            <div class="w-8 h-8 rounded bg-purple-500/10 text-purple-400 flex items-center justify-center shrink-0 font-bold text-xs">1</div>
                            <div><h4 class="text-sm font-bold text-white mb-1">Sejarah CSS</h4><p class="text-[11px] text-white/50 leading-relaxed">Analisis masalah skalabilitas CSS tradisional.</p></div>
                        </div>
                        <div class="bg-[#1e1e1e] border border-white/5 p-5 rounded-xl flex items-start gap-4 hover:border-fuchsia-500/30 transition group h-full">
                            <div class="w-8 h-8 rounded bg-fuchsia-500/10 text-fuchsia-400 flex items-center justify-center shrink-0 font-bold text-xs">2</div>
                            <div><h4 class="text-sm font-bold text-white mb-1">Architecture</h4><p class="text-[11px] text-white/50 leading-relaxed">Hierarki Base, Components, dan Utilities.</p></div>
                        </div>
                        <div class="bg-[#1e1e1e] border border-white/5 p-5 rounded-xl flex items-start gap-4 hover:border-indigo-500/30 transition group h-full">
                            <div class="w-8 h-8 rounded bg-indigo-500/10 text-indigo-400 flex items-center justify-center shrink-0 font-bold text-xs">3</div>
                            <div><h4 class="text-sm font-bold text-white mb-1">JIT Engine</h4><p class="text-[11px] text-white/50 leading-relaxed">Cara kerja compiler dan arbitrary values.</p></div>
                        </div>
                        <div class="bg-gradient-to-br from-purple-900/40 to-indigo-900/40 border border-purple-500/30 p-5 rounded-xl flex items-start gap-4 hover:shadow-[0_0_20px_rgba(168,85,247,0.2)] transition group h-full col-span-3">
                            <div class="w-8 h-8 rounded bg-white/10 text-white flex items-center justify-center shrink-0 font-bold text-xs">🏁</div>
                            <div><h4 class="text-sm font-bold text-white mb-1">JIT Precision Challenge</h4><p class="text-[11px] text-white/70 leading-relaxed">Implementasi nilai presisi (arbitrary) menggunakan JIT.</p></div>
                        </div>
                    </div>
                </div>

                <article class="space-y-40">
                    
                    <section id="section-12" class="lesson-section scroll-mt-32" data-lesson-id="12">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-purple-500 pl-6">
                                <span class="text-purple-400 font-mono text-xs uppercase tracking-widest">Lesson 1.3.1</span>
                                <h2 class="text-4xl lg:text-5xl font-black text-white leading-[1.1]">
                                    Krisis Skalabilitas <br> 
                                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-indigo-500">CSS Tradisional</span>
                                </h2>
                            </div>
                            
                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-white flex items-center gap-2"><span class="w-6 h-6 rounded bg-purple-600 flex items-center justify-center text-[10px] text-white">A</span> The Append-Only Problem (Masalah CSS Bengkak)</h3>
                                <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed space-y-4">
                                    <p>
                                        Dalam pengembangan web tradisional, setiap kali Anda membuat fitur baru, Anda menulis baris CSS baru. Misalnya, saat membuat tombol "Login", Anda menulis kelas <code>.btn-login</code>. Saat membuat tombol "Register" yang sedikit berbeda, Anda membuat <code>.btn-register</code>.
                                    </p>
                                    <p>
                                        Masalah utamanya adalah: <strong>Pengembang takut menghapus kode lama</strong>. Karena sifat CSS yang global (cascading), menghapus satu baris kode bisa merusak tampilan di halaman yang tidak terduga. Akibatnya, file CSS terus bertambah besar (Append-Only) hingga mencapai ratusan kilobyte. Ini memperlambat loading website secara signifikan. Tailwind memecahkan ini dengan menyediakan utilitas yang dapat digunakan ulang tanpa batas, sehingga file CSS berhenti tumbuh setelah titik tertentu (plateau).
                                    </p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-white flex items-center gap-2"><span class="w-6 h-6 rounded bg-indigo-600 flex items-center justify-center text-[10px] text-white">B</span> Semantic Naming Fatigue (Kelelahan Penamaan)</h3>
                                <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed space-y-4">
                                    <p>
                                        "Ada dua hal tersulit dalam ilmu komputer: invalidasi cache dan memberi nama." - Phil Karlton.
                                    </p>
                                    <p>
                                        Dalam metodologi seperti BEM (Block Element Modifier), Anda menghabiskan energi mental yang besar hanya untuk menamai sebuah kotak pembungkus. Apakah ini <code>.card-inner-wrapper</code>? Atau <code>.profile-body-container</code>? Atau <code>.sidebar-left-box</code>? Proses ini memecah fokus (Context Switching). Dengan Tailwind, beban kognitif ini hilang sepenuhnya. Anda tidak perlu memberi nama abstrak. Anda cukup mendeskripsikan tampilannya secara harfiah: <code>flex p-4 bg-white</code>.
                                    </p>
                                </div>
                            </div>

                            <div class="bg-[#1e1e1e] border border-white/10 rounded-xl overflow-hidden shadow-2xl p-8 relative">
                                <h4 class="text-xs font-bold text-white/50 uppercase mb-8 text-center">Simulasi: Traditional vs Tailwind Scaling</h4>
                                <div class="flex flex-col gap-6">
                                    <div>
                                        <div class="flex justify-between text-xs mb-2">
                                            <span class="text-red-400 font-bold">Traditional CSS (Terus Tumbuh)</span>
                                            <span id="trad-size" class="text-white">10 KB</span>
                                        </div>
                                        <div class="w-full h-4 bg-white/5 rounded-full overflow-hidden relative">
                                            <div id="bar-trad" class="h-full bg-red-600 w-[5%] transition-all duration-1000"></div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex justify-between text-xs mb-2">
                                            <span class="text-emerald-400 font-bold">Tailwind CSS (Stabil/Plateau)</span>
                                            <span id="tw-size" class="text-white">10 KB</span>
                                        </div>
                                        <div class="w-full h-4 bg-white/5 rounded-full overflow-hidden relative">
                                            <div id="bar-tw" class="h-full bg-emerald-600 w-[5%] transition-all duration-1000"></div>
                                        </div>
                                    </div>
                                    <div class="flex justify-center mt-6">
                                        <button onclick="simulateGrowth()" id="growBtn" class="px-6 py-2 bg-purple-600 hover:bg-purple-500 text-white rounded-lg font-bold text-sm transition shadow-lg">
                                            + Tambah 10 Fitur Baru
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="section-13" class="lesson-section scroll-mt-32" data-lesson-id="13">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-purple-500 pl-6">
                                <span class="text-purple-400 font-mono text-xs uppercase tracking-widest">Lesson 1.3.2</span>
                                <h2 class="text-4xl lg:text-5xl font-black text-white leading-[1.1]">
                                    Arsitektur <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-indigo-500">Tiga Lapisan</span>
                                </h2>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-white flex items-center gap-2"><span class="w-6 h-6 rounded bg-purple-600 flex items-center justify-center text-[10px] text-white">A</span> The Cascade Layers (@layer)</h3>
                                <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed space-y-4">
                                    <p>
                                        CSS bekerja berdasarkan prioritas (Cascading). Untuk menghindari konflik, Tailwind mengorganisir CSS-nya ke dalam 3 lapisan (bucket) utama yang disuntikkan melalui direktif <code>@tailwind</code>.
                                    </p>
                                    <ul class="space-y-2 list-none pl-0 mt-4 text-sm font-mono bg-black/20 p-4 rounded border border-white/5">
                                        <li class="text-white/50">1. @tailwind <span class="text-red-400">base</span>; <span class="text-gray-500">// Reset browser (Preflight).</span></li>
                                        <li class="text-white/50">2. @tailwind <span class="text-yellow-400">components</span>; <span class="text-gray-500">// Kelas abstrak (.btn, .card).</span></li>
                                        <li class="text-white/50">3. @tailwind <span class="text-emerald-400">utilities</span>; <span class="text-gray-500">// Kelas atomik (p-4, flex).</span></li>
                                    </ul>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-white flex items-center gap-2"><span class="w-6 h-6 rounded bg-indigo-600 flex items-center justify-center text-[10px] text-white">B</span> Why Order Matters?</h3>
                                <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed space-y-4">
                                    <p>
                                        Urutan ini sangat krusial. <strong>Utilities</strong> harus selalu berada di paling bawah (terakhir dimuat) agar bisa menimpa gaya dari <strong>Components</strong> atau <strong>Base</strong>.
                                    </p>
                                    <p>
                                        Contoh: Jika Anda punya komponen <code>.btn</code> (padding 10px). Jika Anda ingin menimpanya di HTML dengan <code>&lt;button class="btn p-8"&gt;</code>, maka <code>p-8</code> harus didefinisikan SETELAH <code>.btn</code> dalam CSS. Jika tidak, gaya utilitas tidak akan bekerja.
                                    </p>
                                </div>
                            </div>

                            <div class="bg-[#0b0f19] border border-white/10 rounded-xl p-8 relative overflow-hidden flex flex-col items-center">
                                <div class="relative w-full max-w-md h-64 flex flex-col justify-end items-center gap-2 perspective-1000">
                                    <div id="layer-util" class="w-full bg-emerald-900/40 border border-emerald-500/50 p-4 rounded-lg text-center text-emerald-300 font-bold shadow-lg transform transition-all translate-y-0 opacity-50 cursor-pointer hover:opacity-100 hover:-translate-y-2 z-30" onclick="highlightLayer('util')">UTILITIES (Tertinggi)</div>
                                    <div id="layer-comp" class="w-[95%] bg-yellow-900/40 border border-yellow-500/50 p-4 rounded-lg text-center text-yellow-300 font-bold shadow-lg transform transition-all translate-y-0 opacity-50 cursor-pointer hover:opacity-100 hover:-translate-y-2 z-20" onclick="highlightLayer('comp')">COMPONENTS</div>
                                    <div id="layer-base" class="w-[90%] bg-red-900/40 border border-red-500/50 p-4 rounded-lg text-center text-red-300 font-bold shadow-lg transform transition-all translate-y-0 opacity-50 cursor-pointer hover:opacity-100 hover:-translate-y-2 z-10" onclick="highlightLayer('base')">BASE (Terendah)</div>
                                </div>
                                <div id="layer-desc" class="mt-6 text-sm text-white/60 text-center min-h-[50px] bg-white/5 p-4 rounded-lg w-full max-w-md border border-white/10">
                                    Klik layer di atas untuk melihat detail fungsinya.
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="section-14" class="lesson-section scroll-mt-32" data-lesson-id="14">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-purple-500 pl-6">
                                <span class="text-purple-400 font-mono text-xs uppercase tracking-widest">Lesson 1.3.3</span>
                                <h2 class="text-4xl lg:text-5xl font-black text-white leading-[1.1]">
                                    Mesin <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-indigo-500">JIT (Just-In-Time)</span>
                                </h2>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-white flex items-center gap-2"><span class="w-6 h-6 rounded bg-purple-600 flex items-center justify-center text-[10px] text-white">A</span> Generating on Demand</h3>
                                <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed space-y-4">
                                    <p>
                                        Mesin <strong>JIT (Just-In-Time)</strong> Tailwind bekerja seperti scanner pintar. Ia memantau file HTML Anda secara real-time saat Anda mengetik.
                                    </p>
                                    <p>
                                        Jika Anda menulis <code>text-red-500</code>, JIT langsung membuat kode CSS untuk kelas tersebut detik itu juga. Jika Anda menghapusnya, CSS-nya pun hilang. Ini menjaga file CSS Anda sekecil mungkin (biasanya di bawah 10KB di produksi).
                                    </p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-white flex items-center gap-2"><span class="w-6 h-6 rounded bg-indigo-600 flex items-center justify-center text-[10px] text-white">B</span> Arbitrary Values (Nilai Bebas)</h3>
                                <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed space-y-4">
                                    <p>
                                        Karena JIT membuat CSS secara langsung, ia memungkinkan fitur revolusioner: <strong>Arbitrary Values</strong>.
                                    </p>
                                    <p>
                                        Misalkan desainer meminta lebar tepat <strong>327px</strong> (yang tidak ada di skala standar Tailwind). Anda tidak perlu membuat CSS kustom. Cukup tulis <code>w-[327px]</code>. JIT mengenali kurung siku <code>[]</code> dan membuat kelas unik untuk Anda.
                                    </p>
                                </div>
                            </div>

                            <div class="bg-[#1e1e1e] border border-white/10 rounded-xl overflow-hidden shadow-2xl flex flex-col md:flex-row h-[350px]">
                                <div class="w-full md:w-1/2 p-6 flex flex-col gap-4">
                                    <h4 class="text-xs font-bold text-white/50 uppercase">HTML Input (Scanner)</h4>
                                    <div class="flex-1 bg-black/30 border border-white/10 rounded p-4 relative group">
                                        <textarea id="jit-input" oninput="runJit()" class="w-full h-full bg-transparent text-sm font-mono text-cyan-300 outline-none resize-none placeholder-white/20 z-10 relative leading-loose" placeholder="Ketik class di sini...&#10;Contoh:&#10;p-10&#10;bg-[#ff00ff]&#10;w-[325px]"></textarea>
                                        <div id="scan-line" class="absolute top-0 left-0 w-full h-0.5 bg-purple-500 shadow-[0_0_10px_#a855f7] opacity-0 transition-opacity"></div>
                                    </div>
                                </div>
                                <div class="w-full md:w-1/2 bg-[#111] p-6 border-l border-white/5 flex flex-col gap-4">
                                    <div class="flex justify-between items-center">
                                        <h4 class="text-xs font-bold text-purple-400 uppercase">Generated CSS Output</h4>
                                        <div id="jit-badge" class="px-2 py-0.5 bg-gray-800 text-[10px] rounded text-gray-500 transition-colors">Idle</div>
                                    </div>
                                    <div id="jit-output" class="flex-1 font-mono text-xs text-white/70 overflow-auto whitespace-pre leading-relaxed bg-[#0a0a0a] p-4 rounded border border-white/5">
                                        /* Menunggu input... */
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="section-15" class="lesson-section scroll-mt-32 pt-10 border-t border-white/10" data-lesson-id="15" data-type="activity">
                        <div class="relative rounded-[2rem] bg-[#0b0f19] border border-white/10 p-8 overflow-hidden shadow-2xl group hover:border-purple-500/30 transition-all duration-500">
                            
                            <div class="absolute -top-24 -right-24 w-64 h-64 bg-purple-600/20 blur-[100px] rounded-full pointer-events-none"></div>

                            <div class="flex items-center gap-4 mb-6 relative z-10">
                                <div class="p-3 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl text-white shadow-lg shadow-purple-500/30">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold text-white">JIT Precision Challenge</h2>
                                    <p class="text-purple-300 text-sm">Gunakan <strong>Arbitrary Values</strong> untuk memenuhi spesifikasi desain Pixel Perfect berikut.</p>
                                </div>
                            </div>

                            <div class="grid lg:grid-cols-2 gap-6 max-w-6xl mx-auto h-[500px]">
                                
                                <div class="bg-[#1e1e1e] rounded-xl border border-white/10 flex flex-col overflow-hidden h-full relative shadow-2xl">
                                    
                                    <div id="lockOverlay" class="hidden absolute inset-0 bg-[#050912]/95 backdrop-blur-md z-50 flex flex-col items-center justify-center text-center p-6 border-4 border-emerald-500/20 m-1 rounded-lg">
                                        <div class="w-24 h-24 bg-emerald-500/10 rounded-full flex items-center justify-center mb-6 border border-emerald-500/50 shadow-[0_0_50px_rgba(16,185,129,0.3)] animate-bounce">
                                            <svg class="w-12 h-12 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                        </div>
                                        <h3 class="text-3xl font-black text-white mb-2 tracking-tight">PIXEL PERFECT!</h3>
                                        <p class="text-base text-white/60 mb-8 max-w-xs">Anda telah menguasai kekuatan JIT Engine.</p>
                                        <button disabled class="px-8 py-3 rounded-full bg-white/5 border border-white/10 text-white/30 text-xs font-bold cursor-not-allowed uppercase tracking-widest">Review Mode</button>
                                    </div>

                                    <div class="bg-[#0f141e] px-4 py-2 border-b border-white/5 flex justify-between items-center">
                                        <div class="flex gap-2 text-xs font-mono text-white/50">
                                            <span>Target Spesifik:</span>
                                        </div>
                                        <button onclick="resetEditor()" class="text-[10px] text-purple-400 hover:text-purple-300 transition uppercase font-bold">Reset</button>
                                    </div>
                                    
                                    <div id="codeEditor" class="flex-1 w-full"></div>

                                    <div class="p-4 bg-[#0f141e] border-t border-white/5">
                                        <div class="text-[11px] font-mono text-white/50 mb-3 flex flex-col gap-1" id="validation-list">
                                            <div id="chk-w">❌ Lebar ( Width )harus tepat 327px </div>
                                            <div id="chk-h">❌ Tinggi ( Height ) harus tepat 200px </div>
                                            <div id="chk-bg">❌ Warna ( Background ) Hex harus #5b21b6 </div>
                                        </div>
                                        <button id="submitExerciseBtn" onclick="submitExercise()" disabled class="w-full py-3 rounded-lg bg-emerald-600 text-white font-bold text-xs shadow-lg shadow-emerald-500/20 hover:bg-emerald-500 transition-all cursor-not-allowed opacity-50 flex items-center justify-center gap-2">
                                            <span>Validasi Kode JIT</span>
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                        </button>
                                    </div>
                                </div>

                                <div class="bg-white rounded-xl border border-white/10 flex-1 flex flex-col relative overflow-hidden min-h-[500px]">
                                    <div class="bg-gray-100 px-4 py-2 border-b border-gray-200 flex items-center justify-between">
                                        <span class="text-[10px] text-gray-500 font-mono">Browser Preview</span>
                                        <span class="text-[10px] bg-green-100 text-green-600 px-2 py-0.5 rounded border border-green-200">Live</span>
                                    </div>
                                    <div class="flex-1 bg-gray-50 p-8 flex items-center justify-center relative overflow-hidden">
                                        <iframe id="previewFrame" class="w-full h-full border-0"></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                </article>

                <div class="mt-32 pt-10 border-t border-white/10 flex justify-between items-center">
                    <a href="{{ route('courses.tailwindcss') }}" class="group flex items-center gap-3 text-slate-400 hover:text-white transition">
                        <div class="w-10 h-10 rounded-full border border-white/10 flex items-center justify-center group-hover:border-white/30 transition">←</div>
                        <div class="text-left"><div class="text-[10px] uppercase tracking-widest opacity-50">Sebelumnya</div><div class="font-bold text-sm">Konsep Dasar</div></div>
                    </a>
                    
                    <div id="nextChapterBtn" class="group flex items-center gap-3 text-right text-slate-500 cursor-not-allowed opacity-50 pointer-events-none transition-all duration-500">
                        <div class="text-right">
                            <div id="nextLabel" class="text-[10px] uppercase tracking-widest opacity-50">Terkunci</div>
                            <div class="font-bold text-sm">Penerapan Utility </div>
                        </div>
                        <div id="nextIcon" class="w-10 h-10 rounded-full border border-white/5 flex items-center justify-center bg-white/5">🔒</div>
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>

<style>
    .nav-link.active { color: #22d3ee; position: relative; }
    .nav-link.active::after { content: ''; position: absolute; left: 0; bottom: -6px; width: 100%; height: 2px; background: linear-gradient(to right,#22d3ee,#0ea5e9); box-shadow: 0 0 12px rgba(34,211,238,.8); border-radius: 2px; }
    .custom-scrollbar::-webkit-scrollbar { width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
    #animated-bg{ background: radial-gradient(800px circle at 20% 20%, rgba(34,211,238,.15), transparent 40%), radial-gradient(800px circle at 80% 80%, rgba(14,165,233,.15), transparent 40%); animation:bgMove 20s ease-in-out infinite alternate; }
    @keyframes bgMove{to{transform:scale(1.15)}}
    @keyframes shake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-5px); } 75% { transform: translateX(5px); } }
    .shake { animation: shake 0.4s ease-in-out; }
    .nav-item.active { color: #22d3ee; background: rgba(34,211,238,0.05); font-weight: 600; }
    .nav-item.active .dot { background: #22d3ee; box-shadow: 0 0 8px #22d3ee; transform: scale(1.2); }
</style>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/monaco-editor@0.45.0/min/vs/loader.js"></script>

<script>
    /* --- CONFIGURATION --- */
    window.LESSON_IDS = [12, 13, 14, 15]; 
    window.COMPLETED_IDS = {!! json_encode($completedLessonIds ?? []) !!};
    let completedSet = new Set(window.COMPLETED_IDS);
    
    // Activity ID 3 = JIT Precision Challenge
    let activityCompleted = {!! ($activityCompleted ?? false) ? 'true' : 'false' !!};
    const ACTIVITY_ID = 3; 
    const ACTIVITY_LESSON_ID = 15; 

    document.addEventListener('DOMContentLoaded', () => {
        initScrollSpy();
        initLessonObserver();
        initVisualEffects();
        initSidebarScroll();
        updateProgressUI();
        
        // Init Simulators
        simulateGrowth();
        highlightLayer('util'); // Default highlight
        initMonaco();

        if (activityCompleted) {
            lockActivityUI();
            unlockNextChapter();
        }
    });

    /* --- SIMULATOR 1: FILE SIZE GROWTH --- */
    function simulateGrowth() {
        const barTrad = document.getElementById('bar-trad');
        const txtTrad = document.getElementById('trad-size');
        const barTw = document.getElementById('bar-tw');
        const txtTw = document.getElementById('tw-size');
        const btn = document.getElementById('growBtn');
        
        btn.disabled = true;
        btn.innerHTML = "Menambahkan Fitur...";

        let tradW = parseInt(barTrad.style.width) || 5;
        let twW = parseInt(barTw.style.width) || 5;

        // Logic Linear vs Plateau
        let newTradW = tradW + 15;
        if(newTradW > 100) newTradW = 100;

        let newTwW = twW;
        if(twW < 20) newTwW = twW + 5; 
        else newTwW = twW + 1; // Plateau effect

        barTrad.style.width = newTradW + '%';
        txtTrad.innerText = (newTradW * 5) + ' KB';

        barTw.style.width = newTwW + '%';
        txtTw.innerText = (newTwW * 2) + ' KB';

        setTimeout(() => {
            btn.disabled = false;
            btn.innerHTML = "+ Tambah 10 Fitur Baru";
            if(newTradW >= 100) {
                btn.innerHTML = "Reset Simulasi";
                btn.onclick = () => {
                    barTrad.style.width = '5%'; txtTrad.innerText = '10 KB';
                    barTw.style.width = '5%'; txtTw.innerText = '10 KB';
                    btn.onclick = simulateGrowth;
                    btn.innerHTML = "+ Tambah 10 Fitur Baru";
                };
            }
        }, 800);
    }

    /* --- SIMULATOR 2: LAYER STACK --- */
    function highlightLayer(layer) {
        ['base', 'comp', 'util'].forEach(l => {
            const el = document.getElementById('layer-'+l);
            el.classList.remove('opacity-100', 'scale-105', '-translate-y-2', 'ring-2', 'ring-white');
            el.classList.add('opacity-50', 'translate-y-0');
        });
        
        const active = document.getElementById('layer-'+layer);
        active.classList.remove('opacity-50', 'translate-y-0');
        active.classList.add('opacity-100', 'scale-105', '-translate-y-2', 'ring-2', 'ring-white');

        const desc = document.getElementById('layer-desc');
        if(layer === 'base') desc.innerHTML = "<strong class='text-red-400'>BASE:</strong> Reset bawaan browser (Preflight). Prioritas paling rendah. Dimuat pertama.";
        if(layer === 'comp') desc.innerHTML = "<strong class='text-yellow-400'>COMPONENTS:</strong> Kelas abstrak seperti <code>.card</code> atau <code>.btn</code>. Menimpa Base.";
        if(layer === 'util') desc.innerHTML = "<strong class='text-emerald-400'>UTILITIES:</strong> Kelas atomik (p-4, flex). Prioritas TERTINGGI (Penting!). Dimuat terakhir.";
    }

    /* --- SIMULATOR 3: JIT COMPILER --- */
    function runJit() {
        const input = document.getElementById('jit-input').value;
        const output = document.getElementById('jit-output');
        const badge = document.getElementById('jit-badge');
        const scanLine = document.getElementById('scan-line');
        
        badge.innerText = "Compiling...";
        badge.className = "px-2 py-0.5 bg-yellow-500/20 text-yellow-400 text-[10px] rounded animate-pulse font-mono";
        scanLine.style.opacity = 1;
        scanLine.style.top = Math.random() * 100 + '%';

        setTimeout(() => {
            let css = '';
            if(!input) {
                css = '<div class="text-white/20 italic">// Menunggu input...</div>';
            } else {
                const classes = input.split(/[\s\n]+/);
                classes.forEach(cls => {
                    const clean = cls.trim();
                    if(clean) {
                        if(clean.startsWith('bg-[')) {
                            const val = clean.match(/\[(.*?)\]/)[1];
                            css += `<div>.<span class="text-purple-400">${clean.replace('[','\\[').replace(']','\\]')}</span> { background-color: ${val}; }</div>`;
                        } else if(clean.startsWith('w-[')) {
                            const val = clean.match(/\[(.*?)\]/)[1];
                            css += `<div>.<span class="text-purple-400">${clean.replace('[','\\[').replace(']','\\]')}</span> { width: ${val}; }</div>`;
                        } else if(clean.startsWith('p-')) {
                            const val = clean.split('-')[1] * 0.25;
                            css += `<div>.<span class="text-purple-400">${clean}</span> { padding: ${val}rem; }</div>`;
                        } else {
                            css += `<div>.<span class="text-purple-400">${clean}</span> { /* Generated */ }</div>`;
                        }
                    }
                });
            }
            output.innerHTML = css;
            badge.innerText = "Done";
            badge.className = "px-2 py-0.5 bg-green-500/20 text-green-400 text-[10px] rounded font-mono";
            scanLine.style.opacity = 0;
        }, 400);
    }


    /* --- FINAL ACTIVITY: JIT CHALLENGE (MONACO) --- */
    let editor;
    const starterCode = `<div class="">
  </div>
`;

    function initMonaco() {
        require.config({ paths: { vs: 'https://cdn.jsdelivr.net/npm/monaco-editor@0.45.0/min/vs' } });
        require(['vs/editor/editor.main'], function () {
            editor = monaco.editor.create(document.getElementById('codeEditor'), {
                value: starterCode, language: 'html', theme: 'vs-dark', fontSize: 13,
                minimap: { enabled: false }, automaticLayout: true, padding: { top: 16 }
            });
            updatePreview(starterCode);
            editor.onDidChangeModelContent(() => {
                if(activityCompleted) return;
                const code = editor.getValue();
                updatePreview(code);
                validateConfig(code);
            });
        });
    }

    function updatePreview(code) {
        const frame = document.getElementById('previewFrame');
        const content = `<!doctype html><html><head><script src="https://cdn.tailwindcss.com"><\/script><style>body { background-color: #0f172a; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }</style></head><body>${code}</body></html>`;
        frame.srcdoc = content;
    }

    function validateConfig(code) {
        const checks = [
            { id: 'chk-w', regex: /w-\[327px\]/ },
            { id: 'chk-h', regex: /h-\[200px\]/ },
            { id: 'chk-bg', regex: /bg-\[#5b21b6\]/ }
        ];

        let passed = 0;
        checks.forEach(c => {
            const el = document.getElementById(c.id);
            if(c.regex.test(code)) {
                el.classList.remove('text-white/50'); el.classList.add('text-emerald-400');
                el.innerText = "✅ " + el.innerText.substring(2);
                passed++;
            } else {
                el.classList.add('text-white/50'); el.classList.remove('text-emerald-400');
                el.innerText = "❌ " + el.innerText.substring(2);
            }
        });

        const btn = document.getElementById('submitExerciseBtn');
        if (passed === 3) {
            btn.disabled = false;
            btn.classList.remove('cursor-not-allowed', 'opacity-50');
        } else {
            btn.disabled = true;
            btn.classList.add('cursor-not-allowed', 'opacity-50');
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
        } catch(e) { console.error(e); btn.innerHTML = "Gagal."; btn.disabled = false; }
    }

    function lockActivityUI() {
        document.getElementById('lockOverlay').classList.remove('hidden');
        if(editor) editor.updateOptions({ readOnly: true });
        const btn = document.getElementById('submitExerciseBtn'); btn.innerText = "Terkunci"; btn.disabled = true;
        if(editor) editor.setValue(`<div class="w-[327px] h-[200px] bg-[#5b21b6] rounded-[20px]"></div>`);
    }

    function unlockNextChapter() {
        const btn = document.getElementById('nextChapterBtn');
        if(btn) {
            btn.classList.remove('cursor-not-allowed', 'opacity-50', 'pointer-events-none', 'text-slate-500');
            btn.classList.add('text-purple-400', 'hover:text-purple-300', 'cursor-pointer');
            document.getElementById('nextLabel').innerText = "Selanjutnya"; document.getElementById('nextLabel').classList.remove('opacity-50');
            document.getElementById('nextIcon').innerHTML = "→"; document.getElementById('nextIcon').classList.add('bg-purple-500/20', 'border-purple-500/50');
            btn.onclick = () => window.location.href = "{{ route('courses.implementation') }}"; 
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

    // Standard Observers
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
                        link.classList.remove('bg-white/5'); dot.classList.remove('bg-cyan-500', 'shadow-[0_0_8px_#22d3ee]', 'scale-125'); dot.classList.add('bg-slate-600');
                        if (link.dataset.target === id) { link.classList.add('bg-white/5'); dot.classList.remove('bg-slate-600'); dot.classList.add('bg-cyan-500', 'shadow-[0_0_8px_#22d3ee]', 'scale-125'); }
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