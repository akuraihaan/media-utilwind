@extends('layouts.landing')
@section('title','Bab 2.1 · Layouting dengan Flexbox')

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
        --accent: #6366f1; /* Indigo 500 */
        --accent-glow: rgba(99, 102, 241, 0.3);
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
        --accent-glow: rgba(99, 102, 241, 0.5);
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

    .hl-term {
        background-color: rgba(99, 102, 241, 0.15);
        color: #4f46e5;
        padding: 0.125rem 0.375rem;
        border-radius: 0.375rem;
        font-weight: 600;
        font-style: normal;
        white-space: nowrap;
        border: 1px solid rgba(99, 102, 241, 0.3);
    }
    .dark .hl-term {
        background-color: rgba(99, 102, 241, 0.2);
        color: #818cf8;
        border-color: rgba(99, 102, 241, 0.4);
    }

    /* SCROLLBAR */
    .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(150,150,150,0.3); border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: var(--accent); }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }
    
    /* ANIMATIONS & EFFECTS */
    #animated-bg { 
        background: radial-gradient(800px circle at 20% 20%, rgba(99,102,241,.10), transparent 40%), 
                    radial-gradient(800px circle at 80% 80%, rgba(168,85,247,.10), transparent 40%); 
        animation: bgMove 20s ease-in-out infinite alternate; 
    }
    .dark #animated-bg {
        background: radial-gradient(800px circle at 20% 20%, rgba(99,102,241,.15), transparent 40%), 
                    radial-gradient(800px circle at 80% 80%, rgba(168,85,247,.15), transparent 40%); 
    }
    @keyframes bgMove{to{transform:scale(1.15)}}
    @keyframes shake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-5px); } 75% { transform: translateX(5px); } }
    .shake { animation: shake 0.4s ease-in-out; }
    
    /* FIX MOBILE RESPONSIVE SIDEBAR BLUR */
    
    /* SCROLLSPY SIDEBAR ACTIVE */
    .nav-item { display: flex; width: 100%; text-align: left; align-items: center; gap: 12px; padding: 10px 14px; font-size: 0.85rem; color: var(--text-muted); border-radius: 8px; transition: all 0.2s; position: relative; }
    .nav-item:hover { color: var(--text-main); background: var(--card-hover); }
    .nav-item.active { color: #6366f1; background: rgba(99, 102, 241, 0.05); font-weight: 600; }
    .dot { width: 6px; height: 6px; border-radius: 50%; background: #94a3b8; transition: all 0.3s; }
    .dark .dot { background: #334155; }
    .nav-item.active .dot { background: #6366f1; box-shadow: 0 0 8px #6366f1; transform: scale(1.2); }
</style>



<div id="courseRoot" class="relative h-screen bg-adaptive text-adaptive font-sans overflow-hidden flex flex-col selection:bg-indigo-500/30 pt-20 transition-colors duration-500">

    {{-- BACKGROUND EFFECTS --}}
    <div class="fixed inset-0 -z-50 pointer-events-none">
        <div id="animated-bg" class="absolute inset-0 opacity-50 transition-opacity"></div>
        <div class="absolute top-[-20%] left-[-10%] w-[1000px] h-[1000px] bg-indigo-500/5 dark:bg-indigo-900/20 rounded-full blur-[150px] animate-pulse transition-colors"></div>
        <div class="absolute bottom-[-20%] right-[-10%] w-[800px] h-[800px] bg-purple-500/5 dark:bg-purple-900/20 rounded-full blur-[100px] transition-colors"></div>
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
                    <div class="w-8 h-8 rounded-lg bg-indigo-500/10 dark:bg-indigo-500/20 border border-indigo-500/20 flex items-center justify-center font-bold text-xs text-indigo-600 dark:text-indigo-400 transition-colors">2.1</div>
                    <div>
                        <h1 class="text-sm font-bold text-heading transition-colors">Layouting dengan Flexbox</h1>
                        <p class="text-[10px] text-muted transition-colors">Sistem Tata Letak Satu Dimensi</p>
                    </div>
                </div>
                
                {{-- DYNAMIC PROGRESS BAR UI --}}
                <div class="flex items-center gap-3">
                    <div class="hidden sm:block w-32 h-1.5 bg-slate-200 dark:bg-white/10 rounded-full overflow-hidden transition-colors">
                        <div id="topProgressBar" class="h-full bg-gradient-to-r from-indigo-400 to-purple-500 w-0 transition-all duration-500 shadow-[0_0_10px_rgba(99,102,241,0.5)]"></div>
                    </div>
                    <span id="progressLabelTop" class="text-indigo-600 dark:text-indigo-400 font-bold text-xs transition-colors">0%</span>
                </div>
            </div>

            <div class="p-6 lg:p-16 max-w-5xl mx-auto pb-40">
                
                {{-- TUJUAN PEMBELAJARAN --}}
                <div class="mb-24">
                    <h3 class="text-xl font-bold text-heading mb-6 flex items-center gap-2 transition-colors">
                        <svg class="w-5 h-5 text-indigo-500 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                        Tujuan Pembelajaran
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="card-adaptive border border-adaptive p-6 rounded-xl flex items-start gap-4 hover:border-indigo-500/30 transition group h-full">
                            <div class="w-10 h-10 rounded-lg bg-indigo-100 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0 font-bold text-sm border border-indigo-200 dark:border-indigo-500/10 transition-colors">1</div>
                            <div>
                                <h4 class="text-sm font-bold text-heading mb-2 group-hover:text-indigo-500 dark:group-hover:text-indigo-400 transition-colors">Mental Model</h4>
                                <p class="text-xs text-muted leading-relaxed transition-colors">Memahami peralihan dari tata letak blok statis menjadi tata letak yang fleksibel dan dinamis.</p>
                            </div>
                        </div>

                        <div class="card-adaptive border border-adaptive p-6 rounded-xl flex items-start gap-4 hover:border-purple-500/30 transition group h-full">
                            <div class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400 flex items-center justify-center shrink-0 font-bold text-sm border border-purple-200 dark:border-purple-500/10 transition-colors">2</div>
                            <div>
                                <h4 class="text-sm font-bold text-heading mb-2 group-hover:text-purple-500 dark:group-hover:text-purple-400 transition-colors">Axis & Wrapping</h4>
                                <p class="text-xs text-muted leading-relaxed transition-colors">Mengelola sumbu utama penempatan elemen dan memecah baris pada layar sempit.</p>
                            </div>
                        </div>

                        <div class="card-adaptive border border-adaptive p-6 rounded-xl flex items-start gap-4 hover:border-fuchsia-500/30 transition group h-full">
                            <div class="w-10 h-10 rounded-lg bg-fuchsia-100 dark:bg-fuchsia-900/20 text-fuchsia-600 dark:text-fuchsia-400 flex items-center justify-center shrink-0 font-bold text-sm border border-fuchsia-200 dark:border-fuchsia-500/10 transition-colors">3</div>
                            <div>
                                <h4 class="text-sm font-bold text-heading mb-2 group-hover:text-fuchsia-500 dark:group-hover:text-fuchsia-400 transition-colors">Distribution</h4>
                                <p class="text-xs text-muted leading-relaxed transition-colors">Membagi sisa ruang antar elemen dengan utilitas Justify dan Align secara akurat.</p>
                            </div>
                        </div>

                        <div class="card-adaptive border border-adaptive p-6 rounded-xl flex items-start gap-4 hover:border-pink-500/30 transition group h-full">
                            <div class="w-10 h-10 rounded-lg bg-pink-100 dark:bg-pink-900/20 text-pink-600 dark:text-pink-400 flex items-center justify-center shrink-0 font-bold text-sm border border-pink-200 dark:border-pink-500/10 transition-colors">4</div>
                            <div>
                                <h4 class="text-sm font-bold text-heading mb-2 group-hover:text-pink-500 dark:group-hover:text-pink-400 transition-colors">Flexibility</h4>
                                <p class="text-xs text-muted leading-relaxed transition-colors">Menerapkan kelenturan komponen untuk mengatur ukuran dan pencegahan kompresi.</p>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-indigo-900/10 dark:to-purple-900/10 border border-indigo-300 dark:border-indigo-500/20 p-6 rounded-xl flex items-start gap-4 hover:shadow-[0_0_30px_rgba(99,102,241,0.15)] transition group h-full col-span-1 lg:col-span-4 cursor-default">
                            <div class="w-10 h-10 rounded-lg bg-white/50 dark:bg-white/10 text-indigo-700 dark:text-white flex items-center justify-center shrink-0 font-bold text-lg border border-white/10 shadow-sm dark:shadow-none transition-colors">🏁</div>
                            <div>
                                <h4 class="text-sm font-bold text-indigo-900 dark:text-white mb-2 transition-colors">Final Mission: Fix the Navbar (Expert)</h4>
                                <p class="text-xs text-indigo-800 dark:text-white/60 leading-relaxed max-w-3xl transition-colors">Analisis antarmuka yang rusak, lalu perbaiki strukturnya dengan kombinasi utilitas Flexbox agar responsif dan solid pada seluruh ukuran layar perangkat.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <article class="space-y-40">
                    
                    {{-- LESSON 29: FONDASI FLEX --}}
                    <section id="section-29" class="lesson-section scroll-mt-32" data-lesson-id="29">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-indigo-500 pl-6">
                                <span class="text-indigo-600 dark:text-indigo-400 font-mono text-xs uppercase tracking-widest transition-colors">Lesson 2.1.1</span>
                                <h2 class="text-4xl lg:text-5xl font-black text-heading leading-[1.1] transition-colors">
                                    Filosofi & <br> 
                                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-purple-600 dark:from-indigo-400 dark:to-purple-500">Flex Container</span>
                                </h2>
                            </div>
                            
                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-heading flex items-center gap-2 transition-colors"><span class="w-6 h-6 rounded bg-indigo-500 dark:bg-indigo-600 flex items-center justify-center text-[10px] text-white">A</span> Konsep Tata Letak Fleksibel</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-lg leading-relaxed space-y-4 text-justify transition-colors">
                                    <p>Sebelum kehadiran sistem Flexbox, penempatan elemen web sangat mengandalkan properti yang tidak diperuntukkan bagi tata letak rumit, seperti properti Float atau Absolute Positioning. Pendekatan lama ini membuat kode HTML menjadi kaku dan mudah berantakan saat dimuat pada layar dengan rasio berbeda.</p>
                                    <p>Sistem Flexbox hadir untuk memecahkan masalah ini. Dengan menerapkan kelas pembungkus <span class="hl-term">flex</span> pada suatu kontainer utama, elemen di dalamnya akan berubah wujud menjadi unit yang dinamis. Elemen anak tidak lagi menumpuk ke bawah seperti sifat asli dokumen teks, melainkan berjajar sejajar dan dapat menyesuaikan ukurannya dengan ruang yang tersedia secara otomatis.</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-heading flex items-center gap-2 transition-colors"><span class="w-6 h-6 rounded bg-purple-500 dark:bg-purple-600 flex items-center justify-center text-[10px] text-white">B</span> Kontainer Flex vs Inline-Flex</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-lg leading-relaxed space-y-4 text-justify transition-colors">
                                    <p>Tailwind menyediakan dua varian kelas untuk memicu mode fleksibel. Perbedaan antara keduanya terletak pada cara kontainer tersebut menempati ruang di luar dirinya:</p>
                                    <ul class="list-disc pl-5 space-y-3 mt-4 text-base">
                                        <li><strong><code class="text-indigo-600 dark:text-indigo-400 font-bold bg-indigo-50 dark:bg-indigo-900/30 px-1 py-0.5 rounded">flex</code></strong>: Menciptakan kontainer yang berperilaku sebagai blok. Wadah ini akan memakan lebar layar secara maksimal dari induknya dan mendorong elemen di sebelahnya untuk turun ke baris baru.</li>
                                        <li><strong><code class="text-purple-600 dark:text-purple-400 font-bold bg-purple-50 dark:bg-purple-900/30 px-1 py-0.5 rounded">inline-flex</code></strong>: Membuat kontainer yang hanya mengambil lebar sesuai ukuran konten di dalamnya. Wadah ini mengizinkan elemen lain seperti teks atau tombol untuk berada di baris yang sama.</li>
                                    </ul>
                                </div>
                            </div>

                            {{-- SIMULATOR 1 --}}
                            <div class="sim-bg-adaptive border border-adaptive rounded-xl overflow-hidden shadow-xl dark:shadow-2xl p-6 lg:p-8 relative transition-colors mt-8">
                                <h4 class="text-xs font-bold text-muted uppercase mb-4 text-center transition-colors">Simulator: Penumpukan vs Sejajar</h4>
                                
                                <div class="bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-500/30 rounded-lg p-4 mb-8 text-sm text-indigo-800 dark:text-indigo-300 relative z-10 transition-colors">
                                    <p class="font-bold flex items-center gap-2 mb-2">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Panduan Simulasi Orientasi
                                    </p>
                                    <p class="m-0 opacity-90 leading-relaxed">Gunakan tombol konfigurasi di bawah untuk membandingkan susunan standar penumpukan vertikal <span class="hl-term text-[10px] px-1 py-0.5 border-none">Block</span> dengan pengaturan horizontal <span class="hl-term text-[10px] px-1 py-0.5 border-none">Flexbox</span>.</p>
                                </div>

                                <div class="flex flex-col lg:flex-row items-stretch gap-6 lg:gap-10 relative z-10">
                                    <div class="w-full lg:w-1/2 space-y-4 font-mono text-[11px] sm:text-sm flex flex-col bg-slate-50 dark:bg-[#18181b] p-5 rounded-lg border border-adaptive transition-colors">
                                        <div class="flex flex-col sm:flex-row justify-between items-center gap-3 sm:gap-0 pb-4 border-b border-adaptive shrink-0">
                                            <span class="text-slate-500 dark:text-white/50 font-bold uppercase text-[10px] tracking-widest">Konfigurasi Kontainer</span>
                                            <div class="flex gap-2">
                                                <button onclick="setSimFlex('block')" class="flex-1 sm:flex-none px-4 py-2 bg-slate-200 dark:bg-white/10 border border-slate-300 dark:border-white/20 rounded-lg text-slate-700 dark:text-gray-300 hover:bg-slate-300 dark:hover:bg-white/20 transition text-[11px] font-bold focus:outline-none focus:ring-2 focus:ring-slate-400">Block</button>
                                                <button onclick="setSimFlex('flex')" class="flex-1 sm:flex-none px-4 py-2 bg-indigo-100 dark:bg-indigo-900/30 border border-indigo-300 dark:border-indigo-700/50 text-indigo-700 dark:text-indigo-400 rounded-lg transition text-[11px] font-bold hover:bg-indigo-200 dark:hover:bg-indigo-800/40 focus:outline-none focus:ring-2 focus:ring-indigo-500">Flexbox</button>
                                            </div>
                                        </div>
                                        <div class="flex-1 overflow-x-auto text-indigo-600 dark:text-indigo-300 leading-loose whitespace-pre bg-white dark:bg-black/30 p-4 rounded-xl border border-adaptive shadow-inner dark:shadow-none custom-scrollbar">
&lt;div class="<span id="sim1-class" class="text-slate-500 dark:text-white/50 font-bold px-1 rounded transition-colors">block</span>"&gt;
  &lt;div&gt;Elemen 1&lt;/div&gt;
  &lt;div&gt;Elemen 2&lt;/div&gt;
  &lt;div&gt;Elemen 3&lt;/div&gt;
&lt;/div&gt;</div>
                                    </div>
                                    
                                    <div class="w-full lg:w-1/2 flex items-center justify-center p-6 bg-slate-100 dark:bg-[#0b0f19] rounded-xl border border-adaptive relative overflow-hidden transition-colors shadow-inner min-h-[250px]">
                                        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-10"></div>
                                        
                                        <div id="sim1-preview" class="block w-full border-2 border-dashed border-slate-300 dark:border-white/10 p-5 rounded-xl bg-white/50 dark:bg-white/5 transition-all duration-500 relative z-10 shadow-sm">
                                            <div class="bg-indigo-500 w-16 h-16 sm:w-20 sm:h-20 rounded-lg flex items-center justify-center font-black text-white mb-3 sim1-item shadow-md transition-all duration-300 text-lg">1</div>
                                            <div class="bg-purple-500 w-16 h-16 sm:w-20 sm:h-20 rounded-lg flex items-center justify-center font-black text-white mb-3 sim1-item shadow-md transition-all duration-300 text-lg">2</div>
                                            <div class="bg-pink-500 w-16 h-16 sm:w-20 sm:h-20 rounded-lg flex items-center justify-center font-black text-white mb-3 sim1-item shadow-md transition-all duration-300 text-lg">3</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-8 bg-gradient-to-r from-indigo-50 to-transparent dark:from-indigo-900/20 dark:to-transparent border-l-4 border-indigo-500 p-5 rounded-r-xl relative z-10 transition-colors">
                                    <h5 class="text-sm font-bold text-indigo-700 dark:text-indigo-400 mb-2 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> 
                                        Kesimpulan Mode Layar
                                    </h5>
                                    <p class="text-xs sm:text-sm text-slate-700 dark:text-slate-300 leading-relaxed text-justify m-0">
                                        Menambahkan kelas <span class="hl-term text-[11px] py-0.5">flex</span> pada elemen pembungkus akan menonaktifkan aturan penumpukan asli dokumen. Elemen di dalamnya akan mematuhi susunan sejajar menyamping, yang menjadi gerbang utama dalam penyusunan antarmuka modern.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 30: DIRECTION & WRAP --}}
                    <section id="section-30" class="lesson-section scroll-mt-32" data-lesson-id="30">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-purple-500 pl-6">
                                <span class="text-purple-600 dark:text-purple-400 font-mono text-xs uppercase tracking-widest transition-colors">Lesson 2.1.2</span>
                                <h2 class="text-4xl lg:text-5xl font-black text-heading leading-[1.1] transition-colors">
                                    Arah Sumbu & <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-500 to-pink-600 dark:from-purple-400 dark:to-pink-500">Wrapping</span>
                                </h2>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-heading flex items-center gap-2 transition-colors"><span class="w-6 h-6 rounded bg-purple-500 dark:bg-purple-600 flex items-center justify-center text-[10px] text-white">A</span> Menentukan Arah Elemen</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-lg leading-relaxed space-y-4 text-justify transition-colors">
                                    <p>Penempatan di dalam Flexbox dikendalikan oleh dua sumbu: <span class="hl-term">Sumbu Utama</span> dan <span class="hl-term">Sumbu Silang</span>. Sumbu Utama adalah jalur perlintasan tempat elemen berbaris, sedangkan Sumbu Silang adalah arah tegak lurus yang mengontrol ketinggiannya. Arah dari sumbu ini bisa Anda ubah sesuai kebutuhan.</p>
                                    <p>Secara bawaan, kontainer diatur ke arah <span class="hl-term">flex-row</span>, yang menata elemen dari kiri ke kanan. Jika Anda menggunakan perintah <span class="hl-term">flex-col</span>, maka barisan elemen tersebut akan diputar menumpuk ke bawah. Memahami orientasi sumbu ini sangat penting, karena semua perintah perataan jarak hanya akan mengikuti arah Sumbu Utama yang sedang aktif.</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-heading flex items-center gap-2 transition-colors"><span class="w-6 h-6 rounded bg-purple-500 dark:bg-purple-600 flex items-center justify-center text-[10px] text-white">B</span> Mencegah Pemadatan Elemen</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-lg leading-relaxed space-y-4 text-justify transition-colors">
                                    <p>Perilaku dasar Flexbox mencegah elemen turun baris dengan menerapkan aturan tertutup kelas <span class="hl-term">flex-nowrap</span>. Aturan ini akan memaksakan komponen untuk saling berhimpitan di satu baris memanjang, sekalipun ukuran layarnya menjadi sangat sempit. Pemaksaan ini sering merusak bentuk gambar atau membuat antarmuka meluber ke samping.</p>
                                    <p>Untuk mengatasi masalah layar sempit, Anda dapat menggunakan kelas <span class="hl-term">flex-wrap</span>. Perintah ini mengizinkan komponen untuk turun ke baris baru ketika baris sebelumnya sudah tidak memiliki ruang. Kemampuan adaptasi inilah yang menjadi kunci utama saat membuat desain yang ramah seluler seperti galeri foto atau deretan kartu informasi.</p>
                                </div>
                            </div>

                            {{-- SIMULATOR 2 --}}
                            <div class="sim-bg-adaptive border border-adaptive rounded-xl overflow-hidden shadow-xl dark:shadow-2xl p-6 lg:p-8 relative transition-colors mt-8">
                                <h4 class="text-xs font-bold text-muted uppercase mb-4 text-center transition-colors">Simulator: Arah & Baris</h4>
                                
                                <div class="bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-500/30 rounded-lg p-4 mb-8 text-sm text-purple-800 dark:text-purple-300 relative z-10 transition-colors">
                                    <p class="font-bold flex items-center gap-2 mb-2">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Panduan Simulasi Baris
                                    </p>
                                    <p class="m-0 opacity-90 leading-relaxed">Gunakan panel kendali untuk merubah Sumbu Utama dengan tombol orientasi, lalu nyalakan fitur <span class="hl-term text-[10px] px-1 py-0.5 border-none">flex-wrap</span> untuk membebaskan elemen dari pemadatan di layar sempit.</p>
                                </div>

                                <div class="flex flex-col lg:flex-row items-stretch gap-6 lg:gap-10 relative z-10">
                                    <div class="w-full lg:w-1/2 space-y-4 font-mono text-[11px] sm:text-sm flex flex-col bg-slate-50 dark:bg-[#18181b] p-5 rounded-lg border border-adaptive transition-colors">
                                        <div class="mb-2">
                                            <label class="text-[10px] sm:text-[11px] text-indigo-600 dark:text-indigo-400 block mb-3 font-bold transition-colors uppercase tracking-wider">Penunjuk Arah Sumbu</label>
                                            <div class="flex gap-2">
                                                <button onclick="updateSimDir('flex-row')" class="flex-1 py-3 bg-white dark:bg-black/30 border border-slate-300 dark:border-white/10 rounded-lg text-[11px] sm:text-xs hover:bg-indigo-50 dark:hover:bg-indigo-600/20 transition text-slate-700 dark:text-gray-300 font-bold shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">flex-row</button>
                                                <button onclick="updateSimDir('flex-col')" class="flex-1 py-3 bg-white dark:bg-black/30 border border-slate-300 dark:border-white/10 rounded-lg text-[11px] sm:text-xs hover:bg-indigo-50 dark:hover:bg-indigo-600/20 transition text-slate-700 dark:text-gray-300 font-bold shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">flex-col</button>
                                            </div>
                                        </div>
                                        <div class="mt-4">
                                            <label class="text-[10px] sm:text-[11px] text-purple-600 dark:text-purple-400 block mb-3 font-bold transition-colors uppercase tracking-wider">Izin Turun Baris</label>
                                            <button onclick="updateSimDir('flex-wrap')" class="w-full py-3.5 bg-purple-100 dark:bg-purple-900/30 border border-purple-300 dark:border-purple-700/50 rounded-xl text-[11px] sm:text-xs hover:bg-purple-200 dark:hover:bg-purple-800/40 transition text-purple-800 dark:text-purple-300 font-bold shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 flex justify-center items-center gap-2 group">
                                                <svg class="w-5 h-5 text-purple-600 dark:text-purple-400 group-hover:animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                                Nyalakan Fitur Wrap
                                            </button>
                                        </div>
                                        <div class="bg-white dark:bg-black/40 p-4 rounded-xl border border-adaptive font-mono text-[11px] sm:text-sm text-slate-500 dark:text-gray-400 mt-4 shadow-inner dark:shadow-none transition-colors overflow-x-auto custom-scrollbar flex-1">
                                            &lt;div class="flex <span id="sim2-code" class="text-indigo-600 dark:text-indigo-400 font-bold bg-indigo-50 dark:bg-indigo-900/30 px-1 py-0.5 rounded transition-colors">flex-row</span> gap-3"&gt;...&lt;/div&gt;
                                        </div>
                                    </div>
                                    
                                    <div class="w-full lg:w-1/2 flex flex-col items-center justify-center p-6 bg-slate-100 dark:bg-[#0b0f19] rounded-xl border border-adaptive relative overflow-hidden transition-colors shadow-inner min-h-[300px]">
                                        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-10 pointer-events-none"></div>
                                        <div class="absolute top-4 left-4 px-3 py-1.5 bg-white/80 dark:bg-black/50 border border-slate-200 dark:border-white/10 backdrop-blur rounded text-[9px] font-bold text-slate-500 dark:text-white/40 uppercase tracking-widest shadow-sm z-20">Simulasi Lebar Layar Sempit</div>
                                        
                                        <div id="sim2-target" class="flex flex-row gap-2 sm:gap-3 transition-all duration-500 border-2 border-dashed border-slate-400 dark:border-white/20 p-3 sm:p-4 rounded-xl w-full max-w-[280px] bg-white/50 dark:bg-transparent shadow-sm dark:shadow-none relative z-10 overflow-hidden mt-6">
                                            <div class="w-14 h-12 sm:w-16 sm:h-14 bg-purple-500 rounded-lg flex items-center justify-center font-black text-white text-sm sm:text-base shrink-0 shadow-md transition-all duration-300">1</div>
                                            <div class="w-14 h-12 sm:w-16 sm:h-14 bg-indigo-500 rounded-lg flex items-center justify-center font-black text-white text-sm sm:text-base shrink-0 shadow-md transition-all duration-300">2</div>
                                            <div class="w-14 h-12 sm:w-16 sm:h-14 bg-fuchsia-500 rounded-lg flex items-center justify-center font-black text-white text-sm sm:text-base shrink-0 shadow-md transition-all duration-300">3</div>
                                            <div class="w-14 h-12 sm:w-16 sm:h-14 bg-pink-500 rounded-lg flex items-center justify-center font-black text-white text-sm sm:text-base shrink-0 shadow-md transition-all duration-300">4</div>
                                            <div class="w-14 h-12 sm:w-16 sm:h-14 bg-rose-500 rounded-lg flex items-center justify-center font-black text-white text-sm sm:text-base shrink-0 shadow-md transition-all duration-300">5</div>
                                            <div class="w-14 h-12 sm:w-16 sm:h-14 bg-orange-500 rounded-lg flex items-center justify-center font-black text-white text-sm sm:text-base shrink-0 shadow-md transition-all duration-300">6</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-8 bg-gradient-to-r from-purple-50 to-transparent dark:from-purple-900/20 dark:to-transparent border-l-4 border-purple-500 p-5 rounded-r-xl relative z-10 transition-colors">
                                    <h5 class="text-sm font-bold text-purple-700 dark:text-purple-400 mb-2 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> 
                                        Catatan Hasil Akhir
                                    </h5>
                                    <p class="text-xs sm:text-sm text-slate-700 dark:text-slate-300 leading-relaxed text-justify m-0">
                                        Kelas wrap memastikan terbentuknya baris baru apabila elemen sudah tidak memiliki ruang tersisa. Perilaku responsif ini menghindarkan antarmuka situs Anda dari kerusakan dan kepadatan yang tidak wajar.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 31: ALIGNMENT --}}
                    <section id="section-31" class="lesson-section scroll-mt-32" data-lesson-id="31">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-fuchsia-500 pl-6">
                                <span class="text-fuchsia-600 dark:text-fuchsia-400 font-mono text-xs uppercase tracking-widest transition-colors">Lesson 2.1.3</span>
                                <h2 class="text-4xl lg:text-5xl font-black text-heading leading-[1.1] transition-colors">
                                    Justify & <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-fuchsia-500 to-pink-600 dark:from-fuchsia-400 dark:to-pink-500">Alignment</span>
                                </h2>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-heading flex items-center gap-2 transition-colors"><span class="w-6 h-6 rounded bg-fuchsia-500 dark:bg-fuchsia-600 flex items-center justify-center text-[10px] text-white">A</span> Membagikan Ruang Kosong (Justify Content)</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-lg leading-relaxed space-y-4 text-justify transition-colors">
                                    <p>Kualitas sebuah antarmuka sangat dipengaruhi oleh bagaimana Anda mengelola jarak negatif. Keluarga kelas yang diawali dengan kata justify bertugas mengatur distribusi ruang kosong yang tersisa di sepanjang jalur <span class="hl-term">Sumbu Utama</span>.</p>
                                    
                                    <div class="bg-fuchsia-50 dark:bg-fuchsia-900/10 p-6 rounded-xl border border-fuchsia-100 dark:border-fuchsia-800/30 my-6 shadow-inner dark:shadow-none transition-colors">
                                        <p class="font-bold text-fuchsia-800 dark:text-fuchsia-300 mb-4 uppercase tracking-wide text-xs">Pilihan Kelas Justify Utama:</p>
                                        <ul class="space-y-4 list-none pl-0 m-0 text-sm">
                                            <li class="flex gap-4"><span class="font-mono font-bold text-slate-800 dark:text-white min-w-[140px] bg-white dark:bg-black/30 px-2 py-1 rounded border border-adaptive text-center">justify-start</span> <span class="opacity-80 leading-relaxed text-justify">Aturan dasar mesin yang akan mengumpulkan semua elemen agar menempel rapat ke sudut awal tanpa menyisakan ruang jeda.</span></li>
                                            <li class="flex gap-4"><span class="font-mono font-bold text-slate-800 dark:text-white min-w-[140px] bg-white dark:bg-black/30 px-2 py-1 rounded border border-adaptive text-center">justify-center</span> <span class="opacity-80 leading-relaxed text-justify">Merapatkan seluruh kelompok elemen tepat di tengah layar, dan menyisakan jarak udara yang berimbang di kedua sisi luarnya.</span></li>
                                            <li class="flex gap-4"><span class="font-mono font-bold text-fuchsia-600 dark:text-fuchsia-400 min-w-[140px] bg-white dark:bg-black/30 px-2 py-1 rounded border border-fuchsia-300 dark:border-fuchsia-500/50 shadow-sm text-center">justify-between</span> <span class="opacity-80 leading-relaxed text-justify">Pilihan paling sering dipakai. Mengunci elemen pertama ke batas kiri, elemen terakhir ke batas kanan, dan membagi sisa ruang secara adil untuk elemen yang berada di bagian tengah.</span></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-heading flex items-center gap-2 transition-colors"><span class="w-6 h-6 rounded bg-fuchsia-500 dark:bg-fuchsia-600 flex items-center justify-center text-[10px] text-white">B</span> Menyelaraskan Ketinggian (Align Items)</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-lg leading-relaxed space-y-4 text-justify transition-colors">
                                    <p>Berbeda dengan arah sumbu utama, sekumpulan perintah kelas yang menggunakan kata items bertugas menjaga keseimbangan titik vertikal elemen pada jalur <span class="hl-term">Sumbu Silang</span>.</p>
                                    <p>Perintah standarnya adalah kelas <code class="font-bold">items-stretch</code>, yang akan memaksa seluruh elemen untuk memanjangkan badannya agar memiliki ketinggian yang sama, meniru ketinggian objek paling tinggi yang ada di baris tersebut.</p>
                                    <p>Sebagai solusi penyeimbang, kelas <code class="font-bold text-fuchsia-600 dark:text-fuchsia-400">items-center</code> sangat berguna untuk mensejajarkan elemen berukuran berbeda tanpa mengubah bentuk aslinya. Objek akan diseret dan diposisikan agar titik tengahnya sejajar membentuk satu garis lurus horizontal. Ini sangat memudahkan Anda saat ingin menyejajarkan teks paragraf dengan sebuah gambar logo yang memiliki ukuran tinggi yang lebih besar.</p>
                                </div>
                            </div>

                            {{-- SIMULATOR 3 --}}
                            <div class="sim-bg-adaptive border border-adaptive rounded-xl overflow-hidden shadow-xl dark:shadow-2xl p-6 lg:p-8 relative transition-colors mt-8">
                                <h4 class="text-xs font-bold text-muted uppercase mb-4 text-center transition-colors">Simulator: Distribusi & Penyelarasan</h4>
                                
                                <div class="bg-fuchsia-50 dark:bg-fuchsia-900/20 border border-fuchsia-200 dark:border-fuchsia-500/30 rounded-lg p-4 mb-8 text-sm text-fuchsia-800 dark:text-fuchsia-300 relative z-10 transition-colors">
                                    <p class="font-bold flex items-center gap-2 mb-2">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Uji Coba Penyelarasan
                                    </p>
                                    <p class="m-0 opacity-90 leading-relaxed">Gunakan tombol <span class="hl-term text-[10px] px-1 py-0.5 border-none">Justify</span> untuk memanipulasi distribusi ruang melebar, dan pilih kelas <span class="hl-term text-[10px] px-1 py-0.5 border-none">Items</span> untuk mengatur keseimbangan posisi ketinggian elemen di sebelah kanan.</p>
                                </div>

                                <div class="flex flex-col lg:flex-row items-stretch gap-6 lg:gap-10 relative z-10">
                                    <div class="w-full lg:w-1/2 p-5 font-mono text-[11px] sm:text-sm flex flex-col bg-slate-50 dark:bg-[#18181b] rounded-lg border border-adaptive transition-colors">
                                        <div class="mb-6">
                                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-3 gap-2">
                                                <label class="text-[10px] text-fuchsia-600 dark:text-fuchsia-400 font-bold uppercase tracking-wider transition-colors">Pengatur Ruang Horizontal</label>
                                                <span class="text-[10px] text-slate-700 dark:text-white font-mono bg-slate-200 dark:bg-white/10 px-2.5 py-1 rounded font-bold shadow-sm border border-slate-300 dark:border-white/10 transition-colors" id="sim3-label-j">justify-start</span>
                                            </div>
                                            <div class="grid grid-cols-2 gap-2 sm:flex sm:flex-wrap">
                                                <button onclick="updateSimAlign('j', 'justify-start')" class="sm:flex-1 px-3 py-2 bg-white dark:bg-black/30 rounded-lg text-[11px] hover:bg-fuchsia-50 dark:hover:bg-fuchsia-600/30 transition border border-slate-300 dark:border-white/10 font-bold text-slate-700 dark:text-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-500/50">Start</button>
                                                <button onclick="updateSimAlign('j', 'justify-center')" class="sm:flex-1 px-3 py-2 bg-white dark:bg-black/30 rounded-lg text-[11px] hover:bg-fuchsia-50 dark:hover:bg-fuchsia-600/30 transition border border-slate-300 dark:border-white/10 font-bold text-slate-700 dark:text-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-500/50">Center</button>
                                                <button onclick="updateSimAlign('j', 'justify-between')" class="sm:flex-1 px-3 py-2 bg-white dark:bg-black/30 rounded-lg text-[11px] hover:bg-fuchsia-50 dark:hover:bg-fuchsia-600/30 transition border border-slate-300 dark:border-white/10 font-bold text-slate-700 dark:text-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-500/50">Between</button>
                                                <button onclick="updateSimAlign('j', 'justify-end')" class="sm:flex-1 px-3 py-2 bg-white dark:bg-black/30 rounded-lg text-[11px] hover:bg-fuchsia-50 dark:hover:bg-fuchsia-600/30 transition border border-slate-300 dark:border-white/10 font-bold text-slate-700 dark:text-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-500/50">End</button>
                                            </div>
                                        </div>
                                        <div class="mt-auto">
                                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-3 gap-2">
                                                <label class="text-[10px] text-purple-600 dark:text-purple-400 font-bold uppercase tracking-wider transition-colors">Penyeimbang Ketinggian</label>
                                                <span class="text-[10px] text-slate-700 dark:text-white font-mono bg-slate-200 dark:bg-white/10 px-2.5 py-1 rounded font-bold shadow-sm border border-slate-300 dark:border-white/10 transition-colors" id="sim3-label-i">items-start</span>
                                            </div>
                                            <div class="grid grid-cols-2 gap-2 sm:flex sm:flex-wrap">
                                                <button onclick="updateSimAlign('i', 'items-start')" class="sm:flex-1 px-3 py-2 bg-white dark:bg-black/30 rounded-lg text-[11px] hover:bg-purple-50 dark:hover:bg-purple-600/30 transition border border-slate-300 dark:border-white/10 font-bold text-slate-700 dark:text-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500/50">Start</button>
                                                <button onclick="updateSimAlign('i', 'items-center')" class="sm:flex-1 px-3 py-2 bg-white dark:bg-black/30 rounded-lg text-[11px] hover:bg-purple-50 dark:hover:bg-purple-600/30 transition border border-slate-300 dark:border-white/10 font-bold text-slate-700 dark:text-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500/50">Center</button>
                                                <button onclick="updateSimAlign('i', 'items-end')" class="sm:flex-1 px-3 py-2 bg-white dark:bg-black/30 rounded-lg text-[11px] hover:bg-purple-50 dark:hover:bg-purple-600/30 transition border border-slate-300 dark:border-white/10 font-bold text-slate-700 dark:text-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500/50">End</button>
                                                <button onclick="updateSimAlign('i', 'items-stretch')" class="sm:flex-1 px-3 py-2 bg-white dark:bg-black/30 rounded-lg text-[11px] hover:bg-purple-50 dark:hover:bg-purple-600/30 transition border border-slate-300 dark:border-white/10 font-bold text-slate-700 dark:text-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500/50">Stretch</button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="w-full lg:w-1/2 flex items-center justify-center bg-slate-100 dark:bg-[#0b0f19] rounded-xl border border-adaptive relative overflow-hidden transition-colors shadow-inner min-h-[300px] p-6">
                                        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-10"></div>
                                        <div class="bg-white dark:bg-[#1e1e1e] p-4 md:p-6 rounded-2xl border border-adaptive w-full flex justify-start items-start h-48 md:h-56 relative z-10 transition-all duration-300 gap-3 shadow-lg" id="sim3-target">
                                            <div class="bg-fuchsia-500 w-12 md:w-16 h-16 md:h-20 rounded-lg flex items-center justify-center font-black text-white shadow-md text-lg shrink-0 transition-all duration-300 border border-fuchsia-400">A</div>
                                            <div class="bg-purple-500 w-12 md:w-16 h-28 md:h-32 rounded-lg flex items-center justify-center font-black text-white shadow-md text-lg shrink-0 transition-all duration-300 border border-purple-400">B</div>
                                            <div class="bg-indigo-500 w-12 md:w-16 h-12 md:h-16 rounded-lg flex items-center justify-center font-black text-white shadow-md text-lg shrink-0 transition-all duration-300 border border-indigo-400">C</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-8 bg-gradient-to-r from-fuchsia-50 to-transparent dark:from-fuchsia-900/20 dark:to-transparent border-l-4 border-fuchsia-500 p-5 rounded-r-xl relative z-10 transition-colors">
                                    <h5 class="text-sm font-bold text-fuchsia-700 dark:text-fuchsia-400 mb-2 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> 
                                        Catatan Penyusunan
                                    </h5>
                                    <p class="text-xs sm:text-sm text-slate-700 dark:text-slate-300 leading-relaxed text-justify m-0">
                                        Perpaduan kelas perataan jarak dan keseimbangan ketinggian membantu Anda membuat barisan komponen terlihat rapi dan proporsional tanpa harus bersusah payah mengatur angka margin secara manual.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 32: SIZING --}}
                    <section id="section-32" class="lesson-section scroll-mt-32" data-lesson-id="32">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-pink-500 pl-6">
                                <span class="text-pink-600 dark:text-pink-400 font-mono text-xs uppercase tracking-widest transition-colors">Lesson 2.1.4</span>
                                <h2 class="text-4xl lg:text-5xl font-black text-heading leading-[1.1] transition-colors">
                                    Sizing & <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-pink-500 to-rose-600 dark:from-pink-400 dark:to-rose-500">Flexibility</span>
                                </h2>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-heading flex items-center gap-2 transition-colors"><span class="w-6 h-6 rounded bg-pink-500 dark:bg-pink-600 flex items-center justify-center text-[10px] text-white">A</span> Algoritma Pemuaian Area (Flex-1)</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-lg leading-relaxed space-y-4 text-justify transition-colors">
                                    <p>Saat merancang antarmuka aplikasi modern, Anda pasti akan sering membuat tata letak yang menggabungkan komponen berukuran tetap (seperti menu navigasi di pinggir layar) dengan komponen halaman yang fleksibel (seperti area konten utama yang bisa melebar bebas).</p>
                                    <p>Kemampuan untuk mengambil sisa luasan layar secara responsif ini diatur oleh utilitas <span class="hl-term">flex-1</span>. Kelas ini memberikan izin pada elemen untuk memanjang secara otomatis dan memakan semua sisa ruang kosong yang belum terpakai di Sumbu Utama. Hebatnya, area yang melebar ini tidak akan pernah merusak batas lebar dari komponen tetap yang berada di sebelahnya.</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-heading flex items-center gap-2 transition-colors"><span class="w-6 h-6 rounded bg-pink-500 dark:bg-pink-600 flex items-center justify-center text-[10px] text-white">B</span> Menolak Pemadatan Paksa (Shrink-0)</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-lg leading-relaxed space-y-4 text-justify transition-colors">
                                    <p>Masalah umum yang kerap terjadi adalah gambar atau logo menjadi mengecil gepeng (squishing) saat pengguna mengecilkan ukuran jendela browser mereka. Hal ini terjadi karena sistem bawaan peramban akan selalu berusaha memeras ukuran elemen agar semuanya dapat masuk ke dalam area pandang.</p>
                                    <p>Untuk mencegah objek penyusun desain Anda mengecil dengan cara yang tidak etis, Anda bisa mengamankannya dengan perlindungan kelas <span class="hl-term text-[11px] px-1 py-0.5">shrink-0</span>. Aturan ini sangat ampuh dalam melindungi proporsi asli gambar atau penampang statis agar kebal dari kompresi paksa ketika layar ditarik menyempit.</p>
                                </div>
                            </div>

                            {{-- SIMULATOR 4 --}}
                            <div class="sim-bg-adaptive border border-adaptive rounded-xl overflow-hidden shadow-xl dark:shadow-2xl p-6 lg:p-8 relative transition-colors mt-8">
                                <h4 class="text-xs font-bold text-muted uppercase mb-4 text-center transition-colors">Simulator: Uji Coba Responsivitas</h4>
                                
                                <div class="bg-pink-50 dark:bg-pink-900/20 border border-pink-200 dark:border-pink-500/30 rounded-lg p-4 mb-8 text-sm text-pink-800 dark:text-pink-300 relative z-10 transition-colors">
                                    <p class="font-bold flex items-center gap-2 mb-2">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                        Interaksi Kursor
                                    </p>
                                    <p class="m-0 opacity-90 leading-relaxed text-justify">Arahkan kursor (<span class="hl-term text-[10px] px-1 py-0.5 border-none">Hover</span>) ke area kotak biru di bawah untuk menyimulasikan pemuaian ukuran dinamis. Lihat bagaimana bagian Sidebar statis tetap aman dan tidak menyusut.</p>
                                </div>

                                <div class="p-6 md:p-10 flex flex-col items-center justify-center relative z-10 bg-slate-50 dark:bg-[#0b0f19] rounded-xl border border-adaptive shadow-inner transition-colors">
                                    <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-10 pointer-events-none mix-blend-overlay"></div>
                                    
                                    <div class="flex gap-2 w-full max-w-3xl bg-slate-200 dark:bg-black/50 p-3 md:p-4 rounded-xl border border-slate-300 dark:border-white/10 transition-colors shadow-inner h-40 md:h-48 relative group/canvas z-10">
                                        <div class="w-24 md:w-40 h-full bg-gradient-to-b from-purple-500 to-purple-600 rounded-lg flex flex-col items-center justify-center text-[10px] md:text-sm text-white font-bold flex-none shadow-md text-center border border-white/20 transition-all duration-300 shrink-0 p-2 md:p-3 relative z-10">
                                            Sidebar Statis<br>
                                            <span class="font-mono font-normal opacity-90 mt-2 block text-[8px] md:text-[11px] bg-black/20 p-2 rounded w-full border border-white/10 leading-tight">
                                                <span class="text-pink-300 font-bold block mb-1">flex-none</span>
                                                <span class="text-pink-300 font-bold block">shrink-0</span>
                                            </span>
                                        </div>
                                        
                                        <div class="h-full bg-gradient-to-b from-indigo-500 to-indigo-600 rounded-lg flex flex-col items-center justify-center text-sm md:text-base text-white font-bold flex-1 transition-all duration-[800ms] hover:bg-indigo-400 shadow-md cursor-crosshair group text-center border border-white/20 p-2 relative overflow-hidden group-hover/canvas:flex-[0.3] hover:!flex-[2]">
                                            <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 pointer-events-none mix-blend-overlay"></div>
                                            <span class="mb-1 relative z-10 drop-shadow-md">Area Konten yang Fleksibel</span>
                                            
                                            <span class="font-mono font-normal opacity-90 text-[10px] md:text-xs relative z-10 bg-black/20 px-2 py-1 rounded border border-white/10 mt-2">
                                                <span class="text-cyan-300 font-bold">flex-1</span>
                                            </span>
                                            
                                            <div class="hidden sm:flex group-hover:flex items-center gap-2 mt-4 opacity-90 transition-opacity relative z-10 bg-white/10 px-3 py-1.5 rounded-full border border-white/20 backdrop-blur-sm">
                                                <svg class="w-4 h-4 animate-pulse text-cyan-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                                                <span class="text-[10px] tracking-widest uppercase text-cyan-100 font-bold">Gerakan Pemuaian Luas</span>
                                                <svg class="w-4 h-4 animate-pulse text-cyan-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m-7 7H3"/></svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-8 bg-gradient-to-r from-pink-50 to-transparent dark:from-pink-900/20 dark:to-transparent border-l-4 border-pink-500 p-5 rounded-r-xl relative z-10 transition-colors">
                                    <h5 class="text-xs sm:text-sm font-bold text-pink-700 dark:text-pink-400 mb-2 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> 
                                        Catatan Kesimpulan
                                    </h5>
                                    <p class="text-[11px] sm:text-xs text-slate-700 dark:text-slate-300 leading-relaxed text-justify m-0">
                                        Perintah kelas penolak kompresi akan menjamin ukuran elemen navigasi tetap terjaga. Di saat bersamaan, area halaman di sebelahnya dibebaskan merenggang secara leluasa mengikuti ruang layar yang ada berkat perintah kelenturan.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 33: ACTIVITY FINAL EXPERT --}}
                    <section id="section-33" class="lesson-section scroll-mt-32 pt-10 border-t border-adaptive transition-colors" data-lesson-id="33" data-type="activity">
                        <div class="relative rounded-[2.5rem] sim-bg-adaptive border border-adaptive p-6 md:p-10 overflow-hidden shadow-xl dark:shadow-2xl group hover:border-indigo-500/30 transition-all duration-500">
                            
                            <div class="absolute -top-24 -right-24 w-64 h-64 bg-indigo-600/10 dark:bg-indigo-600/20 blur-[100px] rounded-full pointer-events-none transition-colors"></div>

                            <div class="flex flex-col md:flex-row items-start md:items-center gap-6 mb-8 relative z-10">
                                <div class="p-4 bg-gradient-to-br from-indigo-600 to-purple-800 rounded-2xl text-white shadow-lg shadow-indigo-500/20 shrink-0">
                                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                                </div>
                                <div>
                                    <div class="flex items-center gap-3 mb-2">
                                        <h2 class="text-2xl lg:text-3xl font-black text-heading tracking-tight transition-colors">Final Mission: Fix the Navbar</h2>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 dark:bg-rose-500/20 text-rose-700 dark:text-rose-400 border border-rose-200 dark:border-rose-500/30 uppercase tracking-wider transition-colors">Real-world Case</span>
                                    </div>
                                    <p class="text-slate-600 dark:text-indigo-200/60 text-sm leading-relaxed max-w-2xl transition-colors text-justify">
                                        Sebuah tata letak Navbar hancur. Tugas Anda: Buat elemen sejajar menyamping secara merata, pastikan kotak pencarian merenggang elastis mengisi ruang kosong, dan kunci ikon profil agar tidak gepeng saat layar menyempit.
                                    </p>
                                    
                                    {{-- TOOLBOX CLUE --}}
                                    <div class="mt-4 flex flex-wrap items-center gap-2">
                                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mr-1">Petunjuk Kelas:</span>
                                        <code class="text-xs font-mono font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-500/10 border border-indigo-200 dark:border-indigo-500/20 px-2 py-1 rounded shadow-sm">flex</code>
                                        <code class="text-xs font-mono font-bold text-fuchsia-600 dark:text-fuchsia-400 bg-fuchsia-50 dark:bg-fuchsia-500/10 border border-fuchsia-200 dark:border-fuchsia-500/20 px-2 py-1 rounded shadow-sm">justify-between</code>
                                        <code class="text-xs font-mono font-bold text-purple-600 dark:text-purple-400 bg-purple-50 dark:bg-purple-500/10 border border-purple-200 dark:border-purple-500/20 px-2 py-1 rounded shadow-sm">items-center</code>
                                        <code class="text-xs font-mono font-bold text-cyan-600 dark:text-cyan-400 bg-cyan-50 dark:bg-cyan-500/10 border border-cyan-200 dark:border-cyan-500/20 px-2 py-1 rounded shadow-sm">flex-1</code>
                                        <code class="text-xs font-mono font-bold text-pink-600 dark:text-pink-400 bg-pink-50 dark:bg-pink-500/10 border border-pink-200 dark:border-pink-500/20 px-2 py-1 rounded shadow-sm">shrink-0</code>
                                        <code class="text-xs font-mono font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 px-2 py-1 rounded shadow-sm">p-6</code>
                                    </div>
                                </div>
                            </div>

                            <div class="grid xl:grid-cols-2 gap-0 border border-adaptive rounded-2xl overflow-hidden min-h-[550px] shadow-2xl transition-colors relative z-10">
                                
                                {{-- EDITOR KIRI --}}
                                <div class="bg-slate-50 dark:bg-[#151515] border-r border-adaptive overflow-hidden relative flex flex-col transition-colors min-h-[550px]">
                                    
                                    {{-- LOCK OVERLAY (Muncul saat sukses) --}}
                                    <div id="lockOverlay" class="hidden absolute inset-0 bg-white/90 dark:bg-[#050912]/95 backdrop-blur-md z-50 flex flex-col items-center justify-center text-center p-8 transition-colors">
                                        <div class="w-24 h-24 bg-emerald-100 dark:bg-emerald-500/10 rounded-full flex items-center justify-center mb-6 border border-emerald-300 dark:border-emerald-500/50 shadow-[0_0_50px_rgba(16,185,129,0.2)] animate-bounce transition-colors">
                                            <svg class="w-12 h-12 text-emerald-600 dark:text-emerald-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                        </div>
                                        <h3 class="text-3xl font-black text-slate-900 dark:text-white mb-2 tracking-tight transition-colors">MISI SELESAI!</h3>
                                        <p class="text-sm font-bold text-slate-500 dark:text-white/60 mb-8 max-w-xs transition-colors">Tata letak responsif telah berhasil diimplementasikan.</p>
                                        <button disabled class="w-full sm:w-auto px-6 sm:px-8 py-3 rounded-full bg-slate-200 dark:bg-white/5 border border-slate-300 dark:border-white/10 text-slate-500 dark:text-white/30 text-xs font-bold cursor-not-allowed uppercase tracking-widest transition-colors">Terminal Terkunci</button>
                                    </div>

                                    <div class="bg-slate-200 dark:bg-[#0f141e] px-4 py-2 border-b border-adaptive flex justify-between items-center transition-colors">
                                        <span class="text-xs text-slate-600 dark:text-white/50 font-mono font-bold transition-colors">Terminal Engineer</span>
                                        <button onclick="resetEditor()" class="text-[10px] text-red-500 dark:text-red-400 hover:text-red-600 dark:hover:text-red-300 transition-colors uppercase font-bold focus:outline-none bg-white dark:bg-red-500/10 px-3 py-1.5 rounded shadow-sm border border-red-200 dark:border-red-500/30 active:scale-95">Reset Ulang</button>
                                    </div>
                                    
                                    {{-- Editor Container --}}
                                    <div id="codeEditor" class="h-[300px] flex-1 w-full border-b border-adaptive transition-colors"></div>

                                    {{-- CLUES AREA DENGAN INSTRUKSI KONTEKSTUAL --}}
                                    <div class="p-5 bg-slate-50 dark:bg-[#0f141e] transition-colors flex flex-col shrink-0 h-[220px]">
                                        <div class="flex justify-between items-center mb-3">
                                            <span class="text-[10px] uppercase font-bold text-muted transition-colors tracking-widest">Status Pemeriksaan</span>
                                            <span id="progressText" class="text-[10px] font-mono text-indigo-600 dark:text-indigo-400 bg-indigo-100 dark:bg-indigo-900/30 px-2 py-0.5 rounded font-bold transition-colors">0/4 Selesai</span>
                                        </div>
                                        <div class="space-y-2 text-[11px] font-mono text-slate-600 dark:text-white/50 mb-4 transition-colors overflow-y-auto custom-scrollbar flex-1 bg-white dark:bg-black/20 rounded-lg shadow-inner border border-adaptive p-3">
                                            <div id="check-main" class="flex items-center gap-3 transition-colors"><span class="w-3 h-3 rounded-full border border-slate-300 dark:border-white/20 flex items-center justify-center text-[8px] transition-colors shrink-0"></span> Aktifkan flex & beri p-6 (#main-nav)</div>
                                            <div id="check-align" class="flex items-center gap-3 transition-colors"><span class="w-3 h-3 rounded-full border border-slate-300 dark:border-white/20 flex items-center justify-center text-[8px] transition-colors shrink-0"></span> Ratakan vertikal & horizontal (#main-nav)</div>
                                            <div id="check-flex-1" class="flex items-center gap-3 transition-colors"><span class="w-3 h-3 rounded-full border border-slate-300 dark:border-white/20 flex items-center justify-center text-[8px] transition-colors shrink-0"></span> Buat elemen ini melar elastis (#search-box)</div>
                                            <div id="check-shrink" class="flex items-center gap-3 transition-colors"><span class="w-3 h-3 rounded-full border border-slate-300 dark:border-white/20 flex items-center justify-center text-[8px] transition-colors shrink-0"></span> Kunci ukuran agar tak gepeng (#profile-group)</div>
                                        </div>

                                        <button id="submitExerciseBtn" onclick="submitExercise()" disabled class="w-full py-3 rounded-lg bg-emerald-600 text-white font-bold text-xs shadow-lg shadow-emerald-500/20 hover:bg-emerald-500 transition-all cursor-not-allowed opacity-50 flex items-center justify-center gap-2 shrink-0 outline-none active:scale-95">
                                            <span>Selesaikan Semua Syarat Dulu</span>
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                        </button>
                                    </div>
                                </div>

                                {{-- RIGHT PANEL: VISUAL RENDER ZONE --}}
                                <div class="bg-white dark:bg-[#1e1e1e] flex flex-col relative overflow-hidden shadow-sm dark:shadow-none transition-colors w-full xl:w-auto h-[400px] xl:h-auto">
                                    <div class="bg-slate-100 dark:bg-[#2d2d2d] px-4 py-2 border-b border-adaptive flex items-center justify-between transition-colors shrink-0">
                                        <span class="text-[10px] text-slate-500 dark:text-gray-400 font-mono font-bold transition-colors">Live Preview</span>
                                        <span class="text-[10px] bg-emerald-100 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 px-2 py-0.5 rounded border border-emerald-200 dark:border-emerald-500/20 font-bold transition-colors flex items-center gap-1 uppercase tracking-widest shadow-sm">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Auto-Sync
                                        </span>
                                    </div>
                                    <div class="flex-1 bg-slate-50 dark:bg-[#020617] flex items-start justify-center relative overflow-hidden transition-colors w-full h-full">
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
                            <div id="nextLabel" class="text-[10px] uppercase tracking-widest font-bold opacity-60">Tugas Belum Selesai</div>
                            <div class="font-black text-sm">Sistem Penempatan Tingkat Lanjut</div>
                        </div>
                        <div id="nextIcon" class="w-12 h-12 rounded-full border border-adaptive flex items-center justify-center bg-slate-100 dark:bg-white/5 transition-colors shadow-sm dark:shadow-none">
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
    window.LESSON_IDS = [29, 30, 31, 32, 33]; 
    let rawCompletedIds = {!! json_encode($completedLessonIds ?? []) !!};
    window.COMPLETED_IDS = rawCompletedIds.map(id => Number(id)); 
    let completedSet = new Set(window.COMPLETED_IDS);
    
    // Config Aktivitas
    const ACTIVITY_LESSON_ID = 33; 
    let activityCompleted = completedSet.has(ACTIVITY_LESSON_ID);

    /* --- 2. INITIALIZATION --- */
    document.addEventListener('DOMContentLoaded', () => {
    initSidebarScroll();
    initVisualEffects();
    
    // Render Progress Bar awal
    updateProgressUI(false); 
    
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
                    dot.outerHTML = `<svg class="w-4 h-4 text-indigo-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>`;
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
    const starterCode = `<nav class="bg-slate-900 text-white w-full border-b border-slate-700">
  
  <div id="main-nav" class="bg-slate-800/50">

    <div class="font-black text-2xl text-fuchsia-500">DevSpace.</div>

    <div id="search-box" class="bg-slate-950 border border-slate-700 rounded px-4 py-2 mx-4 text-slate-400">
      Cari modul...
    </div>

    <div id="profile-group" class="flex items-center gap-3">
      <span class="hidden sm:block text-sm font-bold">Halo, Developer</span>
      <div class="w-10 h-10 rounded-full border-2 border-fuchsia-500 bg-fuchsia-900/50"></div>
    </div>

  </div>
</nav>`;

    function initMonaco() {
    require.config({ paths: { vs: 'https://cdn.jsdelivr.net/npm/monaco-editor@0.45.0/min/vs' } });
    require(['vs/editor/editor.main'], function () {
        
        const isDark = document.documentElement.classList.contains('dark');
        
        editor = monaco.editor.create(document.getElementById('codeEditor'), {
            value: starterCode, 
            language: 'html', 
            theme: isDark ? 'vs-dark' : 'vs', 
            fontSize: window.innerWidth < 768 ? 12 : 14,
            minimap: { enabled: false }, 
            automaticLayout: true, 
            padding: { top: 16, bottom: 16 }, 
            lineNumbers: 'on',
            scrollBeyondLastLine: false,
            wordWrap: 'on',
            formatOnPaste: true,
        });
        
        window.addEventListener('resize', () => {
            if(editor) editor.layout();
        });

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
        
        window.addEventListener('theme-toggled', () => {
            const newIsDark = document.documentElement.classList.contains('dark');
            monaco.editor.setTheme(newIsDark ? 'vs-dark' : 'vs');
            setSimFlex && setSimFlex(document.getElementById('sim1-class').innerText);
        });
    });
}

    function updatePreview(code) {
        const frame = document.getElementById('previewFrame');
        const isDark = document.documentElement.classList.contains('dark');
        const bgColor = isDark ? '#020617' : '#f8fafc'; 
        
        const content = `
        <!doctype html>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <script src="https://cdn.tailwindcss.com"><\/script>
            <style>
                body { 
                    background-color: ${bgColor}; 
                    font-family: sans-serif; 
                    transition: background-color 0.3s; 
                    margin: 0; padding: 0;
                    display: flex; align-items: flex-start; justify-content: center;
                    min-height: 100vh;
                }
                * { transition: all 0.3s ease-in-out; }
            </style>
        </head>
        <body class="w-full">
            ${code}
        </body>
        </html>`;
        
        frame.srcdoc = content;
    }

    function validateCodeRegex(code) {
        let passed = 0;
        const isDark = document.documentElement.classList.contains('dark');

        const mainNavMatch = code.match(/id="main-nav"[^>]*class="([^"]*)"/);
        const searchBoxMatch = code.match(/id="search-box"[^>]*class="([^"]*)"/);
        const profileMatch = code.match(/id="profile-group"[^>]*class="([^"]*)"/);

        const mainNavClasses = mainNavMatch ? mainNavMatch[1] : '';
        const searchClasses = searchBoxMatch ? searchBoxMatch[1] : '';
        const profileClasses = profileMatch ? profileMatch[1] : '';

        const checks = [
            { id: 'check-main', valid: /\bflex\b/.test(mainNavClasses) && /\bp-[4-8]\b/.test(mainNavClasses) },
            { id: 'check-align', valid: /\bjustify-between\b/.test(mainNavClasses) && /\bitems-center\b/.test(mainNavClasses) },
            { id: 'check-flex-1', valid: /\bflex-1\b/.test(searchClasses) },
            { id: 'check-shrink', valid: /\bshrink-0\b/.test(profileClasses) || /\bflex-none\b/.test(profileClasses) }
        ];

        checks.forEach(c => {
            const el = document.getElementById(c.id);
            if (!el) return;
            const dot = el.querySelector('span'); 
            
            if(c.valid) {
                el.classList.remove('text-slate-600', 'dark:text-white/50');
                el.classList.add(isDark ? 'text-green-400' : 'text-emerald-700', 'font-bold');
                dot.innerHTML = '✓'; 
                dot.classList.remove('border-slate-300', 'dark:border-white/20');
                dot.classList.add('bg-emerald-500', 'border-transparent', 'text-white');
                passed++;
            } else {
                el.classList.add(isDark ? 'text-white/50' : 'text-slate-600');
                el.classList.remove('text-green-400', 'text-emerald-700', 'font-bold');
                dot.innerHTML = ''; 
                dot.classList.remove('bg-emerald-500', 'border-transparent', 'text-white');
                dot.classList.add(isDark ? 'border-white/20' : 'border-slate-300');
            }
        });

        document.getElementById('progressText').innerText = passed + '/4 Selesai';
        
        const btn = document.getElementById('submitExerciseBtn');
        if (passed === 4) {
            btn.disabled = false;
            btn.classList.remove('cursor-not-allowed', 'opacity-50');
            btn.innerHTML = '<span>Simpan Hasil & Lanjut</span><svg class="w-4 h-4 sm:w-5 sm:h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
        } else {
            btn.disabled = true;
            btn.classList.add('cursor-not-allowed', 'opacity-50');
            btn.innerHTML = '<span>Selesaikan Semua Syarat Dulu</span><svg class="w-4 h-4 sm:w-5 sm:h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>';
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
            btn.classList.add('shake'); setTimeout(() => btn.classList.remove('shake'), 500);
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
            editor.setValue(`<nav class="bg-slate-900 text-white w-full border-b border-slate-700">\n  <div id="main-nav" class="flex justify-between items-center p-6 bg-slate-800/50">\n\n    <div class="font-black text-2xl text-fuchsia-500">DevSpace.</div>\n\n    <div id="search-box" class="flex-1 bg-slate-950 border border-slate-700 rounded px-4 py-2 mx-4 text-slate-400">\n      Cari modul...\n    </div>\n\n    <div id="profile-group" class="shrink-0 flex items-center gap-3">\n      <span class="hidden sm:block text-sm font-bold">Halo, Developer</span>\n      <div class="w-10 h-10 rounded-full border-2 border-fuchsia-500 bg-fuchsia-900/50"></div>\n    </div>\n\n  </div>\n</nav>`);
            validateCodeRegex(editor.getValue());
        }
    }

    function unlockNextChapter() {
        const btn = document.getElementById('nextChapterBtn');
        if(btn) {
            btn.classList.remove('cursor-not-allowed', 'opacity-50', 'pointer-events-none', 'text-muted');
            btn.classList.add('text-indigo-600', 'dark:text-indigo-400', 'cursor-pointer');
            
            document.getElementById('nextLabel').innerText = "Selanjutnya";
            document.getElementById('nextLabel').classList.remove('opacity-60', 'text-rose-500', 'dark:text-red-400');
            document.getElementById('nextLabel').classList.add('text-indigo-600', 'dark:text-indigo-400', 'opacity-100');
            
            const icon = document.getElementById('nextIcon');
            icon.innerHTML = `<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>`;
            icon.classList.remove('bg-slate-100', 'dark:bg-white/5');
            icon.classList.add('bg-indigo-100', 'dark:bg-indigo-500/20', 'border-indigo-300', 'dark:border-indigo-500/50', 'text-indigo-600', 'dark:text-indigo-400', 'shadow-lg');
            
            btn.onclick = () => window.location.href = "{{ route('courses.grid') ?? '#' }}"; 
        }
    }

    /* --- 5. SIMULATOR LOGIC (LAINNYA) --- */
    window.setSimFlex = function(type) {
        document.getElementById('sim1-class').innerText = type;
        const preview = document.getElementById('sim1-preview');
        const items = preview.querySelectorAll('.sim1-item');
        if(type === 'flex') {
            preview.className = "flex w-full border-2 border-dashed border-slate-300 dark:border-white/10 p-5 rounded-xl bg-white/50 dark:bg-white/5 gap-3 transition-all duration-500 relative z-10 shadow-sm flex-1 lg:flex-none";
            items.forEach(item => item.classList.remove('mb-3'));
        } else {
            preview.className = "block w-full border-2 border-dashed border-slate-300 dark:border-white/10 p-5 rounded-xl bg-white/50 dark:bg-white/5 transition-all duration-500 relative z-10 shadow-sm flex-1 lg:flex-none";
            items.forEach(item => item.classList.add('mb-3'));
        }
    };

    window.updateSimDir = function(val) {
        const target = document.getElementById('sim2-target');
        const code = document.getElementById('sim2-code');

        if (val === 'flex-wrap') {
            if (target.classList.contains('flex-wrap')) {
                target.classList.remove('flex-wrap');
                code.innerText = code.innerText.replace(' flex-wrap', '');
            } else {
                target.classList.add('flex-wrap');
                if(!code.innerText.includes('flex-wrap')) code.innerText += ' flex-wrap';
            }
        } else {
            target.classList.remove('flex-row', 'flex-col');
            target.classList.add(val);
            code.innerText = code.innerText.replace(/flex-row|flex-col/, val);
        }
    };

    window.updateSimAlign = function(type, val) {
        const target = document.getElementById('sim3-target');
        if (type === 'j') {
            target.classList.remove('justify-start', 'justify-center', 'justify-between', 'justify-end');
            target.classList.add(val);
            document.getElementById('sim3-label-j').innerText = val;
        } else if (type === 'i') {
            target.classList.remove('items-start', 'items-center', 'items-end', 'items-stretch');
            target.classList.add(val);
            document.getElementById('sim3-label-i').innerText = val;
        }
    };

    /* --- 7. SCROLL SPY & SIDEBAR LOGIC --- */
    function highlightAnchor(id) {
        const isDark = document.documentElement.classList.contains('dark');
        const anchors = document.querySelectorAll('.sidebar-anchor');

        anchors.forEach(a => {
            a.classList.remove('bg-slate-100', 'dark:bg-white/5', 'border-indigo-500', 'border-purple-500');
            a.classList.add('border-transparent');
            
            const dot = a.querySelector('.anchor-dot');
            const isActivity = a.dataset.type === 'activity';

            if(dot) dot.classList.remove('scale-125', 'shadow-sm', 'dark:shadow-[0_0_10px_#6366f1]', 'dark:shadow-[0_0_10px_#a855f7]', 'bg-indigo-500', 'dark:bg-indigo-400');
            
            if (isActivity) {
                if(dot) { dot.classList.remove('bg-purple-500', 'dark:bg-purple-400'); dot.classList.add('bg-slate-400', 'dark:bg-slate-600'); }
            } else {
                if(dot) { dot.classList.remove('bg-indigo-500', 'dark:bg-indigo-400'); dot.classList.add('bg-slate-400', 'dark:bg-slate-600'); }
            }

            const text = a.querySelector('.anchor-text');
            if(text) { text.classList.remove('text-slate-800', 'dark:text-white', 'font-bold'); text.classList.add('text-slate-500'); }
        });

        const activeAnchor = document.querySelector(`.sidebar-anchor[data-target="${id}"]`);
        if (activeAnchor) {
            const isActivity = activeAnchor.dataset.type === 'activity';
            
            activeAnchor.classList.add(isDark ? 'dark:bg-white/5' : 'bg-slate-100');
            activeAnchor.classList.add(isActivity ? 'border-purple-500' : 'border-indigo-500');
            activeAnchor.classList.remove('border-transparent');
            
            const dot = activeAnchor.querySelector('.anchor-dot');
            if(dot) {
                dot.classList.remove('bg-slate-400', 'dark:bg-slate-600');
                if (isActivity) {
                    dot.classList.add(isDark ? 'dark:bg-purple-400' : 'bg-purple-500', 'scale-125', isDark ? 'dark:shadow-[0_0_10px_#a855f7]' : 'shadow-sm');
                } else {
                    dot.classList.add(isDark ? 'dark:bg-indigo-400' : 'bg-indigo-500', 'scale-125', isDark ? 'dark:shadow-[0_0_10px_#6366f1]' : 'shadow-sm');
                }
            }
            
            const text = activeAnchor.querySelector('.anchor-text');
            if(text) { text.classList.remove('text-slate-500'); text.classList.add(isDark ? 'dark:text-white' : 'text-slate-800', 'font-bold'); }
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