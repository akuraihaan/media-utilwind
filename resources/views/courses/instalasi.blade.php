@extends('layouts.landing')
@section('title','Bab 1.6 · Instalasi & Konfigurasi Dasar')

@section('content')
<div id="courseRoot" class="relative h-screen bg-[#020617] text-white font-sans overflow-hidden flex flex-col selection:bg-cyan-500/30">

    {{-- Background Effects --}}
    <div class="fixed inset-0 -z-50 pointer-events-none">
        <div id="animated-bg" class="absolute inset-0 opacity-20"></div>
        <div class="absolute top-[-20%] left-[-10%] w-[1200px] h-[1200px] bg-cyan-900/20 rounded-full blur-[150px] animate-pulse"></div>
        <div class="absolute bottom-[-20%] right-[-10%] w-[1000px] h-[1000px] bg-blue-900/20 rounded-full blur-[100px]"></div>
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.03]"></div>
        <canvas id="stars" class="absolute inset-0 pointer-events-none"></canvas>
    </div>

    {{-- Navbar --}}
    <nav id="navbar" class="h-[74px] w-full bg-[#020617]/10 backdrop-blur-xl border-b border-white/5 shrink-0 z-50 flex items-center justify-between px-6 lg:px-8 transition-all duration-500 relative">
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center font-extrabold text-white shadow-xl">TW</div>
            <span class="font-semibold tracking-wide text-lg">TailwindLearn</span>
        </div>
        <div class="hidden md:flex gap-10 text-sm font-medium">
            <a href="{{ route('landing') }}" class="nav-link opacity-70 hover:opacity-100 transition">Beranda</a>
            <span class="nav-link active cursor-default">Course</span> 
            <a href="{{ route('dashboard') }}" class="nav-link opacity-70 hover:opacity-100 transition">Dashboard</a>
        </div>
        <div class="flex gap-3 items-center">
            <span class="text-white/70 text-sm hidden sm:block">{{ Auth::user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="cta-main px-6 py-2 rounded-xl bg-gradient-to-r from-cyan-600 to-blue-600 text-sm font-semibold shadow-xl hover:scale-105 transition">Keluar</button>
            </form>
        </div>
    </nav>

    <div class="flex flex-1 overflow-hidden relative z-20">

        @include('layouts.partials.course-sidebar')

        <main id="mainScroll" class="flex-1 h-full overflow-y-auto scroll-smooth relative bg-transparent custom-scrollbar scroll-padding-top-24">
            
            {{-- Sticky Header with PROGRESS BAR --}}
            <div id="stickyHeader" class="sticky top-0 z-30 w-full bg-[#020617]/90 backdrop-blur-2xl border-b border-white/5 px-8 py-4 flex items-center justify-between transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-cyan-500/20 to-transparent border border-cyan-500/20 flex items-center justify-center font-bold text-xs text-cyan-400">1.6</div>
                    <div>
                        <h1 class="text-sm font-bold text-white">Instalasi & Konfigurasi Dasar</h1>
                        <p class="text-[10px] text-white/50">Fondasi Utama Tailwind CSS</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="hidden sm:block w-40 h-1.5 bg-white/10 rounded-full overflow-hidden">
                        <div id="topProgressBar" class="h-full bg-gradient-to-r from-cyan-500 to-blue-500 w-0 transition-all duration-500 shadow-[0_0_10px_#22d3ee]"></div>
                    </div>
                    <span id="progressLabelTop" class="text-cyan-400 font-bold text-xs">0%</span>
                </div>
            </div>

            <div class="p-6 lg:p-16 max-w-5xl mx-auto pb-40">
                
                {{-- Target Pembelajaran --}}
                <div class="mb-24">
                    <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-2">Target Pembelajaran</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-[#151515] border border-white/5 p-6 rounded-xl flex items-start gap-4 hover:border-blue-500/30 transition group h-full">
                            <div class="w-10 h-10 rounded-lg bg-blue-900/20 text-blue-400 flex items-center justify-center shrink-0 font-bold text-lg border border-blue-500/10">1</div>
                            <div>
                                <h4 class="text-sm font-bold text-white mb-2 group-hover:text-blue-400 transition">Instalasi NPM</h4>
                                <p class="text-xs text-white/50 leading-relaxed">
                                    Memahami mengapa kita perlu menginstal Tailwind menggunakan Terminal (CLI), bukan sekadar copy-paste link.
                                </p>
                            </div>
                        </div>
                        <div class="bg-[#151515] border border-white/5 p-6 rounded-xl flex items-start gap-4 hover:border-pink-500/30 transition group h-full">
                            <div class="w-10 h-10 rounded-lg bg-pink-900/20 text-pink-400 flex items-center justify-center shrink-0 font-bold text-lg border border-pink-500/10">2</div>
                            <div>
                                <h4 class="text-sm font-bold text-white mb-2 group-hover:text-pink-400 transition">File Konfigurasi</h4>
                                <p class="text-xs text-white/50 leading-relaxed">
                                    Memahami fungsi file <code>tailwind.config.js</code> sebagai "pusat kendali" atau otak dari website Anda.
                                </p>
                            </div>
                        </div>
                        <div class="bg-[#151515] border border-white/5 p-6 rounded-xl flex items-start gap-4 hover:border-yellow-500/30 transition group h-full">
                            <div class="w-10 h-10 rounded-lg bg-yellow-900/20 text-yellow-400 flex items-center justify-center shrink-0 font-bold text-lg border border-yellow-500/10">3</div>
                            <div>
                                <h4 class="text-sm font-bold text-white mb-2 group-hover:text-yellow-400 transition">Tema & Warna</h4>
                                <p class="text-xs text-white/50 leading-relaxed">
                                    Memahami cara menambah warna sendiri tanpa merusak atau menghilangkan warna bawaan Tailwind.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <article class="space-y-40">
                    
                    {{-- LESSON 1.6.1 --}}
                    <section id="section-1" class="lesson-section scroll-mt-32" data-lesson-id="24">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-blue-500 pl-6">
                                <span class="text-blue-400 font-mono text-xs uppercase tracking-widest">Lesson 1.6.1</span>
                                <h2 class="text-4xl lg:text-5xl font-black text-white leading-[1.1]">
                                    Kenapa Harus <br> 
                                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-500">Lewat Terminal (NPM)?</span>
                                </h2>
                            </div>
                            
                            {{-- MATERI PERTAMA: ANALOGI SEDERHANA --}}
                            <div class="space-y-6">
                                <div class="prose prose-invert max-w-none text-white/80 text-lg leading-relaxed space-y-6">
                                    <p>
                                        Banyak pemula bertanya: <em>"Kenapa tidak pakai link CDN saja seperti Bootstrap? Kenapa harus ribet mengetik perintah di layar hitam (terminal)?"</em>. Jawabannya ada pada <strong>Kendali Penuh</strong>.
                                    </p>
                                    <p>
                                        Bayangkan Anda ingin memiliki rumah. Menggunakan CDN itu ibarat <strong>Menyewa Kamar Hotel</strong>. Cepat, siap pakai, tapi Anda tidak boleh mengecat tembok, tidak boleh merobohkan sekat, dan Anda harus menerima semua perabotan yang ada di sana, suka atau tidak suka.
                                    </p>
                                    <p>
                                        Menginstal Tailwind via NPM (Node Package Manager) itu ibarat <strong>Membangun Rumah Sendiri</strong>. Awalnya terlihat rumit karena harus menyiapkan pondasi, tapi Anda bebas menentukan warna cat, bentuk jendela, dan membuang barang yang tidak perlu. Dalam dunia web, "barang tidak perlu" ini adalah kode CSS sampah yang membuat website lambat. Dengan NPM, kita bisa membuang semua itu otomatis.
                                    </p>
                                </div>
                            </div>

                            {{-- SIMULASI 1: STEP-BY-STEP TERMINAL --}}
                            <div class="bg-[#0f1117] rounded-xl border border-white/10 overflow-hidden shadow-2xl mt-8 font-mono text-sm relative group">
                                <div class="bg-[#1e222e] px-4 py-2 flex items-center gap-2 border-b border-white/5">
                                    <div class="flex gap-1.5"><div class="w-3 h-3 rounded-full bg-red-500"></div><div class="w-3 h-3 rounded-full bg-yellow-500"></div><div class="w-3 h-3 rounded-full bg-green-500"></div></div>
                                    <div class="text-xs text-gray-500 ml-2">terminal</div>
                                </div>
                                
                                <div class="p-8 h-80 overflow-y-auto space-y-6 relative flex flex-col justify-center items-center" id="terminal-body">
                                    {{-- Initial State --}}
                                    <div id="sim1-intro" class="text-center space-y-4">
                                        <div class="text-4xl">📦</div>
                                        <p class="text-gray-400">Mari kita praktikkan instalasi. Klik tombol di bawah untuk memulai.</p>
                                        <button onclick="runStep(1)" class="px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white rounded-lg font-bold transition shadow-lg shadow-blue-500/20">
                                            1. Install Tailwind CSS
                                        </button>
                                    </div>

                                    {{-- Step 1 Result --}}
                                    <div id="sim1-step1" class="hidden w-full text-left space-y-2">
                                        <div class="text-blue-400">➜ project <span class="text-white">npm install -D tailwindcss postcss autoprefixer</span></div>
                                        <div class="text-gray-500 text-xs pl-4">
                                            [+] tailwindcss... done<br>
                                            [+] postcss... done<br>
                                            <span class="text-green-400">✔ Paket berhasil diunduh.</span>
                                        </div>
                                        <div class="pt-4 text-center">
                                            <button onclick="runStep(2)" class="px-6 py-3 bg-purple-600 hover:bg-purple-500 text-white rounded-lg font-bold transition shadow-lg shadow-purple-500/20">
                                                2. Buat File Konfigurasi
                                            </button>
                                        </div>
                                    </div>

                                    {{-- Step 2 Result --}}
                                    <div id="sim1-step2" class="hidden w-full text-left space-y-2">
                                        <div class="text-blue-400">➜ project <span class="text-white">npx tailwindcss init</span></div>
                                        <div class="text-gray-500 text-xs pl-4">
                                            <span class="text-green-400">✔ Created Tailwind config file: tailwind.config.js</span>
                                        </div>
                                        <div class="pt-6 text-center w-full">
                                            <div class="text-green-400 font-bold bg-green-500/10 p-2 rounded border border-green-500/20 inline-block">
                                                🎉 SIAP! Website Anda sekarang punya "Pondasi".
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 1.6.2 --}}
                    <section id="section-2" class="lesson-section scroll-mt-32" data-lesson-id="25">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-pink-500 pl-6">
                                <span class="text-pink-400 font-mono text-xs uppercase tracking-widest">Lesson 1.6.2</span>
                                <h2 class="text-4xl lg:text-5xl font-black text-white leading-[1.1]">
                                    Peta Lokasi: <br> 
                                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-pink-400 to-rose-500">File Konfigurasi</span>
                                </h2>
                            </div>

                            {{-- VISUALISASI DIAGRAM: PETA HARTA KARUN --}}
                            <div class="w-full bg-[#1a1015] border border-pink-500/20 p-6 rounded-xl relative overflow-hidden group">
                                <h4 class="text-center text-pink-300 font-bold mb-6 uppercase tracking-widest text-xs">Cara Kerja "Content" Array</h4>
                                <div class="flex items-center justify-between gap-4 text-center relative z-10">
                                    <div class="w-24 p-3 bg-gray-800 rounded border border-white/10">
                                        <div class="text-2xl mb-1">📄</div>
                                        <div class="text-[10px] text-gray-400">index.html</div>
                                        <div class="text-[8px] text-pink-400 font-mono">class="bg-red-500"</div>
                                    </div>
                                    
                                    <div class="flex-1 h-1 bg-gray-700 relative rounded overflow-hidden">
                                        <div class="absolute inset-0 bg-pink-500 w-1/2 animate-[loading_2s_infinite]"></div>
                                    </div>

                                    <div class="w-24 p-3 bg-pink-900/20 rounded border border-pink-500/50">
                                        <div class="text-2xl mb-1">⚙️</div>
                                        <div class="text-[10px] text-white font-bold">tailwind.config</div>
                                        <div class="text-[8px] text-gray-400">content: ["./index.html"]</div>
                                    </div>

                                    <div class="flex-1 h-1 bg-gray-700 relative rounded overflow-hidden">
                                        <div class="absolute inset-0 bg-green-500 w-1/2 animate-[loading_2s_infinite_1s]"></div>
                                    </div>

                                    <div class="w-24 p-3 bg-gray-800 rounded border border-white/10">
                                        <div class="text-2xl mb-1">🎨</div>
                                        <div class="text-[10px] text-green-400 font-bold">Output CSS</div>
                                        <div class="text-[8px] text-gray-400">.bg-red-500 { ... }</div>
                                    </div>
                                </div>
                            </div>

                            {{-- MATERI --}}
                            <div class="space-y-6">
                                <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed space-y-4">
                                    <p>
                                        Setelah instalasi selesai, sebuah file baru bernama <code>tailwind.config.js</code> akan muncul. Anggap file ini sebagai <strong>"Pusat Komando"</strong> atau otak dari proyek Anda. Di sinilah Anda mengatur segala sesuatu tentang tampilan website.
                                    </p>
                                    <p>
                                        Pengaturan yang paling penting—dan sering dilupakan pemula—adalah bagian <code>content: []</code>. Bagian ini berfungsi seperti <strong>Peta Harta Karun</strong> bagi Tailwind.
                                    </p>
                                    <p>
                                        Tailwind itu buta. Dia tidak tahu file mana yang berisi kode HTML Anda. Dengan mengisi <code>content</code> (misalnya: <code>"./index.html"</code>), Anda memberitahu Tailwind: <em>"Hei, tolong baca file ini. Kalau di dalamnya ada tulisan 'bg-red-500', tolong buatkan kode warna merah untuk saya."</em>. Jika peta ini kosong, Tailwind tidak akan membaca apa-apa, dan website Anda akan tampil tanpa gaya sama sekali.
                                    </p>
                                </div>
                            </div>

                            {{-- SIMULATOR 2: CONFIG EFFECT --}}
                            <div class="bg-[#151515] border border-white/10 rounded-xl overflow-hidden shadow-2xl p-8 mt-8">
                                <h4 class="text-center text-white font-bold mb-6">Simulasi: Apa yang terjadi jika Config Kosong?</h4>
                                <div class="grid md:grid-cols-2 gap-8 items-center">
                                    <div class="space-y-4">
                                        <p class="text-sm text-gray-400 text-center">Status File Config:</p>
                                        <div class="flex justify-center">
                                            <button onclick="toggleConfig()" id="btn-config-toggle" class="px-6 py-3 bg-red-500/20 text-red-400 border border-red-500 rounded-xl w-full transition-all hover:scale-105 font-bold flex items-center justify-center gap-2">
                                                <span>❌</span> Content: [ ] (KOSONG)
                                            </button>
                                        </div>
                                        <p class="text-xs text-gray-500 text-center italic">Klik tombol di atas untuk mengisi config</p>
                                    </div>
                                    
                                    <div class="bg-white rounded-xl h-48 overflow-hidden relative shadow-lg flex flex-col items-center justify-center transition-all" id="website-preview">
                                        <div id="preview-unstyled" class="text-center p-4">
                                            <h1 class="text-black text-2xl font-serif mb-2">Website Saya</h1>
                                            <button class="bg-gray-200 border border-black p-1 text-black text-sm">Tombol Biasa</button>
                                            <p class="text-red-500 text-xs mt-4 font-bold">↑ Tampilan hancur karena Tailwind tidak jalan</p>
                                        </div>

                                        <div id="preview-styled" class="hidden text-center w-full h-full bg-gradient-to-br from-indigo-500 to-purple-600 flex flex-col items-center justify-center text-white">
                                            <h1 class="text-3xl font-bold mb-4 drop-shadow-md">✨ Website Keren</h1>
                                            <button class="bg-white text-purple-600 px-6 py-2 rounded-full font-bold shadow-lg hover:scale-105 transition">Tombol Cantik</button>
                                            <p class="text-white/80 text-xs mt-4">✔ Tailwind mendeteksi & styling file!</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 1.6.3 --}}
                    <section id="section-3" class="lesson-section scroll-mt-32" data-lesson-id="26">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-yellow-500 pl-6">
                                <span class="text-yellow-400 font-mono text-xs uppercase tracking-widest">Lesson 1.6.3</span>
                                <h2 class="text-4xl lg:text-5xl font-black text-white leading-[1.1]">
                                    Menambah Gaya: <br> 
                                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-orange-500">Extend vs Overwrite</span>
                                </h2>
                            </div>

                            {{-- MATERI --}}
                            <div class="space-y-6">
                                <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed space-y-4">
                                    <p>
                                        Setelah paham dasar konfigurasi, Anda mungkin ingin menambahkan warna khas perusahaan atau font unik. Di sinilah banyak pemula melakukan kesalahan fatal. Dalam file konfigurasi, ada dua tempat untuk menaruh pengaturan: langsung di bawah <code>theme</code> atau di dalam <code>theme.extend</code>.
                                    </p>
                                    <p>
                                        <strong>1. Bahaya Overwrite (Menimpa)</strong><br>
                                        Jika Anda menaruh warna langsung di <code>theme: { colors: ... }</code>, Anda memberitahu Tailwind: <em>"Lupakan semua warna yang kamu punya, ganti semuanya dengan warna baruku ini."</em> Akibatnya, ribuan warna standar (seperti merah, biru, hijau) akan hilang seketika.
                                    </p>
                                    <p>
                                        <strong>2. Keamanan Extend (Memperluas)</strong><br>
                                        Cara yang benar adalah menggunakan <code>theme: { extend: { colors: ... } }</code>. Kata kunci <strong>extend</strong> sangat penting. Ini memberitahu Tailwind: <em>"Tolong simpan semua warna lama, tapi tambahkan juga warna baru ini ke dalam koleksi."</em> Dengan begini, Anda mendapatkan yang terbaik dari kedua dunia.
                                    </p>
                                </div>
                            </div>

                            {{-- SIMULATOR 3: THEME CONFIG --}}
                            <div class="bg-[#151515] p-8 rounded-2xl border border-white/10 relative shadow-2xl overflow-hidden mt-8">
                                <h4 class="text-xs font-bold text-white/50 uppercase mb-8 text-center">Simulasi: Efek pada Kotak Pensil (Palette)</h4>
                                
                                <div class="flex flex-col md:flex-row gap-8">
                                    <div class="w-full md:w-1/2 space-y-4">
                                        <p class="text-sm text-gray-400 text-center">Pilih cara menambahkan warna 'Emas':</p>
                                        
                                        <div class="flex flex-col gap-3">
                                            <button onclick="setThemeMode('overwrite')" class="group p-4 border border-red-500/30 rounded-xl hover:bg-red-500/10 transition text-left relative overflow-hidden">
                                                <div class="absolute inset-0 bg-red-500/5 group-hover:bg-red-500/10 transition"></div>
                                                <div class="relative z-10">
                                                    <div class="text-sm font-bold text-red-400">⛔ Cara Salah (Overwrite)</div>
                                                    <div class="text-[10px] text-gray-500 mt-1 font-mono">theme: { colors: { 'emas': ... } }</div>
                                                </div>
                                            </button>

                                            <button onclick="setThemeMode('extend')" class="group p-4 border border-green-500/30 rounded-xl hover:bg-green-500/10 transition text-left relative overflow-hidden">
                                                <div class="absolute inset-0 bg-green-500/5 group-hover:bg-green-500/10 transition"></div>
                                                <div class="relative z-10">
                                                    <div class="text-sm font-bold text-green-400">✅ Cara Benar (Extend)</div>
                                                    <div class="text-[10px] text-gray-500 mt-1 font-mono">theme: { extend: { colors: ... } }</div>
                                                </div>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="w-full md:w-1/2 bg-black/30 rounded-xl p-6 border border-white/5">
                                        <p class="text-[10px] text-gray-500 mb-4 text-center uppercase tracking-wider">Koleksi Warna Anda:</p>
                                        
                                        {{-- Visual Grid --}}
                                        <div class="grid grid-cols-5 gap-3 transition-all duration-500 min-h-[100px] content-start" id="color-grid">
                                            <div class="w-8 h-8 rounded bg-red-500" title="Merah"></div>
                                            <div class="w-8 h-8 rounded bg-blue-500" title="Biru"></div>
                                            <div class="w-8 h-8 rounded bg-green-500" title="Hijau"></div>
                                            <div class="w-8 h-8 rounded bg-purple-500" title="Ungu"></div>
                                            <div class="w-8 h-8 rounded bg-pink-500" title="Pink"></div>
                                            <div class="w-8 h-8 rounded bg-orange-500" title="Orange"></div>
                                            <div class="w-8 h-8 rounded bg-gray-500" title="Abu-abu"></div>
                                            <div class="w-8 h-8 rounded bg-white" title="Putih"></div>
                                            <div class="w-8 h-8 rounded bg-black" title="Hitam"></div>
                                            <div class="w-8 h-8 rounded bg-teal-500" title="Teal"></div>
                                        </div>

                                        <p id="theme-feedback" class="text-xs text-center mt-6 text-gray-500">Pilih mode di kiri...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- ACTIVITY SECTION: CONFIG DOCTOR --}}
                    <section id="section-4" class="lesson-section scroll-mt-32 pt-10 border-t border-white/10" data-lesson-id="27" data-type="activity">
                        <div class="relative rounded-[2.5rem] bg-[#0b0f19] border border-white/10 p-8 overflow-hidden shadow-2xl group hover:border-cyan-500/30 transition-all duration-500">
                            
                            {{-- Activity Header --}}
                            <div class="flex flex-col md:flex-row items-start md:items-center gap-6 mb-8 relative z-10">
                                <div class="p-4 bg-gradient-to-br from-cyan-600 to-blue-800 rounded-2xl text-white shadow-lg shadow-cyan-500/20 shrink-0">
                                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                                </div>
                                <div>
                                    <div class="flex items-center gap-3 mb-1">
                                        <h2 class="text-3xl font-black text-white tracking-tight">Misi: Perbaiki Konfigurasi</h2>
                                        <span id="status-badge" class="px-2 py-0.5 rounded text-[10px] font-bold bg-cyan-500/20 text-cyan-400 border border-cyan-500/30 uppercase tracking-wider">Tugas Wajib</span>
                                    </div>
                                    <p class="text-cyan-200/60 text-sm leading-relaxed max-w-2xl">
                                        Seorang pemula membuat file konfigurasi yang salah total. Website-nya tidak punya gaya (karena <code>content</code> kosong) dan warna default-nya hilang semua (karena salah posisi <code>colors</code>).
                                        <br><br>
                                        <strong>Tugas Anda:</strong> Perbaiki kode di editor bawah ini agar website normal kembali.
                                    </p>
                                </div>
                            </div>

                            {{-- Editor Interface --}}
                            <div class="space-y-4">
                                <div class="relative group">
                                    <div class="absolute top-0 left-0 bg-cyan-900/20 text-cyan-400 text-[10px] px-3 py-1 rounded-br border-r border-b border-cyan-500/20 font-bold z-10">tailwind.config.js</div>
                                    
                                    <textarea id="config-editor" class="w-full h-64 bg-[#0f1117] rounded-xl border border-white/10 p-6 pt-10 font-mono text-sm text-gray-300 focus:border-cyan-500/50 outline-none resize-none leading-relaxed transition-all selection:bg-cyan-500/40" spellcheck="false">
module.exports = {
  // 1. Content masih kosong! Isi dengan "./index.html"
  content: [], 

  theme: {
    // 2. Salah tempat! Pindahkan 'colors' ke dalam 'extend'
    colors: {
      'brand-blue': '#1e40af',
    },
  },
  plugins: [],
}
                                    </textarea>
                                </div>

                                {{-- Checklist Hints --}}
                                <div class="grid grid-cols-2 gap-4 text-[10px] text-gray-500 bg-black/20 p-4 rounded-lg border border-white/5">
                                    <div id="check-content" class="flex items-center gap-2 transition-all">
                                        <span class="w-5 h-5 rounded-full border border-gray-600 flex items-center justify-center text-[8px] bg-gray-800 transition-all">1</span>
                                        <span>Isi array content dengan <code>"./index.html"</code></span>
                                    </div>
                                    <div id="check-extend" class="flex items-center gap-2 transition-all">
                                        <span class="w-5 h-5 rounded-full border border-gray-600 flex items-center justify-center text-[8px] bg-gray-800 transition-all">2</span>
                                        <span>Pindahkan <code>colors</code> ke dalam <code>extend: { ... }</code></span>
                                    </div>
                                </div>

                                <button id="run-check-btn" onclick="validateConfig()" class="w-full py-4 bg-white/5 text-gray-400 font-bold rounded-xl transition-all text-xs border border-white/5 flex items-center justify-center gap-2 hover:bg-white/10 group cursor-pointer hover:border-cyan-500/50">
                                    <span class="group-hover:scale-110 transition">▶</span> CEK JAWABAN SAYA
                                </button>
                                <div id="validation-msg" class="text-center text-xs font-mono h-4 opacity-0 transition">...</div>
                            </div>
                        </div>
                    </section>

                </article>

                {{-- Footer --}}
                <div class="mt-32 pt-10 border-t border-white/10 flex justify-between items-center">
                    <a href="{{ route('courses.implementation') }}" class="group flex items-center gap-3 text-slate-400 hover:text-white transition">
                        <div class="w-10 h-10 rounded-full border border-white/10 flex items-center justify-center group-hover:border-white/30 transition">←</div>
                        <div class="text-left"><div class="text-[10px] uppercase tracking-widest opacity-50">Sebelumnya</div><div class="font-bold text-sm">Bab 1.5: Keunggulan</div></div>
                    </a>
                    
                    <div id="nextChapterBtn" class="group flex items-center gap-3 text-right text-slate-500 cursor-not-allowed opacity-50 pointer-events-none transition-all duration-500">
                        <div class="text-right">
                            <div id="nextLabel" class="text-[10px] uppercase tracking-widest opacity-50">Terkunci</div>
                            <div class="font-bold text-sm">Kuis Bab 1</div>
                        </div>
                        <div id="nextIcon" class="w-10 h-10 rounded-full border border-white/5 flex items-center justify-center bg-white/5">🔒</div>
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>

<style>
    @keyframes loading { 0% { transform: translateX(-100%); } 100% { transform: translateX(200%); } }
    .nav-link.active { color: #a855f7; position: relative; }
    .nav-link.active::after { content: ''; position: absolute; left: 0; bottom: -6px; width: 100%; height: 2px; background: linear-gradient(to right,#a855f7,#6366f1); box-shadow: 0 0 12px rgba(168,85,247,.8); border-radius: 2px; }
    .custom-scrollbar::-webkit-scrollbar { width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
    #animated-bg{ animation:bgMove 20s ease-in-out infinite alternate; }
    @keyframes bgMove{to{transform:scale(1.15)}}
    @keyframes shake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-5px); } 75% { transform: translateX(5px); } }
    .animate-shake { animation: shake 0.3s ease-in-out; }
</style>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
    /* =========================================
       KONFIGURASI UTAMA
       ========================================= */
    window.LESSON_IDS = [24, 25, 26, 27]; 
    window.COMPLETED_IDS = {!! json_encode($completedLessonIds ?? []) !!};
    let completedSet = new Set(window.COMPLETED_IDS);
    
    // Status Aktivitas (Hanya 100% jika ini true)
    let activityCompleted = {!! ($activityCompleted ?? false) ? 'true' : 'false' !!};
    const ACTIVITY_ID = 6; 
    const ACTIVITY_LESSON_ID = 27; 

    document.addEventListener('DOMContentLoaded', () => {
        initScrollSpy();
        initLessonObserver();
        
        // --- LOGIKA KUNCI UTAMA (STRICT MODE) ---
        if (!activityCompleted) {
            if (completedSet.has(ACTIVITY_LESSON_ID)) {
                completedSet.delete(ACTIVITY_LESSON_ID); // Hapus jika belum selesai
            }
            lockNextChapter(); 
        } else {
            completedSet.add(ACTIVITY_LESSON_ID);
            lockActivityUI(); // Kunci editor karena sudah done
        }
        
        updateProgressUI(); 
    });

    /* =========================================
       LOGIKA PROGRESS & LOCKING
       ========================================= */
    function updateProgressUI() {
        const total = window.LESSON_IDS.length;
        const done = window.LESSON_IDS.filter(id => completedSet.has(id)).length;
        const percent = Math.round((done / total) * 100);
        
        document.getElementById('topProgressBar').style.width = percent + '%'; 
        document.getElementById('progressLabelTop').innerText = percent + '%';
        
        if(percent === 100) {
            unlockNextChapter();
        } else {
            lockNextChapter();
        }
    }

    function unlockNextChapter() {
        const btn = document.getElementById('nextChapterBtn');
        if(!btn) return;
        btn.classList.remove('cursor-not-allowed', 'opacity-50', 'pointer-events-none', 'text-slate-500');
        btn.classList.add('text-purple-400', 'hover:text-purple-300', 'cursor-pointer', 'group');
        document.getElementById('nextLabel').innerText = "Mulai Kuis";
        document.getElementById('nextLabel').classList.remove('opacity-50');
        document.getElementById('nextIcon').innerHTML = "→";
        document.getElementById('nextIcon').classList.add('shadow-[0_0_10px_#22d3ee]', 'border-cyan-500');
        btn.onclick = () => window.location.href = "{{ route('quiz.intro', ['chapterId' => 1]) }}"; 
    }

    function lockNextChapter() {
        const btn = document.getElementById('nextChapterBtn');
        if(!btn) return;
        btn.classList.add('cursor-not-allowed', 'opacity-50', 'pointer-events-none', 'text-slate-500');
        btn.classList.remove('text-purple-400', 'hover:text-purple-300', 'cursor-pointer', 'group');
        document.getElementById('nextLabel').innerText = "Terkunci";
        btn.onclick = null;
    }

    /* =========================================
       SIMULASI 1: TERMINAL (Click Flow)
       ========================================= */
    function runStep(step) {
        if(step === 1) {
            // Tampilkan Step 1 Loading
            const intro = document.getElementById('sim1-intro');
            const res1 = document.getElementById('sim1-step1');
            
            intro.innerHTML = `<span class="text-yellow-400 animate-pulse">Installing packages...</span>`;
            
            setTimeout(() => {
                intro.classList.add('hidden');
                res1.classList.remove('hidden');
            }, 1000);
        } 
        else if (step === 2) {
            // Tampilkan Step 2
            const res2 = document.getElementById('sim1-step2');
            document.querySelector('#sim1-step1 button').classList.add('hidden'); // hide prev button
            
            res2.classList.remove('hidden');
        }
    }

    /* =========================================
       SIMULASI 2: CONFIG EFFECT (TOGGLE)
       ========================================= */
    let isConfigured = false;
    function toggleConfig() {
        const btn = document.getElementById('btn-config-toggle');
        const preview = document.getElementById('website-preview');
        const unstyled = document.getElementById('preview-unstyled');
        const styled = document.getElementById('preview-styled');

        isConfigured = !isConfigured;

        if (isConfigured) {
            // Switch to Configured
            btn.innerHTML = "<span>✅</span> Content: [ './index.html' ]";
            btn.className = "px-6 py-3 bg-green-500/20 text-green-400 border border-green-500 rounded-xl w-full transition-all hover:scale-105 font-bold flex items-center justify-center gap-2 shadow-[0_0_15px_#22c55e]";
            
            unstyled.classList.add('hidden');
            styled.classList.remove('hidden');
        } else {
            // Revert
            btn.innerHTML = "<span>❌</span> Content: [ ] (KOSONG)";
            btn.className = "px-6 py-3 bg-red-500/20 text-red-400 border border-red-500 rounded-xl w-full transition-all hover:scale-105 font-bold flex items-center justify-center gap-2";
            
            unstyled.classList.remove('hidden');
            styled.classList.add('hidden');
        }
    }

    /* =========================================
       SIMULASI 3: THEME VISUAL
       ========================================= */
    function setThemeMode(mode) {
        const grid = document.getElementById('color-grid');
        const msg = document.getElementById('theme-feedback');
        grid.innerHTML = '';

        if (mode === 'overwrite') {
            // HANYA 1 Warna (Sisanya hilang)
            const div = document.createElement('div');
            div.className = "w-8 h-8 rounded bg-yellow-400 border-2 border-red-500 shadow-[0_0_15px_red]";
            grid.appendChild(div);
            msg.innerHTML = "<span class='text-red-400 font-bold'>BAHAYA!</span> Warna default (merah, biru, dll) hilang.";
        } else {
            // BANYAK warna (Default + Baru)
            const colors = ['bg-red-500', 'bg-blue-500', 'bg-green-500', 'bg-purple-500', 'bg-white', 'bg-gray-500', 'bg-pink-500', 'bg-orange-500', 'bg-teal-500', 'bg-yellow-400'];
            colors.forEach(c => {
                const d = document.createElement('div');
                if(c === 'bg-yellow-400') {
                    d.className = "w-8 h-8 rounded bg-yellow-400 border-2 border-white shadow-[0_0_10px_gold] order-first";
                } else {
                    d.className = `w-8 h-8 rounded ${c} border border-white/10 opacity-50`;
                }
                grid.appendChild(d);
            });
            msg.innerHTML = "<span class='text-green-400 font-bold'>AMAN!</span> Warna lama tetap ada + warna baru.";
        }
    }

    /* =========================================
       ACTIVITY VALIDATION
       ========================================= */
    function validateConfig() {
        const code = document.getElementById('config-editor').value;
        const feedback = document.getElementById('validation-msg');
        const btn = document.getElementById('run-check-btn');

        // Regex fleksibel
        const hasContent = /content:\s*\[\s*['"`].+['"`]\s*\]/.test(code) || /content:\s*\[\s*['"`].+['"`],\s*\]/.test(code);
        const hasExtend = /extend:\s*\{\s*colors:/.test(code.replace(/\s/g, ''));

        // Update checklist UI
        if(hasContent) {
            document.getElementById('check-content').classList.replace('text-gray-500', 'text-green-400');
            document.getElementById('check-content').querySelector('span:first-child').classList.replace('bg-gray-800', 'bg-green-500');
            document.getElementById('check-content').querySelector('span:first-child').classList.add('text-black');
            document.getElementById('check-content').querySelector('span:first-child').innerText = "✓";
        }
        if(hasExtend) {
            document.getElementById('check-extend').classList.replace('text-gray-500', 'text-green-400');
            document.getElementById('check-extend').querySelector('span:first-child').classList.replace('bg-gray-800', 'bg-green-500');
            document.getElementById('check-extend').querySelector('span:first-child').classList.add('text-black');
            document.getElementById('check-extend').querySelector('span:first-child').innerText = "✓";
        }

        if (hasContent && hasExtend) {
            feedback.innerText = "JAWABAN BENAR! Syabas.";
            feedback.className = "text-center text-xs mt-2 h-4 text-green-400 font-bold mt-2 animate-pulse";
            btn.innerHTML = "🎉 SELESAIKAN BAB INI";
            btn.className = "w-full mt-4 py-3 bg-green-600 text-white font-bold rounded-lg hover:scale-105 transition shadow-[0_0_20px_rgba(34,197,94,0.6)]";
            btn.onclick = null;
            
            finishChapter();
        } else {
            feedback.innerText = "Masih salah. Cek instruksi di kotak merah/hijau.";
            feedback.className = "text-center text-xs mt-2 h-4 text-red-400 font-bold animate-pulse";
            btn.classList.add('animate-shake');
            setTimeout(() => btn.classList.remove('animate-shake'), 500);
        }
    }

    async function finishChapter() {
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
        } catch(e) { console.error(e); }
    }

    function lockActivityUI() {
        document.getElementById('config-editor').disabled = true;
        document.getElementById('config-editor').classList.add('opacity-50', 'cursor-not-allowed');
        document.getElementById('status-badge').innerText = "SELESAI";
        document.getElementById('status-badge').className = "px-2 py-0.5 rounded text-[10px] font-bold bg-green-500/20 text-green-400 border border-green-500/30 uppercase tracking-wider";
        const btn = document.getElementById('run-check-btn');
        btn.innerText = "MISI TERSELESAIKAN";
        btn.className = "w-full py-4 bg-gray-800 text-gray-500 font-bold rounded-xl transition-all text-xs border border-white/5 cursor-not-allowed";
        btn.disabled = true;
    }

    // Helper: Scroll Spy (Sidebar)
    function initScrollSpy() {
        const mainScroll = document.getElementById('mainScroll'); 
        const navLinks = document.querySelectorAll('.sidebar-nav-link');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const id = '#' + entry.target.id;
                    navLinks.forEach(link => {
                        const dot = link.querySelector('.dot-indicator'); if(!dot) return;
                        link.classList.remove('bg-white/5'); dot.classList.remove('bg-purple-500', 'shadow-[0_0_8px_#a855f7]', 'scale-125'); dot.classList.add('bg-slate-600');
                        if (link.dataset.target === id) { link.classList.add('bg-white/5'); dot.classList.remove('bg-slate-600'); dot.classList.add('bg-purple-500', 'shadow-[0_0_8px_#a855f7]', 'scale-125'); }
                    });
                }
            });
        }, { root: mainScroll, rootMargin: '-20% 0px -60% 0px', threshold: 0 });
        document.querySelectorAll('.lesson-section').forEach(s => observer.observe(s));
    }

    // Helper: Lesson Observer (Auto Save for Reading)
    function initLessonObserver() {
        const obs = new IntersectionObserver(async entries => {
            for (const entry of entries) {
                if (entry.isIntersecting) {
                    const id = Number(entry.target.dataset.lessonId);
                    const type = entry.target.dataset.type;
                    
                    // Filter: Jangan auto-save Activity
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
</script>
@endsection