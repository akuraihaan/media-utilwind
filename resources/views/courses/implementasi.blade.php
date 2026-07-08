@extends('layouts.landing')
@section('title','Instalasi Tailwind CSS')

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
    .dark .hl-term { color: #67e8f9; background-color: rgba(6,182,212,.18); border-color: rgba(34,211,238,.38); }

    .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(148,163,184,.38); border-radius: 999px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #06b6d4; }

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
    .nav-item.active { color:#06b6d4; background:rgba(6,182,212,.08); font-weight:700; }
    .dot { width:6px; height:6px; border-radius:50%; background:#94a3b8; transition:all .3s; }
    .dark .dot { background:#475569; }
    .nav-item.active .dot { background:#06b6d4; box-shadow:0 0 8px #06b6d4; transform:scale(1.2); }

    .code-line { display:block; min-width:max-content; }
    .tag { color:#0e7490; font-weight:700; }
    .attr { color:#d97706; }
    .str { color:#059669; }
    .prop { color:#2563eb; }
    .comment { color:#94a3b8; font-style:italic; }
    .dark .tag { color:#67e8f9; }
    .dark .attr { color:#fbbf24; }
    .dark .str { color:#34d399; }
    .dark .prop { color:#60a5fa; }

    .step-card.active { border-color: rgba(6,182,212,.55) !important; box-shadow: 0 0 0 3px rgba(6,182,212,.12); }
    .folder-row.active { background: rgba(6,182,212,.12); }
    .activity-select.valid { border-color:#10b981 !important; background:rgba(16,185,129,.10) !important; }
    .activity-select.invalid { border-color:#ef4444 !important; background:rgba(239,68,68,.10) !important; }
</style>

@include('courses.partials.interactive-activity-kit')

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
                    <div class="w-8 h-8 rounded-lg bg-cyan-500/10 dark:bg-cyan-500/20 border border-cyan-500/20 flex items-center justify-center font-bold text-xs text-cyan-600 dark:text-cyan-400 shrink-0">1.4</div>
                    <div class="min-w-0">
                        <h1 class="text-sm font-bold text-heading line-clamp-1">Instalasi Tailwind CSS</h1>
                        <p class="text-[10px] text-muted line-clamp-1">Node.js, NPM, Tailwind CLI, input.css, dan output.css</p>
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
                        <p class="text-xs font-mono font-bold text-cyan-600 dark:text-cyan-400 uppercase tracking-[.25em] mb-3">Subbab 1.4</p>
                        <h2 class="text-3xl md:text-5xl font-black text-heading leading-tight mb-4">Instalasi Tailwind CSS</h2>
                        <p class="text-sm md:text-base text-muted leading-relaxed max-w-3xl text-justify">
                            Pada subbab ini, pembelajar mempelajari cara menyiapkan Tailwind CSS secara lokal. Instalasi dilakukan menggunakan Node.js, NPM, dan Tailwind CLI agar file CSS sumber dapat diproses menjadi file CSS hasil yang siap dihubungkan ke HTML.
                        </p>
                    </div>

                    <h3 class="text-xl font-bold text-heading mt-10 mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-cyan-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Tujuan Pembelajaran
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="card-adaptive border rounded-xl p-5">
                            <div class="w-8 h-8 rounded-lg bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 flex items-center justify-center font-bold mb-4">1</div>
                            <h4 class="text-sm font-bold text-heading mb-1">Menyiapkan Prasyarat</h4>
                            <p class="text-[11px] text-muted leading-relaxed">Menjelaskan fungsi Node.js, NPM, terminal, dan folder proyek.</p>
                        </div>
                        <div class="card-adaptive border rounded-xl p-5">
                            <div class="w-8 h-8 rounded-lg bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold mb-4">2</div>
                            <h4 class="text-sm font-bold text-heading mb-1">Menyusun Folder</h4>
                            <p class="text-[11px] text-muted leading-relaxed">Membedakan fungsi index.html, input.css, output.css, dan package.json.</p>
                        </div>
                        <div class="card-adaptive border rounded-xl p-5">
                            <div class="w-8 h-8 rounded-lg bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold mb-4">3</div>
                            <h4 class="text-sm font-bold text-heading mb-1">Menjalankan Perintah</h4>
                            <p class="text-[11px] text-muted leading-relaxed">Mengurutkan perintah npm init, instalasi paket, dan build Tailwind CLI.</p>
                        </div>
                        <div class="card-adaptive border rounded-xl p-5">
                            <div class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold mb-4">4</div>
                            <h4 class="text-sm font-bold text-heading mb-1">Menganalisis Build</h4>
                            <p class="text-[11px] text-muted leading-relaxed">Menjelaskan alur input.css menjadi output.css yang dipakai HTML.</p>
                        </div>
                    </div>
                </div>

                <article class="space-y-32 md:space-y-40">

                    <section id="section-16" class="lesson-section scroll-mt-32" data-lesson-id="16">
                        <div class="space-y-8">
                            <div class="space-y-4 border-l-4 border-cyan-500 pl-4 md:pl-6">
                                <span class="text-cyan-600 dark:text-cyan-400 font-mono text-xs uppercase tracking-widest">Lesson 1.4.1</span>
                                <h2 class="text-3xl md:text-5xl font-black text-heading leading-tight">Persiapan <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-500 to-blue-600">Node.js dan NPM</span></h2>
                            </div>

                            <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-90 text-sm md:text-base leading-relaxed space-y-4 text-justify">
                                <p>Pada subbab sebelumnya, Tailwind CSS digunakan melalui CDN untuk latihan cepat. Pada proyek yang lebih terstruktur, Tailwind CSS perlu dipasang secara lokal agar proses pengelolaan file, build CSS, dan pengembangan proyek dapat berjalan lebih rapi.</p>
                                <p><span class="hl-term">Node.js</span> digunakan agar alat berbasis JavaScript dapat berjalan di komputer. <span class="hl-term">NPM</span> digunakan untuk membuat paket proyek dan memasang Tailwind CSS. Sementara itu, terminal digunakan untuk menjalankan perintah instalasi dan build.</p>
                                <p>Sebelum instalasi, pembelajar perlu memastikan Node.js dan NPM sudah tersedia. Pengecekan dilakukan melalui perintah <code>node -v</code> dan <code>npm -v</code>. Jika terminal menampilkan nomor versi, prasyarat dasar sudah siap.</p>
                            </div>

                            <div class="sim-bg-adaptive border border-adaptive rounded-2xl overflow-hidden shadow-xl">
                                <div class="bg-cyan-600/95 dark:bg-cyan-900/95 text-white p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3 border-b border-cyan-400/50">
                                    <div class="text-xs font-bold uppercase tracking-widest">Simulasi 1 — Cek Prasyarat</div>
                                    <p class="text-[11px] opacity-90 leading-relaxed m-0 md:text-right max-w-xl">Klik tombol pengecekan untuk melihat bagaimana terminal memastikan Node.js dan NPM sudah siap.</p>
                                </div>
                                <div class="grid grid-cols-1 lg:grid-cols-2 min-h-[360px]">
                                    <div class="code-adaptive border-b lg:border-b-0 lg:border-r border-adaptive p-5">
                                        <div class="flex flex-wrap gap-2 mb-5">
                                            <button onclick="runCheck('node')" class="px-4 py-2 rounded-lg bg-cyan-600 hover:bg-cyan-500 text-white text-xs font-bold transition">Cek Node.js</button>
                                            <button onclick="runCheck('npm')" class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold transition">Cek NPM</button>
                                            <button onclick="runCheck('reset')" class="px-4 py-2 rounded-lg bg-slate-200 dark:bg-white/10 text-slate-700 dark:text-slate-200 text-xs font-bold border border-adaptive transition">Reset</button>
                                        </div>
                                        <div class="bg-slate-950 text-slate-100 rounded-xl border border-slate-800 p-4 font-mono text-xs min-h-[220px] overflow-auto custom-scrollbar shadow-inner">
                                            <div class="text-slate-500 mb-2">Terminal</div>
                                            <div id="check-terminal" class="space-y-2">
                                                <div class="text-slate-400">$ Siapkan pengecekan prasyarat instalasi...</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-white dark:bg-slate-950 flex items-center justify-center p-6">
                                        <div class="w-full max-w-sm space-y-3">
                                            <div id="node-status" class="step-card card-adaptive border rounded-xl p-4">
                                                <div class="flex items-center justify-between gap-3">
                                                    <div><h4 class="text-sm font-bold text-heading">Node.js</h4><p class="text-[11px] text-muted mt-1">Menjalankan perintah berbasis JavaScript.</p></div>
                                                    <span class="text-[10px] px-2 py-1 rounded bg-slate-100 dark:bg-white/10 text-muted font-bold">Belum dicek</span>
                                                </div>
                                            </div>
                                            <div id="npm-status" class="step-card card-adaptive border rounded-xl p-4">
                                                <div class="flex items-center justify-between gap-3">
                                                    <div><h4 class="text-sm font-bold text-heading">NPM</h4><p class="text-[11px] text-muted mt-1">Memasang paket Tailwind CSS.</p></div>
                                                    <span class="text-[10px] px-2 py-1 rounded bg-slate-100 dark:bg-white/10 text-muted font-bold">Belum dicek</span>
                                                </div>
                                            </div>
                                            <div id="ready-status" class="border border-dashed border-adaptive rounded-xl p-4 text-center">
                                                <p class="text-[11px] text-muted leading-relaxed">Status siap muncul setelah Node.js dan NPM berhasil dicek.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gradient-to-r from-cyan-50 to-transparent dark:from-cyan-900/20 dark:to-transparent border-l-4 border-cyan-500 p-4 rounded-r-xl">
                                <h5 class="text-xs font-bold text-cyan-700 dark:text-cyan-400 mb-1">Kesimpulan</h5>
                                <p class="text-[11px] text-slate-700 dark:text-slate-300 leading-relaxed m-0 text-justify">Instalasi Tailwind CSS membutuhkan lingkungan kerja yang siap. Node.js menjalankan alatnya, NPM mengelola paketnya, dan terminal digunakan untuk menjalankan proses instalasi.</p>
                            </div>
                        </div>
                    </section>

                    <section id="section-17" class="lesson-section scroll-mt-32" data-lesson-id="17">
                        <div class="space-y-8">
                            <div class="space-y-4 border-l-4 border-cyan-500 pl-4 md:pl-6">
                                <span class="text-cyan-600 dark:text-cyan-400 font-mono text-xs uppercase tracking-widest">Lesson 1.4.2</span>
                                <h2 class="text-3xl md:text-5xl font-black text-heading leading-tight">Struktur <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-500 to-blue-600">Folder Proyek</span></h2>
                            </div>

                            <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-90 text-sm md:text-base leading-relaxed space-y-4 text-justify">
                                <p>Langkah awal instalasi adalah membuat folder proyek. Folder ini digunakan untuk menyimpan file HTML, file CSS sumber, file CSS hasil, dan file informasi paket. Struktur folder yang rapi membantu pembelajar memahami alur kerja Tailwind secara bertahap.</p>
                                <p>File <code>index.html</code> memuat struktur halaman dan class Tailwind. File <code>input.css</code> menjadi sumber CSS yang berisi pemanggilan Tailwind. File <code>output.css</code> adalah hasil build yang nantinya dihubungkan ke HTML. File <code>package.json</code> menyimpan informasi proyek dan daftar paket yang dipasang.</p>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                <div class="card-adaptive border rounded-2xl p-5">
                                    <h3 class="font-bold text-heading mb-4">Struktur folder yang digunakan</h3>
                                    <div class="bg-slate-100 dark:bg-black/30 border border-adaptive rounded-xl p-4 font-mono text-xs leading-7">
                                        <div class="folder-row rounded px-2" id="folder-root">belajar-tailwind/</div>
                                        <div class="folder-row rounded px-2 pl-6" id="folder-package">├── package.json</div>
                                        <div class="folder-row rounded px-2 pl-6" id="folder-src">└── src/</div>
                                        <div class="folder-row rounded px-2 pl-10" id="folder-html">├── index.html</div>
                                        <div class="folder-row rounded px-2 pl-10" id="folder-input">├── input.css</div>
                                        <div class="folder-row rounded px-2 pl-10" id="folder-output">└── output.css</div>
                                    </div>
                                </div>
                                <div class="card-adaptive border rounded-2xl p-5">
                                    <h3 class="font-bold text-heading mb-4">Fungsi file</h3>
                                    <div class="grid grid-cols-2 gap-2 mb-4">
                                        <button id="file-html" onclick="selectProjectFile('html')" class="file-btn px-3 py-2 rounded-lg bg-slate-100 dark:bg-black/30 border border-adaptive text-xs font-bold hover:border-cyan-500 transition">index.html</button>
                                        <button id="file-input" onclick="selectProjectFile('input')" class="file-btn px-3 py-2 rounded-lg bg-slate-100 dark:bg-black/30 border border-adaptive text-xs font-bold hover:border-cyan-500 transition">input.css</button>
                                        <button id="file-output" onclick="selectProjectFile('output')" class="file-btn px-3 py-2 rounded-lg bg-slate-100 dark:bg-black/30 border border-adaptive text-xs font-bold hover:border-cyan-500 transition">output.css</button>
                                        <button id="file-package" onclick="selectProjectFile('package')" class="file-btn px-3 py-2 rounded-lg bg-slate-100 dark:bg-black/30 border border-adaptive text-xs font-bold hover:border-cyan-500 transition">package.json</button>
                                    </div>
                                    <div class="bg-slate-100 dark:bg-black/30 border border-adaptive rounded-xl p-4">
                                        <h4 id="file-title" class="font-bold text-heading text-sm mb-2">index.html</h4>
                                        <p id="file-desc" class="text-xs text-muted leading-relaxed mb-4">File halaman HTML yang memanggil output.css dan berisi class Tailwind.</p>
                                        <pre id="file-code" class="font-mono text-[11px] leading-relaxed overflow-auto custom-scrollbar whitespace-pre-wrap text-slate-700 dark:text-slate-300"></pre>
                                    </div>
                                </div>
                            </div>

                            <div class="sim-bg-adaptive border border-adaptive rounded-2xl overflow-hidden shadow-xl">
                                <div class="bg-blue-600/95 dark:bg-blue-900/95 text-white p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3 border-b border-blue-400/50">
                                    <div class="text-xs font-bold uppercase tracking-widest">Simulasi 2 — Alur File</div>
                                    <p class="text-[11px] opacity-90 leading-relaxed m-0 md:text-right max-w-xl">Klik tahap alur untuk melihat bagaimana file saling berhubungan dalam instalasi Tailwind.</p>
                                </div>
                                <div class="p-5 md:p-6 grid grid-cols-1 md:grid-cols-4 gap-3">
                                    <button onclick="setFlow(1)" id="flow-1" class="flow-card card-adaptive border rounded-xl p-4 text-left"><span class="text-xs font-bold text-cyan-600">1. HTML</span><p class="text-[11px] text-muted mt-2">Class Tailwind ditulis di elemen HTML.</p></button>
                                    <button onclick="setFlow(2)" id="flow-2" class="flow-card card-adaptive border rounded-xl p-4 text-left"><span class="text-xs font-bold text-blue-600">2. input.css</span><p class="text-[11px] text-muted mt-2">Tailwind dipanggil sebagai sumber CSS.</p></button>
                                    <button onclick="setFlow(3)" id="flow-3" class="flow-card card-adaptive border rounded-xl p-4 text-left"><span class="text-xs font-bold text-indigo-600">3. CLI Build</span><p class="text-[11px] text-muted mt-2">CLI memproses file sumber.</p></button>
                                    <button onclick="setFlow(4)" id="flow-4" class="flow-card card-adaptive border rounded-xl p-4 text-left"><span class="text-xs font-bold text-emerald-600">4. output.css</span><p class="text-[11px] text-muted mt-2">CSS hasil dihubungkan ke HTML.</p></button>
                                </div>
                                <div class="border-t border-adaptive p-5 md:p-6 bg-slate-50 dark:bg-black/20">
                                    <pre id="flow-code" class="font-mono text-xs leading-relaxed overflow-auto custom-scrollbar bg-white dark:bg-black/30 border border-adaptive rounded-xl p-4"></pre>
                                    <p id="flow-desc" class="text-xs text-muted leading-relaxed mt-3 text-justify"></p>
                                </div>
                            </div>

                            <div class="bg-gradient-to-r from-blue-50 to-transparent dark:from-blue-900/20 dark:to-transparent border-l-4 border-blue-500 p-4 rounded-r-xl">
                                <h5 class="text-xs font-bold text-blue-700 dark:text-blue-400 mb-1">Kesimpulan</h5>
                                <p class="text-[11px] text-slate-700 dark:text-slate-300 leading-relaxed m-0 text-justify">Instalasi Tailwind CSS tidak hanya memasang paket. Pembelajar juga perlu memahami hubungan antara file HTML, file CSS sumber, file CSS hasil, dan file package proyek.</p>
                            </div>
                        </div>
                    </section>

                    <section id="section-18" class="lesson-section scroll-mt-32" data-lesson-id="18">
                        <div class="space-y-8">
                            <div class="space-y-4 border-l-4 border-cyan-500 pl-4 md:pl-6">
                                <span class="text-cyan-600 dark:text-cyan-400 font-mono text-xs uppercase tracking-widest">Lesson 1.4.3</span>
                                <h2 class="text-3xl md:text-5xl font-black text-heading leading-tight">Membuat Package dan <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-500 to-blue-600">Memasang Tailwind CLI</span></h2>
                            </div>

                            <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-90 text-sm md:text-base leading-relaxed space-y-4 text-justify">
                                <p>Setelah folder proyek dibuat, terminal dibuka pada folder tersebut. Perintah <code>npm init -y</code> digunakan untuk membuat file <code>package.json</code>. File ini menjadi penanda bahwa folder sudah menjadi proyek yang dapat mengelola paket melalui NPM.</p>
                                <p>Langkah berikutnya adalah memasang Tailwind CSS dan Tailwind CLI. Paket <code>tailwindcss</code> berisi fitur utama Tailwind, sedangkan <code>@tailwindcss/cli</code> digunakan untuk menjalankan proses build melalui terminal.</p>
                            </div>

                            <div class="code-adaptive border border-adaptive rounded-2xl overflow-hidden">
                                <div class="px-4 py-3 border-b border-adaptive flex items-center justify-between">
                                    <span class="font-mono text-xs font-bold text-muted">Terminal</span>
                                    <span class="text-[10px] uppercase tracking-widest text-cyan-500 font-bold">Perintah Instalasi</span>
                                </div>
                                <pre class="p-5 overflow-auto custom-scrollbar text-xs md:text-sm font-mono leading-relaxed"><code><span class="code-line"><span class="comment"># masuk ke folder proyek</span></span>
<span class="code-line"><span class="tag">cd</span> belajar-tailwind</span>
<span class="code-line"></span>
<span class="code-line"><span class="comment"># membuat package.json</span></span>
<span class="code-line"><span class="tag">npm</span> init -y</span>
<span class="code-line"></span>
<span class="code-line"><span class="comment"># memasang Tailwind CSS dan CLI</span></span>
<span class="code-line"><span class="tag">npm</span> install tailwindcss @tailwindcss/cli</span></code></pre>
                            </div>

                            <div class="sim-bg-adaptive border border-adaptive rounded-2xl overflow-hidden shadow-xl">
                                <div class="bg-cyan-600/95 dark:bg-cyan-900/95 text-white p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3 border-b border-cyan-400/50">
                                    <div class="text-xs font-bold uppercase tracking-widest">Simulasi 3 — Jalankan Perintah Instalasi</div>
                                    <p class="text-[11px] opacity-90 leading-relaxed m-0 md:text-right max-w-xl">Jalankan perintah secara berurutan agar proses instalasi masuk akal.</p>
                                </div>
                                <div class="grid grid-cols-1 lg:grid-cols-2 min-h-[360px]">
                                    <div class="code-adaptive p-5 border-b lg:border-b-0 lg:border-r border-adaptive">
                                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-2 mb-4">
                                            <button onclick="runInstallStep(1)" id="install-step-1" class="install-step px-3 py-2 rounded-lg bg-white dark:bg-black/30 border border-adaptive text-xs font-bold hover:border-cyan-500">cd folder</button>
                                            <button onclick="runInstallStep(2)" id="install-step-2" class="install-step px-3 py-2 rounded-lg bg-white dark:bg-black/30 border border-adaptive text-xs font-bold hover:border-cyan-500">npm init</button>
                                            <button onclick="runInstallStep(3)" id="install-step-3" class="install-step px-3 py-2 rounded-lg bg-white dark:bg-black/30 border border-adaptive text-xs font-bold hover:border-cyan-500">install</button>
                                            <button onclick="runInstallStep(0)" class="px-3 py-2 rounded-lg bg-slate-200 dark:bg-white/10 border border-adaptive text-xs font-bold">Reset</button>
                                        </div>
                                        <div class="bg-slate-950 text-slate-100 rounded-xl border border-slate-800 p-4 font-mono text-xs min-h-[220px] overflow-auto custom-scrollbar" id="install-terminal">
                                            <div class="text-slate-400">$ Terminal siap menjalankan perintah instalasi.</div>
                                        </div>
                                    </div>
                                    <div class="bg-white dark:bg-slate-950 p-6 flex items-center justify-center">
                                        <div class="w-full max-w-sm">
                                            <div class="rounded-2xl border border-adaptive bg-slate-50 dark:bg-black/30 p-5">
                                                <p class="text-[10px] uppercase tracking-widest text-muted font-bold mb-4">Hasil Setelah Instalasi</p>
                                                <div class="font-mono text-xs leading-7">
                                                    <div id="after-root">belajar-tailwind/</div>
                                                    <div id="after-package" class="pl-4 text-slate-400">├── package.json</div>
                                                    <div id="after-lock" class="pl-4 text-slate-400">├── package-lock.json</div>
                                                    <div id="after-node" class="pl-4 text-slate-400">├── node_modules/</div>
                                                    <div class="pl-4">└── src/</div>
                                                    <div class="pl-8">├── index.html</div>
                                                    <div class="pl-8">└── input.css</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gradient-to-r from-cyan-50 to-transparent dark:from-cyan-900/20 dark:to-transparent border-l-4 border-cyan-500 p-4 rounded-r-xl">
                                <h5 class="text-xs font-bold text-cyan-700 dark:text-cyan-400 mb-1">Kesimpulan</h5>
                                <p class="text-[11px] text-slate-700 dark:text-slate-300 leading-relaxed m-0 text-justify">Perintah instalasi harus berjalan dari folder proyek. <code>package.json</code> dibuat terlebih dahulu, lalu Tailwind CSS dan Tailwind CLI dipasang sebagai paket proyek.</p>
                            </div>
                        </div>
                    </section>

                    <section id="section-19" class="lesson-section scroll-mt-32" data-lesson-id="19">
                        <div class="space-y-8">
                            <div class="space-y-4 border-l-4 border-cyan-500 pl-4 md:pl-6">
                                <span class="text-cyan-600 dark:text-cyan-400 font-mono text-xs uppercase tracking-widest">Lesson 1.4.4</span>
                                <h2 class="text-3xl md:text-5xl font-black text-heading leading-tight">Proses Build <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-500 to-blue-600">Input ke Output CSS</span></h2>
                            </div>

                            <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-90 text-sm md:text-base leading-relaxed space-y-4 text-justify">
                                <p>File <code>input.css</code> berisi perintah <code>@import "tailwindcss";</code>. File ini disebut file CSS sumber karena menjadi pintu masuk Tailwind dalam proyek. File ini belum langsung dipakai oleh browser.</p>
                                <p>Tailwind CLI memproses <code>input.css</code> dan class Tailwind yang digunakan pada HTML, lalu menghasilkan <code>output.css</code>. File <code>output.css</code> inilah yang dihubungkan ke HTML melalui tag <code>&lt;link rel="stylesheet" href="./output.css"&gt;</code>.</p>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                <div class="code-adaptive border border-adaptive rounded-2xl overflow-hidden">
                                    <div class="px-4 py-3 border-b border-adaptive flex items-center justify-between">
                                        <span class="font-mono text-xs font-bold text-muted">src/input.css</span>
                                        <span class="text-[10px] uppercase tracking-widest text-cyan-500 font-bold">File Sumber</span>
                                    </div>
                                    <pre class="p-5 overflow-auto custom-scrollbar text-xs md:text-sm font-mono leading-relaxed"><code><span class="code-line"><span class="prop">@import</span> <span class="str">"tailwindcss"</span>;</span></code></pre>
                                </div>
                                <div class="code-adaptive border border-adaptive rounded-2xl overflow-hidden">
                                    <div class="px-4 py-3 border-b border-adaptive flex items-center justify-between">
                                        <span class="font-mono text-xs font-bold text-muted">Perintah build</span>
                                        <span class="text-[10px] uppercase tracking-widest text-blue-500 font-bold">Tailwind CLI</span>
                                    </div>
                                    <pre class="p-5 overflow-auto custom-scrollbar text-xs md:text-sm font-mono leading-relaxed"><code><span class="tag">npx</span> @tailwindcss/cli -i ./src/input.css -o ./src/output.css --watch</code></pre>
                                </div>
                            </div>

                            <div class="sim-bg-adaptive border border-adaptive rounded-2xl overflow-hidden shadow-xl">
                                <div class="bg-blue-600/95 dark:bg-blue-900/95 text-white p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3 border-b border-blue-400/50">
                                    <div class="text-xs font-bold uppercase tracking-widest">Simulasi 4 — Build CSS</div>
                                    <p class="text-[11px] opacity-90 leading-relaxed m-0 md:text-right max-w-xl">Pilih tahap build untuk memahami hubungan HTML, input.css, Tailwind CLI, dan output.css.</p>
                                </div>
                                <div class="p-5 md:p-6">
                                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-2 mb-5">
                                        <button onclick="setBuildStage(1)" id="build-pill-1" class="build-pill px-3 py-2 rounded-lg bg-slate-100 dark:bg-black/30 border border-adaptive text-xs font-bold transition">HTML</button>
                                        <button onclick="setBuildStage(2)" id="build-pill-2" class="build-pill px-3 py-2 rounded-lg bg-slate-100 dark:bg-black/30 border border-adaptive text-xs font-bold transition">input.css</button>
                                        <button onclick="setBuildStage(3)" id="build-pill-3" class="build-pill px-3 py-2 rounded-lg bg-slate-100 dark:bg-black/30 border border-adaptive text-xs font-bold transition">Build</button>
                                        <button onclick="setBuildStage(4)" id="build-pill-4" class="build-pill px-3 py-2 rounded-lg bg-slate-100 dark:bg-black/30 border border-adaptive text-xs font-bold transition">output.css</button>
                                    </div>
                                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                        <pre id="build-code" class="bg-slate-100 dark:bg-black/30 border border-adaptive rounded-xl p-4 font-mono text-xs leading-relaxed overflow-auto custom-scrollbar min-h-[180px]"></pre>
                                        <div class="bg-slate-50 dark:bg-black/20 border border-adaptive rounded-xl p-5 flex flex-col justify-center">
                                            <p class="text-[10px] uppercase tracking-widest font-bold text-muted mb-2">Penjelasan Tahap</p>
                                            <p id="build-desc" class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed text-justify"></p>
                                            <div class="mt-5 rounded-xl bg-white dark:bg-slate-900 border border-adaptive p-4">
                                                <p class="text-[10px] uppercase tracking-widest text-muted font-bold mb-2">Preview Alur</p>
                                                <div class="flex flex-wrap items-center gap-2 text-xs font-bold">
                                                    <span class="px-3 py-2 rounded-lg bg-cyan-500/10 text-cyan-600 dark:text-cyan-400">input.css</span>
                                                    <span class="text-muted">→</span>
                                                    <span class="px-3 py-2 rounded-lg bg-blue-500/10 text-blue-600 dark:text-blue-400">Tailwind CLI</span>
                                                    <span class="text-muted">→</span>
                                                    <span class="px-3 py-2 rounded-lg bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">output.css</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gradient-to-r from-blue-50 to-transparent dark:from-blue-900/20 dark:to-transparent border-l-4 border-blue-500 p-4 rounded-r-xl">
                                <h5 class="text-xs font-bold text-blue-700 dark:text-blue-400 mb-1">Kesimpulan</h5>
                                <p class="text-[11px] text-slate-700 dark:text-slate-300 leading-relaxed m-0 text-justify">Alur instalasi berakhir ketika <code>output.css</code> berhasil dibuat dan dihubungkan ke HTML. Browser membaca file hasil tersebut untuk menampilkan gaya Tailwind pada halaman.</p>
                            </div>
                        </div>
                    </section>

                    <section id="section-20" class="lesson-section scroll-mt-32" data-lesson-id="20" data-type="activity">
                        <div class="space-y-8">
                            <div class="space-y-4 border-l-4 border-blue-500 pl-4 md:pl-6">
                                <span class="text-blue-600 dark:text-blue-400 font-mono text-xs uppercase tracking-widest">Aktivitas 1.4</span>
                                <h2 class="text-3xl md:text-5xl font-black text-heading leading-tight">Susun Alur <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-500 to-blue-600">Instalasi Tailwind CSS</span></h2>
                            </div>

                            <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-90 text-sm md:text-base leading-relaxed text-justify">
                                <p>Aktivitas ini menggunakan drag n drop untuk menyusun alur instalasi Tailwind CSS. Geser kartu dari pemeriksaan Node.js sampai proses build output.css!</p>
                            </div>

                            <div class="card-adaptive border rounded-2xl overflow-hidden shadow-xl relative">
                                <div id="lockOverlay" class="hidden absolute inset-0 z-20 bg-white/80 dark:bg-slate-950/80 backdrop-blur-sm items-center justify-center p-6">
                                    <div class="max-w-md text-center">
                                        <div class="w-14 h-14 rounded-full bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mx-auto mb-4 text-2xl">✓</div>
                                        <h3 class="font-black text-heading mb-2">Aktivitas Sudah Selesai</h3>
                                        <p class="text-sm text-muted leading-relaxed">Jawaban aktivitas telah tersimpan. Anda dapat melanjutkan ke subbab berikutnya.</p>
                                    </div>
                                </div>

                                <div class="bg-blue-600/95 dark:bg-blue-900/95 text-white p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                                    <div class="text-xs font-bold uppercase tracking-widest">Drag n Drop Instalasi</div>
                                    <p class="text-[11px] opacity-90 leading-relaxed m-0 md:text-right max-w-xl">Geser kartu langkah instalasi ke urutan yang benar, lalu tekan tombol periksa!</p>
                                </div>

                                <div id="activityForm" class="p-4 md:p-6 space-y-4 max-h-[620px] overflow-y-auto custom-scrollbar">
                                    <div class="activity-row card-adaptive border rounded-xl p-5" data-answer="b">
                                        <p class="text-sm font-bold text-heading mb-3">1. Terminal perlu memastikan Node.js sudah terpasang. Perintah yang tepat adalah ....</p>
                                        <select onchange="chooseActivity(this, 'q1')" class="activity-select w-full bg-white dark:bg-black/30 border border-adaptive rounded-lg px-3 py-3 text-xs outline-none focus:ring-2 focus:ring-cyan-500">
                                            <option value="">Geser kartu langkah</option>
                                            <option value="a">npm init -y</option>
                                            <option value="b">node -v</option>
                                            <option value="c">@import "tailwindcss";</option>
                                            <option value="d">&lt;link rel="stylesheet" href="./output.css"&gt;</option>
                                        </select>
                                    </div>
                                    <div class="activity-row card-adaptive border rounded-xl p-5" data-answer="a">
                                        <p class="text-sm font-bold text-heading mb-3">2. Folder proyek perlu dibuat menjadi proyek NPM agar memiliki package.json. Perintah yang tepat adalah ....</p>
                                        <select onchange="chooseActivity(this, 'q2')" class="activity-select w-full bg-white dark:bg-black/30 border border-adaptive rounded-lg px-3 py-3 text-xs outline-none focus:ring-2 focus:ring-cyan-500">
                                            <option value="">Geser kartu langkah</option>
                                            <option value="a">npm init -y</option>
                                            <option value="b">npm -v</option>
                                            <option value="c">src/output.css</option>
                                            <option value="d">grid-cols-3</option>
                                        </select>
                                    </div>
                                    <div class="activity-row card-adaptive border rounded-xl p-5" data-answer="c">
                                        <p class="text-sm font-bold text-heading mb-3">3. Paket yang dipasang agar Tailwind dan CLI dapat digunakan adalah ....</p>
                                        <select onchange="chooseActivity(this, 'q3')" class="activity-select w-full bg-white dark:bg-black/30 border border-adaptive rounded-lg px-3 py-3 text-xs outline-none focus:ring-2 focus:ring-cyan-500">
                                            <option value="">Geser kartu langkah</option>
                                            <option value="a">npm install bootstrap jquery</option>
                                            <option value="b">npm install laravel vite</option>
                                            <option value="c">npm install tailwindcss @tailwindcss/cli</option>
                                            <option value="d">npm install html css</option>
                                        </select>
                                    </div>
                                    <div class="activity-row card-adaptive border rounded-xl p-5" data-answer="d">
                                        <p class="text-sm font-bold text-heading mb-3">4. Isi dasar file input.css untuk memanggil Tailwind adalah ....</p>
                                        <select onchange="chooseActivity(this, 'q4')" class="activity-select w-full bg-white dark:bg-black/30 border border-adaptive rounded-lg px-3 py-3 text-xs outline-none focus:ring-2 focus:ring-cyan-500">
                                            <option value="">Geser kartu langkah</option>
                                            <option value="a">&lt;script src="https://cdn.tailwindcss.com"&gt;&lt;/script&gt;</option>
                                            <option value="b">body { margin: 0; }</option>
                                            <option value="c">npm init -y</option>
                                            <option value="d">@import "tailwindcss";</option>
                                        </select>
                                    </div>
                                    <div class="activity-row card-adaptive border rounded-xl p-5" data-answer="b">
                                        <p class="text-sm font-bold text-heading mb-3">5. Perintah yang memproses input.css menjadi output.css adalah ....</p>
                                        <select onchange="chooseActivity(this, 'q5')" class="activity-select w-full bg-white dark:bg-black/30 border border-adaptive rounded-lg px-3 py-3 text-xs outline-none focus:ring-2 focus:ring-cyan-500">
                                            <option value="">Geser kartu langkah</option>
                                            <option value="a">node -v -i ./src/input.css</option>
                                            <option value="b">npx @tailwindcss/cli -i ./src/input.css -o ./src/output.css --watch</option>
                                            <option value="c">&lt;link rel="stylesheet" href="./input.css"&gt;</option>
                                            <option value="d">npm init output.css</option>
                                        </select>
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
                                    <h3 class="font-bold text-heading mb-3">Status Aktivitas</h3>
                                    <div class="space-y-2 text-xs text-muted leading-relaxed">
                                        <p>Aktivitas telah memenuhi skor minimal. Progress materi berhasil diproses.</p>
                                        <p>Gunakan urutan yang telah disusun sebagai latihan mandiri sebelum melanjutkan.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                </article>

                <div class="mt-20 md:mt-32 pt-8 border-t border-adaptive flex flex-col sm:flex-row justify-between items-center gap-4">
                    <a href="{{ \Illuminate\Support\Facades\Route::has('courses.latarbelakang') ? route('courses.latarbelakang') : '#' }}" class="group flex items-center gap-4 text-muted hover:text-heading transition-colors w-full sm:w-auto justify-center sm:justify-start">
                        <div class="w-10 h-10 md:w-12 md:h-12 rounded-full border border-adaptive flex items-center justify-center group-hover:bg-slate-100 dark:group-hover:bg-white/5 transition-colors shrink-0">
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        </div>
                        <div class="text-left">
                            <div class="text-[10px] uppercase tracking-widest font-bold opacity-60">Sebelumnya</div>
                            <div class="font-black text-xs md:text-sm line-clamp-1">Tailwind CSS melalui CDN</div>
                        </div>
                    </a>

                    <div id="nextChapterBtn" class="group flex items-center gap-4 text-right text-muted cursor-not-allowed opacity-50 pointer-events-none transition-all duration-500 w-full sm:w-auto justify-center sm:justify-end">
                        <div class="text-right">
                            <div id="nextLabel" class="text-[10px] uppercase tracking-widest font-bold opacity-60">Berikutnya</div>
                            <div class="font-black text-xs md:text-sm line-clamp-1">Konfigurasi Dasar Tailwind CSS</div>
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
    window.LESSON_IDS = [16, 17, 18, 19, 20];
    let rawCompletedIds = {!! json_encode($completedLessonIds ?? []) !!};
    window.COMPLETED_IDS = rawCompletedIds.map(id => Number(id));
    let completedSet = new Set(window.COMPLETED_IDS);
    const ACTIVITY_LESSON_ID = 20;
    let activityCompleted = {!! ($activityCompleted ?? false) ? 'true' : 'false' !!};

    const activityAnswers = {};
    let activityWidget = null;
    const projectFiles = {
        html: {
            title: 'index.html',
            desc: 'File halaman HTML. File ini memanggil output.css dan berisi class Tailwind pada elemen HTML.',
            code: '<link rel="stylesheet" href="./output.css">\n\n<section class="bg-slate-100 p-6">\n  <h1 class="text-2xl font-bold">Halo Tailwind</h1>\n</section>',
            folder: 'folder-html'
        },
        input: {
            title: 'input.css',
            desc: 'File sumber CSS. File ini memanggil Tailwind sebelum diproses menjadi output.css.',
            code: '@import "tailwindcss";',
            folder: 'folder-input'
        },
        output: {
            title: 'output.css',
            desc: 'File hasil build. File ini dibuat oleh Tailwind CLI dan dipakai oleh browser.',
            code: '/* Hasil proses build Tailwind */\n.bg-slate-100 { background-color: #f1f5f9; }\n.p-6 { padding: 1.5rem; }\n.text-2xl { font-size: 1.5rem; }',
            folder: 'folder-output'
        },
        package: {
            title: 'package.json',
            desc: 'File informasi proyek dan daftar paket yang dipasang melalui NPM.',
            code: '{\n  "scripts": {\n    "dev": "npx @tailwindcss/cli -i ./src/input.css -o ./src/output.css --watch"\n  },\n  "devDependencies": {\n    "tailwindcss": "latest",\n    "@tailwindcss/cli": "latest"\n  }\n}',
            folder: 'folder-package'
        }
    };

    const flowStages = {
        1: {
            code: '<section class="bg-slate-100 p-6 rounded-xl">\n  <h1 class="text-2xl font-bold">Halo Tailwind</h1>\n</section>',
            desc: 'HTML berisi class Tailwind. Class ini menjadi bahan yang akan dibaca saat proses build berjalan.'
        },
        2: {
            code: '@import "tailwindcss";',
            desc: 'input.css menjadi pintu masuk Tailwind. File ini belum digunakan langsung oleh HTML.'
        },
        3: {
            code: 'npx @tailwindcss/cli -i ./src/input.css -o ./src/output.css --watch',
            desc: 'Tailwind CLI memproses input.css dan class yang dipakai di HTML, lalu menulis hasilnya ke output.css.'
        },
        4: {
            code: '<link rel="stylesheet" href="./output.css">',
            desc: 'output.css dihubungkan ke HTML. Browser membaca file ini untuk menampilkan gaya Tailwind.'
        }
    };

    document.addEventListener('DOMContentLoaded', () => {
        initScrollSpy();
        initVisualEffects();
        initLessonObserver();
        updateProgressUI(false);
        selectProjectFile('html');
        setFlow(1);
        setBuildStage(1);
        initInstallOrderActivity();

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

    function highlightAnchor(id) {
        if (!id) return;
        document.querySelectorAll('.nav-item').forEach(item => item.classList.remove('active'));
        const active = document.querySelector(`.nav-item[data-target="#${id}"]`);
        if (active) active.classList.add('active');
    }

    function runCheck(type) {
        const terminal = document.getElementById('check-terminal');
        if (!terminal) return;
        if (type === 'reset') {
            terminal.innerHTML = '<div class="text-slate-400">$ Siapkan pengecekan prasyarat instalasi...</div>';
            setStatus('node-status', 'Belum dicek', false);
            setStatus('npm-status', 'Belum dicek', false);
            document.getElementById('ready-status').innerHTML = '<p class="text-[11px] text-muted leading-relaxed">Status siap muncul setelah Node.js dan NPM berhasil dicek.</p>';
            return;
        }
        if (type === 'node') {
            terminal.innerHTML += '<div class="text-cyan-300 mt-2">$ node -v</div><div class="text-emerald-300">v20.11.1 — Node.js tersedia.</div>';
            setStatus('node-status', 'Siap', true);
        }
        if (type === 'npm') {
            terminal.innerHTML += '<div class="text-blue-300 mt-2">$ npm -v</div><div class="text-emerald-300">10.2.4 — NPM tersedia.</div>';
            setStatus('npm-status', 'Siap', true);
        }
        const nodeReady = document.getElementById('node-status')?.classList.contains('active');
        const npmReady = document.getElementById('npm-status')?.classList.contains('active');
        if (nodeReady && npmReady) {
            document.getElementById('ready-status').innerHTML = '<p class="text-[11px] text-emerald-600 dark:text-emerald-400 font-bold leading-relaxed">Prasyarat siap. Proyek dapat dilanjutkan ke instalasi Tailwind CSS.</p>';
        }
        terminal.scrollTop = terminal.scrollHeight;
    }

    function setStatus(id, text, ready) {
        const box = document.getElementById(id);
        if (!box) return;
        box.classList.toggle('active', ready);
        const badge = box.querySelector('span');
        if (badge) {
            badge.innerText = text;
            badge.className = ready ? 'text-[10px] px-2 py-1 rounded bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 font-bold' : 'text-[10px] px-2 py-1 rounded bg-slate-100 dark:bg-white/10 text-muted font-bold';
        }
    }

    function selectProjectFile(type) {
        Object.keys(projectFiles).forEach(k => {
            document.getElementById('file-' + k)?.classList.remove('active', 'border-cyan-500', 'bg-cyan-50', 'dark:bg-cyan-500/10');
            document.getElementById(projectFiles[k].folder)?.classList.remove('active');
        });
        const item = projectFiles[type];
        document.getElementById('file-' + type)?.classList.add('border-cyan-500', 'bg-cyan-50', 'dark:bg-cyan-500/10');
        document.getElementById(item.folder)?.classList.add('active');
        document.getElementById('file-title').innerText = item.title;
        document.getElementById('file-desc').innerText = item.desc;
        document.getElementById('file-code').innerText = item.code;
    }

    function setFlow(stage) {
        [1,2,3,4].forEach(n => document.getElementById('flow-' + n)?.classList.remove('active', 'border-cyan-500'));
        document.getElementById('flow-' + stage)?.classList.add('active', 'border-cyan-500');
        document.getElementById('flow-code').innerText = flowStages[stage].code;
        document.getElementById('flow-desc').innerText = flowStages[stage].desc;
    }

    function runInstallStep(step) {
        const terminal = document.getElementById('install-terminal');
        if (!terminal) return;
        if (step === 0) {
            terminal.innerHTML = '<div class="text-slate-400">$ Terminal siap menjalankan perintah instalasi.</div>';
            [1,2,3].forEach(n => document.getElementById('install-step-' + n)?.classList.remove('active', 'border-cyan-500'));
            ['after-package','after-lock','after-node'].forEach(id => document.getElementById(id)?.classList.add('text-slate-400'));
            return;
        }
        const lines = {
            1: '<div class="text-cyan-300 mt-2">$ cd belajar-tailwind</div><div class="text-slate-300">Berhasil masuk ke folder proyek.</div>',
            2: '<div class="text-cyan-300 mt-2">$ npm init -y</div><div class="text-emerald-300">package.json berhasil dibuat.</div>',
            3: '<div class="text-cyan-300 mt-2">$ npm install tailwindcss @tailwindcss/cli</div><div class="text-emerald-300">Tailwind CSS dan CLI berhasil dipasang.</div>'
        };
        terminal.innerHTML += lines[step];
        document.getElementById('install-step-' + step)?.classList.add('active', 'border-cyan-500');
        if (step >= 2) document.getElementById('after-package')?.classList.remove('text-slate-400');
        if (step >= 3) {
            document.getElementById('after-lock')?.classList.remove('text-slate-400');
            document.getElementById('after-node')?.classList.remove('text-slate-400');
        }
        terminal.scrollTop = terminal.scrollHeight;
    }

    function setBuildStage(stage) {
        setFlow(stage);
        [1,2,3,4].forEach(n => document.getElementById('build-pill-' + n)?.classList.remove('active', 'border-cyan-500'));
        document.getElementById('build-pill-' + stage)?.classList.add('active', 'border-cyan-500');
        document.getElementById('build-code').innerText = flowStages[stage].code;
        document.getElementById('build-desc').innerText = flowStages[stage].desc;
    }

    function chooseActivity(select, q) {
        if (activityCompleted) return;
        activityAnswers[q] = select.value;
        select.classList.remove('valid', 'invalid');
    }

    function initInstallOrderActivity() {
        activityWidget = CourseActivityKit.mountDragOrderActivity({
            root: '#activityForm',
            badge: 'Drag n Drop Instalasi',
            title: 'Perhatikan susunan alur instalasi berikut!',
            description: 'Geser kartu untuk membentuk urutan instalasi dan proses build dari proyek kosong hingga file output.css dipakai browser!',
            minScore: 4,
            initialOrder: ['install', 'output', 'node', 'input', 'package'],
            correctOrder: ['node', 'package', 'install', 'input', 'output'],
            items: [
                {
                    id: 'node',
                    title: 'Periksa Node.js',
                    desc: 'Pastikan Node.js tersedia sebelum menjalankan perintah NPM.',
                    code: 'node -v',
                    preview: 'Lingkungan siap menjalankan NPM dan Tailwind CLI.'
                },
                {
                    id: 'package',
                    title: 'Inisialisasi proyek',
                    desc: 'Buat package.json sebagai identitas proyek dan tempat menyimpan dependency.',
                    code: 'npm init -y',
                    preview: 'Proyek memiliki package.json.'
                },
                {
                    id: 'install',
                    title: 'Pasang Tailwind dan CLI',
                    desc: 'Instal paket yang diperlukan agar Tailwind dapat diproses melalui terminal.',
                    code: 'npm install tailwindcss @tailwindcss/cli',
                    preview: 'Dependency Tailwind tersedia pada proyek.'
                },
                {
                    id: 'input',
                    title: 'Siapkan file input.css',
                    desc: 'Tambahkan import Tailwind sebagai sumber CSS yang akan diproses.',
                    code: '@import "tailwindcss";',
                    preview: 'input.css menjadi titik masuk pemrosesan Tailwind.'
                },
                {
                    id: 'output',
                    title: 'Jalankan build ke output.css',
                    desc: 'Proses input.css menjadi output.css, lalu hubungkan output.css ke HTML.',
                    code: 'npx @tailwindcss/cli -i ./src/input.css -o ./src/output.css --watch',
                    preview: 'Browser membaca output.css yang berisi utility class terpakai.'
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

        scoreLabel.innerText = `Skor: ${result.score}/${result.total}`;
        document.getElementById('activity-analysis').classList.toggle('hidden', !result.passed);

        if (result.passed) {
            status.innerText = 'Berhasil. Alur instalasi Tailwind sudah valid.';
            status.className = 'text-xs font-bold text-emerald-600 dark:text-emerald-400';
            activityCompleted = true;
            await saveLessonToDB(ACTIVITY_LESSON_ID);
            lockActivityUI();
            unlockNextChapter();
        } else {
            status.innerText = 'Belum berhasil. Susun kembali urutan instalasi dan proses build, lalu periksa lagi.';
            status.className = 'text-xs font-bold text-amber-600 dark:text-amber-400';
            submit.classList.add('shake');
            setTimeout(() => submit.classList.remove('shake'), 500);
        }
    }

    function lockActivityUI() {
        const overlay = document.getElementById('lockOverlay');
        if (overlay) {
            overlay.classList.remove('hidden');
            overlay.classList.add('flex');
        }
        document.querySelectorAll('#activityForm select, #submitBtn').forEach(el => el.disabled = true);
        if (activityWidget) activityWidget.lock();
    }

    function unlockNextChapter() {
        const btn = document.getElementById('nextChapterBtn');
        const icon = document.getElementById('nextIcon');
        const label = document.getElementById('nextLabel');
        if (!btn || !icon || !label) return;
        const nextUrl = "{{ \Illuminate\Support\Facades\Route::has('courses.advantages') ? route('courses.advantages') : '#' }}";
        btn.classList.remove('cursor-not-allowed', 'opacity-50', 'pointer-events-none', 'text-muted');
        btn.classList.add('cursor-pointer', 'text-cyan-600', 'dark:text-cyan-400');
        icon.classList.add('bg-cyan-600', 'text-white', 'border-cyan-600');
        icon.innerHTML = '<svg class="w-4 h-4 md:w-5 md:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>';
        label.innerText = 'Terbuka';
        btn.onclick = () => { window.location.href = nextUrl; };
    }

    function initVisualEffects() {
        const canvas = document.getElementById('stars');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        let stars = [];
        function resize() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
            stars = Array.from({ length: 70 }, () => ({ x: Math.random() * canvas.width, y: Math.random() * canvas.height, r: Math.random() * 1.4 + .2, a: Math.random() * .6 + .2 }));
        }
        function draw() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            stars.forEach(s => {
                ctx.beginPath();
                ctx.arc(s.x, s.y, s.r, 0, Math.PI * 2);
                ctx.fillStyle = `rgba(255,255,255,${s.a})`;
                ctx.fill();
                s.y += .08;
                if (s.y > canvas.height) s.y = 0;
            });
            requestAnimationFrame(draw);
        }
        resize();
        draw();
        window.addEventListener('resize', resize);
    }
</script>
@endsection
