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
        --accent: #0ea5e9; 
        --accent-glow: rgba(14, 165, 233, 0.3);
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
        --accent-glow: rgba(14, 165, 233, 0.5);
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
    .sim-bg-adaptive { background-color: var(--simulator-bg); border-color: var(--glass-border); }

    /* SCROLLBAR */
    .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(150,150,150,0.3); border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: var(--accent); }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }
    
    /* ANIMATIONS & EFFECTS */
    #animated-bg { 
        background: radial-gradient(800px circle at 20% 20%, rgba(14,165,233,.10), transparent 40%), 
                    radial-gradient(800px circle at 80% 80%, rgba(99,102,241,.10), transparent 40%); 
        animation: bgMove 20s ease-in-out infinite alternate; 
    }
    .dark #animated-bg {
        background: radial-gradient(800px circle at 20% 20%, rgba(14,165,233,.15), transparent 40%), 
                    radial-gradient(800px circle at 80% 80%, rgba(99,102,241,.15), transparent 40%); 
    }
    @keyframes bgMove{to{transform:scale(1.15)}}
    @keyframes shake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-5px); } 75% { transform: translateX(5px); } }
    .shake { animation: shake 0.4s ease-in-out; }
    @keyframes radar-sweep { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
    .animate-radar { animation: radar-sweep 2s linear infinite; transform-origin: bottom right; }
    
    /* SCROLLSPY SIDEBAR ACTIVE */
    .nav-item { display: flex; width: 100%; text-align: left; align-items: center; gap: 12px; padding: 10px 14px; font-size: 0.85rem; color: var(--text-muted); border-radius: 8px; transition: all 0.2s; position: relative; }
    .nav-item:hover { color: var(--text-main); background: var(--card-hover); }
    .nav-item.active { color: #0ea5e9; background: rgba(14, 165, 233, 0.05); font-weight: 600; }
    .dot { width: 6px; height: 6px; border-radius: 50%; background: #94a3b8; transition: all 0.3s; }
    .dark .dot { background: #334155; }
    .nav-item.active .dot { background: #0ea5e9; box-shadow: 0 0 8px #0ea5e9; transform: scale(1.2); }

    /* CSS GRID UNTUK SIMULATOR JIT */
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
        <div class="absolute top-[-20%] left-[-10%] w-[1000px] h-[1000px] bg-cyan-500/5 dark:bg-cyan-900/20 rounded-full blur-[150px] animate-pulse transition-colors"></div>
        <div class="absolute bottom-[-20%] right-[-10%] w-[800px] h-[800px] bg-indigo-500/5 dark:bg-indigo-900/20 rounded-full blur-[100px] transition-colors"></div>
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
                        <h1 class="text-sm font-bold text-heading transition-colors">Instalasi & Konfigurasi</h1>
                        <p class="text-[10px] text-muted transition-colors">Node.js, CLI, JIT & Arsitektur Tema</p>
                    </div>
                </div>
                
                {{-- DYNAMIC PROGRESS BAR UI --}}
                <div class="flex items-center gap-3">
                    <div class="hidden sm:block w-32 h-1.5 bg-slate-200 dark:bg-white/10 rounded-full overflow-hidden transition-colors">
                        <div id="topProgressBar" class="h-full bg-gradient-to-r from-cyan-400 to-blue-500 w-0 transition-all duration-500 shadow-[0_0_10px_rgba(14,165,233,0.5)]"></div>
                    </div>
                    <span id="progressLabelTop" class="text-cyan-600 dark:text-cyan-400 font-bold text-xs transition-colors">0%</span>
                </div>
            </div>

            <div class="p-6 lg:p-16 max-w-5xl mx-auto pb-40">
                
                {{-- TUJUAN PEMBELAJARAN --}}
                <div class="mb-24 animate-fade-in-up">
                    <h3 class="text-xl font-bold text-heading mb-6 flex items-center gap-2 transition-colors">
                        <svg class="w-5 h-5 text-cyan-500 dark:text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        Tujuan Pembelajaran
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="card-adaptive p-5 rounded-xl flex items-start gap-4 hover:border-cyan-500/30 transition group h-full">
                            <div class="w-8 h-8 rounded bg-cyan-100 dark:bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 flex items-center justify-center shrink-0 font-bold text-xs transition-colors">1</div>
                            <div>
                                <h4 class="text-sm font-bold text-heading mb-1 transition-colors">Lingkungan Node</h4>
                                <p class="text-[11px] text-muted leading-relaxed transition-colors">Memahami mengapa kompilator Tailwind modern wajib dieksekusi di atas lingkungan <span class="font-semibold text-cyan-600 dark:text-cyan-400">Node.js</span>.</p>
                            </div>
                        </div>
                        <div class="card-adaptive p-5 rounded-xl flex items-start gap-4 hover:border-blue-500/30 transition group h-full">
                            <div class="w-8 h-8 rounded bg-blue-100 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0 font-bold text-xs transition-colors">2</div>
                            <div>
                                <h4 class="text-sm font-bold text-heading mb-1 transition-colors">Instalasi CLI</h4>
                                <p class="text-[11px] text-muted leading-relaxed transition-colors">Menguasai hirarki perintah terminal untuk menginisialisasi arsitektur proyek baru secara bersih.</p>
                            </div>
                        </div>
                        <div class="card-adaptive p-5 rounded-xl flex items-start gap-4 hover:border-indigo-500/30 transition group h-full">
                            <div class="w-8 h-8 rounded bg-indigo-100 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0 font-bold text-xs transition-colors">3</div>
                            <div>
                                <h4 class="text-sm font-bold text-heading mb-1 transition-colors">Radar JIT</h4>
                                <p class="text-[11px] text-muted leading-relaxed transition-colors">Menyetel jalur pemindaian <span class="font-semibold text-indigo-600 dark:text-indigo-400">Just-In-Time</span> agar tidak ada HTML yang terlewat dirender menjadi CSS.</p>
                            </div>
                        </div>
                        <div class="card-adaptive p-5 rounded-xl flex items-start gap-4 hover:border-violet-500/30 transition group h-full">
                            <div class="w-8 h-8 rounded bg-violet-100 dark:bg-violet-500/10 text-violet-600 dark:text-violet-400 flex items-center justify-center shrink-0 font-bold text-xs transition-colors">4</div>
                            <div>
                                <h4 class="text-sm font-bold text-heading mb-1 transition-colors">Arsitektur Tema</h4>
                                <p class="text-[11px] text-muted leading-relaxed transition-colors">Menghindari jebakan fatal saat memodifikasi warna khusus: <span class="font-semibold text-violet-600 dark:text-violet-400">Metode Extend vs Override</span>.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <article class="space-y-40">
                    
                    {{-- SECTION 24: PRASYARAT SISTEM (NODE.JS) --}}
                    <section id="section-24" class="lesson-section scroll-mt-32" data-lesson-id="24">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-cyan-500 pl-6">
                                <span class="text-cyan-600 dark:text-cyan-400 font-mono text-xs uppercase tracking-widest transition-colors">Lesson 1.6.1</span>
                                <h2 class="text-4xl lg:text-5xl font-black text-heading leading-[1.1] transition-colors">
                                    Prasyarat Utama: <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-500 to-blue-600 dark:from-cyan-400 dark:to-blue-500">Ekosistem Mesin Node.js</span>
                                </h2>
                            </div>
                            
                            <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-lg leading-relaxed space-y-6 text-justify transition-colors">
                                <p>
                                    Bagi para pengembang pemula, sering muncul sebuah kebingungan mendasar: <em class="font-semibold text-slate-700 dark:text-slate-300">"Mengapa saya memerlukan bahasa pemrograman sisi peladen (server) seperti Node.js hanya untuk menulis tata letak CSS yang terkesan sederhana?"</em>. Jawabannya terletak pada evolusi drastis dari arsitektur desain web modern.
                                </p>
                                <p>
                                    Perlu diingat bahwa browser standar di komputer Anda hanya dapat membaca bahasa CSS murni secara harfiah (misalnya <code>background-color: blue;</code>). Browser sama sekali tidak memahami singkatan utilitas seperti <code>bg-blue-500</code>. Di sinilah letak peran sesungguhnya dari Tailwind. Tailwind CSS <strong class="font-bold text-rose-500">bukanlah</strong> sekumpulan berkas statis raksasa yang Anda unduh lalu tautkan ke dokumen HTML. Tailwind pada dasarnya adalah sebuah <span class="font-bold text-cyan-600 dark:text-cyan-400">Perangkat Lunak Kompilator (Build Tool)</span> dinamis.
                                </p>
                                <p>
                                    Perangkat lunak cerdas ini memindai tumpukan kode HTML Anda layaknya mesin sensor di pabrik, menyeleksi dan merakit hanya kelas utilitas yang benar-benar Anda panggil, lalu menyeduhnya menjadi satu berkas CSS final yang murni dan super ringan.
                                </p>
                                <p>
                                    Namun, perangkat lunak kompilator tersebut tidak bisa beroperasi di ruang hampa. Ia membutuhkan sebuah mesin eksekusi dasar di dalam komputer Anda. Mesin penggerak pabrik itulah yang dinamakan <strong>Node.js</strong>. Bersamaan dengan instalasi Node.js, komputer Anda secara otomatis akan dilengkapi dengan sebuah fitur bernama <strong>NPM (Node Package Manager)</strong>. Anda dapat mengibaratkan NPM ini sebagai kurir logistik global yang akan bertugas mengambil mesin Tailwind dari server pusat dan mengantarkannya langsung ke dalam map proyek Anda.
                                </p>
                            </div>

                            {{-- PETUNJUK SIMULATOR 1 --}}
                            <div class="bg-cyan-50 dark:bg-cyan-900/10 border border-cyan-200 dark:border-cyan-500/20 p-5 rounded-2xl mb-6 shadow-sm transition-colors mt-12">
                                <h4 class="text-cyan-800 dark:text-cyan-400 font-bold text-xs uppercase tracking-widest mb-3 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> 
                                    Petunjuk Penggunaan Simulasi
                                </h4>
                                <p class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed">Sebelum dapat memasang Tailwind, kita harus memastikan pondasi sistem operasi Anda siap. Klik tombol <strong>Verifikasi Node.js</strong> dan <strong>Verifikasi NPM</strong> di bawah ini secara bergantian. Perhatikan bagaimana layar konsol hitam di sebelah kanan merespons untuk memvalidasi bahwa mesin sudah terinstal di komputer Anda.</p>
                            </div>

                            {{-- VISUAL SIMULATOR 1: NODE CHECKER --}}
                            <div class="sim-bg-adaptive border border-adaptive rounded-3xl p-8 lg:p-10 relative overflow-hidden group transition-all shadow-xl dark:shadow-2xl">
                                <div class="absolute -right-10 -top-10 w-32 h-32 bg-cyan-500/10 rounded-full blur-3xl transition-colors"></div>
                                <h4 class="text-sm font-bold text-heading mb-6 flex items-center gap-2 transition-colors border-b border-adaptive pb-4">
                                    <span class="w-2.5 h-2.5 rounded-full bg-cyan-500 animate-pulse shadow-[0_0_8px_rgba(6,182,212,0.6)]"></span>
                                    Simulasi Terminal: Cek Ketersediaan Mesin
                                </h4>
                                
                                <div class="grid lg:grid-cols-2 gap-10 items-stretch relative z-10">
                                    <div class="flex flex-col justify-center space-y-6">
                                        <p class="text-sm font-medium text-slate-600 dark:text-white/60 transition-colors leading-relaxed">Komputer lokal Anda akan menolak semua instalasi Tailwind jika fondasi Node.js dan armada NPM belum aktif. Eksekusi pengecekannya sekarang.</p>
                                        
                                        <div class="space-y-4">
                                            <button onclick="runNodeCheckVis('node')" class="w-full px-6 py-4 bg-slate-100 dark:bg-[#0a0e17] hover:bg-emerald-50 dark:hover:bg-emerald-900/20 border border-slate-300 dark:border-white/10 rounded-2xl text-sm font-bold transition-all text-left group shadow-sm flex items-center justify-between focus:outline-none focus:ring-2 focus:ring-emerald-500/50">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-8 h-8 rounded-full bg-slate-200 dark:bg-white/10 flex items-center justify-center group-hover:bg-emerald-500 group-hover:text-white transition-colors shadow-inner">
                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                                    </div>
                                                    <span class="text-slate-700 dark:text-white">Verifikasi Versi Node.js</span>
                                                </div>
                                                <span class="font-mono text-xs font-bold text-slate-400 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">node -v</span>
                                            </button>

                                            <button onclick="runNodeCheckVis('npm')" class="w-full px-6 py-4 bg-slate-100 dark:bg-[#0a0e17] hover:bg-blue-50 dark:hover:bg-blue-900/20 border border-slate-300 dark:border-white/10 rounded-2xl text-sm font-bold transition-all text-left group shadow-sm flex items-center justify-between focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-8 h-8 rounded-full bg-slate-200 dark:bg-white/10 flex items-center justify-center group-hover:bg-blue-500 group-hover:text-white transition-colors shadow-inner">
                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                                                    </div>
                                                    <span class="text-slate-700 dark:text-white">Verifikasi Versi NPM</span>
                                                </div>
                                                <span class="font-mono text-xs font-bold text-slate-400 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">npm -v</span>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="bg-slate-800 dark:bg-[#0a0e17] p-6 rounded-3xl border border-slate-700 dark:border-white/10 shadow-inner flex flex-col transition-colors min-h-[250px] relative overflow-hidden">
                                        <div class="flex justify-between items-center border-b border-slate-700 dark:border-white/10 pb-4 mb-4 transition-colors">
                                            <span class="font-bold text-slate-300 dark:text-slate-400 text-xs tracking-widest uppercase">Console Status</span>
                                            <div class="flex gap-1.5"><div class="w-2.5 h-2.5 rounded-full bg-red-500 shadow-sm border border-rose-600"></div><div class="w-2.5 h-2.5 rounded-full bg-amber-500 shadow-sm border border-amber-600"></div><div class="w-2.5 h-2.5 rounded-full bg-emerald-500 shadow-sm border border-emerald-600"></div></div>
                                        </div>
                                        <div id="vis-node-output" class="flex-1 flex flex-col justify-center gap-4">
                                            <div class="text-center opacity-50 transition-opacity" id="vis-node-idle">
                                                <svg class="w-12 h-12 mx-auto mb-2 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                                <p class="text-xs font-mono text-slate-400 animate-pulse">Menunggu eksekusi transmisi...</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- KETERANGAN SIMULATOR 1 --}}
                            <div class="bg-emerald-50 dark:bg-emerald-900/10 border border-emerald-200 dark:border-emerald-500/20 p-6 rounded-2xl mt-6 shadow-sm transition-colors">
                                <h4 class="text-emerald-800 dark:text-emerald-400 font-bold text-xs uppercase tracking-widest mb-3 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> 
                                    Kesimpulan Simulasi
                                </h4>
                                <p class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed font-medium">Jika keluaran konsol memunculkan kombinasi angka versi (seperti <code class="font-bold bg-emerald-100 dark:bg-emerald-900/30 px-1 rounded text-emerald-700 dark:text-emerald-300">v20.11.0</code>), itu merupakan tanda bukti bahwa mesin Node.js telah menyatu dengan sistem operasi Anda. Anda kini memiliki izin akses penuh untuk mulai mengunduh dan merakit program Tailwind menggunakan armada logistik NPM yang juga terpantau telah aktif.</p>
                            </div>
                        </div>
                    </section>

                    {{-- SECTION 25: SIMULASI INSTALASI CLI --}}
                    <section id="section-25" class="lesson-section scroll-mt-32" data-lesson-id="25">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-blue-500 pl-6">
                                <span class="text-blue-600 dark:text-blue-400 font-mono text-xs uppercase tracking-widest transition-colors">Lesson 1.6.2</span>
                                <h2 class="text-4xl lg:text-5xl font-black text-heading leading-[1.1] transition-colors">
                                    Instalasi Proyek: <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-500 to-indigo-600 dark:from-blue-400 dark:to-indigo-500">Tailwind Command Line</span>
                                </h2>
                            </div>

                            <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-lg leading-relaxed space-y-6 text-justify transition-colors">
                                <p>
                                    Ada banyak cara memanggil Tailwind ke dalam sebuah proyek web. Namun, metode yang paling bersih dan direkomendasikan secara resmi untuk struktur HTML standar adalah menggunakan alat bernama <strong>Tailwind CLI (Command Line Interface)</strong>. 
                                </p>
                                <p>
                                    Proses instalasi menggunakan terminal ini terkesan menakutkan, namun sejatinya Anda hanya perlu menghafal dan mengetikkan <strong>tiga baris perintah dasar</strong>. Ibarat sebuah proses pembangunan gedung, setiap baris perintah ini akan memanipulasi dan memunculkan fail-file pondasi baru ke dalam struktur direktori map proyek Anda:
                                </p>
                                <div class="bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-3xl p-8 shadow-sm dark:shadow-none transition-colors mt-8">
                                    <ul class="space-y-8 m-0 list-none pl-0">
                                        <li class="flex items-start gap-5">
                                            <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center font-black text-sm shrink-0 mt-0.5 shadow-sm border border-blue-200 dark:border-blue-500/30">1</div>
                                            <div>
                                                <code class="text-sm font-bold text-slate-800 dark:text-white bg-slate-200 dark:bg-black/50 px-3 py-1.5 rounded-lg shadow-inner inline-block mb-3 border border-slate-300 dark:border-white/10">npm init -y</code>
                                                <p class="text-sm text-slate-700 dark:text-slate-300 font-medium">Meresmikan map kosong Anda menjadi sebuah proyek Node.js secara resmi. Perintah inisialisasi ini menciptakan satu dokumen bernama <code>package.json</code>. Anda dapat mengibaratkan file ini sebagai "Buku Daftar Inventaris" yang bertugas mencatat dan merekam segala perangkat lunak pendukung di dalam proyek.</p>
                                            </div>
                                        </li>
                                        <li class="flex items-start gap-5">
                                            <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center font-black text-sm shrink-0 mt-0.5 shadow-sm border border-blue-200 dark:border-blue-500/30">2</div>
                                            <div>
                                                <code class="text-sm font-bold text-slate-800 dark:text-white bg-slate-200 dark:bg-black/50 px-3 py-1.5 rounded-lg shadow-inner inline-block mb-3 border border-slate-300 dark:border-white/10">npm install -D tailwindcss</code>
                                                <p class="text-sm text-slate-700 dark:text-slate-300 font-medium">Memerintahkan kurir NPM untuk menjemput dan mengunduh mesin Tailwind dari pusat server dunia. Tambahan huruf <code>-D</code> bermakna <span class="font-bold text-blue-600 dark:text-blue-400">Development Dependency</span>, sebuah instruksi tegas kepada sistem bahwa kompilator ini adalah <em class="italic">"perkakas tukang bangunan"</em> yang hanya digunakan saat bekerja lokal, dan tidak boleh disertakan saat situs diunggah (<em>deploy</em>) ke publik.</p>
                                            </div>
                                        </li>
                                        <li class="flex items-start gap-5">
                                            <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center font-black text-sm shrink-0 mt-0.5 shadow-sm border border-blue-200 dark:border-blue-500/30">3</div>
                                            <div>
                                                <code class="text-sm font-bold text-slate-800 dark:text-white bg-slate-200 dark:bg-black/50 px-3 py-1.5 rounded-lg shadow-inner inline-block mb-3 border border-slate-300 dark:border-white/10">npx tailwindcss init</code>
                                                <p class="text-sm text-slate-700 dark:text-slate-300 font-medium">Perintah akhir untuk membangkitkan dokumen sakti di direktori proyek Anda, yakni <code>tailwind.config.js</code>. File ini akan menjadi panel kendali arsitektur untuk seluruh visual proyek Anda.</p>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            {{-- PETUNJUK SIMULATOR 2 --}}
                            <div class="bg-blue-50 dark:bg-blue-900/10 border border-blue-200 dark:border-blue-500/20 p-5 rounded-2xl mb-6 shadow-sm transition-colors mt-12">
                                <h4 class="text-blue-800 dark:text-blue-400 font-bold text-xs uppercase tracking-widest mb-3 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> 
                                    Petunjuk Penggunaan Simulasi
                                </h4>
                                <p class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed">Pahami korelasi sebab-akibat antara terminal dan map proyek Anda. <strong>Klik pada teks berwarna biru yang berkedip</strong> di dalam terminal hitam (sebelah kiri) untuk memasukkan perintah secara otomatis. Kemudian alihkan perhatian Anda ke panel <strong>Struktur Direktori (sebelah kanan)</strong> untuk mengamati kemunculan fail fisik baru setiap kali sebuah instruksi diselesaikan oleh sistem.</p>
                            </div>

                            {{-- VISUAL SIMULATOR 2: CLI & FILE EXPLORER --}}
                            <div class="sim-bg-adaptive rounded-3xl border border-adaptive shadow-xl dark:shadow-2xl overflow-hidden relative group transition-colors">
                                <div class="bg-slate-100 dark:bg-[#0a0e17] px-6 py-4 border-b border-adaptive flex items-center justify-between transition-colors">
                                    <h4 class="text-xs font-black text-slate-700 dark:text-white/60 uppercase tracking-widest flex items-center gap-2">
                                        <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                        Korelasi Terminal dan Struktur File
                                    </h4>
                                    <button onclick="resetCliSimulator()" class="text-[10px] bg-slate-200 dark:bg-white/10 px-4 py-1.5 rounded-lg text-slate-600 dark:text-white/50 hover:bg-rose-100 dark:hover:bg-rose-900/30 hover:text-rose-700 dark:hover:text-rose-400 transition-colors font-bold shadow-sm active:scale-95 focus:outline-none">Ulangi Simulasi</button>
                                </div>
                                
                                <div class="grid lg:grid-cols-5 h-[450px] bg-slate-50 dark:bg-transparent">
                                    {{-- Left: Terminal --}}
                                    <div class="lg:col-span-3 bg-slate-800 dark:bg-[#0f141e] p-6 lg:p-8 flex flex-col transition-colors relative shadow-inner border-r border-slate-700 dark:border-white/5">
                                        <div class="text-slate-400 dark:text-white/40 mb-6 transition-colors text-xs font-mono border-b border-slate-700 dark:border-white/5 pb-5 leading-relaxed">
                                            <span class="text-emerald-400 font-bold tracking-widest uppercase text-[10px] bg-emerald-900/40 px-2 py-1 rounded border border-emerald-500/30 shadow-inner block w-max mb-3">Target 1</span>
                                            # Perintah untuk meresmikan direktori menjadi proyek Node.js<br>
                                            <span class="text-blue-400 font-bold cursor-pointer hover:text-blue-300 transition-colors animate-pulse inline-block mt-2 bg-blue-900/20 px-2 py-1 rounded" onclick="autoType('npm init -y')">👉 KLIK DI SINI: npm init -y</span>
                                        </div>
                                        
                                        <div id="cli-output" class="flex-1 overflow-y-auto custom-scrollbar font-mono text-sm space-y-4 pb-8 pr-2">
                                            <div class="flex items-center gap-3 terminal-line">
                                                <span class="text-emerald-400 font-bold text-lg">➜</span>
                                                <span class="text-cyan-400 font-bold tracking-wide">/project-baru</span>
                                                <input type="text" id="cli-input" class="bg-transparent border-none outline-none text-white w-full focus:ring-0 font-mono text-sm" placeholder="Ketik perintah instalasi..." autocomplete="off">
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Right: File Explorer --}}
                                    <div class="lg:col-span-2 bg-slate-100 dark:bg-[#050912] p-6 lg:p-8 transition-colors overflow-y-auto relative shadow-inner">
                                        <div class="text-[11px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-8 flex items-center gap-2 border-b border-slate-300 dark:border-slate-800 pb-3">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" /></svg>
                                            Struktur Direktori Proyek
                                        </div>
                                        
                                        <div class="space-y-4 font-mono text-[13px] text-slate-700 dark:text-slate-300 transition-colors font-medium">
                                            <div class="flex items-center gap-3 bg-white dark:bg-white/5 p-2.5 rounded-lg border border-slate-200 dark:border-white/10 shadow-sm">
                                                <svg class="w-5 h-5 text-sky-500" viewBox="0 0 24 24" fill="currentColor"><path d="M10 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2h-8l-2-2z"/></svg>
                                                <strong class="tracking-wide">my-project</strong>
                                            </div>
                                            
                                            <div class="pl-6 space-y-5 border-l-2 border-slate-300 dark:border-slate-800 ml-4 mt-4 transition-colors relative">
                                                {{-- Hidden until commands run --}}
                                                <div id="file-node-modules" class="hidden items-center gap-3 opacity-0 transform translate-x-6 transition-all duration-500 ease-out">
                                                    <svg class="w-5 h-5 text-slate-400" viewBox="0 0 24 24" fill="currentColor"><path d="M10 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2h-8l-2-2z"/></svg>
                                                    <span class="text-slate-500 line-through decoration-slate-400/50">node_modules/</span>
                                                </div>
                                                <div id="file-package-lock" class="hidden items-center gap-3 opacity-0 transform translate-x-6 transition-all duration-500 ease-out delay-150">
                                                    <svg class="w-5 h-5 text-amber-500" viewBox="0 0 24 24" fill="currentColor"><path d="M13 9h5.5L13 3.5V9M6 2h8l6 6v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4c0-1.11.89-2 2-2m5 2H6v16h12v-9h-7V4z"/></svg>
                                                    <span class="text-slate-600 dark:text-slate-400">package-lock.json</span>
                                                </div>
                                                <div id="file-package" class="hidden items-center gap-3 opacity-0 transform translate-x-6 transition-all duration-500 ease-out relative bg-amber-50 dark:bg-amber-900/10 p-2 rounded-lg border border-amber-200 dark:border-amber-500/20 shadow-sm w-max pr-4">
                                                    <div class="absolute -left-6 w-4 h-px bg-amber-400"></div>
                                                    <svg class="w-5 h-5 text-amber-500 drop-shadow-sm" viewBox="0 0 24 24" fill="currentColor"><path d="M13 9h5.5L13 3.5V9M6 2h8l6 6v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4c0-1.11.89-2 2-2m5 2H6v16h12v-9h-7V4z"/></svg>
                                                    <span class="font-bold text-amber-700 dark:text-amber-400">package.json</span>
                                                </div>
                                                <div id="file-tailwind" class="hidden items-center gap-3 opacity-0 transform translate-x-6 transition-all duration-500 ease-out relative bg-cyan-50 dark:bg-cyan-900/10 p-2 rounded-lg border border-cyan-200 dark:border-cyan-500/20 shadow-sm w-max pr-4">
                                                    <div class="absolute -left-6 w-4 h-px bg-cyan-400"></div>
                                                    <svg class="w-5 h-5 text-cyan-500 drop-shadow-sm" viewBox="0 0 24 24" fill="currentColor"><path d="M13 9h5.5L13 3.5V9M6 2h8l6 6v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4c0-1.11.89-2 2-2m5 2H6v16h12v-9h-7V4z"/></svg>
                                                    <span class="font-bold text-cyan-700 dark:text-cyan-400">tailwind.config.js</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- KETERANGAN SIMULATOR 2 --}}
                            <div class="bg-emerald-50 dark:bg-emerald-900/10 border border-emerald-200 dark:border-emerald-500/20 p-6 rounded-2xl mt-6 shadow-sm transition-colors">
                                <h4 class="text-emerald-800 dark:text-emerald-400 font-bold text-xs uppercase tracking-widest mb-3 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> 
                                    Kesimpulan Pemahaman
                                </h4>
                                <p class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed font-medium mb-3">Rangkaian 3 tahapan instruksi ini adalah ritual wajib untuk membuka kapabilitas penuh Tailwind di setiap proyek standar Anda.</p>
                                <ul class="list-disc pl-5 text-sm text-slate-600 dark:text-slate-400 space-y-1.5">
                                    <li><strong>package.json</strong> hadir mencatat fondasi struktur logistik proyek.</li>
                                    <li><strong>node_modules/</strong> hadir sebagai peti kemas fisik raksasa tempat raga perangkat lunak Tailwind disembunyikan.</li>
                                    <li>Dan <strong>tailwind.config.js</strong> mewujud sebagai file otak sistem yang akan kita bedah konfigurasinya sesaat lagi.</li>
                                </ul>
                            </div>
                        </div>
                    </section>

                    {{-- SECTION 26: JIT & CONTENT --}}
                    <section id="section-26" class="lesson-section scroll-mt-32" data-lesson-id="26">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-indigo-500 pl-6">
                                <span class="text-indigo-600 dark:text-indigo-400 font-mono text-xs uppercase tracking-widest transition-colors">Lesson 1.6.3</span>
                                <h2 class="text-4xl lg:text-5xl font-black text-heading leading-[1.1] transition-colors">
                                    Navigasi Lensa Radar: <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-violet-600 dark:from-indigo-400 dark:to-violet-500">JIT Compiler & Array Content</span>
                                </h2>
                            </div>
                            
                            <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-lg leading-relaxed space-y-6 text-justify transition-colors">
                                <p>
                                    Tepat setelah berkas <code>tailwind.config.js</code> berhasil diciptakan di direktori Anda, langkah esensial berikutnya yang mutlak dilakukan adalah mengisi rute lokasi pada blok deklarasi <strong><code class="text-indigo-700 dark:text-indigo-300 font-bold bg-indigo-50 dark:bg-indigo-900/30 px-2 py-0.5 rounded shadow-sm border border-indigo-200 dark:border-indigo-500/20">content</code></strong>. Area konfigurasi ini sering sekali dilewatkan sehingga menimbulkan kefrustrasian parah bagi pemula.
                                </p>
                                <p>
                                    Tailwind modern tidak lagi menciptakan berkas raksasa yang tidak terpakai. Ia murni digerakkan oleh arsitektur kompilator dinamis bernama <strong>Just-In-Time (JIT)</strong>. JIT ini dapat Anda visualisasikan sebagai sebuah <span class="font-semibold text-indigo-600 dark:text-indigo-400">Radar Pelacak Otomatis</span>. Agar mesin tahu kombinasi CSS mana saja yang harus ia cetak secara instan, radar ini harus dihidupkan dan diarahkan secara spesifik ke folder tempat Anda menyusun struktur dokumen HTML Anda.
                                </p>
                                <p>
                                    Arah pemindaian ini ditugaskan melalui array <em>Content</em> menggunakan sintaks rute global (Glob Pattern). Mari kita bedah makna di balik sintaks <code>"./src/**/*.html"</code> yang biasa digunakan:
                                    <ul class="list-disc pl-5 mt-4 space-y-3 border-l-2 border-indigo-200 dark:border-indigo-500/30 ml-2">
                                        <li><code class="bg-slate-200 dark:bg-black/40 px-1.5 py-0.5 rounded shadow-sm text-slate-800 dark:text-slate-300 font-bold">./src/</code> : Memerintahkan lensa radar untuk fokus menerobos masuk ke dalam sebuah direktori bernama "src".</li>
                                        <li><code class="bg-slate-200 dark:bg-black/40 px-1.5 py-0.5 rounded shadow-sm text-slate-800 dark:text-slate-300 font-bold">**</code> : Mewajibkan radar agar secara agresif terus menjelajahi seluruh lorong sub-direktori (folder anak) di dalamnya tanpa limitasi kedalaman.</li>
                                        <li><code class="bg-slate-200 dark:bg-black/40 px-1.5 py-0.5 rounded shadow-sm text-slate-800 dark:text-slate-300 font-bold">*.html</code> : Mengunci sistem sensor untuk menangkap instruksi desain <em>hanya</em> pada file yang dikonfirmasi memiliki ekstensi HTML.</li>
                                    </ul>
                                </p>
                                <p>
                                    Konsekuensinya fatal dan instan: Jika blok <code>content</code> ini Anda biarkan kosong atau salah mengeja jalurnya, radar JIT menjadi buta. Hasilnya, saat Anda memulai kompilasi, Tailwind akan menyemburkan <strong>0 Baris CSS Output</strong>. Halaman antarmuka Anda akan melongo tanpa polesan desain sepeserpun.
                                </p>
                            </div>

                            {{-- PETUNJUK SIMULATOR 3 --}}
                            <div class="bg-indigo-50 dark:bg-indigo-900/10 border border-indigo-200 dark:border-indigo-500/20 p-5 rounded-xl mb-6 shadow-sm transition-colors mt-8">
                                <h4 class="text-indigo-800 dark:text-indigo-400 font-bold text-xs uppercase tracking-widest mb-2 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> 
                                    Petunjuk Penggunaan Simulasi
                                </h4>
                                <p class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed">Klik tombol <strong>Sisipkan Target Valid</strong> untuk menyuplai arah rute radar ke dalam berkas konfigurasi di panel sebelah kiri. Lirik panel sebelah kanan, dan perhatikan bagaimana mesin radar berwarna hijau mulai memancarkan sinyal, menangkap kodingan HTML, dan sukses melahirkan bobot data CSS murni ke hadapan Anda.</p>
                            </div>

                            {{-- VISUAL SIMULATOR 3: JIT SCANNER ANIMATION --}}
                            <div class="sim-bg-adaptive border border-adaptive rounded-3xl p-8 lg:p-10 relative shadow-xl dark:shadow-2xl group transition-colors overflow-hidden">
                                <div class="absolute -top-3 left-8 bg-indigo-600 text-white text-[10px] font-black px-5 py-2 rounded-b-lg shadow-lg z-10 uppercase tracking-widest transition-colors border-x border-b border-indigo-400">Arsitektur Operasional JIT Radar</div>
                                
                                <div class="grid md:grid-cols-2 gap-10 mt-6 items-center">
                                    {{-- Kiri: Config --}}
                                    <div class="space-y-6">
                                        <label class="text-xs text-slate-700 dark:text-white/60 uppercase font-black transition-colors tracking-widest flex items-center gap-2 border-b border-slate-200 dark:border-white/10 pb-3">
                                            <div class="w-6 h-6 rounded-full bg-slate-200 dark:bg-white/10 flex items-center justify-center shadow-inner">1</div>
                                            Tentukan Titik Koordinat Radar
                                        </label>
                                        <div class="bg-slate-100 dark:bg-[#0a0e17] p-6 rounded-2xl border border-slate-300 dark:border-white/5 font-mono text-[13px] text-slate-700 dark:text-gray-400 shadow-inner transition-colors leading-loose">
                                            <span class="text-pink-600 dark:text-pink-400">module</span>.<span class="text-blue-600 dark:text-blue-300">exports</span> = {<br>
                                            &nbsp;&nbsp;<span class="text-amber-600 dark:text-amber-300">content</span>: [<br>
                                            &nbsp;&nbsp;&nbsp;&nbsp;<span id="vis-jit-path" class="text-rose-600 dark:text-rose-400 font-bold bg-rose-100 dark:bg-rose-900/30 px-2 py-0.5 rounded transition-colors animate-pulse border border-rose-200 dark:border-rose-500/30">"" // Area Kosong (Radar Buta)</span><br>
                                            &nbsp;&nbsp;],<br>
                                            }
                                        </div>
                                        <div class="flex flex-col sm:flex-row gap-4">
                                            <button onclick="runJitScan(true)" class="flex-1 py-4 px-4 bg-emerald-100 dark:bg-emerald-500/10 text-emerald-800 dark:text-emerald-400 font-black text-[10px] uppercase tracking-widest rounded-xl border border-emerald-300 dark:border-emerald-500/30 hover:bg-emerald-200 dark:hover:bg-emerald-500/40 transition-colors shadow-sm active:scale-95 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 flex items-center justify-center gap-2">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                Sisipkan Target Valid
                                            </button>
                                            <button onclick="runJitScan(false)" class="flex-1 py-4 px-4 bg-slate-200 dark:bg-white/10 text-slate-700 dark:text-white/60 font-black text-[10px] uppercase tracking-widest rounded-xl border border-slate-300 dark:border-white/20 hover:bg-slate-300 dark:hover:bg-white/20 transition-colors shadow-sm active:scale-95 focus:outline-none flex items-center justify-center gap-2">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                Hapus / Kosongkan
                                            </button>
                                        </div>
                                    </div>

                                    {{-- Kanan: Area Radar & Output --}}
                                    <div class="space-y-6">
                                        <label class="text-xs text-slate-700 dark:text-white/60 uppercase font-black transition-colors tracking-widest flex items-center gap-2 border-b border-slate-200 dark:border-white/10 pb-3">
                                            <div class="w-6 h-6 rounded-full bg-slate-200 dark:bg-white/10 flex items-center justify-center shadow-inner">2</div>
                                            Tangkapan File & Hasil Render CSS
                                        </label>
                                        <div id="jit-output-box" class="h-[280px] bg-slate-900 dark:bg-[#050912] rounded-3xl border-2 border-slate-800 dark:border-white/10 relative overflow-hidden flex items-center justify-center shadow-[inset_0_0_50px_rgba(0,0,0,0.5)] transition-colors">
                                            
                                            <div class="absolute inset-0 visual-grid opacity-20"></div>

                                            {{-- Radar Sweep UI --}}
                                            <div id="radar-ui" class="absolute w-40 h-40 border border-indigo-500/30 rounded-full flex items-center justify-center opacity-50 transition-all duration-500 z-10">
                                                <div class="absolute w-full h-full border border-indigo-500/20 rounded-full animate-ping"></div>
                                                <div class="absolute w-3/4 h-3/4 border border-indigo-500/10 rounded-full"></div>
                                                <div class="absolute w-2 h-2 bg-indigo-500 rounded-full"></div>
                                                <div class="w-1/2 h-1/2 bg-gradient-to-tr from-indigo-500/60 to-transparent rounded-tl-full absolute bottom-1/2 right-1/2 transform origin-bottom-right animate-radar hidden" id="radar-beam"></div>
                                            </div>

                                            {{-- File HTML Target --}}
                                            <div id="target-html" class="absolute left-6 top-10 flex items-center gap-3 opacity-20 transition-all duration-700 transform scale-90 z-20 bg-slate-800/80 backdrop-blur-sm p-3 rounded-xl border border-slate-700/50 shadow-lg">
                                                <div class="w-10 h-10 bg-orange-500/10 rounded-lg flex items-center justify-center border border-orange-500/30 shadow-sm shrink-0">
                                                    <svg class="w-6 h-6 text-orange-500" viewBox="0 0 24 24" fill="currentColor"><path d="M13 9h5.5L13 3.5V9M6 2h8l6 6v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4c0-1.11.89-2 2-2m5 2H6v16h12v-9h-7V4z"/></svg>
                                                </div>
                                                <div class="text-xs font-mono text-white/50">
                                                    <div class="font-bold tracking-wider">index.html</div>
                                                    <div class="text-[9px] text-emerald-400 font-bold bg-emerald-900/50 border border-emerald-500/30 px-1.5 py-0.5 rounded inline-block mt-1.5 shadow-sm">&lt;div class="bg-blue-500 p-4"&gt;</div>
                                                </div>
                                            </div>

                                            {{-- CSS Output Result --}}
                                            <div id="output-css" class="absolute right-6 bottom-8 flex items-center gap-3 opacity-0 transform translate-y-6 transition-all duration-700 z-20 bg-slate-800/80 backdrop-blur border border-slate-600/50 p-4 rounded-xl shadow-xl">
                                                <div class="text-right text-xs font-mono text-white/50">
                                                    <div class="text-cyan-400 font-black tracking-widest uppercase">output.css</div>
                                                    <div id="css-size" class="text-[10px] font-bold mt-1.5 bg-slate-900 px-3 py-1 rounded-md inline-block border border-slate-700 shadow-inner">0 Bytes</div>
                                                </div>
                                                <div class="w-10 h-10 bg-cyan-500/10 rounded-lg flex items-center justify-center border border-cyan-500/30 shadow-sm shrink-0">
                                                    <svg class="w-6 h-6 text-cyan-500" viewBox="0 0 24 24" fill="currentColor"><path d="M13 9h5.5L13 3.5V9M6 2h8l6 6v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4c0-1.11.89-2 2-2m5 2H6v16h12v-9h-7V4z"/></svg>
                                                </div>
                                            </div>

                                            {{-- Status Feedback --}}
                                            <div id="radar-status" class="absolute bg-slate-800/90 backdrop-blur-md px-6 py-2.5 rounded-full text-[10px] font-black text-slate-400 border border-slate-600 transition-all duration-300 uppercase tracking-widest z-30 shadow-lg top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-max">
                                                Radar Standby Offline
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- KETERANGAN SIMULATOR 3 --}}
                            <div class="bg-emerald-50 dark:bg-emerald-900/10 border border-emerald-200 dark:border-emerald-500/20 p-6 rounded-2xl mt-6 shadow-sm transition-colors">
                                <h4 class="text-emerald-800 dark:text-emerald-400 font-bold text-xs uppercase tracking-widest mb-3 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> 
                                    Kesimpulan Pemahaman
                                </h4>
                                <p class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed font-medium">Bila deklarasi Array Content Anda dibiarkan keliru, radar mesin JIT tidak akan dapat menemukan fail dokumen HTML desain Anda. Dampak akhirnya: JIT akan menolak menciptakan barisan kode dan menghasilkan CSS statis yang berukuran persis 0 bytes, meruntuhkan tampilan grafis web secara <em class="font-bold">silent fail</em> (gagal tanpa memberikan peringatan yang jelas di konsol).</p>
                            </div>

                        </div>
                    </section>

                    {{-- SECTION 27: KONFIGURASI TEMA --}}
                    <section id="section-27" class="lesson-section scroll-mt-32" data-lesson-id="27">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-violet-500 pl-6">
                                <span class="text-violet-600 dark:text-violet-400 font-mono text-xs uppercase tracking-widest transition-colors">Lesson 1.6.4</span>
                                <h2 class="text-4xl lg:text-5xl font-black text-heading leading-[1.1] transition-colors">
                                    Arsitektur Tema: <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-violet-500 to-fuchsia-600 dark:from-violet-400 dark:to-fuchsia-500">Extend vs Override</span>
                                </h2>
                            </div>
                            
                            <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-lg leading-relaxed space-y-6 text-justify transition-colors">
                                <p>
                                    Di dalam berkas <code>tailwind.config.js</code>, terdapat satu ruang khusus yang paling memikat daya visual: sebuah entitas objek bernama <strong><code class="text-violet-600 dark:text-violet-400 font-bold px-2 py-0.5 bg-violet-50 dark:bg-violet-900/20 rounded border border-violet-200 dark:border-violet-500/20 shadow-sm">theme</code></strong>. Ruang lingkup ini adalah ranah fondasi cetak biru <em>Design System</em> identitas produk Anda. Di sinilah lahan steril untuk mendaftarkan kombinasi palet warna khusus, menyisipkan <em>font</em> ketikan komersial, hingga mengatur batas resolusi batas-layar dinamis demi memfasilitasi kebutuhan responsivitas gawai.
                                </p>
                                <p>
                                    Meskipun begitu, arsitektur penyusunan tema Tailwind ini dikelilingi oleh satu perangkap teknis yang begitu destruktif. Konfigurasi modifikasi di dalam area ini dikategorikan ke dalam dua metodologi fundamental yang amat berlawanan dampaknya: Pendekatan <strong>Extending</strong> (Penyuntikan Aman) dan praktik brutal <strong>Overriding</strong> (Penimpaan Paksa).
                                </p>
                                
                                <div class="grid md:grid-cols-2 gap-8 mt-10">
                                    <div class="bg-emerald-50 dark:bg-[#0a0e17] p-8 lg:p-10 rounded-3xl border border-emerald-200 dark:border-emerald-500/20 hover:border-emerald-400 dark:hover:border-emerald-500/50 transition-colors shadow-sm dark:shadow-none h-full relative overflow-hidden group">
                                        <div class="absolute -right-6 -top-6 w-32 h-32 bg-emerald-500/10 rounded-full blur-3xl group-hover:bg-emerald-500/20 transition-colors"></div>
                                        <div class="flex items-center gap-4 mb-5 relative z-10">
                                            <div class="w-12 h-12 rounded-2xl bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-400 flex items-center justify-center font-black text-2xl border border-emerald-300 dark:border-emerald-500/30 transition-colors shadow-inner">✓</div>
                                            <h4 class="text-emerald-800 dark:text-emerald-400 font-black text-2xl m-0 transition-colors tracking-wide">theme.extend</h4>
                                        </div>
                                        <p class="text-sm text-slate-700 dark:text-white/70 leading-relaxed transition-colors relative z-10 text-justify font-medium">
                                            Langkah ini adalah sebuah jalur penulisan aman dan <strong>wajib dipraktikkan sebagai standarisasi</strong>. Mendaftarkan manipulasi palet kustom Anda di balik perlindungan baris <code>extend: {}</code> memastikan bahwa rona warna unik tersebut <strong>ditambahkan perlahan</strong> ke dalam daftar panjang sistem. Kelas-kelas pewarnaan korporat tersebut (seperti halnya <code>bg-brand</code>) dan susunan properti turunan pabrikan (seperti barisan utilitas <code>bg-blue-500</code> atau <code>text-red-500</code>) dapat dieksekusi secara instan dan saling melengkapi.
                                        </p>
                                    </div>
                                    
                                    <div class="bg-rose-50 dark:bg-[#0a0e17] p-8 lg:p-10 rounded-3xl border border-rose-200 dark:border-rose-500/20 hover:border-rose-400 dark:hover:border-rose-500/50 transition-colors shadow-sm dark:shadow-none h-full relative overflow-hidden group">
                                        <div class="absolute -right-6 -top-6 w-32 h-32 bg-rose-500/10 rounded-full blur-3xl group-hover:bg-rose-500/20 transition-colors"></div>
                                        <div class="flex items-center gap-4 mb-5 relative z-10">
                                            <div class="w-12 h-12 rounded-2xl bg-rose-100 dark:bg-rose-500/20 text-rose-700 dark:text-rose-400 flex items-center justify-center font-black text-2xl border border-rose-300 dark:border-rose-500/30 transition-colors shadow-inner">⚠</div>
                                            <h4 class="text-rose-800 dark:text-rose-400 font-black text-2xl m-0 transition-colors tracking-wide">theme (Override)</h4>
                                        </div>
                                        <p class="text-sm text-slate-700 dark:text-white/70 leading-relaxed transition-colors relative z-10 text-justify font-medium">
                                            Ini secara mutlak didapuk menjadi <strong>Zona Kehancuran (Fatal Zone)</strong>. Menyisipkan definisi skema <code>colors: {}</code> secara terang-terangan dan terbuka persis di bawah area indukan utama <code>theme</code> tanpa membungkus perisai extend—akan menyalakan protokol Override. Mesin rakitan Tailwind seketika mengabaikan dan <strong>menghapus/memusnahkan seutuhnya memori mengenai seluruh warna bawaan asli cetakan pengembang</strong>, lantas membekukan fungsionalitasnya menjadi satu-satunya entitas warna terdaftar. Ratusan kelas default menjadi error dan tak bisa terbaca lagi.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- PETUNJUK SIMULATOR 4 --}}
                            <div class="bg-violet-50 dark:bg-violet-900/10 border border-violet-200 dark:border-violet-500/20 p-5 rounded-xl mb-6 shadow-sm transition-colors mt-12">
                                <h4 class="text-violet-800 dark:text-violet-400 font-bold text-xs uppercase tracking-widest mb-2 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> 
                                    Petunjuk Penggunaan Simulasi
                                </h4>
                                <p class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed">Tekan tombol opsi letak skenario arsitektur di panel kiri untuk mengubah posisi kode. Telusuri langsung efek kerusakan struktural dengan menatap panel <strong>Sistem Ekosistem Kelas Bawaan</strong> di sebelah kanan. Amati bagaimana tindakan Override menelan mentah-mentah kotak indikator warna biru, merah, dan hijau.</p>
                            </div>

                            {{-- VISUAL SIMULATOR 4: THEME CONFIGURATOR --}}
                            <div class="sim-bg-adaptive border border-adaptive rounded-3xl p-8 lg:p-10 shadow-xl dark:shadow-2xl relative overflow-hidden transition-colors">
                                <div class="absolute top-0 right-0 w-64 h-64 bg-violet-500/5 rounded-full blur-3xl pointer-events-none"></div>
                                <div class="grid grid-cols-1 lg:grid-cols-5 gap-12 items-center relative z-10">
                                    
                                    <div class="lg:col-span-3 space-y-8">
                                        <div class="flex justify-between items-center border-b border-slate-200 dark:border-white/10 pb-5 transition-colors">
                                            <h3 class="text-sm font-black text-violet-700 dark:text-violet-400 uppercase tracking-widest transition-colors flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-full bg-violet-100 dark:bg-violet-900/30 flex items-center justify-center">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" /></svg>
                                                </div>
                                                Simulasi Konfigurasi Injeksi Warna
                                            </h3>
                                        </div>
                                        
                                        <div class="space-y-6">
                                            <div class="bg-slate-50 dark:bg-[#0a0e17] p-6 rounded-2xl border border-slate-200 dark:border-white/5 transition-colors shadow-sm dark:shadow-none">
                                                <label class="text-xs font-black text-slate-700 dark:text-white/60 block mb-4 transition-colors uppercase tracking-widest">Penentuan Letak Baris Injeksi Kelas Warna</label>
                                                <div class="flex flex-col sm:flex-row gap-4">
                                                    <button onclick="setThemeArchitecture('extend')" id="btn-theme-extend" class="flex-1 py-4 px-5 bg-emerald-600 text-white font-bold text-xs rounded-xl shadow-lg shadow-emerald-500/20 focus:outline-none transition-all active:scale-95 flex items-center justify-between border-2 border-emerald-400">
                                                        <span class="flex items-center gap-2"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> Sisipkan ke blok <code>extend</code></span>
                                                    </button>
                                                    <button onclick="setThemeArchitecture('override')" id="btn-theme-override" class="flex-1 py-4 px-5 bg-slate-200 dark:bg-[#111827] text-slate-600 dark:text-slate-400 font-bold text-xs rounded-xl shadow-sm border border-slate-300 dark:border-white/10 hover:bg-rose-50 hover:text-rose-700 dark:hover:bg-rose-900/20 dark:hover:text-rose-400 transition-all focus:outline-none active:scale-95 flex items-center justify-between group">
                                                        <span class="flex items-center gap-2"><svg class="w-4 h-4 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg> Timpa Objek <code>theme</code> Secara Paksa</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="bg-slate-800 dark:bg-[#0a0e17] p-8 rounded-2xl border border-slate-700 dark:border-white/10 font-mono text-xs text-slate-400 dark:text-gray-400 leading-relaxed shadow-inner transition-colors relative" id="code-theme-preview">
                                            <div class="absolute top-0 right-0 bg-slate-700 dark:bg-white/10 px-3 py-1 text-[9px] font-bold uppercase tracking-widest text-white rounded-bl-lg shadow-sm">tailwind.config.js</div>
                                            <span class="text-pink-400">module</span>.<span class="text-blue-400 dark:text-blue-300">exports</span> = {<br>
                                            &nbsp;&nbsp;<span class="text-amber-400 dark:text-amber-300">theme</span>: {<br>
                                            &nbsp;&nbsp;&nbsp;&nbsp;<span class="text-emerald-500 dark:text-emerald-400 font-bold bg-emerald-100 dark:bg-emerald-900/30 px-2 py-0.5 rounded transition-colors shadow-sm" id="extend-highlight">extend: {</span><br>
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;colors: { <br>
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="text-slate-300 dark:text-white">brand:</span> '<span class="text-violet-400 dark:text-violet-300 font-bold">#8b5cf6</span>'<br>
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}<br>
                                            &nbsp;&nbsp;&nbsp;&nbsp;<span id="extend-close" class="text-emerald-500 dark:text-emerald-400 font-bold bg-emerald-100 dark:bg-emerald-900/30 px-2 py-0.5 rounded transition-colors shadow-sm">}</span><br>
                                            &nbsp;&nbsp;}<br>
                                            }
                                        </div>
                                    </div>

                                    <div class="lg:col-span-2 flex flex-col bg-slate-100 dark:bg-[#050912] rounded-3xl border border-slate-300 dark:border-white/10 h-full min-h-[400px] relative overflow-hidden transition-colors shadow-inner p-8 lg:p-10 justify-center">
                                        <div class="absolute inset-0 visual-grid opacity-30"></div>
                                        <div class="absolute inset-0 bg-white/40 dark:bg-black/20 backdrop-blur-[1px]"></div>
                                        
                                        <div class="w-full flex flex-col gap-8 items-center z-10 relative">
                                            <div class="w-full space-y-4">
                                                <div class="flex items-center justify-between border-b border-slate-300 dark:border-white/10 pb-3">
                                                    <span class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest flex items-center gap-2"><svg class="w-4 h-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/></svg> Ekosistem Kelas Bawaan Pabrik</span>
                                                    <span id="badge-default" class="text-[9px] font-bold bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400 px-2 py-0.5 rounded uppercase transition-colors border border-emerald-200 dark:border-emerald-500/30">Terproteksi Aman</span>
                                                </div>
                                                <div id="default-palette" class="grid grid-cols-3 gap-3 transition-all duration-700">
                                                    <div class="bg-red-500 h-12 rounded-xl shadow-md border border-white/20 flex items-center justify-center text-[9px] text-white font-mono font-bold tracking-wider hover:-translate-y-1 transition-transform cursor-default">bg-red-500</div>
                                                    <div class="bg-blue-500 h-12 rounded-xl shadow-md border border-white/20 flex items-center justify-center text-[9px] text-white font-mono font-bold tracking-wider hover:-translate-y-1 transition-transform cursor-default">bg-blue-500</div>
                                                    <div class="bg-emerald-500 h-12 rounded-xl shadow-md border border-white/20 flex items-center justify-center text-[9px] text-white font-mono font-bold tracking-wider hover:-translate-y-1 transition-transform cursor-default">bg-emerald</div>
                                                </div>
                                            </div>

                                            <div class="w-full space-y-4 pt-6 border-t border-slate-300 dark:border-white/10">
                                                <div class="flex items-center justify-between border-b border-slate-300 dark:border-white/10 pb-3">
                                                    <span class="text-[10px] font-black text-violet-700 dark:text-violet-400 uppercase tracking-widest flex items-center gap-2"><svg class="w-4 h-4 text-violet-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.3 1.046A12.014 12.014 0 0010.3 1C5.163 1 1.046 5.163 1.046 10.3c0 1.002.115 1.975.331 2.906a1.001 1.001 0 001.218.736l.24-.059c.732-.183 1.503-.183 2.235 0l.24.059a1 1 0 001.218-.736c.216-.931.331-1.904.331-2.906 0-3.313 2.687-6 6-6s6 2.687 6 6c0 1.002-.115 1.975-.331 2.906a1.001 1.001 0 00-1.218.736l-.24-.059a9.996 9.996 0 00-2.235 0l-.24.059a1.002 1.002 0 00-1.218-.736c-.216-.931-.331-1.904-.331-2.906 0-1.657-1.343-3-3-3zm-3.6 8.3a1 1 0 112 0 1 1 0 01-2 0zm5.6 0a1 1 0 112 0 1 1 0 01-2 0z" clip-rule="evenodd"/></svg> Merek Identitas Khusus (Inject)</span>
                                                </div>
                                                <div id="brand-palette" class="bg-[#8b5cf6] h-16 rounded-2xl shadow-[0_10px_20px_rgba(139,92,246,0.4)] border-2 border-[#a78bfa] flex items-center justify-center text-sm text-white font-black tracking-widest transition-all duration-500 transform hover:-translate-y-2 hover:shadow-[0_15px_30px_rgba(139,92,246,0.6)] cursor-pointer">
                                                    bg-brand
                                                </div>
                                            </div>
                                        </div>

                                        <div id="theme-warning" class="absolute bottom-8 left-6 right-6 text-center opacity-0 transform translate-y-4 transition-all duration-500 z-20 pointer-events-none">
                                            <div class="bg-rose-600 dark:bg-rose-900/95 text-white text-[11px] font-black px-6 py-4 rounded-2xl border-2 border-rose-400 dark:border-rose-500/50 shadow-[0_15px_40px_rgba(225,29,72,0.5)] flex flex-col items-center gap-1.5 backdrop-blur-md">
                                                <span class="uppercase tracking-widest text-rose-200">⚠️ Bencana Fatal Terdeteksi</span>
                                                <span class="font-medium text-[10px] leading-relaxed">Metode Override menghancurkan seluruh utilitas warna murni bawaan Tailwind. Ekosistem CSS gagal mendeteksi keberadaan kelas merah, biru, dan hijau!</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- KETERANGAN SIMULATOR 4 --}}
                            <div class="bg-emerald-50 dark:bg-emerald-900/10 border border-emerald-200 dark:border-emerald-500/20 p-6 rounded-2xl mt-6 shadow-sm transition-colors">
                                <h4 class="text-emerald-800 dark:text-emerald-400 font-bold text-xs uppercase tracking-widest mb-3 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> 
                                    Kesimpulan Pemahaman Konfigurasi
                                </h4>
                                <p class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed font-medium">Dalam mendesain identitas korporat, Anda secara mutlak wajib menempatkannya pada hierarki perlindungan objek <code>extend</code> agar fondasi utilitas bawaan Tailwind tetap menyala dan merender dengan normal. Mempraktikkan metode <em>Override</em> akan memaksa Anda memprogram ulang setiap pewarnaan secara mandiri dari awal persis seperti menggunakan CSS primitif tradisional, yang mana hal itu akan mengebiri inti keunggulan efisiensi di Tailwind.</p>
                            </div>

                        </div>
                    </section>

                    {{-- FINAL ACTIVITY CHECKPOINT (SCENARIO BASED QUIZ) --}}
                    <section id="section-28" class="lesson-section scroll-mt-32 pt-10 border-t border-adaptive transition-colors" data-lesson-id="28" data-type="activity">
                        <div class="relative rounded-[3rem] sim-bg-adaptive border border-adaptive p-8 lg:p-12 overflow-hidden shadow-2xl group hover:border-cyan-500/30 transition-all duration-500 text-center">
                            
                            {{-- OVERLAY MISSION COMPLETE --}}
                            <div id="activityOverlay" class="hidden absolute inset-0 bg-white/95 dark:bg-[#0b0f19]/95 z-30 flex-col items-center justify-center backdrop-blur-md transition-colors">
                                <div class="w-24 h-24 bg-emerald-100 dark:bg-emerald-500/10 rounded-full flex items-center justify-center mb-6 border border-emerald-300 dark:border-emerald-500/50 shadow-[0_0_40px_rgba(16,185,129,0.3)] transition-colors transform hover:scale-110 duration-300">
                                    <svg class="w-12 h-12 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                </div>
                                <h3 class="text-4xl font-black text-slate-900 dark:text-white mb-3 tracking-tight transition-colors uppercase">Analisis Sistem Tuntas!</h3>
                                <p class="text-slate-600 dark:text-white/60 text-base mb-10 font-medium transition-colors max-w-md mx-auto leading-relaxed">Luar biasa! Anda telah memahami teori fundamental sistem kompilator Tailwind dan membuktikan kemampuan menganalisis kesalahan instalasi teknis di dunia nyata. Kompetensi Modul dikunci.</p>
                                <button disabled class="px-8 py-3.5 rounded-2xl bg-slate-200 dark:bg-white/5 border border-slate-300 dark:border-white/10 text-slate-500 dark:text-white/40 text-xs font-black cursor-not-allowed uppercase tracking-widest transition-colors shadow-inner dark:shadow-none">
                                    Log Pemulihan Tersimpan
                                </button>
                            </div>

                            <div class="relative z-10 flex flex-col xl:flex-row gap-12 text-left items-stretch">
                                {{-- Left: Mission Briefing --}}
                                <div class="w-full xl:w-1/3 flex flex-col">
                                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-cyan-100 dark:bg-cyan-900/40 text-cyan-800 dark:text-cyan-400 text-[10px] font-black uppercase mb-6 tracking-widest shadow-sm dark:shadow-lg dark:shadow-cyan-600/20 border border-cyan-300 dark:border-cyan-500/30 transition-colors w-max">
                                        Misi Ujian Penutup
                                    </div>
                                    <h2 class="text-3xl lg:text-4xl font-black text-heading mb-4 tracking-tight transition-colors leading-[1.1]">Sistem Diagnosis Darurat</h2>
                                    <p class="text-slate-600 dark:text-white/70 text-sm mb-8 leading-relaxed text-justify transition-colors">
                                        Sebagai <strong>Lead Frontend Architect</strong>, Anda mendadak dikejutkan dengan datangnya pesan tiket darurat bantuan dari seorang <span class="italic font-semibold text-rose-500 dark:text-rose-400">Junior Intern</span> yang sedang belajar mempraktikkan proses inisialisasi basis Tailwind dari awal. Susunan kode proyeknya berantakan dan gagal menstimulasi antarmuka. <br><br>Gunakan asahan kejelian logika yang baru saja Anda resapi dari empat pemaparan modul bab di atas untuk memecahkan secara bertahap setiap rekayasa skenario kesalahan kelumpuhan fatal sistem di panel terminal sebelah kanan!
                                    </p>

                                    {{-- Task List Tracker --}}
                                    <div class="bg-slate-100 dark:bg-[#0a0e17] border border-slate-300 dark:border-white/10 rounded-3xl p-8 shadow-inner transition-colors flex-1">
                                        <h4 class="text-slate-800 dark:text-white text-xs font-black mb-6 uppercase tracking-widest border-b border-slate-300 dark:border-white/10 pb-4 transition-colors flex items-center gap-2"><svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 012-2h2a2 2 0 012 2" /></svg> Riwayat Tahapan Resolusi Pemulihan:</h4>
                                        <div class="space-y-5 font-bold text-xs text-slate-500 dark:text-white/50 tracking-wide">
                                            <div id="tracker-1" class="flex items-center gap-4 transition-colors">
                                                <div class="w-8 h-8 rounded-lg bg-slate-300 dark:bg-slate-800 flex items-center justify-center font-black text-[11px] shadow-inner transition-colors">1</div> <span>Integritas Ketersediaan Basis Mesin</span>
                                            </div>
                                            <div id="tracker-2" class="flex items-center gap-4 transition-colors">
                                                <div class="w-8 h-8 rounded-lg bg-slate-300 dark:bg-slate-800 flex items-center justify-center font-black text-[11px] shadow-inner transition-colors">2</div> <span>Kecakapan Penulisan Instruksi CLI</span>
                                            </div>
                                            <div id="tracker-3" class="flex items-center gap-4 transition-colors">
                                                <div class="w-8 h-8 rounded-lg bg-slate-300 dark:bg-slate-800 flex items-center justify-center font-black text-[11px] shadow-inner transition-colors">3</div> <span>Sinkronisasi Lensa Radar Pemindai JIT</span>
                                            </div>
                                            <div id="tracker-4" class="flex items-center gap-4 transition-colors">
                                                <div class="w-8 h-8 rounded-lg bg-slate-300 dark:bg-slate-800 flex items-center justify-center font-black text-[11px] shadow-inner transition-colors">4</div> <span>Keamanan Struktur Arsitektur Warna</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Right: Interactive Scenario Quiz --}}
                                <div class="w-full xl:w-2/3 flex flex-col">
                                    <div class="bg-white dark:bg-[#0a0e17] border border-adaptive rounded-[2.5rem] shadow-xl dark:shadow-2xl overflow-hidden flex-1 flex flex-col relative transition-colors h-full min-h-[450px]">
                                        
                                        {{-- Header Bar --}}
                                        <div class="bg-slate-100 dark:bg-white/5 border-b border-adaptive px-8 py-5 flex justify-between items-center transition-colors">
                                            <div class="flex gap-2">
                                                <div class="w-3.5 h-3.5 rounded-full bg-red-500 shadow-sm border border-red-600"></div><div class="w-3.5 h-3.5 rounded-full bg-amber-400 shadow-sm border border-amber-500"></div><div class="w-3.5 h-3.5 rounded-full bg-emerald-500 shadow-sm border border-emerald-600"></div>
                                            </div>
                                            <div id="quiz-header-status" class="text-[10px] font-black text-cyan-700 dark:text-cyan-400 uppercase tracking-widest px-4 py-1.5 bg-cyan-100 dark:bg-cyan-900/30 border border-cyan-200 dark:border-cyan-500/30 rounded-lg shadow-inner transition-colors">
                                                INSPEKSI LOG TICKET #1 / 4
                                            </div>
                                        </div>

                                        {{-- Body Quiz Area --}}
                                        <div class="p-8 lg:p-12 flex-1 flex flex-col relative bg-slate-50 dark:bg-transparent transition-colors">
                                            
                                            {{-- STEP 1 --}}
                                            <div id="quiz-step-1" class="flex-1 flex flex-col transition-all duration-500">
                                                <span class="text-rose-600 dark:text-rose-400 text-[11px] font-black uppercase tracking-widest mb-4 inline-flex items-center gap-3"><div class="w-3 h-3 rounded-full bg-rose-500 animate-pulse shadow-[0_0_10px_rgba(244,63,94,0.6)]"></div> Laporan Bencana #1</span>
                                                <h3 class="text-2xl font-bold text-slate-800 dark:text-white mb-8 transition-colors leading-relaxed">Sang pengembang mencoba melancarkan instalasi perangkat lunak kompilator, namun panel terminal mendadak menembakkan kode error <code class="text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 px-2 py-1 rounded-lg text-sm font-mono border border-red-200 dark:border-red-500/20 shadow-sm">"npm: command not found"</code>. Berdasarkan pemahaman teknis Anda, apa prasyarat pondasi komputasi yang terlewat olehnya?</h3>
                                                <div class="space-y-4 mt-auto">
                                                    <button onclick="checkAnswer(1, false, this)" class="w-full text-left p-6 rounded-3xl border-2 border-slate-200 dark:border-white/10 hover:border-slate-300 dark:hover:border-white/20 hover:bg-white dark:hover:bg-white/5 text-sm font-bold text-slate-700 dark:text-white/80 transition-all shadow-sm focus:outline-none group bg-slate-100 dark:bg-[#0f141e]">
                                                        <span class="text-slate-400 group-hover:text-slate-600 dark:group-hover:text-white mr-3 font-black text-lg">A.</span> Ia lalai dalam menyisipkan deklarasi inisialisasi tag koneksi tautan (<code class="font-mono bg-slate-200 dark:bg-slate-800 px-1.5 py-0.5 rounded shadow-inner">&lt;link&gt;</code>) menuju jaringan server konten eksternal di dalam berkas pusat <code>index.html</code>.
                                                    </button>
                                                    <button onclick="checkAnswer(1, true, this)" class="w-full text-left p-6 rounded-3xl border-2 border-slate-200 dark:border-white/10 hover:border-slate-300 dark:hover:border-white/20 hover:bg-white dark:hover:bg-white/5 text-sm font-bold text-slate-700 dark:text-white/80 transition-all shadow-sm focus:outline-none group bg-slate-100 dark:bg-[#0f141e]">
                                                        <span class="text-slate-400 group-hover:text-slate-600 dark:group-hover:text-white mr-3 font-black text-lg">B.</span> Ia belum meresmikan pemasangan lingkungan mesin eksekusi perangkat lunak <strong>Node.js</strong> beserta modul paket manajernya di dalam dasar sistem operasi komputer lokalnya.
                                                    </button>
                                                </div>
                                            </div>

                                            {{-- STEP 2 --}}
                                            <div id="quiz-step-2" class="hidden flex-1 flex flex-col transition-all duration-500">
                                                <span class="text-rose-600 dark:text-rose-400 text-[11px] font-black uppercase tracking-widest mb-4 inline-flex items-center gap-3"><div class="w-3 h-3 rounded-full bg-rose-500 animate-pulse shadow-[0_0_10px_rgba(244,63,94,0.6)]"></div> Laporan Bencana #2</span>
                                                <h3 class="text-2xl font-bold text-slate-800 dark:text-white mb-8 transition-colors leading-relaxed">Krisis pendeteksian teratasi. Lingkungan Node.js sudah online di dalam komputernya. Ia kemudian menelusuri langkah untuk membuat file manifestasi pelacakan modul dependensi bernama <code>package.json</code> secara otomatis. Instruksi CLI murni apakah yang tepat untuk ia serukan di Terminal?</h3>
                                                <div class="space-y-4 mt-auto">
                                                    <button onclick="checkAnswer(2, true, this)" class="w-full text-left p-6 rounded-3xl border-2 border-slate-200 dark:border-white/10 hover:border-slate-300 dark:hover:border-white/20 hover:bg-white dark:hover:bg-white/5 text-sm font-bold text-slate-700 dark:text-white/80 transition-all shadow-sm focus:outline-none group bg-slate-100 dark:bg-[#0f141e]">
                                                        <span class="text-slate-400 group-hover:text-slate-600 dark:group-hover:text-white mr-3 font-black text-lg">A.</span> Mengetikkan paksa baris perintah inisialisasi lingkungan dasar: <code class="bg-slate-200 dark:bg-black/40 border border-slate-300 dark:border-white/10 px-2.5 py-1 rounded-lg font-bold text-indigo-600 dark:text-indigo-400 shadow-sm ml-1.5">npm init -y</code>
                                                    </button>
                                                    <button onclick="checkAnswer(2, false, this)" class="w-full text-left p-6 rounded-3xl border-2 border-slate-200 dark:border-white/10 hover:border-slate-300 dark:hover:border-white/20 hover:bg-white dark:hover:bg-white/5 text-sm font-bold text-slate-700 dark:text-white/80 transition-all shadow-sm focus:outline-none group bg-slate-100 dark:bg-[#0f141e]">
                                                        <span class="text-slate-400 group-hover:text-slate-600 dark:group-hover:text-white mr-3 font-black text-lg">B.</span> Mengetikkan sentuhan perintah arsitektur pemicu file konfigurasi desain murni: <code class="bg-slate-200 dark:bg-black/40 border border-slate-300 dark:border-white/10 px-2.5 py-1 rounded-lg font-bold text-indigo-600 dark:text-indigo-400 shadow-sm ml-1.5">npx tailwindcss init</code>
                                                    </button>
                                                </div>
                                            </div>

                                            {{-- STEP 3 --}}
                                            <div id="quiz-step-3" class="hidden flex-1 flex flex-col transition-all duration-500">
                                                <span class="text-rose-600 dark:text-rose-400 text-[11px] font-black uppercase tracking-widest mb-4 inline-flex items-center gap-3"><div class="w-3 h-3 rounded-full bg-rose-500 animate-pulse shadow-[0_0_10px_rgba(244,63,94,0.6)]"></div> Laporan Bencana #3</span>
                                                <h3 class="text-2xl font-bold text-slate-800 dark:text-white mb-8 transition-colors leading-relaxed">Perakitan dan instalasi mesin terminal berjalan mulus tiada halangan. Ia pun tersenyum percaya diri dan mulai menyuntikkan kelas pondasi utilitas <code>bg-red-500</code> di hamparan file struktur dokumen HTML miliknya. Namun petaka hadir; saat program dirender secara statis ke jendela grafis peraga browser, <strong>berkas file kompilasi CSS Tailwind outputnya tercetak hampa dan berwujud mutlak 0 Bytes</strong>. Tata letak struktur komponen warna sama sekali tidak terangkai bereaksi naik ke layar antarmuka. Di manakah letak titik kelumpuhan radarnya?</h3>
                                                <div class="space-y-4 mt-auto">
                                                    <button onclick="checkAnswer(3, false, this)" class="w-full text-left p-6 rounded-3xl border-2 border-slate-200 dark:border-white/10 hover:border-slate-300 dark:hover:border-white/20 hover:bg-white dark:hover:bg-white/5 text-sm font-bold text-slate-700 dark:text-white/80 transition-all shadow-sm focus:outline-none group bg-slate-100 dark:bg-[#0f141e]">
                                                        <span class="text-slate-400 group-hover:text-slate-600 dark:group-hover:text-white mr-3 font-black text-lg">A.</span> Mesin kompilator radar cerdas JIT (Just-in-Time) tidak diciptakan dengan kapasitas dukungan untuk memproses properti kelas pewarnaan dasar tipe merah.
                                                    </button>
                                                    <button onclick="checkAnswer(3, true, this)" class="w-full text-left p-6 rounded-3xl border-2 border-slate-200 dark:border-white/10 hover:border-slate-300 dark:hover:border-white/20 hover:bg-white dark:hover:bg-white/5 text-sm font-bold text-slate-700 dark:text-white/80 transition-all shadow-sm focus:outline-none group bg-slate-100 dark:bg-[#0f141e]">
                                                        <span class="text-slate-400 group-hover:text-slate-600 dark:group-hover:text-white mr-3 font-black text-lg">B.</span> Ia secara fatal membiarkan titik koordinat jalur lokasi folder di dalam blok <strong>Array Content</strong> kosong. Radar pemindainya menjadi buta dan malah memindai ruang hampa.
                                                    </button>
                                                </div>
                                            </div>

                                            {{-- STEP 4 --}}
                                            <div id="quiz-step-4" class="hidden flex-1 flex flex-col transition-all duration-500">
                                                <span class="text-rose-600 dark:text-rose-400 text-[11px] font-black uppercase tracking-widest mb-4 inline-flex items-center gap-3"><div class="w-3 h-3 rounded-full bg-rose-500 animate-pulse shadow-[0_0_8px_rgba(244,63,94,0.6)]"></div> Laporan Bencana #4</span>
                                                <h3 class="text-2xl font-bold text-slate-800 dark:text-white mb-8 transition-colors leading-relaxed">Target pengunci koordinat JIT kembali sinkron secara online. Kendala ujian akhir pun menanti: Tim pemandu grafis produk memaksa masuknya penambahan palet skema penyuntikan kode warna eksklusif guna mencerminkan identitas perusahaaan. Sang intern pun menuruti instruksi. Sedetik kemudian, air matanya berlinang karena menyadari <strong>ratusan palet ragam utilitas warna kombinasi struktur dasar warisan Tailwind musnah dari website dan mendadak memicu ledakan error grafis massal.</strong> Apa jenis tindakan sesat penulisan kecerobohan instruksi fatal arsitekturnya di lembar file konfigurasi visualnya?</h3>
                                                <div class="space-y-4 mt-auto">
                                                    <button onclick="checkAnswer(4, true, this)" class="w-full text-left p-6 rounded-3xl border-2 border-slate-200 dark:border-white/10 hover:border-slate-300 dark:hover:border-white/20 hover:bg-white dark:hover:bg-white/5 text-sm font-bold text-slate-700 dark:text-white/80 transition-all shadow-sm focus:outline-none group bg-slate-100 dark:bg-[#0f141e]">
                                                        <span class="text-slate-400 group-hover:text-slate-600 dark:group-hover:text-white mr-3 font-black text-lg">A.</span> Ia mengaktivasi dekonstruksi metode <strong>Override</strong> dengan mendeklarasikan parameter susunan warna secara mentah langsung menimpa area pusaran blok <code>theme</code>, alih-alih meletakkannya di zona pengamanan perisai <code>extend</code>.
                                                    </button>
                                                    <button onclick="checkAnswer(4, false, this)" class="w-full text-left p-6 rounded-3xl border-2 border-slate-200 dark:border-white/10 hover:border-slate-300 dark:hover:border-white/20 hover:bg-white dark:hover:bg-white/5 text-sm font-bold text-slate-700 dark:text-white/80 transition-all shadow-sm focus:outline-none group bg-slate-100 dark:bg-[#0f141e]">
                                                        <span class="text-slate-400 group-hover:text-slate-600 dark:group-hover:text-white mr-3 font-black text-lg">B.</span> Ia belum mendaftarkan kredensial tagihan akun premium untuk lisensi akses palet warna berbayar pada modul komunal di sistem registri peranti lunak NPM global.
                                                    </button>
                                                </div>
                                            </div>

                                            {{-- SUCCESS OVERLAY PER STEP --}}
                                            <div id="quiz-feedback" class="absolute bottom-12 left-1/2 transform -translate-x-1/2 bg-emerald-600 text-white px-10 py-4 rounded-full font-black text-sm shadow-[0_15px_40px_rgba(16,185,129,0.3)] translate-y-24 opacity-0 transition-all duration-300 flex items-center gap-3 tracking-wide uppercase border-2 border-emerald-400 z-20">
                                                <span>Diagnosis Analisis Terverifikasi! Sinkronisasi Skenario Berikunya...</span>
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
                        <div class="w-12 h-12 rounded-full border border-adaptive flex items-center justify-center group-hover:bg-slate-100 dark:group-hover:bg-white/5 transition-colors shadow-sm dark:shadow-none">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        </div>
                        <div class="text-left hidden sm:block">
                            <div class="text-[10px] uppercase tracking-widest font-bold opacity-60">Modul Sebelumnya</div>
                            <div class="font-black text-sm">Keunggulan Praktis</div>
                        </div>
                    </a>
                    
                    <div id="nextChapterBtn" class="group flex items-center gap-4 text-right text-muted cursor-not-allowed opacity-50 pointer-events-none transition-all duration-500">
                        <div class="text-right hidden sm:block">
                            <div id="nextLabel" class="text-[10px] uppercase tracking-widest font-bold opacity-60">Status Akses: Terkunci</div>
                            <div class="font-black text-sm">Laboratorium Praktik Final</div>
                        </div>
                        <div id="nextIcon" class="w-12 h-12 rounded-full border border-adaptive flex items-center justify-center bg-slate-100 dark:bg-white/5 transition-colors shadow-sm dark:shadow-none">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 00-2-2H6a2 2 0 00-2-2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
    /* ==========================================
       1. STATE & KONFIGURASI AWAL
       ========================================== */
    window.LESSON_IDS = [24, 25, 26, 27, 28]; 
    let rawCompletedIds = {!! json_encode($completedLessonIds ?? []) !!};
    window.COMPLETED_IDS = rawCompletedIds.map(id => Number(id)); 
    let completedSet = new Set(window.COMPLETED_IDS);
    
    const ACTIVITY_ID = 6; 
    const ACTIVITY_LESSON_ID = 28; 
    let activityCompleted = {!! ($activityCompleted ?? false) ? 'true' : 'false' !!};

    /* ==========================================
       2. INISIALISASI SAAT HALAMAN DIMUAT
       ========================================== */
    document.addEventListener('DOMContentLoaded', () => {
        initSidebarScroll(); 
        initVisualEffects();
        initTerminalSimulator();
        
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
       3. LOGIKA PROGRESS BAR DINAMIS
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
                    dot.outerHTML = `<svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>`;
                }
            }
        }
    }

    /* ==========================================
       4. AUTO-SAVE DATABASE
       ========================================== */
    async function saveLessonToDB(lessonId) { 
        lessonId = Number(lessonId);
        if(completedSet.has(lessonId)) return; 

        completedSet.add(lessonId);
        updateProgressUI(true);
        markSidebarDone(lessonId);

        try {
            const formData = new URLSearchParams();
            formData.append('lesson_id', lessonId);
            formData.append('_token', '{{ csrf_token() }}');

            const response = await fetch('{{ route("lesson.complete") }}', { 
                method: 'POST', 
                headers: { 
                    'Content-Type': 'application/x-www-form-urlencoded' 
                }, 
                body: formData.toString() 
            });

            if (!response.ok) throw new Error("Kegagalan transmisi progres ke server.");
            
        } catch(e) {
            console.error('Network Error:', e);
            completedSet.delete(lessonId);
            updateProgressUI(true);
        }
    }

    /* ==========================================
       5. MASTER SCROLL OBSERVER
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

    /* --- SCROLL SPY & SIDEBAR LOGIC ASLI --- */
    function highlightAnchor(id) {
        const isDark = document.documentElement.classList.contains('dark');
        const anchors = document.querySelectorAll('.sidebar-anchor');

        anchors.forEach(a => {
            a.classList.remove('bg-slate-100', 'dark:bg-white/5', 'border-cyan-500', 'border-amber-500');
            a.classList.add('border-transparent');
            
            const dot = a.querySelector('.anchor-dot');
            const isActivity = a.dataset.type === 'activity';

            if(dot) {
                dot.classList.remove('scale-125', 'shadow-sm', 'dark:shadow-[0_0_10px_#06b6d4]', 'dark:shadow-[0_0_10px_#f59e0b]', 'bg-cyan-500', 'dark:bg-cyan-400');
                
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
                    dot.classList.add(isDark ? 'dark:bg-cyan-400' : 'bg-cyan-500', 'scale-125', isDark ? 'dark:shadow-[0_0_10px_#06b6d4]' : 'shadow-sm');
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
                if (m.scrollTop >= s.offsetTop - 250) c = s.id;
            });
            l.forEach(k => {
                k.classList.remove('active');
                if (k.getAttribute('data-target') === c) k.classList.add('active')
            })
        });
    }

    /* ==========================================
       6. NAVIGASI CHAPTER (UNLOCK/LOCK)
       ========================================== */
    function unlockNextChapter() {
        const btn = document.getElementById('nextChapterBtn');
        const label = document.getElementById('nextLabel');
        const icon = document.getElementById('nextIcon');

        if(btn && label && icon) {
            btn.classList.remove('cursor-not-allowed', 'opacity-50', 'pointer-events-none', 'text-muted');
            btn.classList.add('text-cyan-600', 'dark:text-cyan-400', 'cursor-pointer');
            
            label.innerText = "Akses Laboratorium Tersedia";
            label.classList.remove('opacity-60');
            label.classList.add('opacity-100', 'text-cyan-700', 'dark:text-cyan-400');
            
            icon.innerHTML = `<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>`;
            icon.classList.remove('bg-slate-100', 'dark:bg-white/5');
            icon.classList.add('bg-cyan-100', 'dark:bg-cyan-500/20', 'border-cyan-300', 'dark:border-cyan-500/50', 'text-cyan-700', 'dark:text-cyan-400', 'shadow-lg');
            
            btn.onclick = () => window.location.href = "{{ route('lab.start', ['id' => 2]) }}"; 
        }
    }

    function lockNextChapter() {
        const btn = document.getElementById('nextChapterBtn');
        if(btn) {
            btn.classList.add('cursor-not-allowed', 'opacity-50', 'pointer-events-none', 'text-muted');
            btn.classList.remove('text-cyan-600', 'dark:text-cyan-400', 'cursor-pointer');
            btn.onclick = null;
        }
    }

    /* ==========================================
       7. LOGIKA VISUAL SIMULATOR (1,2,3,4)
       ========================================== */
       
    // SIMULATOR 1: NODE CHECKER VISUAL
    function runNodeCheckVis(type) {
        const output = document.getElementById('node-check-output');
        const idle = document.getElementById('vis-node-idle');
        if(idle) idle.style.display = 'none';

        const wrap = document.createElement('div');
        wrap.className = "flex items-start gap-3 p-4 bg-slate-700/50 dark:bg-white/5 rounded-xl border border-slate-600/50 dark:border-white/10 terminal-line transition-colors";
        
        if(type === 'node') {
            wrap.innerHTML = `
                <div class="w-10 h-10 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center shrink-0 border border-emerald-300 dark:border-emerald-500/30 shadow-sm transition-colors">
                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                </div>
                <div>
                    <div class="text-slate-300 dark:text-slate-400 text-[11px] mb-2 font-bold tracking-widest uppercase">Target Eksekusi: <span class="text-emerald-500 font-mono lowercase">node -v</span></div>
                    <div class="text-emerald-700 dark:text-emerald-400 font-black tracking-wider bg-emerald-100 dark:bg-emerald-500/10 px-2.5 py-1 rounded shadow-inner inline-block transition-colors border border-emerald-300 dark:border-emerald-500/30">v20.11.0 (LTS Target Engine Verified)</div>
                </div>
            `;
        } else {
            wrap.innerHTML = `
                <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center shrink-0 border border-blue-300 dark:border-blue-500/30 shadow-sm transition-colors">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                </div>
                <div>
                    <div class="text-slate-300 dark:text-slate-400 text-[11px] mb-2 font-bold tracking-widest uppercase">Target Eksekusi: <span class="text-blue-500 font-mono lowercase">npm -v</span></div>
                    <div class="text-blue-700 dark:text-blue-400 font-black tracking-wider bg-blue-100 dark:bg-blue-500/10 px-2.5 py-1 rounded shadow-inner inline-block transition-colors border border-blue-300 dark:border-blue-500/30">10.2.4 (NPM Logistics Connected)</div>
                </div>
            `;
        }
        output.appendChild(wrap);
        output.scrollTop = output.scrollHeight;
    }

    // SIMULATOR 2: CLI TERMINAL
    function resetCliSimulator() {
        document.getElementById('cli-output').innerHTML = `
            <div class="flex items-center gap-3 terminal-line">
                <span class="text-emerald-400 font-bold text-lg">➜</span>
                <span class="text-cyan-400 font-bold">/project-baru</span>
                <input type="text" id="cli-input" class="bg-transparent border-none outline-none text-white w-full focus:ring-0 font-mono text-sm" placeholder="Menunggu instruksi terminal..." autocomplete="off">
            </div>
        `;
        
        document.getElementById('terminal-hint').innerHTML = `<span class="text-xs font-bold text-slate-700 dark:text-white/70 transition-colors tracking-wide">Tahap 1: Inisialisasi manifest package.json</span><code class="text-xs bg-slate-200 dark:bg-black/50 border border-slate-300 dark:border-white/10 px-4 py-2 rounded-lg text-indigo-600 dark:text-yellow-400 font-bold cursor-pointer hover:bg-slate-300 dark:hover:bg-black/70 transition-colors shadow-sm active:scale-95" onclick="autoType('npm init -y')">npm init -y</code>`;
        initTerminalSimulator();
    }

    function initTerminalSimulator() {
        const input = document.getElementById('cli-input');
        const output = document.getElementById('cli-output');
        const hint = document.getElementById('terminal-hint');
        
        let step = 0;
        const steps = [
            { cmd: 'npm init -y', res: '<span class="text-emerald-500 font-black">✔</span> Manifest cetak biru <b>package.json</b> berhasil dibangkitkan. Proyek Node.js dirintis.', hint: 'Tahap 2: Minta armada NPM mengunduh mesin Tailwind dari bursa.', hintCode: 'npm install -D tailwindcss'},
            { cmd: 'npm install -D tailwindcss', res: '<span class="text-blue-500 font-black text-lg">⬇</span> Ekstraksi dependensi berjalan aman... <br> <span class="text-emerald-500 font-black">✔</span> Komponen mesin kompilator pendukung pengembangan berhasil dilampirkan.', hint: 'Tahap 3: Perintahkan NPX memicu arsitektur pusat kendali instalasi akhir.', hintCode: 'npx tailwindcss init'},
            { cmd: 'npx tailwindcss init', res: '<span class="text-emerald-500 font-black">✔</span> Sistem kendali arsitektur online: <strong>tailwind.config.js</strong>', hint: 'Fondasi Rampung!', hintCode: ''}
        ];

        window.autoType = function(text) {
            if(input) { input.value = text; input.focus(); }
        };

        if(input) {
            input.addEventListener('keypress', function (e) {
                if (e.key === 'Enter') {
                    const val = this.value.trim();
                    const historyItem = document.createElement('div');
                    historyItem.className = "mb-5 text-xs font-mono transition-colors terminal-line leading-relaxed";
                    historyItem.innerHTML = `<div class="flex items-center gap-3"><span class="text-emerald-500 font-black text-lg">➜</span> <span class="text-white font-bold bg-slate-700 px-3 py-1 rounded-lg shadow-sm border border-slate-600">${val}</span></div>`;
                    
                    if(step < steps.length && val === steps[step].cmd) {
                        historyItem.innerHTML += `<div class="text-gray-400 ml-7 mt-3 border-l-4 border-emerald-500/50 pl-4 transition-colors py-1.5 bg-slate-800/50 rounded-r-lg shadow-inner">${steps[step].res}</div>`;
                        output.insertBefore(historyItem, this.parentElement);

                        step++;
                        this.value = '';
                        
                        if(step < steps.length) {
                            hint.querySelector('span').innerText = steps[step-1].hint;
                            if(steps[step-1].hintCode) {
                                hint.querySelector('code').innerText = steps[step-1].hintCode;
                                hint.querySelector('code').setAttribute('onclick', `autoType('${steps[step-1].hintCode}')`);
                            }
                        } else {
                            hint.innerHTML = `<span class="text-emerald-600 dark:text-emerald-400 font-black tracking-widest uppercase transition-colors flex items-center gap-2"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> Rangkaian Instalasi Terminal Selesai! Ekosistem terbentuk.</span>`;
                            this.placeholder = 'Console Locked.';
                            this.disabled = true;
                        }
                    } else {
                        historyItem.innerHTML += `<div class="text-rose-400 ml-7 mt-3 font-bold transition-colors border-l-4 border-rose-500/80 pl-4 py-1.5 bg-rose-900/20 rounded-r-lg shadow-inner">Peringatan: Instruksi perintah ditolak oleh sistem karena sintaks tidak valid atau meloncati urutan. Cobalah mengetikkan perintah berikut:<br><code class="bg-rose-900/50 border border-rose-500/30 px-3 py-1 rounded-md mt-2 inline-block shadow-inner">${steps[step].cmd}</code></div>`;
                        output.insertBefore(historyItem, this.parentElement);
                        
                        this.parentElement.classList.add('shake');
                        setTimeout(() => this.parentElement.classList.remove('shake'), 500);
                        this.value = '';
                    }
                    output.scrollTop = output.scrollHeight;
                }
            });
        }
    }

    // SIMULATOR 3: VISUAL JIT RADAR ANIMATION
    function runJitScan(isCorrect) {
        const pathEl = document.getElementById('vis-jit-path');
        const radarBeam = document.getElementById('radar-beam');
        const radarUi = document.getElementById('radar-ui');
        const status = document.getElementById('radar-status');
        const targetHtml = document.getElementById('target-html');
        const outputCss = document.getElementById('output-css');
        const cssSize = document.getElementById('css-size');

        // Reset visual state
        radarBeam.classList.remove('hidden');
        outputCss.classList.remove('opacity-100', 'translate-y-0');
        outputCss.classList.add('opacity-0', 'translate-y-6');
        targetHtml.classList.remove('opacity-100', 'text-white', 'scale-110');
        targetHtml.classList.add('opacity-20', 'text-white/50');
        cssSize.innerText = "0 Bytes";

        // Update Config Text
        if (isCorrect) {
            pathEl.innerText = '"./src/**/*.html"';
            pathEl.className = "text-emerald-700 dark:text-emerald-400 font-bold bg-emerald-100 dark:bg-emerald-900/20 px-1.5 py-0.5 rounded transition-colors";
            status.innerText = "RADAR: MELACAK TARGET LOKASI HTML";
            status.className = "absolute top-6 left-1/2 transform -translate-x-1/2 bg-emerald-900/80 backdrop-blur-md px-5 py-2 rounded-full text-[10px] font-bold text-emerald-300 border border-emerald-500 transition-colors uppercase tracking-widest z-20 shadow-[0_0_15px_rgba(16,185,129,0.3)] animate-pulse";
            radarUi.className = "absolute w-40 h-40 border-2 border-emerald-500/50 rounded-full flex items-center justify-center transition-all duration-500 shadow-[0_0_30px_rgba(16,185,129,0.2)]";
        } else {
            pathEl.innerText = '"" // Area Pemindaian Kosong';
            pathEl.className = "text-rose-700 dark:text-rose-400 font-bold bg-rose-100 dark:bg-rose-900/20 px-1.5 py-0.5 rounded transition-colors";
            status.innerText = "RADAR ERROR: MEMINDAI RUANG HAMPA";
            status.className = "absolute top-6 left-1/2 transform -translate-x-1/2 bg-rose-900/80 backdrop-blur-md px-5 py-2 rounded-full text-[10px] font-bold text-rose-300 border border-rose-500 transition-colors uppercase tracking-widest z-20 shadow-[0_0_15px_rgba(225,29,72,0.3)] animate-pulse";
            radarUi.className = "absolute w-40 h-40 border-2 border-rose-500/50 rounded-full flex items-center justify-center transition-all duration-500 shadow-[0_0_30px_rgba(225,29,72,0.2)]";
        }

        // Simulasi Scan Progress...
        setTimeout(() => {
            radarBeam.classList.add('hidden'); // Matikan putaran radar
            
            if (isCorrect) {
                status.innerHTML = "<svg class='w-4 h-4 inline-block mr-1.5' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 13l4 4L19 7'/></svg> CSS BERHASIL DIRAKIT & DIEKSTRAK";
                status.className = "absolute top-6 left-1/2 transform -translate-x-1/2 bg-emerald-600 backdrop-blur-md px-5 py-2 rounded-full text-[10px] font-black text-white border-2 border-emerald-400 transition-colors uppercase tracking-widest z-20 shadow-[0_10px_20px_rgba(16,185,129,0.5)]";
                
                // Nyalakan target
                targetHtml.classList.remove('opacity-20', 'text-white/50');
                targetHtml.classList.add('opacity-100', 'text-white', 'scale-110');
                
                // Munculkan CSS File Output
                outputCss.classList.remove('opacity-0', 'translate-y-6');
                outputCss.classList.add('opacity-100', 'translate-y-0');
                cssSize.innerText = "4.2 KB (Terisi)";
                cssSize.className = "text-[11px] font-black mt-2.5 bg-emerald-50 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/40 px-3.5 py-1 rounded-lg inline-block transition-all shadow-inner tracking-wider";
            } else {
                status.innerHTML = "<svg class='w-4 h-4 inline-block mr-1.5' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M6 18L18 6M6 6l12 12'/></svg> KOMPILASI GAGAL: 0 CSS DIHASILKAN";
                status.className = "absolute top-6 left-1/2 transform -translate-x-1/2 bg-rose-600 backdrop-blur-md px-5 py-2 rounded-full text-[10px] font-black text-white border-2 border-rose-400 transition-colors uppercase tracking-widest z-20 shadow-[0_10px_20px_rgba(225,29,72,0.5)]";
                
                // CSS Tetap tersembunyi
                outputCss.classList.remove('opacity-0', 'translate-y-6');
                outputCss.classList.add('opacity-100', 'translate-y-0');
                cssSize.innerText = "0 Bytes";
                cssSize.className = "text-[11px] font-black mt-2.5 bg-rose-50 dark:bg-rose-500/20 text-rose-700 dark:text-rose-400 border border-rose-200 dark:border-rose-500/40 px-3.5 py-1 rounded-lg inline-block transition-all shadow-inner tracking-wider";
            }
        }, 2000); // Radar berputar selama 2 detik
    }

    // SIMULATOR 4: THEME ARCHITECTURE
    function setThemeArchitecture(mode) {
        const bgDefault = document.getElementById('default-palette');
        const highlight = document.getElementById('extend-highlight');
        const close = document.getElementById('extend-close');
        const warning = document.getElementById('theme-warning');
        const badgeDefault = document.getElementById('badge-default');

        if(mode === 'extend') {
            highlight.className = "text-emerald-500 dark:text-emerald-400 font-bold bg-emerald-100 dark:bg-emerald-900/30 px-2 py-0.5 rounded transition-colors shadow-sm";
            close.className = "text-emerald-500 dark:text-emerald-400 font-bold bg-emerald-100 dark:bg-emerald-900/30 px-2 py-0.5 rounded transition-colors shadow-sm";
            highlight.innerText = "extend: {";
            close.innerText = "}";
            
            bgDefault.classList.remove('opacity-20', 'grayscale', 'scale-95');
            bgDefault.classList.add('opacity-100', 'scale-100');
            warning.classList.remove('opacity-100', 'translate-y-0');
            warning.classList.add('opacity-0', 'translate-y-4');
            
            if(badgeDefault) {
                badgeDefault.innerText = "Sistem Kelas Aktif Harmonis";
                badgeDefault.className = "text-[9px] font-bold bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400 px-2.5 py-1 rounded uppercase transition-colors shadow-sm tracking-wide";
            }
            
            document.getElementById('btn-theme-extend').className = "flex-1 py-4 px-6 bg-emerald-600 text-white font-bold text-sm rounded-xl shadow-lg focus:outline-none transition-all active:scale-95 flex items-center justify-between border-2 border-emerald-400";
            document.getElementById('btn-theme-override').className = "flex-1 py-4 px-6 bg-slate-200 dark:bg-[#0a0e17] text-slate-600 dark:text-slate-400 font-bold text-sm rounded-xl shadow-sm border border-slate-300 dark:border-white/10 hover:bg-rose-50 hover:text-rose-600 dark:hover:bg-rose-900/20 dark:hover:text-rose-400 transition-all focus:outline-none active:scale-95 flex items-center justify-between group";
        } else {
            highlight.className = "text-rose-500 dark:text-rose-400 font-bold bg-rose-100 dark:bg-rose-900/30 px-2 py-0.5 rounded transition-colors shadow-sm line-through";
            close.className = "text-rose-500 dark:text-rose-400 font-bold bg-rose-100 dark:bg-rose-900/30 px-2 py-0.5 rounded transition-colors shadow-sm line-through";
            highlight.innerText = "extend: {";
            close.innerText = "}";
            
            bgDefault.classList.remove('opacity-100', 'scale-100');
            bgDefault.classList.add('opacity-20', 'grayscale', 'scale-95');
            warning.classList.remove('opacity-0', 'translate-y-4');
            warning.classList.add('opacity-100', 'translate-y-0');

            if(badgeDefault) {
                badgeDefault.innerText = "Error (Terhapus Paksa)";
                badgeDefault.className = "text-[9px] font-bold bg-rose-100 dark:bg-rose-900/40 text-rose-700 dark:text-rose-400 px-2.5 py-1 rounded uppercase transition-colors shadow-sm animate-pulse tracking-wide";
            }

            document.getElementById('btn-theme-extend').className = "flex-1 py-4 px-6 bg-slate-200 dark:bg-[#0a0e17] text-slate-600 dark:text-slate-400 font-bold text-sm rounded-xl shadow-sm border border-slate-300 dark:border-white/10 hover:bg-emerald-50 hover:text-emerald-700 dark:hover:bg-emerald-900/20 dark:hover:text-emerald-400 transition-all focus:outline-none active:scale-95 flex items-center justify-between group";
            document.getElementById('btn-theme-override').className = "flex-1 py-4 px-6 bg-rose-600 text-white font-bold text-sm rounded-xl shadow-lg focus:outline-none transition-all active:scale-95 flex items-center justify-between border-2 border-rose-400";
        }
    }

    function updateConfigPreview() {
        const color = document.getElementById('config-color').value;
        const radius = document.getElementById('config-radius').value;
        const box = document.getElementById('preview-box');
        const codeColor = document.getElementById('code-color');
        const codeRadius = document.getElementById('code-radius');
        const radiusVal = document.getElementById('radius-val');

        if(box) {
            box.style.backgroundColor = color;
            box.style.borderRadius = radius + 'rem';
        }
        
        if(codeColor) codeColor.innerText = color;
        if(codeRadius) codeRadius.innerText = radius + 'rem';
        if(radiusVal) radiusVal.innerText = radius + 'rem';
    }

    // ==========================================
    // 8. LOGIKA AKTIVITAS FINAL (QUIZ SCENARIO)
    // ==========================================
    function checkAnswer(step, isCorrect, btnElement) {
        if(isCorrect) {
            // State: Benar
            btnElement.classList.remove('border-slate-300', 'dark:border-white/10', 'text-slate-700', 'dark:text-white/80', 'hover:bg-slate-50', 'dark:hover:bg-white/5');
            btnElement.classList.add('border-emerald-400', 'bg-emerald-50', 'dark:bg-emerald-900/20', 'text-emerald-800', 'dark:text-emerald-300', 'shadow-[0_5px_15px_rgba(16,185,129,0.15)]');

            // Update Tracker Kiri
            const trackerIcon = document.querySelector(`#tracker-${step} div`);
            trackerIcon.classList.remove('bg-slate-300', 'dark:bg-slate-800');
            trackerIcon.classList.add('bg-emerald-500', 'text-white', 'shadow-[0_0_10px_rgba(16,185,129,0.5)]');
            trackerIcon.innerHTML = '✓';

            // Munculkan Feedback Overlay
            const feedback = document.getElementById('quiz-feedback');
            feedback.innerHTML = `<span class="flex items-center gap-2"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Diagnosis Analisis Sempurna! Sistem mendeteksi logika yang akurat. Melanjutkan proses...</span>`;
            feedback.className = "absolute bottom-10 left-1/2 transform -translate-x-1/2 bg-emerald-600 text-white px-8 py-3.5 rounded-2xl font-black text-xs shadow-[0_10px_30px_rgba(16,185,129,0.4)] translate-y-0 opacity-100 transition-all duration-300 z-20 flex items-center gap-3 tracking-wide uppercase border border-emerald-400";
            
            // Kunci semua tombol pada soal ini agar tidak bisa ditebak ulang
            const allBtns = document.querySelectorAll(`#quiz-step-${step} button`);
            allBtns.forEach(b => b.disabled = true);

            setTimeout(() => {
                feedback.classList.remove('translate-y-0', 'opacity-100');
                feedback.classList.add('translate-y-20', 'opacity-0');
                
                // Pindah Soal
                document.getElementById(`quiz-step-${step}`).classList.add('hidden');
                
                if(step < 4) {
                    document.getElementById(`quiz-step-${step+1}`).classList.remove('hidden');
                    document.getElementById('quiz-header-status').innerText = `INSPEKSI LOG TICKET #${step+1} / 4`;
                } else {
                    // Semua Misi Selesai!
                    finishActivityDB();
                }
            }, 2200);

        } else {
            // State: Salah
            btnElement.classList.remove('border-slate-300', 'dark:border-white/10');
            btnElement.classList.add('border-rose-400', 'bg-rose-50', 'dark:bg-rose-900/20', 'text-rose-800', 'dark:text-rose-300');
            
            btnElement.classList.add('shake');
            setTimeout(() => {
                btnElement.classList.remove('shake');
                // Kembalikan style ke normal setelah beberapa detik agar bisa nebak lagi
                setTimeout(() => {
                    btnElement.classList.remove('border-rose-400', 'bg-rose-50', 'dark:bg-rose-900/20', 'text-rose-800', 'dark:text-rose-300');
                    btnElement.classList.add('border-slate-300', 'dark:border-white/10');
                }, 1000);
            }, 500);

            const feedback = document.getElementById('quiz-feedback');
            feedback.innerHTML = `<span class="flex items-center gap-2"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg> Analisis Ditolak. Ingat kembali materi pondasi arsitekturnya di atas!</span>`;
            feedback.className = "absolute bottom-10 left-1/2 transform -translate-x-1/2 bg-rose-600 text-white px-8 py-3.5 rounded-2xl font-black text-xs shadow-[0_10px_30px_rgba(225,29,72,0.4)] translate-y-0 opacity-100 transition-all duration-300 z-20 flex items-center gap-3 tracking-wide uppercase border border-rose-400";
            
            setTimeout(() => {
                feedback.classList.remove('translate-y-0', 'opacity-100');
                feedback.classList.add('translate-y-20', 'opacity-0');
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
            console.error(e); 
            alert('Koneksi terputus saat merekam data log penyimpanan ke server. Mohon muat ulang halaman browser.');
        }
    }

    function lockActivityUI() {
        const overlay = document.getElementById('activityOverlay');
        if(overlay) {
            overlay.classList.remove('hidden');
            overlay.style.display = 'flex';
        }
        
        // Paksakan semua indikator tracker di kiri menjadi centang hijau cerah
        for(let i=1; i<=4; i++) {
            const trackerIcon = document.querySelector(`#tracker-${i} div`);
            if(trackerIcon) {
                trackerIcon.className = 'w-6 h-6 rounded-lg bg-emerald-500 text-white flex items-center justify-center font-bold text-[12px] shadow-[0_0_15px_rgba(16,185,129,0.5)] transition-all';
                trackerIcon.innerHTML = '✓';
            }
        }
    }

    /* --- GLOBAL VISUAL EFFECTS --- */
    function initVisualEffects(){const c=document.getElementById('stars'),x=c.getContext('2d');function r(){c.width=innerWidth;c.height=innerHeight}r();window.addEventListener('resize',r);let s=[];for(let i=0;i<100;i++)s.push({x:Math.random()*c.width,y:Math.random()*c.height,r:Math.random()*1.2,v:Math.random()*0.2+.1});(function a(){x.clearRect(0,0,c.width,c.height);x.fillStyle='rgba(255,255,255,.3)';s.forEach(t=>{x.beginPath();x.arc(t.x,t.y,t.r,0,6.28);x.fill();t.y+=t.v;if(t.y>c.height)t.y=0});requestAnimationFrame(a)})();}
</script>
@endsection