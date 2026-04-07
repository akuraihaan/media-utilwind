@extends('layouts.landing')
@section('title','Latar Belakang Tailwind CSS')

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
        --accent: #a855f7; /* Purple 500 */
        --accent-glow: rgba(168, 85, 247, 0.3);
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
        --accent-glow: rgba(168, 85, 247, 0.5);
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
        background-color: rgba(168, 85, 247, 0.15); /* Purple tint */
        color: #7e22ce; /* Purple 700 */
        padding: 0.125rem 0.375rem;
        border-radius: 0.375rem;
        font-weight: 600;
        font-style: normal;
        white-space: nowrap;
        border: 1px solid rgba(168, 85, 247, 0.3);
    }
    .dark .hl-term {
        background-color: rgba(168, 85, 247, 0.2);
        color: #d8b4fe; /* Purple 300 */
        border: 1px solid rgba(168, 85, 247, 0.4);
    }

    /* SCROLLBAR */
    .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(150,150,150,0.3); border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: var(--accent); }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }
    
    /* ANIMATIONS & EFFECTS */
    #animated-bg { 
        background: radial-gradient(800px circle at 20% 20%, rgba(168,85,247,.10), transparent 40%), 
                    radial-gradient(800px circle at 80% 80%, rgba(99,102,241,.10), transparent 40%); 
        animation: bgMove 20s ease-in-out infinite alternate; 
    }
    .dark #animated-bg {
        background: radial-gradient(800px circle at 20% 20%, rgba(168,85,247,.15), transparent 40%), 
                    radial-gradient(800px circle at 80% 80%, rgba(99,102,241,.15), transparent 40%); 
    }
    @keyframes bgMove{to{transform:scale(1.15)}}
    @keyframes shake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-5px); } 75% { transform: translateX(5px); } }
    .shake { animation: shake 0.4s ease-in-out; }
    
    /* SCROLLSPY SIDEBAR ACTIVE */
    .nav-item { display: flex; width: 100%; text-align: left; align-items: center; gap: 12px; padding: 10px 14px; font-size: 0.85rem; color: var(--text-muted); border-radius: 8px; transition: all 0.2s; position: relative; }
    .nav-item:hover { color: var(--text-main); background: var(--card-hover); }
    .nav-item.active { color: #c026d3; background: rgba(192, 38, 211, 0.05); font-weight: 600; }
    .dot { width: 6px; height: 6px; border-radius: 50%; background: #94a3b8; transition: all 0.3s; }
    .dark .dot { background: #334155; }
    .nav-item.active .dot { background: #c026d3; box-shadow: 0 0 8px #c026d3; transform: scale(1.2); }
</style>

{{-- H-SCREEN UNTUK MENGUNCI SIDEBAR AGAR TIDAK IKUT SCROLL --}}
<div id="courseRoot" class="relative h-screen bg-adaptive text-adaptive font-sans overflow-hidden flex flex-col selection:bg-purple-500/30 pt-20 transition-colors duration-500">

    {{-- BACKGROUND EFFECTS --}}
    <div class="fixed inset-0 -z-50 pointer-events-none">
        <div id="animated-bg" class="absolute inset-0 opacity-50 transition-opacity"></div>
        <div class="absolute top-[-20%] left-[-10%] w-[1000px] h-[1000px] bg-purple-500/5 dark:bg-purple-900/10 rounded-full blur-[120px] animate-pulse transition-colors"></div>
        <div class="absolute bottom-[-20%] right-[-10%] w-[800px] h-[800px] bg-indigo-500/5 dark:bg-indigo-900/10 rounded-full blur-[100px] transition-colors"></div>
        <canvas id="stars" class="absolute inset-0 pointer-events-none opacity-0 dark:opacity-100 transition-opacity duration-500"></canvas>
        <div id="noise-overlay" class="absolute inset-0 z-10 opacity-[0.02] dark:opacity-[0.03]"></div>
        <div id="cursor-glow"></div>
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
                    <div class="w-8 h-8 rounded-lg bg-purple-500/10 dark:bg-purple-500/20 border border-purple-500/20 flex items-center justify-center font-bold text-xs text-purple-600 dark:text-purple-400 transition-colors shrink-0">1.3</div>
                    <div>
                        <h1 class="text-sm font-bold text-heading transition-colors line-clamp-1">Struktur & Latar Belakang</h1>
                        <p class="text-[10px] text-muted transition-colors line-clamp-1">History, Layers & JIT Engine</p>
                    </div>
                </div>
                
                {{-- DYNAMIC PROGRESS BAR UI --}}
                <div class="flex items-center gap-3 shrink-0">
                    <div class="hidden sm:block w-24 md:w-32 h-1.5 bg-slate-200 dark:bg-white/10 rounded-full overflow-hidden transition-colors">
                        <div id="topProgressBar" class="h-full bg-gradient-to-r from-purple-500 to-indigo-500 w-0 transition-all duration-500 shadow-[0_0_10px_rgba(168,85,247,0.5)]"></div>
                    </div>
                    <span id="progressLabelTop" class="text-purple-600 dark:text-purple-400 font-bold text-xs transition-colors">0%</span>
                </div>
            </div>

            <div class="p-4 sm:p-6 lg:p-16 max-w-5xl mx-auto pb-40">
                
                {{-- TUJUAN PEMBELAJARAN --}}
                <div class="mb-16 md:mb-24">
                    <h3 class="text-xl font-bold text-heading mb-6 flex items-center gap-2 transition-colors">
                        <svg class="w-5 h-5 text-purple-500 dark:text-purple-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
                        Tujuan Pembelajaran
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                        <div class="card-adaptive p-5 rounded-xl flex items-start gap-4 hover:border-purple-500/30 transition group h-full">
                            <div class="w-8 h-8 rounded bg-purple-500/10 text-purple-600 dark:text-purple-400 flex items-center justify-center shrink-0 font-bold text-xs transition-colors">1</div>
                            <div><h4 class="text-sm font-bold text-heading mb-1 transition-colors">Sejarah CSS</h4><p class="text-[11px] text-muted leading-relaxed transition-colors">Kenapa CSS tradisional rentan rusak dan bagaimana Tailwind menyelamatkannya.</p></div>
                        </div>
                        <div class="card-adaptive p-5 rounded-xl flex items-start gap-4 hover:border-fuchsia-500/30 transition group h-full">
                            <div class="w-8 h-8 rounded bg-fuchsia-500/10 text-fuchsia-600 dark:text-fuchsia-400 flex items-center justify-center shrink-0 font-bold text-xs transition-colors">2</div>
                            <div><h4 class="text-sm font-bold text-heading mb-1 transition-colors">Arsitektur Layer</h4><p class="text-[11px] text-muted leading-relaxed transition-colors">Mengenal struktur Base, Components, dan Utilities agar class tidak gampang bentrok.</p></div>
                        </div>
                        <div class="card-adaptive p-5 rounded-xl flex items-start gap-4 hover:border-indigo-500/30 transition group h-full">
                            <div class="w-8 h-8 rounded bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0 font-bold text-xs transition-colors">3</div>
                            <div><h4 class="text-sm font-bold text-heading mb-1 transition-colors">JIT Engine</h4><p class="text-[11px] text-muted leading-relaxed transition-colors">Membongkar rahasia mesin modern Tailwind yang membuat ukuran file CSS super kecil.</p></div>
                        </div>
                        <div class="bg-gradient-to-br from-purple-100 to-indigo-100 dark:from-purple-900/40 dark:to-indigo-900/40 border border-purple-200 dark:border-purple-500/30 p-5 rounded-xl flex items-start gap-4 hover:shadow-[0_0_20px_rgba(168,85,247,0.2)] transition group h-full col-span-1 sm:col-span-2 md:col-span-3 cursor-default">
                            <div class="w-8 h-8 rounded bg-white/50 dark:bg-white/10 text-purple-700 dark:text-white flex items-center justify-center shrink-0 font-bold text-xs shadow-sm dark:shadow-none">🏁</div>
                            <div><h4 class="text-sm font-bold text-purple-900 dark:text-white mb-1 transition-colors">Final Mission</h4><p class="text-[11px] text-purple-800 dark:text-white/70 leading-relaxed transition-colors">Ujian akhir menggunakan <span class="hl-term">Arbitrary Values</span> untuk menciptakan desain dengan presisi tinggi.</p></div>
                        </div>
                    </div>
                </div>

                <article class="space-y-24 md:space-y-32">
                    
                    {{-- LESSON 12 --}}
                    <section id="section-12" class="lesson-section scroll-mt-32" data-lesson-id="12">
                        <div class="space-y-8 md:space-y-10">
                            <div class="space-y-4 border-l-4 border-purple-500 pl-4 md:pl-6">
                                <span class="text-purple-600 dark:text-purple-400 font-mono text-xs uppercase tracking-widest transition-colors">Lesson 1.3.1</span>
                                <h2 class="text-2xl md:text-4xl font-black text-heading leading-[1.1] transition-colors">
                                    Krisis Skalabilitas <br> 
                                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-500 to-indigo-600 dark:from-purple-400 dark:to-indigo-500">CSS Tradisional</span>
                                </h2>
                            </div>
                            
                            <div class="space-y-4">
                                <h3 class="text-base md:text-lg font-bold text-heading flex items-center gap-2 transition-colors"><span class="w-5 h-5 rounded bg-purple-500 dark:bg-purple-600 flex items-center justify-center text-[10px] text-white shrink-0">A</span> Berawal dari Rasa Frustrasi</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-sm md:text-base leading-relaxed space-y-4 text-justify transition-colors">
                                    <p>Pernahkah Anda menatap layar monitor selama bermenit-menit hanya untuk memikirkan nama sebuah class HTML? Apakah elemen ini pantas diberi nama <code>.profile-wrapper</code>? Atau mungkin <code>.user-card-container</code>? Menguras waktu, bukan? Itulah persisnya yang dirasakan oleh <strong>Adam Wathan</strong> sebelum ia menciptakan Tailwind CSS.</p>
                                    <p>Dulu, standar industri menuntut kita untuk memisahkan struktur HTML dan gaya CSS. Kita dipaksa membuat nama class yang panjang dan deskriptif, lalu berpindah ke file CSS terpisah untuk mewarnainya. Sayangnya, begitu proyek web kita membesar, cara tradisional ini pelan-pelan berubah menjadi bom waktu. Mencari di mana letak suatu gaya CSS tersembunyi rasanya seperti mencari jarum di tumpukan jerami.</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-base md:text-lg font-bold text-heading flex items-center gap-2 transition-colors"><span class="w-5 h-5 rounded bg-indigo-500 dark:bg-indigo-600 flex items-center justify-center text-[10px] text-white shrink-0">B</span> Penyakit Bernama Append-Only</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-sm md:text-base leading-relaxed space-y-4 text-justify transition-colors">
                                    <p>Ada satu ketakutan terbesar bagi setiap developer CSS: <strong>Takut menghapus kode lama</strong>. Karena sifat CSS itu global, kita tidak pernah yakin sepenuhnya apakah baris kode yang kita hapus akan merusak desain di halaman lain yang luput dari pantauan kita.</p>
                                    <p>Solusi pragmatis yang sering diambil? <i>Biarkan saja kode lama itu, kita tulis kode baru di baris paling bawah!</i> Kebiasaan buruk ini melahirkan masalah yang disebut <span class="hl-term">The Append-Only Problem</span>. File CSS perlahan membengkak hingga berukuran raksasa, penuh dengan kode mati yang membebani kecepatan muat website. Karena class di Tailwind bisa dipakai ulang tanpa batas, ukuran file CSS Anda pada akhirnya akan berhenti membesar (masuk fase <span class="hl-term">plateau</span>) betapapun kompleksnya web Anda.</p>
                                </div>
                            </div>

                            <div class="card-adaptive border border-adaptive rounded-xl overflow-hidden shadow-xl dark:shadow-2xl flex flex-col transition-colors mt-8 relative">
                                
                                {{-- KOTAK INSTRUKSI SIMULATOR 1 --}}
                                <div class="w-full bg-purple-600/95 dark:bg-purple-900/95 backdrop-blur text-white p-3 z-20 flex flex-col sm:flex-row sm:justify-between items-start sm:items-center shadow-md gap-2 sm:gap-0 border-b border-purple-400 dark:border-purple-700">
                                    <div class="flex items-center gap-2 text-xs font-bold shrink-0">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        PANDUAN SIMULASI 1
                                    </div>
                                    <p class="text-[10px] opacity-90 sm:max-w-xs md:max-w-md sm:text-right leading-tight m-0">Tekan tombol penambahan fitur di bawah secara berulang. Bandingkan anomali grafik merah yang tumbuh tak terkendali dengan stabilitas grafik hijau.</p>
                                </div>

                                <div class="flex flex-col gap-6 relative z-10 p-4 sm:p-6 lg:p-8">
                                    <div>
                                        <div class="flex justify-between text-xs mb-2">
                                            <span class="text-rose-600 dark:text-red-400 font-bold transition-colors">CSS Tradisional (Terus Membengkak)</span>
                                            <span id="trad-size" class="text-heading font-mono transition-colors">10 KB</span>
                                        </div>
                                        <div class="w-full h-4 sm:h-5 bg-slate-200 dark:bg-white/5 rounded-full overflow-hidden relative transition-colors border border-adaptive">
                                            <div id="bar-trad" class="h-full bg-rose-500 dark:bg-red-600 w-[5%] transition-all duration-1000 shadow-[0_0_10px_#f43f5e] dark:shadow-[0_0_10px_#dc2626]"></div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex justify-between text-xs mb-2">
                                            <span class="text-emerald-600 dark:text-emerald-400 font-bold transition-colors">Tailwind CSS (Stabilitas Kurva <span class="hl-term">Plateau</span>)</span>
                                            <span id="tw-size" class="text-heading font-mono transition-colors">10 KB</span>
                                        </div>
                                        <div class="w-full h-4 sm:h-5 bg-slate-200 dark:bg-white/5 rounded-full overflow-hidden relative transition-colors border border-adaptive">
                                            <div id="bar-tw" class="h-full bg-emerald-500 dark:bg-emerald-600 w-[5%] transition-all duration-1000 shadow-[0_0_10px_#10b981]"></div>
                                        </div>
                                    </div>
                                    <div class="flex justify-center mt-4 sm:mt-6">
                                        <button onclick="simulateGrowth()" id="growBtn" class="px-6 py-3 bg-purple-600 hover:bg-purple-500 text-white rounded-lg font-bold text-[11px] sm:text-sm transition-all shadow-md hover:shadow-lg active:scale-95 flex items-center gap-2 border border-purple-400">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                            Tambah 10 Fitur Baru di Web
                                        </button>
                                    </div>
                                </div>

                                {{-- KESIMPULAN SIMULASI 1 --}}
                                <div class="bg-gradient-to-r from-purple-50 to-transparent dark:from-purple-900/20 dark:to-transparent border-t border-purple-200 dark:border-purple-500/20 p-4 sm:p-5 relative z-10 transition-colors">
                                    <h5 class="text-[11px] sm:text-xs font-bold text-purple-700 dark:text-purple-400 mb-1 flex items-center gap-2">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> 
                                        Kesimpulan Pertumbuhan File
                                    </h5>
                                    <p class="text-[10px] sm:text-[11px] text-slate-700 dark:text-slate-300 leading-relaxed text-justify m-0">
                                        Tailwind pada dasarnya memutus korelasi langsung antara kompleksitas web dengan bertambahnya ukuran CSS. Strategi daur ulang menjamin bahwa kurva pertumbuhan akan membeku di batas aman.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 13 --}}
                    <section id="section-13" class="lesson-section scroll-mt-32" data-lesson-id="13">
                        <div class="space-y-8 md:space-y-10">
                            <div class="space-y-4 border-l-4 border-purple-500 pl-4 md:pl-6">
                                <span class="text-purple-600 dark:text-purple-400 font-mono text-xs uppercase tracking-widest transition-colors">Lesson 1.3.2</span>
                                <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-heading leading-[1.1] transition-colors">
                                    Arsitektur <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-500 to-indigo-600 dark:from-purple-400 dark:to-indigo-500">Tiga Lapisan Sistem (Layers)</span>
                                </h2>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-base md:text-lg font-bold text-heading flex items-center gap-2 transition-colors"><span class="w-5 h-5 rounded bg-purple-500 dark:bg-purple-600 flex items-center justify-center text-[10px] text-white shrink-0">A</span> Mengakhiri Perang Class CSS</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-sm md:text-base leading-relaxed space-y-4 text-justify transition-colors">
                                    <p>Pernahkah Anda memberi class CSS, mengatur warnanya jadi merah, tapi entah kenapa di layar warnanya tetap hitam? Situasi menyebalkan itu punya nama: <span class="hl-term">Specificity War</span>. Browser kadang bingung menentukan mana gaya yang harus dimenangkan jika ada dua class yang bertabrakan.</p>
                                    <p>Untuk menertibkan kekacauan ini, Tailwind mengemas seluruh CSS-nya ke dalam tiga lapisan yang berbeda menggunakan perintah <code>@tailwind</code>. Bayangkan layer ini seperti piramida, di mana layer yang posisinya paling bawah memiliki kasta tertinggi untuk menimpa gaya di atasnya.</p>
                                    <ul class="space-y-3 list-none pl-0 mt-4 text-[10px] sm:text-xs font-mono bg-slate-50 dark:bg-black/40 p-4 sm:p-6 rounded-xl border border-adaptive shadow-inner transition-colors break-words">
                                        <li class="text-slate-600 dark:text-white/70 flex flex-col sm:flex-row gap-1 sm:gap-4"><span class="font-bold text-rose-500 sm:min-w-[150px]">@tailwind base;</span> <span class="opacity-80">Lapisan dasar. Cuma buat reset margin bawaan browser.</span></li>
                                        <li class="text-slate-600 dark:text-white/70 flex flex-col sm:flex-row gap-1 sm:gap-4"><span class="font-bold text-amber-500 sm:min-w-[150px]">@tailwind components;</span> <span class="opacity-80">Tempat menyimpan class buatanmu (misal: <code>.btn</code>).</span></li>
                                        <li class="text-slate-600 dark:text-white/70 flex flex-col sm:flex-row gap-1 sm:gap-4"><span class="font-bold text-emerald-500 sm:min-w-[150px]">@tailwind utilities;</span> <span class="opacity-80">Kasta tertinggi! Class utilitas kayak <code>p-4</code> ada di sini.</span></li>
                                    </ul>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-base md:text-lg font-bold text-heading flex items-center gap-2 transition-colors"><span class="w-5 h-5 rounded bg-indigo-500 dark:bg-indigo-600 flex items-center justify-center text-[10px] text-white shrink-0">B</span> Resolusi Spesifisitas Mutlak</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-sm md:text-base leading-relaxed space-y-4 text-justify transition-colors">
                                    <p>Kenapa sih <strong>Utilities</strong> harus ada di paling bawah? Dalam dunia CSS, kode yang ditulis paling akhir-lah yang akan menang jika terjadi perdebatan.</p>
                                    <p>Bayangkan Anda membuat desain tombol di layer <span class="hl-term">Components</span> dengan nama <code>.btn-biru</code> yang secara default punya padding 10px. Suatu hari, bos Anda minta tombol khusus di satu halaman ukurannya dibesarkan. Anda cukup menulis <code>&lt;button class="btn-biru p-8"&gt;</code>. Karena <code>p-8</code> hidup di layer <span class="hl-term">Utilities</span> (di bawah Components), maka perintah <code>p-8</code> sah menimpa padding bawaan tombol. Anda tidak perlu lagi menulis <code>!important</code> secara paksa!</p>
                                </div>
                            </div>

                            <div class="card-adaptive border border-adaptive rounded-xl flex flex-col relative overflow-hidden transition-colors shadow-xl dark:shadow-2xl mt-8 justify-center">
                                
                                {{-- KOTAK INSTRUKSI SIMULATOR 2 --}}
                                <div class="w-full bg-indigo-600/95 dark:bg-indigo-900/95 backdrop-blur text-white p-3 z-20 flex flex-col sm:flex-row sm:justify-between items-start sm:items-center shadow-md gap-2 sm:gap-0 border-b border-indigo-400 dark:border-indigo-700">
                                    <div class="flex items-center gap-2 text-xs font-bold shrink-0">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        PANDUAN SIMULASI 2
                                    </div>
                                    <p class="text-[10px] opacity-90 sm:max-w-xs md:max-w-md sm:text-right leading-tight m-0">Tekan lempengan grafis piramida kaskade untuk melihat rincian deskripsi spesifikasi tingkat kekuatannya.</p>
                                </div>

                                <div class="relative w-full max-w-md h-[250px] sm:h-[280px] flex flex-col justify-end items-center gap-3 perspective-1000 z-10 mt-6 mb-4 px-4 mx-auto">
                                    <div id="layer-util" class="w-full bg-gradient-to-r from-emerald-100 to-emerald-50 dark:from-emerald-900/60 dark:to-emerald-900/30 border border-emerald-400 dark:border-emerald-500 p-4 rounded-xl text-center text-emerald-800 dark:text-emerald-300 font-bold shadow-[0_10px_20px_-5px_rgba(16,185,129,0.3)] transform transition-all translate-y-0 opacity-50 cursor-pointer hover:opacity-100 hover:-translate-y-2 z-30 flex flex-col justify-center h-[70px] sm:h-[80px]" onclick="highlightLayer('util')">
                                        UTILITIES LAYER <span class="block text-[9px] sm:text-[10px] font-normal mt-1 text-emerald-600 dark:text-emerald-400 line-clamp-1">(Kasta Kuasa Tertinggi Mutlak)</span>
                                    </div>
                                    <div id="layer-comp" class="w-[90%] bg-gradient-to-r from-amber-100 to-amber-50 dark:from-yellow-900/60 dark:to-yellow-900/30 border border-amber-400 dark:border-yellow-500 p-4 rounded-xl text-center text-amber-800 dark:text-yellow-300 font-bold shadow-[0_10px_20px_-5px_rgba(245,158,11,0.3)] transform transition-all translate-y-0 opacity-50 cursor-pointer hover:opacity-100 hover:-translate-y-2 z-20 flex flex-col justify-center h-[70px] sm:h-[80px]" onclick="highlightLayer('comp')">
                                        COMPONENTS LAYER <span class="block text-[9px] sm:text-[10px] font-normal mt-1 text-amber-600 dark:text-yellow-400 line-clamp-1">(Kasta Titik Menengah)</span>
                                    </div>
                                    <div id="layer-base" class="w-[80%] bg-gradient-to-r from-rose-100 to-rose-50 dark:from-red-900/60 dark:to-red-900/30 border border-rose-400 dark:border-red-500 p-4 rounded-xl text-center text-rose-800 dark:text-red-300 font-bold shadow-[0_10px_20px_-5px_rgba(244,63,94,0.3)] transform transition-all translate-y-0 opacity-50 cursor-pointer hover:opacity-100 hover:-translate-y-2 z-10 flex flex-col justify-center h-[70px] sm:h-[80px]" onclick="highlightLayer('base')">
                                        BASE LAYER <span class="block text-[9px] sm:text-[10px] font-normal mt-1 text-rose-600 dark:text-red-400 line-clamp-1">(Kasta Pondasi Terendah)</span>
                                    </div>
                                </div>
                                
                                <div class="px-4 pb-6 w-full max-w-lg mx-auto">
                                    <div id="layer-desc" class="text-xs sm:text-sm text-slate-700 dark:text-white/80 text-justify min-h-[90px] bg-slate-50 dark:bg-black/30 p-4 sm:p-5 rounded-xl border border-adaptive transition-colors shadow-inner leading-relaxed flex items-center justify-center">
                                        <span class="italic opacity-60 text-center block w-full">Silakan ketuk bidang area grafis tumpukan piramida untuk menjalankan tinjauan inspeksi mendalam.</span>
                                    </div>
                                </div>

                                {{-- KESIMPULAN SIMULASI 2 --}}
                                <div class="w-full bg-gradient-to-r from-indigo-50 to-transparent dark:from-indigo-900/20 dark:to-transparent border-t border-indigo-200 dark:border-indigo-500/20 p-4 sm:p-5 relative z-10 transition-colors">
                                    <div class="max-w-xl mx-auto">
                                        <h5 class="text-[11px] sm:text-xs font-bold text-indigo-700 dark:text-indigo-400 mb-1 flex items-center gap-2">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> 
                                            Kesimpulan Analisis Arsitektur
                                        </h5>
                                        <p class="text-[10px] sm:text-[11px] text-slate-700 dark:text-slate-300 leading-relaxed text-justify m-0">
                                            Penempatan kelas utilitas mutlak murni di pelataran lapisan terbawah menyegel jaminan ketertiban resolusi <span class="hl-term">Specificity Conflict</span> yang beroperasi transparan. Ini meyakinkan para insinyur web bahwa injeksi nilai sebaris seperti <code>text-red-500</code> pasti menaklukkan ego rona elemen induk mana pun, membebaskan arsitektur dari tag <code>!important</code> beracun.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 14 --}}
                    <section id="section-14" class="lesson-section scroll-mt-32" data-lesson-id="14">
                        <div class="space-y-8 md:space-y-10">
                            <div class="space-y-4 border-l-4 border-purple-500 pl-4 md:pl-6">
                                <span class="text-purple-600 dark:text-purple-400 font-mono text-xs uppercase tracking-widest transition-colors">Lesson 1.3.3</span>
                                <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-heading leading-[1.1] transition-colors">
                                    Mesin Kompilasi <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-500 to-indigo-600 dark:from-purple-400 to-indigo-500">JIT (Just-In-Time)</span>
                                </h2>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-base md:text-lg font-bold text-heading flex items-center gap-2 transition-colors"><span class="w-5 h-5 rounded bg-purple-500 dark:bg-purple-600 flex items-center justify-center text-[10px] text-white shrink-0">A</span> Asisten Cerdas di Belakang Layar</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-sm md:text-base leading-relaxed space-y-4 text-justify transition-colors">
                                    <p>Di masa lalu, mesin Tailwind versi lama harus menyiapkan semua kemungkinan warna dan ukuran padding sebelum kita memintanya. Hasilnya? File CSS mentah bisa seukuran game kecil yang bikin browser ngos-ngosan.</p>
                                    <p>Sekarang, Tailwind dipersenjatai dengan mesin <strong>JIT (Just-In-Time)</strong> yang super cerdas. Mesin JIT bertindak seperti <span class="hl-term">scanner</span> yang memantau file HTML Anda secara aktual. Begitu Anda mengetik <code>text-red-500</code>, saat itu juga JIT menyuntikkan kode merah tersebut ke hasil akhir. Hasilnya, ukuran file CSS yang dikirim ke server production sangat minim dan bersih.</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-base md:text-lg font-bold text-heading flex items-center gap-2 transition-colors"><span class="w-5 h-5 rounded bg-indigo-500 dark:bg-indigo-600 flex items-center justify-center text-[10px] text-white shrink-0">B</span> Keajaiban Arbitrary Values</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-sm md:text-base leading-relaxed space-y-4 text-justify transition-colors">
                                    <p>Kecerdasan mesin JIT melahirkan sebuah fitur yang rasanya seperti sihir: <strong>Arbitrary Values</strong> atau manipulasi nilai presisi sebebas-bebasnya.</p>
                                    <p>Skenario umum: Desain <span class="hl-term">mockup</span> menuntut lebar gambar harus pas <strong>327px</strong>, dan warnanya wajib Hex <strong>#ff00ff</strong>. Angka aneh ini tidak ada di buku standar Tailwind. Dulu, Anda pasti langsung membuat <span class="hl-term">custom class</span> di file terpisah.</p>
                                    <p>Sekarang? Cukup ketik langsung di HTML menggunakan kurung siku: <code>w-[327px]</code> atau <code>bg-[#ff00ff]</code>. Mesin JIT akan mendeteksi kurung siku tersebut dan meracik CSS-nya khusus untuk Anda secara kilat.</p>
                                </div>
                            </div>

                            <div class="card-adaptive border border-adaptive rounded-xl overflow-hidden shadow-xl dark:shadow-2xl flex flex-col md:flex-row h-auto md:min-h-[400px] transition-colors mt-8 relative">
                                
                                {{-- KOTAK INSTRUKSI SIMULATOR 3 --}}
                                <div class="absolute top-0 left-0 w-full bg-purple-600/95 dark:bg-purple-900/95 backdrop-blur text-white p-3 z-20 flex flex-col sm:flex-row sm:justify-between items-start sm:items-center shadow-md gap-2 sm:gap-0">
                                    <div class="flex items-center gap-2 text-xs font-bold shrink-0">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                        PANDUAN SIMULASI 3
                                    </div>
                                    <p class="text-[10px] opacity-90 sm:max-w-xs md:max-w-md sm:text-right leading-tight">Ketik berbagai class utilitas di panel kiri (termasuk gunakan <span class="hl-term">Arbitrary Values</span> dengan kurung siku <code>[]</code>). Perhatikan bagaimana mesin meracik CSS di panel kanan secara spontan!</p>
                                </div>

                                <div class="w-full md:w-1/2 p-4 sm:p-6 pt-28 sm:pt-20 lg:pt-16 flex flex-col gap-4 bg-slate-50 dark:bg-[#18181b] transition-colors border-b md:border-b-0 md:border-r border-adaptive relative z-10 min-h-[250px]">
                                    <h4 class="text-[10px] sm:text-xs font-bold text-muted uppercase transition-colors hidden sm:block">Mesin JIT Scanner (HTML Anda)</h4>
                                    <div class="flex-1 bg-white dark:bg-black/30 border border-adaptive rounded-lg p-4 relative group shadow-inner transition-colors flex flex-col">
                                        <textarea id="jit-input" oninput="runJit()" class="w-full flex-1 bg-transparent text-[11px] sm:text-sm font-mono text-cyan-700 dark:text-cyan-300 outline-none resize-none placeholder-slate-400 dark:placeholder-white/20 z-10 relative leading-loose transition-colors custom-scrollbar" placeholder="Ketik class tailwind apa saja di sini...&#10;&#10;Contoh Referensi:&#10;p-10&#10;text-center&#10;bg-[#ff00ff]&#10;w-[325px]"></textarea>
                                        <div id="scan-line" class="absolute top-0 left-0 w-full h-0.5 bg-purple-500 shadow-[0_0_10px_#a855f7] opacity-0 transition-opacity"></div>
                                    </div>
                                </div>
                                <div class="w-full md:w-1/2 bg-slate-100 dark:bg-[#111] p-4 sm:p-6 lg:pt-16 flex flex-col gap-4 transition-colors relative z-10 min-h-[250px]">
                                    <div class="flex justify-between items-center hidden sm:flex">
                                        <h4 class="text-[10px] sm:text-xs font-bold text-purple-600 dark:text-purple-400 uppercase transition-colors flex items-center gap-2"><svg class="w-4 h-4 animate-spin-slow" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg> Hasil Output CSS Instan</h4>
                                        <div id="jit-badge" class="px-2 py-0.5 bg-slate-200 dark:bg-gray-800 text-[10px] rounded text-slate-500 dark:text-gray-500 font-mono transition-colors border border-adaptive">Idle</div>
                                    </div>
                                    <div id="jit-output" class="flex-1 font-mono text-[10px] sm:text-xs text-slate-600 dark:text-white/70 overflow-auto whitespace-pre leading-relaxed bg-white dark:bg-[#0a0a0a] p-4 rounded-lg border border-adaptive shadow-inner transition-colors custom-scrollbar flex flex-col">
                                        /* Sistem deteksi status siaga memantau aktivitas Anda... */
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 15: ACTIVITY FINAL --}}
                    <section id="section-15" class="lesson-section scroll-mt-32 pt-10 border-t border-adaptive transition-colors" data-lesson-id="15" data-type="activity">
                        <div class="relative rounded-[1.5rem] md:rounded-[2rem] sim-bg-adaptive border border-adaptive p-4 sm:p-6 md:p-10 overflow-hidden shadow-xl dark:shadow-2xl group hover:border-purple-500/30 transition-all duration-500">
                            
                            <div class="absolute -top-24 -right-24 w-40 h-40 md:w-64 md:h-64 bg-purple-600/10 dark:bg-purple-600/20 blur-[100px] rounded-full pointer-events-none transition-colors"></div>

                            <div class="flex items-start md:items-center gap-4 mb-6 md:mb-8 relative z-10 flex-col md:flex-row">
                                <div class="p-3 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl text-white shadow-lg shadow-purple-500/30 shrink-0">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                                </div>
                                <div>
                                    <h2 class="text-xl md:text-2xl font-bold text-heading transition-colors">Tantangan JIT Presisi</h2>
                                    <p class="text-purple-700 dark:text-purple-300 text-xs md:text-sm font-medium mt-1 transition-colors leading-relaxed text-justify">Skenario Lapangan: Desainer memberikan mockup dengan dimensi <span class="hl-term">Pixel Perfect</span> yang tidak ada di standar Tailwind. Jangan mundur untuk menulis CSS manual! Gunakan sihir <strong>Arbitrary Values</strong> (dengan kurung siku) langsung di editor Monaco bawah untuk menaklukkan spesifikasi tak lazim ini.</p>
                                </div>
                            </div>

                            <div class="flex flex-col lg:grid lg:grid-cols-2 gap-6 max-w-6xl mx-auto relative z-10">
                                
                                {{-- LEFT: EDITOR --}}
                                <div class="code-adaptive rounded-xl border border-adaptive flex flex-col overflow-hidden relative shadow-inner transition-colors min-h-[450px] lg:min-h-0 lg:h-[550px]">
                                    
                                    {{-- LOCK OVERLAY --}}
                                    <div id="lockOverlay" class="hidden absolute inset-0 bg-white/90 dark:bg-[#050912]/95 backdrop-blur-md z-50 flex flex-col items-center justify-center text-center p-6 border-4 border-emerald-500/20 m-1 rounded-lg transition-colors">
                                        <div class="w-16 h-16 md:w-24 md:h-24 bg-emerald-100 dark:bg-emerald-500/10 rounded-full flex items-center justify-center mb-4 md:mb-6 border border-emerald-300 dark:border-emerald-500/50 shadow-[0_0_50px_rgba(16,185,129,0.2)] dark:shadow-[0_0_50px_rgba(16,185,129,0.3)] animate-bounce transition-colors">
                                            <svg class="w-8 h-8 md:w-12 md:h-12 text-emerald-600 dark:text-emerald-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                        </div>
                                        <h3 class="text-2xl md:text-3xl font-black text-slate-900 dark:text-white mb-2 tracking-tight transition-colors">PIXEL PERFECT!</h3>
                                        <p class="text-sm md:text-base font-bold text-slate-500 dark:text-white/60 mb-6 md:mb-8 max-w-xs transition-colors">Anda telah menguasai spesifikasi mutlak manipulasi compiler JIT.</p>
                                        <button disabled class="px-6 py-2.5 md:px-8 md:py-3 rounded-full bg-slate-200 dark:bg-white/5 border border-slate-300 dark:border-white/10 text-slate-500 dark:text-white/30 text-[10px] md:text-xs font-bold cursor-not-allowed uppercase tracking-widest transition-colors">Review Mode Only</button>
                                    </div>

                                    <div class="bg-slate-200 dark:bg-[#0f141e] px-4 py-2 border-b border-adaptive flex justify-between items-center transition-colors">
                                        <span class="text-[10px] md:text-xs text-slate-600 dark:text-white/50 font-mono font-bold flex items-center gap-2 transition-colors"><div class="w-2.5 h-2.5 rounded-full bg-indigo-500"></div> JIT-Compiler.html</span>
                                        <button onclick="resetEditor()" class="text-[10px] text-red-500 dark:text-red-400 hover:text-red-600 dark:hover:text-red-300 transition-colors uppercase font-bold">Reset Editor</button>
                                    </div>
                                    
                                    <div id="codeEditor" class="h-[200px] lg:flex-1 w-full border-b border-adaptive transition-colors"></div>

                                    {{-- CLUES AREA DENGAN INSTRUKSI JIT --}}
                                    <div class="p-4 md:p-6 bg-slate-50 dark:bg-[#0f141e] transition-colors flex flex-col h-[250px] lg:h-[280px]">
                                        <div class="flex justify-between items-center mb-4">
                                            <span class="text-[10px] uppercase font-bold text-muted transition-colors">Spesifikasi Klien</span>
                                            <span id="progressText" class="text-[10px] font-mono text-purple-600 dark:text-purple-400 font-bold transition-colors bg-purple-100 dark:bg-purple-900/30 px-2 py-0.5 rounded">0/3 Terpenuhi</span>
                                        </div>
                                        
                                        <div class="space-y-4 text-[10px] md:text-[11px] text-slate-600 dark:text-white/60 mb-6 transition-colors overflow-y-auto custom-scrollbar pr-2 flex-1" id="validation-list">
                                            <div id="chk-w" class="flex items-start gap-3 transition-colors">
                                                <span class="w-4 h-4 mt-0.5 rounded-full border border-slate-300 dark:border-white/20 flex items-center justify-center text-[8px] transition-colors shrink-0"></span> 
                                                <div>
                                                    <strong class="block text-slate-800 dark:text-white mb-0.5">Lebar Presisi</strong>
                                                    <span class="opacity-80">Gunakan kurung siku untuk mengatur lebar tepat <code>327px</code>.</span>
                                                </div>
                                            </div>
                                            <div id="chk-h" class="flex items-start gap-3 transition-colors">
                                                <span class="w-4 h-4 mt-0.5 rounded-full border border-slate-300 dark:border-white/20 flex items-center justify-center text-[8px] transition-colors shrink-0"></span> 
                                                <div>
                                                    <strong class="block text-slate-800 dark:text-white mb-0.5">Tinggi Proporsional</strong>
                                                    <span class="opacity-80">Atur tinggi kotak tersebut tepat di angka <code>200px</code>.</span>
                                                </div>
                                            </div>
                                            <div id="chk-bg" class="flex items-start gap-3 transition-colors">
                                                <span class="w-4 h-4 mt-0.5 rounded-full border border-slate-300 dark:border-white/20 flex items-center justify-center text-[8px] transition-colors shrink-0"></span> 
                                                <div>
                                                    <strong class="block text-slate-800 dark:text-white mb-0.5">Warna Spesifik</strong>
                                                    <span class="opacity-80">Berikan warna latar belakang dengan kode Hex <code>#5b21b6</code>.</span>
                                                </div>
                                            </div>
                                        </div>

                                        <button id="submitExerciseBtn" onclick="checkSolution()" disabled class="w-full py-3 rounded-lg bg-emerald-600 text-white font-bold text-[11px] md:text-xs shadow-lg shadow-emerald-500/20 hover:bg-emerald-500 transition-all cursor-not-allowed opacity-50 flex items-center justify-center gap-2 mt-auto shrink-0">
                                            <span>Validasi Kode JIT</span>
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </button>
                                    </div>
                                </div>

                                {{-- RIGHT: BROWSER PREVIEW --}}
                                <div class="bg-white dark:bg-[#1e1e1e] rounded-xl border border-adaptive flex flex-col relative overflow-hidden shadow-sm dark:shadow-none transition-colors min-h-[300px] lg:min-h-0 lg:h-[550px]">
                                    <div class="bg-slate-100 dark:bg-[#2d2d2d] px-4 py-2 border-b border-adaptive flex items-center justify-between transition-colors shrink-0">
                                        <span class="text-[10px] md:text-xs text-slate-500 dark:text-gray-400 font-mono font-bold transition-colors">Layar Klien (Preview)</span>
                                        <span class="text-[10px] bg-emerald-100 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 px-2 py-0.5 rounded border border-emerald-200 dark:border-emerald-500/20 font-bold transition-colors">Live Compilation</span>
                                    </div>
                                    <div class="flex-1 bg-slate-50 dark:bg-gray-900 p-2 sm:p-6 flex items-center justify-center relative overflow-hidden transition-colors w-full h-full">
                                        <iframe id="previewFrame" class="w-full h-full border-0 bg-transparent transition-colors"></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                </article>

                {{-- FOOTER NAVIGATION --}}
                <div class="mt-20 md:mt-32 pt-8 border-t border-adaptive flex flex-col sm:flex-row justify-between items-center gap-4 sm:gap-0 transition-colors">
                    <a href="{{ route('courses.tailwindcss') ?? '#' }}" class="group flex items-center gap-4 text-muted hover:text-heading transition-colors w-full sm:w-auto justify-center sm:justify-start">
                        <div class="w-10 h-10 md:w-12 md:h-12 rounded-full border border-adaptive flex items-center justify-center group-hover:bg-slate-100 dark:group-hover:bg-white/5 transition-colors shrink-0">
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        </div>
                        <div class="text-left">
                            <div class="text-[10px] uppercase tracking-widest font-bold opacity-60">Sebelumnya</div>
                            <div class="font-black text-xs md:text-sm line-clamp-1">Konsep Dasar Tailwind</div>
                        </div>
                    </a>
                    
                    <div id="nextChapterBtn" class="group flex items-center gap-4 text-right text-muted cursor-not-allowed opacity-50 pointer-events-none transition-all duration-500 w-full sm:w-auto justify-center sm:justify-end">
                        <div class="text-right">
                            <div id="nextLabel" class="text-[10px] uppercase tracking-widest font-bold opacity-60">Terkunci</div>
                            <div class="font-black text-xs md:text-sm line-clamp-1">Penerapan Utility Class</div>
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

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/monaco-editor@0.45.0/min/vs/loader.js"></script>

<script>
    /* --- CONFIGURATION AJAX & PROGRESS --- */
    window.LESSON_IDS = [12, 13, 14, 15]; 
    
    let rawCompletedIds = {!! json_encode($completedLessonIds ?? []) !!};
    window.COMPLETED_IDS = rawCompletedIds.map(id => Number(id)); 
    let completedSet = new Set(window.COMPLETED_IDS);
    
    const ACTIVITY_LESSON_ID = 15; 
    let activityCompleted = completedSet.has(ACTIVITY_LESSON_ID);

    document.addEventListener('DOMContentLoaded', () => {
        initScrollSpy();
        initSidebarScroll();
        initVisualEffects();
        
        updateProgressUI(false); 
        
        simulateGrowth();
        highlightLayer('util'); 
        initMonaco();
        
        if (activityCompleted) {
            lockActivityUI();
            unlockNextChapter();
        }

        initLessonObserver();
        
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
    // OBSERVER SCROLL (DENGAN THRESHOLD 0.1)
    // ==========================================
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
            rootMargin: "0px 0px -100px 0px", 
            root: document.getElementById('mainScroll') 
        });
        
        document.querySelectorAll('.lesson-section').forEach(s => obs.observe(s));
    }

    /* ==========================================
       SIMULATOR 1: FILE SIZE GROWTH
       ========================================== */
    function simulateGrowth() {
        const barTrad = document.getElementById('bar-trad');
        const txtTrad = document.getElementById('trad-size');
        const barTw = document.getElementById('bar-tw');
        const txtTw = document.getElementById('tw-size');
        const btn = document.getElementById('growBtn');
        
        btn.disabled = true;
        btn.innerHTML = `<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg> Menambahkan Fitur...`;

        let tradW = parseInt(barTrad.style.width) || 5;
        let twW = parseInt(barTw.style.width) || 5;

        let newTradW = tradW + 15;
        if(newTradW > 100) newTradW = 100;

        let newTwW = twW;
        if(twW < 20) newTwW = twW + 5; 
        else newTwW = twW + 1; // Plateau effect

        barTrad.style.width = newTradW + '%';
        txtTrad.innerText = (newTradW * 5) + ' KB';

        barTw.style.width = newTwW + '%';
        txtTw.innerText = (newTwW * 2) + ' KB';

        setTimeout(() => {
            btn.disabled = false;
            btn.innerHTML = `<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg> Tambah 10 Fitur Baru di Web`;
            if(newTradW >= 100) {
                btn.innerHTML = `<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg> Reset Simulasi Engine`;
                btn.onclick = () => {
                    barTrad.style.width = '5%'; txtTrad.innerText = '10 KB';
                    barTw.style.width = '5%'; txtTw.innerText = '10 KB';
                    btn.onclick = simulateGrowth;
                    btn.innerHTML = `<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg> Tambah 10 Fitur Baru di Web`;
                };
            }
        }, 800);
    }

  /* ==========================================
       SIMULATOR 2: LAYER STACK
       ========================================== */
    function highlightLayer(layer) {
        const isDark = document.documentElement.classList.contains('dark');
        ['base', 'comp', 'util'].forEach(l => {
            const el = document.getElementById('layer-'+l);
            el.classList.remove('opacity-100', 'scale-105', '-translate-y-2', 'ring-2', 'ring-slate-400', 'dark:ring-white');
            el.classList.add('opacity-50', 'translate-y-0');
        });
        
        const active = document.getElementById('layer-'+layer);
        active.classList.remove('opacity-50', 'translate-y-0');
        active.classList.add('opacity-100', 'scale-105', '-translate-y-2', 'ring-2', isDark ? 'dark:ring-white' : 'ring-slate-400');

        const desc = document.getElementById('layer-desc');
        
        if(layer === 'base') {
            desc.innerHTML = `
                <div class='flex flex-col items-center justify-center w-full h-full'>
                    <strong class='text-rose-600 dark:text-red-400 text-center mb-2'>BASE LAYER</strong>
                    <p class='text-center m-0'>Lapisan paling rendah untuk mereset desain bawaan peramban (<span class='hl-term'>Preflight</span>).</p>
                </div>`;
        }
        
        if(layer === 'comp') {
            desc.innerHTML = `
                <div class='flex flex-col items-center justify-center w-full h-full'>
                    <strong class='text-amber-600 dark:text-yellow-400 text-center mb-2'>COMPONENTS LAYER</strong>
                    <p class='text-center m-0'>Lapisan penyimpanan utama bagi abstraksi kelas repetitif desain Anda sendiri seperti komponen <code>.card</code> atau <code>.btn</code>.</p>
                </div>`;
        }
        
        if(layer === 'util') {
            desc.innerHTML = `
                <div class='flex flex-col items-center justify-center w-full h-full'>
                    <strong class='text-emerald-600 dark:text-emerald-400 text-center mb-2'>UTILITIES LAYER</strong>
                    <p class='text-center m-0'>Singgasana tertinggi. Markas bagi kelas atomik Tailwind (seperti <code>flex</code>, <code>p-4</code>).</p>
                </div>`;
        }
    }
    /* ==========================================
       SIMULATOR 3: JIT COMPILER
       ========================================== */
    function runJit() {
        const isDark = document.documentElement.classList.contains('dark');
        const input = document.getElementById('jit-input').value;
        const output = document.getElementById('jit-output');
        const badge = document.getElementById('jit-badge');
        const scanLine = document.getElementById('scan-line');
        
        badge.innerText = "Processing...";
        badge.className = `px-2 py-0.5 ${isDark ? 'bg-yellow-500/20 text-yellow-400' : 'bg-amber-100 text-amber-600'} text-[10px] rounded animate-pulse font-mono transition-colors border ${isDark ? 'border-transparent' : 'border-amber-300'}`;
        scanLine.style.opacity = 1;
        scanLine.style.top = Math.random() * 100 + '%';

        setTimeout(() => {
            let css = '';
            if(!input) {
                css = `<div class="text-slate-400 dark:text-white/20 italic transition-colors">/* Sistem memantau pengetikan Anda... */</div>`;
            } else {
                const classes = input.split(/[\s\n]+/);
                classes.forEach(cls => {
                    const clean = cls.trim();
                    if(clean) {
                        const hlClass = isDark ? 'text-purple-400' : 'text-purple-600 font-bold';
                        const textClass = isDark ? 'text-white' : 'text-slate-800';
                        if(clean.startsWith('bg-[')) {
                            const val = clean.match(/\[(.*?)\]/)[1];
                            css += `<div>.<span class="${hlClass}">${clean.replace('[','\\[').replace(']','\\]')}</span> { \n  <span class="text-blue-500 dark:text-blue-400">background-color</span>: <span class="${textClass}">${val}</span>; \n}</div>\n`;
                        } else if(clean.startsWith('w-[')) {
                            const val = clean.match(/\[(.*?)\]/)[1];
                            css += `<div>.<span class="${hlClass}">${clean.replace('[','\\[').replace(']','\\]')}</span> { \n  <span class="text-blue-500 dark:text-blue-400">width</span>: <span class="${textClass}">${val}</span>; \n}</div>\n`;
                        } else if(clean.startsWith('p-')) {
                            const num = clean.split('-')[1];
                            if(!isNaN(num) && num !== "") {
                                const val = num * 0.25;
                                css += `<div>.<span class="${hlClass}">${clean}</span> { \n  <span class="text-blue-500 dark:text-blue-400">padding</span>: <span class="${textClass}">${val}rem</span>; \n}</div>\n`;
                            } else {
                                css += `<div>.<span class="${hlClass}">${clean}</span> { <span class="text-slate-400 dark:text-gray-500 italic">/* JIT Compiled */</span> }</div>\n`;
                            }
                        } else {
                            css += `<div>.<span class="${hlClass}">${clean}</span> { <span class="text-slate-400 dark:text-gray-500 italic">/* JIT Compiled */</span> }</div>\n`;
                        }
                    }
                });
            }
            output.innerHTML = css;
            badge.innerText = "Compiled";
            badge.className = `px-2 py-0.5 ${isDark ? 'bg-green-500/20 text-green-400' : 'bg-emerald-100 text-emerald-700'} text-[10px] rounded font-mono transition-colors border ${isDark ? 'border-transparent' : 'border-emerald-300'}`;
            scanLine.style.opacity = 0;
        }, 400);
    }

    /* ==========================================
       REALTIME CODING & ACTIVITY LOGIC (Lesson 15)
       ========================================== */
    let editor;
    const starterCode = `<div class="rounded-[20px]">
  </div>
`;

    function initMonaco() {
        require.config({ paths: { vs: 'https://cdn.jsdelivr.net/npm/monaco-editor@0.45.0/min/vs' } });
        require(['vs/editor/editor.main'], function () {
            
            const isDark = document.documentElement.classList.contains('dark');
            
            editor = monaco.editor.create(document.getElementById('codeEditor'), {
                value: starterCode, 
                language: 'html', 
                theme: isDark ? 'vs-dark' : 'vs', 
                fontSize: window.innerWidth < 768 ? 11 : 13,
                minimap: { enabled: false }, 
                automaticLayout: true, 
                padding: { top: 16 }, 
                lineNumbers: 'off',
                scrollBeyondLastLine: false,
                wordWrap: 'on'
            });
            updatePreview(starterCode);
            
            editor.onDidChangeModelContent(() => {
                if(activityCompleted) return;
                const code = editor.getValue();
                updatePreview(code);
                validateCode(code);
            });
            
            window.addEventListener('theme-toggled', () => {
                const newIsDark = document.documentElement.classList.contains('dark');
                monaco.editor.setTheme(newIsDark ? 'vs-dark' : 'vs');
            });
        });
    }

    function updatePreview(code) {
        const frame = document.getElementById('previewFrame');
        const isDark = document.documentElement.classList.contains('dark');
        const bgColor = isDark ? '#111827' : '#f8fafc'; 
        
        const content = `<!doctype html><html><head><script src="https://cdn.tailwindcss.com"><\/script><style>body { background-color: ${bgColor}; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; padding: 1rem; font-family: sans-serif; transition: background-color 0.3s; box-sizing: border-box; overflow: hidden; }</style></head><body>${code}</body></html>`;
        frame.srcdoc = content;
    }

    function validateCode(code) {
        const checks = [
            { id: 'chk-w', regex: /w-\[327px\]/, valid: false },
            { id: 'chk-h', regex: /h-\[200px\]/, valid: false },
            { id: 'chk-bg', regex: /bg-\[#5b21b6\]/, valid: false }
        ];
        let passedCount = 0;
        const isDark = document.documentElement.classList.contains('dark');

        checks.forEach(check => {
            const el = document.getElementById(check.id);
            if(!el) return;
            const dot = el.querySelector('span'); 
            const textContainer = el.querySelector('div'); 

            if (check.regex.test(code)) {
                textContainer.classList.remove('opacity-80');
                textContainer.classList.add(isDark ? 'text-green-400' : 'text-emerald-600');
                
                dot.innerHTML = '✓'; 
                dot.classList.remove('border-slate-300', 'border-white/20');
                dot.classList.add('bg-emerald-500', 'border-transparent', 'text-white');
                passedCount++;
            } else {
                textContainer.classList.add('opacity-80');
                textContainer.classList.remove('text-green-400', 'text-emerald-600');
                
                dot.innerHTML = ''; 
                dot.classList.remove('bg-emerald-500', 'border-transparent', 'text-white');
                dot.classList.add(isDark ? 'border-white/20' : 'border-slate-300');
            }
        });
        
        document.getElementById('progressText').innerText = `${passedCount}/3 Terpenuhi`;
        const btn = document.getElementById('submitExerciseBtn');
        if (passedCount === 3) {
            btn.disabled = false; btn.classList.remove('cursor-not-allowed', 'opacity-50');
            btn.innerHTML = `<span>Validasi Kode JIT Spesifikasi</span><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`;
        } else {
            btn.disabled = true; btn.classList.add('cursor-not-allowed', 'opacity-50');
            btn.innerHTML = `<span>Validasi Kode JIT Spesifikasi</span><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`;
        }
    }

    function resetEditor() { if(editor && !activityCompleted) { editor.setValue(starterCode); validateCode(starterCode); } }

    async function checkSolution() {
        if(activityCompleted) return;
        const btn = document.getElementById('submitExerciseBtn');
        
        btn.innerHTML = '<span class="animate-pulse">Menyimpan ke Server...</span>'; 
        btn.disabled = true;
        
        try {
            await saveLessonToDB(ACTIVITY_LESSON_ID); 
            
            activityCompleted = true; 
            lockActivityUI(); 
            unlockNextChapter(); 
        } catch(e) { 
            console.error(e); 
            btn.innerHTML = "Gagal Terhubung. Coba Ulangi."; 
            btn.disabled = false; 
        }
    }

    function lockActivityUI() {
        document.getElementById('lockOverlay').classList.remove('hidden');
        if(editor) editor.updateOptions({ readOnly: true });
        
        const btn = document.getElementById('submitExerciseBtn'); 
        btn.innerText = "Penyimpanan Arsitektur Terkunci (Sukses)"; 
        btn.disabled = true;
        btn.classList.remove('bg-emerald-600', 'hover:bg-emerald-500');
        btn.classList.add('bg-slate-400', 'dark:bg-slate-700', 'text-slate-200', 'cursor-not-allowed', 'shadow-none');

        if(editor && activityCompleted) {
            editor.setValue(`<div class="w-[327px] h-[200px] bg-[#5b21b6] rounded-[20px]"></div>`);
            validateCode(editor.getValue());
        }
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
            icon.innerHTML = `<svg class="w-4 h-4 md:w-5 md:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>`;
            icon.classList.remove('bg-slate-100', 'dark:bg-white/5');
            icon.classList.add('bg-indigo-100', 'dark:bg-indigo-500/20', 'border-indigo-300', 'dark:border-indigo-500/50', 'text-indigo-600', 'dark:text-indigo-400', 'shadow-lg');
            
            btn.onclick = () => window.location.href = "{{ route('courses.implementation') ?? '#' }}"; 
        }
    }

    /* ==========================================
       7. SCROLL SPY & SIDEBAR LOGIC
       ========================================== */
    function highlightAnchor(id) {
        const isDark = document.documentElement.classList.contains('dark');
        const anchors = document.querySelectorAll('.sidebar-anchor');

        anchors.forEach(a => {
            a.classList.remove('bg-slate-100', 'dark:bg-white/5', 'border-purple-500', 'border-amber-500');
            a.classList.add('border-transparent');
            
            const dot = a.querySelector('.anchor-dot');
            const isActivity = a.dataset.type === 'activity';

            dot.classList.remove('scale-125', 'shadow-sm', 'dark:shadow-[0_0_10px_#a855f7]', 'dark:shadow-[0_0_10px_#f59e0b]', 'bg-purple-500', 'dark:bg-purple-400');
            
            if (isActivity) {
                dot.classList.remove('bg-amber-500', 'dark:bg-amber-400');
                dot.classList.add('bg-slate-400', 'dark:bg-slate-600'); 
            } else {
                dot.classList.remove('bg-purple-500', 'dark:bg-purple-400');
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
            activeAnchor.classList.add(isActivity ? 'border-amber-500' : 'border-purple-500');
            activeAnchor.classList.remove('border-transparent');
            
            const dot = activeAnchor.querySelector('.anchor-dot');
            dot.classList.remove('bg-slate-400', 'dark:bg-slate-600');
            
            if (isActivity) {
                dot.classList.add(isDark ? 'dark:bg-amber-400' : 'bg-amber-500', 'scale-125', isDark ? 'dark:shadow-[0_0_10px_#f59e0b]' : 'shadow-sm');
            } else {
                dot.classList.add(isDark ? 'dark:bg-purple-400' : 'bg-purple-500', 'scale-125', isDark ? 'dark:shadow-[0_0_10px_#a855f7]' : 'shadow-sm');
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