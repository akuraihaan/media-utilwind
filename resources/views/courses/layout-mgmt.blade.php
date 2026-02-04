@extends('layouts.landing')
@section('title','Bab 2.3 · Mengelola Layout Tingkat Lanjut')

@section('content')
<div id="courseRoot" class="relative h-screen bg-[#020617] text-white font-sans overflow-hidden flex flex-col selection:bg-indigo-500/30">

    <div class="fixed inset-0 -z-50 pointer-events-none">
        <div id="animated-bg" class="absolute inset-0 opacity-50"></div>
        <div class="absolute top-[-20%] left-[-10%] w-[800px] h-[800px] bg-indigo-900/10 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute bottom-[-20%] right-[-10%] w-[600px] h-[600px] bg-violet-900/10 rounded-full blur-[100px]"></div>
        <canvas id="stars" class="absolute inset-0 pointer-events-none"></canvas>
        <div id="noise-overlay" class="absolute inset-0 z-10 opacity-[0.04]"></div>
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
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500/20 to-transparent border border-indigo-500/20 flex items-center justify-center font-bold text-xs text-indigo-400">2.3</div>
                    <div>
                        <h1 class="text-sm font-bold text-white">Mengelola Layout</h1>
                        <p class="text-[10px] text-white/50">Mastering Layout Flow & Positioning</p>
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
                <article class="space-y-32">
                    
                    <section id="section-41" class="lesson-section scroll-mt-32" data-lesson-id="41">
                        <div class="space-y-8">
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-300 text-[10px] font-bold uppercase tracking-widest">
                                <!-- <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 animate-pulse"></span> -->
                                Mengelola Layout
                            </div>
                            
                            <h2 class="text-4xl lg:text-5xl font-black text-white leading-[1.1]">
                                Container & <br> 
                                <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-purple-500">Konfigurasi Viewport</span>
                            </h2>
                            
                            <div class="prose prose-invert max-w-4xl text-white/70 text-lg leading-relaxed space-y-6">
                                <p>
                                    Dalam pengembangan antarmuka modern, kita tidak lagi mendesain untuk satu ukuran layar statis. Kita mendesain untuk ekosistem perangkat yang cair, mulai dari smartwatch hingga layar ultrawide 4K. Fondasi dari responsivitas ini terletak pada dua konsep utama: <strong>Viewport</strong> dan <strong>Container</strong>.
                                </p>

                                <div class="bg-indigo-900/20 border-l-4 border-indigo-500 p-6 rounded-r-xl">
                                    <h4 class="text-white font-bold text-lg mb-2">1. The Viewport Meta Tag</h4>
                                    <p class="text-sm mb-3">
                                        Viewport adalah area tampilan browser tempat konten Anda dirender. Tanpa konfigurasi yang benar, browser mobile akan mencoba "meniru" layar desktop dengan merender halaman pada lebar (misalnya) 980px lalu mengecilkannya (zoom out), membuat teks menjadi sangat kecil dan tidak terbaca.
                                    </p>
                                    <p class="text-sm mb-2">Solusinya adalah menambahkan meta tag ini di <code>&lt;head&gt;</code>:</p>
                                    <code class="block bg-black/40 p-3 rounded text-xs text-indigo-300 font-mono border border-indigo-500/30">
                                        &lt;meta name="viewport" content="width=device-width, initial-scale=1"&gt;
                                    </code>
                                </div>

                                <div>
                                    <h4 class="text-white font-bold text-lg mb-2">2. Utility .container</h4>
                                    <p>
                                        Banyak framework menggunakan kelas <code>.container</code> sebagai elemen pembungkus utama. Di Tailwind, kelas ini berfungsi menetapkan <code>max-width</code> elemen agar sesuai dengan <em>min-width</em> breakpoint saat ini.
                                    </p>
                                    <ul class="list-disc pl-5 mt-2 space-y-2 text-base">
                                        <li>Di layar kecil (mobile), container lebarnya 100%.</li>
                                        <li>Di layar medium (md), max-width dikunci ke 768px.</li>
                                        <li>Di layar large (lg), max-width dikunci ke 1024px, dan seterusnya.</li>
                                    </ul>
                                    <p class="mt-4 bg-white/5 p-4 rounded-lg border border-white/5 text-sm">
                                        <strong class="text-indigo-400">PERHATIAN:</strong> Berbeda dengan Bootstrap, container di Tailwind <strong>tidak otomatis berada di tengah</strong> dan <strong>tidak memiliki padding</strong> bawaan. Anda harus selalu memadukannya dengan:
                                        <br><br>
                                        <code class="text-indigo-300">mx-auto</code> : Untuk margin kiri-kanan otomatis (centering).
                                        <br>
                                        <code class="text-indigo-300">px-4</code> : Untuk memberikan jarak aman (gutter) di sisi layar.
                                    </p>
                                </div>
                            </div>

                            <div class="bg-[#0b0f19] border border-white/10 rounded-2xl p-8 shadow-2xl relative overflow-hidden group">
                                <div class="flex flex-wrap justify-between items-center mb-6 gap-4">
                                    <h3 class="text-lg font-bold text-white">📏 Container Simulator</h3>
                                    <div class="flex gap-1 bg-white/5 p-1 rounded-lg">
                                        <button onclick="resizeContainer('w-full', this)" class="res-btn px-4 py-2 text-xs rounded bg-indigo-600 text-white shadow-lg transition active-btn">Mobile (100%)</button>
                                        <button onclick="resizeContainer('max-w-md', this)" class="res-btn px-4 py-2 text-xs rounded bg-white/5 text-white/50 hover:bg-white/10 transition">Tablet (md)</button>
                                        <button onclick="resizeContainer('max-w-2xl', this)" class="res-btn px-4 py-2 text-xs rounded bg-white/5 text-white/50 hover:bg-white/10 transition">Desktop (xl)</button>
                                    </div>
                                </div>
                                <div class="w-full bg-black/50 h-64 rounded-xl border border-white/5 relative flex items-center justify-center overflow-hidden">
                                    <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-10"></div>
                                    <div id="demo-container" class="h-40 bg-indigo-500/10 border border-indigo-500/50 rounded-lg flex flex-col items-center justify-center text-center p-4 transition-all duration-700 w-full mx-auto relative z-10 shadow-[0_0_50px_rgba(99,102,241,0.15)] backdrop-blur-sm">
                                        <span class="text-indigo-300 font-bold text-xl tracking-tight">Konten Website</span>
                                        <span class="text-[10px] text-white/40 mt-2 bg-black/40 px-3 py-1.5 rounded-full border border-white/5 font-mono">max-width applied</span>
                                    </div>
                                    <div class="absolute top-0 bottom-0 left-4 w-px bg-red-500/30 border-r border-dashed border-red-500/50" title="Padding Left"></div>
                                    <div class="absolute top-0 bottom-0 right-4 w-px bg-red-500/30 border-l border-dashed border-red-500/50" title="Padding Right"></div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="section-42" class="lesson-section scroll-mt-32" data-lesson-id="42">
                        <div class="flex items-center gap-4 mb-8">
                            <h2 class="text-3xl font-bold text-white">2. Legacy Layout: Float & Clear</h2>
                            <div class="h-px flex-1 bg-gradient-to-r from-white/10 to-transparent"></div>
                        </div>

                        <div class="grid md:grid-cols-2 gap-8">
                            <div class="space-y-6 text-white/70">
                                <p>
                                    Sebelum era modern Flexbox dan Grid, <code>float</code> adalah satu-satunya mekanisme untuk membuat layout kolom. Namun di era Tailwind modern, penggunaan float telah bergeser menjadi sangat spesifik: <strong>Text Wrapping</strong>.
                                </p>
                                <p>
                                    Properti <code>float</code> mengangkat elemen dari aliran dokumen normal (normal flow) dan menempelkannya ke sisi kiri atau kanan wadahnya, membiarkan teks atau elemen inline lainnya "mengalir" mengisi ruang kosong di sekitarnya. Ini meniru tata letak majalah cetak.
                                </p>
                                <div class="bg-orange-900/10 border-l-4 border-orange-500 p-4 rounded-r-lg mt-4">
                                    <strong class="text-orange-400 text-sm block mb-1">Warning: The Clearing Problem</strong>
                                    <p class="text-xs">
                                        Karena elemen float "keluar" dari aliran dokumen, elemen parent seringkali mengalami <em>height collapse</em> (tingginya menjadi 0). Untuk memperbaikinya, gunakan utility <code>clearfix</code> atau properti <code>clear-both</code> pada elemen setelahnya, atau gunakan <code>flow-root</code> pada parent.
                                    </p>
                                </div>
                            </div>

                            <div class="bg-[#1e1e1e] p-6 rounded-2xl border border-white/10 relative group">
                                <div class="absolute top-4 right-4 flex gap-2 z-10">
                                    <button onclick="toggleFloat('left')" class="px-3 py-1 bg-orange-600/20 text-orange-400 text-xs rounded border border-orange-500/30 hover:bg-orange-600/30 transition">Float Left</button>
                                    <button onclick="toggleFloat('right')" class="px-3 py-1 bg-white/5 text-white/50 text-xs rounded border border-white/10 hover:bg-white/10 transition">Float Right</button>
                                </div>
                                <h4 class="text-xs font-bold text-white/50 uppercase mb-4">Preview Majalah</h4>
                                <div class="text-xs text-white/60 leading-relaxed bg-black/30 p-6 rounded-xl border border-white/5 font-serif text-justify h-64 overflow-hidden relative">
                                    <div id="float-box" class="w-20 h-20 bg-gradient-to-br from-orange-500 to-red-600 rounded-lg mb-2 mr-4 float-left flex items-center justify-center text-white font-bold transition-all shadow-lg text-[10px] ring-4 ring-black/50">IMG</div>
                                    <p class="mb-4"><span class="text-white font-bold text-lg float-left mr-1 leading-none">L</span>orem ipsum dolor sit amet, consectetur adipiscing elit. Float memungkinkan elemen "mengapung" ke sisi container, membiarkan teks mengisi ruang yang tersisa di sebelahnya.</p>
                                    <p>Ini memberikan efek visual yang rapi dan profesional seperti pada media cetak. Coba klik tombol di pojok kanan atas untuk memindahkan posisi gambar dan melihat bagaimana teks menyesuaikan diri.</p>
                                    <div class="absolute bottom-0 left-0 w-full h-12 bg-gradient-to-t from-[#151517] to-transparent"></div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="section-43" class="lesson-section scroll-mt-32" data-lesson-id="43">
                        <div class="flex items-center gap-4 mb-8">
                            <h2 class="text-3xl font-bold text-white">3. Manipulasi Dimensi: Position & Z-Index</h2>
                            <div class="h-px flex-1 bg-gradient-to-r from-white/10 to-transparent"></div>
                        </div>

                        <div class="grid lg:grid-cols-2 gap-10 items-center">
                            <div class="space-y-6 text-white/70">
                                <p class="text-lg">
                                    Properti <code>position</code> adalah alat paling presisi untuk menempatkan elemen. Bayangkan website Anda sebagai sebuah meja kerja dengan lapisan kertas.
                                </p>
                                <ul class="space-y-4 text-sm">
                                    <li class="bg-white/5 p-4 rounded-xl border border-white/5 transition hover:border-indigo-500/50 group">
                                        <div class="flex items-center gap-3 mb-2">
                                            <span class="w-2 h-2 rounded-full bg-indigo-500 group-hover:animate-ping"></span>
                                            <strong class="text-white text-base">Relative (Si Kanvas / Acuan)</strong>
                                        </div>
                                        <p class="text-white/60">
                                            Elemen dengan <code>relative</code> diposisikan sesuai aliran normal, TAPI ia membuka fitur "koordinat" untuk anak-anaknya. Anak elemen dengan posisi absolute akan mengukur jarak (top, left) dari batas elemen relative ini.
                                        </p>
                                    </li>
                                    <li class="bg-white/5 p-4 rounded-xl border border-white/5 transition hover:border-violet-500/50 group">
                                        <div class="flex items-center gap-3 mb-2">
                                            <span class="w-2 h-2 rounded-full bg-violet-500 group-hover:animate-ping"></span>
                                            <strong class="text-white text-base">Absolute (Si Stiker Bebas)</strong>
                                        </div>
                                        <p class="text-white/60">
                                            Elemen ini <strong>dicabut</strong> dari aliran dokumen. Ia tidak memakan tempat (elemen lain akan menumpuk di bawahnya). Posisinya ditentukan oleh properti <code>top</code>, <code>right</code>, <code>bottom</code>, <code>left</code> relatif terhadap induk terdekat yang memiliki posisi non-static.
                                        </p>
                                    </li>
                                    <li class="bg-white/5 p-4 rounded-xl border border-white/5 transition hover:border-green-500/50 group">
                                        <div class="flex items-center gap-3 mb-2">
                                            <span class="w-2 h-2 rounded-full bg-green-500 group-hover:animate-ping"></span>
                                            <strong class="text-white text-base">Z-Index (Urutan Tumpukan)</strong>
                                        </div>
                                        <p class="text-white/60">
                                            Jika dua elemen bertumpuk, siapa yang menang? <code>z-index</code> menentukan sumbu Z (kedalaman). Angka lebih besar = lapisan lebih atas/depan. Tailwind menyediakan skala <code>z-0</code> hingga <code>z-50</code>.
                                        </p>
                                    </li>
                                </ul>
                            </div>

                            <div class="bg-[#0b0f19] p-10 rounded-2xl border border-white/10 shadow-2xl relative flex items-center justify-center h-96 overflow-hidden group perspective-1000">
                                <div class="absolute inset-0 bg-[linear-gradient(45deg,transparent_25%,rgba(255,255,255,0.05)_50%,transparent_75%,transparent_100%)] bg-[length:20px_20px]"></div>
                                
                                <div class="relative w-48 h-48 cursor-pointer transform-style-3d group-hover:rotate-y-12 transition-transform duration-700">
                                    <div class="absolute inset-0 bg-indigo-900/40 border-2 border-dashed border-indigo-500/50 rounded-xl flex items-center justify-center z-0 backdrop-blur-sm transform transition-transform group-hover:rotate-x-12">
                                        <div class="text-center">
                                            <p class="text-indigo-300 font-bold text-xs">Parent</p>
                                            <code class="text-[10px] bg-black/50 px-1 rounded text-white">relative</code>
                                        </div>
                                    </div>
                                    <div class="absolute top-0 left-0 w-full h-full bg-red-600/80 rounded-xl border border-red-400/50 shadow-lg flex items-start p-4 justify-start transform transition-all duration-500 ease-out group-hover:translate-z-20 group-hover:-translate-x-4 group-hover:-translate-y-4">
                                        <span class="text-white font-bold text-xs bg-black/30 px-2 py-1 rounded backdrop-blur-md shadow-lg">z-10</span>
                                    </div>
                                    <div class="absolute top-0 left-0 w-full h-full bg-blue-600/80 rounded-xl border border-blue-400/50 shadow-lg flex items-start p-4 justify-start transform transition-all duration-500 ease-out group-hover:translate-z-40 group-hover:translate-x-0 group-hover:translate-y-0 delay-75">
                                        <span class="text-white font-bold text-xs bg-black/30 px-2 py-1 rounded backdrop-blur-md shadow-lg">z-20</span>
                                    </div>
                                    <div class="absolute top-0 left-0 w-full h-full bg-green-600/90 rounded-xl border border-green-400/50 shadow-lg flex items-start p-4 justify-start transform transition-all duration-500 ease-out group-hover:translate-z-60 group-hover:translate-x-4 group-hover:translate-y-4 delay-150">
                                        <span class="text-white font-bold text-xs bg-black/30 px-2 py-1 rounded backdrop-blur-md shadow-lg">z-30</span>
                                    </div>
                                </div>
                                <p class="absolute bottom-6 text-[10px] text-white/30 uppercase tracking-widest font-mono">Hover to Explode Layers</p>
                            </div>
                        </div>
                    </section>

                    <section id="section-44" class="lesson-section scroll-mt-32" data-lesson-id="44">
                        <div class="flex items-center gap-4 mb-8">
                            <h2 class="text-3xl font-bold text-white">4. Table Layout</h2>
                            <div class="h-px flex-1 bg-gradient-to-r from-white/10 to-transparent"></div>
                        </div>

                        <div class="grid md:grid-cols-2 gap-8">
                            <div class="bg-[#1e1e1e] p-6 rounded-xl border border-white/10 hover:border-cyan-500/30 transition shadow-lg flex flex-col h-full">
                                <div class="flex justify-between mb-4 items-center">
                                    <code class="text-cyan-400 bg-cyan-500/10 px-2 py-1 rounded border border-cyan-500/20">.table-auto</code>
                                    <span class="text-[10px] text-white/50 bg-white/5 px-2 py-1 rounded">Algoritma Default</span>
                                </div>
                                <p class="text-xs text-white/60 mb-6 flex-1">
                                    Lebar kolom ditentukan oleh <strong>panjang konten</strong> di dalamnya. Browser harus membaca dan mengukur seluruh isi tabel sebelum merender. Ini bagus untuk fleksibilitas tapi lambat untuk data besar.
                                </p>
                                <div class="overflow-hidden rounded-lg border border-white/5 bg-black/20 mt-auto">
                                    <table class="table-auto w-full text-xs text-left text-white/70">
                                        <thead class="bg-white/5 text-white"><tr><th class="p-3">Nama</th><th class="p-3">Bio (Panjang)</th></tr></thead>
                                        <tbody><tr class="border-b border-white/5"><td class="p-3 whitespace-nowrap">Budi</td><td class="p-3">Bio ini sangat panjang sehingga akan mendesak kolom Nama menjadi sempit.</td></tr></tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="bg-[#1e1e1e] p-6 rounded-xl border border-white/10 hover:border-pink-500/30 transition shadow-lg flex flex-col h-full">
                                <div class="flex justify-between mb-4 items-center">
                                    <code class="text-pink-400 bg-pink-500/10 px-2 py-1 rounded border border-pink-500/20">.table-fixed</code>
                                    <span class="text-[10px] text-white/50 bg-white/5 px-2 py-1 rounded">Performa Tinggi</span>
                                </div>
                                <p class="text-xs text-white/60 mb-6 flex-1">
                                    Lebar kolom <strong>mengabaikan konten</strong> dan mematuhi lebar yang kita set (misal <code>w-1/4</code>). Browser merender tabel jauh lebih cepat karena tidak perlu "menebak" lebar berdasarkan isi.
                                </p>
                                <div class="overflow-hidden rounded-lg border border-white/5 bg-black/20 mt-auto">
                                    <table class="table-fixed w-full text-xs text-left text-white/70">
                                        <thead class="bg-white/5 text-white"><tr><th class="p-3 w-1/4">Nama (25%)</th><th class="p-3 w-3/4">Bio (75%)</th></tr></thead>
                                        <tbody><tr class="border-b border-white/5"><td class="p-3">Budi</td><td class="p-3 truncate text-white/40">Bio ini akan terpotong karena lebar fix...</td></tr></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="section-45" class="lesson-section scroll-mt-32 pt-10 border-t border-white/10" data-lesson-id="45" data-type="activity">
                        <div class="relative rounded-[2rem] bg-[#0b0f19] border border-white/10 p-8 overflow-hidden shadow-2xl group hover:border-indigo-500/30 transition-all duration-500">
                            
                            <div class="absolute -top-24 -right-24 w-64 h-64 bg-indigo-600/20 blur-[100px] rounded-full pointer-events-none"></div>

                            <div class="flex items-center gap-4 mb-6 relative z-10">
                                <div class="p-3 bg-indigo-600 rounded-xl text-white shadow-lg shadow-indigo-500/30">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold text-white">Aktivitas 2.3: The Notification Badge</h2>
                                    <p class="text-indigo-300 text-sm">Tantangan: Buat Kartu Profil yang berada di tengah, dan tempelkan Badge "NEW" di pojok kanan atasnya.</p>
                                </div>
                            </div>

                            <div class="grid lg:grid-cols-2 gap-6 max-w-6xl mx-auto">
                                
                                <div class="bg-[#1e1e1e] rounded-xl border border-white/10 flex flex-col overflow-hidden h-full relative">
                                    
                                    <div id="lockOverlay" class="hidden absolute inset-0 bg-[#050912]/95 backdrop-blur-md z-50 flex flex-col items-center justify-center text-center p-6 border-4 border-green-500/20 m-1 rounded-lg">
                                        <div class="w-20 h-20 bg-green-500/10 rounded-full flex items-center justify-center mb-4 border border-green-500/50 shadow-[0_0_30px_rgba(34,197,94,0.3)] animate-bounce">
                                            <svg class="w-10 h-10 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                        </div>
                                        <h3 class="text-2xl font-black text-white mb-2 tracking-tight">MISSION COMPLETED!</h3>
                                        <p class="text-sm text-white/50 mb-6 font-mono">Anda telah menguasai dasar Layouting.</p>
                                        <button disabled class="px-8 py-3 rounded-full bg-white/5 border border-white/10 text-white/30 text-xs font-mono font-bold cursor-not-allowed uppercase tracking-widest">
                                            Read-Only Mode
                                        </button>
                                    </div>

                                    <div class="bg-[#2d2d2d] px-4 py-3 border-b border-white/5 flex justify-between items-center">
                                        <span class="text-xs text-gray-400 font-mono flex items-center gap-2"><div class="w-2 h-2 rounded-full bg-yellow-500"></div> editor.html</span>
                                        <span class="text-[10px] text-indigo-400 font-bold tracking-wider">● Live Config</span>
                                    </div>
                                    
                                    <div class="p-6 space-y-8 flex-1 overflow-y-auto custom-scrollbar">
                                        <form id="layoutActivityForm">
                                            
                                            <div class="space-y-3">
                                                <div class="flex justify-between">
                                                    <label class="text-[10px] uppercase text-white/40 font-bold tracking-widest">1. Wrapper Luar</label>
                                                    <span class="text-[10px] text-indigo-400 font-mono bg-indigo-500/10 px-2 py-0.5 rounded">Tujuan: Tengah Layar</span>
                                                </div>
                                                <div class="grid grid-cols-2 gap-2">
                                                    <button type="button" onclick="selectOption('wrapper', 'w-full', this)" class="opt-btn-wrapper px-3 py-3 rounded-lg bg-white/5 text-gray-400 border border-white/5 text-xs text-left font-mono transition hover:bg-white/10">w-full</button>
                                                    <button type="button" onclick="selectOption('wrapper', 'container mx-auto', this)" class="opt-btn-wrapper px-3 py-3 rounded-lg bg-white/5 text-gray-400 border border-white/5 text-xs text-left font-mono transition hover:bg-white/10">container mx-auto</button>
                                                </div>
                                            </div>

                                            <div class="space-y-3">
                                                <div class="flex justify-between">
                                                    <label class="text-[10px] uppercase text-white/40 font-bold tracking-widest">2. Konteks Kartu (Parent)</label>
                                                    <span class="text-[10px] text-indigo-400 font-mono bg-indigo-500/10 px-2 py-0.5 rounded">Tujuan: Jadi Acuan</span>
                                                </div>
                                                <div class="grid grid-cols-2 gap-2">
                                                    <button type="button" onclick="selectOption('parent', 'static', this)" class="opt-btn-parent px-3 py-3 rounded-lg bg-white/5 text-gray-400 border border-white/5 text-xs text-left font-mono transition hover:bg-white/10">static (default)</button>
                                                    <button type="button" onclick="selectOption('parent', 'relative', this)" class="opt-btn-parent px-3 py-3 rounded-lg bg-white/5 text-gray-400 border border-white/5 text-xs text-left font-mono transition hover:bg-white/10">relative</button>
                                                </div>
                                            </div>

                                            <div class="space-y-3">
                                                <div class="flex justify-between">
                                                    <label class="text-[10px] uppercase text-white/40 font-bold tracking-widest">3. Posisi Badge (Child)</label>
                                                    <span class="text-[10px] text-indigo-400 font-mono bg-indigo-500/10 px-2 py-0.5 rounded">Tujuan: Pojok Kanan</span>
                                                </div>
                                                <div class="grid grid-cols-1 gap-2">
                                                    <button type="button" onclick="selectOption('badge', 'block mt-2', this)" class="opt-btn-badge px-3 py-3 rounded-lg bg-white/5 text-gray-400 border border-white/5 text-xs text-left font-mono transition hover:bg-white/10">block (normal flow)</button>
                                                    <button type="button" onclick="selectOption('badge', 'absolute top-0 right-0 m-4', this)" class="opt-btn-badge px-3 py-3 rounded-lg bg-white/5 text-gray-400 border border-white/5 text-xs text-left font-mono transition hover:bg-white/10">absolute top-0 right-0 m-4</button>
                                                </div>
                                            </div>

                                        </form>
                                    </div>
                                    <div class="p-4 bg-black/40 border-t border-white/5 flex justify-between items-center">
                                        <span class="text-[10px] text-white/30 font-mono" id="status-text">Menunggu konfigurasi...</span>
                                        <button onclick="checkSolution()" id="submitBtn" class="px-6 py-2.5 rounded-lg bg-gradient-to-r from-indigo-600 to-violet-600 text-white font-bold text-xs shadow-lg shadow-indigo-500/20 hover:scale-105 transition-all cursor-pointer">
                                            Jalankan & Periksa
                                        </button>
                                    </div>
                                </div>

                                <div class="bg-[#1e1e1e] rounded-xl border border-white/10 flex-1 flex flex-col relative overflow-hidden min-h-[400px]">
                                    <div class="bg-[#2d2d2d] px-4 py-2 border-b border-white/5 flex items-center gap-2">
                                        <div class="flex gap-1.5"><div class="w-2.5 h-2.5 rounded-full bg-red-500"></div><div class="w-2.5 h-2.5 rounded-full bg-yellow-500"></div><div class="w-2.5 h-2.5 rounded-full bg-green-500"></div></div>
                                        <span class="text-[10px] text-gray-500 font-mono ml-2">Browser Output</span>
                                    </div>
                                    <div class="flex-1 bg-gray-900 p-8 relative overflow-auto flex items-start justify-center">
                                        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-5 pointer-events-none"></div>
                                        
                                        <div id="preview-wrapper" class="bg-white/5 border border-white/10 p-4 transition-all duration-500 w-full border-dashed">
                                            <span class="text-[9px] text-white/20 block mb-2 font-mono">Wrapper Area (.container)</span>
                                            
                                            <div id="preview-parent" class="bg-[#0b0f19] border border-indigo-500/50 p-6 rounded-xl h-48 transition-all duration-500 shadow-2xl flex flex-col">
                                                
                                                <div class="flex items-center gap-4 mb-4">
                                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-white/10 to-white/5 border border-white/10"></div>
                                                    <div>
                                                        <div class="h-3 w-24 bg-white/20 rounded mb-2"></div>
                                                        <div class="h-2 w-16 bg-white/10 rounded"></div>
                                                    </div>
                                                </div>
                                                <div class="h-2 w-full bg-white/5 rounded mb-2"></div>
                                                <div class="h-2 w-2/3 bg-white/5 rounded"></div>

                                                <div id="preview-badge" class="bg-gradient-to-r from-red-500 to-pink-500 text-white text-[10px] font-bold px-3 py-1 rounded-full shadow-lg transition-all duration-500 w-fit mt-4 border border-white/20 tracking-wider">
                                                    NEW
                                                </div>

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
                        <div class="text-left"><div class="text-[10px] uppercase tracking-widest opacity-50">Sebelumnya</div><div class="font-bold text-sm">Grid Layout</div></div>
                    </a>
                    <div id="nextChapterBtn" class="group flex items-center gap-3 text-right text-slate-500 cursor-not-allowed">
                        <div class="text-right"><div class="text-[10px] uppercase tracking-widest opacity-30">Terkunci</div><div class="font-bold text-sm">Bab 3: Styling</div></div>
                        <div class="w-10 h-10 rounded-full border border-white/5 flex items-center justify-center">🔒</div>
                    </div>
                </div>

                <div class="mt-16 text-center text-white/20 text-xs font-mono">&copy; {{ date('Y') }} Flowwind Learn.</div>
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

    /* 3D CLASSES MANUAL (NO PLUGIN NEEDED) */
    .perspective-1000 { perspective: 1000px; }
    .transform-style-3d { transform-style: preserve-3d; }
    .group-hover\:translate-z-12:hover { transform: translateZ(20px) translateX(-20px) translateY(-20px); }
    .group-hover\:translate-z-24:hover { transform: translateZ(40px); }
    .group-hover\:translate-z-36:hover { transform: translateZ(60px) translateX(20px) translateY(20px); }
    .group-hover\:rotate-x-20:hover { transform: rotateX(20deg) rotateY(-20deg); }
</style>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    /* --- CONFIGURATION (SYNC WITH CONTROLLER) --- */
    window.LESSON_IDS = [41, 42, 43, 44, 45]; 
    window.COMPLETED_IDS = {!! json_encode($completedLessonIds ?? []) !!};
    let completedSet = new Set(window.COMPLETED_IDS);
    
    // Activity Status
    let activityCompleted = {!! ($activityCompleted ?? false) ? 'true' : 'false' !!};
    const ACTIVITY_ID = 9; // ID Activity di Database
    const ACTIVITY_LESSON_ID = 45; // ID Lesson Penutup

    document.addEventListener('DOMContentLoaded', () => {
        initScrollSpy();
        initLessonObserver();
        initVisualEffects();
        initSidebarScroll();
        updateProgressUI();
        
        if (activityCompleted) {
            lockActivityUI();
            unlockNext();
        }
    });

    /* --- 1. SIMULATORS (THEORY) --- */
    function resizeContainer(cls, btn) {
        const c = document.getElementById('demo-container');
        c.className = `h-40 bg-indigo-500/10 border border-indigo-500/50 rounded-lg flex flex-col items-center justify-center text-center p-4 transition-all duration-700 w-full mx-auto relative z-10 shadow-[0_0_50px_rgba(99,102,241,0.15)] backdrop-blur-sm ${cls}`;
        document.querySelectorAll('.res-btn').forEach(b => {
            b.className = "res-btn px-4 py-2 text-xs rounded bg-white/5 text-white/50 hover:bg-white/10 transition";
        });
        btn.className = "res-btn px-4 py-2 text-xs rounded bg-indigo-600 text-white shadow-lg transition active-btn";
    }

    function toggleFloat(dir) {
        const el = document.getElementById('float-box');
        el.className = `w-20 h-20 bg-gradient-to-br from-orange-500 to-red-600 rounded-lg mb-2 ${dir==='left'?'mr-4 float-left':'ml-4 float-right'} flex items-center justify-center text-white font-bold transition-all shadow-lg text-[10px] ring-4 ring-black/50`;
    }

    /* --- 2. ACTIVITY LOGIC (PRACTICE) --- */
    let config = { wrapper: '', parent: '', badge: '' };

    function selectOption(cat, val, btn) {
        if(activityCompleted) return;
        
        config[cat] = val;
        
        // Update Buttons Visual
        const buttons = document.querySelectorAll(`.opt-btn-${cat}`);
        buttons.forEach(b => {
            b.classList.remove('bg-indigo-600', 'text-white', 'border-indigo-500', 'shadow-lg');
            b.classList.add('bg-white/5', 'text-gray-400', 'border-white/5');
        });
        
        btn.classList.remove('bg-white/5', 'text-gray-400', 'border-white/5');
        btn.classList.add('bg-indigo-600', 'text-white', 'border-indigo-500', 'shadow-lg');
        
        updatePreview();
    }

    function updatePreview() {
        const wrapper = document.getElementById('preview-wrapper');
        const parent = document.getElementById('preview-parent');
        const badge = document.getElementById('preview-badge');
        
        // Apply Classes
        wrapper.className = `bg-white/5 border border-white/10 p-4 transition-all duration-500 w-full border-dashed ${config.wrapper}`;
        parent.className = `bg-[#0b0f19] border border-indigo-500/50 p-6 rounded-xl h-48 transition-all duration-500 shadow-2xl flex flex-col ${config.parent}`;
        badge.className = `bg-gradient-to-r from-red-500 to-pink-500 text-white text-[10px] font-bold px-3 py-1 rounded-full shadow-lg transition-all duration-500 w-fit border border-white/20 tracking-wider ${config.badge}`;
    }

    async function checkSolution() {
        if(activityCompleted) return;
        
        const btn = document.getElementById('submitBtn');
        const status = document.getElementById('status-text');
        
        btn.innerHTML = '<span class="animate-pulse">Memeriksa...</span>';
        btn.disabled = true;

        await new Promise(r => setTimeout(r, 1000)); // Dramatic Delay

        // Logic Benar: Container + Relative Parent + Absolute Badge
        const isCorrect = (config.wrapper.includes('container') && config.parent === 'relative' && config.badge.includes('absolute'));

        if(isCorrect) {
            status.innerText = "Konfigurasi Benar!";
            status.className = "text-[10px] text-green-400 font-mono font-bold";
            await saveActivityData();
        } else {
            status.innerText = "Masih salah. Coba perhatikan logika Relative vs Absolute.";
            status.className = "text-[10px] text-red-400 font-mono font-bold";
            btn.innerText = "Coba Lagi";
            btn.disabled = false;
            btn.classList.add('shake');
            setTimeout(() => btn.classList.remove('shake'), 500);
        }
    }

    async function saveActivityData() {
        const btn = document.getElementById('submitBtn');
        try {
            // 1. Save Activity
            await fetch('/activity/complete', { 
                method: 'POST', 
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'Content-Type': 'application/json' }, 
                body: JSON.stringify({ activity_id: ACTIVITY_ID, score: 100 }) 
            });
            
            // 2. Save Lesson Penutup
            await saveLessonToDB(ACTIVITY_LESSON_ID); 
            completedSet.add(ACTIVITY_LESSON_ID);
            
            // 3. Update UI
            activityCompleted = true;
            updateProgressUI();
            lockActivityUI();
            unlockNext();
            
        } catch(e) { 
            console.error(e); 
            btn.innerText = "Gagal Simpan"; 
            btn.disabled = false; 
        }
    }

    function lockActivityUI() {
        document.getElementById('lockOverlay').classList.remove('hidden');
        document.getElementById('status-text').innerText = "Tantangan Selesai!";
        
        const btn = document.getElementById('submitBtn');
        btn.innerText = "Terkunci & Selesai";
        btn.className = "px-6 py-2.5 rounded-lg bg-white/5 text-white/30 font-bold text-xs border border-white/10 cursor-not-allowed";
        btn.disabled = true;
        
        // Set Preview to Correct State Visuals
        document.getElementById('preview-wrapper').className = "bg-white/5 border border-white/10 p-4 transition-all duration-500 w-full border-dashed container mx-auto";
        document.getElementById('preview-parent').className = "bg-[#0b0f19] border border-indigo-500/50 p-6 rounded-xl h-48 transition-all duration-500 shadow-2xl flex flex-col relative";
        document.getElementById('preview-badge').className = "bg-gradient-to-r from-red-500 to-pink-500 text-white text-[10px] font-bold px-3 py-1 rounded-full shadow-lg transition-all duration-500 w-fit border border-white/20 tracking-wider absolute top-0 right-0 m-4";
        
        // Disable Options
        document.querySelectorAll('#layoutActivityForm button').forEach(b => b.disabled = true);
    }

    /* --- 3. SYSTEM FUNCTIONS --- */
    function updateProgressUI() {
        const total = window.LESSON_IDS.length;
        const done = window.LESSON_IDS.filter(id => completedSet.has(id)).length;
        const percent = Math.round((done / total) * 100);
        
        const bar = document.getElementById('topProgressBar');
        const label = document.getElementById('progressLabelTop');
        
        if(bar) bar.style.width = percent + '%';
        if(label) label.innerText = percent + '%';
        
        if(percent === 100) unlockNext();
    }

    function unlockNext() {
        const btn = document.getElementById('nextChapterBtn');
        if(btn) {
            btn.className = "group flex items-center gap-3 text-right text-indigo-400 hover:text-indigo-300 transition cursor-pointer";
            btn.innerHTML = `<div class="text-right"><div class="text-[10px] uppercase tracking-widest opacity-50">Selanjutnya</div><div class="font-bold text-sm">Bab 3: Styling</div></div><div class="w-10 h-10 rounded-full border border-indigo-500/30 bg-indigo-500/10 flex items-center justify-center group-hover:scale-110 transition">→</div>`;
            btn.onclick = () => window.location.href = "{{ route('dashboard') }}"; 
        }
    }

    function initLessonObserver() {
        const obs = new IntersectionObserver(async entries => {
            for (const entry of entries) {
                if (entry.isIntersecting) {
                    const id = Number(entry.target.dataset.lessonId);
                    const type = entry.target.dataset.type;
                    if (id && type !== 'activity' && !completedSet.has(id)) {
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
        await fetch('/lesson/complete', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: new URLSearchParams({ lesson_id: id }) });
    }

    // Sidebar ScrollSpy (HIGHLIGHT LOGIC)
    function initScrollSpy() {
        const mainScroll = document.getElementById('mainScroll');
        const sections = document.querySelectorAll('.lesson-section');
        const navLinks = document.querySelectorAll('.sidebar-nav-link');

        // Styles for Active State
        const activeDotClasses = ['bg-indigo-500', 'shadow-[0_0_8px_#6366f1]', 'scale-125'];
        const inactiveDotClasses = ['bg-slate-600', 'group-hover/item:bg-white'];
        const activeTextClasses = ['text-indigo-300', 'font-bold'];
        const inactiveTextClasses = ['text-slate-400', 'group-hover/item:text-white'];

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const id = '#' + entry.target.id;
                    
                    navLinks.forEach(link => {
                        const dot = link.querySelector('.dot-indicator');
                        const label = link.querySelector('.label');
                        if(!dot || !label) return;

                        // Reset
                        link.classList.remove('bg-white/5');
                        dot.classList.remove(...activeDotClasses); dot.classList.add(...inactiveDotClasses);
                        label.classList.remove(...activeTextClasses); label.classList.add(...inactiveTextClasses);

                        // Highlight
                        if (link.dataset.target === id) {
                            link.classList.add('bg-white/5');
                            dot.classList.remove(...inactiveDotClasses); dot.classList.add(...activeDotClasses);
                            label.classList.remove(...inactiveTextClasses); label.classList.add(...activeTextClasses);
                        }
                    });
                }
            });
        }, { root: mainScroll, rootMargin: '-20% 0px -60% 0px', threshold: 0 });

        sections.forEach(section => observer.observe(section));
    }

    function initSidebarScroll(){const m=document.getElementById('mainScroll');const l=document.querySelectorAll('.accordion-content .nav-item');m.addEventListener('scroll',()=>{let c='';document.querySelectorAll('.lesson-section').forEach(s=>{if(m.scrollTop>=s.offsetTop-250)c='#'+s.id;});l.forEach(k=>{k.classList.remove('active');if(k.getAttribute('data-target')===c)k.classList.add('active')})});l.forEach(k=>k.addEventListener('click',(e)=>{const t=document.querySelector(k.getAttribute('data-target'));if(t)m.scrollTo({top:t.offsetTop-120,behavior:'smooth'})}));}
    function initVisualEffects(){const c=document.getElementById('stars'),x=c.getContext('2d');function r(){c.width=innerWidth;c.height=innerHeight}r();window.onresize=r;let s=[];for(let i=0;i<100;i++)s.push({x:Math.random()*c.width,y:Math.random()*c.height,r:Math.random()*1.2,v:Math.random()*0.2+.1});(function a(){x.clearRect(0,0,c.width,c.height);x.fillStyle='rgba(255,255,255,.3)';s.forEach(t=>{x.beginPath();x.arc(t.x,t.y,t.r,0,6.28);x.fill();t.y+=t.v;if(t.y>c.height)t.y=0});requestAnimationFrame(a)})();$(window).on('mousemove',e=>{$('#cursor-glow').css({left:e.clientX,top:e.clientY})});}
    function toggleAccordion(id) {
        const el = document.getElementById(id);
        const group = el.closest('.accordion-group');
        const arrow = document.getElementById(id.replace('content', 'arrow'));
        if(el.style.maxHeight){ el.style.maxHeight=null; group.classList.remove('open'); if(arrow) arrow.style.transform='rotate(0deg)'; }
        else{ el.style.maxHeight=el.scrollHeight+"px"; group.classList.add('open'); if(arrow) arrow.style.transform='rotate(180deg)'; }
    }
</script>
@endsection 