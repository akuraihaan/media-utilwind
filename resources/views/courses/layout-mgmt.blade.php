@extends('layouts.landing')
@section('title', 'Layouting Tailwind CSS')

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
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;700&display=swap');

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
        --accent: #6366f1;
        --accent-glow: rgba(99, 102, 241, 0.3);
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
        --accent-glow: rgba(99, 102, 241, 0.5);
    }

    body { 
        font-family: 'Inter', sans-serif; 
        background-color: var(--bg-main); 
        color: var(--text-main); 
        transition: background-color 0.4s ease, color 0.4s ease; 
        -webkit-font-smoothing: antialiased;
    }
    .font-mono { font-family: 'JetBrains Mono', monospace; }

    /* UTILITIES ADAPTIF */
    .bg-adaptive { background-color: var(--bg-main); transition: background-color 0.4s ease; }
    .text-adaptive { color: var(--text-main); transition: color 0.4s ease; }
    .text-heading { color: var(--text-heading); transition: color 0.4s ease; }
    .text-muted { color: var(--text-muted); transition: color 0.4s ease; }
    .border-adaptive { border-color: var(--border-color); transition: border-color 0.4s ease; }
    .card-adaptive { background-color: var(--card-bg); border-color: var(--glass-border); transition: border-color 0.4s ease, background-color 0.4s ease; }
    .card-adaptive:hover { border-color: var(--accent-glow); }
    .sim-bg-adaptive { background-color: var(--simulator-bg); transition: background-color 0.4s ease; }
    .code-adaptive { background-color: var(--code-bg); border-color: var(--glass-border); transition: border-color 0.4s ease, background-color 0.4s ease; }

    /* SCROLLBAR */
    .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(150,150,150,0.3); border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: var(--accent); }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }
    
    /* ANIMATIONS & 3D EFFECTS */
    #animated-bg { 
        background: radial-gradient(800px circle at 20% 20%, rgba(99,102,241,.08), transparent 40%), 
                    radial-gradient(800px circle at 80% 80%, rgba(139,92,246,.08), transparent 40%); 
        animation: bgMove 20s ease-in-out infinite alternate; 
    }
    .dark #animated-bg {
        background: radial-gradient(800px circle at 20% 20%, rgba(99,102,241,.15), transparent 40%), 
                    radial-gradient(800px circle at 80% 80%, rgba(139,92,246,.15), transparent 40%); 
    }
    @keyframes bgMove{to{transform:scale(1.15)}}
    @keyframes shake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-5px); } 75% { transform: translateX(5px); } }
    .shake { animation: shake 0.4s ease-in-out; }
    .perspective-1000 { perspective: 1000px; }
    .transform-style-3d { transform-style: preserve-3d; }
    
    /* MOBILE SIDEBAR NO-BLUR FIX */
    @media (max-width: 1023px) {
        #courseSidebar {
            position: fixed;
            top: 64px;
            left: -100%;
            height: calc(100vh - 64px);
            transition: left 0.3s ease-in-out;
            z-index: 40;
        }
        #courseSidebar.mobile-open { left: 0; box-shadow: 10px 0 30px rgba(0,0,0,0.5); }
        #mobileOverlay { display: none; position: fixed; inset: 0; top: 64px; background: rgba(0,0,0,0.6); z-index: 30; }
        #mobileOverlay.show { display: block; }
    }
    
    /* SCROLLSPY SIDEBAR ACTIVE */
    .nav-item { display: flex; width: 100%; text-align: left; align-items: center; gap: 12px; padding: 10px 14px; font-size: 0.85rem; color: var(--text-muted); border-radius: 8px; transition: all 0.2s; position: relative; border-left-width: 2px; border-color: transparent; }
    .nav-item:hover { color: var(--text-main); background: var(--card-hover); }
    .nav-item.active { color: #6366f1; background: rgba(99, 102, 241, 0.05); font-weight: 600; }
    .dark .nav-item.active { color: #818cf8; background: rgba(129, 140, 248, 0.1); }
    .dot { width: 6px; height: 6px; border-radius: 50%; background: #94a3b8; transition: all 0.3s; }
    .dark .dot { background: #334155; }
    .nav-item.active .dot { background: #6366f1; box-shadow: 0 0 8px #6366f1; transform: scale(1.2); }
    .dark .nav-item.active .dot { background: #818cf8; box-shadow: 0 0 8px #818cf8; }
</style>

<button id="mobileSidebarToggle" class="lg:hidden fixed bottom-6 right-6 z-50 p-4 rounded-full shadow-2xl bg-indigo-600 text-white hover:bg-indigo-500 focus:outline-none transition-transform hover:scale-110">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
    </svg>
</button>

<div id="mobileOverlay" onclick="toggleMobileSidebar()"></div>

<div id="courseRoot" class="relative h-screen bg-slate-50 dark:bg-[#020617] text-slate-800 dark:text-white font-sans overflow-hidden flex flex-col selection:bg-indigo-500/30 pt-20 transition-colors duration-500">

    {{-- BACKGROUND LAYER --}}
    <div class="fixed inset-0 -z-50 pointer-events-none">
        <div id="animated-bg" class="absolute inset-0 opacity-50 transition-opacity duration-500"></div>
        <div class="absolute top-[-10%] right-[-10%] w-[600px] h-[600px] bg-indigo-300/30 dark:bg-indigo-900/10 rounded-full blur-[120px] animate-pulse transition-colors duration-500"></div>
        <div class="absolute bottom-[-10%] left-[-10%] w-[500px] h-[500px] bg-violet-300/30 dark:bg-violet-900/10 rounded-full blur-[100px] transition-colors duration-500"></div>
        <canvas id="stars" class="absolute inset-0 pointer-events-none opacity-0 dark:opacity-100 transition-opacity duration-500"></canvas>
    </div>

    @include('layouts.partials.navbar')

    <div class="flex flex-1 overflow-hidden relative z-20">
        @include('layouts.partials.course-sidebar')

        <main id="mainScroll" class="flex-1 h-full overflow-y-auto scroll-smooth relative bg-transparent custom-scrollbar scroll-padding-top-24">
            
            {{-- STICKY HEADER --}}
            <div id="stickyHeader" class="sticky top-0 z-30 w-full bg-white/80 dark:bg-[#020617]/80 backdrop-blur-2xl border-b border-slate-200 dark:border-white/5 px-4 md:px-8 py-4 flex items-center justify-between transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-gradient-to-br dark:from-indigo-500/20 dark:to-transparent border border-indigo-200 dark:border-indigo-500/20 flex items-center justify-center font-bold text-xs text-indigo-600 dark:text-indigo-400 shrink-0 transition-colors">2.3</div>
                    <div>
                        <h1 class="text-sm font-bold text-slate-900 dark:text-white line-clamp-1 transition-colors">Arsitektur Layout Responsif</h1>
                        <p class="text-[10px] text-slate-500 dark:text-white/50 line-clamp-1 transition-colors">Penguasaan Dimensi, Posisi & Z-Index</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <div class="hidden sm:block w-24 md:w-32 h-1.5 bg-slate-200 dark:bg-white/10 rounded-full overflow-hidden transition-colors">
                        <div id="topProgressBar" class="h-full bg-gradient-to-r from-indigo-500 to-purple-500 dark:from-indigo-500 dark:to-purple-500 w-0 transition-all duration-500 shadow-[0_0_10px_#6366f1]"></div>
                    </div>
                    <span id="progressLabelTop" class="text-indigo-600 dark:text-indigo-400 font-bold text-xs transition-colors">0%</span>
                </div>
            </div>

            {{-- CONTENT WRAPPER --}}
            <div class="p-6 lg:p-16 max-w-5xl mx-auto pb-40">
                
               {{-- LEARNING OBJECTIVES --}}
                <div class="mb-24 animate-fade-in-up">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-2 transition-colors">
                        <svg class="w-5 h-5 text-indigo-500 dark:text-indigo-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Target Pembelajaran
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                        
                        <div class="bg-white dark:bg-[#1e1e1e] border border-slate-200 dark:border-white/5 p-5 rounded-xl flex items-start gap-4 hover:border-indigo-400 dark:hover:border-indigo-500/30 shadow-sm dark:shadow-none transition group h-full">
                            <div class="w-8 h-8 rounded bg-indigo-100 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0 font-bold text-xs transition-colors shadow-inner">1</div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800 dark:text-white mb-1 transition-colors">Fungsi Container</h4>
                                <p class="text-[11px] text-slate-500 dark:text-white/50 leading-relaxed transition-colors">Memahami cara kerja container untuk menahan lebar halaman agar tetap rapi dan tidak terlalu melebar di layar besar.</p>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-[#1e1e1e] border border-slate-200 dark:border-white/5 p-5 rounded-xl flex items-start gap-4 hover:border-orange-400 dark:hover:border-orange-500/30 shadow-sm dark:shadow-none transition group h-full">
                            <div class="w-8 h-8 rounded bg-orange-100 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400 flex items-center justify-center shrink-0 font-bold text-xs transition-colors shadow-inner">2</div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800 dark:text-white mb-1 transition-colors">Solusi Clearfix</h4>
                                <p class="text-[11px] text-slate-500 dark:text-white/50 leading-relaxed transition-colors">Menggunakan utilitas flow-root untuk memperbaiki wadah induk yang rusak atau kempis akibat elemen float.</p>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-[#1e1e1e] border border-slate-200 dark:border-white/5 p-5 rounded-xl flex items-start gap-4 hover:border-blue-400 dark:hover:border-blue-500/30 shadow-sm dark:shadow-none transition group h-full">
                            <div class="w-8 h-8 rounded bg-blue-100 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0 font-bold text-xs transition-colors shadow-inner">3</div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800 dark:text-white mb-1 transition-colors">Posisi & Z-Index</h4>
                                <p class="text-[11px] text-slate-500 dark:text-white/50 leading-relaxed transition-colors">Mengatur letak elemen (absolute, fixed) dan tumpukannya (z-index) untuk membuat komponen melayang seperti navbar.</p>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-[#1e1e1e] border border-slate-200 dark:border-white/5 p-5 rounded-xl flex items-start gap-4 hover:border-pink-400 dark:hover:border-pink-500/30 shadow-sm dark:shadow-none transition group h-full">
                            <div class="w-8 h-8 rounded bg-pink-100 dark:bg-pink-500/10 text-pink-600 dark:text-pink-400 flex items-center justify-center shrink-0 font-bold text-xs transition-colors shadow-inner">4</div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800 dark:text-white mb-1 transition-colors">Layout Tabel</h4>
                                <p class="text-[11px] text-slate-500 dark:text-white/50 leading-relaxed transition-colors">Membedakan penggunaan tabel auto dan fixed agar ukuran antarmuka tidak berubah tiba-tiba saat memuat data panjang.</p>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-indigo-50 to-violet-50 dark:from-indigo-900/40 dark:to-violet-900/40 border border-indigo-200 dark:border-indigo-500/30 p-5 rounded-xl flex items-start gap-4 hover:shadow-md dark:hover:shadow-[0_0_20px_rgba(99,102,241,0.2)] transition group h-full col-span-1 sm:col-span-2 md:col-span-4 cursor-pointer" onclick="document.getElementById('section-45').scrollIntoView({ behavior: 'smooth', block: 'start' });">
                            <div class="w-8 h-8 rounded bg-indigo-100 dark:bg-white/10 text-indigo-600 dark:text-white flex items-center justify-center shrink-0 font-bold text-xs transition-colors shadow-inner border border-indigo-300 dark:border-white/20">🏁</div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800 dark:text-white mb-1 transition-colors">Final Mission</h4>
                                <p class="text-[11px] text-slate-600 dark:text-white/70 leading-relaxed transition-colors">Memahami integrasi tumpukan sumbu 3D dengan mengkombinasikan paku bumi navigasi permanen (fixed navbar) berserta lencana absolut yang diikat oleh wadah referensi.</p>
                            </div>
                        </div>

                    </div>
                </div>

                <article class="space-y-40">
                    
                    {{-- LESSON 41: CONTAINER & VIEWPORT --}}
                    <section id="section-41" class="lesson-section scroll-mt-32" data-lesson-id="41">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-indigo-500 pl-6">
                                <span class="text-indigo-600 dark:text-indigo-400 font-mono text-xs uppercase tracking-widest font-bold transition-colors">Lesson 2.3.1</span>
                                <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-slate-900 dark:text-white leading-[1.1] transition-colors">
                                    Container & <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-purple-600 dark:from-indigo-400 dark:to-purple-500">Logika Viewport</span>
                                </h2>
                            </div>

                            <div class="grid md:grid-cols-2 gap-4">
                                <div class="bg-white dark:bg-[#111112] border border-slate-200 dark:border-white/10 p-5 rounded-xl shadow-sm transition-colors">
                                    <h4 class="text-indigo-700 dark:text-indigo-400 font-bold mb-2 flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                        Viewport (Layar Fisik)
                                    </h4>
                                    <p class="text-sm m-0 text-slate-600 dark:text-white/60">Area fisik perangkat yang digunakan pengguna untuk melihat situs Anda. Ini adalah bingkai nyata dari alat yang dipakai (layar HP kecil atau monitor lebar).</p>
                                </div>
                                <div class="bg-white dark:bg-[#111112] border border-slate-200 dark:border-white/10 p-5 rounded-xl shadow-sm transition-colors">
                                    <h4 class="text-purple-700 dark:text-purple-400 font-bold mb-2 flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path></svg>
                                        Container (Wadah Kode)
                                    </h4>
                                    <p class="text-sm m-0 text-slate-600 dark:text-white/60">Kotak transparan terluar dalam kode HTML. Fungsinya menahan komponen antarmuka agar tidak tumpah dan memanjang jelek saat dibuka di monitor besar.</p>
                                </div>
                            </div>
                            
                            <div class="space-y-6 mt-8">
                                <h3 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2 transition-colors"><span class="w-6 h-6 rounded bg-slate-200 dark:bg-white/10 border border-slate-300 dark:border-white/20 flex items-center justify-center text-[10px] text-slate-600 dark:text-white transition-colors shadow-sm">A</span> Mengapa Tampilan Mobile Sering Rusak?</h3>
                                
                                <div class="grid md:grid-cols-2 gap-4">
                                    <div class="bg-rose-50 dark:bg-rose-500/5 border-l-4 border-rose-400 p-5 rounded-r-xl flex flex-col gap-2 transition-colors">
                                        <h4 class="text-rose-700 dark:text-rose-400 font-bold m-0 flex items-center gap-2 text-sm uppercase tracking-wide">
                                            Ilusi Browser Bawaan
                                        </h4>
                                        <p class="text-sm text-rose-800/90 dark:text-rose-100/70 m-0 leading-relaxed transition-colors">Tanpa instruksi yang jelas, browser di HP akan mengira situs Anda adalah web desktop jadul. Ia otomatis melakukan Zoom Out paksa agar seluruh halaman masuk ke layar kecil. Teks pun menyusut dan mustahil dibaca tanpa zoom manual.</p>
                                    </div>
                                    <div class="bg-emerald-50 dark:bg-emerald-500/5 border-l-4 border-emerald-400 p-5 rounded-r-xl flex flex-col gap-2 transition-colors">
                                        <h4 class="text-emerald-700 dark:text-emerald-400 font-bold m-0 flex items-center gap-2 text-sm uppercase tracking-wide">
                                            Solusi Tag Resolusi
                                        </h4>
                                        <code class="text-[10px] sm:text-[11px] text-emerald-800 dark:text-emerald-300 bg-emerald-200/50 dark:bg-black/40 p-2 rounded shadow-inner transition-colors font-bold block truncate">&lt;meta name="viewport" content="width=device..."&gt;</code>
                                        <p class="text-sm text-emerald-800/90 dark:text-emerald-100/70 m-0 leading-relaxed transition-colors mt-1">Kode wajib ini memaksa rasio tampilan menjadi 1:1. Ini adalah nyawa yang menyinkronkan desain web responsif dengan dimensi layar fisik yang sebenarnya.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-6">
                                <h3 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2 transition-colors"><span class="w-6 h-6 rounded bg-slate-200 dark:bg-white/10 border border-slate-300 dark:border-white/20 flex items-center justify-center text-[10px] text-slate-600 dark:text-white transition-colors shadow-sm">B</span> Aturan Dasar Kelas Container Tailwind</h3>
                                
                                <div class="bg-indigo-50 dark:bg-indigo-900/10 border border-indigo-200 dark:border-indigo-500/20 p-5 rounded-xl text-sm md:text-base text-indigo-900 dark:text-indigo-100/80 leading-relaxed transition-colors shadow-sm">
                                    <span class="flex items-center gap-2 font-bold mb-2 text-indigo-700 dark:text-indigo-400"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Catatan Penting</span>
                                    Utilitas <code class="font-bold">.container</code> di Tailwind secara bawaan tidak akan meratakan posisi ke tengah dan tidak memiliki bantalan dalam (padding). Fungsinya murni hanya untuk menyetel batas ekspansi layar (max-width).
                                </div>

                                <div class="grid sm:grid-cols-3 gap-4">
                                    <div class="bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 p-5 rounded-xl flex flex-col items-center text-center gap-3 transition-colors shadow-sm">
                                        <code class="text-indigo-700 dark:text-indigo-400 font-bold bg-indigo-100 dark:bg-indigo-500/10 px-3 py-1.5 rounded w-full shadow-inner border border-indigo-200 dark:border-indigo-500/20 tracking-wide transition-colors">container</code>
                                        <span class="text-xs text-slate-600 dark:text-white/60 leading-relaxed transition-colors font-medium">Pengunci laju pelebaran dimensi maksimum.</span>
                                    </div>
                                    <div class="bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 p-5 rounded-xl flex flex-col items-center text-center gap-3 transition-colors shadow-sm">
                                        <code class="text-indigo-700 dark:text-indigo-400 font-bold bg-indigo-100 dark:bg-indigo-500/10 px-3 py-1.5 rounded w-full shadow-inner border border-indigo-200 dark:border-indigo-500/20 tracking-wide transition-colors">mx-auto</code>
                                        <span class="text-xs text-slate-600 dark:text-white/60 leading-relaxed transition-colors font-medium">Pembagi otomatis sisa ruang kiri dan kanan (Rata Tengah).</span>
                                    </div>
                                    <div class="bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 p-5 rounded-xl flex flex-col items-center text-center gap-3 transition-colors shadow-sm">
                                        <code class="text-indigo-700 dark:text-indigo-400 font-bold bg-indigo-100 dark:bg-indigo-500/10 px-3 py-1.5 rounded w-full shadow-inner border border-indigo-200 dark:border-indigo-500/20 tracking-wide transition-colors">px-4</code>
                                        <span class="text-xs text-slate-600 dark:text-white/60 leading-relaxed transition-colors font-medium">Peredam spasi agar teks tak menabrak pinggiran layar HP.</span>
                                    </div>
                                </div>
                            </div>

                            {{-- SIMULATOR 1 --}}
                            <div class="bg-white dark:bg-[#0b0f19] border border-slate-200 dark:border-white/10 rounded-2xl p-6 lg:p-8 shadow-xl dark:shadow-2xl relative group hover:border-indigo-400 dark:hover:border-indigo-500/30 transition-all mt-8">
                                <h4 class="text-xs font-bold text-slate-400 dark:text-muted uppercase mb-4 text-center transition-colors tracking-widest">Simulator Logis: Koreografi Batas Layar</h4>
                                
                                <div class="bg-indigo-50 dark:bg-indigo-500/10 border border-indigo-200 dark:border-indigo-500/30 rounded-xl p-4 mb-6 text-sm text-indigo-800 dark:text-indigo-300 relative z-10 shadow-sm dark:shadow-inner transition-colors">
                                    <p class="font-bold flex items-center gap-2 mb-2">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Uji Coba Interaktif
                                    </p>
                                    <p class="m-0 opacity-90 leading-relaxed text-xs md:text-sm text-justify transition-colors font-medium">
                                        Tekan sakelar perangkat di bawah. Perhatikan garis merah tebal (<span class="font-bold text-indigo-700 dark:text-indigo-300">px-4</span>) dan amati bagaimana <code class="font-bold">container</code> memblokir pelebaran layar Desktop, namun hanya akan rata tengah jika Anda mengaktifkan <span class="font-bold text-indigo-700 dark:text-indigo-300">mx-auto</span>.
                                    </p>
                                </div>

                                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6 gap-4 relative z-10">
                                    <div class="flex gap-2 bg-slate-100 dark:bg-black/40 p-1.5 rounded-lg border border-slate-200 dark:border-white/10 shadow-inner w-full lg:w-auto overflow-x-auto custom-scrollbar transition-colors">
                                        <button onclick="simContainer('w-full', this)" class="res-btn px-4 py-2 text-[10px] sm:text-xs font-bold rounded bg-indigo-600 text-white shadow-lg border border-indigo-400 transition shrink-0 focus:outline-none">Mobile (100%)</button>
                                        <button onclick="simContainer('max-w-md', this)" class="res-btn px-4 py-2 text-[10px] sm:text-xs font-bold rounded bg-slate-200 dark:bg-transparent text-slate-600 dark:text-white/50 border border-transparent transition shrink-0 focus:outline-none hover:bg-slate-300 dark:hover:bg-white/5">Tablet (md)</button>
                                        <button onclick="simContainer('max-w-2xl', this)" class="res-btn px-4 py-2 text-[10px] sm:text-xs font-bold rounded bg-slate-200 dark:bg-transparent text-slate-600 dark:text-white/50 border border-transparent transition shrink-0 focus:outline-none hover:bg-slate-300 dark:hover:bg-white/5">Desktop (xl)</button>
                                    </div>
                                    <div class="flex gap-2 w-full lg:w-auto bg-slate-100 dark:bg-black/40 p-1.5 rounded-lg border border-slate-200 dark:border-white/10 shadow-inner transition-colors">
                                        <button onclick="toggleSimClass('mx-auto', this)" class="flex-1 lg:flex-none px-4 py-2 text-[10px] sm:text-xs font-bold rounded bg-indigo-600 text-white border border-indigo-400 shadow-[0_0_10px_rgba(79,70,229,0.4)] transition focus:outline-none">mx-auto: ON</button>
                                        <button onclick="toggleSimClass('px-4', this)" class="flex-1 lg:flex-none px-4 py-2 text-[10px] sm:text-xs font-bold rounded bg-indigo-600 text-white border border-indigo-400 shadow-[0_0_10px_rgba(79,70,229,0.4)] transition focus:outline-none">px-4: ON</button>
                                    </div>
                                </div>
                                
                                <div class="w-full bg-slate-100 dark:bg-black/50 h-48 sm:h-64 rounded-xl border border-slate-300 dark:border-white/10 relative flex items-center justify-center overflow-hidden shadow-inner p-2 transition-colors z-10">
                                    <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-10 pointer-events-none"></div>
                                    
                                    <div id="sim1-target" class="w-full h-32 sm:h-40 bg-gradient-to-b from-indigo-100 to-indigo-50 dark:from-indigo-500/20 dark:to-indigo-600/10 border-2 border-indigo-300 dark:border-indigo-400/50 rounded-xl flex flex-col items-center justify-center transition-all duration-700 ease-in-out mx-auto px-4 relative shadow-sm dark:shadow-[0_0_40px_rgba(99,102,241,0.2)] backdrop-blur-sm">
                                        
                                        <div id="sim1-pad-l" class="absolute top-0 bottom-0 left-0 w-4 bg-red-100 dark:bg-red-500/30 border-r border-dashed border-red-300 dark:border-red-400 flex items-center justify-center transition-opacity"><span class="text-[8px] -rotate-90 text-red-600 dark:text-white font-bold tracking-widest hidden sm:block transition-colors">px-4</span></div>
                                        <div id="sim1-pad-r" class="absolute top-0 bottom-0 right-0 w-4 bg-red-100 dark:bg-red-500/30 border-l border-dashed border-red-300 dark:border-red-400 flex items-center justify-center transition-opacity"><span class="text-[8px] rotate-90 text-red-600 dark:text-white font-bold tracking-widest hidden sm:block transition-colors">px-4</span></div>
                                        
                                        <div class="bg-indigo-600 dark:bg-indigo-900/80 w-full h-16 sm:h-20 rounded border border-indigo-400 dark:border-indigo-500/50 flex flex-col items-center justify-center shadow-lg relative z-10 transition-colors">
                                            <span class="text-white dark:text-indigo-200 font-black text-sm sm:text-lg tracking-widest transition-colors drop-shadow-md">KONTEN SITUS</span>
                                        </div>
                                        <span class="text-indigo-500/80 dark:text-indigo-300/80 font-mono text-[10px] absolute bottom-2 font-bold text-center" id="sim1-width-indicator">100% Lebar Layar Bebas Meregang</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 42: FLOAT & CLEAR --}}
                    <section id="section-42" class="lesson-section scroll-mt-32" data-lesson-id="42">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-orange-500 pl-6">
                                <span class="text-orange-600 dark:text-orange-400 font-mono text-xs uppercase tracking-widest transition-colors">Lesson 2.3.2</span>
                                <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-slate-900 dark:text-white leading-[1.1] transition-colors">
                                    Legacy Layout: <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-red-600 dark:from-orange-400 dark:to-red-500">Float & Clear</span>
                                </h2>
                            </div>

                            <div class="bg-white dark:bg-[#111112] border border-slate-200 dark:border-white/10 p-6 rounded-xl shadow-sm text-justify text-sm sm:text-base text-slate-600 dark:text-white/70 leading-relaxed font-medium transition-colors">
                                <p class="mb-4">Dalam peradaban awal pengembangan web, utilitas <code class="bg-slate-100 dark:bg-white/10 px-1.5 py-0.5 rounded text-orange-600 dark:text-orange-400">float</code> dieksploitasi sebagai trik untuk menyusun layout kolom. Praktik tersebut kini telah usang dalam arsitektur modern, digantikan oleh fondasi stabil seperti <strong>Flexbox</strong> dan <strong>Grid</strong>.</p>
                                <p class="m-0">Kini float dikembalikan ke satu tujuan aslinya: <strong>Membungkus teks di sekitar gambar agar menyatu melingkar</strong>. Namun teknik ini memicu cacat struktural yang merusak hitungan tinggi pada wadah pembungkus utama.</p>
                            </div>

                            <div class="grid md:grid-cols-2 gap-6">
                                <div class="bg-rose-50 dark:bg-rose-500/5 border-l-4 border-rose-400 p-5 flex flex-col gap-3 transition-colors rounded-r-xl">
                                    <h4 class="font-bold text-rose-800 dark:text-rose-400 text-base flex items-center gap-2 m-0 uppercase tracking-wide">
                                        Anomali Wadah Kempis
                                    </h4>
                                    <p class="text-sm text-rose-800/80 dark:text-rose-100/70 leading-relaxed text-justify m-0 transition-colors">
                                        Saat gambar diberi instruksi mengambang (float), elemen induk di atasnya tidak lagi menghitung dimensi gambar tersebut. Wadah induk menjadi buta dimensi. Bila teks tak cukup panjang, wadah akan mengerut kempis (Collapsed Container), membiarkan gambar merobek tembus keluar batas.
                                    </p>
                                </div>
                                
                                <div class="bg-emerald-50 dark:bg-emerald-500/5 border-l-4 border-emerald-400 p-5 flex flex-col gap-3 transition-colors rounded-r-xl">
                                    <h4 class="font-bold text-emerald-800 dark:text-emerald-400 text-base flex items-center gap-2 m-0 uppercase tracking-wide">
                                        Solusi Modern BFC
                                    </h4>
                                    <p class="text-sm text-emerald-800/80 dark:text-emerald-100/70 leading-relaxed text-justify m-0 transition-colors">
                                        Terapkan <code class="text-emerald-700 dark:text-emerald-300 font-bold bg-emerald-200/50 dark:bg-emerald-900/40 px-1.5 py-0.5 rounded shadow-sm">flow-root</code> pada blok induk. Direktif baru ini mengaktifkan Block Formatting Context secara utuh. Wadah secara otomatis terkunci rapat dan diwajibkan kembali menelan seluruh ukuran gambar yang mengambang di dalamnya.
                                    </p>
                                </div>
                            </div>

                            {{-- SIMULATOR 2 --}}
                            <div class="bg-white dark:bg-[#0b0f19] border border-slate-200 dark:border-white/10 rounded-2xl p-6 lg:p-8 shadow-xl dark:shadow-2xl relative group hover:border-orange-400 dark:hover:border-orange-500/30 transition-all flex flex-col mt-8">
                                <h4 class="text-xs font-bold text-slate-400 dark:text-muted uppercase mb-4 text-center transition-colors tracking-widest">Simulator Logis: Keruntuhan & Penyembuhan</h4>
                                
                                <div class="bg-orange-50 dark:bg-orange-500/10 border border-orange-200 dark:border-orange-500/30 rounded-xl p-4 mb-6 text-sm text-orange-700 dark:text-orange-300 relative z-10 shadow-sm dark:shadow-inner transition-colors">
                                    <p class="font-bold flex items-center gap-2 mb-2">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                        Uji Coba Keruntuhan
                                    </p>
                                    <p class="m-0 opacity-90 leading-relaxed text-[11px] sm:text-xs text-justify transition-colors font-medium">
                                        Ubah volume Teks menjadi "Sedikit", lalu matikan sakelar proteksi Clearfix. Amati garis putus-putus merah (Wadah Induk) yang hancur kempis. Setelah itu, nyalakan sakelar <span class="font-bold text-emerald-600 dark:text-emerald-400">flow-root</span> untuk merekonstruksi kerusakan dimensinya.
                                    </p>
                                </div>

                                <div class="flex justify-between items-end mb-4 relative z-10">
                                    <div class="flex gap-1.5 bg-slate-100 dark:bg-black/40 p-1.5 rounded-lg border border-slate-200 dark:border-white/10 shadow-inner overflow-x-auto custom-scrollbar w-full sm:w-auto transition-colors">
                                        <button onclick="simFloat('left', this)" class="float-btn px-3 py-1.5 bg-gradient-to-b from-orange-500 to-orange-600 text-white border-orange-400 shadow-[0_0_10px_rgba(249,115,22,0.3)] font-bold text-[10px] sm:text-xs rounded transition focus:outline-none shrink-0">float-left</button>
                                        <button onclick="simFloat('right', this)" class="float-btn px-3 py-1.5 bg-slate-200 dark:bg-transparent text-slate-600 dark:text-white/50 border-transparent font-bold text-[10px] sm:text-xs rounded border hover:bg-slate-300 dark:hover:bg-white/10 transition focus:outline-none shrink-0">float-right</button>
                                        
                                        <div class="w-px bg-slate-300 dark:bg-white/10 mx-1 transition-colors"></div>
                                        
                                        <button onclick="simText('short', this)" class="txt-btn px-3 py-1.5 bg-slate-300 dark:bg-white/10 text-slate-800 dark:text-white border-transparent dark:border-white/10 font-bold text-[10px] sm:text-xs rounded border transition focus:outline-none shrink-0">Teks Sedikit</button>
                                        <button onclick="simText('long', this)" class="txt-btn px-3 py-1.5 bg-slate-200 dark:bg-transparent text-slate-600 dark:text-white/50 border-transparent dark:border-white/10 font-bold text-[10px] sm:text-xs rounded border hover:bg-slate-300 dark:hover:bg-white/10 transition focus:outline-none shrink-0">Teks Banyak</button>
                                    </div>
                                    <div class="hidden sm:flex gap-1.5 bg-slate-100 dark:bg-black/40 p-1.5 rounded-lg border border-slate-200 dark:border-white/10 shadow-inner shrink-0 transition-colors">
                                        <button onclick="simClearfix(false, this)" class="clr-btn px-3 py-1.5 bg-red-600 text-white font-bold text-[10px] sm:text-xs rounded border border-red-400 shadow-[0_0_10px_rgba(220,38,38,0.4)] transition focus:outline-none shrink-0">No Clearfix </button>
                                        <button onclick="simClearfix(true, this)" class="clr-btn px-3 py-1.5 bg-slate-200 dark:bg-transparent text-slate-600 dark:text-white/50 border border-transparent dark:border-white/10 font-bold text-[10px] sm:text-xs rounded hover:bg-slate-300 dark:hover:bg-white/10 transition focus:outline-none shrink-0">flow-root</button>
                                    </div>
                                </div>
                                <div class="flex sm:hidden gap-1.5 bg-slate-100 dark:bg-black/40 p-1.5 rounded-lg border border-slate-200 dark:border-white/10 shadow-inner shrink-0 mb-4 overflow-x-auto custom-scrollbar w-full transition-colors relative z-10">
                                    <button onclick="simClearfix(false, this)" class="clr-btn px-3 py-1.5 bg-red-600 text-white font-bold text-[10px] sm:text-xs rounded border border-red-400 shadow-[0_0_10px_rgba(220,38,38,0.4)] transition focus:outline-none shrink-0 w-1/2">No Clearfix</button>
                                    <button onclick="simClearfix(true, this)" class="clr-btn px-3 py-1.5 bg-slate-200 dark:bg-transparent text-slate-600 dark:text-white/50 border border-transparent dark:border-white/10 font-bold text-[10px] sm:text-xs rounded hover:bg-slate-300 dark:hover:bg-white/10 transition focus:outline-none shrink-0 w-1/2">flow-root</button>
                                </div>
                                
                                <div class="flex-1 bg-slate-50 dark:bg-gradient-to-b dark:from-[#1c1c1e] dark:to-[#111112] p-5 sm:p-8 rounded-xl border border-slate-200 dark:border-white/5 font-serif text-slate-600 dark:text-white/60 leading-relaxed text-sm sm:text-base text-justify relative shadow-inner transition-colors z-10">
                                    <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.05] dark:opacity-5 pointer-events-none transition-opacity"></div>
                                    
                                    {{-- Wrapper Box --}}
                                    <div id="float-parent" class="border-2 border-dashed border-red-400 dark:border-red-500/80 p-3 sm:p-4 rounded-lg transition-all duration-700 bg-red-50 dark:bg-red-500/5 relative min-h-[140px]">
                                        <div class="absolute -top-3 left-4 bg-slate-50 dark:bg-[#1c1c1e] px-2 text-[9px] sm:text-[10px] font-mono font-bold text-red-500 dark:text-red-400 tracking-widest transition-colors shadow-sm rounded border border-red-200 dark:border-red-500/30" id="parent-label">WADAH INDUK KEMPIS!</div>
                                        
                                        <div id="float-img" class="w-20 h-20 sm:w-28 sm:h-28 bg-gradient-to-br from-orange-400 to-red-600 rounded-2xl mb-2 float-left mr-6 flex items-center justify-center text-white font-black text-sm transition-all duration-500 shadow-[0_15px_30px_rgba(239,68,68,0.4)] border-2 border-white/30 transform hover:-rotate-3 hover:scale-105 cursor-default mt-1">IMG</div>
                                        
                                        <div id="float-txt" class="transition-all duration-300">
                                            <span class="text-slate-900 dark:text-white font-black text-3xl float-left mr-1.5 leading-none mt-1 transition-colors drop-shadow-sm font-sans">P</span>aragraf pendek ini mendemonstrasikan kecacatan layout kuno. Jika teks pendek, wadah induk otomatis akan kolaps dan gagal menyelimuti gambar.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 43: POSITION & Z-INDEX --}}
                    <section id="section-43" class="lesson-section scroll-mt-32" data-lesson-id="43">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-blue-500 pl-6">
                                <span class="text-blue-600 dark:text-blue-400 font-mono text-xs uppercase tracking-widest transition-colors">Lesson 2.3.3</span>
                                <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-slate-900 dark:text-white leading-[1.1] transition-colors">
                                    Sumbu Posisi & <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-500 to-cyan-600 dark:from-blue-400 dark:to-cyan-500">Z-Index Layers</span>
                                </h2>
                            </div>

                            <div class="bg-blue-50 dark:bg-[#111112] border border-blue-200 dark:border-white/10 p-5 rounded-xl shadow-sm text-justify text-sm sm:text-base text-slate-600 dark:text-white/70 leading-relaxed font-medium transition-colors">
                                Pemahaman sistem koordinat <strong>Positioning</strong> dan struktur <strong>Z-Index</strong> adalah syarat mutlak merekayasa ruang tiga dimensi. Konsep ini mengizinkan pembuatan komponen melayang (seperti menu dropdown atau notifikasi lencana) agar terlepas sepenuhnya dari urutan tumpukan dokumen standar.
                            </div>

                            <div class="grid md:grid-cols-2 gap-4 lg:gap-6">
                                <div class="bg-white dark:bg-gradient-to-br dark:from-slate-800 dark:to-slate-900 p-5 rounded-xl border border-slate-200 dark:border-white/10 shadow-sm transition-colors hover:border-blue-400 dark:hover:border-blue-500/50">
                                    <div class="flex items-center gap-3 mb-3 border-b border-slate-100 dark:border-white/5 pb-2">
                                        <div class="w-2.5 h-2.5 rounded-full bg-blue-500 dark:bg-blue-400 shrink-0 shadow-sm"></div>
                                        <code class="text-blue-600 dark:text-blue-300 font-bold bg-blue-50 dark:bg-black/50 px-2 py-1 rounded shadow-sm text-sm tracking-wide">relative</code>
                                    </div>
                                    <p class="text-xs sm:text-sm m-0 text-slate-600 dark:text-white/60">Elemen tetap ada di tempat aslinya, namun ia bertindak mutlak sebagai <span class="text-slate-800 dark:text-white font-bold bg-slate-100 dark:bg-white/10 px-1 rounded border border-slate-200 dark:border-white/10">Jangkar Penahan</span> (Positioning Context). Ia mengikat elemen anak berstatus absolut di dalamnya agar tidak melayang keluar batas.</p>
                                </div>
                                
                                <div class="bg-white dark:bg-gradient-to-br dark:from-slate-800 dark:to-slate-900 p-5 rounded-xl border border-slate-200 dark:border-white/10 shadow-sm transition-colors hover:border-rose-400 dark:hover:border-rose-500/50">
                                    <div class="flex items-center gap-3 mb-3 border-b border-slate-100 dark:border-white/5 pb-2">
                                        <div class="w-2.5 h-2.5 rounded-full bg-rose-500 dark:bg-rose-400 animate-pulse shrink-0"></div>
                                        <code class="text-rose-600 dark:text-rose-300 font-bold bg-rose-50 dark:bg-black/50 px-2 py-1 rounded shadow-sm text-sm tracking-wide">absolute</code>
                                    </div>
                                    <p class="text-xs sm:text-sm m-0 text-slate-600 dark:text-white/60">Pencabut gravitasi dokumen. Terbang bebas menindih elemen lain. Ia akan melacak keluar terus menerus hingga <strong class="text-rose-600 dark:text-rose-300">menabrak elemen induk terdekat yang berstatus relative, absolute, atau fixed</strong>.</p>
                                </div>
                                
                                <div class="bg-white dark:bg-gradient-to-br dark:from-slate-800 dark:to-slate-900 p-5 rounded-xl border border-slate-200 dark:border-white/10 shadow-sm transition-colors hover:border-emerald-400 dark:hover:border-emerald-500/50">
                                    <div class="flex items-center gap-3 mb-3 border-b border-slate-100 dark:border-white/5 pb-2">
                                        <div class="w-2.5 h-2.5 border-2 border-emerald-500 dark:border-emerald-400 shrink-0 shadow-sm"></div>
                                        <code class="text-emerald-600 dark:text-emerald-300 font-bold bg-emerald-50 dark:bg-black/50 px-2 py-1 rounded shadow-sm text-sm tracking-wide">fixed</code>
                                    </div>
                                    <p class="text-xs sm:text-sm m-0 text-slate-600 dark:text-white/60">Paku bumi layar (Viewport Canvas). Mengunci komponen sehingga kebal mutlak terhadap guliran (scroll) layar. Teknik standar untuk membekukan Navbar di atas.</p>
                                </div>
                                
                                <div class="bg-white dark:bg-gradient-to-br dark:from-slate-800 dark:to-slate-900 p-5 rounded-xl border border-slate-200 dark:border-white/10 shadow-sm transition-colors hover:border-amber-400 dark:hover:border-amber-500/50">
                                    <div class="flex items-center gap-3 mb-3 border-b border-slate-100 dark:border-white/5 pb-2">
                                        <div class="w-2.5 h-2.5 bg-amber-500 dark:bg-amber-400 rotate-45 shrink-0 shadow-sm"></div>
                                        <code class="text-amber-600 dark:text-amber-300 font-bold bg-amber-50 dark:bg-black/50 px-2 py-1 rounded shadow-sm text-sm tracking-wide">sticky</code>
                                    </div>
                                    <p class="text-xs sm:text-sm m-0 text-slate-600 dark:text-white/60">Hibrida mutasi dinamis. Awalnya berperilaku diam layaknya balok normal, namun otomatis mengunci diri berubah menjadi <strong class="text-amber-600 dark:text-amber-400">status fixed</strong> ketika ia tersenggol batas atas layar guliran Anda.</p>
                                </div>
                            </div>

                            <div class="bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 p-5 rounded-r-xl shadow-sm dark:shadow-none transition-colors border-y border-r border-y-blue-200 border-r-blue-200 dark:border-y-blue-500/20 dark:border-r-blue-500/20">
                                <h4 class="text-blue-800 dark:text-blue-400 font-bold text-sm mb-3 flex items-center gap-2 transition-colors">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                    Aturan Penumpukan Z-Index 
                                </h4>
                                <ul class="list-none pl-0 space-y-3 m-0">
                                    <li class="flex items-start gap-3 text-xs sm:text-sm text-blue-900/80 dark:text-blue-100/80 transition-colors"><div class="mt-1 w-1.5 h-1.5 rounded-full bg-blue-500 dark:bg-blue-400 shrink-0 transition-colors shadow-sm"></div> <p class="m-0 leading-relaxed font-medium">Bila antarmuka bergeser saling tumpang tindih, urutan posisi depan-belakangnya dimanipulasi dengan skala indeks <code class="text-blue-700 dark:text-blue-300 font-bold bg-blue-100 dark:bg-blue-900/40 px-1.5 py-0.5 rounded shadow-sm transition-colors">z-{indeks}</code>. Nilai numerik tertinggi berhak menang dan mendominasi lapisan visual depan (aturan Painter's Algorithm).</p></li>
                                    <li class="flex items-start gap-3 text-xs sm:text-sm text-blue-900/80 dark:text-blue-100/80 transition-colors"><div class="mt-1 w-1.5 h-1.5 rounded-full bg-rose-500 shrink-0 transition-colors shadow-sm"></div> <p class="m-0 leading-relaxed font-medium"><strong class="text-rose-700 dark:text-rose-400 uppercase tracking-widest text-[10px] sm:text-xs block mb-1">Peringatan Kegagalan:</strong> Indeks penumpukan Z-Index dipastikan <strong>batal dan lumpuh</strong> apabila elemen target masih membeku mati di posisi <code class="text-slate-800 dark:text-white font-bold bg-rose-100 dark:bg-rose-900/40 px-1.5 py-0.5 rounded border border-rose-200 dark:border-rose-500/30 transition-colors">static</code> bawaan.</p></li>
                                </ul>
                            </div>

                            {{-- SIMULATOR 3 --}}
                            <div class="bg-white dark:bg-[#0b0f19] border border-slate-200 dark:border-white/10 rounded-2xl p-6 lg:p-8 shadow-xl dark:shadow-2xl relative overflow-hidden group transition-colors mt-10">
                                <h4 class="text-xs font-bold text-slate-400 dark:text-muted uppercase mb-4 text-center transition-colors tracking-widest">Simulator Visual: Konteks 3D Stacking Hierarki</h4>
                                
                                <div class="bg-blue-50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/30 rounded-xl p-4 mb-6 flex flex-col sm:flex-row gap-4 items-center shadow-sm dark:shadow-none relative z-20 transition-colors">
                                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-500/20 rounded-full flex items-center justify-center shrink-0 transition-colors border border-blue-200 dark:border-blue-500/30"><svg class="w-5 h-5 text-blue-600 dark:text-blue-300 animate-pulse transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"></path></svg></div>
                                    <div>
                                        <p class="font-bold text-blue-800 dark:text-blue-300 text-xs sm:text-sm mb-1.5 uppercase tracking-wider transition-colors">Operasi Bongkar Dimensi</p>
                                        <p class="m-0 text-[11px] sm:text-xs text-blue-700/80 dark:text-blue-200/80 transition-colors leading-relaxed font-medium">Ubah Wadah Induk ke panel <span class="font-bold">Static</span>. Perhatikan kubus terbang membobol dinding wadah karena hilangnya jangkar posisi. Restorasi kembali ke <span class="font-bold">Relative</span>, lalu tekan barisan tombol warna untuk melemparkan kubus tersebut mendominasi lapisan puncak layar dengan manipulasi <span class="font-bold text-slate-900 dark:text-white">z-50</span>.</p>
                                    </div>
                                </div>

                                <div class="flex flex-col md:flex-row gap-3 justify-between mb-6 relative z-20 transition-colors">
                                    <div class="flex gap-1 items-center bg-slate-100 dark:bg-black/40 p-1.5 rounded-lg border border-slate-200 dark:border-white/10 shadow-inner flex-wrap sm:flex-nowrap w-full md:w-auto transition-colors">
                                        <span class="text-[9px] text-slate-500 dark:text-white/40 uppercase font-bold px-2 hidden lg:block transition-colors tracking-widest">Wadah Induk:</span>
                                        <button onclick="simPosition('relative', this)" class="sim3-pos flex-1 px-4 py-2 text-[10px] sm:text-xs font-bold rounded bg-blue-600 text-white border border-blue-500 shadow-[0_0_10px_rgba(59,130,246,0.4)] transition shrink-0 focus:outline-none">Relative</button>
                                        <button onclick="simPosition('static', this)" class="sim3-pos flex-1 px-4 py-2 text-[10px] sm:text-xs font-bold rounded bg-slate-200 dark:bg-transparent text-slate-600 dark:text-white/50 hover:bg-slate-300 dark:hover:bg-white/5 transition border border-transparent shrink-0 focus:outline-none">Static (Bocor)</button>
                                    </div>
                                    <div class="flex gap-1 items-center bg-slate-100 dark:bg-black/40 p-1.5 rounded-lg border border-slate-200 dark:border-white/10 shadow-inner flex-wrap sm:flex-nowrap w-full md:w-auto transition-colors">
                                        <span class="text-[9px] text-slate-500 dark:text-white/40 uppercase font-bold px-2 hidden lg:block transition-colors tracking-widest">Tarik Depan (z-50):</span>
                                        <button onclick="simZIndex('red')" class="flex-1 px-4 py-2 text-[10px] sm:text-xs font-bold rounded bg-red-100 dark:bg-red-600/30 hover:bg-red-200 dark:hover:bg-red-500 text-red-700 dark:text-red-300 transition border border-red-300 dark:border-red-500/50 shrink-0 focus:outline-none">Merah</button>
                                        <button onclick="simZIndex('blue')" class="flex-1 px-4 py-2 text-[10px] sm:text-xs font-bold rounded bg-blue-100 dark:bg-blue-600/30 hover:bg-blue-200 dark:hover:bg-blue-500 text-blue-700 dark:text-blue-300 transition border border-blue-300 dark:border-blue-500/50 shrink-0 focus:outline-none">Biru</button>
                                        <button onclick="simZIndex('green')" class="flex-1 px-4 py-2 text-[10px] sm:text-xs font-bold rounded bg-emerald-100 dark:bg-emerald-600/30 hover:bg-emerald-200 dark:hover:bg-emerald-500 text-emerald-700 dark:text-emerald-300 transition border border-emerald-300 dark:border-emerald-500/50 shrink-0 focus:outline-none">Hijau</button>
                                    </div>
                                </div>
                                
                                <div class="w-full bg-slate-50 dark:bg-[#111112] h-[320px] rounded-xl border border-slate-200 dark:border-white/5 relative flex items-center justify-center overflow-hidden perspective-1000 shadow-inner transition-colors">
                                    <div class="absolute inset-0 visual-grid opacity-50 dark:opacity-20 pointer-events-none"></div>
                                    
                                    {{-- Relative Parent Anchor --}}
                                    <div id="sim3-parent" class="w-56 h-40 sm:w-64 sm:h-48 border-2 border-dashed border-blue-400 dark:border-blue-500/80 bg-blue-50 dark:bg-blue-500/5 rounded-xl relative transition-all duration-700 flex items-center justify-center transform-style-3d rotate-x-12 rotate-y-6 shadow-xl dark:shadow-[0_0_50px_rgba(59,130,246,0.15)] z-10 backdrop-blur-sm">
                                        <div class="absolute -top-3 left-4 bg-slate-100 dark:bg-[#111112] px-2 py-0.5 text-[9px] font-mono font-bold text-blue-600 dark:text-blue-400 tracking-widest transition-colors shadow-sm border border-blue-200 dark:border-blue-500/30 rounded" id="sim3-label">WADAH RELATIVE</div>
                                        
                                        {{-- Absolute Children --}}
                                        <div id="sim3-red" class="absolute -top-4 -left-4 w-20 h-20 sm:w-24 sm:h-24 bg-red-600/90 rounded-lg shadow-[0_10px_20px_rgba(220,38,38,0.3)] border border-red-400 p-2 flex items-end justify-end transition-all duration-700 ease-out z-10"><span class="text-[9px] sm:text-[10px] font-bold bg-white/90 dark:bg-black/50 text-red-900 dark:text-white px-1.5 py-0.5 rounded shadow-sm dark:shadow-inner transition-colors" id="lbl-z-red">z-10</span></div>
                                        <div id="sim3-blue" class="absolute top-4 left-4 w-20 h-20 sm:w-24 sm:h-24 bg-blue-600/90 rounded-lg shadow-[0_10px_20px_rgba(37,99,235,0.3)] border border-blue-400 p-2 flex items-end justify-end transition-all duration-700 ease-out z-20"><span class="text-[9px] sm:text-[10px] font-bold bg-white/90 dark:bg-black/50 text-blue-900 dark:text-white px-1.5 py-0.5 rounded shadow-sm dark:shadow-inner transition-colors" id="lbl-z-blue">z-20</span></div>
                                        <div id="sim3-green" class="absolute top-12 left-12 w-20 h-20 sm:w-24 sm:h-24 bg-emerald-500/90 rounded-lg shadow-[0_10px_20px_rgba(16,185,129,0.3)] border border-emerald-300 p-2 flex items-end justify-end transition-all duration-700 ease-out z-30"><span class="text-[9px] sm:text-[10px] font-bold bg-white/90 dark:bg-black/50 text-emerald-900 dark:text-white px-1.5 py-0.5 rounded shadow-sm dark:shadow-inner transition-colors" id="lbl-z-green">z-30</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 44: TABLE LAYOUTS --}}
                    <section id="section-44" class="lesson-section scroll-mt-32" data-lesson-id="44">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-pink-500 pl-6">
                                <span class="text-pink-600 dark:text-pink-400 font-mono text-xs uppercase tracking-widest font-bold transition-colors">Lesson 2.3.4</span>
                                <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-slate-900 dark:text-white leading-[1.1] transition-colors">
                                    Table Layout: <br> 
                                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-pink-500 to-rose-600 dark:from-pink-400 dark:to-rose-500">Auto vs Fixed Algoritma</span>
                                </h2>
                            </div>

                            <div class="bg-white dark:bg-[#111112] border border-slate-200 dark:border-white/10 p-6 rounded-xl shadow-sm text-justify text-sm sm:text-base text-slate-600 dark:text-white/70 leading-relaxed font-medium transition-colors">
                                <p class="mb-4">Ketika merancang sistem antarmuka Dashboard Admin, keliru memilih algoritma tabel murni dapat menyebabkan kelumpuhan respons rendering pada perangkat klien. Hal ini berdampak langsung pada metrik Cumulative Layout Shift (CLS).</p>
                                <p class="m-0">Browser merender tabel lewat dua pendekatan komputasi: <strong>Automatic Layout Algorithm</strong> (fleksibel namun lambat mencetak karena menimbang seluruh isi data) dan <strong>Fixed Table Layout Algorithm</strong> (sangat cepat dengan mengorbankan pelebaran organik demi satu kali cetak kilat).</p>
                            </div>

                            <div class="grid md:grid-cols-2 gap-5 mt-6">
                                {{-- Table Auto --}}
                                <div class="bg-cyan-50 dark:bg-cyan-500/5 border border-cyan-200 dark:border-cyan-500/20 p-5 rounded-2xl flex flex-col group hover:-translate-y-1 duration-300 transition-all">
                                    <div class="flex justify-between items-center mb-3 border-b border-cyan-200/50 dark:border-cyan-500/20 pb-2">
                                        <code class="text-cyan-700 dark:text-cyan-400 font-bold bg-white dark:bg-black/40 px-2 py-1 rounded-md shadow-sm text-xs">.table-auto</code>
                                    </div>
                                    <p class="text-xs text-cyan-900 dark:text-cyan-100/70 mb-4 leading-relaxed text-justify font-medium flex-1">
                                        Menggunakan algoritma iterasi multi-pass. Browser dipaksa menunda cetakan layar hanya untuk mensurvei, memindai, dan menimbang lebar teks terpanjang dari tiap sel yang ada.
                                    </p>
                                    <div class="bg-white/60 dark:bg-black/30 p-3 rounded-xl border border-cyan-200/50 dark:border-cyan-500/20 text-xs text-cyan-800 dark:text-cyan-300 mt-auto shadow-inner">
                                        <strong class="text-rose-600 dark:text-rose-400 block mb-1 uppercase tracking-widest text-[10px]">Cacat Terbesar:</strong> Menyebabkan guncangan ukuran secara mendadak (Layout Shift) jika data beban berat ditembak masuk via database asinkron.
                                    </div>
                                </div>

                                {{-- Table Fixed --}}
                                <div class="bg-pink-50 dark:bg-pink-500/5 border border-pink-200 dark:border-pink-500/20 p-5 rounded-2xl flex flex-col group hover:-translate-y-1 duration-300 transition-all">
                                    <div class="flex justify-between items-center mb-3 border-b border-pink-200/50 dark:border-pink-500/20 pb-2">
                                        <code class="text-pink-700 dark:text-pink-400 font-bold bg-white dark:bg-black/40 px-2 py-1 rounded-md shadow-sm text-xs">.table-fixed</code>
                                    </div>
                                    <p class="text-xs text-pink-900 dark:text-pink-100/70 mb-4 leading-relaxed text-justify font-medium flex-1">
                                        Metode perisai kebal guncangan dengan proses satu siklus (single-pass). Rasio lebar kolom dibekukan secara statis mematuhi instruksi keras pada baris pertama saja (<code class="bg-white/60 dark:bg-black/50 px-1 rounded">w-1/4</code>) mengabaikan teks di bawahnya.
                                    </p>
                                    <div class="bg-white/60 dark:bg-black/30 p-3 rounded-xl border border-pink-200/50 dark:border-pink-500/20 text-xs text-pink-800 dark:text-pink-300 mt-auto shadow-inner">
                                        <strong class="text-emerald-600 dark:text-emerald-400 block mb-1 uppercase tracking-widest text-[10px]">Taktik Tambahan:</strong> Padukan dengan <code class="font-bold">truncate</code> untuk memotong teks berlebih yang meluap dari garis tabel.
                                    </div>
                                </div>
                            </div>

                            {{-- SIMULATOR 4 --}}
                            <div class="bg-white dark:bg-[#0b0f19] border border-slate-200 dark:border-white/10 rounded-2xl p-5 lg:p-6 shadow-md dark:shadow-xl relative group hover:border-pink-400 dark:hover:border-pink-500/30 transition-all flex flex-col mt-8 overflow-hidden">
                                <h4 class="text-[10px] font-bold text-slate-400 dark:text-muted uppercase mb-5 text-center transition-colors tracking-widest">Simulator Render: Uji Guncangan Tekanan Muatan Asinkron API</h4>
                                
                                <div class="bg-pink-50 dark:bg-pink-900/10 border border-pink-200 dark:border-pink-500/20 rounded-xl p-4 mb-5 flex flex-col md:flex-row gap-4 items-center justify-between shadow-sm dark:shadow-inner transition-colors relative z-10">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-pink-100 dark:bg-pink-500/20 rounded-xl flex items-center justify-center shrink-0 border border-pink-300 dark:border-pink-500/30 transition-colors"><svg class="w-5 h-5 text-pink-600 dark:text-pink-400 animate-pulse transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg></div>
                                        <div>
                                            <p class="font-bold text-pink-800 dark:text-pink-300 text-[11px] sm:text-xs mb-1 uppercase tracking-wider transition-colors">Uji Guncangan Layout</p>
                                            <p class="m-0 text-[10px] sm:text-[11px] text-pink-700/90 dark:text-pink-200/70 text-justify max-w-lg transition-colors font-medium">Klik tombol untuk menyuntikkan muatan teks secara masif. Saksikan tipe arsitektur tabel mana yang menderita kolaps bentuk paling parah dan membuat teksnya loncat.</p>
                                        </div>
                                    </div>
                                    <button onclick="simTableInject(this)" class="px-4 py-2.5 bg-gradient-to-r from-pink-600 to-rose-600 hover:from-pink-500 hover:to-rose-500 text-white font-bold text-[10px] uppercase tracking-widest rounded-lg shadow-[0_0_10px_rgba(244,63,94,0.4)] transition-all active:scale-95 shrink-0 w-full md:w-auto border border-pink-400 focus:outline-none hover:-translate-y-0.5 whitespace-nowrap">Injeksi Payload API</button>
                                </div>

                                <div class="grid lg:grid-cols-2 gap-4 relative z-10">
                                    {{-- Table Auto Target --}}
                                    <div class="bg-slate-50 dark:bg-[#111112] rounded-xl border border-slate-200 dark:border-white/5 overflow-hidden shadow-sm dark:shadow-inner flex flex-col transition-colors">
                                        <div class="bg-cyan-50 dark:bg-cyan-900/20 p-2 border-b border-cyan-200 dark:border-cyan-500/20 text-center transition-colors"><code class="text-[10px] text-cyan-700 dark:text-cyan-400 font-bold tracking-widest uppercase transition-colors">.table-auto</code></div>
                                        <div class="overflow-x-auto w-full custom-scrollbar flex-1 bg-white dark:bg-transparent">
                                            <table class="table-auto w-full text-[10px] text-left text-slate-600 dark:text-white/70 transition-colors min-w-[300px]">
                                                <thead class="bg-slate-100 dark:bg-white/5 border-b border-slate-200 dark:border-white/10 transition-colors">
                                                    <tr>
                                                        <th class="p-3 text-cyan-800 dark:text-cyan-300 font-bold transition-colors">ID Sesi <span class="opacity-60 font-medium">(Auto)</span></th>
                                                        <th class="p-3 text-cyan-800 dark:text-cyan-300 font-bold transition-colors">Laporan <span class="opacity-60 font-medium">(Auto)</span></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="p-3 border-r border-slate-200 dark:border-white/5 whitespace-nowrap text-cyan-700 dark:text-cyan-100 font-mono font-bold transition-colors bg-cyan-50/50 dark:bg-cyan-900/10" id="sim4-auto-id">AX-001</td>
                                                        <td class="p-3 text-slate-500 dark:text-white/50 sim4-data transition-all duration-300 bg-white dark:bg-white/5 font-medium">Koneksi protokol komunikasi sistem prima. Menanti muatan susulan.</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    {{-- Table Fixed Target --}}
                                    <div class="bg-slate-50 dark:bg-[#111112] rounded-xl border border-slate-200 dark:border-white/5 overflow-hidden shadow-sm dark:shadow-inner flex flex-col transition-colors">
                                        <div class="bg-pink-50 dark:bg-pink-900/20 p-2 border-b border-pink-200 dark:border-pink-500/20 text-center transition-colors"><code class="text-[10px] text-pink-700 dark:text-pink-400 font-bold tracking-widest uppercase transition-colors">.table-fixed</code></div>
                                        <div class="overflow-x-auto w-full custom-scrollbar flex-1 bg-white dark:bg-transparent">
                                            <table class="table-fixed w-full text-[10px] text-left text-slate-600 dark:text-white/70 transition-colors min-w-[300px]">
                                                <thead class="bg-slate-100 dark:bg-white/5 border-b border-slate-200 dark:border-white/10 transition-colors">
                                                    <tr>
                                                        <th class="p-3 w-1/4 text-pink-800 dark:text-pink-300 font-bold transition-colors bg-pink-100/50 dark:bg-pink-500/10 border-r border-pink-200 dark:border-pink-500/20">
                                                            ID <code class="ml-1 bg-pink-200/50 dark:bg-pink-900/50 px-1 rounded border border-pink-300 dark:border-pink-500/40">w-1/4</code>
                                                        </th>
                                                        <th class="p-3 w-3/4 text-pink-800 dark:text-pink-300 font-bold transition-colors">
                                                            Log Data <code class="ml-1 bg-pink-100 dark:bg-pink-900/40 px-1 rounded border border-pink-200 dark:border-pink-500/30">w-3/4</code>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="p-3 border-r border-slate-200 dark:border-white/5 whitespace-nowrap text-pink-700 dark:text-pink-100 font-mono font-bold transition-colors bg-pink-50/80 dark:bg-pink-900/10">AX-001</td>
                                                        <td class="p-3 text-slate-500 dark:text-white/50 truncate sim4-data transition-all duration-300 bg-emerald-50 dark:bg-emerald-500/5 font-medium relative border-b border-emerald-100 dark:border-transparent">
                                                            <div class="absolute right-0 top-0 bottom-0 w-8 bg-gradient-to-l from-emerald-50 dark:from-[#0b0e14] to-transparent pointer-events-none hidden transition-colors" id="fade-mask"></div>
                                                            Koneksi protokol komunikasi sistem prima. Menanti muatan susulan.
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- FINAL MISSION (EXPERT MODE) --}}
                    <section id="section-45" class="lesson-section scroll-mt-24 pt-8 border-t border-slate-200 dark:border-white/10 transition-colors" data-lesson-id="45" data-type="activity">
                        <div class="relative rounded-3xl bg-white dark:bg-[#050b14] border border-slate-200 dark:border-white/10 p-6 lg:p-8 shadow-xl dark:shadow-2xl flex flex-col group hover:border-indigo-400 dark:hover:border-indigo-500/30 transition-all duration-500 overflow-hidden">
                            <div class="absolute -top-24 -right-24 w-64 h-64 bg-indigo-400/20 dark:bg-indigo-600/10 blur-[100px] rounded-full pointer-events-none transition-colors"></div>

                            <div class="flex items-center gap-4 mb-6 relative z-10">
                                <div class="p-3 bg-indigo-600 rounded-xl text-white shadow-[0_0_15px_rgba(99,102,241,0.4)] shrink-0 border border-indigo-400">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <h2 class="text-xl font-black text-slate-900 dark:text-white tracking-tight transition-colors">Final Mission: Holographic Floating Navigation</h2>
                                        <span class="px-1.5 py-0.5 rounded text-[8px] font-bold bg-rose-100 dark:bg-rose-500/20 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-500/30 uppercase tracking-widest shadow-sm transition-colors">Expert Mode</span>
                                    </div>
                                    <div class="text-slate-600 dark:text-indigo-100/70 text-xs leading-relaxed max-w-3xl transition-colors font-medium m-0">
                                        <p class="text-slate-500 dark:text-slate-400 text-xs sm:text-sm leading-relaxed max-w-3xl text-justify transition-colors">
                                            Sebagai ujian praktik tahap akhir di bab ini, rancanglah sebuah <i>Holographic Floating Navigation</i>. Terapkan kelas utilitas Tailwind CSS ke dalam struktur kode HTML pada jendela Terminal Kode untuk menyusun paku bumi navigasi permanen (fixed), menetapkan jangkar referensi (relative), melontarkan lencana notifikasi (absolute), dan menahan lebar konten utama (container). Penuhi indikator kelas di bawah ini agar pemindai mesin otomatis menerbitkan centang kelulusan.
                                        </p>
                                        
                                        <div class="mt-4 flex flex-wrap items-center gap-2">
                                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mr-1 transition-colors">Kamus Indikator Validasi Target Modul:</span>
                                            <code class="text-[10px] md:text-xs font-mono font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-100 dark:bg-indigo-500/10 border border-indigo-200 dark:border-indigo-500/20 px-2 py-1 rounded shadow-sm transition-colors">fixed</code>
                                            <code class="text-[10px] md:text-xs font-mono font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-100 dark:bg-indigo-500/10 border border-indigo-200 dark:border-indigo-500/20 px-2 py-1 rounded shadow-sm transition-colors">w-full</code>
                                            <code class="text-[10px] md:text-xs font-mono font-bold text-blue-600 dark:text-blue-400 bg-blue-100 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/20 px-2 py-1 rounded shadow-sm transition-colors">z-50</code>
                                            <code class="text-[10px] md:text-xs font-mono font-bold text-purple-600 dark:text-purple-400 bg-purple-100 dark:bg-purple-500/10 border border-purple-200 dark:border-purple-500/20 px-2 py-1 rounded shadow-sm transition-colors">relative</code>
                                            <code class="text-[10px] md:text-xs font-mono font-bold text-rose-600 dark:text-rose-400 bg-rose-100 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/20 px-2 py-1 rounded shadow-sm transition-colors">absolute</code>
                                            <code class="text-[10px] md:text-xs font-mono font-bold text-rose-600 dark:text-rose-400 bg-rose-100 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/20 px-2 py-1 rounded shadow-sm transition-colors">-top-1</code>
                                            <code class="text-[10px] md:text-xs font-mono font-bold text-rose-600 dark:text-rose-400 bg-rose-100 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/20 px-2 py-1 rounded shadow-sm transition-colors">-right-1</code>
                                            <code class="text-[10px] md:text-xs font-mono font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 px-2 py-1 rounded shadow-sm transition-colors">container</code>
                                            <code class="text-[10px] md:text-xs font-mono font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 px-2 py-1 rounded shadow-sm transition-colors">mx-auto</code>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-col lg:grid lg:grid-cols-12 gap-0 border border-slate-200 dark:border-white/10 rounded-2xl overflow-hidden shadow-md dark:shadow-xl relative z-10 flex-1 transition-colors bg-white dark:bg-slate-950 mt-8">
                                
                                {{-- EDITOR KIRI --}}
                                <div class="lg:col-span-5 bg-slate-50 dark:bg-[#111112] border-b lg:border-b-0 lg:border-r border-slate-200 dark:border-white/10 flex flex-col relative min-h-[500px] xl:min-h-[600px] transition-colors w-full">
                                    
                                    {{-- LOCK OVERLAY --}}
                                    <div id="lockOverlay" class="hidden absolute inset-0 bg-white/95 dark:bg-[#050912]/95 backdrop-blur-md z-50 flex flex-col items-center justify-center text-center p-6 transition-colors border-2 border-emerald-500/20">
                                        <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-500/10 rounded-full flex items-center justify-center mb-4 border border-emerald-300 dark:border-emerald-500/50 shadow-[0_0_20px_rgba(16,185,129,0.2)] animate-bounce transition-colors">
                                            <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                        </div>
                                        <h3 class="text-lg font-black text-slate-900 dark:text-white mb-2 tracking-tight transition-colors uppercase">Misi Sempurna!</h3>
                                        <p class="text-xs font-medium text-slate-500 dark:text-white/60 mb-6 max-w-xs transition-colors">Data log penyelesaian arsitektur layout tingkat lanjut ini telah direkam permanen menuju database pusat kelulusan Anda.</p>
                                    </div>

                                    <div class="bg-slate-200 dark:bg-[#1a1a1c] px-4 py-2 border-b border-slate-300 dark:border-white/5 flex justify-between items-center shrink-0 transition-colors shadow-sm dark:shadow-none">
                                        <span class="text-[10px] text-slate-600 dark:text-white/60 font-mono font-bold flex items-center gap-1.5 transition-colors"><div class="w-2 h-2 rounded-full bg-yellow-500 shadow-[0_0_5px_#f59e0b]"></div> Editor: <span class="bg-slate-300 dark:bg-black/50 px-1.5 py-0.5 rounded ml-1 border border-slate-400 dark:border-white/10">App-Layout.html</span></span>
                                        <button onclick="resetEditor()" class="text-[9px] text-red-600 dark:text-red-400 hover:text-white hover:bg-red-500 uppercase font-black focus:outline-none bg-red-100 dark:bg-red-900/40 px-2 py-1 rounded shadow-sm border border-red-300 dark:border-red-500/30 active:scale-95 transition-all duration-300 tracking-wider">Reset Kode</button>
                                    </div>
                                    
                                    <div id="codeEditor" class="flex-1 w-full relative transition-colors shadow-inner"></div>

                                    <div class="p-4 sm:p-5 bg-slate-100 dark:bg-[#0f141e] flex flex-col shrink-0 border-t border-slate-200 dark:border-white/5 transition-colors shadow-inner dark:shadow-none z-10">
                                        <div class="flex justify-between items-center mb-3">
                                            <span class="text-[9px] uppercase font-black text-slate-500 dark:text-white/40 tracking-widest transition-colors">Kriteria Uji Tuntas</span>
                                            <span id="progressText" class="text-[9px] font-mono font-black text-indigo-700 dark:text-indigo-300 bg-indigo-100 dark:bg-indigo-900/40 px-2 py-0.5 rounded border border-indigo-200 dark:border-indigo-500/30 shadow-sm transition-colors tracking-widest">0/4 Lolos</span>
                                        </div>
                                        <div class="grid gap-2 text-[10px] font-mono text-slate-600 dark:text-white/50 mb-4 bg-white dark:bg-black/30 p-3 rounded-lg shadow-sm dark:shadow-inner border border-slate-200 dark:border-white/5 transition-colors max-h-36 overflow-y-auto custom-scrollbar">
                                            <div id="check-nav" class="flex items-start gap-2"><span class="w-3.5 h-3.5 mt-0.5 rounded-full border-2 border-slate-300 dark:border-white/20 flex items-center justify-center text-[8px] transition-colors shrink-0 font-bold bg-slate-50 dark:bg-transparent"></span> <div class="leading-tight text-slate-800 dark:text-white/90 transition-colors font-sans"><b>1. Holographic Nav:</b> Terapkan kelas <code class="text-[9px]">fixed w-full z-50</code> pada <span class="font-bold text-rose-500">#hologram-nav</span>.</div></div>
                                            <div id="check-avatar" class="flex items-start gap-2"><span class="w-3.5 h-3.5 mt-0.5 rounded-full border-2 border-slate-300 dark:border-white/20 flex items-center justify-center text-[8px] transition-colors shrink-0 font-bold bg-slate-50 dark:bg-transparent"></span> <div class="leading-tight text-slate-800 dark:text-white/90 transition-colors font-sans"><b>2. Jangkar Dimensi:</b> Terapkan <code class="text-[9px]">relative</code> mutlak pada pembungkus <span class="font-bold text-rose-500">#avatar-wrapper</span>.</div></div>
                                            <div id="check-badge" class="flex items-start gap-2"><span class="w-3.5 h-3.5 mt-0.5 rounded-full border-2 border-slate-300 dark:border-white/20 flex items-center justify-center text-[8px] transition-colors shrink-0 font-bold bg-slate-50 dark:bg-transparent"></span> <div class="leading-tight text-slate-800 dark:text-white/90 transition-colors font-sans"><b>3. Pelontar Bebas:</b> Terapkan kelas <code class="text-[9px]">absolute -top-1 -right-1</code> pada <span class="font-bold text-rose-500">#alert-badge</span>.</div></div>
                                            <div id="check-main" class="flex items-start gap-2"><span class="w-3.5 h-3.5 mt-0.5 rounded-full border-2 border-slate-300 dark:border-white/20 flex items-center justify-center text-[8px] transition-colors shrink-0 font-bold bg-slate-50 dark:bg-transparent"></span> <div class="leading-tight text-slate-800 dark:text-white/90 transition-colors font-sans"><b>4. Pembungkus Elastis:</b> Terapkan <code class="text-[9px]">container mx-auto</code> pada <span class="font-bold text-rose-500">#main-content</span>.</div></div>
                                        </div>
                                        <button id="submitExerciseBtn" onclick="submitExercise()" disabled class="w-full py-3 rounded-xl bg-emerald-600 text-white font-black text-xs shadow-md hover:bg-emerald-500 hover:-translate-y-0.5 transition-all duration-300 cursor-not-allowed opacity-50 flex items-center justify-center gap-2 focus:outline-none active:scale-95 border border-emerald-500 uppercase tracking-widest">
                                            <span>Penuhi Syarat Validator Terlebih Dahulu</span>
                                        </button>
                                    </div>
                                </div>

                                {{-- PREVIEW KANAN --}}
                                <div class="lg:col-span-7 bg-slate-100 dark:bg-[#111112] flex flex-col relative min-h-[300px] xl:h-auto transition-colors shadow-inner">
                                    <div class="bg-slate-200 dark:bg-[#1a1a1c] px-4 py-2 border-b border-slate-300 dark:border-white/5 flex items-center justify-between shrink-0 transition-colors z-10 shadow-sm dark:shadow-none">
                                        <span class="text-[10px] text-slate-600 dark:text-gray-400 font-mono font-bold flex items-center gap-2 transition-colors uppercase tracking-widest"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg> Live Browser Preview</span>
                                        <span class="text-[8px] bg-indigo-200/60 dark:bg-indigo-900/50 text-indigo-800 dark:text-indigo-300 px-1.5 py-0.5 rounded border border-indigo-300 dark:border-indigo-500/40 font-black uppercase tracking-widest flex items-center gap-1 shadow-sm transition-colors">
                                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 dark:bg-indigo-400 animate-pulse shadow-[0_0_8px_#6366f1]"></span> Auto-Sync Live
                                        </span>
                                    </div>
                                    <div class="flex-1 bg-white dark:bg-black relative w-full h-full p-0 transition-colors shadow-inner overflow-hidden">
                                        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.03] dark:opacity-20 pointer-events-none mix-blend-overlay transition-opacity"></div>
                                        <div class="absolute inset-0 visual-grid opacity-30 pointer-events-none"></div>
                                        <iframe id="previewFrame" class="w-full h-full border-0 bg-transparent relative z-10 custom-scrollbar"></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                </article>

                <div class="mt-32 pt-8 border-t border-slate-200 dark:border-white/10 flex flex-col sm:flex-row justify-between items-center gap-4 sm:gap-0 transition-colors">
                    <a href="{{ route('courses.grid') ?? '#' }}" class="group flex items-center gap-4 text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors w-full sm:w-auto p-3 rounded-2xl hover:bg-slate-100 dark:hover:bg-white/5 border border-transparent hover:border-slate-200 dark:hover:border-white/10">
                        <div class="w-10 h-10 rounded-xl border border-slate-200 dark:border-white/10 flex items-center justify-center bg-white dark:bg-transparent group-hover:bg-slate-200 dark:group-hover:bg-white/10 transition-all shadow-sm dark:shadow-inner group-hover:-translate-x-1 shrink-0">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </div>
                        <div class="text-left">
                            <div class="text-[10px] uppercase tracking-widest opacity-60 font-black mb-0.5">Sebelumnya</div>
                            <div class="font-bold text-xs sm:text-sm">Grid Layout</div>
                        </div>
                    </a>
                    
                    {{-- TOMBOL NEXT TERKUNCI --}}
                    <div id="nextChapterBtn" class="group flex items-center gap-4 text-right text-slate-400 dark:text-slate-500 cursor-not-allowed opacity-50 pointer-events-none transition-all duration-500 w-full sm:w-auto p-3 rounded-2xl border border-transparent">
                        <div class="text-right">
                            <div id="nextLabel" class="text-[10px] uppercase tracking-widest opacity-50 font-black mb-0.5 text-rose-500 dark:text-rose-400 transition-colors">Berikutnya</div>
                            <div class="font-bold text-xs sm:text-sm line-clamp-1">Hands On Lab 2</div>
                        </div>
                        <div id="nextIcon" class="w-10 h-10 rounded-xl border border-slate-200 dark:border-white/10 flex items-center justify-center bg-slate-100 dark:bg-white/5 transition-all duration-300 text-sm shadow-sm dark:shadow-inner shrink-0">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
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
    /* --- CONFIGURATION --- */
    window.LESSON_IDS = [41, 42, 43, 44, 45]; 
    window.COMPLETED_IDS = {!! json_encode($completedLessonIds ?? []) !!};
    let completedSet = new Set(window.COMPLETED_IDS.map(Number));
    
    let activityCompleted = {!! ($activityCompleted ?? false) ? 'true' : 'false' !!};
    const ACTIVITY_ID = 9; 
    const ACTIVITY_LESSON_ID = 45;

    document.addEventListener('DOMContentLoaded', () => {
        initLessonObserver();
        initVisualEffects();
        initSidebarScroll();
        updateProgressUI();
        initMonaco();
        if (activityCompleted) { lockActivityUI(); unlockNextChapter(); }
        
        window.LESSON_IDS.forEach(id => {
            if(completedSet.has(id)) markSidebarDone(id);
        });
    });

    /* --- SIMULATOR LOGICS --- */
    window.clsMxAuto = false; window.clsPx4 = false; window.clsWidth = 'w-full';
    window.simContainer = function(widthCls, btn) {
        const c = document.getElementById('sim1-target');
        c.className = `w-full h-24 sm:h-32 bg-gradient-to-b from-indigo-100 to-indigo-50 dark:from-indigo-500/20 dark:to-indigo-600/10 border-2 border-indigo-300 dark:border-indigo-400/50 rounded-xl flex flex-col items-center justify-center transition-all duration-700 ease-in-out mx-auto px-4 relative shadow-sm dark:shadow-[0_0_40px_rgba(99,102,241,0.2)] backdrop-blur-sm ${widthCls} ${window.clsMxAuto ? 'mx-auto' : 'ml-0'} ${window.clsPx4 ? 'px-4' : 'px-0'}`;
        window.clsWidth = widthCls;
        document.querySelectorAll('.res-btn').forEach(b => {
            b.classList.remove('bg-indigo-600', 'text-white', 'shadow-lg', 'border-indigo-500', 'shadow-indigo-500/30');
            b.classList.add('bg-slate-200', 'dark:bg-transparent', 'text-slate-600', 'dark:text-white/60', 'border-transparent');
        });
        btn.classList.remove('bg-slate-200', 'dark:bg-transparent', 'text-slate-600', 'dark:text-white/60', 'border-transparent');
        btn.classList.add('bg-indigo-600', 'text-white', 'shadow-lg', 'shadow-indigo-500/30', 'border-indigo-500');
        
        document.getElementById('sim1-width-indicator').innerText = widthCls === 'w-full' ? '100% Lebar Layar Bebas Meregang' : widthCls === 'max-w-md' ? 'Ekspansi Terkunci di Max: 448px' : 'Ekspansi Terkunci Pada Max: 768px';
    }

    window.toggleSimClass = function(clsName, btn) {
        if(clsName === 'mx-auto') window.clsMxAuto = !window.clsMxAuto;
        if(clsName === 'px-4') window.clsPx4 = !window.clsPx4;
        
        const isOn = clsName === 'mx-auto' ? window.clsMxAuto : window.clsPx4;
        if(isOn) {
            btn.classList.replace('bg-slate-200', 'bg-indigo-600'); btn.classList.replace('dark:bg-[#0a0e17]', 'bg-indigo-600');
            btn.classList.replace('text-slate-600', 'text-white'); btn.classList.replace('dark:text-white/60', 'text-white');
            btn.classList.replace('border-transparent', 'border-indigo-500');
            btn.classList.add('shadow-[0_0_15px_rgba(79,70,229,0.3)]');
            btn.innerHTML = `${clsName}: <span class="bg-white/20 px-1.5 rounded ml-1 text-[9px] shadow-inner border border-white/20">STATUS: ON (AKTIF)</span>`;
        } else {
            btn.classList.replace('bg-indigo-600', 'bg-slate-200'); btn.classList.replace('bg-indigo-600', 'dark:bg-[#0a0e17]'); 
            btn.classList.replace('text-white', 'text-slate-600'); btn.classList.replace('text-white', 'dark:text-white/60'); 
            btn.classList.replace('border-indigo-500', 'border-transparent');
            btn.classList.remove('shadow-[0_0_15px_rgba(79,70,229,0.3)]');
            btn.innerHTML = `${clsName}: <span class="bg-slate-300 dark:bg-white/10 px-1.5 rounded ml-1 text-[9px] shadow-inner border border-slate-400 dark:border-white/20">STATUS: OFF (PASIF)</span>`;
        }
        const c = document.getElementById('sim1-target');
        c.className = `w-full h-24 sm:h-32 bg-gradient-to-b from-indigo-100 to-indigo-50 dark:from-indigo-500/20 dark:to-indigo-600/10 border-2 border-indigo-300 dark:border-indigo-400/50 rounded-xl flex flex-col items-center justify-center transition-all duration-700 ease-in-out mx-auto px-4 relative shadow-sm dark:shadow-[0_0_40px_rgba(99,102,241,0.2)] backdrop-blur-sm ${window.clsWidth} ${window.clsMxAuto ? 'mx-auto' : 'ml-0'} ${window.clsPx4 ? 'px-4' : 'px-0'}`;
        document.getElementById('sim1-pad-l').style.opacity = window.clsPx4 ? '1' : '0'; document.getElementById('sim1-pad-r').style.opacity = window.clsPx4 ? '1' : '0';
    }

    /* --- SIMULATOR 2: FLOAT & CLEAR --- */
    window.sim2Float = 'left';
    window.sim2Text = 'short';
    window.sim2Clear = false;

    window.simFloat = function(dir, btn) {
        window.sim2Float = dir;
        const el = document.getElementById('float-img');
        el.className = `w-16 h-16 sm:w-24 sm:h-24 bg-gradient-to-br from-orange-400 to-red-600 rounded-xl mb-2 flex items-center justify-center text-white font-black text-xs transition-all duration-500 shadow-[0_10px_20px_rgba(239,68,68,0.4)] border-2 border-white/30 transform hover:-rotate-3 hover:scale-105 cursor-default mt-1 float-${dir} ${dir==='left'?'mr-4 ml-0':'ml-4 mr-0'}`;
        
        document.querySelectorAll('.float-btn').forEach(b => {
            b.classList.remove('bg-gradient-to-b', 'from-orange-500', 'to-orange-600', 'text-white', 'border-orange-400', 'shadow-[0_0_10px_rgba(249,115,22,0.3)]');
            b.classList.add('bg-slate-200', 'dark:bg-transparent', 'text-slate-600', 'dark:text-white/50', 'border-transparent');
        });
        btn.classList.remove('bg-slate-200', 'dark:bg-transparent', 'text-slate-600', 'dark:text-white/50', 'border-transparent', 'hover:bg-slate-300', 'dark:hover:bg-white/10');
        btn.classList.add('bg-gradient-to-b', 'from-orange-500', 'to-orange-600', 'text-white', 'border-orange-400', 'shadow-[0_0_10px_rgba(249,115,22,0.3)]');
    };

    window.simText = function(len, btn) {
        window.sim2Text = len;
        const txt = document.getElementById('float-txt');
        txt.innerHTML = len === 'short' 
            ? `<span class="text-slate-900 dark:text-white font-black text-2xl float-left mr-1.5 leading-none mt-1.5 transition-colors drop-shadow-sm font-sans">P</span>aragraf pendek ini mendemonstrasikan kecacatan layout kuno. Jika teks pendek, wadah induk otomatis akan kolaps dan gagal menyelimuti gambar.` 
            : `<span class="text-slate-900 dark:text-white font-black text-2xl float-left mr-1.5 leading-none mt-1.5 transition-colors drop-shadow-sm font-sans">P</span>aragraf panjang ini sengaja ditambahkan untuk membanjiri ruang. Ketika teks meluap melebihi tinggi gambar, wadah induk seolah-olah terlihat normal. Namun ini hanyalah kebetulan semata. Tanpa penawar struktural yang tepat, desain Anda rawan hancur berantakan ketika layar diakses oleh perangkat dengan lebar berbeda.`;
        
        document.querySelectorAll('.txt-btn').forEach(b => { 
            b.classList.remove('bg-slate-300', 'dark:bg-white/10', 'text-slate-900', 'dark:text-white', 'shadow-sm'); 
            b.classList.add('bg-slate-200', 'dark:bg-transparent', 'text-slate-600', 'dark:text-white/60'); 
        });
        btn.classList.remove('bg-slate-200', 'dark:bg-transparent', 'text-slate-600', 'dark:text-white/60', 'hover:bg-slate-300', 'dark:hover:bg-white/10'); 
        btn.classList.add('bg-slate-300', 'dark:bg-white/10', 'text-slate-900', 'dark:text-white', 'shadow-sm');
        
        simClearfix(window.sim2Clear, document.querySelector(window.sim2Clear ? '.clr-btn:last-child' : '.clr-btn:first-child'));
    }
    
    window.simClearfix = function(state, btn) {
        window.sim2Clear = state;
        const parent = document.getElementById('float-parent');
        const label = document.getElementById('parent-label');
        
        document.querySelectorAll('.clr-btn').forEach(b => { 
            b.className = "clr-btn flex-1 lg:flex-none px-3 py-1.5 bg-slate-200 dark:bg-transparent text-slate-600 dark:text-white/50 border border-transparent font-bold text-[9px] rounded hover:bg-slate-300 dark:hover:bg-white/10 transition focus:outline-none shrink-0 uppercase tracking-wide w-1/2 lg:w-auto"; 
        });
        
        if(state) {
            btn.className = "clr-btn flex-1 lg:flex-none px-3 py-1.5 bg-emerald-600 text-white font-bold text-[9px] rounded border border-emerald-400 shadow-[0_0_10px_rgba(16,185,129,0.4)] transition focus:outline-none shrink-0 hover:-translate-y-0.5 uppercase tracking-wide w-1/2 lg:w-auto";
            parent.className = "border-2 border-dashed border-emerald-400 dark:border-emerald-500/80 p-3 sm:p-4 rounded-lg transition-all duration-700 bg-emerald-50 dark:bg-emerald-500/10 relative min-h-[120px] flow-root";
            label.className = "absolute -top-3 left-4 bg-slate-50 dark:bg-[#1c1c1e] px-2 text-[8px] sm:text-[9px] font-mono font-bold text-emerald-600 dark:text-emerald-400 tracking-widest transition-colors shadow-sm rounded border border-emerald-300 dark:border-emerald-500/50 uppercase";
            label.innerText = "BFC AKTIF (WADAH TERKUNCI)";
        } else {
            btn.className = "clr-btn flex-1 lg:flex-none px-3 py-1.5 bg-rose-600 text-white font-bold text-[9px] rounded border border-rose-400 shadow-[0_0_10px_rgba(225,29,72,0.4)] transition focus:outline-none shrink-0 hover:-translate-y-0.5 uppercase tracking-wide w-1/2 lg:w-auto";
            parent.className = "border-2 border-dashed border-red-400 dark:border-red-500/80 p-3 sm:p-4 rounded-lg transition-all duration-700 bg-red-50 dark:bg-red-500/5 relative min-h-[120px]";
            label.className = "absolute -top-3 left-4 bg-slate-50 dark:bg-[#1c1c1e] px-2 text-[8px] sm:text-[9px] font-mono font-bold text-red-600 dark:text-red-400 tracking-widest transition-colors shadow-sm rounded border border-red-200 dark:border-red-500/40 uppercase";
            label.innerText = window.sim2Text === 'short' ? "AWAS: KEBOCORAN WADAH" : "BOCOR TAPI TERSEMBUNYI";
        }
    }

    /* --- SIMULATOR 3: Z-INDEX --- */
    window.simPos = 'relative';
    window.simPosition = function(val, btn) {
        window.simPos = val;
        document.querySelectorAll('.sim3-pos').forEach(b => {
            b.className = "sim3-pos flex-1 px-3 py-1.5 text-[9px] font-bold rounded bg-slate-200 dark:bg-transparent text-slate-600 dark:text-white/50 hover:bg-slate-300 dark:hover:bg-white/5 transition border border-transparent focus:outline-none uppercase tracking-wide";
        });
        if(val === 'relative') {
            btn.className = "sim3-pos flex-1 px-3 py-1.5 text-[9px] font-bold rounded bg-blue-600 text-white border border-blue-500 shadow-[0_0_10px_rgba(59,130,246,0.4)] transition focus:outline-none hover:-translate-y-0.5 uppercase tracking-wide";
        } else {
            btn.className = "sim3-pos flex-1 px-3 py-1.5 text-[9px] font-bold rounded bg-rose-600 text-white border border-rose-500 shadow-[0_0_10px_rgba(225,29,72,0.4)] transition focus:outline-none hover:-translate-y-0.5 uppercase tracking-wide";
        }
        const parent = document.getElementById('sim3-parent');
        const label = document.getElementById('sim3-label');
        if(val === 'relative') {
            parent.className = "w-48 h-32 sm:w-56 sm:h-40 border-2 border-dashed border-blue-400 dark:border-blue-500/80 bg-blue-50 dark:bg-blue-500/5 rounded-xl relative transition-all duration-700 flex items-center justify-center transform-style-3d rotate-x-12 rotate-y-6 shadow-xl dark:shadow-[0_0_50px_rgba(59,130,246,0.15)] z-10 backdrop-blur-sm";
            label.innerText = "WADAH RELATIVE AKTIF (JANGKAR)"; label.className = "absolute -top-3 left-4 bg-slate-100 dark:bg-[#111112] px-2 py-0.5 text-[8px] font-mono font-bold text-blue-600 dark:text-blue-400 tracking-widest transition-colors shadow-sm border border-blue-200 dark:border-blue-500/30 rounded uppercase";
        } else {
            parent.className = "w-48 h-32 sm:w-56 sm:h-40 border-2 border-dashed border-rose-400 dark:border-rose-500/80 bg-rose-50 dark:bg-rose-500/5 rounded-xl static transition-all duration-700 flex items-center justify-center transform-style-3d rotate-x-0 rotate-y-0 shadow-none z-10 backdrop-blur-sm";
            label.innerText = "WADAH STATIC (BOCOR FATAL!)"; label.className = "absolute -top-3 left-4 bg-slate-100 dark:bg-[#111112] px-2 py-0.5 text-[8px] font-mono font-bold text-rose-600 dark:text-rose-400 tracking-widest transition-colors shadow-sm border border-rose-200 dark:border-rose-500/30 rounded uppercase";
        }
        simZIndex('green'); 
    }

    window.simZIndex = function(color) {
        const r = document.getElementById('sim3-red'); const lr = document.getElementById('lbl-z-red');
        const b = document.getElementById('sim3-blue'); const lb = document.getElementById('lbl-z-blue');
        const g = document.getElementById('sim3-green'); const lg = document.getElementById('lbl-z-green');

        const sStatic = window.simPos === 'static' ? '-translate-y-20 -translate-x-20 opacity-40 shadow-none grayscale filter' : '';
        const baseR = `absolute -top-3 -left-3 w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-[0_10px_20px_rgba(220,38,38,0.3)] border border-red-400 p-1.5 flex items-end justify-end transition-all duration-700 ease-out z-10 transform hover:scale-105 ${sStatic}`;
        const baseB = `absolute top-3 left-3 w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-[0_10px_20px_rgba(37,99,235,0.3)] border border-blue-400 p-1.5 flex items-end justify-end transition-all duration-700 ease-out z-20 transform hover:scale-105 ${sStatic}`;
        const baseG = `absolute top-10 left-10 sm:top-12 sm:left-12 w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-xl shadow-[0_10px_20px_rgba(16,185,129,0.3)] border border-emerald-300 p-1.5 flex items-end justify-end transition-all duration-700 ease-out z-30 transform hover:scale-105 ${sStatic}`;

        r.className = baseR; lr.innerText = "z-10"; lr.className = "text-[8px] sm:text-[9px] font-bold bg-white/90 dark:bg-black/50 text-red-900 dark:text-white px-1.5 py-0.5 rounded shadow-sm dark:shadow-inner transition-colors uppercase tracking-widest";
        b.className = baseB; lb.innerText = "z-20"; lb.className = "text-[8px] sm:text-[9px] font-bold bg-white/90 dark:bg-black/50 text-blue-900 dark:text-white px-1.5 py-0.5 rounded shadow-sm dark:shadow-inner transition-colors uppercase tracking-widest";
        g.className = baseG; lg.innerText = "z-30"; lg.className = "text-[8px] sm:text-[9px] font-bold bg-white/90 dark:bg-black/50 text-emerald-900 dark:text-white px-1.5 py-0.5 rounded shadow-sm dark:shadow-inner transition-colors uppercase tracking-widest";

        if(window.simPos === 'static') return; 

        if(color === 'red') { r.classList.replace('z-10', 'z-50'); r.classList.replace('shadow-[0_10px_20px_rgba(220,38,38,0.3)]', 'shadow-[0_20px_40px_rgba(220,38,38,0.6)]'); lr.innerText = "Z-50 (PUNCAK)"; lr.classList.replace('text-red-900', 'text-red-700'); lr.classList.replace('dark:text-white', 'dark:text-red-300'); lr.classList.replace('bg-white/90', 'bg-white'); lr.classList.replace('dark:bg-black/50', 'dark:bg-red-900/80'); lr.classList.add('animate-pulse'); }
        if(color === 'blue') { b.classList.replace('z-20', 'z-50'); b.classList.replace('shadow-[0_10px_20px_rgba(37,99,235,0.3)]', 'shadow-[0_20px_40px_rgba(37,99,235,0.6)]'); lb.innerText = "Z-50 (PUNCAK)"; lb.classList.replace('text-blue-900', 'text-blue-700'); lb.classList.replace('dark:text-white', 'dark:text-blue-300'); lb.classList.replace('bg-white/90', 'bg-white'); lb.classList.replace('dark:bg-black/50', 'dark:bg-blue-900/80'); lb.classList.add('animate-pulse'); }
        if(color === 'green') { g.classList.replace('z-30', 'z-50'); g.classList.replace('shadow-[0_10px_20px_rgba(16,185,129,0.3)]', 'shadow-[0_20px_40px_rgba(16,185,129,0.6)]'); lg.innerText = "Z-50 (PUNCAK)"; lg.classList.replace('text-emerald-900', 'text-emerald-700'); lg.classList.replace('dark:text-white', 'dark:text-emerald-300'); lg.classList.replace('bg-white/90', 'bg-white'); lg.classList.replace('dark:bg-black/50', 'dark:bg-emerald-900/80'); lg.classList.add('animate-pulse'); }
    }

    /* --- SIMULATOR 4: TABLE INJECT --- */
    window.simTableInject = function(btn) {
        btn.innerHTML = '<span class="animate-pulse flex items-center gap-2 text-[10px] tracking-wide"><svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg> Menyuntikkan Payload...</span>';
        btn.disabled = true;
        setTimeout(() => {
            document.getElementById('sim4-auto-id').classList.replace('text-cyan-700', 'text-rose-600');
            document.getElementById('sim4-auto-id').classList.replace('dark:text-cyan-100', 'dark:text-rose-500');
            document.getElementById('sim4-auto-id').classList.add('line-through', 'decoration-rose-500');
            
            const els = document.querySelectorAll('.sim4-data');
            els.forEach(el => {
                el.innerText = "Peringatan Darurat: Rangkaian kalimat sangat padat ini diteruskan secara mendadak dari server backend untuk menguji ketahanan struktur sel tabel. Jika Anda membiarkan tabel dalam mode otomatis yang lambat, rentetan beban teks asinkron ini akan mendobrak rasio kolom menjadi amblas seketika dan memicu guncangan Layout Shift yang akan sangat merusak estetika dashboard Anda.";
                if(el.classList.contains('truncate')) {
                    el.classList.replace('text-slate-500', 'text-emerald-700'); el.classList.replace('dark:text-white/50', 'dark:text-emerald-300'); 
                    el.classList.replace('bg-emerald-50', 'bg-emerald-100'); el.classList.replace('dark:bg-emerald-500/5', 'dark:bg-emerald-900/20');
                } else {
                    el.classList.replace('text-slate-500', 'text-red-700'); el.classList.replace('dark:text-white/50', 'dark:text-red-300'); 
                    el.classList.replace('bg-white', 'bg-red-50'); el.classList.replace('dark:bg-white/5', 'dark:bg-red-900/20');
                }
            });
            btn.innerHTML = "Injeksi API Selesai. Pengamatan Dimulai!";
            btn.classList.replace('from-pink-600', 'from-emerald-600'); btn.classList.replace('to-rose-600', 'to-emerald-500'); btn.classList.replace('border-pink-400', 'border-emerald-400');
            btn.classList.replace('shadow-[0_0_10px_rgba(244,63,94,0.4)]', 'shadow-[0_0_10px_rgba(16,185,129,0.4)]');
        }, 1200);
    }

    /* --- EXPERT ACTIVITY (MONACO) --- */
    let editor;
    const starterCode = `<div class="bg-slate-900 min-h-screen w-full font-sans text-slate-200 relative">
  
  <header id="hologram-nav" class="bg-slate-800/80 backdrop-blur-md border-b border-white/10 p-4">
    <div class="flex justify-between items-center max-w-4xl mx-auto">
      <div class="font-black text-xl text-white tracking-widest">NEXUS</div>
      
      <div id="avatar-wrapper" class="w-10 h-10 bg-gradient-to-tr from-indigo-500 to-purple-500 rounded-full border-2 border-slate-700">
        <div id="alert-badge" class="w-3.5 h-3.5 bg-rose-500 rounded-full border-2 border-slate-800"></div>
      </div>
    </div>
  </header>

  <main id="main-content" class="pt-28 p-8">
    <div class="h-40 bg-slate-800/50 rounded-2xl border border-white/5 mb-6"></div>
    <div class="h-64 bg-slate-800/50 rounded-2xl border border-white/5 mb-6 text-center flex flex-col justify-center items-center opacity-50">
       <span class="font-mono text-sm tracking-widest text-indigo-400">SCROLL KE BAWAH UNTUK UJI NAVBAR</span>
    </div>
    <div class="h-96 bg-slate-800/50 rounded-2xl border border-white/5"></div>
  </main>
  
</div>`;

    function initMonaco() {
        require.config({ paths: { vs: 'https://cdn.jsdelivr.net/npm/monaco-editor@0.45.0/min/vs' } });
        require(['vs/editor/editor.main'], function () {
            
            editor = monaco.editor.create(document.getElementById('codeEditor'), {
                value: starterCode, 
                language: 'html', 
                theme: 'vs-dark',
                fontSize: window.innerWidth < 768 ? 12 : 14,
                minimap: { enabled: false }, 
                automaticLayout: true, 
                padding: { top: 16, bottom: 16 }, 
                lineNumbers: 'on',
                scrollBeyondLastLine: false,
                wordWrap: 'on',
                formatOnPaste: true,
                renderLineHighlight: 'all',
                cursorBlinking: 'smooth',
            });
            
            window.addEventListener('resize', () => { if(editor) editor.layout(); });

            updateIframePreview(starterCode);
            
            if (activityCompleted) {
                lockActivityUI();
            }
            
            editor.onDidChangeModelContent(() => {
                if(activityCompleted) return;
                const code = editor.getValue();
                updateIframePreview(code);
                validateCodeRegex(code);
            });
        });
    }

    function updateIframePreview(code) {
        const frame = document.getElementById('previewFrame');
        const content = `
        <!doctype html>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <script src="https://cdn.tailwindcss.com"><\/script>
            <style>
                body { margin: 0; padding: 0; background-color: transparent; font-family: sans-serif; }
                * { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
            </style>
        </head>
        <body class="w-full h-full">
            ${code}
        </body>
        </html>`;
        frame.srcdoc = content;
    }

    function validateCodeRegex(code) {
        let passed = 0;
        
        const navMatch = code.match(/id="hologram-nav"[^>]*class="([^"]*)"/);
        const avatarMatch = code.match(/id="avatar-wrapper"[^>]*class="([^"]*)"/);
        const badgeMatch = code.match(/id="alert-badge"[^>]*class="([^"]*)"/);
        const mainMatch = code.match(/id="main-content"[^>]*class="([^"]*)"/);

        const navCls = navMatch ? navMatch[1] : '';
        const avatarCls = avatarMatch ? avatarMatch[1] : '';
        const badgeCls = badgeMatch ? badgeMatch[1] : '';
        const mainCls = mainMatch ? mainMatch[1] : '';

        const hasPositionCoords = /(?:-top-\d+|top-0)\s+(?:-right-\d+|right-0)/.test(badgeCls) || /(?:-right-\d+|right-0)\s+(?:-top-\d+|top-0)/.test(badgeCls);

        const checks = [
            { id: 'check-nav', valid: /\b(fixed|sticky)\b/.test(navCls) && (/\bw-full\b/.test(navCls) || (/\bleft-0\b/.test(navCls) && /\bright-0\b/.test(navCls))) && /\bz-50\b/.test(navCls) },
            { id: 'check-avatar', valid: /\brelative\b/.test(avatarCls) },
            { id: 'check-badge', valid: /\babsolute\b/.test(badgeCls) && hasPositionCoords },
            { id: 'check-main', valid: /\bcontainer\b/.test(mainCls) && /\bmx-auto\b/.test(mainCls) }
        ];

        checks.forEach(c => {
            const el = document.getElementById(c.id);
            if (!el) return;
            const dot = el.querySelector('span'); 
            const textDiv = el.querySelector('div');
            
            if(c.valid) {
                textDiv.classList.add('text-emerald-500', 'dark:text-emerald-400');
                textDiv.querySelector('b').classList.add('text-emerald-600', 'dark:text-emerald-300');
                textDiv.querySelector('b').classList.remove('text-slate-800', 'dark:text-white/90');
                dot.innerHTML = '✓'; 
                dot.classList.remove('border-slate-300', 'dark:border-white/20', 'bg-slate-50', 'dark:bg-transparent');
                dot.classList.add('bg-emerald-500', 'border-transparent', 'text-white', 'shadow-[0_0_10px_rgba(16,185,129,0.5)]');
                passed++;
            } else {
                textDiv.classList.remove('text-emerald-500', 'dark:text-emerald-400');
                textDiv.querySelector('b').classList.remove('text-emerald-600', 'dark:text-emerald-300');
                textDiv.querySelector('b').classList.add('text-slate-800', 'dark:text-white/90');
                dot.innerHTML = ''; 
                dot.classList.remove('bg-emerald-500', 'border-transparent', 'text-white', 'shadow-[0_0_10px_rgba(16,185,129,0.5)]');
                dot.classList.add('border-slate-300', 'dark:border-white/20', 'bg-slate-50', 'dark:bg-transparent');
            }
        });

        document.getElementById('progressText').innerText = passed + '/4 Lolos';
        
        const btn = document.getElementById('submitExerciseBtn');
        if (passed === 4) {
            btn.disabled = false;
            btn.classList.remove('cursor-not-allowed', 'opacity-50');
            btn.innerHTML = '<span class="tracking-wide uppercase">Simpan & Selesaikan Tantangan</span><svg class="w-4 h-4 sm:w-5 sm:h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
            
            document.getElementById('progressText').classList.replace('text-indigo-700', 'text-emerald-700');
            document.getElementById('progressText').classList.replace('dark:text-indigo-300', 'dark:text-emerald-300');
            document.getElementById('progressText').classList.replace('bg-indigo-100', 'bg-emerald-100');
            document.getElementById('progressText').classList.replace('dark:bg-indigo-900/40', 'dark:bg-emerald-900/40');
            document.getElementById('progressText').classList.replace('border-indigo-200', 'border-emerald-200');
            document.getElementById('progressText').classList.replace('dark:border-indigo-500/30', 'dark:border-emerald-500/30');

        } else {
            btn.disabled = true;
            btn.classList.add('cursor-not-allowed', 'opacity-50');
            btn.innerHTML = '<span class="tracking-wide uppercase">Penuhi Syarat Validator Terlebih Dahulu</span><svg class="w-4 h-4 sm:w-5 sm:h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>';
            
            document.getElementById('progressText').classList.replace('text-emerald-700', 'text-indigo-700');
            document.getElementById('progressText').classList.replace('dark:text-emerald-300', 'dark:text-indigo-300');
            document.getElementById('progressText').classList.replace('bg-emerald-100', 'bg-indigo-100');
            document.getElementById('progressText').classList.replace('dark:bg-emerald-900/40', 'dark:bg-indigo-900/40');
            document.getElementById('progressText').classList.replace('border-emerald-200', 'border-indigo-200');
            document.getElementById('progressText').classList.replace('dark:border-emerald-500/30', 'dark:border-indigo-500/30');
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
        btn.innerHTML = '<span class="animate-pulse tracking-wide uppercase flex items-center gap-2"><svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg> Menyimpan Kemajuan...</span>'; 
        btn.disabled = true;
        
        try {
            await fetch('/activity/complete', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'Content-Type': 'application/json' }, body: JSON.stringify({ activity_id: ACTIVITY_ID, score: 100 }) });
            await saveLessonToDB(ACTIVITY_LESSON_ID); 
            activityCompleted = true;
            updateProgressUI(); 
            lockActivityUI();   
            unlockNextChapter(); 
        } catch(e) { 
            console.error(e); 
            btn.innerHTML = "Gagal Terhubung ke Server. Coba Lagi.";
            btn.disabled = false;
            btn.classList.add('shake'); setTimeout(() => btn.classList.remove('shake'), 500);
        }
    }

    function lockActivityUI() {
        document.getElementById('lockOverlay').classList.remove('hidden');
        if(editor) editor.updateOptions({ readOnly: true });
        
        const btn = document.getElementById('submitExerciseBtn'); 
        btn.innerText = "Tantangan Telah Diselesaikan & Terkunci"; 
        btn.disabled = true;
        btn.classList.remove('bg-emerald-600', 'hover:bg-emerald-500', 'border-emerald-500', 'shadow-[0_5px_15px_rgba(16,185,129,0.3)]', 'hover:shadow-[0_10px_25px_rgba(16,185,129,0.4)]');
        btn.classList.add('bg-slate-200', 'dark:bg-slate-800', 'text-slate-500', 'dark:text-slate-400', 'border-slate-300', 'dark:border-white/10', 'cursor-not-allowed', 'shadow-none');
        
        if(editor && activityCompleted) {
            editor.setValue(`<div class="bg-slate-900 min-h-screen w-full font-sans text-slate-200 relative">\n  \n  \n  <header id="hologram-nav" class="fixed top-0 z-50 w-full bg-slate-800/80 backdrop-blur-md border-b border-white/10 p-4">\n    <div class="flex justify-between items-center max-w-4xl mx-auto">\n      <div class="font-black text-xl text-white tracking-widest">NEXUS</div>\n      \n      \n      <div id="avatar-wrapper" class="relative w-10 h-10 bg-gradient-to-tr from-indigo-500 to-purple-500 rounded-full border-2 border-slate-700">\n        \n        <div id="alert-badge" class="absolute -top-1 -right-1 w-3.5 h-3.5 bg-rose-500 rounded-full border-2 border-slate-800"></div>\n      </div>\n    </div>\n  </header>\n\n  \n  <main id="main-content" class="container mx-auto pt-28 p-8">\n    <div class="h-40 bg-slate-800/50 rounded-2xl border border-white/5 mb-6"></div>\n    <div class="h-64 bg-slate-800/50 rounded-2xl border border-white/5 mb-6 text-center flex flex-col justify-center items-center opacity-50">\n       <span class="font-mono text-sm tracking-widest text-indigo-400">SCROLL KE BAWAH UNTUK UJI NAVBAR</span>\n    </div>\n    <div class="h-96 bg-slate-800/50 rounded-2xl border border-white/5"></div>\n  </main>\n  \n</div>`);
            validateCodeRegex(editor.getValue());
        }
    }

    function unlockNextChapter() {
        const btn = document.getElementById('nextChapterBtn');
        if(btn) {
            btn.classList.remove('cursor-not-allowed', 'opacity-50', 'pointer-events-none', 'text-slate-400', 'dark:text-slate-500', 'border-transparent');
            btn.classList.add('text-indigo-700', 'dark:text-indigo-400', 'hover:text-indigo-800', 'dark:hover:text-white', 'cursor-pointer', 'border-indigo-200', 'dark:border-white/10', 'hover:bg-slate-100', 'dark:hover:bg-white/5');
            document.getElementById('nextLabel').innerText = "Rute Lab Terbuka";
            document.getElementById('nextLabel').classList.remove('opacity-50');
            document.getElementById('nextLabel').classList.add('text-indigo-700', 'dark:text-indigo-400', 'opacity-100');
            const icon = document.getElementById('nextIcon');
            icon.innerHTML = `<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>`;
            icon.classList.remove('bg-slate-100', 'dark:bg-white/5', 'text-slate-400', 'dark:text-slate-500');
            icon.classList.add('bg-indigo-100', 'dark:bg-indigo-500/20', 'border-indigo-300', 'dark:border-indigo-500/40', 'text-indigo-700', 'dark:text-indigo-400', 'shadow-md', 'dark:shadow-[0_0_15px_rgba(99,102,241,0.3)]', 'group-hover:translate-x-1');
            btn.onclick = () => window.location.href = "{{ route('lab.start', ['id' => 2]) ?? '#' }}"; 
        }
    }

    /* --- SYSTEM FUNCTIONS & PERFECT SCROLL SPY --- */
    function updateProgressUI() {
        const total = window.LESSON_IDS.length;
        const done = window.LESSON_IDS.filter(id => completedSet.has(id)).length;
        const percent = Math.round((done / total) * 100);
        const bar = document.getElementById('topProgressBar');
        const label = document.getElementById('progressLabelTop');
        if(bar) bar.style.width = percent + '%';
        if(label) label.innerText = percent + '%';
        if(percent === 100 && activityCompleted) unlockNextChapter();
    }

    function markSidebarDone(lessonId) {
        const navItem = document.querySelector(`.nav-item[data-target="#section-${lessonId}"]`);
        if (navItem) {
            const dot = navItem.querySelector('.dot, .anchor-dot');
            if (dot) {
                dot.outerHTML = `<svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400 shrink-0 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>`;
            }
        }
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

    async function saveLessonToDB(id) { 
        await fetch('/lesson/complete', { 
            method: 'POST', 
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, 
            body: new URLSearchParams({ lesson_id: id }) 
        }); 
    }

    // SCROLL SPY YANG DISEMPURNAKAN (MENCEGAH "NEMPEL")
    function initSidebarScroll() {
        const m = document.getElementById('mainScroll');
        const sections = document.querySelectorAll('.lesson-section');
        const navItems = document.querySelectorAll('.nav-item');

        if (!m || sections.length === 0 || navItems.length === 0) return;

        let isClickScrolling = false;

        const syncScrollSpy = () => {
            if (isClickScrolling) return;

            let currentId = '';
            const scrollPosition = m.scrollTop + 150; 

            sections.forEach(section => {
                if (section.offsetTop <= scrollPosition) {
                    currentId = '#' + section.id;
                }
            });

            if (m.scrollTop + m.clientHeight >= m.scrollHeight - 50) {
                currentId = '#' + sections[sections.length - 1].id;
            }

            if(!currentId && sections.length > 0) {
                currentId = '#' + sections[0].id;
            }

            navItems.forEach(item => {
                const target = item.getAttribute('data-target') || item.getAttribute('href');
                
                item.classList.remove('active');
                item.className = item.className.replace(/\b(border-(cyan|blue|indigo|purple|amber|rose|emerald)-\d+|bg-(slate|white)\/\d+|bg-slate-\d+)\b/g, '').trim();
                item.classList.add('border-transparent');
                
                if (target === currentId) {
                    item.classList.add('active');
                    item.classList.remove('border-transparent');
                    
                    const isActivity = item.dataset.type === 'activity' || (item.innerText && item.innerText.toLowerCase().includes('latihan')) || (item.innerText && item.innerText.toLowerCase().includes('tugas'));
                    
                    if (isActivity) {
                        item.classList.add('border-amber-500', 'bg-slate-100', 'dark:bg-white/5');
                    } else {
                        item.classList.add('border-indigo-500', 'bg-slate-100', 'dark:bg-white/5'); 
                    }
                }
            });
        };

        let ticking = false;
        m.addEventListener('scroll', () => {
            if (!ticking) {
                window.requestAnimationFrame(() => {
                    syncScrollSpy();
                    ticking = false;
                });
                ticking = true;
            }
        });

        syncScrollSpy();

        navItems.forEach(item => {
            item.addEventListener('click', (e) => {
                const targetSelector = item.getAttribute('data-target') || item.getAttribute('href');
                if(!targetSelector) return;
                
                const targetSection = document.querySelector(targetSelector);
                if (targetSection) {
                    if(targetSelector.startsWith('#') && item.tagName === 'A') e.preventDefault();
                    
                    isClickScrolling = true;

                    m.scrollTo({
                        top: targetSection.offsetTop - 100,
                        behavior: 'smooth'
                    });

                    navItems.forEach(k => {
                        k.classList.remove('active');
                        k.className = k.className.replace(/\b(border-(cyan|blue|indigo|purple|amber|rose|emerald)-\d+|bg-(slate|white)\/\d+|bg-slate-\d+)\b/g, '').trim();
                        k.classList.add('border-transparent');
                    });
                    
                    item.classList.remove('border-transparent');
                    item.classList.add('active', 'bg-slate-100', 'dark:bg-white/5');
                    
                    const isActivity = item.dataset.type === 'activity' || (item.innerText && item.innerText.toLowerCase().includes('latihan')) || (item.innerText && item.innerText.toLowerCase().includes('tugas'));
                    if (isActivity) {
                        item.classList.add('border-amber-500');
                    } else {
                        item.classList.add('border-indigo-500');
                    }

                    setTimeout(() => {
                        isClickScrolling = false;
                        syncScrollSpy();
                    }, 800);
                }
            });
        });
    }

    function initVisualEffects(){const c=document.getElementById('stars');if(!c)return;const x=c.getContext('2d');function r(){c.width=innerWidth;c.height=innerHeight}r();window.onresize=r;let s=[];for(let i=0;i<100;i++)s.push({x:Math.random()*c.width,y:Math.random()*c.height,r:Math.random()*1.2,v:Math.random()*0.2+.1});(function a(){x.clearRect(0,0,c.width,c.height);x.fillStyle='rgba(255,255,255,.3)';s.forEach(t=>{x.beginPath();x.arc(t.x,t.y,t.r,0,6.28);x.fill();t.y+=t.v;if(t.y>c.height)t.y=0});requestAnimationFrame(a)})();}
</script>
@endsection