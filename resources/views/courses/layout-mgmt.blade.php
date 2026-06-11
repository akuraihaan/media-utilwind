@extends('layouts.landing')
@section('title','Grid')

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
    :root {
        --bg-main: #f8fafc;
        --text-main: #0f172a;
        --glass-header: rgba(255,255,255,.86);
        --card-bg: #ffffff;
        --border-color: rgba(15,23,42,.10);
        --text-muted: #64748b;
        --text-heading: #0f172a;
        --code-bg: #f1f5f9;
        --simulator-bg: #ffffff;
        --accent: #06b6d4;
        --accent-glow: rgba(6,182,212,.34);
    }
    .dark {
        --bg-main: #020617;
        --text-main: #e2e8f0;
        --glass-header: rgba(2,6,23,.82);
        --card-bg: #0f172a;
        --border-color: rgba(255,255,255,.10);
        --text-muted: rgba(226,232,240,.62);
        --text-heading: #ffffff;
        --code-bg: #111827;
        --simulator-bg: #0b1120;
        --accent-glow: rgba(6,182,212,.52);
    }

    body { font-family: 'Inter', sans-serif; background-color: var(--bg-main); color: var(--text-main); transition: background-color .4s, color .4s; }
    .font-mono { font-family: 'JetBrains Mono', monospace; }
    .bg-adaptive { background-color: var(--bg-main); }
    .text-adaptive { color: var(--text-main); }
    .text-heading { color: var(--text-heading); }
    .text-muted { color: var(--text-muted); }
    .border-adaptive { border-color: var(--border-color); }
    .card-adaptive { background-color: var(--card-bg); border-color: var(--border-color); transition: all .25s ease; }
    .card-adaptive:hover { border-color: var(--accent-glow); transform: translateY(-1px); }
    .sim-bg-adaptive { background-color: var(--simulator-bg); }
    .code-adaptive { background-color: var(--code-bg); border-color: var(--border-color); }

    .hl-term {
        background-color: rgba(6,182,212,.14);
        color: #0e7490;
        padding: .125rem .375rem;
        border-radius: .375rem;
        font-weight: 700;
        white-space: nowrap;
        border: 1px solid rgba(6,182,212,.28);
    }
    .dark .hl-term { color: #67e8f9; background-color: rgba(6,182,212,.18); border-color: rgba(6,182,212,.36); }
    .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(148,163,184,.42); border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: var(--accent); }
    #animated-bg {
        background: radial-gradient(850px circle at 18% 18%, rgba(6,182,212,.12), transparent 42%),
                    radial-gradient(850px circle at 82% 78%, rgba(59,130,246,.12), transparent 42%);
        animation: bgMove 20s ease-in-out infinite alternate;
    }
    .dark #animated-bg {
        background: radial-gradient(850px circle at 18% 18%, rgba(6,182,212,.18), transparent 42%),
                    radial-gradient(850px circle at 82% 78%, rgba(59,130,246,.16), transparent 42%);
    }
    @keyframes bgMove { to { transform: scale(1.12); } }
    @keyframes shake { 0%,100% { transform: translateX(0); } 25% { transform: translateX(-5px); } 75% { transform: translateX(5px); } }
    .shake { animation: shake .35s ease-in-out; }

    .nav-item { display: flex; width: 100%; text-align: left; align-items: center; gap: 12px; padding: 10px 14px; font-size: .85rem; color: var(--text-muted); border-radius: 8px; transition: all .2s; position: relative; }
    .nav-item:hover { color: var(--text-main); background: rgba(6,182,212,.06); }
    .nav-item.active { color: #0891b2; background: rgba(6,182,212,.08); font-weight: 700; }
    .dark .nav-item.active { color: #67e8f9; }
    .dot { width: 6px; height: 6px; border-radius: 50%; background: #94a3b8; transition: all .3s; }
    .dark .dot { background: #334155; }
    .nav-item.active .dot { background: #06b6d4; box-shadow: 0 0 10px #06b6d4; transform: scale(1.25); }

    .grid-paper {
        background-size: 24px 24px;
        background-image: linear-gradient(to right, rgba(148,163,184,.16) 1px, transparent 1px),
                          linear-gradient(to bottom, rgba(148,163,184,.16) 1px, transparent 1px);
    }
    .choice-card.selected { border-color: #06b6d4; background: rgba(6,182,212,.10); }
    .choice-card.correct { border-color: #10b981; background: rgba(16,185,129,.12); }
    .choice-card.wrong { border-color: #f43f5e; background: rgba(244,63,94,.10); }
</style>

<div id="courseRoot" class="relative h-screen bg-adaptive text-adaptive font-sans overflow-hidden flex flex-col selection:bg-cyan-500/30 pt-20 transition-colors duration-500">
    <div class="fixed inset-0 -z-50 pointer-events-none">
        <div id="animated-bg" class="absolute inset-0 opacity-60 transition-opacity"></div>
        <div class="absolute top-[-20%] right-[-10%] w-[800px] h-[800px] bg-cyan-400/10 dark:bg-cyan-900/20 rounded-full blur-[130px] animate-pulse transition-colors"></div>
        <div class="absolute bottom-[-20%] left-[-10%] w-[700px] h-[700px] bg-blue-500/10 dark:bg-blue-900/20 rounded-full blur-[120px] transition-colors"></div>
        <canvas id="stars" class="absolute inset-0 pointer-events-none opacity-0 dark:opacity-100 transition-opacity duration-500"></canvas>
    </div>

    @include('layouts.partials.navbar')

    <div class="flex flex-1 overflow-hidden relative z-20 h-full">
        @include('layouts.partials.course-sidebar')

        <main id="mainScroll" class="flex-1 h-full overflow-y-auto scroll-smooth relative bg-transparent custom-scrollbar scroll-padding-top-24">
            <div id="stickyHeader" class="sticky top-0 z-30 w-full backdrop-blur-2xl border-b border-adaptive px-4 md:px-8 py-4 flex items-center justify-between transition-colors duration-500" style="background-color: var(--glass-header);">
                <div class="flex items-center gap-4 min-w-0">
                    <div class="w-8 h-8 rounded-lg bg-cyan-500/10 dark:bg-cyan-500/20 border border-cyan-500/20 flex items-center justify-center font-bold text-xs text-cyan-600 dark:text-cyan-400 shrink-0">2.3</div>
                    <div class="min-w-0">
                        <h1 class="text-sm font-bold text-heading line-clamp-1 transition-colors">Grid</h1>
                        <p class="text-[10px] text-muted line-clamp-1 transition-colors">Tata Letak Dua Dimensi</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <div class="hidden sm:block w-24 md:w-32 h-1.5 bg-slate-200 dark:bg-white/10 rounded-full overflow-hidden transition-colors">
                        <div id="topProgressBar" class="h-full bg-gradient-to-r from-cyan-400 to-blue-500 w-0 transition-all duration-500 shadow-[0_0_12px_rgba(6,182,212,.55)]"></div>
                    </div>
                    <span id="progressLabelTop" class="text-cyan-600 dark:text-cyan-400 font-bold text-xs transition-colors">0%</span>
                </div>
            </div>

            <div class="p-4 sm:p-6 lg:p-16 max-w-5xl mx-auto pb-40">
                <div class="mb-16 md:mb-24">
                    <div class="card-adaptive rounded-3xl border p-6 md:p-8 overflow-hidden relative mb-10">
                        <div class="absolute -right-10 -top-10 w-44 h-44 bg-cyan-500/10 rounded-full blur-3xl"></div>
                        <p class="text-xs font-mono font-bold text-cyan-600 dark:text-cyan-400 uppercase tracking-[.25em] mb-3">Subbab 2.3</p>
                        <h2 class="text-3xl md:text-5xl font-black text-heading leading-tight mb-4">Grid pada Tailwind CSS</h2>
                        <p class="text-sm md:text-base text-muted leading-relaxed max-w-3xl text-justify">
                            Pada subbab ini, pembelajar mempelajari cara menyusun kartu dan konten menggunakan Grid. Materi difokuskan pada class <code>grid</code>, <code>grid-cols-3</code>, <code>gap-4</code>, <code>col-span-2</code>, dan <code>col-span-full</code>.
                        </p>
                    </div>
                    <h3 class="text-xl font-bold text-heading mb-6 flex items-center gap-2 transition-colors">
                        <svg class="w-5 h-5 text-cyan-500 dark:text-cyan-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M4 6a2 2 0 012-2h3a2 2 0 012 2v3a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm9 0a2 2 0 012-2h3a2 2 0 012 2v3a2 2 0 01-2 2h-3a2 2 0 01-2-2V6zM4 15a2 2 0 012-2h3a2 2 0 012 2v3a2 2 0 01-2 2H6a2 2 0 01-2-2v-3zm9 0a2 2 0 012-2h3a2 2 0 012 2v3a2 2 0 01-2 2h-3a2 2 0 01-2-2v-3z"/></svg>
                        Tujuan Pembelajaran
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="card-adaptive border p-5 rounded-xl flex items-start gap-4 h-full">
                            <div class="w-9 h-9 rounded-lg bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 flex items-center justify-center shrink-0 font-bold text-xs border border-cyan-500/20">1</div>
                            <div><h4 class="text-sm font-bold text-heading mb-1">Konsep Grid</h4><p class="text-[11px] text-muted leading-relaxed">Membedakan grid sebagai sistem layout dua dimensi.</p></div>
                        </div>
                        <div class="card-adaptive border p-5 rounded-xl flex items-start gap-4 h-full">
                            <div class="w-9 h-9 rounded-lg bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0 font-bold text-xs border border-blue-500/20">2</div>
                            <div><h4 class="text-sm font-bold text-heading mb-1">Kolom dan Gap</h4><p class="text-[11px] text-muted leading-relaxed">Mengatur jumlah kolom dan jarak antar item secara konsisten.</p></div>
                        </div>
                        <div class="card-adaptive border p-5 rounded-xl flex items-start gap-4 h-full">
                            <div class="w-9 h-9 rounded-lg bg-sky-500/10 text-sky-600 dark:text-sky-400 flex items-center justify-center shrink-0 font-bold text-xs border border-sky-500/20">3</div>
                            <div><h4 class="text-sm font-bold text-heading mb-1">Span</h4><p class="text-[11px] text-muted leading-relaxed">Membuat item melebar atau memanjang melewati beberapa sel.</p></div>
                        </div>
                        <div class="card-adaptive border p-5 rounded-xl flex items-start gap-4 h-full">
                            <div class="w-9 h-9 rounded-lg bg-teal-500/10 text-teal-600 dark:text-teal-400 flex items-center justify-center shrink-0 font-bold text-xs border border-teal-500/20">4</div>
                            <div><h4 class="text-sm font-bold text-heading mb-1">Span Penuh</h4><p class="text-[11px] text-muted leading-relaxed">Memilih <code>col-span-full</code> saat satu kartu perlu menempati seluruh kolom.</p></div>
                        </div>
                        <div class="col-span-1 sm:col-span-2 lg:col-span-4 bg-gradient-to-br from-cyan-50 to-blue-50 dark:from-cyan-900/30 dark:to-blue-900/20 border border-cyan-200 dark:border-cyan-500/20 p-5 rounded-xl flex items-start gap-4">
                            <div class="w-9 h-9 rounded-lg bg-white/70 dark:bg-white/10 text-cyan-700 dark:text-white flex items-center justify-center shrink-0 font-bold text-sm">🏁</div>
                            <div><h4 class="text-sm font-bold text-cyan-900 dark:text-white mb-1">Aktivitas Akhir</h4><p class="text-[11px] text-cyan-800 dark:text-cyan-100/70 leading-relaxed">Pilih class grid yang tepat untuk menyelesaikan beberapa kasus layout kartu, galeri, dan panel dashboard.</p></div>
                        </div>
                    </div>
                </div>

                <article class="space-y-24 md:space-y-32">
                    <section id="section-36" class="lesson-section scroll-mt-32" data-lesson-id="36">
                        <div class="space-y-8 md:space-y-10">
                            <div class="space-y-4 border-l-4 border-cyan-500 pl-4 md:pl-6">
                                <span class="text-cyan-600 dark:text-cyan-400 font-mono text-xs uppercase tracking-widest">Lesson 2.3.1</span>
                                <h2 class="text-2xl md:text-4xl lg:text-5xl font-black text-heading leading-[1.1]">Konsep Dasar <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-500 to-blue-600 dark:from-cyan-400 dark:to-blue-500">Grid Layout</span></h2>
                            </div>
                            <div class="space-y-4 prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-85 text-sm md:text-base leading-relaxed text-justify">
                                <p>Grid digunakan ketika susunan halaman membutuhkan kontrol terhadap <strong>baris dan kolom sekaligus</strong>. Jika Flexbox lebih cocok untuk susunan satu arah, Grid lebih tepat untuk menyusun struktur yang berbentuk bidang, seperti galeri produk, daftar artikel, dashboard, dan kartu informasi yang tersusun rapi dalam beberapa kolom.</p>
                                <p>Dalam Tailwind CSS, Grid dimulai dari class <span class="hl-term">grid</span>. Class ini menjadikan elemen pembungkus sebagai wadah grid. Setelah itu, jumlah kolom diatur dengan class seperti <span class="hl-term">grid-cols-2</span>, <span class="hl-term">grid-cols-3</span>, atau <span class="hl-term">grid-cols-4</span>. Setiap elemen anak akan ditempatkan ke dalam sel grid sesuai urutan HTML.</p>
                                <p>Konsep penting yang harus dipahami adalah bahwa grid tidak hanya mengatur posisi item, tetapi juga membentuk kerangka ruang. Dengan grid, pengembang tidak perlu menghitung lebar setiap kartu secara manual. Browser akan membagi ruang berdasarkan jumlah kolom yang dipilih, sedangkan Tailwind menyediakan class yang mudah dibaca.</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="card-adaptive border rounded-xl p-5">
                                    <h4 class="text-sm font-bold text-heading mb-2">Flexbox</h4>
                                    <p class="text-xs text-muted leading-relaxed">Cocok untuk mengatur elemen dalam satu arah, misalnya tombol berjajar, navbar, atau ikon dengan teks.</p>
                                </div>
                                <div class="card-adaptive border rounded-xl p-5 border-cyan-500/30">
                                    <h4 class="text-sm font-bold text-cyan-700 dark:text-cyan-300 mb-2">Grid</h4>
                                    <p class="text-xs text-muted leading-relaxed">Cocok untuk membuat bidang dua dimensi, misalnya galeri 3 kolom, dashboard, dan layout kartu responsif.</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="section-37" class="lesson-section scroll-mt-32" data-lesson-id="37">
                        <div class="space-y-8 md:space-y-10">
                            <div class="space-y-4 border-l-4 border-blue-500 pl-4 md:pl-6">
                                <span class="text-blue-600 dark:text-blue-400 font-mono text-xs uppercase tracking-widest">Lesson 2.3.2</span>
                                <h2 class="text-2xl md:text-4xl lg:text-5xl font-black text-heading leading-[1.1]">Kolom, Gap, dan <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-500 to-cyan-600 dark:from-blue-400 dark:to-cyan-500">Pembagian Ruang</span></h2>
                            </div>
                            <div class="space-y-4 prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-85 text-sm md:text-base leading-relaxed text-justify">
                                <p>Class <span class="hl-term">grid-cols-{n}</span> menentukan jumlah kolom dalam satu baris. Misalnya, <code>grid-cols-3</code> berarti ruang kontainer dibagi menjadi tiga kolom yang sama besar. Jika jumlah item lebih banyak daripada jumlah kolom, item berikutnya akan turun ke baris baru secara otomatis.</p>
                                <p>Jarak antar item grid diatur menggunakan <span class="hl-term">gap</span>. Class seperti <code>gap-4</code>, <code>gap-6</code>, dan <code>gap-8</code> membuat jarak antar kartu lebih konsisten daripada mengatur margin satu per satu. Pada layout berbasis kartu, gap lebih aman karena jarak berlaku pada arah horizontal dan vertikal secara seragam.</p>
                            </div>

                            <div class="sim-bg-adaptive border border-adaptive rounded-2xl p-5 md:p-8 shadow-xl relative overflow-hidden">
                                <h4 class="text-xs font-bold text-muted uppercase mb-4 text-center tracking-widest">Simulator: Jumlah Kolom dan Gap</h4>
                                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-500/30 rounded-xl p-4 mb-6 text-sm text-blue-800 dark:text-blue-300">
                                    <p class="font-bold mb-1">Panduan Simulasi</p>
                                    <p class="text-xs m-0 leading-relaxed">Pilih jumlah kolom dan besar gap. Perhatikan bagaimana kartu berpindah baris dan jaraknya berubah tanpa menulis CSS manual.</p>
                                </div>
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                    <div class="space-y-5">
                                        <div>
                                            <p class="text-[10px] font-bold text-muted uppercase tracking-widest mb-2">Jumlah Kolom</p>
                                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                                                <button onclick="setGridCols(1)" class="px-3 py-2 rounded-lg bg-white dark:bg-black/30 border border-adaptive text-xs font-bold hover:border-cyan-400">grid-cols-1</button>
                                                <button onclick="setGridCols(2)" class="px-3 py-2 rounded-lg bg-white dark:bg-black/30 border border-adaptive text-xs font-bold hover:border-cyan-400">grid-cols-2</button>
                                                <button onclick="setGridCols(3)" class="px-3 py-2 rounded-lg bg-cyan-600 text-white border border-cyan-400 text-xs font-bold">grid-cols-3</button>
                                                <button onclick="setGridCols(4)" class="px-3 py-2 rounded-lg bg-white dark:bg-black/30 border border-adaptive text-xs font-bold hover:border-cyan-400">grid-cols-4</button>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-bold text-muted uppercase tracking-widest mb-2">Jarak Antar Item</p>
                                            <div class="grid grid-cols-3 gap-2">
                                                <button onclick="setGridGap(2)" class="px-3 py-2 rounded-lg bg-white dark:bg-black/30 border border-adaptive text-xs font-bold hover:border-blue-400">gap-2</button>
                                                <button onclick="setGridGap(4)" class="px-3 py-2 rounded-lg bg-blue-600 text-white border border-blue-400 text-xs font-bold">gap-4</button>
                                                <button onclick="setGridGap(6)" class="px-3 py-2 rounded-lg bg-white dark:bg-black/30 border border-adaptive text-xs font-bold hover:border-blue-400">gap-6</button>
                                            </div>
                                        </div>
                                        <div class="code-adaptive border rounded-xl p-4 font-mono text-xs overflow-x-auto custom-scrollbar">
                                            &lt;div class=&quot;grid <span id="sim-grid-cols" class="text-cyan-600 dark:text-cyan-400 font-bold">grid-cols-3</span> <span id="sim-grid-gap" class="text-blue-600 dark:text-blue-400 font-bold">gap-4</span>&quot;&gt;
                                        </div>
                                    </div>
                                    <div class="bg-slate-100 dark:bg-black/30 rounded-xl border border-adaptive p-4 grid-paper min-h-[260px]">
                                        <div id="gridPreview" class="grid grid-cols-3 gap-4 transition-all duration-300">
                                            @for($i = 1; $i <= 6; $i++)
                                                <div class="h-16 md:h-20 rounded-xl bg-cyan-500/15 border border-cyan-400/50 flex items-center justify-center text-cyan-700 dark:text-cyan-300 font-black shadow-sm">{{ $i }}</div>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-6 bg-gradient-to-r from-blue-50 to-transparent dark:from-blue-900/20 dark:to-transparent border-l-4 border-blue-500 p-4 rounded-r-xl">
                                    <p class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed text-justify m-0">Grid membantu menyusun banyak item secara konsisten. Jumlah kolom menentukan pembagian ruang, sedangkan gap menjaga jarak antar item agar tidak saling menempel.</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="section-38" class="lesson-section scroll-mt-32" data-lesson-id="38">
                        <div class="space-y-8 md:space-y-10">
                            <div class="space-y-4 border-l-4 border-sky-500 pl-4 md:pl-6">
                                <span class="text-sky-600 dark:text-sky-400 font-mono text-xs uppercase tracking-widest">Lesson 2.3.3</span>
                                <h2 class="text-2xl md:text-4xl lg:text-5xl font-black text-heading leading-[1.1]">Span dan <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-500 to-cyan-600 dark:from-sky-400 dark:to-cyan-500">Penggabungan Sel</span></h2>
                            </div>
                            <div class="space-y-4 prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-85 text-sm md:text-base leading-relaxed text-justify">
                                <p>Dalam Grid, satu item tidak harus selalu menempati satu sel. Item dapat dibuat melebar melewati beberapa kolom menggunakan class <span class="hl-term">col-span-{n}</span>, atau memanjang melewati beberapa baris menggunakan <span class="hl-term">row-span-{n}</span>. Fitur ini berguna untuk membuat layout seperti dashboard, kartu utama, atau tampilan bento.</p>
                                <p>Contohnya, pada grid tiga kolom, item dengan class <code>col-span-2</code> akan mengambil dua kolom sekaligus. Item lain tetap mengikuti alur grid yang tersedia. Dengan cara ini, halaman tidak terlihat datar karena beberapa elemen dapat diberi penekanan visual lebih besar.</p>
                            </div>

                            <div class="sim-bg-adaptive border border-adaptive rounded-2xl p-5 md:p-8 shadow-xl relative overflow-hidden">
                                <h4 class="text-xs font-bold text-muted uppercase mb-4 text-center tracking-widest">Simulator: Bento Grid</h4>
                                <div class="bg-sky-50 dark:bg-sky-900/20 border border-sky-200 dark:border-sky-500/30 rounded-xl p-4 mb-6 text-sm text-sky-800 dark:text-sky-300">
                                    <p class="font-bold mb-1">Panduan Simulasi</p>
                                    <p class="text-xs m-0 leading-relaxed">Aktifkan atau nonaktifkan span. Bandingkan layout biasa dengan layout yang memiliki kartu utama lebih besar.</p>
                                </div>
                                <div class="flex flex-col lg:flex-row gap-6">
                                    <div class="lg:w-1/3 space-y-3">
                                        <button onclick="setSpanMode(false)" class="w-full px-4 py-3 rounded-xl bg-white dark:bg-black/30 border border-adaptive text-xs font-bold hover:border-slate-400">Tanpa Span</button>
                                        <button onclick="setSpanMode(true)" class="w-full px-4 py-3 rounded-xl bg-sky-600 text-white border border-sky-400 text-xs font-bold">Gunakan col-span-2</button>
                                        <div class="code-adaptive border rounded-xl p-4 font-mono text-[11px] leading-relaxed">
                                            <div id="spanCode">Item utama: <span class="text-sky-600 dark:text-sky-400 font-bold">col-span-2</span></div>
                                        </div>
                                    </div>
                                    <div class="lg:w-2/3 bg-slate-100 dark:bg-black/30 rounded-xl border border-adaptive p-4 grid-paper">
                                        <div id="spanPreview" class="grid grid-cols-3 gap-3 transition-all duration-300 min-h-[300px]">
                                            <div id="heroCard" class="col-span-2 h-32 rounded-xl bg-gradient-to-br from-sky-500/30 to-cyan-500/20 border border-sky-400/50 flex items-center justify-center text-sky-700 dark:text-sky-200 font-black transition-all duration-300">Kartu Utama</div>
                                            <div class="h-32 rounded-xl bg-white/70 dark:bg-white/5 border border-adaptive flex items-center justify-center text-muted font-bold">A</div>
                                            <div class="h-24 rounded-xl bg-white/70 dark:bg-white/5 border border-adaptive flex items-center justify-center text-muted font-bold">B</div>
                                            <div class="h-24 rounded-xl bg-white/70 dark:bg-white/5 border border-adaptive flex items-center justify-center text-muted font-bold">C</div>
                                            <div class="h-24 rounded-xl bg-white/70 dark:bg-white/5 border border-adaptive flex items-center justify-center text-muted font-bold">D</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-6 bg-gradient-to-r from-sky-50 to-transparent dark:from-sky-900/20 dark:to-transparent border-l-4 border-sky-500 p-4 rounded-r-xl">
                                    <p class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed text-justify m-0">Span digunakan untuk memberi prioritas visual. Elemen penting dapat dibuat lebih besar tanpa harus membuat banyak wrapper tambahan.</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="section-39" class="lesson-section scroll-mt-32" data-lesson-id="39">
                        <div class="space-y-8 md:space-y-10">
                            <div class="space-y-4 border-l-4 border-teal-500 pl-4 md:pl-6">
                                <span class="text-teal-600 dark:text-teal-400 font-mono text-xs uppercase tracking-widest">Lesson 2.3.4</span>
                                <h2 class="text-2xl md:text-4xl lg:text-5xl font-black text-heading leading-[1.1]">Kartu Full dan <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-500 to-cyan-600 dark:from-teal-400 dark:to-cyan-500">Pola Grid Sederhana</span></h2>
                            </div>
                            <div class="space-y-4 prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-85 text-sm md:text-base leading-relaxed text-justify">
                                <p>Selain <code>col-span-2</code>, Tailwind CSS menyediakan class <span class="hl-term">col-span-full</span>. Class ini membuat satu item menempati seluruh kolom yang tersedia pada container grid. Pola ini cocok digunakan ketika satu kartu perlu menjadi informasi penuh, misalnya banner, ringkasan, atau catatan penting.</p>
                                <p>Pola dasarnya tetap sama: container memakai <code>grid</code>, jumlah kolom diatur dengan <code>grid-cols-3</code>, jarak antar item diberi <code>gap-4</code>, lalu item tertentu diberi <code>col-span-2</code> atau <code>col-span-full</code>. Dengan memahami urutan ini, pembelajar dapat memilih class yang sesuai tanpa mencampuradukkan fungsi container dan fungsi item.</p>
                            </div>

                            <div class="sim-bg-adaptive border border-adaptive rounded-2xl p-5 md:p-8 shadow-xl relative overflow-hidden">
                                <h4 class="text-xs font-bold text-muted uppercase mb-4 text-center tracking-widest">Simulator: col-span-2 dan col-span-full</h4>
                                <div class="bg-teal-50 dark:bg-teal-900/20 border border-teal-200 dark:border-teal-500/30 rounded-xl p-4 mb-6 text-sm text-teal-800 dark:text-teal-300">
                                    <p class="font-bold mb-1">Panduan Simulasi</p>
                                    <p class="text-xs m-0 leading-relaxed">Pilih mode kartu utama. Preview akan menunjukkan perbedaan antara satu kartu biasa, kartu dua kolom, dan kartu yang memenuhi seluruh kolom.</p>
                                </div>
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                    <div class="space-y-4">
                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                                            <button onclick="setFullSpanMode('normal')" class="px-3 py-3 rounded-lg bg-white dark:bg-black/30 border border-adaptive text-xs font-bold hover:border-teal-400">Tanpa span</button>
                                            <button onclick="setFullSpanMode('two')" class="px-3 py-3 rounded-lg bg-white dark:bg-black/30 border border-adaptive text-xs font-bold hover:border-teal-400">col-span-2</button>
                                            <button onclick="setFullSpanMode('full')" class="px-3 py-3 rounded-lg bg-teal-600 text-white border border-teal-400 text-xs font-bold">col-span-full</button>
                                        </div>
                                        <div class="code-adaptive border rounded-xl p-4 font-mono text-xs overflow-x-auto custom-scrollbar">
&lt;section class="grid grid-cols-3 gap-4"&gt;
  &lt;article class="<span id="fullSpanCode" class="text-teal-600 dark:text-teal-400 font-bold">col-span-full</span>"&gt;Kartu Utama&lt;/article&gt;
&lt;/section&gt;
                                        </div>
                                        <div class="bg-gradient-to-r from-teal-50 to-transparent dark:from-teal-900/20 dark:to-transparent border-l-4 border-teal-500 p-4 rounded-r-xl">
                                            <p id="fullSpanInfo" class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed text-justify m-0"><code>col-span-full</code> membuat kartu menempati seluruh kolom pada container grid.</p>
                                        </div>
                                    </div>
                                    <div class="bg-slate-100 dark:bg-black/30 rounded-xl border border-adaptive p-4 grid-paper min-h-[300px]">
                                        <div class="grid grid-cols-3 gap-4 transition-all duration-300">
                                            <div id="fullSpanCard" class="col-span-full h-24 rounded-xl bg-gradient-to-br from-teal-500/30 to-cyan-500/20 border border-teal-400/60 flex items-center justify-center text-teal-700 dark:text-teal-200 font-black transition-all duration-300">Kartu Utama</div>
                                            <div class="h-20 rounded-xl bg-white/70 dark:bg-white/5 border border-adaptive flex items-center justify-center text-muted font-bold">Kartu 2</div>
                                            <div class="h-20 rounded-xl bg-white/70 dark:bg-white/5 border border-adaptive flex items-center justify-center text-muted font-bold">Kartu 3</div>
                                            <div class="h-20 rounded-xl bg-white/70 dark:bg-white/5 border border-adaptive flex items-center justify-center text-muted font-bold">Kartu 4</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="section-40" class="lesson-section scroll-mt-32 pt-10 border-t border-adaptive" data-lesson-id="40" data-type="activity">
                        <div class="relative rounded-[2rem] sim-bg-adaptive border border-adaptive p-5 sm:p-6 md:p-10 overflow-hidden shadow-xl group hover:border-cyan-500/30 transition-all duration-500">
                            <div class="absolute -top-24 -right-24 w-64 h-64 bg-cyan-600/10 dark:bg-cyan-600/20 blur-[100px] rounded-full pointer-events-none"></div>
                            <div class="flex items-start md:items-center gap-5 mb-8 flex-col md:flex-row relative z-10">
                                <div class="p-4 bg-gradient-to-br from-cyan-600 to-blue-700 rounded-2xl text-white shadow-lg shadow-cyan-500/20 shrink-0">
                                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M4 5a2 2 0 012-2h3a2 2 0 012 2v3a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9 0a2 2 0 012-2h3a2 2 0 012 2v3a2 2 0 01-2 2h-3a2 2 0 01-2-2V5zM4 16a2 2 0 012-2h3a2 2 0 012 2v3a2 2 0 01-2 2H6a2 2 0 01-2-2v-3zm9 0a2 2 0 012-2h3a2 2 0 012 2v3a2 2 0 01-2 2h-3a2 2 0 01-2-2v-3z"/></svg>
                                </div>
                                <div>
                                    <h2 class="text-2xl md:text-3xl font-black text-heading tracking-tight">Aktivitas 2.3: Cocokkan Class Grid</h2>
                                    <p class="text-muted text-sm leading-relaxed mt-2 max-w-3xl text-justify">Perhatikan kebutuhan layout pada tabel. Pilih huruf jawaban A sampai G berdasarkan pilihan class yang tersedia. Aktivitas dianggap selesai jika skor minimal 4 dari 5.</p>
                                </div>
                            </div>

                            <div id="activityPanel" class="space-y-5 relative z-10">
                                <div id="quizContainer" class="space-y-5"></div>
                                <div class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center justify-between pt-4">
                                    <div id="activityResult" class="text-sm font-bold text-muted">Pilih jawaban pada setiap kasus.</div>
                                    <div class="flex gap-2">
                                        <button onclick="resetActivity()" class="px-5 py-3 rounded-xl border border-adaptive text-xs font-bold hover:bg-slate-100 dark:hover:bg-white/10 transition">Ulangi</button>
                                        <button id="submitActivityBtn" onclick="submitActivity()" class="px-5 py-3 rounded-xl bg-cyan-600 hover:bg-cyan-500 text-white text-xs font-bold shadow-lg shadow-cyan-500/20 transition">Periksa Jawaban</button>
                                    </div>
                                </div>
                            </div>

                            <div id="lockOverlay" class="hidden absolute inset-0 bg-white/92 dark:bg-[#050912]/95 backdrop-blur-md z-30 flex flex-col items-center justify-center text-center p-8 rounded-[2rem]">
                                <div class="w-20 h-20 bg-emerald-100 dark:bg-emerald-500/10 rounded-full flex items-center justify-center mb-5 border border-emerald-300 dark:border-emerald-500/50 shadow-[0_0_50px_rgba(16,185,129,.25)] animate-bounce">
                                    <svg class="w-10 h-10 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <h3 class="text-2xl md:text-3xl font-black text-slate-900 dark:text-white mb-2">AKTIVITAS SELESAI</h3>
                                <p class="text-sm font-bold text-slate-500 dark:text-white/60 max-w-sm">Pemahaman Grid sudah valid dan progress berhasil disimpan.</p>
                            </div>
                        </div>
                    </section>
                </article>

                <div class="mt-20 md:mt-32 pt-8 border-t border-adaptive flex flex-col sm:flex-row justify-between items-center gap-4 transition-colors">
                    <a href="{{ \Illuminate\Support\Facades\Route::has('courses.flexbox') ? route('courses.flexbox') : '#' }}" class="group flex items-center gap-4 text-muted hover:text-heading transition-colors w-full sm:w-auto justify-center sm:justify-start">
                        <div class="w-10 h-10 md:w-12 md:h-12 rounded-full border border-adaptive flex items-center justify-center group-hover:bg-slate-100 dark:group-hover:bg-white/5 transition-colors shrink-0">
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        </div>
                        <div class="text-left"><div class="text-[10px] uppercase tracking-widest font-bold opacity-60">Sebelumnya</div><div class="font-black text-xs md:text-sm line-clamp-1">Flexbox</div></div>
                    </a>
                    <div id="nextChapterBtn" class="group flex items-center gap-4 text-right text-muted cursor-not-allowed opacity-50 pointer-events-none transition-all duration-500 w-full sm:w-auto justify-center sm:justify-end">
                        <div class="text-right"><div id="nextLabel" class="text-[10px] uppercase tracking-widest font-bold opacity-60">Terkunci</div><div class="font-black text-xs md:text-sm line-clamp-1">Responsif</div></div>
                        <div id="nextIcon" class="w-10 h-10 md:w-12 md:h-12 rounded-full border border-adaptive flex items-center justify-center bg-slate-100 dark:bg-white/5 transition-colors shrink-0">
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    window.LESSON_IDS = [36, 37, 38, 39, 40];
    let rawCompletedIds = {!! json_encode($completedLessonIds ?? []) !!};
    window.COMPLETED_IDS = rawCompletedIds.map(id => Number(id));
    let completedSet = new Set(window.COMPLETED_IDS);
    const ACTIVITY_LESSON_ID = 40;
    let activityCompleted = {!! ($activityCompleted ?? false) ? 'true' : 'false' !!};
    let selectedAnswers = {};

    const choices = [
        { letter: 'A', className: 'col-span-2', note: 'Membuat satu item menempati dua kolom.' },
        { letter: 'B', className: 'text-center', note: 'Mengatur teks ke tengah, bukan layout grid.' },
        { letter: 'C', className: 'grid', note: 'Mengaktifkan layout Grid pada container.' },
        { letter: 'D', className: 'col-span-full', note: 'Membuat satu item menempati seluruh kolom.' },
        { letter: 'E', className: 'gap-4', note: 'Memberi jarak antar item grid.' },
        { letter: 'F', className: 'flex-col', note: 'Class Flexbox, bukan class utama Grid.' },
        { letter: 'G', className: 'grid-cols-3', note: 'Membagi container menjadi tiga kolom.' }
    ];

    const questions = [
        { id: 1, need: 'Mengaktifkan layout Grid pada kartu.', answer: 'C', explain: 'Class grid dipasang pada container untuk mengaktifkan sistem Grid.' },
        { id: 2, need: 'Membuat susunan menjadi tiga kolom.', answer: 'G', explain: 'Class grid-cols-3 membagi container Grid menjadi tiga kolom.' },
        { id: 3, need: 'Memberi jarak antar kartu agar tidak menempel.', answer: 'E', explain: 'Class gap-4 memberi jarak antar kartu secara horizontal dan vertikal.' },
        { id: 4, need: 'Membuat Kartu Utama menempati dua kolom.', answer: 'A', explain: 'Class col-span-2 dipasang pada item agar menempati dua kolom.' },
        { id: 5, need: 'Membuat satu kartu menempati seluruh kolom.', answer: 'D', explain: 'Class col-span-full membuat item membentang memenuhi seluruh kolom grid.' }
    ];

    document.addEventListener('DOMContentLoaded', () => {
        initSidebarScroll();
        initVisualEffects();
        renderActivity();
        updateProgressUI(false);
        if (activityCompleted) {
            lockActivityUI();
            unlockNextChapter();
        }
        initMasterObserver();
        document.querySelectorAll('.nav-item').forEach(item => {
            const targetId = parseInt(item.getAttribute('data-target')?.replace('#section-', '') || '0');
            if(completedSet.has(targetId)) markSidebarDone(targetId);
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
        if(percent === 100 && activityCompleted) unlockNextChapter();
    }

    function markSidebarDone(lessonId) {
        const navItem = document.querySelector(`.nav-item[data-target="#section-${lessonId}"]`);
        if(navItem) {
            const dot = navItem.querySelector('.dot');
            if(dot) dot.outerHTML = `<svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>`;
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
            if(response.ok) {
                completedSet.add(lessonId);
                updateProgressUI(true);
                markSidebarDone(lessonId);
                if (lessonId === ACTIVITY_LESSON_ID) {
                    window.markActiveCourseItemCompleted?.();
                }
            }
        } catch(e) { console.error('Network Error:', e); }
    }

    function initMasterObserver() {
        const mainScroll = document.getElementById('mainScroll');
        const sections = document.querySelectorAll('.lesson-section');
        if(!mainScroll || !sections.length) return;
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if(entry.isIntersecting) {
                    const targetId = entry.target.id;
                    const lessonId = Number(entry.target.dataset.lessonId);
                    const isActivity = entry.target.dataset.type === 'activity';
                    highlightAnchor(targetId);
                    if(lessonId && !isActivity && !completedSet.has(lessonId)) saveLessonToDB(lessonId);
                }
            });
        }, { root: mainScroll, rootMargin: '-10% 0px -60% 0px', threshold: 0 });
        sections.forEach(section => observer.observe(section));
    }

    function highlightAnchor(targetId) {
        document.querySelectorAll('.nav-item').forEach(item => item.classList.remove('active'));
        const active = document.querySelector(`.nav-item[data-target="#${targetId}"]`);
        if(active) active.classList.add('active');
    }

    function initSidebarScroll() {
        document.querySelectorAll('.nav-item, .sidebar-anchor').forEach(item => {
            item.addEventListener('click', (e) => {
                const target = item.getAttribute('data-target') || item.dataset.target;
                if(target && target.startsWith('#')) {
                    const el = document.querySelector(target);
                    if(el) {
                        e.preventDefault();
                        el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                }
            });
        });
    }

    function initVisualEffects() {
        const canvas = document.getElementById('stars');
        if(!canvas) return;
        const ctx = canvas.getContext('2d');
        let stars = [];
        function resize() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
            stars = Array.from({length: 60}, () => ({ x: Math.random()*canvas.width, y: Math.random()*canvas.height, r: Math.random()*1.5, a: Math.random() }));
        }
        function draw() {
            ctx.clearRect(0,0,canvas.width,canvas.height);
            stars.forEach(s => {
                ctx.globalAlpha = s.a;
                ctx.beginPath();
                ctx.arc(s.x, s.y, s.r, 0, Math.PI*2);
                ctx.fillStyle = '#67e8f9';
                ctx.fill();
            });
            requestAnimationFrame(draw);
        }
        resize(); draw(); window.addEventListener('resize', resize);
    }

    function setGridCols(n) {
        const el = document.getElementById('gridPreview');
        const code = document.getElementById('sim-grid-cols');
        if(!el) return;
        el.classList.remove('grid-cols-1','grid-cols-2','grid-cols-3','grid-cols-4');
        el.classList.add(`grid-cols-${n}`);
        code.innerText = `grid-cols-${n}`;
    }
    function setGridGap(n) {
        const el = document.getElementById('gridPreview');
        const code = document.getElementById('sim-grid-gap');
        if(!el) return;
        el.classList.remove('gap-2','gap-4','gap-6');
        el.classList.add(`gap-${n}`);
        code.innerText = `gap-${n}`;
    }
    function setSpanMode(active) {
        const hero = document.getElementById('heroCard');
        const code = document.getElementById('spanCode');
        if(!hero) return;
        if(active) {
            hero.classList.add('col-span-2');
            code.innerHTML = 'Item utama: <span class="text-sky-600 dark:text-sky-400 font-bold">col-span-2</span>';
        } else {
            hero.classList.remove('col-span-2');
            code.innerHTML = 'Item utama: <span class="text-slate-500 font-bold">tanpa span</span>';
        }
    }
    function setJustifyItems(cls) {
        const el = document.getElementById('alignPreview');
        const code = document.getElementById('alignXCode');
        el.classList.remove('justify-items-start','justify-items-center','justify-items-end');
        el.classList.add(cls);
        code.innerText = cls;
    }
    function setAlignItems(cls) {
        const el = document.getElementById('alignPreview');
        const code = document.getElementById('alignYCode');
        el.classList.remove('items-start','items-center','items-end');
        el.classList.add(cls);
        code.innerText = cls;
    }

    function setFullSpanMode(mode) {
        const card = document.getElementById('fullSpanCard');
        const code = document.getElementById('fullSpanCode');
        const info = document.getElementById('fullSpanInfo');
        if(!card || !code || !info) return;
        card.classList.remove('col-span-2','col-span-full');
        if(mode === 'two') {
            card.classList.add('col-span-2');
            code.innerText = 'col-span-2';
            info.innerHTML = '<code>col-span-2</code> membuat Kartu Utama menempati dua kolom pada grid tiga kolom.';
        } else if(mode === 'full') {
            card.classList.add('col-span-full');
            code.innerText = 'col-span-full';
            info.innerHTML = '<code>col-span-full</code> membuat kartu menempati seluruh kolom pada container grid.';
        } else {
            code.innerText = 'tanpa span';
            info.innerHTML = 'Tanpa class span, Kartu Utama hanya menempati satu kolom seperti item lainnya.';
        }
    }

    function renderActivity() {
        const container = document.getElementById('quizContainer');
        if(!container) return;
        container.innerHTML = `
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-5">
                <div class="lg:col-span-3 card-adaptive border border-adaptive rounded-2xl overflow-hidden">
                    <div class="p-4 border-b border-adaptive bg-cyan-50/70 dark:bg-cyan-500/10">
                        <h3 class="text-sm font-black text-heading">Tabel Kebutuhan Layout</h3>
                        <p class="text-xs text-muted mt-1">Pilih huruf jawaban pada setiap baris.</p>
                    </div>
                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="w-full text-left text-xs md:text-sm">
                            <thead class="text-[10px] uppercase tracking-widest text-muted bg-slate-100 dark:bg-black/20">
                                <tr>
                                    <th class="p-3 w-12">No.</th>
                                    <th class="p-3 min-w-[260px]">Kebutuhan</th>
                                    <th class="p-3 min-w-[220px]">Jawaban</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${questions.map(q => `
                                    <tr class="border-t border-adaptive" data-q="${q.id}">
                                        <td class="p-3 font-black text-cyan-600 dark:text-cyan-400">${q.id}</td>
                                        <td class="p-3 text-heading font-semibold leading-relaxed">${q.need}</td>
                                        <td class="p-3">
                                            <div class="flex flex-wrap gap-2">
                                                ${choices.map(c => `
                                                    <button type="button" onclick="selectAnswer(${q.id}, '${c.letter}', this)" class="choice-card w-9 h-9 rounded-lg border border-adaptive bg-white/70 dark:bg-black/20 hover:border-cyan-400 transition text-xs font-black text-slate-700 dark:text-slate-200">${c.letter}</button>
                                                `).join('')}
                                            </div>
                                            <p class="explain hidden text-[11px] text-muted mt-3 leading-relaxed"></p>
                                        </td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="lg:col-span-2 card-adaptive border border-adaptive rounded-2xl p-5">
                    <h3 class="text-sm font-black text-heading mb-4">Pilihan Class</h3>
                    <div class="space-y-2">
                        ${choices.map(c => `
                            <div class="flex items-start gap-3 rounded-xl border border-adaptive bg-white/60 dark:bg-black/20 p-3">
                                <div class="w-7 h-7 rounded-lg bg-cyan-500/10 text-cyan-700 dark:text-cyan-300 border border-cyan-500/20 flex items-center justify-center font-black text-xs shrink-0">${c.letter}</div>
                                <div>
                                    <code class="text-xs font-bold text-heading">${c.className}</code>
                                    <p class="text-[11px] text-muted leading-relaxed mt-1">${c.note}</p>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>
            </div>
        `;
    }

    function selectAnswer(qid, letter, btn) {
        if(activityCompleted) return;
        selectedAnswers[qid] = letter;
        const wrapper = btn.closest('[data-q]');
        wrapper.querySelectorAll('.choice-card').forEach(b => b.classList.remove('selected'));
        btn.classList.add('selected');
    }

    async function submitActivity() {
        if(activityCompleted) return;
        const result = document.getElementById('activityResult');
        if(Object.keys(selectedAnswers).length < questions.length) {
            result.innerText = 'Lengkapi semua jawaban terlebih dahulu.';
            result.className = 'text-sm font-bold text-rose-600 dark:text-rose-400';
            document.getElementById('activityPanel').classList.add('shake');
            setTimeout(() => document.getElementById('activityPanel').classList.remove('shake'), 400);
            return;
        }
        let score = 0;
        questions.forEach(q => {
            const wrapper = document.querySelector(`[data-q="${q.id}"]`);
            const buttons = wrapper.querySelectorAll('.choice-card');
            buttons.forEach(btn => {
                btn.classList.remove('selected','correct','wrong');
                const letter = btn.textContent.trim();
                if(letter === q.answer) btn.classList.add('correct');
                if(selectedAnswers[q.id] === letter && letter !== q.answer) btn.classList.add('wrong');
            });
            const explain = wrapper.querySelector('.explain');
            explain.classList.remove('hidden');
            explain.innerText = q.explain;
            if(selectedAnswers[q.id] === q.answer) score++;
        });
        if(score >= 4) {
            result.innerText = `Skor ${score}/5. Aktivitas valid dan progress disimpan.`;
            result.className = 'text-sm font-bold text-emerald-600 dark:text-emerald-400';
            await saveLessonToDB(ACTIVITY_LESSON_ID);
            activityCompleted = true;
            lockActivityUI();
            unlockNextChapter();
        } else {
            result.innerText = `Skor ${score}/5. Minimal 4 benar. Silakan pelajari pembahasan dan ulangi aktivitas.`;
            result.className = 'text-sm font-bold text-rose-600 dark:text-rose-400';
        }
    }

    function resetActivity() {
        if(activityCompleted) return;
        selectedAnswers = {};
        renderActivity();
        const result = document.getElementById('activityResult');
        result.innerText = 'Pilih jawaban pada setiap kasus.';
        result.className = 'text-sm font-bold text-muted';
    }

    function lockActivityUI() {
        const overlay = document.getElementById('lockOverlay');
        if(overlay) overlay.classList.remove('hidden');
        const btn = document.getElementById('submitActivityBtn');
        if(btn) { btn.disabled = true; btn.innerText = 'Aktivitas Selesai'; }
    }

    function unlockNextChapter() {
        const btn = document.getElementById('nextChapterBtn');
        if(!btn) return;
        btn.classList.remove('cursor-not-allowed','opacity-50','pointer-events-none','text-muted');
        btn.classList.add('text-cyan-600','dark:text-cyan-400','cursor-pointer');
        document.getElementById('nextLabel').innerText = 'Selanjutnya';
        document.getElementById('nextLabel').classList.remove('opacity-60');
        const icon = document.getElementById('nextIcon');
        icon.innerHTML = `<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>`;
        icon.classList.remove('bg-slate-100','dark:bg-white/5');
        icon.classList.add('bg-cyan-100','dark:bg-cyan-500/20','border-cyan-300','dark:border-cyan-500/50','text-cyan-600','dark:text-cyan-400','shadow-lg');
        btn.onclick = () => window.location.href = "{{ \Illuminate\Support\Facades\Route::has('courses.responsive') ? route('courses.responsive') : '#' }}";
    }
</script>
@endsection
