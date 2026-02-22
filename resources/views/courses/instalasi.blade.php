@extends('layouts.landing')
@section('title', 'Bab 1.6 · Instalasi & Konfigurasi Masterclass')

@section('content')
<div id="courseRoot" class="relative h-screen bg-[#020617] text-white font-sans overflow-hidden flex flex-col selection:bg-cyan-500/30 pt-20">

    {{-- BACKGROUND SYSTEM (Technical Theme) --}}
    <div class="fixed inset-0 -z-50 pointer-events-none">
        <div id="animated-bg" class="absolute inset-0 opacity-50"></div>
        <div class="absolute top-[-20%] left-[-10%] w-[800px] h-[800px] bg-cyan-900/10 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute bottom-[-20%] right-[-10%] w-[600px] h-[600px] bg-blue-900/10 rounded-full blur-[100px]"></div>
        <canvas id="stars" class="absolute inset-0 pointer-events-none"></canvas>
        <div id="noise-overlay" class="absolute inset-0 z-10 opacity-[0.04]"></div>
        <div id="cursor-glow"></div>
    </div>

    @include('layouts.partials.navbar')

    <div class="flex flex-1 overflow-hidden relative z-20">

        @include('layouts.partials.course-sidebar')

        <main id="mainScroll" class="flex-1 h-full overflow-y-auto scroll-smooth relative bg-transparent custom-scrollbar scroll-padding-top-24">
            
            {{-- STICKY HEADER --}}
            <div id="stickyHeader" class="sticky top-0 z-30 w-full bg-[#020617]/80 backdrop-blur-2xl border-b border-white/5 px-8 py-4 flex items-center justify-between transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-cyan-500/20 to-transparent border border-cyan-500/20 flex items-center justify-center font-bold text-xs text-cyan-400">1.6</div>
                    <div>
                        <h1 class="text-sm font-bold text-white">Instalasi & Konfigurasi</h1>
                        <p class="text-[10px] text-white/50">Node.js, CLI, JIT & Theming</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="hidden sm:block w-32 h-1.5 bg-white/10 rounded-full overflow-hidden">
                        <div id="topProgressBar" class="h-full bg-gradient-to-r from-cyan-500 to-blue-500 w-0 transition-all duration-500 shadow-[0_0_10px_#0ea5e9]"></div>
                    </div>
                    <span id="progressLabelTop" class="text-cyan-400 font-bold text-xs">0%</span>
                </div>
            </div>

            {{-- CONTENT WRAPPER --}}
            <div class="p-6 lg:p-16 max-w-5xl mx-auto pb-40">
                
                {{-- LEARNING OBJECTIVES --}}
                <div class="mb-24 animate-fade-in-up">
                    <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        Tujuan Pembelajaran
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-[#1e1e1e] border border-white/5 p-5 rounded-xl flex items-start gap-4 hover:border-cyan-500/30 transition group h-full">
                            <div class="w-8 h-8 rounded bg-cyan-500/10 text-cyan-400 flex items-center justify-center shrink-0 font-bold text-xs">1</div>
                            <div><h4 class="text-sm font-bold text-white mb-1">Runtime Environment</h4><p class="text-[11px] text-white/50 leading-relaxed">Memahami peran Node.js sebagai "pabrik" pembuatan CSS.</p></div>
                        </div>
                        <div class="bg-[#1e1e1e] border border-white/5 p-5 rounded-xl flex items-start gap-4 hover:border-blue-500/30 transition group h-full">
                            <div class="w-8 h-8 rounded bg-blue-500/10 text-blue-400 flex items-center justify-center shrink-0 font-bold text-xs">2</div>
                            <div><h4 class="text-sm font-bold text-white mb-1">Command Line</h4><p class="text-[11px] text-white/50 leading-relaxed">Memahami perintah CLI untuk inisialisasi proyek.</p></div>
                        </div>
                        <div class="bg-[#1e1e1e] border border-white/5 p-5 rounded-xl flex items-start gap-4 hover:border-indigo-500/30 transition group h-full">
                            <div class="w-8 h-8 rounded bg-indigo-500/10 text-indigo-400 flex items-center justify-center shrink-0 font-bold text-xs">3</div>
                            <div><h4 class="text-sm font-bold text-white mb-1">JIT Compilation</h4><p class="text-[11px] text-white/50 leading-relaxed">Memahami mekanisme pemindaian konten dan pembuatan CSS on-demand.</p></div>
                        </div>
                        <div class="bg-[#1e1e1e] border border-white/5 p-5 rounded-xl flex items-start gap-4 hover:border-violet-500/30 transition group h-full">
                            <div class="w-8 h-8 rounded bg-violet-500/10 text-violet-400 flex items-center justify-center shrink-0 font-bold text-xs">4</div>
                            <div><h4 class="text-sm font-bold text-white mb-1">Theme Architecture</h4><p class="text-[11px] text-white/50 leading-relaxed">Memahami strategi Extend vs Override pada tema.</p></div>
                        </div>
                    </div>
                </div>

                <article class="space-y-40">
                    
                    {{-- SECTION 24: PRASYARAT SISTEM (NODE.JS) --}}
                    <section id="section-24" class="lesson-section scroll-mt-32" data-lesson-id="24">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-cyan-500 pl-6">
                                <span class="text-cyan-400 font-mono text-xs uppercase tracking-widest">Lesson 1.6.1</span>
                                <h2 class="text-4xl lg:text-5xl font-black text-white leading-[1.1]">
                                    Prasyarat Sistem <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-500">(Node.js Runtime)</span>
                                </h2>
                            </div>
                            
                            <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed space-y-6">
                                <p>
                                    Salah satu pertanyaan paling mendasar yang sering diajukan oleh pengembang web pemula adalah: <em>"Mengapa saya memerlukan Node.js hanya untuk menulis CSS?"</em>. Jawabannya terletak pada evolusi cara kita membangun antarmuka web modern. Tailwind CSS bukanlah sekadar file stylesheet statis yang Anda unduh dan tautkan ke HTML Anda seperti pada era awal web development. Tailwind sejatinya adalah sebuah <strong>perangkat lunak (software)</strong> canggih yang berjalan di komputer Anda untuk "memproduksi" kode CSS.
                                </p>
                                <p>
                                    Dalam buku <em>"Modern CSS with Tailwind"</em>, Node.js digambarkan sebagai <strong>"pabrik"</strong> atau lingkungan eksekusi (runtime) tempat perangkat lunak Tailwind ini beroperasi. Tanpa Node.js, komputer Anda tidak akan mengerti cara menjalankan instruksi-instruksi kompleks yang diperlukan Tailwind untuk memindai file proyek Anda, menganalisis kelas-kelas utilitas yang Anda gunakan, dan kemudian menyusun file CSS akhir yang sangat ringan dan teroptimasi. Oleh karena itu, Node.js adalah fondasi mutlak yang tidak bisa ditawar dalam ekosistem pengembangan modern ini.
                                </p>
                                <p>
                                    Bersamaan dengan Node.js, Anda juga akan secara otomatis mendapatkan NPM (Node Package Manager). Jika Node.js adalah pabriknya, maka NPM adalah <strong>"gudang logistik"</strong> raksasa yang menyediakan semua bahan baku yang Anda butuhkan. Melalui NPM, kita dapat mengunduh paket Tailwind CSS, plugin tambahan, serta alat bantu lainnya seperti PostCSS dan Autoprefixer dengan satu baris perintah, memastikan semua alat kerja kita selalu mutakhir dan terkelola dengan baik tanpa perlu mengunduh file <code>.zip</code> secara manual.
                                </p>
                                
                                <div class="grid md:grid-cols-2 gap-6 mt-6">
                                    <div class="bg-[#151921] p-6 rounded-xl border border-white/5 relative overflow-hidden group hover:border-green-500/30 transition">
                                        <div class="absolute top-0 right-0 p-3 opacity-20 group-hover:opacity-50 transition"><svg class="w-10 h-10 text-green-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12.003 1.997C6.48 1.997 1.997 6.48 1.997 12.003c0 5.523 4.483 10.006 10.006 10.006 5.523 0 10.006-4.483 10.006-10.006 0-5.523-4.483-10.006-10.006-10.006zM9.486 16.326l-3.32-3.32 1.414-1.414 1.906 1.906 4.906-4.906 1.414 1.414-6.32 6.32z"/></svg></div>
                                        <h4 class="text-white font-bold mb-2 text-xl">Node.js (LTS Version)</h4>
                                        <p class="text-sm text-white/50 leading-relaxed">Mesin utama. Disarankan menggunakan versi LTS (Long Term Support) terbaru (v18 atau v20) untuk stabilitas maksimal.</p>
                                    </div>
                                    <div class="bg-[#151921] p-6 rounded-xl border border-white/5 relative overflow-hidden group hover:border-red-500/30 transition">
                                        <div class="absolute top-0 right-0 p-3 opacity-20 group-hover:opacity-50 transition"><svg class="w-10 h-10 text-red-500" fill="currentColor" viewBox="0 0 24 24"><path d="M0 7.5v9l7 4v-9l-7-4zm7.5 4l7.5-4 7.5 4-7.5 4-7.5-4zM22.5 7.5v9l-7.5 4v-9l7.5-4z"/></svg></div>
                                        <h4 class="text-white font-bold mb-2 text-xl">NPM (Package Manager)</h4>
                                        <p class="text-sm text-white/50 leading-relaxed">Alat manajemen dependensi yang terinstal otomatis. NPM memungkinkan kita menginstal Tailwind sebagai <em>development dependency</em>.</p>
                                    </div>
                                </div>
                            </div>

                            {{-- SIMULATOR 1: NODE CHECKER --}}
                            <div class="bg-[#0f141e] border border-white/10 rounded-2xl p-8 relative overflow-hidden group hover:border-cyan-500/30 transition-all mt-8">
                                <div class="absolute -right-10 -top-10 w-32 h-32 bg-cyan-500/10 rounded-full blur-3xl"></div>
                                <h4 class="text-sm font-bold text-white mb-4 flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-cyan-500 animate-pulse"></span>
                                    Simulasi Cek Lingkungan Sistem
                                </h4>
                                <div class="flex flex-col md:flex-row gap-6 items-center">
                                    <div class="flex-1 w-full space-y-4">
                                        <div class="bg-black/50 p-4 rounded-lg font-mono text-xs text-gray-400 border border-white/5">
                                            <div class="flex justify-between border-b border-white/5 pb-2 mb-2">
                                                <span>System Check</span>
                                                <span class="text-white/30">v1.0</span>
                                            </div>
                                            <div id="node-check-output" class="space-y-2 h-24 overflow-y-auto custom-scrollbar">
                                                <p class="text-white/30">> Menunggu perintah...</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="w-full md:w-auto flex flex-col gap-3">
                                        <button onclick="runNodeCheck('node -v')" class="px-6 py-3 bg-white/5 hover:bg-green-600/20 hover:text-green-400 hover:border-green-500/50 border border-white/10 rounded-lg text-sm font-mono transition text-left group">
                                            <span class="text-white/50 group-hover:text-green-400 mr-2">$</span> node -v
                                        </button>
                                        <button onclick="runNodeCheck('npm -v')" class="px-6 py-3 bg-white/5 hover:bg-red-600/20 hover:text-red-400 hover:border-red-500/50 border border-white/10 rounded-lg text-sm font-mono transition text-left group">
                                            <span class="text-white/50 group-hover:text-red-400 mr-2">$</span> npm -v
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- SECTION 25: SIMULASI INSTALASI CLI --}}
                    <section id="section-25" class="lesson-section scroll-mt-32" data-lesson-id="25">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-blue-500 pl-6">
                                <span class="text-blue-400 font-mono text-xs uppercase tracking-widest">Lesson 1.6.2</span>
                                <h2 class="text-4xl lg:text-5xl font-black text-white leading-[1.1]">
                                    Simulasi Instalasi <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-500">Tailwind CLI</span>
                                </h2>
                            </div>

                            <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed space-y-6">
                                <p>
                                    Meskipun ada banyak cara untuk menggunakan Tailwind (seperti melalui CDN atau PostCSS plugin), metode instalasi yang paling direkomendasikan dalam buku <em>"Ultimate Tailwind CSS Handbook"</em> untuk fleksibilitas maksimal adalah melalui <strong>Tailwind CLI (Command Line Interface)</strong>. Metode ini memberikan isolasi penuh dari framework backend apa pun yang Anda gunakan dan memberikan akses langsung ke fitur konfigurasi tingkat lanjut tanpa kerumitan setup build tool yang berlebihan seperti Webpack atau Gulp.
                                </p>
                                <p>
                                    Proses instalasi ini terdiri dari serangkaian langkah logis yang harus dijalankan secara berurutan:
                                </p>
                                <ol class="list-decimal pl-5 space-y-2 marker:text-blue-400">
                                    <li><strong>Inisialisasi Proyek (<code>npm init -y</code>)</strong>: Langkah pertama adalah membuat file <code>package.json</code>. Ini seperti "buku inventaris" yang mencatat barang apa saja (dependensi) yang digunakan dalam proyek kita.</li>
                                    <li><strong>Instalasi Paket (<code>npm install ...</code>)</strong>: Selanjutnya, kita memerintahkan NPM untuk mengunduh paket Tailwind CSS, PostCSS, dan Autoprefixer. Kita menginstalnya sebagai <code>devDependencies</code> (-D) karena alat ini hanya dibutuhkan saat pengembangan.</li>
                                    <li><strong>Inisialisasi Konfigurasi (<code>npx tailwindcss init</code>)</strong>: Terakhir, kita membuat file <code>tailwind.config.js</code>. Ini adalah file "settings" tempat Anda akan mengatur tema, warna, dan font nantinya.</li>
                                </ol>
                                <p>
                                    Untuk membantu Anda memahami dan mengingat alur kerja ini, mari kita lakukan simulasi instalasi langsung di terminal virtual di bawah ini.
                                </p>
                            </div>

                            {{-- INTERACTIVE TERMINAL SIMULATOR --}}
                            <div class="bg-[#0f141e] rounded-xl border border-white/10 shadow-2xl overflow-hidden font-mono text-sm relative group mt-6">
                                <div class="bg-[#1a1f2e] px-4 py-2 border-b border-white/5 flex items-center justify-between">
                                    <div class="flex gap-1.5">
                                        <div class="w-3 h-3 rounded-full bg-red-500/80"></div>
                                        <div class="w-3 h-3 rounded-full bg-yellow-500/80"></div>
                                        <div class="w-3 h-3 rounded-full bg-green-500/80"></div>
                                    </div>
                                    <div class="text-white/30 text-xs font-sans">Terminal - bash — 80x24</div>
                                </div>
                                <div class="p-6 h-72 overflow-y-auto custom-scrollbar bg-[#0f141e]" id="terminal-output">
                                    <div class="text-white/50 mb-4"># Langkah 1: Inisialisasi package.json untuk manajemen dependensi</div>
                                    <div class="flex items-center gap-2 terminal-line">
                                        <span class="text-green-400 font-bold">➜</span>
                                        <span class="text-blue-400 font-bold">~</span>
                                        <input type="text" id="cli-input" class="bg-transparent border-none outline-none text-white w-full focus:ring-0 font-mono" placeholder="Menunggu input..." autocomplete="off">
                                    </div>
                                </div>
                                
                                {{-- Hint Overlay --}}
                                <div id="terminal-hint" class="absolute bottom-0 left-0 w-full bg-[#1a1f2e]/90 backdrop-blur p-3 border-t border-white/10 flex justify-between items-center transition-transform duration-300 translate-y-0">
                                    <span class="text-xs text-white/70">Langkah 1: Inisialisasi package.json</span>
                                    <code class="text-xs bg-black/50 px-2 py-1 rounded text-yellow-400 cursor-pointer hover:bg-black/70" onclick="autoType('npm init -y')">npm init -y</code>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- SECTION 26: INTEGRASI & KOMPILASI --}}
                    <section id="section-26" class="lesson-section scroll-mt-32" data-lesson-id="26">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-indigo-500 pl-6">
                                <span class="text-indigo-400 font-mono text-xs uppercase tracking-widest">Lesson 1.6.3</span>
                                <h2 class="text-4xl lg:text-5xl font-black text-white leading-[1.1]">
                                    Integrasi & <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-violet-500">Kompilasi (JIT)</span>
                                </h2>
                            </div>
                            
                            <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed space-y-6">
                                <p>
                                    Salah satu inovasi terbesar yang dibawa oleh Tailwind CSS v3.0 ke atas adalah <strong>Just-in-Time (JIT) Engine</strong>. Dalam framework CSS tradisional, Anda biasanya mengunduh file CSS raksasa yang berisi ribuan kelas yang mungkin tidak akan pernah Anda gunakan (seperti <code>btn-primary</code>, <code>card-lg</code>, dll). Ini menyebabkan ukuran file membengkak dan performa website menurun secara signifikan.
                                </p>
                                <p>
                                    Tailwind bekerja dengan cara yang sama sekali berbeda. JIT Engine membalikkan proses tersebut. Alih-alih menyediakan semua kemungkinan kelas di awal, Tailwind bertindak sebagai <strong>"CCTV"</strong> atau pengamat yang cerdas. Ia memantau file HTML, JavaScript, atau Blade Anda secara <em>real-time</em>. Segera setelah Anda mengetikkan sebuah kelas utilitas (misalnya <code>text-sky-500</code>), JIT engine akan mendeteksinya, membuat kode CSS yang sesuai untuk kelas tersebut, dan menyuntikkannya ke dalam stylesheet Anda secara instan.
                                </p>
                                <p>
                                    Agar keajaiban ini bisa terjadi, konfigurasi array <code>content</code> di dalam file <code>tailwind.config.js</code> menjadi sangat krusial. Array ini memberitahu Tailwind di mana ia harus mencari nama-nama kelas yang Anda gunakan. Jika path file di konfigurasi ini salah, Tailwind tidak akan "melihat" kelas yang Anda tulis, dan akibatnya, tidak ada CSS yang dihasilkan sama sekali. Ini adalah penyebab nomor satu mengapa style Tailwind tidak muncul di browser.
                                </p>
                                
                                <div class="bg-indigo-900/20 border border-indigo-500/30 p-6 rounded-xl my-6 relative overflow-hidden">
                                    <div class="absolute -right-10 -top-10 w-32 h-32 bg-indigo-500/20 rounded-full blur-3xl"></div>
                                    <h4 class="text-indigo-300 font-bold text-sm mb-4 uppercase tracking-wider border-b border-indigo-500/20 pb-2">🚀 Mekanisme Kerja JIT Engine:</h4>
                                    <ol class="list-decimal pl-4 text-sm text-white/80 space-y-3 font-mono">
                                        <li>
                                            <span class="text-white font-bold">Input:</span> Anda menulis <code class="bg-black/30 px-1 rounded text-sky-400">class="text-sky-500"</code> di file HTML.
                                        </li>
                                        <li>
                                            <span class="text-white font-bold">Watch:</span> Proses watcher (npm run dev) mendeteksi perubahan file.
                                        </li>
                                        <li>
                                            <span class="text-white font-bold">Scan:</span> Engine Tailwind memindai konten file menggunakan Regex untuk menemukan kandidat kelas.
                                        </li>
                                        <li>
                                            <span class="text-white font-bold">Generate:</span> CSS dihasilkan secara on-demand: <code class="bg-black/30 px-1 rounded text-green-400">.text-sky-500 { color: #0ea5e9; }</code>.
                                        </li>
                                        <li>
                                            <span class="text-white font-bold">Output:</span> CSS baru disuntikkan ke browser tanpa memuat ulang halaman penuh.
                                        </li>
                                    </ol>
                                </div>
                            </div>

                            {{-- SIMULATOR 3: CONTENT SCANNER --}}
                            <div class="bg-[#1e1e1e] border border-white/10 rounded-xl p-8 relative shadow-lg group mt-6">
                                <div class="absolute -top-3 left-6 bg-indigo-600 text-white text-[10px] font-bold px-3 py-1 rounded shadow-md z-10 group-hover:bg-indigo-500 transition">Visualisasi JIT Scanner</div>
                                
                                <div class="grid md:grid-cols-2 gap-8">
                                    {{-- Kiri: Config --}}
                                    <div class="space-y-2">
                                        <label class="text-xs text-white/50 uppercase font-bold">1. Konfigurasi Path</label>
                                        <div class="bg-black/30 p-4 rounded border border-white/5 font-mono text-xs text-gray-400">
                                            module.exports = {<br>
                                            &nbsp;&nbsp;content: [<br>
                                            &nbsp;&nbsp;&nbsp;&nbsp;<span id="jit-path" class="text-green-400 transition-colors">"./resources/**/*.blade.php"</span><br>
                                            &nbsp;&nbsp;],<br>
                                            }
                                        </div>
                                        <div class="flex gap-2">
                                            <button onclick="setJitPath(true)" class="px-3 py-1 bg-green-500/10 text-green-400 text-[10px] rounded border border-green-500/20 hover:bg-green-500/20">Path Benar</button>
                                            <button onclick="setJitPath(false)" class="px-3 py-1 bg-red-500/10 text-red-400 text-[10px] rounded border border-red-500/20 hover:bg-red-500/20">Path Salah</button>
                                        </div>
                                    </div>

                                    {{-- Kanan: Output --}}
                                    <div class="space-y-2">
                                        <label class="text-xs text-white/50 uppercase font-bold">2. Output CSS</label>
                                        <div id="jit-output-box" class="h-32 bg-indigo-900/10 border border-indigo-500/30 rounded flex items-center justify-center text-center transition-all">
                                            <div id="jit-loading" class="hidden">
                                                <div class="w-6 h-6 border-2 border-indigo-500 border-t-transparent rounded-full animate-spin mx-auto mb-2"></div>
                                                <span class="text-xs text-indigo-300">Scanning...</span>
                                            </div>
                                            <div id="jit-result" class="text-sm text-white/60">
                                                Pilih konfigurasi path di kiri.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- SECTION 27: KONFIGURASI TEMA --}}
                    <section id="section-27" class="lesson-section scroll-mt-32" data-lesson-id="27">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-violet-500 pl-6">
                                <span class="text-violet-400 font-mono text-xs uppercase tracking-widest">Lesson 1.6.4</span>
                                <h2 class="text-4xl lg:text-5xl font-black text-white leading-[1.1]">
                                    Konfigurasi Tema <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-violet-400 to-fuchsia-500">(Extend vs Override)</span>
                                </h2>
                            </div>
                            
                            <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed space-y-6">
                                <p>
                                    File <code>tailwind.config.js</code> bukan hanya tempat mendaftarkan file content, tapi juga tempat Anda mendesain sistem tema Anda (Design System). Di sinilah Anda menentukan warna brand, jenis font khusus, atau ukuran breakpoint custom.
                                </p>
                                <p>
                                    Namun, ada satu jebakan besar yang sering menjerat pemula, yang ditekankan dalam buku <em>"Tailwind CSS by SitePoint"</em>: Perbedaan antara <strong>Extending</strong> (Memperluas) dan <strong>Overriding</strong> (Menimpa).
                                </p>
                                
                                <div class="grid md:grid-cols-2 gap-6 mt-4">
                                    <div class="bg-white/5 p-6 rounded-xl border border-white/5 hover:border-green-500/30 transition group h-full">
                                        <div class="flex items-center gap-3 mb-4">
                                            <div class="w-8 h-8 rounded-lg bg-green-500/20 text-green-400 flex items-center justify-center font-bold text-lg">✓</div>
                                            <h4 class="text-green-400 font-bold text-lg">theme.extend</h4>
                                        </div>
                                        <p class="text-sm text-white/70 leading-relaxed">
                                            Ini adalah cara yang <strong>paling aman dan direkomendasikan</strong> (99% kasus). Dengan menempatkan konfigurasi di dalam objek <code>extend</code>, Anda menambahkan nilai baru <em>tanpa menghapus</em> nilai default Tailwind.
                                            <br><br>
                                            <em>Analogi:</em> Anda merenovasi rumah dengan <strong>menambahkan</strong> garasi baru. Rumah utama (kamar tidur, dapur) tetap ada dan bisa dipakai.
                                        </p>
                                    </div>
                                    <div class="bg-white/5 p-6 rounded-xl border border-white/5 hover:border-red-500/30 transition group h-full">
                                        <div class="flex items-center gap-3 mb-4">
                                            <div class="w-8 h-8 rounded-lg bg-red-500/20 text-red-400 flex items-center justify-center font-bold text-lg">⚠</div>
                                            <h4 class="text-red-400 font-bold text-lg">theme (Direct)</h4>
                                        </div>
                                        <p class="text-sm text-white/70 leading-relaxed">
                                            Ini adalah zona bahaya. Menempatkan konfigurasi langsung di bawah <code>theme</code> akan <strong>mengganti total</strong> kategori tersebut.
                                            <br><br>
                                            <em>Analogi:</em> Anda merubuhkan seluruh rumah lama dan hanya membangun satu garasi. Kamar tidur dan dapur (warna bawaan Tailwind seperti red-500, blue-500) akan hilang/terhapus.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- SIMULATOR 4: THEME CONFIGURATOR --}}
                            <div class="bg-[#0b0f19] border border-white/10 rounded-2xl p-8 shadow-2xl relative overflow-hidden mt-8">
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                                    <div class="space-y-6">
                                        <div class="flex justify-between items-center border-b border-white/5 pb-2">
                                            <h3 class="text-sm font-bold text-violet-400 uppercase tracking-widest">Live Configurator</h3>
                                            <span class="text-[10px] text-white/30 bg-white/5 px-2 py-1 rounded font-mono">tailwind.config.js</span>
                                        </div>
                                        <div class="space-y-4">
                                            <div>
                                                <label class="text-xs text-white/50 block mb-1">Primary Color (Hex)</label>
                                                <div class="flex gap-2">
                                                    <input type="text" id="config-color" value="#8b5cf6" class="bg-white/5 border border-white/10 rounded px-3 py-2 text-sm text-white w-full font-mono focus:border-violet-500 focus:outline-none transition uppercase" oninput="updateConfigPreview()">
                                                    <input type="color" id="config-color-picker" value="#8b5cf6" class="h-10 w-10 bg-transparent cursor-pointer rounded overflow-hidden border-0" oninput="document.getElementById('config-color').value = this.value; updateConfigPreview()">
                                                </div>
                                            </div>
                                            <div>
                                                <div class="flex justify-between">
                                                    <label class="text-xs text-white/50 block mb-1">Border Radius</label>
                                                    <span id="radius-val" class="text-xs text-violet-400 font-mono">0.5rem</span>
                                                </div>
                                                <input type="range" id="config-radius" min="0" max="2" step="0.1" value="0.5" class="w-full h-1 bg-white/10 rounded-lg appearance-none cursor-pointer accent-violet-500" oninput="updateConfigPreview()">
                                            </div>
                                        </div>
                                        <div class="bg-black/30 p-4 rounded-lg border border-white/5 font-mono text-[10px] text-gray-400 leading-relaxed shadow-inner">
                                            theme: {<br>
                                            &nbsp;&nbsp;extend: {<br>
                                            &nbsp;&nbsp;&nbsp;&nbsp;colors: { brand: '<span id="code-color" class="text-violet-400">#8b5cf6</span>' },<br>
                                            &nbsp;&nbsp;&nbsp;&nbsp;borderRadius: { box: '<span id="code-radius" class="text-violet-400">0.5rem</span>' }<br>
                                            &nbsp;&nbsp;}<br>
                                            }
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-center bg-[url('https://grainy-gradients.vercel.app/noise.svg')] bg-cover rounded-xl border border-white/5 h-64 relative overflow-hidden">
                                        <div class="absolute inset-0 bg-grid-white/[0.05]"></div>
                                        <div id="preview-box" class="w-32 h-32 flex items-center justify-center text-white font-bold shadow-2xl transition-all duration-300 transform hover:scale-105" style="background-color: #8b5cf6; border-radius: 0.5rem;">
                                            Preview
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- FINAL ACTIVITY CHECKPOINT (SECTION 28) --}}
                    <section id="section-28" class="lesson-section scroll-mt-32 pt-10 border-t border-white/10" data-lesson-id="28" data-type="activity">
                        <div class="relative rounded-[2rem] bg-[#0b0f19] border border-white/10 p-8 overflow-hidden shadow-2xl group hover:border-cyan-500/30 transition-all duration-500 text-center">
                            
                            {{-- READ ONLY OVERLAY IF COMPLETED --}}
                            <div id="activityOverlay" class="hidden absolute inset-0 bg-[#0b0f19]/95 z-20 flex-col items-center justify-center backdrop-blur-sm">
                                <div class="w-16 h-16 bg-emerald-500/10 rounded-full flex items-center justify-center mb-4 border border-emerald-500/50 shadow-[0_0_20px_rgba(16,185,129,0.2)]">
                                    <svg class="w-8 h-8 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                </div>
                                <h3 class="text-xl font-bold text-white mb-1">Misi Selesai!</h3>
                                <p class="text-white/50 text-xs mb-6">Anda telah menguasai dasar instalasi.</p>
                                <button class="px-6 py-2 rounded-full bg-white/5 border border-white/10 text-white/30 text-xs font-mono font-bold cursor-not-allowed uppercase tracking-widest">
                                    Read-Only Mode
                                </button>
                            </div>

                            <div class="relative z-10 flex flex-col md:flex-row gap-8 text-left">
                                {{-- Left: Instructions --}}
                                <div class="w-full md:w-1/3">
                                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-600 text-white text-[10px] font-bold uppercase mb-4 tracking-widest shadow-lg shadow-cyan-600/20">
                                        Misi: Config Doctor
                                    </div>
                                    <h2 class="text-2xl font-bold text-white mb-2">Website Rusak!</h2>
                                    <p class="text-white/60 text-sm mb-6 leading-relaxed">
                                        Seorang intern baru saja menghapus konfigurasi penting. 
                                        <br>1. JIT tidak jalan karena <code>content</code> kosong.
                                        <br>2. Warna default hilang karena dia menggunakan <em>Override</em> bukannya <em>Extend</em>.
                                    </p>
                                    <div class="p-4 bg-red-500/10 border border-red-500/20 rounded-lg mb-4">
                                        <h4 class="text-red-400 text-xs font-bold mb-1">Diagnosa Error:</h4>
                                        <ul class="list-disc list-inside text-[10px] text-red-200/70">
                                            <li>Class 'bg-red-500' not generated.</li>
                                            <li>Default colors missing.</li>
                                        </ul>
                                    </div>
                                </div>

                                {{-- Right: Interactive Editor --}}
                                <div class="w-full md:w-2/3 flex flex-col gap-4">
                                    {{-- Preview Screen --}}
                                    <div class="bg-white rounded-lg h-32 w-full overflow-hidden relative shadow-lg flex items-center justify-center transition-all" id="website-preview">
                                        <div id="preview-unstyled" class="text-center p-4">
                                            <h1 class="text-black text-xl font-serif mb-1">Website Saya</h1>
                                            <button class="bg-gray-200 border border-black p-1 text-black text-xs">Tombol Biasa</button>
                                            <p class="text-red-500 text-[10px] mt-2 font-bold">⚠️ Tampilan Hancur (No CSS)</p>
                                        </div>
                                        <div id="preview-styled" class="hidden w-full h-full bg-gradient-to-br from-indigo-600 to-cyan-600 flex flex-col items-center justify-center text-white">
                                            <h1 class="text-2xl font-bold mb-2 drop-shadow-md">✨ Website Keren</h1>
                                            <button class="bg-white text-indigo-600 px-4 py-1.5 rounded-full font-bold shadow-lg hover:scale-105 transition text-xs">Tombol Cantik</button>
                                            <div class="absolute bottom-2 right-2 flex gap-1">
                                                <div class="w-2 h-2 rounded-full bg-red-500"></div>
                                                <div class="w-2 h-2 rounded-full bg-green-500"></div>
                                                <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Code Editor --}}
                                    <div class="relative group">
                                        <div class="absolute top-0 left-0 bg-[#0f1117] text-gray-500 text-[10px] px-3 py-1 rounded-br border-r border-b border-white/10 font-mono">tailwind.config.js</div>
                                        <textarea id="config-editor" class="w-full h-48 bg-[#1e1e1e] rounded-xl border border-white/10 p-4 pt-8 font-mono text-xs text-blue-300 focus:border-cyan-500/50 outline-none resize-none leading-relaxed transition-all selection:bg-cyan-500/40" spellcheck="false">
module.exports = {
  // TODO 1: Isi content array dengan "./index.html"
  content: [], 

  theme: {
    // TODO 2: Pindahkan colors ke dalam extend!
    colors: {
      'brand': '#0ea5e9',
    },
  },
}
                                        </textarea>
                                        
                                        {{-- Validation Feedback --}}
                                        <div id="validation-msg" class="absolute bottom-2 right-4 text-[10px] font-bold hidden"></div>
                                    </div>

                                    <button id="run-check-btn" onclick="validateConfig()" class="w-full py-3 bg-cyan-600 hover:bg-cyan-500 text-white font-bold rounded-xl transition-all text-xs shadow-lg shadow-cyan-500/20 flex items-center justify-center gap-2 group">
                                        <span>🔧</span> Perbaiki & Jalankan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </section>

                </article>

                {{-- FOOTER NAVIGATION --}}
                <div class="mt-32 pt-10 border-t border-white/10 flex justify-between items-center">
                    <a href="{{ route('courses.advantages') }}" class="group flex items-center gap-3 text-slate-400 hover:text-white transition">
                        <div class="w-10 h-10 rounded-full border border-white/10 flex items-center justify-center group-hover:border-white/30 transition">←</div>
                        <div class="text-left"><div class="text-[10px] uppercase tracking-widest opacity-50">Sebelumnya</div><div class="font-bold text-sm">Keunggulan</div></div>
                    </a>
                    
                    <div id="nextChapterBtn" class="group flex items-center gap-3 text-right text-cyan-400 hover:text-cyan-300 transition cursor-pointer">
                        <div class="text-right">
                            <div id="nextLabel" class="text-[10px] uppercase tracking-widest opacity-50">Bab Berikutnya</div>
                            <div class="font-bold text-sm">Bab 2: Layouting</div>
                        </div>
                        <div id="nextIcon" class="w-10 h-10 rounded-full border border-cyan-500/30 bg-cyan-500/10 flex items-center justify-center group-hover:bg-cyan-500/20 transition">→</div>
                    </div>
                </div>

                <div class="mt-16 text-center text-white/20 text-xs font-mono">&copy; {{ date('Y') }} Flowwind Learn. Materi dilindungi hak cipta.</div>
            </div>
        </main>
    </div>
</div>

<style>
    .nav-link.active { color: #22d3ee; position: relative; }
    .nav-link.active::after { content: ''; position: absolute; left: 0; bottom: -6px; width: 100%; height: 2px; background: linear-gradient(to right,#22d3ee,#3b82f6); box-shadow: 0 0 12px rgba(34,211,238,.8); border-radius: 2px; }
    .custom-scrollbar::-webkit-scrollbar { width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
    
    @keyframes shake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-5px); } 75% { transform: translateX(5px); } }
    .shake { animation: shake 0.4s ease-in-out; }

    #animated-bg{ background: radial-gradient(600px circle at 20% 20%, rgba(168,85,247,.15), transparent 40%), radial-gradient(700px circle at 80% 30%, rgba(217,70,239,.15), transparent 40%), radial-gradient(800px circle at 50% 80%, rgba(34,211,238,.15), transparent 40%); animation:bgMove 20s ease-in-out infinite alternate; }
    @keyframes bgMove{to{transform:scale(1.15)}}
    
    .nav-item.active { color: #c084fc; background: rgba(192,132,252,0.05); font-weight: 600; }
    .nav-item.active .dot { background: #c084fc; box-shadow: 0 0 8px #c084fc; transform: scale(1.2); }
    
    .sb-group.open .accordion-content { max-height: 1000px; opacity: 1; }
    .sb-group:not(.open) .accordion-content { max-height: 0; opacity: 0; overflow: hidden; }
    .sb-group.open svg { transform: rotate(180deg); }
    
    .terminal-line { animation: fadeIn 0.3s ease-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }
</style>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    /* --- CONFIGURATION --- */
    window.LESSON_IDS = [24, 25, 26, 27, 28]; 
    window.COMPLETED_IDS = {!! json_encode($completedLessonIds ?? []) !!};
    let completedSet = new Set(window.COMPLETED_IDS);
    
    // Activity Status
    let activityCompleted = {!! ($activityCompleted ?? false) ? 'true' : 'false' !!};
    const ACTIVITY_ID = 6; // ID Activity
    const ACTIVITY_LESSON_ID = 28; // ID Lesson Penutup

    document.addEventListener('DOMContentLoaded', () => {
        initSidebarScroll();
        initLessonObserver();
        initVisualEffects();
        initTerminalSimulator();
        updateProgressUI();
        
        // CHECK IF COMPLETED ON LOAD
        if (activityCompleted) {
            lockActivityUI();
        } else {
            lockNextChapter(); // Default Locked
        }
    });

    /* --- LOGIC UTAMA: PROGRESS & LOCK --- */
    function updateProgressUI() {
        const total = window.LESSON_IDS.length;
        const done = window.LESSON_IDS.filter(id => completedSet.has(id)).length;
        let percent = Math.round((done / total) * 100);
        
        // STRICT CHECK: Jika activity belum selesai, mentok di 95%
        if (done === total && !activityCompleted) {
            percent = 95;
        }

        const bar = document.getElementById('topProgressBar');
        const label = document.getElementById('progressLabelTop');
        
        if(bar) bar.style.width = percent + '%';
        if(label) label.innerText = percent + '%';
        
        if (percent === 100 && activityCompleted) {
            unlockNextChapter();
        }
    }

    function unlockNextChapter() {
        const btn = document.getElementById('nextChapterBtn');
        const label = document.getElementById('nextLabel');
        const icon = document.getElementById('nextIcon');

        if(btn) {
            btn.classList.remove('cursor-not-allowed', 'opacity-50', 'pointer-events-none', 'text-slate-500');
            btn.classList.add('text-cyan-400', 'hover:text-cyan-300', 'cursor-pointer');
            label.innerText = "Mulai Praktik";
            label.classList.remove('opacity-50');
            icon.innerHTML = "💻";
            icon.classList.remove('bg-white/5', 'border-white/5');
            icon.classList.add('bg-cyan-500/20', 'border-cyan-500/50', 'text-cyan-400', 'shadow-[0_0_15px_rgba(34,211,238,0.3)]');
            
            // Set link ke Lab atau Bab Berikutnya
            btn.onclick = () => window.location.href = "{{ route('lab.start', ['id' => 1]) }}"; 
        }
    }

    function lockNextChapter() {
        const btn = document.getElementById('nextChapterBtn');
        if(btn) {
            btn.classList.add('cursor-not-allowed', 'opacity-50', 'pointer-events-none', 'text-slate-500');
            btn.classList.remove('text-cyan-400', 'hover:text-cyan-300', 'cursor-pointer');
            btn.onclick = null;
        }
    }

    /* --- SIMULATOR 1: NODE CHECKER --- */
    function runNodeCheck(cmd) {
        const output = document.getElementById('node-check-output');
        const p = document.createElement('p');
        p.className = "font-mono text-xs mb-1 terminal-line";
        
        if(cmd === 'node -v') {
            p.innerHTML = `<span class="text-white">$ node -v</span><br><span class="text-green-400">v20.11.0 (LTS Detected)</span>`;
        } else {
            p.innerHTML = `<span class="text-white">$ npm -v</span><br><span class="text-blue-400">10.2.4</span>`;
        }
        output.appendChild(p);
        output.scrollTop = output.scrollHeight;
    }

    /* --- SIMULATOR 2: TERMINAL CLI --- */
    function initTerminalSimulator() {
        const input = document.getElementById('cli-input');
        const output = document.getElementById('terminal-output');
        const hint = document.getElementById('terminal-hint');
        const historyDiv = document.getElementById('terminal-history');
        
        let step = 0;
        const steps = [
            { cmd: 'npm init -y', res: '<span class="text-green-400">✔</span> Package.json created successfully.', hint: 'Langkah 2: Install package Tailwind' },
            { cmd: 'npm install -D tailwindcss postcss autoprefixer', res: '<span class="text-blue-400">⬇</span> Downloading packages... <br> <span class="text-green-400">✔</span> Added 3 packages in 2s.', hint: 'Langkah 3: Inisialisasi Config' },
            { cmd: 'npx tailwindcss init', res: '<span class="text-green-400">✔</span> Created Tailwind config file: <strong>tailwind.config.js</strong>', hint: 'Selesai! Anda siap coding.' }
        ];

        window.autoType = function(text) {
            if(input) {
                input.value = text;
                input.focus();
            }
        };

        if(input) {
            input.addEventListener('keypress', function (e) {
                if (e.key === 'Enter') {
                    const val = this.value.trim();
                    
                    // Create History Element
                    const historyItem = document.createElement('div');
                    historyItem.className = "mb-2 terminal-line";
                    historyItem.innerHTML = `<div class="flex gap-2"><span class="text-green-400 font-bold">➜</span> <span class="text-white">${val}</span></div>`;
                    
                    if(step < steps.length && val === steps[step].cmd) {
                        // Success Response
                        historyItem.innerHTML += `<div class="text-gray-400 ml-5 mt-1">${steps[step].res}</div>`;
                        historyDiv.appendChild(historyItem);
                        
                        // Move to next step
                        step++;
                        this.value = '';
                        
                        if(step < steps.length) {
                            hint.querySelector('span').innerText = steps[step-1].hint;
                            hint.querySelector('code').innerText = steps[step].cmd;
                            hint.querySelector('code').setAttribute('onclick', `autoType('${steps[step].cmd}')`);
                            showFeedback('Perintah berhasil!', 'text-green-400', 'border-green-500/30');
                        } else {
                            hint.innerHTML = `<span class="text-green-400 font-bold">🎉 Instalasi Selesai!</span>`;
                            this.placeholder = 'Instalasi Selesai!';
                            this.disabled = true;
                            showFeedback('Selamat! Anda telah menguasai instalasi CLI.', 'text-cyan-400', 'border-cyan-500/30');
                        }
                    } else {
                        // Error Response
                        historyItem.innerHTML += `<div class="text-red-400 ml-5 mt-1">Command not found or incorrect step. Try: ${steps[step].cmd}</div>`;
                        historyDiv.appendChild(historyItem);
                        
                        showFeedback('Perintah salah. Coba lagi.', 'text-red-400', 'border-red-500/30');
                        this.classList.add('shake');
                        setTimeout(() => this.classList.remove('shake'), 500);
                        this.value = '';
                    }
                    output.scrollTop = output.scrollHeight;
                }
            });
        }
    }

    function showFeedback(msg, colorClass, borderClass) {
        const el = document.getElementById('terminal-feedback');
        if(el) {
            el.innerText = msg;
            el.className = `absolute bottom-4 right-4 text-xs font-bold px-3 py-1 rounded bg-black/50 backdrop-blur border ${borderClass} ${colorClass}`;
            el.classList.remove('hidden');
            setTimeout(() => el.classList.add('hidden'), 3000);
        }
    }

    /* --- SIMULATOR 3: JIT VISUALIZER --- */
    function setJitPath(isCorrect) {
        const pathEl = document.getElementById('jit-path');
        const output = document.getElementById('jit-result');
        const loading = document.getElementById('jit-loading');
        const box = document.getElementById('jit-output-box');

        loading.classList.remove('hidden');
        output.classList.add('hidden');
        
        if (isCorrect) {
            pathEl.innerText = '"./resources/**/*.blade.php"';
            pathEl.className = "text-green-400 transition-colors font-bold";
        } else {
            pathEl.innerText = '"./wrong/folder/**/*.html"';
            pathEl.className = "text-red-400 transition-colors font-bold";
        }

        setTimeout(() => {
            loading.classList.add('hidden');
            output.classList.remove('hidden');
            if (isCorrect) {
                output.innerHTML = `<span class="text-green-400 font-bold">✔ CSS Generated</span><br><code class="text-xs bg-black/20 p-1 rounded mt-1 block">.text-sky-500 { color: #0ea5e9 }</code>`;
                box.className = "h-32 bg-green-500/10 border border-green-500/30 rounded flex items-center justify-center text-center transition-all";
            } else {
                output.innerHTML = `<span class="text-red-400 font-bold">✘ No CSS Output</span><br><span class="text-xs">Tailwind tidak menemukan class karena path salah.</span>`;
                box.className = "h-32 bg-red-500/10 border border-red-500/30 rounded flex items-center justify-center text-center transition-all";
            }
        }, 800);
    }

    /* --- SIMULATOR 4: THEME CONFIG --- */
    function updateConfigPreview() {
        const color = document.getElementById('config-color').value;
        const radius = document.getElementById('config-radius').value;
        const box = document.getElementById('preview-box');
        const codeColor = document.getElementById('code-color');
        const codeRadius = document.getElementById('code-radius');
        const radiusVal = document.getElementById('radius-val');

        box.style.backgroundColor = color;
        box.style.borderRadius = radius + 'rem';
        codeColor.innerText = color;
        codeRadius.innerText = radius + 'rem';
        radiusVal.innerText = radius + 'rem';
    }

    /* --- ACTIVITY LOGIC (CONFIG DOCTOR) --- */
    function validateConfig() {
        if(activityCompleted) return;

        const code = document.getElementById('config-editor').value;
        const msgEl = document.getElementById('validation-msg');
        const unstyled = document.getElementById('preview-unstyled');
        const styled = document.getElementById('preview-styled');
        const btn = document.getElementById('run-check-btn');

        // Regex Validation
        const hasContent = /content:\s*\[\s*['"`].+['"`]\s*\]/.test(code) || /content:\s*\[\s*['"`].+['"`],\s*\]/.test(code);
        const hasExtend = /extend:\s*\{\s*colors:/.test(code.replace(/\s/g, ''));

        if(hasContent && hasExtend) {
            // SUCCESS STATE
            unstyled.classList.add('hidden');
            styled.classList.remove('hidden');
            msgEl.innerHTML = "<span class='text-green-400'>✔ PERFECT! Config Fixed.</span>";
            msgEl.classList.remove('hidden');
            
            btn.innerHTML = "🎉 Simpan & Selesaikan";
            btn.className = "w-full py-3 bg-green-600 hover:bg-green-500 text-white font-bold rounded-xl transition-all text-xs shadow-lg shadow-green-500/20 flex items-center justify-center gap-2";
            btn.onclick = finishChapter;
        } else {
            // ERROR STATE
            msgEl.innerHTML = "<span class='text-red-400'>❌ Masih Salah. Cek Content & Extend.</span>";
            msgEl.classList.remove('hidden');
            btn.classList.add('shake');
            setTimeout(() => btn.classList.remove('shake'), 500);
        }
    }

    async function finishChapter() {
        const btn = document.getElementById('run-check-btn');
        btn.innerHTML = "Menyimpan...";
        btn.disabled = true;

        try {
            await fetch('/activity/complete', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'Content-Type': 'application/json' }, body: JSON.stringify({ activity_id: ACTIVITY_ID, score: 100 }) });
            await saveLessonToDB(ACTIVITY_LESSON_ID); 
            completedSet.add(ACTIVITY_LESSON_ID);
            activityCompleted = true;
            updateProgressUI();
            lockActivityUI();
        } catch(e) { console.error(e); btn.innerHTML = "Gagal. Coba lagi."; btn.disabled = false; }
    }

    function lockActivityUI() {
        const overlay = document.getElementById('activityOverlay');
        if(overlay) {
            overlay.classList.remove('hidden');
            overlay.style.display = 'flex';
        }
        // Force preview to success state
        const unstyled = document.getElementById('preview-unstyled');
        const styled = document.getElementById('preview-styled');
        if(unstyled && styled) {
            unstyled.classList.add('hidden');
            styled.classList.remove('hidden');
        }
    }

    /* --- SYSTEM FUNCTIONS --- */
    function initSidebarScroll(){const m=document.getElementById('mainScroll');const l=document.querySelectorAll('.accordion-content .nav-item');m.addEventListener('scroll',()=>{let c='';document.querySelectorAll('.lesson-section').forEach(s=>{if(m.scrollTop>=s.offsetTop-250)c='#'+s.id;});l.forEach(k=>{k.classList.remove('active');if(k.getAttribute('data-target')===c)k.classList.add('active')})});l.forEach(k=>k.addEventListener('click',(e)=>{const t=document.querySelector(k.getAttribute('data-target'));if(t)m.scrollTo({top:t.offsetTop-120,behavior:'smooth'})}));}
    function initVisualEffects(){const c=document.getElementById('stars'),x=c.getContext('2d');function r(){c.width=innerWidth;c.height=innerHeight}r();window.onresize=r;let s=[];for(let i=0;i<100;i++)s.push({x:Math.random()*c.width,y:Math.random()*c.height,r:Math.random()*1.2,v:Math.random()*0.2+.1});(function a(){x.clearRect(0,0,c.width,c.height);x.fillStyle='rgba(255,255,255,.3)';s.forEach(t=>{x.beginPath();x.arc(t.x,t.y,t.r,0,6.28);x.fill();t.y+=t.v;if(t.y>c.height)t.y=0});requestAnimationFrame(a)})();$(window).on('mousemove',e=>{$('#cursor-glow').css({left:e.clientX,top:e.clientY})});}
    
    function initLessonObserver() {
        const obs = new IntersectionObserver(async entries => {
            for (const entry of entries) {
                if (entry.isIntersecting) {
                    const id = Number(entry.target.dataset.lessonId);
                    const type = entry.target.dataset.type;
                    
                    if (type === 'activity') return;

                    if (id && !completedSet.has(id)) {
                        try { 
                            await saveLessonToDB(id); 
                            completedSet.add(id); 
                            updateProgressUI(); 
                        } catch(e) {}
                    }
                }
            }
        }, { threshold: 0.5, root: document.getElementById('mainScroll') });
        document.querySelectorAll('.lesson-section').forEach(s => obs.observe(s));
    }

    async function saveLessonToDB(id) { 
        await fetch('/lesson/complete', { 
            method: 'POST', 
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, 
            body: new URLSearchParams({ lesson_id: id }) 
        }); 
    }

    function toggleAccordion(id) {
        const el = document.getElementById(id);
        const group = el.closest('.accordion-group');
        const arrow = document.getElementById(id.replace('content', 'arrow'));
        if(el.style.maxHeight){ el.style.maxHeight=null; group.classList.remove('open'); if(arrow) arrow.style.transform='rotate(0deg)'; }
        else{ el.style.maxHeight=el.scrollHeight+"px"; group.classList.add('open'); if(arrow) arrow.style.transform='rotate(180deg)'; }
    }
</script>
@endsection