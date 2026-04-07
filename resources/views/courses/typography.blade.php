@extends('layouts.landing')
@section('title','Tipografi ')

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
        --accent: #06b6d4; /* Cyan 500 */
        --accent-glow: rgba(6, 182, 212, 0.3);
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
        --accent-glow: rgba(6, 182, 212, 0.5);
    }

    body { font-family: 'Inter', sans-serif; background-color: var(--bg-main); color: var(--text-main); transition: background-color 0.4s ease, color 0.4s ease; }
    .font-mono { font-family: 'JetBrains Mono', monospace; }

    /* UTILITIES ADAPTIF */
    .bg-adaptive { background-color: var(--bg-main); transition: background-color 0.4s ease; }
    .text-adaptive { color: var(--text-main); transition: color 0.4s ease; }
    .text-heading { color: var(--text-heading); transition: color 0.4s ease; }
    .text-muted { color: var(--text-muted); transition: color 0.4s ease; }
    .border-adaptive { border-color: var(--border-color); transition: border-color 0.4s ease; }
    .card-adaptive { background-color: var(--card-bg); border-color: var(--glass-border); transition: all 0.3s; }
    .card-adaptive:hover { border-color: var(--accent-glow); }
    .sim-bg-adaptive { background-color: var(--simulator-bg); transition: background-color 0.4s ease; }
    .code-adaptive { background-color: var(--code-bg); border-color: var(--glass-border); transition: all 0.4s ease; }

    /* SCROLLBAR */
    .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(150,150,150,0.3); border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: var(--accent); }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }
    
    /* ANIMATIONS & EFFECTS */
    #animated-bg { 
        background: radial-gradient(800px circle at 20% 20%, rgba(6,182,212,.08), transparent 40%), 
                    radial-gradient(800px circle at 80% 80%, rgba(59,130,246,.08), transparent 40%); 
        animation: bgMove 20s ease-in-out infinite alternate; 
    }
    .dark #animated-bg {
        background: radial-gradient(800px circle at 20% 20%, rgba(6,182,212,.15), transparent 40%), 
                    radial-gradient(800px circle at 80% 80%, rgba(59,130,246,.15), transparent 40%); 
    }
    @keyframes bgMove{to{transform:scale(1.15)}}
    @keyframes shake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-5px); } 75% { transform: translateX(5px); } }
    .shake { animation: shake 0.4s ease-in-out; }
    
    /* MOBILE SIDEBAR NO-BLUR FIX */
    @media (max-width: 1023px) {
        #courseSidebar {
            position: fixed;
            top: 64px;
            left: -100%;
            height: calc(100vh - 64px);
            transition: left 0.3s ease-in-out;
        }
        #courseSidebar.mobile-open { left: 0; box-shadow: 10px 0 30px rgba(0,0,0,0.5); }
        #mobileOverlay { display: none; position: fixed; inset: 0; top: 64px; background: rgba(0,0,0,0.6); z-index: 30; }
        #mobileOverlay.show { display: block; }
    }
    
    /* SCROLLSPY SIDEBAR ACTIVE */
    .nav-item { display: flex; width: 100%; text-align: left; align-items: center; gap: 12px; padding: 10px 14px; font-size: 0.85rem; color: var(--text-muted); border-radius: 8px; transition: all 0.2s; position: relative; }
    .nav-item:hover { color: var(--text-main); background: var(--card-hover); }
    .nav-item.active { color: #06b6d4; background: rgba(6, 182, 212, 0.05); font-weight: 600; }
    .dot { width: 6px; height: 6px; border-radius: 50%; background: #94a3b8; transition: all 0.3s; }
    .dark .dot { background: #334155; }
    .nav-item.active .dot { background: #06b6d4; box-shadow: 0 0 8px #06b6d4; transform: scale(1.2); }

    .insight-box {
        animation: fadeIn 0.4s ease-in-out;
    }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }
</style>


<div id="courseRoot" class="relative h-screen bg-slate-50 dark:bg-[#020617] text-slate-800 dark:text-white font-sans overflow-hidden flex flex-col selection:bg-cyan-500/30 pt-20 transition-colors duration-500">

    {{-- BACKGROUND COSMIC LAYER --}}
    <div class="fixed inset-0 -z-50 pointer-events-none">
        <div id="animated-bg" class="absolute inset-0 opacity-50 transition-opacity duration-500"></div>
        <div class="absolute top-[-10%] right-[-10%] w-[600px] h-[600px] bg-cyan-300/30 dark:bg-cyan-900/10 rounded-full blur-[120px] animate-pulse transition-colors duration-500"></div>
        <div class="absolute bottom-[-10%] left-[-10%] w-[500px] h-[500px] bg-blue-300/30 dark:bg-blue-900/10 rounded-full blur-[100px] transition-colors duration-500"></div>
        <canvas id="stars" class="absolute inset-0 pointer-events-none opacity-0 dark:opacity-100 transition-opacity duration-500"></canvas>
        <div id="cursor-glow"></div>
    </div>

    @include('layouts.partials.navbar')

    <div class="flex flex-1 overflow-hidden relative z-20">
        @include('layouts.partials.course-sidebar')

        <main id="mainScroll" class="flex-1 h-full overflow-y-auto scroll-smooth relative bg-transparent custom-scrollbar scroll-padding-top-24">
            
            {{-- STICKY HEADER --}}
            <div id="stickyHeader" class="sticky top-0 z-30 w-full bg-white/80 dark:bg-[#020617]/80 backdrop-blur-2xl border-b border-slate-200 dark:border-white/5 px-4 md:px-8 py-4 flex items-center justify-between transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div class="w-8 h-8 rounded-lg bg-cyan-50 dark:bg-gradient-to-br dark:from-cyan-500/20 dark:to-transparent border border-cyan-200 dark:border-cyan-500/20 flex items-center justify-center font-bold text-xs text-cyan-600 dark:text-cyan-400 shrink-0 transition-colors">3.1</div>
                    <div>
                        <h1 class="text-sm font-bold text-slate-900 dark:text-white line-clamp-1 transition-colors">Tipografi Masterclass</h1>
                        <p class="text-[10px] text-slate-500 dark:text-white/50 line-clamp-1 transition-colors">Hierarki Visual Tingkat Lanjut</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <div class="hidden sm:block w-24 md:w-32 h-1.5 bg-slate-200 dark:bg-white/10 rounded-full overflow-hidden transition-colors">
                        <div id="topProgressBar" class="h-full bg-gradient-to-r from-cyan-400 to-blue-500 dark:from-cyan-500 dark:to-blue-500 w-0 transition-all duration-500 shadow-[0_0_10px_#0ea5e9]"></div>
                    </div>
                    <span id="progressLabelTop" class="text-cyan-600 dark:text-cyan-400 font-bold text-xs transition-colors">0%</span>
                </div>
            </div>

            {{-- CONTENT WRAPPER --}}
            <div class="p-6 lg:p-16 max-w-5xl mx-auto pb-40">
                
                {{-- LEARNING OBJECTIVES --}}
                <div class="mb-24">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-2 transition-colors">
                        <svg class="w-5 h-5 text-cyan-500 dark:text-cyan-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Tujuan Pembelajaran
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                        
                        <div class="bg-white dark:bg-[#1e1e1e] border border-slate-200 dark:border-white/5 p-5 rounded-xl flex items-start gap-4 hover:border-cyan-400 dark:hover:border-cyan-500/30 shadow-sm dark:shadow-none transition group h-full">
                            <div class="w-8 h-8 rounded bg-cyan-100 dark:bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 flex items-center justify-center shrink-0 font-bold text-xs transition-colors">1</div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800 dark:text-white mb-1 transition-colors">Klasifikasi Huruf</h4>
                                <p class="text-[11px] text-slate-500 dark:text-white/50 leading-relaxed transition-colors"><strong>Memahami</strong> karakteristik dan psikologi keluarga font bawaan sistem untuk efisiensi performa web.</p>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-[#1e1e1e] border border-slate-200 dark:border-white/5 p-5 rounded-xl flex items-start gap-4 hover:border-blue-400 dark:hover:border-blue-500/30 shadow-sm dark:shadow-none transition group h-full">
                            <div class="w-8 h-8 rounded bg-blue-100 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0 font-bold text-xs transition-colors">2</div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800 dark:text-white mb-1 transition-colors">Skala Proporsional</h4>
                                <p class="text-[11px] text-slate-500 dark:text-white/50 leading-relaxed transition-colors"><strong>Mempelajari</strong> cara menyusun hierarki visual yang harmonis menggunakan sistem ukuran modular responsif.</p>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-[#1e1e1e] border border-slate-200 dark:border-white/5 p-5 rounded-xl flex items-start gap-4 hover:border-sky-400 dark:hover:border-sky-500/30 shadow-sm dark:shadow-none transition group h-full">
                            <div class="w-8 h-8 rounded bg-sky-100 dark:bg-sky-500/10 text-sky-600 dark:text-sky-400 flex items-center justify-center shrink-0 font-bold text-xs transition-colors">3</div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800 dark:text-white mb-1 transition-colors">Ketebalan Font</h4>
                                <p class="text-[11px] text-slate-500 dark:text-white/50 leading-relaxed transition-colors"><strong>Menguasai</strong> teknik manipulasi bobot (weight) huruf untuk mengarahkan fokus mata secara elegan.</p>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-[#1e1e1e] border border-slate-200 dark:border-white/5 p-5 rounded-xl flex items-start gap-4 hover:border-teal-400 dark:hover:border-teal-500/30 shadow-sm dark:shadow-none transition group h-full">
                            <div class="w-8 h-8 rounded bg-teal-100 dark:bg-teal-500/10 text-teal-600 dark:text-teal-400 flex items-center justify-center shrink-0 font-bold text-xs transition-colors">4</div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800 dark:text-white mb-1 transition-colors">Ruang & Spasi</h4>
                                <p class="text-[11px] text-slate-500 dark:text-white/50 leading-relaxed transition-colors"><strong>Memahami</strong> prinsip pengaturan jarak baris dan kerapatan karakter demi kenyamanan baca jangka panjang.</p>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-cyan-50 to-blue-50 dark:from-cyan-900/40 dark:to-blue-900/40 border border-cyan-200 dark:border-cyan-500/30 p-5 rounded-xl flex items-start gap-4 hover:shadow-md dark:hover:shadow-[0_0_20px_rgba(34,211,238,0.2)] transition group h-full col-span-1 sm:col-span-2 md:col-span-4 cursor-default">
                            <div class="w-8 h-8 rounded bg-cyan-100 dark:bg-white/10 text-cyan-600 dark:text-white flex items-center justify-center shrink-0 font-bold text-xs transition-colors">🏁</div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800 dark:text-white mb-1 transition-colors">Final Mission</h4>
                                <p class="text-[11px] text-slate-600 dark:text-white/70 leading-relaxed transition-colors"><strong>Mengimplementasikan</strong> seluruh teori tipografi ke dalam studi kasus nyata merancang tata letak portal berita (Expert Mode).</p>
                            </div>
                        </div>

                    </div>
                </div>

                <article class="space-y-40">
                    
                    {{-- LESSON 46: FONT FAMILY --}}
                    <section id="section-46" class="lesson-section scroll-mt-32" data-lesson-id="46">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-cyan-500 pl-6">
                                <span class="text-cyan-600 dark:text-cyan-400 font-mono text-xs uppercase tracking-widest transition-colors">Lesson 3.1.1</span>
                                <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-slate-900 dark:text-white leading-[1.1] transition-colors">
                                    Keluarga Huruf Sistem <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-500 to-blue-500 dark:from-cyan-400 dark:to-blue-500">(System Fonts)</span>
                                </h2>
                            </div>
                            
                            <div class="space-y-6">
                                <div class="prose prose-slate dark:prose-invert max-w-none text-slate-600 dark:text-white/70 text-base md:text-lg leading-relaxed transition-colors text-justify">
                                    <p>
                                        Menggunakan font khusus dari pihak ketiga (seperti Google Fonts) sering kali membebani kecepatan muat halaman dan memicu efek kedipan kosong (<em>Flash of Unstyled Text</em>). Tailwind CSS menyelesaikan masalah ini secara elegan dengan memanfaatkan koleksi huruf bawaan sistem (<em>system font stack</em>) secara otomatis.
                                    </p>
                                    <p>
                                        Metode ini menjamin situs Anda menggunakan font San Francisco di macOS/iOS, Segoe UI di Windows, dan Roboto di Android. Hasilnya situs termuat dalam sekejap mata dan terlihat menyatu secara natural dengan perangkat penonton.
                                    </p>

                                    <h4 class="text-slate-800 dark:text-white font-bold mb-4 mt-8 text-sm uppercase tracking-wide transition-colors">Tiga Kategori Desain Utama</h4>
                                    <ul class="list-disc pl-5 space-y-3 text-sm md:text-base text-left mt-4 border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-white/5 p-6 rounded-xl transition-colors">
                                        <li><strong><code class="text-cyan-600 dark:text-cyan-300 font-bold transition-colors">font-sans</code></strong>: Huruf modern bersudut bersih tanpa lekukan. Keterbacaannya yang tinggi menjadikannya pilihan standar untuk tombol, navigasi, dan antarmuka web.</li>
                                        <li><strong><code class="text-cyan-600 dark:text-cyan-300 font-bold transition-colors">font-serif</code></strong>: Huruf dengan ornamen kaitan di ujung garisnya. Memberikan nuansa elegan dan klasik. Ideal untuk tubuh artikel panjang atau judul editorial.</li>
                                        <li><strong><code class="text-cyan-600 dark:text-cyan-300 font-bold transition-colors">font-mono</code></strong>: Huruf bertipe mesin tik di mana tiap karakter memakan ruang sejajar. Esensial untuk menampilkan kode pemrograman atau data tabular.</li>
                                    </ul>
                                </div>
                            </div>

                            {{-- SIMULATOR 1 --}}
                            <div class="bg-white dark:bg-[#0b0f19] border border-slate-200 dark:border-white/10 rounded-2xl p-6 lg:p-8 shadow-xl dark:shadow-2xl relative group hover:border-cyan-400 dark:hover:border-cyan-500/30 transition-all">
                                <h4 class="text-xs font-bold text-slate-400 dark:text-muted uppercase mb-4 text-center transition-colors tracking-widest">Simulator: Karakteristik Tema Huruf</h4>
                                
                                <div class="bg-cyan-50 dark:bg-cyan-500/10 border border-cyan-200 dark:border-cyan-500/30 rounded-xl p-4 mb-8 text-sm text-cyan-700 dark:text-cyan-300 relative z-10 shadow-sm dark:shadow-inner transition-colors">
                                    <p class="font-bold flex items-center gap-2 mb-2">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"></path></svg>
                                        Panduan Interaksi
                                    </p>
                                    <p class="m-0 opacity-90 leading-relaxed text-xs md:text-sm text-cyan-800/80 dark:text-cyan-100/80 transition-colors">
                                        Klik tombol konfigurasi jenis huruf di bawah ini untuk melihat bagaimana nuansa komponen desain berubah secara instan pada pratinjau di sebelah kanan.
                                    </p>
                                </div>

                                <div class="flex flex-col lg:flex-row justify-between items-start mb-6 gap-4 lg:gap-6 relative z-10">
                                    <div class="flex flex-col gap-2 w-full lg:w-1/3">
                                        <button onclick="updateSimFont(this, 'font-sans')" class="btn-sim-1 px-4 py-3 text-xs font-bold rounded-lg bg-cyan-600 text-white shadow-lg border border-cyan-400 transition text-left">Sans (Modern)</button>
                                        <button onclick="updateSimFont(this, 'font-serif')" class="btn-sim-1 px-4 py-3 text-xs font-bold rounded-lg bg-slate-200 dark:bg-white/5 text-slate-600 dark:text-white/50 border border-transparent hover:bg-slate-300 dark:hover:bg-white/10 transition text-left">Serif (Klasik)</button>
                                        <button onclick="updateSimFont(this, 'font-mono')" class="btn-sim-1 px-4 py-3 text-xs font-bold rounded-lg bg-slate-200 dark:bg-white/5 text-slate-600 dark:text-white/50 border border-transparent hover:bg-slate-300 dark:hover:bg-white/10 transition text-left">Mono (Kode)</button>
                                    </div>
                                    <div class="w-full lg:w-2/3 p-6 flex flex-col items-center justify-center bg-slate-100 dark:bg-black/40 rounded-xl min-h-[300px] border border-dashed border-slate-300 dark:border-white/10 relative overflow-hidden">
                                        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-5"></div>
                                        <div class="bg-white dark:bg-[#1e1e1e] p-6 rounded-xl shadow-sm border border-slate-200 dark:border-white/5 w-full max-w-sm relative z-10 transition-colors mb-16">
                                            <div class="w-10 h-10 rounded-full bg-cyan-100 dark:bg-cyan-900/30 flex items-center justify-center mb-4">
                                                <svg class="w-5 h-5 text-cyan-600 dark:text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                            </div>
                                            <h4 id="demo-font-title" class="text-xl font-bold text-slate-900 dark:text-white font-sans transition-all duration-300 mb-2">Psikologi Desain</h4>
                                            <p id="demo-font-body" class="text-sm text-slate-500 dark:text-white/60 font-sans transition-all duration-300 leading-relaxed">
                                                Pemilihan jenis huruf sangat krusial dalam membentuk identitas visual, nada komunikasi, dan tingkat profesionalisme sebuah produk digital.
                                            </p>
                                        </div>
                                        <div class="absolute bottom-4 left-4 right-4 bg-cyan-50 dark:bg-cyan-900/40 border border-cyan-100 dark:border-cyan-800/50 p-3 rounded-lg text-xs text-cyan-800 dark:text-cyan-200 flex items-start gap-3 backdrop-blur-sm shadow-sm z-20 transition-colors">
                                            <span class="text-base shrink-0">💡</span>
                                            <p id="demo-font-insight" class="insight-box m-0 leading-relaxed">Gunakan <code class="font-bold">font-sans</code> sebagai fondasi utama antarmuka (UI) karena sifatnya yang netral dan sangat mudah dibaca pada berbagai ukuran layar.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 47: SIZE & HIERARCHY --}}
                    <section id="section-47" class="lesson-section scroll-mt-32" data-lesson-id="47">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-blue-500 pl-6">
                                <span class="text-blue-600 dark:text-blue-400 font-mono text-xs uppercase tracking-widest transition-colors">Lesson 3.1.2</span>
                                <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-slate-900 dark:text-white leading-[1.1] transition-colors">
                                    Skala Modular & Hierarki
                                </h2>
                            </div>

                            <div class="space-y-6">
                                <div class="prose prose-slate dark:prose-invert max-w-none text-slate-600 dark:text-white/70 text-base md:text-lg leading-relaxed transition-colors text-justify">
                                    <p>
                                        Menebak-nebak ukuran teks menggunakan angka piksel acak sering kali merusak ritme visual halaman. Tailwind menerapkan <strong>Sistem Skala Modular</strong>, yaitu deretan ukuran baku yang telah dihitung secara proporsional agar selalu terlihat harmonis.
                                    </p>
                                    <p>
                                        Kehebatan utama utilitas tipografi Tailwind adalah integrasi otomatis antara ukuran huruf dan jarak antar baris (<em>line-height</em>). Setiap kali Anda memanggil kelas ukuran teks seperti <code class="text-blue-600 dark:text-blue-300 font-bold bg-slate-100 dark:bg-white/10 px-1 rounded transition-colors">text-xl</code>, Tailwind secara presisi akan menyetel jarak barisnya agar selaras secara optikal.
                                    </p>
                                </div>
                            </div>

                            {{-- SIMULATOR 2 --}}
                            <div class="bg-white dark:bg-[#0b0f19] border border-slate-200 dark:border-white/10 rounded-2xl p-6 lg:p-8 shadow-xl dark:shadow-2xl relative group hover:border-blue-400 dark:hover:border-blue-500/30 transition-all">
                                <h4 class="text-xs font-bold text-slate-400 dark:text-muted uppercase mb-4 text-center transition-colors tracking-widest">Simulator: Dimensi & Relasi Baris</h4>

                                <div class="bg-blue-50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/30 rounded-xl p-4 mb-8 text-sm text-blue-700 dark:text-blue-300 relative z-10 shadow-sm dark:shadow-inner transition-colors">
                                    <p class="font-bold flex items-center gap-2 mb-2">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"></path></svg>
                                        Panduan Eksplorasi
                                    </p>
                                    <p class="m-0 opacity-90 leading-relaxed text-xs md:text-sm text-blue-800/80 dark:text-blue-100/80 transition-colors">
                                        Pilih skala modular di bawah untuk melihat efek otomatisasi ukuran dan kerapatan spasi baris oleh mesin penyetel Tailwind.
                                    </p>
                                </div>
                                
                                <div class="flex flex-col md:flex-row w-full gap-4 lg:gap-6 relative z-10">
                                    <div class="w-full md:w-1/2 flex flex-col gap-2">
                                        <div onclick="updateSimSize(this, 'text-sm')" class="btn-sim-2 group border-l-4 border-transparent hover:border-blue-400 bg-slate-50 dark:bg-white/5 p-3 rounded-r cursor-pointer transition">
                                            <code class="text-xs text-blue-600 dark:text-blue-400 block mb-1">text-sm</code>
                                            <p class="text-sm font-bold text-slate-800 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition">Ukuran Pelengkap (Keterangan)</p>
                                        </div>
                                        <div onclick="updateSimSize(this, 'text-base')" class="btn-sim-2 group border-l-4 border-blue-500 bg-blue-50 dark:bg-blue-900/20 p-3 rounded-r cursor-pointer transition">
                                            <code class="text-xs text-blue-600 dark:text-blue-400 block mb-1">text-base</code>
                                            <p class="text-base font-bold text-slate-800 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition">Standar Bacaan Nyaman (Body)</p>
                                        </div>
                                        <div onclick="updateSimSize(this, 'text-2xl')" class="btn-sim-2 group border-l-4 border-transparent hover:border-blue-400 bg-slate-50 dark:bg-white/5 p-3 rounded-r cursor-pointer transition">
                                            <code class="text-xs text-blue-600 dark:text-blue-400 block mb-1">text-2xl</code>
                                            <p class="text-2xl font-bold text-slate-800 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition">Tajuk Menengah (Sub-Heading)</p>
                                        </div>
                                        <div onclick="updateSimSize(this, 'text-4xl')" class="btn-sim-2 group border-l-4 border-transparent hover:border-blue-400 bg-slate-50 dark:bg-white/5 p-3 rounded-r cursor-pointer transition">
                                            <code class="text-xs text-blue-600 dark:text-blue-400 block mb-1">text-4xl</code>
                                            <p class="text-4xl font-bold text-slate-800 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition">Judul Raksasa (Hero)</p>
                                        </div>
                                    </div>
                                    <div class="w-full md:w-1/2 p-6 flex flex-col items-center justify-center bg-slate-100 dark:bg-black/40 rounded-xl min-h-[350px] border border-dashed border-slate-300 dark:border-white/10 relative overflow-hidden">
                                        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-5"></div>
                                        <div class="bg-white dark:bg-[#1e1e1e] border border-slate-200 dark:border-white/5 p-6 rounded-xl shadow-sm w-full max-w-sm relative z-10 mb-16">
                                            <p id="demo-size" class="text-base text-slate-900 dark:text-white font-bold transition-all duration-300 bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800/50 p-3 rounded leading-normal">
                                                Kalimat contoh ini membuktikan bagaimana mesin penyetel Tailwind mengatur kerapatan spasi baris secara otomatis sesuai dengan dimensi ukuran huruf.
                                            </p>
                                        </div>
                                        <div class="absolute bottom-4 left-4 right-4 bg-blue-50 dark:bg-blue-900/40 border border-blue-100 dark:border-blue-800/50 p-3 rounded-lg text-xs text-blue-800 dark:text-blue-200 flex items-start gap-3 backdrop-blur-sm shadow-sm z-20">
                                            <span class="text-base shrink-0">💡</span>
                                            <p id="demo-size-insight" class="insight-box m-0 leading-relaxed">Keseimbangan absolut. Tailwind menyetel rasio paling sempurna pada <code class="font-bold">text-base</code> untuk dijadikan ukuran patokan tulisan badan panjang.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 48: WEIGHT & ANTIALIASING --}}
                    <section id="section-48" class="lesson-section scroll-mt-32" data-lesson-id="48">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-sky-500 pl-6">
                                <span class="text-sky-600 dark:text-sky-400 font-mono text-xs uppercase tracking-widest transition-colors">Lesson 3.1.3</span>
                                <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-slate-900 dark:text-white leading-[1.1] transition-colors">
                                    Ketebalan Huruf & <br> Resolusi Piksel
                                </h2>
                            </div>

                            <div class="space-y-6">
                                <div class="prose prose-slate dark:prose-invert max-w-none text-slate-600 dark:text-white/70 text-base md:text-lg leading-relaxed transition-colors text-justify">
                                    <p>
                                        Memperbesar ukuran huruf bukanlah satu-satunya cara untuk menciptakan penekanan. Desainer profesional sering mempertahankan ukuran yang sama, namun memanipulasi <strong>bobot ketebalannya</strong> untuk menciptakan kontras visual.
                                    </p>
                                    <p>
                                        Selain itu, pada kanvas digital mode gelap, teks putih sering terlihat membengkak akibat pendaran piksel layar. Atasi ilusi optik ini dengan utilitas perisai <code class="text-sky-600 dark:text-sky-300 font-bold bg-slate-100 dark:bg-white/10 px-1 rounded transition-colors">antialiased</code> yang akan memaksa browser merender ujung piksel setajam mungkin.
                                    </p>
                                </div>
                            </div>

                            {{-- SIMULATOR 3 --}}
                            <div class="bg-white dark:bg-[#0b0f19] border border-slate-200 dark:border-white/10 rounded-2xl p-6 lg:p-8 shadow-xl dark:shadow-2xl relative group hover:border-sky-400 dark:hover:border-sky-500/30 transition-all">
                                <h4 class="text-xs font-bold text-slate-400 dark:text-muted uppercase mb-4 text-center transition-colors tracking-widest">Simulator: Dimensi Bobot Visual</h4>
                                
                                <div class="flex flex-col md:flex-row w-full gap-4 lg:gap-6 relative z-10 mt-8">
                                    <div class="w-full md:w-1/3 flex flex-col gap-2 justify-center">
                                        <button onclick="updateSimWeight(this, 'font-light')" class="btn-sim-3 w-full text-left px-4 py-3 rounded-lg bg-slate-200 dark:bg-white/5 border border-transparent text-slate-600 dark:text-white/50 hover:bg-slate-300 dark:hover:bg-white/10 transition text-sm font-bold">font-light <span class="text-xs font-normal opacity-60 ml-2">(Tipis)</span></button>
                                        <button onclick="updateSimWeight(this, 'font-normal')" class="btn-sim-3 w-full text-left px-4 py-3 rounded-lg bg-sky-600 text-white shadow-lg border border-sky-400 transition text-sm font-bold">font-normal <span class="text-xs font-normal opacity-80 ml-2">(Standar)</span></button>
                                        <button onclick="updateSimWeight(this, 'font-bold')" class="btn-sim-3 w-full text-left px-4 py-3 rounded-lg bg-slate-200 dark:bg-white/5 border border-transparent text-slate-600 dark:text-white/50 hover:bg-slate-300 dark:hover:bg-white/10 transition text-sm font-bold">font-bold <span class="text-xs font-normal opacity-60 ml-2">(Fokus)</span></button>
                                        <button onclick="updateSimWeight(this, 'font-black')" class="btn-sim-3 w-full text-left px-4 py-3 rounded-lg bg-slate-200 dark:bg-white/5 border border-transparent text-slate-600 dark:text-white/50 hover:bg-slate-300 dark:hover:bg-white/10 transition text-sm font-bold">font-black <span class="text-xs font-normal opacity-60 ml-2">(Agresif)</span></button>
                                    </div>
                                    <div class="w-full md:w-2/3 p-6 flex flex-col items-center justify-center bg-slate-100 dark:bg-black/40 rounded-xl min-h-[300px] border border-dashed border-slate-300 dark:border-white/10 relative overflow-hidden">
                                        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-5"></div>
                                        <div class="bg-[#1e1e1e] p-10 rounded-xl shadow-lg border border-white/10 w-full max-w-sm text-center relative z-10 flex flex-col justify-center mb-16">
                                            <p id="demo-weight" class="text-4xl text-white font-normal transition-all duration-300 antialiased">
                                                Kontras Visual
                                            </p>
                                        </div>
                                        <div class="absolute bottom-4 left-4 right-4 bg-sky-50 dark:bg-sky-900/40 border border-sky-100 dark:border-sky-800/50 p-3 rounded-lg text-xs text-sky-800 dark:text-sky-200 flex items-start gap-3 backdrop-blur-sm shadow-sm z-20">
                                            <span class="text-base shrink-0">💡</span>
                                            <p id="demo-weight-insight" class="insight-box m-0 leading-relaxed">Sebagian besar teks tubuh harus menetap pada kelas pelindung <code class="font-bold">font-normal</code> agar pembaca tidak merasa diteriaki dan mata tak cepat lelah.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 49: MICRO TYPOGRAPHY --}}
                    <section id="section-49" class="lesson-section scroll-mt-32" data-lesson-id="49">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-teal-500 pl-6">
                                <span class="text-teal-600 dark:text-teal-400 font-mono text-xs uppercase tracking-widest transition-colors">Lesson 3.1.4</span>
                                <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-slate-900 dark:text-white leading-[1.1] transition-colors">
                                    Manajemen Spasi & Penambat
                                </h2>
                            </div>

                            <div class="space-y-6">
                                <div class="prose prose-slate dark:prose-invert max-w-none text-slate-600 dark:text-white/70 text-base md:text-lg leading-relaxed transition-colors text-justify">
                                    <p>
                                        Kerapatan jarak karakter (<em>letter-spacing</em>) dan orientasi perataan paragraf adalah detil mikrotipografi yang membedakan desain amatir dan profesional.
                                    </p>
                                    <ul class="list-disc pl-5 space-y-3 mt-4 border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-white/5 p-6 rounded-xl transition-colors">
                                        <li>Kaidah Emas Tracking: Teks kapital berukuran kecil WAJIB direnggangkan (<code class="text-teal-600 dark:text-teal-300 font-bold transition-colors">tracking-wider</code>), sedangkan judul hero berukuran raksasa sebaiknya dikompres rapat (<code class="text-teal-600 dark:text-teal-300 font-bold transition-colors">tracking-tighter</code>) agar terlihat solid layaknya poster film.</li>
                                        <li>Kaidah Emas Alignment: Hindari perataan <em>Justify</em> di layar digital karena akan melahirkan sungai rongga kosong yang mengganggu mata. Prioritaskan perataan kiri (<code class="text-teal-600 dark:text-teal-300 font-bold transition-colors">text-left</code>) untuk badan artikel.</li>
                                    </ul>
                                </div>
                            </div>

                            {{-- SIMULATOR 4 --}}
                            <div class="bg-white dark:bg-[#0b0f19] border border-slate-200 dark:border-white/10 rounded-2xl p-6 lg:p-8 shadow-xl dark:shadow-2xl relative group hover:border-teal-400 dark:hover:border-teal-500/30 transition-all mt-8">
                                <h4 class="text-xs font-bold text-slate-400 dark:text-muted uppercase mb-4 text-center transition-colors tracking-widest">Simulator: Spasi & Penambat Paragraf</h4>

                                <div class="flex flex-col md:flex-row w-full gap-4 lg:gap-6 relative z-10 mt-8">
                                    <div class="w-full md:w-1/3 flex flex-col gap-4 justify-center">
                                        <div class="text-xs font-bold text-slate-700 dark:text-slate-300">Renggang Huruf Judul</div>
                                        <div class="flex flex-wrap gap-2">
                                            <button onclick="updateSimTrackAlign(this, 'track', 'tracking-tighter')" class="btn-sim-4-track px-3 py-1.5 rounded bg-slate-200 dark:bg-white/5 text-slate-600 dark:text-white/50 border border-transparent hover:bg-slate-300 dark:hover:bg-white/10 transition text-xs font-bold">Rapat</button>
                                            <button onclick="updateSimTrackAlign(this, 'track', 'tracking-normal')" class="btn-sim-4-track px-3 py-1.5 rounded bg-teal-600 text-white shadow-md border border-teal-400 transition text-xs font-bold">Normal</button>
                                            <button onclick="updateSimTrackAlign(this, 'track', 'tracking-widest')" class="btn-sim-4-track px-3 py-1.5 rounded bg-slate-200 dark:bg-white/5 text-slate-600 dark:text-white/50 border border-transparent hover:bg-slate-300 dark:hover:bg-white/10 transition text-xs font-bold">Renggang</button>
                                        </div>
                                        
                                        <div class="border-t border-slate-200 dark:border-white/10 my-2"></div>
                                        
                                        <div class="text-xs font-bold text-slate-700 dark:text-slate-300">Arah Paragraf</div>
                                        <div class="flex flex-wrap gap-2">
                                            <button onclick="updateSimTrackAlign(this, 'align', 'text-left')" class="btn-sim-4-align px-3 py-1.5 rounded bg-teal-600 text-white shadow-md border border-teal-400 transition text-xs font-bold">Kiri</button>
                                            <button onclick="updateSimTrackAlign(this, 'align', 'text-center')" class="btn-sim-4-align px-3 py-1.5 rounded bg-slate-200 dark:bg-white/5 text-slate-600 dark:text-white/50 border border-transparent hover:bg-slate-300 dark:hover:bg-white/10 transition text-xs font-bold">Tengah</button>
                                            <button onclick="updateSimTrackAlign(this, 'align', 'text-justify')" class="btn-sim-4-align px-3 py-1.5 rounded bg-slate-200 dark:bg-white/5 text-slate-600 dark:text-white/50 border border-transparent hover:bg-slate-300 dark:hover:bg-white/10 transition text-xs font-bold">Sama Sisi</button>
                                        </div>
                                    </div>
                                    <div class="w-full md:w-2/3 p-6 flex flex-col items-center justify-center bg-slate-100 dark:bg-black/40 rounded-xl min-h-[350px] border border-dashed border-slate-300 dark:border-white/10 relative overflow-hidden">
                                        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-5"></div>
                                        <div class="bg-white dark:bg-[#1e1e1e] p-6 rounded-xl shadow-sm border border-slate-200 dark:border-white/5 w-full relative z-10 transition-all duration-300 mb-16">
                                            <h4 id="demo-track" class="text-3xl font-black text-slate-900 dark:text-white mb-4 uppercase tracking-normal transition-all duration-300 border-b border-slate-200 dark:border-white/10 pb-4">
                                                Sorotan Berita
                                            </h4>
                                            <p id="demo-align" class="text-sm text-slate-500 dark:text-white/60 text-left transition-all duration-300 leading-relaxed">
                                                Penentuan batas pelurusan tepi kalimat adalah elemen kunci pembentukan keseimbangan visual layar web. Simulasikan modifikasi untuk melihat rentang ruang yang ditinggalkan akibat perataan asimetris atau paksaan pelurusan pinggir secara artifisial.
                                            </p>
                                        </div>
                                        <div class="absolute bottom-4 left-4 right-4 bg-teal-50 dark:bg-teal-900/40 border border-teal-100 dark:border-teal-800/50 p-3 rounded-lg text-xs text-teal-800 dark:text-teal-200 flex items-start gap-3 backdrop-blur-sm shadow-sm z-20">
                                            <span class="text-base shrink-0">💡</span>
                                            <p id="demo-track-align-insight" class="insight-box m-0 leading-relaxed">Formasi <code class="font-bold">text-left</code> adalah landasan mutlak untuk tubuh artikel karena mata manusia telah terlatih melacak tepi rata lurus di sisi kiri tulisan.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 50: ORNAMENT --}}
                    <section id="section-50" class="lesson-section scroll-mt-32" data-lesson-id="50">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-indigo-500 pl-6">
                                <span class="text-indigo-600 dark:text-indigo-400 font-mono text-xs uppercase tracking-widest transition-colors">Lesson 3.1.5</span>
                                <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-slate-900 dark:text-white leading-[1.1] transition-colors">
                                    Ornamen & Transformasi
                                </h2>
                            </div>

                            <div class="space-y-6">
                                <div class="prose prose-slate dark:prose-invert max-w-none text-slate-600 dark:text-white/70 text-base md:text-lg leading-relaxed transition-colors text-justify">
                                    <p>
                                        Mengetik data judul secara manual menggunakan tombol Caps Lock di database sangat tidak disarankan. Biarkan data murni apa adanya, lalu rekayasa tampilan kapitalisasinya di lapisan depan (frontend) menggunakan kelas <code class="text-indigo-600 dark:text-indigo-300 font-bold bg-slate-100 dark:bg-white/10 px-1 rounded transition-colors">uppercase</code> atau <code class="text-indigo-600 dark:text-indigo-300 font-bold bg-slate-100 dark:bg-white/10 px-1 rounded transition-colors">capitalize</code>.
                                    </p>
                                    <p>
                                        Untuk tautan web, garis bawah standar browser kerap memotong perut huruf "g", "y", "p". Bebaskan mereka menggunakan kombinasi <code class="text-indigo-600 dark:text-indigo-300 font-bold bg-slate-100 dark:bg-white/10 px-1 rounded transition-colors">underline</code> ditambah dengan <code class="text-indigo-600 dark:text-indigo-300 font-bold bg-slate-100 dark:bg-white/10 px-1 rounded transition-colors">underline-offset-4</code> agar garis menjauh ke bawah dan terlihat jauh lebih modern.
                                    </p>
                                </div>
                            </div>

                            {{-- SIMULATOR 5 --}}
                            <div class="bg-white dark:bg-[#0b0f19] border border-slate-200 dark:border-white/10 rounded-2xl p-6 lg:p-8 shadow-xl dark:shadow-2xl relative group hover:border-indigo-400 dark:hover:border-indigo-500/30 transition-all mt-8">
                                <h4 class="text-xs font-bold text-slate-400 dark:text-muted uppercase mb-4 text-center transition-colors tracking-widest">Simulator: Transformasi & Ornamen CSS</h4>

                                <div class="flex flex-col md:flex-row w-full gap-4 lg:gap-6 relative z-10 mt-8">
                                    <div class="w-full md:w-1/3 flex flex-col gap-4 justify-center">
                                        <div class="text-xs font-bold text-slate-700 dark:text-slate-300">Konversi Kapitalisasi</div>
                                        <div class="flex flex-wrap gap-2">
                                            <button onclick="updateSimTransDecor(this, 'trans', 'normal-case')" class="btn-sim-5-trans px-3 py-1.5 rounded bg-indigo-600 text-white shadow-md border border-indigo-400 transition text-xs font-bold">Asli</button>
                                            <button onclick="updateSimTransDecor(this, 'trans', 'uppercase')" class="btn-sim-5-trans px-3 py-1.5 rounded bg-slate-200 dark:bg-white/5 text-slate-600 dark:text-white/50 border border-transparent hover:bg-slate-300 dark:hover:bg-white/10 transition text-xs font-bold">Kapital</button>
                                            <button onclick="updateSimTransDecor(this, 'trans', 'capitalize')" class="btn-sim-5-trans px-3 py-1.5 rounded bg-slate-200 dark:bg-white/5 text-slate-600 dark:text-white/50 border border-transparent hover:bg-slate-300 dark:hover:bg-white/10 transition text-xs font-bold">Awalan</button>
                                        </div>
                                        
                                        <div class="border-t border-slate-200 dark:border-white/10 my-2"></div>
                                        
                                        <div class="text-xs font-bold text-slate-700 dark:text-slate-300">Garis Ornamen</div>
                                        <div class="flex flex-wrap gap-2">
                                            <button onclick="updateSimTransDecor(this, 'decor', '')" class="btn-sim-5-decor px-3 py-1.5 rounded bg-indigo-600 text-white shadow-md border border-indigo-400 transition text-xs font-bold">Polos</button>
                                            <button onclick="updateSimTransDecor(this, 'decor', 'underline underline-offset-4 decoration-2 decoration-indigo-500')" class="btn-sim-5-decor px-3 py-1.5 rounded bg-slate-200 dark:bg-white/5 text-slate-600 dark:text-white/50 border border-transparent hover:bg-slate-300 dark:hover:bg-white/10 transition text-xs font-bold">Garis Modern</button>
                                            <button onclick="updateSimTransDecor(this, 'decor', 'underline decoration-wavy decoration-pink-500')" class="btn-sim-5-decor px-3 py-1.5 rounded bg-slate-200 dark:bg-white/5 text-slate-600 dark:text-white/50 border border-transparent hover:bg-slate-300 dark:hover:bg-white/10 transition text-xs font-bold">Gelombang</button>
                                        </div>
                                    </div>
                                    <div class="w-full md:w-2/3 p-6 flex flex-col items-center justify-center bg-slate-100 dark:bg-black/40 rounded-xl min-h-[300px] border border-dashed border-slate-300 dark:border-white/10 relative overflow-hidden">
                                        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-5"></div>
                                        <div class="w-full flex items-center justify-center mb-16">
                                            <p id="demo-trans-decor" class="text-3xl sm:text-5xl font-bold text-slate-900 dark:text-white transition-all duration-300 normal-case relative z-10 py-2 text-center">
                                                gaya pergelangan web
                                            </p>
                                        </div>
                                        <div class="absolute bottom-4 left-4 right-4 bg-indigo-50 dark:bg-indigo-900/40 border border-indigo-100 dark:border-indigo-800/50 p-3 rounded-lg text-xs text-indigo-800 dark:text-indigo-200 flex items-start gap-3 backdrop-blur-sm shadow-sm z-20">
                                            <span class="text-base shrink-0">💡</span>
                                            <p id="demo-trans-decor-insight" class="insight-box m-0 leading-relaxed">Kalimat dirender sesuai dengan integritas format dari pangkalan data asalnya tanpa diintervensi oleh gaya CSS tambahan.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- FINAL MISSION (EXPERT MODE - MONACO EDITOR) --}}
                    <section id="section-51" class="lesson-section scroll-mt-32 pt-10 border-t border-slate-200 dark:border-white/10 transition-colors" data-lesson-id="51" data-type="activity">
                        <div class="relative rounded-[2rem] md:rounded-[2.5rem] bg-white dark:bg-[#050b14] border border-slate-200 dark:border-white/10 p-6 md:p-10 overflow-hidden shadow-xl dark:shadow-2xl group hover:border-cyan-400 dark:hover:border-cyan-500/30 transition-all duration-500 flex flex-col">
                            
                            <div class="absolute -top-24 -right-24 w-64 h-64 bg-cyan-400/20 dark:bg-cyan-600/20 blur-[100px] rounded-full pointer-events-none transition-colors"></div>

                            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 sm:gap-6 mb-8 relative z-10 shrink-0">
                                <div class="p-3 sm:p-4 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-xl text-white shadow-lg shadow-cyan-500/30 shrink-0">
                                    <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <div>
                                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-3 mb-2">
                                        <h2 class="text-xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight transition-colors">Misi Editorial Akhir</h2>
                                        <span class="px-2 py-0.5 rounded text-[9px] sm:text-[10px] font-bold bg-rose-100 dark:bg-rose-500/20 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-500/30 uppercase tracking-wider shadow-sm transition-colors">Expert Live Code</span>
                                    </div>
                                    <p class="text-slate-500 dark:text-slate-400 text-xs sm:text-sm leading-relaxed max-w-2xl text-justify transition-colors">
                                        Sebuah portal berita elit menugaskan Anda merombak tipografi artikel harian mereka. Melalui terminal kode di bawah, suntikkan kelas utilitas pada elemen judul dan paragraf sesuai dengan parameter desain ketat yang diminta oleh klien.
                                    </p>
                                    
                                    {{-- TOOLBOX CLUE --}}
                                    <div class="mt-4 flex flex-wrap items-center gap-2">
                                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mr-1 transition-colors">Persenjataan Kelas Anda:</span>
                                        <code class="text-[10px] md:text-xs font-mono font-bold text-cyan-600 dark:text-cyan-400 bg-cyan-100 dark:bg-cyan-500/10 border border-cyan-200 dark:border-cyan-500/20 px-2 py-1 rounded shadow-sm transition-colors">font-serif</code>
                                        <code class="text-[10px] md:text-xs font-mono font-bold text-cyan-600 dark:text-cyan-400 bg-cyan-100 dark:bg-cyan-500/10 border border-cyan-200 dark:border-cyan-500/20 px-2 py-1 rounded shadow-sm transition-colors">text-6xl</code>
                                        <code class="text-[10px] md:text-xs font-mono font-bold text-cyan-600 dark:text-cyan-400 bg-cyan-100 dark:bg-cyan-500/10 border border-cyan-200 dark:border-cyan-500/20 px-2 py-1 rounded shadow-sm transition-colors">font-black</code>
                                        <code class="text-[10px] md:text-xs font-mono font-bold text-cyan-600 dark:text-cyan-400 bg-cyan-100 dark:bg-cyan-500/10 border border-cyan-200 dark:border-cyan-500/20 px-2 py-1 rounded shadow-sm transition-colors">text-slate-900</code>
                                        <code class="text-[10px] md:text-xs font-mono font-bold text-teal-600 dark:text-teal-400 bg-teal-100 dark:bg-teal-500/10 border border-teal-200 dark:border-teal-500/20 px-2 py-1 rounded shadow-sm transition-colors">text-left</code>
                                        <code class="text-[10px] md:text-xs font-mono font-bold text-teal-600 dark:text-teal-400 bg-teal-100 dark:bg-teal-500/10 border border-teal-200 dark:border-teal-500/20 px-2 py-1 rounded shadow-sm transition-colors">leading-relaxed</code>
                                        <code class="text-[10px] md:text-xs font-mono font-bold text-teal-600 dark:text-teal-400 bg-teal-100 dark:bg-teal-500/10 border border-teal-200 dark:border-teal-500/20 px-2 py-1 rounded shadow-sm transition-colors">text-slate-500</code>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-col xl:grid xl:grid-cols-2 gap-0 border border-slate-200 dark:border-white/10 rounded-2xl overflow-hidden shadow-lg dark:shadow-2xl relative z-10 flex-1 transition-colors">
                                
                                {{-- EDITOR KIRI --}}
                                <div class="bg-slate-50 dark:bg-[#151515] border-b xl:border-b-0 xl:border-r border-slate-200 dark:border-white/10 flex flex-col relative w-full xl:w-auto min-h-[500px] xl:min-h-[600px] transition-colors">
                                    
                                    {{-- LOCK OVERLAY --}}
                                    <div id="lockOverlay" class="hidden absolute inset-0 bg-white/95 dark:bg-[#050912]/95 backdrop-blur-md z-50 flex flex-col items-center justify-center text-center p-8 transition-colors border-2 border-emerald-500/20">
                                        <div class="w-20 h-20 bg-emerald-100 dark:bg-emerald-500/10 rounded-full flex items-center justify-center mb-6 border border-emerald-300 dark:border-emerald-500/50 shadow-[0_0_50px_rgba(16,185,129,0.2)] animate-bounce transition-colors">
                                            <svg class="w-10 h-10 text-emerald-600 dark:text-emerald-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                        </div>
                                        <h3 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white mb-2 tracking-tight transition-colors">ESTETIKA EDITORIAL VALID!</h3>
                                        <p class="text-xs sm:text-sm font-bold text-slate-500 dark:text-white/60 mb-8 max-w-xs transition-colors">Pemahaman tipografi digital Anda diakui profesional. Data modul telah tersinkronisasi server.</p>
                                        <button disabled class="w-full sm:w-auto px-8 py-3 rounded-full bg-slate-200 dark:bg-white/5 border border-slate-300 dark:border-white/10 text-slate-400 dark:text-white/30 text-[10px] sm:text-xs font-bold cursor-not-allowed uppercase tracking-widest transition-colors">Editor Terkunci</button>
                                    </div>

                                    <div class="bg-slate-100 dark:bg-[#1e1e1e] px-4 py-3 border-b border-slate-200 dark:border-white/5 flex justify-between items-center shrink-0 transition-colors">
                                        <span class="text-[10px] sm:text-xs text-slate-500 dark:text-white/50 font-mono font-bold transition-colors">Editorial-View.html</span>
                                        <button onclick="resetEditor()" class="text-[10px] text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 uppercase font-bold focus:outline-none bg-red-100 dark:bg-red-500/10 px-3 py-1.5 rounded shadow-sm border border-red-200 dark:border-red-500/20 active:scale-95 transition">Hapus Tinta</button>
                                    </div>
                                    
                                    <div id="codeEditor" class="flex-1 w-full border-b border-slate-200 dark:border-white/5 min-h-[250px] relative transition-colors"></div>

                                    <div class="p-5 bg-slate-50 dark:bg-[#0f141e] flex flex-col shrink-0 h-auto sm:h-[230px] transition-colors">
                                        <div class="flex justify-between items-center mb-3">
                                            <span class="text-[10px] uppercase font-bold text-slate-400 dark:text-white/30 tracking-widest transition-colors">Syarat Kontrak Desain</span>
                                            <span id="progressText" class="text-[9px] sm:text-[10px] font-mono font-bold text-cyan-700 dark:text-cyan-400 bg-cyan-100 dark:bg-cyan-900/30 px-2 py-0.5 rounded border border-cyan-200 dark:border-cyan-500/20 shadow-inner transition-colors">0/3 Terpenuhi</span>
                                        </div>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-[10px] sm:text-[11px] font-mono text-slate-600 dark:text-white/50 mb-4 flex-1 overflow-y-auto custom-scrollbar p-3 bg-white dark:bg-black/20 rounded-lg shadow-sm dark:shadow-inner border border-slate-200 dark:border-white/5 transition-colors">
                                            <div id="check-title-font" class="flex items-start gap-2.5"><span class="w-3.5 h-3.5 mt-0.5 rounded-full border border-slate-300 dark:border-white/20 flex items-center justify-center text-[8px] shrink-0 transition-colors"></span> <div><b class="block mb-1 text-slate-800 dark:text-white/80 transition-colors">Identitas Font (#news-title):</b> Hapus atribut aslinya, lalu wajibkan penggunaan huruf klasik (serif) dan ukuran ekstra jumbo 6xl.</div></div>
                                            <div id="check-title-weight" class="flex items-start gap-2.5"><span class="w-3.5 h-3.5 mt-0.5 rounded-full border border-slate-300 dark:border-white/20 flex items-center justify-center text-[8px] shrink-0 transition-colors"></span> <div><b class="block mb-1 text-slate-800 dark:text-white/80 transition-colors">Bobot Warna (#news-title):</b> Pastikan tinta tulisan tersebut ditebalkan sekokoh baja (black) dengan pigmen hitam pekat (slate-900).</div></div>
                                            <div id="check-body-style" class="flex items-start gap-2.5 sm:col-span-2 mt-2 pt-2 border-t border-slate-200 dark:border-white/5 transition-colors"><span class="w-3.5 h-3.5 mt-0.5 rounded-full border border-slate-300 dark:border-white/20 flex items-center justify-center text-[8px] shrink-0 transition-colors"></span> <div><b class="block mb-1 text-slate-800 dark:text-white/80 transition-colors">Tubuh Teks (#news-body):</b> Singkirkan pemusatan paragraf. Patok pada tepi kiri, longgarkan pernapasan spasi antar baris secara leluasa, dan redamkan kontras pigmen ke tingkat menengah (slate-500).</div></div>
                                        </div>
                                        <button id="submitExerciseBtn" onclick="submitExercise()" disabled class="w-full py-2.5 sm:py-3 rounded-lg bg-emerald-600 text-white font-bold text-[11px] sm:text-xs shadow-lg shadow-emerald-500/20 hover:bg-emerald-500 hover:-translate-y-0.5 transition-all cursor-not-allowed opacity-50 flex items-center justify-center gap-2 focus:outline-none active:scale-95">
                                            <span>Selesaikan Semua Syarat Pemeriksaan Dulu</span>
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                        </button>
                                    </div>
                                </div>

                                {{-- PREVIEW KANAN --}}
                                <div class="bg-slate-100 dark:bg-[#1e1e1e] flex flex-col relative overflow-hidden w-full xl:w-auto h-[400px] xl:h-auto transition-colors">
                                    <div class="bg-slate-200 dark:bg-[#2d2d2d] px-4 py-3 border-b border-slate-300 dark:border-white/5 flex items-center justify-between shrink-0 transition-colors">
                                        <span class="text-[10px] text-slate-500 dark:text-gray-400 font-mono font-bold transition-colors">Live Browser Rendering</span>
                                        <span class="text-[9px] sm:text-[10px] bg-emerald-100 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 px-2 py-0.5 rounded border border-emerald-200 dark:border-emerald-500/20 font-bold uppercase tracking-widest flex items-center gap-1.5 shadow-sm transition-colors">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse shadow-[0_0_5px_#10b981]"></span> Auto-Sync
                                        </span>
                                    </div>
                                    <div class="flex-1 bg-slate-100 dark:bg-gray-900 relative w-full h-full p-0 transition-colors">
                                        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.03] pointer-events-none mix-blend-overlay"></div>
                                        <iframe id="previewFrame" class="w-full h-full border-0 bg-transparent relative z-10 custom-scrollbar"></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                </article>

                {{-- FOOTER NAVIGATION --}}
                <div class="mt-32 pt-8 border-t border-slate-200 dark:border-white/10 flex flex-col sm:flex-row justify-between items-center gap-4 sm:gap-0 transition-colors">
                    <a href="{{ route('courses.layout-mgmt') ?? '#' }}" class="group flex items-center gap-4 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition w-full sm:w-auto justify-center sm:justify-start">
                        <div class="w-10 h-10 md:w-12 md:h-12 rounded-full border border-slate-200 dark:border-white/10 flex items-center justify-center bg-slate-100 dark:bg-transparent group-hover:bg-slate-200 dark:group-hover:bg-white/5 transition shrink-0">
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        </div>
                        <div class="text-center sm:text-left">
                            <div class="text-[10px] sm:text-xs uppercase tracking-widest font-bold opacity-50 mb-0.5">Sebelumnya</div>
                            <div class="font-bold text-xs md:text-sm line-clamp-1">Layouting CSS</div>
                        </div>
                    </a>
                    
                    <div id="nextChapterBtn" class="group flex items-center gap-4 text-right text-slate-500 cursor-not-allowed opacity-50 pointer-events-none transition-all duration-500 w-full sm:w-auto justify-center sm:justify-end flex-row-reverse sm:flex-row">
                        <div class="text-center sm:text-right">
                            <div id="nextLabel" class="text-[10px] sm:text-xs uppercase tracking-widest font-bold opacity-50 mb-0.5 text-rose-500 dark:text-rose-400 transition-colors">Berikutnya</div>
                            <div class="font-bold text-xs md:text-sm line-clamp-1">Background </div>
                        </div>
                        <div id="nextIcon" class="w-10 h-10 md:w-12 md:h-12 rounded-full border border-slate-200 dark:border-white/5 flex items-center justify-center bg-slate-100 dark:bg-white/5 shrink-0 transition-colors">
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/monaco-editor@0.45.0/min/vs/loader.js"></script>

<script>
    /* --- 1. CONFIGURATION (AJAX & DATABASE) --- */
    window.LESSON_IDS = [46, 47, 48, 49, 50, 51]; 
    let rawCompletedIds = {!! json_encode($completedLessonIds ?? []) !!};
    window.COMPLETED_IDS = rawCompletedIds.map(id => Number(id)); 
    let completedSet = new Set(window.COMPLETED_IDS);
    
    // Config Aktivitas Akhir Tipografi 3.1
    const ACTIVITY_LESSON_ID = 51; 
    let activityCompleted = completedSet.has(ACTIVITY_LESSON_ID);

    /* --- 2. INITIALIZATION --- */
    document.addEventListener('DOMContentLoaded', () => {
        initSidebarScroll();
        initVisualEffects();
        
        updateProgressUI(false); 
        
        initMonaco();
        
        if (activityCompleted) {
            lockActivityUI();
            unlockNextChapter();
        }

        initMasterObserver();
        
        document.querySelectorAll('.nav-item').forEach(item => {
            const targetId = parseInt(item.getAttribute('data-target').replace('#section-', ''));
            if(completedSet.has(targetId)) {
                markSidebarDone(targetId);
            }
        });
    });

    /* ==========================================
       LOGIKA KEMAJUAN PELAJARAN (PROGRESS BAR)
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
                const isActivity = navItem.querySelector('.sidebar-anchor')?.dataset.type === 'activity';
                if (isActivity) {
                    dot.outerHTML = `<svg class="w-4 h-4 text-cyan-600 dark:text-cyan-400 shrink-0 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>`;
                } else {
                    dot.outerHTML = `<svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400 shrink-0 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>`;
                }
            }
        }
    }

    /* ==========================================
       AJAX POST REQUEST KE DATABASE
       ========================================== */
    async function saveLessonToDB(lessonId) { 
        lessonId = Number(lessonId);
        if(completedSet.has(lessonId)) return; 

        try {
            const formData = new FormData();
            formData.append('lesson_id', lessonId);
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            const response = await fetch('{{ route("lesson.complete") }}', { 
                method: 'POST', 
                headers: { 
                    'X-CSRF-TOKEN': csrfToken || '',
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
       MASTER SCROLL OBSERVER
       ========================================== */
    function initMasterObserver() {
        const mainScroll = document.getElementById('mainScroll'); 
        const sections = document.querySelectorAll('.lesson-section');

        if (mainScroll && sections.length > 0) {
            const observerOptions = { 
                root: mainScroll, 
                rootMargin: "-10% 0px -60% 0px", 
                threshold: 0 
            };
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const targetId = entry.target.id;
                        const lessonId = Number(entry.target.dataset.lessonId);
                        const isActivity = entry.target.dataset.type === 'activity';

                        if (typeof highlightAnchor === 'function') {
                            highlightAnchor(targetId);
                        }

                        if (lessonId && !isActivity && !completedSet.has(lessonId)) {
                            saveLessonToDB(lessonId); 
                        }
                    }
                });
            }, observerOptions);

            sections.forEach(section => observer.observe(section));
        }
    }

    /* --- 4. FINISH LOGIC (ACTIVITY EXPERT MONACO) --- */
    let editor;
    const starterCode = `<div class="bg-slate-100 p-8 min-h-screen">
  <article class="max-w-2xl mx-auto bg-white p-8 rounded-xl shadow-lg border border-slate-200">
    
    <h1 id="news-title" class="font-sans text-xl font-normal text-slate-400 mb-4">
      Evolusi Tipografi Digital di Era Modern
    </h1>

    <div class="h-px bg-slate-200 my-4 w-1/4"></div>

    <p id="news-body" class="font-sans text-center leading-none text-slate-900">
      Tipografi bukan sekadar memilih jenis huruf, melainkan seni mengatur teks agar postur visualnya seimbang dan nyaman dibaca. Di era digital moderen yang berkecepatan tinggi, pengaturan mikrotipografi pernapasan spasi antar baris dan perataan tepi paragraf sangat menentukan secara absolut apakah pembaca akan memusatkan perhatian lebih lama atau pergi meninggalkan produk web komersial Anda dalam hitungan sepersekian detik.
    </p>

  </article>
</div>`;

    function initMonaco() {
        require.config({ paths: { vs: 'https://cdn.jsdelivr.net/npm/monaco-editor@0.45.0/min/vs' } });
        require(['vs/editor/editor.main'], function () {
            
            editor = monaco.editor.create(document.getElementById('codeEditor'), {
                value: starterCode, 
                language: 'html', 
                theme: 'vs-dark', // Force dark theme for cosmic design consistently
                fontSize: window.innerWidth < 768 ? 12 : 14,
                minimap: { enabled: false }, 
                automaticLayout: true, 
                padding: { top: 16, bottom: 16 }, 
                lineNumbers: 'on',
                scrollBeyondLastLine: false,
                wordWrap: 'on',
                formatOnPaste: true,
            });
            
            window.addEventListener('resize', () => { if(editor) editor.layout(); });

            updatePreview(starterCode);
            
            if (activityCompleted) {
                lockActivityUI();
            }
            
            editor.onDidChangeModelContent(() => {
                if(activityCompleted) return;
                const code = editor.getValue();
                updatePreview(code);
                validateCodeRegex(code);
            });
        });
    }

    function updatePreview(code) {
        const frame = document.getElementById('previewFrame');
        const content = `
        <!doctype html>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <script src="https://cdn.tailwindcss.com"><\/script>
            <style>
                body { margin: 0; padding: 0; background-color: transparent; }
                * { transition: all 0.3s ease-in-out; }
            </style>
        </head>
        <body class="w-full h-full flex items-start justify-center pt-8">
            ${code}
        </body>
        </html>`;
        frame.srcdoc = content;
    }

    function validateCodeRegex(code) {
        let passed = 0;
        
        const titleMatch = code.match(/id="news-title"[^>]*class="([^"]*)"/);
        const bodyMatch = code.match(/id="news-body"[^>]*class="([^"]*)"/);

        const titleCls = titleMatch ? titleMatch[1] : '';
        const bodyCls = bodyMatch ? bodyMatch[1] : '';

        const checks = [
            { id: 'check-title-font', valid: /\bfont-serif\b/.test(titleCls) && /\btext-6xl\b/.test(titleCls) && !/\bfont-sans\b/.test(titleCls) },
            { id: 'check-title-weight', valid: /\bfont-black\b/.test(titleCls) && /\btext-slate-900\b/.test(titleCls) },
            { id: 'check-body-style', valid: /\btext-left\b/.test(bodyCls) && /\bleading-relaxed\b/.test(bodyCls) && /\btext-slate-500\b/.test(bodyCls) && !/\btext-center\b/.test(bodyCls) }
        ];

        checks.forEach(c => {
            const el = document.getElementById(c.id);
            if (!el) return;
            const dot = el.querySelector('span'); 
            const textDiv = el.querySelector('div');
            
            if(c.valid) {
                textDiv.classList.add('text-green-500', 'dark:text-green-400');
                textDiv.querySelector('b').classList.add('text-green-600', 'dark:text-green-300');
                textDiv.querySelector('b').classList.remove('text-slate-800', 'dark:text-white/80');
                dot.innerHTML = '✓'; 
                dot.classList.remove('border-slate-300', 'dark:border-white/20');
                dot.classList.add('bg-emerald-500', 'border-transparent', 'text-white');
                passed++;
            } else {
                textDiv.classList.remove('text-green-500', 'dark:text-green-400');
                textDiv.querySelector('b').classList.remove('text-green-600', 'dark:text-green-300');
                textDiv.querySelector('b').classList.add('text-slate-800', 'dark:text-white/80');
                dot.innerHTML = ''; 
                dot.classList.remove('bg-emerald-500', 'border-transparent', 'text-white');
                dot.classList.add('border-slate-300', 'dark:border-white/20');
            }
        });

        document.getElementById('progressText').innerText = passed + '/3 Selesai';
        
        const btn = document.getElementById('submitExerciseBtn');
        if (passed === 3) {
            btn.disabled = false;
            btn.classList.remove('cursor-not-allowed', 'opacity-50');
            btn.innerHTML = '<span>Simpan Arsitektur Server & Lanjut</span><svg class="w-4 h-4 sm:w-5 sm:h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
        } else {
            btn.disabled = true;
            btn.classList.add('cursor-not-allowed', 'opacity-50');
            btn.innerHTML = '<span>Selesaikan Semua Syarat Test Dulu</span><svg class="w-4 h-4 sm:w-5 sm:h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>';
        }
    }

    function resetEditor() { 
        if(editor && !activityCompleted) { 
            editor.setValue(starterCode); 
            validateCodeRegex(starterCode); 
        } 
    }

    async function submitExercise() {
        const btn = document.getElementById('submitExerciseBtn');
        btn.innerHTML = '<span class="animate-pulse">Menyimpan Tinta Komando ke Database...</span>'; 
        btn.disabled = true;
        
        try {
            await saveLessonToDB(ACTIVITY_LESSON_ID); 
            activityCompleted = true;
            lockActivityUI();   
            unlockNextChapter(); 
        } catch(e) { 
            console.error(e); 
            btn.innerHTML = "Sistem Menolak Koneksi. Silakan Coba Tekan Lagi.";
            btn.disabled = false;
            btn.classList.add('shake'); setTimeout(() => btn.classList.remove('shake'), 500);
        }
    }

    function lockActivityUI() {
        document.getElementById('lockOverlay').classList.remove('hidden');
        if(editor) editor.updateOptions({ readOnly: true });
        
        const btn = document.getElementById('submitExerciseBtn'); 
        btn.innerText = "Sistem Arsitektur Modul Terkunci Otomatis"; 
        btn.disabled = true;
        btn.classList.remove('bg-emerald-600', 'hover:bg-emerald-500');
        btn.classList.add('bg-slate-200', 'dark:bg-slate-700', 'text-slate-500', 'dark:text-slate-400', 'cursor-not-allowed', 'shadow-none');
        
        if(editor && activityCompleted) {
            editor.setValue(`<div class="bg-slate-100 p-8 min-h-screen">\n  <article class="max-w-2xl mx-auto bg-white p-8 rounded-xl shadow-lg border border-slate-200">\n    \n    <h1 id="news-title" class="font-serif text-6xl font-black text-slate-900 mb-4">\n      Evolusi Tipografi Digital di Era Modern\n    </h1>\n\n    <div class="h-px bg-slate-200 my-4 w-1/4"></div>\n\n    <p id="news-body" class="font-sans text-left leading-relaxed text-slate-500">\n      Tipografi bukan sekadar memilih jenis huruf, melainkan seni mengatur teks agar postur visualnya seimbang dan nyaman dibaca. Di era digital moderen yang berkecepatan tinggi, pengaturan mikrotipografi pernapasan spasi antar baris dan perataan tepi paragraf sangat menentukan secara absolut apakah pembaca akan memusatkan perhatian lebih lama atau pergi meninggalkan produk web komersial Anda dalam hitungan sepersekian detik.\n    </p>\n\n  </article>\n</div>`);
            validateCodeRegex(editor.getValue());
        }
    }

    function unlockNextChapter() {
        const btn = document.getElementById('nextChapterBtn');
        if(btn) {
            btn.classList.remove('cursor-not-allowed', 'opacity-50', 'pointer-events-none', 'text-slate-500');
            btn.classList.add('text-cyan-600', 'dark:text-cyan-400', 'hover:text-cyan-700', 'dark:hover:text-white', 'cursor-pointer');
            
            document.getElementById('nextLabel').innerText = "Berikutnya";
            document.getElementById('nextLabel').classList.remove('opacity-50', 'text-rose-500', 'dark:text-rose-400');
            document.getElementById('nextLabel').classList.add('text-cyan-600', 'dark:text-cyan-400', 'opacity-100');
            
            const icon = document.getElementById('nextIcon');
            icon.innerHTML = `<svg class="w-5 h-5 md:w-6 md:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>`;
            icon.classList.remove('bg-slate-100', 'dark:bg-white/5');
            icon.classList.add('bg-cyan-100', 'dark:bg-cyan-500/10', 'border-cyan-300', 'dark:border-cyan-500/30', 'text-cyan-600', 'dark:text-cyan-400', 'shadow-md', 'dark:shadow-lg');
            
            btn.onclick = () => window.location.href = "{{ route('courses.backgrounds') ?? '#' }}"; 
        }
    }

    /* --- 5. SIMULATOR LOGIC INTERAKTIF --- */
    window.updateSimFont = function(btn, cls) {
        $('.btn-sim-1').removeClass('bg-cyan-600 text-white shadow-lg border-cyan-400').addClass('bg-slate-200 dark:bg-white/5 text-slate-600 dark:text-white/50 border-transparent hover:bg-slate-300 dark:hover:bg-white/10');
        $(btn).removeClass('bg-slate-200 dark:bg-white/5 text-slate-600 dark:text-white/50 border-transparent hover:bg-slate-300 dark:hover:bg-white/10').addClass('bg-cyan-600 text-white shadow-lg border-cyan-400');
        
        const el = document.getElementById('demo-font-title');
        const body = document.getElementById('demo-font-body');
        el.className = `text-xl font-bold text-slate-900 dark:text-white transition-all duration-300 mb-2 ${cls}`;
        body.className = `text-sm text-slate-500 dark:text-white/60 transition-all duration-300 leading-relaxed ${cls}`;

        const insight = document.getElementById('demo-font-insight');
        if(cls === 'font-sans') insight.innerHTML = "Gunakan <code class='font-bold'>font-sans</code> sebagai fondasi utama antarmuka (UI) karena sifatnya yang netral dan sangat mudah dibaca pada berbagai ukuran layar.";
        if(cls === 'font-serif') insight.innerHTML = "Nuansa <code class='font-bold'>font-serif</code> memancarkan kesan intelektual, formal, dan editorial bergengsi. Pilihan absolut untuk blog dan koran digital.";
        if(cls === 'font-mono') insight.innerHTML = "Pola mekanis <code class='font-bold'>font-mono</code> memaksa setiap huruf memakan lebar area yang sama. Esensial jika Anda merancang tabel angka atau papan kode blok.";
        
        insight.classList.remove('insight-box'); void insight.offsetWidth; insight.classList.add('insight-box');
    };

    window.updateSimSize = function(btn, size) {
        $('.btn-sim-2').removeClass('border-blue-500 bg-blue-50 dark:bg-blue-900/20 rounded-r').addClass('border-transparent hover:border-blue-400 bg-slate-50 dark:bg-white/5 rounded-r');
        $(btn).removeClass('border-transparent hover:border-blue-400 bg-slate-50 dark:bg-white/5').addClass('border-blue-500 bg-blue-50 dark:bg-blue-900/20');

        const el = document.getElementById('demo-size');
        el.className = `text-base text-slate-900 dark:text-white font-bold transition-all duration-300 bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800/50 p-3 rounded leading-normal ${size}`;

        const insight = document.getElementById('demo-size-insight');
        if(size === 'text-sm') insight.innerHTML = "Ukuran <code class='font-bold'>text-sm</code> secara otomatis merenggangkan barisnya agar teks mungil ini tetap nyaman disapu pandangan.";
        if(size === 'text-base') insight.innerHTML = "Keseimbangan absolut. Tailwind menyetel rasio paling sempurna pada <code class='font-bold'>text-base</code> untuk tulisan panjang (body text).";
        if(size === 'text-2xl') insight.innerHTML = "Menjelang ukuran <code class='font-bold'>text-2xl</code>, perhatikan barisnya ditarik menjadi sedikit lebih kompak dan mampat ke dalam.";
        if(size === 'text-4xl') insight.innerHTML = "Pada ukuran super jumbo <code class='font-bold'>text-4xl</code>, spasi baris wajib sangat rapat agar sepasang kalimat judul tidak terlihat retak/terpisah secara optikal.";
        insight.classList.remove('insight-box'); void insight.offsetWidth; insight.classList.add('insight-box');
    };

    window.updateSimWeight = function(btn, cls) {
        $('.btn-sim-3').removeClass('bg-sky-600 text-white border-sky-400 shadow-lg').addClass('bg-slate-200 dark:bg-white/5 text-slate-600 dark:text-white/50 hover:bg-slate-300 dark:hover:bg-white/10 border-transparent');
        $(btn).removeClass('bg-slate-200 dark:bg-white/5 text-slate-600 dark:text-white/50 hover:bg-slate-300 dark:hover:bg-white/10 border-transparent').addClass('bg-sky-600 text-white border-sky-400 shadow-lg');

        const el = document.getElementById('demo-weight');
        el.className = `text-4xl text-white transition-all duration-300 antialiased ${cls}`;

        const insight = document.getElementById('demo-weight-insight');
        if(cls === 'font-light') insight.innerHTML = "Efek <code class='font-bold'>font-light</code> menampilkan visual ramping bersahaja. Ideal untuk subjudul besar yang sengaja dirancang agar tidak berisik mencuri sorotan utama.";
        if(cls === 'font-normal') insight.innerHTML = "Kembali ke setelan netral alami. Paragraf panjang hampir selalu dikunci dengan <code class='font-bold'>font-normal</code> agar penglihatan pembaca tidak teraniaya.";
        if(cls === 'font-bold') insight.innerHTML = "Menghunjam seketika! <code class='font-bold'>font-bold</code> adalah perangkat tajam mutlak guna memaku nama data terpenting di tumpukan UI Anda.";
        if(cls === 'font-black') insight.innerHTML = "Massa dan bobot puncaknya <code class='font-bold'>font-black</code> menghasilkan daya dorong dominan. Spesial untuk tipografi bergaya sampul poster yang pendek.";
        insight.classList.remove('insight-box'); void insight.offsetWidth; insight.classList.add('insight-box');
    };

    window.updateSimTrackAlign = function(btn, type, cls) {
        $(`.btn-sim-4-${type}`).removeClass('bg-teal-600 text-white border-teal-400 shadow-md').addClass('bg-slate-200 dark:bg-white/5 text-slate-600 dark:text-white/50 hover:bg-slate-300 dark:hover:bg-white/10 border-transparent');
        $(btn).removeClass('bg-slate-200 dark:bg-white/5 text-slate-600 dark:text-white/50 hover:bg-slate-300 dark:hover:bg-white/10 border-transparent').addClass('bg-teal-600 text-white border-teal-400 shadow-md');

        const insight = document.getElementById('demo-track-align-insight');

        if(type === 'track') {
            const el = document.getElementById('demo-track');
            el.className = `text-3xl font-black text-slate-900 dark:text-white mb-4 uppercase transition-all duration-300 border-b border-slate-200 dark:border-white/10 pb-4 ${cls}`;
            
            if(cls === 'tracking-tighter') insight.innerHTML = "Kompresi spasi dengan <code class='font-bold'>tracking-tighter</code> mengeratkan susunan kata, mendongkrak teks Hero ini sehingga dirasa mengunci satu sama lain laksana tajuk majalah kenamaan.";
            if(cls === 'tracking-normal') insight.innerHTML = "Karakter asali yang wajar, polos, aman diterapkan secara meluas tanpa riak distorsi mental bagi otak yang memindainya.";
            if(cls === 'tracking-widest') insight.innerHTML = "Regangan longgar dari <code class='font-bold'>tracking-widest</code> melahirkan sirkulasi ruang nan lapang, memoles frasa judul seketika terasa sinematik dan berkelas tinggi (premium).";
        } else {
            const el = document.getElementById('demo-align');
            el.className = el.className.replace(/text-(left|center|right|justify)/g, '');
            el.classList.add(cls);

            if(cls === 'text-left') insight.innerHTML = "Formasi <code class='font-bold'>text-left</code> adalah landasan mutlak untuk tubuh artikel panjang karena mata manusia telah terlatih melacak tepi rata lurus di sisi kiri tulisan.";
            if(cls === 'text-center') insight.innerHTML = "Formasi simetris <code class='font-bold'>text-center</code>. Hindari penerapannya untuk naskah lebih dari tiga baris kalimat, karena titik awal pandang mata pengguna menjadi acak berpindah-pindah.";
            if(cls === 'text-justify') insight.innerHTML = "Awas jebakan pemula! Memaksa tepian sejajar dengan <code class='font-bold'>text-justify</code> menelurkan ruang spasial compang-camping di antara susunan kata, sangat merusak keindahan susunan pada perangkat layar kecil.";
        }
        insight.classList.remove('insight-box'); void insight.offsetWidth; insight.classList.add('insight-box');
    };

    window.updateSimTransDecor = function(btn, type, cls) {
        $(`.btn-sim-5-${type}`).removeClass('bg-indigo-600 text-white border-indigo-400 shadow-md').addClass('bg-slate-200 dark:bg-white/5 text-slate-600 dark:text-white/50 hover:bg-slate-300 dark:hover:bg-white/10 border-transparent');
        $(btn).removeClass('bg-slate-200 dark:bg-white/5 text-slate-600 dark:text-white/50 hover:bg-slate-300 dark:hover:bg-white/10 border-transparent').addClass('bg-indigo-600 text-white border-indigo-400 shadow-md');

        const el = document.getElementById('demo-trans-decor');
        const insight = document.getElementById('demo-trans-decor-insight');

        if(type === 'trans') {
            el.className = el.className.replace(/normal-case|uppercase|lowercase|capitalize/g, '');
            el.classList.add(cls === '' ? 'normal-case' : cls);
            
            if(cls === 'uppercase') insight.innerHTML = "Modifikasi absolut! Filter <code class='font-bold'>uppercase</code> melapisi antarmuka grafis di garis depan, membiarkan nilai rekaman mentah di database Anda bersih dan lugu selamanya.";
            if(cls === 'capitalize') insight.innerHTML = "Penyulap awalan! Kelas pintar <code class='font-bold'>capitalize</code> memaksa abjad muka dalam untaian kalimat berdiri menyambut secara kapital, membersihkan rutinitas melelahkan via JS.";
            if(cls === 'normal-case' || cls === '') insight.innerHTML = "Kalimat dirender sesuai dengan integritas format dari pangkalan data asalnya tanpa diintervensi oleh gaya CSS tambahan.";
        } else {
            el.className = el.className.replace(/underline[\w-]*|decoration-[\w-]*|line-through/g, '');
            if(cls !== '') {
                const parts = cls.split(' ');
                parts.forEach(p => el.classList.add(p));
            }

            if(cls.includes('underline-offset')) insight.innerHTML = "Lihat saat Anda meletikkan bilah <strong>Garis Modern</strong>. Tinta garis bawah longsor mengendur dari himpitan dasar huruf dan menghadirkan rongga segar memanjakan lekukan lengkung huruf g/y/p.";
            else if(cls.includes('decoration-wavy')) insight.innerHTML = "Menumpuk properti! Menjajarkan ornamen <code class='font-bold'>decoration-wavy</code> jamak dijumpai dalam sorotan modul cek-ejaan (spell-check) atau rancang seni moderen nan nakal berestetika eksentrik.";
            else insight.innerHTML = "Sebuah tautan tak harus selamanya dijerat ikatan tali bawah. Menghilangkannya memutihkan kanvas aplikasi menuju tatanan era UI yang jauh lebih bersih ketimbang situs purba era 90-an.";
        }
        insight.classList.remove('insight-box'); void insight.offsetWidth; insight.classList.add('insight-box');
    };

    /* --- 7. SCROLL SPY & SIDEBAR LOGIC --- */
    function highlightAnchor(id) {
        const anchors = document.querySelectorAll('.sidebar-anchor');

        anchors.forEach(a => {
            a.classList.remove('bg-slate-100', 'dark:bg-white/5', 'border-cyan-500', 'border-amber-500');
            a.classList.add('border-transparent');
            
            const dot = a.querySelector('.anchor-dot');
            const isActivity = a.dataset.type === 'activity';

            if(dot) dot.classList.remove('scale-125', 'shadow-sm', 'dark:shadow-[0_0_10px_#06b6d4]', 'dark:shadow-[0_0_10px_#f59e0b]', 'bg-cyan-500', 'dark:bg-cyan-400');
            
            if (isActivity) {
                if(dot) { dot.classList.remove('bg-amber-400'); dot.classList.add('bg-slate-300', 'dark:bg-slate-600'); }
            } else {
                if(dot) { dot.classList.remove('bg-cyan-500', 'dark:bg-cyan-400'); dot.classList.add('bg-slate-300', 'dark:bg-slate-600'); }
            }

            const text = a.querySelector('.anchor-text');
            if(text) { text.classList.remove('text-slate-900', 'dark:text-white', 'font-bold'); text.classList.add('text-slate-500', 'dark:text-slate-500'); }
        });

        const activeAnchor = document.querySelector(`.sidebar-anchor[data-target="${id}"]`);
        if (activeAnchor) {
            const isActivity = activeAnchor.dataset.type === 'activity';
            
            activeAnchor.classList.add('bg-slate-100', 'dark:bg-white/5');
            activeAnchor.classList.add(isActivity ? 'border-amber-500' : 'border-cyan-500');
            activeAnchor.classList.remove('border-transparent');
            
            const dot = activeAnchor.querySelector('.anchor-dot');
            if(dot) {
                dot.classList.remove('bg-slate-300', 'dark:bg-slate-600');
                if (isActivity) {
                    dot.classList.add('bg-amber-500', 'dark:bg-amber-400', 'scale-125', 'shadow-sm', 'dark:shadow-[0_0_10px_#f59e0b]');
                } else {
                    dot.classList.add('bg-cyan-600', 'dark:bg-cyan-400', 'scale-125', 'shadow-sm', 'dark:shadow-[0_0_10px_#06b6d4]');
                }
            }
            
            const text = activeAnchor.querySelector('.anchor-text');
            if(text) { text.classList.remove('text-slate-500', 'dark:text-slate-500'); text.classList.add('text-slate-900', 'dark:text-white', 'font-bold'); }
        }
    }

    function toggleAccordion(id) {
        const content = document.getElementById(id);
        const icon = document.getElementById('icon-' + id.replace('collapse-', ''));
        if (content) {
            const isClosed = content.style.maxHeight === '0px' || content.style.maxHeight === '';
            content.style.maxHeight = isClosed ? content.scrollHeight + "px" : "0px";
            content.style.opacity = isClosed ? "1" : "0";
            if(icon) {
                 if(isClosed) icon.classList.add('rotate-180', 'bg-slate-200', 'dark:bg-white/10');
                 else icon.classList.remove('rotate-180', 'bg-slate-200', 'dark:bg-white/10');
            }
        }
    }

    function scrollToSection(id) {
        const el = document.getElementById(id);
        if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
        highlightAnchor(id); 
    }

    function toggleMobileSidebar() {
        const sidebar = document.getElementById('courseSidebar');
        const overlay = document.getElementById('mobileOverlay');
        
        if (sidebar.classList.contains('mobile-open')) {
            sidebar.classList.remove('mobile-open');
            overlay.classList.remove('show');
        } else {
            sidebar.classList.add('mobile-open');
            overlay.classList.add('show');
        }
    }

    function initSidebarScroll(){
        const m = document.getElementById('mainScroll');
        const l = document.querySelectorAll('.accordion-content .nav-item');
        if(!m) return;
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