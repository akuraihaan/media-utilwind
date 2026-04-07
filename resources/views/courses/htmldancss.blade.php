@extends('layouts.landing')
@section('title','Konsep Dasar HTML dan CSS')

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
    /* --- THEME CONFIG (DYNAMIC GLASSMORPHISM) --- */
    :root { 
        --bg-main: #f8fafc;
        --text-main: #0f172a;
        --glass-bg: rgba(255, 255, 255, 0.85); 
        --glass-border: rgba(0, 0, 0, 0.05);
        --glass-header: rgba(255, 255, 255, 0.85);
        --card-bg: #ffffff;
        --card-hover: rgba(0, 0, 0, 0.02);
        --border-color: rgba(0, 0, 0, 0.1);
        --text-muted: #64748b;
        --text-heading: #0f172a;
        --code-bg: #f1f5f9;
        --simulator-bg: #ffffff;
        --accent: #6366f1; /* Indigo 500 */
        --accent-glow: rgba(99, 102, 241, 0.3);
    }

    .dark {
        --bg-main: #020617;
        --text-main: #e2e8f0;
        --glass-bg: rgba(10, 14, 23, 0.85); 
        --glass-border: rgba(255, 255, 255, 0.05);
        --glass-header: rgba(2, 6, 23, 0.80);
        --card-bg: #1e1e1e;
        --card-hover: rgba(255, 255, 255, 0.02);
        --border-color: rgba(255, 255, 255, 0.1);
        --text-muted: rgba(255, 255, 255, 0.5);
        --text-heading: #ffffff;
        --code-bg: #252525;
        --simulator-bg: #0b0f19;
        --accent-glow: rgba(99, 102, 241, 0.5);
    }

    body { font-family: 'Inter', sans-serif; background-color: var(--bg-main); color: var(--text-main); transition: background-color 0.4s, color 0.4s; }
    .font-mono { font-family: 'JetBrains Mono', monospace; }

    /* UTILITIES ADAPTIF */
    .bg-adaptive { background-color: var(--bg-main); }
    .text-adaptive { color: var(--text-main); }
    .text-heading { color: var(--text-heading); }
    .text-muted { color: var(--text-muted); }
    .border-adaptive { border-color: var(--border-color); }
    .card-adaptive { background-color: var(--card-bg); border-color: var(--glass-border); transition: all 0.3s; }
    .card-adaptive:hover { border-color: var(--accent-glow); }
    .sim-bg-adaptive { background-color: var(--simulator-bg); }
    .code-adaptive { background-color: var(--code-bg); border-color: var(--glass-border); }

    /* HIGHLIGHT TERM UTILITY */
    .hl-term {
        background-color: rgba(99, 102, 241, 0.15);
        color: #4f46e5;
        padding: 0.125rem 0.375rem;
        border-radius: 0.375rem;
        font-weight: 600;
        font-style: normal;
        white-space: nowrap;
        border: 1px solid rgba(99, 102, 241, 0.3);
    }
    .dark .hl-term {
        background-color: rgba(99, 102, 241, 0.2);
        color: #818cf8;
        border: 1px solid rgba(99, 102, 241, 0.4);
    }

    /* SCROLLBAR */
    .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(150,150,150,0.3); border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: var(--accent); }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }
    
    /* ANIMATIONS & EFFECTS */
    #animated-bg { 
        background: radial-gradient(600px circle at 20% 20%, rgba(168,85,247,.10), transparent 40%), 
                    radial-gradient(700px circle at 80% 30%, rgba(217,70,239,.10), transparent 40%), 
                    radial-gradient(800px circle at 50% 80%, rgba(34,211,238,.10), transparent 40%); 
        animation: bgMove 20s ease-in-out infinite alternate; 
    }
    .dark #animated-bg {
        background: radial-gradient(600px circle at 20% 20%, rgba(168,85,247,.15), transparent 40%), 
                    radial-gradient(700px circle at 80% 30%, rgba(217,70,239,.15), transparent 40%), 
                    radial-gradient(800px circle at 50% 80%, rgba(34,211,238,.15), transparent 40%); 
    }
    @keyframes bgMove{to{transform:scale(1.15)}}
    @keyframes shake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-5px); } 75% { transform: translateX(5px); } }
    .shake { animation: shake 0.4s ease-in-out; }
    
    /* SCROLLSPY SIDEBAR ACTIVE */
    .nav-item { display: flex; width: 100%; text-align: left; align-items: center; gap: 12px; padding: 10px 14px; font-size: 0.85rem; color: var(--text-muted); border-radius: 8px; transition: all 0.2s; position: relative; }
    .nav-item:hover { color: var(--text-main); background: var(--card-hover); }
    .nav-item.active { color: #6366f1; background: rgba(99, 102, 241, 0.05); font-weight: 600; }
    .dot { width: 6px; height: 6px; border-radius: 50%; background: #94a3b8; transition: all 0.3s; }
    .dark .dot { background: #334155; }
    .nav-item.active .dot { background: #6366f1; box-shadow: 0 0 8px #6366f1; transform: scale(1.2); }
</style>

<div id="courseRoot" class="relative h-screen bg-adaptive text-adaptive font-sans overflow-hidden flex flex-col selection:bg-indigo-500/30 pt-20 transition-colors duration-500">

    {{-- BACKGROUND EFFECTS --}}
    <div class="fixed inset-0 -z-50 pointer-events-none">
        <div id="animated-bg" class="absolute inset-0 opacity-50 transition-opacity"></div>
        <div class="absolute top-[-20%] left-[-10%] w-[1000px] h-[1000px] bg-indigo-500/5 dark:bg-indigo-900/10 rounded-full blur-[120px] animate-pulse transition-colors"></div>
        <div class="absolute bottom-[-20%] right-[-10%] w-[800px] h-[800px] bg-purple-500/5 dark:bg-purple-900/10 rounded-full blur-[100px] transition-colors"></div>
        <canvas id="stars" class="absolute inset-0 pointer-events-none opacity-0 dark:opacity-100 transition-opacity duration-500"></canvas>
        <div id="noise-overlay" class="absolute inset-0 z-10 opacity-[0.02] dark:opacity-[0.04]"></div>
    </div>

    @include('layouts.partials.navbar')

    {{-- WRAPPER FLEX-1 OVERFLOW-HIDDEN UNTUK SCROLLING INDEPENDEN --}}
    <div class="flex flex-1 overflow-hidden relative z-20 h-full">

        @include('layouts.partials.course-sidebar')

        {{-- MAIN CONTENT SCROLL AREA --}}
        <main id="mainScroll" class="flex-1 h-full overflow-y-auto scroll-smooth relative bg-transparent custom-scrollbar scroll-padding-top-24">
            
            {{-- STICKY HEADER & PROGRESS BAR --}}
            <div id="stickyHeader" class="sticky top-0 z-30 w-full backdrop-blur-2xl border-b border-adaptive px-4 md:px-8 py-4 flex items-center justify-between transition-colors duration-500" style="background-color: var(--glass-header);">
                <div class="flex items-center gap-4">
                    <div class="w-8 h-8 rounded-lg bg-indigo-500/10 dark:bg-indigo-500/20 border border-indigo-500/20 flex items-center justify-center font-bold text-xs text-indigo-600 dark:text-indigo-400 transition-colors shrink-0">1.1</div>
                    <div>
                        <h1 class="text-sm font-bold text-heading transition-colors line-clamp-1">Konsep Dasar HTML & CSS</h1>
                        <p class="text-[10px] text-muted transition-colors line-clamp-1">Mastering the Building Blocks</p>
                    </div>
                </div>
                
                {{-- DYNAMIC PROGRESS BAR UI --}}
                <div class="flex items-center gap-3 shrink-0">
                    <div class="hidden sm:block w-24 md:w-32 h-1.5 bg-slate-200 dark:bg-white/10 rounded-full overflow-hidden transition-colors">
                        <div id="topProgressBar" class="h-full bg-gradient-to-r from-indigo-500 to-purple-500 w-0 transition-all duration-500 shadow-[0_0_10px_rgba(99,102,241,0.5)]"></div>
                    </div>
                    <span id="progressLabelTop" class="text-indigo-600 dark:text-indigo-400 font-bold text-xs transition-colors">0%</span>
                </div>
            </div>

            <div class="p-4 sm:p-6 lg:p-16 max-w-5xl mx-auto pb-40">
                
                {{-- TUJUAN PEMBELAJARAN --}}
                <div class="mb-16 md:mb-24">
                    <h3 class="text-xl font-bold text-heading mb-6 flex items-center gap-2 transition-colors">
                        <svg class="w-5 h-5 text-indigo-500 dark:text-indigo-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Tujuan Pembelajaran
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                        <div class="card-adaptive p-5 rounded-xl flex items-start gap-4 group h-full">
                            <div class="w-8 h-8 rounded-lg bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0 font-bold transition-colors">1</div>
                            <div><h4 class="text-sm font-bold text-heading mb-1 transition-colors">Struktur DOM</h4><p class="text-[11px] text-muted leading-relaxed transition-colors">Memahami hierarki elemen dan representasi pohon Node.</p></div>
                        </div>
                        <div class="card-adaptive p-5 rounded-xl flex items-start gap-4 group h-full hover:border-purple-500/30">
                            <div class="w-8 h-8 rounded-lg bg-purple-500/10 text-purple-600 dark:text-purple-400 flex items-center justify-center shrink-0 font-bold transition-colors">2</div>
                            <div><h4 class="text-sm font-bold text-heading mb-1 transition-colors">Semantik & Atribut</h4><p class="text-[11px] text-muted leading-relaxed transition-colors">Menerapkan Tag bermakna untuk optimasi SEO dan Aksesibilitas.</p></div>
                        </div>
                        <div class="card-adaptive p-5 rounded-xl flex items-start gap-4 group h-full hover:border-pink-500/30">
                            <div class="w-8 h-8 rounded-lg bg-pink-500/10 text-pink-600 dark:text-pink-400 flex items-center justify-center shrink-0 font-bold transition-colors">3</div>
                            <div><h4 class="text-sm font-bold text-heading mb-1 transition-colors">Sintaksis CSS</h4><p class="text-[11px] text-muted leading-relaxed transition-colors">Menguasai penyeleksi elemen, properti kaskade, dan spesifisitas.</p></div>
                        </div>
                        <div class="card-adaptive p-5 rounded-xl flex items-start gap-4 group h-full hover:border-blue-500/30">
                            <div class="w-8 h-8 rounded-lg bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0 font-bold transition-colors">4</div>
                            <div><h4 class="text-sm font-bold text-heading mb-1 transition-colors">Warna & Tipografi</h4><p class="text-[11px] text-muted leading-relaxed transition-colors">Mengatur ruang warna visual dan unit satuan metrik tipografi.</p></div>
                        </div>
                        <div class="card-adaptive p-5 rounded-xl flex items-start gap-4 group h-full hover:border-orange-500/30">
                            <div class="w-8 h-8 rounded-lg bg-orange-500/10 text-orange-600 dark:text-orange-400 flex items-center justify-center shrink-0 font-bold transition-colors">5</div>
                            <div><h4 class="text-sm font-bold text-heading mb-1 transition-colors">The Box Model</h4><p class="text-[11px] text-muted leading-relaxed transition-colors">Konsep layout absolut: Margin, Border, dan Padding.</p></div>
                        </div>
                        <div class="bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-indigo-900/40 dark:to-purple-900/40 border border-indigo-200 dark:border-indigo-500/30 p-5 rounded-xl flex items-start gap-4 hover:shadow-[0_0_20px_rgba(99,102,241,0.2)] transition group h-full cursor-default sm:col-span-2 md:col-span-1">
                            <div class="w-8 h-8 rounded-lg bg-white/50 dark:bg-white/10 text-indigo-600 dark:text-white flex items-center justify-center shrink-0 font-bold shadow-sm dark:shadow-none">🏁</div>
                            <div><h4 class="text-sm font-bold text-indigo-900 dark:text-white mb-1 transition-colors">Final Mission</h4><p class="text-[11px] text-indigo-700 dark:text-white/70 leading-relaxed transition-colors">Membangun komponen Kartu Produk interaktif secara presisi.</p></div>
                        </div>
                    </div>
                </div>

                <article class="space-y-32 md:space-y-40">
                    
                    {{-- LESSON 1 --}}
                    <section id="section-1" class="lesson-section scroll-mt-32" data-lesson-id="1">
                        <div class="space-y-8 md:space-y-10">
                            <div class="space-y-4 border-l-4 border-indigo-500 pl-4 md:pl-6">
                                <span class="text-indigo-600 dark:text-indigo-400 font-mono text-xs uppercase tracking-widest transition-colors">Lesson 1.1.1</span>
                                <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-heading leading-[1.1] transition-colors">
                                    Arsitektur Web & <br> 
                                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-purple-600 dark:from-indigo-400 dark:to-purple-500">Struktur DOM</span>
                                </h2>
                            </div>
                            
                            <div class="space-y-4">
                                <h3 class="text-base md:text-lg font-bold text-heading flex items-center gap-2 transition-colors"><span class="w-5 h-5 rounded bg-indigo-500 flex items-center justify-center text-[10px] text-white shrink-0">A</span> Siklus Request-Response</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-sm md:text-base leading-relaxed space-y-4 transition-colors text-justify">
                                    <p>Internet beroperasi melalui siklus komunikasi antara <strong>Client</strong> (browser pengguna) dan <strong>Server</strong> (komputer penyimpan data website).</p>
                                    <p>Saat Anda menavigasi ke sebuah URL, browser mengirimkan <em>HTTP Request</em>. Server membalasnya (<em>HTTP Response</em>) dengan mengirimkan dokumen <strong>HTML (HyperText Markup Language)</strong>. HTML adalah bahasa markah penyusun struktur dasar halaman; tanpa HTML, CSS tidak memiliki kerangka untuk dihias, dan JavaScript tidak memiliki objek untuk dimanipulasi.</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-base md:text-lg font-bold text-heading flex items-center gap-2 transition-colors"><span class="w-5 h-5 rounded bg-purple-500 flex items-center justify-center text-[10px] text-white shrink-0">B</span> Document Object Model (DOM)</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-sm md:text-base leading-relaxed space-y-4 transition-colors text-justify">
                                    <p>Browser mengonversi baris kode HTML menjadi struktur memori logis berbentuk pohon yang disebut <span class="hl-term">DOM (Document Object Model)</span>. Dalam DOM, setiap elemen HTML menjadi sebuah <strong>Node</strong>.</p>
                                    <p>Konsep terpenting dalam DOM adalah <strong>Nesting</strong> (Sistem Bersarang). Elemen pembungkus disebut <span class="hl-term">Parent</span> (Induk), dan elemen di dalamnya disebut <span class="hl-term">Child</span> (Anak). Hierarki ini sangat penting karena gaya visual (CSS) dari <em>Parent</em> umumnya akan diwariskan ke bawah kepada para <em>Child</em>-nya.</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-base md:text-lg font-bold text-heading flex items-center gap-2 transition-colors"><span class="w-5 h-5 rounded bg-pink-500 flex items-center justify-center text-[10px] text-white shrink-0">C</span> Block vs Inline Elements</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-sm md:text-base leading-relaxed space-y-4 transition-colors text-justify">
                                    <p>Secara default aliran dokumen (Document Flow), HTML membagi elemen menjadi dua jenis:</p>
                                    <ul class="list-disc pl-5 space-y-2">
                                        <li><strong>Block-Level:</strong> Mengambil lebar layar penuh (100%) dan selalu dimulai di baris baru. Contoh: <code>&lt;div&gt;</code>, <code>&lt;h1&gt;</code>, <code>&lt;p&gt;</code>.</li>
                                        <li><strong>Inline-Level:</strong> Hanya mengambil lebar sesuai konten di dalamnya dan tidak membuat baris baru. Contoh: <code>&lt;span&gt;</code>, <code>&lt;a&gt;</code>.</li>
                                    </ul>
                                </div>
                            </div>

                            {{-- SIMULATOR DOM --}}
                            <div class="sim-bg-adaptive border border-adaptive rounded-xl overflow-hidden shadow-xl dark:shadow-2xl flex flex-col transition-colors mt-8">
                                
                                {{-- KOTAK INSTRUKSI SIMULATOR 1 (Static/Normal Flow) --}}
                                <div class="w-full bg-indigo-600/95 dark:bg-indigo-900/95 text-white p-4 shrink-0 flex flex-col sm:flex-row sm:justify-between items-start sm:items-center shadow-sm gap-3 sm:gap-0 border-b border-indigo-400 dark:border-indigo-700 z-20">
                                    <div class="flex items-center gap-2 text-[11px] sm:text-xs font-bold shrink-0">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        PANDUAN SIMULASI
                                    </div>
                                    <p class="text-[10px] sm:text-[11px] opacity-90 m-0 leading-tight sm:text-right sm:max-w-md">Klik tombol di bawah untuk menyisipkan elemen ke dalam struktur DOM dan lihat hasil rendernya di panel kanan.</p>
                                </div>

                                <div class="flex flex-col lg:flex-row min-h-[350px]">
                                    <div class="w-full lg:w-1/2 code-adaptive border-b lg:border-b-0 lg:border-r border-adaptive flex flex-col transition-colors z-10 shrink-0">
                                        <div class="bg-black/5 dark:bg-[#252525] px-4 py-3 border-b border-adaptive flex justify-between transition-colors shrink-0">
                                            <span class="text-[10px] sm:text-xs font-mono font-bold text-slate-500 dark:text-gray-400">index.html</span>
                                        </div>
                                        <div class="flex-1 p-4 sm:p-6 font-mono text-[11px] sm:text-sm overflow-auto custom-scrollbar">
                                            <div class="text-purple-600 dark:text-purple-400 font-bold">&lt;body&gt;</div>
                                            <div id="dom-code-area" class="pl-4 border-l border-slate-300 dark:border-white/10 ml-1 space-y-2 my-4 min-h-[100px] transition-colors"><span class="text-slate-400 dark:text-gray-600 italic">/* Menunggu injeksi elemen... */</span></div>
                                            <div class="text-purple-600 dark:text-purple-400 font-bold">&lt;/body&gt;</div>
                                        </div>
                                        <div class="p-4 bg-black/5 dark:bg-[#252525] border-t border-adaptive flex flex-wrap gap-2 transition-colors shrink-0">
                                            <button onclick="toggleDom('nav')" class="px-4 py-2 bg-indigo-100 dark:bg-indigo-600/20 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-500/30 rounded text-[10px] sm:text-xs hover:bg-indigo-200 dark:hover:bg-indigo-600/40 transition-colors font-bold shadow-sm">+ Tambah Nav</button>
                                            <button onclick="toggleDom('h1')" class="px-4 py-2 bg-purple-100 dark:bg-purple-600/20 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-500/30 rounded text-[10px] sm:text-xs hover:bg-purple-200 dark:hover:bg-purple-600/40 transition-colors font-bold shadow-sm">+ Tambah H1</button>
                                            <button onclick="toggleDom('p')" class="px-4 py-2 bg-pink-100 dark:bg-pink-600/20 text-pink-700 dark:text-pink-300 border border-pink-200 dark:border-pink-500/30 rounded text-[10px] sm:text-xs hover:bg-pink-200 dark:hover:bg-pink-600/40 transition-colors font-bold shadow-sm">+ Tambah Paragraf</button>
                                        </div>
                                    </div>
                                    <div class="w-full lg:w-1/2 bg-white dark:bg-black/20 flex flex-col relative transition-colors z-10 min-h-[250px] shrink-0">
                                        <div class="w-full flex justify-between p-3 border-b border-adaptive bg-slate-100 dark:bg-[#111] shrink-0">
                                            <span class="bg-white dark:bg-black/50 px-3 py-1 rounded text-[9px] sm:text-[10px] text-slate-500 dark:text-gray-400 shadow-sm border border-slate-200 dark:border-white/10 font-mono tracking-widest uppercase font-bold">Browser Preview</span>
                                        </div>
                                        <div id="dom-preview-area" class="flex-1 p-6 sm:p-8 overflow-auto flex items-center justify-center">
                                            <div class="text-center text-slate-400 dark:text-white/20 italic text-[11px] sm:text-sm font-bold">Halaman Kosong</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- KESIMPULAN SIMULASI 1 --}}
                            <div class="mt-4 bg-gradient-to-r from-indigo-50 to-transparent dark:from-indigo-900/20 dark:to-transparent border-l-4 border-indigo-500 p-4 rounded-r-xl relative z-10 transition-colors">
                                <h5 class="text-[11px] sm:text-xs font-bold text-indigo-700 dark:text-indigo-400 mb-1 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> 
                                    Kesimpulan
                                </h5>
                                <p class="text-[10px] sm:text-[11px] text-slate-700 dark:text-slate-300 leading-relaxed text-justify m-0">
                                    DOM mengeksekusi elemen secara linier (dari atas ke bawah). Elemen <em>Block-Level</em> secara otomatis mengisi lebar ruang yang tersedia, menciptakan fondasi kanvas sebelum dipoles oleh CSS.
                                </p>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 2 --}}
                    <section id="section-2" class="lesson-section scroll-mt-32" data-lesson-id="2">
                        <div class="space-y-8 md:space-y-10">
                            <div class="space-y-4 border-l-4 border-indigo-500 pl-4 md:pl-6">
                                <span class="text-indigo-600 dark:text-indigo-400 font-mono text-xs uppercase tracking-widest transition-colors">Lesson 1.1.2</span>
                                <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-heading leading-[1.1] transition-colors">
                                    Semantik & <br> 
                                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-purple-600 dark:from-indigo-400 dark:to-purple-500">Atribut HTML</span>
                                </h2>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-base md:text-lg font-bold text-heading flex items-center gap-2 transition-colors"><span class="w-5 h-5 rounded bg-indigo-500 flex items-center justify-center text-[10px] text-white shrink-0">A</span> Filosofi Semantic Web</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-sm md:text-base leading-relaxed space-y-4 transition-colors text-justify">
                                    <p>HTML harus ditulis dengan integritas struktural (Semantic). Alih-alih membungkus semua elemen menggunakan tag generik <code>&lt;div&gt;</code> (yang sering disebut <span class="hl-term">Div Soup</span>), praktik modern mewajibkan penggunaan tag yang memiliki arti.</p>
                                    <p>Gunakan <code>&lt;header&gt;</code> untuk bagian atas, <code>&lt;nav&gt;</code> untuk menu navigasi, <code>&lt;main&gt;</code> untuk konten utama, dan <code>&lt;article&gt;</code> untuk blok konten independen. Hal ini mempermudah <strong>Search Engine (SEO)</strong> dalam memahami konteks halaman dan sangat membantu teknologi <em>Screen Reader</em> bagi pengguna disabilitas visual.</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-base md:text-lg font-bold text-heading flex items-center gap-2 transition-colors"><span class="w-5 h-5 rounded bg-purple-500 flex items-center justify-center text-[10px] text-white shrink-0">B</span> Atribut Penanda Spesifik</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-sm md:text-base leading-relaxed space-y-4 transition-colors text-justify">
                                    <p>Atribut digunakan untuk menyuntikkan konfigurasi ekstra ke dalam tag HTML. Atribut ditulis di dalam tag pembuka dengan format <code>kunci="nilai"</code>.</p>
                                    <p>Terdapat atribut fungsional seperti <code>src</code> pada tag gambar dan <code>href</code> pada tag tautan. Selain itu, ada atribut global seperti <code>id</code> (identifikasi unik) dan <code>class</code> (identifikasi grup) yang difungsikan sebagai penanda target bagi instruksi CSS atau JavaScript.</p>
                                </div>
                            </div>

                            {{-- SIMULATOR ATRIBUT --}}
                            <div class="card-adaptive rounded-xl overflow-hidden shadow-xl dark:shadow-2xl flex flex-col transition-colors border-adaptive mt-8">
                                
                                {{-- KOTAK INSTRUKSI SIMULATOR 2 --}}
                                <div class="w-full bg-purple-600/95 dark:bg-purple-900/95 text-white p-4 shrink-0 flex flex-col sm:flex-row sm:justify-between items-start sm:items-center shadow-sm gap-3 sm:gap-0 border-b border-purple-400 dark:border-purple-700 z-20">
                                    <div class="flex items-center gap-2 text-[11px] sm:text-xs font-bold shrink-0">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        PANDUAN SIMULASI
                                    </div>
                                    <p class="text-[10px] sm:text-[11px] opacity-90 m-0 leading-tight sm:text-right sm:max-w-md">Ubah opsi <span class="hl-term">src</span> untuk mengganti data file gambar, dan ubah <span class="hl-term">class</span> untuk memodifikasi bentuk objek melalui CSS.</p>
                                </div>

                                <div class="flex flex-col md:flex-row min-h-[350px]">
                                    <div class="w-full md:w-1/2 p-6 flex flex-col gap-6 bg-slate-50 dark:bg-[#18181b] transition-colors border-b md:border-b-0 md:border-r border-adaptive shrink-0">
                                        <div>
                                            <h4 class="text-[11px] sm:text-xs font-bold text-slate-500 dark:text-white/50 uppercase mb-4 transition-colors">Modifikator Atribut</h4>
                                            <div class="space-y-4">
                                                <div>
                                                    <label class="text-[10px] uppercase text-indigo-600 dark:text-indigo-400 font-bold block mb-1.5 transition-colors">Sumber Gambar (src)</label>
                                                    <select id="inp-src" onchange="updateAttrSim()" class="w-full bg-white dark:bg-black/30 border border-slate-300 dark:border-white/10 rounded px-3 py-2.5 text-[11px] sm:text-xs text-adaptive outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-colors shadow-sm dark:shadow-none cursor-pointer">
                                                        <option value="https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=500">setup_komputer.jpg</option>
                                                        <option value="https://images.unsplash.com/photo-1518791841217-8f162f1e1131?w=500">kucing_lucu.jpg</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="text-[10px] uppercase text-amber-600 dark:text-yellow-400 font-bold block mb-1.5 transition-colors">Target CSS (class)</label>
                                                    <select id="inp-class" onchange="updateAttrSim()" class="w-full bg-white dark:bg-black/30 border border-slate-300 dark:border-white/10 rounded px-3 py-2.5 text-[11px] sm:text-xs text-adaptive outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-colors shadow-sm dark:shadow-none cursor-pointer">
                                                        <option value="rounded-none">class="rounded-none"</option>
                                                        <option value="rounded-xl">class="rounded-xl"</option>
                                                        <option value="rounded-full">class="rounded-full"</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="bg-white dark:bg-black/40 p-4 rounded-lg border border-slate-200 dark:border-white/5 font-mono text-[11px] sm:text-sm leading-relaxed shadow-inner transition-colors mt-auto overflow-x-auto custom-scrollbar">
                                            <span class="text-blue-600 dark:text-blue-400 font-bold">&lt;img</span> <br>
                                            &nbsp;&nbsp;<span class="text-indigo-600 dark:text-indigo-400">src</span>="<span id="code-src" class="text-emerald-600 dark:text-green-400">setup_komputer.jpg</span>" <br>
                                            &nbsp;&nbsp;<span class="text-amber-600 dark:text-yellow-400">class</span>="<span id="code-class" class="text-emerald-600 dark:text-green-400">rounded-none</span>" <br>
                                            <span class="text-blue-600 dark:text-blue-400 font-bold">/&gt;</span>
                                        </div>
                                    </div>
                                    <div class="w-full md:w-1/2 bg-slate-200 dark:bg-[#111] flex flex-col relative bg-[url('https://grainy-gradients.vercel.app/noise.svg')] min-h-[250px] shrink-0">
                                        <div class="w-full flex justify-between items-center p-3 border-b border-adaptive/50 bg-white/30 dark:bg-black/20 backdrop-blur z-20 shrink-0">
                                             <span class="text-[9px] sm:text-[10px] text-slate-600 dark:text-white/40 uppercase tracking-widest font-bold">Live Visual Output</span>
                                        </div>
                                        <div class="flex-1 flex items-center justify-center p-6">
                                            <img id="view-attr" src="https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=500" class="w-40 h-40 sm:w-48 sm:h-48 object-cover border-4 border-white dark:border-white/10 shadow-xl transition-all duration-500 rounded-none">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- KESIMPULAN SIMULASI 2 --}}
                            <div class="mt-4 bg-gradient-to-r from-purple-50 to-transparent dark:from-purple-900/20 dark:to-transparent border-l-4 border-purple-500 p-4 rounded-r-xl relative z-10 transition-colors">
                                <h5 class="text-[11px] sm:text-xs font-bold text-purple-700 dark:text-purple-400 mb-1 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> 
                                    Kesimpulan
                                </h5>
                                <p class="text-[10px] sm:text-[11px] text-slate-700 dark:text-slate-300 leading-relaxed text-justify m-0">
                                    Atribut bertindak sebagai jembatan fungsional (memasukkan lokasi media gambar) dan penanda logis (label <code>class</code>) untuk kemudian dimanipulasi secara visual oleh dokumen CSS.
                                </p>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 3 --}}
                    <section id="section-3" class="lesson-section scroll-mt-32" data-lesson-id="3">
                        <div class="space-y-8 md:space-y-10">
                            <div class="space-y-4 border-l-4 border-indigo-500 pl-4 md:pl-6">
                                <span class="text-indigo-600 dark:text-indigo-400 font-mono text-xs uppercase tracking-widest transition-colors">Lesson 1.1.3</span>
                                <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-heading leading-[1.1] transition-colors">
                                    Pengenalan <br> 
                                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-purple-600 dark:from-indigo-400 dark:to-purple-500">Sintaksis CSS</span>
                                </h2>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-base md:text-lg font-bold text-heading flex items-center gap-2 transition-colors"><span class="w-5 h-5 rounded bg-indigo-500 flex items-center justify-center text-[10px] text-white shrink-0">A</span> Separation of Concerns</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-sm md:text-base leading-relaxed space-y-4 transition-colors text-justify">
                                    <p>Prinsip <strong>Separation of Concerns</strong> mengharuskan pemisahan antara kerangka konten (HTML) dan desain visual (CSS). Menulis gaya visual secara eksternal (menggunakan file <code>.css</code>) merupakan praktik standar, di mana browser akan mengolah aturan tersebut ke dalam memori <em>CSSOM (CSS Object Model)</em>.</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-base md:text-lg font-bold text-heading flex items-center gap-2 transition-colors"><span class="w-5 h-5 rounded bg-purple-500 flex items-center justify-center text-[10px] text-white shrink-0">B</span> Selector & Prioritas (Cascading)</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-sm md:text-base leading-relaxed space-y-4 transition-colors text-justify">
                                    <p>Aturan CSS ditulis dalam bentuk blok deklarasi. Anda membidik elemen dengan <span class="hl-term">Selector</span> (seperti pemanggilan `.class` atau `#id`), kemudian menetapkan properti dalam kurung kurawal `{}`.</p>
                                    <p>Istilah "Cascading" pada CSS mengacu pada mekanisme prioritas. Jika terjadi konflik antara dua baris perintah, CSS memenangkan pihak yang memiliki skor spesifisitas lebih tinggi (ID lebih kuat dari Class). Bila skornya seimbang, maka gaya yang dideklarasikan paling terakhir (di bawah) yang akan menimpa konfigurasi sebelumnya.</p>
                                </div>
                            </div>

                            {{-- SIMULATOR CSS --}}
                            <div class="sim-bg-adaptive border border-adaptive rounded-xl overflow-hidden shadow-xl dark:shadow-2xl flex flex-col transition-colors mt-8">
                                
                                {{-- KOTAK INSTRUKSI SIMULATOR 3 --}}
                                <div class="w-full bg-indigo-600/95 dark:bg-indigo-900/95 text-white p-4 shrink-0 flex flex-col sm:flex-row sm:justify-between items-start sm:items-center shadow-sm gap-3 sm:gap-0 border-b border-indigo-400 dark:border-indigo-700 z-20">
                                    <div class="flex items-center gap-2 text-[11px] sm:text-xs font-bold shrink-0">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        PANDUAN SIMULASI
                                    </div>
                                    <p class="text-[10px] sm:text-[11px] opacity-90 m-0 leading-tight sm:text-right sm:max-w-md">Eksperimen dengan warna latar dan nilai <span class="hl-term">border-radius</span>. Perhatikan bagaimana blok properti mengubah Target Selector seketika.</p>
                                </div>

                                <div class="flex flex-col lg:flex-row min-h-[300px]">
                                    <div class="w-full lg:w-1/2 code-adaptive flex flex-col border-b lg:border-b-0 lg:border-r border-adaptive shrink-0">
                                        <div class="bg-black/5 dark:bg-[#252525] px-4 py-3 border-b border-adaptive text-[10px] sm:text-xs text-slate-500 font-mono font-bold flex items-center gap-2 shrink-0"><div class="w-2.5 h-2.5 rounded-full bg-sky-500"></div> style.css</div>
                                        <div class="p-4 sm:p-6 font-mono text-[11px] sm:text-sm space-y-3 flex-1 overflow-x-auto custom-scrollbar">
                                            <div><span class="text-amber-600 dark:text-yellow-400 font-bold">.btn-utama</span> <span class="text-adaptive">{</span></div>
                                            
                                            <div class="pl-2 sm:pl-4 flex items-center gap-2 sm:gap-4 group hover:bg-black/5 dark:hover:bg-white/5 p-1.5 rounded transition-colors w-max sm:w-auto">
                                                <span class="text-blue-600 dark:text-blue-400 w-24 sm:w-28 shrink-0">background:</span>
                                                <div class="flex gap-2.5">
                                                    <button onclick="updateCss('bg', 'indigo')" class="w-6 h-6 sm:w-7 sm:h-7 rounded bg-indigo-600 border-2 border-transparent focus:border-slate-400 dark:focus:border-white focus:ring-2 focus:ring-indigo-300 hover:scale-110 transition shadow-sm cursor-pointer"></button>
                                                    <button onclick="updateCss('bg', 'rose')" class="w-6 h-6 sm:w-7 sm:h-7 rounded bg-rose-600 border-2 border-transparent focus:border-slate-400 dark:focus:border-white focus:ring-2 focus:ring-rose-300 hover:scale-110 transition shadow-sm cursor-pointer"></button>
                                                    <button onclick="updateCss('bg', 'emerald')" class="w-6 h-6 sm:w-7 sm:h-7 rounded bg-emerald-600 border-2 border-transparent focus:border-slate-400 dark:focus:border-white focus:ring-2 focus:ring-emerald-300 hover:scale-110 transition shadow-sm cursor-pointer"></button>
                                                </div>
                                                <span class="text-adaptive">;</span>
                                            </div>
                                            
                                            <div class="pl-2 sm:pl-4 flex items-center gap-2 sm:gap-4 group hover:bg-black/5 dark:hover:bg-white/5 p-1.5 rounded transition-colors w-max sm:w-auto">
                                                <span class="text-blue-600 dark:text-blue-400 w-24 sm:w-28 shrink-0">border-radius:</span>
                                                <input type="range" min="0" max="30" value="8" oninput="updateCss('rad', this.value)" class="w-20 sm:w-28 accent-indigo-600 dark:accent-white h-1.5 bg-slate-200 dark:bg-white/10 rounded-full cursor-pointer shrink-0 focus:outline-none focus:ring-2 focus:ring-indigo-500/50">
                                                <span id="css-val-rad" class="text-emerald-600 dark:text-green-400 text-[10px] sm:text-xs w-8 sm:w-10 font-bold shrink-0">8px</span>
                                                <span class="text-adaptive shrink-0">;</span>
                                            </div>
                                            
                                            <div class="text-adaptive">}</div>
                                        </div>
                                    </div>
                                    <div class="w-full lg:w-1/2 flex flex-col bg-slate-100 dark:bg-[#111] bg-[url('https://grainy-gradients.vercel.app/noise.svg')] min-h-[250px] shrink-0 relative">
                                        <div class="w-full flex justify-start p-3 border-b border-adaptive/50 bg-white/30 dark:bg-black/20 backdrop-blur z-20 shrink-0">
                                             <span class="text-[9px] sm:text-[10px] font-bold text-slate-600 dark:text-white/40 uppercase tracking-widest px-1">Target Element</span>
                                        </div>
                                        <div class="flex-1 flex items-center justify-center p-6">
                                            <button id="css-btn-target" class="text-white font-bold shadow-xl transition-all duration-300 px-6 sm:px-8 py-3 text-sm sm:text-base bg-indigo-600 rounded-[8px] active:scale-95 focus:outline-none focus:ring-4 focus:ring-indigo-300/50">
                                                Klik Tombol Ini
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- KESIMPULAN SIMULASI 3 --}}
                            <div class="mt-4 bg-gradient-to-r from-indigo-50 to-transparent dark:from-indigo-900/20 dark:to-transparent border-l-4 border-indigo-500 p-4 rounded-r-xl relative z-10 transition-colors">
                                <h5 class="text-[11px] sm:text-xs font-bold text-indigo-700 dark:text-indigo-400 mb-1 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> 
                                    Kesimpulan
                                </h5>
                                <p class="text-[10px] sm:text-[11px] text-slate-700 dark:text-slate-300 leading-relaxed text-justify m-0">
                                    CSS bekerja dari luar jangkauan HTML, membidik class <code>.btn-utama</code> dan mengubah visual parameternya tanpa harus memodifikasi langsung struktur HTML asal.
                                </p>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 4 --}}
                    <section id="section-4" class="lesson-section scroll-mt-32" data-lesson-id="4">
                        <div class="space-y-8 md:space-y-10">
                            <div class="space-y-4 border-l-4 border-indigo-500 pl-4 md:pl-6">
                                <span class="text-indigo-600 dark:text-indigo-400 font-mono text-xs uppercase tracking-widest transition-colors">Lesson 1.1.4</span>
                                <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-heading leading-[1.1] transition-colors">
                                    Warna & <br> 
                                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-purple-600 dark:from-indigo-400 dark:to-purple-500">Skala Tipografi</span>
                                </h2>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-base md:text-lg font-bold text-heading flex items-center gap-2 transition-colors"><span class="w-5 h-5 rounded bg-indigo-500 flex items-center justify-center text-[10px] text-white shrink-0">A</span> Sistem Pewarnaan Digital</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-sm md:text-base leading-relaxed space-y-4 transition-colors text-justify">
                                    <p>Terdapat tiga format warna standar di dunia web:</p>
                                    <ul class="list-disc pl-5 space-y-2">
                                        <li><strong>Hexadecimal (Hex):</strong> Kode enam digit klasik (misal: <code>#FF5733</code>) yang membagi persentase Merah, Hijau, dan Biru.</li>
                                        <li><strong>RGB / RGBA:</strong> Menggunakan format desimal (<code>rgb(255, 87, 51)</code>). Akhiran "A" (Alpha) berguna untuk menyetel transparansi.</li>
                                        <li><strong>HSL (Hue, Saturation, Lightness):</strong> Format paling organik dan banyak diadopsi UI/UX designer. Sangat intuitif untuk mengatur tingkat terang/gelap dan saturasi persentase warna tanpa harus mengubah rona (hue) utama.</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-base md:text-lg font-bold text-heading flex items-center gap-2 transition-colors"><span class="w-5 h-5 rounded bg-purple-500 flex items-center justify-center text-[10px] text-white shrink-0">B</span> Metrik Tipografi Responsif</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-sm md:text-base leading-relaxed space-y-4 transition-colors text-justify">
                                    <p>Membentuk tipografi yang nyaman dibaca (Readability) jauh lebih penting dari sekadar estetika font. Aturan aksesibilitas modern mengamanatkan kita untuk <strong>menghindari ukuran tetap seperti piksel (<code>px</code>)</strong> dalam ukuran teks teks.</p>
                                    <p>Praktik terbaik menggunakan <code>rem</code> (Root Em). Satuan relatif ini memastikan ukuran huruf akan beradaptasi secara otomatis mengikuti pengaturan <em>accessibility zoom</em> di browser sang pengguna.</p>
                                </div>
                            </div>

                            {{-- SIMULATOR BRAND --}}
                            <div class="code-adaptive border border-adaptive rounded-xl overflow-hidden shadow-xl dark:shadow-2xl mt-8 flex flex-col">
                                
                                {{-- KOTAK INSTRUKSI SIMULATOR 4 --}}
                                <div class="bg-indigo-600/95 dark:bg-indigo-900/95 text-white p-4 shrink-0 flex flex-col sm:flex-row gap-3 sm:items-center justify-between border-b border-indigo-400 dark:border-indigo-700 z-20">
                                    <div class="flex items-center gap-2 text-[11px] sm:text-xs font-bold shrink-0">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                        PANDUAN SIMULASI
                                    </div>
                                    <p class="text-[10px] sm:text-[11px] opacity-90 m-0 leading-tight sm:text-right sm:max-w-md">Modifikasi <span class="hl-term">Primary Hex</span> dan geser rasio unit <span class="hl-term">REM</span> untuk melihat perubahan kode dan output-nya secara instan.</p>
                                </div>

                                <div class="flex flex-col lg:flex-row min-h-[300px]">
                                    <div class="w-full lg:w-1/3 p-6 border-b lg:border-b-0 lg:border-r border-adaptive flex flex-col justify-center shrink-0">
                                        <h4 class="text-[10px] sm:text-[11px] font-bold text-slate-500 dark:text-white/50 uppercase tracking-widest transition-colors text-center lg:text-left mb-6">Tokens Configuration</h4>
                                        <div class="space-y-5 max-w-sm mx-auto w-full">
                                            <div>
                                                <label class="text-[10px] text-indigo-600 dark:text-indigo-400 font-bold block mb-2 transition-colors uppercase">Hex Color</label>
                                                <div class="relative w-full h-10 rounded-lg overflow-hidden border border-slate-300 dark:border-white/20 shadow-inner transition-colors group cursor-pointer focus-within:ring-2 focus-within:ring-indigo-500/50">
                                                    <input type="color" id="inp-brand-color" oninput="updateBrand()" value="#6366f1" class="absolute -top-2 -left-2 w-[150%] h-[150%] cursor-pointer opacity-100">
                                                </div>
                                            </div>
                                            <div class="pt-2">
                                                <label class="text-[10px] text-pink-600 dark:text-pink-400 font-bold flex justify-between mb-2 transition-colors uppercase">
                                                    <span>Font Scale (rem)</span>
                                                    <span id="label-brand-size" class="text-slate-500 dark:text-white/60 font-mono">2.5</span>
                                                </label>
                                                <input type="range" min="1.5" max="4" step="0.1" value="2.5" oninput="updateBrand()" id="inp-brand-size" class="w-full accent-pink-600 dark:accent-pink-500 h-1.5 bg-slate-200 dark:bg-white/10 rounded-full cursor-pointer hover:accent-pink-500 transition-colors focus:outline-none focus:ring-2 focus:ring-pink-400/50">
                                            </div>
                                        </div>
                                        <div class="mt-6 bg-white dark:bg-black/40 p-4 rounded-lg text-[11px] sm:text-xs font-mono text-slate-600 dark:text-gray-400 border border-adaptive shadow-inner transition-colors overflow-x-auto custom-scrollbar">
                                            <span class="text-amber-600 dark:text-yellow-400 font-bold">h1</span> <span class="text-adaptive">{</span> <br>
                                            &nbsp;&nbsp;<span class="text-blue-600 dark:text-blue-400">color</span>: <span id="css-brand-color" class="text-indigo-600 dark:text-indigo-400 font-bold bg-indigo-50 dark:bg-indigo-900/30 px-1.5 rounded transition-colors">#6366f1</span><span class="text-adaptive">;</span> <br>
                                            &nbsp;&nbsp;<span class="text-blue-600 dark:text-blue-400">font-size</span>: <span id="css-brand-size" class="text-pink-600 dark:text-pink-400 font-bold bg-pink-50 dark:bg-pink-900/30 px-1.5 rounded transition-colors">2.5rem</span><span class="text-adaptive">;</span> <br>
                                            <span class="text-adaptive">}</span>
                                        </div>
                                    </div>
                                    <div class="w-full lg:w-2/3 bg-slate-100 dark:bg-[#0a0a0a] flex flex-col relative bg-[url('https://grainy-gradients.vercel.app/noise.svg')] min-h-[300px] shrink-0">
                                         <div class="w-full flex justify-end p-3 border-b border-adaptive/50 bg-white/30 dark:bg-black/20 backdrop-blur z-20 shrink-0">
                                             <span class="text-[9px] sm:text-[10px] font-bold text-slate-600 dark:text-white/40 uppercase tracking-widest">Heading Output</span>
                                         </div>
                                         <div class="flex-1 flex items-center justify-center p-6 text-center overflow-hidden">
                                             <h1 id="brand-preview" class="font-black transition-all duration-300 leading-tight drop-shadow-sm px-4 break-words w-full" style="color: #6366f1; font-size: 2.5rem;">
                                                 Design is Intelligence<br>Made Visible.
                                             </h1>
                                         </div>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- KESIMPULAN SIMULASI 4 --}}
                            <div class="mt-4 bg-gradient-to-r from-indigo-50 to-transparent dark:from-indigo-900/20 dark:to-transparent border-l-4 border-indigo-500 p-4 rounded-r-xl relative z-10 transition-colors">
                                <h5 class="text-[11px] sm:text-xs font-bold text-indigo-700 dark:text-indigo-400 mb-1 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> 
                                    Kesimpulan
                                </h5>
                                <p class="text-[10px] sm:text-[11px] text-slate-700 dark:text-slate-300 leading-relaxed text-justify m-0">
                                    Menggunakan rasio <code>rem</code> membantu elemen skala secara fleksibel sesuai kapabilitas penglihatan pengguna, menjaga integritas desain tanpa merusak komposisi visual dasar.
                                </p>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 5 --}}
                    <section id="section-5" class="lesson-section scroll-mt-32" data-lesson-id="5">
                        <div class="space-y-8 md:space-y-10">
                            <div class="space-y-4 border-l-4 border-indigo-500 pl-4 md:pl-6">
                                <span class="text-indigo-600 dark:text-indigo-400 font-mono text-xs uppercase tracking-widest transition-colors">Lesson 1.1.5</span>
                                <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-heading leading-[1.1] transition-colors">
                                    Relativitas Ruang <br> 
                                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-purple-600 dark:from-indigo-400 dark:to-purple-500">The Box Model</span>
                                </h2>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-base md:text-lg font-bold text-heading flex items-center gap-2 transition-colors"><span class="w-5 h-5 rounded bg-indigo-500 flex items-center justify-center text-[10px] text-white shrink-0">A</span> Segalanya Adalah "Sebuah Kotak"</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-sm md:text-base leading-relaxed space-y-4 transition-colors text-justify">
                                    <p>Tidak peduli seberapa melengkung bentuk yang Anda buat dengan CSS, di mata <em>Rendering Engine</em> browser, semua elemen HTML dihitung secara rigid sebagai sebuah kotak empat persegi. Konsep fundamental ini disebut <strong>The Box Model</strong>.</p>
                                    
                                    <div class="bg-indigo-50 dark:bg-indigo-900/10 p-5 rounded-xl border border-indigo-100 dark:border-indigo-800/30 my-6 shadow-sm">
                                        <p class="font-bold text-indigo-800 dark:text-indigo-300 mb-4 uppercase tracking-wider text-[11px] sm:text-xs">4 Lapisan Arsitektur Box Model:</p>
                                        <ul class="space-y-4 list-none pl-0 m-0 text-xs sm:text-sm">
                                            <li class="flex flex-col sm:flex-row sm:items-start gap-2 sm:gap-4"><span class="font-bold text-blue-600 dark:text-blue-400 sm:w-20 shrink-0 uppercase tracking-wide">1. Content</span> <span class="opacity-80 leading-relaxed">Nukleus tengah. Ini adalah area inti di mana teks atau gambar dirender.</span></li>
                                            <li class="flex flex-col sm:flex-row sm:items-start gap-2 sm:gap-4"><span class="font-bold text-emerald-600 dark:text-emerald-400 sm:w-20 shrink-0 uppercase tracking-wide">2. Padding</span> <span class="opacity-80 leading-relaxed">Ruang udara internal. Bantalan yang membungkus <em>Content</em>, menjauhkannya dari garis bingkai.</span></li>
                                            <li class="flex flex-col sm:flex-row sm:items-start gap-2 sm:gap-4"><span class="font-bold text-amber-600 dark:text-amber-400 sm:w-20 shrink-0 uppercase tracking-wide">3. Border</span> <span class="opacity-80 leading-relaxed">Garis pagar perbatasan dimensi fisik yang menyelimuti area Padding dan Content.</span></li>
                                            <li class="flex flex-col sm:flex-row sm:items-start gap-2 sm:gap-4"><span class="font-bold text-orange-600 dark:text-orange-400 sm:w-20 shrink-0 uppercase tracking-wide">4. Margin</span> <span class="opacity-80 leading-relaxed">Zona dorongan eksterior transparan. Berfungsi memberikan jarak penyekat dengan kotak elemen lain di sekitarnya.</span></li>
                                        </ul>
                                    </div>
                                    <p>Pastikan Anda menambahkan baris deklarasi standar global <code>* { box-sizing: border-box; }</code> di file CSS. Perintah ini mencegah kalkulasi ukuran elemen meledak tak terkendali ketika Anda menambahkan ketebalan <em>Padding</em> dan <em>Border</em>.</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-base md:text-lg font-bold text-heading flex items-center gap-2 transition-colors"><span class="w-5 h-5 rounded bg-purple-500 flex items-center justify-center text-[10px] text-white shrink-0">B</span> Jeda Internal vs Eksternal (Padding vs Margin)</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-sm md:text-base leading-relaxed space-y-4 transition-colors text-justify">
                                    <p>Kebingungan yang paling sering dialami pemula adalah tertukarnya peran <strong>Padding</strong> dan <strong>Margin</strong>.</p>
                                    <p>Gunakan <strong>Padding</strong> untuk memberikan kelegaan bantalan di <span class="hl-term">bagian dalam (interior)</span> elemen. Misalnya, agar paragraf teks tidak menghimpit lekat menyentuh garis <em>Border</em> atau tepi layar. Warna background selalu ikut mengisi area padding.</p>
                                    <p>Sebaliknya, gunakan <strong>Margin</strong> jika Anda ingin memberikan daya dorong ke <span class="hl-term">bagian luar (eksterior)</span> kotak untuk menjaga jarak dari blok elemen tetangganya. Margin selalu bersifat transparan murni dan tidak pernah terwarnai oleh background elemen itu sendiri.</p>
                                </div>
                            </div>

                            {{-- SIMULATOR BOX MODEL --}}
                            <div class="sim-bg-adaptive border border-adaptive rounded-xl overflow-hidden shadow-xl dark:shadow-2xl flex flex-col mt-8">
                                
                                {{-- KOTAK INSTRUKSI SIMULATOR 5 --}}
                                <div class="bg-indigo-600/95 dark:bg-indigo-900/95 text-white p-4 shrink-0 flex flex-col sm:flex-row gap-3 sm:items-center justify-between border-b border-indigo-400 dark:border-indigo-700 z-20">
                                     <div class="flex items-center gap-2 text-[11px] sm:text-xs font-bold shrink-0">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        PANDUAN SIMULASI
                                    </div>
                                    <p class="text-[10px] sm:text-[11px] opacity-90 m-0 leading-tight sm:text-right sm:max-w-md">Mainkan slider <span class="hl-term">Margins</span>, <span class="hl-term">Borders</span>, dan <span class="hl-term">Paddings</span> untuk menganalisis anatomi dimensi ruang secara real-time di atas kanvas tata letak.</p>
                                </div>

                                <div class="flex flex-col p-4 sm:p-6 md:p-8 shrink-0">
                                    <div class="w-full max-w-3xl mx-auto flex flex-col md:flex-row md:items-center gap-6 mb-8 shrink-0">
                                        <div class="flex-1 bg-white dark:bg-black/40 p-5 rounded-lg border border-adaptive shadow-inner shrink-0">
                                            <div class="text-slate-500 dark:text-gray-400 mb-2 font-bold font-mono text-xs"><span class="text-purple-600 dark:text-purple-400">.the-box-element</span> {</div>
                                            <div class="pl-4 font-mono text-xs transition-all flex flex-wrap items-center gap-1.5 mb-2"><span class="text-blue-500 dark:text-blue-400 font-bold">margin:</span> <div><span id="val-mar" class="text-slate-900 dark:text-white bg-orange-100 dark:bg-orange-900/30 px-1.5 rounded transition-colors font-bold">20</span><span class="text-slate-500">px;</span></div></div>
                                            <div class="pl-4 font-mono text-xs transition-all flex flex-wrap items-center gap-1.5 mb-2"><span class="text-blue-500 dark:text-blue-400 font-bold">border:</span> <div><span id="val-bor" class="text-slate-900 dark:text-white bg-amber-100 dark:bg-amber-900/30 px-1.5 rounded transition-colors font-bold">5</span><span class="text-slate-500">px </span><span class="text-purple-500 dark:text-purple-400 font-bold">solid</span><span class="text-slate-500">;</span></div></div>
                                            <div class="pl-4 font-mono text-xs transition-all flex flex-wrap items-center gap-1.5 mb-2"><span class="text-blue-500 dark:text-blue-400 font-bold">padding:</span> <div><span id="val-pad" class="text-slate-900 dark:text-white bg-emerald-100 dark:bg-emerald-900/30 px-1.5 rounded transition-colors font-bold">20</span><span class="text-slate-500">px;</span></div></div>
                                            <div class="text-slate-500 dark:text-gray-400 mt-2 font-bold font-mono text-xs">}</div>
                                        </div>
                                        
                                        <div class="flex-1 flex flex-col gap-5 bg-white dark:bg-black/20 p-5 rounded-lg border border-adaptive shrink-0">
                                            <div class="flex items-center gap-4">
                                                <div class="w-16 shrink-0 text-right">
                                                    <label class="text-[10px] text-orange-600 dark:text-orange-400 font-bold uppercase tracking-wider">Margin</label>
                                                </div>
                                                <input type="range" min="0" max="60" value="20" oninput="updateBoxModel('mar', this.value)" class="flex-1 accent-orange-500 h-1.5 bg-slate-200 dark:bg-white/10 rounded-full cursor-pointer focus:outline-none focus:ring-2 focus:ring-orange-500/50">
                                            </div>
                                            <div class="flex items-center gap-4">
                                                <div class="w-16 shrink-0 text-right">
                                                    <label class="text-[10px] text-amber-600 dark:text-yellow-400 font-bold uppercase tracking-wider">Border</label>
                                                </div>
                                                <input type="range" min="0" max="20" value="5" oninput="updateBoxModel('bor', this.value)" class="flex-1 accent-amber-500 h-1.5 bg-slate-200 dark:bg-white/10 rounded-full cursor-pointer focus:outline-none focus:ring-2 focus:ring-amber-500/50">
                                            </div>
                                            <div class="flex items-center gap-4">
                                                <div class="w-16 shrink-0 text-right">
                                                    <label class="text-[10px] text-emerald-600 dark:text-green-400 font-bold uppercase tracking-wider">Padding</label>
                                                </div>
                                                <input type="range" min="0" max="60" value="20" oninput="updateBoxModel('pad', this.value)" class="flex-1 accent-emerald-500 h-1.5 bg-slate-200 dark:bg-white/10 rounded-full cursor-pointer focus:outline-none focus:ring-2 focus:ring-emerald-500/50">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="w-full bg-slate-100 dark:bg-[#0a0f18] rounded-xl flex flex-col relative border border-adaptive bg-[url('https://grainy-gradients.vercel.app/noise.svg')] shadow-inner min-h-[350px] shrink-0">
                                         <div class="w-full flex justify-end p-3 border-b border-adaptive/50 bg-white/30 dark:bg-black/20 backdrop-blur z-20 shrink-0">
                                             <span class="text-[9px] sm:text-[10px] font-bold text-slate-500 dark:text-white/40 uppercase tracking-widest px-2">Visual Render Output X-Ray</span>
                                         </div>
                                         <div class="flex-1 flex items-center justify-center p-4 overflow-hidden">
                                             
                                             <div id="box-margin" class="relative bg-orange-500/10 dark:bg-orange-500/20 border border-dashed border-orange-400 dark:border-orange-500/50 transition-all duration-300 p-[20px] flex items-center justify-center min-w-[150px] min-h-[150px] group shadow-sm scale-75 sm:scale-100 origin-center">
                                                 <span class="absolute -top-4 sm:-top-5 left-1/2 -translate-x-1/2 text-[8px] sm:text-[9px] text-orange-700 dark:text-orange-400 font-bold uppercase bg-orange-100 dark:bg-[#1a0f0a] px-1.5 sm:px-2 py-0.5 rounded shadow-sm transition-colors border border-orange-200 dark:border-orange-900/50 whitespace-nowrap opacity-70 group-hover:opacity-100">Margin Area</span>
                                                 
                                                 <div id="box-border" class="relative bg-white dark:bg-[#151515] border-[5px] border-amber-400 dark:border-yellow-500/80 transition-all duration-300 shadow-md w-full h-full flex items-center justify-center group/border">
                                                     <span class="absolute -left-5 sm:-left-6 top-1/2 -translate-y-1/2 -rotate-90 text-[8px] sm:text-[9px] text-amber-700 dark:text-yellow-400 font-bold uppercase bg-amber-100 dark:bg-[#1a170a] px-1.5 sm:px-2 py-0.5 rounded shadow-sm transition-colors border border-amber-200 dark:border-yellow-900/50 whitespace-nowrap opacity-70 group-hover/border:opacity-100 origin-center">Border</span>
                                                     
                                                     <div id="box-padding" class="relative bg-emerald-500/10 dark:bg-green-500/20 p-[20px] transition-all duration-300 w-full h-full flex items-center justify-center border border-dashed border-emerald-300 dark:border-green-500/30 group/pad shadow-inner">
                                                         <span class="absolute -bottom-4 sm:-bottom-5 left-1/2 -translate-x-1/2 text-[8px] sm:text-[9px] text-emerald-700 dark:text-green-400 font-bold uppercase bg-emerald-100 dark:bg-[#0a1a10] px-1.5 sm:px-2 py-0.5 rounded shadow-sm transition-colors border border-emerald-200 dark:border-green-900/50 whitespace-nowrap opacity-70 group-hover/pad:opacity-100 z-20">Padding Area</span>
                                                         
                                                         <div class="w-full h-full min-w-[80px] min-h-[60px] sm:min-w-[120px] sm:min-h-[80px] bg-indigo-500 dark:bg-indigo-600 flex flex-col items-center justify-center text-white font-bold text-[10px] sm:text-xs shadow-lg relative z-10 border border-indigo-400 dark:border-indigo-500/50 rounded-sm hover:bg-indigo-400 dark:hover:bg-indigo-500 transition-colors cursor-default overflow-hidden">
                                                             <span class="opacity-90 tracking-widest text-[7px] sm:text-[8px] md:text-[9px] uppercase mb-1 text-indigo-200">Core</span>
                                                             <span>CONTENT</span>
                                                             <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 pointer-events-none mix-blend-overlay"></div>
                                                         </div>
                                                     </div>
                                                 </div>
                                             </div>
                                         </div>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- KESIMPULAN SIMULASI 5 --}}
                            <div class="mt-4 bg-gradient-to-r from-indigo-50 to-transparent dark:from-indigo-900/20 dark:to-transparent border-l-4 border-indigo-500 p-4 rounded-r-xl relative z-10 transition-colors">
                                <h5 class="text-[11px] sm:text-xs font-bold text-indigo-700 dark:text-indigo-400 mb-1 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> 
                                    Kesimpulan Geometri Visual
                                </h5>
                                <p class="text-[10px] sm:text-[11px] text-slate-700 dark:text-slate-300 leading-relaxed text-justify m-0">
                                    <strong>Padding</strong> mengekspansi ruang perlindungan di bagian dalam (melindungi <em>Content</em> agar tidak menabrak bingkai). Sementara itu, <strong>Margin</strong> adalah kekuatan tolak menolak yang memaksa entitas luar untuk menjaga jarak dari batas <em>Border</em> elemen Anda.
                                </p>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 6: ACTIVITY FINAL --}}
                    <section id="section-6" class="lesson-section scroll-mt-32 pt-10 border-t border-adaptive transition-colors" data-lesson-id="6" data-type="activity">
                        <div class="relative rounded-[1.5rem] md:rounded-[2rem] sim-bg-adaptive border border-adaptive overflow-hidden shadow-xl dark:shadow-2xl transition-all duration-500 flex flex-col">
                            
                            <div class="absolute -top-24 -right-24 w-40 h-40 md:w-64 md:h-64 bg-indigo-600/10 dark:bg-indigo-600/20 blur-[100px] rounded-full pointer-events-none transition-colors"></div>
                            
                            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 mb-2 md:mb-4 relative z-10 p-4 sm:p-6 md:p-10 pb-0 md:pb-0 shrink-0">
                                <div class="p-3 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl text-white shadow-lg shadow-indigo-500/30 shrink-0"><svg class="w-5 h-5 md:w-6 md:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg></div>
                                <div><h2 class="text-xl md:text-2xl font-bold text-heading transition-colors">Final Mission: Product Card Builder</h2><p class="text-indigo-600 dark:text-indigo-300 text-xs md:text-sm font-medium transition-colors mt-1.5 leading-relaxed text-justify">Padukan teori Tag Semantik HTML, properti Box Model internal ruang (Padding), dan sentuhan Modifier UI CSS untuk mengeksekusi misi perakitan prototipe kartu produk e-commerce interaktif modern.</p></div>
                            </div>

                            <div class="flex flex-col lg:flex-row gap-6 relative z-10 p-4 sm:p-6 md:p-10 pt-4 md:pt-6">
                                
                                {{-- LEFT: EDITOR --}}
                                <div class="w-full lg:w-1/2 code-adaptive rounded-xl flex flex-col overflow-hidden relative shadow-inner transition-colors border border-adaptive min-h-[500px] shrink-0">
                                    
                                    {{-- LOCK OVERLAY --}}
                                    <div id="lockOverlay" class="hidden absolute inset-0 bg-white/90 dark:bg-[#050912]/95 backdrop-blur-md z-50 flex flex-col items-center justify-center text-center p-6 border-4 border-emerald-500/20 m-1 rounded-lg transition-colors">
                                        <div class="w-16 h-16 md:w-20 md:h-20 bg-emerald-100 dark:bg-green-500/10 rounded-full flex items-center justify-center mb-4 border border-emerald-500/50 shadow-lg animate-bounce"><svg class="w-8 h-8 md:w-10 md:h-10 text-emerald-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" d="M5 13l4 4L19 7" /></svg></div>
                                        <h3 class="text-2xl md:text-3xl font-black text-slate-900 dark:text-white mb-2 transition-colors tracking-tight">MISSION COMPLETED!</h3>
                                        <p class="text-sm md:text-base font-bold text-slate-500 dark:text-white/60 mb-6 md:mb-8 max-w-xs transition-colors">Modul kompetensi 1.1 telah tuntas diselesaikan. Modul kurikulum bab navigasi level selanjutnya kini telah terbuka kuncinya.</p>
                                        <button disabled class="px-6 py-2.5 md:px-8 md:py-3 rounded-full bg-slate-200 dark:bg-white/5 border border-slate-300 dark:border-white/10 text-slate-500 dark:text-white/30 text-[10px] md:text-xs font-bold cursor-not-allowed uppercase transition-colors tracking-widest">Akses Builder Dikunci</button>
                                    </div>

                                    <div class="bg-slate-200 dark:bg-[#2d2d2d] px-4 py-3 border-b border-adaptive flex justify-between items-center transition-colors shrink-0">
                                        <span class="text-[10px] md:text-xs text-slate-600 dark:text-gray-400 font-mono font-bold flex items-center gap-2"><div class="w-2 h-2 rounded-full bg-amber-500"></div> ui_challenge_builder.js</span>
                                        <span class="text-[9px] uppercase tracking-widest font-bold text-indigo-500 dark:text-indigo-400">Config Builder</span>
                                    </div>
                                    
                                    <div class="p-4 md:p-6 flex-1 overflow-y-auto custom-scrollbar bg-slate-50 dark:bg-transparent transition-colors pb-6">
                                        <form id="activityForm" class="space-y-8">
                                            <div class="space-y-3">
                                                <label class="text-[9px] md:text-[10px] uppercase text-slate-500 dark:text-white/50 font-bold tracking-widest transition-colors flex items-center gap-2"><span class="w-4 h-4 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-[8px] text-slate-600 dark:text-slate-300">1</span> <span class="leading-tight">Semantic Root Wrapper (Konteks Makna Kontainer DOM)</span></label>
                                                <div class="grid grid-cols-2 gap-3 pl-6">
                                                    <button type="button" onclick="setAct('tag', 'div', this)" class="opt-btn-tag px-3 py-3 rounded-xl bg-white dark:bg-white/5 text-slate-600 dark:text-gray-400 border border-slate-300 dark:border-white/10 text-[11px] md:text-sm font-bold text-center font-mono hover:bg-slate-100 dark:hover:bg-white/10 shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-indigo-500/50 active:scale-95">&lt;div&gt;</button>
                                                    <button type="button" onclick="setAct('tag', 'article', this)" class="opt-btn-tag px-3 py-3 rounded-xl bg-white dark:bg-white/5 text-slate-600 dark:text-gray-400 border border-slate-300 dark:border-white/10 text-[11px] md:text-sm font-bold text-center font-mono hover:bg-slate-100 dark:hover:bg-white/10 shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-indigo-500/50 active:scale-95">&lt;article&gt;</button>
                                                </div>
                                            </div>
                                            <div class="space-y-3">
                                                <label class="text-[9px] md:text-[10px] uppercase text-slate-500 dark:text-white/50 font-bold tracking-widest transition-colors flex items-center gap-2"><span class="w-4 h-4 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-[8px] text-slate-600 dark:text-slate-300 shrink-0">2</span> <span class="leading-tight">Internal Box Model Geometry (Injeksi Kelonggaran Bantalan Interior)</span></label>
                                                <div class="grid grid-cols-2 gap-3 pl-6">
                                                    <button type="button" onclick="setAct('pad', '0px', this)" class="opt-btn-pad px-3 py-3 rounded-xl bg-white dark:bg-white/5 text-slate-600 dark:text-gray-400 border border-slate-300 dark:border-white/10 text-[11px] md:text-sm font-bold text-center font-mono hover:bg-slate-100 dark:hover:bg-white/10 shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-indigo-500/50 active:scale-95">padding: 0</button>
                                                    <button type="button" onclick="setAct('pad', '24px', this)" class="opt-btn-pad px-3 py-3 rounded-xl bg-white dark:bg-white/5 text-slate-600 dark:text-gray-400 border border-slate-300 dark:border-white/10 text-[11px] md:text-sm font-bold text-center font-mono hover:bg-slate-100 dark:hover:bg-white/10 shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-indigo-500/50 active:scale-95">padding: 24px</button>
                                                </div>
                                            </div>
                                            <div class="space-y-3">
                                                <label class="text-[9px] md:text-[10px] uppercase text-slate-500 dark:text-white/50 font-bold tracking-widest transition-colors flex items-center gap-2"><span class="w-4 h-4 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-[8px] text-slate-600 dark:text-slate-300 shrink-0">3</span> <span class="leading-tight">Visual Theme Styling Modifier (Ekseskusi Target Estetika Tampilan)</span></label>
                                                <div class="grid grid-cols-2 gap-3 pl-6">
                                                    <button type="button" onclick="setAct('style', 'plain', this)" class="opt-btn-style px-3 py-3 rounded-xl bg-white dark:bg-white/5 text-slate-600 dark:text-gray-400 border border-slate-300 dark:border-white/10 text-[11px] md:text-sm font-bold text-center font-mono hover:bg-slate-100 dark:hover:bg-white/10 shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-indigo-500/50 active:scale-95">Mode Plain Text</button>
                                                    <button type="button" onclick="setAct('style', 'card', this)" class="opt-btn-style px-3 py-3 rounded-xl bg-white dark:bg-white/5 text-slate-600 dark:text-gray-400 border border-slate-300 dark:border-white/10 text-[11px] md:text-sm font-bold text-center font-mono hover:bg-slate-100 dark:hover:bg-white/10 shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-indigo-500/50 active:scale-95">Mode Styled Card</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    
                                    <div class="p-4 bg-slate-200/80 dark:bg-black/60 border-t border-adaptive flex flex-col sm:flex-row justify-between items-center gap-3 sm:gap-0 transition-colors shrink-0 backdrop-blur-sm z-20 mt-auto">
                                        <span class="text-[10px] sm:text-[11px] font-bold text-slate-500 dark:text-white/40 font-mono transition-colors text-center sm:text-left w-full sm:w-auto" id="status-text">Menunggu instruksi formasi input komponen...</span>
                                        <button onclick="checkSolution()" id="submitBtn" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold text-[11px] sm:text-xs shadow-lg shadow-indigo-500/20 hover:shadow-indigo-500/40 hover:-translate-y-0.5 transition-all cursor-pointer focus:outline-none focus:ring-4 focus:ring-indigo-500/30 active:scale-95 shrink-0">Evaluasi Validasi Kode Output</button>
                                    </div>
                                </div>

                                {{-- RIGHT: BROWSER PREVIEW --}}
                                <div class="w-full lg:w-1/2 bg-slate-100 dark:bg-[#1e1e1e] rounded-xl border border-adaptive flex flex-col relative overflow-hidden shadow-inner transition-colors min-h-[400px] shrink-0">
                                    <div class="bg-slate-200 dark:bg-[#2d2d2d] px-4 py-3 border-b border-adaptive flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-0 justify-between transition-colors shrink-0">
                                        <div class="flex items-center gap-4 w-full sm:w-auto">
                                            <div class="flex gap-1.5 shrink-0"><div class="w-3 h-3 rounded-full bg-red-400 border border-red-500/50"></div><div class="w-3 h-3 rounded-full bg-amber-400 border border-amber-500/50"></div><div class="w-3 h-3 rounded-full bg-emerald-400 border border-emerald-500/50"></div></div>
                                            <div class="bg-white/50 dark:bg-black/20 flex-1 sm:w-48 rounded-md px-3 py-1 font-mono text-[9px] sm:text-[10px] text-slate-500 dark:text-gray-500 text-center shadow-sm dark:shadow-inner transition-colors truncate">localhost:3000/product/preview</div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex-1 bg-white dark:bg-gray-900 p-4 sm:p-8 relative overflow-y-auto flex items-center justify-center transition-colors">
                                        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-5 pointer-events-none"></div>
                                        
                                        <div id="final-card" class="w-64 sm:w-72 bg-transparent text-left transition-all duration-500 relative z-10 mx-auto">
                                            <div class="h-32 sm:h-40 bg-indigo-100 dark:bg-indigo-900/30 rounded-t-xl relative overflow-hidden mb-4 hidden transition-colors" id="final-img">
                                                <img src="https://images.unsplash.com/photo-1595225476474-87563907a212?w=500" class="w-full h-full object-cover mix-blend-overlay opacity-80" alt="Keyboard">
                                            </div>
                                            <div id="final-content" class="text-slate-900 dark:text-white transition-colors">
                                                <h3 class="font-black text-lg sm:text-xl mb-1 transition-colors">Mechanical Tactile Keyboard</h3>
                                                <p class="text-xs sm:text-sm opacity-70 mb-4 sm:mb-5 font-medium transition-colors">Keyboard mekanikal berperforma tinggi untuk kenyamanan dan durabilitas maksimal para pengembang website harian.</p>
                                                <button class="w-full py-2.5 sm:py-3 rounded-lg bg-indigo-600 text-white font-bold text-[11px] sm:text-sm shadow-md hover:bg-indigo-700 transition-colors focus:outline-none focus:ring-4 focus:ring-indigo-500/50 active:scale-95">Tambahkan Ke Keranjang - $99</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                </article>

                {{-- FOOTER NAVIGATION --}}
                <div class="mt-20 md:mt-32 pt-8 border-t border-adaptive flex flex-col sm:flex-row justify-between items-center gap-4 sm:gap-0 transition-colors">
                    <a href="{{ route('courses.curriculum') ?? '#' }}" class="group flex items-center gap-4 text-muted hover:text-heading transition-colors w-full sm:w-auto justify-center sm:justify-start">
                        <div class="w-10 h-10 md:w-12 md:h-12 rounded-full border border-adaptive flex items-center justify-center group-hover:bg-slate-100 dark:group-hover:bg-white/5 transition-colors shrink-0">
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        </div>
                        <div class="text-left">
                            <div class="text-[10px] uppercase tracking-widest font-bold opacity-60">Kembali </div>
                            <div class="font-black text-xs md:text-sm line-clamp-1">Dashboard Modul Kurikulum Utama</div>
                        </div>
                    </a>
                    
                    <div id="nextChapterBtn" class="group flex items-center gap-4 text-right text-muted cursor-not-allowed opacity-50 pointer-events-none transition-all duration-500 w-full sm:w-auto justify-center sm:justify-end">
                        <div class="text-right">
                            <div id="nextLabel" class="text-[10px] uppercase tracking-widest font-bold opacity-60">Berikutnya</div>
                            <div class="font-black text-xs md:text-sm line-clamp-1">Latar Belakang Tailwind CSS</div>
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
    /* ==========================================
       1. KONFIGURASI GLOBAL & DATA CASTING
       ========================================== */
    window.LESSON_IDS = [1, 2, 3, 4, 5, 6]; 
    
    let rawCompletedIds = {!! json_encode($completedLessonIds ?? []) !!};
    window.COMPLETED_IDS = rawCompletedIds.map(id => Number(id)); 
    let completedSet = new Set(window.COMPLETED_IDS);
    
    const ACTIVITY_LESSON_ID = 6; 
    let activityCompleted = completedSet.has(ACTIVITY_LESSON_ID);

    document.addEventListener('DOMContentLoaded', () => {
        initScrollSpy();
        initSidebarScroll();
        initVisualEffects();
        
        updateProgressUI(false); 
        
        if (activityCompleted) {
            lockActivityUI();
            unlockNextChapter();
        }

        initLessonObserver();
        
        document.querySelectorAll('.nav-item').forEach(item => {
            const targetId = parseInt(item.getAttribute('data-target').replace('#section-', ''));
            if(completedSet.has(targetId)) markSidebarDone(targetId);
        });
    });

    /* ==========================================
       2. LOGIKA PROGRESS BAR & SIDEBAR UI
       ========================================== */
    function updateProgressUI(animate = true) {
        const total = window.LESSON_IDS.length; 
        const done = window.LESSON_IDS.filter(id => completedSet.has(Number(id))).length; 
        const percent = Math.round((done / total) * 100);
        
        const bar = document.getElementById('topProgressBar');
        const label = document.getElementById('progressLabelTop');
        
        if(!animate) bar.style.transition = 'none';
        bar.style.width = percent + '%'; 
        if(!animate) setTimeout(() => bar.style.transition = 'all 0.5s', 50);
        
        label.innerText = percent + '%';
        if(percent === 100 && activityCompleted) unlockNextChapter();
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

    /* ==========================================
       3. AJAX POST REQUEST KE DATABASE
       ========================================== */
    async function saveLessonToDB(lessonId) { 
        lessonId = Number(lessonId); 
        if(completedSet.has(lessonId)) return; 

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
            }
        } catch(e) {
            console.error('Network Error:', e);
        }
    }

    /* ==========================================
       4. SCROLL OBSERVER (Pendeteksi user membaca)
       ========================================== */
    function initLessonObserver() {
        const obs = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const id = Number(entry.target.dataset.lessonId);
                    
                    if (id && entry.target.dataset.type !== 'activity' && !completedSet.has(id)) {
                        saveLessonToDB(id); 
                    }
                }
            });
        }, { 
            threshold: 0.1, 
            rootMargin: "0px 0px -50px 0px", 
            root: document.getElementById('mainScroll') 
        });
        
        document.querySelectorAll('.lesson-section').forEach(s => obs.observe(s));
    }

    /* ==========================================
       5. LOGIKA ACTIVITY FINAL (VALIDASI & KUNCI)
       ========================================== */
    let actConfig = { tag: '', pad: '', style: '' };

    function setAct(cat, val, btn) {
        if(activityCompleted) return;
        actConfig[cat] = val;
        
        document.querySelectorAll(`.opt-btn-${cat}`).forEach(b => {
            b.classList.remove('bg-indigo-600', 'text-white', 'border-indigo-500', 'shadow-md', 'dark:shadow-[0_0_15px_rgba(99,102,241,0.3)]');
            b.classList.add('bg-white', 'dark:bg-white/5', 'text-slate-600', 'dark:text-gray-400', 'border-slate-300', 'dark:border-white/10');
        });
        
        btn.classList.remove('bg-white', 'dark:bg-white/5', 'text-slate-600', 'dark:text-gray-400', 'border-slate-300', 'dark:border-white/10');
        btn.classList.add('bg-indigo-600', 'text-white', 'border-indigo-500', 'shadow-md', 'dark:shadow-[0_0_15px_rgba(99,102,241,0.3)]');
        
        renderFinalCard();
    }

    function renderFinalCard() {
        const card = document.getElementById('final-card');
        const img = document.getElementById('final-img');
        const content = document.getElementById('final-content');
        
        if(actConfig.style === 'card') {
            card.className = "w-64 sm:w-72 bg-white dark:bg-[#0f172a] border border-slate-200 dark:border-white/10 text-left shadow-2xl transition-all duration-500 rounded-2xl overflow-hidden mx-auto";
            img.classList.remove('hidden');
        } else {
            card.className = "w-64 sm:w-72 text-left text-slate-500 dark:text-gray-400 mx-auto";
            img.classList.add('hidden');
        }
        content.className = (actConfig.pad === '24px') ? "p-5 sm:p-6 text-slate-900 dark:text-white" : "p-0 text-slate-900 dark:text-white";
    }

    async function checkSolution() {
        if(activityCompleted) return;
        const btn = document.getElementById('submitBtn');
        const status = document.getElementById('status-text');
        
        btn.innerHTML = '<span class="animate-pulse">Sistem sedang memverifikasi integritas algoritma konfigurasi arsitektur Anda...</span>'; 
        btn.disabled = true;
        
        await new Promise(r => setTimeout(r, 1200)); 

        if(actConfig.tag === 'article' && actConfig.pad === '24px' && actConfig.style === 'card') {
            status.innerText = "VALIDASI SUKSES! Konfigurasi komponen Product Card telah sempurna dan fungsional. Data sertifikasi evaluasi telah terekam pada server."; 
            status.className = "text-[10px] sm:text-[11px] text-emerald-600 dark:text-green-400 font-mono font-bold tracking-wider text-center sm:text-left w-full sm:w-auto";
            
            await saveLessonToDB(ACTIVITY_LESSON_ID); 
            
            activityCompleted = true;
            lockActivityUI();
            unlockNextChapter();
        } else {
            status.innerText = "PERINGATAN: Validasi ditolak! Pastikan Anda menggunakan tag Container semantik murni (<article>), volume Padding protektif (24px Area), serta Modus Gaya Visual (Styled Card)."; 
            status.className = "text-[9px] sm:text-[10px] text-red-500 dark:text-red-400 font-mono font-bold leading-tight text-center sm:text-left w-full sm:w-auto";
            btn.innerText = "Ulangi Evaluasi Kalibrasi Tes Uji"; btn.disabled = false; btn.classList.add('shake'); setTimeout(() => btn.classList.remove('shake'), 500);
        }
    }

    function lockActivityUI() {
        document.getElementById('lockOverlay').classList.remove('hidden');
        const btn = document.getElementById('submitBtn'); 
        btn.innerText = "Zona Evaluasi Dikunci Rapat (Pencapaian Validasi Lulus Seratus Persen)"; btn.disabled = true;
        btn.classList.remove('from-indigo-600', 'to-purple-600');
        btn.classList.add('bg-slate-400', 'dark:bg-slate-700', 'text-slate-200', 'cursor-not-allowed', 'shadow-none');
        
        actConfig = { tag: 'article', pad: '24px', style: 'card' }; renderFinalCard();
        document.querySelectorAll('#activityForm button').forEach(b => { b.disabled = true; b.classList.add('cursor-not-allowed'); });
    }

    function unlockNextChapter() {
        const btn = document.getElementById('nextChapterBtn');
        if(btn) {
            btn.classList.remove('cursor-not-allowed', 'opacity-50', 'pointer-events-none', 'text-muted');
            btn.classList.add('text-indigo-600', 'dark:text-indigo-400', 'cursor-pointer');
            
            document.getElementById('nextLabel').innerText = "Berikutnya";
            document.getElementById('nextLabel').classList.remove('opacity-60');
            document.getElementById('nextLabel').classList.add('text-indigo-600', 'dark:text-indigo-400', 'opacity-100');
            
            const icon = document.getElementById('nextIcon');
            icon.innerHTML = `<svg class="w-4 h-4 md:w-5 md:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>`;
            icon.classList.remove('bg-slate-100', 'dark:bg-white/5');
            icon.classList.add('bg-indigo-100', 'dark:bg-indigo-500/20', 'border-indigo-300', 'dark:border-indigo-500/50', 'text-indigo-600', 'dark:text-indigo-400', 'shadow-lg');
            
            btn.onclick = () => window.location.href = "{{ route('courses.latarbelakang') ?? '#' }}"; 
        }
    }

    /* ==========================================
       6. SIMULATOR MATERI LOGIC
       ========================================== */
    function toggleDom(el) {
        let domState = window.domState || { nav: false, h1: false, p: false };
        window.domState = domState;
        domState[el] = !domState[el];
        const code = document.getElementById('dom-code-area');
        const view = document.getElementById('dom-preview-area');
        let hC = '', hV = '';
        
        if(domState.nav) { 
            hC += `<div class="text-indigo-600 dark:text-indigo-400 font-bold">&lt;nav&gt;<span class="text-slate-400">...</span>&lt;/nav&gt;</div>`; 
            hV += `<div class="w-full h-8 sm:h-10 bg-indigo-500 rounded-md mb-3 sm:mb-4 shadow-md flex items-center px-3 sm:px-4"><div class="w-3 h-3 sm:w-4 sm:h-4 rounded-full bg-white/50"></div></div>`; 
        }
        if(domState.h1) { 
            hC += `<div class="text-purple-600 dark:text-purple-400 font-bold">&lt;h1&gt;<span class="text-slate-400">Judul Utama Halaman</span>&lt;/h1&gt;</div>`; 
            hV += `<h1 class="text-xl sm:text-2xl font-black text-slate-800 dark:text-white mb-2 tracking-tight transition-colors">Judul Utama Halaman</h1>`; 
        }
        if(domState.p) { 
            hC += `<div class="text-pink-600 dark:text-pink-400 font-bold">&lt;p&gt;<span class="text-slate-400">Ini adalah contoh paragraf yang memuat rincian informasi dokumen atau konten artikel untuk dibaca pengguna situs secara utuh.</span>&lt;/p&gt;</div>`; 
            hV += `<div class="space-y-1.5 sm:space-y-2 mt-2"><div class="h-1.5 sm:h-2 bg-slate-300 dark:bg-white/20 rounded w-full transition-colors"></div><div class="h-1.5 sm:h-2 bg-slate-300 dark:bg-white/20 rounded w-5/6 transition-colors"></div><div class="h-1.5 sm:h-2 bg-slate-300 dark:bg-white/20 rounded w-4/6 transition-colors"></div></div>`; 
        }
        
        code.innerHTML = hC || '<span class="text-slate-400 dark:text-gray-600 italic">/* Menunggu injeksi elemen DOM... */</span>';
        view.innerHTML = hV || '<div class="flex h-full items-center justify-center text-slate-400 dark:text-white/20 italic text-[11px] sm:text-sm font-bold w-full text-center">Menunggu injeksi elemen antarmuka komponen HTML</div>';
    }

    function updateAttrSim() {
        const src = document.getElementById('inp-src').value;
        const cls = document.getElementById('inp-class').value;
        document.getElementById('code-src').innerText = src.includes('coding') ? 'setup_komputer.jpg' : 'kucing_lucu.jpg';
        document.getElementById('code-class').innerText = cls;
        const img = document.getElementById('view-attr');
        img.src = src; img.className = `w-40 h-40 sm:w-48 sm:h-48 object-cover border-4 border-white dark:border-white/10 transition-all duration-500 shadow-xl rounded-none ${cls}`;
    }

    function updateCss(prop, val) {
        const btn = document.getElementById('css-btn-target');
        if(prop === 'bg') {
            btn.classList.remove('bg-indigo-600', 'bg-rose-600', 'bg-emerald-600');
            btn.classList.add(`bg-${val}-600`);
        }
        if(prop === 'rad') {
            btn.style.borderRadius = val + 'px';
            document.getElementById('css-val-rad').innerText = val + 'px';
        }
    }

    function updateBrand() {
        const color = document.getElementById('inp-brand-color').value;
        const size = document.getElementById('inp-brand-size').value;
        const h1 = document.getElementById('brand-preview');
        h1.style.color = color; h1.style.fontSize = size + 'rem';
        document.getElementById('css-brand-color').innerText = color;
        document.getElementById('css-brand-size').innerText = size + 'rem';
        document.getElementById('label-brand-size').innerText = size;
    }

    function updateBoxModel(prop, val) {
        if(prop === 'pad') document.getElementById('box-padding').style.padding = val + 'px';
        if(prop === 'bor') document.getElementById('box-border').style.borderWidth = val + 'px';
        if(prop === 'mar') document.getElementById('box-margin').style.padding = val + 'px';
        document.getElementById(`val-${prop}`).innerText = val;
    }

    /* ==========================================
       7. SCROLL SPY & SIDEBAR LOGIC
       ========================================== */
    function highlightAnchor(id) {
        const isDark = document.documentElement.classList.contains('dark');
        const anchors = document.querySelectorAll('.sidebar-anchor');

        anchors.forEach(a => {
            a.classList.remove('bg-slate-100', 'dark:bg-white/5', 'border-indigo-500', 'border-purple-500');
            a.classList.add('border-transparent');
            
            const dot = a.querySelector('.anchor-dot');
            const isActivity = a.dataset.type === 'activity';

            dot.classList.remove('scale-125', 'shadow-sm', 'dark:shadow-[0_0_10px_#6366f1]', 'dark:shadow-[0_0_10px_#a855f7]', 'bg-indigo-500', 'dark:bg-indigo-400');
            
            if (isActivity) {
                dot.classList.remove('bg-purple-500', 'dark:bg-purple-400');
                dot.classList.add('bg-slate-400', 'dark:bg-slate-600'); 
            } else {
                dot.classList.remove('bg-indigo-500', 'dark:bg-indigo-400');
                dot.classList.add('bg-slate-400', 'dark:bg-slate-600'); 
            }

            const text = a.querySelector('.anchor-text');
            text.classList.remove('text-slate-800', 'dark:text-white', 'font-bold');
            text.classList.add('text-slate-500');
        });

        const activeAnchor = document.querySelector(`.sidebar-anchor[data-target="${id}"]`);
        if (activeAnchor) {
            const isActivity = activeAnchor.dataset.type === 'activity';
            
            activeAnchor.classList.add(isDark ? 'dark:bg-white/5' : 'bg-slate-100');
            activeAnchor.classList.add(isActivity ? 'border-purple-500' : 'border-indigo-500');
            activeAnchor.classList.remove('border-transparent');
            
            const dot = activeAnchor.querySelector('.anchor-dot');
            dot.classList.remove('bg-slate-400', 'dark:bg-slate-600');
            
            if (isActivity) {
                dot.classList.add(isDark ? 'dark:bg-purple-400' : 'bg-purple-500', 'scale-125', isDark ? 'dark:shadow-[0_0_10px_#a855f7]' : 'shadow-sm');
            } else {
                dot.classList.add(isDark ? 'dark:bg-indigo-400' : 'bg-indigo-500', 'scale-125', isDark ? 'dark:shadow-[0_0_10px_#6366f1]' : 'shadow-sm');
            }
            
            const text = activeAnchor.querySelector('.anchor-text');
            text.classList.remove('text-slate-500');
            text.classList.add(isDark ? 'dark:text-white' : 'text-slate-800', 'font-bold');
        }
    }

    function initScrollSpy() {
        const mainScroll = document.getElementById('mainScroll'); 
        const sections = document.querySelectorAll('.lesson-section');

        if (mainScroll && sections.length > 0) {
            const observerOptions = { root: mainScroll, threshold: 0.5 };
            const observer = new IntersectionObserver((entries) => {
                let intersectingEntries = entries.filter(e => e.isIntersecting);
                if(intersectingEntries.length > 0) {
                    if (typeof highlightAnchor === 'function') {
                        highlightAnchor(intersectingEntries[0].target.id);
                    }
                }
            }, observerOptions);
            sections.forEach(section => observer.observe(section));
        }
    }

    function initSidebarScroll(){
        const m = document.getElementById('mainScroll');
        const l = document.querySelectorAll('.accordion-content .nav-item');
        m.addEventListener('scroll', () => {
            let c = '';
            document.querySelectorAll('.lesson-section').forEach(s => {
                if (m.scrollTop >= s.offsetTop - 250) c = '#' + s.id;
            });
            l.forEach(k => {
                k.classList.remove('active');
                if (k.getAttribute('data-target') === c) k.classList.add('active');
            })
        });
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