@extends('layouts.landing')
@section('title','Dasar Layout dan Ruang')

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
        --glass-border: rgba(0, 0, 0, 0.06);
        --glass-header: rgba(255, 255, 255, 0.85);
        --card-bg: #ffffff;
        --card-hover: rgba(0, 0, 0, 0.02);
        --border-color: rgba(0, 0, 0, 0.1);
        --text-muted: #64748b;
        --text-heading: #0f172a;
        --code-bg: #f1f5f9;
        --simulator-bg: #ffffff;
        --accent: #6366f1;
        --accent-glow: rgba(99, 102, 241, 0.28);
    }

    .dark {
        --bg-main: #020617;
        --text-main: #e2e8f0;
        --glass-border: rgba(255, 255, 255, 0.06);
        --glass-header: rgba(2, 6, 23, 0.82);
        --card-bg: #0f172a;
        --card-hover: rgba(255, 255, 255, 0.03);
        --border-color: rgba(255, 255, 255, 0.1);
        --text-muted: rgba(226, 232, 240, 0.62);
        --text-heading: #ffffff;
        --code-bg: #111827;
        --simulator-bg: #0b1120;
        --accent-glow: rgba(99, 102, 241, 0.5);
    }

    body { font-family: 'Inter', sans-serif; background-color: var(--bg-main); color: var(--text-main); transition: background-color 0.4s, color 0.4s; }
    .font-mono { font-family: 'JetBrains Mono', monospace; }
    .bg-adaptive { background-color: var(--bg-main); }
    .text-adaptive { color: var(--text-main); }
    .text-heading { color: var(--text-heading); }
    .text-muted { color: var(--text-muted); }
    .border-adaptive { border-color: var(--border-color); }
    .card-adaptive { background-color: var(--card-bg); border-color: var(--glass-border); transition: all 0.3s; }
    .card-adaptive:hover { border-color: var(--accent-glow); }
    .sim-bg-adaptive { background-color: var(--simulator-bg); }
    .code-adaptive { background-color: var(--code-bg); border-color: var(--glass-border); }

    .hl-term {
        background-color: rgba(99, 102, 241, 0.13);
        color: #4f46e5;
        padding: 0.125rem 0.375rem;
        border-radius: 0.375rem;
        font-weight: 700;
        white-space: nowrap;
        border: 1px solid rgba(99, 102, 241, 0.25);
    }
    .dark .hl-term { background-color: rgba(99, 102, 241, 0.18); color: #a5b4fc; border-color: rgba(129, 140, 248, 0.35); }

    .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(150,150,150,0.30); border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: var(--accent); }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }

    #animated-bg {
        background: radial-gradient(800px circle at 20% 20%, rgba(99,102,241,.10), transparent 40%),
                    radial-gradient(800px circle at 82% 75%, rgba(14,165,233,.10), transparent 40%);
        animation: bgMove 20s ease-in-out infinite alternate;
    }
    .dark #animated-bg {
        background: radial-gradient(800px circle at 20% 20%, rgba(99,102,241,.16), transparent 40%),
                    radial-gradient(800px circle at 82% 75%, rgba(14,165,233,.14), transparent 40%);
    }
    @keyframes bgMove { to { transform: scale(1.12); } }
    @keyframes shake { 0%,100%{transform:translateX(0)} 25%{transform:translateX(-5px)} 75%{transform:translateX(5px)} }
    .shake { animation: shake 0.4s ease-in-out; }

    .nav-item { display: flex; width: 100%; text-align: left; align-items: center; gap: 12px; padding: 10px 14px; font-size: 0.85rem; color: var(--text-muted); border-radius: 8px; transition: all 0.2s; position: relative; }
    .nav-item:hover { color: var(--text-main); background: var(--card-hover); }
    .nav-item.active { color: #4f46e5; background: rgba(99, 102, 241, 0.08); font-weight: 700; }
    .dot { width: 6px; height: 6px; border-radius: 50%; background: #94a3b8; transition: all 0.3s; }
    .dark .dot { background: #334155; }
    .nav-item.active .dot { background: #6366f1; box-shadow: 0 0 8px #6366f1; transform: scale(1.2); }
</style>

<div id="courseRoot" class="relative h-screen bg-adaptive text-adaptive font-sans overflow-hidden flex flex-col selection:bg-indigo-500/30 pt-20 transition-colors duration-500">
    <div class="fixed inset-0 -z-50 pointer-events-none">
        <div id="animated-bg" class="absolute inset-0 opacity-50 transition-opacity"></div>
        <div class="absolute top-[-20%] left-[-10%] w-[1000px] h-[1000px] bg-indigo-500/5 dark:bg-indigo-900/20 rounded-full blur-[150px] animate-pulse transition-colors"></div>
        <div class="absolute bottom-[-20%] right-[-10%] w-[800px] h-[800px] bg-sky-500/5 dark:bg-sky-900/20 rounded-full blur-[100px] transition-colors"></div>
        <canvas id="stars" class="absolute inset-0 pointer-events-none opacity-0 dark:opacity-100 transition-opacity duration-500"></canvas>
        <div id="noise-overlay" class="absolute inset-0 z-10 opacity-[0.02] dark:opacity-[0.03]"></div>
    </div>

    @include('layouts.partials.navbar')

    <div class="flex flex-1 overflow-hidden relative z-20">
        @include('layouts.partials.course-sidebar')

        <main id="mainScroll" class="flex-1 h-full overflow-y-auto scroll-smooth relative bg-transparent custom-scrollbar scroll-padding-top-24">
            <div id="stickyHeader" class="sticky top-0 z-30 w-full backdrop-blur-2xl border-b border-adaptive px-4 md:px-8 py-4 flex items-center justify-between transition-colors duration-500" style="background-color: var(--glass-header);">
                <div class="flex items-center gap-4 min-w-0">
                    <div class="w-8 h-8 rounded-lg bg-indigo-500/10 dark:bg-indigo-500/20 border border-indigo-500/20 flex items-center justify-center font-bold text-xs text-indigo-600 dark:text-indigo-400 transition-colors shrink-0">2.1</div>
                    <div class="min-w-0">
                        <h1 class="text-sm font-bold text-heading transition-colors line-clamp-1">Dasar Layout dan Ruang</h1>
                        <p class="text-[10px] text-muted transition-colors line-clamp-1">Padding, Margin, Gap, dan Kerapian Tampilan</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <div class="hidden sm:block w-24 md:w-32 h-1.5 bg-slate-200 dark:bg-white/10 rounded-full overflow-hidden transition-colors">
                        <div id="topProgressBar" class="h-full bg-gradient-to-r from-indigo-400 to-sky-500 w-0 transition-all duration-500 shadow-[0_0_10px_rgba(99,102,241,0.5)]"></div>
                    </div>
                    <span id="progressLabelTop" class="text-indigo-600 dark:text-indigo-400 font-bold text-xs transition-colors">0%</span>
                </div>
            </div>

            <div class="p-4 sm:p-6 lg:p-16 max-w-5xl mx-auto pb-40">
                <div class="mb-16 md:mb-24">
                    <div class="card-adaptive rounded-3xl border p-6 md:p-8 overflow-hidden relative mb-10">
                        <div class="absolute -right-10 -top-10 w-40 h-40 bg-indigo-500/10 rounded-full blur-3xl"></div>
                        <p class="text-xs font-mono font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-[.25em] mb-3">Subbab 2.1</p>
                        <h2 class="text-3xl md:text-5xl font-black text-heading leading-tight mb-4">Dasar Layout dan Ruang</h2>
                        <p class="text-sm md:text-base text-muted leading-relaxed max-w-3xl text-justify">
                            Pada subbab ini, pembelajar mempelajari konsep layout pada halaman web. Materi dimulai dari susunan umum halaman, penggunaan tag semantik, pengaturan jarak menggunakan padding dan margin, hingga contoh awal layout sederhana dengan utility class Tailwind CSS.
                        </p>
                    </div>

                    <h3 class="text-xl font-bold text-heading mb-6 flex items-center gap-2 transition-colors">
                        <svg class="w-5 h-5 text-indigo-500 dark:text-indigo-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        Tujuan Pembelajaran
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="card-adaptive border border-adaptive p-5 rounded-xl flex items-start gap-4 h-full">
                            <div class="w-9 h-9 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0 font-bold text-sm border border-indigo-200 dark:border-indigo-500/20">1</div>
                            <div><h4 class="text-sm font-bold text-heading mb-1">Susunan Layout</h4><p class="text-xs text-muted leading-relaxed">Menjelaskan fungsi header, navigasi, konten utama, kartu, dan footer pada halaman web.</p></div>
                        </div>
                        <div class="card-adaptive border border-adaptive p-5 rounded-xl flex items-start gap-4 h-full">
                            <div class="w-9 h-9 rounded-lg bg-sky-100 dark:bg-sky-900/30 text-sky-600 dark:text-sky-400 flex items-center justify-center shrink-0 font-bold text-sm border border-sky-200 dark:border-sky-500/20">2</div>
                            <div><h4 class="text-sm font-bold text-heading mb-1">Struktur Semantik</h4><p class="text-xs text-muted leading-relaxed">Menggunakan tag header, nav, main, section, article, dan footer sesuai fungsinya.</p></div>
                        </div>
                        <div class="card-adaptive border border-adaptive p-5 rounded-xl flex items-start gap-4 h-full">
                            <div class="w-9 h-9 rounded-lg bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 flex items-center justify-center shrink-0 font-bold text-sm border border-purple-200 dark:border-purple-500/20">3</div>
                            <div><h4 class="text-sm font-bold text-heading mb-1">Jarak Layout</h4><p class="text-xs text-muted leading-relaxed">Membedakan fungsi padding, margin, dan gap dalam penyusunan layout.</p></div>
                        </div>
                        <div class="card-adaptive border border-adaptive p-5 rounded-xl flex items-start gap-4 h-full">
                            <div class="w-9 h-9 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 font-bold text-sm border border-emerald-200 dark:border-emerald-500/20">4</div>
                            <div><h4 class="text-sm font-bold text-heading mb-1">Layout Tailwind</h4><p class="text-xs text-muted leading-relaxed">Menerapkan class dasar seperti bg-slate-100, p-6, mx-auto, max-w-md, rounded-xl, dan shadow-md.</p></div>
                        </div>
                    </div>
                </div>

                <article class="space-y-24 md:space-y-32">
                    <section id="section-26" class="lesson-section scroll-mt-32" data-lesson-id="26">
                        <div class="space-y-8 md:space-y-10">
                            <div class="space-y-4 border-l-4 border-indigo-500 pl-4 md:pl-6">
                                <span class="text-indigo-600 dark:text-indigo-400 font-mono text-xs uppercase tracking-widest transition-colors">Lesson 2.1.1</span>
                                <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-heading leading-[1.1] transition-colors">
                                    Pengertian Layout <br>
                                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-sky-600 dark:from-indigo-400 dark:to-sky-400">dan Ruang pada Halaman</span>
                                </h2>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-base md:text-lg font-bold text-heading flex items-center gap-2"><span class="w-5 h-5 rounded bg-indigo-500 flex items-center justify-center text-[10px] text-white shrink-0">A</span> Layout sebagai Susunan Informasi</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-sm md:text-base leading-relaxed space-y-4 text-justify transition-colors">
                                    <p>Layout adalah susunan bagian-bagian halaman web. Dalam halaman sederhana, layout membantu menentukan letak judul, menu navigasi, konten utama, kartu informasi, tombol, gambar, dan bagian bawah halaman.</p>
                                    <p>Layout yang baik membuat pengguna lebih mudah memahami isi halaman. Bagian <span class="hl-term">header</span> biasanya berada di atas, <span class="hl-term">navigasi</span> membantu pengguna berpindah halaman, <span class="hl-term">main</span> memuat isi utama, dan <span class="hl-term">footer</span> berada di bagian bawah.</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-base md:text-lg font-bold text-heading flex items-center gap-2"><span class="w-5 h-5 rounded bg-sky-500 flex items-center justify-center text-[10px] text-white shrink-0">B</span> Mengapa Ruang Penting?</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-sm md:text-base leading-relaxed space-y-4 text-justify transition-colors">
                                    <p>Sebelum menulis utility class, susunan halaman perlu dipahami lebih dulu. Jika struktur halaman sudah jelas, pengaturan tampilan dengan Tailwind CSS akan lebih mudah dilakukan.</p>
                                    <p>Contoh susunan umum halaman web dapat dimulai dari <code>&lt;header&gt;</code>, dilanjutkan dengan <code>&lt;nav&gt;</code>, kemudian <code>&lt;main&gt;</code>, dan diakhiri dengan <code>&lt;footer&gt;</code>. Di dalam <code>&lt;main&gt;</code>, konten dapat dikelompokkan menggunakan <code>&lt;section&gt;</code> dan <code>&lt;article&gt;</code>.</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mt-8">
                                <div class="code-adaptive border border-adaptive rounded-2xl p-5 overflow-auto custom-scrollbar">
<pre class="font-mono text-[11px] sm:text-xs leading-relaxed text-slate-700 dark:text-slate-300"><code>&lt;header&gt;Toko Sekolah&lt;/header&gt;
&lt;nav&gt;Beranda | Produk | Kontak&lt;/nav&gt;
&lt;main&gt;
  &lt;section&gt;
    &lt;article&gt;Kartu Produk&lt;/article&gt;
  &lt;/section&gt;
&lt;/main&gt;
&lt;footer&gt;© 2026 Toko Sekolah&lt;/footer&gt;</code></pre>
                                </div>
                                <div class="card-adaptive border border-adaptive rounded-2xl p-5">
                                    <div class="rounded-xl overflow-hidden border border-adaptive text-center text-xs font-bold shadow-sm">
                                        <div class="bg-indigo-100 dark:bg-indigo-500/20 text-indigo-700 dark:text-indigo-300 p-4">Header</div>
                                        <div class="bg-sky-100 dark:bg-sky-500/20 text-sky-700 dark:text-sky-300 p-3">Navigasi</div>
                                        <div class="bg-white dark:bg-slate-900 p-5 text-slate-700 dark:text-slate-300">
                                            <div class="border border-dashed border-adaptive rounded-xl p-4">Main → Section → Article</div>
                                        </div>
                                        <div class="bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 p-3">Footer</div>
                                    </div>
                                    <p class="text-[11px] text-muted leading-relaxed mt-4 text-justify">Struktur ini mengikuti materi modul: setiap bagian halaman memakai tag yang sesuai fungsi kontennya.</p>
                                </div>
                            </div>

                            <div class="sim-bg-adaptive border border-adaptive rounded-xl overflow-hidden shadow-xl p-5 md:p-8 relative transition-colors mt-8">
                                <h4 class="text-xs font-bold text-muted uppercase mb-4 text-center">Simulator: Ruang pada Kartu Layout</h4>
                                <div class="bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-500/30 rounded-lg p-4 mb-8 text-sm text-indigo-800 dark:text-indigo-300">
                                    <p class="font-bold flex items-center gap-2 mb-2">Panduan Simulasi</p>
                                    <p class="m-0 opacity-90 leading-relaxed">Geser slider untuk mengatur jarak dalam kartu. Perhatikan bagaimana kartu yang terlalu rapat berubah menjadi lebih nyaman saat ruang ditambahkan.</p>
                                </div>
                                <div class="grid lg:grid-cols-2 gap-8 items-center">
                                    <div class="space-y-4">
                                        <div class="flex justify-between items-center text-xs font-mono font-bold">
                                            <span class="text-indigo-600 dark:text-indigo-400">Class ruang</span>
                                            <span id="layoutSpaceClass" class="text-heading">p-2</span>
                                        </div>
                                        <input type="range" min="2" max="8" step="2" value="2" oninput="updateLayoutSpace(this.value)" class="w-full accent-indigo-500 cursor-pointer">
                                        <div class="code-adaptive border rounded-xl p-4 text-xs font-mono text-slate-600 dark:text-slate-300 overflow-x-auto custom-scrollbar">
&lt;article class="bg-white rounded-xl <span id="layoutCodeClass" class="text-indigo-600 dark:text-indigo-400 font-bold">p-2</span>"&gt;
  &lt;h3&gt;Produk Pilihan&lt;/h3&gt;
  &lt;p&gt;Sepatu ringan untuk harian.&lt;/p&gt;
  &lt;button&gt;Lihat Detail&lt;/button&gt;
&lt;/article&gt;
                                        </div>
                                    </div>
                                    <div class="bg-slate-100 dark:bg-[#020617] rounded-xl border border-adaptive p-6 flex items-center justify-center min-h-[260px] shadow-inner">
                                        <article id="layoutCardPreview" class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-xl p-2 w-full max-w-xs shadow-sm transition-all duration-300">
                                            <p class="text-[10px] font-bold uppercase tracking-widest text-indigo-500 mb-1">Produk Pilihan</p>
                                            <h3 class="text-lg font-black text-slate-900 dark:text-white leading-tight">Sepatu Kanvas</h3>
                                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Sepatu ringan untuk kegiatan harian dan belajar di kelas.</p>
                                            <button class="mt-3 px-4 py-2 rounded-lg bg-indigo-600 text-white text-xs font-bold">Lihat Detail</button>
                                        </article>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="section-27" class="lesson-section scroll-mt-32" data-lesson-id="27">
                        <div class="space-y-8 md:space-y-10">
                            <div class="space-y-4 border-l-4 border-sky-500 pl-4 md:pl-6">
                                <span class="text-sky-600 dark:text-sky-400 font-mono text-xs uppercase tracking-widest transition-colors">Lesson 2.1.2</span>
                                <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-heading leading-[1.1] transition-colors">
                                    Padding, Margin, <br>
                                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-500 to-cyan-600 dark:from-sky-400 dark:to-cyan-400">dan Gap</span>
                                </h2>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-base md:text-lg font-bold text-heading flex items-center gap-2"><span class="w-5 h-5 rounded bg-sky-500 flex items-center justify-center text-[10px] text-white shrink-0">A</span> Perbedaan Fungsi Ruang</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-sm md:text-base leading-relaxed space-y-4 text-justify transition-colors">
                                    <p><span class="hl-term">Padding</span> adalah ruang di dalam elemen, yaitu jarak antara isi dengan batas elemen. Padding membuat isi kartu atau tombol tidak menempel ke tepi.</p>
                                    <p><span class="hl-term">Margin</span> adalah ruang di luar elemen. Margin digunakan untuk memberi jarak antara satu elemen dengan elemen lain di sekitarnya.</p>
                                    <p><span class="hl-term">Gap</span> adalah jarak antar elemen anak di dalam container yang menggunakan layout seperti flex atau grid. Gap lebih rapi dibanding memberi margin satu per satu pada setiap item.</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-base md:text-lg font-bold text-heading flex items-center gap-2"><span class="w-5 h-5 rounded bg-cyan-500 flex items-center justify-center text-[10px] text-white shrink-0">B</span> Pola Class pada Tailwind</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-sm md:text-base leading-relaxed space-y-4 text-justify transition-colors">
                                    <p>Tailwind menggunakan pola yang mudah dibaca. Class <code>p-4</code> berarti padding semua sisi, <code>px-4</code> berarti padding kiri dan kanan, sedangkan <code>py-4</code> berarti padding atas dan bawah. Pola yang sama juga berlaku pada margin, seperti <code>m-4</code>, <code>mx-4</code>, dan <code>my-4</code>.</p>
                                    <p>Untuk jarak antar item, class <code>gap-4</code> sering digunakan pada container. Saat membuat daftar kartu atau tombol berdampingan, gap menjaga jarak antar item tetap konsisten tanpa perlu menambahkan margin manual pada setiap elemen.</p>
                                </div>
                            </div>

                            <div class="sim-bg-adaptive border border-adaptive rounded-xl overflow-hidden shadow-xl p-5 md:p-8 relative transition-colors mt-8">
                                <h4 class="text-xs font-bold text-muted uppercase mb-4 text-center">Simulator: Padding, Margin, dan Gap</h4>
                                <div class="bg-sky-50 dark:bg-sky-900/20 border border-sky-200 dark:border-sky-500/30 rounded-lg p-4 mb-8 text-sm text-sky-800 dark:text-sky-300">
                                    <p class="font-bold mb-2">Panduan Simulasi</p>
                                    <p class="m-0 opacity-90 leading-relaxed">Pilih jenis ruang yang ingin diterapkan. Area preview akan menampilkan perbedaan padding, margin, dan gap secara visual.</p>
                                </div>
                                <div class="grid lg:grid-cols-2 gap-8 items-stretch">
                                    <div class="space-y-3">
                                        <button onclick="setSpacingType('padding')" class="w-full text-left p-4 rounded-xl border border-adaptive bg-white dark:bg-white/5 hover:border-sky-400 transition">
                                            <span class="font-mono text-sky-600 dark:text-sky-400 text-xs font-bold">p-6</span>
                                            <p class="text-xs text-muted mt-1">Menambah ruang di dalam kartu.</p>
                                        </button>
                                        <button onclick="setSpacingType('margin')" class="w-full text-left p-4 rounded-xl border border-adaptive bg-white dark:bg-white/5 hover:border-sky-400 transition">
                                            <span class="font-mono text-purple-600 dark:text-purple-400 text-xs font-bold">m-6</span>
                                            <p class="text-xs text-muted mt-1">Menambah ruang di luar kartu.</p>
                                        </button>
                                        <button onclick="setSpacingType('gap')" class="w-full text-left p-4 rounded-xl border border-adaptive bg-white dark:bg-white/5 hover:border-sky-400 transition">
                                            <span class="font-mono text-emerald-600 dark:text-emerald-400 text-xs font-bold">gap-6</span>
                                            <p class="text-xs text-muted mt-1">Menambah ruang antar item dalam container.</p>
                                        </button>
                                        <div id="spacingExplanation" class="code-adaptive border rounded-xl p-4 text-xs leading-relaxed text-slate-600 dark:text-slate-300">Padding membuat isi elemen tidak menempel ke tepi.</div>
                                    </div>
                                    <div class="bg-slate-100 dark:bg-[#020617] border border-adaptive rounded-xl p-6 min-h-[300px] shadow-inner overflow-hidden">
                                        <div id="spacingOuter" class="bg-indigo-100 dark:bg-indigo-500/10 border-2 border-dashed border-indigo-300 dark:border-indigo-500/30 rounded-xl transition-all duration-300">
                                            <div id="spacingContainer" class="bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-white/10 transition-all duration-300 p-6">
                                                <div id="spacingItems" class="flex gap-2 transition-all duration-300">
                                                    <div class="h-14 flex-1 rounded-lg bg-sky-500 text-white flex items-center justify-center text-xs font-bold">A</div>
                                                    <div class="h-14 flex-1 rounded-lg bg-indigo-500 text-white flex items-center justify-center text-xs font-bold">B</div>
                                                    <div class="h-14 flex-1 rounded-lg bg-purple-500 text-white flex items-center justify-center text-xs font-bold">C</div>
                                                </div>
                                            </div>
                                        </div>
                                        <p id="spacingLabel" class="text-[10px] text-center text-muted mt-4 font-mono">class aktif: p-6</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="section-28" class="lesson-section scroll-mt-32" data-lesson-id="28">
                        <div class="space-y-8 md:space-y-10">
                            <div class="space-y-4 border-l-4 border-purple-500 pl-4 md:pl-6">
                                <span class="text-purple-600 dark:text-purple-400 font-mono text-xs uppercase tracking-widest transition-colors">Lesson 2.1.3</span>
                                <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-heading leading-[1.1] transition-colors">
                                    Layout Rapat <br>
                                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-500 to-indigo-600 dark:from-purple-400 dark:to-indigo-400">dan Layout Rapi</span>
                                </h2>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-base md:text-lg font-bold text-heading flex items-center gap-2"><span class="w-5 h-5 rounded bg-purple-500 flex items-center justify-center text-[10px] text-white shrink-0">A</span> Membaca Masalah Visual</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-sm md:text-base leading-relaxed space-y-4 text-justify transition-colors">
                                    <p>Layout yang kurang nyaman biasanya memiliki tanda yang mudah dikenali. Teks terlalu dekat dengan tepi, tombol terlalu menempel dengan paragraf, dan antar kartu tidak memiliki jarak yang jelas. Masalah seperti ini membuat tampilan terlihat penuh walaupun isi halamannya sedikit.</p>
                                    <p>Perbaikan pertama tidak selalu harus mengganti warna atau menambah efek visual. Sering kali, cukup dengan menambah padding pada kartu, margin antar bagian, dan gap antar item, tampilan langsung menjadi lebih teratur.</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-base md:text-lg font-bold text-heading flex items-center gap-2"><span class="w-5 h-5 rounded bg-indigo-500 flex items-center justify-center text-[10px] text-white shrink-0">B</span> Prinsip Sederhana Mengatur Ruang</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-sm md:text-base leading-relaxed space-y-4 text-justify transition-colors">
                                    <p>Gunakan ruang kecil untuk elemen yang masih satu kelompok, seperti ikon dan teks. Gunakan ruang sedang untuk memisahkan paragraf dan tombol. Gunakan ruang besar untuk memisahkan satu section dengan section lain.</p>
                                    <p>Dalam modul ini, pembelajar tidak dituntut menghafal seluruh class spacing. Yang penting adalah memahami fungsi ruang dan mampu memilih class yang masuk akal sesuai kebutuhan tampilan.</p>
                                </div>
                            </div>

                            <div class="sim-bg-adaptive border border-adaptive rounded-xl overflow-hidden shadow-xl p-5 md:p-8 relative transition-colors mt-8">
                                <h4 class="text-xs font-bold text-muted uppercase mb-4 text-center">Simulator: Perbaiki Kartu Informasi</h4>
                                <div class="bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-500/30 rounded-lg p-4 mb-8 text-sm text-purple-800 dark:text-purple-300">
                                    <p class="font-bold mb-2">Panduan Simulasi</p>
                                    <p class="m-0 opacity-90 leading-relaxed">Aktifkan beberapa pilihan perbaikan. Preview akan menunjukkan bagaimana kombinasi ruang membuat kartu lebih rapi.</p>
                                </div>
                                <div class="grid lg:grid-cols-2 gap-8 items-stretch">
                                    <div class="space-y-3">
                                        <label class="flex items-center justify-between gap-4 p-4 rounded-xl border border-adaptive bg-white dark:bg-white/5 cursor-pointer">
                                            <span><span class="block text-xs font-mono font-bold text-purple-600 dark:text-purple-400">p-6</span><span class="text-xs text-muted">Ruang dalam kartu</span></span>
                                            <input type="checkbox" checked onchange="updateCardFix()" id="fixPadding" class="accent-purple-500 w-4 h-4">
                                        </label>
                                        <label class="flex items-center justify-between gap-4 p-4 rounded-xl border border-adaptive bg-white dark:bg-white/5 cursor-pointer">
                                            <span><span class="block text-xs font-mono font-bold text-indigo-600 dark:text-indigo-400">space-y-3</span><span class="text-xs text-muted">Jarak antar teks</span></span>
                                            <input type="checkbox" onchange="updateCardFix()" id="fixSpace" class="accent-indigo-500 w-4 h-4">
                                        </label>
                                        <label class="flex items-center justify-between gap-4 p-4 rounded-xl border border-adaptive bg-white dark:bg-white/5 cursor-pointer">
                                            <span><span class="block text-xs font-mono font-bold text-sky-600 dark:text-sky-400">mt-4</span><span class="text-xs text-muted">Jarak tombol dari paragraf</span></span>
                                            <input type="checkbox" onchange="updateCardFix()" id="fixButton" class="accent-sky-500 w-4 h-4">
                                        </label>
                                        <div id="fixClassOutput" class="code-adaptive border rounded-xl p-4 text-xs font-mono text-slate-600 dark:text-slate-300">class="bg-white rounded-xl p-6"</div>
                                    </div>
                                    <div class="bg-slate-100 dark:bg-[#020617] rounded-xl border border-adaptive p-6 flex items-center justify-center shadow-inner">
                                        <article id="fixCard" class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-white/10 w-full max-w-sm p-6 transition-all duration-300 shadow-sm">
                                            <div id="fixCardInner" class="transition-all duration-300">
                                                <p class="text-[10px] uppercase tracking-widest font-bold text-indigo-500">Informasi</p>
                                                <h3 class="text-xl font-black text-slate-900 dark:text-white">Belajar Layout</h3>
                                                <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">Ruang yang tepat membuat konten lebih mudah dipahami dan tampilan lebih nyaman.</p>
                                                <button id="fixButtonPreview" class="px-4 py-2 rounded-lg bg-indigo-600 text-white text-xs font-bold">Mulai Latihan</button>
                                            </div>
                                        </article>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="section-29" class="lesson-section scroll-mt-32" data-lesson-id="29">
                        <div class="space-y-8 md:space-y-10">
                            <div class="space-y-4 border-l-4 border-emerald-500 pl-4 md:pl-6">
                                <span class="text-emerald-600 dark:text-emerald-400 font-mono text-xs uppercase tracking-widest transition-colors">Lesson 2.1.4</span>
                                <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-heading leading-[1.1] transition-colors">
                                    Alur Membuat <br>
                                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-500 to-sky-600 dark:from-emerald-400 dark:to-sky-400">Layout Sederhana</span>
                                </h2>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-base md:text-lg font-bold text-heading flex items-center gap-2"><span class="w-5 h-5 rounded bg-emerald-500 flex items-center justify-center text-[10px] text-white shrink-0">A</span> Mulai dari Container</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-sm md:text-base leading-relaxed space-y-4 text-justify transition-colors">
                                    <p>Sebelum mengatur isi, tentukan terlebih dahulu container utama. Container berperan sebagai pembungkus agar elemen tidak melebar tanpa batas. Pada Tailwind, container sederhana dapat dibuat dengan kombinasi <code>max-w</code>, <code>mx-auto</code>, dan <code>p</code>.</p>
                                    <p>Setelah container siap, barulah bagian isi dibuat berurutan. Misalnya halaman produk dapat dimulai dari judul, deskripsi singkat, daftar kartu, lalu tombol aksi. Setiap bagian diberi jarak agar halaman tidak terlihat sesak.</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-base md:text-lg font-bold text-heading flex items-center gap-2"><span class="w-5 h-5 rounded bg-sky-500 flex items-center justify-center text-[10px] text-white shrink-0">B</span> Class Dasar yang Sering Digunakan</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-sm md:text-base leading-relaxed space-y-4 text-justify transition-colors">
                                    <p>Class <code>max-w-4xl</code> membatasi lebar konten agar tidak terlalu melebar di layar besar. Class <code>mx-auto</code> membuat container berada di tengah. Class <code>p-6</code> memberi ruang di dalam container, sedangkan <code>space-y-6</code> memberi jarak vertikal antar elemen yang berada di dalamnya.</p>
                                    <p>Pola ini sederhana, tetapi sangat sering dipakai dalam pembuatan halaman web. Dengan memahami pola container dan ruang, pembelajar akan lebih siap memasuki subbab Flexbox, Grid, dan Responsif.</p>
                                </div>
                            </div>

                            <div class="code-adaptive border border-adaptive rounded-2xl p-5 overflow-auto custom-scrollbar mt-6">
                                <div class="text-[10px] uppercase tracking-widest text-emerald-600 dark:text-emerald-400 font-bold mb-3">Contoh kode layout sederhana dari modul</div>
<pre class="font-mono text-[11px] sm:text-xs leading-relaxed text-slate-700 dark:text-slate-300"><code>&lt;body class="bg-slate-100 p-6"&gt;
  &lt;main class="mx-auto max-w-md"&gt;
    &lt;section class="rounded-xl bg-white p-6 shadow-md"&gt;
      &lt;h1 class="text-2xl font-bold text-slate-900"&gt;Produk Pilihan&lt;/h1&gt;
      &lt;p class="mt-2 text-slate-600"&gt;Daftar produk yang tersedia minggu ini.&lt;/p&gt;
      &lt;article class="mt-4 rounded-lg border p-4"&gt;
        &lt;h2 class="font-semibold text-slate-900"&gt;Sepatu Kanvas&lt;/h2&gt;
        &lt;p class="mt-2 text-slate-600"&gt;Sepatu ringan untuk kegiatan harian.&lt;/p&gt;
      &lt;/article&gt;
    &lt;/section&gt;
  &lt;/main&gt;
&lt;/body&gt;</code></pre>
                            </div>

                            <div class="sim-bg-adaptive border border-adaptive rounded-xl overflow-hidden shadow-xl p-5 md:p-8 relative transition-colors mt-8">
                                <h4 class="text-xs font-bold text-muted uppercase mb-4 text-center">Simulator: Susun Container Halaman</h4>
                                <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-500/30 rounded-lg p-4 mb-8 text-sm text-emerald-800 dark:text-emerald-300">
                                    <p class="font-bold mb-2">Panduan Simulasi</p>
                                    <p class="m-0 opacity-90 leading-relaxed">Pilih ukuran container dan jarak vertikal. Preview akan memperlihatkan bagaimana area halaman menjadi lebih terkontrol.</p>
                                </div>
                                <div class="grid lg:grid-cols-2 gap-8 items-stretch">
                                    <div class="space-y-4">
                                        <div>
                                            <label class="text-[10px] uppercase tracking-widest font-bold text-muted block mb-2">Lebar Container</label>
                                            <select id="containerWidth" onchange="updateContainerBuilder()" class="w-full rounded-xl border border-adaptive bg-white dark:bg-slate-900 p-3 text-sm text-heading outline-none">
                                                <option value="max-w-xl">max-w-xl</option>
                                                <option value="max-w-2xl" selected>max-w-2xl</option>
                                                <option value="max-w-4xl">max-w-4xl</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="text-[10px] uppercase tracking-widest font-bold text-muted block mb-2">Jarak Vertikal</label>
                                            <select id="containerSpace" onchange="updateContainerBuilder()" class="w-full rounded-xl border border-adaptive bg-white dark:bg-slate-900 p-3 text-sm text-heading outline-none">
                                                <option value="space-y-2">space-y-2</option>
                                                <option value="space-y-4" selected>space-y-4</option>
                                                <option value="space-y-6">space-y-6</option>
                                            </select>
                                        </div>
                                        <div id="containerCode" class="code-adaptive border rounded-xl p-4 text-xs font-mono text-slate-600 dark:text-slate-300 overflow-x-auto custom-scrollbar">class="max-w-2xl mx-auto p-6 space-y-4"</div>
                                    </div>
                                    <div class="bg-slate-100 dark:bg-[#020617] rounded-xl border border-adaptive p-4 min-h-[320px] shadow-inner overflow-hidden">
                                        <div id="containerPreview" class="max-w-2xl mx-auto bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-xl p-6 space-y-4 transition-all duration-300">
                                            <div class="h-10 rounded-lg bg-indigo-500/90 flex items-center px-4 text-white text-xs font-bold">Judul Halaman</div>
                                            <div class="h-16 rounded-lg bg-sky-500/80 flex items-center px-4 text-white text-xs font-bold">Deskripsi Konten</div>
                                            <div class="grid grid-cols-2 gap-3">
                                                <div class="h-20 rounded-lg bg-purple-500/80 flex items-center justify-center text-white text-xs font-bold">Kartu 1</div>
                                                <div class="h-20 rounded-lg bg-emerald-500/80 flex items-center justify-center text-white text-xs font-bold">Kartu 2</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="section-30" class="lesson-section scroll-mt-32 pt-10 border-t border-adaptive transition-colors" data-lesson-id="30" data-type="activity">
                        <div class="relative rounded-[2rem] md:rounded-[2.5rem] sim-bg-adaptive border border-adaptive p-5 sm:p-6 md:p-10 overflow-hidden shadow-xl group hover:border-indigo-500/30 transition-all duration-500">
                            <div class="absolute -top-24 -right-24 w-64 h-64 bg-indigo-600/10 dark:bg-indigo-600/20 blur-[100px] rounded-full pointer-events-none transition-colors"></div>
                            <div class="flex flex-col md:flex-row items-start md:items-center gap-5 mb-8 relative z-10">
                                <div class="p-4 bg-gradient-to-br from-indigo-600 to-sky-700 rounded-2xl text-white shadow-lg shadow-indigo-500/20 shrink-0">
                                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z"/></svg>
                                </div>
                                <div>
                                    <h2 class="text-2xl md:text-3xl font-black text-heading tracking-tight transition-colors">Aktivitas: Pilih Utility Ruang</h2>
                                    <p class="text-slate-600 dark:text-indigo-200/70 text-sm leading-relaxed max-w-2xl transition-colors mt-2 text-justify">Pilih class Tailwind yang paling tepat untuk memperbaiki masalah ruang pada beberapa kasus layout sederhana!</p>
                                </div>
                            </div>

                            <div id="activityPanel" class="grid lg:grid-cols-2 gap-6 relative z-10">
                                <div class="code-adaptive border border-adaptive rounded-2xl p-5 md:p-6 min-h-[360px] flex flex-col">
                                    <div class="flex justify-between items-center mb-5">
                                        <span id="activityStep" class="text-[10px] uppercase tracking-widest font-bold text-indigo-600 dark:text-indigo-400">Soal 1 dari 5</span>
                                        <span id="activityScore" class="text-[10px] font-mono font-bold text-muted">Skor: 0</span>
                                    </div>
                                    <h3 id="activityQuestion" class="text-lg md:text-xl font-black text-heading leading-snug mb-3">Pertanyaan</h3>
                                    <p id="activityScenario" class="text-sm text-muted leading-relaxed mb-6">Skenario</p>
                                    <div id="activityOptions" class="space-y-3"></div>
                                    <div id="activityFeedback" class="mt-5 min-h-[70px] text-sm leading-relaxed rounded-xl border border-dashed border-adaptive p-4 text-muted">Pilih salah satu jawaban!</div>
                                    <button id="nextActivityBtn" onclick="nextActivityQuestion()" disabled class="mt-auto w-full py-3 rounded-xl bg-indigo-600 text-white text-xs font-bold opacity-50 cursor-not-allowed transition-all">Lanjut</button>
                                </div>
                                <div class="bg-slate-100 dark:bg-[#020617] border border-adaptive rounded-2xl p-6 min-h-[360px] flex items-center justify-center shadow-inner">
                                    <div id="activityPreview" class="w-full max-w-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-xl shadow-sm transition-all duration-300">
                                        <p class="text-[10px] uppercase tracking-widest font-bold text-indigo-500">Preview</p>
                                        <h3 class="font-black text-slate-900 dark:text-white text-xl">Kartu Informasi</h3>
                                        <p class="text-sm text-slate-500 dark:text-slate-400">Tampilan akan berubah sesuai jawaban yang dipilih.</p>
                                        <button class="bg-indigo-600 text-white text-xs font-bold rounded-lg px-4 py-2">Aksi</button>
                                    </div>
                                </div>
                            </div>

                            <div id="activityDone" class="hidden relative z-10 text-center p-8 rounded-2xl border border-emerald-300 dark:border-emerald-500/30 bg-emerald-50 dark:bg-emerald-500/10">
                                <div class="w-16 h-16 rounded-full bg-emerald-500 text-white mx-auto flex items-center justify-center text-3xl font-black mb-4">✓</div>
                                <h3 class="text-2xl font-black text-emerald-700 dark:text-emerald-300 mb-2">Aktivitas Selesai</h3>
                                <p class="text-sm text-emerald-700/80 dark:text-emerald-200/80">Progress subbab 2.1 berhasil disimpan. Tombol lanjut sudah terbuka.</p>
                            </div>
                        </div>
                    </section>
                </article>

                <div class="mt-20 md:mt-32 pt-8 border-t border-adaptive flex flex-col sm:flex-row justify-between items-center gap-4 sm:gap-0 transition-colors">
                    <a href="{{ \Illuminate\Support\Facades\Route::has('quiz.intro') ? route('quiz.intro', ['chapterId' => 1]) : '#' }}" class="group flex items-center gap-4 text-muted hover:text-heading transition-colors w-full sm:w-auto justify-center sm:justify-start">
                        <div class="w-10 h-10 md:w-12 md:h-12 rounded-full border border-adaptive flex items-center justify-center group-hover:bg-slate-100 dark:group-hover:bg-white/5 transition-colors shrink-0">
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        </div>
                        <div class="text-left"><div class="text-[10px] uppercase tracking-widest font-bold opacity-60">Sebelumnya</div><div class="font-black text-xs md:text-sm line-clamp-1">Evaluasi Bab 1</div></div>
                    </a>

                    <div id="nextChapterBtn" class="group flex items-center gap-4 text-right text-muted cursor-not-allowed opacity-50 pointer-events-none transition-all duration-500 w-full sm:w-auto justify-center sm:justify-end">
                        <div class="text-right"><div id="nextLabel" class="text-[10px] uppercase tracking-widest font-bold opacity-60">Terkunci</div><div class="font-black text-xs md:text-sm line-clamp-1">Flexbox</div></div>
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
    window.LESSON_IDS = [26, 27, 28, 29, 30];
    let rawCompletedIds = {!! json_encode($completedLessonIds ?? []) !!};
    window.COMPLETED_IDS = rawCompletedIds.map(id => Number(id));
    let completedSet = new Set(window.COMPLETED_IDS);
    const ACTIVITY_LESSON_ID = 30;
    let activityCompleted = {!! ($activityCompleted ?? false) ? 'true' : 'false' !!};

    document.addEventListener('DOMContentLoaded', () => {
        if (typeof initSidebarScroll === 'function') initSidebarScroll();
        if (typeof initVisualEffects === 'function') initVisualEffects();
        updateProgressUI(false);
        updateLayoutSpace(2);
        setSpacingType('padding');
        updateCardFix();
        updateContainerBuilder();
        renderActivityQuestion();

        if (activityCompleted) {
            lockActivityUI();
            unlockNextChapter();
        }

        initMasterObserver();
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
        if (!animate) bar.style.transition = 'none';
        bar.style.width = percent + '%';
        if (!animate) setTimeout(() => bar.style.transition = 'all 0.5s', 50);
        label.innerText = percent + '%';
        if (percent === 100 && activityCompleted) unlockNextChapter();
    }

    function markSidebarDone(lessonId) {
        const navItem = document.querySelector(`.nav-item[data-target="#section-${lessonId}"]`);
        if (navItem) {
            const dot = navItem.querySelector('.dot');
            if (dot) {
                dot.outerHTML = `<svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>`;
            }
        }
    }

    async function saveLessonToDB(lessonId) {
        lessonId = Number(lessonId);
        if (completedSet.has(lessonId)) return true;
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
                return true;
            }
        } catch (e) { console.error('Network Error:', e); }
        return false;
    }

    function initMasterObserver() {
        const mainScroll = document.getElementById('mainScroll');
        const sections = document.querySelectorAll('.lesson-section');
        if (!mainScroll || !sections.length) return;
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (!entry.isIntersecting) return;
                const targetId = entry.target.id;
                const lessonId = Number(entry.target.dataset.lessonId);
                const isActivity = entry.target.dataset.type === 'activity';
                if (typeof highlightAnchor === 'function') highlightAnchor(targetId);
                if (lessonId && !isActivity && !completedSet.has(lessonId)) saveLessonToDB(lessonId);
            });
        }, { root: mainScroll, rootMargin: '-10% 0px -60% 0px', threshold: 0 });
        sections.forEach(section => observer.observe(section));
    }

    function updateLayoutSpace(value) {
        const preview = document.getElementById('layoutCardPreview');
        const label = document.getElementById('layoutSpaceClass');
        const code = document.getElementById('layoutCodeClass');
        if (!preview) return;
        ['p-2','p-4','p-6','p-8'].forEach(c => preview.classList.remove(c));
        preview.classList.add(`p-${value}`);
        if (label) label.textContent = `p-${value}`;
        if (code) code.textContent = `p-${value}`;
    }

    function setSpacingType(type) {
        const outer = document.getElementById('spacingOuter');
        const container = document.getElementById('spacingContainer');
        const items = document.getElementById('spacingItems');
        const label = document.getElementById('spacingLabel');
        const explanation = document.getElementById('spacingExplanation');
        if (!outer || !container || !items) return;
        outer.className = 'bg-indigo-100 dark:bg-indigo-500/10 border-2 border-dashed border-indigo-300 dark:border-indigo-500/30 rounded-xl transition-all duration-300';
        container.className = 'bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-white/10 transition-all duration-300 p-3';
        items.className = 'flex gap-2 transition-all duration-300';

        if (type === 'padding') {
            container.classList.remove('p-3'); container.classList.add('p-8');
            label.textContent = 'class aktif: p-6';
            explanation.textContent = 'Padding membuat isi elemen tidak menempel ke tepi kartu.';
        }
        if (type === 'margin') {
            outer.classList.add('p-8');
            label.textContent = 'class aktif: m-6';
            explanation.textContent = 'Margin membuat jarak di luar elemen, sehingga elemen tidak menempel dengan area sekitarnya.';
        }
        if (type === 'gap') {
            items.classList.remove('gap-2'); items.classList.add('gap-6');
            label.textContent = 'class aktif: gap-6';
            explanation.textContent = 'Gap membuat jarak antar item di dalam container secara konsisten.';
        }
    }

    function updateCardFix() {
        const card = document.getElementById('fixCard');
        const inner = document.getElementById('fixCardInner');
        const btn = document.getElementById('fixButtonPreview');
        const output = document.getElementById('fixClassOutput');
        const pad = document.getElementById('fixPadding')?.checked;
        const space = document.getElementById('fixSpace')?.checked;
        const button = document.getElementById('fixButton')?.checked;
        if (!card || !inner || !btn) return;
        ['p-1','p-6'].forEach(c => card.classList.remove(c));
        card.classList.add(pad ? 'p-6' : 'p-1');
        inner.className = `transition-all duration-300 ${space ? 'space-y-3' : 'space-y-0'}`;
        btn.className = `px-4 py-2 rounded-lg bg-indigo-600 text-white text-xs font-bold ${button ? 'mt-4' : 'mt-0'}`;
        const classes = ['bg-white', 'rounded-xl'];
        classes.push(pad ? 'p-6' : 'p-1');
        if (space) classes.push('space-y-3');
        if (button) classes.push('button: mt-4');
        if (output) output.textContent = `class="${classes.join(' ')}"`;
    }

    function updateContainerBuilder() {
        const width = document.getElementById('containerWidth')?.value || 'max-w-2xl';
        const space = document.getElementById('containerSpace')?.value || 'space-y-4';
        const preview = document.getElementById('containerPreview');
        const code = document.getElementById('containerCode');
        if (!preview) return;
        ['max-w-xl','max-w-2xl','max-w-4xl','space-y-2','space-y-4','space-y-6'].forEach(c => preview.classList.remove(c));
        preview.classList.add(width, space);
        if (code) code.textContent = `class="${width} mx-auto p-6 ${space}"`;
    }

    const activityQuestions = [
        {
            q: 'Bagian halaman yang paling tepat untuk menampilkan nama toko atau judul situs adalah...',
            s: 'Sebuah halaman toko sederhana memiliki bagian atas yang berisi identitas halaman.',
            options: ['header', 'footer', 'article', 'input'], answer: 'header',
            preview: 'header'
        },
        {
            q: 'Kartu produk seperti Sepatu Kanvas paling tepat ditulis menggunakan tag...',
            s: 'Kartu produk merupakan konten mandiri di dalam section produk pilihan.',
            options: ['article', 'nav', 'footer', 'meta'], answer: 'article',
            preview: 'article'
        },
        {
            q: 'Teks di dalam kartu terlalu menempel ke tepi. Class Tailwind yang paling sesuai adalah...',
            s: 'Masalah terjadi pada ruang bagian dalam kartu, bukan jarak luar antar kartu.',
            options: ['p-6', 'mx-auto', 'text-2xl', 'bg-slate-100'], answer: 'p-6',
            preview: 'p-6'
        },
        {
            q: 'Kartu produk perlu diberi jarak dari paragraf sebelumnya. Class yang paling tepat adalah...',
            s: 'Pada contoh modul, article kartu produk diberi jarak dari paragraf di atasnya.',
            options: ['mt-4', 'p-0', 'font-bold', 'rounded-xl'], answer: 'mt-4',
            preview: 'mt-4'
        },
        {
            q: 'Kombinasi untuk membatasi lebar konten dan menempatkannya di tengah adalah...',
            s: 'Pada contoh layout sederhana, main perlu berada di tengah dan tidak melebar terlalu jauh.',
            options: ['mx-auto max-w-md', 'bg-white text-white', 'rounded-xl shadow-md', 'p-6 mt-2'], answer: 'mx-auto max-w-md',
            preview: 'mx-auto max-w-md'
        }
    ]
    let activityIndex = 0;
    let activityScore = 0;
    let answered = false;

    function renderActivityQuestion() {
        if (activityCompleted) return;
        const item = activityQuestions[activityIndex];
        document.getElementById('activityStep').textContent = `Soal ${activityIndex + 1} dari ${activityQuestions.length}`;
        document.getElementById('activityScore').textContent = `Skor: ${activityScore}`;
        document.getElementById('activityQuestion').textContent = item.q;
        document.getElementById('activityScenario').textContent = item.s;
        document.getElementById('activityFeedback').textContent = 'Pilih salah satu jawaban!';
        document.getElementById('activityFeedback').className = 'mt-5 min-h-[70px] text-sm leading-relaxed rounded-xl border border-dashed border-adaptive p-4 text-muted';
        const options = document.getElementById('activityOptions');
        options.innerHTML = '';
        item.options.forEach(option => {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'w-full text-left p-4 rounded-xl border border-adaptive bg-white dark:bg-white/5 hover:border-indigo-400 transition text-sm font-mono text-heading';
            button.textContent = option;
            button.onclick = () => answerActivity(option, button);
            options.appendChild(button);
        });
        const next = document.getElementById('nextActivityBtn');
        next.disabled = true;
        next.classList.add('opacity-50', 'cursor-not-allowed');
        next.textContent = activityIndex === activityQuestions.length - 1 ? 'Selesaikan Aktivitas' : 'Lanjut';
        answered = false;
        updateActivityPreview('default');
    }

    function answerActivity(option, button) {
        if (answered || activityCompleted) return;
        answered = true;
        const item = activityQuestions[activityIndex];
        const correct = option === item.answer;
        if (correct) activityScore++;
        document.querySelectorAll('#activityOptions button').forEach(btn => {
            btn.disabled = true;
            if (correct && btn.textContent === item.answer) btn.classList.add('border-emerald-400', 'bg-emerald-50', 'dark:bg-emerald-500/10');
        });
        if (!correct) button.classList.add('border-rose-400', 'bg-rose-50', 'dark:bg-rose-500/10', 'shake');
        const feedback = document.getElementById('activityFeedback');
        feedback.className = `mt-5 min-h-[70px] text-sm leading-relaxed rounded-xl border p-4 ${correct ? 'border-emerald-300 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-300' : 'border-rose-300 bg-rose-50 dark:bg-rose-500/10 text-rose-700 dark:text-rose-300'}`;
        feedback.textContent = correct
            ? 'Pilihan diterima. Preview diperbarui sesuai pilihan Anda.'
            : 'Belum tepat. Lanjutkan aktivitas, lalu ulangi bila skor belum cukup.';
        document.getElementById('activityScore').textContent = `Skor: ${activityScore}`;
        updateActivityPreview(item.preview);
        const next = document.getElementById('nextActivityBtn');
        next.disabled = false;
        next.classList.remove('opacity-50', 'cursor-not-allowed');
    }

    function updateActivityPreview(type) {
        const card = document.getElementById('activityPreview');
        if (!card) return;
        card.className = 'w-full max-w-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-xl shadow-sm transition-all duration-300';
        if (type === 'p-6') card.classList.add('p-6'); else card.classList.add('p-3');
        if (type === 'article' || type === 'mt-4') card.classList.add('mt-4');
        if (type === 'header') card.classList.add('border-indigo-400');
        if (type === 'mx-auto max-w-md') card.classList.add('mx-auto');
        card.classList.add('space-y-2');
    }

    async function nextActivityQuestion() {
        if (!answered) return;
        if (activityIndex < activityQuestions.length - 1) {
            activityIndex++;
            renderActivityQuestion();
            return;
        }
        if (activityScore >= 4) {
            const btn = document.getElementById('nextActivityBtn');
            btn.textContent = 'Menyimpan progress...';
            btn.disabled = true;
            const saved = await saveLessonToDB(ACTIVITY_LESSON_ID);
            if (saved) {
                activityCompleted = true;
                lockActivityUI();
                unlockNextChapter();
            } else {
                btn.textContent = 'Gagal menyimpan. Coba lagi.';
                btn.disabled = false;
            }
        } else {
            const feedback = document.getElementById('activityFeedback');
            feedback.className = 'mt-5 min-h-[70px] text-sm leading-relaxed rounded-xl border border-amber-300 bg-amber-50 dark:bg-amber-500/10 p-4 text-amber-700 dark:text-amber-300';
            feedback.textContent = `Skor Anda ${activityScore}/5. Minimal benar 4 soal. Ulangi aktivitas setelah meninjau materi subbab ini.`;
            activityIndex = 0;
            activityScore = 0;
            setTimeout(renderActivityQuestion, 1200);
        }
    }

    function lockActivityUI() {
        document.getElementById('activityPanel')?.classList.add('hidden');
        document.getElementById('activityDone')?.classList.remove('hidden');
    }

    function unlockNextChapter() {
        const btn = document.getElementById('nextChapterBtn');
        if (!btn) return;
        btn.classList.remove('cursor-not-allowed', 'opacity-50', 'pointer-events-none', 'text-muted');
        btn.classList.add('text-indigo-600', 'dark:text-indigo-400', 'cursor-pointer');
        document.getElementById('nextLabel').innerText = 'Selanjutnya';
        document.getElementById('nextLabel').classList.remove('opacity-60');
        document.getElementById('nextLabel').classList.add('text-indigo-600', 'dark:text-indigo-400', 'opacity-100');
        const icon = document.getElementById('nextIcon');
        icon.innerHTML = `<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>`;
        icon.classList.remove('bg-slate-100', 'dark:bg-white/5');
        icon.classList.add('bg-indigo-100', 'dark:bg-indigo-500/20', 'border-indigo-300', 'dark:border-indigo-500/50', 'text-indigo-600', 'dark:text-indigo-400', 'shadow-lg');
        btn.onclick = () => window.location.href = "{{ \Illuminate\Support\Facades\Route::has('courses.flexbox') ? route('courses.flexbox') : '#' }}";
    }
</script>
@endsection
