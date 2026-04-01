@extends('layouts.landing')
@section('title','Keunggulan Tailwind CSS')

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
        --accent: #06b6d4;
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
    
    /* SCROLLSPY SIDEBAR ACTIVE */
    .nav-item { display: flex; width: 100%; text-align: left; align-items: center; gap: 12px; padding: 10px 14px; font-size: 0.85rem; color: var(--text-muted); border-radius: 8px; transition: all 0.2s; position: relative; }
    .nav-item:hover { color: var(--text-main); background: var(--card-hover); }
    .nav-item.active { color: #06b6d4; background: rgba(6, 182, 212, 0.05); font-weight: 600; }
    .dot { width: 6px; height: 6px; border-radius: 50%; background: #94a3b8; transition: all 0.3s; }
    .dark .dot { background: #334155; }
    .nav-item.active .dot { background: #06b6d4; box-shadow: 0 0 8px #06b6d4; transform: scale(1.2); }

    /* CSS GRID UNTUK SIMULATOR 2 */
    .visual-grid {
        background-size: 16px 16px;
        background-image: 
            linear-gradient(to right, rgba(150, 150, 150, 0.1) 1px, transparent 1px),
            linear-gradient(to bottom, rgba(150, 150, 150, 0.1) 1px, transparent 1px);
    }
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
                    <div class="w-8 h-8 rounded-lg bg-cyan-500/10 dark:bg-cyan-500/20 border border-cyan-500/20 flex items-center justify-center font-bold text-xs text-cyan-600 dark:text-cyan-400 transition-colors">1.5</div>
                    <div>
                        <h1 class="text-sm font-bold text-heading transition-colors">Keunggulan Tailwind CSS</h1>
                        <p class="text-[10px] text-muted transition-colors">Speed, Consistency & Performance</p>
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
                            <path stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        Tujuan Pembelajaran
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="card-adaptive border border-adaptive p-6 rounded-xl flex items-start gap-4 hover:border-purple-500/30 transition group h-full">
                            <div class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400 flex items-center justify-center shrink-0 font-bold text-sm border border-purple-200 dark:border-purple-500/10 transition-colors">1</div>
                            <div>
                                <h4 class="text-sm font-bold text-heading mb-2 group-hover:text-purple-500 dark:group-hover:text-purple-400 transition-colors">Menghancurkan Jeda</h4>
                                <p class="text-xs text-muted leading-relaxed transition-colors">Memahami bagaimana <span class="font-semibold text-purple-600 dark:text-purple-400">Context Switching</span> dan <span class="font-semibold text-purple-600 dark:text-purple-400">Naming Fatigue</span> membunuh kecepatan, dan bagaimana Tailwind menyelesaikannya.</p>
                            </div>
                        </div>

                        <div class="card-adaptive border border-adaptive p-6 rounded-xl flex items-start gap-4 hover:border-cyan-500/30 transition group h-full">
                            <div class="w-10 h-10 rounded-lg bg-cyan-100 dark:bg-cyan-900/20 text-cyan-600 dark:text-cyan-400 flex items-center justify-center shrink-0 font-bold text-sm border border-cyan-200 dark:border-cyan-500/10 transition-colors">2</div>
                            <div>
                                <h4 class="text-sm font-bold text-heading mb-2 group-hover:text-cyan-500 dark:group-hover:text-cyan-400 transition-colors">Sistem Desain Mengikat</h4>
                                <p class="text-xs text-muted leading-relaxed transition-colors">Meninggalkan <span class="font-semibold text-cyan-600 dark:text-cyan-400">Magic Numbers</span> dengan mematuhi skala desain Tailwind yang matematis dan presisi.</p>
                            </div>
                        </div>

                        <div class="card-adaptive border border-adaptive p-6 rounded-xl flex items-start gap-4 hover:border-emerald-500/30 transition group h-full">
                            <div class="w-10 h-10 rounded-lg bg-emerald-100 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 font-bold text-sm border border-emerald-200 dark:border-emerald-500/10 transition-colors">3</div>
                            <div>
                                <h4 class="text-sm font-bold text-heading mb-2 group-hover:text-emerald-500 dark:group-hover:text-emerald-400 transition-colors">Optimasi Performa JIT</h4>
                                <p class="text-xs text-muted leading-relaxed transition-colors">Menganalisis fenomena <span class="font-semibold text-emerald-600 dark:text-emerald-400">Append-Only CSS</span> dan bagaimana mesin kompilator Just-In-Time mengatasinya.</p>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-cyan-100 to-indigo-100 dark:from-cyan-900/10 dark:to-indigo-900/10 border border-cyan-300 dark:border-cyan-500/20 p-6 rounded-xl flex items-start gap-4 hover:shadow-[0_0_30px_rgba(6,182,212,0.15)] transition group h-full col-span-1 md:col-span-3 cursor-default">
                            <div class="w-10 h-10 rounded-lg bg-white/50 dark:bg-white/10 text-cyan-700 dark:text-white flex items-center justify-center shrink-0 font-bold text-lg border border-white/10 shadow-sm dark:shadow-none transition-colors">🏁</div>
                            <div>
                                <h4 class="text-sm font-bold text-cyan-900 dark:text-white mb-2 transition-colors">Final Mission: Layout Architect</h4>
                                <p class="text-xs text-cyan-800 dark:text-white/60 leading-relaxed max-w-2xl transition-colors">Buktikan pemahaman konseptual Anda. Konstruksikan sebuah kartu profil dengan arsitektur Flexbox secara bertahap di lingkungan <span class="font-semibold text-cyan-700 dark:text-cyan-300">live-code</span>.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <article class="space-y-40">
                    
                    {{-- LESSON 20: RAPID PROTOTYPING --}}
                    <section id="section-20" class="lesson-section scroll-mt-32" data-lesson-id="20">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-purple-500 pl-6">
                                <span class="text-purple-600 dark:text-purple-400 font-mono text-xs uppercase tracking-widest transition-colors">Lesson 1.5.1</span>
                                <h2 class="text-4xl lg:text-5xl font-black text-heading leading-[1.1] transition-colors">
                                    Kecepatan: <br> 
                                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-500 to-indigo-600 dark:from-purple-400 dark:to-indigo-500">Menghancurkan Jeda Berpikir</span>
                                </h2>
                            </div>
                            
                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-heading flex items-center gap-2 transition-colors"><span class="w-6 h-6 rounded bg-purple-500 dark:bg-purple-600 flex items-center justify-center text-[10px] text-white">A</span> Kelelahan Penamaan (Naming Fatigue)</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-lg leading-relaxed space-y-4 text-justify transition-colors">
                                    <p>Sebelum adanya Tailwind, industri <span class="font-semibold text-purple-600 dark:text-purple-400">frontend</span> sangat bergantung pada metodologi <strong>BEM (Block, Element, Modifier)</strong>. BEM awalnya diciptakan untuk memecahkan masalah <em>global namespace</em> pada CSS. Metode ini memaksa Anda memecah struktur HTML menjadi komponen bernama logis yang panjang. Namun sadar atau tidak, sebagian besar kapasitas otak Anda akhirnya habis terkuras hanya untuk memikirkan nama yang ideal dan memastikan nama tersebut tidak bentrok dengan class lain di sistem Anda.</p>
                                    <p>Apakah kontainer luar ini harus dinamakan <code>.profile-card__wrapper</code>, <code>.user-info-container</code>, atau sekadar <code>.card-box</code>? Dalam buku <em>"Modern CSS with Tailwind"</em>, Noel Rappin menyoroti bahwa merawat sistem penamaan semantik di proyek skala besar akan memicu <span class="font-semibold text-purple-600 dark:text-purple-400">Naming Fatigue</span> (Kelelahan Penamaan). Anda menghabiskan 30% waktu untuk menulis gaya, dan 70% waktu untuk berdebat dengan diri sendiri tentang penamaan. Tailwind CSS hadir untuk menghancurkan lapisan abstraksi tersebut. Alih-alih membuat "nama entitas" dari sebuah kotak, Anda mendeskripsikan <strong>properti fisiknya secara harfiah</strong>: <code>bg-blue-500 p-4 rounded-lg</code>. Energi mental Anda bisa difokuskan murni pada <em>problem solving</em> arsitektur UI, bukan pada inventarisasi kamus kata.</p>
                                </div>
                            </div>

                            <div class="space-y-4 pt-6">
                                <h3 class="text-xl font-bold text-heading flex items-center gap-2 transition-colors"><span class="w-6 h-6 rounded bg-indigo-500 dark:bg-indigo-600 flex items-center justify-center text-[10px] text-white">B</span> Kematian "Context Switching"</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-lg leading-relaxed space-y-4 text-justify transition-colors">
                                    <p>Hambatan kedua yang paling mematikan bagi produktivitas seorang programmer adalah <span class="font-semibold text-purple-600 dark:text-purple-400">Context Switching</span> (Perpindahan Konteks). Otak manusia sangat buruk dalam memelihara dua konteks informasi (struktur HTML vs presentasi Visual) secara bersamaan. Saat mendesain dengan CSS murni, Anda dipaksa membelah fokus antara dua dunia:</p>
                                    <ul class="list-disc pl-5 opacity-90 space-y-1">
                                        <li>Membuka file <code>index.html</code> (atau <code>.blade.php</code>) untuk melihat kerangka DOM dan mengingat nama kelasnya.</li>
                                        <li>Pindah tab ke file <code>style.css</code> eksternal untuk menulis aturan visualnya.</li>
                                        <li>Mencari baris kode spesifik tempat kelas tersebut didefinisikan.</li>
                                        <li>Kembali lagi ke HTML untuk mengecek apakah hirarki elemen yang Anda maksud sudah benar.</li>
                                    </ul>
                                    <p>Di era modern pengembangan web, di mana kita sudah menggunakan arsitektur berbasis komponen (seperti React, Vue, atau Blade Components), abstraksi pemisahan file HTML dan CSS ini sudah kehilangan relevansinya. Siklus perpindahan berkali-kali ini membunuh <strong>Flow State</strong> (momentum fokus mendalam). Tailwind memusatkan seluruh kegiatan <span class="font-semibold text-purple-600 dark:text-purple-400">styling</span> Anda murni ke dalam satu titik koordinat: di dalam atribut <code>class</code> elemen itu sendiri. Kecepatan pembuatan kerangka awal (<em>rapid prototyping</em>) menjadi nyaris instan karena saat mata Anda menatap kode DOM, otak Anda langsung tahu persis wujud elemen tersebut tanpa perlu menerjemahkan lapisan abstraksi.</p>
                                </div>
                            </div>

                            {{-- SIMULATOR 1: TYPING FRICTION TEST --}}
                            <div class="sim-bg-adaptive border border-adaptive rounded-xl overflow-hidden shadow-xl dark:shadow-2xl p-8 relative transition-colors mt-8">
                                <h4 class="text-xs font-bold text-muted uppercase mb-4 text-center transition-colors">Simulasi Logis: Dampak Beban Waktu Context Switching</h4>
                                
                                {{-- KOTAK INSTRUKSI SIMULATOR 1 --}}
                                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-500/30 rounded-lg p-4 mb-8 text-sm text-blue-800 dark:text-blue-300 relative z-10">
                                    <p class="font-bold flex items-center gap-2 mb-2">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        💡 Panduan Simulasi 1
                                    </p>
                                    <ol class="list-decimal pl-5 space-y-1">
                                        <li>Pada panel <strong>TRADISIONAL CSS</strong> (kiri), cobalah ketik nama class sembarang seperti <code>.avatar-image</code>. Perhatikan bahwa elemen di layar tidak akan berubah karena dalam sistem tradisional, Anda <em>harus</em> berpindah tab ke CSS external untuk memberinya gaya.</li>
                                        <li>Pada panel <strong>TAILWIND UTILITY</strong> (kanan), ketik persis instruksi ini: <code>rounded-full bg-blue-500</code>. Perhatikan bagaimana elemen langsung bereaksi secara instan tanpa Anda harus memikirkan nama komponen atau berpindah dokumen.</li>
                                    </ol>
                                </div>

                                <div class="grid md:grid-cols-2 gap-10 relative z-10">
                                    {{-- Kiri: BEM --}}
                                    <div class="space-y-4 bg-red-50 dark:bg-red-900/5 p-6 rounded-xl border border-red-200 dark:border-red-500/10 transition-colors shadow-inner dark:shadow-none flex flex-col relative overflow-hidden">
                                        <div class="flex justify-between items-center mb-2 relative z-10">
                                            <div class="text-xs text-red-600 dark:text-red-400 font-bold transition-colors">TRADISIONAL CSS (BEM)</div>
                                        </div>
                                        <p class="text-[10px] text-slate-500 dark:text-gray-400 transition-colors relative z-10">Coba ketik nama class BEM abstrak untuk membuat avatar ini bulat. <br><em>Catatan: Di sistem tradisional, layar <strong>tidak akan berubah</strong> sebelum Anda pindah ke file CSS.</em></p>
                                        
                                        <input type="text" placeholder="Cth: .profile-card__avatar-image" 
                                            class="w-full bg-white dark:bg-black/50 border border-slate-300 dark:border-white/10 rounded px-3 py-2 text-xs text-slate-800 dark:text-white focus:border-red-500 outline-none font-mono transition-colors relative z-10"
                                            onkeyup="sim1Trad(this.value)">
                                        
                                        <div id="sim1-trad-status" class="text-[10px] text-red-500 dark:text-red-400 font-mono h-8 leading-snug transition-colors relative z-10">
                                            Menunggu penamaan class abstrak...
                                        </div>

                                        <div class="relative w-full h-2 bg-slate-200 dark:bg-gray-800 rounded-full overflow-hidden transition-colors mt-2 z-10">
                                            <div id="sim1-meter" class="absolute top-0 left-0 h-full bg-red-500 w-0 transition-all duration-200"></div>
                                        </div>
                                        <p id="sim1-text" class="text-[9px] text-red-500 dark:text-red-400 font-bold mt-1 relative z-10">Context Switch & Naming Fatigue: 0%</p>

                                        <div class="w-16 h-16 border border-dashed border-slate-400 dark:border-white/30 flex items-center justify-center text-[10px] text-slate-400 mt-6 mx-auto rounded bg-slate-100 dark:bg-transparent relative z-10">
                                            Avatar
                                        </div>
                                    </div>

                                    {{-- Kanan: Tailwind --}}
                                    <div class="space-y-4 bg-emerald-50 dark:bg-emerald-900/5 p-6 rounded-xl border border-emerald-200 dark:border-emerald-500/10 transition-colors shadow-inner dark:shadow-none flex flex-col relative overflow-hidden">
                                        <div class="flex justify-between items-center mb-2 relative z-10">
                                            <div class="text-xs text-emerald-600 dark:text-emerald-400 font-bold transition-colors">TAILWIND UTILITY</div>
                                        </div>
                                        <p class="text-[10px] text-slate-500 dark:text-gray-400 transition-colors relative z-10">Abaikan penamaan. Langsung ketik instruksi utilitas harfiah untuk membulatkan gambar dan memberinya warna biru (ketik: <code>rounded-full bg-blue-500</code>). Elemen akan bereaksi instan!</p>
                                        
                                        <input type="text" placeholder="Ketik: rounded-full bg-blue-500" 
                                            class="w-full bg-white dark:bg-black/50 border border-slate-300 dark:border-white/10 rounded px-3 py-2 text-xs text-slate-800 dark:text-white focus:border-emerald-500 outline-none font-mono transition-colors relative z-10"
                                            onkeyup="sim1Tw(this.value)">

                                        <div id="sim1-tw-status" class="text-[10px] text-emerald-600 dark:text-emerald-400 font-mono h-8 leading-snug transition-colors relative z-10">
                                            Menunggu utility class diketik...
                                        </div>
                                        
                                        <div class="relative w-full h-2 bg-transparent rounded-full overflow-hidden transition-colors mt-2 z-10"></div>
                                        <p class="text-[9px] text-emerald-600 dark:text-emerald-400 font-bold mt-1 relative z-10">Fokus Terjaga: 100%</p>

                                        <div id="sim1-tw-box" class="w-16 h-16 border border-dashed border-slate-400 dark:border-white/30 flex items-center justify-center text-[10px] text-slate-400 mt-6 mx-auto rounded transition-all duration-300 bg-slate-100 dark:bg-transparent relative z-10">
                                            Avatar
                                        </div>
                                    </div>
                                </div>
                                
                                {{-- KESIMPULAN SIMULASI 1 --}}
                                <div class="mt-8 bg-gradient-to-r from-purple-50 to-transparent dark:from-purple-900/20 dark:to-transparent border-l-4 border-purple-500 p-5 rounded-r-xl relative z-10 transition-colors">
                                    <h5 class="text-sm font-bold text-purple-700 dark:text-purple-400 mb-2 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> 
                                        Kesimpulan Analisis Kecepatan
                                    </h5>
                                    <p class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed text-justify">
                                        Waktu yang terbuang untuk mengarang nama elemen dan berpindah bolak-balik antara file HTML dan CSS secara sekilas terlihat sepele. Namun, jika diakumulasikan sepanjang ratusan baris kode dalam proyek pengembangan skala menengah hingga besar, perpindahan ini merupakan <strong>penghambat momentum yang fatal</strong>. Pendekatan <span class="font-semibold">Utility-First</span> pada Tailwind mengamputasi kedua hambatan ini secara permanen, memungkinkan developer memasuki "Flow State" di mana ide desain bisa langsung diterjemahkan menjadi kode seketika.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 21: CONSISTENCY --}}
                    <section id="section-21" class="lesson-section scroll-mt-32" data-lesson-id="21">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-cyan-500 pl-6">
                                <span class="text-cyan-600 dark:text-cyan-400 font-mono text-xs uppercase tracking-widest transition-colors">Lesson 1.5.2</span>
                                <h2 class="text-4xl lg:text-5xl font-black text-heading leading-[1.1] transition-colors">
                                    Konsistensi: <br> 
                                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-500 to-blue-600 dark:from-cyan-400 dark:to-blue-500">Constraint-Based System</span>
                                </h2>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-heading flex items-center gap-2 transition-colors"><span class="w-6 h-6 rounded bg-cyan-500 dark:bg-cyan-600 flex items-center justify-center text-[10px] text-white">A</span> Membasmi "Magic Numbers"</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-lg leading-relaxed space-y-4 text-justify transition-colors">
                                    <p>Dalam panduan <em>Tailwind CSS by SitePoint</em>, ditekankan bahwa kebebasan absolut dalam CSS murni seringkali berujung pada kekacauan antarmuka. Mengapa? Karena jika setiap <span class="font-semibold text-cyan-600 dark:text-cyan-400">developer</span> dalam satu tim diizinkan menulis nilai spasial sesuka hati (misalnya si A menulis <code>padding: 13px;</code>, lalu si B di file terpisah menulis <code>padding: 17px;</code> untuk fungsi yang mirip), hasilnya adalah aplikasi yang secara visual compang-camping, asimetris, dan kehilangan konsistensi <em>brand</em>. Nilai-nilai tak berdasar yang ditebak-tebak ini diistilahkan dalam industri sebagai <span class="font-semibold text-cyan-600 dark:text-cyan-400">"Magic Numbers"</span> (Angka Siluman).</p>
                                    <p>Tailwind didirikan di atas fondasi desain UI modern yang disebut <strong>Constraint-Based Design</strong> (Desain Berbasis Sistem Skala Terbatas). Tailwind menetapkan satu unit default yang setara dengan <code>0.25rem</code> atau <code>4px</code>. Oleh karena itu, Anda secara implisit "dilarang" untuk menebak angka. Jika Anda butuh bantalan (padding) atau jarak (margin), sistem mewajibkan Anda memilih dari skala proporsional yang telah dikalibrasi ketat — contohnya <code>p-4</code> berarti tepat 16px, <code>p-5</code> berarti 20px, dan <code>p-6</code> berarti 24px. Pembatasan yang tegas namun masuk akal ini secara otomatis menggaransi apa yang desainer sebut sebagai <strong>Visual Rhythm</strong> (Ritme Visual), menjamin <em>margin</em> dan tata letak aplikasi Anda selalu tampak profesional, matematis, dan serasi layaknya diciptakan oleh seorang Desainer UI senior.</p>
                                </div>
                            </div>

                            <div class="space-y-4 pt-6">
                                <h3 class="text-xl font-bold text-heading flex items-center gap-2 transition-colors"><span class="w-6 h-6 rounded bg-blue-500 dark:bg-blue-600 flex items-center justify-center text-[10px] text-white">B</span> Penyelamat "Arbitrary Values"</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-lg leading-relaxed space-y-4 text-justify transition-colors">
                                    <p>Tentu hal ini menimbulkan pertanyaan logis: Bagaimana jika sebuah desain (misalnya dari representasi visual di <span class="font-semibold text-cyan-600 dark:text-cyan-400">Figma</span> klien) benar-benar menuntut lebar sebuah komponen hero image harus sangat presisi di angka <code>317px</code>, atau menuntut penggunaan kode warna heksadesimal <em>brand guideline</em> perusahaan yang amat spesifik seperti <code>#e14a3b</code>? Jangan khawatir, Tailwind dirancang sebagai kerangka kerja pragmatis yang tidak mengekang Anda layaknya jaket pengekang (<em>straitjacket</em>).</p>
                                    <p>Melalui fitur mutakhir yang diperkenalkan pada versi terbarunya yang disebut <strong>Arbitrary Values</strong> (Nilai Bebas), Anda dapat menerobos skala sistem dengan memasukkan nilai kustom tersebut secara eksplisit menggunakan tanda kurung siku <code>[]</code>, contoh penggunaannya adalah <code>w-[317px]</code> atau <code>bg-[#e14a3b]</code>. Fasilitas ini memastikan bahwa ketika Anda terbentur pada situasi tidak wajar (<span class="font-semibold text-cyan-600 dark:text-cyan-400">edge cases</span>), Anda tetap bisa merender gaya yang sifatnya mutlak secara <em>inline</em>, tanpa harus menyerah dan kembali ke cara lama membuat file CSS eksternal baru. Meskipun demikian, <strong>Arbitrary Values</strong> seyogyanya hanya digunakan sebagai pengecualian mendesak, bukan sebagai basis rutinitas penulisan Anda.</p>
                                </div>
                            </div>

                            {{-- SIMULATOR 2: GRID SNAPPER --}}
                            <div class="sim-bg-adaptive border border-adaptive rounded-xl overflow-hidden shadow-xl dark:shadow-2xl p-8 relative mt-8 transition-colors">
                                <h4 class="text-xs font-bold text-muted uppercase mb-4 text-center transition-colors">Simulator Logis: Magic Number vs Skala Tailwind (Grid 16px)</h4>
                                
                                {{-- KOTAK INSTRUKSI SIMULATOR 2 --}}
                                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-500/30 rounded-lg p-4 mb-8 text-sm text-blue-800 dark:text-blue-300 relative z-10">
                                    <p class="font-bold flex items-center gap-2 mb-2">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        💡 Panduan Simulasi 2
                                    </p>
                                    <ol class="list-decimal pl-5 space-y-1">
                                        <li>Ketikkan sebuah nilai tebakan "Magic Number" menggunakan Arbitrary Value pada kolom input, misalnya: <code>w-[45px]</code> atau <code>w-[71px]</code>. Anda akan melihat sistem memberi peringatan merah karena tepi kotak tidak sejajar presisi dengan garis panduan grid latar (kelipatan 4px).</li>
                                        <li>Sekarang hapus, dan gunakan sistem skala proporsional Tailwind yang sesungguhnya. Ketikkan: <code>w-16</code> (yang bernilai 64px) atau <code>w-24</code>. Perhatikan bagaimana elemen terpasang mengunci sempurna dengan garis kotak-kotak grid, menciptakan harmoni desain struktural.</li>
                                    </ol>
                                </div>

                                <div class="grid lg:grid-cols-2 gap-8 relative z-10">
                                    <div class="flex flex-col gap-4 justify-center">
                                        <p class="text-xs text-slate-600 dark:text-gray-400 transition-colors">Ketik nilai lebar (width) pada kotak input. Bandingkan secara visual konsekuensinya jika Anda memaksakan "Magic Number" (Cth: <code class="text-amber-500 font-bold">w-[45px]</code>) melawan skala proporsional Constraint Tailwind (Cth: <code class="text-emerald-500 font-bold">w-16</code>).</p>
                                        
                                        <div class="space-y-2 mt-2">
                                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Input Instruksi Dimensi:</label>
                                            <input type="text" id="sim2-input" placeholder="Cth: w-[45px] atau w-16" 
                                                class="w-full bg-white dark:bg-black/50 border border-slate-300 dark:border-white/20 rounded-lg py-2 px-4 text-slate-800 dark:text-white font-mono focus:border-cyan-500 outline-none transition-colors shadow-inner dark:shadow-none"
                                                onkeyup="sim2UpdateGrid(this.value)">
                                        </div>

                                        <div id="sim2-status" class="p-3 text-[10px] font-bold rounded-lg border border-dashed border-slate-300 dark:border-white/20 text-slate-500 dark:text-white/40 bg-slate-50 dark:bg-transparent transition-colors">
                                            Menunggu instruksi dimensi...
                                        </div>
                                    </div>

                                    <div class="bg-slate-100 dark:bg-[#0a0e17] rounded-xl border border-slate-200 dark:border-white/10 h-48 relative overflow-hidden flex items-center visual-grid">
                                        <div id="sim2-box" class="h-16 bg-indigo-500 text-white text-[10px] font-bold flex items-center justify-center transition-all duration-300 border-r-2 border-r-indigo-300 shadow-lg" style="width: 0px;">
                                            <span class="opacity-0 truncate px-2" id="sim2-box-text">0px</span>
                                        </div>
                                    </div>
                                </div>
                                
                                {{-- KESIMPULAN SIMULASI 2 --}}
                                <div class="mt-8 bg-gradient-to-r from-cyan-50 to-transparent dark:from-cyan-900/20 dark:to-transparent border-l-4 border-cyan-500 p-5 rounded-r-xl relative z-10 transition-colors">
                                    <h5 class="text-sm font-bold text-cyan-700 dark:text-cyan-400 mb-2 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> 
                                        Kesimpulan Analisis Konsistensi
                                    </h5>
                                    <p class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed text-justify">
                                        Tailwind pada dasarnya bukan sekadar koleksi class CSS biasa, ia bertindak layaknya sebuah <strong>Design System</strong> seutuhnya. Dengan membatasi pilihan parameter Anda melalui sekumpulan set skala yang sangat dipertimbangkan (Constraint-Based System), Tailwind menghilangkan celah <em>human-error</em> dalam menentukan jarak, padding, dan tipografi komponen. Pendekatan ini memastikan bahwa terlepas dari berapapun banyaknya <em>developer</em> yang berkontribusi dalam tim Anda, hasil akhir kode antarmuka (UI) akan tetap beresonansi harmonis secara matematis. 
                                    </p>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 22: PERFORMANCE & JIT --}}
                    <section id="section-22" class="lesson-section scroll-mt-32" data-lesson-id="22">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-emerald-500 pl-6">
                                <span class="text-emerald-600 dark:text-emerald-400 font-mono text-xs uppercase tracking-widest transition-colors">Lesson 1.5.3</span>
                                <h2 class="text-4xl lg:text-5xl font-black text-heading leading-[1.1] transition-colors">
                                    Performa: <br> 
                                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-500 to-teal-600 dark:from-emerald-400 dark:to-teal-500">The JIT Compiler</span>
                                </h2>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-heading flex items-center gap-2 transition-colors"><span class="w-6 h-6 rounded bg-emerald-500 dark:bg-emerald-600 flex items-center justify-center text-[10px] text-white">A</span> Penyakit Kritis "Append-Only" CSS</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-lg leading-relaxed space-y-4 text-justify transition-colors">
                                    <p>Buku <em>"Ultimate Tailwind CSS Handbook"</em> membedah salah satu praktik paling bobrok dalam sejarah pengembangan web berbasis <em>stylesheet</em> eksternal tradisional: <span class="font-semibold text-emerald-600 dark:text-emerald-400">Append-Only CSS</span>. Praktik ini secara harfiah menggambarkan kebiasaan di mana saat anggota tim menambahkan fitur baru atau merombak antarmuka, mereka seringkali hanya berani "menyuntikkan" deklarasi kode CSS baru di baris paling bawah (<em>append</em>) dari sebuah <em>stylesheet</em> berukuran global.</p>
                                    <p>Meskipun fitur komponen UI yang lama perlahan-lahan telah dihapus dari aplikasi, seorang programmer di lingkungan <em>legacy code</em> nyaris selalu menolak untuk menghapus baris definisi CSS yang dianggapnya tak terpakai. Mengapa? Karena mereka dilanda rasa takut (<span class="font-semibold text-emerald-600 dark:text-emerald-400">regression fear</span>). Mereka khawatir bahwa jika class tersebut dihapus, hal itu diam-diam akan menghancurkan struktur visual (layout) dari subsistem elemen lain yang tersembunyi di sudut berbeda aplikasi. Konsekuensi jangka panjang dari kepengecutan sistemik kolektif ini adalah lahirnya file CSS yang akan membesar (bloat) secara linier seiring waktu, mencekik <em>bandwidth</em>, dan menyeret waktu tunggu beban muat (<em>load time</em>) menuju titik performa terendahnya.</p>
                                </div>
                            </div>
                            
                            <div class="space-y-4 pt-4">
                                <h3 class="text-xl font-bold text-heading flex items-center gap-2 transition-colors"><span class="w-6 h-6 rounded bg-teal-500 dark:bg-teal-600 flex items-center justify-center text-[10px] text-white">B</span> Membentuk Kurva "Plateau" (JIT)</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-lg leading-relaxed space-y-4 text-justify transition-colors">
                                    <p>Pendekatan Tailwind memutarbalikkan kutukan pertumbuhan linier tersebut. Karena sifat alami dari <span class="font-semibold text-emerald-600 dark:text-emerald-400">utility class</span> adalah fungsi daur ulang murni yang sangat agresif (sebagai contoh, kita cukup memakai satu baris perintah utilitas <code>flex</code> yang sama untuk menyusun seribu hierarki komponen berbeda dalam aplikasi belanja). Pada akhirnya, Anda akan mendapati posisi di mana Anda hampir "kehabisan" kelas baru yang tersisa untuk diketikkan. Tidak peduli berapapun masifnya ukuran <span class="font-semibold text-emerald-600 dark:text-emerald-400">platform</span> Anda berkembang dari 10 menjadi ratusan halaman, kurva ukuran file CSS rakitan akhir Anda pada grafik akan melandai tajam mendatar hingga membentuk sebuah garis konstan horizontal absolut, yang lazim dikenal dengan nama <strong>Fase Plateau</strong>.</p>
                                    <p>Sebagai senjata pamungkas di balik optimasi ukuran rakitan ini, modern Tailwind (semenjak versi 3.0) ditenagai murni oleh sebuah inti mesin pendeteksi canggih yang dijuluki <strong><span class="font-semibold text-emerald-600 dark:text-emerald-400">Just-In-Time</span> (JIT) Compiler</strong>. Di saat Anda memulai proses final pelemparan aplikasi ke fase <em>Production Build</em>, mesin pendeteksi JIT tidak mengkompilasi file raksasa untuk kemudian menghapusnya perlahan. Sebaliknya, kompilator ini men-<em>scan</em> kilat isi dari seluruh file HTML, Blade, atau JavaScript Anda secara spesifik. Ia <strong>secara absolut hanya akan membuat dan mengekstrak utilitas ke dalam CSS Anda jika utilitas itu ditemukan (on-demand extraction)</strong> dalam teks kode Anda. Efisiensi bedah mikroskopis (Tree-Shaking) yang sempurna ini menjamin lahirnya sebuah file CSS rakitan (<span class="font-semibold text-emerald-600 dark:text-emerald-400">Tiny Bundle</span>) di mana bobot statis rata-ratanya tak akan lebih dari <strong>10 Kilobytes</strong> saja, meskipun aplikasi Anda dihuni ribuan fungsionalitas visual tingkat tinggi!</p>
                                </div>
                            </div>

                            {{-- SIMULATOR 3: BLOAT MONITOR --}}
                            <div class="sim-bg-adaptive border border-adaptive rounded-xl overflow-hidden shadow-xl dark:shadow-2xl p-8 relative mt-8 transition-colors">
                                <h4 class="text-xs font-bold text-muted uppercase mb-4 text-center transition-colors">Visualisasi Kinerja: "Linear CSS Bloat" vs "Tailwind Plateau"</h4>
                                
                                {{-- KOTAK INSTRUKSI SIMULATOR 3 --}}
                                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-500/30 rounded-lg p-4 mb-8 text-sm text-blue-800 dark:text-blue-300 relative z-10">
                                    <p class="font-bold flex items-center gap-2 mb-2">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        💡 Panduan Simulasi 3
                                    </p>
                                    <ol class="list-decimal pl-5 space-y-1">
                                        <li>Klik tombol <strong>"SIMULASI: Bangun & Rilis Halaman Web"</strong> secara berulang-ulang untuk mensimulasikan penambahan fitur/halaman baru di proyek Anda dari waktu ke waktu.</li>
                                        <li>Perhatikan bar grafik merah (CSS Konvensional). Ukurannya akan terus melonjak naik secara linier tak terkendali di setiap rilis karena akumulasi <em>Append-Only CSS</em>.</li>
                                        <li>Perhatikan bar grafik hijau (Tailwind JIT). Awalnya naik sedikit karena Anda memakai class dasar, lalu akan segera <strong>membentuk garis datar (Plateau)</strong> dan terhenti stabil di angka bobot yang sangat minimum meskipun Anda terus menyuntik puluhan halaman baru yang kompleks.</li>
                                    </ol>
                                </div>

                                <div class="space-y-8 relative z-10 max-w-3xl mx-auto">
                                    <div>
                                        <div class="flex justify-between text-xs text-muted mb-2 font-mono transition-colors">
                                            <span>Sistem CSS Konvensional (Append-Only = Tumbuh Linear)</span>
                                            <span id="txt-trad" class="text-red-600 dark:text-red-400 font-bold transition-colors">Ukuran Bundle: 15.0 KB</span>
                                        </div>
                                        <div class="w-full bg-slate-200 dark:bg-white/5 rounded-full h-6 overflow-hidden border border-slate-300 dark:border-white/5 transition-colors">
                                            <div id="bar-trad" class="bg-gradient-to-r from-red-500 to-red-400 dark:from-red-600 dark:to-red-400 h-full rounded-full w-[15%] transition-all duration-300 relative"></div>
                                        </div>
                                    </div>

                                    <div>
                                        <div class="flex justify-between text-xs text-muted mb-2 font-mono transition-colors">
                                            <span>Tailwind CSS JIT (Daur Ulang Utility & JIT Purge)</span>
                                            <span id="txt-tw" class="text-emerald-600 dark:text-emerald-400 font-bold transition-colors">Ukuran Bundle: 4.0 KB</span>
                                        </div>
                                        <div class="w-full bg-slate-200 dark:bg-white/5 rounded-full h-6 overflow-hidden border border-slate-300 dark:border-white/5 transition-colors">
                                            <div id="bar-tw" class="bg-gradient-to-r from-emerald-500 to-emerald-400 dark:from-emerald-600 dark:to-emerald-400 h-full rounded-full w-[4%] shadow-none dark:shadow-[0_0_15px_#10b981] transition-all duration-300 relative"></div>
                                        </div>
                                        <p class="text-[10px] text-emerald-600/80 dark:text-emerald-400/60 mt-3 italic transition-colors font-bold">Perhatikan bagaimana kurva ukuran Tailwind akan secara konstan membentuk garis datar (Plateau) karena daur ulang utilitas yang ekstrem.</p>
                                    </div>
                                </div>

                                <div class="mt-12 flex justify-center relative z-10">
                                    <button onclick="sim3AddPage()" id="btn-sim3" class="px-8 py-3.5 bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl text-xs font-bold hover:bg-slate-50 dark:hover:bg-white/10 transition-all text-slate-800 dark:text-white shadow-sm dark:shadow-none flex items-center gap-3 group cursor-pointer active:scale-95 select-none">
                                        <span class="w-5 h-5 rounded-full bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-400 flex items-center justify-center text-[10px] group-hover:bg-emerald-500 group-hover:text-white dark:group-hover:text-black transition-colors font-black">+</span>
                                        SIMULASI: Bangun & Rilis Halaman Web Ke-1
                                    </button>
                                </div>
                                
                                {{-- KESIMPULAN SIMULASI 3 --}}
                                <div class="mt-8 bg-gradient-to-r from-emerald-50 to-transparent dark:from-emerald-900/20 dark:to-transparent border-l-4 border-emerald-500 p-5 rounded-r-xl relative z-10 transition-colors">
                                    <h5 class="text-sm font-bold text-emerald-700 dark:text-emerald-400 mb-2 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> 
                                        Kesimpulan Analisis Performa
                                    </h5>
                                    <p class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed text-justify">
                                        Melalui mesin analisis Just-In-Time (JIT) yang disuntikkan mulai dari versi ketiga, paradigma ukuran <em>bundle size</em> CSS telah dimusnahkan. Anda tidak akan pernah dihadapkan dengan kode kelas mati (Dead-Code) lagi, terlepas dari fakta proyek Anda memiliki sejuta desain halaman sekalipun. Tingkat skalabilitas ekstrim melalui fenomena pembentukan <span class="font-semibold">kurva Plateau (mendatar)</span> adalah keunggulan mutlak yang membuat Tailwind CSS disukai banyak perusahaan rintisan <em>Tech-Giants</em> terkemuka demi mencapai nilai metrik Core Web Vitals tertinggi di sisi klien.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 23: ACTIVITY FINAL (STEP-BY-STEP REFACTOR) --}}
                    <section id="section-23" class="lesson-section scroll-mt-32 pt-10 border-t border-adaptive transition-colors" data-lesson-id="23" data-type="activity">
                        <div class="relative rounded-[2.5rem] sim-bg-adaptive border border-adaptive p-6 md:p-10 overflow-hidden shadow-xl dark:shadow-2xl group hover:border-cyan-500/30 transition-all duration-500">
                            
                            <div class="absolute -top-24 -right-24 w-64 h-64 bg-cyan-600/10 dark:bg-cyan-600/20 blur-[100px] rounded-full pointer-events-none transition-colors"></div>

                            <div class="flex flex-col md:flex-row items-start md:items-center gap-6 mb-8 relative z-10">
                                <div class="p-4 bg-gradient-to-br from-cyan-600 to-blue-800 rounded-2xl text-white shadow-lg shadow-cyan-500/20 shrink-0">
                                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                                </div>
                                <div>
                                    <div class="flex items-center gap-3 mb-1">
                                        <h2 class="text-3xl font-black text-heading tracking-tight transition-colors">Final Mission: Layout Architect</h2>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-cyan-100 dark:bg-cyan-500/20 text-cyan-700 dark:text-cyan-400 border border-cyan-200 dark:border-cyan-500/30 uppercase tracking-wider transition-colors"><span class="text-cyan-600 dark:text-cyan-400 font-semibold">Step-by-Step</span> Challenge</span>
                                    </div>
                                    <p class="text-slate-600 dark:text-cyan-200/60 text-sm leading-relaxed max-w-2xl transition-colors mt-2 text-justify">
                                        Buktikan pemahaman Anda terhadap seluruh materi di atas. Bangun struktur Kartu Profil ini secara bertahap murni di dalam atribut <span class="font-semibold text-cyan-600 dark:text-cyan-400">class</span> editor di bawah. Sistem telah menyediakan petunjuk logis di panel instruksi. Tidak ada <em>spoiler</em> eksplisit tentang nama utilitas; andalkan intuisi <strong>Constraint-Based Design</strong> Anda secara mandiri!
                                    </p>
                                </div>
                            </div>

                            <div class="grid lg:grid-cols-2 gap-0 border border-adaptive rounded-2xl overflow-hidden min-h-[550px] shadow-2xl transition-colors relative z-10">
                                
                                {{-- LEFT PANEL: MONACO EDITOR & TASK LIST --}}
                                <div class="bg-slate-50 dark:bg-[#151515] border-r border-adaptive overflow-hidden relative flex flex-col transition-colors min-h-[550px]">
                                    
                                    {{-- LOCK OVERLAY --}}
                                    <div id="lockOverlay" class="hidden absolute inset-0 bg-white/90 dark:bg-[#050912]/95 backdrop-blur-md z-50 flex flex-col items-center justify-center text-center p-8 transition-colors">
                                        <div class="w-24 h-24 bg-emerald-100 dark:bg-emerald-500/10 rounded-full flex items-center justify-center mb-6 border border-emerald-300 dark:border-emerald-500/50 shadow-[0_0_50px_rgba(16,185,129,0.2)] dark:shadow-[0_0_50px_rgba(16,185,129,0.3)] animate-bounce transition-colors">
                                            <svg class="w-12 h-12 text-emerald-600 dark:text-emerald-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                        </div>
                                        <h3 class="text-3xl font-black text-slate-900 dark:text-white mb-2 tracking-tight transition-colors">TAHAPAN SELESAI!</h3>
                                        <p class="text-sm font-bold text-slate-500 dark:text-white/60 mb-8 max-w-xs transition-colors">Struktur UI berhasil dibangun secara terstruktur tanpa menyentuh stylesheet eksternal.</p>
                                        <button disabled class="px-8 py-3 rounded-xl bg-slate-200 dark:bg-white/5 border border-slate-300 dark:border-white/10 text-slate-500 dark:text-white/30 text-xs font-bold cursor-not-allowed uppercase tracking-widest transition-colors">Tersimpan ke Database</button>
                                    </div>

                                    <div class="bg-slate-200 dark:bg-[#0f141e] px-4 py-2 border-b border-adaptive flex justify-between items-center transition-colors">
                                        <span class="text-xs text-slate-600 dark:text-white/50 font-mono font-bold transition-colors">Profile-Card.html</span>
                                        <button onclick="resetEditor()" class="text-[10px] text-red-500 dark:text-red-400 hover:text-red-600 dark:hover:text-red-300 transition-colors uppercase font-bold">Reset Editor</button>
                                    </div>
                                    
                                    {{-- Editor Container --}}
                                    <div id="codeEditor" class="h-[250px] w-full border-b border-adaptive transition-colors"></div>

                                    {{-- DYNAMIC TASK LIST DENGAN CONCEPTUAL HINTS --}}
                                    <div class="p-6 bg-slate-50 dark:bg-[#0f141e] transition-colors flex flex-col flex-1">
                                        <div class="flex justify-between items-center mb-4">
                                            <span class="text-[10px] uppercase font-bold text-muted transition-colors">Tahapan Instruksi Arsitektur</span>
                                            <span id="progressText" class="text-[10px] font-mono text-cyan-600 dark:text-cyan-400 font-bold transition-colors">Step 1 dari 4</span>
                                        </div>
                                        
                                        <div class="space-y-3 text-[11px] text-slate-600 dark:text-white/60 mb-6 transition-colors overflow-y-auto custom-scrollbar pr-2 flex-1">
                                            
                                            {{-- TASK 1 --}}
                                            <div id="task-1" class="flex items-start gap-3 transition-all duration-300 opacity-100">
                                                <div id="icon-task-1" class="w-5 h-5 rounded-full border-2 border-cyan-500 flex items-center justify-center text-[8px] font-bold text-cyan-600 dark:text-cyan-400 shrink-0 transition-colors">1</div> 
                                                <div>
                                                    <strong class="block text-slate-800 dark:text-white mb-0.5 transition-colors">Latar Belakang & Dimensi Kedalaman</strong>
                                                    <span id="desc-task-1" class="opacity-80 block transition-all duration-300">Hindari <span class="font-semibold text-cyan-600 dark:text-cyan-400">Context Switch</span>. Langsung pada div terluar, berikan atribut <code>class</code> untuk elemen <code>div</code> utama. Masukkan warna <strong>latar belakang putih murni (bg-white)</strong> dan efek elevasi <strong>bayangan berukuran ekstra besar (shadow-xl)</strong> secara instan.</span>
                                                </div>
                                            </div>

                                            {{-- TASK 2 --}}
                                            <div id="task-2" class="flex items-start gap-3 transition-all duration-300 opacity-30 grayscale pointer-events-none">
                                                <div id="icon-task-2" class="w-5 h-5 rounded-full border-2 border-slate-300 dark:border-white/20 flex items-center justify-center text-[8px] font-bold text-slate-500 shrink-0 transition-colors">2</div> 
                                                <div>
                                                    <strong class="block text-slate-800 dark:text-white mb-0.5 transition-colors">Skala Constraint Geometri</strong>
                                                    <span id="desc-task-2" class="opacity-0 h-0 overflow-hidden block transition-all duration-300">Patuhi skala Tailwind! Tambahkan pada div terluar tadi, lembutkan sudut terluarnya dengan <strong>kelengkungan (radius) tingkat 2xl</strong>. Untuk ruang bernapas, beri <strong>bantalan dalam memutar (padding) sebesar 24px (p-6)</strong>.</span>
                                                </div>
                                            </div>

                                            {{-- TASK 3 --}}
                                            <div id="task-3" class="flex items-start gap-3 transition-all duration-300 opacity-30 grayscale pointer-events-none">
                                                <div id="icon-task-3" class="w-5 h-5 rounded-full border-2 border-slate-300 dark:border-white/20 flex items-center justify-center text-[8px] font-bold text-slate-500 shrink-0 transition-colors">3</div> 
                                                <div>
                                                    <strong class="block text-slate-800 dark:text-white mb-0.5 transition-colors">Deklarasi Orientasi Lentur</strong>
                                                    <span id="desc-task-3" class="opacity-0 h-0 overflow-hidden block transition-all duration-300">Struktur saat ini masih menumpuk kaku secara vertikal. Panggil perilaku <strong>kotak fleksibel (flex)</strong> pada div terluar agar gambar avatar dan blok teks di dalamnya otomatis terdorong berjejer sejajar ke samping.</span>
                                                </div>
                                            </div>

                                            {{-- TASK 4 --}}
                                            <div id="task-4" class="flex items-start gap-3 transition-all duration-300 opacity-30 grayscale pointer-events-none">
                                                <div id="icon-task-4" class="w-5 h-5 rounded-full border-2 border-slate-300 dark:border-white/20 flex items-center justify-center text-[8px] font-bold text-slate-500 shrink-0 transition-colors">4</div> 
                                                <div>
                                                    <strong class="block text-slate-800 dark:text-white mb-0.5 transition-colors">Penyelarasan Sentral Vertikal</strong>
                                                    <span id="desc-task-4" class="opacity-0 h-0 overflow-hidden block transition-all duration-300">Selesaikan desain dengan memaksa elemen dalam flex tersebut selaras tepat <strong>di posisi tengah pada sumbu vertikal (items-center)</strong>, lalu dorong elemen tersebut agar memiliki <strong>ruang jeda (gap) kosong sebesar 16px (gap-4)</strong>.</span>
                                                </div>
                                            </div>
                                        </div>

                                        <button id="submitExerciseBtn" onclick="submitExercise()" disabled class="w-full py-3 rounded-lg bg-emerald-600 text-white font-bold text-xs shadow-lg shadow-emerald-500/20 hover:bg-emerald-500 transition-all cursor-not-allowed opacity-50 flex items-center justify-center gap-2 mt-auto shrink-0">
                                            <span id="submitBtnText">Menunggu Validasi Baris Kode...</span>
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </button>
                                    </div>
                                </div>

                                {{-- RIGHT PANEL: VISUAL RENDER ZONE --}}
                                <div class="bg-white dark:bg-[#1e1e1e] border-l border-adaptive flex-1 flex flex-col relative overflow-hidden shadow-sm dark:shadow-none transition-colors">
                                    <div class="bg-slate-100 dark:bg-[#2d2d2d] px-4 py-2 border-b border-adaptive flex items-center justify-between transition-colors">
                                        <span class="text-[10px] text-slate-500 dark:text-gray-400 font-mono font-bold transition-colors">Browser Preview</span>
                                        <span class="text-[10px] bg-emerald-100 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 px-2 py-0.5 rounded border border-emerald-200 dark:border-emerald-500/20 font-bold transition-colors">Real-Time Engine</span>
                                    </div>
                                    <div class="flex-1 bg-slate-100 dark:bg-[#020617] p-8 flex items-center justify-center relative overflow-hidden transition-colors">
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
                    <a href="{{ route('courses.implementation') ?? '#' }}" class="group flex items-center gap-4 text-muted hover:text-heading transition-colors">
                        <div class="w-12 h-12 rounded-full border border-adaptive flex items-center justify-center group-hover:bg-slate-100 dark:group-hover:bg-white/5 transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        </div>
                        <div class="text-left hidden sm:block">
                            <div class="text-[10px] uppercase tracking-widest font-bold opacity-60">Sebelumnya</div>
                            <div class="font-black text-sm">Implementasi Utility</div>
                        </div>
                    </a>
                    
                    <div id="nextChapterBtn" class="group flex items-center gap-4 text-right text-muted cursor-not-allowed opacity-50 pointer-events-none transition-all duration-500">
                        <div class="text-right hidden sm:block">
                            <div id="nextLabel" class="text-[10px] uppercase tracking-widest font-bold opacity-60">Terkunci</div>
                            <div class="font-black text-sm">Instalasi & Konfigurasi</div>
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

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/monaco-editor@0.45.0/min/vs/loader.js"></script>

<script>
    /* --- 1. CONFIGURATION (AJAX & DATABASE) --- */
    window.LESSON_IDS = [20, 21, 22, 23]; 
    let rawCompletedIds = {!! json_encode($completedLessonIds ?? []) !!};
    window.COMPLETED_IDS = rawCompletedIds.map(id => Number(id)); 
    let completedSet = new Set(window.COMPLETED_IDS);
    
    const ACTIVITY_LESSON_ID = 23; 
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

    // ==========================================
    // LOGIKA SIMULATOR (SIM 1, SIM 2, SIM 3 LOGIS)
    // ==========================================
    
    // SIM 1 (Context Switch Timer)
    let sim1TradTimer = null;
    function runSim1Trad() {
        if(sim1TradTimer) return;
        const btn = document.getElementById('btn-sim1-trad');
        const log = document.getElementById('sim1-log-trad');
        const timer = document.getElementById('sim1-timer-trad');
        const meter = document.getElementById('sim1-meter');
        const text = document.getElementById('sim1-text');
        
        btn.disabled = true; btn.classList.add('opacity-50');
        log.innerHTML = "";
        
        const inputVal = document.querySelector('input[placeholder="Cth: .profile-card__avatar-image"]').value;
        if(inputVal.length > 0) {
            meter.style.width = '100%';
            text.innerText = "Context Switch & Naming Fatigue Terjadi: 100%";
            meter.className = "absolute top-0 left-0 h-full bg-red-500 w-0 transition-all duration-200 animate-pulse";
        }

        const steps = [
            { time: 0, text: "> Membuka file HTML..." },
            { time: 1000, text: "> Memikirkan abstraksi nama class BEM..." },
            { time: 2500, text: "> Menulis atribut penamaan di komponen..." },
            { time: 3500, text: "> Berpindah drastis ke tab styles.css..." },
            { time: 5000, text: "> Menulis aturan fisik elemen..." },
            { time: 6500, text: "> Menyimpan (Save) file CSS eksternal..." },
            { time: 7500, text: "> Memuat ulang (Reload) browser engine..." },
            { time: 8000, text: "> SIKLUS SWITCHING BERHASIL DILALUI." }
        ];

        let start = Date.now();
        sim1TradTimer = setInterval(() => {
            timer.innerText = ((Date.now() - start) / 1000).toFixed(1) + "s";
        }, 100);

        steps.forEach(step => {
            setTimeout(() => {
                log.innerHTML += `<br><span class="${step.time === 8000 ? 'text-amber-500 font-bold' : ''}">${step.text}</span>`;
                log.scrollTop = log.scrollHeight;
                if(step.time === 8000) {
                    clearInterval(sim1TradTimer);
                    sim1TradTimer = null;
                    btn.disabled = false; btn.classList.remove('opacity-50');
                }
            }, step.time);
        });
    }

    let sim1TwTimer = null;
    function runSim1Tw() {
        if(sim1TwTimer) return;
        const btn = document.getElementById('btn-sim1-tw');
        const log = document.getElementById('sim1-log-tw');
        const timer = document.getElementById('sim1-timer-tw');
        btn.disabled = true; btn.classList.add('opacity-50');
        log.innerHTML = "";
        
        const steps = [
            { time: 0, text: "> Menulis utilitas harfiah secara in-line..." },
            { time: 500, text: "> Mesin JIT Tailwind menyinkronkan memori..." },
            { time: 800, text: "> RENDER VISUAL SECARA INSTAN." }
        ];

        let start = Date.now();
        sim1TwTimer = setInterval(() => {
            timer.innerText = ((Date.now() - start) / 1000).toFixed(1) + "s";
        }, 100);

        steps.forEach(step => {
            setTimeout(() => {
                log.innerHTML += `<br><span class="${step.time === 800 ? 'text-emerald-500 font-bold' : ''}">${step.text}</span>`;
                log.scrollTop = log.scrollHeight;
                if(step.time === 800) {
                    clearInterval(sim1TwTimer);
                    sim1TwTimer = null;
                    btn.disabled = false; btn.classList.remove('opacity-50');
                }
            }, step.time);
        });
    }

    function sim1Tw(val) {
        const status = document.getElementById('sim1-tw-status');
        const box = document.getElementById('sim1-tw-box');
        
        let classesToApply = "w-16 h-16 flex items-center justify-center text-[10px] transition-all duration-300 mx-auto mt-6 ";
        let isChanged = false;

        if (val.includes('bg-blue-500')) { classesToApply += "bg-blue-500 text-white "; isChanged = true; }
        else { classesToApply += "bg-slate-100 dark:bg-transparent text-slate-400 "; }

        if (val.includes('rounded-full')) { classesToApply += "rounded-full "; isChanged = true;}
        else { classesToApply += "rounded "; }

        if (val.includes('shadow-md')) { classesToApply += "shadow-md "; isChanged = true;}
        else { classesToApply += "border border-dashed border-slate-400 dark:border-white/30 "; }

        box.className = classesToApply;

        if (isChanged && val.length > 10) {
            status.innerHTML = `<span class="text-emerald-600 dark:text-emerald-400 font-bold tracking-wide">✓ Gaya terpasang secepat kilat! Context Switch berhasil dihindari.</span>`;
        } else {
            status.innerHTML = `Menuliskan properti fisik secara murni tanpa penamaan...`;
        }
    }

    function sim1Trad(val) {
        const status = document.getElementById('sim1-trad-status');
        if (val.length > 5) {
            status.innerHTML = `Class <b class="text-slate-800 dark:text-white font-mono bg-slate-200 dark:bg-white/10 px-1 rounded">${val}</b> tertanam di HTML.<br><span class="text-amber-600 dark:text-amber-500 font-bold mt-1 block">Silakan menempuh siklus perpindahan ke dokumen lain.</span>`;
        } else {
            status.innerHTML = `Menunggu rekayasa penamaan semantik...`;
        }
    }

    // SIM 2 (Grid Alignment Check)
    function sim2UpdateGrid(val) {
        const status = document.getElementById('sim2-status');
        const box = document.getElementById('sim2-box');
        const boxText = document.getElementById('sim2-box-text');

        if(val.length === 0) {
            status.innerText = "Menunggu instruksi metrik dimensi (px / rem)...";
            status.className = "p-3 text-[10px] font-bold rounded-lg border border-dashed border-slate-300 dark:border-white/20 text-slate-500 dark:text-white/40 bg-slate-50 dark:bg-transparent transition-colors";
            box.style.width = '0px';
            boxText.classList.remove('opacity-100');
            return;
        }

        if(/w-\[(\d+)px\]/.test(val)) {
            const width = val.match(/w-\[(\d+)px\]/)[1];
            box.style.width = width + 'px';
            boxText.innerText = width + 'px';
            boxText.classList.add('opacity-100');

            if (width % 16 !== 0 && width % 4 !== 0) {
                status.innerHTML = `⚠️ <b>PELANGGARAN GRID!</b> "Magic Number". Ukuran ${width}px bertabrakan dengan rasio constraint grid 16px.`;
                status.className = "p-3 text-[10px] font-bold rounded-lg border border-amber-400 dark:border-amber-500/50 text-amber-600 dark:text-amber-500 bg-amber-100 dark:bg-amber-900/20 transition-colors animate-pulse";
                box.className = "h-16 bg-amber-500 text-white text-[10px] font-bold flex items-center justify-center transition-all duration-300 border-r-2 border-r-amber-200 shadow-lg";
            } else {
                status.innerHTML = `✅ <b>SKALA HARMONIS!</b> Secara kebetulan Arbitrary Value mematuhi grid spasial desain.`;
                status.className = "p-3 text-[10px] font-bold rounded-lg border border-emerald-400 dark:border-emerald-500/50 text-emerald-600 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-900/20 transition-colors";
                box.className = "h-16 bg-emerald-500 text-white text-[10px] font-bold flex items-center justify-center transition-all duration-300 border-r-2 border-r-emerald-200 shadow-lg";
            }
        } 
        else if (/w-(\d+)$/.test(val) || /w-\d+\.\d+$/.test(val)) {
            const scale = val.replace('w-','');
            const width = parseFloat(scale) * 4; 
            box.style.width = width + 'px';
            boxText.innerText = width + 'px';
            boxText.classList.add('opacity-100');
            
            status.innerHTML = `✅ <b>KONSISTENSI MUTLAK:</b> Skala <code>w-${scale}</code> dihukum presisi menjadi <b>${width}px</b> oleh mesin Tailwind.`;
            status.className = "p-3 text-[10px] font-bold rounded-lg border border-emerald-400 dark:border-emerald-500/50 text-emerald-600 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-900/20 transition-colors";
            box.className = "h-16 bg-emerald-500 text-white text-[10px] font-bold flex items-center justify-center transition-all duration-300 border-r-2 border-r-emerald-200 shadow-lg";
        }
        else {
            status.innerHTML = `Sintaks tidak terdeteksi. Gunakan instruksi Arbitrary (<code>w-[..px]</code>) atau Sistem Skala (<code>w-..</code>).`;
            status.className = "p-3 text-[10px] font-bold rounded-lg border border-dashed border-slate-300 dark:border-white/20 text-slate-500 dark:text-white/40 bg-slate-50 dark:bg-transparent transition-colors";
            box.style.width = '0px';
            boxText.classList.remove('opacity-100');
        }
    }

    // SIM 3 (JIT Bloat Curve Line)
    let pageCount = 1;
    function sim3AddPage() {
        pageCount++;
        const btn = document.getElementById('btn-sim3');
        const barTrad = document.getElementById('bar-trad');
        const barTw = document.getElementById('bar-tw');
        const txtTrad = document.getElementById('txt-trad');
        const txtTw = document.getElementById('txt-tw');

        let tradW = pageCount * 15;
        if(tradW > 100) tradW = 100;
        barTrad.style.width = tradW + '%';
        txtTrad.innerText = 'Ukuran File: ' + (pageCount * 15) + ' KB';

        let twW = 4; let twSize = 4.0;
        if(pageCount === 2) { twW = 6; twSize = 4.8; }
        else if (pageCount === 3) { twW = 8; twSize = 5.6; }
        else { twW = 10; twSize = 6.2; } 
        
        barTw.style.width = twW + '%';
        txtTw.innerText = 'Ukuran Bundle: ' + twSize.toFixed(1) + ' KB';

        btn.innerHTML = `<span class="w-5 h-5 rounded-full bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-400 flex items-center justify-center text-[10px] group-hover:bg-emerald-500 group-hover:text-white dark:group-hover:text-black transition-colors font-black">+</span> SIMULASI: Bangun Halaman Web Ke-${pageCount+1}`;

        if(pageCount >= 6) {
            pageCount = 0;
            setTimeout(() => {
                barTrad.style.width = '15%'; txtTrad.innerText = 'Ukuran: 15 KB';
                barTw.style.width = '4%'; txtTw.innerText = 'Ukuran: 4.0 KB';
                btn.innerHTML = `<span class="w-5 h-5 rounded-full bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-400 flex items-center justify-center text-[10px] group-hover:bg-emerald-500 group-hover:text-white dark:group-hover:text-black transition-colors font-black">↻</span> RESET ENGINE KOMPILATOR`;
            }, 600);
        }
    }

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
    // MASTER SCROLL OBSERVER (Auto-Save)
    // ==========================================
    function initMasterObserver() {
        const mainScroll = document.getElementById('mainScroll'); 
        const sections = document.querySelectorAll('.lesson-section');

        if (mainScroll && sections.length > 0) {
            const observerOptions = { root: mainScroll, rootMargin: "-10% 0px -60% 0px", threshold: 0 };
            
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

    /* --- FINAL LOGIC (ACTIVITY MONACO REFACTOR STEP-BY-STEP) --- */
    let editor;
    let currentTaskStep = 1;
    const starterCode = `\n<div class="transition-all hover:-translate-y-1 ">\n  <img src="https://i.pravatar.cc/150?u=a042581f4e29026024d" class="w-16 h-16 rounded-full border border-gray-200">\n  <div>\n    <h3 class="font-bold text-lg text-slate-800">Sarah Connor</h3>\n    <p class="text-sm text-slate-500">Lead Engineer</p>\n  </div>\n</div>`;

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
                validateStepLogic(code);
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
        const bgColor = isDark ? '#020617' : '#f1f5f9'; 
        
        const content = `<!doctype html><html><head><script src="https://cdn.tailwindcss.com"><\/script><style>body { background-color: ${bgColor}; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; font-family: sans-serif; transition: background-color 0.3s; }</style></head><body>${code}</body></html>`;
        frame.srcdoc = content;
    }

    function markTaskDone(taskNum) {
        const el = document.getElementById('task-' + taskNum);
        const icon = document.getElementById('icon-task-' + taskNum);
        const desc = document.getElementById('desc-task-' + taskNum);
        
        if(!el) return;
        el.classList.remove('opacity-30', 'grayscale', 'pointer-events-none');
        icon.classList.remove('border-cyan-500', 'text-cyan-600', 'dark:text-cyan-400');
        icon.classList.add('bg-emerald-500', 'border-transparent', 'text-white');
        icon.innerHTML = '✓';
        desc.classList.remove('h-0', 'opacity-0');
        desc.classList.add('h-auto', 'opacity-80', 'mt-1');
    }

    function markTaskActive(taskNum) {
        const el = document.getElementById('task-' + taskNum);
        const icon = document.getElementById('icon-task-' + taskNum);
        const desc = document.getElementById('desc-task-' + taskNum);
        
        if(!el) return;
        el.classList.remove('opacity-30', 'grayscale', 'pointer-events-none');
        icon.classList.remove('bg-emerald-500', 'border-transparent', 'text-white', 'border-slate-300', 'dark:border-white/20', 'text-slate-500');
        icon.classList.add('border-cyan-500', 'text-cyan-600', 'dark:text-cyan-400', 'border-2');
        icon.innerHTML = taskNum;
        desc.classList.remove('h-0', 'opacity-0');
        desc.classList.add('h-auto', 'opacity-80', 'mt-1');
    }

    function markTaskLocked(taskNum) {
        const el = document.getElementById('task-' + taskNum);
        const icon = document.getElementById('icon-task-' + taskNum);
        const desc = document.getElementById('desc-task-' + taskNum);
        
        if(!el) return;
        el.classList.add('opacity-30', 'grayscale', 'pointer-events-none');
        icon.classList.remove('bg-emerald-500', 'border-transparent', 'text-white', 'border-cyan-500', 'text-cyan-600', 'dark:text-cyan-400');
        icon.classList.add('border-slate-300', 'dark:border-white/20', 'text-slate-500', 'border-2');
        icon.innerHTML = taskNum;
        desc.classList.add('h-0', 'opacity-0');
        desc.classList.remove('h-auto', 'opacity-80', 'mt-1');
    }

    function validateStepLogic(code) {
        const classMatch = code.match(/<div[^>]*class=["']([^"']*)["']/i);
        const classString = classMatch ? classMatch[1] : '';

        // Logic check: bg-white + shadow-xl
        let isBgShadow = /(?=.*\bbg-white\b)(?=.*\bshadow-xl\b)/.test(classString);
        
        // Logic check: rounded-2xl + p-6
        let isShape = /(?=.*\brounded-2xl\b)(?=.*\bp-6\b)/.test(classString);
        
        // Logic check: flex
        let isFlex = /(?=.*\bflex\b)/.test(classString);
        
        // Logic check: items-center + gap-4
        let isAlign = /(?=.*\bitems-center\b)(?=.*\bgap-4\b)/.test(classString);

        const btn = document.getElementById('submitExerciseBtn');
        const btnText = document.getElementById('submitBtnText');
        const progText = document.getElementById('progressText');

        // Logic Step 1
        if(currentTaskStep === 1) {
            if(isBgShadow) {
                markTaskDone(1);
                currentTaskStep = 2;
                markTaskActive(2);
                progText.innerText = "Langkah 2: Geometri Skala";
            }
        }
        // Logic Step 2
        else if (currentTaskStep === 2) {
            if(!isBgShadow) { currentTaskStep = 1; markTaskActive(1); markTaskLocked(2); markTaskLocked(3); markTaskLocked(4); progText.innerText = "Langkah 1: Dasar Kanvas"; return; }
            if(isShape) {
                markTaskDone(2);
                currentTaskStep = 3;
                markTaskActive(3);
                progText.innerText = "Langkah 3: Tata Letak";
            }
        }
        // Logic Step 3
        else if (currentTaskStep === 3) {
            if(!isShape) { currentTaskStep = 2; markTaskActive(2); markTaskLocked(3); markTaskLocked(4); progText.innerText = "Langkah 2: Geometri Skala"; return; }
            if(isFlex) {
                markTaskDone(3);
                currentTaskStep = 4;
                markTaskActive(4);
                progText.innerText = "Langkah 4: Penyelarasan Puncak";
            }
        }
        // Logic Step 4
        else if (currentTaskStep === 4) {
            if(!isFlex) { currentTaskStep = 3; markTaskActive(3); markTaskLocked(4); btn.disabled = true; btn.classList.add('opacity-50', 'cursor-not-allowed'); btnText.innerText = "Menunggu Penyelesaian Arsitektur..."; progText.innerText = "Langkah 3: Tata Letak"; return; }
            
            if(isAlign) {
                markTaskDone(4);
                progText.innerText = "Arsitektur Dinyatakan Sempurna!";
                progText.classList.remove('text-cyan-600', 'dark:text-cyan-400');
                progText.classList.add('text-emerald-600', 'dark:text-emerald-400');
                
                btn.disabled = false;
                btn.classList.remove('cursor-not-allowed', 'opacity-50');
                btnText.innerText = "Kirim Evaluasi Kode (Validasi)";
            } else {
                markTaskActive(4);
                btn.disabled = true;
                btn.classList.add('opacity-50', 'cursor-not-allowed');
                btnText.innerText = "Menunggu Sentuhan Terakhir...";
                progText.innerText = "Langkah 4: Penyelarasan Puncak";
                progText.classList.add('text-cyan-600', 'dark:text-cyan-400');
                progText.classList.remove('text-emerald-600', 'dark:text-emerald-400');
            }
        }
    }

    function resetEditor() { 
        if(editor && !activityCompleted) { 
            editor.setValue(starterCode); 
            currentTaskStep = 1;
            markTaskActive(1);
            markTaskLocked(2); markTaskLocked(3); markTaskLocked(4);
            document.getElementById('progressText').innerText = "Langkah 1: Dasar Kanvas";
        } 
    }

    async function submitExercise() {
        const btn = document.getElementById('submitExerciseBtn');
        const btnText = document.getElementById('submitBtnText');
        btnText.innerText = 'Mentransfer Jejak Logika ke Database...'; 
        btn.disabled = true;
        
        try {
            await saveLessonToDB(ACTIVITY_LESSON_ID); 
            
            activityCompleted = true;
            lockActivityUI();   
            unlockNextChapter(); 
        } catch(e) { 
            console.error(e); 
            btnText.innerText = "Penyimpanan Gagal. Coba kirim ulang.";
            btn.disabled = false;
        }
    }

    function lockActivityUI() {
        document.getElementById('lockOverlay').classList.remove('hidden');
        if(editor) editor.updateOptions({ readOnly: true });
        
        const btn = document.getElementById('submitExerciseBtn'); 
        document.getElementById('submitBtnText').innerText = "Arsitektur Telah Selesai"; 
        btn.disabled = true;
        btn.classList.remove('bg-emerald-600', 'hover:bg-emerald-500');
        btn.classList.add('bg-slate-400', 'dark:bg-slate-700', 'text-slate-200', 'cursor-not-allowed', 'shadow-none');
        
        if(editor && activityCompleted) {
            editor.setValue(`\n<div class="transition-all hover:-translate-y-1 bg-white shadow-xl rounded-2xl p-6 flex items-center gap-4">\n  <img src="https://i.pravatar.cc/150?u=a042581f4e29026024d" class="w-16 h-16 rounded-full border border-gray-200">\n  <div>\n    <h3 class="font-bold text-lg text-slate-800">Sarah Connor</h3>\n    <p class="text-sm text-slate-500">Lead Engineer</p>\n  </div>\n</div>`);
            
            markTaskDone(1); markTaskDone(2); markTaskDone(3); markTaskDone(4);
            document.getElementById('progressText').innerText = "Seluruh Tantangan Tuntas!";
        }
    }

    function unlockNextChapter() {
        const btn = document.getElementById('nextChapterBtn');
        if(btn) {
            btn.classList.remove('cursor-not-allowed', 'opacity-50', 'pointer-events-none', 'text-muted');
            btn.classList.add('text-cyan-600', 'dark:text-cyan-400', 'cursor-pointer');
            
            document.getElementById('nextLabel').innerText = "Lanjutkan Perjalanan";
            document.getElementById('nextLabel').classList.remove('opacity-60');
            document.getElementById('nextLabel').classList.add('text-cyan-600', 'dark:text-cyan-400', 'opacity-100');
            
            const icon = document.getElementById('nextIcon');
            icon.innerHTML = `<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>`;
            icon.classList.remove('bg-slate-100', 'dark:bg-white/5');
            icon.classList.add('bg-cyan-100', 'dark:bg-cyan-500/20', 'border-cyan-300', 'dark:border-cyan-500/50', 'text-cyan-600', 'dark:text-cyan-400', 'shadow-lg');
            
            btn.onclick = () => window.location.href = "{{ route('courses.installation') ?? '#' }}"; 
        }
    }

    /* --- SCROLL SPY & SIDEBAR LOGIC --- */
    function highlightAnchor(id) {
        const isDark = document.documentElement.classList.contains('dark');
        const anchors = document.querySelectorAll('.sidebar-anchor');

        anchors.forEach(a => {
            a.classList.remove('bg-slate-100', 'dark:bg-white/5', 'border-cyan-500', 'border-amber-500');
            a.classList.add('border-transparent');
            
            const dot = a.querySelector('.anchor-dot');
            const isActivity = a.dataset.type === 'activity';

            dot.classList.remove('scale-125', 'shadow-sm', 'dark:shadow-[0_0_10px_#06b6d4]', 'dark:shadow-[0_0_10px_#f59e0b]', 'bg-cyan-500', 'dark:bg-cyan-400');
            
            if (isActivity) {
                dot.classList.remove('bg-amber-500', 'dark:bg-amber-400');
                dot.classList.add('bg-slate-400', 'dark:bg-slate-600'); 
            } else {
                dot.classList.remove('bg-cyan-500', 'dark:bg-cyan-400');
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
            activeAnchor.classList.add(isActivity ? 'border-amber-500' : 'border-cyan-500');
            activeAnchor.classList.remove('border-transparent');
            
            const dot = activeAnchor.querySelector('.anchor-dot');
            dot.classList.remove('bg-slate-400', 'dark:bg-slate-600');
            
            if (isActivity) {
                dot.classList.add(isDark ? 'dark:bg-amber-400' : 'bg-amber-500', 'scale-125', isDark ? 'dark:shadow-[0_0_10px_#f59e0b]' : 'shadow-sm');
            } else {
                dot.classList.add(isDark ? 'dark:bg-cyan-400' : 'bg-cyan-500', 'scale-125', isDark ? 'dark:shadow-[0_0_10px_#06b6d4]' : 'shadow-sm');
            }
            
            const text = activeAnchor.querySelector('.anchor-text');
            text.classList.remove('text-slate-500');
            text.classList.add(isDark ? 'dark:text-white' : 'text-slate-800', 'font-bold');
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
                if (k.getAttribute('data-target') === c) k.classList.add('active')
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