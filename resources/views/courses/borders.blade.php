@extends('layouts.landing')
@section('title','Border, Radius, dan Bayangan')

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
        --accent: #6366f1;
        --accent-glow: rgba(99,102,241,.34);
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
        --accent-glow: rgba(99,102,241,.55);
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
        background-color: rgba(99,102,241,.14);
        color: #4f46e5;
        padding: .125rem .375rem;
        border-radius: .375rem;
        font-weight: 700;
        white-space: nowrap;
        border: 1px solid rgba(99,102,241,.28);
    }
    .dark .hl-term { color: #a5b4fc; background-color: rgba(99,102,241,.18); border-color: rgba(129,140,248,.36); }
    .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(148,163,184,.42); border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: var(--accent); }
    #animated-bg {
        background: radial-gradient(850px circle at 18% 18%, rgba(99,102,241,.12), transparent 42%),
                    radial-gradient(850px circle at 82% 78%, rgba(168,85,247,.12), transparent 42%);
        animation: bgMove 20s ease-in-out infinite alternate;
    }
    .dark #animated-bg {
        background: radial-gradient(850px circle at 18% 18%, rgba(99,102,241,.18), transparent 42%),
                    radial-gradient(850px circle at 82% 78%, rgba(168,85,247,.16), transparent 42%);
    }
    @keyframes bgMove { to { transform: scale(1.12); } }
    @keyframes shake { 0%,100% { transform: translateX(0); } 25% { transform: translateX(-5px); } 75% { transform: translateX(5px); } }
    .shake { animation: shake .35s ease-in-out; }

    .nav-item { display: flex; width: 100%; text-align: left; align-items: center; gap: 12px; padding: 10px 14px; font-size: .85rem; color: var(--text-muted); border-radius: 8px; transition: all .2s; position: relative; }
    .nav-item:hover { color: var(--text-main); background: rgba(99,102,241,.06); }
    .nav-item.active { color: #4f46e5; background: rgba(99,102,241,.08); font-weight: 700; }
    .dark .nav-item.active { color: #a5b4fc; }
    .dot { width: 6px; height: 6px; border-radius: 50%; background: #94a3b8; transition: all .3s; }
    .dark .dot { background: #334155; }
    .nav-item.active .dot { background: #6366f1; box-shadow: 0 0 10px #6366f1; transform: scale(1.25); }

    .choice-card.selected { border-color: #6366f1; background: rgba(99,102,241,.10); }
    .choice-card.correct { border-color: #10b981; background: rgba(16,185,129,.12); }
    .choice-card.wrong { border-color: #f43f5e; background: rgba(244,63,94,.10); }
</style>

<div id="courseRoot" class="relative h-screen bg-adaptive text-adaptive font-sans overflow-hidden flex flex-col selection:bg-indigo-500/30 pt-20 transition-colors duration-500">
    <div class="fixed inset-0 -z-50 pointer-events-none">
        <div id="animated-bg" class="absolute inset-0 opacity-60 transition-opacity"></div>
        <div class="absolute top-[-20%] right-[-10%] w-[800px] h-[800px] bg-indigo-400/10 dark:bg-indigo-900/20 rounded-full blur-[130px] animate-pulse transition-colors"></div>
        <div class="absolute bottom-[-20%] left-[-10%] w-[700px] h-[700px] bg-purple-500/10 dark:bg-purple-900/20 rounded-full blur-[120px] transition-colors"></div>
        <canvas id="stars" class="absolute inset-0 pointer-events-none opacity-0 dark:opacity-100 transition-opacity duration-500"></canvas>
    </div>

    @include('layouts.partials.navbar')

    <div class="flex flex-1 overflow-hidden relative z-20 h-full">
        @include('layouts.partials.course-sidebar')

        <main id="mainScroll" class="flex-1 h-full overflow-y-auto scroll-smooth relative bg-transparent custom-scrollbar scroll-padding-top-24">
            <div id="stickyHeader" class="sticky top-0 z-30 w-full backdrop-blur-2xl border-b border-adaptive px-4 md:px-8 py-4 flex items-center justify-between transition-colors duration-500" style="background-color: var(--glass-header);">
                <div class="flex items-center gap-4 min-w-0">
                    <div class="w-8 h-8 rounded-lg bg-indigo-500/10 dark:bg-indigo-500/20 border border-indigo-500/20 flex items-center justify-center font-bold text-xs text-indigo-600 dark:text-indigo-400 shrink-0">3.3</div>
                    <div class="min-w-0">
                        <h1 class="text-sm font-bold text-heading line-clamp-1 transition-colors">Border, Radius, dan Bayangan</h1>
                        <p class="text-[10px] text-muted line-clamp-1 transition-colors">Garis tepi, sudut melengkung, dan efek menonjol</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <div class="hidden sm:block w-24 md:w-32 h-1.5 bg-slate-200 dark:bg-white/10 rounded-full overflow-hidden transition-colors">
                        <div id="topProgressBar" class="h-full bg-gradient-to-r from-indigo-500 to-purple-500 w-0 transition-all duration-500 shadow-[0_0_12px_rgba(99,102,241,.55)]"></div>
                    </div>
                    <span id="progressLabelTop" class="text-indigo-600 dark:text-indigo-400 font-bold text-xs transition-colors">0%</span>
                </div>
            </div>

            <div class="p-4 sm:p-6 lg:p-16 max-w-5xl mx-auto pb-40">
                <div class="mb-16 md:mb-24">
                    <div class="card-adaptive rounded-3xl border p-6 md:p-8 overflow-hidden relative mb-10">
                        <div class="absolute -right-10 -top-10 w-40 h-40 bg-indigo-500/10 rounded-full blur-3xl"></div>
                        <p class="text-xs font-mono font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-[.25em] mb-3">Subbab 3.3</p>
                        <h2 class="text-3xl md:text-5xl font-black text-heading leading-tight mb-4">Border, Radius, dan Bayangan</h2>
                        <p class="text-sm md:text-base text-muted leading-relaxed max-w-3xl text-justify">
                            Pada subbab ini, pembelajar mempelajari cara memperjelas bentuk elemen menggunakan garis tepi, sudut melengkung, dan bayangan. Ketiga bagian ini sering digunakan pada kartu, tombol, kotak informasi, dan formulir agar komponen lebih mudah dibedakan dari latar halaman.
                        </p>
                    </div>

                    <h3 class="text-xl font-bold text-heading mb-6 flex items-center gap-2 transition-colors">
                        <svg class="w-5 h-5 text-indigo-500 dark:text-indigo-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Tujuan Pembelajaran
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="card-adaptive border p-5 rounded-xl flex items-start gap-4 h-full">
                            <div class="w-9 h-9 rounded-lg bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0 font-bold text-xs border border-indigo-500/20">1</div>
                            <div><h4 class="text-sm font-bold text-heading mb-1">Border</h4><p class="text-[11px] text-muted leading-relaxed">Menjelaskan fungsi garis tepi dan warna border pada elemen.</p></div>
                        </div>
                        <div class="card-adaptive border p-5 rounded-xl flex items-start gap-4 h-full">
                            <div class="w-9 h-9 rounded-lg bg-purple-500/10 text-purple-600 dark:text-purple-400 flex items-center justify-center shrink-0 font-bold text-xs border border-purple-500/20">2</div>
                            <div><h4 class="text-sm font-bold text-heading mb-1">Radius</h4><p class="text-[11px] text-muted leading-relaxed">Menerapkan sudut melengkung pada kartu dan tombol.</p></div>
                        </div>
                        <div class="card-adaptive border p-5 rounded-xl flex items-start gap-4 h-full">
                            <div class="w-9 h-9 rounded-lg bg-pink-500/10 text-pink-600 dark:text-pink-400 flex items-center justify-center shrink-0 font-bold text-xs border border-pink-500/20">3</div>
                            <div><h4 class="text-sm font-bold text-heading mb-1">Shadow</h4><p class="text-[11px] text-muted leading-relaxed">Membedakan bayangan ringan, sedang, besar, dan tanpa bayangan.</p></div>
                        </div>
                        <div class="card-adaptive border p-5 rounded-xl flex items-start gap-4 h-full">
                            <div class="w-9 h-9 rounded-lg bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 font-bold text-xs border border-emerald-500/20">4</div>
                            <div><h4 class="text-sm font-bold text-heading mb-1">Kombinasi</h4><p class="text-[11px] text-muted leading-relaxed">Memilih class yang tepat sesuai kebutuhan tampilan elemen.</p></div>
                        </div>
                    </div>
                </div>

                <article class="space-y-24 md:space-y-32">
                    <section id="section-51" class="lesson-section scroll-mt-32" data-lesson-id="51">
                        <div class="space-y-8 md:space-y-10">
                            <div class="space-y-4 border-l-4 border-indigo-500 pl-4 md:pl-6">
                                <span class="text-indigo-600 dark:text-indigo-400 font-mono text-xs uppercase tracking-widest">Lesson 3.3.1</span>
                                <h2 class="text-2xl md:text-4xl lg:text-5xl font-black text-heading leading-[1.1]">Fungsi Dasar <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-purple-600 dark:from-indigo-400 dark:to-purple-500">Border, Radius, Shadow</span></h2>
                            </div>
                            <div class="space-y-4 prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-85 text-sm md:text-base leading-relaxed text-justify">
                                <p><span class="hl-term">Border</span> digunakan untuk memberi garis tepi pada elemen. Garis tepi membantu pengguna melihat batas kartu, tombol, atau kotak informasi dengan lebih jelas.</p>
                                <p><span class="hl-term">Radius</span> digunakan untuk membuat sudut elemen melengkung. Radius membuat bentuk elemen terlihat lebih lembut dan tidak terlalu kaku.</p>
                                <p><span class="hl-term">Shadow</span> digunakan untuk memberi bayangan. Bayangan membuat elemen terlihat lebih menonjol dari latar halaman, terutama pada komponen kartu.</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="card-adaptive border rounded-xl p-5">
                                    <div class="h-24 bg-white dark:bg-slate-900 border border-slate-300 flex items-center justify-center text-xs font-bold text-muted">border</div>
                                    <p class="text-xs text-muted leading-relaxed mt-3">Garis tepi memberi batas visual pada elemen.</p>
                                </div>
                                <div class="card-adaptive border rounded-xl p-5">
                                    <div class="h-24 bg-white dark:bg-slate-900 border border-slate-300 rounded-xl flex items-center justify-center text-xs font-bold text-muted">rounded-xl</div>
                                    <p class="text-xs text-muted leading-relaxed mt-3">Radius membuat sudut elemen melengkung.</p>
                                </div>
                                <div class="card-adaptive border rounded-xl p-5">
                                    <div class="h-24 bg-white dark:bg-slate-900 border border-slate-300 rounded-xl shadow-md flex items-center justify-center text-xs font-bold text-muted">shadow-md</div>
                                    <p class="text-xs text-muted leading-relaxed mt-3">Bayangan membuat kartu lebih menonjol.</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="section-52" class="lesson-section scroll-mt-32" data-lesson-id="52">
                        <div class="space-y-8 md:space-y-10">
                            <div class="space-y-4 border-l-4 border-purple-500 pl-4 md:pl-6">
                                <span class="text-purple-600 dark:text-purple-400 font-mono text-xs uppercase tracking-widest">Lesson 3.3.2</span>
                                <h2 class="text-2xl md:text-4xl lg:text-5xl font-black text-heading leading-[1.1]">Mengatur <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-500 to-pink-600 dark:from-purple-400 dark:to-pink-500">Border</span></h2>
                            </div>
                            <div class="space-y-4 prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-85 text-sm md:text-base leading-relaxed text-justify">
                                <p>Dalam Tailwind CSS, garis tepi dasar dibuat dengan class <code>border</code>. Jika garis tepi perlu diberi warna, gunakan class <code>border-*</code>, misalnya <code>border-slate-300</code>.</p>
                                <p>Border sering dipakai pada kartu informasi, form input, tabel, dan tombol sekunder. Border membantu elemen tetap terlihat walaupun latarnya sama-sama terang.</p>
                            </div>

                            <div class="sim-bg-adaptive border border-adaptive rounded-2xl p-5 md:p-8 shadow-xl relative overflow-hidden">
                                <h4 class="text-xs font-bold text-muted uppercase mb-4 text-center tracking-widest">Simulator: Border dan Warna Garis Tepi</h4>
                                <div class="bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-500/30 rounded-xl p-4 mb-6 text-sm text-purple-800 dark:text-purple-300">
                                    <p class="font-bold mb-1">Panduan Simulasi</p>
                                    <p class="text-xs m-0 leading-relaxed">Aktifkan border, lalu pilih warna garis tepi. Perhatikan bagaimana kartu lebih mudah dibedakan dari latar halaman.</p>
                                </div>
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                    <div class="space-y-4">
                                        <div>
                                            <p class="text-[10px] font-bold text-muted uppercase tracking-widest mb-2">Garis Tepi</p>
                                            <div class="grid grid-cols-2 gap-2">
                                                <button onclick="setBorderState(false)" class="px-3 py-2 rounded-lg bg-white dark:bg-black/30 border border-adaptive text-xs font-bold hover:border-purple-400">Tanpa border</button>
                                                <button onclick="setBorderState(true)" class="px-3 py-2 rounded-lg bg-purple-600 text-white border border-purple-400 text-xs font-bold">border</button>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-bold text-muted uppercase tracking-widest mb-2">Warna Border</p>
                                            <div class="grid grid-cols-3 gap-2">
                                                <button onclick="setBorderColor('border-slate-300')" class="px-3 py-2 rounded-lg bg-white dark:bg-black/30 border border-slate-300 text-xs font-bold">slate</button>
                                                <button onclick="setBorderColor('border-indigo-400')" class="px-3 py-2 rounded-lg bg-indigo-600 text-white border border-indigo-400 text-xs font-bold">indigo</button>
                                                <button onclick="setBorderColor('border-rose-400')" class="px-3 py-2 rounded-lg bg-rose-600 text-white border border-rose-400 text-xs font-bold">rose</button>
                                            </div>
                                        </div>
                                        <div class="code-adaptive border rounded-xl p-4 font-mono text-xs overflow-x-auto custom-scrollbar">
                                            &lt;div class=&quot;<span id="borderCode" class="text-purple-600 dark:text-purple-400 font-bold">border border-slate-300</span> p-4&quot;&gt;
                                        </div>
                                    </div>
                                    <div class="bg-slate-100 dark:bg-black/30 rounded-xl border border-adaptive p-6 flex items-center justify-center min-h-[260px]">
                                        <article id="borderPreview" class="bg-white dark:bg-slate-900 border border-slate-300 p-6 w-full max-w-sm transition-all duration-300">
                                            <h3 class="text-lg font-black text-slate-900 dark:text-white">Kartu Informasi</h3>
                                            <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">Border membantu batas kartu terlihat lebih jelas.</p>
                                        </article>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="section-53" class="lesson-section scroll-mt-32" data-lesson-id="53">
                        <div class="space-y-8 md:space-y-10">
                            <div class="space-y-4 border-l-4 border-pink-500 pl-4 md:pl-6">
                                <span class="text-pink-600 dark:text-pink-400 font-mono text-xs uppercase tracking-widest">Lesson 3.3.3</span>
                                <h2 class="text-2xl md:text-4xl lg:text-5xl font-black text-heading leading-[1.1]">Mengatur <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-pink-500 to-rose-600 dark:from-pink-400 dark:to-rose-500">Radius</span></h2>
                            </div>
                            <div class="space-y-4 prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-85 text-sm md:text-base leading-relaxed text-justify">
                                <p>Radius mengatur kelengkungan sudut elemen. Class yang sering digunakan adalah <code>rounded</code>, <code>rounded-md</code>, <code>rounded-lg</code>, <code>rounded-xl</code>, dan <code>rounded-full</code>.</p>
                                <p>Pada tombol, class <code>rounded-lg</code> sudah cukup untuk membuat sudut terlihat lebih lembut. Pada kartu, <code>rounded-xl</code> sering digunakan agar bentuk kartu terlihat modern dan nyaman dilihat.</p>
                            </div>

                            <div class="sim-bg-adaptive border border-adaptive rounded-2xl p-5 md:p-8 shadow-xl relative overflow-hidden">
                                <h4 class="text-xs font-bold text-muted uppercase mb-4 text-center tracking-widest">Simulator: Sudut Melengkung</h4>
                                <div class="bg-pink-50 dark:bg-pink-900/20 border border-pink-200 dark:border-pink-500/30 rounded-xl p-4 mb-6 text-sm text-pink-800 dark:text-pink-300">
                                    <p class="font-bold mb-1">Panduan Simulasi</p>
                                    <p class="text-xs m-0 leading-relaxed">Pilih bentuk radius. Preview menunjukkan perbedaan sudut datar, sedang, besar, dan lingkaran penuh.</p>
                                </div>
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                    <div class="space-y-3">
                                        <button onclick="setRadius('rounded-none')" class="w-full text-left px-4 py-3 rounded-xl bg-white dark:bg-black/30 border border-adaptive text-xs font-bold hover:border-pink-400">rounded-none</button>
                                        <button onclick="setRadius('rounded-lg')" class="w-full text-left px-4 py-3 rounded-xl bg-white dark:bg-black/30 border border-adaptive text-xs font-bold hover:border-pink-400">rounded-lg</button>
                                        <button onclick="setRadius('rounded-xl')" class="w-full text-left px-4 py-3 rounded-xl bg-pink-600 text-white border border-pink-400 text-xs font-bold">rounded-xl</button>
                                        <button onclick="setRadius('rounded-full')" class="w-full text-left px-4 py-3 rounded-xl bg-white dark:bg-black/30 border border-adaptive text-xs font-bold hover:border-pink-400">rounded-full</button>
                                        <div class="code-adaptive border rounded-xl p-4 font-mono text-xs overflow-x-auto custom-scrollbar">
                                            &lt;button class=&quot;<span id="radiusCode" class="text-pink-600 dark:text-pink-400 font-bold">rounded-xl</span> px-4 py-2&quot;&gt;
                                        </div>
                                    </div>
                                    <div class="bg-slate-100 dark:bg-black/30 rounded-xl border border-adaptive p-6 flex items-center justify-center min-h-[260px]">
                                        <button id="radiusPreview" class="bg-pink-600 text-white px-10 py-5 rounded-xl font-bold transition-all duration-300">Simpan</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="section-54" class="lesson-section scroll-mt-32" data-lesson-id="54">
                        <div class="space-y-8 md:space-y-10">
                            <div class="space-y-4 border-l-4 border-emerald-500 pl-4 md:pl-6">
                                <span class="text-emerald-600 dark:text-emerald-400 font-mono text-xs uppercase tracking-widest">Lesson 3.3.4</span>
                                <h2 class="text-2xl md:text-4xl lg:text-5xl font-black text-heading leading-[1.1]">Mengatur <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-500 to-indigo-600 dark:from-emerald-400 dark:to-indigo-500">Bayangan</span></h2>
                            </div>
                            <div class="space-y-4 prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-85 text-sm md:text-base leading-relaxed text-justify">
                                <p>Bayangan membuat elemen terlihat terangkat dari latar halaman. Class <code>shadow</code> memberi bayangan ringan, <code>shadow-md</code> memberi bayangan sedang, <code>shadow-lg</code> memberi bayangan lebih besar, sedangkan <code>shadow-none</code> menghapus bayangan.</p>
                                <p>Gunakan bayangan secukupnya. Pada kartu informasi, <code>shadow-md</code> biasanya sudah cukup untuk membuat kartu lebih menonjol tanpa terlihat berlebihan.</p>
                            </div>

                            <div class="sim-bg-adaptive border border-adaptive rounded-2xl p-5 md:p-8 shadow-xl relative overflow-hidden">
                                <h4 class="text-xs font-bold text-muted uppercase mb-4 text-center tracking-widest">Simulator: Kekuatan Bayangan</h4>
                                <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-500/30 rounded-xl p-4 mb-6 text-sm text-emerald-800 dark:text-emerald-300">
                                    <p class="font-bold mb-1">Panduan Simulasi</p>
                                    <p class="text-xs m-0 leading-relaxed">Pilih tingkat bayangan. Perhatikan bagaimana kartu terlihat semakin menonjol dari latar.</p>
                                </div>
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                    <div class="space-y-3">
                                        <button onclick="setShadow('shadow-none')" class="w-full text-left px-4 py-3 rounded-xl bg-white dark:bg-black/30 border border-adaptive text-xs font-bold hover:border-emerald-400">shadow-none</button>
                                        <button onclick="setShadow('shadow')" class="w-full text-left px-4 py-3 rounded-xl bg-white dark:bg-black/30 border border-adaptive text-xs font-bold hover:border-emerald-400">shadow</button>
                                        <button onclick="setShadow('shadow-md')" class="w-full text-left px-4 py-3 rounded-xl bg-emerald-600 text-white border border-emerald-400 text-xs font-bold">shadow-md</button>
                                        <button onclick="setShadow('shadow-lg')" class="w-full text-left px-4 py-3 rounded-xl bg-white dark:bg-black/30 border border-adaptive text-xs font-bold hover:border-emerald-400">shadow-lg</button>
                                        <div class="code-adaptive border rounded-xl p-4 font-mono text-xs overflow-x-auto custom-scrollbar">
                                            &lt;article class=&quot;bg-white rounded-xl <span id="shadowCode" class="text-emerald-600 dark:text-emerald-400 font-bold">shadow-md</span>&quot;&gt;
                                        </div>
                                    </div>
                                    <div class="bg-slate-100 dark:bg-black/30 rounded-xl border border-adaptive p-8 flex items-center justify-center min-h-[280px]">
                                        <article id="shadowPreview" class="bg-white dark:bg-slate-900 rounded-xl shadow-md p-6 w-full max-w-sm transition-all duration-300">
                                            <p class="text-[10px] uppercase tracking-widest text-emerald-600 dark:text-emerald-400 font-bold">Preview</p>
                                            <h3 class="text-xl font-black text-slate-900 dark:text-white mt-2">Kartu Produk</h3>
                                            <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">Bayangan membantu kartu terlihat lebih menonjol.</p>
                                        </article>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="section-55" class="lesson-section scroll-mt-32 pt-10 border-t border-adaptive" data-lesson-id="55" data-type="activity">
                        <div class="relative rounded-[2rem] sim-bg-adaptive border border-adaptive p-5 sm:p-6 md:p-10 overflow-hidden shadow-xl group hover:border-indigo-500/30 transition-all duration-500">
                            <div class="absolute -top-24 -right-24 w-64 h-64 bg-indigo-600/10 dark:bg-indigo-600/20 blur-[100px] rounded-full pointer-events-none"></div>
                            <div class="flex items-start md:items-center gap-5 mb-8 flex-col md:flex-row relative z-10">
                                <div class="p-4 bg-gradient-to-br from-indigo-600 to-purple-700 rounded-2xl text-white shadow-lg shadow-indigo-500/20 shrink-0">
                                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M4 8h16M4 16h16M8 4v16M16 4v16"/></svg>
                                </div>
                                <div>
                                    <h2 class="text-2xl md:text-3xl font-black text-heading tracking-tight">Aktivitas 3.3: Pilih Class Tampilan</h2>
                                    <p class="text-muted text-sm leading-relaxed mt-2 max-w-3xl text-justify">Pilih class Tailwind CSS yang sesuai dengan kebutuhan tampilan. Aktivitas selesai jika skor minimal 4 dari 5.</p>
                                </div>
                            </div>

                            <div id="activityPanel" class="space-y-5 relative z-10">
                                <div id="quizContainer" class="space-y-5"></div>
                                <div class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center justify-between pt-4">
                                    <div id="activityResult" class="text-sm font-bold text-muted">Pilih jawaban pada setiap kebutuhan.</div>
                                    <div class="flex gap-2">
                                        <button onclick="resetActivity()" class="px-5 py-3 rounded-xl border border-adaptive text-xs font-bold hover:bg-slate-100 dark:hover:bg-white/10 transition">Ulangi</button>
                                        <button id="submitActivityBtn" onclick="submitActivity()" class="px-5 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow-lg shadow-indigo-500/20 transition">Periksa Jawaban</button>
                                    </div>
                                </div>
                            </div>

                            <div id="lockOverlay" class="hidden absolute inset-0 bg-white/92 dark:bg-[#050912]/95 backdrop-blur-md z-30 flex flex-col items-center justify-center text-center p-8 rounded-[2rem]">
                                <div class="w-20 h-20 bg-emerald-100 dark:bg-emerald-500/10 rounded-full flex items-center justify-center mb-5 border border-emerald-300 dark:border-emerald-500/50 shadow-[0_0_50px_rgba(16,185,129,.25)] animate-bounce">
                                    <svg class="w-10 h-10 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <h3 class="text-2xl md:text-3xl font-black text-slate-900 dark:text-white mb-2">AKTIVITAS SELESAI</h3>
                                <p class="text-sm font-bold text-slate-500 dark:text-white/60 max-w-sm">Pemahaman border, radius, dan bayangan sudah valid dan progress berhasil disimpan.</p>
                            </div>
                        </div>
                    </section>
                </article>

                <div class="mt-20 md:mt-32 pt-8 border-t border-adaptive flex flex-col sm:flex-row justify-between items-center gap-4 transition-colors">
                    <a href="{{ \Illuminate\Support\Facades\Route::has('courses.backgrounds') ? route('courses.backgrounds') : '#' }}" class="group flex items-center gap-4 text-muted hover:text-heading transition-colors w-full sm:w-auto justify-center sm:justify-start">
                        <div class="w-10 h-10 md:w-12 md:h-12 rounded-full border border-adaptive flex items-center justify-center group-hover:bg-slate-100 dark:group-hover:bg-white/5 transition-colors shrink-0">
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        </div>
                        <div class="text-left"><div class="text-[10px] uppercase tracking-widest font-bold opacity-60">Sebelumnya</div><div class="font-black text-xs md:text-sm line-clamp-1">Warna dan Latar Belakang</div></div>
                    </a>
                    <div id="nextChapterBtn" class="group flex items-center gap-4 text-right text-muted cursor-not-allowed opacity-50 pointer-events-none transition-all duration-500 w-full sm:w-auto justify-center sm:justify-end">
                        <div class="text-right"><div id="nextLabel" class="text-[10px] uppercase tracking-widest font-bold opacity-60">Terkunci</div><div class="font-black text-xs md:text-sm line-clamp-1">Kuis Bab 3</div></div>
                        <div id="nextIcon" class="w-10 h-10 md:w-12 md:h-12 rounded-full border border-adaptive flex items-center justify-center bg-slate-100 dark:bg-white/5 transition-colors shrink-0">
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
    const ACTIVITY_LESSON_ID = 60;
    let activityCompleted = {!! ($activityCompleted ?? false) ? 'true' : 'false' !!};
    let selectedAnswers = {};

    let borderEnabled = true;
    let borderColor = 'border-slate-300';

    const activityQuestions = [
        { id: 1, need: 'Kotak informasi perlu memiliki garis tepi.', options: ['border', 'border-slate-300', 'shadow-md'], answer: 0, explain: 'Class border digunakan untuk membuat garis tepi dasar pada elemen.' },
        { id: 2, need: 'Garis tepi kartu perlu diberi warna abu-abu.', options: ['border', 'border-slate-300', 'bg-slate-300'], answer: 1, explain: 'Class border-slate-300 memberi warna abu-abu pada garis tepi.' },
        { id: 3, need: 'Tombol perlu memiliki sudut melengkung.', options: ['rounded-lg', 'border', 'shadow-lg'], answer: 0, explain: 'Class rounded-lg membuat sudut tombol melengkung.' },
        { id: 4, need: 'Kartu perlu terlihat lebih menonjol dari latar.', options: ['shadow-md', 'rounded-md', 'border-slate-300'], answer: 0, explain: 'Class shadow-md memberi bayangan sedang agar kartu terlihat menonjol.' },
        { id: 5, need: 'Kartu perlu memiliki latar putih, sudut melengkung, dan bayangan sedang.', options: ['bg-white rounded-xl shadow-md', 'bg-white border-slate-300 rounded-xl', 'bg-white shadow-none rounded-xl'], answer: 0, explain: 'Kombinasi bg-white rounded-xl shadow-md membuat kartu putih dengan sudut melengkung dan bayangan sedang.' }
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
                ctx.fillStyle = '#a5b4fc';
                ctx.fill();
            });
            requestAnimationFrame(draw);
        }
        resize(); draw(); window.addEventListener('resize', resize);
    }

    function setBorderState(active) {
        borderEnabled = active;
        applyBorderSim();
    }
    function setBorderColor(cls) {
        borderColor = cls;
        borderEnabled = true;
        applyBorderSim();
    }
    function applyBorderSim() {
        const preview = document.getElementById('borderPreview');
        const code = document.getElementById('borderCode');
        if(!preview || !code) return;
        preview.classList.remove('border', 'border-0', 'border-slate-300', 'border-indigo-400', 'border-rose-400');
        if(borderEnabled) {
            preview.classList.add('border', borderColor);
            code.innerText = `border ${borderColor}`;
        } else {
            preview.classList.add('border-0');
            code.innerText = 'tanpa border';
        }
    }

    function setRadius(cls) {
        const preview = document.getElementById('radiusPreview');
        const code = document.getElementById('radiusCode');
        if(!preview || !code) return;
        preview.classList.remove('rounded-none','rounded-lg','rounded-xl','rounded-full');
        preview.classList.add(cls);
        code.innerText = cls;
    }

    function setShadow(cls) {
        const preview = document.getElementById('shadowPreview');
        const code = document.getElementById('shadowCode');
        if(!preview || !code) return;
        preview.classList.remove('shadow-none','shadow','shadow-md','shadow-lg');
        preview.classList.add(cls);
        code.innerText = cls;
    }

    function renderActivity() {
        const container = document.getElementById('quizContainer');
        if(!container) return;
        container.innerHTML = activityQuestions.map((q, qi) => `
            <div class="card-adaptive border border-adaptive rounded-2xl p-5" data-q="${q.id}">
                <div class="flex items-start gap-3 mb-4">
                    <div class="w-7 h-7 rounded-lg bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-black text-xs shrink-0 border border-indigo-500/20">${qi+1}</div>
                    <div>
                        <h4 class="text-sm font-bold text-heading leading-relaxed">${q.need}</h4>
                        <p class="explain hidden text-xs text-muted mt-2 leading-relaxed"></p>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    ${q.options.map((opt, oi) => `
                        <button type="button" onclick="selectAnswer(${q.id}, ${oi}, this)" class="choice-card text-left px-4 py-3 rounded-xl border border-adaptive bg-white/70 dark:bg-black/20 hover:border-indigo-400 transition text-xs font-semibold text-slate-700 dark:text-slate-200">□ ${opt}</button>
                    `).join('')}
                </div>
            </div>
        `).join('');
    }

    function selectAnswer(qid, oid, btn) {
        if(activityCompleted) return;
        selectedAnswers[qid] = oid;
        const wrapper = btn.closest('[data-q]');
        wrapper.querySelectorAll('.choice-card').forEach(b => {
            b.classList.remove('selected');
            b.innerText = '□ ' + b.innerText.replace(/^✓\s|^□\s/, '');
        });
        btn.classList.add('selected');
        btn.innerText = '✓ ' + btn.innerText.replace(/^✓\s|^□\s/, '');
    }

    async function submitActivity() {
        if(activityCompleted) return;
        const result = document.getElementById('activityResult');
        if(Object.keys(selectedAnswers).length < activityQuestions.length) {
            result.innerText = 'Lengkapi semua jawaban terlebih dahulu.';
            result.className = 'text-sm font-bold text-rose-600 dark:text-rose-400';
            document.getElementById('activityPanel').classList.add('shake');
            setTimeout(() => document.getElementById('activityPanel').classList.remove('shake'), 400);
            return;
        }
        let score = 0;
        activityQuestions.forEach(q => {
            const wrapper = document.querySelector(`[data-q="${q.id}"]`);
            const buttons = wrapper.querySelectorAll('.choice-card');
            buttons.forEach((btn, idx) => {
                btn.classList.remove('selected','correct','wrong');
                if(idx === q.answer) btn.classList.add('correct');
                if(selectedAnswers[q.id] === idx && idx !== q.answer) btn.classList.add('wrong');
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
        result.innerText = 'Pilih jawaban pada setiap kebutuhan.';
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
        btn.classList.add('text-indigo-600','dark:text-indigo-400','cursor-pointer');
        document.getElementById('nextLabel').innerText = 'Selanjutnya';
        document.getElementById('nextLabel').classList.remove('opacity-60');
        const icon = document.getElementById('nextIcon');
        icon.innerHTML = `<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>`;
        icon.classList.remove('bg-slate-100','dark:bg-white/5');
        icon.classList.add('bg-indigo-100','dark:bg-indigo-500/20','border-indigo-300','dark:border-indigo-500/50','text-indigo-600','dark:text-indigo-400','shadow-lg');
        btn.onclick = () => window.location.href = "{{ route('quiz.intro', ['chapterId' => 3]) ?? '#' }}";
    }
</script>
@endsection
