@extends('layouts.landing')
@section('title','Bab 1.4 · Implementasi Utility Classes')

@section('content')
<div id="courseRoot" class="relative h-screen bg-[#020617] text-white font-sans overflow-hidden flex flex-col selection:bg-cyan-500/30">

    {{-- Background Effects --}}
    <div class="fixed inset-0 -z-50 pointer-events-none">
        <div id="animated-bg" class="absolute inset-0 opacity-20"></div>
        <div class="absolute top-[-20%] left-[-10%] w-[1200px] h-[1200px] bg-indigo-900/20 rounded-full blur-[150px] animate-pulse"></div>
        <div class="absolute bottom-[-20%] right-[-10%] w-[1000px] h-[1000px] bg-cyan-900/20 rounded-full blur-[100px]"></div>
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.03]"></div>
    </div>

    {{-- Navbar --}}
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
            
            {{-- Sticky Header --}}
            <div id="stickyHeader" class="sticky top-0 z-30 w-full bg-[#020617]/90 backdrop-blur-2xl border-b border-white/5 px-8 py-4 flex items-center justify-between transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-cyan-500/20 to-transparent border border-cyan-500/20 flex items-center justify-center font-bold text-xs text-cyan-400">1.4</div>
                    <div>
                        <h1 class="text-sm font-bold text-white">Implementasi Utility Classes</h1>
                        <p class="text-[10px] text-white/50">Core Concepts & Architecture</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="hidden sm:block w-32 h-1.5 bg-white/10 rounded-full overflow-hidden">
                        <div id="topProgressBar" class="h-full bg-gradient-to-r from-cyan-500 to-indigo-500 w-0 transition-all duration-500 shadow-[0_0_10px_#06b6d4]"></div>
                    </div>
                    <span id="progressLabelTop" class="text-cyan-400 font-bold text-xs">0%</span>
                </div>
            </div>

            <div class="p-6 lg:p-16 max-w-5xl mx-auto pb-40">
                
                {{-- Target Penguasaan Materi --}}
                <div class="mb-24">
                    <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                        Tujuan Pembelajaran
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-[#151515] border border-white/5 p-6 rounded-xl flex items-start gap-4 hover:border-cyan-500/30 transition group h-full">
                            <div class="w-10 h-10 rounded-lg bg-cyan-900/20 text-cyan-400 flex items-center justify-center shrink-0 font-bold text-sm border border-cyan-500/10">1</div>
                            <div>
                                <h4 class="text-sm font-bold text-white mb-2 group-hover:text-cyan-400 transition">Filosofi & Sintaks</h4>
                                <p class="text-xs text-white/50 leading-relaxed">
                                    Menguasai pola penamaan prediktif dan sistem skala desain (Constraint-Based) untuk menghindari nilai acak.
                                </p>
                            </div>
                        </div>

                        <div class="bg-[#151515] border border-white/5 p-6 rounded-xl flex items-start gap-4 hover:border-indigo-500/30 transition group h-full">
                            <div class="w-10 h-10 rounded-lg bg-indigo-900/20 text-indigo-400 flex items-center justify-center shrink-0 font-bold text-sm border border-indigo-500/10">2</div>
                            <div>
                                <h4 class="text-sm font-bold text-white mb-2 group-hover:text-indigo-400 transition">Komposisi & Interaksi</h4>
                                <p class="text-xs text-white/50 leading-relaxed">
                                    Membangun komponen kompleks dengan layering utilitas dan menerapkan <em>Locality of Behavior</em> (hover/focus/active).
                                </p>
                            </div>
                        </div>

                        <div class="bg-[#151515] border border-white/5 p-6 rounded-xl flex items-start gap-4 hover:border-fuchsia-500/30 transition group h-full">
                            <div class="w-10 h-10 rounded-lg bg-fuchsia-900/20 text-fuchsia-400 flex items-center justify-center shrink-0 font-bold text-sm border border-fuchsia-500/10">3</div>
                            <div>
                                <h4 class="text-sm font-bold text-white mb-2 group-hover:text-fuchsia-400 transition">Manajemen Kode (DRY)</h4>
                                <p class="text-xs text-white/50 leading-relaxed">
                                    Menangani duplikasi kode secara efisien menggunakan strategi Ekstraksi Komponen dibandingkan <code>@apply</code>.
                                </p>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-cyan-900/10 to-indigo-900/10 border border-cyan-500/20 p-6 rounded-xl flex items-start gap-4 hover:shadow-[0_0_30px_rgba(6,182,212,0.15)] transition group h-full col-span-1 md:col-span-3">
                            <div class="w-10 h-10 rounded-lg bg-white/10 text-white flex items-center justify-center shrink-0 font-bold text-lg border border-white/10">🏁</div>
                            <div>
                                <h4 class="text-sm font-bold text-white mb-2">Final Mission: System Architect</h4>
                                <p class="text-xs text-white/60 leading-relaxed max-w-2xl">
                                    Merakit sistem UI lengkap dengan memvalidasi keputusan arsitektur kode melalui simulasi diagnostik real-time tanpa bantuan.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <article class="space-y-40">
                    
                    {{-- LESSON 1.4.1 --}}
                    <section id="section-1" class="lesson-section scroll-mt-32" data-lesson-id="16">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-cyan-500 pl-6">
                                <span class="text-cyan-400 font-mono text-xs uppercase tracking-widest">Lesson 1.4.1</span>
                                <h2 class="text-4xl lg:text-5xl font-black text-white leading-[1.1]">
                                    Filosofi Utilitas & <br> 
                                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-indigo-500">Sistem Desain</span>
                                </h2>
                            </div>
                            
                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-white flex items-center gap-2"><span class="w-6 h-6 rounded bg-cyan-600 flex items-center justify-center text-[10px] text-white">A</span> Analogi: Bahan Mentah vs Makanan Jadi</h3>
                                <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed space-y-4">
                                    <p>
                                        Dalam buku <em>"Modern CSS with Tailwind"</em>, Noel Rappin menggunakan analogi yang sangat kuat: Framework tradisional (seperti Bootstrap) ibarat <strong>Restoran Cepat Saji</strong>. Anda memesan "Paket Tombol" (class <code>.btn</code>), dan koki memberikannya kepada Anda dalam bentuk yang sudah matang dengan gaya yang ditentukan koki. Ini cepat, tetapi sangat sulit untuk disesuaikan. Jika Anda ingin mengubah padding atau radius, Anda harus menulis CSS tambahan untuk menimpa (override) gaya bawaan tersebut.
                                    </p>
                                    <p>
                                        Tailwind CSS, di sisi lain, adalah <strong>Dapur dengan Bahan Siap Pakai</strong>. Tailwind tidak memberikan "Tombol Jadi". Ia menyediakan bahan mentah atomik: tepung (<code>bg-white</code>), telur (<code>rounded</code>), dan rempah-rempah (<code>shadow-lg</code>). Pendekatan ini dikenal sebagai <strong>Utility-First</strong>.
                                    </p>
                                    <p>
                                        Anda membangun desain dengan merakit bahan-bahan kecil ini langsung di dalam HTML. Ini memberikan kendali granular atas setiap piksel desain tanpa pernah meninggalkan file HTML Anda atau menulis satu baris pun CSS kustom. Mental model Anda bergeser dari "Mencari nama kelas yang tepat" menjadi "Mendesain langsung dengan properti".
                                    </p>
                                </div>
                            </div>

                            <div class="bg-[#151515] border border-white/10 rounded-xl overflow-hidden shadow-2xl p-8 relative">
                                <h4 class="text-xs font-bold text-white/50 uppercase mb-6 text-center">Simulator: The Syntax Decrypter</h4>
                                <div class="grid md:grid-cols-2 gap-8 items-center">
                                    <div class="space-y-4">
                                        <p class="text-xs text-white/40">Ketik utilitas Tailwind untuk melihat CSS aslinya (Regex Engine):</p>
                                        <input type="text" id="decoder-input" oninput="translateClass()" class="w-full bg-black/30 border border-white/10 rounded px-4 py-3 text-cyan-300 font-mono text-sm focus:border-cyan-500 outline-none transition" placeholder="Cth: text-center, mt-4, bg-blue-500">
                                        
                                        <div id="decoder-result" class="p-4 rounded bg-white/5 border border-white/5 min-h-[80px] flex flex-col justify-center font-mono">
                                            <span class="text-xs text-white/30 mb-1 block">// CSS Output:</span>
                                            <code class="text-cyan-400 font-bold text-sm block">Waiting for input...</code>
                                        </div>
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        <p class="text-[10px] uppercase font-bold text-white/30">Quick Presets:</p>
                                        <div class="flex flex-wrap gap-2">
                                            <button onclick="setTranslate('m-10')" class="px-3 py-1.5 bg-white/5 rounded border border-white/10 text-xs hover:bg-white/10 hover:text-white transition font-mono">m-10 (Margin)</button>
                                            <button onclick="setTranslate('text-3xl')" class="px-3 py-1.5 bg-white/5 rounded border border-white/10 text-xs hover:bg-white/10 hover:text-white transition font-mono">text-3xl (Size)</button>
                                            <button onclick="setTranslate('bg-indigo-600')" class="px-3 py-1.5 bg-white/5 rounded border border-white/10 text-xs hover:bg-white/10 hover:text-white transition font-mono">bg-indigo-600</button>
                                            <button onclick="setTranslate('flex')" class="px-3 py-1.5 bg-white/5 rounded border border-white/10 text-xs hover:bg-white/10 hover:text-white transition font-mono">flex (Layout)</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-4 pt-10">
                                <h3 class="text-xl font-bold text-white flex items-center gap-2"><span class="w-6 h-6 rounded bg-blue-600 flex items-center justify-center text-[10px] text-white">B</span> Prediktabilitas Pola Nama</h3>
                                <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed space-y-4">
                                    <p>
                                        Ketakutan terbesar pemula adalah: "Apakah saya harus menghafal ribuan nama kelas?". Jawabannya adalah tidak. Anda hanya perlu memahami <strong>Pola Bahasa</strong>-nya. Tailwind dirancang dengan sintaks yang sangat prediktif: <code>{Properti}-{Nilai}</code>.
                                    </p>
                                    <p>
                                        Jika Anda tahu properti CSS <code>text-align: center</code>, di Tailwind menjadi <code>text-center</code>. Jika CSS-nya <code>display: flex</code>, di Tailwind menjadi <code>flex</code>. Jika CSS-nya <code>font-weight: 700</code>, di Tailwind menjadi <code>font-bold</code>.
                                    </p>
                                    <p>
                                        Setelah Anda memahami pola ini, Anda bisa "menebak" nama kelas tanpa membuka dokumentasi. Ini menciptakan alur kerja "Flow State" di mana Anda mendesain secepat Anda berpikir.
                                    </p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-white flex items-center gap-2"><span class="w-6 h-6 rounded bg-indigo-600 flex items-center justify-center text-[10px] text-white">C</span> Sistem Skala (Constraint-Based Design)</h3>
                                <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed space-y-4">
                                    <p>
                                        Masalah klasik CSS murni adalah "Magic Numbers". Pengembang A menggunakan margin <code>13px</code>, sementara Pengembang B menggunakan <code>15px</code> di tempat lain. Hasilnya adalah antarmuka yang terasa tidak rapi. Tailwind memecahkan ini dengan menerapkan <strong>Constraint-Based Design</strong> (Desain Berbasis Batasan).
                                    </p>
                                    <p>
                                        Tailwind menggunakan skala spasi berbasis <strong>4px (0.25rem)</strong>.
                                        <br>• Angka <code>1</code> = 4px.
                                        <br>• Angka <code>4</code> = 16px (1rem).
                                        <br>• Angka <code>8</code> = 32px.
                                        <br>Anda dipaksa untuk memilih nilai dari skala ini, yang secara otomatis menciptakan ritme visual yang harmonis di seluruh aplikasi.
                                    </p>
                                    <p>
                                        Demikian pula dengan warna, yang menggunakan skala 50-950 (Terang ke Gelap). Ini menjamin konsistensi visual di seluruh aplikasi, bahkan jika dikerjakan oleh tim yang berbeda.
                                    </p>
                                </div>
                            </div>

                            <div class="bg-[#151515] border border-white/10 rounded-xl overflow-hidden shadow-2xl p-8 relative">
                                <h4 class="text-xs font-bold text-white/50 uppercase mb-6 text-center">Simulator: The Scale Slider (4px Grid)</h4>
                                <div class="flex flex-col md:flex-row items-center gap-10">
                                    <div class="w-full md:w-1/2 space-y-6">
                                        <p class="text-xs text-white/40">Geser untuk melihat bagaimana angka skala Tailwind diterjemahkan menjadi piksel.</p>
                                        <div class="space-y-2">
                                            <div class="flex justify-between text-xs text-cyan-400 font-mono">
                                                <span>CLASS: PADDING (P-{N})</span>
                                                <span id="scale-p-val">p-6</span>
                                            </div>
                                            <input type="range" min="0" max="16" step="1" value="6" class="w-full accent-cyan-500" oninput="updateScale('p', this.value)">
                                        </div>
                                        <div class="p-4 bg-white/5 rounded border border-white/10">
                                            <p class="text-xs font-mono text-white/60">Formula: <span id="scale-formula" class="text-white font-bold">6 x 4px = 24px (1.5rem)</span></p>
                                        </div>
                                    </div>
                                    <div class="w-full md:w-1/2 flex items-center justify-center h-48 bg-[#0b0f19] rounded-xl border border-white/5 relative overflow-hidden">
                                        <div id="scale-target" class="bg-indigo-600 text-white font-bold rounded-lg shadow-lg transition-all duration-300 transform border border-white/20">
                                            Dynamic Box
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 1.4.2 --}}
                    <section id="section-2" class="lesson-section scroll-mt-32" data-lesson-id="17">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-cyan-500 pl-6">
                                <span class="text-cyan-400 font-mono text-xs uppercase tracking-widest">Lesson 1.4.2</span>
                                <h2 class="text-4xl lg:text-5xl font-black text-white leading-[1.1]">
                                    Komposisi & <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-indigo-500">Interaktivitas</span>
                                </h2>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-white flex items-center gap-2"><span class="w-6 h-6 rounded bg-cyan-600 flex items-center justify-center text-[10px] text-white">A</span> Seni Komposisi (Layering)</h3>
                                <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed space-y-4">
                                    <p>
                                        Kekuatan sesungguhnya dari Tailwind muncul ketika Anda menggabungkan (chaining) banyak kelas utilitas kecil menjadi satu komponen kompleks. Ini seperti menyusun balok Lego; satu balok mungkin sederhana, tetapi gabungan 20 balok membentuk struktur yang kokoh dan fungsional.
                                    </p>
                                    <p>
                                        Contohnya, sebuah tombol modern dibangun dengan lapisan:
                                        <br>1. <strong>Layout:</strong> <code>flex items-center</code> (Struktur).
                                        <br>2. <strong>Spacing:</strong> <code>p-6 gap-4</code> (Ruang napas).
                                        <br>3. <strong>Skin:</strong> <code>bg-slate-800 text-white</code> (Tampilan).
                                        <br>4. <strong>Effects:</strong> <code>shadow-xl rounded-2xl</code> (Sentuhan akhir).
                                    </p>
                                    <p>
                                        Penting: Urutan penulisan kelas di HTML <strong>tidak mempengaruhi</strong> hasil akhir. <code>class="p-4 bg-red-500"</code> sama persis dengan <code>class="bg-red-500 p-4"</code>. Prioritas gaya diatur oleh sistem internal Tailwind (CSS Cascade layers).
                                    </p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-white flex items-center gap-2"><span class="w-6 h-6 rounded bg-blue-600 flex items-center justify-center text-[10px] text-white">B</span> Locality of Behavior (States)</h3>
                                <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed space-y-4">
                                    <p>
                                        Konsep <strong>Locality of Behavior</strong> (LoB) adalah filosofi kunci dalam buku-buku Tailwind. Idenya adalah: "Perilaku sebuah unit kode harus sedekat mungkin dengan definisinya". Dalam CSS tradisional, logika hover sering terpisah jauh di file CSS lain.
                                    </p>
                                    <p>
                                        Tailwind membawa logika ini langsung ke elemen HTML menggunakan <strong>Modifier Prefixes</strong>.
                                        <br>• <code>hover:bg-blue-700</code>: Ubah background hanya saat kursor melintas.
                                        <br>• <code>focus:ring-2</code>: Tambahkan cincin fokus saat elemen diklik.
                                        <br>• <code>active:scale-95</code>: Perkecil ukuran saat elemen ditekan.
                                    </p>
                                    <p>
                                        Dengan pendekatan ini, Anda bisa melihat sebuah elemen HTML dan langsung memahami bagaimana ia bereaksi terhadap interaksi pengguna, tanpa perlu membuka file lain.
                                    </p>
                                </div>
                            </div>

                            <div class="bg-[#151515] border border-white/10 rounded-xl overflow-hidden shadow-2xl p-8 relative">
                                <h4 class="text-xs font-bold text-white/50 uppercase mb-6 text-center">Simulator: Interaction Holodeck</h4>
                                <div class="flex flex-col md:flex-row items-center gap-10">
                                    <div class="w-full md:w-1/2 space-y-3">
                                        <p class="text-xs text-white/40 mb-2">Inject logika interaksi ke tombol target:</p>
                                        <label class="flex items-center justify-between p-3 rounded bg-white/5 border border-white/10 cursor-pointer hover:bg-white/10 transition group">
                                            <span class="text-xs font-mono text-cyan-300">hover:scale-110</span>
                                            <input type="checkbox" onchange="toggleStateClass('hover')" class="accent-cyan-500 w-4 h-4">
                                        </label>
                                        <label class="flex items-center justify-between p-3 rounded bg-white/5 border border-white/10 cursor-pointer hover:bg-white/10 transition group">
                                            <span class="text-xs font-mono text-fuchsia-300">active:bg-fuchsia-600</span>
                                            <input type="checkbox" onchange="toggleStateClass('active')" class="accent-fuchsia-500 w-4 h-4">
                                        </label>
                                        <label class="flex items-center justify-between p-3 rounded bg-white/5 border border-white/10 cursor-pointer hover:bg-white/10 transition group">
                                            <span class="text-xs font-mono text-yellow-300">focus:ring-4</span>
                                            <input type="checkbox" onchange="toggleStateClass('focus')" class="accent-yellow-500 w-4 h-4">
                                        </label>
                                    </div>
                                    <div class="w-full md:w-1/2 flex items-center justify-center h-48 bg-[#0b0f19] rounded-xl border border-white/5 relative group">
                                        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20"></div>
                                        <button id="state-target" class="bg-indigo-600 text-white font-bold py-3 px-8 rounded-lg transition-all duration-300 outline-none shadow-xl relative z-10">
                                            Interact With Me
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-4 pt-10">
                                <h3 class="text-xl font-bold text-white flex items-center gap-2"><span class="w-6 h-6 rounded bg-indigo-600 flex items-center justify-center text-[10px] text-white">C</span> Responsif (Mobile-First)</h3>
                                <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed space-y-4">
                                    <p>
                                        Tailwind dibangun dengan pendekatan <strong>Mobile-First</strong>. Artinya, gaya dasar yang Anda tulis tanpa prefix akan berlaku untuk layar terkecil (ponsel). Ini membalikkan kebiasaan lama "Desktop-First".
                                    </p>
                                    <p>
                                        Untuk mengubah gaya pada layar yang lebih besar, Anda menggunakan prefix breakpoint:
                                        <br>• <code>md:</code> (Tablet & ke atas).
                                        <br>• <code>lg:</code> (Laptop & ke atas).
                                        <br>• <code>xl:</code> (Desktop besar).
                                    </p>
                                    <p>
                                        Contoh: <code>class="w-full md:w-1/2"</code>. Kode ini dibaca: "Secara default (di HP) lebarnya 100%, TAPI jika di layar Medium ke atas, lebarnya menjadi 50%".
                                    </p>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 1.4.3 --}}
                    <section id="section-3" class="lesson-section scroll-mt-32" data-lesson-id="18">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-cyan-500 pl-6">
                                <span class="text-cyan-400 font-mono text-xs uppercase tracking-widest">Lesson 1.4.3</span>
                                <h2 class="text-4xl lg:text-5xl font-black text-white leading-[1.1]">
                                    Arsitektur <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-indigo-500">Kode & Duplikasi</span>
                                </h2>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-white flex items-center gap-2"><span class="w-6 h-6 rounded bg-cyan-600 flex items-center justify-center text-[10px] text-white">A</span> Mitos "Kode Kotor" (The Ugly HTML)</h3>
                                <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed space-y-4">
                                    <p>
                                        Kritik yang paling sering terdengar tentang Tailwind adalah: "HTML saya terlihat kotor dan berantakan!". Memang benar, elemen dengan 20 kelas utilitas terlihat menakutkan pada awalnya. Contoh: <code>class="text-lg font-bold p-4 bg-white shadow-lg rounded-xl flex items-center gap-4..."</code>.
                                    </p>
                                    <p>
                                        Namun, buku <em>"Tailwind CSS"</em> oleh Ivaylo Gerchev menjelaskan bahwa ini adalah pertukaran (trade-off) yang sangat menguntungkan. Anda menukar "HTML bersih tetapi CSS terpisah yang sulit dilacak" dengan "HTML eksplisit yang mudah dipahami". Masalah sebenarnya bukanlah panjangnya kelas, melainkan <strong>duplikasi logika</strong>.
                                    </p>
                                    <p>
                                        Jika Anda menyalin string kelas panjang itu ke 10 tombol yang berbeda di halaman yang berbeda, Anda melanggar prinsip <strong>DRY (Don't Repeat Yourself)</strong>. Jika Anda ingin mengubah warna tombol dari biru ke merah, Anda harus mengedit 10 file satu per satu. Inilah masalah arsitektur yang sebenarnya.
                                    </p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-white flex items-center gap-2"><span class="w-6 h-6 rounded bg-blue-600 flex items-center justify-center text-[10px] text-white">B</span> Solusi: Ekstraksi Komponen</h3>
                                <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed space-y-4">
                                    <p>
                                        Solusi utama yang sangat disarankan oleh komunitas dan penulis buku Tailwind adalah menggunakan <strong>Ekstraksi Komponen</strong>. Jangan membuat kelas CSS baru (seperti <code>.btn</code>). Sebaliknya, buatlah komponen UI menggunakan fitur template framework Anda (seperti Blade Components di Laravel, React Component, atau Vue Component).
                                    </p>
                                    <p>
                                        Misalnya, Anda membuat komponen <code>&lt;x-button&gt;</code>. Di dalam file definisi komponen tersebut, Anda menulis deretan kelas Tailwind yang panjang itu <strong>hanya satu kali</strong>. Di halaman lain, Anda cukup memanggil tag <code>&lt;x-button&gt;</code>.
                                    </p>
                                    <p>
                                        Ini memberikan yang terbaik dari kedua dunia: Anda tetap menggunakan utilitas (tanpa CSS kustom), tetapi kode HTML Anda di halaman utama tetap bersih, semantik, dan terpusat (Single Source of Truth).
                                    </p>
                                </div>
                            </div>

                            <div class="bg-[#151515] border border-white/10 rounded-xl overflow-hidden shadow-2xl p-8 relative">
                                <h4 class="text-xs font-bold text-white/50 uppercase mb-6 text-center">Simulator: The Architecture Morph</h4>
                                <div class="grid md:grid-cols-2 gap-0 relative rounded-lg overflow-hidden border border-white/10 h-48 bg-black/20">
                                    
                                    <div class="p-6 flex flex-col items-center justify-center gap-2 border-r border-white/10 transition-all duration-700" id="morph-messy">
                                        <div class="text-[10px] text-red-400 font-mono bg-red-900/10 px-2 py-1 rounded border border-red-500/20 w-full text-center opacity-100 transition-opacity duration-300">&lt;btn class="p-4 bg-blue..."&gt;</div>
                                        <div class="text-[10px] text-red-400 font-mono bg-red-900/10 px-2 py-1 rounded border border-red-500/20 w-full text-center opacity-100 transition-opacity duration-300 delay-100">&lt;btn class="p-4 bg-blue..."&gt;</div>
                                        <div class="text-[10px] text-red-400 font-mono bg-red-900/10 px-2 py-1 rounded border border-red-500/20 w-full text-center opacity-100 transition-opacity duration-300 delay-200">&lt;btn class="p-4 bg-blue..."&gt;</div>
                                    </div>

                                    <div class="p-6 flex flex-col items-center justify-center gap-2 transition-all duration-700 relative">
                                        <div id="morph-clean" class="absolute inset-0 flex flex-col items-center justify-center gap-2 opacity-0 scale-90 transition-all duration-500">
                                            <div class="text-[10px] text-emerald-400 font-mono bg-emerald-900/10 px-4 py-2 rounded border border-emerald-500/20 w-3/4 text-center">&lt;x-button /&gt;</div>
                                            <div class="text-[10px] text-emerald-400 font-mono bg-emerald-900/10 px-4 py-2 rounded border border-emerald-500/20 w-3/4 text-center">&lt;x-button /&gt;</div>
                                            <div class="text-[10px] text-emerald-400 font-mono bg-emerald-900/10 px-4 py-2 rounded border border-emerald-500/20 w-3/4 text-center">&lt;x-button /&gt;</div>
                                        </div>
                                    </div>

                                    <button onclick="triggerMorph()" class="absolute bottom-4 right-4 px-4 py-2 bg-indigo-600 text-white text-[10px] font-bold rounded shadow-lg hover:bg-indigo-500 transition z-10">
                                        ✨ Refactor
                                    </button>
                                </div>
                            </div>

                            <div class="space-y-4 pt-10">
                                <h3 class="text-xl font-bold text-white flex items-center gap-2"><span class="w-6 h-6 rounded bg-indigo-600 flex items-center justify-center text-[10px] text-white">C</span> Solusi: @apply (Gunakan dengan Hati-Hati)</h3>
                                <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed space-y-4">
                                    <p>
                                        Jika Anda tidak bisa menggunakan komponen (misalnya pada proyek HTML statis sederhana), Tailwind menyediakan direktif <code>@apply</code>. Fitur ini memungkinkan Anda untuk membungkus sekumpulan utilitas menjadi kelas CSS kustom.
                                    </p>
                                    <p>
                                        Contoh: Di file CSS, Anda bisa menulis:
                                        <br><code>.btn { @apply font-bold py-2 px-4 rounded bg-blue-500 text-white; }</code>.
                                    </p>
                                    <p>
                                        Namun, buku memperingatkan untuk menggunakan ini sebagai <strong>jalan terakhir</strong>. Terlalu banyak menggunakan <code>@apply</code> akan membuat file CSS Anda membengkak kembali, menciptakan dependensi nama kelas yang sulit dilacak, dan menghilangkan fleksibilitas utility-first. Gunakan hanya jika benar-benar diperlukan.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- ACTIVITY SECTION --}}
                    <section id="section-4" class="lesson-section scroll-mt-32 pt-10 border-t border-white/10" data-lesson-id="19" data-type="activity">
                        <div class="relative rounded-[2.5rem] bg-[#0b0f19] border border-white/10 p-8 overflow-hidden shadow-2xl group hover:border-cyan-500/30 transition-all duration-500">
                            
                            <div class="flex flex-col md:flex-row items-start md:items-center gap-6 mb-8 relative z-10">
                                <div class="p-4 bg-gradient-to-br from-cyan-600 to-indigo-800 rounded-2xl text-white shadow-lg shadow-cyan-500/20 shrink-0">
                                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                </div>
                                <div>
                                    <div class="flex items-center gap-3 mb-1">
                                        <h2 class="text-3xl font-black text-white tracking-tight">Mission: System Assembler</h2>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-cyan-500/20 text-cyan-400 border border-cyan-500/30 uppercase tracking-wider">Expert Mode</span>
                                    </div>
                                    <p class="text-cyan-200/60 text-sm leading-relaxed max-w-2xl">
                                        Rakit komponen <strong>"Feature Card"</strong> yang optimal. Perhatikan panel <strong>System Diagnostic</strong> di kiri bawah untuk feedback real-time tentang pilihan Anda.
                                        <br><strong>Tugas:</strong> Pilih kombinasi Container, Content, dan Action yang memenuhi standar sistem desain.
                                    </p>
                                </div>
                            </div>

                            <div class="grid lg:grid-cols-2 gap-0 border border-white/10 rounded-2xl overflow-hidden h-[750px] shadow-2xl">
                                
                                <div class="bg-[#151515] p-8 border-r border-white/10 overflow-y-auto relative custom-scrollbar flex flex-col">
                                    
                                    <div class="flex-1 space-y-8">
                                        
                                        <div id="mod-1-container">
                                            <h4 class="text-xs font-bold text-white/50 uppercase mb-3">1. Container Module</h4>
                                            <div class="grid grid-cols-1 gap-2">
                                                <button onclick="assemble(1, 'A', this)" class="module-btn w-full text-left p-4 rounded bg-white/5 border border-white/10 hover:border-cyan-500/30 font-mono text-[11px] text-gray-400 transition hover:bg-white/10" data-choice="A">
                                                    bg-slate-800 rounded-2xl p-8 border border-white/5
                                                </button>
                                                <button onclick="assemble(1, 'B', this)" class="module-btn w-full text-left p-4 rounded bg-white/5 border border-white/10 hover:border-cyan-500/30 font-mono text-[11px] text-gray-400 transition hover:bg-white/10" data-choice="B">
                                                    bg-red-900 border-4 border-yellow-500 margin-20
                                                </button>
                                            </div>
                                        </div>

                                        <div id="mod-2-container">
                                            <h4 class="text-xs font-bold text-white/50 uppercase mb-3">2. Content Module</h4>
                                            <div class="grid grid-cols-1 gap-2">
                                                <button onclick="assemble(2, 'A', this)" class="module-btn w-full text-left p-4 rounded bg-white/5 border border-white/10 hover:border-cyan-500/30 font-mono text-[11px] text-gray-400 transition hover:bg-white/10" data-choice="A">
                                                    flex flex-col gap-4 text-center items-center
                                                </button>
                                                <button onclick="assemble(2, 'B', this)" class="module-btn w-full text-left p-4 rounded bg-white/5 border border-white/10 hover:border-cyan-500/30 font-mono text-[11px] text-gray-400 transition hover:bg-white/10" data-choice="B">
                                                    block align-middle font-huge
                                                </button>
                                            </div>
                                        </div>

                                        <div id="mod-3-container">
                                            <h4 class="text-xs font-bold text-white/50 uppercase mb-3">3. Action Module</h4>
                                            <div class="grid grid-cols-1 gap-2">
                                                <button onclick="assemble(3, 'A', this)" class="module-btn w-full text-left p-4 rounded bg-white/5 border border-white/10 hover:border-cyan-500/30 font-mono text-[11px] text-gray-400 transition hover:bg-white/10" data-choice="A">
                                                    bg-cyan-600 hover:bg-cyan-500 text-white w-full py-3 rounded-lg
                                                </button>
                                                <button onclick="assemble(3, 'B', this)" class="module-btn w-full text-left p-4 rounded bg-white/5 border border-white/10 hover:border-cyan-500/30 font-mono text-[11px] text-gray-400 transition hover:bg-white/10" data-choice="B">
                                                    bg-cyan-600 cursor-pointer on-hover-light
                                                </button>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="mt-8 p-4 rounded-lg bg-black/40 border border-white/10">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-[10px] font-bold text-cyan-400 uppercase tracking-widest">System Diagnostic</span>
                                            <div class="flex gap-1">
                                                <div class="w-1.5 h-1.5 rounded-full bg-red-500/20" id="diag-light-1"></div>
                                                <div class="w-1.5 h-1.5 rounded-full bg-red-500/20" id="diag-light-2"></div>
                                                <div class="w-1.5 h-1.5 rounded-full bg-red-500/20" id="diag-light-3"></div>
                                            </div>
                                        </div>
                                        <div id="diagnostic-text" class="text-[10px] text-white/40 font-mono h-12 leading-relaxed">
                                            > WAITING FOR INPUT STREAM...<br>
                                            > STANDBY...
                                        </div>
                                    </div>

                                </div>

                                <div class="bg-[#020617] flex flex-col relative overflow-hidden items-center justify-center p-8 border-l border-white/5">
                                    <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-10"></div>
                                    
                                    <div id="render-zone" class="transition-all duration-300 w-full max-w-sm">
                                        <div class="w-full h-64 border-2 border-dashed border-white/10 rounded-xl flex items-center justify-center text-white/20 text-xs font-mono">
                                            [ VISUAL RENDER OFFLINE ]
                                        </div>
                                    </div>

                                    <div class="absolute bottom-8 w-full px-8">
                                        <button id="btn-deploy" onclick="deploySystem()" class="w-full py-4 rounded-xl bg-gradient-to-r from-cyan-600 to-indigo-600 text-white font-bold text-xs shadow-lg shadow-cyan-500/20 hover:scale-[1.02] transition flex items-center justify-center gap-2 border border-white/10">
                                            <span>INITIATE DEPLOYMENT</span>
                                        </button>
                                    </div>

                                    <div id="deploy-overlay" class="hidden absolute inset-0 bg-[#020617]/95 z-50 flex flex-col items-center justify-center text-center p-8 backdrop-blur-xl">
                                        <div id="deploy-icon" class="w-16 h-16 rounded-full flex items-center justify-center mb-4 transition-colors"></div>
                                        <h3 id="deploy-title" class="text-2xl font-bold text-white mb-2"></h3>
                                        <p id="deploy-msg" class="text-sm text-white/60 mb-6 max-w-xs"></p>
                                        <button id="deploy-action" class="text-cyan-400 hover:text-white transition text-xs uppercase tracking-widest font-bold"></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                </article>

                {{-- Footer Navigation --}}
                <div class="mt-32 pt-10 border-t border-white/10 flex justify-between items-center">
                    <a href="{{ route('courses.latarbelakang') }}" class="group flex items-center gap-3 text-slate-400 hover:text-white transition">
                        <div class="w-10 h-10 rounded-full border border-white/10 flex items-center justify-center group-hover:border-white/30 transition">←</div>
                        <div class="text-left"><div class="text-[10px] uppercase tracking-widest opacity-50">Sebelumnya</div><div class="font-bold text-sm">Latar Belakang</div></div>
                    </a>
                    
                    {{-- Tombol Next Chapter --}}
                    <div id="nextChapterBtn" class="group flex items-center gap-3 text-right text-slate-500 cursor-not-allowed opacity-50 pointer-events-none transition-all duration-500">
                        <div class="text-right">
                            <div id="nextLabel" class="text-[10px] uppercase tracking-widest opacity-50">Terkunci</div>
                            <div class="font-bold text-sm">Keunggulan Tailwind CSS</div>
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
    .nav-link.active::after { content: ''; position: absolute; left: 0; bottom: -6px; width: 100%; height: 2px; background: linear-gradient(to right,#22d3ee,#0ea5e9); box-shadow: 0 0 12px rgba(34,211,238,.8); border-radius: 2px; }
    .custom-scrollbar::-webkit-scrollbar { width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
    #animated-bg{ background: radial-gradient(800px circle at 20% 20%, rgba(34,211,238,.15), transparent 40%), radial-gradient(800px circle at 80% 80%, rgba(14,165,233,.15), transparent 40%); animation:bgMove 20s ease-in-out infinite alternate; }
    @keyframes bgMove{to{transform:scale(1.15)}}
    @keyframes shake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-5px); } 75% { transform: translateX(5px); } }
    .shake { animation: shake 0.4s ease-in-out; }
    .nav-item.active { color: #22d3ee; background: rgba(34,211,238,0.05); font-weight: 600; }
    .nav-item.active .dot { background: #22d3ee; box-shadow: 0 0 8px #22d3ee; transform: scale(1.2); }
</style>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
    /* --- CONFIGURATION --- */
    window.LESSON_IDS = [16, 17, 18, 19]; 
    window.COMPLETED_IDS = {!! json_encode($completedLessonIds ?? []) !!};
    let completedSet = new Set(window.COMPLETED_IDS);
    
    // Activity ID 4 = System Assembler
    let activityCompleted = {!! ($activityCompleted ?? false) ? 'true' : 'false' !!};
    const ACTIVITY_ID = 4; 
    const ACTIVITY_LESSON_ID = 19; 

    document.addEventListener('DOMContentLoaded', () => {
        initScrollSpy();
        initLessonObserver();
        initSidebarScroll();
        updateProgressUI();
        
        // Init Sims
        setTranslate('text-center');
        updateScale('p', 6);
        
        // --- LOGIKA UTAMA: PENGARSIPAN AKTIVITAS ---
        if (activityCompleted) { 
            unlockNextChapter(); 
            lockActivityUI(); // <-- Memanggil fungsi pengunci aktivitas
        }
    });

    /* --- SIMULATOR 1: SYNTAX TRANSLATOR --- */
    function translateClass() {
        const input = document.getElementById('decoder-input').value.trim();
        const res = document.getElementById('decoder-result');
        let css = '';

        if (!input) css = '<span class="text-white/20 italic">// Menunggu input...</span>';
        else {
            if (input.match(/^text-(center|left|right)$/)) css = `text-align: ${input.split('-')[1]};`;
            else if (input.match(/^(m|p)[trblxy]?-\d+$/)) css = `${input.startsWith('m')?'margin':'padding'}: ${input.split('-').pop() * 0.25}rem; /* ${input.split('-').pop()*4}px */`;
            else if (input.match(/^bg-[a-z]+-\d{3}$/)) css = `background-color: [Palette ${input.split('-')[1]}];`;
            else if (input === 'flex') css = 'display: flex;';
            else if (input.startsWith('text-')) css = `font-size: [Scale ${input.split('-')[1]}];`;
            else css = `/* Style untuk .${input} */`;
            res.innerHTML = `<span class="text-xs text-white/30 font-mono mb-1">CSS Equivalent:</span><code class="text-cyan-400 font-bold text-sm block">${css}</code>`;
        }
    }
    function setTranslate(val) { document.getElementById('decoder-input').value = val; translateClass(); }

    /* --- SIMULATOR 2: SCALE SLIDER --- */
    function updateScale(type, val) {
        const formula = document.getElementById('scale-formula');
        const target = document.getElementById('scale-target');
        const label = document.getElementById('scale-p-val');
        
        const px = val * 4;
        const rem = val * 0.25;
        
        formula.innerHTML = `${val} x 4px = <span class="text-cyan-400">${px}px</span> (${rem}rem)`;
        label.innerText = `p-${val}`;
        target.style.padding = `${px}px`;
    }

    /* --- SIMULATOR 3: INTERACTION HOLODECK --- */
    let activeStates = new Set();
    function toggleStateClass(state) {
        const btn = document.getElementById('state-target');
        if(activeStates.has(state)) {
            activeStates.delete(state);
            if(state === 'hover') { btn.onmouseenter = null; btn.onmouseleave = null; btn.classList.remove('scale-110'); }
            if(state === 'active') { btn.onmousedown = null; btn.onmouseup = null; btn.classList.remove('bg-fuchsia-800'); }
            if(state === 'focus') btn.classList.remove('ring-4', 'ring-yellow-500/50');
        } else {
            activeStates.add(state);
            if(state === 'hover') { btn.onmouseenter = () => btn.classList.add('scale-110'); btn.onmouseleave = () => btn.classList.remove('scale-110'); }
            if(state === 'active') { btn.onmousedown = () => btn.classList.add('bg-fuchsia-800'); btn.onmouseup = () => btn.classList.remove('bg-fuchsia-800'); }
            if(state === 'focus') btn.classList.add('ring-4', 'ring-yellow-500/50');
        }
    }

    /* --- SIMULATOR 4: REFACTOR MORPH --- */
    function triggerMorph() {
        const messy = document.getElementById('morph-messy');
        const clean = document.getElementById('morph-clean');
        messy.style.opacity = '0'; messy.style.transform = 'translateY(-20px)';
        setTimeout(() => { clean.classList.remove('opacity-0', 'scale-90', 'pointer-events-none'); }, 400);
    }

    /* --- FINAL ACTIVITY: SYSTEM ASSEMBLER --- */
    let asmState = { 1: null, 2: null, 3: null };

    function assemble(module, choice, btn) {
        // Cegah interaksi jika aktivitas sudah selesai
        if (activityCompleted) return;

        asmState[module] = choice;
        
        // UI State
        const parent = btn.parentElement;
        parent.querySelectorAll('.module-btn').forEach(b => {
            b.classList.remove('bg-cyan-500/20', 'border-cyan-500', 'text-cyan-300');
            b.classList.add('bg-white/5', 'text-gray-400');
        });
        btn.classList.remove('bg-white/5', 'text-gray-400');
        btn.classList.add('bg-cyan-500/20', 'border-cyan-500', 'text-cyan-300');

        // Update Diagnostic
        updateDiagnostic(module, choice);

        // Live Render
        renderAssembly();
    }

    function updateDiagnostic(module, choice) {
        const diag = document.getElementById('diagnostic-text');
        const lights = [document.getElementById('diag-light-1'), document.getElementById('diag-light-2'), document.getElementById('diag-light-3')];
        
        let msg = "";
        let color = "text-white/40";

        if (choice === 'A') {
            msg = `> MODULE ${module} [OK]: Optimization verified.\n> Standard compliant structure detected.`;
            color = "text-emerald-400";
            lights[module-1].className = "w-1.5 h-1.5 rounded-full bg-emerald-500 shadow-[0_0_5px_#10b981]";
        } else {
            msg = `> MODULE ${module} [WARN]: Anomalous pattern detected.\n> Suggest checking documentation references.`;
            color = "text-red-400";
            lights[module-1].className = "w-1.5 h-1.5 rounded-full bg-red-500 shadow-[0_0_5px_#ef4444]";
        }

        diag.innerHTML = msg;
        diag.className = `text-[10px] font-mono h-12 leading-relaxed ${color}`;
    }

    function renderAssembly() {
        const zone = document.getElementById('render-zone');
        
        // Visual Logic based on Choices
        let container = asmState[1] === 'A' ? "bg-slate-800 rounded-2xl p-8 border border-white/10 w-full" : (asmState[1] === 'B' ? "bg-red-900 border-4 border-yellow-500 p-20 w-full" : "w-full border-2 border-dashed border-white/10 rounded-xl p-6");
        let layout = asmState[2] === 'A' ? "flex flex-col gap-4 text-center items-center" : (asmState[2] === 'B' ? "block" : "");
        let btn = asmState[3] === 'A' ? "bg-cyan-600 hover:bg-cyan-500 text-white w-full py-3 rounded-lg transition-colors" : (asmState[3] === 'B' ? "bg-cyan-600 cursor-pointer p-4 text-black" : "");

        // Only render content if at least container is picked
        if (asmState[1]) {
            zone.className = `transition-all duration-500 max-w-sm ${container}`;
            zone.innerHTML = `
                <div class="${layout}">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-tr from-cyan-400 to-indigo-500 shadow-lg mb-2"></div>
                    <div>
                        <h3 class="text-xl font-bold text-white">Feature Unlock</h3>
                        <p class="text-slate-400 text-sm mt-1">Upgrade system capabilities.</p>
                    </div>
                    ${asmState[3] ? `<button class="${btn}">Activate Now</button>` : ''}
                </div>
            `;
        }
    }

    function deploySystem() {
        // Cegah klik jika aktivitas sudah selesai
        if (activityCompleted) return;

        const overlay = document.getElementById('deploy-overlay');
        const icon = document.getElementById('deploy-icon');
        const title = document.getElementById('deploy-title');
        const msg = document.getElementById('deploy-msg');
        const action = document.getElementById('deploy-action');

        overlay.classList.remove('hidden');

        // Logic Check: Correct is A, A, A
        if (asmState[1] === 'A' && asmState[2] === 'A' && asmState[3] === 'A') {
            // Success Flow
            icon.className = "w-16 h-16 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center mb-4 border border-emerald-500/50 animate-bounce";
            icon.innerHTML = `<svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" d="M5 13l4 4L19 7" /></svg>`;
            title.innerText = "System Deployed Successfully";
            msg.innerText = "Konfigurasi optimal. Kode bersih, semantik, dan performa tinggi.";
            
            // Tombol Lanjut (Trigger Finish Chapter)
            action.innerText = "Lanjut Bab Berikutnya →";
            action.onclick = finishChapter;
        } else {
            // Fail Flow
            icon.className = "w-16 h-16 rounded-full bg-red-500/20 text-red-400 flex items-center justify-center mb-4 border border-red-500/50 animate-pulse";
            icon.innerHTML = `<svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" d="M6 18L18 6M6 6l12 12" /></svg>`;
            title.innerText = "Deployment Failed";
            msg.innerText = "Terdeteksi inefisiensi pada konfigurasi. Periksa kembali pilihan Anda menggunakan panel diagnostik.";
            action.innerText = "Re-Configure";
            action.onclick = () => { overlay.classList.add('hidden'); };
        }
    }

    // --- FUNGSI PENGUNCI AKTIVITAS ---
    function lockActivityUI() {
        // 1. Set Status Internal ke Benar
        asmState = { 1: 'A', 2: 'A', 3: 'A' };

        // 2. Kunci Tombol Pilihan (Visual Only)
        // Kita loop elemen button yang memiliki data-choice
        document.querySelectorAll('.module-btn').forEach(btn => {
            const choice = btn.getAttribute('data-choice');
            // Jika ini jawaban benar (A), highlight. Jika salah (B), disable.
            if(choice === 'A') {
                btn.classList.remove('bg-white/5', 'text-gray-400');
                btn.classList.add('bg-emerald-500/20', 'border-emerald-500', 'text-emerald-300', 'cursor-default');
            } else {
                btn.classList.add('opacity-30', 'cursor-not-allowed');
            }
            // Hapus onclick handler
            btn.removeAttribute('onclick');
        });

        // 3. Render Hasil Akhir
        renderAssembly();

        // 4. Update Diagnostik ke Status Final
        const diag = document.getElementById('diagnostic-text');
        diag.innerHTML = "> SYSTEM ARCHIVED.\n> STATUS: COMPLETED.";
        diag.className = "text-[10px] font-mono h-12 leading-relaxed text-emerald-400";
        [1,2,3].forEach(i => document.getElementById(`diag-light-${i}`).className = "w-1.5 h-1.5 rounded-full bg-emerald-500 shadow-[0_0_5px_#10b981]");

        // 5. Ubah Tombol Deploy menjadi Archived
        const deployBtn = document.getElementById('btn-deploy');
        if(deployBtn) {
            deployBtn.innerHTML = "<span>MISSION ACCOMPLISHED (ARCHIVED)</span>";
            deployBtn.classList.remove('hover:scale-[1.02]');
            deployBtn.classList.add('opacity-50', 'cursor-not-allowed', 'grayscale');
            deployBtn.disabled = true;
            deployBtn.removeAttribute('onclick');
        }
    }


    // --- SYSTEM UTILS: UNLOCK LOGIC (From 1.3) ---

    async function finishChapter() {
        const actionBtn = document.getElementById('deploy-action');
        actionBtn.innerText = "Menyimpan...";
        
        try {
            await fetch('/activity/complete', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'Content-Type': 'application/json' }, body: JSON.stringify({ activity_id: ACTIVITY_ID, score: 100 }) });
            await saveLessonToDB(ACTIVITY_LESSON_ID); 
            completedSet.add(ACTIVITY_LESSON_ID);
            activityCompleted = true; 
            
            updateProgressUI(); 
            unlockNextChapter(); 
            lockActivityUI(); // Kunci UI setelah selesai
            
            // Redirect to Dashboard or next chapter
            window.location.href = "{{ route('courses.advantages') }}";
        } catch(e) { 
            console.error(e); 
            actionBtn.innerText = "Gagal. Coba lagi.";
        }
    }

    function unlockNextChapter() {
        const btn = document.getElementById('nextChapterBtn');
        const nextLabel = document.getElementById('nextLabel');
        const nextIcon = document.getElementById('nextIcon');

        if(btn) {
            // Unlock UI Container
            btn.classList.remove('cursor-not-allowed', 'opacity-50', 'pointer-events-none', 'text-slate-500');
            btn.classList.add('text-cyan-400', 'hover:text-cyan-300', 'cursor-pointer');
            
            // Update Label
            if(nextLabel) {
                nextLabel.innerText = "Selanjutnya";
                nextLabel.classList.remove('opacity-50');
            }

            // Update Icon
            if(nextIcon) {
                nextIcon.innerHTML = "→";
                nextIcon.classList.remove('bg-white/5');
                nextIcon.classList.add('bg-cyan-500/20', 'border-cyan-500/50');
            }

            // Bind Click Action
            btn.onclick = () => window.location.href = "{{ route('courses.advantages') }}"; 
        }
    }

    function updateProgressUI() {
        const total = window.LESSON_IDS.length; 
        const done = window.LESSON_IDS.filter(id => completedSet.has(id)).length; 
        const percent = Math.round((done / total) * 100);
        document.getElementById('topProgressBar').style.width = percent + '%'; 
        document.getElementById('progressLabelTop').innerText = percent + '%';
        if(percent === 100) unlockNextChapter();
    }

    // --- SCROLL & OBSERVER LOGIC ---

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
                        link.classList.remove('bg-white/5'); dot.classList.remove('bg-cyan-500', 'shadow-[0_0_8px_#22d3ee]', 'scale-125'); dot.classList.add('bg-slate-600');
                        if (link.dataset.target === id) { link.classList.add('bg-white/5'); dot.classList.remove('bg-slate-600'); dot.classList.add('bg-cyan-500', 'shadow-[0_0_8px_#22d3ee]', 'scale-125'); }
                    });
                }
            });
        }, { root: mainScroll, rootMargin: '-20% 0px -60% 0px', threshold: 0 });
        sections.forEach(section => observer.observe(section));
    }

    function initSidebarScroll(){const m=document.getElementById('mainScroll');const l=document.querySelectorAll('.accordion-content .nav-item');m.addEventListener('scroll',()=>{let c='';document.querySelectorAll('.lesson-section').forEach(s=>{if(m.scrollTop>=s.offsetTop-250)c='#'+s.id;});l.forEach(k=>{k.classList.remove('active');if(k.getAttribute('data-target')===c)k.classList.add('active')})});l.forEach(k=>k.addEventListener('click',(e)=>{const t=document.querySelector(k.getAttribute('data-target'));if(t)m.scrollTo({top:t.offsetTop-120,behavior:'smooth'})}));}
</script>
@endsection