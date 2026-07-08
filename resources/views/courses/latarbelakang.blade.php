@extends('layouts.landing')
@section('title','Tailwind CSS melalui CDN')

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
                    <div class="w-8 h-8 rounded-lg bg-indigo-500/10 dark:bg-indigo-500/20 border border-indigo-500/20 flex items-center justify-center font-bold text-xs text-indigo-600 dark:text-indigo-400 shrink-0">1.3</div>
                    <div class="min-w-0">
                        <h1 class="text-sm font-bold text-heading line-clamp-1">Tailwind CSS melalui CDN</h1>
                        <p class="text-[10px] text-muted line-clamp-1">CDN, penempatan head, class pada body, dan batas penggunaannya</p>
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
                        <p class="text-xs font-mono font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-[.25em] mb-3">Subbab 1.3</p>
                        <h2 class="text-3xl md:text-5xl font-black text-heading leading-tight mb-4">Tailwind CSS melalui CDN</h2>
                        <p class="text-sm md:text-base text-muted leading-relaxed max-w-3xl text-justify">
                            Pada subbab ini, pembelajar mempelajari cara mencoba Tailwind CSS secara cepat melalui CDN. Materi ini berfokus pada fungsi CDN, penempatan script pada bagian <code>&lt;head&gt;</code>, penggunaan utility class pada elemen HTML, serta batasan CDN sebelum masuk ke instalasi lokal.
                        </p>
                    </div>

                    <h3 class="text-xl font-bold text-heading mt-10 mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Tujuan Pembelajaran
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="card-adaptive border rounded-xl p-5">
                            <div class="w-8 h-8 rounded-lg bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold mb-4">1</div>
                            <h4 class="text-sm font-bold text-heading mb-1">Menjelaskan CDN</h4>
                            <p class="text-[11px] text-muted leading-relaxed">Menjelaskan fungsi CDN sebagai cara cepat menjalankan Tailwind CSS pada file HTML.</p>
                        </div>
                        <div class="card-adaptive border rounded-xl p-5">
                            <div class="w-8 h-8 rounded-lg bg-purple-500/10 text-purple-600 dark:text-purple-400 flex items-center justify-center font-bold mb-4">2</div>
                            <h4 class="text-sm font-bold text-heading mb-1">Menempatkan Script</h4>
                            <p class="text-[11px] text-muted leading-relaxed">Menentukan posisi script CDN Tailwind di bagian <code>&lt;head&gt;</code> dokumen HTML.</p>
                        </div>
                        <div class="card-adaptive border rounded-xl p-5">
                            <div class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold mb-4">3</div>
                            <h4 class="text-sm font-bold text-heading mb-1">Menerapkan Class</h4>
                            <p class="text-[11px] text-muted leading-relaxed">Menggunakan utility class Tailwind pada elemen HTML dan mengamati hasil tampilannya.</p>
                        </div>
                        <div class="card-adaptive border rounded-xl p-5">
                            <div class="w-8 h-8 rounded-lg bg-orange-500/10 text-orange-600 dark:text-orange-400 flex items-center justify-center font-bold mb-4">4</div>
                            <h4 class="text-sm font-bold text-heading mb-1">Memilih Penggunaan</h4>
                            <p class="text-[11px] text-muted leading-relaxed">Membedakan kondisi yang cocok menggunakan CDN dan kondisi yang lebih tepat menggunakan instalasi lokal.</p>
                        </div>
                    </div>
                </div>

                <article class="space-y-32 md:space-y-40">

                    <section id="section-11" class="lesson-section scroll-mt-32" data-lesson-id="11">
                        <div class="space-y-8">
                            <div class="space-y-4 border-l-4 border-indigo-500 pl-4 md:pl-6">
                                <span class="text-indigo-600 dark:text-indigo-400 font-mono text-xs uppercase tracking-widest">Lesson 1.3.1</span>
                                <h2 class="text-3xl md:text-5xl font-black text-heading leading-tight">Fungsi CDN <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-purple-600">Tailwind CSS</span></h2>
                            </div>

                            <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-90 text-sm md:text-base leading-relaxed space-y-4 text-justify">
                                <p>CDN atau <em>Content Delivery Network</em> adalah jaringan server yang dapat mengirimkan file library ke browser melalui internet. Pada pembelajaran Tailwind CSS, CDN dipakai agar pembelajar dapat mencoba Tailwind tanpa menyiapkan Node.js, NPM, atau proses build terlebih dahulu.</p>
                                <p>Dengan CDN, alur belajar menjadi lebih sederhana. Pembelajar cukup membuat file <code>index.html</code>, menambahkan script Tailwind pada bagian <code>&lt;head&gt;</code>, lalu menulis utility class pada elemen di bagian <code>&lt;body&gt;</code>. Perubahan tampilan dapat langsung diamati di browser.</p>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                <div class="code-adaptive border border-adaptive rounded-2xl overflow-hidden">
                                    <div class="px-4 py-3 border-b border-adaptive flex items-center justify-between">
                                        <span class="font-mono text-xs font-bold text-muted">alur-cdn.html</span>
                                        <span class="text-[10px] uppercase tracking-widest text-indigo-500 font-bold">Alur Kerja</span>
                                    </div>
                                    <pre class="p-5 overflow-auto custom-scrollbar text-xs md:text-sm font-mono leading-relaxed"><code><span class="code-line"><span class="comment">/* 1. Browser membaca HTML */</span></span>
<span class="code-line"><span class="tag">&lt;head&gt;</span></span>
<span class="code-line">  <span class="tag">&lt;script</span> <span class="attr">src</span>=<span class="str">"https://cdn.tailwindcss.com"</span><span class="tag">&gt;&lt;/script&gt;</span></span>
<span class="code-line"><span class="tag">&lt;/head&gt;</span></span>

<span class="code-line"><span class="comment">/* 2. Utility class dipakai pada body */</span></span>
<span class="code-line"><span class="tag">&lt;div</span> <span class="attr">class</span>=<span class="str">"bg-white p-6 rounded-xl shadow-md"</span><span class="tag">&gt;</span></span>
<span class="code-line">  Produk Pilihan</span>
<span class="code-line"><span class="tag">&lt;/div&gt;</span></span></code></pre>
                                </div>

                                <div class="card-adaptive border rounded-2xl p-5 flex flex-col justify-center">
                                    <h3 class="font-bold text-heading mb-4">Cara membaca alurnya</h3>
                                    <div class="space-y-3 text-sm">
                                        <div class="flex gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold shrink-0">1</div>
                                            <p class="text-muted leading-relaxed">Script CDN memanggil Tailwind dari server luar.</p>
                                        </div>
                                        <div class="flex gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-purple-500/10 text-purple-600 dark:text-purple-400 flex items-center justify-center font-bold shrink-0">2</div>
                                            <p class="text-muted leading-relaxed">Utility class ditulis langsung pada atribut <code>class</code>.</p>
                                        </div>
                                        <div class="flex gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold shrink-0">3</div>
                                            <p class="text-muted leading-relaxed">Browser menampilkan hasil gaya sesuai class Tailwind yang digunakan.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="sim-bg-adaptive border border-adaptive rounded-2xl overflow-hidden shadow-xl">
                                <div class="bg-indigo-600/95 dark:bg-indigo-900/95 text-white p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3 border-b border-indigo-400/50">
                                    <div class="text-xs font-bold uppercase tracking-widest">Simulasi 1 — Aktifkan CDN</div>
                                    <p class="text-[11px] opacity-90 leading-relaxed m-0 md:text-right max-w-xl">Nyalakan atau matikan CDN untuk melihat apakah utility class Tailwind terbaca oleh browser.</p>
                                </div>
                                <div class="grid grid-cols-1 lg:grid-cols-2 min-h-[360px]">
                                    <div class="code-adaptive border-b lg:border-b-0 lg:border-r border-adaptive p-5">
                                        <div class="flex items-center justify-between gap-4 mb-5">
                                            <div>
                                                <h3 class="font-bold text-heading text-sm">Status CDN</h3>
                                                <p class="text-xs text-muted">Script CDN menentukan apakah class Tailwind bekerja.</p>
                                            </div>
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input id="cdn-toggle" type="checkbox" class="sr-only peer" onchange="updateCdnToggleSim()" checked>
                                                <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                            </label>
                                        </div>
                                        <pre id="cdn-toggle-code" class="bg-white dark:bg-black/30 border border-adaptive rounded-xl p-4 overflow-auto custom-scrollbar text-xs md:text-sm font-mono leading-relaxed"></pre>
                                    </div>
                                    <div class="bg-white dark:bg-slate-950 flex flex-col">
                                        <div class="px-4 py-3 border-b border-adaptive bg-slate-50 dark:bg-black/20">
                                            <span class="text-[10px] font-mono font-bold text-muted uppercase tracking-widest">Browser Preview</span>
                                        </div>
                                        <div id="cdn-toggle-preview" class="flex-1 flex items-center justify-center p-8 transition-all duration-300">
                                            <div id="cdn-demo-card" class="transition-all duration-300 bg-white border border-slate-200 rounded-xl shadow-md p-6 max-w-xs">
                                                <p id="cdn-demo-status" class="text-[10px] uppercase tracking-widest font-bold text-emerald-600 mb-2">CDN aktif</p>
                                                <h4 class="font-black text-slate-900 text-xl">Produk Pilihan</h4>
                                                <p class="text-slate-600 text-sm mt-2">Kartu terlihat rapi karena Tailwind terbaca.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gradient-to-r from-indigo-50 to-transparent dark:from-indigo-900/20 dark:to-transparent border-l-4 border-indigo-500 p-4 rounded-r-xl">
                                <h5 class="text-xs font-bold text-indigo-700 dark:text-indigo-400 mb-1">Kesimpulan</h5>
                                <p class="text-[11px] text-slate-700 dark:text-slate-300 leading-relaxed m-0 text-justify">CDN membantu pembelajaran awal karena Tailwind dapat dicoba langsung pada satu file HTML. Namun, class Tailwind hanya bekerja jika script CDN benar-benar dimuat oleh browser.</p>
                            </div>
                        </div>
                    </section>

                    <section id="section-12" class="lesson-section scroll-mt-32" data-lesson-id="12">
                        <div class="space-y-8">
                            <div class="space-y-4 border-l-4 border-indigo-500 pl-4 md:pl-6">
                                <span class="text-indigo-600 dark:text-indigo-400 font-mono text-xs uppercase tracking-widest">Lesson 1.3.2</span>
                                <h2 class="text-3xl md:text-5xl font-black text-heading leading-tight">Menempatkan CDN pada <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-purple-600">Bagian Head</span></h2>
                            </div>

                            <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-90 text-sm md:text-base leading-relaxed space-y-4 text-justify">
                                <p>Script CDN Tailwind diletakkan di bagian <code>&lt;head&gt;</code>. Penempatan ini membuat browser memuat Tailwind sebelum elemen pada bagian <code>&lt;body&gt;</code> dirender. Dengan begitu, class yang ditulis pada elemen dapat segera dikenali saat halaman tampil.</p>
                                <p>Pada latihan dasar, struktur dokumen tetap perlu rapi. Bagian <code>&lt;head&gt;</code> berisi informasi halaman dan script CDN, sedangkan bagian <code>&lt;body&gt;</code> berisi konten yang akan dilihat pengguna.</p>
                            </div>

                            <div class="code-adaptive border border-adaptive rounded-2xl overflow-hidden">
                                <div class="px-4 py-3 border-b border-adaptive flex items-center justify-between">
                                    <span class="font-mono text-xs font-bold text-muted">index.html</span>
                                    <span class="text-[10px] uppercase tracking-widest text-indigo-500 font-bold">Struktur Minimal</span>
                                </div>
                                <pre class="p-5 overflow-auto custom-scrollbar text-xs md:text-sm font-mono leading-relaxed"><code><span class="code-line"><span class="tag">&lt;!DOCTYPE html&gt;</span></span>
<span class="code-line"><span class="tag">&lt;html</span> <span class="attr">lang</span>=<span class="str">"id"</span><span class="tag">&gt;</span></span>
<span class="code-line">  <span class="tag">&lt;head&gt;</span></span>
<span class="code-line">    <span class="tag">&lt;meta</span> <span class="attr">charset</span>=<span class="str">"UTF-8"</span><span class="tag">&gt;</span></span>
<span class="code-line">    <span class="tag">&lt;title&gt;</span>Belajar Tailwind<span class="tag">&lt;/title&gt;</span></span>
<span class="code-line">    <span class="tag">&lt;script</span> <span class="attr">src</span>=<span class="str">"https://cdn.tailwindcss.com"</span><span class="tag">&gt;&lt;/script&gt;</span></span>
<span class="code-line">  <span class="tag">&lt;/head&gt;</span></span>
<span class="code-line">  <span class="tag">&lt;body&gt;</span></span>
<span class="code-line">    <span class="tag">&lt;h1</span> <span class="attr">class</span>=<span class="str">"text-3xl font-bold text-blue-600"</span><span class="tag">&gt;</span>Halo Tailwind<span class="tag">&lt;/h1&gt;</span></span>
<span class="code-line">  <span class="tag">&lt;/body&gt;</span></span>
<span class="code-line"><span class="tag">&lt;/html&gt;</span></span></code></pre>
                            </div>

                            <div class="sim-bg-adaptive border border-adaptive rounded-2xl overflow-hidden shadow-xl">
                                <div class="bg-purple-600/95 dark:bg-purple-900/95 text-white p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3 border-b border-purple-400/50">
                                    <div class="text-xs font-bold uppercase tracking-widest">Simulasi 2 — Pilih Posisi CDN</div>
                                    <p class="text-[11px] opacity-90 leading-relaxed m-0 md:text-right max-w-xl">Pilih lokasi script CDN, lalu perhatikan umpan balik struktur HTML.</p>
                                </div>
                                <div class="grid grid-cols-1 lg:grid-cols-2 min-h-[380px]">
                                    <div class="code-adaptive border-b lg:border-b-0 lg:border-r border-adaptive p-5">
                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 mb-5">
                                            <button onclick="setCdnPlacement('head', this)" class="cdn-place-btn px-3 py-2 rounded-lg bg-indigo-600 text-white text-xs font-bold">Di &lt;head&gt;</button>
                                            <button onclick="setCdnPlacement('before', this)" class="cdn-place-btn px-3 py-2 rounded-lg bg-white dark:bg-white/5 border border-adaptive text-xs font-bold">Sebelum HTML</button>
                                            <button onclick="setCdnPlacement('body', this)" class="cdn-place-btn px-3 py-2 rounded-lg bg-white dark:bg-white/5 border border-adaptive text-xs font-bold">Di &lt;body&gt;</button>
                                        </div>
                                        <pre id="cdn-placement-code" class="bg-white dark:bg-black/30 border border-adaptive rounded-xl p-4 overflow-auto custom-scrollbar text-xs md:text-sm font-mono leading-relaxed min-h-[250px]"></pre>
                                    </div>
                                    <div class="bg-white dark:bg-slate-950 flex flex-col">
                                        <div class="px-4 py-3 border-b border-adaptive bg-slate-50 dark:bg-black/20">
                                            <span class="text-[10px] font-mono font-bold text-muted uppercase tracking-widest">Status Struktur</span>
                                        </div>
                                        <div class="flex-1 flex items-center justify-center p-8 bg-slate-50 dark:bg-slate-900/40">
                                            <div id="cdn-placement-status" class="rounded-2xl p-5 border max-w-sm"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gradient-to-r from-purple-50 to-transparent dark:from-purple-900/20 dark:to-transparent border-l-4 border-purple-500 p-4 rounded-r-xl">
                                <h5 class="text-xs font-bold text-purple-700 dark:text-purple-400 mb-1">Kesimpulan</h5>
                                <p class="text-[11px] text-slate-700 dark:text-slate-300 leading-relaxed m-0 text-justify">Penempatan CDN di bagian <code>&lt;head&gt;</code> membuat struktur dokumen lebih rapi dan mudah dipahami. Elemen yang diberi class Tailwind tetap ditulis pada bagian <code>&lt;body&gt;</code>.</p>
                            </div>
                        </div>
                    </section>

                    <section id="section-13" class="lesson-section scroll-mt-32" data-lesson-id="13">
                        <div class="space-y-8">
                            <div class="space-y-4 border-l-4 border-indigo-500 pl-4 md:pl-6">
                                <span class="text-indigo-600 dark:text-indigo-400 font-mono text-xs uppercase tracking-widest">Lesson 1.3.3</span>
                                <h2 class="text-3xl md:text-5xl font-black text-heading leading-tight">Menerapkan Class pada <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-purple-600">Elemen HTML</span></h2>
                            </div>

                            <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-90 text-sm md:text-base leading-relaxed space-y-4 text-justify">
                                <p>Setelah CDN dipasang, utility class Tailwind dapat ditulis pada atribut <code>class</code>. Setiap class mengatur satu bagian tampilan, misalnya <code>bg-white</code> untuk latar putih, <code>p-6</code> untuk padding, <code>rounded-xl</code> untuk sudut melengkung, dan <code>shadow-md</code> untuk bayangan.</p>
                                <p>Beberapa utility class dapat digabungkan dalam satu elemen. Cara ini membuat pembelajar dapat membaca tampilan dari kiri ke kanan langsung dari HTML tanpa membuka file CSS tambahan.</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div class="card-adaptive border rounded-xl p-5"><h3 class="font-bold text-heading text-sm mb-2">bg-white</h3><p class="text-xs text-muted leading-relaxed">Memberi latar putih pada kartu.</p></div>
                                <div class="card-adaptive border rounded-xl p-5"><h3 class="font-bold text-heading text-sm mb-2">p-6</h3><p class="text-xs text-muted leading-relaxed">Memberi ruang dalam agar isi tidak menempel ke tepi.</p></div>
                                <div class="card-adaptive border rounded-xl p-5"><h3 class="font-bold text-heading text-sm mb-2">rounded-xl</h3><p class="text-xs text-muted leading-relaxed">Membuat sudut kartu lebih melengkung.</p></div>
                                <div class="card-adaptive border rounded-xl p-5"><h3 class="font-bold text-heading text-sm mb-2">shadow-md</h3><p class="text-xs text-muted leading-relaxed">Memberi kesan kartu terangkat dari latar.</p></div>
                            </div>

                            <div class="sim-bg-adaptive border border-adaptive rounded-2xl overflow-hidden shadow-xl">
                                <div class="bg-indigo-600/95 dark:bg-indigo-900/95 text-white p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3 border-b border-indigo-400/50">
                                    <div class="text-xs font-bold uppercase tracking-widest">Simulasi 3 — Rakit Class Kartu</div>
                                    <p class="text-[11px] opacity-90 leading-relaxed m-0 md:text-right max-w-xl">Pilih utility class untuk melihat kode dan tampilan kartu berubah secara langsung.</p>
                                </div>
                                <div class="grid grid-cols-1 lg:grid-cols-2 min-h-[420px]">
                                    <div class="code-adaptive border-b lg:border-b-0 lg:border-r border-adaptive p-5">
                                        <div class="space-y-5 mb-6">
                                            <div>
                                                <label class="block text-[10px] font-bold uppercase tracking-widest text-indigo-600 dark:text-indigo-400 mb-2">Latar kartu</label>
                                                <select id="builder-bg" onchange="updateCardBuilder()" class="w-full bg-white dark:bg-black/30 border border-adaptive rounded-lg px-3 py-2 text-xs outline-none focus:ring-2 focus:ring-indigo-500">
                                                    <option value="bg-white">bg-white</option>
                                                    <option value="bg-blue-50">bg-blue-50</option>
                                                    <option value="bg-purple-50">bg-purple-50</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-bold uppercase tracking-widest text-pink-600 dark:text-pink-400 mb-2">Padding</label>
                                                <select id="builder-pad" onchange="updateCardBuilder()" class="w-full bg-white dark:bg-black/30 border border-adaptive rounded-lg px-3 py-2 text-xs outline-none focus:ring-2 focus:ring-indigo-500">
                                                    <option value="p-3">p-3</option>
                                                    <option value="p-6" selected>p-6</option>
                                                    <option value="p-8">p-8</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-bold uppercase tracking-widest text-orange-600 dark:text-orange-400 mb-2">Bentuk</label>
                                                <select id="builder-shape" onchange="updateCardBuilder()" class="w-full bg-white dark:bg-black/30 border border-adaptive rounded-lg px-3 py-2 text-xs outline-none focus:ring-2 focus:ring-indigo-500">
                                                    <option value="rounded-none shadow-none">rounded-none shadow-none</option>
                                                    <option value="rounded-xl shadow-md" selected>rounded-xl shadow-md</option>
                                                    <option value="rounded-2xl shadow-xl">rounded-2xl shadow-xl</option>
                                                </select>
                                            </div>
                                        </div>
                                        <pre id="card-builder-code" class="bg-white dark:bg-black/30 border border-adaptive rounded-xl p-4 overflow-auto custom-scrollbar text-xs md:text-sm font-mono leading-relaxed"></pre>
                                    </div>
                                    <div class="bg-slate-100 dark:bg-slate-950 flex flex-col">
                                        <div class="px-4 py-3 border-b border-adaptive bg-white/60 dark:bg-black/20">
                                            <span class="text-[10px] font-mono font-bold text-muted uppercase tracking-widest">Browser Preview</span>
                                        </div>
                                        <div class="flex-1 flex items-center justify-center p-8 overflow-auto custom-scrollbar">
                                            <div id="card-builder-preview" class="transition-all duration-300 max-w-xs border border-slate-200 bg-white p-6 rounded-xl shadow-md">
                                                <p class="text-[10px] uppercase tracking-widest font-bold text-indigo-600 mb-2">Produk</p>
                                                <h3 class="text-xl font-black text-slate-900">Sepatu Kanvas</h3>
                                                <p class="text-sm text-slate-600 mt-2">Sepatu ringan untuk kegiatan harian.</p>
                                                <button class="mt-4 bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-bold">Lihat Produk</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gradient-to-r from-indigo-50 to-transparent dark:from-indigo-900/20 dark:to-transparent border-l-4 border-indigo-500 p-4 rounded-r-xl">
                                <h5 class="text-xs font-bold text-indigo-700 dark:text-indigo-400 mb-1">Kesimpulan</h5>
                                <p class="text-[11px] text-slate-700 dark:text-slate-300 leading-relaxed m-0 text-justify">Class Tailwind ditulis langsung pada elemen HTML. Setiap class menjelaskan satu bagian tampilan, sehingga hasil desain dapat dibaca dari susunan class yang digunakan.</p>
                            </div>
                        </div>
                    </section>

                    <section id="section-14" class="lesson-section scroll-mt-32" data-lesson-id="14">
                        <div class="space-y-8">
                            <div class="space-y-4 border-l-4 border-indigo-500 pl-4 md:pl-6">
                                <span class="text-indigo-600 dark:text-indigo-400 font-mono text-xs uppercase tracking-widest">Lesson 1.3.4</span>
                                <h2 class="text-3xl md:text-5xl font-black text-heading leading-tight">Kelebihan dan Batasan <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-purple-600">Penggunaan CDN</span></h2>
                            </div>

                            <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-90 text-sm md:text-base leading-relaxed space-y-4 text-justify">
                                <p>CDN cocok digunakan untuk latihan awal karena prosesnya cepat dan tidak membutuhkan instalasi. Satu file HTML sudah cukup untuk mencoba utility class Tailwind dan melihat hasil perubahan tampilannya.</p>
                                <p>Namun, CDN memiliki batasan. Halaman membutuhkan koneksi internet, pengaturan tema tidak selengkap instalasi lokal, dan proses optimasi belum sesuai untuk proyek yang lebih besar. Karena itu, CDN sebaiknya dipakai sebagai tahap pengenalan sebelum belajar instalasi Tailwind.</p>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                <div class="card-adaptive border rounded-2xl p-5">
                                    <h3 class="font-bold text-heading mb-4">CDN cocok digunakan untuk</h3>
                                    <div class="space-y-3 text-sm">
                                        <div class="flex gap-3"><div class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold shrink-0">✓</div><p class="text-muted leading-relaxed">Latihan awal dalam satu file HTML.</p></div>
                                        <div class="flex gap-3"><div class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold shrink-0">✓</div><p class="text-muted leading-relaxed">Demo cepat perubahan tampilan.</p></div>
                                        <div class="flex gap-3"><div class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold shrink-0">✓</div><p class="text-muted leading-relaxed">Memahami hubungan class dan hasil visual.</p></div>
                                    </div>
                                </div>
                                <div class="card-adaptive border rounded-2xl p-5">
                                    <h3 class="font-bold text-heading mb-4">CDN kurang cocok untuk</h3>
                                    <div class="space-y-3 text-sm">
                                        <div class="flex gap-3"><div class="w-8 h-8 rounded-lg bg-orange-500/10 text-orange-600 dark:text-orange-400 flex items-center justify-center font-bold shrink-0">!</div><p class="text-muted leading-relaxed">Proyek besar yang membutuhkan konfigurasi khusus.</p></div>
                                        <div class="flex gap-3"><div class="w-8 h-8 rounded-lg bg-orange-500/10 text-orange-600 dark:text-orange-400 flex items-center justify-center font-bold shrink-0">!</div><p class="text-muted leading-relaxed">Aplikasi yang perlu optimasi file CSS.</p></div>
                                        <div class="flex gap-3"><div class="w-8 h-8 rounded-lg bg-orange-500/10 text-orange-600 dark:text-orange-400 flex items-center justify-center font-bold shrink-0">!</div><p class="text-muted leading-relaxed">Pengembangan tema dan build yang lebih serius.</p></div>
                                    </div>
                                </div>
                            </div>

                            <div class="sim-bg-adaptive border border-adaptive rounded-2xl overflow-hidden shadow-xl">
                                <div class="bg-orange-600/95 dark:bg-orange-900/95 text-white p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3 border-b border-orange-400/50">
                                    <div class="text-xs font-bold uppercase tracking-widest">Simulasi 4 — Pilih Skenario</div>
                                    <p class="text-[11px] opacity-90 leading-relaxed m-0 md:text-right max-w-xl">Pilih kebutuhan proyek untuk menentukan apakah CDN sudah cukup atau perlu instalasi lokal.</p>
                                </div>
                                <div class="grid grid-cols-1 lg:grid-cols-2 min-h-[360px]">
                                    <div class="code-adaptive border-b lg:border-b-0 lg:border-r border-adaptive p-5 space-y-3">
                                        <button onclick="setScenario('latihan', this)" class="scenario-btn w-full text-left border border-adaptive rounded-xl p-4 bg-white dark:bg-black/30 hover:border-indigo-500 transition">
                                            <span class="block text-sm font-bold text-heading">Latihan satu file HTML</span>
                                            <span class="block text-xs text-muted mt-1">Pembelajar baru mengenali utility class Tailwind.</span>
                                        </button>
                                        <button onclick="setScenario('demo', this)" class="scenario-btn w-full text-left border border-adaptive rounded-xl p-4 bg-white dark:bg-black/30 hover:border-indigo-500 transition">
                                            <span class="block text-sm font-bold text-heading">Demo cepat di kelas</span>
                                            <span class="block text-xs text-muted mt-1">Guru ingin menunjukkan perubahan class secara langsung.</span>
                                        </button>
                                        <button onclick="setScenario('besar', this)" class="scenario-btn w-full text-left border border-adaptive rounded-xl p-4 bg-white dark:bg-black/30 hover:border-indigo-500 transition">
                                            <span class="block text-sm font-bold text-heading">Proyek besar dengan tema khusus</span>
                                            <span class="block text-xs text-muted mt-1">Proyek membutuhkan konfigurasi dan optimasi build.</span>
                                        </button>
                                    </div>
                                    <div class="bg-white dark:bg-slate-950 flex flex-col">
                                        <div class="px-4 py-3 border-b border-adaptive bg-slate-50 dark:bg-black/20"><span class="text-[10px] font-mono font-bold text-muted uppercase tracking-widest">Rekomendasi</span></div>
                                        <div class="flex-1 flex items-center justify-center p-8 bg-slate-50 dark:bg-slate-900/40">
                                            <div id="scenario-result" class="rounded-2xl p-5 border max-w-sm"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gradient-to-r from-orange-50 to-transparent dark:from-orange-900/20 dark:to-transparent border-l-4 border-orange-500 p-4 rounded-r-xl">
                                <h5 class="text-xs font-bold text-orange-700 dark:text-orange-400 mb-1">Kesimpulan</h5>
                                <p class="text-[11px] text-slate-700 dark:text-slate-300 leading-relaxed m-0 text-justify">CDN adalah pintu masuk yang praktis untuk belajar Tailwind. Setelah konsep class dipahami, instalasi lokal dibutuhkan agar proyek dapat dikembangkan dengan konfigurasi dan optimasi yang lebih baik.</p>
                            </div>
                        </div>
                    </section>

                    <section id="section-15" class="lesson-section scroll-mt-32" data-lesson-id="15" data-type="activity">
                        <div class="space-y-8">
                            <div class="space-y-4 border-l-4 border-purple-500 pl-4 md:pl-6">
                                <span class="text-purple-600 dark:text-purple-400 font-mono text-xs uppercase tracking-widest">Aktivitas 1.3</span>
                                <h2 class="text-3xl md:text-5xl font-black text-heading leading-tight">Analisis Penggunaan <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-500 to-indigo-600">CDN Tailwind</span></h2>
                            </div>

                            <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-90 text-sm md:text-base leading-relaxed text-justify">
                                <p>Aktivitas ini menggunakan drag n drop untuk menyusun alur penggunaan CDN Tailwind. Geser kartu dari struktur HTML sampai hasilnya tampil di browser!</p>
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
                                    <div class="text-xs font-bold uppercase tracking-widest">Drag n Drop CDN</div>
                                    <p class="text-[11px] opacity-90 leading-relaxed m-0 md:text-right max-w-xl">Geser kartu alur CDN ke urutan yang benar, lalu tekan tombol periksa!</p>
                                </div>

                                <div id="activityForm" class="p-4 md:p-6 space-y-4 max-h-[620px] overflow-y-auto custom-scrollbar">
                                    <div class="activity-question card-adaptive border rounded-xl p-5" data-answer="b">
                                        <p class="text-sm font-bold text-heading mb-4">1. Fungsi utama CDN Tailwind CSS dalam pembelajaran awal adalah ....</p>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                            <button onclick="chooseActivity(this, 'q1', 'a')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-indigo-500 transition">A. Menghapus kebutuhan menulis HTML</button>
                                            <button onclick="chooseActivity(this, 'q1', 'b')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-indigo-500 transition">B. Memanggil Tailwind dari server luar agar dapat langsung dicoba</button>
                                            <button onclick="chooseActivity(this, 'q1', 'c')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-indigo-500 transition">C. Mengubah file HTML menjadi file gambar</button>
                                            <button onclick="chooseActivity(this, 'q1', 'd')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-indigo-500 transition">D. Menggantikan semua fungsi JavaScript</button>
                                        </div>
                                    </div>

                                    <div class="activity-question card-adaptive border rounded-xl p-5" data-answer="a">
                                        <p class="text-sm font-bold text-heading mb-4">2. Posisi yang paling tepat untuk script CDN Tailwind pada struktur HTML sederhana adalah ....</p>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                            <button onclick="chooseActivity(this, 'q2', 'a')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-indigo-500 transition">A. Di dalam <code>&lt;head&gt;</code></button>
                                            <button onclick="chooseActivity(this, 'q2', 'b')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-indigo-500 transition">B. Di luar <code>&lt;html&gt;</code></button>
                                            <button onclick="chooseActivity(this, 'q2', 'c')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-indigo-500 transition">C. Di dalam teks paragraf</button>
                                            <button onclick="chooseActivity(this, 'q2', 'd')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-indigo-500 transition">D. Setelah tag penutup <code>&lt;/html&gt;</code></button>
                                        </div>
                                    </div>

                                    <div class="activity-question card-adaptive border rounded-xl p-5" data-answer="c">
                                        <p class="text-sm font-bold text-heading mb-4">3. Jika CDN tidak dipanggil, class seperti <code>bg-white</code> dan <code>p-6</code> akan ....</p>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                            <button onclick="chooseActivity(this, 'q3', 'a')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-indigo-500 transition">A. Tetap otomatis mengubah tampilan</button>
                                            <button onclick="chooseActivity(this, 'q3', 'b')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-indigo-500 transition">B. Menghapus elemen dari halaman</button>
                                            <button onclick="chooseActivity(this, 'q3', 'c')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-indigo-500 transition">C. Menjadi nama class biasa tanpa aturan Tailwind</button>
                                            <button onclick="chooseActivity(this, 'q3', 'd')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-indigo-500 transition">D. Mengubah HTML menjadi CSS</button>
                                        </div>
                                    </div>

                                    <div class="activity-question card-adaptive border rounded-xl p-5" data-answer="d">
                                        <p class="text-sm font-bold text-heading mb-4">4. Class Tailwind yang digunakan untuk memberi padding pada elemen adalah ....</p>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                            <button onclick="chooseActivity(this, 'q4', 'a')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-indigo-500 transition">A. <code>bg-blue-600</code></button>
                                            <button onclick="chooseActivity(this, 'q4', 'b')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-indigo-500 transition">B. <code>text-white</code></button>
                                            <button onclick="chooseActivity(this, 'q4', 'c')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-indigo-500 transition">C. <code>rounded-xl</code></button>
                                            <button onclick="chooseActivity(this, 'q4', 'd')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-indigo-500 transition">D. <code>p-6</code></button>
                                        </div>
                                    </div>

                                    <div class="activity-question card-adaptive border rounded-xl p-5" data-answer="b">
                                        <p class="text-sm font-bold text-heading mb-4">5. Penggunaan CDN paling sesuai untuk kondisi ....</p>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                            <button onclick="chooseActivity(this, 'q5', 'a')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-indigo-500 transition">A. Proyek besar dengan konfigurasi tema kompleks</button>
                                            <button onclick="chooseActivity(this, 'q5', 'b')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-indigo-500 transition">B. Latihan awal dan demo cepat dalam satu file HTML</button>
                                            <button onclick="chooseActivity(this, 'q5', 'c')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-indigo-500 transition">C. Build CSS lokal untuk produksi</button>
                                            <button onclick="chooseActivity(this, 'q5', 'd')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-indigo-500 transition">D. Mengatur tema khusus melalui file konfigurasi penuh</button>
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
                                        <p>Gunakan urutan kartu sebagai latihan pengamatan alur sebelum melanjutkan.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                </article>

                <div class="mt-20 md:mt-32 pt-8 border-t border-adaptive flex flex-col sm:flex-row justify-between items-center gap-4">
                    <a href="{{ \Illuminate\Support\Facades\Route::has('courses.tailwindcss') ? route('courses.tailwindcss') : '#' }}" class="group flex items-center gap-4 text-muted hover:text-heading transition-colors w-full sm:w-auto justify-center sm:justify-start">
                        <div class="w-10 h-10 md:w-12 md:h-12 rounded-full border border-adaptive flex items-center justify-center group-hover:bg-slate-100 dark:group-hover:bg-white/5 transition-colors shrink-0">
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        </div>
                        <div class="text-left">
                            <div class="text-[10px] uppercase tracking-widest font-bold opacity-60">Sebelumnya</div>
                            <div class="font-black text-xs md:text-sm line-clamp-1">Konsep Dasar Tailwind CSS</div>
                        </div>
                    </a>

                    <div id="nextChapterBtn" class="group flex items-center gap-4 text-right text-muted cursor-not-allowed opacity-50 pointer-events-none transition-all duration-500 w-full sm:w-auto justify-center sm:justify-end">
                        <div class="text-right">
                            <div id="nextLabel" class="text-[10px] uppercase tracking-widest font-bold opacity-60">Berikutnya</div>
                            <div class="font-black text-xs md:text-sm line-clamp-1">Instalasi Tailwind CSS</div>
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
    window.LESSON_IDS = [11, 12, 13, 14, 15];
    let rawCompletedIds = {!! json_encode($completedLessonIds ?? []) !!};
    window.COMPLETED_IDS = rawCompletedIds.map(id => Number(id));
    let completedSet = new Set(window.COMPLETED_IDS);
    const ACTIVITY_LESSON_ID = 15;
    let activityCompleted = {!! ($activityCompleted ?? false) ? 'true' : 'false' !!};
    const activityAnswers = {};
    let activityWidget = null;

    document.addEventListener('DOMContentLoaded', () => {
        initScrollSpy();
        initVisualEffects();
        initLessonObserver();
        updateProgressUI(false);
        updateCdnToggleSim();
        setCdnPlacement('head');
        updateCardBuilder();
        setScenario('latihan');
        initCdnOrderActivity();

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

    function updateCdnToggleSim() {
        const active = document.getElementById('cdn-toggle')?.checked;
        const code = document.getElementById('cdn-toggle-code');
        const card = document.getElementById('cdn-demo-card');
        const status = document.getElementById('cdn-demo-status');

        if (code) {
            code.innerHTML = `<code><span class="tag">&lt;head&gt;</span>
  ${active ? '<span class="tag">&lt;script</span> <span class="attr">src</span>=<span class="str">"https://cdn.tailwindcss.com"</span><span class="tag">&gt;&lt;/script&gt;</span>' : '<span class="comment">&lt;!-- script CDN belum dipasang --&gt;</span>'}
<span class="tag">&lt;/head&gt;</span>

<span class="tag">&lt;body&gt;</span>
  <span class="tag">&lt;div</span> <span class="attr">class</span>=<span class="str">"bg-white p-6 rounded-xl shadow-md"</span><span class="tag">&gt;</span>
    Produk Pilihan
  <span class="tag">&lt;/div&gt;</span>
<span class="tag">&lt;/body&gt;</span></code>`;
        }

        if (card && status) {
            if (active) {
                card.className = 'transition-all duration-300 bg-white border border-slate-200 rounded-xl shadow-md p-6 max-w-xs';
                status.className = 'text-[10px] uppercase tracking-widest font-bold text-emerald-600 mb-2';
                status.innerText = 'CDN aktif';
            } else {
                card.className = 'transition-all duration-300 bg-transparent border border-dashed border-rose-300 rounded-none shadow-none p-0 max-w-xs';
                status.className = 'text-[10px] uppercase tracking-widest font-bold text-rose-600 mb-2';
                status.innerText = 'CDN nonaktif';
            }
        }
    }

    function setCdnPlacement(type, btn = null) {
        document.querySelectorAll('.cdn-place-btn').forEach(b => {
            b.classList.remove('bg-indigo-600', 'text-white');
            b.classList.add('bg-white', 'dark:bg-white/5', 'border', 'border-adaptive');
        });
        if (btn) {
            btn.classList.add('bg-indigo-600', 'text-white');
            btn.classList.remove('bg-white', 'dark:bg-white/5', 'border-adaptive');
        }

        const code = document.getElementById('cdn-placement-code');
        const status = document.getElementById('cdn-placement-status');
        if (!code || !status) return;

        if (type === 'head') {
            code.innerHTML = `<code><span class="tag">&lt;!DOCTYPE html&gt;</span>
<span class="tag">&lt;html</span> <span class="attr">lang</span>=<span class="str">"id"</span><span class="tag">&gt;</span>
  <span class="tag">&lt;head&gt;</span>
    <span class="tag">&lt;title&gt;</span>Belajar Tailwind<span class="tag">&lt;/title&gt;</span>
    <span class="tag">&lt;script</span> <span class="attr">src</span>=<span class="str">"https://cdn.tailwindcss.com"</span><span class="tag">&gt;&lt;/script&gt;</span>
  <span class="tag">&lt;/head&gt;</span>
  <span class="tag">&lt;body&gt;</span>...<span class="tag">&lt;/body&gt;</span>
<span class="tag">&lt;/html&gt;</span></code>`;
            status.className = 'rounded-2xl p-5 border max-w-sm border-emerald-200 dark:border-emerald-500/30 bg-emerald-50 dark:bg-emerald-500/10';
            status.innerHTML = '<h4 class="font-bold text-emerald-700 dark:text-emerald-400 mb-2">Struktur sudah tepat</h4><p class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed">Script CDN berada di bagian head sehingga Tailwind dapat dimuat sebelum isi halaman dirender.</p>';
        } else if (type === 'before') {
            code.innerHTML = `<code><span class="tag">&lt;script</span> <span class="attr">src</span>=<span class="str">"https://cdn.tailwindcss.com"</span><span class="tag">&gt;&lt;/script&gt;</span>
<span class="tag">&lt;!DOCTYPE html&gt;</span>
<span class="tag">&lt;html</span> <span class="attr">lang</span>=<span class="str">"id"</span><span class="tag">&gt;</span>
  <span class="tag">&lt;head&gt;</span>...<span class="tag">&lt;/head&gt;</span>
  <span class="tag">&lt;body&gt;</span>...<span class="tag">&lt;/body&gt;</span>
<span class="tag">&lt;/html&gt;</span></code>`;
            status.className = 'rounded-2xl p-5 border max-w-sm border-rose-200 dark:border-rose-500/30 bg-rose-50 dark:bg-rose-500/10';
            status.innerHTML = '<h4 class="font-bold text-rose-700 dark:text-rose-400 mb-2">Struktur kurang tepat</h4><p class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed">Script tidak sebaiknya diletakkan sebelum dokumen HTML. Simpan script pada bagian head.</p>';
        } else {
            code.innerHTML = `<code><span class="tag">&lt;!DOCTYPE html&gt;</span>
<span class="tag">&lt;html</span> <span class="attr">lang</span>=<span class="str">"id"</span><span class="tag">&gt;</span>
  <span class="tag">&lt;head&gt;</span>
    <span class="tag">&lt;title&gt;</span>Belajar Tailwind<span class="tag">&lt;/title&gt;</span>
  <span class="tag">&lt;/head&gt;</span>
  <span class="tag">&lt;body&gt;</span>
    <span class="tag">&lt;h1</span> <span class="attr">class</span>=<span class="str">"text-3xl font-bold"</span><span class="tag">&gt;</span>Halo<span class="tag">&lt;/h1&gt;</span>
    <span class="tag">&lt;script</span> <span class="attr">src</span>=<span class="str">"https://cdn.tailwindcss.com"</span><span class="tag">&gt;&lt;/script&gt;</span>
  <span class="tag">&lt;/body&gt;</span>
<span class="tag">&lt;/html&gt;</span></code>`;
            status.className = 'rounded-2xl p-5 border max-w-sm border-amber-200 dark:border-amber-500/30 bg-amber-50 dark:bg-amber-500/10';
            status.innerHTML = '<h4 class="font-bold text-amber-700 dark:text-amber-400 mb-2">Masih kurang ideal</h4><p class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed">Pada latihan sederhana mungkin tetap terbaca, tetapi materi ini mengarahkan script CDN di bagian head agar struktur dokumen rapi.</p>';
        }
    }

    function updateCardBuilder() {
        const bg = document.getElementById('builder-bg')?.value || 'bg-white';
        const pad = document.getElementById('builder-pad')?.value || 'p-6';
        const shape = document.getElementById('builder-shape')?.value || 'rounded-xl shadow-md';
        const code = document.getElementById('card-builder-code');
        const preview = document.getElementById('card-builder-preview');
        const classText = `${bg} ${pad} ${shape} max-w-md`;

        if (code) {
            code.innerHTML = `<code><span class="tag">&lt;div</span> <span class="attr">class</span>=<span class="str">"${classText}"</span><span class="tag">&gt;</span>
  <span class="tag">&lt;h3</span> <span class="attr">class</span>=<span class="str">"text-xl font-bold"</span><span class="tag">&gt;</span>Sepatu Kanvas<span class="tag">&lt;/h3&gt;</span>
  <span class="tag">&lt;p</span> <span class="attr">class</span>=<span class="str">"text-slate-600 mt-2"</span><span class="tag">&gt;</span>Sepatu ringan.<span class="tag">&lt;/p&gt;</span>
<span class="tag">&lt;/div&gt;</span></code>`;
        }

        if (preview) {
            preview.className = `transition-all duration-300 max-w-xs border border-slate-200 ${bg} ${pad} ${shape}`;
        }
    }

    function setScenario(type, btn = null) {
        document.querySelectorAll('.scenario-btn').forEach(b => b.classList.remove('border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900/20'));
        if (btn) btn.classList.add('border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900/20');

        const result = document.getElementById('scenario-result');
        if (!result) return;

        if (type === 'besar') {
            result.className = 'rounded-2xl p-5 border max-w-sm border-amber-200 dark:border-amber-500/30 bg-amber-50 dark:bg-amber-500/10';
            result.innerHTML = '<p class="text-[10px] uppercase tracking-widest font-bold text-amber-600 dark:text-amber-400 mb-2">Rekomendasi</p><h4 class="font-black text-heading mb-2">Gunakan instalasi lokal</h4><p class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed">Proyek besar membutuhkan konfigurasi tema, optimasi, dan proses build. CDN tidak ideal untuk kebutuhan ini.</p>';
        } else if (type === 'demo') {
            result.className = 'rounded-2xl p-5 border max-w-sm border-emerald-200 dark:border-emerald-500/30 bg-emerald-50 dark:bg-emerald-500/10';
            result.innerHTML = '<p class="text-[10px] uppercase tracking-widest font-bold text-emerald-600 dark:text-emerald-400 mb-2">Rekomendasi</p><h4 class="font-black text-heading mb-2">CDN cocok digunakan</h4><p class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed">Untuk demo cepat, CDN memudahkan perubahan tampilan tanpa menyiapkan project build.</p>';
        } else {
            result.className = 'rounded-2xl p-5 border max-w-sm border-emerald-200 dark:border-emerald-500/30 bg-emerald-50 dark:bg-emerald-500/10';
            result.innerHTML = '<p class="text-[10px] uppercase tracking-widest font-bold text-emerald-600 dark:text-emerald-400 mb-2">Rekomendasi</p><h4 class="font-black text-heading mb-2">CDN cocok digunakan</h4><p class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed">Untuk latihan satu file HTML, CDN sudah cukup karena tujuan utamanya adalah memahami hubungan class Tailwind dengan tampilan halaman.</p>';
        }
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

    function initCdnOrderActivity() {
        activityWidget = CourseActivityKit.mountDragOrderActivity({
            root: '#activityForm',
            badge: 'Drag n Drop CDN',
            title: 'Susun alur penggunaan Tailwind melalui CDN',
            description: 'Geser kartu sesuai urutan kerja saat membuat halaman HTML sederhana yang memakai Tailwind CDN!',
            minScore: 4,
            initialOrder: ['class', 'html', 'browser', 'cdn', 'head'],
            correctOrder: ['html', 'head', 'cdn', 'class', 'browser'],
            items: [
                {
                    id: 'html',
                    title: 'Buat struktur HTML dasar',
                    desc: 'Siapkan file HTML dengan elemen html, head, dan body.',
                    code: '<!doctype html> ... <body>...</body>',
                    preview: 'Halaman memiliki kerangka awal sebelum Tailwind dipanggil.'
                },
                {
                    id: 'head',
                    title: 'Masuk ke bagian head',
                    desc: 'Tempatkan pemanggilan pustaka sebelum isi halaman ditampilkan.',
                    code: '<head> ... </head>',
                    preview: 'Browser mengetahui resource yang harus dimuat lebih awal.'
                },
                {
                    id: 'cdn',
                    title: 'Tambahkan script CDN Tailwind',
                    desc: 'Panggil Tailwind dari CDN agar utility class dapat digunakan pada latihan cepat.',
                    code: '<script src="https://cdn.tailwindcss.com"><' + '/script>',
                    preview: 'Utility class Tailwind tersedia tanpa instalasi lokal.'
                },
                {
                    id: 'class',
                    title: 'Tulis class pada elemen HTML',
                    desc: 'Gunakan class seperti bg-white, p-6, rounded-xl, dan shadow-md pada elemen.',
                    code: '<article class="bg-white p-6 rounded-xl shadow-md">',
                    preview: 'Tampilan mulai berubah sesuai class yang dipasang.'
                },
                {
                    id: 'browser',
                    title: 'Jalankan dan amati hasil di browser',
                    desc: 'Buka file HTML dan periksa apakah class Tailwind menghasilkan tampilan yang sesuai.',
                    preview: 'Hasil akhir terlihat langsung pada halaman web.'
                }
            ]
        });
    }

    async function checkActivity() {
        if (activityCompleted) return;
        const status = document.getElementById('activity-status');
        const scoreLabel = document.getElementById('activity-score');
        const submit = document.getElementById('submitBtn');
        const result = activityWidget?.check();
        if (!result) return;

        document.getElementById('activity-analysis').classList.toggle('hidden', !result.passed);
        scoreLabel.innerText = `Skor: ${result.score}/${result.total}`;

        if (result.passed) {
            status.innerText = 'Aktivitas selesai. Alur CDN sudah tersusun sesuai materi.';
            status.className = 'text-xs font-bold text-emerald-600 dark:text-emerald-400';
            activityCompleted = true;
            await saveLessonToDB(ACTIVITY_LESSON_ID);
            completedSet.add(ACTIVITY_LESSON_ID);
            updateProgressUI(true);
            unlockNextChapter();
            setTimeout(lockActivityUI, 600);
        } else {
            status.innerText = 'Skor belum memenuhi. Susun ulang kartu berdasarkan alur CDN, lalu periksa kembali.';
            status.className = 'text-xs font-bold text-orange-500';
            submit.classList.add('shake');
            setTimeout(() => submit.classList.remove('shake'), 500);
        }
    }

    function lockActivityUI() {
        const overlay = document.getElementById('lockOverlay');
        if (overlay) overlay.classList.remove('hidden');
        if (overlay) overlay.classList.add('flex');
        document.querySelectorAll('#activityForm button, #submitBtn').forEach(el => el.disabled = true);
        if (activityWidget) activityWidget.lock();
    }

    function unlockNextChapter() {
        const btn = document.getElementById('nextChapterBtn');
        const label = document.getElementById('nextLabel');
        const icon = document.getElementById('nextIcon');
        if (!btn) return;
        btn.classList.remove('cursor-not-allowed', 'opacity-50', 'pointer-events-none', 'text-muted');
        btn.classList.add('text-indigo-600', 'dark:text-indigo-400', 'cursor-pointer');
        btn.onclick = () => { window.location.href = '{{ \Illuminate\Support\Facades\Route::has('courses.implementation') ? route('courses.implementation') : '#' }}'; };
        if (label) label.innerText = 'Lanjutkan';
        if (icon) {
            icon.classList.add('bg-indigo-600', 'text-white', 'border-indigo-500');
            icon.innerHTML = '<svg class="w-4 h-4 md:w-5 md:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>';
        }
    }

    function initScrollSpy() {
        const root = document.getElementById('mainScroll');
        const sections = document.querySelectorAll('.lesson-section');
        const obs = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (!entry.isIntersecting) return;
                const id = entry.target.getAttribute('id');
                document.querySelectorAll('.nav-item').forEach(item => item.classList.remove('active'));
                const active = document.querySelector(`.nav-item[data-target="#${id}"]`);
                if (active) active.classList.add('active');
            });
        }, { root, threshold: 0.35 });
        sections.forEach(section => obs.observe(section));
    }

    function initVisualEffects() {
        const canvas = document.getElementById('stars');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        let stars = [];

        function resize() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
            stars = Array.from({length: 80}, () => ({
                x: Math.random() * canvas.width,
                y: Math.random() * canvas.height,
                r: Math.random() * 1.2 + .2,
                a: Math.random() * .8 + .2,
                s: Math.random() * .25 + .05
            }));
        }

        function draw() {
            ctx.clearRect(0,0,canvas.width,canvas.height);
            stars.forEach(star => {
                star.y += star.s;
                if (star.y > canvas.height) star.y = 0;
                ctx.globalAlpha = star.a;
                ctx.beginPath();
                ctx.arc(star.x, star.y, star.r, 0, Math.PI * 2);
                ctx.fillStyle = '#ffffff';
                ctx.fill();
            });
            requestAnimationFrame(draw);
        }

        resize();
        draw();
        window.addEventListener('resize', resize);
    }
</script>
@endsection
