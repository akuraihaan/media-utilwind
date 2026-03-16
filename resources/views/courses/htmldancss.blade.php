@extends('layouts.landing')
@section('title','Bab 1.1 · HTML & CSS Foundations')

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
        --accent: #6366f1;
        --accent-glow: rgba(99, 102, 241, 0.3);
    }

    .dark {
        /* ORIGINAL DARK THEME VALUES */
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

    <div class="flex flex-1 overflow-hidden relative z-20">

        @include('layouts.partials.course-sidebar')

        <main id="mainScroll" class="flex-1 h-full overflow-y-auto scroll-smooth relative bg-transparent custom-scrollbar scroll-padding-top-24">
            
            {{-- STICKY HEADER & PROGRESS BAR --}}
            <div id="stickyHeader" class="sticky top-0 z-30 w-full backdrop-blur-2xl border-b border-adaptive px-8 py-4 flex items-center justify-between transition-colors duration-500" style="background-color: var(--glass-header);">
                <div class="flex items-center gap-4">
                    <div class="w-8 h-8 rounded-lg bg-indigo-500/10 dark:bg-indigo-500/20 border border-indigo-500/20 flex items-center justify-center font-bold text-xs text-indigo-600 dark:text-indigo-400 transition-colors">1.1</div>
                    <div>
                        <h1 class="text-sm font-bold text-heading transition-colors">Konsep Dasar HTML & CSS</h1>
                        <p class="text-[10px] text-muted transition-colors">Mastering the Building Blocks</p>
                    </div>
                </div>
                
                {{-- DYNAMIC PROGRESS BAR UI --}}
                <div class="flex items-center gap-3">
                    <div class="hidden sm:block w-32 h-1.5 bg-slate-200 dark:bg-white/10 rounded-full overflow-hidden transition-colors">
                        <div id="topProgressBar" class="h-full bg-gradient-to-r from-indigo-500 to-purple-500 w-0 transition-all duration-500 shadow-[0_0_10px_rgba(99,102,241,0.5)]"></div>
                    </div>
                    <span id="progressLabelTop" class="text-indigo-600 dark:text-indigo-400 font-bold text-xs transition-colors">0%</span>
                </div>
            </div>

            <div class="p-6 lg:p-16 max-w-5xl mx-auto pb-40">
                
                {{-- TUJUAN PEMBELAJARAN --}}
                <div class="mb-24">
                    <h3 class="text-xl font-bold text-heading mb-6 flex items-center gap-2 transition-colors">
                        <svg class="w-5 h-5 text-indigo-500 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Tujuan Pembelajaran
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <div class="card-adaptive p-5 rounded-xl flex items-start gap-4 group h-full">
                            <div class="w-10 h-10 rounded-lg bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0 font-bold transition-colors">1</div>
                            <div><h4 class="text-sm font-bold text-heading mb-1 transition-colors">Struktur DOM</h4><p class="text-[11px] text-muted leading-relaxed transition-colors">Memahami hierarki elemen dan alur rendering browser.</p></div>
                        </div>
                        <div class="card-adaptive p-5 rounded-xl flex items-start gap-4 group h-full hover:border-purple-500/30">
                            <div class="w-10 h-10 rounded-lg bg-purple-500/10 text-purple-600 dark:text-purple-400 flex items-center justify-center shrink-0 font-bold transition-colors">2</div>
                            <div><h4 class="text-sm font-bold text-heading mb-1 transition-colors">Semantik & Atribut</h4><p class="text-[11px] text-muted leading-relaxed transition-colors">Menerapkan tag bermakna untuk SEO dan aksesibilitas.</p></div>
                        </div>
                        <div class="card-adaptive p-5 rounded-xl flex items-start gap-4 group h-full hover:border-pink-500/30">
                            <div class="w-10 h-10 rounded-lg bg-pink-500/10 text-pink-600 dark:text-pink-400 flex items-center justify-center shrink-0 font-bold transition-colors">3</div>
                            <div><h4 class="text-sm font-bold text-heading mb-1 transition-colors">Sintaks CSS</h4><p class="text-[11px] text-muted leading-relaxed transition-colors">Menguasai selektor, properti, dan nilai gaya.</p></div>
                        </div>
                        <div class="card-adaptive p-5 rounded-xl flex items-start gap-4 group h-full hover:border-blue-500/30">
                            <div class="w-10 h-10 rounded-lg bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0 font-bold transition-colors">4</div>
                            <div><h4 class="text-sm font-bold text-heading mb-1 transition-colors">Warna & Font</h4><p class="text-[11px] text-muted leading-relaxed transition-colors">Mengatur estetika visual dan tipografi modern.</p></div>
                        </div>
                        <div class="card-adaptive p-5 rounded-xl flex items-start gap-4 group h-full hover:border-orange-500/30">
                            <div class="w-10 h-10 rounded-lg bg-orange-500/10 text-orange-600 dark:text-orange-400 flex items-center justify-center shrink-0 font-bold transition-colors">5</div>
                            <div><h4 class="text-sm font-bold text-heading mb-1 transition-colors">The Box Model</h4><p class="text-[11px] text-muted leading-relaxed transition-colors">Konsep fundamental layout: Margin, Border, Padding.</p></div>
                        </div>
                        <div class="bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-indigo-900/40 dark:to-purple-900/40 border border-indigo-200 dark:border-indigo-500/30 p-5 rounded-xl flex items-start gap-4 hover:shadow-[0_0_20px_rgba(99,102,241,0.2)] transition group h-full cursor-default">
                            <div class="w-10 h-10 rounded-lg bg-white/50 dark:bg-white/10 text-indigo-600 dark:text-white flex items-center justify-center shrink-0 font-bold shadow-sm dark:shadow-none">🏁</div>
                            <div><h4 class="text-sm font-bold text-indigo-900 dark:text-white mb-1 transition-colors">Final Project</h4><p class="text-[11px] text-indigo-700 dark:text-white/70 leading-relaxed transition-colors">Membangun komponen Kartu Produk yang lengkap.</p></div>
                        </div>
                    </div>
                </div>

                <article class="space-y-40">
                    
                    {{-- LESSON 1 --}}
                    {{-- PASTIKAN data-lesson-id SESUAI DENGAN ID DI DATABASE COURSE_LESSONS ANDA --}}
                    <section id="section-1" class="lesson-section scroll-mt-32" data-lesson-id="1">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-indigo-500 pl-6">
                                <span class="text-indigo-600 dark:text-indigo-400 font-mono text-xs uppercase tracking-widest transition-colors">Lesson 1.1</span>
                                <h2 class="text-4xl lg:text-5xl font-black text-heading leading-[1.1] transition-colors">
                                    Arsitektur Web & <br> 
                                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-purple-600 dark:from-indigo-400 dark:to-purple-500">Struktur DOM</span>
                                </h2>
                            </div>
                            
                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-heading flex items-center gap-2 transition-colors"><span class="w-6 h-6 rounded bg-indigo-500 flex items-center justify-center text-[10px] text-white">A</span> The Request-Response Cycle</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-lg leading-relaxed space-y-4 transition-colors">
                                    <p>Sebelum kita menulis satu baris kode pun, penting untuk memahami di mana kode tersebut hidup. Web bekerja berdasarkan siklus percakapan antara dua pihak: <strong>Client</strong> (Browser Anda seperti Chrome/Firefox) dan <strong>Server</strong> (Komputer yang menyimpan file website).</p>
                                    <p>Saat Anda mengetik alamat web, Browser mengirimkan <em>Request</em>. Server merespons dengan mengirimkan paket data berupa HTML. HTML inilah yang menjadi instruksi dasar bagi browser untuk mulai menggambar halaman. Tanpa HTML, tidak ada struktur, tidak ada konten, dan tidak ada kerangka untuk ditempati oleh CSS atau JavaScript.</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-heading flex items-center gap-2 transition-colors"><span class="w-6 h-6 rounded bg-purple-500 flex items-center justify-center text-[10px] text-white">B</span> The Document Object Model (DOM)</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-lg leading-relaxed space-y-4 transition-colors">
                                    <p>Browser tidak membaca HTML sebagai teks datar seperti kita membaca buku. Browser mengubah kode HTML menjadi struktur pohon yang hidup yang disebut <strong>DOM (Document Object Model)</strong>. Dalam pohon ini, setiap tag HTML menjadi "node" atau simpul.</p>
                                    <p>Konsep kunci di sini adalah <strong>Nesting</strong> (Sarang). Elemen yang membungkus elemen lain disebut <em>Parent</em>, dan elemen di dalamnya disebut <em>Child</em>. Hubungan kekeluargaan ini sangat krusial karena gaya (CSS) yang kita berikan pada Parent seringkali diwariskan (inherit) kepada Child-nya, mirip seperti pewarisan genetik.</p>
                                </div>
                            </div>

                            {{-- SIMULATOR DOM --}}
                            <div class="sim-bg-adaptive border border-adaptive rounded-xl overflow-hidden shadow-xl dark:shadow-2xl flex flex-col lg:flex-row h-[500px] transition-colors">
                                <div class="w-full lg:w-1/2 code-adaptive border-r flex flex-col transition-colors">
                                    <div class="bg-black/5 dark:bg-[#252525] px-4 py-2 border-b border-adaptive flex justify-between transition-colors"><span class="text-xs font-mono text-slate-500 dark:text-gray-400">index.html</span></div>
                                    <div class="flex-1 p-6 font-mono text-sm overflow-auto custom-scrollbar">
                                        <div class="text-purple-600 dark:text-purple-400">&lt;body&gt;</div>
                                        <div id="dom-code-area" class="pl-4 border-l border-slate-300 dark:border-white/10 ml-1 space-y-1 my-2 min-h-[100px] transition-colors"><span class="text-slate-400 dark:text-gray-600 italic">// Klik tombol di bawah...</span></div>
                                        <div class="text-purple-600 dark:text-purple-400">&lt;/body&gt;</div>
                                    </div>
                                    <div class="p-4 bg-black/5 dark:bg-[#252525] border-t border-adaptive flex gap-2 transition-colors">
                                        <button onclick="toggleDom('nav')" class="px-3 py-1 bg-indigo-100 dark:bg-indigo-600/20 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-500/30 rounded text-xs hover:bg-indigo-200 dark:hover:bg-indigo-600/40 transition">+ Navbar</button>
                                        <button onclick="toggleDom('h1')" class="px-3 py-1 bg-purple-100 dark:bg-purple-600/20 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-500/30 rounded text-xs hover:bg-purple-200 dark:hover:bg-purple-600/40 transition">+ Header</button>
                                        <button onclick="toggleDom('p')" class="px-3 py-1 bg-pink-100 dark:bg-pink-600/20 text-pink-700 dark:text-pink-300 border border-pink-200 dark:border-pink-500/30 rounded text-xs hover:bg-pink-200 dark:hover:bg-pink-600/40 transition">+ Content</button>
                                    </div>
                                </div>
                                <div class="w-full lg:w-1/2 bg-white flex flex-col relative">
                                    <div class="bg-slate-100 border-b border-slate-200 p-2 text-center"><span class="bg-white px-4 py-0.5 rounded text-[10px] text-slate-500 shadow-sm border border-slate-200">Browser Preview</span></div>
                                    <div id="dom-preview-area" class="flex-1 p-8 bg-slate-50 overflow-auto"><div class="text-center mt-20 text-slate-400 italic text-sm">Halaman Kosong</div></div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 2 --}}
                    <section id="section-2" class="lesson-section scroll-mt-32" data-lesson-id="2">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-indigo-500 pl-6">
                                <span class="text-indigo-600 dark:text-indigo-400 font-mono text-xs uppercase tracking-widest transition-colors">Lesson 1.2</span>
                                <h2 class="text-4xl lg:text-5xl font-black text-heading leading-[1.1] transition-colors">
                                    Semantik & <br> 
                                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-purple-600 dark:from-indigo-400 dark:to-purple-500">Atribut HTML</span>
                                </h2>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-heading flex items-center gap-2 transition-colors"><span class="w-6 h-6 rounded bg-indigo-500 flex items-center justify-center text-[10px] text-white">A</span> The Power of Semantics</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-lg leading-relaxed space-y-4 transition-colors">
                                    <p>HTML bukan hanya tentang menampilkan sesuatu di layar; ini tentang memberikan <strong>makna</strong> pada konten tersebut. Di masa lalu, developer menggunakan tag generik <code>&lt;div&gt;</code> untuk segalanya. Ini adalah praktik buruk yang dikenal sebagai "Div Soup".</p>
                                    <p>HTML5 memperkenalkan tag semantik seperti <code>&lt;header&gt;</code>, <code>&lt;nav&gt;</code>, <code>&lt;article&gt;</code>, dan <code>&lt;footer&gt;</code>. Menggunakan tag ini membantu Mesin Pencari (Google) memahami struktur konten Anda (SEO) dan membantu teknologi bantu (Screen Readers) membacakan konten bagi pengguna tunanetra (Aksesibilitas).</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-heading flex items-center gap-2 transition-colors"><span class="w-6 h-6 rounded bg-purple-500 flex items-center justify-center text-[10px] text-white">B</span> Attributes: The Configurator</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-lg leading-relaxed space-y-4 transition-colors">
                                    <p>Tag HTML hanyalah objek dasar. Untuk membuatnya berguna dan spesifik, kita memerlukan <strong>Atribut</strong>. Atribut selalu ditulis di dalam tag pembuka dengan format <code>nama="nilai"</code>.</p>
                                    <p>Contoh paling umum adalah tag Gambar <code>&lt;img&gt;</code>. Tanpa atribut, tag ini tidak melakukan apa-apa. Kita butuh atribut <code>src</code> (source) untuk memberi tahu browser file mana yang harus diambil, dan atribut <code>alt</code> (alternative text) untuk mendeskripsikan gambar tersebut jika gagal dimuat.</p>
                                </div>
                            </div>

                            {{-- SIMULATOR ATRIBUT --}}
                            <div class="card-adaptive rounded-xl overflow-hidden shadow-xl dark:shadow-2xl flex flex-col md:flex-row h-[450px] transition-colors border-adaptive">
                                <div class="w-full md:w-1/2 p-6 flex flex-col gap-6 bg-slate-50 dark:bg-[#18181b] transition-colors border-r border-adaptive">
                                    <div>
                                        <h4 class="text-xs font-bold text-slate-500 dark:text-white/50 uppercase mb-4 transition-colors">Attribute Injector</h4>
                                        <div class="space-y-4">
                                            <div>
                                                <label class="text-[10px] uppercase text-indigo-600 dark:text-indigo-400 font-bold block mb-1 transition-colors">Source (src)</label>
                                                <select id="inp-src" onchange="updateAttrSim()" class="w-full bg-white dark:bg-black/30 border border-slate-300 dark:border-white/10 rounded px-3 py-2 text-xs text-adaptive outline-none focus:border-indigo-500 transition-colors shadow-sm dark:shadow-none">
                                                    <option value="https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=500">coding_setup.jpg</option>
                                                    <option value="https://images.unsplash.com/photo-1518791841217-8f162f1e1131?w=500">kitten.jpg</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="text-[10px] uppercase text-amber-600 dark:text-yellow-400 font-bold block mb-1 transition-colors">Style (Class)</label>
                                                <select id="inp-class" onchange="updateAttrSim()" class="w-full bg-white dark:bg-black/30 border border-slate-300 dark:border-white/10 rounded px-3 py-2 text-xs text-adaptive outline-none focus:border-amber-500 transition-colors shadow-sm dark:shadow-none">
                                                    <option value="rounded-none">class="rounded-none"</option>
                                                    <option value="rounded-xl">class="rounded-xl"</option>
                                                    <option value="rounded-full">class="rounded-full"</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-white dark:bg-black/40 p-4 rounded-lg border border-slate-200 dark:border-white/5 font-mono text-sm leading-6 shadow-inner transition-colors">
                                        <span class="text-blue-600 dark:text-blue-400">&lt;img</span> <br>
                                        &nbsp;&nbsp;<span class="text-indigo-600 dark:text-indigo-400">src</span>="<span id="code-src" class="text-emerald-600 dark:text-green-400">...</span>" <br>
                                        &nbsp;&nbsp;<span class="text-amber-600 dark:text-yellow-400">class</span>="<span id="code-class" class="text-emerald-600 dark:text-green-400">...</span>" <br>
                                        <span class="text-blue-600 dark:text-blue-400">/&gt;</span>
                                    </div>
                                </div>
                                <div class="w-full md:w-1/2 bg-slate-200 dark:bg-[#111] flex items-center justify-center relative bg-[url('https://grainy-gradients.vercel.app/noise.svg')] transition-colors overflow-hidden">
                                    <div class="absolute top-2 right-2 text-[10px] text-slate-500 dark:text-white/30 uppercase tracking-widest transition-colors font-bold bg-white/50 dark:bg-black/50 px-2 py-1 rounded backdrop-blur-sm z-10">Live Output</div>
                                    <img id="view-attr" src="" class="w-48 h-48 object-cover border-4 border-white shadow-xl transition-all duration-500 relative z-0">
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 3 --}}
                    <section id="section-3" class="lesson-section scroll-mt-32" data-lesson-id="3">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-indigo-500 pl-6">
                                <span class="text-indigo-600 dark:text-indigo-400 font-mono text-xs uppercase tracking-widest transition-colors">Lesson 1.3</span>
                                <h2 class="text-4xl lg:text-5xl font-black text-heading leading-[1.1] transition-colors">
                                    Pengenalan <br> 
                                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-purple-600 dark:from-indigo-400 dark:to-purple-500">CSS Styling</span>
                                </h2>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-heading flex items-center gap-2 transition-colors"><span class="w-6 h-6 rounded bg-indigo-500 flex items-center justify-center text-[10px] text-white">A</span> The Separation of Concerns</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-lg leading-relaxed space-y-4 transition-colors">
                                    <p>Jika HTML adalah tulang, maka <strong>CSS (Cascading Style Sheets)</strong> adalah kulit, pakaian, dan riasannya. Filosofi utama pengembangan web modern adalah "Pemisahan Kepentingan". Kita tidak boleh mencampur struktur (HTML) dengan presentasi (CSS).</p>
                                    <p>CSS memungkinkan kita mengubah tampilan ribuan halaman hanya dengan mengedit satu file. Ini memberikan efisiensi yang luar biasa dan konsistensi desain di seluruh website.</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-heading flex items-center gap-2 transition-colors"><span class="w-6 h-6 rounded bg-purple-500 flex items-center justify-center text-[10px] text-white">B</span> Syntax: Selectors & Declarations</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-lg leading-relaxed space-y-4 transition-colors">
                                    <p>Bahasa CSS sangat logis. Ia bekerja dengan pola sederhana: <strong>"Pilih Elemen Ini, Lalu Ubah Properti Itu"</strong>. Bagian pemilihan disebut <em>Selector</em> (seperti tag, class, atau ID), dan bagian aturan disebut <em>Declaration block</em>.</p>
                                    <p>Kekuatan utama CSS ada pada "Cascading" (Air Terjun). Jika ada dua aturan yang bertabrakan, aturan yang lebih spesifik atau yang ditulis terakhir akan menang. Memahami prioritas ini adalah kunci menguasai CSS.</p>
                                </div>
                            </div>

                            {{-- SIMULATOR CSS --}}
                            <div class="sim-bg-adaptive border border-adaptive rounded-2xl p-0 md:p-8 relative shadow-xl dark:shadow-2xl overflow-hidden flex flex-col lg:flex-row gap-0 md:gap-8 transition-colors">
                                <div class="w-full lg:w-1/2 code-adaptive rounded-t-xl md:rounded-xl flex flex-col transition-colors border-b md:border-b-0 border-adaptive">
                                    <div class="bg-black/5 dark:bg-[#252525] px-4 py-2 border-b border-adaptive text-xs text-slate-500 dark:text-gray-400 transition-colors">style.css</div>
                                    <div class="p-6 font-mono text-sm space-y-4">
                                        <div><span class="text-amber-600 dark:text-yellow-400">.btn</span> <span class="text-adaptive">{</span></div>
                                        <div class="pl-4 flex items-center gap-4 group hover:bg-black/5 dark:hover:bg-white/5 p-1 rounded transition-colors">
                                            <span class="text-blue-600 dark:text-blue-400 w-28">background:</span>
                                            <div class="flex gap-2">
                                                <button onclick="updateCss('bg', 'indigo')" class="w-5 h-5 rounded bg-indigo-600 border-2 border-transparent focus:border-slate-400 dark:focus:border-white hover:scale-110 transition shadow-sm"></button>
                                                <button onclick="updateCss('bg', 'rose')" class="w-5 h-5 rounded bg-rose-600 border-2 border-transparent focus:border-slate-400 dark:focus:border-white hover:scale-110 transition shadow-sm"></button>
                                                <button onclick="updateCss('bg', 'emerald')" class="w-5 h-5 rounded bg-emerald-600 border-2 border-transparent focus:border-slate-400 dark:focus:border-white hover:scale-110 transition shadow-sm"></button>
                                            </div>
                                            <span class="text-adaptive">;</span>
                                        </div>
                                        <div class="pl-4 flex items-center gap-4 group hover:bg-black/5 dark:hover:bg-white/5 p-1 rounded transition-colors">
                                            <span class="text-blue-600 dark:text-blue-400 w-28">radius:</span>
                                            <input type="range" min="0" max="30" value="8" oninput="updateCss('rad', this.value)" class="w-24 accent-indigo-600 dark:accent-white h-1 bg-slate-200 dark:bg-white/10 rounded cursor-pointer">
                                            <span id="css-val-rad" class="text-emerald-600 dark:text-green-400 text-xs w-10 font-bold">8px</span>
                                        </div>
                                        <div class="text-adaptive">}</div>
                                    </div>
                                </div>
                                <div class="w-full lg:w-1/2 flex flex-col items-center justify-center bg-slate-100 dark:bg-[#151515] rounded-b-xl md:rounded-xl border border-adaptive min-h-[300px] bg-[url('https://grainy-gradients.vercel.app/noise.svg')] transition-colors relative">
                                    <div class="absolute top-2 left-2 px-2 py-1 bg-white/50 dark:bg-black/50 backdrop-blur rounded text-[9px] font-bold text-slate-500 uppercase tracking-widest shadow-sm">Preview</div>
                                    <button id="css-btn-target" class="text-white font-bold shadow-xl transition-all duration-500 px-8 py-3 text-sm bg-indigo-600 rounded-[8px]">
                                        Click Me!
                                    </button>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 4 --}}
                    <section id="section-4" class="lesson-section scroll-mt-32" data-lesson-id="4">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-indigo-500 pl-6">
                                <span class="text-indigo-600 dark:text-indigo-400 font-mono text-xs uppercase tracking-widest transition-colors">Lesson 1.4</span>
                                <h2 class="text-4xl lg:text-5xl font-black text-heading leading-[1.1] transition-colors">
                                    Warna & <br> 
                                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-purple-600 dark:from-indigo-400 dark:to-purple-500">Tipografi</span>
                                </h2>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-heading flex items-center gap-2 transition-colors"><span class="w-6 h-6 rounded bg-indigo-500 flex items-center justify-center text-[10px] text-white">A</span> Color Systems</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-lg leading-relaxed space-y-4 transition-colors">
                                    <p>Warna menciptakan suasana hati. Di web, kita memiliki beberapa cara untuk mendefinisikan warna. Yang paling populer adalah kode <strong>Hexadecimal</strong> (contoh: <code>#FF5733</code>), yang merupakan kombinasi Merah, Hijau, dan Biru.</p>
                                    <p>Alternatif modern adalah <strong>RGB</strong> dan <strong>HSL</strong> (Hue, Saturation, Lightness). HSL sangat disukai oleh desainer karena lebih intuitif untuk menyesuaikan kecerahan atau intensitas warna tanpa mengubah rona dasarnya.</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-heading flex items-center gap-2 transition-colors"><span class="w-6 h-6 rounded bg-purple-500 flex items-center justify-center text-[10px] text-white">B</span> Web Typography</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-lg leading-relaxed space-y-4 transition-colors">
                                    <p>90% konten web adalah teks. Tipografi yang baik bukan hanya soal memilih font keren, tapi soal keterbacaan (Readability).</p>
                                    <p>Hindari penggunaan satuan <code>px</code> (piksel) untuk ukuran font utama karena bersifat statis. Gunakan satuan relatif seperti <code>rem</code> (Root Em). Satuan <code>rem</code> akan menyesuaikan diri dengan pengaturan preferensi browser pengguna, menjadikan website Anda lebih aksesibel.</p>
                                </div>
                            </div>

                            {{-- SIMULATOR BRAND --}}
                            <div class="code-adaptive p-1 rounded-2xl border shadow-xl dark:shadow-2xl transition-colors">
                                <div class="flex flex-col md:flex-row h-full">
                                    <div class="w-full md:w-1/3 p-6 border-b md:border-b-0 md:border-r border-adaptive space-y-6 transition-colors">
                                        <h4 class="text-xs font-bold text-slate-500 dark:text-white/50 uppercase tracking-widest transition-colors">Design System</h4>
                                        <div>
                                            <label class="text-[10px] text-indigo-600 dark:text-indigo-300 font-bold block mb-2 transition-colors">Primary Color</label>
                                            <div class="relative w-full h-10 rounded-lg overflow-hidden border border-adaptive shadow-sm transition-colors">
                                                <input type="color" id="inp-brand-color" oninput="updateBrand()" value="#6366f1" class="absolute -top-2 -left-2 w-[120%] h-[150%] cursor-pointer">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="text-[10px] text-pink-600 dark:text-pink-300 font-bold block mb-2 transition-colors">Font Size (rem)</label>
                                            <input type="range" min="1.5" max="4" step="0.1" value="2.5" oninput="updateBrand()" id="inp-brand-size" class="w-full accent-pink-600 dark:accent-white h-1.5 bg-slate-200 dark:bg-white/10 rounded-full cursor-pointer">
                                        </div>
                                        <div class="bg-white dark:bg-black/40 p-4 rounded-lg text-[10px] font-mono text-slate-500 dark:text-gray-400 border border-adaptive shadow-inner transition-colors">
                                            <span class="font-bold text-slate-400 dark:text-white/30 mb-2 block">Generated CSS:</span>
                                            <span class="text-amber-600 dark:text-yellow-400">h1</span> { <br>
                                            &nbsp;&nbsp;<span class="text-blue-600 dark:text-blue-400">color</span>: <span id="css-brand-color" class="text-slate-900 dark:text-white font-bold">#6366f1</span>; <br>
                                            &nbsp;&nbsp;<span class="text-blue-600 dark:text-blue-400">font-size</span>: <span id="css-brand-size" class="text-emerald-600 dark:text-green-400 font-bold">2.5rem</span>; <br>
                                            }
                                        </div>
                                    </div>
                                    <div class="w-full md:w-2/3 p-10 flex flex-col justify-center items-center text-center bg-slate-50 dark:bg-[#111] rounded-b-xl md:rounded-r-xl md:rounded-bl-none transition-colors relative overflow-hidden bg-[url('https://grainy-gradients.vercel.app/noise.svg')]">
                                        <h1 id="brand-preview" class="font-black transition-all duration-300 leading-tight drop-shadow-sm" style="color: #6366f1; font-size: 2.5rem;">Design is Intelligence<br>Made Visible.</h1>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 5 --}}
                    <section id="section-5" class="lesson-section scroll-mt-32" data-lesson-id="5">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-indigo-500 pl-6">
                                <span class="text-indigo-600 dark:text-indigo-400 font-mono text-xs uppercase tracking-widest transition-colors">Lesson 1.5</span>
                                <h2 class="text-4xl lg:text-5xl font-black text-heading leading-[1.1] transition-colors">
                                    The <br> 
                                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-purple-600 dark:from-indigo-400 dark:to-purple-500">Box Model</span>
                                </h2>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-heading flex items-center gap-2 transition-colors"><span class="w-6 h-6 rounded bg-indigo-500 flex items-center justify-center text-[10px] text-white">A</span> Everything is a Box</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-lg leading-relaxed space-y-4 transition-colors">
                                    <p>Ini adalah konsep <strong>paling fundamental</strong> dalam layout CSS. Tidak peduli seberapa bulat atau abstrak sebuah elemen terlihat, bagi browser, itu tetaplah sebuah kotak persegi panjang.</p>
                                    <p>Setiap "Kotak" terdiri dari 4 lapisan seperti bawang:<br>
                                    1. <strong>Content:</strong> Isi sebenarnya (teks/gambar).<br>
                                    2. <strong>Padding:</strong> Area transparan di sekitar konten (Bantalan dalam).<br>
                                    3. <strong>Border:</strong> Garis yang mengelilingi padding (Bingkai).<br>
                                    4. <strong>Margin:</strong> Area transparan di luar border (Jarak dengan elemen lain).</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-heading flex items-center gap-2 transition-colors"><span class="w-6 h-6 rounded bg-purple-500 flex items-center justify-center text-[10px] text-white">B</span> Controlling Spacing</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-lg leading-relaxed space-y-4 transition-colors">
                                    <p>Kebingungan terbesar pemula adalah membedakan <strong>Margin</strong> dan <strong>Padding</strong>.</p>
                                    <p>Aturan sederhananya: Gunakan <strong>Padding</strong> jika Anda ingin memberi ruang <em>di dalam</em> kotak (misalnya, agar teks tidak menempel ke garis tepi tombol). Gunakan <strong>Margin</strong> jika Anda ingin memberi jarak <em>di antara</em> dua kotak (misalnya, memberi jarak antar paragraf).</p>
                                </div>
                            </div>

                            {{-- SIMULATOR BOX MODEL --}}
                            <div class="sim-bg-adaptive border border-adaptive rounded-2xl p-4 md:p-8 relative shadow-xl dark:shadow-2xl overflow-hidden flex flex-col items-center transition-colors">
                                
                                <div class="w-full max-w-2xl bg-slate-50 dark:bg-black/80 backdrop-blur p-4 rounded-xl border border-adaptive font-mono text-xs z-20 shadow-sm dark:shadow-lg mb-8 transition-colors flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                    <div>
                                        <div class="text-slate-500 dark:text-gray-400 mb-1 font-bold">.the-box {</div>
                                        <div class="pl-4 text-orange-600 dark:text-orange-400 font-bold">margin: <span id="val-mar" class="text-slate-900 dark:text-white">20</span>px;</div>
                                        <div class="pl-4 text-amber-600 dark:text-yellow-400 font-bold">border: <span id="val-bor" class="text-slate-900 dark:text-white">5</span>px solid;</div>
                                        <div class="pl-4 text-emerald-600 dark:text-green-400 font-bold">padding: <span id="val-pad" class="text-slate-900 dark:text-white">20</span>px;</div>
                                        <div class="text-slate-500 dark:text-gray-400 mt-1 font-bold">}</div>
                                    </div>
                                    
                                    <div class="flex flex-col gap-3 w-full sm:w-1/2">
                                        <div class="flex items-center gap-3"><label class="text-[10px] text-orange-600 dark:text-orange-400 font-bold w-12 uppercase">Margin</label><input type="range" min="0" max="60" value="20" oninput="updateBoxModel('mar', this.value)" class="flex-1 accent-orange-500 h-1.5 bg-slate-200 dark:bg-white/10 rounded-full cursor-pointer"></div>
                                        <div class="flex items-center gap-3"><label class="text-[10px] text-amber-600 dark:text-yellow-400 font-bold w-12 uppercase">Border</label><input type="range" min="0" max="20" value="5" oninput="updateBoxModel('bor', this.value)" class="flex-1 accent-amber-500 dark:accent-yellow-500 h-1.5 bg-slate-200 dark:bg-white/10 rounded-full cursor-pointer"></div>
                                        <div class="flex items-center gap-3"><label class="text-[10px] text-emerald-600 dark:text-green-400 font-bold w-12 uppercase">Padding</label><input type="range" min="0" max="60" value="20" oninput="updateBoxModel('pad', this.value)" class="flex-1 accent-emerald-500 dark:accent-green-500 h-1.5 bg-slate-200 dark:bg-white/10 rounded-full cursor-pointer"></div>
                                    </div>
                                </div>

                                <div class="w-full bg-slate-100 dark:bg-[#0b0f19] rounded-xl flex items-center justify-center p-8 min-h-[350px] border border-adaptive transition-colors overflow-hidden relative bg-[url('https://grainy-gradients.vercel.app/noise.svg')]">
                                    <div id="box-margin" class="relative bg-orange-500/20 border-2 border-dashed border-orange-500 transition-all duration-300 p-[20px]">
                                        <span class="absolute -top-6 left-0 text-[10px] text-orange-600 dark:text-orange-500 font-bold uppercase bg-white dark:bg-[#0b0f19] px-1 rounded-sm shadow-sm dark:shadow-none transition-colors">Margin</span>
                                        <div id="box-border" class="relative bg-white/50 dark:bg-transparent border-[5px] border-amber-400 dark:border-yellow-400 transition-all duration-300">
                                            <span class="absolute -bottom-6 right-0 text-[10px] text-amber-600 dark:text-yellow-400 font-bold uppercase bg-white dark:bg-[#0b0f19] px-1 rounded-sm shadow-sm dark:shadow-none transition-colors">Border</span>
                                            <div id="box-padding" class="relative bg-emerald-500/20 dark:bg-green-500/20 p-[20px] transition-all duration-300">
                                                <span class="absolute -top-6 right-0 text-[10px] text-emerald-700 dark:text-green-500 font-bold uppercase bg-white dark:bg-[#0b0f19] px-1 rounded-sm shadow-sm dark:shadow-none transition-colors">Padding</span>
                                                <div class="w-24 sm:w-40 h-16 sm:h-24 bg-indigo-600 flex items-center justify-center text-white font-bold text-[10px] sm:text-xs shadow-xl relative z-10 border border-indigo-400">CONTENT</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </section>

                    {{-- LESSON 6: ACTIVITY FINAL --}}
                    <section id="section-6" class="lesson-section scroll-mt-32 pt-10 border-t border-adaptive transition-colors" data-lesson-id="6" data-type="activity">
                        <div class="relative rounded-[2rem] sim-bg-adaptive border border-adaptive p-6 md:p-10 overflow-hidden shadow-xl dark:shadow-2xl transition-all duration-500">
                            
                            <div class="absolute -top-24 -right-24 w-64 h-64 bg-indigo-600/10 dark:bg-indigo-600/20 blur-[100px] rounded-full pointer-events-none transition-colors"></div>
                            
                            <div class="flex items-center gap-4 mb-8 relative z-10">
                                <div class="p-3 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl text-white shadow-lg shadow-indigo-500/30"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg></div>
                                <div><h2 class="text-2xl font-bold text-heading transition-colors">Final Mission: Product Card</h2><p class="text-indigo-600 dark:text-indigo-300 text-sm font-medium transition-colors">Gabungkan HTML Semantik, Box Model, dan Styling untuk menyelesaikan misi.</p></div>
                            </div>

                            <div class="grid lg:grid-cols-2 gap-6 max-w-6xl mx-auto relative z-10">
                                
                                {{-- LEFT: EDITOR --}}
                                <div class="code-adaptive rounded-xl flex flex-col overflow-hidden h-full relative shadow-inner transition-colors">
                                    
                                    {{-- LOCK OVERLAY --}}
                                    <div id="lockOverlay" class="hidden absolute inset-0 bg-white/90 dark:bg-[#050912]/95 backdrop-blur-md z-50 flex flex-col items-center justify-center text-center p-6 border-4 border-emerald-500/20 m-1 rounded-lg transition-colors">
                                        <div class="w-20 h-20 bg-emerald-100 dark:bg-green-500/10 rounded-full flex items-center justify-center mb-4 border border-emerald-500/50 shadow-lg animate-bounce"><svg class="w-10 h-10 text-emerald-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" d="M5 13l4 4L19 7" /></svg></div>
                                        <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-2 transition-colors">MISSION COMPLETED!</h3>
                                        <p class="text-base font-bold text-slate-500 dark:text-white/60 mb-8 max-w-xs transition-colors">Subbab 1.1 Tuntas. Modul selanjutnya telah terbuka.</p>
                                        <button disabled class="px-8 py-3 rounded-full bg-slate-200 dark:bg-white/5 border border-slate-300 dark:border-white/10 text-slate-500 dark:text-white/30 text-xs font-bold cursor-not-allowed uppercase transition-colors">Review Mode</button>
                                    </div>

                                    <div class="bg-slate-200 dark:bg-[#2d2d2d] px-4 py-3 border-b border-adaptive flex justify-between items-center transition-colors">
                                        <span class="text-xs text-slate-600 dark:text-gray-400 font-mono font-bold flex items-center gap-2"><div class="w-2.5 h-2.5 rounded-full bg-amber-500"></div> challenge.css</span>
                                        <span class="text-[10px] text-indigo-600 dark:text-indigo-400 font-bold tracking-wider uppercase transition-colors">Interactive</span>
                                    </div>
                                    
                                    <div class="p-6 space-y-8 flex-1 overflow-y-auto custom-scrollbar bg-slate-50 dark:bg-transparent transition-colors">
                                        <form id="activityForm">
                                            <div class="space-y-3">
                                                <label class="text-[10px] uppercase text-slate-500 dark:text-white/50 font-bold tracking-widest transition-colors">1. Wrapper Tag (Semantik)</label>
                                                <div class="grid grid-cols-2 gap-3">
                                                    <button type="button" onclick="setAct('tag', 'div', this)" class="opt-btn-tag px-4 py-3 rounded-xl bg-white dark:bg-white/5 text-slate-600 dark:text-gray-400 border border-slate-300 dark:border-white/5 text-sm font-bold text-center font-mono hover:bg-slate-100 dark:hover:bg-white/10 shadow-sm transition-all">&lt;div&gt;</button>
                                                    <button type="button" onclick="setAct('tag', 'article', this)" class="opt-btn-tag px-4 py-3 rounded-xl bg-white dark:bg-white/5 text-slate-600 dark:text-gray-400 border border-slate-300 dark:border-white/5 text-sm font-bold text-center font-mono hover:bg-slate-100 dark:hover:bg-white/10 shadow-sm transition-all">&lt;article&gt;</button>
                                                </div>
                                            </div>
                                            <div class="space-y-3 mt-6">
                                                <label class="text-[10px] uppercase text-slate-500 dark:text-white/50 font-bold tracking-widest transition-colors">2. Internal Spacing (Box Model)</label>
                                                <div class="grid grid-cols-2 gap-3">
                                                    <button type="button" onclick="setAct('pad', '0px', this)" class="opt-btn-pad px-4 py-3 rounded-xl bg-white dark:bg-white/5 text-slate-600 dark:text-gray-400 border border-slate-300 dark:border-white/5 text-sm font-bold text-center font-mono hover:bg-slate-100 dark:hover:bg-white/10 shadow-sm transition-all">padding: 0</button>
                                                    <button type="button" onclick="setAct('pad', '24px', this)" class="opt-btn-pad px-4 py-3 rounded-xl bg-white dark:bg-white/5 text-slate-600 dark:text-gray-400 border border-slate-300 dark:border-white/5 text-sm font-bold text-center font-mono hover:bg-slate-100 dark:hover:bg-white/10 shadow-sm transition-all">padding: 24px</button>
                                                </div>
                                            </div>
                                            <div class="space-y-3 mt-6">
                                                <label class="text-[10px] uppercase text-slate-500 dark:text-white/50 font-bold tracking-widest transition-colors">3. Visual Style (Styling)</label>
                                                <div class="grid grid-cols-2 gap-3">
                                                    <button type="button" onclick="setAct('style', 'plain', this)" class="opt-btn-style px-4 py-3 rounded-xl bg-white dark:bg-white/5 text-slate-600 dark:text-gray-400 border border-slate-300 dark:border-white/5 text-sm font-bold text-center font-mono hover:bg-slate-100 dark:hover:bg-white/10 shadow-sm transition-all">Plain Text</button>
                                                    <button type="button" onclick="setAct('style', 'card', this)" class="opt-btn-style px-4 py-3 rounded-xl bg-white dark:bg-white/5 text-slate-600 dark:text-gray-400 border border-slate-300 dark:border-white/5 text-sm font-bold text-center font-mono hover:bg-slate-100 dark:hover:bg-white/10 shadow-sm transition-all">Styled Card</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    
                                    <div class="p-4 bg-slate-200/50 dark:bg-black/40 border-t border-adaptive flex justify-between items-center transition-colors">
                                        <span class="text-xs font-bold text-slate-500 dark:text-white/40 font-mono transition-colors" id="status-text">Menunggu Input...</span>
                                        <button onclick="checkSolution()" id="submitBtn" class="px-6 py-3 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold text-xs shadow-lg shadow-indigo-500/20 hover:shadow-indigo-500/40 hover:-translate-y-0.5 transition-all cursor-pointer">Validasi & Kunci</button>
                                    </div>
                                </div>

                                {{-- RIGHT: BROWSER PREVIEW --}}
                                <div class="bg-slate-100 dark:bg-[#1e1e1e] rounded-xl border border-adaptive flex-1 flex flex-col relative overflow-hidden min-h-[400px] shadow-inner transition-colors">
                                    <div class="bg-slate-200 dark:bg-[#2d2d2d] px-4 py-2 border-b border-adaptive flex items-center gap-2 transition-colors">
                                        <div class="flex gap-1.5"><div class="w-3 h-3 rounded-full bg-red-400 border border-red-500/50"></div><div class="w-3 h-3 rounded-full bg-amber-400 border border-amber-500/50"></div><div class="w-3 h-3 rounded-full bg-emerald-400 border border-emerald-500/50"></div></div>
                                        <div class="ml-4 bg-white/50 dark:bg-black/20 flex-1 rounded-md px-3 py-1 font-mono text-[10px] text-slate-500 dark:text-gray-500 text-center shadow-sm dark:shadow-inner transition-colors">localhost:8080/preview</div>
                                    </div>
                                    
                                    <div class="flex-1 bg-white dark:bg-gray-900 p-8 relative overflow-auto flex items-center justify-center transition-colors">
                                        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-5 pointer-events-none"></div>
                                        
                                        <div id="final-card" class="w-72 bg-transparent text-left transition-all duration-500">
                                            <div class="h-40 bg-indigo-100 dark:bg-indigo-900/30 rounded-t-xl relative overflow-hidden mb-4 hidden transition-colors" id="final-img">
                                                <img src="https://images.unsplash.com/photo-1595225476474-87563907a212?w=500" class="w-full h-full object-cover mix-blend-overlay opacity-80" alt="Keyboard">
                                            </div>
                                            <div id="final-content" class="text-slate-900 dark:text-white transition-colors">
                                                <h3 class="font-black text-xl mb-1">Mechanical Keyboard</h3>
                                                <p class="text-sm opacity-70 mb-5 font-medium">High performance clicky switches for developers.</p>
                                                <button class="w-full py-2.5 rounded-lg bg-indigo-600 text-white font-bold text-sm shadow-md hover:bg-indigo-700 transition-colors">Add to Cart - $99</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                </article>

                {{-- FOOTER NAVIGATION --}}
                <div class="mt-32 pt-8 border-t border-adaptive flex justify-between items-center transition-colors">
                    <a href="{{ route('courses.curriculum') ?? '#' }}" class="group flex items-center gap-4 text-muted hover:text-heading transition-colors">
                        <div class="w-12 h-12 rounded-full border border-adaptive flex items-center justify-center group-hover:bg-slate-100 dark:group-hover:bg-white/5 transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        </div>
                        <div class="text-left hidden sm:block">
                            <div class="text-[10px] uppercase tracking-widest font-bold opacity-60">Kembali ke Grid</div>
                            <div class="font-black text-sm">Dashboard Utama</div>
                        </div>
                    </a>
                    
                    <div id="nextChapterBtn" class="group flex items-center gap-4 text-right text-muted cursor-not-allowed opacity-50 pointer-events-none transition-all duration-500">
                        <div class="text-right hidden sm:block">
                            <div id="nextLabel" class="text-[10px] uppercase tracking-widest font-bold opacity-60">Terkunci</div>
                            <div class="font-black text-sm">Konsep Dasar Tailwind</div>
                        </div>
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
    /* --- CONFIGURATION AJAX & PROGRESS --- */
    // PASTIKAN window.LESSON_IDS SAMA DENGAN ID DI DATABASE `course_lessons` ANDA
    window.LESSON_IDS = [1, 2, 3, 4, 5, 6]; 
    window.COMPLETED_IDS = {!! json_encode($completedLessonIds ?? []) !!};
    let completedSet = new Set(window.COMPLETED_IDS);
    
    // Cek status khusus Activity Lesson (Asumsi ID ke-6 adalah Activity finalnya)
    let activityCompleted = completedSet.has(6);
    const ACTIVITY_LESSON_ID = 6; 

    document.addEventListener('DOMContentLoaded', () => {
        initScrollSpy();
        initSidebarScroll();
        initVisualEffects();
        
        // Render Progress Bar awal
        updateProgressUI(false); 
        
        if (activityCompleted) {
            lockActivityUI();
            unlockNextChapter();
        }

        // Inisialisasi observer scroll
        initLessonObserver();
        
        // Tandai sidebar jika sudah komplit
        document.querySelectorAll('.nav-item').forEach(item => {
            const targetId = parseInt(item.getAttribute('data-target').replace('#section-', ''));
            if(completedSet.has(targetId)) {
                markSidebarDone(targetId);
            }
        });
    });

    // ==========================================
    // LOGIKA UPDATE PROGRESS BAR DINAMIS
    // ==========================================
    function updateProgressUI(animate = true) {
        const total = window.LESSON_IDS.length; 
        const done = window.LESSON_IDS.filter(id => completedSet.has(id)).length; 
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

    // ==========================================
    // AJAX POST KE DATABASE MENGGUNAKAN FORM DATA
    // ==========================================
    async function saveLessonToDB(lessonId) { 
        if(completedSet.has(lessonId)) return; // Cegah dobel request

        try {
            console.log("Mencoba save progress untuk ID:", lessonId);
            
            const formData = new FormData();
            formData.append('lesson_id', lessonId);

            // Memastikan URL route benar, atau fallback ke path absolut
            const routeUrl = '{{ route("lesson.complete") }}' || '/lesson/complete';

            const response = await fetch(routeUrl, { 
                method: 'POST', 
                headers: { 
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json' // Meminta respons JSON
                }, 
                body: formData 
            });

            if (response.ok) {
                console.log("✅ Berhasil menyimpan progress ID:", lessonId);
                completedSet.add(lessonId);
                updateProgressUI(true);
                markSidebarDone(lessonId);
            } else {
                const errData = await response.json();
                console.error("❌ Gagal menyimpan progress:", response.status, errData);
            }
        } catch(e) {
            console.error('❌ Network Error saat menyimpan progress:', e);
        }
    }

    // ==========================================
    // OBSERVER SCROLL (DENGAN THRESHOLD 0.1)
    // ==========================================
    function initLessonObserver() {
        const obs = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const id = Number(entry.target.dataset.lessonId);
                    // Panggil fungsi DB save jika ID valid, belum diselesaikan, dan BUKAN kotak activity
                    if (id && entry.target.dataset.type !== 'activity' && !completedSet.has(id)) {
                        saveLessonToDB(id); 
                    }
                }
            });
        }, { 
            threshold: 0.1, // Jauh lebih sensitif agar berhasil dideteksi
            rootMargin: "0px 0px -100px 0px", // Margin bawah diabaikan sedikit agar tidak perlu scroll mentok
            root: document.getElementById('mainScroll') 
        });
        
        document.querySelectorAll('.lesson-section').forEach(s => obs.observe(s));
    }


    /* --- ACTIVITY LOGIC (FINAL PROJECT) --- */
    let actConfig = { tag: '', pad: '', style: '' };

    function setAct(cat, val, btn) {
        if(activityCompleted) return;
        actConfig[cat] = val;
        
        document.querySelectorAll(`.opt-btn-${cat}`).forEach(b => {
            b.classList.remove('bg-indigo-600', 'text-white', 'border-indigo-500', 'shadow-md', 'dark:shadow-[0_0_15px_rgba(99,102,241,0.3)]');
            b.classList.add('bg-white', 'dark:bg-white/5', 'text-slate-600', 'dark:text-gray-400', 'border-slate-300', 'dark:border-white/5');
        });
        
        btn.classList.remove('bg-white', 'dark:bg-white/5', 'text-slate-600', 'dark:text-gray-400', 'border-slate-300', 'dark:border-white/5');
        btn.classList.add('bg-indigo-600', 'text-white', 'border-indigo-500', 'shadow-md', 'dark:shadow-[0_0_15px_rgba(99,102,241,0.3)]');
        
        renderFinalCard();
    }

    function renderFinalCard() {
        const card = document.getElementById('final-card');
        const img = document.getElementById('final-img');
        const content = document.getElementById('final-content');
        
        if(actConfig.style === 'card') {
            card.className = "w-72 bg-white dark:bg-[#0f172a] border border-slate-200 dark:border-white/10 text-left shadow-2xl transition-all duration-500 rounded-2xl overflow-hidden";
            img.classList.remove('hidden');
        } else {
            card.className = "w-72 text-left text-slate-500 dark:text-gray-400";
            img.classList.add('hidden');
        }
        content.className = (actConfig.pad === '24px') ? "p-6 text-slate-900 dark:text-white" : "p-0 text-slate-900 dark:text-white";
    }

    async function checkSolution() {
        if(activityCompleted) return;
        const btn = document.getElementById('submitBtn');
        const status = document.getElementById('status-text');
        
        btn.innerHTML = '<span class="animate-pulse">Memeriksa...</span>'; btn.disabled = true;
        await new Promise(r => setTimeout(r, 1200)); 

        if(actConfig.tag === 'article' && actConfig.pad === '24px' && actConfig.style === 'card') {
            status.innerText = "BENAR! VALIDASI BERHASIL."; 
            status.className = "text-[11px] text-emerald-600 dark:text-green-400 font-mono font-bold tracking-wider";
            
            // Simpan Data Progress Activity ini ke DB via AJAX yang sama
            await saveLessonToDB(ACTIVITY_LESSON_ID); 
            
            activityCompleted = true;
            lockActivityUI();
            unlockNextChapter();
        } else {
            status.innerText = "Hint: Gunakan tag semantik <article>, Padding besar (24px), dan Style Card."; 
            status.className = "text-[10px] text-red-500 dark:text-red-400 font-mono font-bold leading-tight";
            btn.innerText = "Coba Lagi"; btn.disabled = false; btn.classList.add('shake'); setTimeout(() => btn.classList.remove('shake'), 500);
        }
    }

    function lockActivityUI() {
        document.getElementById('lockOverlay').classList.remove('hidden');
        const btn = document.getElementById('submitBtn'); 
        btn.innerText = "Terkunci (Selesai)"; btn.disabled = true;
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
            
            document.getElementById('nextLabel').innerText = "Selanjutnya";
            document.getElementById('nextLabel').classList.remove('opacity-60');
            document.getElementById('nextLabel').classList.add('text-indigo-600', 'dark:text-indigo-400', 'opacity-100');
            
            const icon = document.getElementById('nextIcon');
            icon.innerHTML = `<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>`;
            icon.classList.remove('bg-slate-100', 'dark:bg-white/5');
            icon.classList.add('bg-indigo-100', 'dark:bg-indigo-500/20', 'border-indigo-300', 'dark:border-indigo-500/50', 'text-indigo-600', 'dark:text-indigo-400', 'shadow-lg');
            
            btn.onclick = () => window.location.href = "{{ route('courses.tailwindcss') ?? '#' }}"; 
        }
    }

    /* --- SIMULATORS LOGIC --- */
    function toggleDom(el) {
        // ... (Fungsi toggle DOM tidak berubah, mempertahankan simulasi interaktif)
        let domState = window.domState || { nav: false, h1: false, p: false };
        window.domState = domState;
        domState[el] = !domState[el];
        const code = document.getElementById('dom-code-area');
        const view = document.getElementById('dom-preview-area');
        let hC = '', hV = '';
        
        if(domState.nav) { 
            hC += `<div class="text-indigo-600 dark:text-indigo-400 font-bold">&lt;nav&gt;<span class="text-slate-400">...</span>&lt;/nav&gt;</div>`; 
            hV += `<div class="w-full h-10 bg-indigo-500 rounded-md mb-4 shadow-md flex items-center px-4"><div class="w-4 h-4 rounded-full bg-white/50"></div></div>`; 
        }
        if(domState.h1) { 
            hC += `<div class="text-purple-600 dark:text-purple-400 font-bold">&lt;h1&gt;<span class="text-slate-400">Judul Utama</span>&lt;/h1&gt;</div>`; 
            hV += `<h1 class="text-2xl font-black text-slate-800 mb-2 tracking-tight">Judul Website</h1>`; 
        }
        if(domState.p) { 
            hC += `<div class="text-pink-600 dark:text-pink-400 font-bold">&lt;p&gt;<span class="text-slate-400">Sebuah paragraf.</span>&lt;/p&gt;</div>`; 
            hV += `<div class="space-y-2"><div class="h-2 bg-slate-300 rounded w-full"></div><div class="h-2 bg-slate-300 rounded w-5/6"></div><div class="h-2 bg-slate-300 rounded w-4/6"></div></div>`; 
        }
        
        code.innerHTML = hC || '<span class="text-slate-400 dark:text-gray-600 italic">// Klik tombol untuk inject elemen</span>';
        view.innerHTML = hV || '<div class="text-center mt-16 text-slate-300 font-bold text-sm">Document Kosong</div>';
    }

    function updateAttrSim() {
        const src = document.getElementById('inp-src').value;
        const cls = document.getElementById('inp-class').value;
        document.getElementById('code-src').innerText = src.includes('coding') ? 'coding.jpg' : 'cat.jpg';
        document.getElementById('code-class').innerText = cls;
        const img = document.getElementById('view-attr');
        img.src = src; img.className = `w-48 h-48 object-cover border-4 border-white dark:border-white/10 transition-all duration-500 shadow-xl ${cls}`;
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
        
        h1.style.color = color; 
        h1.style.fontSize = size + 'rem';
        
        document.getElementById('css-brand-color').innerText = color;
        document.getElementById('css-brand-size').innerText = size + 'rem';
    }

    function updateBoxModel(prop, val) {
        if(prop === 'pad') document.getElementById('box-padding').style.padding = val + 'px';
        if(prop === 'bor') document.getElementById('box-border').style.borderWidth = val + 'px';
        if(prop === 'mar') document.getElementById('box-margin').style.padding = val + 'px';
        document.getElementById(`val-${prop}`).innerText = val;
    }

    /* --- SCROLL SPY LOGIC --- */
    function initScrollSpy() {
        const mainScroll = document.getElementById('mainScroll'); 
        const anchors = document.querySelectorAll('.sidebar-anchor');
        const sections = document.querySelectorAll('.lesson-section');

        if (mainScroll && sections.length > 0) {
            const observerOptions = { root: mainScroll, threshold: 0.5 };
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const isDark = document.documentElement.classList.contains('dark');
                        anchors.forEach(a => {
                            a.classList.remove('bg-slate-100', 'dark:bg-white/5', 'border-fuchsia-500');
                            a.classList.add('border-transparent');
                            const dot = a.querySelector('.anchor-dot');
                            dot.classList.remove('bg-fuchsia-500', 'dark:bg-fuchsia-400', 'scale-125', 'shadow-sm', 'dark:shadow-[0_0_10px_#e879f9]');
                            dot.classList.add('bg-slate-400', 'dark:bg-slate-600');
                            const text = a.querySelector('.anchor-text');
                            text.classList.remove('text-slate-800', 'dark:text-white', 'font-bold');
                            text.classList.add('text-slate-500');
                        });

                        const activeAnchor = document.querySelector(`.sidebar-anchor[data-target="${entry.target.id}"]`);
                        if (activeAnchor) {
                            activeAnchor.classList.add(isDark ? 'dark:bg-white/5' : 'bg-slate-100', 'border-fuchsia-500');
                            activeAnchor.classList.remove('border-transparent');
                            
                            const dot = activeAnchor.querySelector('.anchor-dot');
                            dot.classList.remove('bg-slate-400', 'dark:bg-slate-600');
                            dot.classList.add(isDark ? 'dark:bg-fuchsia-400' : 'bg-fuchsia-500', 'scale-125', isDark ? 'dark:shadow-[0_0_10px_#e879f9]' : 'shadow-sm');
                            
                            const text = activeAnchor.querySelector('.anchor-text');
                            text.classList.remove('text-slate-500');
                            text.classList.add(isDark ? 'dark:text-white' : 'text-slate-800', 'font-bold');
                        }
                    }
                });
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

        l.forEach(k => k.addEventListener('click', (e) => {
            const t = document.querySelector(k.getAttribute('data-target'));
            if (t) m.scrollTo({top: t.offsetTop - 120, behavior: 'smooth'});
        }));
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