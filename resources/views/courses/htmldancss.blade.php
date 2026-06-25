@extends('layouts.landing')
@section('title','Konsep Dasar HTML dan CSS')

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
                    <div class="w-8 h-8 rounded-lg bg-indigo-500/10 dark:bg-indigo-500/20 border border-indigo-500/20 flex items-center justify-center font-bold text-xs text-indigo-600 dark:text-indigo-400 shrink-0">1.1</div>
                    <div class="min-w-0">
                        <h1 class="text-sm font-bold text-heading line-clamp-1">Konsep Dasar HTML dan CSS</h1>
                        <p class="text-[10px] text-muted line-clamp-1">Struktur halaman, gaya tampilan, dan dasar layout web</p>
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
                        <p class="text-xs font-mono font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-[.25em] mb-3">Subbab 1.1</p>
                        <h2 class="text-3xl md:text-5xl font-black text-heading leading-tight mb-4">Konsep Dasar HTML dan CSS</h2>
                        <p class="text-sm md:text-base text-muted leading-relaxed max-w-3xl text-justify">
                            Pada subbab ini, pembelajar mempelajari dasar pembuatan halaman web melalui dua bagian utama, yaitu HTML sebagai penyusun struktur halaman dan CSS sebagai pengatur tampilan. Materi ini menjadi bekal sebelum masuk ke Tailwind CSS pada subbab berikutnya.
                        </p>
                    </div>

                    <h3 class="text-xl font-bold text-heading mt-10 mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Tujuan Pembelajaran
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="card-adaptive border rounded-xl p-5">
                            <div class="w-8 h-8 rounded-lg bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold mb-4">1</div>
                            <h4 class="text-sm font-bold text-heading mb-1">Menjelaskan HTML</h4>
                            <p class="text-[11px] text-muted leading-relaxed">Membedakan bagian <code>&lt;head&gt;</code> dan <code>&lt;body&gt;</code> pada dokumen HTML.</p>
                        </div>
                        <div class="card-adaptive border rounded-xl p-5">
                            <div class="w-8 h-8 rounded-lg bg-purple-500/10 text-purple-600 dark:text-purple-400 flex items-center justify-center font-bold mb-4">2</div>
                            <h4 class="text-sm font-bold text-heading mb-1">Menjelaskan CSS</h4>
                            <p class="text-[11px] text-muted leading-relaxed">Mengidentifikasi peran selector, properti, dan nilai dalam aturan CSS.</p>
                        </div>
                        <div class="card-adaptive border rounded-xl p-5">
                            <div class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold mb-4">3</div>
                            <h4 class="text-sm font-bold text-heading mb-1">Menghubungkan CSS</h4>
                            <p class="text-[11px] text-muted leading-relaxed">Menjelaskan penggunaan file CSS eksternal melalui tag <code>&lt;link&gt;</code>.</p>
                        </div>
                        <div class="card-adaptive border rounded-xl p-5">
                            <div class="w-8 h-8 rounded-lg bg-orange-500/10 text-orange-600 dark:text-orange-400 flex items-center justify-center font-bold mb-4">4</div>
                            <h4 class="text-sm font-bold text-heading mb-1">Menganalisis Tampilan</h4>
                            <p class="text-[11px] text-muted leading-relaxed">Membedakan padding, border, margin, dan jenis display sederhana.</p>
                        </div>
                    </div>
                </div>

                <article class="space-y-32 md:space-y-40">

                    <section id="section-1" class="lesson-section scroll-mt-32" data-lesson-id="1">
                        <div class="space-y-8">
                            <div class="space-y-4 border-l-4 border-indigo-500 pl-4 md:pl-6">
                                <span class="text-indigo-600 dark:text-indigo-400 font-mono text-xs uppercase tracking-widest">Lesson 1.1.1</span>
                                <h2 class="text-3xl md:text-5xl font-black text-heading leading-tight">HTML sebagai <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-purple-600">Struktur Halaman</span></h2>
                            </div>

                            <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-90 text-sm md:text-base leading-relaxed space-y-4 text-justify">
                                <p>HTML atau <em>HyperText Markup Language</em> digunakan untuk menyusun isi halaman web. Di dalam HTML, setiap bagian halaman ditulis menggunakan tag, misalnya judul, paragraf, gambar, tautan, dan tombol.</p>
                                <p>Dokumen HTML memiliki susunan dasar yang tetap. Bagian <span class="hl-term">&lt;head&gt;</span> berisi informasi halaman yang tidak tampil langsung sebagai konten utama, seperti judul tab browser dan pengaturan karakter. Bagian <span class="hl-term">&lt;body&gt;</span> berisi konten yang terlihat oleh pengguna, seperti teks, gambar, daftar, dan tombol.</p>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                <div class="code-adaptive border border-adaptive rounded-2xl overflow-hidden">
                                    <div class="px-4 py-3 border-b border-adaptive flex items-center justify-between">
                                        <span class="font-mono text-xs font-bold text-muted">index.html</span>
                                        <span class="text-[10px] uppercase tracking-widest text-indigo-500 font-bold">Struktur Dasar</span>
                                    </div>
                                    <pre class="p-5 overflow-auto custom-scrollbar text-xs md:text-sm font-mono leading-relaxed"><code><span class="code-line"><span class="tag">&lt;!DOCTYPE html&gt;</span></span>
<span class="code-line"><span class="tag">&lt;html</span> <span class="attr">lang</span>=<span class="str">"id"</span><span class="tag">&gt;</span></span>
<span class="code-line">  <span class="tag">&lt;head&gt;</span></span>
<span class="code-line">    <span class="tag">&lt;meta</span> <span class="attr">charset</span>=<span class="str">"UTF-8"</span><span class="tag">&gt;</span></span>
<span class="code-line">    <span class="tag">&lt;title&gt;</span>Halaman Produk<span class="tag">&lt;/title&gt;</span></span>
<span class="code-line">  <span class="tag">&lt;/head&gt;</span></span>
<span class="code-line">  <span class="tag">&lt;body&gt;</span></span>
<span class="code-line">    <span class="tag">&lt;h1&gt;</span>Produk Terbaru<span class="tag">&lt;/h1&gt;</span></span>
<span class="code-line">    <span class="tag">&lt;p&gt;</span>Temukan produk pilihan.<span class="tag">&lt;/p&gt;</span></span>
<span class="code-line">    <span class="tag">&lt;button&gt;</span>Beli Sekarang<span class="tag">&lt;/button&gt;</span></span>
<span class="code-line">  <span class="tag">&lt;/body&gt;</span></span>
<span class="code-line"><span class="tag">&lt;/html&gt;</span></span></code></pre>
                                </div>

                                <div class="card-adaptive border rounded-2xl p-5 flex flex-col justify-center">
                                    <h3 class="font-bold text-heading mb-4">Cara membaca strukturnya</h3>
                                    <div class="space-y-3 text-sm">
                                        <div class="flex gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold shrink-0">1</div>
                                            <p class="text-muted leading-relaxed"><code>&lt;head&gt;</code> menyimpan informasi halaman, bukan isi utama yang dibaca pengguna.</p>
                                        </div>
                                        <div class="flex gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-purple-500/10 text-purple-600 dark:text-purple-400 flex items-center justify-center font-bold shrink-0">2</div>
                                            <p class="text-muted leading-relaxed"><code>&lt;body&gt;</code> memuat isi yang muncul pada browser.</p>
                                        </div>
                                        <div class="flex gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold shrink-0">3</div>
                                            <p class="text-muted leading-relaxed">Tag seperti <code>&lt;h1&gt;</code>, <code>&lt;p&gt;</code>, dan <code>&lt;button&gt;</code> menentukan jenis konten.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="sim-bg-adaptive border border-adaptive rounded-2xl overflow-hidden shadow-xl">
                                <div class="bg-indigo-600/95 dark:bg-indigo-900/95 text-white p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3 border-b border-indigo-400/50">
                                    <div class="text-xs font-bold uppercase tracking-widest">Simulasi 1 — Susun Isi Body</div>
                                    <p class="text-[11px] opacity-90 leading-relaxed m-0 md:text-right max-w-xl">Klik elemen HTML untuk melihat kode pada <code>&lt;body&gt;</code> dan hasil tampilannya pada browser.</p>
                                </div>
                                <div class="grid grid-cols-1 lg:grid-cols-2 min-h-[360px]">
                                    <div class="code-adaptive border-b lg:border-b-0 lg:border-r border-adaptive flex flex-col">
                                        <div class="p-4 flex flex-wrap gap-2 border-b border-adaptive">
                                            <button onclick="toggleHtmlElement('h1', this)" class="html-btn px-3 py-2 rounded-lg bg-white dark:bg-white/5 border border-adaptive text-xs font-bold hover:border-indigo-500 transition">Judul</button>
                                            <button onclick="toggleHtmlElement('p', this)" class="html-btn px-3 py-2 rounded-lg bg-white dark:bg-white/5 border border-adaptive text-xs font-bold hover:border-indigo-500 transition">Paragraf</button>
                                            <button onclick="toggleHtmlElement('img', this)" class="html-btn px-3 py-2 rounded-lg bg-white dark:bg-white/5 border border-adaptive text-xs font-bold hover:border-indigo-500 transition">Gambar</button>
                                            <button onclick="toggleHtmlElement('button', this)" class="html-btn px-3 py-2 rounded-lg bg-white dark:bg-white/5 border border-adaptive text-xs font-bold hover:border-indigo-500 transition">Tombol</button>
                                        </div>
                                        <pre id="html-code-preview" class="p-5 text-xs md:text-sm font-mono leading-relaxed overflow-auto custom-scrollbar flex-1"><code><span class="tag">&lt;body&gt;</span>
  <span class="comment">/* pilih elemen terlebih dahulu */</span>
<span class="tag">&lt;/body&gt;</span></code></pre>
                                    </div>
                                    <div class="bg-white dark:bg-slate-950 flex flex-col">
                                        <div class="px-4 py-3 border-b border-adaptive bg-slate-50 dark:bg-black/20">
                                            <span class="text-[10px] font-mono font-bold text-muted uppercase tracking-widest">Browser Preview</span>
                                        </div>
                                        <div id="html-live-preview" class="flex-1 p-6 md:p-8 flex items-center justify-center">
                                            <div class="text-center text-slate-400 dark:text-slate-600 text-sm font-bold">Halaman masih kosong</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gradient-to-r from-indigo-50 to-transparent dark:from-indigo-900/20 dark:to-transparent border-l-4 border-indigo-500 p-4 rounded-r-xl">
                                <h5 class="text-xs font-bold text-indigo-700 dark:text-indigo-400 mb-1">Kesimpulan</h5>
                                <p class="text-[11px] text-slate-700 dark:text-slate-300 leading-relaxed m-0 text-justify">HTML tidak mengatur tampilan secara rinci. HTML memberikan struktur dan makna pada konten, sedangkan tampilan akan diatur oleh CSS.</p>
                            </div>
                        </div>
                    </section>

                    <section id="section-2" class="lesson-section scroll-mt-32" data-lesson-id="2">
                        <div class="space-y-8">
                            <div class="space-y-4 border-l-4 border-indigo-500 pl-4 md:pl-6">
                                <span class="text-indigo-600 dark:text-indigo-400 font-mono text-xs uppercase tracking-widest">Lesson 1.1.2</span>
                                <h2 class="text-3xl md:text-5xl font-black text-heading leading-tight">CSS sebagai <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-purple-600">Pengatur Tampilan</span></h2>
                            </div>

                            <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-90 text-sm md:text-base leading-relaxed space-y-4 text-justify">
                                <p>CSS atau <em>Cascading Style Sheets</em> digunakan untuk mengatur tampilan halaman web. CSS dapat mengubah warna, ukuran teks, jarak, garis tepi, posisi, dan bentuk elemen.</p>
                                <p>Aturan CSS terdiri atas <span class="hl-term">selector</span>, <span class="hl-term">properti</span>, dan <span class="hl-term">nilai</span>. Selector menentukan elemen yang diberi gaya. Properti menentukan bagian tampilan yang diubah. Nilai menentukan hasil pengaturannya.</p>
                            </div>

                            <div class="code-adaptive border border-adaptive rounded-2xl overflow-hidden">
                                <div class="px-4 py-3 border-b border-adaptive flex items-center justify-between">
                                    <span class="font-mono text-xs font-bold text-muted">style.css</span>
                                    <span class="text-[10px] uppercase tracking-widest text-indigo-500 font-bold">Anatomi CSS</span>
                                </div>
                                <pre class="p-5 overflow-auto custom-scrollbar text-xs md:text-sm font-mono leading-relaxed"><code><span class="code-line"><span class="comment">/* selector */</span></span>
<span class="code-line"><span class="tag">button</span> {</span>
<span class="code-line">  <span class="prop">background-color</span>: <span class="str">blue</span>;  <span class="comment">/* properti dan nilai */</span></span>
<span class="code-line">  <span class="prop">color</span>: <span class="str">white</span>;</span>
<span class="code-line">  <span class="prop">padding</span>: <span class="str">12px</span>;</span>
<span class="code-line">}</span></code></pre>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="card-adaptive border rounded-xl p-5">
                                    <h3 class="font-bold text-heading text-sm mb-2">Selector</h3>
                                    <p class="text-xs text-muted leading-relaxed">Bagian yang memilih elemen HTML. Contoh: <code>button</code>, <code>p</code>, atau <code>.kartu</code>.</p>
                                </div>
                                <div class="card-adaptive border rounded-xl p-5">
                                    <h3 class="font-bold text-heading text-sm mb-2">Properti</h3>
                                    <p class="text-xs text-muted leading-relaxed">Bagian tampilan yang ingin diubah. Contoh: <code>color</code>, <code>padding</code>, dan <code>border</code>.</p>
                                </div>
                                <div class="card-adaptive border rounded-xl p-5">
                                    <h3 class="font-bold text-heading text-sm mb-2">Nilai</h3>
                                    <p class="text-xs text-muted leading-relaxed">Isi pengaturan dari properti. Contoh: <code>white</code>, <code>12px</code>, atau <code>solid</code>.</p>
                                </div>
                            </div>

                            <div class="sim-bg-adaptive border border-adaptive rounded-2xl overflow-hidden shadow-xl">
                                <div class="bg-purple-600/95 dark:bg-purple-900/95 text-white p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3 border-b border-purple-400/50">
                                    <div class="text-xs font-bold uppercase tracking-widest">Simulasi 2 — Ubah Aturan CSS</div>
                                    <p class="text-[11px] opacity-90 leading-relaxed m-0 md:text-right max-w-xl">Pilih properti untuk melihat perubahan kode CSS dan tampilan tombol secara langsung.</p>
                                </div>
                                <div class="grid grid-cols-1 lg:grid-cols-2 min-h-[360px]">
                                    <div class="code-adaptive border-b lg:border-b-0 lg:border-r border-adaptive p-5">
                                        <div class="space-y-5 mb-6">
                                            <div>
                                                <label class="block text-[10px] font-bold uppercase tracking-widest text-indigo-600 dark:text-indigo-400 mb-2">Warna Tombol</label>
                                                <select id="css-bg-select" onchange="updateBasicCssSim()" class="w-full bg-white dark:bg-black/30 border border-adaptive rounded-lg px-3 py-2 text-xs outline-none focus:ring-2 focus:ring-indigo-500">
                                                    <option value="#2563eb">Biru</option>
                                                    <option value="#16a34a">Hijau</option>
                                                    <option value="#dc2626">Merah</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-bold uppercase tracking-widest text-pink-600 dark:text-pink-400 mb-2">Padding: <span id="css-pad-label">12</span>px</label>
                                                <input id="css-pad-range" oninput="updateBasicCssSim()" type="range" min="4" max="28" value="12" class="w-full accent-pink-600">
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-bold uppercase tracking-widest text-orange-600 dark:text-orange-400 mb-2">Sudut: <span id="css-radius-label">8</span>px</label>
                                                <input id="css-radius-range" oninput="updateBasicCssSim()" type="range" min="0" max="28" value="8" class="w-full accent-orange-500">
                                            </div>
                                        </div>
                                        <pre id="basic-css-code" class="bg-white dark:bg-black/30 border border-adaptive rounded-xl p-4 overflow-auto custom-scrollbar text-xs md:text-sm font-mono leading-relaxed"></pre>
                                    </div>
                                    <div class="bg-white dark:bg-slate-950 flex flex-col">
                                        <div class="px-4 py-3 border-b border-adaptive bg-slate-50 dark:bg-black/20">
                                            <span class="text-[10px] font-mono font-bold text-muted uppercase tracking-widest">Hasil Tampilan</span>
                                        </div>
                                        <div class="flex-1 flex items-center justify-center p-8 bg-slate-50 dark:bg-slate-900/40">
                                            <button id="basic-css-btn" style="background-color:#2563eb; color:white; padding:12px 20px; border-radius:8px;" class="font-bold shadow-lg transition-all duration-300">Simpan Data</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gradient-to-r from-purple-50 to-transparent dark:from-purple-900/20 dark:to-transparent border-l-4 border-purple-500 p-4 rounded-r-xl">
                                <h5 class="text-xs font-bold text-purple-700 dark:text-purple-400 mb-1">Kesimpulan</h5>
                                <p class="text-[11px] text-slate-700 dark:text-slate-300 leading-relaxed m-0 text-justify">CSS bekerja dengan memilih elemen HTML, lalu memberikan aturan tampilan. Satu perubahan kecil pada CSS dapat mengubah tampilan elemen tanpa mengubah isi HTML.</p>
                            </div>
                        </div>
                    </section>

                    <section id="section-3" class="lesson-section scroll-mt-32" data-lesson-id="3">
                        <div class="space-y-8">
                            <div class="space-y-4 border-l-4 border-indigo-500 pl-4 md:pl-6">
                                <span class="text-indigo-600 dark:text-indigo-400 font-mono text-xs uppercase tracking-widest">Lesson 1.1.3</span>
                                <h2 class="text-3xl md:text-5xl font-black text-heading leading-tight">Menghubungkan <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-purple-600">HTML dan CSS</span></h2>
                            </div>

                            <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-90 text-sm md:text-base leading-relaxed space-y-4 text-justify">
                                <p>CSS dapat ditulis secara langsung di dalam HTML, tetapi cara yang lebih rapi adalah menggunakan file CSS eksternal. Dengan cara ini, struktur HTML dan aturan tampilan berada pada file yang berbeda.</p>
                                <p>File CSS eksternal biasanya diletakkan dalam folder <code>css</code>, lalu dihubungkan melalui tag <code>&lt;link rel="stylesheet" href="css/style.css"&gt;</code> di bagian <code>&lt;head&gt;</code>.</p>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                <div class="card-adaptive border rounded-2xl p-5">
                                    <h3 class="font-bold text-heading mb-4">Struktur folder sederhana</h3>
                                    <div class="bg-slate-100 dark:bg-black/30 border border-adaptive rounded-xl p-4 font-mono text-xs leading-7">
                                        <div>projek-web/</div>
                                        <div class="pl-4">├── index.html</div>
                                        <div class="pl-4">└── css/</div>
                                        <div class="pl-8">└── style.css</div>
                                    </div>
                                </div>
                                <div class="card-adaptive border rounded-2xl p-5">
                                    <h3 class="font-bold text-heading mb-4">Penempatan tag link</h3>
                                    <pre class="bg-slate-100 dark:bg-black/30 border border-adaptive rounded-xl p-4 overflow-auto custom-scrollbar text-xs font-mono leading-relaxed"><code><span class="tag">&lt;head&gt;</span>
  <span class="tag">&lt;link</span> <span class="attr">rel</span>=<span class="str">"stylesheet"</span>
        <span class="attr">href</span>=<span class="str">"css/style.css"</span><span class="tag">&gt;</span>
<span class="tag">&lt;/head&gt;</span></code></pre>
                                </div>
                            </div>

                            <div class="sim-bg-adaptive border border-adaptive rounded-2xl overflow-hidden shadow-xl">
                                <div class="bg-indigo-600/95 dark:bg-indigo-900/95 text-white p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3 border-b border-indigo-400/50">
                                    <div class="text-xs font-bold uppercase tracking-widest">Simulasi 3 — Aktifkan CSS Eksternal</div>
                                    <p class="text-[11px] opacity-90 leading-relaxed m-0 md:text-right max-w-xl">Nyalakan atau matikan file CSS untuk melihat perbedaan halaman sebelum dan sesudah diberi gaya.</p>
                                </div>
                                <div class="grid grid-cols-1 lg:grid-cols-2 min-h-[360px]">
                                    <div class="code-adaptive border-b lg:border-b-0 lg:border-r border-adaptive p-5">
                                        <div class="flex items-center justify-between gap-4 mb-5">
                                            <div>
                                                <h3 class="font-bold text-heading text-sm">Status file CSS</h3>
                                                <p class="text-xs text-muted">Tag <code>&lt;link&gt;</code> menentukan apakah file CSS terbaca.</p>
                                            </div>
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input id="external-css-toggle" type="checkbox" class="sr-only peer" onchange="updateExternalCssSim()" checked>
                                                <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                            </label>
                                        </div>
                                        <pre id="external-css-code" class="bg-white dark:bg-black/30 border border-adaptive rounded-xl p-4 overflow-auto custom-scrollbar text-xs md:text-sm font-mono leading-relaxed"></pre>
                                    </div>
                                    <div class="bg-white dark:bg-slate-950 flex flex-col">
                                        <div class="px-4 py-3 border-b border-adaptive bg-slate-50 dark:bg-black/20">
                                            <span class="text-[10px] font-mono font-bold text-muted uppercase tracking-widest">Browser Preview</span>
                                        </div>
                                        <div id="external-css-preview" class="flex-1 flex items-center justify-center p-8 transition-all duration-300">
                                            <div id="external-css-card" class="transition-all duration-300">
                                                <h1>Produk Pilihan</h1>
                                                <p>Sepatu ringan untuk kegiatan harian.</p>
                                                <button>Beli Sekarang</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gradient-to-r from-indigo-50 to-transparent dark:from-indigo-900/20 dark:to-transparent border-l-4 border-indigo-500 p-4 rounded-r-xl">
                                <h5 class="text-xs font-bold text-indigo-700 dark:text-indigo-400 mb-1">Kesimpulan</h5>
                                <p class="text-[11px] text-slate-700 dark:text-slate-300 leading-relaxed m-0 text-justify">HTML dan CSS dapat bekerja bersama ketika file CSS terhubung dengan benar. Jika alamat file salah atau tag <code>&lt;link&gt;</code> tidak ditulis, tampilan halaman akan kembali polos.</p>
                            </div>
                        </div>
                    </section>

                    <section id="section-4" class="lesson-section scroll-mt-32" data-lesson-id="4">
                        <div class="space-y-8">
                            <div class="space-y-4 border-l-4 border-indigo-500 pl-4 md:pl-6">
                                <span class="text-indigo-600 dark:text-indigo-400 font-mono text-xs uppercase tracking-widest">Lesson 1.1.4</span>
                                <h2 class="text-3xl md:text-5xl font-black text-heading leading-tight">Box Model dan <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-purple-600">Display</span></h2>
                            </div>

                            <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-90 text-sm md:text-base leading-relaxed space-y-4 text-justify">
                                <p>Setiap elemen HTML dianggap sebagai kotak oleh browser. Kotak tersebut terdiri atas <strong>content</strong>, <strong>padding</strong>, <strong>border</strong>, dan <strong>margin</strong>. Content adalah isi utama. Padding adalah ruang di dalam kotak. Border adalah garis tepi. Margin adalah jarak luar antar elemen.</p>
                                <p>Selain box model, CSS juga mengenal jenis tampilan elemen. Elemen <code>block</code> menempati satu baris penuh, elemen <code>inline</code> mengikuti lebar isi, sedangkan <code>flex</code> membantu menyusun beberapa elemen dalam satu baris atau kolom dengan lebih teratur.</p>
                            </div>

                            <div class="sim-bg-adaptive border border-adaptive rounded-2xl overflow-hidden shadow-xl">
                                <div class="bg-orange-600/95 dark:bg-orange-900/95 text-white p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3 border-b border-orange-400/50">
                                    <div class="text-xs font-bold uppercase tracking-widest">Simulasi 4 — Ubah Box Model</div>
                                    <p class="text-[11px] opacity-90 leading-relaxed m-0 md:text-right max-w-xl">Geser nilai padding, border, dan margin untuk melihat perubahan ruang pada sebuah kartu.</p>
                                </div>
                                <div class="grid grid-cols-1 lg:grid-cols-2 min-h-[420px]">
                                    <div class="code-adaptive border-b lg:border-b-0 lg:border-r border-adaptive p-5">
                                        <div class="space-y-5 mb-6">
                                            <div>
                                                <label class="block text-[10px] font-bold uppercase tracking-widest text-emerald-600 dark:text-emerald-400 mb-2">Padding: <span id="box-pad-label">20</span>px</label>
                                                <input id="box-pad-range" oninput="updateBoxSim()" type="range" min="0" max="50" value="20" class="w-full accent-emerald-500">
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-bold uppercase tracking-widest text-amber-600 dark:text-amber-400 mb-2">Border: <span id="box-border-label">4</span>px</label>
                                                <input id="box-border-range" oninput="updateBoxSim()" type="range" min="0" max="16" value="4" class="w-full accent-amber-500">
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-bold uppercase tracking-widest text-orange-600 dark:text-orange-400 mb-2">Margin: <span id="box-margin-label">20</span>px</label>
                                                <input id="box-margin-range" oninput="updateBoxSim()" type="range" min="0" max="60" value="20" class="w-full accent-orange-500">
                                            </div>
                                        </div>
                                        <pre id="box-css-code" class="bg-white dark:bg-black/30 border border-adaptive rounded-xl p-4 overflow-auto custom-scrollbar text-xs md:text-sm font-mono leading-relaxed"></pre>
                                    </div>
                                    <div class="bg-slate-100 dark:bg-slate-950 flex flex-col">
                                        <div class="px-4 py-3 border-b border-adaptive bg-white/60 dark:bg-black/20">
                                            <span class="text-[10px] font-mono font-bold text-muted uppercase tracking-widest">Visual Box Model</span>
                                        </div>
                                        <div class="flex-1 flex items-center justify-center p-6 overflow-auto custom-scrollbar">
                                            <div id="box-margin-layer" class="bg-orange-400/15 border border-dashed border-orange-400 transition-all duration-300 p-[20px]">
                                                <div id="box-border-layer" class="bg-amber-400/20 border-4 border-amber-500 transition-all duration-300">
                                                    <div id="box-pad-layer" class="bg-emerald-400/20 transition-all duration-300 p-[20px]">
                                                        <div class="bg-indigo-600 text-white font-bold rounded-lg px-8 py-6 text-center shadow-lg">
                                                            Content
                                                            <div class="text-[10px] opacity-80 mt-1">isi elemen</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-adaptive border rounded-2xl p-5 md:p-6">
                                <h3 class="font-bold text-heading mb-4">Perbandingan display sederhana</h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="bg-slate-100 dark:bg-black/30 rounded-xl p-4 border border-adaptive">
                                        <div class="text-xs font-bold text-heading mb-3">display: block</div>
                                        <div class="space-y-2">
                                            <div class="bg-indigo-500 text-white rounded px-3 py-2 text-xs">Elemen 1</div>
                                            <div class="bg-indigo-500 text-white rounded px-3 py-2 text-xs">Elemen 2</div>
                                        </div>
                                    </div>
                                    <div class="bg-slate-100 dark:bg-black/30 rounded-xl p-4 border border-adaptive">
                                        <div class="text-xs font-bold text-heading mb-3">display: inline</div>
                                        <span class="bg-purple-500 text-white rounded px-3 py-2 text-xs">Teks A</span>
                                        <span class="bg-purple-500 text-white rounded px-3 py-2 text-xs">Teks B</span>
                                    </div>
                                    <div class="bg-slate-100 dark:bg-black/30 rounded-xl p-4 border border-adaptive">
                                        <div class="text-xs font-bold text-heading mb-3">display: flex</div>
                                        <div class="flex gap-2">
                                            <div class="bg-emerald-500 text-white rounded px-3 py-2 text-xs">A</div>
                                            <div class="bg-emerald-500 text-white rounded px-3 py-2 text-xs">B</div>
                                            <div class="bg-emerald-500 text-white rounded px-3 py-2 text-xs">C</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gradient-to-r from-orange-50 to-transparent dark:from-orange-900/20 dark:to-transparent border-l-4 border-orange-500 p-4 rounded-r-xl">
                                <h5 class="text-xs font-bold text-orange-700 dark:text-orange-400 mb-1">Kesimpulan</h5>
                                <p class="text-[11px] text-slate-700 dark:text-slate-300 leading-relaxed m-0 text-justify">Box model menentukan ruang pada elemen. Display menentukan cara elemen tersusun di halaman. Keduanya menjadi dasar penting sebelum mempelajari layout yang lebih lanjut.</p>
                            </div>
                        </div>
                    </section>

                    <section id="section-5" class="lesson-section scroll-mt-32" data-lesson-id="5" data-type="activity">
                        <div class="space-y-8">
                            <div class="space-y-4 border-l-4 border-purple-500 pl-4 md:pl-6">
                                <span class="text-purple-600 dark:text-purple-400 font-mono text-xs uppercase tracking-widest">Aktivitas 1.1</span>
                                <h2 class="text-3xl md:text-5xl font-black text-heading leading-tight">Analisis Halaman <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-500 to-indigo-600">HTML dan CSS</span></h2>
                            </div>

                            <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-90 text-sm md:text-base leading-relaxed text-justify">
                                <p>Aktivitas ini digunakan untuk mengecek pemahaman dasar. Pilih jawaban yang paling tepat berdasarkan situasi tampilan yang diberikan. Soal disusun dari pemahaman dasar sampai analisis sederhana.</p>
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
                                    <div class="text-xs font-bold uppercase tracking-widest">Evaluasi Interaktif</div>
                                    <p class="text-[11px] opacity-90 leading-relaxed m-0 md:text-right max-w-xl">Jawab semua soal, lalu tekan tombol periksa untuk melihat skor dan pembahasan.</p>
                                </div>

                                <div id="activityForm" class="p-4 md:p-6 space-y-4 max-h-[620px] overflow-y-auto custom-scrollbar">
                                    <div class="activity-question card-adaptive border rounded-xl p-5" data-answer="b">
                                        <p class="text-sm font-bold text-heading mb-4">1. Bagian dokumen HTML yang berisi konten terlihat oleh pengguna adalah ....</p>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                            <button onclick="chooseActivity(this, 'q1', 'a')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-indigo-500 transition">A. <code>&lt;head&gt;</code></button>
                                            <button onclick="chooseActivity(this, 'q1', 'b')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-indigo-500 transition">B. <code>&lt;body&gt;</code></button>
                                            <button onclick="chooseActivity(this, 'q1', 'c')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-indigo-500 transition">C. <code>&lt;title&gt;</code></button>
                                            <button onclick="chooseActivity(this, 'q1', 'd')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-indigo-500 transition">D. <code>&lt;meta&gt;</code></button>
                                        </div>
                                    </div>

                                    <div class="activity-question card-adaptive border rounded-xl p-5" data-answer="c">
                                        <p class="text-sm font-bold text-heading mb-4">2. Aturan CSS <code>p { color: gray; }</code> berarti ....</p>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                            <button onclick="chooseActivity(this, 'q2', 'a')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-indigo-500 transition">A. Semua tombol berubah menjadi abu-abu</button>
                                            <button onclick="chooseActivity(this, 'q2', 'b')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-indigo-500 transition">B. Semua judul berubah menjadi abu-abu</button>
                                            <button onclick="chooseActivity(this, 'q2', 'c')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-indigo-500 transition">C. Semua paragraf berubah menjadi abu-abu</button>
                                            <button onclick="chooseActivity(this, 'q2', 'd')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-indigo-500 transition">D. Semua gambar berubah menjadi abu-abu</button>
                                        </div>
                                    </div>

                                    <div class="activity-question card-adaptive border rounded-xl p-5" data-answer="a">
                                        <p class="text-sm font-bold text-heading mb-4">3. Tag yang tepat untuk menghubungkan file <code>css/style.css</code> adalah ....</p>
                                        <div class="grid grid-cols-1 gap-3">
                                            <button onclick="chooseActivity(this, 'q3', 'a')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-indigo-500 transition">A. <code>&lt;link rel="stylesheet" href="css/style.css"&gt;</code></button>
                                            <button onclick="chooseActivity(this, 'q3', 'b')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-indigo-500 transition">B. <code>&lt;style href="css/style.css"&gt;</code></button>
                                            <button onclick="chooseActivity(this, 'q3', 'c')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-indigo-500 transition">C. <code>&lt;css src="css/style.css"&gt;</code></button>
                                            <button onclick="chooseActivity(this, 'q3', 'd')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-indigo-500 transition">D. <code>&lt;script href="css/style.css"&gt;</code></button>
                                        </div>
                                    </div>

                                    <div class="activity-question card-adaptive border rounded-xl p-5" data-answer="d">
                                        <p class="text-sm font-bold text-heading mb-4">4. Kartu informasi terlihat terlalu rapat karena teks menempel pada tepi kartu. Properti CSS yang paling tepat diperbaiki adalah ....</p>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                            <button onclick="chooseActivity(this, 'q4', 'a')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-indigo-500 transition">A. <code>margin</code></button>
                                            <button onclick="chooseActivity(this, 'q4', 'b')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-indigo-500 transition">B. <code>display</code></button>
                                            <button onclick="chooseActivity(this, 'q4', 'c')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-indigo-500 transition">C. <code>title</code></button>
                                            <button onclick="chooseActivity(this, 'q4', 'd')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-indigo-500 transition">D. <code>padding</code></button>
                                        </div>
                                    </div>

                                    <div class="activity-question card-adaptive border rounded-xl p-5" data-answer="b">
                                        <p class="text-sm font-bold text-heading mb-4">5. Tiga tombol perlu disusun sejajar dalam satu baris dan diberi jarak antar tombol. Pengaturan awal yang paling sesuai adalah ....</p>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                            <button onclick="chooseActivity(this, 'q5', 'a')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-indigo-500 transition">A. Mengubah semua tombol menjadi <code>inline</code> saja</button>
                                            <button onclick="chooseActivity(this, 'q5', 'b')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-indigo-500 transition">B. Menggunakan <code>display: flex</code> pada pembungkus tombol</button>
                                            <button onclick="chooseActivity(this, 'q5', 'c')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-indigo-500 transition">C. Menambahkan <code>title</code> pada setiap tombol</button>
                                            <button onclick="chooseActivity(this, 'q5', 'd')" class="activity-option text-left border border-adaptive rounded-lg p-3 text-xs hover:border-indigo-500 transition">D. Menghapus seluruh CSS tombol</button>
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
                                    <h3 class="font-bold text-heading mb-3">Pembahasan Singkat</h3>
                                    <div class="space-y-2 text-xs text-muted leading-relaxed">
                                        <p><strong>1.</strong> Konten utama yang tampil pada browser berada di dalam <code>&lt;body&gt;</code>.</p>
                                        <p><strong>2.</strong> Selector <code>p</code> memilih elemen paragraf, lalu properti <code>color</code> mengubah warna teksnya.</p>
                                        <p><strong>3.</strong> File CSS eksternal dihubungkan menggunakan tag <code>&lt;link rel="stylesheet" href="..."&gt;</code>.</p>
                                        <p><strong>4.</strong> Teks yang terlalu menempel pada tepi kartu diperbaiki dengan <code>padding</code> karena padding mengatur ruang bagian dalam.</p>
                                        <p><strong>5.</strong> <code>display: flex</code> pada pembungkus elemen cocok untuk menyusun tombol dalam satu baris dengan jarak yang teratur.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                </article>

                <div class="mt-20 md:mt-32 pt-8 border-t border-adaptive flex flex-col sm:flex-row justify-between items-center gap-4">
                    <a href="{{ route('courses.curriculum') ?? '#' }}" class="group flex items-center gap-4 text-muted hover:text-heading transition-colors w-full sm:w-auto justify-center sm:justify-start">
                        <div class="w-10 h-10 md:w-12 md:h-12 rounded-full border border-adaptive flex items-center justify-center group-hover:bg-slate-100 dark:group-hover:bg-white/5 transition-colors shrink-0">
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        </div>
                        <div class="text-left">
                            <div class="text-[10px] uppercase tracking-widest font-bold opacity-60">Kembali</div>
                            <div class="font-black text-xs md:text-sm line-clamp-1">Dashboard Modul Kurikulum Utama</div>
                        </div>
                    </a>

                    <div id="nextChapterBtn" class="group flex items-center gap-4 text-right text-muted cursor-not-allowed opacity-50 pointer-events-none transition-all duration-500 w-full sm:w-auto justify-center sm:justify-end">
                        <div class="text-right">
                            <div id="nextLabel" class="text-[10px] uppercase tracking-widest font-bold opacity-60">Berikutnya</div>
                            <div class="font-black text-xs md:text-sm line-clamp-1">Konsep Dasar Tailwind CSS</div>
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
    window.LESSON_IDS = [1, 2, 3, 4, 5];
    let rawCompletedIds = {!! json_encode($completedLessonIds ?? []) !!};
    window.COMPLETED_IDS = rawCompletedIds.map(id => Number(id));
    let completedSet = new Set(window.COMPLETED_IDS);
    const ACTIVITY_LESSON_ID = 5;
    let activityCompleted = {!! ($activityCompleted ?? false) ? 'true' : 'false' !!};

    const htmlState = { h1: false, p: false, img: false, button: false };
    const activityAnswers = {};

    document.addEventListener('DOMContentLoaded', () => {
        initScrollSpy();
        initVisualEffects();
        initLessonObserver();
        updateProgressUI(false);
        updateBasicCssSim();
        updateExternalCssSim();
        updateBoxSim();

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

    function toggleHtmlElement(type, btn) {
        htmlState[type] = !htmlState[type];
        btn.classList.toggle('bg-indigo-600', htmlState[type]);
        btn.classList.toggle('text-white', htmlState[type]);
        btn.classList.toggle('border-indigo-500', htmlState[type]);
        renderHtmlSim();
    }

    function renderHtmlSim() {
        const code = document.getElementById('html-code-preview');
        const preview = document.getElementById('html-live-preview');
        let lines = ['<span class="tag">&lt;body&gt;</span>'];
        let view = '';

        if (htmlState.h1) {
            lines.push('  <span class="tag">&lt;h1&gt;</span>Produk Terbaru<span class="tag">&lt;/h1&gt;</span>');
            view += '<h1 class="text-2xl md:text-3xl font-black text-slate-900 dark:text-white mb-3">Produk Terbaru</h1>';
        }
        if (htmlState.p) {
            lines.push('  <span class="tag">&lt;p&gt;</span>Temukan produk pilihan untuk kebutuhan harian.<span class="tag">&lt;/p&gt;</span>');
            view += '<p class="text-sm text-slate-600 dark:text-slate-300 mb-4">Temukan produk pilihan untuk kebutuhan harian.</p>';
        }
        if (htmlState.img) {
            lines.push('  <span class="tag">&lt;img</span> <span class="attr">src</span>=<span class="str">"produk.jpg"</span> <span class="attr">alt</span>=<span class="str">"Foto produk"</span><span class="tag">&gt;</span>');
            view += '<div class="w-full h-28 rounded-xl bg-gradient-to-br from-indigo-200 to-purple-200 dark:from-indigo-900 dark:to-purple-900 mb-4 flex items-center justify-center text-xs font-bold text-indigo-700 dark:text-indigo-200">Gambar Produk</div>';
        }
        if (htmlState.button) {
            lines.push('  <span class="tag">&lt;button&gt;</span>Beli Sekarang<span class="tag">&lt;/button&gt;</span>');
            view += '<button class="px-4 py-2 rounded-lg bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-sm font-bold">Beli Sekarang</button>';
        }

        if (!htmlState.h1 && !htmlState.p && !htmlState.img && !htmlState.button) {
            lines.push('  <span class="comment">/* pilih elemen terlebih dahulu */</span>');
            view = '<div class="text-center text-slate-400 dark:text-slate-600 text-sm font-bold">Halaman masih kosong</div>';
        }

        lines.push('<span class="tag">&lt;/body&gt;</span>');
        code.innerHTML = '<code>' + lines.join('\n') + '</code>';
        preview.innerHTML = '<div class="w-full max-w-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-2xl p-5 shadow-sm">' + view + '</div>';
    }

    function updateBasicCssSim() {
        const bg = document.getElementById('css-bg-select')?.value || '#2563eb';
        const pad = document.getElementById('css-pad-range')?.value || 12;
        const radius = document.getElementById('css-radius-range')?.value || 8;
        const btn = document.getElementById('basic-css-btn');
        const code = document.getElementById('basic-css-code');

        document.getElementById('css-pad-label').innerText = pad;
        document.getElementById('css-radius-label').innerText = radius;

        if (btn) {
            btn.style.backgroundColor = bg;
            btn.style.padding = `${pad}px 20px`;
            btn.style.borderRadius = `${radius}px`;
        }

        if (code) {
            code.innerHTML = `<code><span class="tag">button</span> {
  <span class="prop">background-color</span>: <span class="str">${bg}</span>;
  <span class="prop">color</span>: <span class="str">white</span>;
  <span class="prop">padding</span>: <span class="str">${pad}px 20px</span>;
  <span class="prop">border-radius</span>: <span class="str">${radius}px</span>;
}</code>`;
        }
    }

    function updateExternalCssSim() {
        const active = document.getElementById('external-css-toggle')?.checked;
        const code = document.getElementById('external-css-code');
        const card = document.getElementById('external-css-card');

        if (code) {
            code.innerHTML = `<code><span class="tag">&lt;head&gt;</span>
  ${active ? '<span class="tag">&lt;link</span> <span class="attr">rel</span>=<span class="str">"stylesheet"</span> <span class="attr">href</span>=<span class="str">"css/style.css"</span><span class="tag">&gt;</span>' : '<span class="comment">&lt;!-- file CSS belum dihubungkan --&gt;</span>'}
<span class="tag">&lt;/head&gt;</span>

<span class="comment">/* css/style.css */</span>
${active ? `<span class="tag">.kartu</span> {
  <span class="prop">background</span>: <span class="str">white</span>;
  <span class="prop">padding</span>: <span class="str">24px</span>;
  <span class="prop">border</span>: <span class="str">1px solid #cbd5e1</span>;
  <span class="prop">border-radius</span>: <span class="str">16px</span>;
}` : '<span class="comment">/* aturan CSS tidak terbaca */</span>'}</code>`;
        }

        if (card) {
            if (active) {
                card.className = 'transition-all duration-300 bg-white dark:bg-slate-900 border border-slate-300 dark:border-white/10 rounded-2xl p-6 shadow-xl max-w-xs';
                card.querySelector('h1').className = 'text-xl font-black text-slate-900 dark:text-white mb-2';
                card.querySelector('p').className = 'text-sm text-slate-600 dark:text-slate-300 mb-4';
                card.querySelector('button').className = 'px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-bold';
            } else {
                card.className = 'transition-all duration-300 max-w-xs';
                card.querySelector('h1').className = '';
                card.querySelector('p').className = '';
                card.querySelector('button').className = '';
            }
        }
    }

    function updateBoxSim() {
        const pad = document.getElementById('box-pad-range')?.value || 20;
        const border = document.getElementById('box-border-range')?.value || 4;
        const margin = document.getElementById('box-margin-range')?.value || 20;

        document.getElementById('box-pad-label').innerText = pad;
        document.getElementById('box-border-label').innerText = border;
        document.getElementById('box-margin-label').innerText = margin;

        document.getElementById('box-pad-layer').style.padding = `${pad}px`;
        document.getElementById('box-border-layer').style.borderWidth = `${border}px`;
        document.getElementById('box-margin-layer').style.padding = `${margin}px`;

        document.getElementById('box-css-code').innerHTML = `<code><span class="tag">.kartu</span> {
  <span class="prop">margin</span>: <span class="str">${margin}px</span>;
  <span class="prop">border</span>: <span class="str">${border}px solid orange</span>;
  <span class="prop">padding</span>: <span class="str">${pad}px</span>;
}</code>`;
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

    async function checkActivity() {
        if (activityCompleted) return;
        const correct = { q1:'b', q2:'c', q3:'a', q4:'d', q5:'b' };
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
            status.innerText = 'Aktivitas berhasil. Pemahaman dasar HTML dan CSS sudah sesuai.';
            status.className = 'text-xs font-bold text-emerald-600 dark:text-emerald-400';
            await saveLessonToDB(ACTIVITY_LESSON_ID);
            activityCompleted = true;
            lockActivityUI(false);
            unlockNextChapter();
        } else {
            status.innerText = 'Skor belum cukup. Baca pembahasan, lalu perbaiki jawaban yang salah.';
            status.className = 'text-xs font-bold text-orange-600 dark:text-orange-400';
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

            btn.onclick = () => window.location.href = "{{ route('courses.tailwindcss') ?? '#' }}";
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
