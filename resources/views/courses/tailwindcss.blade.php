@extends('layouts.landing')
@section('title','Konsep Dasar Tailwind CSS')

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
        --accent: #22d3ee;
        --accent-glow: rgba(34, 211, 238, 0.3);
    }

    .dark {
        /* ORIGINAL DARK THEME VALUES */
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
        --accent-glow: rgba(34, 211, 238, 0.5);
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
    
    /* ANIMATIONS & EFFECTS (KHAS BAB 1.2: WARNA CYAN) */
    #animated-bg { 
        background: radial-gradient(800px circle at 20% 20%, rgba(34,211,238,.10), transparent 40%), 
                    radial-gradient(800px circle at 80% 80%, rgba(14,165,233,.10), transparent 40%); 
        animation: bgMove 20s ease-in-out infinite alternate; 
    }
    .dark #animated-bg {
        background: radial-gradient(800px circle at 20% 20%, rgba(34,211,238,.15), transparent 40%), 
                    radial-gradient(800px circle at 80% 80%, rgba(14,165,233,.15), transparent 40%); 
    }
    @keyframes bgMove{to{transform:scale(1.15)}}
    @keyframes shake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-5px); } 75% { transform: translateX(5px); } }
    .shake { animation: shake 0.4s ease-in-out; }
    
    /* SCROLLSPY SIDEBAR ACTIVE */
    .nav-item { display: flex; width: 100%; text-align: left; align-items: center; gap: 12px; padding: 10px 14px; font-size: 0.85rem; color: var(--text-muted); border-radius: 8px; transition: all 0.2s; position: relative; }
    .nav-item:hover { color: var(--text-main); background: var(--card-hover); }
    .nav-item.active { color: #22d3ee; background: rgba(34,211,238,0.05); font-weight: 600; }
    .dot { width: 6px; height: 6px; border-radius: 50%; background: #94a3b8; transition: all 0.3s; }
    .dark .dot { background: #334155; }
    .nav-item.active .dot { background: #22d3ee; box-shadow: 0 0 8px #22d3ee; transform: scale(1.2); }
</style>

<div id="courseRoot" class="relative h-screen bg-adaptive text-adaptive font-sans overflow-hidden flex flex-col selection:bg-cyan-500/30 pt-20 transition-colors duration-500">

    {{-- BACKGROUND EFFECTS --}}
    <div class="fixed inset-0 -z-50 pointer-events-none">
        <div id="animated-bg" class="absolute inset-0 opacity-50 transition-opacity"></div>
        <div class="absolute top-[-20%] left-[-10%] w-[1000px] h-[1000px] bg-cyan-500/5 dark:bg-cyan-900/10 rounded-full blur-[120px] animate-pulse transition-colors"></div>
        <div class="absolute bottom-[-20%] right-[-10%] w-[800px] h-[800px] bg-sky-500/5 dark:bg-sky-900/10 rounded-full blur-[100px] transition-colors"></div>
        <canvas id="stars" class="absolute inset-0 pointer-events-none opacity-0 dark:opacity-100 transition-opacity duration-500"></canvas>
        <div id="noise-overlay" class="absolute inset-0 z-10 opacity-[0.02] dark:opacity-[0.03]"></div>
        <div id="cursor-glow"></div>
    </div>

    @include('layouts.partials.navbar')

    <div class="flex flex-1 overflow-hidden relative z-20">

        @include('layouts.partials.course-sidebar')

        <main id="mainScroll" class="flex-1 h-full overflow-y-auto scroll-smooth relative bg-transparent custom-scrollbar scroll-padding-top-24">
            
            {{-- STICKY HEADER & PROGRESS BAR --}}
            <div id="stickyHeader" class="sticky top-0 z-30 w-full backdrop-blur-2xl border-b border-adaptive px-8 py-4 flex items-center justify-between transition-colors duration-500" style="background-color: var(--glass-header);">
                <div class="flex items-center gap-4">
                    <div class="w-8 h-8 rounded-lg bg-cyan-500/10 dark:bg-cyan-500/20 border border-cyan-500/20 flex items-center justify-center font-bold text-xs text-cyan-600 dark:text-cyan-400 transition-colors">1.2</div>
                    <div>
                        <h1 class="text-sm font-bold text-heading transition-colors">Konsep Dasar Tailwind</h1>
                        <p class="text-[10px] text-muted transition-colors">Core Utilities & Workflow</p>
                    </div>
                </div>
                
                {{-- DYNAMIC PROGRESS BAR UI --}}
                <div class="flex items-center gap-3">
                    <div class="hidden sm:block w-32 h-1.5 bg-slate-200 dark:bg-white/10 rounded-full overflow-hidden transition-colors">
                        <div id="topProgressBar" class="h-full bg-gradient-to-r from-cyan-400 to-blue-500 w-0 transition-all duration-500 shadow-[0_0_10px_rgba(34,211,238,0.5)]"></div>
                    </div>
                    <span id="progressLabelTop" class="text-cyan-600 dark:text-cyan-400 font-bold text-xs transition-colors">0%</span>
                </div>
            </div>

            <div class="p-6 lg:p-16 max-w-5xl mx-auto pb-40">
                
                {{-- TUJUAN PEMBELAJARAN --}}
                <div class="mb-24">
                    <h3 class="text-xl font-bold text-heading mb-6 flex items-center gap-2 transition-colors">
                        <svg class="w-5 h-5 text-cyan-500 dark:text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Tujuan Pembelajaran
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <div class="card-adaptive p-5 rounded-xl flex items-start gap-4 hover:border-cyan-500/30 transition group h-full">
                            <div class="w-8 h-8 rounded bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 flex items-center justify-center shrink-0 font-bold text-xs transition-colors">1</div>
                            <div><h4 class="text-sm font-bold text-heading mb-1 transition-colors">Filosofi Utility</h4><p class="text-[11px] text-muted leading-relaxed transition-colors">Mengapa class atomik lebih efektif dari CSS konvensional.</p></div>
                        </div>
                        <div class="card-adaptive p-5 rounded-xl flex items-start gap-4 hover:border-sky-500/30 transition group h-full">
                            <div class="w-8 h-8 rounded bg-sky-500/10 text-sky-600 dark:text-sky-400 flex items-center justify-center shrink-0 font-bold text-xs transition-colors">2</div>
                            <div><h4 class="text-sm font-bold text-heading mb-1 transition-colors">Design Tokens</h4><p class="text-[11px] text-muted leading-relaxed transition-colors">Menguasai sistem warna dan tipografi bawaan.</p></div>
                        </div>
                        <div class="card-adaptive p-5 rounded-xl flex items-start gap-4 hover:border-teal-500/30 transition group h-full">
                            <div class="w-8 h-8 rounded bg-teal-500/10 text-teal-600 dark:text-teal-400 flex items-center justify-center shrink-0 font-bold text-xs transition-colors">3</div>
                            <div><h4 class="text-sm font-bold text-heading mb-1 transition-colors">Box Model</h4><p class="text-[11px] text-muted leading-relaxed transition-colors">Padding, Margin, dan Sizing yang presisi dan konsisten.</p></div>
                        </div>
                        <div class="card-adaptive p-5 rounded-xl flex items-start gap-4 hover:border-blue-500/30 transition group h-full">
                            <div class="w-8 h-8 rounded bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0 font-bold text-xs transition-colors">4</div>
                            <div><h4 class="text-sm font-bold text-heading mb-1 transition-colors">Styling UI</h4><p class="text-[11px] text-muted leading-relaxed transition-colors">Border radius dan shadow untuk estetika modern.</p></div>
                        </div>
                        <div class="bg-gradient-to-br from-cyan-100 to-blue-100 dark:from-cyan-900/40 dark:to-blue-900/40 border border-cyan-200 dark:border-cyan-500/30 p-5 rounded-xl flex items-start gap-4 hover:shadow-[0_0_20px_rgba(34,211,238,0.2)] transition group h-full col-span-2 md:col-span-2 cursor-default">
                            <div class="w-8 h-8 rounded bg-white/50 dark:bg-white/10 text-cyan-700 dark:text-white flex items-center justify-center shrink-0 font-bold text-xs shadow-sm dark:shadow-none">🏁</div>
                            <div><h4 class="text-sm font-bold text-cyan-900 dark:text-white mb-1 transition-colors">Final Mission</h4><p class="text-[11px] text-cyan-800 dark:text-white/70 leading-relaxed transition-colors">Live Code: Membangun struktur kerangka Notification Card.</p></div>
                        </div>
                    </div>
                </div>

                <article class="space-y-40">
                    
                    {{-- LESSON 1 --}}
                    <section id="section-7" class="lesson-section scroll-mt-32" data-lesson-id="7">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-cyan-500 pl-6">
                                <span class="text-cyan-600 dark:text-cyan-400 font-mono text-xs uppercase tracking-widest transition-colors">Lesson 1.2.1</span>
                                <h2 class="text-4xl lg:text-5xl font-black text-heading leading-[1.1] transition-colors">
                                    Filosofi <br> 
                                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-500 to-blue-600 dark:from-cyan-400 dark:to-blue-500">Utility-First</span>
                                </h2>
                            </div>
                            
                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-heading flex items-center gap-2 transition-colors"><span class="w-6 h-6 rounded bg-cyan-500 dark:bg-cyan-600 flex items-center justify-center text-[10px] text-white">A</span> Jangan Tinggalkan HTML Anda</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-lg leading-relaxed space-y-4 text-justify transition-colors">
                                    <p>Dalam pengembangan web tradisional, kita diajarkan untuk memisahkan struktur dan gaya. Kita membuat file HTML, memberikan class seperti <code>.card-container</code> atau menggunakan metodologi BEM seperti <code>.card__title--active</code>, lalu beralih ke file CSS untuk menulis aturannya. Proses bolak-balik antara file HTML dan CSS ini disebut <strong>"Context Switching"</strong>, yang sering kali memperlambat alur kerja dan memecah fokus kita.</p>
                                    <p><strong>Tailwind CSS merevolusi paradigma ini dengan pendekatan Utility-First.</strong> Alih-alih menulis CSS buatan sendiri, Tailwind menyediakan ribuan class kecil bermakna tunggal (seperti <code>flex</code> untuk flexbox, <code>pt-4</code> untuk padding top 1rem, atau <code>text-center</code>). Anda merakit desain visual secara langsung di dalam elemen HTML. Anda tidak perlu lagi memikirkan penamaan class yang membingungkan atau khawatir gaya Anda akan "bocor" dan merusak elemen lain di halaman yang berbeda.</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-heading flex items-center gap-2 transition-colors"><span class="w-6 h-6 rounded bg-blue-500 dark:bg-blue-600 flex items-center justify-center text-[10px] text-white">B</span> Kemenangan Skalabilitas</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-lg leading-relaxed space-y-4 text-justify transition-colors">
                                    <p>Masalah klasik CSS adalah ia selalu bertambah besar. Setiap fitur baru berarti baris CSS baru. Lama kelamaan, file CSS menjadi raksasa yang sulit dipelihara, penuh dengan "kode mati" yang tidak ada yang berani menghapusnya karena takut akan merusak tampilan di bagian lain website.</p>
                                    <p>Tailwind memecahkan masalah file CSS yang terus membengkak. Karena Tailwind mendaur ulang class utilitas yang sama di seluruh proyek, dan mesin JIT (Just-In-Time) compiler-nya hanya akan mengkompilasi class yang benar-benar Anda gunakan ke dalam hasil akhir, ukuran file CSS production Anda akan sangat kecil dan sangat optimal. Sebuah tombol di beranda dan halaman admin akan berbagi ruang class yang sama secara super efisien.</p>
                                </div>
                            </div>

                            <div class="sim-bg-adaptive border border-adaptive rounded-xl overflow-hidden shadow-xl dark:shadow-2xl flex flex-col md:flex-row h-[350px] transition-colors">
                                <div class="w-full md:w-1/2 code-adaptive p-6 font-mono text-xs flex flex-col transition-colors">
                                    <div class="flex justify-between items-center mb-4 pb-2 border-b border-adaptive transition-colors">
                                        <span class="text-muted font-bold uppercase text-[10px] transition-colors">Code Editor View</span>
                                        <div class="flex gap-2">
                                            <button onclick="setSim1('css')" class="px-3 py-1 bg-slate-200 dark:bg-white/5 border border-slate-300 dark:border-white/10 rounded text-slate-500 dark:text-gray-400 hover:text-slate-900 dark:hover:text-white transition text-[10px]">CSS Biasa</button>
                                            <button onclick="setSim1('tw')" class="px-3 py-1 bg-cyan-100 dark:bg-cyan-600/20 border border-cyan-300 dark:border-cyan-500/50 text-cyan-700 dark:text-cyan-400 rounded transition text-[10px]">Tailwind</button>
                                        </div>
                                    </div>
                                    <div id="sim1-code" class="flex-1 overflow-auto text-blue-600 dark:text-blue-300 leading-relaxed whitespace-pre font-mono bg-white dark:bg-black/20 p-4 rounded border border-adaptive shadow-inner dark:shadow-none transition-colors"></div>
                                </div>
                                <div class="w-full md:w-1/2 bg-slate-100 dark:bg-[#111] flex flex-col items-center justify-center border-l border-adaptive relative transition-colors">
                                    <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-10"></div>
                                    <button class="bg-blue-600 text-white font-bold py-2 px-6 rounded-full shadow-lg hover:scale-105 transition transform z-10">
                                        Beli Sekarang
                                    </button>
                                    <p class="mt-6 text-xs text-slate-400 dark:text-white/30 font-mono text-center px-8 transition-colors z-10">Output visual sama persis.<br>Perbedaannya ada di efisiensi penulisan.</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 2 --}}
                    <section id="section-8" class="lesson-section scroll-mt-32" data-lesson-id="8">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-cyan-500 pl-6">
                                <span class="text-cyan-600 dark:text-cyan-400 font-mono text-xs uppercase tracking-widest transition-colors">Lesson 1.2.2</span>
                                <h2 class="text-4xl lg:text-5xl font-black text-heading leading-[1.1] transition-colors">
                                    Warna & <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-500 to-blue-600 dark:from-cyan-400 dark:to-blue-500">Tipografi</span>
                                </h2>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-heading flex items-center gap-2 transition-colors"><span class="w-6 h-6 rounded bg-cyan-500 dark:bg-cyan-600 flex items-center justify-center text-[10px] text-white">A</span> Sistem Warna 50-950</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-lg leading-relaxed space-y-4 text-justify transition-colors">
                                    <p>Warna memegang peranan krusial dalam menciptakan suasana hati dan hierarki visual antarmuka. Tailwind menyediakan <strong>Sistem Desain Terkurasi</strong> dari palet warna profesional. Setiap warna memiliki spektrum ketebalan dari <code>50</code> (paling terang/pucat) hingga <code>950</code> (paling gelap/pekat). Pendekatan ini memudahkan pembuatan tema yang harmonis tanpa harus menebak kode warna Hexadecimal secara manual.</p>
                                    <p>Salah satu fitur terkuat Tailwind adalah dukungan opasitas bawaan. Anda dapat menambahkan tingkat transparansi langsung pada class warna menggunakan <i>slash syntax</i>. Sebagai contoh, <code>bg-slate-900/50</code> akan memberikan background slate gelap dengan transparansi 50%, sangat cocok untuk efek <i>glassmorphism</i> atau lapisan <i>overlay</i> pelindung di atas gambar.</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-heading flex items-center gap-2 transition-colors"><span class="w-6 h-6 rounded bg-blue-500 dark:bg-blue-600 flex items-center justify-center text-[10px] text-white">B</span> Menguasai Teks</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-lg leading-relaxed space-y-4 text-justify transition-colors">
                                    <p>Tipografi membangun hierarki visual. Di ranah tipografi, Tailwind tidak hanya memfasilitasi ukuran (seperti <code>text-sm</code>, <code>text-xl</code>), tetapi juga memberikan kontrol penuh atas <i>Line Height</i> (<code>leading-tight</code>, <code>leading-relaxed</code>) dan <i>Letter Spacing</i> (<code>tracking-wide</code>, <code>tracking-tight</code>).</p>
                                    <p>Penggunaan kombinasi yang tepat dapat secara drastis meningkatkan <i>Readability</i> (keterbacaan) untuk paragraf yang panjang, atau memberikan kesan eksklusif dan premium pada judul utama (Hero Headline) di website Anda. Semua satuan ukur yang digunakan menggunakan <code>rem</code>, memastikan tipografi Anda sepenuhnya responsif terhadap konfigurasi aksesibilitas layar pengguna.</p>
                                </div>
                            </div>

                            <div class="sim-bg-adaptive rounded-xl border border-adaptive p-6 flex flex-col md:flex-row gap-8 shadow-xl dark:shadow-2xl relative overflow-hidden transition-colors">
                                <div class="w-full md:w-1/2 space-y-6 relative z-10">
                                    <h4 class="text-xs font-bold text-muted uppercase transition-colors">Styling Text Practice</h4>
                                    <div>
                                        <label class="text-[10px] text-cyan-600 dark:text-cyan-400 block mb-2 font-bold transition-colors">SIZE</label>
                                        <div class="flex gap-2">
                                            <button onclick="updateSim2('size', 'text-sm')" class="flex-1 py-2 bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded text-xs hover:bg-cyan-50 dark:hover:bg-cyan-600/20 transition text-slate-600 dark:text-gray-300 font-medium">text-sm</button>
                                            <button onclick="updateSim2('size', 'text-2xl')" class="flex-1 py-2 bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded text-xs hover:bg-cyan-50 dark:hover:bg-cyan-600/20 transition text-slate-600 dark:text-gray-300 font-medium">text-2xl</button>
                                            <button onclick="updateSim2('size', 'text-5xl')" class="flex-1 py-2 bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded text-xs hover:bg-cyan-50 dark:hover:bg-cyan-600/20 transition text-slate-600 dark:text-gray-300 font-medium">text-5xl</button>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="text-[10px] text-pink-600 dark:text-pink-400 block mb-2 font-bold transition-colors">COLOR</label>
                                        <div class="flex gap-2">
                                            <button onclick="updateSim2('color', 'text-slate-500 dark:text-slate-300')" class="flex-1 py-2 bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded text-xs hover:bg-slate-200 dark:hover:bg-slate-600/20 transition text-slate-600 dark:text-gray-300 font-medium">Slate</button>
                                            <button onclick="updateSim2('color', 'text-cyan-600 dark:text-cyan-400')" class="flex-1 py-2 bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded text-xs hover:bg-cyan-100 dark:hover:bg-cyan-600/20 transition text-cyan-600 dark:text-cyan-400 font-medium">Cyan</button>
                                            <button onclick="updateSim2('color', 'text-rose-600 dark:text-rose-500')" class="flex-1 py-2 bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded text-xs hover:bg-rose-100 dark:hover:bg-rose-600/20 transition text-rose-600 dark:text-rose-500 font-medium">Rose</button>
                                        </div>
                                    </div>
                                    <div class="bg-white dark:bg-black/40 p-3 rounded border border-adaptive font-mono text-[10px] text-slate-500 dark:text-gray-400 mt-4 shadow-inner dark:shadow-none transition-colors">
                                        &lt;h1 class="<span id="sim2-code" class="text-cyan-600 dark:text-cyan-300">font-bold text-base text-white</span>"&gt;...&lt;/h1&gt;
                                    </div>
                                </div>
                                <div class="w-full md:w-1/2 bg-slate-800 dark:bg-black/40 rounded-xl flex items-center justify-center border border-slate-700 dark:border-white/5 min-h-[200px] relative z-10 transition-colors shadow-inner">
                                    <h1 id="sim2-target" class="text-base text-white transition-all duration-300 font-bold">Hello Tailwind</h1>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 3 --}}
                    <section id="section-9" class="lesson-section scroll-mt-32" data-lesson-id="9">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-cyan-500 pl-6">
                                <span class="text-cyan-600 dark:text-cyan-400 font-mono text-xs uppercase tracking-widest transition-colors">Lesson 1.2.3</span>
                                <h2 class="text-4xl lg:text-5xl font-black text-heading leading-[1.1] transition-colors">
                                    Spacing & <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-500 to-blue-600 dark:from-cyan-400 dark:to-blue-500">Sizing</span>
                                </h2>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-heading flex items-center gap-2 transition-colors"><span class="w-6 h-6 rounded bg-cyan-500 dark:bg-cyan-600 flex items-center justify-center text-[10px] text-white">A</span> The 4-Point Grid System</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-lg leading-relaxed space-y-4 text-justify transition-colors">
                                    <p>Konsistensi spasial adalah fondasi dari desain antarmuka yang terlihat profesional. Tailwind menggunakan <strong>The 4-Point Grid System</strong>, di mana 1 satuan bawaan bernilai <code>0.25rem</code> (atau setara dengan 4px pada ukuran font default browser). Artinya, <code>p-4</code> berarti padding sebesar 16px (4 x 4px), sedangkan <code>m-8</code> berarti margin sebesar 32px.</p>
                                    <p>Sistem ukur ini secara tidak sadar memaksa Anda untuk tetap berada pada skala yang proporsional, mencegah desain yang berantakan karena penggunaan angka piksel yang acak (misal: menebak-nebak menggunakan margin 17px atau 21px). Jangan takut menggunakan <i>whitespace</i> atau ruang kosong. Padding yang luas (seperti <code>p-6</code> atau <code>p-8</code>) seringkali merupakan kunci rahasia membuat desain terlihat lega, modern, dan tidak sumpek.</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-heading flex items-center gap-2 transition-colors"><span class="w-6 h-6 rounded bg-blue-500 dark:bg-blue-600 flex items-center justify-center text-[10px] text-white">B</span> Sizing & Constraints</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-lg leading-relaxed space-y-4 text-justify transition-colors">
                                    <p>Mengatur dimensi elemen secara fleksibel adalah inti dari layout yang responsif. Tailwind menyediakan utilitas lebar (width) dan tinggi (height) komprehensif. Anda dapat menggunakan ukuran pasti (<code>w-64</code> untuk 16rem), atau persentase fluida (<code>w-1/2</code> untuk 50%, <code>w-full</code> untuk 100%).</p>
                                    <p>Pahami juga kekuatan ukuran viewport (<code>w-screen</code>, <code>h-screen</code>) untuk membuat elemen header yang memenuhi layar penuh perangkat pengguna secara dinamis. Untuk desain berbasis kontainer, manfaatkan utilitas constraint seperti <code>max-w-md</code> untuk menjaga elemen seperti form login atau kartu profil agar tidak merentang terlihat terlalu lebar ketika dibuka di layar monitor desktop yang besar, dibantu dengan <code>mx-auto</code> untuk memosisikannya tepat di tengah layar.</p>
                                </div>
                            </div>

                            <div class="sim-bg-adaptive border border-adaptive rounded-2xl p-8 relative shadow-xl dark:shadow-2xl overflow-hidden flex flex-col items-center transition-colors">
                                <div class="w-full max-w-md space-y-6 mb-8 relative z-10">
                                    <div>
                                        <div class="flex justify-between mb-2">
                                            <label class="text-xs text-cyan-600 dark:text-cyan-400 font-bold uppercase transition-colors">Padding (p-)</label>
                                            <span class="text-xs text-slate-500 dark:text-white/50 font-mono transition-colors" id="sim3-label-p">class="p-4"</span>
                                        </div>
                                        <input type="range" min="0" max="12" value="4" oninput="updateSim3('p', this.value)" class="w-full accent-cyan-500 h-1 bg-slate-200 dark:bg-white/10 rounded cursor-pointer">
                                    </div>
                                    <div>
                                        <div class="flex justify-between mb-2">
                                            <label class="text-xs text-blue-600 dark:text-blue-400 font-bold uppercase transition-colors">Width (w-)</label>
                                            <span class="text-xs text-slate-500 dark:text-white/50 font-mono transition-colors" id="sim3-label-w">class="w-32"</span>
                                        </div>
                                        <input type="range" min="16" max="64" value="32" oninput="updateSim3('w', this.value)" class="w-full accent-blue-500 h-1 bg-slate-200 dark:bg-white/10 rounded cursor-pointer">
                                    </div>
                                </div>
                                
                                <div class="code-adaptive p-10 rounded-xl border border-adaptive w-full flex justify-center h-64 items-center relative z-10 shadow-inner transition-colors">
                                    <div id="sim3-target" class="bg-cyan-600 text-white font-bold text-center transition-all duration-300 shadow-xl overflow-hidden p-4 w-32 rounded-lg flex items-center justify-center border border-white/20">
                                        KONTEN
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 4 --}}
                    <section id="section-10" class="lesson-section scroll-mt-32" data-lesson-id="10">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-cyan-500 pl-6">
                                <span class="text-cyan-600 dark:text-cyan-400 font-mono text-xs uppercase tracking-widest transition-colors">Lesson 1.2.4</span>
                                <h2 class="text-4xl lg:text-5xl font-black text-heading leading-[1.1] transition-colors">
                                    Borders & <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-500 to-blue-600 dark:from-cyan-400 dark:to-blue-500">Effects</span>
                                </h2>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-heading flex items-center gap-2 transition-colors"><span class="w-6 h-6 rounded bg-cyan-500 dark:bg-cyan-600 flex items-center justify-center text-[10px] text-white">A</span> Rounded Corners</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-lg leading-relaxed space-y-4 text-justify transition-colors">
                                    <p>Desain antarmuka modern sangat menghindari sudut yang terlalu tajam karena dapat memberikan kesan kaku dan tidak ramah. Tailwind CSS memberikan utilitas <code>rounded</code> untuk mengontrol kelengkungan sudut elemen (Border Radius) secara instan dan konsisten di seluruh web.</p>
                                    <p>Anda dapat menggunakan <code>rounded-md</code> untuk lengkungan halus yang elegan pada tombol interaktif, <code>rounded-2xl</code> untuk membungkus kartu produk berukuran besar, hingga <code>rounded-full</code> untuk menghasilkan bentuk lingkaran sempurna yang umumnya diterapkan pada badge notifikasi atau avatar profil pengguna.</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-heading flex items-center gap-2 transition-colors"><span class="w-6 h-6 rounded bg-blue-500 dark:bg-blue-600 flex items-center justify-center text-[10px] text-white">B</span> Depth with Shadows</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-lg leading-relaxed space-y-4 text-justify transition-colors">
                                    <p>Untuk menciptakan ilusi kedalaman (Depth) ruang 3D pada layar 2D dan menyusun hierarki visual, kita menggunakan efek bayangan (Shadow). Elemen yang memiliki <i>drop-shadow</i> akan terlihat seolah-olah "mengambang" di atas lapisan background, secara psikologis membantu pandangan mata pengguna fokus pada elemen tersebut karena terkesan lebih dekat.</p>
                                    <p>Gunakan <code>shadow-md</code> untuk elevasi antarmuka yang rendah (seperti pada navbar), atau <code>shadow-xl</code> untuk elevasi dramatis (seperti pada modal popup). Praktik terbaik UI: Kombinasikan bayangan tebal tersebut dengan class interaktif seperti <code>hover:-translate-y-1 hover:shadow-2xl transition</code> untuk membuat kartu terlihat responsif dan "terangkat" secara mulus saat kursor pengguna diarahkan ke sana. Sebagai alternatif pengganti border standar yang bisa merusak ukuran layout, Tailwind juga memiliki utilitas shadow berupa <code>ring</code>, yang sangat esensial diaplikasikan pada status <code>focus</code> untuk standar aksesibilitas keyboard yang optimal.</p>
                                </div>
                            </div>

                            <div class="sim-bg-adaptive border border-adaptive rounded-2xl p-8 flex flex-col md:flex-row gap-10 items-center shadow-xl dark:shadow-2xl relative overflow-hidden transition-colors">
                                <div class="w-full md:w-1/2 space-y-6 relative z-10">
                                    <h4 class="text-xs font-bold text-muted uppercase transition-colors">Visual Effects Practice</h4>
                                    
                                    <div class="space-y-2">
                                        <p class="text-[10px] text-cyan-600 dark:text-cyan-400 font-bold transition-colors">ROUNDED</p>
                                        <div class="flex gap-2">
                                            <button onclick="updateSim4('rad', 'rounded-none')" class="flex-1 py-2 bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded text-xs hover:bg-cyan-50 dark:hover:bg-cyan-600/20 text-slate-600 dark:text-gray-400 font-medium transition-colors">None</button>
                                            <button onclick="updateSim4('rad', 'rounded-xl')" class="flex-1 py-2 bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded text-xs hover:bg-cyan-50 dark:hover:bg-cyan-600/20 text-slate-600 dark:text-gray-400 font-medium transition-colors">XL</button>
                                            <button onclick="updateSim4('rad', 'rounded-full')" class="flex-1 py-2 bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded text-xs hover:bg-cyan-50 dark:hover:bg-cyan-600/20 text-slate-600 dark:text-gray-400 font-medium transition-colors">Full</button>
                                        </div>
                                    </div>
                                    
                                    <div class="space-y-2">
                                        <p class="text-[10px] text-blue-600 dark:text-blue-400 font-bold transition-colors">SHADOW</p>
                                        <div class="flex gap-2">
                                            <button onclick="updateSim4('shadow', 'shadow-none')" class="flex-1 py-2 bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded text-xs hover:bg-cyan-50 dark:hover:bg-cyan-600/20 text-slate-600 dark:text-gray-400 font-medium transition-colors">Flat</button>
                                            <button onclick="updateSim4('shadow', 'shadow-lg')" class="flex-1 py-2 bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded text-xs hover:bg-cyan-50 dark:hover:bg-cyan-600/20 text-slate-600 dark:text-gray-400 font-medium transition-colors">Large</button>
                                            <button onclick="updateSim4('shadow', 'shadow-cyan-500/50')" class="flex-1 py-2 bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded text-xs hover:bg-cyan-50 dark:hover:bg-cyan-600/20 text-slate-600 dark:text-gray-400 font-medium transition-colors">Glow</button>
                                        </div>
                                    </div>

                                    <div class="bg-white dark:bg-black/40 p-3 rounded border border-adaptive font-mono text-[10px] text-slate-500 dark:text-gray-400 mt-4 shadow-inner dark:shadow-none transition-colors">
                                        class="<span id="sim4-code" class="text-cyan-600 dark:text-cyan-300">rounded-none shadow-none</span>"
                                    </div>
                                </div>
                                <div class="w-full md:w-1/2 flex justify-center code-adaptive p-10 rounded-xl border border-adaptive relative z-10 shadow-inner transition-colors">
                                    <div id="sim4-target" class="w-32 h-32 bg-gradient-to-br from-cyan-400 to-blue-500 dark:from-cyan-500 dark:to-blue-600 transition-all duration-500 border border-white/20"></div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 5: ACTIVITY FINAL (DIREVISI DENGAN CLUE MATANG & KONTEKSTUAL) --}}
                    <section id="section-11" class="lesson-section scroll-mt-32 pt-10 border-t border-adaptive transition-colors" data-lesson-id="11" data-type="activity">
                        <div class="relative rounded-[2rem] sim-bg-adaptive border border-adaptive p-6 md:p-10 overflow-hidden shadow-xl dark:shadow-2xl group hover:border-cyan-500/30 transition-all duration-500">
                            
                            <div class="absolute -top-24 -right-24 w-64 h-64 bg-cyan-600/10 dark:bg-cyan-600/20 blur-[100px] rounded-full pointer-events-none transition-colors"></div>

                            <div class="flex items-start md:items-center gap-4 mb-8 relative z-10 flex-col md:flex-row">
                                <div class="p-3 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-xl text-white shadow-lg shadow-cyan-500/30 shrink-0">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold text-heading transition-colors">Coding Challenge: Notification Card</h2>
                                    <p class="text-cyan-700 dark:text-cyan-300 text-sm font-medium transition-colors mt-1">Mari praktikkan apa yang baru saja kamu pelajari! Tantangannya sederhana: bangun sebuah kartu notifikasi pesan. Ingat kembali konsep Utility-First, sistem ukuran (4-point grid), dan pewarnaan. Ketik langsung class-nya di editor!</p>
                                </div>
                            </div>

                            <div class="grid lg:grid-cols-2 gap-6 max-w-6xl mx-auto h-[600px] relative z-10">
                                
                                {{-- LEFT: EDITOR --}}
                                <div class="code-adaptive rounded-xl border border-adaptive flex flex-col overflow-hidden h-full relative shadow-inner transition-colors">
                                    
                                    {{-- LOCK OVERLAY --}}
                                    <div id="lockOverlay" class="hidden absolute inset-0 bg-white/90 dark:bg-[#050912]/95 backdrop-blur-md z-50 flex flex-col items-center justify-center text-center p-6 border-4 border-emerald-500/20 m-1 rounded-lg transition-colors">
                                        <div class="w-24 h-24 bg-emerald-100 dark:bg-emerald-500/10 rounded-full flex items-center justify-center mb-6 border border-emerald-300 dark:border-emerald-500/50 shadow-[0_0_50px_rgba(16,185,129,0.2)] dark:shadow-[0_0_50px_rgba(16,185,129,0.3)] animate-bounce transition-colors">
                                            <svg class="w-12 h-12 text-emerald-600 dark:text-emerald-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                        </div>
                                        <h3 class="text-3xl font-black text-slate-900 dark:text-white mb-2 tracking-tight transition-colors">MISSION COMPLETED!</h3>
                                        <p class="text-base font-bold text-slate-500 dark:text-white/60 mb-8 max-w-xs transition-colors">Subbab 1.2 Tuntas. Lanjut ke Latar Belakang Tailwind CSS</p>
                                        <button disabled class="px-8 py-3 rounded-full bg-slate-200 dark:bg-white/5 border border-slate-300 dark:border-white/10 text-slate-500 dark:text-white/30 text-xs font-bold cursor-not-allowed uppercase tracking-widest transition-colors">Review Mode</button>
                                    </div>

                                    <div class="bg-slate-200 dark:bg-[#0f141e] px-4 py-2 border-b border-adaptive flex justify-between items-center transition-colors">
                                        <span class="text-xs text-slate-600 dark:text-white/50 font-mono font-bold transition-colors">Component.html</span>
                                        <button onclick="resetEditor()" class="text-[10px] text-red-500 dark:text-red-400 hover:text-red-600 dark:hover:text-red-300 transition-colors uppercase font-bold">Reset</button>
                                    </div>
                                    
                                    <div id="codeEditor" class="flex-1 w-full border-b border-adaptive transition-colors"></div>

                                    {{-- CLUES AREA DENGAN INSTRUKSI KONTEKSTUAL --}}
                                    <div class="p-6 bg-slate-50 dark:bg-[#0f141e] transition-colors flex flex-col h-[280px]">
                                        <div class="flex justify-between items-center mb-4">
                                            <span class="text-[10px] uppercase font-bold text-muted transition-colors">Requirements Checklist</span>
                                            <span id="progressText" class="text-[10px] font-mono text-cyan-600 dark:text-cyan-400 font-bold transition-colors">0/4 Terpenuhi</span>
                                        </div>
                                        
                                        <div class="space-y-3 text-[11px] text-slate-600 dark:text-white/60 mb-6 transition-colors overflow-y-auto custom-scrollbar pr-2 flex-1">
                                            <div id="check-bg" class="flex items-start gap-3 transition-colors">
                                                <span class="w-4 h-4 mt-0.5 rounded-full border border-slate-300 dark:border-white/20 flex items-center justify-center text-[8px] transition-colors shrink-0"></span> 
                                                <div>
                                                    <strong class="block text-slate-800 dark:text-white mb-0.5">Warna Latar Belakang</strong>
                                                    <span class="opacity-80">Supaya cocok dengan tema gelap, berikan warna background pada kontainer luar menggunakan palet <code class="text-cyan-600 dark:text-cyan-400 font-bold">slate</code> dengan tingkat kegelapan <code class="text-cyan-600 dark:text-cyan-400 font-bold">800</code>.</span>
                                                </div>
                                            </div>
                                            <div id="check-pad" class="flex items-start gap-3 transition-colors">
                                                <span class="w-4 h-4 mt-0.5 rounded-full border border-slate-300 dark:border-white/20 flex items-center justify-center text-[8px] transition-colors shrink-0"></span> 
                                                <div>
                                                    <strong class="block text-slate-800 dark:text-white mb-0.5">Jarak Dalam (Padding)</strong>
                                                    <span class="opacity-80">Kartunya terlihat terlalu sesak. Beri bantalan di semua sisinya (padding) sebesar 24px. Ingat aturan 4-point grid Tailwind? 24 dibagi 4 adalah 6.</span>
                                                </div>
                                            </div>
                                            <div id="check-flex" class="flex items-start gap-3 transition-colors">
                                                <span class="w-4 h-4 mt-0.5 rounded-full border border-slate-300 dark:border-white/20 flex items-center justify-center text-[8px] transition-colors shrink-0"></span> 
                                                <div>
                                                    <strong class="block text-slate-800 dark:text-white mb-0.5">Tata Letak Elemen</strong>
                                                    <span class="opacity-80">Ikon dan teksnya masih bertumpuk atas-bawah. Buat mereka sejajar menyamping dengan model <code class="text-cyan-600 dark:text-cyan-400 font-bold">flex</code>, lalu beri jarak antar keduanya sebesar 16px (hint: gap-...).</span>
                                                </div>
                                            </div>
                                            <div id="check-round" class="flex items-start gap-3 transition-colors">
                                                <span class="w-4 h-4 mt-0.5 rounded-full border border-slate-300 dark:border-white/20 flex items-center justify-center text-[8px] transition-colors shrink-0"></span> 
                                                <div>
                                                    <strong class="block text-slate-800 dark:text-white mb-0.5">Sudut Melengkung</strong>
                                                    <span class="opacity-80">UI modern benci sudut tajam. Buat sudut-sudut luar kartu ini melengkung dengan ukuran ekstra besar (<code class="text-cyan-600 dark:text-cyan-400 font-bold">xl</code>).</span>
                                                </div>
                                            </div>
                                        </div>

                                        <button id="submitExerciseBtn" onclick="checkSolution()" disabled class="w-full py-3 rounded-lg bg-emerald-600 text-white font-bold text-xs shadow-lg shadow-emerald-500/20 hover:bg-emerald-500 transition-all cursor-not-allowed opacity-50 flex items-center justify-center gap-2 mt-auto shrink-0">
                                            <span>Validasi & Kunci</span>
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </button>
                                    </div>
                                </div>

                                {{-- RIGHT: BROWSER PREVIEW --}}
                                <div class="bg-white dark:bg-[#1e1e1e] rounded-xl border border-adaptive flex-1 flex flex-col relative overflow-hidden min-h-[500px] shadow-sm dark:shadow-none transition-colors">
                                    <div class="bg-slate-100 dark:bg-[#2d2d2d] px-4 py-2 border-b border-adaptive flex items-center justify-between transition-colors">
                                        <span class="text-[10px] text-slate-500 dark:text-gray-400 font-mono font-bold transition-colors">Browser Preview</span>
                                        <span class="text-[10px] bg-emerald-100 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 px-2 py-0.5 rounded border border-emerald-200 dark:border-emerald-500/20 font-bold transition-colors">Live</span>
                                    </div>
                                    <iframe id="previewFrame" class="w-full h-full border-0 bg-slate-50 dark:bg-transparent transition-colors"></iframe>
                                </div>
                            </div>
                        </div>
                    </section>

                </article>

                {{-- FOOTER NAVIGATION --}}
                <div class="mt-32 pt-8 border-t border-adaptive flex justify-between items-center transition-colors">
                    <a href="{{ route('courses.htmldancss') ?? '#' }}" class="group flex items-center gap-4 text-muted hover:text-heading transition-colors">
                        <div class="w-12 h-12 rounded-full border border-adaptive flex items-center justify-center group-hover:bg-slate-100 dark:group-hover:bg-white/5 transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        </div>
                        <div class="text-left hidden sm:block">
                            <div class="text-[10px] uppercase tracking-widest font-bold opacity-60">Sebelumnya</div>
                            <div class="font-black text-sm">Konsep Dasar HTML & CSS</div>
                        </div>
                    </a>
                    
                    <div id="nextChapterBtn" class="group flex items-center gap-4 text-right text-muted cursor-not-allowed opacity-50 pointer-events-none transition-all duration-500">
                        <div class="text-right hidden sm:block">
                            <div id="nextLabel" class="text-[10px] uppercase tracking-widest font-bold opacity-60">Terkunci</div>
                            <div class="font-black text-sm">Latar Belakang & Struktur</div>
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

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/monaco-editor@0.45.0/min/vs/loader.js"></script>

<script>
    /* --- CONFIGURATION AJAX & PROGRESS --- */
    // PASTIKAN window.LESSON_IDS SAMA DENGAN ID DI DATABASE `course_lessons` UNTUK BAB INI
    window.LESSON_IDS = [7, 8, 9, 10, 11]; 
    
    let rawCompletedIds = {!! json_encode($completedLessonIds ?? []) !!};
    window.COMPLETED_IDS = rawCompletedIds.map(id => Number(id)); 
    let completedSet = new Set(window.COMPLETED_IDS);
    
    // Cek status khusus Activity Lesson (Pastikan 11 adalah ID untuk activity bab ini di DB)
    const ACTIVITY_LESSON_ID = 11; 
    let activityCompleted = completedSet.has(ACTIVITY_LESSON_ID);

    document.addEventListener('DOMContentLoaded', () => {
        initScrollSpy();
        initSidebarScroll();
        initVisualEffects();
        
        // Render Progress Bar awal
        updateProgressUI(false); 
        
        // Inisialisasi Monaco Editor
        initMonaco();
        setSim1('css');
        
        if (activityCompleted) {
            lockActivityUI();
            unlockNextChapter();
        }

        // Inisialisasi observer scroll
        initLessonObserver();
        
        // Tandai sidebar jika sudah komplit
        document.querySelectorAll('.nav-item').forEach(item => {
            const targetId = parseInt(item.getAttribute('data-target').replace('#section-', ''));
            if(completedSet.has(targetId)) {
                markSidebarDone(targetId);
            }
        });
    });

    // ==========================================
    // LOGIKA UPDATE PROGRESS BAR DINAMIS
    // ==========================================
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
                    dot.outerHTML = `<svg class="w-4 h-4 text-amber-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>`;
                } else {
                    dot.outerHTML = `<svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>`;
                }
            }
        }
    }

    // ==========================================
    // AJAX POST REQUEST KE DATABASE (BULLETPROOF)
    // ==========================================
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

    // ==========================================
    // OBSERVER SCROLL (DENGAN THRESHOLD 0.1)
    // ==========================================
    function initLessonObserver() {
        const obs = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const id = Number(entry.target.dataset.lessonId);
                    if (id && entry.target.dataset.type !== 'activity' && !completedSet.has(id)) {
                        saveLessonToDB(id); 
                    }
                }
            });
        }, { 
            threshold: 0.1, 
            rootMargin: "0px 0px -100px 0px", 
            root: document.getElementById('mainScroll') 
        });
        
        document.querySelectorAll('.lesson-section').forEach(s => obs.observe(s));
    }


    /* ==========================================
       SIMULATORS 1-4 (Visual)
       ========================================== */
    function setSim1(mode) {
        const codeBox = document.getElementById('sim1-code');
        if (mode === 'css') {
            codeBox.innerHTML = `.btn {\n  background-color: blue;\n  color: white;\n  padding: 10px 20px;\n  border-radius: 5px;\n}`;
        } else {
            codeBox.innerHTML = `&lt;button class="bg-blue-600 text-white px-4 py-2 rounded"&gt;\n  Button\n&lt;/button&gt;`;
        }
    }

    let typeState = { size: 'text-base', color: 'text-white', weight: 'font-normal' };
    function updateSim2(cat, val) {
        const isDark = document.documentElement.classList.contains('dark');
        const target = document.getElementById('sim2-target');
        const code = document.getElementById('sim2-code');
        
        if(cat === 'color') {
            target.classList.remove('text-slate-300', 'text-slate-500', 'text-cyan-400', 'text-cyan-600', 'text-rose-500', 'text-rose-600', 'text-white', 'text-slate-800');
            typeState.color = val;
        }
        if(cat === 'size') {
            target.classList.remove('text-sm', 'text-base', 'text-2xl', 'text-5xl');
            typeState.size = val;
        }
        
        target.classList.add(typeState.size);
        const colorClasses = typeState.color.split(' ');
        colorClasses.forEach(c => target.classList.add(c));
        
        const finalColorClass = colorClasses.length > 1 ? (isDark ? colorClasses[1].replace('dark:', '') : colorClasses[0]) : colorClasses[0];
        code.innerText = `font-bold ${typeState.size} ${finalColorClass}`;
    }

    function updateSim3(cat, val) {
        const target = document.getElementById('sim3-target');
        if(cat === 'p') { document.getElementById('sim3-label-p').innerText = `class="p-${val}"`; target.style.padding = `${val * 4}px`; }
        if(cat === 'w') { document.getElementById('sim3-label-w').innerText = `class="w-${val}"`; target.style.width = `${val * 4}px`; }
    }

    let effectState = { rad: 'rounded-none', shadow: 'shadow-none' };
    function updateSim4(cat, val) {
        const target = document.getElementById('sim4-target');
        const code = document.getElementById('sim4-code');
        if(cat === 'rad') { target.classList.remove('rounded-none', 'rounded-xl', 'rounded-full'); target.classList.add(val); effectState.rad = val; }
        if(cat === 'shadow') { target.classList.remove('shadow-none', 'shadow-lg', 'shadow-2xl', 'shadow-cyan-500/50'); target.classList.add(val); effectState.shadow = val; }
        code.innerText = `${effectState.rad} ${effectState.shadow}`;
    }


    /* ==========================================
       REALTIME CODING & ACTIVITY LOGIC (Lesson 11)
       ========================================== */
    let editor;
    const starterCode = `<div class="">
  
  <div class="p-3 bg-cyan-500/20 rounded-full shrink-0 h-fit">
    <svg class="w-6 h-6 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
  </div>

  <div>
    <h4 class="font-bold text-lg text-white">Pesan Baru</h4>
    <p class="text-slate-400 text-sm">Anda mendapat pesan pembaruan sistem dari tim DevStudio.</p>
  </div>

</div>`;

    function initMonaco() {
        require.config({ paths: { vs: 'https://cdn.jsdelivr.net/npm/monaco-editor@0.45.0/min/vs' } });
        require(['vs/editor/editor.main'], function () {
            
            const isDark = document.documentElement.classList.contains('dark');
            
            editor = monaco.editor.create(document.getElementById('codeEditor'), {
                value: starterCode, 
                language: 'html', 
                theme: isDark ? 'vs-dark' : 'vs', 
                fontSize: 13,
                minimap: { enabled: false }, 
                automaticLayout: true, 
                padding: { top: 16 }, 
                lineNumbers: 'off',
                scrollBeyondLastLine: false
            });
            updatePreview(starterCode);
            
            editor.onDidChangeModelContent(() => {
                if(activityCompleted) return;
                const code = editor.getValue();
                updatePreview(code);
                validateCode(code);
            });
            
            window.addEventListener('theme-toggled', () => {
                const newIsDark = document.documentElement.classList.contains('dark');
                monaco.editor.setTheme(newIsDark ? 'vs-dark' : 'vs');
            });
        });
    }

    function updatePreview(code) {
        const frame = document.getElementById('previewFrame');
        const isDark = document.documentElement.classList.contains('dark');
        const bgColor = isDark ? '#111827' : '#f8fafc'; 
        
        const content = `<!doctype html><html><head><script src="https://cdn.tailwindcss.com"><\/script><style>body { background-color: ${bgColor}; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; font-family: sans-serif; transition: background-color 0.3s; }</style></head><body>${code}</body></html>`;
        frame.srcdoc = content;
    }

    // UPDATE: Validasi diubah untuk membaca struktur Clue yang baru
    function validateCode(code) {
        const checks = [
            { id: 'check-bg', regex: /bg-slate-800/, valid: false },
            { id: 'check-pad', regex: /p-6/, valid: false },
            { id: 'check-flex', regex: /flex.*gap-4|gap-4.*flex/, valid: false },
            { id: 'check-round', regex: /rounded-xl/, valid: false }
        ];
        let passedCount = 0;
        const isDark = document.documentElement.classList.contains('dark');

        checks.forEach(check => {
            const el = document.getElementById(check.id);
            const dot = el.querySelector('span'); // Dot indicator
            const textContainer = el.querySelector('div'); // Wrapper untuk text

            if (check.regex.test(code)) {
                textContainer.classList.remove('opacity-80');
                textContainer.classList.add(isDark ? 'text-green-400' : 'text-emerald-600');
                
                dot.innerHTML = '✓'; 
                dot.classList.remove('border-slate-300', 'border-white/20');
                dot.classList.add('bg-emerald-500', 'border-transparent', 'text-white');
                passedCount++;
            } else {
                textContainer.classList.add('opacity-80');
                textContainer.classList.remove('text-green-400', 'text-emerald-600');
                
                dot.innerHTML = ''; 
                dot.classList.remove('bg-emerald-500', 'border-transparent', 'text-white');
                dot.classList.add(isDark ? 'border-white/20' : 'border-slate-300');
            }
        });
        
        document.getElementById('progressText').innerText = `${passedCount}/4 Terpenuhi`;
        const btn = document.getElementById('submitExerciseBtn');
        if (passedCount === 4) {
            btn.disabled = false; btn.classList.remove('cursor-not-allowed', 'opacity-50');
            btn.innerHTML = `<span>Validasi & Kunci</span><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`;
        } else {
            btn.disabled = true; btn.classList.add('cursor-not-allowed', 'opacity-50');
        }
    }

    function resetEditor() { if(editor && !activityCompleted) { editor.setValue(starterCode); validateCode(starterCode); } }

    async function checkSolution() {
        if(activityCompleted) return;
        const btn = document.getElementById('submitExerciseBtn');
        
        btn.innerHTML = '<span class="animate-pulse">Menyimpan...</span>'; 
        btn.disabled = true;
        
        try {
            await saveLessonToDB(ACTIVITY_LESSON_ID); 
            
            activityCompleted = true; 
            lockActivityUI(); 
            unlockNextChapter(); 
        } catch(e) { 
            console.error(e); 
            btn.innerHTML = "Gagal Menyimpan."; 
            btn.disabled = false; 
        }
    }

    function lockActivityUI() {
        document.getElementById('lockOverlay').classList.remove('hidden');
        if(editor) editor.updateOptions({ readOnly: true });
        
        const btn = document.getElementById('submitExerciseBtn'); 
        btn.innerText = "Terkunci (Selesai)"; 
        btn.disabled = true;
        btn.classList.remove('bg-emerald-600', 'hover:bg-emerald-500');
        btn.classList.add('bg-slate-400', 'dark:bg-slate-700', 'text-slate-200', 'cursor-not-allowed', 'shadow-none');
    }

    function unlockNextChapter() {
        const btn = document.getElementById('nextChapterBtn');
        if(btn) {
            btn.classList.remove('cursor-not-allowed', 'opacity-50', 'pointer-events-none', 'text-muted');
            btn.classList.add('text-cyan-600', 'dark:text-cyan-400', 'cursor-pointer');
            
            document.getElementById('nextLabel').innerText = "Selanjutnya";
            document.getElementById('nextLabel').classList.remove('opacity-60');
            document.getElementById('nextLabel').classList.add('text-cyan-600', 'dark:text-cyan-400', 'opacity-100');
            
            const icon = document.getElementById('nextIcon');
            icon.innerHTML = `<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>`;
            icon.classList.remove('bg-slate-100', 'dark:bg-white/5');
            icon.classList.add('bg-cyan-100', 'dark:bg-cyan-500/20', 'border-cyan-300', 'dark:border-cyan-500/50', 'text-cyan-600', 'dark:text-cyan-400', 'shadow-lg');
            
            btn.onclick = () => window.location.href = "{{ route('courses.latarbelakang') ?? '#' }}"; 
        }
    }

    /* ==========================================
       7. SCROLL SPY & SIDEBAR LOGIC
       ========================================== */
    function highlightAnchor(id) {
        const isDark = document.documentElement.classList.contains('dark');
        const anchors = document.querySelectorAll('.sidebar-anchor');

        anchors.forEach(a => {
            a.classList.remove('bg-slate-100', 'dark:bg-white/5', 'border-cyan-500', 'border-amber-500');
            a.classList.add('border-transparent');
            
            const dot = a.querySelector('.anchor-dot');
            const isActivity = a.dataset.type === 'activity';

            dot.classList.remove('scale-125', 'shadow-sm', 'dark:shadow-[0_0_10px_#22d3ee]', 'dark:shadow-[0_0_10px_#f59e0b]', 'bg-cyan-500', 'dark:bg-cyan-400');
            
            if (isActivity) {
                dot.classList.remove('bg-amber-500', 'dark:bg-amber-400');
                dot.classList.add('bg-slate-400', 'dark:bg-slate-600'); 
            } else {
                dot.classList.remove('bg-cyan-500', 'dark:bg-cyan-400');
                dot.classList.add('bg-slate-400', 'dark:bg-slate-600'); 
            }

            const text = a.querySelector('.anchor-text');
            text.classList.remove('text-slate-800', 'dark:text-white', 'font-bold');
            text.classList.add('text-slate-500');
        });

        const activeAnchor = document.querySelector(`.sidebar-anchor[data-target="${id}"]`);
        if (activeAnchor) {
            const isActivity = activeAnchor.dataset.type === 'activity';
            
            activeAnchor.classList.add(isDark ? 'dark:bg-white/5' : 'bg-slate-100');
            activeAnchor.classList.add(isActivity ? 'border-amber-500' : 'border-cyan-500');
            activeAnchor.classList.remove('border-transparent');
            
            const dot = activeAnchor.querySelector('.anchor-dot');
            dot.classList.remove('bg-slate-400', 'dark:bg-slate-600');
            
            if (isActivity) {
                dot.classList.add(isDark ? 'dark:bg-amber-400' : 'bg-amber-500', 'scale-125', isDark ? 'dark:shadow-[0_0_10px_#f59e0b]' : 'shadow-sm');
            } else {
                dot.classList.add(isDark ? 'dark:bg-cyan-400' : 'bg-cyan-500', 'scale-125', isDark ? 'dark:shadow-[0_0_10px_#22d3ee]' : 'shadow-sm');
            }
            
            const text = activeAnchor.querySelector('.anchor-text');
            text.classList.remove('text-slate-500');
            text.classList.add(isDark ? 'dark:text-white' : 'text-slate-800', 'font-bold');
        }
    }

    function initScrollSpy() {
        const mainScroll = document.getElementById('mainScroll'); 
        const sections = document.querySelectorAll('.lesson-section');

        if (mainScroll && sections.length > 0) {
            const observerOptions = { root: mainScroll, threshold: 0.5 };
            const observer = new IntersectionObserver((entries) => {
                let intersectingEntries = entries.filter(e => e.isIntersecting);
                if(intersectingEntries.length > 0) {
                    if (typeof highlightAnchor === 'function') {
                        highlightAnchor(intersectingEntries[0].target.id);
                    }
                }
            }, observerOptions);

            sections.forEach(section => observer.observe(section));
        }
    }

    function initSidebarScroll(){
        const m = document.getElementById('mainScroll');
        const l = document.querySelectorAll('.accordion-content .nav-item');
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