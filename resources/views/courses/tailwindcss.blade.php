@extends('layouts.landing')
@section('title','Konsep Dasar Tailwind CSS')

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

    /* HIGHLIGHT TERM UTILITY */
    .hl-term {
        background-color: rgba(6, 182, 212, 0.15); /* Cyan tint */
        color: #0891b2; /* Cyan 600 */
        padding: 0.125rem 0.375rem;
        border-radius: 0.375rem;
        font-weight: 600;
        font-style: normal;
        white-space: nowrap;
    }
    .dark .hl-term {
        background-color: rgba(34, 211, 238, 0.2);
        color: #67e8f9; /* Cyan 300 */
    }

    /* SCROLLBAR */
    .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(150,150,150,0.3); border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: var(--accent); }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }
    
    /* ANIMATIONS & EFFECTS */
    #animated-bg { 
        background: radial-gradient(800px circle at 20% 20%, rgba(34,211,238,.10), transparent 40%), 
                    radial-gradient(800px circle at 80% 80%, rgba(14,165,233,.10), transparent 40%); 
        animation: bgMove 20s ease-in-out infinite alternate; 
    }
    .dark #animated-bg {
        background: radial-gradient(800px circle at 20% 20%, rgba(34,211,238,.15), transparent 40%), 
                    radial-gradient(800px circle at 80% 80%, rgba(14,165,233,.15), transparent 40%); 
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
        <div class="absolute top-[-20%] left-[-10%] w-[1000px] h-[1000px] bg-cyan-500/5 dark:bg-cyan-900/10 rounded-full blur-[120px] animate-pulse transition-colors"></div>
        <div class="absolute bottom-[-20%] right-[-10%] w-[800px] h-[800px] bg-sky-500/5 dark:bg-sky-900/10 rounded-full blur-[100px] transition-colors"></div>
        <canvas id="stars" class="absolute inset-0 pointer-events-none opacity-0 dark:opacity-100 transition-opacity duration-500"></canvas>
        <div id="noise-overlay" class="absolute inset-0 z-10 opacity-[0.02] dark:opacity-[0.03]"></div>
        <div id="cursor-glow"></div>
    </div>

    @include('layouts.partials.navbar')

    <div class="flex flex-1 overflow-hidden relative z-20">

        @include('layouts.partials.course-sidebar')

        <main id="mainScroll" class="flex-1 h-full overflow-y-auto scroll-smooth relative bg-transparent custom-scrollbar scroll-padding-top-24">
            
            {{-- STICKY HEADER & PROGRESS BAR --}}
            <div id="stickyHeader" class="sticky top-0 z-30 w-full backdrop-blur-2xl border-b border-adaptive px-4 md:px-8 py-4 flex items-center justify-between transition-colors duration-500" style="background-color: var(--glass-header);">
                <div class="flex items-center gap-4">
                    <div class="w-8 h-8 rounded-lg bg-cyan-500/10 dark:bg-cyan-500/20 border border-cyan-500/20 flex items-center justify-center font-bold text-xs text-cyan-600 dark:text-cyan-400 transition-colors shrink-0">1.2</div>
                    <div>
                        <h1 class="text-sm font-bold text-heading transition-colors line-clamp-1">Konsep Dasar Tailwind</h1>
                        <p class="text-[10px] text-muted transition-colors line-clamp-1">Core Utilities & Workflow</p>
                    </div>
                </div>
                
                {{-- DYNAMIC PROGRESS BAR UI --}}
                <div class="flex items-center gap-3 shrink-0">
                    <div class="hidden sm:block w-24 md:w-32 h-1.5 bg-slate-200 dark:bg-white/10 rounded-full overflow-hidden transition-colors">
                        <div id="topProgressBar" class="h-full bg-gradient-to-r from-cyan-400 to-blue-500 w-0 transition-all duration-500 shadow-[0_0_10px_rgba(6,182,212,0.5)]"></div>
                    </div>
                    <span id="progressLabelTop" class="text-cyan-600 dark:text-cyan-400 font-bold text-xs transition-colors">0%</span>
                </div>
            </div>

            <div class="p-4 sm:p-6 lg:p-16 max-w-5xl mx-auto pb-40">
                
                {{-- TUJUAN PEMBELAJARAN --}}
                <div class="mb-16 md:mb-24">
                    <h3 class="text-xl font-bold text-heading mb-6 flex items-center gap-2 transition-colors">
                        <svg class="w-5 h-5 text-cyan-500 dark:text-cyan-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Tujuan Pembelajaran
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                        <div class="card-adaptive p-5 rounded-xl flex items-start gap-4 hover:border-cyan-500/30 transition group h-full">
                            <div class="w-8 h-8 rounded bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 flex items-center justify-center shrink-0 font-bold text-xs transition-colors">1</div>
                            <div><h4 class="text-sm font-bold text-heading mb-1 transition-colors">Filosofi Utility</h4><p class="text-[11px] text-muted leading-relaxed transition-colors">Mendekonstruksi pendekatan desain konvensional dan beralih ke arsitektur utility-first.</p></div>
                        </div>
                        <div class="card-adaptive p-5 rounded-xl flex items-start gap-4 hover:border-sky-500/30 transition group h-full">
                            <div class="w-8 h-8 rounded bg-sky-500/10 text-sky-600 dark:text-sky-400 flex items-center justify-center shrink-0 font-bold text-xs transition-colors">2</div>
                            <div><h4 class="text-sm font-bold text-heading mb-1 transition-colors">Design Tokens</h4><p class="text-[11px] text-muted leading-relaxed transition-colors">Menguasai palet warna bawaan dan hierarki tipografi Tailwind.</p></div>
                        </div>
                        <div class="card-adaptive p-5 rounded-xl flex items-start gap-4 hover:border-teal-500/30 transition group h-full">
                            <div class="w-8 h-8 rounded bg-teal-500/10 text-teal-600 dark:text-teal-400 flex items-center justify-center shrink-0 font-bold text-xs transition-colors">3</div>
                            <div><h4 class="text-sm font-bold text-heading mb-1 transition-colors">Sistem Grid Terukur</h4><p class="text-[11px] text-muted leading-relaxed transition-colors">Memahami mekanika <span class="hl-term">4-Point Grid</span> untuk penetapan margin, padding, dan dimensi.</p></div>
                        </div>
                        <div class="card-adaptive p-5 rounded-xl flex items-start gap-4 hover:border-blue-500/30 transition group h-full">
                            <div class="w-8 h-8 rounded bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0 font-bold text-xs transition-colors">4</div>
                            <div><h4 class="text-sm font-bold text-heading mb-1 transition-colors">Styling Visual UI</h4><p class="text-[11px] text-muted leading-relaxed transition-colors">Memoles antarmuka dengan kedalaman bayangan (shadow) dan sudut (border-radius).</p></div>
                        </div>
                        <div class="bg-gradient-to-br from-cyan-100 to-blue-100 dark:from-cyan-900/40 dark:to-blue-900/40 border border-cyan-200 dark:border-cyan-500/30 p-5 rounded-xl flex items-start gap-4 hover:shadow-[0_0_20px_rgba(6,182,212,0.2)] transition group h-full col-span-1 sm:col-span-2 md:col-span-2 cursor-default">
                            <div class="w-8 h-8 rounded bg-white/50 dark:bg-white/10 text-cyan-700 dark:text-white flex items-center justify-center shrink-0 font-bold text-xs shadow-sm dark:shadow-none">🏁</div>
                            <div><h4 class="text-sm font-bold text-cyan-900 dark:text-white mb-1 transition-colors">Final Mission</h4><p class="text-[11px] text-cyan-800 dark:text-white/70 leading-relaxed transition-colors">Menyusun arsitektur komponen Notification Card secara langsung untuk melatih penulisan kelas Tailwind.</p></div>
                        </div>
                    </div>
                </div>

                <article class="space-y-32 md:space-y-40">
                    
                    {{-- LESSON 1 --}}
                    <section id="section-7" class="lesson-section scroll-mt-32" data-lesson-id="7">
                        <div class="space-y-8 md:space-y-10">
                            <div class="space-y-4 border-l-4 border-cyan-500 pl-4 md:pl-6">
                                <span class="text-cyan-600 dark:text-cyan-400 font-mono text-xs uppercase tracking-widest transition-colors">Lesson 1.2.1</span>
                                <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-heading leading-[1.1] transition-colors">
                                    Filosofi <br> 
                                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-500 to-blue-600 dark:from-cyan-400 dark:to-blue-500">Utility-First</span>
                                </h2>
                            </div>
                            
                            <div class="space-y-4">
                                <h3 class="text-lg md:text-xl font-bold text-heading flex items-center gap-2 transition-colors"><span class="w-6 h-6 rounded bg-cyan-500 dark:bg-cyan-600 flex items-center justify-center text-[10px] text-white shrink-0">A</span> Separation of Concerns vs Utility-First</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-base md:text-lg leading-relaxed space-y-4 text-justify transition-colors">
                                    <p>Dalam CSS konvensional, praktik standar adalah memisahkan file struktur (HTML) dan gaya (CSS) atau yang dikenal sebagai <span class="hl-term">Separation of Concerns</span>. Proses ini mengharuskan Anda membuat nama kelas abstrak (misal: <code>.card-profile</code>) di HTML, lalu berpindah ke file CSS untuk mendefinisikan gaya warna dan ukurannya. Pendekatan ini rentan memperlambat proses pengembangan karena mengharuskan Anda melakukan <em>context switching</em> (berpindah antar-file).</p>
                                    <p><strong>Tailwind CSS menggunakan arsitektur Utility-First.</strong> Anda tidak lagi menulis CSS di file terpisah, melainkan menyusun kelas fungsional bermakna tunggal secara langsung di dalam elemen HTML. Contohnya: kelas <code>flex</code> akan mengaktifkan Flexbox; <code>pt-4</code> memberikan <em>padding-top</em>; dan <code>text-center</code> meratakan teks ke tengah. Pendekatan ini menghilangkan kebutuhan untuk mencari nama kelas yang tepat dan secara signifikan mempercepat proses pembuatan UI (<em>prototyping</em>).</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-lg md:text-xl font-bold text-heading flex items-center gap-2 transition-colors"><span class="w-6 h-6 rounded bg-blue-500 dark:bg-blue-600 flex items-center justify-center text-[10px] text-white shrink-0">B</span> Efisiensi Skalabilitas & Kompilator JIT</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-base md:text-lg leading-relaxed space-y-4 text-justify transition-colors">
                                    <p>Salah satu masalah utama CSS konvensional adalah ukuran filenya yang terus membengkak. Setiap kali fitur baru ditambahkan, kode CSS baru ditulis. Hal ini sering menyisakan kode yang tidak lagi terpakai (<em>Dead Code</em>) karena tidak ada yang berani menghapusnya demi menghindari kerusakan tata letak di halaman lain.</p>
                                    <p>Tailwind menyelesaikan masalah skalabilitas ini. Kelas utilitasnya bersifat global dan dapat digunakan berulang kali di seluruh proyek, sehingga ukuran CSS tidak tumbuh secara eksponensial. Ditambah dengan kompilator <strong>JIT (Just-In-Time) Engine</strong>, Tailwind hanya akan membundel kelas-kelas CSS yang benar-benar Anda tulis di file HTML, menjadikan ukuran file akhir (<em>production build</em>) sangat kecil dan efisien.</p>
                                </div>
                            </div>

                            <div class="sim-bg-adaptive border border-adaptive rounded-xl overflow-hidden shadow-xl dark:shadow-2xl flex flex-col md:flex-row min-h-[420px] transition-colors mt-8 relative">
                                
                                {{-- KOTAK INSTRUKSI SIMULATOR 1 --}}
                                <div class="absolute top-0 left-0 w-full bg-cyan-600/95 dark:bg-cyan-900/95 backdrop-blur text-white p-3 z-20 flex flex-col sm:flex-row sm:justify-between items-start sm:items-center shadow-md gap-2 sm:gap-0">
                                    <div class="flex items-center gap-2 text-xs font-bold shrink-0">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                        PANDUAN SIMULASI 1
                                    </div>
                                    <p class="text-[10px] opacity-90 sm:max-w-xs md:max-w-md sm:text-right leading-tight">Tekan tombol opsi untuk melihat perbandingan penulisan sintaks antara pendekatan <strong>CSS Biasa (Konvensional)</strong> dan metode <strong>Tailwind (Utility-First)</strong>.</p>
                                </div>

                                <div class="w-full md:w-1/2 code-adaptive p-4 sm:p-6 pt-24 sm:pt-20 font-mono text-xs flex flex-col transition-colors relative z-10 min-h-[250px]">
                                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 sm:gap-0 mb-4 pb-2 border-b border-adaptive transition-colors">
                                        <span class="text-muted font-bold uppercase text-[10px] transition-colors">Code Editor</span>
                                        <div class="flex gap-2">
                                            <button onclick="setSim1('css')" class="flex-1 sm:flex-none px-3 py-1.5 bg-slate-200 dark:bg-white/5 border border-slate-300 dark:border-white/10 rounded text-slate-600 dark:text-gray-300 hover:text-slate-900 dark:hover:text-white transition text-[10px] font-bold">CSS Biasa</button>
                                            <button onclick="setSim1('tw')" class="flex-1 sm:flex-none px-3 py-1.5 bg-cyan-100 dark:bg-cyan-600/20 border border-cyan-300 dark:border-cyan-500/50 text-cyan-700 dark:text-cyan-400 rounded transition text-[10px] font-bold">Tailwind</button>
                                        </div>
                                    </div>
                                    <div id="sim1-code" class="flex-1 overflow-x-auto text-blue-600 dark:text-blue-300 leading-relaxed whitespace-pre font-mono bg-white dark:bg-black/20 p-4 rounded border border-adaptive shadow-inner dark:shadow-none transition-colors text-[11px] sm:text-xs custom-scrollbar"></div>
                                </div>
                                <div class="w-full md:w-1/2 bg-slate-100 dark:bg-[#111] flex flex-col items-center justify-center border-t md:border-t-0 md:border-l border-adaptive relative transition-colors z-10 min-h-[200px] p-6">
                                    <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-10"></div>
                                    <button class="bg-blue-600 text-white font-bold py-3 px-8 rounded-full shadow-lg hover:scale-105 transition-transform transform z-10 text-sm">
                                        Beli Sekarang
                                    </button>
                                </div>
                            </div>
                            
                            {{-- KESIMPULAN SIMULASI 1 --}}
                            <div class="mt-4 bg-gradient-to-r from-cyan-50 to-transparent dark:from-cyan-900/20 dark:to-transparent border-l-4 border-cyan-500 p-4 rounded-r-xl relative z-10 transition-colors">
                                <h5 class="text-sm font-bold text-cyan-700 dark:text-cyan-400 mb-1 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> 
                                    Kesimpulan Pendekatan
                                </h5>
                                <p class="text-[11px] sm:text-xs text-slate-700 dark:text-slate-300 leading-relaxed text-justify">
                                    Meskipun hasil akhirnya terlihat identik secara visual, metode penulisan Tailwind memungkinkan pengembang merancang antarmuka langsung di dalam file HTML. Hal ini menghemat waktu secara dramatis karena menghilangkan kebutuhan untuk menulis blok gaya (style block) yang terpisah-pisah.
                                </p>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 2 --}}
                    <section id="section-8" class="lesson-section scroll-mt-32" data-lesson-id="8">
                        <div class="space-y-8 md:space-y-10">
                            <div class="space-y-4 border-l-4 border-cyan-500 pl-4 md:pl-6">
                                <span class="text-cyan-600 dark:text-cyan-400 font-mono text-xs uppercase tracking-widest transition-colors">Lesson 1.2.2</span>
                                <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-heading leading-[1.1] transition-colors">
                                    Warna & <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-500 to-blue-600 dark:from-cyan-400 dark:to-blue-500">Tipografi</span>
                                </h2>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-lg md:text-xl font-bold text-heading flex items-center gap-2 transition-colors"><span class="w-6 h-6 rounded bg-cyan-500 dark:bg-cyan-600 flex items-center justify-center text-[10px] text-white shrink-0">A</span> Design Tokens (Sistem Palet Warna)</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-base md:text-lg leading-relaxed space-y-4 text-justify transition-colors">
                                    <p>Tailwind menyediakan sistem warna terkurasi (Design Tokens) untuk menjaga konsistensi visual di seluruh aplikasi. Daripada menggunakan nilai heksadesimal acak, Anda dapat memanfaatkan palet bawaan yang telah disusun berdasarkan tingkat kecerahan, mulai dari <code>50</code> (paling terang) hingga <code>950</code> (paling gelap/pekat).</p>
                                    <p>Contohnya, kelas <code>bg-slate-900</code> akan memberikan latar warna abu-abu gelap. Tailwind juga mendukung kontrol tingkat transparansi (opacity) secara langsung dengan menggunakan sintaks pecahan, seperti <code>bg-slate-900/50</code> untuk memberikan transparansi 50%, yang sangat berguna untuk membuat efek overlay atau glassmorphism.</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-lg md:text-xl font-bold text-heading flex items-center gap-2 transition-colors"><span class="w-6 h-6 rounded bg-blue-500 dark:bg-blue-600 flex items-center justify-center text-[10px] text-white shrink-0">B</span> Sistem Ukuran Tipografi Relatif</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-base md:text-lg leading-relaxed space-y-4 text-justify transition-colors">
                                    <p>Kelas utilitas tipografi Tailwind dirancang untuk menjaga hierarki teks yang proporsional. Skala ukuran yang disediakan berkisar dari <code>text-xs</code> hingga ukuran raksasa <code>text-9xl</code>. Selain itu, utilitas pengaturan seperti <code>leading-tight</code> (untuk line-height/spasi baris) dan <code>tracking-wide</code> (untuk letter-spacing/jarak antar huruf) tersedia untuk mengoptimalkan kenyamanan membaca (<em>readability</em>).</p>
                                    <p>Sistem tipografi ini menggunakan satuan relatif <code>rem</code> secara otomatis. Ini memastikan ukuran teks di situs web Anda akan beradaptasi secara fleksibel dengan pengaturan prasetel perbesaran layar (zoom) pada perangkat pengguna.</p>
                                </div>
                            </div>

                            <div class="sim-bg-adaptive rounded-xl border border-adaptive p-4 sm:p-6 flex flex-col lg:flex-row gap-6 lg:gap-8 shadow-xl dark:shadow-2xl relative overflow-hidden transition-colors mt-8">
                                
                                {{-- KOTAK INSTRUKSI SIMULATOR 2 --}}
                                <div class="absolute top-0 left-0 w-full bg-cyan-600/95 dark:bg-cyan-900/95 backdrop-blur text-white p-3 z-20 flex flex-col sm:flex-row sm:justify-between items-start sm:items-center shadow-md gap-2 sm:gap-0">
                                    <div class="flex items-center gap-2 text-xs font-bold shrink-0">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                        PANDUAN SIMULASI 2
                                    </div>
                                    <p class="text-[10px] opacity-90 sm:max-w-xs md:max-w-md sm:text-right leading-tight">Tekan tombol opsi besaran teks (SIZE) dan palet (COLOR) untuk melihat bagaimana properti CSS dikendalikan menggunakan kelas utilitas Tailwind.</p>
                                </div>

                                <div class="w-full lg:w-1/2 space-y-6 relative z-10 pt-24 sm:pt-20 lg:pt-16">
                                    <h4 class="text-xs font-bold text-muted uppercase transition-colors hidden sm:block">Tipografi & Warna</h4>
                                    <div>
                                        <label class="text-[10px] text-cyan-600 dark:text-cyan-400 block mb-2 font-bold transition-colors">SIZE (Ukuran Teks)</label>
                                        <div class="flex gap-2">
                                            <button onclick="updateSim2('size', 'text-sm')" class="flex-1 py-2 bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded text-[11px] sm:text-xs hover:bg-cyan-50 dark:hover:bg-cyan-600/20 transition text-slate-600 dark:text-gray-300 font-medium">text-sm</button>
                                            <button onclick="updateSim2('size', 'text-2xl')" class="flex-1 py-2 bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded text-[11px] sm:text-xs hover:bg-cyan-50 dark:hover:bg-cyan-600/20 transition text-slate-600 dark:text-gray-300 font-medium">text-2xl</button>
                                            <button onclick="updateSim2('size', 'text-5xl')" class="flex-1 py-2 bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded text-[11px] sm:text-xs hover:bg-cyan-50 dark:hover:bg-cyan-600/20 transition text-slate-600 dark:text-gray-300 font-medium">text-5xl</button>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="text-[10px] text-pink-600 dark:text-pink-400 block mb-2 font-bold transition-colors">COLOR (Palet Terkurasi)</label>
                                        <div class="flex gap-2">
                                            <button onclick="updateSim2('color', 'text-slate-500 dark:text-slate-300')" class="flex-1 py-2 bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded text-[11px] sm:text-xs hover:bg-slate-200 dark:hover:bg-slate-600/20 transition text-slate-600 dark:text-gray-300 font-medium">Slate</button>
                                            <button onclick="updateSim2('color', 'text-cyan-600 dark:text-cyan-400')" class="flex-1 py-2 bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded text-[11px] sm:text-xs hover:bg-cyan-100 dark:hover:bg-cyan-600/20 transition text-cyan-600 dark:text-cyan-400 font-medium">Cyan</button>
                                            <button onclick="updateSim2('color', 'text-rose-600 dark:text-rose-500')" class="flex-1 py-2 bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded text-[11px] sm:text-xs hover:bg-rose-100 dark:hover:bg-rose-600/20 transition text-rose-600 dark:text-rose-500 font-medium">Rose</button>
                                        </div>
                                    </div>
                                    <div class="bg-white dark:bg-black/40 p-3 rounded border border-adaptive font-mono text-[10px] sm:text-xs text-slate-500 dark:text-gray-400 mt-4 shadow-inner dark:shadow-none transition-colors break-all">
                                        &lt;h1 class="<span id="sim2-code" class="text-cyan-600 dark:text-cyan-300">font-bold text-base text-white</span>"&gt;...&lt;/h1&gt;
                                    </div>
                                </div>
                                <div class="w-full lg:w-1/2 bg-slate-800 dark:bg-black/40 rounded-xl flex items-center justify-center border border-slate-700 dark:border-white/5 min-h-[200px] lg:min-h-[250px] relative z-10 transition-colors shadow-inner mt-4 lg:mt-0 p-4 overflow-hidden text-center">
                                    <h1 id="sim2-target" class="text-base text-white transition-all duration-300 font-bold break-words w-full">Hello Tailwind</h1>
                                </div>
                            </div>
                            
                            {{-- KESIMPULAN SIMULASI 2 --}}
                            <div class="mt-4 bg-gradient-to-r from-cyan-50 to-transparent dark:from-cyan-900/20 dark:to-transparent border-l-4 border-cyan-500 p-4 rounded-r-xl relative z-10 transition-colors">
                                <h5 class="text-sm font-bold text-cyan-700 dark:text-cyan-400 mb-1 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> 
                                    Kesimpulan Sistem Visual
                                </h5>
                                <p class="text-[11px] sm:text-xs text-slate-700 dark:text-slate-300 leading-relaxed text-justify">
                                    Dengan membatasi penggunaan warna dan ukuran menggunakan token bawaan, Tailwind memastikan desain Anda tetap seragam dan sesuai dengan pedoman estetika tanpa harus menebak kode heksadesimal secara acak.
                                </p>
                            </div>

                        </div>
                    </section>

                    {{-- LESSON 3 --}}
                    <section id="section-9" class="lesson-section scroll-mt-32" data-lesson-id="9">
                        <div class="space-y-8 md:space-y-10">
                            <div class="space-y-4 border-l-4 border-cyan-500 pl-4 md:pl-6">
                                <span class="text-cyan-600 dark:text-cyan-400 font-mono text-xs uppercase tracking-widest transition-colors">Lesson 1.2.3</span>
                                <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-heading leading-[1.1] transition-colors">
                                    Spacing & <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-500 to-blue-600 dark:from-cyan-400 dark:to-blue-500">Sizing</span>
                                </h2>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-lg md:text-xl font-bold text-heading flex items-center gap-2 transition-colors"><span class="w-6 h-6 rounded bg-cyan-500 dark:bg-cyan-600 flex items-center justify-center text-[10px] text-white shrink-0">A</span> Sistem Grid 4-Titik (4-Point Grid System)</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-base md:text-lg leading-relaxed space-y-4 text-justify transition-colors">
                                    <p>Proporsi jarak antar elemen (spacing) sangat memengaruhi kualitas tata letak antarmuka. Tailwind menggunakan kalkulasi matematis <strong>4-Point Grid System</strong>. Dalam sistem ini, 1 unit jarak Tailwind sama dengan <code>0.25rem</code> (sekitar 4px pada pengaturan browser standar). Oleh karena itu, perintah <code>p-4</code> akan menghasilkan padding sebesar 16px (4 unit * 4px), sedangkan <code>m-8</code> menghasilkan margin 32px.</p>
                                    <p>Pendekatan ini mencegah Anda menggunakan jarak ganjil atau tidak beraturan (seperti 17px atau 21px) yang sering merusak ritme visual layar. Ruang kosong (Whitespace) sangat penting untuk menjaga elemen UI agar tidak terlihat menumpuk.</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-lg md:text-xl font-bold text-heading flex items-center gap-2 transition-colors"><span class="w-6 h-6 rounded bg-blue-500 dark:bg-blue-600 flex items-center justify-center text-[10px] text-white shrink-0">B</span> Sizing & Fluid Constraints</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-base md:text-lg leading-relaxed space-y-4 text-justify transition-colors">
                                    <p>Untuk mengendalikan proporsi elemen pada berbagai layar, Tailwind memiliki utilitas penetapan lebar (<code>width</code> / <code>w-</code>) dan tinggi (<code>height</code> / <code>h-</code>). Kelas ukuran tersedia dalam bentuk absolut (seperti <code>w-64</code> untuk 16rem) maupun persentase fraksional relatif (seperti <code>w-1/2</code> untuk 50% atau <code>w-full</code> untuk 100% lebar kontainer parent).</p>
                                    <p>Gunakan kelas batas maksimum seperti <code>max-w-md</code> untuk menjaga elemen konten tidak meregang terlalu panjang pada resolusi monitor besar. Anda juga bisa mengombinasikannya dengan <code>mx-auto</code> (margin horizontal otomatis) untuk membuat elemen tersebut simetris dan selalu berada di tengah layar.</p>
                                </div>
                            </div>

                            <div class="sim-bg-adaptive border border-adaptive rounded-2xl p-4 sm:p-8 relative shadow-xl dark:shadow-2xl overflow-hidden flex flex-col lg:flex-row items-center transition-colors mt-8 gap-6 lg:gap-10">
                                
                                {{-- KOTAK INSTRUKSI SIMULATOR 3 --}}
                                <div class="absolute top-0 left-0 w-full bg-cyan-600/95 dark:bg-cyan-900/95 backdrop-blur text-white p-3 z-20 flex flex-col sm:flex-row sm:justify-between items-start sm:items-center shadow-md gap-2 sm:gap-0">
                                    <div class="flex items-center gap-2 text-xs font-bold shrink-0">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                        PANDUAN SIMULASI 3
                                    </div>
                                    <p class="text-[10px] opacity-90 sm:max-w-xs md:max-w-md sm:text-right leading-tight">Geser tuas <span class="hl-term">Slider</span> pada pengaturan <span class="hl-term">Padding</span> dan <span class="hl-term">Width</span> untuk melihat bagaimana sistem skala jarak 4-Piksel bekerja dalam mendimensikan elemen.</p>
                                </div>

                                <div class="w-full lg:w-1/2 space-y-6 relative z-10 pt-24 sm:pt-20 lg:pt-16">
                                    <div>
                                        <div class="flex justify-between mb-2">
                                            <label class="text-[10px] sm:text-xs text-cyan-600 dark:text-cyan-400 font-bold uppercase transition-colors">Padding (p-)</label>
                                            <span class="text-[10px] sm:text-xs text-slate-500 dark:text-white/50 font-mono transition-colors bg-slate-100 dark:bg-white/10 px-2 py-0.5 rounded" id="sim3-label-p">class="p-4"</span>
                                        </div>
                                        <input type="range" min="0" max="12" value="4" oninput="updateSim3('p', this.value)" class="w-full accent-cyan-500 h-1.5 bg-slate-200 dark:bg-white/10 rounded cursor-pointer">
                                    </div>
                                    <div>
                                        <div class="flex justify-between mb-2">
                                            <label class="text-[10px] sm:text-xs text-blue-600 dark:text-blue-400 font-bold uppercase transition-colors">Width (w-)</label>
                                            <span class="text-[10px] sm:text-xs text-slate-500 dark:text-white/50 font-mono transition-colors bg-slate-100 dark:bg-white/10 px-2 py-0.5 rounded" id="sim3-label-w">class="w-32"</span>
                                        </div>
                                        <input type="range" min="16" max="64" value="32" oninput="updateSim3('w', this.value)" class="w-full accent-blue-500 h-1.5 bg-slate-200 dark:bg-white/10 rounded cursor-pointer">
                                    </div>
                                </div>
                                
                                <div class="code-adaptive p-4 sm:p-10 rounded-xl border border-adaptive w-full lg:w-1/2 flex justify-center h-[200px] sm:h-64 items-center relative z-10 shadow-inner transition-colors">
                                    <div id="sim3-target" class="bg-cyan-600 text-white font-bold text-center transition-all duration-300 shadow-xl overflow-hidden p-4 w-32 rounded-lg flex items-center justify-center border border-white/20 text-xs sm:text-base break-words">
                                        KONTEN
                                    </div>
                                </div>
                            </div>
                            
                            {{-- KESIMPULAN SIMULASI 3 --}}
                            <div class="mt-4 bg-gradient-to-r from-cyan-50 to-transparent dark:from-cyan-900/20 dark:to-transparent border-l-4 border-cyan-500 p-4 rounded-r-xl relative z-10 transition-colors">
                                <h5 class="text-sm font-bold text-cyan-700 dark:text-cyan-400 mb-1 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> 
                                    Kesimpulan Sistem Tata Letak Proporsional
                                </h5>
                                <p class="text-[11px] sm:text-xs text-slate-700 dark:text-slate-300 leading-relaxed text-justify">
                                    Penggunaan sistem grid yang dikalkulasi secara presisi (kelipatan 0.25rem) menghilangkan inkonsistensi pengukuran visual, menjamin bahwa ruang bernapas antar elemen selalu terdistribusi secara proporsional.
                                </p>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 4 --}}
                    <section id="section-10" class="lesson-section scroll-mt-32" data-lesson-id="10">
                        <div class="space-y-8 md:space-y-10">
                            <div class="space-y-4 border-l-4 border-cyan-500 pl-4 md:pl-6">
                                <span class="text-cyan-600 dark:text-cyan-400 font-mono text-xs uppercase tracking-widest transition-colors">Lesson 1.2.4</span>
                                <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-heading leading-[1.1] transition-colors">
                                    Borders & <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-500 to-blue-600 dark:from-cyan-400 dark:to-blue-500">Effects</span>
                                </h2>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-lg md:text-xl font-bold text-heading flex items-center gap-2 transition-colors"><span class="w-6 h-6 rounded bg-cyan-500 dark:bg-cyan-600 flex items-center justify-center text-[10px] text-white shrink-0">A</span> Estetika Geometri Visual (Border Radius)</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-base md:text-lg leading-relaxed space-y-4 text-justify transition-colors">
                                    <p>Dalam standar desain web modern, elemen antarmuka yang memiliki sudut lancip 90 derajat sering kali dihindari karena dinilai kaku dan tidak organik. Tailwind menyelesaikan kebutuhan ini dengan utilitas pembulatan sudut menggunakan kelompok kelas <code>rounded</code>.</p>
                                    <p>Anda dapat menerapkan <code>rounded-md</code> untuk melembutkan sudut kotak input atau tombol (Button). Tersedia pula skala yang lebih drastis seperti <code>rounded-2xl</code> untuk membingkai kontainer panel, hingga <code>rounded-full</code> yang secara sempurna membulatkan elemen, sangat cocok digunakan untuk mencetak tampilan bingkai foto profil atau notifikasi lencana.</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-lg md:text-xl font-bold text-heading flex items-center gap-2 transition-colors"><span class="w-6 h-6 rounded bg-blue-500 dark:bg-blue-600 flex items-center justify-center text-[10px] text-white shrink-0">B</span> Elevasi Antarmuka (Box Shadow)</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-base md:text-lg leading-relaxed space-y-4 text-justify transition-colors">
                                    <p>Bayangan (Drop Shadow) bukan sekadar dekorasi, melainkan instrumen esensial untuk mengomunikasikan kedalaman layar (Z-Index). Elemen yang terlihat memiliki bayangan lebih kuat akan dipersepsikan lebih dekat ke pengguna, memfokuskan perhatian pada komponen tersebut.</p>
                                    <p>Gunakan kelas bayangan tipis seperti <code>shadow-md</code> untuk batang navigasi (Navbar), dan bayangan tebal seperti <code>shadow-xl</code> atau <code>shadow-2xl</code> untuk pop-up modal (Dialog Windows). Kelas-kelas bayangan ini dapat dengan mudah dikombinasikan dengan efek transisi (seperti <code>hover:-translate-y-1 hover:shadow-2xl transition</code>) untuk menciptakan sensasi traksi dan komponen yang terasa interaktif merespons kursor mouse.</p>
                                </div>
                            </div>

                            <div class="sim-bg-adaptive border border-adaptive rounded-2xl p-4 sm:p-8 flex flex-col lg:flex-row gap-6 lg:gap-10 items-center shadow-xl dark:shadow-2xl relative overflow-hidden transition-colors mt-8">
                                
                                {{-- KOTAK INSTRUKSI SIMULATOR 4 --}}
                                <div class="absolute top-0 left-0 w-full bg-cyan-600/95 dark:bg-cyan-900/95 backdrop-blur text-white p-3 z-20 flex flex-col sm:flex-row sm:justify-between items-start sm:items-center shadow-md gap-2 sm:gap-0">
                                    <div class="flex items-center gap-2 text-xs font-bold shrink-0">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                        PANDUAN SIMULASI 4
                                    </div>
                                    <p class="text-[10px] opacity-90 sm:max-w-xs md:max-w-md sm:text-right leading-tight">Terapkan konfigurasi utilitas pada tingkat sudut (Border Radius) dan elevasi bayangan (Box Shadow) untuk menganalisa pembentukan ruang visual desain 3D.</p>
                                </div>

                                <div class="w-full lg:w-1/2 space-y-6 relative z-10 pt-24 sm:pt-20 lg:pt-16">
                                    <h4 class="text-xs font-bold text-muted uppercase transition-colors hidden sm:block">Visual Effects UI</h4>
                                    
                                    <div class="space-y-2">
                                        <p class="text-[10px] sm:text-xs text-cyan-600 dark:text-cyan-400 font-bold transition-colors">BORDER RADIUS (Sudut Lengkung)</p>
                                        <div class="flex gap-2">
                                            <button onclick="updateSim4('rad', 'rounded-none')" class="flex-1 py-2 bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded text-[11px] sm:text-xs hover:bg-cyan-50 dark:hover:bg-cyan-600/20 text-slate-600 dark:text-gray-400 font-medium transition-colors">Flat</button>
                                            <button onclick="updateSim4('rad', 'rounded-xl')" class="flex-1 py-2 bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded text-[11px] sm:text-xs hover:bg-cyan-50 dark:hover:bg-cyan-600/20 text-slate-600 dark:text-gray-400 font-medium transition-colors">XL Curve</button>
                                            <button onclick="updateSim4('rad', 'rounded-full')" class="flex-1 py-2 bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded text-[11px] sm:text-xs hover:bg-cyan-50 dark:hover:bg-cyan-600/20 text-slate-600 dark:text-gray-400 font-medium transition-colors">Pill Full</button>
                                        </div>
                                    </div>
                                    
                                    <div class="space-y-2">
                                        <p class="text-[10px] sm:text-xs text-blue-600 dark:text-blue-400 font-bold transition-colors">BOX SHADOW (Efek Bayangan)</p>
                                        <div class="flex gap-2">
                                            <button onclick="updateSim4('shadow', 'shadow-none')" class="flex-1 py-2 bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded text-[11px] sm:text-xs hover:bg-cyan-50 dark:hover:bg-cyan-600/20 text-slate-600 dark:text-gray-400 font-medium transition-colors">No Drop</button>
                                            <button onclick="updateSim4('shadow', 'shadow-lg')" class="flex-1 py-2 bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded text-[11px] sm:text-xs hover:bg-cyan-50 dark:hover:bg-cyan-600/20 text-slate-600 dark:text-gray-400 font-medium transition-colors">Large Gap</button>
                                            <button onclick="updateSim4('shadow', 'shadow-cyan-500/50')" class="flex-1 py-2 bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded text-[11px] sm:text-xs hover:bg-cyan-50 dark:hover:bg-cyan-600/20 text-slate-600 dark:text-gray-400 font-medium transition-colors">Cyan Glow</button>
                                        </div>
                                    </div>

                                    <div class="bg-white dark:bg-black/40 p-3 rounded border border-adaptive font-mono text-[10px] sm:text-xs text-slate-500 dark:text-gray-400 mt-4 shadow-inner dark:shadow-none transition-colors break-all">
                                        class="<span id="sim4-code" class="text-cyan-600 dark:text-cyan-300 font-bold">rounded-none shadow-none</span>"
                                    </div>
                                </div>
                                <div class="w-full lg:w-1/2 flex justify-center code-adaptive p-4 sm:p-10 rounded-xl border border-adaptive relative z-10 shadow-inner transition-colors min-h-[250px] items-center">
                                    <div id="sim4-target" class="w-24 h-24 sm:w-32 sm:h-32 bg-gradient-to-br from-cyan-400 to-blue-500 dark:from-cyan-500 dark:to-blue-600 transition-all duration-500 border border-white/20"></div>
                                </div>
                            </div>
                            
                            {{-- KESIMPULAN SIMULASI 4 --}}
                            <div class="mt-4 bg-gradient-to-r from-cyan-50 to-transparent dark:from-cyan-900/20 dark:to-transparent border-l-4 border-cyan-500 p-4 rounded-r-xl relative z-10 transition-colors">
                                <h5 class="text-sm font-bold text-cyan-700 dark:text-cyan-400 mb-1 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> 
                                    Kesimpulan Modifikasi Estetika
                                </h5>
                                <p class="text-[11px] sm:text-xs text-slate-700 dark:text-slate-300 leading-relaxed text-justify">
                                    Penggunaan utilitas kelengkungan sudut dan elevasi bayangan yang tepat merupakan protokol wajib dalam material design masa kini. Elemen yang memiliki bayangan tegas akan mengarahkan fokus interaktif pandangan pengguna terhadap antarmuka.
                                </p>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 5: ACTIVITY FINAL --}}
                    <section id="section-11" class="lesson-section scroll-mt-32 pt-10 border-t border-adaptive transition-colors" data-lesson-id="11" data-type="activity">
                        <div class="relative rounded-[1.5rem] md:rounded-[2rem] sim-bg-adaptive border border-adaptive p-4 sm:p-6 md:p-10 overflow-hidden shadow-xl dark:shadow-2xl group hover:border-cyan-500/30 transition-all duration-500">
                            
                            <div class="absolute -top-24 -right-24 w-40 h-40 md:w-64 md:h-64 bg-cyan-600/10 dark:bg-cyan-600/20 blur-[100px] rounded-full pointer-events-none transition-colors"></div>

                            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 mb-8 relative z-10">
                                <div class="p-3 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-xl text-white shadow-lg shadow-cyan-500/30 shrink-0">
                                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                                </div>
                                <div>
                                    <h2 class="text-xl md:text-2xl font-bold text-heading transition-colors">Coding Challenge: Notification Card</h2>
                                    <p class="text-cyan-700 dark:text-cyan-300 text-xs md:text-sm font-medium transition-colors mt-1 leading-relaxed text-justify">Rangkai pemahaman Anda mengenai Tag Semantik, properti kelonggaran internal (Padding), dan modifikator gaya tata letak untuk membangun kerangka Notification Card menggunakan Utility-First CSS secara interaktif di panel editor bawah.</p>
                                </div>
                            </div>

                            <div class="flex flex-col lg:grid lg:grid-cols-2 gap-6 max-w-6xl mx-auto relative z-10">
                                
                                {{-- LEFT: EDITOR --}}
                                <div class="code-adaptive rounded-xl border border-adaptive flex flex-col overflow-hidden relative shadow-inner transition-colors min-h-[500px] lg:min-h-0 lg:h-[650px]">
                                    
                                    {{-- LOCK OVERLAY --}}
                                    <div id="lockOverlay" class="hidden absolute inset-0 bg-white/90 dark:bg-[#050912]/95 backdrop-blur-md z-50 flex flex-col items-center justify-center text-center p-6 border-4 border-emerald-500/20 m-1 rounded-lg transition-colors">
                                        <div class="w-16 h-16 md:w-24 md:h-24 bg-emerald-100 dark:bg-emerald-500/10 rounded-full flex items-center justify-center mb-4 md:mb-6 border border-emerald-300 dark:border-emerald-500/50 shadow-[0_0_50px_rgba(16,185,129,0.2)] dark:shadow-[0_0_50px_rgba(16,185,129,0.3)] animate-bounce transition-colors">
                                            <svg class="w-8 h-8 md:w-12 md:h-12 text-emerald-600 dark:text-emerald-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                        </div>
                                        <h3 class="text-2xl md:text-3xl font-black text-slate-900 dark:text-white mb-2 tracking-tight transition-colors">MISSION COMPLETED!</h3>
                                        <p class="text-sm md:text-base font-bold text-slate-500 dark:text-white/60 mb-6 md:mb-8 max-w-xs transition-colors">Subbab Konsep Dasar berhasil dieksekusi. Data pencapaian telah tersimpan dalam rekam kemajuan.</p>
                                        <button disabled class="px-6 py-2.5 md:px-8 md:py-3 rounded-full bg-slate-200 dark:bg-white/5 border border-slate-300 dark:border-white/10 text-slate-500 dark:text-white/30 text-[10px] md:text-xs font-bold cursor-not-allowed uppercase tracking-widest transition-colors">Review Mode Only</button>
                                    </div>

                                    <div class="bg-slate-200 dark:bg-[#0f141e] px-4 py-2 border-b border-adaptive flex justify-between items-center transition-colors">
                                        <span class="text-[10px] md:text-xs text-slate-600 dark:text-white/50 font-mono font-bold transition-colors">Component.blade.php</span>
                                        <button onclick="resetEditor()" class="text-[10px] text-red-500 dark:text-red-400 hover:text-red-600 dark:hover:text-red-300 transition-colors uppercase font-bold">Reset Kerangka</button>
                                    </div>
                                    
                                    <div id="codeEditor" class="h-[250px] lg:flex-1 w-full border-b border-adaptive transition-colors"></div>

                                    {{-- CLUES AREA DENGAN INSTRUKSI KONTEKSTUAL --}}
                                    <div class="p-4 md:p-6 bg-slate-50 dark:bg-[#0f141e] transition-colors flex flex-col h-[350px] lg:h-[300px]">
                                        <div class="flex justify-between items-center mb-4">
                                            <span class="text-[10px] uppercase font-bold text-muted transition-colors">Requirements Checklist</span>
                                            <span id="progressText" class="text-[10px] font-mono text-cyan-600 dark:text-cyan-400 font-bold transition-colors bg-cyan-100 dark:bg-cyan-900/30 px-2 py-0.5 rounded">0/4 Terpenuhi</span>
                                        </div>
                                        
                                        <div class="space-y-4 text-[10px] md:text-[11px] text-slate-600 dark:text-white/60 mb-6 transition-colors overflow-y-auto custom-scrollbar pr-2 flex-1">
                                            <div id="check-bg" class="flex items-start gap-3 transition-colors">
                                                <span class="w-4 h-4 mt-0.5 rounded-full border border-slate-300 dark:border-white/20 flex items-center justify-center text-[8px] transition-colors shrink-0"></span> 
                                                <div>
                                                    <strong class="block text-slate-800 dark:text-white mb-0.5">Warna Latar Belakang</strong>
                                                    <span class="opacity-80">Ubah gaya div terluar agar memiliki tema gelap menggunakan kelas <code class="text-cyan-600 dark:text-cyan-400 font-bold hl-term">bg-slate-800</code>.</span>
                                                </div>
                                            </div>
                                            <div id="check-pad" class="flex items-start gap-3 transition-colors">
                                                <span class="w-4 h-4 mt-0.5 rounded-full border border-slate-300 dark:border-white/20 flex items-center justify-center text-[8px] transition-colors shrink-0"></span> 
                                                <div>
                                                    <strong class="block text-slate-800 dark:text-white mb-0.5">Padding Interior</strong>
                                                    <span class="opacity-80">Berikan kelonggaran ruang bernapas (padding) di sekeliling komponen sebesar 24px menggunakan utilitas 4-Point Grid, yaitu <code class="text-cyan-600 dark:text-cyan-400 font-bold hl-term">p-6</code>.</span>
                                                </div>
                                            </div>
                                            <div id="check-flex" class="flex items-start gap-3 transition-colors">
                                                <span class="w-4 h-4 mt-0.5 rounded-full border border-slate-300 dark:border-white/20 flex items-center justify-center text-[8px] transition-colors shrink-0"></span> 
                                                <div>
                                                    <strong class="block text-slate-800 dark:text-white mb-0.5">Orientasi Flexbox Baris</strong>
                                                    <span class="opacity-80">Cegah ikon dan tulisan menumpuk ke bawah dengan memposisikannya secara horizontal menggunakan <code class="text-cyan-600 dark:text-cyan-400 font-bold hl-term">flex</code>, disertai penyekat celah antar elemen <code class="text-cyan-600 dark:text-cyan-400 font-bold hl-term">gap-4</code>.</span>
                                                </div>
                                            </div>
                                            <div id="check-round" class="flex items-start gap-3 transition-colors">
                                                <span class="w-4 h-4 mt-0.5 rounded-full border border-slate-300 dark:border-white/20 flex items-center justify-center text-[8px] transition-colors shrink-0"></span> 
                                                <div>
                                                    <strong class="block text-slate-800 dark:text-white mb-0.5">Modifikasi Radius Sudut</strong>
                                                    <span class="opacity-80">Lembutkan patahan sudut kotak yang tajam dengan menyisipkan lekukan sudut yang besar melalui kelas <code class="text-cyan-600 dark:text-cyan-400 font-bold hl-term">rounded-xl</code>.</span>
                                                </div>
                                            </div>
                                        </div>

                                        <button id="submitExerciseBtn" onclick="checkSolution()" disabled class="w-full py-3 rounded-lg bg-emerald-600 text-white font-bold text-[11px] md:text-xs shadow-lg shadow-emerald-500/20 hover:bg-emerald-500 transition-all cursor-not-allowed opacity-50 flex items-center justify-center gap-2 mt-auto shrink-0">
                                            <span>Validasi Kode Sistem</span>
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </button>
                                    </div>
                                </div>

                                {{-- RIGHT: BROWSER PREVIEW --}}
                                <div class="bg-white dark:bg-[#1e1e1e] rounded-xl border border-adaptive flex flex-col relative overflow-hidden shadow-sm dark:shadow-none transition-colors min-h-[350px] lg:min-h-0 lg:h-[650px]">
                                    <div class="bg-slate-100 dark:bg-[#2d2d2d] px-4 py-2 border-b border-adaptive flex items-center justify-between transition-colors shrink-0">
                                        <span class="text-[10px] md:text-xs text-slate-500 dark:text-gray-400 font-mono font-bold transition-colors">Canvas Rendering (Preview)</span>
                                        <span class="text-[10px] bg-emerald-100 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 px-2 py-0.5 rounded border border-emerald-200 dark:border-emerald-500/20 font-bold transition-colors">Live Reloading</span>
                                    </div>
                                    <div class="flex-1 bg-slate-50 dark:bg-gray-900 p-2 sm:p-6 lg:p-8 flex items-center justify-center relative overflow-hidden transition-colors w-full h-full">
                                        <iframe id="previewFrame" class="w-full h-full border-0 bg-transparent transition-colors"></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                </article>

                {{-- FOOTER NAVIGATION --}}
                <div class="mt-20 md:mt-32 pt-8 border-t border-adaptive flex flex-col sm:flex-row justify-between items-center gap-4 sm:gap-0 transition-colors">
                    <a href="{{ route('courses.htmldancss') ?? '#' }}" class="group flex items-center gap-4 text-muted hover:text-heading transition-colors w-full sm:w-auto justify-center sm:justify-start">
                        <div class="w-10 h-10 md:w-12 md:h-12 rounded-full border border-adaptive flex items-center justify-center group-hover:bg-slate-100 dark:group-hover:bg-white/5 transition-colors shrink-0">
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        </div>
                        <div class="text-left">
                            <div class="text-[10px] uppercase tracking-widest font-bold opacity-60">Kembali ke Indeks</div>
                            <div class="font-black text-xs md:text-sm line-clamp-1">Konsep Dasar HTML & CSS</div>
                        </div>
                    </a>
                    
                    <div id="nextChapterBtn" class="group flex items-center gap-4 text-right text-muted cursor-not-allowed opacity-50 pointer-events-none transition-all duration-500 w-full sm:w-auto justify-center sm:justify-end">
                        <div class="text-right">
                            <div id="nextLabel" class="text-[10px] uppercase tracking-widest font-bold opacity-60">Akses Modul Terkunci</div>
                            <div class="font-black text-xs md:text-sm line-clamp-1">Latar Belakang Tailwind CSS</div>
                        </div>
                        <div id="nextIcon" class="w-10 h-10 md:w-12 md:h-12 rounded-full border border-adaptive flex items-center justify-center bg-slate-100 dark:bg-white/5 transition-colors shrink-0">
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
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
    window.LESSON_IDS = [7, 8, 9, 10, 11]; 
    
    let rawCompletedIds = {!! json_encode($completedLessonIds ?? []) !!};
    window.COMPLETED_IDS = rawCompletedIds.map(id => Number(id)); 
    let completedSet = new Set(window.COMPLETED_IDS);
    
    const ACTIVITY_LESSON_ID = 11; 
    let activityCompleted = completedSet.has(ACTIVITY_LESSON_ID);

    document.addEventListener('DOMContentLoaded', () => {
        initScrollSpy();
        initSidebarScroll();
        initVisualEffects();
        
        // Render Progress Bar awal
        updateProgressUI(false); 
        
        // Inisialisasi Monaco Editor
        initMonaco();
        setSim1('css');
        
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
       SIMULATORS 1-4 (Visual)
       ========================================== */
    function setSim1(mode) {
        const codeBox = document.getElementById('sim1-code');
        if (mode === 'css') {
            codeBox.innerHTML = `.btn {\n  <span class="text-blue-400">background-color</span>: <span class="text-pink-400">blue</span>;\n  <span class="text-blue-400">color</span>: <span class="text-pink-400">white</span>;\n  <span class="text-blue-400">padding</span>: <span class="text-pink-400">10px 20px</span>;\n  <span class="text-blue-400">border-radius</span>: <span class="text-pink-400">5px</span>;\n}`;
        } else {
            codeBox.innerHTML = `&lt;<span class="text-pink-500">button</span> <span class="text-emerald-400">class</span>="<span class="hl-term text-blue-500">bg-blue-600 text-white px-4 py-2 rounded</span>"&gt;\n  Beli Sekarang\n&lt;/<span class="text-pink-500">button</span>&gt;`;
        }
    }

    let typeState = { size: 'text-base', color: 'text-white', weight: 'font-normal' };
    function updateSim2(cat, val) {
        const isDark = document.documentElement.classList.contains('dark');
        const target = document.getElementById('sim2-target');
        const code = document.getElementById('sim2-code');
        
        if(cat === 'color') {
            target.classList.remove('text-slate-300', 'text-slate-500', 'text-cyan-400', 'text-cyan-600', 'text-rose-500', 'text-rose-600', 'text-white', 'text-slate-800');
            typeState.color = val;
        }
        if(cat === 'size') {
            target.classList.remove('text-sm', 'text-base', 'text-2xl', 'text-5xl');
            typeState.size = val;
        }
        
        target.classList.add(typeState.size);
        const colorClasses = typeState.color.split(' ');
        colorClasses.forEach(c => target.classList.add(c));
        
        const finalColorClass = colorClasses.length > 1 ? (isDark ? colorClasses[1].replace('dark:', '') : colorClasses[0]) : colorClasses[0];
        code.innerText = `font-bold ${typeState.size} ${finalColorClass}`;
    }

    function updateSim3(cat, val) {
        const target = document.getElementById('sim3-target');
        if(cat === 'p') { document.getElementById('sim3-label-p').innerText = `class="p-${val}"`; target.style.padding = `${val * 4}px`; }
        if(cat === 'w') { document.getElementById('sim3-label-w').innerText = `class="w-${val}"`; target.style.width = `${val * 4}px`; }
    }

    let effectState = { rad: 'rounded-none', shadow: 'shadow-none' };
    function updateSim4(cat, val) {
        const target = document.getElementById('sim4-target');
        const code = document.getElementById('sim4-code');
        if(cat === 'rad') { target.classList.remove('rounded-none', 'rounded-xl', 'rounded-full'); target.classList.add(val); effectState.rad = val; }
        if(cat === 'shadow') { target.classList.remove('shadow-none', 'shadow-lg', 'shadow-2xl', 'shadow-cyan-500/50'); target.classList.add(val); effectState.shadow = val; }
        code.innerText = `${effectState.rad} ${effectState.shadow}`;
    }


    /* ==========================================
       REALTIME CODING & ACTIVITY LOGIC (Lesson 11)
       ========================================== */
    let editor;
    const starterCode = `<div class="">
  
  <div class="p-3 bg-cyan-500/20 rounded-full shrink-0 h-fit">
    <svg class="w-6 h-6 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
  </div>

  <div>
    <h4 class="font-bold text-lg text-white">Notifikasi Sistem Data</h4>
    <p class="text-slate-400 text-sm mt-1">Sistem antarmuka web berhasil diperbarui ke versi stabil.</p>
  </div>

</div>`;

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
            { id: 'check-bg', regex: /bg-slate-800/, valid: false },
            { id: 'check-pad', regex: /p-6/, valid: false },
            { id: 'check-flex', regex: /flex.*gap-4|gap-4.*flex/, valid: false },
            { id: 'check-round', regex: /rounded-xl/, valid: false }
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
        
        document.getElementById('progressText').innerText = `${passedCount}/4 Terpenuhi`;
        const btn = document.getElementById('submitExerciseBtn');
        if (passedCount === 4) {
            btn.disabled = false; btn.classList.remove('cursor-not-allowed', 'opacity-50');
            btn.innerHTML = `<span>Validasi Kode Sistem</span><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`;
        } else {
            btn.disabled = true; btn.classList.add('cursor-not-allowed', 'opacity-50');
            btn.innerHTML = `<span>Menunggu Persyaratan Terpenuhi</span><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`;
        }
    }

    function resetEditor() { if(editor && !activityCompleted) { editor.setValue(starterCode); validateCode(starterCode); } }

    async function checkSolution() {
        if(activityCompleted) return;
        const btn = document.getElementById('submitExerciseBtn');
        
        btn.innerHTML = '<span class="animate-pulse">Memverifikasi kode...</span>'; 
        btn.disabled = true;
        
        try {
            await saveLessonToDB(ACTIVITY_LESSON_ID); 
            
            activityCompleted = true; 
            lockActivityUI(); 
            unlockNextChapter(); 
        } catch(e) { 
            console.error(e); 
            btn.innerHTML = "Gagal memvalidasi. Silakan coba kembali."; 
            btn.disabled = false; 
        }
    }

    function lockActivityUI() {
        document.getElementById('lockOverlay').classList.remove('hidden');
        if(editor) editor.updateOptions({ readOnly: true });
        
        const btn = document.getElementById('submitExerciseBtn'); 
        btn.innerText = "Panel Eksekusi Terkunci (Selesai)"; 
        btn.disabled = true;
        btn.classList.remove('bg-emerald-600', 'hover:bg-emerald-500');
        btn.classList.add('bg-slate-400', 'dark:bg-slate-700', 'text-slate-200', 'cursor-not-allowed', 'shadow-none');

        if(editor && activityCompleted) {
            editor.setValue(`<div class="bg-slate-800 p-6 flex gap-4 rounded-xl">\n  \n  <div class="p-3 bg-cyan-500/20 rounded-full shrink-0 h-fit">\n    <svg class="w-6 h-6 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>\n  </div>\n\n  <div>\n    <h4 class="font-bold text-lg text-white">Notifikasi Sistem Data</h4>\n    <p class="text-slate-400 text-sm mt-1">Sistem antarmuka web berhasil diperbarui ke versi stabil.</p>\n  </div>\n\n</div>`);
            validateCode(editor.getValue());
        }
    }

    function unlockNextChapter() {
        const btn = document.getElementById('nextChapterBtn');
        if(btn) {
            btn.classList.remove('cursor-not-allowed', 'opacity-50', 'pointer-events-none', 'text-muted');
            btn.classList.add('text-cyan-600', 'dark:text-cyan-400', 'cursor-pointer');
            
            document.getElementById('nextLabel').innerText = "Akses Navigasi Terbuka";
            document.getElementById('nextLabel').classList.remove('opacity-60');
            document.getElementById('nextLabel').classList.add('text-cyan-600', 'dark:text-cyan-400', 'opacity-100');
            
            const icon = document.getElementById('nextIcon');
            icon.innerHTML = `<svg class="w-4 h-4 md:w-5 md:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>`;
            icon.classList.remove('bg-slate-100', 'dark:bg-white/5');
            icon.classList.add('bg-cyan-100', 'dark:bg-cyan-500/20', 'border-cyan-300', 'dark:border-cyan-500/50', 'text-cyan-600', 'dark:text-cyan-400', 'shadow-lg');
            
            btn.onclick = () => window.location.href = "{{ route('courses.latarbelakang') ?? '#' }}"; 
        }
    }

    /* ==========================================
       7. SCROLL SPY & SIDEBAR LOGIC
       ========================================== */
    function highlightAnchor(id) {
        const isDark = document.documentElement.classList.contains('dark');
        const anchors = document.querySelectorAll('.sidebar-anchor');

        anchors.forEach(a => {
            a.classList.remove('bg-slate-100', 'dark:bg-white/5', 'border-cyan-500', 'border-amber-500');
            a.classList.add('border-transparent');
            
            const dot = a.querySelector('.anchor-dot');
            const isActivity = a.dataset.type === 'activity';

            dot.classList.remove('scale-125', 'shadow-sm', 'dark:shadow-[0_0_10px_#22d3ee]', 'dark:shadow-[0_0_10px_#f59e0b]', 'bg-cyan-500', 'dark:bg-cyan-400');
            
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
                dot.classList.add(isDark ? 'dark:bg-cyan-400' : 'bg-cyan-500', 'scale-125', isDark ? 'dark:shadow-[0_0_10px_#22d3ee]' : 'shadow-sm');
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