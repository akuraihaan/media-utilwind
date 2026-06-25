@extends('layouts.landing')
@section('title','Layout Responsif dengan Breakpoint')

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
        --accent: #0ea5e9;
        --accent-glow: rgba(14,165,233,.34);
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
        --accent-glow: rgba(14,165,233,.52);
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
        background-color: rgba(14,165,233,.14);
        color: #0369a1;
        padding: .125rem .375rem;
        border-radius: .375rem;
        font-weight: 700;
        white-space: nowrap;
        border: 1px solid rgba(14,165,233,.28);
    }
    .dark .hl-term { color: #7dd3fc; background-color: rgba(14,165,233,.18); border-color: rgba(14,165,233,.36); }
    .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(148,163,184,.42); border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: var(--accent); }
    #animated-bg {
        background: radial-gradient(850px circle at 18% 18%, rgba(14,165,233,.12), transparent 42%),
                    radial-gradient(850px circle at 82% 78%, rgba(99,102,241,.12), transparent 42%);
        animation: bgMove 20s ease-in-out infinite alternate;
    }
    .dark #animated-bg {
        background: radial-gradient(850px circle at 18% 18%, rgba(14,165,233,.18), transparent 42%),
                    radial-gradient(850px circle at 82% 78%, rgba(99,102,241,.16), transparent 42%);
    }
    @keyframes bgMove { to { transform: scale(1.12); } }
    @keyframes shake { 0%,100% { transform: translateX(0); } 25% { transform: translateX(-5px); } 75% { transform: translateX(5px); } }
    .shake { animation: shake .35s ease-in-out; }

    .nav-item { display: flex; width: 100%; text-align: left; align-items: center; gap: 12px; padding: 10px 14px; font-size: .85rem; color: var(--text-muted); border-radius: 8px; transition: all .2s; position: relative; }
    .nav-item:hover { color: var(--text-main); background: rgba(14,165,233,.06); }
    .nav-item.active { color: #0284c7; background: rgba(14,165,233,.08); font-weight: 700; }
    .dark .nav-item.active { color: #7dd3fc; }
    .dot { width: 6px; height: 6px; border-radius: 50%; background: #94a3b8; transition: all .3s; }
    .dark .dot { background: #334155; }
    .nav-item.active .dot { background: #0ea5e9; box-shadow: 0 0 10px #0ea5e9; transform: scale(1.25); }

    .device-btn.active { border-color: #0ea5e9; background: rgba(14,165,233,.12); color: #0369a1; }
    .dark .device-btn.active { color: #7dd3fc; }
    .choice-card.selected { border-color: #0ea5e9; background: rgba(14,165,233,.10); }
    .choice-card.correct { border-color: #10b981; background: rgba(16,185,129,.12); }
    .choice-card.wrong { border-color: #f43f5e; background: rgba(244,63,94,.10); }
    .screen-shell { transition: width .35s ease, max-width .35s ease; }
</style>

<div id="courseRoot" class="relative h-screen bg-adaptive text-adaptive font-sans overflow-hidden flex flex-col selection:bg-sky-500/30 pt-20 transition-colors duration-500">
    <div class="fixed inset-0 -z-50 pointer-events-none">
        <div id="animated-bg" class="absolute inset-0 opacity-60 transition-opacity"></div>
        <div class="absolute top-[-20%] right-[-10%] w-[800px] h-[800px] bg-sky-400/10 dark:bg-sky-900/20 rounded-full blur-[130px] animate-pulse transition-colors"></div>
        <div class="absolute bottom-[-20%] left-[-10%] w-[700px] h-[700px] bg-indigo-500/10 dark:bg-indigo-900/20 rounded-full blur-[120px] transition-colors"></div>
        <canvas id="stars" class="absolute inset-0 pointer-events-none opacity-0 dark:opacity-100 transition-opacity duration-500"></canvas>
    </div>

    @include('layouts.partials.navbar')

    <div class="flex flex-1 overflow-hidden relative z-20 h-full">
        @include('layouts.partials.course-sidebar')

        <main id="mainScroll" class="flex-1 h-full overflow-y-auto scroll-smooth relative bg-transparent custom-scrollbar scroll-padding-top-24">
            <div id="stickyHeader" class="sticky top-0 z-30 w-full backdrop-blur-2xl border-b border-adaptive px-4 md:px-8 py-4 flex items-center justify-between transition-colors duration-500" style="background-color: var(--glass-header);">
                <div class="flex items-center gap-4 min-w-0">
                    <div class="w-8 h-8 rounded-lg bg-sky-500/10 dark:bg-sky-500/20 border border-sky-500/20 flex items-center justify-center font-bold text-xs text-sky-600 dark:text-sky-400 shrink-0">2.4</div>
                    <div class="min-w-0">
                        <h1 class="text-sm font-bold text-heading line-clamp-1 transition-colors">Layout Responsif dengan Breakpoint</h1>
                        <p class="text-[10px] text-muted line-clamp-1 transition-colors">Mobile-first, sm, md, lg, Grid, Flex, Gap, dan Padding</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <div class="hidden sm:block w-24 md:w-32 h-1.5 bg-slate-200 dark:bg-white/10 rounded-full overflow-hidden transition-colors">
                        <div id="topProgressBar" class="h-full bg-gradient-to-r from-sky-400 to-indigo-500 w-0 transition-all duration-500 shadow-[0_0_12px_rgba(14,165,233,.55)]"></div>
                    </div>
                    <span id="progressLabelTop" class="text-sky-600 dark:text-sky-400 font-bold text-xs transition-colors">0%</span>
                </div>
            </div>

            <div class="p-4 sm:p-6 lg:p-16 max-w-5xl mx-auto pb-40">
                <div class="mb-16 md:mb-24">
                    <div class="card-adaptive rounded-3xl border p-6 md:p-8 overflow-hidden relative">
                        <div class="absolute -right-10 -top-10 w-40 h-40 bg-sky-500/10 rounded-full blur-3xl"></div>
                        <p class="text-xs font-mono font-bold text-sky-600 dark:text-sky-400 uppercase tracking-[.25em] mb-3">Subbab 2.4</p>
                        <h2 class="text-3xl md:text-5xl font-black text-heading leading-tight mb-4">Layout Responsif dengan Breakpoint</h2>
                        <p class="text-sm md:text-base text-muted leading-relaxed max-w-3xl text-justify">
                            Pada subbab ini, pembelajar mempelajari cara membuat layout yang menyesuaikan ukuran layar. Materi difokuskan pada pendekatan mobile-first, penggunaan awalan breakpoint seperti <code>sm:</code>, <code>md:</code>, dan <code>lg:</code>, serta penerapan class responsif pada Grid, Flexbox, gap, dan padding.
                        </p>
                    </div>

                    <h3 class="text-xl font-bold text-heading mt-10 mb-6 flex items-center gap-2 transition-colors">
                        <svg class="w-5 h-5 text-sky-500 dark:text-sky-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        Tujuan Pembelajaran
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="card-adaptive border p-5 rounded-xl flex items-start gap-4 h-full">
                            <div class="w-9 h-9 rounded-lg bg-sky-500/10 text-sky-600 dark:text-sky-400 flex items-center justify-center shrink-0 font-bold text-xs border border-sky-500/20">1</div>
                            <div><h4 class="text-sm font-bold text-heading mb-1">Mobile-first</h4><p class="text-[11px] text-muted leading-relaxed">Menjelaskan bahwa class dasar berlaku untuk layar kecil terlebih dahulu.</p></div>
                        </div>
                        <div class="card-adaptive border p-5 rounded-xl flex items-start gap-4 h-full">
                            <div class="w-9 h-9 rounded-lg bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0 font-bold text-xs border border-indigo-500/20">2</div>
                            <div><h4 class="text-sm font-bold text-heading mb-1">Breakpoint</h4><p class="text-[11px] text-muted leading-relaxed">Menggunakan awalan <code>sm:</code>, <code>md:</code>, dan <code>lg:</code> untuk mengubah tampilan.</p></div>
                        </div>
                        <div class="card-adaptive border p-5 rounded-xl flex items-start gap-4 h-full">
                            <div class="w-9 h-9 rounded-lg bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 flex items-center justify-center shrink-0 font-bold text-xs border border-cyan-500/20">3</div>
                            <div><h4 class="text-sm font-bold text-heading mb-1">Layout Responsif</h4><p class="text-[11px] text-muted leading-relaxed">Menerapkan Grid dan Flexbox yang berubah dari layar kecil ke layar sedang.</p></div>
                        </div>
                        <div class="card-adaptive border p-5 rounded-xl flex items-start gap-4 h-full">
                            <div class="w-9 h-9 rounded-lg bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 font-bold text-xs border border-emerald-500/20">4</div>
                            <div><h4 class="text-sm font-bold text-heading mb-1">Ruang Responsif</h4><p class="text-[11px] text-muted leading-relaxed">Mengatur gap dan padding yang berubah mengikuti ukuran layar.</p></div>
                        </div>
                    </div>
                </div>

                <article class="space-y-24 md:space-y-32">
                    <section id="section-41" class="lesson-section scroll-mt-32" data-lesson-id="41">
                        <div class="space-y-8 md:space-y-10">
                            <div class="space-y-4 border-l-4 border-sky-500 pl-4 md:pl-6">
                                <span class="text-sky-600 dark:text-sky-400 font-mono text-xs uppercase tracking-widest">Lesson 2.4.1</span>
                                <h2 class="text-2xl md:text-4xl lg:text-5xl font-black text-heading leading-[1.1]">Konsep Dasar <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-500 to-indigo-600 dark:from-sky-400 dark:to-indigo-500">Layout Responsif</span></h2>
                            </div>
                            <div class="space-y-4 prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-85 text-sm md:text-base leading-relaxed text-justify">
                                <p>Layout responsif adalah tampilan halaman yang dapat menyesuaikan ukuran layar. Halaman web tidak hanya dibuka melalui laptop, tetapi juga melalui ponsel dan tablet. Karena ukuran layar berbeda, susunan kartu, teks, tombol, dan jarak antar elemen perlu berubah agar tetap nyaman digunakan.</p>
                                <p>Tailwind CSS menggunakan pendekatan <span class="hl-term">mobile-first</span>. Artinya, class tanpa awalan berlaku lebih dahulu untuk layar kecil. Jika tampilan perlu berubah pada layar yang lebih besar, maka class diberi awalan breakpoint, misalnya <span class="hl-term">md:</span> atau <span class="hl-term">lg:</span>.</p>
                                <p>Contoh <code>text-2xl md:text-4xl lg:text-5xl</code> berarti teks berukuran <code>text-2xl</code> pada layar kecil, berubah menjadi <code>md:text-4xl</code> pada layar sedang, dan menjadi <code>lg:text-5xl</code> pada layar besar. Pola yang sama dapat digunakan untuk layout, gap, padding, dan arah Flexbox.</p>
                            </div>

                            <div class="sim-bg-adaptive border border-adaptive rounded-2xl p-5 md:p-8 shadow-xl relative overflow-hidden">
                                <h4 class="text-xs font-bold text-muted uppercase mb-4 text-center tracking-widest">Simulator: Cara Kerja Breakpoint</h4>
                                <div class="bg-sky-50 dark:bg-sky-900/20 border border-sky-200 dark:border-sky-500/30 rounded-xl p-4 mb-6 text-sm text-sky-800 dark:text-sky-300">
                                    <p class="font-bold mb-1">Panduan Simulasi</p>
                                    <p class="text-xs m-0 leading-relaxed">Pilih ukuran layar. Perhatikan class mana yang aktif pada layar kecil, sedang, dan besar.</p>
                                </div>
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                    <div class="space-y-4">
                                        <div class="grid grid-cols-3 gap-2">
                                            <button onclick="setDevice('mobile')" data-device="mobile" class="device-btn active px-3 py-3 rounded-xl border border-adaptive text-xs font-bold transition">Mobile</button>
                                            <button onclick="setDevice('tablet')" data-device="tablet" class="device-btn px-3 py-3 rounded-xl border border-adaptive text-xs font-bold transition">md</button>
                                            <button onclick="setDevice('desktop')" data-device="desktop" class="device-btn px-3 py-3 rounded-xl border border-adaptive text-xs font-bold transition">lg</button>
                                        </div>
                                        <div class="code-adaptive border rounded-xl p-4 font-mono text-xs overflow-x-auto custom-scrollbar">
                                            &lt;h1 class=&quot;<span id="breakBase" class="text-sky-600 dark:text-sky-400 font-bold">text-2xl</span> <span id="breakMd" class="text-slate-400">md:text-4xl</span> <span id="breakLg" class="text-slate-400">lg:text-5xl</span>&quot;&gt;
                                        </div>
                                        <div id="breakDesc" class="text-xs text-muted leading-relaxed border border-adaptive rounded-xl p-4 bg-white/60 dark:bg-black/20">Pada layar kecil, class dasar aktif terlebih dahulu.</div>
                                    </div>
                                    <div class="bg-slate-100 dark:bg-black/30 rounded-xl border border-adaptive p-4 min-h-[260px] flex items-center justify-center overflow-hidden">
                                        <div id="deviceShell" class="screen-shell w-[280px] bg-white dark:bg-slate-900 border border-adaptive rounded-2xl shadow-xl p-5">
                                            <div class="text-[10px] uppercase tracking-widest font-bold text-muted mb-3" id="deviceLabel">Layar kecil</div>
                                            <h3 id="breakTitle" class="font-black text-slate-900 dark:text-white text-2xl leading-tight transition-all duration-300">Belajar Tailwind</h3>
                                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-3 leading-relaxed">Ukuran teks berubah ketika breakpoint aktif.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="section-42" class="lesson-section scroll-mt-32" data-lesson-id="42">
                        <div class="space-y-8 md:space-y-10">
                            <div class="space-y-4 border-l-4 border-indigo-500 pl-4 md:pl-6">
                                <span class="text-indigo-600 dark:text-indigo-400 font-mono text-xs uppercase tracking-widest">Lesson 2.4.2</span>
                                <h2 class="text-2xl md:text-4xl lg:text-5xl font-black text-heading leading-[1.1]">Grid Responsif <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-sky-600 dark:from-indigo-400 dark:to-sky-500">grid-cols-1 md:grid-cols-2</span></h2>
                            </div>
                            <div class="space-y-4 prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-85 text-sm md:text-base leading-relaxed text-justify">
                                <p>Grid responsif digunakan ketika item perlu tersusun satu kolom pada layar kecil, lalu berubah menjadi beberapa kolom saat layar lebih besar. Pola ini sering digunakan pada daftar kartu produk, artikel, fitur aplikasi, atau daftar materi.</p>
                                <p>Contoh class <span class="hl-term">grid grid-cols-1 md:grid-cols-2</span> berarti container menggunakan Grid, satu kolom pada layar kecil, lalu dua kolom pada layar sedang ke atas. Dengan pola ini, kartu tidak dipaksa berdampingan pada layar sempit, sehingga isi tetap mudah dibaca.</p>
                            </div>

                            <div class="sim-bg-adaptive border border-adaptive rounded-2xl p-5 md:p-8 shadow-xl relative overflow-hidden">
                                <h4 class="text-xs font-bold text-muted uppercase mb-4 text-center tracking-widest">Simulator: Grid Responsif</h4>
                                <div class="bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-500/30 rounded-xl p-4 mb-6 text-sm text-indigo-800 dark:text-indigo-300">
                                    <p class="font-bold mb-1">Panduan Simulasi</p>
                                    <p class="text-xs m-0 leading-relaxed">Ubah ukuran layar. Pada layar kecil kartu tersusun satu kolom, sedangkan pada layar sedang kartu berubah menjadi dua kolom.</p>
                                </div>
                                <div class="grid lg:grid-cols-2 gap-6">
                                    <div class="space-y-4">
                                        <div class="grid grid-cols-2 gap-2">
                                            <button onclick="setResponsiveGrid('small')" class="px-4 py-3 rounded-xl bg-white dark:bg-black/30 border border-adaptive text-xs font-bold hover:border-indigo-400">Layar kecil</button>
                                            <button onclick="setResponsiveGrid('medium')" class="px-4 py-3 rounded-xl bg-indigo-600 text-white border border-indigo-400 text-xs font-bold">Layar sedang</button>
                                        </div>
                                        <div class="code-adaptive border rounded-xl p-4 font-mono text-xs overflow-x-auto custom-scrollbar">
                                            &lt;section class=&quot;<span class="text-sky-600 dark:text-sky-400 font-bold">grid</span> <span id="gridClassCode" class="text-indigo-600 dark:text-indigo-400 font-bold">grid-cols-1 md:grid-cols-2</span> gap-4&quot;&gt;
                                        </div>
                                        <p id="gridResponsiveDesc" class="text-xs text-muted leading-relaxed border border-adaptive rounded-xl p-4 bg-white/60 dark:bg-black/20">Pada layar kecil, class dasar <code>grid-cols-1</code> membuat kartu tersusun satu kolom.</p>
                                    </div>
                                    <div class="bg-slate-100 dark:bg-black/30 rounded-xl border border-adaptive p-4 overflow-hidden min-h-[320px] flex justify-center">
                                        <div id="gridDevice" class="screen-shell w-[290px] bg-white dark:bg-slate-900 border border-adaptive rounded-2xl p-4 shadow-xl">
                                            <div id="gridResponsivePreview" class="grid grid-cols-1 gap-4 transition-all duration-300">
                                                @for($i = 1; $i <= 4; $i++)
                                                    <article class="rounded-xl bg-indigo-500/15 border border-indigo-400/40 p-4">
                                                        <h4 class="text-sm font-black text-indigo-700 dark:text-indigo-300">Kartu {{ $i }}</h4>
                                                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">Konten tetap terbaca pada ukuran layar berbeda.</p>
                                                    </article>
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="section-43" class="lesson-section scroll-mt-32" data-lesson-id="43">
                        <div class="space-y-8 md:space-y-10">
                            <div class="space-y-4 border-l-4 border-cyan-500 pl-4 md:pl-6">
                                <span class="text-cyan-600 dark:text-cyan-400 font-mono text-xs uppercase tracking-widest">Lesson 2.4.3</span>
                                <h2 class="text-2xl md:text-4xl lg:text-5xl font-black text-heading leading-[1.1]">Flexbox Responsif <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-500 to-sky-600 dark:from-cyan-400 dark:to-sky-500">flex-col md:flex-row</span></h2>
                            </div>
                            <div class="space-y-4 prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-85 text-sm md:text-base leading-relaxed text-justify">
                                <p>Flexbox responsif digunakan ketika komponen perlu tersusun ke bawah pada layar kecil, lalu berubah ke samping pada layar sedang. Pola ini cocok untuk kartu profil, hero section, tampilan gambar dengan deskripsi, atau panel informasi.</p>
                                <p>Class <span class="hl-term">flex flex-col md:flex-row</span> berarti container memakai Flexbox. Pada layar kecil, elemen anak tersusun dari atas ke bawah. Pada layar sedang ke atas, susunannya berubah menjadi dari kiri ke kanan.</p>
                            </div>

                            <div class="sim-bg-adaptive border border-adaptive rounded-2xl p-5 md:p-8 shadow-xl relative overflow-hidden">
                                <h4 class="text-xs font-bold text-muted uppercase mb-4 text-center tracking-widest">Simulator: Flex Responsif</h4>
                                <div class="bg-cyan-50 dark:bg-cyan-900/20 border border-cyan-200 dark:border-cyan-500/30 rounded-xl p-4 mb-6 text-sm text-cyan-800 dark:text-cyan-300">
                                    <p class="font-bold mb-1">Panduan Simulasi</p>
                                    <p class="text-xs m-0 leading-relaxed">Pilih mode layar untuk melihat perubahan dari <code>flex-col</code> menjadi <code>md:flex-row</code>.</p>
                                </div>
                                <div class="grid lg:grid-cols-2 gap-6">
                                    <div class="space-y-4">
                                        <div class="grid grid-cols-2 gap-2">
                                            <button onclick="setFlexResponsive('small')" class="px-4 py-3 rounded-xl bg-white dark:bg-black/30 border border-adaptive text-xs font-bold hover:border-cyan-400">Layar kecil</button>
                                            <button onclick="setFlexResponsive('medium')" class="px-4 py-3 rounded-xl bg-cyan-600 text-white border border-cyan-400 text-xs font-bold">Layar sedang</button>
                                        </div>
                                        <div class="code-adaptive border rounded-xl p-4 font-mono text-xs overflow-x-auto custom-scrollbar">
                                            &lt;div class=&quot;<span class="text-cyan-600 dark:text-cyan-400 font-bold">flex</span> <span id="flexClassCode" class="text-sky-600 dark:text-sky-400 font-bold">flex-col md:flex-row</span> gap-4&quot;&gt;
                                        </div>
                                        <p id="flexResponsiveDesc" class="text-xs text-muted leading-relaxed border border-adaptive rounded-xl p-4 bg-white/60 dark:bg-black/20">Pada layar kecil, <code>flex-col</code> membuat gambar dan teks tersusun ke bawah.</p>
                                    </div>
                                    <div class="bg-slate-100 dark:bg-black/30 rounded-xl border border-adaptive p-4 overflow-hidden min-h-[320px] flex justify-center">
                                        <div id="flexDevice" class="screen-shell w-[300px] bg-white dark:bg-slate-900 border border-adaptive rounded-2xl p-4 shadow-xl">
                                            <div id="flexResponsivePreview" class="flex flex-col gap-4 transition-all duration-300">
                                                <div class="h-32 rounded-xl bg-cyan-500/20 border border-cyan-400/50 flex items-center justify-center text-cyan-700 dark:text-cyan-300 font-black">Gambar</div>
                                                <div class="rounded-xl bg-white/70 dark:bg-white/5 border border-adaptive p-4">
                                                    <h4 class="font-black text-heading">Informasi Produk</h4>
                                                    <p class="text-xs text-muted mt-2 leading-relaxed">Pada layar sedang, bagian gambar dan teks dapat berdampingan.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="section-44" class="lesson-section scroll-mt-32" data-lesson-id="44">
                        <div class="space-y-8 md:space-y-10">
                            <div class="space-y-4 border-l-4 border-emerald-500 pl-4 md:pl-6">
                                <span class="text-emerald-600 dark:text-emerald-400 font-mono text-xs uppercase tracking-widest">Lesson 2.4.4</span>
                                <h2 class="text-2xl md:text-4xl lg:text-5xl font-black text-heading leading-[1.1]">Gap dan Padding <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-500 to-sky-600 dark:from-emerald-400 dark:to-sky-500">yang Responsif</span></h2>
                            </div>
                            <div class="space-y-4 prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-85 text-sm md:text-base leading-relaxed text-justify">
                                <p>Responsif tidak hanya berkaitan dengan jumlah kolom atau arah layout. Jarak antar elemen juga perlu menyesuaikan ukuran layar. Pada layar kecil, jarak yang terlalu besar dapat membuat konten cepat habis ke bawah. Pada layar sedang atau besar, jarak yang lebih luas membuat tampilan terlihat lapang.</p>
                                <p>Class <span class="hl-term">gap-3 md:gap-6</span> membuat jarak antar kartu lebih kecil pada layar kecil dan lebih besar pada layar sedang. Class <span class="hl-term">p-4 md:p-8</span> membuat padding section lebih hemat pada layar kecil dan lebih lega pada layar sedang ke atas.</p>
                            </div>

                            <div class="sim-bg-adaptive border border-adaptive rounded-2xl p-5 md:p-8 shadow-xl relative overflow-hidden">
                                <h4 class="text-xs font-bold text-muted uppercase mb-4 text-center tracking-widest">Simulator: Jarak Responsif</h4>
                                <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-500/30 rounded-xl p-4 mb-6 text-sm text-emerald-800 dark:text-emerald-300">
                                    <p class="font-bold mb-1">Panduan Simulasi</p>
                                    <p class="text-xs m-0 leading-relaxed">Bandingkan padding dan gap pada layar kecil dan layar sedang. Class dasar berlaku dulu, lalu class <code>md:</code> aktif pada layar sedang.</p>
                                </div>
                                <div class="grid lg:grid-cols-2 gap-6">
                                    <div class="space-y-4">
                                        <div class="grid grid-cols-2 gap-2">
                                            <button onclick="setSpaceResponsive('small')" class="px-4 py-3 rounded-xl bg-white dark:bg-black/30 border border-adaptive text-xs font-bold hover:border-emerald-400">Layar kecil</button>
                                            <button onclick="setSpaceResponsive('medium')" class="px-4 py-3 rounded-xl bg-emerald-600 text-white border border-emerald-400 text-xs font-bold">Layar sedang</button>
                                        </div>
                                        <div class="code-adaptive border rounded-xl p-4 font-mono text-xs overflow-x-auto custom-scrollbar">
                                            &lt;section class=&quot;<span id="spacePaddingCode" class="text-emerald-600 dark:text-emerald-400 font-bold">p-4 md:p-8</span>&quot;&gt;<br>
                                            &nbsp;&nbsp;&lt;div class=&quot;<span id="spaceGapCode" class="text-sky-600 dark:text-sky-400 font-bold">gap-3 md:gap-6</span>&quot;&gt;
                                        </div>
                                        <p id="spaceResponsiveDesc" class="text-xs text-muted leading-relaxed border border-adaptive rounded-xl p-4 bg-white/60 dark:bg-black/20">Pada layar kecil, section memakai <code>p-4</code> dan jarak antar kartu memakai <code>gap-3</code>.</p>
                                    </div>
                                    <div class="bg-slate-100 dark:bg-black/30 rounded-xl border border-adaptive p-4 overflow-hidden min-h-[320px] flex justify-center">
                                        <section id="spacePreview" class="screen-shell w-[300px] bg-white dark:bg-slate-900 border border-adaptive rounded-2xl p-4 shadow-xl transition-all duration-300">
                                            <div id="spaceInner" class="grid grid-cols-1 gap-3 transition-all duration-300">
                                                <div class="h-16 rounded-xl bg-emerald-500/20 border border-emerald-400/50 flex items-center justify-center text-emerald-700 dark:text-emerald-300 text-xs font-black">Kartu 1</div>
                                                <div class="h-16 rounded-xl bg-sky-500/20 border border-sky-400/50 flex items-center justify-center text-sky-700 dark:text-sky-300 text-xs font-black">Kartu 2</div>
                                                <div class="h-16 rounded-xl bg-indigo-500/20 border border-indigo-400/50 flex items-center justify-center text-indigo-700 dark:text-indigo-300 text-xs font-black">Kartu 3</div>
                                            </div>
                                        </section>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="section-45" class="lesson-section scroll-mt-32 pt-10 border-t border-adaptive" data-lesson-id="45" data-type="activity">
                        <div class="relative rounded-[2rem] sim-bg-adaptive border border-adaptive p-5 sm:p-6 md:p-10 overflow-hidden shadow-xl group hover:border-sky-500/30 transition-all duration-500">
                            <div class="absolute -top-24 -right-24 w-64 h-64 bg-sky-600/10 dark:bg-sky-600/20 blur-[100px] rounded-full pointer-events-none"></div>
                            <div class="flex items-start md:items-center gap-5 mb-8 flex-col md:flex-row relative z-10">
                                <div class="p-4 bg-gradient-to-br from-sky-600 to-indigo-700 rounded-2xl text-white shadow-lg shadow-sky-500/20 shrink-0">
                                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                </div>
                                <div>
                                    <h2 class="text-2xl md:text-3xl font-black text-heading tracking-tight">Aktivitas: Pilih Class Responsif</h2>
                                    <p class="text-muted text-sm leading-relaxed mt-2 max-w-3xl text-justify">Pilih huruf jawaban yang paling sesuai dengan kebutuhan layout responsif sederhana. Aktivitas dianggap selesai jika minimal 3 dari 4 jawaban benar.</p>
                                </div>
                            </div>

                            <div class="relative z-10 grid lg:grid-cols-5 gap-6">
                                <div class="lg:col-span-3 space-y-4" id="responsiveActivityList"></div>
                                <div class="lg:col-span-2 card-adaptive border border-adaptive rounded-2xl p-5 h-fit lg:sticky lg:top-28">
                                    <h3 class="text-sm font-black text-heading mb-3">Pilihan Class</h3>
                                    <div class="grid grid-cols-1 gap-2 text-xs font-mono mb-5">
                                        <div class="rounded-xl border border-adaptive p-3"><b>A.</b> gap-3 md:gap-6</div>
                                        <div class="rounded-xl border border-adaptive p-3"><b>B.</b> p-4 md:p-8</div>
                                        <div class="rounded-xl border border-adaptive p-3"><b>C.</b> grid grid-cols-1 md:grid-cols-2</div>
                                        <div class="rounded-xl border border-adaptive p-3"><b>D.</b> flex flex-col md:flex-row</div>
                                        <div class="rounded-xl border border-adaptive p-3 text-muted"><b>E.</b> text-center font-bold</div>
                                        <div class="rounded-xl border border-adaptive p-3 text-muted"><b>F.</b> rounded-xl shadow-md</div>
                                    </div>
                                    <div class="bg-slate-100 dark:bg-black/30 border border-adaptive rounded-xl p-4 mb-4">
                                        <div class="text-[10px] uppercase font-bold text-muted mb-2">Skor</div>
                                        <div id="activityScoreLabel" class="text-4xl font-black text-heading">0/4</div>
                                        <div id="activityResult" class="text-xs text-muted mt-2 leading-relaxed">Pilih jawaban pada setiap kebutuhan.</div>
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        <button id="submitActivityBtn" onclick="submitActivity()" class="px-5 py-3 rounded-xl bg-sky-600 hover:bg-sky-500 text-white text-xs font-bold shadow-lg shadow-sky-500/20 transition">Periksa Jawaban</button>
                                        <button onclick="resetActivity()" class="px-5 py-3 rounded-xl border border-adaptive text-xs font-bold hover:bg-slate-100 dark:hover:bg-white/10 transition">Ulangi</button>
                                    </div>
                                </div>
                            </div>

                            <div id="lockOverlay" class="hidden absolute inset-0 bg-white/92 dark:bg-[#050912]/95 backdrop-blur-md z-30 flex flex-col items-center justify-center text-center p-8 rounded-[2rem]">
                                <div class="w-20 h-20 bg-emerald-100 dark:bg-emerald-500/10 rounded-full flex items-center justify-center mb-5 border border-emerald-300 dark:border-emerald-500/50 shadow-[0_0_50px_rgba(16,185,129,.25)] animate-bounce">
                                    <svg class="w-10 h-10 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <h3 class="text-2xl md:text-3xl font-black text-slate-900 dark:text-white mb-2">AKTIVITAS SELESAI</h3>
                                <p class="text-sm font-bold text-slate-500 dark:text-white/60 max-w-sm">Pemahaman layout responsif sudah valid dan progress berhasil disimpan.</p>
                            </div>
                        </div>
                    </section>
                </article>

                <div class="mt-20 md:mt-32 pt-8 border-t border-adaptive flex flex-col sm:flex-row justify-between items-center gap-4 transition-colors">
                    <a href="{{ \Illuminate\Support\Facades\Route::has('courses.grid') ? route('courses.grid') : '#' }}" class="group flex items-center gap-4 text-muted hover:text-heading transition-colors w-full sm:w-auto justify-center sm:justify-start">
                        <div class="w-10 h-10 md:w-12 md:h-12 rounded-full border border-adaptive flex items-center justify-center group-hover:bg-slate-100 dark:group-hover:bg-white/5 transition-colors shrink-0">
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        </div>
                        <div class="text-left"><div class="text-[10px] uppercase tracking-widest font-bold opacity-60">Sebelumnya</div><div class="font-black text-xs md:text-sm line-clamp-1">Grid</div></div>
                    </a>
                    <div id="nextChapterBtn" class="group flex items-center gap-4 text-right text-muted cursor-not-allowed opacity-50 pointer-events-none transition-all duration-500 w-full sm:w-auto justify-center sm:justify-end">
                        <div class="text-right"><div id="nextLabel" class="text-[10px] uppercase tracking-widest font-bold opacity-60">Terkunci</div><div class="font-black text-xs md:text-sm line-clamp-1">Evaluasi Bab 2</div></div>
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
    window.LESSON_IDS = [41, 42, 43, 44, 45];
    let rawCompletedIds = {!! json_encode($completedLessonIds ?? []) !!};
    window.COMPLETED_IDS = rawCompletedIds.map(id => Number(id));
    let completedSet = new Set(window.COMPLETED_IDS);
    const ACTIVITY_LESSON_ID = 45;
    let activityCompleted = {!! ($activityCompleted ?? false) ? 'true' : 'false' !!};
    let selectedAnswers = {};

    const activityItems = [
        { id: 1, need: 'Kartu satu kolom pada layar kecil dan dua kolom pada layar sedang.', answer: 'C', explain: 'grid grid-cols-1 md:grid-cols-2 membuat kartu satu kolom lebih dahulu, lalu dua kolom pada layar sedang.' },
        { id: 2, need: 'Kartu tersusun ke bawah pada layar kecil dan ke samping pada layar sedang.', answer: 'D', explain: 'flex flex-col md:flex-row membuat susunan turun pada layar kecil dan menyamping pada layar sedang.' },
        { id: 3, need: 'Jarak antar kartu kecil pada layar kecil dan lebih besar pada layar sedang.', answer: 'A', explain: 'gap-3 md:gap-6 mengatur jarak kecil terlebih dahulu, lalu memperbesar gap pada breakpoint md.' },
        { id: 4, need: 'Padding section kecil pada layar kecil dan lebih besar pada layar sedang.', answer: 'B', explain: 'p-4 md:p-8 membuat padding section hemat di layar kecil dan lebih lega di layar sedang.' }
    ];
    const choices = ['A','B','C','D','E','F'];

    document.addEventListener('DOMContentLoaded', () => {
        initSidebarScroll();
        initVisualEffects();
        renderActivity();
        updateProgressUI(false);
        setDevice('mobile');
        setResponsiveGrid('small');
        setFlexResponsive('small');
        setSpaceResponsive('small');
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
                return true;
            }
        } catch(e) { console.error('Network Error:', e); }
        return false;
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
                ctx.fillStyle = '#7dd3fc';
                ctx.fill();
            });
            requestAnimationFrame(draw);
        }
        resize(); draw(); window.addEventListener('resize', resize);
    }

    function setDevice(type) {
        document.querySelectorAll('[data-device]').forEach(btn => btn.classList.remove('active'));
        document.querySelector(`[data-device="${type}"]`)?.classList.add('active');
        const shell = document.getElementById('deviceShell');
        const title = document.getElementById('breakTitle');
        const label = document.getElementById('deviceLabel');
        const desc = document.getElementById('breakDesc');
        const base = document.getElementById('breakBase');
        const md = document.getElementById('breakMd');
        const lg = document.getElementById('breakLg');
        [base, md, lg].forEach(el => { el.className = 'text-slate-400'; });
        if(type === 'mobile') {
            shell.style.width = '280px'; label.textContent = 'Layar kecil'; title.className = 'font-black text-slate-900 dark:text-white text-2xl leading-tight transition-all duration-300'; base.className = 'text-sky-600 dark:text-sky-400 font-bold'; desc.innerHTML = 'Pada layar kecil, class dasar <code>text-2xl</code> aktif terlebih dahulu.';
        } else if(type === 'tablet') {
            shell.style.width = '440px'; label.textContent = 'Layar sedang / md'; title.className = 'font-black text-slate-900 dark:text-white text-4xl leading-tight transition-all duration-300'; md.className = 'text-indigo-600 dark:text-indigo-400 font-bold'; desc.innerHTML = 'Pada layar sedang, class <code>md:text-4xl</code> mulai menggantikan ukuran dasar.';
        } else {
            shell.style.width = '560px'; label.textContent = 'Layar besar / lg'; title.className = 'font-black text-slate-900 dark:text-white text-5xl leading-tight transition-all duration-300'; lg.className = 'text-purple-600 dark:text-purple-400 font-bold'; desc.innerHTML = 'Pada layar besar, class <code>lg:text-5xl</code> aktif sehingga judul menjadi lebih besar.';
        }
    }

    function setResponsiveGrid(type) {
        const device = document.getElementById('gridDevice');
        const preview = document.getElementById('gridResponsivePreview');
        const desc = document.getElementById('gridResponsiveDesc');
        preview.classList.remove('grid-cols-1', 'grid-cols-2');
        if(type === 'small') {
            device.style.width = '290px'; preview.classList.add('grid-cols-1'); desc.innerHTML = 'Pada layar kecil, class dasar <code>grid-cols-1</code> membuat kartu tersusun satu kolom.';
        } else {
            device.style.width = '540px'; preview.classList.add('grid-cols-2'); desc.innerHTML = 'Pada layar sedang, class <code>md:grid-cols-2</code> mulai aktif sehingga kartu tersusun dua kolom.';
        }
    }

    function setFlexResponsive(type) {
        const device = document.getElementById('flexDevice');
        const preview = document.getElementById('flexResponsivePreview');
        const desc = document.getElementById('flexResponsiveDesc');
        preview.classList.remove('flex-col', 'flex-row');
        if(type === 'small') {
            device.style.width = '300px'; preview.classList.add('flex-col'); desc.innerHTML = 'Pada layar kecil, <code>flex-col</code> membuat gambar dan teks tersusun ke bawah.';
        } else {
            device.style.width = '560px'; preview.classList.add('flex-row'); desc.innerHTML = 'Pada layar sedang, <code>md:flex-row</code> aktif sehingga gambar dan teks tersusun ke samping.';
        }
    }

    function setSpaceResponsive(type) {
        const preview = document.getElementById('spacePreview');
        const inner = document.getElementById('spaceInner');
        const desc = document.getElementById('spaceResponsiveDesc');
        preview.classList.remove('p-4', 'p-8');
        inner.classList.remove('gap-3', 'gap-6', 'grid-cols-1', 'grid-cols-3');
        if(type === 'small') {
            preview.style.width = '300px'; preview.classList.add('p-4'); inner.classList.add('gap-3', 'grid-cols-1'); desc.innerHTML = 'Pada layar kecil, section memakai <code>p-4</code> dan jarak antar kartu memakai <code>gap-3</code>.';
        } else {
            preview.style.width = '560px'; preview.classList.add('p-8'); inner.classList.add('gap-6', 'grid-cols-3'); desc.innerHTML = 'Pada layar sedang, <code>md:p-8</code> dan <code>md:gap-6</code> aktif sehingga ruang tampilan lebih lega.';
        }
    }

    function renderActivity() {
        const container = document.getElementById('responsiveActivityList');
        if(!container) return;
        container.innerHTML = activityItems.map((item) => `
            <div class="card-adaptive border border-adaptive rounded-2xl p-5" data-q="${item.id}">
                <div class="flex items-start gap-3 mb-4">
                    <div class="w-7 h-7 rounded-lg bg-sky-500/10 text-sky-600 dark:text-sky-400 flex items-center justify-center font-black text-xs shrink-0 border border-sky-500/20">${item.id}</div>
                    <div>
                        <h4 class="text-sm font-bold text-heading leading-relaxed">${item.need}</h4>
                        <p class="explain hidden text-xs text-muted mt-2 leading-relaxed"></p>
                    </div>
                </div>
                <div class="grid grid-cols-3 sm:grid-cols-6 gap-2">
                    ${choices.map((choice) => `
                        <button type="button" onclick="selectAnswer(${item.id}, '${choice}', this)" class="choice-card text-center px-3 py-3 rounded-xl border border-adaptive bg-white/70 dark:bg-black/20 hover:border-sky-400 transition text-xs font-black text-slate-700 dark:text-slate-200">${choice}</button>
                    `).join('')}
                </div>
            </div>
        `).join('');
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
        if(Object.keys(selectedAnswers).length < activityItems.length) {
            result.innerText = 'Lengkapi semua jawaban terlebih dahulu.';
            result.className = 'text-xs text-rose-600 dark:text-rose-400 mt-2 leading-relaxed';
            document.getElementById('responsiveActivityList').classList.add('shake');
            setTimeout(() => document.getElementById('responsiveActivityList').classList.remove('shake'), 400);
            return;
        }
        let score = 0;
        activityItems.forEach(item => {
            const wrapper = document.querySelector(`[data-q="${item.id}"]`);
            const buttons = wrapper.querySelectorAll('.choice-card');
            buttons.forEach(btn => {
                btn.classList.remove('selected','correct','wrong');
                if(btn.textContent.trim() === item.answer) btn.classList.add('correct');
                if(selectedAnswers[item.id] === btn.textContent.trim() && selectedAnswers[item.id] !== item.answer) btn.classList.add('wrong');
            });
            const explain = wrapper.querySelector('.explain');
            explain.classList.remove('hidden');
            explain.innerText = item.explain;
            if(selectedAnswers[item.id] === item.answer) score++;
        });
        document.getElementById('activityScoreLabel').innerText = `${score}/${activityItems.length}`;
        if(score >= 3) {
            result.innerText = `Skor ${score}/4. Aktivitas valid dan progress disimpan.`;
            result.className = 'text-xs text-emerald-600 dark:text-emerald-400 mt-2 leading-relaxed font-bold';
            await saveLessonToDB(ACTIVITY_LESSON_ID);
            activityCompleted = true;
            lockActivityUI();
            unlockNextChapter();
        } else {
            result.innerText = `Skor ${score}/4. Minimal 3 benar. Pelajari pembahasan, baca ulang breakpoint responsif, lalu ulangi aktivitas.`;
            result.className = 'text-xs text-rose-600 dark:text-rose-400 mt-2 leading-relaxed font-bold';
        }
    }

    function resetActivity() {
        if(activityCompleted) return;
        selectedAnswers = {};
        renderActivity();
        document.getElementById('activityScoreLabel').innerText = '0/4';
        const result = document.getElementById('activityResult');
        result.innerText = 'Pilih jawaban pada setiap kebutuhan.';
        result.className = 'text-xs text-muted mt-2 leading-relaxed';
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
        btn.classList.add('text-sky-600','dark:text-sky-400','cursor-pointer');
        document.getElementById('nextLabel').innerText = 'Selanjutnya';
        document.getElementById('nextLabel').classList.remove('opacity-60');
        const icon = document.getElementById('nextIcon');
        icon.innerHTML = `<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>`;
        icon.classList.remove('bg-slate-100','dark:bg-white/5');
        icon.classList.add('bg-sky-100','dark:bg-sky-500/20','border-sky-300','dark:border-sky-500/50','text-sky-600','dark:text-sky-400','shadow-lg');
        btn.onclick = () => window.location.href = "{{ \Illuminate\Support\Facades\Route::has('quiz.intro') ? route('quiz.intro', ['chapterId' => 2]) : '#' }}";
    }
</script>
@endsection
