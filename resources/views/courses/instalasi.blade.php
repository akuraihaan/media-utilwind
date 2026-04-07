@extends('layouts.landing')
@section('title', 'Instalasi & Konfigurasi')

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
        --accent: #06b6d4;
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

    body { font-family: 'Inter', sans-serif; background-color: var(--bg-main); color: var(--text-main); transition: background-color 0.4s, color 0.4s; }
    .font-mono { font-family: 'JetBrains Mono', monospace; }

    /* UTILITIES ADAPTIF */
    .bg-adaptive { background-color: var(--bg-main); }
    .text-adaptive { color: var(--text-main); }
    .text-heading { color: var(--text-heading); }
    .text-muted { color: var(--text-muted); }
    .border-adaptive { border-color: var(--border-color); }
    .card-adaptive { background-color: var(--card-bg); border-color: var(--glass-border); transition: all 0.3s; }
    .card-adaptive:hover { border-color: var(--accent-glow); }
    .sim-bg-adaptive { background-color: var(--simulator-bg); }
    .code-adaptive { background-color: var(--code-bg); border-color: var(--glass-border); }

    /* SCROLLBAR */
    .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(150,150,150,0.3); border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: var(--accent); }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }
    
    /* ANIMATIONS & EFFECTS */
    #animated-bg { 
        background: radial-gradient(800px circle at 20% 20%, rgba(99,102,241,.10), transparent 40%), 
                    radial-gradient(800px circle at 80% 80%, rgba(6,182,212,.10), transparent 40%); 
        animation: bgMove 20s ease-in-out infinite alternate; 
    }
    .dark #animated-bg {
        background: radial-gradient(800px circle at 20% 20%, rgba(99,102,241,.15), transparent 40%), 
                    radial-gradient(800px circle at 80% 80%, rgba(6,182,212,.15), transparent 40%); 
    }
    @keyframes bgMove{to{transform:scale(1.15)}}
    @keyframes shake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-5px); } 75% { transform: translateX(5px); } }
    .shake { animation: shake 0.4s ease-in-out; }
    
    /* RADAR SCANLINE */
    .scanline {
        background: linear-gradient(to bottom, transparent, rgba(16, 185, 129, 0.5), transparent);
        animation: scan 2s linear infinite;
    }
    .scanline-red {
        background: linear-gradient(to bottom, transparent, rgba(225, 29, 72, 0.5), transparent);
        animation: scan 2s linear infinite;
    }
    @keyframes scan { 0% { top: -100%; } 100% { top: 100%; } }

    @media (max-width: 1023px) {
        #courseSidebar { position: fixed; top: 64px; left: -100vw; height: calc(100vh - 64px); transition: left 0.3s ease-in-out; }
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

    /* CSS GRID UNTUK SIMULATOR */
    .visual-grid {
        background-size: 16px 16px;
        background-image: 
            linear-gradient(to right, rgba(150, 150, 150, 0.1) 1px, transparent 1px),
            linear-gradient(to bottom, rgba(150, 150, 150, 0.1) 1px, transparent 1px);
    }
</style>



<div id="courseRoot" class="relative h-screen bg-adaptive text-adaptive font-sans overflow-hidden flex flex-col selection:bg-cyan-500/30 pt-20 transition-colors duration-500">

    {{-- BACKGROUND EFFECTS --}}
    <div class="fixed inset-0 -z-50 pointer-events-none">
        <div id="animated-bg" class="absolute inset-0 opacity-50 transition-opacity"></div>
        <div class="absolute top-[-20%] left-[-10%] w-[1000px] h-[1000px] bg-indigo-500/5 dark:bg-indigo-900/20 rounded-full blur-[150px] animate-pulse transition-colors"></div>
        <div class="absolute bottom-[-20%] right-[-10%] w-[800px] h-[800px] bg-cyan-500/5 dark:bg-cyan-900/20 rounded-full blur-[100px] transition-colors"></div>
        <canvas id="stars" class="absolute inset-0 pointer-events-none opacity-0 dark:opacity-100 transition-opacity duration-500"></canvas>
        <div id="noise-overlay" class="absolute inset-0 z-10 opacity-[0.02] dark:opacity-[0.03]"></div>
    </div>

    @include('layouts.partials.navbar')

    <div class="flex flex-1 overflow-hidden relative z-20">

        @include('layouts.partials.course-sidebar')

        <main id="mainScroll" class="flex-1 h-full overflow-y-auto scroll-smooth relative bg-transparent custom-scrollbar scroll-padding-top-24">
            
            {{-- STICKY HEADER & PROGRESS BAR --}}
            <div id="stickyHeader" class="sticky top-0 z-30 w-full backdrop-blur-2xl border-b border-adaptive px-8 py-4 flex items-center justify-between transition-colors duration-500" style="background-color: var(--glass-header);">
                <div class="flex items-center gap-4">
                    <div class="w-8 h-8 rounded-lg bg-cyan-500/10 dark:bg-cyan-500/20 border border-cyan-500/20 flex items-center justify-center font-bold text-xs text-cyan-600 dark:text-cyan-400 transition-colors">1.6</div>
                    <div>
                        <h1 class="text-sm font-bold text-heading transition-colors line-clamp-1">Instalasi dan Konfigurasi</h1>
                        <p class="text-[10px] text-muted transition-colors line-clamp-1">Node.js, Antarmuka CLI, dan Pemindai JIT</p>
                    </div>
                </div>
                
                {{-- DYNAMIC PROGRESS BAR UI --}}
                <div class="flex items-center gap-3">
                    <div class="hidden sm:block w-32 h-1.5 bg-slate-200 dark:bg-white/10 rounded-full overflow-hidden transition-colors">
                        <div id="topProgressBar" class="h-full bg-gradient-to-r from-cyan-400 to-blue-500 w-0 transition-all duration-500 shadow-[0_0_10px_rgba(6,182,212,0.5)]"></div>
                    </div>
                    <span id="progressLabelTop" class="text-cyan-600 dark:text-cyan-400 font-bold text-xs transition-colors">0%</span>
                </div>
            </div>

            <div class="p-6 lg:p-16 max-w-5xl mx-auto pb-40">
                
                {{-- TUJUAN PEMBELAJARAN --}}
                <div class="mb-24">
                    <h3 class="text-xl font-bold text-heading mb-6 flex items-center gap-2 transition-colors">
                        <svg class="w-5 h-5 text-cyan-500 dark:text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        Tujuan Pembelajaran
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="card-adaptive border border-adaptive p-6 rounded-xl flex items-start gap-4 hover:border-purple-500/30 transition group h-full">
                            <div class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400 flex items-center justify-center shrink-0 font-bold text-sm border border-purple-200 dark:border-purple-500/10 transition-colors">1</div>
                            <div>
                                <h4 class="text-sm font-bold text-heading mb-2 group-hover:text-purple-500 dark:group-hover:text-purple-400 transition-colors">Kebutuhan Node.js</h4>
                                <p class="text-xs text-muted leading-relaxed transition-colors">Memahami fungsi lingkungan peladen (<span class="font-semibold text-purple-600 dark:text-purple-400">backend</span>) sebagai pendorong mesin kompilasi.</p>
                            </div>
                        </div>

                        <div class="card-adaptive border border-adaptive p-6 rounded-xl flex items-start gap-4 hover:border-cyan-500/30 transition group h-full">
                            <div class="w-10 h-10 rounded-lg bg-cyan-100 dark:bg-cyan-900/20 text-cyan-600 dark:text-cyan-400 flex items-center justify-center shrink-0 font-bold text-sm border border-cyan-200 dark:border-cyan-500/10 transition-colors">2</div>
                            <div>
                                <h4 class="text-sm font-bold text-heading mb-2 group-hover:text-cyan-500 dark:group-hover:text-cyan-400 transition-colors">Instalasi Terminal</h4>
                                <p class="text-xs text-muted leading-relaxed transition-colors">Mengeksekusi perintah antarmuka <span class="font-semibold text-cyan-600 dark:text-cyan-400">CLI</span> untuk merakit fail pondasi proyek terstruktur.</p>
                            </div>
                        </div>

                        <div class="card-adaptive border border-adaptive p-6 rounded-xl flex items-start gap-4 hover:border-emerald-500/30 transition group h-full">
                            <div class="w-10 h-10 rounded-lg bg-emerald-100 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 font-bold text-sm border border-emerald-200 dark:border-emerald-500/10 transition-colors">3</div>
                            <div>
                                <h4 class="text-sm font-bold text-heading mb-2 group-hover:text-emerald-500 dark:group-hover:text-emerald-400 transition-colors">Pemindai JIT</h4>
                                <p class="text-xs text-muted leading-relaxed transition-colors">Menetapkan koordinat lensa radar agar mesin merender kode desain secara <span class="font-semibold text-emerald-600 dark:text-emerald-400">instan</span>.</p>
                            </div>
                        </div>

                        <div class="card-adaptive border border-adaptive p-6 rounded-xl flex items-start gap-4 hover:border-violet-500/30 transition group h-full">
                            <div class="w-10 h-10 rounded-lg bg-violet-100 dark:bg-violet-900/20 text-violet-600 dark:text-violet-400 flex items-center justify-center shrink-0 font-bold text-sm border border-violet-200 dark:border-violet-500/10 transition-colors">4</div>
                            <div>
                                <h4 class="text-sm font-bold text-heading mb-2 group-hover:text-violet-500 dark:group-hover:text-violet-400 transition-colors">Arsitektur Tema</h4>
                                <p class="text-xs text-muted leading-relaxed transition-colors">Praktik penyisipan atribut desain yang aman dengan properti <span class="font-semibold text-violet-600 dark:text-violet-400">extend</span> vs override.</p>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-cyan-100 to-indigo-100 dark:from-cyan-900/10 dark:to-indigo-900/10 border border-cyan-300 dark:border-cyan-500/20 p-6 rounded-xl flex items-start gap-4 hover:shadow-[0_0_30px_rgba(6,182,212,0.15)] transition group h-full col-span-1 lg:col-span-4 cursor-pointer" onclick="document.getElementById('section-28').scrollIntoView({ behavior: 'smooth', block: 'start' });">
                            <div class="w-10 h-10 rounded-lg bg-white/50 dark:bg-white/10 text-cyan-700 dark:text-white flex items-center justify-center shrink-0 font-bold text-lg border border-white/10 shadow-sm dark:shadow-none transition-colors">🏁</div>
                            <div>
                                <h4 class="text-sm font-bold text-cyan-900 dark:text-white mb-2 transition-colors">Final Mission</h4>
                                <p class="text-xs text-cyan-800 dark:text-white/60 leading-relaxed max-w-2xl transition-colors">Studi Kasus Akhir: Lakukan diagnosis arsitektural untuk menemukan sumber kegagalan kompilasi pada lingkungan terminal simulasi interaktif.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <article class="space-y-40">
                    
                    {{-- LESSON 24: PRASYARAT SISTEM (NODE.JS) --}}
                    <section id="section-24" class="lesson-section scroll-mt-32" data-lesson-id="24">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-purple-500 pl-6">
                                <span class="text-purple-600 dark:text-purple-400 font-mono text-xs uppercase tracking-widest transition-colors">Lesson 1.6.1</span>
                                <h2 class="text-4xl lg:text-5xl font-black text-heading leading-[1.1] transition-colors">
                                    Prasyarat Mesin: <br> 
                                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-500 to-indigo-600 dark:from-purple-400 dark:to-indigo-500">Lingkungan Node.js</span>
                                </h2>
                            </div>
                            
                            <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-lg leading-relaxed space-y-4 text-justify transition-colors">
                                <p>Sebuah pertanyaan wajar di awal pengembangan adalah: Mengapa diperlukan sistem pemrograman peladen (backend) seperti <strong>Node.js</strong> hanya untuk menata gaya tampilan muka yang menggunakan bahasa penataan (CSS)? Jawabannya bertumpu pada mekanisme pemrosesan moderen.</p>
                                <p>Peramban internet standar membaca struktur deklarasi gaya secara murni (sebagai contoh, <code>margin: 10px;</code>). Mereka tidak dapat menerjemahkan label singkat seperti <code class="text-purple-600 dark:text-purple-400">m-2</code>. Tailwind CSS menengahi celah ini. Modul ini bertindak sebagai sebuah perangkat lunak penganalisa dan kompilator (<span class="font-semibold text-purple-600 dark:text-purple-400">Build Tool</span>) dinamis, bukan sekadar fail statis yang disisipkan ke kode HTML.</p>
                                <p>Sebagai peranti kompilator, ia memerlukan lingkungan komputasi yang beroperasi di balik layar. Node.js memenuhi peran tersebut. Saat Anda memasang Node.js pada sistem operasi, fitur pengelolaan paket perangkat lunak (<strong>Node Package Manager</strong> atau NPM) akan turut aktif. Fitur NPM bertugas mengunduh pustaka Tailwind dari peladen awan dan menatanya ke dalam direktori lokal proyek Anda.</p>
                            </div>

                            {{-- SIMULATOR 1: NODE VERIFICATION --}}
                            <div class="sim-bg-adaptive border border-adaptive rounded-xl overflow-hidden shadow-xl dark:shadow-2xl p-8 relative transition-colors mt-8">
                                <h4 class="text-xs font-bold text-muted uppercase mb-4 text-center transition-colors">Simulasi Logis: Validasi Infrastruktur Terminal</h4>
                                
                                {{-- KOTAK INSTRUKSI SIMULATOR 1 --}}
                                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-500/30 rounded-lg p-4 mb-8 text-sm text-blue-800 dark:text-blue-300 relative z-10">
                                    <p class="font-bold flex items-center gap-2 mb-2">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        💡 Panduan Simulasi 1
                                    </p>
                                    <ol class="list-decimal pl-5 space-y-1">
                                        <li>Pastikan perangkat pendukung sistem telah aktif di perangkat lokal Anda.</li>
                                        <li>Tekan opsi uji verifikasi di panel kiri dan pantau hasil umpan balik versi mesin (seperti <code>v20.11.0</code>) di jendela terminal sebelah kanan.</li>
                                    </ol>
                                </div>

                                <div class="grid lg:grid-cols-2 gap-10 relative z-10">
                                    {{-- Kiri: Buttons --}}
                                    <div class="flex flex-col justify-center space-y-4">
                                        <button onclick="runNodeCheckVis('node')" class="w-full px-5 py-4 bg-white dark:bg-[#0a0e17] hover:bg-emerald-50 dark:hover:bg-emerald-900/20 border border-slate-200 dark:border-white/10 rounded-xl text-sm font-bold transition-all text-left group shadow-sm flex items-center justify-between focus:outline-none">
                                            <div class="flex items-center gap-4">
                                                <div class="w-10 h-10 rounded-lg bg-slate-100 dark:bg-white/5 flex items-center justify-center group-hover:bg-emerald-500 group-hover:text-white transition-colors shadow-inner text-slate-500 dark:text-white">
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                                </div>
                                                <span class="text-slate-800 dark:text-white transition-colors text-base">Uji Lingkungan Node</span>
                                            </div>
                                            <span class="font-mono text-xs font-bold text-slate-400 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors bg-slate-100 dark:bg-black/30 px-2.5 py-1 rounded">node -v</span>
                                        </button>

                                        <button onclick="runNodeCheckVis('npm')" class="w-full px-5 py-4 bg-white dark:bg-[#0a0e17] hover:bg-blue-50 dark:hover:bg-blue-900/20 border border-slate-200 dark:border-white/10 rounded-xl text-sm font-bold transition-all text-left group shadow-sm flex items-center justify-between focus:outline-none">
                                            <div class="flex items-center gap-4">
                                                <div class="w-10 h-10 rounded-lg bg-slate-100 dark:bg-white/5 flex items-center justify-center group-hover:bg-blue-500 group-hover:text-white transition-colors shadow-inner text-slate-500 dark:text-white">
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                                                </div>
                                                <span class="text-slate-800 dark:text-white transition-colors text-base">Uji Pengelola NPM</span>
                                            </div>
                                            <span class="font-mono text-xs font-bold text-slate-400 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors bg-slate-100 dark:bg-black/30 px-2.5 py-1 rounded">npm -v</span>
                                        </button>
                                    </div>

                                    {{-- Kanan: Terminal Output --}}
                                    <div class="bg-slate-800 dark:bg-[#0a0e17] p-6 rounded-2xl border border-slate-700 dark:border-white/10 shadow-inner flex flex-col transition-colors min-h-[220px] relative overflow-hidden">
                                        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-5 pointer-events-none"></div>
                                        <div class="flex justify-between items-center border-b border-slate-700 dark:border-white/10 pb-3 mb-4 transition-colors relative z-10">
                                            <span class="font-bold text-slate-400 text-[10px] tracking-widest uppercase flex items-center gap-2">
                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg> 
                                                Output Log Terminal
                                            </span>
                                            <div class="flex gap-1.5"><div class="w-2.5 h-2.5 rounded-full bg-red-500/80"></div><div class="w-2.5 h-2.5 rounded-full bg-amber-400/80"></div><div class="w-2.5 h-2.5 rounded-full bg-emerald-500/80"></div></div>
                                        </div>
                                        <div id="vis-node-output" class="flex-1 flex flex-col justify-start gap-4 relative z-10 overflow-y-auto custom-scrollbar pr-2">
                                            <div class="text-center opacity-50 mt-8" id="vis-node-idle">
                                                <p class="text-xs font-mono text-slate-400 animate-pulse">Siaga menanti ketukan instruksi validasi_</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                {{-- KESIMPULAN SIMULASI 1 --}}
                                <div class="mt-8 bg-gradient-to-r from-purple-50 to-transparent dark:from-purple-900/20 dark:to-transparent border-l-4 border-purple-500 p-5 rounded-r-xl relative z-10 transition-colors">
                                    <h5 class="text-sm font-bold text-purple-700 dark:text-purple-400 mb-2 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> 
                                        Kesimpulan Analisis Infrastruktur
                                    </h5>
                                    <p class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed text-justify">
                                        Sistem komputasi <span class="font-semibold">Tailwind CSS</span> tidak bisa beroperasi di ruang hampa. Keberadaan Node.js dan NPM adalah prasyarat mutlak yang bertindak sebagai mesin pendorong utama di balik layar. Tanpa validasi versi yang berhasil pada langkah ini, instruksi pengunduhan dan kompilasi pada tahap selanjutnya dipastikan akan gagal tereksekusi.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 25: CLI --}}
                    <section id="section-25" class="lesson-section scroll-mt-32" data-lesson-id="25">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-cyan-500 pl-6">
                                <span class="text-cyan-600 dark:text-cyan-400 font-mono text-xs uppercase tracking-widest transition-colors">Lesson 1.6.2</span>
                                <h2 class="text-4xl lg:text-5xl font-black text-heading leading-[1.1] transition-colors">
                                    Tahapan Konstruksi: <br> 
                                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-500 to-blue-600 dark:from-cyan-400 dark:to-blue-500">Command Line Interface</span>
                                </h2>
                            </div>

                            <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-lg leading-relaxed space-y-4 text-justify transition-colors">
                                <p>Praktik industri yang paling direkomendasikan untuk membangkitkan kerangka proyek Tailwind secara bersih adalah melalui eksekusi Terminal Antarmuka Baris Perintah (<span class="font-semibold text-cyan-600 dark:text-cyan-400">Command Line Interface</span>).</p>
                                <p>Secara berurutan, Anda wajib melontarkan tiga instruksi esensial. Masing-masing perintah bertugas membangun fail sistem tertentu di dalam hirarki folder Anda:</p>
                                
                                <ul class="list-none pl-0 space-y-6 mt-4">
                                    <li class="flex items-start gap-4">
                                        <div class="w-8 h-8 rounded-lg bg-cyan-100 dark:bg-cyan-900/30 text-cyan-600 dark:text-cyan-400 flex items-center justify-center font-black text-sm shrink-0 border border-cyan-200 dark:border-cyan-500/30 mt-1 shadow-sm transition-colors">1</div>
                                        <div>
                                            <code class="text-sm font-bold text-slate-800 dark:text-white bg-slate-200 dark:bg-black/50 px-3 py-1.5 rounded shadow-inner inline-block mb-2 border border-slate-300 dark:border-white/10 transition-colors">npm init -y</code>
                                            <p class="text-base text-slate-600 dark:text-white/70 transition-colors leading-relaxed m-0">Menginisialisasi folder sebagai proyek Node. Perintah ini menciptakan fail pendaftaran <code class="text-sm">package.json</code> yang bertugas mendokumentasikan setiap pustaka program yang Anda pasang ke depannya.</p>
                                        </div>
                                    </li>
                                    <li class="flex items-start gap-4">
                                        <div class="w-8 h-8 rounded-lg bg-cyan-100 dark:bg-cyan-900/30 text-cyan-600 dark:text-cyan-400 flex items-center justify-center font-black text-sm shrink-0 border border-cyan-200 dark:border-cyan-500/30 mt-1 shadow-sm transition-colors">2</div>
                                        <div>
                                            <code class="text-sm font-bold text-slate-800 dark:text-white bg-slate-200 dark:bg-black/50 px-3 py-1.5 rounded shadow-inner inline-block mb-2 border border-slate-300 dark:border-white/10 transition-colors">npm install -D tailwindcss</code>
                                            <p class="text-base text-slate-600 dark:text-white/70 transition-colors leading-relaxed m-0">Menarik arsip kompilator dari peladen utama dan meletakkannya di direktori <code class="text-sm">node_modules</code>. Penanda parameter -D menegaskan ini diklasifikasikan sebagai modul pengembangan (<span class="font-semibold">Development Dependency</span>).</p>
                                        </div>
                                    </li>
                                    <li class="flex items-start gap-4">
                                        <div class="w-8 h-8 rounded-lg bg-cyan-100 dark:bg-cyan-900/30 text-cyan-600 dark:text-cyan-400 flex items-center justify-center font-black text-sm shrink-0 border border-cyan-200 dark:border-cyan-500/30 mt-1 shadow-sm transition-colors">3</div>
                                        <div>
                                            <code class="text-sm font-bold text-slate-800 dark:text-white bg-slate-200 dark:bg-black/50 px-3 py-1.5 rounded shadow-inner inline-block mb-2 border border-slate-300 dark:border-white/10 transition-colors">npx tailwindcss init</code>
                                            <p class="text-base text-slate-600 dark:text-white/70 transition-colors leading-relaxed m-0">Membentuk berkas pengontrol utama bernama <code class="text-sm">tailwind.config.js</code> di akar kerja Anda. Penyesuaian skema warna, batas lebar, dan injeksi font akan murni dikendalikan melalui fail ini.</p>
                                        </div>
                                    </li>
                                </ul>
                            </div>

                            {{-- SIMULATOR 2: CLI EXPLORER --}}
                            <div class="sim-bg-adaptive border border-adaptive rounded-xl overflow-hidden shadow-xl dark:shadow-2xl p-8 relative mt-8 transition-colors">
                                <h4 class="text-xs font-bold text-muted uppercase mb-4 text-center transition-colors">Simulator Logis: Rantai Eksekusi Perintah Sekuensial</h4>
                                
                                {{-- KOTAK INSTRUKSI SIMULATOR 2 --}}
                                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-500/30 rounded-lg p-4 mb-8 text-sm text-blue-800 dark:text-blue-300 relative z-10">
                                    <p class="font-bold flex items-center gap-2 mb-2">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        💡 Panduan Simulasi 2
                                    </p>
                                    <ol class="list-decimal pl-5 space-y-1">
                                        <li>Eksekusi rangkaian perintah di bawah ini secara bertahap pada Konsol Terminal Terpadu (panel kiri).</li>
                                        <li>Anda dapat mengetik secara manual lalu menekan <code>Enter</code>, atau mengklik tombol panduan instruksi.</li>
                                        <li>Pantau kemunculan arsip berkas baru secara langsung (real-time) pada penjelajah file di panel sebelah kanan.</li>
                                    </ol>
                                </div>

                                <div class="grid lg:grid-cols-5 min-h-[420px] border border-adaptive rounded-xl overflow-hidden shadow-sm transition-colors relative z-10">
                                    {{-- Left: Terminal Flow --}}
                                    <div class="lg:col-span-3 bg-slate-800 dark:bg-[#0f141e] p-6 lg:p-8 flex flex-col transition-colors relative border-r border-slate-700 dark:border-white/5">
                                        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-5 pointer-events-none"></div>
                                        
                                        <div class="flex justify-between items-center border-b border-slate-700 dark:border-white/10 pb-4 mb-6 transition-colors relative z-10">
                                            <span class="text-xs font-bold text-slate-400 dark:text-white/50 tracking-widest uppercase flex items-center gap-2"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg> Konsol Terminal Terpadu</span>
                                            <button onclick="resetCliSim()" class="text-[10px] text-red-500 hover:text-red-400 px-3 py-1.5 rounded bg-red-100 dark:bg-red-500/10 border border-red-300 dark:border-red-500/20 transition-colors focus:outline-none font-bold shadow-sm">Setel Ulang Simulasi</button>
                                        </div>
                                        
                                        <div class="space-y-4 font-mono text-xs flex-1 relative z-10 flex flex-col">
                                            <div class="border border-cyan-400 dark:border-cyan-500/50 p-5 rounded-xl bg-slate-700/80 dark:bg-slate-800/50 transition-all duration-300 mb-2 flex flex-col gap-3 shadow-inner" id="cli-instructor">
                                                <span class="text-slate-300 dark:text-slate-400 font-bold uppercase tracking-wider text-[10px] flex items-center gap-2" id="cli-hint-label"><div class="w-2 h-2 rounded-full bg-cyan-400 animate-pulse"></div> Instruksi Tahap 1: Inisialisasi</span>
                                                <button id="cli-hint-btn" class="text-left bg-slate-900 px-5 py-3 rounded-lg border border-slate-600 hover:border-cyan-400 text-cyan-400 font-bold transition-all focus:outline-none w-max shadow-md" onclick="autoType('npm init -y')">npm init -y</button>
                                            </div>

                                            <div id="cli-output" class="overflow-y-auto custom-scrollbar space-y-3 pb-4 flex-1 bg-slate-900/50 rounded-xl p-4 border border-slate-700 dark:border-white/5">
                                                <div class="flex items-center gap-3">
                                                    <span class="text-emerald-400 font-bold text-lg">➜</span>
                                                    <span class="text-cyan-400 font-bold tracking-wide">/pusat-proyek</span>
                                                    <input type="text" id="cli-input" class="bg-transparent border-none outline-none text-white w-full focus:ring-0 font-mono text-sm placeholder:text-slate-600" placeholder="Ketik lalu Enter..." autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Right: File Explorer --}}
                                    <div class="lg:col-span-2 bg-slate-50 dark:bg-[#050912] p-6 lg:p-8 transition-colors overflow-y-auto relative shadow-inner flex flex-col">
                                        <div class="text-[11px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-6 flex items-center gap-2 border-b border-slate-200 dark:border-slate-800 pb-3 transition-colors">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                                            Pemantau Struktur Direktori
                                        </div>
                                        
                                        <div class="space-y-3 font-mono text-[13px] text-slate-700 dark:text-slate-300 transition-colors font-medium flex-1">
                                            <div class="flex items-center gap-3 bg-white dark:bg-white/5 p-2.5 rounded-lg border border-slate-200 dark:border-white/10 shadow-sm transition-colors cursor-default">
                                                <svg class="w-5 h-5 text-sky-500" viewBox="0 0 24 24" fill="currentColor"><path d="M10 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2h-8l-2-2z"/></svg>
                                                <strong class="tracking-wide">pusat-proyek/</strong>
                                            </div>
                                            
                                            <div class="pl-6 space-y-4 border-l-2 border-slate-200 dark:border-slate-800 ml-3.5 pt-2 relative min-h-[150px] transition-colors">
                                                
                                                <div id="fs-node-modules" class="hidden items-center gap-3 opacity-0 transform translate-x-4 transition-all duration-300 ease-out bg-white dark:bg-white/5 py-1.5 px-3 rounded-md border border-slate-200 dark:border-transparent w-max shadow-sm dark:shadow-none cursor-default">
                                                    <svg class="w-4 h-4 text-emerald-500" viewBox="0 0 24 24" fill="currentColor"><path d="M10 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2h-8l-2-2z"/></svg>
                                                    <span class="text-slate-600 dark:text-slate-400 font-bold">node_modules/</span>
                                                </div>
                                                
                                                <div id="fs-package" class="hidden items-center gap-3 opacity-0 transform translate-x-4 transition-all duration-300 ease-out relative bg-amber-50 dark:bg-amber-900/20 py-1.5 px-3 rounded-md border border-amber-200 dark:border-amber-500/20 w-max shadow-sm cursor-default">
                                                    <svg class="w-4 h-4 text-amber-500" viewBox="0 0 24 24" fill="currentColor"><path d="M13 9h5.5L13 3.5V9M6 2h8l6 6v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4c0-1.11.89-2 2-2m5 2H6v16h12v-9h-7V4z"/></svg>
                                                    <span class="font-bold text-amber-700 dark:text-amber-400">package.json</span>
                                                </div>

                                                <div id="fs-package-lock" class="hidden items-center gap-3 opacity-0 transform translate-x-4 transition-all duration-300 ease-out bg-white dark:bg-white/5 py-1.5 px-3 rounded-md border border-slate-200 dark:border-transparent w-max shadow-sm dark:shadow-none cursor-default">
                                                    <svg class="w-4 h-4 text-amber-500" viewBox="0 0 24 24" fill="currentColor"><path d="M13 9h5.5L13 3.5V9M6 2h8l6 6v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4c0-1.11.89-2 2-2m5 2H6v16h12v-9h-7V4z"/></svg>
                                                    <span class="text-slate-600 dark:text-slate-400 font-bold">package-lock.json</span>
                                                </div>

                                                <div id="fs-tailwind" class="hidden items-center gap-3 opacity-0 transform translate-x-4 transition-all duration-300 ease-out relative bg-cyan-50 dark:bg-cyan-900/20 py-1.5 px-3 rounded-md border border-cyan-200 dark:border-cyan-500/20 w-max shadow-sm cursor-default">
                                                    <svg class="w-4 h-4 text-cyan-500" viewBox="0 0 24 24" fill="currentColor"><path d="M13 9h5.5L13 3.5V9M6 2h8l6 6v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4c0-1.11.89-2 2-2m5 2H6v16h12v-9h-7V4z"/></svg>
                                                    <span class="font-bold text-cyan-700 dark:text-cyan-400">tailwind.config.js</span>
                                                </div>
                                                
                                                <div id="fs-idle-state" class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-slate-400 text-center opacity-70 dark:opacity-50 transition-opacity w-full">
                                                    <div class="w-12 h-12 rounded-full border-2 border-dashed border-slate-300 dark:border-slate-600 mx-auto mb-2 flex items-center justify-center"><svg class="w-5 h-5 text-slate-300 dark:text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg></div>
                                                    <p class="text-[10px] uppercase tracking-widest mb-1 font-bold">Direktori Kosong</p>
                                                    <p class="text-[10px]">Menanti instruksi inisialisasi terminal.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                {{-- KESIMPULAN SIMULASI 2 --}}
                                <div class="mt-8 bg-gradient-to-r from-cyan-50 to-transparent dark:from-cyan-900/20 dark:to-transparent border-l-4 border-cyan-500 p-5 rounded-r-xl relative z-10 transition-colors">
                                    <h5 class="text-sm font-bold text-cyan-700 dark:text-cyan-400 mb-2 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> 
                                        Kesimpulan Analisis Tahapan CLI
                                    </h5>
                                    <p class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed text-justify">
                                        Tiga urutan eksekusi perintah terminal tersebut bertindak layaknya DNA dari anatomi Tailwind modern. Urutan ini menciptakan <em>dependency tree</em> (pohon ketergantungan) yang sehat dan mengukuhkan fondasi <code>tailwind.config.js</code>, yang nantinya akan menjadi otak operasional tempat Anda menanamkan modifikasi desain khusus milik Anda secara aman.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 26: JIT & CONTENT --}}
                    <section id="section-26" class="lesson-section scroll-mt-32" data-lesson-id="26">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-emerald-500 pl-6">
                                <span class="text-emerald-600 dark:text-emerald-400 font-mono text-xs uppercase tracking-widest transition-colors">Lesson 1.6.3</span>
                                <h2 class="text-4xl lg:text-5xl font-black text-heading leading-[1.1] transition-colors">
                                    Kalibrasi Lensa: <br> 
                                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-500 to-teal-600 dark:from-emerald-400 dark:to-teal-500">Kompilator JIT</span>
                                </h2>
                            </div>
                            
                            <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-lg leading-relaxed space-y-4 text-justify transition-colors">
                                <p>Tahapan paling krusial pasca peresmian berkas tata kendali <code>tailwind.config.js</code> adalah mendeklarasikan letak posisi fail HTML pada properti <strong class="text-emerald-600 dark:text-emerald-400">content</strong>. Kekeliruan pada matriks ini menyumbangkan persentase tertinggi atas kegagalan perakitan gaya visual situs yang sering dialami oleh pemula.</p>
                                <p>Mesin komputasi Tailwind didorong oleh arsitektur pelacak <strong>Just-In-Time (JIT)</strong>. Komponen ini bekerja persis seperti radar penjejak militer. Alih-alih memproduksi dan membebani file CSS dengan ratusan ribu utilitas yang tidak pernah Anda pakai, JIT murni <em>hanya</em> meracik kode CSS untuk kelas yang terdeteksi aktif pada sasaran dokumen.</p>
                                <p>Arah penargetan radar diatur oleh nilai <em>Glob Pattern</em>. Pendeklarasian <code class="bg-slate-200 dark:bg-white/10 px-1.5 py-0.5 rounded font-mono text-sm text-slate-800 dark:text-white">"./src/&#42;&#42;/&#42;.html"</code> memerintahkan radar penjejak JIT untuk menyusuri direktori <code class="text-sm">src</code>, menembus seluruh lorong foldernya berkat parameter dua bintang (<code>**</code>), dan memindai sintaks di dalam fail HTML. Mengosongkan pendaftaran lintasan target ini akan melumpuhkan radar, menyebabkan JIT menelurkan fail CSS berukuran absolut 0 bytes (hampa) dan mematikan visual web secara instan.</p>
                            </div>

                            {{-- SIMULATOR 3: JIT SCANNER --}}
                            <div class="sim-bg-adaptive border border-adaptive rounded-xl p-8 relative shadow-xl dark:shadow-2xl transition-colors mt-8 overflow-hidden">
                                <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-500/5 dark:bg-emerald-500/10 rounded-full blur-[80px] pointer-events-none transition-colors"></div>
                                
                                <h4 class="text-xs font-bold text-muted uppercase mb-4 text-center transition-colors">Simulator Logis: Sensitivitas Pelacakan JIT Engine</h4>
                                
                                {{-- KOTAK INSTRUKSI SIMULATOR 3 --}}
                                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-500/30 rounded-lg p-4 mb-8 text-sm text-blue-800 dark:text-blue-300 relative z-10">
                                    <p class="font-bold flex items-center gap-2 mb-2">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        💡 Panduan Simulasi 3
                                    </p>
                                    <ol class="list-decimal pl-5 space-y-1">
                                        <li>Cobalah tekan <strong>"Kosongkan Sensor"</strong>. Radar JIT akan kehilangan arah dan mencetak file output yang kosong (0 bytes).</li>
                                        <li>Tekan <strong>"Integrasikan Rute"</strong> untuk menanamkan jalur letak direktori pada array <code>content</code>. Radar Tailwind akan kembali hidup, memindai sasaran HTML, lalu sukses melahirkan file CSS yang matang.</li>
                                    </ol>
                                </div>

                                <div class="grid md:grid-cols-2 gap-10 items-center relative z-10 border border-adaptive rounded-2xl overflow-hidden shadow-sm transition-colors">
                                    <div class="space-y-6 p-6 bg-slate-50 dark:bg-[#0a0e17] transition-colors h-full flex flex-col justify-center">
                                        <div class="bg-white dark:bg-[#151a23] p-6 rounded-xl border border-slate-200 dark:border-white/5 font-mono text-sm text-slate-600 dark:text-gray-400 shadow-inner transition-colors leading-loose relative overflow-hidden">
                                            <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-5 pointer-events-none"></div>
                                            module.exports = {<br>
                                            &nbsp;&nbsp;content: [<br>
                                            &nbsp;&nbsp;&nbsp;&nbsp;<span id="vis-jit-path" class="text-red-500 font-bold bg-red-50 dark:bg-red-900/30 px-2 py-1 rounded transition-colors duration-300 border border-red-200 dark:border-red-500/30 shadow-sm inline-block my-1">"" // Kekosongan Jalur Target</span><br>
                                            &nbsp;&nbsp;],<br>
                                            &nbsp;&nbsp;}
                                        </div>
                                        <div class="flex gap-4">
                                            <button onclick="runJitScan(true)" class="flex-1 py-3.5 px-4 bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-400 font-bold text-[10px] uppercase tracking-widest rounded-xl border border-emerald-300 dark:border-emerald-500/40 hover:bg-emerald-200 dark:hover:bg-emerald-500/30 hover:shadow-lg transition-all focus:outline-none text-center shadow-sm hover:-translate-y-1 scale-[1.02]">Integrasikan Rute</button>
                                            <button onclick="runJitScan(false)" class="flex-1 py-3.5 px-4 bg-slate-200 dark:bg-white/5 text-slate-600 dark:text-white/60 font-bold text-[10px] uppercase tracking-widest rounded-xl border border-slate-300 dark:border-white/10 hover:bg-slate-300 dark:hover:bg-white/10 transition-all focus:outline-none text-center shadow-sm hover:-translate-y-1">Kosongkan Sensor</button>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center justify-center p-8 bg-slate-100 dark:bg-[#020617] h-full relative transition-colors overflow-hidden">
                                        <div class="absolute inset-0 visual-grid opacity-30"></div>
                                        
                                        <div id="radar-ui" class="w-48 h-48 border-[3px] border-red-300 dark:border-red-500/50 rounded-full flex items-center justify-center transition-all duration-500 relative z-10 shadow-[0_0_30px_rgba(225,29,72,0.15)] bg-red-50 dark:bg-red-500/5 overflow-hidden">
                                            <div id="radar-scanline" class="absolute inset-0 hidden"></div>
                                            <p id="radar-status" class="text-[10px] font-bold text-red-500 dark:text-red-400 text-center uppercase tracking-widest transition-colors z-10 px-2 bg-white/80 dark:bg-black/50 rounded backdrop-blur-sm py-1 shadow-sm border border-red-200 dark:border-transparent">Sensor Mati</p>
                                        </div>
                                        
                                        <div class="w-full flex justify-between items-end px-8 absolute bottom-6 z-10 transition-opacity duration-300 opacity-0" id="output-css">
                                            <div class="flex flex-col">
                                                <span class="text-[10px] text-slate-500 dark:text-white/50 font-mono uppercase tracking-widest font-bold mb-1">Hasil Kompilasi CSS</span>
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                                    <span class="text-sm font-bold text-slate-700 dark:text-white transition-colors font-mono">output.css</span>
                                                </div>
                                            </div>
                                            <span id="css-size" class="text-[11px] font-bold bg-slate-800 text-white px-3 py-1.5 rounded-lg shadow-inner transition-colors border border-slate-600">0 Bytes</span>
                                        </div>
                                    </div>
                                </div>
                                
                                {{-- KESIMPULAN SIMULASI 3 --}}
                                <div class="mt-8 bg-gradient-to-r from-emerald-50 to-transparent dark:from-emerald-900/20 dark:to-transparent border-l-4 border-emerald-500 p-5 rounded-r-xl relative z-10 transition-colors">
                                    <h5 class="text-sm font-bold text-emerald-700 dark:text-emerald-400 mb-2 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> 
                                        Kesimpulan Analisis Kompilator
                                    </h5>
                                    <p class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed text-justify">
                                        Perlakuan Tailwind sangat bertolak belakang dengan <em>framework CSS</em> kuno seperti Bootstrap, di mana Anda harus mengunduh ratusan kilobyte fail raksasa bahkan jika Anda hanya membutuhkan gaya untuk sebuah tombol sederhana. Dengan mengamankan deklarasi array <code>content</code> yang valid, JIT membuang semua <em>dead-code</em> yang tidak terpakai dan mempertahankan rasio beban ringan mutlak pada antarmuka web.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 27: THEME CONFIGURATION --}}
                    <section id="section-27" class="lesson-section scroll-mt-32" data-lesson-id="27">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-violet-500 pl-6">
                                <span class="text-violet-600 dark:text-violet-400 font-mono text-xs uppercase tracking-widest transition-colors">Lesson 1.6.4</span>
                                <h2 class="text-4xl lg:text-5xl font-black text-heading leading-[1.1] transition-colors">
                                    Prinsip Tema: <br> 
                                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-violet-500 to-fuchsia-600 dark:from-violet-400 dark:to-fuchsia-500">Ekstensi Visual</span>
                                </h2>
                            </div>
                            
                            <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-lg leading-relaxed space-y-4 text-justify transition-colors">
                                <p>Di dalam kerangka kendali <code>tailwind.config.js</code>, pusat pengelolaan gaya desain bernaung secara eksklusif pada susunan objek <strong class="text-violet-600 dark:text-violet-400">theme</strong>. Di arena manipulasi ini identitas komersial merek produk web Anda ditorehkan—mulai dari mengubah batasan <em>container</em> responsivitas ukuran layar, menempelkan set tipografi pihak eksternal, hingga membubuhkan palet ragam pigmen warna ciri khas korporasi.</p>
                                <p>Namun, sangat penting menyadari bahwa Tailwind menyediakan dua cabang metodologi berseberangan saat Anda menginjeksikan properti desain kustom: Melalui pendelegasian perpanjangan sistem (<strong class="text-violet-600 dark:text-violet-400">Extend</strong>) atau metode penimpaan bongkahan absolut (<strong class="text-violet-600 dark:text-violet-400">Override</strong>).</p>
                                <p>Memerintahkan pendaftaran corak eksklusif ke dalam perlindungan pembatas blok objek <code>extend: {}</code> meyakinkan ekosistem bahwa warna tambahan Anda bersikap sebagai perpanjangan komplementer. Ini berarti kelas bawaan pembuat sistem (seperti <code>bg-blue-500</code> atau <code>bg-red-500</code>) dijamin 100% aman dan beroperasi sejajar. Sebaliknya, menyisipkan properti kustom secara frontal dan diletakkan di luar pagar pengaman <code>extend</code> akan memaksa mesin bereaksi secara "Override", di mana JIT membunuh amnesia dan membakar membuang seluruh ingatan pustaka warna <em>default</em> yang ia miliki demi menampung warna tunggal Anda.</p>
                            </div>
                            
                            {{-- SIMULATOR 4: THEME EXTEND VS OVERRIDE --}}
                            <div class="sim-bg-adaptive border border-adaptive rounded-xl p-8 shadow-xl dark:shadow-2xl transition-colors mt-8 overflow-hidden relative">
                                <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-violet-500/5 dark:bg-violet-500/10 rounded-full blur-[80px] pointer-events-none transition-colors"></div>
                                
                                <h4 class="text-xs font-bold text-muted uppercase mb-4 text-center transition-colors">Simulator Logis: Konsekuensi Arsitektur Extend Vs Override</h4>
                                
                                {{-- KOTAK INSTRUKSI SIMULATOR 4 --}}
                                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-500/30 rounded-lg p-4 mb-8 text-sm text-blue-800 dark:text-blue-300 relative z-10">
                                    <p class="font-bold flex items-center gap-2 mb-2">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        💡 Panduan Simulasi 4
                                    </p>
                                    <ol class="list-decimal pl-5 space-y-1">
                                        <li>Amati tiga kelas warna pada panggung simulator. Warna bawaan Tailwind (Blue & Emerald) dapat berdampingan harmonis dengan warna buatan kustom (Violet BRAND) saat sistem berada dalam mode <span class="font-bold">EXTEND</span>.</li>
                                        <li>Tekan perlahan tombol aksi <strong>"Tumbuk Melalui Override"</strong> untuk menyaksikan bagaimana fatalnya dampak pemusnahan ketika Anda menaruh kelas properti warna di luar batas proteksi.</li>
                                    </ol>
                                </div>

                                <div class="flex flex-col sm:flex-row gap-6 mb-8 relative z-10">
                                    <button onclick="setThemeArchitecture('extend')" id="btn-theme-extend" class="flex-1 py-4 px-6 bg-emerald-600 text-white font-bold text-xs rounded-xl shadow-lg shadow-emerald-500/30 border border-emerald-500 focus:outline-none transition-all uppercase tracking-widest text-center scale-[1.02]">
                                        <div class="flex flex-col items-center gap-2">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                            Mode Aman: EXTEND
                                        </div>
                                    </button>
                                    <button onclick="setThemeArchitecture('override')" id="btn-theme-override" class="flex-1 py-4 px-6 bg-slate-200 dark:bg-[#111827] text-slate-600 dark:text-slate-400 font-bold text-xs rounded-xl border border-slate-300 dark:border-white/10 hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-900/20 dark:hover:text-red-400 focus:outline-none transition-all uppercase tracking-widest text-center shadow-sm">
                                        <div class="flex flex-col items-center gap-2">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            Penimpaan: OVERRIDE
                                        </div>
                                    </button>
                                </div>
                                
                                <div class="bg-slate-100 dark:bg-[#050912] rounded-2xl border border-adaptive p-8 min-h-[250px] flex items-center justify-center relative overflow-hidden transition-colors shadow-inner">
                                    <div class="absolute inset-0 visual-grid opacity-40 dark:opacity-20"></div>
                                    
                                    <div id="theme-base-colors" class="flex items-center gap-8 transition-all duration-500 z-10">
                                        <div class="base-color flex flex-col items-center gap-3 transition-all duration-500">
                                            <div class="color-box w-20 h-20 rounded-2xl bg-blue-500 shadow-lg flex items-center justify-center border-2 border-transparent transition-all duration-500">
                                                <svg class="w-8 h-8 text-white opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            </div>
                                            <span class="color-label text-xs font-bold font-mono text-slate-600 dark:text-white/60 bg-white dark:bg-black/50 px-3 py-1 rounded-lg shadow-sm border border-slate-200 dark:border-white/10 transition-colors">bg-blue-500</span>
                                        </div>
                                        
                                        <div class="base-color flex flex-col items-center gap-3 transition-all duration-500">
                                            <div class="color-box w-20 h-20 rounded-2xl bg-emerald-500 shadow-lg flex items-center justify-center border-2 border-transparent transition-all duration-500">
                                                <svg class="w-8 h-8 text-white opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            </div>
                                            <span class="color-label text-xs font-bold font-mono text-slate-600 dark:text-white/60 bg-white dark:bg-black/50 px-3 py-1 rounded-lg shadow-sm border border-slate-200 dark:border-white/10 transition-colors">bg-emerald-500</span>
                                        </div>
                                    </div>
                                    
                                    <div id="theme-plus-icon" class="mx-10 text-slate-400 dark:text-white/30 font-black text-3xl z-10 transition-all duration-500">+</div>

                                    <div class="flex flex-col items-center gap-3 z-10">
                                        <div class="w-24 h-24 rounded-2xl bg-violet-500 dark:bg-[#8b5cf6] shadow-[0_10px_25px_rgba(139,92,246,0.3)] dark:shadow-[0_10px_25px_rgba(139,92,246,0.5)] border-[3px] border-violet-300 dark:border-[#a78bfa] flex items-center justify-center text-[12px] text-white font-black tracking-widest relative transform hover:scale-105 transition-transform duration-300">
                                            <div class="absolute -top-3 -right-3 w-8 h-8 bg-emerald-400 rounded-full border-4 border-slate-100 dark:border-[#050912] flex items-center justify-center shadow-md transition-colors"><svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></div>
                                            BRAND
                                        </div>
                                        <span class="text-xs font-bold font-mono text-violet-700 dark:text-violet-400 bg-violet-100 dark:bg-violet-900/30 px-3 py-1 rounded-lg shadow-sm border border-violet-300 dark:border-violet-500/30 transition-colors">bg-brand</span>
                                    </div>
                                    
                                    <div id="theme-warning" class="absolute inset-0 bg-red-50/95 dark:bg-red-950/90 text-red-800 dark:text-red-200 flex flex-col items-center justify-center p-8 text-center opacity-0 transition-all duration-500 pointer-events-none z-20 backdrop-blur-sm border-2 border-red-200 dark:border-red-500/30 rounded-2xl">
                                        <div class="w-16 h-16 rounded-full bg-red-100 dark:bg-red-500/20 flex items-center justify-center mb-4 border border-red-300 dark:border-red-500/50 shadow-lg">
                                            <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                        </div>
                                        <span class="font-black text-xl uppercase tracking-widest mb-3">Ekosistem Kelas Bawaan Terhapus!</span>
                                        <span class="text-base opacity-90 max-w-xl leading-relaxed font-medium">Sintaks kelas dasar (seperti <code class="bg-red-200 dark:bg-red-900/50 px-1 rounded">bg-blue-500</code>) tidak dapat lagi dirender. Penggunaan metode <strong>Override</strong> di luar blok struktur "extend" menjatuhkan sanksi fatal di mana pangkalan warna asal digugurkan murni dari rakitan akhir JIT.</span>
                                    </div>
                                </div>

                                {{-- KESIMPULAN SIMULASI 4 --}}
                                <div class="mt-8 bg-gradient-to-r from-violet-50 to-transparent dark:from-violet-900/20 dark:to-transparent border-l-4 border-violet-500 p-5 rounded-r-xl relative z-10 transition-colors">
                                    <h5 class="text-sm font-bold text-violet-700 dark:text-violet-400 mb-2 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> 
                                        Kesimpulan Analisis Arsitektur Tema
                                    </h5>
                                    <p class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed text-justify">
                                        Sebagai konvensi desain yang terstandarisasi, pastikan selalu menanamkan modifikasi desain Anda ke dalam pangkuan rahim parameter properti `extend: {}`. Kecuali Anda bertindak atas nama sengaja dan memiliki tujuan purifikasi yang spesifik (seperti membangun sebuah tema murni yang dirancang seratus persen dari nol untuk menghilangkan ketergantungan warisan).
                                    </p>
                                </div>
                            </div>

                        </div>
                    </section>

                    {{-- FINAL ACTIVITY CHECKPOINT (DIAGNOSIS QUIZ) --}}
                    <section id="section-28" class="lesson-section scroll-mt-32 pt-10 border-t border-adaptive transition-colors" data-lesson-id="28" data-type="activity">
                        
                        {{-- HEADLINE --}}
                        <div class="relative mb-10 group pl-8">
                            <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-gradient-to-b from-cyan-500 to-transparent rounded-r-md opacity-80 group-hover:opacity-100 transition-opacity"></div>
                            <div class="space-y-4">
                                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-100 dark:bg-cyan-950/50 border border-cyan-300 dark:border-cyan-500/20 shadow-inner w-max transition-colors">
                                    <svg class="w-3.5 h-3.5 text-cyan-600 dark:text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    <span class="text-cyan-700 dark:text-cyan-400 font-mono text-[10px] font-black tracking-widest uppercase">Sesi Kuis Investigasi Akhir</span>
                                </div>
                                <h2 class="text-3xl lg:text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-cyan-600 to-blue-600 dark:from-white dark:to-white/50 leading-[1.2] tracking-tight">
                                    Evaluasi Diagnosis Pemasangan
                                </h2>
                            </div>
                        </div>

                        <div class="relative rounded-[2.5rem] sim-bg-adaptive border border-adaptive p-6 lg:p-10 overflow-hidden shadow-xl dark:shadow-2xl transition-all duration-500 group hover:border-cyan-500/30">
                            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-cyan-400 via-blue-500 to-indigo-500 opacity-50"></div>
                            
                            {{-- OVERLAY MISSION COMPLETE --}}
                            <div id="activityOverlay" class="hidden absolute inset-0 bg-white/95 dark:bg-[#0b0f19]/95 z-30 flex-col items-center justify-center backdrop-blur-md transition-colors">
                                <div class="w-24 h-24 bg-emerald-50 dark:bg-emerald-500/10 rounded-full flex items-center justify-center mb-6 border border-emerald-200 dark:border-emerald-500/50 shadow-[0_0_40px_rgba(16,185,129,0.3)] animate-bounce">
                                    <svg class="w-12 h-12 text-emerald-500 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                </div>
                                <h3 class="text-3xl font-black text-heading mb-3 uppercase tracking-widest transition-colors">Validasi Berhasil</h3>
                                <p class="text-muted text-base mb-8 max-w-md mx-auto text-center leading-relaxed transition-colors font-medium">Diagnosis fungsionalitas sistem telah tercatat sah dan masuk ke pangkalan rekaman basis peladen utama.</p>
                            </div>

                            <div class="flex flex-col xl:flex-row gap-8 items-stretch relative z-10">
                                {{-- Left: Briefing --}}
                                <div class="w-full xl:w-1/3 flex flex-col space-y-6">
                                    <div class="p-4 bg-gradient-to-br from-cyan-600 to-blue-800 rounded-2xl text-white shadow-lg shadow-cyan-500/20 shrink-0 w-max">
                                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                                    </div>
                                    <div>
                                        <p class="text-muted text-sm leading-relaxed text-justify transition-colors font-medium">
                                            Selaraskan seluruh instrumen rasional Anda untuk merekonstruksi dan merunut krisis kegagalan sistem yang dilaporkan pada skenario terminal di panel sebelah. Pastikan analisis Anda didasarkan teori yang baru dipahami untuk meretas blokade langkah instalasinya!
                                        </p>
                                    </div>

                                    <div class="bg-slate-50 dark:bg-[#0a0e17] border border-slate-200 dark:border-white/10 rounded-2xl p-6 shadow-inner dark:shadow-none flex-1 flex flex-col transition-colors">
                                        <h4 class="text-heading text-[11px] font-black mb-5 uppercase border-b border-adaptive pb-3 tracking-widest transition-colors flex items-center gap-2">
                                            <svg class="w-4 h-4 text-cyan-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                            Log Tahapan Diagnosis
                                        </h4>
                                        <div class="space-y-4 font-bold text-xs text-slate-500 dark:text-white/50 flex-1 flex flex-col justify-center transition-colors">
                                            <div id="tracker-1" class="flex items-center gap-4"><div class="w-7 h-7 rounded-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-white/10 shadow-sm flex items-center justify-center text-[10px] text-slate-600 dark:text-white transition-colors">1</div> <span>Kelayakan Sistem Node</span></div>
                                            <div id="tracker-2" class="flex items-center gap-4"><div class="w-7 h-7 rounded-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-white/10 shadow-sm flex items-center justify-center text-[10px] text-slate-600 dark:text-white transition-colors">2</div> <span>Logika Pemrograman CLI</span></div>
                                            <div id="tracker-3" class="flex items-center gap-4"><div class="w-7 h-7 rounded-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-white/10 shadow-sm flex items-center justify-center text-[10px] text-slate-600 dark:text-white transition-colors">3</div> <span>Rute Pemindai JIT</span></div>
                                            <div id="tracker-4" class="flex items-center gap-4"><div class="w-7 h-7 rounded-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-white/10 shadow-sm flex items-center justify-center text-[10px] text-slate-600 dark:text-white transition-colors">4</div> <span>Prosedur Aman Ekstensi</span></div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Right: Quiz --}}
                                <div class="w-full xl:w-2/3 flex flex-col">
                                    <div class="bg-slate-50 dark:bg-[#151515] border border-slate-200 dark:border-white/10 rounded-3xl shadow-lg flex-1 flex flex-col relative transition-colors min-h-[450px] overflow-hidden">
                                        <div class="bg-slate-200 dark:bg-[#0f141e] border-b border-adaptive px-6 py-4 flex justify-between items-center transition-colors">
                                            <div class="flex gap-2"><div class="w-3 h-3 rounded-full bg-red-500 shadow-inner"></div><div class="w-3 h-3 rounded-full bg-amber-400 shadow-inner"></div><div class="w-3 h-3 rounded-full bg-emerald-500 shadow-inner"></div></div>
                                            <div id="quiz-header-status" class="text-[9px] font-black text-cyan-700 dark:text-cyan-400 uppercase bg-white dark:bg-black/30 px-3 py-1 rounded-full shadow-sm tracking-widest border border-slate-200 dark:border-white/5 transition-colors flex items-center gap-2">
                                                <div class="w-1.5 h-1.5 rounded-full bg-cyan-500 animate-pulse"></div> REKAMAN SKENARIO #1 / 4
                                            </div>
                                        </div>

                                        <div class="p-6 sm:p-8 flex-1 flex flex-col relative bg-white dark:bg-[#1e1e1e] overflow-y-auto custom-scrollbar shadow-inner transition-colors">
                                            
                                            {{-- STEP 1 --}}
                                            <div id="quiz-step-1" class="flex-1 flex flex-col animate-fade-in-up">
                                                <h3 class="text-base font-bold text-heading mb-6 leading-relaxed transition-colors">Pengembang merintis prosedur kompilasi dengan memantik instruksi terminal, tetapi panel segera memberangus dengan notifikasi tolakan: <code class="text-red-600 dark:text-red-400 text-sm bg-red-100 dark:bg-red-900/20 px-2 py-1 rounded shadow-sm border border-red-200 dark:border-red-500/20 inline-block mt-2 transition-colors">npm: command not found</code> Apa sumber pondasi komputasi yang kosong di dalam skenario ini?</h3>
                                                <div class="space-y-4 mt-auto">
                                                    <button onclick="checkAnswer(1, false, this)" class="w-full text-left p-5 rounded-2xl border border-slate-300 dark:border-white/10 hover:bg-slate-50 dark:hover:bg-white/5 text-sm font-medium text-slate-700 dark:text-slate-300 transition-all focus:outline-none bg-white dark:bg-[#0f141e] shadow-sm hover:shadow-md hover:-translate-y-0.5 group">
                                                        <div class="flex gap-4 items-start">
                                                            <div class="w-6 h-6 rounded-full border-2 border-slate-300 dark:border-slate-600 flex items-center justify-center shrink-0 text-xs font-bold text-slate-400 group-hover:border-cyan-400 group-hover:text-cyan-500 transition-colors mt-0.5 bg-white dark:bg-transparent">A</div>
                                                            <span>Ketidakhadiran rantai deklarasi pranala impor tag HTML perpustakaan di dalam penampang kerangka kode fail <code>index.html</code>.</span>
                                                        </div>
                                                    </button>
                                                    <button onclick="checkAnswer(1, true, this)" class="w-full text-left p-5 rounded-2xl border border-slate-300 dark:border-white/10 hover:bg-slate-50 dark:hover:bg-white/5 text-sm font-medium text-slate-700 dark:text-slate-300 transition-all focus:outline-none bg-white dark:bg-[#0f141e] shadow-sm hover:shadow-md hover:-translate-y-0.5 group">
                                                        <div class="flex gap-4 items-start">
                                                            <div class="w-6 h-6 rounded-full border-2 border-slate-300 dark:border-slate-600 flex items-center justify-center shrink-0 text-xs font-bold text-slate-400 group-hover:border-cyan-400 group-hover:text-cyan-500 transition-colors mt-0.5 bg-white dark:bg-transparent">B</div>
                                                            <span>Perangkat belum diberkahi instalasi sah perangkat lunak lingkungan <strong>Node.js</strong> sehingga operasional NPM mustahil terbaca sistem.</span>
                                                        </div>
                                                    </button>
                                                </div>
                                            </div>

                                            {{-- STEP 2 --}}
                                            <div id="quiz-step-2" class="hidden flex-1 flex flex-col animate-fade-in-up">
                                                <h3 class="text-base font-bold text-heading mb-6 leading-relaxed transition-colors">Integrasi lingkungan Node terverifikasi sukses. Berpegang pada kaidah, tentukan instruksi CLI terminal perdana mutlak apa yang kini ia butuhkan untuk merintis kelahiran catatan inventaris struktur <code class="text-cyan-600 dark:text-cyan-400 bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 px-1.5 py-0.5 rounded shadow-sm">package.json</code> secara instan?</h3>
                                                <div class="space-y-4 mt-auto">
                                                    <button onclick="checkAnswer(2, true, this)" class="w-full text-left p-5 rounded-2xl border border-slate-300 dark:border-white/10 hover:bg-slate-50 dark:hover:bg-white/5 text-sm font-medium text-slate-700 dark:text-slate-300 transition-all focus:outline-none bg-white dark:bg-[#0f141e] shadow-sm hover:shadow-md hover:-translate-y-0.5 group">
                                                        <div class="flex gap-4 items-start">
                                                            <div class="w-6 h-6 rounded-full border-2 border-slate-300 dark:border-slate-600 flex items-center justify-center shrink-0 text-xs font-bold text-slate-400 group-hover:border-cyan-400 group-hover:text-cyan-500 transition-colors mt-0.5 bg-white dark:bg-transparent">A</div>
                                                            <span>Mendaratkan instruksi penginisialisasian daftar proyek: <code class="text-cyan-700 dark:text-cyan-300 font-bold bg-cyan-50 dark:bg-cyan-900/30 px-2 py-1 rounded transition-colors border border-cyan-200 dark:border-cyan-500/30">npm init -y</code></span>
                                                        </div>
                                                    </button>
                                                    <button onclick="checkAnswer(2, false, this)" class="w-full text-left p-5 rounded-2xl border border-slate-300 dark:border-white/10 hover:bg-slate-50 dark:hover:bg-white/5 text-sm font-medium text-slate-700 dark:text-slate-300 transition-all focus:outline-none bg-white dark:bg-[#0f141e] shadow-sm hover:shadow-md hover:-translate-y-0.5 group">
                                                        <div class="flex gap-4 items-start">
                                                            <div class="w-6 h-6 rounded-full border-2 border-slate-300 dark:border-slate-600 flex items-center justify-center shrink-0 text-xs font-bold text-slate-400 group-hover:border-cyan-400 group-hover:text-cyan-500 transition-colors mt-0.5 bg-white dark:bg-transparent">B</div>
                                                            <span>Menyodorkan perintah pembuat pengatur sentral tema rupa tailwind: <code class="text-cyan-700 dark:text-cyan-300 font-bold bg-cyan-50 dark:bg-cyan-900/30 px-2 py-1 rounded transition-colors border border-cyan-200 dark:border-cyan-500/30">npx tailwindcss init</code></span>
                                                        </div>
                                                    </button>
                                                </div>
                                            </div>

                                            {{-- STEP 3 --}}
                                            <div id="quiz-step-3" class="hidden flex-1 flex flex-col animate-fade-in-up">
                                                <h3 class="text-base font-bold text-heading mb-6 leading-relaxed transition-colors">Kompilator mendarat mantap. Ia mengetik atribut warna <code class="text-blue-600 dark:text-blue-400 bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 px-1.5 py-0.5 rounded shadow-sm">bg-blue-500</code> di HTML, tetapi mesin gagal memproduksi penyesuaian visualisasi, melepas fail CSS kosong total berkapasitas 0 Bytes. Diagnosis apa yang paling tepat?</h3>
                                                <div class="space-y-4 mt-auto">
                                                    <button onclick="checkAnswer(3, false, this)" class="w-full text-left p-5 rounded-2xl border border-slate-300 dark:border-white/10 hover:bg-slate-50 dark:hover:bg-white/5 text-sm font-medium text-slate-700 dark:text-slate-300 transition-all focus:outline-none bg-white dark:bg-[#0f141e] shadow-sm hover:shadow-md hover:-translate-y-0.5 group">
                                                        <div class="flex gap-4 items-start">
                                                            <div class="w-6 h-6 rounded-full border-2 border-slate-300 dark:border-slate-600 flex items-center justify-center shrink-0 text-xs font-bold text-slate-400 group-hover:border-cyan-400 group-hover:text-cyan-500 transition-colors mt-0.5 bg-white dark:bg-transparent">A</div>
                                                            <span>Radar cerdas Just-In-Time membutuhkan unduhan tambahan aplikasi ekstensi pihak eksternal independen guna mendeteksi pigmen spektrum biru.</span>
                                                        </div>
                                                    </button>
                                                    <button onclick="checkAnswer(3, true, this)" class="w-full text-left p-5 rounded-2xl border border-slate-300 dark:border-white/10 hover:bg-slate-50 dark:hover:bg-white/5 text-sm font-medium text-slate-700 dark:text-slate-300 transition-all focus:outline-none bg-white dark:bg-[#0f141e] shadow-sm hover:shadow-md hover:-translate-y-0.5 group">
                                                        <div class="flex gap-4 items-start">
                                                            <div class="w-6 h-6 rounded-full border-2 border-slate-300 dark:border-slate-600 flex items-center justify-center shrink-0 text-xs font-bold text-slate-400 group-hover:border-cyan-400 group-hover:text-cyan-500 transition-colors mt-0.5 bg-white dark:bg-transparent">B</div>
                                                            <span>Lensa pemindai mesin pelacakan pada area parameter <strong>Content</strong> terekam dikosongkan. Sensor radar pemantau gagal menembakkan daya jelajah lokasinya karena menatap wilayah hampa.</span>
                                                        </div>
                                                    </button>
                                                </div>
                                            </div>

                                            {{-- STEP 4 --}}
                                            <div id="quiz-step-4" class="hidden flex-1 flex flex-col animate-fade-in-up">
                                                <h3 class="text-base font-bold text-heading mb-6 leading-relaxed transition-colors">Lintasan radar menyala terang kembali. Ia menginisialisasi warna baru ke konfigurasi <code class="text-violet-600 dark:text-violet-400 bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 px-1.5 py-0.5 rounded shadow-sm">tailwind.config.js</code>. Bencana terjadi, warna palet bawaan mesin (seperti merah, biru) meluap hilang terbakar hangus menjadi error. Apakah kekeliruan fatalnya?</h3>
                                                <div class="space-y-4 mt-auto">
                                                    <button onclick="checkAnswer(4, true, this)" class="w-full text-left p-5 rounded-2xl border border-slate-300 dark:border-white/10 hover:bg-slate-50 dark:hover:bg-white/5 text-sm font-medium text-slate-700 dark:text-slate-300 transition-all focus:outline-none bg-white dark:bg-[#0f141e] shadow-sm hover:shadow-md hover:-translate-y-0.5 group">
                                                        <div class="flex gap-4 items-start">
                                                            <div class="w-6 h-6 rounded-full border-2 border-slate-300 dark:border-slate-600 flex items-center justify-center shrink-0 text-xs font-bold text-slate-400 group-hover:border-cyan-400 group-hover:text-cyan-500 transition-colors mt-0.5 bg-white dark:bg-transparent">A</div>
                                                            <span class="leading-relaxed">Keputusan destruktif mendaftarkan kelas barunya ke dalam Override. Deklarasi mentah diletakkan membentur mutlak lapisan utama area objek <code class="text-red-500 dark:text-red-400 font-bold bg-red-50 dark:bg-red-900/20 px-1 rounded border border-red-200 dark:border-red-500/20 shadow-sm">theme</code> tanpa mematuhi area perisai aman wadah <code class="text-emerald-600 dark:text-emerald-400 font-bold bg-emerald-50 dark:bg-emerald-900/20 px-1 rounded border border-emerald-200 dark:border-emerald-500/20 shadow-sm">extend</code>.</span>
                                                        </div>
                                                    </button>
                                                    <button onclick="checkAnswer(4, false, this)" class="w-full text-left p-5 rounded-2xl border border-slate-300 dark:border-white/10 hover:bg-slate-50 dark:hover:bg-white/5 text-sm font-medium text-slate-700 dark:text-slate-300 transition-all focus:outline-none bg-white dark:bg-[#0f141e] shadow-sm hover:shadow-md hover:-translate-y-0.5 group">
                                                        <div class="flex gap-4 items-start">
                                                            <div class="w-6 h-6 rounded-full border-2 border-slate-300 dark:border-slate-600 flex items-center justify-center shrink-0 text-xs font-bold text-slate-400 group-hover:border-cyan-400 group-hover:text-cyan-500 transition-colors mt-0.5 bg-white dark:bg-transparent">B</div>
                                                            <span class="leading-relaxed">Kegagalan memverifikasi persetujuan ikatan registrasi lisensi dari modul korporasi palet tinta cetak komersial berbayar pada portal ekosistem NPM.</span>
                                                        </div>
                                                    </button>
                                                </div>
                                            </div>

                                            {{-- SUCCESS OVERLAY PER STEP --}}
                                            <div id="quiz-feedback" class="absolute bottom-6 left-1/2 transform -translate-x-1/2 bg-emerald-500 dark:bg-emerald-600 text-white px-6 py-3 rounded-full font-bold text-xs shadow-[0_10px_25px_rgba(16,185,129,0.3)] dark:shadow-[0_10px_25px_rgba(16,185,129,0.5)] translate-y-10 opacity-0 transition-all z-20 flex items-center gap-2 whitespace-nowrap border border-emerald-400 pointer-events-none">
                                                Kesimpulan Evaluasi Valid Dinyatakan Sempurna!
                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </section>

                </article>

                {{-- FOOTER NAVIGATION --}}
                <div class="mt-32 pt-8 border-t border-adaptive flex justify-between items-center transition-colors">
                    <a href="{{ route('courses.advantages') ?? '#' }}" class="group flex items-center gap-4 text-muted hover:text-heading transition-colors">
                        <div class="w-12 h-12 rounded-full border border-adaptive flex items-center justify-center group-hover:bg-slate-100 dark:group-hover:bg-white/5 transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        </div>
                        <div class="text-left hidden sm:block">
                            <div class="text-[10px] uppercase tracking-widest font-bold opacity-60">Sebelumnya</div>
                            <div class="font-black text-sm">Keunggulan Tailwind</div>
                        </div>
                    </a>
                    
                    <div id="nextChapterBtn" class="group flex items-center gap-4 text-right text-muted cursor-not-allowed opacity-50 pointer-events-none transition-all duration-500">
                        <div class="text-right hidden sm:block">
                            <div id="nextLabel" class="text-[10px] uppercase tracking-widest font-bold opacity-60">Terkunci</div>
                            <div class="font-black text-sm">Hands On Labs 1</div>
                        </div>
                        <div id="nextIcon" class="w-12 h-12 rounded-full border border-adaptive flex items-center justify-center bg-slate-100 dark:bg-white/5 transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>

<script>
    /* ==========================================
       1. DATA PENYIMPANAN
       ========================================== */
    window.LESSON_IDS = [24, 25, 26, 27, 28]; 
    let rawCompletedIds = {!! json_encode($completedLessonIds ?? []) !!};
    window.COMPLETED_IDS = rawCompletedIds.map(id => Number(id)); 
    let completedSet = new Set(window.COMPLETED_IDS);
    
    const ACTIVITY_ID = 6; 
    const ACTIVITY_LESSON_ID = 28; 
    let activityCompleted = {!! ($activityCompleted ?? false) ? 'true' : 'false' !!};

    /* ==========================================
       2. PEMICU OTOMATIS
       ========================================== */
    document.addEventListener('DOMContentLoaded', () => {
        initSidebarScroll(); 
        initCliSimulator();
        initVisualEffects();
        
        updateProgressUI(false); 
        
        if (activityCompleted) {
            lockActivityUI();
            unlockNextChapter();
        }

        initMasterObserver();
        
        window.LESSON_IDS.forEach(id => {
            if(completedSet.has(id)) markSidebarDone(id);
        });
    });

    /* ==========================================
       3. PROGRESS BAR
       ========================================== */
    function updateProgressUI(animate = true) {
        const total = window.LESSON_IDS.length;
        const done = window.LESSON_IDS.filter(id => completedSet.has(id)).length;
        let percent = Math.round((done / total) * 100);
        
        if (done === total && !activityCompleted) percent = 95;

        const bar = document.getElementById('topProgressBar');
        const label = document.getElementById('progressLabelTop');
        
        if(bar && label) {
            if(!animate) {
                bar.style.transition = 'none';
                bar.style.width = percent + '%';
                setTimeout(() => bar.style.transition = 'all 0.5s ease-out', 50);
            } else {
                bar.style.width = percent + '%'; 
            }
            label.innerText = percent + '%';
        }
        
        if (percent === 100 && activityCompleted) unlockNextChapter();
    }

    function markSidebarDone(lessonId) {
        const navItem = document.querySelector(`.nav-item[data-target="#section-${lessonId}"]`);
        if(navItem) {
            const dot = navItem.querySelector('.dot');
            if(dot) {
                const isActivity = navItem.querySelector('.sidebar-anchor')?.dataset.type === 'activity';
                if (isActivity) {
                    dot.outerHTML = `<svg class="w-4 h-4 text-amber-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>`;
                } else {
                    dot.outerHTML = `<svg class="w-4 h-4 text-cyan-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>`;
                }
            }
        }
    }

    async function saveLessonToDB(lessonId) { 
        lessonId = Number(lessonId);
        if(completedSet.has(lessonId)) return; 

        try {
            const formData = new URLSearchParams();
            formData.append('lesson_id', lessonId);
            formData.append('_token', '{{ csrf_token() }}');

            const response = await fetch('{{ route("lesson.complete") }}', { 
                method: 'POST', 
                headers: { 
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'Accept': 'application/json' 
                }, 
                body: formData.toString() 
            });
            
            if (response.ok) {
                completedSet.add(lessonId);
                updateProgressUI(true);
                markSidebarDone(lessonId);
            }
        } catch(e) {
            console.error('Koneksi terputus', e);
        }
    }

    /* ==========================================
       4. SCROLL OBSERVER
       ========================================== */
    function initMasterObserver() {
        const mainScroll = document.getElementById('mainScroll'); 
        const sections = document.querySelectorAll('.lesson-section');

        if (mainScroll && sections.length > 0) {
            const observerOptions = { root: mainScroll, rootMargin: "-10% 0px -40% 0px", threshold: 0.1 };
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const targetId = entry.target.id;
                        const lessonId = Number(entry.target.dataset.lessonId);
                        const isActivity = entry.target.dataset.type === 'activity';

                        if (typeof highlightAnchor === 'function') highlightAnchor(targetId); 

                        if (lessonId && !isActivity && !completedSet.has(lessonId)) {
                            saveLessonToDB(lessonId); 
                        }
                    }
                });
            }, observerOptions);

            sections.forEach(section => observer.observe(section));
        }
    }

    function highlightAnchor(id) {
        const isDark = document.documentElement.classList.contains('dark');
        const anchors = document.querySelectorAll('.sidebar-anchor');

        anchors.forEach(a => {
            a.classList.remove('bg-slate-100', 'dark:bg-white/5', 'border-cyan-500', 'border-amber-500');
            a.classList.add('border-transparent');
            
            const dot = a.querySelector('.anchor-dot');
            const isActivity = a.dataset.type === 'activity';

            if(dot) {
                dot.classList.remove('scale-125', 'shadow-sm', 'dark:shadow-[0_0_10px_#0ea5e9]', 'dark:shadow-[0_0_10px_#f59e0b]', 'bg-cyan-500', 'dark:bg-cyan-400');
                
                if (isActivity) {
                    dot.classList.remove('bg-amber-500', 'dark:bg-amber-400');
                    dot.classList.add('bg-slate-400', 'dark:bg-slate-600'); 
                } else {
                    dot.classList.remove('bg-cyan-500', 'dark:bg-cyan-400');
                    dot.classList.add('bg-slate-400', 'dark:bg-slate-600'); 
                }
            }

            const text = a.querySelector('.anchor-text');
            if(text) {
                text.classList.remove('text-slate-800', 'dark:text-white', 'font-bold');
                text.classList.add('text-slate-500');
            }
        });

        const activeAnchor = document.querySelector(`.sidebar-anchor[data-target="${id}"]`);
        if (activeAnchor) {
            const isActivity = activeAnchor.dataset.type === 'activity';
            
            activeAnchor.classList.add(isDark ? 'dark:bg-white/5' : 'bg-slate-100');
            activeAnchor.classList.add(isActivity ? 'border-amber-500' : 'border-cyan-500');
            activeAnchor.classList.remove('border-transparent');
            
            const dot = activeAnchor.querySelector('.anchor-dot');
            if(dot) {
                dot.classList.remove('bg-slate-400', 'dark:bg-slate-600');
                
                if (isActivity) {
                    dot.classList.add(isDark ? 'dark:bg-amber-400' : 'bg-amber-500', 'scale-125', isDark ? 'dark:shadow-[0_0_10px_#f59e0b]' : 'shadow-sm');
                } else {
                    dot.classList.add(isDark ? 'dark:bg-cyan-400' : 'bg-cyan-500', 'scale-125', isDark ? 'dark:shadow-[0_0_10px_#0ea5e9]' : 'shadow-sm');
                }
            }
            
            const text = activeAnchor.querySelector('.anchor-text');
            if(text) {
                text.classList.remove('text-slate-500');
                text.classList.add(isDark ? 'dark:text-white' : 'text-slate-800', 'font-bold');
            }
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
                if (k.getAttribute('data-target') === c) k.classList.add('active')
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

    function unlockNextChapter() {
        const btn = document.getElementById('nextChapterBtn');
        const label = document.getElementById('nextLabel');
        const icon = document.getElementById('nextIcon');

        if(btn && label && icon) {
            btn.classList.remove('cursor-not-allowed', 'opacity-50', 'pointer-events-none', 'text-muted');
            btn.classList.add('text-cyan-600', 'dark:text-cyan-400', 'cursor-pointer');
            
            label.innerText = "Selanjutnya";
            label.classList.remove('opacity-60', 'text-rose-400');
            label.classList.add('opacity-100', 'text-cyan-600', 'dark:text-cyan-400');
            
            icon.innerHTML = `<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>`;
            icon.classList.remove('bg-slate-100', 'dark:bg-white/5');
            icon.classList.add('bg-cyan-100', 'dark:bg-cyan-500/20', 'border-cyan-300', 'dark:border-cyan-500/40', 'text-cyan-600', 'dark:text-cyan-400', 'shadow-sm');
            
            btn.onclick = () => window.location.href = "{{ route('lab.start', ['id' => 1]) }}"; 
        }
    }

    /* ==========================================
       5. LOGIKA SIMULATOR (1,2,3,4)
       ========================================== */
       
    // SIM 1: NODE CHECKER
    function runNodeCheckVis(type) {
        const output = document.getElementById('vis-node-output');
        const idle = document.getElementById('vis-node-idle');
        if(idle) idle.style.display = 'none';

        const wrap = document.createElement('div');
        wrap.className = "flex items-center justify-between p-3 bg-slate-700/50 dark:bg-white/5 rounded-lg border border-slate-600 dark:border-white/10 terminal-line transition-colors animate-fade-in-up";
        
        if(type === 'node') {
            wrap.innerHTML = `<span class="text-[12px] font-mono text-emerald-400 font-bold">➜ <span class="text-slate-100 ml-2">node -v</span></span><span class="text-[10px] font-mono text-slate-800 dark:text-emerald-300 bg-emerald-400 dark:bg-emerald-500/20 px-2 py-0.5 rounded shadow-sm border border-emerald-500/30 font-bold transition-colors">v20.11.0</span>`;
        } else {
            wrap.innerHTML = `<span class="text-[12px] font-mono text-blue-400 font-bold">➜ <span class="text-slate-100 ml-2">npm -v</span></span><span class="text-[10px] font-mono text-slate-800 dark:text-blue-300 bg-blue-400 dark:bg-blue-500/20 px-2 py-0.5 rounded shadow-sm border border-blue-500/30 font-bold transition-colors">10.2.4</span>`;
        }
        
        if(output) {
            output.appendChild(wrap);
            output.scrollTop = output.scrollHeight;
        }
    }

    // SIM 2: CLI TERMINAL SEQUENCE
    let currentCliStep = 1;
    function initCliSimulator() {
        currentCliStep = 1;
        updateCliUi();
    }

    function resetCliSim() {
        currentCliStep = 1;
        
        const removeFile = (id) => {
            const el = document.getElementById(id);
            if(el) {
                el.classList.add('hidden', 'opacity-0', 'translate-x-4');
                el.classList.remove('flex');
            }
        };
        removeFile('fs-package');
        removeFile('fs-node-modules');
        removeFile('fs-package-lock');
        removeFile('fs-tailwind');
        
        const idle = document.getElementById('fs-idle-state');
        if(idle) idle.classList.remove('hidden');

        const input = document.getElementById('cli-input');
        if(input) {
            input.value = '';
            input.placeholder = "Ketik perintah terminal di sini...";
            input.disabled = false;
            
            const outputs = document.querySelectorAll('#cli-output > div:not(:first-child)');
            outputs.forEach(o => o.remove());
        }

        updateCliUi();
    }

    function executeCli(step, command) {
        if(step !== currentCliStep) return;
        
        const btn = document.getElementById(`btn-cli-${step}`);
        const input = document.getElementById('cli-input');
        
        if(input) input.value = command;
        
        if (btn) {
            btn.classList.add('bg-slate-800');
            btn.innerHTML = `<span class="animate-pulse flex items-center gap-2 text-xs"><svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg> Menjalankan perintah...</span>`;
        }
        
        const event = new KeyboardEvent('keypress', { key: 'Enter' });
        if(input) input.dispatchEvent(event);
    }

    function revealFile(id) {
        const el = document.getElementById(id);
        if(el) {
            el.classList.remove('hidden');
            el.classList.add('flex');
            setTimeout(() => {
                el.classList.remove('opacity-0', 'translate-x-4');
            }, 50);
        }
    }

    function updateCliUi() {
        const hintBtn = document.getElementById('cli-hint-btn');
        const hintLabel = document.getElementById('cli-hint-label');
        const pulseDot = `<div class="w-2 h-2 rounded-full bg-cyan-400 animate-pulse shadow-[0_0_8px_#22d3ee]"></div>`;
        const checkDot = `<svg class="w-3.5 h-3.5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>`;
        
        if (currentCliStep === 1) {
            if(hintLabel) hintLabel.innerHTML = `${pulseDot} Instruksi Tahap 1: Daftar Proyek`;
            if(hintBtn) { hintBtn.innerText = "npm init -y"; hintBtn.setAttribute('onclick', "executeCli(1, 'npm init -y')"); }
        } else if (currentCliStep === 2) {
            if(hintLabel) hintLabel.innerHTML = `${pulseDot} Instruksi Tahap 2: Modul Utama`;
            if(hintBtn) { hintBtn.innerText = "npm install -D tailwindcss"; hintBtn.setAttribute('onclick', "executeCli(2, 'npm install -D tailwindcss')"); }
        } else if (currentCliStep === 3) {
            if(hintLabel) hintLabel.innerHTML = `${pulseDot} Instruksi Tahap 3: Fail Kendali`;
            if(hintBtn) { hintBtn.innerText = "npx tailwindcss init"; hintBtn.setAttribute('onclick', "executeCli(3, 'npx tailwindcss init')"); }
        } else {
            if(hintLabel) hintLabel.innerHTML = `${checkDot} Laporan Selesai Penuh`;
            if(hintBtn) { hintBtn.innerText = "Seluruh Fondasi Struktur Terbangun"; hintBtn.removeAttribute('onclick'); hintBtn.classList.replace('hover:border-cyan-400','cursor-not-allowed'); hintBtn.classList.replace('text-cyan-400', 'text-emerald-400'); }
        }
    }

    const steps = [
        { cmd: 'npm init -y', res: '<span class="text-emerald-400 font-black">✔</span> Lembar manifest <b class="text-white">package.json</b> berhasil dibentuk. Proyek Node.js dirintis.', fileReveal: 'fs-package'},
        { cmd: 'npm install -D tailwindcss', res: '<span class="text-blue-400 font-black text-sm">⬇</span> Penarikan kompilator dependensi aman berjalan... <br> <span class="text-emerald-400 font-black">✔</span> Seluruh pasokan perangkat kompilator didaratkan masuk ke lumbung penyimpanan.', fileReveal: 'fs-node-modules'},
        { cmd: 'npx tailwindcss init', res: '<span class="text-emerald-400 font-black">✔</span> Sistem kontrol konfigurasi menyala operasional aktif: <strong class="text-white">tailwind.config.js</strong>', fileReveal: 'fs-tailwind'}
    ];

    document.getElementById('cli-input')?.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            const val = this.value.trim();
            const output = document.getElementById('cli-output');
            const historyItem = document.createElement('div');
            historyItem.className = "mb-4 text-[12px] font-mono transition-colors terminal-line leading-relaxed";
            historyItem.innerHTML = `<div class="flex items-center gap-3"><span class="text-emerald-400 font-black text-base">➜</span> <span class="text-white font-bold bg-slate-800 px-3 py-1.5 rounded shadow-sm border border-slate-600/50">${val}</span></div>`;
            
            let targetStep = steps[currentCliStep - 1];

            if(targetStep && val === targetStep.cmd) {
                historyItem.innerHTML += `<div class="text-slate-300 ml-6 mt-3 border-l-2 border-emerald-500/50 pl-4 transition-colors py-2 bg-slate-800/30 rounded-r-md shadow-inner text-[11px] leading-loose">${targetStep.res}</div>`;
                output.insertBefore(historyItem, this.parentElement);

                const idle = document.getElementById('fs-idle-state');
                if(idle) idle.classList.add('hidden');

                if(currentCliStep === 1) {
                    revealFile('fs-package');
                } else if (currentCliStep === 2) {
                    revealFile('fs-node-modules');
                    setTimeout(() => revealFile('fs-package-lock'), 250);
                } else if (currentCliStep === 3) {
                    revealFile('fs-tailwind');
                }

                currentCliStep++;
                this.value = '';
                
                if(currentCliStep > steps.length) {
                    this.placeholder = 'Kunci Terminal Diblokir Aman.';
                    this.disabled = true;
                }
                updateCliUi();

            } else if (val !== '') {
                historyItem.innerHTML += `<div class="text-red-300 dark:text-red-400 ml-6 mt-3 font-medium transition-colors border-l-2 border-red-500/80 pl-4 py-2 bg-red-900/20 rounded-r-md shadow-inner text-[11px] leading-loose">Peringatan: Instruksi perintah ditolak penelusuran oleh rute sistem kompilasi karena penulisan ejaan yang salah atau pengurutan terlewati.<br>Lakukan pemeriksaan ketelitian dengan mengetikkan perintah sakti ini:<br><code class="bg-red-900/50 border border-red-500/30 px-2 py-0.5 rounded mt-2 inline-block shadow-inner text-white tracking-wide">${targetStep.cmd}</code></div>`;
                output.insertBefore(historyItem, this.parentElement);
                
                this.parentElement.classList.add('shake');
                setTimeout(() => this.parentElement.classList.remove('shake'), 500);
                this.value = '';
                updateCliUi();
            }
            output.scrollTop = output.scrollHeight;
        }
    });

    function autoType(text) {
        const input = document.getElementById('cli-input');
        if(!input || input.disabled) return;
        input.value = text;
        const event = new KeyboardEvent('keypress', { key: 'Enter' });
        input.dispatchEvent(event);
    }


    // SIM 3: JIT RADAR ANIMATION PEMINDAI SATELIT
    function runJitScan(isCorrect) {
        const pathEl = document.getElementById('vis-jit-path');
        const radarUi = document.getElementById('radar-ui');
        const scanline = document.getElementById('radar-scanline');
        const status = document.getElementById('radar-status');
        const cssOut = document.getElementById('output-css');
        const cssSize = document.getElementById('css-size');

        // Reset display
        cssOut.classList.remove('opacity-100', 'translate-y-0');
        cssOut.classList.add('opacity-0', 'translate-y-6');
        scanline.classList.remove('hidden');

        if (isCorrect) {
            pathEl.innerHTML = '"./src/&#42;&#42;/&#42;.html"';
            pathEl.className = "text-emerald-600 dark:text-emerald-400 font-bold bg-emerald-100 dark:bg-emerald-900/20 px-2 py-1 rounded transition-colors duration-300 border border-emerald-300 dark:border-emerald-500/30 shadow-sm inline-block my-1";
            
            scanline.className = "absolute inset-0 scanline z-0";
            status.innerHTML = "MELACAK PENEMPATAN BERKAS... <span class='animate-ping ml-1.5 inline-block w-1.5 h-1.5 rounded-full bg-emerald-500'></span>";
            status.className = "absolute top-5 left-1/2 transform -translate-x-1/2 bg-white/90 dark:bg-emerald-900/80 backdrop-blur-md px-5 py-2 rounded-full text-[9px] font-black text-emerald-600 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-500 transition-colors uppercase tracking-widest z-20 shadow-[0_5px_15px_rgba(16,185,129,0.2)] dark:shadow-[0_5px_15px_rgba(16,185,129,0.3)] whitespace-nowrap";
            
            radarUi.className = "w-48 h-48 border-[3px] border-emerald-400 dark:border-emerald-500/50 rounded-full flex items-center justify-center transition-all duration-500 bg-emerald-50 dark:bg-emerald-500/10 shadow-[0_0_30px_rgba(16,185,129,0.2)] dark:shadow-[0_0_30px_rgba(16,185,129,0.4)] animate-pulse relative z-10 overflow-hidden";
            radarUi.innerHTML += '<div class="absolute inset-0 bg-emerald-400/20 rounded-full animate-ping z-0"></div><svg class="w-10 h-10 text-emerald-500 dark:text-emerald-400 animate-spin-slow relative z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>';
        } else {
            pathEl.innerHTML = '"" // KOSONG TANPA LOKASI';
            pathEl.className = "text-red-600 dark:text-red-400 font-bold bg-red-100 dark:bg-red-900/30 px-2 py-1 rounded transition-colors duration-300 border border-red-300 dark:border-red-500/30 shadow-sm inline-block my-1";
            
            scanline.className = "absolute inset-0 scanline-red z-0";
            status.innerHTML = "MEMINDAI HAMPAAN KOSONG... <span class='animate-ping ml-1.5 inline-block w-1.5 h-1.5 rounded-full bg-red-500'></span>";
            status.className = "absolute top-5 left-1/2 transform -translate-x-1/2 bg-white/90 dark:bg-red-900/80 backdrop-blur-md px-5 py-2 rounded-full text-[9px] font-black text-red-600 dark:text-red-300 border border-red-300 dark:border-red-500 transition-colors uppercase tracking-widest z-20 shadow-[0_5px_15px_rgba(225,29,72,0.2)] dark:shadow-[0_5px_15px_rgba(225,29,72,0.3)] whitespace-nowrap";
            
            radarUi.className = "w-48 h-48 border-[3px] border-red-400 dark:border-red-500/50 rounded-full flex items-center justify-center transition-all duration-500 bg-red-50 dark:bg-red-500/10 shadow-[0_0_30px_rgba(225,29,72,0.2)] dark:shadow-[0_0_30px_rgba(225,29,72,0.4)] animate-pulse relative z-10 overflow-hidden";
            radarUi.innerHTML += '<div class="absolute inset-0 bg-red-400/20 rounded-full animate-ping z-0"></div><svg class="w-10 h-10 text-red-500 dark:text-red-400 animate-spin-slow relative z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>';
        }

        setTimeout(() => {
            cssOut.classList.remove('opacity-0', 'translate-y-6');
            cssOut.classList.add('opacity-100', 'translate-y-0');
            
            if (isCorrect) {
                status.innerHTML = "<svg class='w-3 h-3 inline-block mr-1.5' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 13l4 4L19 7'/></svg> CSS BERHASIL DICETAK";
                status.className = "absolute top-5 left-1/2 transform -translate-x-1/2 bg-emerald-500 dark:bg-emerald-600 backdrop-blur-md px-5 py-2 rounded-full text-[9px] font-black text-white border border-emerald-400 transition-colors uppercase tracking-widest z-20 shadow-[0_10px_20px_rgba(16,185,129,0.3)] dark:shadow-[0_10px_20px_rgba(16,185,129,0.5)] whitespace-nowrap";
                radarUi.className = "w-48 h-48 border-4 border-emerald-400 rounded-full flex items-center justify-center transition-all bg-emerald-100 dark:bg-emerald-500/20 relative z-10 shadow-[0_0_40px_rgba(16,185,129,0.4)] dark:shadow-[0_0_40px_rgba(16,185,129,0.3)]";
                radarUi.innerHTML = '<svg class="w-16 h-16 text-emerald-500 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>';
                
                cssSize.innerText = "24.6 KB (Terisi)";
                cssSize.className = "text-[11px] font-bold bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400 px-3 py-1.5 rounded-lg shadow-inner border border-emerald-300 dark:border-emerald-500/40 transition-colors";
            } else {
                status.innerHTML = "<svg class='w-3 h-3 inline-block mr-1.5' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M6 18L18 6M6 6l12 12'/></svg> KOMPILASI GAGAL";
                status.className = "absolute top-5 left-1/2 transform -translate-x-1/2 bg-red-500 dark:bg-red-600 backdrop-blur-md px-5 py-2 rounded-full text-[9px] font-black text-white border border-red-400 transition-colors uppercase tracking-widest z-20 shadow-[0_10px_20px_rgba(225,29,72,0.3)] dark:shadow-[0_10px_20px_rgba(225,29,72,0.5)] whitespace-nowrap";
                radarUi.className = "w-48 h-48 border-4 border-red-400 rounded-full flex items-center justify-center transition-all bg-red-100 dark:bg-red-500/20 relative z-10 shadow-[0_0_40px_rgba(225,29,72,0.4)] dark:shadow-[0_0_40px_rgba(225,29,72,0.3)]";
                radarUi.innerHTML = '<svg class="w-16 h-16 text-red-500 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" /></svg>';

                cssSize.innerText = "0 Bytes (Kosong)";
                cssSize.className = "text-[11px] font-bold bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-400 px-3 py-1.5 rounded-lg shadow-inner border border-red-300 dark:border-red-500/40 transition-colors";
            }
        }, 2200); 
    }

    // SIM 4: THEME ARCHITECTURE SWITCHER
    function setThemeArchitecture(mode) {
        const warningUi = document.getElementById('theme-warning');
        const baseColors = document.querySelectorAll('.base-color');
        const iconPlus = document.getElementById('theme-plus-icon');
        
        document.getElementById('btn-theme-extend').className = "flex-1 py-4 px-6 rounded-xl font-bold text-xs uppercase tracking-widest transition-all shadow-md focus:outline-none flex flex-col items-center gap-2 " + (mode === 'extend' ? 'bg-emerald-600 text-white border border-emerald-500 scale-[1.02] shadow-lg shadow-emerald-500/20' : 'bg-slate-100 dark:bg-[#111827] text-slate-600 dark:text-slate-400 border border-slate-300 dark:border-white/10 hover:bg-emerald-50 hover:text-emerald-700 dark:hover:bg-emerald-900/20 dark:hover:text-emerald-400 hover:-translate-y-1');
        document.getElementById('btn-theme-override').className = "flex-1 py-4 px-6 rounded-xl font-bold text-xs uppercase tracking-widest transition-all shadow-md focus:outline-none flex flex-col items-center gap-2 " + (mode === 'override' ? 'bg-red-600 text-white border border-red-500 scale-[1.02] shadow-lg shadow-red-500/20' : 'bg-slate-100 dark:bg-[#111827] text-slate-600 dark:text-slate-400 border border-slate-300 dark:border-white/10 hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-900/20 dark:hover:text-red-400 hover:-translate-y-1');

        if(mode === 'extend') {
            warningUi.style.opacity = '0';
            warningUi.style.pointerEvents = 'none';
            iconPlus.style.opacity = '1';
            
            baseColors.forEach(el => {
                const box = el.querySelector('.color-box');
                const label = el.querySelector('.color-label');
                
                box.classList.remove('bg-slate-200', 'dark:bg-slate-800', 'border-red-400', 'border-dashed', 'opacity-30', 'grayscale');
                if (label.innerText.includes('blue')) box.classList.add('bg-blue-500');
                if (label.innerText.includes('emerald')) box.classList.add('bg-emerald-500');
                
                box.innerHTML = `<svg class="w-8 h-8 text-white opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="${label.innerText.includes('blue') ? 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z' : 'M5 13l4 4L19 7'}"/></svg>`;
                
                label.classList.remove('line-through', 'text-red-500', 'dark:text-red-400');
            });
            
        } else {
            warningUi.style.opacity = '1';
            warningUi.style.pointerEvents = 'auto';
            iconPlus.style.opacity = '0';

            baseColors.forEach(el => {
                const box = el.querySelector('.color-box');
                const label = el.querySelector('.color-label');
                
                box.classList.remove('bg-blue-500', 'bg-emerald-500');
                box.classList.add('bg-slate-200', 'dark:bg-slate-800', 'border-red-400', 'border-dashed', 'opacity-30', 'grayscale');
                
                box.innerHTML = `<svg class="w-8 h-8 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>`;
                
                label.classList.add('line-through', 'text-red-500', 'dark:text-red-400');
            });
        }
    }

    // ==========================================
    // 8. LOGIKA PENJAGA AKTIVITAS KUIS (FINAL MISSION EVALUATION)
    // ==========================================
    function checkAnswer(step, isCorrect, btnElement) {
        if(isCorrect) {
            btnElement.classList.remove('border-slate-300', 'dark:border-white/10', 'hover:bg-slate-50', 'dark:hover:bg-white/5', 'bg-white', 'dark:bg-[#0f141e]', 'text-slate-700', 'dark:text-slate-300');
            btnElement.classList.add('border-emerald-400', 'bg-emerald-50', 'dark:bg-emerald-900/20', 'text-emerald-700', 'dark:text-emerald-300', 'shadow-[0_5px_15px_rgba(16,185,129,0.15)]');
            
            // Re-style indicator circle
            const indicator = btnElement.querySelector('.rounded-full');
            indicator.classList.remove('border-slate-300', 'dark:border-slate-600', 'text-slate-400', 'bg-white', 'dark:bg-transparent');
            indicator.classList.add('border-emerald-400', 'bg-emerald-500', 'text-white');
            indicator.innerHTML = '✓';
            
            const trackerIcon = document.querySelector(`#tracker-${step} div`);
            if(trackerIcon) {
                trackerIcon.classList.remove('bg-white', 'dark:bg-slate-800', 'text-slate-600', 'dark:text-white', 'border-slate-200', 'dark:border-white/10');
                trackerIcon.classList.add('bg-emerald-500', 'text-white', 'shadow-[0_0_10px_rgba(16,185,129,0.4)]', 'border-emerald-400');
                trackerIcon.innerHTML = '✓';
            }

            const fb = document.getElementById('quiz-feedback');
            fb.innerHTML = '<span class="flex items-center gap-2"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Diagnosis Valid! Melanjutkan simulasi...</span>';
            fb.className = "absolute bottom-8 left-1/2 transform -translate-x-1/2 bg-emerald-500 dark:bg-emerald-600 text-white px-8 py-3 rounded-full font-black text-[11px] shadow-[0_10px_25px_rgba(16,185,129,0.3)] dark:shadow-[0_10px_25px_rgba(16,185,129,0.5)] translate-y-0 opacity-100 transition-all duration-300 z-20 flex items-center justify-center whitespace-nowrap border border-emerald-400 tracking-wide uppercase pointer-events-none";
            
            const allBtns = document.querySelectorAll(`#quiz-step-${step} button`);
            allBtns.forEach(b => {
                b.disabled = true;
                if(b !== btnElement) {
                    b.classList.add('opacity-30', 'grayscale');
                }
            });

            setTimeout(() => {
                fb.classList.remove('translate-y-0', 'opacity-100');
                fb.classList.add('translate-y-10', 'opacity-0');
                
                document.getElementById(`quiz-step-${step}`).classList.add('hidden');
                
                if(step < 4) {
                    document.getElementById(`quiz-step-${step+1}`).classList.remove('hidden');
                    document.getElementById('quiz-header-status').innerHTML = `<div class="w-1.5 h-1.5 rounded-full bg-cyan-500 animate-pulse"></div> REKAMAN SKENARIO #${step+1} / 4`;
                } else {
                    finishActivityDB();
                }
            }, 1800);

        } else {
            btnElement.classList.remove('border-slate-300', 'dark:border-white/10', 'hover:bg-slate-50', 'dark:hover:bg-white/5', 'bg-white', 'dark:bg-[#0f141e]', 'text-slate-700', 'dark:text-slate-300');
            btnElement.classList.add('border-red-400', 'bg-red-50', 'dark:bg-red-900/20', 'text-red-700', 'dark:text-red-300');
            btnElement.classList.add('shake');
            
            const indicator = btnElement.querySelector('.rounded-full');
            indicator.classList.remove('border-slate-300', 'dark:border-slate-600', 'text-slate-400', 'bg-white', 'dark:bg-transparent');
            indicator.classList.add('border-red-400', 'bg-red-500', 'text-white');
            indicator.innerHTML = '✕';
            
            const fb = document.getElementById('quiz-feedback');
            fb.innerHTML = '<span class="flex items-center gap-2"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg> Diagnosis Keliru. Bongkar kembali materi fundamental!</span>';
            fb.className = "absolute bottom-8 left-1/2 transform -translate-x-1/2 bg-red-500 dark:bg-red-600 text-white px-8 py-3 rounded-full font-black text-[11px] shadow-[0_10px_25px_rgba(225,29,72,0.3)] dark:shadow-[0_10px_25px_rgba(225,29,72,0.5)] translate-y-0 opacity-100 transition-all duration-300 z-20 flex items-center justify-center whitespace-nowrap border border-red-400 tracking-wide uppercase pointer-events-none";

            setTimeout(() => {
                btnElement.classList.remove('shake');
                btnElement.classList.remove('border-red-400', 'bg-red-50', 'dark:bg-red-900/20', 'text-red-700', 'dark:text-red-300');
                btnElement.classList.add('border-slate-300', 'dark:border-white/10', 'bg-white', 'dark:bg-[#0f141e]', 'text-slate-700', 'dark:text-slate-300', 'hover:bg-slate-50', 'dark:hover:bg-white/5');
                
                const isA = btnElement.innerText.trim().startsWith('A');
                indicator.classList.remove('border-red-400', 'bg-red-500', 'text-white');
                indicator.classList.add('border-slate-300', 'dark:border-slate-600', 'text-slate-400', 'bg-white', 'dark:bg-transparent');
                indicator.innerHTML = isA ? 'A' : 'B';
                
                fb.classList.remove('translate-y-0', 'opacity-100');
                fb.classList.add('translate-y-10', 'opacity-0');
            }, 2500);
        }
    }

    async function finishActivityDB() {
        try {
            const formData = new URLSearchParams();
            formData.append('activity_id', ACTIVITY_ID);
            formData.append('score', 100);
            formData.append('_token', '{{ csrf_token() }}');

            await fetch('/activity/complete', { 
                method: 'POST', 
                headers: { 
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'Accept': 'application/json'
                }, 
                body: formData.toString() 
            });

            await saveLessonToDB(ACTIVITY_LESSON_ID); 
            
            activityCompleted = true;
            completedSet.add(ACTIVITY_LESSON_ID);
            updateProgressUI(true);
            lockActivityUI();
        } catch(e) { 
            console.error('Pemutusan rekaman database pada misi ujian:', e); 
            alert('Pemutusan jaringan menyumbat pengiriman rekaman. Segarkan peramban Anda untuk mengulang pemanggilan persetujuan database.');
        }
    }

    function lockActivityUI() {
        const overlay = document.getElementById('activityOverlay');
        if(overlay) {
            overlay.classList.remove('hidden');
            overlay.style.display = 'flex';
        }
        
        for(let i=1; i<=4; i++) {
            const trackerIcon = document.querySelector(`#tracker-${i} div`);
            if(trackerIcon) {
                trackerIcon.className = 'w-7 h-7 rounded-lg bg-emerald-500 text-white flex items-center justify-center font-bold text-[10px] shadow-[0_0_10px_rgba(16,185,129,0.5)] transition-all border border-emerald-400';
                trackerIcon.innerHTML = '✓';
            }
        }
    }
</script>
@endsection