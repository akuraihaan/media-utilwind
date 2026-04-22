@extends('layouts.landing')
@section('title', ' Borders & Effects ')

@section('content')

<script>
    if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
</script>

<style>
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

    body { font-family: 'Inter', sans-serif; background-color: var(--bg-main); color: var(--text-main); transition: background-color 0.4s ease, color 0.4s ease; }
    .font-mono { font-family: 'JetBrains Mono', monospace; }

    .bg-adaptive { background-color: var(--bg-main); transition: background-color 0.4s ease; }
    .text-adaptive { color: var(--text-main); transition: color 0.4s ease; }
    .text-heading { color: var(--text-heading); transition: color 0.4s ease; }
    .text-muted { color: var(--text-muted); transition: color 0.4s ease; }
    .border-adaptive { border-color: var(--border-color); transition: border-color 0.4s ease; }
    .card-adaptive { background-color: var(--card-bg); border-color: var(--glass-border); transition: all 0.3s; }
    .card-adaptive:hover { border-color: var(--accent-glow); }
    .sim-bg-adaptive { background-color: var(--simulator-bg); transition: background-color 0.4s ease; }
    .code-adaptive { background-color: var(--code-bg); border-color: var(--glass-border); transition: all 0.4s ease; }

    .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(150,150,150,0.3); border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: var(--accent); }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }
    
    #animated-bg { 
        background: radial-gradient(800px circle at 20% 20%, rgba(99,102,241,.08), transparent 40%), 
                    radial-gradient(800px circle at 80% 80%, rgba(217,70,239,.08), transparent 40%); 
        animation: bgMove 20s ease-in-out infinite alternate; 
    }
    .dark #animated-bg {
        background: radial-gradient(800px circle at 20% 20%, rgba(99,102,241,.15), transparent 40%), 
                    radial-gradient(800px circle at 80% 80%, rgba(217,70,239,.15), transparent 40%); 
    }
    @keyframes bgMove{to{transform:scale(1.15)}}
    @keyframes shake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-5px); } 75% { transform: translateX(5px); } }
    .shake { animation: shake 0.4s ease-in-out; }
    
    @media (max-width: 1023px) {
        #courseSidebar { position: fixed; top: 64px; left: -100%; height: calc(100vh - 64px); transition: left 0.3s ease-in-out; z-index: 40; }
        #courseSidebar.mobile-open { left: 0; box-shadow: 10px 0 30px rgba(0,0,0,0.5); }
        #mobileOverlay { display: none; position: fixed; inset: 0; top: 64px; background: rgba(0,0,0,0.6); z-index: 30; }
        #mobileOverlay.show { display: block; }
    }
    
    .nav-item { display: flex; width: 100%; text-align: left; align-items: center; gap: 12px; padding: 10px 14px; font-size: 0.85rem; color: var(--text-muted); border-radius: 8px; transition: all 0.2s; position: relative; border-left-width: 2px; border-color: transparent;}
    .nav-item:hover { color: var(--text-main); background: var(--card-hover); }
    .nav-item.active { color: var(--accent); background: rgba(99, 102, 241, 0.05); font-weight: 600; }
    .dot { width: 6px; height: 6px; border-radius: 50%; background: #94a3b8; transition: all 0.3s; }
    .dark .dot { background: #334155; }
    .nav-item.active .dot { background: var(--accent); box-shadow: 0 0 8px var(--accent); transform: scale(1.2); }
    .dark .nav-item.active .dot { background: #818cf8; box-shadow: 0 0 8px #818cf8; }

    .insight-box { animation: fadeIn 0.4s ease-in-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }

    .bg-checkered {
        background-image: 
            linear-gradient(45deg, #ccc 25%, transparent 25%), 
            linear-gradient(-45deg, #ccc 25%, transparent 25%), 
            linear-gradient(45deg, transparent 75%, #ccc 75%), 
            linear-gradient(-45deg, transparent 75%, #ccc 75%);
        background-size: 20px 20px;
        background-position: 0 0, 0 10px, 10px -10px, -10px 0px;
    }
    .dark .bg-checkered {
        background-image: 
            linear-gradient(45deg, #333 25%, transparent 25%), 
            linear-gradient(-45deg, #333 25%, transparent 25%), 
            linear-gradient(45deg, transparent 75%, #333 75%), 
            linear-gradient(-45deg, transparent 75%, #333 75%);
    }
</style>

<button id="mobileSidebarToggle" class="lg:hidden fixed bottom-6 right-6 z-50 p-4 rounded-full shadow-2xl bg-indigo-600 text-white hover:bg-indigo-500 focus:outline-none transition-transform hover:scale-110">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
    </svg>
</button>

<div id="mobileOverlay" onclick="toggleMobileSidebar()"></div>

<div id="courseRoot" class="relative h-screen bg-slate-50 dark:bg-[#020617] text-slate-800 dark:text-white font-sans overflow-hidden flex flex-col selection:bg-indigo-500/30 pt-20 transition-colors duration-500">

    <div class="fixed inset-0 -z-50 pointer-events-none">
        <div id="animated-bg" class="absolute inset-0 opacity-50 transition-opacity duration-500"></div>
        <div class="absolute top-[-10%] right-[-10%] w-[600px] h-[600px] bg-indigo-300/30 dark:bg-indigo-900/10 rounded-full blur-[120px] animate-pulse transition-colors duration-500"></div>
        <div class="absolute bottom-[-10%] left-[-10%] w-[500px] h-[500px] bg-fuchsia-300/30 dark:bg-fuchsia-900/10 rounded-full blur-[100px] transition-colors duration-500"></div>
        <canvas id="stars" class="absolute inset-0 pointer-events-none opacity-0 dark:opacity-100 transition-opacity duration-500"></canvas>
    </div>

    @include('layouts.partials.navbar')

    <div class="flex flex-1 overflow-hidden relative z-20">
        @include('layouts.partials.course-sidebar')

        <main id="mainScroll" class="flex-1 h-full overflow-y-auto scroll-smooth relative bg-transparent custom-scrollbar scroll-padding-top-24">
            
            <div id="stickyHeader" class="sticky top-0 z-30 w-full bg-white/80 dark:bg-[#020617]/80 backdrop-blur-2xl border-b border-slate-200 dark:border-white/5 px-4 md:px-8 py-4 flex items-center justify-between transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-gradient-to-br dark:from-indigo-500/20 dark:to-transparent border border-indigo-200 dark:border-indigo-500/20 flex items-center justify-center font-bold text-xs text-indigo-600 dark:text-indigo-400 shrink-0 transition-colors">3.3</div>
                    <div>
                        <h1 class="text-sm font-bold text-slate-900 dark:text-white line-clamp-1 transition-colors">Borders & Effects Masterclass</h1>
                        <p class="text-[10px] text-slate-500 dark:text-white/50 line-clamp-1 transition-colors">Struktur, Ring, & Smart Dividers</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <div class="hidden sm:block w-24 md:w-32 h-1.5 bg-slate-200 dark:bg-white/10 rounded-full overflow-hidden transition-colors">
                        <div id="topProgressBar" class="h-full bg-gradient-to-r from-indigo-400 to-fuchsia-500 dark:from-indigo-500 dark:to-fuchsia-500 w-0 transition-all duration-500 shadow-[0_0_10px_#818cf8]"></div>
                    </div>
                    <span id="progressLabelTop" class="text-indigo-600 dark:text-indigo-400 font-bold text-xs transition-colors">0%</span>
                </div>
            </div>

            <div class="p-6 lg:p-16 max-w-5xl mx-auto pb-40">
                
                <div class="mb-24">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-2 transition-colors">
                        <svg class="w-5 h-5 text-indigo-500 dark:text-indigo-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Target Pembelajaran
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                        
                        <div class="bg-white dark:bg-[#1e1e1e] border border-slate-200 dark:border-white/5 p-5 rounded-xl flex items-start gap-4 hover:border-indigo-400 dark:hover:border-indigo-500/30 shadow-sm dark:shadow-none transition group h-full">
                            <div class="w-8 h-8 rounded bg-indigo-100 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0 font-bold text-xs transition-colors">1</div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800 dark:text-white mb-1 transition-colors">Mastering Radius</h4>
                                <p class="text-[11px] text-slate-500 dark:text-white/50 leading-relaxed transition-colors">Memahami fungsi psikologis bentuk komponen (tajam vs melengkung) dalam desain antarmuka modern.</p>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-[#1e1e1e] border border-slate-200 dark:border-white/5 p-5 rounded-xl flex items-start gap-4 hover:border-fuchsia-400 dark:hover:border-fuchsia-500/30 shadow-sm dark:shadow-none transition group h-full">
                            <div class="w-8 h-8 rounded bg-fuchsia-100 dark:bg-fuchsia-500/10 text-fuchsia-600 dark:text-fuchsia-400 flex items-center justify-center shrink-0 font-bold text-xs transition-colors">2</div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800 dark:text-white mb-1 transition-colors">Struktur Border</h4>
                                <p class="text-[11px] text-slate-500 dark:text-white/50 leading-relaxed transition-colors">Mengendalikan gaya dan ketebalan garis secara presisi untuk memperkuat hierarki pembatas elemen.</p>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-[#1e1e1e] border border-slate-200 dark:border-white/5 p-5 rounded-xl flex items-start gap-4 hover:border-sky-400 dark:hover:border-sky-500/30 shadow-sm dark:shadow-none transition group h-full">
                            <div class="w-8 h-8 rounded bg-sky-100 dark:bg-sky-500/10 text-sky-600 dark:text-sky-400 flex items-center justify-center shrink-0 font-bold text-xs transition-colors">3</div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800 dark:text-white mb-1 transition-colors">Smart Dividers</h4>
                                <p class="text-[11px] text-slate-500 dark:text-white/50 leading-relaxed transition-colors">Membelah tumpukan menu secara bersih tanpa harus repot menuliskan CSS pengecualian elemen terakhir.</p>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-[#1e1e1e] border border-slate-200 dark:border-white/5 p-5 rounded-xl flex items-start gap-4 hover:border-emerald-400 dark:hover:border-emerald-500/30 shadow-sm dark:shadow-none transition group h-full">
                            <div class="w-8 h-8 rounded bg-emerald-100 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 font-bold text-xs transition-colors">4</div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800 dark:text-white mb-1 transition-colors">Cincin Interaksi</h4>
                                <p class="text-[11px] text-slate-500 dark:text-white/50 leading-relaxed transition-colors">Mengganti kebiasaan border hover dengan Rings elegan yang tidak merusak susunan tata letak.</p>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-indigo-50 to-purple-50 dark:from-indigo-900/40 dark:to-purple-900/40 border border-indigo-200 dark:border-indigo-500/30 p-5 rounded-xl flex items-start gap-4 hover:shadow-md dark:hover:shadow-[0_0_20px_rgba(99,102,241,0.2)] transition group h-full col-span-1 sm:col-span-2 md:col-span-4 cursor-default">
                            <div class="w-8 h-8 rounded bg-indigo-100 dark:bg-white/10 text-indigo-600 dark:text-white flex items-center justify-center shrink-0 font-bold text-xs transition-colors">🏁</div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800 dark:text-white mb-1 transition-colors">Final Mission</h4>
                                <p class="text-[11px] text-slate-600 dark:text-white/70 leading-relaxed transition-colors">Terapkan manipulasi kelengkungan, ketebalan border 3D, pembatas pintar divide, dan cincin fokus (ring) untuk menyusun kartu komponen profil interaktif menggunakan Live Code Editor.</p>
                            </div>
                        </div>

                    </div>
                </div>

                <article class="space-y-40">
                    
                    {{-- LESSON 56: RADIUS & WIDTH --}}
                    <section id="section-56" class="lesson-section scroll-mt-32" data-lesson-id="56">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-indigo-500 pl-6">
                                <span class="text-indigo-600 dark:text-indigo-400 font-mono text-xs uppercase tracking-widest transition-colors">Lesson 3.3.1</span>
                                <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-slate-900 dark:text-white leading-[1.1] transition-colors">
                                    Bentuk (Radius) & <br> Ketebalan Garis (Width)
                                </h2>
                            </div>
                            
                            <div class="space-y-6">
                                <div class="prose prose-slate dark:prose-invert max-w-none text-slate-600 dark:text-white/70 text-base md:text-lg leading-relaxed transition-colors text-justify">
                                    <p>
                                        Dalam psikologi UI, sudut tajam merepresentasikan ketegasan struktural (cocok untuk <i>Card</i> atau tabel kaku), sementara lengkungan (<i>rounded</i>) memberikan nuansa "ramah" yang memicu intuisi alami manusia untuk menekan atau berinteraksi.
                                    </p>
                                    
                                    <ul class="list-disc pl-5 space-y-3 text-sm md:text-base text-left mt-6 border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-white/5 p-6 rounded-xl transition-colors">
                                        <li><strong><code class="text-indigo-600 dark:text-indigo-400 font-bold transition-colors">rounded-sm</code> (2px):</strong> Lengkungan super halus, elegan untuk elemen yang saling berhimpitan seperti <i>checkbox</i>.</li>
                                        <li><strong><code class="text-indigo-600 dark:text-indigo-400 font-bold transition-colors">rounded-xl</code> (12px):</strong> Standar keemasan (Golden Standard) di industri masa kini untuk wadah pembungkus modal atau kartu.</li>
                                        <li><strong><code class="text-indigo-600 dark:text-indigo-400 font-bold transition-colors">rounded-full</code> (9999px):</strong> Mengubah elemen persegi panjang menjadi bentuk pil obat (untuk tombol), dan mengubah elemen persegi presisi menjadi lingkaran mutlak (untuk Avatar).</li>
                                    </ul>

                                    <p class="mt-6">
                                         <strong>Pro Tip Ketebalan (Width):</strong> Batas garis (<i>border</i>) tidak wajib mengelilingi keempat sisi. Salah satu trik klasik desain "Tombol 3D" adalah memadukan <code class="font-bold text-fuchsia-600 dark:text-fuchsia-400">border-b-4</code> (garis bawah tebal) dengan warna dasar yang lebih gelap. Ini menipu mata seolah tombol memiliki alas fisik solid yang dapat ditekan masuk ke dalam layar.
                                    </p>
                                </div>
                            </div>

                            <div class="bg-white dark:bg-[#0b0f19] border border-slate-200 dark:border-white/10 rounded-2xl p-6 lg:p-8 shadow-xl dark:shadow-2xl relative group hover:border-indigo-400 dark:hover:border-indigo-500/30 transition-all mt-8">
                                <h4 class="text-xs font-bold text-slate-400 dark:text-muted uppercase mb-4 text-center transition-colors tracking-widest">Simulator: Konfigurasi Shape & Width</h4>
                                
                                <div class="bg-indigo-50 dark:bg-indigo-500/10 border border-indigo-200 dark:border-indigo-500/30 rounded-xl p-4 mb-8 text-sm text-indigo-700 dark:text-indigo-300 relative z-10 shadow-sm dark:shadow-inner transition-colors">
                                    <p class="font-bold flex items-center gap-2 mb-2">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"></path></svg>
                                        Panduan Pengujian
                                    </p>
                                    <p class="m-0 opacity-90 leading-relaxed text-xs md:text-sm text-indigo-800/80 dark:text-indigo-100/80 transition-colors">
                                        Klik kombinasi tombol <b>Kelengkungan</b> dan <b>Ketebalan</b> di bawah untuk melihat transformasi real-time dari struktur kotak komponen beserta penjelasan logis dari utilitas tersebut.
                                    </p>
                                </div>

                                <div class="flex flex-col xl:flex-row justify-between items-start mb-6 gap-6 relative z-10">
                                    <div class="flex flex-col gap-6 w-full xl:w-1/2 bg-slate-50 dark:bg-black/30 border border-slate-200 dark:border-white/10 p-5 rounded-2xl shadow-inner">
                                        <div>
                                            <div class="text-[10px] font-black text-indigo-700 dark:text-indigo-300 uppercase tracking-widest mb-3 flex items-center gap-2"><span class="w-2 h-2 rounded bg-indigo-500 block"></span> 1. Pengendali Kelengkungan (Radius)</div>
                                            <div class="grid grid-cols-3 gap-2">
                                                <button onclick="updateSim56('radius', 'rounded-none')" class="btn-sim-56-rad py-2.5 rounded-lg bg-indigo-600 text-white shadow-md border border-indigo-400 transition text-xs font-bold focus:outline-none">Kaku (None)</button>
                                                <button onclick="updateSim56('radius', 'rounded-xl')" class="btn-sim-56-rad py-2.5 rounded-lg bg-white dark:bg-white/5 text-slate-600 dark:text-white/50 border border-slate-200 dark:border-white/10 hover:bg-slate-100 dark:hover:bg-white/10 transition text-xs font-bold focus:outline-none">Halus (XL)</button>
                                                <button onclick="updateSim56('radius', 'rounded-full')" class="btn-sim-56-rad py-2.5 rounded-lg bg-white dark:bg-white/5 text-slate-600 dark:text-white/50 border border-slate-200 dark:border-white/10 hover:bg-slate-100 dark:hover:bg-white/10 transition text-xs font-bold focus:outline-none">Bulat (Full)</button>
                                            </div>
                                        </div>
                                        
                                        <div class="border-t border-slate-200 dark:border-white/10"></div>

                                        <div>
                                            <div class="text-[10px] font-black text-fuchsia-700 dark:text-fuchsia-300 uppercase tracking-widest mb-3 flex items-center gap-2"><span class="w-2 h-2 rounded bg-fuchsia-500 block"></span> 2. Pengatur Ketebalan (Width)</div>
                                            <div class="grid grid-cols-3 gap-2">
                                                <button onclick="updateSim56('width', 'border-0')" class="btn-sim-56-wid py-2.5 rounded-lg bg-white dark:bg-white/5 text-slate-600 dark:text-white/50 border border-slate-200 dark:border-white/10 hover:bg-slate-100 dark:hover:bg-white/10 transition text-xs font-bold focus:outline-none">Tanpa Garis</button>
                                                <button onclick="updateSim56('width', 'border-4')" class="btn-sim-56-wid py-2.5 rounded-lg bg-fuchsia-600 text-white shadow-md border border-fuchsia-400 transition text-xs font-bold focus:outline-none">Penuh (4)</button>
                                                <button onclick="updateSim56('width', 'border-b-8')" class="btn-sim-56-wid py-2.5 rounded-lg bg-white dark:bg-white/5 text-slate-600 dark:text-white/50 border border-slate-200 dark:border-white/10 hover:bg-slate-100 dark:hover:bg-white/10 transition text-xs font-bold focus:outline-none">3D Bawah</button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="w-full xl:w-1/2 flex flex-col items-center justify-center bg-slate-200 dark:bg-black/60 rounded-2xl border border-slate-300 dark:border-white/10 relative overflow-hidden h-[300px] shadow-inner p-4">
                                        <div class="absolute inset-0 bg-checkered opacity-20 dark:opacity-30 mix-blend-overlay"></div>
                                        
                                        <span class="absolute top-4 left-4 text-[10px] font-bold uppercase tracking-widest text-slate-500 z-10 bg-white/80 dark:bg-black/50 px-2 py-1 rounded shadow-sm">
                                            class="<code id="code-56-r" class="text-indigo-600 dark:text-indigo-400">rounded-none</code> <code id="code-56-w" class="text-fuchsia-600 dark:text-fuchsia-400">border-4</code>"
                                        </span>

                                        <div id="sim56-target" class="w-32 h-32 md:w-40 md:h-40 bg-gradient-to-br from-indigo-500 to-purple-600 border-4 border-slate-900/20 dark:border-white/20 flex items-center justify-center text-white font-black tracking-widest transition-all duration-500 rounded-none shadow-xl z-10 relative">
                                            OBJEK
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-6 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-100 dark:border-emerald-800/50 p-4 rounded-xl text-sm text-emerald-900 dark:text-emerald-100 flex items-start gap-4 shadow-sm z-20 transition-colors">
                                    <div class="p-2 bg-emerald-200 dark:bg-emerald-800/50 rounded-lg shrink-0">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <p id="demo-56-insight" class="insight-box m-0 leading-relaxed font-medium">Kombinasi awal ini mempertahankan keaslian bentuk kaku murni (<code class="font-bold bg-white dark:bg-black/40 px-1 rounded text-indigo-600 dark:text-indigo-300">rounded-none</code>) yang dilindungi oleh dinding penegasan solid yang sama tebalnya di setiap sisi (<code class="font-bold bg-white dark:bg-black/40 px-1 rounded text-fuchsia-600 dark:text-fuchsia-300">border-4</code>). Pola yang sangat umum untuk kontainer dasar.</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 57: COLORS & STYLES --}}
                    <section id="section-57" class="lesson-section scroll-mt-32" data-lesson-id="57">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-sky-500 pl-6">
                                <span class="text-sky-600 dark:text-sky-400 font-mono text-xs uppercase tracking-widest transition-colors">Lesson 3.3.2</span>
                                <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-slate-900 dark:text-white leading-[1.1] transition-colors">
                                    Estetika Harmoni & <br> Semantik Gaya Garis
                                </h2>
                            </div>

                            <div class="space-y-6">
                                <div class="prose prose-slate dark:prose-invert max-w-none text-slate-600 dark:text-white/70 text-base md:text-lg leading-relaxed transition-colors text-justify">
                                    <p>
                                        Kesalahan desainer pemula yang paling fatal adalah menggunakan <i>border</i> hitam pekat (<code class="font-mono text-sm">border-black</code>) tanpa transparansi sama sekali. Hal ini membuat aplikasi tampak kasar bagaikan cetak biru (<i>wireframe</i>) yang belum diselesaikan.
                                    </p>
                                    
                                    <ul class="list-disc pl-5 space-y-4 mt-4 border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-white/5 p-6 rounded-xl transition-colors text-sm md:text-base text-left">
                                        <li><strong>Warna Halus (Opacity):</strong> Biasakan menggunakan warna pucat dengan sedikit transparansi, misal <code class="text-sky-600 dark:text-sky-300 font-bold transition-colors">border-slate-200</code> untuk nuansa terang atau <code class="text-sky-600 dark:text-sky-300 font-bold transition-colors">border-white/10</code> untuk nuansa gelap. Garis seharusnya memandu batas tanpa perlu "berteriak" menyaingi konten utama.</li>
                                        <li><strong>Gaya Fungsional (Semantics):</strong>
                                            <br>• <code class="font-bold">border-solid</code> (Bawaan): Mengunci ruang secara permanen (untuk wadah tabel, input).
                                            <br>• <code class="font-bold">border-dashed</code>: Pola putus-putus yang memberi sinyal universal "Area Interaktif Khusus" (sangat pas untuk <i>Drag & Drop Upload Area</i>).
                                            <br>• <code class="font-bold">border-dotted</code>: Pola titik lembut untuk batas robekan kasir atau elemen hiasan sekunder.
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="bg-white dark:bg-[#0b0f19] border border-slate-200 dark:border-white/10 rounded-2xl p-6 lg:p-8 shadow-xl dark:shadow-2xl relative group hover:border-sky-400 dark:hover:border-sky-500/30 transition-all mt-8">
                                <h4 class="text-xs font-bold text-slate-400 dark:text-muted uppercase mb-4 text-center transition-colors tracking-widest">Simulator: Area Konteks Fungsional</h4>

                                <div class="bg-sky-50 dark:bg-sky-500/10 border border-sky-200 dark:border-sky-500/30 rounded-xl p-4 mb-8 text-sm text-sky-700 dark:text-sky-300 relative z-10 shadow-sm dark:shadow-inner transition-colors">
                                    <p class="font-bold flex items-center gap-2 mb-2">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"></path></svg>
                                        Panduan Pengujian
                                    </p>
                                    <p class="m-0 opacity-90 leading-relaxed text-xs md:text-sm text-sky-800/80 dark:text-sky-100/80 transition-colors">
                                        Ubah tipe gaya pembatas (Style) di bawah ini untuk menganalisis kecocokan secara psikologis dengan antarmuka area unggah dokumen (Upload Dropzone).
                                    </p>
                                </div>

                                <div class="flex flex-col items-center justify-center relative z-10">
                                    <div id="sim57-target" class="w-full max-w-md h-40 border-2 border-dashed border-slate-400 dark:border-white/20 bg-slate-50 dark:bg-white/5 rounded-2xl flex flex-col items-center justify-center text-slate-500 dark:text-white/40 font-medium transition-all duration-300 shadow-sm">
                                        <svg class="w-10 h-10 mb-3 text-slate-400 dark:opacity-50 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                        <span class="text-sm tracking-wide">Tarik & Lepaskan File Pendaftaran</span>
                                    </div>

                                    <div class="flex gap-2 w-full max-w-md mt-6">
                                        <button onclick="updateSim57('border-solid')" class="btn-sim-57 flex-1 py-2.5 rounded-lg bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-white/50 border border-slate-200 dark:border-white/10 hover:bg-slate-200 dark:hover:bg-white/10 transition text-xs font-bold focus:outline-none">Garis Solid</button>
                                        <button onclick="updateSim57('border-dashed')" class="btn-sim-57 flex-1 py-2.5 rounded-lg bg-sky-600 text-white shadow-md border border-sky-400 transition text-xs font-bold focus:outline-none">Garis Dashed</button>
                                        <button onclick="updateSim57('border-dotted')" class="btn-sim-57 flex-1 py-2.5 rounded-lg bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-white/50 border border-slate-200 dark:border-white/10 hover:bg-slate-200 dark:hover:bg-white/10 transition text-xs font-bold focus:outline-none">Garis Dotted</button>
                                    </div>

                                    <div class="mt-4 w-full max-w-md bg-slate-100 dark:bg-black/40 p-4 rounded-xl font-mono text-[12px] text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-white/5 text-center transition-colors">
                                        class="border-2 <span id="code-57" class="text-sky-600 dark:text-sky-400 font-bold">border-dashed</span> border-slate-400"
                                    </div>
                                </div>

                                <div class="mt-8 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-100 dark:border-emerald-800/50 p-4 rounded-xl text-sm text-emerald-900 dark:text-emerald-100 flex items-start gap-4 shadow-sm z-20 transition-colors">
                                    <div class="p-2 bg-emerald-200 dark:bg-emerald-800/50 rounded-lg shrink-0">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <p id="demo-57-insight" class="insight-box m-0 leading-relaxed font-medium">Bentuk patah-patah <code class="font-bold bg-white dark:bg-black/40 px-1 rounded text-sky-600 dark:text-sky-300">border-dashed</code> adalah pilihan paling logis untuk kasus ini. Pola berongganya memicu naluri otak manusia untuk "memasukkan sesuatu guna mengisi ruang kosong yang terpotong" tersebut.</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 58: DIVIDE UTILITIES & RINGS --}}
                    <section id="section-58" class="lesson-section scroll-mt-32" data-lesson-id="58">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-teal-500 pl-6">
                                <span class="text-teal-600 dark:text-teal-400 font-mono text-xs uppercase tracking-widest transition-colors">Lesson 3.3.3</span>
                                <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-slate-900 dark:text-white leading-[1.1] transition-colors">
                                    Utilitas Cerdas: <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-500 to-emerald-500 dark:from-teal-400 dark:to-emerald-400">Smart Dividers & Rings</span>
                                </h2>
                            </div>

                            <div class="space-y-6">
                                <div class="prose prose-slate dark:prose-invert max-w-none text-slate-600 dark:text-white/70 text-base md:text-lg leading-relaxed transition-colors text-justify">
                                    <p>
                                        Ketika membuat menu menurun (<i>Dropdown</i>) atau daftar panel riwayat, pendekatan paling amatir adalah menyematkan kelas <code class="font-mono text-sm">border-b</code> secara manual berulang-ulang ke setiap baris elemen tersebut.
                                    </p>
                                    <p>
                                        Bencana terjadi di item baris paling terakhir; dasar kotaknya akan menggendong garis buangan yang tidak senada dengan pelindung kontainer utama. Akibatnya, kita harus menulis CSS pengkhususan super merepotkan, seperti <code class="font-mono text-sm">last:border-b-0</code>. Sangat merepotkan!
                                    </p>
                                    <p>
                                        <strong>Rahasia Framework Modern:</strong> Panggil kelas pembantu <code class="text-teal-600 dark:text-teal-400 font-bold transition-colors bg-teal-50 dark:bg-teal-900/20 px-1.5 py-0.5 rounded">divide-y</code>, cukup letakkan tepat di tag pembungkus luar (<i>Parent Container</i>). Sistem Tailwind akan membedah dom tersebut dan otomatis menyuntikkan pemisah hanya pada <strong>sela-sela ruang dalam</strong> saja. Elemen paling atas dan bawah akan dibiarkan perawan tanpa intervensi modifikasi batas.
                                    </p>
                                </div>
                            </div>

                            <div class="bg-white dark:bg-[#0b0f19] border border-slate-200 dark:border-white/10 rounded-2xl p-6 lg:p-8 shadow-xl dark:shadow-2xl relative group hover:border-teal-400 dark:hover:border-teal-500/30 transition-all mt-8">
                                <h4 class="text-xs font-bold text-slate-400 dark:text-muted uppercase mb-4 text-center transition-colors tracking-widest">Simulator: Regulasi Daftar Elegan</h4>

                                <div class="bg-teal-50 dark:bg-teal-500/10 border border-teal-200 dark:border-teal-500/30 rounded-xl p-4 mb-8 text-sm text-teal-700 dark:text-teal-300 relative z-10 shadow-sm dark:shadow-inner transition-colors">
                                    <p class="font-bold flex items-center gap-2 mb-2">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"></path></svg>
                                        Panduan Pengujian
                                    </p>
                                    <p class="m-0 opacity-90 leading-relaxed text-xs md:text-sm text-teal-800/80 dark:text-teal-100/80 transition-colors">
                                        Klik pilihan sekat pembagi (<i>Divide</i>) di bawah untuk mengevaluasi bagaimana utilitas cerdas ini menertibkan jajaran menu pengaturan navigasi tanpa menghancurkan radius kontainer utama.
                                    </p>
                                </div>

                                <div class="flex flex-col items-center justify-center relative z-10">
                                    <div id="sim58-target" class="w-full max-w-sm bg-white dark:bg-slate-800 rounded-2xl shadow-lg border border-slate-200 dark:border-slate-700 transition-all duration-500 divide-y-0 divide-slate-200 dark:divide-slate-700 overflow-hidden">
                                        <div class="p-5 text-sm font-medium text-slate-700 dark:text-slate-300 flex justify-between items-center hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors cursor-pointer">
                                            Pengaturan Akun <span class="text-slate-400">→</span>
                                        </div>
                                        <div class="p-5 text-sm font-medium text-slate-700 dark:text-slate-300 flex justify-between items-center hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors cursor-pointer">
                                            Privasi Otentikasi <span class="text-slate-400">→</span>
                                        </div>
                                        <div class="p-5 text-sm font-bold text-rose-500 dark:text-rose-400 flex justify-between items-center hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-colors cursor-pointer">
                                            Keluar Sesi <span class="opacity-50">⎋</span>
                                        </div>
                                    </div>

                                    <div class="flex gap-2 w-full max-w-sm mt-8">
                                        <button onclick="updateSim58('divide-y-0')" class="btn-sim-58 flex-1 py-2.5 rounded-lg bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-white/50 border border-slate-200 dark:border-white/10 hover:bg-slate-200 dark:hover:bg-white/10 transition text-xs font-bold focus:outline-none">Menumpuk (0)</button>
                                        <button onclick="updateSim58('divide-y')" class="btn-sim-58 flex-1 py-2.5 rounded-lg bg-teal-600 text-white shadow-md border border-teal-400 transition text-xs font-bold focus:outline-none">Ideal (Y)</button>
                                        <button onclick="updateSim58('divide-y-4')" class="btn-sim-58 flex-1 py-2.5 rounded-lg bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-white/50 border border-slate-200 dark:border-white/10 hover:bg-slate-200 dark:hover:bg-white/10 transition text-xs font-bold focus:outline-none">Pisau Tebal (Y-4)</button>
                                    </div>

                                    <div class="mt-4 w-full max-w-sm bg-slate-100 dark:bg-black/40 p-4 rounded-xl font-mono text-[12px] text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-white/5 text-center transition-colors">
                                        &lt;div class="<span id="code-58" class="text-teal-600 dark:text-teal-400 font-bold">divide-y</span>"&gt;
                                    </div>
                                </div>

                                <div class="mt-8 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-100 dark:border-emerald-800/50 p-4 rounded-xl text-sm text-emerald-900 dark:text-emerald-100 flex items-start gap-4 shadow-sm z-20 transition-colors">
                                    <div class="p-2 bg-emerald-200 dark:bg-emerald-800/50 rounded-lg shrink-0">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <p id="demo-58-insight" class="insight-box m-0 leading-relaxed font-medium">Bagus sekali! Utilitas ajaib <code class="font-bold bg-white dark:bg-black/40 px-1 rounded text-teal-600 dark:text-teal-300">divide-y</code> otomatis membelah irisan celah antara menu tanpa menyumbat bagian atap atau membocorkan alas kotak elemen tersebut.</p>
                                </div>
                            </div>
                            
                            <div class="my-16 border-t border-slate-200 dark:border-white/10"></div>

                            <div class="space-y-4 border-l-4 border-rose-500 pl-6">
                                <h3 class="text-2xl md:text-3xl font-black text-slate-900 dark:text-white leading-[1.1] transition-colors">
                                    Elevasi Fokus: Rings Outline
                                </h3>
                            </div>

                            <div class="space-y-6">
                                <div class="prose prose-slate dark:prose-invert max-w-none text-slate-600 dark:text-white/70 text-base md:text-lg leading-relaxed transition-colors text-justify">
                                    <p>
                                        Pendekatan uzur dalam pemrograman masa lalu adalah menambah sintaks <code class="font-mono text-sm">border: 2px solid;</code> setiap kali user mengklik tombol (status <i>Focus</i>). 
                                        Imbasnya sangat buruk: tambahan tiba-tiba sebesar 2 piksel akan meronta memaksa seluruh form pendaftaran ikut meloncat turun untuk mengompensasi pertambahan ruang fisik tombol. Masalah ini dijuluki Janky Layout Shift.
                                    </p>
                                    
                                    <ul class="list-disc pl-5 space-y-4 mt-4 border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-white/5 p-6 rounded-xl transition-colors text-sm md:text-base text-left">
                                        <li><strong>Bayangan Virtual (<code class="text-rose-600 dark:text-rose-300 font-bold transition-colors">ring</code>):</strong> Tailwind memperkenalkan Rings yang tidak memakan jatah realitas tata letak elemen. Karena murni mengemulasikan manipulasi ilusi cahaya (<i>box-shadow</i>), cincin pelindung ini murni memancar di ruang bebas tanpa mengganggu tetangganya se-piksel pun.</li>
                                        <li><strong>Standar Apple OS (<code class="text-rose-600 dark:text-rose-300 font-bold transition-colors">ring-offset</code>):</strong> Untuk meraih nilai kemewahan maksimal ala produk Apple, padukan kekuatan Ring tadi dengan spasi pelebaran <code class="font-bold">ring-offset-2</code>. Ini akan mendemonstrasikan rongga potong terpisah di udara antara baju tombol dengan cahaya target fokusnya. Kelihatan sangat mahal dan profesional.</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="bg-white dark:bg-[#0b0f19] border border-slate-200 dark:border-white/10 rounded-2xl p-6 lg:p-8 shadow-xl dark:shadow-2xl relative group hover:border-rose-400 dark:hover:border-rose-500/30 transition-all mt-8">
                                <h4 class="text-xs font-bold text-slate-400 dark:text-muted uppercase mb-4 text-center transition-colors tracking-widest">Simulator: Simulasi Ring Fokus</h4>

                                <div class="bg-rose-50 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/30 rounded-xl p-4 mb-8 text-sm text-rose-700 dark:text-rose-300 relative z-10 shadow-sm dark:shadow-inner transition-colors">
                                    <p class="font-bold flex items-center gap-2 mb-2">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"></path></svg>
                                        Panduan Pengujian
                                    </p>
                                    <p class="m-0 opacity-90 leading-relaxed text-xs md:text-sm text-rose-800/80 dark:text-rose-100/80 transition-colors">
                                        Tekan variasi pemancar cincin di bawah untuk menstimulasikan kejadian tatkala keyboard-tab pengguna berlabuh ke atas tombol Call To Action.
                                    </p>
                                </div>

                                <div class="flex flex-col items-center justify-center relative z-10">
                                    <div class="h-32 w-full flex items-center justify-center bg-slate-100 dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-white/5 mb-6">
                                        <button id="sim59-target" class="px-8 py-3.5 bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-bold rounded-xl transition-all duration-300 ring-0 ring-rose-500 ring-offset-0 ring-offset-slate-100 dark:ring-offset-slate-900 outline-none transform shadow-xl focus:outline-none">
                                            Aksi Sentral
                                        </button>
                                    </div>

                                    <div class="flex gap-2 w-full max-w-lg mt-2">
                                        <button onclick="updateSim59('ring-0')" class="btn-sim-59 flex-1 py-2.5 rounded-lg bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-white/50 border border-slate-200 dark:border-white/10 hover:bg-slate-200 dark:hover:bg-white/10 transition text-[11px] font-bold focus:outline-none">Abaikan (0)</button>
                                        <button onclick="updateSim59('ring-4')" class="btn-sim-59 flex-1 py-2.5 rounded-lg bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-white/50 border border-slate-200 dark:border-white/10 hover:bg-slate-200 dark:hover:bg-white/10 transition text-[11px] font-bold focus:outline-none">Cincin Dasar (4)</button>
                                        <button onclick="updateSim59('ring-4 ring-offset-4')" class="btn-sim-59 flex-1 py-2.5 rounded-lg bg-rose-600 text-white shadow-md border border-rose-400 transition text-[11px] font-bold focus:outline-none">Premium Offset</button>
                                    </div>

                                    <div class="mt-4 w-full max-w-lg bg-slate-100 dark:bg-black/40 p-4 rounded-xl font-mono text-[12px] text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-white/5 text-center transition-colors">
                                        class="<span id="code-59" class="text-rose-600 dark:text-rose-400 font-bold">ring-4 ring-offset-4</span>"
                                    </div>
                                </div>

                                <div class="mt-8 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-100 dark:border-emerald-800/50 p-4 rounded-xl text-sm text-emerald-900 dark:text-emerald-100 flex items-start gap-4 shadow-sm z-20 transition-colors">
                                    <div class="p-2 bg-emerald-200 dark:bg-emerald-800/50 rounded-lg shrink-0">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <p id="demo-59-insight" class="insight-box m-0 leading-relaxed font-medium">Bintang kelasnya! Memadukan <code class="font-bold bg-white dark:bg-black/40 px-1 rounded text-rose-600 dark:text-rose-300">ring-4</code> dengan pelindung udara renggang bernilai <code class="font-bold bg-white dark:bg-black/40 px-1 rounded text-rose-600 dark:text-rose-300">ring-offset-4</code> mempresentasikan kemewahan arsitektur ring sempurna tanpa layout bergeser.</p>
                                </div>
                            </div>

                        </div>
                    </section>

                    {{-- FINAL CHALLENGE (EXPERT ACTIVITY) --}}
                    <section id="section-59" class="lesson-section scroll-mt-32 pt-10 border-t border-slate-200 dark:border-white/10 transition-colors" data-lesson-id="59" data-type="activity">
                        <div class="relative rounded-[2rem] md:rounded-[2.5rem] bg-white dark:bg-[#050b14] border border-slate-200 dark:border-white/10 p-6 md:p-10 overflow-hidden shadow-xl dark:shadow-2xl group hover:border-indigo-400 dark:hover:border-indigo-500/30 transition-all duration-500 flex flex-col">
                            
                            <div class="absolute -top-24 -right-24 w-64 h-64 bg-indigo-400/20 dark:bg-indigo-600/20 blur-[100px] rounded-full pointer-events-none transition-colors"></div>

                            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 sm:gap-6 mb-8 relative z-10 shrink-0">
                                <div class="p-3 sm:p-4 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl text-white shadow-lg border border-indigo-400/50 shrink-0">
                                    <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                                </div>
                                <div>
                                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-3 mb-2">
                                        <h2 class="text-xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight transition-colors">Aktivitas Expert: Profile Component Modern</h2>
                                        <span class="px-2 py-0.5 rounded text-[9px] sm:text-[10px] font-bold bg-amber-100 dark:bg-amber-500/20 text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-500/30 uppercase tracking-wider shadow-sm transition-colors">Evaluasi Live Code Editor</span>
                                    </div>
                                    <p class="text-slate-500 dark:text-slate-400 text-xs sm:text-sm leading-relaxed max-w-3xl text-justify transition-colors">
                                        Saatnya meramu semua teori menjadi produk nyata! Tugas kamu adalah melengkapi kode HTML pada terminal editor di bawah menggunakan utilitas batas (border) dan efek Tailwind. Bangun rancangan profil (Profile Component) yang rapi dengan dukungan cincin cahaya, elevasi garis, dan sekat pembelah. Sistem kecerdasan akan merender dan memvalidasi sintaksmumu secara instan (Real-time).
                                    </p>
                                    
                                    <div class="mt-4 flex flex-wrap items-center gap-2">
                                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mr-1 transition-colors">Kamus Utilitas Target:</span>
                                        <code class="text-[10px] md:text-xs font-mono font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-100 dark:bg-indigo-500/10 border border-indigo-200 dark:border-indigo-500/20 px-2 py-1 rounded shadow-sm transition-colors">rounded-full</code>
                                        <code class="text-[10px] md:text-xs font-mono font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-100 dark:bg-indigo-500/10 border border-indigo-200 dark:border-indigo-500/20 px-2 py-1 rounded shadow-sm transition-colors">ring-4</code>
                                        <code class="text-[10px] md:text-xs font-mono font-bold text-fuchsia-600 dark:text-fuchsia-400 bg-fuchsia-100 dark:bg-fuchsia-500/10 border border-fuchsia-200 dark:border-fuchsia-500/20 px-2 py-1 rounded shadow-sm transition-colors">border-b-4</code>
                                        <code class="text-[10px] md:text-xs font-mono font-bold text-teal-600 dark:text-teal-400 bg-teal-100 dark:bg-teal-500/10 border border-teal-200 dark:border-teal-500/20 px-2 py-1 rounded shadow-sm transition-colors">divide-y</code>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-col xl:grid xl:grid-cols-2 gap-0 border border-slate-200 dark:border-white/10 rounded-2xl overflow-hidden shadow-lg dark:shadow-2xl relative z-10 flex-1 transition-colors">
                                
                                <div class="bg-slate-50 dark:bg-[#151515] border-b xl:border-b-0 xl:border-r border-slate-200 dark:border-white/10 flex flex-col relative w-full xl:w-auto min-h-[500px] xl:min-h-[600px] transition-colors">
                                    
                                    <div id="lockOverlay" class="hidden absolute inset-0 bg-white/95 dark:bg-[#050912]/95 backdrop-blur-md z-50 flex flex-col items-center justify-center text-center p-8 transition-colors border-2 border-emerald-500/20">
                                        <div class="w-20 h-20 bg-emerald-100 dark:bg-emerald-500/10 rounded-full flex items-center justify-center mb-6 border border-emerald-300 dark:border-emerald-500/50 shadow-[0_0_50px_rgba(16,185,129,0.2)] animate-bounce transition-colors">
                                            <svg class="w-10 h-10 text-emerald-600 dark:text-emerald-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                        </div>
                                        <h3 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white mb-2 tracking-tight transition-colors">VALIDASI SINTAKS BERHASIL</h3>
                                        <p class="text-xs sm:text-sm font-bold text-slate-500 dark:text-white/60 mb-8 max-w-xs transition-colors">Penerapan arsitektur struktur batas dan efek elevasi Anda dieksekusi dengan sempurna. Anda dinyatakan sah menguasai materi Borders & Effects ini!</p>
                                        <button disabled class="w-full sm:w-auto px-8 py-3 rounded-full bg-slate-200 dark:bg-white/5 border border-slate-300 dark:border-white/10 text-slate-400 dark:text-white/30 text-[10px] sm:text-xs font-bold cursor-not-allowed uppercase tracking-widest transition-colors">Progres Terkunci (Selesai)</button>
                                    </div>

                                    <div class="bg-slate-100 dark:bg-[#1e1e1e] px-4 py-3 border-b border-slate-200 dark:border-white/5 flex justify-between items-center shrink-0 transition-colors">
                                        <span class="text-[10px] sm:text-xs text-slate-500 dark:text-white/50 font-mono font-bold transition-colors">Terminal Kode CSS HTML</span>
                                        <button onclick="resetEditor()" class="text-[10px] text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 uppercase font-bold focus:outline-none bg-red-100 dark:bg-red-500/10 px-3 py-1.5 rounded shadow-sm border border-red-200 dark:border-red-500/20 active:scale-95 transition">Reset Format</button>
                                    </div>
                                    
                                    <div id="codeEditor" class="flex-1 w-full border-b border-slate-200 dark:border-white/5 min-h-[250px] relative transition-colors"></div>

                                    <div class="p-5 bg-slate-50 dark:bg-[#0f141e] flex flex-col shrink-0 h-auto sm:h-[280px] transition-colors">
                                        <div class="flex justify-between items-center mb-3">
                                            <span class="text-[10px] uppercase font-bold text-slate-400 dark:text-white/30 tracking-widest transition-colors">Kriteria Pengerjaan Modul (Bot Valdiator)</span>
                                            <span id="progressText" class="text-[9px] sm:text-[10px] font-mono font-bold text-indigo-700 dark:text-indigo-400 bg-indigo-100 dark:bg-indigo-900/30 px-2 py-0.5 rounded border border-indigo-200 dark:border-indigo-500/20 shadow-inner transition-colors">0/3 Indikator Terpenuhi</span>
                                        </div>
                                        <div class="flex flex-col gap-4 text-xs sm:text-[13px] font-sans text-slate-700 dark:text-white/70 mb-4 flex-1 overflow-y-auto custom-scrollbar p-4 bg-white dark:bg-black/20 rounded-lg shadow-sm dark:shadow-inner border border-slate-200 dark:border-white/5 transition-colors">
                                            
                                            <div id="check-avatar" class="flex items-start gap-3">
                                                <span class="w-4 h-4 mt-0.5 rounded-full border-2 border-slate-300 dark:border-white/20 flex items-center justify-center text-[10px] shrink-0 transition-colors font-bold text-white"></span> 
                                                <div class="leading-relaxed">
                                                    <b class="block mb-1 text-slate-800 dark:text-white/90 transition-colors font-extrabold text-sm">1. Transformasi Avatar & Pendaran:</b> 
                                                    Pada tag gambar <code class="text-[11px] bg-slate-100 dark:bg-white/10 px-1 rounded text-rose-500 font-mono">id="profile-avatar"</code>, hapus kelas perusak seperti <code class="text-[10px] text-rose-500 line-through decoration-rose-500">rounded-none</code>, terapkan bentuk bulat presisi <code class="text-[10px] font-bold text-indigo-600 dark:text-indigo-400">rounded-full</code>, serta pasang cincin pemancar <code class="text-[10px] font-bold text-indigo-600 dark:text-indigo-400">ring-4</code>.
                                                </div>
                                            </div>
                                            
                                            <div id="check-btn" class="flex items-start gap-3">
                                                <span class="w-4 h-4 mt-0.5 rounded-full border-2 border-slate-300 dark:border-white/20 flex items-center justify-center text-[10px] shrink-0 transition-colors font-bold text-white"></span> 
                                                <div class="leading-relaxed">
                                                    <b class="block mb-1 text-slate-800 dark:text-white/90 transition-colors font-extrabold text-sm">2. Injeksi Ilusi Dorongan Bawah Tombol:</b> 
                                                    Pada komponen <code class="text-[11px] bg-slate-100 dark:bg-white/10 px-1 rounded text-rose-500 font-mono">id="action-btn"</code>, buang kepingan statis <code class="text-[10px] text-rose-500 line-through decoration-rose-500">border-0 rounded-none</code> dan tambahkan batas tebal hanya di lantai utamanya (<code class="text-[10px] font-bold text-fuchsia-600 dark:text-fuchsia-400">border-b-4</code>).
                                                </div>
                                            </div>
                                            
                                            <div id="check-list" class="flex items-start gap-3">
                                                <span class="w-4 h-4 mt-0.5 rounded-full border-2 border-slate-300 dark:border-white/20 flex items-center justify-center text-[10px] shrink-0 transition-colors font-bold text-white"></span> 
                                                <div class="leading-relaxed">
                                                    <b class="block mb-1 text-slate-800 dark:text-white/90 transition-colors font-extrabold text-sm">3. Pembedahan Data Elegan dengan Smart Divider:</b> 
                                                    Pada induk kontainer statistik pengikut di <code class="text-[11px] bg-slate-100 dark:bg-white/10 px-1 rounded text-rose-500 font-mono">id="stats-list"</code>, tanamkan pembelah horizontal canggih (<code class="text-[10px] font-bold text-teal-600 dark:text-teal-400">divide-y</code>) dan hapus tumpukan <code class="text-[10px] text-rose-500 line-through decoration-rose-500">divide-y-0</code>.
                                                </div>
                                            </div>
                                            
                                        </div>
                                        <button id="submitExerciseBtn" onclick="submitExercise()" disabled class="w-full py-2.5 sm:py-3 rounded-lg bg-emerald-600 text-white font-bold text-[11px] sm:text-xs shadow-lg shadow-emerald-500/20 hover:bg-emerald-500 hover:-translate-y-0.5 transition-all cursor-not-allowed opacity-50 flex items-center justify-center gap-2 focus:outline-none active:scale-95">
                                            <span>Merekam Kehadiran Sintaksis yang Valid...</span>
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                        </button>
                                    </div>
                                </div>

                                <div class="bg-slate-100 dark:bg-[#1e1e1e] flex flex-col relative overflow-hidden w-full xl:w-auto h-[400px] xl:h-auto transition-colors">
                                    <div class="bg-slate-200 dark:bg-[#2d2d2d] px-4 py-3 border-b border-slate-300 dark:border-white/5 flex items-center justify-between shrink-0 transition-colors">
                                        <span class="text-[10px] text-slate-500 dark:text-gray-400 font-mono font-bold transition-colors">Peninjau Visual Target Berjalan</span>
                                        <span class="text-[9px] sm:text-[10px] bg-emerald-100 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 px-2 py-0.5 rounded border border-emerald-200 dark:border-emerald-500/20 font-bold uppercase tracking-widest flex items-center gap-1.5 shadow-sm transition-colors">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse shadow-[0_0_5px_#10b981]"></span> Kompilasi Auto-Sync
                                        </span>
                                    </div>
                                    <div class="flex-1 bg-slate-100 dark:bg-gray-900 relative w-full h-full p-0 transition-colors">
                                        <div class="absolute inset-0 bg-checkered opacity-20 dark:opacity-30 pointer-events-none mix-blend-overlay"></div>
                                        <iframe id="previewFrame" class="w-full h-full border-0 bg-transparent relative z-10 custom-scrollbar"></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                </article>

                <div class="mt-32 pt-8 border-t border-slate-200 dark:border-white/10 flex flex-col sm:flex-row justify-between items-center gap-4 sm:gap-0 transition-colors">
                    <a href="{{ route('courses.backgrounds') ?? '#' }}" class="group flex items-center gap-4 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition w-full sm:w-auto justify-center sm:justify-start">
                        <div class="w-10 h-10 md:w-12 md:h-12 rounded-full border border-slate-200 dark:border-white/10 flex items-center justify-center bg-slate-100 dark:bg-transparent group-hover:bg-slate-200 dark:group-hover:bg-white/5 transition shrink-0">
                            <span class="text-lg group-hover:-translate-x-1 transition-transform">←</span>
                        </div>
                        <div class="text-center sm:text-left">
                            <div class="text-[10px] sm:text-xs uppercase tracking-widest font-bold opacity-50 mb-0.5">Sebelumnya</div>
                            <div class="font-bold text-xs md:text-sm line-clamp-1">Backgrounds</div>
                        </div>
                    </a>
                    
                    {{-- TOMBOL NEXT TERKUNCI --}}
                    <div id="nextChapterBtn" class="group flex items-center gap-4 text-right text-slate-500 cursor-not-allowed opacity-50 pointer-events-none transition-all duration-500 w-full sm:w-auto justify-center sm:justify-end flex-row-reverse sm:flex-row">
                        <div class="text-center sm:text-right">
                            <div id="nextLabel" class="text-[10px] sm:text-xs uppercase tracking-widest font-bold opacity-50 mb-0.5 text-rose-500 dark:text-rose-400 transition-colors">Berikutnya</div>
                            <div class="font-bold text-xs md:text-sm line-clamp-1">Efek Visual</div>
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

<script src="https://cdn.jsdelivr.net/npm/monaco-editor@0.45.0/min/vs/loader.js"></script>

<script>
    window.LESSON_IDS = [56, 57, 58, 59]; 
    const ACTIVITY_LESSON_ID = 59; 

    let rawCompletedIds = {!! json_encode($completedLessonIds ?? '[]') !!};
    window.COMPLETED_IDS = Array.isArray(rawCompletedIds) ? rawCompletedIds.map(id => Number(id)) : []; 

    let completedSet = new Set(window.COMPLETED_IDS);
    let activityCompleted = completedSet.has(ACTIVITY_LESSON_ID) || {!! ($activityCompleted ?? false) ? 'true' : 'false' !!};
    
    // Antrian request API agar tidak terjadi redudansi pemanggilan
    let pendingRequests = new Set();

    document.addEventListener('DOMContentLoaded', () => {
        initSidebarScroll();
        initVisualEffects();
        initMonaco();
        
        updateProgressUI(false); 
        
        if (activityCompleted) {
            lockActivityUI();
            unlockNextChapter();
        }

        initMasterObserver();
        
        window.LESSON_IDS.forEach(id => {
            if(completedSet.has(id)) {
                markSidebarDone(id);
            }
        });
    });

    function updateProgressUI(animate = true) {
        const total = window.LESSON_IDS.length; 
        const done = window.LESSON_IDS.filter(id => completedSet.has(Number(id))).length; 
        let percent = Math.round((done / total) * 100);
        
        const isActivityDone = completedSet.has(ACTIVITY_LESSON_ID);
        
        // Membatasi persentase maksimum jika belum menyelesaikan Expert Mode (ID 59)
        if (!isActivityDone && percent >= 100) {
             percent = Math.floor(((total - 1) / total) * 100); 
        }
        
        const bar = document.getElementById('topProgressBar');
        const label = document.getElementById('progressLabelTop');
        
        if(!animate && bar) bar.style.transition = 'none';
        if(bar) bar.style.width = percent + '%'; 
        if(!animate && bar) setTimeout(() => bar.style.transition = 'all 0.5s ease-out', 50);
        
        if(label) label.innerText = percent + '%';
        
        if(percent === 100 && isActivityDone) {
            unlockNextChapter();
        }
    }

    function markSidebarDone(lessonId) {
        const navItem = document.querySelector(`.nav-item[data-target="#section-${lessonId}"]`);
        if(navItem) {
            const dot = navItem.querySelector('.dot');
            if(dot) {
                const isActivity = navItem.querySelector('.sidebar-anchor')?.dataset.type === 'activity';
                if (isActivity) {
                    dot.outerHTML = `<svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400 shrink-0 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>`;
                } else {
                    dot.outerHTML = `<svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400 shrink-0 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>`;
                }
            }
        }
    }

    // Melakukan update optimis agar progress bar bergerak instan, request dikirim dibelakang layar
    async function saveLessonToDB(lessonId) { 
        lessonId = Number(lessonId);
        if(completedSet.has(lessonId) || pendingRequests.has(lessonId)) return; 

        pendingRequests.add(lessonId);
        completedSet.add(lessonId); 
        updateProgressUI(true);
        markSidebarDone(lessonId);

        try {
            const formData = new FormData();
            formData.append('lesson_id', lessonId);
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            const response = await fetch('{{ route("lesson.complete") }}', { 
                method: 'POST', 
                headers: { 
                    'X-CSRF-TOKEN': csrfToken || '',
                    'Accept': 'application/json' 
                }, 
                body: formData 
            });

            if (response.ok) {
                pendingRequests.delete(lessonId);
            } else {
                throw new Error("Gagal mengamankan log laporan.");
            }
        } catch(e) {
            console.error('Network Error:', e);
            // Kembalikan UI jika gagal (opsional)
            completedSet.delete(lessonId);
            pendingRequests.delete(lessonId);
            updateProgressUI(true);
        }
    }

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

    let editor;
    const starterCode = `<div class="min-h-screen flex items-center justify-center font-sans p-8 text-slate-800">
  <div class="w-full max-w-sm bg-white rounded-3xl overflow-hidden shadow-2xl border border-slate-200">
    
    <div class="h-28 bg-gradient-to-r from-indigo-500 to-purple-600"></div>

    <div class="px-8 pb-8 relative text-center">
      
      <div class="flex justify-center -mt-10 mb-5 relative z-10">
        <img id="profile-avatar" src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=150&h=150" 
             class="w-20 h-20 object-cover bg-slate-200 border-4 border-white rounded-none">
      </div>

      <h3 class="font-black text-xl mb-1">Satria Coder</h3>
      <p class="text-indigo-600 text-xs font-bold uppercase tracking-widest mb-8">Backend Engineer</p>

      <button id="action-btn" class="w-full py-3 bg-indigo-600 text-white text-xs font-bold tracking-widest mb-8 transition-all active:translate-y-1 rounded-none border-0 border-indigo-800">
        HUBUNGI SAYA
      </button>

      <div id="stats-list" class="text-xs text-slate-600 font-medium rounded-xl bg-slate-50 border border-slate-200 text-left flex flex-col divide-y-0 divide-slate-200">
        <div class="px-5 py-4 flex justify-between">
            <span>Repositori Kode</span> <span class="font-black text-slate-900 text-sm">342</span>
        </div>
        <div class="px-5 py-4 flex justify-between">
            <span>Koneksi Rekan</span> <span class="font-black text-slate-900 text-sm">18.5k</span>
        </div>
        <div class="px-5 py-4 flex justify-between">
            <span>Klien Korporasi</span> <span class="font-black text-slate-900 text-sm">120</span>
        </div>
      </div>

    </div>
  </div>
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
                renderLineHighlight: "none",
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
                validateCodeDOM(code);
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
                .custom-scrollbar::-webkit-scrollbar { width: 5px; }
                .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(150,150,150,0.3); border-radius: 10px; }
            </style>
        </head>
        <body class="w-full h-full flex items-start justify-center m-0 custom-scrollbar overflow-x-hidden bg-transparent text-slate-800">
            ${code}
        </body>
        </html>`;
        frame.srcdoc = content;
    }

    function validateCodeDOM(code) {
        let passed = 0;
        
        const parser = new DOMParser();
        const doc = parser.parseFromString(code, 'text/html');
        
        const avatarEl = doc.getElementById('profile-avatar');
        const btnEl = doc.getElementById('action-btn');
        const listEl = doc.getElementById('stats-list');

        const avatarCls = avatarEl ? avatarEl.className : '';
        const btnCls = btnEl ? btnEl.className : '';
        const listCls = listEl ? listEl.className : '';

        const checks = [
            { id: 'check-avatar', valid: avatarCls.includes('rounded-full') && avatarCls.includes('ring') && !avatarCls.includes('rounded-none') },
            { id: 'check-btn', valid: btnCls.includes('border-b-') && !btnCls.includes('rounded-none') && !btnCls.includes('border-0') },
            { id: 'check-list', valid: listCls.includes('divide-y') && !listCls.includes('divide-y-0') }
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
                dot.classList.remove('border-slate-300', 'dark:border-white/20');
                dot.classList.add('bg-emerald-500', 'border-transparent', 'text-white');
                passed++;
            } else {
                textDiv.classList.remove('text-emerald-500', 'dark:text-emerald-400');
                textDiv.querySelector('b').classList.remove('text-emerald-600', 'dark:text-emerald-300');
                textDiv.querySelector('b').classList.add('text-slate-800', 'dark:text-white/90');
                dot.innerHTML = ''; 
                dot.classList.remove('bg-emerald-500', 'border-transparent', 'text-white');
                dot.classList.add('border-slate-300', 'dark:border-white/20');
            }
        });

        document.getElementById('progressText').innerText = passed + '/3 Status Evaluasi Bersih';
        
        const btn = document.getElementById('submitExerciseBtn');
        if (passed === 3) {
            btn.disabled = false;
            btn.classList.remove('cursor-not-allowed', 'opacity-50');
            btn.innerHTML = '<span>Latihan Telah Sukses Terjawab! Simpan Catatan Permanen.</span><svg class="w-4 h-4 sm:w-5 sm:h-5 shrink-0 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
            
            document.getElementById('progressText').classList.replace('text-indigo-700', 'text-white');
            document.getElementById('progressText').classList.replace('dark:text-indigo-400', 'text-white');
            document.getElementById('progressText').classList.replace('bg-indigo-100', 'bg-emerald-500');
            document.getElementById('progressText').classList.replace('dark:bg-indigo-900/30', 'bg-emerald-500');
            document.getElementById('progressText').classList.replace('border-indigo-200', 'border-emerald-400');
            document.getElementById('progressText').classList.replace('dark:border-indigo-500/20', 'border-emerald-400');

        } else {
            btn.disabled = true;
            btn.classList.add('cursor-not-allowed', 'opacity-50');
            btn.innerHTML = '<span>Penuhi Kriteria Deteksi Validasi Sistem Di Atas</span><svg class="w-4 h-4 sm:w-5 sm:h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>';
            
            document.getElementById('progressText').classList.replace('text-white', 'text-indigo-700');
            document.getElementById('progressText').classList.replace('text-white', 'dark:text-indigo-400');
            document.getElementById('progressText').classList.replace('bg-emerald-500', 'bg-indigo-100');
            document.getElementById('progressText').classList.replace('bg-emerald-500', 'dark:bg-indigo-900/30');
            document.getElementById('progressText').classList.replace('border-emerald-400', 'border-indigo-200');
            document.getElementById('progressText').classList.replace('border-emerald-400', 'dark:border-indigo-500/20');
        }
    }

    function resetEditor() { 
        if(editor && !activityCompleted) { 
            editor.setValue(starterCode); 
            validateCodeDOM(starterCode); 
        } 
    }

    async function submitExercise() {
        const btn = document.getElementById('submitExerciseBtn');
        btn.innerHTML = '<span class="animate-pulse">Sistem Sinkronisasi Sedang Merangkum...</span>'; 
        btn.disabled = true;
        
        try {
            await fetch('/activity/complete', { 
                method: 'POST', 
                headers: { 
                    'X-CSRF-TOKEN': '{{ csrf_token() }}', 
                    'Accept': 'application/json', 
                    'Content-Type': 'application/json' 
                }, 
                body: JSON.stringify({ activity_id: 10, score: 100 }) // Adjust Activity ID accordingly
            });
            await saveLessonToDB(ACTIVITY_LESSON_ID); 
            activityCompleted = true;
            lockActivityUI();   
            unlockNextChapter(); 
        } catch(e) { 
            console.error(e); 
            btn.innerHTML = "Penyimpanan Kegagalan Sinkronisasi. Tekan Kembali.";
            btn.disabled = false;
            btn.classList.add('shake'); setTimeout(() => btn.classList.remove('shake'), 500);
        }
    }

    function lockActivityUI() {
        document.getElementById('lockOverlay').classList.remove('hidden');
        if(editor) editor.updateOptions({ readOnly: true });
        
        const btn = document.getElementById('submitExerciseBtn'); 
        btn.innerText = "Target Kompetensi Terselesaikan Sepenuhnya."; 
        btn.disabled = true;
        btn.classList.remove('bg-emerald-600', 'hover:bg-emerald-500');
        btn.classList.add('bg-slate-200', 'dark:bg-slate-700', 'text-slate-500', 'dark:text-slate-400', 'cursor-not-allowed', 'shadow-none');
        
        if(editor && activityCompleted) {
            editor.setValue(`<div class="min-h-screen flex items-center justify-center font-sans p-8 text-slate-800">\n  <div class="w-full max-w-sm bg-white rounded-3xl overflow-hidden shadow-2xl border border-slate-200">\n    \n    \n    <div class="h-28 bg-gradient-to-r from-indigo-500 to-purple-600"></div>\n\n    <div class="px-8 pb-8 relative text-center">\n      \n      \n      <div class="flex justify-center -mt-10 mb-5 relative z-10">\n        <img id="profile-avatar" src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=150&h=150" \n             class="w-20 h-20 object-cover bg-slate-200 border-4 border-white rounded-full ring-4 ring-indigo-500/50 ring-offset-2">\n      </div>\n\n      <h3 class="font-black text-xl mb-1">Satria Coder</h3>\n      <p class="text-indigo-600 text-xs font-bold uppercase tracking-widest mb-8">Backend Engineer</p>\n\n      \n      <button id="action-btn" class="w-full py-3 bg-indigo-600 text-white text-xs font-bold tracking-widest mb-8 transition-all active:translate-y-1 rounded-xl border-b-4 border-indigo-800 hover:bg-indigo-500">\n        HUBUNGI SAYA\n      </button>\n\n      \n      <div id="stats-list" class="text-xs text-slate-600 font-medium rounded-xl bg-slate-50 border border-slate-200 text-left flex flex-col divide-y divide-slate-200">\n        <div class="px-5 py-4 flex justify-between">\n            <span>Repositori Kode</span> <span class="font-black text-slate-900 text-sm">342</span>\n        </div>\n        <div class="px-5 py-4 flex justify-between">\n            <span>Koneksi Rekan</span> <span class="font-black text-slate-900 text-sm">18.5k</span>\n        </div>\n        <div class="px-5 py-4 flex justify-between">\n            <span>Klien Korporasi</span> <span class="font-black text-slate-900 text-sm">120</span>\n        </div>\n      </div>\n\n    </div>\n  </div>\n</div>`);
            validateCodeDOM(editor.getValue());
        }
    }

    function unlockNextChapter() {
        const btn = document.getElementById('nextChapterBtn');
        if(btn) {
            btn.classList.remove('cursor-not-allowed', 'opacity-50', 'pointer-events-none', 'text-slate-500');
            btn.classList.add('text-indigo-600', 'dark:text-indigo-400', 'hover:text-indigo-700', 'dark:hover:text-white', 'cursor-pointer');
            
            document.getElementById('nextLabel').innerText = "Berikutnya";
            document.getElementById('nextLabel').classList.remove('opacity-50', 'text-rose-500', 'dark:text-rose-400');
            document.getElementById('nextLabel').classList.add('text-indigo-600', 'dark:text-indigo-400', 'opacity-100');
            
            const icon = document.getElementById('nextIcon');
            icon.innerHTML = `<svg class="w-5 h-5 md:w-6 md:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>`;
            icon.classList.remove('bg-slate-100', 'dark:bg-white/5');
            icon.classList.add('bg-indigo-100', 'dark:bg-indigo-500/10', 'border-indigo-300', 'dark:border-indigo-500/30', 'text-indigo-600', 'dark:text-indigo-400', 'shadow-md', 'dark:shadow-lg', 'group-hover:scale-105', 'transition-transform');
            
            btn.onclick = () => window.location.href = "{{ route('courses.effects') ?? '#' }}"; 
        }
    }

    // Micro-Simulators Handlers (56, 57, 58, 59)
    
    // Sim 56
    window.updateSim56 = function(type, val) {
        const target = document.getElementById('sim56-target');
        const codeR = document.getElementById('code-56-r');
        const codeW = document.getElementById('code-56-w');
        const explText = document.getElementById('demo-56-insight');

        if(type === 'radius') {
            target.classList.remove('rounded-none', 'rounded-xl', 'rounded-full');
            target.classList.add(val);
            codeR.innerText = val;
            const btns = document.querySelectorAll('.btn-sim-56-rad');
            btns.forEach(b => {
                b.classList.remove('bg-indigo-600', 'text-white', 'border-indigo-400', 'shadow-md');
                b.classList.add('bg-white', 'dark:bg-white/5', 'text-slate-600', 'dark:text-white/50', 'border-slate-200', 'dark:border-white/10');
            });
            event.target.classList.add('bg-indigo-600', 'text-white', 'border-indigo-400', 'shadow-md');
            event.target.classList.remove('bg-white', 'dark:bg-white/5', 'text-slate-600', 'dark:text-white/50', 'border-slate-200', 'dark:border-white/10');
        } else {
            target.classList.remove('border-0', 'border-4', 'border-b-8');
            target.classList.add(val);
            codeW.innerText = val;
            const btns = document.querySelectorAll('.btn-sim-56-wid');
            btns.forEach(b => {
                b.classList.remove('bg-fuchsia-600', 'text-white', 'border-fuchsia-400', 'shadow-md');
                b.classList.add('bg-white', 'dark:bg-white/5', 'text-slate-600', 'dark:text-white/50', 'border-slate-200', 'dark:border-white/10');
            });
            event.target.classList.add('bg-fuchsia-600', 'text-white', 'border-fuchsia-400', 'shadow-md');
            event.target.classList.remove('bg-white', 'dark:bg-white/5', 'text-slate-600', 'dark:text-white/50', 'border-slate-200', 'dark:border-white/10');
        }

        let msg = "";
        let rad = codeR.innerText;
        let wid = codeW.innerText;
        if (rad === 'rounded-full' && wid === 'border-4') msg = "Menggabungkan <code class='font-bold'>rounded-full</code> dengan <code class='font-bold'>border-4</code> menyulap bentuk kotak dasar ini menjadi layaknya frame avatar bundar modern.";
        else if (rad === 'rounded-none' && wid === 'border-0') msg = "Terlalu polos. Bentuk <code class='font-bold'>rounded-none</code> tanpa garis seringnya hanya dimanfaatkan untuk area background latar belakang mutlak.";
        else if (wid === 'border-b-8') msg = "Brilian! Memberikan penekanan ekstra di sisi bawah (<code class='font-bold'>border-b-8</code>) menciptakan ilusi seolah komponen memiliki alas bantalan (3D Push) yang bisa dipencet ke layar.";
        else if (rad === 'rounded-xl') msg = "Standar estetik. Pilihan kelengkungan menengah seperti <code class='font-bold'>rounded-xl</code> adalah nilai teraman untuk bingkai kontainer desain Card UI manapun.";
        else msg = "Eksplorasi berani pada pertahanan struktur bingkai garis ketebalan berpadu kelembutan kelengkungan sudut yang tajam.";
        explText.innerHTML = msg;
        explText.classList.remove('insight-box'); void explText.offsetWidth; explText.classList.add('insight-box');
    }

    // Sim 57
    window.updateSim57 = function(val) {
        const target = document.getElementById('sim57-target');
        const code = document.getElementById('code-57');
        const explText = document.getElementById('demo-57-insight');

        target.classList.remove('border-solid', 'border-dashed', 'border-dotted');
        target.classList.add(val);
        code.innerText = val;
        
        const btns = document.querySelectorAll('.btn-sim-57');
        btns.forEach(b => {
            b.classList.remove('bg-sky-600', 'text-white', 'shadow-md', 'border-sky-400');
            b.classList.add('bg-slate-100', 'dark:bg-white/5', 'text-slate-600', 'dark:text-white/50', 'border-slate-200', 'dark:border-white/10');
        });
        event.target.classList.add('bg-sky-600', 'text-white', 'shadow-md', 'border-sky-400');
        event.target.classList.remove('bg-slate-100', 'dark:bg-white/5', 'text-slate-600', 'dark:text-white/50', 'border-slate-200', 'dark:border-white/10');

        if(val === 'border-solid') explText.innerHTML = "Gaya Solid (<code class='font-bold'>border-solid</code>) memancarkan kesan tersegel tertutup dengan ketat, rasanya kurang cocok kalau digunakan untuk elemen dropzone interaktif yang meminta user memasukkan benda.";
        if(val === 'border-dashed') explText.innerHTML = "Sangat disarankan. Desain patah (<code class='font-bold'>border-dashed</code>) memprovokasi kesadaran user secara intuitif di alam bawah sadarnya bahwa 'ada ruang kosong rumpang di sini yang perlu saya isi dengan berkas'.";
        if(val === 'border-dotted') explText.innerHTML = "Estetik tapi terlalu rentan. Pola mikro bintik dari <code class='font-bold'>border-dotted</code> hanya cocok digunakan sebagai pembatas halus kupon transaksi yang siap diiris.";
        explText.classList.remove('insight-box'); void explText.offsetWidth; explText.classList.add('insight-box');
    }

    // Sim 58
    window.updateSim58 = function(val) {
        const target = document.getElementById('sim58-target');
        const code = document.getElementById('code-58');
        const explText = document.getElementById('demo-58-insight');

        target.classList.remove('divide-y-0', 'divide-y', 'divide-y-4');
        target.classList.add(val);
        code.innerText = val;
        
        const btns = document.querySelectorAll('.btn-sim-58');
        btns.forEach(b => {
            b.classList.remove('bg-teal-600', 'text-white', 'shadow-md', 'border-teal-400');
            b.classList.add('bg-slate-100', 'dark:bg-white/5', 'text-slate-600', 'dark:text-white/50', 'border-slate-200', 'dark:border-white/10');
        });
        event.target.classList.add('bg-teal-600', 'text-white', 'shadow-md', 'border-teal-400');
        event.target.classList.remove('bg-slate-100', 'dark:bg-white/5', 'text-slate-600', 'dark:text-white/50', 'border-slate-200', 'dark:border-white/10');

        if(val === 'divide-y-0') explText.innerHTML = "Tumpukan berhimpitan statis. Bawaan <code class='font-bold'>divide-y-0</code> menyulitkan pengguna bernavigasi dari jarak jauh tanpa panduan kursor melayang karena tak ada petunjuk spasial garis lintang pembeda.";
        if(val === 'divide-y') explText.innerHTML = "Bagus sekali! Utilitas cerdas <code class='font-bold'>divide-y</code> menganalisis formasi daftar dan secara presisi menyayat garis pemisah hanya tepat di tulang sendi tiap anak elemennya saja, melestarikan bingkai atas dan alas luar kotaknya.";
        if(val === 'divide-y-4') explText.innerHTML = "Eksperimen drastis. Pelebaran ukuran semacam <code class='font-bold'>divide-y-4</code> sanggup menembakkan efek dinding tebal yang mencolok antar batasan garis vertikal. Dipakai khusus untuk blok pemisah keras modul UI terpisah.";
        explText.classList.remove('insight-box'); void explText.offsetWidth; explText.classList.add('insight-box');
    }

    // Sim 59
    window.updateSim59 = function(val) {
        const target = document.getElementById('sim59-target');
        const code = document.getElementById('code-59');
        const explText = document.getElementById('demo-59-insight');

        target.classList.remove('ring-0', 'ring-4', 'ring-offset-4', 'ring-offset-slate-100', 'dark:ring-offset-slate-900', 'ring-offset-slate-900');
        
        if(val.includes('ring-offset-4')) {
            target.classList.add('ring-offset-4', 'ring-offset-slate-100', 'dark:ring-offset-slate-900');
        }
        
        const coreClass = val.split(' ')[0];
        target.classList.add(coreClass);
        
        code.innerText = val;
        
        const btns = document.querySelectorAll('.btn-sim-59');
        btns.forEach(b => {
            b.classList.remove('bg-rose-600', 'text-white', 'shadow-md', 'border-rose-400');
            b.classList.add('bg-slate-100', 'dark:bg-white/5', 'text-slate-600', 'dark:text-white/50', 'border-slate-200', 'dark:border-white/10');
        });
        event.target.classList.add('bg-rose-600', 'text-white', 'shadow-md', 'border-rose-400');
        event.target.classList.remove('bg-slate-100', 'dark:bg-white/5', 'text-slate-600', 'dark:text-white/50', 'border-slate-200', 'dark:border-white/10');

        if(coreClass === 'ring-0') explText.innerHTML = "Buta fokus. Menihilkan garis cahaya (<code class='font-bold'>ring-0</code>) merampas nyawa aksesibilitas. Jika pengguna menyusuri website dengan tab tombol keyboard, dia tidak akan tahu di elemen navigasi mana posisi kursor aslinya menetap.";
        if(coreClass === 'ring-4' && !val.includes('offset')) explText.innerHTML = "Memuaskan. Bayangan utuh (<code class='font-bold'>ring-4</code>) mengelilingi komponen layaknya dinding ilusi cahaya tanpa mengubah besaran kotak (pikselnya bebas dari layout pergeseran paksa). Cukup elegan menyambut sentuhan.";
        if(val.includes('ring-offset-4')) explText.innerHTML = "Kualitas desainer dunia! Mengkolaborasikan cincin pendar <code class='font-bold'>ring-4</code> dipisahkan parit sela transparan sebesar <code class='font-bold'>ring-offset-4</code> murni menyimulasikan nuansa tombol komponen premium sekelas desain operasi Apple iOS dan MacOS.";
        explText.classList.remove('insight-box'); void explText.offsetWidth; explText.classList.add('insight-box');
    }

    function highlightAnchor(id) {
        const anchors = document.querySelectorAll('.sidebar-anchor');

        anchors.forEach(a => {
            a.classList.remove('bg-slate-100', 'dark:bg-white/5', 'border-indigo-500', 'border-amber-500');
            a.classList.add('border-transparent');
            
            const dot = a.querySelector('.anchor-dot');
            const isActivity = a.dataset.type === 'activity';

            if(dot) dot.classList.remove('scale-125', 'shadow-sm', 'dark:shadow-[0_0_10px_#6366f1]', 'dark:shadow-[0_0_10px_#f59e0b]', 'bg-indigo-500', 'dark:bg-indigo-400', 'bg-cyan-500', 'dark:bg-cyan-400');
            
            if (isActivity) {
                if(dot) { dot.classList.remove('bg-amber-400'); dot.classList.add('bg-slate-300', 'dark:bg-slate-600'); }
            } else {
                if(dot) { dot.classList.remove('bg-indigo-500', 'dark:bg-indigo-400'); dot.classList.add('bg-slate-300', 'dark:bg-slate-600'); }
            }

            const text = a.querySelector('.anchor-text');
            if(text) { text.classList.remove('text-slate-900', 'dark:text-white', 'font-bold'); text.classList.add('text-slate-500', 'dark:text-slate-500'); }
        });

        const activeAnchor = document.querySelector(`.sidebar-anchor[data-target="${id}"]`);
        if (activeAnchor) {
            const isActivity = activeAnchor.dataset.type === 'activity';
            
            activeAnchor.classList.add('bg-slate-100', 'dark:bg-white/5');
            activeAnchor.classList.add(isActivity ? 'border-amber-500' : 'border-indigo-500');
            activeAnchor.classList.remove('border-transparent');
            
            const dot = activeAnchor.querySelector('.anchor-dot');
            if(dot) {
                dot.classList.remove('bg-slate-300', 'dark:bg-slate-600');
                if (isActivity) {
                    dot.classList.add('bg-amber-500', 'dark:bg-amber-400', 'scale-125', 'shadow-sm', 'dark:shadow-[0_0_10px_#f59e0b]');
                } else {
                    dot.classList.add('bg-indigo-600', 'dark:bg-indigo-400', 'scale-125', 'shadow-sm', 'dark:shadow-[0_0_10px_#6366f1]');
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

    // SCROLL SPY PENYEMPURNAAN MUTLAK (ANTI-NEMPEL)
    function initSidebarScroll() {
        const m = document.getElementById('mainScroll');
        const sections = document.querySelectorAll('.lesson-section');
        const navItems = document.querySelectorAll('.nav-item');

        if (!m || sections.length === 0 || navItems.length === 0) return;

        let isClickScrolling = false;

        const syncScrollSpy = () => {
            if (isClickScrolling) return;

            let currentId = '';
            // Kompensasi jarak hitung agar transisi sidebar responsif saat section berada di bawah header
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
                
                // Pembersihan Total
                item.classList.remove('active');
                item.className = item.className.replace(/\b(border-(cyan|blue|indigo|purple|amber|rose|emerald|fuchsia|sky|teal)-\d+|bg-(slate|white)\/\d+|bg-slate-\d+)\b/g, '').trim();
                item.classList.add('border-transparent');
                
                // Penyorotan Menu Aktif Saja
                if (target === currentId) {
                    item.classList.add('active');
                    item.classList.remove('border-transparent');
                    
                    const isActivity = item.dataset.type === 'activity' || (item.innerText && item.innerText.toLowerCase().includes('latihan')) || (item.innerText && item.innerText.toLowerCase().includes('mission'));
                    
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
                        k.className = k.className.replace(/\b(border-(cyan|blue|indigo|purple|amber|rose|emerald|fuchsia|sky|teal)-\d+|bg-(slate|white)\/\d+|bg-slate-\d+)\b/g, '').trim();
                        k.classList.add('border-transparent');
                    });
                    
                    item.classList.remove('border-transparent');
                    item.classList.add('active', 'bg-slate-100', 'dark:bg-white/5');
                    
                    const isActivity = item.dataset.type === 'activity' || (item.innerText && item.innerText.toLowerCase().includes('latihan')) || (item.innerText && item.innerText.toLowerCase().includes('mission'));
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