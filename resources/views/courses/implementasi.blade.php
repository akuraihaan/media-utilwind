@extends('layouts.landing')
@section('title','Implementasi Utility Classes')

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
        background: radial-gradient(800px circle at 20% 20%, rgba(99,102,241,.10), transparent 40%), 
                    radial-gradient(800px circle at 80% 80%, rgba(6,182,212,.10), transparent 40%); 
        animation: bgMove 20s ease-in-out infinite alternate; 
    }
    .dark #animated-bg {
        background: radial-gradient(800px circle at 20% 20%, rgba(99,102,241,.15), transparent 40%), 
                    radial-gradient(800px circle at 80% 80%, rgba(6,182,212,.15), transparent 40%); 
    }
    @keyframes bgMove{to{transform:scale(1.15)}}
    @keyframes shake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-5px); } 75% { transform: translateX(5px); } }
    .shake { animation: shake 0.4s ease-in-out; }
    
    /* SCROLLSPY SIDEBAR ACTIVE */
    .nav-item { display: flex; width: 100%; text-align: left; align-items: center; gap: 12px; padding: 10px 14px; font-size: 0.85rem; color: var(--text-muted); border-radius: 8px; transition: all 0.2s; position: relative; }
    .nav-item:hover { color: var(--text-main); background: var(--card-hover); }
    .nav-item.active { color: #06b6d4; background: rgba(6, 182, 212, 0.05); font-weight: 600; }
    .dot { width: 6px; height: 6px; border-radius: 50%; background: #94a3b8; transition: all 0.3s; }
    .dark .dot { background: #334155; }
    .nav-item.active .dot { background: #06b6d4; box-shadow: 0 0 8px #06b6d4; transform: scale(1.2); }
</style>

<div id="courseRoot" class="relative h-screen bg-adaptive text-adaptive font-sans overflow-hidden flex flex-col selection:bg-cyan-500/30 pt-20 transition-colors duration-500">

    {{-- BACKGROUND EFFECTS --}}
    <div class="fixed inset-0 -z-50 pointer-events-none">
        <div id="animated-bg" class="absolute inset-0 opacity-50 transition-opacity"></div>
        <div class="absolute top-[-20%] left-[-10%] w-[1000px] h-[1000px] bg-indigo-500/5 dark:bg-indigo-900/20 rounded-full blur-[150px] animate-pulse transition-colors"></div>
        <div class="absolute bottom-[-20%] right-[-10%] w-[800px] h-[800px] bg-cyan-500/5 dark:bg-cyan-900/20 rounded-full blur-[100px] transition-colors"></div>
        <canvas id="stars" class="absolute inset-0 pointer-events-none opacity-0 dark:opacity-100 transition-opacity duration-500"></canvas>
        <div id="noise-overlay" class="absolute inset-0 z-10 opacity-[0.02] dark:opacity-[0.03]"></div>
    </div>

    @include('layouts.partials.navbar')

    <div class="flex flex-1 overflow-hidden relative z-20">

        @include('layouts.partials.course-sidebar')

        <main id="mainScroll" class="flex-1 h-full overflow-y-auto scroll-smooth relative bg-transparent custom-scrollbar scroll-padding-top-24">
            
            {{-- STICKY HEADER & PROGRESS BAR --}}
            <div id="stickyHeader" class="sticky top-0 z-30 w-full backdrop-blur-2xl border-b border-adaptive px-8 py-4 flex items-center justify-between transition-colors duration-500" style="background-color: var(--glass-header);">
                <div class="flex items-center gap-4">
                    <div class="w-8 h-8 rounded-lg bg-cyan-500/10 dark:bg-cyan-500/20 border border-cyan-500/20 flex items-center justify-center font-bold text-xs text-cyan-600 dark:text-cyan-400 transition-colors">1.4</div>
                    <div>
                        <h1 class="text-sm font-bold text-heading transition-colors">Implementasi Utility Classes</h1>
                        <p class="text-[10px] text-muted transition-colors">Core Concepts & Architecture</p>
                    </div>
                </div>
                
                {{-- DYNAMIC PROGRESS BAR UI --}}
                <div class="flex items-center gap-3">
                    <div class="hidden sm:block w-32 h-1.5 bg-slate-200 dark:bg-white/10 rounded-full overflow-hidden transition-colors">
                        <div id="topProgressBar" class="h-full bg-gradient-to-r from-cyan-400 to-blue-500 w-0 transition-all duration-500 shadow-[0_0_10px_rgba(6,182,212,0.5)]"></div>
                    </div>
                    <span id="progressLabelTop" class="text-cyan-600 dark:text-cyan-400 font-bold text-xs transition-colors">0%</span>
                </div>
            </div>

            <div class="p-6 lg:p-16 max-w-5xl mx-auto pb-40">
                
                {{-- TUJUAN PEMBELAJARAN --}}
                <div class="mb-24">
                    <h3 class="text-xl font-bold text-heading mb-6 flex items-center gap-2 transition-colors">
                        <svg class="w-5 h-5 text-cyan-500 dark:text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                        Tujuan Pembelajaran
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="card-adaptive border border-adaptive p-6 rounded-xl flex items-start gap-4 hover:border-cyan-500/30 transition group h-full">
                            <div class="w-10 h-10 rounded-lg bg-cyan-100 dark:bg-cyan-900/20 text-cyan-600 dark:text-cyan-400 flex items-center justify-center shrink-0 font-bold text-sm border border-cyan-200 dark:border-cyan-500/10 transition-colors">1</div>
                            <div>
                                <h4 class="text-sm font-bold text-heading mb-2 group-hover:text-cyan-500 dark:group-hover:text-cyan-400 transition-colors">Filosofi Utility-First</h4>
                                <p class="text-xs text-muted leading-relaxed transition-colors">Memahami pola penamaan utilitas dan sistem grid matematis untuk memastikan konsistensi tata letak visual.</p>
                            </div>
                        </div>

                        <div class="card-adaptive border border-adaptive p-6 rounded-xl flex items-start gap-4 hover:border-indigo-500/30 transition group h-full">
                            <div class="w-10 h-10 rounded-lg bg-indigo-100 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0 font-bold text-sm border border-indigo-200 dark:border-indigo-500/10 transition-colors">2</div>
                            <div>
                                <h4 class="text-sm font-bold text-heading mb-2 group-hover:text-indigo-500 dark:group-hover:text-indigo-400 transition-colors">Komposisi Kode</h4>
                                <p class="text-xs text-muted leading-relaxed transition-colors">Membangun elemen antarmuka yang reaktif terhadap interaksi kursor langsung dari HTML.</p>
                            </div>
                        </div>

                        <div class="card-adaptive border border-adaptive p-6 rounded-xl flex items-start gap-4 hover:border-fuchsia-500/30 transition group h-full">
                            <div class="w-10 h-10 rounded-lg bg-fuchsia-100 dark:bg-fuchsia-900/20 text-fuchsia-600 dark:text-fuchsia-400 flex items-center justify-center shrink-0 font-bold text-sm border border-fuchsia-200 dark:border-fuchsia-500/10 transition-colors">3</div>
                            <div>
                                <h4 class="text-sm font-bold text-heading mb-2 group-hover:text-fuchsia-500 dark:group-hover:text-fuchsia-400 transition-colors">Ekstraksi Komponen</h4>
                                <p class="text-xs text-muted leading-relaxed transition-colors">Menghindari repetisi dan duplikasi kode HTML dengan memanfaatkan strategi arsitektur komponen modular.</p>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-cyan-100 to-indigo-100 dark:from-cyan-900/10 dark:to-indigo-900/10 border border-cyan-300 dark:border-cyan-500/20 p-6 rounded-xl flex items-start gap-4 hover:shadow-[0_0_30px_rgba(6,182,212,0.15)] transition group h-full col-span-1 md:col-span-3 cursor-default">
                            <div class="w-10 h-10 rounded-lg bg-white/50 dark:bg-white/10 text-cyan-700 dark:text-white flex items-center justify-center shrink-0 font-bold text-lg border border-white/10 shadow-sm dark:shadow-none transition-colors">🏁</div>
                            <div>
                                <h4 class="text-sm font-bold text-cyan-900 dark:text-white mb-2 transition-colors">Final Mission: Code Refactoring</h4>
                                <p class="text-xs text-cyan-800 dark:text-white/60 leading-relaxed max-w-2xl transition-colors">Lakukan refaktor pada komponen statis agar memenuhi standar utility-first dan lolos verifikasi sistem.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <article class="space-y-40">
                    
                    {{-- LESSON 16 --}}
                    <section id="section-16" class="lesson-section scroll-mt-32" data-lesson-id="16">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-cyan-500 pl-6">
                                <span class="text-cyan-600 dark:text-cyan-400 font-mono text-xs uppercase tracking-widest transition-colors">Lesson 1.4.1</span>
                                <h2 class="text-4xl lg:text-5xl font-black text-heading leading-[1.1] transition-colors">
                                    Filosofi Utilitas & <br> 
                                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-500 to-indigo-600 dark:from-cyan-400 dark:to-indigo-500">Sistem Desain</span>
                                </h2>
                            </div>
                            
                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-heading flex items-center gap-2 transition-colors"><span class="w-6 h-6 rounded bg-cyan-500 dark:bg-cyan-600 flex items-center justify-center text-[10px] text-white">A</span> Pergeseran Paradigma</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-lg leading-relaxed space-y-4 text-justify transition-colors">
                                    <p>CSS konvensional sering berpusat pada komponen statis. Framework lawas menyediakan kelas siap pakai seperti <code>.card</code> atau <code>.btn</code>. Cara ini memang mempercepat proses awal, tetapi menjadi kaku ketika ada kebutuhan modifikasi kecil. Pengembang biasanya harus menulis kode CSS tambahan untuk menimpa gaya bawaan, yang berujung pada penumpukan kode dan konflik tata letak.</p>
                                    <p>Tailwind mengubah paradigma ini melalui konsep Utility-First. Daripada memberikan komponen utuh, Tailwind menyediakan kelas utilitas spesifik seperti <strong class="text-cyan-600 dark:text-cyan-400">bg-blue-500</strong> untuk warna, <strong class="text-cyan-600 dark:text-cyan-400">px-4 py-2</strong> untuk ruang padding, dan <strong class="text-cyan-600 dark:text-cyan-400">rounded-md</strong> untuk membulatkan sudut. Anda merakit elemen langsung di HTML secara bebas tanpa harus menulis atau mengelola file CSS kustom terpisah.</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-heading flex items-center gap-2 transition-colors"><span class="w-6 h-6 rounded bg-indigo-500 dark:bg-indigo-600 flex items-center justify-center text-[10px] text-white">B</span> Konsistensi Sistem Penamaan</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-lg leading-relaxed space-y-4 text-justify transition-colors">
                                    <p>Banyak pengembang ragu menggunakan Tailwind karena merasa harus menghafal banyak nama kelas. Pada kenyataannya, Tailwind menggunakan pola penamaan yang dapat diprediksi. Pola ini konsisten mengadopsi format Properti-Nilai. Contohnya, properti <code>text-align: center</code> ditulis sebagai <strong class="text-cyan-600 dark:text-cyan-400">text-center</strong>, dan ukuran <code>font-size: 1.125rem</code> ditulis sebagai <strong class="text-cyan-600 dark:text-cyan-400">text-lg</strong>. Pola ini membuat proses pengembangan antarmuka menjadi sangat cepat.</p>
                                    <p>Tailwind juga menggunakan pendekatan desain berbasis batasan (Constraint-Based Design). Sistem ini mencegah penggunaan angka acak seperti margin 13px atau padding 17px. Sebagai gantinya, ukuran mengacu pada skala kelipatan 4px atau 0.25rem. Disiplin ukuran ini memastikan antarmuka selalu proporsional dan rapi.</p>
                                </div>
                            </div>

                            <div class="sim-bg-adaptive border border-adaptive rounded-xl overflow-hidden shadow-xl dark:shadow-2xl p-8 relative transition-colors mt-8">
                                <h4 class="text-xs font-bold text-muted uppercase mb-4 text-center transition-colors">Simulator: Skala 4px System</h4>
                                
                                {{-- KOTAK INSTRUKSI SIMULATOR 1 --}}
                                <div class="bg-cyan-50 dark:bg-cyan-900/20 border border-cyan-200 dark:border-cyan-500/30 rounded-lg p-4 mb-8 text-sm text-cyan-800 dark:text-cyan-300 relative z-10 transition-colors">
                                    <p class="font-bold flex items-center gap-2 mb-2">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Panduan Simulasi
                                    </p>
                                    <ol class="list-decimal pl-5 space-y-1">
                                        <li>Ubah posisi Slider untuk mengatur nilai kelas padding <code>p-N</code>.</li>
                                        <li>Perhatikan bagaimana angka N otomatis dikalikan 4px untuk menghasilkan ukuran ruang visual yang sesungguhnya.</li>
                                    </ol>
                                </div>

                                <div class="flex flex-col md:flex-row items-center gap-10 relative z-10">
                                    <div class="w-full md:w-1/2 space-y-6">
                                        <div class="space-y-2">
                                            <div class="flex justify-between text-xs font-bold font-mono transition-colors">
                                                <span class="text-cyan-600 dark:text-cyan-400">CLASS: PADDING</span>
                                                <span id="scale-p-val" class="text-slate-800 dark:text-white">p-6</span>
                                            </div>
                                            <input type="range" min="0" max="16" step="1" value="6" class="w-full accent-cyan-500 cursor-pointer" oninput="updateScale('p', this.value)">
                                        </div>
                                        <div class="p-4 bg-slate-50 dark:bg-white/5 rounded-lg border border-adaptive shadow-inner dark:shadow-none transition-colors">
                                            <p class="text-xs font-mono text-slate-500 dark:text-white/60">Perhitungan Piksel:<br><br><span id="scale-formula" class="text-slate-800 dark:text-white font-bold text-sm block">6 x 4px = <span class="text-cyan-600 dark:text-cyan-400">24px</span> (1.5rem)</span></p>
                                        </div>
                                    </div>
                                    <div class="w-full md:w-1/2 flex items-center justify-center h-48 bg-slate-100 dark:bg-[#0b0f19] rounded-xl border border-adaptive relative overflow-hidden transition-colors shadow-inner">
                                        <div id="scale-target" class="bg-indigo-600 text-white font-bold rounded-xl shadow-lg transition-all duration-300 transform border border-indigo-400 dark:border-white/20 flex items-center justify-center">
                                            Dynamic Box
                                        </div>
                                    </div>
                                </div>

                                {{-- KESIMPULAN SIMULASI 1 --}}
                                <div class="mt-8 bg-gradient-to-r from-cyan-50 to-transparent dark:from-cyan-900/20 dark:to-transparent border-l-4 border-cyan-500 p-5 rounded-r-xl relative z-10 transition-colors">
                                    <h5 class="text-sm font-bold text-cyan-700 dark:text-cyan-400 mb-2 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> 
                                        Kesimpulan
                                    </h5>
                                    <p class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed text-justify">
                                        Penggunaan sistem desain berbasis batasan ini memastikan keseragaman jarak antar elemen. Konsistensi skala matematis mencegah desainer atau pengembang membuat tata letak yang tidak proporsional.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 17 --}}
                    <section id="section-17" class="lesson-section scroll-mt-32" data-lesson-id="17">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-indigo-500 pl-6">
                                <span class="text-indigo-600 dark:text-indigo-400 font-mono text-xs uppercase tracking-widest transition-colors">Lesson 1.4.2</span>
                                <h2 class="text-4xl lg:text-5xl font-black text-heading leading-[1.1] transition-colors">
                                    Komposisi & <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-purple-600 dark:from-indigo-400 dark:to-purple-500">Interaktivitas</span>
                                </h2>
                            </div>  

                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-heading flex items-center gap-2 transition-colors"><span class="w-6 h-6 rounded bg-indigo-500 dark:bg-indigo-600 flex items-center justify-center text-[10px] text-white">A</span> Harmoni Layering Komponen</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-lg leading-relaxed space-y-4 text-justify transition-colors">
                                    <p>Kelas utilitas tunggal terlihat sederhana, namun penggabungannya mampu membentuk komponen antarmuka yang lengkap dan fungsional. Proses ini mirip dengan menyusun fondasi bangunan secara berlapis.</p>
                                    <p>Misalnya, pembuatan sebuah tombol panggilan aksi (CTA). Pertama, kita gunakan <strong class="text-indigo-600 dark:text-indigo-400">flex items-center justify-center</strong> untuk menempatkan teks di tengah. Selanjutnya, ruang di dalam tombol diatur melalui <strong class="text-indigo-600 dark:text-indigo-400">px-6 py-3</strong>. Latar dan warna teks dibentuk menggunakan <strong class="text-indigo-600 dark:text-indigo-400">bg-slate-800 text-white</strong>. Tahap akhir melibatkan pembulatan sudut dengan <strong class="text-indigo-600 dark:text-indigo-400">rounded-xl</strong> serta efek bayangan elevasi melalui <strong class="text-indigo-600 dark:text-indigo-400">shadow-lg</strong>. Seluruh proses perakitan komponen kompleks ini berhasil direalisasikan di dalam atribut kelas HTML.</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-heading flex items-center gap-2 transition-colors"><span class="w-6 h-6 rounded bg-purple-500 dark:bg-purple-600 flex items-center justify-center text-[10px] text-white">B</span> Locality of Behavior (LoB)</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-lg leading-relaxed space-y-4 text-justify transition-colors">
                                    <p>Prinsip <strong>Locality of Behavior (LoB)</strong> menekankan bahwa perilaku suatu elemen harus bisa dipahami sepenuhnya hanya dengan memeriksa kode elemen tersebut. Pada pendekatan CSS tradisional, perilaku seperti status kursor melayang (hover) harus diatur dalam file terpisah, yang memperpanjang waktu pelacakan bug.</p>
                                    <p>Tailwind menarik logika reaktif ini langsung ke HTML melalui penggunaan modifikator status (State Modifiers). Cukup tambahkan prefiks kondisi di depan utilitas utama, seperti <strong class="text-indigo-600 dark:text-indigo-400">hover:bg-blue-600</strong> untuk mengubah warna dasar saat dilewati kursor, atau <strong class="text-indigo-600 dark:text-indigo-400">active:scale-95</strong> untuk mengecilkan elemen ketika ditekan. Pendekatan deklaratif ini menjaga kode tetap transparan tanpa ketergantungan pada file eksternal.</p>
                                </div>
                            </div>

                            <div class="sim-bg-adaptive border border-adaptive rounded-xl overflow-hidden shadow-xl dark:shadow-2xl p-8 relative transition-colors mt-8">
                                <h4 class="text-xs font-bold text-muted uppercase mb-6 text-center transition-colors">Simulator: Interaktivitas DOM States</h4>
                                
                                {{-- KOTAK INSTRUKSI SIMULATOR 2 --}}
                                <div class="bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-500/30 rounded-lg p-4 mb-8 text-sm text-indigo-800 dark:text-indigo-300 relative z-10 transition-colors">
                                    <p class="font-bold flex items-center gap-2 mb-2">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Panduan Simulasi
                                    </p>
                                    <ol class="list-decimal pl-5 space-y-1">
                                        <li>Pilih kotak centang di panel kiri untuk menerapkan modifikator seperti <code class="bg-indigo-100 dark:bg-indigo-800/50 px-1 rounded">hover:</code> atau <code class="bg-indigo-100 dark:bg-indigo-800/50 px-1 rounded">active:</code> pada komponen.</li>
                                        <li>Lakukan interaksi langsung dengan komponen tombol di sebelah kanan.</li>
                                    </ol>
                                </div>

                                <div class="flex flex-col md:flex-row items-center gap-10 relative z-10">
                                    <div class="w-full md:w-1/2 space-y-3">
                                        <label class="flex items-center justify-between p-4 rounded-lg bg-slate-50 dark:bg-white/5 border border-adaptive cursor-pointer hover:bg-slate-100 dark:hover:bg-white/10 transition-colors group shadow-sm dark:shadow-none">
                                            <span class="text-xs font-mono text-cyan-600 dark:text-cyan-300 font-bold transition-colors">hover:scale-110</span>
                                            <input type="checkbox" onchange="toggleStateClass('hover')" class="accent-cyan-500 w-4 h-4 cursor-pointer">
                                        </label>
                                        
                                        <label class="flex items-center justify-between p-4 rounded-lg bg-slate-50 dark:bg-white/5 border border-adaptive cursor-pointer hover:bg-slate-100 dark:hover:bg-white/10 transition-colors group shadow-sm dark:shadow-none">
                                            <span class="text-xs font-mono text-fuchsia-600 dark:text-fuchsia-300 font-bold transition-colors">active:bg-fuchsia-600</span>
                                            <input type="checkbox" onchange="toggleStateClass('active')" class="accent-fuchsia-500 w-4 h-4 cursor-pointer">
                                        </label>
                                        
                                        <label class="flex items-center justify-between p-4 rounded-lg bg-slate-50 dark:bg-white/5 border border-adaptive cursor-pointer hover:bg-slate-100 dark:hover:bg-white/10 transition-colors group shadow-sm dark:shadow-none">
                                            <span class="text-xs font-mono text-amber-600 dark:text-yellow-400 font-bold transition-colors">focus:ring-4</span>
                                            <input type="checkbox" onchange="toggleStateClass('focus')" class="accent-amber-500 dark:accent-yellow-500 w-4 h-4 cursor-pointer">
                                        </label>
                                    </div>
                                    <div class="w-full md:w-1/2 flex items-center justify-center h-56 bg-slate-100 dark:bg-[#0b0f19] rounded-xl border border-adaptive relative group transition-colors shadow-inner">
                                        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-10 dark:opacity-20"></div>
                                        <button id="state-target" class="bg-indigo-600 text-white font-bold py-3 px-8 rounded-xl transition-all duration-300 outline-none shadow-xl relative z-10 border border-indigo-400 dark:border-transparent">
                                            Interact Component
                                        </button>
                                    </div>
                                </div>

                                {{-- KESIMPULAN SIMULASI 2 --}}
                                <div class="mt-8 bg-gradient-to-r from-indigo-50 to-transparent dark:from-indigo-900/20 dark:to-transparent border-l-4 border-indigo-500 p-5 rounded-r-xl relative z-10 transition-colors">
                                    <h5 class="text-sm font-bold text-indigo-700 dark:text-indigo-400 mb-2 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> 
                                        Kesimpulan
                                    </h5>
                                    <p class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed text-justify">
                                        Prinsip Locality of Behavior (LoB) mempercepat waktu pelacakan bug. Dengan menyematkan perilaku interaktif secara berdekatan pada tag HTML aslinya, Anda tidak perlu lagi berpindah file untuk memodifikasi reaksi kursor pada komponen web.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 18 --}}
                    <section id="section-18" class="lesson-section scroll-mt-32" data-lesson-id="18">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-fuchsia-500 pl-6">
                                <span class="text-fuchsia-600 dark:text-fuchsia-400 font-mono text-xs uppercase tracking-widest transition-colors">Lesson 1.4.3</span>
                                <h2 class="text-4xl lg:text-5xl font-black text-heading leading-[1.1] transition-colors">
                                    Arsitektur Kode & <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-fuchsia-500 to-rose-600 dark:from-fuchsia-400 dark:to-rose-500">Mitos Duplikasi</span>
                                </h2>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-heading flex items-center gap-2 transition-colors"><span class="w-6 h-6 rounded bg-fuchsia-500 dark:bg-fuchsia-600 flex items-center justify-center text-[10px] text-white">A</span> Mitos HTML yang Panjang</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-lg leading-relaxed space-y-4 text-justify transition-colors">
                                    <p>Salah satu kritik utama terhadap Tailwind adalah atribut HTML yang menjadi sangat panjang dan terkesan kotor. Secara visual, blok seperti <code>&lt;button class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700..."&gt;</code> mungkin terlihat kurang rapi jika dibandingkan dengan penamaan singkat seperti <code>class="btn-primary"</code>.</p>
                                    <p>Namun, dalam pengelolaan aplikasi perangkat lunak yang kompleks, masalah utamanya bukanlah panjang karakter HTML, melainkan <strong>Duplikasi Kode</strong>. Apabila Anda secara manual menyalin atribut panjang tersebut pada 50 tombol di file yang berbeda, perubahan desain ke depannya akan sangat menyulitkan. Ini jelas merupakan pelanggaran terhadap prinsip pengkodean DRY (Don't Repeat Yourself).</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-heading flex items-center gap-2 transition-colors"><span class="w-6 h-6 rounded bg-rose-500 dark:bg-rose-600 flex items-center justify-center text-[10px] text-white">B</span> Ekstraksi Komponen Modular</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-lg leading-relaxed space-y-4 text-justify transition-colors">
                                    <p>Untuk mengatasi masalah duplikasi tersebut, Tailwind merekomendasikan teknik <strong>Ekstraksi Komponen (Component Extraction)</strong> melalui ekosistem bawaan framework web Anda, seperti komponen Blade di Laravel atau komponen React/Vue.</p>
                                    <p>Mekanisme ini memastikan pemusatan pengaturan logika pada satu file (*Single Source of Truth*). Anda cukup membuat sebuah komponen utama, misalnya <code>&lt;x-button&gt;</code>, yang berisikan seluruh daftar kelas utilitas. Saat komponen ini dipakai berulang kali, setiap perubahan warna atau ukuran hanya perlu diedit sekali pada file komponen inti, yang secara otomatis akan diperbarui ke seluruh antarmuka aplikasi.</p>
                                </div>
                            </div>

                            <div class="sim-bg-adaptive border border-adaptive rounded-xl overflow-hidden shadow-xl dark:shadow-2xl p-8 relative transition-colors mt-8">
                                <h4 class="text-xs font-bold text-muted uppercase mb-6 text-center transition-colors">Simulator: Component Morphology</h4>
                                
                                {{-- KOTAK INSTRUKSI SIMULATOR 3 --}}
                                <div class="bg-fuchsia-50 dark:bg-fuchsia-900/20 border border-fuchsia-200 dark:border-fuchsia-500/30 rounded-lg p-4 mb-8 text-sm text-fuchsia-800 dark:text-fuchsia-300 relative z-10 transition-colors">
                                    <p class="font-bold flex items-center gap-2 mb-2">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Panduan Simulasi
                                    </p>
                                    <ol class="list-decimal pl-5 space-y-1">
                                        <li>Lihat barisan struktur atribut HTML yang menduplikasi susunan utilitas secara berulang pada panel kiri.</li>
                                        <li>Pilih <strong>Ekstrak ke Modul Pusat</strong>.</li>
                                        <li>Perhatikan perubahan di mana duplikasi teks disederhanakan melalui pemanggilan abstraksi komponen modular (contoh: <code>&lt;x-button&gt;</code>).</li>
                                    </ol>
                                </div>

                                <div class="grid md:grid-cols-2 gap-0 relative rounded-xl overflow-hidden border border-adaptive h-48 bg-slate-50 dark:bg-black/20 shadow-inner transition-colors z-10">
                                    {{-- Messy HTML --}}
                                    <div class="p-6 flex flex-col items-center justify-center gap-2 border-r border-adaptive transition-all duration-700" id="morph-messy">
                                        <div class="text-[10px] text-rose-600 dark:text-red-400 font-mono font-bold bg-rose-100 dark:bg-red-900/10 px-3 py-1.5 rounded-lg border border-rose-200 dark:border-red-500/20 w-full text-center opacity-100 transition-colors shadow-sm dark:shadow-none">&lt;button class="p-4 flex bg-blue-600 text-white..."&gt;</div>
                                        <div class="text-[10px] text-rose-600 dark:text-red-400 font-mono font-bold bg-rose-100 dark:bg-red-900/10 px-3 py-1.5 rounded-lg border border-rose-200 dark:border-red-500/20 w-full text-center opacity-100 transition-colors shadow-sm dark:shadow-none delay-100">&lt;button class="p-4 flex bg-blue-600 text-white..."&gt;</div>
                                        <div class="text-[10px] text-rose-600 dark:text-red-400 font-mono font-bold bg-rose-100 dark:bg-red-900/10 px-3 py-1.5 rounded-lg border border-rose-200 dark:border-red-500/20 w-full text-center opacity-100 transition-colors shadow-sm dark:shadow-none delay-200">&lt;button class="p-4 flex bg-blue-600 text-white..."&gt;</div>
                                    </div>

                                    {{-- Clean Component --}}
                                    <div class="p-6 flex flex-col items-center justify-center gap-2 transition-all duration-700 relative">
                                        <div id="morph-clean" class="absolute inset-0 flex flex-col items-center justify-center gap-2 opacity-0 scale-90 transition-all duration-500">
                                            <div class="text-[11px] text-emerald-700 dark:text-emerald-400 font-mono font-bold bg-emerald-100 dark:bg-emerald-900/10 px-4 py-2 rounded-lg border border-emerald-300 dark:border-emerald-500/20 w-3/4 text-center shadow-sm dark:shadow-none transition-colors">&lt;x-button /&gt;</div>
                                            <div class="text-[11px] text-emerald-700 dark:text-emerald-400 font-mono font-bold bg-emerald-100 dark:bg-emerald-900/10 px-4 py-2 rounded-lg border border-emerald-300 dark:border-emerald-500/20 w-3/4 text-center shadow-sm dark:shadow-none transition-colors">&lt;x-button /&gt;</div>
                                            <div class="text-[11px] text-emerald-700 dark:text-emerald-400 font-mono font-bold bg-emerald-100 dark:bg-emerald-900/10 px-4 py-2 rounded-lg border border-emerald-300 dark:border-emerald-500/20 w-3/4 text-center shadow-sm dark:shadow-none transition-colors">&lt;x-button /&gt;</div>
                                        </div>
                                    </div>

                                    <button onclick="triggerMorph()" class="absolute bottom-4 right-4 px-4 py-2 bg-fuchsia-600 text-white text-[10px] font-bold rounded-lg shadow-lg hover:bg-fuchsia-500 transition z-10">
                                        Ekstrak ke Modul Pusat
                                    </button>
                                </div>
                                
                                {{-- KESIMPULAN SIMULASI 3 --}}
                                <div class="mt-8 bg-gradient-to-r from-fuchsia-50 to-transparent dark:from-fuchsia-900/20 dark:to-transparent border-l-4 border-fuchsia-500 p-5 rounded-r-xl relative z-10 transition-colors">
                                    <h5 class="text-sm font-bold text-fuchsia-700 dark:text-fuchsia-400 mb-2 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> 
                                        Kesimpulan
                                    </h5>
                                    <p class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed text-justify">
                                        Mengekstrak kelas utilitas ke dalam satu file komponen utama meminimalisasi duplikasi dan menjaga kebersihan arsitektur kode antarmuka. Dengan memusatkan elemen, pengelolaan dan pembaruan aplikasi web ke depannya menjadi sangat terstruktur.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 19: ACTIVITY FINAL (CODE REFACTOR) --}}
                    <section id="section-19" class="lesson-section scroll-mt-32 pt-10 border-t border-adaptive transition-colors" data-lesson-id="19" data-type="activity">
                        <div class="relative rounded-[2.5rem] sim-bg-adaptive border border-adaptive p-6 md:p-10 overflow-hidden shadow-xl dark:shadow-2xl group hover:border-cyan-500/30 transition-all duration-500">
                            
                            <div class="absolute -top-24 -right-24 w-64 h-64 bg-cyan-600/10 dark:bg-cyan-600/20 blur-[100px] rounded-full pointer-events-none transition-colors"></div>

                            <div class="flex flex-col md:flex-row items-start md:items-center gap-6 mb-8 relative z-10">
                                <div class="p-4 bg-gradient-to-br from-cyan-600 to-indigo-800 rounded-2xl text-white shadow-lg shadow-cyan-500/20 shrink-0">
                                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                                </div>
                                <div>
                                    <div class="flex items-center gap-3 mb-1">
                                        <h2 class="text-3xl font-black text-heading tracking-tight transition-colors">Mission: Code Refactoring</h2>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-cyan-100 dark:bg-cyan-500/20 text-cyan-700 dark:text-cyan-400 border border-cyan-200 dark:border-cyan-500/30 uppercase tracking-wider transition-colors">Expert Validation</span>
                                    </div>
                                    <p class="text-slate-600 dark:text-cyan-200/60 text-sm leading-relaxed max-w-2xl transition-colors mt-2 text-justify">
                                        Selesaikan perbaikan pada kerangka komponen tombol statis di editor bawah. Tulis dan terapkan utilitas CSS Tailwind yang valid sesuai dengan syarat pada checklist untuk mengubahnya menjadi elemen interaktif modern.
                                    </p>
                                </div>
                            </div>

                            <div class="grid lg:grid-cols-2 gap-0 border border-adaptive rounded-2xl overflow-hidden min-h-[550px] shadow-2xl transition-colors relative z-10">
                                
                                {{-- LEFT PANEL: MONACO EDITOR & CLUES --}}
                                <div class="bg-slate-50 dark:bg-[#151515] border-r border-adaptive overflow-hidden relative flex flex-col transition-colors min-h-[550px]">
                                    
                                    {{-- LOCK OVERLAY (Muncul saat sukses) --}}
                                    <div id="lockOverlay" class="hidden absolute inset-0 bg-white/90 dark:bg-[#050912]/95 backdrop-blur-md z-50 flex flex-col items-center justify-center text-center p-8 transition-colors">
                                        <div class="w-24 h-24 bg-emerald-100 dark:bg-emerald-500/10 rounded-full flex items-center justify-center mb-6 border border-emerald-300 dark:border-emerald-500/50 shadow-[0_0_50px_rgba(16,185,129,0.2)] animate-bounce transition-colors">
                                            <svg class="w-12 h-12 text-emerald-600 dark:text-emerald-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                        </div>
                                        <h3 class="text-3xl font-black text-slate-900 dark:text-white mb-2 tracking-tight transition-colors">REFACTOR SUCCESS!</h3>
                                        <p class="text-sm font-bold text-slate-500 dark:text-white/60 mb-8 max-w-xs transition-colors">Struktur kode telah lolos kompilasi dan riwayat pengerjaan berhasil disimpan.</p>
                                    </div>

                                    <div class="bg-slate-200 dark:bg-[#0f141e] px-4 py-2 border-b border-adaptive flex justify-between items-center transition-colors">
                                        <span class="text-xs text-slate-600 dark:text-white/50 font-mono font-bold transition-colors">Action-Button.html</span>
                                        <button onclick="resetEditor()" class="text-[10px] text-red-500 dark:text-red-400 hover:text-red-600 dark:hover:text-red-300 transition-colors uppercase font-bold">Reset</button>
                                    </div>
                                    
                                    {{-- Editor Container --}}
                                    <div id="codeEditor" class="h-[200px] w-full border-b border-adaptive transition-colors"></div>

                                    {{-- CLUES AREA DENGAN INSTRUKSI KONTEKSTUAL --}}
                                    <div class="p-6 bg-slate-50 dark:bg-[#0f141e] transition-colors flex flex-col flex-1">
                                        <div class="flex justify-between items-center mb-4">
                                            <span class="text-[10px] uppercase font-bold text-muted transition-colors">Requirements Checklist</span>
                                            <span id="progressText" class="text-[10px] font-mono text-cyan-600 dark:text-cyan-400 font-bold transition-colors">0/4 Terpenuhi</span>
                                        </div>
                                        <div class="space-y-3 text-[11px] text-slate-600 dark:text-white/60 mb-6 transition-colors overflow-y-auto custom-scrollbar pr-2 flex-1">
                                            <div id="chk-pad" class="flex items-start gap-3 transition-colors">
                                                <span class="w-4 h-4 mt-0.5 rounded-full border border-slate-300 dark:border-white/20 flex items-center justify-center text-[8px] transition-colors shrink-0"></span> 
                                                <div>
                                                    <strong class="block text-slate-800 dark:text-white mb-0.5 transition-colors">Ruang Padding</strong>
                                                    <span class="opacity-80">Berikan padding sumbu horizontal sebesar 32px  dan vertikal sejauh 12px .</span>
                                                </div>
                                            </div>
                                            <div id="chk-hover" class="flex items-start gap-3 transition-colors">
                                                <span class="w-4 h-4 mt-0.5 rounded-full border border-slate-300 dark:border-white/20 flex items-center justify-center text-[8px] transition-colors shrink-0"></span> 
                                                <div>
                                                    <strong class="block text-slate-800 dark:text-white mb-0.5 transition-colors">Reaktivitas Hover State</strong>
                                                    <span class="opacity-80">Gunakan transisi hover untuk merubah latar tombol menjadi <code class="text-cyan-600 dark:text-cyan-400 font-bold">bg-indigo-600</code>.</span>
                                                </div>
                                            </div>
                                            <div id="chk-fx" class="flex items-start gap-3 transition-colors">
                                                <span class="w-4 h-4 mt-0.5 rounded-full border border-slate-300 dark:border-white/20 flex items-center justify-center text-[8px] transition-colors shrink-0"></span> 
                                                <div>
                                                    <strong class="block text-slate-800 dark:text-white mb-0.5 transition-colors">Elevasi Kedalaman (Shadow)</strong>
                                                    <span class="opacity-80">Tambahkan dimensi bayangan medium (md) menggunakan atribut <code class="text-cyan-600 dark:text-cyan-400 font-bold">shadow</code>.</span>
                                                </div>
                                            </div>
                                            <div id="chk-rnd" class="flex items-start gap-3 transition-colors">
                                                <span class="w-4 h-4 mt-0.5 rounded-full border border-slate-300 dark:border-white/20 flex items-center justify-center text-[8px] transition-colors shrink-0"></span> 
                                                <div>
                                                    <strong class="block text-slate-800 dark:text-white mb-0.5 transition-colors">Kelengkungan Sudut (Rounded)</strong>
                                                    <span class="opacity-80">Buat sudut tombol membulat penuh (full) menggunakan kelas <code class="text-cyan-600 dark:text-cyan-400 font-bold">rounded</code>.</span>
                                                </div>
                                            </div>
                                        </div>

                                        <button id="submitExerciseBtn" onclick="submitExercise()" disabled class="w-full py-3 rounded-lg bg-emerald-600 text-white font-bold text-xs shadow-lg shadow-emerald-500/20 hover:bg-emerald-500 transition-all cursor-not-allowed opacity-50 flex items-center justify-center gap-2 mt-auto shrink-0">
                                            <span>Validasi ke Server</span>
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </button>
                                    </div>
                                </div>

                                {{-- RIGHT PANEL: VISUAL RENDER ZONE --}}
                                <div class="bg-white dark:bg-[#1e1e1e] border-l border-adaptive flex-1 flex flex-col relative overflow-hidden shadow-sm dark:shadow-none transition-colors">
                                    <div class="bg-slate-100 dark:bg-[#2d2d2d] px-4 py-2 border-b border-adaptive flex items-center justify-between transition-colors">
                                        <span class="text-[10px] text-slate-500 dark:text-gray-400 font-mono font-bold transition-colors">Browser Preview</span>
                                        <span class="text-[10px] bg-emerald-100 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 px-2 py-0.5 rounded border border-emerald-200 dark:border-emerald-500/20 font-bold transition-colors">Live Update</span>
                                    </div>
                                    <div class="flex-1 bg-slate-50 dark:bg-[#020617] p-8 flex items-center justify-center relative overflow-hidden transition-colors w-full h-full">
                                        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-10"></div>
                                        <iframe id="previewFrame" class="w-full h-full border-0 relative z-10"></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                </article>

                {{-- FOOTER NAVIGATION --}}
                <div class="mt-32 pt-8 border-t border-adaptive flex justify-between items-center transition-colors">
                    <a href="{{ route('courses.latarbelakang') ?? '#' }}" class="group flex items-center gap-4 text-muted hover:text-heading transition-colors">
                        <div class="w-12 h-12 rounded-full border border-adaptive flex items-center justify-center group-hover:bg-slate-100 dark:group-hover:bg-white/5 transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        </div>
                        <div class="text-left hidden sm:block">
                            <div class="text-[10px] uppercase tracking-widest font-bold opacity-60">Sebelumnya</div>
                            <div class="font-black text-sm">Latar Belakang & Struktur</div>
                        </div>
                    </a>
                    
                    <div id="nextChapterBtn" class="group flex items-center gap-4 text-right text-muted cursor-not-allowed opacity-50 pointer-events-none transition-all duration-500">
                        <div class="text-right hidden sm:block">
                            <div id="nextLabel" class="text-[10px] uppercase tracking-widest font-bold opacity-60">Terkunci</div>
                            <div class="font-black text-sm">Keunggulan Tailwind CSS</div>
                        </div>
                        <div id="nextIcon" class="w-12 h-12 rounded-full border border-adaptive flex items-center justify-center bg-slate-100 dark:bg-white/5 transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
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
    window.LESSON_IDS = [16, 17, 18, 19]; 
    let rawCompletedIds = {!! json_encode($completedLessonIds ?? []) !!};
    window.COMPLETED_IDS = rawCompletedIds.map(id => Number(id)); 
    let completedSet = new Set(window.COMPLETED_IDS);
    
    // Config Aktivitas
    const ACTIVITY_LESSON_ID = 19; 
    let activityCompleted = completedSet.has(ACTIVITY_LESSON_ID);

    /* --- 2. INITIALIZATION --- */
    document.addEventListener('DOMContentLoaded', () => {
    initSidebarScroll();
    initVisualEffects();
    
    // Render Progress Bar awal
    updateProgressUI(false); 
    
    // PENGAMANAN: Cek apakah elemen ada sebelum fungsi dijalankan
    if(typeof setTranslate === 'function' && document.getElementById('decoder-input')) {
        setTranslate('text-center');
    }
    if(typeof updateScale === 'function' && document.getElementById('scale-target')) {
        updateScale('p', 6);
    }
    
    // Eksekusi Monaco
    initMonaco();
    
    // Buka kunci chapter selanjutnya jika aktivitas sudah selesai
    if (activityCompleted) {
        unlockNextChapter();
    }

    // Inisialisasi observer tunggal yang digabung
    initMasterObserver();
    
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
                    dot.outerHTML = `<svg class="w-4 h-4 text-amber-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>`;
                } else {
                    dot.outerHTML = `<svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>`;
                }
            }
        }
    }

    // ==========================================
    // AJAX POST REQUEST KE DATABASE
    // ==========================================
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

    // ==========================================
    // MASTER SCROLL OBSERVER
    // ==========================================
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

    /* --- 4. FINISH LOGIC (ACTIVITY MONACO REFACTOR) --- */
    let editor;
    const starterCode = `<button class="bg-indigo-500 text-white font-bold transition-all ">
  Kirim Komando
</button>
`;

    function initMonaco() {
    require.config({ paths: { vs: 'https://cdn.jsdelivr.net/npm/monaco-editor@0.45.0/min/vs' } });
    require(['vs/editor/editor.main'], function () {
        
        const isDark = document.documentElement.classList.contains('dark');
        
        editor = monaco.editor.create(document.getElementById('codeEditor'), {
            value: starterCode, 
            language: 'html', 
            theme: isDark ? 'vs-dark' : 'vs', 
            fontSize: 13,
            minimap: { enabled: false }, 
            automaticLayout: true, 
            padding: { top: 16 }, 
            lineNumbers: 'off',
            scrollBeyondLastLine: false
        });
        
        updatePreview(starterCode);
        
        if (activityCompleted) {
            lockActivityUI();
        }
        
        editor.onDidChangeModelContent(() => {
            if(activityCompleted) return;
            const code = editor.getValue();
            updatePreview(code);
            validateConfig(code);
        });
        
        window.addEventListener('theme-toggled', () => {
            const newIsDark = document.documentElement.classList.contains('dark');
            monaco.editor.setTheme(newIsDark ? 'vs-dark' : 'vs');
            updatePreview(editor.getValue());
        });
    });
}

    function updatePreview(code) {
        const frame = document.getElementById('previewFrame');
        const isDark = document.documentElement.classList.contains('dark');
        const bgColor = isDark ? '#020617' : '#f8fafc'; 
        
        const content = `<!doctype html><html><head><script src="https://cdn.tailwindcss.com"><\/script><style>body { background-color: ${bgColor}; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; font-family: sans-serif; transition: background-color 0.3s; }</style></head><body>${code}</body></html>`;
        frame.srcdoc = content;
    }

    function validateConfig(code) {
    const checks = [
        { id: 'chk-pad', regex: /(?=.*\bpx-8\b)(?=.*\bpy-3\b)/ }, 
        { id: 'chk-hover', regex: /(?=.*\bhover:bg-indigo-600\b)/ },
        { id: 'chk-fx', regex: /(?=.*\bshadow-md\b)/ },
        { id: 'chk-rnd', regex: /(?=.*\brounded-full\b)/ }
    ];

    let passed = 0;
    const isDark = document.documentElement.classList.contains('dark');

    checks.forEach(c => {
        const el = document.getElementById(c.id);
        if (!el) return;
        const dot = el.querySelector('span'); 
        const textContainer = el.querySelector('div'); 
        
        const classMatch = code.match(/class=["'](.*?)["']/);
        const classString = classMatch ? classMatch[1] : '';

        if(c.regex.test(classString)) {
            textContainer.classList.remove('opacity-80');
            textContainer.classList.add(isDark ? 'text-green-400' : 'text-emerald-600');
            
            dot.innerHTML = '✓'; 
            dot.classList.remove('border-slate-300', 'border-white/20');
            dot.classList.add('bg-emerald-500', 'border-transparent', 'text-white');
            passed++;
        } else {
            textContainer.classList.add('opacity-80');
            textContainer.classList.remove('text-green-400', 'text-emerald-600');
            
            dot.innerHTML = ''; 
            dot.classList.remove('bg-emerald-500', 'border-transparent', 'text-white');
            dot.classList.add(isDark ? 'border-white/20' : 'border-slate-300');
        }
    });

    document.getElementById('progressText').innerText = `${passed}/4 Terpenuhi`;
    
    const btn = document.getElementById('submitExerciseBtn');
    if (passed === 4) {
        btn.disabled = false;
        btn.classList.remove('cursor-not-allowed', 'opacity-50');
        btn.innerHTML = `<span>Validasi ke Server</span><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`;
    } else {
        btn.disabled = true;
        btn.classList.add('cursor-not-allowed', 'opacity-50');
        btn.innerHTML = `<span>Menunggu Persyaratan</span><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`;
    }
}

    function resetEditor() { 
        if(editor && !activityCompleted) { 
            editor.setValue(starterCode); 
            validateConfig(starterCode); 
        } 
    }

    async function submitExercise() {
        const btn = document.getElementById('submitExerciseBtn');
        btn.innerHTML = '<span class="animate-pulse">Menyimpan data...</span>'; 
        btn.disabled = true;
        
        try {
            await saveLessonToDB(ACTIVITY_LESSON_ID); 
            
            activityCompleted = true;
            lockActivityUI();   
            unlockNextChapter(); 
        } catch(e) { 
            console.error(e); 
            btn.innerHTML = "Koneksi gagal. Coba lagi.";
            btn.disabled = false;
        }
    }

    function lockActivityUI() {
        document.getElementById('lockOverlay').classList.remove('hidden');
        if(editor) editor.updateOptions({ readOnly: true });
        
        const btn = document.getElementById('submitExerciseBtn'); 
        btn.innerText = "Terkunci (Selesai)"; 
        btn.disabled = true;
        btn.classList.remove('bg-emerald-600', 'hover:bg-emerald-500');
        btn.classList.add('bg-slate-400', 'dark:bg-slate-700', 'text-slate-200', 'cursor-not-allowed', 'shadow-none');
        
        if(editor && activityCompleted) {
            editor.setValue(`<button class="bg-indigo-500 text-white font-bold transition-all px-8 py-3 hover:bg-indigo-600 shadow-md rounded-full">\n  Kirim Komando\n</button>`);
            validateConfig(editor.getValue());
        }
    }

    function unlockNextChapter() {
        const btn = document.getElementById('nextChapterBtn');
        if(btn) {
            btn.classList.remove('cursor-not-allowed', 'opacity-50', 'pointer-events-none', 'text-muted');
            btn.classList.add('text-cyan-600', 'dark:text-cyan-400', 'cursor-pointer');
            
            document.getElementById('nextLabel').innerText = "Selanjutnya";
            document.getElementById('nextLabel').classList.remove('opacity-60');
            document.getElementById('nextLabel').classList.add('text-cyan-600', 'dark:text-cyan-400', 'opacity-100');
            
            const icon = document.getElementById('nextIcon');
            icon.innerHTML = `<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>`;
            icon.classList.remove('bg-slate-100', 'dark:bg-white/5');
            icon.classList.add('bg-cyan-100', 'dark:bg-cyan-500/20', 'border-cyan-300', 'dark:border-cyan-500/50', 'text-cyan-600', 'dark:text-cyan-400', 'shadow-lg');
            
            btn.onclick = () => window.location.href = "{{ route('courses.advantages') ?? '#' }}"; 
        }
    }

    /* --- 5. SIMULATOR LOGIC (LAINNYA) --- */
    function translateClass() {
        const isDark = document.documentElement.classList.contains('dark');
        const input = document.getElementById('decoder-input')?.value?.trim();
        const res = document.getElementById('decoder-result');
        let css = '';
        
        if (!input) {
            css = `<span class="text-slate-400 dark:text-white/30 italic transition-colors">// Menunggu input...</span>`;
            if(res) res.innerHTML = `<span class="text-xs text-slate-400 dark:text-white/30 mb-1 block transition-colors">// CSS Output:</span><code class="text-cyan-700 dark:text-cyan-400 font-bold text-sm block transition-colors">${css}</code>`;
            return;
        }

        if (input.match(/^text-(center|left|right)$/)) css = `text-align: ${input.split('-')[1]};`;
        else if (input.match(/^(m|p)[trblxy]?-\d+$/)) css = `${input.startsWith('m')?'margin':'padding'}: ${input.split('-').pop() * 0.25}rem;`;
        else if (input.match(/^bg-[a-z]+-\d{3}$/)) css = `background-color: [Varian Palet ${input.split('-')[1]}];`;
        else if (input === 'flex') css = 'display: flex;';
        else if (input.startsWith('text-')) css = `font-size: [Skala Proporsi ${input.split('-')[1]}];`;
        else css = `/* Deklarasi styling untuk .${input} */`;
        
        if(res) res.innerHTML = `<span class="text-xs text-slate-400 dark:text-white/30 mb-1 block transition-colors">// CSS Output:</span><code class="text-cyan-700 dark:text-cyan-400 font-bold text-sm block transition-colors">${css}</code>`;
    }
    
    function setTranslate(val) { 
        const inputEl = document.getElementById('decoder-input');
        if(!inputEl) return; 
        
        inputEl.value = val; 
        translateClass(); 
    }

    function updateScale(type, val) {
        const formula = document.getElementById('scale-formula');
        const target = document.getElementById('scale-target');
        const label = document.getElementById('scale-p-val');
        const px = val * 4; 
        const rem = val * 0.25;
        if(formula) formula.innerHTML = `${val} x 4px = <span class="text-cyan-600 dark:text-cyan-400 font-bold transition-colors">${px}px</span> (${rem}rem)`;
        if(label) label.innerText = `p-${val}`;
        if(target) target.style.padding = `${px}px`;
    }

    let activeStates = new Set();
    function toggleStateClass(state) {
        const btn = document.getElementById('state-target');
        if(!btn) return;
        if(activeStates.has(state)) {
            activeStates.delete(state);
            if(state==='hover') { btn.onmouseenter = null; btn.onmouseleave = null; btn.classList.remove('scale-110'); }
            if(state==='active') { btn.onmousedown = null; btn.onmouseup = null; btn.classList.remove('bg-fuchsia-800'); }
            if(state==='focus') btn.classList.remove('ring-4', 'ring-yellow-500/50');
        } else {
            activeStates.add(state);
            if(state==='hover') { btn.onmouseenter = () => btn.classList.add('scale-110'); btn.onmouseleave = () => btn.classList.remove('scale-110'); }
            if(state==='active') { btn.onmousedown = () => btn.classList.add('bg-fuchsia-800'); btn.onmouseup = () => btn.classList.remove('bg-fuchsia-800'); }
            if(state==='focus') btn.classList.add('ring-4', 'ring-yellow-500/50');
        }
    }

    function triggerMorph() {
        const messy = document.getElementById('morph-messy');
        const clean = document.getElementById('morph-clean');
        if(messy && clean) {
            messy.style.opacity = '0'; messy.style.transform = 'translateY(-20px)';
            setTimeout(() => { clean.classList.remove('opacity-0', 'scale-90'); }, 400);
        }
    }

    /* --- 7. SCROLL SPY & SIDEBAR LOGIC --- */
    function highlightAnchor(id) {
        const isDark = document.documentElement.classList.contains('dark');
        const anchors = document.querySelectorAll('.sidebar-anchor');

        anchors.forEach(a => {
            a.classList.remove('bg-slate-100', 'dark:bg-white/5', 'border-cyan-500', 'border-amber-500');
            a.classList.add('border-transparent');
            
            const dot = a.querySelector('.anchor-dot');
            const isActivity = a.dataset.type === 'activity';

            if(dot) dot.classList.remove('scale-125', 'shadow-sm', 'dark:shadow-[0_0_10px_#06b6d4]', 'dark:shadow-[0_0_10px_#f59e0b]', 'bg-cyan-500', 'dark:bg-cyan-400');
            
            if (isActivity) {
                if(dot) { dot.classList.remove('bg-amber-500', 'dark:bg-amber-400'); dot.classList.add('bg-slate-400', 'dark:bg-slate-600'); }
            } else {
                if(dot) { dot.classList.remove('bg-cyan-500', 'dark:bg-cyan-400'); dot.classList.add('bg-slate-400', 'dark:bg-slate-600'); }
            }

            const text = a.querySelector('.anchor-text');
            if(text) { text.classList.remove('text-slate-800', 'dark:text-white', 'font-bold'); text.classList.add('text-slate-500'); }
        });

        const activeAnchor = document.querySelector(`.sidebar-anchor[data-target="${id}"]`);
        if (activeAnchor) {
            const isActivity = activeAnchor.dataset.type === 'activity';
            
            activeAnchor.classList.add(isDark ? 'dark:bg-white/5' : 'bg-slate-100');
            activeAnchor.classList.add(isActivity ? 'border-amber-500' : 'border-cyan-500');
            activeAnchor.classList.remove('border-transparent');
            
            const dot = activeAnchor.querySelector('.anchor-dot');
            if(dot) {
                dot.classList.remove('bg-slate-400', 'dark:bg-slate-600');
                if (isActivity) {
                    dot.classList.add(isDark ? 'dark:bg-amber-400' : 'bg-amber-500', 'scale-125', isDark ? 'dark:shadow-[0_0_10px_#f59e0b]' : 'shadow-sm');
                } else {
                    dot.classList.add(isDark ? 'dark:bg-cyan-400' : 'bg-cyan-500', 'scale-125', isDark ? 'dark:shadow-[0_0_10px_#06b6d4]' : 'shadow-sm');
                }
            }
            
            const text = activeAnchor.querySelector('.anchor-text');
            if(text) { text.classList.remove('text-slate-500'); text.classList.add(isDark ? 'dark:text-white' : 'text-slate-800', 'font-bold'); }
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