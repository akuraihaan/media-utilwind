@extends('layouts.landing')
@section('title','Tipografi')

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
        --accent-glow: rgba(6, 182, 212, 0.34);
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
        --accent-glow: rgba(6, 182, 212, 0.52);
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
        background-color: rgba(6, 182, 212, .14);
        color: #0e7490;
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
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: var(--accent); }

    #animated-bg {
        background:
            radial-gradient(600px circle at 18% 16%, rgba(6,182,212,.12), transparent 40%),
            radial-gradient(700px circle at 85% 25%, rgba(59,130,246,.10), transparent 42%),
            radial-gradient(800px circle at 48% 88%, rgba(99,102,241,.10), transparent 44%);
        animation: bgMove 20s ease-in-out infinite alternate;
    }
    .dark #animated-bg {
        background:
            radial-gradient(600px circle at 18% 16%, rgba(6,182,212,.18), transparent 40%),
            radial-gradient(700px circle at 85% 25%, rgba(59,130,246,.14), transparent 42%),
            radial-gradient(800px circle at 48% 88%, rgba(99,102,241,.16), transparent 44%);
    }
    @keyframes bgMove { to { transform: scale(1.12); } }
    @keyframes shake { 0%,100%{transform:translateX(0)} 25%{transform:translateX(-5px)} 75%{transform:translateX(5px)} }
    .shake { animation: shake .4s ease-in-out; }

    .nav-item { display:flex; width:100%; text-align:left; align-items:center; gap:12px; padding:10px 14px; font-size:.85rem; color:var(--text-muted); border-radius:8px; transition:all .2s; }
    .nav-item:hover { color:var(--text-main); background:var(--card-hover); }
    .nav-item.active { color:#0891b2; background:rgba(6,182,212,.08); font-weight:700; }
    .dark .nav-item.active { color:#67e8f9; }
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

    .choice-card.selected { border-color:#06b6d4 !important; background:rgba(6,182,212,.10) !important; color:#0e7490; font-weight:700; }
    .dark .choice-card.selected { color:#67e8f9; }
    .choice-card.correct { border-color:#10b981 !important; background:rgba(16,185,129,.12) !important; }
    .choice-card.wrong { border-color:#ef4444 !important; background:rgba(239,68,68,.10) !important; }
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
                    <div class="w-8 h-8 rounded-lg bg-cyan-500/10 dark:bg-cyan-500/20 border border-cyan-500/20 flex items-center justify-center font-bold text-xs text-cyan-600 dark:text-cyan-400 shrink-0">3.1</div>
                    <div class="min-w-0">
                        <h1 class="text-sm font-bold text-heading line-clamp-1">Tipografi</h1>
                        <p class="text-[10px] text-muted line-clamp-1">Ukuran, ketebalan, leading, warna teks, dan jenis huruf</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <div class="hidden sm:block w-24 md:w-32 h-1.5 bg-slate-200 dark:bg-white/10 rounded-full overflow-hidden">
                        <div id="topProgressBar" class="h-full bg-gradient-to-r from-cyan-400 to-blue-500 w-0 transition-all duration-500 shadow-[0_0_10px_rgba(6,182,212,0.5)]"></div>
                    </div>
                    <span id="progressLabelTop" class="text-cyan-600 dark:text-cyan-400 font-bold text-xs">0%</span>
                </div>
            </div>

            <div class="p-4 sm:p-6 lg:p-16 max-w-5xl mx-auto pb-40">

                <div class="mb-16 md:mb-24">
                    <div class="card-adaptive rounded-3xl border p-6 md:p-8 overflow-hidden relative">
                        <div class="absolute -right-10 -top-10 w-40 h-40 bg-cyan-500/10 rounded-full blur-3xl"></div>
                        <p class="text-xs font-mono font-bold text-cyan-600 dark:text-cyan-400 uppercase tracking-[.25em] mb-3">Subbab 3.1</p>
                        <h2 class="text-3xl md:text-5xl font-black text-heading leading-tight mb-4">Tipografi pada Tailwind CSS</h2>
                        <p class="text-sm md:text-base text-muted leading-relaxed max-w-3xl text-justify">
                            Pada subbab ini, pembelajar mempelajari cara mengatur teks agar informasi pada halaman web lebih mudah dibaca. Fokus materi meliputi ukuran teks, ketebalan, jarak antarbaris, perataan, jenis huruf, dan warna teks menggunakan utility class Tailwind CSS.
                        </p>
                    </div>

                    <h3 class="text-xl font-bold text-heading mt-10 mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-cyan-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Tujuan Pembelajaran
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="card-adaptive border rounded-xl p-5">
                            <div class="w-8 h-8 rounded-lg bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 flex items-center justify-center font-bold mb-4">1</div>
                            <h4 class="text-sm font-bold text-heading mb-1">Membuat Hierarki</h4>
                            <p class="text-[11px] text-muted leading-relaxed">Memilih ukuran dan ketebalan teks untuk membedakan judul, deskripsi, dan keterangan.</p>
                        </div>
                        <div class="card-adaptive border rounded-xl p-5">
                            <div class="w-8 h-8 rounded-lg bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold mb-4">2</div>
                            <h4 class="text-sm font-bold text-heading mb-1">Mengatur Keterbacaan</h4>
                            <p class="text-[11px] text-muted leading-relaxed">Menggunakan <code>leading-*</code> agar paragraf lebih nyaman dibaca.</p>
                        </div>
                        <div class="card-adaptive border rounded-xl p-5">
                            <div class="w-8 h-8 rounded-lg bg-sky-500/10 text-sky-600 dark:text-sky-400 flex items-center justify-center font-bold mb-4">3</div>
                            <h4 class="text-sm font-bold text-heading mb-1">Mengatur Perataan</h4>
                            <p class="text-[11px] text-muted leading-relaxed">Membedakan penggunaan <code>text-left</code>, <code>text-center</code>, dan <code>text-right</code>.</p>
                        </div>
                        <div class="card-adaptive border rounded-xl p-5">
                            <div class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold mb-4">4</div>
                            <h4 class="text-sm font-bold text-heading mb-1">Membedakan Warna Teks</h4>
                            <p class="text-[11px] text-muted leading-relaxed">Memilih warna teks utama, pendukung, dan informasi penting sesuai kebutuhan tampilan.</p>
                        </div>
                    </div>
                </div>

                <article class="space-y-32 md:space-y-40">

                    <section id="section-46" class="lesson-section scroll-mt-32" data-lesson-id="46">
                        <div class="space-y-8">
                            <div class="space-y-4 border-l-4 border-cyan-500 pl-4 md:pl-6">
                                <span class="text-cyan-600 dark:text-cyan-400 font-mono text-xs uppercase tracking-widest">Lesson 3.1.1</span>
                                <h2 class="text-3xl md:text-5xl font-black text-heading leading-tight">Fungsi Tipografi dan <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-500 to-blue-600">Hierarki Teks</span></h2>
                            </div>

                            <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-90 text-sm md:text-base leading-relaxed space-y-4 text-justify">
                                <p>Tipografi adalah pengaturan tampilan teks agar informasi lebih mudah dibaca. Dalam halaman web, teks tidak hanya ditampilkan sebagai isi, tetapi juga disusun agar pembaca dapat mengenali bagian yang paling penting terlebih dahulu.</p>
                                <p>Hierarki tipografi membantu membedakan peran teks. Judul utama biasanya dibuat paling besar dan tebal. Nama produk atau subjudul berada di bawahnya. Deskripsi dibuat lebih kecil dan ringan. Keterangan tambahan menggunakan ukuran yang lebih kecil agar tidak mengganggu fokus utama.</p>
                                <p>Pada Tailwind CSS, hierarki tersebut dapat dibuat dengan class seperti <span class="hl-term">text-3xl</span>, <span class="hl-term">font-bold</span>, <span class="hl-term">text-base</span>, dan <span class="hl-term">text-sm</span>. Class tersebut membuat tampilan teks lebih terarah tanpa menulis CSS manual.</p>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                <div class="code-adaptive border border-adaptive rounded-2xl overflow-hidden">
                                    <div class="px-4 py-3 border-b border-adaptive flex items-center justify-between">
                                        <span class="font-mono text-xs font-bold text-muted">Contoh struktur teks</span>
                                        <span class="text-[10px] uppercase tracking-widest text-cyan-500 font-bold">Hierarchy</span>
                                    </div>
                                    <pre class="p-5 overflow-auto custom-scrollbar text-xs md:text-sm font-mono leading-relaxed"><code><span class="code-line"><span class="tag">&lt;section</span> <span class="attr">class</span>=<span class="str">"space-y-2"</span><span class="tag">&gt;</span></span>
<span class="code-line">  <span class="tag">&lt;p</span> <span class="attr">class</span>=<span class="str">"text-sm text-slate-500"</span><span class="tag">&gt;</span>Kategori Produk<span class="tag">&lt;/p&gt;</span></span>
<span class="code-line">  <span class="tag">&lt;h1</span> <span class="attr">class</span>=<span class="str">"text-3xl font-bold"</span><span class="tag">&gt;</span>Sepatu Kanvas<span class="tag">&lt;/h1&gt;</span></span>
<span class="code-line">  <span class="tag">&lt;p</span> <span class="attr">class</span>=<span class="str">"text-base text-slate-600"</span><span class="tag">&gt;</span>Sepatu ringan untuk harian.<span class="tag">&lt;/p&gt;</span></span>
<span class="code-line"><span class="tag">&lt;/section&gt;</span></span></code></pre>
                                </div>

                                <div class="card-adaptive border rounded-2xl p-5 flex flex-col justify-center">
                                    <h3 class="font-bold text-heading mb-4">Cara membacanya</h3>
                                    <div class="space-y-3 text-sm">
                                        <div class="flex gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 flex items-center justify-center font-bold shrink-0">1</div>
                                            <p class="text-muted leading-relaxed"><code>text-3xl</code> membuat judul lebih besar dibanding teks lain.</p>
                                        </div>
                                        <div class="flex gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold shrink-0">2</div>
                                            <p class="text-muted leading-relaxed"><code>font-bold</code> membuat judul lebih tegas dan mudah dikenali.</p>
                                        </div>
                                        <div class="flex gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold shrink-0">3</div>
                                            <p class="text-muted leading-relaxed"><code>text-slate-600</code> membuat deskripsi lebih lembut dari judul.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="sim-bg-adaptive border border-adaptive rounded-2xl overflow-hidden shadow-xl">
                                <div class="bg-cyan-600/95 dark:bg-cyan-900/95 text-white p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3 border-b border-cyan-400/50">
                                    <div class="text-xs font-bold uppercase tracking-widest">Simulasi 1 — Pilih Gaya Judul Produk</div>
                                    <p class="text-[11px] opacity-90 leading-relaxed m-0 md:text-right max-w-xl">Pilih kombinasi class untuk melihat apakah judul produk terlihat lemah, sedang, atau paling menonjol.</p>
                                </div>
                                <div class="grid grid-cols-1 lg:grid-cols-2 min-h-[360px]">
                                    <div class="code-adaptive border-b lg:border-b-0 lg:border-r border-adaptive p-5 space-y-3">
                                        <button onclick="setTitleStyle('text-sm font-medium')" class="title-style-btn w-full text-left px-4 py-3 rounded-xl border border-adaptive bg-white dark:bg-black/20 hover:border-cyan-400 transition text-xs font-bold">text-sm font-medium</button>
                                        <button onclick="setTitleStyle('text-base text-slate-600')" class="title-style-btn w-full text-left px-4 py-3 rounded-xl border border-adaptive bg-white dark:bg-black/20 hover:border-cyan-400 transition text-xs font-bold">text-base text-slate-600</button>
                                        <button onclick="setTitleStyle('text-3xl font-bold')" class="title-style-btn w-full text-left px-4 py-3 rounded-xl border border-cyan-400 bg-cyan-50 dark:bg-cyan-500/10 text-cyan-700 dark:text-cyan-300 transition text-xs font-bold">text-3xl font-bold</button>
                                        <button onclick="setTitleStyle('leading-7 text-center')" class="title-style-btn w-full text-left px-4 py-3 rounded-xl border border-adaptive bg-white dark:bg-black/20 hover:border-cyan-400 transition text-xs font-bold">leading-7 text-center</button>
                                        <pre id="title-code" class="mt-4 bg-white dark:bg-black/30 border border-adaptive rounded-xl p-4 overflow-auto custom-scrollbar text-xs font-mono leading-relaxed"><code>&lt;h1 class="text-3xl font-bold"&gt;Sepatu Kanvas&lt;/h1&gt;</code></pre>
                                    </div>
                                    <div class="bg-slate-100 dark:bg-slate-950 flex items-center justify-center p-6">
                                        <article class="w-full max-w-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-2xl p-6 shadow-lg">
                                            <p class="text-xs uppercase tracking-widest font-bold text-cyan-600 mb-2">Produk Pilihan</p>
                                            <h1 id="title-preview" class="text-3xl font-bold text-slate-900 dark:text-white transition-all duration-300">Sepatu Kanvas</h1>
                                            <p class="mt-3 text-sm text-slate-600 dark:text-slate-300 leading-relaxed">Sepatu ringan untuk kegiatan harian dan belajar di kelas.</p>
                                        </article>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="section-47" class="lesson-section scroll-mt-32" data-lesson-id="47">
                        <div class="space-y-8">
                            <div class="space-y-4 border-l-4 border-blue-500 pl-4 md:pl-6">
                                <span class="text-blue-600 dark:text-blue-400 font-mono text-xs uppercase tracking-widest">Lesson 3.1.2</span>
                                <h2 class="text-3xl md:text-5xl font-black text-heading leading-tight">Ukuran dan <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-500 to-cyan-600">Ketebalan Teks</span></h2>
                            </div>

                            <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-90 text-sm md:text-base leading-relaxed space-y-4 text-justify">
                                <p>Ukuran teks menentukan besar kecilnya perhatian pembaca. Class <code>text-sm</code> cocok untuk keterangan kecil, <code>text-base</code> cocok untuk isi paragraf, sedangkan <code>text-3xl</code> cocok untuk judul utama pada komponen sederhana.</p>
                                <p>Ketebalan teks diatur dengan class <code>font-*</code>. Class <code>font-medium</code> memberi penekanan ringan, sedangkan <code>font-bold</code> membuat teks lebih kuat. Untuk judul produk yang harus paling mudah dikenali, kombinasi <span class="hl-term">text-3xl font-bold</span> lebih tepat dibanding ukuran kecil atau teks biasa.</p>
                            </div>

                            <div class="sim-bg-adaptive border border-adaptive rounded-2xl overflow-hidden shadow-xl">
                                <div class="bg-blue-600/95 dark:bg-blue-900/95 text-white p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3 border-b border-blue-400/50">
                                    <div class="text-xs font-bold uppercase tracking-widest">Simulasi 2 — Ukuran dan Ketebalan</div>
                                    <p class="text-[11px] opacity-90 leading-relaxed m-0 md:text-right max-w-xl">Atur ukuran dan ketebalan judul. Kode class dan preview akan berubah secara langsung.</p>
                                </div>
                                <div class="grid grid-cols-1 lg:grid-cols-2 min-h-[360px]">
                                    <div class="code-adaptive border-b lg:border-b-0 lg:border-r border-adaptive p-5 space-y-5">
                                        <div>
                                            <label class="block text-[10px] font-bold uppercase tracking-widest text-blue-600 dark:text-blue-400 mb-2">Ukuran Teks</label>
                                            <div class="grid grid-cols-3 gap-2">
                                                <button onclick="setSizeClass('text-sm')" class="px-3 py-2 rounded-lg bg-white dark:bg-black/20 border border-adaptive text-xs font-bold">text-sm</button>
                                                <button onclick="setSizeClass('text-xl')" class="px-3 py-2 rounded-lg bg-white dark:bg-black/20 border border-adaptive text-xs font-bold">text-xl</button>
                                                <button onclick="setSizeClass('text-3xl')" class="px-3 py-2 rounded-lg bg-blue-600 text-white border border-blue-400 text-xs font-bold">text-3xl</button>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold uppercase tracking-widest text-sky-600 dark:text-sky-400 mb-2">Ketebalan</label>
                                            <div class="grid grid-cols-3 gap-2">
                                                <button onclick="setWeightClass('font-normal')" class="px-3 py-2 rounded-lg bg-white dark:bg-black/20 border border-adaptive text-xs font-bold">normal</button>
                                                <button onclick="setWeightClass('font-medium')" class="px-3 py-2 rounded-lg bg-white dark:bg-black/20 border border-adaptive text-xs font-bold">medium</button>
                                                <button onclick="setWeightClass('font-bold')" class="px-3 py-2 rounded-lg bg-blue-600 text-white border border-blue-400 text-xs font-bold">bold</button>
                                            </div>
                                        </div>
                                        <div id="size-code" class="bg-white dark:bg-black/30 border border-adaptive rounded-xl p-4 overflow-auto custom-scrollbar text-xs font-mono">class="text-3xl font-bold"</div>
                                    </div>
                                    <div class="bg-slate-100 dark:bg-slate-950 flex items-center justify-center p-6">
                                        <div class="w-full max-w-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-2xl p-6 shadow-md">
                                            <h2 id="size-preview" class="text-3xl font-bold text-slate-900 dark:text-white transition-all duration-300">Sepatu Kanvas</h2>
                                            <p class="mt-3 text-sm text-slate-600 dark:text-slate-300">Judul harus tampak lebih kuat daripada deskripsi.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gradient-to-r from-blue-50 to-transparent dark:from-blue-900/20 dark:to-transparent border-l-4 border-blue-500 p-4 rounded-r-xl">
                                <h5 class="text-xs font-bold text-blue-700 dark:text-blue-400 mb-1">Kesimpulan</h5>
                                <p class="text-[11px] text-slate-700 dark:text-slate-300 leading-relaxed m-0 text-justify">Ukuran dan ketebalan teks dipakai untuk mengatur fokus. Judul utama dibuat paling kuat, sedangkan deskripsi dibuat lebih ringan agar informasi tidak saling berebut perhatian.</p>
                            </div>
                        </div>
                    </section>

                    <section id="section-48" class="lesson-section scroll-mt-32" data-lesson-id="48">
                        <div class="space-y-8">
                            <div class="space-y-4 border-l-4 border-sky-500 pl-4 md:pl-6">
                                <span class="text-sky-600 dark:text-sky-400 font-mono text-xs uppercase tracking-widest">Lesson 3.1.3</span>
                                <h2 class="text-3xl md:text-5xl font-black text-heading leading-tight">Jarak Baris dan <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-500 to-cyan-600">Perataan Teks</span></h2>
                            </div>

                            <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-90 text-sm md:text-base leading-relaxed space-y-4 text-justify">
                                <p>Paragraf yang terdiri atas beberapa baris perlu diberi jarak antarbaris yang cukup. Jika baris teks terlalu rapat, pembaca akan lebih cepat lelah. Dalam Tailwind CSS, jarak antarbaris diatur menggunakan class <span class="hl-term">leading-*</span>.</p>
                                <p>Class <code>leading-7</code> dapat membuat paragraf lebih nyaman dibaca karena jarak antarbaris menjadi lebih longgar. Perataan teks juga perlu dipilih sesuai konteks. <code>text-left</code> cocok untuk paragraf panjang, sedangkan <code>text-center</code> lebih cocok untuk judul pendek atau teks pada hero section.</p>
                            </div>

                            <div class="sim-bg-adaptive border border-adaptive rounded-2xl overflow-hidden shadow-xl">
                                <div class="bg-sky-600/95 dark:bg-sky-900/95 text-white p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3 border-b border-sky-400/50">
                                    <div class="text-xs font-bold uppercase tracking-widest">Simulasi 3 — Leading dan Perataan</div>
                                    <p class="text-[11px] opacity-90 leading-relaxed m-0 md:text-right max-w-xl">Ubah jarak antarbaris dan perataan untuk melihat perbedaan keterbacaan paragraf.</p>
                                </div>
                                <div class="grid grid-cols-1 lg:grid-cols-2 min-h-[380px]">
                                    <div class="code-adaptive border-b lg:border-b-0 lg:border-r border-adaptive p-5 space-y-5">
                                        <div>
                                            <label class="block text-[10px] font-bold uppercase tracking-widest text-sky-600 dark:text-sky-400 mb-2">Jarak Antarbaris</label>
                                            <div class="grid grid-cols-3 gap-2">
                                                <button onclick="setLeadingClass('leading-none')" class="px-3 py-2 rounded-lg bg-white dark:bg-black/20 border border-adaptive text-xs font-bold">leading-none</button>
                                                <button onclick="setLeadingClass('leading-normal')" class="px-3 py-2 rounded-lg bg-white dark:bg-black/20 border border-adaptive text-xs font-bold">normal</button>
                                                <button onclick="setLeadingClass('leading-7')" class="px-3 py-2 rounded-lg bg-sky-600 text-white border border-sky-400 text-xs font-bold">leading-7</button>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold uppercase tracking-widest text-cyan-600 dark:text-cyan-400 mb-2">Perataan</label>
                                            <div class="grid grid-cols-3 gap-2">
                                                <button onclick="setAlignText('text-left')" class="px-3 py-2 rounded-lg bg-sky-600 text-white border border-sky-400 text-xs font-bold">left</button>
                                                <button onclick="setAlignText('text-center')" class="px-3 py-2 rounded-lg bg-white dark:bg-black/20 border border-adaptive text-xs font-bold">center</button>
                                                <button onclick="setAlignText('text-right')" class="px-3 py-2 rounded-lg bg-white dark:bg-black/20 border border-adaptive text-xs font-bold">right</button>
                                            </div>
                                        </div>
                                        <div id="leading-code" class="bg-white dark:bg-black/30 border border-adaptive rounded-xl p-4 overflow-auto custom-scrollbar text-xs font-mono">class="text-slate-600 leading-7 text-left"</div>
                                    </div>
                                    <div class="bg-slate-100 dark:bg-slate-950 flex items-center justify-center p-6">
                                        <article class="w-full max-w-md bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-2xl p-6 shadow-md">
                                            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3">Deskripsi Produk</h3>
                                            <p id="leading-preview" class="text-sm text-slate-600 dark:text-slate-300 leading-7 text-left transition-all duration-300">
                                                Sepatu kanvas ini dirancang untuk kegiatan harian. Bahannya ringan, mudah dipadukan dengan pakaian sekolah atau kasual, dan nyaman digunakan dalam waktu lama.
                                            </p>
                                        </article>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="section-49" class="lesson-section scroll-mt-32" data-lesson-id="49">
                        <div class="space-y-8">
                            <div class="space-y-4 border-l-4 border-emerald-500 pl-4 md:pl-6">
                                <span class="text-emerald-600 dark:text-emerald-400 font-mono text-xs uppercase tracking-widest">Lesson 3.1.4</span>
                                <h2 class="text-3xl md:text-5xl font-black text-heading leading-tight">Warna Teks dan <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-500 to-cyan-600">Jenis Huruf</span></h2>
                            </div>

                            <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-90 text-sm md:text-base leading-relaxed space-y-4 text-justify">
                                <p>Warna teks membantu pembaca membedakan fungsi informasi. Teks utama biasanya memakai warna gelap seperti <code>text-slate-900</code>. Teks pendukung menggunakan warna lebih lembut seperti <code>text-slate-600</code>. Informasi penting dapat dibuat lebih menonjol, misalnya dengan warna biru pada konteks tertentu.</p>
                                <p>Tailwind juga menyediakan jenis huruf dasar. Class <code>font-sans</code> cocok untuk antarmuka umum, <code>font-serif</code> memberi kesan formal atau editorial, sedangkan <code>font-mono</code> cocok untuk kode atau teks berjarak tetap. Pemilihan jenis huruf sebaiknya mengikuti fungsi teks, bukan sekadar variasi tampilan.</p>
                            </div>

                            <div class="sim-bg-adaptive border border-adaptive rounded-2xl overflow-hidden shadow-xl">
                                <div class="bg-emerald-600/95 dark:bg-emerald-900/95 text-white p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3 border-b border-emerald-400/50">
                                    <div class="text-xs font-bold uppercase tracking-widest">Simulasi 4 — Warna Teks pada Kartu Produk</div>
                                    <p class="text-[11px] opacity-90 leading-relaxed m-0 md:text-right max-w-xl">Pilih warna harga dan jenis huruf. Kode class akan ikut berubah.</p>
                                </div>
                                <div class="grid grid-cols-1 lg:grid-cols-2 min-h-[380px]">
                                    <div class="code-adaptive border-b lg:border-b-0 lg:border-r border-adaptive p-5 space-y-5">
                                        <div>
                                            <label class="block text-[10px] font-bold uppercase tracking-widest text-emerald-600 dark:text-emerald-400 mb-2">Warna Harga</label>
                                            <div class="grid grid-cols-3 gap-2">
                                                <button onclick="setPriceColor('text-slate-900')" class="px-3 py-2 rounded-lg bg-emerald-600 text-white border border-emerald-400 text-xs font-bold">slate-900</button>
                                                <button onclick="setPriceColor('text-blue-600')" class="px-3 py-2 rounded-lg bg-white dark:bg-black/20 border border-adaptive text-xs font-bold">blue-600</button>
                                                <button onclick="setPriceColor('text-slate-600')" class="px-3 py-2 rounded-lg bg-white dark:bg-black/20 border border-adaptive text-xs font-bold">slate-600</button>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold uppercase tracking-widest text-cyan-600 dark:text-cyan-400 mb-2">Jenis Huruf</label>
                                            <div class="grid grid-cols-3 gap-2">
                                                <button onclick="setFontFamilyClass('font-sans')" class="px-3 py-2 rounded-lg bg-emerald-600 text-white border border-emerald-400 text-xs font-bold">sans</button>
                                                <button onclick="setFontFamilyClass('font-serif')" class="px-3 py-2 rounded-lg bg-white dark:bg-black/20 border border-adaptive text-xs font-bold">serif</button>
                                                <button onclick="setFontFamilyClass('font-mono')" class="px-3 py-2 rounded-lg bg-white dark:bg-black/20 border border-adaptive text-xs font-bold">mono</button>
                                            </div>
                                        </div>
                                        <pre id="color-code" class="bg-white dark:bg-black/30 border border-adaptive rounded-xl p-4 overflow-auto custom-scrollbar text-xs font-mono leading-relaxed"><code>&lt;p class="text-slate-900 font-sans"&gt;Rp150.000&lt;/p&gt;</code></pre>
                                    </div>
                                    <div class="bg-slate-100 dark:bg-slate-950 flex items-center justify-center p-6">
                                        <article id="font-preview-wrap" class="w-full max-w-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-2xl p-6 shadow-md font-sans transition-all duration-300">
                                            <p class="text-xs uppercase tracking-widest font-bold text-emerald-600 mb-2">Produk Pilihan</p>
                                            <h3 class="text-2xl font-bold text-slate-900 dark:text-white">Sepatu Kanvas</h3>
                                            <p class="text-sm text-slate-600 dark:text-slate-300 mt-2 leading-7">Sepatu ringan untuk kegiatan harian.</p>
                                            <p id="price-preview" class="text-slate-900 dark:text-white font-bold mt-4 text-lg transition-all duration-300">Rp150.000</p>
                                        </article>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gradient-to-r from-emerald-50 to-transparent dark:from-emerald-900/20 dark:to-transparent border-l-4 border-emerald-500 p-4 rounded-r-xl">
                                <h5 class="text-xs font-bold text-emerald-700 dark:text-emerald-400 mb-1">Kesimpulan</h5>
                                <p class="text-[11px] text-slate-700 dark:text-slate-300 leading-relaxed m-0 text-justify">Warna teks membantu membedakan peran informasi. Teks gelap cocok untuk informasi utama, teks abu-abu cocok untuk deskripsi, dan warna yang lebih kuat dapat dipakai untuk informasi yang ingin ditonjolkan.</p>
                            </div>
                        </div>
                    </section>

                    <section id="section-50" class="lesson-section scroll-mt-32" data-lesson-id="50" data-type="activity">
                        <div class="space-y-8">
                            <div class="space-y-4 border-l-4 border-cyan-500 pl-4 md:pl-6">
                                <span class="text-cyan-600 dark:text-cyan-400 font-mono text-xs uppercase tracking-widest">Aktivitas 3.1</span>
                                <h2 class="text-3xl md:text-5xl font-black text-heading leading-tight">Cek Pemahaman <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-500 to-blue-600">Tipografi</span></h2>
                            </div>

                            <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-90 text-sm md:text-base leading-relaxed text-justify">
                                <p>Pilihlah jawaban yang paling tepat dengan memberi tanda silang pada pilihan A, B, C, atau D. Soal dibuat sesuai materi tipografi, yaitu ukuran dan ketebalan judul, jarak antarbaris paragraf, serta warna teks pada informasi produk.</p>
                            </div>

                            <div class="card-adaptive border rounded-2xl overflow-hidden shadow-xl relative">
                                <div id="lockOverlay" class="hidden absolute inset-0 z-20 bg-white/90 dark:bg-slate-950/90 backdrop-blur-sm items-center justify-center p-6">
                                    <div class="max-w-md text-center">
                                        <div class="w-14 h-14 rounded-full bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mx-auto mb-4 text-2xl">✓</div>
                                        <h3 class="font-black text-heading mb-2">Aktivitas Sudah Selesai</h3>
                                        <p class="text-sm text-muted leading-relaxed">Jawaban aktivitas telah tersimpan. Anda dapat melanjutkan ke subbab berikutnya.</p>
                                    </div>
                                </div>

                                <div class="bg-cyan-600/95 dark:bg-cyan-900/95 text-white p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                                    <div class="text-xs font-bold uppercase tracking-widest">Evaluasi Interaktif</div>
                                    <p class="text-[11px] opacity-90 leading-relaxed m-0 md:text-right max-w-xl">Jawab semua soal, lalu tekan tombol periksa untuk melihat skor dan pembahasan.</p>
                                </div>

                                <div id="activityForm" class="p-4 md:p-6 space-y-4 max-h-[620px] overflow-y-auto custom-scrollbar">
                                    <div class="activity-question card-adaptive border rounded-xl p-5" data-answer="c">
                                        <p class="text-sm font-bold text-heading mb-4">1. Sebuah judul utama pada produk perlu dibuat paling menonjol agar mudah dikenali pembaca. Kombinasi class yang paling tepat adalah ....</p>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                            <button onclick="chooseActivity(this, 'q1', 'a')" class="choice-card text-left border border-adaptive rounded-lg p-3 text-xs hover:border-cyan-500 transition">A. <code>text-sm font-medium</code></button>
                                            <button onclick="chooseActivity(this, 'q1', 'b')" class="choice-card text-left border border-adaptive rounded-lg p-3 text-xs hover:border-cyan-500 transition">B. <code>text-base text-slate-600</code></button>
                                            <button onclick="chooseActivity(this, 'q1', 'c')" class="choice-card text-left border border-adaptive rounded-lg p-3 text-xs hover:border-cyan-500 transition">C. <code>text-3xl font-bold</code></button>
                                            <button onclick="chooseActivity(this, 'q1', 'd')" class="choice-card text-left border border-adaptive rounded-lg p-3 text-xs hover:border-cyan-500 transition">D. <code>leading-7 text-center</code></button>
                                        </div>
                                    </div>

                                    <div class="activity-question card-adaptive border rounded-xl p-5" data-answer="a">
                                        <p class="text-sm font-bold text-heading mb-4">2. Sebuah paragraf terdiri dari beberapa baris. Teksnya sudah menggunakan warna yang sesuai, tetapi masih terlihat padat dan kurang nyaman dibaca. Perbaikan yang paling tepat yaitu ....</p>
                                        <div class="grid grid-cols-1 gap-3">
                                            <button onclick="chooseActivity(this, 'q2', 'a')" class="choice-card text-left border border-adaptive rounded-lg p-3 text-xs hover:border-cyan-500 transition">A. Menambahkan <code>leading-7</code> agar jarak antarbaris lebih nyaman</button>
                                            <button onclick="chooseActivity(this, 'q2', 'b')" class="choice-card text-left border border-adaptive rounded-lg p-3 text-xs hover:border-cyan-500 transition">B. Menambahkan <code>text-center</code> agar semua paragraf berada di tengah</button>
                                            <button onclick="chooseActivity(this, 'q2', 'c')" class="choice-card text-left border border-adaptive rounded-lg p-3 text-xs hover:border-cyan-500 transition">C. Menambahkan <code>font-bold</code> agar semua isi paragraf menjadi tebal</button>
                                            <button onclick="chooseActivity(this, 'q2', 'd')" class="choice-card text-left border border-adaptive rounded-lg p-3 text-xs hover:border-cyan-500 transition">D. Menambahkan <code>font-mono</code> agar paragraf terlihat seperti kode</button>
                                        </div>
                                    </div>

                                    <div class="activity-question card-adaptive border rounded-xl p-5" data-answer="a">
                                        <p class="text-sm font-bold text-heading mb-4">3. Perhatikan kebutuhan berikut: judul produk menggunakan teks gelap dan tebal, deskripsi menggunakan teks abu-abu, dan harga dibuat dengan warna hitam. Susunan class yang paling sesuai untuk harga adalah ....</p>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                            <button onclick="chooseActivity(this, 'q3', 'a')" class="choice-card text-left border border-adaptive rounded-lg p-3 text-xs hover:border-cyan-500 transition">A. <code>text-slate-900</code></button>
                                            <button onclick="chooseActivity(this, 'q3', 'b')" class="choice-card text-left border border-adaptive rounded-lg p-3 text-xs hover:border-cyan-500 transition">B. <code>text-blue-600</code></button>
                                            <button onclick="chooseActivity(this, 'q3', 'c')" class="choice-card text-left border border-adaptive rounded-lg p-3 text-xs hover:border-cyan-500 transition">C. <code>text-slate-600</code></button>
                                            <button onclick="chooseActivity(this, 'q3', 'd')" class="choice-card text-left border border-adaptive rounded-lg p-3 text-xs hover:border-cyan-500 transition">D. <code>text-base</code></button>
                                        </div>
                                    </div>
                                </div>

                                <div class="border-t border-adaptive p-4 md:p-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
                                    <div>
                                        <p id="activity-status" class="text-xs font-bold text-muted">Belum diperiksa.</p>
                                        <p id="activity-score" class="text-sm font-black text-heading mt-1">Skor: -</p>
                                    </div>
                                    <div class="flex flex-col sm:flex-row gap-2">
                                        <button id="resetBtn" onclick="resetActivity()" class="px-5 py-3 rounded-xl border border-adaptive text-heading font-bold text-xs hover:bg-slate-100 dark:hover:bg-white/5 transition">Ulangi</button>
                                        <button id="submitBtn" onclick="checkActivity()" class="px-5 py-3 rounded-xl bg-gradient-to-r from-cyan-600 to-blue-600 text-white font-bold text-xs shadow-lg hover:shadow-cyan-500/30 transition">Periksa Jawaban</button>
                                    </div>
                                </div>

                                <div id="activity-analysis" class="hidden border-t border-adaptive p-4 md:p-6 bg-slate-50 dark:bg-black/20">
                                    <h3 class="font-bold text-heading mb-3">Pembahasan Singkat</h3>
                                    <div class="space-y-2 text-xs text-muted leading-relaxed">
                                        <p><strong>1.</strong> <code>text-3xl font-bold</code> paling tepat karena judul utama perlu ukuran besar dan ketebalan kuat.</p>
                                        <p><strong>2.</strong> <code>leading-7</code> memperbaiki jarak antarbaris sehingga paragraf panjang lebih nyaman dibaca.</p>
                                        <p><strong>3.</strong> Jika harga diminta berwarna hitam atau gelap, <code>text-slate-900</code> paling sesuai. <code>text-slate-600</code> lebih cocok untuk deskripsi, sedangkan <code>text-base</code> hanya mengatur ukuran.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                </article>

                <div class="mt-20 md:mt-32 pt-8 border-t border-adaptive flex flex-col sm:flex-row justify-between items-center gap-4">
                    <a href="{{ \Illuminate\Support\Facades\Route::has('courses.responsive') ? route('courses.responsive') : '#' }}" class="group flex items-center gap-4 text-muted hover:text-heading transition-colors w-full sm:w-auto justify-center sm:justify-start">
                        <div class="w-10 h-10 md:w-12 md:h-12 rounded-full border border-adaptive flex items-center justify-center group-hover:bg-slate-100 dark:group-hover:bg-white/5 transition-colors shrink-0">
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        </div>
                        <div class="text-left">
                            <div class="text-[10px] uppercase tracking-widest font-bold opacity-60">Sebelumnya</div>
                            <div class="font-black text-xs md:text-sm line-clamp-1">Layout Responsif</div>
                        </div>
                    </a>

                    <div id="nextChapterBtn" class="group flex items-center gap-4 text-right text-muted cursor-not-allowed opacity-50 pointer-events-none transition-all duration-500 w-full sm:w-auto justify-center sm:justify-end">
                        <div class="text-right">
                            <div id="nextLabel" class="text-[10px] uppercase tracking-widest font-bold opacity-60">Terkunci</div>
                            <div class="font-black text-xs md:text-sm line-clamp-1">Warna dan Latar Belakang</div>
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
    window.LESSON_IDS = [46, 47, 48, 49, 50];
    let rawCompletedIds = {!! json_encode($completedLessonIds ?? []) !!};
    window.COMPLETED_IDS = rawCompletedIds.map(id => Number(id));
    let completedSet = new Set(window.COMPLETED_IDS);
    const ACTIVITY_LESSON_ID = 50;
    let activityCompleted = {!! ($activityCompleted ?? false) ? 'true' : 'false' !!};

    let titleClass = 'text-3xl font-bold';
    let sizeClass = 'text-3xl';
    let weightClass = 'font-bold';
    let leadingClass = 'leading-7';
    let alignTextClass = 'text-left';
    let priceColorClass = 'text-slate-900';
    let fontFamilyClass = 'font-sans';
    const activityAnswers = {};

    document.addEventListener('DOMContentLoaded', () => {
        initScrollSpy();
        initVisualEffects();
        initLessonObserver();
        updateProgressUI(false);
        setTitleStyle(titleClass);
        updateSizePreview();
        updateLeadingPreview();
        updateColorPreview();

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

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            const response = await fetch('{{ route("lesson.complete") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
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
                return true;
            }
        } catch(e) {
            console.error('Network Error:', e);
        }
        return false;
    }

    function initLessonObserver() {
        const root = document.getElementById('mainScroll');
        const obs = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const id = Number(entry.target.dataset.lessonId);
                    if (typeof highlightAnchor === 'function') highlightAnchor(entry.target.id);
                    if (id && entry.target.dataset.type !== 'activity' && !completedSet.has(id)) {
                        saveLessonToDB(id);
                    }
                }
            });
        }, { threshold: 0.12, rootMargin: "0px 0px -80px 0px", root });
        document.querySelectorAll('.lesson-section').forEach(s => obs.observe(s));
    }

    function initScrollSpy() {
        const root = document.getElementById('mainScroll');
        if (!root) return;
        root.addEventListener('scroll', () => {
            let current = '';
            document.querySelectorAll('.lesson-section').forEach(section => {
                if (root.scrollTop >= section.offsetTop - 240) current = section.id;
            });
            if (current) highlightAnchor(current);
        });

        document.querySelectorAll('.nav-item, .sidebar-anchor').forEach(item => {
            item.addEventListener('click', (e) => {
                const target = item.getAttribute('data-target') || item.dataset.target;
                if (target && target.startsWith('#')) {
                    const el = document.querySelector(target);
                    if (el) {
                        e.preventDefault();
                        el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                }
            });
        });
    }

    function highlightAnchor(id) {
        document.querySelectorAll('.nav-item').forEach(item => item.classList.remove('active'));
        const active = document.querySelector(`.nav-item[data-target="#${id}"]`);
        if (active) active.classList.add('active');
    }

    function setTitleStyle(cls) {
        titleClass = cls;
        const preview = document.getElementById('title-preview');
        const code = document.getElementById('title-code');
        if (!preview || !code) return;
        preview.className = `${cls} text-slate-900 dark:text-white transition-all duration-300`;
        code.innerHTML = `<code>&lt;h1 class="${cls}"&gt;Sepatu Kanvas&lt;/h1&gt;</code>`;

        document.querySelectorAll('.title-style-btn').forEach(btn => {
            btn.classList.remove('border-cyan-400','bg-cyan-50','dark:bg-cyan-500/10','text-cyan-700','dark:text-cyan-300');
            btn.classList.add('border-adaptive');
            if (btn.textContent.trim() === cls) {
                btn.classList.add('border-cyan-400','bg-cyan-50','dark:bg-cyan-500/10','text-cyan-700','dark:text-cyan-300');
                btn.classList.remove('border-adaptive');
            }
        });
    }

    function setSizeClass(cls) {
        sizeClass = cls;
        updateSizePreview();
    }

    function setWeightClass(cls) {
        weightClass = cls;
        updateSizePreview();
    }

    function updateSizePreview() {
        const preview = document.getElementById('size-preview');
        const code = document.getElementById('size-code');
        if (!preview || !code) return;
        preview.className = `${sizeClass} ${weightClass} text-slate-900 dark:text-white transition-all duration-300`;
        code.textContent = `class="${sizeClass} ${weightClass}"`;
    }

    function setLeadingClass(cls) {
        leadingClass = cls;
        updateLeadingPreview();
    }

    function setAlignText(cls) {
        alignTextClass = cls;
        updateLeadingPreview();
    }

    function updateLeadingPreview() {
        const preview = document.getElementById('leading-preview');
        const code = document.getElementById('leading-code');
        if (!preview || !code) return;
        preview.className = `text-sm text-slate-600 dark:text-slate-300 ${leadingClass} ${alignTextClass} transition-all duration-300`;
        code.textContent = `class="text-slate-600 ${leadingClass} ${alignTextClass}"`;
    }

    function setPriceColor(cls) {
        priceColorClass = cls;
        updateColorPreview();
    }

    function setFontFamilyClass(cls) {
        fontFamilyClass = cls;
        updateColorPreview();
    }

    function updateColorPreview() {
        const price = document.getElementById('price-preview');
        const wrap = document.getElementById('font-preview-wrap');
        const code = document.getElementById('color-code');
        if (!price || !wrap || !code) return;

        wrap.className = `w-full max-w-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-2xl p-6 shadow-md ${fontFamilyClass} transition-all duration-300`;

        let darkPrice = priceColorClass === 'text-slate-900' ? 'dark:text-white' : '';
        price.className = `${priceColorClass} ${darkPrice} font-bold mt-4 text-lg transition-all duration-300`;
        code.innerHTML = `<code>&lt;p class="${priceColorClass} ${fontFamilyClass}"&gt;Rp150.000&lt;/p&gt;</code>`;
    }

    function chooseActivity(btn, q, ans) {
        if (activityCompleted) return;
        activityAnswers[q] = ans;
        const group = btn.closest('.activity-question');
        group.querySelectorAll('.choice-card').forEach(b => {
            b.classList.remove('selected', 'correct', 'wrong');
        });
        btn.classList.add('selected');
    }

    function resetActivity() {
        if (activityCompleted) return;
        Object.keys(activityAnswers).forEach(key => delete activityAnswers[key]);
        document.querySelectorAll('.choice-card').forEach(btn => btn.classList.remove('selected', 'correct', 'wrong'));
        document.getElementById('activity-status').innerText = 'Belum diperiksa.';
        document.getElementById('activity-status').className = 'text-xs font-bold text-muted';
        document.getElementById('activity-score').innerText = 'Skor: -';
        document.getElementById('activity-analysis').classList.add('hidden');
    }

    async function checkActivity() {
        if (activityCompleted) return;
        const correct = { q1:'c', q2:'a', q3:'a' };
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
        Object.keys(correct).forEach((qKey, index) => {
            const group = document.querySelectorAll('.activity-question')[index];
            const answer = correct[qKey];
            group.querySelectorAll('.choice-card').forEach(btn => {
                const onclickValue = btn.getAttribute('onclick') || '';
                btn.classList.remove('correct', 'wrong');
                if (onclickValue.includes(`'${answer}'`)) btn.classList.add('correct');
                if (onclickValue.includes(`'${activityAnswers[qKey]}'`) && activityAnswers[qKey] !== answer) btn.classList.add('wrong');
            });
            if (activityAnswers[qKey] === answer) score++;
        });

        scoreLabel.innerText = `Skor: ${score}/${total}`;
        document.getElementById('activity-analysis').classList.remove('hidden');

        if (score === total) {
            status.innerText = 'Jawaban benar semua. Progress aktivitas disimpan.';
            status.className = 'text-xs font-bold text-emerald-600 dark:text-emerald-400';
            submit.innerText = 'Menyimpan...';
            submit.disabled = true;
            const saved = await saveLessonToDB(ACTIVITY_LESSON_ID);
            if (saved) {
                activityCompleted = true;
                lockActivityUI();
                unlockNextChapter();
            } else {
                status.innerText = 'Jawaban benar, tetapi progress gagal disimpan. Coba periksa koneksi.';
                submit.innerText = 'Periksa Jawaban';
                submit.disabled = false;
            }
        } else {
            status.innerText = 'Masih ada jawaban yang belum tepat. Baca ulang bagian ukuran teks, bobot huruf, dan leading, lalu perbaiki jawaban dan periksa lagi.';
            status.className = 'text-xs font-bold text-amber-600 dark:text-amber-400';
        }
    }

    function lockActivityUI() {
        const overlay = document.getElementById('lockOverlay');
        if(overlay) overlay.classList.remove('hidden');
        const submit = document.getElementById('submitBtn');
        const reset = document.getElementById('resetBtn');
        if (submit) {
            submit.innerText = 'Selesai';
            submit.disabled = true;
            submit.classList.add('cursor-not-allowed', 'opacity-70');
        }
        if (reset) {
            reset.disabled = true;
            reset.classList.add('cursor-not-allowed', 'opacity-50');
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
            btn.onclick = () => window.location.href = "{{ \Illuminate\Support\Facades\Route::has('courses.backgrounds') ? route('courses.backgrounds') : '#' }}";
        }
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
</script>
@endsection
