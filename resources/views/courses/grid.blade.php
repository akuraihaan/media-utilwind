@extends('layouts.landing')
@section('title','Layouting dengan Grid')

@section('content')

{{-- KONFIGURASI TEMA AWAL UNTUK MENCEGAH FOUC --}}
<script>
    if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
</script>

<style>
    /* --- THEME CONFIG (DYNAMIC GLASSMORPHISM) --- */
    :root { 
        --bg-main: #f8fafc;
        --text-main: #0f172a;
        --glass-bg: rgba(255, 255, 255, 0.85); 
        --glass-border: rgba(0, 0, 0, 0.05);
        --glass-header: rgba(255, 255, 255, 0.85);
        --card-bg: #ffffff;
        --card-hover: rgba(0, 0, 0, 0.02);
        --border-color: rgba(0, 0, 0, 0.1);
        --text-muted: #64748b;
        --text-heading: #0f172a;
        --code-bg: #f1f5f9;
        --simulator-bg: #ffffff;
        --accent: #06b6d4; /* Cyan 500 */
        --accent-glow: rgba(6, 182, 212, 0.3);
    }

    .dark {
        --bg-main: #020617;
        --text-main: #e2e8f0;
        --glass-bg: rgba(10, 14, 23, 0.85); 
        --glass-border: rgba(255, 255, 255, 0.05);
        --glass-header: rgba(2, 6, 23, 0.80);
        --card-bg: #1e1e1e;
        --card-hover: rgba(255, 255, 255, 0.02);
        --border-color: rgba(255, 255, 255, 0.1);
        --text-muted: rgba(255, 255, 255, 0.5);
        --text-heading: #ffffff;
        --code-bg: #252525;
        --simulator-bg: #0b0f19;
        --accent-glow: rgba(6, 182, 212, 0.5);
    }

    body { font-family: 'Inter', sans-serif; background-color: var(--bg-main); color: var(--text-main); transition: background-color 0.4s ease, color 0.4s ease; }
    .font-mono { font-family: 'JetBrains Mono', monospace; }

    /* UTILITIES ADAPTIF */
    .bg-adaptive { background-color: var(--bg-main); transition: background-color 0.4s ease; }
    .text-adaptive { color: var(--text-main); transition: color 0.4s ease; }
    .text-heading { color: var(--text-heading); transition: color 0.4s ease; }
    .text-muted { color: var(--text-muted); transition: color 0.4s ease; }
    .border-adaptive { border-color: var(--border-color); transition: border-color 0.4s ease; }
    .card-adaptive { background-color: var(--card-bg); border-color: var(--glass-border); transition: all 0.3s; }
    .card-adaptive:hover { border-color: var(--accent-glow); }
    .sim-bg-adaptive { background-color: var(--simulator-bg); transition: background-color 0.4s ease; }
    .code-adaptive { background-color: var(--code-bg); border-color: var(--glass-border); transition: all 0.4s ease; }

    /* SCROLLBAR */
    .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(150,150,150,0.3); border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: var(--accent); }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }
    
    /* ANIMATIONS & EFFECTS */
    #animated-bg { 
        background: radial-gradient(800px circle at 20% 20%, rgba(6,182,212,.08), transparent 40%), 
                    radial-gradient(800px circle at 80% 80%, rgba(59,130,246,.08), transparent 40%); 
        animation: bgMove 20s ease-in-out infinite alternate; 
    }
    .dark #animated-bg {
        background: radial-gradient(800px circle at 20% 20%, rgba(6,182,212,.15), transparent 40%), 
                    radial-gradient(800px circle at 80% 80%, rgba(59,130,246,.15), transparent 40%); 
    }
    @keyframes bgMove{to{transform:scale(1.15)}}
    @keyframes shake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-5px); } 75% { transform: translateX(5px); } }
    .shake { animation: shake 0.4s ease-in-out; }
    
    /* MOBILE SIDEBAR NO-BLUR FIX */
    @media (max-width: 1023px) {
        #courseSidebar {
            position: fixed;
            top: 64px;
            left: -100%;
            height: calc(100vh - 64px);
            transition: left 0.3s ease-in-out;
        }
        #courseSidebar.mobile-open { left: 0; box-shadow: 10px 0 30px rgba(0,0,0,0.5); }
        #mobileOverlay { display: none; position: fixed; inset: 0; top: 64px; background: rgba(0,0,0,0.6); z-index: 30; }
        #mobileOverlay.show { display: block; }
    }
    
    /* SCROLLSPY SIDEBAR ACTIVE */
    .nav-item { display: flex; width: 100%; text-align: left; align-items: center; gap: 12px; padding: 10px 14px; font-size: 0.85rem; color: var(--text-muted); border-radius: 8px; transition: all 0.2s; position: relative; }
    .nav-item:hover { color: var(--text-main); background: var(--card-hover); }
    .nav-item.active { color: #06b6d4; background: rgba(6, 182, 212, 0.05); font-weight: 600; }
    .dot { width: 6px; height: 6px; border-radius: 50%; background: #94a3b8; transition: all 0.3s; }
    .dark .dot { background: #334155; }
    .nav-item.active .dot { background: #06b6d4; box-shadow: 0 0 8px #06b6d4; transform: scale(1.2); }
</style>



<div id="courseRoot" class="relative h-screen bg-slate-50 dark:bg-[#020617] text-slate-800 dark:text-white font-sans overflow-hidden flex flex-col selection:bg-cyan-500/30 pt-20 transition-colors duration-500">

    {{-- BACKGROUND COSMIC LAYER --}}
    <div class="fixed inset-0 -z-50 pointer-events-none">
        <div id="animated-bg" class="absolute inset-0 opacity-50 transition-opacity duration-500"></div>
        <div class="absolute top-[-10%] right-[-10%] w-[600px] h-[600px] bg-cyan-300/30 dark:bg-cyan-900/10 rounded-full blur-[120px] animate-pulse transition-colors duration-500"></div>
        <div class="absolute bottom-[-10%] left-[-10%] w-[500px] h-[500px] bg-blue-300/30 dark:bg-blue-900/10 rounded-full blur-[100px] transition-colors duration-500"></div>
        <canvas id="stars" class="absolute inset-0 pointer-events-none opacity-0 dark:opacity-100 transition-opacity duration-500"></canvas>
        <div id="cursor-glow"></div>
    </div>

    @include('layouts.partials.navbar')

    <div class="flex flex-1 overflow-hidden relative z-20">
        @include('layouts.partials.course-sidebar')

        <main id="mainScroll" class="flex-1 h-full overflow-y-auto scroll-smooth relative bg-transparent custom-scrollbar scroll-padding-top-24">
            
            {{-- STICKY HEADER --}}
            <div id="stickyHeader" class="sticky top-0 z-30 w-full bg-white/80 dark:bg-[#020617]/80 backdrop-blur-2xl border-b border-slate-200 dark:border-white/5 px-4 md:px-8 py-4 flex items-center justify-between transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div class="w-8 h-8 rounded-lg bg-cyan-50 dark:bg-gradient-to-br dark:from-cyan-500/20 dark:to-transparent border border-cyan-200 dark:border-cyan-500/20 flex items-center justify-center font-bold text-xs text-cyan-600 dark:text-cyan-400 shrink-0 transition-colors">2.2</div>
                    <div>
                        <h1 class="text-sm font-bold text-slate-900 dark:text-white line-clamp-1 transition-colors">Layouting dengan Grid</h1>
                        <p class="text-[10px] text-slate-500 dark:text-white/50 line-clamp-1 transition-colors">Sistem Tata Letak Dua Dimensi</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <div class="hidden sm:block w-24 md:w-32 h-1.5 bg-slate-200 dark:bg-white/10 rounded-full overflow-hidden transition-colors">
                        <div id="topProgressBar" class="h-full bg-gradient-to-r from-cyan-400 to-blue-500 dark:from-cyan-500 dark:to-blue-500 w-0 transition-all duration-500 shadow-[0_0_10px_#0ea5e9]"></div>
                    </div>
                    <span id="progressLabelTop" class="text-cyan-600 dark:text-cyan-400 font-bold text-xs transition-colors">0%</span>
                </div>
            </div>

            {{-- CONTENT WRAPPER --}}
            <div class="p-6 lg:p-16 max-w-5xl mx-auto pb-40">
                
                {{-- LEARNING OBJECTIVES --}}
                <div class="mb-24">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-2 transition-colors">
                        <svg class="w-5 h-5 text-cyan-500 dark:text-cyan-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Tujuan Pembelajaran
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-white dark:bg-[#1e1e1e] border border-slate-200 dark:border-white/5 p-5 rounded-xl flex items-start gap-4 hover:border-cyan-400 dark:hover:border-cyan-500/30 shadow-sm dark:shadow-none transition group h-full">
                            <div class="w-8 h-8 rounded bg-cyan-100 dark:bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 flex items-center justify-center shrink-0 font-bold text-xs transition-colors">1</div>
                            <div><h4 class="text-sm font-bold text-slate-800 dark:text-white mb-1 transition-colors">Grid System</h4><p class="text-[11px] text-slate-500 dark:text-white/50 leading-relaxed transition-colors">Memahami paradigma layout dua dimensi (baris & kolom).</p></div>
                        </div>
                        <div class="bg-white dark:bg-[#1e1e1e] border border-slate-200 dark:border-white/5 p-5 rounded-xl flex items-start gap-4 hover:border-blue-400 dark:hover:border-blue-500/30 shadow-sm dark:shadow-none transition group h-full">
                            <div class="w-8 h-8 rounded bg-blue-100 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0 font-bold text-xs transition-colors">2</div>
                            <div><h4 class="text-sm font-bold text-slate-800 dark:text-white mb-1 transition-colors">Columns & Gap</h4><p class="text-[11px] text-slate-500 dark:text-white/50 leading-relaxed transition-colors">Memahami penggunaan utilitas <code>grid-cols</code> dan manajemen spasi.</p></div>
                        </div>
                        <div class="bg-white dark:bg-[#1e1e1e] border border-slate-200 dark:border-white/5 p-5 rounded-xl flex items-start gap-4 hover:border-sky-400 dark:hover:border-sky-500/30 shadow-sm dark:shadow-none transition group h-full">
                            <div class="w-8 h-8 rounded bg-sky-100 dark:bg-sky-500/10 text-sky-600 dark:text-sky-400 flex items-center justify-center shrink-0 font-bold text-xs transition-colors">3</div>
                            <div><h4 class="text-sm font-bold text-slate-800 dark:text-white mb-1 transition-colors">Spanning</h4><p class="text-[11px] text-slate-500 dark:text-white/50 leading-relaxed transition-colors">Memahami cara menggabungkan sel asimetris.</p></div>
                        </div>
                        <div class="bg-white dark:bg-[#1e1e1e] border border-slate-200 dark:border-white/5 p-5 rounded-xl flex items-start gap-4 hover:border-teal-400 dark:hover:border-teal-500/30 shadow-sm dark:shadow-none transition group h-full">
                            <div class="w-8 h-8 rounded bg-teal-100 dark:bg-teal-500/10 text-teal-600 dark:text-teal-400 flex items-center justify-center shrink-0 font-bold text-xs transition-colors">4</div>
                            <div><h4 class="text-sm font-bold text-slate-800 dark:text-white mb-1 transition-colors">Auto Flow</h4><p class="text-[11px] text-slate-500 dark:text-white/50 leading-relaxed transition-colors">Memahami algoritma penempatan otomatis (Dense & Row/Col).</p></div>
                        </div>
                        <div class="bg-gradient-to-br from-cyan-50 to-blue-50 dark:from-cyan-900/40 dark:to-blue-900/40 border border-cyan-200 dark:border-cyan-500/30 p-5 rounded-xl flex items-start gap-4 hover:shadow-md dark:hover:shadow-[0_0_20px_rgba(34,211,238,0.2)] transition group h-full col-span-1 sm:col-span-2 md:col-span-4 cursor-default">
                            <div class="w-8 h-8 rounded bg-cyan-100 dark:bg-white/10 text-cyan-600 dark:text-white flex items-center justify-center shrink-0 font-bold text-xs transition-colors">🏁</div>
                            <div><h4 class="text-sm font-bold text-slate-800 dark:text-white mb-1 transition-colors">Final Mission</h4><p class="text-[11px] text-slate-600 dark:text-white/70 leading-relaxed transition-colors">Live Code: Membangun Layout Galeri Foto kompleks menggunakan kombinasi Grid, Spanning, dan Alignment (Expert Mode).</p></div>
                        </div>
                    </div>
                </div>

                <article class="space-y-40">
                    
                    {{-- LESSON 34: KONSEP GRID --}}
                    <section id="section-34" class="lesson-section scroll-mt-32" data-lesson-id="34">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-cyan-500 pl-6">
                                <span class="text-cyan-600 dark:text-cyan-400 font-mono text-xs uppercase tracking-widest transition-colors">Lesson 2.2.1</span>
                                <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-slate-900 dark:text-white leading-[1.1] transition-colors">
                                    Konsep Grid Layout & <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-500 to-blue-500 dark:from-cyan-400 dark:to-blue-500">Struktur Kolom</span>
                                </h2>
                            </div>
                            
                            {{-- Materi --}}
                            <div class="space-y-6">
                                <div class="prose prose-slate dark:prose-invert max-w-none text-slate-600 dark:text-white/70 text-base md:text-lg leading-relaxed transition-colors">
                                    <p class="mb-6 text-justify">
                                        Buku rujukan <em>"Tailwind CSS by SitePoint"</em> menjelaskan perbedaan fundamental antara Flexbox dan Grid. Berikut adalah kunci untuk memahami perbedaannya:
                                    </p>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                                        <div class="bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 p-5 rounded-xl flex flex-col gap-2 shadow-sm dark:shadow-none transition-colors">
                                            <h4 class="text-slate-800 dark:text-white font-bold m-0 flex items-center gap-2 transition-colors">
                                                <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                                                Flexbox
                                            </h4>
                                            <p class="text-sm text-slate-500 dark:text-white/60 m-0 transition-colors">Sistem tata letak <strong>satu dimensi</strong>. Fokus mengatur susunan item dalam satu baris <em>atau</em> satu kolom secara fleksibel.</p>
                                        </div>
                                        <div class="bg-cyan-50 dark:bg-cyan-500/10 border border-cyan-200 dark:border-cyan-500/30 p-5 rounded-xl flex flex-col gap-2 shadow-sm dark:shadow-[0_0_20px_rgba(6,182,212,0.1)] transition-colors">
                                            <h4 class="text-cyan-700 dark:text-cyan-400 font-bold m-0 flex items-center gap-2 transition-colors">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                                                Grid
                                            </h4>
                                            <p class="text-sm text-cyan-800/80 dark:text-cyan-100/70 m-0 transition-colors">Sistem <strong>dua dimensi</strong>. Mengatur tata letak item dalam baris <em>dan</em> kolom secara bersamaan dengan struktur yang lebih kaku dan terprediksi.</p>
                                        </div>
                                    </div>

                                    <p class="text-justify">
                                        Dengan Grid, Anda pada dasarnya sedang merancang sebuah "papan catur" atau cetak biru (<span class="text-cyan-700 dark:text-cyan-300 font-semibold bg-cyan-100 dark:bg-cyan-900/30 px-1.5 py-0.5 rounded border border-cyan-200 dark:border-cyan-500/20 transition-colors">scaffolding</span>) terlebih dahulu—menentukan jumlah garis vertikal dan horizontal—lalu melemparkan item-item antarmuka ke dalam kotak-kotak yang tercipta. Tailwind menggunakan class <code class="text-cyan-700 dark:text-cyan-400 font-bold bg-slate-100 dark:bg-white/10 border border-slate-200 dark:border-transparent px-1 rounded transition-colors">grid</code> pada wrapper utama untuk mengaktifkan mode canggih ini.
                                    </p>

                                    <div class="bg-slate-50 dark:bg-[#0f172a] border border-cyan-200 dark:border-cyan-500/30 p-5 rounded-xl my-6 transition-colors">
                                        <h4 class="text-cyan-700 dark:text-cyan-300 font-bold text-sm mb-3 flex items-center gap-2 transition-colors">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Fractional Unit (fr) vs Persentase
                                        </h4>
                                        <p class="text-xs md:text-sm text-slate-600 dark:text-cyan-100/70 m-0 leading-relaxed text-justify transition-colors">
                                            Di balik layar, struktur kolom Tailwind menggunakan spesifikasi unit <code>fr</code> bawaan CSS Grid (contoh: <code>repeat(3, 1fr)</code>). Unit fraksional ini sangat cerdas karena ia menghitung sisa ruang yang tersedia <strong>setelah</strong> dikurangi jarak celah (gap), lalu membaginya secara rata. Hal ini mencegah layout yang "meluber" (pecah) yang sering terjadi jika Anda menggunakan persentase statis murni seperti 33.33% namun lupa memperhitungkan margin.
                                        </p>
                                    </div>
                                    <p>
                                        Untuk mengatur berapa banyak kolom yang diinginkan, gunakan utilitas <code class="text-blue-600 dark:text-blue-400 font-bold bg-slate-100 dark:bg-white/10 border border-slate-200 dark:border-transparent px-1 rounded transition-colors">grid-cols-{n}</code>. Untuk mengatur jarak celah antar kotak, gunakan <code class="text-blue-600 dark:text-blue-400 font-bold bg-slate-100 dark:bg-white/10 border border-slate-200 dark:border-transparent px-1 rounded transition-colors">gap-{n}</code>.
                                    </p>
                                </div>
                            </div>

                            {{-- SIMULATOR 1 --}}
                            <div class="bg-white dark:bg-[#0b0f19] border border-slate-200 dark:border-white/10 rounded-2xl p-6 lg:p-8 shadow-xl dark:shadow-2xl relative group hover:border-cyan-400 dark:hover:border-cyan-500/30 transition-all">
                                <h4 class="text-xs font-bold text-slate-400 dark:text-muted uppercase mb-4 text-center transition-colors tracking-widest">Simulator: Distribusi Kolom Dinamis</h4>
                                
                                {{-- Kotak Instruksi --}}
                                <div class="bg-cyan-50 dark:bg-cyan-500/10 border border-cyan-200 dark:border-cyan-500/30 rounded-xl p-4 mb-8 text-sm text-cyan-700 dark:text-cyan-300 relative z-10 shadow-sm dark:shadow-inner transition-colors">
                                    <p class="font-bold flex items-center gap-2 mb-2">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"></path></svg>
                                        Panduan Interaksi
                                    </p>
                                    <p class="m-0 opacity-90 leading-relaxed text-xs md:text-sm text-cyan-800/80 dark:text-cyan-100/80 transition-colors">
                                        Klik tombol konfigurasi di bawah untuk merubah jumlah pecahan kolom (<code>grid-cols-*</code>). Perhatikan bagaimana 4 buah kotak anak di bawahnya secara otomatis menyusut, melebar, atau turun baris secara cerdas untuk mematuhi cetak biru scaffolding yang Anda buat.
                                    </p>
                                </div>

                                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end mb-6 gap-4 lg:gap-6">
                                    <div>
                                        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-1 transition-colors">🏗️ Grid Track Builder</h3>
                                        <p class="text-[11px] text-slate-500 dark:text-white/50 transition-colors">Membangun kerangka sel horizontal.</p>
                                    </div>
                                    <div class="flex gap-2 bg-slate-100 dark:bg-white/5 p-1.5 rounded-lg w-full lg:w-auto overflow-x-auto custom-scrollbar pb-1 lg:pb-1.5 border border-slate-200 dark:border-white/10 shadow-inner transition-colors">
                                        <button onclick="updateGridCols(1, this)" class="col-btn px-4 py-2 text-[10px] sm:text-xs font-bold rounded bg-slate-200 dark:bg-white/5 text-slate-600 dark:text-white/50 hover:bg-slate-300 dark:hover:bg-white/10 transition shrink-0 focus:outline-none">grid-cols-1</button>
                                        <button onclick="updateGridCols(2, this)" class="col-btn px-4 py-2 text-[10px] sm:text-xs font-bold rounded bg-cyan-600 text-white shadow-lg transition active-btn shrink-0 focus:outline-none">grid-cols-2</button>
                                        <button onclick="updateGridCols(3, this)" class="col-btn px-4 py-2 text-[10px] sm:text-xs font-bold rounded bg-slate-200 dark:bg-white/5 text-slate-600 dark:text-white/50 hover:bg-slate-300 dark:hover:bg-white/10 transition shrink-0 focus:outline-none">grid-cols-3</button>
                                        <button onclick="updateGridCols(4, this)" class="col-btn px-4 py-2 text-[10px] sm:text-xs font-bold rounded bg-slate-200 dark:bg-white/5 text-slate-600 dark:text-white/50 hover:bg-slate-300 dark:hover:bg-white/10 transition shrink-0 focus:outline-none">grid-cols-4</button>
                                    </div>
                                </div>

                                <div id="demo-grid-cols" class="grid grid-cols-2 gap-4 p-4 lg:p-6 bg-slate-100 dark:bg-black/40 rounded-xl border-2 border-dashed border-slate-300 dark:border-white/10 transition-all duration-500 overflow-hidden relative min-h-[160px]">
                                    <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-10 pointer-events-none"></div>
                                    <div class="h-16 lg:h-20 bg-cyan-100 dark:bg-cyan-500/10 border border-cyan-300 dark:border-cyan-500/30 text-cyan-600 dark:text-cyan-400 flex items-center justify-center font-bold rounded-lg text-lg lg:text-xl relative z-10 transition-transform hover:scale-105">1</div>
                                    <div class="h-16 lg:h-20 bg-cyan-100 dark:bg-cyan-500/10 border border-cyan-300 dark:border-cyan-500/30 text-cyan-600 dark:text-cyan-400 flex items-center justify-center font-bold rounded-lg text-lg lg:text-xl relative z-10 transition-transform hover:scale-105">2</div>
                                    <div class="h-16 lg:h-20 bg-cyan-100 dark:bg-cyan-500/10 border border-cyan-300 dark:border-cyan-500/30 text-cyan-600 dark:text-cyan-400 flex items-center justify-center font-bold rounded-lg text-lg lg:text-xl relative z-10 transition-transform hover:scale-105">3</div>
                                    <div class="h-16 lg:h-20 bg-cyan-100 dark:bg-cyan-500/10 border border-cyan-300 dark:border-cyan-500/30 text-cyan-600 dark:text-cyan-400 flex items-center justify-center font-bold rounded-lg text-lg lg:text-xl relative z-10 transition-transform hover:scale-105">4</div>
                                </div>
                                
                                <div class="mt-6 text-center"><code id="code-grid-cols" class="bg-slate-200 dark:bg-black/50 px-5 py-2.5 rounded text-cyan-700 dark:text-cyan-300 text-[10px] sm:text-xs font-mono border border-slate-300 dark:border-white/10 shadow-inner transition-colors">class="grid grid-cols-2 gap-4"</code></div>

                                {{-- Kotak Kesimpulan --}}
                                <div class="mt-8 bg-gradient-to-r from-cyan-50 dark:from-cyan-500/10 to-transparent border-l-4 border-cyan-500 p-5 rounded-r-xl relative z-10 transition-colors">
                                    <h5 class="text-sm font-bold text-cyan-700 dark:text-cyan-400 mb-2 flex items-center gap-2 transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Catatan Kesimpulan
                                    </h5>
                                    <p class="text-xs sm:text-sm text-slate-600 dark:text-white/70 leading-relaxed text-justify m-0 transition-colors">
                                        Kelas <code>grid-cols-{n}</code> membagi total lebar ruang menjadi lajur vertikal yang simetris dan sama besar. Sistem ini secara ajaib mengambil alih beban perhitungan matematika yang biasanya Anda lakukan secara manual menggunakan persentase (width: 25%, 33%, dsb) di CSS klasik. Anda cukup mendiktekan jumlah lajurnya, dan Tailwind akan menyelesaikan sisanya.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- SECTION 35: PENJAJARAN GRID (ALIGNMENT) --}}
                    <section id="section-35" class="lesson-section scroll-mt-32" data-lesson-id="35">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-blue-500 pl-6">
                                <span class="text-blue-600 dark:text-blue-400 font-mono text-xs uppercase tracking-widest transition-colors">Lesson 2.2.2</span>
                                <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-slate-900 dark:text-white leading-[1.1] transition-colors">
                                    Penjajaran Grid <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-500 to-indigo-600 dark:from-blue-400 dark:to-indigo-500">(Cell Alignment)</span>
                                </h2>
                            </div>

                            {{-- Materi --}}
                            <div class="prose prose-slate dark:prose-invert max-w-none text-slate-600 dark:text-white/70 text-base md:text-lg leading-relaxed space-y-6 text-justify transition-colors">
                                <p>
                                    Banyak pemula Tailwind yang bingung membedakan antara <code class="bg-slate-100 dark:bg-white/10 text-slate-800 dark:text-white px-1 rounded border border-slate-200 dark:border-transparent">justify-content</code> (pengaturan baris secara utuh) dan <code class="bg-slate-100 dark:bg-white/10 text-slate-800 dark:text-white px-1 rounded border border-slate-200 dark:border-transparent">justify-items</code>. Dalam ekosistem Grid, <code>justify-items-*</code> sangat krusial karena ia mengontrol posisi elemen <strong>di dalam sangkar/sel grid-nya masing-masing</strong> pada perlintasan sumbu horizontal (X).
                                </p>
                                
                                <div class="bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 p-5 rounded-xl transition-colors">
                                    <h4 class="text-slate-800 dark:text-white font-bold mb-4 text-sm uppercase tracking-wide transition-colors">Panduan Sumbu X (Horizontal)</h4>
                                    <ul class="list-none pl-0 space-y-3 m-0 text-sm md:text-base text-left">
                                        <li class="flex items-start gap-3">
                                            <span class="mt-1 w-2 h-2 rounded-full bg-blue-500 dark:bg-blue-400 shrink-0 transition-colors"></span>
                                            <div><strong><code class="text-blue-600 dark:text-blue-300">justify-items-start</code></strong>: Item merapat ke garis awal sel (kiri). Lebar item menyusut pas memeluk konten di dalamnya.</div>
                                        </li>
                                        <li class="flex items-start gap-3">
                                            <span class="mt-1 w-2 h-2 rounded-full bg-blue-500 dark:bg-blue-400 shrink-0 transition-colors"></span>
                                            <div><strong><code class="text-blue-600 dark:text-blue-300">justify-items-center</code></strong>: Memarkir item di tengah-tengah ruang sel secara horizontal.</div>
                                        </li>
                                        <li class="flex items-start gap-3">
                                            <span class="mt-1 w-2 h-2 rounded-full bg-blue-500 dark:bg-blue-400 shrink-0 transition-colors"></span>
                                            <div><strong><code class="text-blue-600 dark:text-blue-300">justify-items-stretch</code></strong> (Default): Item ditarik paksa agar menutupi seluruh lebar sel tersebut.</div>
                                        </li>
                                    </ul>
                                </div>

                                <p>
                                    Sementara untuk memanipulasi posisi pada sumbu vertikal (Y), kita mendelegasikan tugas tersebut kepada properti <code class="text-indigo-600 dark:text-indigo-400 font-bold bg-slate-100 dark:bg-white/10 border border-slate-200 dark:border-transparent px-1 rounded transition-colors">items-*</code> (alias align-items).
                                </p>
                                
                                <div class="bg-gradient-to-r from-blue-50 dark:from-blue-900/40 to-transparent border-l-4 border-blue-500 p-5 rounded-r-xl mt-6 shadow-sm dark:shadow-inner transition-colors">
                                    <p class="m-0 text-sm leading-relaxed text-slate-700 dark:text-white"><strong>🔥 Magic Formula:</strong> Kombinasi sakti untuk memusatkan elemen secara absolut (tepat di jantung kotak) tanpa rumus rumit adalah:
                                    <code class="text-blue-700 dark:text-blue-300 bg-slate-200 dark:bg-black/40 px-2 py-1 rounded mx-1 font-bold border border-slate-300 dark:border-white/10 shadow-sm transition-colors">justify-center items-center</code> (Atau gunakan utilitas singkat Tailwind: <code>place-items-center</code>).</p>
                                </div>
                            </div>

                            {{-- SIMULATOR 2 --}}
                            <div class="bg-white dark:bg-[#0b0f19] p-6 lg:p-8 rounded-2xl border border-slate-200 dark:border-white/10 shadow-xl dark:shadow-2xl relative group hover:border-blue-400 dark:hover:border-blue-500/30 transition-all">
                                <h4 class="text-xs font-bold text-slate-400 dark:text-muted uppercase mb-4 text-center transition-colors tracking-widest">Simulator: Kendali Posisi Sel</h4>
                                
                                {{-- Kotak Instruksi --}}
                                <div class="bg-blue-50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/30 rounded-xl p-4 mb-8 text-sm text-blue-700 dark:text-blue-300 relative z-10 shadow-sm dark:shadow-inner transition-colors">
                                    <p class="font-bold flex items-center gap-2 mb-2">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"></path></svg>
                                        Uji Coba Penyelarasan
                                    </p>
                                    <p class="m-0 opacity-90 leading-relaxed text-xs md:text-sm text-blue-800/80 dark:text-blue-100/80 transition-colors">
                                        Klik pada deretan variasi ikon posisi X (Justify) pada panel kiri dan posisi Y (Align) pada panel kanan. Amati bagaimana tindakan tersebut memindahkan blok A, B, C, dan D yang terkurung di dalam batas dimensi sel grid mereka.
                                    </p>
                                </div>

                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8">
                                    <div class="space-y-4 bg-slate-50 dark:bg-[#151a26] p-5 md:p-6 rounded-xl border border-slate-200 dark:border-white/5 shadow-sm dark:shadow-inner transition-colors">
                                        <div class="flex justify-between items-center mb-3">
                                            <h4 class="text-[11px] sm:text-xs font-bold text-cyan-600 dark:text-cyan-400 uppercase tracking-widest bg-cyan-100 dark:bg-cyan-500/10 px-3 py-1 rounded-full border border-cyan-200 dark:border-cyan-500/20 transition-colors">Justify (X Axis)</h4>
                                            <div class="flex gap-1.5 bg-slate-200 dark:bg-black/40 p-1.5 rounded-lg border border-slate-300 dark:border-white/10 shadow-inner transition-colors">
                                                <button onclick="updateJustify('justify-items-start')" class="p-2 hover:bg-slate-300 dark:hover:bg-white/10 rounded-md transition focus:outline-none" title="Start"><div class="w-5 h-5 bg-cyan-500/50 rounded-sm"></div></button>
                                                <button onclick="updateJustify('justify-items-center')" class="p-2 bg-white dark:bg-white/10 rounded-md transition shadow focus:outline-none" title="Center"><div class="w-5 h-5 bg-cyan-500 rounded-sm mx-auto shadow-[0_0_8px_#06b6d4]"></div></button>
                                                <button onclick="updateJustify('justify-items-end')" class="p-2 hover:bg-slate-300 dark:hover:bg-white/10 rounded-md transition focus:outline-none" title="End"><div class="w-5 h-5 bg-cyan-500/50 rounded-sm ml-auto"></div></button>
                                            </div>
                                        </div>
                                        <div id="demo-justify" class="grid grid-cols-2 gap-3 justify-items-center h-40 bg-slate-100 dark:bg-black/50 rounded-xl border border-dashed border-slate-300 dark:border-white/20 p-4 relative overflow-hidden transition-all duration-300">
                                            <div class="w-12 h-12 bg-cyan-600 rounded-lg flex items-center justify-center text-sm font-black text-white shadow-[0_0_15px_rgba(8,145,178,0.5)] transition-all duration-300 z-10 border border-cyan-400/50">A</div>
                                            <div class="w-12 h-12 bg-cyan-600 rounded-lg flex items-center justify-center text-sm font-black text-white shadow-[0_0_15px_rgba(8,145,178,0.5)] transition-all duration-300 z-10 border border-cyan-400/50">B</div>
                                            <div class="w-12 h-12 bg-cyan-600 rounded-lg flex items-center justify-center text-sm font-black text-white shadow-[0_0_15px_rgba(8,145,178,0.5)] transition-all duration-300 z-10 border border-cyan-400/50">C</div>
                                            <div class="w-12 h-12 bg-cyan-600 rounded-lg flex items-center justify-center text-sm font-black text-white shadow-[0_0_15px_rgba(8,145,178,0.5)] transition-all duration-300 z-10 border border-cyan-400/50">D</div>
                                        </div>
                                        <div class="pt-2 text-center"><code class="text-[10px] sm:text-xs text-cyan-700 dark:text-cyan-300 bg-cyan-100 dark:bg-cyan-900/30 px-4 py-2 rounded-lg border border-cyan-200 dark:border-cyan-500/20 font-bold tracking-wide transition-colors" id="code-justify">justify-items-center</code></div>
                                    </div>
                                    
                                    <div class="space-y-4 bg-slate-50 dark:bg-[#151a26] p-5 md:p-6 rounded-xl border border-slate-200 dark:border-white/5 shadow-sm dark:shadow-inner transition-colors">
                                        <div class="flex justify-between items-center mb-3">
                                            <h4 class="text-[11px] sm:text-xs font-bold text-blue-600 dark:text-blue-400 uppercase tracking-widest bg-blue-100 dark:bg-blue-500/10 px-3 py-1 rounded-full border border-blue-200 dark:border-blue-500/20 transition-colors">Align (Y Axis)</h4>
                                            <div class="flex gap-1.5 bg-slate-200 dark:bg-black/40 p-1.5 rounded-lg border border-slate-300 dark:border-white/10 shadow-inner transition-colors">
                                                <button onclick="updateAlign('items-start')" class="p-2 hover:bg-slate-300 dark:hover:bg-white/10 rounded-md transition focus:outline-none"><div class="w-5 h-2.5 bg-blue-500/50 rounded-sm mb-auto"></div></button>
                                                <button onclick="updateAlign('items-center')" class="p-2 bg-white dark:bg-white/10 rounded-md transition shadow focus:outline-none"><div class="w-5 h-2.5 bg-blue-500 rounded-sm my-auto shadow-[0_0_8px_#3b82f6]"></div></button>
                                                <button onclick="updateAlign('items-end')" class="p-2 hover:bg-slate-300 dark:hover:bg-white/10 rounded-md transition focus:outline-none"><div class="w-5 h-2.5 bg-blue-500/50 rounded-sm mt-auto"></div></button>
                                            </div>
                                        </div>
                                        <div id="demo-align" class="grid grid-cols-2 gap-3 items-center h-40 bg-slate-100 dark:bg-black/50 rounded-xl border border-dashed border-slate-300 dark:border-white/20 p-4 relative overflow-hidden transition-all duration-300">
                                            <div class="w-full h-10 bg-blue-600 rounded-lg flex items-center justify-center text-sm font-black text-white shadow-[0_0_15px_rgba(37,99,235,0.5)] transition-all duration-300 z-10 border border-blue-400/50">A</div>
                                            <div class="w-full h-10 bg-blue-600 rounded-lg flex items-center justify-center text-sm font-black text-white shadow-[0_0_15px_rgba(37,99,235,0.5)] transition-all duration-300 z-10 border border-blue-400/50">B</div>
                                        </div>
                                        <div class="pt-2 text-center"><code class="text-[10px] sm:text-xs text-blue-700 dark:text-blue-300 bg-blue-100 dark:bg-blue-900/30 px-4 py-2 rounded-lg border border-blue-200 dark:border-blue-500/20 font-bold tracking-wide transition-colors" id="code-align">items-center</code></div>
                                    </div>
                                </div>

                                {{-- Kotak Kesimpulan --}}
                                <div class="mt-8 bg-gradient-to-r from-blue-50 dark:from-blue-500/10 to-transparent border-l-4 border-blue-500 p-5 rounded-r-xl relative z-10 transition-colors">
                                    <h5 class="text-sm font-bold text-blue-700 dark:text-blue-400 mb-2 flex items-center gap-2 transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Catatan Kesimpulan
                                    </h5>
                                    <p class="text-xs sm:text-sm text-slate-600 dark:text-white/70 leading-relaxed text-justify m-0 transition-colors">
                                        Perbedaan tajam dengan Flexbox: sistem keselarasan pada Grid mengizinkan Anda untuk menyentuh dan mendikte posisi setiap individu objek pada kotak isolasinya sendiri. <code>justify-items</code> dan <code>align-items</code> memberikan hak veto posisi absolut, menghindarkan desain dari pelebaran objek yang sering menggepengkan gambar tanpa disengaja.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- SECTION 36: SPAN & START/END --}}
                    <section id="section-36" class="lesson-section scroll-mt-32" data-lesson-id="36">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-sky-500 pl-6">
                                <span class="text-sky-600 dark:text-sky-400 font-mono text-xs uppercase tracking-widest transition-colors">Lesson 2.2.3</span>
                                <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-slate-900 dark:text-white leading-[1.1] transition-colors">
                                    Teknik Span <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-500 to-cyan-600 dark:from-sky-400 dark:to-cyan-500">(Penggabungan Sel)</span>
                                </h2>
                            </div>
                            
                            {{-- Materi --}}
                            <div class="prose prose-slate dark:prose-invert max-w-none text-slate-600 dark:text-white/70 text-base md:text-lg leading-relaxed space-y-6 text-justify transition-colors">
                                <p>
                                    Fitur evolusioner dan paling kuat dari Grid Layout adalah kemampuan mutlak untuk membuat satu elemen "memakan" (<span class="text-sky-700 dark:text-sky-300 font-semibold bg-sky-100 dark:bg-sky-900/30 px-1.5 py-0.5 rounded border border-sky-200 dark:border-sky-500/20 transition-colors">spanning</span>) beberapa kolom atau baris sekaligus, menembus batas struktur grid yang kaku. Bagi Anda yang terbiasa dengan spreadsheet, konsep ini sama persis dengan operasi <em>Merge Cells</em> di Excel.
                                </p>
                                
                                <div class="bg-slate-50 dark:bg-[#151a26] border border-slate-200 dark:border-white/5 p-6 rounded-xl shadow-sm dark:shadow-inner my-6 transition-colors">
                                    <h4 class="text-sky-600 dark:text-sky-400 font-bold mb-3 text-sm flex items-center gap-2 uppercase tracking-wide transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                        Pintu Gerbang Layout Kompleks
                                    </h4>
                                    <p class="text-sm text-slate-600 dark:text-white/70 mb-4 transition-colors">Kemampuan ekspansi inilah yang memungkinkan penciptaan layout kompleks yang asimetris dan artistik tanpa <span class="text-sky-700 dark:text-sky-300 font-semibold bg-sky-100 dark:bg-sky-900/30 px-1.5 py-0.5 rounded border border-sky-200 dark:border-sky-500/20 transition-colors">nesting div</span> yang membingungkan. Contoh penggunaannya:</p>
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                        <div class="bg-slate-200/50 dark:bg-black/30 border border-slate-300 dark:border-white/10 p-3 rounded-lg text-xs text-center text-slate-600 dark:text-white/60 transition-colors">Desain Dashboard<br>(Sidebar utuh)</div>
                                        <div class="bg-slate-200/50 dark:bg-black/30 border border-slate-300 dark:border-white/10 p-3 rounded-lg text-xs text-center text-slate-600 dark:text-white/60 transition-colors">Artikel Majalah Digital</div>
                                        <div class="bg-slate-200/50 dark:bg-black/30 border border-sky-300 dark:border-sky-500/20 p-3 rounded-lg text-xs text-center font-bold text-sky-600 dark:text-sky-400 transition-colors"><span class="text-sky-700 dark:text-sky-300 font-bold bg-sky-100 dark:bg-sky-900/30 px-1.5 py-0.5 rounded border border-sky-200 dark:border-sky-500/20 transition-colors">Bento Grid UI</span> ala Apple</div>
                                    </div>
                                </div>

                                <ul class="list-disc pl-5 space-y-3 text-sm md:text-base text-left mt-4 border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-white/5 p-6 rounded-xl transition-colors">
                                    <li><strong><code class="text-sky-600 dark:text-sky-300 font-bold transition-colors">col-span-{n}</code></strong>: Menginstruksikan elemen untuk merentang (melebar) ke arah samping memakan sebanyak <em>n</em> lintasan kolom.</li>
                                    <li><strong><code class="text-sky-600 dark:text-sky-300 font-bold transition-colors">row-span-{n}</code></strong>: Menarik panjang elemen (menukik) ke arah bawah memakan <em>n</em> baris.</li>
                                    <li><strong><code class="text-sky-600 dark:text-sky-300 font-bold bg-slate-200 dark:bg-black/40 px-2 py-1 rounded transition-colors">col-span-full</code></strong>: Utilitas istimewa yang akan memaksa suatu elemen merebut seluruh lebar lintasan grid secara brutal—berawal dari garis permulaan sisi kiri (kolom 1) hingga berlabuh tepat di garis paling ujung di sisi kanan (-1). Sangat efisien diterapkan untuk komponen pita Header atau kaki Footer.</li>
                                </ul>
                            </div>

                            {{-- DEMO BENTO GRID --}}
                            <div class="bg-white dark:bg-[#0b0f19] p-6 lg:p-10 rounded-2xl border border-slate-200 dark:border-white/10 shadow-xl dark:shadow-2xl relative group hover:border-sky-400 dark:hover:border-sky-500/30 transition-all">
                                <div class="absolute -top-3 -right-3 bg-gradient-to-r from-orange-500 to-amber-500 text-white text-[10px] font-bold px-4 py-1.5 rounded-full shadow-lg shadow-orange-500/30 tracking-widest uppercase">Bento Layout</div>
                                
                                {{-- Kotak Instruksi --}}
                                <div class="bg-sky-50 dark:bg-sky-500/10 border border-sky-200 dark:border-sky-500/30 rounded-xl p-4 mb-8 text-sm text-sky-700 dark:text-sky-300 relative z-10 shadow-sm dark:shadow-inner transition-colors">
                                    <p class="font-bold flex items-center gap-2 mb-2">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"></path></svg>
                                        Panduan Eksplorasi DOM
                                    </p>
                                    <p class="m-0 opacity-90 leading-relaxed text-xs md:text-sm text-sky-800/80 dark:text-sky-100/80 transition-colors">
                                        Arahkan kursor tetikus (hover) secara perlahan untuk menelusuri tiap petak arsitektur blok pada maket <em>Bento Grid</em> di bawah. Perhatikan lencana transparan (badge) yang tertera di dalam blok untuk mempelajari deklarasi kelas utilitas `span` yang memungkinkan terwujudnya desain kompleks nan elegan ini.
                                    </p>
                                </div>

                                <div class="grid grid-cols-3 grid-rows-3 gap-3 md:gap-4 h-[350px] relative z-10">
                                    <div class="row-span-3 bg-gradient-to-b from-indigo-100 to-purple-100 dark:from-indigo-600/30 dark:to-purple-600/30 border border-indigo-300 dark:border-indigo-500/50 rounded-xl flex flex-col items-center justify-center text-indigo-700 dark:text-indigo-300 font-bold p-4 text-center text-xs shadow-sm dark:shadow-inner transition-all hover:scale-[1.02] cursor-default">
                                        Sidebar Panel
                                        <span class="opacity-90 bg-white/80 dark:bg-black/50 px-2 py-1 rounded text-[10px] mt-3 font-mono border border-indigo-200 dark:border-indigo-400/30 text-indigo-800 dark:text-white shadow-sm transition-colors">row-span-3</span>
                                    </div>
                                    <div class="col-span-2 bg-gradient-to-r from-pink-100 to-rose-100 dark:from-pink-600/30 dark:to-rose-600/30 border border-pink-300 dark:border-pink-500/50 rounded-xl flex flex-col items-center justify-center text-pink-700 dark:text-pink-300 font-bold text-xs shadow-sm dark:shadow-inner transition-all hover:scale-[1.02] cursor-default p-4">
                                        Navigasi Header Teratas 
                                        <span class="opacity-90 bg-white/80 dark:bg-black/50 px-2 py-1 rounded text-[10px] mt-3 font-mono border border-pink-200 dark:border-pink-400/30 text-pink-800 dark:text-white shadow-sm transition-colors">col-span-2</span>
                                    </div>
                                    <div class="bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl flex items-center justify-center text-slate-500 dark:text-white/50 text-xs shadow-sm dark:shadow-inner transition-all hover:bg-slate-200 dark:hover:bg-white/10 cursor-default font-bold">Item Kiri</div>
                                    <div class="bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl flex items-center justify-center text-slate-500 dark:text-white/50 text-xs shadow-sm dark:shadow-inner transition-all hover:bg-slate-200 dark:hover:bg-white/10 cursor-default font-bold">Item Kanan</div>
                                    <div class="col-span-2 bg-gradient-to-r from-cyan-100 to-teal-100 dark:from-cyan-600/30 dark:to-teal-600/30 border border-cyan-300 dark:border-cyan-500/50 rounded-xl flex flex-col items-center justify-center text-cyan-700 dark:text-cyan-300 font-bold text-xs shadow-sm dark:shadow-inner transition-all hover:scale-[1.02] cursor-default p-4">
                                        Footer Bawah Penuh
                                        <span class="opacity-90 bg-white/80 dark:bg-black/50 px-2 py-1 rounded text-[10px] mt-3 font-mono border border-cyan-200 dark:border-cyan-400/30 text-cyan-800 dark:text-white shadow-sm transition-colors">col-span-2</span>
                                    </div>
                                </div>

                                {{-- Kotak Kesimpulan --}}
                                <div class="mt-8 bg-gradient-to-r from-sky-50 dark:from-sky-500/10 to-transparent border-l-4 border-sky-500 p-5 rounded-r-xl relative z-10 transition-colors">
                                    <h5 class="text-sm font-bold text-sky-700 dark:text-sky-400 mb-2 flex items-center gap-2 transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Catatan Kesimpulan
                                    </h5>
                                    <p class="text-xs sm:text-sm text-slate-600 dark:text-white/70 leading-relaxed text-justify m-0 transition-colors">
                                        Teknik merajut atau melebur petak-petak ini merupakan peluru rahasia bagi front-end developer zaman sekarang untuk menciptakan asimetri estetis yang kerap ditemukan pada aplikasi profesional dan portofolio desainer. Dengan Grid, menyusun antarmuka rumit ala dashboard kini layaknya merakit potongan balok Lego.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- SECTION 37 & 38: ROWS & ARBITRARY --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 lg:gap-10">
                        {{-- SECTION 37 --}}
                        <section id="section-37" class="lesson-section scroll-mt-32" data-lesson-id="37">
                            <div class="space-y-6">
                                <div class="flex items-center gap-4 mb-4">
                                    <h2 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight transition-colors">4. Dimensi Eksplisit Rows</h2>
                                    <div class="h-px flex-1 bg-gradient-to-r from-slate-200 dark:from-white/10 to-transparent transition-colors"></div>
                                </div>
                                
                                <div class="space-y-4 text-justify">
                                    <div class="bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 p-4 rounded-xl transition-colors">
                                        <h4 class="text-slate-800 dark:text-white font-bold text-sm mb-1 uppercase tracking-wide transition-colors">Cara Kerja Implicit (Default)</h4>
                                        <p class="text-sm text-slate-600 dark:text-white/70 m-0 transition-colors">Secara mekanisme dasar, arsitektur baris pada Grid Tailwind bersifat <strong>Implicit</strong>. Anda cukup membiarkannya berjalan mengalir ke bawah tak terhingga—mesin browser akan merakit baris baru secara gaib setinggi pas tumpukan konten yang ada di dalamnya saat wadahnya tidak muat lagi.</p>
                                    </div>
                                    
                                    <div class="bg-blue-50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/20 p-4 rounded-xl transition-colors">
                                        <h4 class="text-blue-700 dark:text-blue-400 font-bold text-sm mb-1 uppercase tracking-wide transition-colors">Solusi: Dimensi Eksplisit</h4>
                                        <p class="text-sm text-blue-800/80 dark:text-blue-100/70 m-0 transition-colors">Namun ada kasus pengecualian yang mendesak, contohnya merancang tirai vertikal aplikasi yang ingin dibagi 3 bagian rata secara presisi simetris tanpa sisa. Pada skenario ini, ambil alih kendali dengan utilitas deklarasi eksplisit <code class="text-blue-600 dark:text-blue-300 bg-blue-100 dark:bg-blue-900/40 px-1 rounded font-bold transition-colors">grid-rows-{n}</code>.</p>
                                    </div>
                                </div>

                                <div class="bg-white dark:bg-[#1e1e1e] p-5 lg:p-6 rounded-xl border border-slate-200 dark:border-white/10 shadow-md dark:shadow-lg group hover:border-blue-400 dark:hover:border-blue-500/30 transition-all">
                                    <div class="flex flex-col sm:flex-row justify-between mb-4 items-start sm:items-center gap-3 sm:gap-0">
                                        <span class="text-[10px] font-bold text-slate-500 dark:text-white/50 uppercase tracking-widest transition-colors">Tinjauan Rendering</span>
                                        <code class="text-[10px] text-blue-600 dark:text-blue-400 bg-blue-100 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/20 px-3 py-1.5 rounded shadow-sm font-bold transition-colors">grid-rows-3 grid-flow-col</code>
                                    </div>
                                    <div class="grid grid-rows-3 grid-flow-col gap-3 h-48 bg-slate-100 dark:bg-black/40 p-5 rounded-xl border border-dashed border-slate-300 dark:border-white/10 shadow-inner transition-colors">
                                        <div class="bg-blue-50 dark:bg-blue-500/5 hover:bg-blue-100 dark:hover:bg-blue-500/20 transition-colors rounded-lg border border-blue-200 dark:border-blue-500/20 flex items-center justify-center text-sm text-blue-600 dark:text-blue-300 font-bold">1</div>
                                        <div class="bg-blue-50 dark:bg-blue-500/5 hover:bg-blue-100 dark:hover:bg-blue-500/20 transition-colors rounded-lg border border-blue-200 dark:border-blue-500/20 flex items-center justify-center text-sm text-blue-600 dark:text-blue-300 font-bold">2</div>
                                        <div class="bg-blue-50 dark:bg-blue-500/5 hover:bg-blue-100 dark:hover:bg-blue-500/20 transition-colors rounded-lg border border-blue-200 dark:border-blue-500/20 flex items-center justify-center text-sm text-blue-600 dark:text-blue-300 font-bold">3</div>
                                        <div class="bg-blue-50 dark:bg-blue-500/5 hover:bg-blue-100 dark:hover:bg-blue-500/20 transition-colors rounded-lg border border-blue-200 dark:border-blue-500/20 flex items-center justify-center text-sm text-blue-600 dark:text-blue-300 font-bold">4</div>
                                        <div class="bg-blue-50 dark:bg-blue-500/5 hover:bg-blue-100 dark:hover:bg-blue-500/20 transition-colors rounded-lg border border-blue-200 dark:border-blue-500/20 flex items-center justify-center text-sm text-blue-600 dark:text-blue-300 font-bold">5</div>
                                        <div class="bg-blue-50 dark:bg-blue-500/5 hover:bg-blue-100 dark:hover:bg-blue-500/20 transition-colors rounded-lg border border-blue-200 dark:border-blue-500/20 flex items-center justify-center text-sm text-blue-600 dark:text-blue-300 font-bold">6</div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        {{-- SECTION 38 --}}
                        <section id="section-38" class="lesson-section scroll-mt-32" data-lesson-id="38">
                            <div class="space-y-6">
                                <div class="flex items-center gap-4 mb-4">
                                    <h2 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight transition-colors">5. JIT Arbitrary Value</h2>
                                    <div class="h-px flex-1 bg-gradient-to-r from-slate-200 dark:from-white/10 to-transparent transition-colors"></div>
                                </div>
                                
                                <div class="space-y-4 text-justify">
                                    <p class="text-sm md:text-base text-slate-600 dark:text-white/70 transition-colors">
                                        Menurut jurnal teknis <em>"Modern CSS with Tailwind"</em>, sistem bawaan fraksional serba sama rata seringkali menabrak tembok kebuntuan ketika berhadapan dengan permintaan desain klien yang spesifik.
                                    </p>
                                    
                                    <div class="bg-red-50 dark:bg-red-500/5 border border-red-200 dark:border-red-500/20 p-4 rounded-xl transition-colors">
                                        <h4 class="text-red-600 dark:text-red-400 font-bold text-sm mb-2 flex items-center gap-2 transition-colors">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                            Masalah Khas Layout
                                        </h4>
                                        <ul class="list-disc pl-5 m-0 text-sm text-red-800/80 dark:text-red-100/70 space-y-1 transition-colors">
                                            <li>Area Sidebar dikunci mati lebarnya pada <code>80px</code> (Fixed) tidak peduli betapa lebarnya layar.</li>
                                            <li>Sedangkan area Artikel Konten harus menyerap semua sisa ruang yang ada (Fluid).</li>
                                        </ul>
                                    </div>

                                    <p class="text-sm md:text-base text-slate-600 dark:text-white/70 transition-colors">
                                        Tembok ini dihancurkan dengan kekuatan kompiler Just-in-Time (JIT) Tailwind. Sisipkan langsung <strong>Nilai Arbitrer Kustom</strong> ke dalam urat nadi HTML menggunakan kurung kurawal siku ajaib <code class="text-red-600 dark:text-red-400 font-bold bg-slate-100 dark:bg-white/10 px-1 rounded transition-colors">[]</code> tanpa perlu menyentuh file CSS murni.
                                    </p>
                                </div>

                                <div class="bg-white dark:bg-[#1e1e1e] p-5 lg:p-6 rounded-xl border border-slate-200 dark:border-white/10 shadow-md dark:shadow-lg group hover:border-red-400 dark:hover:border-red-500/30 transition-all">
                                    <div class="flex flex-col sm:flex-row justify-between mb-4 items-start sm:items-center gap-3 sm:gap-0">
                                        <span class="text-[10px] font-bold text-slate-500 dark:text-white/50 uppercase tracking-widest transition-colors">Tinjauan Rendering</span>
                                        <code class="text-[10px] text-red-600 dark:text-red-400 bg-red-100 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 px-3 py-1.5 rounded shadow-sm font-bold tracking-wide transition-colors">grid-cols-[80px_1fr_80px]</code>
                                    </div>
                                    <div class="grid grid-cols-[80px_1fr_80px] gap-3 h-48 bg-slate-100 dark:bg-black/40 p-5 rounded-xl border border-dashed border-slate-300 dark:border-white/10 shadow-inner transition-colors">
                                        <div class="bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/30 rounded-lg flex flex-col items-center justify-center text-[10px] text-red-600 dark:text-red-400 font-bold text-center shadow-sm transition-colors">Dikunci Fix<br><span class="text-sm mt-1">80px</span></div>
                                        <div class="bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/30 rounded-lg flex flex-col items-center justify-center text-[10px] text-green-600 dark:text-green-400 font-bold text-center shadow-sm transition-colors">Area Dinamis<br><span class="text-sm mt-1">(1fr)</span></div>
                                        <div class="bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/30 rounded-lg flex flex-col items-center justify-center text-[10px] text-red-600 dark:text-red-400 font-bold text-center shadow-sm transition-colors">Dikunci Fix<br><span class="text-sm mt-1">80px</span></div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>

                    {{-- SECTION 39: GRID AUTO FLOW --}}
                    <section id="section-39" class="lesson-section scroll-mt-32" data-lesson-id="39">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-teal-500 pl-6">
                                <span class="text-teal-600 dark:text-teal-400 font-mono text-xs uppercase tracking-widest transition-colors">Lesson 2.2.6</span>
                                <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-slate-900 dark:text-white leading-[1.1] transition-colors">
                                    Grid Auto <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-500 to-emerald-600 dark:from-teal-400 dark:to-emerald-500">Flow & Dense</span>
                                </h2>
                            </div>

                            <div class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 p-5 rounded-xl text-justify transition-colors">
                                        <h4 class="text-slate-800 dark:text-white font-bold mb-3 text-sm uppercase tracking-wide transition-colors">Cara Kerja Default (Z-Pattern)</h4>
                                        <p class="text-sm text-slate-600 dark:text-white/70 m-0 transition-colors">
                                            Di belakang panggung layar, kerangka kerja peramban memiliki mesin algoritma mutakhir (Auto Placement Algorithm) untuk membuang dan menjatuhkan item secara runut jika elemen tersebut "kehilangan" koordinat spesifiknya. Secara bawaan, rute yang ditempuh adalah <code class="bg-slate-200 dark:bg-white/10 px-1 rounded font-bold text-slate-600 dark:text-slate-300 transition-colors">grid-flow-row</code>, membentuk Pola-Z: mesin melempar item berbaris dari pelabuhan kiri menyusuri sisi kanan, lantas membanting baris baru ke dasar bawah.
                                        </p>
                                    </div>
                                    
                                    <div class="bg-teal-50 dark:bg-teal-500/10 border border-teal-200 dark:border-teal-500/30 p-5 rounded-xl text-justify transition-colors">
                                        <h4 class="text-teal-700 dark:text-teal-400 font-bold mb-3 text-sm uppercase tracking-wide flex items-center gap-2 transition-colors">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                            Solusi Cerdas: Mode Dense
                                        </h4>
                                        <p class="text-sm text-teal-800/80 dark:text-teal-100/70 m-0 transition-colors">
                                            Tatkala galeri Anda dibubuhi keragaman dimensi objek (ada yang mencaplok span-2 besar, ada pula yang mini seukuran span-1 reguler), fenomena ruang angkasa kosong/lubang acapkali mencoreng kanvas web lantaran baris di antreannya terlalu sempit. Keajaiban mode <strong><code class="text-teal-700 dark:text-teal-300 font-bold bg-teal-100 dark:bg-black/40 px-1.5 py-0.5 rounded border border-teal-200 dark:border-white/20 transition-colors">grid-flow-dense</code></strong> menitahkan algoritma untuk "memutar waktu" dan mencari balok mungil dari masa depan DOM lantas menancapkannya menambal rongga bolong nan cacat tersebut.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- SIMULATOR 3: AUTO FLOW --}}
                            <div class="bg-white dark:bg-[#0b0f19] border border-slate-200 dark:border-white/10 rounded-2xl p-6 lg:p-8 shadow-xl dark:shadow-2xl relative group hover:border-teal-400 dark:hover:border-teal-500/30 transition-all mt-8">
                                <h4 class="text-xs font-bold text-slate-400 dark:text-muted uppercase mb-4 text-center transition-colors tracking-widest">Simulator: Koreografi Arus Elemen</h4>
                                
                                {{-- Kotak Instruksi --}}
                                <div class="bg-teal-50 dark:bg-teal-500/10 border border-teal-200 dark:border-teal-500/30 rounded-xl p-4 mb-8 text-sm text-teal-700 dark:text-teal-300 relative z-10 shadow-sm dark:shadow-inner transition-colors">
                                    <p class="font-bold flex items-center gap-2 mb-2">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"></path></svg>
                                        Panduan Identifikasi Pola
                                    </p>
                                    <p class="m-0 opacity-90 leading-relaxed text-xs md:text-sm text-teal-800/80 dark:text-teal-100/80 text-justify transition-colors">
                                        Bandingkan jalur pergerakan angka (1, 2, 3, 4) antara panel aliran Baris (Row) di sisi sebelah kiri yang turun zigzag berbalut warna Emerald, dengan panel aliran Kolom (Col) di sisi perbatasan kanan yang tegak vertikal bergelimang nuansa Ungu (Purple).
                                    </p>
                                </div>

                                <div class="grid md:grid-cols-2 gap-8 relative z-10">
                                    <div class="bg-slate-50 dark:bg-[#151a26] p-5 lg:p-6 rounded-xl border border-slate-200 dark:border-white/5 shadow-sm dark:shadow-inner transition-colors">
                                        <div class="flex justify-between items-center mb-6">
                                            <code class="text-emerald-600 dark:text-emerald-400 font-bold bg-emerald-100 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 px-3 py-1.5 rounded shadow-sm text-xs font-mono transition-colors">grid-flow-row</code>
                                            <span class="text-[10px] font-bold text-emerald-500 dark:text-emerald-400/50 uppercase tracking-widest bg-emerald-50 dark:bg-emerald-500/5 border border-emerald-100 dark:border-emerald-500/10 px-2 py-1 rounded transition-colors">Z-Pattern Layout</span>
                                        </div>
                                        <div class="grid grid-rows-2 grid-flow-row gap-3 bg-slate-100 dark:bg-black/40 p-5 rounded-xl h-48 border border-dashed border-slate-300 dark:border-white/10 shadow-inner transition-colors">
                                            <div class="bg-gradient-to-br from-emerald-100 to-emerald-200 dark:from-emerald-500/20 dark:to-emerald-600/10 border border-emerald-300 dark:border-emerald-500/30 rounded-lg flex items-center justify-center text-emerald-700 dark:text-emerald-300 font-black text-xl shadow-sm transition hover:scale-[1.03]">1</div>
                                            <div class="bg-gradient-to-br from-emerald-100 to-emerald-200 dark:from-emerald-500/20 dark:to-emerald-600/10 border border-emerald-300 dark:border-emerald-500/30 rounded-lg flex items-center justify-center text-emerald-700 dark:text-emerald-300 font-black text-xl shadow-sm transition hover:scale-[1.03]">2</div>
                                            <div class="bg-gradient-to-br from-emerald-100 to-emerald-200 dark:from-emerald-500/20 dark:to-emerald-600/10 border border-emerald-300 dark:border-emerald-500/30 rounded-lg flex items-center justify-center text-emerald-700 dark:text-emerald-300 font-black text-xl shadow-sm transition hover:scale-[1.03]">3</div>
                                            <div class="bg-gradient-to-br from-emerald-100 to-emerald-200 dark:from-emerald-500/20 dark:to-emerald-600/10 border border-emerald-300 dark:border-emerald-500/30 rounded-lg flex items-center justify-center text-emerald-700 dark:text-emerald-300 font-black text-xl shadow-sm transition hover:scale-[1.03]">4</div>
                                        </div>
                                    </div>
                                    <div class="bg-slate-50 dark:bg-[#151a26] p-5 lg:p-6 rounded-xl border border-slate-200 dark:border-white/5 shadow-sm dark:shadow-inner transition-colors">
                                        <div class="flex justify-between items-center mb-6">
                                            <code class="text-purple-600 dark:text-purple-400 font-bold bg-purple-100 dark:bg-purple-500/10 border border-purple-200 dark:border-purple-500/20 px-3 py-1.5 rounded shadow-sm text-xs font-mono transition-colors">grid-flow-col</code>
                                            <span class="text-[10px] font-bold text-purple-500 dark:text-purple-400/50 uppercase tracking-widest bg-purple-50 dark:bg-purple-500/5 border border-purple-100 dark:border-purple-500/10 px-2 py-1 rounded transition-colors">N-Pattern Layout</span>
                                        </div>
                                        <div class="grid grid-rows-2 grid-flow-col gap-3 bg-slate-100 dark:bg-black/40 p-5 rounded-xl h-48 border border-dashed border-slate-300 dark:border-white/10 shadow-inner transition-colors">
                                            <div class="bg-gradient-to-br from-purple-100 to-purple-200 dark:from-purple-500/20 dark:to-purple-600/10 border border-purple-300 dark:border-purple-500/30 rounded-lg flex items-center justify-center text-purple-700 dark:text-purple-300 font-black text-xl shadow-sm transition hover:scale-[1.03]">1</div>
                                            <div class="bg-gradient-to-br from-purple-100 to-purple-200 dark:from-purple-500/20 dark:to-purple-600/10 border border-purple-300 dark:border-purple-500/30 rounded-lg flex items-center justify-center text-purple-700 dark:text-purple-300 font-black text-xl shadow-sm transition hover:scale-[1.03]">2</div>
                                            <div class="bg-gradient-to-br from-purple-100 to-purple-200 dark:from-purple-500/20 dark:to-purple-600/10 border border-purple-300 dark:border-purple-500/30 rounded-lg flex items-center justify-center text-purple-700 dark:text-purple-300 font-black text-xl shadow-sm transition hover:scale-[1.03]">3</div>
                                            <div class="bg-gradient-to-br from-purple-100 to-purple-200 dark:from-purple-500/20 dark:to-purple-600/10 border border-purple-300 dark:border-purple-500/30 rounded-lg flex items-center justify-center text-purple-700 dark:text-purple-300 font-black text-xl shadow-sm transition hover:scale-[1.03]">4</div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Kotak Kesimpulan --}}
                                <div class="mt-8 bg-gradient-to-r from-teal-50 dark:from-teal-500/10 to-transparent border-l-4 border-teal-500 p-5 rounded-r-xl relative z-10 transition-colors">
                                    <h5 class="text-sm font-bold text-teal-700 dark:text-teal-400 mb-2 flex items-center gap-2 transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Catatan Kesimpulan
                                    </h5>
                                    <p class="text-xs sm:text-sm text-slate-600 dark:text-white/70 leading-relaxed text-justify m-0 transition-colors">
                                        Mengetahui watak algoritma distribusi turunan Grid ini akan meloloskan Anda dari kebingungan tata letak blok yang kacau tanpa sengaja. Atribut `grid-flow-col` kerap digunakan untuk barisan daftar kategori memanjang yang meluber di seluler (Carousels), di mana konten ditata bergelontoran mengalir tak kenal henti ke bilah tepian kanan layar.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- FINAL MISSION (EXPERT MODE - MONACO EDITOR) --}}
                    <section id="section-40" class="lesson-section scroll-mt-32 pt-10 border-t border-slate-200 dark:border-white/10 transition-colors" data-lesson-id="40" data-type="activity">
                        <div class="relative rounded-[2rem] md:rounded-[2.5rem] bg-white dark:bg-[#050b14] border border-slate-200 dark:border-white/10 p-6 md:p-10 overflow-hidden shadow-xl dark:shadow-2xl group hover:border-cyan-400 dark:hover:border-cyan-500/30 transition-all duration-500 flex flex-col">
                            
                            <div class="absolute -top-24 -right-24 w-64 h-64 bg-cyan-400/20 dark:bg-cyan-600/20 blur-[100px] rounded-full pointer-events-none transition-colors"></div>

                            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 sm:gap-6 mb-8 relative z-10 shrink-0">
                                <div class="p-3 sm:p-4 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-xl text-white shadow-lg shadow-cyan-500/30 shrink-0">
                                    <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <div>
                                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-3 mb-2">
                                        <h2 class="text-xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight transition-colors">Final Mission: Bento Gallery</h2>
                                        <span class="px-2 py-0.5 rounded text-[9px] sm:text-[10px] font-bold bg-rose-100 dark:bg-rose-500/20 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-500/30 uppercase tracking-wider shadow-sm transition-colors">Expert Mode</span>
                                    </div>
                                    <p class="text-slate-500 dark:text-slate-400 text-xs sm:text-sm leading-relaxed max-w-2xl text-justify transition-colors">
                                        Sang Art Director klien kita menuntut arsitektur Galeri Foto asimetris ala antarmuka <em>Bento Grid</em> berdimensi 2x2. Turun tangan ke dalam arena editor di bawah dan suntikkan kode utilitas yang merealisasikan visi mereka. Ubah selah teratas membentang lebar bagaikan bentangan kanvas utuh.
                                    </p>
                                    
                                    {{-- TOOLBOX CLUE --}}
                                    <div class="mt-4 flex flex-wrap items-center gap-2">
                                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mr-1 transition-colors">Petunjuk Persenjataan Kelas:</span>
                                        <code class="text-[10px] md:text-xs font-mono font-bold text-cyan-600 dark:text-cyan-400 bg-cyan-100 dark:bg-cyan-500/10 border border-cyan-200 dark:border-cyan-500/20 px-2 py-1 rounded shadow-sm transition-colors">grid</code>
                                        <code class="text-[10px] md:text-xs font-mono font-bold text-cyan-600 dark:text-cyan-400 bg-cyan-100 dark:bg-cyan-500/10 border border-cyan-200 dark:border-cyan-500/20 px-2 py-1 rounded shadow-sm transition-colors">grid-cols-2</code>
                                        <code class="text-[10px] md:text-xs font-mono font-bold text-teal-600 dark:text-teal-400 bg-teal-100 dark:bg-teal-500/10 border border-teal-200 dark:border-teal-500/20 px-2 py-1 rounded shadow-sm transition-colors">gap-4</code>
                                        <code class="text-[10px] md:text-xs font-mono font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-100 dark:bg-indigo-500/10 border border-indigo-200 dark:border-indigo-500/20 px-2 py-1 rounded shadow-sm transition-colors">col-span-2</code>
                                        <code class="text-[10px] md:text-xs font-mono font-bold text-purple-600 dark:text-purple-400 bg-purple-100 dark:bg-purple-500/10 border border-purple-200 dark:border-purple-500/20 px-2 py-1 rounded shadow-sm transition-colors">flex</code>
                                        <code class="text-[10px] md:text-xs font-mono font-bold text-purple-600 dark:text-purple-400 bg-purple-100 dark:bg-purple-500/10 border border-purple-200 dark:border-purple-500/20 px-2 py-1 rounded shadow-sm transition-colors">items-center</code>
                                        <code class="text-[10px] md:text-xs font-mono font-bold text-purple-600 dark:text-purple-400 bg-purple-100 dark:bg-purple-500/10 border border-purple-200 dark:border-purple-500/20 px-2 py-1 rounded shadow-sm transition-colors">justify-center</code>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-col xl:grid xl:grid-cols-2 gap-0 border border-slate-200 dark:border-white/10 rounded-2xl overflow-hidden shadow-lg dark:shadow-2xl relative z-10 flex-1 transition-colors">
                                
                                {{-- EDITOR KIRI --}}
                                <div class="bg-slate-50 dark:bg-[#151515] border-b xl:border-b-0 xl:border-r border-slate-200 dark:border-white/10 flex flex-col relative w-full xl:w-auto min-h-[500px] xl:min-h-[600px] transition-colors">
                                    
                                    {{-- LOCK OVERLAY --}}
                                    <div id="lockOverlay" class="hidden absolute inset-0 bg-white/95 dark:bg-[#050912]/95 backdrop-blur-md z-50 flex flex-col items-center justify-center text-center p-8 transition-colors border-2 border-emerald-500/20">
                                        <div class="w-20 h-20 bg-emerald-100 dark:bg-emerald-500/10 rounded-full flex items-center justify-center mb-6 border border-emerald-300 dark:border-emerald-500/50 shadow-[0_0_50px_rgba(16,185,129,0.2)] animate-bounce transition-colors">
                                            <svg class="w-10 h-10 text-emerald-600 dark:text-emerald-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                        </div>
                                        <h3 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white mb-2 tracking-tight transition-colors">ARSITEKTUR GRID VALID!</h3>
                                        <p class="text-xs sm:text-sm font-bold text-slate-500 dark:text-white/60 mb-8 max-w-xs transition-colors">Penguasaan kerangka asimetris Anda sempurna. Data riwayat kemajuan telah terkunci dan tersinkronisasi server.</p>
                                        <button disabled class="w-full sm:w-auto px-8 py-3 rounded-full bg-slate-200 dark:bg-white/5 border border-slate-300 dark:border-white/10 text-slate-400 dark:text-white/30 text-[10px] sm:text-xs font-bold cursor-not-allowed uppercase tracking-widest transition-colors">Akses Terminal Terblokir</button>
                                    </div>

                                    <div class="bg-slate-100 dark:bg-[#1e1e1e] px-4 py-3 border-b border-slate-200 dark:border-white/5 flex justify-between items-center shrink-0 transition-colors">
                                        <span class="text-[10px] sm:text-xs text-slate-500 dark:text-white/50 font-mono font-bold transition-colors">Bento-Layout.html</span>
                                        <button onclick="resetEditor()" class="text-[10px] text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 uppercase font-bold focus:outline-none bg-red-100 dark:bg-red-500/10 px-3 py-1.5 rounded shadow-sm border border-red-200 dark:border-red-500/20 active:scale-95 transition">Hapus Tinta</button>
                                    </div>
                                    
                                    <div id="codeEditor" class="flex-1 w-full border-b border-slate-200 dark:border-white/5 min-h-[250px] relative transition-colors"></div>

                                    <div class="p-5 bg-slate-50 dark:bg-[#0f141e] flex flex-col shrink-0 h-auto sm:h-[230px] transition-colors">
                                        <div class="flex justify-between items-center mb-3">
                                            <span class="text-[10px] uppercase font-bold text-slate-400 dark:text-white/30 tracking-widest transition-colors">Kriteria Unit Test Render</span>
                                            <span id="progressText" class="text-[9px] sm:text-[10px] font-mono font-bold text-cyan-700 dark:text-cyan-400 bg-cyan-100 dark:bg-cyan-900/30 px-2 py-0.5 rounded border border-cyan-200 dark:border-cyan-500/20 shadow-inner transition-colors">0/3 Terpenuhi</span>
                                        </div>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-[10px] sm:text-[11px] font-mono text-slate-600 dark:text-white/50 mb-4 flex-1 overflow-y-auto custom-scrollbar p-3 bg-white dark:bg-black/20 rounded-lg shadow-sm dark:shadow-inner border border-slate-200 dark:border-white/5 transition-colors">
                                            <div id="check-grid" class="flex items-start gap-2.5"><span class="w-3.5 h-3.5 mt-0.5 rounded-full border border-slate-300 dark:border-white/20 flex items-center justify-center text-[8px] shrink-0 transition-colors"></span> <div><b class="block mb-1 text-slate-800 dark:text-white/80 transition-colors">Sangkar Utama Galeri (#gallery-container):</b> Terapkan arsitektur grid yang mengandalkan sumbu 2 kolom dengan jeda gap bernilai 4.</div></div>
                                            <div id="check-span" class="flex items-start gap-2.5"><span class="w-3.5 h-3.5 mt-0.5 rounded-full border border-slate-300 dark:border-white/20 flex items-center justify-center text-[8px] shrink-0 transition-colors"></span> <div><b class="block mb-1 text-slate-800 dark:text-white/80 transition-colors">Gambar Utama Atas (#featured-item):</b> Perintahkan modul ini untuk membentang memotong batas melewati bentangan penuh sebanyak 2 kolom.</div></div>
                                            <div id="check-align" class="flex items-start gap-2.5 sm:col-span-2 mt-2 pt-2 border-t border-slate-200 dark:border-white/5 transition-colors"><span class="w-3.5 h-3.5 mt-0.5 rounded-full border border-slate-300 dark:border-white/20 flex items-center justify-center text-[8px] shrink-0 transition-colors"></span> <div><b class="block mb-1 text-slate-800 dark:text-white/80 transition-colors">Potret Sebelah Kiri (#center-item):</b> Bangkitkan fleksibilitas pada kotak tersebut lalu paksa elemen teks di rongga bagian dalam untuk menempati titik ekuator secara vertikal sekaligus horizontal pas di titik tengahnya secara absolut.</div></div>
                                        </div>
                                        <button id="submitExerciseBtn" onclick="submitExercise()" disabled class="w-full py-2.5 sm:py-3 rounded-lg bg-emerald-600 text-white font-bold text-[11px] sm:text-xs shadow-lg shadow-emerald-500/20 hover:bg-emerald-500 hover:-translate-y-0.5 transition-all cursor-not-allowed opacity-50 flex items-center justify-center gap-2 focus:outline-none active:scale-95">
                                            <span>Selesaikan Semua Syarat Pemeriksaan Dulu</span>
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                        </button>
                                    </div>
                                </div>

                                {{-- PREVIEW KANAN --}}
                                <div class="bg-slate-100 dark:bg-[#1e1e1e] flex flex-col relative overflow-hidden w-full xl:w-auto h-[400px] xl:h-auto transition-colors">
                                    <div class="bg-slate-200 dark:bg-[#2d2d2d] px-4 py-3 border-b border-slate-300 dark:border-white/5 flex items-center justify-between shrink-0 transition-colors">
                                        <span class="text-[10px] text-slate-500 dark:text-gray-400 font-mono font-bold transition-colors">Virtual Browser Rendering Engine</span>
                                        <span class="text-[9px] sm:text-[10px] bg-emerald-100 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 px-2 py-0.5 rounded border border-emerald-200 dark:border-emerald-500/20 font-bold uppercase tracking-widest flex items-center gap-1.5 shadow-sm transition-colors">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse shadow-[0_0_5px_#10b981]"></span> Auto-Sync
                                        </span>
                                    </div>
                                    <div class="flex-1 bg-white dark:bg-gray-900 relative w-full h-full p-0 transition-colors">
                                        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.03] pointer-events-none mix-blend-overlay"></div>
                                        <iframe id="previewFrame" class="w-full h-full border-0 bg-transparent relative z-10 custom-scrollbar"></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                </article>

                {{-- FOOTER NAVIGATION --}}
                <div class="mt-32 pt-8 border-t border-slate-200 dark:border-white/10 flex flex-col sm:flex-row justify-between items-center gap-4 sm:gap-0 transition-colors">
                    <a href="{{ route('courses.flexbox') ?? '#' }}" class="group flex items-center gap-4 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition w-full sm:w-auto justify-center sm:justify-start">
                        <div class="w-10 h-10 md:w-12 md:h-12 rounded-full border border-slate-200 dark:border-white/10 flex items-center justify-center bg-slate-100 dark:bg-transparent group-hover:bg-slate-200 dark:group-hover:bg-white/5 transition shrink-0">
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        </div>
                        <div class="text-center sm:text-left">
                            <div class="text-[10px] sm:text-xs uppercase tracking-widest font-bold opacity-50 mb-0.5">Sebelumnya</div>
                            <div class="font-bold text-xs md:text-sm line-clamp-1">Dasar Flexbox </div>
                        </div>
                    </a>
                    
                    <div id="nextChapterBtn" class="group flex items-center gap-4 text-right text-slate-500 cursor-not-allowed opacity-50 pointer-events-none transition-all duration-500 w-full sm:w-auto justify-center sm:justify-end flex-row-reverse sm:flex-row">
                        <div class="text-center sm:text-right">
                            <div id="nextLabel" class="text-[10px] sm:text-xs uppercase tracking-widest font-bold opacity-50 mb-0.5 text-rose-500 dark:text-rose-400 transition-colors">Berikutnya</div>
                            <div class="font-bold text-xs md:text-sm line-clamp-1">Dasar Layouting</div>
                        </div>
                        <div id="nextIcon" class="w-10 h-10 md:w-12 md:h-12 rounded-full border border-slate-200 dark:border-white/5 flex items-center justify-center bg-slate-100 dark:bg-white/5 shrink-0 transition-colors">
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/monaco-editor@0.45.0/min/vs/loader.js"></script>

<script>
    /* --- 1. CONFIGURATION (AJAX & DATABASE) --- */
    window.LESSON_IDS = [34, 35, 36, 37, 38, 39, 40]; 
    let rawCompletedIds = {!! json_encode($completedLessonIds ?? []) !!};
    window.COMPLETED_IDS = rawCompletedIds.map(id => Number(id)); 
    let completedSet = new Set(window.COMPLETED_IDS);
    
    // Config Aktivitas Bab 2.2
    const ACTIVITY_LESSON_ID = 40; 
    let activityCompleted = completedSet.has(ACTIVITY_LESSON_ID);

    /* --- 2. INITIALIZATION --- */
    document.addEventListener('DOMContentLoaded', () => {
        initSidebarScroll();
        initVisualEffects();
        
        updateProgressUI(false); 
        
        initMonaco();
        
        if (activityCompleted) {
            lockActivityUI();
            unlockNextChapter();
        }

        initMasterObserver();
        
        document.querySelectorAll('.nav-item').forEach(item => {
            const targetId = parseInt(item.getAttribute('data-target').replace('#section-', ''));
            if(completedSet.has(targetId)) {
                markSidebarDone(targetId);
            }
        });
    });

    /* ==========================================
       LOGIKA KEMAJUAN PELAJARAN (PROGRESS BAR)
       ========================================== */
    function updateProgressUI(animate = true) {
        const total = window.LESSON_IDS.length; 
        const done = window.LESSON_IDS.filter(id => completedSet.has(Number(id))).length; 
        const percent = Math.round((done / total) * 100);
        
        const bar = document.getElementById('topProgressBar');
        const label = document.getElementById('progressLabelTop');
        
        if(!animate) bar.style.transition = 'none';
        bar.style.width = percent + '%'; 
        if(!animate) setTimeout(() => bar.style.transition = 'all 0.5s', 50);
        
        label.innerText = percent + '%';
        if(percent === 100 && activityCompleted) unlockNextChapter();
    }

    function markSidebarDone(lessonId) {
        const navItem = document.querySelector(`.nav-item[data-target="#section-${lessonId}"]`);
        if(navItem) {
            const dot = navItem.querySelector('.dot');
            if(dot) {
                const isActivity = navItem.querySelector('.sidebar-anchor')?.dataset.type === 'activity';
                if (isActivity) {
                    dot.outerHTML = `<svg class="w-4 h-4 text-cyan-600 dark:text-cyan-400 shrink-0 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>`;
                } else {
                    dot.outerHTML = `<svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400 shrink-0 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>`;
                }
            }
        }
    }

    /* ==========================================
       AJAX POST REQUEST KE DATABASE
       ========================================== */
    async function saveLessonToDB(lessonId) { 
        lessonId = Number(lessonId);
        if(completedSet.has(lessonId)) return; 

        try {
            const formData = new FormData();
            formData.append('lesson_id', lessonId);

            const response = await fetch('{{ route("lesson.complete") }}', { 
                method: 'POST', 
                headers: { 
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json' 
                }, 
                body: formData 
            });

            if (response.ok) {
                completedSet.add(lessonId);
                updateProgressUI(true);
                markSidebarDone(lessonId);
            }
        } catch(e) {
            console.error('Network Error:', e);
        }
    }

    /* ==========================================
       MASTER SCROLL OBSERVER
       ========================================== */
    function initMasterObserver() {
        const mainScroll = document.getElementById('mainScroll'); 
        const sections = document.querySelectorAll('.lesson-section');

        if (mainScroll && sections.length > 0) {
            const observerOptions = { 
                root: mainScroll, 
                rootMargin: "-10% 0px -60% 0px", 
                threshold: 0 
            };
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const targetId = entry.target.id;
                        const lessonId = Number(entry.target.dataset.lessonId);
                        const isActivity = entry.target.dataset.type === 'activity';

                        if (typeof highlightAnchor === 'function') {
                            highlightAnchor(targetId);
                        }

                        if (lessonId && !isActivity && !completedSet.has(lessonId)) {
                            saveLessonToDB(lessonId); 
                        }
                    }
                });
            }, observerOptions);

            sections.forEach(section => observer.observe(section));
        }
    }

    /* --- 4. FINISH LOGIC (ACTIVITY EXPERT MONACO) --- */
    let editor;
    const starterCode = `<div class="bg-slate-900 min-h-screen w-full flex items-center justify-center p-8">
  
  <div id="gallery-container" class="bg-slate-800 p-4 rounded-xl max-w-2xl w-full">

    <div id="featured-item" class="h-48 bg-gradient-to-r from-cyan-500 to-blue-500 rounded-lg flex items-center justify-center font-bold text-white text-xl shadow-lg">
      Featured Panorama
    </div>

    <div id="center-item" class="h-32 bg-slate-700 rounded-lg text-cyan-400 font-bold border border-cyan-500/30">
      Portrait A
    </div>

    <div class="h-32 bg-slate-700 rounded-lg flex items-center justify-center text-white/50 font-bold border border-white/10">
      Portrait B
    </div>

  </div>
  
</div>`;

    function initMonaco() {
        require.config({ paths: { vs: 'https://cdn.jsdelivr.net/npm/monaco-editor@0.45.0/min/vs' } });
        require(['vs/editor/editor.main'], function () {
            
            editor = monaco.editor.create(document.getElementById('codeEditor'), {
                value: starterCode, 
                language: 'html', 
                theme: 'vs-dark', // Force dark theme for cosmic design consistently
                fontSize: window.innerWidth < 768 ? 12 : 14,
                minimap: { enabled: false }, 
                automaticLayout: true, 
                padding: { top: 16, bottom: 16 }, 
                lineNumbers: 'on',
                scrollBeyondLastLine: false,
                wordWrap: 'on',
                formatOnPaste: true,
            });
            
            window.addEventListener('resize', () => { if(editor) editor.layout(); });

            updatePreview(starterCode);
            
            if (activityCompleted) {
                lockActivityUI();
            }
            
            editor.onDidChangeModelContent(() => {
                if(activityCompleted) return;
                const code = editor.getValue();
                updatePreview(code);
                validateCodeRegex(code);
            });
        });
    }

    function updatePreview(code) {
        const frame = document.getElementById('previewFrame');
        const content = `
        <!doctype html>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <script src="https://cdn.tailwindcss.com"><\/script>
            <style>
                body { margin: 0; padding: 0; background-color: transparent; }
                * { transition: all 0.3s ease-in-out; }
            </style>
        </head>
        <body class="w-full h-full flex items-center justify-center">
            ${code}
        </body>
        </html>`;
        frame.srcdoc = content;
    }

    function validateCodeRegex(code) {
        let passed = 0;
        
        const galleryMatch = code.match(/id="gallery-container"[^>]*class="([^"]*)"/);
        const featMatch = code.match(/id="featured-item"[^>]*class="([^"]*)"/);
        const centerMatch = code.match(/id="center-item"[^>]*class="([^"]*)"/);

        const galCls = galleryMatch ? galleryMatch[1] : '';
        const featCls = featMatch ? featMatch[1] : '';
        const cenCls = centerMatch ? centerMatch[1] : '';

        const checks = [
            { id: 'check-grid', valid: /\bgrid\b/.test(galCls) && /\bgrid-cols-2\b/.test(galCls) && /\bgap-[1-9]\b/.test(galCls) },
            { id: 'check-span', valid: /\bcol-span-2\b/.test(featCls) || /\bcol-span-full\b/.test(featCls) },
            { id: 'check-align', valid: (/\bflex\b/.test(cenCls) && /\bjustify-center\b/.test(cenCls) && /\bitems-center\b/.test(cenCls)) || /\bplace-items-center\b/.test(cenCls) }
        ];

        checks.forEach(c => {
            const el = document.getElementById(c.id);
            if (!el) return;
            const dot = el.querySelector('span'); 
            const textDiv = el.querySelector('div');
            
            if(c.valid) {
                textDiv.classList.add('text-green-500', 'dark:text-green-400');
                textDiv.querySelector('b').classList.add('text-green-600', 'dark:text-green-300');
                textDiv.querySelector('b').classList.remove('text-slate-800', 'dark:text-white/80');
                dot.innerHTML = '✓'; 
                dot.classList.remove('border-slate-300', 'dark:border-white/20');
                dot.classList.add('bg-emerald-500', 'border-transparent', 'text-white');
                passed++;
            } else {
                textDiv.classList.remove('text-green-500', 'dark:text-green-400');
                textDiv.querySelector('b').classList.remove('text-green-600', 'dark:text-green-300');
                textDiv.querySelector('b').classList.add('text-slate-800', 'dark:text-white/80');
                dot.innerHTML = ''; 
                dot.classList.remove('bg-emerald-500', 'border-transparent', 'text-white');
                dot.classList.add('border-slate-300', 'dark:border-white/20');
            }
        });

        document.getElementById('progressText').innerText = passed + '/3 Selesai';
        
        const btn = document.getElementById('submitExerciseBtn');
        if (passed === 3) {
            btn.disabled = false;
            btn.classList.remove('cursor-not-allowed', 'opacity-50');
            btn.innerHTML = '<span>Simpan Arsitektur Server & Lanjut</span><svg class="w-4 h-4 sm:w-5 sm:h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
        } else {
            btn.disabled = true;
            btn.classList.add('cursor-not-allowed', 'opacity-50');
            btn.innerHTML = '<span>Selesaikan Semua Syarat Test Dulu</span><svg class="w-4 h-4 sm:w-5 sm:h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>';
        }
    }

    function resetEditor() { 
        if(editor && !activityCompleted) { 
            editor.setValue(starterCode); 
            validateCodeRegex(starterCode); 
        } 
    }

    async function submitExercise() {
        const btn = document.getElementById('submitExerciseBtn');
        btn.innerHTML = '<span class="animate-pulse">Menyimpan Tinta Komando ke Database...</span>'; 
        btn.disabled = true;
        
        try {
            await saveLessonToDB(ACTIVITY_LESSON_ID); 
            activityCompleted = true;
            lockActivityUI();   
            unlockNextChapter(); 
        } catch(e) { 
            console.error(e); 
            btn.innerHTML = "Sistem Menolak Koneksi. Silakan Coba Tekan Lagi.";
            btn.disabled = false;
            btn.classList.add('shake'); setTimeout(() => btn.classList.remove('shake'), 500);
        }
    }

    function lockActivityUI() {
        document.getElementById('lockOverlay').classList.remove('hidden');
        if(editor) editor.updateOptions({ readOnly: true });
        
        const btn = document.getElementById('submitExerciseBtn'); 
        btn.innerText = "Sistem Arsitektur Modul Terkunci Otomatis"; 
        btn.disabled = true;
        btn.classList.remove('bg-emerald-600', 'hover:bg-emerald-500');
        btn.classList.add('bg-slate-200', 'dark:bg-slate-700', 'text-slate-500', 'dark:text-slate-400', 'cursor-not-allowed', 'shadow-none');
        
        if(editor && activityCompleted) {
            editor.setValue(`<div class="bg-slate-900 min-h-screen w-full flex items-center justify-center p-8">\n  <div id="gallery-container" class="grid grid-cols-2 gap-4 bg-slate-800 p-4 rounded-xl max-w-2xl w-full">\n\n    <div id="featured-item" class="col-span-2 h-48 bg-gradient-to-r from-cyan-500 to-blue-500 rounded-lg flex items-center justify-center font-bold text-white text-xl shadow-lg">\n      Featured Panorama\n    </div>\n\n    <div id="center-item" class="flex justify-center items-center h-32 bg-slate-700 rounded-lg text-cyan-400 font-bold border border-cyan-500/30">\n      Portrait A\n    </div>\n\n    <div class="h-32 bg-slate-700 rounded-lg flex items-center justify-center text-white/50 font-bold border border-white/10">\n      Portrait B\n    </div>\n\n  </div>\n</div>`);
            validateCodeRegex(editor.getValue());
        }
    }

    function unlockNextChapter() {
        const btn = document.getElementById('nextChapterBtn');
        if(btn) {
            btn.classList.remove('cursor-not-allowed', 'opacity-50', 'pointer-events-none', 'text-slate-500');
            btn.classList.add('text-cyan-600', 'dark:text-cyan-400', 'hover:text-cyan-700', 'dark:hover:text-white', 'cursor-pointer');
            
            document.getElementById('nextLabel').innerText = "Rute Modul Telah Terbuka";
            document.getElementById('nextLabel').classList.remove('opacity-50', 'text-rose-500', 'dark:text-rose-400');
            document.getElementById('nextLabel').classList.add('text-cyan-600', 'dark:text-cyan-400', 'opacity-100');
            
            const icon = document.getElementById('nextIcon');
            icon.innerHTML = `<svg class="w-5 h-5 md:w-6 md:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>`;
            icon.classList.remove('bg-slate-100', 'dark:bg-white/5');
            icon.classList.add('bg-cyan-100', 'dark:bg-cyan-500/10', 'border-cyan-300', 'dark:border-cyan-500/30', 'text-cyan-600', 'dark:text-cyan-400', 'shadow-md', 'dark:shadow-lg');
            
            btn.onclick = () => window.location.href = "{{ route('courses.layout-mgmt') ?? '#' }}"; 
        }
    }

    /* --- 5. SIMULATOR LOGIC INTERAKTIF --- */
    window.updateGridCols = function(n, btn) {
        const grid = document.getElementById('demo-grid-cols');
        const code = document.getElementById('code-grid-cols');
        grid.className = `grid grid-cols-${n} gap-4 p-4 lg:p-6 bg-slate-100 dark:bg-black/40 rounded-xl border-2 border-dashed border-slate-300 dark:border-white/10 transition-all duration-500 relative overflow-hidden min-h-[160px]`;
        code.innerText = `class="grid grid-cols-${n} gap-4"`;
        document.querySelectorAll('.col-btn').forEach(b => b.className = "col-btn px-4 py-2 text-[10px] sm:text-xs font-bold rounded bg-slate-200 dark:bg-white/5 text-slate-600 dark:text-white/50 hover:bg-slate-300 dark:hover:bg-white/10 transition shrink-0 focus:outline-none");
        btn.className = "col-btn px-4 py-2 text-[10px] sm:text-xs font-bold rounded bg-cyan-600 text-white shadow-lg transition active-btn shrink-0 focus:outline-none";
        let html = '<div class="absolute inset-0 bg-[url(\'https://grainy-gradients.vercel.app/noise.svg\')] opacity-10 pointer-events-none"></div>';
        for(let i=1; i<=(n>2?6:4); i++) {
            html += `<div class="h-16 lg:h-20 bg-cyan-100 dark:bg-cyan-500/10 border border-cyan-300 dark:border-cyan-500/30 text-cyan-600 dark:text-cyan-400 flex items-center justify-center font-bold rounded-lg text-lg lg:text-xl relative z-10 transition-transform hover:scale-105">${i}</div>`;
        }
        grid.innerHTML = html;
    };

    window.updateJustify = function(val) {
        const demo = document.getElementById('demo-justify');
        const code = document.getElementById('code-justify');
        demo.classList.remove('justify-items-start', 'justify-items-center', 'justify-items-end', 'justify-items-stretch');
        demo.classList.add(val);
        code.innerText = val;
    };

    window.updateAlign = function(val) {
        const demo = document.getElementById('demo-align');
        const code = document.getElementById('code-align');
        demo.classList.remove('items-start', 'items-center', 'items-end', 'items-stretch');
        demo.classList.add(val);
        code.innerText = val;
    };

    /* --- 7. SCROLL SPY & SIDEBAR LOGIC --- */
    function highlightAnchor(id) {
        const anchors = document.querySelectorAll('.sidebar-anchor');

        anchors.forEach(a => {
            a.classList.remove('bg-slate-100', 'dark:bg-white/5', 'border-cyan-500', 'border-amber-500');
            a.classList.add('border-transparent');
            
            const dot = a.querySelector('.anchor-dot');
            const isActivity = a.dataset.type === 'activity';

            if(dot) dot.classList.remove('scale-125', 'shadow-sm', 'dark:shadow-[0_0_10px_#06b6d4]', 'dark:shadow-[0_0_10px_#f59e0b]', 'bg-cyan-500', 'dark:bg-cyan-400');
            
            if (isActivity) {
                if(dot) { dot.classList.remove('bg-amber-400'); dot.classList.add('bg-slate-300', 'dark:bg-slate-600'); }
            } else {
                if(dot) { dot.classList.remove('bg-cyan-500', 'dark:bg-cyan-400'); dot.classList.add('bg-slate-300', 'dark:bg-slate-600'); }
            }

            const text = a.querySelector('.anchor-text');
            if(text) { text.classList.remove('text-slate-900', 'dark:text-white', 'font-bold'); text.classList.add('text-slate-500', 'dark:text-slate-500'); }
        });

        const activeAnchor = document.querySelector(`.sidebar-anchor[data-target="${id}"]`);
        if (activeAnchor) {
            const isActivity = activeAnchor.dataset.type === 'activity';
            
            activeAnchor.classList.add('bg-slate-100', 'dark:bg-white/5');
            activeAnchor.classList.add(isActivity ? 'border-amber-500' : 'border-cyan-500');
            activeAnchor.classList.remove('border-transparent');
            
            const dot = activeAnchor.querySelector('.anchor-dot');
            if(dot) {
                dot.classList.remove('bg-slate-300', 'dark:bg-slate-600');
                if (isActivity) {
                    dot.classList.add('bg-amber-500', 'dark:bg-amber-400', 'scale-125', 'shadow-sm', 'dark:shadow-[0_0_10px_#f59e0b]');
                } else {
                    dot.classList.add('bg-cyan-600', 'dark:bg-cyan-400', 'scale-125', 'shadow-sm', 'dark:shadow-[0_0_10px_#06b6d4]');
                }
            }
            
            const text = activeAnchor.querySelector('.anchor-text');
            if(text) { text.classList.remove('text-slate-500', 'dark:text-slate-500'); text.classList.add('text-slate-900', 'dark:text-white', 'font-bold'); }
        }
    }

    function toggleAccordion(id) {
        const content = document.getElementById(id);
        const icon = document.getElementById('icon-' + id.replace('collapse-', ''));
        if (content) {
            const isClosed = content.style.maxHeight === '0px' || content.style.maxHeight === '';
            content.style.maxHeight = isClosed ? content.scrollHeight + "px" : "0px";
            content.style.opacity = isClosed ? "1" : "0";
            if(icon) {
                 if(isClosed) icon.classList.add('rotate-180', 'bg-slate-200', 'dark:bg-white/10');
                 else icon.classList.remove('rotate-180', 'bg-slate-200', 'dark:bg-white/10');
            }
        }
    }

    function scrollToSection(id) {
        const el = document.getElementById(id);
        if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
        highlightAnchor(id); 
    }

    function toggleMobileSidebar() {
        const sidebar = document.getElementById('courseSidebar');
        const overlay = document.getElementById('mobileOverlay');
        
        if (sidebar.classList.contains('mobile-open')) {
            sidebar.classList.remove('mobile-open');
            overlay.classList.remove('show');
        } else {
            sidebar.classList.add('mobile-open');
            overlay.classList.add('show');
        }
    }

    function initSidebarScroll(){
        const m = document.getElementById('mainScroll');
        const l = document.querySelectorAll('.accordion-content .nav-item');
        if(!m) return;
        m.addEventListener('scroll', () => {
            let c = '';
            document.querySelectorAll('.lesson-section').forEach(s => {
                if (m.scrollTop >= s.offsetTop - 250) c = '#' + s.id;
            });
            l.forEach(k => {
                k.classList.remove('active');
                if (k.getAttribute('data-target') === c) k.classList.add('active');
            })
        });
    }

    function initVisualEffects(){
        const c = document.getElementById('stars');
        if(!c) return;
        const x = c.getContext('2d');
        function r(){ c.width = innerWidth; c.height = innerHeight; }
        r(); window.addEventListener('resize', r);
        let s=[];
        for(let i=0; i<100; i++) s.push({x:Math.random()*c.width, y:Math.random()*c.height, r:Math.random()*1.2, v:Math.random()*0.2+.1});
        
        function drawStars() {
            x.clearRect(0,0,c.width,c.height);
            x.fillStyle='rgba(255,255,255,.3)';
            s.forEach(t=>{
                x.beginPath(); x.arc(t.x,t.y,t.r,0,6.28); x.fill();
                t.y += t.v;
                if(t.y > c.height) t.y = 0;
            });
            requestAnimationFrame(drawStars);
        }
        drawStars();
    }
</script>
@endsection