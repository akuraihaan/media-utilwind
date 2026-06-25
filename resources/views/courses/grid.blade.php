@extends('layouts.landing')
@section('title','Flexbox')

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
        --glass-header: rgba(255, 255, 255, 0.86);
        --card-bg: #ffffff;
        --card-hover: rgba(0, 0, 0, 0.025);
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
        --glass-border: rgba(255, 255, 255, 0.06);
        --glass-header: rgba(2, 6, 23, 0.82);
        --card-bg: #111827;
        --card-hover: rgba(255, 255, 255, 0.025);
        --border-color: rgba(255, 255, 255, 0.10);
        --text-muted: rgba(226, 232, 240, 0.58);
        --text-heading: #ffffff;
        --code-bg: #111827;
        --simulator-bg: #0b1120;
        --accent-glow: rgba(99, 102, 241, 0.55);
    }

    body { font-family: 'Inter', sans-serif; background-color: var(--bg-main); color: var(--text-main); transition: background-color .4s, color .4s; }
    .font-mono { font-family: 'JetBrains Mono', monospace; }
    .bg-adaptive { background-color: var(--bg-main); }
    .text-adaptive { color: var(--text-main); }
    .text-heading { color: var(--text-heading); }
    .text-muted { color: var(--text-muted); }
    .border-adaptive { border-color: var(--border-color); }
    .card-adaptive { background-color: var(--card-bg); border-color: var(--glass-border); transition: all .3s; }
    .card-adaptive:hover { border-color: var(--accent-glow); }
    .sim-bg-adaptive { background-color: var(--simulator-bg); }
    .code-adaptive { background-color: var(--code-bg); border-color: var(--glass-border); }

    .hl-term {
        background-color: rgba(99, 102, 241, .14);
        color: #4f46e5;
        padding: .125rem .375rem;
        border-radius: .375rem;
        font-weight: 700;
        white-space: nowrap;
        border: 1px solid rgba(99, 102, 241, .25);
    }
    .dark .hl-term { color:#a5b4fc; background-color:rgba(99,102,241,.18); border-color:rgba(129,140,248,.30); }

    .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(150,150,150,.3); border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: var(--accent); }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }

    #animated-bg { 
        background: radial-gradient(800px circle at 20% 20%, rgba(99,102,241,.10), transparent 40%),
                    radial-gradient(800px circle at 80% 80%, rgba(168,85,247,.10), transparent 40%);
        animation: bgMove 20s ease-in-out infinite alternate; 
    }
    .dark #animated-bg {
        background: radial-gradient(800px circle at 20% 20%, rgba(99,102,241,.16), transparent 40%),
                    radial-gradient(800px circle at 80% 80%, rgba(168,85,247,.16), transparent 40%);
    }
    @keyframes bgMove { to { transform: scale(1.15); } }
    @keyframes shake { 0%,100%{transform:translateX(0)} 25%{transform:translateX(-5px)} 75%{transform:translateX(5px)} }
    .shake { animation: shake .35s ease-in-out; }

    .nav-item { display:flex; width:100%; text-align:left; align-items:center; gap:12px; padding:10px 14px; font-size:.85rem; color:var(--text-muted); border-radius:8px; transition:all .2s; position:relative; }
    .nav-item:hover { color:var(--text-main); background:var(--card-hover); }
    .nav-item.active { color:#6366f1; background:rgba(99,102,241,.06); font-weight:700; }
    .dot { width:6px; height:6px; border-radius:999px; background:#94a3b8; transition:all .3s; }
    .dark .dot { background:#334155; }
    .nav-item.active .dot { background:#6366f1; box-shadow:0 0 8px #6366f1; transform:scale(1.2); }

    .axis-line::before { content:""; position:absolute; left:12%; right:12%; top:50%; height:2px; background:rgba(99,102,241,.25); transform:translateY(-50%); }
    .axis-line-y::after { content:""; position:absolute; top:14%; bottom:14%; left:50%; width:2px; background:rgba(168,85,247,.25); transform:translateX(-50%); }
</style>

<div id="courseRoot" class="relative h-screen bg-adaptive text-adaptive font-sans overflow-hidden flex flex-col selection:bg-indigo-500/30 pt-20 transition-colors duration-500">
    <div class="fixed inset-0 -z-50 pointer-events-none">
        <div id="animated-bg" class="absolute inset-0 opacity-50 transition-opacity"></div>
        <div class="absolute top-[-20%] left-[-10%] w-[1000px] h-[1000px] bg-indigo-500/5 dark:bg-indigo-900/20 rounded-full blur-[150px] animate-pulse transition-colors"></div>
        <div class="absolute bottom-[-20%] right-[-10%] w-[800px] h-[800px] bg-purple-500/5 dark:bg-purple-900/20 rounded-full blur-[100px] transition-colors"></div>
        <canvas id="stars" class="absolute inset-0 pointer-events-none opacity-0 dark:opacity-100 transition-opacity duration-500"></canvas>
    </div>

    @include('layouts.partials.navbar')

    <div class="flex flex-1 overflow-hidden relative z-20">
        @include('layouts.partials.course-sidebar')

        <main id="mainScroll" class="flex-1 h-full overflow-y-auto scroll-smooth relative bg-transparent custom-scrollbar scroll-padding-top-24">
            <div id="stickyHeader" class="sticky top-0 z-30 w-full backdrop-blur-2xl border-b border-adaptive px-8 py-4 flex items-center justify-between transition-colors duration-500" style="background-color: var(--glass-header);">
                <div class="flex items-center gap-4">
                    <div class="w-8 h-8 rounded-lg bg-indigo-500/10 dark:bg-indigo-500/20 border border-indigo-500/20 flex items-center justify-center font-bold text-xs text-indigo-600 dark:text-indigo-400 transition-colors">2.2</div>
                    <div>
                        <h1 class="text-sm font-bold text-heading transition-colors">Flexbox</h1>
                        <p class="text-[10px] text-muted transition-colors">Arah susunan, jarak, perataan, dan fleksibilitas elemen</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="hidden sm:block w-32 h-1.5 bg-slate-200 dark:bg-white/10 rounded-full overflow-hidden transition-colors">
                        <div id="topProgressBar" class="h-full bg-gradient-to-r from-indigo-400 to-purple-500 w-0 transition-all duration-500 shadow-[0_0_10px_rgba(99,102,241,0.5)]"></div>
                    </div>
                    <span id="progressLabelTop" class="text-indigo-600 dark:text-indigo-400 font-bold text-xs transition-colors">0%</span>
                </div>
            </div>

            <div class="p-6 lg:p-16 max-w-5xl mx-auto pb-40">
                <div class="mb-16 md:mb-24">
                    <div class="card-adaptive rounded-3xl border border-adaptive p-6 md:p-8 overflow-hidden relative">
                        <div class="absolute -right-10 -top-10 w-40 h-40 bg-indigo-500/10 rounded-full blur-3xl"></div>
                        <p class="text-xs font-mono font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-[.25em] mb-3">Subbab 2.2</p>
                        <h2 class="text-3xl md:text-5xl font-black text-heading leading-tight mb-4">Flexbox pada Tailwind CSS</h2>
                        <p class="text-sm md:text-base text-muted leading-relaxed max-w-3xl text-justify">
                            Pada subbab ini, pembelajar mempelajari cara menyusun elemen menggunakan Flexbox di Tailwind CSS. Materi difokuskan pada penggunaan <code>flex</code>, <code>flex-row</code>, <code>flex-col</code>, <code>justify-*</code>, <code>items-*</code>, dan <code>flex-1</code> untuk kebutuhan layout satu arah.
                        </p>
                    </div>
                </div>

                <div class="mb-24">
                    <h3 class="text-xl font-bold text-heading mb-6 flex items-center gap-2 transition-colors">
                        <svg class="w-5 h-5 text-indigo-500 dark:text-indigo-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Tujuan Pembelajaran
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="card-adaptive border p-6 rounded-xl flex items-start gap-4 hover:border-indigo-500/30 transition group h-full">
                            <div class="w-10 h-10 rounded-lg bg-indigo-100 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0 font-bold text-sm border border-indigo-200 dark:border-indigo-500/10 transition-colors">1</div>
                            <div><h4 class="text-sm font-bold text-heading mb-2 group-hover:text-indigo-500 dark:group-hover:text-indigo-400 transition-colors">Menjelaskan Flexbox</h4><p class="text-xs text-muted leading-relaxed">Menjelaskan fungsi Flexbox untuk menyusun elemen dalam satu arah secara lebih rapi.</p></div>
                        </div>
                        <div class="card-adaptive border p-6 rounded-xl flex items-start gap-4 hover:border-purple-500/30 transition group h-full">
                            <div class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400 flex items-center justify-center shrink-0 font-bold text-sm border border-purple-200 dark:border-purple-500/10 transition-colors">2</div>
                            <div><h4 class="text-sm font-bold text-heading mb-2 group-hover:text-purple-500 dark:group-hover:text-purple-400 transition-colors">Mengatur Arah</h4><p class="text-xs text-muted leading-relaxed">Membedakan penggunaan <code>flex-row</code> dan <code>flex-col</code> pada susunan konten.</p></div>
                        </div>
                        <div class="card-adaptive border p-6 rounded-xl flex items-start gap-4 hover:border-fuchsia-500/30 transition group h-full">
                            <div class="w-10 h-10 rounded-lg bg-fuchsia-100 dark:bg-fuchsia-900/20 text-fuchsia-600 dark:text-fuchsia-400 flex items-center justify-center shrink-0 font-bold text-sm border border-fuchsia-200 dark:border-fuchsia-500/10 transition-colors">3</div>
                            <div><h4 class="text-sm font-bold text-heading mb-2 group-hover:text-fuchsia-500 dark:group-hover:text-fuchsia-400 transition-colors">Merapikan Posisi</h4><p class="text-xs text-muted leading-relaxed">Menggunakan <code>gap</code>, <code>justify-*</code>, dan <code>items-*</code> untuk membuat layout nyaman dilihat.</p></div>
                        </div>
                        <div class="card-adaptive border p-6 rounded-xl flex items-start gap-4 hover:border-pink-500/30 transition group h-full">
                            <div class="w-10 h-10 rounded-lg bg-pink-100 dark:bg-pink-900/20 text-pink-600 dark:text-pink-400 flex items-center justify-center shrink-0 font-bold text-sm border border-pink-200 dark:border-pink-500/10 transition-colors">4</div>
                            <div><h4 class="text-sm font-bold text-heading mb-2 group-hover:text-pink-500 dark:group-hover:text-pink-400 transition-colors">Memperbaiki Layout</h4><p class="text-xs text-muted leading-relaxed">Memilih class Flexbox yang sesuai untuk memperbaiki navbar dan kartu sederhana.</p></div>
                        </div>
                    </div>
                </div>

                <article class="space-y-40">
                    <section id="section-31" class="lesson-section scroll-mt-32" data-lesson-id="31">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-indigo-500 pl-6">
                                <span class="text-indigo-600 dark:text-indigo-400 font-mono text-xs uppercase tracking-widest">Lesson 2.2.1</span>
                                <h2 class="text-4xl lg:text-5xl font-black text-heading leading-[1.1]">Konsep Dasar <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-purple-600 dark:from-indigo-400 dark:to-purple-500">Flexbox</span></h2>
                            </div>
                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-heading flex items-center gap-2"><span class="w-6 h-6 rounded bg-indigo-500 flex items-center justify-center text-[10px] text-white">A</span> Mengapa Flexbox Dibutuhkan?</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-lg leading-relaxed space-y-4 text-justify">
                                    <p>Dalam halaman web, elemen HTML secara bawaan biasanya mengikuti alur dokumen. Elemen seperti judul, paragraf, dan div akan tersusun dari atas ke bawah. Susunan ini cukup untuk teks panjang, tetapi kurang tepat saat kita perlu membuat navbar, deretan tombol, kartu profil, atau bagian produk yang elemennya harus sejajar.</p>
                                    <p><span class="hl-term">Flexbox</span> digunakan untuk mengatur elemen dalam satu arah utama, yaitu mendatar atau menurun. Dalam Tailwind CSS, mode Flexbox diaktifkan dengan class <span class="hl-term">flex</span> pada elemen pembungkus. Setelah pembungkus menjadi flex container, elemen di dalamnya dapat diatur arahnya, jaraknya, posisi tengahnya, dan pembagian ruangnya.</p>
                                </div>
                            </div>
                            <div class="grid md:grid-cols-2 gap-5">
                                <div class="card-adaptive border border-adaptive rounded-2xl p-6">
                                    <h4 class="text-sm font-black text-heading mb-3">Sebelum flex</h4>
                                    <div class="space-y-3 bg-slate-100 dark:bg-slate-950/60 border border-adaptive rounded-xl p-4">
                                        <div class="h-12 rounded-lg bg-slate-300 dark:bg-slate-700 flex items-center justify-center text-xs font-bold">Logo</div>
                                        <div class="h-12 rounded-lg bg-slate-300 dark:bg-slate-700 flex items-center justify-center text-xs font-bold">Menu</div>
                                        <div class="h-12 rounded-lg bg-slate-300 dark:bg-slate-700 flex items-center justify-center text-xs font-bold">Profil</div>
                                    </div>
                                    <p class="text-xs text-muted leading-relaxed mt-4">Elemen masih menumpuk sehingga kurang sesuai untuk navbar.</p>
                                </div>
                                <div class="card-adaptive border border-indigo-300/40 dark:border-indigo-500/30 rounded-2xl p-6">
                                    <h4 class="text-sm font-black text-indigo-600 dark:text-indigo-400 mb-3">Sesudah flex</h4>
                                    <div class="flex gap-3 bg-indigo-50 dark:bg-indigo-950/30 border border-indigo-200 dark:border-indigo-500/20 rounded-xl p-4 overflow-x-auto custom-scrollbar">
                                        <div class="h-12 min-w-24 rounded-lg bg-indigo-500 text-white flex items-center justify-center text-xs font-bold">Logo</div>
                                        <div class="h-12 min-w-24 rounded-lg bg-purple-500 text-white flex items-center justify-center text-xs font-bold">Menu</div>
                                        <div class="h-12 min-w-24 rounded-lg bg-fuchsia-500 text-white flex items-center justify-center text-xs font-bold">Profil</div>
                                    </div>
                                    <p class="text-xs text-muted leading-relaxed mt-4">Class <code>flex</code> membuat elemen anak tersusun sejajar dalam satu baris.</p>
                                </div>
                            </div>
                            <div class="sim-bg-adaptive border border-adaptive rounded-2xl overflow-hidden shadow-xl p-6 lg:p-8">
                                <h4 class="text-xs font-bold text-muted uppercase mb-4 text-center">Simulasi: Block vs Flex</h4>
                                <div class="bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-500/30 rounded-lg p-4 mb-8 text-sm text-indigo-800 dark:text-indigo-300">
                                    Pilih mode <strong>block</strong> atau <strong>flex</strong>. Perhatikan bahwa perubahan class pada pembungkus langsung mengubah susunan semua elemen anak.
                                </div>
                                <div class="grid lg:grid-cols-2 gap-6">
                                    <div class="code-adaptive border rounded-xl p-5 font-mono text-xs overflow-x-auto custom-scrollbar">
&lt;div class="<span id="sim1-class" class="text-indigo-600 dark:text-indigo-400 font-bold">block</span> gap-4"&gt;
  &lt;div&gt;Logo&lt;/div&gt;
  &lt;div&gt;Menu&lt;/div&gt;
  &lt;div&gt;Profil&lt;/div&gt;
&lt;/div&gt;
                                    </div>
                                    <div class="space-y-4">
                                        <div class="flex gap-2">
                                            <button onclick="setSimFlex('block')" class="px-4 py-2 rounded-lg bg-slate-200 dark:bg-white/10 text-xs font-bold">Block</button>
                                            <button onclick="setSimFlex('flex')" class="px-4 py-2 rounded-lg bg-indigo-600 text-white text-xs font-bold">Flex</button>
                                        </div>
                                        <div id="sim1-preview" class="block bg-slate-100 dark:bg-slate-950/60 border-2 border-dashed border-adaptive rounded-xl p-5 transition-all duration-300 min-h-40">
                                            <div class="sim1-item mb-3 h-14 w-24 rounded-xl bg-indigo-500 text-white flex items-center justify-center font-bold">Logo</div>
                                            <div class="sim1-item mb-3 h-14 w-24 rounded-xl bg-purple-500 text-white flex items-center justify-center font-bold">Menu</div>
                                            <div class="sim1-item mb-3 h-14 w-24 rounded-xl bg-fuchsia-500 text-white flex items-center justify-center font-bold">Profil</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="section-32" class="lesson-section scroll-mt-32" data-lesson-id="32">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-purple-500 pl-6">
                                <span class="text-purple-600 dark:text-purple-400 font-mono text-xs uppercase tracking-widest">Lesson 2.2.2</span>
                                <h2 class="text-4xl lg:text-5xl font-black text-heading leading-[1.1]">Arah Susunan <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-500 to-pink-600 dark:from-purple-400 dark:to-pink-500">flex-row dan flex-col</span></h2>
                            </div>
                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-heading flex items-center gap-2"><span class="w-6 h-6 rounded bg-purple-500 flex items-center justify-center text-[10px] text-white">A</span> Menentukan Arah Elemen</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-lg leading-relaxed space-y-4 text-justify">
                                    <p>Setelah sebuah pembungkus diberi class <code>flex</code>, elemen anak dapat diarahkan dengan class <span class="hl-term">flex-row</span> atau <span class="hl-term">flex-col</span>. Class <code>flex-row</code> menyusun elemen dari kiri ke kanan. Class ini cocok untuk navbar, tombol berdampingan, dan daftar informasi singkat.</p>
                                    <p>Class <span class="hl-term">flex-col</span> menyusun elemen dari atas ke bawah. Class ini cocok untuk kartu produk, panel informasi, form login, atau daftar konten yang perlu dibaca secara berurutan. Perbedaan ini penting karena pilihan arah akan memengaruhi cara <code>justify-*</code> dan <code>items-*</code> bekerja.</p>
                                </div>
                            </div>
                            <div class="sim-bg-adaptive border border-adaptive rounded-2xl overflow-hidden shadow-xl p-6 lg:p-8">
                                <h4 class="text-xs font-bold text-muted uppercase mb-4 text-center">Simulasi: Arah Susunan dan Gap</h4>
                                <div class="bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-500/30 rounded-lg p-4 mb-8 text-sm text-purple-800 dark:text-purple-300">
                                    Pilih arah susunan, lalu ubah jarak antar elemen. Modul ini menekankan bahwa <code>gap</code> lebih rapi dibanding memberi margin manual pada setiap item.
                                </div>
                                <div class="grid lg:grid-cols-2 gap-6">
                                    <div class="space-y-5 code-adaptive border rounded-xl p-5">
                                        <div>
                                            <label class="text-[10px] font-bold uppercase text-purple-600 dark:text-purple-400">Arah</label>
                                            <div class="flex gap-2 mt-2">
                                                <button onclick="setDirection('flex-row')" class="px-4 py-2 bg-purple-600 text-white rounded-lg text-xs font-bold">flex-row</button>
                                                <button onclick="setDirection('flex-col')" class="px-4 py-2 bg-slate-200 dark:bg-white/10 rounded-lg text-xs font-bold">flex-col</button>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="text-[10px] font-bold uppercase text-purple-600 dark:text-purple-400">Gap</label>
                                            <div class="flex gap-2 mt-2 flex-wrap">
                                                <button onclick="setGap('gap-2')" class="px-4 py-2 bg-slate-200 dark:bg-white/10 rounded-lg text-xs font-bold">gap-2</button>
                                                <button onclick="setGap('gap-4')" class="px-4 py-2 bg-purple-600 text-white rounded-lg text-xs font-bold">gap-4</button>
                                                <button onclick="setGap('gap-8')" class="px-4 py-2 bg-slate-200 dark:bg-white/10 rounded-lg text-xs font-bold">gap-8</button>
                                            </div>
                                        </div>
                                        <code id="sim2-code" class="block text-xs text-purple-700 dark:text-purple-300 bg-white dark:bg-black/30 border border-adaptive rounded-xl p-4">class="flex flex-row gap-4"</code>
                                    </div>
                                    <div class="bg-slate-100 dark:bg-slate-950/60 border border-adaptive rounded-xl p-6 min-h-72 flex items-center justify-center">
                                        <div id="sim2-target" class="flex flex-row gap-4 transition-all duration-300 border-2 border-dashed border-slate-300 dark:border-white/10 rounded-xl p-5">
                                            <div class="h-16 w-20 rounded-xl bg-purple-500 text-white flex items-center justify-center font-bold">A</div>
                                            <div class="h-16 w-20 rounded-xl bg-indigo-500 text-white flex items-center justify-center font-bold">B</div>
                                            <div class="h-16 w-20 rounded-xl bg-fuchsia-500 text-white flex items-center justify-center font-bold">C</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="section-33" class="lesson-section scroll-mt-32" data-lesson-id="33">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-fuchsia-500 pl-6">
                                <span class="text-fuchsia-600 dark:text-fuchsia-400 font-mono text-xs uppercase tracking-widest">Lesson 2.2.3</span>
                                <h2 class="text-4xl lg:text-5xl font-black text-heading leading-[1.1]">Perataan Elemen <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-fuchsia-500 to-pink-600 dark:from-fuchsia-400 dark:to-pink-500">justify dan items</span></h2>
                            </div>
                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-heading flex items-center gap-2"><span class="w-6 h-6 rounded bg-fuchsia-500 flex items-center justify-center text-[10px] text-white">A</span> Mengatur Ruang Kosong dan Posisi Tengah</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-lg leading-relaxed space-y-4 text-justify">
                                    <p>Dalam Flexbox, <span class="hl-term">justify-*</span> digunakan untuk mengatur posisi elemen pada sumbu utama. Jika arah susunan menggunakan <code>flex-row</code>, maka justify bekerja secara horizontal. Class yang sering digunakan adalah <code>justify-start</code>, <code>justify-center</code>, <code>justify-between</code>, dan <code>justify-end</code>.</p>
                                    <p>Sementara itu, <span class="hl-term">items-*</span> digunakan untuk mengatur posisi elemen pada sumbu silang. Pada susunan <code>flex-row</code>, items bekerja secara vertikal. Class <code>items-center</code> sering digunakan untuk membuat logo, teks, tombol, dan ikon sejajar di tengah tinggi navbar.</p>
                                </div>
                            </div>
                            <div class="sim-bg-adaptive border border-adaptive rounded-2xl overflow-hidden shadow-xl p-6 lg:p-8">
                                <h4 class="text-xs font-bold text-muted uppercase mb-4 text-center">Simulasi: Justify dan Items</h4>
                                <div class="bg-fuchsia-50 dark:bg-fuchsia-900/20 border border-fuchsia-200 dark:border-fuchsia-500/30 rounded-lg p-4 mb-8 text-sm text-fuchsia-800 dark:text-fuchsia-300">
                                    Coba beberapa kombinasi. Untuk navbar, kombinasi yang paling sering dipakai adalah <code>justify-between items-center</code>.
                                </div>
                                <div class="grid lg:grid-cols-2 gap-6">
                                    <div class="space-y-5 code-adaptive border rounded-xl p-5">
                                        <div>
                                            <label class="text-[10px] font-bold uppercase text-fuchsia-600 dark:text-fuchsia-400">Justify</label>
                                            <div class="grid grid-cols-2 gap-2 mt-2">
                                                <button onclick="setAlign('j','justify-start')" class="px-3 py-2 bg-slate-200 dark:bg-white/10 rounded-lg text-xs font-bold">start</button>
                                                <button onclick="setAlign('j','justify-center')" class="px-3 py-2 bg-slate-200 dark:bg-white/10 rounded-lg text-xs font-bold">center</button>
                                                <button onclick="setAlign('j','justify-between')" class="px-3 py-2 bg-fuchsia-600 text-white rounded-lg text-xs font-bold">between</button>
                                                <button onclick="setAlign('j','justify-end')" class="px-3 py-2 bg-slate-200 dark:bg-white/10 rounded-lg text-xs font-bold">end</button>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="text-[10px] font-bold uppercase text-fuchsia-600 dark:text-fuchsia-400">Items</label>
                                            <div class="grid grid-cols-2 gap-2 mt-2">
                                                <button onclick="setAlign('i','items-start')" class="px-3 py-2 bg-slate-200 dark:bg-white/10 rounded-lg text-xs font-bold">start</button>
                                                <button onclick="setAlign('i','items-center')" class="px-3 py-2 bg-fuchsia-600 text-white rounded-lg text-xs font-bold">center</button>
                                                <button onclick="setAlign('i','items-end')" class="px-3 py-2 bg-slate-200 dark:bg-white/10 rounded-lg text-xs font-bold">end</button>
                                                <button onclick="setAlign('i','items-stretch')" class="px-3 py-2 bg-slate-200 dark:bg-white/10 rounded-lg text-xs font-bold">stretch</button>
                                            </div>
                                        </div>
                                        <code id="sim3-code" class="block text-xs text-fuchsia-700 dark:text-fuchsia-300 bg-white dark:bg-black/30 border border-adaptive rounded-xl p-4">class="flex justify-between items-center"</code>
                                    </div>
                                    <div class="relative axis-line axis-line-y bg-slate-100 dark:bg-slate-950/60 border border-adaptive rounded-xl p-6 min-h-72">
                                        <div id="sim3-target" class="relative z-10 bg-white dark:bg-slate-900 border border-adaptive rounded-2xl p-5 h-56 flex justify-between items-center gap-3 transition-all duration-300 shadow-lg">
                                            <div class="bg-fuchsia-500 w-16 h-16 rounded-xl flex items-center justify-center font-black text-white">1</div>
                                            <div class="bg-purple-500 w-16 h-28 rounded-xl flex items-center justify-center font-black text-white">2</div>
                                            <div class="bg-indigo-500 w-16 h-12 rounded-xl flex items-center justify-center font-black text-white">3</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="section-34" class="lesson-section scroll-mt-32" data-lesson-id="34">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-pink-500 pl-6">
                                <span class="text-pink-600 dark:text-pink-400 font-mono text-xs uppercase tracking-widest">Lesson 2.2.4</span>
                                <h2 class="text-4xl lg:text-5xl font-black text-heading leading-[1.1]">Pembagian Ruang <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-pink-500 to-rose-600 dark:from-pink-400 dark:to-rose-500">flex-1 dan shrink-0</span></h2>
                            </div>
                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-heading flex items-center gap-2"><span class="w-6 h-6 rounded bg-pink-500 flex items-center justify-center text-[10px] text-white">A</span> Elemen yang Melebar dan Elemen yang Tetap</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-lg leading-relaxed space-y-4 text-justify">
                                    <p>Pada layout nyata, tidak semua elemen memiliki perilaku ukuran yang sama. Logo atau avatar biasanya harus tetap ukurannya, sedangkan kolom pencarian, area konten, atau deskripsi dapat dibuat melebar mengikuti sisa ruang.</p>
                                    <p>Class <span class="hl-term">flex-1</span> membuat elemen mengambil ruang kosong yang tersedia. Class <span class="hl-term">shrink-0</span> menjaga elemen agar tidak mengecil saat ruang layar menyempit. Dua class ini sering digunakan bersama pada navbar, kartu profil, sidebar, dan komponen media object.</p>
                                </div>
                            </div>
                            <div class="sim-bg-adaptive border border-adaptive rounded-2xl overflow-hidden shadow-xl p-6 lg:p-8">
                                <h4 class="text-xs font-bold text-muted uppercase mb-4 text-center">Simulasi: Elemen Fleksibel dan Tetap</h4>
                                <div class="bg-pink-50 dark:bg-pink-900/20 border border-pink-200 dark:border-pink-500/30 rounded-lg p-4 mb-8 text-sm text-pink-800 dark:text-pink-300">
                                    Geser lebar container. Nyalakan <code>flex-1</code> untuk kolom teks dan <code>shrink-0</code> untuk avatar agar layout tetap stabil.
                                </div>
                                <div class="grid lg:grid-cols-2 gap-6">
                                    <div class="space-y-5 code-adaptive border rounded-xl p-5">
                                        <div>
                                            <label class="text-[10px] font-bold uppercase text-pink-600 dark:text-pink-400">Lebar container</label>
                                            <input type="range" min="45" max="100" value="100" class="w-full accent-pink-600 mt-3" oninput="resizeFlexBox(this.value)">
                                            <span id="sim4-width" class="text-xs font-bold text-muted">100%</span>
                                        </div>
                                        <div class="grid grid-cols-2 gap-2">
                                            <button onclick="toggleFlex1()" class="px-3 py-2 bg-pink-600 text-white rounded-lg text-xs font-bold">Toggle flex-1</button>
                                            <button onclick="toggleShrink0()" class="px-3 py-2 bg-slate-200 dark:bg-white/10 rounded-lg text-xs font-bold">Toggle shrink-0</button>
                                        </div>
                                        <code id="sim4-code" class="block text-xs text-pink-700 dark:text-pink-300 bg-white dark:bg-black/30 border border-adaptive rounded-xl p-4">avatar: shrink | text: normal</code>
                                    </div>
                                    <div class="bg-slate-100 dark:bg-slate-950/60 border border-adaptive rounded-xl p-6 min-h-72 flex items-center justify-center overflow-hidden">
                                        <div id="sim4-box" class="w-full bg-white dark:bg-slate-900 border border-adaptive rounded-2xl p-4 flex items-center gap-4 shadow-lg transition-all duration-200 overflow-hidden">
                                            <div id="sim4-avatar" class="w-20 h-20 rounded-full bg-gradient-to-br from-pink-500 to-rose-500 flex items-center justify-center text-white font-black shrink transition-all duration-300">IMG</div>
                                            <div id="sim4-text" class="bg-slate-100 dark:bg-slate-800 border border-adaptive rounded-xl p-4 min-w-0 transition-all duration-300">
                                                <div class="font-black text-heading text-sm truncate">Judul Profil Produk</div>
                                                <div class="text-xs text-muted truncate">Deskripsi singkat akan mengikuti sisa ruang container.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="section-35" class="lesson-section scroll-mt-32 pt-10 border-t border-adaptive transition-colors" data-lesson-id="35" data-type="activity">
                        <div class="relative rounded-[2.5rem] sim-bg-adaptive border border-adaptive p-6 md:p-10 overflow-hidden shadow-xl group hover:border-indigo-500/30 transition-all duration-500">
                            <div class="absolute -top-24 -right-24 w-64 h-64 bg-indigo-600/10 dark:bg-indigo-600/20 blur-[100px] rounded-full pointer-events-none"></div>

                            <div class="flex flex-col md:flex-row items-start md:items-center gap-6 mb-8 relative z-10">
                                <div class="p-4 bg-gradient-to-br from-indigo-600 to-purple-800 rounded-2xl text-white shadow-lg shadow-indigo-500/20 shrink-0">
                                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z"/></svg>
                                </div>
                                <div>
                                    <div class="flex flex-wrap items-center gap-3 mb-1">
                                        <h2 class="text-3xl font-black text-heading tracking-tight">Aktivitas 2.2: Pilih Susunan Class Flexbox</h2>
                                    </div>
                                    <p class="text-slate-600 dark:text-indigo-200/60 text-sm leading-relaxed max-w-2xl mt-2 text-justify">
                                        Pilih huruf jawaban yang sesuai dengan kebutuhan layout. Aktivitas ini membantu mencocokkan kebutuhan layout dengan susunan class Tailwind CSS yang tepat.
                                    </p>
                                </div>
                            </div>

                            <div id="activityPanel" class="relative z-10 grid xl:grid-cols-5 gap-6">
                                <div class="xl:col-span-3 card-adaptive border border-adaptive rounded-2xl overflow-hidden">
                                    <div class="bg-indigo-600/95 dark:bg-indigo-900/95 text-white p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                        <div class="text-xs font-bold uppercase tracking-widest">Tabel Aktivitas</div>
                                        <p class="text-[11px] opacity-90 leading-relaxed m-0 sm:text-right max-w-xl">Pilih A sampai H pada kolom Jawaban. Minimal benar 4 dari 5 agar progress subbab selesai.</p>
                                    </div>

                                    <div class="overflow-x-auto custom-scrollbar">
                                        <table class="w-full text-sm">
                                            <thead class="bg-slate-100 dark:bg-black/30 text-heading">
                                                <tr>
                                                    <th class="px-4 py-3 text-left text-xs font-black uppercase tracking-widest border-b border-adaptive w-12">No.</th>
                                                    <th class="px-4 py-3 text-left text-xs font-black uppercase tracking-widest border-b border-adaptive">Kebutuhan Layout</th>
                                                    <th class="px-4 py-3 text-left text-xs font-black uppercase tracking-widest border-b border-adaptive w-32">Jawaban</th>
                                                </tr>
                                            </thead>
                                            <tbody id="matchRows" class="divide-y divide-adaptive"></tbody>
                                        </table>
                                    </div>

                                    <div class="p-4 md:p-6 border-t border-adaptive bg-slate-50 dark:bg-black/20">
                                        <h3 class="text-sm font-black text-heading mb-3">Pilihan Class</h3>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                                            <div class="code-adaptive border border-adaptive rounded-lg px-3 py-2"><b>A.</b> <code>flex gap-4</code></div>
                                            <div class="code-adaptive border border-adaptive rounded-lg px-3 py-2"><b>B.</b> <code>flex flex-col</code></div>
                                            <div class="code-adaptive border border-adaptive rounded-lg px-3 py-2"><b>C.</b> <code>flex items-center</code></div>
                                            <div class="code-adaptive border border-adaptive rounded-lg px-3 py-2"><b>D.</b> <code>flex justify-between</code></div>
                                            <div class="code-adaptive border border-adaptive rounded-lg px-3 py-2"><b>E.</b> <code>flex-1</code></div>
                                            <div class="code-adaptive border border-adaptive rounded-lg px-3 py-2"><b>F.</b> <code>text-center</code></div>
                                            <div class="code-adaptive border border-adaptive rounded-lg px-3 py-2"><b>G.</b> <code>rounded-lg</code></div>
                                            <div class="code-adaptive border border-adaptive rounded-lg px-3 py-2"><b>H.</b> <code>bg-white</code></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="xl:col-span-2 space-y-6">
                                    <div class="card-adaptive border border-adaptive rounded-2xl p-6">
                                        <h3 class="text-sm font-black text-heading mb-3">Preview Layout</h3>
                                        <p class="text-xs text-muted leading-relaxed mb-4">Preview akan berubah mengikuti baris yang sedang dipilih agar pembelajar memahami alasan pemilihan class.</p>
                                        <div id="matchPreview" class="bg-slate-100 dark:bg-slate-950/60 border border-adaptive rounded-xl p-5 min-h-[240px] flex items-center justify-center"></div>
                                    </div>

                                    <div class="card-adaptive border border-adaptive rounded-2xl p-6">
                                        <div class="flex items-center justify-between mb-3">
                                            <h3 class="text-sm font-black text-heading">Skor Aktivitas</h3>
                                            <span id="answerCounter" class="text-xs font-bold text-indigo-600 dark:text-indigo-400">0/5</span>
                                        </div>
                                        <div class="w-full h-2 bg-slate-200 dark:bg-white/10 rounded-full overflow-hidden mb-4">
                                            <div id="activityBar" class="h-full bg-gradient-to-r from-indigo-500 to-purple-500 w-0 transition-all duration-500"></div>
                                        </div>
                                        <div id="activityMsg" class="rounded-xl bg-slate-100 dark:bg-black/30 border border-adaptive p-4 text-xs text-muted leading-relaxed mb-4">
                                            Isi semua jawaban terlebih dahulu, lalu tekan tombol validasi.
                                        </div>
                                        <button id="submitExerciseBtn" onclick="submitMatchActivity()" disabled class="w-full py-3 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-black uppercase tracking-wider transition-all shadow-lg shadow-emerald-500/20 opacity-50 cursor-not-allowed">Validasi Aktivitas</button>
                                        <button onclick="resetMatchActivity()" class="mt-3 w-full py-3 rounded-xl bg-slate-200 dark:bg-white/10 text-xs font-black uppercase tracking-wider">Ulangi Jawaban</button>
                                    </div>
                                </div>
                            </div>

                            <div id="activityDone" class="hidden relative z-10 text-center p-8 rounded-2xl border border-emerald-300 dark:border-emerald-500/30 bg-emerald-50 dark:bg-emerald-500/10">
                                <div class="w-16 h-16 rounded-full bg-emerald-500 text-white mx-auto flex items-center justify-center text-3xl font-black mb-4">✓</div>
                                <h3 class="text-2xl font-black text-emerald-700 dark:text-emerald-300 mb-2">Aktivitas Selesai</h3>
                                <p class="text-sm text-emerald-700/80 dark:text-emerald-200/80">Progress subbab 2.2 berhasil disimpan. Tombol lanjut sudah terbuka.</p>
                            </div>
                        </div>
                    </section>
                </article>

                <div class="mt-32 pt-8 border-t border-adaptive flex justify-between items-center transition-colors">
                    <a href="{{ \Illuminate\Support\Facades\Route::has('courses.layout-basics') ? route('courses.layout-basics') : '#' }}" class="group flex items-center gap-4 text-muted hover:text-heading transition-colors">
                        <div class="w-12 h-12 rounded-full border border-adaptive flex items-center justify-center group-hover:bg-slate-100 dark:group-hover:bg-white/5 transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        </div>
                        <div class="text-left hidden sm:block"><div class="text-[10px] uppercase tracking-widest font-bold opacity-60">Sebelumnya</div><div class="font-black text-sm">Dasar Layout dan Ruang</div></div>
                    </a>
                    <div id="nextChapterBtn" class="group flex items-center gap-4 text-right text-muted cursor-not-allowed opacity-50 pointer-events-none transition-all duration-500">
                        <div class="text-right hidden sm:block"><div id="nextLabel" class="text-[10px] uppercase tracking-widest font-bold opacity-60">Terkunci</div><div class="font-black text-sm">Grid</div></div>
                        <div id="nextIcon" class="w-12 h-12 rounded-full border border-adaptive flex items-center justify-center bg-slate-100 dark:bg-white/5 transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
    window.LESSON_IDS = [31, 32, 33, 34, 35];
    let rawCompletedIds = {!! json_encode($completedLessonIds ?? []) !!};
    window.COMPLETED_IDS = rawCompletedIds.map(id => Number(id));
    let completedSet = new Set(window.COMPLETED_IDS);
    const ACTIVITY_LESSON_ID = 35;
    let activityCompleted = {!! ($activityCompleted ?? false) ? 'true' : 'false' !!};

    document.addEventListener('DOMContentLoaded', () => {
        updateProgressUI(false);
        initMasterObserver();
        initSidebarScroll();
        initVisualEffects();
        renderMatchRows();
        if (activityCompleted) { lockMatchActivity(); unlockNextChapter(); }
        document.querySelectorAll('.nav-item').forEach(item => {
            const target = item.getAttribute('data-target') || '';
            const targetId = Number(target.replace('#section-', ''));
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
            }
        } catch (e) { console.error('Network Error:', e); }
    }

    function initMasterObserver() {
        const mainScroll = document.getElementById('mainScroll');
        const sections = document.querySelectorAll('.lesson-section');
        if (!mainScroll || !sections.length) return;
        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const lessonId = Number(entry.target.dataset.lessonId);
                    const isActivity = entry.target.dataset.type === 'activity';
                    if (typeof highlightAnchor === 'function') highlightAnchor(entry.target.id);
                    if (lessonId && !isActivity && !completedSet.has(lessonId)) saveLessonToDB(lessonId);
                }
            });
        }, { root: mainScroll, rootMargin: '-10% 0px -60% 0px', threshold: 0 });
        sections.forEach(section => observer.observe(section));
    }

    window.setSimFlex = function(type) {
        const label = document.getElementById('sim1-class');
        const preview = document.getElementById('sim1-preview');
        const items = preview.querySelectorAll('.sim1-item');
        label.innerText = type;
        if (type === 'flex') {
            preview.className = 'flex gap-4 bg-slate-100 dark:bg-slate-950/60 border-2 border-dashed border-adaptive rounded-xl p-5 transition-all duration-300 min-h-40 overflow-x-auto custom-scrollbar';
            items.forEach(item => item.classList.remove('mb-3'));
        } else {
            preview.className = 'block bg-slate-100 dark:bg-slate-950/60 border-2 border-dashed border-adaptive rounded-xl p-5 transition-all duration-300 min-h-40';
            items.forEach(item => item.classList.add('mb-3'));
        }
    };

    let simDirection = 'flex-row';
    let simGap = 'gap-4';
    window.setDirection = function(dir) { simDirection = dir; applyDirectionGap(); };
    window.setGap = function(gap) { simGap = gap; applyDirectionGap(); };
    function applyDirectionGap() {
        const target = document.getElementById('sim2-target');
        const code = document.getElementById('sim2-code');
        target.className = `flex ${simDirection} ${simGap} transition-all duration-300 border-2 border-dashed border-slate-300 dark:border-white/10 rounded-xl p-5`;
        code.innerText = `class="flex ${simDirection} ${simGap}"`;
    }

    let simJustify = 'justify-between';
    let simItems = 'items-center';
    window.setAlign = function(type, val) {
        if (type === 'j') simJustify = val;
        if (type === 'i') simItems = val;
        const target = document.getElementById('sim3-target');
        const code = document.getElementById('sim3-code');
        target.className = `relative z-10 bg-white dark:bg-slate-900 border border-adaptive rounded-2xl p-5 h-56 flex ${simJustify} ${simItems} gap-3 transition-all duration-300 shadow-lg`;
        code.innerText = `class="flex ${simJustify} ${simItems}"`;
    };

    let hasFlex1 = false;
    let hasShrink0 = false;
    window.resizeFlexBox = function(val) {
        document.getElementById('sim4-box').style.width = val + '%';
        document.getElementById('sim4-width').innerText = val + '%';
    };
    window.toggleFlex1 = function() { hasFlex1 = !hasFlex1; updateFlexSizeSim(); };
    window.toggleShrink0 = function() { hasShrink0 = !hasShrink0; updateFlexSizeSim(); };
    function updateFlexSizeSim() {
        const text = document.getElementById('sim4-text');
        const avatar = document.getElementById('sim4-avatar');
        const code = document.getElementById('sim4-code');
        text.classList.toggle('flex-1', hasFlex1);
        avatar.classList.toggle('shrink-0', hasShrink0);
        avatar.classList.toggle('shrink', !hasShrink0);
        code.innerText = `avatar: ${hasShrink0 ? 'shrink-0' : 'shrink'} | text: ${hasFlex1 ? 'flex-1' : 'normal'}`;
    }

    const matchItems = [
        {
            need: 'Dua tombol perlu disusun sejajar ke samping.',
            answer: 'A',
            className: 'flex gap-4',
            explain: 'flex membuat tombol berada satu baris, sedangkan gap-4 memberi jarak antar tombol.',
            preview: 'buttons'
        },
        {
            need: 'Daftar menu perlu disusun dari atas ke bawah.',
            answer: 'B',
            className: 'flex flex-col',
            explain: 'flex-col mengubah arah susunan menjadi vertikal dari atas ke bawah.',
            preview: 'menu'
        },
        {
            need: 'Ikon dan teks perlu sejajar di tengah secara vertikal.',
            answer: 'C',
            className: 'flex items-center',
            explain: 'items-center menyejajarkan elemen pada sumbu silang, sehingga ikon dan teks berada di tengah secara vertikal.',
            preview: 'icontext'
        },
        {
            need: 'Judul berada di kiri dan tombol berada di kanan.',
            answer: 'D',
            className: 'flex justify-between',
            explain: 'justify-between membagi ruang kosong sehingga elemen pertama berada di kiri dan elemen terakhir berada di kanan.',
            preview: 'between'
        },
        {
            need: 'Kolom isian perlu melebar mengisi ruang kosong.',
            answer: 'E',
            className: 'flex-1',
            explain: 'flex-1 membuat elemen mengambil sisa ruang yang tersedia di dalam container flex.',
            preview: 'input'
        }
    ];
    const choiceMap = {
        A: 'flex gap-4',
        B: 'flex flex-col',
        C: 'flex items-center',
        D: 'flex justify-between',
        E: 'flex-1',
        F: 'text-center',
        G: 'rounded-lg',
        H: 'bg-white'
    };
    let matchAnswers = {};
    let matchValidated = false;

    function renderMatchRows() {
        const tbody = document.getElementById('matchRows');
        if (!tbody) return;
        tbody.innerHTML = matchItems.map((item, index) => `
            <tr id="match-row-${index}" class="hover:bg-slate-50 dark:hover:bg-white/[0.02] transition">
                <td class="px-4 py-4 align-top font-black text-heading">${index + 1}.</td>
                <td class="px-4 py-4 align-top text-sm text-heading leading-relaxed">
                    <div class="font-bold">${item.need}</div>
                    <div id="match-feedback-${index}" class="hidden text-[11px] mt-2 leading-relaxed"></div>
                </td>
                <td class="px-4 py-4 align-top">
                    <select id="match-select-${index}" onchange="setMatchAnswer(${index}, this.value)" onfocus="showMatchPreview('${item.preview}')" class="w-full rounded-lg border border-adaptive bg-white dark:bg-slate-900 px-3 py-2 text-xs font-bold text-heading outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Pilih</option>
                        ${Object.keys(choiceMap).map(key => `<option value="${key}">${key}</option>`).join('')}
                    </select>
                </td>
            </tr>
        `).join('');
        showMatchPreview('buttons');
        updateMatchProgress();
    }

    window.setMatchAnswer = function(index, value) {
        if (matchValidated || activityCompleted) return;
        if (value) matchAnswers[index] = value;
        else delete matchAnswers[index];
        showMatchPreview(matchItems[index].preview);
        updateMatchProgress();
    };

    function updateMatchProgress() {
        const answered = Object.keys(matchAnswers).length;
        const total = matchItems.length;
        const percent = Math.round((answered / total) * 100);
        const counter = document.getElementById('answerCounter');
        const bar = document.getElementById('activityBar');
        const btn = document.getElementById('submitExerciseBtn');
        if (counter) counter.textContent = `${answered}/${total}`;
        if (bar) bar.style.width = percent + '%';
        if (btn) {
            btn.disabled = answered < total || matchValidated || activityCompleted;
            btn.classList.toggle('opacity-50', answered < total || matchValidated || activityCompleted);
            btn.classList.toggle('cursor-not-allowed', answered < total || matchValidated || activityCompleted);
        }
    }

    function showMatchPreview(type) {
        const preview = document.getElementById('matchPreview');
        if (!preview) return;
        const baseBox = 'bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-xl p-4 shadow-sm';
        if (type === 'buttons') {
            preview.innerHTML = `<div class="${baseBox} w-full"><div class="flex gap-4"><button class="px-4 py-2 rounded-lg bg-indigo-600 text-white text-xs font-bold">Simpan</button><button class="px-4 py-2 rounded-lg bg-slate-200 dark:bg-white/10 text-heading text-xs font-bold">Batal</button></div><code class="block mt-4 text-[11px] text-indigo-600 dark:text-indigo-400 font-bold">flex gap-4</code></div>`;
        } else if (type === 'menu') {
            preview.innerHTML = `<div class="${baseBox} w-full max-w-xs"><nav class="flex flex-col gap-2"><a class="rounded-lg bg-indigo-50 dark:bg-indigo-500/10 px-3 py-2 text-xs font-bold text-indigo-600 dark:text-indigo-300">Beranda</a><a class="rounded-lg bg-slate-100 dark:bg-white/5 px-3 py-2 text-xs font-bold">Produk</a><a class="rounded-lg bg-slate-100 dark:bg-white/5 px-3 py-2 text-xs font-bold">Kontak</a></nav><code class="block mt-4 text-[11px] text-indigo-600 dark:text-indigo-400 font-bold">flex flex-col</code></div>`;
        } else if (type === 'icontext') {
            preview.innerHTML = `<div class="${baseBox} w-full"><div class="flex items-center gap-3"><div class="w-10 h-10 rounded-full bg-purple-600 text-white flex items-center justify-center font-black">i</div><span class="text-sm font-bold text-heading">Informasi Produk</span></div><code class="block mt-4 text-[11px] text-indigo-600 dark:text-indigo-400 font-bold">flex items-center</code></div>`;
        } else if (type === 'between') {
            preview.innerHTML = `<div class="${baseBox} w-full"><div class="flex justify-between items-center"><h4 class="font-black text-heading">Produk</h4><button class="px-3 py-2 rounded-lg bg-indigo-600 text-white text-xs font-bold">Tambah</button></div><code class="block mt-4 text-[11px] text-indigo-600 dark:text-indigo-400 font-bold">flex justify-between</code></div>`;
        } else {
            preview.innerHTML = `<div class="${baseBox} w-full"><div class="flex gap-3 items-center"><label class="text-xs font-bold text-muted shrink-0">Cari</label><input class="flex-1 rounded-lg border border-slate-300 dark:border-white/10 bg-white dark:bg-slate-950 px-3 py-2 text-xs" value="sepatu kanvas"><button class="px-3 py-2 rounded-lg bg-indigo-600 text-white text-xs font-bold">Go</button></div><code class="block mt-4 text-[11px] text-indigo-600 dark:text-indigo-400 font-bold">flex-1</code></div>`;
        }
    }

    window.submitMatchActivity = async function() {
        if (activityCompleted) return;
        const msg = document.getElementById('activityMsg');
        const btn = document.getElementById('submitExerciseBtn');
        let score = 0;

        matchItems.forEach((item, index) => {
            const selected = matchAnswers[index];
            const feedback = document.getElementById(`match-feedback-${index}`);
            const row = document.getElementById(`match-row-${index}`);
            const select = document.getElementById(`match-select-${index}`);
            const correct = selected === item.answer;
            if (correct) score++;
            if (select) select.disabled = true;
            if (row) {
                row.classList.remove('bg-emerald-50', 'dark:bg-emerald-500/10', 'bg-rose-50', 'dark:bg-rose-500/10');
                row.classList.add(correct ? 'bg-emerald-50' : 'bg-rose-50', correct ? 'dark:bg-emerald-500/10' : 'dark:bg-rose-500/10');
            }
            if (feedback) {
                feedback.className = `block text-[11px] mt-2 leading-relaxed ${correct ? 'text-emerald-700 dark:text-emerald-300' : 'text-rose-700 dark:text-rose-300'}`;
                feedback.innerHTML = `${correct ? 'Benar' : 'Belum tepat'}. Jawaban yang sesuai adalah <b>${item.answer}. ${item.className}</b>. ${item.explain}`;
            }
        });

        document.getElementById('scoreLabel').innerText = `${score}/5`;
        matchValidated = true;

        if (score >= 4) {
            msg.className = 'rounded-xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/30 p-4 text-xs text-emerald-700 dark:text-emerald-300 leading-relaxed mb-4';
            msg.innerHTML = `<b>Skor ${score}/5.</b> Aktivitas valid. Progress sedang disimpan.`;
            btn.innerText = 'Menyimpan...';
            btn.disabled = true;
            await saveLessonToDB(ACTIVITY_LESSON_ID);
            activityCompleted = true;
            lockMatchActivity();
            unlockNextChapter();
        } else {
            msg.className = 'rounded-xl bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/30 p-4 text-xs text-amber-700 dark:text-amber-300 leading-relaxed mb-4';
            msg.innerHTML = `<b>Skor ${score}/5.</b> Minimal benar 4. Baca ulang bagian Flexbox yang belum dipahami, tinjau pembahasan pada jawaban salah, lalu tekan tombol ulangi.`;
            btn.disabled = true;
            btn.classList.add('opacity-50', 'cursor-not-allowed', 'shake');
            setTimeout(() => btn.classList.remove('shake'), 400);
        }
    };

    window.resetMatchActivity = function() {
        if (activityCompleted) return;
        matchAnswers = {};
        matchValidated = false;
        document.getElementById('scoreLabel').innerText = '0/5';
        const msg = document.getElementById('activityMsg');
        msg.className = 'rounded-xl bg-slate-100 dark:bg-black/30 border border-adaptive p-4 text-xs text-muted leading-relaxed mb-4';
        msg.innerText = 'Isi semua jawaban terlebih dahulu, lalu tekan tombol validasi.';
        renderMatchRows();
    };

    function lockMatchActivity() {
        document.getElementById('activityPanel')?.classList.add('hidden');
        document.getElementById('activityDone')?.classList.remove('hidden');
        markSidebarDone(ACTIVITY_LESSON_ID);
    }
    function unlockNextChapter() {
        const btn = document.getElementById('nextChapterBtn');
        if (!btn) return;
        btn.classList.remove('cursor-not-allowed', 'opacity-50', 'pointer-events-none', 'text-muted');
        btn.classList.add('text-indigo-600', 'dark:text-indigo-400', 'cursor-pointer');
        const nextLabel = document.getElementById('nextLabel');
        nextLabel.innerText = 'Selanjutnya';
        nextLabel.classList.remove('opacity-60');
        nextLabel.classList.add('opacity-100', 'text-indigo-600', 'dark:text-indigo-400');
        const icon = document.getElementById('nextIcon');
        icon.innerHTML = `<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>`;
        icon.classList.remove('bg-slate-100', 'dark:bg-white/5');
        icon.classList.add('bg-indigo-100', 'dark:bg-indigo-500/20', 'border-indigo-300', 'dark:border-indigo-500/50', 'text-indigo-600', 'dark:text-indigo-400', 'shadow-lg');
        btn.onclick = () => window.location.href = "{{ \Illuminate\Support\Facades\Route::has('courses.grid') ? route('courses.grid') : '#' }}";
    }

    function highlightAnchor(id) {
        document.querySelectorAll('.nav-item').forEach(item => item.classList.remove('active'));
        const active = document.querySelector(`.nav-item[data-target="#${id}"]`);
        if (active) active.classList.add('active');
    }
    function initSidebarScroll() {
        const m = document.getElementById('mainScroll');
        if (!m) return;
        m.addEventListener('scroll', () => {
            let current = '';
            document.querySelectorAll('.lesson-section').forEach(s => {
                if (m.scrollTop >= s.offsetTop - 250) current = s.id;
            });
            if (current) highlightAnchor(current);
        });
    }
    function initVisualEffects() {
        const c = document.getElementById('stars');
        if (!c) return;
        const ctx = c.getContext('2d');
        function resize(){ c.width = innerWidth; c.height = innerHeight; }
        resize(); window.addEventListener('resize', resize);
        let stars = [];
        for (let i=0; i<90; i++) stars.push({x:Math.random()*c.width,y:Math.random()*c.height,r:Math.random()*1.2,v:Math.random()*0.25+.08});
        function draw(){
            ctx.clearRect(0,0,c.width,c.height);
            ctx.fillStyle='rgba(255,255,255,.30)';
            stars.forEach(s=>{ctx.beginPath();ctx.arc(s.x,s.y,s.r,0,Math.PI*2);ctx.fill();s.y+=s.v;if(s.y>c.height)s.y=0;});
            requestAnimationFrame(draw);
        }
        draw();
    }
</script>
@endsection
