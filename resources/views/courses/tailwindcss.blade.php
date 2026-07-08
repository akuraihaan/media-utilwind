@extends('layouts.landing')
@section('title','Konsep Dasar Tailwind CSS')

@section('content')

<script>
    if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
</script>

<style>
    :root {
        --bg-main: #f8fafc;
        --text-main: #0f172a;
        --glass-bg: rgba(255, 255, 255, 0.86);
        --glass-border: rgba(15, 23, 42, 0.08);
        --glass-header: rgba(255, 255, 255, 0.84);
        --card-bg: #ffffff;
        --card-hover: rgba(15, 23, 42, 0.03);
        --border-color: rgba(15, 23, 42, 0.10);
        --text-muted: #64748b;
        --text-heading: #0f172a;
        --code-bg: #f1f5f9;
        --simulator-bg: #ffffff;
        --accent: #6366f1;
        --accent-glow: rgba(99, 102, 241, 0.32);
    }

    .dark {
        --bg-main: #020617;
        --text-main: #e2e8f0;
        --glass-bg: rgba(10, 14, 23, 0.88);
        --glass-border: rgba(255, 255, 255, 0.06);
        --glass-header: rgba(2, 6, 23, 0.82);
        --card-bg: #0f172a;
        --card-hover: rgba(255, 255, 255, 0.04);
        --border-color: rgba(255, 255, 255, 0.10);
        --text-muted: rgba(226, 232, 240, 0.62);
        --text-heading: #ffffff;
        --code-bg: #111827;
        --simulator-bg: #0b1120;
        --accent-glow: rgba(99, 102, 241, 0.48);
    }

    body { font-family: 'Inter', sans-serif; background-color: var(--bg-main); color: var(--text-main); transition: background-color .4s, color .4s; }
    .font-mono { font-family: 'JetBrains Mono', monospace; }
    .bg-adaptive { background-color: var(--bg-main); }
    .text-adaptive { color: var(--text-main); }
    .text-heading { color: var(--text-heading); }
    .text-muted { color: var(--text-muted); }
    .border-adaptive { border-color: var(--border-color); }
    .card-adaptive { background-color: var(--card-bg); border-color: var(--glass-border); transition: all .3s; }
    .card-adaptive:hover { border-color: var(--accent-glow); transform: translateY(-1px); }
    .sim-bg-adaptive { background-color: var(--simulator-bg); }
    .code-adaptive { background-color: var(--code-bg); border-color: var(--glass-border); }

    .hl-term {
        background-color: rgba(99, 102, 241, 0.14);
        color: #4f46e5;
        padding: .125rem .375rem;
        border-radius: .375rem;
        font-weight: 700;
        border: 1px solid rgba(99, 102, 241, .26);
        white-space: nowrap;
    }
    .dark .hl-term { color: #a5b4fc; background-color: rgba(99,102,241,.20); border-color: rgba(99,102,241,.38); }

    .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(148,163,184,.38); border-radius: 999px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #6366f1; }

    #animated-bg {
        background:
            radial-gradient(600px circle at 18% 16%, rgba(99,102,241,.12), transparent 40%),
            radial-gradient(700px circle at 85% 25%, rgba(14,165,233,.10), transparent 42%),
            radial-gradient(800px circle at 48% 88%, rgba(168,85,247,.10), transparent 44%);
        animation: bgMove 20s ease-in-out infinite alternate;
    }
    .dark #animated-bg {
        background:
            radial-gradient(600px circle at 18% 16%, rgba(99,102,241,.18), transparent 40%),
            radial-gradient(700px circle at 85% 25%, rgba(14,165,233,.14), transparent 42%),
            radial-gradient(800px circle at 48% 88%, rgba(168,85,247,.16), transparent 44%);
    }
    @keyframes bgMove { to { transform: scale(1.12); } }
    @keyframes shake { 0%,100%{transform:translateX(0)} 25%{transform:translateX(-5px)} 75%{transform:translateX(5px)} }
    .shake { animation: shake .4s ease-in-out; }

    .nav-item { display:flex; width:100%; text-align:left; align-items:center; gap:12px; padding:10px 14px; font-size:.85rem; color:var(--text-muted); border-radius:8px; transition:all .2s; }
    .nav-item:hover { color:var(--text-main); background:var(--card-hover); }
    .nav-item.active { color:#6366f1; background:rgba(99,102,241,.08); font-weight:700; }
    .dot { width:6px; height:6px; border-radius:50%; background:#94a3b8; transition:all .3s; }
    .dark .dot { background:#475569; }
    .nav-item.active .dot { background:#6366f1; box-shadow:0 0 8px #6366f1; transform:scale(1.2); }

    .code-line { display:block; min-width:max-content; }
    .tag { color:#7c3aed; font-weight:700; }
    .attr { color:#d97706; }
    .str { color:#059669; }
    .prop { color:#2563eb; }
    .comment { color:#94a3b8; font-style:italic; }
    .dark .tag { color:#c084fc; }
    .dark .attr { color:#fbbf24; }
    .dark .str { color:#34d399; }
    .dark .prop { color:#60a5fa; }

    .activity-option.correct { border-color:#10b981 !important; background:rgba(16,185,129,.10) !important; }
    .activity-option.wrong { border-color:#ef4444 !important; background:rgba(239,68,68,.10) !important; }
</style>

@include('courses.partials.interactive-activity-kit')

<div id="courseRoot" class="relative h-screen bg-adaptive text-adaptive font-sans overflow-hidden flex flex-col selection:bg-indigo-500/30 pt-20 transition-colors duration-500">

    <div class="fixed inset-0 -z-50 pointer-events-none">
        <div id="animated-bg" class="absolute inset-0 opacity-60 transition-opacity"></div>
        <canvas id="stars" class="absolute inset-0 pointer-events-none opacity-0 dark:opacity-100 transition-opacity duration-500"></canvas>
    </div>

    @include('layouts.partials.navbar')

    <div class="flex flex-1 overflow-hidden relative z-20 h-full">
        @include('layouts.partials.course-sidebar')

        <main id="mainScroll" class="flex-1 h-full overflow-y-auto scroll-smooth relative bg-transparent custom-scrollbar scroll-padding-top-24">

            <div id="stickyHeader" class="sticky top-0 z-30 w-full backdrop-blur-2xl border-b border-adaptive px-4 md:px-8 py-4 flex items-center justify-between transition-colors duration-500" style="background-color: var(--glass-header);">
                <div class="flex items-center gap-4 min-w-0">
                    <div class="w-8 h-8 rounded-lg bg-indigo-500/10 dark:bg-indigo-500/20 border border-indigo-500/20 flex items-center justify-center font-bold text-xs text-indigo-600 dark:text-indigo-400 shrink-0">1.2</div>
                    <div class="min-w-0">
                        <h1 class="text-sm font-bold text-heading line-clamp-1">Konsep Dasar Tailwind CSS</h1>
                        <p class="text-[10px] text-muted line-clamp-1">Utility class, utility-first, dan cara membaca class</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <div class="hidden sm:block w-24 md:w-32 h-1.5 bg-slate-200 dark:bg-white/10 rounded-full overflow-hidden">
                        <div id="topProgressBar" class="h-full bg-gradient-to-r from-indigo-500 to-purple-500 w-0 transition-all duration-500 shadow-[0_0_10px_rgba(99,102,241,0.5)]"></div>
                    </div>
                    <span id="progressLabelTop" class="text-indigo-600 dark:text-indigo-400 font-bold text-xs">0%</span>
                </div>
            </div>

            <div class="p-4 sm:p-6 lg:p-16 max-w-5xl mx-auto pb-40">

                <div class="mb-16 md:mb-24">
                    <div class="card-adaptive rounded-3xl border p-6 md:p-8 overflow-hidden relative">
                        <div class="absolute -right-10 -top-10 w-40 h-40 bg-indigo-500/10 rounded-full blur-3xl"></div>
                        <p class="text-xs font-mono font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-[.25em] mb-3">Subbab 1.2</p>
                        <h2 class="text-3xl md:text-5xl font-black text-heading leading-tight mb-4">Konsep Dasar Tailwind CSS</h2>
                        <p class="text-sm md:text-base text-muted leading-relaxed max-w-3xl text-justify">
                            Pada subbab ini, pembelajar mempelajari konsep dasar Tailwind CSS sebagai framework CSS berbasis utility-first. Materi berfokus pada cara kerja utility class, cara membaca nama class, dan cara menyusun tampilan sederhana dari beberapa class kecil tanpa membahas CDN, instalasi, atau konfigurasi.
                        </p>
                    </div>

                    <h3 class="text-xl font-bold text-heading mt-10 mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Tujuan Pembelajaran
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="card-adaptive border rounded-xl p-5">
                            <div class="w-8 h-8 rounded-lg bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold mb-4">1</div>
                            <h4 class="text-sm font-bold text-heading mb-1">Menjelaskan Tailwind</h4>
                            <p class="text-[11px] text-muted leading-relaxed">Menjelaskan Tailwind CSS sebagai framework yang menyediakan class siap pakai untuk mengatur tampilan.</p>
                        </div>
                        <div class="card-adaptive border rounded-xl p-5">
                            <div class="w-8 h-8 rounded-lg bg-purple-500/10 text-purple-600 dark:text-purple-400 flex items-center justify-center font-bold mb-4">2</div>
                            <h4 class="text-sm font-bold text-heading mb-1">Membaca Utility</h4>
                            <p class="text-[11px] text-muted leading-relaxed">Menghubungkan class seperti <code>p-4</code>, <code>bg-blue-600</code>, dan <code>rounded-lg</code> dengan efek tampilannya.</p>
                        </div>
                        <div class="card-adaptive border rounded-xl p-5">
                            <div class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold mb-4">3</div>
                            <h4 class="text-sm font-bold text-heading mb-1">Menyusun Tampilan</h4>
                            <p class="text-[11px] text-muted leading-relaxed">Menganalisis gabungan beberapa class utility pada satu elemen HTML.</p>
                        </div>
                        <div class="card-adaptive border rounded-xl p-5">
                            <div class="w-8 h-8 rounded-lg bg-orange-500/10 text-orange-600 dark:text-orange-400 flex items-center justify-center font-bold mb-4">4</div>
                            <h4 class="text-sm font-bold text-heading mb-1">Membedakan Pendekatan</h4>
                            <p class="text-[11px] text-muted leading-relaxed">Membedakan penulisan CSS biasa dengan pendekatan utility-first secara sederhana.</p>
                        </div>
                    </div>
                </div>

                <article class="space-y-32 md:space-y-40">

                    <section id="section-6" class="lesson-section scroll-mt-32" data-lesson-id="6">
                        <div class="space-y-8">
                            <div class="space-y-4 border-l-4 border-indigo-500 pl-4 md:pl-6">
                                <span class="text-indigo-600 dark:text-indigo-400 font-mono text-xs uppercase tracking-widest">Lesson 1.2.1</span>
                                <h2 class="text-3xl md:text-5xl font-black text-heading leading-tight">Apa itu <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-purple-600">Tailwind CSS?</span></h2>
                            </div>

                            <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-90 text-sm md:text-base leading-relaxed space-y-4 text-justify">
                                <p>Tailwind CSS adalah framework CSS yang menyediakan kumpulan class kecil untuk mengatur tampilan halaman web. Class tersebut dapat langsung ditulis pada atribut <code>class</code> di elemen HTML.</p>
                                <p>Jika pada CSS biasa pembelajar membuat selector sendiri, misalnya <code>.tombol-utama</code>, pada Tailwind pembelajar memakai class yang sudah tersedia, seperti <span class="hl-term">bg-blue-600</span>, <span class="hl-term">text-white</span>, <span class="hl-term">px-4</span>, dan <span class="hl-term">rounded-lg</span>. Setiap class memiliki tugas tampilan yang spesifik.</p>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                <div class="code-adaptive border border-adaptive rounded-2xl overflow-hidden">
                                    <div class="px-4 py-3 border-b border-adaptive flex items-center justify-between">
                                        <span class="font-mono text-xs font-bold text-muted">CSS biasa</span>
                                        <span class="text-[10px] uppercase tracking-widest text-indigo-500 font-bold">selector sendiri</span>
                                    </div>
                                    <pre class="p-5 overflow-auto custom-scrollbar text-xs md:text-sm font-mono leading-relaxed"><code><span class="code-line"><span class="tag">.tombol-utama</span> {</span>
<span class="code-line">  <span class="prop">background-color</span>: <span class="str">blue</span>;</span>
<span class="code-line">  <span class="prop">color</span>: <span class="str">white</span>;</span>
<span class="code-line">  <span class="prop">padding</span>: <span class="str">12px 16px</span>;</span>
<span class="code-line">  <span class="prop">border-radius</span>: <span class="str">8px</span>;</span>
<span class="code-line">}</span>

<span class="code-line"><span class="tag">&lt;button</span> <span class="attr">class</span>=<span class="str">"tombol-utama"</span><span class="tag">&gt;</span>Simpan<span class="tag">&lt;/button&gt;</span></span></code></pre>
                                </div>

                                <div class="code-adaptive border border-adaptive rounded-2xl overflow-hidden">
                                    <div class="px-4 py-3 border-b border-adaptive flex items-center justify-between">
                                        <span class="font-mono text-xs font-bold text-muted">Tailwind CSS</span>
                                        <span class="text-[10px] uppercase tracking-widest text-purple-500 font-bold">utility class</span>
                                    </div>
                                    <pre class="p-5 overflow-auto custom-scrollbar text-xs md:text-sm font-mono leading-relaxed"><code><span class="code-line"><span class="tag">&lt;button</span></span>
<span class="code-line">  <span class="attr">class</span>=<span class="str">"bg-blue-600 text-white px-4 py-3 rounded-lg"</span></span>
<span class="code-line"><span class="tag">&gt;</span></span>
<span class="code-line">  Simpan</span>
<span class="code-line"><span class="tag">&lt;/button&gt;</span></span></code></pre>
                                </div>
                            </div>

                            <div class="sim-bg-adaptive border border-adaptive rounded-2xl overflow-hidden shadow-xl">
                                <div class="bg-indigo-600/95 dark:bg-indigo-900/95 text-white p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3 border-b border-indigo-400/50">
                                    <div class="text-xs font-bold uppercase tracking-widest">Simulasi 1 — Bandingkan Cara Penulisan</div>
                                    <p class="text-[11px] opacity-90 leading-relaxed m-0 md:text-right max-w-xl">Pilih pendekatan penulisan untuk melihat perbedaan antara CSS biasa dan utility class Tailwind.</p>
                                </div>
                                <div class="grid grid-cols-1 lg:grid-cols-2 min-h-[360px]">
                                    <div class="code-adaptive border-b lg:border-b-0 lg:border-r border-adaptive flex flex-col">
                                        <div class="p-4 flex flex-wrap gap-2 border-b border-adaptive">
                                            <button onclick="setCompareMode('css', this)" class="compare-btn px-3 py-2 rounded-lg bg-indigo-600 text-white border border-indigo-500 text-xs font-bold transition">CSS biasa</button>
                                            <button onclick="setCompareMode('tailwind', this)" class="compare-btn px-3 py-2 rounded-lg bg-white dark:bg-white/5 border border-adaptive text-xs font-bold hover:border-indigo-500 transition">Tailwind</button>
                                        </div>
                                        <pre id="compare-code" class="p-5 text-xs md:text-sm font-mono leading-relaxed overflow-auto custom-scrollbar flex-1"></pre>
                                    </div>
                                    <div class="bg-white dark:bg-slate-950 flex flex-col">
                                        <div class="px-4 py-3 border-b border-adaptive bg-slate-50 dark:bg-black/20">
                                            <span class="text-[10px] font-mono font-bold text-muted uppercase tracking-widest">Hasil Tampilan</span>
                                        </div>
                                        <div class="flex-1 p-6 md:p-8 flex items-center justify-center">
                                            <button id="compare-preview" class="font-bold shadow-lg transition-all duration-300">Simpan Data</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gradient-to-r from-indigo-50 to-transparent dark:from-indigo-900/20 dark:to-transparent border-l-4 border-indigo-500 p-4 rounded-r-xl">
                                <h5 class="text-xs font-bold text-indigo-700 dark:text-indigo-400 mb-1">Kesimpulan</h5>
                                <p class="text-[11px] text-slate-700 dark:text-slate-300 leading-relaxed m-0 text-justify">Tailwind tidak menghilangkan CSS. Tailwind menyediakan cara lain untuk memakai aturan CSS melalui class kecil yang langsung dibaca pada HTML.</p>
                            </div>
                        </div>
                    </section>

                    <section id="section-7" class="lesson-section scroll-mt-32" data-lesson-id="7">
                        <div class="space-y-8">
                            <div class="space-y-4 border-l-4 border-indigo-500 pl-4 md:pl-6">
                                <span class="text-indigo-600 dark:text-indigo-400 font-mono text-xs uppercase tracking-widest">Lesson 1.2.2</span>
                                <h2 class="text-3xl md:text-5xl font-black text-heading leading-tight">Utility Class dan <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-purple-600">Utility-First</span></h2>
                            </div>

                            <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-90 text-sm md:text-base leading-relaxed space-y-4 text-justify">
                                <p>Utility class adalah class yang mewakili satu aturan tampilan tertentu. Contohnya, <code>text-center</code> digunakan untuk meratakan teks ke tengah, <code>p-4</code> digunakan untuk memberi padding, dan <code>bg-slate-100</code> digunakan untuk memberi warna latar.</p>
                                <p>Disebut utility-first karena tampilan dibangun dari kumpulan utility class terlebih dahulu. Pembelajar tidak perlu langsung membuat nama class baru untuk setiap komponen. Cukup susun utility sesuai kebutuhan tampilan.</p>
                            </div>

                            <div class="card-adaptive border rounded-2xl p-5 md:p-6">
                                <h3 class="font-bold text-heading mb-4">Contoh membaca utility class</h3>
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                    <div class="bg-slate-100 dark:bg-black/30 rounded-xl p-4 border border-adaptive">
                                        <div class="text-xs font-bold text-heading mb-2"><code>bg-white</code></div>
                                        <p class="text-xs text-muted leading-relaxed">Memberi latar belakang putih pada elemen.</p>
                                    </div>
                                    <div class="bg-slate-100 dark:bg-black/30 rounded-xl p-4 border border-adaptive">
                                        <div class="text-xs font-bold text-heading mb-2"><code>p-6</code></div>
                                        <p class="text-xs text-muted leading-relaxed">Memberi ruang dalam sebesar skala 6.</p>
                                    </div>
                                    <div class="bg-slate-100 dark:bg-black/30 rounded-xl p-4 border border-adaptive">
                                        <div class="text-xs font-bold text-heading mb-2"><code>rounded-xl</code></div>
                                        <p class="text-xs text-muted leading-relaxed">Membuat sudut elemen lebih melengkung.</p>
                                    </div>
                                    <div class="bg-slate-100 dark:bg-black/30 rounded-xl p-4 border border-adaptive">
                                        <div class="text-xs font-bold text-heading mb-2"><code>shadow-md</code></div>
                                        <p class="text-xs text-muted leading-relaxed">Menambahkan bayangan sedang pada elemen.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="sim-bg-adaptive border border-adaptive rounded-2xl overflow-hidden shadow-xl">
                                <div class="bg-purple-600/95 dark:bg-purple-900/95 text-white p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3 border-b border-purple-400/50">
                                    <div class="text-xs font-bold uppercase tracking-widest">Simulasi 2 — Terjemahkan Utility Class</div>
                                    <p class="text-[11px] opacity-90 leading-relaxed m-0 md:text-right max-w-xl">Pilih utility class untuk melihat arti tampilannya secara langsung.</p>
                                </div>
                                <div class="grid grid-cols-1 lg:grid-cols-2 min-h-[360px]">
                                    <div class="code-adaptive border-b lg:border-b-0 lg:border-r border-adaptive p-5">
                                        <div class="grid grid-cols-2 gap-2 mb-5">
                                            <button onclick="explainUtility('bg-blue-600')" class="px-3 py-2 rounded-lg bg-white dark:bg-white/5 border border-adaptive text-xs font-bold hover:border-indigo-500 transition">bg-blue-600</button>
                                            <button onclick="explainUtility('text-white')" class="px-3 py-2 rounded-lg bg-white dark:bg-white/5 border border-adaptive text-xs font-bold hover:border-indigo-500 transition">text-white</button>
                                            <button onclick="explainUtility('p-6')" class="px-3 py-2 rounded-lg bg-white dark:bg-white/5 border border-adaptive text-xs font-bold hover:border-indigo-500 transition">p-6</button>
                                            <button onclick="explainUtility('rounded-xl')" class="px-3 py-2 rounded-lg bg-white dark:bg-white/5 border border-adaptive text-xs font-bold hover:border-indigo-500 transition">rounded-xl</button>
                                            <button onclick="explainUtility('shadow-md')" class="px-3 py-2 rounded-lg bg-white dark:bg-white/5 border border-adaptive text-xs font-bold hover:border-indigo-500 transition">shadow-md</button>
                                            <button onclick="explainUtility('text-center')" class="px-3 py-2 rounded-lg bg-white dark:bg-white/5 border border-adaptive text-xs font-bold hover:border-indigo-500 transition">text-center</button>
                                        </div>
                                        <pre id="utility-explain-code" class="bg-white dark:bg-black/30 border border-adaptive rounded-xl p-4 overflow-auto custom-scrollbar text-xs md:text-sm font-mono leading-relaxed"></pre>
                                    </div>
                                    <div class="bg-white dark:bg-slate-950 flex flex-col">
                                        <div class="px-4 py-3 border-b border-adaptive bg-slate-50 dark:bg-black/20">
                                            <span class="text-[10px] font-mono font-bold text-muted uppercase tracking-widest">Penjelasan</span>
                                        </div>
                                        <div class="flex-1 flex items-center justify-center p-8 bg-slate-50 dark:bg-slate-900/40">
                                            <div id="utility-explain-box" class="max-w-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-2xl p-6 shadow-sm transition-all duration-300">
                                                <h3 class="font-black text-slate-900 dark:text-white mb-2">Pilih Utility</h3>
                                                <p class="text-sm text-slate-600 dark:text-slate-300">Tekan salah satu class di sebelah kiri untuk melihat arti dan efeknya.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gradient-to-r from-purple-50 to-transparent dark:from-purple-900/20 dark:to-transparent border-l-4 border-purple-500 p-4 rounded-r-xl">
                                <h5 class="text-xs font-bold text-purple-700 dark:text-purple-400 mb-1">Kesimpulan</h5>
                                <p class="text-[11px] text-slate-700 dark:text-slate-300 leading-relaxed m-0 text-justify">Utility class membuat aturan tampilan lebih mudah dibaca karena nama class menunjukkan fungsi tampilannya secara langsung.</p>
                            </div>
                        </div>
                    </section>

                    <section id="section-8" class="lesson-section scroll-mt-32" data-lesson-id="8">
                        <div class="space-y-8">
                            <div class="space-y-4 border-l-4 border-indigo-500 pl-4 md:pl-6">
                                <span class="text-indigo-600 dark:text-indigo-400 font-mono text-xs uppercase tracking-widest">Lesson 1.2.3</span>
                                <h2 class="text-3xl md:text-5xl font-black text-heading leading-tight">Menyusun Tampilan dari <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-purple-600">Beberapa Class</span></h2>
                            </div>

                            <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-90 text-sm md:text-base leading-relaxed space-y-4 text-justify">
                                <p>Satu utility class biasanya hanya mengatur satu bagian tampilan. Karena itu, komponen Tailwind dibangun dengan menggabungkan beberapa utility class dalam satu atribut <code>class</code>.</p>
                                <p>Contohnya, sebuah kartu dapat memiliki latar putih, padding, border, sudut melengkung, dan bayangan. Masing-masing bagian tampilan tersebut ditulis sebagai class yang berbeda agar mudah dibaca dan diubah.</p>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                <div class="code-adaptive border border-adaptive rounded-2xl overflow-hidden">
                                    <div class="px-4 py-3 border-b border-adaptive flex items-center justify-between">
                                        <span class="font-mono text-xs font-bold text-muted">Komponen kartu</span>
                                        <span class="text-[10px] uppercase tracking-widest text-indigo-500 font-bold">class gabungan</span>
                                    </div>
                                    <pre class="p-5 overflow-auto custom-scrollbar text-xs md:text-sm font-mono leading-relaxed"><code><span class="code-line"><span class="tag">&lt;article</span></span>
<span class="code-line">  <span class="attr">class</span>=<span class="str">"bg-white border border-slate-300 rounded-xl p-6 shadow-md"</span></span>
<span class="code-line"><span class="tag">&gt;</span></span>
<span class="code-line">  <span class="tag">&lt;h3&gt;</span>Produk Pilihan<span class="tag">&lt;/h3&gt;</span></span>
<span class="code-line">  <span class="tag">&lt;p&gt;</span>Sepatu ringan untuk harian.<span class="tag">&lt;/p&gt;</span></span>
<span class="code-line"><span class="tag">&lt;/article&gt;</span></span></code></pre>
                                </div>

                                <div class="card-adaptive border rounded-2xl p-5 flex flex-col justify-center">
                                    <h3 class="font-bold text-heading mb-4">Cara membaca dari kiri ke kanan</h3>
                                    <div class="space-y-3 text-sm">
                                        <div class="flex gap-3"><div class="w-8 h-8 rounded-lg bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold shrink-0">1</div><p class="text-muted leading-relaxed"><code>bg-white</code> mengatur warna latar kartu.</p></div>
                                        <div class="flex gap-3"><div class="w-8 h-8 rounded-lg bg-purple-500/10 text-purple-600 dark:text-purple-400 flex items-center justify-center font-bold shrink-0">2</div><p class="text-muted leading-relaxed"><code>border border-slate-300</code> memberi garis tepi.</p></div>
                                        <div class="flex gap-3"><div class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold shrink-0">3</div><p class="text-muted leading-relaxed"><code>rounded-xl p-6 shadow-md</code> mengatur sudut, ruang dalam, dan bayangan.</p></div>
                                    </div>
                                </div>
                            </div>

                            <div class="sim-bg-adaptive border border-adaptive rounded-2xl overflow-hidden shadow-xl">
                                <div class="bg-indigo-600/95 dark:bg-indigo-900/95 text-white p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3 border-b border-indigo-400/50">
                                    <div class="text-xs font-bold uppercase tracking-widest">Simulasi 3 — Bangun Kartu dengan Utility</div>
                                    <p class="text-[11px] opacity-90 leading-relaxed m-0 md:text-right max-w-xl">Aktifkan atau matikan class untuk melihat pengaruhnya pada kartu.</p>
                                </div>
                                <div class="grid grid-cols-1 lg:grid-cols-2 min-h-[420px]">
                                    <div class="code-adaptive border-b lg:border-b-0 lg:border-r border-adaptive p-5">
                                        <div class="grid grid-cols-2 gap-2 mb-5">
                                            <label class="flex items-center gap-2 bg-white dark:bg-black/30 border border-adaptive rounded-lg p-3 text-xs font-bold"><input type="checkbox" class="accent-indigo-600" onchange="updateCardBuilder()" data-class="bg-white" checked> bg-white</label>
                                            <label class="flex items-center gap-2 bg-white dark:bg-black/30 border border-adaptive rounded-lg p-3 text-xs font-bold"><input type="checkbox" class="accent-indigo-600" onchange="updateCardBuilder()" data-class="border" checked> border</label>
                                            <label class="flex items-center gap-2 bg-white dark:bg-black/30 border border-adaptive rounded-lg p-3 text-xs font-bold"><input type="checkbox" class="accent-indigo-600" onchange="updateCardBuilder()" data-class="rounded-xl" checked> rounded-xl</label>
                                            <label class="flex items-center gap-2 bg-white dark:bg-black/30 border border-adaptive rounded-lg p-3 text-xs font-bold"><input type="checkbox" class="accent-indigo-600" onchange="updateCardBuilder()" data-class="p-6" checked> p-6</label>
                                            <label class="flex items-center gap-2 bg-white dark:bg-black/30 border border-adaptive rounded-lg p-3 text-xs font-bold"><input type="checkbox" class="accent-indigo-600" onchange="updateCardBuilder()" data-class="shadow-md" checked> shadow-md</label>
                                            <label class="flex items-center gap-2 bg-white dark:bg-black/30 border border-adaptive rounded-lg p-3 text-xs font-bold"><input type="checkbox" class="accent-indigo-600" onchange="updateCardBuilder()" data-class="text-center"> text-center</label>
                                        </div>
                                        <pre id="card-builder-code" class="bg-white dark:bg-black/30 border border-adaptive rounded-xl p-4 overflow-auto custom-scrollbar text-xs md:text-sm font-mono leading-relaxed"></pre>
                                    </div>
                                    <div class="bg-white dark:bg-slate-950 flex flex-col">
                                        <div class="px-4 py-3 border-b border-adaptive bg-slate-50 dark:bg-black/20">
                                            <span class="text-[10px] font-mono font-bold text-muted uppercase tracking-widest">Browser Preview</span>
                                        </div>
                                        <div class="flex-1 flex items-center justify-center p-8 bg-slate-50 dark:bg-slate-900/40">
                                            <article id="card-builder-preview" class="transition-all duration-300 max-w-xs">
                                                <h3 class="font-black text-slate-900 dark:text-white mb-2">Produk Pilihan</h3>
                                                <p class="text-sm text-slate-600 dark:text-slate-300 mb-4">Sepatu ringan untuk kegiatan harian.</p>
                                                <button class="px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-bold">Lihat Produk</button>
                                            </article>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gradient-to-r from-indigo-50 to-transparent dark:from-indigo-900/20 dark:to-transparent border-l-4 border-indigo-500 p-4 rounded-r-xl">
                                <h5 class="text-xs font-bold text-indigo-700 dark:text-indigo-400 mb-1">Kesimpulan</h5>
                                <p class="text-[11px] text-slate-700 dark:text-slate-300 leading-relaxed m-0 text-justify">Kekuatan Tailwind terletak pada penyusunan class kecil. Setiap class memberi satu perubahan tampilan sehingga komponen dapat dibaca dari daftar class-nya.</p>
                            </div>
                        </div>
                    </section>

                    <section id="section-9" class="lesson-section scroll-mt-32" data-lesson-id="9">
                        <div class="space-y-8">
                            <div class="space-y-4 border-l-4 border-indigo-500 pl-4 md:pl-6">
                                <span class="text-indigo-600 dark:text-indigo-400 font-mono text-xs uppercase tracking-widest">Lesson 1.2.4</span>
                                <h2 class="text-3xl md:text-5xl font-black text-heading leading-tight">Kapan Utility-First <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-purple-600">Digunakan?</span></h2>
                            </div>

                            <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-90 text-sm md:text-base leading-relaxed space-y-4 text-justify">
                                <p>Pendekatan utility-first cocok digunakan ketika pembelajar ingin membuat tampilan secara cepat dan tetap konsisten. Nama class Tailwind sudah mengikuti pola tertentu sehingga lebih mudah ditebak setelah memahami dasarnya.</p>
                                <p>Walaupun begitu, Tailwind tetap perlu digunakan secara teratur. Daftar class yang terlalu panjang dapat membuat kode sulit dibaca jika tidak dirapikan. Pada proyek yang lebih besar, komponen yang sering digunakan dapat dipisahkan menjadi komponen Blade agar tidak ditulis berulang-ulang.</p>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                <div class="card-adaptive border rounded-2xl p-5">
                                    <h3 class="font-bold text-heading mb-4">Kelebihan dasar</h3>
                                    <div class="space-y-3 text-sm text-muted leading-relaxed">
                                        <p><span class="hl-term">Cepat</span> karena class langsung ditulis pada elemen yang sedang dibuat.</p>
                                        <p><span class="hl-term">Konsisten</span> karena nilai ukuran, warna, dan jarak mengikuti skala Tailwind.</p>
                                        <p><span class="hl-term">Mudah dilihat</span> karena tampilan elemen dapat dipahami dari atribut class.</p>
                                    </div>
                                </div>
                                <div class="card-adaptive border rounded-2xl p-5">
                                    <h3 class="font-bold text-heading mb-4">Hal yang perlu diperhatikan</h3>
                                    <div class="space-y-3 text-sm text-muted leading-relaxed">
                                        <p>Jangan menulis class secara sembarangan tanpa memahami fungsi class tersebut.</p>
                                        <p>Gunakan urutan class yang rapi agar mudah dibaca kembali.</p>
                                        <p>Komponen yang sering dipakai sebaiknya dibuat menjadi komponen terpisah pada tahap lanjutan.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="sim-bg-adaptive border border-adaptive rounded-2xl overflow-hidden shadow-xl">
                                <div class="bg-orange-600/95 dark:bg-orange-900/95 text-white p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3 border-b border-orange-400/50">
                                    <div class="text-xs font-bold uppercase tracking-widest">Simulasi 4 — Pilih Pendekatan yang Tepat</div>
                                    <p class="text-[11px] opacity-90 leading-relaxed m-0 md:text-right max-w-xl">Pilih situasi untuk melihat apakah utility-first cocok digunakan.</p>
                                </div>
                                <div class="grid grid-cols-1 lg:grid-cols-2 min-h-[360px]">
                                    <div class="code-adaptive border-b lg:border-b-0 lg:border-r border-adaptive p-5">
                                        <div class="space-y-2">
                                            <button onclick="setScenario('prototype')" class="w-full text-left px-4 py-3 rounded-xl bg-white dark:bg-black/30 border border-adaptive text-xs font-bold hover:border-indigo-500 transition">Membuat tampilan kartu produk sederhana</button>
                                            <button onclick="setScenario('repeat')" class="w-full text-left px-4 py-3 rounded-xl bg-white dark:bg-black/30 border border-adaptive text-xs font-bold hover:border-indigo-500 transition">Membuat tombol sama pada banyak halaman</button>
                                            <button onclick="setScenario('random')" class="w-full text-left px-4 py-3 rounded-xl bg-white dark:bg-black/30 border border-adaptive text-xs font-bold hover:border-indigo-500 transition">Menulis banyak class tanpa memahami fungsinya</button>
                                        </div>
                                    </div>
                                    <div class="bg-white dark:bg-slate-950 flex flex-col">
                                        <div class="px-4 py-3 border-b border-adaptive bg-slate-50 dark:bg-black/20">
                                            <span class="text-[10px] font-mono font-bold text-muted uppercase tracking-widest">Rekomendasi</span>
                                        </div>
                                        <div class="flex-1 flex items-center justify-center p-8 bg-slate-50 dark:bg-slate-900/40">
                                            <div id="scenario-box" class="max-w-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-2xl p-6 shadow-sm">
                                                <h3 class="font-black text-slate-900 dark:text-white mb-2">Pilih situasi</h3>
                                                <p class="text-sm text-slate-600 dark:text-slate-300">Gunakan tombol di sebelah kiri untuk melihat penjelasan.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gradient-to-r from-orange-50 to-transparent dark:from-orange-900/20 dark:to-transparent border-l-4 border-orange-500 p-4 rounded-r-xl">
                                <h5 class="text-xs font-bold text-orange-700 dark:text-orange-400 mb-1">Kesimpulan</h5>
                                <p class="text-[11px] text-slate-700 dark:text-slate-300 leading-relaxed m-0 text-justify">Tailwind membantu mempercepat penyusunan tampilan, tetapi tetap membutuhkan pemahaman fungsi class dan kerapian struktur kode.</p>
                            </div>
                        </div>
                    </section>

                    <section id="section-10" class="lesson-section scroll-mt-32" data-lesson-id="10" data-type="activity">
                        <div class="space-y-8">
                            <div class="space-y-4 border-l-4 border-purple-500 pl-4 md:pl-6">
                                <span class="text-purple-600 dark:text-purple-400 font-mono text-xs uppercase tracking-widest">Aktivitas 1.2</span>
                                <h2 class="text-3xl md:text-5xl font-black text-heading leading-tight">Analisis Utility <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-500 to-indigo-600">Tailwind CSS</span></h2>
                            </div>

                            <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-90 text-sm md:text-base leading-relaxed text-justify">
                                <p>Aktivitas ini menggunakan class composer. Pilih chip utility yang sesuai, lalu amati perubahan kartu pada preview!</p>
                            </div>

                            <div class="card-adaptive border rounded-2xl overflow-hidden shadow-xl relative">
                                <div id="lockOverlay" class="hidden absolute inset-0 z-20 bg-white/80 dark:bg-slate-950/80 backdrop-blur-sm items-center justify-center p-6">
                                    <div class="max-w-md text-center">
                                        <div class="w-14 h-14 rounded-full bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mx-auto mb-4 text-2xl">✓</div>
                                        <h3 class="font-black text-heading mb-2">Aktivitas Sudah Selesai</h3>
                                        <p class="text-sm text-muted leading-relaxed">Jawaban aktivitas telah tersimpan. Anda dapat melanjutkan ke subbab berikutnya.</p>
                                    </div>
                                </div>

                                <div class="bg-purple-600/95 dark:bg-purple-900/95 text-white p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                                    <div class="text-xs font-bold uppercase tracking-widest">Class Composer</div>
                                    <p class="text-[11px] opacity-90 leading-relaxed m-0 md:text-right max-w-xl">Pilih chip utility untuk membentuk kartu produk, lalu tekan tombol periksa!</p>
                                </div>

                                <div id="activityForm" class="p-4 md:p-6 space-y-4 max-h-[620px] overflow-y-auto custom-scrollbar">
                                    <div class="activity-question card-adaptive border rounded-xl p-5" data-answer="b">
                                        <p class="text-sm font-bold text-heading mb-4">1. Tailwind CSS disebut berbasis utility-first karena ....</p>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                            <button onclick="chooseActivity(this, 'q1', 'a')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-indigo-500 transition">A. Semua tampilan harus dibuat dari file gambar</button>
                                            <button onclick="chooseActivity(this, 'q1', 'b')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-indigo-500 transition">B. Tampilan dibangun dari class kecil yang memiliki fungsi tertentu</button>
                                            <button onclick="chooseActivity(this, 'q1', 'c')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-indigo-500 transition">C. Semua CSS harus ditulis di dalam tag <code>&lt;style&gt;</code></button>
                                            <button onclick="chooseActivity(this, 'q1', 'd')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-indigo-500 transition">D. Tailwind hanya digunakan untuk membuat struktur HTML</button>
                                        </div>
                                    </div>

                                    <div class="activity-question card-adaptive border rounded-xl p-5" data-answer="c">
                                        <p class="text-sm font-bold text-heading mb-4">2. Class <code>p-6</code> pada Tailwind CSS digunakan untuk ....</p>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                            <button onclick="chooseActivity(this, 'q2', 'a')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-indigo-500 transition">A. Mengatur warna latar elemen</button>
                                            <button onclick="chooseActivity(this, 'q2', 'b')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-indigo-500 transition">B. Mengatur ukuran huruf elemen</button>
                                            <button onclick="chooseActivity(this, 'q2', 'c')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-indigo-500 transition">C. Memberi ruang dalam pada elemen</button>
                                            <button onclick="chooseActivity(this, 'q2', 'd')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-indigo-500 transition">D. Menghapus garis tepi elemen</button>
                                        </div>
                                    </div>

                                    <div class="activity-question card-adaptive border rounded-xl p-5" data-answer="a">
                                        <p class="text-sm font-bold text-heading mb-4">3. Gabungan class <code>bg-blue-600 text-white rounded-lg</code> paling tepat menghasilkan tampilan ....</p>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                            <button onclick="chooseActivity(this, 'q3', 'a')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-indigo-500 transition">A. Latar biru, teks putih, dan sudut melengkung</button>
                                            <button onclick="chooseActivity(this, 'q3', 'b')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-indigo-500 transition">B. Latar putih, teks biru, dan sudut tajam</button>
                                            <button onclick="chooseActivity(this, 'q3', 'c')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-indigo-500 transition">C. Teks besar, jarak luar, dan garis bawah</button>
                                            <button onclick="chooseActivity(this, 'q3', 'd')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-indigo-500 transition">D. Gambar bulat, tombol merah, dan margin otomatis</button>
                                        </div>
                                    </div>

                                    <div class="activity-question card-adaptive border rounded-xl p-5" data-answer="d">
                                        <p class="text-sm font-bold text-heading mb-4">4. Dibanding membuat class <code>.kartu-produk</code> sendiri, pendekatan Tailwind pada tahap dasar lebih menekankan ....</p>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                            <button onclick="chooseActivity(this, 'q4', 'a')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-indigo-500 transition">A. Menghapus seluruh atribut HTML</button>
                                            <button onclick="chooseActivity(this, 'q4', 'b')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-indigo-500 transition">B. Menulis selector baru untuk semua elemen</button>
                                            <button onclick="chooseActivity(this, 'q4', 'c')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-indigo-500 transition">C. Mengganti HTML dengan file CSS</button>
                                            <button onclick="chooseActivity(this, 'q4', 'd')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-indigo-500 transition">D. Menyusun tampilan dari class utility yang sudah tersedia</button>
                                        </div>
                                    </div>

                                    <div class="activity-question card-adaptive border rounded-xl p-5" data-answer="b">
                                        <p class="text-sm font-bold text-heading mb-4">5. Sebuah kartu memiliki class <code>bg-white border rounded-xl p-6 shadow-md</code>. Bagian yang menunjukkan kartu memiliki bayangan adalah ....</p>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                            <button onclick="chooseActivity(this, 'q5', 'a')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-indigo-500 transition">A. <code>border</code></button>
                                            <button onclick="chooseActivity(this, 'q5', 'b')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-indigo-500 transition">B. <code>shadow-md</code></button>
                                            <button onclick="chooseActivity(this, 'q5', 'c')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-indigo-500 transition">C. <code>bg-white</code></button>
                                            <button onclick="chooseActivity(this, 'q5', 'd')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-indigo-500 transition">D. <code>p-6</code></button>
                                        </div>
                                    </div>
                                </div>

                                <div class="border-t border-adaptive p-4 md:p-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
                                    <div>
                                        <p id="activity-status" class="text-xs font-bold text-muted">Belum diperiksa.</p>
                                        <p id="activity-score" class="text-sm font-black text-heading mt-1">Skor: -</p>
                                    </div>
                                    <button id="submitBtn" onclick="checkActivity()" class="px-5 py-3 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold text-xs shadow-lg hover:shadow-indigo-500/30 transition">Periksa Jawaban</button>
                                </div>

                                <div id="activity-analysis" class="hidden border-t border-adaptive p-4 md:p-6 bg-slate-50 dark:bg-black/20">
                                    <h3 class="font-bold text-heading mb-3">Status Aktivitas</h3>
                                    <div class="space-y-2 text-xs text-muted leading-relaxed">
                                        <p>Aktivitas telah memenuhi skor minimal. Progress materi berhasil diproses.</p>
                                        <p>Gunakan perubahan preview sebagai bahan refleksi sebelum melanjutkan.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                </article>

                <div class="mt-20 md:mt-32 pt-8 border-t border-adaptive flex flex-col sm:flex-row justify-between items-center gap-4">
                    <a href="{{ route('courses.htmldancss') ?? '#' }}" class="group flex items-center gap-4 text-muted hover:text-heading transition-colors w-full sm:w-auto justify-center sm:justify-start">
                        <div class="w-10 h-10 md:w-12 md:h-12 rounded-full border border-adaptive flex items-center justify-center group-hover:bg-slate-100 dark:group-hover:bg-white/5 transition-colors shrink-0">
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        </div>
                        <div class="text-left">
                            <div class="text-[10px] uppercase tracking-widest font-bold opacity-60">Sebelumnya</div>
                            <div class="font-black text-xs md:text-sm line-clamp-1">Konsep Dasar HTML dan CSS</div>
                        </div>
                    </a>

                    <div id="nextChapterBtn" class="group flex items-center gap-4 text-right text-muted cursor-not-allowed opacity-50 pointer-events-none transition-all duration-500 w-full sm:w-auto justify-center sm:justify-end">
                        <div class="text-right">
                            <div id="nextLabel" class="text-[10px] uppercase tracking-widest font-bold opacity-60">Berikutnya</div>
                            <div class="font-black text-xs md:text-sm line-clamp-1">Tailwind CSS melalui CDN</div>
                        </div>
                        <div id="nextIcon" class="w-10 h-10 md:w-12 md:h-12 rounded-full border border-adaptive flex items-center justify-center bg-slate-100 dark:bg-white/5 transition-colors shrink-0">
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>

<script>
    window.LESSON_IDS = [6, 7, 8, 9, 10];
    let rawCompletedIds = {!! json_encode($completedLessonIds ?? []) !!};
    window.COMPLETED_IDS = rawCompletedIds.map(id => Number(id));
    let completedSet = new Set(window.COMPLETED_IDS);
    const ACTIVITY_LESSON_ID = 10;
    let activityCompleted = {!! ($activityCompleted ?? false) ? 'true' : 'false' !!};

    const activityAnswers = {};
    let activityWidget = null;

    document.addEventListener('DOMContentLoaded', () => {
        initScrollSpy();
        initVisualEffects();
        initLessonObserver();
        updateProgressUI(false);
        setCompareMode('css');
        explainUtility('bg-blue-600');
        updateCardBuilder();
        initTailwindClassActivity();

        if (activityCompleted) {
            lockActivityUI();
            unlockNextChapter();
        }

        document.querySelectorAll('.nav-item').forEach(item => {
            const target = item.getAttribute('data-target');
            if (!target) return;
            const targetId = parseInt(target.replace('#section-', ''));
            if (completedSet.has(targetId)) markSidebarDone(targetId);
        });
    });

    function updateProgressUI(animate = true) {
        const total = window.LESSON_IDS.length;
        const done = window.LESSON_IDS.filter(id => completedSet.has(Number(id))).length;
        const percent = Math.round((done / total) * 100);
        const bar = document.getElementById('topProgressBar');
        const label = document.getElementById('progressLabelTop');
        if (!bar || !label) return;
        if(!animate) bar.style.transition = 'none';
        bar.style.width = percent + '%';
        if(!animate) setTimeout(() => bar.style.transition = 'all .5s', 50);
        label.innerText = percent + '%';
        if (percent === 100 && activityCompleted) unlockNextChapter();
    }

    function markSidebarDone(lessonId) {
        const navItem = document.querySelector(`.nav-item[data-target="#section-${lessonId}"]`);
        if(navItem) {
            const dot = navItem.querySelector('.dot');
            if(dot) {
                dot.outerHTML = `<svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>`;
            }
        }
    }

    async function saveLessonToDB(lessonId) {
        lessonId = Number(lessonId);
        if (completedSet.has(lessonId) && !(lessonId === ACTIVITY_LESSON_ID && !activityCompleted)) return true;

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
                if (lessonId === ACTIVITY_LESSON_ID) {
                    window.markActiveCourseItemCompleted?.();
                }
            }
        } catch(e) {
            console.error('Network Error:', e);
        }
    }

    function initLessonObserver() {
        const root = document.getElementById('mainScroll');
        const obs = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const id = Number(entry.target.dataset.lessonId);
                    if (id && entry.target.dataset.type !== 'activity' && !completedSet.has(id)) {
                        saveLessonToDB(id);
                    }
                }
            });
        }, { threshold: 0.12, rootMargin: "0px 0px -50px 0px", root });
        document.querySelectorAll('.lesson-section').forEach(s => obs.observe(s));
    }

    function setCompareMode(mode, btn = null) {
        document.querySelectorAll('.compare-btn').forEach(b => {
            b.classList.remove('bg-indigo-600','text-white','border-indigo-500');
            b.classList.add('bg-white','dark:bg-white/5','border-adaptive');
        });
        if (btn) {
            btn.classList.add('bg-indigo-600','text-white','border-indigo-500');
            btn.classList.remove('bg-white','dark:bg-white/5','border-adaptive');
        }

        const code = document.getElementById('compare-code');
        const preview = document.getElementById('compare-preview');
        if (!code || !preview) return;

        preview.style.backgroundColor = '#2563eb';
        preview.style.color = '#ffffff';
        preview.style.padding = '12px 18px';
        preview.style.borderRadius = '10px';

        if (mode === 'css') {
            code.innerHTML = `<code><span class="tag">.tombol-utama</span> {
  <span class="prop">background-color</span>: <span class="str">#2563eb</span>;
  <span class="prop">color</span>: <span class="str">white</span>;
  <span class="prop">padding</span>: <span class="str">12px 18px</span>;
  <span class="prop">border-radius</span>: <span class="str">10px</span>;
}

<span class="tag">&lt;button</span> <span class="attr">class</span>=<span class="str">"tombol-utama"</span><span class="tag">&gt;</span>Simpan Data<span class="tag">&lt;/button&gt;</span></code>`;
        } else {
            code.innerHTML = `<code><span class="tag">&lt;button</span>
  <span class="attr">class</span>=<span class="str">"bg-blue-600 text-white px-4 py-3 rounded-lg"</span>
<span class="tag">&gt;</span>
  Simpan Data
<span class="tag">&lt;/button&gt;</span></code>`;
        }
    }

    function explainUtility(cls) {
        const code = document.getElementById('utility-explain-code');
        const box = document.getElementById('utility-explain-box');
        if (!code || !box) return;

        const map = {
            'bg-blue-600': ['background-color', 'Memberi warna latar biru yang kuat pada elemen.', 'bg-blue-600', 'bg-blue-600 text-white'],
            'text-white': ['color', 'Mengubah warna teks menjadi putih.', 'text-white', 'bg-slate-800 text-white'],
            'p-6': ['padding', 'Memberi ruang dalam pada semua sisi elemen.', 'p-6', 'p-6 border border-slate-300'],
            'rounded-xl': ['border-radius', 'Membuat sudut elemen melengkung lebih besar.', 'rounded-xl', 'rounded-xl border border-slate-300 p-4'],
            'shadow-md': ['box-shadow', 'Memberi bayangan sedang agar elemen tampak terangkat.', 'shadow-md', 'shadow-md border border-slate-200 p-4'],
            'text-center': ['text-align', 'Membuat isi teks berada di tengah.', 'text-center', 'text-center border border-slate-300 p-4']
        };

        const item = map[cls];
        code.innerHTML = `<code><span class="comment">/* Utility class */</span>
<span class="tag">${item[2]}</span>

<span class="comment">/* Makna sederhana */</span>
<span class="prop">${item[0]}</span>: <span class="str">sesuai skala Tailwind</span>;</code>`;

        box.className = `max-w-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-2xl transition-all duration-300 ${item[3]}`;
        box.innerHTML = `<h3 class="font-black mb-2">${cls}</h3><p class="text-sm opacity-80">${item[1]}</p>`;
    }

    function updateCardBuilder() {
        const preview = document.getElementById('card-builder-preview');
        const code = document.getElementById('card-builder-code');
        if (!preview || !code) return;

        const checked = Array.from(document.querySelectorAll('[data-class]')).filter(i => i.checked).map(i => i.dataset.class);
        const classes = [];
        checked.forEach(c => {
            if (c === 'bg-white') classes.push('bg-white', 'dark:bg-slate-900');
            if (c === 'border') classes.push('border', 'border-slate-300', 'dark:border-white/10');
            if (c === 'rounded-xl') classes.push('rounded-xl');
            if (c === 'p-6') classes.push('p-6');
            if (c === 'shadow-md') classes.push('shadow-md');
            if (c === 'text-center') classes.push('text-center');
        });
        preview.className = `transition-all duration-300 max-w-xs ${classes.join(' ')}`;
        code.innerHTML = `<code><span class="tag">&lt;article</span> <span class="attr">class</span>=<span class="str">"${checked.join(' ')}"</span><span class="tag">&gt;</span>
  <span class="tag">&lt;h3&gt;</span>Produk Pilihan<span class="tag">&lt;/h3&gt;</span>
  <span class="tag">&lt;p&gt;</span>Sepatu ringan untuk harian.<span class="tag">&lt;/p&gt;</span>
<span class="tag">&lt;/article&gt;</span></code>`;
    }

    function setScenario(type) {
        const box = document.getElementById('scenario-box');
        const data = {
            prototype: ['Cocok digunakan', 'Utility class cocok untuk menyusun tampilan kartu produk sederhana karena perubahan dapat dilakukan langsung pada elemen yang sedang dibuat.', 'border-emerald-300 dark:border-emerald-500/40'],
            repeat: ['Perlu dirapikan', 'Jika tombol yang sama dipakai berulang pada banyak halaman, class dapat dipindahkan ke komponen Blade agar tidak ditulis berkali-kali.', 'border-orange-300 dark:border-orange-500/40'],
            random: ['Tidak disarankan', 'Menulis banyak class tanpa memahami fungsinya membuat kode sulit dibaca. Utility class tetap harus dipilih sesuai kebutuhan tampilan.', 'border-red-300 dark:border-red-500/40']
        }[type];
        box.className = `max-w-sm bg-white dark:bg-slate-900 border ${data[2]} rounded-2xl p-6 shadow-sm transition-all duration-300`;
        box.innerHTML = `<h3 class="font-black text-slate-900 dark:text-white mb-2">${data[0]}</h3><p class="text-sm text-slate-600 dark:text-slate-300">${data[1]}</p>`;
    }

    function chooseActivity(btn, q, ans) {
        if (activityCompleted) return;
        activityAnswers[q] = ans;
        const group = btn.closest('.activity-question');
        group.querySelectorAll('.activity-option').forEach(b => {
            b.classList.remove('bg-indigo-600', 'text-white', 'border-indigo-500');
        });
        btn.classList.add('bg-indigo-600', 'text-white', 'border-indigo-500');
    }

    function initTailwindClassActivity() {
        activityWidget = CourseActivityKit.mountChoiceBuilderActivity({
            root: '#activityForm',
            badge: 'Class Composer',
            title: 'Rancang kartu produk dengan utility class',
            description: 'Pilih chip utility yang paling sesuai untuk membentuk kartu produk yang rapi!',
            previewLabel: 'Preview Kartu',
            minScore: 4,
            groups: [
                {
                    id: 'surface',
                    label: 'Permukaan kartu',
                    desc: 'Kartu perlu kontras dari latar halaman.',
                    correct: 'card',
                    default: 'plain',
                    options: [
                        { id: 'plain', label: 'Tanpa permukaan', classText: 'border', desc: 'Kartu masih terlalu datar.' },
                        { id: 'card', label: 'Kartu putih dengan border', classText: 'bg-white border border-slate-200', desc: 'Permukaan kartu terlihat jelas.', color: '#ffffff' },
                        { id: 'dark', label: 'Kartu gelap', classText: 'bg-slate-900 text-white', desc: 'Kontras kuat tetapi kurang sesuai dengan contoh materi.', color: '#0f172a' }
                    ]
                },
                {
                    id: 'spacing',
                    label: 'Ruang dalam kartu',
                    desc: 'Isi kartu tidak boleh menempel pada tepi.',
                    correct: 'roomy',
                    default: 'tight',
                    options: [
                        { id: 'tight', label: 'Terlalu rapat', classText: 'p-2' },
                        { id: 'medium', label: 'Sedang', classText: 'p-4' },
                        { id: 'roomy', label: 'Nyaman', classText: 'p-6' }
                    ]
                },
                {
                    id: 'shape',
                    label: 'Sudut kartu',
                    desc: 'Kartu produk perlu terlihat lembut.',
                    correct: 'soft',
                    default: 'sharp',
                    options: [
                        { id: 'sharp', label: 'Tajam', classText: 'rounded-none' },
                        { id: 'soft', label: 'Melengkung', classText: 'rounded-xl' },
                        { id: 'pill', label: 'Sangat bulat', classText: 'rounded-full' }
                    ]
                },
                {
                    id: 'depth',
                    label: 'Kedalaman visual',
                    desc: 'Kartu perlu sedikit terangkat dari latar.',
                    correct: 'shadow',
                    default: 'flat',
                    options: [
                        { id: 'flat', label: 'Tanpa bayangan', classText: 'shadow-none' },
                        { id: 'shadow', label: 'Bayangan sedang', classText: 'shadow-md' },
                        { id: 'heavy', label: 'Bayangan berat', classText: 'shadow-2xl' }
                    ]
                },
                {
                    id: 'button',
                    label: 'Tombol utama',
                    desc: 'Aksi utama butuh warna kuat dan teks kontras.',
                    correct: 'primary',
                    default: 'muted',
                    options: [
                        { id: 'muted', label: 'Tombol sekunder', classText: 'bg-slate-100 text-slate-700', color: '#f1f5f9' },
                        { id: 'primary', label: 'Tombol utama', classText: 'bg-blue-600 text-white', color: '#2563eb' },
                        { id: 'warning', label: 'Tombol peringatan', classText: 'bg-amber-400 text-slate-950', color: '#fbbf24' }
                    ]
                }
            ],
            renderPreview: (state, selected) => `
                <section class="w-full min-h-[300px] bg-slate-100 p-6 grid place-items-center">
                    <article class="max-w-sm ${selected.surface.classText} ${selected.spacing.classText} ${selected.shape.classText} ${selected.depth.classText}">
                        <p class="text-sm text-slate-500">Produk Pilihan</p>
                        <h1 class="mt-1 text-2xl font-bold text-slate-900">Sepatu Harian</h1>
                        <p class="mt-3 text-slate-600">Ringan, nyaman, dan cocok digunakan untuk aktivitas harian.</p>
                        <button class="mt-5 px-4 py-3 rounded-lg font-bold ${selected.button.classText}">Simpan Data</button>
                    </article>
                </section>
            `
        });
    }

    async function checkActivity() {
        if (activityCompleted) return;
        const status = document.getElementById('activity-status');
        const scoreLabel = document.getElementById('activity-score');
        const submit = document.getElementById('submitBtn');
        const result = activityWidget?.check();
        if (!result) return;

        const percent = Math.round((result.score / result.total) * 100);
        scoreLabel.innerText = `Skor: ${result.score}/${result.total} (${percent}%)`;
        document.getElementById('activity-analysis').classList.toggle('hidden', !result.passed);

        if (result.passed) {
            status.innerText = 'Aktivitas berhasil. Utility class sudah membentuk tampilan kartu sesuai kebutuhan.';
            status.className = 'text-xs font-bold text-emerald-600 dark:text-emerald-400';
            await saveLessonToDB(ACTIVITY_LESSON_ID);
            activityCompleted = true;
            lockActivityUI(false);
            unlockNextChapter();
        } else {
            status.innerText = 'Skor belum cukup. Ubah pilihan chip utility dan amati kembali preview.';
            status.className = 'text-xs font-bold text-orange-600 dark:text-orange-400';
            submit.classList.add('shake');
            setTimeout(() => submit.classList.remove('shake'), 500);
        }
    }

    function lockActivityUI(showOverlay = true) {
        if (showOverlay) {
            const overlay = document.getElementById('lockOverlay');
            overlay.classList.remove('hidden');
            overlay.classList.add('flex');
        }

        const submit = document.getElementById('submitBtn');
        submit.innerText = 'Aktivitas Selesai';
        submit.disabled = true;
        submit.classList.remove('from-indigo-600', 'to-purple-600');
        submit.classList.add('bg-slate-400', 'dark:bg-slate-700', 'cursor-not-allowed', 'shadow-none');

        document.querySelectorAll('#activityForm button').forEach(b => {
            b.disabled = true;
            b.classList.add('cursor-not-allowed');
        });
        if (activityWidget) activityWidget.lock();
    }

    function unlockNextChapter() {
        const btn = document.getElementById('nextChapterBtn');
        if(btn) {
            btn.classList.remove('cursor-not-allowed', 'opacity-50', 'pointer-events-none', 'text-muted');
            btn.classList.add('text-indigo-600', 'dark:text-indigo-400', 'cursor-pointer');

            const nextLabel = document.getElementById('nextLabel');
            nextLabel.innerText = 'Berikutnya';
            nextLabel.classList.remove('opacity-60');
            nextLabel.classList.add('text-indigo-600', 'dark:text-indigo-400', 'opacity-100');

            const icon = document.getElementById('nextIcon');
            icon.innerHTML = `<svg class="w-4 h-4 md:w-5 md:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>`;
            icon.classList.remove('bg-slate-100', 'dark:bg-white/5');
            icon.classList.add('bg-indigo-100', 'dark:bg-indigo-500/20', 'border-indigo-300', 'dark:border-indigo-500/50', 'text-indigo-600', 'dark:text-indigo-400', 'shadow-lg');

            btn.onclick = () => window.location.href = "{{ route('courses.latarbelakang') ?? '#' }}";
        }
    }

    function initScrollSpy() {
        const main = document.getElementById('mainScroll');
        if (!main) return;
        const sections = document.querySelectorAll('.lesson-section');

        main.addEventListener('scroll', () => {
            let current = '';
            sections.forEach(sec => {
                const top = sec.getBoundingClientRect().top;
                if (top < 240) current = '#' + sec.id;
            });

            document.querySelectorAll('.nav-item').forEach(item => {
                item.classList.remove('active');
                if (item.getAttribute('data-target') === current) item.classList.add('active');
            });
        }, { passive: true });
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
