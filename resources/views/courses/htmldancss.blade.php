@extends('layouts.landing')
@section('title','Bab 1.1 · HTML & CSS Foundations')

@section('content')
<div id="courseRoot" class="relative h-screen bg-[#020617] text-white font-sans overflow-hidden flex flex-col selection:bg-indigo-500/30">

    <div class="fixed inset-0 -z-50 pointer-events-none">
        <div id="animated-bg" class="absolute inset-0 opacity-50"></div>
        <div class="absolute top-[-20%] left-[-10%] w-[1000px] h-[1000px] bg-indigo-900/10 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute bottom-[-20%] right-[-10%] w-[800px] h-[800px] bg-purple-900/10 rounded-full blur-[100px]"></div>
        <canvas id="stars" class="absolute inset-0 pointer-events-none"></canvas>
        <div id="noise-overlay" class="absolute inset-0 z-10 opacity-[0.03]"></div>
        <div id="cursor-glow"></div>
    </div>

    <nav id="navbar" class="h-[74px] w-full bg-[#020617]/10 backdrop-blur-xl border-b border-white/5 shrink-0 z-50 flex items-center justify-between px-6 lg:px-8 transition-all duration-500 relative">
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-fuchsia-500 to-cyan-400 flex items-center justify-center font-extrabold text-black shadow-xl">TW</div>
            <span class="font-semibold tracking-wide text-lg">TailwindLearn</span>
        </div>
        <div class="hidden md:flex gap-10 text-sm font-medium">
            <a href="{{ route('landing') }}" class="nav-link opacity-70 hover:opacity-100 transition">Beranda</a>
            <span class="nav-link active cursor-default">Course</span> 
            <a href="{{ route('dashboard') }}" class="nav-link opacity-70 hover:opacity-100 transition">Dashboard</a>
            <a href="{{ route('sandbox') }}" class="nav-link opacity-70 hover:opacity-100 transition">Sandbox</a>
        </div>
        <div class="flex gap-3 items-center">
            <span class="text-white/70 text-sm hidden sm:block">{{ Auth::user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="cta-main px-6 py-2 rounded-xl bg-gradient-to-r from-fuchsia-500 to-purple-600 text-sm font-semibold shadow-xl hover:scale-105 transition">Keluar</button>
            </form>
        </div>
    </nav>

    <div class="flex flex-1 overflow-hidden relative z-20">

        @include('layouts.partials.course-sidebar')

        <main id="mainScroll" class="flex-1 h-full overflow-y-auto scroll-smooth relative bg-transparent custom-scrollbar scroll-padding-top-24">
            
            <div id="stickyHeader" class="sticky top-0 z-30 w-full bg-[#020617]/80 backdrop-blur-2xl border-b border-white/5 px-8 py-4 flex items-center justify-between transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500/20 to-transparent border border-indigo-500/20 flex items-center justify-center font-bold text-xs text-indigo-400">1.1</div>
                    <div>
                        <h1 class="text-sm font-bold text-white">Konsep Dasar HTML & CSS</h1>
                        <p class="text-[10px] text-white/50">Mastering the Building Blocks</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="hidden sm:block w-32 h-1.5 bg-white/10 rounded-full overflow-hidden">
                        <div id="topProgressBar" class="h-full bg-gradient-to-r from-indigo-500 to-purple-500 w-0 transition-all duration-500 shadow-[0_0_10px_#6366f1]"></div>
                    </div>
                    <span id="progressLabelTop" class="text-indigo-400 font-bold text-xs">0%</span>
                </div>
            </div>

            <div class="p-6 lg:p-16 max-w-5xl mx-auto pb-40">
                
                <div class="mb-24">
                    <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Tujuan Pembelajaran
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <div class="bg-[#1e1e1e] border border-white/5 p-5 rounded-xl flex items-start gap-4 hover:border-indigo-500/30 transition group h-full">
                            <div class="w-10 h-10 rounded-lg bg-indigo-500/10 text-indigo-400 flex items-center justify-center shrink-0 font-bold">1</div>
                            <div><h4 class="text-sm font-bold text-white mb-1">Struktur DOM</h4><p class="text-[11px] text-white/50 leading-relaxed">Memahami hierarki elemen dan alur rendering browser.</p></div>
                        </div>
                        <div class="bg-[#1e1e1e] border border-white/5 p-5 rounded-xl flex items-start gap-4 hover:border-purple-500/30 transition group h-full">
                            <div class="w-10 h-10 rounded-lg bg-purple-500/10 text-purple-400 flex items-center justify-center shrink-0 font-bold">2</div>
                            <div><h4 class="text-sm font-bold text-white mb-1">Semantik & Atribut</h4><p class="text-[11px] text-white/50 leading-relaxed">Menerapkan tag bermakna untuk SEO dan aksesibilitas.</p></div>
                        </div>
                        <div class="bg-[#1e1e1e] border border-white/5 p-5 rounded-xl flex items-start gap-4 hover:border-pink-500/30 transition group h-full">
                            <div class="w-10 h-10 rounded-lg bg-pink-500/10 text-pink-400 flex items-center justify-center shrink-0 font-bold">3</div>
                            <div><h4 class="text-sm font-bold text-white mb-1">Sintaks CSS</h4><p class="text-[11px] text-white/50 leading-relaxed">Menguasai selektor, properti, dan nilai gaya.</p></div>
                        </div>
                        <div class="bg-[#1e1e1e] border border-white/5 p-5 rounded-xl flex items-start gap-4 hover:border-blue-500/30 transition group h-full">
                            <div class="w-10 h-10 rounded-lg bg-blue-500/10 text-blue-400 flex items-center justify-center shrink-0 font-bold">4</div>
                            <div><h4 class="text-sm font-bold text-white mb-1">Warna & Font</h4><p class="text-[11px] text-white/50 leading-relaxed">Mengatur estetika visual dan tipografi modern.</p></div>
                        </div>
                        <div class="bg-[#1e1e1e] border border-white/5 p-5 rounded-xl flex items-start gap-4 hover:border-orange-500/30 transition group h-full">
                            <div class="w-10 h-10 rounded-lg bg-orange-500/10 text-orange-400 flex items-center justify-center shrink-0 font-bold">5</div>
                            <div><h4 class="text-sm font-bold text-white mb-1">The Box Model</h4><p class="text-[11px] text-white/50 leading-relaxed">Konsep fundamental layout: Margin, Border, Padding.</p></div>
                        </div>
                        <div class="bg-gradient-to-br from-indigo-900/40 to-purple-900/40 border border-indigo-500/30 p-5 rounded-xl flex items-start gap-4 hover:shadow-[0_0_20px_rgba(99,102,241,0.2)] transition group h-full">
                            <div class="w-10 h-10 rounded-lg bg-white/10 text-white flex items-center justify-center shrink-0 font-bold">🏁</div>
                            <div><h4 class="text-sm font-bold text-white mb-1">Final Project</h4><p class="text-[11px] text-white/70 leading-relaxed">Membangun komponen Kartu Produk yang lengkap.</p></div>
                        </div>
                    </div>
                </div>

                <article class="space-y-40">
                    
                    <section id="section-1" class="lesson-section scroll-mt-32" data-lesson-id="1">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-indigo-500 pl-6">
                                <span class="text-indigo-400 font-mono text-xs uppercase tracking-widest">Lesson 1.1</span>
                                <h2 class="text-4xl lg:text-5xl font-black text-white leading-[1.1]">
                                    Arsitektur Web & <br> 
                                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-purple-500">Struktur DOM</span>
                                </h2>
                            </div>
                            
                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-white flex items-center gap-2"><span class="w-6 h-6 rounded bg-indigo-500 flex items-center justify-center text-[10px] text-white">A</span> The Request-Response Cycle</h3>
                                <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed space-y-4">
                                    <p>
                                        Sebelum kita menulis satu baris kode pun, penting untuk memahami di mana kode tersebut hidup. Web bekerja berdasarkan siklus percakapan antara dua pihak: <strong>Client</strong> (Browser Anda seperti Chrome/Firefox) dan <strong>Server</strong> (Komputer yang menyimpan file website).
                                    </p>
                                    <p>
                                        Saat Anda mengetik alamat web, Browser mengirimkan <em>Request</em>. Server merespons dengan mengirimkan paket data berupa HTML. HTML inilah yang menjadi instruksi dasar bagi browser untuk mulai menggambar halaman. Tanpa HTML, tidak ada struktur, tidak ada konten, dan tidak ada kerangka untuk ditempati oleh CSS atau JavaScript.
                                    </p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-white flex items-center gap-2"><span class="w-6 h-6 rounded bg-purple-500 flex items-center justify-center text-[10px] text-white">B</span> The Document Object Model (DOM)</h3>
                                <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed space-y-4">
                                    <p>
                                        Browser tidak membaca HTML sebagai teks datar seperti kita membaca buku. Browser mengubah kode HTML menjadi struktur pohon yang hidup yang disebut <strong>DOM (Document Object Model)</strong>. Dalam pohon ini, setiap tag HTML menjadi "node" atau simpul.
                                    </p>
                                    <p>
                                        Konsep kunci di sini adalah <strong>Nesting</strong> (Sarang). Elemen yang membungkus elemen lain disebut <em>Parent</em>, dan elemen di dalamnya disebut <em>Child</em>. Hubungan kekeluargaan ini sangat krusial karena gaya (CSS) yang kita berikan pada Parent seringkali diwariskan (inherit) kepada Child-nya, mirip seperti pewarisan genetik.
                                    </p>
                                </div>
                            </div>

                            <div class="bg-[#0b0f19] border border-white/10 rounded-xl overflow-hidden shadow-2xl flex flex-col lg:flex-row h-[500px]">
                                <div class="w-full lg:w-1/2 bg-[#1e1e1e] border-r border-white/5 flex flex-col">
                                    <div class="bg-[#252525] px-4 py-2 border-b border-white/5 flex justify-between"><span class="text-xs font-mono text-gray-500">index.html</span></div>
                                    <div class="flex-1 p-6 font-mono text-sm overflow-auto custom-scrollbar">
                                        <div class="text-purple-400">&lt;body&gt;</div>
                                        <div id="dom-code-area" class="pl-4 border-l border-white/10 ml-1 space-y-1 my-2 min-h-[100px]"><span class="text-gray-600 italic">// Klik tombol di bawah...</span></div>
                                        <div class="text-purple-400">&lt;/body&gt;</div>
                                    </div>
                                    <div class="p-4 bg-[#252525] border-t border-white/5 flex gap-2">
                                        <button onclick="toggleDom('nav')" class="px-3 py-1 bg-indigo-600/20 text-indigo-300 border border-indigo-500/30 rounded text-xs hover:bg-indigo-600/40 transition">+ Navbar</button>
                                        <button onclick="toggleDom('h1')" class="px-3 py-1 bg-purple-600/20 text-purple-300 border border-purple-500/30 rounded text-xs hover:bg-purple-600/40 transition">+ Header</button>
                                        <button onclick="toggleDom('p')" class="px-3 py-1 bg-pink-600/20 text-pink-300 border border-pink-500/30 rounded text-xs hover:bg-pink-600/40 transition">+ Content</button>
                                    </div>
                                </div>
                                <div class="w-full lg:w-1/2 bg-white flex flex-col">
                                    <div class="bg-gray-100 border-b border-gray-300 p-2 text-center"><span class="bg-white px-4 py-0.5 rounded text-[10px] text-gray-400 shadow-sm">Browser Preview</span></div>
                                    <div id="dom-preview-area" class="flex-1 p-8 bg-gray-50 overflow-auto"><div class="text-center mt-20 text-gray-300 italic text-sm">Halaman Kosong</div></div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="section-2" class="lesson-section scroll-mt-32" data-lesson-id="2">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-indigo-500 pl-6">
                                <span class="text-indigo-400 font-mono text-xs uppercase tracking-widest">Lesson 1.2</span>
                                <h2 class="text-4xl lg:text-5xl font-black text-white leading-[1.1]">
                                    Semantik & <br> 
                                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-purple-500">Atribut HTML</span>
                                </h2>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-white flex items-center gap-2"><span class="w-6 h-6 rounded bg-indigo-500 flex items-center justify-center text-[10px] text-white">A</span> The Power of Semantics</h3>
                                <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed space-y-4">
                                    <p>
                                        HTML bukan hanya tentang menampilkan sesuatu di layar; ini tentang memberikan <strong>makna</strong> pada konten tersebut. Di masa lalu, developer menggunakan tag generik <code>&lt;div&gt;</code> untuk segalanya. Ini adalah praktik buruk yang dikenal sebagai "Div Soup".
                                    </p>
                                    <p>
                                        HTML5 memperkenalkan tag semantik seperti <code>&lt;header&gt;</code>, <code>&lt;nav&gt;</code>, <code>&lt;article&gt;</code>, dan <code>&lt;footer&gt;</code>. Menggunakan tag ini membantu Mesin Pencari (Google) memahami struktur konten Anda (SEO) dan membantu teknologi bantu (Screen Readers) membacakan konten bagi pengguna tunanetra (Aksesibilitas).
                                    </p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-white flex items-center gap-2"><span class="w-6 h-6 rounded bg-purple-500 flex items-center justify-center text-[10px] text-white">B</span> Attributes: The Configurator</h3>
                                <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed space-y-4">
                                    <p>
                                        Tag HTML hanyalah objek dasar. Untuk membuatnya berguna dan spesifik, kita memerlukan <strong>Atribut</strong>. Atribut selalu ditulis di dalam tag pembuka dengan format <code>nama="nilai"</code>.
                                    </p>
                                    <p>
                                        Contoh paling umum adalah tag Gambar <code>&lt;img&gt;</code>. Tanpa atribut, tag ini tidak melakukan apa-apa. Kita butuh atribut <code>src</code> (source) untuk memberi tahu browser file mana yang harus diambil, dan atribut <code>alt</code> (alternative text) untuk mendeskripsikan gambar tersebut jika gagal dimuat.
                                    </p>
                                </div>
                            </div>

                            <div class="bg-[#1e1e1e] rounded-xl overflow-hidden border border-white/10 shadow-2xl flex flex-col md:flex-row h-[450px]">
                                <div class="w-full md:w-1/2 p-6 flex flex-col gap-6 bg-[#18181b]">
                                    <div>
                                        <h4 class="text-xs font-bold text-white/50 uppercase mb-4">Attribute Injector</h4>
                                        <div class="space-y-4">
                                            <div>
                                                <label class="text-[10px] uppercase text-indigo-400 font-bold block mb-1">Source (src)</label>
                                                <select id="inp-src" onchange="updateAttrSim()" class="w-full bg-black/30 border border-white/10 rounded px-3 py-2 text-xs text-white outline-none focus:border-indigo-500 transition">
                                                    <option value="https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=500">coding_setup.jpg</option>
                                                    <option value="https://images.unsplash.com/photo-1518791841217-8f162f1e1131?w=500">kitten.jpg</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="text-[10px] uppercase text-yellow-400 font-bold block mb-1">Style (Class)</label>
                                                <select id="inp-class" onchange="updateAttrSim()" class="w-full bg-black/30 border border-white/10 rounded px-3 py-2 text-xs text-white outline-none focus:border-yellow-500 transition">
                                                    <option value="rounded-none">class="rounded-none"</option>
                                                    <option value="rounded-xl">class="rounded-xl"</option>
                                                    <option value="rounded-full">class="rounded-full"</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-black/40 p-4 rounded-lg border border-white/5 font-mono text-sm leading-6">
                                        <span class="text-blue-400">&lt;img</span> <br>
                                        &nbsp;&nbsp;<span class="text-indigo-400">src</span>="<span id="code-src" class="text-green-400">...</span>" <br>
                                        &nbsp;&nbsp;<span class="text-yellow-400">class</span>="<span id="code-class" class="text-green-400">...</span>" <br>
                                        <span class="text-blue-400">/&gt;</span>
                                    </div>
                                </div>
                                <div class="w-full md:w-1/2 bg-[#111] flex items-center justify-center relative border-l border-white/5 bg-[url('https://grainy-gradients.vercel.app/noise.svg')]">
                                    <div class="absolute top-2 right-2 text-[10px] text-white/20 uppercase tracking-widest">Live Output</div>
                                    <img id="view-attr" src="" class="w-48 h-48 object-cover border-2 border-white/10 transition-all duration-500 shadow-2xl">
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="section-3" class="lesson-section scroll-mt-32" data-lesson-id="3">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-indigo-500 pl-6">
                                <span class="text-indigo-400 font-mono text-xs uppercase tracking-widest">Lesson 1.3</span>
                                <h2 class="text-4xl lg:text-5xl font-black text-white leading-[1.1]">
                                    Pengenalan <br> 
                                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-purple-500">CSS Styling</span>
                                </h2>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-white flex items-center gap-2"><span class="w-6 h-6 rounded bg-indigo-500 flex items-center justify-center text-[10px] text-white">A</span> The Separation of Concerns</h3>
                                <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed space-y-4">
                                    <p>
                                        Jika HTML adalah tulang, maka <strong>CSS (Cascading Style Sheets)</strong> adalah kulit, pakaian, dan riasannya. Filosofi utama pengembangan web modern adalah "Pemisahan Kepentingan". Kita tidak boleh mencampur struktur (HTML) dengan presentasi (CSS).
                                    </p>
                                    <p>
                                        CSS memungkinkan kita mengubah tampilan ribuan halaman hanya dengan mengedit satu file. Ini memberikan efisiensi yang luar biasa dan konsistensi desain di seluruh website.
                                    </p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-white flex items-center gap-2"><span class="w-6 h-6 rounded bg-purple-500 flex items-center justify-center text-[10px] text-white">B</span> Syntax: Selectors & Declarations</h3>
                                <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed space-y-4">
                                    <p>
                                        Bahasa CSS sangat logis. Ia bekerja dengan pola sederhana: <strong>"Pilih Elemen Ini, Lalu Ubah Properti Itu"</strong>. Bagian pemilihan disebut <em>Selector</em> (seperti tag, class, atau ID), dan bagian aturan disebut <em>Declaration block</em>.
                                    </p>
                                    <p>
                                        Kekuatan utama CSS ada pada "Cascading" (Air Terjun). Jika ada dua aturan yang bertabrakan, aturan yang lebih spesifik atau yang ditulis terakhir akan menang. Memahami prioritas ini adalah kunci menguasai CSS.
                                    </p>
                                </div>
                            </div>

                            <div class="bg-[#0b0f19] border border-white/10 rounded-2xl p-8 relative shadow-2xl overflow-hidden flex flex-col lg:flex-row gap-8">
                                <div class="w-full lg:w-1/2 bg-[#1e1e1e] rounded-xl border border-white/10 flex flex-col">
                                    <div class="bg-[#252525] px-4 py-2 border-b border-white/5 text-xs text-gray-400">style.css</div>
                                    <div class="p-6 font-mono text-sm space-y-4">
                                        <div><span class="text-yellow-400">.btn</span> <span class="text-white">{</span></div>
                                        <div class="pl-4 flex items-center gap-4 group hover:bg-white/5 p-1 rounded transition">
                                            <span class="text-blue-400 w-28">background:</span>
                                            <div class="flex gap-2">
                                                <button onclick="updateCss('bg', 'indigo')" class="w-4 h-4 rounded bg-indigo-600 border border-white/20 hover:scale-125 transition"></button>
                                                <button onclick="updateCss('bg', 'rose')" class="w-4 h-4 rounded bg-rose-600 border border-white/20 hover:scale-125 transition"></button>
                                                <button onclick="updateCss('bg', 'emerald')" class="w-4 h-4 rounded bg-emerald-600 border border-white/20 hover:scale-125 transition"></button>
                                            </div>
                                            <span class="text-white">;</span>
                                        </div>
                                        <div class="pl-4 flex items-center gap-4 group hover:bg-white/5 p-1 rounded transition">
                                            <span class="text-blue-400 w-28">radius:</span>
                                            <input type="range" min="0" max="30" value="8" oninput="updateCss('rad', this.value)" class="w-24 accent-white h-1 bg-white/10 rounded">
                                            <span id="css-val-rad" class="text-green-400 text-xs w-10">8px</span>
                                        </div>
                                        <div class="text-white">}</div>
                                    </div>
                                </div>
                                <div class="w-full lg:w-1/2 flex flex-col items-center justify-center bg-[#151515] rounded-xl border border-white/5 min-h-[300px] bg-[url('https://grainy-gradients.vercel.app/noise.svg')]">
                                    <button id="css-btn-target" class="text-white font-bold shadow-2xl transition-all duration-500 border border-white/10 px-8 py-3 text-sm">
                                        Click Me!
                                    </button>
                                    <p class="text-white/20 text-xs mt-8 font-mono">Live CSS Rendering</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="section-4" class="lesson-section scroll-mt-32" data-lesson-id="4">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-indigo-500 pl-6">
                                <span class="text-indigo-400 font-mono text-xs uppercase tracking-widest">Lesson 1.4</span>
                                <h2 class="text-4xl lg:text-5xl font-black text-white leading-[1.1]">
                                    Warna & <br> 
                                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-purple-500">Tipografi</span>
                                </h2>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-white flex items-center gap-2"><span class="w-6 h-6 rounded bg-indigo-500 flex items-center justify-center text-[10px] text-white">A</span> Color Systems</h3>
                                <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed space-y-4">
                                    <p>
                                        Warna menciptakan suasana hati. Di web, kita memiliki beberapa cara untuk mendefinisikan warna. Yang paling populer adalah kode <strong>Hexadecimal</strong> (contoh: <code>#FF5733</code>), yang merupakan kombinasi Merah, Hijau, dan Biru.
                                    </p>
                                    <p>
                                        Alternatif modern adalah <strong>RGB</strong> dan <strong>HSL</strong> (Hue, Saturation, Lightness). HSL sangat disukai oleh desainer karena lebih intuitif untuk menyesuaikan kecerahan atau intensitas warna tanpa mengubah rona dasarnya.
                                    </p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-white flex items-center gap-2"><span class="w-6 h-6 rounded bg-purple-500 flex items-center justify-center text-[10px] text-white">B</span> Web Typography</h3>
                                <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed space-y-4">
                                    <p>
                                        90% konten web adalah teks. Tipografi yang baik bukan hanya soal memilih font keren, tapi soal keterbacaan (Readability).
                                    </p>
                                    <p>
                                        Hindari penggunaan satuan <code>px</code> (piksel) untuk ukuran font utama karena bersifat statis. Gunakan satuan relatif seperti <code>rem</code> (Root Em). Satuan <code>rem</code> akan menyesuaikan diri dengan pengaturan preferensi browser pengguna, menjadikan website Anda lebih aksesibel.
                                    </p>
                                </div>
                            </div>
                            
                            <div class="bg-[#1e1e1e] p-1 rounded-2xl border border-white/10 shadow-2xl">
                                <div class="flex flex-col md:flex-row h-full">
                                    <div class="w-full md:w-1/3 p-6 border-r border-white/5 space-y-6">
                                        <h4 class="text-xs font-bold text-white/50 uppercase tracking-widest">Design System</h4>
                                        <div><label class="text-[10px] text-indigo-300 font-bold block mb-2">Primary Color</label><input type="color" id="inp-brand-color" oninput="updateBrand()" value="#6366f1" class="w-full h-10 rounded bg-transparent border border-white/10 cursor-pointer"></div>
                                        <div><label class="text-[10px] text-pink-300 font-bold block mb-2">Font Size (rem)</label><input type="range" min="1.5" max="4" step="0.1" value="2.5" oninput="updateBrand()" id="inp-brand-size" class="w-full accent-white h-1 bg-white/10 rounded"></div>
                                        <div class="bg-black/40 p-4 rounded text-[10px] font-mono text-gray-400 border border-white/5">Generated CSS: <br><span class="text-yellow-400">h1</span> { <br>&nbsp;&nbsp;color: <span id="css-brand-color" class="text-white">...</span>; <br>&nbsp;&nbsp;font-size: <span id="css-brand-size" class="text-white">...</span>; <br>}</div>
                                    </div>
                                    <div class="w-full md:w-2/3 p-10 flex flex-col justify-center items-center text-center bg-[#111] rounded-r-xl">
                                        <h1 id="brand-preview" class="font-bold text-white transition-all duration-300 leading-tight">Design is Intelligence<br>Made Visible.</h1>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="section-5" class="lesson-section scroll-mt-32" data-lesson-id="5">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-indigo-500 pl-6">
                                <span class="text-indigo-400 font-mono text-xs uppercase tracking-widest">Lesson 1.5</span>
                                <h2 class="text-4xl lg:text-5xl font-black text-white leading-[1.1]">
                                    The <br> 
                                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-purple-500">Box Model</span>
                                </h2>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-white flex items-center gap-2"><span class="w-6 h-6 rounded bg-indigo-500 flex items-center justify-center text-[10px] text-white">A</span> Everything is a Box</h3>
                                <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed space-y-4">
                                    <p>
                                        Ini adalah konsep <strong>paling fundamental</strong> dalam layout CSS. Tidak peduli seberapa bulat atau abstrak sebuah elemen terlihat, bagi browser, itu tetaplah sebuah kotak persegi panjang.
                                    </p>
                                    <p>
                                        Setiap "Kotak" terdiri dari 4 lapisan seperti bawang:
                                        1. <strong>Content:</strong> Isi sebenarnya (teks/gambar).
                                        2. <strong>Padding:</strong> Area transparan di sekitar konten (Bantalan dalam).
                                        3. <strong>Border:</strong> Garis yang mengelilingi padding (Bingkai).
                                        4. <strong>Margin:</strong> Area transparan di luar border (Jarak dengan elemen lain).
                                    </p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-white flex items-center gap-2"><span class="w-6 h-6 rounded bg-purple-500 flex items-center justify-center text-[10px] text-white">B</span> Controlling Spacing</h3>
                                <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed space-y-4">
                                    <p>
                                        Kebingungan terbesar pemula adalah membedakan <strong>Margin</strong> dan <strong>Padding</strong>.
                                    </p>
                                    <p>
                                        Aturan sederhananya: Gunakan <strong>Padding</strong> jika Anda ingin memberi ruang <em>di dalam</em> kotak (misalnya, agar teks tidak menempel ke garis tepi tombol). Gunakan <strong>Margin</strong> jika Anda ingin memberi jarak <em>di antara</em> dua kotak (misalnya, memberi jarak antar paragraf).
                                    </p>
                                </div>
                            </div>

                            <div class="bg-[#0b0f19] border border-white/10 rounded-2xl p-8 relative shadow-2xl overflow-hidden flex flex-col items-center">
                                <div class="absolute top-4 left-4 bg-black/80 backdrop-blur p-3 rounded-lg border border-white/10 font-mono text-xs z-20 shadow-lg">
                                    <div class="text-gray-400 mb-1">.the-box {</div>
                                    <div class="pl-4 text-orange-400">margin: <span id="val-mar">20</span>px;</div>
                                    <div class="pl-4 text-yellow-400">border: <span id="val-bor">5</span>px solid;</div>
                                    <div class="pl-4 text-green-400">padding: <span id="val-pad">20</span>px;</div>
                                    <div class="text-gray-400 mt-1">}</div>
                                </div>
                                <div id="box-margin" class="relative bg-orange-500/20 border-2 border-dashed border-orange-500 transition-all duration-300 p-[20px] mt-12">
                                    <span class="absolute -top-6 left-0 text-[10px] text-orange-500 font-bold uppercase bg-[#0b0f19] px-1">Margin (Luar)</span>
                                    <div id="box-border" class="relative bg-transparent border-[5px] border-yellow-400 transition-all duration-300">
                                        <span class="absolute -bottom-6 right-0 text-[10px] text-yellow-400 font-bold uppercase bg-[#0b0f19] px-1">Border</span>
                                        <div id="box-padding" class="relative bg-green-500/20 p-[20px] transition-all duration-300">
                                            <span class="absolute -top-6 right-0 text-[10px] text-green-500 font-bold uppercase bg-[#0b0f19] px-1">Padding (Dalam)</span>
                                            <div class="w-40 h-24 bg-indigo-600 flex items-center justify-center text-white font-bold text-xs shadow-xl relative z-10 border border-white/20">CONTENT</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="grid grid-cols-3 gap-6 mt-16 w-full max-w-xl z-10 bg-[#1e1e1e] p-6 rounded-xl border border-white/10">
                                    <div class="text-center"><label class="block text-[10px] text-orange-400 font-bold mb-2 uppercase">Margin</label><input type="range" min="0" max="60" value="20" oninput="updateBoxModel('mar', this.value)" class="w-full accent-orange-500 h-1 bg-white/10 rounded"></div>
                                    <div class="text-center"><label class="block text-[10px] text-yellow-400 font-bold mb-2 uppercase">Border</label><input type="range" min="0" max="20" value="5" oninput="updateBoxModel('bor', this.value)" class="w-full accent-yellow-500 h-1 bg-white/10 rounded"></div>
                                    <div class="text-center"><label class="block text-[10px] text-green-400 font-bold mb-2 uppercase">Padding</label><input type="range" min="0" max="60" value="20" oninput="updateBoxModel('pad', this.value)" class="w-full accent-green-500 h-1 bg-white/10 rounded"></div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="section-6" class="lesson-section scroll-mt-32 pt-10 border-t border-white/10" data-lesson-id="6" data-type="activity">
                        <div class="relative rounded-[2rem] bg-[#0b0f19] border border-white/10 p-8 overflow-hidden shadow-2xl group hover:border-indigo-500/30 transition-all duration-500">
                            
                            <div class="absolute -top-24 -right-24 w-64 h-64 bg-indigo-600/20 blur-[100px] rounded-full pointer-events-none"></div>
                            <div class="flex items-center gap-4 mb-6 relative z-10">
                                <div class="p-3 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl text-white shadow-lg shadow-indigo-500/30"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg></div>
                                <div><h2 class="text-2xl font-bold text-white">Final Mission: Product Card</h2><p class="text-indigo-300 text-sm">Gabungkan HTML Semantik, Box Model, dan Styling untuk menyelesaikan misi.</p></div>
                            </div>

                            <div class="grid lg:grid-cols-2 gap-6 max-w-6xl mx-auto">
                                <div class="bg-[#1e1e1e] rounded-xl border border-white/10 flex flex-col overflow-hidden h-full relative">
                                    <div id="lockOverlay" class="hidden absolute inset-0 bg-[#050912]/95 backdrop-blur-md z-50 flex flex-col items-center justify-center text-center p-6 border-4 border-green-500/20 m-1 rounded-lg">
                                        <div class="w-20 h-20 bg-green-500/10 rounded-full flex items-center justify-center mb-4 border border-green-500/50 shadow-lg animate-bounce"><svg class="w-10 h-10 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" d="M5 13l4 4L19 7" /></svg></div>
                                        <h3 class="text-2xl font-black text-white mb-2">MISSION COMPLETED!</h3>
                                        {{-- <p class="text-sm text-white/50 mb-6">Akses ke Bab 2 telah dibuka.</p> --}}
                                        <p class="text-base text-white/60 mb-8 max-w-xs">Subbab 1.1 Tuntas. Lanjut ke Konsep Dasar Tailwind CSS</p>

                                        <button disabled class="px-8 py-3 rounded-full bg-white/5 border border-white/10 text-white/30 text-xs font-bold cursor-not-allowed uppercase">Review Mode</button>
                                    </div>

                                    <div class="bg-[#2d2d2d] px-4 py-3 border-b border-white/5 flex justify-between items-center">
                                        <span class="text-xs text-gray-400 font-mono flex items-center gap-2"><div class="w-2 h-2 rounded-full bg-yellow-500"></div> challenge.css</span>
                                        <span class="text-[10px] text-indigo-400 font-bold tracking-wider">● Interactive</span>
                                    </div>
                                    <div class="p-6 space-y-8 flex-1 overflow-y-auto custom-scrollbar">
                                        <form id="activityForm">
                                            <div class="space-y-3">
                                                <div class="flex justify-between"><label class="text-[10px] uppercase text-white/40 font-bold tracking-widest">1. Wrapper Tag</label><span class="text-[10px] text-indigo-400 font-mono bg-indigo-500/10 px-2 py-0.5 rounded">Target: &lt;article&gt;</span></div>
                                                <div class="grid grid-cols-2 gap-2">
                                                    <button type="button" onclick="setAct('tag', 'div', this)" class="opt-btn-tag px-3 py-3 rounded-lg bg-white/5 text-gray-400 border border-white/5 text-xs text-left font-mono transition hover:bg-white/10">&lt;div&gt;</button>
                                                    <button type="button" onclick="setAct('tag', 'article', this)" class="opt-btn-tag px-3 py-3 rounded-lg bg-white/5 text-gray-400 border border-white/5 text-xs text-left font-mono transition hover:bg-white/10">&lt;article&gt;</button>
                                                </div>
                                            </div>
                                            <div class="space-y-3">
                                                <div class="flex justify-between"><label class="text-[10px] uppercase text-white/40 font-bold tracking-widest">2. Internal Spacing</label><span class="text-[10px] text-indigo-400 font-mono bg-indigo-500/10 px-2 py-0.5 rounded">Target: padding: 24px</span></div>
                                                <div class="grid grid-cols-2 gap-2">
                                                    <button type="button" onclick="setAct('pad', '0px', this)" class="opt-btn-pad px-3 py-3 rounded-lg bg-white/5 text-gray-400 border border-white/5 text-xs text-left font-mono transition hover:bg-white/10">0px</button>
                                                    <button type="button" onclick="setAct('pad', '24px', this)" class="opt-btn-pad px-3 py-3 rounded-lg bg-white/5 text-gray-400 border border-white/5 text-xs text-left font-mono transition hover:bg-white/10">24px</button>
                                                </div>
                                            </div>
                                            <div class="space-y-3">
                                                <div class="flex justify-between"><label class="text-[10px] uppercase text-white/40 font-bold tracking-widest">3. Card Style</label><span class="text-[10px] text-indigo-400 font-mono bg-indigo-500/10 px-2 py-0.5 rounded">Target: Dark Theme</span></div>
                                                <div class="grid grid-cols-2 gap-2">
                                                    <button type="button" onclick="setAct('style', 'plain', this)" class="opt-btn-style px-3 py-3 rounded-lg bg-white/5 text-gray-400 border border-white/5 text-xs text-left font-mono transition hover:bg-white/10">Plain Text</button>
                                                    <button type="button" onclick="setAct('style', 'card', this)" class="opt-btn-style px-3 py-3 rounded-lg bg-white/5 text-gray-400 border border-white/5 text-xs text-left font-mono transition hover:bg-white/10">Styled Card</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="p-4 bg-black/40 border-t border-white/5 flex justify-between items-center">
                                        <span class="text-[10px] text-white/30 font-mono" id="status-text">Menunggu Input...</span>
                                        <button onclick="checkSolution()" id="submitBtn" class="px-6 py-2.5 rounded-lg bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold text-xs shadow-lg shadow-indigo-500/20 hover:scale-105 transition-all cursor-pointer">Validasi & Kunci</button>
                                    </div>
                                </div>

                                <div class="bg-[#1e1e1e] rounded-xl border border-white/10 flex-1 flex flex-col relative overflow-hidden min-h-[400px]">
                                    <div class="bg-[#2d2d2d] px-4 py-2 border-b border-white/5 flex items-center gap-2">
                                        <div class="flex gap-1.5"><div class="w-2.5 h-2.5 rounded-full bg-red-500"></div><div class="w-2.5 h-2.5 rounded-full bg-yellow-500"></div><div class="w-2.5 h-2.5 rounded-full bg-green-500"></div></div>
                                        <span class="text-[10px] text-gray-500 font-mono ml-2">localhost:8080</span>
                                    </div>
                                    <div class="flex-1 bg-gray-900 p-8 relative overflow-auto flex items-center justify-center">
                                        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-5 pointer-events-none"></div>
                                        <div id="final-card" class="w-72 bg-transparent text-left transition-all duration-500">
                                            <div class="h-40 bg-indigo-900/30 rounded-t-xl relative overflow-hidden group mb-4 hidden" id="final-img">
                                                <div class="absolute inset-0 bg-indigo-600/10"></div>
                                                <div class="absolute inset-0 flex items-center justify-center text-indigo-400 font-bold opacity-30">PRODUCT IMG</div>
                                            </div>
                                            <div id="final-content" class="text-white">
                                                <h3 class="font-bold text-lg mb-1">Mechanical Keyboard</h3>
                                                <p class="text-sm opacity-60 mb-4">High performance clicky switches.</p>
                                                <button class="w-full py-2 rounded bg-indigo-600 font-bold text-xs">BUY NOW</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                </article>

                <div class="mt-32 pt-10 border-t border-white/10 flex justify-between items-center">
                    <a href="{{ route('courses.grid') }}" class="group flex items-center gap-3 text-slate-400 hover:text-white transition">
                        <div class="w-10 h-10 rounded-full border border-white/10 flex items-center justify-center group-hover:border-white/30 transition">←</div>
                        <div class="text-left"><div class="text-[10px] uppercase tracking-widest opacity-50">Sebelumnya</div><div class="font-bold text-sm">Dashboard</div></div>
                    </a>
                    
                    <div id="nextChapterBtn" class="group flex items-center gap-3 text-right text-slate-500 cursor-not-allowed opacity-50 pointer-events-none transition-all duration-500">
                        <div class="text-right">
                            <div id="nextLabel" class="text-[10px] uppercase tracking-widest opacity-50">Terkunci</div>
                            <div class="font-bold text-sm">Konsep Dasar Tailwind</div>
                        </div>
                        <div id="nextIcon" class="w-10 h-10 rounded-full border border-white/5 flex items-center justify-center bg-white/5">🔒</div>
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>

<style>
    .nav-link.active { color: #22d3ee; position: relative; }
    nav-link.active { color: #22d3ee; position: relative; }
    .nav-link.active::after { content: ''; position: absolute; left: 0; bottom: -6px; width: 100%; height: 2px; background: linear-gradient(to right,#22d3ee,#3b82f6); box-shadow: 0 0 12px rgba(34,211,238,.8); border-radius: 2px; }
    .custom-scrollbar::-webkit-scrollbar { width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
    
    @keyframes shake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-5px); } 75% { transform: translateX(5px); } }
    .shake { animation: shake 0.4s ease-in-out; }

    #animated-bg{ background: radial-gradient(600px circle at 20% 20%, rgba(168,85,247,.15), transparent 40%), radial-gradient(700px circle at 80% 30%, rgba(217,70,239,.15), transparent 40%), radial-gradient(800px circle at 50% 80%, rgba(34,211,238,.15), transparent 40%); animation:bgMove 20s ease-in-out infinite alternate; }
    @keyframes bgMove{to{transform:scale(1.15)}}
    
    /* SCROLLSPY ACTIVE STATES */
    .nav-item { display: flex; width: 100%; text-align: left; align-items: center; gap: 12px; padding: 10px 14px; font-size: 0.85rem; color: rgba(255,255,255,0.5); border-radius: 8px; transition: all 0.2s; position: relative; }
    .nav-item:hover { color: #fff; background: rgba(255,255,255,0.03); }
    .nav-item.active { color: #c084fc; background: rgba(192,132,252,0.05); font-weight: 600; }
    .dot { width: 6px; height: 6px; border-radius: 50%; background: #334155; transition: all 0.3s; }
    .nav-item.active .dot { background: #c084fc; box-shadow: 0 0 8px #c084fc; transform: scale(1.2); }
    
    .sb-group.open .accordion-content { max-height: 1000px; opacity: 1; }
    .sb-group:not(.open) .accordion-content { max-height: 0; opacity: 0; overflow: hidden; }
    .sb-group.open svg { transform: rotate(180deg); }
    
    /* SIDEBAR COMPATIBILITY */
    .sb-group.open .accordion-content { max-height: 1000px; opacity: 1; }
    .sb-group:not(.open) .accordion-content { max-height: 0; opacity: 0; overflow: hidden; }
    .sb-group.open svg { transform: rotate(180deg); }
    .nav-item { display: flex; width: 100%; text-align: left; align-items: center; gap: 12px; padding: 10px 14px; font-size: 0.85rem; color: rgba(255,255,255,0.5); border-radius: 8px; transition: all 0.2s; position: relative; }
    .nav-item:hover { color: #fff; background: rgba(255,255,255,0.03); }
    .nav-item.active { color: #22d3ee; background: rgba(34,211,238,0.05); font-weight: 600; }
    .dot { width: 6px; height: 6px; border-radius: 50%; background: #334155; transition: all 0.3s; }
    .nav-item.active .dot { background: #22d3ee; box-shadow: 0 0 8px #22d3ee; transform: scale(1.2); }
</style>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    /* --- CONFIGURATION --- */
    window.LESSON_IDS = [1, 2, 3, 4, 5, 6]; 
    window.COMPLETED_IDS = {!! json_encode($completedLessonIds ?? []) !!};
    let completedSet = new Set(window.COMPLETED_IDS);
    let activityCompleted = {!! ($activityCompleted ?? false) ? 'true' : 'false' !!};
    const ACTIVITY_ID = 1; 
    const ACTIVITY_LESSON_ID = 6; 

    document.addEventListener('DOMContentLoaded', () => {
        initScrollSpy();
        initLessonObserver();
        initVisualEffects();
        initSidebarScroll();
        updateProgressUI();
        
        // LOCK LOGIC CHECK ON LOAD
        if (activityCompleted) {
            lockActivityUI();
            unlockNextChapter();
        }

        // Initialize Simulators
        updateAttrSim();
        updateCss('bg', 'indigo');
        updateBrand();
        updateBoxModel('mar', 20);
    });

    /* --- SIMULATORS --- */
    let domState = { nav: false, h1: false, p: false };
    function toggleDom(el) {
        domState[el] = !domState[el];
        const code = document.getElementById('dom-code-area');
        const view = document.getElementById('dom-preview-area');
        let hC = '', hV = '';
        if(domState.nav) { hC += `<div class="text-indigo-400">&lt;nav&gt;...&lt;/nav&gt;</div>`; hV += `<div class="w-full h-8 bg-indigo-600 rounded mb-4 shadow"></div>`; }
        if(domState.h1) { hC += `<div class="text-purple-400">&lt;h1&gt;Judul&lt;/h1&gt;</div>`; hV += `<h1 class="text-2xl font-bold text-black mb-2">Hello World</h1>`; }
        if(domState.p) { hC += `<div class="text-pink-400">&lt;p&gt;Konten...&lt;/p&gt;</div>`; hV += `<p class="text-sm text-gray-500">Ini adalah konten website.</p>`; }
        code.innerHTML = hC || '<span class="text-gray-600 italic">// Kosong...</span>';
        view.innerHTML = hV || '<div class="text-center mt-10 text-gray-300 italic">Halaman Kosong</div>';
    }

    function updateAttrSim() {
        const src = document.getElementById('inp-src').value;
        const cls = document.getElementById('inp-class').value;
        document.getElementById('code-src').innerText = src.includes('coding') ? 'coding.jpg' : 'cat.jpg';
        document.getElementById('code-class').innerText = cls;
        const img = document.getElementById('view-attr');
        img.src = src; img.className = `w-48 h-48 object-cover border-2 border-white/10 transition-all duration-500 shadow-2xl ${cls}`;
    }

    function updateCss(prop, val) {
        const btn = document.getElementById('css-btn-target');
        if(prop === 'bg') {
            btn.classList.remove('bg-indigo-600', 'bg-rose-600', 'bg-emerald-600', 'hover:bg-indigo-500', 'hover:bg-rose-500', 'hover:bg-emerald-500');
            btn.classList.add(`bg-${val}-600`);
        }
        if(prop === 'rad') {
            btn.style.borderRadius = val + 'px';
            document.getElementById('css-val-rad').innerText = val + 'px';
        }
        if(prop === 'pad') btn.style.padding = val;
    }

    function updateBrand() {
        const color = document.getElementById('inp-brand-color').value;
        const size = document.getElementById('inp-brand-size').value;
        const h1 = document.getElementById('brand-preview');
        h1.style.color = color; h1.style.fontSize = size + 'rem';
        document.getElementById('css-brand-color').innerText = color;
        document.getElementById('css-brand-size').innerText = size + 'rem';
    }

    function updateBoxModel(prop, val) {
        if(prop === 'pad') document.getElementById('box-padding').style.padding = val + 'px';
        if(prop === 'bor') document.getElementById('box-border').style.borderWidth = val + 'px';
        if(prop === 'mar') document.getElementById('box-margin').style.padding = val + 'px';
        document.getElementById(`val-${prop}`).innerText = val;
    }

    /* --- ACTIVITY LOGIC --- */
    let actConfig = { tag: '', pad: '', style: '' };

    function setAct(cat, val, btn) {
        if(activityCompleted) return;
        actConfig[cat] = val;
        document.querySelectorAll(`.opt-btn-${cat}`).forEach(b => {
            b.classList.remove('bg-indigo-600', 'text-white', 'border-indigo-500', 'shadow-lg');
            b.classList.add('bg-white/5', 'text-gray-400', 'border-white/5');
        });
        btn.classList.remove('bg-white/5', 'text-gray-400', 'border-white/5');
        btn.classList.add('bg-indigo-600', 'text-white', 'border-indigo-500', 'shadow-lg');
        renderFinalCard();
    }

    function renderFinalCard() {
        const card = document.getElementById('final-card');
        const img = document.getElementById('final-img');
        const content = document.getElementById('final-content');
        
        if(actConfig.style === 'card') {
            card.className = "w-72 bg-[#0f172a] border border-white/10 text-left shadow-2xl transition-all duration-500 rounded-xl overflow-hidden";
            img.classList.remove('hidden');
        } else {
            card.className = "w-72 text-left text-gray-400";
            img.classList.add('hidden');
        }
        content.className = (actConfig.pad === '24px') ? "p-6 text-white" : "p-0 text-white";
    }

    async function checkSolution() {
        if(activityCompleted) return;
        const btn = document.getElementById('submitBtn');
        const status = document.getElementById('status-text');
        btn.innerHTML = '<span class="animate-pulse">Memeriksa...</span>'; btn.disabled = true;
        
        await new Promise(r => setTimeout(r, 1000));

        if(actConfig.tag === 'article' && actConfig.pad === '24px' && actConfig.style === 'card') {
            status.innerText = "BENAR!"; status.className = "text-[10px] text-green-400 font-mono font-bold";
            await saveActivityData();
        } else {
            status.innerText = "Hint: Gunakan Article, Padding 24px, Dark Card."; 
            status.className = "text-[10px] text-red-400 font-mono font-bold";
            btn.innerText = "Coba Lagi"; btn.disabled = false; btn.classList.add('shake'); setTimeout(() => btn.classList.remove('shake'), 500);
        }
    }

    /* --- SYSTEM & UNLOCK LOGIC --- */
    async function saveActivityData() {
        try {
            await fetch('/activity/complete', { 
                method: 'POST', 
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'Content-Type': 'application/json' }, 
                body: JSON.stringify({ activity_id: ACTIVITY_ID, score: 100 }) 
            });
            await saveLessonToDB(ACTIVITY_LESSON_ID); 
            completedSet.add(ACTIVITY_LESSON_ID);
            
            activityCompleted = true;
            updateProgressUI();
            lockActivityUI();
            unlockNextChapter(); // UNLOCKS THE BUTTON
        } catch(e) { console.error(e); }
    }

    function lockActivityUI() {
        document.getElementById('lockOverlay').classList.remove('hidden');
        const btn = document.getElementById('submitBtn'); btn.innerText = "Terkunci"; btn.disabled = true;
        actConfig = { tag: 'article', pad: '24px', style: 'card' }; renderFinalCard();
        document.querySelectorAll('#activityForm button').forEach(b => b.disabled = true);
    }

    function unlockNextChapter() {
        const btn = document.getElementById('nextChapterBtn');
        if(btn) {
            btn.classList.remove('cursor-not-allowed', 'opacity-50', 'pointer-events-none', 'text-slate-500');
            btn.classList.add('text-indigo-400', 'hover:text-indigo-300', 'cursor-pointer');
            
            document.getElementById('nextLabel').innerText = "Selanjutnya";
            document.getElementById('nextLabel').classList.remove('opacity-50');
            document.getElementById('nextIcon').innerHTML = "→";
            document.getElementById('nextIcon').classList.add('bg-indigo-500/20', 'border-indigo-500/50');
            
            btn.onclick = () => window.location.href = "{{ route('courses.tailwindcss') }}"; 
        }
    }

    function updateProgressUI() {
        const total = window.LESSON_IDS.length; const done = window.LESSON_IDS.filter(id => completedSet.has(id)).length; const percent = Math.round((done / total) * 100);
        document.getElementById('topProgressBar').style.width = percent + '%'; document.getElementById('progressLabelTop').innerText = percent + '%';
        if(percent === 100) unlockNextChapter();
    }

    function initLessonObserver() {
        const obs = new IntersectionObserver(async entries => {
            for (const entry of entries) {
                if (entry.isIntersecting) {
                    const id = Number(entry.target.dataset.lessonId);
                    if (id && entry.target.dataset.type !== 'activity' && !completedSet.has(id)) {
                        try { await saveLessonToDB(id); completedSet.add(id); updateProgressUI(); } catch(e) {}
                    }
                }
            }
        }, { threshold: 0.5, root: document.getElementById('mainScroll') });
        document.querySelectorAll('.lesson-section').forEach(s => obs.observe(s));
    }

    async function saveLessonToDB(id) { await fetch('/lesson/complete', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: new URLSearchParams({ lesson_id: id }) }); }

    function initScrollSpy() {
        const mainScroll = document.getElementById('mainScroll'); const sections = document.querySelectorAll('.lesson-section'); const navLinks = document.querySelectorAll('.sidebar-nav-link');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const id = '#' + entry.target.id;
                    navLinks.forEach(link => {
                        const dot = link.querySelector('.dot-indicator'); if(!dot) return;
                        link.classList.remove('bg-white/5'); dot.classList.remove('bg-indigo-500', 'shadow-[0_0_8px_#6366f1]', 'scale-125'); dot.classList.add('bg-slate-600');
                        if (link.dataset.target === id) { link.classList.add('bg-white/5'); dot.classList.remove('bg-slate-600'); dot.classList.add('bg-indigo-500', 'shadow-[0_0_8px_#6366f1]', 'scale-125'); }
                    });
                }
            });
        }, { root: mainScroll, rootMargin: '-20% 0px -60% 0px', threshold: 0 });
        sections.forEach(section => observer.observe(section));
    }

    function initSidebarScroll(){const m=document.getElementById('mainScroll');const l=document.querySelectorAll('.accordion-content .nav-item');m.addEventListener('scroll',()=>{let c='';document.querySelectorAll('.lesson-section').forEach(s=>{if(m.scrollTop>=s.offsetTop-250)c='#'+s.id;});l.forEach(k=>{k.classList.remove('active');if(k.getAttribute('data-target')===c)k.classList.add('active')})});l.forEach(k=>k.addEventListener('click',(e)=>{const t=document.querySelector(k.getAttribute('data-target'));if(t)m.scrollTo({top:t.offsetTop-120,behavior:'smooth'})}));}
    function initVisualEffects(){const c=document.getElementById('stars'),x=c.getContext('2d');function r(){c.width=innerWidth;c.height=innerHeight}r();window.onresize=r;let s=[];for(let i=0;i<100;i++)s.push({x:Math.random()*c.width,y:Math.random()*c.height,r:Math.random()*1.2,v:Math.random()*0.2+.1});(function a(){x.clearRect(0,0,c.width,c.height);x.fillStyle='rgba(255,255,255,.3)';s.forEach(t=>{x.beginPath();x.arc(t.x,t.y,t.r,0,6.28);x.fill();t.y+=t.v;if(t.y>c.height)t.y=0});requestAnimationFrame(a)})();$(window).on('mousemove',e=>{$('#cursor-glow').css({left:e.clientX,top:e.clientY})});}
</script>
@endsection