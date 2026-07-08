@extends('layouts.landing')
@section('title','Warna dan Latar Belakang')

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
            radial-gradient(800px circle at 48% 88%, rgba(34,197,94,.09), transparent 44%);
        animation: bgMove 20s ease-in-out infinite alternate;
    }
    .dark #animated-bg {
        background:
            radial-gradient(600px circle at 18% 16%, rgba(99,102,241,.18), transparent 40%),
            radial-gradient(700px circle at 85% 25%, rgba(14,165,233,.14), transparent 42%),
            radial-gradient(800px circle at 48% 88%, rgba(34,197,94,.13), transparent 44%);
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
                    <div class="w-8 h-8 rounded-lg bg-indigo-500/10 dark:bg-indigo-500/20 border border-indigo-500/20 flex items-center justify-center font-bold text-xs text-indigo-600 dark:text-indigo-400 shrink-0">3.2</div>
                    <div class="min-w-0">
                        <h1 class="text-sm font-bold text-heading line-clamp-1">Warna dan Latar Belakang</h1>
                        <p class="text-[10px] text-muted line-clamp-1">bg-*, text-*, kontras kartu, dan warna tindakan</p>
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
                        <p class="text-xs font-mono font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-[.25em] mb-3">Subbab 3.2</p>
                        <h2 class="text-3xl md:text-5xl font-black text-heading leading-tight mb-4">Warna dan Latar Belakang</h2>
                        <p class="text-sm md:text-base text-muted leading-relaxed max-w-3xl text-justify">
                            Pada subbab ini, pembelajar mempelajari cara menggunakan warna pada Tailwind CSS untuk membangun tampilan halaman yang jelas. Materi berfokus pada warna latar halaman, kartu, teks, dan tombol agar setiap elemen mudah dibedakan dan memiliki fungsi visual yang tepat.
                        </p>
                    </div>

                    <h3 class="text-xl font-bold text-heading mt-10 mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Tujuan Pembelajaran
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="card-adaptive border rounded-xl p-5">
                            <div class="w-8 h-8 rounded-lg bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold mb-4">1</div>
                            <h4 class="text-sm font-bold text-heading mb-1">Membaca Class Warna</h4>
                            <p class="text-[11px] text-muted leading-relaxed">Membedakan fungsi <code>bg-*</code>, <code>text-*</code>, <code>border-*</code>, dan <code>font-*</code>.</p>
                        </div>
                        <div class="card-adaptive border rounded-xl p-5">
                            <div class="w-8 h-8 rounded-lg bg-purple-500/10 text-purple-600 dark:text-purple-400 flex items-center justify-center font-bold mb-4">2</div>
                            <h4 class="text-sm font-bold text-heading mb-1">Mengatur Latar</h4>
                            <p class="text-[11px] text-muted leading-relaxed">Menjelaskan penggunaan <code>bg-slate-100</code> sebagai latar halaman yang lembut.</p>
                        </div>
                        <div class="card-adaptive border rounded-xl p-5">
                            <div class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold mb-4">3</div>
                            <h4 class="text-sm font-bold text-heading mb-1">Membedakan Kartu</h4>
                            <p class="text-[11px] text-muted leading-relaxed">Memilih <code>bg-white</code> agar kartu terlihat jelas di atas latar abu-abu muda.</p>
                        </div>
                        <div class="card-adaptive border rounded-xl p-5">
                            <div class="w-8 h-8 rounded-lg bg-orange-500/10 text-orange-600 dark:text-orange-400 flex items-center justify-center font-bold mb-4">4</div>
                            <h4 class="text-sm font-bold text-heading mb-1">Memilih Warna Tombol</h4>
                            <p class="text-[11px] text-muted leading-relaxed">Menentukan warna tombol sesuai makna tindakan, misalnya biru dan hijau.</p>
                        </div>
                    </div>
                </div>

                <article class="space-y-32 md:space-y-40">

                    <section id="section-51" class="lesson-section scroll-mt-32" data-lesson-id="51">
                        <div class="space-y-8">
                            <div class="space-y-4 border-l-4 border-indigo-500 pl-4 md:pl-6">
                                <span class="text-indigo-600 dark:text-indigo-400 font-mono text-xs uppercase tracking-widest">Lesson 3.2.1</span>
                                <h2 class="text-3xl md:text-5xl font-black text-heading leading-tight">Pola Class <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-purple-600">Warna</span></h2>
                            </div>

                            <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-90 text-sm md:text-base leading-relaxed space-y-4 text-justify">
                                <p>Tailwind CSS menggunakan awalan class yang konsisten. Awalan <span class="hl-term">bg-*</span> digunakan untuk memberi warna latar pada elemen. Awalan <span class="hl-term">text-*</span> digunakan untuk warna teks. Awalan <span class="hl-term">border-*</span> digunakan untuk warna garis tepi.</p>
                                <p>Pola ini membantu pembelajar membaca fungsi class dari namanya. Contohnya, <code>bg-blue-600</code> berarti latar elemen berwarna biru, <code>text-white</code> berarti teks berwarna putih, dan <code>border-slate-300</code> berarti garis tepi berwarna abu-abu. Class <code>font-*</code> bukan untuk warna, tetapi untuk mengatur jenis atau ketebalan huruf.</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div class="card-adaptive border rounded-xl p-5">
                                    <h3 class="font-bold text-heading text-sm mb-2"><code>bg-*</code></h3>
                                    <p class="text-xs text-muted leading-relaxed">Mengatur warna latar. Contoh: <code>bg-slate-100</code>, <code>bg-white</code>, <code>bg-blue-600</code>.</p>
                                </div>
                                <div class="card-adaptive border rounded-xl p-5">
                                    <h3 class="font-bold text-heading text-sm mb-2"><code>text-*</code></h3>
                                    <p class="text-xs text-muted leading-relaxed">Mengatur warna teks. Contoh: <code>text-slate-900</code>, <code>text-slate-600</code>, <code>text-white</code>.</p>
                                </div>
                                <div class="card-adaptive border rounded-xl p-5">
                                    <h3 class="font-bold text-heading text-sm mb-2"><code>border-*</code></h3>
                                    <p class="text-xs text-muted leading-relaxed">Mengatur warna garis tepi. Contoh: <code>border-slate-300</code>.</p>
                                </div>
                                <div class="card-adaptive border rounded-xl p-5">
                                    <h3 class="font-bold text-heading text-sm mb-2"><code>font-*</code></h3>
                                    <p class="text-xs text-muted leading-relaxed">Mengatur huruf, bukan warna. Contoh: <code>font-bold</code> dan <code>font-medium</code>.</p>
                                </div>
                            </div>

                            <div class="sim-bg-adaptive border border-adaptive rounded-2xl overflow-hidden shadow-xl">
                                <div class="bg-indigo-600/95 dark:bg-indigo-900/95 text-white p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3 border-b border-indigo-400/50">
                                    <div class="text-xs font-bold uppercase tracking-widest">Simulasi 1 — Kenali Awalan Class</div>
                                    <p class="text-[11px] opacity-90 leading-relaxed m-0 md:text-right max-w-xl">Klik class untuk melihat bagian elemen yang berubah pada kartu.</p>
                                </div>
                                <div class="grid grid-cols-1 lg:grid-cols-2 min-h-[360px]">
                                    <div class="code-adaptive border-b lg:border-b-0 lg:border-r border-adaptive p-5">
                                        <div class="space-y-3 mb-6">
                                            <button onclick="setColorPattern('bg')" class="w-full text-left px-4 py-3 rounded-lg bg-white dark:bg-white/5 border border-adaptive text-xs font-bold hover:border-indigo-500 transition"><code>bg-blue-600</code><span class="block text-muted mt-1 font-normal">Mengubah latar elemen.</span></button>
                                            <button onclick="setColorPattern('text')" class="w-full text-left px-4 py-3 rounded-lg bg-white dark:bg-white/5 border border-adaptive text-xs font-bold hover:border-indigo-500 transition"><code>text-white</code><span class="block text-muted mt-1 font-normal">Mengubah warna teks.</span></button>
                                            <button onclick="setColorPattern('border')" class="w-full text-left px-4 py-3 rounded-lg bg-white dark:bg-white/5 border border-adaptive text-xs font-bold hover:border-indigo-500 transition"><code>border-indigo-500</code><span class="block text-muted mt-1 font-normal">Mengubah garis tepi.</span></button>
                                            <button onclick="setColorPattern('font')" class="w-full text-left px-4 py-3 rounded-lg bg-white dark:bg-white/5 border border-adaptive text-xs font-bold hover:border-indigo-500 transition"><code>font-bold</code><span class="block text-muted mt-1 font-normal">Mengubah ketebalan huruf.</span></button>
                                        </div>
                                        <pre id="pattern-code" class="bg-white dark:bg-black/30 border border-adaptive rounded-xl p-4 overflow-auto custom-scrollbar text-xs md:text-sm font-mono leading-relaxed"></pre>
                                    </div>
                                    <div class="bg-white dark:bg-slate-950 flex flex-col">
                                        <div class="px-4 py-3 border-b border-adaptive bg-slate-50 dark:bg-black/20">
                                            <span class="text-[10px] font-mono font-bold text-muted uppercase tracking-widest">Browser Preview</span>
                                        </div>
                                        <div class="flex-1 flex items-center justify-center p-8 bg-slate-100 dark:bg-slate-900/40">
                                            <div id="pattern-card" class="bg-white text-slate-900 border border-slate-300 rounded-2xl p-6 shadow-sm max-w-xs transition-all duration-300">
                                                <h3 id="pattern-title" class="text-xl font-bold">Kartu Produk</h3>
                                                <p id="pattern-desc" class="text-sm mt-2 text-slate-600">Class yang dipilih akan mengubah tampilan bagian tertentu.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gradient-to-r from-indigo-50 to-transparent dark:from-indigo-900/20 dark:to-transparent border-l-4 border-indigo-500 p-4 rounded-r-xl">
                                <h5 class="text-xs font-bold text-indigo-700 dark:text-indigo-400 mb-1">Kesimpulan</h5>
                                <p class="text-[11px] text-slate-700 dark:text-slate-300 leading-relaxed m-0 text-justify">Class untuk warna dapat dikenali dari awalan namanya. Untuk memberi warna latar, gunakan pola <code>bg-*</code>.</p>
                            </div>
                        </div>
                    </section>

                    <section id="section-52" class="lesson-section scroll-mt-32" data-lesson-id="52">
                        <div class="space-y-8">
                            <div class="space-y-4 border-l-4 border-indigo-500 pl-4 md:pl-6">
                                <span class="text-indigo-600 dark:text-indigo-400 font-mono text-xs uppercase tracking-widest">Lesson 3.2.2</span>
                                <h2 class="text-3xl md:text-5xl font-black text-heading leading-tight">Latar Halaman <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-purple-600">yang Lembut</span></h2>
                            </div>

                            <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-90 text-sm md:text-base leading-relaxed space-y-4 text-justify">
                                <p>Latar halaman berfungsi sebagai dasar visual. Warna latar yang terlalu kuat dapat mengganggu isi, sedangkan latar yang terlalu sama dengan kartu dapat membuat batas konten kurang jelas. Pada halaman web, <span class="hl-term">bg-slate-100</span> sering digunakan untuk memberi latar abu-abu muda yang lembut.</p>
                                <p>Latar abu-abu muda cocok untuk halaman yang memiliki banyak kartu, seperti daftar produk, dashboard, dan halaman latihan. Warna ini membantu kartu putih terlihat lebih menonjol tanpa membuat tampilan terasa berat.</p>
                            </div>

                            <div class="sim-bg-adaptive border border-adaptive rounded-2xl overflow-hidden shadow-xl">
                                <div class="bg-purple-600/95 dark:bg-purple-900/95 text-white p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3 border-b border-purple-400/50">
                                    <div class="text-xs font-bold uppercase tracking-widest">Simulasi 2 — Pilih Latar Halaman</div>
                                    <p class="text-[11px] opacity-90 leading-relaxed m-0 md:text-right max-w-xl">Bandingkan tampilan kartu saat latar halaman diganti.</p>
                                </div>
                                <div class="grid grid-cols-1 lg:grid-cols-2 min-h-[360px]">
                                    <div class="code-adaptive border-b lg:border-b-0 lg:border-r border-adaptive p-5">
                                        <div class="space-y-3 mb-6">
                                            <button onclick="setPageBackground('bg-white')" class="w-full text-left px-4 py-3 rounded-lg bg-white dark:bg-white/5 border border-adaptive text-xs font-bold hover:border-indigo-500 transition"><code>bg-white</code><span class="block text-muted mt-1 font-normal">Latar halaman putih polos.</span></button>
                                            <button onclick="setPageBackground('bg-slate-100')" class="w-full text-left px-4 py-3 rounded-lg bg-white dark:bg-white/5 border border-indigo-500 text-xs font-bold hover:border-indigo-500 transition"><code>bg-slate-100</code><span class="block text-muted mt-1 font-normal">Latar halaman lembut dan nyaman.</span></button>
                                            <button onclick="setPageBackground('bg-blue-600')" class="w-full text-left px-4 py-3 rounded-lg bg-white dark:bg-white/5 border border-adaptive text-xs font-bold hover:border-indigo-500 transition"><code>bg-blue-600</code><span class="block text-muted mt-1 font-normal">Terlalu kuat untuk latar halaman penuh.</span></button>
                                        </div>
                                        <pre id="page-bg-code" class="bg-white dark:bg-black/30 border border-adaptive rounded-xl p-4 overflow-auto custom-scrollbar text-xs md:text-sm font-mono leading-relaxed"></pre>
                                    </div>
                                    <div id="page-bg-preview" class="bg-slate-100 flex items-center justify-center p-8 transition-all duration-300">
                                        <article class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm max-w-xs w-full">
                                            <p class="text-[10px] uppercase tracking-widest font-bold text-indigo-600">Produk Pilihan</p>
                                            <h3 class="text-xl font-black text-slate-900 mt-2">Sepatu Kanvas</h3>
                                            <p class="text-sm text-slate-600 mt-2 leading-relaxed">Kartu putih terlihat lebih jelas di atas latar abu-abu muda.</p>
                                        </article>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gradient-to-r from-purple-50 to-transparent dark:from-purple-900/20 dark:to-transparent border-l-4 border-purple-500 p-4 rounded-r-xl">
                                <h5 class="text-xs font-bold text-purple-700 dark:text-purple-400 mb-1">Kesimpulan</h5>
                                <p class="text-[11px] text-slate-700 dark:text-slate-300 leading-relaxed m-0 text-justify"><code>bg-slate-100</code> paling tepat digunakan untuk memberi warna latar halaman yang lembut.</p>
                            </div>
                        </div>
                    </section>

                    <section id="section-53" class="lesson-section scroll-mt-32" data-lesson-id="53">
                        <div class="space-y-8">
                            <div class="space-y-4 border-l-4 border-indigo-500 pl-4 md:pl-6">
                                <span class="text-indigo-600 dark:text-indigo-400 font-mono text-xs uppercase tracking-widest">Lesson 3.2.3</span>
                                <h2 class="text-3xl md:text-5xl font-black text-heading leading-tight">Kontras Kartu <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-purple-600">dan Teks</span></h2>
                            </div>

                            <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-90 text-sm md:text-base leading-relaxed space-y-4 text-justify">
                                <p>Ketika halaman memakai latar <code>bg-slate-100</code>, kartu sebaiknya memakai <span class="hl-term">bg-white</span>. Kombinasi ini membuat kartu lebih mudah dibedakan dari latar halaman. Pembelajar dapat melihat bahwa latar halaman dan area konten memiliki peran yang berbeda.</p>
                                <p>Selain warna latar, warna teks juga perlu disusun. Judul dan harga dapat memakai <code>text-slate-900</code> agar terlihat tegas. Deskripsi dapat memakai <code>text-slate-600</code> agar tidak bersaing dengan judul. Pola ini membuat informasi pada kartu lebih mudah dibaca.</p>
                            </div>

                            <div class="sim-bg-adaptive border border-adaptive rounded-2xl overflow-hidden shadow-xl">
                                <div class="bg-indigo-600/95 dark:bg-indigo-900/95 text-white p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3 border-b border-indigo-400/50">
                                    <div class="text-xs font-bold uppercase tracking-widest">Simulasi 3 — Kartu di Atas Latar Abu-Abu</div>
                                    <p class="text-[11px] opacity-90 leading-relaxed m-0 md:text-right max-w-xl">Pilih warna kartu dan teks harga untuk melihat perbedaan kontras.</p>
                                </div>
                                <div class="grid grid-cols-1 lg:grid-cols-2 min-h-[380px]">
                                    <div class="code-adaptive border-b lg:border-b-0 lg:border-r border-adaptive p-5">
                                        <div class="space-y-5 mb-6">
                                            <div>
                                                <label class="block text-[10px] font-bold uppercase tracking-widest text-indigo-600 dark:text-indigo-400 mb-2">Warna kartu</label>
                                                <div class="grid grid-cols-3 gap-2">
                                                    <button onclick="setCardBackground('bg-white')" class="px-3 py-2 rounded-lg bg-white text-slate-900 border border-slate-300 text-xs font-bold">bg-white</button>
                                                    <button onclick="setCardBackground('bg-slate-100')" class="px-3 py-2 rounded-lg bg-slate-100 text-slate-900 border border-slate-300 text-xs font-bold">slate-100</button>
                                                    <button onclick="setCardBackground('bg-blue-600')" class="px-3 py-2 rounded-lg bg-blue-600 text-white border border-blue-500 text-xs font-bold">blue-600</button>
                                                </div>
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-bold uppercase tracking-widest text-pink-600 dark:text-pink-400 mb-2">Warna harga</label>
                                                <div class="grid grid-cols-3 gap-2">
                                                    <button onclick="setPriceText('text-slate-900')" class="px-3 py-2 rounded-lg bg-white dark:bg-white/5 border border-adaptive text-xs font-bold">slate-900</button>
                                                    <button onclick="setPriceText('text-blue-600')" class="px-3 py-2 rounded-lg bg-white dark:bg-white/5 border border-adaptive text-xs font-bold text-blue-600">blue-600</button>
                                                    <button onclick="setPriceText('text-slate-600')" class="px-3 py-2 rounded-lg bg-white dark:bg-white/5 border border-adaptive text-xs font-bold text-slate-600">slate-600</button>
                                                </div>
                                            </div>
                                        </div>
                                        <pre id="card-color-code" class="bg-white dark:bg-black/30 border border-adaptive rounded-xl p-4 overflow-auto custom-scrollbar text-xs md:text-sm font-mono leading-relaxed"></pre>
                                    </div>
                                    <div class="bg-slate-100 flex items-center justify-center p-8 transition-all duration-300">
                                        <article id="color-card-preview" class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm max-w-xs w-full transition-all duration-300">
                                            <p class="text-[10px] uppercase tracking-widest font-bold text-indigo-600">Produk Pilihan</p>
                                            <h3 id="color-card-title" class="text-xl font-black text-slate-900 mt-2">Sepatu Kanvas</h3>
                                            <p id="color-card-desc" class="text-sm text-slate-600 mt-2 leading-relaxed">Sepatu ringan untuk kegiatan harian.</p>
                                            <p id="color-card-price" class="text-lg font-black text-slate-900 mt-4">Rp150.000</p>
                                        </article>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gradient-to-r from-indigo-50 to-transparent dark:from-indigo-900/20 dark:to-transparent border-l-4 border-indigo-500 p-4 rounded-r-xl">
                                <h5 class="text-xs font-bold text-indigo-700 dark:text-indigo-400 mb-1">Kesimpulan</h5>
                                <p class="text-[11px] text-slate-700 dark:text-slate-300 leading-relaxed m-0 text-justify">Kartu di atas latar abu-abu muda paling mudah dibedakan ketika menggunakan <code>bg-white</code>. Judul dan harga dapat memakai <code>text-slate-900</code> agar lebih tegas.</p>
                            </div>
                        </div>
                    </section>

                    <section id="section-54" class="lesson-section scroll-mt-32" data-lesson-id="54">
                        <div class="space-y-8">
                            <div class="space-y-4 border-l-4 border-indigo-500 pl-4 md:pl-6">
                                <span class="text-indigo-600 dark:text-indigo-400 font-mono text-xs uppercase tracking-widest">Lesson 3.2.4</span>
                                <h2 class="text-3xl md:text-5xl font-black text-heading leading-tight">Warna Tombol <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-purple-600">Berdasarkan Fungsi</span></h2>
                            </div>

                            <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-90 text-sm md:text-base leading-relaxed space-y-4 text-justify">
                                <p>Warna tombol tidak hanya berfungsi sebagai hiasan. Warna membantu pengguna mengenali makna tindakan. Tombol biru seperti <span class="hl-term">bg-blue-600 text-white</span> sering digunakan untuk aksi utama, misalnya simpan, lanjut, atau lihat detail.</p>
                                <p>Untuk tindakan yang menunjukkan keberhasilan, warna hijau lebih sesuai. Class <span class="hl-term">bg-green-600 text-white</span> dapat digunakan pada tombol berhasil, selesai, atau konfirmasi positif. Dengan warna yang tepat, pengguna lebih mudah memahami fungsi tombol tanpa membaca penjelasan panjang.</p>
                            </div>

                            <div class="sim-bg-adaptive border border-adaptive rounded-2xl overflow-hidden shadow-xl">
                                <div class="bg-purple-600/95 dark:bg-purple-900/95 text-white p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3 border-b border-purple-400/50">
                                    <div class="text-xs font-bold uppercase tracking-widest">Simulasi 4 — Pilih Warna Tombol</div>
                                    <p class="text-[11px] opacity-90 leading-relaxed m-0 md:text-right max-w-xl">Pilih fungsi tombol. Preview akan menampilkan class warna yang sesuai.</p>
                                </div>
                                <div class="grid grid-cols-1 lg:grid-cols-2 min-h-[360px]">
                                    <div class="code-adaptive border-b lg:border-b-0 lg:border-r border-adaptive p-5">
                                        <div class="space-y-3 mb-6">
                                            <button onclick="setActionButton('primary')" class="w-full text-left px-4 py-3 rounded-lg bg-white dark:bg-white/5 border border-adaptive text-xs font-bold hover:border-indigo-500 transition">Aksi utama<span class="block text-muted mt-1 font-normal">Contoh: simpan, lanjut, lihat detail.</span></button>
                                            <button onclick="setActionButton('success')" class="w-full text-left px-4 py-3 rounded-lg bg-white dark:bg-white/5 border border-adaptive text-xs font-bold hover:border-indigo-500 transition">Aksi berhasil<span class="block text-muted mt-1 font-normal">Contoh: selesai, berhasil, konfirmasi.</span></button>
                                            <button onclick="setActionButton('secondary')" class="w-full text-left px-4 py-3 rounded-lg bg-white dark:bg-white/5 border border-adaptive text-xs font-bold hover:border-indigo-500 transition">Aksi sekunder<span class="block text-muted mt-1 font-normal">Contoh: batal atau kembali.</span></button>
                                        </div>
                                        <pre id="button-color-code" class="bg-white dark:bg-black/30 border border-adaptive rounded-xl p-4 overflow-auto custom-scrollbar text-xs md:text-sm font-mono leading-relaxed"></pre>
                                    </div>
                                    <div class="bg-white dark:bg-slate-950 flex flex-col">
                                        <div class="px-4 py-3 border-b border-adaptive bg-slate-50 dark:bg-black/20">
                                            <span class="text-[10px] font-mono font-bold text-muted uppercase tracking-widest">Hasil Tampilan</span>
                                        </div>
                                        <div class="flex-1 flex items-center justify-center p-8 bg-slate-100 dark:bg-slate-900/40">
                                            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm w-full max-w-sm">
                                                <h3 class="text-xl font-black text-slate-900">Konfirmasi Pembelian</h3>
                                                <p class="text-sm text-slate-600 mt-2 leading-relaxed">Pilih warna tombol yang sesuai dengan fungsi tindakan.</p>
                                                <button id="action-button-preview" class="mt-5 w-full px-4 py-3 rounded-xl bg-blue-600 text-white text-sm font-bold transition-all duration-300">Lanjutkan</button>
                                                <p id="action-button-note" class="text-xs text-slate-500 leading-relaxed mt-3">Biru digunakan untuk aksi utama.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gradient-to-r from-purple-50 to-transparent dark:from-purple-900/20 dark:to-transparent border-l-4 border-purple-500 p-4 rounded-r-xl">
                                <h5 class="text-xs font-bold text-purple-700 dark:text-purple-400 mb-1">Kesimpulan</h5>
                                <p class="text-[11px] text-slate-700 dark:text-slate-300 leading-relaxed m-0 text-justify">Warna tombol harus sesuai dengan makna tindakan. Untuk tindakan berhasil, pilihan yang sesuai adalah <code>bg-green-600 text-white</code>.</p>
                            </div>
                        </div>
                    </section>

                    <section id="section-55" class="lesson-section scroll-mt-32 pt-10 border-t border-adaptive transition-colors" data-lesson-id="55" data-type="activity">
                        <div class="relative rounded-[2rem] md:rounded-[2.5rem] sim-bg-adaptive border border-adaptive p-6 md:p-10 overflow-hidden shadow-xl group hover:border-indigo-500/30 transition-all duration-500">
                            <div class="absolute -top-24 -right-24 w-64 h-64 bg-indigo-600/10 dark:bg-indigo-600/20 blur-[100px] rounded-full pointer-events-none"></div>
                            <div id="lockOverlay" class="hidden absolute inset-0 bg-white/95 dark:bg-[#050912]/95 backdrop-blur-md z-40 flex-col items-center justify-center text-center p-8 rounded-[2rem] md:rounded-[2.5rem]">
                                <div class="w-20 h-20 bg-emerald-100 dark:bg-emerald-500/10 rounded-full flex items-center justify-center mb-6 border border-emerald-300 dark:border-emerald-500/50 shadow-[0_0_50px_rgba(16,185,129,.25)] animate-bounce">
                                    <svg class="w-10 h-10 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                </div>
                                <h3 class="text-2xl md:text-3xl font-black text-heading mb-2">Aktivitas Selesai</h3>
                                <p class="text-sm font-bold text-muted max-w-sm">Jawaban sudah sesuai. Progress subbab 3.2 berhasil disimpan.</p>
                            </div>

                            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 sm:gap-6 mb-8 relative z-10">
                                <div class="p-3 sm:p-4 bg-gradient-to-br from-indigo-600 to-purple-700 rounded-xl text-white shadow-lg shadow-indigo-500/30 shrink-0">
                                    <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z"/></svg>
                                </div>
                                <div>
                                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-3 mb-2">
                                        <h2 class="text-xl sm:text-3xl font-black text-heading tracking-tight">Aktivitas 3.2: Palette Builder</h2>
                                        <span class="px-2 py-0.5 rounded text-[9px] sm:text-[10px] font-bold bg-indigo-100 dark:bg-indigo-500/20 text-indigo-700 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-500/30 uppercase tracking-wider">Swatch Warna</span>
                                    </div>
                                    <p class="text-muted text-xs sm:text-sm leading-relaxed max-w-3xl text-justify">Pilih swatch warna untuk latar halaman, kartu, teks, dan tombol, lalu amati preview kartu!</p>
                                </div>
                            </div>

                            <div id="activityForm" class="space-y-5 relative z-10">
                                <div class="activity-question card-adaptive border rounded-2xl p-5" data-question="q1">
                                    <h3 class="text-sm font-bold text-heading leading-relaxed mb-4">1. Class Tailwind CSS yang digunakan untuk memberi warna latar pada elemen adalah ....</h3>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        <button onclick="chooseActivity(this,'q1','a')" class="activity-option flex items-center gap-3 text-left px-4 py-3 rounded-xl border border-adaptive bg-white dark:bg-white/5 hover:border-indigo-500 transition text-sm"><span class="w-5 h-5 rounded border border-adaptive flex items-center justify-center font-bold text-xs">□</span><code>bg-*</code></button>
                                        <button onclick="chooseActivity(this,'q1','b')" class="activity-option flex items-center gap-3 text-left px-4 py-3 rounded-xl border border-adaptive bg-white dark:bg-white/5 hover:border-indigo-500 transition text-sm"><span class="w-5 h-5 rounded border border-adaptive flex items-center justify-center font-bold text-xs">□</span><code>text-*</code></button>
                                        <button onclick="chooseActivity(this,'q1','c')" class="activity-option flex items-center gap-3 text-left px-4 py-3 rounded-xl border border-adaptive bg-white dark:bg-white/5 hover:border-indigo-500 transition text-sm"><span class="w-5 h-5 rounded border border-adaptive flex items-center justify-center font-bold text-xs">□</span><code>border-*</code></button>
                                        <button onclick="chooseActivity(this,'q1','d')" class="activity-option flex items-center gap-3 text-left px-4 py-3 rounded-xl border border-adaptive bg-white dark:bg-white/5 hover:border-indigo-500 transition text-sm"><span class="w-5 h-5 rounded border border-adaptive flex items-center justify-center font-bold text-xs">□</span><code>font-*</code></button>
                                    </div>
                                </div>

                                <div class="activity-question card-adaptive border rounded-2xl p-5" data-question="q2">
                                    <h3 class="text-sm font-bold text-heading leading-relaxed mb-4">2. Pada halaman web, latar <code>bg-slate-100</code> paling tepat digunakan untuk ....</h3>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        <button onclick="chooseActivity(this,'q2','a')" class="activity-option flex items-center gap-3 text-left px-4 py-3 rounded-xl border border-adaptive bg-white dark:bg-white/5 hover:border-indigo-500 transition text-sm"><span class="w-5 h-5 rounded border border-adaptive flex items-center justify-center font-bold text-xs">□</span>memberi warna latar halaman yang lembut</button>
                                        <button onclick="chooseActivity(this,'q2','b')" class="activity-option flex items-center gap-3 text-left px-4 py-3 rounded-xl border border-adaptive bg-white dark:bg-white/5 hover:border-indigo-500 transition text-sm"><span class="w-5 h-5 rounded border border-adaptive flex items-center justify-center font-bold text-xs">□</span>memberi warna teks utama yang gelap</button>
                                        <button onclick="chooseActivity(this,'q2','c')" class="activity-option flex items-center gap-3 text-left px-4 py-3 rounded-xl border border-adaptive bg-white dark:bg-white/5 hover:border-indigo-500 transition text-sm"><span class="w-5 h-5 rounded border border-adaptive flex items-center justify-center font-bold text-xs">□</span>memberi garis tepi pada kartu</button>
                                        <button onclick="chooseActivity(this,'q2','d')" class="activity-option flex items-center gap-3 text-left px-4 py-3 rounded-xl border border-adaptive bg-white dark:bg-white/5 hover:border-indigo-500 transition text-sm"><span class="w-5 h-5 rounded border border-adaptive flex items-center justify-center font-bold text-xs">□</span>membuat tombol terlihat berbahaya</button>
                                    </div>
                                </div>

                                <div class="activity-question card-adaptive border rounded-2xl p-5" data-question="q3">
                                    <h3 class="text-sm font-bold text-heading leading-relaxed mb-4">3. Sebuah kartu berada di atas latar halaman abu-abu muda. Agar area kartu mudah dibedakan, class yang paling tepat digunakan pada kartu adalah ....</h3>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        <button onclick="chooseActivity(this,'q3','a')" class="activity-option flex items-center gap-3 text-left px-4 py-3 rounded-xl border border-adaptive bg-white dark:bg-white/5 hover:border-indigo-500 transition text-sm"><span class="w-5 h-5 rounded border border-adaptive flex items-center justify-center font-bold text-xs">□</span><code>bg-white</code></button>
                                        <button onclick="chooseActivity(this,'q3','b')" class="activity-option flex items-center gap-3 text-left px-4 py-3 rounded-xl border border-adaptive bg-white dark:bg-white/5 hover:border-indigo-500 transition text-sm"><span class="w-5 h-5 rounded border border-adaptive flex items-center justify-center font-bold text-xs">□</span><code>text-white</code></button>
                                        <button onclick="chooseActivity(this,'q3','c')" class="activity-option flex items-center gap-3 text-left px-4 py-3 rounded-xl border border-adaptive bg-white dark:bg-white/5 hover:border-indigo-500 transition text-sm"><span class="w-5 h-5 rounded border border-adaptive flex items-center justify-center font-bold text-xs">□</span><code>bg-slate-100</code></button>
                                        <button onclick="chooseActivity(this,'q3','d')" class="activity-option flex items-center gap-3 text-left px-4 py-3 rounded-xl border border-adaptive bg-white dark:bg-white/5 hover:border-indigo-500 transition text-sm"><span class="w-5 h-5 rounded border border-adaptive flex items-center justify-center font-bold text-xs">□</span><code>text-slate-600</code></button>
                                    </div>
                                </div>

                                <div class="activity-question card-adaptive border rounded-2xl p-5" data-question="q4">
                                    <h3 class="text-sm font-bold text-heading leading-relaxed mb-4">4. Sebuah halaman memakai latar abu-abu muda, kartu putih, teks utama gelap, dan tombol biru. Pengembang ingin menambahkan tombol untuk tindakan berhasil. Pilihan warna yang paling sesuai adalah ....</h3>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        <button onclick="chooseActivity(this,'q4','a')" class="activity-option flex items-center gap-3 text-left px-4 py-3 rounded-xl border border-adaptive bg-white dark:bg-white/5 hover:border-indigo-500 transition text-sm"><span class="w-5 h-5 rounded border border-adaptive flex items-center justify-center font-bold text-xs">□</span><code>bg-green-600 text-white</code></button>
                                        <button onclick="chooseActivity(this,'q4','b')" class="activity-option flex items-center gap-3 text-left px-4 py-3 rounded-xl border border-adaptive bg-white dark:bg-white/5 hover:border-indigo-500 transition text-sm"><span class="w-5 h-5 rounded border border-adaptive flex items-center justify-center font-bold text-xs">□</span><code>bg-blue-600 text-white</code></button>
                                        <button onclick="chooseActivity(this,'q4','c')" class="activity-option flex items-center gap-3 text-left px-4 py-3 rounded-xl border border-adaptive bg-white dark:bg-white/5 hover:border-indigo-500 transition text-sm"><span class="w-5 h-5 rounded border border-adaptive flex items-center justify-center font-bold text-xs">□</span><code>bg-slate-100 text-slate-600</code></button>
                                        <button onclick="chooseActivity(this,'q4','d')" class="activity-option flex items-center gap-3 text-left px-4 py-3 rounded-xl border border-adaptive bg-white dark:bg-white/5 hover:border-indigo-500 transition text-sm"><span class="w-5 h-5 rounded border border-adaptive flex items-center justify-center font-bold text-xs">□</span><code>bg-white text-slate-900</code></button>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-4 relative z-10">
                                <div class="lg:col-span-2 bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-500/30 rounded-2xl p-5">
                                    <h3 class="text-sm font-black text-indigo-800 dark:text-indigo-300 mb-2">Status Aktivitas</h3>
                                    <div id="activity-analysis" class="hidden space-y-2 text-xs text-indigo-900 dark:text-indigo-100/80 leading-relaxed">
                                        <p>Aktivitas telah memenuhi skor minimal. Progress materi berhasil diproses.</p>
                                        <p>Jika ingin memperkuat pemahaman, ulangi pengamatan preview pada materi sebelum melanjutkan.</p>
                                    </div>
                                    <p id="activity-hint" class="text-xs text-indigo-900 dark:text-indigo-100/80 leading-relaxed">Status tambahan akan muncul setelah aktivitas memenuhi skor.</p>
                                </div>
                                <div class="card-adaptive border rounded-2xl p-5 flex flex-col justify-between gap-4">
                                    <div>
                                        <p class="text-[10px] uppercase tracking-widest font-bold text-muted mb-2">Hasil Aktivitas</p>
                                        <p id="activity-score" class="text-3xl font-black text-heading">0/5</p>
                                        <p id="activity-status" class="text-xs font-bold text-muted mt-2">Belum diperiksa.</p>
                                    </div>
                                    <div class="space-y-2">
                                        <button id="submitBtn" onclick="checkActivity()" class="w-full py-3 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-xs font-black uppercase tracking-wider shadow-lg shadow-indigo-500/20 hover:-translate-y-0.5 transition-all">Periksa Jawaban</button>
                                        <button onclick="resetActivity()" class="w-full py-3 rounded-xl bg-slate-200 dark:bg-white/10 text-xs font-black uppercase tracking-wider">Ulangi</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </article>

                <div class="mt-32 pt-8 border-t border-adaptive flex flex-col sm:flex-row justify-between items-center gap-4 sm:gap-0 transition-colors">
                    <a href="{{ \Illuminate\Support\Facades\Route::has('courses.typography') ? route('courses.typography') : '#' }}" class="group flex items-center gap-4 text-muted hover:text-heading transition w-full sm:w-auto justify-center sm:justify-start">
                        <div class="w-10 h-10 md:w-12 md:h-12 rounded-full border border-adaptive flex items-center justify-center bg-slate-100 dark:bg-transparent group-hover:bg-slate-200 dark:group-hover:bg-white/5 transition shrink-0">
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        </div>
                        <div class="text-center sm:text-left">
                            <div class="text-[10px] sm:text-xs uppercase tracking-widest font-bold opacity-50 mb-0.5">Sebelumnya</div>
                            <div class="font-bold text-xs md:text-sm line-clamp-1">Tipografi</div>
                        </div>
                    </a>

                    <div id="nextChapterBtn" class="group flex items-center gap-4 text-right text-muted cursor-not-allowed opacity-50 pointer-events-none transition-all duration-500 w-full sm:w-auto justify-center sm:justify-end flex-row-reverse sm:flex-row">
                        <div class="text-center sm:text-right">
                            <div id="nextLabel" class="text-[10px] sm:text-xs uppercase tracking-widest font-bold opacity-50 mb-0.5">Terkunci</div>
                            <div class="font-bold text-xs md:text-sm line-clamp-1">Border, Radius, dan Bayangan</div>
                        </div>
                        <div id="nextIcon" class="w-10 h-10 md:w-12 md:h-12 rounded-full border border-adaptive flex items-center justify-center bg-slate-100 dark:bg-white/5 shrink-0 transition-colors">
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
    window.LESSON_IDS = [51, 52, 53, 54, 55];
    let rawCompletedIds = {!! json_encode($completedLessonIds ?? []) !!};
    window.COMPLETED_IDS = rawCompletedIds.map(id => Number(id));
    let completedSet = new Set(window.COMPLETED_IDS);
    const ACTIVITY_LESSON_ID = 55;
    let activityCompleted = {!! ($activityCompleted ?? false) ? 'true' : 'false' !!};
    let activityAnswers = {};
    let activityWidget = null;

    document.addEventListener('DOMContentLoaded', () => {
        updateProgressUI(false);
        initObservers();
        initScrollSpy();
        initVisualEffects();
        setColorPattern('bg');
        setPageBackground('bg-slate-100');
        updateCardColorCode();
        setActionButton('primary');
        initBackgroundActivity();

        if (activityCompleted) {
            lockActivityUI(true);
            unlockNextChapter();
        }

        document.querySelectorAll('.nav-item').forEach(item => {
            const target = item.getAttribute('data-target') || '';
            const id = Number(target.replace('#section-', ''));
            if (completedSet.has(id)) markSidebarDone(id);
        });
    });

    function updateProgressUI(animate = true) {
        const total = window.LESSON_IDS.length;
        const done = window.LESSON_IDS.filter(id => completedSet.has(Number(id))).length;
        const percent = Math.round((done / total) * 100);
        const bar = document.getElementById('topProgressBar');
        const label = document.getElementById('progressLabelTop');
        if (!bar || !label) return;
        if (!animate) bar.style.transition = 'none';
        bar.style.width = percent + '%';
        if (!animate) setTimeout(() => bar.style.transition = 'all .5s', 50);
        label.innerText = percent + '%';
        if (percent === 100 && activityCompleted) unlockNextChapter();
    }

    function markSidebarDone(lessonId) {
        const navItem = document.querySelector(`.nav-item[data-target="#section-${lessonId}"]`);
        if (navItem) {
            const dot = navItem.querySelector('.dot');
            if (dot) dot.outerHTML = `<svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>`;
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
                return true;
            }
        } catch (e) { console.error('Network Error:', e); }
        return false;
    }

    function initObservers() {
        const main = document.getElementById('mainScroll');
        const sections = document.querySelectorAll('.lesson-section');
        if (!main || !sections.length) return;

        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const section = entry.target;
                    const lessonId = Number(section.dataset.lessonId);
                    const isActivity = section.dataset.type === 'activity';
                    highlightAnchor(section.id);
                    if (lessonId && !isActivity && !completedSet.has(lessonId)) {
                        saveLessonToDB(lessonId);
                    }
                }
            });
        }, { root: main, rootMargin: '-10% 0px -60% 0px', threshold: 0 });

        sections.forEach(section => observer.observe(section));
    }

    function highlightAnchor(id) {
        document.querySelectorAll('.nav-item').forEach(item => item.classList.remove('active'));
        const active = document.querySelector(`.nav-item[data-target="#${id}"]`);
        if (active) active.classList.add('active');
    }

    function initScrollSpy() {
        const main = document.getElementById('mainScroll');
        if (!main) return;
        const sections = document.querySelectorAll('.lesson-section');
        main.addEventListener('scroll', () => {
            let current = '';
            sections.forEach(sec => {
                const top = sec.getBoundingClientRect().top;
                if (top < 240) current = sec.id;
            });
            if (current) highlightAnchor(current);
        }, { passive: true });
    }

    function setColorPattern(type) {
        const card = document.getElementById('pattern-card');
        const title = document.getElementById('pattern-title');
        const desc = document.getElementById('pattern-desc');
        const code = document.getElementById('pattern-code');
        if (!card || !code) return;

        card.className = 'rounded-2xl p-6 shadow-sm max-w-xs transition-all duration-300 border bg-white text-slate-900 border-slate-300';
        title.className = 'text-xl font-bold';
        desc.className = 'text-sm mt-2 text-slate-600';

        let explanation = 'Class bg-* digunakan untuk memberi warna latar pada elemen.';
        if (type === 'bg') {
            card.className = 'rounded-2xl p-6 shadow-sm max-w-xs transition-all duration-300 border bg-blue-600 text-white border-blue-600';
            desc.className = 'text-sm mt-2 text-white/80';
            explanation = 'bg-blue-600 mengubah warna latar kartu menjadi biru.';
        }
        if (type === 'text') {
            card.className = 'rounded-2xl p-6 shadow-sm max-w-xs transition-all duration-300 border bg-white text-blue-600 border-slate-300';
            desc.className = 'text-sm mt-2 text-blue-600/80';
            explanation = 'text-* mengubah warna teks, bukan latar.';
        }
        if (type === 'border') {
            card.className = 'rounded-2xl p-6 shadow-sm max-w-xs transition-all duration-300 border-4 bg-white text-slate-900 border-indigo-500';
            explanation = 'border-indigo-500 mengubah warna garis tepi kartu.';
        }
        if (type === 'font') {
            title.className = 'text-xl font-black';
            explanation = 'font-* mengubah jenis atau ketebalan huruf. Class ini bukan untuk warna.';
        }

        code.innerHTML = `<code><span class="tag">&lt;article</span> <span class="attr">class</span>=<span class="str">"${card.className.replace('rounded-2xl p-6 shadow-sm max-w-xs transition-all duration-300 ', '')}"</span><span class="tag">&gt;</span>
  <span class="comment">${explanation}</span>
<span class="tag">&lt;/article&gt;</span></code>`;
    }

    function setPageBackground(cls) {
        const preview = document.getElementById('page-bg-preview');
        const code = document.getElementById('page-bg-code');
        if (!preview || !code) return;
        preview.className = `${cls} flex items-center justify-center p-8 transition-all duration-300`;
        code.innerHTML = `<code><span class="tag">&lt;section</span> <span class="attr">class</span>=<span class="str">"${cls} p-6"</span><span class="tag">&gt;</span>
  <span class="comment">latar halaman</span>
<span class="tag">&lt;/section&gt;</span></code>`;
    }

    let currentCardBg = 'bg-white';
    let currentPriceColor = 'text-slate-900';

    function setCardBackground(cls) {
        currentCardBg = cls;
        updateCardColorCode();
    }

    function setPriceText(cls) {
        currentPriceColor = cls;
        updateCardColorCode();
    }

    function updateCardColorCode() {
        const card = document.getElementById('color-card-preview');
        const title = document.getElementById('color-card-title');
        const desc = document.getElementById('color-card-desc');
        const price = document.getElementById('color-card-price');
        const code = document.getElementById('card-color-code');
        if (!card || !price || !code) return;

        card.className = `${currentCardBg} border border-slate-200 rounded-2xl p-6 shadow-sm max-w-xs w-full transition-all duration-300`;
        title.className = 'text-xl font-black text-slate-900 mt-2';
        desc.className = 'text-sm text-slate-600 mt-2 leading-relaxed';
        price.className = `text-lg font-black ${currentPriceColor} mt-4`;

        if (currentCardBg === 'bg-blue-600') {
            title.className = 'text-xl font-black text-white mt-2';
            desc.className = 'text-sm text-white/80 mt-2 leading-relaxed';
            price.className = 'text-lg font-black text-white mt-4';
        }

        code.innerHTML = `<code><span class="tag">&lt;article</span> <span class="attr">class</span>=<span class="str">"${currentCardBg} p-6 rounded-xl"</span><span class="tag">&gt;</span>
  <span class="tag">&lt;p</span> <span class="attr">class</span>=<span class="str">"${currentPriceColor}"</span><span class="tag">&gt;</span>Rp150.000<span class="tag">&lt;/p&gt;</span>
<span class="tag">&lt;/article&gt;</span></code>`;
    }

    function setActionButton(type) {
        const btn = document.getElementById('action-button-preview');
        const note = document.getElementById('action-button-note');
        const code = document.getElementById('button-color-code');
        if (!btn || !code) return;

        if (type === 'primary') {
            btn.className = 'mt-5 w-full px-4 py-3 rounded-xl bg-blue-600 text-white text-sm font-bold transition-all duration-300';
            btn.innerText = 'Lanjutkan';
            note.innerText = 'Biru digunakan untuk aksi utama.';
            code.innerHTML = `<code><span class="tag">&lt;button</span> <span class="attr">class</span>=<span class="str">"bg-blue-600 text-white"</span><span class="tag">&gt;</span>Lanjutkan<span class="tag">&lt;/button&gt;</span></code>`;
        }
        if (type === 'success') {
            btn.className = 'mt-5 w-full px-4 py-3 rounded-xl bg-green-600 text-white text-sm font-bold transition-all duration-300';
            btn.innerText = 'Berhasil';
            note.innerText = 'Hijau digunakan untuk aksi berhasil atau konfirmasi positif.';
            code.innerHTML = `<code><span class="tag">&lt;button</span> <span class="attr">class</span>=<span class="str">"bg-green-600 text-white"</span><span class="tag">&gt;</span>Berhasil<span class="tag">&lt;/button&gt;</span></code>`;
        }
        if (type === 'secondary') {
            btn.className = 'mt-5 w-full px-4 py-3 rounded-xl bg-slate-100 text-slate-700 text-sm font-bold transition-all duration-300';
            btn.innerText = 'Kembali';
            note.innerText = 'Abu-abu lembut dapat digunakan untuk aksi sekunder.';
            code.innerHTML = `<code><span class="tag">&lt;button</span> <span class="attr">class</span>=<span class="str">"bg-slate-100 text-slate-700"</span><span class="tag">&gt;</span>Kembali<span class="tag">&lt;/button&gt;</span></code>`;
        }
    }

    function chooseActivity(btn, q, ans) {
        if (activityCompleted) return;
        activityAnswers[q] = ans;
        const group = btn.closest('.activity-question');
        group.querySelectorAll('.activity-option').forEach(b => {
            b.classList.remove('bg-indigo-600', 'text-white', 'border-indigo-500');
            const square = b.querySelector('span');
            if (square) square.innerText = '□';
        });
        btn.classList.add('bg-indigo-600', 'text-white', 'border-indigo-500');
        const square = btn.querySelector('span');
        if (square) square.innerText = '✓';
    }

    function initBackgroundActivity() {
        activityWidget = CourseActivityKit.mountChoiceBuilderActivity({
            root: '#activityForm',
            badge: 'Palette Builder',
            title: 'Rancang palet warna antarmuka',
            description: 'Pilih kombinasi warna yang membedakan latar halaman, kartu, teks, dan tombol aksi!',
            previewLabel: 'Preview Palet',
            minScore: 4,
            groups: [
                {
                    id: 'page',
                    label: 'Latar halaman',
                    desc: 'Halaman perlu latar lembut agar kartu menonjol.',
                    correct: 'soft',
                    default: 'blank',
                    options: [
                        { id: 'blank', label: 'Putih polos', classText: 'bg-white', color: '#ffffff' },
                        { id: 'soft', label: 'Abu-abu lembut', classText: 'bg-slate-100', color: '#f1f5f9' },
                        { id: 'strong', label: 'Biru kuat', classText: 'bg-blue-600', color: '#2563eb' }
                    ]
                },
                {
                    id: 'card',
                    label: 'Permukaan kartu',
                    desc: 'Kartu harus terbaca jelas di atas latar.',
                    correct: 'white',
                    default: 'same',
                    options: [
                        { id: 'same', label: 'Sama dengan latar', classText: 'bg-slate-100', color: '#f1f5f9' },
                        { id: 'white', label: 'Kartu putih', classText: 'bg-white', color: '#ffffff' },
                        { id: 'dark', label: 'Kartu gelap', classText: 'bg-slate-900', color: '#0f172a' }
                    ]
                },
                {
                    id: 'title',
                    label: 'Teks utama',
                    desc: 'Judul perlu warna paling kuat.',
                    correct: 'dark',
                    default: 'muted',
                    options: [
                        { id: 'muted', label: 'Pendukung', classText: 'text-slate-500', color: '#64748b' },
                        { id: 'dark', label: 'Utama', classText: 'text-slate-900', color: '#0f172a' },
                        { id: 'light', label: 'Terlalu terang', classText: 'text-white', color: '#ffffff' }
                    ]
                },
                {
                    id: 'body',
                    label: 'Teks pendukung',
                    desc: 'Deskripsi perlu lebih lembut dari judul.',
                    correct: 'muted',
                    default: 'dark',
                    options: [
                        { id: 'dark', label: 'Terlalu kuat', classText: 'text-slate-900', color: '#0f172a' },
                        { id: 'muted', label: 'Lembut', classText: 'text-slate-600', color: '#475569' },
                        { id: 'accent', label: 'Aksen', classText: 'text-indigo-600', color: '#4f46e5' }
                    ]
                },
                {
                    id: 'button',
                    label: 'Tombol aksi',
                    desc: 'Aksi berhasil memakai warna kuat dengan teks kontras.',
                    correct: 'success',
                    default: 'secondary',
                    options: [
                        { id: 'secondary', label: 'Sekunder', classText: 'bg-slate-100 text-slate-700', color: '#f1f5f9' },
                        { id: 'success', label: 'Berhasil', classText: 'bg-green-600 text-white', color: '#16a34a' },
                        { id: 'flat', label: 'Terlalu datar', classText: 'bg-white text-slate-900', color: '#ffffff' }
                    ]
                }
            ],
            renderPreview: (state, selected) => `
                <section class="w-full min-h-[300px] ${selected.page.classText} p-6 grid place-items-center">
                    <article class="max-w-sm ${selected.card.classText} border border-slate-200 rounded-2xl p-6 shadow-md">
                        <p class="text-sm ${selected.body.classText}">Status pesanan</p>
                        <h1 class="mt-1 text-2xl font-bold ${selected.title.classText}">Pesanan berhasil dibuat</h1>
                        <p class="mt-3 ${selected.body.classText}">Gunakan warna untuk membedakan informasi utama dan pendukung.</p>
                        <button class="mt-5 px-4 py-3 rounded-xl font-bold ${selected.button.classText}">Lihat Detail</button>
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

        scoreLabel.innerText = `${result.score}/${result.total}`;
        document.getElementById('activity-analysis').classList.toggle('hidden', !result.passed);
        document.getElementById('activity-hint')?.classList.toggle('hidden', result.passed);

        if (result.passed) {
            status.innerText = 'Aktivitas berhasil. Kombinasi warna dan latar sudah sesuai.';
            status.className = 'text-xs font-bold text-emerald-600 dark:text-emerald-400';
            const saved = await saveLessonToDB(ACTIVITY_LESSON_ID);
            if (saved) {
                activityCompleted = true;
                lockActivityUI(false);
                unlockNextChapter();
            } else {
                status.innerText = 'Aktivitas valid, tetapi progress belum berhasil disimpan. Periksa koneksi atau route penyimpanan.';
                status.className = 'text-xs font-bold text-orange-600 dark:text-orange-400';
            }
        } else {
            status.innerText = 'Skor belum cukup. Ubah pilihan pada panel, lalu amati kembali preview.';
            status.className = 'text-xs font-bold text-orange-600 dark:text-orange-400';
            submit.classList.add('shake');
            setTimeout(() => submit.classList.remove('shake'), 500);
        }
    }

    function resetActivity() {
        if (activityCompleted) return;
        if (activityWidget) activityWidget.reset();
        document.getElementById('activity-score').innerText = '0/5';
        document.getElementById('activity-status').innerText = 'Belum diperiksa.';
        document.getElementById('activity-status').className = 'text-xs font-bold text-muted mt-2';
        document.getElementById('activity-analysis').classList.add('hidden');
        document.getElementById('activity-hint').classList.remove('hidden');
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

            btn.onclick = () => window.location.href = "{{ \Illuminate\Support\Facades\Route::has('courses.borders') ? route('courses.borders') : '#' }}";
        }
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
