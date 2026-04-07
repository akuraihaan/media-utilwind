@extends('layouts.landing')
@section('title', 'Efek Visual Masterclass')

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
        --accent: #3b82f6; /* Blue Primary */
        --accent-glow: rgba(59, 130, 246, 0.3);
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
        --accent-glow: rgba(59, 130, 246, 0.5);
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
        background: radial-gradient(800px circle at 20% 20%, rgba(168,85,247,.08), transparent 40%), 
                    radial-gradient(800px circle at 80% 80%, rgba(59,130,246,.08), transparent 40%); 
        animation: bgMove 20s ease-in-out infinite alternate; 
    }
    .dark #animated-bg {
        background: radial-gradient(800px circle at 20% 20%, rgba(168,85,247,.15), transparent 40%), 
                    radial-gradient(800px circle at 80% 80%, rgba(59,130,246,.15), transparent 40%); 
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
    
    .nav-item { display: flex; width: 100%; text-align: left; align-items: center; gap: 12px; padding: 10px 14px; font-size: 0.85rem; color: var(--text-muted); border-radius: 8px; transition: all 0.2s; position: relative; }
    .nav-item:hover { color: var(--text-main); background: var(--card-hover); }
    .nav-item.active { color: #3b82f6; background: rgba(59, 130, 246, 0.05); font-weight: 600; }
    .dot { width: 6px; height: 6px; border-radius: 50%; background: #94a3b8; transition: all 0.3s; }
    .dark .dot { background: #334155; }
    .nav-item.active .dot { background: #3b82f6; box-shadow: 0 0 8px #3b82f6; transform: scale(1.2); }
    .dark .nav-item.active .dot { background: #60a5fa; box-shadow: 0 0 8px #60a5fa; }

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

    /* Modern Dropdown Styling */
    select.sim-dropdown {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        background-size: 1em;
    }
    .dark select.sim-dropdown {
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
    }

    /* Range Slider Styling */
    input[type=range] { -webkit-appearance: none; width: 100%; background: transparent; outline: none; }
    input[type=range]::-webkit-slider-thumb { -webkit-appearance: none; height: 18px; width: 18px; border-radius: 50%; background: #3b82f6; cursor: pointer; margin-top: -7px; box-shadow: 0 0 10px rgba(59,130,246,0.6); transition: transform 0.15s ease, background 0.15s ease; border: 2px solid #fff; }
    .dark input[type=range]::-webkit-slider-thumb { border-color: #1e293b; }
    input[type=range]::-webkit-slider-thumb:hover { transform: scale(1.15); background: #2563eb; }
    input[type=range]::-webkit-slider-runnable-track { width: 100%; height: 4px; cursor: pointer; background: #cbd5e1; border-radius: 4px; transition: background 0.3s; }
    .dark input[type=range]::-webkit-slider-runnable-track { background: #334155; }
    input[type=range]:focus::-webkit-slider-runnable-track { background: #94a3b8; }
    .dark input[type=range]:focus::-webkit-slider-runnable-track { background: #475569; }
</style>

<button id="mobileSidebarToggle" class="lg:hidden fixed bottom-6 right-6 z-50 p-4 rounded-full shadow-2xl bg-blue-600 text-white hover:bg-blue-500 focus:outline-none transition-transform hover:scale-110">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
    </svg>
</button>

<div id="mobileOverlay" onclick="toggleMobileSidebar()"></div>

<div id="courseRoot" class="relative h-screen bg-slate-50 dark:bg-[#020617] text-slate-800 dark:text-white font-sans overflow-hidden flex flex-col selection:bg-blue-500/30 pt-20 transition-colors duration-500">

    <div class="fixed inset-0 -z-50 pointer-events-none">
        <div id="animated-bg" class="absolute inset-0 opacity-50 transition-opacity duration-500"></div>
        <div class="absolute top-[-10%] right-[-10%] w-[600px] h-[600px] bg-purple-300/30 dark:bg-purple-900/10 rounded-full blur-[120px] animate-pulse transition-colors duration-500"></div>
        <div class="absolute bottom-[-10%] left-[-10%] w-[500px] h-[500px] bg-blue-300/30 dark:bg-blue-900/10 rounded-full blur-[100px] transition-colors duration-500"></div>
        <canvas id="stars" class="absolute inset-0 pointer-events-none opacity-0 dark:opacity-100 transition-opacity duration-500"></canvas>
    </div>

    @include('layouts.partials.navbar')

    <div class="flex flex-1 overflow-hidden relative z-20">
        @include('layouts.partials.course-sidebar')

        <main id="mainScroll" class="flex-1 h-full overflow-y-auto scroll-smooth relative bg-transparent custom-scrollbar scroll-padding-top-24">
            
            <div id="stickyHeader" class="sticky top-0 z-30 w-full bg-white/80 dark:bg-[#020617]/80 backdrop-blur-2xl border-b border-slate-200 dark:border-white/5 px-4 md:px-8 py-4 flex items-center justify-between transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-gradient-to-br dark:from-blue-500/20 dark:to-transparent border border-blue-200 dark:border-blue-500/20 flex items-center justify-center font-bold text-xs text-blue-600 dark:text-blue-400 shrink-0 transition-colors">3.4</div>
                    <div>
                        <h1 class="text-sm font-bold text-slate-900 dark:text-white line-clamp-1 transition-colors">Efek Visual Masterclass</h1>
                        <p class="text-[10px] text-slate-500 dark:text-white/50 line-clamp-1 transition-colors">Bayangan, Filter & Animasi Transisi</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <div class="hidden sm:block w-24 md:w-32 h-1.5 bg-slate-200 dark:bg-white/10 rounded-full overflow-hidden transition-colors">
                        <div id="topProgressBar" class="h-full bg-gradient-to-r from-blue-400 to-indigo-500 dark:from-blue-500 dark:to-indigo-500 w-0 transition-all duration-500 shadow-[0_0_10px_#3b82f6]"></div>
                    </div>
                    <span id="progressLabelTop" class="text-blue-600 dark:text-blue-400 font-bold text-xs transition-colors">0%</span>
                </div>
            </div>

            <div class="p-6 lg:p-16 max-w-5xl mx-auto pb-40">
                
                <div class="mb-24">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-2 transition-colors">
                        <svg class="w-5 h-5 text-blue-500 dark:text-blue-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Target Pembelajaran
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                        
                        <div class="bg-white dark:bg-[#1e1e1e] border border-slate-200 dark:border-white/5 p-5 rounded-xl flex items-start gap-4 hover:border-purple-400 dark:hover:border-purple-500/30 shadow-sm dark:shadow-none transition group h-full">
                            <div class="w-8 h-8 rounded bg-purple-100 dark:bg-purple-500/10 text-purple-600 dark:text-purple-400 flex items-center justify-center shrink-0 font-bold text-xs transition-colors">1</div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800 dark:text-white mb-1 transition-colors">Ilusi Kedalaman</h4>
                                <p class="text-[11px] text-slate-500 dark:text-white/50 leading-relaxed transition-colors">Memahami hierarki visual objek 3D melalui manipulasi bayangan (<code class="font-bold text-purple-500">shadow</code>).</p>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-[#1e1e1e] border border-slate-200 dark:border-white/5 p-5 rounded-xl flex items-start gap-4 hover:border-blue-400 dark:hover:border-blue-500/30 shadow-sm dark:shadow-none transition group h-full">
                            <div class="w-8 h-8 rounded bg-blue-100 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0 font-bold text-xs transition-colors">2</div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800 dark:text-white mb-1 transition-colors">Lensa Buram</h4>
                                <p class="text-[11px] text-slate-500 dark:text-white/50 leading-relaxed transition-colors">Memahami teknik render efek kaca (Glassmorphism) menggunakan <code class="font-bold text-blue-500">backdrop-blur</code>.</p>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-[#1e1e1e] border border-slate-200 dark:border-white/5 p-5 rounded-xl flex items-start gap-4 hover:border-indigo-400 dark:hover:border-indigo-500/30 shadow-sm dark:shadow-none transition group h-full">
                            <div class="w-8 h-8 rounded bg-indigo-100 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0 font-bold text-xs transition-colors">3</div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800 dark:text-white mb-1 transition-colors">Transisi Natural</h4>
                                <p class="text-[11px] text-slate-500 dark:text-white/50 leading-relaxed transition-colors">Memahami pembuatan transisi UI yang halus dan hidup menggunakan <code class="font-bold text-indigo-500">transition-all</code>.</p>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-[#1e1e1e] border border-slate-200 dark:border-white/5 p-5 rounded-xl flex items-start gap-4 hover:border-sky-400 dark:hover:border-sky-500/30 shadow-sm dark:shadow-none transition group h-full">
                            <div class="w-8 h-8 rounded bg-sky-100 dark:bg-sky-500/10 text-sky-600 dark:text-sky-400 flex items-center justify-center shrink-0 font-bold text-xs transition-colors">4</div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800 dark:text-white mb-1 transition-colors">Koordinat Gerak</h4>
                                <p class="text-[11px] text-slate-500 dark:text-white/50 leading-relaxed transition-colors">Memahami manipulasi koordinat objek untuk animasi akselerasi GPU menggunakan <code class="font-bold text-sky-500">transform</code>.</p>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/40 dark:to-indigo-900/40 border border-blue-200 dark:border-blue-500/30 p-5 rounded-xl flex items-start gap-4 hover:shadow-md dark:hover:shadow-[0_0_20px_rgba(59,130,246,0.2)] transition group h-full col-span-1 sm:col-span-2 md:col-span-4 cursor-default">
                            <div class="w-8 h-8 rounded bg-blue-100 dark:bg-white/10 text-blue-600 dark:text-white flex items-center justify-center shrink-0 font-bold text-xs transition-colors">🏁</div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800 dark:text-white mb-1 transition-colors">Final Mission</h4>
                                <p class="text-[11px] text-slate-600 dark:text-white/70 leading-relaxed transition-colors">Memahami integrasi menyeluruh efek Glassmorphism, pendaran cahaya (Glow), dan animasi interaktif pada komponen antarmuka nyata melalui penyunting kode.</p>
                            </div>
                        </div>

                    </div>
                </div>

                <article class="space-y-40">
                    
                    {{-- LESSON 1: BOX SHADOW --}}
                    <section id="section-60" class="lesson-section scroll-mt-32" data-lesson-id="60">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-purple-500 pl-6">
                                <span class="text-purple-600 dark:text-purple-400 font-mono text-xs uppercase tracking-widest transition-colors">Pelajaran 3.4.1</span>
                                <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-slate-900 dark:text-white leading-[1.1] transition-colors">
                                    Ilusi Elevasi: Box Shadow
                                </h2>
                            </div>
                            
                            <div class="space-y-6">
                                <div class="prose prose-slate dark:prose-invert max-w-none text-slate-600 dark:text-white/70 text-base md:text-lg leading-relaxed transition-colors text-justify">
                                    <p>
                                        Layar digital berbentuk datar (2D), namun otak manusia membedakan kedudukan dimensi benda berdasarkan jatuhnya bayangan. Dalam arsitektur antarmuka modern, bayangan (<b>Box Shadow</b>) adalah instrumen utama pemisah hierarki elemen.
                                    </p>
                                    <p>
                                        Semakin menyebar dan kabur pendaran cahayanya (misal: <code class="font-bold text-purple-600 dark:text-purple-400 bg-purple-50 dark:bg-purple-900/20 px-1.5 py-0.5 rounded">shadow-xl</code>), maka elemen tersebut akan terkesan sedang "melayang" mendekati mata pengguna.
                                    </p>
                                    <p>
                                        💡 <b>Tips Elegan (Colored Shadow):</b> Pada tema gelap, bayangan hitam murni sering kali tak terlihat. Padukan bayangan dengan warna yang selaras, seperti <code class="font-bold text-purple-600 dark:text-purple-400 bg-purple-50 dark:bg-purple-900/20 px-1.5 py-0.5 rounded">shadow-blue-500/50</code>, untuk menciptakan efek pendaran cahaya (<i>Neon Glow</i>) futuristik.
                                    </p>
                                </div>
                            </div>

                            <div class="bg-white dark:bg-[#0b0f19] border border-slate-200 dark:border-white/10 rounded-2xl p-6 lg:p-8 shadow-xl dark:shadow-2xl relative group transition-all mt-8">
                                <h4 class="text-xs font-bold text-slate-400 dark:text-muted uppercase mb-4 text-center transition-colors tracking-widest">Simulator Bebas: Manipulasi Bayangan</h4>
                                
                                <div class="flex flex-col sm:flex-row gap-4 mb-8 w-full">
                                    <div class="flex-1 bg-sky-50 dark:bg-sky-500/10 border border-sky-200 dark:border-sky-500/20 p-4 rounded-xl transition-colors">
                                        <div class="text-[10px] font-black text-sky-600 dark:text-sky-400 uppercase tracking-widest mb-2 flex items-center gap-1.5">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Instruksi
                                        </div>
                                        <p class="text-xs text-slate-600 dark:text-sky-100/70 leading-relaxed font-medium">Kombinasikan menu *dropdown* elevasi dan warna bayangan. Temukan formulasi yang tepat untuk menciptakan efek melayang maupun efek cekung (inner).</p>
                                    </div>
                                    <div class="flex-1 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 p-4 rounded-xl transition-colors">
                                        <div class="text-[10px] font-black text-emerald-600 dark:text-emerald-400 uppercase tracking-widest mb-2 flex items-center gap-1.5">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Analisis Visual
                                        </div>
                                        <p id="demo-60-insight" class="text-xs text-slate-600 dark:text-emerald-100/70 leading-relaxed font-medium transition-all duration-300">Absennya bayangan (<code class='font-bold text-slate-500'>shadow-none</code>) membuat objek datar menyatu murni pada kanvas.</p>
                                    </div>
                                </div>

                                <div class="flex flex-col xl:flex-row justify-between items-start gap-6 relative z-10">
                                    <div class="flex flex-col gap-6 w-full xl:w-1/2 bg-slate-50 dark:bg-black/30 border border-slate-200 dark:border-white/10 p-5 rounded-2xl shadow-inner">
                                        
                                        <div>
                                            <label class="text-[10px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-widest mb-2 block">1. Skala Elevasi (Size & Inset)</label>
                                            <select id="sim60-size" onchange="updateSim60Flex()" class="sim-dropdown w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 dark:text-blue-100 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-700 transition-all shadow-sm">
                                                <option value="shadow-none">shadow-none (Datar)</option>
                                                <option value="shadow-sm">shadow-sm (Kecil)</option>
                                                <option value="shadow-md">shadow-md (Menengah)</option>
                                                <option value="shadow-lg">shadow-lg (Besar)</option>
                                                <option value="shadow-xl">shadow-xl (Ekstra Besar)</option>
                                                <option value="shadow-2xl">shadow-2xl (Melayang Jauh)</option>
                                                <option value="shadow-inner">shadow-inner (Tenggelam Ke Dalam)</option>
                                            </select>
                                        </div>
                                        
                                        <div>
                                            <label class="text-[10px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-widest mb-2 block">2. Warna Pendaran (Color & Alpha)</label>
                                            <select id="sim60-color" onchange="updateSim60Flex()" class="sim-dropdown w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 dark:text-blue-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-700 transition-all shadow-sm">
                                                <option value="shadow-slate-900/50">shadow-slate-900/50 (Gelap/Klasik)</option>
                                                <option value="shadow-blue-500/50">shadow-blue-500/50 (Neon Biru)</option>
                                                <option value="shadow-purple-500/50">shadow-purple-500/50 (Neon Ungu)</option>
                                                <option value="shadow-rose-500/50">shadow-rose-500/50 (Neon Merah)</option>
                                                <option value="shadow-emerald-500/50">shadow-emerald-500/50 (Neon Hijau)</option>
                                            </select>
                                        </div>

                                    </div>
                                    
                                    <div class="w-full xl:w-1/2 flex flex-col items-center justify-center bg-slate-200 dark:bg-[#151a23] rounded-2xl border border-slate-300 dark:border-white/10 relative overflow-hidden h-[360px] shadow-inner p-4">
                                        <div class="absolute inset-0 bg-checkered opacity-20 mix-blend-overlay"></div>
                                        
                                        <span class="absolute top-4 left-4 text-[10px] font-bold uppercase tracking-widest text-slate-500 z-10 bg-white/80 dark:bg-black/50 px-3 py-1.5 rounded shadow-sm transition-colors border border-white/20">
                                            class="<code id="code-60" class="text-blue-600 dark:text-blue-400">shadow-none shadow-slate-900/50</code>"
                                        </span>

                                        <div id="sim60-target" class="w-32 h-32 md:w-40 md:h-40 bg-white dark:bg-slate-800 rounded-2xl flex items-center justify-center text-slate-900 dark:text-white font-black tracking-widest transition-all duration-500 shadow-none shadow-slate-900/50 z-10 relative">
                                            OBJEK
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 2: OPACITY VS BG-ALPHA --}}
                    <section id="section-61" class="lesson-section scroll-mt-32" data-lesson-id="61">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-blue-500 pl-6">
                                <span class="text-blue-600 dark:text-blue-400 font-mono text-xs uppercase tracking-widest transition-colors">Pelajaran 3.4.2</span>
                                <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-slate-900 dark:text-white leading-[1.1] transition-colors">
                                    Transparansi: Opacity vs Latar Tembus Pandang
                                </h2>
                            </div>

                            <div class="space-y-12">
                                <div>
                                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2 transition-colors"><span class="w-6 h-6 rounded bg-blue-100 dark:bg-blue-600 text-blue-600 dark:text-white flex items-center justify-center text-[10px] transition-colors">1</span> Batasan Opacity Keseluruhan</h3>
                                    <div class="prose prose-slate dark:prose-invert max-w-none text-slate-600 dark:text-white/70 text-base md:text-lg leading-relaxed transition-colors text-justify">
                                        <p>
                                            Kesalahan umum desainer saat ingin membuat latar belakang transparan adalah menggunakan utilitas <strong>Opacity</strong> (misal: <code class="font-bold text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20 px-1.5 py-0.5 rounded">opacity-50</code>) pada kontainer utama.
                                        </p>
                                        <p>
                                            Utilitas ini akan memudarkan seluruh komponen beserta <i>children</i> di dalamnya. Teks, ikon, dan gambar akan ikut memudar sehingga melanggar standar aksesibilitas keterbacaan (WCAG).
                                        </p>
                                    </div>
                                </div>

                                <div>
                                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2 transition-colors"><span class="w-6 h-6 rounded bg-blue-100 dark:bg-blue-600 text-blue-600 dark:text-white flex items-center justify-center text-[10px] transition-colors">2</span> Transparansi Latar Belakang (Background Alpha)</h3>
                                    <div class="prose prose-slate dark:prose-invert max-w-none text-slate-600 dark:text-white/70 text-base md:text-lg leading-relaxed transition-colors text-justify">
                                        <p>
                                            <strong>Solusi Tepat:</strong> Kendalikan transparansi melalui saluran warna (*color channel*) khusus pada latar belakangnya saja.
                                        </p>
                                        <p>
                                            Gunakan sintaks bawaan Tailwind CSS seperti <code class="font-bold text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20 px-1.5 py-0.5 rounded">bg-black/50</code> atau <code class="font-bold text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20 px-1.5 py-0.5 rounded">bg-blue-600/80</code>. Angka setelah garis miring mengatur tingkat ketebalan warna latar (*background*), sedangkan elemen konten di atasnya (teks dan ikon) tetap tampil 100% tebal dan jelas.
                                        </p>
                                    </div>
                                </div>

                                <div class="bg-white dark:bg-[#0f141e] border border-slate-200 dark:border-white/10 rounded-2xl p-6 lg:p-8 shadow-xl dark:shadow-2xl relative group transition-all mt-8">
                                    <h4 class="text-xs font-bold text-slate-400 dark:text-muted uppercase mb-4 text-center transition-colors tracking-widest">Simulator Bebas: Degradasi Keterbacaan Teks</h4>
                                    
                                    <div class="flex flex-col xl:flex-row justify-between items-start gap-6 relative z-10">
                                        <div class="flex flex-col gap-6 w-full xl:w-1/2 bg-slate-50 dark:bg-black/30 border border-slate-200 dark:border-white/10 p-5 rounded-2xl shadow-inner">
                                            <div>
                                                <label class="text-[10px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-widest mb-2 block">1. Mode Transparansi CSS</label>
                                                <select id="sim61-mode" onchange="updateSim61Flex()" class="sim-dropdown w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 dark:text-blue-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-700 transition-all shadow-sm">
                                                    <option value="bg">Transparansi Latar (bg-color/alpha) - BENAR</option>
                                                    <option value="opacity">Opacity Keseluruhan (opacity-*) - SALAH</option>
                                                </select>
                                            </div>
                                            
                                            <div class="border-t border-slate-200 dark:border-white/10"></div>

                                            <div>
                                                <div class="flex justify-between items-center mb-2">
                                                    <label class="text-[10px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-widest block">2. Intensitas Ketebalan Alpha/Opacity</label>
                                                    <span id="sim61-val" class="font-mono text-xs font-bold text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-500/10 px-2 py-0.5 rounded border border-blue-200 dark:border-blue-500/20">100%</span>
                                                </div>
                                                <input type="range" id="sim61-alpha" min="10" max="100" step="10" value="100" class="mt-3" oninput="updateSim61Flex()">
                                            </div>
                                            
                                            <p id="demo-61-insight" class="text-[11px] text-slate-500 dark:text-white/60 bg-blue-50 dark:bg-blue-500/10 p-3 rounded-lg border border-blue-200 dark:border-blue-500/20 leading-relaxed font-medium mt-auto">Pengaturan berada di level penuh (100%). Kontainer menutupi gambar latar belakang secara keseluruhan.</p>
                                        </div>

                                        <div class="w-full xl:w-1/2 flex justify-center bg-[url('https://images.unsplash.com/photo-1550684848-fac1c5b4e853?q=80&w=1000&auto=format&fit=crop')] bg-cover bg-center p-8 rounded-2xl border border-slate-200 dark:border-white/5 transition-colors relative overflow-hidden h-[300px]">
                                            <span class="absolute top-4 left-4 text-[10px] font-bold uppercase tracking-widest text-slate-500 z-10 bg-white/90 dark:bg-black/70 px-3 py-1.5 rounded shadow-sm transition-colors border border-white/20">
                                                class="<code id="code-61" class="text-blue-600 dark:text-blue-400">bg-blue-600</code>"
                                            </span>

                                            <div id="sim61-target" class="w-48 h-32 bg-blue-600 rounded-2xl flex flex-col items-center justify-center text-white transition-all duration-300 border border-white/20 shadow-2xl relative z-10 mt-6">
                                                <span class="font-black text-xl tracking-tight text-center drop-shadow-md">KONTEN <br> PENTING</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 3: GLASSMORPHISM --}}
                    <section id="section-62" class="lesson-section scroll-mt-32" data-lesson-id="62">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-indigo-500 pl-6">
                                <span class="text-indigo-600 dark:text-indigo-400 font-mono text-xs uppercase tracking-widest transition-colors">Pelajaran 3.4.3</span>
                                <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-slate-900 dark:text-white leading-[1.1] transition-colors">
                                    Akselerasi GPU: Efek Kaca (Glassmorphism)
                                </h2>
                            </div>

                            <div class="space-y-12">
                                <div>
                                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2 transition-colors"><span class="w-6 h-6 rounded bg-indigo-100 dark:bg-indigo-600 text-indigo-600 dark:text-white flex items-center justify-center text-[10px] transition-colors">1</span> Pemrosesan Filter CSS</h3>
                                    <div class="prose prose-slate dark:prose-invert max-w-none text-slate-600 dark:text-white/70 text-base md:text-lg leading-relaxed transition-colors text-justify">
                                        <p>
                                            CSS Filter mengubah visual HTML langsung di peramban tanpa mengedit gambar asli. Operasi seperti <code class="font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/20 px-1.5 py-0.5 rounded">grayscale</code> atau <code class="font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/20 px-1.5 py-0.5 rounded">blur</code> diproses oleh kartu grafis (GPU), sehingga kinerjanya sangat ringan.
                                        </p>
                                    </div>
                                </div>

                                <div>
                                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2 transition-colors"><span class="w-6 h-6 rounded bg-indigo-100 dark:bg-indigo-600 text-indigo-600 dark:text-white flex items-center justify-center text-[10px] transition-colors">2</span> Rahasia Estetika Lensa Kaca</h3>
                                    <div class="prose prose-slate dark:prose-invert max-w-none text-slate-600 dark:text-white/70 text-base md:text-lg leading-relaxed transition-colors text-justify">
                                        <p>
                                            Penting untuk memahami perbedaan antara <b>Blur</b> standar dan <b>Backdrop Blur</b>:
                                            <br>• <code class="font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/20 px-1.5 py-0.5 rounded">blur-md</code>: Akan mengaburkan komponen itu sendiri secara utuh.
                                            <br>• <code class="font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/20 px-1.5 py-0.5 rounded">backdrop-blur-md</code>: Hanya mengaburkan area <strong>di belakang</strong> elemen transparan. Komponen pelapisnya tetap terlihat jernih.
                                        </p>
                                        <p>
                                            Perpaduan utuh antara warna latar transparan tipis (misal: <code class="font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/20 px-1.5 py-0.5 rounded">bg-white/20</code>) yang dikombinasikan dengan (<code class="font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/20 px-1.5 py-0.5 rounded">backdrop-blur-md</code>) melahirkan estetika kaca buram modern (<i>Glassmorphism</i>).
                                        </p>
                                    </div>
                                </div>

                                <div class="bg-white dark:bg-[#0f141e] border border-slate-200 dark:border-white/10 rounded-2xl p-6 lg:p-8 shadow-xl dark:shadow-2xl relative group transition-all mt-8">
                                    <h4 class="text-xs font-bold text-slate-400 dark:text-muted uppercase mb-4 text-center transition-colors tracking-widest">Simulator Bebas: Peracik Kaca Optik (Glassmorphism)</h4>

                                    <div class="flex flex-col xl:flex-row justify-between items-start gap-6 relative z-10">
                                        <div class="flex flex-col gap-6 w-full xl:w-1/2 bg-slate-50 dark:bg-black/30 border border-slate-200 dark:border-white/10 p-5 rounded-2xl shadow-inner">
                                            
                                            <div>
                                                <div class="flex justify-between items-center mb-2">
                                                    <label class="text-[10px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-widest block">1. Tingkat Efek Pengaburan (Backdrop Blur)</label>
                                                    <span id="sim62-blur-val" class="font-mono text-xs font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-500/10 px-2 py-0.5 rounded border border-indigo-200 dark:border-indigo-500/20">md</span>
                                                </div>
                                                <input type="range" id="sim62-blur" min="0" max="5" step="1" value="2" class="mt-3" oninput="updateSim62Flex()">
                                                <div class="flex justify-between text-[9px] text-slate-400 font-mono mt-1 px-1"><span>none</span><span>sm</span><span>md</span><span>lg</span><span>xl</span><span>2xl</span></div>
                                            </div>
                                            
                                            <div class="border-t border-slate-200 dark:border-white/10"></div>

                                            <div>
                                                <div class="flex justify-between items-center mb-2">
                                                    <label class="text-[10px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-widest block">2. Ketebalan Tint Lensa (Bg White Alpha)</label>
                                                    <span id="sim62-alpha-val" class="font-mono text-xs font-bold text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-500/10 px-2 py-0.5 rounded border border-blue-200 dark:border-blue-500/20">20%</span>
                                                </div>
                                                <input type="range" id="sim62-alpha" min="0" max="100" step="10" value="20" class="mt-3" oninput="updateSim62Flex()">
                                            </div>
                                            
                                            <p id="demo-62-insight" class="text-[11px] text-slate-500 dark:text-white/60 bg-indigo-50 dark:bg-indigo-500/10 p-3 rounded-lg border border-indigo-200 dark:border-indigo-500/20 leading-relaxed font-medium mt-auto">Perpaduan <code class='font-bold text-indigo-600 dark:text-indigo-400'>bg-white/20</code> dan <code class='font-bold text-indigo-600 dark:text-indigo-400'>backdrop-blur-md</code> menciptakan ilusi lensa kaca yang memukau.</p>
                                        </div>

                                        <div class="w-full xl:w-1/2 flex justify-center relative overflow-hidden h-[300px] rounded-2xl border border-slate-200 dark:border-white/10 shadow-lg bg-black">
                                            <img src="https://images.unsplash.com/photo-1620641788421-7a1c342ea42e?q=80&w=1000&auto=format&fit=crop" class="absolute inset-0 w-full h-full object-cover">
                                            
                                            <span class="absolute top-4 left-4 text-[10px] font-bold uppercase tracking-widest text-slate-500 z-20 bg-white/90 dark:bg-black/70 px-3 py-1.5 rounded shadow-sm transition-colors border border-white/20">
                                                class="<code id="code-62" class="text-indigo-600 dark:text-indigo-400">bg-white/20 backdrop-blur-md</code>"
                                            </span>

                                            <div id="sim62-target" class="absolute inset-10 flex flex-col items-center justify-center transition-all duration-300 rounded-2xl border border-white/20 bg-white/20 backdrop-blur-md z-10 shadow-2xl">
                                                <span class="font-black text-white text-2xl tracking-widest drop-shadow-xl" id="sim62-text">KACA BURAM</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 4: TRANSFORM & TRANSITION --}}
                    <section id="section-63" class="lesson-section scroll-mt-32" data-lesson-id="63">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-sky-500 pl-6">
                                <span class="text-sky-600 dark:text-sky-400 font-mono text-xs uppercase tracking-widest transition-colors">Pelajaran 3.4.4</span>
                                <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-slate-900 dark:text-white leading-[1.1] transition-colors">
                                    Animasi Interaktif Berbasis Transform GPU
                                </h2>
                            </div>

                            <div class="space-y-12">
                                <div>
                                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2 transition-colors"><span class="w-6 h-6 rounded bg-sky-100 dark:bg-sky-600 text-sky-600 dark:text-white flex items-center justify-center text-[10px] transition-colors">1</span> Transisi Gerakan yang Natural</h3>
                                    <div class="prose prose-slate dark:prose-invert max-w-none text-slate-600 dark:text-white/70 text-base md:text-lg leading-relaxed transition-colors text-justify">
                                        <p>
                                            Perubahan wujud komponen UI seketika tanpa transisi pergerakan akan terlihat mekanis dan terkesan merusak (*glitch*). Respons visual harus menyerupai pergerakan dunia nyata.
                                        </p>
                                        <p>
                                            Utilitas <code class="font-bold text-sky-600 dark:text-sky-400 bg-sky-50 dark:bg-sky-900/20 px-1.5 py-0.5 rounded">transition-all</code> pada Tailwind bertugas menginstruksikan peramban agar menghasilkan rangka perantara pergerakan secara otomatis dan mulus.
                                        </p>
                                    </div>
                                </div>

                                <div>
                                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2 transition-colors"><span class="w-6 h-6 rounded bg-sky-100 dark:bg-sky-600 text-sky-600 dark:text-white flex items-center justify-center text-[10px] transition-colors">2</span> Manajemen Transformasi CSS</h3>
                                    <div class="prose prose-slate dark:prose-invert max-w-none text-slate-600 dark:text-white/70 text-base md:text-lg leading-relaxed transition-colors text-justify">
                                        <p>
                                            Dilarang keras memodifikasi parameter tata letak murni (seperti `height` atau `margin-top`) untuk animasi. Hal ini memaksa peramban menghitung ulang tata letak dan menguras CPU (*Layout Thrashing*).
                                        </p>
                                        <p>
                                            Solusi paling efisien adalah menugaskan kerja ini kepada GPU menggunakan utilitas <strong>Transform</strong>. Kelas seperti <code class="font-bold text-sky-600 dark:text-sky-400 bg-sky-50 dark:bg-sky-900/20 px-1.5 py-0.5 rounded">scale-110</code> atau <code class="font-bold text-sky-600 dark:text-sky-400 bg-sky-50 dark:bg-sky-900/20 px-1.5 py-0.5 rounded">-translate-y-4</code> akan diproses stabil pada 60 FPS tanpa merusak fondasi layout elemen lainnya.
                                        </p>
                                    </div>
                                </div>

                                <div class="bg-white dark:bg-[#0f141e] border border-slate-200 dark:border-white/10 rounded-2xl p-6 lg:p-8 shadow-xl dark:shadow-2xl relative group transition-all mt-8">
                                    <h4 class="text-xs font-bold text-slate-400 dark:text-muted uppercase mb-4 text-center transition-colors tracking-widest">Simulator Bebas: Algoritma Fisika Animasi Interaktif</h4>
                                    
                                    <div class="flex flex-col xl:flex-row justify-between items-start gap-6 relative z-10">
                                        <div class="flex flex-col gap-6 w-full xl:w-1/2 bg-slate-50 dark:bg-black/30 border border-slate-200 dark:border-white/10 p-5 rounded-2xl shadow-inner">
                                            
                                            <div class="space-y-4">
                                                <div>
                                                    <label class="text-[10px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-widest mb-2 block">A. Kurva & Durasi Transisi</label>
                                                    <div class="flex gap-2">
                                                        <select id="sim63-ease" onchange="updateSim63Flex()" class="sim-dropdown w-1/2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg px-3 py-2.5 text-[11px] font-mono text-slate-700 dark:text-blue-100 focus:ring-2 focus:ring-sky-500 outline-none cursor-pointer">
                                                            <option value="ease-linear">ease-linear</option>
                                                            <option value="ease-in">ease-in</option>
                                                            <option value="ease-out" selected>ease-out (Natural)</option>
                                                            <option value="ease-in-out">ease-in-out</option>
                                                        </select>
                                                        <select id="sim63-dur" onchange="updateSim63Flex()" class="sim-dropdown w-1/2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg px-3 py-2.5 text-[11px] font-mono text-slate-700 dark:text-blue-100 focus:ring-2 focus:ring-sky-500 outline-none cursor-pointer">
                                                            <option value="duration-150">duration-150</option>
                                                            <option value="duration-300">duration-300</option>
                                                            <option value="duration-500" selected>duration-500 (1/2 dtk)</option>
                                                            <option value="duration-1000">duration-1000</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="border-t border-slate-200 dark:border-white/5 my-2"></div>

                                                <label class="text-[10px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-widest block mb-2">B. Parameter Transformasi (Saat Sorot/Hover)</label>
                                                
                                                <div>
                                                    <div class="flex justify-between items-center mb-1">
                                                        <label class="text-[10px] text-sky-600 dark:text-sky-400 uppercase font-bold">1. Scale (Perbesaran)</label>
                                                        <span id="sim63-scale-val" class="font-mono text-[10px] font-bold text-sky-600 bg-sky-50 dark:bg-sky-500/10 px-1.5 rounded">scale-110</span>
                                                    </div>
                                                    <select id="sim63-scale" onchange="updateSim63Flex()" class="sim-dropdown w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg px-3 py-2 text-[11px] font-mono text-slate-700 dark:text-blue-100 focus:ring-2 focus:ring-sky-500 outline-none cursor-pointer">
                                                        <option value="">(Normal - Tidak Diubah)</option>
                                                        <option value="hover:scale-90">hover:scale-90 (Mengecil)</option>
                                                        <option value="hover:scale-105">hover:scale-105 (Membesar Sedikit)</option>
                                                        <option value="hover:scale-110" selected>hover:scale-110 (Membesar Biasa)</option>
                                                        <option value="hover:scale-125">hover:scale-125 (Membesar Jelas)</option>
                                                        <option value="hover:scale-150">hover:scale-150 (Membesar Ekstrim)</option>
                                                    </select>
                                                </div>

                                                <div>
                                                    <div class="flex justify-between items-center mb-1 mt-2">
                                                        <label class="text-[10px] text-blue-600 dark:text-blue-400 uppercase font-bold">2. Translate Y (Geser Vertikal)</label>
                                                        <span id="sim63-trans-val" class="font-mono text-[10px] font-bold text-blue-600 bg-blue-50 dark:bg-blue-500/10 px-1.5 rounded">-translate-y-4</span>
                                                    </div>
                                                    <select id="sim63-trans" onchange="updateSim63Flex()" class="sim-dropdown w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg px-3 py-2 text-[11px] font-mono text-slate-700 dark:text-blue-100 focus:ring-2 focus:ring-blue-500 outline-none cursor-pointer">
                                                        <option value="">(Normal - Tidak Diubah)</option>
                                                        <option value="hover:translate-y-2">hover:translate-y-2 (Turun Sedikit)</option>
                                                        <option value="hover:translate-y-8">hover:translate-y-8 (Turun Jauh)</option>
                                                        <option value="hover:-translate-y-2">hover:-translate-y-2 (Naik Sedikit)</option>
                                                        <option value="hover:-translate-y-4" selected>hover:-translate-y-4 (Melayang Naik)</option>
                                                        <option value="hover:-translate-y-12">hover:-translate-y-12 (Terbang Ekstrim)</option>
                                                    </select>
                                                </div>
                                                
                                                <div>
                                                    <div class="flex justify-between items-center mb-1 mt-2">
                                                        <label class="text-[10px] text-indigo-600 dark:text-indigo-400 uppercase font-bold">3. Rotate (Putaran)</label>
                                                        <span id="sim63-rot-val" class="font-mono text-[10px] font-bold text-indigo-600 bg-indigo-50 dark:bg-indigo-500/10 px-1.5 rounded">rotate-12</span>
                                                    </div>
                                                    <select id="sim63-rot" onchange="updateSim63Flex()" class="sim-dropdown w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg px-3 py-2 text-[11px] font-mono text-slate-700 dark:text-blue-100 focus:ring-2 focus:ring-indigo-500 outline-none cursor-pointer">
                                                        <option value="">(Normal - Tidak Diubah)</option>
                                                        <option value="hover:-rotate-12">hover:-rotate-12 (Kiri Sedikit)</option>
                                                        <option value="hover:-rotate-45">hover:-rotate-45 (Kiri Miring)</option>
                                                        <option value="hover:-rotate-180">hover:-rotate-180 (Kiri Terbalik)</option>
                                                        <option value="hover:rotate-12" selected>hover:rotate-12 (Kanan Sedikit)</option>
                                                        <option value="hover:rotate-45">hover:rotate-45 (Kanan Miring)</option>
                                                        <option value="hover:rotate-180">hover:rotate-180 (Kanan Terbalik)</option>
                                                    </select>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="w-full xl:w-1/2 flex justify-center bg-slate-200 dark:bg-[#151a23] p-8 rounded-2xl border border-slate-300 dark:border-white/10 transition-colors relative overflow-hidden h-[420px] shadow-inner cursor-crosshair">
                                            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg viewBox=%220 0 200 200%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cfilter id=%22noiseFilter%22%3E%3CfeTurbulence type=%22fractalNoise%22 baseFrequency=%220.65%22 numOctaves=%223%22 stitchTiles=%22stitch%22/%3E%3C/filter%3E%3Crect width=%22100%25%22 height=%22100%25%22 filter=%22url(%23noiseFilter)%22 opacity=%220.3%22/%3E%3C/svg%3E')] opacity-10 mix-blend-overlay"></div>
                                            
                                            <div class="absolute bottom-6 left-0 right-0 flex justify-center text-[10px] text-slate-500 dark:text-white/40 font-mono font-bold uppercase tracking-widest animate-pulse">
                                                Sorot (Hover) Objek GPU Untuk Melihat Animasi
                                            </div>

                                            <span class="absolute top-4 left-4 right-4 text-[10px] font-bold uppercase tracking-widest text-slate-500 z-20 bg-white/80 dark:bg-black/50 px-3 py-2 rounded shadow-sm transition-colors text-center line-clamp-2 leading-relaxed border border-white/20">
                                                class="<code class="text-sky-600 dark:text-sky-400">transition-all</code> <code id="code-63" class="text-blue-600 dark:text-blue-400">ease-out duration-500 hover:scale-110 hover:-translate-y-4 hover:rotate-12</code>"
                                            </span>

                                            <div id="sim63-target" class="mt-8 w-24 h-24 bg-gradient-to-tr from-sky-500 to-indigo-600 rounded-2xl shadow-2xl shadow-sky-500/30 flex items-center justify-center font-black text-white text-xl tracking-widest absolute z-10 border border-white/20 transition-all ease-out duration-500 hover:scale-110 hover:-translate-y-4 hover:rotate-12">
                                                GPU
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- 5. EXPERT CHALLENGE (LIVE EDITOR) --}}
                    <section id="visual-challenge" class="lesson-section scroll-mt-32 pt-10 border-t border-slate-200 dark:border-white/10 transition-colors" data-lesson-id="64" data-type="activity">
                        <div class="relative rounded-[2rem] md:rounded-[2.5rem] bg-white dark:bg-[#050b14] border border-slate-200 dark:border-white/10 p-6 md:p-10 overflow-hidden shadow-xl dark:shadow-2xl group hover:border-blue-400 dark:hover:border-blue-500/30 transition-all duration-500 flex flex-col">
                            
                            <div class="absolute -top-24 -right-24 w-64 h-64 bg-blue-400/20 dark:bg-blue-600/20 blur-[100px] rounded-full pointer-events-none transition-colors"></div>

                            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 sm:gap-6 mb-10 relative z-10 shrink-0">
                                <div class="p-3 sm:p-4 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl text-white shadow-lg border border-blue-400/50 shrink-0">
                                    <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                                </div>
                                <div>
                                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-3 mb-2">
                                        <h2 class="text-xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight transition-colors">Final Mission : Ekosistem Kaca Futuristik</h2>
                                        <span class="px-2 py-0.5 rounded text-[9px] sm:text-[10px] font-bold bg-amber-100 dark:bg-amber-500/20 text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-500/30 uppercase tracking-wider shadow-sm transition-colors">Modul Ujian Terpandu</span>
                                    </div>
                                    <p class="text-slate-500 dark:text-slate-400 text-xs sm:text-sm leading-relaxed max-w-3xl text-justify transition-colors">
                                        Memahami integrasi efek Glassmorphism, efek bayangan bercahaya (glow), dan animasi interaktif pada komponen antarmuka. Tambahkan kelas utilitas Tailwind CSS ke dalam struktur kode HTML pada jendela Terminal Kode berdasarkan syarat yang dilampirkan.
                                    </p>
                                    
                                    <div class="mt-4 flex flex-wrap items-center gap-2">
                                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mr-1 transition-colors">Kamus Indikator Validasi:</span>
                                        <code class="text-[10px] md:text-xs font-mono font-bold text-purple-600 dark:text-purple-400 bg-purple-100 dark:bg-purple-500/10 border border-purple-200 dark:border-purple-500/20 px-2 py-1 rounded shadow-sm transition-colors">bg-white/10</code>
                                        <code class="text-[10px] md:text-xs font-mono font-bold text-purple-600 dark:text-purple-400 bg-purple-100 dark:bg-purple-500/10 border border-purple-200 dark:border-purple-500/20 px-2 py-1 rounded shadow-sm transition-colors">backdrop-blur-md</code>
                                        <code class="text-[10px] md:text-xs font-mono font-bold text-blue-600 dark:text-blue-400 bg-blue-100 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/20 px-2 py-1 rounded shadow-sm transition-colors">shadow-2xl</code>
                                        <code class="text-[10px] md:text-xs font-mono font-bold text-blue-600 dark:text-blue-400 bg-blue-100 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/20 px-2 py-1 rounded shadow-sm transition-colors">shadow-blue-500/30</code>
                                        <code class="text-[10px] md:text-xs font-mono font-bold text-sky-600 dark:text-sky-400 bg-sky-100 dark:bg-sky-500/10 border border-sky-200 dark:border-sky-500/20 px-2 py-1 rounded shadow-sm transition-colors">transition-all</code>
                                        <code class="text-[10px] md:text-xs font-mono font-bold text-sky-600 dark:text-sky-400 bg-sky-100 dark:bg-sky-500/10 border border-sky-200 dark:border-sky-500/20 px-2 py-1 rounded shadow-sm transition-colors">hover:-translate-y-2</code>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-col xl:grid xl:grid-cols-2 gap-0 border border-slate-200 dark:border-white/10 rounded-3xl overflow-hidden shadow-lg dark:shadow-2xl relative z-10 flex-1 transition-colors">
                                
                                <div class="bg-slate-50 dark:bg-[#151515] border-b xl:border-b-0 xl:border-r border-slate-200 dark:border-white/10 flex flex-col relative w-full xl:w-auto min-h-[500px] xl:min-h-[600px] transition-colors">
                                    
                                    <div id="lockOverlay" class="hidden absolute inset-0 bg-white/95 dark:bg-[#050912]/95 backdrop-blur-md z-50 flex flex-col items-center justify-center text-center p-8 transition-colors border-2 border-emerald-500/20">
                                        <div class="w-20 h-20 bg-emerald-100 dark:bg-emerald-500/10 rounded-full flex items-center justify-center mb-6 border border-emerald-300 dark:border-emerald-500/50 shadow-[0_0_50px_rgba(16,185,129,0.2)] animate-bounce transition-colors">
                                            <svg class="w-10 h-10 text-emerald-600 dark:text-emerald-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                        </div>
                                        <h3 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white mb-2 tracking-tight transition-colors">VALIDASI KODE BERHASIL</h3>
                                        <p class="text-xs sm:text-sm font-bold text-slate-500 dark:text-white/60 mb-8 max-w-xs transition-colors">Anda telah membuktikan kemampuan dalam merancang manipulasi efek visual tingkat lanjut menggunakan utilitas grafis Tailwind CSS.</p>
                                        <button disabled class="w-full sm:w-auto px-8 py-3 rounded-full bg-slate-200 dark:bg-white/5 border border-slate-300 dark:border-white/10 text-slate-400 dark:text-white/30 text-[10px] sm:text-xs font-bold cursor-not-allowed uppercase tracking-widest transition-colors">Modul Ujian Disegel</button>
                                    </div>

                                    <div class="bg-slate-100 dark:bg-[#1e1e1e] px-5 py-3.5 border-b border-slate-200 dark:border-white/5 flex justify-between items-center shrink-0 transition-colors">
                                        <span class="text-[10px] sm:text-xs text-slate-500 dark:text-white/50 font-mono font-bold transition-colors">Terminal Kode Penyunting HTML Terstruktur</span>
                                        <button onclick="resetEditor()" class="text-[10px] text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 uppercase font-bold focus:outline-none bg-red-100 dark:bg-red-500/10 px-3 py-1.5 rounded-lg shadow-sm border border-red-200 dark:border-red-500/20 active:scale-95 transition">Hapus Parameter Ulang</button>
                                    </div>
                                    
                                    <div id="codeEditor" class="flex-1 w-full border-b border-slate-200 dark:border-white/5 min-h-[250px] relative transition-colors"></div>

                                    <div class="p-6 bg-slate-50 dark:bg-[#0f141e] flex flex-col shrink-0 h-auto sm:h-[300px] transition-colors">
                                        <div class="flex justify-between items-center mb-4">
                                            <span class="text-[10px] uppercase font-bold text-slate-400 dark:text-white/30 tracking-widest transition-colors">Pedoman Evaluasi Pemeriksaan Mesin Cerdas</span>
                                            <span id="progressText" class="text-[9px] sm:text-[10px] font-mono font-bold text-blue-700 dark:text-blue-400 bg-blue-100 dark:bg-blue-900/30 px-2 py-0.5 rounded border border-blue-200 dark:border-blue-500/20 shadow-inner transition-colors">0/3 Syarat Tervalidasi Sistem</span>
                                        </div>
                                        <div class="flex flex-col gap-4 text-xs sm:text-[13px] font-sans text-slate-700 dark:text-white/70 mb-5 flex-1 overflow-y-auto custom-scrollbar p-4 bg-white dark:bg-black/20 rounded-xl shadow-sm dark:shadow-inner border border-slate-200 dark:border-white/5 transition-colors">
                                            
                                            <div id="check-glass" class="flex items-start gap-3">
                                                <span class="w-4 h-4 mt-0.5 rounded-full border-2 border-slate-300 dark:border-white/20 flex items-center justify-center text-[10px] shrink-0 transition-colors font-bold text-white"></span> 
                                                <div class="leading-relaxed">
                                                    <b class="block mb-1 text-slate-800 dark:text-white/90 transition-colors font-extrabold text-sm">1. Efek Kaca (Glassmorphism):</b> 
                                                    Pada blok <code class="text-[11px] bg-slate-100 dark:bg-white/10 px-1 rounded text-rose-500 font-mono">id="glass-card"</code>, buang latar <code class="text-[10px] text-rose-500 line-through decoration-rose-500">bg-white</code> lalu gantikan dengan transparansi <code class="text-[10px] font-bold text-indigo-600 dark:text-indigo-400">bg-white/10</code>, dan filter lensa <code class="text-[10px] font-bold text-indigo-600 dark:text-indigo-400">backdrop-blur-md</code>.
                                                </div>
                                            </div>
                                            
                                            <div id="check-glow" class="flex items-start gap-3">
                                                <span class="w-4 h-4 mt-0.5 rounded-full border-2 border-slate-300 dark:border-white/20 flex items-center justify-center text-[10px] shrink-0 transition-colors font-bold text-white"></span> 
                                                <div class="leading-relaxed">
                                                    <b class="block mb-1 text-slate-800 dark:text-white/90 transition-colors font-extrabold text-sm">2. Bayangan Bercahaya (Glow):</b> 
                                                    Hapus <code class="text-[10px] text-rose-500 line-through decoration-rose-500">shadow-none</code>, lalu perluas dimensi jatuhnya bayangan (<code class="text-[10px] font-bold text-blue-600 dark:text-blue-400">shadow-2xl</code>) dan pasangkan pendaran neon biru (<code class="text-[10px] font-bold text-blue-600 dark:text-blue-400">shadow-blue-500/30</code>).
                                                </div>
                                            </div>
                                            
                                            <div id="check-anim" class="flex items-start gap-3">
                                                <span class="w-4 h-4 mt-0.5 rounded-full border-2 border-slate-300 dark:border-white/20 flex items-center justify-center text-[10px] shrink-0 transition-colors font-bold text-white"></span> 
                                                <div class="leading-relaxed">
                                                    <b class="block mb-1 text-slate-800 dark:text-white/90 transition-colors font-extrabold text-sm">3. Transisi Hover Melayang:</b> 
                                                    Tambahkan kelas transisi gerakan <code class="text-[10px] font-bold text-sky-600 dark:text-sky-400">transition-all</code>, dan modifikasi dorongan vertikal saat elemen disorot: <code class="text-[10px] font-bold text-sky-600 dark:text-sky-400">hover:-translate-y-2</code>.
                                                </div>
                                            </div>
                                            
                                        </div>
                                        <button id="submitExerciseBtn" onclick="submitExercise()" disabled class="w-full py-3 sm:py-3.5 rounded-xl bg-emerald-600 text-white font-bold text-[11px] sm:text-xs shadow-lg shadow-emerald-500/20 hover:bg-emerald-500 hover:-translate-y-0.5 transition-all cursor-not-allowed opacity-50 flex items-center justify-center gap-2 focus:outline-none active:scale-95 tracking-wide">
                                            <span>Penuhkan Kriteria</span>
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                        </button>
                                    </div>
                                </div>

                                <div class="bg-slate-100 dark:bg-[#1e1e1e] flex flex-col relative overflow-hidden w-full xl:w-auto h-[400px] xl:h-auto transition-colors">
                                    <div class="bg-slate-200 dark:bg-[#2d2d2d] px-5 py-3.5 border-b border-slate-300 dark:border-white/5 flex items-center justify-between shrink-0 transition-colors">
                                        <span class="text-[10px] sm:text-xs text-slate-500 dark:text-white/50 font-mono font-bold transition-colors flex items-center gap-2">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg> Jendela Peramban
                                        </span>
                                        <span class="text-[9px] sm:text-[10px] bg-emerald-100 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 px-2 py-0.5 rounded border border-emerald-200 dark:border-emerald-500/20 font-bold uppercase tracking-widest flex items-center gap-1.5 shadow-sm transition-colors">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse shadow-[0_0_5px_#10b981]"></span> Sinkronisasi Langsung
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
                    <a href="{{ route('courses.borders') ?? '#' }}" class="group flex items-center gap-4 text-slate-600 dark:text-slate-400 hover:text-blue-600 dark:hover:text-white transition w-full sm:w-auto justify-center sm:justify-start">
                        <div class="w-12 h-12 rounded-full border border-slate-200 dark:border-white/10 flex items-center justify-center bg-slate-100 dark:bg-transparent group-hover:bg-blue-50 dark:group-hover:bg-white/5 transition shrink-0">
                            <span class="text-lg group-hover:-translate-x-1 transition-transform">←</span>
                        </div>
                        <div class="text-center sm:text-left">
                            <div class="text-[10px] sm:text-xs uppercase tracking-widest font-bold opacity-50 mb-0.5">Sebelumnya</div>
                            <div class="font-bold text-xs md:text-sm line-clamp-1">Borders and Effects</div>
                        </div>
                    </a>
                    
                    {{-- TOMBOL NEXT TERKUNCI --}}
                    <div id="nextChapterBtn" class="group flex items-center gap-4 text-right text-slate-500 cursor-not-allowed opacity-50 pointer-events-none transition-all duration-500 w-full sm:w-auto justify-center sm:justify-end flex-row-reverse sm:flex-row">
                        <div class="text-center sm:text-right">
                            <div id="nextLabel" class="text-[10px] sm:text-xs uppercase tracking-widest font-bold opacity-50 mb-0.5 text-rose-500 dark:text-rose-400 transition-colors">Berikutnya</div>
                            <div class="font-bold text-xs md:text-sm line-clamp-1">Hands On Lab 3</div>
                        </div>
                        <div id="nextIcon" class="w-12 h-12 rounded-full border border-slate-200 dark:border-white/5 flex items-center justify-center bg-slate-100 dark:bg-white/5 shrink-0 transition-colors">
                            🔒
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/monaco-editor@0.45.0/min/vs/loader.js"></script>

<script>
    window.LESSON_IDS = [60, 61, 62, 63, 64, 65]; 
    const ACTIVITY_LESSON_ID = 65; 

    let rawCompletedIds = @json($completedLessonIds ?? []);
    let isActCompleted = @json($activityCompleted ?? false);
    
    window.COMPLETED_IDS = Array.isArray(rawCompletedIds) ? rawCompletedIds.map(Number) : []; 

    let completedSet = new Set(window.COMPLETED_IDS);
    if (isActCompleted) completedSet.add(ACTIVITY_LESSON_ID); 

    let pendingRequests = new Set(); 

    document.addEventListener('DOMContentLoaded', () => {
        initSidebarScroll();
        initVisualEffects();
        initMonaco();
        
        updateProgressUI(false); 
        
        // Inisiasi Nilai Default Simulator DOM
        updateSim60Flex();
        updateSim61Flex();
        updateSim62Flex();
        updateSim63Flex();

        if (completedSet.has(ACTIVITY_LESSON_ID)) {
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

    function updateProgressUI(animate = true) {
        const total = window.LESSON_IDS.length; 
        const done = window.LESSON_IDS.filter(id => completedSet.has(Number(id))).length; 
        
        let percent = Math.round((done / total) * 100);
        const isActivityDone = completedSet.has(ACTIVITY_LESSON_ID);
        
        if (!isActivityDone && percent >= 100) {
             percent = Math.floor(((total - 1) / total) * 100); 
        }
        
        const bar = document.getElementById('topProgressBar');
        const label = document.getElementById('progressLabelTop');
        
        if(!animate) bar.style.transition = 'none';
        bar.style.width = percent + '%'; 
        if(!animate) setTimeout(() => bar.style.transition = 'all 0.5s ease-out', 50);
        
        label.innerText = percent + '%';
        
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
                    dot.outerHTML = `<svg class="w-4 h-4 text-blue-600 dark:text-blue-400 shrink-0 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>`;
                } else {
                    dot.outerHTML = `<svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400 shrink-0 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>`;
                }
            }
        }
    }

    async function saveLessonToDB(lessonId) { 
        lessonId = Number(lessonId);
        
        if (completedSet.has(lessonId) || pendingRequests.has(lessonId)) return; 

        completedSet.add(lessonId);
        pendingRequests.add(lessonId);
        
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
    let editorDebounceTimer; 
    const starterCode = `<div class="min-h-screen flex items-center justify-center font-sans p-8 text-white relative w-full overflow-hidden bg-slate-900 bg-[url('https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?q=80&w=2000&auto=format&fit=crop')] bg-cover bg-center">
    
  <div id="glass-card" class="w-full max-w-sm bg-white rounded-3xl overflow-hidden border border-white/20 p-8 text-center cursor-pointer shadow-none">
      
    <div class="flex justify-center mb-6">
      <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-white/30 shadow-inner">
        <img src="https://images.unsplash.com/photo-1634926878768-2a5b3c42f139?q=80&w=300&auto=format&fit=crop" class="w-full h-full object-cover">
      </div>
    </div>

    <h3 class="font-black text-3xl mb-1 tracking-tight">Seni Fraktal</h3>
    <p class="text-blue-300 text-xs font-bold uppercase tracking-widest mb-6">Koleksi Digital</p>

    <div class="bg-black/30 rounded-2xl p-4 flex justify-between items-center mb-6 border border-white/10">
        <div class="text-left">
            <p class="text-[10px] uppercase tracking-widest opacity-60">Tertinggi Saat Ini</p>
            <p class="font-bold text-lg text-emerald-400">3.40 Koin</p>
        </div>
        <div class="text-right">
            <p class="text-[10px] uppercase tracking-widest opacity-60">Batas Waktu</p>
            <p class="font-bold text-lg">04j 20m</p>
        </div>
    </div>

    <button class="w-full py-4 bg-white text-blue-900 text-sm font-bold tracking-widest rounded-xl hover:bg-blue-100 transition-colors cursor-pointer">
      REBUT TRANSAKSI
    </button>

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
            
            if (completedSet.has(ACTIVITY_LESSON_ID)) {
                lockActivityUI();
            }
            
            editor.onDidChangeModelContent(() => {
                if(completedSet.has(ACTIVITY_LESSON_ID)) return;
                
                clearTimeout(editorDebounceTimer);
                editorDebounceTimer = setTimeout(() => {
                    const code = editor.getValue();
                    updatePreview(code);
                    validateCodeDOM(code);
                }, 500); 
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
                .custom-scrollbar::-webkit-scrollbar { width: 6px; }
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
        const cardEl = doc.getElementById('glass-card');

        let isGlassValid = false;
        let isGlowValid = false;
        let isAnimValid = false;

        if (cardEl) {
            const classes = Array.from(cardEl.classList);
            isGlassValid = !classes.includes('bg-white') && classes.includes('bg-white/10') && classes.includes('backdrop-blur-md');
            isGlowValid = classes.includes('shadow-2xl') && classes.includes('shadow-blue-500/30') && !classes.includes('shadow-none');
            isAnimValid = classes.includes('transition-all') && (classes.includes('hover:-translate-y-2') || classes.includes('hover:scale-105'));
        }

        const checks = [
            { id: 'check-glass', valid: isGlassValid },
            { id: 'check-glow', valid: isGlowValid },
            { id: 'check-anim', valid: isAnimValid }
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

        document.getElementById('progressText').innerText = passed + '/3 Status Evaluasi Tervalidasi';
        
        const btn = document.getElementById('submitExerciseBtn');
        if (passed === 3) {
            btn.disabled = false;
            btn.classList.remove('cursor-not-allowed', 'opacity-50');
            btn.innerHTML = '<span>Simpan Proyek Kompilasi Kode ke Pangkalan Data</span><svg class="w-4 h-4 sm:w-5 sm:h-5 shrink-0 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
            
            document.getElementById('progressText').classList.replace('text-blue-700', 'text-white');
            document.getElementById('progressText').classList.replace('dark:text-blue-400', 'text-white');
            document.getElementById('progressText').classList.replace('bg-blue-100', 'bg-emerald-500');
            document.getElementById('progressText').classList.replace('dark:bg-blue-900/30', 'bg-emerald-500');
            document.getElementById('progressText').classList.replace('border-blue-200', 'border-emerald-400');
            document.getElementById('progressText').classList.replace('dark:border-blue-500/20', 'border-emerald-400');

        } else {
            btn.disabled = true;
            btn.classList.add('cursor-not-allowed', 'opacity-50');
            btn.innerHTML = '<span>Penuhkan kriteria</span><svg class="w-4 h-4 sm:w-5 sm:h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>';
            
            document.getElementById('progressText').classList.replace('text-white', 'text-blue-700');
            document.getElementById('progressText').classList.replace('text-white', 'dark:text-blue-400');
            document.getElementById('progressText').classList.replace('bg-emerald-500', 'bg-blue-100');
            document.getElementById('progressText').classList.replace('bg-emerald-500', 'dark:bg-blue-900/30');
            document.getElementById('progressText').classList.replace('border-emerald-400', 'border-blue-200');
            document.getElementById('progressText').classList.replace('border-emerald-400', 'dark:border-blue-500/20');
        }
    }

    function resetEditor() { 
        if(editor && !completedSet.has(ACTIVITY_LESSON_ID)) { 
            editor.setValue(starterCode); 
            validateCodeDOM(starterCode); 
        } 
    }

    async function submitExercise() {
        const btn = document.getElementById('submitExerciseBtn');
        btn.innerHTML = '<span class="animate-pulse">Menghubungi Pusat Validasi Sistem Server...</span>'; 
        btn.disabled = true;
        
        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            
            await fetch('/activity/complete', { 
                method: 'POST', 
                headers: { 
                    'X-CSRF-TOKEN': csrfToken || '{{ csrf_token() }}', 
                    'Accept': 'application/json', 
                    'Content-Type': 'application/json' 
                }, 
                body: JSON.stringify({ activity_id: ACTIVITY_LESSON_ID, score: 100 }) 
            });

            completedSet.add(ACTIVITY_LESSON_ID);
            updateProgressUI(true);
            markSidebarDone(ACTIVITY_LESSON_ID);
            lockActivityUI();   

            await saveLessonToDB(ACTIVITY_LESSON_ID);

        } catch(e) { 
            console.error("Gagal Submit Aktivitas:", e); 
            btn.innerHTML = "Permintaan Ditolak Oleh Server Karena Masalah Jaringan.";
            btn.disabled = false;
            btn.classList.add('shake'); 
            setTimeout(() => btn.classList.remove('shake'), 500);
        }
    }

    function lockActivityUI() {
        document.getElementById('lockOverlay').classList.remove('hidden');
        if(editor) editor.updateOptions({ readOnly: true });
        
        const btn = document.getElementById('submitExerciseBtn'); 
        btn.innerText = "Selamat Anda Telah Menuntaskan Latihan Evaluasi Terakhir."; 
        btn.disabled = true;
        btn.classList.remove('bg-emerald-600', 'hover:bg-emerald-500');
        btn.classList.add('bg-slate-200', 'dark:bg-slate-700', 'text-slate-500', 'dark:text-slate-400', 'cursor-not-allowed', 'shadow-none');
        
        if(editor && completedSet.has(ACTIVITY_LESSON_ID)) {
            editor.setValue(`<div class="min-h-screen flex items-center justify-center font-sans p-8 text-white relative w-full overflow-hidden bg-slate-900 bg-[url('https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?q=80&w=2000&auto=format&fit=crop')] bg-cover bg-center">\n    \n  \n  <div id="glass-card" class="w-full max-w-sm bg-white/10 backdrop-blur-md rounded-3xl overflow-hidden border border-white/20 p-8 text-center cursor-pointer shadow-2xl shadow-blue-500/30 transition-all hover:-translate-y-2">\n      \n    \n    <div class="flex justify-center mb-6">\n      <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-white/30 shadow-inner">\n        <img src="https://images.unsplash.com/photo-1634926878768-2a5b3c42f139?q=80&w=300&auto=format&fit=crop" class="w-full h-full object-cover">\n      </div>\n    </div>\n\n    <h3 class="font-black text-3xl mb-1 tracking-tight">Seni Fraktal</h3>\n    <p class="text-blue-300 text-xs font-bold uppercase tracking-widest mb-6">Koleksi Digital</p>\n\n    <div class="bg-black/30 rounded-2xl p-4 flex justify-between items-center mb-6 border border-white/10">\n        <div class="text-left">\n            <p class="text-[10px] uppercase tracking-widest opacity-60">Tertinggi Saat Ini</p>\n            <p class="font-bold text-lg text-emerald-400">3.40 Koin</p>\n        </div>\n        <div class="text-right">\n            <p class="text-[10px] uppercase tracking-widest opacity-60">Batas Waktu</p>\n            <p class="font-bold text-lg">04j 20m</p>\n        </div>\n    </div>\n\n    <button class="w-full py-4 bg-white text-blue-900 text-sm font-bold tracking-widest rounded-xl hover:bg-blue-100 transition-colors cursor-pointer">\n      REBUT TRANSAKSI\n    </button>\n\n  </div>\n</div>`);
            validateCodeDOM(editor.getValue());
        }
    }

    function unlockNextChapter() {
        const btn = document.getElementById('nextChapterBtn');
        if(btn) {
            btn.classList.remove('cursor-not-allowed', 'opacity-50', 'pointer-events-none', 'text-slate-500');
            btn.classList.add('text-blue-600', 'dark:text-blue-400', 'hover:text-blue-700', 'dark:hover:text-white', 'cursor-pointer');
            
            document.getElementById('nextLabel').innerText = "Rute Jalur Materi Tahap Selanjutnya Tersedia";
            document.getElementById('nextLabel').classList.remove('opacity-50', 'text-rose-500', 'dark:text-rose-400');
            document.getElementById('nextLabel').classList.add('text-blue-600', 'dark:text-blue-400', 'opacity-100');
            
            const icon = document.getElementById('nextIcon');
            icon.innerHTML = `<svg class="w-5 h-5 md:w-6 md:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>`;
            icon.classList.remove('bg-slate-100', 'dark:bg-white/5');
            icon.classList.add('bg-blue-100', 'dark:bg-blue-500/10', 'border-blue-300', 'dark:border-blue-500/30', 'text-blue-600', 'dark:text-blue-400', 'shadow-md', 'dark:shadow-lg', 'group-hover:scale-105', 'transition-transform');
            
            btn.onclick = () => window.location.href = "{{ route('lab.start', ['id' => 3]) }}"; 
        }
    }

    // --- LOGIKA SIMULATOR FLEKSIBEL TERBARU ---

    // 1. Simulator Shadow (Sim 60)
    window.updateSim60Flex = function() {
        const sizeEl = document.getElementById('sim60-size');
        const colorEl = document.getElementById('sim60-color');
        const target = document.getElementById('sim60-target');
        const codeEl = document.getElementById('code-60');
        const insightEl = document.getElementById('demo-60-insight');

        const sizeClass = sizeEl.value;
        const colorClass = colorEl.value;

        target.className = "w-32 h-32 md:w-40 md:h-40 bg-white dark:bg-slate-800 rounded-2xl flex items-center justify-center text-slate-900 dark:text-white font-black tracking-widest transition-all duration-500 z-10 relative";

        let finalClasses = "";
        let insightMsg = "";

        if (sizeClass === 'shadow-inner') {
            finalClasses = "shadow-inner"; 
            insightMsg = "Bayangan dengan atribut <code class='font-bold text-slate-800 dark:text-white'>shadow-inner</code> memberikan ilusi seolah kotak objek masuk menjorok berdimensi cekung membelah dasar dinding.";
        } else if (sizeClass === 'shadow-none') {
            finalClasses = "shadow-none";
            insightMsg = "Absennya elemen bayangan cahaya (<code class='font-bold text-slate-800 dark:text-white'>shadow-none</code>) membuat susunan objek secara visual tidak menonjol, mengisyaratkan kesetaraan dimensi murni pada kanvas datar (2D).";
        } else {
            finalClasses = `${sizeClass} ${colorClass}`;
            if (sizeClass === 'shadow-2xl' && !colorClass.includes('slate')) {
                insightMsg = "Kombinasi <code class='font-bold text-slate-800 dark:text-white'>shadow-2xl</code> dan rentang pendaran warna neon secara teknis berhasil menciptakan pemancaran cahaya (Glow Effect) memukau bergaya modern.";
            } else {
                insightMsg = "Bayangan menciptakan separasi dimensi logis. Memanfaatkan efek ini memisahkan hierarki komponen secara natural tanpa membebani daya tangkap optik pengguna.";
            }
        }

        target.classList.add(...finalClasses.split(' '));
        codeEl.innerText = finalClasses;
        insightEl.innerHTML = insightMsg;
    }

    // 2. Simulator Opacity vs BG-Alpha (Sim 61)
    window.updateSim61Flex = function() {
        const modeEl = document.getElementById('sim61-mode');
        const alphaEl = document.getElementById('sim61-alpha');
        const target = document.getElementById('sim61-target');
        const codeEl = document.getElementById('code-61');
        const insightEl = document.getElementById('demo-61-insight');
        const valText = document.getElementById('sim61-val');

        const mode = modeEl.value;
        const alpha = parseInt(alphaEl.value);

        valText.innerText = alpha + '%';

        target.className = "w-48 h-32 rounded-2xl flex flex-col items-center justify-center text-white transition-all duration-300 border border-white/20 shadow-2xl relative z-10 mt-6";
        target.style.opacity = "";
        target.style.backgroundColor = "";

        if (mode === 'opacity') {
            let opClass = alpha === 100 ? 'opacity-100' : (alpha < 15 ? 'opacity-10' : `opacity-${Math.round(alpha/10)*10}`);
            target.classList.add('bg-blue-600', opClass);
            codeEl.innerText = `bg-blue-600 ${opClass}`;

            if(alpha < 100) {
                insightEl.innerHTML = "Penggunaan atribut pudar (<code class='font-bold text-slate-800 dark:text-white'>opacity-*</code>) langsung memudarkan struktur objek anak turunannya tanpa terkecuali, memaksa teks instruksi vital terbaca sangat samar. DILARANG untuk aksesibilitas.";
            } else {
                insightEl.innerHTML = "Pengaturan opasitas utuh seratus persen (100%). Seluruh blok pembungkus sukses beroperasi mencegah latar memengaruhi teks.";
            }
        } else {
            let step = Math.round(alpha/10)*10;
            let bgClass = alpha === 100 ? 'bg-blue-600' : `bg-blue-600/${step}`;
            
            target.classList.add('bg-blue-600');
            target.style.backgroundColor = `rgba(37, 99, 235, ${alpha/100})`; 
            
            codeEl.innerText = bgClass;

            if(alpha < 100) {
                insightEl.innerHTML = "Filter transparansi Alpha Channel (<code class='font-bold text-slate-800 dark:text-white'>bg-color/alpha</code>) terbukti sukses menjaga susunan integritas informasi abjad penting di atas kanvas agar tetap tebal dan tersampaikan utuh.";
            } else {
                insightEl.innerHTML = "Blok bangunan ini terhalangi corak pekat warna solid tanpa menyisakan ruang celah pandang ke arah latar panorama.";
            }
        }
    }

    // 3. Simulator Glassmorphism (Sim 62)
    window.updateSim62Flex = function() {
        const blurEl = document.getElementById('sim62-blur');
        const alphaEl = document.getElementById('sim62-alpha');
        const target = document.getElementById('sim62-target');
        const codeEl = document.getElementById('code-62');
        const insightEl = document.getElementById('demo-62-insight');
        
        const blurLevels = ['none', 'sm', 'md', 'lg', 'xl', '2xl'];
        const blurValue = blurLevels[parseInt(blurEl.value)];
        const alphaValue = parseInt(alphaEl.value);

        document.getElementById('sim62-blur-val').innerText = blurValue;
        document.getElementById('sim62-alpha-val').innerText = alphaValue + '%';

        target.className = "absolute inset-10 flex flex-col items-center justify-center transition-all duration-300 rounded-2xl border border-white/20 z-10 shadow-2xl";

        let finalClasses = "";
        
        if (blurValue !== 'none') {
            finalClasses += `backdrop-blur-${blurValue} `;
            target.classList.add(`backdrop-blur-${blurValue}`);
        } else {
            finalClasses += `backdrop-blur-none `;
        }

        if (alphaValue > 0) {
            let step = Math.round(alphaValue / 10) * 10;
            if(step === 50) step = 50; 
            const bgClass = alphaValue === 100 ? 'bg-white' : `bg-white/${step}`;
            finalClasses += bgClass;
            target.style.backgroundColor = `rgba(255, 255, 255, ${alphaValue / 100})`;
        } else {
            target.style.backgroundColor = 'transparent';
            finalClasses += "bg-transparent";
        }

        codeEl.innerText = finalClasses.trim();

        if (blurValue === 'none' && alphaValue === 0) {
            insightEl.innerHTML = "Lensa tembus pandang murni secara utuh tanpa dilapisi filter pengaburan kartu grafis sama sekali.";
        } else if (blurValue !== 'none' && alphaValue > 0 && alphaValue < 80) {
            insightEl.innerHTML = `Kombinasi menawan latar putih ber-alpha (<code class='font-bold text-slate-800 dark:text-white'>bg-white/${Math.round(alphaValue/10)*10}</code>) dan filter peredam <code class='font-bold text-slate-800 dark:text-white'>backdrop-blur-${blurValue}</code> berhasil menyajikan ilusi optik kaca Glassmorphism modern.`;
        } else if (alphaValue >= 80) {
            insightEl.innerHTML = "Ketebalan lapisan warna (Tint) terlalu tinggi sehingga menutupi pantulan bayangan latar dan menggagalkan kesan tembus pandang alami.";
        } else {
            insightEl.innerHTML = "Filter pengaburan memang berjalan, namun lapisan elemen ini kehilangan ketebalan massa logis karena ketiadaan zat warna pelapis pada permukaannya.";
        }
    }

    // 4. Simulator Transform GPU (Sim 63)
    window.updateSim63Flex = function() {
        const easeEl = document.getElementById('sim63-ease');
        const durEl = document.getElementById('sim63-dur');
        const scaleEl = document.getElementById('sim63-scale');
        const transEl = document.getElementById('sim63-trans');
        const rotEl = document.getElementById('sim63-rot');
        const codeEl = document.getElementById('code-63');
        const target = document.getElementById('sim63-target');

        document.getElementById('sim63-scale-val').innerText = scaleEl.value.replace('hover:', '') || '(Normal)';
        document.getElementById('sim63-trans-val').innerText = transEl.value.replace('hover:', '') || '(Normal)';
        document.getElementById('sim63-rot-val').innerText = rotEl.value.replace('hover:', '') || '(Normal)';

        const baseClasses = "mt-8 w-24 h-24 bg-gradient-to-tr from-sky-500 to-indigo-600 rounded-2xl shadow-2xl shadow-sky-500/30 flex items-center justify-center font-black text-white text-xl tracking-widest absolute z-10 border border-white/20 transition-all";
        target.className = baseClasses;

        let actionClasses = [easeEl.value, durEl.value, scaleEl.value, transEl.value, rotEl.value]
                            .filter(Boolean)
                            .join(' ')
                            .trim();

        if(actionClasses) {
            const validClasses = actionClasses.split(' ').filter(c => c.trim() !== '');
            target.classList.add(...validClasses);
        }

        codeEl.innerText = actionClasses || '(Tanpa Efek Tambahan)';
    }

    function highlightAnchor(id) {
        const anchors = document.querySelectorAll('.sidebar-anchor');

        anchors.forEach(a => {
            a.classList.remove('bg-slate-100', 'dark:bg-white/5', 'border-blue-500', 'border-amber-500');
            a.classList.add('border-transparent');
            
            const dot = a.querySelector('.anchor-dot');
            const isActivity = a.dataset.type === 'activity';

            if(dot) dot.classList.remove('scale-125', 'shadow-sm', 'dark:shadow-[0_0_10px_#3b82f6]', 'dark:shadow-[0_0_10px_#f59e0b]', 'bg-blue-500', 'dark:bg-blue-400');
            
            if (isActivity) {
                if(dot) { dot.classList.remove('bg-amber-400'); dot.classList.add('bg-slate-300', 'dark:bg-slate-600'); }
            } else {
                if(dot) { dot.classList.remove('bg-blue-500', 'dark:bg-blue-400'); dot.classList.add('bg-slate-300', 'dark:bg-slate-600'); }
            }

            const text = a.querySelector('.anchor-text');
            if(text) { text.classList.remove('text-slate-900', 'dark:text-white', 'font-bold'); text.classList.add('text-slate-500', 'dark:text-slate-500'); }
        });

        const activeAnchor = document.querySelector(`.sidebar-anchor[data-target="${id}"]`);
        if (activeAnchor) {
            const isActivity = activeAnchor.dataset.type === 'activity';
            
            activeAnchor.classList.add('bg-slate-100', 'dark:bg-white/5');
            activeAnchor.classList.add(isActivity ? 'border-amber-500' : 'border-blue-500');
            activeAnchor.classList.remove('border-transparent');
            
            const dot = activeAnchor.querySelector('.anchor-dot');
            if(dot) {
                dot.classList.remove('bg-slate-300', 'dark:bg-slate-600');
                if (isActivity) {
                    dot.classList.add('bg-amber-500', 'dark:bg-amber-400', 'scale-125', 'shadow-sm', 'dark:shadow-[0_0_10px_#f59e0b]');
                } else {
                    dot.classList.add('bg-blue-600', 'dark:bg-blue-400', 'scale-125', 'shadow-sm', 'dark:shadow-[0_0_10px_#3b82f6]');
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