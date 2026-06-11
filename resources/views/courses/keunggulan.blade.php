@extends('layouts.landing')
@section('title','Konfigurasi Dasar Tailwind CSS')

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
        --accent: #06b6d4;
        --accent-glow: rgba(6, 182, 212, 0.32);
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
        --accent-glow: rgba(6, 182, 212, 0.48);
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
        background-color: rgba(6, 182, 212, 0.14);
        color: #0891b2;
        padding: .125rem .375rem;
        border-radius: .375rem;
        font-weight: 700;
        border: 1px solid rgba(6, 182, 212, .26);
        white-space: nowrap;
    }
    .dark .hl-term { color: #67e8f9; background-color: rgba(6,182,212,.20); border-color: rgba(6,182,212,.38); }

    .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(148,163,184,.38); border-radius: 999px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #06b6d4; }

    #animated-bg {
        background:
            radial-gradient(600px circle at 18% 16%, rgba(6,182,212,.12), transparent 40%),
            radial-gradient(700px circle at 85% 25%, rgba(99,102,241,.10), transparent 42%),
            radial-gradient(800px circle at 48% 88%, rgba(14,165,233,.10), transparent 44%);
        animation: bgMove 20s ease-in-out infinite alternate;
    }
    .dark #animated-bg {
        background:
            radial-gradient(600px circle at 18% 16%, rgba(6,182,212,.18), transparent 40%),
            radial-gradient(700px circle at 85% 25%, rgba(99,102,241,.14), transparent 42%),
            radial-gradient(800px circle at 48% 88%, rgba(14,165,233,.16), transparent 44%);
    }
    @keyframes bgMove { to { transform: scale(1.12); } }
    @keyframes shake { 0%,100%{transform:translateX(0)} 25%{transform:translateX(-5px)} 75%{transform:translateX(5px)} }
    .shake { animation: shake .4s ease-in-out; }

    .nav-item { display:flex; width:100%; text-align:left; align-items:center; gap:12px; padding:10px 14px; font-size:.85rem; color:var(--text-muted); border-radius:8px; transition:all .2s; }
    .nav-item:hover { color:var(--text-main); background:var(--card-hover); }
    .nav-item.active { color:#06b6d4; background:rgba(6,182,212,.08); font-weight:700; }
    .dot { width:6px; height:6px; border-radius:50%; background:#94a3b8; transition:all .3s; }
    .dark .dot { background:#475569; }
    .nav-item.active .dot { background:#06b6d4; box-shadow:0 0 8px #06b6d4; transform:scale(1.2); }

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
    .flow-card.active, .token-btn.active, .scenario-btn.active { border-color: rgba(6,182,212,.65) !important; background: rgba(6,182,212,.10) !important; }
</style>

<div id="courseRoot" class="relative h-screen bg-adaptive text-adaptive font-sans overflow-hidden flex flex-col selection:bg-cyan-500/30 pt-20 transition-colors duration-500">

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
                    <div class="w-8 h-8 rounded-lg bg-cyan-500/10 dark:bg-cyan-500/20 border border-cyan-500/20 flex items-center justify-center font-bold text-xs text-cyan-600 dark:text-cyan-400 shrink-0">1.5</div>
                    <div class="min-w-0">
                        <h1 class="text-sm font-bold text-heading line-clamp-1">Konfigurasi Dasar Tailwind CSS</h1>
                        <p class="text-[10px] text-muted line-clamp-1">@import, @theme, build, dan class hasil konfigurasi</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <div class="hidden sm:block w-24 md:w-32 h-1.5 bg-slate-200 dark:bg-white/10 rounded-full overflow-hidden">
                        <div id="topProgressBar" class="h-full bg-gradient-to-r from-cyan-500 to-blue-500 w-0 transition-all duration-500 shadow-[0_0_10px_rgba(6,182,212,0.5)]"></div>
                    </div>
                    <span id="progressLabelTop" class="text-cyan-600 dark:text-cyan-400 font-bold text-xs">0%</span>
                </div>
            </div>

            <div class="p-4 sm:p-6 lg:p-16 max-w-5xl mx-auto pb-40">

                <div class="mb-16 md:mb-24">
                    <div class="card-adaptive rounded-3xl border p-6 md:p-8 overflow-hidden relative">
                        <div class="absolute -right-10 -top-10 w-40 h-40 bg-cyan-500/10 rounded-full blur-3xl"></div>
                        <p class="text-xs font-mono font-bold text-cyan-600 dark:text-cyan-400 uppercase tracking-[.25em] mb-3">Subbab 1.5</p>
                        <h2 class="text-3xl md:text-5xl font-black text-heading leading-tight mb-4">Konfigurasi Dasar Tailwind CSS</h2>
                        <p class="text-sm md:text-base text-muted leading-relaxed max-w-3xl text-justify">
                            Pada subbab ini, pembelajar mempelajari cara menambahkan nilai desain khusus ke dalam Tailwind CSS. Materi dimulai dari file <code>input.css</code>, penggunaan <code>@import</code>, penulisan <code>@theme</code>, proses build, sampai penggunaan class hasil konfigurasi pada HTML.
                        </p>
                    </div>

                    <h3 class="text-xl font-bold text-heading mt-10 mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-cyan-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Tujuan Pembelajaran
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="card-adaptive border rounded-xl p-5">
                            <div class="w-8 h-8 rounded-lg bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 flex items-center justify-center font-bold mb-4">1</div>
                            <h4 class="text-sm font-bold text-heading mb-1">Menjelaskan input.css</h4>
                            <p class="text-[11px] text-muted leading-relaxed">Membedakan fungsi <code>input.css</code> sebagai sumber dan <code>output.css</code> sebagai hasil build.</p>
                        </div>
                        <div class="card-adaptive border rounded-xl p-5">
                            <div class="w-8 h-8 rounded-lg bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold mb-4">2</div>
                            <h4 class="text-sm font-bold text-heading mb-1">Menggunakan @import</h4>
                            <p class="text-[11px] text-muted leading-relaxed">Menjelaskan peran <code>@import "tailwindcss";</code> dalam proses build Tailwind.</p>
                        </div>
                        <div class="card-adaptive border rounded-xl p-5">
                            <div class="w-8 h-8 rounded-lg bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold mb-4">3</div>
                            <h4 class="text-sm font-bold text-heading mb-1">Membuat @theme</h4>
                            <p class="text-[11px] text-muted leading-relaxed">Menambahkan warna, font, radius, dan bayangan kustom sebagai token desain.</p>
                        </div>
                        <div class="card-adaptive border rounded-xl p-5">
                            <div class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold mb-4">4</div>
                            <h4 class="text-sm font-bold text-heading mb-1">Menerapkan Class</h4>
                            <p class="text-[11px] text-muted leading-relaxed">Menggunakan class hasil konfigurasi seperti <code>bg-sekolah-500</code> dan <code>rounded-kartu</code>.</p>
                        </div>
                    </div>
                </div>

                <article class="space-y-32 md:space-y-40">

                    <section id="section-21" class="lesson-section scroll-mt-32" data-lesson-id="21">
                        <div class="space-y-8">
                            <div class="space-y-4 border-l-4 border-cyan-500 pl-4 md:pl-6">
                                <span class="text-cyan-600 dark:text-cyan-400 font-mono text-xs uppercase tracking-widest">Lesson 1.5.1</span>
                                <h2 class="text-3xl md:text-5xl font-black text-heading leading-tight">Membuat Konfigurasi <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-500 to-blue-600">pada input.css</span></h2>
                            </div>

                            <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-90 text-sm md:text-base leading-relaxed space-y-4 text-justify">
                                <p>File <code>input.css</code> digunakan sebagai tempat menulis sumber CSS Tailwind. Pada file ini, Tailwind dipanggil menggunakan <code>@import</code>. Jika proyek membutuhkan nilai desain tambahan, nilai tersebut dapat ditulis menggunakan <code>@theme</code>.</p>
                                <p>Konfigurasi dasar tidak selalu wajib dibuat. Jika nilai bawaan Tailwind sudah cukup, pengembang dapat langsung memakai class bawaan. Konfigurasi diperlukan ketika proyek membutuhkan nilai khusus, misalnya warna utama bernama sekolah, radius khusus untuk kartu, atau shadow khusus untuk komponen.</p>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                <div class="code-adaptive border border-adaptive rounded-2xl overflow-hidden">
                                    <div class="px-4 py-3 border-b border-adaptive flex items-center justify-between">
                                        <span class="font-mono text-xs font-bold text-muted">src/input.css</span>
                                        <span class="text-[10px] uppercase tracking-widest text-cyan-500 font-bold">Konfigurasi</span>
                                    </div>
                                    <pre class="p-5 overflow-auto custom-scrollbar text-xs md:text-sm font-mono leading-relaxed"><code><span class="code-line"><span class="prop">@import</span> <span class="str">"tailwindcss"</span>;</span>
<span class="code-line"></span>
<span class="code-line"><span class="prop">@theme</span> {</span>
<span class="code-line">  <span class="prop">--color-sekolah-500</span>: <span class="str">#2563eb</span>;</span>
<span class="code-line">  <span class="prop">--font-display</span>: <span class="str">"Inter", sans-serif</span>;</span>
<span class="code-line">  <span class="prop">--radius-kartu</span>: <span class="str">1rem</span>;</span>
<span class="code-line">  <span class="prop">--shadow-kartu</span>: <span class="str">0 10px 25px rgba(15, 23, 42, 0.15)</span>;</span>
<span class="code-line">}</span></code></pre>
                                </div>

                                <div class="card-adaptive border rounded-2xl p-5 flex flex-col justify-center">
                                    <h3 class="font-bold text-heading mb-4">Cara membaca konfigurasi</h3>
                                    <div class="space-y-3 text-sm">
                                        <div class="flex gap-3"><div class="w-8 h-8 rounded-lg bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 flex items-center justify-center font-bold shrink-0">1</div><p class="text-muted leading-relaxed"><code>@import</code> memanggil sistem Tailwind agar dapat diproses oleh CLI.</p></div>
                                        <div class="flex gap-3"><div class="w-8 h-8 rounded-lg bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold shrink-0">2</div><p class="text-muted leading-relaxed"><code>@theme</code> menyimpan nilai desain proyek yang ingin dipakai berulang.</p></div>
                                        <div class="flex gap-3"><div class="w-8 h-8 rounded-lg bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold shrink-0">3</div><p class="text-muted leading-relaxed">Nama token akan menjadi dasar terbentuknya utility class baru.</p></div>
                                    </div>
                                </div>
                            </div>

                            <div class="sim-bg-adaptive border border-adaptive rounded-2xl overflow-hidden shadow-xl">
                                <div class="bg-cyan-600/95 dark:bg-cyan-900/95 text-white p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3 border-b border-cyan-400/50">
                                    <div class="text-xs font-bold uppercase tracking-widest">Simulasi 1 — Alur File Konfigurasi</div>
                                    <p class="text-[11px] opacity-90 leading-relaxed m-0 md:text-right max-w-xl">Pilih tahap untuk melihat hubungan <code>input.css</code>, proses build, <code>output.css</code>, dan HTML.</p>
                                </div>
                                <div class="grid grid-cols-1 lg:grid-cols-2 min-h-[360px]">
                                    <div class="code-adaptive border-b lg:border-b-0 lg:border-r border-adaptive p-5">
                                        <div class="grid grid-cols-2 gap-3">
                                            <button id="flow-input" onclick="showFlow('input')" class="flow-card border border-adaptive rounded-xl p-4 text-left bg-white dark:bg-black/20 hover:border-cyan-500 transition"><b class="text-xs text-cyan-600 dark:text-cyan-400">1. input.css</b><p class="text-[11px] text-muted mt-1">Sumber konfigurasi.</p></button>
                                            <button id="flow-build" onclick="showFlow('build')" class="flow-card border border-adaptive rounded-xl p-4 text-left bg-white dark:bg-black/20 hover:border-cyan-500 transition"><b class="text-xs text-blue-600 dark:text-blue-400">2. Build CLI</b><p class="text-[11px] text-muted mt-1">Memproses sumber.</p></button>
                                            <button id="flow-output" onclick="showFlow('output')" class="flow-card border border-adaptive rounded-xl p-4 text-left bg-white dark:bg-black/20 hover:border-cyan-500 transition"><b class="text-xs text-indigo-600 dark:text-indigo-400">3. output.css</b><p class="text-[11px] text-muted mt-1">Hasil build.</p></button>
                                            <button id="flow-html" onclick="showFlow('html')" class="flow-card border border-adaptive rounded-xl p-4 text-left bg-white dark:bg-black/20 hover:border-cyan-500 transition"><b class="text-xs text-emerald-600 dark:text-emerald-400">4. HTML</b><p class="text-[11px] text-muted mt-1">Memakai hasil.</p></button>
                                        </div>
                                    </div>
                                    <div class="bg-white dark:bg-slate-950 flex flex-col">
                                        <div class="px-4 py-3 border-b border-adaptive bg-slate-50 dark:bg-black/20"><span class="text-[10px] font-mono font-bold text-muted uppercase tracking-widest">Penjelasan Tahap</span></div>
                                        <div id="flowPreview" class="flex-1 p-5 md:p-6 flex items-center justify-center">
                                            <div class="text-sm text-muted">Pilih tahap di sebelah kiri.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gradient-to-r from-cyan-50 to-transparent dark:from-cyan-900/20 dark:to-transparent border-l-4 border-cyan-500 p-4 rounded-r-xl">
                                <h5 class="text-xs font-bold text-cyan-700 dark:text-cyan-400 mb-1">Kesimpulan</h5>
                                <p class="text-[11px] text-slate-700 dark:text-slate-300 leading-relaxed m-0 text-justify"><code>input.css</code> adalah file sumber. Browser tidak memakai file ini secara langsung. Browser membaca <code>output.css</code> yang sudah diproses oleh Tailwind CLI.</p>
                            </div>
                        </div>
                    </section>

                    <section id="section-22" class="lesson-section scroll-mt-32" data-lesson-id="22">
                        <div class="space-y-8">
                            <div class="space-y-4 border-l-4 border-blue-500 pl-4 md:pl-6">
                                <span class="text-blue-600 dark:text-blue-400 font-mono text-xs uppercase tracking-widest">Lesson 1.5.2</span>
                                <h2 class="text-3xl md:text-5xl font-black text-heading leading-tight">Memahami <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-500 to-indigo-600">@theme</span></h2>
                            </div>

                            <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-90 text-sm md:text-base leading-relaxed space-y-4 text-justify">
                                <p><code>@theme</code> digunakan untuk menambahkan nilai desain proyek. Nilai ini disebut token desain. Token dapat berupa warna, jenis huruf, radius, atau bayangan. Setelah token ditulis dan diproses, Tailwind dapat menghasilkan class baru berdasarkan nama token tersebut.</p>
                                <p>Nilai di dalam <code>@theme</code> ditulis dengan pola tertentu. Variabel <code>--color-*</code> digunakan untuk warna, <code>--font-*</code> untuk jenis huruf, <code>--radius-*</code> untuk radius, dan <code>--shadow-*</code> untuk bayangan.</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div class="card-adaptive border rounded-xl p-5"><h3 class="font-bold text-heading text-sm mb-2">--color-*</h3><p class="text-xs text-muted leading-relaxed">Menghasilkan class warna seperti <code>bg-</code>, <code>text-</code>, dan <code>border-</code>.</p></div>
                                <div class="card-adaptive border rounded-xl p-5"><h3 class="font-bold text-heading text-sm mb-2">--font-*</h3><p class="text-xs text-muted leading-relaxed">Menghasilkan class font, misalnya <code>font-display</code>.</p></div>
                                <div class="card-adaptive border rounded-xl p-5"><h3 class="font-bold text-heading text-sm mb-2">--radius-*</h3><p class="text-xs text-muted leading-relaxed">Menghasilkan class sudut, misalnya <code>rounded-kartu</code>.</p></div>
                                <div class="card-adaptive border rounded-xl p-5"><h3 class="font-bold text-heading text-sm mb-2">--shadow-*</h3><p class="text-xs text-muted leading-relaxed">Menghasilkan class bayangan, misalnya <code>shadow-kartu</code>.</p></div>
                            </div>

                            <div class="sim-bg-adaptive border border-adaptive rounded-2xl overflow-hidden shadow-xl">
                                <div class="bg-blue-600/95 dark:bg-blue-900/95 text-white p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3 border-b border-blue-400/50">
                                    <div class="text-xs font-bold uppercase tracking-widest">Simulasi 2 — Token Tema menjadi Class</div>
                                    <p class="text-[11px] opacity-90 leading-relaxed m-0 md:text-right max-w-xl">Pilih token untuk melihat class Tailwind yang dapat terbentuk dari konfigurasi tersebut.</p>
                                </div>
                                <div class="grid grid-cols-1 lg:grid-cols-2 min-h-[360px]">
                                    <div class="code-adaptive border-b lg:border-b-0 lg:border-r border-adaptive p-5">
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                            <button id="token-color" onclick="showToken('color')" class="token-btn border border-adaptive rounded-xl p-4 text-left bg-white dark:bg-black/20 hover:border-blue-500 transition"><b class="text-xs text-blue-600 dark:text-blue-400">--color-sekolah-500</b><p class="text-[11px] text-muted mt-1">Token warna.</p></button>
                                            <button id="token-font" onclick="showToken('font')" class="token-btn border border-adaptive rounded-xl p-4 text-left bg-white dark:bg-black/20 hover:border-blue-500 transition"><b class="text-xs text-blue-600 dark:text-blue-400">--font-display</b><p class="text-[11px] text-muted mt-1">Token font.</p></button>
                                            <button id="token-radius" onclick="showToken('radius')" class="token-btn border border-adaptive rounded-xl p-4 text-left bg-white dark:bg-black/20 hover:border-blue-500 transition"><b class="text-xs text-blue-600 dark:text-blue-400">--radius-kartu</b><p class="text-[11px] text-muted mt-1">Token radius.</p></button>
                                            <button id="token-shadow" onclick="showToken('shadow')" class="token-btn border border-adaptive rounded-xl p-4 text-left bg-white dark:bg-black/20 hover:border-blue-500 transition"><b class="text-xs text-blue-600 dark:text-blue-400">--shadow-kartu</b><p class="text-[11px] text-muted mt-1">Token bayangan.</p></button>
                                        </div>
                                    </div>
                                    <div class="bg-white dark:bg-slate-950 flex flex-col">
                                        <div class="px-4 py-3 border-b border-adaptive bg-slate-50 dark:bg-black/20"><span class="text-[10px] font-mono font-bold text-muted uppercase tracking-widest">Class yang Dihasilkan</span></div>
                                        <div id="tokenPreview" class="flex-1 p-5 md:p-6 flex items-center justify-center"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gradient-to-r from-blue-50 to-transparent dark:from-blue-900/20 dark:to-transparent border-l-4 border-blue-500 p-4 rounded-r-xl">
                                <h5 class="text-xs font-bold text-blue-700 dark:text-blue-400 mb-1">Kesimpulan</h5>
                                <p class="text-[11px] text-slate-700 dark:text-slate-300 leading-relaxed m-0 text-justify">Nama token menentukan nama class. Karena itu, penamaan token perlu dibuat jelas, singkat, dan sesuai kebutuhan proyek.</p>
                            </div>
                        </div>
                    </section>

                    <section id="section-23" class="lesson-section scroll-mt-32" data-lesson-id="23">
                        <div class="space-y-8">
                            <div class="space-y-4 border-l-4 border-indigo-500 pl-4 md:pl-6">
                                <span class="text-indigo-600 dark:text-indigo-400 font-mono text-xs uppercase tracking-widest">Lesson 1.5.3</span>
                                <h2 class="text-3xl md:text-5xl font-black text-heading leading-tight">Proses Build <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-cyan-600">Konfigurasi</span></h2>
                            </div>

                            <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-90 text-sm md:text-base leading-relaxed space-y-4 text-justify">
                                <p>Setelah <code>input.css</code> diubah, konfigurasi perlu diproses agar masuk ke <code>output.css</code>. File <code>output.css</code> adalah file hasil yang dihubungkan ke HTML. Jika <code>output.css</code> belum diperbarui, class hasil konfigurasi belum tentu bekerja di browser.</p>
                                <p>Perintah build membaca <code>input.css</code>, memproses nilai Tailwind, lalu membuat atau memperbarui <code>output.css</code>. Opsi <code>--watch</code> membuat proses tetap berjalan dan memantau perubahan selama pengembangan.</p>
                            </div>

                            <div class="code-adaptive border border-adaptive rounded-2xl overflow-hidden">
                                <div class="px-4 py-3 border-b border-adaptive flex items-center justify-between">
                                    <span class="font-mono text-xs font-bold text-muted">Terminal</span>
                                    <span class="text-[10px] uppercase tracking-widest text-indigo-500 font-bold">Build Command</span>
                                </div>
                                <pre class="p-5 overflow-auto custom-scrollbar text-xs md:text-sm font-mono leading-relaxed"><code>npx @tailwindcss/cli -i ./src/input.css -o ./src/output.css --watch</code></pre>
                            </div>

                            <div class="sim-bg-adaptive border border-adaptive rounded-2xl overflow-hidden shadow-xl">
                                <div class="bg-indigo-600/95 dark:bg-indigo-900/95 text-white p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3 border-b border-indigo-400/50">
                                    <div class="text-xs font-bold uppercase tracking-widest">Simulasi 3 — Jalankan Build</div>
                                    <p class="text-[11px] opacity-90 leading-relaxed m-0 md:text-right max-w-xl">Klik tombol proses untuk melihat tahapan dari konfigurasi sampai output CSS terbentuk.</p>
                                </div>
                                <div class="grid grid-cols-1 lg:grid-cols-2 min-h-[360px]">
                                    <div class="code-adaptive border-b lg:border-b-0 lg:border-r border-adaptive p-5">
                                        <div class="flex flex-wrap gap-2 mb-4">
                                            <button onclick="runBuildSim(1)" class="px-3 py-2 rounded-lg bg-indigo-600 text-white text-xs font-bold">1. Baca input.css</button>
                                            <button onclick="runBuildSim(2)" class="px-3 py-2 rounded-lg bg-cyan-600 text-white text-xs font-bold">2. Proses tema</button>
                                            <button onclick="runBuildSim(3)" class="px-3 py-2 rounded-lg bg-emerald-600 text-white text-xs font-bold">3. Buat output.css</button>
                                            <button onclick="runBuildSim(0)" class="px-3 py-2 rounded-lg bg-slate-200 dark:bg-white/10 text-slate-700 dark:text-slate-200 text-xs font-bold border border-adaptive">Reset</button>
                                        </div>
                                        <div class="bg-slate-950 text-slate-100 rounded-xl border border-slate-800 p-4 font-mono text-[11px] sm:text-xs min-h-[220px] overflow-auto custom-scrollbar shadow-inner">
                                            <div class="text-slate-500 mb-2">Terminal</div>
                                            <div id="build-terminal" class="space-y-2"><div class="text-slate-400">$ Menunggu perintah build...</div></div>
                                        </div>
                                    </div>
                                    <div class="bg-white dark:bg-slate-950 flex flex-col">
                                        <div class="px-4 py-3 border-b border-adaptive bg-slate-50 dark:bg-black/20"><span class="text-[10px] font-mono font-bold text-muted uppercase tracking-widest">Status File</span></div>
                                        <div class="flex-1 p-6 flex items-center justify-center">
                                            <div class="w-full max-w-sm space-y-3">
                                                <div id="build-step-1" class="card-adaptive border border-adaptive rounded-xl p-4"><h4 class="font-bold text-heading text-sm">input.css dibaca</h4><p class="text-xs text-muted mt-1">Tailwind menemukan <code>@import</code> dan <code>@theme</code>.</p></div>
                                                <div id="build-step-2" class="card-adaptive border border-adaptive rounded-xl p-4"><h4 class="font-bold text-heading text-sm">Tema diproses</h4><p class="text-xs text-muted mt-1">Token berubah menjadi class yang dapat digunakan.</p></div>
                                                <div id="build-step-3" class="card-adaptive border border-adaptive rounded-xl p-4"><h4 class="font-bold text-heading text-sm">output.css diperbarui</h4><p class="text-xs text-muted mt-1">HTML dapat menghubungkan file hasil ini.</p></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gradient-to-r from-indigo-50 to-transparent dark:from-indigo-900/20 dark:to-transparent border-l-4 border-indigo-500 p-4 rounded-r-xl">
                                <h5 class="text-xs font-bold text-indigo-700 dark:text-indigo-400 mb-1">Kesimpulan</h5>
                                <p class="text-[11px] text-slate-700 dark:text-slate-300 leading-relaxed m-0 text-justify">Perubahan konfigurasi belum cukup hanya ditulis. Konfigurasi perlu diproses melalui build agar masuk ke <code>output.css</code>.</p>
                            </div>
                        </div>
                    </section>

                    <section id="section-24" class="lesson-section scroll-mt-32" data-lesson-id="24">
                        <div class="space-y-8">
                            <div class="space-y-4 border-l-4 border-emerald-500 pl-4 md:pl-6">
                                <span class="text-emerald-600 dark:text-emerald-400 font-mono text-xs uppercase tracking-widest">Lesson 1.5.4</span>
                                <h2 class="text-3xl md:text-5xl font-black text-heading leading-tight">Menggunakan Hasil <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-500 to-cyan-600">Konfigurasi</span></h2>
                            </div>

                            <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-90 text-sm md:text-base leading-relaxed space-y-4 text-justify">
                                <p>Setelah konfigurasi diproses, nilai tersebut dapat digunakan pada dokumen HTML dalam bentuk class. Cara penggunaannya sama seperti class Tailwind lainnya, tetapi class tersebut berasal dari nilai yang ditambahkan sendiri pada <code>@theme</code>.</p>
                                <p>Nilai konfigurasi dapat dipakai berulang pada beberapa elemen. Hal ini membuat tampilan lebih konsisten karena warna, sudut, bayangan, dan jenis huruf berasal dari satu sumber konfigurasi yang sama.</p>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                <div class="code-adaptive border border-adaptive rounded-2xl overflow-hidden">
                                    <div class="px-4 py-3 border-b border-adaptive flex items-center justify-between"><span class="font-mono text-xs font-bold text-muted">index.html</span><span class="text-[10px] uppercase tracking-widest text-emerald-500 font-bold">Class Custom</span></div>
                                    <pre class="p-5 overflow-auto custom-scrollbar text-xs md:text-sm font-mono leading-relaxed"><code>&lt;article class=&quot;bg-sekolah-500 text-white
               rounded-kartu shadow-kartu p-6&quot;&gt;
  &lt;h2 class=&quot;font-display text-2xl font-bold&quot;&gt;
    Profil Modul
  &lt;/h2&gt;
  &lt;p&gt;Class berasal dari konfigurasi tema.&lt;/p&gt;
&lt;/article&gt;</code></pre>
                                </div>
                                <div class="card-adaptive border rounded-2xl p-5 flex items-center justify-center">
                                    <article class="text-white p-6 max-w-sm w-full transition-all duration-300" style="background:#2563eb;border-radius:1rem;box-shadow:0 10px 25px rgba(15,23,42,.18);">
                                        <p class="text-xs uppercase tracking-widest font-bold opacity-80 mb-2">Preview</p>
                                        <h3 class="text-2xl font-black">Profil Modul</h3>
                                        <p class="text-sm mt-2 opacity-90 leading-relaxed">Class <code>bg-sekolah-500</code>, <code>rounded-kartu</code>, dan <code>shadow-kartu</code> dapat dipakai setelah konfigurasi diproses.</p>
                                    </article>
                                </div>
                            </div>

                            <div class="sim-bg-adaptive border border-adaptive rounded-2xl overflow-hidden shadow-xl">
                                <div class="bg-emerald-600/95 dark:bg-emerald-900/95 text-white p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3 border-b border-emerald-400/50">
                                    <div class="text-xs font-bold uppercase tracking-widest">Simulasi 4 — Cek Skenario Konfigurasi</div>
                                    <p class="text-[11px] opacity-90 leading-relaxed m-0 md:text-right max-w-xl">Pilih skenario untuk melihat apakah class hasil konfigurasi akan bekerja di browser.</p>
                                </div>
                                <div class="grid grid-cols-1 lg:grid-cols-2 min-h-[340px]">
                                    <div class="code-adaptive border-b lg:border-b-0 lg:border-r border-adaptive p-5 space-y-3">
                                        <button id="scenario-wronglink" onclick="checkScenario('wronglink')" class="scenario-btn w-full border border-adaptive rounded-xl p-4 text-left bg-white dark:bg-black/20 hover:border-rose-500 transition"><b class="text-xs text-rose-600 dark:text-rose-400">HTML menghubungkan input.css</b><p class="text-[11px] text-muted mt-1">File sumber belum diproses.</p></button>
                                        <button id="scenario-nobuild" onclick="checkScenario('nobuild')" class="scenario-btn w-full border border-adaptive rounded-xl p-4 text-left bg-white dark:bg-black/20 hover:border-amber-500 transition"><b class="text-xs text-amber-600 dark:text-amber-400">@theme diubah, build belum dijalankan</b><p class="text-[11px] text-muted mt-1">Output masih versi lama.</p></button>
                                        <button id="scenario-valid" onclick="checkScenario('valid')" class="scenario-btn w-full border border-adaptive rounded-xl p-4 text-left bg-white dark:bg-black/20 hover:border-emerald-500 transition"><b class="text-xs text-emerald-600 dark:text-emerald-400">HTML menghubungkan output.css setelah build</b><p class="text-[11px] text-muted mt-1">Alur sudah benar.</p></button>
                                    </div>
                                    <div class="bg-white dark:bg-slate-950 flex flex-col">
                                        <div class="px-4 py-3 border-b border-adaptive bg-slate-50 dark:bg-black/20"><span class="text-[10px] font-mono font-bold text-muted uppercase tracking-widest">Hasil Analisis</span></div>
                                        <div id="scenarioPreview" class="flex-1 p-6 flex items-center justify-center"><div class="text-sm text-muted">Pilih salah satu skenario.</div></div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gradient-to-r from-emerald-50 to-transparent dark:from-emerald-900/20 dark:to-transparent border-l-4 border-emerald-500 p-4 rounded-r-xl">
                                <h5 class="text-xs font-bold text-emerald-700 dark:text-emerald-400 mb-1">Kesimpulan</h5>
                                <p class="text-[11px] text-slate-700 dark:text-slate-300 leading-relaxed m-0 text-justify">Class hasil konfigurasi bekerja jika konfigurasi ditulis di <code>input.css</code>, diproses melalui build, lalu <code>output.css</code> dihubungkan ke HTML.</p>
                            </div>
                        </div>
                    </section>

                    <section id="section-25" class="lesson-section scroll-mt-32" data-lesson-id="25" data-type="activity">
                        <div class="space-y-8">
                            <div class="space-y-4 border-l-4 border-cyan-500 pl-4 md:pl-6">
                                <span class="text-cyan-600 dark:text-cyan-400 font-mono text-xs uppercase tracking-widest">Aktivitas 1.5</span>
                                <h2 class="text-3xl md:text-5xl font-black text-heading leading-tight">Analisis Konfigurasi <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-500 to-blue-600">Tailwind CSS</span></h2>
                            </div>

                            <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-90 text-sm md:text-base leading-relaxed text-justify">
                                <p>Aktivitas ini digunakan untuk mengecek pemahaman tentang konfigurasi dasar Tailwind CSS. Pilih jawaban paling tepat berdasarkan alur <code>input.css</code>, <code>@import</code>, <code>@theme</code>, proses build, dan penggunaan class hasil konfigurasi.</p>
                            </div>

                            <div class="card-adaptive border rounded-2xl overflow-hidden shadow-xl relative">
                                <div id="lockOverlay" class="hidden absolute inset-0 z-20 bg-white/80 dark:bg-slate-950/80 backdrop-blur-sm items-center justify-center p-6">
                                    <div class="max-w-md text-center">
                                        <div class="w-14 h-14 rounded-full bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mx-auto mb-4 text-2xl">✓</div>
                                        <h3 class="font-black text-heading mb-2">Aktivitas Sudah Selesai</h3>
                                        <p class="text-sm text-muted leading-relaxed">Jawaban aktivitas telah tersimpan. Anda dapat melanjutkan ke evaluasi Bab 1.</p>
                                    </div>
                                </div>

                                <div class="bg-cyan-600/95 dark:bg-cyan-900/95 text-white p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                                    <div class="text-xs font-bold uppercase tracking-widest">Evaluasi Interaktif</div>
                                    <p class="text-[11px] opacity-90 leading-relaxed m-0 md:text-right max-w-xl">Jawab semua soal, lalu tekan tombol periksa untuk melihat skor dan pembahasan.</p>
                                </div>

                                <div id="activityForm" class="p-4 md:p-6 space-y-4 max-h-[640px] overflow-y-auto custom-scrollbar">
                                    <div class="activity-question card-adaptive border rounded-xl p-5" data-answer="b">
                                        <p class="text-sm font-bold text-heading mb-4">1. File yang digunakan sebagai sumber untuk menulis <code>@import</code> dan <code>@theme</code> adalah ....</p>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                            <button onclick="chooseActivity(this, 'q1', 'a')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-cyan-500 transition">A. <code>output.css</code></button>
                                            <button onclick="chooseActivity(this, 'q1', 'b')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-cyan-500 transition">B. <code>input.css</code></button>
                                            <button onclick="chooseActivity(this, 'q1', 'c')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-cyan-500 transition">C. <code>index.php</code></button>
                                            <button onclick="chooseActivity(this, 'q1', 'd')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-cyan-500 transition">D. <code>package-lock.json</code></button>
                                        </div>
                                    </div>

                                    <div class="activity-question card-adaptive border rounded-xl p-5" data-answer="a">
                                        <p class="text-sm font-bold text-heading mb-4">2. Baris yang benar untuk memanggil Tailwind di file CSS sumber adalah ....</p>
                                        <div class="grid grid-cols-1 gap-3">
                                            <button onclick="chooseActivity(this, 'q2', 'a')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-cyan-500 transition">A. <code>@import "tailwindcss";</code></button>
                                            <button onclick="chooseActivity(this, 'q2', 'b')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-cyan-500 transition">B. <code>@theme "tailwindcss";</code></button>
                                            <button onclick="chooseActivity(this, 'q2', 'c')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-cyan-500 transition">C. <code>&lt;link href="tailwindcss"&gt;</code></button>
                                            <button onclick="chooseActivity(this, 'q2', 'd')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-cyan-500 transition">D. <code>npm run tailwindcss;</code></button>
                                        </div>
                                    </div>

                                    <div class="activity-question card-adaptive border rounded-xl p-5" data-answer="c">
                                        <p class="text-sm font-bold text-heading mb-4">3. Variabel <code>--color-sekolah-500</code> dapat menghasilkan class ....</p>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                            <button onclick="chooseActivity(this, 'q3', 'a')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-cyan-500 transition">A. <code>rounded-sekolah-500</code></button>
                                            <button onclick="chooseActivity(this, 'q3', 'b')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-cyan-500 transition">B. <code>font-sekolah-500</code></button>
                                            <button onclick="chooseActivity(this, 'q3', 'c')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-cyan-500 transition">C. <code>bg-sekolah-500</code></button>
                                            <button onclick="chooseActivity(this, 'q3', 'd')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-cyan-500 transition">D. <code>shadow-sekolah-500</code></button>
                                        </div>
                                    </div>

                                    <div class="activity-question card-adaptive border rounded-xl p-5" data-answer="d">
                                        <p class="text-sm font-bold text-heading mb-4">4. Setelah <code>@theme</code> diubah, langkah yang perlu dilakukan agar perubahan masuk ke file hasil adalah ....</p>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                            <button onclick="chooseActivity(this, 'q4', 'a')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-cyan-500 transition">A. Menghapus file HTML</button>
                                            <button onclick="chooseActivity(this, 'q4', 'b')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-cyan-500 transition">B. Menghubungkan <code>input.css</code> langsung ke HTML</button>
                                            <button onclick="chooseActivity(this, 'q4', 'c')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-cyan-500 transition">C. Mengubah nama folder menjadi Tailwind</button>
                                            <button onclick="chooseActivity(this, 'q4', 'd')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-cyan-500 transition">D. Menjalankan proses build kembali</button>
                                        </div>
                                    </div>

                                    <div class="activity-question card-adaptive border rounded-xl p-5" data-answer="b">
                                        <p class="text-sm font-bold text-heading mb-4">5. File yang seharusnya dihubungkan ke HTML setelah proses build adalah ....</p>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                            <button onclick="chooseActivity(this, 'q5', 'a')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-cyan-500 transition">A. <code>input.css</code></button>
                                            <button onclick="chooseActivity(this, 'q5', 'b')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-cyan-500 transition">B. <code>output.css</code></button>
                                            <button onclick="chooseActivity(this, 'q5', 'c')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-cyan-500 transition">C. <code>node_modules</code></button>
                                            <button onclick="chooseActivity(this, 'q5', 'd')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-cyan-500 transition">D. <code>README.md</code></button>
                                        </div>
                                    </div>
                                </div>

                                <div class="border-t border-adaptive p-4 md:p-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
                                    <div>
                                        <p id="activity-status" class="text-xs font-bold text-muted">Belum diperiksa.</p>
                                        <p id="activity-score" class="text-sm font-black text-heading mt-1">Skor: -</p>
                                    </div>
                                    <button id="submitBtn" onclick="checkActivity()" class="px-5 py-3 rounded-xl bg-gradient-to-r from-cyan-600 to-blue-600 text-white font-bold text-xs shadow-lg hover:shadow-cyan-500/30 transition">Periksa Jawaban</button>
                                </div>

                                <div id="activity-analysis" class="hidden border-t border-adaptive p-4 md:p-6 bg-slate-50 dark:bg-black/20">
                                    <h3 class="font-bold text-heading mb-3">Pembahasan Singkat</h3>
                                    <div class="space-y-2 text-xs text-muted leading-relaxed">
                                        <p><strong>1.</strong> <code>input.css</code> adalah file sumber untuk menulis <code>@import</code> dan konfigurasi <code>@theme</code>.</p>
                                        <p><strong>2.</strong> <code>@import "tailwindcss";</code> digunakan untuk memanggil sistem Tailwind pada file CSS sumber.</p>
                                        <p><strong>3.</strong> Token warna seperti <code>--color-sekolah-500</code> dapat digunakan sebagai <code>bg-sekolah-500</code>, <code>text-sekolah-500</code>, dan <code>border-sekolah-500</code>.</p>
                                        <p><strong>4.</strong> Setelah konfigurasi diubah, build perlu dijalankan kembali agar <code>output.css</code> ikut diperbarui.</p>
                                        <p><strong>5.</strong> HTML menghubungkan <code>output.css</code> karena file itulah hasil proses build.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                </article>

                <div class="mt-20 md:mt-32 pt-8 border-t border-adaptive flex flex-col sm:flex-row justify-between items-center gap-4">
                    <a href="{{ \Illuminate\Support\Facades\Route::has('courses.implementation') ? route('courses.implementation') : '#' }}" class="group flex items-center gap-4 text-muted hover:text-heading transition-colors w-full sm:w-auto justify-center sm:justify-start">
                        <div class="w-10 h-10 md:w-12 md:h-12 rounded-full border border-adaptive flex items-center justify-center group-hover:bg-slate-100 dark:group-hover:bg-white/5 transition-colors shrink-0">
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        </div>
                        <div class="text-left">
                            <div class="text-[10px] uppercase tracking-widest font-bold opacity-60">Sebelumnya</div>
                            <div class="font-black text-xs md:text-sm line-clamp-1">Instalasi Tailwind CSS</div>
                        </div>
                    </a>

                    <div id="nextChapterBtn" class="group flex items-center gap-4 text-right text-muted cursor-not-allowed opacity-50 pointer-events-none transition-all duration-500 w-full sm:w-auto justify-center sm:justify-end">
                        <div class="text-right">
                            <div id="nextLabel" class="text-[10px] uppercase tracking-widest font-bold opacity-60">Terkunci</div>
                            <div class="font-black text-xs md:text-sm line-clamp-1">Evaluasi Bab 1</div>
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
    window.LESSON_IDS = [21, 22, 23, 24, 25];
    let rawCompletedIds = {!! json_encode($completedLessonIds ?? []) !!};
    window.COMPLETED_IDS = rawCompletedIds.map(id => Number(id));
    let completedSet = new Set(window.COMPLETED_IDS);
    const ACTIVITY_LESSON_ID = 25;
    let activityCompleted = {!! ($activityCompleted ?? false) ? 'true' : 'false' !!};
    const activityAnswers = {};

    document.addEventListener('DOMContentLoaded', () => {
        initScrollSpy();
        initVisualEffects();
        initLessonObserver();
        updateProgressUI(false);
        showFlow('input');
        showToken('color');
        runBuildSim(0);
        checkScenario('valid');

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
                    highlightAnchor(entry.target.id);
                }
            });
        }, { threshold: 0.12, rootMargin: "0px 0px -50px 0px", root });
        document.querySelectorAll('.lesson-section').forEach(s => obs.observe(s));
    }

    function highlightAnchor(id) {
        document.querySelectorAll('.nav-item').forEach(item => item.classList.remove('active'));
        const active = document.querySelector(`.nav-item[data-target="#${id}"]`);
        if(active) active.classList.add('active');
    }

    function showFlow(type) {
        document.querySelectorAll('.flow-card').forEach(btn => btn.classList.remove('active'));
        document.getElementById('flow-' + type)?.classList.add('active');
        const data = {
            input: { title: 'src/input.css', text: 'File sumber berisi @import "tailwindcss"; dan @theme. File ini masih perlu diproses sebelum dipakai browser.', code: '@import "tailwindcss";\n\n@theme {\n  --color-sekolah-500: #2563eb;\n}' },
            build: { title: 'Tailwind CLI', text: 'CLI membaca input.css, memproses token tema, lalu membuat file output.css yang siap dihubungkan ke HTML.', code: 'npx @tailwindcss/cli -i ./src/input.css -o ./src/output.css --watch' },
            output: { title: 'src/output.css', text: 'File hasil build berisi aturan CSS final. File ini dapat berubah setiap kali input.css diproses ulang.', code: '/* hasil build */\n.bg-sekolah-500 { background-color: #2563eb; }' },
            html: { title: 'index.html', text: 'HTML menghubungkan output.css, bukan input.css. Setelah itu class hasil konfigurasi dapat dipakai pada elemen.', code: '<link rel="stylesheet" href="./src/output.css">\n\n<article class="bg-sekolah-500 rounded-kartu">...</article>' }
        };
        const item = data[type];
        document.getElementById('flowPreview').innerHTML = `<div class="w-full code-adaptive border border-adaptive rounded-2xl p-5"><h3 class="font-bold text-heading mb-2">${item.title}</h3><p class="text-sm text-muted leading-relaxed mb-4 text-justify">${item.text}</p><pre class="bg-white dark:bg-black/30 border border-adaptive rounded-xl p-4 overflow-auto custom-scrollbar text-xs font-mono whitespace-pre-wrap">${escapeHtml(item.code)}</pre></div>`;
    }

    function showToken(type) {
        document.querySelectorAll('.token-btn').forEach(btn => btn.classList.remove('active'));
        document.getElementById('token-' + type)?.classList.add('active');
        const data = {
            color: { title: '--color-sekolah-500', desc: 'Token warna dapat digunakan pada latar, teks, dan border.', classes: ['bg-sekolah-500', 'text-sekolah-500', 'border-sekolah-500'] },
            font: { title: '--font-display', desc: 'Token font menghasilkan class untuk mengatur jenis huruf.', classes: ['font-display'] },
            radius: { title: '--radius-kartu', desc: 'Token radius menghasilkan class untuk bentuk sudut elemen.', classes: ['rounded-kartu'] },
            shadow: { title: '--shadow-kartu', desc: 'Token shadow menghasilkan class untuk bayangan komponen.', classes: ['shadow-kartu'] }
        };
        const item = data[type];
        document.getElementById('tokenPreview').innerHTML = `<div class="w-full max-w-sm card-adaptive border rounded-2xl p-5"><p class="text-[10px] uppercase tracking-widest text-cyan-600 dark:text-cyan-400 font-bold mb-2">${item.title}</p><h3 class="font-black text-heading mb-2">Class yang terbentuk</h3><p class="text-sm text-muted leading-relaxed mb-4">${item.desc}</p><div class="flex flex-wrap gap-2">${item.classes.map(c => `<span class="hl-term text-xs">${c}</span>`).join('')}</div></div>`;
    }

    function runBuildSim(step) {
        const term = document.getElementById('build-terminal');
        [1,2,3].forEach(n => document.getElementById('build-step-' + n)?.classList.remove('border-cyan-400', 'bg-cyan-50', 'dark:bg-cyan-900/20'));
        if(step === 0) {
            term.innerHTML = '<div class="text-slate-400">$ Menunggu perintah build...</div>';
            return;
        }
        const lines = {
            1: '<div class="text-cyan-300">$ Membaca ./src/input.css</div><div class="text-slate-300">Ditemukan @import dan @theme.</div>',
            2: '<div class="text-blue-300 mt-2">$ Memproses token tema</div><div class="text-slate-300">Token --color-sekolah-500 menghasilkan class warna.</div>',
            3: '<div class="text-emerald-300 mt-2">$ Menulis ./src/output.css</div><div class="text-slate-300">output.css berhasil diperbarui.</div>'
        };
        term.innerHTML += lines[step];
        document.getElementById('build-step-' + step)?.classList.add('border-cyan-400', 'bg-cyan-50', 'dark:bg-cyan-900/20');
        term.scrollTop = term.scrollHeight;
    }

    function checkScenario(type) {
        document.querySelectorAll('.scenario-btn').forEach(btn => btn.classList.remove('active'));
        document.getElementById('scenario-' + type)?.classList.add('active');
        const data = {
            wronglink: { color: 'rose', title: 'Tidak valid', text: 'HTML tidak sebaiknya menghubungkan input.css karena file tersebut belum menjadi hasil build. Hubungkan output.css.' },
            nobuild: { color: 'amber', title: 'Belum lengkap', text: 'Jika @theme diubah tetapi build belum dijalankan kembali, output.css masih memakai hasil lama.' },
            valid: { color: 'emerald', title: 'Valid', text: 'Alur benar: konfigurasi ditulis di input.css, diproses melalui build, lalu HTML menghubungkan output.css.' }
        };
        const item = data[type];
        document.getElementById('scenarioPreview').innerHTML = `<div class="w-full max-w-sm rounded-2xl border border-${item.color}-200 dark:border-${item.color}-500/30 bg-${item.color}-50 dark:bg-${item.color}-500/10 p-5"><p class="text-[10px] uppercase tracking-widest font-bold text-${item.color}-600 dark:text-${item.color}-400 mb-2">Hasil Skenario</p><h3 class="font-black text-heading mb-2">${item.title}</h3><p class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed text-justify">${item.text}</p></div>`;
    }

    function chooseActivity(btn, q, ans) {
        if (activityCompleted) return;
        activityAnswers[q] = ans;
        const group = btn.closest('.activity-question');
        group.querySelectorAll('.activity-option').forEach(b => b.classList.remove('bg-cyan-600', 'text-white', 'border-cyan-500'));
        btn.classList.add('bg-cyan-600', 'text-white', 'border-cyan-500');
    }

    async function checkActivity() {
        if (activityCompleted) return;
        const correct = { q1:'b', q2:'a', q3:'c', q4:'d', q5:'b' };
        const total = Object.keys(correct).length;
        const answered = Object.keys(activityAnswers).length;
        const status = document.getElementById('activity-status');
        const scoreLabel = document.getElementById('activity-score');
        const submit = document.getElementById('submitBtn');

        if (answered < total) {
            status.innerText = 'Lengkapi semua soal terlebih dahulu.';
            status.className = 'text-xs font-bold text-red-500';
            submit.classList.add('shake');
            setTimeout(() => submit.classList.remove('shake'), 500);
            return;
        }

        let score = 0;
        Object.keys(correct).forEach((q, idx) => {
            const question = document.querySelectorAll('.activity-question')[idx];
            const options = question.querySelectorAll('.activity-option');
            options.forEach(opt => {
                opt.classList.remove('correct', 'wrong');
                const clickAttr = opt.getAttribute('onclick') || '';
                if (clickAttr.includes(`'${correct[q]}'`)) opt.classList.add('correct');
                if (clickAttr.includes(`'${activityAnswers[q]}'`) && activityAnswers[q] !== correct[q]) opt.classList.add('wrong');
            });
            if (activityAnswers[q] === correct[q]) score++;
        });

        const percent = Math.round((score / total) * 100);
        scoreLabel.innerText = `Skor: ${score}/${total} (${percent}%)`;
        document.getElementById('activity-analysis').classList.remove('hidden');

        if (percent >= 80) {
            status.innerText = 'Aktivitas berhasil. Pemahaman konfigurasi dasar Tailwind sudah sesuai.';
            status.className = 'text-xs font-bold text-emerald-600 dark:text-emerald-400';
            await saveLessonToDB(ACTIVITY_LESSON_ID);
            activityCompleted = true;
            lockActivityUI();
            unlockNextChapter();
        } else {
            status.innerText = 'Skor belum cukup. Baca pembahasan, lalu perbaiki jawaban yang salah.';
            status.className = 'text-xs font-bold text-amber-600 dark:text-amber-400';
        }
    }

    function lockActivityUI() {
        const overlay = document.getElementById('lockOverlay');
        if(overlay) overlay.classList.remove('hidden');
        const submit = document.getElementById('submitBtn');
        if(submit) {
            submit.disabled = true;
            submit.classList.add('opacity-50', 'cursor-not-allowed');
            submit.innerText = 'Aktivitas Selesai';
        }
    }

    function unlockNextChapter() {
        const btn = document.getElementById('nextChapterBtn');
        if(btn) {
            btn.classList.remove('cursor-not-allowed', 'opacity-50', 'pointer-events-none', 'text-muted');
            btn.classList.add('text-cyan-600', 'dark:text-cyan-400', 'cursor-pointer');
            document.getElementById('nextLabel').innerText = 'Selanjutnya';
            document.getElementById('nextLabel').classList.remove('opacity-60');
            document.getElementById('nextLabel').classList.add('text-cyan-600', 'dark:text-cyan-400', 'opacity-100');
            const icon = document.getElementById('nextIcon');
            icon.innerHTML = `<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>`;
            icon.classList.remove('bg-slate-100', 'dark:bg-white/5');
            icon.classList.add('bg-cyan-100', 'dark:bg-cyan-500/20', 'border-cyan-300', 'dark:border-cyan-500/50', 'text-cyan-600', 'dark:text-cyan-400', 'shadow-lg');
            btn.onclick = () => window.location.href = "{{ \Illuminate\Support\Facades\Route::has('quiz.intro') ? route('quiz.intro', ['chapterId' => 1]) : '#' }}";
        }
    }

    function initScrollSpy() {
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', () => {
                const target = item.getAttribute('data-target');
                if (!target) return;
                const el = document.querySelector(target);
                if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        });
    }

    function initVisualEffects() {
        const canvas = document.getElementById('stars');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        let stars = [];
        function resize() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
            stars = Array.from({length: 60}, () => ({x: Math.random()*canvas.width, y: Math.random()*canvas.height, r: Math.random()*1.4, a: Math.random()}));
        }
        function draw() {
            ctx.clearRect(0,0,canvas.width,canvas.height);
            stars.forEach(s => {
                ctx.globalAlpha = s.a;
                ctx.fillStyle = '#ffffff';
                ctx.beginPath(); ctx.arc(s.x,s.y,s.r,0,Math.PI*2); ctx.fill();
                s.a += (Math.random()-.5)*0.03; s.a = Math.max(.15, Math.min(1, s.a));
            });
            requestAnimationFrame(draw);
        }
        resize(); draw(); window.addEventListener('resize', resize);
    }

    function escapeHtml(str) {
        return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
    }
</script>
@endsection
