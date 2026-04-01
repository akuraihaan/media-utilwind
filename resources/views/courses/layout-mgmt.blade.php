@extends('layouts.landing')
@section('title', 'Bab 2.3 · Mengelola Layout Tingkat Lanjut')

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
        --accent: #6366f1; /* Indigo 500 */
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
    
    /* ANIMATIONS & 3D EFFECTS */
    #animated-bg { 
        background: radial-gradient(800px circle at 20% 20%, rgba(99,102,241,.10), transparent 40%), 
                    radial-gradient(800px circle at 80% 80%, rgba(139,92,246,.10), transparent 40%); 
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
        }
        #courseSidebar.mobile-open { left: 0; box-shadow: 10px 0 30px rgba(0,0,0,0.5); }
        #mobileOverlay { display: none; position: fixed; inset: 0; top: 64px; background: rgba(0,0,0,0.6); z-index: 30; }
        #mobileOverlay.show { display: block; }
    }
    
    /* SCROLLSPY SIDEBAR ACTIVE */
    .nav-item { display: flex; width: 100%; text-align: left; align-items: center; gap: 12px; padding: 10px 14px; font-size: 0.85rem; color: var(--text-muted); border-radius: 8px; transition: all 0.2s; position: relative; }
    .nav-item:hover { color: var(--text-main); background: var(--card-hover); }
    .nav-item.active { color: #6366f1; background: rgba(99, 102, 241, 0.05); font-weight: 600; }
    .dot { width: 6px; height: 6px; border-radius: 50%; background: #94a3b8; transition: all 0.3s; }
    .dark .dot { background: #334155; }
    .nav-item.active .dot { background: #6366f1; box-shadow: 0 0 8px #6366f1; transform: scale(1.2); }
</style>

<button id="mobileSidebarToggle" class="lg:hidden fixed bottom-6 right-6 z-50 p-4 rounded-full shadow-2xl bg-indigo-600 text-white hover:bg-indigo-500 focus:outline-none transition-transform hover:scale-110">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
    </svg>
</button>

<div id="mobileOverlay" onclick="toggleMobileSidebar()"></div>

<div id="courseRoot" class="relative h-screen bg-[#020617] text-white font-sans overflow-hidden flex flex-col selection:bg-indigo-500/30 pt-20">

    {{-- BACKGROUND COSMIC LAYER --}}
    <div class="fixed inset-0 -z-50 pointer-events-none">
        <div id="animated-bg" class="absolute inset-0 opacity-50"></div>
        <div class="absolute top-[-10%] right-[-10%] w-[600px] h-[600px] bg-indigo-900/10 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute bottom-[-10%] left-[-10%] w-[500px] h-[500px] bg-violet-900/10 rounded-full blur-[100px]"></div>
        <canvas id="stars" class="absolute inset-0 pointer-events-none"></canvas>
        <div id="cursor-glow"></div>
    </div>

    @include('layouts.partials.navbar')

    <div class="flex flex-1 overflow-hidden relative z-20">
        @include('layouts.partials.course-sidebar')

        <main id="mainScroll" class="flex-1 h-full overflow-y-auto scroll-smooth relative bg-transparent custom-scrollbar scroll-padding-top-24">
            
            {{-- STICKY HEADER --}}
            <div id="stickyHeader" class="sticky top-0 z-30 w-full bg-[#020617]/80 backdrop-blur-2xl border-b border-white/5 px-4 md:px-8 py-4 flex items-center justify-between transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500/20 to-transparent border border-indigo-500/20 flex items-center justify-center font-bold text-xs text-indigo-400 shrink-0">2.3</div>
                    <div>
                        <h1 class="text-sm font-bold text-white line-clamp-1">Mengelola Layout Lanjut</h1>
                        <p class="text-[10px] text-white/50 line-clamp-1">Container, Position & Z-Index</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <div class="hidden sm:block w-24 md:w-32 h-1.5 bg-white/10 rounded-full overflow-hidden">
                        <div id="topProgressBar" class="h-full bg-gradient-to-r from-indigo-500 to-purple-500 w-0 transition-all duration-500 shadow-[0_0_10px_#6366f1]"></div>
                    </div>
                    <span id="progressLabelTop" class="text-indigo-400 font-bold text-xs">0%</span>
                </div>
            </div>

            {{-- CONTENT WRAPPER --}}
            <div class="p-6 lg:p-16 max-w-5xl mx-auto pb-40">
                
                {{-- LEARNING OBJECTIVES --}}
                <div class="mb-24">
                    <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Tujuan Pembelajaran
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-[#1e1e1e] border border-white/5 p-5 rounded-xl flex items-start gap-4 hover:border-indigo-500/30 transition group h-full">
                            <div class="w-8 h-8 rounded bg-indigo-500/10 text-indigo-400 flex items-center justify-center shrink-0 font-bold text-xs">1</div>
                            <div><h4 class="text-sm font-bold text-white mb-1">Anatomi Container</h4><p class="text-[11px] text-white/50 leading-relaxed">Menguasai batas dimensi logis & utilitas pemusatan.</p></div>
                        </div>
                        <div class="bg-[#1e1e1e] border border-white/5 p-5 rounded-xl flex items-start gap-4 hover:border-orange-500/30 transition group h-full">
                            <div class="w-8 h-8 rounded bg-orange-500/10 text-orange-400 flex items-center justify-center shrink-0 font-bold text-xs">2</div>
                            <div><h4 class="text-sm font-bold text-white mb-1">Float & Clear</h4><p class="text-[11px] text-white/50 leading-relaxed">Penyelamatan layout kolaps via Context Formatting.</p></div>
                        </div>
                        <div class="bg-[#1e1e1e] border border-white/5 p-5 rounded-xl flex items-start gap-4 hover:border-blue-500/30 transition group h-full">
                            <div class="w-8 h-8 rounded bg-blue-500/10 text-blue-400 flex items-center justify-center shrink-0 font-bold text-xs">3</div>
                            <div><h4 class="text-sm font-bold text-white mb-1">Posisi Koordinat</h4><p class="text-[11px] text-white/50 leading-relaxed">Membobol dimensi Z & perilaku Absolute/Fixed.</p></div>
                        </div>
                        <div class="bg-[#1e1e1e] border border-white/5 p-5 rounded-xl flex items-start gap-4 hover:border-pink-500/30 transition group h-full">
                            <div class="w-8 h-8 rounded bg-pink-500/10 text-pink-400 flex items-center justify-center shrink-0 font-bold text-xs">4</div>
                            <div><h4 class="text-sm font-bold text-white mb-1">Struktur Tabel</h4><p class="text-[11px] text-white/50 leading-relaxed">Adu performa algoritma Tabel Auto vs Fixed.</p></div>
                        </div>
                        <div class="bg-gradient-to-br from-indigo-900/40 to-violet-900/40 border border-indigo-500/30 p-5 rounded-xl flex items-start gap-4 hover:shadow-[0_0_20px_rgba(99,102,241,0.2)] transition group h-full col-span-1 sm:col-span-2 md:col-span-4 cursor-default">
                            <div class="w-8 h-8 rounded bg-white/10 text-white flex items-center justify-center shrink-0 font-bold text-xs">🏁</div>
                            <div><h4 class="text-sm font-bold text-white mb-1">Final Mission</h4><p class="text-[11px] text-white/70 leading-relaxed">Live Code: Menembakkan lencana notifikasi yang terlepas dari gravitasi dokumen pada sudut Kartu Profil pengguna (Expert Mode).</p></div>
                        </div>
                    </div>
                </div>

                <article class="space-y-40">
                    
                    {{-- LESSON 41: CONTAINER & VIEWPORT --}}
                    <section id="section-41" class="lesson-section scroll-mt-32" data-lesson-id="41">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-indigo-500 pl-6">
                                <span class="text-indigo-400 font-mono text-xs uppercase tracking-widest">Lesson 2.3.1</span>
                                <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-white leading-[1.1]">
                                    Container & <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-purple-500">Logika Viewport</span>
                                </h2>
                            </div>
                            
                            <div class="space-y-6">
                                <h3 class="text-xl font-bold text-white flex items-center gap-2"><span class="w-6 h-6 rounded bg-white/10 border border-white/20 flex items-center justify-center text-[10px] text-white">A</span> Meta Tag Viewport</h3>
                                
                                <div class="grid md:grid-cols-2 gap-4">
                                    <div class="bg-rose-500/5 border border-rose-500/20 p-5 rounded-xl flex flex-col gap-2">
                                        <h4 class="text-rose-400 font-bold m-0 flex items-center gap-2 text-sm uppercase tracking-wide">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                            Ilusi Bawaan Seluler
                                        </h4>
                                        <p class="text-sm text-rose-100/70 m-0 leading-relaxed text-justify">Tanpa instruksi HTML khusus, ponsel merender web seakan layarnya lebar (980px) lalu memaksa <span class="text-rose-300 font-bold bg-rose-900/40 px-1.5 py-0.5 rounded border border-rose-500/30">Zoom Out</span>. Akibatnya tata letak mengecil tak terbaca.</p>
                                    </div>
                                    <div class="bg-emerald-500/5 border border-emerald-500/20 p-5 rounded-xl flex flex-col gap-2">
                                        <h4 class="text-emerald-400 font-bold m-0 flex items-center gap-2 text-sm uppercase tracking-wide">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Tag Resolusi Mutlak
                                        </h4>
                                        <code class="text-[10px] sm:text-[11px] text-emerald-300 bg-black/40 p-2 rounded border border-emerald-500/30 block overflow-hidden text-ellipsis shadow-inner">&lt;meta name="viewport" content="width=device-width, initial-scale=1"&gt;</code>
                                        <p class="text-sm text-emerald-100/70 m-0 leading-relaxed text-justify">Memaksa rasio render <span class="text-emerald-300 font-bold bg-emerald-900/40 px-1.5 py-0.5 rounded border border-emerald-500/30">1:1 Piksel Fisik</span>. Inilah jantung yang membuat kelas responsif Tailwind (seperti <code>md:</code>) terpicu akurat.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-6">
                                <h3 class="text-xl font-bold text-white flex items-center gap-2"><span class="w-6 h-6 rounded bg-white/10 border border-white/20 flex items-center justify-center text-[10px] text-white">B</span> Anatomi Kelas .container</h3>
                                
                                <div class="bg-[#0f172a] border border-indigo-500/30 p-5 rounded-xl text-justify text-sm md:text-base text-indigo-100/80 leading-relaxed">
                                    Di Tailwind, utilitas <code>.container</code> <span class="text-white font-bold bg-rose-500/40 px-1.5 py-0.5 rounded border border-rose-500/50 shadow-sm">TIDAK</span> otomatis rata tengah dan <span class="text-white font-bold bg-rose-500/40 px-1.5 py-0.5 rounded border border-rose-500/50 shadow-sm">TIDAK</span> memiliki bantalan margin internal. Ia murni hanya menyetel <span class="text-indigo-300 font-bold bg-indigo-900/50 px-1.5 py-0.5 rounded border border-indigo-500/40 shadow-sm">max-width</span> yang berhenti membesar sesuai titik layar.
                                </div>

                                <div class="grid sm:grid-cols-3 gap-4">
                                    <div class="bg-white/5 border border-white/10 p-5 rounded-xl flex flex-col items-center text-center gap-3">
                                        <code class="text-indigo-400 font-bold bg-indigo-500/10 px-3 py-1.5 rounded w-full shadow-inner border border-indigo-500/20 tracking-wide">container</code>
                                        <span class="text-xs text-white/60 leading-relaxed">Membatasi pelebaran maksimum agar situs web tidak merenggang jelek di monitor ultra lebar.</span>
                                    </div>
                                    <div class="bg-white/5 border border-white/10 p-5 rounded-xl flex flex-col items-center text-center gap-3">
                                        <code class="text-indigo-400 font-bold bg-indigo-500/10 px-3 py-1.5 rounded w-full shadow-inner border border-indigo-500/20 tracking-wide">mx-auto</code>
                                        <span class="text-xs text-white/60 leading-relaxed">Menghitung dan membagi sisa jarak kosong kiri-kanan sama rata, mendorong kotak pas ke titik tengah.</span>
                                    </div>
                                    <div class="bg-white/5 border border-white/10 p-5 rounded-xl flex flex-col items-center text-center gap-3">
                                        <code class="text-indigo-400 font-bold bg-indigo-500/10 px-3 py-1.5 rounded w-full shadow-inner border border-indigo-500/20 tracking-wide">px-4</code>
                                        <span class="text-xs text-white/60 leading-relaxed">Peredam benturan (padding) horizontal agar tulisan tidak menabrak bingkai fisik telepon seluler.</span>
                                    </div>
                                </div>
                            </div>

                            {{-- SIMULATOR 1 --}}
                            <div class="bg-[#0b0f19] border border-white/10 rounded-2xl p-6 lg:p-8 shadow-2xl relative group hover:border-indigo-500/30 transition-all">
                                <h4 class="text-xs font-bold text-muted uppercase mb-4 text-center transition-colors tracking-widest">Simulator: Koreografi Batas Layar</h4>
                                
                                <div class="bg-indigo-500/10 border border-indigo-500/30 rounded-xl p-4 mb-6 text-sm text-indigo-300 relative z-10 shadow-inner">
                                    <p class="font-bold flex items-center gap-2 mb-2">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Uji Coba Formulatif
                                    </p>
                                    <p class="m-0 opacity-90 leading-relaxed text-xs md:text-sm text-justify">
                                        Mainkan sakelar utilitas di bawah. Perhatikan bar merah yang memvisualisasikan <span class="text-indigo-200 font-bold bg-indigo-900/50 px-1 rounded">px-4</span>, dan bagaimana blok ungu (Container) mengunci pertumbuhannya lalu memusatkan diri akibat <span class="text-indigo-200 font-bold bg-indigo-900/50 px-1 rounded">mx-auto</span>.
                                    </p>
                                </div>

                                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6 gap-4">
                                    <div class="flex gap-2 bg-black/40 p-1.5 rounded-lg border border-white/10 shadow-inner w-full lg:w-auto overflow-x-auto custom-scrollbar">
                                        <button onclick="simContainer('w-full', this)" class="res-btn px-4 py-2 text-[10px] sm:text-xs font-bold rounded bg-indigo-600 text-white shadow-lg border border-indigo-400 transition shrink-0 focus:outline-none">Mobile (100%)</button>
                                        <button onclick="simContainer('max-w-md', this)" class="res-btn px-4 py-2 text-[10px] sm:text-xs font-bold rounded bg-white/5 text-white/50 border border-white/10 hover:bg-white/10 transition shrink-0 focus:outline-none">Tablet (md)</button>
                                        <button onclick="simContainer('max-w-2xl', this)" class="res-btn px-4 py-2 text-[10px] sm:text-xs font-bold rounded bg-white/5 text-white/50 border border-white/10 hover:bg-white/10 transition shrink-0 focus:outline-none">Desktop (xl)</button>
                                    </div>
                                    <div class="flex gap-2 w-full lg:w-auto">
                                        <button onclick="toggleSimClass('mx-auto', this)" class="flex-1 lg:flex-none px-4 py-2 text-[10px] sm:text-xs font-bold rounded bg-indigo-600 text-white border border-indigo-400 shadow-[0_0_10px_rgba(79,70,229,0.4)] transition focus:outline-none">mx-auto: ON</button>
                                        <button onclick="toggleSimClass('px-4', this)" class="flex-1 lg:flex-none px-4 py-2 text-[10px] sm:text-xs font-bold rounded bg-indigo-600 text-white border border-indigo-400 shadow-[0_0_10px_rgba(79,70,229,0.4)] transition focus:outline-none">px-4: ON</button>
                                    </div>
                                </div>
                                
                                <div class="w-full bg-black/50 h-48 sm:h-64 rounded-xl border border-white/10 relative flex items-center justify-center overflow-hidden shadow-inner p-2">
                                    <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-10 pointer-events-none"></div>
                                    
                                    <div id="sim1-target" class="w-full h-32 sm:h-40 bg-gradient-to-b from-indigo-500/20 to-indigo-600/10 border border-indigo-400/50 rounded-lg flex items-center justify-center transition-all duration-700 ease-out mx-auto px-4 relative shadow-[0_0_40px_rgba(99,102,241,0.2)]">
                                        
                                        <div id="sim1-pad-l" class="absolute top-0 bottom-0 left-0 w-4 bg-red-500/30 border-r border-dashed border-red-400 flex items-center justify-center transition-opacity"><span class="text-[8px] -rotate-90 text-white font-bold tracking-widest hidden sm:block">px-4</span></div>
                                        <div id="sim1-pad-r" class="absolute top-0 bottom-0 right-0 w-4 bg-red-500/30 border-l border-dashed border-red-400 flex items-center justify-center transition-opacity"><span class="text-[8px] rotate-90 text-white font-bold tracking-widest hidden sm:block">px-4</span></div>
                                        
                                        <div class="bg-indigo-900/80 w-full h-16 sm:h-20 rounded border border-indigo-500/50 flex flex-col items-center justify-center shadow-lg relative z-10">
                                            <span class="text-indigo-200 font-black text-sm sm:text-lg tracking-widest">KONTEN SITUS</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 42: FLOAT & CLEAR --}}
                    <section id="section-42" class="lesson-section scroll-mt-32" data-lesson-id="42">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-orange-500 pl-6">
                                <span class="text-orange-400 font-mono text-xs uppercase tracking-widest">Lesson 2.3.2</span>
                                <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-white leading-[1.1]">
                                    Legacy Layout: <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-red-500">Float & Clear</span>
                                </h2>
                            </div>

                            <div class="grid md:grid-cols-2 gap-8">
                                <div class="bg-white/5 border border-white/10 p-6 rounded-xl flex flex-col gap-3">
                                    <h4 class="font-bold text-white text-lg flex items-center gap-2 border-b border-white/10 pb-3">Tugas Terakhir Float</h4>
                                    <p class="text-sm text-white/70 leading-relaxed text-justify m-0">
                                        Di era dominasi tata letak modern, properti warisan <code>float</code> tidak lagi dipakai merakit kolom. Ia difokuskan murni pada manuver artistik: <span class="text-orange-300 font-bold bg-orange-900/40 px-1.5 py-0.5 rounded border border-orange-500/30 shadow-sm">Text Wrapping</span>.
                                    </p>
                                    <p class="text-sm text-white/70 leading-relaxed text-justify m-0">
                                        Perintah <code>float-left</code> mencabut objek gambar lalu menyandarkannya ke dinding. Teks paragraf kemudian bereaksi dengan <span class="text-orange-200 font-bold">membungkus sisi kosong objek tersebut</span>, menciptakan format bacaan padat ala koran jurnalistik.
                                    </p>
                                </div>
                                
                                <div class="bg-gradient-to-r from-red-900/20 to-transparent border-l-4 border-red-500 p-6 rounded-r-xl shadow-inner flex flex-col gap-3">
                                    <h5 class="text-red-400 font-bold text-sm flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                        Anomali Wadah Kempis
                                    </h5>
                                    <p class="text-xs sm:text-sm leading-relaxed text-red-200/80 text-justify m-0">
                                        Saat objek melayang, elemen pembungkus luarnya menjadi buta. Wadah tersebut akan <span class="text-white font-bold bg-red-500/30 px-1 rounded">Kolaps/Kempis</span> gagal membungkus anaknya.
                                    </p>
                                    <div class="bg-black/40 p-3 rounded border border-white/10 text-xs text-white/60 mt-2">
                                        <strong class="text-emerald-400 block mb-1">Clearfix Modern Tailwind:</strong>
                                        Hantamkan utilitas <code class="text-emerald-300 font-bold bg-emerald-900/40 px-1 rounded border border-emerald-500/30">flow-root</code> pada pembungkus (Parent) guna mencipta blok teritori baru yang memaksa penelanan elemen apung di dalamnya.
                                    </div>
                                </div>
                            </div>

                            {{-- SIMULATOR 2 --}}
                            <div class="bg-[#0b0f19] border border-white/10 rounded-2xl p-6 lg:p-8 shadow-2xl relative group hover:border-orange-500/30 transition-all flex flex-col">
                                <h4 class="text-xs font-bold text-muted uppercase mb-4 text-center transition-colors tracking-widest">Simulator: Anomali & Penyembuhan</h4>
                                
                                <div class="bg-orange-500/10 border border-orange-500/30 rounded-xl p-4 mb-6 text-sm text-orange-300 relative z-10 shadow-inner">
                                    <p class="font-bold flex items-center gap-2 mb-2">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                        Mekanika Text Wrap & Clearfix
                                    </p>
                                    <p class="m-0 opacity-90 leading-relaxed text-[11px] sm:text-xs text-justify">
                                        Setel Panjang Teks menjadi "Sedikit". Matikan sakelar Clearfix. Amati garis putus-putus merah (Wadah Induk) yang cacat gagal menyelimuti gambar. Lalu nyalakan kembali sakelar <span class="text-emerald-300 font-bold">flow-root</span> untuk memulihkan kerusakan dimensi.
                                    </p>
                                </div>

                                <div class="flex justify-between items-end mb-4">
                                    <div class="flex gap-1.5 bg-black/40 p-1.5 rounded-lg border border-white/10 shadow-inner overflow-x-auto custom-scrollbar w-full sm:w-auto">
                                        <button onclick="simFloat('left', this)" class="float-btn px-3 py-1.5 bg-gradient-to-b from-orange-500 to-orange-600 text-white border-orange-400 shadow-[0_0_10px_rgba(249,115,22,0.3)] font-bold text-[10px] sm:text-xs rounded transition focus:outline-none shrink-0">float-left</button>
                                        <button onclick="simFloat('right', this)" class="float-btn px-3 py-1.5 bg-white/5 text-white/50 border-white/10 font-bold text-[10px] sm:text-xs rounded border hover:bg-white/10 transition focus:outline-none shrink-0">float-right</button>
                                        
                                        <div class="w-px bg-white/10 mx-1"></div>
                                        
                                        <button onclick="simText('short', this)" class="txt-btn px-3 py-1.5 bg-white/10 text-white border-white/10 font-bold text-[10px] sm:text-xs rounded border transition focus:outline-none shrink-0">Teks Sedikit</button>
                                        <button onclick="simText('long', this)" class="txt-btn px-3 py-1.5 bg-white/5 text-white/50 border-white/10 font-bold text-[10px] sm:text-xs rounded border hover:bg-white/10 transition focus:outline-none shrink-0">Teks Banyak</button>
                                    </div>
                                    <div class="hidden sm:flex gap-1.5 bg-black/40 p-1.5 rounded-lg border border-white/10 shadow-inner shrink-0">
                                        <button onclick="simClearfix(false, this)" class="clr-btn px-3 py-1.5 bg-red-600 text-white font-bold text-[10px] sm:text-xs rounded border border-red-400 shadow-[0_0_10px_rgba(220,38,38,0.4)] transition focus:outline-none shrink-0">No Clearfix </button>
                                        <button onclick="simClearfix(true, this)" class="clr-btn px-3 py-1.5 bg-white/5 text-white/50 border-white/10 font-bold text-[10px] sm:text-xs rounded border hover:bg-white/10 transition focus:outline-none shrink-0">flow-root (Aman)</button>
                                    </div>
                                </div>
                                <div class="flex sm:hidden gap-1.5 bg-black/40 p-1.5 rounded-lg border border-white/10 shadow-inner shrink-0 mb-4 overflow-x-auto custom-scrollbar w-full">
                                    <button onclick="simClearfix(false, this)" class="clr-btn px-3 py-1.5 bg-red-600 text-white font-bold text-[10px] sm:text-xs rounded border border-red-400 shadow-[0_0_10px_rgba(220,38,38,0.4)] transition focus:outline-none shrink-0 w-1/2">No Clearfix</button>
                                    <button onclick="simClearfix(true, this)" class="clr-btn px-3 py-1.5 bg-white/5 text-white/50 border-white/10 font-bold text-[10px] sm:text-xs rounded border hover:bg-white/10 transition focus:outline-none shrink-0 w-1/2">flow-root</button>
                                </div>
                                
                                <div class="flex-1 bg-gradient-to-b from-[#1c1c1e] to-[#111112] p-5 sm:p-8 rounded-xl border border-white/5 font-serif text-white/60 leading-relaxed text-sm sm:text-base text-justify relative shadow-inner">
                                    <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-5 pointer-events-none"></div>
                                    
                                    {{-- Wrapper Box --}}
                                    <div id="float-parent" class="border-2 border-dashed border-red-500/80 p-3 sm:p-4 rounded-lg transition-all duration-700 bg-red-500/5 relative min-h-[140px]">
                                        <div class="absolute -top-3 left-4 bg-[#1c1c1e] px-2 text-[9px] sm:text-[10px] font-mono font-bold text-red-400 tracking-widest transition-colors shadow-sm" id="parent-label">WADAH INDUK KEMPIS!</div>
                                        
                                        <div id="float-img" class="w-20 h-20 sm:w-28 sm:h-28 bg-gradient-to-br from-orange-400 to-red-600 rounded-lg mb-2 float-left mr-4 flex items-center justify-center text-white font-black text-sm transition-all duration-500 shadow-[0_5px_20px_rgba(239,68,68,0.3)] ring-1 ring-white/20">IMG</div>
                                        
                                        <div id="float-txt" class="transition-all duration-300">
                                            <span class="text-white font-black text-3xl float-left mr-1.5 leading-none mt-1">P</span>aragraf pendek ini mendemonstrasikan malapetaka CSS.
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
                                <span class="text-blue-400 font-mono text-xs uppercase tracking-widest">Lesson 2.3.3</span>
                                <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-white leading-[1.1]">
                                    Sumbu Posisi & <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-cyan-500">Z-Index Layers</span>
                                </h2>
                            </div>

                            <div class="space-y-8">
                                <div class="bg-white/5 border border-white/10 p-5 rounded-xl text-white/80 text-sm md:text-base text-justify leading-relaxed">
                                    Konsep <strong>Positioning</strong> adalah kunci membongkar Dimensi Z: menumpuk lapis antarmuka agar melayang lepas dari hukum cetak biru normal dokumen HTML Anda.
                                </div>

                                <div class="grid md:grid-cols-2 gap-4 lg:gap-6">
                                    <div class="bg-gradient-to-br from-slate-800 to-slate-900 p-5 rounded-xl border border-white/10 shadow-lg hover:border-blue-500/40 transition">
                                        <div class="flex items-center gap-3 mb-3 border-b border-white/5 pb-2">
                                            <div class="w-2.5 h-2.5 rounded-full bg-blue-400 shrink-0"></div>
                                            <code class="text-blue-300 font-bold bg-black/50 px-2 py-1 rounded shadow-inner text-sm tracking-wide">relative</code>
                                        </div>
                                        <p class="text-xs sm:text-sm leading-relaxed m-0 text-white/60">Elemen pasif yang menempati lokasi asalnya, namun energinya aktif berfungsi sebagai <span class="text-white font-bold bg-white/10 px-1 rounded">Jangkar Penahan Luar</span> bagi elemen liar <code class="text-xs">absolute</code> di dalam perutnya.</p>
                                    </div>
                                    
                                    <div class="bg-gradient-to-br from-slate-800 to-slate-900 p-5 rounded-xl border border-white/10 shadow-lg hover:border-rose-500/40 transition">
                                        <div class="flex items-center gap-3 mb-3 border-b border-white/5 pb-2">
                                            <div class="w-2.5 h-2.5 rounded-full bg-rose-400 animate-pulse shrink-0"></div>
                                            <code class="text-rose-300 font-bold bg-black/50 px-2 py-1 rounded shadow-inner text-sm tracking-wide">absolute</code>
                                        </div>
                                        <p class="text-xs sm:text-sm leading-relaxed m-0 text-white/60">Si pencabut gravitasi. Lenyap dari dokumen dan terbang menindih sekitarnya. Terus melacak ke dinding atas hingga <span class="text-rose-200 font-bold bg-rose-900/40 px-1 rounded">menabrak induk berseragam relative</span>.</p>
                                    </div>
                                    
                                    <div class="bg-gradient-to-br from-slate-800 to-slate-900 p-5 rounded-xl border border-white/10 shadow-lg hover:border-emerald-500/40 transition">
                                        <div class="flex items-center gap-3 mb-3 border-b border-white/5 pb-2">
                                            <div class="w-2.5 h-2.5 border-2 border-emerald-400 shrink-0"></div>
                                            <code class="text-emerald-300 font-bold bg-black/50 px-2 py-1 rounded shadow-inner text-sm tracking-wide">fixed</code>
                                        </div>
                                        <p class="text-xs sm:text-sm leading-relaxed m-0 text-white/60">Paku bumi layar monitor (<span class="text-white font-bold bg-white/10 px-1 rounded">viewport</span>). Beku di posisi layarnya dan imun terhadap manipulasi roda gulir (*scroll* mouse).</p>
                                    </div>
                                    
                                    <div class="bg-gradient-to-br from-slate-800 to-slate-900 p-5 rounded-xl border border-white/10 shadow-lg hover:border-amber-500/40 transition">
                                        <div class="flex items-center gap-3 mb-3 border-b border-white/5 pb-2">
                                            <div class="w-2.5 h-2.5 bg-amber-400 rotate-45 shrink-0"></div>
                                            <code class="text-amber-300 font-bold bg-black/50 px-2 py-1 rounded shadow-inner text-sm tracking-wide">sticky</code>
                                        </div>
                                        <p class="text-xs sm:text-sm leading-relaxed m-0 text-white/60">Hibrida langka. Beraksi normal layaknya balok biasa, lalu bermutasi wujud menjadi <span class="text-amber-200 font-bold bg-amber-900/40 px-1 rounded">fixed</span> seketika mahkotanya berbenturan dengan garis atap layar.</p>
                                    </div>
                                </div>

                                <div class="bg-blue-900/20 border-l-4 border-blue-500 p-5 rounded-r-xl shadow-inner">
                                    <h4 class="text-blue-400 font-bold text-sm mb-3 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                        Hukum Z-Index Layers
                                    </h4>
                                    <ul class="list-none pl-0 space-y-2.5 m-0">
                                        <li class="flex items-start gap-3 text-xs sm:text-sm text-blue-100/80"><div class="mt-1 w-1.5 h-1.5 rounded-full bg-blue-400 shrink-0"></div> <p class="m-0">Kode <code class="text-blue-300 font-bold bg-black/40 px-1.5 rounded border border-blue-500/30">z-{0-50}</code> memegang palu lelang penentuan juara hierarki lembaran depan (*stacking context*).</p></li>
                                        <li class="flex items-start gap-3 text-xs sm:text-sm text-blue-100/80"><div class="mt-1 w-1.5 h-1.5 rounded-full bg-rose-500 shrink-0"></div> <p class="m-0"><strong>Kelemahan Mutlak:</strong> Sihir indeks Z mati tak berguna jika elemen berada dalam struktur kasta <code class="text-white font-bold bg-rose-500/30 px-1 rounded">static</code> bawaan lahir.</p></li>
                                    </ul>
                                </div>
                            </div>

                            {{-- SIMULATOR 3 --}}
                            <div class="bg-[#0b0f19] border border-white/10 rounded-2xl p-6 lg:p-8 shadow-2xl relative overflow-hidden group">
                                <h4 class="text-xs font-bold text-muted uppercase mb-4 text-center transition-colors tracking-widest">Simulator: 3D Stacking Context</h4>
                                
                                <div class="bg-blue-500/10 border border-blue-500/30 rounded-xl p-4 mb-6 flex flex-col sm:flex-row gap-4 items-center shadow-inner relative z-20">
                                    <div class="w-10 h-10 bg-blue-500/20 rounded-full flex items-center justify-center shrink-0"><svg class="w-5 h-5 text-blue-300 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"></path></svg></div>
                                    <div>
                                        <p class="font-bold text-blue-200 text-xs sm:text-sm mb-1 uppercase tracking-wider">Misi Eksplorasi Dimensi</p>
                                        <p class="m-0 text-[11px] sm:text-xs text-blue-200/70">1. Alihkan Induk ke <span class="text-white font-bold">static</span>, rasakan bagaimana anak terbang bocor tanpa rantai pengikat!<br>2. Kembalikan ke <span class="text-white font-bold">relative</span>, lalu letupkan tombol lapisan di bawah mendobrak posisi ke barisan layar utama (<span class="font-bold text-white">z-50</span>).</p>
                                    </div>
                                </div>

                                <div class="flex flex-col md:flex-row gap-3 justify-between mb-8 bg-black/40 p-2 rounded-lg border border-white/5 relative z-20">
                                    <div class="flex gap-1 items-center flex-wrap sm:flex-nowrap">
                                        <span class="text-[9px] text-white/40 uppercase font-bold mr-1 hidden sm:block">Status Induk:</span>
                                        <button onclick="simPosition('relative', this)" class="sim3-pos px-3 py-1.5 text-[10px] sm:text-xs font-bold rounded bg-blue-600 text-white border border-blue-400 shadow-[0_0_10px_rgba(59,130,246,0.4)] transition shrink-0 focus:outline-none">Relative</button>
                                        <button onclick="simPosition('static', this)" class="sim3-pos px-3 py-1.5 text-[10px] sm:text-xs font-bold rounded bg-transparent text-white/50 hover:bg-white/5 transition border border-transparent shrink-0 focus:outline-none">Static (Bocor)</button>
                                    </div>
                                    <div class="flex gap-1 items-center md:border-l border-white/10 md:pl-3 flex-wrap sm:flex-nowrap">
                                        <span class="text-[9px] text-white/40 uppercase font-bold mr-1 hidden sm:block">Dominasi Depan:</span>
                                        <button onclick="simZIndex('red')" class="px-3 py-1.5 text-[10px] sm:text-xs font-bold rounded bg-red-600/50 hover:bg-red-500 text-white transition border border-red-500/50 shrink-0 focus:outline-none">Merah</button>
                                        <button onclick="simZIndex('blue')" class="px-3 py-1.5 text-[10px] sm:text-xs font-bold rounded bg-blue-600/50 hover:bg-blue-500 text-white transition border border-blue-500/50 shrink-0 focus:outline-none">Biru</button>
                                        <button onclick="simZIndex('green')" class="px-3 py-1.5 text-[10px] sm:text-xs font-bold rounded bg-emerald-600/50 hover:bg-emerald-500 text-white transition border border-emerald-500/50 shrink-0 focus:outline-none">Hijau</button>
                                    </div>
                                </div>
                                
                                <div class="w-full bg-[#111112] h-[320px] rounded-xl border border-white/5 relative flex items-center justify-center overflow-hidden perspective-1000 shadow-inner">
                                    <div class="absolute inset-0 bg-[linear-gradient(45deg,transparent_25%,rgba(255,255,255,0.05)_50%,transparent_75%,transparent_100%)] bg-[length:20px_20px] pointer-events-none"></div>
                                    
                                    {{-- Relative Parent Anchor --}}
                                    <div id="sim3-parent" class="w-56 h-40 sm:w-64 sm:h-48 border-2 border-dashed border-blue-500 bg-blue-500/10 rounded-xl relative transition-all duration-700 flex items-center justify-center transform-style-3d rotate-x-12 rotate-y-6">
                                        <div class="absolute -top-3 left-4 bg-[#111112] px-2 text-[9px] font-mono font-bold text-blue-400 tracking-widest transition-colors shadow-sm" id="sim3-label">WADAH RELATIVE</div>
                                        
                                        {{-- Absolute Children --}}
                                        <div id="sim3-red" class="absolute -top-4 -left-4 w-20 h-20 sm:w-24 sm:h-24 bg-red-600/90 rounded-lg shadow-[0_10px_20px_rgba(220,38,38,0.3)] border border-red-400 p-2 flex items-end justify-end transition-all duration-700 ease-out z-10"><span class="text-[9px] sm:text-[10px] font-bold bg-black/40 px-1 rounded shadow-inner" id="lbl-z-red">z-10</span></div>
                                        <div id="sim3-blue" class="absolute top-4 left-4 w-20 h-20 sm:w-24 sm:h-24 bg-blue-600/90 rounded-lg shadow-[0_10px_20px_rgba(37,99,235,0.3)] border border-blue-400 p-2 flex items-end justify-end transition-all duration-700 ease-out z-20"><span class="text-[9px] sm:text-[10px] font-bold bg-black/40 px-1 rounded shadow-inner" id="lbl-z-blue">z-20</span></div>
                                        <div id="sim3-green" class="absolute top-12 left-12 w-20 h-20 sm:w-24 sm:h-24 bg-emerald-500/90 rounded-lg shadow-[0_10px_20px_rgba(16,185,129,0.3)] border border-emerald-300 p-2 flex items-end justify-end transition-all duration-700 ease-out z-30"><span class="text-[9px] sm:text-[10px] font-bold bg-black/40 px-1 rounded shadow-inner" id="lbl-z-green">z-30</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 44: TABLE LAYOUTS --}}
                    <section id="section-44" class="lesson-section scroll-mt-32" data-lesson-id="44">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-pink-500 pl-6">
                                <span class="text-pink-400 font-mono text-xs uppercase tracking-widest">Lesson 2.3.4</span>
                                <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-white leading-[1.1]">
                                    Table Layout: <br> 
                                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-pink-400 to-rose-500">Auto vs Fixed</span>
                                </h2>
                            </div>

                            <div class="grid md:grid-cols-2 gap-6">
                                {{-- Table Auto --}}
                                <div class="bg-white/5 p-6 rounded-xl border border-white/10 hover:border-cyan-500/30 transition">
                                    <div class="flex justify-between items-center mb-4 border-b border-white/5 pb-3">
                                        <code class="text-cyan-400 font-bold bg-cyan-500/10 px-2 py-1 rounded-md shadow-inner tracking-wide">.table-auto</code>
                                        <span class="text-[10px] font-bold text-white/50 uppercase tracking-widest bg-black/30 px-2 py-0.5 rounded">Default Tunduk</span>
                                    </div>
                                    <p class="text-sm text-white/70 mb-4 leading-relaxed text-justify">
                                        Kolom dikendalikan paksa oleh muatan kalimat terpanjang. Browser tersandera merender super telat karena harus mendeteksi total seluruh data triliunan di perut barisnya.
                                    </p>
                                    <div class="bg-red-500/10 p-3 rounded-lg border border-red-500/20 text-xs text-red-200/80 mt-auto">
                                        <strong class="text-red-400 block mb-1">Cacat Sistem:</strong> Rentan mengakibatkan lompatan tatanan brutal (*layout shifting*) setiap kali diinjeksi muatan data API baru.
                                    </div>
                                </div>

                                {{-- Table Fixed --}}
                                <div class="bg-white/5 p-6 rounded-xl border border-white/10 hover:border-pink-500/30 transition">
                                    <div class="flex justify-between items-center mb-4 border-b border-white/5 pb-3">
                                        <code class="text-pink-400 font-bold bg-pink-500/10 px-2 py-1 rounded-md shadow-inner tracking-wide">.table-fixed</code>
                                        <span class="text-[10px] font-bold text-emerald-400 bg-emerald-900/30 border border-emerald-500/30 px-2 py-0.5 rounded uppercase tracking-widest flex items-center gap-1">🔥 Turbo</span>
                                    </div>
                                    <p class="text-sm text-white/70 mb-4 leading-relaxed text-justify">
                                        Dimensi kebal dari intimidasi paragraf. Perintah rasio patuh mutlak pada deklarasi leher Header (contoh: <code class="text-pink-200 font-bold bg-pink-900/40 px-1 rounded">w-1/4</code>). Browser mengecat kanvas secepat kedipan.
                                    </p>
                                    <div class="bg-emerald-500/10 p-3 rounded-lg border border-emerald-500/20 text-xs text-emerald-100/90 mt-auto">
                                        <strong class="text-emerald-400 block mb-1">Mekanika Estetis:</strong> Teks raksasa yang menerobos dinding wilayah terhukum potong oleh penggalan pisaunya <code class="text-white font-bold bg-black/40 px-1 rounded border border-white/10">truncate</code>.
                                    </div>
                                </div>
                            </div>

                            {{-- SIMULATOR 4 --}}
                            <div class="bg-[#0b0f19] border border-white/10 rounded-2xl p-6 lg:p-8 shadow-2xl relative group hover:border-pink-500/30 transition-all flex flex-col">
                                <h4 class="text-xs font-bold text-muted uppercase mb-4 text-center transition-colors tracking-widest">Simulator: Adu Mekanik Rendering</h4>
                                
                                <div class="bg-pink-500/10 border border-pink-500/30 rounded-xl p-4 mb-6 flex flex-col md:flex-row gap-4 items-center justify-between shadow-inner">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 bg-pink-500/20 rounded-full flex items-center justify-center shrink-0 border border-pink-500/30"><svg class="w-5 h-5 text-pink-300 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg></div>
                                        <div>
                                            <p class="font-bold text-pink-200 text-xs sm:text-sm mb-1 uppercase tracking-wider">Tes Tekanan Berat</p>
                                            <p class="m-0 text-[11px] sm:text-xs text-pink-200/70 text-justify max-w-xl">Hancurkan kemapanan tabel di bawah dengan menembakkan muatan data teks ekstra panjang secara brutal. Amati siapa yang selamat mempertahankan rasionya.</p>
                                        </div>
                                    </div>
                                    <button onclick="simTableInject(this)" class="px-5 py-2.5 bg-gradient-to-r from-pink-600 to-rose-600 hover:from-pink-500 hover:to-rose-500 text-white font-bold text-[11px] sm:text-xs rounded-lg shadow-[0_0_15px_rgba(244,63,94,0.4)] transition active:scale-95 shrink-0 w-full md:w-auto border border-pink-400 focus:outline-none">Injeksi Muatan API</button>
                                </div>

                                <div class="grid lg:grid-cols-2 gap-6 relative z-10">
                                    {{-- Table Auto Target --}}
                                    <div class="bg-[#111112] rounded-xl border border-white/5 overflow-hidden shadow-inner flex flex-col">
                                        <div class="bg-cyan-900/20 p-3 border-b border-cyan-500/20 text-center"><code class="text-xs text-cyan-400 font-bold tracking-wide">.table-auto</code></div>
                                        <table class="table-auto w-full text-xs text-left text-white/70">
                                            <thead class="bg-white/5 border-b border-white/10"><tr><th class="p-4 text-cyan-200">ID Sesi</th><th class="p-4 text-cyan-200">Laporan Server</th></tr></thead>
                                            <tbody>
                                                <tr>
                                                    <td class="p-4 border-r border-white/5 whitespace-nowrap text-cyan-100 font-mono" id="sim4-auto-id">AX-001</td>
                                                    <td class="p-4 text-white/50 sim4-data transition-all duration-300 bg-white/5 leading-relaxed">Paket sistem aman dirender.</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    {{-- Table Fixed Target --}}
                                    <div class="bg-[#111112] rounded-xl border border-white/5 overflow-hidden shadow-inner flex flex-col">
                                        <div class="bg-pink-900/20 p-3 border-b border-pink-500/20 text-center"><code class="text-xs text-pink-400 font-bold tracking-wide">.table-fixed</code></div>
                                        <table class="table-fixed w-full text-xs text-left text-white/70">
                                            <thead class="bg-white/5 border-b border-white/10"><tr><th class="p-4 w-1/4 text-pink-200">w-1/4</th><th class="p-4 w-3/4 text-pink-200">w-3/4</th></tr></thead>
                                            <tbody>
                                                <tr>
                                                    <td class="p-4 border-r border-white/5 whitespace-nowrap text-pink-100 font-mono">AX-001</td>
                                                    <td class="p-4 text-white/50 truncate sim4-data transition-all duration-300 bg-emerald-500/5 leading-relaxed">Paket sistem aman dirender.</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- FINAL MISSION (EXPERT MODE) --}}
                    <section id="section-45" class="lesson-section scroll-mt-24 pt-10 border-t border-white/10" data-lesson-id="45" data-type="activity">
                        <div class="relative rounded-[2rem] bg-[#050b14] border border-white/10 p-6 md:p-10 shadow-2xl flex flex-col group hover:border-indigo-500/30 transition-all duration-500 overflow-hidden">
                            <div class="absolute -top-24 -right-24 w-64 h-64 bg-indigo-600/10 blur-[100px] rounded-full pointer-events-none"></div>

                            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 sm:gap-6 mb-8 relative z-10 shrink-0">
                                <div class="p-3 sm:p-4 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-xl text-white shadow-[0_0_20px_rgba(99,102,241,0.3)] border border-indigo-400 shrink-0">
                                    <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                                </div>
                                <div>
                                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-3 mb-2">
                                        <h2 class="text-xl sm:text-3xl font-black text-white tracking-tight">Final Mission: Notifikasi Lencana</h2>
                                        <span class="px-2 py-0.5 rounded text-[9px] sm:text-[10px] font-bold bg-rose-500/20 text-rose-400 border border-rose-500/30 uppercase tracking-wider shadow-sm">Expert Mode</span>
                                    </div>
                                    <p class="text-indigo-100/70 text-xs sm:text-sm mt-1 mb-4 leading-relaxed text-justify max-w-3xl">
                                        Uji pamungkas dominasi hierarki dimensi Z Anda! Eksekusi formasi taktis penjangkaran pada wadah utama profil, lalu lentingkan kubus merah notifikasi secara paksa menyudut akurat di koordinat kanan atas.
                                    </p>

                                    {{-- TOOLBOX CLUE --}}
                                    <div class="bg-indigo-900/20 border border-indigo-500/20 p-4 rounded-xl flex flex-wrap items-center gap-2 shadow-inner">
                                        <span class="text-[10px] font-bold text-indigo-300 uppercase tracking-widest mr-1 hidden sm:block">Logika Tembakan:</span>
                                        <code class="text-[10px] md:text-xs font-mono font-bold text-indigo-400 bg-indigo-500/10 border border-indigo-500/20 px-2 py-1 rounded shadow-sm">relative</code>
                                        <code class="text-[10px] md:text-xs font-mono font-bold text-rose-400 bg-rose-500/10 border border-rose-500/20 px-2 py-1 rounded shadow-sm">absolute</code>
                                        <code class="text-[10px] md:text-xs font-mono font-bold text-blue-400 bg-blue-500/10 border border-blue-500/20 px-2 py-1 rounded shadow-sm">-top-3</code>
                                        <code class="text-[10px] md:text-xs font-mono font-bold text-blue-400 bg-blue-500/10 border border-blue-500/20 px-2 py-1 rounded shadow-sm">-right-3</code>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-col xl:grid xl:grid-cols-2 gap-0 border border-white/10 rounded-2xl overflow-hidden shadow-2xl relative z-10 flex-1">
                                
                                {{-- EDITOR KIRI --}}
                                <div class="bg-[#151515] border-b xl:border-b-0 xl:border-r border-white/10 flex flex-col relative min-h-[500px] xl:min-h-[600px]">
                                    <div id="lockOverlay" class="hidden absolute inset-0 bg-[#050912]/95 backdrop-blur-md z-50 flex flex-col items-center justify-center text-center p-8">
                                        <div class="w-16 h-16 sm:w-20 sm:h-20 bg-emerald-500/10 rounded-full flex items-center justify-center mb-4 sm:mb-6 border border-emerald-500/50 shadow-[0_0_30px_rgba(16,185,129,0.2)] animate-bounce"><svg class="w-8 h-8 sm:w-10 sm:h-10 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" d="M5 13l4 4L19 7" /></svg></div>
                                        <h3 class="text-xl sm:text-2xl font-black text-white mb-2 tracking-tight">KODE Z-INDEX VALID!</h3>
                                        <p class="text-xs sm:text-sm text-white/50 mb-0 font-mono text-center max-w-xs">Akses kompilasi dimatikan.</p>
                                    </div>

                                    <div class="bg-[#1e1e1e] px-4 py-3 border-b border-white/5 flex justify-between items-center shrink-0">
                                        <span class="text-[10px] sm:text-xs text-white/50 font-mono font-bold flex items-center gap-2"><div class="w-2 h-2 rounded-full bg-yellow-500 hidden sm:block"></div> Profile-Badge.html</span>
                                        <button onclick="resetEditor()" class="text-[9px] sm:text-[10px] text-red-400 hover:text-red-300 uppercase font-bold border border-red-500/20 bg-red-500/10 px-2 sm:px-3 py-1 sm:py-1.5 rounded shadow-sm transition focus:outline-none active:scale-95">Reset</button>
                                    </div>
                                    <div id="codeEditor" class="flex-1 w-full border-b border-white/5 relative"></div>

                                    <div class="p-4 sm:p-5 bg-[#0f141e] flex flex-col shrink-0">
                                        <div class="flex justify-between items-center mb-3">
                                            <span class="text-[10px] uppercase font-bold text-white/30 tracking-widest">Kriteria Mesin Uji</span>
                                            <span id="progressText" class="text-[9px] sm:text-[10px] font-mono font-bold text-indigo-400 bg-indigo-900/30 px-2 py-0.5 rounded border border-indigo-500/20 shadow-inner">0/2 Lolos</span>
                                        </div>
                                        <div class="grid sm:grid-cols-2 gap-2 text-[10px] sm:text-[11px] font-mono text-white/50 mb-4 bg-black/20 p-3 rounded-lg shadow-inner border border-white/5">
                                            <div id="check-relative" class="flex items-start gap-2"><span class="w-3.5 h-3.5 mt-0.5 rounded-full border border-white/20 flex items-center justify-center text-[8px] transition-colors shrink-0"></span> <div><b class="block mb-1 text-white/80 transition-colors">Tanam Jangkar (#profile-card)</b> Pasang tameng <code class="text-white/60 bg-white/5 px-1 rounded">relative</code> penahan bocor.</div></div>
                                            <div id="check-absolute" class="flex items-start gap-2"><span class="w-3.5 h-3.5 mt-0.5 rounded-full border border-white/20 flex items-center justify-center text-[8px] transition-colors shrink-0"></span> <div><b class="block mb-1 text-white/80 transition-colors">Lontar Notif (#badge-new)</b> Eksekusi pelarian <code class="text-white/60 bg-white/5 px-1 rounded">absolute</code> memojok negatif.</div></div>
                                        </div>
                                        <button id="submitExerciseBtn" onclick="submitExercise()" disabled class="w-full py-2.5 sm:py-3 rounded-lg bg-emerald-600 text-white font-bold text-[11px] sm:text-xs shadow-lg shadow-emerald-500/20 hover:bg-emerald-500 hover:-translate-y-0.5 transition-all cursor-not-allowed opacity-50 flex items-center justify-center gap-2 focus:outline-none active:scale-95">
                                            <span>Selesaikan Semua Syarat Tes Mesin</span>
                                            <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                        </button>
                                    </div>
                                </div>

                                {{-- PREVIEW KANAN --}}
                                <div class="bg-[#1e1e1e] flex flex-col relative overflow-hidden min-h-[300px] xl:h-auto">
                                    <div class="bg-[#2d2d2d] px-4 py-3 border-b border-white/5 flex items-center justify-between shrink-0">
                                        <span class="text-[10px] text-gray-400 font-mono font-bold">Live Visual Engine</span>
                                        <span class="text-[9px] sm:text-[10px] bg-indigo-500/10 text-indigo-400 px-2 py-0.5 rounded border border-indigo-500/20 font-bold uppercase tracking-widest flex items-center gap-1.5 shadow-sm"><span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-pulse shadow-[0_0_5px_#6366f1]"></span> Auto-Sync</span>
                                    </div>
                                    <div class="flex-1 bg-gray-900 relative w-full h-full p-0">
                                        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.03] pointer-events-none mix-blend-overlay"></div>
                                        <iframe id="previewFrame" class="w-full h-full border-0 bg-transparent relative z-10 custom-scrollbar"></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                </article>

                <div class="mt-32 pt-10 border-t border-white/10 flex justify-between items-center">
                    <a href="{{ route('courses.grid') }}" class="group flex items-center gap-3 text-slate-400 hover:text-white transition">
                        <div class="w-10 h-10 rounded-full border border-white/10 flex items-center justify-center group-hover:border-white/30 transition shadow-inner">←</div>
                        <div class="text-left"><div class="text-[10px] uppercase tracking-widest opacity-50 font-bold">Sebelumnya</div><div class="font-bold text-sm">Bab 2.2 - Grid Layout</div></div>
                    </a>
                    
                    <div id="nextChapterBtn" class="group flex items-center gap-3 text-right text-slate-500 cursor-not-allowed opacity-50 pointer-events-none transition-all duration-500">
                        <div class="text-right">
                            <div id="nextLabel" class="text-[10px] uppercase tracking-widest opacity-50 font-bold">Gerbang Terkunci</div>
                            <div class="font-bold text-sm">Hands-on Lab 2</div>
                        </div>
                        <div id="nextIcon" class="w-10 h-10 rounded-full border border-white/5 flex items-center justify-center bg-white/5 transition-all duration-300 text-sm shadow-inner">🔒</div>
                    </div>
                </div>

                <div class="mt-16 text-center text-white/20 text-xs font-mono">&copy; {{ date('Y') }} Flowwind Learn.</div>
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
    let completedSet = new Set(window.COMPLETED_IDS);
    
    let activityCompleted = {!! ($activityCompleted ?? false) ? 'true' : 'false' !!};
    const ACTIVITY_ID = 9; 
    const ACTIVITY_LESSON_ID = 45;

    document.addEventListener('DOMContentLoaded', () => {
        initLessonObserver();
        initVisualEffects();
        initSidebarScroll();
        updateProgressUI();
        initMonaco();
        if (activityCompleted) { lockActivityUI(); unlockNext(); }
    });

    /* --- SIMULATOR 1: CONTAINER --- */
    window.clsMxAuto = false; window.clsPx4 = false; window.clsWidth = 'w-full';

    window.simContainer = function(widthCls, btn) {
        window.clsWidth = widthCls;
        document.querySelectorAll('.sim1-scr').forEach(b => {
            b.classList.remove('bg-white/10', 'text-white', 'border-indigo-400');
            b.classList.add('bg-transparent', 'text-white/50', 'border-transparent');
        });
        btn.classList.remove('bg-transparent', 'text-white/50', 'border-transparent');
        btn.classList.add('bg-white/10', 'text-white', 'border-indigo-400');
        renderSim1();
    }
    
    window.toggleSimClass = function(clsName, btn) {
        if(clsName === 'mx-auto') window.clsMxAuto = !window.clsMxAuto;
        if(clsName === 'px-4') window.clsPx4 = !window.clsPx4;
        
        const isOn = clsName === 'mx-auto' ? window.clsMxAuto : window.clsPx4;
        if(isOn) {
            btn.classList.replace('bg-white/5', 'bg-indigo-600'); btn.classList.replace('text-white/50', 'text-white'); btn.classList.replace('border-white/10', 'border-indigo-400');
            btn.innerText = clsName + ": ON";
        } else {
            btn.classList.replace('bg-indigo-600', 'bg-white/5'); btn.classList.replace('text-white', 'text-white/50'); btn.classList.replace('border-indigo-400', 'border-white/10');
            btn.innerText = clsName + ": OFF";
        }
        renderSim1();
    }

    function renderSim1() {
        const target = document.getElementById('sim1-target');
        target.className = `w-full h-32 sm:h-40 bg-gradient-to-b from-indigo-500/20 to-indigo-600/10 border border-indigo-400/50 rounded-lg flex items-center justify-center transition-all duration-700 ease-out relative shadow-[0_0_40px_rgba(99,102,241,0.2)] backdrop-blur-md ${window.clsWidth} ${window.clsMxAuto ? 'mx-auto' : 'ml-0'} ${window.clsPx4 ? 'px-4' : 'px-0'}`;
        document.getElementById('sim1-pad-l').style.opacity = window.clsPx4 ? '1' : '0'; document.getElementById('sim1-pad-r').style.opacity = window.clsPx4 ? '1' : '0';
    }

    /* --- SIMULATOR 2: FLOAT & CLEAR --- */
    window.sim2Float = 'left'; window.sim2Text = 'short'; window.sim2Clear = false;

    window.simFloat = function(dir, btn) {
        window.sim2Float = dir;
        document.querySelectorAll('.float-btn').forEach(b => { b.classList.replace('bg-gradient-to-b', 'bg-white/5'); b.classList.replace('text-white', 'text-white/50'); b.classList.remove('from-orange-500', 'to-orange-600', 'border-orange-400', 'shadow-[0_0_10px_rgba(249,115,22,0.3)]'); b.classList.add('border-white/10', 'hover:bg-white/10'); });
        btn.classList.replace('bg-white/5', 'bg-gradient-to-b'); btn.classList.replace('text-white/50', 'text-white'); btn.classList.remove('border-white/10', 'hover:bg-white/10'); btn.classList.add('from-orange-500', 'to-orange-600', 'border-orange-400', 'shadow-[0_0_10px_rgba(249,115,22,0.3)]');
        renderSim2();
    }
    window.simText = function(len, btn) {
        window.sim2Text = len;
        document.querySelectorAll('.txt-btn').forEach(b => { b.classList.replace('bg-white/10', 'bg-white/5'); b.classList.replace('text-white', 'text-white/50'); b.classList.add('hover:bg-white/10'); });
        btn.classList.replace('bg-white/5', 'bg-white/10'); btn.classList.replace('text-white/50', 'text-white'); btn.classList.remove('hover:bg-white/10');
        renderSim2();
    }
    window.simClearfix = function(state, btn) {
        window.sim2Clear = state;
        document.querySelectorAll('.clr-btn').forEach(b => { b.className = "clr-btn px-3 py-1.5 bg-white/5 text-white/50 border-white/10 font-bold text-[10px] sm:text-xs rounded border hover:bg-white/10 transition focus:outline-none shrink-0" + (b.parentElement.classList.contains('hidden') ? '' : ' w-1/2'); });
        
        const activeBtnClasses = state 
            ? "clr-btn px-3 py-1.5 bg-emerald-600 text-white font-bold text-[10px] sm:text-xs rounded border border-emerald-400 shadow-[0_0_10px_rgba(16,185,129,0.4)] transition focus:outline-none shrink-0" 
            : "clr-btn px-3 py-1.5 bg-red-600 text-white font-bold text-[10px] sm:text-xs rounded border border-red-400 shadow-[0_0_10px_rgba(220,38,38,0.4)] transition focus:outline-none shrink-0";
            
        btn.className = activeBtnClasses + (btn.parentElement.classList.contains('hidden') ? '' : ' w-1/2');
        renderSim2();
    }

    function renderSim2() {
        const parent = document.getElementById('float-parent'); const img = document.getElementById('float-img'); const txt = document.getElementById('float-txt'); const label = document.getElementById('parent-label');

        img.className = `w-20 h-20 sm:w-28 sm:h-28 bg-gradient-to-br from-orange-400 to-red-600 rounded-lg mb-2 flex flex-col items-center justify-center text-white font-black text-sm transition-all duration-500 shadow-[0_5px_20px_rgba(239,68,68,0.3)] ring-1 ring-white/20 float-${window.sim2Float} ${window.sim2Float === 'left' ? 'mr-4 ml-0' : 'ml-4 mr-0'}`;
        
        txt.innerHTML = window.sim2Text === 'short' 
            ? `<span class="text-white font-black text-3xl float-left mr-1.5 leading-none mt-1">P</span>aragraf pendek ini mendemonstrasikan malapetaka CSS. Jika teks tidak cukup merangkum tinggi gambar yang melayang di sebelahnya, wadah induk bergaris putus-putus merah akan gagal mendeteksi total tinggi gambar tersebut!`
            : `<span class="text-white font-black text-3xl float-left mr-1.5 leading-none mt-1">P</span>aragraf panjang ini membanjiri ruang. Ketika volume teks merembes melebihi lekuk pinggang gambar, secara kebetulan semata ia mendesak batasan bawah Parent ke dasar yang normal. Tapi awas, tanpa penawar 'flow-root', struktur ini bagai bom waktu yang rawan hancur secara diam-diam.`;

        if(window.sim2Clear) {
            parent.className = "border-2 border-dashed border-emerald-500/80 p-3 sm:p-4 rounded-lg transition-all duration-700 bg-emerald-500/10 relative min-h-[140px] flow-root";
            label.className = "absolute -top-3 left-4 bg-[#111112] px-2 text-[9px] sm:text-[10px] font-mono font-bold text-emerald-400 tracking-widest transition-colors shadow-sm";
            label.innerText = "PARENT MERANGKUL (FLOW-ROOT)";
        } else {
            parent.className = "border-2 border-dashed border-red-500/80 p-3 sm:p-4 rounded-lg transition-all duration-700 bg-red-500/5 relative min-h-[140px]";
            label.className = "absolute -top-3 left-4 bg-[#111112] px-2 text-[9px] sm:text-[10px] font-mono font-bold text-red-400 tracking-widest transition-colors shadow-sm";
            label.innerText = window.sim2Text === 'short' ? "WADAH INDUK KEMPIS!" : "TERSAMARKAN (KEBETULAN AMAN)";
        }
    }

    /* --- SIMULATOR 3: POSITION & Z-INDEX --- */
    window.simPos = 'relative';
    
    window.simPosition = function(val, btn) {
        window.simPos = val;
        document.querySelectorAll('.sim3-pos').forEach(b => {
            b.className = "sim3-pos px-3 py-1.5 text-[10px] sm:text-xs font-bold rounded bg-transparent text-white/50 hover:bg-white/5 transition border border-transparent shrink-0 focus:outline-none";
        });
        if(val === 'relative') {
            btn.className = "sim3-pos px-3 py-1.5 text-[10px] sm:text-xs font-bold rounded bg-blue-600 text-white border border-blue-400 shadow-[0_0_10px_rgba(59,130,246,0.4)] transition shrink-0 focus:outline-none";
        } else {
            btn.className = "sim3-pos px-3 py-1.5 text-[10px] sm:text-xs font-bold rounded bg-red-600 text-white border border-red-400 shadow-[0_0_10px_rgba(220,38,38,0.4)] transition shrink-0 focus:outline-none";
        }
        renderSim3();
    }
    
    function renderSim3() {
        const parent = document.getElementById('sim3-parent');
        const label = document.getElementById('sim3-label');
        if(window.simPos === 'relative') {
            parent.className = "w-56 h-40 sm:w-64 sm:h-48 border-2 border-dashed border-blue-500 bg-blue-500/10 rounded-xl relative transition-all duration-700 flex items-center justify-center transform-style-3d rotate-x-12 rotate-y-6 shadow-2xl";
            label.innerText = "WADAH RELATIVE"; label.className = "absolute -top-3 left-4 bg-[#111112] px-2 text-[9px] font-mono font-bold text-blue-400 tracking-widest transition-colors shadow-sm";
        } else {
            parent.className = "w-56 h-40 sm:w-64 sm:h-48 border-2 border-dashed border-red-500 bg-red-500/10 rounded-xl static transition-all duration-700 flex items-center justify-center transform-style-3d rotate-x-0 rotate-y-0 shadow-none";
            label.innerText = "WADAH STATIC (BOCOR)"; label.className = "absolute -top-3 left-4 bg-[#111112] px-2 text-[9px] font-mono font-bold text-red-400 tracking-widest transition-colors shadow-sm";
        }
        simZIndex('green'); // Reset visually
    }

    window.simZIndex = function(color) {
        const r = document.getElementById('sim3-red'); const lr = document.getElementById('lbl-z-red');
        const b = document.getElementById('sim3-blue'); const lb = document.getElementById('lbl-z-blue');
        const g = document.getElementById('sim3-green'); const lg = document.getElementById('lbl-z-green');

        // Clean slate bases
        const sStatic = window.simPos === 'static' ? '-translate-y-24 -translate-x-24 opacity-40 shadow-none' : '';
        const baseR = `absolute -top-4 -left-4 w-20 h-20 sm:w-24 sm:h-24 bg-red-600/90 rounded-lg shadow-[0_10px_20px_rgba(220,38,38,0.3)] border border-red-400 p-2 flex items-end justify-end transition-all duration-700 ease-out z-10 ${sStatic}`;
        const baseB = `absolute top-4 left-4 w-20 h-20 sm:w-24 sm:h-24 bg-blue-600/90 rounded-lg shadow-[0_10px_20px_rgba(37,99,235,0.3)] border border-blue-400 p-2 flex items-end justify-end transition-all duration-700 ease-out z-20 ${sStatic}`;
        const baseG = `absolute top-12 left-12 w-20 h-20 sm:w-24 sm:h-24 bg-emerald-500/90 rounded-lg shadow-[0_10px_20px_rgba(16,185,129,0.3)] border border-emerald-300 p-2 flex items-end justify-end transition-all duration-700 ease-out z-30 ${sStatic}`;

        r.className = baseR; lr.innerText = "z-10"; lr.className = "text-[9px] sm:text-[10px] font-bold bg-black/40 text-white px-1 rounded shadow-inner";
        b.className = baseB; lb.innerText = "z-20"; lb.className = "text-[9px] sm:text-[10px] font-bold bg-black/40 text-white px-1 rounded shadow-inner";
        g.className = baseG; lg.innerText = "z-30"; lg.className = "text-[9px] sm:text-[10px] font-bold bg-black/40 text-white px-1 rounded shadow-inner";

        if(window.simPos === 'static') return; // Cannot boost Z if static

        if(color === 'red') { r.classList.replace('z-10', 'z-50'); r.classList.replace('shadow-[0_10px_20px_rgba(220,38,38,0.3)]', 'shadow-[0_20px_40px_rgba(220,38,38,0.6)]'); lr.innerText = "Z-50 (DEPAN)"; lr.classList.replace('text-white', 'text-red-300'); lr.classList.replace('bg-black/40', 'bg-red-900/60'); }
        if(color === 'blue') { b.classList.replace('z-20', 'z-50'); b.classList.replace('shadow-[0_10px_20px_rgba(37,99,235,0.3)]', 'shadow-[0_20px_40px_rgba(37,99,235,0.6)]'); lb.innerText = "Z-50 (DEPAN)"; lb.classList.replace('text-white', 'text-blue-300'); lb.classList.replace('bg-black/40', 'bg-blue-900/60'); }
        if(color === 'green') { g.classList.replace('z-30', 'z-50'); g.classList.replace('shadow-[0_10px_20px_rgba(16,185,129,0.3)]', 'shadow-[0_20px_40px_rgba(16,185,129,0.6)]'); lg.innerText = "Z-50 (DEPAN)"; lg.classList.replace('text-white', 'text-emerald-300'); lg.classList.replace('bg-black/40', 'bg-emerald-900/60'); }
    }

    /* --- SIMULATOR 4: TABLE --- */
    window.simTableInject = function(btn) {
        btn.innerHTML = '<span class="animate-pulse">Loading Payload API...</span>';
        btn.disabled = true;
        setTimeout(() => {
            document.getElementById('sim4-auto-id').classList.replace('text-cyan-100', 'text-rose-500');
            document.getElementById('sim4-auto-id').classList.add('line-through', 'decoration-rose-500');
            
            const els = document.querySelectorAll('.sim4-data');
            els.forEach(el => {
                el.innerText = "Data string super panjang membludak tanpa sensor pengereman ini direkayasa khusus merobek tatanan dimensi tabel.";
                if(el.classList.contains('truncate')) {
                    el.classList.add('text-pink-300'); el.classList.replace('bg-emerald-500/5', 'bg-emerald-900/20');
                } else {
                    el.classList.add('text-red-300'); el.classList.replace('bg-white/5', 'bg-red-900/20');
                }
            });
            btn.innerHTML = "Injeksi API Sukses";
            btn.classList.replace('from-pink-600', 'from-emerald-600'); btn.classList.replace('to-rose-600', 'to-emerald-500'); btn.classList.replace('border-pink-400', 'border-emerald-400');
        }, 600);
    }


    /* --- EXPERT ACTIVITY (MONACO) --- */
    let editor;
    const starterCode = `<div class="bg-slate-900 min-h-screen w-full flex items-center justify-center p-8">
  
  <div id="profile-card" class="bg-slate-800 p-8 rounded-2xl w-72 shadow-xl border border-white/10 flex flex-col items-center text-center">
    
    <div id="badge-new" class="bg-rose-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-lg border border-white/20 tracking-wider">
      NEW
    </div>

    <div class="w-20 h-20 bg-gradient-to-tr from-indigo-500 to-purple-500 rounded-full mb-4 shadow-[0_0_20px_rgba(99,102,241,0.3)] border-2 border-white/10"></div>
    <div class="h-3 w-3/4 bg-white/20 rounded mb-2"></div>
    <div class="h-2 w-1/2 bg-white/10 rounded"></div>
  </div>
  
</div>`;

    function initMonaco() {
        require.config({ paths: { vs: 'https://cdn.jsdelivr.net/npm/monaco-editor@0.45.0/min/vs' } });
        require(['vs/editor/editor.main'], function () {
            editor = monaco.editor.create(document.getElementById('codeEditor'), {
                value: starterCode, language: 'html', theme: 'vs-dark', fontSize: window.innerWidth < 768 ? 12 : 14,
                minimap: { enabled: false }, automaticLayout: true, padding: { top: 16, bottom: 16 }, scrollBeyondLastLine: false, wordWrap: 'on'
            });
            window.addEventListener('resize', () => { if(editor) editor.layout(); });
            updateIframePreview(starterCode);
            if (activityCompleted) { lockActivityUI(); }
            editor.onDidChangeModelContent(() => {
                if(activityCompleted) return;
                const code = editor.getValue();
                updateIframePreview(code); validateCodeRegex(code);
            });
        });
    }

    function updateIframePreview(code) {
        const frame = document.getElementById('previewFrame');
        frame.srcdoc = `<!doctype html><html><head><meta charset="UTF-8"><script src="https://cdn.tailwindcss.com"><\/script><style>body{margin:0;padding:0;background:transparent;} *{transition:all 0.3s ease;}</style></head><body class="w-full h-full flex items-center justify-center">${code}</body></html>`;
    }

    function validateCodeRegex(code) {
        let passed = 0;
        const parentMatch = code.match(/id="profile-card"[^>]*class="([^"]*)"/);
        const badgeMatch = code.match(/id="badge-new"[^>]*class="([^"]*)"/);
        const parentCls = parentMatch ? parentMatch[1] : ''; const badgeCls = badgeMatch ? badgeMatch[1] : '';

        const hasPositionCoords = /(?:-top-\d+|top-0)\s+(?:-right-\d+|right-0)/.test(badgeCls) || /(?:-right-\d+|right-0)\s+(?:-top-\d+|top-0)/.test(badgeCls);

        const checks = [ { id: 'check-relative', valid: /\brelative\b/.test(parentCls) }, { id: 'check-absolute', valid: /\babsolute\b/.test(badgeCls) && hasPositionCoords } ];

        checks.forEach(c => {
            const el = document.getElementById(c.id); if (!el) return;
            const dot = el.querySelector('span'); const textSpan = el.querySelector('span:nth-child(2)');
            if(c.valid) {
                textSpan.classList.add('text-green-400', 'font-bold'); textSpan.classList.remove('text-white/80');
                textSpan.querySelector('code')?.classList.add('bg-green-900/40', 'text-green-200');
                dot.innerHTML = '✓'; dot.classList.replace('border-white/20', 'border-transparent'); dot.classList.add('bg-emerald-500', 'text-white');
                passed++;
            } else {
                textSpan.classList.remove('text-green-400', 'font-bold'); textSpan.classList.add('text-white/80');
                textSpan.querySelector('code')?.classList.remove('bg-green-900/40', 'text-green-200');
                dot.innerHTML = ''; dot.classList.replace('border-transparent', 'border-white/20'); dot.classList.remove('bg-emerald-500', 'text-white');
            }
        });

        document.getElementById('progressText').innerText = passed + '/2 Lolos';
        const btn = document.getElementById('submitExerciseBtn');
        if (passed === 2) {
            btn.disabled = false; btn.classList.remove('cursor-not-allowed', 'opacity-50');
            btn.innerHTML = '<span>Simpan Arsitektur Server & Lanjut</span><svg class="w-3 h-3 sm:w-4 sm:h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
        } else {
            btn.disabled = true; btn.classList.add('cursor-not-allowed', 'opacity-50');
            btn.innerHTML = '<span>Selesaikan Semua Syarat Tes Mesin</span><svg class="w-3 h-3 sm:w-4 sm:h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>';
        }
    }

    function resetEditor() { if(editor && !activityCompleted) { editor.setValue(starterCode); validateCodeRegex(starterCode); } }

    async function submitExercise() {
        const btn = document.getElementById('submitExerciseBtn');
        btn.innerHTML = '<span class="animate-pulse">Menyimpan Tinta Komando...</span>'; btn.disabled = true;
        try {
            await fetch('/activity/complete', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'Content-Type': 'application/json' }, body: JSON.stringify({ activity_id: ACTIVITY_ID, score: 100 }) });
            await saveLessonToDB(ACTIVITY_LESSON_ID); completedSet.add(ACTIVITY_LESSON_ID);
            activityCompleted = true; updateProgressUI(); lockActivityUI(); unlockNext(); 
        } catch(e) { 
            btn.innerHTML = "Koneksi Terputus. Coba Lagi."; btn.disabled = false; btn.classList.add('shake'); setTimeout(() => btn.classList.remove('shake'), 500);
        }
    }

    function lockActivityUI() {
        document.getElementById('lockOverlay').classList.remove('hidden');
        if(editor) {
            editor.updateOptions({ readOnly: true });
            if(activityCompleted) {
                editor.setValue(`<div class="bg-slate-900 min-h-screen w-full flex items-center justify-center p-8">\n  <div id="profile-card" class="relative bg-slate-800 p-8 rounded-2xl w-72 shadow-xl border border-white/10 flex flex-col items-center text-center">\n    <div id="badge-new" class="absolute -top-3 -right-3 bg-rose-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-lg border border-white/20 tracking-wider">\n      NEW\n    </div>\n    <div class="w-20 h-20 bg-gradient-to-tr from-indigo-500 to-purple-500 rounded-full mb-4 shadow-[0_0_20px_rgba(99,102,241,0.3)] border-2 border-white/10"></div>\n    <div class="h-3 w-3/4 bg-white/20 rounded mb-2"></div>\n    <div class="h-2 w-1/2 bg-white/10 rounded"></div>\n  </div>\n</div>`);
                validateCodeRegex(editor.getValue());
            }
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
        if(percent === 100 && activityCompleted) unlockNext();
    }

    function unlockNext() {
        const btn = document.getElementById('nextChapterBtn'); if(!btn) return;
        btn.classList.remove('cursor-not-allowed', 'opacity-50', 'pointer-events-none', 'text-slate-500');
        btn.classList.add('text-indigo-400', 'hover:text-white', 'cursor-pointer', 'group');
        document.getElementById('nextLabel').innerText = "Rute Lab Terbuka";
        document.getElementById('nextLabel').classList.remove('opacity-50');
        document.getElementById('nextIcon').innerHTML = `<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>`; 
        document.getElementById('nextIcon').classList.replace('bg-white/5', 'bg-indigo-500/20');
        document.getElementById('nextIcon').classList.replace('border-white/5', 'border-indigo-500/50');
        document.getElementById('nextIcon').classList.add('text-indigo-400', 'shadow-[0_0_15px_rgba(99,102,241,0.3)]');
        btn.onclick = () => window.location.href = "{{ route('lab.start', ['id' => 2]) ?? '#' }}"; 
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

    function initSidebarScroll(){
        const m = document.getElementById('mainScroll'); const sections = document.querySelectorAll('.lesson-section'); const navItems = document.querySelectorAll('.accordion-content .nav-item');
        if(!m) return;
        const observer = new IntersectionObserver((entries) => {
            let bestMatch = null;
            entries.forEach(entry => { if (entry.isIntersecting) bestMatch = '#' + entry.target.id; });
            if (bestMatch) {
                navItems.forEach(k => k.classList.remove('active'));
                const activeLink = document.querySelector(`.accordion-content .nav-item[data-target="${bestMatch}"]`);
                if (activeLink) activeLink.classList.add('active');
            }
        }, { root: m, rootMargin: '-20% 0px -60% 0px', threshold: 0 }); // Margin ketat cegah double active
        sections.forEach(s => observer.observe(s));
        navItems.forEach(k => { k.addEventListener('click', (e) => { const target = document.querySelector(k.getAttribute('data-target')); if(target) m.scrollTo({top: target.offsetTop - 120, behavior: 'smooth'}); }); });
    }

    function initVisualEffects(){const c=document.getElementById('stars');if(!c)return;const x=c.getContext('2d');function r(){c.width=innerWidth;c.height=innerHeight}r();window.onresize=r;let s=[];for(let i=0;i<100;i++)s.push({x:Math.random()*c.width,y:Math.random()*c.height,r:Math.random()*1.2,v:Math.random()*0.2+.1});(function a(){x.clearRect(0,0,c.width,c.height);x.fillStyle='rgba(255,255,255,.3)';s.forEach(t=>{x.beginPath();x.arc(t.x,t.y,t.r,0,6.28);x.fill();t.y+=t.v;if(t.y>c.height)t.y=0});requestAnimationFrame(a)})();}
</script>
@endsection